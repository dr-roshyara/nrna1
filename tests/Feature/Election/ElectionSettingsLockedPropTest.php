<?php

namespace Tests\Feature\Election;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-254 — RED: the Settings page knows whether the security policy is locked (PO 2026-10-06: "lock locked
 * fields + show errors"). The same lifecycle fact the update refuses on (ElectionLifecycle::isLocked, PBDIGIT-244).
 *
 *   K-P1 setup administration → securityPolicyLocked = false
 *   K-P2 voting active        → securityPolicyLocked = true
 * Synthetic data only.
 */
class ElectionSettingsLockedPropTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private function settingsPage($election)
    {
        return $this->actingAs($this->officer($election, 'chief'))
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.settings.edit', $election->slug));
    }

    public function test_k_p1_in_setup_administration_the_security_policy_is_not_locked(): void
    {
        $this->makeOrganisation();
        $election = ElectionScenarioFactory::setupAdministration($this->org);

        $this->settingsPage($election)->assertInertia(fn ($p) => $p->where('securityPolicyLocked', false));
    }

    public function test_k_p2_while_voting_the_security_policy_is_locked(): void
    {
        $this->makeOrganisation();
        $election = ElectionScenarioFactory::votingActive($this->org);

        $this->settingsPage($election)->assertInertia(fn ($p) => $p->where('securityPolicyLocked', true));
    }
}
