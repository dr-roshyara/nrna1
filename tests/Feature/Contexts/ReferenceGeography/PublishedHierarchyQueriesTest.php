<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\Published\GeographyReadModel;
use App\Contexts\ReferenceGeography\Application\Published\Knowledge;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresGeographyReadSource;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-355 (Principal decision 2026-11-15, "Extend the contract"): two additive read-only queries on Reference
 * Geography's published contract, on the isolated cluster.
 * - topLevelAt: the places existing at t with no parent (containment in force at t) in any scheme, as known at p;
 * - childrenInAnySchemeOf: childrenOf across every scheme, each child named with its scheme;
 * - both published-only, temporal like placesAt / childrenOf, and BOUNDED IN QUERIES (not one query per place):
 *   slotsOfKind loads every history of a kind in one query, with exactly the histories slotHistory gives.
 * The cluster is shared: assertions are relative to this test's own fresh units.
 */
final class PublishedHierarchyQueriesTest extends RefgeoPostgresTestCase
{
    use DirectPublishing;

    private static function geography(): GeographyReadModel
    {
        return app(GeographyReadModel::class);
    }

    private static function last(): Knowledge
    {
        return new Knowledge((int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position);
    }

    public function test_the_top_level_follows_containment_in_force_at_t(): void
    {
        [$country, $province, $hidden] = [self::unit(), self::unit(), self::unit()];
        $p = self::publish(
            self::c(SlotKey::existence($country), 'exists', '1768-01-01'),
            self::c(SlotKey::existence($province), 'exists', '2000-01-01'),
            self::c(SlotKey::containment($province, 'admin'), $country->value(), '2010-01-01'),
            self::c(SlotKey::existence($hidden), 'exists'),
        );
        self::freezeUnpublished(self::c(SlotKey::containment($hidden, 'admin'), $country->value()));
        $g = self::geography();
        $k = self::last();

        $this->assertContains($country->value(), $g->topLevelAt('2020-01-01', $k));
        $this->assertNotContains($province->value(), $g->topLevelAt('2020-01-01', $k), 'contained from 2010');
        $this->assertContains($province->value(), $g->topLevelAt('2005-01-01', $k), 'no parent in force in 2005');
        $this->assertNotContains($country->value(), $g->topLevelAt('1700-01-01', $k), 'did not exist yet');
        $this->assertContains($hidden->value(), $g->topLevelAt('2020-01-01', $k), 'an unpublished containment is not a parent');
        $this->assertNotContains($country->value(), $g->topLevelAt('2020-01-01', $g->knowledgeAt($p - 1)), 'not yet published');

        $top = $g->topLevelAt('2020-01-01', $k);
        $places = $g->placesAt('2020-01-01', $k);
        $this->assertSame($top, array_values(array_filter($places, fn (string $u): bool => $g->place($u, '2020-01-01', $k)->parents === [])),
            'the same answer as placesAt filtered by place()->parents');
    }

    public function test_children_in_any_scheme_name_the_scheme_and_follow_time_and_publication(): void
    {
        [$region, $a, $b, $c, $later] = [self::unit(), self::unit(), self::unit(), self::unit(), self::unit()];
        $p = self::publish(
            self::c(SlotKey::existence($region), 'exists', '1768-01-01'),
            self::c(SlotKey::containment($a, 'admin'), $region->value()),
            self::c(SlotKey::containment($b, 'electoral'), $region->value()),
            self::c(SlotKey::containment($c, 'admin'), $region->value(), '2000-01-01', '2010-01-01'),
        );
        self::freezeUnpublished(self::c(SlotKey::containment($later, 'admin'), $region->value()));
        $g = self::geography();
        $k = self::last();

        $expected = [['scheme' => 'admin', 'unitId' => $a->value()], ['scheme' => 'electoral', 'unitId' => $b->value()]];
        usort($expected, static fn (array $x, array $y): int => [$x['scheme'], $x['unitId']] <=> [$y['scheme'], $y['unitId']]);
        $this->assertSame($expected, $g->childrenInAnySchemeOf($region->value(), '2020-01-01', $k), 'both schemes; c ended in 2010; unpublished excluded');
        $this->assertContains(['scheme' => 'admin', 'unitId' => $c->value()], $g->childrenInAnySchemeOf($region->value(), '2005-01-01', $k));
        $this->assertSame([], $g->childrenInAnySchemeOf($region->value(), '2020-01-01', $g->knowledgeAt($p - 1)), 'not yet published');
        $admin = array_column(array_filter($g->childrenInAnySchemeOf($region->value(), '2020-01-01', $k), static fn (array $x): bool => $x['scheme'] === 'admin'), 'unitId');
        $this->assertSame($g->childrenOf($region->value(), 'admin', '2020-01-01', $k), array_values($admin), 'per scheme, the same answer as childrenOf');
    }

    public function test_slots_of_a_kind_are_loaded_in_one_query_with_the_same_histories(): void
    {
        [$x, $y] = [self::unit(), self::unit()];
        self::publish(self::c(SlotKey::existence($x), 'exists'), self::c(SlotKey::containment($y, 'admin'), $x->value()));
        self::publish(self::c(SlotKey::containment($y, 'admin'), $x->value(), '2015-01-01'));
        $source = new PostgresGeographyReadSource(DB::connection());
        $p = self::last()->position;

        DB::enableQueryLog();
        $batched = $source->slotsOfKind(ClaimKind::Containment, $p);
        $queries = count(DB::getQueryLog());
        DB::disableQueryLog();
        DB::flushQueryLog();

        $this->assertLessThanOrEqual(2, $queries, 'one query for the kind, plus at most the committed-position check');
        $this->assertNotEmpty($batched);
        foreach ($batched as $history) {
            $this->assertEquals(self::claims($source->slotHistory($history->slot(), $p), $p), self::claims($history, $p), 'the same history as slotHistory: ' . $history->slot()->encoded());
        }
    }

    public function test_the_top_level_costs_a_bounded_number_of_queries_whatever_the_number_of_places(): void
    {
        $claims = [];
        for ($i = 0; $i < 60; $i++) {
            $parent = self::unit();
            $claims[] = self::c(SlotKey::existence($parent), 'exists');
            for ($j = 0; $j < 3; $j++) {
                $child = self::unit();
                $claims[] = self::c(SlotKey::existence($child), 'exists');
                $claims[] = self::c(SlotKey::containment($child, 'admin'), $parent->value());
            }
        }
        self::publish(...$claims);
        $g = self::geography();
        $k = self::last();

        DB::enableQueryLog();
        $g->topLevelAt('2020-01-01', $k);
        $queries = count(DB::getQueryLog());
        DB::disableQueryLog();
        DB::flushQueryLog();

        $this->assertLessThanOrEqual(6, $queries, "topLevelAt used {$queries} queries for 240 places");
    }

    /** @return list<array{string, ?string, string, ?string, int}> a history's claims as plain values, in order */
    private static function claims(SlotHistory $history, int $p): array
    {
        $out = [];
        $claim = $history->latest($p);
        foreach (['2000-01-01', '2015-01-01', '2020-01-01'] as $t) {
            $at = $history->claimAt(\App\Contexts\ReferenceGeography\Domain\ValidDate::fromString($t), $p);
            $out[] = [$t, $at?->id->value(), $at?->value->isNone() ? null : $at?->value->value(), $at === null ? null : (string) $history->positionOf($at->id), 0];
        }
        $out[] = ['latest', $claim?->id->value(), null, null, 0];

        return $out;
    }
}
