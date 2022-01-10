<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendVoteSavingCode extends Notification
{
    use Queueable;
     public $vote_saving_code ;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($vote_saving_code)
    {
        //
        $this->vote_saving_code =$vote_saving_code;
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
        return (new MailMessage)->markdown('mail.send_vote_saving_code', [
            'vote_saving_code'=>$this->vote_saving_code,
             'user'=>auth()->user()
        ])->subject('Code to see & check your vote');
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
