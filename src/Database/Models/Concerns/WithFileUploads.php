<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Database\Models\FileAttached;
use Creasi\Base\Database\Models\FileUpload;
use Creasi\Base\Enums\FileUploadType;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;

/**
 * @mixin \Creasi\Base\Contracts\HasFileUploads
 */
trait WithFileUploads
{
    /**
     * {@inheritdoc}
     */
    public function files(): MorphToMany
    {
        return $this->morphToMany(FileUpload::class, 'attachable', 'file_attached', null, 'file_upload_id')
            ->using(FileAttached::class)
            ->withPivot('type')
            ->as('attachment');
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getAttachableKey(): mixed
    {
        return $this->getAttributeValue($this->getAttachableKeyName());
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachableKeyName(): string
    {
        return 'name';
    }
}
