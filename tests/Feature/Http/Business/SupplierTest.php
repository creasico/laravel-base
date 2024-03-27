<?php

namespace Creasi\Tests\Feature\Http\Business;

use Creasi\Base\Enums\StakeholderType;
use Creasi\Tests\Feature\Http\StakeholderTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
#[Group('supplier')]
class SupplierTest extends StakeholderTestCase
{
    protected function getRelativeType(): StakeholderType
    {
        return StakeholderType::Supplier;
    }
}
