<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Domain service: may this person submit or approve, and may this revision be published, at roster version v?
 * (v3.11 §8; D-84, D-84a, D-88, D-89; completion §5, §5a.)
 *
 * A pure function of (revision, roster at v). Governance is NOT dependency-recorded: the publication step (G-4)
 * re-evaluates it in full, under the publication lock, at the current roster version, so later appointments,
 * ends and merge notices are always seen.
 *
 * The one independence rule: a second person is valid only if NOT known-same, under E(v), as anyone in excluded(r)
 * (everyone who prepared, assessed or submitted any content version of r).
 */
final class PublicationGovernance
{
    public function maySubmit(PersonReference $assessor, Revision $revision, StewardRoster $roster, int $version): GovernanceDecision
    {
        $refusals = $roster->mayActForAt($assessor, $revision->id(), $version) ? [] : [GovernanceRefusal::NotAppointed];

        return new GovernanceDecision($version, GovernanceRule::Act, $refusals);
    }

    public function mayApprove(PersonReference $approver, Revision $revision, StewardRoster $roster, int $version): GovernanceDecision
    {
        $refusals = [];
        if (!$roster->mayActForAt($approver, $revision->id(), $version)) {
            $refusals[] = GovernanceRefusal::NotAppointed;
        }
        if ($this->isExcluded($approver, $revision, $roster, $version)) {
            $refusals[] = GovernanceRefusal::ApproverNotIndependent;
        }

        return new GovernanceDecision($version, GovernanceRule::Act, $refusals);
    }

    public function mayPublish(Revision $revision, StewardRoster $roster, int $version): GovernanceDecision
    {
        if ($revision->state() !== RevisionState::Submitted || $revision->validation() === null) {
            return new GovernanceDecision($version, GovernanceRule::Act, [GovernanceRefusal::NotSubmitted]);
        }
        if (!$revision->validation()->structural) {
            return new GovernanceDecision($version, GovernanceRule::NonStructural);
        }
        if (!$roster->secondStewardEverAppointedAt($version)) {
            return new GovernanceDecision($version, GovernanceRule::EraASolePublication);
        }
        $refusals = [];
        foreach ($revision->countingApprovals() as $approval) {
            $problems = [];
            if ($approval->rosterVersion > $version || !$roster->mayActForAt($approval->by, $revision->id(), $approval->rosterVersion)) {
                $problems[] = GovernanceRefusal::ApproverNotAppointedWhenApproving;
            }
            if ($this->isExcluded($approval->by, $revision, $roster, $version)) {
                $problems[] = GovernanceRefusal::ApproverNotIndependent;
            }
            if ($problems === []) {
                return new GovernanceDecision($version, GovernanceRule::SecondPersonApproved, [], $approval->by);
            }
            $refusals = [...$refusals, ...$problems];
        }

        return new GovernanceDecision($version, GovernanceRule::SecondPersonApproved, $refusals === [] ? [GovernanceRefusal::SecondPersonRequired] : array_values(array_unique($refusals, SORT_REGULAR)));
    }

    private function isExcluded(PersonReference $person, Revision $revision, StewardRoster $roster, int $version): bool
    {
        foreach ($revision->excludedPersons() as $excluded) {
            if ($roster->knownSameAt($person, $excluded, $version)) {
                return true;
            }
        }

        return false;
    }
}
