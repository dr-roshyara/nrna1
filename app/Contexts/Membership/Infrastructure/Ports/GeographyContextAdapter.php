<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Ports;

use App\Contexts\Membership\Domain\Committee\Ports\GeoContextPort;
use App\Contexts\Membership\Domain\Committee\Ports\GeoScope;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;

/**
 * Geography Context Adapter
 *
 * Stub implementation of GeoContextPort for Phase C.
 * Will be fully implemented when Geography context integration is complete.
 *
 * For now, this permissive implementation allows all geographic references
 * and scopes. Phase B will add actual validation.
 */
final class GeographyContextAdapter implements GeoContextPort
{
    public function validateGeoReference(GeoScope $scope, GeoReference $reference): void
    {
        // Phase C: Permissive - allow all references
        // Phase B: Will validate against Geography context
    }

    public function isScopeValid(GeoScope $scope): bool
    {
        // Phase C: Permissive - all scopes valid
        return true;
    }

    /**
     * @return array<GeoScope>
     */
    public function getValidScopes(): array
    {
        // Phase C: Return empty array - no validation needed yet
        return [];
    }
}
