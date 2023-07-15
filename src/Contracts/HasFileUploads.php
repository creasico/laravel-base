<?php

namespace Creasi\Base\Contracts;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Creasi\Base\Models\FileUpload> $files
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasFileUploads
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|\Creasi\Base\Models\FileUpload
     */
    public function files();
}
