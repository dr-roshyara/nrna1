<?php

namespace App\Exceptions;

use Exception;

/**
 * UnauthorizedStateMutationException
 *
 * Thrown when code attempts to mutate election.state without authorization
 * from ConstitutionalTransitionGuard at Level 4 (full strict enforcement).
 *
 * At Level 1-3, the mutation is recorded to metrics but allowed.
 * At Level 4, this exception blocks the mutation entirely.
 *
 * This enforces constitutional governance: the guard is sovereign over all
 * state changes. No bypasses possible.
 */
final class UnauthorizedStateMutationException extends Exception
{
}
