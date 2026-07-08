<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Inbox;

use App\Contexts\Shared\Application\Inbox\IdempotentReplay;
use RuntimeException;

/**
 * Messaging classification (translation target): a Contestation reaction determined the
 * work was already done. Implements the Shared {@see IdempotentReplay} marker so the inbox
 * wrapper acks the message without retrying. Produced only by the inbox translator — the
 * reactions themselves never name messaging outcomes.
 */
final class ChallengeReactionReplay extends RuntimeException implements IdempotentReplay
{
}
