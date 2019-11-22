<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Guest;
use App\Models\Tenant\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteGuest;

class GuestClientLoginController extends Controller
{
    public function guestLogin()
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
           $response = $http->post('http://' . $hostname->fqdn . '/oauth/token', [
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
                   //return data to the user
                   return response()->json(['access_token' => $data['access_token']]);
               } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                   if ($e->getCode() === 400) {
                       return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
                   } else if ($e->getCode() === 401) {
                       return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
           }
           return response()->json('Something went wrong on the server.', $e->getCode());
       }
    }

    public function guestInvite(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|integer',
            'send_to' => 'required|string'
        ]);

        $client = Client::where('id', $validated['client_id'])->first();
        
        try {
            if($validated['send_to'] == 'both') {
                Mail::to($client->email)->send(new InviteGuest([ 
                    'client' => $client,
                    'send_to' => $validated['send_to']
                ]));
            }
            if($validated['send_to']  == 'taxpayer' && $client->email != null) {
                Mail::to($client->email)->send(new InviteGuest([
                    'client' => $client, 
                    'send_to' => $validated['send_to']
                ]));
            }
            if($validated['send_to'] == 'spouse' && $client->spouse_email != null) {
                Mail::to($client->spouse_email)->send(new InviteGuest([
                    'client' => $client,  
                    'send_to' => $validated['send_to']
                ]));
            }
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response('The Contact(s) has been invited by email');
    }

    public function guestRegister(Request $request)
    {

        $validated = $request->validate([
            'client_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guests',
            'password' => 'required|string|min:10',
        ]);

        $client =  Client::where('id', $validated->client_id)->findOrFail();

        $guest = Guest::create([
            'client_id' => $client->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response($guest, 200);
    }
}
