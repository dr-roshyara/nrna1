<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The kinds of roster write (v3.11 §8 "Roster and publication ordering"). */
enum RosterEntryKind: string
{
    case StewardAppointed = 'steward-appointed';
    case StewardEnded = 'steward-ended';
    case MatterReviewerAppointed = 'matter-reviewer-appointed';
    /** Identity's notice that references denote one person (IG-4); recorded, never decided by Geography. */
    case PersonsMerged = 'persons-merged';
    /** Identity's reversal of a mistaken merge (IG-5); drops only that merge's links. */
    case PersonMergeReversed = 'person-merge-reversed';
    /** A person's conflict declaration for one matter (D-84, D-84a); a later declaration supersedes an earlier one. */
    case ConflictDeclared = 'conflict-declared';
}
