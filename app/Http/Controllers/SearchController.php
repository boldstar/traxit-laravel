<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {

        $client = $request->validate([
            'keyword' => 'required|string'
        ]);
        
        $results = Client::query()
        ->select(\DB::raw("*, CONCAT(first_name,' ',last_name) AS name") )       
        ->havingRaw( " name LIKE '%$request->keyword%'")
        ->with('engagements')
        ->get();

        return response()->json($results);
    }
}
