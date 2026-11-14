<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\LockedLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
use App\Contexts\ReferenceGeography\Domain\FindingConfirmation;
use App\Contexts\ReferenceGeography\Domain\GovernanceNotice;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;

/**
 * D-90 confirmation (design revision 3 §7, §8.4 (a); accepted 2026-11-14): an eligible, independent steward confirms an
 * open finding, naming the published content digest they judged. Under the ONE publication lock, in one transaction:
 * 1. the finding and the publication it concerns (not a finding → refused);
 * 2. the roster as committed (w), the publication's counted approver replayed (§17), its lifecycle;
 * 3. FindingConfirmation decides (open; digest; appointed; independent of excluded(r) and of the defective approver;
 *    declaration without material conflict);
 * 4. appended as a confirmation notice; the ledger indexes the confirmer with the publication, so later merges
 *    re-evaluate them. Nothing else changes: no claim, no earlier row.
 * The actor is the signed-in person (ActingPerson, D-225), never a parameter. Concurrent confirmations serialise on the
 * lock: the later one finds the finding no longer open. The database's one-resolution index is the backstop.
 */
final class ConfirmPublication
{
    public function __construct(
        private readonly PublicationLedger $ledger,
        private readonly PublicationGovernance $governance,
        private readonly ActingPerson $acting,
    ) {
    }

    public function confirm(int $finding, string $digest): ConfirmationOutcome
    {
        $actor = $this->acting->current();

        return $this->ledger->withLock(function (LockedLedger $ledger) use ($finding, $digest, $actor): ConfirmationOutcome {
            $publication = $ledger->findingConcerning($finding);
            if ($publication === null) {
                return ConfirmationOutcome::refused([GovernanceRefusal::FindingNotOpen]);
            }
            $roster = $ledger->roster();
            $w = $roster->version();
            $approval = $this->governance->publishedApproval($publication->position, $publication->revision, $roster, $publication->rosterVersion);
            $lifecycle = $ledger->lifecycleFor([$publication->position])[$publication->position];
            $decision = FindingConfirmation::decide($this->governance, $actor, $finding, $digest, $approval, $lifecycle, $publication->revision, $roster, $w);
            if (!$decision->allowed) {
                return ConfirmationOutcome::refused($decision->refusals);
            }

            return ConfirmationOutcome::confirmed($ledger->appendNotice(GovernanceNotice::confirmation($finding, $actor, $digest, $w)));
        });
    }
}
