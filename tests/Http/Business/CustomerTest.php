<?php

namespace Creasi\Tests\Http\Business;

use Creasi\Base\Enums\BusinessRelativeType;
use Creasi\Tests\Http\StakeholderTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
#[Group('customer')]
class CustomerTest extends StakeholderTestCase
{
    protected function getRelativeType(): BusinessRelativeType
    {
        return BusinessRelativeType::Customer;
    }
}
