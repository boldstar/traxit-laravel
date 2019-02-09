<?php

namespace App\Mail;

use App\Models\Tenant\User;
use App\Models\Tenant\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StartConversation extends Mailable
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
        $sender = User::where('id', auth()->user()->id)->first();
        $email = $sender->email;
        $name = $sender->name;

        return $this->from($email, $name)
                    ->replyTo($email, $name)
                    ->bcc($email, $name)
                    ->subject('Pending Questions From '. $account->business_name)
                    ->view('email')
                    ->with([
                        'phoneNumber' => $account->phone_number,
                        'faxNumber' => $account->fax_number,
                        'accountName' => $account->business_name,
                        'accountEmail' => $account->email,
                        'userEmail' => $email
                    ]);
    }
}
