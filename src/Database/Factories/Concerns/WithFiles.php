<?php

namespace Creasi\Base\Database\Factories\Concerns;

use Creasi\Base\Database\Models\File;
use Creasi\Base\Enums\FileType;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait WithFiles
{
    public function withFileUpload(?FileType $type = null): static
    {
        return $this->hasAttached(File::factory(), [
            'type' => $type ?? \fake()->randomElement(FileType::cases()),
        ], 'files');
    }
}
