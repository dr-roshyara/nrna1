<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority\ValueObjects;

use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationStatus;
use PHPUnit\Framework\TestCase;

final class DelegationStatusTest extends TestCase
{
    public function test_has_exactly_two_cases_in_correct_order(): void
    {
        $this->assertSame(
            ['ACTIVE', 'REVOKED'],
            array_map(fn($c) => $c->name, DelegationStatus::cases())
        );
    }

    public function test_is_string_backed_with_correct_values(): void
    {
        $this->assertSame('ACTIVE', DelegationStatus::ACTIVE->value);
        $this->assertSame('REVOKED', DelegationStatus::REVOKED->value);
    }

    public function test_can_be_constructed_from_string_value(): void
    {
        $this->assertSame(DelegationStatus::ACTIVE, DelegationStatus::from('ACTIVE'));
        $this->assertSame(DelegationStatus::REVOKED, DelegationStatus::from('REVOKED'));
    }

    public function test_revoked_represents_irreversible_constitutional_revocation(): void
    {
        // REVOKED is a constitutional fact: the delegation authority was
        // explicitly withdrawn. It cannot be un-revoked; a new assignment
        // must be created. This test anchors that ubiquitous language.
        $status = DelegationStatus::REVOKED;

        $this->assertSame('REVOKED', $status->value);
        $this->assertNotSame(DelegationStatus::ACTIVE, $status);
    }
}
