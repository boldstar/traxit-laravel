<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\ENote;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EngagementNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return ENote::where('engagement_id', $id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'engagement_id' => 'required|integer',
            'note' => 'required|string'
        ]);

        $user = User::where('id', auth()->user()->id)->value('name');

        $note = ENote::create([
            'engagement_id' => $request->engagement_id,
            'note' => $request->note,
            'username' => $user
        ]);

        $notes = ENote::where('engagement_id', $request->engagement_id)->get();

        return response()->json([
            'notes' => $notes, 
            'message' => 'Engagement note has been added'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ENote::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ENote $enote)
    {
        $data = $request->validate([
            'engagement_id' => 'required|integer',
            'note' => 'required|string'
        ]);

        $note = ENote::where('id', $request->id)->first();
        $note->update($data);
        $note->save();

        return response()->json(['message' => 'Note updated', 'note' => $note]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = ENote::find($id);

        $note->delete();

        return response('Note deleted');
    }
}
