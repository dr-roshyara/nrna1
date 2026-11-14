<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Application;

use App\Contexts\Platform\Application\OrganisationNotRoutable;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Application\RoutingRefusal;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 2, T-2 (ISO-2, D-193 Part 4): the ONLY way to obtain where an organisation's data lives.
 * - an organisation id goes in; the locator comes out only from the registry, never from the request;
 * - refused: unknown (or malformed) id, not operational, schema not current; nothing partial is returned;
 * - the refusal names its reason for logs, but never echoes a locator.
 */
final class ResolutionServiceTest extends TestCase
{
    private const OP = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const PENDING = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';
    private const PROVISIONING = '2d7a5c30-4e3f-4d5a-9c5b-4f7e0a3b6c82';
    private const FAILED = '3e6b4d2f-3f4a-4c4b-8d6c-5a8f1b4c7d93';

    private static function locator(string $id): DatabaseLocator
    {
        return new DatabaseLocator('cluster-1', 'org_' . substr(str_replace('-', '', $id), 0, 8), 'secret-store:' . $id);
    }

    private static function upTo(string $id, string $stage): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString($id), new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']));
        if ($stage === 'pending') {
            return $o;
        }
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:1'));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-12T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $o->startProvisioning();
        $o->assignLocator(self::locator($id));
        if ($stage === 'provisioning') {
            return $o;
        }
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));
        if ($stage === 'failed') {
            $o->markMigrationFailed();
        }

        return $o;
    }

    private static function service(): ResolutionService
    {
        $registry = [];
        foreach ([[self::OP, 'operational'], [self::PENDING, 'pending'], [self::PROVISIONING, 'provisioning'], [self::FAILED, 'failed']] as [$id, $stage]) {
            $registry[$id] = self::upTo($id, $stage);
        }

        return new ResolutionService(new class ($registry) implements ServedOrganisationRepository {
            /** @param array<string, ServedOrganisation> $registry */
            public function __construct(private array $registry)
            {
            }

            public function find(TenantId $id): ?ServedOrganisation
            {
                return $this->registry[$id->value()] ?? null;
            }

            public function add(ServedOrganisation $organisation): void
            {
                $this->registry[$organisation->id()->value()] = $organisation;
            }

            public function save(ServedOrganisation $organisation): void
            {
                $this->registry[$organisation->id()->value()] = $organisation;
            }
        });
    }

    public function test_an_operational_organisation_with_a_current_schema_resolves_to_its_registry_locator(): void
    {
        $resolved = self::service()->resolve(self::OP);

        $this->assertSame(self::OP, $resolved->organisationId->value());
        $this->assertTrue(self::locator(self::OP)->equals($resolved->locator));
    }

    /** @return array<string, array{string, RoutingRefusal}> */
    public static function refusals(): array
    {
        return [
            'unknown' => ['9f9f9f9f-9f9f-4f9f-8f9f-9f9f9f9f9f9f', RoutingRefusal::Unknown],
            'malformed id' => ["x'; DROP TABLE x; --", RoutingRefusal::Unknown],
            'pending' => [self::PENDING, RoutingRefusal::NotOperational],
            'provisioning (even with a locator)' => [self::PROVISIONING, RoutingRefusal::NotOperational],
            'operational, migration failed' => [self::FAILED, RoutingRefusal::SchemaNotCurrent],
        ];
    }

    #[DataProvider('refusals')]
    public function test_anything_not_routable_is_refused_with_its_reason_and_no_locator(string $id, RoutingRefusal $reason): void
    {
        try {
            self::service()->resolve($id);
            $this->fail('resolved an organisation that is not routable');
        } catch (OrganisationNotRoutable $e) {
            $this->assertSame($reason, $e->reason);
            $this->assertStringNotContainsString('secret-store', $e->getMessage(), 'a refusal never echoes a locator');
            $this->assertStringNotContainsString('cluster-1', $e->getMessage());
        }
    }
}
