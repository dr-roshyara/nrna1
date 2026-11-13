<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\ObjectionRepository;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedClaims;
use App\Contexts\ReferenceGeography\Application\Ports\RosterReader;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\Objection;
use App\Contexts\ReferenceGeography\Domain\ObjectionAssessment;
use App\Contexts\ReferenceGeography\Domain\ObjectionId;
use App\Contexts\ReferenceGeography\Domain\ObjectionRefused;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\RevisionId;

/**
 * The Objection use cases (G-6b; strategic baseline S1.1; D-84; D-84a). Every act:
 * - takes its actor from ActingPerson; needs a steward appointed at the roster's current version (read-only roster);
 * - is saved against the row version it was loaded at.
 * A steward records an organisation's objection on its behalf (direct organisation submission waits for D-147). Only a
 * PUBLISHED claim can be objected to; reconsideration needs evidence not already cited against that claim.
 * Correction and dispute PROPOSE a new revision through RevisionService, after the lifecycle allowed the outcome:
 * the published claim is unchanged until that revision is itself governed and published.
 */
final class ObjectionService
{
    public function __construct(
        private readonly ObjectionRepository $objections,
        private readonly RosterReader $roster,
        private readonly RevisionService $revisions,
        private readonly PublishedClaims $published,
        private readonly ActingPerson $acting,
    ) {
    }

    /** @param list<string> $evidence */
    public function submitOnBehalf(ObjectionId $id, string $organisationId, ClaimId $target, string $statement, array $evidence): void
    {
        $actor = $this->steward();
        if (!$this->published->isPublished($target)) {
            throw new \OutOfBoundsException("Claim {$target->value()} is not published; only a published fact can be objected to.");
        }
        $new = array_diff($evidence, $this->objections->evidenceCitedAgainst($target));
        if ($new === []) {
            throw new ObjectionRefused('Reconsideration is a new objection with NEW evidence: everything cited was already cited against this claim.');
        }
        $this->objections->add(Objection::submitOnBehalf($id, $organisationId, $target, $statement, $evidence, $actor));
    }

    public function assess(ObjectionId $id, bool $authentic, bool $mandate, bool $applicable): void
    {
        $this->act($id, static fn (Objection $o, PersonReference $actor) => $o->assess(new ObjectionAssessment($authentic, $mandate, $applicable), $actor));
    }

    public function correct(ObjectionId $id, RevisionId $proposedRevision): void
    {
        $this->decideWithRevision($id, $proposedRevision, static fn (Objection $o, PersonReference $actor) => $o->correct($proposedRevision, $actor));
    }

    public function dispute(ObjectionId $id, RevisionId $proposedRevision): void
    {
        $this->decideWithRevision($id, $proposedRevision, static fn (Objection $o, PersonReference $actor) => $o->dispute($proposedRevision, $actor));
    }

    public function reject(ObjectionId $id, string $reason): void
    {
        $this->act($id, static fn (Objection $o, PersonReference $actor) => $o->reject($reason, $actor));
    }

    /** Lifecycle first (nothing prepared if refused), then the revision, then the objection. */
    private function decideWithRevision(ObjectionId $id, RevisionId $proposedRevision, \Closure $decide): void
    {
        $actor = $this->steward();
        $stored = $this->objections->get($id);
        $decide($stored->objection, $actor);
        $this->revisions->prepare($proposedRevision);
        $this->objections->save($stored->objection, $stored->rowVersion);
    }

    private function act(ObjectionId $id, \Closure $act): void
    {
        $actor = $this->steward();
        $stored = $this->objections->get($id);
        $act($stored->objection, $actor);
        $this->objections->save($stored->objection, $stored->rowVersion);
    }

    private function steward(): PersonReference
    {
        $actor = $this->acting->current();
        $roster = $this->roster->current();
        if (!$roster->isStewardAt($actor, $roster->version())) {
            throw new GeographyActRefused([GovernanceRefusal::NotAppointed]);
        }

        return $actor;
    }
}
