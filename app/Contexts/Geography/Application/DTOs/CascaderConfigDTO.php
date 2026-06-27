<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\DTOs;

use App\Contexts\Geography\Domain\Models\Country;
use App\Contexts\Geography\Domain\ValueObjects\GeographicScope;

final readonly class CascaderConfigDTO implements \JsonSerializable
{
    public function __construct(
        public string $scope,
        public ?string $initialCountryCode,
        public array $allowedCountryCodes,
        public bool $showCountrySelector,
        public array $levelLabels,
        public int $maxDepth,
        public array $levels = [],
    ) {}

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function fromOrganisation($organisation): self
    {
        $scope = GeographicScope::from($organisation->geographic_scope ?? 'single_country');

        // Determine allowed countries based on scope
        $allowedCountryCodes = match($scope) {
            GeographicScope::WORLDWIDE => self::getAllCountryCodes(),
            GeographicScope::MULTI_COUNTRY => $organisation->allowed_countries ?? [],
            GeographicScope::SINGLE_COUNTRY => [$organisation->base_country_code ?? 'NP'],
            GeographicScope::SUB_COUNTRY => [$organisation->base_country_code ?? 'NP'],
        };

        // Only set initialCountryCode if country is NOT required to be selected
        $initialCountryCode = match($scope) {
            GeographicScope::WORLDWIDE => null,
            GeographicScope::MULTI_COUNTRY => null,
            GeographicScope::SINGLE_COUNTRY => $organisation->base_country_code ?? 'NP',
            GeographicScope::SUB_COUNTRY => $organisation->base_country_code ?? 'NP',
        };

        $levelCountry = $organisation->base_country_code ?? 'NP';

        // Get organisation's geographic structure (defaults to Nepal if not set)
        $structure = $organisation->getGeographicStructure();
        $levels = $structure->getLevelsArray();

        return new self(
            scope: $scope->value,
            initialCountryCode: $initialCountryCode,
            allowedCountryCodes: $allowedCountryCodes,
            showCountrySelector: $scope->requiresCountrySelector(),
            levelLabels: $structure->getLabels(),
            maxDepth: $structure->getMaxDepth(),
            levels: $levels,
        );
    }

    private static function getLevelLabelsForCountry(string $countryCode): array
    {
        return match(strtoupper($countryCode)) {
            'NP' => ['Province', 'District', 'Municipality', 'Ward'],
            'DE' => ['State', 'District', 'Municipality', 'Ward'],
            default => ['Level 1', 'Level 2', 'Level 3', 'Level 4'],
        };
    }

    private static function getMaxDepthForCountry(string $countryCode): int
    {
        return 4; // Assume 4 levels for now — extend if needed
    }

    private static function getAllCountryCodes(): array
    {
        return Country::where('is_active', true)
            ->orderBy('code')
            ->pluck('code')
            ->toArray();
    }

    public function toArray(): array
    {
        return [
            'scope' => $this->scope,
            'initialCountryCode' => $this->initialCountryCode,
            'allowedCountryCodes' => $this->allowedCountryCodes,
            'showCountrySelector' => $this->showCountrySelector,
            'levelLabels' => $this->levelLabels,
            'maxDepth' => $this->maxDepth,
            'levels' => $this->levels,
        ];
    }
}
