<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Domain\PlaceMatcher;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * PBDIGIT-346 G-5b closure (Principal review 2026-11-13): PlaceMatcher::similarity is claimed to BE pg_trgm's
 * similarity(). This compares the two on the isolated cluster, pair by pair, after PlaceMatcher's own normalisation
 * (NFKC, lower case, punctuation as space), which is PlaceLookup's documented semantics and is applied to both sides:
 * the comparison isolates the trigram algorithm. pg_trgm returns a real (float4), so the tolerance is 1e-6.
 * Cases: ASCII, case, punctuation, spacing, short strings, digits, partial words, ties, the 0.5 / 0.7 band edges,
 * Latin with diacritics, Devanagari.
 */
final class PlaceMatcherPgTrgmDifferentialTest extends RefgeoPostgresTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
    }

    /** @return array<string, array{string, string}> */
    public static function pairs(): array
    {
        return [
            'identical' => ['Kathmandu', 'Kathmandu'],
            'one letter off' => ['Kathmandoo', 'Kathmandu'],
            'case' => ['KATHMANDU', 'kathmandu'],
            'punctuation' => ['Kathmandu-Metropolitan, City.', 'kathmandu metropolitan city'],
            'spacing' => ['  Lalit   pur ', 'Lalitpur'],
            'word in a longer name' => ['Kathmandu', 'Kathmandu Metropolitan City'],
            'pg_trgm documentation example' => ['word', 'two words'],
            'single letter' => ['a', 'a'],
            'two letters' => ['ab', 'abc'],
            'disjoint' => ['abc', 'xyz'],
            'digits' => ['Ward 12', 'Ward 21'],
            'repeated trigrams' => ['aaaa', 'aaa'],
            'tie one' => ['Patan', 'Patna'],
            'tie two' => ['Patan', 'Patam'],
            'near the 0.5 band' => ['Biratnagar', 'Birat Nagar'],
            'near the 0.7 band' => ['Bharatpur', 'Bharatpura'],
            'Latin with diacritics' => ['Zürich', 'Zurich'],
            'Latin with diacritics, same' => ['São Paulo', 'são paulo'],
            'Devanagari' => ['बागमती', 'बागमती प्रदेश'],
            'Devanagari, one sign off' => ['काठमाडौं', 'काठमाडौँ'],
        ];
    }

    #[DataProvider('pairs')]
    public function test_php_similarity_equals_pg_trgm_similarity(string $a, string $b): void
    {
        $x = PlaceMatcher::normalise($a);
        $y = PlaceMatcher::normalise($b);
        $pg = (float) DB::selectOne('SELECT similarity(?, ?) AS s', [$x, $y])->s;

        $this->assertEqualsWithDelta($pg, PlaceMatcher::similarity($x, $y), 1e-6, "pg_trgm similarity('{$x}', '{$y}') = {$pg}");
    }
}
