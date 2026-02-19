<?php

namespace App\Exceptions;

use Exception;

/**
 * OrganisationMismatchException
 *
 * Thrown when election_id, vote_id, or result_id organisation_id values don't match.
 *
 * Examples:
 * - Vote's organisation_id ≠ Election's organisation_id
 * - Result's organisation_id ≠ Vote's organisation_id
 *
 * This indicates a data integrity issue or attempted cross-organisation access.
 */
class OrganisationMismatchException extends Exception
{
    /**
     * Context data about the mismatch
     *
     * @var array
     */
    public $context = [];

    /**
     * Create a new exception instance
     *
     * @param string $message The error message
     * @param array $context Additional context (org IDs, record IDs, etc.)
     * @param \Throwable $previous Previous exception for chaining
     */
    public function __construct($message = '', array $context = [], \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->context = $context;
    }

    /**
     * Get the context data
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Get the full error report as array
     *
     * @return array
     */
    public function report(): array
    {
        return [
            'exception' => static::class,
            'message' => $this->getMessage(),
            'context' => $this->context,
            'timestamp' => now()->toIso8601String(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
        ];
    }
}
