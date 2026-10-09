<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Why a fact was recorded: a decision, a rule, or an attestation (RA-7).
 *
 * Slice 0 validates structure only. Whether a declaration or authority evidence
 * MUST be present is BD-CONSENT / LQ-1 / DS-4 (Slice 2). Resolution never reads it.
 */
final readonly class Provenance
{
    private function __construct(
        private ProvenanceKind $kind,
        private string $actorOrSource,
        private ?AuthorityEvidence $authorityEvidence,
        private ?string $declarationRef,
        private ?string $ruleRef,
        private ?string $ruleVersion,
        private ?string $importer,
        private ?string $note,
    ) {
        foreach ([$actorOrSource, $declarationRef, $ruleRef, $ruleVersion, $importer] as $value) {
            if ($value !== null && trim($value) === '') {
                throw new \InvalidArgumentException('Provenance fields cannot be empty.');
            }
        }
    }

    public static function decision(string $actor, ?AuthorityEvidence $authorityEvidence = null, ?string $declarationRef = null): self
    {
        return new self(ProvenanceKind::Decision, $actor, $authorityEvidence, $declarationRef, null, null, null, null);
    }

    public static function rule(string $ruleRef, string $ruleVersion): self
    {
        return new self(ProvenanceKind::Rule, $ruleRef, null, null, $ruleRef, $ruleVersion, null, null);
    }

    public static function attested(string $source, string $importer, ?string $note = null): self
    {
        return new self(ProvenanceKind::Attested, $source, null, null, null, null, $importer, $note);
    }

    public function kind(): ProvenanceKind
    {
        return $this->kind;
    }

    /** The deciding actor (decision), the rule (rule) or the source (attested). */
    public function actorOrSource(): string
    {
        return $this->actorOrSource;
    }

    public function authorityEvidence(): ?AuthorityEvidence
    {
        return $this->authorityEvidence;
    }

    public function declarationRef(): ?string
    {
        return $this->declarationRef;
    }

    public function ruleRef(): ?string
    {
        return $this->ruleRef;
    }

    public function ruleVersion(): ?string
    {
        return $this->ruleVersion;
    }

    public function importer(): ?string
    {
        return $this->importer;
    }

    public function note(): ?string
    {
        return $this->note;
    }
}
