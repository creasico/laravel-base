<?php

namespace Creasi\Laravel\Factories;

use Creasi\Laravel\Accounts\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => null,
            'name' => $this->faker->words(3, true),
            'slug' => null,
            'display' => null,
            'summary' => $this->faker->sentence(2),
        ];
    }
}
