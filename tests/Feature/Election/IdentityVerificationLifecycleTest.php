<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\DTO\SecurityAssessment;
use App\Contexts\Elections\Application\DTO\SecurityAttemptContext;
use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery;
use App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Contexts\Trust\Domain\Events\VerificationRevokedEvent;
use Illuminate\Support\Facades\Event;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Identity-verification lifecycle and separation from security — backend contract
 * (proposal docs/publicdigit/reviews/2026-10-05-identity-verification-lifecycle-and-email-proposal.md).
 *
 *   B1  Verify → Cancel (existing revoke endpoint): the evidence ends its admission effect, the
 *       history (verified_by / verified_at) stays, the revocation is recorded and audited, and the
 *       voter is no longer admitted while the policy requires identity.                guard (expected pass)
 *   B2  The identity endpoint rejects legacy IP / device fields (ID-D2).                      RED
 *   B4  Identity evidence is recorded without network / device data.                         guard
 *   B3  Identity verification does not consult the Security Verdict Authority.             guard (expected pass)
 *
 * Synthetic data only. No production change.
 */
class IdentityVerificationLifecycleTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private Election $election;
    private User $chief;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
        $this->election = ElectionScenarioFactory::setupAdministration($this->org);
        $this->requireIdentityVerification($this->election);
        $this->chief = $this->officer($this->election, 'chief');
        $this->voter = $this->registeredVoter($this->election);
    }

    private function verify(array $extra = [])
    {
        return $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])
            ->post($this->voterRoute('elections.voters.verify', $this->election),
                array_merge(['user_id' => $this->voter->id, 'attested' => true], $extra));
    }

    private function evidenceRow(): ?VoterVerification
    {
        return VoterVerification::where('election_id', $this->election->id)->where('user_id', $this->voter->id)->first();
    }

    private function admitted(): bool
    {
        return app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id)->isEntitled();
    }

    public function test_b1_cancelling_a_verification_ends_admission_but_keeps_the_history(): void
    {
        $this->verify()->assertSessionHasNoErrors();
        $this->assertTrue($this->admitted(), 'precondition: verified voter is admitted');
        $row = $this->evidenceRow();
        Event::fake([VerificationRevokedEvent::class]);   // the revocation is announced (audit goes to the election audit file)

        $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])
            ->delete($this->voterRoute('elections.voters.verification.revoke', $this->election, ['verification' => $row->id]))
            ->assertSessionHasNoErrors();

        $row = $row->fresh();
        $this->assertSame('revoked', $row->status, 'the current admission effect ends');
        $this->assertSame($this->chief->id, $row->verified_by, 'who verified stays');
        $this->assertNotNull($row->verified_at, 'when it was verified stays');
        $this->assertSame($this->chief->id, $row->revoked_by, 'who cancelled is recorded');
        $this->assertNotNull($row->revoked_at, 'when it was cancelled is recorded');
        $this->assertFalse($this->admitted(), 'a cancelled verification no longer satisfies the requirement');
        Event::assertDispatched(VerificationRevokedEvent::class, fn ($e) => $e->verificationId() === (string) $row->id && $e->revokedBy() === $this->chief->id);
    }

    public function test_b2_identity_verification_rejects_legacy_ip_and_device_fields(): void
    {
        // ID-D2 (PO 2026-10-05): the frontend contract is removed; the backend refuses the legacy fields
        // instead of treating them as identity evidence. Nothing is recorded.
        $this->verify([
            'verified_ip'                      => '203.0.113.7',
            'verified_device_fingerprint_hash' => str_repeat('a', 64),
            'verified_device_components'       => ['ua' => 'test'],
        ])->assertSessionHasErrors(['verified_ip', 'verified_device_fingerprint_hash', 'verified_device_components']);

        $this->assertNull($this->evidenceRow(), 'no identity evidence is recorded from a request carrying network / device data');
    }

    public function test_b4_identity_verification_records_evidence_without_network_or_device_data(): void
    {
        $this->verify()->assertSessionHasNoErrors();

        $row = $this->evidenceRow();
        $this->assertSame('active', $row?->status);
        $this->assertNull($row->verified_ip, 'Admission evidence carries no network data (Security Policy concern)');
        $this->assertNull($row->verified_device_fingerprint_hash, 'Admission evidence carries no device data');
        $this->assertNull($row->verified_device_components);
    }

    public function test_b3_identity_verification_does_not_consult_the_security_verdict_authority(): void
    {
        $refuse = fn () => throw new \LogicException('identity verification must not consult the Security Verdict Authority');
        $this->app->instance(VotingSecurityVerdictQuery::class, new class($refuse) implements VotingSecurityVerdictQuery {
            public function __construct(private \Closure $refuse) {}
            public function verdictFor(SecurityAttemptContext $context): SecurityVerdict { ($this->refuse)(); }
        });
        $this->app->instance(VotingSecurityAssessmentQuery::class, new class($refuse) implements VotingSecurityAssessmentQuery {
            public function __construct(private \Closure $refuse) {}
            public function assess(SecurityAttemptContext $context): SecurityAssessment { ($this->refuse)(); }
        });

        $this->verify()->assertSessionHasNoErrors();

        $this->assertSame('active', $this->evidenceRow()?->status);
    }
}
