<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCore;

use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyReason;
use App\Contexts\Election\Domain\OperatingCore\Condition\ElectionLevelCancellation;
use App\Contexts\Election\Domain\OperatingCore\Condition\TerminalStatePlaceholder;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use PHPUnit\Framework\TestCase;

/**
 * Closed value-object sets and the governed terminal vocabulary
 * (EM-ARCH-001 §2c VOs; EM-GOV-064, 066, 069; D-9; ADR-T11).
 * RED tests 15–17 of the readiness report §E.
 */
final class OperatingCoreValueObjectsTest extends TestCase
{
    /** Test 15 — D-9: AcceptancePosition admits exactly accept and object; no abstention is constructible (G-1). */
    public function test_acceptance_position_is_closed_at_accept_and_object(): void
    {
        $cases = AcceptancePosition::cases();

        $this->assertCount(2, $cases, 'Adopted text defines no Committee abstention; the model must not invent one.');
        $this->assertSame(['Accept', 'Object'], array_map(static fn ($c) => $c->name, $cases));
    }

    /** Test 16 — VacancyGround admits exactly the three grounds of EM-GOV-064. */
    public function test_vacancy_ground_is_closed_at_the_three_adopted_grounds(): void
    {
        $cases = VacancyGround::cases();

        $this->assertCount(3, $cases);
        $this->assertSame(
            ['ResignationWithReason', 'DeathOrPermanentIncapacity', 'LossOfEligibilityOrIndependence'],
            array_map(static fn ($c) => $c->name, $cases),
        );
    }

    /**
     * Test 17 — D-2 discharged / D-9: the terminal state renders as ELECTION DISCONTINUED
     * (EM-GOV-069) and as nothing else; it is a distinct type from election-level
     * cancellation (EM-GOV-058), which is itself distinct from any opportunity-level value.
     */
    public function test_terminal_state_renders_as_election_discontinued_and_stays_a_distinct_type(): void
    {
        $terminal = TerminalStatePlaceholder::electionDiscontinued();

        $this->assertSame('Election Discontinued', $terminal->businessRendering());

        // Discontinued ≠ Cancelled — distinct recorded causes, never merged (EM-GOV-069).
        $cancellation = ElectionLevelCancellation::onRestorationExpiry();
        $this->assertSame('Election Cancelled', $cancellation->businessRendering());
        $this->assertNotInstanceOf(ElectionLevelCancellation::class, $terminal);
        $this->assertNotInstanceOf(TerminalStatePlaceholder::class, $cancellation);

        // The forbidden substitutes never appear as the terminal rendering.
        foreach (['Terminated', 'Suspended', 'Abandoned', 'Expired', 'Cancelled'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $terminal->businessRendering());
        }
    }

    /** ADR-T11 — reason fields are constrained surfaces, checked at the boundary (design §5e). */
    public function test_vacancy_reason_is_a_constrained_surface(): void
    {
        $reason = VacancyReason::fromString('stated reason');
        $this->assertSame('stated reason', $reason->toString());

        try {
            VacancyReason::fromString('   ');
            $this->fail('A blank vacancy reason must be refused.');
        } catch (\InvalidArgumentException) {
            // expected
        }

        $this->expectException(\InvalidArgumentException::class);
        VacancyReason::fromString(str_repeat('x', 501));
    }
}
