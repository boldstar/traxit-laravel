<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Bookkeeping;
use App\Models\Tenant\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookkeepingController extends Controller
{
    public function getBookkeepingAccounts()
    {
        return Bookkeeping::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|integer',
            'belongs_to' => 'nullable|string',
            'business_name' => 'nullable|string',
            'account_name' => 'required|string',
            'account_type' => 'required|string',
            'year' => 'required|string',
            'account_start_date' => 'nullable|string',
        ]);

        $account = Bookkeeping::create($data);

        return response($account);
    }

    public function storeNewYear(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|string',
            'id' => 'required|integer'
        ]);

        $accounts = Bookkeeping::where(['client_id' => $request->id, 'year' => $request->year])->get();

        foreach($accounts as $account)
        {
            $newAccount = $account->replicate();
            $newAccount['year'] = json_decode($request->year, true) + 1;
            $newAccount['jan'] = false;
            $newAccount['feb'] = false;
            $newAccount['mar'] = false;
            $newAccount['apr'] = false;
            $newAccount['may'] = false;
            $newAccount['jun'] = false;
            $newAccount['jul'] = false;
            $newAccount['aug'] = false;
            $newAccount['sep'] = false;
            $newAccount['oct'] = false;
            $newAccount['nov'] = false;
            $newAccount['dec'] = false;
            $newAccount->save();
        };

        $newAccounts = Bookkeeping::all();

        return response($newAccounts);
    }

    public function updateMonth(Request $request, Bookkeeping $bookkeeping)
    {
        $data = $request->validate([
            'mth' => 'required|string'
        ]);

        $bookkeeping[$data['mth']] = !$bookkeeping[$data['mth']];
        $bookkeeping->save();

        return response($bookkeeping);
    }

    public function updateAccount(Request $request, Bookkeeping $bookkeeping)
    {
        $data = $request->validate([
            'account_name' => 'required|string',
            'account_type' => 'required|string'
        ]);

        $bookkeeping->update($data);

        return response ($bookkeeping);
    }

    public function delete($id)
    {
        $bookkeeping = Bookkeeping::where('id', $id)->first();
        $bookkeeping->delete();

        return response('Bookkeeping Account Deleted');
    }
}
