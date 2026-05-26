<?php

namespace Tests\Feature\Election\Security;

use App\Models\Election;
use App\Models\ElectionSecurityEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SecuritySchemaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function election_has_network_binding_strategy_column(): void
    {
        $election = Election::factory()->create([
            'network_binding_strategy' => 'ip_strict',
        ]);

        $this->assertEquals('ip_strict', $election->network_binding_strategy);
    }

    #[Test]
    public function election_has_device_binding_strategy_column(): void
    {
        $election = Election::factory()->create([
            'device_binding_strategy' => 'fingerprint_required',
        ]);

        $this->assertEquals('fingerprint_required', $election->device_binding_strategy);
    }

    #[Test]
    public function election_has_max_votes_per_ip_column(): void
    {
        $election = Election::factory()->create([
            'max_votes_per_ip' => 6,
        ]);

        $this->assertEquals(6, $election->max_votes_per_ip);
    }

    #[Test]
    public function election_has_ballot_authorization_protocol_column(): void
    {
        $election = Election::factory()->create([
            'ballot_authorization_protocol' => 'dual_code',
        ]);

        $this->assertEquals('dual_code', $election->ballot_authorization_protocol);
    }

    #[Test]
    public function election_has_trust_overlay_columns(): void
    {
        $election = Election::factory()->create([
            'trust_overlay_active' => true,
            'trust_overlay_priority' => 'emergency',
            'trust_overlay_reason' => 'Suspicious activity detected',
        ]);

        $this->assertTrue($election->trust_overlay_active);
        $this->assertEquals('emergency', $election->trust_overlay_priority);
        $this->assertEquals('Suspicious activity detected', $election->trust_overlay_reason);
    }

    #[Test]
    public function election_security_events_table_exists(): void
    {
        $election = Election::factory()->create();

        $event = ElectionSecurityEvent::create([
            'event_type' => 'trust_allowed',
            'election_id' => $election->id,
            'voter_slug_id' => 'slug_123',
            'network_evidence' => ['ip_hash' => 'hash123'],
            'device_evidence' => ['fingerprint_hash' => 'fprint123'],
            'trust_level_before' => 'unverified',
            'trust_level_after' => 'attested',
            'policy_evaluated' => 'verification_attestation',
            'overlay_applied' => null,
            'policy_evaluation_sequence' => ['verification' => 'passed'],
            'overlay_influence_chain' => [],
            'trust_state_transition' => 'unverified → attested',
            'final_constitutional_outcome' => 'allow',
            'retention_days' => 730,
            'recorded_at' => now(),
        ]);

        $this->assertDatabaseHas('election_security_events', [
            'id' => $event->id,
            'event_type' => 'trust_allowed',
            'election_id' => $election->id,
        ]);
    }

    #[Test]
    public function election_security_event_cannot_be_updated(): void
    {
        $election = Election::factory()->create();

        $event = ElectionSecurityEvent::create([
            'event_type' => 'trust_allowed',
            'election_id' => $election->id,
            'voter_slug_id' => 'slug_123',
            'network_evidence' => [],
            'device_evidence' => [],
            'trust_level_before' => 'unverified',
            'trust_level_after' => 'attested',
            'policy_evaluated' => 'verification_attestation',
            'overlay_applied' => null,
            'policy_evaluation_sequence' => [],
            'overlay_influence_chain' => [],
            'trust_state_transition' => 'unverified → attested',
            'final_constitutional_outcome' => 'allow',
            'retention_days' => 730,
            'recorded_at' => now(),
        ]);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('ElectionSecurityEvent is append-only');

        $event->update(['event_type' => 'trust_denied']);
    }

    #[Test]
    public function election_security_event_cannot_be_deleted(): void
    {
        $election = Election::factory()->create();

        $event = ElectionSecurityEvent::create([
            'event_type' => 'trust_allowed',
            'election_id' => $election->id,
            'voter_slug_id' => 'slug_123',
            'network_evidence' => [],
            'device_evidence' => [],
            'trust_level_before' => 'unverified',
            'trust_level_after' => 'attested',
            'policy_evaluated' => 'verification_attestation',
            'overlay_applied' => null,
            'policy_evaluation_sequence' => [],
            'overlay_influence_chain' => [],
            'trust_state_transition' => 'unverified → attested',
            'final_constitutional_outcome' => 'allow',
            'retention_days' => 730,
            'recorded_at' => now(),
        ]);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('ElectionSecurityEvent is append-only');

        $event->delete();
    }

    #[Test]
    public function election_security_event_retention_days_defaults_to_730(): void
    {
        $election = Election::factory()->create();

        $event = ElectionSecurityEvent::create([
            'event_type' => 'trust_allowed',
            'election_id' => $election->id,
            'voter_slug_id' => null,
            'network_evidence' => [],
            'device_evidence' => [],
            'trust_level_before' => 'unverified',
            'trust_level_after' => 'attested',
            'policy_evaluated' => 'verification_attestation',
            'overlay_applied' => null,
            'policy_evaluation_sequence' => [],
            'overlay_influence_chain' => [],
            'trust_state_transition' => 'unverified → attested',
            'final_constitutional_outcome' => 'allow',
            'recorded_at' => now(),
        ]);

        $this->assertEquals(730, $event->retention_days);
    }
}
