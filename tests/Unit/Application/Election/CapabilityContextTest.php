<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\User;
use Tests\TestCase;

class CapabilityContextTest extends TestCase
{
    private Election $election;
    private ?User $user;
    private ElectionLifecycleState $state;

    protected function setUp(): void
    {
        parent::setUp();
        $this->election = new Election();
        $this->user = new User();
        $this->state = ElectionLifecycleState::Draft;
    }

    public function test_context_with_all_properties(): void
    {
        $metadata = [
            'allowed_roles' => ['chief', 'deputy'],
            'allowed_states' => ['setup', 'nomination'],
        ];

        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'open_voting',
            $metadata,
            $this->state,
        );

        $this->assertSame($this->election, $context->election);
        $this->assertSame($this->user, $context->user);
        $this->assertEquals('open_voting', $context->action);
        $this->assertEquals($metadata, $context->actionMetadata);
        $this->assertEquals($this->state, $context->state);
    }

    public function test_context_with_null_user(): void
    {
        $context = new CapabilityContext(
            $this->election,
            null,
            'auto_submit',
            [],
            $this->state,
        );

        $this->assertNull($context->user);
        $this->assertFalse($context->isUserAuthenticated());
    }

    public function test_is_system_action(): void
    {
        $metadata = ['allowed_roles' => ['system']];
        $context = new CapabilityContext($this->election, null, 'auto_submit', $metadata, $this->state);

        $this->assertTrue($context->isSystemAction());
    }

    public function test_is_system_action_false_with_multiple_roles(): void
    {
        $metadata = ['allowed_roles' => ['system', 'chief']];
        $context = new CapabilityContext($this->election, $this->user, 'open_voting', $metadata, $this->state);

        $this->assertFalse($context->isSystemAction());
    }

    public function test_is_user_authenticated(): void
    {
        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'open_voting',
            [],
            $this->state,
        );

        $this->assertTrue($context->isUserAuthenticated());
    }

    public function test_is_user_not_authenticated(): void
    {
        $context = new CapabilityContext($this->election, null, 'auto_submit', [], $this->state);

        $this->assertFalse($context->isUserAuthenticated());
    }

    public function test_action_requires_role(): void
    {
        $metadata = ['allowed_roles' => ['chief', 'deputy']];
        $context = new CapabilityContext($this->election, $this->user, 'open_voting', $metadata, $this->state);

        $this->assertTrue($context->actionRequiresRole('chief'));
        $this->assertTrue($context->actionRequiresRole('deputy'));
        $this->assertFalse($context->actionRequiresRole('admin'));
    }

    public function test_action_requires_role_empty_roles(): void
    {
        $metadata = ['allowed_roles' => []];
        $context = new CapabilityContext($this->election, $this->user, 'auto_submit', $metadata, $this->state);

        $this->assertFalse($context->actionRequiresRole('chief'));
    }

    public function test_is_action_allowed_in_state_with_matching_state(): void
    {
        $metadata = [
            'allowed_states' => ['draft', 'setup_administration', 'voting_active'],
        ];
        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'open_voting',
            $metadata,
            ElectionLifecycleState::Draft,
        );

        $this->assertTrue($context->isActionAllowedInState());
    }

    public function test_is_action_allowed_in_state_with_non_matching_state(): void
    {
        $metadata = [
            'allowed_states' => ['setup_nomination', 'voting_active'],
        ];
        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'open_voting',
            $metadata,
            ElectionLifecycleState::Draft,
        );

        $this->assertFalse($context->isActionAllowedInState());
    }

    public function test_is_action_allowed_in_state_empty_allowed_states(): void
    {
        $metadata = ['allowed_states' => []];
        $context = new CapabilityContext($this->election, $this->user, 'open_voting', $metadata, $this->state);

        $this->assertFalse($context->isActionAllowedInState());
    }

    public function test_enum_state_value_comparison(): void
    {
        $metadata = ['allowed_states' => [ElectionLifecycleState::Draft->value, 'nomination']];
        $context = new CapabilityContext($this->election, $this->user, 'open_voting', $metadata, $this->state);

        $this->assertTrue($context->isActionAllowedInState());
    }
}
