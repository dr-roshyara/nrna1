<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The read-side business rules (G-5a; v3.11 §4, §12), over published slot histories only:
 * - belief(slot, t, p) = the highest-position claim ≤ p covering t; a withdrawal gives none;
 * - a dispute flags a fact only when its target is the claim IN FORCE and its latest state ≤ p is "disputed"; it never
 *   changes the value (a superseded claim keeps its marker, historically);
 * - a merge holds for all time and changes only by knowledge: the latest reconciliation claim ≤ p; NONE = unmerged.
 */
final class PlaceBelief
{
    private const DISPUTED = 'disputed';

    /**
     * @param list<SlotHistory> $subjectSlots published slots whose subject is $unit
     * @param array<string, SlotHistory> $disputes DISPUTE histories, keyed by target claim id
     */
    public static function of(ReferenceUnitId $unit, array $subjectSlots, array $disputes, ValidDate $t, int $p): PlaceFacts
    {
        $known = false;
        $exists = false;
        $parents = $classifications = $identifiers = $names = $disputed = [];
        $mergedInto = null;

        foreach ($subjectSlots as $history) {
            $slot = $history->slot();
            if ($slot->subject() === null || !$slot->subject()->equals($unit)) {
                throw new \InvalidArgumentException('A slot of another subject was given for ' . $unit->value() . '.');
            }
            if ($slot->kind() === ClaimKind::Reconciliation) {
                $mergedInto = self::resolve($unit, $history, $p)?->canonical;
                continue;
            }
            $inForce = $history->claimAt($t, $p);
            if ($slot->kind() === ClaimKind::Existence) {
                $known = $known || $history->latest($p) !== null;
            }
            if ($inForce === null) {
                continue;
            }
            $dispute = isset($disputes[$inForce->id->value()]) ? $disputes[$inForce->id->value()]->latest($p) : null;
            if ($dispute !== null && !$dispute->value->isNone() && $dispute->value->value() === self::DISPUTED) {
                $disputed[$slot->kind()->value] = true;
            }
            if ($inForce->value->isNone()) {
                continue;
            }
            $value = $inForce->value->value();
            match ($slot->kind()) {
                ClaimKind::Existence => $exists = true,
                ClaimKind::Containment => $parents[(string) $slot->component('scheme')] = $value,
                ClaimKind::Classification => $classifications[(string) $slot->component('scheme')] = $value,
                ClaimKind::Identifier => $identifiers[(string) $slot->component('namespace')] = $value,
                ClaimKind::SourceName => $names[] = ['source' => (string) $slot->component('source'), 'language' => (string) $slot->component('language'), 'type' => (string) $slot->component('name_type'), 'value' => $value],
                default => null,
            };
        }
        ksort($parents);
        ksort($classifications);
        ksort($identifiers);
        usort($names, static fn (array $a, array $b): int => [$a['source'], $a['language'], $a['type'], $a['value']] <=> [$b['source'], $b['language'], $b['type'], $b['value']]);
        $disputedKinds = array_keys($disputed);
        sort($disputedKinds);

        return new PlaceFacts($unit, $known, $exists, $parents, $classifications, $identifiers, $names, $disputedKinds, $mergedInto);
    }

    /** v3.11 §12: B → A as known at p, or null (never merged, or reversed). One level only (R-2), so no chain to follow. */
    public static function resolve(ReferenceUnitId $unit, SlotHistory $reconciliation, int $p): ?IdentityResolution
    {
        if (!$reconciliation->slot()->equals(SlotKey::reconciliation($unit))) {
            throw new \InvalidArgumentException('Not the reconciliation slot of ' . $unit->value() . '.');
        }
        $latest = $reconciliation->latest($p);
        if ($latest === null || $latest->value->isNone()) {
            return null;
        }

        return new IdentityResolution(ReferenceUnitId::fromString($latest->value->value()), (int) $reconciliation->positionOf($latest->id), $latest->id);
    }
}
