<?php

namespace App\Http\Controllers;

use App\Engagement;
use App\Task;
use App\User;
use App\Question;
use Illuminate\Http\Request;

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
    public function chartdata()
    {
        $engagements = Engagement::all();

        $data = $engagements->groupBy('status');

        return response($data);

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
            'client_id' => 'required|integer',
            'workflow_id' => 'required|integer',
            'return_type' => 'required|string',
            'year' => 'required|string',
            'assigned_to' => 'required|integer',
            'status' => 'required|string',
            'done' => 'required|boolean'
        ]);
        
        // grab name of user by id
        $userName = User::where('id', $request->assigned_to)->value('name');

        // create new engagement
        $engagement = Engagement::create([
            'client_id' => $request->client_id,
            'workflow_id' => $request->workflow_id,
            'return_type' => $request->return_type,
            'year' => $request->year,
            'assigned_to' => $userName,
            'status' => $request->status,
            'done' => $request->done,
        ]);

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

        $engagement->update($data);

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
