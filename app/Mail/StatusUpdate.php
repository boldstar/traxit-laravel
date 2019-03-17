<?php

namespace App\Mail;

use App\Models\Tenant\User;
use App\Models\Tenant\Account;
use App\Models\Tenant\EmailTemplate;
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
        $template = EmailTemplate::where('title', 'Status Update')->first();
        $account = Account::first();
        $send_to = $this->client['send_to'];
        $clients = $this->client['client'];
        $sender = User::where('id', auth()->user()->id)->first();
        $email = $sender->email;
        $name = $sender->name;

        if(!file_exists('../resources/views/status.blade.php')) {
            file_put_contents('../resources/views/status.blade.php', $template->html_template);
        }

        if($this->client['test'] == true) {
            return $this->replyTo($email, $name)
            ->subject($template->subject . $account->business_name)
            ->cc('test@example.com')
            ->view('status')
            ->with([
                'phoneNumber' => $account->phone_number,
                'faxNumber' => $account->fax_number,
                'accountName' => $account->business_name,
                'accountEmail' => $account->email,
                'userEmail' => $email
            ]);
        }

        if($send_to == 'taxpayer') {
            return $this->replyTo($email, $name)
                        ->bcc($email, $name)
                        ->subject($template->subject . $account->business_name)
                        ->view('status')
                        ->with([
                            'phoneNumber' => $account->phone_number,
                            'faxNumber' => $account->fax_number,
                            'accountName' => $account->business_name,
                            'accountEmail' => $account->email,
                            'userEmail' => $email
            ]);
        }

        if($send_to == 'spouse') {
            return $this->replyTo($email, $name)
                        ->bcc($email, $name)
                        ->subject($template->subject . $account->business_name)
                        ->view('status')
                        ->with([
                            'phoneNumber' => $account->phone_number,
                            'faxNumber' => $account->fax_number,
                            'accountName' => $account->business_name,
                            'accountEmail' => $account->email,
                            'userEmail' => $email
            ]);
        }

            
        if($clients->has_spouse == true && $clients->spouse_email != null) {
            $spouse_email = $clients->spouse_email;
            return $this->replyTo($email, $name)
                        ->cc($spouse_email)
                        ->bcc($email, $name)
                        ->subject($template->subject . $account->business_name)
                        ->view('status')
                        ->with([
                            'phoneNumber' => $account->phone_number,
                            'faxNumber' => $account->fax_number,
                            'accountName' => $account->business_name,
                            'accountEmail' => $account->email,
                            'userEmail' => $email
            ]);
        };
    }
}
