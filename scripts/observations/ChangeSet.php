<?php

declare(strict_types=1);

/**
 * ChangeSet — the technology-neutral object every trigger produces and the
 * only thing the Observation Runtime consumes.
 *
 * NOT GitCommit, NOT VSCodeFile, NOT PullRequest: the runtime must never
 * know how an observation was triggered (commission 2026-08-04).
 */
final class ChangeSet
{
    /** @param list<string> $changedFiles repo-relative paths */
    public function __construct(
        public readonly array $changedFiles,
        public readonly string $source,
        public readonly string $timestamp,
        public readonly ?string $commitId = null,
    ) {
    }

    /**
     * Changed PHP class files — the LCOM4-relevant subset.
     * Blade templates are not production classes (same rule as test-presence).
     *
     * @return list<string>
     */
    public function phpClassFiles(): array
    {
        return array_values(array_filter(
            $this->changedFiles,
            fn (string $f) => str_ends_with($f, '.php') && !str_ends_with($f, '.blade.php')
        ));
    }
}
