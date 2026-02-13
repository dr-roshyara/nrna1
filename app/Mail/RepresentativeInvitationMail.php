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

class RepresentativeInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $locale;

    public function __construct(
        public User $representative,
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
            'de' => 'Sie wurden als Vertreter/in zu ' . $this->organization->name . ' hinzugefügt',
            'en' => 'You have been added as a representative to ' . $this->organization->name,
            'np' => 'तपाई ' . $this->organization->name . ' को प्रतिनिधि के रूप में जोड़ गए हैं',
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
            'de' => 'emails.representative.invitation-de',
            'en' => 'emails.representative.invitation-en',
            'np' => 'emails.representative.invitation-np',
        ];

        return new Content(
            markdown: $templates[$this->locale] ?? $templates['de'],
            with: [
                'representativeName' => $this->representative->name,
                'organizationName' => $this->organization->name,
                'creatorName' => $this->creator->name,
                'setupUrl' => route('password.request'),
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
