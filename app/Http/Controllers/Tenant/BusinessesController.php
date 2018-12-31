<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $data = $request->validate([
            'client_id' => 'required|integer',
            'business_name' => 'required|string',
            'business_type' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'email' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'fax_number' => 'nullable|string'
        ]);

        $business = Business::create($data);

        return response()->json([ 'business' => $business, 'message' => 'A new business has been added!'], 200);
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
            'business_type' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'email' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'fax_number' => 'nullable|string',
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

        return response('The Business Has Been Deleted', 200);
    }
}
