<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Mail\ResetPasswordMail;

class MyResetPassword extends Notification
{
    
    public $broker = null;
    
    use Queueable;

     /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($data)
    {
        $this->token = $data['token'];
        $this->broker = $data['broker'];
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
        $emailData['token'] = $this->token;
        $emailData['broker'] = $this->broker;
        $this->user = $notifiable;
        $url = url('customer/password/reset/'.$this->token.'/'.base64_encode($this->user->email));
       return (new MailMessage)
           ->view('mail.reset_password_mail', ['user' => $this->user,'url'=>$url]);
    }
}
