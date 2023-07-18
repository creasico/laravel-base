<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\Identity;
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|Identity
     */
    public function avatar(): Attribute
    {
        return Attribute::get(fn () => $this->getAvatarFile?->first());
    }

    public function setAvatar(string|UploadedFile $image)
    {
        return $this->storeFile(FileUploadType::Avatar, $image, $this->getRouteKey(), 'Avatar Image');
    }

    protected function getAvatarFile()
    {
        return $this->files()->where('type', FileUploadType::Avatar);
    }
}
