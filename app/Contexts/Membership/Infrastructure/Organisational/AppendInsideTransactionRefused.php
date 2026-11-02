<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

/**
 * PBDIGIT-264 F-5, N1 (Principal Reviewer disposition 2026-10-09; ratification pack §9.3).
 *
 * The PostgreSQL adapter refuses to append inside an already open transaction, before issuing any statement:
 * - `SET TRANSACTION ISOLATION LEVEL READ COMMITTED` must be a transaction's first statement, and fails
 *   (SQLSTATE 25001) inside an outer transaction or a savepoint;
 * - an Appended outcome must mean "durably committed", which an outer transaction could still roll back.
 *
 * Infrastructure misuse, not a domain outcome.
 */
final class AppendInsideTransactionRefused extends \RuntimeException
{
    public static function atLevel(int $level): self
    {
        return new self("Membership append refused: called inside an open transaction (level {$level}); the adapter commits its own top-level transaction (N1).");
    }
}
