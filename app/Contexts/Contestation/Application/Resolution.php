<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application;

/**
 * How a Challenge concluded, as carried on the PUBLISHED Integration Event only (F-2):
 * `Upheld` (a correction was applied) or `Dismissed` (no correction). This is an
 * integration/application concern — it is NOT part of the `ChallengeResolved` domain event
 * (which stays minimal). The reaction derives it from the trigger and supplies it to the
 * outbox at publish time.
 */
enum Resolution: string
{
    case Upheld = 'upheld';
    case Dismissed = 'dismissed';
}
