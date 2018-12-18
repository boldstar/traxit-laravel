<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Workflow;
use App\Models\Tenant\Status;
use App\Models\Tenant\Engagement;
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

        foreach($newStatuses as $newStatus){
            $workflow->statuses()->create([
                'status' => $newStatus['value'],
                'order' => $newStatus['order']
            ]);
        };
        
        $engagements = Engagement::where('workflow_id', $workflow->id)->get();
        $engagementsExist = $engagements->containsStrict('workflow_id', $workflow->id);
        
        if($engagementsExist === true) {
            return response()->json([
                'workflow' => $workflow->load('statuses'),
                'message' => 'New Statuses Added! But Engagements Containing Old Statuses Exist, Please Update Enagagements First',
            ], 403);
        };
        
        foreach($statuses as $status){
            $workflow->statuses()->where('id', $status['id'])->update([
                'status' =>  $status['status']
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
        $statusToDelete = Status::where('id', $status->id)->first();

        $allEngagements = Engagement::all();

        $engagementsExist = $allEngagements->containsStrict('workflow_id', $statusToDelete->workflow_id);

        if($engagementsExist === false) {
            $statusToDelete->delete();

            return response('Status Has Been Deleted', 200);
        };

        $engagements = Engagement::where('workflow_id', $statusToDelete->workflow_id)->get();

        $statusInUse = $engagements->containsStrict('status', $statusToDelete->status);

        if($statusInUse === false) {
            $statusToDelete->delete();
        } else {
            return response('Status Is Currently In Use, Please Re-assign Engagements Before Deleting', 401);
        };

        return response('Status Has Been Deleted', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyWorkflow(Workflow $workflow)
    {
        $workflowToDelete = Workflow::where('id', $workflow->id)->first();

        $allEngagements = Engagement::all();

        $engagementsExist = $allEngagements->containsStrict('workflow_id', $workflowToDelete->id);

        if($engagementsExist === false) {
            $workflowToDelete->delete();

            return response('Workflow Has Been Deleted', 200);
        };

        $engagements = Engagement::where('workflow_id', $workflowToDelete->id)->get();

        $workflowInUse = $engagements->containsStrict('workflow_id', $workflowToDelete->id);

        if($workflowInUse === false) {
            $workflowToDelete->delete();
        } else {
            return response('Workflow Is Currently In Use, Please Re-assign Engagements Before Deleting', 401);
        };

        return response('Workflow Has Been Deleted', 200);
    }
}
