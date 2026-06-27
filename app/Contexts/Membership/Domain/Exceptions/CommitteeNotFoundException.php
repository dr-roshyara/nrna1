<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Exceptions;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;

final class CommitteeNotFoundException extends \DomainException
{
    public function __construct(CommitteeId $id)
    {
        parent::__construct("Committee {$id->value()} not found");
    }
}
