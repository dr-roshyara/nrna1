<?php

namespace App\Contexts\Membership\Domain\Exceptions;

use RuntimeException;

/**
 * Invalid Member Geography Exception
 *
 * Thrown when member registration has invalid geography according to DDD validation.
 * This exception wraps geography context exceptions for the membership context.
 *
 * Examples:
 * - Invalid hierarchy (gaps, wrong parent-child)
 * - Missing required levels for country
 * - Unsupported country
 * - Insufficient geographic detail for membership
 */
class InvalidMemberGeographyException extends RuntimeException
{
    /**
     * Create exception for invalid hierarchy
     */
    public static function invalidHierarchy(string $message): self
    {
        return new self("Invalid geography hierarchy: {$message}");
    }

    /**
     * Create exception for unsupported country
     */
    public static function unsupportedCountry(string $countryCode): self
    {
        return new self("Country '{$countryCode}' is not supported for membership");
    }

    /**
     * Create exception for missing required level
     */
    public static function missingRequiredLevel(string $message): self
    {
        return new self("Missing required geographic level: {$message}");
    }

    /**
     * Create exception for insufficient geographic detail
     */
    public static function insufficientDetail(string $message = null): self
    {
        $defaultMessage = 'Insufficient geographic detail provided';
        return new self($message ?? $defaultMessage);
    }
}