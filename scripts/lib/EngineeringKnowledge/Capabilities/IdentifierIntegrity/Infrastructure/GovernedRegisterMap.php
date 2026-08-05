<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure;

use RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * Which register(ns) are governed, and where each one lives.
 *
 * ⛔ THIS CLASS OWNS NO POLICY. It LOADS configuration.
 *    Configuration:  docs/knowledge/schema/governed-registers.yaml
 *    Policy:         PMR-10 (GOVERNED) · AP-4
 *    Behaviour:      this class
 *
 * That separation is the house rule — *ADRs govern policy · the registry stores
 * configuration · scripts execute behaviour; each fact lives in exactly one layer.*
 * Adding a register is a CONFIGURATION EDIT, not a code change.
 *
 * ⚠️ The configuration is NOT a ruling. G-1 (no canonical register list) is open, and
 *    every series absent from it returns INCONCLUSIVE — which measures G-1 rather than
 *    hiding it. AP-7/DR-4: criteria are used here, owned elsewhere.
 */
final class GovernedRegisterMap
{
    public const DEFAULT_CONFIG = 'docs/knowledge/schema/governed-registers.yaml';

    /** @var array<string, list<string>>|null series prefix => repo-relative files */
    private ?array $registers = null;

    public function __construct(
        private readonly string $repositoryRoot,
        private readonly string $configPath = self::DEFAULT_CONFIG,
    ) {
    }

    public function isGoverned(string $seriesPrefix): bool
    {
        return isset($this->load()[$seriesPrefix]);
    }

    /** @return list<string> absolute paths of every file holding this register(ns) */
    public function pathsFor(string $seriesPrefix): array
    {
        return array_map(
            fn (string $rel): string => $this->repositoryRoot.'/'.$rel,
            $this->load()[$seriesPrefix] ?? [],
        );
    }

    /** @return list<string> */
    public function governedSeries(): array
    {
        return array_keys($this->load());
    }

    /** @return array<string, list<string>> */
    private function load(): array
    {
        if ($this->registers !== null) {
            return $this->registers;
        }

        $path = $this->repositoryRoot.'/'.$this->configPath;

        if (! is_readable($path)) {
            throw new RuntimeException("governed-registers configuration is not readable at {$path}");
        }

        /** @var array{registers?: array<string, array{files?: list<string>}>} $parsed */
        $parsed = Yaml::parseFile($path) ?? [];

        $out = [];

        foreach ($parsed['registers'] ?? [] as $prefix => $entry) {
            $files = $entry['files'] ?? [];

            if ($files === []) {
                // A register with no file cannot be evaluated; omit it so the series
                // returns INCONCLUSIVE rather than a spuriously empty PASS.
                continue;
            }

            $out[(string) $prefix] = array_values($files);
        }

        return $this->registers = $out;
    }
}
