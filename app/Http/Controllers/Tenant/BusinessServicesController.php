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

        ]);

        $business_service = BusinessService::create($data);

        return response($business_service);
    }

    public function show($id)
    {
        return BusinessService::where('id', $id)->first();
    }

    public function update(Request $request)
    {
        $business_service = BusinessService::where('id', $request->id)->first();

        $business_service->update($request);

        return response($business_service);
    }

    public function destroy($id)
    {
        $business_service->delete();
        return response('Business Service Deleted');
    }
}
