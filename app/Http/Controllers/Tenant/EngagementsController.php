<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\CallList;
use App\Models\Tenant\Automation;
use App\Models\Tenant\Engagement;
use App\Models\Tenant\Client;
use App\Models\Tenant\Task;
use App\Models\Tenant\User;
use App\Models\Tenant\Question;
use App\Models\Tenant\ReturnType;
use App\Models\Tenant\Workflow;
use App\Models\Tenant\EngagementActions;
use App\Exports\EngagementsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class EngagementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Engagement::with('client')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function returnType_index()
    {
        return  ReturnType::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function historyindex($id)
    {       
        return EngagementActions::where('engagement_id', $id)->get();
    }

    /**
     * Display a listing of the resource belonging to client.
     *
     * @return \Illuminate\Http\Response
     */
    public function clientindex($client_id)
    {
        return Engagement::where('client_id', $client_id)->get();
    }

     /**
     * Display a listing of the resource belonging to client.
     *
     * @return \Illuminate\Http\Response
     */
    public function questionindex($client_id)
    {
        return Engagement::where('client_id', $client_id)
                        ->with('questions')
                        ->get();
    }
    /**
     * validate engagement data to be stored
     */
    public function validateStoreRequest($request) {
        $data = $request->validate([
            'category' => 'required|string',
            'title' => 'nullable|string',
            'type' => 'nullable|string',
            'description' => 'nullable|string',
            'client_id' => 'required|integer',
            'name' => 'nullable|string',
            'workflow_id' => 'required|integer',
            'return_type' => 'nullable|string',
            'year' => 'required|string',
            'assigned_to' => 'required|integer',
            'status' => 'required|string',
            'done' => 'required|boolean',
            'difficulty' => 'nullable|integer',
            'priority' => 'nullable|integer',
            'estimated_date' => 'nullable|string'
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
        $data = $this->validateStoreRequest($request);
        $userName = User::where('id', $request->assigned_to)->value('name');
        $client = Client::findOrFail($request->client_id);
        $engagementName = $this->engagementName($request, $client);
        $estimatedDate = $request->estimated_date ? \Carbon\Carbon::parse($request->estimated_date) : null;
        $data['estimated_date'] = $estimatedDate;
        $data['name'] = $engagementName;
        $data['assigned_to'] = $userName;
        $engagement = Engagement::create($data);

        // create task
        $task = Task::create([
            'user_id' => $request->get('assigned_to'),
            'title' => $request->get('status')
        ]);

        // create record on pivot table
        $engagement->tasks()->attach($task->id);

        return response()->json([ 
            'engagement' => $engagement, 
            'message' => 'A new engagement has been added!'], 
            201
        );
    }

    /**
     * check condition of engagement that is going to be stored for the name
     */
    public function engagementName($engagement, $client)
    {
        $name = '';

        if($engagement->category == 'Personal') {
            $name = $client->fullNameWithSpouse();
            return $name;
        } else if ($engagement->category == 'Business') {
            $name = $engagement->name;
            return $name;
        }
        
        return $name;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $engagement = Engagement::with(['client', 'questions'])->find($id);
        $workflow = Workflow::where('id', $engagement->workflow_id)->with('statuses')->first();

        return response()->json([
            'engagement' => $engagement, 
            'workflow' => $workflow
        ]);
    }

    /**
     * validate the data coming in to be updated
     */
    public function validateUpdateRequest($request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'client_id' => 'required|integer',
            'workflow_id' => 'required|integer',
            'title' => 'nullable|string',
            'type' => 'nullable|string',
            'description' => 'nullable|string',
            'return_type' => 'nullable|string',
            'year' => 'required|string',
            'status' => 'required|string',
            'assigned_to' => 'required|string',
            'difficulty' => 'nullable|integer',
            'fee' => 'nullable|string',
            'balance' => 'nullable|string',
            'done' => 'required|boolean',
            'in_progress' => 'required|boolean',
            'paid' => 'required|boolean', 
            'estimated_date' => 'nullable|string',
            'priority' => 'nullable|integer'
        ]);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Engagement $engagement)
    {
        
        $data = $this->validateUpdateRequest($request);
        $estimatedDate = $request->estimated_date ? \Carbon\Carbon::parse($request->estimated_date) : null;
        $data['estimated_date'] = $estimatedDate;

        if($request->done == false) {
            $engagement->update($data);
            $user = User::where('name', $request->assigned_to)->value('id');
            $engagement->tasks()->update([ 
                'user_id' => $user,
                'title' => $request->status
            ]);
        }

        if($request->done == true) {
            $engagement->update($data);
            $engagement->status = 'Complete';
            $engagement->assigned_to = 'Complete';
            $engagement->in_progress = false;
            $engagement->done = true;
            $engagement->save();
            $task = $engagement->tasks()->first();
            $engagement->tasks()->detach();
            $task->delete();
        }

        $updatedEngagement = Engagement::where('id', $engagement->id)
                            ->with(['client', 'questions'])
                            ->first();

        $automation = Automation::where([
            'workflow_id' => $engagement->workflow_id, 
            'status' => $engagement->status, 
            'active' => true
        ])->get();

        if(CallList::where('engagement_id', $engagement->id)->exists()) {
            $call_list_item = CallList::where('engagement_id', $engagement->id)->first();
            $call_list_item->user_name = $engagement->assigned_to;
            $call_list_item->save();
        }
        
        return response()->json([
            'engagement' => $updatedEngagement, 
            'message' => 'Engagement Updated Succesfully',
            'automation' => $automation
        ], 
            200
        );
    }

    /**
     * validate engagements to be updated
     */
    public function validateEngagements($request)
    {
        $engagements = $request->validate([
            'engagements' => 'required|array',
            'assigned_to' => 'nullable|integer',
            'status' => 'nullable|string',
            'due_date' => 'nullable|string',
            'priority' => 'nullable|integer'
        ]);
        return $engagements;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCheckedEngagements(Request $request)
    {
        $this->validateEngagements($request);
        $newlyAssigned = $request->assigned_to != 0 ? User::where('id', $request->assigned_to)->first() : null;
        $engagements = Engagement::whereIn('id', $request->engagements)->get();
        foreach($engagements as $engagement) {
            $currentlyAssigned = User::where('name', $engagement->assigned_to)->first();
            $newDueDate = \Carbon\Carbon::parse($request->due_date);
            if($request->status == 'Complete') {
                $engagement->update([
                    'assigned_to' => 'Complete',
                    'status' => $request->status,
                    'in_progress' => false,
                    'done' => true
                ]);
                $task = $engagement->tasks()->first();
                $engagement->tasks()->detach();
                $task->delete();
            }   else {
                $engagement->update([
                    'assigned_to' => $newlyAssigned ? $newlyAssigned->name : $currentlyAssigned->name,
                    'status' => $request->status ? $request->status : $engagement->status,
                    'estimated_date' => $request->due_date ? $newDueDate->toDateTimeString() : $engagement->estimated_date,
                    'priority' => $request->priority ? $request->priority : $engagement->priority,
                    'in_progress' => false 
                ]);

                if(!$engagement->done) {
                    $engagement->tasks()->update([ 
                        'user_id' => $newlyAssigned ? $newlyAssigned->id : $currentlyAssigned->id,
                        'title' => $request->status ? $request->status : $engagement->status 
                    ]);
                } else if($engagement->done){
                    $engagement->update(['done' => false]);
                    $task = Task::create([
                        'user_id' => $newlyAssigned ? $newlyAssigned->id : $currentlyAssigned->id,
                        'title' => $request->status ? $request->status : $engagement->status
                    ]);
                    $engagement->tasks()->detach();
                    $engagement->tasks()->attach($task->id);
                }       
            }
        }

        if($engagements->count() === 1) {
            $automation = Automation::where([
                'workflow_id' => $engagements[0]->workflow_id, 
                'status' => $engagements[0]->status, 
                'active' => true
            ])->get();
        } else {
            $automation = null;
        }


        return response()->json([
            'engagements' => $engagements, 
            'message' => 'Engagement(s) Updated',
            'automation' => $automation
        ], 200);
    }
    /**
     * archive an engagement
     */
    public function archiveEngagement(Request $request) 
    {
        Engagement::unsetEventDispatcher();
        $engagement = Engagement::where('id', $request->id)
                    ->with(['client', 'questions'])
                    ->first();
        $engagement->archive = !$engagement->archive;
        $engagement->save();
        return response()->json([
            'message' =>  'Done', 
            'engagement' => $engagement
        ]);
    }

    /**
     * Checkout engagement
     */
    public function engagementProgress(Request $request, Engagement $engagement)
    {
        Engagement::unsetEventDispatcher();

        $engagement->in_progress = !$engagement->in_progress;
        $engagement->save();

        $task = $engagement->tasks()->get();

        return response()->json([
            'message' => 'Engagement Updated', 
            'task' => $task->load(['engagements']), 
            'engagement' => $engagement->load(['client', 'questions'])
        ]);
    }

    /**
     * download engagements
     */
    public function downloadEngagements(Request $request)
    {
        return Excel::download(new EngagementsExport($request), 'engagements.xlsx');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Engagement $engagement)
    {
        $task = $engagement->tasks()->first();
        $engagement->delete();
        $task->delete();
        return response('Engagement Is Deleted', 200);
    }
}
