<?php

declare(strict_types=1);

/**
 * Observation Runtime — run(ChangeSet) → observations + recommendations.
 *
 * Reuses the EXISTING collectors and engine unchanged; introduces no
 * recommendation logic, no thresholds, no policy. Never scans the
 * repository: changed files only. Trigger-independent by construction —
 * identical ChangeSet + identical sources → identical recommendations,
 * whether triggered by commit, file save, or anything else.
 *
 * Publication is the TRIGGER's decision, not the runtime's: the runtime
 * returns results; CommitTrigger's pipeline persists to streams, the
 * FileSaveTrigger displays ephemerally (stream hygiene — a save is not
 * yet an engineering event worth recording).
 */
final class ObservationRuntime
{
    /**
     * @param list<array<string,mixed>> $rules            rules-as-data rows
     * @param null|callable(string):?string $fileReader   path → source (injected for tests; defaults to disk)
     * @return array{observations: array<string,mixed>, recommendations: list<array<string,mixed>>}
     */
    public static function run(ChangeSet $changeSet, array $rules, ?callable $fileReader = null): array
    {
        require_once __DIR__ . '/Lcom4Collector.php';
        require_once __DIR__ . '/TestPresenceCollector.php';
        require_once __DIR__ . '/RecommendationEngine.php';

        $read = $fileReader ?? fn (string $path): ?string => is_file($path) ? (string) file_get_contents($path) : null;

        $facts = [];
        $lcom4Observations = [];

        foreach ($changeSet->phpClassFiles() as $file) {
            $source = $read($file);
            if ($source === null) {
                continue;   // deleted or unreadable — nothing to observe
            }
            foreach (Lcom4Collector::collect($source) as $obs) {
                $obs['file'] = $file;
                $lcom4Observations[] = $obs;
                $facts['lcom4'][] = [
                    'subject'       => $obs['class'],
                    'value'         => $obs['value'],
                    'evidence_refs' => [$file],
                ];
            }
        }

        $testPresence = TestPresenceCollector::classify($changeSet->changedFiles);
        $facts['test_presence'][] = [
            'subject'                  => $changeSet->source . ':' . implode(',', $changeSet->changedFiles),
            'production_without_tests' => (bool) ($testPresence['production_without_tests'] ?? false),
            'evidence_refs'            => $changeSet->changedFiles,
        ];

        return [
            'observations'    => ['lcom4' => $lcom4Observations, 'test_presence' => $testPresence],
            'recommendations' => RecommendationEngine::evaluate($facts, $rules),
        ];
    }
}
