<?php

declare(strict_types=1);

/**
 * FileSaveTrigger — pure change detection over mtime snapshots.
 *
 * The trigger detects and delegates: no recommendation logic, no collector
 * knowledge (commission 2026-08-04). The watch.php runner polls; IDE
 * integrations (VS Code task, plugins) are adapters that delegate here.
 */
final class FileSaveTrigger
{
    /**
     * @param array<string,int> $previous path => mtime
     * @param array<string,int> $current  path => mtime
     * @return list<string> changed or new files, in current-snapshot order
     */
    public static function detect(array $previous, array $current): array
    {
        $changed = [];
        foreach ($current as $path => $mtime) {
            if (!isset($previous[$path]) || $previous[$path] !== $mtime) {
                $changed[] = $path;
            }
        }
        return $changed;
    }

    /** @param list<string> $changedFiles */
    public static function changeSet(array $changedFiles, string $timestamp): ChangeSet
    {
        return new ChangeSet(
            changedFiles: $changedFiles,
            source: 'file-save',
            timestamp: $timestamp,
            commitId: null,
        );
    }
}
