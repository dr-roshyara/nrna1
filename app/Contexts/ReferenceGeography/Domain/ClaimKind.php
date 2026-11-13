<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The 21 claim kinds of v3.11 §3, exactly. SOURCE_NAME has two key shapes (single- and set-valued), so there are
 * 22 shapes. "Withdrawal" is not a kind: it is a claim with value none in the target's own slot.
 */
enum ClaimKind: string
{
    case Existence = 'EXISTENCE';
    case Containment = 'CONTAINMENT';
    case Classification = 'CLASSIFICATION';
    case Boundary = 'BOUNDARY';
    case Succession = 'SUCCESSION';
    case Identifier = 'IDENTIFIER';
    case Grouping = 'GROUPING';
    case Admission = 'ADMISSION';
    case SourceName = 'SOURCE_NAME';
    case CldrName = 'CLDR_NAME';
    case CldrInheritance = 'CLDR_INHERITANCE';
    case Issuer = 'ISSUER';
    case Mandate = 'MANDATE';
    case Competence = 'COMPETENCE';
    case Precedence = 'PRECEDENCE';
    case Dispute = 'DISPUTE';
    case Reconciliation = 'RECONCILIATION';
    case ProfileScheme = 'PROFILE_SCHEME';
    case ProfileTier = 'PROFILE_TIER';
    case ProfileClass = 'PROFILE_CLASS';
    case PlatformDefaultLocale = 'PLATFORM_DEFAULT_LOCALE';
}
