<?php

namespace Workbench\Database\Seeders;

use Creasi\Base\Database\Models\Person;
use Illuminate\Database\Seeder;
use Workbench\App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createInitialUser();
    }

    private function createInitialUser(): void
    {
        User::factory()->withProfile(
            Person::factory()->asEmployee()
        )->createOne([
            'name' => 'creasi',
            'email' => 'developers@creasi.dev',
        ]);
    }
}
