<?php

namespace Database\Factories;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->numerify('######'),
            'name' => $this->faker->company(),
            'email' => $this->faker->safeEmail(),
            'phone_number' => '08'.$this->faker->numerify('##########'),
            'summary' => $this->faker->sentence(4),
        ];
    }

    public function withAddress(): static
    {
        return $this->has(Address::factory());
    }
}
