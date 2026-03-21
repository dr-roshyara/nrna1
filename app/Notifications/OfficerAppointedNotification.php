<?php

namespace App\Notifications;

use App\Models\ElectionOfficer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class OfficerAppointedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected ElectionOfficer $officer) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $acceptUrl = URL::temporarySignedRoute(
            'organisations.election-officers.invitation.accept',
            now()->addDays(7),
            [
                'organisation' => $this->officer->organisation->slug,
                'officer'      => $this->officer->id,
            ]
        );

        return (new MailMessage)
            ->subject("Election Officer Appointment: {$this->officer->organisation->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("You have been appointed as **{$this->officer->role}** election officer for **{$this->officer->organisation->name}**.")
            ->line('**Role:** ' . ucfirst($this->officer->role))
            ->line('**Appointed by:** ' . ($this->officer->appointer?->name ?? 'Administrator'))
            ->line('**Appointed on:** ' . $this->officer->appointed_at->format('F j, Y'))
            ->action('Accept Appointment', $acceptUrl)
            ->line('If you already have an account, clicking the link will log you in and accept the appointment.')
            ->line('If you are new, you will be guided through registration first.')
            ->line('This invitation expires in 7 days.')
            ->line('If you did not expect this appointment, please contact your organisation administrator.');
    }
}
