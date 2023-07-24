<?php

namespace Database\Factories;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\Business;
use Creasi\Base\Models\Enums\EmploymentStatus;
use Creasi\Base\Models\Enums\EmploymentType;
use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\Enums\Gender;
use Creasi\Base\Models\FileUpload;
use Creasi\Base\Models\Personnel;
use Creasi\Base\Models\Profile;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Personnel>
 */
class PersonnelFactory extends Factory
{
    protected $model = Personnel::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var Gender */
        $gender = $this->faker->randomElement(Gender::cases());

        return [
            'name' => $this->faker->firstName($gender->toFaker()),
            'email' => $this->faker->safeEmail(),
            'phone' => '08'.$this->faker->numerify('##########'),
            'gender' => $gender,
            'summary' => $this->faker->sentence(4),
        ];
    }

    public function withoutUser(): static
    {
        return $this->state([
            'user_id' => null,
        ]);
    }

    public function withProfile(Gender $gender = null): static
    {
        return $this->has(Profile::factory(), 'profile')->state(fn () => [
            'name' => $this->faker->firstName($gender?->toFaker()),
        ]);
    }

    public function withCompany(
        bool $primary = null,
        EmploymentType $type = null,
        EmploymentStatus $status = null,
        false|DateTimeInterface $startDate = null,
    ): static {
        if (null === $startDate) {
            $startDate = $this->faker->dateTime();
        }

        return $this->hasAttached(Business::factory(), [
            'is_primary' => $primary,
            'type' => $type ?? $this->faker->randomElement(EmploymentType::cases()),
            'status' => $status ?? $this->faker->randomElement(EmploymentStatus::cases()),
            'start_date' => $startDate?->format('Y-m-d'),
            'finish_date' => null,
        ], 'employers');
    }

    public function withAddress(): static
    {
        return $this->has(Address::factory());
    }

    public function withFileUpload(FileUploadType $type = null): static
    {
        return $this->hasAttached(FileUpload::factory(), [
            'type' => $type ?? $this->faker->randomElement(FileUploadType::cases()),
        ], 'files');
    }
}
