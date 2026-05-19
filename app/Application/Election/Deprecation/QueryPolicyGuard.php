<?php

namespace App\Application\Election\Deprecation;

use App\Exceptions\DeprecatedQueryException;
use App\Application\Election\Monitoring\DriftMonitorInterface;
use App\Application\Election\Monitoring\SSOTViolationEvent;
use Illuminate\Support\Facades\Log;

/**
 * QueryPolicyGuard: SQL-Level Deprecation Enforcement
 *
 * Prevents deprecated fields from being used in database queries.
 * This is the third layer of defense against SSOT corruption:
 * 1. ElectionReadModel (domain layer) - encourages SSOT paths
 * 2. DeprecationAccessGuard (application layer) - blocks runtime field access
 * 3. QueryPolicyGuard (query layer) - blocks SQL-level bypass attempts
 *
 * Acts as a hard gate on all query operations that touch deprecated fields.
 */
final class QueryPolicyGuard
{
    public function __construct(
        private readonly ?DriftMonitorInterface $monitor = null
    ) {}

    /**
     * Assert that a query does not use deprecated fields.
     *
     * @param array $criteria Query criteria (key=field, value=filter value)
     * @param string $context Human-readable query context for logging (e.g., "ElectionRepository::findActive")
     * @return void
     * @throws DeprecatedQueryException If any criterion uses a deprecated field
     */
    public function assertAllowedQuery(array $criteria, string $context): void
    {
        foreach ($criteria as $field => $value) {
            if (DeprecationPolicy::isDeprecated($field)) {
                Log::warning(
                    'Deprecated field used in database query',
                    [
                        'field' => $field,
                        'context' => $context,
                        'severity' => DeprecationPolicy::getSeverity($field),
                    ]
                );

                // Fire to drift monitor (Stream 4)
                $this->monitor?->record(SSOTViolationEvent::make(
                    'deprecated_query_field',
                    'query_policy',
                    $context
                ));

                throw DeprecatedQueryException::fieldNotAllowed($field, $context);
            }
        }
    }
}
