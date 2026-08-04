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
}
