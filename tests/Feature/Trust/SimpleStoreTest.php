<?php

namespace Tests\Feature\Trust;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

class SimpleStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_endpoint_creates_verification(): void
    {
        $organisation = Organisation::factory()->create();
        // PBDIGIT-213 C3 (fixture only, PO-approved like C3-D1): identity verification is
        // recorded only inside the verification window (D1: not in Draft).
        $election = ElectionScenarioFactory::setupAdministration($organisation);
        $officer = User::factory()->create();
        $voter = User::factory()->create();

        // Officer must be an organisation member (required by middleware)
        UserOrganisationRole::create([
            'user_id' => $officer->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // PBDIGIT-213 C3 (fixture only, PO-approved like C3-D1): only a registered voter
        // of this election can be verified (D3 registered-target rule).
        UserOrganisationRole::create([
            'user_id' => $voter->id,
            'organisation_id' => $organisation->id,
            'role' => 'voter',
        ]);
        ElectionMembership::create([
            'user_id' => $voter->id,
            'organisation_id' => $organisation->id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        // Officer role in election
        ElectionOfficer::create([
            'election_id' => $election->id,
            'user_id' => $officer->id,
            'organisation_id' => $organisation->id,
            'role' => 'chief',
            'status' => 'active',
        ]);

        // Verify record does NOT exist before POST
        $this->assertDatabaseMissing('voter_verifications', [
            'user_id' => $voter->id,
            'election_id' => $election->id,
        ]);

        // Make the POST
        $response = $this->actingAs($officer)->post(
            route('elections.voters.verify', [
                'organisation' => $organisation->slug,
                'election' => $election->slug,
            ]),
            [
                'user_id' => $voter->id,
                'verified_ip' => '192.168.1.1',
            ]
        );

        // Debug response
        $status = $response->getStatusCode();

        // Print errors if the request failed validation
        if ($status !== 200 && $status !== 302) {
            $this->fail("Unexpected status {$status}. Response:\n" . $response->getContent());
        }

        // Check if there are session errors (validation errors)
        if ($response->getSession() && $response->getSession()->has('errors')) {
            $errors = $response->getSession()->get('errors');
            $this->fail("Validation errors: " . json_encode($errors));
        }

        // Verify record EXISTS after POST
        $this->assertDatabaseHas('voter_verifications', [
            'user_id' => $voter->id,
            'election_id' => $election->id,
            'verified_by' => $officer->id,
        ]);
    }
}
