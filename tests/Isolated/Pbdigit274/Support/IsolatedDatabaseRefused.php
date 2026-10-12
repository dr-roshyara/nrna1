<?php

namespace Tests\Isolated\Pbdigit274\Support;

use RuntimeException;

/**
 * The isolated PBDIGIT-274 test database may not be used. Carries a stable reason code
 * (missing · empty · forbidden · malformed · credentials_missing · config_cached · connection_failed ·
 * mismatch · not_confined). Messages never contain credentials.
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
