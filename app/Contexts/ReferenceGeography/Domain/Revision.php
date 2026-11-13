<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Aggregate: a proposed change to the atlas (v3.11 §5, §5.3, §8, §16).
 *
 * Protects:
 * - R-1: no two claims for one slot with overlapping intervals;
 * - internal completeness at submission: at least one claim, every claim's evidence attached, and the required
 *   assessments (authenticity, competence, applicability) recorded FOR THIS VERSION;
 * - frozen content: submission freezes a content version whose digest covers everything judged; nothing changes
 *   until re-preparation, which opens the next version;
 * - approvals name a content version; only those naming the CURRENT version count;
 * - `excluded(r)`: everyone who prepared, assessed or submitted any version, accumulated.
 *
 * Not here: dependency predicates, p₀ and before-images (G-2b/G-2c, added to the digest there); the derived
 * structural classification (G-2c); approver eligibility (G-3); publication (G-4).
 */
final class Revision
{
    private RevisionState $state = RevisionState::Draft;

    /** @var array<string, Claim> by claim id */
    private array $claims = [];

    /** @var array<string, EvidenceVersion> by evidence id */
    private array $evidence = [];

    /** @var list<Assessment> assessments of the version being prepared */
    private array $assessments = [];

    /** @var list<array{int, Approval}> every approval ever given: [version it named, approval] */
    private array $approvals = [];

    /** @var array<string, PersonReference> preparers, accumulated over versions */
    private array $preparers = [];

    /** @var array<string, PersonReference> everyone who prepared, assessed or submitted any version */
    private array $excluded = [];

    private ?ContentVersion $current = null;

    /** The validation of exactly the current claims (G-2c); voided by any claim change and by re-preparation. */
    private ?ValidationResult $validation = null;

    private function __construct(private readonly RevisionId $id)
    {
    }

    public static function prepare(RevisionId $id, PersonReference $preparer): self
    {
        $r = new self($id);
        $r->notePreparer($preparer);

        return $r;
    }

    public function addClaim(Claim $claim, PersonReference $by): void
    {
        $this->requireDraft();
        foreach ($this->claims as $existing) {
            if ($existing->slot->equals($claim->slot) && $existing->interval->overlaps($claim->interval)) {
                throw new RevisionRefused(RevisionRefusal::SlotOverlapInRevision);
            }
        }
        $this->claims[$claim->id->value()] = $claim;
        $this->validation = null;
        $this->notePreparer($by);
    }

    public function removeClaim(ClaimId $id, PersonReference $by): void
    {
        $this->requireDraft();
        if (!isset($this->claims[$id->value()])) {
            throw new RevisionRefused(RevisionRefusal::UnknownClaim);
        }
        unset($this->claims[$id->value()]);
        $this->validation = null;
        $this->notePreparer($by);
    }

    /** Validates exactly the current claims at the reader's p₀ (v3.11 §5) and keeps the result for submission. */
    public function validateWith(RevisionValidator $validator, DependencyRecordingReader $reader): ValidationResult
    {
        $this->requireDraft();
        $this->validation = $validator->validate(array_values($this->claims), $reader);

        return $this->validation;
    }

    public function validation(): ?ValidationResult
    {
        return $this->validation;
    }

    public function attachEvidence(EvidenceVersion $evidence, PersonReference $by): void
    {
        $this->requireDraft();
        $known = $this->evidence[$evidence->id] ?? null;
        if ($known !== null && $known->fingerprint !== $evidence->fingerprint) {
            throw new RevisionRefused(RevisionRefusal::EvidenceChangedUnderSameIdentifier);
        }
        $this->evidence[$evidence->id] = $evidence;
        $this->notePreparer($by);
    }

    public function recordAssessment(Assessment $assessment): void
    {
        $this->requireDraft();
        $this->assessments[] = $assessment;
        $this->excluded[$assessment->by->value()] = $assessment->by;
    }

    /** By the assessing steward: freezes the next content version. */
    public function submit(PersonReference $assessor): ContentVersion
    {
        $this->requireDraft();
        $this->requireComplete();
        $this->excluded[$assessor->value()] = $assessor;
        $number = ($this->current?->number ?? 0) + 1;
        $this->current = new ContentVersion($number, $this->digest($number, $assessor));
        $this->state = RevisionState::Submitted;

        return $this->current;
    }

    public function approve(Approval $approval): void
    {
        if ($this->state !== RevisionState::Submitted) {
            throw new RevisionRefused(RevisionRefusal::NotSubmitted);
        }
        if ($approval->contentVersion !== $this->current?->number) {
            throw new RevisionRefused(RevisionRefusal::ApprovalNamesAnotherVersion);
        }
        $this->approvals[] = [$approval->contentVersion, $approval];
    }

    /** Set by the publication step when an intervening claim matches a dependency (G-4). */
    public function markStale(): void
    {
        if ($this->state !== RevisionState::Submitted) {
            throw new RevisionRefused(RevisionRefusal::NotSubmitted);
        }
        $this->state = RevisionState::Stale;
    }

    /** A human reconsiders a stale or submitted revision (v3.11 §5.3): back to draft; the next submit is a new version. */
    public function reprepare(PersonReference $by): void
    {
        if ($this->state === RevisionState::Withdrawn) {
            throw new RevisionRefused(RevisionRefusal::Withdrawn);
        }
        if ($this->state === RevisionState::Draft) {
            throw new RevisionRefused(RevisionRefusal::NothingToReprepare);
        }
        $this->state = RevisionState::Draft;
        $this->assessments = [];   // assessments name the version they judged; the next version is assessed afresh
        $this->validation = null;  // the next version is validated at its own p₀ (§5.1: before-images recomputed)
        $this->notePreparer($by);
    }

    public function withdraw(PersonReference $by): void
    {
        if ($this->state === RevisionState::Withdrawn) {
            throw new RevisionRefused(RevisionRefusal::Withdrawn);
        }
        $this->state = RevisionState::Withdrawn;
    }

    public function id(): RevisionId
    {
        return $this->id;
    }

    public function state(): RevisionState
    {
        return $this->state;
    }

    public function currentVersion(): ?ContentVersion
    {
        return $this->current;
    }

    /** @return list<Claim> */
    public function claims(): array
    {
        return array_values($this->claims);
    }

    /** @return list<Approval> every approval ever recorded (append-only) */
    public function approvals(): array
    {
        return array_map(static fn (array $a): Approval => $a[1], $this->approvals);
    }

    /** @return list<Approval> only approvals naming the current content version (§8) */
    public function countingApprovals(): array
    {
        $current = $this->current?->number;

        return array_values(array_map(
            static fn (array $a): Approval => $a[1],
            array_filter($this->approvals, static fn (array $a): bool => $a[0] === $current),
        ));
    }

    /** @return list<PersonReference> everyone who prepared, assessed or submitted any version (`excluded(r)`, §8) */
    public function excludedPersons(): array
    {
        return array_values($this->excluded);
    }

    private function notePreparer(PersonReference $by): void
    {
        $this->preparers[$by->value()] = $by;
        $this->excluded[$by->value()] = $by;
    }

    private function requireDraft(): void
    {
        if ($this->state !== RevisionState::Draft) {
            throw new RevisionRefused(RevisionRefusal::NotDraft);
        }
    }

    private function requireComplete(): void
    {
        if ($this->claims === []) {
            throw new RevisionRefused(RevisionRefusal::Incomplete);
        }
        foreach ($this->claims as $claim) {
            if (!isset($this->evidence[$claim->basis->reference])) {
                throw new RevisionRefused(RevisionRefusal::Incomplete);
            }
        }
        $assessed = array_map(static fn (Assessment $a): AssessmentKind => $a->kind, $this->assessments);
        foreach (AssessmentKind::required() as $kind) {
            if (!in_array($kind, $assessed, true)) {
                throw new RevisionRefused(RevisionRefusal::Incomplete);
            }
        }
        if ($this->validation === null) {
            throw new RevisionRefused(RevisionRefusal::NotValidated);
        }
        if (!$this->validation->isValid()) {
            throw new RevisionRefused(RevisionRefusal::Invalid);
        }
    }

    /** SHA-256 over a canonical form of everything judged (§5.3), deterministic for equal content. */
    private function digest(int $number, PersonReference $assessor): string
    {
        $claims = array_map(static fn (Claim $c): array => [
            $c->id->value(), $c->slot->encoded(), $c->value->isNone() ? null : $c->value->value(),
            $c->interval->from->value(), $c->interval->to?->value(), $c->basis->kind->value, $c->basis->reference,
        ], $this->claims);
        ksort($claims);
        $evidence = array_map(static fn (EvidenceVersion $e): array => [$e->id, $e->fingerprint], $this->evidence);
        ksort($evidence);
        $assessments = array_map(static fn (Assessment $a): array => [$a->kind->value, $a->outcome, $a->by->value()], $this->assessments);
        sort($assessments);
        $preparers = array_keys($this->preparers);
        sort($preparers);

        return hash('sha256', json_encode([
            'revision' => $this->id->value(),
            'version' => $number,
            'claims' => array_values($claims),
            'evidence' => array_values($evidence),
            'assessments' => $assessments,
            'preparers' => $preparers,
            'assessor' => $assessor->value(),
            'validation' => $this->validation?->describe(),   // p₀, dependency predicates, before-images, structural
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
