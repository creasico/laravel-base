<?php

namespace Database\Factories;

use Creasi\Base\Models\Business;
use Creasi\Base\Models\Concerns\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Business>
 */
class BusinessFactory extends Factory
{
    use Factories\WithAddress;
    use Factories\WithFileUpload;

    protected $model = Business::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->safeEmail(),
            'phone' => '08'.$this->faker->numerify('##########'),
            'summary' => $this->faker->sentence(4),
        ];
    }
}
