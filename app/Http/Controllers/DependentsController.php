<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DependentsController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'middle_initial' => 'required|string',
            'last_name' => 'required|string',
            'dob' => 'required|string',
        ]);

        $dependent = Client::create($data);

        return response($dependent, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'middle_initial' => 'required|string',
            'last_name' => 'required|string',
            'dob' => 'required|string',
        ]);

        $dependent = Client::create($data);

        return response($dependent, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dependent->delete();

        return response('Deleted Dependent', 200);
    }
}
