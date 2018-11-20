<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'category' => 'nullable|string',
            'referral_type' => 'nullable|string',
            'first_name' => 'nullable|string',
            'middle_initial' => 'nullable|string',
            'last_name' => 'nullable|string',
            'occupation' => 'nullable|string',
            'dob' => 'nullable|string',
            'email' => 'nullable|string',
            'cell_phone' => 'nullable|string',
            'work_phone' => 'nullable|string',
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

        return response($client, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::with('dependents')->find($id);

        return response($client);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
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

        return response($client, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response('Deleted Client', 200);
    }
}
