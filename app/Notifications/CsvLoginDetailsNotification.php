<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Contracts\Queue\ShouldQueue;

class CsvLoginDetailsNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $userId,
        public string $name,
        public string $password,
        public string $time,
        public string $loginUrl,
        public array  $contacts = []
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $email = $notifiable->routeNotificationFor('mail'); // username

        return (new MailMessage)
            ->subject('Your Voting Account Details')
            ->view('mail.send_password_and_process', [
                'userId'   => $this->userId,
                'name'     => $this->name,
                'email'    => $email,
                'password' => $this->password,
                'time'     => $this->time,
                'loginUrl' => $this->loginUrl,
                'contacts' => $this->contacts,
            ]);
    }
}
