<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\CldrDisplayNames;
use App\Contexts\ReferenceGeography\Domain\CldrLocale;
use App\Contexts\ReferenceGeography\Domain\CldrParentLocales;
use App\Contexts\ReferenceGeography\Domain\CldrRelease;
use App\Contexts\ReferenceGeography\Domain\DisplayNameOrigin;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-7 (D-87; v3.11 §9; Option A, 2026-11-14: D-87 extended to the five level-1 regions): the display name
 * of an entry in the user's language, from CLDR only.
 * - the requested locale, then its CLDR parents (an explicit parent map of the release, else truncation), ending at
 *   root, which is NOT assumed to be English;
 * - the short form if asked, else the standard form, and the result says the short form fell back;
 * - if the chain has nothing: the PLATFORM DEFAULT LOCALE's chain, marked PLATFORM_FALLBACK (never presented as CLDR);
 * - nothing at all: null. PublicDigit authors no name.
 */
final class CldrDisplayNamesTest extends TestCase
{
    private const ENTRY = '7a7a7a7a-7a7a-4a7a-8a7a-7a7a7a7a7a7a';
    private static int $n = 0;

    /**
     * @param array<string, string> $names "locale/form" => value (release 46), or "locale/form" => [value, release]
     * @return \Closure(string, string): ?Claim
     */
    private static function names(array $names): \Closure
    {
        $claims = [];
        foreach ($names as $key => $v) {
            [$locale, $form] = explode('/', $key);
            [$value, $release] = is_array($v) ? $v : [$v, '46'];
            $claims[$key] = new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', ++self::$n)),
                SlotKey::cldrName(ReferenceUnitId::fromString(self::ENTRY), $locale, $form),
                $value === null ? ClaimValue::none() : ClaimValue::of($value), new ValidInterval(ValidDate::fromString('2000-01-01')), new Basis(BasisKind::Other, "cldr:{$release}"));
        }

        return static fn (string $locale, string $form): ?Claim => $claims["{$locale}/{$form}"] ?? null;
    }

    public function test_the_requested_locale_gives_a_cldr_name_with_its_release(): void
    {
        $r = CldrDisplayNames::resolve('de', 'standard', self::names(['de/standard' => 'Deutschland']), CldrParentLocales::none(), null);

        $this->assertSame(['Deutschland', DisplayNameOrigin::Cldr, 'de', 'de', '46', 'standard', false, null],
            [$r->value, $r->origin, $r->requestedLocale, $r->resolvedLocale, $r->release, $r->form, $r->shortFellBack, $r->fallbackLocale]);
    }

    public function test_the_chain_truncates_then_follows_the_release_parent_map_and_ends_at_root(): void
    {
        $names = self::names(['de/standard' => 'Deutschland', 'en/standard' => 'Germany', 'root/standard' => 'DE']);
        $this->assertSame('de', CldrDisplayNames::resolve('de_AT', 'standard', $names, CldrParentLocales::none(), null)->resolvedLocale, 'de_AT → de by truncation');

        $parents = CldrParentLocales::fromJson('{"en_IN":"en_001","en_001":"en"}');
        $this->assertSame(['en_IN', 'en_001', 'en', 'root'], $parents->chain('en_IN'));
        $this->assertSame('en', CldrDisplayNames::resolve('en_IN', 'standard', $names, $parents, null)->resolvedLocale, 'en_IN → en_001 (the map) → en');

        $this->assertSame(['ne_NP', 'ne', 'root'], CldrParentLocales::none()->chain('ne-NP'), 'a hyphen is read as CLDR\'s underscore');
        $root = CldrDisplayNames::resolve('ne', 'standard', $names, CldrParentLocales::none(), null);
        $this->assertSame(['DE', 'root', DisplayNameOrigin::Cldr], [$root->value, $root->resolvedLocale, $root->origin], 'root is part of the chain and is not English');
    }

    public function test_a_short_form_falls_back_to_the_standard_form_and_says_so(): void
    {
        $names = self::names(['en/standard' => 'United Kingdom', 'en/short' => 'UK', 'de/standard' => 'Vereinigtes Königreich']);

        $short = CldrDisplayNames::resolve('en', 'short', $names, CldrParentLocales::none(), null);
        $this->assertSame(['UK', 'short', false], [$short->value, $short->form, $short->shortFellBack]);
        $fell = CldrDisplayNames::resolve('de', 'short', $names, CldrParentLocales::none(), null);
        $this->assertSame(['Vereinigtes Königreich', 'standard', true], [$fell->value, $fell->form, $fell->shortFellBack], 'no short form in the chain: the standard form, marked');
    }

    public function test_an_empty_chain_falls_back_to_the_platform_default_locale_marked_as_such(): void
    {
        $names = self::names(['en/standard' => 'Germany']);

        $r = CldrDisplayNames::resolve('ne', 'standard', $names, CldrParentLocales::none(), 'en');
        $this->assertSame(['Germany', DisplayNameOrigin::PlatformFallback, 'ne', null, null, 'en'],
            [$r->value, $r->origin, $r->requestedLocale, $r->resolvedLocale, $r->release, $r->fallbackLocale], 'never presented as a CLDR result');
        $this->assertNull(CldrDisplayNames::resolve('ne', 'standard', $names, CldrParentLocales::none(), null), 'no default locale published: nothing, no invented name');
        $this->assertNull(CldrDisplayNames::resolve('ne', 'standard', $names, CldrParentLocales::none(), 'fr'), 'nothing in the default chain either');
    }

    public function test_a_withdrawn_name_is_no_name(): void
    {
        $names = self::names(['de_AT/standard' => [null, '46'], 'de/standard' => 'Deutschland']);
        $this->assertSame('de', CldrDisplayNames::resolve('de_AT', 'standard', $names, CldrParentLocales::none(), null)->resolvedLocale);
    }

    public function test_a_cyclic_parent_map_ends_the_chain_instead_of_looping(): void
    {
        $this->assertSame(['aa_BB', 'cc_DD', 'root'], CldrParentLocales::fromJson('{"aa_BB":"cc_DD","cc_DD":"aa_BB"}')->chain('aa_BB'));
    }

    public function test_releases_locales_and_parent_maps_are_parsed_strictly(): void
    {
        $this->assertSame('46.1', CldrRelease::fromBasis(new Basis(BasisKind::Other, 'cldr:46.1'))->value());
        $this->assertNull(CldrRelease::fromBasis(new Basis(BasisKind::Other, 'gazette')));
        $this->assertNull(CldrRelease::fromBasis(new Basis(BasisKind::Instrument, 'cldr:46')), 'only an Other basis names a CLDR release');
        $this->assertTrue(CldrRelease::of('47')->isNewerThan(CldrRelease::of('46.1')));
        $this->assertTrue(CldrRelease::of('46.10')->isNewerThan(CldrRelease::of('46.9')), 'numeric, not lexicographic');

        foreach (['de', 'de_AT', 'en_001', 'zh_Hant_TW', 'root', 'sr_Latn'] as $ok) {
            $this->assertTrue(CldrLocale::isValid($ok), $ok);
        }
        foreach (['', 'DE', 'de-AT', 'english', 'de_', '_AT', 'de AT'] as $bad) {
            $this->assertFalse(CldrLocale::isValid($bad), "'{$bad}'");
        }
        $this->assertNull(CldrParentLocales::tryFromJson('["de"]'), 'a list is not a map');
        $this->assertNull(CldrParentLocales::tryFromJson('{"de_AT":"DE"}'), 'a malformed locale');
        $this->assertNull(CldrParentLocales::tryFromJson('not json'));
    }
}
