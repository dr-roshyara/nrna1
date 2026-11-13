<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\CandidateRepository;
use App\Contexts\ReferenceGeography\Application\Ports\RosterReader;
use App\Contexts\ReferenceGeography\Domain\Candidate;
use App\Contexts\ReferenceGeography\Domain\CandidateId;
use App\Contexts\ReferenceGeography\Domain\CandidateSubmitter;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionId;

/**
 * The Candidate use cases (G-6a; v3.11 §16; D-31). Every act:
 * - takes its actor from ActingPerson; no method accepts a person as its actor;
 * - needs a steward appointed at the roster's current version (stewards decide candidates, D-31; stewards submit
 *   imports). Organisation submissions arrive with the Organisation integration (D-147), not here;
 * - is saved against the row version it was loaded at.
 * Acceptance PROPOSES a new revision through RevisionService (see accept): the candidate never becomes a fact by
 * itself; the revision is governed and published on its own path. It reads the roster through the READ-ONLY
 * RosterReader and touches revisions only through the authorised RevisionService (no-bypass guard, rules 2 and 3).
 */
final class CandidateService
{
    public function __construct(
        private readonly CandidateRepository $candidates,
        private readonly RosterReader $roster,
        private readonly RevisionService $revisions,
        private readonly ActingPerson $acting,
    ) {
    }

    /** @param list<string> $evidence */
    public function submitImport(CandidateId $id, string $summary, ?ReferenceUnitId $subject, array $evidence): void
    {
        $actor = $this->steward();
        $this->candidates->add(Candidate::submit($id, CandidateSubmitter::steward($actor), $summary, $subject, $evidence, $actor));
    }

    public function startVerification(CandidateId $id): void
    {
        $this->act($id, static fn (Candidate $c, PersonReference $actor) => $c->startVerification($actor));
    }

    /**
     * Accept → ProposeRevision (closure design §Candidate): acceptance PREPARES a new draft revision with the given id,
     * through the one authorised path (RevisionService::prepare), so the revision provably originates from this
     * candidate and belongs to no other. An existing revision id is refused (never attached). Order: the lifecycle is
     * checked first (nothing is prepared if acceptance is refused); then the revision is prepared; then the candidate
     * is saved. If that last save loses a race, the draft remains as an unpublished, withdrawable draft (no fact).
     */
    public function accept(CandidateId $id, RevisionId $proposedRevision): void
    {
        $actor = $this->steward();
        $stored = $this->candidates->get($id);
        $stored->candidate->accept($proposedRevision, $actor);
        $this->revisions->prepare($proposedRevision);
        $this->candidates->save($stored->candidate, $stored->rowVersion);
    }

    public function linkTo(CandidateId $duplicate, CandidateId $primary): void
    {
        $primaryCandidate = $this->candidates->get($primary)->candidate;
        $this->act($duplicate, static fn (Candidate $c, PersonReference $actor) => $c->linkTo($primaryCandidate, $actor));
    }

    public function reject(CandidateId $id, string $reason): void
    {
        $this->act($id, static fn (Candidate $c, PersonReference $actor) => $c->reject($reason, $actor));
    }

    private function act(CandidateId $id, \Closure $act): void
    {
        $actor = $this->steward();
        $stored = $this->candidates->get($id);
        $act($stored->candidate, $actor);
        $this->candidates->save($stored->candidate, $stored->rowVersion);
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
