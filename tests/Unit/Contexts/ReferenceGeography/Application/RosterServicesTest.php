<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\ActingPersonRefused;
use App\Contexts\ReferenceGeography\Application\IdentityNoticeConsumer;
use App\Contexts\ReferenceGeography\Application\PlatformOwnerRequired;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\PlatformOwnerAuthority;
use App\Contexts\ReferenceGeography\Application\StewardRosterService;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RosterEntryKind;
use App\Contexts\ReferenceGeography\Infrastructure\UnestablishedPlatformOwnerAuthority;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\InMemoryPublicationLedger;
use Tests\Support\ReferenceGeography\InMemoryStewardRosterStore;

/**
 * PBDIGIT-346 G-4c, application half (D-84, D-84a; G-3 acceptance item 3; G-4 interpretation §5):
 * - appointments, ends and matter-reviewer appointments are made by the acting person, only if the platform-owner
 *   boundary confirms them; refused otherwise, and nothing is written;
 * - the production binding of that boundary refuses everyone until the Principal establishes who the platform owner is;
 * - a conflict declaration is made by the acting person for themselves only;
 * - Identity notices are consumed without an acting person; a duplicate writes nothing.
 */
final class RosterServicesTest extends TestCase
{
    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    private static function acting(?string $label): ActingPerson
    {
        return new class ($label === null ? null : self::p($label)) implements ActingPerson {
            public function __construct(private readonly ?PersonReference $person)
            {
            }

            public function current(): PersonReference
            {
                return $this->person ?? throw new ActingPersonRefused('not-signed-in');
            }
        };
    }

    private static function owner(string ...$owners): PlatformOwnerAuthority
    {
        return new class (array_map(static fn (string $o): string => self::p($o)->value(), $owners)) implements PlatformOwnerAuthority {
            public function __construct(private readonly array $owners)
            {
            }

            public function isPlatformOwner(PersonReference $person): bool
            {
                return in_array($person->value(), $this->owners, true);
            }
        };
    }

    private static function matter(): RevisionId
    {
        return RevisionId::fromString('9d9d9d9d-9d9d-4d9d-8d9d-9d9d9d9d9d9d');
    }

    public function test_the_platform_owner_appoints_and_ends_stewards_and_appoints_matter_reviewers(): void
    {
        $store = new InMemoryStewardRosterStore();
        $service = new StewardRosterService($store, self::acting('owner'), self::owner('owner'));

        $this->assertSame(1, $service->appointSteward(self::p('a')));
        $this->assertSame(2, $service->appointMatterReviewer(self::p('r'), self::matter()));
        $this->assertSame(3, $service->endSteward(self::p('a')));
        $this->assertTrue($store->current()->entries()[0]->by->equals(self::p('owner')), 'the act records who made it');
    }

    public function test_anyone_else_is_refused_and_nothing_is_written(): void
    {
        $store = new InMemoryStewardRosterStore();
        $service = new StewardRosterService($store, self::acting('someone'), self::owner('owner'));

        foreach ([fn () => $service->appointSteward(self::p('a')), fn () => $service->appointMatterReviewer(self::p('r'), self::matter())] as $act) {
            try {
                $act();
                $this->fail('must be refused');
            } catch (PlatformOwnerRequired) {
                $this->addToAssertionCount(1);
            }
        }
        $this->assertSame(0, $store->current()->version());
    }

    public function test_the_production_authority_refuses_everyone_until_the_platform_owner_is_established(): void
    {
        $store = new InMemoryStewardRosterStore();
        $service = new StewardRosterService($store, self::acting('owner'), new UnestablishedPlatformOwnerAuthority());

        $this->expectException(PlatformOwnerRequired::class);
        try {
            $service->appointSteward(self::p('a'));
        } finally {
            $this->assertSame(0, $store->current()->version());
        }
    }

    public function test_without_an_acting_person_nothing_is_written(): void
    {
        $store = new InMemoryStewardRosterStore();
        $service = new StewardRosterService($store, self::acting(null), self::owner('owner'));

        $this->expectException(ActingPersonRefused::class);
        try {
            $service->appointSteward(self::p('a'));
        } finally {
            $this->assertSame(0, $store->current()->version());
        }
    }

    public function test_a_conflict_declaration_is_made_by_the_acting_person_for_themselves(): void
    {
        $store = new InMemoryStewardRosterStore();
        $service = new StewardRosterService($store, self::acting('b'), self::owner());   // no platform-owner rights needed

        $v = $service->declareConflict(self::matter(), true);

        $entry = $store->current()->entries()[0];
        $this->assertSame(1, $v);
        $this->assertSame(RosterEntryKind::ConflictDeclared, $entry->kind);
        $this->assertTrue($entry->subject->equals(self::p('b')));
        $this->assertTrue($store->current()->declaredInterestAt(self::p('b'), self::matter(), 1));
    }

    public function test_identity_notices_are_consumed_once(): void
    {
        $store = new InMemoryStewardRosterStore();
        $consumer = new IdentityNoticeConsumer(new InMemoryPublicationLedger($store), new PublicationGovernance());

        $this->assertSame(1, $consumer->merged('m-1', [self::p('a'), self::p('b')]));
        $this->assertNull($consumer->merged('m-1', [self::p('a'), self::p('b')]), 'a replay writes nothing');
        $this->assertSame(2, $consumer->reversed('m-1'));
        $this->assertSame(2, $store->current()->version());
    }
}
