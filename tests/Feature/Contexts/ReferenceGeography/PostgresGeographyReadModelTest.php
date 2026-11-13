<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\Ports\GeographyReadSource;
use App\Contexts\ReferenceGeography\Application\Ports\TimeBoundary;
use App\Contexts\ReferenceGeography\Application\Published\GeographyReadModel;
use App\Contexts\ReferenceGeography\Application\Published\KnowledgeNotAvailable;
use App\Contexts\ReferenceGeography\Application\PublishedGeography;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresGeographyReadSource;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlas;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-346 G-5a on the isolated PostgreSQL cluster (design §2.1, §2.2):
 * - only PUBLISHED claims are answered; a frozen-but-unpublished revision is invisible;
 * - an answer at a position never changes after later publications (PO-5 by immutability);
 * - a time τ is closed through, then mapped to the highest position recorded at or before it; a future τ is refused;
 * - production wiring binds the published contract.
 * The cluster is shared across tests, so every test uses fresh units and asserts relative to its own publications.
 * Fixture claims are published DIRECTLY (under the lock, through the G-4a guards), never through PublishRevision.
 */
final class PostgresGeographyReadModelTest extends RefgeoPostgresTestCase
{
    use PublishingFixtures;

    private static function unit(): ReferenceUnitId
    {
        return ReferenceUnitId::fromString((string) Str::uuid());
    }

    private static function c(SlotKey $slot, ?string $value, string $from = '2015-09-20', ?string $to = null): Claim
    {
        return new Claim(ClaimId::fromString((string) Str::uuid()), $slot, $value === null ? ClaimValue::none() : ClaimValue::of($value),
            new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)), new Basis(BasisKind::Instrument, 'act'));
    }

    /** Publishes the claims as ONE publication, directly; returns its position. */
    private static function publishDirect(Claim ...$claims): int
    {
        return DB::transaction(static function () use ($claims): int {
            $position = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1 FOR UPDATE')->last_position + 1;
            $revision = (string) Str::uuid();
            $atlas = PostgresPublishedAtlas::at(DB::connection());
            foreach ($claims as $c) {
                DB::table('refgeo_claims')->insert([
                    'claim_id' => $c->id->value(), 'revision_id' => $revision, 'content_version' => 1, 'kind' => $c->slot->kind()->value, 'slot_key' => $c->slot->encoded(),
                    'value' => $c->value->isNone() ? null : $c->value->value(), 'valid_from' => $c->interval->from->value(), 'valid_to' => $c->interval->to?->value(),
                    'basis_kind' => $c->basis->kind->value, 'basis_reference' => $c->basis->reference,
                    'before_image' => PostgresRevisionRepository::beforeImageJson(BeforeImage::of($atlas->history($c->slot), $c->interval, $atlas->p0())),
                ]);
            }
            DB::insert("INSERT INTO refgeo_publications (position, kind, revision_id, content_version, roster_version, governance_rule, recorded_at)
                VALUES (?, 'revision', ?, 1, (SELECT last_roster_version FROM refgeo_sequence WHERE id = 1), 'test-fixture-direct', clock_timestamp())", [$position, $revision]);

            return $position;
        });
    }

    private static function geography(): GeographyReadModel
    {
        return new PublishedGeography(new PostgresGeographyReadSource(DB::connection()), new PostgresPublicationLedger(DB::connection()));
    }

    public function test_production_wiring_binds_the_published_contract(): void
    {
        $this->assertInstanceOf(PublishedGeography::class, app(GeographyReadModel::class));
        $this->assertInstanceOf(PostgresGeographyReadSource::class, app(GeographyReadSource::class));
        $this->assertInstanceOf(PostgresPublicationLedger::class, app(TimeBoundary::class));
    }

    public function test_published_places_hierarchy_profile_and_merge_are_answered(): void
    {
        [$country, $province, $old] = [self::unit(), self::unit(), self::unit()];
        $identifier = self::c(SlotKey::identifier($province, 'iso3166-2'), 'NP-P3');
        $p = self::publishDirect(
            self::c(SlotKey::existence($country), 'exists', '1768-01-01'),
            self::c(SlotKey::existence($province), 'exists'),
            self::c(SlotKey::containment($province, 'admin'), $country->value()),
            self::c(SlotKey::classification($province, 'admin'), 'province'),
            $identifier,
            self::c(SlotKey::sourceName($province, 'gazette', 'ne', 'official'), 'बागमती'),
        );
        $merge = self::publishDirect(self::c(SlotKey::reconciliation($old), $province->value(), '1000-01-01'), self::c(SlotKey::dispute($identifier->id), 'disputed'));
        $g = self::geography();
        $k = $g->knowledgeAt($merge);

        $places = $g->placesAt('2020-01-01', $k);
        $this->assertContains($country->value(), $places);
        $this->assertContains($province->value(), $places);
        $this->assertNotContains($province->value(), $g->placesAt('2000-01-01', $k), 'the province did not exist in 2000');

        $place = $g->place($province->value(), '2020-01-01', $k);
        $this->assertNotNull($place);
        $this->assertTrue($place->exists);
        $this->assertSame(['admin' => $country->value()], $place->parents);
        $this->assertSame(['admin' => 'province'], $place->classifications);
        $this->assertSame(['iso3166-2' => 'NP-P3'], $place->identifiers);
        $this->assertSame([['source' => 'gazette', 'language' => 'ne', 'type' => 'official', 'value' => 'बागमती']], $place->names);
        $this->assertSame(['IDENTIFIER'], $place->disputedKinds);
        $this->assertSame([], $g->place($province->value(), '2020-01-01', $g->knowledgeAt($p))->disputedKinds, 'not yet disputed at the first position');

        $this->assertSame([$province->value()], $g->childrenOf($country->value(), 'admin', '2020-01-01', $k));

        $resolution = $g->resolve($old->value(), $k);
        $this->assertTrue($resolution->merged);
        $this->assertSame($province->value(), $resolution->canonical);
        $this->assertSame($merge, $resolution->knownAtPosition);
        $row = DB::selectOne('SELECT recorded_at::text AS r, revision_id::text AS v FROM refgeo_publications WHERE position = ?', [$merge]);
        $this->assertSame($row->r, $resolution->recordedAt);
        $this->assertSame($row->v, $resolution->revisionId);
        $this->assertFalse($g->resolve($old->value(), $g->knowledgeAt($p))->merged, 'not merged before its position');
    }

    public function test_a_frozen_but_unpublished_revision_is_invisible(): void
    {
        $id = self::revision('g5-assessor', 'g5-approver');   // submitted, approved, frozen in refgeo_claims: NOT published
        $unit = DB::selectOne("SELECT slot_key FROM refgeo_claims WHERE revision_id = ? AND kind = 'EXISTENCE'", [$id->value()])->slot_key;
        $unitId = SlotKey::decode($unit)->subject()->value();
        $g = self::geography();
        $k = $g->knowledgeAt((int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position);

        $this->assertNotContains($unitId, $g->placesAt('2020-01-01', $k));
        $this->assertNull($g->place($unitId, '2020-01-01', $k));
    }

    /** Frozen claims of a revision that NO publication names (as after submission, before publication). */
    private static function freezeUnpublished(Claim ...$claims): void
    {
        $revision = (string) Str::uuid();
        $atlas = PostgresPublishedAtlas::at(DB::connection());
        foreach ($claims as $c) {
            DB::table('refgeo_claims')->insert([
                'claim_id' => $c->id->value(), 'revision_id' => $revision, 'content_version' => 1, 'kind' => $c->slot->kind()->value, 'slot_key' => $c->slot->encoded(),
                'value' => $c->value->isNone() ? null : $c->value->value(), 'valid_from' => $c->interval->from->value(), 'valid_to' => $c->interval->to?->value(),
                'basis_kind' => $c->basis->kind->value, 'basis_reference' => $c->basis->reference,
                'before_image' => PostgresRevisionRepository::beforeImageJson(BeforeImage::of($atlas->history($c->slot), $c->interval, $atlas->p0())),
            ]);
        }
    }

    public function test_no_query_exposes_an_unpublished_claim_of_any_kind(): void
    {
        [$country, $province, $hidden, $old] = [self::unit(), self::unit(), self::unit(), self::unit()];
        $identifier = self::c(SlotKey::identifier($province, 'iso3166-2'), 'NP-P3');
        $p = self::publishDirect(self::c(SlotKey::existence($country), 'exists', '1768-01-01'), self::c(SlotKey::existence($province), 'exists'), $identifier);
        self::freezeUnpublished(
            self::c(SlotKey::existence($hidden), 'exists'),
            self::c(SlotKey::containment($hidden, 'admin'), $country->value()),
            self::c(SlotKey::containment($province, 'admin'), $country->value()),
            self::c(SlotKey::reconciliation($old), $province->value(), '1000-01-01'),
            self::c(SlotKey::dispute($identifier->id), 'disputed'),
        );
        $g = self::geography();
        $k = $g->knowledgeAt((int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position);

        $this->assertGreaterThanOrEqual($p, $k->position);
        $this->assertNotContains($hidden->value(), $g->placesAt('2020-01-01', $k), 'unpublished existence');
        $this->assertNull($g->place($hidden->value(), '2020-01-01', $k), 'unpublished place');
        $this->assertSame([], $g->childrenOf($country->value(), 'admin', '2020-01-01', $k), 'unpublished containment');
        $this->assertSame([], $g->place($province->value(), '2020-01-01', $k)->parents, 'unpublished parent');
        $this->assertSame([], $g->place($province->value(), '2020-01-01', $k)->disputedKinds, 'unpublished dispute');
        $this->assertFalse($g->resolve($old->value(), $k)->merged, 'unpublished merge');
    }

    public function test_successive_merges_are_visible_only_from_their_own_positions(): void
    {
        [$a, $b, $c] = [self::unit(), self::unit(), self::unit()];
        $first = self::publishDirect(self::c(SlotKey::existence($a), 'exists'), self::c(SlotKey::existence($c), 'exists'), self::c(SlotKey::reconciliation($b), $a->value(), '1000-01-01'));
        // v3.11 §12 R-2: A into C re-maps B to C in the same revision
        $second = self::publishDirect(self::c(SlotKey::reconciliation($a), $c->value(), '1000-01-01'), self::c(SlotKey::reconciliation($b), $c->value(), '1000-01-01'));
        $g = self::geography();

        $this->assertSame($a->value(), $g->resolve($b->value(), $g->knowledgeAt($first))->canonical);
        $this->assertFalse($g->resolve($a->value(), $g->knowledgeAt($first))->merged, 'the later merge is not visible earlier');
        $this->assertSame($c->value(), $g->resolve($b->value(), $g->knowledgeAt($second))->canonical);
        $this->assertSame($second, $g->resolve($b->value(), $g->knowledgeAt($second))->knownAtPosition);
        $this->assertSame($c->value(), $g->resolve($a->value(), $g->knowledgeAt($second))->canonical);
        $this->assertSame($a->value(), $g->resolve($b->value(), $g->knowledgeAt($first))->canonical, 'asked again at the first position: unchanged');
    }

    public function test_a_place_ending_published_later_keeps_earlier_knowledge(): void
    {
        $unit = self::unit();
        $existed = self::publishDirect(self::c(SlotKey::existence($unit), 'exists', '2016-01-01'));
        $ended = self::publishDirect(self::c(SlotKey::existence($unit), null, '2020-01-01'));
        $g = self::geography();

        $this->assertContains($unit->value(), $g->placesAt('2021-01-01', $g->knowledgeAt($existed)));
        $this->assertNotContains($unit->value(), $g->placesAt('2021-01-01', $g->knowledgeAt($ended)));
        $this->assertContains($unit->value(), $g->placesAt('2019-01-01', $g->knowledgeAt($ended)));
        $this->assertFalse($g->place($unit->value(), '2021-01-01', $g->knowledgeAt($ended))->exists);
    }

    public function test_a_forged_knowledge_beyond_the_committed_position_is_refused_by_every_query(): void
    {
        $forged = new \App\Contexts\ReferenceGeography\Application\Published\Knowledge((int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position + 1);
        $unit = self::unit()->value();
        $g = self::geography();
        foreach ([
            'placesAt' => static fn () => $g->placesAt('2020-01-01', $forged),
            'place' => static fn () => $g->place($unit, '2020-01-01', $forged),
            'childrenOf' => static fn () => $g->childrenOf($unit, 'admin', '2020-01-01', $forged),
            'resolve' => static fn () => $g->resolve($unit, $forged),
        ] as $name => $query) {
            try {
                $query();
                $this->fail("{$name} answered beyond the boundary");
            } catch (KnowledgeNotAvailable) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_lookup_finds_published_names_and_codes_only_and_reports_a_merge(): void
    {
        $tag = substr(str_replace('-', '', (string) Str::uuid()), 0, 10);   // a unique word: the cluster is shared
        [$country, $district, $old, $hidden] = [self::unit(), self::unit(), self::unit(), self::unit()];
        $p = self::publishDirect(
            self::c(SlotKey::existence($country), 'exists', '1768-01-01'),
            self::c(SlotKey::existence($district), 'exists'),
            self::c(SlotKey::containment($district, 'admin'), $country->value()),
            self::c(SlotKey::sourceName($district, 'gazette', 'en', 'official'), "Zz{$tag} District"),
            self::c(SlotKey::sourceNameValue($district, 'gazette', 'en', 'former', "Old{$tag}"), "Old{$tag}"),
            self::c(SlotKey::identifier($district, 'test-ns'), "C-{$tag}"),
            self::c(SlotKey::existence($old), 'exists'),
            self::c(SlotKey::sourceName($old, 'gazette', 'en', 'official'), "Zz{$tag}"),
        );
        $merge = self::publishDirect(self::c(SlotKey::reconciliation($old), $district->value(), '1000-01-01'));
        self::freezeUnpublished(self::c(SlotKey::existence($hidden), 'exists'), self::c(SlotKey::sourceName($hidden, 'gazette', 'en', 'official'), "Zz{$tag}"));
        $g = self::geography();
        $k = $g->knowledgeAt($merge);

        $found = $g->lookup("Zz{$tag}", '2020-01-01', $k);
        $ids = array_map(static fn ($m) => $m->unitId, $found);
        $this->assertSame([$old->value(), $district->value()], $ids, 'whole exact first, then the partial one; the unpublished place never');
        $this->assertSame($district->value(), $found[0]->mergedInto, 'the merged identity says where it resolves');
        $this->assertTrue($found[1]->partial);

        $former = $g->lookup("Old{$tag}", '2020-01-01', $k);
        $this->assertSame([$district->value(), 'en/former'], [$former[0]->unitId, $former[0]->detail], 'a published former name is a published fact');

        $code = $g->lookup("c-{$tag}", '2020-01-01', $k);
        $this->assertSame(['identifier', 'test-ns'], [$code[0]->matchedOn, $code[0]->detail]);

        $this->assertSame([$district->value()], array_map(static fn ($m) => $m->unitId, $g->lookup("Zz{$tag}", '2020-01-01', $k, $country->value(), 'admin')), 'scoped below the country');
        $this->assertNull($g->lookup("Zz{$tag}", '2020-01-01', $g->knowledgeAt($p))[0]->mergedInto, 'not merged at the earlier position');
    }

    public function test_the_feed_gives_every_publication_once_in_order_with_provenance_and_never_unpublished_work(): void
    {
        [$a, $b] = [self::unit(), self::unit()];
        $start = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position;
        $first = self::publishDirect(self::c(SlotKey::existence($a), 'exists'), self::c(SlotKey::containment($a, 'admin'), $b->value()));
        self::freezeUnpublished(self::c(SlotKey::existence(self::unit()), 'exists'));
        $second = self::publishDirect(self::c(SlotKey::reconciliation($b), $a->value(), '1000-01-01'));
        $g = self::geography();
        $k = $g->knowledgeAt($second);

        $feed = $g->publicationsSince($start, $k);
        $this->assertSame(range($start + 1, $second), array_map(static fn ($e) => $e->position, $feed), 'gap-free, ascending, nothing unpublished');
        $mine = array_values(array_filter($feed, static fn ($e) => in_array($e->position, [$first, $second], true)));
        $this->assertSame(['CONTAINMENT', 'EXISTENCE'], $mine[0]->claimKinds);
        $this->assertSame([$a->value()], $mine[0]->subjects);
        $this->assertSame([['identity' => $b->value(), 'canonical' => $a->value()]], $mine[1]->merges);
        $row = DB::selectOne('SELECT recorded_at::text AS r, revision_id::text AS v, governance_rule AS g FROM refgeo_publications WHERE position = ?', [$first]);
        $this->assertSame([$row->r, $row->v, 1, $row->g, 'revision'], [$mine[0]->recordedAt, $mine[0]->revisionId, $mine[0]->contentVersion, $mine[0]->governanceRule, $mine[0]->kind]);

        $this->assertEquals($feed, $g->publicationsSince($start, $k), 'the same range reads the same');
        $paged = array_merge($g->publicationsSince($start, $k, 1), $g->publicationsSince($start + 1, $k, 500));
        $this->assertEquals($feed, $paged, 'paging from the last position received loses and repeats nothing');
        self::publishDirect(self::c(SlotKey::existence(self::unit()), 'exists'));
        $this->assertEquals($feed, $g->publicationsSince($start, $k), 'a later publication does not change a feed read at the same knowledge');
    }

    public function test_an_answer_at_a_position_never_changes_after_later_publications(): void
    {
        [$country, $other, $province] = [self::unit(), self::unit(), self::unit()];
        $before = self::publishDirect(
            self::c(SlotKey::existence($country), 'exists', '1768-01-01'),
            self::c(SlotKey::existence($other), 'exists', '1768-01-01'),
            self::c(SlotKey::existence($province), 'exists'),
            self::c(SlotKey::containment($province, 'admin'), $country->value()),
        );
        $g = self::geography();
        $k = $g->knowledgeAt($before);
        $then = $g->place($province->value(), '2020-01-01', $k);

        $after = self::publishDirect(self::c(SlotKey::containment($province, 'admin'), $other->value()));

        $this->assertEquals($then, $g->place($province->value(), '2020-01-01', $k), 'the same knowledge, the same answer');
        $this->assertSame(['admin' => $other->value()], $g->place($province->value(), '2020-01-01', $g->knowledgeAt($after))->parents);
        $this->assertSame([$province->value()], $g->childrenOf($country->value(), 'admin', '2020-01-01', $k));
        $this->assertSame([], $g->childrenOf($country->value(), 'admin', '2020-01-01', $g->knowledgeAt($after)));
    }

    public function test_a_time_is_closed_through_then_mapped_to_the_highest_position_recorded_by_it(): void
    {
        $unit = self::unit();
        $p = self::publishDirect(self::c(SlotKey::existence($unit), 'exists'));
        $recorded = DB::selectOne('SELECT recorded_at::text AS r FROM refgeo_publications WHERE position = ?', [$p])->r;
        $justBefore = DB::selectOne("SELECT (?::timestamptz - interval '1 microsecond')::text AS t", [$recorded])->t;
        $g = self::geography();

        $at = $g->knowledgeAtTime($recorded);
        $this->assertGreaterThanOrEqual($p, $at->position);
        $this->assertSame($recorded, $at->asOfTime);
        $this->assertContains($unit->value(), $g->placesAt('2020-01-01', $at));

        $this->assertLessThan($p, $g->knowledgeAtTime($justBefore)->position, 'a publication recorded after τ is not known at τ');
        $this->assertNotContains($unit->value(), $g->placesAt('2020-01-01', $g->knowledgeAtTime($justBefore)));
    }

    public function test_a_future_time_is_refused_and_a_position_beyond_the_committed_one_too(): void
    {
        $g = self::geography();

        try {
            $g->knowledgeAtTime('2999-01-01 00:00:00+00');
            $this->fail('a future τ was answered');
        } catch (KnowledgeNotAvailable $e) {
            $this->assertStringContainsString('future', $e->getMessage());
        }
        $this->expectException(KnowledgeNotAvailable::class);
        $g->knowledgeAt((int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position + 1);
    }
}
