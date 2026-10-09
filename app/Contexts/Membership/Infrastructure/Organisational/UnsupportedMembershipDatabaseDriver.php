<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

/** DB-1: Membership persistence supports PostgreSQL only. A configuration error, raised at construction. */
final class UnsupportedMembershipDatabaseDriver extends \RuntimeException
{
    public static function for(string $connection, string $driver): self
    {
        return new self(sprintf(
            "Membership persistence requires PostgreSQL (DB-1); connection '%s' uses '%s'.",
            $connection,
            $driver,
        ));
    }
}
