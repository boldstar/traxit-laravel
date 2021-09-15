<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Integration;
use App\Http\Controllers\Controller;

class IntegrationsController extends Controller
{
    public function get()
    {
        return Integration::all();
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'expires' => 'nullable|string',
            'issued' => 'nullable|string',
            'expires_in' => 'nullable|integer',
            'mfa_token' => 'nullable|string',
            'refresh_token' => 'nullable|string',
            'token_type' => 'nullable|string',
            'user_id' => 'nullable|string'
        ]);

        $integration = Integration::create($data);

        return response($integration);
    }

    public function show($id)
    {
        return Integration::where('id', $id)->first();
    }

    public function update(Request $request)
    {
        $integration = Integration::where('id', $request->id)->first();

        $integration->update($request);

        return response($integration);
    }

    public function destroy($id)
    {
        $integration->delete();
        return response('Integration Deleted')
    }
}
