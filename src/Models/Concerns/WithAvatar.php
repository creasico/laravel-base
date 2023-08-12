<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Enums\FileUploadType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;

/**
 * @property-read null|\Creasi\Base\Models\FileUpload $avatar
 *
 * @mixin \Creasi\Base\Contracts\HasFileUploads
 */
trait WithAvatar
{
    /**
     * Initialize the trait.
     */
    final protected function initializeWithAvatar(): void
    {
        $this->append('avatar');

        $this->makeHidden('avatarFile');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|\Creasi\Base\Models\FileUpload
     */
    public function avatar(): Attribute
    {
        $this->loadMissing('avatarFile');

        return Attribute::get(fn () => $this->avatarFile?->first());
    }

    public function setAvatar(string|UploadedFile $image)
    {
        return $this->storeFile(FileUploadType::Avatar, $image, $this->getRouteKey(), 'Avatar Image');
    }

    public function avatarFile()
    {
        return $this->files()->wherePivot('type', '=', FileUploadType::Avatar);
    }
}