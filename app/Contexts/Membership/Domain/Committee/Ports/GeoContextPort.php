<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Ports;

use App\Contexts\Membership\Domain\ValueObjects\GeoReference;

interface GeoContextPort
{
    /**
     * Validate that a geo reference is valid for the given scope.
     *
     * This port abstracts the Geography context to prevent direct dependencies
     * from Membership → Geography. The adapter implementation will handle
     * cross-context communication and validation.
     *
     * @param GeoScope $scope The geographic scope (strongly typed VO)
     * @param GeoReference $reference The geographic reference to validate
     * @throws \DomainException if reference is invalid for the scope
     */
    public function validateGeoReference(GeoScope $scope, GeoReference $reference): void;

    /**
     * Check if a geographic scope is valid and exists.
     *
     * @param GeoScope $scope The scope to validate
     * @return bool True if scope is valid, false otherwise
     */
    public function isScopeValid(GeoScope $scope): bool;

    /**
     * Get all valid geographic scopes available in the system.
     *
     * @return array<GeoScope> List of valid scopes
     */
    public function getValidScopes(): array;
}
