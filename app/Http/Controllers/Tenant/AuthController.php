<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\User;
use App\Models\Tenant\Role;
use App\Models\Tenant\Account;
use App\Models\Tenant\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Website;
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
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function userToUpdate($id)
    {
        $user = User::where('id', $id)->with('roles')->get();

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
        //find oauth table where id = 2
        $passport = DB::table('oauth_clients')->where('id', 2)->first();
        //set website to the current website connetion aka fqdn making request
        $website  = app(\Hyn\Tenancy\Environment::class)->website();
        //grab host name through the website connection by relation ship method
        $hostname = $website->hostnames()->first();
        //start a new guzzle client instance
        $http = new \GuzzleHttp\Client;
        //try to create access token
        try {
            $response = $http->post('https://' . $hostname->fqdn . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $passport->id,
                    'client_secret' => $passport->secret,
                    'username' => $request->username,
                    'password' => $request->password,
                    ]
                    ]);
                    //if token is successful grab the token body
                    $token = $response->getBody();
                    //decode the token
                    $data = json_decode($token, true);
                    //grab user details of the user loging in
                    $user = User::where('email', $request->username)->with('roles.rules')->get();
                    //grab the role of the user
                    $role = $user->pluck('roles');
                    //grab the rules associated with the role
                    $rules = $user->pluck('roles')->collapse()->pluck('rules');
                    //put the rules in the array with the access token
                    $rules->put('access_token', $data['access_token']);
                    //return data to the user
                    return response()->json(['role' => $role, 'rules' => $rules, 'fqdn' => $hostname->fqdn]);
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

    /**
     * delete a user
     */
    public function destroy(User $user) 
    {   
        //check if user has tasks before deleting. if yes return error to user
        if($user->tasks()->get()->count() > 0 ) {
            return response($user->name . ' Currently Has Tasks, Please Remove Tasks Before Deleting The User', 403);
        }
        $user->delete();
        return response('User Has Been Deleted');
    }
}