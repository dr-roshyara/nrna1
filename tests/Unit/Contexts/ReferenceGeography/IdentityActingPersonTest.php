<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography;

use App\Contexts\Identity\Application\ActingContextRef;
use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Application\Ports\ContextPersonRefs;
use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Domain\AccountId;
use App\Contexts\Identity\Domain\ContextPersonRef;
use App\Contexts\Identity\Domain\PersonId;
use App\Contexts\Identity\Domain\PlatformContext;
use App\Contexts\Identity\Infrastructure\LaravelCurrentAccount;
use App\Contexts\ReferenceGeography\Application\ActingPersonRefused;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Infrastructure\IdentityActingPerson;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-0 (D-225 IG-2; D-226 §5a): Geography's view of "who is acting".
 * - Geography holds its own PersonReference value; its Domain and Application never import Identity.
 * - The adapter asks Identity for the 'reference-geography' context reference; any refusal becomes ActingPersonRefused.
 * - LaravelCurrentAccount reads the signed-in legacy users row id from the auth guard.
 */
final class IdentityActingPersonTest extends TestCase
{
    private const REF = 'fedcba9876543210fedcba9876543210';

    private static function identity(?string $account, ?string $person, bool $down = false): ActingContextRef
    {
        $current = new class ($account) implements CurrentAccount {
            public function __construct(private ?string $a) {}
            public function accountId(): ?AccountId { return $this->a === null ? null : AccountId::fromString($this->a); }
        };
        $accounts = new class ($person, $down) implements AccountDirectory {
            public function __construct(private ?string $p, private bool $down) {}
            public function activePersonOf(AccountId $account): ?PersonId
            {
                return $this->down ? throw new IdentityUnavailable('down') : ($this->p === null ? null : PersonId::fromString($this->p));
            }
        };
        $refs = new class () implements ContextPersonRefs {
            public ?string $context = null;
            public function refFor(PersonId $person, PlatformContext $context): ContextPersonRef
            {
                $this->context = $context->value;

                return ContextPersonRef::fromString('fedcba9876543210fedcba9876543210');
            }
        };

        return new ActingContextRef($current, $accounts, $refs);
    }

    public function test_the_acting_steward_is_geographys_own_person_reference(): void
    {
        $person = (new IdentityActingPerson(self::identity('5f0c3a9e-1d2b-4c6e-8a7f-9b1e2d3c4a5b', '7a1b2c3d-4e5f-4a6b-8c7d-9e0f1a2b3c4d')))->current();

        $this->assertInstanceOf(PersonReference::class, $person);
        $this->assertSame(self::REF, $person->value());
    }

    /** @return iterable<string, array{?string, ?string, bool, string}> */
    public static function refusals(): iterable
    {
        yield 'nobody signed in' => [null, null, false, 'not-signed-in'];
        yield 'account not active' => ['5f0c3a9e-1d2b-4c6e-8a7f-9b1e2d3c4a5b', null, false, 'account-not-active'];
        yield 'identity unavailable' => ['5f0c3a9e-1d2b-4c6e-8a7f-9b1e2d3c4a5b', null, true, 'identity-unavailable'];
    }

    /** @dataProvider refusals */
    #[\PHPUnit\Framework\Attributes\DataProvider('refusals')]
    public function test_every_identity_refusal_refuses_the_act(?string $account, ?string $person, bool $down, string $reason): void
    {
        try {
            (new IdentityActingPerson(self::identity($account, $person, $down)))->current();
            $this->fail('must refuse');
        } catch (ActingPersonRefused $e) {
            $this->assertSame($reason, $e->reason);
        }
    }

    public function test_a_person_reference_is_opaque_32_hex(): void
    {
        $this->assertTrue(PersonReference::fromString(self::REF)->equals(PersonReference::fromString(self::REF)));

        $this->expectException(\InvalidArgumentException::class);
        PersonReference::fromString('7a1b2c3d-4e5f-4a6b-8c7d-9e0f1a2b3c4d');
    }

    public function test_the_current_account_is_the_guards_user_id_or_nobody(): void
    {
        $guard = new class () implements Guard {
            public ?string $id = null;
            public function check() { return $this->id !== null; }
            public function guest() { return $this->id === null; }
            public function user() { return null; }
            public function id() { return $this->id; }
            public function validate(array $credentials = []) { return false; }
            public function hasUser() { return $this->id !== null; }
            public function setUser(Authenticatable $user) { return $this; }
        };

        $this->assertNull((new LaravelCurrentAccount($guard))->accountId());
        $guard->id = '5f0c3a9e-1d2b-4c6e-8a7f-9b1e2d3c4a5b';
        $this->assertSame('5f0c3a9e-1d2b-4c6e-8a7f-9b1e2d3c4a5b', (new LaravelCurrentAccount($guard))->accountId()?->value());
    }
}
