<?php

namespace database\seeds\tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Rule;

class RuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rule = new Rule();
        $rule->role_id = 3;
        $rule->action = 'manage';
        $rule->subject = 'all';
        $rule->save();

        $rule = new Rule();
        $rule->role_id = 2;
        $rule->action = 'create';
        $rule->subject = 'all';
        $rule->save();

        $rule = new Rule();
        $rule->role_id = 2;
        $rule->action = 'read';
        $rule->subject = 'all';
        $rule->save();

        $rule = new Rule();
        $rule->role_id = 2;
        $rule->action = 'update';
        $rule->subject = 'all';
        $rule->save();

        $rule = new Rule();
        $rule->role_id = 1;
        $rule->action = 'create';
        $rule->subject = 'all';
        $rule->save();

        $rule = new Rule();
        $rule->role_id = 1;
        $rule->action = 'read';
        $rule->subject = 'all';
        $rule->save();

        $rule = new Rule();
        $rule->role_id = 1;
        $rule->action = 'update';
        $rule->subject = 'all';
        $rule->save();

    }
}
