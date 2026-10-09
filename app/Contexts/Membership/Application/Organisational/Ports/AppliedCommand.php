<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Application\Organisational\Appended;

/** What an idempotency key was used for: the command's fingerprint and the fact it recorded (IDEM-4: appended outcomes only). */
final readonly class AppliedCommand
{
    public function __construct(private string $fingerprint, private Appended $appended)
    {
    }

    public function fingerprint(): string
    {
        return $this->fingerprint;
    }

    public function appended(): Appended
    {
        return $this->appended;
    }
}
