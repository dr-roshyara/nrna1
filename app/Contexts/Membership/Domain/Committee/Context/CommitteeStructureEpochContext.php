<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Context;

final readonly class CommitteeStructureEpochContext
{
    public function __construct(
        public int $version,
        public string $status,
        public int $depth,
        public string $levelCode,
        public ?string $parentStructureId = null,
        public bool $isLatestActive = false,
    ) {}

    public function isActive(): bool
    {
        return strtolower($this->status) === 'active';
    }

    public function canEvolve(): bool
    {
        return $this->isActive();
    }

    /** @deprecated Phase B temporary shim. Phase A3 will replace with actual structure query. */
    public static function fromOrganisationStatus(string $governanceStatus): self
    {
        $isActive = strtolower($governanceStatus) === 'active';
        return new self(
            version: 1,
            status: $isActive ? 'active' : 'deprecated',
            depth: 0,
            levelCode: 'temporary',
            parentStructureId: null,
            isLatestActive: $isActive
        );
    }
}
