<?php

namespace database\seeds\tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new Role();
        $role_user->name = 'User';
        $role_user->description = 'A normal User';
        $role_user->save();
        
        $role_manager = new Role();
        $role_manager->name = 'Manager';
        $role_manager->description = 'A Manager';
        $role_manager->save();

        $role_admin = new Role();
        $role_admin->name = 'Admin';
        $role_admin->description = 'A Admin';
        $role_admin->save();
    }
}    