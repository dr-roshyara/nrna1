<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Aggregate: an organisation's challenge to a PUBLISHED fact, with counter-evidence (strategic baseline S1.1; D-84;
 * v3.11 §16). It owns the objection record, never the target claim: the claim is unchanged until an outcome is recorded,
 * and every outcome that changes knowledge acts through a new, governed Revision.
 *
 * Invariants:
 * - submitted citing at least one instrument → assessed (authenticity, mandate, applicability) →
 *   corrected or disputed (all three findings positive; each proposes a revision) · rejected (at least one negative
 *   finding: "not authentic, no covering mandate, or not applicable", with a reason);
 * - decided objections are final; reconsideration is a NEW objection with new evidence (enforced by the application);
 * - D-84a: the steward who recorded it on the organisation's behalf may neither assess nor decide it.
 */
final class Objection
{
    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    private ObjectionState $state = ObjectionState::Submitted;
    private ?ObjectionAssessment $assessment = null;
    private ?PersonReference $assessedBy = null;
    private ?PersonReference $decidedBy = null;
    private ?RevisionId $revision = null;
    private ?string $reason = null;

    /** @param list<string> $evidence the instruments cited (references, e.g. fingerprints) */
    private function __construct(
        private readonly ObjectionId $id,
        private readonly string $organisationId,
        private readonly ClaimId $target,
        private readonly string $statement,
        private readonly array $evidence,
        private readonly PersonReference $recordedBy,
    ) {
        if (preg_match(self::UUID, $organisationId) !== 1) {
            throw new \InvalidArgumentException('An objection is brought by an organisation, named by its id (a lower-case UUID).');
        }
        if (trim($statement) === '') {
            throw new \InvalidArgumentException('An objection states what it challenges.');
        }
        if ($evidence === []) {
            throw new \InvalidArgumentException('An objection cites at least one instrument.');
        }
        foreach ($evidence as $ref) {
            if (!is_string($ref) || trim($ref) === '') {
                throw new \InvalidArgumentException('An evidence reference cannot be empty.');
            }
        }
    }

    /** @param list<string> $evidence */
    public static function submitOnBehalf(ObjectionId $id, string $organisationId, ClaimId $target, string $statement, array $evidence, PersonReference $recordedBy): self
    {
        return new self($id, $organisationId, $target, $statement, array_values($evidence), $recordedBy);
    }

    public function assess(ObjectionAssessment $assessment, PersonReference $by): void
    {
        $this->requireNotRecorder($by);
        if ($this->state !== ObjectionState::Submitted) {
            throw new ObjectionRefused('Only a submitted objection is assessed.');
        }
        $this->state = ObjectionState::Assessed;
        $this->assessment = $assessment;
        $this->assessedBy = $by;
    }

    public function correct(RevisionId $proposedRevision, PersonReference $by): void
    {
        $this->requirePositiveAssessment($by);
        $this->decide(ObjectionState::Corrected, $by);
        $this->revision = $proposedRevision;
    }

    public function dispute(RevisionId $proposedRevision, PersonReference $by): void
    {
        $this->requirePositiveAssessment($by);
        $this->decide(ObjectionState::Disputed, $by);
        $this->revision = $proposedRevision;
    }

    public function reject(string $reason, PersonReference $by): void
    {
        $this->requireAssessed($by);
        if ($this->assessment?->allPositive() === true) {
            throw new ObjectionRefused('An authentic, mandated and applicable objection is not rejected: it is a correction or a dispute.');
        }
        if (trim($reason) === '') {
            throw new \InvalidArgumentException('A rejection records its reason.');
        }
        $this->decide(ObjectionState::Rejected, $by);
        $this->reason = $reason;
    }

    public function snapshot(): ObjectionSnapshot
    {
        return new ObjectionSnapshot($this->id, $this->state, $this->organisationId, $this->target, $this->statement, $this->evidence,
            $this->recordedBy, $this->assessment, $this->assessedBy, $this->decidedBy, $this->revision, $this->reason);
    }

    /** The only way back from storage; a state the lifecycle could never produce is refused. */
    public static function fromSnapshot(ObjectionSnapshot $s): self
    {
        $refuse = static fn (string $why) => throw new \UnexpectedValueException("Stored objection {$s->id->value()} is impossible: {$why}.");
        if (($s->state !== ObjectionState::Submitted) !== ($s->assessment !== null && $s->assessedBy !== null)) {
            $refuse('an assessment, and its assessor, exist exactly after submission');
        }
        if ($s->state->isDecided() !== ($s->decidedBy !== null)) {
            $refuse('a decision and its decider go together');
        }
        if (in_array($s->state, [ObjectionState::Corrected, ObjectionState::Disputed], true) !== ($s->revision !== null)) {
            $refuse('a correction or a dispute, and only they, propose a revision');
        }
        if (($s->state === ObjectionState::Rejected) !== ($s->reason !== null)) {
            $refuse('a rejection, and only it, carries a reason');
        }
        if ($s->state->isDecided() && $s->assessment !== null && ($s->state === ObjectionState::Rejected) === $s->assessment->allPositive()) {
            $refuse('the outcome contradicts the assessment (a rejection needs a negative finding; a correction or dispute none)');
        }
        $o = new self($s->id, $s->organisationId, $s->target, $s->statement, $s->evidence, $s->recordedBy);
        $o->state = $s->state;
        $o->assessment = $s->assessment;
        $o->assessedBy = $s->assessedBy;
        $o->decidedBy = $s->decidedBy;
        $o->revision = $s->revision;
        $o->reason = $s->reason;

        return $o;
    }

    public function id(): ObjectionId
    {
        return $this->id;
    }

    public function state(): ObjectionState
    {
        return $this->state;
    }

    public function target(): ClaimId
    {
        return $this->target;
    }

    /** @return list<string> */
    public function evidence(): array
    {
        return $this->evidence;
    }

    public function revision(): ?RevisionId
    {
        return $this->revision;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }

    private function requireAssessed(PersonReference $by): void
    {
        $this->requireNotRecorder($by);
        if ($this->state->isDecided()) {
            throw new ObjectionRefused('A decided objection is final; reconsideration is a new objection with new evidence.');
        }
        if ($this->state !== ObjectionState::Assessed) {
            throw new ObjectionRefused('An outcome is recorded only after assessment.');
        }
    }

    private function requirePositiveAssessment(PersonReference $by): void
    {
        $this->requireAssessed($by);
        if ($this->assessment?->allPositive() !== true) {
            throw new ObjectionRefused('A correction or a dispute needs the objection authentic, mandated and applicable.');
        }
    }

    private function requireNotRecorder(PersonReference $by): void
    {
        if ($by->equals($this->recordedBy)) {
            throw new ObjectionRefused('D-84a: the steward who recorded the objection on the organisation\'s behalf may not assess or decide it.');
        }
    }

    private function decide(ObjectionState $state, PersonReference $by): void
    {
        $this->state = $state;
        $this->decidedBy = $by;
    }
}
