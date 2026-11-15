<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\OrganisationOperationReport;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Platform\Application\TechnicalOperationRecord;
use App\Contexts\Platform\Infrastructure\Outbox\TechnicalOperationPerformedHydrator;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Str;

/**
 * PBDIGIT-345 task 11 (ISO-8): reports a technical operation TO THE ORGANISATION by writing a
 * TechnicalOperationPerformed event into its OWN outbox (task 9), at the locator the registry holds. It WRITES one
 * technical row and reads nothing of the organisation. Its database unreachable → false (the central record says so).
 */
final class OutboxOrganisationOperationReport implements OrganisationOperationReport
{
    public function __construct(
        private readonly ServedOrganisationRepository $registry,
        private readonly OrganisationConnectionFactory $factory,
    ) {
    }

    public function report(TechnicalOperationRecord $record, string $outcome, ?string $reason): bool
    {
        $id = TenantId::fromString($record->organisationId);
        $locator = $this->registry->find($id)?->locator();
        if ($locator === null) {
            return false;
        }
        try {
            $context = $this->factory->establish(new ResolvedOrganisation($id, $locator));
        } catch (\Throwable) {
            return false;
        }
        try {
            $context->connection->table('outbox_events')->insert([
                'id' => (string) Str::uuid(),
                'event_id' => $record->operationId,
                'organisation_id' => $record->organisationId,
                'aggregate_type' => 'TechnicalOperation',
                'aggregate_id' => $record->operationId,
                'event_type' => TechnicalOperationPerformedHydrator::EVENT_TYPE,
                'payload' => json_encode(['operationId' => $record->operationId, 'organisationId' => $record->organisationId, 'operation' => $record->operation,
                    'operator' => $record->operator, 'outcome' => $outcome, 'reason' => $reason, 'requestedAt' => $record->requestedAt->format(DATE_ATOM)], JSON_THROW_ON_ERROR),
                'status' => 'pending',
            ]);

            return true;
        } catch (\Throwable) {
            return false;
        } finally {
            $this->factory->release($context);
        }
    }
}
