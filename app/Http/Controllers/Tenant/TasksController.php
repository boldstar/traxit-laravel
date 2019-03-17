<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Task;
use App\Models\Tenant\User;
use App\Models\Tenant\Client;
use App\Models\Tenant\Engagement;
use App\Models\Tenant\Workflow;
use Illuminate\Http\Request;
use App\Mail\StatusUpdate;
use Illuminate\Support\Facades\Mail;
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

        return Task::where('user_id', auth()->user()->id)
            ->with(['engagements', 'engagements.client'])
            ->has('engagements')
            ->get();

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
            'status' => 'required|string'
        ]);

        $task->update([
            'user_id' => $request->user_id,
            'title' => $request->status
        ]);

        $assigned_to = User::where('id', $request->user_id)->value('name');

        $status = $request->validate([
            'status' => 'required|string'
        ]);

        $engagement = $task->engagements()->first();

        $engagement->update([ 
            'assigned_to' => $assigned_to,
            'status' => $status['status'],
        ]);

        $workflow = Workflow::where('id', $engagement->workflow_id)->with('statuses')->get();

        $statuses = $workflow->pluck('statuses')->collapse();

        $notifyClient = $statuses->contains(function ($val, $key) use ($engagement) {
            return $val->status == $engagement->status && $val->notify_client == true;
        });

        return response()->json(['task' => $task, 'message' => 'Task Was Updated', 'notify' => $notifyClient], 200);
    }

    /**
     * update multiple tasks at one time
     */
    public function batchUpdateTasks(Request $request)
    {
        $data = $request->validate([
            'tasksToUpdate' => 'required|array',
            'user_id' => 'required|integer',
            'status' => 'required|string'
        ]);

        Task::whereIn('id', $request->tasksToUpdate)->update([ 
            'user_id' => $request->user_id,
            'title' => $request->status,
        ]);

        $assigned_to = User::where('id', $request->user_id)->value('name');

        
        foreach($request->tasksToUpdate as $taskToUpdate) {
            $task = Task::where('id', $taskToUpdate)->first();
            $engagement = $task->engagements()->first();

            $engagement->update([ 
                'assigned_to' => $assigned_to,
                'status' => $request->status,
            ]);
        };

        return response()->json(['tasks' => $request->tasksToUpdate, 'message' => 'Tasks Were Succesfully Updated'], 200);

    }

    /**
     * notify client of status
     */
    public function notifyClient(Request $request) {
        $task = Task::find($request->id);
        $engagement = $task->engagements()->first();
        $client = Client::where('id', $engagement->client_id)->first();
        if($request->send_to == 'both') {
            $email = $client->email;
        }
        if($request->send_to == 'taxpayer') {
            $email = $client->email;
        }
        if($request->send_to == 'spouse') {
            $email = $client->spouse_email;
        }
        
        try {
            Mail::to($email)->send(new StatusUpdate([
                'engagement' => $engagement, 
                'client' => $client, 
                'test' =>  false,
                'send_to' =>  $request->send_to
            ]));
    
            return response()->json(['message' => 'The Contact Has Been Notified']);
        } catch(\Exception $e) {
            $e->getMessage();
        }    
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
