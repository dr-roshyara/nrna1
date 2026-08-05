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
            // environment-adaptive (capability = FileSaveTrigger; adapter chosen by detection):
            // installed only where VS Code actually exists, never forced elsewhere
            ['action' => 'install VS Code watch task (.vscode/tasks.json — FileSaveTrigger adapter)',
             'needed' => ($state['vscode_present'] ?? false) && !($state['vscode_watch_task'] ?? false)],
            // PlatformProvisioner slice: Claude Code platform detected → merge
            // the ClaudeCodeTrigger hook (preserve user hooks, never overwrite)
            ['action' => 'merge ClaudeCodeTrigger hook into .claude/settings.json (preserving user hooks)',
             'needed' => ($state['claude_present'] ?? false) && !($state['claude_trigger_wired'] ?? false)],
        ];
    }
}
