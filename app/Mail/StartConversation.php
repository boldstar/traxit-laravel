<?php

namespace App\Mail;

use App\Models\Tenant\User;
use App\Models\Tenant\Account;
use App\Models\Tenant\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\View\View;


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
        $template = EmailTemplate::where('title', 'Pending Questions')->first();
        $account = Account::first();
        $send_to = $this->client['send_to'];
        $clients = $this->client['client'];
        $sender = User::where('id', auth()->user()->id)->first();
        $email = $sender->email;
        $name = $sender->name;

        if(!file_exists('../resources/views/pending.blade.php')) {
            file_put_contents('../resources/views/pending.blade.php', $template->html_template);
        }

        $this->withSwiftMessage(function ($message) {
            $account = Account::first();
            $message->setFrom([
                'email@example.com' => $account->business_name
            ]);
        });

        if($this->client['test'] == true) {
            return $this->replyTo($email, $name)
            ->subject($template->subject . $account->business_name)
            ->cc('test@example.com')
            ->view('pending')
            ->with([
                'phoneNumber' => $account->phone_number,
                'faxNumber' => $account->fax_number,
                'accountName' => $account->business_name,
                'accountEmail' => $account->email,
                'userEmail' => $email
            ]);
        }

        if($send_to == 'both') {
            $spouse_email = $clients->spouse_email;
            return $this->replyTo($email, $name)
                        ->cc($spouse_email)
                        ->bcc($email, $name)
                        ->subject($template->subject . $account->business_name)
                        ->view('pending')
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
                    ->subject($template->subject . $account->business_name)
                    ->view('pending')
                    ->with([
                        'phoneNumber' => $account->phone_number,
                        'faxNumber' => $account->fax_number,
                        'accountName' => $account->business_name,
                        'accountEmail' => $account->email,
                        'userEmail' => $email
        ]);
    }
}
