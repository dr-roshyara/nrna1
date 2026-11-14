<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Domain;

use App\Contexts\Platform\Domain\OperationalReadiness;
use PHPUnit\Framework\TestCase;

/** PBDIGIT-345 task 5c (L-1, D-193 F-07; T-13): readiness names EACH missing precondition, so a refusal can say which. */
final class OperationalReadinessTest extends TestCase
{
    public function test_each_missing_precondition_is_named_and_a_complete_readiness_names_none(): void
    {
        $this->assertSame([], (new OperationalReadiness(true, true, true, 1))->missing());
        $this->assertSame(['ledger schema'], (new OperationalReadiness(false, true, true, 1))->missing());
        $this->assertSame(['organisation identity'], (new OperationalReadiness(true, false, true, 1))->missing());
        $this->assertSame(['ledger clock row'], (new OperationalReadiness(true, true, false, 1))->missing());
        $this->assertSame(['a platform contact with an active account'], (new OperationalReadiness(true, true, true, 0))->missing());
        $this->assertCount(4, (new OperationalReadiness(false, false, false, 0))->missing());
        foreach ([[false, true, true, 1], [true, false, true, 1], [true, true, false, 1], [true, true, true, 0]] as $case) {
            $this->assertFalse((new OperationalReadiness(...$case))->isComplete());
        }
    }
}
