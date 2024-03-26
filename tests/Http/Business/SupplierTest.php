<?php

namespace Creasi\Tests\Http\Business;

use Creasi\Base\Enums\BusinessRelativeType;
use Creasi\Tests\Http\StakeholderTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
#[Group('supplier')]
class SupplierTest extends StakeholderTestCase
{
    protected function getRelativeType(): BusinessRelativeType
    {
        return BusinessRelativeType::Supplier;
    }
}
