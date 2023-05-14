<?php

namespace Creasi\Laravel\Factories;

use Creasi\Laravel\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    protected $model = Account::class;

    /**
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $name = $this->faker->words(2, true),
            'slug' => Str::slug($name),
            'display' => $name,
            'summary' => $this->faker->sentence(2),
        ];
    }

    /**
     * @param mixed $gender
     * @return static
     */
    public function asPerson($gender = null)
    {
        return $this->state(fn (array $attributes) => [
            'name' => $attributes['name'] ?? $this->faker->name($gender)
        ]);
    }

    /**
     * @return static
     */
    public function asCompany()
    {
        return $this->state(fn (array $attributes) => [
            'name' => $attributes['name'] ?? $this->faker->company()
        ]);
    }
}
