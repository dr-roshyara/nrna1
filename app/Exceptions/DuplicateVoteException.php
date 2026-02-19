<?php

namespace App\Exceptions;

use Exception;

/**
 * DuplicateVoteException
 *
 * Thrown when a user attempts to vote twice in the same election.
 *
 * The voting system enforces a one-vote-per-user-per-election rule.
 * This exception is triggered when an attempt is made to create a second vote.
 *
 * Security-critical: This protects election integrity.
 */
class DuplicateVoteException extends Exception
{
    /**
     * Context about the duplicate attempt
     *
     * @var array
     */
    public $context = [];

    /**
     * Create a new exception instance
     *
     * @param string $message The error message
     * @param array $context Additional context (user_id, election_id, existing_vote_id, etc.)
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
