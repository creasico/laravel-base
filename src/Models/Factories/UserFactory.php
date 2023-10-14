<?php

namespace Creasi\Base\Models\Factories;

use Creasi\Base\Models\Concerns\Factories\WithIdentity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Model\User>
 */
class UserFactory extends Factory
{
    use WithIdentity;

    public function modelName()
    {
        return app('creasi.base.user_model');
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = Str::slug($this->faker->name()),
            'email' => str($name)->append('@example.com'),
            'email_verified_at' => now(),
            'password' => 'secret',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state([
            'email_verified_at' => null,
        ]);
    }
}
