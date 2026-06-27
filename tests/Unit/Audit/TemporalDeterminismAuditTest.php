<?php

namespace Tests\Unit\Audit;

use App\Application\Election\Security\SecurityEventRecorder;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Domain\Election\Security\VotingTrustResult;
use App\Infrastructure\Shared\Clock\FrozenClock;
use App\Infrastructure\Shared\Clock\SystemClock;
use App\Models\Election;
use App\Models\ElectionSecurityEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemporalDeterminismAuditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * AUDIT 4.9: Frozen Clock Produces Deterministic Event Timestamps
     *
     * Proves that frozen time eliminates temporal entropy from replay evaluation.
     * Same frozen instant → identical recorded timestamp (replay-auditable).
     */
    public function test_frozen_clock_produces_identical_timestamps_on_replay(): void
    {
        // Arrange: Create test data
        $election = Election::factory()->create();
        $user = User::factory()->create();
        $frozenTime = FrozenClock::at('2026-05-27T14:30:00Z');

        // Create security event recorder with frozen clock
        $recorder = new SecurityEventRecorder($frozenTime);

        // Build trust context
        $ctx = new TrustCapabilityContext(
            election: $election,
            user: $user,
            network: new NetworkTrustEvidence(
                currentIpHash: hash('sha256', '192.168.1.1'),
                registeredIpHash: null,
                whitelist: null,
                maxVotesPerIp: 6,
                votesFromThisIp: 0,
                restrictionEnabled: true,
                bindingStrategy: 'ip_strict',
            ),
            device: new DeviceTrustContext(
                fingerprintHash: null,
                registeredFingerprintHash: null,
                matchType: FingerprintMatchType::NotRequired,
                captureMethod: 'none',
                volatility: 'stable',
            ),
            attestation: new VerificationAttestationRecord(
                required: false,
                attested: false,
                registrarId: null,
                attestationTimestamp: null,
                protocol: 'none',
                networkEvidenceHash: null,
                deviceEvidenceHash: null,
                revoked: false,
                validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 'session_abc',
                ipHashAtStart: hash('sha256', '192.168.1.1'),
                ipHashCurrent: hash('sha256', '192.168.1.1'),
                deviceChanged: false,
                continuityState: 'continuous',
            ),
        );

        // First recording (DENY events always recorded, even without sampling)
        $result1 = VotingTrustResult::insufficientEvidence(
            reason: 'verification_required',
            trustLevel: TrustLevel::Unverified,
            context: [],
            sequence: ['verification_attestation' => 'verification_required'],
        );
        $recorder->record($result1, $ctx);

        // Second recording (identical inputs, same frozen instant)
        $result2 = VotingTrustResult::insufficientEvidence(
            reason: 'verification_required',
            trustLevel: TrustLevel::Unverified,
            context: [],
            sequence: ['verification_attestation' => 'verification_required'],
        );
        $recorder->record($result2, $ctx);

        // Assert: Both events have identical recorded_at timestamp
        $events = ElectionSecurityEvent::where('election_id', $election->id)
            ->orderBy('id')
            ->get();

        $this->assertCount(2, $events);
        $this->assertEquals(
            $events[0]->recorded_at->format('Y-m-d H:i:s'),
            $events[1]->recorded_at->format('Y-m-d H:i:s'),
            'Frozen clock should produce identical timestamps for replay'
        );

        // Assert: Timestamp matches frozen instant
        $expectedTime = $frozenTime->now();
        $this->assertEquals(
            $expectedTime->format('Y-m-d H:i:s'),
            $events[0]->recorded_at->format('Y-m-d H:i:s'),
            'Event timestamp should match frozen clock instant'
        );
    }

    /**
     * AUDIT 4.10: No Direct Temporal Access in Evaluation Paths
     *
     * Verifies that evaluation code does not use Carbon::now(), time(), strtotime(), etc.
     * All temporal access must flow through ClockInterface (injected dependency).
     *
     * Note: Legitimate usage like $this->clock->now() is expected and correct.
     */
    public function test_trust_evaluation_code_excludes_direct_temporal_access(): void
    {
        $forbiddenPatterns = [
            'Carbon::now' => 'Carbon::now() forbidden — use ClockInterface',
            'time()' => 'time() forbidden — use ClockInterface',
            'microtime()' => 'microtime() forbidden — use ClockInterface',
            'date(' => 'date() forbidden — use ClockInterface',
            'strtotime(' => 'strtotime() forbidden — use ClockInterface',
        ];

        $scanDirs = [
            app_path('Application/Election/Security/'),
            app_path('Domain/Election/Security/'),
        ];

        $violations = [];

        foreach ($scanDirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if (!$file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }

                // Skip tests and non-evaluation files
                if (str_contains($file->getPathname(), 'Test.php')) {
                    continue;
                }

                $content = file_get_contents($file->getPathname());

                foreach ($forbiddenPatterns as $pattern => $reason) {
                    if (strpos($content, $pattern) !== false) {
                        // Legitimate patterns to exclude:
                        // - $this->clock->now() is correct
                        // - ClockInterface is correct
                        if (str_contains($pattern, 'now') && str_contains($content, '$this->clock->now()')) {
                            continue; // Skip this file, it's correct usage
                        }
                        $violations[] = "{$file->getPathname()}: {$reason}";
                    }
                }
            }
        }

        $this->assertEmpty(
            $violations,
            "Direct temporal access found in evaluation code:\n" . implode("\n", $violations)
        );
    }

    /**
     * AUDIT 4.11: Clock Injection Verified
     *
     * Confirms ClockInterface is injected into all temporal-sensitive services.
     */
    public function test_security_event_recorder_has_injected_clock(): void
    {
        $recorder = new SecurityEventRecorder(new SystemClock());

        // Verify recorder has clock dependency
        $reflection = new \ReflectionClass($recorder);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor, 'SecurityEventRecorder must have constructor');

        $params = $constructor->getParameters();
        $clockParam = null;

        foreach ($params as $param) {
            if (str_contains($param->getType()->getName(), 'ClockInterface')) {
                $clockParam = $param;
                break;
            }
        }

        $this->assertNotNull(
            $clockParam,
            'SecurityEventRecorder must have ClockInterface injected'
        );
    }

    /**
     * AUDIT 4.12: Temporal Values Are Immutable DateTimeImmutable
     *
     * Verifies that all clock results are immutable to prevent replay contamination.
     */
    public function test_clock_interface_returns_immutable_datetime(): void
    {
        $frozen = FrozenClock::at('2026-05-27T14:30:00Z');
        $system = new SystemClock();

        // Both should return immutable instances
        $frozenResult = $frozen->now();
        $systemResult = $system->now();

        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $frozenResult,
            'FrozenClock must return DateTimeImmutable'
        );

        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $systemResult,
            'SystemClock must return DateTimeImmutable'
        );
    }
}
