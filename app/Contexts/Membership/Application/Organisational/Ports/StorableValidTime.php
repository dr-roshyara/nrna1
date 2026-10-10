<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Domain\Organisational\Admit;
use App\Contexts\Membership\Domain\Organisational\EffectiveDatedFact;
use App\Contexts\Membership\Domain\Organisational\Fact;
use App\Contexts\Membership\Domain\Organisational\TimePoint;

/**
 * R-1a-6 (Slice 1a contract §4): the finite valid times a MemberRepositoryPort adapter can store,
 * 0001-01-01 … 9999-12-31 inclusive, as ordinals (days since 1970-01-01). A persistence capability
 * of the port, not a business calendar rule: TimePoint itself stays unbounded and keeps ∞.
 * An adapter answers ValidTimeNotStorable after the idempotency-key lookup and before the version
 * comparison. The fact kinds with a valid time are pinned by MembershipStorableValidTimeCoverageTest.
 */
final class StorableValidTime
{
    public const FIRST = -719_162;
    public const LAST = 2_932_896;

    /** True when every finite valid time of the fact lies in [FIRST, LAST]; ∞ and facts without one pass. */
    public static function admits(Fact $fact): bool
    {
        $points = $fact instanceof EffectiveDatedFact ? [$fact->effectiveAt()] : [];
        if ($fact instanceof Admit && $fact->initialTermUntil() !== null) {
            $points[] = $fact->initialTermUntil();
        }
        foreach ($points as $point) {
            if (!self::storable($point)) {
                return false;
            }
        }

        return true;
    }

    private static function storable(TimePoint $point): bool
    {
        return $point->isInfinite() || ($point->ordinal() >= self::FIRST && $point->ordinal() <= self::LAST);
    }
}
