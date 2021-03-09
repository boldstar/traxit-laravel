<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\EngagementActions;
use App\Models\Tenant\Engagement;
use Illuminate\Support\Arr;

class EngagementsHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $engagements = EngagementActions::where('action', '!=', 'updated')->get();
        $created = $engagements->where('action', 'created');
        $completed = $engagements->where('action', 'completed');
        $createdArray = array();
        $completedArray = array();
        foreach($created as $c) {
            array_push($createdArray, [ $c->created_at, $c->year]);
        }
        foreach($completed as $c) {
            array_push($completedArray, [ $c->created_at, $c->year]);
        }

        return response()->json([
            'created' => $createdArray, 
            'completed' => $completedArray
        ]); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function average()
    {
        $engagements = EngagementActions::where('action', '!=', 'updated')->get();
        $created = $engagements->where('action', 'created');
        $completed = $engagements->where('action', 'completed');
        $createdArray = array();
        $completedArray = array();
        foreach($created as $c) {
            $matched = $completed->containsStrict('engagement_id', $c->engagement_id);
            if($matched) {
                array_push($createdArray, $c);
            }
        }
        foreach($completed as $c) {
            array_push($completedArray, $c);
        }
        return response()->json([
            'created' => $createdArray, 
            'completed' => $completedArray
        ]); 
    }

    /**
     * update received date of engagements history
     */
    public function updateReceivedDate(Request $request) 
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'date' => 'required|string'
        ]);

        $history = EngagementActions::where('engagement_id', $request->id)->get();
        $engagement = Engagement::where('id', $request->id)->first();
        $action = $history->where('action', 'created')->first();
        try {
            $action->created_at = \Carbon\Carbon::parse($request->date)->timestamp;
            $engagement->created_at = \Carbon\Carbon::parse($request->date)->timestamp;
            $action->save();
            $engagement->save();
        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'The history has been updated', 
            'history' => $action
        ]);
    }

    /**
     * return user history
     */
    public function getUserHistory($id)
    {
        return EngagementActions::where('user_id', $id)->get();
    }
}
