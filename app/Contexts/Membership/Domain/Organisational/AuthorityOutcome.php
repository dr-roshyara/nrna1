<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The outcome of an AuthorityCheck answer (accepted D-208 §1; GTS note 1). Undetermined is NOT NotAuthorised: the rules
 * are silent or the sources are unavailable, and nothing is recorded either way (D-167, D-181). PBDIGIT-354 S2-3a.
 */
enum AuthorityOutcome: string
{
    case Authorised = 'authorised';
    case NotAuthorised = 'not-authorised';
    case Undetermined = 'undetermined';
}
