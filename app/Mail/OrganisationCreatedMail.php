<?php

namespace App\Mail;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class OrganisationCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Organisation $organisation,
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
            'de' => 'Ihre Organisation wurde erstellt – ' . $this->organisation->name,
            'en' => 'Your organisation has been created – ' . $this->organisation->name,
            'np' => 'आपको संगठन बनाया गया है – ' . $this->organisation->name,
        ];

        $templates = [
            'de' => 'emails.organisation.created-de',
            'en' => 'emails.organisation.created-en',
            'np' => 'emails.organisation.created-np',
        ];

        return $this->markdown($templates[$this->locale] ?? $templates['de'])
                    ->subject($subjects[$this->locale] ?? $subjects['de'])
                    ->with([
                        'organisationName' => $this->organisation->name,
                        'creatorName' => $this->creator->name,
                        'loginUrl' => route('login'),
                        'dashboardUrl' => route('organisations.show', $this->organisation->slug),
                        'organisationEmail' => $this->organisation->email,
                        'locale' => $this->locale,
                    ]);
    }
}
