<?php

declare(strict_types=1);

/**
 * Test-presence collector — an ADVISORY observation producer.
 *
 * Classifies a set of changed files and emits the observation
 * "production behavior changed with/without accompanying tests".
 *
 * Scope guard: this collector OBSERVES. It emits no verdicts, blocks
 * nothing, and contains no policy — whether the observation matters is
 * decided by humans (and, someday, by configured governance rules).
 * Advisory→enforcing status is earned through evidence, never assumed.
 */
final class TestPresenceCollector
{
    /**
     * @param list<string> $changedFiles repo-relative paths, forward slashes
     * @return array{
     *   production_changed:int, tests_changed:int,
     *   production_without_tests:bool,
     *   production_files:list<string>, test_files:list<string>
     * }
     */
    public static function classify(array $changedFiles): array
    {
        $production = [];
        $tests = [];

        foreach ($changedFiles as $file) {
            $file = str_replace('\\', '/', trim($file));
            if ($file === '' || !str_ends_with($file, '.php')) {
                continue;
            }
            if (str_ends_with($file, '.blade.php')) {
                continue; // templates are not production behavior
            }
            if (str_starts_with($file, 'app/')) {
                $production[] = $file;
            } elseif (str_starts_with($file, 'tests/')) {
                $tests[] = $file;
            }
            // everything else (scripts/, config/, vendor/, docs/) is out of scope
        }

        return [
            'production_changed'       => count($production),
            'tests_changed'            => count($tests),
            'production_without_tests' => $production !== [] && $tests === [],
            'production_files'         => $production,
            'test_files'               => $tests,
        ];
    }
}
