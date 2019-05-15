<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Client;
use Illuminate\Http\Request;
use App\Imports\ClientsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Business;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Client::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clientWithBusinesses()
    {
        return Client::with('businesses')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'active' => 'required|boolean',
            'category' => 'required|string',
            'referral_type' => 'required|string',
            'first_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'last_name' => 'required|string',
            'occupation' => 'nullable|string',
            'dob' => 'nullable|string',
            'email' => 'nullable|string',
            'cell_phone' => 'nullable|string',
            'work_phone' => 'nullable|string',
            'has_spouse' => 'required|boolean',
            'spouse_first_name' => 'nullable|string',
            'spouse_middle_initial' => 'nullable|string',
            'spouse_last_name' => 'nullable|string',
            'spouse_occupation' => 'nullable|string',
            'spouse_dob' => 'nullable|string',
            'spouse_email' => 'nullable|string',
            'spouse_cell_phone' => 'nullable|string',
            'spouse_work_phone' => 'nullable|string',
            'street_address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        $client = Client::create($data);

        return response()->json([ 'contact' => $client, 'message' => 'Contact Succesfully Added'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::with('dependents', 'businesses')->find($id);

        return response($client);
    }

    public function importExcel(Request $request) 
    {

        try {
            if (empty($request->file('file')->getRealPath())) {
                return back()->with('success','No file selected');
            } else {
                //import client using ClientsImport from imports folder
                Excel::import(new ClientsImport, $request->file('file'));
            }
        }catch(\Exception $e) {
            return response()->json(['message' => 'Oops, something went wrong. It appears that there was a formatting issue. Make sure that first row of spreadsheet does not contain a header. Only contact information should be present.']);
        }

        $clients = Client::all();
        return response()->json(['message' => 'Upload was succesful', 'clients' => $clients]);
    }

    public function importExcelOnSetup(Request $request) 
    {   
        try {
            if (empty($request->file('file')->getRealPath())) {
                return back()->with('success','No file selected');
            } else {
                //import client using ClientsImport from imports folder
                Excel::import(new ClientsImport, $request->file('file'));
            }
        }catch(\Exception $e) {
            return response()->json(['message' => 'Oops, something went wrong. It appears that there was a formatting issue. Make sure that first row of spreadsheet does not contain a header. Only contact information should be present.']);
        }

        return response()->json(['message' => 'Upload Was Successful']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'active' => 'required|boolean',
            'category' => 'required|string',
            'referral_type' => 'required|string',
            'first_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'last_name' => 'required|string',
            'occupation' => 'nullable|string',
            'dob' => 'nullable|string',
            'email' => 'nullable|string',
            'cell_phone' => 'nullable|string',
            'work_phone' => 'nullable|string',
            'has_spouse' => 'required|boolean',
            'spouse_first_name' => 'nullable|string',
            'spouse_middle_initial' => 'nullable|string',
            'spouse_last_name' => 'nullable|string',
            'spouse_occupation' => 'nullable|string',
            'spouse_dob' => 'nullable|string',
            'spouse_email' => 'nullable|string',
            'spouse_cell_phone' => 'nullable|string',
            'spouse_work_phone' => 'nullable|string',
            'street_address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        $client->update($data);

        return response()->json(['client' => $client, 'message' => 'Contact updated succesfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //added this because I forgot to add to add onDelete cascade to business table migration
        $businesses = Business::where('client_id', $client->id)->get();
        foreach($businesses as $business) {
            $business->delete();
        }

        $client->delete();

        return response('Deleted Client', 200);
    }
}
