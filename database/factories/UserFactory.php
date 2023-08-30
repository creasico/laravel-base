<?php

namespace Database\Factories;

use Creasi\Base\Models\Personnel;
use Creasi\Base\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

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

    public function withIdentity(\Closure $cb = null): static
    {
        if (null === $cb) {
            $cb = fn (PersonnelFactory $identity) => $identity->withProfile();
        }

        return $this->has($cb(Personnel::factory()), 'identity');
    }
}
