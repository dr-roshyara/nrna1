<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Messaging Architecture Verification (PB-003-C6A).
 *
 * Certifies that the Inbox subsystem is a reusable *platform messaging capability*,
 * not a ticket-specific implementation. Every assertion verifies an architectural
 * PROPERTY, never a concrete class name — renaming a component must NOT break these
 * tests; violating an invariant MUST. Pure-PHP file scanning (no external tool, no
 * shell — Windows-safe), mirroring GreenfieldCoreArchitectureTest.
 *
 * Property map (see PB-003 Verification Matrix):
 *   1 Port purity            2 Port has no infra dep     3 Messaging ownership
 *   4 Single writer          5 Single execution owner    6 Single recovery owner
 *   7 Transaction ownership  8 Clock ownership (decisions) 9 Clock allow-list (audit only)
 *  10 Message immutability
 */
final class InboxMessagingArchitectureTest extends TestCase
{
    private const PORT = 'app/Contexts/Shared/Application/Inbox';
    private const INFRA = 'app/Contexts/Shared/Infrastructure/Inbox';

    /**
     * Ownership cardinality is an APP-WIDE property: "exactly one execution owner"
     * means one in the whole codebase, not one per directory. A second owner
     * introduced in any context must be detected (ARR finding F-2).
     */
    private const SCAN = 'app';

    private const FRAMEWORK_IMPORTS = ['Illuminate\\', 'Laravel\\', 'Eloquent', 'Carbon\\'];

    // NOTE (AD-M1): anonymity is a CONSTITUTIONAL invariant that Messaging PRESERVES
    // but does not OWN. Its executable guard is hosted by the owner's suite —
    // GreenfieldCoreArchitectureTest::test_at_q7_001_no_voter_vote_linkage now scans
    // the Shared messaging surface. Per owner-hosts-the-guard (PGP-03/ADR-MP-03), this
    // suite no longer hosts that guard.

    /** Handler-outcome classification markers (Blueprint §8). */
    private const CLASSIFICATION_MARKERS = [
        'CausalPreconditionMissing', 'IdempotentReplay', 'PermanentInboxFailure',
    ];

    /**
     * Ambient (non-injected) time — forbidden on any decision path.
     * Matches the Laravel global helpers `now()`/`time()` and `Carbon::`/`Date::`
     * statics, but NOT `$this->clock->now()` (injected time is preceded by `>`).
     */
    private const AMBIENT_TIME = '~(?<![>\w:$])\bnow\s*\(\)|Carbon::|CarbonImmutable::|(?<![>\w:$])\bDate::|(?<![>\w:$])\btime\s*\(\)~';

    // ── 1. Port purity: the Application port is pure PHP ─────────────
    public function test_port_layer_is_framework_free(): void
    {
        $violations = [];
        foreach ($this->phpFiles(self::PORT) as $file) {
            $content = file_get_contents($file->getRealPath());
            foreach (self::FRAMEWORK_IMPORTS as $forbidden) {
                if (preg_match('~use\s+' . preg_quote($forbidden, '~') . '~', $content)) {
                    $violations[] = sprintf('%s imports framework %s', $file->getBasename(), $forbidden);
                }
            }
        }
        $this->assertEmpty($violations, "Inbox port is not framework-free:\n" . implode("\n", $violations));
    }

    // ── 2. Dependency direction: port must not depend on Infrastructure ──
    public function test_port_layer_has_no_infrastructure_dependency(): void
    {
        $violations = [];
        foreach ($this->phpFiles(self::PORT) as $file) {
            $content = file_get_contents($file->getRealPath());
            if (preg_match('~use\s+App\\\\Contexts\\\\Shared\\\\Infrastructure\\\\~', $content)) {
                $violations[] = $file->getBasename();
            }
        }
        $this->assertEmpty($violations, "Inbox port depends on Infrastructure (dependency direction reversed):\n" . implode("\n", $violations));
    }

    // ── 3. Messaging ownership: Shared messaging imports NO bounded context ──
    // Shared Infrastructure only transports/schedules/retries/dispatches/persists.
    // It must never import another bounded context's code (business policy stays
    // inside the owning context; the event is the only seam).
    public function test_messaging_layer_imports_no_bounded_context(): void
    {
        $violations = [];
        foreach ([self::PORT, self::INFRA] as $dir) {
            foreach ($this->phpFiles($dir) as $file) {
                $content = file_get_contents($file->getRealPath());
                // Any reference to App\Contexts\<X>\ where <X> is NOT Shared —
                // via `use` OR an inline fully-qualified `\App\Contexts\<X>\` name
                // (ARR finding F-3: a `use`-only scan is evadable by inline FQN).
                if (preg_match_all('~App\\\\Contexts\\\\(\w+)\\\\~', $content, $m)) {
                    foreach ($m[1] as $ctx) {
                        if ($ctx !== 'Shared') {
                            $violations[] = sprintf('%s references bounded context "%s"', $file->getBasename(), $ctx);
                        }
                    }
                }
            }
        }
        $this->assertEmpty($violations, "Messaging ownership violated — Shared references business code:\n" . implode("\n", $violations));
    }

    // (Anonymity guard relocated to the constitutional suite — see AD-M1 note above.)

    // ── 4. Single writer: only the messaging package owns the persistence model ──
    public function test_inbox_persistence_model_has_a_single_owning_package(): void
    {
        $owner = $this->realpath(self::INFRA);
        $violations = [];
        foreach ($this->phpFiles('app') as $file) {
            $real = $file->getRealPath();
            if (str_starts_with($real, $owner)) {
                continue; // the owning package may reference its own model
            }
            $content = file_get_contents($real);
            // Reference to the InboxEvent Eloquent model outside the package.
            if (preg_match('~\bInboxEvent::|new\s+InboxEvent\b|\\\\InboxEvent\b~', $content)) {
                $violations[] = $file->getPathname();
            }
        }
        $this->assertEmpty($violations, "inbox_events has a writer/reader outside the messaging package:\n" . implode("\n", $violations));
    }

    // ── 5. Execution ownership: EXACTLY ONE component classifies outcomes ──
    // Property, not class name: count files in the messaging package that catch a
    // classification marker. Must be exactly one — no duplicated classify logic.
    public function test_exactly_one_component_classifies_handler_outcomes(): void
    {
        $classifiers = $this->filesMatching(self::SCAN, $this->markerCatchRegex());
        $this->assertCount(
            1,
            $classifiers,
            "Handler-outcome classification must live in exactly ONE component (app-wide); found: "
                . implode(', ', array_map('basename', $classifiers))
        );
    }

    // ── 6. Recovery ownership: EXACTLY ONE component owns retry scheduling ──
    public function test_exactly_one_component_owns_retry_scheduling(): void
    {
        $schedulers = $this->schedulingOwners();
        $this->assertCount(
            1,
            $schedulers,
            "Retry scheduling must be owned by exactly ONE component (app-wide); found: "
                . implode(', ', array_map('basename', $schedulers))
        );
    }

    // ── 7. Transaction ownership: the execution component opens no transaction ──
    // It runs inside the caller's txn (ADR-T1: one boundary per consumed row, owned
    // by the orchestrator that claims/selects the row).
    public function test_execution_component_opens_no_transaction(): void
    {
        $classifiers = $this->filesMatching(self::SCAN, $this->markerCatchRegex());
        $this->assertCount(1, $classifiers, 'precondition: one execution component (see property 5)');

        $content = file_get_contents($classifiers[0]);
        // Match the transaction-OPENING call (requires `(`), not prose that merely
        // references the caller's transaction in a comment.
        $this->assertDoesNotMatchRegularExpression(
            '~DB::transaction\s*\(|->transaction\s*\(|beginTransaction\s*\(~',
            $content,
            'The execution component must not own a transaction boundary (' . basename($classifiers[0]) . ').'
        );
    }

    // ── 8. Clock ownership: decision paths use injected time only ──
    // The execution component and the recovery component are the decision paths;
    // neither may read ambient time.
    public function test_decision_paths_use_injected_time_only(): void
    {
        $decisionFiles = array_unique(array_merge(
            $this->filesMatching(self::SCAN, $this->markerCatchRegex()),
            $this->schedulingOwners()
        ));

        $violations = [];
        foreach ($decisionFiles as $f) {
            if (preg_match(self::AMBIENT_TIME, file_get_contents($f))) {
                $violations[] = basename($f);
            }
        }
        $this->assertEmpty($violations, "Decision path reads ambient time (must inject ClockInterface):\n" . implode("\n", $violations));
    }

    // ── 9. Clock allow-list: ambient time in messaging is ONLY audit stamps ──
    // Any surviving ambient-time call must be on a line that stamps audit metadata
    // (processed_at). Decisions get injected time; metadata may use framework time.
    public function test_ambient_time_in_messaging_is_only_audit_metadata(): void
    {
        $violations = [];
        foreach ($this->phpFiles(self::INFRA) as $file) {
            foreach (explode("\n", file_get_contents($file->getRealPath())) as $n => $line) {
                if (preg_match(self::AMBIENT_TIME, $line) && !str_contains($line, 'processed_at')) {
                    $violations[] = sprintf('%s:%d %s', $file->getBasename(), $n + 1, trim($line));
                }
            }
        }
        $this->assertEmpty($violations, "Ambient time outside audit metadata:\n" . implode("\n", $violations));
    }

    // ── 10. Message immutability: port data carriers are immutable ──
    public function test_port_data_carriers_are_immutable(): void
    {
        $violations = [];
        foreach ($this->phpFiles(self::PORT) as $file) {
            $content = file_get_contents($file->getRealPath());
            // A concrete data class: `class X`, not an interface/enum/exception/abstract.
            $isConcreteData = preg_match('~^(?!abstract).*\bclass\s+\w+~m', $content)
                && !preg_match('~\binterface\s+\w+|\benum\s+\w+|\bextends\b|Exception~', $content);
            if ($isConcreteData && !preg_match('~\breadonly\s+class\b~', $content)) {
                $violations[] = $file->getBasename() . ' must be a readonly class (immutable message carrier)';
            }
        }
        $this->assertEmpty($violations, "Mutable data carrier in the inbox port:\n" . implode("\n", $violations));
    }

    // ── helpers ──────────────────────────────────────────────────────
    private function markerCatchRegex(): string
    {
        return '~catch\s*\(\s*\\\\?(' . implode('|', self::CLASSIFICATION_MARKERS) . ')~';
    }

    /**
     * App-wide owners of retry scheduling: files that USE the `parkedDue` scope,
     * excluding the model that DECLARES it (declaring a scope is not owning the
     * scheduling decision).
     *
     * @return list<string>
     */
    private function schedulingOwners(): array
    {
        return array_values(array_filter(
            $this->filesMatching(self::SCAN, '~parkedDue\s*\(~'),
            fn (string $f) => !str_contains(file_get_contents($f), 'function scopeParkedDue')
        ));
    }

    /** @return list<string> realpaths of files whose content matches $regex */
    private function filesMatching(string $dir, string $regex): array
    {
        $hits = [];
        foreach ($this->phpFiles($dir) as $file) {
            if (preg_match($regex, file_get_contents($file->getRealPath()))) {
                $hits[] = $file->getRealPath();
            }
        }
        return $hits;
    }

    private function realpath(string $path): string
    {
        return realpath($path) ?: $path;
    }

    /** @return iterable<SplFileInfo> */
    private function phpFiles(string $path): iterable
    {
        if (!is_dir($path)) {
            return [];
        }
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($it as $file) {
            if ($file instanceof SplFileInfo && $file->getExtension() === 'php') {
                yield $file;
            }
        }
    }
}
