<?php

namespace Creasi\Tests\Accounts;

use Creasi\Laravel\Accounts\{Account, Field, Relation};
use Creasi\Tests\TestCase;

class AccountTest extends TestCase
{
    /** @test */
    public function it_should_be_true()
    {
        /** @var Account $account */
        $account = Account::factory()->create();

        $this->assertModelExists($account);
    }

    /** @test */
    public function it_should_has_contacts()
    {
        /** @var Account $account */
        $account = Account::factory()->create();
        /** @var Field $setting */
        $setting = Field::factory()->withType(Field\Type::Setting)->create();
        /** @var Field $profile */
        $profile = Field::factory()->withType(Field\Type::Profile)->create();
        /** @var Relation $setting */
        $relation = Relation::factory()->create();

        $account->settings()->attach($setting);
        $account->settings()->attach($setting);

        $account->profile()->attach($profile);
        $account->profile()->attach($profile);

        $account->relations()->attach($relation);
        $account->with(['settings', 'profile', 'relations'])->get()->toArray();
        $this->assertTrue(\class_exists(Account::class));
    }
}
