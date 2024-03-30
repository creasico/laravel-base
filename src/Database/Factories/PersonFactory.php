<?php

namespace Creasi\Base\Database\Factories;

use Creasi\Base\Database\Models\Organization;
use Creasi\Base\Database\Models\Person;
use Creasi\Base\Enums\Gender;
use Creasi\Base\Enums\PersonnelStatus;
use Creasi\Base\Enums\PersonRelativeStatus;
use Creasi\Base\Enums\StakeholderStatus;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Nusa\Models\Regency;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Person>
 */
class PersonFactory extends Factory
{
    use Concerns\WithAddress;
    use Concerns\WithFiles;

    protected $model = Person::class;

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
        $gender = \fake()->randomElement(Gender::cases());

        return [
            'user_id' => null,
            'name' => \fake()->firstName($gender->toFaker()),
            'email' => \fake()->safeEmail(),
            'phone' => '08'.\fake()->numerify('##########'),
            'gender' => $gender,
            'nik' => \fake()->nik($gender->toFaker(), $birthDate = \fake()->dateTimeInInterval('-25 years', '+5 years')),
            'birth_date' => $birthDate->format('Y-m-d'),
            'birth_place_code' => $birthPlace->code,
            'summary' => \fake()->sentence(4),
        ];
    }

    public function withCredental(): static
    {
        return $this->state([
            'user_id' => null,
        ]);
    }

    /**
     * @param  \DateTime|int|string  $date
     * @param  \DateTime|int|string  $interval
     */
    protected function withGender(Gender $gender, $date = '-25 years', $interval = '+5 years'): static
    {
        return $this->state([
            'gender' => $gender,
            'nik' => \fake()->nik($gender->toFaker(), $birthDate = \fake()->dateTimeInInterval($date, $interval)),
            'birth_date' => $birthDate->format('Y-m-d'),
        ]);
    }

    /**
     * @param  \DateTime|int|string  $date
     * @param  \DateTime|int|string  $interval
     */
    public function male($date = '-25 years', $interval = '+5 years'): static
    {
        return $this->withGender(Gender::Male, $date, $interval);
    }

    /**
     * @param  \DateTime|int|string  $date
     * @param  \DateTime|int|string  $interval
     */
    public function female($date = '-25 years', $interval = '+5 years'): static
    {
        return $this->withGender(Gender::Female, $date, $interval);
    }

    public function withRelative(PersonFactory $relative, PersonRelativeStatus $status): static
    {
        return $this->hasAttached($relative, ['status' => $status], 'relatives');
    }

    public function withOrganization(
        ?bool $primary = null,
        ?StakeholderType $type = null,
        ?StakeholderStatus $status = null,
        ?PersonnelStatus $employmentStatus = null,
        ?DateTimeInterface $startDate = null,
        ?DateTimeInterface $finishDate = null,
    ): static {
        return $this->hasAttached(Organization::factory(), [
            'is_primary' => $primary,
            'type' => $type ?? \fake()->randomElement(StakeholderType::cases()),
            'status' => $status ?? \fake()->randomElement(StakeholderStatus::cases()),
            'personnel_status' => $employmentStatus ?? \fake()->randomElement(PersonnelStatus::cases()),
            'start_date' => ($startDate ?: \fake()->dateTimeBetween('-2 years', '-5 months'))->format('Y-m-d'),
            'finish_date' => $finishDate,
        ], 'employers');
    }

    public function asEmployee(?PersonnelStatus $status = null, bool $isPrimary = true): static
    {
        return $this->withOrganization(
            $isPrimary,
            StakeholderType::Employee,
            StakeholderStatus::Permanent,
            $status ?: PersonnelStatus::Fulltime
        );
    }
}
