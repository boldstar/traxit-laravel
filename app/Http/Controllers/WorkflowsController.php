<?php

namespace App\Http\Controllers;

use App\Workflow;
use App\Status;
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
                'status' => $newStatus['value'],
                'order' => $newStatus['order']
            ]);
        };
        
        return response($workflow->load('statuses'), 200);

    }

  /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWorkflowStatuses(Request $request)
    {
        // validate form data
        $validated = $request->validate([
            'id' => 'required|integer',
            'statuses' => 'required|array',
        ]);

        $workflow = Workflow::where('id', $validated['id'])->firstOrFail();

        $statuses = $validated['statuses'];

        $workflow->statuses->each->delete();
       
        foreach($statuses as $status){
            $workflow->statuses()->create([
                'status' => $status['status'],
                'order' => $status['order']
            ]);
        };

        return response('Update Succesful', 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        $status->delete();

        return response('Status Has Been Deleted', 200);
    }
}
