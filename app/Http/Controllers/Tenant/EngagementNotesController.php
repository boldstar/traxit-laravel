<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Enote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EngagementNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return Enote::where('engagement_id', $id)->get();
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

        $note = Enote::create([
            'engagement_id' => $request->engagement_id,
            'note' => $request->note
        ]);

        $notes = Enote::where('engagement_id', $request->engagement_id)->get();

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
    public function update(Request $request, Enote $enote)
    {
        $data = $request->validate([
            'engagement_id' => 'required|integer',
            'note' => 'required|string'
        ]);

        $enote->update($data);

        return response()->json(['message' => 'Note updated', 'note' => $enote]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Enote::find($id);

        $note->delete();

        return response('Note deleted');
    }
}
