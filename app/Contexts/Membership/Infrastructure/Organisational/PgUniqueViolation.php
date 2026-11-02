<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use Illuminate\Database\UniqueConstraintViolationException;

/**
 * PBDIGIT-264 F-5 (Principal Reviewer disposition 2026-10-09; Part 2 plan §4.1): which unique constraint a
 * PostgreSQL violation is about, decided from the driver's structured error only.
 *
 * - SQLSTATE `errorInfo[0]` must be 23505 (unique_violation).
 * - The constraint name is taken from the FIRST line of `errorInfo[2]` (`… violates unique constraint "<name>"`).
 *
 * The DETAIL line (key values) and Laravel's message (SQL with bindings) are never read: both can contain the
 * constraint's name as data. Infrastructure only; no Laravel facade.
 */
final class PgUniqueViolation
{
    private const UNIQUE_VIOLATION = '23505';

    public static function isConstraint(UniqueConstraintViolationException $e, string $constraint): bool
    {
        $info = $e->errorInfo;
        if (!is_array($info) || ($info[0] ?? null) !== self::UNIQUE_VIOLATION || !is_string($info[2] ?? null)) {
            return false;
        }
        $firstLine = strtok($info[2], "\n");
        if ($firstLine === false || preg_match('/violates unique constraint "([^"]+)"/', $firstLine, $m) !== 1) {
            return false;
        }

        return $m[1] === $constraint;
    }
}
