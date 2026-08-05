<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Adjudication;

use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessId;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessState;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * WP-2 KEYSTONE — one ACTIVE adjudication process per challenge, enforced at the
 * database seam.
 *
 * This is the INV-B1 two-seat pattern mirrored at the process side (EPIC-004E ·
 * EPIC-004K §11): the application guard states the business rule, the unique
 * index is the concurrency backstop that makes a race structurally impossible.
 * Precedent: `uniq_determination_per_challenge`.
 *
 * The constraint binds ACTIVE processes only — §6's opening guard reads "no
 * ACTIVE process exists for this challenge", and the ARB's horizon ruling returns
 * an expired challenge to Contestation, which may route it again (WP-5's path).
 * A challenge may therefore accumulate more than one process over time, but never
 * two at once. (Finding F-T1 of the WP-2 traceability review.)
 *
 * Traceability: EPIC-004K §6, §11 · EPIC-004E INV-B1 · roadmap §WP-2 keystones ·
 * Q-2 horizon-expiry ruling.
 */
final class AdjudicationProcessUniquenessTest extends TestCase
{
    use RefreshDatabase;

    private string $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        $org = Organisation::create([
            'name' => 'APM Uniqueness Test Org',
            'slug' => 'apm-uniqueness-test-org',
            'type' => 'tenant',
            'is_default' => false,
        ]);
        $this->tenantId = (string) $org->id;
        TenantContext::set($this->tenantId);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    private function store(): AdjudicationProcessStore
    {
        return $this->app->make(AdjudicationProcessStore::class);
    }

    private function at(string $t = '2026-07-30T10:00:00+00:00'): DateTimeImmutable
    {
        return new DateTimeImmutable($t);
    }

    private function opened(string $id, string $challenge): AdjudicationProcessState
    {
        return AdjudicationProcessState::open(
            AdjudicationProcessId::fromString($id),
            ChallengeRef::fromString($challenge),
            $this->at(),
        );
    }

    public function test_an_opened_process_is_persisted_and_loadable_for_reaction(): void
    {
        $this->store()->save($this->opened('apm-1', 'ch-1'));

        $loaded = $this->store()->activeForChallenge(ChallengeRef::fromString('ch-1'));

        $this->assertNotNull($loaded);
        $this->assertSame('apm-1', $loaded->id()->toString());
        $this->assertSame(AdjudicationProcessStatus::Opened, $loaded->status());
        $this->assertSame(1, DB::table('adjudication_processes')->where('organisation_id', $this->tenantId)->count());
    }

    // ── KEYSTONE: a second ACTIVE process for one challenge is impossible ────

    public function test_a_second_active_process_for_the_same_challenge_is_rejected_by_the_database(): void
    {
        $this->store()->save($this->opened('apm-1', 'ch-race'));

        // The concurrency backstop: even if an application guard were bypassed
        // (two racing requests both seeing "no active process"), the database
        // must refuse the second row.
        $this->expectException(\Illuminate\Database\QueryException::class);

        $this->store()->save($this->opened('apm-2', 'ch-race'));
    }

    // ── F-T1: the backstop is scoped EXACTLY like the guard it backs ────────

    public function test_the_uniqueness_constraint_does_not_forbid_what_the_guard_permits(): void
    {
        // SCOPE — what this test does and does not claim (Business Assumption
        // Review, plan §13.4):
        //   It DOES assert WP-2's storage obligation: the unique-index backstop is
        //   scoped exactly like §6's guard, which reads "no ACTIVE process exists"
        //   (and PM-1: "open exactly one ACTIVE process per challenge"). A backstop
        //   stricter than its guard would fire on cases the guard permits.
        //   It does NOT assert that an expired challenge is ever re-routed. Whether
        //   Contestation re-routes is Contestation's business (WP-5); the ARB ruled
        //   only that expiry RETURNS the challenge there. WP-2 asserts no business
        //   behaviour it does not own.
        $this->store()->save(
            $this->opened('apm-1', 'ch-reopen')->expire($this->at('2026-09-28T10:00:00+00:00'))
        );

        $this->store()->save($this->opened('apm-2', 'ch-reopen'));

        $active = $this->store()->activeForChallenge(ChallengeRef::fromString('ch-reopen'));
        $this->assertNotNull($active);
        $this->assertSame('apm-2', $active->id()->toString());
        $this->assertSame(2, DB::table('adjudication_processes')->where('organisation_id', $this->tenantId)->count());
    }

    public function test_a_terminal_process_is_not_returned_as_active(): void
    {
        $this->store()->save($this->opened('apm-1', 'ch-done')->expire($this->at('2026-09-28T10:00:00+00:00')));

        $this->assertNull($this->store()->activeForChallenge(ChallengeRef::fromString('ch-done')));
    }

    public function test_processes_are_tenant_scoped(): void
    {
        $this->store()->save($this->opened('apm-1', 'ch-tenant'));

        $other = Organisation::create([
            'name' => 'Other Org',
            'slug' => 'apm-other-org',
            'type' => 'tenant',
            'is_default' => false,
        ]);
        TenantContext::set((string) $other->id);

        $this->assertNull(
            $this->store()->activeForChallenge(ChallengeRef::fromString('ch-tenant')),
            'A process must never be visible outside its organisation',
        );
    }
}
