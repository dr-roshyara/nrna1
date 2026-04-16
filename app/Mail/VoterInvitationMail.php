<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoterInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private array $content,
        private string $resetUrl,
        private string $lang = 'de'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->content['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.voter-invitation',
            with: [
                'content' => $this->content,
                'resetUrl' => $this->resetUrl,
            ],
        );
    }
}
