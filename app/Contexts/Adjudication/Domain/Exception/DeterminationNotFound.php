<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Exception;

use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use DomainException;

final class DeterminationNotFound extends DomainException
{
    public static function withId(DeterminationId $id): self
    {
        return new self(sprintf('Determination "%s" not found.', $id->toString()));
    }
}
