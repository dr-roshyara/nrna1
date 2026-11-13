<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\LockedLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
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

    public function publish(RevisionId $id): PublicationOutcome
    {
        $actor = $this->acting->current();

        return $this->ledger->withLock(function (LockedLedger $ledger) use ($id, $actor): PublicationOutcome {
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
            $dependencies = $revision->validation()?->dependencies ?? throw new \LogicException('A publishable revision is validated.');
            if ($dependencies->firstInvalidating($ledger->publishedAfter($dependencies->p0)) !== null) {
                $revision->markStale();
                $ledger->saveRevision($revision, $stored->rowVersion);

                return PublicationOutcome::stale();
            }

            return PublicationOutcome::published($ledger->append($revision, $decision), $decision);
        });
    }
}
