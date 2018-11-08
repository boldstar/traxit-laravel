<?php

namespace App\Http\Controllers;

use App\Workflow;
use Illuminate\Http\Request;

class WorkflowsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Workflow::with('statuses')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // validate form data
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        // create new workflow
        $workflow = Workflow::create([
            'workflow' => $request->name,
        ]);

        return response($workflow, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workflow = Workflow::with('statuses')->find($id);

        return response($workflow);
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
    public function workflowStatuses(Request $request, Workflow $workflow)
    {
    
        // validate form data
        $validated = $request->validate([
            'workflow' => 'required|string',
            'statuses' => 'nullable|array',
            'newStatuses' => 'nullable|array',
        ]);

        $statuses = $validated['statuses'];
        $newStatuses = $validated['newStatuses'];

        $workflow->update($validated);
       
        foreach($statuses as $status){
            $workflow->statuses()->where('id', $status['id'])->update([
                'status' =>  $status['status']
            ]);
        };

        foreach($newStatuses as $newStatus){
            $workflow->statuses()->create([
                'status' => $newStatus['value']
            ]);
        };
        
        return response($workflow, 200);

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
