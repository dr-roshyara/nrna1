<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

/**
 * TEST ONLY (PBDIGIT-345 task 9, T-24): an organisation event whose consumer must queue follow-up work AFTER the
 * organisation's commit. Its hydrator is T9FollowUpRequestedHydrator.
 */
final readonly class T9FollowUpRequested
{
    public function __construct(public string $organisationId, public string $label)
    {
    }
}
