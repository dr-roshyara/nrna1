<?php

namespace App\Exceptions;

/**
 * DeprecatedFieldException
 *
 * Thrown when strict-mode deprecation enforcement blocks access to a deprecated field.
 * This indicates the codebase is trying to use a field that should be migrated to SSOT.
 */
final class DeprecatedFieldException extends \RuntimeException
{
}
