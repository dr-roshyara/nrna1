<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;

final readonly class AuthorityDecision
{
    private function __construct(private bool $granted, private AuthorityEvidence $evidence)
    {
    }

    public static function granted(AuthorityEvidence $evidence): self
    {
        return new self(true, $evidence);
    }

    public static function denied(AuthorityEvidence $evidence): self
    {
        return new self(false, $evidence);
    }

    public function isGranted(): bool
    {
        return $this->granted;
    }

    public function evidence(): AuthorityEvidence
    {
        return $this->evidence;
    }
}
