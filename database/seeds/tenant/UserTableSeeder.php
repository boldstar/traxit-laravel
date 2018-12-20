<?php

namespace database\seeds\tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\User;
use App\Models\Tenant\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'Admin')->first();


        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@example.com';
        $admin->password = bcrypt('secretadmin');
        $admin->save();
        $admin->roles()->attach($role_admin);

    }
}
