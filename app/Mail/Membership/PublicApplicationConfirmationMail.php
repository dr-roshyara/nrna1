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

class PublicApplicationConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly MembershipApplication $application,
        public readonly Organisation $organisation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your membership application to {$this->organisation->name} has been received",
        );
    }

    public function content(): Content
    {
        $data     = $this->application->application_data ?? [];
        $firstName = $data['first_name'] ?? 'Applicant';

        return new Content(
            view: 'emails.membership.public-application-confirmation',
            with: [
                'firstName'    => $firstName,
                'organisation' => $this->organisation,
            ],
        );
    }
}
