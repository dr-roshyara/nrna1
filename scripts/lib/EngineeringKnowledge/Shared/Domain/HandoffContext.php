<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Domain;

use InvalidArgumentException;

/**
 * Provenance of one handoff-assurance run (Phase-1 D-5).
 *
 * Carries the EXISTING provenance mechanisms only — the artifact being assessed,
 * checker identity + version, the source commit the checker ran at (git HEAD, or
 * UNKNOWN), the generated-at timestamp, and the command line. It is CODE
 * provenance, never AI-process attestation: nothing here asserts who produced
 * the artifact (EKS-07 stays unsolved; self-declared process identity is not
 * independently attested authorship).
 */
final readonly class HandoffContext
{
    private function __construct(
        private string $target,
        private string $checkerName,
        private string $checkerVersion,
        private string $sourceCommit,
        private string $generatedAt,
        private string $commandLine,
    ) {
    }

    public static function of(
        string $target,
        string $checkerName,
        string $checkerVersion,
        string $sourceCommit,
        string $generatedAt,
        string $commandLine,
    ): self {
        foreach ([
            'target' => $target,
            'checker name' => $checkerName,
            'checker version' => $checkerVersion,
            'source commit' => $sourceCommit,
            'generated-at timestamp' => $generatedAt,
            'command line' => $commandLine,
        ] as $label => $value) {
            if (trim($value) === '') {
                throw new InvalidArgumentException("Handoff context {$label} must not be blank.");
            }
        }

        return new self($target, $checkerName, $checkerVersion, $sourceCommit, $generatedAt, $commandLine);
    }

    /** The artifact this report assesses (the author's document path). */
    public function target(): string
    {
        return $this->target;
    }

    public function checkerName(): string
    {
        return $this->checkerName;
    }

    public function checkerVersion(): string
    {
        return $this->checkerVersion;
    }

    /** The git commit the checker ran at — code provenance, not authorship. */
    public function sourceCommit(): string
    {
        return $this->sourceCommit;
    }

    /** ISO-8601 timestamp of the run. */
    public function generatedAt(): string
    {
        return $this->generatedAt;
    }

    /** The exact command line, as recorded by the adapter. */
    public function commandLine(): string
    {
        return $this->commandLine;
    }
}
