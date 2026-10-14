<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\DemoVoterSlug;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Services\TenantContext;
use App\Services\VoterStepTrackingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * Invariant: recording the same step for the same voter slug must not create a duplicate just because the
 * current tenant context differs from the slug's organisation. The "already completed?" lookup is therefore
 * pinned to the slug's own organisation_id (authoritative), not to the ambient tenant context.
 *
 * Database-free: SQL is compiled, never executed; `pgsql` points at a non-existent socket directory and every
 * test asserts that no query ran. The real-database proof is
 * tests/Isolated/Pbdigit274/Database/VoterSlugStepTenantIsolationTest.php (after G5).
 */
final class VoterStepTrackingServiceTenantTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;

    private const ORG_A = 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa';
    private const ORG_B = 'bbbbbbbb-bbbb-4bbb-8bbb-bbbbbbbbbbbb';

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => '/nonexistent-step-tracking-test-socket-dir',
            'database.connections.pgsql.url' => null,
        ]);
        DB::purge('pgsql');
        DB::listen(function () {
            $this->queries++;
        });
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    private function slugOf(string $class, string $organisationId): VoterSlug|DemoVoterSlug
    {
        return (new $class())->forceFill(['id' => 'cccccccc-cccc-4ccc-8ccc-cccccccccccc', 'organisation_id' => $organisationId]);
    }

    private function election(): Election
    {
        return (new Election())->forceFill(['id' => 'eeeeeeee-eeee-4eee-8eee-eeeeeeeeeeee']);
    }

    public function test_the_step_lookup_is_pinned_to_the_slugs_organisation_not_the_current_tenant(): void
    {
        TenantContext::set(self::ORG_A);
        $query = (new VoterStepTrackingService())->stepQuery($this->slugOf(VoterSlug::class, self::ORG_B), $this->election());

        $this->assertStringContainsString('"voter_slug_steps"."organisation_id" = ?', $query->toSql());
        $this->assertContains(self::ORG_B, $query->getBindings());
        $this->assertNotContains(self::ORG_A, $query->getBindings(), 'the ambient tenant must not decide which steps exist');
        $this->assertSame(0, $this->queries);
    }

    public function test_the_lookup_stays_narrow_slug_election_and_organisation(): void
    {
        TenantContext::set(self::ORG_A);
        $query = (new VoterStepTrackingService())->stepQuery($this->slugOf(VoterSlug::class, self::ORG_A), $this->election());
        $sql = $query->toSql();

        $this->assertStringContainsString('"voter_slug_id" = ?', $sql);
        $this->assertStringContainsString('"election_id" = ?', $sql);
        $this->assertSame(1, substr_count($sql, 'organisation_id'), 'exactly one organisation filter: no broad bypass, no double filter');
        $this->assertSame(0, $this->queries);
    }

    public function test_demo_slugs_use_the_demo_step_table_with_the_same_pinning(): void
    {
        TenantContext::set(self::ORG_A);
        $query = (new VoterStepTrackingService())->stepQuery($this->slugOf(DemoVoterSlug::class, self::ORG_B), $this->election());

        $this->assertStringContainsString('"demo_voter_slug_steps"."organisation_id" = ?', $query->toSql());
        $this->assertContains(self::ORG_B, $query->getBindings());
        $this->assertNotContains(self::ORG_A, $query->getBindings());
        $this->assertSame(0, $this->queries);
    }

    /**
     * Runs a reader on a service that records the query its pinned lookup (stepQuery) built. The reader then
     * tries to execute it and fails on the dead socket (no real database is reachable); the recorded builder,
     * including the reader's own where / order clauses, is what the test inspects. If the reader bypasses
     * stepQuery, nothing is recorded and the test fails.
     */
    private function capturedLookup(callable $read, VoterSlug|DemoVoterSlug $slug): Builder
    {
        $service = new class () extends VoterStepTrackingService {
            public ?Builder $captured = null;

            public function stepQuery($voterSlug, Election $election): Builder
            {
                return $this->captured = parent::stepQuery($voterSlug, $election);
            }
        };
        try {
            $read($service, $slug, $this->election());
        } catch (\Illuminate\Database\QueryException|\PDOException) {
            // expected: execution is refused by the dead socket
        }
        $this->assertNotNull($service->captured, 'the reader must use the pinned lookup (stepQuery)');

        return $service->captured;
    }

    public static function readers(): array
    {
        return [
            'getHighestCompletedStep' => [fn ($s, $slug, $e) => $s->getHighestCompletedStep($slug, $e)],
            'hasCompletedStep'        => [fn ($s, $slug, $e) => $s->hasCompletedStep($slug, $e, 3)],
            'getCompletedSteps'       => [fn ($s, $slug, $e) => $s->getCompletedSteps($slug, $e)],
            'getNextStep'             => [fn ($s, $slug, $e) => $s->getNextStep($slug, $e)],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('readers')]
    public function test_every_step_reader_is_pinned_to_the_slugs_organisation(callable $read): void
    {
        TenantContext::set(self::ORG_A);
        $query = $this->capturedLookup($read, $this->slugOf(VoterSlug::class, self::ORG_B));

        $this->assertStringContainsString('"voter_slug_steps"."organisation_id" = ?', $query->toSql());
        $this->assertSame(1, substr_count($query->toSql(), 'organisation_id'));
        $this->assertContains(self::ORG_B, $query->getBindings());
        $this->assertNotContains(self::ORG_A, $query->getBindings(), 'the ambient tenant must not decide which steps a voter has');
    }

    public function test_demo_step_readers_use_the_demo_table_with_the_same_pinning(): void
    {
        TenantContext::set(self::ORG_A);
        $query = $this->capturedLookup(fn ($s, $slug, $e) => $s->getHighestCompletedStep($slug, $e), $this->slugOf(DemoVoterSlug::class, self::ORG_B));

        $this->assertStringContainsString('"demo_voter_slug_steps"."organisation_id" = ?', $query->toSql());
        $this->assertContains(self::ORG_B, $query->getBindings());
        $this->assertNotContains(self::ORG_A, $query->getBindings());
    }

    public function test_has_completed_step_keeps_its_step_bound(): void
    {
        TenantContext::set(self::ORG_A);
        $query = $this->capturedLookup(fn ($s, $slug, $e) => $s->hasCompletedStep($slug, $e, 3), $this->slugOf(VoterSlug::class, self::ORG_A));

        $this->assertStringContainsString('"step" <= ?', $query->toSql());
        $this->assertContains(3, $query->getBindings());
    }

    public function test_completed_steps_stay_ordered_by_step(): void
    {
        TenantContext::set(self::ORG_A);
        $query = $this->capturedLookup(fn ($s, $slug, $e) => $s->getCompletedSteps($slug, $e), $this->slugOf(VoterSlug::class, self::ORG_A));

        $this->assertMatchesRegularExpression('/order by "step" asc/i', $query->toSql());
    }

    public function test_complete_step_checks_for_an_existing_step_through_the_pinned_lookup(): void
    {
        $service = new class () extends VoterStepTrackingService {
            public function stepQuery($voterSlug, Election $election): Builder
            {
                throw new RuntimeException('pinned lookup used');
            }
        };

        $this->expectExceptionMessage('pinned lookup used');
        try {
            $service->completeStep($this->slugOf(VoterSlug::class, self::ORG_B), $this->election(), 1);
        } finally {
            $this->assertSame(0, $this->queries, 'nothing may be written before the duplicate check');
        }
    }
}
