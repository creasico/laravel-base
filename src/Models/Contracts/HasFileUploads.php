<?php

namespace Creasi\Base\Models\Contracts;

use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\FileUpload;
use Illuminate\Http\UploadedFile;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Creasi\Base\Models\FileUpload> $files
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasFileUploads
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function files();

    public function storeFile(
        FileUploadType $type,
        string|UploadedFile $path,
        string $name,
        ?string $title = null,
        ?string $summary = null,
        ?string $disk = null,
    ): FileUpload;

    /**
     * Retrieve attachable key attribute value
     */
    public function getAttachableKey(): mixed;

    /**
     * Retrieve attachable key attribute name.
     */
    public function getAttachableKeyName(): string;
}
