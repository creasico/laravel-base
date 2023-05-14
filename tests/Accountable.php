<?php

namespace Creasi\Tests;

use Creasi\Laravel\Concerns\WithAccounts;
use Creasi\Laravel\Contracts\Accountable as AccountableContract;
use Illuminate\Database\Eloquent\Model;

class Accountable extends Model implements AccountableContract
{
    use WithAccounts;

    protected $fillable = [];
}
