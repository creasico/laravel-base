<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Base\Enums\StakeholderType;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
#[Group('customers')]
class CustomerTest extends StakeholderTestCase
{
    protected function getRelativeType(): StakeholderType
    {
        return StakeholderType::Customer;
    }
}
