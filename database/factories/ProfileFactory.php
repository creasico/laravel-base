<?php

namespace Database\Factories;

use Creasi\Base\Models\Enums\Education;
use Creasi\Base\Models\Enums\Religion;
use Creasi\Base\Models\Enums\TaxStatus;
use Creasi\Base\Models\Profile;
use Creasi\Nusa\Models\Regency;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $birthPlace = Regency::query()->whereHas('province', function (Builder $query) {
            $query->where('code', 33);
        })->inRandomOrder()->first();

        return [
            'nik' => $nik = $this->faker->nik(null, $birthDate = $this->faker->dateTime()),
            'birth_date' => $birthDate->format('Y-m-d'),
            'birth_place_code' => $birthPlace->code,
            'education' => $this->faker->randomElement(Education::cases()),
            'religion' => $this->faker->randomElement(Religion::cases()),
            'tax_status' => $this->faker->randomElement(TaxStatus::cases()),
            'tax_id' => $nik,
        ];
    }
}