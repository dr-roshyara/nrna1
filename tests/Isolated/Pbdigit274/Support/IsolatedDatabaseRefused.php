<?php

namespace Tests\Isolated\Pbdigit274\Support;

use RuntimeException;

/**
 * The isolated PBDIGIT-274 test database may not be used. Carries a stable reason code
 * (missing · empty · malformed · reserved · forbidden · credentials_missing · config_cached · not_approved ·
 * connection_failed · mismatch · not_confined). Messages never contain credentials.
 */
final class IsolatedDatabaseRefused extends RuntimeException
{
    public function __construct(private readonly string $reason, string $detail)
    {
        parent::__construct("[{$reason}] {$detail}");
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
