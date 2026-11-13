<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Identity;

use App\Contexts\Identity\Application\ActingContextRef;
use App\Contexts\Identity\Application\ActingContextRefRefusal;
use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Application\Ports\ContextPersonRefs;
use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Domain\AccountId;
use App\Contexts\Identity\Domain\ContextPersonRef;
use App\Contexts\Identity\Domain\PersonId;
use App\Contexts\Identity\Domain\PlatformContext;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-0 (D-225 IG-1 … IG-3; D-226 §5a). The acting person's reference for one platform context:
 * signed-in Account → active? → Person → ContextPersonRef. Every failure is a refusal, never a guessed reference.
 * Database-free: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Identity
 */
final class ActingContextRefTest extends TestCase
{
    private const ACCOUNT = '5f0c3a9e-1d2b-4c6e-8a7f-9b1e2d3c4a5b';
    private const PERSON = '7a1b2c3d-4e5f-4a6b-8c7d-9e0f1a2b3c4d';
    private const REF = '0123456789abcdef0123456789abcdef';

    private static function current(?string $account): CurrentAccount
    {
        return new class ($account) implements CurrentAccount {
            public function __construct(private ?string $a) {}
            public function accountId(): ?AccountId { return $this->a === null ? null : AccountId::fromString($this->a); }
        };
    }

    private static function accounts(?string $person, bool $down = false): AccountDirectory
    {
        return new class ($person, $down) implements AccountDirectory {
            public function __construct(private ?string $p, private bool $down) {}
            public function activePersonOf(AccountId $account): ?PersonId
            {
                if ($this->down) {
                    throw new IdentityUnavailable('directory down');
                }

                return $this->p === null ? null : PersonId::fromString($this->p);
            }
        };
    }

    private static function refs(bool $down = false): ContextPersonRefs
    {
        return new class ($down) implements ContextPersonRefs {
            /** @var list<array{string, string}> */
            public array $asked = [];
            public function __construct(private bool $down) {}
            public function refFor(PersonId $person, PlatformContext $context): ContextPersonRef
            {
                if ($this->down) {
                    throw new IdentityUnavailable('refs down');
                }
                $this->asked[] = [$person->value(), $context->value];

                return ContextPersonRef::fromString(self::REF_VALUE);
            }
            private const REF_VALUE = '0123456789abcdef0123456789abcdef';
        };
    }

    public function test_an_active_signed_in_account_gets_its_persons_reference_for_the_context(): void
    {
        $refs = self::refs();
        $answer = (new ActingContextRef(self::current(self::ACCOUNT), self::accounts(self::PERSON), $refs))->for(PlatformContext::ReferenceGeography);

        $this->assertTrue($answer->isGranted());
        $this->assertSame(self::REF, $answer->ref()->value());
        $this->assertSame([[self::PERSON, 'reference-geography']], $refs->asked);
    }

    public function test_nobody_signed_in_is_refused(): void
    {
        $answer = (new ActingContextRef(self::current(null), self::accounts(self::PERSON), self::refs()))->for(PlatformContext::ReferenceGeography);

        $this->assertSame(ActingContextRefRefusal::NotSignedIn, $answer->refusal());
    }

    public function test_an_account_that_is_not_active_is_refused(): void
    {
        $answer = (new ActingContextRef(self::current(self::ACCOUNT), self::accounts(null), self::refs()))->for(PlatformContext::ReferenceGeography);

        $this->assertSame(ActingContextRefRefusal::AccountNotActive, $answer->refusal());
    }

    public function test_identity_unavailable_is_refused_never_guessed(): void
    {
        $directoryDown = (new ActingContextRef(self::current(self::ACCOUNT), self::accounts(self::PERSON, down: true), self::refs()))->for(PlatformContext::ReferenceGeography);
        $refsDown = (new ActingContextRef(self::current(self::ACCOUNT), self::accounts(self::PERSON), self::refs(down: true)))->for(PlatformContext::ReferenceGeography);

        $this->assertSame(ActingContextRefRefusal::IdentityUnavailable, $directoryDown->refusal());
        $this->assertSame(ActingContextRefRefusal::IdentityUnavailable, $refsDown->refusal());
    }

    public function test_a_refused_answer_has_no_reference_to_read(): void
    {
        $answer = (new ActingContextRef(self::current(null), self::accounts(null), self::refs()))->for(PlatformContext::ReferenceGeography);

        $this->assertFalse($answer->isGranted());
        $this->expectException(\LogicException::class);
        $answer->ref();
    }

    public function test_a_context_person_ref_is_32_lower_case_hex_and_nothing_else(): void
    {
        $this->assertSame(self::REF, ContextPersonRef::fromString(self::REF)->value());

        foreach (['', self::PERSON, strtoupper(self::REF), substr(self::REF, 1)] as $bad) {
            try {
                ContextPersonRef::fromString($bad);
                $this->fail("'{$bad}' must be refused");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_the_only_platform_context_is_reference_geography(): void
    {
        $this->assertSame(['reference-geography'], array_map(static fn (PlatformContext $c): string => $c->value, PlatformContext::cases()));
    }

    public function test_ids_are_uuids(): void
    {
        $this->assertSame(self::PERSON, PersonId::fromString(self::PERSON)->value());
        $this->assertSame(self::ACCOUNT, AccountId::fromString(self::ACCOUNT)->value());

        $this->expectException(\InvalidArgumentException::class);
        PersonId::fromString('not-a-uuid');
    }
}
