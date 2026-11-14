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

        return new GovernanceDecision($version, GovernanceRule::Act, [...$refusals, ...$this->conflict($assessor, $revision, $roster, $version)]);
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

        return new GovernanceDecision($version, GovernanceRule::Act, [...$refusals, ...$this->conflict($approver, $revision, $roster, $version)]);
    }

    public function mayPublish(Revision $revision, StewardRoster $roster, int $version): GovernanceDecision
    {
        if ($revision->state() !== RevisionState::Submitted || $revision->validation() === null) {
            return new GovernanceDecision($version, GovernanceRule::Act, [GovernanceRefusal::NotSubmitted]);
        }
        // the assessing steward of the current version: the conflict declaration is re-checked at v (v3.11 §8 Checks)
        $assessorProblems = $revision->assessor() === null ? [GovernanceRefusal::NotSubmitted] : $this->conflict($revision->assessor(), $revision, $roster, $version);
        if (!$revision->validation()->structural) {
            return new GovernanceDecision($version, GovernanceRule::NonStructural, $assessorProblems);
        }
        if (!$roster->secondStewardEverAppointedAt($version)) {
            return new GovernanceDecision($version, GovernanceRule::EraASolePublication, $assessorProblems);
        }
        if ($assessorProblems !== []) {
            return new GovernanceDecision($version, GovernanceRule::SecondPersonApproved, $assessorProblems);
        }
        $refusals = [];
        foreach ($revision->countingApprovals() as $approval) {
            $problems = $this->conflict($approval->by, $revision, $roster, $version);
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

    /**
     * G-6c (design revision 3 §17): a second-person publication as D-90 evaluates it. Its counted approver is REPLAYED:
     * mayPublish at the publication's stored roster version. Exact, because a published revision is closed (G-4f) and
     * every roster query takes a version.
     *
     * @throws \LogicException if the replay gives no second person (not a second-person publication, or an integrity fault)
     */
    public function publishedApproval(int $position, Revision $revision, StewardRoster $roster, int $rosterVersion): PublishedApproval
    {
        $decision = $this->mayPublish($revision, $roster, $rosterVersion);
        $version = $revision->currentVersion();
        if ($decision->secondPerson === null || $version === null) {
            throw new \LogicException("Publication {$position}: replaying its governance at roster version {$rosterVersion} gives no counted approver.");
        }

        return new PublishedApproval($position, $revision->id(), $version->number, $version->digest, $decision->secondPerson, $revision->excludedPersons());
    }

    /**
     * G-6c-4 (D-90, D-88; design revision 3 §7): may $confirmer confirm a finding recording $defectiveApprover's defect,
     * at roster version $version? Appointed for the matter; NOT known-same as anyone in excluded(r) nor as the defective
     * approver; a D-84a declaration without a material conflict. (Whether the finding is open: FindingConfirmation.)
     */
    public function mayConfirm(PersonReference $confirmer, Revision $revision, PersonReference $defectiveApprover, StewardRoster $roster, int $version): GovernanceDecision
    {
        $refusals = $roster->mayActForAt($confirmer, $revision->id(), $version) ? [] : [GovernanceRefusal::NotAppointed];
        if ($this->isExcluded($confirmer, $revision, $roster, $version) || $roster->knownSameAt($confirmer, $defectiveApprover, $version)) {
            $refusals[] = GovernanceRefusal::ConfirmerNotIndependent;
        }

        return new GovernanceDecision($version, GovernanceRule::Act, [...$refusals, ...$this->conflict($confirmer, $revision, $roster, $version)]);
    }

    /**
     * D-84, D-84a: a decider needs a declaration for the matter; a declared interest in a judgment matter is a material
     * conflict, and the matter waits (an eligible independent decider: a non-conflicted steward, or a matter reviewer
     * appointed by the platform owner for that matter). Nothing here lets a decider declare the matter routine.
     *
     * @return list<GovernanceRefusal>
     */
    private function conflict(PersonReference $person, Revision $revision, StewardRoster $roster, int $version): array
    {
        $interest = $roster->declaredInterestAt($person, $revision->id(), $version);
        if ($interest === null) {
            return [GovernanceRefusal::ConflictDeclarationMissing];
        }

        return $interest && $revision->isJudgmentMatter() ? [GovernanceRefusal::MateriallyConflicted] : [];
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
