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
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Adjudication\Domain\Exception\DeterminationAlreadyIssued;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            new DateTimeImmutable('2026-06-27T10:00:00+00:00'),
        );
    }

    public function test_issuing_persists_determination_and_enqueues_exactly_one_event(): void
    {
        $id = $this->service()->issueDetermination($this->command('ch-1'));

        $this->assertDatabaseCount('determinations', 1);
        $this->assertDatabaseHas('determinations', [
            'id' => $id->toString(),
            'organisation_id' => $this->tenantId,
            'challenge_ref' => 'ch-1',
            'state' => 'issued',
        ]);

        // Exactly one DeterminationIssued enqueued to the existing outbox.
        $this->assertDatabaseCount('outbox_events', 1);
        $this->assertDatabaseHas('outbox_events', [
            'event_type' => 'DeterminationIssued',
            'aggregate_type' => 'Determination',
            'aggregate_id' => $id->toString(),
            'organisation_id' => $this->tenantId,
            'status' => 'pending',
        ]);
    }

    public function test_reissue_same_challenge_throws_and_keeps_single_row(): void
    {
        $this->service()->issueDetermination($this->command('ch-9'));

        try {
            $this->service()->issueDetermination($this->command('ch-9'));
            $this->fail('Expected DeterminationAlreadyIssued');
        } catch (DeterminationAlreadyIssued) {
            $this->assertDatabaseCount('determinations', 1);
            $this->assertDatabaseCount('outbox_events', 1);
        }
    }
}
