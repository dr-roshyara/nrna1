<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/** RA-7: the basis on which a fact was recorded. */
enum ProvenanceKind: string
{
    case Decision = 'decision';
    case Rule = 'rule';
    case Attested = 'attested';
}
