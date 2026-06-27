<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

/**
 * PotentialMatches Value Object
 *
 * Container for fuzzy matching results, categorized by similarity level.
 */
final class PotentialMatches
{
    /**
     * @param array<string, array<MatchResult>> $matchesByCategory
     * @param int $totalCount
     * @param MatchResult|null $bestMatch
     */
    private function __construct(
        private readonly array $matchesByCategory,
        private readonly int $totalCount,
        private readonly ?MatchResult $bestMatch
    ) {}

    /**
     * Create from array of MatchResult objects
     *
     * @param array<MatchResult> $matchResults
     */
    public static function fromMatchResults(array $matchResults): self
    {
        $matchesByCategory = [
            'exact' => [],
            'very_high' => [],
            'high' => [],
            'medium' => [],
            'low' => [],
        ];

        $bestMatch = null;
        $bestScore = 0.0;

        foreach ($matchResults as $match) {
            $category = MatchCategory::fromSimilarityScore($match->similarityScore());

            if ($category->isMatch()) {
                $matchesByCategory[$category->value][] = $match;

                // Track best match
                $score = $match->similarityScore()->toFloat();
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = $match;
                }
            }
        }

        return new self(
            $matchesByCategory,
            count($matchResults),
            $bestMatch
        );
    }

    /**
     * Get matches for a specific category
     *
     * @return array<MatchResult>
     */
    public function getMatchesForCategory(MatchCategory $category): array
    {
        return $this->matchesByCategory[$category->value] ?? [];
    }

    /**
     * Get all matches across all categories
     *
     * @return array<MatchResult>
     */
    public function getAllMatches(): array
    {
        $allMatches = [];
        foreach ($this->matchesByCategory as $categoryMatches) {
            $allMatches = array_merge($allMatches, $categoryMatches);
        }
        return $allMatches;
    }

    /**
     * Get matches grouped by category
     *
     * @return array<string, array<MatchResult>>
     */
    public function getMatchesByCategory(): array
    {
        return $this->matchesByCategory;
    }

    /**
     * Check if there are any matches
     */
    public function hasMatches(): bool
    {
        return $this->totalCount > 0;
    }

    /**
     * Check if there are exact matches
     */
    public function hasExactMatches(): bool
    {
        return !empty($this->matchesByCategory['exact']);
    }

    /**
     * Check if there are very high similarity matches
     */
    public function hasVeryHighMatches(): bool
    {
        return !empty($this->matchesByCategory['very_high']);
    }

    /**
     * Check if there are high similarity matches
     */
    public function hasHighMatches(): bool
    {
        return !empty($this->matchesByCategory['high']);
    }

    /**
     * Get total number of matches
     */
    public function totalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * Get best match (highest similarity score)
     */
    public function bestMatch(): ?MatchResult
    {
        return $this->bestMatch;
    }

    /**
     * Get similarity score of best match, or 0.0 if no matches
     */
    public function bestMatchScore(): SimilarityScore
    {
        if ($this->bestMatch === null) {
            return SimilarityScore::fromFloat(0.0);
        }
        return $this->bestMatch->similarityScore();
    }

    /**
     * Convert to array for API response
     */
    public function toArray(): array
    {
        $result = [
            'matches_by_category' => [],
            'total_matches' => $this->totalCount,
            'best_match' => null,
        ];

        foreach ($this->matchesByCategory as $category => $matches) {
            $result['matches_by_category'][$category] = array_map(
                fn(MatchResult $match) => $match->toArray(),
                $matches
            );
        }

        if ($this->bestMatch !== null) {
            $result['best_match'] = $this->bestMatch->toArray();
        }

        return $result;
    }
}