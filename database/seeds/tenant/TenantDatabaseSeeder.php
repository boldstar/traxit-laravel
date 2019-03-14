<?php

namespace database\seeds\tenant;

use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(RuleTableSeeder::class);
        $this->call(ReturnTypeTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(EmailTemplateTableSeeder::class);
    }
}
