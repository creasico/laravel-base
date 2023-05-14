<?php

namespace Creasi\Laravel\Contracts;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface Accountable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|Account
     */
    public function accounts();

    public function addConnection(Accountable $accountable);
}
