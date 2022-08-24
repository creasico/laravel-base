<?php

namespace Creasi\Tests\Accounts;

use Creasi\Laravel\Accounts\Relation;
use Creasi\Tests\TestCase;

class RelationTest extends TestCase
{
    /** @test */
    public function it_should_be_true()
    {
        $this->assertTrue(\class_exists(Relation::class));
    }
}
