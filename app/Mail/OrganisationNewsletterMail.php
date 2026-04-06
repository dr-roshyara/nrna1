<?php

namespace App\Mail;

use App\Models\NewsletterRecipient;
use App\Models\OrganisationNewsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganisationNewsletterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly OrganisationNewsletter $newsletter,
        public readonly NewsletterRecipient $recipient
    ) {}

    public function envelope(): Envelope
    {
        $org     = $this->newsletter->organisation;
        $from    = $org?->email ?? config('mail.from.address');
        $fromName = $org?->name ?? config('mail.from.name');

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from, $fromName),
            subject: $this->newsletter->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->newsletter->html_content,
        );
    }

    public function headers(): \Illuminate\Mail\Mailables\Headers
    {
        $token = $this->recipient->member?->newsletter_unsubscribe_token;
        $unsubUrl = $token ? route('newsletter.unsubscribe', $token) : null;

        return new \Illuminate\Mail\Mailables\Headers(
            text: $unsubUrl ? ['List-Unsubscribe' => "<{$unsubUrl}>"] : []
        );
    }
}
