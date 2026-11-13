<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\CandidateChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\CandidateService;
use App\Contexts\ReferenceGeography\Application\Ports\CandidateRepository;
use App\Contexts\ReferenceGeography\Domain\Candidate;
use App\Contexts\ReferenceGeography\Domain\CandidateId;
use App\Contexts\ReferenceGeography\Domain\CandidateSubmitter;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresCandidateRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-346 G-6a on the isolated cluster: candidates are stored centrally, rebuilt only through the aggregate,
 * saved against their row version; the table refuses states the lifecycle forbids; a corrupt row is refused on
 * reading; production wiring binds the repository and the service.
 */
final class PostgresCandidateRepositoryTest extends RefgeoPostgresTestCase
{
    private static function p(string $who): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $who));
    }

    private static function repo(): PostgresCandidateRepository
    {
        return new PostgresCandidateRepository(DB::connection());
    }

    private static function fresh(?ReferenceUnitId $subject = null, ?CandidateSubmitter $submitter = null): Candidate
    {
        return Candidate::submit(CandidateId::fromString((string) Str::uuid()), $submitter ?? CandidateSubmitter::steward(self::p('steward')),
            'Ward 33 created by the 2026 act', $subject, ['sha256:' . str_repeat('a', 64)], self::p('steward'));
    }

    public function test_production_wiring_binds_the_repository_and_the_service(): void
    {
        $this->assertInstanceOf(PostgresCandidateRepository::class, app(CandidateRepository::class));
        $this->assertInstanceOf(CandidateService::class, app(CandidateService::class));
    }

    public function test_every_state_round_trips_through_the_aggregate(): void
    {
        $submitted = self::fresh(ReferenceUnitId::fromString((string) Str::uuid()), CandidateSubmitter::organisation((string) Str::uuid()));
        $accepted = self::fresh();
        $accepted->startVerification(self::p('steward'));
        $accepted->accept(RevisionId::fromString((string) Str::uuid()), self::p('second'));
        $rejected = self::fresh();
        $rejected->reject('No competent instrument', self::p('steward'));
        $linked = self::fresh();
        $linked->linkTo($rejected, self::p('steward'));

        foreach ([$submitted, $accepted, $rejected, $linked] as $c) {
            self::repo()->add($c);
            $this->assertEquals($c->snapshot(), self::repo()->get($c->id())->candidate->snapshot(), $c->state()->value);
        }
    }

    public function test_a_stale_save_is_refused_and_nothing_changes(): void
    {
        $c = self::fresh();
        self::repo()->add($c);
        $first = self::repo()->get($c->id());
        $second = self::repo()->get($c->id());
        $first->candidate->startVerification(self::p('steward'));
        self::repo()->save($first->candidate, $first->rowVersion);
        $second->candidate->reject('late', self::p('other'));

        try {
            self::repo()->save($second->candidate, $second->rowVersion);
            $this->fail('a stale save was accepted');
        } catch (CandidateChangedConcurrently) {
            $this->assertSame('under-verification', self::repo()->get($c->id())->candidate->state()->value);
        }
    }

    public function test_add_never_overwrites_and_a_missing_candidate_is_not_found(): void
    {
        $c = self::fresh();
        self::repo()->add($c);
        try {
            self::repo()->add($c);
            $this->fail('added twice');
        } catch (\RuntimeException) {
            $this->addToAssertionCount(1);
        }
        $this->expectException(\OutOfBoundsException::class);
        self::repo()->get(CandidateId::fromString((string) Str::uuid()));
    }

    public function test_the_table_refuses_an_accepted_candidate_without_its_revision(): void
    {
        $this->expectException(QueryException::class);
        DB::insert("INSERT INTO refgeo_candidates (id, state, submitter_kind, submitter_ref, summary, evidence, recorded_by, verification_by, decided_by)
            VALUES (?, 'accepted', 'steward', ?, 's', '[]', ?, ?, ?)", [(string) Str::uuid(), md5('a'), md5('a'), md5('a'), md5('a')]);
    }

    public function test_one_revision_belongs_to_at_most_one_candidate(): void
    {
        $revision = RevisionId::fromString((string) Str::uuid());
        foreach ([self::fresh(), self::fresh()] as $i => $c) {
            $c->startVerification(self::p('steward'));
            $c->accept($revision, self::p('steward'));
            if ($i === 0) {
                self::repo()->add($c);
                continue;
            }
            $this->expectException(QueryException::class);   // Accept → ProposeRevision: a proposal belongs to one candidate
            self::repo()->add($c);
        }
    }

    public function test_a_corrupt_row_is_refused_on_reading(): void
    {
        $id = (string) Str::uuid();
        DB::insert("INSERT INTO refgeo_candidates (id, state, submitter_kind, submitter_ref, summary, evidence, recorded_by)
            VALUES (?, 'submitted', 'organisation', 'not-an-id', 's', '[]', ?)", [$id, md5('a')]);

        $this->expectException(\UnexpectedValueException::class);
        self::repo()->get(CandidateId::fromString($id));
    }
}
