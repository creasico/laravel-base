<?php

namespace Creasi\Tests\Models;

use Creasi\Laravel\Contracts\Accountable as AccountableContract;
use Creasi\Laravel\Factories\AccountFactory;
use Creasi\Laravel\Models\Account;
use Creasi\Tests\Accountable;
use Creasi\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Mockery\MockInterface;

class AccountTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function defineDatabaseMigrations()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('accountable_test', function (Blueprint $table) {
            $table->id();
        });
    }

    protected function destroyDatabaseMigrations()
    {
        $this->app['db']->connection()->getSchemaBuilder()->dropIfExists('accountable_test');
    }

    public function factoriesProvider()
    {
        $factory = Account::factory();

        return [
            [$factory],
            [$factory->asPerson()],
            [$factory->asCompany()],
        ];
    }

    /**
     * @test
     * @dataProvider factoriesProvider
     */
    public function should_get_name_as_display_by_default(AccountFactory $factory)
    {
        $account = $factory->create($attrs = ['display' => null]);

        $this->assertSame($account->name, $account->display);
        $this->assertDatabaseHas($account, $attrs);
    }

    /**
     * @test
     * @dataProvider factoriesProvider
     */
    public function should_set_slug_with_name_by_default(AccountFactory $factory)
    {
        $account = $factory->create(['slug' => null]);

        $this->assertSame($slug = Str::slug($account->name), $account->slug);
        $this->assertDatabaseHas($account, ['slug' => $slug]);
    }

    /**
     * @test
     */
    public function should_has_connections()
    {
        [$employee, $person] = $this->createAccountable();
        [$employer, $company] = $this->createAccountable();

        $employee->addConnection($employer);

        $this->assertDatabaseHas('account_connections', [
            'account_id' => $person->getKey(),
            'connected_id' => $company->getKey(),
        ]);
    }

    protected function createAccountable(): array
    {
        $account = Account::factory()->asCompany()->create();
        /** @var \Creasi\Laravel\Contracts\Accountable $accountable */
        $accountable = new class extends Accountable {
            protected $table = 'accountable_test';
        };

        $accountable->accounts()->attach($account);

        return [$accountable, $account];
    }
}
