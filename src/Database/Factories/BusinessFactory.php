<?php

namespace Creasi\Base\Database\Factories;

use Creasi\Base\Database\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Business>
 */
class BusinessFactory extends Factory
{
    use Concerns\WithAddress;
    use Concerns\WithFileUpload;

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
