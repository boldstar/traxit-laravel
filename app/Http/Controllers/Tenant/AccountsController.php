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
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'fax_number' => 'required|string',
            'logo' => 'nullable|string',
            'subscription' => 'required|string'
        ]);

        $account = Account::create($data);

        return response($account, 200);
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
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'fax_number' => 'required|string',
            'logo' => 'nullable|string',
            'subscription' => 'required|string'
        ]);

        $account = Account::where('id', $id)->firstOrFail();

        $account->update($data);

        return response($account, 200);
    }

}