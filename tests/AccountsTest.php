<?php

namespace Creasi\Tests;

use Creasi\Laravel\Accounts;

class AccountsTest extends TestCase
{
    /** @test */
    public function it_should_be_true()
    {
        $account = $this->app->get('creasi.accounts');

        $this->assertInstanceOf(Accounts::class, $account);

        $this->assertEquals('Lorem ipsum', $account->lorem());
    }
}
