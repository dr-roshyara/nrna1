<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use InvalidArgumentException;

/**
 * @deprecated Use JurisdictionInstance instead.
 *
 * This is the legacy jurisdiction VO with semantic scope names.
 * It exists only for migration from the old hardcoded enum model.
 * New code should use JurisdictionInstance (matrix-based, configuration-driven).
 *
 * Bridge method: toMatrixJurisdiction()
 */
final readonly class LegacyJurisdiction
{
    private const VALID_SCOPES = ['ward', 'district', 'province', 'national'];

    public string $scope;
    public ?string $reference;

    private function __construct(string $scope, ?string $reference = null)
    {
        if (!in_array($scope, self::VALID_SCOPES, true)) {
            throw new InvalidArgumentException("Invalid jurisdiction scope: {$scope}");
        }
        $this->scope = $scope;
        $this->reference = $reference;
    }

    public static function ward(string $reference): self
    {
        return new self('ward', $reference);
    }

    public static function district(string $reference): self
    {
        return new self('district', $reference);
    }

    public static function province(string $reference): self
    {
        return new self('province', $reference);
    }

    public static function national(): self
    {
        return new self('national');
    }

    /**
     * Reconstitute from persisted string (scope:reference or just scope).
     */
    public static function fromString(string $string): self
    {
        if (!str_contains($string, ':')) {
            if ($string === 'national') {
                return self::national();
            }
            throw new InvalidArgumentException("Invalid jurisdiction string: {$string}");
        }

        [$scope, $reference] = explode(':', $string, 2);

        if (!in_array($scope, self::VALID_SCOPES, true)) {
            throw new InvalidArgumentException("Invalid jurisdiction scope: {$scope}");
        }

        return new self($scope, $reference);
    }

    public function equals(LegacyJurisdiction $other): bool
    {
        return $this->scope === $other->scope
            && $this->reference === $other->reference;
    }

    public function toString(): string
    {
        return $this->reference !== null
            ? "{$this->scope}:{$this->reference}"
            : $this->scope;
    }

    /**
     * Bridge to the new matrix-based model.
     *
     * The caller must supply the governance level and geo level from the
     * governance_level_definitions table — this VO cannot query the database itself.
     * This design removes the diagonal assumption: the caller is responsible for
     * providing the correct levels based on configuration.
     *
     * @param int $governanceLevel The governance level from governance_level_definitions
     * @param int $geoLevel The geographic level (may differ from governance level)
     */
    public function toMatrixJurisdiction(int $governanceLevel, int $geoLevel): JurisdictionInstance
    {
        return new JurisdictionInstance(
            governanceLevel: $governanceLevel,
            geoLevel: $geoLevel,
            geoCode: $this->reference ?? $this->scope,
        );
    }
}
