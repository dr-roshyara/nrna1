<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * D-90 case B, after publication (design revision 3 §5, §5.1, §6, §8.4 (a); accepted 2026-11-14). Pure.
 * - affectedPersons: the components of the changed merge's persons BEFORE and AFTER the change. A publication's case
 *   can flip only if its counted approver lies in one of them (§5.1), so selecting publications by these persons
 *   misses none.
 * - evaluate: for each candidate not yet corrected, with its counted approver (the confirmer after a confirmation),
 *   defective = known-same at w as anyone in excluded(r). Defective with no open finding: a FINDING. Sound with an
 *   open finding: a CLOSURE of it. Nothing else. Ordered by publication position.
 */
final class ApprovalDefects
{
    /**
     * @param list<PersonReference> $named the persons the merge named (for a reversal: those of the reversed merge)
     * @return list<PersonReference>
     */
    public static function affectedPersons(StewardRoster $roster, int $before, int $after, array $named): array
    {
        $people = [];
        foreach ($named as $person) {
            foreach ([...$roster->componentAt($person, $before), ...$roster->componentAt($person, $after)] as $p) {
                $people[$p->value()] = $p;
            }
        }
        ksort($people);

        return array_values($people);
    }

    /**
     * @param list<PublishedApproval> $candidates
     * @param array<int, FindingLifecycle> $lifecycles publication position => its lifecycle (absent: no notices yet)
     * @return list<GovernanceNotice>
     */
    public static function evaluate(StewardRoster $roster, int $w, array $candidates, array $lifecycles, string $change): array
    {
        usort($candidates, static fn (PublishedApproval $a, PublishedApproval $b): int => $a->position <=> $b->position);
        $notices = [];
        foreach ($candidates as $approval) {
            $lifecycle = $lifecycles[$approval->position] ?? FindingLifecycle::of($approval->position, [], []);
            if ($lifecycle->correctedAt() !== null) {
                continue;   // full coverage (§9, decision 1): its facts are replaced; it is no longer evaluated
            }
            $counted = $lifecycle->countedApproverAt($approval->approver);
            $defective = false;
            foreach ($approval->excluded as $excluded) {
                $defective = $defective || $roster->knownSameAt($counted, $excluded, $w);
            }
            $open = $lifecycle->openFindingAt();
            if ($defective && $open === null) {
                $notices[] = GovernanceNotice::finding($approval->position, $roster->effectiveMergesWithin($roster->componentAt($counted, $w), $w), $w);
            } elseif (!$defective && $open !== null) {
                $notices[] = GovernanceNotice::closure($open, $change, $w);
            }
        }

        return $notices;
    }
}
