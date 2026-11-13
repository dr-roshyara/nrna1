<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\RevisionChangedConcurrently;
use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\DependencySet;
use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\FamilyDependency;
use App\Contexts\ReferenceGeography\Domain\JudgmentReason;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PriorValue;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionSnapshot;
use App\Contexts\ReferenceGeography\Domain\RevisionState;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotDependency;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\SubjectFamilyDependency;
use App\Contexts\ReferenceGeography\Domain\ValidationResult;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Domain\Violation;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use App\Contexts\ReferenceGeography\Infrastructure\StoredRevisionCorrupt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-4d, revision persistence (completion §4 tables; G-4 interpretation §3; the Principal's G-4d proof list):
 * - every state (draft, submitted, stale, re-prepared, withdrawn) and every part (claims, evidence, assessments,
 *   approvals, persons, flags, validation with all three dependency forms, violations, before-images, disputed
 *   reliances, content version and digest) survives a reload exactly; the reloaded aggregate behaves identically;
 * - a submitted version's claims are frozen in refgeo_claims (immutable, G-4a);
 * - a reload rebuilds ONLY through Revision::fromSnapshot: tampered content (digest), duplicate or overlapping claims,
 *   and invalid dependency records are refused as StoredRevisionCorrupt, never repaired;
 * - a save is one transaction: a failure part-way leaves the previous state; a concurrent change is refused.
 */
final class PostgresRevisionRepositoryTest extends MembershipPostgresTestCase
{
    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    private static function repo(): PostgresRevisionRepository
    {
        return new PostgresRevisionRepository(DB::connection());
    }

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function reader(): DependencyRecordingReader
    {
        $c = CompetentInstrument::competenceClaim();

        return new DependencyRecordingReader(new InMemoryPublishedAtlas(1, [new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, 0), 1)]));
    }

    /** A fresh revision with one existence claim and two claims of one name slot (non-overlapping). */
    private static function draft(): Revision
    {
        $unit = ReferenceUnitId::fromString((string) Str::uuid());
        $r = Revision::prepare(RevisionId::fromString((string) Str::uuid()), self::p('a'));
        $r->addClaim(new Claim(ClaimId::fromString((string) Str::uuid()), SlotKey::existence($unit), ClaimValue::of('exists'), self::iv('2016-01-01'), new Basis(BasisKind::Instrument, 'ev-1')), self::p('a'));
        $name = SlotKey::sourceName($unit, 'gazette', 'ne', 'official');
        $r->addClaim(new Claim(ClaimId::fromString((string) Str::uuid()), $name, ClaimValue::of('Koshi'), self::iv('2016-01-01', '2020-01-01'), new Basis(BasisKind::Instrument, 'ev-1')), self::p('a'));
        $r->addClaim(new Claim(ClaimId::fromString((string) Str::uuid()), $name, ClaimValue::none(), self::iv('2020-01-01'), new Basis(BasisKind::Correction, 'corr-1')), self::p('a'));
        $r->attachEvidence(CompetentInstrument::evidence('ev-1'), self::p('a'));
        $r->attachEvidence(new EvidenceVersion('corr-1', str_repeat('b', 64)), self::p('b'));
        $r->flagJudgmentMatter(self::p('z'), JudgmentReason::CompetingInstruments);

        return $r;
    }

    private static function submit(Revision $r, string $assessor = 's'): Revision
    {
        foreach ([...AssessmentKind::required(), AssessmentKind::DuplicateSearch] as $kind) {
            $r->recordAssessment(new Assessment($kind, "outcome {$kind->value}", self::p($assessor)));
        }
        $r->validateWith(new RevisionValidator(), self::reader());
        $r->submit(self::p($assessor));

        return $r;
    }

    private function assertRoundTrip(Revision $r, string $state): void
    {
        $stored = self::repo()->get($r->id());
        $this->assertEquals($r->snapshot(), $stored->revision->snapshot(), "{$state}: the reload is exact");
    }

    public function test_every_state_and_every_part_survives_a_reload(): void
    {
        $r = self::draft();
        self::repo()->add($r);
        $this->assertRoundTrip($r, 'draft');

        self::submit($r);
        $r->approve(new Approval(1, 7, self::p('x')));
        $v = self::repo()->save($r, self::repo()->get($r->id())->rowVersion);
        $this->assertRoundTrip($r, 'submitted');

        $r->markStale();
        self::repo()->save($r, $v);
        $this->assertRoundTrip($r, 'stale');

        $r->reprepare(self::p('b'));
        self::repo()->save($r, self::repo()->get($r->id())->rowVersion);
        $this->assertRoundTrip($r, 're-prepared (draft of version 2)');

        self::submit($r, 't');
        self::repo()->save($r, self::repo()->get($r->id())->rowVersion);
        $this->assertRoundTrip($r, 'version 2 submitted');

        $r->withdraw(self::p('a'));
        self::repo()->save($r, self::repo()->get($r->id())->rowVersion);
        $this->assertRoundTrip($r, 'withdrawn');
        $this->assertSame(RevisionState::Withdrawn, self::repo()->get($r->id())->revision->state());
    }

    public function test_a_validation_with_every_dependency_form_violations_before_images_and_disputes_survives(): void
    {
        $base = self::draft()->snapshot();
        $claim = $base->claims[0];
        $validation = new ValidationResult(
            [new Violation('PARENT_EXISTS', $claim->id, 'a detail with | and = and : in it')],
            new DependencySet(41, [
                new SlotDependency($claim->slot, self::iv('2016-01-01')),
                FamilyDependency::of(ClaimKind::Containment, ['scheme' => 'admin'], (string) $claim->slot->subject()?->value(), self::iv('2016-01-01', '2030-01-01')),
                FamilyDependency::of(ClaimKind::Containment, [], 'x', self::iv('2016-01-01')),
                new SubjectFamilyDependency($claim->slot->subject()),
            ]),
            [$claim->id->value() => BeforeImage::fromSegments([[self::iv('2016-01-01', '2018-01-01'), PriorValue::of('none')], [self::iv('2018-01-01', '2019-01-01'), PriorValue::none()], [self::iv('2019-01-01'), PriorValue::absent()]])],
            true,
            [CompetentInstrument::competenceClaim()->id],
        );
        $r = Revision::fromSnapshot(new RevisionSnapshot(...[...get_object_vars($base), 'validation' => $validation]));
        self::repo()->add($r);

        $this->assertRoundTrip($r, 'draft with a hand-built validation');
    }

    public function test_the_reloaded_aggregate_behaves_identically(): void
    {
        $original = self::draft();
        self::repo()->add($original);
        $reloaded = self::repo()->get($original->id())->revision;

        foreach ([$original, $reloaded] as $r) {
            self::submit($r);
            $r->approve(new Approval(1, 3, self::p('x')));
        }
        $this->assertEquals($original->snapshot(), $reloaded->snapshot(), 'same digest, same approvals, same excluded persons');
    }

    public function test_a_submitted_versions_claims_are_frozen_in_refgeo_claims(): void
    {
        $r = self::submit(self::draft());
        self::repo()->add($r);

        $frozen = DB::table('refgeo_claims')->where('revision_id', $r->id()->value())->where('content_version', 1)->count();
        $this->assertSame(3, $frozen);
        $r->markStale();
        $r->reprepare(self::p('a'));
        self::submit($r);
        self::repo()->save($r, self::repo()->get($r->id())->rowVersion);
        $this->assertSame(3, DB::table('refgeo_claims')->where('revision_id', $r->id()->value())->where('content_version', 2)->count());
        $this->assertSame(3, DB::table('refgeo_claims')->where('revision_id', $r->id()->value())->where('content_version', 1)->count(), 'version 1 unchanged');
    }

    public function test_tampered_stored_content_is_refused_never_repaired(): void
    {
        $tamper = [
            'submitted content changed (digest)' => static fn (string $id) => DB::update("UPDATE refgeo_revision_claims SET value = 'changed' WHERE revision_id = ? AND value = 'exists'", [$id]),
            'two overlapping claims of one slot' => static fn (string $id) => DB::insert("INSERT INTO refgeo_revision_claims (revision_id, claim_id, slot_key, value, valid_from, valid_to, basis_kind, basis_reference) SELECT revision_id, ?, slot_key, value, valid_from, valid_to, basis_kind, basis_reference FROM refgeo_revision_claims WHERE revision_id = ? AND value = 'exists'", [(string) Str::uuid(), $id]),
            'an unknown dependency form' => static fn (string $id) => DB::update("UPDATE refgeo_dependency_predicates SET form = 'bogus' WHERE revision_id = ? AND seq = 1", [$id]),
            'an incomplete dependency record' => static fn (string $id) => DB::update('UPDATE refgeo_dependency_predicates SET slot_key = NULL, kind = NULL, subject = NULL WHERE revision_id = ? AND seq = 1', [$id]),
            'an unparseable slot key' => static fn (string $id) => DB::update("UPDATE refgeo_revision_claims SET slot_key = 'EXISTENCE|unit=3:abc|' WHERE revision_id = ? AND value = 'exists'", [$id]),
        ];
        foreach ($tamper as $why => $act) {
            $r = self::submit(self::draft());
            self::repo()->add($r);
            $act($r->id()->value());
            try {
                self::repo()->get($r->id());
                $this->fail("must be refused: {$why}");
            } catch (StoredRevisionCorrupt) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_save_failing_part_way_leaves_the_previous_state(): void
    {
        $r = self::draft();
        self::repo()->add($r);
        $before = self::repo()->get($r->id());
        $r->recordAssessment(new Assessment(AssessmentKind::Authenticity, 'FAIL-HERE', self::p('s')));

        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE FUNCTION refgeo_test_fail_here() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN IF NEW.outcome = 'FAIL-HERE' THEN RAISE EXCEPTION 'test: failure injected part-way'; END IF; RETURN NEW; END $$;
            CREATE TRIGGER refgeo_test_fail_here BEFORE INSERT ON refgeo_assessments FOR EACH ROW EXECUTE FUNCTION refgeo_test_fail_here();
            SQL);
        try {
            self::repo()->save($r, $before->rowVersion);
            $this->fail('the injected failure must surface');
        } catch (\Illuminate\Database\QueryException) {
            $this->addToAssertionCount(1);
        } finally {
            DB::unprepared('DROP TRIGGER refgeo_test_fail_here ON refgeo_assessments; DROP FUNCTION refgeo_test_fail_here();');
        }

        $after = self::repo()->get($r->id());
        $this->assertSame($before->rowVersion, $after->rowVersion);
        $this->assertEquals($before->revision->snapshot(), $after->revision->snapshot(), 'no partial business record');
    }

    public function test_a_concurrent_change_is_refused_and_writes_nothing(): void
    {
        $r = self::draft();
        self::repo()->add($r);
        $first = self::repo()->get($r->id());
        $second = self::repo()->get($r->id());

        $first->revision->flagJudgmentMatter(self::p('q'), JudgmentReason::Dispute);
        self::repo()->save($first->revision, $first->rowVersion);
        $second->revision->recordAssessment(new Assessment(AssessmentKind::Competence, 'late', self::p('s')));

        $this->expectException(RevisionChangedConcurrently::class);
        try {
            self::repo()->save($second->revision, $second->rowVersion);
        } finally {
            $this->assertEquals($first->revision->snapshot(), self::repo()->get($r->id())->revision->snapshot());
        }
    }

    public function test_a_revision_is_added_once(): void
    {
        $r = self::draft();
        self::repo()->add($r);

        $this->expectException(RevisionChangedConcurrently::class);
        self::repo()->add($r);
    }
}
