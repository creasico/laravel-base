<?php

namespace Database\Factories;

use Creasi\Base\Models\Enums\Education;
use Creasi\Base\Models\Enums\Gender;
use Creasi\Base\Models\Enums\Religion;
use Creasi\Base\Models\Identity;
use Creasi\Nusa\Models\Regency;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Identity>
 */
class IdentityFactory extends Factory
{
    protected $model = Identity::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var Gender */
        $gender = $this->faker->randomElement(Gender::cases());

        $birthPlace = Regency::query()->whereHas('province', function (Builder $query) {
            $query->where('code', 33);
        })->inRandomOrder()->first();

        return [
            'nik' => $this->faker->nik($gender->toFaker(), $birthDate = $this->faker->dateTime()),
            'prefix' => null,
            'fullname' => $this->faker->name($gender->toFaker()),
            'suffix' => null,
            'birth_date' => $birthDate->format('Y-m-d'),
            'birth_place_code' => $birthPlace->code,
            'education' => $this->faker->randomElement(Education::cases()),
            'gender' => $gender,
            'religion' => $this->faker->randomElement(Religion::cases()),
            'summary' => $this->faker->sentence(4),
        ];
    }

    public function withoutUser(): static
    {
        return $this->state([
            'user_id' => null,
        ]);
    }

    public function withGender(Gender $gender = null, mixed $start = '-30 years', mixed $until = 'now'): static
    {
        $gender = $gender ?: $this->faker->randomElement(Gender::cases());

        return $this->state(fn () => [
            'nik' => $this->faker->nik($gender->toFaker(), $birthDate = $this->faker->dateTimeBetween($start, $until)),
            'fullname' => $this->faker->name($gender->toFaker()),
            'gender' => $gender,
            'birth_date' => $birthDate->format('Y-m-d'),
        ]);
    }
}
