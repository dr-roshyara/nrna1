<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Repositories;

use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;

/**
 * FuzzyMatchingRepository Interface
 *
 * Repository for fuzzy matching of geographic names.
 * Uses PostgreSQL trigram similarity and other fuzzy matching techniques.
 */
interface FuzzyMatchingRepositoryInterface
{
    /**
     * Find similar geographic units by name
     *
     * @param string $candidateName The name to match against
     * @param CountryCode $countryCode Limit to specific country
     * @param int $level Administrative level (1-8)
     * @param GeoUnitId|null $parentId Limit to children of specific parent
     * @param float $similarityThreshold Minimum similarity score (0.0-1.0)
     * @param int $limit Maximum number of results
     * @return array<\stdClass> Array of objects with: id, name_local, admin_level, parent_id, country_code, similarity_score, match_type
     */
    public function findSimilarNames(
        string $candidateName,
        CountryCode $countryCode,
        int $level,
        ?GeoUnitId $parentId = null,
        float $similarityThreshold = 0.3,
        int $limit = 10
    ): array;

    /**
     * Suggest correction for a potentially misspelled name
     *
     * @param string $candidateName The potentially misspelled name
     * @param CountryCode $countryCode Limit to specific country
     * @param float $similarityThreshold Minimum similarity score
     * @return string|null Suggested correct name, or null if no good match
     */
    public function suggestCorrection(
        string $candidateName,
        CountryCode $countryCode,
        float $similarityThreshold = 0.7
    ): ?string;

    /**
     * Normalize Nepal-specific geography name variations
     *
     * @param string $name Raw geography name
     * @return string Normalized name (lowercase, prefixes/suffixes removed, variations standardized)
     */
    public function normalizeNepaliName(string $name): string;

    /**
     * Get common Nepal-specific name variations
     *
     * @return array<string, string> Map of variation => standard form
     */
    public function getNepalVariations(): array;
}