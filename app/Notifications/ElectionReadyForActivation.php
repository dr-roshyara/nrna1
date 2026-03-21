<?php

namespace App\Notifications;

use App\Models\Election;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ElectionReadyForActivation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Election $election) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $managementUrl = route('elections.management', $this->election->id);

        return (new MailMessage)
            ->subject("Election Ready for Activation: {$this->election->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("A new election **{$this->election->name}** has been created and is ready for activation.")
            ->line("Before activating, please ensure all posts, candidates, and voters are set up.")
            ->action('Review and Activate Election', $managementUrl)
            ->line("Scheduled: {$this->election->start_date->format('F j, Y')} to {$this->election->end_date->format('F j, Y')}.");
    }
}
