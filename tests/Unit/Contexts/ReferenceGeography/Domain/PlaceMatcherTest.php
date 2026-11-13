<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\LookupCandidate;
use App\Contexts\ReferenceGeography\Domain\MatchQuality;
use App\Contexts\ReferenceGeography\Domain\PlaceMatcher;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-5b (design §4): PlaceLookup's matching, pure.
 * - the legacy matcher's ALGORITHM, copied: normalisation; pg_trgm trigram similarity; the legacy quality bands
 *   (exact 1.0, very high ≥ 0.95, high ≥ 0.85, medium ≥ 0.70, low ≥ 0.50; below: no match);
 * - NOT its data: no hard-coded name variants and no designation stripping (variants are published names);
 * - a partial name ("Kathmandu" in "Kathmandu Metropolitan City") is found through the best run of words, and said
 *   to be partial; codes match only exactly; one result per place, its best; best first, then by id.
 */
final class PlaceMatcherTest extends TestCase
{
    private const KTM = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const LALITPUR = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const BAGMATI = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';

    private static function placeName(string $unit, string $value, string $detail = 'en/official'): LookupCandidate
    {
        return new LookupCandidate(ReferenceUnitId::fromString($unit), LookupCandidate::NAME, $value, $detail);
    }

    private static function code(string $unit, string $value, string $namespace = 'iso3166-2'): LookupCandidate
    {
        return new LookupCandidate(ReferenceUnitId::fromString($unit), LookupCandidate::IDENTIFIER, $value, $namespace);
    }

    public function test_normalisation_ignores_case_spacing_punctuation_and_compatibility_forms(): void
    {
        $this->assertSame('kathmandu metropolitan city', PlaceMatcher::normalise("  Kathmandu-Metropolitan   CITY. "));
        $this->assertSame('बागमती', PlaceMatcher::normalise(' बागमती '));
        $this->assertSame('ﬁ', 'ﬁ');
        $this->assertSame('fi', PlaceMatcher::normalise('ﬁ'), 'NFKC: a ligature is its letters');
    }

    public function test_similarity_is_pg_trgm_trigram_similarity(): void
    {
        $this->assertSame(1.0, PlaceMatcher::similarity('word', 'word'));
        $this->assertSame(0.0, PlaceMatcher::similarity('abc', 'xyz'));
        // pg_trgm: similarity('word', 'two words') = 0.363636 (4 shared of 11 trigrams)
        $this->assertEqualsWithDelta(0.363636, PlaceMatcher::similarity('word', 'two words'), 0.000001);
    }

    public function test_quality_bands_are_the_legacy_ones(): void
    {
        $this->assertSame(MatchQuality::Exact, MatchQuality::fromScore(1.0));
        $this->assertSame(MatchQuality::VeryHigh, MatchQuality::fromScore(0.95));
        $this->assertSame(MatchQuality::High, MatchQuality::fromScore(0.85));
        $this->assertSame(MatchQuality::Medium, MatchQuality::fromScore(0.70));
        $this->assertSame(MatchQuality::Low, MatchQuality::fromScore(0.50));
        $this->assertNull(MatchQuality::fromScore(0.49));
    }

    public function test_a_whole_name_matches_exactly_and_a_misspelling_with_lower_quality(): void
    {
        $candidates = [self::placeName(self::KTM, 'Kathmandu'), self::placeName(self::LALITPUR, 'Lalitpur')];

        $exact = PlaceMatcher::best('kathmandu', $candidates);
        $this->assertCount(1, $exact);
        $this->assertSame(self::KTM, $exact[0]->unit->value());
        $this->assertSame(MatchQuality::Exact, $exact[0]->quality);
        $this->assertFalse($exact[0]->partial);

        $typo = PlaceMatcher::best('Kathmandoo', $candidates);
        $this->assertSame(self::KTM, $typo[0]->unit->value());
        $this->assertNotSame(MatchQuality::Exact, $typo[0]->quality);
        $this->assertLessThan(1.0, $typo[0]->score);
    }

    public function test_a_partial_name_is_found_through_its_best_run_of_words_and_said_to_be_partial(): void
    {
        $match = PlaceMatcher::best('Kathmandu', [self::placeName(self::KTM, 'Kathmandu Metropolitan City')]);

        $this->assertCount(1, $match);
        $this->assertSame(1.0, $match[0]->score);
        $this->assertTrue($match[0]->partial);
    }

    public function test_codes_match_only_exactly(): void
    {
        $candidates = [self::code(self::BAGMATI, 'NP-P3')];

        $this->assertSame(MatchQuality::Exact, PlaceMatcher::best(' np-p3 ', $candidates)[0]->quality);
        $this->assertSame([], PlaceMatcher::best('NP-P2', $candidates), 'never fuzzy: NP-P2 is another place');
    }

    public function test_no_hard_coded_variant_is_applied(): void
    {
        $this->assertSame('roshyara', PlaceMatcher::normalise('Roshyara'), 'the legacy NEPAL_VARIATIONS map is not copied');
        $this->assertSame('biratnagar', PlaceMatcher::normalise('Biratnagar'));
        $this->assertSame('pokhara metropolitan city', PlaceMatcher::normalise('Pokhara Metropolitan City'), 'no designation stripping');
    }

    public function test_one_result_per_place_its_best_and_best_first_then_by_id(): void
    {
        $results = PlaceMatcher::best('Lalitpur', [
            self::placeName(self::LALITPUR, 'Patan', 'en/alternative'),
            self::placeName(self::LALITPUR, 'Lalitpur'),
            self::placeName(self::KTM, 'Lalitpur Road'),
            self::placeName(self::BAGMATI, 'Bagmati'),
        ]);

        $this->assertSame([self::LALITPUR, self::KTM], array_map(static fn ($m) => $m->unit->value(), $results));
        $this->assertSame('Lalitpur', $results[0]->candidate->value);
        $this->assertFalse($results[0]->partial, 'a whole match beats a partial one of the same score');
    }

    public function test_blank_text_matches_nothing(): void
    {
        $this->assertSame([], PlaceMatcher::best('  ', [self::placeName(self::KTM, 'Kathmandu')]));
    }
}
