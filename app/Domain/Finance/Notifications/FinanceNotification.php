<?php

namespace App\Domain\Finance\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinanceNotification extends Notification
{
    use Queueable;

    private $user;
    private $financeInfo;
    private  $type;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $financeInfo, $type)
    {
        //
        $this->user         =$user;
        $this->financeInfo  =$financeInfo;
        $this->type         =$type;

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
        //send emails to treasurer and the sender
        return (new MailMessage)->markdown('mail.notify_finance',[
           'user'       =>$this->user,
           'finance'    => $this->financeInfo,
           'type'       =>$this->type

        ]) ->subject('New Financial Sheet');


        return (new MailMessage)->markdown('mail.notify_finance');
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
