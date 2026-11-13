<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The Candidate lifecycle (v3.11 §16). Accepted, Linked and Rejected are final. */
enum CandidateState: string
{
    case Submitted = 'submitted';
    case UnderVerification = 'under-verification';
    case Accepted = 'accepted';
    case Linked = 'linked';
    case Rejected = 'rejected';

    public function isDecided(): bool
    {
        return in_array($this, [self::Accepted, self::Linked, self::Rejected], true);
    }
}
