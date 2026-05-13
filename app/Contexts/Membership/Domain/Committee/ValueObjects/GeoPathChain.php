<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use DomainException;

/**
 * GeoPathChain — Semantic path chain for geographic hierarchy comparison.
 *
 * Derived from GeoSemanticProjection. Used by CommitteeEligibilityPolicy
 * for deterministic eligibility determination.
 *
 * ZERO dependencies. Pure value object.
 */
final readonly class GeoPathChain
{
    /** @var int[] Ordered ancestor IDs including the leaf */
    public array $segments;

    /**
     * @param int $geoUnitId The leaf (deepest) geo unit ID
     * @param string $path Canonical materialized path (e.g., "/1/23/456")
     * @param array $segments Parsed ancestor IDs (root-first, including leaf)
     */
    public function __construct(
        public int $geoUnitId,
        public string $path,
        array $segments,
    ) {
        if ($geoUnitId < 1) {
            throw new DomainException('geoUnitId must be a positive integer');
        }

        $this->segments = $segments;
    }

    /**
     * Check if this chain contains the given geo unit ID.
     * Used by eligibility policy for hierarchy membership checks.
     */
    public function contains(int $geoUnitId): bool
    {
        return in_array($geoUnitId, $this->segments, true);
    }

    /**
     * Check if this chain starts with the given path prefix.
     * Higher-level check: "is this geo unit within the area defined by $prefix?"
     */
    public function startsWith(string $pathPrefix): bool
    {
        if ($pathPrefix === '' || $this->path === '') {
            return false;
        }
        return str_starts_with($this->path, $pathPrefix);
    }
}
