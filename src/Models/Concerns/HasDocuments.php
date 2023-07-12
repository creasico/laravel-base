<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\File;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, File> $files
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasDocuments
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this->morphToMany(File::class, 'attached_to', 'file_attached', null, 'file_id');
    }
}
