<?php

namespace Tests\Unit\Domain\Election;

use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use PHPUnit\Framework\TestCase;

/**
 * Phase 1: Constitutional Guard Layer
 * Test: ElectionConstitution Rules Registry
 *
 * RED tests for the centralized definition of all allowed state transitions
 * and required roles for each action.
 */
class ElectionConstitutionTest extends TestCase
{
    /**
     * Test: Constitution has rules for all actions
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function constitution_has_rules_for_all_actions(): void
    {
        $this->assertNotEmpty(ElectionConstitution::RULES);
        $this->assertArrayHasKey('submit_for_approval', ElectionConstitution::RULES);
        $this->assertArrayHasKey('complete_administration', ElectionConstitution::RULES);
        $this->assertArrayHasKey('complete_nomination', ElectionConstitution::RULES);
        $this->assertArrayHasKey('open_voting', ElectionConstitution::RULES);
        $this->assertArrayHasKey('close_voting', ElectionConstitution::RULES);
        $this->assertArrayHasKey('publish_results', ElectionConstitution::RULES);
        $this->assertArrayHasKey('archive', ElectionConstitution::RULES);
    }

    /**
     * Test: getRulesForAction returns rule structure
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_rules_for_action_returns_rule_structure(): void
    {
        $rules = ElectionConstitution::getRulesForAction('open_voting');

        $this->assertArrayHasKey('allowed_states', $rules);
        $this->assertArrayHasKey('allowed_roles', $rules);
        $this->assertArrayHasKey('preconditions', $rules);
        $this->assertIsArray($rules['allowed_states']);
        $this->assertIsArray($rules['allowed_roles']);
        $this->assertIsArray($rules['preconditions']);
    }

    /**
     * Test: isActionAllowedInState checks state membership
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function is_action_allowed_in_state_checks_state(): void
    {
        $this->assertTrue(
            ElectionConstitution::isActionAllowedInState(
                'open_voting',
                ElectionLifecycleState::ReadyForVoting
            )
        );

        $this->assertFalse(
            ElectionConstitution::isActionAllowedInState(
                'open_voting',
                ElectionLifecycleState::Draft
            )
        );
    }

    /**
     * Test: Only committees (chief, deputy) can administer
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function administration_requires_committee_roles(): void
    {
        $administrationRoles = ElectionConstitution::getAllowedRolesForAction('complete_administration');

        $this->assertContains('chief', $administrationRoles);
        $this->assertContains('deputy', $administrationRoles);
        $this->assertCount(2, $administrationRoles);
    }

    /**
     * Test: Only chief can open voting
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function only_chief_can_open_voting(): void
    {
        $votingRoles = ElectionConstitution::getAllowedRolesForAction('open_voting');

        $this->assertContains('chief', $votingRoles);
        $this->assertCount(1, $votingRoles);
    }

    /**
     * Test: complete_administration has preconditions
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function complete_administration_has_preconditions(): void
    {
        $preconditions = ElectionConstitution::getPreconditionsForAction('complete_administration');

        $this->assertNotEmpty($preconditions);
        $this->assertContains('has_posts', $preconditions);
        $this->assertContains('has_voters', $preconditions);
        $this->assertContains('has_chief', $preconditions);
    }

    /**
     * Test: open_voting requires voting_window_defined
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function open_voting_requires_voting_window(): void
    {
        $preconditions = ElectionConstitution::getPreconditionsForAction('open_voting');

        $this->assertContains('voting_window_defined', $preconditions);
    }

    /**
     * Test: Unknown action throws exception
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function unknown_action_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        ElectionConstitution::getRulesForAction('invalid_action');
    }
}
