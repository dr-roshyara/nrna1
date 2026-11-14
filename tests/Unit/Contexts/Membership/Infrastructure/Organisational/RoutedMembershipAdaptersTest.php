<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Infrastructure\Organisational\RegistryRoutedLedgerSource;
use App\Contexts\Membership\Infrastructure\Organisational\RoutedWorkingOrganisation;
use App\Contexts\Platform\Application\Published\RoutedOrganisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 10b (routing design §3.5; D-233): Membership's production adapters M-1 and M-2 over Platform's
 * published RoutedOrganisation.
 * - M-1: the working organisation IS the routed one (null outside), never caller input;
 * - M-2: a ledger only when the registry routes the current organisation now; its only true path (T-14 part 2);
 *   a registry that cannot answer propagates (never "not a ledger").
 */
final class RoutedMembershipAdaptersTest extends TestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';

    /** @param array<string, bool|\Throwable> $routed */
    private static function routed(?string $current, array $routed = []): RoutedOrganisation
    {
        return new class ($current, $routed) implements RoutedOrganisation {
            /** @param array<string, bool|\Throwable> $routed */
            public function __construct(private readonly ?string $current, private readonly array $routed)
            {
            }

            public function current(): ?TenantId
            {
                return $this->current === null ? null : TenantId::fromString($this->current);
            }

            public function isRegistryRouted(TenantId $organisation): bool
            {
                $answer = $this->routed[$organisation->value()] ?? false;
                if ($answer instanceof \Throwable) {
                    throw $answer;
                }

                return $answer;
            }
        };
    }

    public function test_m1_the_working_organisation_is_the_routed_one_or_none(): void
    {
        $this->assertSame(self::A, (new RoutedWorkingOrganisation(self::routed(self::A)))->current()?->value());
        $this->assertNull((new RoutedWorkingOrganisation(self::routed(null)))->current());
    }

    public function test_m2_a_ledger_only_when_the_registry_routes_it_now(): void
    {
        $a = TenantId::fromString(self::A);
        $this->assertTrue((new RegistryRoutedLedgerSource(self::routed(self::A, [self::A => true])))->isLedger($a));
        $this->assertFalse((new RegistryRoutedLedgerSource(self::routed(self::A, [self::A => false])))->isLedger($a), 'not routable now');
        $this->assertFalse((new RegistryRoutedLedgerSource(self::routed(null)))->isLedger($a), 'outside any routed operation');
    }

    public function test_m2_a_registry_that_cannot_answer_is_never_read_as_not_a_ledger(): void
    {
        $this->expectException(\RuntimeException::class);
        (new RegistryRoutedLedgerSource(self::routed(self::A, [self::A => new \RuntimeException('registry unavailable')])))->isLedger(TenantId::fromString(self::A));
    }
}
