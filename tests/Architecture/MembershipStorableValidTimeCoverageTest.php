<?php

declare(strict_types=1);

namespace Tests\Architecture;

use App\Contexts\Membership\Domain\Organisational\EffectiveDatedFact;
use PHPUnit\Framework\TestCase;

/**
 * E10 (Option E, R-1a-6): StorableValidTime::admits() checks the valid times of Admit and End.
 * A new kernel fact that carries a valid time must be reviewed there (and in the adapter's
 * encoding) before it can be persisted; this test makes that review impossible to skip.
 */
final class MembershipStorableValidTimeCoverageTest extends TestCase
{
    private const KERNEL = 'app/Contexts/Membership/Domain/Organisational';
    private const NAMESPACE = 'App\\Contexts\\Membership\\Domain\\Organisational\\';

    public function test_the_facts_with_a_valid_time_are_exactly_those_storable_valid_time_checks(): void
    {
        $dated = [];
        foreach (glob(dirname(__DIR__, 2) . '/' . self::KERNEL . '/*.php') ?: [] as $file) {
            $class = self::NAMESPACE . basename($file, '.php');
            if (class_exists($class) && (new \ReflectionClass($class))->implementsInterface(EffectiveDatedFact::class)) {
                $dated[] = basename($file, '.php');
            }
        }
        sort($dated);

        $this->assertSame(['Admit', 'End'], $dated, 'a new fact with a valid time: extend StorableValidTime::admits() and its contract tests (R-1a-6)');
    }
}
