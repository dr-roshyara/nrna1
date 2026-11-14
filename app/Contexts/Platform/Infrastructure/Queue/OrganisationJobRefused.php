<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Queue;

/**
 * PBDIGIT-345 task 7: an organisation job was refused before it ran. `reason`: wrong-connection, not-an-organisation-job,
 * no-organisation-id, or a routing refusal (unknown, not-operational, schema-not-current). Never an id or a locator.
 */
final class OrganisationJobRefused extends \RuntimeException
{
    public function __construct(public readonly string $reason)
    {
        parent::__construct("Organisation job refused: {$reason}.");
    }
}
