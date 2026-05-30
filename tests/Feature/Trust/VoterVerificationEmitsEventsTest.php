<?php

namespace Tests\Feature\Trust;

use App\Contexts\Trust\Domain\Events\IdentityAttestedEvent;
use App\Contexts\Trust\Domain\Events\VerificationRevokedEvent;
use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class VoterVerificationEmitsEventsTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $officer;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->election = Election::factory()->for($this->organisation)->create();
        $this->officer = User::factory()->create();
        $this->voter = User::factory()->create();

        // Officer must be organisation member (required by middleware)
        UserOrganisationRole::create([
            'user_id' => $this->officer->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
        ]);

        // Create officer record with chief role for authorization
        ElectionOfficer::create([
            'election_id' => $this->election->id,
            'user_id' => $this->officer->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'chief',
            'status' => 'active',
        ]);

        $this->actingAs($this->officer);
    }

    public function test_identity_attested_event_dispatched_on_store(): void
    {
        Event::fake([IdentityAttestedEvent::class]);

        $response = $this->post(
            route('elections.voters.verify', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]),
            [
                'user_id' => $this->voter->id,
                'verified_ip' => '192.168.1.1',
                'verified_device_fingerprint_hash' => 'hash-12345',
                'notes' => 'Verified via video',
            ]
        );

        // DEBUG: Show what actually happened
        $statusCode = $response->getStatusCode();
        $this->assertTrue(
            $statusCode === 302 || $statusCode === 200,
            "POST returned $statusCode. Response:\n" . $response->getContent()
        );

        Event::assertDispatched(IdentityAttestedEvent::class, function ($event) {
            return $event->userId() === $this->voter->id
                && $event->attestedBy() === $this->officer->id
                && $event->verificationId() !== null;
        });
    }

    public function test_verification_revoked_event_dispatched_on_revoke(): void
    {
        $verification = VoterVerification::factory()
            ->for($this->election)
            ->for($this->voter, 'user')
            ->create([
                'verified_by' => $this->officer->id,
                'verified_at' => now(),
            ]);

        Event::fake([VerificationRevokedEvent::class]);

        $response = $this->delete(
            route('elections.voters.verification.revoke', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
                'verification' => $verification->id,
            ])
        );

        $statusCode = $response->getStatusCode();
        $this->assertTrue(
            $statusCode === 302 || $statusCode === 200,
            "DELETE returned $statusCode. Response:\n" . $response->getContent()
        );

        Event::assertDispatched(VerificationRevokedEvent::class, function ($event) use ($verification) {
            return $event->verificationId() === $verification->id
                && $event->userId() === $this->voter->id
                && $event->revokedBy() === $this->officer->id;
        });
    }

    public function test_existing_db_state_preserved_after_store(): void
    {
        $response = $this->post(
            route('elections.voters.verify', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]),
            [
                'user_id' => $this->voter->id,
                'verified_ip' => '192.168.1.1',
                'notes' => 'Test verification',
            ]
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 302 && $statusCode !== 200) {
            $this->fail("POST returned $statusCode:\n" . $response->getContent());
        }

        $this->assertDatabaseHas('voter_verifications', [
            'election_id' => $this->election->id,
            'user_id' => $this->voter->id,
            'verified_by' => $this->officer->id,
            'status' => 'active',
        ]);
    }

    public function test_existing_db_state_preserved_after_revoke(): void
    {
        $verification = VoterVerification::factory()
            ->for($this->election)
            ->for($this->voter, 'user')
            ->create([
                'verified_by' => $this->officer->id,
                'status' => 'active',
            ]);

        $response = $this->delete(
            route('elections.voters.verification.revoke', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
                'verification' => $verification->id,
            ])
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 302 && $statusCode !== 200) {
            $this->fail("DELETE returned $statusCode:\n" . $response->getContent());
        }

        $this->assertDatabaseHas('voter_verifications', [
            'id' => $verification->id,
            'status' => 'revoked',
            'revoked_by' => $this->officer->id,
        ]);
    }
}
