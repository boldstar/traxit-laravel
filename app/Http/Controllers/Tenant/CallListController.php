<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Engagement;
use App\Models\Tenant\CallList;
use App\Http\Controllers\Controller;

class CallListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CallList::where('archive', false)->get();
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
            'engagement_id' => 'required|integer',
            'engagement_name' => 'required|string',
            'current_status' => 'required|string',
            'first_called_date' => 'required|string',
            'last_called_date' => 'required|string',
            'total_calls' => 'required|integer',
        ]);

        $engagement = Engagement::where('id', $request->engagement_id)->first();
        $user = $engagement->assigned_to;
        $data['user_name'] = $user;
        $data['first_called_date'] = \Carbon\Carbon::parse($request->first_called_date);
        $data['last_called_date'] = \Carbon\Carbon::parse($request->last_called_date);
        $data['comments'] = '';

        $call_list_item = CallList::create($data);

        return response($call_list_item);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CallList::where('engagement_id', $id)->first();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'last_called_date' => 'required|string',
            'total_calls' => 'required|integer'
        ]);

        $engagement = Engagement::where('id', $request->engagement_id)->first();
        $user = $engagement->assigned_to;
        $data['user_name'] = $user;
        $data['last_called_date'] = \Carbon\Carbon::parse($request->last_called_date);

        $call_list_item = CallList::where('id', $request->id)->first();
        $call_list_item->update($data);

        return response($call_list_item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateItem(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'first_called_date' => 'required|string',
            'last_called_date' => 'required|string',
            'total_calls' => 'required|integer'
        ]);
        
        $engagement = Engagement::where('id', $request->engagement_id)->first();
        $user = $engagement->assigned_to;
        $data['user_name'] = $user;
        $data['first_called_date'] = \Carbon\Carbon::parse($request->first_called_date);
        $data['last_called_date'] = \Carbon\Carbon::parse($request->last_called_date);
        $data['comments'] = $request->comments;

        $call_list_item = CallList::where('id', $request->id)->first();
        $call_list_item->update($data);

        return response($call_list_item);
    }

    /**
     * Remove from call list
     */
    public function removeFromCallList(Request $request)
    {
        $callListItem = CallList::where('id', $request->id)->first();

        if($callListItem) {
            if($callListItem->archive && $request->automated) {
                return response('Call List Item Already Removed', 400);
            } else {
                $callListItem->archive = !$callListItem->archive;
                $callListItem->save();
            }
        } else {
            return response('Call List Item Does Not Exist', 400);
        }

        return response($callListItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CallList $call_list)
    {
        $call_list->delete();
        return response('Call List Item Deleted', 200);   
    }
}
