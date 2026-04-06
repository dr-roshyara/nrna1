<?php

namespace App\Mail\Membership;

use App\Models\MembershipApplication;
use App\Models\Organisation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PublicApplicationAdminNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly MembershipApplication $application,
        public readonly Organisation $organisation,
    ) {}

    public function envelope(): Envelope
    {
        $name = $this->application->applicantName();

        return new Envelope(
            subject: "New membership application — {$name}",
        );
    }

    public function content(): Content
    {
        $data = $this->application->application_data ?? [];

        return new Content(
            view: 'emails.membership.public-application-admin-notification',
            with: [
                'application'  => $this->application,
                'organisation' => $this->organisation,
                'data'         => $data,
                'reviewUrl'    => url("/organisations/{$this->organisation->slug}/membership/applications/{$this->application->id}"),
            ],
        );
    }
}
