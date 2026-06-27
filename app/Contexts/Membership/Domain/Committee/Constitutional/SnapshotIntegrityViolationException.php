<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final class SnapshotIntegrityViolationException extends \DomainException
{
    public function __construct(string $decisionId)
    {
        parent::__construct("Snapshot integrity violation for decision: {$decisionId}");
    }
}
