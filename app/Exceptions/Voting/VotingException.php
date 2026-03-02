<?php

namespace App\Exceptions\Voting;

use Exception;

/**
 * Base Exception for all voting-related errors
 *
 * Provides standardized handling of voting exceptions with:
 * - User-friendly error messages
 * - Internal context for logging/debugging
 * - HTTP status codes
 * - Exception hierarchy for specific error types
 */
abstract class VotingException extends Exception
{
    /**
     * Context data for logging and debugging
     */
    protected array $context = [];

    /**
     * Constructor
     *
     * @param string $message Technical message for logging
     * @param array $context Additional context for debugging
     */
    public function __construct(string $message = "", array $context = [])
    {
        parent::__construct($message);
        $this->context = $context;
    }

    /**
     * Get user-friendly error message for UI display
     *
     * @return string
     */
    abstract public function getUserMessage(): string;

    /**
     * Get context data for logging
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Get HTTP status code for API response
     *
     * @return int
     */
    public function getHttpCode(): int
    {
        return 500;
    }
}
