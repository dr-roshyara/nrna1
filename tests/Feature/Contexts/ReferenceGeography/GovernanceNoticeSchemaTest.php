<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-346 G-6c-1 (design revision 3 §11, §17): the database's share of the D-90 notices.
 * - row shapes: a revision row carries no notice field; a finding, confirmation or closure carries exactly its own
 *   fields and no revision; references point backwards, a finding at a revision row, a confirmation or closure at a
 *   finding, a correction at a revision row;
 * - one confirmation and one closure per finding; notices immutable and gap-free like every publication row (G-4);
 * - refgeo_publication_persons: append-only, immutable; backfilled (idempotently) from the second-person
 *   publications that predate it: every approver of the published version, and the revision's excluded persons.
 * Rows are written directly here ON PURPOSE: this tests what the database refuses whoever writes.
 */
final class GovernanceNoticeSchemaTest extends RefgeoPostgresTestCase
{
    use PublishingFixtures;

    /** @param array<string, mixed> $fields */
    private static function insertRow(array $fields): int
    {
        return DB::transaction(static function () use ($fields): int {
            $position = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1 FOR UPDATE')->last_position + 1;
            DB::table('refgeo_publications')->insert($fields + [
                'position' => $position, 'roster_version' => (int) DB::selectOne('SELECT last_roster_version AS v FROM refgeo_sequence')->v,
                'governance_rule' => 'test-fixture', 'recorded_at' => DB::raw('clock_timestamp()'),
            ]);

            return $position;
        });
    }

    private static function revisionRow(): int
    {
        return self::insertRow(['kind' => 'revision', 'revision_id' => (string) Str::uuid(), 'content_version' => 1]);
    }

    private static function finding(int $concerns): int
    {
        return self::insertRow(['kind' => 'notice', 'notice_kind' => 'finding', 'concerns_position' => $concerns, 'merge_basis' => '["m1"]']);
    }

    /**
     * Each refusal must come from the INTENDED constraint or trigger, not from some unrelated failure (a missing column
     * value would also raise QueryException and make the test vacuous).
     */
    private const REFUSED_BY = [
        // The ONE case two constraints jointly guard: every notice kind needs concerns_position, which a revision row
        // may not have. Exactly one of these two names must appear (PostgreSQL does not promise which it reports).
        'a revision row with a notice kind' => ['refgeo_publications_notice_iff_kind', 'refgeo_publications_revision_shape'],
        'a notice without a notice kind' => 'refgeo_publications_notice_iff_kind',         // only this one applies
        'a reference to a missing position' => 'refgeo_publications_concerns_position_fkey',
        'a correction of a missing position' => 'refgeo_publications_corrects_position_fkey',
        'a closure after a confirmation' => 'refgeo_publications_one_resolution',
        'a confirmation after a closure' => 'refgeo_publications_one_resolution',
        'a duplicate person association' => 'refgeo_publication_persons_pkey',
        'a revision row with a merge basis' => 'refgeo_publications_revision_shape',
        'a finding concerning nothing' => 'refgeo_publications_notice_shape',
        'a finding without its merge basis' => 'refgeo_publications_finding_shape',
        'a notice carrying a revision' => 'refgeo_publications_check',
        'a confirmation without its digest' => 'refgeo_publications_confirmation_shape',
        'a closure without what ended it' => 'refgeo_publications_closure_shape',
        'a closure carrying a confirmer' => 'refgeo_publications_closure_shape',
        'a notice carrying a correction' => 'refgeo_publications_notice_shape',
        'an unknown notice kind' => 'refgeo_publications_notice_kind_known',
        'a reference forward (or to nothing)' => 'refgeo_publications_notice_shape',
        'a finding about a notice' => 'a finding concerns a revision publication',
        'a confirmation of a revision row' => 'a confirmation concerns a finding',
        'a closure of a revision row' => 'a closure concerns a finding',
        'a correction of a notice' => 'a correction corrects a revision publication',
        // a finding is resolved ONCE (design §8): one resolving notice (confirmation or closure) per finding
        'a second confirmation' => 'refgeo_publications_one_resolution',
        'a second closure' => 'refgeo_publications_one_resolution',
    ];

    /** @param array<string, mixed> $fields */
    private function assertRefused(array $fields, string $why): void
    {
        try {
            self::insertRow($fields);
            $this->fail("accepted: {$why}");
        } catch (QueryException $e) {
            $expected = (array) self::REFUSED_BY[$why];
            $named = array_values(array_filter($expected, static fn (string $n): bool => str_contains($e->getMessage(), $n)));
            $this->assertCount(1, $named, "refused, but not for the intended reason: {$why}: " . $e->getMessage());
        }
    }

    public function test_each_notice_kind_is_accepted_in_its_own_shape(): void
    {
        $p = self::revisionRow();
        $f = self::finding($p);
        $c = self::insertRow(['kind' => 'notice', 'notice_kind' => 'confirmation', 'concerns_position' => $f, 'confirmed_by' => md5('c'), 'confirmed_digest' => str_repeat('d', 64)]);
        $f2 = self::finding($p);
        $x = self::insertRow(['kind' => 'notice', 'notice_kind' => 'closure', 'concerns_position' => $f2, 'ended_by' => 'reversal:m1']);
        $q = self::insertRow(['kind' => 'revision', 'revision_id' => (string) Str::uuid(), 'content_version' => 1, 'corrects_position' => $p]);

        $this->assertSame([$p + 1, $p + 2, $p + 3, $p + 4, $p + 5], [$f, $c, $f2, $x, $q], 'notices take gap-free positions like revisions');
    }

    public function test_row_shapes_the_design_forbids_are_refused(): void
    {
        $p = self::revisionRow();
        $f = self::finding($p);
        $base = ['kind' => 'notice', 'concerns_position' => $f];

        // a VALID closure shape pointing at a real finding (so the closure CHECK and the reference trigger pass): only the
        // discriminator and the revision-shape rules can refuse it
        $this->assertRefused(['kind' => 'revision', 'revision_id' => (string) Str::uuid(), 'content_version' => 1, 'notice_kind' => 'closure',
            'concerns_position' => $f, 'ended_by' => 'x'], 'a revision row with a notice kind');
        $this->assertRefused(['kind' => 'notice'], 'a notice without a notice kind');
        $this->assertRefused(['kind' => 'revision', 'revision_id' => (string) Str::uuid(), 'content_version' => 1, 'merge_basis' => '[]'], 'a revision row with a merge basis');
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'finding', 'merge_basis' => '["m"]'], 'a finding concerning nothing');
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'finding', 'concerns_position' => $p], 'a finding without its merge basis');
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'finding', 'concerns_position' => $p, 'merge_basis' => '["m"]', 'revision_id' => (string) Str::uuid(), 'content_version' => 1], 'a notice carrying a revision');
        $this->assertRefused($base + ['notice_kind' => 'confirmation', 'confirmed_by' => md5('c')], 'a confirmation without its digest');
        $this->assertRefused($base + ['notice_kind' => 'closure'], 'a closure without what ended it');
        $this->assertRefused($base + ['notice_kind' => 'closure', 'ended_by' => 'x', 'confirmed_by' => md5('c')], 'a closure carrying a confirmer');
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'finding', 'concerns_position' => $p, 'merge_basis' => '["m"]', 'corrects_position' => $p], 'a notice carrying a correction');
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'retraction', 'concerns_position' => $p, 'merge_basis' => '["m"]'], 'an unknown notice kind');
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'finding', 'concerns_position' => $f + 10, 'merge_basis' => '["m"]'], 'a reference forward (or to nothing)');
    }

    public function test_references_point_at_the_right_kind_of_row(): void
    {
        $p = self::revisionRow();
        $f = self::finding($p);

        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'finding', 'concerns_position' => $f, 'merge_basis' => '["m"]'], 'a finding about a notice');
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'confirmation', 'concerns_position' => $p, 'confirmed_by' => md5('c'), 'confirmed_digest' => 'd'], 'a confirmation of a revision row');
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'closure', 'concerns_position' => $p, 'ended_by' => 'x'], 'a closure of a revision row');
        $this->assertRefused(['kind' => 'revision', 'revision_id' => (string) Str::uuid(), 'content_version' => 1, 'corrects_position' => $f], 'a correction of a notice');
    }

    public function test_a_reference_to_a_missing_position_is_refused_by_the_foreign_key(): void
    {
        self::revisionRow();
        // 0 < position, so the backwards CHECK passes: only the foreign key can refuse it (positions start at 1)
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'finding', 'concerns_position' => 0, 'merge_basis' => '["m"]'], 'a reference to a missing position');
        $this->assertRefused(['kind' => 'revision', 'revision_id' => (string) Str::uuid(), 'content_version' => 1, 'corrects_position' => 0], 'a correction of a missing position');
    }

    public function test_a_finding_is_resolved_once_whatever_the_resolution(): void
    {
        $f = self::finding(self::revisionRow());
        self::insertRow(['kind' => 'notice', 'notice_kind' => 'confirmation', 'concerns_position' => $f, 'confirmed_by' => md5('c'), 'confirmed_digest' => 'd']);
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'closure', 'concerns_position' => $f, 'ended_by' => 'reversal:m1'], 'a closure after a confirmation');

        $g = self::finding(self::revisionRow());
        self::insertRow(['kind' => 'notice', 'notice_kind' => 'closure', 'concerns_position' => $g, 'ended_by' => 'reversal:m1']);
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'confirmation', 'concerns_position' => $g, 'confirmed_by' => md5('c'), 'confirmed_digest' => 'd'], 'a confirmation after a closure');
    }

    /**
     * Migration safety (the Principal review, 2026-11-14): if a database already holds a finding resolved twice (one
     * confirmation and one closure, which the earlier separate indexes allowed), the one-resolution migration must STOP
     * and name the finding, changing nothing; governance history is never rewritten. Simulated inside a transaction that
     * is rolled back (PostgreSQL DDL is transactional), so the cluster is left as it was.
     */
    public function test_the_one_resolution_migration_stops_and_names_findings_already_resolved_twice(): void
    {
        $migration = require base_path('database/migrations/2026_11_14_110000_refgeo_findings_resolved_once.php');
        DB::beginTransaction();
        try {
            $migration->down();   // the earlier state: separate indexes
            $f = self::finding(self::revisionRow());
            self::insertRow(['kind' => 'notice', 'notice_kind' => 'confirmation', 'concerns_position' => $f, 'confirmed_by' => md5('c'), 'confirmed_digest' => 'd']);
            self::insertRow(['kind' => 'notice', 'notice_kind' => 'closure', 'concerns_position' => $f, 'ended_by' => 'reversal:m1']);
            try {
                $migration->up();
                $this->fail('the migration accepted a finding resolved twice');
            } catch (\RuntimeException $e) {
                $this->assertStringContainsString("finding(s) resolved more than once: {$f}", $e->getMessage());
                $this->assertSame(2, (int) DB::selectOne("SELECT count(*) AS n FROM refgeo_publications WHERE concerns_position = ? AND notice_kind IN ('confirmation', 'closure')", [$f])->n, 'nothing rewritten');
            }
        } finally {
            DB::rollBack();
        }
        $this->assertNotNull(DB::selectOne("SELECT 1 AS x FROM pg_indexes WHERE indexname = 'refgeo_publications_one_resolution'"), 'the real schema is untouched');
    }

    public function test_a_person_association_is_recorded_once(): void
    {
        $p = self::revisionRow();
        DB::table('refgeo_publication_persons')->insert(['position' => $p, 'person' => md5('x'), 'role' => 'excluded']);

        try {
            DB::table('refgeo_publication_persons')->insert(['position' => $p, 'person' => md5('x'), 'role' => 'excluded']);
            $this->fail('a duplicate association was stored');
        } catch (QueryException $e) {
            $this->assertStringContainsString(self::REFUSED_BY['a duplicate person association'], $e->getMessage());
        }
    }

    public function test_one_confirmation_and_one_closure_per_finding(): void
    {
        $f = self::finding(self::revisionRow());
        self::insertRow(['kind' => 'notice', 'notice_kind' => 'confirmation', 'concerns_position' => $f, 'confirmed_by' => md5('c'), 'confirmed_digest' => 'd']);
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'confirmation', 'concerns_position' => $f, 'confirmed_by' => md5('e'), 'confirmed_digest' => 'd'], 'a second confirmation');

        $g = self::finding(self::revisionRow());
        self::insertRow(['kind' => 'notice', 'notice_kind' => 'closure', 'concerns_position' => $g, 'ended_by' => 'reversal:m1']);
        $this->assertRefused(['kind' => 'notice', 'notice_kind' => 'closure', 'concerns_position' => $g, 'ended_by' => 'reversal:m2'], 'a second closure');
    }

    public function test_notices_are_immutable_and_positions_stay_gap_free(): void
    {
        $f = self::finding(self::revisionRow());
        foreach (['UPDATE refgeo_publications SET merge_basis = \'[]\' WHERE position = ?', 'DELETE FROM refgeo_publications WHERE position = ?'] as $sql) {
            try {
                DB::statement($sql, [$f]);
                $this->fail("a notice row changed: {$sql}");
            } catch (QueryException) {
                $this->addToAssertionCount(1);
            }
        }
        $this->expectException(QueryException::class);
        DB::transaction(static fn () => DB::table('refgeo_publications')->insert(['position' => $f + 2, 'kind' => 'notice', 'notice_kind' => 'finding', 'concerns_position' => $f - 1,
            'merge_basis' => '["m"]', 'roster_version' => 0, 'governance_rule' => 'x', 'recorded_at' => DB::raw('clock_timestamp()')]));
    }

    public function test_the_person_index_is_append_only_and_backfilled_from_second_person_publications(): void
    {
        $id = self::revision('g6c-assessor', 'g6c-approver');
        self::publisher('g6c-approver')->publish($id);
        $p = (int) DB::selectOne('SELECT position FROM refgeo_publications WHERE revision_id = ?', [$id->value()])->position;
        $this->assertSame('second-person-approved', DB::selectOne('SELECT governance_rule AS g FROM refgeo_publications WHERE position = ?', [$p])->g);
        // G-6c-3: the ledger indexes the publication in its own transaction; the backfill (for publications made
        // before G-6c) then adds nothing: the two writers agree, and the backfill is idempotent
        $indexed = static fn (): array => DB::select('SELECT trim(person) AS person, role FROM refgeo_publication_persons WHERE position = ? ORDER BY role, person', [$p]);
        $byLedger = $indexed();
        $this->assertCount(2, $byLedger, 'indexed by the ledger at publication: the approver and the excluded assessor');

        $migration = require base_path('database/migrations/2026_11_14_100000_add_refgeo_governance_notices.php');
        $migration->backfillPublicationPersons();
        $migration->backfillPublicationPersons();   // idempotent
        $this->assertEquals($byLedger, $indexed(), 'the backfill agrees with the ledger and adds nothing');

        $rows = DB::select('SELECT trim(person) AS person, role FROM refgeo_publication_persons WHERE position = ? ORDER BY role, person', [$p]);
        $approvers = array_values(array_map(static fn ($r) => $r->person, array_filter($rows, static fn ($r) => $r->role === 'approver')));
        $excluded = array_values(array_map(static fn ($r) => $r->person, array_filter($rows, static fn ($r) => $r->role === 'excluded')));
        $this->assertSame([self::p('g6c-approver')->value()], $approvers);
        $this->assertContains(self::p('g6c-assessor')->value(), $excluded, 'the preparer/assessor is excluded(r)');

        foreach (['UPDATE refgeo_publication_persons SET role = \'confirmer\' WHERE position = ?', 'DELETE FROM refgeo_publication_persons WHERE position = ?'] as $sql) {
            try {
                DB::statement($sql, [$p]);
                $this->fail("an index row changed: {$sql}");
            } catch (QueryException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}
