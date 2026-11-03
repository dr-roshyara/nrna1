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

    /**
     * COR-4 step 2 (PBDIGIT-329, V-13; COR-2 review F-7): a fact can carry a valid time without being an
     * EffectiveDatedFact (Correct's value, and its consequences). Property, not names: every kernel Fact whose
     * constructor takes a TimePoint must be handled by name in StorableValidTime, unless its only TimePoint is the
     * effectiveAt() that the generic EffectiveDatedFact branch already checks (End).
     */
    public function test_every_fact_carrying_a_time_point_is_handled_by_storable_valid_time(): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2) . '/app/Contexts/Membership/Application/Organisational/Ports/StorableValidTime.php');
        $unhandled = [];
        foreach (glob(dirname(__DIR__, 2) . '/' . self::KERNEL . '/*.php') ?: [] as $file) {
            $short = basename($file, '.php');
            $class = self::NAMESPACE . $short;
            if (!class_exists($class) || !(new \ReflectionClass($class))->implementsInterface(\App\Contexts\Membership\Domain\Organisational\Fact::class)) {
                continue;
            }
            $reflection = new \ReflectionClass($class);
            $constructor = $reflection->getConstructor();
            $timePoints = $constructor === null ? 0 : count(array_filter(
                $constructor->getParameters(),
                static fn (\ReflectionParameter $p): bool => (string) $p->getType() === self::NAMESPACE . 'TimePoint' || (string) $p->getType() === '?' . self::NAMESPACE . 'TimePoint',
            ));
            $coveredGenerically = $timePoints === 1 && $reflection->implementsInterface(EffectiveDatedFact::class);
            if ($timePoints > 0 && !$coveredGenerically && preg_match('/\binstanceof\s+' . $short . '\b/', $source) !== 1) {
                $unhandled[] = $short;
            }
        }

        $this->assertSame([], $unhandled, 'these facts carry a valid time that StorableValidTime::admits() does not check (R-1a-6)');
    }
}
