<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Application;

use App\Contexts\Platform\Application\FoundingRefused;
use App\Contexts\Platform\Application\FoundOrganisation;
use App\Contexts\Platform\Application\OrganisationNotRoutable;
use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
use App\Contexts\Platform\Application\Ports\ContactAccounts;
use App\Contexts\Platform\Application\Ports\ContactAccountsUnavailable;
use App\Contexts\Platform\Application\Ports\OrganisationDatabaseFacts;
use App\Contexts\Platform\Application\Ports\OrganisationProvisioner;
use App\Contexts\Platform\Application\Ports\OrganisationReadinessProbe;
use App\Contexts\Platform\Domain\LifecycleRefusal;
use App\Contexts\Platform\Application\ProvisioningFailed;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Application\RoutingRefusal;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Platform\Domain\ServedOrganisationRefused;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Support\Platform\InMemoryServedOrganisationRepository;

/**
 * PBDIGIT-345 task 5a (tactical design §3.6 steps 1–2, §3.7 L-2; T-9 for these steps; T-14 part 1):
 * - step 1 registers a `pending` entry with its founding application; a LEGACY organisation id (present in the legacy
 *   `organisations` table) is refused (L-2); an id already registered with a different application is refused;
 * - step 2 records the counterparty verification and the service-operation grant, then starts provisioning;
 * - every step is idempotent: repeating it with the same input is a no-op, so after a crash (a lost save) the founding
 *   is simply resumed and reaches the same state; repeating it with DIFFERENT input is refused.
 */
final class FoundOrganisationTest extends TestCase
{
    private const ID = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const LEGACY = '9f8e7d6c-5b4a-4392-8170-6f5e4d3c2b1a';

    private InMemoryServedOrganisationRepository $registry;
    private FoundOrganisation $founding;

    protected function setUp(): void
    {
        $this->registry = new InMemoryServedOrganisationRepository();
        $legacy = new class ([self::LEGACY]) implements LegacyOrganisationRegister {
            /** @param list<string> $ids */
            public function __construct(private readonly array $ids)
            {
            }

            public function contains(TenantId $id): bool
            {
                return in_array($id->value(), $this->ids, true);
            }
        };
        $this->provisioner = new class implements OrganisationProvisioner {
            /** @var list<string> */
            public array $calls = [];
            public bool $failNext = false;

            public function prepare(TenantId $id): void
            {
                $this->calls[] = $id->value();
                if ($this->failNext) {
                    $this->failNext = false;
                    throw new ProvisioningFailed('simulated: the organisation database could not be prepared');
                }
            }
        };
        $this->probe = new class implements OrganisationReadinessProbe {
            public OrganisationDatabaseFacts $facts;
            /** @var list<string> */
            public array $calls = [];

            public function __construct()
            {
                $this->facts = new OrganisationDatabaseFacts(true, true, true);
            }

            public function inspect(TenantId $id): OrganisationDatabaseFacts
            {
                $this->calls[] = $id->value();

                return $this->facts;
            }
        };
        $this->contacts = new class implements ContactAccounts {
            public int $active = 1;
            public bool $unavailable = false;
            /** @var list<list<string>> */
            public array $asked = [];

            public function activeCount(array $accountIds): int
            {
                $this->asked[] = $accountIds;
                if ($this->unavailable) {
                    throw new ContactAccountsUnavailable('simulated: Identity unavailable');
                }

                return $this->active;
            }
        };
        $this->founding = new FoundOrganisation($this->registry, $legacy, $this->provisioner, $this->probe, $this->contacts);
    }

    private OrganisationReadinessProbe $probe;
    private ContactAccounts $contacts;

    private function upToSchemaCurrent(): void
    {
        $this->upToProvisioning();
        $this->founding->provision(self::id(), self::locator());
    }

    public function test_5c_step_five_makes_it_operational_when_every_fact_holds_and_then_it_is_routed(): void
    {
        $this->upToSchemaCurrent();

        $this->founding->markOperational(self::id());

        $this->assertSame(PlatformStatus::Operational, $this->registry->find(self::id())->status());
        $this->assertSame([self::ID], $this->probe->calls, 'the facts are read from the organisation database at the transition');
        $this->assertSame([['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']], $this->contacts->asked, 'the founding application\'s contacts, from the registry');
        $this->assertTrue(self::locator()->equals((new ResolutionService($this->registry))->resolve(self::ID)->locator));
    }

    /** @return array<string, array{\Closure(self): void, string}> */
    public static function missingPreconditions(): array
    {
        return [
            'the ledger schema' => [static function (self $t): void { $t->probe->facts = new OrganisationDatabaseFacts(false, true, true); }, 'ledger schema'],
            'the organisation identity' => [static function (self $t): void { $t->probe->facts = new OrganisationDatabaseFacts(true, false, true); }, 'organisation identity'],
            'the ledger clock row' => [static function (self $t): void { $t->probe->facts = new OrganisationDatabaseFacts(true, true, false); }, 'ledger clock row'],
            'an active contact account' => [static function (self $t): void { $t->contacts->active = 0; }, 'active account'],
        ];
    }

    /** T-13: `operational` is refused for each missing precondition alone; nothing is recorded. */
    #[\PHPUnit\Framework\Attributes\DataProvider('missingPreconditions')]
    public function test_5c_t13_operational_is_refused_for_each_missing_fact(\Closure $remove, string $named): void
    {
        $this->upToSchemaCurrent();
        $remove($this);

        try {
            $this->founding->markOperational(self::id());
            $this->fail('operational without: ' . $named);
        } catch (FoundingRefused $e) {
            $this->assertStringContainsString($named, $e->getMessage());
        }
        $this->assertSame(PlatformStatus::Provisioning, $this->registry->find(self::id())->status());
    }

    public function test_5c_t13_operational_is_refused_without_a_database_or_with_a_failed_schema_or_before_provisioning(): void
    {
        $this->founding->register(self::id(), self::application());
        $this->assertRefusedWith(LifecycleRefusal::NotProvisioning);

        $this->founding->verifyCounterparty(self::id(), new CounterpartyVerification('agreement:1'));
        $this->founding->recordServiceOperationGrant(self::id(), self::grant());
        $this->founding->startProvisioning(self::id());
        $this->assertRefusedWith(LifecycleRefusal::NoLocator);

        $this->provisioner->failNext = true;
        try {
            $this->founding->provision(self::id(), self::locator());
        } catch (ProvisioningFailed) {
        }
        $this->assertRefusedWith(LifecycleRefusal::SchemaNotCurrent);
        $this->assertSame([], $this->probe->calls, 'nothing is inspected before the schema is current');
    }

    private function assertRefusedWith(LifecycleRefusal $reason): void
    {
        try {
            $this->founding->markOperational(self::id());
            $this->fail('operational, expected refusal: ' . $reason->name);
        } catch (ServedOrganisationRefused $e) {
            $this->assertSame($reason, $e->refusal);
        }
        $this->assertNotSame(PlatformStatus::Operational, $this->registry->find(self::id())->status());
    }

    public function test_5c_identity_unavailable_fails_closed(): void
    {
        $this->upToSchemaCurrent();
        $this->contacts->unavailable = true;

        try {
            $this->founding->markOperational(self::id());
            $this->fail('operational while Identity was unavailable');
        } catch (ContactAccountsUnavailable) {
            $this->assertSame(PlatformStatus::Provisioning, $this->registry->find(self::id())->status());
        }
    }

    public function test_5c_t9_a_crash_in_step_five_is_resumed_and_a_repeat_is_a_no_op(): void
    {
        $this->upToSchemaCurrent();
        $this->registry->failNextSave = true;
        try {
            $this->founding->markOperational(self::id());
            $this->fail('the simulated crash did not happen');
        } catch (\RuntimeException $e) {
            $this->assertSame('simulated crash before commit', $e->getMessage());
        }
        $this->assertSame(PlatformStatus::Provisioning, $this->registry->find(self::id())->status(), 'not routed: the transition was lost');

        $this->founding->markOperational(self::id());
        $this->probe->facts = new OrganisationDatabaseFacts(false, false, false);   // a repeat inspects nothing
        $this->founding->markOperational(self::id());
        $this->founding->provision(self::id(), self::locator());   // and step 3 repeated after step 5 prepares nothing

        $this->assertSame(PlatformStatus::Operational, $this->registry->find(self::id())->status());
        $this->assertSame([self::ID], $this->provisioner->calls);
    }

    private OrganisationProvisioner $provisioner;

    private static function locator(string $database = 'org_0b9e7a526c1d4f7e9a3b2d5c8e1f4a60'): DatabaseLocator
    {
        return new DatabaseLocator('cluster-1', $database, 'env:ORG_CLUSTER_1');
    }

    private function upToProvisioning(): void
    {
        $this->founding->register(self::id(), self::application());
        $this->founding->verifyCounterparty(self::id(), new CounterpartyVerification('agreement:1'));
        $this->founding->recordServiceOperationGrant(self::id(), self::grant());
        $this->founding->startProvisioning(self::id());
    }

    public function test_5b_step_three_binds_the_locator_prepares_the_database_and_marks_the_schema_current(): void
    {
        $this->upToProvisioning();

        $this->founding->provision(self::id(), self::locator());

        $o = $this->registry->find(self::id());
        $this->assertTrue(self::locator()->equals($o->locator()));
        $this->assertSame(SchemaState::Current, $o->schemaState());
        $this->assertSame([self::ID], $this->provisioner->calls, 'prepared once, in its own process (the port)');
        $this->assertSame(PlatformStatus::Provisioning, $o->status(), 'step 3 never makes it operational');
    }

    public function test_5b_t9_no_request_is_routed_before_step_five(): void
    {
        $this->upToProvisioning();
        $this->founding->provision(self::id(), self::locator());

        try {
            (new ResolutionService($this->registry))->resolve(self::ID);
            $this->fail('a provisioning organisation was routed');
        } catch (OrganisationNotRoutable $e) {
            $this->assertSame(RoutingRefusal::NotOperational, $e->reason);
        }
    }

    public function test_5b_t9_a_crash_in_step_three_is_resumed_by_repeating_it(): void
    {
        $this->upToProvisioning();

        // a lost save of the locator: nothing was prepared, nothing recorded
        $this->registry->failNextSave = true;
        try {
            $this->founding->provision(self::id(), self::locator());
            $this->fail('the simulated crash did not happen');
        } catch (\RuntimeException $e) {
            $this->assertSame('simulated crash before commit', $e->getMessage());
        }
        $this->assertNull($this->registry->find(self::id())->locator());
        $this->assertSame([], $this->provisioner->calls, 'nothing is prepared before the locator is recorded');

        // the preparation fails: recorded as a failed migration, and the failure is reported
        $this->provisioner->failNext = true;
        try {
            $this->founding->provision(self::id(), self::locator());
            $this->fail('a failed preparation was not reported');
        } catch (ProvisioningFailed) {
            $this->assertSame(SchemaState::MigrationFailed, $this->registry->find(self::id())->schemaState());
        }

        // a lost save after a successful preparation: the preparation is repeated (it is idempotent)
        $this->registry->failNextSave = true;
        try {
            $this->founding->provision(self::id(), self::locator());
        } catch (\RuntimeException) {
        }
        $this->founding->provision(self::id(), self::locator());
        $this->founding->provision(self::id(), self::locator());

        $this->assertSame(SchemaState::Current, $this->registry->find(self::id())->schemaState());
        $this->assertTrue(self::locator()->equals($this->registry->find(self::id())->locator()));
    }

    public function test_5b_another_locator_for_a_bound_organisation_is_refused_and_nothing_is_prepared_before_provisioning(): void
    {
        $this->founding->register(self::id(), self::application());
        try {
            $this->founding->provision(self::id(), self::locator());
            $this->fail('a pending organisation was given a database');
        } catch (ServedOrganisationRefused) {
            $this->assertSame([], $this->provisioner->calls);
        }

        $this->founding->verifyCounterparty(self::id(), new CounterpartyVerification('agreement:1'));
        $this->founding->recordServiceOperationGrant(self::id(), self::grant());
        $this->founding->startProvisioning(self::id());
        $this->founding->provision(self::id(), self::locator());
        $this->expectException(FoundingRefused::class);
        $this->founding->provision(self::id(), self::locator('org_other'));
    }

    private static function application(string $evidence = 'founding-evidence:minutes'): FoundingApplication
    {
        return new FoundingApplication($evidence, '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']);
    }

    private static function grant(string $terms = 'terms-2026-11'): ServiceOperationGrant
    {
        return new ServiceOperationGrant($terms, new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:applicant-1', [str_repeat('a', 64)]);
    }

    private static function id(string $id = self::ID): TenantId
    {
        return TenantId::fromString($id);
    }

    public function test_t14_a_legacy_organisation_id_never_enters_the_registry(): void
    {
        try {
            $this->founding->register(self::id(self::LEGACY), self::application());
            $this->fail('a legacy organisation entered the registry');
        } catch (FoundingRefused $e) {
            $this->assertStringContainsString('legacy', $e->getMessage());
        }
        $this->assertNull($this->registry->find(self::id(self::LEGACY)));
    }

    public function test_steps_one_and_two_reach_provisioning(): void
    {
        $this->founding->register(self::id(), self::application());
        $this->assertSame(PlatformStatus::Pending, $this->registry->find(self::id())->status());

        $this->founding->verifyCounterparty(self::id(), new CounterpartyVerification('agreement:1'));
        $this->founding->recordServiceOperationGrant(self::id(), self::grant());
        $this->founding->startProvisioning(self::id());

        $this->assertSame(PlatformStatus::Provisioning, $this->registry->find(self::id())->status());
    }

    public function test_t9_a_crash_at_each_step_resumes_to_the_same_state_by_repeating_the_step(): void
    {
        $steps = [
            fn () => $this->founding->register(self::id(), self::application()),
            fn () => $this->founding->verifyCounterparty(self::id(), new CounterpartyVerification('agreement:1')),
            fn () => $this->founding->recordServiceOperationGrant(self::id(), self::grant()),
            fn () => $this->founding->startProvisioning(self::id()),
        ];
        foreach ($steps as $i => $step) {
            $this->registry->failNextSave = true;
            try {
                $step();
                $this->fail("step {$i}: the simulated crash did not happen");
            } catch (\RuntimeException $e) {
                $this->assertSame('simulated crash before commit', $e->getMessage());
            }
            $step();   // resumed
            $step();   // and repeated once more: a no-op
        }
        $this->assertSame(PlatformStatus::Provisioning, $this->registry->find(self::id())->status());
    }

    public function test_a_step_repeated_with_different_input_is_refused(): void
    {
        $this->founding->register(self::id(), self::application());
        try {
            $this->founding->register(self::id(), self::application('founding-evidence:another'));
            $this->fail('a second, different application was accepted');
        } catch (FoundingRefused $e) {
            $this->assertStringContainsString('already registered', $e->getMessage());
        }
        $this->founding->verifyCounterparty(self::id(), new CounterpartyVerification('agreement:1'));
        $this->founding->recordServiceOperationGrant(self::id(), self::grant());
        foreach ([
            fn () => $this->founding->verifyCounterparty(self::id(), new CounterpartyVerification('agreement:2')),
            fn () => $this->founding->recordServiceOperationGrant(self::id(), self::grant('terms-other')),
        ] as $different) {
            try {
                $different();
                $this->fail('a different record replaced the first');
            } catch (ServedOrganisationRefused) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_step_for_an_unknown_organisation_is_refused(): void
    {
        $this->expectException(FoundingRefused::class);
        $this->founding->startProvisioning(self::id());
    }
}
