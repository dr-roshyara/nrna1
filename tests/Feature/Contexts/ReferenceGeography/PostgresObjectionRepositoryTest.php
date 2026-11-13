<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\ObjectionChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\ObjectionService;
use App\Contexts\ReferenceGeography\Application\Ports\ObjectionRepository;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedClaims;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\Objection;
use App\Contexts\ReferenceGeography\Domain\ObjectionAssessment;
use App\Contexts\ReferenceGeography\Domain\ObjectionId;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresObjectionRepository;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlas;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedClaims;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-346 G-6b on the isolated cluster: objections round-trip through the aggregate; saves are optimistic; the
 * evidence already cited against a claim is reported (reconsideration needs new evidence); one revision belongs to at
 * most one objection; only a PUBLISHED claim counts as published (a frozen, unpublished one does not); production
 * wiring.
 */
final class PostgresObjectionRepositoryTest extends RefgeoPostgresTestCase
{
    private const ORG = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';

    private static function p(string $who): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $who));
    }

    private static function repo(): PostgresObjectionRepository
    {
        return new PostgresObjectionRepository(DB::connection());
    }

    private static function submitted(ClaimId $target, array $evidence = ['sha256:a']): Objection
    {
        return Objection::submitOnBehalf(ObjectionId::fromString((string) Str::uuid()), self::ORG, $target, 'challenge', $evidence, self::p('recorder'));
    }

    /** One claim, frozen in refgeo_claims; published only if $publish (a publication row names it). */
    private static function claim(bool $publish): ClaimId
    {
        $c = new Claim(ClaimId::fromString((string) Str::uuid()), SlotKey::existence(ReferenceUnitId::fromString((string) Str::uuid())), ClaimValue::of('exists'),
            new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'act'));
        DB::transaction(static function () use ($c, $publish): void {
            $position = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1 FOR UPDATE')->last_position + 1;
            $revision = (string) Str::uuid();
            $atlas = PostgresPublishedAtlas::at(DB::connection());
            DB::table('refgeo_claims')->insert([
                'claim_id' => $c->id->value(), 'revision_id' => $revision, 'content_version' => 1, 'kind' => $c->slot->kind()->value, 'slot_key' => $c->slot->encoded(),
                'value' => $c->value->value(), 'valid_from' => $c->interval->from->value(), 'valid_to' => null, 'basis_kind' => $c->basis->kind->value,
                'basis_reference' => $c->basis->reference, 'before_image' => PostgresRevisionRepository::beforeImageJson(BeforeImage::of($atlas->history($c->slot), $c->interval, $atlas->p0())),
            ]);
            if ($publish) {
                DB::insert("INSERT INTO refgeo_publications (position, kind, revision_id, content_version, roster_version, governance_rule, recorded_at)
                    VALUES (?, 'revision', ?, 1, (SELECT last_roster_version FROM refgeo_sequence WHERE id = 1), 'test-fixture-direct', clock_timestamp())", [$position, $revision]);
            }
        });

        return $c->id;
    }

    public function test_production_wiring_binds_the_repository_the_published_claims_and_the_service(): void
    {
        $this->assertInstanceOf(PostgresObjectionRepository::class, app(ObjectionRepository::class));
        $this->assertInstanceOf(PostgresPublishedClaims::class, app(PublishedClaims::class));
        $this->assertInstanceOf(ObjectionService::class, app(ObjectionService::class));
    }

    public function test_only_a_published_claim_is_published(): void
    {
        $published = new PostgresPublishedClaims(DB::connection());

        $this->assertTrue($published->isPublished(self::claim(true)));
        $this->assertFalse($published->isPublished(self::claim(false)), 'frozen but never published');
        $this->assertFalse($published->isPublished(ClaimId::fromString((string) Str::uuid())));
    }

    public function test_every_state_round_trips_and_cited_evidence_is_reported_per_claim(): void
    {
        $target = ClaimId::fromString((string) Str::uuid());
        $assessed = self::submitted($target, ['sha256:b']);
        $assessed->assess(new ObjectionAssessment(true, true, true), self::p('assessor'));
        $rejected = self::submitted($target, ['sha256:a', 'sha256:c']);
        $rejected->assess(new ObjectionAssessment(false, true, true), self::p('assessor'));
        $rejected->reject('not authentic', self::p('assessor'));
        $corrected = self::submitted(ClaimId::fromString((string) Str::uuid()), ['sha256:z']);
        $corrected->assess(new ObjectionAssessment(true, true, true), self::p('assessor'));
        $corrected->correct(RevisionId::fromString((string) Str::uuid()), self::p('assessor'));

        foreach ([self::submitted($target), $assessed, $rejected, $corrected] as $o) {
            self::repo()->add($o);
            $this->assertEquals($o->snapshot(), self::repo()->get($o->id())->objection->snapshot(), $o->state()->value);
        }
        $cited = self::repo()->evidenceCitedAgainst($target);
        sort($cited);
        $this->assertSame(['sha256:a', 'sha256:b', 'sha256:c'], $cited, 'other claims\' evidence not included');
    }

    public function test_a_stale_save_is_refused(): void
    {
        $o = self::submitted(ClaimId::fromString((string) Str::uuid()));
        self::repo()->add($o);
        $first = self::repo()->get($o->id());
        $second = self::repo()->get($o->id());
        $first->objection->assess(new ObjectionAssessment(true, true, true), self::p('a'));
        self::repo()->save($first->objection, $first->rowVersion);
        $second->objection->assess(new ObjectionAssessment(false, false, false), self::p('b'));

        $this->expectException(ObjectionChangedConcurrently::class);
        self::repo()->save($second->objection, $second->rowVersion);
    }

    public function test_one_revision_belongs_to_at_most_one_objection(): void
    {
        $revision = RevisionId::fromString((string) Str::uuid());
        foreach ([0, 1] as $i) {
            $o = self::submitted(ClaimId::fromString((string) Str::uuid()));
            $o->assess(new ObjectionAssessment(true, true, true), self::p('assessor'));
            $o->dispute($revision, self::p('assessor'));
            if ($i === 1) {
                $this->expectException(QueryException::class);
            }
            self::repo()->add($o);
        }
    }

    public function test_the_table_refuses_a_rejection_without_a_reason(): void
    {
        $this->expectException(QueryException::class);
        DB::insert("INSERT INTO refgeo_objections (id, state, organisation_id, target_claim, statement, evidence, recorded_by, assessed_by, decided_by,
            authentic, mandate, applicable) VALUES (?, 'rejected', ?, ?, 's', '[\"e\"]', ?, ?, ?, false, true, true)",
            [(string) Str::uuid(), self::ORG, (string) Str::uuid(), md5('a'), md5('b'), md5('b')]);
    }
}
