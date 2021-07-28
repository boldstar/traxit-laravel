<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Automation;
use App\Models\Tenant\Task;
use App\Models\Tenant\User;
use App\Models\Tenant\Client;
use App\Models\Tenant\Engagement;
use App\Models\Tenant\Workflow;
use App\Models\Tenant\Status;
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

        $tasks = Task::where('user_id', auth()->user()->id)
            ->with(['engagements', 'engagements.client'])
            ->has('engagements')
            ->get();

        foreach($tasks as $task) {
            $status = Status::where(['workflow_id' => $task->engagements[0]->workflow_id, 'status' => $task->title])->first();
            $task['state'] = $status['state'];
        }

        return $tasks;
    }

    /**
     * validate task update data
     */
    public function validateTask($request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'status' => 'required|string'
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
    public function update(Request $request, Task $task)
    {

        $this->validateTask($request);
        $this->authorize('update', $task);
        $engagement = $task->engagements()->first();
        if($request->done === true) {
        $engagement->update([ 
            'assigned_to' => 'Complete',
            'status' => 'Complete',
            'done' => true,
            'in_progress' => false
        ]);
        $task->engagements()->detach();
        $task->delete();
        return response()->json(['task' => $task, 'message' => 'Task Was Updated'], 200);
        }

        $assigned_to = User::where('id', $request->user_id)->value('name');
        $task->update(['user_id' => $request->user_id,'title' => $request->status]);
        $status = $request->validate(['status' => 'required|string']);
        $engagement->update([ 'assigned_to' => $assigned_to,'status' => $status['status'], 'in_progress' => false]);

        $workflow = Workflow::where('id', $engagement->workflow_id)->with('statuses')->get();
        $statuses = $workflow->pluck('statuses')->collapse();
        $notifyClient = $statuses->contains(function ($val, $key) use ($engagement) {
            return $val->status == $engagement->status && $val->notify_client == true;
        });

        $matches = ['workflow_id' => $workflow[0]->id, 'status' => $task->title];
        $status = Status::where($matches)->first();
        return response()->json([
            'task' => $task, 
            'message' => 'Task Was Updated', 
            'notify' => $notifyClient, 
            'status' => $status], 
            200
        );
    }
    /**
     * validate batch update for tasks
     */
    public function validateBatchTasks($request)
    {
        $data = $request->validate([
            'tasksToUpdate' => 'required|array',
            'user_id' => 'required|integer',
            'status' => 'required|string'
        ]);
        return $data;
    }

    /**
     * update multiple tasks at one time
     */
    public function batchUpdateTasks(Request $request)
    {
        $this->validateBatchTasks($request);

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
                'in_progress' => false
            ]);
        };

        return response()->json([
            'tasks' => $request->tasksToUpdate, 
            'message' => 'Tasks Were Succesfully Updated'],
             200
        );

    }

    /**
     * check send to for email
     */
    public function checkSendTo($send_to, $client) 
    {
        if($send_to == 'both') {
            $email = $client->email;
        } else if($send_to == 'taxpayer') {
            $email = $client->email;
        } else if($send_to == 'spouse') {
            $email = $client->spouse_email;
        }
        return $email;
    }

    /**
     * notify client of status
     */
    public function notifyClient(Request $request) {
        $task = Task::find($request->id);
        $engagement = $task->engagements()->first();
        $workflow = Workflow::where('id', $engagement->workflow_id)->first();
        $status = $workflow->statuses()->get();
        $message = $status->where('status', $engagement->status)->first();
        $client = Client::where('id', $engagement->client_id)->first();
        $email = $this->checkSendTo($request->send_to, $client);
       
        try {
            Mail::to($email)->send(new StatusUpdate([
                'engagement' => $engagement, 
                'client' => $client, 
                'test' =>  false,
                'send_to' =>  $request->send_to,
                'message' => $message
            ]));
    
            return response()->json(['message' => 'The Contact Has Been Notified']);
        } catch(\Exception $e) {
            return response( $e->getMessage(), 422);
        }    
    }
}
