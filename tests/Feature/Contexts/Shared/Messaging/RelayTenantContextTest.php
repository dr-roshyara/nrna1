<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Messaging;

use App\Contexts\Contestation\Application\Command\RaiseChallengeCommand;
use App\Contexts\Contestation\Application\Service\ContestationService;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ContestedOutcomeRef;
use App\Contexts\Contestation\Domain\Challenge\ElectionId;
use App\Contexts\Contestation\Domain\Challenge\RaiserStandingRef;
use App\Contexts\Contestation\Domain\Challenge\SubmittedContent;
use App\Contexts\Contestation\Domain\Challenge\TargetId;
use App\Contexts\Contestation\Domain\Challenge\TargetType;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use App\Models\Organisation;
use App\Services\TenantContext;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PBDIGIT-211 addendum (T-1): the SCHEDULED relay must deliver each event under the
 * organisation it carries — characterisation test, written RED before any fix.
 *
 * The defect hypothesis (static evidence, remediation-gate report §2): `outbox:process`
 * runs from the scheduler with NO tenant context; it carries `organisation_id` into the
 * inbox but never establishes it, and the correction-loop consumers call
 * `TenantContext::require()`. Every existing loop test sets the tenant in `setUp`, which
 * masks exactly this.
 *
 * THEREFORE, DELIBERATELY:
 *  - the tenant is set ONLY while producing the event (a production write happens inside
 *    a request that has a tenant), then CLEARED before the relay runs;
 *  - the relay is driven through the scheduled command (`outbox:process`), never by
 *    calling a consumer directly — nothing on the tenant path is mocked.
 *
 * These assertions encode the acceptance criteria AC-R1/R2/R4 of the addendum; they say
 * nothing about HOW the tenant is established — that mechanism is a pending ARB decision
 * (gate report §5, Q1–Q7). A RED result is evidence, not authorisation to change code.
 *
 * Isolation: unique organisations per test (Tests\TestCase has no per-test rollback on
 * PostgreSQL — PBDIGIT-263); every assertion is scoped to this test's organisations.
 *
 * Traceability: PBDIGIT-211 addendum · ADR-MP dispatcher invariant "tenant propagation /
 * isolation" · ADR-MP-04 · ADR-T16 · docs/publicdigit/reviews/2026-10-09-adjudication-remediation-gate.md.
 */
final class RelayTenantContextTest extends TestCase
{
    // RefreshDatabase is inherited from Tests\TestCase and deliberately NOT redeclared:
    // redeclaring it re-enables the per-test transaction that Tests\TestCase skips on
    // PostgreSQL. The scheduled relay runs in AUTOCOMMIT in production; wrapping it in an
    // outer test transaction changes failure semantics (one failed statement aborts the
    // whole outer transaction — observed 2026-10-09, SQLSTATE 25P02).

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    private function organisation(string $label): string
    {
        return (string) Organisation::create([
            'name' => "Relay tenant {$label}",
            'slug' => 'relay-tenant-'.$label.'-'.Str::uuid(),
            'type' => 'tenant',
            'is_default' => false,
        ])->id;
    }

    /**
     * Produce a real `ChallengeRouted` outbox row for $orgId through the production
     * application service — the tenant is present only while producing, as in a request.
     */
    private function routeChallengeFor(string $orgId): ChallengeId
    {
        TenantContext::set($orgId);

        try {
            $service = $this->app->make(ContestationService::class);
            $id = $service->raise(new RaiseChallengeCommand(
                RaiserStandingRef::fromString('standing-holder-1'),
                ContestedOutcomeRef::of(
                    ElectionId::fromString('election-1'),
                    TargetType::ElectionResult,
                    TargetId::fromString('result-1'),
                ),
                SubmittedContent::fromString('Synthetic challenge for the relay tenant test.'),
            ));
            $service->admit($id);
            $service->route($id, 'constitutional-council');

            return $id;
        } finally {
            TenantContext::clear();
        }
    }

    /** Drive the relay exactly as the scheduler does (routes/console.php: outbox:process). */
    private function runScheduledRelay(): void
    {
        OutboxEventProcessor::$lastException = null;
        $levelBefore = DB::transactionLevel();
        Artisan::call('outbox:process');

        if (getenv('RELAY_TEST_DIAGNOSE') === '1') {
            $e = OutboxEventProcessor::$lastException;
            fwrite(STDERR, sprintf(
                "\n[diagnose] %s | transactionLevel before=%d after=%d | lastException=%s\n",
                $this->name(),
                $levelBefore,
                DB::transactionLevel(),
                $e === null ? 'none' : get_class($e).': '.$e->getMessage().' @ '.$e->getFile().':'.$e->getLine(),
            ));
        }
    }

    /** Processes of a challenge, read without any tenant scope (raw table). */
    private function processRowsFor(ChallengeId $id): array
    {
        return DB::table('adjudication_processes')
            ->where('challenge_ref', $id->toString())
            ->get()
            ->all();
    }

    private function relayDiagnosis(): string
    {
        $e = OutboxEventProcessor::$lastException;

        return $e === null
            ? 'relay recorded no exception'
            : 'relay exception: '.get_class($e).': '.$e->getMessage();
    }

    // ── RT-1 · AC-R1 — the loop head opens under the event's organisation ──────

    public function test_the_scheduled_relay_opens_the_adjudication_without_an_ambient_tenant(): void
    {
        $orgA = $this->organisation('a');
        $challenge = $this->routeChallengeFor($orgA);

        $this->assertFalse(TenantContext::has(), 'precondition: no ambient tenant at relay time');
        $this->runScheduledRelay();

        $rows = $this->processRowsFor($challenge);
        $this->assertCount(
            1,
            $rows,
            'the routed challenge must open exactly one adjudication process when the relay runs '
            .'outside a request — '.$this->relayDiagnosis(),
        );
        $this->assertSame($orgA, (string) $rows[0]->organisation_id, 'the process belongs to the event\'s organisation');

        $outbox = DB::table('outbox_events')
            ->where('organisation_id', $orgA)
            ->where('event_type', 'ChallengeRouted')
            ->first();
        $this->assertNotNull($outbox);
        $this->assertSame('completed', $outbox->status, 'a delivered event is completed, not retried');
    }

    // ── RT-3 · AC-R1/AC-R4 — two organisations, one relay pass, no crossing ────

    public function test_one_relay_pass_delivers_each_organisations_event_under_its_own_organisation(): void
    {
        $orgA = $this->organisation('a');
        $orgB = $this->organisation('b');
        $challengeA = $this->routeChallengeFor($orgA);
        $challengeB = $this->routeChallengeFor($orgB);

        $this->runScheduledRelay();

        $rowsA = $this->processRowsFor($challengeA);
        $rowsB = $this->processRowsFor($challengeB);
        $this->assertCount(1, $rowsA, 'organisation A\'s challenge opens one process — '.$this->relayDiagnosis());
        $this->assertCount(1, $rowsB, 'organisation B\'s challenge opens one process — '.$this->relayDiagnosis());
        $this->assertSame($orgA, (string) $rowsA[0]->organisation_id);
        $this->assertSame($orgB, (string) $rowsB[0]->organisation_id);
    }

    // ── RT-2 · AC-R2 — the ambient tenant is the same before and after ─────────

    public function test_the_relay_leaves_no_tenant_behind(): void
    {
        $orgA = $this->organisation('a');
        $this->routeChallengeFor($orgA);

        $this->runScheduledRelay();

        $this->assertFalse(
            TenantContext::has(),
            'after the relay the ambient tenant must equal its value before (none) — no leakage into the next unit of work',
        );
    }

    // ── RT-4 · AC-R4/AC-R2 — a leaked ambient tenant never captures another org's event ──

    public function test_a_foreign_ambient_tenant_never_captures_another_organisations_event(): void
    {
        $orgA = $this->organisation('a');
        $orgB = $this->organisation('b');
        $challengeA = $this->routeChallengeFor($orgA);

        // Simulate state leaked from a previous unit of work in the same process.
        TenantContext::set($orgB);
        $this->runScheduledRelay();
        $ambientAfter = TenantContext::get();
        TenantContext::clear();

        foreach ($this->processRowsFor($challengeA) as $row) {
            $this->assertNotSame(
                $orgB,
                (string) $row->organisation_id,
                'an event of organisation A must never be processed under organisation B',
            );
        }
        $this->assertCount(
            0,
            DB::table('adjudication_processes')->where('organisation_id', $orgB)->get(),
            'organisation B must receive no process from A\'s event',
        );
        $this->assertSame($orgB, $ambientAfter, 'the ambient tenant is restored to its prior value');
    }

    // ── RT-6 · AC-R3 — soft-deleted organisation: classification is ARB Q4 ───

    public function test_an_event_of_a_soft_deleted_organisation_is_classified_per_the_arb_ruling(): void
    {
        // A NON-EXISTENT organisation cannot reach the relay: outbox_events.organisation_id
        // and inbox_events.organisation_id are foreign keys (cascadeOnDelete). The reachable
        // case is a SOFT-DELETED organisation (Organisation uses SoftDeletes). Whether such a
        // message is dead-lettered, parked or processed is ARB question Q4 — not invented here.
        $this->markTestIncomplete('AC-R3 classification pending ARB ruling (remediation-gate report §5 Q4).');
    }
}
