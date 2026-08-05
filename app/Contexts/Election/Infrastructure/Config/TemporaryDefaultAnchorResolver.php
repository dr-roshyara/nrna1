<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Config;

use App\Contexts\Election\Application\Port\EvidenceAnchorResolver;
use App\Models\Election;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * ⚠️ **INTERIM.** The Evidence Preservation Window's anchor is an open **Q-2** decision.
 * Until it is ruled, the first available candidate is used, ordered narrowest-to-broadest.
 *
 * The name says what it is. **This class is expected to be deleted**, not extended: when
 * Q-2 rules, its replacement reads one named field and the binding in
 * `ElectionServiceProvider` changes — no service, no window and no retention logic is
 * touched. That substitutability is the entire point of WP-7B-R1 (R-60 · R-70).
 *
 * **This rule was moved here unchanged**, from `ResolvesEvidencePreservationWindow`'s
 * former private `anchorOf()`. WP-7B-R1's constraint is that no externally observable
 * behaviour changes, so the candidate order and the absent-candidate result are relocated
 * rather than revised — a refactoring, not a decision.
 *
 * It resolves WHICH DATE to measure from. **It invents no duration**: the three business
 * values still arrive through {@see \App\Contexts\Election\Application\Port\EvidencePreservationDurations},
 * and an election with no candidate at all yields no window rather than a substituted one.
 *
 * Seated in Infrastructure beside `ConfiguredEvidencePreservationDurations` — the same
 * port-plus-adapter shape, one slice older.
 */
final class TemporaryDefaultAnchorResolver implements EvidenceAnchorResolver
{
    /** Narrowest to broadest. Ordering is the rule; changing it is a Q-2 act, not a refactor. */
    private const CANDIDATES = ['results_published_at', 'end_date', 'archived_at'];

    public function anchorFor(Election $election): ?DateTimeImmutable
    {
        foreach (self::CANDIDATES as $candidate) {
            $value = $election->getAttribute($candidate);

            if ($value instanceof DateTimeInterface) {
                return DateTimeImmutable::createFromInterface($value);
            }
        }

        return null;
    }
}
