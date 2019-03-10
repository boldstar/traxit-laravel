<?php

namespace App\Mail;

use App\Models\Tenant\User;
use App\Models\Tenant\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusUpdate extends Mailable
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

        if($sender->has_spouse == true) {
            $spouse_email = $sender->spouse_email;
            return $this->replyTo($email, $name)
                        ->cc($spouse_email)
                        ->bcc($email, $name)
                        ->subject('Status Update From '. $account->business_name)
                        ->view('status')
                        ->with([
                            'phoneNumber' => $account->phone_number,
                            'faxNumber' => $account->fax_number,
                            'accountName' => $account->business_name,
                            'accountEmail' => $account->email,
                            'userEmail' => $email
            ]);
        };

        return $this->replyTo($email, $name)
                    ->bcc($email, $name)
                    ->subject('Status Update From '. $account->business_name)
                    ->view('status')
                    ->with([
                        'phoneNumber' => $account->phone_number,
                        'faxNumber' => $account->fax_number,
                        'accountName' => $account->business_name,
                        'accountEmail' => $account->email,
                        'userEmail' => $email
        ]);
    }
}
