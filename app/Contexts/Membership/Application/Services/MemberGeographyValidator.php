<?php

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\Geography\Application\Services\GeographyAntiCorruptionLayer;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use App\Contexts\Geography\Domain\Exceptions\InvalidHierarchyException;
use App\Contexts\Geography\Domain\Exceptions\CountryNotSupportedException;
use App\Contexts\Geography\Domain\Exceptions\MissingRequiredLevelException;
use App\Contexts\Membership\Domain\Exceptions\InvalidMemberGeographyException;
use Illuminate\Support\Facades\Log;

/**
 * Member Geography Validator
 *
 * Validates geography for member registration using DDD patterns.
 * Bridges Membership context with Geography context through anti-corruption layer.
 *
 * Responsibilities:
 * 1. Convert primitive inputs to Geography context Value Objects
 * 2. Delegate validation to GeographyAntiCorruptionLayer
 * 3. Translate Geography context exceptions to Membership context exceptions
 * 4. Enforce membership-specific business rules (e.g., minimum depth for Nepal)
 */
class MemberGeographyValidator
{
    /**
     * Minimum geographic depth requirements per country
     *
     * @var array<string, int>
     */
    private array $minimumDepthRequirements = [
        'NP' => 3, // Nepal requires province, district, local level
        '*'  => 2, // Default for all other countries
    ];

    public function __construct(
        private GeographyAntiCorruptionLayer $geographyACL
    ) {}

    /**
     * Get minimum geographic depth requirement for a country
     *
     * @param CountryCode $countryCode
     * @return int
     */
    private function getMinimumDepthForCountry(CountryCode $countryCode): int
    {
        $country = $countryCode->toString();
        return $this->minimumDepthRequirements[$country] ?? $this->minimumDepthRequirements['*'];
    }

    /**
     * Validate geography for member registration
     *
     * Validates the geography hierarchy and returns the generated GeoPath.
     * Throws InvalidMemberGeographyException for validation failures.
     *
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @param array $geographyIds Array of geographic unit IDs (e.g., [province, district, localLevel])
     * @return GeoPath Validated materialized path
     * @throws InvalidMemberGeographyException
     */
    public function validateForRegistration(string $countryCode, array $geographyIds): GeoPath
    {
        try {
            // Convert to Geography context Value Object
            $country = CountryCode::fromString($countryCode);

            // Delegate to Geography context through anti-corruption layer
            $geoPath = $this->geographyACL->generatePath($country, $geographyIds);

            // Enforce membership-specific business rules
            $this->validateMembershipBusinessRules($country, $geoPath);

            return $geoPath;

        } catch (InvalidHierarchyException $e) {
            throw InvalidMemberGeographyException::invalidHierarchy($e->getMessage());
        } catch (CountryNotSupportedException $e) {
            throw InvalidMemberGeographyException::unsupportedCountry($countryCode);
        } catch (MissingRequiredLevelException $e) {
            throw InvalidMemberGeographyException::missingRequiredLevel($e->getMessage());
        }
    }

    /**
     * Validate membership-specific business rules
     *
     * @param CountryCode $countryCode
     * @param GeoPath $geoPath
     * @throws InvalidMemberGeographyException
     */
    private function validateMembershipBusinessRules(CountryCode $countryCode, GeoPath $geoPath): void
    {
        $requiredDepth = $this->getMinimumDepthForCountry($countryCode);
        $countryCodeString = $countryCode->toString();

        if ($geoPath->getDepth() < $requiredDepth) {
            // Use CountryCode's getName() if available, fallback to code
            $countryName = method_exists($countryCode, 'getName')
                ? $countryCode->getName()
                : $countryCodeString;

            Log::warning('Member geography insufficient depth', [
                'country_code' => $countryCodeString,
                'country_name' => $countryName,
                'depth' => $geoPath->getDepth(),
                'required_depth' => $requiredDepth,
                'validation_failed' => 'insufficient_depth',
            ]);

            throw InvalidMemberGeographyException::insufficientDetail(
                "{$countryName} requires at least {$requiredDepth} geographic levels for membership"
            );
        }

        Log::info('Member geography validation passed', [
            'country_code' => $countryCodeString,
            'depth' => $geoPath->getDepth(),
            'required_depth' => $requiredDepth,
            'validation_passed' => true,
        ]);
    }
}