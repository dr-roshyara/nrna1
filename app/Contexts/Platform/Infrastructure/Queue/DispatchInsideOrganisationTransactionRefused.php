<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Queue;

/**
 * PBDIGIT-345 task 7 (§3.10.2, Q-7; T-25): a dispatch while the organisation connection was inside a transaction.
 * The job would be queued before (or without) the commit; work that must follow a commit goes through the
 * organisation's outbox instead.
 */
final class DispatchInsideOrganisationTransactionRefused extends OrganisationDispatchRefused
{
}
