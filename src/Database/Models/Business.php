<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Database\Models\Concerns\AsCompany;
use Creasi\Base\Database\Models\Contracts\Company;

/**
 * @property-read BusinessRelative $stakeholder
 *
 * @method static \Creasi\Base\Database\Factories\BusinessFactory<Business> factory()
 */
class Business extends Entity implements Company
{
    use AsCompany;

    protected $fillable = [];

    protected $casts = [];
}
