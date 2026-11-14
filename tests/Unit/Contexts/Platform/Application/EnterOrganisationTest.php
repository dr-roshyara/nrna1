<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Application;

use App\Contexts\Platform\Application\EnterOrganisation;
use App\Contexts\Platform\Application\EntryRefusal;
use App\Contexts\Platform\Application\OrganisationEntryRefused;
use App\Contexts\Platform\Application\Ports\BindingsUnavailable;
use App\Contexts\Platform\Application\Ports\IdentityBindings;
use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Support\Platform\InMemoryServedOrganisationRepository;

/**
 * PBDIGIT-345 task 6 (E-1; ISO-2, ISO-3; D-219 (2) shared hostname): entering an organisation over HTTP.
 * - select: a signed-in account chooses an organisation; refused unless well-formed, not legacy, bound to the account
 *   (Identity's CURRENT bindings, ISO-3) and routable through the registry (ISO-2). The selection is only a request;
 * - enter (every request): the selection is checked AGAIN in full; any organisation id the request itself carries
 *   (route, body, header) that differs from the selection is refused (ISO-3 conflicting id);
 * - the locator always comes from the registry, never from the request.
 */
final class EnterOrganisationTest extends TestCase
{
    private const ACCOUNT = '5f4e3d2c-1b0a-4f9e-8d7c-6b5a4f3e2d1c';
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';
    private const PENDING = '2d7a5c30-4e3f-4d5e-9c5b-4f7e0a3b6c82';
    private const LEGACY = '9f8e7d6c-5b4a-4392-8170-6f5e4d3c2b1a';

    private InMemoryServedOrganisationRepository $registry;
    /** @var object{bound: array<string, list<string>>, unavailable: bool} */
    private object $bindings;
    private EnterOrganisation $entry;

    protected function setUp(): void
    {
        $this->registry = new InMemoryServedOrganisationRepository();
        foreach ([self::A, self::B] as $id) {
            $this->registry->add(self::operational($id));
        }
        $this->registry->add(ServedOrganisation::register(TenantId::fromString(self::PENDING), self::application()));
        $this->bindings = new class implements IdentityBindings {
            /** @var array<string, list<string>> account => organisations */
            public array $bound = ['5f4e3d2c-1b0a-4f9e-8d7c-6b5a4f3e2d1c' => ['0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60', '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71',
                '2d7a5c30-4e3f-4d5e-9c5b-4f7e0a3b6c82', '9f8e7d6c-5b4a-4392-8170-6f5e4d3c2b1a']];
            public bool $unavailable = false;

            public function isBound(string $accountId, TenantId $organisation): bool
            {
                if ($this->unavailable) {
                    throw new BindingsUnavailable('simulated');
                }

                return in_array($organisation->value(), $this->bound[$accountId] ?? [], true);
            }
        };
        $legacy = new class implements LegacyOrganisationRegister {
            public function contains(TenantId $id): bool
            {
                return $id->value() === '9f8e7d6c-5b4a-4392-8170-6f5e4d3c2b1a';
            }
        };
        $this->entry = new EnterOrganisation($this->bindings, $legacy, new ResolutionService($this->registry));
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

    private function refusal(\Closure $call): EntryRefusal
    {
        try {
            $call();
        } catch (OrganisationEntryRefused $e) {
            return $e->reason;
        }
        $this->fail('not refused');
    }

    public function test_a_bound_operational_organisation_is_selected_and_entered_with_its_registry_locator(): void
    {
        $selected = $this->entry->select(self::ACCOUNT, strtoupper(self::A));

        $this->assertSame(self::A, $selected->value(), 'kept in canonical form');
        $resolved = $this->entry->enter(self::ACCOUNT, $selected->value(), []);
        $this->assertSame(self::A, $resolved->organisationId->value());
        $this->assertTrue($this->registry->find(TenantId::fromString(self::A))->locator()->equals($resolved->locator));
        $this->assertSame(self::B, $this->entry->enter(self::ACCOUNT, self::B, [self::B, strtoupper(self::B)])->organisationId->value(),
            'the same id carried by the request is no conflict');
    }

    /** @return array<string, array{?string, string, EntryRefusal}> account, requested id, refusal */
    public static function selectionRefusals(): array
    {
        return [
            'nobody signed in' => [null, self::A, EntryRefusal::NotSignedIn],
            'a malformed id' => [self::ACCOUNT, "a'; DROP TABLE x; --", EntryRefusal::MalformedId],
            'a legacy organisation (T-10 part 3)' => [self::ACCOUNT, self::LEGACY, EntryRefusal::LegacyOrganisation],
            'not bound to the account (T-15)' => ['6e5d4c3b-2a19-4f8e-9d7c-6b5a4f3e2d10', self::A, EntryRefusal::NotBound],
            'bound but unknown to the registry' => [self::ACCOUNT, '3e6b4d2f-3f4a-4c4b-8d6c-5a8f1b4c7d93', EntryRefusal::NotBound],
            'bound but not operational' => [self::ACCOUNT, self::PENDING, EntryRefusal::NotOperational],
        ];
    }

    #[DataProvider('selectionRefusals')]
    public function test_a_selection_is_refused(?string $account, string $requested, EntryRefusal $expected): void
    {
        $this->assertSame($expected, $this->refusal(fn () => $this->entry->select($account, $requested)));
    }

    public function test_an_unknown_registry_id_is_refused_as_unknown_once_bound(): void
    {
        $this->bindings->bound[self::ACCOUNT][] = '3e6b4d2f-3f4a-4c4b-8d6c-5a8f1b4c7d93';
        $this->assertSame(EntryRefusal::Unknown, $this->refusal(fn () => $this->entry->select(self::ACCOUNT, '3e6b4d2f-3f4a-4c4b-8d6c-5a8f1b4c7d93')));
    }

    /** @return array<string, array{?string, list<string>, EntryRefusal}> selection, request-carried ids, refusal */
    public static function entryRefusals(): array
    {
        return [
            'no selection' => [null, [], EntryRefusal::NoSelection],
            'a forged, malformed session value (T-15)' => ['../../etc', [], EntryRefusal::MalformedId],
            'a conflicting id in the request (T-1)' => [self::A, [self::B], EntryRefusal::ConflictingId],
            'a malformed id in the request' => [self::A, ['nonsense'], EntryRefusal::ConflictingId],
            'a legacy organisation in the session (T-10 part 3)' => [self::LEGACY, [], EntryRefusal::LegacyOrganisation],
            'a forged session value: an organisation not bound (T-15)' => ['3e6b4d2f-3f4a-4c4b-8d6c-5a8f1b4c7d93', [], EntryRefusal::NotBound],
        ];
    }

    /** @param list<string> $carried */
    #[DataProvider('entryRefusals')]
    public function test_a_request_is_refused(?string $selection, array $carried, EntryRefusal $expected): void
    {
        $this->assertSame($expected, $this->refusal(fn () => $this->entry->enter(self::ACCOUNT, $selection, $carried)));
    }

    public function test_t15_a_binding_revoked_after_selection_refuses_the_next_request(): void
    {
        $selected = $this->entry->select(self::ACCOUNT, self::A);
        $this->entry->enter(self::ACCOUNT, $selected->value(), []);

        $this->bindings->bound[self::ACCOUNT] = [self::B];

        $this->assertSame(EntryRefusal::NotBound, $this->refusal(fn () => $this->entry->enter(self::ACCOUNT, $selected->value(), [])));
    }

    public function test_the_signed_in_account_is_checked_on_every_request(): void
    {
        $this->assertSame(EntryRefusal::NotSignedIn, $this->refusal(fn () => $this->entry->enter(null, self::A, [])));
        $this->assertSame(EntryRefusal::NotBound, $this->refusal(fn () => $this->entry->enter('6e5d4c3b-2a19-4f8e-9d7c-6b5a4f3e2d10', self::A, [])),
            'a selection made by one account is no proof for another');
    }

    public function test_identity_unavailable_fails_closed(): void
    {
        $this->bindings->unavailable = true;
        $this->expectException(BindingsUnavailable::class);
        $this->entry->enter(self::ACCOUNT, self::A, []);
    }
}
