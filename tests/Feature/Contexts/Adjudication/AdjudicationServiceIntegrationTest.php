<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Adjudication;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Service\AdjudicationService;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Adjudication\Domain\Exception\DeterminationAlreadyIssued;
use App\Contexts\Adjudication\Infrastructure\Outbox\DeterminationIssuedHydrator;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class AdjudicationServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private string $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        // outbox_events.organisation_id has a FK to organisations — use a real org.
        $org = Organisation::create([
            'name' => 'Adjudication Test Org',
            'slug' => 'adjudication-test-org',
            'type' => 'tenant',
            'is_default' => false,
        ]);
        $this->tenantId = (string) $org->id;
        TenantContext::set($this->tenantId);
    }

    private function service(): AdjudicationService
    {
        return $this->app->make(AdjudicationService::class);
    }

    private function command(string $challenge = 'ch-1'): IssueDeterminationCommand
    {
        return new IssueDeterminationCommand(
            ChallengeRef::fromString($challenge),
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('Tally dispute upheld.'),
            IssuedByAuthority::fromString('ARB'),
            Jurisdiction::fromString('National'),
            EvidenceEnvelopeRef::fromString('ev-1'),
            ContestedOutcomeRef::of(
                ElectionId::fromString('election-1'),
                TargetType::ElectionResult,
                TargetId::fromString('result-1'),
            ),
            EvidenceSet::fromRefs('ev-1', 'ev-supplementary-2'),
            new DateTimeImmutable('2026-06-27T10:00:00+00:00'),
        );
    }

    public function test_issuing_persists_determination_and_enqueues_exactly_one_event(): void
    {
        $id = $this->service()->issueDetermination($this->command('ch-1'));

        // Scoped to this test's unique organisation — the harness has no per-test
        // rollback, so global table counts break in mixed-run compositions (PB-006
        // convention: scoped assertions, never global counts).
        $this->assertSame(1, DB::table('determinations')->where('organisation_id', $this->tenantId)->count());
        $this->assertDatabaseHas('determinations', [
            'id' => $id->toString(),
            'organisation_id' => $this->tenantId,
            'challenge_ref' => 'ch-1',
            'state' => 'issued',
        ]);

        // Exactly one DeterminationIssued enqueued to the existing outbox.
        $this->assertSame(1, DB::table('outbox_events')->where('organisation_id', $this->tenantId)->count());
        $this->assertDatabaseHas('outbox_events', [
            'event_type' => 'DeterminationIssued',
            'aggregate_type' => 'Determination',
            'aggregate_id' => $id->toString(),
            'organisation_id' => $this->tenantId,
            'status' => 'pending',
        ]);

        // KEYSTONE (WP-1, ADR-T22): v3 round-trip over the REAL wire — the
        // adapter-written payload carries schema_version 3 + the fixed set, and
        // the context's own hydrator reconstructs the event with the set intact.
        $row = DB::table('outbox_events')
            ->where('organisation_id', $this->tenantId)
            ->where('event_type', 'DeterminationIssued')
            ->first();
        $payload = json_decode((string) $row->payload, true);

        $this->assertSame(3, $payload['schema_version']);
        $this->assertSame(['ev-1', 'ev-supplementary-2'], $payload['evidenceSet']);

        $event = (new DeterminationIssuedHydrator())->hydrate($payload);
        $this->assertNotNull($event->evidenceSet);
        $this->assertSame(['ev-1', 'ev-supplementary-2'], $event->evidenceSet->toArray());
    }

    public function test_reissue_same_challenge_throws_and_keeps_single_row(): void
    {
        $this->service()->issueDetermination($this->command('ch-9'));

        try {
            $this->service()->issueDetermination($this->command('ch-9'));
            $this->fail('Expected DeterminationAlreadyIssued');
        } catch (DeterminationAlreadyIssued) {
            $this->assertSame(1, DB::table('determinations')->where('organisation_id', $this->tenantId)->count());
            $this->assertSame(1, DB::table('outbox_events')->where('organisation_id', $this->tenantId)->count());
        }
    }
}
