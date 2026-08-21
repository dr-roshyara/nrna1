<?php

declare(strict_types=1);

/**
 * identifier-check — CAP-001 Identifier Integrity, CLI adapter.
 *
 * Behaviour only. Policy lives in
 *   docs/plans/20260802-0015-pks-identifier-validation-capability-plan.md  (DP-1)
 *   the PMR register                                                       (PMR-10, GOVERNED)
 * Capability contract lives in
 *   scripts/lib/EngineeringKnowledge/Capabilities/IdentifierIntegrity/README.md
 *
 * PMR-10: an identifier must be checked for collision BEFORE it is minted.
 *
 * Usage:
 *   php scripts/identifier-check.php R-72          # may I mint R-72?
 *   php scripts/identifier-check.php --audit R     # what is the state of series R?
 *   php scripts/identifier-check.php --series      # which series are governed?
 *   php scripts/identifier-check.php --document=<path>  # S1 document-local register (Phase 0)
 *
 * Exit: 0 PASS · 1 WARN|INCONCLUSIVE · 2 FAIL · 3 usage error
 *
 * ⛔ This command PREVENTS; it never REPAIRS. Existing collisions are uncurable —
 *    identifier stability forbids renaming (M4; PMR-10's adoption note).
 *
 * --document=<path> is the S7 document-local-register adapter (plan D-1): CAP-001's
 * register notion applied to a single document (its numbered section identifiers).
 * Phase-0 warn-only (D-3): it reports and exits 0 regardless of verdict — it never
 * blocks. The minting and audit paths keep the exit-code contract above.
 */

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\ValidateDocumentLocalIntegrity;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\ValidateIdentifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\IdentifierSeries;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\CliOutputFormatter;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\GovernedRegisterMap;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\MarkdownDocumentContentsReader;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\MarkdownSeriesContentsReader;
use EngineeringKnowledge\Shared\Domain\Verdict;
use EngineeringKnowledge\Shared\Infrastructure\StructuralCliReporter;

require __DIR__.'/../vendor/autoload.php';

$root = dirname(__DIR__);
$registers = new GovernedRegisterMap($root);
$reader = new MarkdownSeriesContentsReader($registers, $root);
$formatter = new CliOutputFormatter();

$args = array_slice($argv, 1);

if ($args === [] || in_array($args[0], ['-h', '--help'], true)) {
    fwrite(STDOUT, <<<TXT
    identifier-check — CAP-001 Identifier Integrity

      php scripts/identifier-check.php <IDENTIFIER>   check one before minting
      php scripts/identifier-check.php --audit <S>    audit a whole series
      php scripts/identifier-check.php --series       list governed series

    Verdicts: PASS · FAIL · WARN · INCONCLUSIVE   (AP-8's emittable subset)

    TXT);
    exit(3);
}

// --series : which register(ns) are governed today?
if ($args[0] === '--series') {
    fwrite(STDOUT, "Governed register(ns) — configuration, NOT a ruling (G-1 is open):\n");
    foreach ($registers->governedSeries() as $s) {
        fwrite(STDOUT, sprintf("  %-8s %s\n", $s, implode(', ', $registers->pathsFor($s))));
    }
    fwrite(STDOUT, "\nEvery other series returns INCONCLUSIVE. That is correct behaviour,\n");
    fwrite(STDOUT, "and it measures G-1 (no canonical register list) rather than hiding it.\n");
    exit(0);
}

// --audit <SERIES> : report the whole series
if ($args[0] === '--audit') {
    $prefix = $args[1] ?? null;

    if ($prefix === null) {
        fwrite(STDERR, "--audit needs a series prefix, e.g. --audit R\n");
        exit(3);
    }

    try {
        $contents = $reader->read(new IdentifierSeries($prefix));
    } catch (Throwable $e) {
        fwrite(STDERR, "[INCONCLUSIVE] series {$prefix}: {$e->getMessage()}\n");
        exit(1);
    }

    if (! $contents->isGoverned()) {
        fwrite(STDOUT, "[INCONCLUSIVE] series '{$prefix}' has no governed register (G-1).\n");
        exit(1);
    }

    fwrite(STDOUT, sprintf(
        "Series %s — %d minted, %d cited-but-unminted.\n",
        $prefix,
        $contents->mintedCount(),
        $contents->citedCount(),
    ));

    exit($contents->citedCount() > 0 ? 1 : 0);
}

// --document=<PATH> : the document-local register (S1, plan D-1).
// Phase-0 warn-only (D-3): reports, never blocks — exit 0 regardless of verdict.
foreach ($args as $arg) {
    if (str_starts_with($arg, '--document=')) {
        $docPath = substr($arg, strlen('--document='));
        if ($docPath === '') {
            fwrite(STDERR, "--document needs a path, e.g. --document=docs/.../PLAN.md\n");
            exit(3);
        }
        $assessment = (new ValidateDocumentLocalIntegrity(new MarkdownDocumentContentsReader()))->handle($docPath);
        $reporter = new StructuralCliReporter();
        fwrite(STDOUT, $reporter->sliceLine('S1', $docPath, $assessment) . "\n\n" . $reporter->notCheckedStatement() . "\n");
        exit(0); // D-3: warn-only
    }
}

// default : check one proposed identifier
$useCase = new ValidateIdentifier($reader);

try {
    $assessment = $useCase->handle($args[0]);
} catch (InvalidArgumentException $e) {
    fwrite(STDERR, "[usage] {$e->getMessage()}\n");
    exit(3);
}

fwrite($assessment->verdict() === Verdict::PASS ? STDOUT : STDERR, $formatter->format($assessment));

exit($formatter->exitCode($assessment));
