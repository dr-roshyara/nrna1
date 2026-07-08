<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Inbox;

use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use RuntimeException;

/**
 * Messaging classification (translation target): a Contestation reaction hit a permanent
 * business incompatibility (conflicting determination, illegal transition). Implements the
 * Shared {@see PermanentInboxFailure} marker so the inbox wrapper dead-letters + escalates
 * and never retries. Produced only by the inbox translator.
 */
final class ChallengeReactionPermanentFailure extends RuntimeException implements PermanentInboxFailure
{
}
