<?php

namespace App\Mail;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class OrganizationCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $locale;

    public function __construct(
        public Organization $organization,
        public User $creator
    ) {
        // Detect locale: use creator's preference or current locale
        $this->locale = auth()->check() ? app()->getLocale() : 'de';
    }

    public function envelope(): Envelope
    {
        // Temporarily set locale for subject translation
        App::setLocale($this->locale);

        $subjects = [
            'de' => 'Ihre Organisation wurde erstellt – ' . $this->organization->name,
            'en' => 'Your organization has been created – ' . $this->organization->name,
            'np' => 'आपको संगठन बनाया गया है – ' . $this->organization->name,
        ];

        return new Envelope(
            subject: $subjects[$this->locale] ?? $subjects['de'],
        );
    }

    public function content(): Content
    {
        // Set locale for template translation
        App::setLocale($this->locale);

        $templates = [
            'de' => 'emails.organization.created-de',
            'en' => 'emails.organization.created-en',
            'np' => 'emails.organization.created-np',
        ];

        return new Content(
            markdown: $templates[$this->locale] ?? $templates['de'],
            with: [
                'organizationName' => $this->organization->name,
                'creatorName' => $this->creator->name,
                'loginUrl' => route('login'),
                'dashboardUrl' => route('organizations.show', $this->organization->slug),
                'organizationEmail' => $this->organization->email,
                'locale' => $this->locale,
            ]
        );
    }

    public function build()
    {
        return $this;
    }
}
