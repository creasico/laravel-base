<?php

namespace Creasi\Tests\Accounts;

use Creasi\Laravel\Accounts\Field;
use Creasi\Tests\TestCase;

class FieldTest extends TestCase
{
    /** @test */
    public function it_should_be_true()
    {
        $this->assertTrue(\class_exists(Field::class));
    }
}
