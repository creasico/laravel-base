<?php

namespace Creasi\Base\Models;

use Creasi\Base\Models\Enums\FileUploadType;
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
 * @property null|FileUploadType $type
 * @property null|string $path
 * @property null|string $disk
 * @property string $url
 * @property bool $is_internal
 * @property null|string $summary
 * @property-read \Illuminate\Database\Eloquent\Collection<int, static> $revisions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FileAttached> $attaches
 * @property-read null|static $revisionOf
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Business> $ownedByCompanies
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $ownedByPersonnels
 *
 * @method static static store(FileUploadType $type, string|UploadedFile $path, string $name, ?string $title = null, ?string $summary = null, ?string $disk = null)
 * @method static \Database\Factories\FileUploadFactory<static> factory()
 */
class FileUpload extends Model
{
    use HasUuids;

    protected $fillable = [
        'revision_id',
        'title',
        'name',
        'path',
        'type',
        'disk',
        'summary',
    ];

    protected $casts = [
        'type' => FileUploadType::class,
    ];

    public function url(): Attribute
    {
        return Attribute::get(fn ($_, array $attrs) => $this->is_internal
            ? Storage::url($attrs['path'])
            : $attrs['path']);
    }

    public function isInternal(): Attribute
    {
        return Attribute::get(fn ($_, array $attrs) => ! \str_contains($attrs['path'], '://'));
    }

    protected function attachable(string $owner)
    {
        return $this->morphedByMany($owner, 'attachable', 'file_attached', 'file_upload_id')
            ->as('attachments');
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
        FileUploadType $type,
        string|UploadedFile $path,
        string $name,
        string $title = null,
        string $summary = null,
        string $disk = null
    ): static {
        $name = Str::slug($name);

        if ($path instanceof UploadedFile) {
            $path = $path->store($type->key().'/'.$name, $disk ?? []);
        }

        $instance = $query->newModelInstance([
            'title' => $title,
            'disk' => $disk,
            'type' => $type,
            'name' => $name,
            'path' => $path,
            'summary' => $summary,
        ]);

        $instance->save();

        return $instance;
    }

    public function createRevision(string|UploadedFile $path, string $summary = null): static
    {
        $revision = static::store($this->type, $path, $this->name, $this->title, $summary, $this->disk);

        /** @var static */
        $this->revisions()->save($revision);

        foreach ($this->attaches as $model) {
            $model->attachable->files()->sync($revision->getKey());
        }

        $this->refresh();

        return $revision;
    }
}
