<?php

namespace App\Domain\Election\Security;

/**
 * Immutable governance record of constitutional divergences.
 *
 * INVARIANT: This ledger documents EVERY legacy vs resolver difference.
 * Each entry is typed, provenance-aware, and signed by governance authority.
 *
 * NOT a bug report. These are EXPECTED architectural changes with explicit
 * constitutional authority approval.
 */
readonly class ConstitutionalDivergenceLedger
{
    /**
     * @param ConstitutionalDivergenceEntry[] $entries
     */
    public function __construct(
        private array $entries,
    ) {}

    /**
     * Record a detected divergence with full provenance.
     */
    public static function withEntry(
        int $electionId,
        ConstitutionalDivergenceType $type,
        string $legacyBehavior,
        string $resolverBehavior,
        string $approvedBy,
        ?string $rationale = null,
    ): self {
        return new self([
            new ConstitutionalDivergenceEntry(
                electionId: $electionId,
                divergenceType: $type,
                severity: $type->severity(),
                constitutionalArticle: $type->article(),
                legacyBehavior: $legacyBehavior,
                resolverBehavior: $resolverBehavior,
                resolverDecision: $resolverBehavior,  // what resolver chose to do
                approvedBy: $approvedBy,               // governance authority
                rationale: $rationale,
                recordedAt: new \DateTimeImmutable(),
            )
        ]);
    }

    public function addEntry(
        int $electionId,
        ConstitutionalDivergenceType $type,
        string $legacyBehavior,
        string $resolverBehavior,
        string $approvedBy,
        ?string $rationale = null,
    ): self {
        return new self([
            ...$this->entries,
            new ConstitutionalDivergenceEntry(
                electionId: $electionId,
                divergenceType: $type,
                severity: $type->severity(),
                constitutionalArticle: $type->article(),
                legacyBehavior: $legacyBehavior,
                resolverBehavior: $resolverBehavior,
                resolverDecision: $resolverBehavior,
                approvedBy: $approvedBy,
                rationale: $rationale,
                recordedAt: new \DateTimeImmutable(),
            )
        ]);
    }

    /**
     * All recorded divergences.
     *
     * @return ConstitutionalDivergenceEntry[]
     */
    public function entries(): array
    {
        return $this->entries;
    }

    /**
     * Critical severity divergences only (require board-level approval).
     *
     * @return ConstitutionalDivergenceEntry[]
     */
    public function criticalDivergences(): array
    {
        return array_filter(
            $this->entries,
            fn ($e) => $e->severity() === Severity::Critical,
        );
    }

    /**
     * Divergences by constitutional article (grouped governance view).
     *
     * @return array<string, ConstitutionalDivergenceEntry[]>
     */
    public function divergencesByArticle(): array
    {
        $grouped = [];
        foreach ($this->entries as $entry) {
            $article = $entry->constitutionalArticle();
            $grouped[$article][] = $entry;
        }
        return $grouped;
    }

    /**
     * Divergences by election (audit trail per election).
     *
     * @return array<int, ConstitutionalDivergenceEntry[]>
     */
    public function divergencesByElection(): array
    {
        $grouped = [];
        foreach ($this->entries as $entry) {
            $id = $entry->electionId();
            $grouped[$id][] = $entry;
        }
        return $grouped;
    }

    /**
     * Is parity verified? (no divergences recorded)
     */
    public function isPerfectParity(): bool
    {
        return empty($this->entries);
    }

    /**
     * Governance approval status.
     */
    public function allApprovedBy(): array
    {
        return array_unique(
            array_map(fn ($e) => $e->approvedBy(), $this->entries)
        );
    }
}

readonly class ConstitutionalDivergenceEntry
{
    public function __construct(
        private int $electionId,
        private ConstitutionalDivergenceType $divergenceType,
        private Severity $severity,
        private string $constitutionalArticle,
        private string $legacyBehavior,
        private string $resolverBehavior,
        private string $resolverDecision,
        private string $approvedBy,  // governance authority (e.g., 'system_architect', 'board_chairman')
        private ?string $rationale,
        private \DateTimeImmutable $recordedAt,
    ) {}

    public function electionId(): int { return $this->electionId; }
    public function divergenceType(): ConstitutionalDivergenceType { return $this->divergenceType; }
    public function severity(): Severity { return $this->severity; }
    public function constitutionalArticle(): string { return $this->constitutionalArticle; }
    public function legacyBehavior(): string { return $this->legacyBehavior; }
    public function resolverBehavior(): string { return $this->resolverBehavior; }
    public function resolverDecision(): string { return $this->resolverDecision; }
    public function approvedBy(): string { return $this->approvedBy; }
    public function rationale(): ?string { return $this->rationale; }
    public function recordedAt(): \DateTimeImmutable { return $this->recordedAt; }

    /**
     * Governance summary for documentation.
     */
    public function summary(): string
    {
        return sprintf(
            "%s [%s] (%s) — Legacy: %s → Resolver: %s. Approved by: %s. %s",
            $this->divergenceType->name,
            $this->severity->value,
            $this->constitutionalArticle,
            $this->legacyBehavior,
            $this->resolverBehavior,
            $this->approvedBy,
            $this->rationale ?? '(no rationale)',
        );
    }
}
