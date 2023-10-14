<?php

namespace Creasi\Base\Models\Concerns\Factories;

use Creasi\Base\Models\Business;
use Creasi\Base\Models\Enums;
use Creasi\Base\Models\Profile;
use DateTimeInterface;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait AsPersonnel
{
    public function withProfile(Enums\Gender $gender = null): static
    {
        $gender = $gender ?: \fake()->randomElement(Enums\Gender::cases());

        return $this->has(Profile::factory(), 'profile')->state(fn ($_, $user) => [
            'name' => \fake()->firstName($gender->toFaker()),
            'alias' => $user->name,
            'email' => $user->email,
            'gender' => $gender,
        ]);
    }

    public function withCompany(
        bool $primary = null,
        Enums\EmploymentType $type = null,
        Enums\EmploymentStatus $status = null,
        false|DateTimeInterface $startDate = null,
    ): static {
        if ($startDate === null) {
            $startDate = \fake()->dateTime();
        }

        return $this->hasAttached(Business::factory(), [
            'is_primary' => $primary,
            'type' => $type ?? \fake()->randomElement(Enums\EmploymentType::cases()),
            'status' => $status ?? \fake()->randomElement(Enums\EmploymentStatus::cases()),
            'start_date' => $startDate?->format('Y-m-d'),
            'finish_date' => null,
        ], 'employers');
    }
}
