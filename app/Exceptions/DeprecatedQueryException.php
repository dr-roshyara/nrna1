<?php

namespace App\Exceptions;

/**
 * DeprecatedQueryException: SQL-Level Deprecation Enforcement
 *
 * Thrown when a database query attempts to use a deprecated field.
 * This prevents the SQL layer from bypassing the SSOT architecture.
 */
final class DeprecatedQueryException extends DomainException
{
    public static function fieldNotAllowed(string $field, string $context): self
    {
        return new self(
            "Deprecated field '{$field}' used in query context: {$context}. " .
            "Use ElectionReadModel::lifecycle() instead of direct field access."
        );
    }
}
