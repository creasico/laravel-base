<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\FileUpload;
use Creasi\Base\Models\FileAttached;

/**
 * @mixin \Creasi\Base\Contracts\HasFileUploads
 */
trait WithFileUploads
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this->morphToMany(FileUpload::class, 'attached_to', 'file_attached', null, 'file_upload_id')
            ->using(FileAttached::class);
    }
}
