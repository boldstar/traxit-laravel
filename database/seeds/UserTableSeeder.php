<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = Role::where('name', 'User')->first();
        $role_manager = Role::where('name', 'Manager')->first();
        $role_admin = Role::where('name', 'Admin')->first();


        $user = new User();
        $user->name = 'User';
        $user->email = 'visitor@example.com';
        $user->password = bcrypt('visitor');
        $user->save();
        $user->roles()->attach($role_user);

        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@example.com';
        $admin->password = bcrypt('secret');
        $admin->save();
        $admin->roles()->attach($role_admin);

        $manager = new User();
        $manager->name = 'Manager';
        $manager->email = 'author@example.com';
        $manager->password = bcrypt('author');
        $manager->save();
        $manager->roles()->attach($role_manager);
    }
}
