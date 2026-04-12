<?php

namespace App\Events\Newsletter;

use App\Models\NewsletterRecipient;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsletterEmailFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly NewsletterRecipient $recipient,
        public readonly string $error
    ) {}
}
