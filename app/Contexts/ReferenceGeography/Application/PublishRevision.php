<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\LockedLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
use App\Contexts\ReferenceGeography\Domain\CorrectionCoverage;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\RevisionId;

/**
 * Publishes a revision's current content version (v3.11 §5.3, §6, §8; G-4 interpretation B-1 to B-7). Everything
 * below runs under the ONE publication lock, so no roster write or Identity notice can fall between the check and
 * the append; every decision is the domain's:
 * 1. already published → AlreadyPublished (nothing written);
 * 2. the acting person must be appointed for the matter at the roster's current version (D-84 "a steward assesses
 *    and publishes");
 * 3. PublicationGovernance::mayPublish at that roster version (eras, second person, excluded(r) under E(v),
 *    approval of this content version, conflict declarations, D-230);
 * 4. staleness: a claim published after p₀ that invalidates a recorded predicate → the revision is marked Stale and
 *    kept so; nothing is published;
 * 5. append (clock rule in the ledger: refused, never adjusted).
 */
final class PublishRevision
{
    public function __construct(
        private readonly PublicationLedger $ledger,
        private readonly PublicationGovernance $governance,
        private readonly ActingPerson $acting,
    ) {
    }

    /**
     * @param int|null $corrects G-6c-5 (D-90; design revision 3 §8, §9): the position of a publication this revision
     *   CORRECTS. Allowed only while that publication has an open finding and only if this revision covers every one of
     *   its claims over the whole valid interval (CorrectionCoverage); otherwise the whole publication is refused and
     *   nothing is written (publish without the link to record a partial fix as an ordinary revision). Published with
     *   the link, the row itself resolves the finding: same act, same lock.
     */
    public function publish(RevisionId $id, ?int $corrects = null): PublicationOutcome
    {
        $actor = $this->acting->current();

        return $this->ledger->withLock(function (LockedLedger $ledger) use ($id, $actor, $corrects): PublicationOutcome {
            $stored = $ledger->revision($id);
            $revision = $stored->revision;
            $version = $revision->currentVersion()?->number;
            if ($version !== null && $ledger->isPublished($id, $version)) {
                return PublicationOutcome::alreadyPublished();
            }
            $roster = $ledger->roster();
            if (!$roster->mayActForAt($actor, $id, $roster->version())) {
                return PublicationOutcome::refused([GovernanceRefusal::NotAppointed]);
            }
            $decision = $this->governance->mayPublish($revision, $roster, $roster->version());
            if (!$decision->allowed) {
                return PublicationOutcome::refused($decision->refusals, $decision);
            }
            if ($corrects !== null) {
                $lifecycle = $ledger->lifecycleFor([$corrects])[$corrects];
                $refusals = CorrectionCoverage::refusals($lifecycle, $ledger->claimsPublishedAt($corrects), $revision->claims());
                if ($refusals !== []) {
                    return PublicationOutcome::refused($refusals, $decision);
                }
            }
            $dependencies = $revision->validation()?->dependencies ?? throw new \LogicException('A publishable revision is validated.');
            if ($dependencies->firstInvalidating($ledger->publishedAfter($dependencies->p0)) !== null) {
                $revision->markStale();
                $ledger->saveRevision($revision, $stored->rowVersion);

                return PublicationOutcome::stale();
            }

            return PublicationOutcome::published($ledger->append($revision, $decision, $corrects), $decision);
        });
    }
}
