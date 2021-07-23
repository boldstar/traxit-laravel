<?php


namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Automation;
use App\Http\Controllers\Controller;

class AutomationsController extends Controller
{
    public function get()
    {
        return Automation::all();
    }

    public function create(Request $request)
    {
        return response($request);
    }

    public function update(Request $requst)
    {
        return response($request);
    }

    public function delete($id) {
        return response($id);
    }
}
