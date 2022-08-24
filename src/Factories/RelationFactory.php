<?php

namespace Creasi\Laravel\Factories;

use Creasi\Laravel\Accounts\{Account, Relation};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Relation>
 */
class RelationFactory extends Factory
{
    protected $model = Relation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'account_id' => null,
            'related_id' => null,
        ];
    }

    public function withAccount(Account $account)
    {
        return $this->state(['account_id' => $account->getKey()]);
    }
}
