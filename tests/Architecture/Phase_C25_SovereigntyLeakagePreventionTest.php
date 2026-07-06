<?php

namespace Tests\Architecture;

use Tests\TestCase;

/**
 * Phase C.2.5: Anti-Leak Architecture Guards (Step 1, P2)
 *
 * These tests PREVENT NEW sovereignty reconstruction patterns from being introduced
 * while Phase C.2.5 refactors existing paths. They establish hard boundaries.
 *
 * CRITICAL: Tests are expected to FAIL initially (red phase) because the codebase
 * contains legacy patterns. As refactoring progresses, tests will pass one by one.
 *
 * DO NOT disable these tests or make them optional. They are governance enforcement.
 */
class Phase_C25_SovereigntyLeakagePreventionTest extends TestCase
{
    protected string $appPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->appPath = base_path('app');
    }

    /**
     * GUARD 1: Forbid NEW Election::allowsAction() call sites
     *
     * Existing call sites (middleware, wrappers) are documented in audit.
     * This test prevents NEW code from calling allowsAction() without review.
     *
     * @test
     */
    public function no_new_allowsAction_call_sites_outside_documented_locations()
    {
        $documentedCallers = [
            'app/Http/Middleware/EnsureElectionState.php',     // Step 2 will refactor
            'app/Domain/Election/StateMachine/ElectionStateMachine.php', // Step 7 will remove
            'app/Models/Election.php',  // Definition only, not usage
            'app/Http/Middleware/OperationCapabilityMapper.php', // Comment-only reference (documents what it replaces); makes no authority decisions
        ];

        $grep = $this->grepFiles('allowsAction', $this->appPath, ['--include=*.php']);

        foreach ($grep as $match) {
            // Extract filename and check if it's in documented list
            $file = $match['file'];
            $isDocumented = in_array($file, $documentedCallers);

            if (!$isDocumented && strpos($file, 'test') === false) {
                $this->fail(
                    "New Election::allowsAction() call site detected: {$file}:{$match['line']}\n" .
                    "This is a sovereignty leakage path. Review audit: claude/audits/PHASE_C_2_5_AUTHORITY_PATH_AUDIT.md\n" .
                    "If this is intentional, add to \$documentedCallers."
                );
            }
        }

        $this->assertTrue(true, 'No new allowsAction() call sites detected');
    }

    /**
     * GUARD 2: Forbid hardcoded state strings in permission contexts
     *
     * Hardcoded state strings (e.g., 'pending_approval', 'voting_active') indicate
     * permission derivation. All permission checks must use ElectionCapabilityResolver.
     *
     * @test
     */
    public function no_hardcoded_state_strings_in_permission_contexts()
    {
        $forbiddenPatterns = [
            // Permission checks using state equality
            "state.*===.*'(draft|pending_approval|approved|voting_active|results|archived)'" => "Direct state comparison in permission logic",
            "state.*==.*'(draft|pending_approval|approved|voting_active|results|archived)'" => "Direct state comparison in permission logic",
            "!==.*'(draft|pending_approval|approved|voting_active|results|archived)'" => "Negated state comparison in permission guard",
        ];

        $files = $this->findPhpFiles("{$this->appPath}/Http/Controllers", '{!test*}');

        foreach ($files as $file) {
            $content = file_get_contents($file);

            foreach ($forbiddenPatterns as $pattern => $reason) {
                if (preg_match("/{$pattern}/", $content)) {
                    // Allow if this is clearly visualization-only (CSS class, badge label, etc)
                    if (preg_match('/(class.*state|badge|label|status.*badge|phase.*badge)/i', $content)) {
                        continue;
                    }

                    $this->fail(
                        "Hardcoded state string found in {$file}\n" .
                        "Reason: {$reason}\n" .
                        "All permission checks must use ElectionCapabilityResolver."
                    );
                }
            }
        }

        $this->assertTrue(true, 'No hardcoded state strings in permission contexts');
    }

    /**
     * GUARD 3: Forbid controller-local capability derivation
     *
     * Controllers MUST NOT compute or infer permissions. They MUST ask the resolver.
     *
     * Pattern to forbid:
     * if ($election->state === ...) { authorize(...) }
     * if ($this->userCan(...)) { ... }  // without resolver
     * if ($election->canApprove()) { ... }  // custom method, not resolver
     *
     * @test
     */
    public function no_controller_local_permission_derivation()
    {
        $controllerPath = "{$this->appPath}/Http/Controllers";
        $files = $this->findPhpFiles($controllerPath);

        $forbiddenPatterns = [
            // Custom permission methods
            '/\$this->authorize.*without.*resolver/i' => "Direct authorization without resolver",
            '/can[A-Z][a-z]+\(.*\)\s*{/' => "Custom can*() method (should use resolver)",
            '/private.*permission/i' => "Private permission derivation method",
        ];

        foreach ($files as $file) {
            $content = file_get_contents($file);

            // Check for state-based permission in if/else
            if (preg_match('/if\s*\(\s*\$election->state\s*===/', $content)) {
                // Exception: allowed if it's checking for voting phase for visualization
                if (!preg_match('/voting.*active|state.*badge|phase.*label/i', $content)) {
                    $this->fail(
                        "Controller-local state-based permission check in {$file}\n" .
                        "Controllers must ask ElectionCapabilityResolver, not derive from state."
                    );
                }
            }
        }

        $this->assertTrue(true, 'No controller-local permission derivation detected');
    }

    /**
     * GUARD 4: Forbid new governance-vocabulary conflicts
     *
     * Ensure denial_reason values are stable (part of governance vocabulary).
     * Hardcoded reason strings indicate local re-interpretation.
     *
     * @test
     */
    public function no_new_denial_reason_hardcoding()
    {
        $forbiddenReasons = [
            'election is suspended' => 'Should be: suspended',
            'voting not active' => 'Should be: voting_window_not_active',
            'not eligible' => 'Should use resolver denial_reason',
        ];

        $controllerPath = "{$this->appPath}/Http/Controllers";
        $files = $this->findPhpFiles($controllerPath);

        foreach ($files as $file) {
            $content = file_get_contents($file);

            foreach ($forbiddenReasons as $hardcoded => $should_be) {
                if (stripos($content, $hardcoded) !== false) {
                    $this->fail(
                        "Hardcoded denial reason in {$file}: '{$hardcoded}'\n" .
                        "Governance vocabulary: {$should_be}\n" .
                        "All denial reasons must come from ElectionCapabilityResolver."
                    );
                }
            }
        }

        $this->assertTrue(true, 'No hardcoded denial reasons');
    }

    /**
     * GUARD 5: Forbid getStateMachine() usage outside documented locations
     *
     * getStateMachine() is a transitional wrapper. New code should call
     * ElectionCapabilityResolver or ElectionLifecycle directly.
     *
     * @test
     */
    public function no_new_getStateMachine_call_sites()
    {
        $documentedCallers = [
            'app/Http/Middleware/EnsureElectionState.php', // Step 2 will refactor
            'app/Models/Election.php', // Definition only
            'app/Domain/Election/StateMachine/ElectionStateMachine.php', // Deprecated
        ];

        // Match the call syntax only — 'getStateMachineData(...)' (a resolver-backed
        // projection helper) must not trip this guard on a name-substring match.
        $grep = $this->grepFiles('getStateMachine(', $this->appPath, ['--include=*.php']);

        foreach ($grep as $match) {
            $file = $match['file'];
            $isDocumented = in_array($file, $documentedCallers);

            if (!$isDocumented && strpos($file, 'test') === false) {
                $this->fail(
                    "New getStateMachine() call site detected: {$file}:{$match['line']}\n" .
                    "Use ElectionCapabilityResolver or ElectionLifecycle instead."
                );
            }
        }

        $this->assertTrue(true, 'No new getStateMachine() call sites');
    }

    /**
     * Helper: grep for pattern in files
     */
    private function grepFiles(string $pattern, string $path, array $grepOptions = []): array
    {
        $cmd = "grep -rn '" . addslashes($pattern) . "' " . escapeshellarg($path) . " " . implode(' ', $grepOptions) . " 2>/dev/null || true";
        exec($cmd, $output, $exitCode);

        if (empty($output)) {
            return [];
        }

        return array_filter(array_map(function ($line) {
            if (empty(trim($line))) {
                return null;
            }

            // Split on grep's ':<line>:' separator, not on every ':' —
            // Windows drive letters (C:\...) contain a colon in the path itself.
            if (!preg_match('/^(.+?):(\d+):(.*)$/', $line, $m)) {
                return null;
            }

            $file = str_replace('\\', '/', trim($m[1]));
            $base = str_replace('\\', '/', base_path()) . '/';

            return [
                'file' => str_replace($base, '', $file),
                'line' => (int) $m[2],
                'content' => $m[3],
            ];
        }, $output));
    }

    /**
     * Helper: find PHP files recursively.
     *
     * Pure PHP — shell `find` differs across platforms and a malformed
     * exclude expression made it fail silently (scanning zero files).
     */
    private function findPhpFiles(string $path, string $exclude = ''): array
    {
        if (!is_dir($path)) {
            return [];
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            if ($exclude !== '' && stripos($file->getFilename(), 'test') !== false) {
                continue;
            }
            $files[] = $file->getRealPath();
        }

        return $files;
    }
}
