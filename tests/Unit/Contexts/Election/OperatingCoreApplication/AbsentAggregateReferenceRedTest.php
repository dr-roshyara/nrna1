<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use PHPUnit\Framework\TestCase;

/**
 * EM-IMPL-002 — RED AMENDMENT (architecture hold before GREEN-5, the PO's added item).
 *
 * THE PIN, in the PO's words:
 *
 *   > **Application handlers must not silently dereference absent aggregate
 *   > references.**
 *
 * ⚠️ THIS PIN FAILS ON ARRIVAL, AND THAT IS ITS PURPOSE. Unlike
 * `ConstitutionalValueConsumptionRedTest` (a regression lock over behaviour already
 * true), this is a genuine RED: UC-3 dereferences possibly-absent references today,
 * which the GREEN-4 verification record identified as a robustness regression. Its
 * GREEN is the NORMALIZATION SLICE that follows the absence-semantics ruling — not
 * this file, and not a repair improvised now.
 *
 * WHAT THIS PIN DELIBERATELY DOES NOT DO — it does not choose an exception model.
 * The defect is not the exception TYPE; it is that the handlers hold different
 * interpretations and no decision. So the pin demands only that **every lookup of a
 * possibly-absent aggregate reaches an EXPLICIT decision point before the reference
 * is used.** All of these satisfy it:
 *
 *   $x = $repo->find(...) ?? throw <whatever the ruling chooses>;
 *   if ($x === null) { <refuse · record · return · throw> }
 *   if ($x !== null) { ... }
 *   is_null($x)
 *
 * A recorded refusal (ADR option C), a caller error (option A), an integrity
 * failure (option B) and an early return all pass. Only SILENCE fails. Which
 * MEANING absence carries belongs to
 * `docs/publicdigit/adr/ADR_20260817_2145_Aggregate_Absence_Semantics.md`, whose
 * decision block is blank and is the PO's.
 *
 * ANALYSIS IS PER METHOD BODY, NOT PER FILE — deliberately. The first draft of this
 * pin scanned whole files and was WRONG TWICE: it reported
 * `$x = $repo->find(...) ?? throw …` as unguarded (the `??` follows the call, not
 * the variable), and it accepted a guard on a same-named variable in ANOTHER method
 * as protection — which hid a real violation. A guard counts only where the
 * reference actually lives.
 *
 * Scope: command handlers only. Queries are excluded — they are unimplemented, and
 * read-side absence (an answer about a thing that does not exist) is a distinct
 * question the ADR does not cover.
 */
final class AbsentAggregateReferenceRedTest extends TestCase
{
    private const HANDLER_DIR = __DIR__ . '/../../../../../app/Contexts/Election/Application/OperatingCore/Handler';

    public function test_no_handler_silently_dereferences_a_possibly_absent_aggregate(): void
    {
        $handlers = $this->handlerSources();
        $this->assertNotEmpty($handlers, 'The granted handlers must exist for this pin to mean anything.');

        $violations = [];

        foreach ($handlers as $file) {
            $source = (string) file_get_contents($file);
            $nullableHelpers = $this->nullableHelperNames($source);

            foreach ($this->methodBodies($source) as $body) {
                foreach ($this->possiblyAbsentReferences($body, $nullableHelpers) as $variable => $origin) {
                    if (! $this->isDereferenced($body, $variable)) {
                        continue; // Never used as an object here: nothing can be dereferenced.
                    }

                    if ($this->absenceIsDecidedExplicitly($body, $variable)) {
                        continue; // A decision exists — whatever shape the ruling gives it.
                    }

                    $violations[] = sprintf('%s: $%s (from %s)', basename($file), $variable, $origin);
                }
            }
        }

        sort($violations);

        $this->assertSame(
            [],
            $violations,
            "A possibly-absent aggregate reference is dereferenced with no explicit decision about its absence:\n  - "
            . implode("\n  - ", $violations)
            . "\n\nThe pin does NOT prescribe the outcome: a recorded refusal, a caller error, an integrity failure or an "
            . "early return all satisfy it. Only silence fails it. Which MEANING absence carries is the open ruling in "
            . 'ADR_20260817_2145_Aggregate_Absence_Semantics.md — and the normalization slice that closes this pin is '
            . 'authorized there, never improvised here.'
        );
    }

    /** @return list<string> */
    private function handlerSources(): array
    {
        $files = glob(self::HANDLER_DIR . '/*.php') ?: [];
        sort($files);

        return $files;
    }

    /**
     * Splits a class into method-sized chunks: chunk 0 holds the class header and
     * constants, every later chunk begins at a method declaration.
     *
     * @return list<string>
     */
    private function methodBodies(string $source): array
    {
        return preg_split('/\n(?=    (?:public|private|protected) function )/', $source) ?: [];
    }

    /**
     * Private helpers of this class declaring a NULLABLE return type — their results
     * are possibly-absent references exactly like a repository lookup.
     *
     * @return list<string>
     */
    private function nullableHelperNames(string $source): array
    {
        preg_match_all('/private function (\w+)\s*\([^)]*\)\s*:\s*\?/', $source, $matches);

        return $matches[1];
    }

    /**
     * Possibly-absent references assigned inside THIS body.
     *
     * @param list<string> $nullableHelpers
     * @return array<string, string> variableName => originDescription
     */
    private function possiblyAbsentReferences(string $body, array $nullableHelpers): array
    {
        $references = [];

        preg_match_all('/\$(\w+)\s*=\s*\$this->\w+->find\s*\(/', $body, $lookups, PREG_SET_ORDER);
        foreach ($lookups as $lookup) {
            $references[$lookup[1]] = 'a repository find()';
        }

        foreach ($nullableHelpers as $helperName) {
            $pattern = '/\$(\w+)\s*=\s*\$this->' . preg_quote($helperName, '/') . '\s*\(/';
            preg_match_all($pattern, $body, $uses, PREG_SET_ORDER);
            foreach ($uses as $use) {
                $references[$use[1]] = sprintf('the nullable helper %s()', $helperName);
            }
        }

        return $references;
    }

    /** True where the variable is used as an object within this body. */
    private function isDereferenced(string $body, string $variable): bool
    {
        return preg_match('/' . preg_quote('$' . $variable, '/') . '->/', $body) === 1;
    }

    /**
     * True where THIS body resolves the variable's absence explicitly — by any shape:
     * null-coalescing on the assignment itself, an explicit comparison, or is_null().
     */
    private function absenceIsDecidedExplicitly(string $body, string $variable): bool
    {
        $v = preg_quote('$' . $variable, '/');

        // (a) the assignment statement itself resolves absence: `$x = … ?? …;`
        if (preg_match('/' . $v . '\s*=\s*[^;]*;/s', $body, $statement) === 1
            && str_contains($statement[0], '??')
        ) {
            return true;
        }

        // (b) an explicit test within the same body.
        foreach ([
            '/' . $v . '\s*(===|!==)\s*null/i',
            '/null\s*(===|!==)\s*' . $v . '/i',
            '/is_null\s*\(\s*' . $v . '\s*\)/i',
        ] as $pattern) {
            if (preg_match($pattern, $body) === 1) {
                return true;
            }
        }

        return false;
    }
}
