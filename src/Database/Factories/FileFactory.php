<?php

namespace Creasi\Base\Database\Factories;

use Creasi\Base\Database\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Testing\File as FileTesting;
use Illuminate\Support\Str;

/**
 * @extends Factory<File>
 */
class FileFactory extends Factory
{
    protected $model = File::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'revision_id' => null,
            'title' => $title = $this->faker->word(),
            'name' => $name = Str::slug($title),
            'path' => FileTesting::fake()->create($name)->path(),
            'disk' => null,
            'summary' => $this->faker->sentence(4),
        ];
    }

    public function asRevisionOf(File $file): static
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
