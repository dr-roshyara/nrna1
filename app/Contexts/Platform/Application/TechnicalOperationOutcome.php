<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

/** PBDIGIT-345 task 11: `status` is succeeded, failed or refused; `reason` names why (never content). */
final readonly class TechnicalOperationOutcome
{
    public function __construct(public string $operationId, public string $status, public ?string $reason)
    {
    }
}
