<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Base\Enums\StakeholderType;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
#[Group('suppliers')]
class SupplierTest extends StakeholderTestCase
{
    protected function getRelativeType(): StakeholderType
    {
        return StakeholderType::Supplier;
    }
}
