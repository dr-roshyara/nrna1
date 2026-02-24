<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class VerifyEmailMail extends Mailable
{
    public $user;
    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $verificationUrl)
    {
        $this->user = $user;
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->to($this->user->email)
                    ->view('emails.verify-email')
                    ->subject(__('emails.verify_email_subject'))
                    ->with([
                        'user' => $this->user,
                        'verificationUrl' => $this->verificationUrl,
                    ]);
    }
}
