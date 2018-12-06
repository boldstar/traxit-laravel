<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function account()
    {
        return Account::all();
    }
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
    public function show($id)
    {
        $user = User::with('roles')->find($id);

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
        $http = new \GuzzleHttp\Client;

        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
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