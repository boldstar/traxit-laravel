<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Dependent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            'client_id' => 'required|integer',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'dob' => 'nullable|string',
        ]);

        $dependent = Dependent::create($data);

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
        $dependent = Dependent::find($id);

        return response($dependent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dependent $dependent)
    {
        $data = $request->validate([
            'client_id' => 'required|integer',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'dob' => 'nullable|string',
        ]);

        $dependent = Dependent::create($data);

        return response($dependent, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dependent $dependent)
    {
        $dependent->delete();

        return response('Deleted Dependent', 200);
    }
}
