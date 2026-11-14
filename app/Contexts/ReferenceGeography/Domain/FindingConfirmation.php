<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * D-90 confirmation of a finding (design revision 3 §7, §8.1, §8.4 (a); accepted 2026-11-14). Pure.
 * 1. the finding must be OPEN now (FindingLifecycle: the one definition; closed, confirmed or corrected → refused);
 * 2. the digest must be the published content version's digest (the confirmer judged what was published);
 * 3. eligibility and independence at w (PublicationGovernance::mayConfirm), against the counted approver whose defect
 *    the finding records: the original approver, or the confirmer of an earlier episode (§8.4 (a)).
 * A confirmation changes no claim and is no publication approval: it is an appended notice.
 */
final class FindingConfirmation
{
    public static function decide(
        PublicationGovernance $governance,
        PersonReference $confirmer,
        int $finding,
        string $digest,
        PublishedApproval $approval,
        FindingLifecycle $lifecycle,
        Revision $revision,
        StewardRoster $roster,
        int $version,
    ): GovernanceDecision {
        if ($lifecycle->openFindingAt() !== $finding) {
            return new GovernanceDecision($version, GovernanceRule::Act, [GovernanceRefusal::FindingNotOpen]);
        }
        $defective = $lifecycle->countedApproverAt($approval->approver, $finding);
        $eligibility = $governance->mayConfirm($confirmer, $revision, $defective, $roster, $version);
        $refusals = hash_equals($approval->digest, $digest) ? [] : [GovernanceRefusal::DigestMismatch];

        return new GovernanceDecision($version, GovernanceRule::Act, [...$refusals, ...$eligibility->refusals]);
    }
}
