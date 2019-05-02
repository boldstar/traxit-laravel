<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Account;
use App\Models\System\Subscription;
use App\Http\Controllers\Controller;

class AccountsController extends Controller
{
    public function account()
    {
        return Account::all();
    }

    public function uploadLogo(Request $request) {

        if (empty($request->file('file')->getRealPath())) {
            return back()->with('success','No file selected');
        }
 
        else {
            $account = Account::first();
            $path = $request->file('file')->getRealPath();
            $logo = file_get_contents($path);
            $base64 = base64_encode($logo);
            $account->logo = $base64;
            $account->save();
            return response($account, 200);
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
        $data = $request->validate([
            'business_name' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'email' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'fax_number' => 'nullable|string',
            'logo' => 'nullable|string',
            'subscription' => 'nullable|string'
        ]);

        $account = Account::create($data);

        return response($account, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOnSetup(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'required|string',
            'email' => 'nullable|string',
            'phone_number' => 'nullable|string'
        ]);

        $account = Account::create($data);

        return response()->json(['message' => 'Account details added']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'business_name' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'email' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'fax_number' => 'nullable|string',
            'logo' => 'nullable|string',
            'subscription' => 'nullable|string'
        ]);

        $account = Account::where('id', $id)->firstOrFail();

        $account->update($data);

        return response($account, 200);
    }

}
