<?php

namespace Creasi\Tests;

use Creasi\Laravel\Accounts\Repository;

class AccountsTest extends TestCase
{
    /** @test */
    public function it_should_be_true()
    {
        $accounts = $this->app->get(Repository::class);

        $this->assertInstanceOf(Repository::class, $accounts);
    }
}
