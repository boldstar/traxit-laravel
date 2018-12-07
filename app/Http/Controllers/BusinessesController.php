<?php

namespace App\Http\Controllers;

use App\Business;
use Illuminate\Http\Request;

class BusinessesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Business::with('client')->get();
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
        $date = $request->validate([
            'client_id' => 'required|integer',
            'business_name' => 'required|string',
            'business_type' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'fax_number' => 'required|string'
        ]);

        $business = Business::create([
            'client_id' => $request->client_id,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'fax_number' => $request->fax_number
        ]);

        return response($business, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Business::with('client')->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Business $business)
    {
        $data = $request->validate([
            'client_id' => 'required|integer',
            'business_name' => 'required|string',
            'business_type' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'fax_number' => 'required|string',
        ]);

        $business->update($data);

        return response($business, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        $business->delete();

        return response('Deleted Business', 200);
    }
}
