<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Platform\Infrastructure\ContainerRoutedOrganisation;
use App\Contexts\Platform\Infrastructure\OrganisationContext;
use App\Contexts\Platform\Infrastructure\Published\NotTheRoutedOrganisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Container\Container;
use Illuminate\Database\Connection;
use PHPUnit\Framework\TestCase;
use Tests\Support\Platform\InMemoryServedOrganisationRepository;

/**
 * PBDIGIT-345 task 10a (D-233 D-1, D-2; proposal §8.2): Platform's ONE adapter behind its published routing contract.
 * - current(): the organisation of the operation's bound OrganisationContext (E-1 request, E-2 job attempt), else null;
 * - isRegistryRouted(org): true only for the CURRENT organisation AND only if the registry routes it NOW (re-asked on
 *   every call); a registry that cannot answer throws (never false, never true);
 * - connectionFor(org): the current organisation's own connection only; any other organisation, or none → refused.
 */
final class ContainerRoutedOrganisationTest extends TestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';

    private Container $container;
    private InMemoryServedOrganisationRepository $registry;
    private ContainerRoutedOrganisation $routed;

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->registry = new InMemoryServedOrganisationRepository();
        foreach ([self::A, self::B] as $id) {
            $this->registry->add(self::operational($id));
        }
        $this->routed = new ContainerRoutedOrganisation($this->container, new ResolutionService($this->registry));
    }

    private static function operational(string $id): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString($id), new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']));
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:' . $id));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $o->startProvisioning();
        $o->assignLocator(new DatabaseLocator('cluster-1', 'org_' . substr(str_replace('-', '', $id), 0, 12), 'env:ORG_CLUSTER_1'));
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));

        return $o;
    }

    private function routeTo(string $id): Connection
    {
        $connection = new Connection(static fn () => null);
        $this->container->instance(OrganisationContext::class, new OrganisationContext(TenantId::fromString($id), $connection));

        return $connection;
    }

    private function refusedConnection(string $id): void
    {
        try {
            $this->routed->connectionFor(TenantId::fromString($id));
            $this->fail('a connection was handed out for ' . $id);
        } catch (NotTheRoutedOrganisation) {
            $this->addToAssertionCount(1);
        }
    }

    public function test_outside_a_routed_operation_nothing_is_current_routed_or_connectable(): void
    {
        $this->assertNull($this->routed->current());
        $this->assertFalse($this->routed->isRegistryRouted(TenantId::fromString(self::A)));
        $this->refusedConnection(self::A);
    }

    public function test_inside_a_routed_operation_only_its_organisation_is_current_routed_and_connectable(): void
    {
        $connection = $this->routeTo(self::A);

        $this->assertSame(self::A, $this->routed->current()?->value());
        $this->assertTrue($this->routed->isRegistryRouted(TenantId::fromString(self::A)));
        $this->assertFalse($this->routed->isRegistryRouted(TenantId::fromString(self::B)), 'B is operational, but not the current organisation');
        $this->assertSame($connection, $this->routed->connectionFor(TenantId::fromString(self::A)));
        $this->refusedConnection(self::B);
    }

    public function test_the_registry_is_re_asked_on_every_call(): void
    {
        $this->routeTo(self::A);
        $this->assertTrue($this->routed->isRegistryRouted(TenantId::fromString(self::A)));

        $a = $this->registry->find(TenantId::fromString(self::A));
        $a->markMigrationFailed();
        $this->registry->save($a);

        $this->assertFalse($this->routed->isRegistryRouted(TenantId::fromString(self::A)), 'no longer routable: refused at once');
        $this->assertSame(self::A, $this->routed->current()?->value(), 'the operation is still routed to A (its context lives to the end of the operation)');
    }

    public function test_a_registry_that_cannot_answer_fails_closed_by_an_exception(): void
    {
        $this->routeTo(self::A);
        $broken = new class implements ServedOrganisationRepository {
            public function find(TenantId $id): ?ServedOrganisation
            {
                throw new \RuntimeException('registry unavailable');
            }

            public function add(ServedOrganisation $organisation): void
            {
            }

            public function save(ServedOrganisation $organisation): void
            {
            }
        };
        $routed = new ContainerRoutedOrganisation($this->container, new ResolutionService($broken));

        $this->expectException(\RuntimeException::class);
        $routed->isRegistryRouted(TenantId::fromString(self::A));
    }
}
