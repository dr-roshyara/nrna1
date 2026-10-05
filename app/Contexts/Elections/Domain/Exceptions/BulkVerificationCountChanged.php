<?php

namespace App\Contexts\Elections\Domain\Exceptions;

use DomainException;

/**
 * The set of voters eligible for "Verify All" changed between the officer's
 * confirmation and execution (PBDIGIT-213 C4-D3): the attestation covered a
 * different number of voters, so nothing is written.
 */
final class BulkVerificationCountChanged extends DomainException
{
    private function __construct(string $message, public readonly int $expected, public readonly int $actual)
    {
        parent::__construct($message);
    }

    public static function between(int $expected, int $actual): self
    {
        return new self("Expected {$expected} voters to verify, but {$actual} are eligible now.", $expected, $actual);
    }
}
