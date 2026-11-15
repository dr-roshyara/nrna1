<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Application;

use App\Contexts\Platform\Application\OperateTechnically;
use App\Contexts\Platform\Application\Ports\OrganisationDatabaseFacts;
use App\Contexts\Platform\Application\Ports\OrganisationOperationReport;
use App\Contexts\Platform\Application\Ports\OrganisationReadinessProbe;
use App\Contexts\Platform\Application\Ports\OrganisationRestoreDrill;
use App\Contexts\Platform\Application\Ports\OrganisationSchemaRepair;
use App\Contexts\Platform\Application\Ports\TechnicalOperationLog;
use App\Contexts\Platform\Application\ProvisioningFailed;
use App\Contexts\Platform\Application\TechnicalOperationRecord;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Support\Platform\InMemoryServedOrganisationRepository;

/**
 * PBDIGIT-345 task 11 (E-4, technical only; tactical design §3.8; D-166 (1), D-219 (3), ISO-8, ISO-11; T-16):
 * - authority is the organisation's JOINING GRANT (the service-operation grant in the registry); no grant → refused;
 * - only TECHNICAL operations exist (migration repair); anything else is content access, which needs a case grant,
 *   not offered in release 1 → refused;
 * - every operation is RECORDED FIRST (attempted), then its outcome; succeeded and failed repairs are REPORTED to the
 *   organisation (no business content);
 * - a repair never changes status; it clears migration-failed only when the readiness facts hold again (L-1).
 */
final class OperateTechnicallyTest extends TestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const PENDING = '2d7a5c30-4e3f-4d5e-9c5b-4f7e0a3b6c82';
    private const PROVISIONING = '3e6b4d2f-3f4a-4c4b-8d6c-5a8f1b4c7d93';

    private InMemoryServedOrganisationRepository $registry;
    /** @var object{entries: list<array{string, string, ?string}>} */
    private object $log;
    /** @var object{calls: list<string>, fail: bool} */
    private object $repair;
    /** @var object{facts: OrganisationDatabaseFacts} */
    private object $probe;
    /** @var object{reports: list<TechnicalOperationRecord>} */
    private object $report;
    private OperateTechnically $operate;

    protected function setUp(): void
    {
        $this->registry = new InMemoryServedOrganisationRepository();
        $a = self::operational(self::A);
        $a->markMigrationFailed();
        $this->registry->add($a);
        $this->registry->add(ServedOrganisation::register(TenantId::fromString(self::PENDING), self::application()));
        $p = self::upToProvisioning(self::PROVISIONING);
        $p->assignLocator(new DatabaseLocator('cluster-1', 'org_p', 'env:ORG_CLUSTER_1'));
        $p->markMigrationFailed();
        $this->registry->add($p);

        $this->log = new class implements TechnicalOperationLog {
            /** @var list<array{string, string, ?string}> step, status, reason */
            public array $entries = [];

            public function attempted(TechnicalOperationRecord $record): void
            {
                $this->entries[] = ['attempted', $record->operation, null];
            }

            public function concluded(TechnicalOperationRecord $record, string $outcome, ?string $reason, bool $reported): void
            {
                $this->entries[] = ['concluded', $outcome, $reason];
            }
        };
        $this->repair = new class implements OrganisationSchemaRepair {
            /** @var list<string> */
            public array $calls = [];
            public bool $fail = false;

            public function repair(TenantId $id): void
            {
                $this->calls[] = $id->value();
                if ($this->fail) {
                    throw new ProvisioningFailed('simulated');
                }
            }
        };
        $this->probe = new class implements OrganisationReadinessProbe {
            public OrganisationDatabaseFacts $facts;

            public function __construct()
            {
                $this->facts = new OrganisationDatabaseFacts(true, true, true);
            }

            public function inspect(TenantId $id): OrganisationDatabaseFacts
            {
                return $this->facts;
            }
        };
        $this->report = new class implements OrganisationOperationReport {
            /** @var list<TechnicalOperationRecord> */
            public array $reports = [];

            public function report(TechnicalOperationRecord $record, string $outcome, ?string $reason): bool
            {
                $this->reports[] = $record;

                return true;
            }
        };
        $this->drill = new class implements OrganisationRestoreDrill {
            public ?OrganisationDatabaseFacts $facts = null;
            public bool $fail = false;
            /** @var list<string> */
            public array $calls = [];

            public function rehearse(TenantId $id): OrganisationDatabaseFacts
            {
                $this->calls[] = $id->value();
                if ($this->fail) {
                    throw new ProvisioningFailed('simulated: the backup could not be restored');
                }

                return $this->facts ?? new OrganisationDatabaseFacts(true, true, true);
            }
        };
        $this->operate = new OperateTechnically($this->registry, $this->log, $this->repair, $this->probe, $this->report, $this->drill);
    }

    /** @var object{facts: ?OrganisationDatabaseFacts, fail: bool, calls: list<string>} */
    private object $drill;

    private function healthyA(): void
    {
        $this->operate->perform(self::A, 'migration-repair', 'ops:alice');   // A back to current
        $this->log->entries = [];
        $this->report->reports = [];
    }

    public function test_t17_a_restore_drill_verifies_a_copy_and_changes_nothing_in_the_registry(): void
    {
        $this->healthyA();
        $before = $this->registry->find(TenantId::fromString(self::A))->snapshot();

        $outcome = $this->operate->perform(self::A, 'restore-drill', 'ops:alice');

        $this->assertSame(['succeeded', null], [$outcome->status, $outcome->reason]);
        $this->assertSame([self::A], $this->drill->calls);
        $this->assertEquals($before, $this->registry->find(TenantId::fromString(self::A))->snapshot(), 'the registry is untouched: the copy is never routable');
        $this->assertSame([['attempted', 'restore-drill', null], ['concluded', 'succeeded', null]], $this->log->entries);
        $this->assertCount(1, $this->report->reports, 'reported to the organisation');
    }

    public function test_t17_a_copy_that_does_not_verify_fails_the_drill_and_changes_nothing(): void
    {
        $this->healthyA();
        $before = $this->registry->find(TenantId::fromString(self::A))->snapshot();
        $this->drill->facts = new OrganisationDatabaseFacts(true, false, true);

        $outcome = $this->operate->perform(self::A, 'restore-drill', 'ops:alice');

        $this->assertSame(['failed', 'restore-not-verified'], [$outcome->status, $outcome->reason]);
        $this->assertEquals($before, $this->registry->find(TenantId::fromString(self::A))->snapshot());

        $this->drill->fail = true;
        $this->assertSame(['failed', 'restore-failed'], [($o = $this->operate->perform(self::A, 'restore-drill', 'ops:alice'))->status, $o->reason]);
    }

    public function test_t17_taking_an_organisation_out_for_a_restore_makes_it_unroutable_and_keeps_its_status(): void
    {
        $this->healthyA();

        $outcome = $this->operate->perform(self::A, 'take-out-for-restore', 'ops:alice');

        $a = $this->registry->find(TenantId::fromString(self::A));
        $this->assertSame('succeeded', $outcome->status);
        $this->assertSame([PlatformStatus::Operational, SchemaState::MigrationFailed], [$a->status(), $a->schemaState()]);
        $this->assertFalse($a->isRoutable(), 'out of routing until migration-repair restores it');
        $this->assertSame([self::A], $this->repair->calls, 'taking out repairs nothing (the one call is healthyA\'s)');
    }

    public function test_t17_the_restore_operations_need_the_joining_grant_and_a_database(): void
    {
        foreach (['restore-drill', 'take-out-for-restore'] as $operation) {
            $this->assertSame(['refused', 'no-joining-grant'], [($o = $this->operate->perform(self::PENDING, $operation, 'ops:alice'))->status, $o->reason], $operation);
        }
        $this->assertSame([], $this->drill->calls);

        $granted = ServedOrganisation::register(TenantId::fromString('4f5a3e1d-2c3b-4a4b-9e7d-6b9c2a5d8e04'), self::application());
        $granted->recordCounterpartyVerified(new CounterpartyVerification('agreement:granted'));
        $granted->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $this->registry->add($granted);   // a joining grant, but no database yet
        foreach (['restore-drill', 'take-out-for-restore'] as $operation) {
            $this->assertSame(['refused', 'no-database'], [($o = $this->operate->perform($granted->id()->value(), $operation, 'ops:alice'))->status, $o->reason], $operation);
        }
        $this->assertSame([], $this->drill->calls, 'nothing to back up');
    }

    private static function application(): FoundingApplication
    {
        return new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']);
    }

    private static function upToProvisioning(string $id): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString($id), self::application());
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:' . $id));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $o->startProvisioning();

        return $o;
    }

    private static function operational(string $id): ServedOrganisation
    {
        $o = self::upToProvisioning($id);
        $o->assignLocator(new DatabaseLocator('cluster-1', 'org_' . substr(str_replace('-', '', $id), 0, 12), 'env:ORG_CLUSTER_1'));
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));

        return $o;
    }

    public function test_t16_1_a_migration_repair_under_the_joining_grant_succeeds_records_and_reports(): void
    {
        $outcome = $this->operate->perform(self::A, 'migration-repair', 'ops:alice');

        $this->assertSame(['succeeded', null], [$outcome->status, $outcome->reason]);
        $this->assertSame([self::A], $this->repair->calls);
        $o = $this->registry->find(TenantId::fromString(self::A));
        $this->assertSame([PlatformStatus::Operational, SchemaState::Current], [$o->status(), $o->schemaState()], 'status unchanged; schema current');
        $this->assertSame([['attempted', 'migration-repair', null], ['concluded', 'succeeded', null]], $this->log->entries, 'recorded first, then the outcome');
        $this->assertCount(1, $this->report->reports, 'reported to the organisation');
        $this->assertSame(['ops:alice', self::A, 'migration-repair'], [$this->report->reports[0]->operator, $this->report->reports[0]->organisationId, $this->report->reports[0]->operation]);
    }

    /** @return array<string, array{string, string, string, string}> organisation, operation, operator, reason */
    public static function refusals(): array
    {
        return [
            'T-16.5 a content read (no case grant in release 1)' => [self::A, 'read-members', 'ops:alice', 'case-grant-required'],
            'T-16.5 any other operation is not technical' => [self::A, "SELECT * FROM org_memberships", 'ops:alice', 'case-grant-required'],
            'T-16.3 no joining grant (pending)' => [self::PENDING, 'migration-repair', 'ops:alice', 'no-joining-grant'],
            'an unknown organisation' => ['9f9f9f9f-9f9f-4f9f-8f9f-9f9f9f9f9f9f', 'migration-repair', 'ops:alice', 'unknown-organisation'],
            'a malformed organisation id' => ['A', 'migration-repair', 'ops:alice', 'unknown-organisation'],
            'no operator named' => [self::A, 'migration-repair', '  ', 'operator-not-named'],
        ];
    }

    #[DataProvider('refusals')]
    public function test_refused_operations_are_recorded_and_change_nothing(string $organisation, string $operation, string $operator, string $reason): void
    {
        $outcome = $this->operate->perform($organisation, $operation, $operator);

        $this->assertSame(['refused', $reason], [$outcome->status, $outcome->reason]);
        $this->assertSame([], $this->repair->calls, 'nothing executed');
        $this->assertSame('attempted', $this->log->entries[0][0], 'even a refusal is recorded first');
        $this->assertSame(['concluded', 'refused', $reason], $this->log->entries[1]);
        $this->assertSame(SchemaState::MigrationFailed, $this->registry->find(TenantId::fromString(self::A))->schemaState());
    }

    public function test_nothing_to_repair_when_the_schema_is_current(): void
    {
        $this->operate->perform(self::A, 'migration-repair', 'ops:alice');
        $this->log->entries = [];

        $outcome = $this->operate->perform(self::A, 'migration-repair', 'ops:alice');

        $this->assertSame(['refused', 'nothing-to-repair'], [$outcome->status, $outcome->reason]);
        $this->assertSame([self::A], $this->repair->calls, 'not run a second time');
    }

    public function test_t16_6_a_repair_never_changes_status_a_provisioning_organisation_stays_provisioning(): void
    {
        $outcome = $this->operate->perform(self::PROVISIONING, 'migration-repair', 'ops:alice');

        $o = $this->registry->find(TenantId::fromString(self::PROVISIONING));
        $this->assertSame('succeeded', $outcome->status);
        $this->assertSame([PlatformStatus::Provisioning, SchemaState::Current], [$o->status(), $o->schemaState()]);
        $this->assertFalse($o->isRoutable(), 'still not routable: operational needs step 5 (L-1)');
    }

    public function test_t16_6_the_schema_stays_failed_unless_the_readiness_facts_hold_again(): void
    {
        $this->probe->facts = new OrganisationDatabaseFacts(true, true, false);

        $outcome = $this->operate->perform(self::A, 'migration-repair', 'ops:alice');

        $this->assertSame(['failed', 'readiness-not-restored'], [$outcome->status, $outcome->reason]);
        $this->assertSame(SchemaState::MigrationFailed, $this->registry->find(TenantId::fromString(self::A))->schemaState());
        $this->assertFalse($this->registry->find(TenantId::fromString(self::A))->isRoutable());
        $this->assertCount(1, $this->report->reports, 'a failed repair is reported too');
    }

    public function test_t16_8_a_failed_repair_leaves_the_organisation_unroutable_and_is_recorded_and_reported(): void
    {
        $this->repair->fail = true;

        $outcome = $this->operate->perform(self::A, 'migration-repair', 'ops:alice');

        $this->assertSame(['failed', 'repair-failed'], [$outcome->status, $outcome->reason]);
        $this->assertSame(SchemaState::MigrationFailed, $this->registry->find(TenantId::fromString(self::A))->schemaState());
        $this->assertSame(['concluded', 'failed', 'repair-failed'], $this->log->entries[1]);
        $this->assertCount(1, $this->report->reports);
    }
}
