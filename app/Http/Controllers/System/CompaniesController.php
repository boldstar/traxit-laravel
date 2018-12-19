<?php

namespace App\Http\Controllers\System;

use App\Models\Tenant\User;
use App\Models\Tenant\Role;
use App\Models\Tenant\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Models\Hostname;
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

        $request->merge(['fqdn' => $request->fqdn . '.' . env('APP_URL_BASE')]);

        $company = Tenant::create($request);

        event(new Registered($user = $this->create($request->all())));
        
        return response()->json(['message' => 'Company has been created!'], 200);
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
}
