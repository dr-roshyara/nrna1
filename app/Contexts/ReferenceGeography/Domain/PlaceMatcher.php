<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * PlaceLookup's matching rules (G-5b; design §4.2). The legacy matcher's ALGORITHM, copied, never imported:
 * - normalise: NFKC, lower case, every non-letter/digit a space, spaces collapsed;
 * - similarity: PostgreSQL pg_trgm trigram similarity (words padded "  w" … "d "), reimplemented so the Domain decides;
 * - a name scores the best of its whole and of any contiguous run of its words (partial);
 * - a code (identifier) matches only exactly, ignoring case and surrounding spaces: codes are never fuzzy (I-1).
 * Its DATA is not copied: no hard-coded variants, no designation stripping. Variants are published names (G-8).
 */
final class PlaceMatcher
{
    public static function normalise(string $text): string
    {
        $text = \Normalizer::normalize($text, \Normalizer::FORM_KC) ?: $text;
        $text = mb_strtolower($text, 'UTF-8');
        $text = (string) preg_replace('/[^\p{L}\p{M}\p{N}]+/u', ' ', $text);

        return trim($text);
    }

    public static function similarity(string $a, string $b): float
    {
        $x = self::trigrams($a);
        $y = self::trigrams($b);
        $union = count($x + $y);

        return $union === 0 ? 0.0 : count(array_intersect_key($x, $y)) / $union;
    }

    /**
     * @param list<LookupCandidate> $candidates
     * @return list<PlaceMatch> one per place, its best; best first (score, then whole before partial, then id)
     */
    public static function best(string $text, array $candidates): array
    {
        $query = self::normalise($text);
        if ($query === '') {
            return [];
        }
        $best = [];
        foreach ($candidates as $candidate) {
            [$score, $partial] = $candidate->kind === LookupCandidate::IDENTIFIER
                ? [strtoupper(trim($text)) === strtoupper(trim($candidate->value)) ? 1.0 : 0.0, false]
                : self::scoreName($query, self::normalise($candidate->value));
            $quality = MatchQuality::fromScore($score);
            if ($quality === null) {
                continue;
            }
            $match = new PlaceMatch($candidate->unit, $quality, $score, $candidate, $partial);
            $key = $candidate->unit->value();
            if (!isset($best[$key]) || self::order($match, $best[$key]) < 0) {
                $best[$key] = $match;
            }
        }
        $results = array_values($best);
        // across places: score, then whole before partial, then the place id (design §4.3); never the matched text
        usort($results, static fn (PlaceMatch $a, PlaceMatch $b): int => [$b->score, $a->partial, $a->unit->value()] <=> [$a->score, $b->partial, $b->unit->value()]);

        return $results;
    }

    /** @return array{float, bool} [score, partial] */
    private static function scoreName(string $query, string $placeText): array
    {
        $whole = $query === $placeText ? 1.0 : self::similarity($query, $placeText);
        $words = $placeText === '' ? [] : explode(' ', $placeText);
        $partial = 0.0;
        for ($i = 0, $n = count($words); $i < $n; $i++) {
            for ($len = 1; $i + $len <= $n; $len++) {
                if ($len === $n) {
                    continue;   // the whole name is scored above
                }
                $span = implode(' ', array_slice($words, $i, $len));
                $partial = max($partial, $span === $query ? 1.0 : self::similarity($query, $span));
            }
        }

        return $partial > $whole ? [$partial, true] : [$whole, false];
    }

    /** Within ONE place, its best candidate: higher score; then whole before partial; then the value (deterministic). */
    private static function order(PlaceMatch $a, PlaceMatch $b): int
    {
        return [$b->score, $a->partial, $a->candidate->value] <=> [$a->score, $b->partial, $b->candidate->value];
    }

    /** @return array<string, true> pg_trgm trigrams of the words of an already normalised text */
    private static function trigrams(string $text): array
    {
        $set = [];
        foreach (preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $word) {
            $chars = mb_str_split('  ' . $word . ' ', 1, 'UTF-8');
            for ($i = 0, $n = count($chars); $i + 2 < $n; $i++) {
                $set[$chars[$i] . $chars[$i + 1] . $chars[$i + 2]] = true;
            }
        }

        return $set;
    }
}
