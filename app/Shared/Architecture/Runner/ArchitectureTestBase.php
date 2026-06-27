<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Runner;

use App\Shared\Architecture\Rule\ArchitectureRule;
use App\Shared\Architecture\Rule\RuleResult;
use App\Shared\Architecture\Services\FileDiscoveryService;
use App\Shared\Architecture\Services\ImportScanner;
use PHPUnit\Framework\TestCase;

abstract class ArchitectureTestBase extends TestCase
{
    protected static RuleRunner $runner;
    protected static FileDiscoveryService $fileDiscovery;
    protected static ImportScanner $importScanner;

    public static function setUpBeforeClass(): void
    {
        self::$runner = new RuleRunner();
        self::$fileDiscovery = new FileDiscoveryService();
        self::$importScanner = new ImportScanner();
    }

    /**
     * Assert all rules in a list pass.
     *
     * @param ArchitectureRule[] $rules
     * @param array<string, mixed> $config Shared config keyed by rule name
     */
    protected static function assertRules(array $rules, array $config = []): void
    {
        $results = self::$runner->run($rules, $config);
        $failures = self::$runner->failedResults($results);

        if ($failures !== []) {
            self::fail(self::$runner->formatFailures($failures));
        }

        static::assertTrue(true);
    }

    /**
     * Assert a single rule passes with the given config.
     */
    protected static function assertRule(ArchitectureRule $rule, array $config = []): void
    {
        $result = $rule->check($config);

        if ($result->failed()) {
            self::fail(self::$runner->formatFailures([$result]));
        }

        static::assertTrue(true);
    }

    /**
     * Assert no violations in a RuleResult.
     */
    protected static function assertNoViolations(RuleResult $result): void
    {
        if ($result->failed()) {
            self::fail(self::$runner->formatFailures([$result]));
        }

        static::assertTrue(true);
    }
}
