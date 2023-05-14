<?php

namespace Creasi\Laravel\Concerns;

use Creasi\Laravel\Contracts\Accountable;
use Creasi\Laravel\Models\Account;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @property-read Account[]|\Illuminate\Database\Eloquent\Collection<int, Account> $accounts
 */
trait WithAccounts
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|Account
     */
    public function accounts()
    {
        return $this->morphToMany(Account::class, 'accountable');
    }

    public function addConnection(Accountable $accountable)
    {
        $this->accounts->first()->connections()->attach($accountable->first());
    }
}
