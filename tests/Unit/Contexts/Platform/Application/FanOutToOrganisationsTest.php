<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Application;

use App\Contexts\Platform\Application\FanOutToOrganisations;
use App\Contexts\Platform\Application\Ports\OrganisationWorkDispatch;
use App\Contexts\Platform\Application\Ports\RegisteredOrganisations;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Support\Platform\InMemoryServedOrganisationRepository;

/**
 * PBDIGIT-345 task 8 (E-3; T-5, application half): a scheduled operation fans out to every registered organisation.
 * - eligibility is the registry's own routing rule (ResolutionService: operational, schema current): never decided here;
 * - not routable → skipped with its reason, nothing dispatched; one dispatch failing → recorded, the others continue;
 * - exactly one dispatch per eligible organisation per run, in registry order;
 * - the registry unreadable → the whole run fails (fail closed), nothing is reported as done.
 */
final class FanOutToOrganisationsTest extends TestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';
    private const PENDING = '2d7a5c30-4e3f-4d5e-9c5b-4f7e0a3b6c82';
    private const FAILED_SCHEMA = '3e6b4d2f-3f4a-4c4b-8d6c-5a8f1b4c7d93';
    private const E = '4f5a6b7c-8d9e-4f0a-9b1c-2d3e4f5a6b7c';

    private InMemoryServedOrganisationRepository $registry;

    protected function setUp(): void
    {
        $this->registry = new InMemoryServedOrganisationRepository();
        foreach ([self::A, self::B, self::E] as $id) {
            $this->registry->add(self::operational($id));
        }
        $this->registry->add(ServedOrganisation::register(TenantId::fromString(self::PENDING), self::application()));
        $failed = self::operational(self::FAILED_SCHEMA);
        $failed->markMigrationFailed();
        $this->registry->add($failed);
    }

    private static function application(): FoundingApplication
    {
        return new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']);
    }

    private static function operational(string $id): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString($id), self::application());
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:' . $id));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $o->startProvisioning();
        $o->assignLocator(new DatabaseLocator('cluster-1', 'org_' . substr(str_replace('-', '', $id), 0, 12), 'env:ORG_CLUSTER_1'));
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));

        return $o;
    }

    /** @param list<string> $ids */
    private static function listing(array $ids, bool $unreadable = false): RegisteredOrganisations
    {
        return new class ($ids, $unreadable) implements RegisteredOrganisations {
            /** @param list<string> $ids */
            public function __construct(private readonly array $ids, private readonly bool $unreadable)
            {
            }

            public function ids(): array
            {
                if ($this->unreadable) {
                    throw new \RuntimeException('registry unavailable');
                }

                return array_map(static fn (string $id): TenantId => TenantId::fromString($id), $this->ids);
            }
        };
    }

    /** @return OrganisationWorkDispatch&object{dispatched: list<string>} */
    private static function work(string ...$failFor): OrganisationWorkDispatch
    {
        return new class ($failFor) implements OrganisationWorkDispatch {
            /** @var list<string> */
            public array $dispatched = [];

            /** @param list<string> $failFor */
            public function __construct(private readonly array $failFor)
            {
            }

            public function dispatchFor(TenantId $organisation): void
            {
                if (in_array($organisation->value(), $this->failFor, true)) {
                    throw new \LogicException('simulated dispatch failure');
                }
                $this->dispatched[] = $organisation->value();
            }
        };
    }

    private function fanOut(RegisteredOrganisations $listing): FanOutToOrganisations
    {
        return new FanOutToOrganisations($listing, new ResolutionService($this->registry));
    }

    public function test_t5_only_routable_organisations_get_work_one_each_the_others_are_skipped_with_their_reason(): void
    {
        $work = self::work();

        $report = $this->fanOut(self::listing([self::A, self::PENDING, self::B, self::FAILED_SCHEMA, self::E]))->run($work);

        $this->assertSame([self::A, self::B, self::E], $work->dispatched, 'one dispatch each, in registry order');
        $this->assertSame([self::A, self::B, self::E], $report->dispatched);
        $this->assertSame([self::PENDING => 'not-operational', self::FAILED_SCHEMA => 'schema-not-current'], $report->skipped);
        $this->assertSame([], $report->failed);
        $this->assertFalse($report->hasFailures());
    }

    public function test_t5_one_failing_dispatch_never_stops_the_others(): void
    {
        $work = self::work(self::A);

        $report = $this->fanOut(self::listing([self::A, self::B, self::E]))->run($work);

        $this->assertSame([self::B, self::E], $work->dispatched, 'the organisations after the failing one still get their work');
        $this->assertSame([self::B, self::E], $report->dispatched);
        $this->assertSame([self::A => \LogicException::class], $report->failed, 'recorded with the error class only');
        $this->assertTrue($report->hasFailures());
    }

    public function test_an_unreadable_registry_fails_the_whole_run(): void
    {
        $work = self::work();
        $this->expectException(\RuntimeException::class);
        try {
            $this->fanOut(self::listing([self::A], unreadable: true))->run($work);
        } finally {
            $this->assertSame([], $work->dispatched);
        }
    }
}
