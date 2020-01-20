<?php

namespace App\Mail;

use App\Models\Tenant\Account;
use App\Models\Tenant\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteGuest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Client
     */
    public $client;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $account = Account::first();
        $clients = $this->client['client'];
        $sender = User::where('id', auth()->user()->id)->first();
        $email = $sender->email;
        $name = $sender->name;
        $client_email = $this->client['send_to'] === 'taxpayer' ? $clients['email'] : $clients['spouse_email'];

        return $this->replyTo($email, $name)
                    ->subject('Portal Invite From ' . $account->business_name)
                    ->view('invite')
                    ->with([
                        'phoneNumber' => $account->phone_number,
                        'faxNumber' => $account->fax_number,
                        'accountName' => $account->business_name,
                        'accountEmail' => $account->email,
                        'client_email' => $client_email
                    ]);
    }
}
