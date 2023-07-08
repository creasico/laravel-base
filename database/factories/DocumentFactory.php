<?php

namespace Database\Factories;

use Creasi\Base\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

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

    public function asRevisionOf(Document $document): static
    {
        return $this->state(fn () => [
            'revision_id' => $document->getKey(),
        ]);
    }
}
