<?php


namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Automation;
use App\Http\Controllers\Controller;

class AutomationsController extends Controller
{
    public function get()
    {
        return Automation::all();
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string',
            'workflow_id' => 'nullable|integer',
            'workflow' => 'nullable|string',
            'status_id' => 'nullable|integer',
            'status' => 'nullable|string',
            'action_id' => 'required|integer',
            'action' => 'required|string', 
            'active' => 'required|boolean'
        ]);

        $automation = Automation::firstOrCreate([
            'workflow_id' => $request->workflow_id,
            'status_id' => $request->status_id,
            'action_id' => $request->action_id
        ], $data);

        return response($automation, 200);
    }

    public function update(Request $request, Automation $automation)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'category' => 'required|string',
            'workflow_id' => 'required|integer',
            'workflow' => 'nullable|string',
            'status_id' => 'required|integer',
            'status' => 'nullable|string',
            'action_id' => 'required|integer',
            'action' => 'required|string', 
            'active' => 'required|boolean'
        ]);

        if(Automation::where([
            'workflow_id' => $request->workflow_id, 
            'status_id' => $request->status_id, 
            'action_id' => $request->action_id 
        ])->exists()){
            return response('Automation Already Exists', 400);
        } else {
            $automation->update($data);
        }

        return response($automation, 200);
    }

    public function updateState(Request $request, $id) 
    {
        $automation = Automation::where('id', $id)->firstOrFail();

        $automation->active = !$automation->active;
        $automation->save();

        return response($automation, 200);
    }

    public function delete(Automation $automation) {
        $automation->delete();
        return response('Automation Deleted', 200);  
    }
}
