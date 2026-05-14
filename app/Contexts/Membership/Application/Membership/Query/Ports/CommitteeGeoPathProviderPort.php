<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Query\Ports;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;

/**
 * Committee Geo Path Provider Port
 *
 * Infrastructure port that resolves a geo unit ID to a materialized GeoPathChain.
 *
 * Used by EligibleCommitteeQueryService to evaluate geographic eligibility.
 * Called ONLY for non-central committees (geoUnitId != null).
 * Central committees bypass this entirely.
 *
 * Implementations:
 * - GeoSemanticProjectionBuilder + GeoPathChainFactory (production)
 * - Stubbed mock in unit tests (returns known paths)
 *
 * Rationale:
 * - Committee aggregates carry geoUnitId: ?int, NOT resolved GeoPathChain
 * - Resolution requires infrastructure (semantic projections, factory)
 * - Tests are purer without infrastructure coupling
 * - Service stays testable with dependency injection
 */
interface CommitteeGeoPathProviderPort
{
    /**
     * Resolve a geo unit ID to its materialized path.
     *
     * @param int $geoUnitId Geo unit ID from committee.geoUnitId
     * @return GeoPathChain Materialized path (e.g., /1/23/456)
     * @throws \RuntimeException If geo unit not found or resolution fails
     */
    public function resolveForCommittee(int $geoUnitId): GeoPathChain;
}
