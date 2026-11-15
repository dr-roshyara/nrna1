<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * How the authority behind an answer was established: a closed set (PBDIGIT-354 S2-3a, D-238).
 * - worked out · asserted · gap declared · contradiction · not established: accepted D-208 §1 (GTS §3; D-181);
 * - interim role: D-177 §8 (a technical role check, never for a ledger organisation);
 * - own act: D-203, valid ONLY for the self-service `record-resignation` (actor = subject). Membership enforces that
 *   restriction on receipt (DecideAndAppend).
 */
enum CompetenceKind: string
{
    case WorkedOut = 'worked-out';
    case Asserted = 'asserted';
    case GapDeclared = 'gap-declared';
    case Contradiction = 'contradiction';
    case NotEstablished = 'not-established';
    case InterimRole = 'interim-role';
    case OwnAct = 'own-act';
}
