<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational\Support;

use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RecordedFact;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\Assert;

/**
 * Test-side stand-in for the Slice 1a commit: appends an accepted fact at seq = version + 1.
 * Not production code; Slice 0 has no persistence.
 */
final class MemberHistory
{
    private int $clock = 1000;

    private function __construct(public Member $member)
    {
    }

    public static function start(string $memberId = 'M1', string $person = 'P1', string $organisation = 'O1'): self
    {
        return new self(Member::fromLog(
            MemberId::fromString($memberId),
            PersonRef::fromString($person),
            TenantId::fromString($organisation),
        ));
    }

    public static function provenance(): Provenance
    {
        return Provenance::decision('registrar-1');
    }

    /** D-1: an admission decided by an actor carries a declaration reference (fixture-only value). */
    public static function admissionProvenance(): Provenance
    {
        return Provenance::decision('registrar-1', null, 'decl-fixture');
    }

    /** D-5: a decision date after every time the fixtures use, so the future-dating guard never fires here. */
    public static function decisionDate(): TimePoint
    {
        return TimePoint::of(1_000_000);
    }

    public function admit(string $id, int $at, ?int $termUntil = null, bool $termIsInfinite = false): Decision
    {
        $until = $termIsInfinite ? TimePoint::infinity() : ($termUntil === null ? null : TimePoint::of($termUntil));

        return $this->member->decideAdmit(FactId::fromString($id), TimePoint::of($at), self::decisionDate(), $until, self::admissionProvenance());
    }

    public function end(string $id, string $period, int $at): Decision
    {
        return $this->member->decideEnd(FactId::fromString($id), PeriodId::fromString($period), TimePoint::of($at), self::decisionDate(), EndKind::fromString('resignation'), self::provenance());
    }

    /** Correction contract §3.1 (C-1): Annul sets aside a decision fact. */
    public function annul(string $id, string $target, ?int $decidedAt = 900, ?Provenance $provenance = null): Decision
    {
        return $this->member->decideAnnul(FactId::fromString($id), FactId::fromString($target), $provenance ?? self::provenance(), $decidedAt === null ? null : Instant::of($decidedAt));
    }

    /** Correction contract §3.2 (C-1): Void records that a fact never reflected reality. */
    public function void(string $id, string $target, ?int $decidedAt = null): Decision
    {
        return $this->member->decideVoid(FactId::fromString($id), FactId::fromString($target), Provenance::decision('registrar-1'), $decidedAt === null ? null : Instant::of($decidedAt));
    }

    /** Appends an accepted decision; fails the test if it was refused. */
    public function commit(Decision $decision, ?int $recordedAt = null): self
    {
        Assert::assertTrue($decision->isAccepted(), 'expected acceptance, got ' . ($decision->refusal()?->name ?? '?'));
        $this->clock = $recordedAt ?? $this->clock + 1;
        $log = $this->member->log();
        $log[] = RecordedFact::of($decision->basedOnVersion() + 1, Instant::of($this->clock), $decision->fact());
        $this->member = Member::fromLog($this->member->id(), $this->member->holder(), $this->member->organisation(), ...$log);

        return $this;
    }
}
