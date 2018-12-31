<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Hyn\Tenancy\Environment;

class PasswordResetRequest extends Notification implements ShouldQueue
{
    use Queueable;
    protected $token;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($token)
    {
        $this->token = $token;
    }
    /**
    * Get the notification's delivery channels.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function via($notifiable)
    {
        return ['mail'];
    }
     /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
     public function toMail($notifiable)
     {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();

        // $url = url('http://localhost:8080/reset-password/'.$this->token);
        $url = url('https://'. $hostname->subdomain.'.traxit.io/reset-password/'.$this->token);
        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account. Please click the below button.')
            ->action('Reset Password', url($url))
            ->line('If you did not request a password reset, please contact your administrator for immidiate action.');
    }
    /**
    * Get the array representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}