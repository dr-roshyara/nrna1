<?php

declare(strict_types=1);

/**
 * KnowledgeOS doctor — pure verification of gathered environment facts.
 *
 * Platform Bootstrap's earned slice (2026-08-04): the observation hook sat
 * active NOWHERE until a manual diagnosis found it; this class is that
 * diagnosis, made repeatable. It checks and reports — it never installs,
 * never repairs. Installation belongs to husky's npm `prepare` script.
 */
final class KnowledgeOsDoctor
{
    /**
     * @param array<string,mixed> $state gathered facts (see doctor.php runner)
     * @return array{ready: bool, checks: array<int,array{name:string,ok:bool,detail:string}>}
     */
    public static function diagnose(array $state): array
    {
        $checks = [];

        $checks[] = [
            'name'   => 'git repository',
            'ok'     => (bool) ($state['git_present'] ?? false),
            'detail' => ($state['git_present'] ?? false) ? 'detected' : 'not a git repository',
        ];

        $huskyOwned = str_contains((string) ($state['hooks_path'] ?? ''), '.husky');
        $checks[] = [
            'name'   => 'hook path configured (husky prepare)',
            'ok'     => $huskyOwned,
            'detail' => $huskyOwned
                ? 'core.hooksPath = ' . $state['hooks_path']
                : 'core.hooksPath not owned by husky — run `npm install` (the prepare script configures it)',
        ];

        $checks[] = [
            'name'   => 'commit trigger active (.husky/post-commit)',
            'ok'     => (bool) ($state['husky_post_commit'] ?? false),
            'detail' => ($state['husky_post_commit'] ?? false)
                ? 'delegate present'
                : 'MISSING — the exact gap of the 2026-08-04 incident; restore .husky/post-commit from git',
        ];

        $checks[] = [
            'name'   => 'canonical hook script',
            'ok'     => (bool) ($state['canonical_hook'] ?? false),
            'detail' => ($state['canonical_hook'] ?? false) ? 'scripts/observations/git-hooks/post-commit present' : 'canonical script missing',
        ];

        $checks[] = [
            'name'   => 'php runtime',
            'ok'     => (bool) ($state['php_present'] ?? false),
            'detail' => ($state['php_present'] ?? false) ? 'available' : 'php not on PATH',
        ];

        $checks[] = [
            'name'   => 'observations directory writable',
            'ok'     => (bool) ($state['obs_dir_writable'] ?? false),
            'detail' => ($state['obs_dir_writable'] ?? false) ? 'engineering/verification/observations' : 'not writable',
        ];

        $collectors = (array) ($state['collectors_present'] ?? []);
        $checks[] = [
            'name'   => 'collectors present',
            'ok'     => $collectors !== [],
            'detail' => $collectors !== [] ? implode(' · ', $collectors) : 'no collectors found',
        ];

        $checks[] = [
            'name'   => 'observation streams parseable',
            'ok'     => (bool) ($state['streams_parseable'] ?? false),
            'detail' => ($state['streams_parseable'] ?? false) ? 'jsonl streams valid' : 'a stream failed to parse',
        ];

        return [
            'ready'  => !in_array(false, array_column($checks, 'ok'), true),
            'checks' => $checks,
        ];
    }

    /**
     * Live-pipeline diagnosis (`doctor --live`, review 2026-08-04): verifies
     * the complete live developer experience, not just installation.
     * "IDE diagnostics received" cannot be verified headlessly — the closest
     * honest checks are extension-installed + chain-proven.
     *
     * @param array<string,mixed> $state
     * @return array{ready: bool, checks: array<int,array{name:string,ok:bool,detail:string}>}
     */
    public static function diagnoseLive(array $state): array
    {
        $checks = [
            ['name' => 'VS Code task installed',
             'ok' => (bool) ($state['task_installed'] ?? false),
             'detail' => ($state['task_installed'] ?? false) ? '.vscode/tasks.json present' : 'missing — run init'],
            ['name' => 'folderOpen task runs the dev lifecycle',
             'ok' => (bool) ($state['task_runs_dev'] ?? false),
             'detail' => ($state['task_runs_dev'] ?? false) ? 'auto-starts dev.php on workspace open' : 'task exists but does not run dev.php'],
            ['name' => 'VS Code extension source present',
             'ok' => (bool) ($state['extension_source'] ?? false),
             'detail' => ($state['extension_source'] ?? false) ? 'scripts/observations/vscode-knowledgeos' : 'extension source missing'],
            ['name' => 'VS Code extension installed',
             'ok' => (bool) ($state['extension_installed'] ?? false),
             'detail' => ($state['extension_installed'] ?? false)
                 ? 'in-editor popups available (rendering itself not verifiable headlessly)'
                 : 'NOT installed — terminal feedback only; F5 dev-mode or vsce package + install'],
            ['name' => 'observation chain proven end-to-end',
             'ok' => (bool) ($state['chain_proven'] ?? false),
             'detail' => ($state['chain_proven'] ?? false)
                 ? sprintf('runtime → collectors → %d recommendation(s)', (int) ($state['chain_recs'] ?? 0))
                 : 'chain self-test FAILED — run dev.php for per-stage traces'],
        ];

        return [
            'ready'  => !in_array(false, array_column($checks, 'ok'), true),
            'checks' => $checks,
        ];
    }
}
