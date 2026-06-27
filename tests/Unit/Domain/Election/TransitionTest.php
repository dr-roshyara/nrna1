<?php

namespace Tests\Unit\Domain\Election;

use PHPUnit\Framework\TestCase;
use App\Domain\Election\StateMachine\Transition;
use App\Domain\Election\StateMachine\TransitionTrigger;
use InvalidArgumentException;

class TransitionTest extends TestCase
{
    /**
     * Test: actorId (int) is cast to string
     */
    public function test_actor_id_int_is_cast_to_string(): void
    {
        $transition = Transition::manual('open_voting', 42, 'Test');

        $this->assertIsString($transition->actorId);
        $this->assertEquals('42', $transition->actorId);
    }

    /**
     * Test: null actorId defaults to 'system'
     */
    public function test_null_actor_id_defaults_to_system(): void
    {
        $transition = new Transition('open_voting', null);

        $this->assertEquals('system', $transition->actorId);
    }

    /**
     * Test: empty action throws InvalidArgumentException
     */
    public function test_empty_action_throws_invalid_argument_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Transition action cannot be empty.');

        new Transition('   ', 1);
    }

    /**
     * Test: trigger defaults to MANUAL
     */
    public function test_trigger_defaults_to_manual(): void
    {
        $transition = new Transition('open_voting', 1);

        $this->assertEquals(TransitionTrigger::MANUAL, $transition->trigger);
    }

    /**
     * Test: manual factory sets correct trigger
     */
    public function test_manual_factory_sets_correct_trigger(): void
    {
        $transition = Transition::manual('open_voting', 1, 'Test');

        $this->assertEquals(TransitionTrigger::MANUAL, $transition->trigger);
        $this->assertEquals('1', $transition->actorId);
    }

    /**
     * Test: automatic factory sets system actor and TIME trigger
     */
    public function test_automatic_factory_sets_system_actor(): void
    {
        $transition = Transition::automatic('close_voting');

        $this->assertEquals('system', $transition->actorId);
        $this->assertEquals(TransitionTrigger::TIME, $transition->trigger);
    }

    /**
     * Test: automatic factory with custom trigger
     */
    public function test_automatic_factory_with_custom_trigger(): void
    {
        $transition = Transition::automatic(
            'close_voting',
            TransitionTrigger::GRACE_PERIOD,
            'Grace period expired'
        );

        $this->assertEquals(TransitionTrigger::GRACE_PERIOD, $transition->trigger);
        $this->assertEquals('system', $transition->actorId);
    }

    /**
     * Test: grace_period factory sets grace_period trigger
     */
    public function test_grace_period_factory_sets_grace_period_trigger(): void
    {
        $transition = Transition::gracePeriod('close_voting', 'Grace period ended');

        $this->assertEquals(TransitionTrigger::GRACE_PERIOD, $transition->trigger);
        $this->assertEquals('system', $transition->actorId);
    }

    /**
     * Test: withMetadata returns new immutable instance
     */
    public function test_with_metadata_returns_new_immutable_instance(): void
    {
        $transition1 = Transition::manual('open_voting', 1, 'Test');
        $transition2 = $transition1->withMetadata('ip', '192.168.1.1');

        // Different instances
        $this->assertNotSame($transition1, $transition2);

        // Original unchanged
        $this->assertEmpty($transition1->metadata);

        // New instance has metadata
        $this->assertEquals(['ip' => '192.168.1.1'], $transition2->metadata);
    }

    /**
     * Test: getMetadata returns value or default
     */
    public function test_get_metadata_returns_value_or_default(): void
    {
        $transition = Transition::manual('open_voting', 1, 'Test', metadata: ['key' => 'value']);

        $this->assertEquals('value', $transition->getMetadata('key'));
        $this->assertEquals('default', $transition->getMetadata('missing', 'default'));
        $this->assertNull($transition->getMetadata('missing'));
    }

    /**
     * Test: isSystemTriggered returns true for system actor
     */
    public function test_is_system_triggered_returns_true_for_system_actor(): void
    {
        $systemTransition = Transition::automatic('close_voting');
        $manualTransition = Transition::manual('close_voting', 1);

        $this->assertTrue($systemTransition->isSystemTriggered());
        $this->assertFalse($manualTransition->isSystemTriggered());
    }

    /**
     * Test: readonly properties prevent modification
     */
    public function test_readonly_properties_prevent_modification(): void
    {
        $transition = Transition::manual('open_voting', 1);

        $this->expectException(\Error::class);
        $transition->action = 'close_voting'; // @phpstan-ignore-line
    }

    /**
     * Test: metadata can be chained for multiple additions
     */
    public function test_metadata_can_be_chained(): void
    {
        $transition = Transition::manual('open_voting', 1)
            ->withMetadata('ip', '192.168.1.1')
            ->withMetadata('user_agent', 'Mozilla/5.0');

        $this->assertEquals('192.168.1.1', $transition->getMetadata('ip'));
        $this->assertEquals('Mozilla/5.0', $transition->getMetadata('user_agent'));
    }

    /**
     * Test: trigger value can be accessed
     */
    public function test_trigger_value_can_be_accessed(): void
    {
        $transition = Transition::manual('open_voting', 1);

        $this->assertEquals('manual', $transition->trigger->value);
    }
}
