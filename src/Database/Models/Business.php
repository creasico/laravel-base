<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Database\Models\Concerns\AsCompany;
use Creasi\Base\Database\Models\Concerns\WithTaxInfo;
use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Contracts\HasTaxInfo;

/**
 * @property-read BusinessRelative $stakeholder
 *
 * @method static \Creasi\Base\Database\Factories\BusinessFactory<Business> factory()
 */
class Business extends Entity implements Company, HasTaxInfo
{
    use AsCompany;
    use WithTaxInfo;

    protected $fillable = [];

    protected $casts = [];
}
