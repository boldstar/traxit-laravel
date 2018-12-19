<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\User;
use App\Models\Tenant\Role;
use App\Models\Tenant\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Hyn\Tenancy\Environment;
use Illuminate\Support\Facades\DB;
use Hyn\Tenancy\Database\Connection;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::with('roles')->get();
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = User::where('id', auth()->user()->id)->with('roles.rules')->get();

        return response($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role' => 'required|string'
        ]);

        $user->update($data);

        $user->roles()->detach();

        $user->roles()->attach(Role::where('name', $request->role)->first());

        return response($user->load('roles'), 200);
    }

    public function login(Request $request)
    {
        $passport = DB::table('oauth_clients')->where('id', 2)->first(); 

        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();

        $http = new \GuzzleHttp\Client;

        try {
            $response = $http->post('http://' . $hostname->fqdn . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $passport->id,
                    'client_secret' => $passport->secret,
                    'username' => $request->username,
                    'password' => $request->password,
                    ]
                    ]);

                    $token = $response->getBody();

                    $data = json_decode($token, true);

                    $user = User::where('email', $request->username)->with('roles.rules')->get();

                    $rules = $user->pluck('roles')->collapse()->pluck('rules');

                    $rules->put('access_token', $data['access_token']);

                    return response()->json($rules);
                } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                    if ($e->getCode() === 400) {
                        return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
                    } else if ($e->getCode() === 401) {
                        return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }
    
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string'
        ]);

       

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach(Role::where('name', $request->role)->first());

        return response($user->load('roles'), 200);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
}