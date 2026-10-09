<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational\Support;

use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\InvariantSet;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RecordedFact;
use App\Contexts\Membership\Domain\Organisational\ResolvedMember;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * One command history applied to production (Member) and mirrored for the
 * independent PointwiseOracle. Commands use "domain values" v; production and
 * oracle both see ordinal 2v + shift, so odd ordinals lie strictly between
 * breakpoints (contract Lemma 4). The shift supports the time-translation
 * metamorphic test.
 *
 * Command: ['A', start, termUntil|null|'inf'] or ['E', periodNumber, end].
 */
final class DualHistory
{
    public Member $member;
    /** @var list<array> */
    public array $oracleFacts = [];
    /** @var list<array> applied commands, for failure reports */
    public array $applied = [];
    private int $periods = 0;
    private int $facts = 0;
    private int $clock = 0;

    public function __construct(private readonly int $shift = 0, private readonly string $idPrefix = '')
    {
        $this->member = Member::fromLog(MemberId::fromString($idPrefix . 'M'), PersonRef::fromString('P'), TenantId::fromString('O'));
    }

    public function at(int $v): int
    {
        return 2 * $v + $this->shift;
    }

    public function periodId(int $number): string
    {
        return $this->idPrefix . 'A' . $number;
    }

    /** @return array{0: bool, 1: ?string} [accepted, refusal] */
    public function apply(array $cmd): array
    {
        $provenance = Provenance::rule('verification', 'v1');
        if ($cmd[0] === 'A') {
            $id = $this->periodId($this->periods + 1);
            $until = match (true) {
                $cmd[2] === null => null,
                $cmd[2] === 'inf' => TimePoint::infinity(),
                default => TimePoint::of($this->at($cmd[2])),
            };
            $decision = $this->member->decideAdmit(FactId::fromString($id), TimePoint::of($this->at($cmd[1])), $until, $provenance);
            $oracle = ['A', $id, $this->at($cmd[1]), is_int($cmd[2]) ? $this->at($cmd[2]) : null, $cmd[2] === 'inf'];
        } else {
            $id = $this->idPrefix . 'E' . ($this->facts + 1);
            $decision = $this->member->decideEnd(FactId::fromString($id), PeriodId::fromString($this->periodId($cmd[1])), TimePoint::of($this->at($cmd[2])), EndKind::fromString('k'), $provenance);
            $oracle = ['E', $id, $this->periodId($cmd[1]), $this->at($cmd[2])];
        }
        if (!$decision->isAccepted()) {
            return [false, $decision->refusal()->value];
        }
        $log = $this->member->log();
        $log[] = RecordedFact::of($decision->basedOnVersion() + 1, Instant::of(++$this->clock), $decision->fact());
        $this->member = Member::fromLog($this->member->id(), $this->member->holder(), $this->member->organisation(), ...$log);
        $this->oracleFacts[] = $oracle;
        $this->applied[] = $cmd;
        $this->facts++;
        if ($cmd[0] === 'A') {
            $this->periods++;
        }

        return [true, null];
    }

    public function periodCount(): int
    {
        return $this->periods;
    }

    public function clone(): self
    {
        return clone $this;
    }

    /** Lemma 4 grid: every ordinal from below the first to above the last breakpoint (odd ones are midpoints). */
    public function grid(int $maxV): array
    {
        return range($this->at(0) - 1, $this->at($maxV + 1) + 1);
    }

    /**
     * Compares production with the oracle at every grid point and checks the invariants.
     *
     * @return list<string> mismatches / violations (empty = consistent)
     */
    public function check(int $maxV): array
    {
        $problems = [];
        $resolved = $this->member->resolve();
        $oracle = new PointwiseOracle($this->oracleFacts);
        if (InvariantSet::violations($resolved) !== []) {
            $problems[] = 'invariant set: ' . implode(',', array_map(static fn ($r) => $r->value, InvariantSet::violations($resolved)));
        }
        foreach ($this->grid($maxV) as $t) {
            $tp = TimePoint::of($t);
            foreach ($oracle->periods() as $p) {
                $rp = $resolved->period(PeriodId::fromString($p));
                $pairs = [
                    'inLifetime' => [$rp->inLifetime($tp), $oracle->inLifetime($p, $t)],
                    'ended' => [$rp->isEnded($tp), $oracle->ended($p, $t)],
                    'termCovered' => [$rp->isTermCovered($tp), $oracle->termCovered($p, $t)],
                    'inForce' => [$rp->isInForce($tp), $oracle->inForce($p, $t)],
                    'lapsed' => [$rp->isLapsed($tp), $oracle->lapsed($p, $t)],
                ];
                foreach ($pairs as $name => [$prod, $orc]) {
                    if ($prod !== $orc) {
                        $problems[] = "$name($p,$t): production " . var_export($prod, true) . ' oracle ' . var_export($orc, true);
                    }
                }
                if ($rp->isEnded($tp) && $rp->isInForce($tp)) {
                    $problems[] = "INV-2 violated at ($p,$t)";
                }
                if ($rp->isLapsed($tp) && $rp->isEnded($tp)) {
                    $problems[] = "INV-3 violated at ($p,$t)";
                }
            }
            if ($resolved->isMember($tp) !== $oracle->isMember($t)) {
                $problems[] = "isMember($t) differs";
            }
            $at = $oracle->periodsAt($t);
            if (count($at) > 1) {
                $problems[] = "INV-9 violated at $t";
            }
            if (($resolved->currentPeriod($tp)?->value()) !== ($at[0] ?? null)) {
                $problems[] = "currentPeriod($t) differs";
            }
        }

        return $problems;
    }

    public function oracle(): PointwiseOracle
    {
        return new PointwiseOracle($this->oracleFacts);
    }

    public function resolved(): ResolvedMember
    {
        return $this->member->resolve();
    }
}
