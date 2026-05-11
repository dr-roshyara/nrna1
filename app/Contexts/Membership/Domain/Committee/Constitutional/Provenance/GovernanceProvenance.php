<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Provenance;

final readonly class GovernanceProvenance
{
    public function __construct(
        public string $producedByKernel,
        public string $doctrineVersion,
        public string $replayEngineVersion,
        public ?string $migratedFromVersion,
        public ?string $certificationId,
        public \DateTimeImmutable $provenanceCreatedAt,
    ) {}

    public function wasMigrated(): bool
    {
        return $this->migratedFromVersion !== null;
    }

    public function isCertified(): bool
    {
        return $this->certificationId !== null;
    }
}
