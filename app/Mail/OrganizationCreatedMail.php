<?php

namespace App\Mail;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class OrganizationCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Organization $organization,
        public User $creator
    ) {
        // Set locale for mail: use current app locale or default to German
        $this->locale = auth()->check() ? app()->getLocale() : 'de';
    }

    public function build()
    {
        // Set locale for template translation
        App::setLocale($this->locale);

        $subjects = [
            'de' => 'Ihre Organisation wurde erstellt – ' . $this->organization->name,
            'en' => 'Your organization has been created – ' . $this->organization->name,
            'np' => 'आपको संगठन बनाया गया है – ' . $this->organization->name,
        ];

        $templates = [
            'de' => 'emails.organization.created-de',
            'en' => 'emails.organization.created-en',
            'np' => 'emails.organization.created-np',
        ];

        return $this->markdown($templates[$this->locale] ?? $templates['de'])
                    ->subject($subjects[$this->locale] ?? $subjects['de'])
                    ->with([
                        'organizationName' => $this->organization->name,
                        'creatorName' => $this->creator->name,
                        'loginUrl' => route('login'),
                        'dashboardUrl' => route('organizations.show', $this->organization->slug),
                        'organizationEmail' => $this->organization->email,
                        'locale' => $this->locale,
                    ]);
    }
}
