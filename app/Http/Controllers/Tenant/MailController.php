<?php


namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Engagement;
use App\Models\Tenant\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyAdmin;

class MailController extends Controller
{
    public function notifyAdmins(Request $request)
    {
        $engagement = $request;
        $users = User::all();
        foreach($users as $user) {
            $role = $user->with('roles')->get();
            if($role[0]->roles[0]->name === 'Admin') {
                Mail::to($user->email)->send(new NotifyAdmin([
                    'engagement' => $engagement,
                    'user' => $user
                ]));
            };
        };

        return response('Admins Notified');
    }
}
