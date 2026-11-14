<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

/** TEST ONLY (PBDIGIT-345 task 7, T-19): a legacy-shaped queued mail (ShouldQueue, no connection of its own). */
final class LegacyStyleQueuedMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function build(): self
    {
        return $this->subject('T-19 characterisation')->html('<p>T-19</p>');
    }
}
