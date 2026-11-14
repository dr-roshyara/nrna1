<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Application;

use App\Contexts\Platform\Application\FoundingRefused;
use App\Contexts\Platform\Application\FoundOrganisation;
use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
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
        $this->founding = new FoundOrganisation($this->registry, $legacy);
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
