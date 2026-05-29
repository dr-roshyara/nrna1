<?php

namespace App\Domain\Election\Replay;

/**
 * ReplayEvidenceEnvelope
 *
 * Sealed container for constitutional evidence at replay time.
 * The envelope captures ALL evidence that participated in an evaluation
 * so that replay can reconstruct exactly the same input set.
 *
 * INVARIANTS:
 * 1. Evidence is frozen at envelope creation — no mutation allowed
 * 2. Hash integrity is always verifiable
 * 3. Same evidence always produces same envelope hash
 * 4. Envelope carries its schema version for replay compatibility checks
 */
readonly class ReplayEvidenceEnvelope
{
    public string $envelopeHash;

    public function __construct(
        public array                    $evidence,        // Constitutional evidence array
        public ReplayCompatibilityVersion $compatibility, // Schema version at freezing time
        public \DateTimeImmutable       $frozenAt,
        public string                   $electionIdentifier, // election-scoped identifier
        public string                   $voterIdentifier,    // hashed voter identifier
    ) {
        $this->envelopeHash = $this->computeHash();
    }

    /**
     * Deterministic hash of all evidence in the envelope.
     * Same evidence → same hash, always, regardless of host, timezone, or process.
     */
    private function computeHash(): string
    {
        $parts = [
            $this->compatibility->toString(),
            $this->electionIdentifier,
            $this->voterIdentifier,
            $this->frozenAt->getTimestamp(),
        ];

        foreach ($this->evidence as $key => $value) {
            $serialized = is_object($value)
                ? spl_object_id($value) . ':' . get_class($value)
                : (string)$value;
            $parts[] = "{$key}:{$serialized}";
        }

        return hash('sha256', implode('|', $parts));
    }

    /**
     * Verify that the envelope hash matches a known hash.
     * Used by replay certification to confirm envelope integrity.
     */
    public function verifyIntegrity(string $knownHash): bool
    {
        return hash_equals($knownHash, $this->envelopeHash);
    }

    /**
     * Count of evidence items in the envelope.
     */
    public function evidenceCount(): int
    {
        return count($this->evidence);
    }
}
