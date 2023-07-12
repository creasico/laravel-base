<?php

namespace Database\Factories;

use Creasi\Base\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
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
            'name' => Str::slug($title),
            'path' => null,
            'drive' => null,
            'summary' => $this->faker->sentence(4),
        ];
    }

    public function asRevisionOf(File $document): static
    {
        return $this->state(fn () => [
            'revision_id' => $document->getKey(),
        ]);
    }
}
