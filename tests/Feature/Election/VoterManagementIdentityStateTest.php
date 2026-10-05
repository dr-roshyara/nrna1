<?php

namespace Tests\Feature\Election;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C4-B — the voter-management page shows each voter's Identity
 * Verification state (a display read-model; never an authorisation source).
 *
 * States are the EVIDENCE state, independent of the policy (PO, 2026-10-04):
 * verified (active evidence) · verification_required (no evidence) ·
 * reverification_required (revoked). Whether verification is REQUIRED is the
 * separate `admission.required`.
 * bulkVerifiableCount = the C4-D1 set: active voters with NO evidence row at all.
 */
class VoterManagementIdentityStateTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private function managePage($chief, $election)
    {
        return $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get($this->voterRoute('elections.voters.index', $election));
    }

    public function test_with_identity_verification_on_each_voter_shows_their_state(): void
    {
        $this->makeOrganisation();
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief    = $this->officer($election, 'chief');
        $this->requireIdentityVerification($election);

        $unverified = $this->registeredVoter($election);
        $verified   = $this->registeredVoter($election);
        $revoked    = $this->registeredVoter($election);
        $this->evidence($verified, $election, $chief);
        $this->evidence($revoked, $election, $chief, 'revoked', 'suspension_confirmed');

        $this->managePage($chief, $election)->assertInertia(fn ($page) => $page
            ->where('admission.required', true)
            ->where('admission.available', true)
            ->where("identityVerification.{$unverified->id}", 'verification_required')
            ->where("identityVerification.{$verified->id}", 'verified')
            ->where("identityVerification.{$revoked->id}", 'reverification_required'));
    }

    /**
     * PO correction (2026-10-04): the policy says whether identity verification is
     * REQUIRED; the evidence state says whether it HAS BEEN DONE. Policy OFF must not
     * hide existing evidence — Verify Identity / Verify All stay usable while OFF.
     */
    public function test_with_identity_verification_off_each_voter_still_shows_their_evidence_state(): void
    {
        $this->makeOrganisation();
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief    = $this->officer($election, 'chief');
        $voter    = $this->registeredVoter($election);
        $verified = $this->registeredVoter($election);
        $revoked  = $this->registeredVoter($election);
        $this->evidence($verified, $election, $chief);
        $this->evidence($revoked, $election, $chief, 'revoked', 'revoked_by_officer');

        $this->managePage($chief, $election)->assertInertia(fn ($page) => $page
            ->where('admission.required', false)
            ->where('admission.bulkVerifiableCount', 1)
            ->where("identityVerification.{$voter->id}", 'verification_required')
            ->where("identityVerification.{$verified->id}", 'verified')
            ->where("identityVerification.{$revoked->id}", 'reverification_required'));
    }

    public function test_the_bulk_verifiable_count_is_active_voters_who_never_had_evidence(): void
    {
        $this->makeOrganisation();
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief    = $this->officer($election, 'chief');
        $this->requireIdentityVerification($election);

        $this->registeredVoter($election);                                                   // counted
        $this->registeredVoter($election);                                                   // counted
        $this->evidence($this->registeredVoter($election), $election, $chief);               // verified: no
        $this->evidence($this->registeredVoter($election), $election, $chief, 'revoked', 'suspension_confirmed'); // revoked: no
        $this->registeredVoter($election, 'inactive');                                       // suspended: no
        $this->registeredVoter($election, 'invited');                                        // invited: no
        $this->registeredVoter($election, 'removed');                                        // removed: no
        $this->registeredVoter(ElectionScenarioFactory::setupAdministration($this->org));    // other election: no

        $this->managePage($chief, $election)->assertInertia(fn ($page) => $page->where('admission.bulkVerifiableCount', 2));
    }

    public function test_a_full_membership_election_does_not_offer_voter_admission(): void
    {
        $this->makeOrganisation(fullMembership: true);
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief    = $this->officer($election, 'chief');

        $this->managePage($chief, $election)->assertInertia(fn ($page) => $page->where('admission.available', false));
    }
}
