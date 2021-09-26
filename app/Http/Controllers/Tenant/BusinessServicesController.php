<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\BusinessService;
use App\Http\Controllers\Controller;

class BusinessServicesController extends Controller
{
    public function get()
    {
        return BusinessService::all();
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'state' => 'required|boolean'
        ]);

        $business_service = BusinessService::create($data);

        return response($business_service);
    }

    public function show($id)
    {
        return BusinessService::where('business_id', $id)->first();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'business_id' => 'required|integer',
            'name' => 'required|string',
            'state' => 'required|boolean'
        ]);

        $business_service = BusinessService::firstOrNew(['business_id' => $request->business_id]);
        $business_service[$request->name] = $request->state;
        $business_service->save();

        return response($business_service);
    }

    public function destroy($id)
    {
        $business_service->delete();
        return response('Business Service Deleted');
    }
}
