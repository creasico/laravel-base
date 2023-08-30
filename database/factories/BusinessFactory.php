<?php

namespace Database\Factories;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\Business;
use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\FileUpload;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Business>
 */
class BusinessFactory extends Factory
{
    protected $model = Business::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->safeEmail(),
            'phone' => '08'.$this->faker->numerify('##########'),
            'summary' => $this->faker->sentence(4),
        ];
    }

    public function withAddress(): static
    {
        return $this->has(Address::factory());
    }

    public function withFileUpload(FileUploadType $type = null): static
    {
        return $this->hasAttached(FileUpload::factory(), [
            'type' => $type ?? $this->faker->randomElement(FileUploadType::cases()),
        ], 'files');
    }
}
