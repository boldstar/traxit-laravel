<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Task;
use App\Models\Tenant\User;
use App\Models\Tenant\Engagement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Task::where('user_id', auth()->user()->id)->with(['engagements', 'engagements.client'])->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(Request $request, Task $task)
    {

        $this->authorize('update', $task);

        if($request->done === true) {

            $engagement = $task->engagements()->first();

            $engagement->update([ 
                'assigned_to' => 'Complete',
                'status' => 'Complete',
                'done' => true
            ]);

            $task->engagements()->detach();

            $task->delete();

            return response()->json(['task' => $task, 'message' => 'Task Was Updated'], 200);
        }

        $data = $request->validate([
            'user_id' => 'required|integer',
        ]);

        $task->update($data);

        $assigned_to = User::where('id', $request->user_id)->value('name');

        $status = $request->validate([
            'status' => 'required|string'
        ]);

        $engagement = $task->engagements()->first();

        $engagement->update([ 
            'assigned_to' => $assigned_to,
            'status' => $status['status'],
        ]);

        return response()->json(['task' => $task, 'message' => 'Task Was Updated'], 200);
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
