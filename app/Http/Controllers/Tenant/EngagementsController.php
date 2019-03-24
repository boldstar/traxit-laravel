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
        $engagements = Engagement::with('client')->get();

        return response($engagements);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function returnType_index()
    {
        $return_types = ReturnType::all();

        return response($return_types);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function historyindex($id)
    { 
        
        $history = EngagementActions::where('engagement_id', $id)->get();

        return response($history);

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
        return Engagement::where('client_id', $client_id)->with('questions')->get();

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
            'difficulty' => 'nullable|integer',
            'done' => 'required|boolean'
        ]);

       
        
        $userName = User::where('id', $request->assigned_to)->value('name');
        
        $client = Client::findOrFail($request->client_id);

        if($request->type == 'taxreturn') {
            $days = (int)7 * $request->difficulty;
            $date = \Carbon\Carbon::now();
            $estimated = $date->addDays($days);

            if($request->category == 'Personal') {
                $engagement = Engagement::create([
                    'category' => $request->category,
                    'title' => $request->title,
                    'type' => $request->type,
                    'description' => $request->description,
                    'client_id' => $request->client_id,
                    'name' => $client->fullNameWithSpouse(),
                    'workflow_id' => $request->workflow_id,
                    'return_type' => $request->return_type,
                    'year' => $request->year,
                    'assigned_to' => $userName,
                    'status' => $request->status,
                    'difficulty' => $request->difficulty,
                    'estimated_date' => $estimated,
                    'done' => $request->done,
                ]);
            }
    
            if($request->category == 'Business') {
                $engagement = Engagement::create([
                    'category' => $request->category,
                    'title' => $request->title,
                    'type' => $request->type,
                    'description' => $request->description,
                    'client_id' => $request->client_id,
                    'name' => $request->name,
                    'workflow_id' => $request->workflow_id,
                    'return_type' => $request->return_type,
                    'year' => $request->year,
                    'assigned_to' => $userName,
                    'status' => $request->status,
                    'difficulty' => $request->difficulty,
                    'estimated_date' => $estimated,
                    'done' => $request->done,
                ]);
            }
        }

        if($request->type == 'bookkeeping') {
            if($request->category == 'Business') {
                $engagement = Engagement::create([
                    'category' => $request->category,
                    'title' => $request->title,
                    'type' => $request->type,
                    'description' => $request->description,
                    'client_id' => $request->client_id,
                    'name' => $request->name,
                    'workflow_id' => $request->workflow_id,
                    'year' => $request->year,
                    'assigned_to' => $userName,
                    'status' => $request->status,
                    'done' => $request->done,
                ]);
            }
        }


        // create task
        $task = Task::create([
            'user_id' => $request->get('assigned_to'),
            'title' => $request->get('status')
        ]);

        // create record on pivot table
        $engagement->tasks()->attach($task->id);

        return response()->json([ 'engagement' => $engagement, 'message' => 'A new engagement has been added!'], 201);
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

        return response()->json(['engagement' => $engagement, 'workflow' => $workflow]);
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
        $data = $request->validate([
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

        if($request->done == false) {

            $engagement->update([
                'client_id' => $request->client_id,
                'workflow_id' => $request->workflow_id,
                'title' => $request->title,
                'type' => $request->type,
                'description' => $request->description,
                'return_type' => $request->return_type,
                'year' => $request->year,
                'status' => $request->status,
                'assigned_to' => $request->assigned_to,
                'difficulty' => $request->difficulty,
                'fee' => $request->fee,
                'owed' => $request->owed,
                'balance' => $request->balance,
                'done' => $request->done
            ]);
    
            $user = User::where('name', $request->assigned_to)->value('id');
    
            $engagement->tasks()->update([ 
                'user_id' => $user 
            ]);

            $updatedEngagement = Engagement::where('id', $engagement->id)->with(['client', 'questions'])->first();
            
            return response()->json(['engagement' => $updatedEngagement, 'message' => 'Engagement Updated Succesfully'], 200);
        }

        if($request->done == true) {
            $engagement->update([
                'client_id' => $request->client_id,
                'workflow_id' => $request->workflow_id,
                'title' => $request->title,
                'type' => $request->type,
                'description' => $request->description,
                'return_type' => $request->return_type,
                'year' => $request->year,
                'status' => 'Complete',
                'assigned_to' => 'Complete',
                'difficulty' => $request->difficulty,
                'fee' => $request->fee,
                'owed' => $request->owed,
                'balance' => $request->balance,
                'done' => true
            ]);
    
            $task = $engagement->tasks()->first();

            $engagement->tasks()->detach();

            $task->delete();

            $updatedEngagement = Engagement::where('id', $engagement->id)->with(['client', 'questions'])->first();

            return response()->json(['engagement' => $engagement, 'message' => 'Engagement Updated Succesfully'], 200);
        }

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
        // validate form data
        $engagements = $request->validate([
            'engagements' => 'required|array',
            'assigned_to' => 'required|integer',
            'status' => 'required|string',
        ]);

        // grab name of user by id
        $user = User::where('id', $request->assigned_to)->value('name');

        if($request->status == 'Complete') {
            // for each engagement update the associated task through the pivot table function
            $engagements = Engagement::whereIn('id', $request->engagements)->get();
            foreach ($engagements as $engagement) {
                $engagement->update([
                    'assigned_to' => 'Complete',
                    'status' => $request->status,
                    'done' => true
                ]);
                if($engagement->done == true)
                {
                    $task = $engagement->tasks()->first();

                    $engagement->tasks()->detach();

                    $task->delete();
                }
            }; 

            return response($engagements, 200);
        }

        else {
            // for each engagement update the associated task through the pivot table function
            $engagements = Engagement::whereIn('id', $request->engagements)->get();
    
            foreach ($engagements as $engagement) {
                $engagement->update([
                    'assigned_to' => $user,
                    'status' => $request->status, 
                ]);

                if($engagement->done == false)
                {
                    $engagement->tasks()->update([ 
                        'user_id' => $request->assigned_to,
                        'title' => $request->status 
                    ]);
                }
    
                if($engagement->done == true)
                {
                    $engagement->update(['done' => false]);
                    // create task
                    $task = Task::create([
                        'user_id' => $request->assigned_to,
                        'title' => $request->status
                    ]);
    
                    $engagement->tasks()->detach();
            
                    // create record on pivot table
                    $engagement->tasks()->attach($task->id);
                }
            };        
            
            return response($engagements, 200);
        }

    }
    /**
     * archive an engagement
     */
    public function archiveEngagement(Request $request) {
        $engagement = Engagement::where('id', $request->id)->with(['client', 'questions'])->first();
        $engagement->archive = !$engagement->archive;
        $engagement->save();
        return response()->json(['message' =>  'Done', 'engagement' => $engagement]);
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
