<?php

declare(strict_types=1);

namespace Tests\Architecture\Messaging;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * PB-007 7B — executable Architecture Fitness Rule (ADR-MP-06):
 *
 *   "Exactly one producer may mint a CorrelationId per Constitutional Conversation.
 *    All subsequent producers propagate the existing CorrelationId unchanged."
 *
 * OWNERSHIP: the invariant is OWNED by the Messaging Platform architecture
 * (ADR-MP-06); PB-007 only HOSTS this executable check (owner-hosts-the-guard,
 * ADR-MP-03).
 *
 * Enforcement encoded here:
 *  - `EventProvenance::start()` may appear ONLY in chain-origin producers (the
 *    explicit allowlist below — today: Adjudication's coordinator, the loop start
 *    while the raise path is unimplemented).
 *  - Reacting producers (Election, Contestation) must use `fromConsumed()` and
 *    must never call `start()` — a random/second mint inside a reaction would
 *    violate replay safety and the one-mint-per-conversation rule.
 */
final class CorrelationIdMintingTest extends TestCase
{
    /** Chain-origin producers approved to MINT (ADR-MP-06). Extending this list is an ARB decision. */
    private const CHAIN_ORIGIN_ALLOWLIST = [
        // WP-5/WP-3B (ARB-approved 2026-07-31): the CORRECTION-LOOP origin. The
        // integration conversation begins when the routing act publishes
        // `ChallengeRouted` -- the loop's head trigger (ADR-T21).
        'app/Contexts/Contestation/Application/Service/CoordinatesContestation.php',

        // The AUTHORITY-DECISION origin. Intentionally still an originator: the
        // authority's decision does not yet arrive as a message, so
        // `issueDetermination` has nothing to derive provenance from. It becomes a
        // reacting producer when that path is wired (WP-6), at which point this
        // entry is expected to DISAPPEAR.
        //
        // The invariant is NOT the number of entries: EACH CONVERSATION HAS EXACTLY
        // ONE ORIGIN. An ORIGIN BEGINS a conversation; it never TRANSFERS ownership
        // of one -- established once, thereafter propagated via fromConsumed(), never
        // recreated downstream. What exists today is two originators serving two
        // DISTINCT conversations (correction loop; authority decision) -- a deliberate
        // intermediate state, not a list that grows. A third entry would have to
        // show a third distinct conversation, and needs an ARB decision (ADR-MP-06).
        'app/Contexts/Adjudication/Application/Service/CoordinatesAdjudication.php',
    ];

    private const SCAN_ROOT = 'app/Contexts';

    public function test_only_chain_origin_producers_mint_a_correlation(): void
    {
        $offenders = [];
        foreach ($this->phpFiles(self::SCAN_ROOT) as $file) {
            $path = str_replace('\\', '/', $file->getPathname());
            $content = (string) file_get_contents($file->getPathname());
            if (!$this->mintsCorrelation($content)) {
                continue;
            }
            $allowed = false;
            foreach (self::CHAIN_ORIGIN_ALLOWLIST as $origin) {
                if (str_ends_with($path, $origin)) {
                    $allowed = true;
                    break;
                }
            }
            // The definition of start() itself is not a mint call site.
            if (str_ends_with($path, 'Shared/Application/Messaging/EventProvenance.php')) {
                $allowed = true;
            }
            if (!$allowed) {
                $offenders[] = $path;
            }
        }

        $this->assertSame([], $offenders,
            "Only chain-origin producers may mint a CorrelationId (EventProvenance::start()).\n"
            .'Non-allowlisted mint call sites: '.implode(', ', $offenders));
    }

    public function test_reacting_producers_propagate_never_mint(): void
    {
        $reactingHandlers = [
            'app/Contexts/Election/Application/DeterminationIssuedReactionHandler.php',
            'app/Contexts/Contestation/Application/AdjudicateChallengeHandler.php',
            'app/Contexts/Contestation/Application/ResolveChallengeHandler.php',
        ];
        foreach ($reactingHandlers as $handler) {
            $content = (string) file_get_contents($handler);
            $this->assertStringContainsString('EventProvenance::fromConsumed', $content,
                "$handler must PROPAGATE provenance from the consumed message");
            $this->assertFalse($this->mintsCorrelation($content),
                "$handler must never MINT a correlation (replay safety; one mint per conversation)");
        }
    }

    /** Falsifiability: prove the guard actually bites on offending code — and only code. */
    public function test_the_scanner_flags_a_minting_call_site(): void
    {
        $offending = '<?php $p = EventProvenance::start($this->identities->next());';
        $this->assertTrue($this->mintsCorrelation($offending), 'the guard must detect a mint call site');

        $conforming = '<?php $p = EventProvenance::fromConsumed($msg->correlationId, $msg->eventId);';
        $this->assertFalse($this->mintsCorrelation($conforming), 'propagation must not be flagged');

        // Prose is not a mint: docblock/comment mentions must not trip the guard
        // (first RED run flagged the EventOutbox port docblock — guard made comment-aware).
        $prose = "<?php\n/** a chain-starting producer passes EventProvenance::start(); */\n// EventProvenance::start() explained here\nclass X {}";
        $this->assertFalse($this->mintsCorrelation($prose), 'comments/docblocks must not be flagged');
    }

    /** Comment-aware: only CODE that calls start() counts as a mint site. */
    private function mintsCorrelation(string $content): bool
    {
        $stripped = preg_replace('~/\*.*?\*/~s', '', $content) ?? $content; // block comments/docblocks
        $stripped = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;  // line comments
        $stripped = preg_replace('~#[^\n]*~', '', $stripped) ?? $stripped;   // hash comments

        return str_contains($stripped, 'EventProvenance::start(');
    }

    /** @return iterable<SplFileInfo> */
    private function phpFiles(string $root): iterable
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
        foreach ($iterator as $file) {
            if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
                yield $file;
            }
        }
    }
}
