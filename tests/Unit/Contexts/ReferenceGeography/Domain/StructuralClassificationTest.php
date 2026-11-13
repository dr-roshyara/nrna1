<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\StructuralClassification;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-2c, cycle 1 (v3.11 §14; D-91, D-92, D-87): structural or not is derived from the claims' KINDS,
 * never from a label, a revision type or the preparer. A revision is structural if ANY claim is of a structural kind.
 */
final class StructuralClassificationTest extends TestCase
{
    public function test_every_kind_is_classified_exactly_as_section_14(): void
    {
        $structural = ['EXISTENCE', 'CONTAINMENT', 'CLASSIFICATION', 'SUCCESSION', 'BOUNDARY', 'PROFILE_SCHEME', 'PROFILE_TIER', 'PROFILE_CLASS', 'ISSUER', 'MANDATE', 'COMPETENCE', 'PRECEDENCE', 'RECONCILIATION', 'ADMISSION'];
        $nonStructural = ['IDENTIFIER', 'SOURCE_NAME', 'CLDR_NAME', 'CLDR_INHERITANCE', 'GROUPING', 'DISPUTE', 'PLATFORM_DEFAULT_LOCALE'];

        foreach (ClaimKind::cases() as $kind) {
            $this->assertSame(in_array($kind->value, $structural, true), StructuralClassification::isStructuralKind($kind), $kind->value);
        }
        $this->assertCount(21, [...$structural, ...$nonStructural], 'every kind classified once');
    }

    public function test_a_revision_is_structural_if_any_claim_is_and_a_withdrawal_of_a_structural_slot_counts(): void
    {
        $u = ReferenceUnitId::fromString('3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f');
        $claim = static fn (string $id, SlotKey $slot, ?string $v): Claim => new Claim(ClaimId::fromString($id), $slot, $v === null ? ClaimValue::none() : ClaimValue::of($v), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Other, 'x'));
        $name = $claim('00000000-0000-4000-8000-000000000001', SlotKey::sourceName($u, 's', 'ne', 'official'), 'Koshi');
        $withdrawParent = $claim('00000000-0000-4000-8000-000000000002', SlotKey::containment($u, 'admin'), null);

        $this->assertFalse(StructuralClassification::ofClaims([$name]));
        $this->assertTrue(StructuralClassification::ofClaims([$name, $withdrawParent]), 'a "correction" of a structural slot is structural');
        $this->assertFalse(StructuralClassification::ofClaims([]));
    }
}
