<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;
class SendFirstVerificationCode extends Notification
{
    use Queueable;
    
    public $user;
    public $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $code)
    {
        //
        $this->user =$user;
        $this->code =$code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail', 'database'];
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
        return (new MailMessage)->markdown('mail.send_first_verification_code',[
           'user'=>$this->user, 
           'code' => $this->code,
        ]) ->subject('Code to open voting form');
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
            // 'info'=>[
            //     'message' =>'You have received the verification code in your email.',
            //     'code'=>$this->code->code1,
            //     'sent'=>Carbon::now()

            // ]
        ];
    }
}
