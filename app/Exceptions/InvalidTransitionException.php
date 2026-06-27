<?php

namespace App\Exceptions;

/**
 * InvalidTransitionException
 *
 * Thrown when ConstitutionalTransitionGuard blocks an illegal state transition.
 * This is a domain-level exception that represents a constitutional violation.
 */
final class InvalidTransitionException extends \DomainException
{
}
