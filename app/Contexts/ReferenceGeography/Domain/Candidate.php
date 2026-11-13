<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Aggregate: a submitted missing or changed place (v3.11 §16; D-31; strategic baseline Q-1 (a)+(c)).
 * A REQUEST, never a fact: it owns its submission record only, never published facts. Its effect on knowledge is only
 * through a verified Revision, governed separately (§15: operational, outside the knowledge stream).
 *
 * Invariants:
 * - submitted → under verification → accepted (names the revision prepared from it) · linked · rejected;
 * - accepted only once verification has started; rejected only with a reason;
 * - linked to a primary candidate that is neither itself nor a link (no duplicate chains);
 * - decided candidates are final; every act records who did it.
 */
final class Candidate
{
    private CandidateState $state = CandidateState::Submitted;
    private ?PersonReference $verificationBy = null;
    private ?PersonReference $decidedBy = null;
    private ?RevisionId $revision = null;
    private ?CandidateId $linkedTo = null;
    private ?string $reason = null;

    /** @param list<string> $evidence references to the submitted evidence (e.g. fingerprints) */
    private function __construct(
        private readonly CandidateId $id,
        private readonly CandidateSubmitter $submitter,
        private readonly string $summary,
        private readonly ?ReferenceUnitId $subject,
        private readonly array $evidence,
        private readonly PersonReference $recordedBy,
    ) {
        if (trim($summary) === '') {
            throw new \InvalidArgumentException('A candidate needs a summary of what is claimed.');
        }
        foreach ($evidence as $ref) {
            if (!is_string($ref) || trim($ref) === '') {
                throw new \InvalidArgumentException('An evidence reference cannot be empty.');
            }
        }
    }

    /** @param list<string> $evidence */
    public static function submit(CandidateId $id, CandidateSubmitter $submitter, string $summary, ?ReferenceUnitId $subject, array $evidence, PersonReference $recordedBy): self
    {
        return new self($id, $submitter, $summary, $subject, array_values($evidence), $recordedBy);
    }

    public function startVerification(PersonReference $by): void
    {
        $this->require(CandidateState::Submitted, 'Verification starts only for a submitted candidate.');
        $this->state = CandidateState::UnderVerification;
        $this->verificationBy = $by;
    }

    public function accept(RevisionId $revision, PersonReference $by): void
    {
        $this->require(CandidateState::UnderVerification, 'A candidate is accepted only after verification has started.');
        $this->decide(CandidateState::Accepted, $by);
        $this->revision = $revision;
    }

    public function linkTo(self $primary, PersonReference $by): void
    {
        $this->requireUndecided();
        if ($primary->id->equals($this->id)) {
            throw new CandidateRefused('A candidate cannot be linked to itself.');
        }
        if ($primary->state === CandidateState::Linked) {
            throw new CandidateRefused('Link to the primary candidate, not to another link.');
        }
        $this->decide(CandidateState::Linked, $by);
        $this->linkedTo = $primary->id;
    }

    public function reject(string $reason, PersonReference $by): void
    {
        $this->requireUndecided();
        if (trim($reason) === '') {
            throw new \InvalidArgumentException('A rejection needs a reason.');
        }
        $this->decide(CandidateState::Rejected, $by);
        $this->reason = $reason;
    }

    public function snapshot(): CandidateSnapshot
    {
        return new CandidateSnapshot($this->id, $this->state, $this->submitter, $this->summary, $this->subject, $this->evidence,
            $this->recordedBy, $this->verificationBy, $this->decidedBy, $this->revision, $this->linkedTo, $this->reason);
    }

    /** The only way back from storage; a state the lifecycle could never produce is refused. */
    public static function fromSnapshot(CandidateSnapshot $s): self
    {
        $refuse = static fn (string $why) => throw new \UnexpectedValueException("Stored candidate {$s->id->value()} is impossible: {$why}.");
        $decided = $s->state->isDecided();
        if ($decided !== ($s->decidedBy !== null)) {
            $refuse('a decision and its decider go together');
        }
        if (($s->state === CandidateState::Accepted) !== ($s->revision !== null)) {
            $refuse('an accepted candidate, and only one, names its revision');
        }
        if (($s->state === CandidateState::Linked) !== ($s->linkedTo !== null)) {
            $refuse('a linked candidate, and only one, names its primary');
        }
        if (($s->state === CandidateState::Rejected) !== ($s->reason !== null)) {
            $refuse('a rejected candidate, and only one, carries a reason');
        }
        if ($s->state === CandidateState::Accepted && $s->verificationBy === null) {
            $refuse('accepted without verification');
        }
        $c = new self($s->id, $s->submitter, $s->summary, $s->subject, $s->evidence, $s->recordedBy);
        $c->state = $s->state;
        $c->verificationBy = $s->verificationBy;
        $c->decidedBy = $s->decidedBy;
        $c->revision = $s->revision;
        $c->linkedTo = $s->linkedTo;
        $c->reason = $s->reason;

        return $c;
    }

    public function id(): CandidateId
    {
        return $this->id;
    }

    public function state(): CandidateState
    {
        return $this->state;
    }

    public function summary(): string
    {
        return $this->summary;
    }

    public function recordedBy(): PersonReference
    {
        return $this->recordedBy;
    }

    public function decidedBy(): ?PersonReference
    {
        return $this->decidedBy;
    }

    public function revision(): ?RevisionId
    {
        return $this->revision;
    }

    public function linkedTo(): ?CandidateId
    {
        return $this->linkedTo;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }

    private function decide(CandidateState $state, PersonReference $by): void
    {
        $this->state = $state;
        $this->decidedBy = $by;
    }

    private function require(CandidateState $state, string $message): void
    {
        if ($this->state !== $state) {
            throw new CandidateRefused($message);
        }
    }

    private function requireUndecided(): void
    {
        if ($this->state->isDecided()) {
            throw new CandidateRefused('A decided candidate is final.');
        }
    }
}
