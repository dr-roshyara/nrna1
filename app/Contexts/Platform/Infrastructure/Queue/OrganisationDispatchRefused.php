<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Queue;

/** PBDIGIT-345 task 7 (§3.10 Q-3): OrganisationJobDispatcher refused a dispatch; nothing was queued. */
class OrganisationDispatchRefused extends \RuntimeException
{
}
