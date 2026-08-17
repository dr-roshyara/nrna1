<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Policy;

use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Gate\RequiredVotes;

/**
 * P-2 `GateIntervalClassification` (stateless): OPEN / decided-pass / decided-failure /
 * unachievable — derived ON RECORDED FACTS ONLY (EM-GOV-068; R-F2; EM-ARCH-001 §2e).
 *
 * Semantics (I-11):
 *  · decided-pass    — accepts ≥ required
 *  · decided-failure — accepts can no longer reach required COUNTING EVERY SEAT THAT
 *                      COULD STILL EXPRESS a position (objections did it — a decision;
 *                      a vacant unexpressed seat could still express after filling, so
 *                      it counts here)
 *  · OPEN            — undecided ∧ achievable on recorded facts (unexpressed NON-VACANT
 *                      seats could supply the missing accepts); NO time input exists
 *  · unachievable    — mathematical impossibility on recorded facts (vacancy
 *                      arithmetic), never a decision (R-F2: vacancy is never a
 *                      decided failure)
 *
 * Temporary unavailability is NOT a recorded fact of this classification —
 * it changes nothing (EM-GOV-064; S-06: no third exit).
 */
final class GateIntervalClassification
{
    private function __construct()
    {
    }

    /**
     * @param array<string, AcceptancePosition> $positions keyed by seat id
     * @param list<string> $vacantSeatIds seats vacant by recorded vacancy events
     */
    public static function classify(
        int $constitutedSize,
        RequiredVotes $required,
        array $positions,
        array $vacantSeatIds,
    ): GateIntervalState {
        $accepts = count(array_filter($positions, static fn (AcceptancePosition $p) => $p === AcceptancePosition::Accept));
        $expressed = count($positions);
        $r = $required->count();

        if ($accepts >= $r) {
            return GateIntervalState::DecidedPass;
        }

        // DECIDED failure is a pure function of OBJECTIONS — never of vacancies.
        // Algebra: accepts + unexpressedAll = constituted − objections, so this
        // condition is exactly `objections > constituted − required` (at 3/2: two
        // objections — R-F2). Every unexpressed seat, vacant or not, is credited
        // as a potential future accept (a replacement expresses where the seat has
        // not — EM-GOV-066), so a vacancy can never trip this branch: it fires only
        // when objections alone made satisfaction PERMANENTLY impossible — a
        // decision, which no future filling can undo (contrast Unachievable below,
        // which restoration returns to OPEN — EM-GOV-059(c)).
        $unexpressedAll = $constitutedSize - $expressed;
        if ($accepts + $unexpressedAll < $r) {
            return GateIntervalState::DecidedFailure;
        }

        // Achievability on recorded facts: only unexpressed NON-VACANT seats can
        // presently supply a position (a vacant seat expresses again only after a
        // recorded filling — a new recorded fact, re-derived then: EM-GOV-059(c)).
        $vacantUnexpressed = count(array_filter(
            $vacantSeatIds,
            static fn (string $seatId) => ! array_key_exists($seatId, $positions),
        ));
        $unexpressedNonVacant = $unexpressedAll - $vacantUnexpressed;

        if ($accepts + $unexpressedNonVacant >= $r) {
            return GateIntervalState::Open;
        }

        return GateIntervalState::Unachievable;
    }
}
