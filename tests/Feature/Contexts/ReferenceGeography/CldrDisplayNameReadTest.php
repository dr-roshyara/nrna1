<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\PublicationStatus;
use App\Contexts\ReferenceGeography\Application\Published\DisplayName;
use App\Contexts\ReferenceGeography\Application\Published\GeographyReadModel;
use App\Contexts\ReferenceGeography\Application\Published\Knowledge;
use App\Contexts\ReferenceGeography\Application\Published\KnowledgeNotAvailable;
use App\Contexts\ReferenceGeography\Application\PublishedGeography;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresGeographyReadSource;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-346 G-7 (D-87; v3.11 §9; Option A, 2026-11-14): GeographyReadModel::displayName through the real publication
 * path. A level-1 region (Europe) gets CLDR names like a country (Option A), its UN name stays an attributed source name
 * in Place::$names; names are read as known at k (a later CLDR release never changes an earlier answer); with no CLDR
 * name in the user's chain the published platform default locale is used and MARKED as a platform fallback.
 * The default locale "en" here is a TEST FIXTURE value: its production value is a G-8 decision of the Principal.
 */
final class CldrDisplayNameReadTest extends RefgeoPostgresTestCase
{
    use PublishingFixtures;

    private static function model(): GeographyReadModel
    {
        return new PublishedGeography(new PostgresGeographyReadSource(DB::connection()), new PostgresPublicationLedger(DB::connection()));
    }

    private static function claim(SlotKey $slot, string $value, string $basis, string $from = '2000-01-01'): Claim
    {
        return new Claim(ClaimId::fromString((string) Str::uuid()), $slot, ClaimValue::of($value), new ValidInterval(ValidDate::fromString($from)), new Basis(BasisKind::Other, $basis));
    }

    /** Publishes non-structural claims (names, maps, the default locale): one assessing steward, no second person. */
    private static function publishClaims(string $t, Claim ...$claims): int
    {
        $id = RevisionId::fromString((string) Str::uuid());
        self::appoint("s-$t");
        self::declare("s-$t", $id);
        $r = Revision::prepare($id, self::p("s-$t"));
        foreach ($claims as $c) {
            $r->addClaim($c, self::p("s-$t"));
            // every basis names attached evidence; for a CLDR claim the evidence IS the release ("cldr:46", Unicode CLDR)
            $r->attachEvidence(new EvidenceVersion($c->basis->reference, hash('sha256', $c->basis->reference),
                str_starts_with($c->basis->reference, 'cldr:') ? 'Unicode CLDR' : 'test-source', ValidDate::fromString('2024-10-01')), self::p("s-$t"));   // D-227: issuer with its date
        }
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p("s-$t")));
        }
        $r->validateWith(new RevisionValidator(), new DependencyRecordingReader(PostgresPublishedAtlas::at(DB::connection())));
        $r->submit(self::p("s-$t"));
        self::repo()->add($r);
        $out = self::publisher("s-$t")->publish($id);
        if ($out->status !== PublicationStatus::Published) {
            throw new \RuntimeException('fixture: not published: ' . $out->status->value . ' ' . implode(',', array_map(static fn ($x) => $x->value, $out->refusals)));
        }

        return $out->position;
    }

    private static function shown(ReferenceUnitId $entry, string $locale, int $k, string $form = 'standard'): ?DisplayName
    {
        return self::model()->displayName($entry->value(), $locale, '2026-01-01', new Knowledge($k), $form);
    }

    public function test_a_region_is_named_by_cldr_in_the_user_language_and_keeps_its_un_name_attributed(): void
    {
        $t = Str::random(8);
        $europe = ReferenceUnitId::fromString((string) Str::uuid());
        $p = self::publishClaims($t,
            self::claim(SlotKey::cldrName($europe, 'de', 'standard'), 'Europa', 'cldr:46'),
            self::claim(SlotKey::cldrName($europe, 'en', 'standard'), 'Europe', 'cldr:46'),
            self::claim(SlotKey::sourceName($europe, 'UN M49', 'en', 'official'), 'Europe', 'un-m49'),
        );

        $de = self::shown($europe, 'de-AT', $p);
        $this->assertSame([$europe->value(), 'Europa', 'CLDR', 'de_AT', 'de', '46', 'standard', false, null, '2026-01-01', $p],
            [$de->unitId, $de->value, $de->origin, $de->requestedLocale, $de->resolvedLocale, $de->cldrRelease, $de->form, $de->shortFellBack, $de->fallbackLocale, $de->validAt, $de->knownAt]);
        $this->assertContains(['source' => 'UN M49', 'language' => 'en', 'type' => 'official', 'value' => 'Europe'],
            self::model()->place($europe->value(), '2026-01-01', new Knowledge($p))->names, 'the UN name, attributed, beside the CLDR display name (Option A)');
    }

    public function test_a_later_cldr_release_never_changes_an_earlier_answer(): void
    {
        $t = Str::random(8);
        $entry = ReferenceUnitId::fromString((string) Str::uuid());
        $p46 = self::publishClaims($t, self::claim(SlotKey::cldrName($entry, 'de', 'standard'), 'Name 46', 'cldr:46'));
        $p47 = self::publishClaims($t, self::claim(SlotKey::cldrName($entry, 'de', 'standard'), 'Name 47', 'cldr:47'));

        $this->assertSame(['Name 46', '46'], [self::shown($entry, 'de', $p46)->value, self::shown($entry, 'de', $p46)->cldrRelease]);
        $this->assertSame(['Name 47', '47'], [self::shown($entry, 'de', $p47)->value, self::shown($entry, 'de', $p47)->cldrRelease]);
        $this->assertSame('Name 46', self::shown($entry, 'de', $p47 - 1)->value, 'as known before the new release');
        $this->assertNull(self::shown($entry, 'de', $p46 - 1), 'nothing published yet: no invented name');
    }

    public function test_the_release_parent_map_and_the_marked_platform_fallback(): void
    {
        $t = Str::random(8);
        $entry = ReferenceUnitId::fromString((string) Str::uuid());
        $p = self::publishClaims($t,
            self::claim(SlotKey::cldrName($entry, 'en', 'standard'), 'Testland', 'cldr:46'),
            self::claim(SlotKey::cldrName($entry, 'en_001', 'standard'), 'Testland (international English)', 'cldr:46'),
            self::claim(SlotKey::cldrInheritance('46'), '{"en_IN":"en_001","en_001":"en"}', 'cldr:46'),
        );
        // truncation alone would give en_IN → en; only the release map gives en_IN → en_001 (mutant N11)
        $this->assertSame(['Testland (international English)', 'en_001'], [self::shown($entry, 'en_IN', $p)->value, self::shown($entry, 'en_IN', $p)->resolvedLocale], 'en_IN → en_001 by the release map');

        $before = self::model()->displayName($entry->value(), 'ne', '2026-01-01', new Knowledge($p));
        $existingDefault = DB::selectOne("SELECT 1 AS x FROM refgeo_claims WHERE kind = 'PLATFORM_DEFAULT_LOCALE'");
        if ($existingDefault === null) {
            $this->assertNull($before, 'no CLDR name in the chain and no default published: nothing');
        }
        $q = self::publishClaims($t, self::claim(SlotKey::platformDefaultLocale(), 'en', 'test-fixture-not-a-production-choice'));
        $fallback = self::shown($entry, 'ne', $q);
        $this->assertNotNull($fallback, 'with a published default locale the fallback answers');
        $this->assertSame(['Testland', 'PLATFORM_FALLBACK', 'ne', null, null, 'en'],
            [$fallback->value, $fallback->origin, $fallback->requestedLocale, $fallback->resolvedLocale, $fallback->cldrRelease, $fallback->fallbackLocale], 'marked, never presented as CLDR');
    }

    public function test_refusals(): void
    {
        $entry = ReferenceUnitId::fromString((string) Str::uuid());
        foreach (['English', 'de AT', ''] as $bad) {
            try {
                self::model()->displayName($entry->value(), $bad, '2026-01-01', new Knowledge(self::lastPosition()));
                $this->fail("a malformed locale was answered: '{$bad}'");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
        $this->expectException(KnowledgeNotAvailable::class);
        self::model()->displayName($entry->value(), 'de', '2026-01-01', new Knowledge(self::lastPosition() + 5));
    }
}
