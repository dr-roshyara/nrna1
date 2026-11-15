<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

/**
 * PBDIGIT-355 step 1 (frontend M1): the read-only web adapter over Reference Geography's published contract
 * (GeographyReadModel, G-5a) on the isolated PostgreSQL cluster.
 * - only published facts reach a page; a frozen-but-unpublished revision never does;
 * - GET /geography closes the boundary once and redirects to the current position; the pinned pages only read;
 * - an empty publication (position 0, as in production today) gives the empty state;
 * - names: CLDR display name in the user's language (np → ne), a platform fallback marked as such, otherwise an
 *   attributed source name; nothing invented;
 * - parents and children per scheme; unknown place or position → 404; signed-in users only.
 * The cluster is shared across tests: every test uses fresh units and asserts relative to its own publications.
 * Fixture claims are published DIRECTLY (as in PostgresGeographyReadModelTest), never through PublishRevision.
 */
final class GeographyExplorerHttpTest extends RefgeoPostgresTestCase
{
    use DirectPublishing;

    private function signedIn(string $locale = 'en'): self
    {
        return $this->actingAs(User::factory()->create())->withSession(['locale' => $locale]);
    }

    /** @return array<string, mixed>|null the summary of $unit in a list prop, or null */
    private static function find(array $list, ReferenceUnitId $unit): ?array
    {
        foreach ($list as $item) {
            if ($item['unitId'] === $unit->value()) {
                return $item;
            }
        }

        return null;
    }

    public function test_a_guest_is_sent_to_sign_in(): void
    {
        $this->get('/geography')->assertRedirect(route('login'));
        $this->get('/geography/k/0')->assertRedirect(route('login'));
    }

    public function test_the_entry_closes_once_and_redirects_to_the_current_position(): void
    {
        $p = self::publish(self::c(SlotKey::existence(self::unit()), 'exists'));

        $response = $this->signedIn()->get('/geography?date=2020-01-01');

        $last = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position;
        $this->assertGreaterThanOrEqual($p, $last);
        $response->assertRedirect('/geography/k/' . $last . '?date=2020-01-01');
        $this->assertNotNull(DB::selectOne('SELECT closed_through_time FROM refgeo_sequence')->closed_through_time, 'the entry closed the boundary');
    }

    public function test_position_zero_is_the_empty_state(): void
    {
        $this->signedIn()->get('/geography/k/0')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('ReferenceGeography/Explorer', false)
                ->where('knownAt.position', 0)
                ->where('places', [])
                ->where('empty', true));
    }

    public function test_top_level_places_are_listed_and_contained_places_are_not(): void
    {
        [$country, $province] = [self::unit(), self::unit()];
        $p = self::publish(
            self::c(SlotKey::existence($country), 'exists', '1768-01-01'),
            self::c(SlotKey::existence($province), 'exists'),
            self::c(SlotKey::containment($province, 'admin'), $country->value()),
            self::c(SlotKey::classification($province, 'admin'), 'province'),
        );

        $this->signedIn()->get("/geography/k/{$p}?date=2020-01-01")
            ->assertOk()
            ->assertInertia(function (Assert $page) use ($country, $province, $p): void {
                $page->component('ReferenceGeography/Explorer', false)->where('knownAt.position', $p)->where('validDate', '2020-01-01')->where('empty', false);
                $places = $page->toArray()['props']['places'];
                $this->assertNotNull(self::find($places, $country), 'a place without a parent is top level');
                $this->assertNull(self::find($places, $province), 'a contained place is not top level');
                $this->assertSame("/geography/k/{$p}/places/{$country->value()}?date=2020-01-01", self::find($places, $country)['url']);
            });
    }

    public function test_a_place_shows_its_profile_parents_and_children(): void
    {
        [$country, $province] = [self::unit(), self::unit()];
        $p = self::publish(
            self::c(SlotKey::existence($country), 'exists', '1768-01-01'),
            self::c(SlotKey::existence($province), 'exists'),
            self::c(SlotKey::containment($province, 'admin'), $country->value()),
            self::c(SlotKey::classification($province, 'admin'), 'province'),
            self::c(SlotKey::identifier($province, 'iso3166-2'), 'NP-P3'),
            self::c(SlotKey::sourceName($province, 'gazette', 'ne', 'official'), 'बागमती'),
        );

        $this->signedIn()->get("/geography/k/{$p}/places/{$country->value()}?date=2020-01-01")
            ->assertOk()
            ->assertInertia(function (Assert $page) use ($country, $province): void {
                $page->component('ReferenceGeography/Place', false)->where('place.unitId', $country->value())->where('place.parents', []);
                $children = $page->toArray()['props']['place']['children'];
                $this->assertSame([[
                    'scheme' => 'admin', 'unitId' => $province->value(),
                    'label' => ['value' => 'बागमती', 'kind' => 'source', 'language' => 'ne'],
                    'classifications' => ['admin' => 'province'],
                    'url' => "/geography/k/{$page->toArray()['props']['knownAt']['position']}/places/{$province->value()}?date=2020-01-01",
                ]], $children);
            });

        $this->signedIn()->get("/geography/k/{$p}/places/{$province->value()}?date=2020-01-01")
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('ReferenceGeography/Place', false)
                ->where('place.exists', true)
                ->where('place.classifications', ['admin' => 'province'])
                ->where('place.identifiers', ['iso3166-2' => 'NP-P3'])
                ->where('place.sourceNames', [['source' => 'gazette', 'language' => 'ne', 'type' => 'official', 'value' => 'बागमती']])
                ->where('place.parents.0.scheme', 'admin')
                ->where('place.parents.0.unitId', $country->value())
                ->where('place.children', []));
    }

    public function test_an_unpublished_revision_never_reaches_a_page(): void
    {
        [$country, $hidden] = [self::unit(), self::unit()];
        $p = self::publish(self::c(SlotKey::existence($country), 'exists', '1768-01-01'));
        self::freezeUnpublished(self::c(SlotKey::existence($hidden), 'exists'), self::c(SlotKey::containment($hidden, 'admin'), $country->value()));
        $k = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position;
        $this->assertGreaterThanOrEqual($p, $k);

        $this->signedIn()->get("/geography/k/{$k}?date=2020-01-01")->assertOk()
            ->assertInertia(fn (Assert $page) => $this->assertNull(self::find($page->toArray()['props']['places'], $hidden), 'unpublished place listed'));
        $this->signedIn()->get("/geography/k/{$k}/places/{$country->value()}?date=2020-01-01")->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('place.children', []));
        $this->signedIn()->get("/geography/k/{$k}/places/{$hidden->value()}?date=2020-01-01")->assertNotFound();
    }

    public function test_display_names_follow_the_language_np_meaning_ne_and_a_fallback_is_marked(): void
    {
        [$nepal, $only] = [self::unit(), self::unit()];
        if (DB::selectOne("SELECT 1 AS x FROM refgeo_claims WHERE kind = 'PLATFORM_DEFAULT_LOCALE'") === null) {
            self::publish(self::c(SlotKey::platformDefaultLocale(), 'en'));
        }
        $p = self::publish(
            self::c(SlotKey::existence($nepal), 'exists', '1768-01-01'),
            self::c(SlotKey::cldrName($nepal, 'en', 'standard'), 'Nepal'),
            self::c(SlotKey::cldrName($nepal, 'de', 'standard'), 'Nepal (de)'),
            self::c(SlotKey::cldrName($nepal, 'ne', 'standard'), 'नेपाल'),
            self::c(SlotKey::existence($only), 'exists', '1768-01-01'),
            self::c(SlotKey::cldrName($only, 'en', 'standard'), 'Onlyland'),
        );
        $label = function (string $locale, ReferenceUnitId $unit) use ($p): array {
            $props = $this->signedIn($locale)->get("/geography/k/{$p}?date=2020-01-01")->assertOk()->viewData('page')['props'];
            $this->assertSame(['np' => 'ne', 'de' => 'de', 'en' => 'en'][$locale], $props['language']);

            return self::find($props['places'], $unit)['label'];
        };

        $this->assertSame(['value' => 'नेपाल', 'kind' => 'display', 'language' => 'ne'], $label('np', $nepal), 'the frontend locale np is the language ne');
        $this->assertSame(['value' => 'Nepal (de)', 'kind' => 'display', 'language' => 'de'], $label('de', $nepal));
        $this->assertSame(['value' => 'Onlyland', 'kind' => 'display_fallback', 'language' => 'en'], $label('de', $only), 'a platform fallback is marked, never a translation');

        $this->signedIn('np')->get("/geography/k/{$p}/places/{$nepal->value()}?date=2020-01-01")->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('place.displayName.value', 'नेपाल')->where('place.displayName.origin', 'CLDR'));
    }

    public function test_the_pinned_pages_only_read(): void
    {
        $country = self::unit();
        $p = self::publish(self::c(SlotKey::existence($country), 'exists', '1768-01-01'));
        $before = DB::selectOne('SELECT last_position, closed_through_time::text AS w FROM refgeo_sequence');

        $this->signedIn()->get("/geography/k/{$p}?date=2020-01-01")->assertOk();
        $this->signedIn()->get("/geography/k/{$p}/places/{$country->value()}?date=2020-01-01")->assertOk();

        $this->assertEquals($before, DB::selectOne('SELECT last_position, closed_through_time::text AS w FROM refgeo_sequence'), 'no position and no boundary change');
    }

    public function test_an_unknown_position_or_place_is_not_found_and_a_bad_date_is_refused(): void
    {
        $last = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position;
        $this->signedIn()->get('/geography/k/' . ($last + 1000))->assertNotFound();
        $this->signedIn()->get("/geography/k/{$last}/places/" . self::unit()->value())->assertNotFound();
        $this->signedIn()->get("/geography/k/{$last}?date=2020-13-45")->assertSessionHasErrors('date');
    }

    public function test_the_valid_date_defaults_to_today(): void
    {
        $this->signedIn()->get('/geography/k/0')->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('validDate', now()->toDateString()));
    }
}
