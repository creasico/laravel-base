<?php

namespace Creasi\Base\Database\Factories\Concerns;

use Creasi\Base\Database\Models\FileUpload;
use Creasi\Base\Enums\FileUploadType;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait WithFileUpload
{
    public function withFileUpload(?FileUploadType $type = null): static
    {
        return $this->hasAttached(FileUpload::factory(), [
            'type' => $type ?? \fake()->randomElement(FileUploadType::cases()),
        ], 'files');
    }
}
