<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Tenant\Account;

class NotifyAdmin extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The demo object instance.
     *
     * @var Engagement
     */
    public $engagement;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($engagement)
    {
        $this->engagement = $engagement;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->withSwiftMessage(function ($message) {
            $account = Account::first();
            $message->setFrom([
                'email@example.com' => $account->business_name
            ]);
        });

        return $this->subject('Engagement Status Updated')
                    ->view('notify');
    }
}
