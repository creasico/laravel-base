<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\FileAttached;
use Creasi\Base\Models\FileUpload;
use Illuminate\Http\UploadedFile;

/**
 * @mixin \Creasi\Base\Contracts\HasFileUploads
 */
trait WithFileUploads
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function files()
    {
        return $this->morphToMany(FileUpload::class, 'attached_to', 'file_attached', null, 'file_upload_id')
            ->using(FileAttached::class);
    }

    public function storeFile(
        FileUploadType $type,
        string|UploadedFile $path,
        string $name,
        string $title = null,
        string $summary = null,
        string $disk = null,
    ): FileUpload {
        $file = FileUpload::store($type, $path, $name, $title, $summary, $disk);

        $this->files()->attach($file);

        return $file;
    }
}
