<?php

namespace Creasi\Base\Database\Models\Contracts;

use Creasi\Base\Database\Models\File;
use Creasi\Base\Enums\FileType;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, File> $files
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasFiles
{
    /**
     * @return MorphToMany|File
     */
    public function files(): MorphToMany;

    public function storeFile(
        FileType $type,
        string|UploadedFile $path,
        string $name,
        ?string $title = null,
        ?string $summary = null,
        ?string $disk = null,
    ): File;

    /**
     * Retrieve attachable key attribute value
     */
    public function getAttachableKey(): mixed;

    /**
     * Retrieve attachable key attribute name.
     */
    public function getAttachableKeyName(): string;
}
