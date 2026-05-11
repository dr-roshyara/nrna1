<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Authority;

final readonly class ResolvedAuthority
{
    /**
     * @param string[] $delegatedJurisdictionIds
     * @param string[] $exceptionZoneIds
     */
    public function __construct(
        public ?string $directJurisdictionId,
        public array $delegatedJurisdictionIds,
        public array $exceptionZoneIds,
        public int $authorityScore,
    ) {}

    public static function none(): self
    {
        return new self(
            directJurisdictionId: null,
            delegatedJurisdictionIds: [],
            exceptionZoneIds: [],
            authorityScore: 0,
        );
    }

    public function canOperateIn(string $jurisdictionId): bool
    {
        if ($this->directJurisdictionId === $jurisdictionId) {
            return true;
        }
        if (in_array($jurisdictionId, $this->delegatedJurisdictionIds, true)) {
            return true;
        }
        if (in_array($jurisdictionId, $this->exceptionZoneIds, true)) {
            return true;
        }
        return false;
    }

    public function isNone(): bool
    {
        return $this->directJurisdictionId === null && empty($this->delegatedJurisdictionIds);
    }
}
