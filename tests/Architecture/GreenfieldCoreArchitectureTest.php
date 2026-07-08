<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Greenfield Core architecture fitness tests (Round 50-09 / ADR-T log).
 *
 * Executable enforcement of the tactical architecture for the Contestation +
 * Adjudication contexts. Mirrors the file-scanning style of the existing
 * Tests\Architecture suite; requires no external tool (Deptrac/PHPStan configs
 * are staged separately in deptrac.yaml / phpstan-greenfield.neon).
 *
 * Test IDs map to Round 50-07 v1.2 §Fitness tests.
 */
final class GreenfieldCoreArchitectureTest extends TestCase
{
    private const CONTEXTS = [
        'Contestation' => 'app/Contexts/Contestation',
        'Adjudication' => 'app/Contexts/Adjudication',
        // 'Election' joins this scan when its Infrastructure layer lands (PB-004 next
        // slice). It is a partial context today (Domain + Application only); the
        // hexagonal-completeness check below asserts all three layers, so scanning it
        // now would be a false RED for a layer not yet authorized to be built.
    ];

    // Q7 (ADR-T11): these contexts must never carry voter<->vote linkage.
    // Identities are modelled as opaque refs (RaiserStandingRef), references to
    // evidence/vote use hashes (envelopeHash/voteHash) — never raw voter columns.
    private const FORBIDDEN_LINKAGE_TOKENS = [
        'user_id', 'voter_id', 'voterId', 'voting_code', 'votingCode',
    ];

    // AD-M1 (owner-hosts-the-guard, PGP-03/ADR-MP-03): anonymity is a CONSTITUTIONAL
    // invariant owned here, so this suite hosts its guard. The Messaging Platform
    // (Shared messaging surface) merely PRESERVES anonymity and contributes its files
    // as scan inputs — it must not host the guarantee it does not own. Relocated from
    // the retired InboxMessagingArchitectureTest property #11.
    private const MESSAGING_SURFACE = [
        'app/Contexts/Shared/Application/Inbox',
        'app/Contexts/Shared/Infrastructure/Inbox',
        'app/Contexts/Shared/Infrastructure/Outbox',
    ];

    private const FORBIDDEN_FRAMEWORK_IMPORTS = [
        'Illuminate\\', 'Laravel\\', 'Eloquent', 'Carbon\\',
    ];

    // ── Package structure exists (step 4) ────────────────────────────
    public function test_greenfield_core_contexts_exist_with_hexagonal_layers(): void
    {
        foreach (self::CONTEXTS as $name => $base) {
            foreach (['Domain', 'Application', 'Infrastructure'] as $layer) {
                $this->assertDirectoryExists("{$base}/{$layer}", "{$name} missing {$layer} layer");
            }
        }
    }

    // ── AT-Q7-001: no voter<->vote linkage in the Core OR the messaging surface ──
    // Owner (constitution) hosts the guard; the messaging surface is a scan input
    // (AD-M1). The Core must be anonymity-clean, and the transport must never
    // introduce/carry linkage either.
    public function test_at_q7_001_no_voter_vote_linkage(): void
    {
        $violations = [];
        $scanRoots = array_merge(array_values(self::CONTEXTS), self::MESSAGING_SURFACE);
        foreach ($scanRoots as $base) {
            foreach ($this->phpFiles($base) as $file) {
                $content = file_get_contents($file->getRealPath());
                foreach (self::FORBIDDEN_LINKAGE_TOKENS as $token) {
                    if (str_contains($content, $token)) {
                        $violations[] = sprintf('%s contains forbidden linkage token "%s"', $file->getPathname(), $token);
                    }
                }
            }
        }
        $this->assertEmpty($violations, "Q7 anonymity violation (ADR-T11):\n" . implode("\n", $violations));
    }

    // ── Domain purity: no framework in Domain layer ──────────────────
    public function test_greenfield_domain_is_framework_free(): void
    {
        $violations = [];
        foreach (self::CONTEXTS as $base) {
            foreach ($this->phpFiles("{$base}/Domain") as $file) {
                $content = file_get_contents($file->getRealPath());
                foreach (self::FORBIDDEN_FRAMEWORK_IMPORTS as $forbidden) {
                    if (preg_match('~use\s+' . preg_quote($forbidden, '~') . '~', $content)) {
                        $violations[] = sprintf('%s imports framework %s', $file->getPathname(), $forbidden);
                    }
                }
            }
        }
        $this->assertEmpty($violations, "Framework import in Core domain:\n" . implode("\n", $violations));
    }

    // ── Domain must not import its own Infrastructure ────────────────
    public function test_greenfield_domain_has_no_infrastructure_imports(): void
    {
        $violations = [];
        foreach (self::CONTEXTS as $name => $base) {
            foreach ($this->phpFiles("{$base}/Domain") as $file) {
                $content = file_get_contents($file->getRealPath());
                if (preg_match('~use\s+App\\\\Contexts\\\\' . $name . '\\\\Infrastructure\\\\~', $content)) {
                    $violations[] = $file->getPathname();
                }
            }
        }
        $this->assertEmpty($violations, "Domain imports Infrastructure:\n" . implode("\n", $violations));
    }

    // ── AT-EVT-001: event ownership (single producer by context) ─────
    public function test_at_evt_001_event_ownership(): void
    {
        $ownership = [
            'Contestation' => ['Challenge'],
            'Adjudication' => ['Determination'],
        ];
        $violations = [];
        foreach (self::CONTEXTS as $name => $base) {
            $eventsDir = "{$base}/Domain/Events";
            foreach ($this->phpFiles($eventsDir) as $file) {
                $event = $file->getBasename('.php');
                $ownsPrefix = false;
                foreach ($ownership[$name] as $prefix) {
                    if (str_starts_with($event, $prefix)) {
                        $ownsPrefix = true;
                        break;
                    }
                }
                if (!$ownsPrefix) {
                    $violations[] = sprintf('%s event "%s" not owned by %s', $name, $event, $name);
                }
            }
        }
        $this->assertEmpty($violations, "Event ownership violation:\n" . implode("\n", $violations));
    }

    // ── Domain events are readonly historical facts (when present) ───
    public function test_greenfield_events_are_readonly(): void
    {
        $violations = [];
        foreach (self::CONTEXTS as $base) {
            foreach ($this->phpFiles("{$base}/Domain/Events") as $file) {
                $content = file_get_contents($file->getRealPath());
                if (!str_contains($content, 'readonly class')) {
                    $violations[] = $file->getBasename() . ' must be a readonly class';
                }
            }
        }
        $this->assertEmpty($violations, "Events not readonly:\n" . implode("\n", $violations));
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
            if ($file->getExtension() === 'php') {
                yield $file;
            }
        }
    }
}
