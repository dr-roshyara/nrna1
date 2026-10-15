<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Application\Election\Security\VoterAttestationEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedDatabaseTestCase;

/**
 * PBDIGIT-286 P5 (AC-10; D3, D3a, D3-E, D3-P) on PostgreSQL: the trust-only read port answers from
 * `voter_verifications` exactly as admission does. Active individual, bulk and legacy rows count; revoked, unknown-status
 * and missing rows don't; rows of another election or voter don't. End to end, the evaluator's trust level follows.
 * A read failure is covered database-free (TrustPolicyAttestationEvidenceTest).
 *
 * Isolated database only (G-ISO): run through phpunit.pbdigit274.xml with the identity guard.
 */
final class VoterAttestationEvidenceTest extends IsolatedDatabaseTestCase
{
    private Organisation $org;

    private Election $election;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd286-p5-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->create([
            'name' => 'PBDIGIT-286 P5 '.uniqid(), 'slug' => 'pbd286-p5-election-'.uniqid(),
        ]);
        $this->officer = $this->voter();
    }

    private function voter(): User
    {
        return User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => null]);
    }

    /** A verification row as the officer flows write it; $overrides set method, status and dates. */
    private function verification(User $voter, array $overrides = [], ?Election $election = null): void
    {
        $election ??= $this->election;
        DB::table('voter_verifications')->insert(array_merge([
            'id' => (string) Str::uuid(),
            'election_id' => $election->id,
            'user_id' => $voter->id,
            'organisation_id' => $this->org->id,
            'verified_by' => $this->officer->id,
            'verified_at' => now(),
            'status' => 'active',
            'method' => 'officer_attestation',
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function port(): VoterAttestationEvidence
    {
        return $this->app->make(VoterAttestationEvidence::class);
    }

    public function test_active_individual_verification_counts(): void
    {
        $voter = $this->voter();
        $this->verification($voter);

        $this->assertTrue($this->port()->hasActiveAttestation($this->election->id, $voter->id));
    }

    public function test_active_bulk_verification_counts(): void
    {
        $voter = $this->voter();
        $this->verification($voter, ['method' => 'officer_bulk_attestation']);

        $this->assertTrue($this->port()->hasActiveAttestation($this->election->id, $voter->id), 'D3 = A');
    }

    public function test_an_active_legacy_row_from_before_2026_10_04_counts(): void
    {
        $voter = $this->voter();
        $this->verification($voter, ['verified_at' => '2026-09-01 10:00:00', 'created_at' => '2026-09-01 10:00:00', 'updated_at' => '2026-09-01 10:00:00']);

        $this->assertTrue($this->port()->hasActiveAttestation($this->election->id, $voter->id), 'D3a');
    }

    public function test_a_revoked_row_does_not_count(): void
    {
        $voter = $this->voter();
        $this->verification($voter, ['status' => 'revoked', 'revoked_by' => $this->officer->id, 'revoked_at' => now()]);

        $this->assertFalse($this->port()->hasActiveAttestation($this->election->id, $voter->id), 'D3-E');
    }

    public function test_a_row_with_an_unknown_status_does_not_count(): void
    {
        // Review F4: `status` is a free string with no check constraint. Only exactly 'active' counts; a weaker
        // predicate such as `status != 'revoked'` would wrongly raise these voters to Attested.
        foreach (['pending', 'suspended', 'ACTIVE', ''] as $status) {
            $voter = $this->voter();
            $this->verification($voter, ['status' => $status]);

            $this->assertFalse($this->port()->hasActiveAttestation($this->election->id, $voter->id), "status '{$status}'");
        }
    }

    public function test_a_missing_row_does_not_count(): void
    {
        $this->assertFalse($this->port()->hasActiveAttestation($this->election->id, $this->voter()->id), 'D3-E');
    }

    public function test_evidence_of_another_election_or_another_voter_does_not_count(): void
    {
        $voter = $this->voter();
        $other = Election::factory()->forOrganisation($this->org)->real()->create(['name' => 'PBDIGIT-286 P5 other '.uniqid(), 'slug' => 'pbd286-p5-other-'.uniqid()]);
        $this->verification($voter, [], $other);
        $this->verification($this->voter());

        $this->assertFalse($this->port()->hasActiveAttestation($this->election->id, $voter->id));
    }

    public function test_the_evaluator_trust_level_follows_the_evidence_end_to_end(): void
    {
        $verified = $this->voter();
        $this->verification($verified);
        $unverified = $this->voter();
        $evaluator = $this->app->make(TrustPolicyEvaluator::class);

        $attested = $evaluator->evaluate($this->election, $verified, '203.0.113.7', null, 'pbd286-p5-session-1');
        $plain = $evaluator->evaluate($this->election, $unverified, '203.0.113.8', null, 'pbd286-p5-session-2');

        $this->assertSame(TrustLevel::Attested, $attested->result->trustLevel);
        $this->assertSame(TrustLevel::Unverified, $plain->result->trustLevel);
    }
}
