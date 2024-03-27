<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Database\Models\File;
use Creasi\Base\Database\Models\FileAttached;
use Creasi\Base\Enums\FileType;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;

/**
 * @mixin \Creasi\Base\Database\Models\Contracts\HasFiles
 */
trait WithFiles
{
    /**
     * {@inheritdoc}
     */
    public function files(): MorphToMany
    {
        return $this->morphToMany(File::class, 'attachable', 'file_attached', null, 'file_id')
            ->using(FileAttached::class)
            ->withPivot('type')
            ->as('attachment');
    }

    /**
     * {@inheritdoc}
     */
    public function storeFile(
        FileType $type,
        string|UploadedFile $path,
        string $name,
        ?string $title = null,
        ?string $summary = null,
        ?string $disk = null,
    ): File {
        $file = File::store($path, $name, $title, $summary, $disk);

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
