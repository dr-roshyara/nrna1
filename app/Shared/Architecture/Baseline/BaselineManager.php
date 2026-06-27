<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Baseline;

use App\Shared\Architecture\Rule\RuleResult;

final class BaselineManager
{
    private const STORAGE_DIR = 'storage' . \DIRECTORY_SEPARATOR . 'architecture-baselines';

    private string $storagePath;

    /** @var array<string, array<string, StructuredViolation>> context => (hash => violation) */
    private array $loaded = [];

    public function __construct(?string $storagePath = null)
    {
        $this->storagePath = $storagePath ?? base_path(self::STORAGE_DIR);
    }

    /**
     * Generate a baseline from RuleResult[] and persist to disk.
     *
     * @param RuleResult[] $results
     * @return StructuredViolation[]
     */
    public function generate(string $context, array $results): array
    {
        $violations = [];
        foreach ($results as $result) {
            foreach ($result->violations as $violation) {
                $file = $this->extractFile($violation);
                $symbol = $this->extractSymbol($violation);
                $violations[] = StructuredViolation::create(
                    rule: $result->ruleName,
                    file: $file,
                    symbol: $symbol,
                    message: $violation,
                );
            }
        }

        // Deterministic: sort by (rule, file, symbol) before persisting
        usort($violations, fn (StructuredViolation $a, StructuredViolation $b) =>
            [$a->rule, $a->file, $a->symbol] <=> [$b->rule, $b->file, $b->symbol]
        );

        $this->persist($context, $violations);
        return $violations;
    }

    /**
     * Load baseline for a context.
     *
     * @return StructuredViolation[]  Indexed by hash
     */
    public function load(string $context): array
    {
        if (isset($this->loaded[$context])) {
            return $this->loaded[$context];
        }

        $filePath = $this->filePath($context);

        if (!file_exists($filePath)) {
            $this->loaded[$context] = [];
            return [];
        }

        $raw = file_get_contents($filePath);

        if ($raw === false) {
            $this->loaded[$context] = [];
            return [];
        }

        try {
            $data = json_decode($raw, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            trigger_error("Baseline file corrupted: {$filePath} — returning empty baseline", \E_USER_WARNING);
            $this->loaded[$context] = [];
            return [];
        }

        $violations = [];
        foreach ($data['violations'] ?? [] as $entry) {
            try {
                $violation = StructuredViolation::fromArray($entry);
                $violations[$violation->hash] = $violation;
            } catch (\TypeError $e) {
                // Skip malformed entries
                continue;
            }
        }

        $this->loaded[$context] = $violations;
        return $violations;
    }

    /**
     * Filter violations to only those NOT in the baseline (i.e. new violations).
     *
     * @param StructuredViolation[] $violations
     * @return StructuredViolation[]
     */
    public function filterNewViolations(array $violations, string $context): array
    {
        $baseline = $this->load($context);

        return array_values(
            array_filter(
                $violations,
                fn (StructuredViolation $v) => !isset($baseline[$v->hash]),
            ),
        );
    }

    /**
     * Check if a specific violation hash is known in the baseline.
     */
    public function isKnown(string $hash, string $context): bool
    {
        $baseline = $this->load($context);

        return isset($baseline[$hash]);
    }

    /**
     * Convenience: check if a StructuredViolation is in the baseline.
     */
    public function isBaselineViolation(StructuredViolation $violation, string $context): bool
    {
        return $this->isKnown($violation->hash, $context);
    }

    /**
     * Compute new vs resolved violations between current state and baseline.
     *
     * @param StructuredViolation[] $current
     * @param StructuredViolation[] $baseline  Hash-indexed array from load()
     * @return array{new: StructuredViolation[], resolved: StructuredViolation[]}
     */
    public function diff(array $current, array $baseline): array
    {
        $baselineHashes = array_keys($baseline);

        $new = [];
        foreach ($current as $v) {
            if (!isset($baseline[$v->hash])) {
                $new[] = $v;
            }
        }

        $currentHashes = array_flip(
            array_map(fn (StructuredViolation $v) => $v->hash, $current),
        );

        $resolved = [];
        foreach ($baseline as $hash => $v) {
            if (!isset($currentHashes[$hash])) {
                $resolved[] = $v;
            }
        }

        return ['new' => $new, 'resolved' => $resolved];
    }

    // ─── Private helpers ─────────────────────────────────────────────────

    private function extractFile(string $violation): string
    {
        // Match Windows absolute (C:\...), Unix absolute (/...), and relative paths (.php)
        if (preg_match('#([A-Za-z]:(?:\\\\|/)[^:\n]+\.php|/[^:\n]+\.php|(?:\./)?[^:\n]+\.php)#', $violation, $m)) {
            return str_replace('\\', '/', $m[1]);
        }

        return 'unknown';
    }

    private function extractSymbol(string $violation): string
    {
        // Try to extract a class name (word starting with uppercase)
        if (preg_match('#\b([A-Z][a-zA-Z0-9_]+)\b#', $violation, $m)) {
            return $m[1];
        }

        return 'unknown';
    }

    /**
     * @param StructuredViolation[] $violations
     */
    private function persist(string $context, array $violations): void
    {
        $dir = $this->storagePath;

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $data = [
            'generated_at' => date('c'),
            'context' => $context,
            'violations' => array_map(
                fn (StructuredViolation $v) => $v->toArray(),
                $violations,
            ),
        ];

        file_put_contents(
            $this->filePath($context),
            json_encode($data, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES),
        );
    }

    private function filePath(string $context): string
    {
        return $this->storagePath . \DIRECTORY_SEPARATOR . $context . '.json';
    }
}
