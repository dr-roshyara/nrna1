<?php

declare(strict_types=1);

/**
 * KnowledgeOS init planner — Track A infrastructure automation.
 *
 * Pure planning over gathered state: every step is deterministic, idempotent,
 * and already understood (the manual sequence this automates was executed by
 * hand on 2026-08-04). No domain decisions live here — "automation of
 * deterministic work is not speculative architecture" (review 2026-08-04).
 */
final class KnowledgeOsInitPlanner
{
    /**
     * @param array<string,bool> $state
     * @return array<int,array{action:string,needed:bool}>
     */
    public static function plan(array $state): array
    {
        return [
            ['action' => 'create observations directory',                          'needed' => !($state['obs_dir_exists'] ?? false)],
            ['action' => 'create metrics directory',                               'needed' => !($state['metrics_dir_exists'] ?? false)],
            ['action' => 'install commit-trigger delegate (.husky/post-commit)',   'needed' => !($state['husky_post_commit'] ?? false)],
            ['action' => 'configure hook path (run npm install — husky prepare)',  'needed' => !($state['hooks_path_set'] ?? false)],
        ];
    }
}
