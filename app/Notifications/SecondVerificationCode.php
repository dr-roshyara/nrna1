<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SecondVerificationCode extends Notification
{
    use Queueable;
    public $user;
    public $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        //
        $this->user =$user;
        $this->code =$this->user->code;
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
        return (new MailMessage)->markdown('mail.send_second_verification_code', [
            'user' => $this->user,
            'code' => $this->code->code2,
        ])
        ->subject('Vote Conformation Code');
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
