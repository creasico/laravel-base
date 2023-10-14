<?php

namespace Creasi\Base\Models\Factories;

use Creasi\Base\Models\Concerns\Factories;
use Creasi\Base\Models\Enums\Gender;
use Creasi\Base\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Personnel>
 */
class PersonnelFactory extends Factory
{
    use Factories\AsPersonnel;
    use Factories\WithAddress;
    use Factories\WithFileUpload;

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
}
