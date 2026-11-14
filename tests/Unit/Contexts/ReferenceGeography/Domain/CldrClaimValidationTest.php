<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-7 (D-87; v3.11 §3 basis "CLDR release", §9): what a CLDR claim must carry to be published.
 * - D-87_RELEASE: a CLDR_NAME or CLDR_INHERITANCE claim names its release (basis Other, "cldr:<release>"); an
 *   inheritance map's basis release is its slot's release;
 * - D-87_LOCALE: CLDR locales are CLDR identifiers; the platform default locale is a real locale (root is no language);
 * - D-87_INHERITANCE: the inheritance value is a {child: parent} map of CLDR locales.
 */
final class CldrClaimValidationTest extends TestCase
{
    private const ENTRY = '7b7b7b7b-7b7b-4b7b-8b7b-7b7b7b7b7b7b';
    private static int $n = 0;

    private static function claim(SlotKey $slot, string $value, string $basis): Claim
    {
        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', 900 + ++self::$n)), $slot, ClaimValue::of($value),
            new ValidInterval(ValidDate::fromString('2024-01-01')), new Basis(BasisKind::Other, $basis));
    }

    /** @return list<string> the rules violated */
    private static function rules(Claim $claim): array
    {
        $result = (new RevisionValidator())->validate([$claim], new DependencyRecordingReader(new InMemoryPublishedAtlas(0, [])));

        return array_values(array_unique(array_map(static fn ($v) => $v->rule, $result->violations)));
    }

    private static function nameSlot(string $locale): SlotKey
    {
        return SlotKey::cldrName(ReferenceUnitId::fromString(self::ENTRY), $locale, 'standard');
    }

    public function test_well_formed_cldr_claims_are_valid(): void
    {
        $this->assertSame([], self::rules(self::claim(self::nameSlot('de_AT'), 'Deutschland', 'cldr:46')));
        $this->assertSame([], self::rules(self::claim(SlotKey::cldrInheritance('46'), '{"en_IN":"en_001","en_001":"en"}', 'cldr:46')));
        $this->assertSame([], self::rules(self::claim(SlotKey::platformDefaultLocale(), 'en', 'platform-configuration')));
    }

    public function test_a_cldr_name_must_name_its_release(): void
    {
        $this->assertSame(['D-87_RELEASE'], self::rules(self::claim(self::nameSlot('de'), 'Deutschland', 'gazette')));
        $this->assertSame(['D-87_RELEASE'], self::rules(self::claim(self::nameSlot('de'), 'Deutschland', 'cldr:latest')));
    }

    public function test_an_inheritance_map_names_its_own_release_and_is_a_map_of_locales(): void
    {
        $this->assertSame(['D-87_RELEASE'], self::rules(self::claim(SlotKey::cldrInheritance('46'), '{"de_AT":"de"}', 'cldr:47')), 'basis release differs from the slot release');
        $this->assertSame(['D-87_INHERITANCE'], self::rules(self::claim(SlotKey::cldrInheritance('46'), '["de"]', 'cldr:46')));
        $this->assertSame(['D-87_INHERITANCE'], self::rules(self::claim(SlotKey::cldrInheritance('46'), '{"de_AT":"German"}', 'cldr:46')));
    }

    public function test_locales_are_cldr_identifiers_and_the_default_locale_is_a_language(): void
    {
        $this->assertSame(['D-87_LOCALE'], self::rules(self::claim(self::nameSlot('German'), 'Deutschland', 'cldr:46')));
        $this->assertSame(['D-87_LOCALE'], self::rules(self::claim(SlotKey::platformDefaultLocale(), 'root', 'platform-configuration')), 'root is no language to fall back to');
        $this->assertSame(['D-87_LOCALE'], self::rules(self::claim(SlotKey::platformDefaultLocale(), 'en-GB', 'platform-configuration')), 'stored in CLDR form');
    }
}
