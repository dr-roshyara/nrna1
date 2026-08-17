<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCore;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PolicyBinding;
use App\Contexts\Election\Domain\OperatingCore\Recovery\RecoveryProcess;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use PHPUnit\Framework\TestCase;

/**
 * AG-3 `RecoveryProcess` — invariants I-13…I-16 and the DD-1 clock model:
 * clocks are computations over recorded condition intervals, never countdowns
 * (EM-ARCH-001 §2c/§2d; EM-GOV-050(b), 059(a), 060, 061, 062).
 * RED tests 18–22 of the readiness report §E.
 */
final class RecoveryProcessTest extends TestCase
{
    private function at(int $epoch): RecordedInstant
    {
        return RecordedInstant::fromEpochSeconds($epoch);
    }

    private function haltedRecovery(int $durationSeconds = 1_000, int $onsetEpoch = 0): RecoveryProcess
    {
        return RecoveryProcess::start(
            ElectionId::fromString('election-1'),
            PeriodKind::HaltedElectionRecovery,
            PolicyBinding::of('service-policy-v1', $durationSeconds),
            $this->at($onsetEpoch),
        );
    }

    /** Test 18 — I-13: PolicyBinding (version + duration) is captured at period start and never retroactively altered (EM-GOV-050(b)). */
    public function test_policy_binding_is_captured_at_period_start(): void
    {
        $process = $this->haltedRecovery(1_000);

        $binding = $process->policyBinding();
        $this->assertSame('service-policy-v1', $binding->policyVersion);
        $this->assertSame(1_000, $binding->durationSeconds);

        // The binding is immutable and the aggregate exposes no way to rebind it.
        foreach (get_class_methods($process) as $method) {
            $this->assertDoesNotMatchRegularExpression(
                '/rebind|setPolicy|updatePolicy|changePolicy/i',
                $method,
                'A later policy change never retroactively alters a running period (EM-GOV-050(b)).'
            );
        }
    }

    /**
     * Test 19 — I-14 / P-5: elapsed time accrues only while the triggering condition is active,
     * and the two clocks never accrue over the same instant (EM-GOV-062) — mutually exclusive
     * recorded conditions, not timer coordination.
     */
    public function test_clocks_accrue_only_while_their_condition_is_active_and_never_together(): void
    {
        // Fact stream: halt at t=0 · Inoperative begins t=300 · restoration at t=500.
        $halted = $this->haltedRecovery(1_000, 0);
        $halted->pause($this->at(300));   // Inoperative onset pauses the halted clock (EM-GOV-060).

        $restoration = RecoveryProcess::start(
            ElectionId::fromString('election-1'),
            PeriodKind::CommitteeRestoration,
            PolicyBinding::of('service-policy-v1', 400),
            $this->at(300),               // restoration clock runs only while Inoperative (EM-GOV-062).
        );
        $restoration->pause($this->at(500)); // restoration ends Inoperative.
        $halted->resume($this->at(500));

        // At t=650: halted accrued [0,300]+[500,650] = 450; restoration accrued [300,500] = 200.
        $this->assertSame(450, $halted->readingAt($this->at(650))->elapsedSeconds);
        $this->assertSame(200, $restoration->readingAt($this->at(650))->elapsedSeconds);

        // Disjoint by construction: together they account for every second exactly once.
        $this->assertSame(
            650,
            $halted->readingAt($this->at(650))->elapsedSeconds
                + $restoration->readingAt($this->at(650))->elapsedSeconds,
            'Operative and Inoperative are mutually exclusive — the two clocks can never run at once (EM-GOV-062).'
        );
    }

    /** Test 20 — EM-GOV-060: the pause is non-retroactive; a deadline already legitimately expired stays expired. */
    public function test_pause_is_non_retroactive_and_does_not_undo_an_expiry(): void
    {
        $process = $this->haltedRecovery(100, 0);

        // The period expired at t=100. Inoperative beginning later must not undo that.
        $this->assertTrue($process->isExpiredAt($this->at(100)));

        $process->pause($this->at(150));
        $this->assertTrue($process->isExpiredAt($this->at(200)), 'Pause is non-retroactive (EM-GOV-060).');
        $this->assertSame(0, $process->readingAt($this->at(200))->remainingSeconds);

        // And recorded history cannot be rewritten: a pause before the last activity start is refused.
        $fresh = $this->haltedRecovery(1_000, 500);
        $this->expectException(\InvalidArgumentException::class);
        $fresh->pause($this->at(400));
    }

    /**
     * Test 21 — I-15/I-16: resumption resumes the REMAINING portion only — no minimum guaranteed,
     * no time created; the allowance is per ELECTION and no repetition renews it (EM-GOV-060, 061).
     */
    public function test_resumption_resumes_the_remaining_portion_and_nothing_renews_the_allowance(): void
    {
        $process = $this->haltedRecovery(1_000, 0);

        $process->pause($this->at(600));
        $process->resume($this->at(2_000));

        // 600 elapsed before the pause; the remaining 400 resume — not a fresh 1000.
        $reading = $process->readingAt($this->at(2_000));
        $this->assertSame(600, $reading->elapsedSeconds);
        $this->assertSame(400, $reading->remainingSeconds);
        $this->assertTrue($process->isExpiredAt($this->at(2_400)));

        // A second pause/resume cycle draws on the SAME allowance; nothing renews it.
        $process->pause($this->at(2_100));
        $process->resume($this->at(3_000));
        $this->assertSame(300, $process->readingAt($this->at(3_000))->remainingSeconds);
        $this->assertTrue($process->isExpiredAt($this->at(3_300)));
        $this->assertSame(0, $process->readingAt($this->at(9_999))->remainingSeconds);

        // No API renews, restarts or extends the period.
        foreach (get_class_methods($process) as $method) {
            $this->assertDoesNotMatchRegularExpression(
                '/renew|restart|extend|reset|topUp/i',
                $method,
                'No clock manufactures new time (EM-GOV-061).'
            );
        }
    }

    /** Test 22 — EM-GOV-059(a): the two period kinds are distinct and never merged or substituted. */
    public function test_the_two_period_kinds_are_distinct_and_never_merged(): void
    {
        $kinds = PeriodKind::cases();
        $this->assertCount(2, $kinds);
        $this->assertSame(['HaltedElectionRecovery', 'CommitteeRestoration'], array_map(static fn ($k) => $k->name, $kinds));

        $halted = $this->haltedRecovery();
        $this->assertSame(PeriodKind::HaltedElectionRecovery, $halted->kind());

        $restoration = RecoveryProcess::start(
            ElectionId::fromString('election-1'),
            PeriodKind::CommitteeRestoration,
            PolicyBinding::of('service-policy-v1', 400),
            $this->at(0),
        );
        $this->assertSame(PeriodKind::CommitteeRestoration, $restoration->kind());
        $this->assertNotSame($halted->kind(), $restoration->kind());
    }
}
