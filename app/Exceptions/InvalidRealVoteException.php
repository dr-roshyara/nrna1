<?php

namespace App\Exceptions;

use Exception;

/**
 * InvalidRealVoteException
 *
 * Thrown when vote creation violates real voting rules.
 * Real votes MUST have:
 * - organisation_id (NOT NULL)
 * - Valid election_id
 * - Election belonging to same organisation
 *
 * This exception provides context about what rule was violated.
 */
class InvalidRealVoteException extends Exception
{
    /**
     * Additional context about the violation
     *
     * @var array
     */
    public $context = [];

    /**
     * Create a new exception instance
     *
     * @param string $message The error message
     * @param array $context Additional context (vote data, reason, etc.)
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
