<?php

namespace App\Infrastructure\Observation;

/**
 * DivergenceTelemetryStore
 *
 * Append-only JSON-lines observation store for D.0 sovereignty convergence telemetry.
 *
 * CONSTITUTIONAL LAW:
 * - Observational only — stores divergence facts, never interprets them
 * - Append-only — never mutates or deletes entries (clear() only for testing)
 * - Lineage preserving — each entry carries full provenance (route, actor, evidence)
 * - No sovereignty decisions — D.0.3 retirement requires human governance review
 */
final class DivergenceTelemetryStore
{
    private string $filePath;

    public function __construct(?string $filePath = null)
    {
        $this->filePath = $filePath ?? storage_path('logs/divergence.observations.jsonl');
    }

    public function path(): string
    {
        return $this->filePath;
    }

    /**
     * Record a divergence observation.
     * Append-only: never modifies existing entries.
     */
    public function record(array $entry): void
    {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $encoded = json_encode($entry + ['recorded_at' => now()->toIso8601String()], JSON_UNESCAPED_SLASHES);
        file_put_contents($this->filePath, $encoded . "\n", FILE_APPEND | LOCK_EX);
    }

    /**
     * Read all divergence observations.
     * Returns array of decoded entries in chronological order.
     */
    public function all(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn(string $line) => json_decode($line, true),
            $lines,
        )));
    }

    /**
     * Count total observations.
     */
    public function count(): int
    {
        if (!file_exists($this->filePath)) {
            return 0;
        }

        $count = 0;
        $handle = fopen($this->filePath, 'rb');
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($line !== false && trim($line) !== '') {
                $count++;
            }
        }
        fclose($handle);

        return $count;
    }

    /**
     * Filter observations since a given timestamp.
     */
    public function since(string $timestamp): array
    {
        return array_values(array_filter(
            $this->all(),
            fn(array $entry) => ($entry['recorded_at'] ?? '') >= $timestamp,
        ));
    }

    /**
     * Filter observations within a time range.
     */
    public function between(string $from, string $until): array
    {
        return array_values(array_filter(
            $this->all(),
            fn(array $entry) => ($entry['recorded_at'] ?? '') >= $from
                && ($entry['recorded_at'] ?? '') <= $until,
        ));
    }

    /**
     * Clear all observations. ONLY for testing.
     */
    public function clear(): void
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }
}
