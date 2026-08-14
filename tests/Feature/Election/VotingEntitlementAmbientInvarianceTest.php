<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PBDIGIT-65/69 — RED tests under the APPROVED implementation boundary
 * (proposal v2, 3499ea38; grant Option A v5 §5a, f6bb5504).
 *
 * Two decisions, never combined:
 *  CC  — credential correspondence (Q-TEN-1/Q-TEN-2): election determines the
 *        required organisation; the credential supplies the comparand;
 *        mismatch or absent credential → DENY.  (TC1, TC2 — pins, GREEN today)
 *  ENT — voting entitlement (Decision A §2a): a fact of (voter, election);
 *        ambient session/tenant has NO role in the answer, in either
 *        direction.                              (TE1–TE5; TE2/TE3/TE4 RED today)
 *
 * TE3 is the central acceptance invariant: same voter + same election + same
 * valid credential across changing ambient tenants → identical answer
 * throughout. The P6 three-step sequence is HISTORICAL defect evidence only —
 * its old behaviour (wrong-ambient FALSE, replayed FALSE) is what these tests
 * prove the repaired system must NOT do.
 */
class VotingEntitlementAmbientInvarianceTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $orgA;
    private Organisation $orgB;
    private User $voter;
    private Election $electionA;
    private VoterSlug $credentialA;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->orgA = Organisation::factory()->create(['type' => 'tenant']);
        $this->orgB = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->orgA->id]);

        // UserFactory::configure() auto-creates the UserOrganisationRole for a
        // user with organisation_id — no explicit attach (it would duplicate).
        $this->voter = User::factory()->create([
            'email_verified_at' => now(),
            'organisation_id'   => $this->orgA->id,
        ]);

        $this->electionA = Election::factory()
            ->forOrganisation($this->orgA)
            ->create([
                'type'                  => 'real',
                'status'                => 'active',
                'voter_source_strategy' => 'election_only',
            ]);

        // Admitted, active voter of election A (org A) — the ENT fact.
        ElectionMembership::create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->orgA->id,
            'election_id'     => $this->electionA->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);

        // Valid credential: belongs to the election's organisation (CC passes).
        $this->credentialA = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->orgA->id,
            'election_id'     => $this->electionA->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    // =========================================================================
    // CC — credential correspondence (pins; expected GREEN today)
    // =========================================================================

    public function test_tc1_credential_organisation_mismatch_is_denied(): void
    {
        // Credential belongs to org B; election belongs to org A → CC must deny.
        // Own voter: voter_slugs is UNIQUE(election_id, user_id), so the
        // mismatch scenario cannot reuse the setUp credential's voter.
        $voterB = User::factory()->create([
            'email_verified_at' => now(),
            'organisation_id'   => $this->orgB->id,
        ]);
        $mismatched = VoterSlug::factory()->create([
            'user_id'         => $voterB->id,
            'organisation_id' => $this->orgB->id,
            'election_id'     => $this->electionA->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);

        $response = $this->actingAs($voterB)
            ->withSession(['current_organisation_id' => $this->orgB->id])
            ->get(route('slug.code.create', ['vslug' => $mismatched->slug]));

        // Established denial semantics: VotingException renderable
        // (bootstrap/app.php) → redirect to dashboard with an error flash
        // (OrganisationMismatchException from VerifyVoterSlugConsistency:58).
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_tc2_absent_credential_is_denied(): void
    {
        // No credential exists for this slug value → deny; nothing may be
        // derived from the election and no platform fallback may admit.
        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->orgA->id])
            ->get(route('slug.code.create', ['vslug' => 'no-such-credential-' . Str::random(8)]));

        // Established denial semantics (measured): the vslug route binder
        // resolves unscoped and aborts 404 for an unknown credential with
        // security logging (RouteServiceProvider::registerVoterSlugBinding).
        $response->assertNotFound();
    }

    // =========================================================================
    // ENT — ambient invariance (TE2/TE3/TE4 expected RED today)
    // =========================================================================

    public function test_te1_matching_ambient_admits_entitled_voter(): void
    {
        TenantContext::set($this->orgA->id);
        $this->assertTrue($this->voter->isVoterInElection($this->electionA->id),
            'ENT: admitted active voter of this election is entitled (matching ambient)');

        // Intended successful outcome: the code-entry step RENDERS
        // (estate pin: neither redirected nor forbidden — CodeControllerTest:77-83).
        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->orgA->id])
            ->get(route('slug.code.create', ['vslug' => $this->credentialA->slug]));

        $response->assertOk();
    }

    public function test_te2_different_ambient_does_not_change_entitlement(): void
    {
        // Same voter, same election, same valid credential — ambient is org B.
        TenantContext::set($this->orgB->id);
        $this->assertTrue($this->voter->isVoterInElection($this->electionA->id),
            'ENT: ambient session/tenant has NO role — the answer must be TRUE under ambient B (PBDIGIT-65)');
        TenantContext::clear();

        // Same intended successful outcome as TE1 — the ambient tenant must
        // not change it: the code-entry step RENDERS.
        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->orgB->id])
            ->get(route('slug.code.create', ['vslug' => $this->credentialA->slug]));

        $response->assertOk();
    }

    public function test_te3_ambient_invariance_sequence_yields_identical_answers(): void
    {
        // The central acceptance invariant. Cache stays WARM across steps on
        // purpose: no evaluation may poison any other (PBDIGIT-69).
        $election = $this->electionA->id;

        TenantContext::set($this->orgB->id);
        $this->assertTrue($this->voter->isVoterInElection($election),
            'TE3 step 1 (ambient B, cold cache): must be TRUE — old P6 produced FALSE here');

        TenantContext::set($this->orgA->id);
        $this->assertTrue($this->voter->isVoterInElection($election),
            'TE3 step 2 (ambient A, warm cache): must be TRUE — old P6 replayed FALSE here');

        TenantContext::set($this->orgB->id);
        $this->assertTrue($this->voter->isVoterInElection($election),
            'TE3 step 3 (ambient B again): must be TRUE');

        TenantContext::set($this->orgA->id);
        $this->assertTrue($this->voter->isVoterInElection($election),
            'TE3 step 4 (ambient A again): must be TRUE — identical answer throughout');
    }

    public function test_te4_entry_projection_reports_entitlement_under_different_ambient(): void
    {
        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->orgB->id])
            ->get(route('elections.show', ['slug' => $this->electionA->slug]));

        $response->assertInertia(fn ($page) => $page->where('isEligible', true));
    }

    public function test_te5_non_member_election_is_false_under_every_ambient(): void
    {
        // Voter is admitted to election A only; election Y grants nothing —
        // and that negative is equally ambient-invariant.
        $electionY = Election::factory()
            ->forOrganisation($this->orgA)
            ->create([
                'type'                  => 'real',
                'status'                => 'active',
                'voter_source_strategy' => 'election_only',
            ]);

        TenantContext::set($this->orgA->id);
        $this->assertFalse($this->voter->isVoterInElection($electionY->id),
            'ENT negative: not admitted → FALSE (ambient A)');

        TenantContext::set($this->orgB->id);
        $this->assertFalse($this->voter->isVoterInElection($electionY->id),
            'ENT negative: not admitted → FALSE (ambient B)');
    }

    public function test_te6_soft_deleted_election_does_not_resolve_entitlement(): void
    {
        // The organisation lookup bypasses ONLY the 'tenant' scope — SoftDeletes
        // stays in force, so a soft-deleted election is not authoritative and
        // the predicate fails closed (under every ambient value).
        TenantContext::set($this->orgA->id);
        $this->assertTrue($this->voter->isVoterInElection($this->electionA->id),
            'Precondition: entitled while the election is live');

        $this->electionA->delete(); // soft delete
        $this->voter->invalidateVoterCache($this->electionA->id);

        TenantContext::set($this->orgA->id);
        $this->assertFalse($this->voter->isVoterInElection($this->electionA->id),
            'A soft-deleted election must not resolve voting entitlement (ambient A)');

        $this->voter->invalidateVoterCache($this->electionA->id);
        TenantContext::set($this->orgB->id);
        $this->assertFalse($this->voter->isVoterInElection($this->electionA->id),
            'A soft-deleted election must not resolve voting entitlement (ambient B)');
    }
}
