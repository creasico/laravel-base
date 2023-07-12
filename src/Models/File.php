<?php

namespace Creasi\Base\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property null|int $revision_id
 * @property null|string $title
 * @property string $name
 * @property null|string $path
 * @property null|string $drive
 * @property null|string $summary
 * @property-read \Illuminate\Database\Eloquent\Collection<int, static> $revisions
 * @property-read null|static $revisionOf
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $ownedByCompanies
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $ownedByPersonnels
 *
 * @method static \Database\Factories\FileFactory<static> factory()
 */
class File extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'revision_id',
        'title',
        'name',
        'path',
        'drive',
        'summary',
    ];

    protected $casts = [
        // .
    ];

    protected function attachedTo(string $owner)
    {
        return $this->morphedByMany($owner, 'attached_to', 'file_attached', 'file_id')
            ->as('attachments');
    }

    public function ownedByCompanies()
    {
        return $this->attachedTo(Company::class);
    }

    public function ownedByPersonnels()
    {
        return $this->attachedTo(Personnel::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|static
     */
    public function revisions()
    {
        return $this->hasMany(static::class, 'revision_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|static
     */
    public function revisionOf()
    {
        return $this->belongsTo(static::class, 'revision_id');
    }

    public function addRevision(string $filePath, ?string $name = null, ?string $summary = null): static
    {
        return $this->revisions()->create([
            'title' => $this->title,
            'drive' => $this->drive,
            'name' => $name ?: $this->name,
            'path' => $filePath,
            'summary' => $summary,
        ]);
    }
}
