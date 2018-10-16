<?php

namespace App\Http\Controllers;

use App\Engagement;
use App\Task;
use App\User;
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

        foreach ($engagements as $engagement) {
            json_decode($engagement);
        }

        return response($engagements);

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
        $data = $request->validate([
            'client_id' => 'required|integer',
            'return_type' => 'required|string',
            'year' => 'required|string',
            'assigned_to' => 'required|integer',
            'status' => 'required|string',
            'done' => 'required|boolean'
        ]);
        
        $userName = User::findOrFail($request->assigned_to)->value('name');

        $engagement = Engagement::create([
            'client_id' => $request->client_id,
            'return_type' => $request->return_type,
            'year' => $request->year,
            'assigned_to' => $userName,
            'status' => $request->status,
            'done' => $request->done,
        ]);

        $task = Task::create([
            'user_id' => $request->get('assigned_to'),
            'title' => $request->get('return_type')
        ]);

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
