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
        return $this->morphToMany(FileUpload::class, 'attachable', 'file_attached', null, 'file_upload_id')
            ->using(FileAttached::class)
            ->withPivot('type')
            ->as('attachment');
    }

    public function storeFile(
        FileUploadType $type,
        string|UploadedFile $path,
        string $name,
        ?string $title = null,
        ?string $summary = null,
        ?string $disk = null,
    ): FileUpload {
        $file = FileUpload::store($path, $name, $title, $summary, $disk);

        $this->files()->syncWithPivotValues($file, ['type' => $type], false);

        return $file;
    }

    public function getAttachableKey(): mixed
    {
        return $this->getAttributeValue($this->getAttachableKeyName());
    }

    public function getAttachableKeyName(): string
    {
        return 'name';
    }
}
