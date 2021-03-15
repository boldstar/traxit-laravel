<?php

namespace database\seeds\tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting();
        $setting->name = 'contact_categories';
        $setting->save();
    }
}

