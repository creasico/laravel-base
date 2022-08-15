<?php

namespace Creasi\Tests;

use Creasi\Laravel\Account;

class AccountTest extends TestCase
{
    /** @test */
    public function it_should_be_true()
    {
        $account = $this->app->get('creasi.account');

        $this->assertInstanceOf(Account::class, $account);

        $this->assertEquals('Lorem ipsum', $account->lorem());
    }
}
