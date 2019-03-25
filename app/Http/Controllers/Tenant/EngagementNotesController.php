<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\ENote;
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
        try{
            return ENote::where('engagement_id', $id)->get();
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
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

        $note = ENote::create([
            'engagement_id' => $request->engagement_id,
            'note' => $request->note
        ]);

        $notes = ENote::where('engagement_id', $request->engagement_id)->get();

        return response()->json(['notes' => $notes, 'message' => 'Engagement note has been added']);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, ENote $ENote)
    {
        $data = $request->validate([
            'engagement_id' => 'required|integer',
            'note' => 'required|string'
        ]);

        $ENote->update($data);

        return response()->json(['message' => 'Note updated', 'note' => $ENote]);
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
