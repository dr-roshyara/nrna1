<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-346 G-4a (v3.11 §6, §8, §15; completion §4; G-4 interpretation B-2, B-3, B-4, B-8): database guards of the
 * Reference Geography knowledge stream and roster, on the isolated cluster.
 * - one sequence row: never a second, never deleted;
 * - a publication (roster entry) is inserted only at the next position (roster version); the insert itself advances
 *   the sequence row, and nobody can move the counters directly, so gaps are structurally impossible (B-3, B-8);
 * - a publication's recorded_at is never below the previous reading and always after closed_through_time (B-4);
 *   closed_through_time only moves forward;
 * - publications, claims and roster entries are never updated or deleted; (revision, content version) is published
 *   at most once; a published version's claims cannot be added to; an Identity notice is recorded once.
 *
 * The harness keeps data between tests, so every write here appends to the stream exactly as the ledger will, and
 * every write that must not survive happens inside a refused (rolled back) transaction.
 */
final class RefgeoSchemaGuardsTest extends RefgeoPostgresTestCase
{
    /**
     * Refused BY A GUARD: a raised exception (P0001) or an integrity violation (class 23). Any other database error,
     * e.g. a missing table, is not a refusal and fails the test (no vacuous pass).
     */
    private function refused(callable $act, string $why): void
    {
        try {
            DB::transaction($act);
            $this->fail("must be refused: {$why}");
        } catch (QueryException $e) {
            $state = (string) ($e->errorInfo[0] ?? $e->getCode());
            $this->assertTrue($state === 'P0001' || str_starts_with($state, '23'), "refused by a guard ({$why}), not by SQLSTATE {$state}: " . $e->getMessage());
        }
    }

    private static function row(): object
    {
        return DB::selectOne('SELECT last_position, last_roster_version, closed_through_time, last_recorded_at FROM refgeo_sequence');
    }

    private static function insertPublication(int $position, string $recordedAtSql = 'clock_timestamp()', ?string $revision = null, int $version = 1): void
    {
        DB::insert("INSERT INTO refgeo_publications (position, kind, revision_id, content_version, roster_version, governance_rule, recorded_at) VALUES (?, 'revision', ?, ?, ?, 'era-a-sole-publication', {$recordedAtSql})",
            [$position, $revision ?? (string) Str::uuid(), $version, (int) self::row()->last_roster_version]);
    }

    /** Appends one publication the way the ledger does: at the next position, with the clock read now. */
    private static function publish(?string $revision = null, int $version = 1): int
    {
        return DB::transaction(static function () use ($revision, $version): int {
            $next = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence FOR UPDATE')->last_position + 1;
            self::insertPublication($next, 'clock_timestamp()', $revision, $version);

            return $next;
        });
    }

    private static function rosterEntry(array $values = []): int
    {
        return DB::transaction(static function () use ($values): int {
            $v = (int) DB::selectOne('SELECT last_roster_version FROM refgeo_sequence FOR UPDATE')->last_roster_version + 1;
            DB::table('refgeo_roster_entries')->insert(['roster_version' => $v, 'kind' => 'steward-appointed', 'subject' => str_repeat('a', 32), 'by_person' => str_repeat('0', 32), ...$values]);

            return $v;
        });
    }

    private static function claim(string $revision, int $version, string $id): array
    {
        return ['claim_id' => $id, 'revision_id' => $revision, 'content_version' => $version, 'kind' => 'EXISTENCE', 'slot_key' => 'EXISTENCE|unit=36:' . $revision . '|',
            'value' => 'exists', 'valid_from' => '2016-01-01', 'valid_to' => null, 'basis_kind' => 'instrument', 'basis_reference' => 'ev-1',
            'before_image' => '[["2016-01-01",null,"absent",null]]'];   // G-4e: every frozen claim carries its before-image
    }

    public function test_there_is_exactly_one_sequence_row_and_nobody_moves_its_counters_directly(): void
    {
        $this->assertSame(1, (int) DB::selectOne('SELECT count(*) AS n FROM refgeo_sequence')->n);
        $this->refused(fn () => DB::statement('INSERT INTO refgeo_sequence (id) VALUES (2)'), 'a second row');
        $this->refused(fn () => DB::statement('DELETE FROM refgeo_sequence'), 'deleting the row');
        $this->refused(fn () => DB::statement('UPDATE refgeo_sequence SET last_position = last_position + 1'), 'advancing the position directly (a gap)');
        $this->refused(fn () => DB::statement('UPDATE refgeo_sequence SET last_roster_version = last_roster_version + 1'), 'advancing the roster version directly');
        $this->refused(fn () => DB::statement('UPDATE refgeo_sequence SET last_recorded_at = clock_timestamp()'), 'moving the last reading directly');
    }

    public function test_a_publication_is_appended_only_at_the_next_position_advances_the_row_and_is_never_changed(): void
    {
        $before = (int) self::row()->last_position;
        $p = self::publish();

        $this->assertSame($before + 1, $p);
        $this->assertSame($p, (int) self::row()->last_position, 'the insert advanced the row');
        $this->refused(fn () => DB::statement('UPDATE refgeo_publications SET governance_rule = ? WHERE position = ?', ['non-structural', $p]), 'update');
        $this->refused(fn () => DB::statement('DELETE FROM refgeo_publications WHERE position = ?', [$p]), 'delete');
        $this->refused(fn () => self::insertPublication($p + 5), 'skipping positions');
        $this->refused(fn () => self::insertPublication($p), 'reusing a position');
    }

    public function test_a_revision_version_is_published_at_most_once(): void
    {
        $revision = (string) Str::uuid();
        self::publish($revision, 1);

        $this->refused(fn () => self::publish($revision, 1), 'the same (revision, content version) twice');
        $this->assertSame(1, DB::table('refgeo_publications')->where('revision_id', $revision)->count(), 'still one');
        self::publish($revision, 2);
        $this->assertSame(2, DB::table('refgeo_publications')->where('revision_id', $revision)->count());
    }

    public function test_recorded_at_never_goes_below_the_previous_reading_or_to_or_below_the_closed_boundary(): void
    {
        self::publish();
        $next = (int) self::row()->last_position + 1;

        $this->refused(fn () => self::insertPublication($next, "(SELECT last_recorded_at FROM refgeo_sequence) - interval '1 microsecond'"), 'a reading below the previous one');
        $this->refused(function () use ($next): void {
            DB::statement('UPDATE refgeo_sequence SET closed_through_time = clock_timestamp()');   // rolled back with the refusal
            self::insertPublication($next, '(SELECT closed_through_time FROM refgeo_sequence)');
        }, 'a reading at W');
        $this->assertSame($next - 1, (int) self::row()->last_position, 'nothing was appended');

        // W at the last reading (in the past, so harmless to later tests), then: never back, never into the future
        DB::statement('UPDATE refgeo_sequence SET closed_through_time = greatest(coalesce(closed_through_time, last_recorded_at), last_recorded_at)');
        $this->refused(fn () => DB::statement("UPDATE refgeo_sequence SET closed_through_time = closed_through_time - interval '1 second'"), 'W moving back');
        $this->refused(fn () => DB::statement("UPDATE refgeo_sequence SET closed_through_time = clock_timestamp() + interval '1 hour'"), 'W in the future (v3.11 §6: a close never confirms a future τ)');
    }

    public function test_claims_are_immutable_and_a_published_versions_claims_cannot_be_added_to(): void
    {
        $revision = (string) Str::uuid();
        DB::table('refgeo_claims')->insert(self::claim($revision, 1, (string) Str::uuid()));

        $this->refused(fn () => DB::statement('UPDATE refgeo_claims SET value = ? WHERE revision_id = ?', ['changed', $revision]), 'update');
        $this->refused(fn () => DB::statement('DELETE FROM refgeo_claims WHERE revision_id = ?', [$revision]), 'delete');

        self::publish($revision, 1);
        $this->refused(fn () => DB::table('refgeo_claims')->insert(self::claim($revision, 1, (string) Str::uuid())), 'a claim added to a published version');
    }

    public function test_roster_entries_are_appended_at_the_next_version_never_changed_and_notices_are_recorded_once(): void
    {
        $v = self::rosterEntry();

        $this->assertSame($v, (int) self::row()->last_roster_version, 'the insert advanced the row');
        $this->refused(fn () => DB::statement('UPDATE refgeo_roster_entries SET kind = ? WHERE roster_version = ?', ['steward-ended', $v]), 'update');
        $this->refused(fn () => DB::statement('DELETE FROM refgeo_roster_entries WHERE roster_version = ?', [$v]), 'delete');
        $this->refused(fn () => DB::table('refgeo_roster_entries')->insert(['roster_version' => $v + 3, 'kind' => 'steward-appointed', 'subject' => str_repeat('a', 32), 'by_person' => str_repeat('0', 32)]), 'skipping versions');

        $merge = 'm-' . Str::random(8);
        $notice = ['kind' => 'persons-merged', 'subject' => null, 'by_person' => null, 'merge_id' => $merge, 'merged' => json_encode([str_repeat('a', 32), str_repeat('b', 32)])];
        self::rosterEntry($notice);
        $this->refused(fn () => self::rosterEntry($notice), 'the same notice twice');
    }
}
