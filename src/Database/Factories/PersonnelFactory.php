<?php

namespace Creasi\Base\Database\Factories;

use Creasi\Base\Database\Models\Business;
use Creasi\Base\Database\Models\Personnel;
use Creasi\Base\Enums\EmploymentStatus;
use Creasi\Base\Enums\Gender;
use Creasi\Base\Enums\StakeholderStatus;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Nusa\Models\Regency;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Personnel>
 */
class PersonnelFactory extends Factory
{
    use Concerns\WithAddress;
    use Concerns\WithFileUpload;

    protected $model = Personnel::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var Regency */
        $birthPlace = Regency::query()
            ->whereHas('province', fn (Builder $query) => $query->where('code', 33))
            ->inRandomOrder()
            ->first();

        /** @var Gender */
        $gender = $this->faker->randomElement(Gender::cases());

        return [
            'name' => $this->faker->firstName($gender->toFaker()),
            'email' => $this->faker->safeEmail(),
            'phone' => '08'.$this->faker->numerify('##########'),
            'gender' => $gender,
            'nik' => $this->faker->nik($gender->toFaker(), $birthDate = $this->faker->dateTime()),
            'birth_date' => $birthDate->format('Y-m-d'),
            'birth_place_code' => $birthPlace->code,
            'summary' => $this->faker->sentence(4),
        ];
    }

    public function withoutUser(): static
    {
        return $this->state([
            'user_id' => null,
        ]);
    }

    public function withCompany(
        ?bool $primary = null,
        ?StakeholderType $type = null,
        ?StakeholderStatus $status = null,
        ?EmploymentStatus $employmentStatus = null,
        false|DateTimeInterface|null $startDate = null,
    ): static {
        if ($startDate === null) {
            $startDate = \fake()->dateTime();
        }

        return $this->hasAttached(Business::factory(), [
            'is_primary' => $primary,
            'type' => $type ?? \fake()->randomElement(StakeholderType::cases()),
            'status' => $status ?? \fake()->randomElement(StakeholderStatus::cases()),
            'employment_status' => $employmentStatus ?? \fake()->randomElement(EmploymentStatus::cases()),
            'start_date' => $startDate?->format('Y-m-d'),
            'finish_date' => null,
        ], 'employers');
    }

    public function asEmployee(?EmploymentStatus $status = null, bool $isPrimary = true): static
    {
        return $this->withCompany(
            $isPrimary,
            StakeholderType::Employee,
            StakeholderStatus::Permanent,
            $status ?: EmploymentStatus::Fulltime
        );
    }
}
