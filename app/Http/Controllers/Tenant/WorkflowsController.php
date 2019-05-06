<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Workflow;
use App\Models\Tenant\Status;
use App\Models\Tenant\Engagement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * valdate store request
     */
    public function validateWorkflow($request)
    {
        $data = $request->validate([
            'name' => 'required|string'
        ]); 
        return $data;
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
        $this->validateWorkflow($request);
        $copy = $request->copy_workflow;

        // create new workflow
        if(!$copy) {
            $workflow = Workflow::create(['workflow' => $request->name]);
            return response($workflow, 201);
        }else if($copy) {
            $workflowToCopy = Workflow::where('id', $request->workflow_id)->with('statuses')->get();
            $statusesToCopy = $workflowToCopy->pluck('statuses');
            $statuses = $statusesToCopy[0];
            $newWorkflow = Workflow::create(['workflow' => $request->name]);
            foreach($statuses as $status){
                $newWorkflow->statuses()->create([
                    'status' => $status['status'],
                    'order' => $status['order'],
                ]);
            };
            return response($newWorkflow->load('statuses'), 201);
        }

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOnSetup(Request $request)
    {

        // validate form data
        $data = $request->validate([
            'name' => 'required|string',
            'statuses' => 'required|array'
        ]); 

        // create new workflow

        $workflow = Workflow::create(['workflow' => $request->name]);
        foreach($request->statuses as $status){
            $workflow->statuses()->create([
                'status' => $status['value'],
                'order' => $status['order'],
            ]);
        };
        return response()->json(['message' => 'Workflow was succesfully created']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       return Workflow::with('statuses')->find($id);
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
                'notify_client' => $newStatus['notify_client'],
                'order' => $newStatus['order']
            ]);
        };
        
        $engagements = Engagement::where('workflow_id', $workflow->id)->get();
        $engagementsExist = $engagements->containsStrict('workflow_id', $workflow->id);
        foreach($statuses as $status){
            $workflow->statuses()->where('id', $status['id'])->update([
                'notify_client' => $status['notify_client']
            ]);
        };
        
        $statusIds = array();
        $matchedIds = array();
        $statusesToCheck = $workflow->statuses()->get();
        if($engagementsExist === true) {
            foreach($statusesToCheck as $statusCheck) {
                $containsStatus = $engagements->containsStrict('status', $statusCheck->status);
                if($containsStatus) {
                    array_push($statusIds, $statusCheck->id);
                }
            }
            foreach($statuses as $status){
                $matched = in_array($status['id'], $statusIds);
                if(!$matched) {
                    $workflow->statuses()->where('id', $status['id'])->update([
                        'status' =>  $status['status'],
                        'notify_client' => $status['notify_client']
                    ]);
                } else if($matched) {
                    array_push($matchedIds, $status['id']);
                }
            };
            return response()->json([
                'workflow' => $workflow->load('statuses'),
                'message' => 'Updates Applied, However Statuses Colored In Red Are Currently In Use. If You Were Wanting To Edit Any Of The In Use Statuses Please Reassign Engagements First',
                'statuses' => $matchedIds
            ], 403);
        };
        
        foreach($statuses as $status){
            $workflow->statuses()->where('id', $status['id'])->update([
                'status' =>  $status['status'],
                'notify_client' => $status['notify_client']
            ]);
        };
        return response()->json([ 'workflow' => $workflow->load('statuses'), 'message' => 'The Workflow Has Been Updated!'], 200);
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
        //after deleting statuses, recreate with new order.
        foreach($statuses as $status){
            $workflow->statuses()->create([
                'status' => $status['status'],
                'notify_client' => $status['notify_client'],
                'order' => $status['order'],
                'message' => $status['message']
            ]);
        };

        return response($statuses, 200);

    }

    /**
     * add status message
     */
    public function message(Request $request) {

        $request->validate([
            'message' => 'required|string' 
        ]);
        $status = Status::where('id', $request->id)->first();
        $status->message = $request->message;
        $status->save();
        $workflow = Workflow::where('id', $status->workflow_id)->with('statuses')->first();
        return response()->json(['workflow' => $workflow, 'message' => 'Message added to status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyStatus(Status $status)
    {
        $statusToDelete = Status::where('id', $status->id)->first();
        $engagements = Engagement::where('workflow_id', $statusToDelete->workflow_id)->get();
        $statusInUse = $engagements->containsStrict('status', $statusToDelete->status);
        //if status is not in use delete it, else return error message
        if(!$statusInUse) {
            $statusToDelete->delete();
        } else if($statusInUse) {
            return response('Status Is Currently In Use, Please Re-assign Engagements Before Deleting', 401);
        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyWorkflow(Workflow $workflow)
    {
        $engagements = Engagement::all();
        $engagementsExist = $engagements->containsStrict('workflow_id', $workflow->id);
        //if engagements are not using this workflow delete it, else return error message
        if(!$engagementsExist) {
            $workflow->delete();
            return response('Workflow Has Been Deleted', 200);
        } else if($engagementsExist) {
            return response('Workflow Is Currently In Use, Please Re-assign Engagements Before Deleting', 401);
        };
    }
}
