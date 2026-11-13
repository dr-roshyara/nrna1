<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Structural or not (v3.11 §14; D-84, D-87, D-91, D-92): derived from claim KINDS only, so it cannot be set by the
 * preparer, evaded by labelling a change a "correction", or by splitting it (each part keeps its kinds).
 */
final class StructuralClassification
{
    public static function isStructuralKind(ClaimKind $kind): bool
    {
        return match ($kind) {
            ClaimKind::Existence, ClaimKind::Containment, ClaimKind::Classification, ClaimKind::Succession, ClaimKind::Boundary,
            ClaimKind::ProfileScheme, ClaimKind::ProfileTier, ClaimKind::ProfileClass,
            ClaimKind::Issuer, ClaimKind::Mandate, ClaimKind::Competence, ClaimKind::Precedence,
            ClaimKind::Reconciliation, ClaimKind::Admission => true,
            ClaimKind::Identifier, ClaimKind::SourceName, ClaimKind::CldrName, ClaimKind::CldrInheritance,
            ClaimKind::Grouping, ClaimKind::Dispute, ClaimKind::PlatformDefaultLocale => false,
        };
    }

    /** @param list<Claim> $claims */
    public static function ofClaims(array $claims): bool
    {
        foreach ($claims as $claim) {
            if (self::isStructuralKind($claim->slot->kind())) {
                return true;
            }
        }

        return false;
    }
}
