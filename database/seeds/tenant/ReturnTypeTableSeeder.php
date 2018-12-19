<?php

namespace database\seeds\tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\ReturnType;

class ReturnTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $return = new ReturnType();
        $return->return_type = '1040';
        $return->save();

        $return = new ReturnType();
        $return->return_type = '1040X';
        $return->save();

        $return = new ReturnType();
        $return->return_type = '1120';
        $return->save();

        $return = new ReturnType();
        $return->return_type = '1120S';
        $return->save();

        $return = new ReturnType();
        $return->return_type = '990';
        $return->save();

        $return = new ReturnType();
        $return->return_type = '1040NR';
        $return->save();

        $return = new ReturnType();
        $return->return_type = '1065';
        $return->save();
    }
}
