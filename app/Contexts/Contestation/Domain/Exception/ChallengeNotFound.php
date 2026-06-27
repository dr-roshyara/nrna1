<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Exception;

use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use DomainException;

final class ChallengeNotFound extends DomainException
{
    public static function withId(ChallengeId $id): self
    {
        return new self(sprintf('Challenge "%s" not found.', $id->toString()));
    }
}
