<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\SeriesContentsReader;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\Identifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\IdentifierSeries;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\SeriesContents;
use RuntimeException;

/**
 * Reads a register(ns) directly from its governed markdown register.
 *
 * ⛔ DR-1 / AP-2: reads the GOVERNED REGISTER ITSELF. No cached index, no projection.
 * ⛔ AP-4:        observes an existing register; creates no source of truth.
 * ⛔ AP-7 / DR-4: read-only throughout. Nothing here authors a criterion.
 *
 * MINTED  = the identifier appears as a register ROW (a table row beginning `| R-nn |`,
 *           or a heading), i.e. it is entered in the register.
 * CITED   = the identifier appears anywhere in the scanned corpus but is NOT minted —
 *           a collision HAZARD, which is the R-65..R-71 case.
 *
 * Replaceable: the port is `SeriesContentsReader`; a YAML or graph reader substitutes
 * here without the domain changing.
 */
final readonly class MarkdownSeriesContentsReader implements SeriesContentsReader
{
    /** @param list<string> $citationScanRoots repo-relative directories scanned for citations */
    public function __construct(
        private GovernedRegisterMap $registers,
        private string $repositoryRoot,
        private array $citationScanRoots = ['docs', 'engineering'],
    ) {
    }

    public function read(IdentifierSeries $series): SeriesContents
    {
        $prefix = $series->prefix();

        if (! $this->registers->isGoverned($prefix)) {
            return SeriesContents::ungoverned($series);
        }

        $minted = [];

        foreach ($this->registers->pathsFor($prefix) as $path) {
            if (! is_readable($path)) {
                throw new RuntimeException("register for series '{$prefix}' is not readable at {$path}");
            }

            $register = file_get_contents($path);

            if ($register === false) {
                throw new RuntimeException("register for series '{$prefix}' could not be read");
            }

            $minted = array_merge($minted, $this->mintedIn($register, $prefix));
        }

        $minted = array_values(array_unique($minted));
        $cited = $this->citedInCorpus($prefix, $minted);

        return SeriesContents::governed(
            $series,
            array_map(static fn (string $v): Identifier => Identifier::fromString($v), $minted),
            array_map(static fn (string $v): Identifier => Identifier::fromString($v), $cited),
        );
    }

    /**
     * A row of the register — a leading table cell, or a heading.
     *
     * @return list<string>
     */
    private function mintedIn(string $register, string $prefix): array
    {
        $q = preg_quote($prefix, '/');
        $found = [];

        // `| R-30 | ...`  or  `| **R-30** | ...`
        if (preg_match_all('/^\|\s*\**\s*('.$q.'-[A-Za-z0-9.]+)\s*\**\s*\|/m', $register, $m) !== false) {
            $found = array_merge($found, $m[1]);
        }

        // `## R-30 — ...` / `### ADR-T22 ...`
        if (preg_match_all('/^#{1,6}\s+\**\s*('.$q.'-[A-Za-z0-9.]+)/m', $register, $m) !== false) {
            $found = array_merge($found, $m[1]);
        }

        return array_values(array_unique($found));
    }

    /**
     * Identifiers of this series cited anywhere in the corpus but not minted.
     *
     * @param  list<string>  $minted
     * @return list<string>
     */
    private function citedInCorpus(string $prefix, array $minted): array
    {
        $q = preg_quote($prefix, '/');
        $mintedSet = array_flip($minted);
        $cited = [];

        foreach ($this->citationScanRoots as $root) {
            $dir = $this->repositoryRoot.'/'.$root;

            if (! is_dir($dir)) {
                continue;
            }

            $it = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($it as $file) {
                if (! $file->isFile() || $file->getExtension() !== 'md') {
                    continue;
                }

                $text = file_get_contents($file->getPathname());

                if ($text === false) {
                    continue;
                }

                if (preg_match_all('/\b('.$q.'-[0-9]+)\b/', $text, $m) === false) {
                    continue;
                }

                foreach ($m[1] as $candidate) {
                    if (! isset($mintedSet[$candidate])) {
                        $cited[$candidate] = true;
                    }
                }
            }
        }

        return array_keys($cited);
    }
}
