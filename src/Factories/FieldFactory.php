<?php

namespace Creasi\Laravel\Factories;

use Creasi\Laravel\Accounts\{Account, Field};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    protected $model = Field::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => null,
            'key' => Str::slug($this->faker->words(2, true)),
            'account_id' => null,
            'cast' => null,
            'payload' => null,
        ];
    }

    public function withType(Field\Type $type)
    {
        return $this->state(['type' => $type]);
    }

    public function withPayload(array $payload)
    {
        return $this->state(['payload' => $payload]);
    }

    public function withAccount(Account $account)
    {
        return $this->state(['account_id' => $account->getKey()]);
    }
}
