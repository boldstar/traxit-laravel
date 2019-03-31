<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\EngagementActions;

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
            array_push($createdArray, $c->created_at);
        }
        foreach($completed as $c) {
            array_push($completedArray, $c->created_at);
        }

        return response()->json(['created' => $createdArray, 'completed' => $completedArray]); 
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
        return response()->json(['created' => $createdArray, 'completed' => $completedArray]); 
    }
}
