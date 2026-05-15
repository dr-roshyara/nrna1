<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Exceptions;

final class InvalidCommitteeSlugException extends \DomainException
{
    public static function empty(): self
    {
        return new self('committee.slug.empty');
    }

    public static function reserved(string $slug): self
    {
        return new self('committee.slug.reserved');
    }
}
