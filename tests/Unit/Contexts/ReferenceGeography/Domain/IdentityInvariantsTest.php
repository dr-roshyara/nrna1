<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\IdentifierAssignment;
use App\Contexts\ReferenceGeography\Domain\IdentifierInvariants;
use App\Contexts\ReferenceGeography\Domain\IdentityLedger;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-1, cycle 4 (v3.11 §2 "Enforced rules"):
 * - I-1: within a namespace, at any valid instant, a value identifies at most one live subject;
 * - I-2: a subject holds at most one value per namespace at any valid instant (structural: the IDENTIFIER slot is
 *   (subject, namespace));
 * - I-3: an identity is never deleted or reused;
 * - code reuse (ISO "CS": Czechoslovakia to 1993, Serbia and Montenegro about 2003 to 2006) is two subjects with
 *   disjoint intervals, so I-1 holds.
 * IdentifierAssignment's interval is when the subject is live AND holds the value (computed by the validator, G-2).
 */
final class IdentityInvariantsTest extends TestCase
{
    private const CZECHOSLOVAKIA = '1a1a1a1a-1a1a-4a1a-8a1a-1a1a1a1a1a1a';
    private const SERBIA_MONTENEGRO = '2b2b2b2b-2b2b-4b2b-8b2b-2b2b2b2b2b2b';
    private const OTHER = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';

    private static function a(string $subject, string $value, string $from, ?string $to, string $namespace = 'iso3166-1-alpha2'): IdentifierAssignment
    {
        return new IdentifierAssignment(ReferenceUnitId::fromString($subject), $namespace, $value, new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)));
    }

    public function test_code_reuse_with_disjoint_intervals_satisfies_i1(): void
    {
        $this->assertSame([], IdentifierInvariants::i1Violations([
            self::a(self::CZECHOSLOVAKIA, 'CS', '1974-01-01', '1993-01-01'),
            self::a(self::SERBIA_MONTENEGRO, 'CS', '2003-07-23', '2006-09-26'),
        ]));
    }

    public function test_one_value_on_two_live_subjects_at_once_violates_i1(): void
    {
        $violations = IdentifierInvariants::i1Violations([
            self::a(self::CZECHOSLOVAKIA, 'CS', '1974-01-01', '1993-06-01'),
            self::a(self::SERBIA_MONTENEGRO, 'CS', '1993-01-01', null),
        ]);

        $this->assertSame([['iso3166-1-alpha2', 'CS', self::CZECHOSLOVAKIA, self::SERBIA_MONTENEGRO]], $violations);
    }

    public function test_i1_is_per_namespace_and_per_value(): void
    {
        $this->assertSame([], IdentifierInvariants::i1Violations([
            self::a(self::CZECHOSLOVAKIA, 'CS', '1974-01-01', null),
            self::a(self::SERBIA_MONTENEGRO, 'CS', '1974-01-01', null, 'fips'),   // another namespace
            self::a(self::OTHER, 'CZ', '1974-01-01', null),                        // another value
        ]));
    }

    public function test_a_subject_repeating_its_own_value_is_not_a_violation(): void
    {
        $this->assertSame([], IdentifierInvariants::i1Violations([
            self::a(self::OTHER, 'NP', '1974-01-01', '2000-01-01'),
            self::a(self::OTHER, 'NP', '1999-01-01', null),
        ]));
    }

    public function test_i2_is_structural_one_slot_per_subject_and_namespace_gives_one_value_at_a_time(): void
    {
        $unit = ReferenceUnitId::fromString(self::OTHER);
        $slot = SlotKey::identifier($unit, 'iso3166-1-alpha2');
        $claim = static fn (string $id, string $v, string $from): Claim => new Claim(ClaimId::fromString($id), $slot, ClaimValue::of($v), new ValidInterval(ValidDate::fromString($from)), new Basis(BasisKind::Other, 'iso-newsletter'));
        $h = SlotHistory::for($slot)
            ->with($claim('00000000-0000-4000-8000-000000000101', 'NP', '1974-01-01'), 1)
            ->with($claim('00000000-0000-4000-8000-000000000102', 'NE', '2001-01-01'), 2);

        $this->assertSame('NP', $h->resolve(ValidDate::fromString('2000-01-01'))?->value());
        $this->assertSame('NE', $h->resolve(ValidDate::fromString('2002-01-01'))?->value(), 'exactly one value at any date');
    }

    public function test_i3_an_identity_is_minted_once_and_never_reused_even_after_retirement(): void
    {
        $id = ReferenceUnitId::fromString(self::OTHER);
        $ledger = IdentityLedger::empty()->mint($id);

        $this->assertTrue($ledger->hasMinted($id));
        foreach ([fn () => $ledger->mint($id), fn () => $ledger->retire($id)->mint($id)] as $reuse) {
            try {
                $reuse();
                $this->fail('I-3: never reused');
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
        $this->assertTrue($ledger->retire($id)->hasMinted($id), 'retirement never deletes an identity');
        $this->assertFalse(IdentityLedger::empty()->hasMinted($id), 'the ledger value is immutable');
    }

    public function test_only_a_minted_identity_can_be_retired(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        IdentityLedger::empty()->retire(ReferenceUnitId::fromString(self::OTHER));
    }
}
