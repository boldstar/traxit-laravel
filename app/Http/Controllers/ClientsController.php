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
            'category' => 'required|string',
            'first_name' => 'required|string',
            'middle_initial' => 'required|string',
            'last_name' => 'required|string',
            'occupation' => 'required|string',
            'dob' => 'required|string',
            'email' => 'required|string',
            'cell_phone' => 'required|string',
            'work_phone' => 'required|string',
            'spouse_first_name' => 'required|string',
            'spouse_middle_initial' => 'required|string',
            'spouse_last_name' => 'required|string',
            'spouse_dob' => 'required|string',
            'spouse_email' => 'required|string',
            'spouse_cell_phone' => 'required|string',
            'spouse_work_phone' => 'required|string',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'created_at' => 'required|string',
            'updated_at' => 'required|string',
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
        $client = Client::find($id);
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
            'first_name' => 'required|string',
            'middle_initial' => 'required|string',
            'last_name' => 'required|string',
            'occupation' => 'required|string',
            'dob' => 'required|string',
            'email' => 'required|string',
            'cell_phone' => 'required|string',
            'work_phone' => 'required|string',
            'spouse_first_name' => 'required|string',
            'spouse_middle_initial' => 'required|string',
            'spouse_last_name' => 'required|string',
            'spouse_dob' => 'required|string',
            'spouse_email' => 'required|string',
            'spouse_cell_phone' => 'required|string',
            'spouse_work_phone' => 'required|string',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'created_at' => 'required|string',
            'updated_at' => 'required|string',
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
