<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Setting;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function get()
    {
        return response(Setting::all());
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'state' => 'required|boolean'
        ]);

        $setting = Setting::where('name', $request->name)->first();

        if($setting) {
            $setting->state = $request->state;
            $setting->save();
        } else {
            Setting::create($data);
        }

        return response(Setting::all());
    }
}
