<?php

declare(strict_types=1);

/**
 * Runner: knowledgeos init — initialize a repository for KnowledgeOS.
 *
 * Idempotent: an already-initialized repository plans zero actions.
 * Executes the plan, then hands off to the doctor for verification.
 *
 * Usage: php scripts/observations/init.php [--dry-run]
 */

require_once __DIR__ . '/KnowledgeOsInitPlanner.php';

$root = dirname(__DIR__, 2);
chdir($root);
$dryRun = in_array('--dry-run', $argv, true);

$obsDir = $root . '/engineering/verification/observations';
$metricsDir = $root . '/engineering/verification/metrics';

$state = [
    'obs_dir_exists'     => is_dir($obsDir),
    'metrics_dir_exists' => is_dir($metricsDir),
    'husky_post_commit'  => is_file($root . '/.husky/post-commit'),
    'hooks_path_set'     => str_contains(trim((string) shell_exec('git config --get core.hooksPath')), '.husky'),
    'vscode_present'     => is_dir($root . '/.vscode'),
    'vscode_watch_task'  => is_file($root . '/.vscode/tasks.json'),
    'claude_present'     => is_dir($root . '/.claude'),
    'claude_trigger_wired' => is_file($root . '/.claude/settings.json')
        && str_contains((string) file_get_contents($root . '/.claude/settings.json'), 'claude-code-trigger.sh'),
];

$plan = KnowledgeOsInitPlanner::plan($state);
$needed = array_values(array_filter($plan, fn ($s) => $s['needed']));

echo "KnowledgeOS init" . ($dryRun ? ' (dry run)' : '') . "\n\n";
foreach ($plan as $step) {
    printf("%s %s\n", $step['needed'] ? '→' : '✓', $step['action']);
}

if ($needed === []) {
    echo "\nalready initialized — nothing to do\n";
} elseif (!$dryRun) {
    foreach ($needed as $step) {
        switch ($step['action']) {
            case 'create observations directory':
                mkdir($obsDir, 0777, true);
                break;
            case 'create metrics directory':
                mkdir($metricsDir, 0777, true);
                break;
            case 'install commit-trigger delegate (.husky/post-commit)':
                if (!is_dir($root . '/.husky')) {
                    mkdir($root . '/.husky', 0777, true);
                }
                file_put_contents(
                    $root . '/.husky/post-commit',
                    "# post-commit — CommitTrigger instrumentation (installed by knowledgeos init)\n"
                    . "bash scripts/observations/git-hooks/post-commit || true\n"
                );
                break;
            case 'configure hook path (run npm install — husky prepare)':
                echo "  (manual: run `npm install` — the husky prepare script configures core.hooksPath)\n";
                break;
            case 'merge ClaudeCodeTrigger hook into .claude/settings.json (preserving user hooks)':
                $sf = $root . '/.claude/settings.json';
                $settings = is_file($sf) ? (json_decode((string) file_get_contents($sf), true) ?: []) : [];
                $hookEntry = ['type' => 'command',
                    'command' => 'bash "$CLAUDE_PROJECT_DIR"/.claude/scripts/claude-code-trigger.sh',
                    'statusMessage' => 'KnowledgeOS: observing changed class'];
                $merged = false;
                foreach ($settings['hooks']['PostToolUse'] ?? [] as $i => $group) {
                    if (str_contains((string) ($group['matcher'] ?? ''), 'Edit')) {
                        $settings['hooks']['PostToolUse'][$i]['hooks'][] = $hookEntry;   // MERGE: user hooks preserved
                        $merged = true;
                        break;
                    }
                }
                if (!$merged) {
                    $settings['hooks']['PostToolUse'][] = ['matcher' => 'Write|Edit', 'hooks' => [$hookEntry]];
                }
                file_put_contents($sf, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                echo "  (restart the Claude Code session for the hook to load)\n";
                break;
            case 'install VS Code watch task (.vscode/tasks.json — FileSaveTrigger adapter)':
                file_put_contents($root . '/.vscode/tasks.json', json_encode([
                    'version' => '2.0.0',
                    'tasks'   => [[
                        'label'          => 'KnowledgeOS: dev session (verify + prove + observe)',
                        'type'           => 'shell',
                        'command'        => 'php scripts/observations/dev.php',
                        'isBackground'   => true,
                        'problemMatcher' => [],
                        // auto-start on workspace open (VS Code asks once: "Allow Automatic Tasks")
                        // — closes the "watcher was never running" gap found 2026-08-04
                        'runOptions'     => ['runOn' => 'folderOpen'],
                    ]],
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
        }
    }
    echo "\ninitialized — verifying:\n\n";
    passthru('php ' . escapeshellarg(__DIR__ . '/doctor.php'), $exit);
    exit($exit);
}

exit(0);
