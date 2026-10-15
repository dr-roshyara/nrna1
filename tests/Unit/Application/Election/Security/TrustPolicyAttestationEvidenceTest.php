<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Application\Election\Security\VoterAttestationEvidence;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Infrastructure\Election\Security\DatabaseVoterAttestationEvidence;
use App\Models\Election;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-286 P5 (AC-10; decisions D3, D3a, D3-E, D3-P): System B's trust level comes from real verification evidence.
 * An active verification (individual, bulk or legacy) makes the voter Attested, so their effective limit L is N instead
 * of max(1, ⌊N/2⌋). Missing or revoked evidence leaves them Unverified (no new denial). A failing evidence store is never
 * read as attested: the error propagates and the existing callers fail closed.
 *
 * With P6 (D7 `>=`, NetworkTrustEvidence::exceedsLimit) a network completes exactly L votes: at N = 6, 6 Attested /
 * 3 Unverified, as approved (decision record §3.3). P5 must not be released without P6.
 *
 * Database-free: the evidence port is a fake; every database target is at a dead socket (DatabaseFreeTest).
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Application/Election/Security/TrustPolicyAttestationEvidenceTest.php
 */
final class TrustPolicyAttestationEvidenceTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;

    /** @var list<array{string, string}> every (election, user) the port was asked about */
    private array $asked = [];

    private function evidence(bool|RuntimeException $answer): VoterAttestationEvidence
    {
        $asked = &$this->asked;

        return new class($answer, $asked) implements VoterAttestationEvidence {
            public function __construct(private bool|RuntimeException $answer, private array &$asked) {}

            public function hasActiveAttestation(string $electionId, string $userId): bool
            {
                $this->asked[] = [$electionId, $userId];
                if ($this->answer instanceof RuntimeException) {
                    throw $this->answer;
                }

                return $this->answer;
            }
        };
    }

    private function attestationFor(?Election $election, ?User $user, bool|RuntimeException $answer): VerificationAttestationRecord
    {
        $this->app->instance(VoterAttestationEvidence::class, $this->evidence($answer));
        $this->app->forgetInstance(TrustPolicyEvaluator::class);
        $evaluator = $this->app->make(TrustPolicyEvaluator::class);

        return (new \ReflectionMethod($evaluator, 'buildAttestationRecord'))->invoke($evaluator, $election, $user);
    }

    /**
     * In-memory models built WITHOUT their constructor: booting Election/User runs HasAuditFields, which asks the
     * schema (a query), and this test must stay database-free. Only the id is needed.
     *
     * @template T of \Illuminate\Database\Eloquent\Model
     * @param class-string<T> $class
     * @return T
     */
    private function model(string $class, string $id): object
    {
        $model = (new \ReflectionClass($class))->newInstanceWithoutConstructor();
        $model->setRawAttributes(['id' => $id]);

        return $model;
    }

    private function election(): Election
    {
        return $this->model(Election::class, '11111111-1111-4111-8111-111111111111');
    }

    private function user(): User
    {
        return $this->model(User::class, '22222222-2222-4222-8222-222222222222');
    }

    public function test_active_verification_evidence_makes_the_voter_attested(): void
    {
        $record = $this->attestationFor($this->election(), $this->user(), true);

        $this->assertTrue($record->attested);
        $this->assertSame(TrustLevel::Attested, $record->trustLevel());
        $this->assertSame([['11111111-1111-4111-8111-111111111111', '22222222-2222-4222-8222-222222222222']], $this->asked);
    }

    public function test_missing_or_revoked_evidence_leaves_the_voter_unverified_without_a_denial(): void
    {
        // The port answers false for both missing and revoked rows (D3-E); `revoked` stays false, so the
        // verification policy raises no finding and nothing new is denied.
        $record = $this->attestationFor($this->election(), $this->user(), false);

        $this->assertFalse($record->attested);
        $this->assertSame(TrustLevel::Unverified, $record->trustLevel());
        $this->assertFalse($record->revoked);
        $this->assertTrue($record->isSatisfied());
    }

    public function test_registrar_and_revocation_are_never_set_from_the_evidence(): void
    {
        $record = $this->attestationFor($this->election(), $this->user(), true);

        $this->assertNull($record->registrarId, 'D3-E: registrarId is never set; RegistrarAttested stays unreachable');
        $this->assertFalse($record->revoked, 'D3-E: revoked is never set');
    }

    public function test_an_evidence_store_failure_is_never_read_as_attested(): void
    {
        $failure = new RuntimeException('evidence store unavailable');

        try {
            $this->attestationFor($this->election(), $this->user(), $failure);
            $outcome = 'no exception';
        } catch (RuntimeException $e) {
            $outcome = $e === $failure ? 'propagated' : 'other: '.$e->getMessage();
        }

        $this->assertSame('propagated', $outcome, 'D3-E: a read failure must reach the fail-closed callers unchanged');
    }

    public function test_without_a_voter_or_an_election_no_evidence_is_read_and_the_level_is_unverified(): void
    {
        foreach ([[null, $this->user()], [$this->election(), null], [null, null]] as [$election, $user]) {
            $record = $this->attestationFor($election, $user, true);
            $this->assertSame(TrustLevel::Unverified, $record->trustLevel());
        }

        $this->assertSame([], $this->asked);
    }

    public function test_the_effective_limit_per_reachable_level_follows_d4(): void
    {
        // D4: with N = 6, the effective limit L is 6 for Attested and max(1, ⌊6/2⌋) = 3 for Unverified. This checks
        // L only (remainingVotes = L − votes). With P6 (D7 `>=`) a network completes exactly L votes.
        $network = new NetworkTrustEvidence('hash', null, null, maxVotesPerIp: 6, votesFromThisIp: 0,
            restrictionEnabled: true, bindingStrategy: 'ip_count');

        $this->assertSame(6, $network->remainingVotes($this->attestationFor($this->election(), $this->user(), true)->trustLevel()));
        $this->assertSame(3, $network->remainingVotes($this->attestationFor($this->election(), $this->user(), false)->trustLevel()));
    }

    public function test_verification_evidence_changes_the_system_b_decision(): void
    {
        // PBDIGIT-286 AC-10 + AC-11 (P5 with P6 D7): the record the evidence produces drives NetworkBindingPolicy.
        // N = 6, so L = 3 Unverified and 6 Attested; with L completed votes the next voter is refused.
        $refused = function (bool $verified, int $completedVotes): bool {
            $ctx = new TrustCapabilityContext(
                election: null,
                user: null,
                network: new NetworkTrustEvidence('hash', null, null, maxVotesPerIp: 6, votesFromThisIp: $completedVotes,
                    restrictionEnabled: true, bindingStrategy: 'ip_count'),
                device: new DeviceTrustContext(fingerprintHash: null, registeredFingerprintHash: null,
                    matchType: FingerprintMatchType::NotRequired, captureMethod: 'none', volatility: 'stable'),
                attestation: $this->attestationFor($this->election(), $this->user(), $verified),
                sessionContinuity: new VotingSessionTrustContinuity(sessionId: 's1', ipHashAtStart: 'hash',
                    ipHashCurrent: 'hash', deviceChanged: false, continuityState: 'continuous'),
            );

            return (new NetworkBindingPolicy())->evaluate($ctx)->hasConstitutionalConcern();
        };

        $this->assertFalse($refused(false, 2), 'Unverified, one below L = 3: admitted');
        $this->assertTrue($refused(false, 3), 'Unverified, at L = 3: refused');
        $this->assertFalse($refused(true, 3), 'Attested, 3 completed votes: admitted (the evidence raised L to 6)');
        $this->assertFalse($refused(true, 5), 'Attested, one below L = 6: admitted');
        $this->assertTrue($refused(true, 6), 'Attested, at L = 6: refused');
    }

    public function test_the_container_wires_the_database_adapter(): void
    {
        $this->assertInstanceOf(DatabaseVoterAttestationEvidence::class, $this->app->make(VoterAttestationEvidence::class));
    }

    public function test_the_database_adapter_does_not_swallow_a_read_failure(): void
    {
        // D3-E (review F4): the real adapter, with every database target at the dead socket, must throw. Returning
        // false would read an unavailable store as "not verified" instead of failing closed.
        $adapter = $this->app->make(DatabaseVoterAttestationEvidence::class);

        try {
            $outcome = 'returned '.var_export($adapter->hasActiveAttestation('11111111-1111-4111-8111-111111111111', '22222222-2222-4222-8222-222222222222'), true);
        } catch (QueryException|\PDOException) {
            $outcome = 'propagated';
        }

        $this->assertSame('propagated', $outcome);
    }
}
