<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedAtlasSource;
use App\Contexts\ReferenceGeography\Application\Ports\RevisionRepository;
use App\Contexts\ReferenceGeography\Application\Ports\StewardRosterStore;
use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\JudgmentReason;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use App\Contexts\ReferenceGeography\Domain\ValidationResult;

/**
 * The revision lifecycle use cases (v3.11 §5, §8; D-84; G-4 interpretation B-9). Publication is PublishRevision.
 *
 * Every act:
 * - takes its actor from ActingPerson; no method accepts a person as its actor;
 * - needs the actor appointed for the matter at the roster's current version (the preparer is "any steward";
 *   a matter reviewer acts for its matter);
 * - is refused on a revision with a published version (closed; a correction is a new revision);
 * - is saved with the row version it was loaded at (a concurrent change is refused).
 * Submission and approval additionally pass PublicationGovernance::maySubmit / mayApprove; publication re-checks
 * all of it under the lock.
 */
final class RevisionService
{
    public function __construct(
        private readonly RevisionRepository $revisions,
        private readonly StewardRosterStore $roster,
        private readonly PublishedAtlasSource $atlas,
        private readonly ActingPerson $acting,
        private readonly PublicationGovernance $governance,
        private readonly RevisionValidator $validator,
    ) {
    }

    public function prepare(RevisionId $id): void
    {
        $actor = $this->acting->current();
        $this->requireAppointed($this->roster->current(), $actor, $id);
        $this->revisions->add(Revision::prepare($id, $actor));
    }

    /**
     * G-8 (the evidenced-dataset import): prepares a draft holding all the evidence and claims at once, in ONE save, so
     * an import is all or nothing. The same authority as prepare (the actor appointed for the matter); the aggregate's
     * own rules apply to every claim (slot overlap, evidence identity). Still only a draft.
     *
     * @param list<EvidenceVersion> $evidence
     * @param list<Claim> $claims
     */
    public function prepareWithContent(RevisionId $id, array $evidence, array $claims): void
    {
        $actor = $this->acting->current();
        $this->requireAppointed($this->roster->current(), $actor, $id);
        $revision = Revision::prepare($id, $actor);
        foreach ($evidence as $e) {
            $revision->attachEvidence($e, $actor);
        }
        foreach ($claims as $claim) {
            $revision->addClaim($claim, $actor);
        }
        $this->revisions->add($revision);
    }

    public function addClaim(RevisionId $id, Claim $claim): void
    {
        $this->act($id, static fn (Revision $r, PersonReference $actor) => $r->addClaim($claim, $actor));
    }

    public function removeClaim(RevisionId $id, ClaimId $claim): void
    {
        $this->act($id, static fn (Revision $r, PersonReference $actor) => $r->removeClaim($claim, $actor));
    }

    public function attachEvidence(RevisionId $id, EvidenceVersion $evidence): void
    {
        $this->act($id, static fn (Revision $r, PersonReference $actor) => $r->attachEvidence($evidence, $actor));
    }

    public function recordAssessment(RevisionId $id, AssessmentKind $kind, string $outcome): void
    {
        $this->act($id, static fn (Revision $r, PersonReference $actor) => $r->recordAssessment(new Assessment($kind, $outcome, $actor)));
    }

    public function flagJudgmentMatter(RevisionId $id, JudgmentReason $reason): void
    {
        $this->act($id, static fn (Revision $r, PersonReference $actor) => $r->flagJudgmentMatter($actor, $reason));
    }

    /** Validates the current claims against the published atlas at the committed position (before the lock). */
    public function validate(RevisionId $id): ValidationResult
    {
        return $this->act($id, fn (Revision $r): ValidationResult => $r->validateWith($this->validator, new DependencyRecordingReader($this->atlas->current())));
    }

    public function submit(RevisionId $id): void
    {
        $this->act($id, function (Revision $r, PersonReference $actor, StewardRoster $roster): void {
            $this->require($this->governance->maySubmit($actor, $r, $roster, $roster->version())->refusals);
            $r->submit($actor);
        });
    }

    public function approve(RevisionId $id): void
    {
        $this->act($id, function (Revision $r, PersonReference $actor, StewardRoster $roster): void {
            $this->require($this->governance->mayApprove($actor, $r, $roster, $roster->version())->refusals);
            $r->approve(new Approval($r->currentVersion()?->number ?? 0, $roster->version(), $actor));
        });
    }

    public function reprepare(RevisionId $id): void
    {
        $this->act($id, static fn (Revision $r, PersonReference $actor) => $r->reprepare($actor));
    }

    public function withdraw(RevisionId $id): void
    {
        $this->act($id, static fn (Revision $r, PersonReference $actor) => $r->withdraw($actor));
    }

    /**
     * @template T
     * @param \Closure(Revision, PersonReference, StewardRoster): T $act
     * @return T
     */
    private function act(RevisionId $id, \Closure $act): mixed
    {
        $actor = $this->acting->current();
        $stored = $this->revisions->get($id);
        if ($stored->published) {
            throw new RevisionAlreadyPublished($id->value());
        }
        $roster = $this->roster->current();
        $this->requireAppointed($roster, $actor, $id);
        $result = $act($stored->revision, $actor, $roster);
        $this->revisions->save($stored->revision, $stored->rowVersion);

        return $result;
    }

    private function requireAppointed(StewardRoster $roster, PersonReference $actor, RevisionId $id): void
    {
        if (!$roster->mayActForAt($actor, $id, $roster->version())) {
            throw new GeographyActRefused([GovernanceRefusal::NotAppointed]);
        }
    }

    /** @param list<GovernanceRefusal> $refusals */
    private function require(array $refusals): void
    {
        if ($refusals !== []) {
            throw new GeographyActRefused($refusals);
        }
    }
}
