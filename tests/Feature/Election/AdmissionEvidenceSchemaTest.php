<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C2 — admission-evidence schema on voter_verifications (ADR D3).
 *
 * The evidence must be able to record WHO verified in WHICH ROLE, by WHICH METHOD,
 * and WHY its current admission effect ended (revocation_reason). C2 adds the
 * columns only; C3 writes verifier_role / revocation_reason. Legacy rows without
 * a role stay valid; the method defaults to officer_attestation.
 */
class AdmissionEvidenceSchemaTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $chief;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        session(['current_organisation_id' => $this->org->id]);

        [$this->election, $this->chief] = ElectionScenarioFactory::setupAdministrationWithChief($this->org);

        $this->voter = User::factory()->create(['organisation_id' => $this->org->id]);
        ElectionMembership::create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);
    }

    private function evidence(array $extra = []): VoterVerification
    {
        return VoterVerification::create(array_merge([
            'election_id'     => $this->election->id,
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'verified_by'     => $this->chief->id,
            'verified_at'     => now(),
            'status'          => 'active',
        ], $extra))->fresh();
    }

    public function test_evidence_records_the_verifier_role_and_method(): void
    {
        $evidence = $this->evidence(['verifier_role' => 'ELECTION_CHIEF', 'method' => 'officer_attestation']);

        $this->assertSame('ELECTION_CHIEF', $evidence->verifier_role);
        $this->assertSame('officer_attestation', $evidence->method);
    }

    public function test_the_method_defaults_to_officer_attestation(): void
    {
        $this->assertSame('officer_attestation', $this->evidence()->method);
    }

    public function test_a_legacy_row_without_a_verifier_role_is_valid(): void
    {
        // The column must exist (otherwise a null read proves nothing) and be nullable.
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('voter_verifications', 'verifier_role'));
        $this->assertNull($this->evidence()->verifier_role);
    }

    public function test_evidence_records_why_its_admission_effect_ended(): void
    {
        $evidence = $this->evidence([
            'status'            => 'revoked',
            'revoked_by'        => $this->chief->id,
            'revoked_at'        => now(),
            'revocation_reason' => 'suspension_confirmed',
        ]);

        $this->assertSame('suspension_confirmed', $evidence->revocation_reason);
    }
}
