<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

use App\Contexts\Platform\Application\Ports\OrganisationOperationReport;
use App\Contexts\Platform\Application\Ports\OrganisationReadinessProbe;
use App\Contexts\Platform\Application\Ports\OrganisationRestoreDrill;
use App\Contexts\Platform\Application\Ports\OrganisationSchemaRepair;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\Ports\TechnicalOperationLog;
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The operator channel E-4, TECHNICAL ONLY (PBDIGIT-345 task 11; tactical design §3.8; D-166 (1), D-219 (3), ISO-8,
 * ISO-11; T-16). One explicit organisation per operation, resolved through the registry; no command iterates
 * organisations (that is E-3).
 *
 * 1. RECORD the attempt first (central), even if it is then refused;
 * 2. refuse: no operator named; an operation that is not technical (content access needs a case grant: NOT offered
 *    in release 1); an unknown organisation; no joining grant (the registry's service-operation grant, D-166 (1));
 *    nothing to repair;
 * 3. execute the repair in the dedicated process; a failure → `failed`, schema stays migration-failed;
 * 4. clear migration-failed ONLY if the readiness facts (L-1, the organisation database) hold again. Status is never
 *    changed: provisioning stays provisioning; nothing becomes operational here;
 * 5. report succeeded or failed repairs to the organisation; record the outcome.
 */
final class OperateTechnically
{
    /** The technical operations of release 1. Everything else is content access (case grant, deferred). */
    public const MIGRATION_REPAIR = 'migration-repair';
    /** task 12b (T-17, D-234): verify a backup on a never-routable scratch copy */
    public const RESTORE_DRILL = 'restore-drill';
    /** task 12b (D-234): take the organisation out of routing before an in-place restore (SchemaState only) */
    public const TAKE_OUT_FOR_RESTORE = 'take-out-for-restore';
    private const TECHNICAL = [self::MIGRATION_REPAIR, self::RESTORE_DRILL, self::TAKE_OUT_FOR_RESTORE];
    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    public function __construct(
        private readonly ServedOrganisationRepository $registry,
        private readonly TechnicalOperationLog $log,
        private readonly OrganisationSchemaRepair $repair,
        private readonly OrganisationReadinessProbe $probe,
        private readonly OrganisationOperationReport $report,
        private readonly OrganisationRestoreDrill $drill,
    ) {
    }

    public function perform(string $organisationId, string $operation, string $operator): TechnicalOperationOutcome
    {
        $record = new TechnicalOperationRecord(self::uuid4(), trim($organisationId), $operation, trim($operator), new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $this->log->attempted($record);

        if ($record->operator === '') {
            return $this->conclude($record, 'refused', 'operator-not-named');
        }
        if (!in_array($operation, self::TECHNICAL, true)) {
            return $this->conclude($record, 'refused', 'case-grant-required');
        }
        $id = preg_match(self::UUID, strtolower($record->organisationId)) === 1 ? TenantId::fromString(strtolower($record->organisationId)) : null;
        $organisation = $id === null ? null : $this->registry->find($id);
        if ($organisation === null) {
            return $this->conclude($record, 'refused', 'unknown-organisation');
        }
        if ($organisation->serviceOperationGrant() === null) {
            return $this->conclude($record, 'refused', 'no-joining-grant');
        }
        if ($operation !== self::MIGRATION_REPAIR && $organisation->locator() === null) {
            return $this->conclude($record, 'refused', 'no-database');
        }
        if ($operation === self::RESTORE_DRILL) {
            // a scratch copy, never routable: the registry is not touched (D-234)
            try {
                $facts = $this->drill->rehearse($id);
            } catch (ProvisioningFailed) {
                return $this->conclude($record, 'failed', 'restore-failed', report: true);
            }

            return $facts->ledgerSchemaPresent && $facts->identityRowMatches && $facts->ledgerClockRowPresent
                ? $this->conclude($record, 'succeeded', null, report: true)
                : $this->conclude($record, 'failed', 'restore-not-verified', report: true);
        }
        if ($operation === self::TAKE_OUT_FOR_RESTORE) {
            // SchemaState only: unroutable until migration-repair returns it (D-234); PlatformStatus never changes
            $organisation->markMigrationFailed();
            $this->registry->save($organisation);

            return $this->conclude($record, 'succeeded', null, report: true);
        }
        if ($organisation->schemaState() !== SchemaState::MigrationFailed) {
            return $this->conclude($record, 'refused', 'nothing-to-repair');
        }

        try {
            $this->repair->repair($id);
        } catch (ProvisioningFailed) {
            return $this->conclude($record, 'failed', 'repair-failed', report: true);
        }
        $facts = $this->probe->inspect($id);
        if (!($facts->ledgerSchemaPresent && $facts->identityRowMatches && $facts->ledgerClockRowPresent)) {
            return $this->conclude($record, 'failed', 'readiness-not-restored', report: true);
        }
        $organisation->markSchemaCurrent();
        $this->registry->save($organisation);

        return $this->conclude($record, 'succeeded', null, report: true);
    }

    private function conclude(TechnicalOperationRecord $record, string $outcome, ?string $reason, bool $report = false): TechnicalOperationOutcome
    {
        $reported = $report && $this->report->report($record, $outcome, $reason);
        $this->log->concluded($record, $outcome, $reason, $reported);

        return new TechnicalOperationOutcome($record->operationId, $outcome, $reason);
    }

    private static function uuid4(): string
    {
        $b = random_bytes(16);
        $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
        $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
    }
}
