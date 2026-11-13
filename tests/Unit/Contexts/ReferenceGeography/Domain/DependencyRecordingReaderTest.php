<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\FamilyDependency;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotDependency;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-2c, cycle 2 (v3.11 §5.2 "Where the proof stops": the validator reads published state only through
 * dependency-recording queries). Every read is recorded with its interval at p₀; claims after p₀ are invisible.
 */
final class DependencyRecordingReaderTest extends TestCase
{
    private const C = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const E = '9e8d7c6b-5a4f-4e3d-8c2b-1a0f9e8d7c6b';
    private const A = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function pub(string $n, SlotKey $slot, string $value, string $from, int $position): PublishedClaim
    {
        $claim = new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', (int) $n)), $slot, ClaimValue::of($value), self::iv($from), new Basis(BasisKind::Instrument, 'act'));

        return new PublishedClaim($claim, BeforeImage::of(SlotHistory::for($slot), $claim->interval, $position - 1), $position);
    }

    private static function atlas(int $p0): InMemoryPublishedAtlas
    {
        $u = static fn (string $id): ReferenceUnitId => ReferenceUnitId::fromString($id);

        return new InMemoryPublishedAtlas($p0, [
            self::pub('1', SlotKey::containment($u(self::C), 'admin'), self::A, '2015-01-01', 1),
            self::pub('2', SlotKey::containment($u(self::E), 'admin'), self::A, '2016-06-01', 2),
            self::pub('3', SlotKey::containment($u(self::E), 'admin'), self::C, '2016-01-01', 9),   // after p0 = 5
        ]);
    }

    public function test_a_slot_read_returns_history_known_at_p0_and_is_recorded(): void
    {
        $reader = new DependencyRecordingReader(self::atlas(5));
        $slot = SlotKey::containment(ReferenceUnitId::fromString(self::E), 'admin');

        $history = $reader->history($slot, self::iv('2016-01-01'));

        $this->assertSame(self::A, $history->resolve(ValidDate::fromString('2016-07-01'))?->value(), 'position 9 is after p₀: invisible');
        $deps = $reader->dependencies();
        $this->assertSame(5, $deps->p0);
        $this->assertSame([(new SlotDependency($slot, self::iv('2016-01-01')))->describe()], $deps->describe());
    }

    public function test_a_family_read_returns_matching_slots_and_records_a_family_dependency(): void
    {
        $reader = new DependencyRecordingReader(self::atlas(5));

        $children = $reader->family(ClaimKind::Containment, ['scheme' => 'admin'], self::A, self::iv('2016-01-01', '2017-01-01'));

        $this->assertEqualsCanonicalizing([self::C, self::E], array_map(static fn (SlotKey $k): string => (string) $k->component('unit'), $children));
        $this->assertSame([FamilyDependency::of(ClaimKind::Containment, ['scheme' => 'admin'], self::A, self::iv('2016-01-01', '2017-01-01'))->describe()], $reader->dependencies()->describe());
    }

    public function test_a_family_read_respects_the_interval(): void
    {
        $reader = new DependencyRecordingReader(self::atlas(5));

        $children = $reader->family(ClaimKind::Containment, ['scheme' => 'admin'], self::A, self::iv('2015-01-01', '2016-01-01'));

        $this->assertSame([self::C], array_map(static fn (SlotKey $k): string => (string) $k->component('unit'), $children), 'E joins A only from 2016-06-01');
    }

    public function test_the_same_read_is_recorded_once_and_nothing_is_recorded_without_a_read(): void
    {
        $reader = new DependencyRecordingReader(self::atlas(5));
        $this->assertSame([], $reader->dependencies()->describe());

        $slot = SlotKey::existence(ReferenceUnitId::fromString(self::A));
        $reader->history($slot, self::iv('2016-01-01'));
        $reader->history($slot, self::iv('2016-01-01'));

        $this->assertCount(1, $reader->dependencies()->describe());
    }
}
