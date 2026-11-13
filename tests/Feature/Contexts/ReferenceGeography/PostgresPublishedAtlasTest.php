<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\PublishedAtlas;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlas;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-4d, the PostgreSQL atlas reader (PublishedAtlas port; v3.11 §5, §5.2, §6; completion §4 clarification A):
 * - a claim is published iff its (revision, content version) is named by a refgeo_publications row; its position is
 *   that row's position; several claims of a slot may share it;
 * - the reader is fixed at p₀ = the committed position when it is made; later publications and unpublished claims
 *   (frozen-but-unpublished versions, drafts) are never part of it;
 * - for the same published claims it answers exactly as InMemoryPublishedAtlas, the reference implementation the
 *   validator was proven against; so validating through it gives the same result.
 * The harness keeps data between tests: every unit id here is fresh, so other tests' publications never match.
 */
final class PostgresPublishedAtlasTest extends RefgeoPostgresTestCase
{
    /** @var list<PublishedClaim> what this test published, with positions, for the in-memory reference */
    private array $published = [];

    private static function u(): ReferenceUnitId
    {
        return ReferenceUnitId::fromString((string) Str::uuid());
    }

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function claim(SlotKey $slot, ?string $value, ValidInterval $interval): Claim
    {
        return new Claim(ClaimId::fromString((string) Str::uuid()), $slot, $value === null ? ClaimValue::none() : ClaimValue::of($value), $interval, new Basis(BasisKind::Instrument, 'act'));
    }

    private static function freeze(string $revision, Claim ...$claims): void
    {
        $atlas = PostgresPublishedAtlas::at(DB::connection());
        foreach ($claims as $c) {
            DB::table('refgeo_claims')->insert([
                'claim_id' => $c->id->value(), 'revision_id' => $revision, 'content_version' => 1, 'kind' => $c->slot->kind()->value, 'slot_key' => $c->slot->encoded(),
                'value' => $c->value->isNone() ? null : $c->value->value(), 'valid_from' => $c->interval->from->value(), 'valid_to' => $c->interval->to?->value(),
                'basis_kind' => $c->basis->kind->value, 'basis_reference' => $c->basis->reference,
                // G-4e: frozen with its before-image: the belief at the committed position
                'before_image' => PostgresRevisionRepository::beforeImageJson(BeforeImage::of($atlas->history($c->slot), $c->interval, $atlas->p0())),
            ]);
        }
    }

    /** Freezes the claims as version 1 of a new revision and publishes it (the G-4a append rule); returns the position. */
    private function publish(Claim ...$claims): int
    {
        return DB::transaction(function () use ($claims): int {
            $revision = (string) Str::uuid();
            self::freeze($revision, ...$claims);
            $position = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence FOR UPDATE')->last_position + 1;
            DB::insert("INSERT INTO refgeo_publications (position, kind, revision_id, content_version, roster_version, governance_rule, recorded_at) VALUES (?, 'revision', ?, 1, 0, 'era-a-sole-publication', clock_timestamp())", [$position, $revision]);
            foreach ($claims as $c) {
                $this->published[] = new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, $position - 1), $position);
            }

            return $position;
        });
    }

    private function reference(int $p0): InMemoryPublishedAtlas
    {
        return new InMemoryPublishedAtlas($p0, $this->published);
    }

    private static function answers(PublishedAtlas $atlas, array $slots, array $families): array
    {
        $out = [];
        foreach ($slots as $slot) {
            $h = $atlas->history($slot);
            foreach (['2015-06-01', '2016-06-01', '2018-06-01', '2021-06-01'] as $d) {
                $out[$slot->encoded()][$d] = $h->priorAt(ValidDate::fromString($d))->describe();
            }
            $out[$slot->encoded()]['boundaries'] = array_map(static fn (ValidDate $x): string => $x->value(), $h->boundariesWithin(self::iv('2015-01-01')));
        }
        foreach ($families as $i => [$kind, $fixed, $value, $interval]) {
            $members = array_map(static fn (SlotKey $s): string => $s->encoded(), $atlas->familySlots($kind, $fixed, $value, $interval));
            sort($members);
            $out["family-$i"] = $members;
        }

        return $out;
    }

    public function test_it_answers_exactly_as_the_reference_atlas_for_the_same_published_claims(): void
    {
        [$x, $y, $a, $b] = [self::u(), self::u(), self::u(), self::u()];
        $name = SlotKey::sourceName($x, 'gazette', 'ne', 'official');
        $this->publish(
            self::claim(SlotKey::containment($x, 'admin'), $a->value(), self::iv('2016-01-01')),
            self::claim(SlotKey::containment($y, 'admin'), $a->value(), self::iv('2016-01-01', '2019-01-01')),
            self::claim(SlotKey::classification($x, 'admin'), 'district', self::iv('2016-01-01')),
        );
        $this->publish(
            self::claim(SlotKey::containment($x, 'admin'), $b->value(), self::iv('2018-01-01')),   // supersedes from 2018
            self::claim($name, 'Koshi', self::iv('2016-01-01', '2020-01-01')),                       // two claims of one slot,
            self::claim($name, null, self::iv('2020-01-01')),                                        // one publication
        );
        $atlas = PostgresPublishedAtlas::at(DB::connection());
        $slots = [SlotKey::containment($x, 'admin'), SlotKey::containment($y, 'admin'), SlotKey::classification($x, 'admin'), $name];
        $families = [
            [ClaimKind::Containment, ['scheme' => 'admin'], $a->value(), self::iv('2016-01-01')],
            [ClaimKind::Containment, ['scheme' => 'admin'], $a->value(), self::iv('2019-06-01')],
            [ClaimKind::Containment, [], $b->value(), self::iv('2016-01-01', '2017-01-01')],
            [ClaimKind::Containment, ['scheme' => 'electoral'], $a->value(), self::iv('2016-01-01')],
        ];

        $this->assertSame((int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position, $atlas->p0());
        $this->assertSame(self::answers($this->reference($atlas->p0()), $slots, $families), self::answers($atlas, $slots, $families));
        $this->assertTrue($atlas->history($name)->priorAt(ValidDate::fromString('2021-06-01'))->isNone(), 'a withdrawal is read as such');
    }

    public function test_unpublished_claims_never_enter_the_atlas(): void
    {
        $x = self::u();
        self::freeze((string) Str::uuid(), self::claim(SlotKey::existence($x), 'exists', self::iv('2016-01-01')));   // frozen, never published
        DB::table('refgeo_revisions')->insert(['id' => $draft = (string) Str::uuid(), 'state' => 'draft', 'row_version' => 1]);
        DB::table('refgeo_revision_claims')->insert(['revision_id' => $draft, 'claim_id' => (string) Str::uuid(), 'slot_key' => SlotKey::classification($x, 'admin')->encoded(),
            'value' => 'district', 'valid_from' => '2016-01-01', 'valid_to' => null, 'basis_kind' => 'instrument', 'basis_reference' => 'act']);

        $atlas = PostgresPublishedAtlas::at(DB::connection());

        $this->assertTrue($atlas->history(SlotKey::existence($x))->priorAt(ValidDate::fromString('2017-01-01'))->isAbsent());
        $this->assertTrue($atlas->history(SlotKey::classification($x, 'admin'))->priorAt(ValidDate::fromString('2017-01-01'))->isAbsent());
        $ofX = array_filter($atlas->familySlots(ClaimKind::Classification, ['scheme' => 'admin'], 'district', self::iv('2016-01-01')), static fn (SlotKey $s): bool => $s->subject()?->equals($x) ?? false);
        $this->assertSame([], array_values($ofX), 'the draft classification is no family member');
    }

    public function test_the_reader_is_fixed_at_its_p0(): void
    {
        $x = self::u();
        $slot = SlotKey::classification($x, 'admin');
        $this->publish(self::claim($slot, 'district', self::iv('2016-01-01')));
        $atlas = PostgresPublishedAtlas::at(DB::connection());
        $this->publish(self::claim($slot, 'province', self::iv('2016-01-01')));

        $this->assertSame('district', $atlas->history($slot)->resolve(ValidDate::fromString('2017-01-01'))?->value(), 'a publication after p0 is not part of it');
        $this->assertSame('province', PostgresPublishedAtlas::at(DB::connection())->history($slot)->resolve(ValidDate::fromString('2017-01-01'))?->value());
        $this->assertSame('district', PostgresPublishedAtlas::at(DB::connection(), $atlas->p0())->history($slot)->resolve(ValidDate::fromString('2017-01-01'))?->value(), 'an explicit p0 reads as at that position');
    }

    public function test_validating_through_it_gives_the_same_result_as_through_the_reference_atlas(): void
    {
        $parent = self::u();
        // the issuer's competence (D-82) and the parent's existence, published
        $this->publish(CompetentInstrument::competenceClaim(), self::claim(SlotKey::existence($parent), 'exists', self::iv('1950-01-01')));
        $atlas = PostgresPublishedAtlas::at(DB::connection());
        $child = self::u();
        $claims = [
            self::claim(SlotKey::containment($child, 'admin'), $parent->value(), self::iv('2016-01-01')),
            self::claim(SlotKey::containment(self::u(), 'admin'), self::u()->value(), self::iv('2016-01-01')),   // an unknown parent
        ];
        $evidence = CompetentInstrument::byId(['act']);

        $viaPostgres = (new RevisionValidator())->validate($claims, new DependencyRecordingReader($atlas), $evidence);
        $viaReference = (new RevisionValidator())->validate($claims, new DependencyRecordingReader($this->reference($atlas->p0())), $evidence);

        $this->assertSame($viaReference->describe(), $viaPostgres->describe());
        $this->assertSame(array_map(static fn ($v): string => $v->rule, $viaReference->violations), array_map(static fn ($v): string => $v->rule, $viaPostgres->violations));
        $this->assertContains('PARENT_EXISTS', array_map(static fn ($v): string => $v->rule, $viaPostgres->violations), 'the unknown parent is found through the database');
    }
}
