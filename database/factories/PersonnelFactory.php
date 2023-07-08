<?php

namespace Database\Factories;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\Enums\Gender;
use Creasi\Base\Models\Identity;
use Creasi\Base\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Personnel>
 */
class PersonnelFactory extends Factory
{
    protected $model = Personnel::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->numerify('################'),
            'name' => $this->faker->firstName(),
            'email' => $this->faker->safeEmail(),
            'phone_number' => '08'.$this->faker->numerify('##########'),
            'photo_path' => null,
            'summary' => $this->faker->sentence(4),
        ];
    }

    public function withIdentity(?Gender $gender = null): static
    {
        return $this->has(Identity::factory()->withGender($gender), 'identity')->state(fn () => [
            'name' => $this->faker->firstName($gender?->toFaker()),
        ]);
    }

    public function withAddress(): static
    {
        return $this->has(Address::factory());
    }
}
