<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Tour;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tour::all();
    }

    /**
     * complete the setup tour
     */
    public function completeSetup() 
    {
        $tour = Tour::first();
        $tour->setup_tour = true;
        $tour->save();

        return response($tour);
    }

}
