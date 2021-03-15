<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Customization;
use App\Http\Controllers\Controller;

class CustomizationController extends Controller
{
    public function create(Request $request)
    {

        $data = $request->validate([
            'belongs_to' => 'required|string',
            'name' => 'required|string'
        ]);

        $customization = Customization::create($data);

        $customizations = Customization::where('belongs_to', $data['belongs_to'])->get();

        return response($customizations);
    }

    public function get(Request $request)
    {
        return response(Customization::where('belongs_to', $request->belongs_to)->get());
    }

    public function delete(Request $request)
    {
        $item_ids = $request->validate([
            'list' => 'required|array'
        ]);
        
        foreach($item_ids['list'] as $id) {
            $option = Customization::where('id', $id)->first();
            $belongs_to = $option->belongs_to; 
            $option->delete();
        }

        if($belongs_to) {
            return response(Customization::where('belongs_to', $belongs_to)->get());
        } else {
            return response([]);
        }
    }
}
