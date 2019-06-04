<?php

namespace database\seeds\tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Tour;

class TourTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tour = new Tour();
        $tour->setup_tour = false;
        $tour->default_tour = false;
        $tour->admin_tour = false;
        $tour->save();
    }
}
