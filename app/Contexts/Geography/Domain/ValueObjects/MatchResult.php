<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

/**
 * MatchResult Value Object
 *
 * Represents a single fuzzy matching result.
 */
final class MatchResult
{
    public function __construct(
        private readonly GeoUnitId $geoUnitId,
        private readonly string $name,
        private readonly GeographyLevel $level,
        private readonly ?GeoUnitId $parentId,
        private readonly CountryCode $countryCode,
        private readonly SimilarityScore $similarityScore,
        private readonly string $matchType, // e.g., 'postgres_trgm', 'fuzzy_search', 'soundex'
        private readonly ?string $normalizedName = null
    ) {}

    /**
     * Create from database row
     *
     * @param object $row StdClass with properties: id, name_local, admin_level, parent_id, country_code, similarity_score, match_type
     */
    public static function fromDatabaseRow(object $row): self
    {
        // Extract name from JSON field (name_local is JSON with language keys)
        $name = self::extractNameFromJson($row->name_local);

        return new self(
            GeoUnitId::fromInt((int) $row->id),
            $name,
            GeographyLevel::fromInt((int) $row->admin_level),
            isset($row->parent_id) ? GeoUnitId::fromInt((int) $row->parent_id) : null,
            CountryCode::fromString($row->country_code),
            SimilarityScore::fromFloat((float) $row->similarity_score),
            $row->match_type,
            $row->normalized_name ?? null
        );
    }

    /**
     * Extract name from JSON field
     *
     * @param string $jsonName JSON string like '{"np":"Kathmandu","en":"Kathmandu"}' or plain string
     * @return string Extracted Nepali name or original string if not JSON
     */
    private static function extractNameFromJson(string $jsonName): string
    {
        // Try to decode JSON
        $decoded = json_decode($jsonName, true);

        if (is_array($decoded)) {
            // Return Nepali name if available, otherwise English, otherwise first value
            return $decoded['np'] ?? $decoded['en'] ?? reset($decoded) ?? $jsonName;
        }

        // Not JSON, return original
        return $jsonName;
    }

    public function geoUnitId(): GeoUnitId
    {
        return $this->geoUnitId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function level(): GeographyLevel
    {
        return $this->level;
    }

    public function parentId(): ?GeoUnitId
    {
        return $this->parentId;
    }

    public function countryCode(): CountryCode
    {
        return $this->countryCode;
    }

    public function similarityScore(): SimilarityScore
    {
        return $this->similarityScore;
    }

    public function matchType(): string
    {
        return $this->matchType;
    }

    public function normalizedName(): ?string
    {
        return $this->normalizedName;
    }

    public function matchCategory(): MatchCategory
    {
        return MatchCategory::fromSimilarityScore($this->similarityScore);
    }

    /**
     * Convert to array for API response
     */
    public function toArray(): array
    {
        return [
            'id' => $this->geoUnitId->toInt(),
            'name' => $this->name,
            'level' => $this->level->toInt(),
            'parent_id' => $this->parentId?->toInt(),
            'country_code' => $this->countryCode->toString(),
            'similarity_score' => $this->similarityScore->toFloat(),
            'match_type' => $this->matchType,
            'normalized_name' => $this->normalizedName,
            'match_category' => $this->matchCategory()->value,
            'match_category_label' => $this->matchCategory()->label(),
        ];
    }
}