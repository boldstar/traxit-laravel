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

        // $client = $q->select(\DB::raw("CONCAT(first_name,' ',last_name) AS name") )
        // ->havingRaw( " name LIKE '%$request->keyword%'")
        // ->get();

        $client = Client::where('first_name', 'LIKE', '%'.$request->keyword.'%')
        ->orWhere('spouse_first_name', 'LIKE', '%'.$request->keyword.'%')
        ->with('engagements')
        ->get();

        return response()->json($client);
    }
}
