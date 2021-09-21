<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\BusinessNote;
use App\Http\Controllers\Controller;

class BusinessNotesController extends Controller
{
    public function get()
    {
        return BusinessNote::all();
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|integer',
            'business_id' => 'required|integer',
            'note' =>   'required|string'
        ]);

        $business_note = BusinessNote::firstOrNew(
            ['id' => $request->id],
            ['business_id' => $request->business_id],
        );

        $business_note->note = $request->note;
        $business_note->save();

        return response(BusinessNote::where('business_id', $business_note->business_id)->get());
    }

    public function show($id)
    {
        return BusinessNote::where('id', $id)->first();
    }

    public function update(Request $request)
    {
        $business_note = BusinessNote::where('id', $request->id)->first();

        $business_note->update($request);

        return response($business_note);
    }

    public function destroy($id)
    {
        $business_note = BusinessNote::find($id);
        $business_note->delete();
        return response('Business Note Deleted');
    }
}
