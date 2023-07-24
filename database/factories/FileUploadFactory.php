<?php

namespace Database\Factories;

use Creasi\Base\Models\FileUpload;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;

/**
 * @extends Factory<FileUpload>
 */
class FileUploadFactory extends Factory
{
    protected $model = FileUpload::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'revision_id' => null,
            'title' => $title = $this->faker->word(),
            'name' => $name = Str::slug($title),
            'path' => File::fake()->create($name)->path(),
            'disk' => null,
            'summary' => $this->faker->sentence(4),
        ];
    }

    public function asRevisionOf(FileUpload $file): static
    {
        return $this->state(fn () => [
            'revision_id' => $file->getKey(),
        ]);
    }

    public function withoutFile(): static
    {
        return $this->state(fn () => [
            'path' => null,
        ]);
    }
}
