<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Engagement;
use App\Models\Tenant\Client;
use App\Models\Tenant\Task;
use App\Models\Tenant\User;
use App\Models\Tenant\Question;
use App\Models\Tenant\ReturnType;
use Illuminate\Http\Request;
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
            'client_id' => 'required|integer',
            'name' => 'nullable|string',
            'workflow_id' => 'required|integer',
            'return_type' => 'required|string',
            'year' => 'required|string',
            'assigned_to' => 'required|integer',
            'status' => 'required|string',
            'done' => 'required|boolean'
        ]);
        
        $userName = User::where('id', $request->assigned_to)->value('name');
        
        $client = Client::findOrFail($request->client_id);

        if($request->category == 'personal') {
            $engagement = Engagement::create([
                'category' => $request->category,
                'client_id' => $request->client_id,
                'name' => $client->fullNameWithSpouse(),
                'workflow_id' => $request->workflow_id,
                'return_type' => $request->return_type,
                'year' => $request->year,
                'assigned_to' => $userName,
                'status' => $request->status,
                'done' => $request->done,
            ]);
        }

        if($request->category == 'business') {
            $engagement = Engagement::create([
                'category' => $request->category,
                'client_id' => $request->client_id,
                'name' => $request->name,
                'workflow_id' => $request->workflow_id,
                'return_type' => $request->return_type,
                'year' => $request->year,
                'assigned_to' => $userName,
                'status' => $request->status,
                'done' => $request->done,
            ]);
        }

        // create task
        $task = Task::create([
            'user_id' => $request->get('assigned_to'),
            'title' => $request->get('return_type')
        ]);

        // create record on pivot table
        $engagement->tasks()->attach($task->id);

        return response($engagement, 201);
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

        return response($engagement);
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
            'return_type' => 'required|string',
            'year' => 'required|string',
            'status' => 'required|string',
            'assigned_to' => 'required|string',
            'done' => 'required|boolean'
        ]);

        $engagement->update([
            'client_id' => $request->client_id,
            'return_type' => $request->return_type,
            'year' => $request->year,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
            'done' => $request->done
        ]);

        $user = User::where('name', $request->assigned_to)->value('id');

        $engagement->tasks()->update([ 
            'user_id' => $user 
        ]);

        return response($engagement, 200);
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

        // for each engagment update the assigned to and status fields
        Engagement::whereIn('id', $request->engagements)->update([ 
            'assigned_to' => $user,
            'status' => $request->status 
        ]);

        // for each engagement update the associated task through the pivot table function
        $engagements = Engagement::whereIn('id', $request->engagements)->get();
        foreach ($engagements as $engagement) {
            $engagement->tasks()->update([ 
                'user_id' => $request->assigned_to 
            ]);
        };
        
        return response($engagements, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Engagement $engagement)
    {
        $engagement->delete();

        return response('Engagement Is Deleted', 200);
    }
}
