<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Engagement;
use App\Models\Tenant\Client;
use App\Models\Tenant\Task;
use App\Models\Tenant\User;
use App\Models\Tenant\Question;
use App\Models\Tenant\ReturnType;
use App\Models\Tenant\Workflow;
use App\Models\Tenant\EngagementActions;
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
            'done' => 'required|boolean'
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
        $workflowName = Workflow::where('id', $request->workflow_id)->value('workflow');
        $client = Client::findOrFail($request->client_id);
        $clientName = $this->engagementName($request, $client);
        $days = $this->engagementEstimatedDate($request);
        $type = $this->determineType($request->type);
        $engagement = Engagement::create($data);
        Engagement::unsetEventDispatcher();

        if($type) {
            $engagement->name = $clientName;
            $engagement->assigned_to = $userName;
            $engagement->description = $workflowName;
            $engagement->estimated_date = $days;
        } else if(!$type) {
            $engagement->assigned_to = $userName;
            $engagement->estimated_date = null;
            $engagement->return_type = null;}
        $engagement->save();

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
        if($engagement->type == 'taxreturn' || $engagement->type == 'custom') {
            if($engagement->category == 'Personal') {
                $name = $client->fullNameWithSpouse();
                return $name;
            } else if ($engagement->category == 'Business') {
                $name = $engagement->name;
                return $name;
            }
        } else if($engagement->type == 'bookkeeping') {
            $name = $engagement->name;
            return $name;
        }

        return $name;
    }

    /**
     * determine engagement type
     */
    public function determineType($type) 
    {
        if($type == 'taxreturn' || $type == 'custom') {
            return true;
        } else if($type == 'bookkeeping') {
            return false;
        }
    }

    /**
     * determine the estimated days of completiong
     */
    public function engagementEstimatedDate($request) 
    {

        $request->validate([
            'difficulty' => 'nullable|integer',
        ]);
        $days = (int)7 * $request->difficulty;
        $date = \Carbon\Carbon::now();
        $estimated = $date->addDays($days);

        return $estimated;
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
            'done' => 'required|boolean'
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

        if($request->done == false) {
            $engagement->update($data);
            $user = User::where('name', $request->assigned_to)->value('id');
            $engagement->tasks()->update([ 
                'user_id' => $user 
            ]);
        }

        if($request->done == true) {
            $engagement->update($data);
            $engagement->status = 'Complete';
            $engagement->assigned_to = 'Complete';
            $engagement->done = true;
            $engagement->save();
            $task = $engagement->tasks()->first();
            $engagement->tasks()->detach();
            $task->delete();
        }

        $updatedEngagement = Engagement::where('id', $engagement->id)
                            ->with(['client', 'questions'])
                            ->first();

        return response()->json([
            'engagement' => $updatedEngagement, 
            'message' => 'Engagement Updated Succesfully'], 
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
            'assigned_to' => 'required|integer',
            'status' => 'required|string',
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
        $user = User::where('id', $request->assigned_to)->value('name');
        $engagements = Engagement::whereIn('id', $request->engagements)->get();
        foreach($engagements as $engagement) {
            if($request->status == 'Complete') {
                $engagement->update([
                    'assigned_to' => 'Complete',
                    'status' => $request->status,
                    'done' => true
                ]);
                $task = $engagement->tasks()->first();
                $engagement->tasks()->detach();
                $task->delete();
            }   else {
                $engagement->update([
                    'assigned_to' => $user,
                    'status' => $request->status, 
                ]);

                if($engagement->done == false) {
                    $engagement->tasks()->update([ 
                        'user_id' => $request->assigned_to,
                        'title' => $request->status 
                    ]);
                } else if($engagement->done == true){
                    $engagement->update(['done' => false]);
                    $task = Task::create([
                        'user_id' => $request->assigned_to,
                        'title' => $request->status
                    ]);
                    $engagement->tasks()->detach();
                    $engagement->tasks()->attach($task->id);
                }       
            }
        }
        return response()->json(['engagements' => $engagements, 'message' => 'Engagements Updated'], 200);
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
