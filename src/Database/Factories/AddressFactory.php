<?php

namespace Creasi\Base\Database\Factories;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Enums\AddressType;
use Creasi\Nusa\Models\Village;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var Village */
        $village = Village::with('district', 'regency')
            ->whereHas('province', fn (Builder $query) => $query->where('code', 33))
            ->inRandomOrder()
            ->first();

        /** @var string */
        $types = config('creasi.base.address.types', AddressType::class);

        return [
            'type' => $this->faker->randomElement($types::cases()),
            'line' => $this->faker->streetAddress(),
            'rt' => $this->faker->numberBetween(1, 15),
            'rw' => $this->faker->numberBetween(1, 10),
            'village_code' => $village->code,
            'district_code' => $village->district->code,
            'regency_code' => $village->regency->code,
            'province_code' => $village->province->code,
            'postal_code' => $village->postal_code,
            'summary' => $this->faker->sentence(4),
        ];
    }
}
