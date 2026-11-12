<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Outbox;

use App\Contexts\Membership\Application\Organisational\PublishedLanguage\CoverageInterval;
use App\Contexts\Membership\Application\Organisational\PublishedLanguage\MembershipCoverageResolved;
use App\Contexts\Membership\Infrastructure\Outbox\MembershipCoverageResolvedHydrator;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-343 task 7 (plan D-6; CR-01 C1, CR-02 schemaVersion 2): the C1 row's payload, exactly as
 * MembershipCoverageResolvedOutbox writes it, hydrates into the Published Language event. A payload that is not
 * that contract fails loudly (the relay then retries and finally marks it failed); nothing is guessed.
 */
final class MembershipCoverageResolvedHydratorTest extends TestCase
{
    /** @return array<string, mixed> */
    private static function payload(): array
    {
        return [
            'eventId' => '0b7a6c1e-2f3d-4e5a-9b8c-7d6e5f4a3b2c',
            'organisationId' => '6f1b0c3e-8d2a-4b7e-9f10-2a3b4c5d6e7f',
            'membershipId' => 'M1',
            'personRef' => 'person-M1',
            'seq' => 2,
            'coverage' => [['from' => '2024-10-04', 'to' => '2025-01-12']],
            'inForce' => [['from' => '2024-10-04', 'to' => '2024-11-23']],
            'suspended' => [],
            'recordedAt' => 1_760_000_000_000_123,
            'schemaVersion' => 2,
        ];
    }

    public function test_it_owns_the_c1_event_type(): void
    {
        $this->assertSame('MembershipCoverageResolved', (new MembershipCoverageResolvedHydrator())->eventType());
    }

    public function test_the_payload_hydrates_into_the_published_language_event(): void
    {
        $event = (new MembershipCoverageResolvedHydrator())->hydrate(self::payload());

        $this->assertInstanceOf(MembershipCoverageResolved::class, $event);
        $this->assertSame('0b7a6c1e-2f3d-4e5a-9b8c-7d6e5f4a3b2c', $event->eventId);
        $this->assertSame('6f1b0c3e-8d2a-4b7e-9f10-2a3b4c5d6e7f', $event->organisationId);
        $this->assertSame('M1', $event->membershipId);
        $this->assertSame('person-M1', $event->personRef);
        $this->assertSame(2, $event->seq);
        $this->assertEquals([new CoverageInterval('2024-10-04', '2025-01-12')], $event->coverage);
        $this->assertEquals([new CoverageInterval('2024-10-04', '2024-11-23')], $event->inForce);
        $this->assertSame([], $event->suspended);
        $this->assertSame(1_760_000_000_000_123, $event->recordedAtMicros);
        $this->assertSame(2, $event->schemaVersion);
    }

    public function test_an_open_ended_interval_keeps_its_null_end(): void
    {
        $payload = self::payload();
        $payload['coverage'] = [['from' => '2024-10-04', 'to' => null]];

        $event = (new MembershipCoverageResolvedHydrator())->hydrate($payload);

        $this->assertNull($event->coverage[0]->to);
    }

    /** @return iterable<string, array{0: \Closure(array): array}> */
    public static function brokenPayloads(): iterable
    {
        yield 'missing membershipId' => [static function (array $p): array { unset($p['membershipId']); return $p; }];
        yield 'missing coverage' => [static function (array $p): array { unset($p['coverage']); return $p; }];
        yield 'seq not an integer' => [static function (array $p): array { $p['seq'] = 'two'; return $p; }];
        yield 'unknown schema version' => [static function (array $p): array { $p['schemaVersion'] = 3; return $p; }];
        yield 'interval without a from' => [static function (array $p): array { $p['inForce'] = [['to' => null]]; return $p; }];
        yield 'interval date not a date' => [static function (array $p): array { $p['coverage'] = [['from' => '04.10.2024', 'to' => null]]; return $p; }];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('brokenPayloads')]
    public function test_a_payload_outside_the_contract_fails_loudly(\Closure $break): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new MembershipCoverageResolvedHydrator())->hydrate($break(self::payload()));
    }
}
