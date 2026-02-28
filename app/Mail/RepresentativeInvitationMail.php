<?php

namespace App\Mail;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class RepresentativeInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $representative,
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
            'de' => 'Sie wurden als Vertreter/in zu ' . $this->organisation->name . ' hinzugefügt',
            'en' => 'You have been added as a representative to ' . $this->organisation->name,
            'np' => 'तपाई ' . $this->organisation->name . ' को प्रतिनिधि के रूप में जोड़ गए हैं',
        ];

        $templates = [
            'de' => 'emails.representative.invitation-de',
            'en' => 'emails.representative.invitation-en',
            'np' => 'emails.representative.invitation-np',
        ];

        return $this->markdown($templates[$this->locale] ?? $templates['de'])
                    ->subject($subjects[$this->locale] ?? $subjects['de'])
                    ->with([
                        'representativeName' => $this->representative->name,
                        'organisationName' => $this->organisation->name,
                        'creatorName' => $this->creator->name,
                        'setupUrl' => route('password.request'),
                        'organisationEmail' => $this->organisation->email,
                        'locale' => $this->locale,
                    ]);
    }
}
