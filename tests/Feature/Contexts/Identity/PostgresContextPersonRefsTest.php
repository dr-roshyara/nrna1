<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Identity;

use App\Contexts\Identity\Domain\AccountId;
use App\Contexts\Identity\Domain\ContextPersonRef;
use App\Contexts\Identity\Domain\PersonId;
use App\Contexts\Identity\Domain\PlatformContext;
use App\Contexts\Identity\Infrastructure\PostgresAccountDirectory;
use App\Contexts\Identity\Infrastructure\PostgresContextPersonRefs;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;

/**
 * PBDIGIT-346 G-0 on PostgreSQL (D-225 IG-1 … IG-3, IG-P1 … IG-P4; D-226 §5a).
 * - A legacy users row gets one Identity Person, lazily, the first time it is needed; never two, even under a race.
 * - "Active" for a legacy row = not soft-deleted and e-mail verified; otherwise no Person and no reference.
 * - One ContextPersonRef per (Person, context): issued once, stable, unique, opaque, never changed or deleted.
 * - A merged Person answers with its survivor (fixture: Identity's merge procedure is deferred).
 */
final class PostgresContextPersonRefsTest extends MembershipPostgresTestCase
{
    private function user(bool $verified = true, bool $deleted = false): AccountId
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 'g0', 'slug' => 'g0-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $id = (string) Str::uuid();
        DB::table('users')->insert([
            'id' => $id, 'organisation_id' => $org, 'name' => 'G0 user', 'region' => 'x', 'email' => "g0-{$id}@example.test",
            'password' => 'x', 'email_verified_at' => $verified ? now() : null, 'deleted_at' => $deleted ? now() : null,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return AccountId::fromString($id);
    }

    /** The harness migrates once per run and keeps data between tests, so every count is scoped to this test's ids. */
    private static function personsOf(AccountId $account): int
    {
        return DB::table('identity_account_persons')->where('account_id', $account->value())->count();
    }

    private static function refsOf(PersonId $person): int
    {
        return DB::table('identity_context_person_refs')->where('person_id', $person->value())->count();
    }

    private static function orphanPersons(): int
    {
        return (int) DB::selectOne('SELECT count(*) AS n FROM identity_persons p WHERE NOT EXISTS (SELECT 1 FROM identity_account_persons a WHERE a.person_id = p.id)')->n;
    }

    private static function directory(): PostgresAccountDirectory
    {
        return new PostgresAccountDirectory(DB::connection());
    }

    private static function refs(?\Closure $generate = null): PostgresContextPersonRefs
    {
        return new PostgresContextPersonRefs(DB::connection(), $generate);
    }

    public function test_an_active_users_row_gets_one_person_lazily_and_the_same_one_again(): void
    {
        $account = $this->user();
        $this->assertSame(0, self::personsOf($account), 'nothing exists before the first request');

        $first = self::directory()->activePersonOf($account);
        $again = self::directory()->activePersonOf($account);

        $this->assertNotNull($first);
        $this->assertTrue($first->equals($again));
        $this->assertSame(1, self::personsOf($account));
        $this->assertNotSame($account->value(), $first->value(), 'a Person is not the Account');
    }

    public function test_two_users_rows_get_two_persons(): void
    {
        $a = self::directory()->activePersonOf($this->user());
        $b = self::directory()->activePersonOf($this->user());

        $this->assertFalse($a->equals($b));
    }

    public function test_an_unverified_or_soft_deleted_users_row_is_not_active_and_gets_no_person(): void
    {
        $unverified = $this->user(verified: false);
        $deleted = $this->user(deleted: true);
        $unknown = AccountId::fromString((string) Str::uuid());

        $this->assertNull(self::directory()->activePersonOf($unverified));
        $this->assertNull(self::directory()->activePersonOf($deleted));
        $this->assertNull(self::directory()->activePersonOf($unknown), 'unknown account');
        $this->assertSame([0, 0, 0], [self::personsOf($unverified), self::personsOf($deleted), self::personsOf($unknown)]);
    }

    public function test_a_once_mapped_account_that_is_later_deleted_is_no_longer_active(): void
    {
        $account = $this->user();
        $person = self::directory()->activePersonOf($account);
        DB::table('users')->where('id', $account->value())->update(['deleted_at' => now()]);

        $this->assertNull(self::directory()->activePersonOf($account));
        $this->assertSame(1, self::personsOf($account), 'the mapping stays for accountability');
        $this->assertSame(1, DB::table('identity_persons')->where('id', $person->value())->count(), 'the Person stays for accountability');
    }

    public function test_one_reference_per_person_and_context_issued_once_and_stable(): void
    {
        $person = self::directory()->activePersonOf($this->user());

        $first = self::refs()->refFor($person, PlatformContext::ReferenceGeography);
        $again = self::refs()->refFor($person, PlatformContext::ReferenceGeography);

        $this->assertTrue($first->equals($again));
        $this->assertSame(1, self::refsOf($person));
    }

    public function test_two_persons_get_different_references_and_a_reference_is_opaque(): void
    {
        $account = $this->user();
        $p1 = self::directory()->activePersonOf($account);
        $p2 = self::directory()->activePersonOf($this->user());

        $r1 = self::refs()->refFor($p1, PlatformContext::ReferenceGeography);
        $r2 = self::refs()->refFor($p2, PlatformContext::ReferenceGeography);

        $this->assertFalse($r1->equals($r2));
        $plain = static fn (string $uuid): string => str_replace('-', '', $uuid);
        $this->assertNotSame($plain($p1->value()), $r1->value(), 'not the PersonId');
        $this->assertNotSame($plain($account->value()), $r1->value(), 'not the AccountId');
    }

    public function test_a_generated_collision_is_retried_never_reused(): void
    {
        $p1 = self::directory()->activePersonOf($this->user());
        $p2 = self::directory()->activePersonOf($this->user());
        $values = ['aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb'];
        $generate = static function () use (&$values): string { return array_shift($values); };

        $r1 = self::refs($generate)->refFor($p1, PlatformContext::ReferenceGeography);
        $r2 = self::refs($generate)->refFor($p2, PlatformContext::ReferenceGeography);

        $this->assertSame('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', $r1->value());
        $this->assertSame('bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb', $r2->value(), 'the colliding value was retried, not reused');
    }

    public function test_a_reference_can_never_be_changed_or_deleted(): void
    {
        $ref = self::refs()->refFor(self::directory()->activePersonOf($this->user()), PlatformContext::ReferenceGeography);

        foreach (["UPDATE identity_context_person_refs SET ref = 'cccccccccccccccccccccccccccccccc' WHERE ref = ?", 'DELETE FROM identity_context_person_refs WHERE ref = ?'] as $sql) {
            try {
                DB::statement($sql, [$ref->value()]);
                $this->fail("must be refused: {$sql}");
            } catch (QueryException) {
                $this->addToAssertionCount(1);
            }
        }
        $this->assertSame(1, DB::table('identity_context_person_refs')->where('ref', $ref->value())->count(), 'unchanged and still there');
    }

    public function test_a_merged_person_answers_with_its_survivor_and_the_survivors_reference(): void
    {
        $account = $this->user();
        $merged = self::directory()->activePersonOf($account);
        $survivor = self::directory()->activePersonOf($this->user());
        $survivorRef = self::refs()->refFor($survivor, PlatformContext::ReferenceGeography);
        // FIXTURE: Identity's merge procedure is deferred (P-I3); the state is set directly to test IG-2's rule.
        DB::table('identity_persons')->where('id', $merged->value())->update(['merged_into' => $survivor->value()]);

        $resolved = self::directory()->activePersonOf($account);

        $this->assertTrue($survivor->equals($resolved));
        $this->assertTrue($survivorRef->equals(self::refs()->refFor($resolved, PlatformContext::ReferenceGeography)));
    }

    public function test_concurrent_first_requests_create_one_person_and_one_reference(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $account = $this->user();
        $out = tempnam(sys_get_temp_dir(), 'g0race');
        $start = microtime(true) + 0.5;
        $pids = [];
        for ($i = 0; $i < 4; $i++) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                DB::purge();
                while (microtime(true) < $start) {
                    usleep(100);
                }
                $line = 'error';
                try {
                    $p = self::directory()->activePersonOf($account);
                    $line = $p->value() . ' ' . self::refs()->refFor($p, PlatformContext::ReferenceGeography)->value();
                } catch (\Throwable $e) {
                    $line = 'error ' . $e::class;
                }
                file_put_contents($out, $line . "\n", FILE_APPEND | LOCK_EX);
                posix_kill(posix_getpid(), SIGKILL);
            }
            $pids[] = $pid;
        }
        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status);
        }
        DB::reconnect();
        $lines = array_values(array_filter(explode("\n", (string) file_get_contents($out))));
        unlink($out);

        $this->assertCount(4, $lines);
        $this->assertCount(1, array_unique($lines), 'every racer saw the same Person and reference: ' . implode(' | ', $lines));
        $this->assertSame(1, self::personsOf($account));
        $this->assertSame(1, self::refsOf(PersonId::fromString(explode(' ', $lines[0])[0])));
        $this->assertSame(0, self::orphanPersons(), 'no racer left a Person without an account');
    }
}
