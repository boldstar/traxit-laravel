<?php

namespace App\Http\Controllers\Tenant;


use App\Models\Tenant\User;
use Illuminate\Http\Request;
use App\Mail\StartConversation;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $testUser = 'thomasweems3@gmail.com';

        Mail::to($testUser->send(new StartConversation()));

        return response('Email Was Sent');
    }
}
