<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** Which D-84 rule a governance decision applied (recorded with the publication, v3.11 §8). */
enum GovernanceRule: string
{
    /** Era A: no second steward ever appointed; the assessing steward publishes alone (D-84). */
    case EraASolePublication = 'era-a-sole-publication';
    /** A non-structural change: never needs a second person (D-84, §14). */
    case NonStructural = 'non-structural';
    /** Era B, structural: an independent second person approved this content version (D-88, D-89). */
    case SecondPersonApproved = 'second-person-approved';
    /** An act other than publication (submission, approval). */
    case Act = 'act';
}
