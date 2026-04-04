<?php

namespace App\Mail;

use App\Models\OrganisationInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganisationInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly OrganisationInvitation $invitation,
        public readonly string $acceptUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to join ' . $this->invitation->organisation->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.organisation-invitation',
        );
    }
}
