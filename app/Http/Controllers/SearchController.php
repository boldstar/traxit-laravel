<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {

        $client = $request->validate([
            'category' => 'nullable|string',
            'keyword' => 'required|string'
        ]);

        if($request->category == 'name') {
            $results = Client::query()
                    ->select(\DB::raw("*, CONCAT(first_name,' ',last_name) AS name") ) 
                    ->havingRaw( " name LIKE '%$request->keyword%'")
                    ->with('engagements')
                    ->get();

                    return response()->json($results);
        }

        else if($request->category == 'number') {
            $results = Client::where('cell_phone', $request->keyword)
                    ->orWhere('work_phone', $request->keyword)
                    ->orWhere('spouse_cell_phone', $request->keyword)
                    ->orWhere('spouse_work_phone', $request->keyword)
                    ->with('engagements')
                    ->get();

                    return response()->json($results);
        }

        else {

            $results = Client::query()
                    ->select(\DB::raw("*, CONCAT(first_name,' ',last_name) AS name") )       
                    ->havingRaw( " name LIKE '%$request->keyword%'")
                    ->with('engagements')
                    ->get();

                    return response()->json($results);
        };   
    }
}
