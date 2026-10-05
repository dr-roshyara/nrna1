<?php

namespace Tests\Feature\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Contexts\Elections\Application\Ports\IdentityEvidenceRecord;
use App\Contexts\Elections\Application\Ports\IdentityEvidenceStore;
use App\Contexts\Elections\Domain\ValueObjects\RevocationReason;
use App\Contexts\Elections\Infrastructure\Repositories\EloquentIdentityEvidenceStore;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\VoterVerification;
use App\Services\ElectionAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C4-C — "Verify All": one bulk attestation by the election's Chief or
 * Deputy Chief, producing the SAME OFFICER_IDENTITY_VERIFICATION evidence as the
 * individual action (only method differs: officer_bulk_attestation).
 *
 * PO decisions C4-D1…D4:
 *  D1 scope: role voter · status active · not deleted · NO voter_verifications row at all
 *     (never verified). Already verified, revoked (incl. suspension_confirmed),
 *     inactive, invited, removed and other elections are excluded.
 *  D2 method officer_bulk_attestation; verifier_role derived from the election.
 *  D3 attested + expected_count required; server-side count mismatch ⇒ nothing written.
 *  D4 one voter_verified audit per voter + one voters_bulk_verified summary, one batch id.
 * Atomic: all evidence in one transaction.
 */
class BulkIdentityVerificationTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private const STATEMENT = "I confirm that the identities of these voters have already been verified according to the organisation's voter-registration process.";

    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
        $this->election = ElectionScenarioFactory::setupAdministration($this->org);
        $this->requireIdentityVerification($this->election);
    }

    private function verifyAll($actor, Election $election, array $payload)
    {
        return $this->actingAs($actor)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->from('/voters-manage')
            ->post($this->voterRoute('elections.voters.verify-all', $election), $payload);
    }

    private function rowsFor(Election $election): int
    {
        return VoterVerification::where('election_id', $election->id)->count();
    }

    // ── Evidence recording is independent of the policy (PO, 2026-10-04) ──────

    public function test_verify_all_records_evidence_while_identity_verification_is_not_required(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);   // policy OFF (default)
        $chief    = $this->officer($election, 'chief');
        $voter    = $this->registeredVoter($election);

        $this->verifyAll($chief, $election, ['attested' => '1', 'expected_count' => 1])->assertSessionHasNoErrors();

        $row = VoterVerification::where('election_id', $election->id)->where('user_id', $voter->id)->first();
        $this->assertSame('active', $row?->status);
        $this->assertSame('officer_bulk_attestation', $row?->method);
    }

    // ── C4-D1 scope matrix ───────────────────────────────────────────────────

    public function test_only_active_voters_who_never_had_evidence_are_bulk_verified(): void
    {
        $chief = $this->officer($this->election, 'chief');

        $neverVerified  = $this->registeredVoter($this->election);
        $verified       = $this->registeredVoter($this->election);
        $verifiedRow    = $this->evidence($verified, $this->election, $chief);
        $suspendRevoked = $this->registeredVoter($this->election);
        $this->evidence($suspendRevoked, $this->election, $chief, 'revoked', 'suspension_confirmed');
        $officerRevoked = $this->registeredVoter($this->election);
        $this->evidence($officerRevoked, $this->election, $chief, 'revoked', 'revoked_by_officer');
        $inactive = $this->registeredVoter($this->election, 'inactive');
        $invited  = $this->registeredVoter($this->election, 'invited');
        $removed  = $this->registeredVoter($this->election, 'removed');
        $otherElection = ElectionScenarioFactory::setupAdministration($this->org);
        $elsewhere     = $this->registeredVoter($otherElection);

        $this->verifyAll($chief, $this->election, ['attested' => '1', 'expected_count' => 1])->assertSessionHasNoErrors();

        $new = VoterVerification::where('election_id', $this->election->id)->where('user_id', $neverVerified->id)->first();
        $this->assertNotNull($new);
        $this->assertSame('active', $new->status);
        $this->assertSame('officer_bulk_attestation', $new->method);
        $this->assertSame('ELECTION_CHIEF', $new->verifier_role);
        $this->assertSame($chief->id, $new->verified_by);
        $this->assertSame(self::STATEMENT, $new->notes, 'the attestation statement is kept for audit');

        $this->assertSame('officer_attestation', $verifiedRow->fresh()->method, 'an existing verification is untouched');
        $this->assertEquals($verifiedRow->verified_at, $verifiedRow->fresh()->verified_at);
        foreach ([$suspendRevoked, $officerRevoked] as $voter) {
            $this->assertSame('revoked', VoterVerification::where('election_id', $this->election->id)->where('user_id', $voter->id)->value('status'),
                'revoked evidence is never re-created by a bulk click');
        }
        foreach ([$inactive, $invited, $removed] as $voter) {
            $this->assertNull(VoterVerification::where('election_id', $this->election->id)->where('user_id', $voter->id)->first());
        }
        $this->assertSame(0, $this->rowsFor($otherElection));
        $this->assertNull(VoterVerification::where('user_id', $elsewhere->id)->first());
    }

    // ── C4-D3 attestation and count guard ────────────────────────────────────

    public function test_the_attestation_must_be_confirmed(): void
    {
        $chief = $this->officer($this->election, 'chief');
        $this->registeredVoter($this->election);

        $this->verifyAll($chief, $this->election, ['expected_count' => 1])->assertSessionHasErrors('attested');

        $this->assertSame(0, $this->rowsFor($this->election));
    }

    public function test_a_changed_voter_count_refuses_the_batch_and_writes_nothing(): void
    {
        $chief = $this->officer($this->election, 'chief');
        $this->registeredVoter($this->election);
        $this->registeredVoter($this->election);

        $this->verifyAll($chief, $this->election, ['attested' => '1', 'expected_count' => 1])->assertSessionHasErrors('expected_count');

        $this->assertSame(0, $this->rowsFor($this->election));
    }

    // ── Authority (C3 rule, unchanged) ───────────────────────────────────────

    /** @return array<string, array{string}> */
    public static function nonLeadership(): array
    {
        return [
            'commissioner of the election' => ['commissioner'],
            'organisation-wide chief'      => ['org-wide-chief'],
            'chief of another election'    => ['other-election-chief'],
        ];
    }

    /** @dataProvider nonLeadership */
    public function test_only_the_chief_or_deputy_chief_of_this_election_may_verify_all(string $actor): void
    {
        $this->registeredVoter($this->election);
        $user = match ($actor) {
            'commissioner'         => $this->officer($this->election, 'commissioner'),
            'org-wide-chief'       => $this->officer($this->election, 'chief', null),
            'other-election-chief' => $this->officer($this->election, 'chief', ElectionScenarioFactory::setupAdministration($this->org)->id),
        };

        $this->verifyAll($user, $this->election, ['attested' => '1', 'expected_count' => 1])->assertForbidden();

        $this->assertSame(0, $this->rowsFor($this->election));
    }

    public function test_the_deputy_chief_may_verify_all_and_is_recorded_as_deputy(): void
    {
        $deputy = $this->officer($this->election, 'deputy');
        $voter  = $this->registeredVoter($this->election);

        $this->verifyAll($deputy, $this->election, ['attested' => '1', 'expected_count' => 1])->assertSessionHasNoErrors();

        $this->assertSame('ELECTION_DEPUTY_CHIEF',
            VoterVerification::where('election_id', $this->election->id)->where('user_id', $voter->id)->value('verifier_role'));
    }

    // ── Verification window (D1, unchanged) ──────────────────────────────────

    public function test_verify_all_is_allowed_during_voting(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief    = $this->officer($election, 'chief');
        $this->registeredVoter($election);

        $this->verifyAll($chief, $election, ['attested' => '1', 'expected_count' => 1])->assertSessionHasNoErrors();

        $this->assertSame(1, $this->rowsFor($election));
    }

    /** @return array<string, array{string}> */
    public static function closedWindow(): array
    {
        return ['Draft' => ['draft'], 'Counting' => ['counting']];
    }

    /** @dataProvider closedWindow */
    public function test_verify_all_is_refused_outside_the_verification_window(string $state): void
    {
        $election = $state === 'draft'
            ? Election::factory()->forOrganisation($this->org)->create(['type' => 'real'])
            : ElectionScenarioFactory::votingClosed($this->org);
        $this->assertContains(ElectionLifecycle::of($election->fresh())->state(),
            [ElectionLifecycleState::Draft, ElectionLifecycleState::Counting], 'Precondition');
        $chief = $this->officer($election, 'chief');
        $this->registeredVoter($election);

        $this->verifyAll($chief, $election, ['attested' => '1', 'expected_count' => 1])->assertForbidden();

        $this->assertSame(0, $this->rowsFor($election));
    }

    // ── C4-D4 audit ──────────────────────────────────────────────────────────

    public function test_each_voter_and_the_batch_are_audited_with_one_batch_id(): void
    {
        $calls = [];
        $this->mock(ElectionAuditService::class, function ($mock) use (&$calls) {
            $mock->shouldReceive('log')->andReturnUsing(function (...$args) use (&$calls) {
                $calls[] = $args;
            });
        });
        $chief = $this->officer($this->election, 'chief');
        $a = $this->registeredVoter($this->election);
        $b = $this->registeredVoter($this->election);

        $this->verifyAll($chief, $this->election, ['attested' => '1', 'expected_count' => 2]);

        $perVoter = array_values(array_filter($calls, fn ($c) => ($c[1] ?? null) === 'voter_verified'));
        $summary  = array_values(array_filter($calls, fn ($c) => ($c[1] ?? null) === 'voters_bulk_verified'));
        $this->assertCount(2, $perVoter);
        $this->assertCount(1, $summary);

        $batchId = $summary[0][5]['batch_id'];
        $this->assertNotEmpty($batchId);
        $this->assertSame(2, $summary[0][5]['count']);
        $this->assertSame(self::STATEMENT, $summary[0][5]['statement']);
        $this->assertEqualsCanonicalizing([$a->id, $b->id], array_map(fn ($c) => $c[5]['subject_user_id'], $perVoter));
        foreach ($perVoter as $call) {
            $this->assertSame('officer_bulk_attestation', $call[5]['method']);
            $this->assertSame('ELECTION_CHIEF', $call[5]['verifier_role']);
            $this->assertSame($batchId, $call[5]['batch_id']);
        }
    }

    // ── Same evidence ⇒ same admission effect, immediately ───────────────────

    public function test_bulk_verified_voters_are_admitted_immediately(): void
    {
        $chief = $this->officer($this->election, 'chief');
        $voter = $this->registeredVoter($this->election);
        $this->assertFalse($voter->isVoterInElection($this->election->id), 'warm: not admitted');

        $this->verifyAll($chief, $this->election, ['attested' => '1', 'expected_count' => 1]);

        $this->assertTrue($voter->isVoterInElection($this->election->id), 'bulk evidence admits at once');
    }

    // ── Atomicity ────────────────────────────────────────────────────────────

    public function test_a_failure_part_way_through_writes_no_evidence_at_all(): void
    {
        $chief = $this->officer($this->election, 'chief');
        $this->registeredVoter($this->election);
        $this->registeredVoter($this->election);
        $this->app->instance(IdentityEvidenceStore::class, new class(new EloquentIdentityEvidenceStore()) implements IdentityEvidenceStore {
            private int $calls = 0;

            public function __construct(private readonly IdentityEvidenceStore $inner) {}

            public function recordActive(IdentityEvidenceRecord $record): string
            {
                if (++$this->calls === 2) {
                    throw new \RuntimeException('simulated failure on the second voter');
                }

                return $this->inner->recordActive($record);
            }

            public function revokeActive(string $electionId, string $voterId, string $revokedBy, RevocationReason $reason): ?string
            {
                return $this->inner->revokeActive($electionId, $voterId, $revokedBy, $reason);
            }
        });

        $this->verifyAll($chief, $this->election, ['attested' => '1', 'expected_count' => 2]);

        $this->assertSame(0, $this->rowsFor($this->election), 'all-or-nothing');
    }
}
