<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServedOrganisationSnapshot;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\ConnectionInterface;

/**
 * The Platform registry in the CENTRAL database (PBDIGIT-345 task 2). Rows become aggregates only through
 * ServedOrganisation::fromSnapshot (baseline §5); a row the domain's value objects refuse is reported as corrupt
 * (\UnexpectedValueException) and never routed. The table's checks and its one-database-per-organisation index
 * (D-222) back the domain up.
 */
final class PostgresServedOrganisationRepository implements ServedOrganisationRepository
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function find(TenantId $id): ?ServedOrganisation
    {
        $row = $this->db->selectOne('SELECT *, grant_accepted_at::text AS accepted_text FROM platform_served_organisations WHERE id = ?::uuid', [$id->value()]);
        if ($row === null) {
            return null;
        }
        try {
            $snapshot = new ServedOrganisationSnapshot(
                TenantId::fromString($row->id),
                PlatformStatus::from($row->status),
                $row->schema_state === null ? null : SchemaState::from($row->schema_state),
                $row->locator_cluster === null ? null : new DatabaseLocator($row->locator_cluster, $row->locator_database, $row->locator_credential_ref),
                $row->grant_terms_version === null ? null : new ServiceOperationGrant($row->grant_terms_version, new \DateTimeImmutable($row->accepted_text),
                    $row->grant_accepted_by, json_decode($row->grant_fingerprints, true, 4, JSON_THROW_ON_ERROR)),
                $row->counterparty_evidence_ref === null ? null : new CounterpartyVerification($row->counterparty_evidence_ref),
                new FoundingApplication($row->founding_evidence_ref, $row->registrar_account,
                    json_decode($row->initial_contact_accounts, true, 4, JSON_THROW_ON_ERROR)),   // task 5a
            );
        } catch (\InvalidArgumentException | \ValueError | \JsonException $e) {
            throw new \UnexpectedValueException("Stored registry entry {$id->value()} is corrupt.", 0, $e);
        }

        return ServedOrganisation::fromSnapshot($snapshot);
    }

    public function add(ServedOrganisation $organisation): void
    {
        $columns = $this->columns($organisation->snapshot());
        if ($this->db->selectOne('SELECT 1 AS x FROM platform_served_organisations WHERE id = ?::uuid', [$organisation->id()->value()]) !== null) {
            throw new \RuntimeException('The organisation is already registered.');
        }
        $application = $organisation->foundingApplication();   // task 5a: written here once; save() never rewrites it
        $this->db->table('platform_served_organisations')->insert(['id' => $organisation->id()->value()] + $columns + [
            'founding_evidence_ref' => $application->evidenceRef,
            'registrar_account' => $application->registrarAccount,
            'initial_contact_accounts' => json_encode($application->initialContactAccounts, JSON_THROW_ON_ERROR),
        ]);
    }

    public function save(ServedOrganisation $organisation): void
    {
        $changed = $this->db->update('UPDATE platform_served_organisations SET status = ?, schema_state = ?, locator_cluster = ?, locator_database = ?,
            locator_credential_ref = ?, counterparty_evidence_ref = ?, grant_terms_version = ?, grant_accepted_at = ?::timestamptz, grant_accepted_by = ?,
            grant_fingerprints = ?::jsonb, row_version = row_version + 1, updated_at = clock_timestamp() WHERE id = ?::uuid',
            [...array_values($this->columns($organisation->snapshot())), $organisation->id()->value()]);
        if ($changed !== 1) {
            throw new \RuntimeException('The organisation is not registered.');
        }
    }

    /** @return array<string, mixed> in the column order save() uses */
    private function columns(ServedOrganisationSnapshot $s): array
    {
        return [
            'status' => $s->status->value,
            'schema_state' => $s->schemaState?->value,
            'locator_cluster' => $s->locator?->cluster,
            'locator_database' => $s->locator?->database,
            'locator_credential_ref' => $s->locator?->credentialRef,
            'counterparty_evidence_ref' => $s->counterparty?->evidenceRef,
            'grant_terms_version' => $s->grant?->termsVersion,
            'grant_accepted_at' => $s->grant?->acceptedAt->format('Y-m-d H:i:s.uP'),
            'grant_accepted_by' => $s->grant?->acceptedBy,
            'grant_fingerprints' => $s->grant === null ? null : json_encode($s->grant->foundingDocumentFingerprints, JSON_THROW_ON_ERROR),
        ];
    }
}
