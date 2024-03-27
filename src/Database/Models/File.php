<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Database\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property null|int $revision_id
 * @property null|string $title
 * @property string $name
 * @property string $path
 * @property null|string $disk
 * @property string $url
 * @property bool $is_internal Determine whether the file is actually stored internally or its an external link.
 * @property null|string $summary
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FileAttached> $attaches
 * @property-read \Illuminate\Database\Eloquent\Collection<int, static> $revisions
 * @property-read null|static $revisionOf
 * @property-read FileAttached $attachment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Business> $ownedByCompanies
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $ownedByPersonnels
 *
 * @method static static store(string|UploadedFile $path, string $name, ?string $title = null, ?string $summary = null, ?string $disk = null)
 * @method static \Creasi\Base\Database\Factories\FileFactory<File> factory()
 */
class File extends Model
{
    use HasUuids;

    protected $fillable = [
        'revision_id',
        'title',
        'name',
        'path',
        'disk',
        'summary',
    ];

    protected $casts = [];

    protected $appends = ['url', 'is_internal'];

    public function url(): Attribute
    {
        return Attribute::get(function () {
            $path = $this->getAttributeValue('path');

            if (! $path) {
                return null;
            }

            if (! $this->is_internal) {
                return $path;
            }

            /** @var \Illuminate\Contracts\Filesystem\Cloud */
            $storage = Storage::disk($this->disk);

            return $storage->url(\ltrim($path, '/'));
        });
    }

    /**
     * Determine whether the file is actually stored internally or its an external link.
     */
    public function isInternal(): Attribute
    {
        return Attribute::get(function () {
            if ($path = $this->getAttributeValue('path')) {
                return ! \str_contains($path, '://');
            }

            return null;
        });
    }

    protected function attachable(string $owner)
    {
        return $this->morphedByMany($owner, 'attachable', 'file_attached', 'file_id')
            ->as('attachment');
    }

    public function ownedByCompanies()
    {
        return $this->attachable(Business::class);
    }

    public function ownedByPersonnels()
    {
        return $this->attachable(Personnel::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|static
     */
    public function revisions()
    {
        return $this->hasMany(static::class, 'revision_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|FileAttached
     */
    public function attaches()
    {
        return $this->hasMany(FileAttached::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|static
     */
    public function revisionOf()
    {
        return $this->belongsTo(static::class, 'revision_id');
    }

    public function scopeStore(
        Builder $query,
        string|UploadedFile $path,
        string $name,
        ?string $title = null,
        ?string $summary = null,
        ?string $disk = null
    ): static {
        $name = Str::slug($name);

        if ($path instanceof UploadedFile) {
            $path = $path->store($name, $disk ?? []);
        }

        $instance = $query->newModelInstance([
            'title' => $title,
            'disk' => $disk,
            'name' => $name,
            'path' => $path,
            'summary' => $summary,
        ]);

        $instance->save();

        return $instance;
    }

    public function createRevision(string|UploadedFile $path, ?string $summary = null): static
    {
        $revision = static::store($path, $this->name, $this->title, $summary, $this->disk);

        /** @var static */
        $this->revisions()->save($revision);

        foreach ($this->attaches as $model) {
            $model->attachable->files()->sync($revision->getKey());
        }

        $this->refresh();

        return $revision;
    }
}
