<?php

namespace Creasi\Base\Models\Concerns\Factories;

use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\FileUpload;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait WithFileUpload
{
    public function withFileUpload(FileUploadType $type = null): static
    {
        return $this->hasAttached(FileUpload::factory(), [
            'type' => $type ?? \fake()->randomElement(FileUploadType::cases()),
        ], 'files');
    }
}
