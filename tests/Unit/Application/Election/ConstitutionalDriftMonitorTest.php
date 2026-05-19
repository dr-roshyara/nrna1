<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Monitoring\SSOTViolationEvent;
use App\Application\Election\Monitoring\ConstitutionalDriftMonitor;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;

class ConstitutionalDriftMonitorTest extends TestCase
{
    /**
     * @test
     * STREAM 1: RED — SSOTViolationEvent must be JsonSerializable
     */
    public function ssot_violation_event_is_json_serializable()
    {
        $event = SSOTViolationEvent::make(
            'deprecated_field_access',
            'application',
            'TestContext'
        );

        $this->assertInstanceOf(\JsonSerializable::class, $event);

        $json = $event->jsonSerialize();
        $this->assertArrayHasKey('violation_type', $json);
        $this->assertArrayHasKey('layer', $json);
        $this->assertArrayHasKey('context', $json);
        $this->assertArrayHasKey('timestamp', $json);

        $this->assertSame('deprecated_field_access', $json['violation_type']);
        $this->assertSame('application', $json['layer']);
        $this->assertSame('TestContext', $json['context']);
        $this->assertIsString($json['timestamp']);
    }

    /**
     * @test
     * STREAM 2: RED — DriftMonitor logs to constitutional_integrity channel
     */
    public function drift_monitor_logs_to_constitutional_integrity_channel()
    {
        Log::spy();

        $monitor = new ConstitutionalDriftMonitor();
        $monitor->record(SSOTViolationEvent::make(
            'deprecated_field_access',
            'application',
            'TestContext'
        ));

        Log::shouldHaveReceived('channel')->with('constitutional_integrity');
    }

    /**
     * @test
     * STREAM 2: RED — DriftMonitor does not throw on record
     */
    public function drift_monitor_does_not_throw_on_record()
    {
        Log::fake();

        $monitor = new ConstitutionalDriftMonitor();

        $this->expectNotToPerformAssertions();
        $monitor->record(SSOTViolationEvent::make(
            'deprecated_field_access',
            'deprecation_access',
            'TestContext'
        ));
    }

    /**
     * @test
     * STREAM 2: RED — DriftMonitor logs violation type and layer
     */
    public function drift_monitor_logs_violation_type_and_layer()
    {
        Log::fake();

        $monitor = new ConstitutionalDriftMonitor();
        $monitor->record(SSOTViolationEvent::make(
            'deprecated_field_access',
            'deprecation_access',
            'ElectionVotingController'
        ));

        Log::assertLogged('warning', function ($message, $context) {
            return isset($context['violation_type']) &&
                   $context['violation_type'] === 'deprecated_field_access' &&
                   isset($context['layer']) &&
                   $context['layer'] === 'deprecation_access';
        });
    }
}
