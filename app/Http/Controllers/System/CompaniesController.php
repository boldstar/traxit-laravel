<?php

namespace App\Http\Controllers\System;

use \Stripe\Plan;
use \Stripe\Stripe;
use App\Models\Tenant\User;
use App\Models\Tenant\Role;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Models\Hostname;
use App\Models\System\Hostname as HostnameModel;
use Illuminate\Support\Facades\DB;
use Hyn\Tenancy\Environment;
use Illuminate\Support\Facades\Config;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Website::with('hostnames')->get();        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $env = app(Environment::class);
            
        if ($fqdn = optional($env->hostname())->fqdn) {
            config(['database.default' => $uuid]);
        }
        
        $website = Website::where('uuid', $uuid)->with('hostnames')->get();
        
        $users = DB::table($uuid . '.users')->get();

        return response()->json(['users' => $users, 'website' => $website]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCompanyToUpdate($uuid)
    {
        $env = app(Environment::class);
            
        if ($fqdn = optional($env->hostname())->fqdn) {
            config(['database.default' => $uuid]);
        }
        
        $company = Website::where('uuid', $uuid)->first();

        return response($company);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $company = Website::where('uuid', $uuid)->firstOrFail();

        $data = $request->validate([
            'company' => 'required|string',
            'email' => 'required|string',
            'number' => 'required|string'
        ]);

        $company->update($data);

        return response($company);
    }
    

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        
        $company = Tenant::create($request);
        
        event(new Registered($user = $this->create($request->all())));

        $user->roles()->attach(Role::where('name', 'Admin')->first());
        
        return response()->json(['message' => 'A New Company Has Been Created!'], 200);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function freeTrialRegister(Request $request)
    {
        $this->validator($request->all())->validate();
        
        $company = Tenant::create($request);
        
        event(new Registered($user = $this->create($request->all())));

        $user->roles()->attach(Role::where('name', 'Admin')->first());

        $host = HostnameModel::where('fqdn', $company->hostname->fqdn)->first();

        $host->trial_ends_at = now()->addDays(1);
        $host->save();
        
        return response(200);
    }

    /**
     * get subscription plan
     */
    public function freeTrialPlan() 
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $plans = Plan::all();

        return $plans;
    }

       /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company' => 'required|string',
            'company_email' => 'nullable|string',
            'company_number' => 'nullable|string',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'fqdn' => 'required|unique:system.hostnames'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {   
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $website = Website::where('uuid', $uuid)->first();
        $hostname = Hostname::where('website_id', $website->id)->first();

        app(HostnameRepository::class)->delete($hostname, true);
        app(WebsiteRepository::class)->delete($website, true);
        
        return response('Tenant Deleted Succesfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAccount($uuid)
    {
        $env = app(Environment::class);
            
        if ($fqdn = optional($env->hostname())->fqdn) {
            config(['database.default' => $uuid]);
        }
        
        $account = DB::table($uuid . '.accounts')->get();

        return response($account);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCompanyAccount(Request $request, $uuid)
    {

        $collection = DB::table($uuid . '.accounts')->get();

        $account = $collection->pluck('id');

        $accountToUpdate = Account::where('id', $account[0])->firstOrFail();

        $data = $request->validate([
            'business_name' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'fax_number' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'subscription' => 'required|string'
        ]);

        $accountToUpdate->update($data);

        return response('Update Was Succesful', 200);
    }

}
