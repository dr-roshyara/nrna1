<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Domain;

/**
 * The closed verdict vocabulary (AP-8 / DR-8).
 *
 * AD-1 AP-8: "assessment outcomes cross a boundary only in the closed vocabulary
 *             (PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT ·
 *             CERTIFIED). Nothing richer crosses."
 *
 * The full governed set is declared here. Only four are EMITTABLE by a mechanical
 * check: PASS AFTER CORRECTION, EMERGENT and CERTIFIED belong to review and
 * certification acts, which a validator does not perform (AP-1: knowledge feeds
 * authority, it never holds it).
 */
enum Verdict: string
{
    case PASS = 'PASS';
    case FAIL = 'FAIL';
    case WARN = 'WARN';
    case INCONCLUSIVE = 'INCONCLUSIVE';

    case PASS_AFTER_CORRECTION = 'PASS AFTER CORRECTION';
    case EMERGENT = 'EMERGENT';
    case CERTIFIED = 'CERTIFIED';

    /** The subset a mechanical validation may emit, in reporting order. */
    public static function emittable(): array
    {
        return [self::PASS, self::FAIL, self::WARN, self::INCONCLUSIVE];
    }

    public function isEmittable(): bool
    {
        return in_array($this, self::emittable(), true);
    }
}
