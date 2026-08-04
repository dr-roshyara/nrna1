<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first for the KnowledgeOS doctor: pure verification of gathered
 * environment facts. The doctor CHECKS and REPORTS — it never installs,
 * never repairs, never judges code. (Platform Bootstrap's earned slice:
 * the 2026-08-04 hook diagnosis was this capability executed by hand.)
 */
final class KnowledgeOsDoctorTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/KnowledgeOsDoctor.php';
    }

    private function healthy(): array
    {
        return [
            'git_present'          => true,
            'hooks_path'           => '.husky/_',
            'husky_post_commit'    => true,
            'canonical_hook'       => true,
            'php_present'          => true,
            'obs_dir_writable'     => true,
            'collectors_present'   => ['test-presence-observer.php', 'lcom4-observer.php', 'recommendation-observer.php'],
            'streams_parseable'    => true,
        ];
    }

    public function test_healthy_environment_reports_ready(): void
    {
        $report = \KnowledgeOsDoctor::diagnose($this->healthy());

        $this->assertTrue($report['ready']);
        $this->assertNotEmpty($report['checks']);
        foreach ($report['checks'] as $check) {
            $this->assertTrue($check['ok'], $check['name']);
        }
    }

    public function test_missing_husky_delegate_fails_the_exact_check_that_caught_the_real_incident(): void
    {
        $state = $this->healthy();
        $state['husky_post_commit'] = false;

        $report = \KnowledgeOsDoctor::diagnose($state);

        $this->assertFalse($report['ready']);
        $failed = array_values(array_filter($report['checks'], fn ($c) => !$c['ok']));
        $this->assertCount(1, $failed);
        $this->assertSame('commit trigger active (.husky/post-commit)', $failed[0]['name']);
    }

    public function test_live_diagnosis_verifies_the_full_pipeline_not_just_installation(): void
    {
        $report = \KnowledgeOsDoctor::diagnoseLive([
            'task_installed'      => true,
            'task_runs_dev'       => true,
            'extension_source'    => true,
            'extension_installed' => false,   // honest: source exists, user hasn't installed the vsix
            'claude_trigger'      => true,
            'chain_proven'        => true,
            'chain_recs'          => 2,
        ]);

        $byName = array_column($report['checks'], null, 'name');
        $this->assertTrue($byName['folderOpen task runs the dev lifecycle']['ok']);
        $this->assertTrue($byName['observation chain proven end-to-end']['ok']);
        $this->assertFalse($byName['VS Code extension installed']['ok']);
        $this->assertFalse($report['ready'], 'live experience is NOT ready while the extension is uninstalled');
    }

    public function test_live_diagnosis_ready_when_every_stage_verifies(): void
    {
        $report = \KnowledgeOsDoctor::diagnoseLive([
            'task_installed'      => true,
            'task_runs_dev'       => true,
            'extension_source'    => true,
            'extension_installed' => true,
            'claude_trigger'      => true,
            'chain_proven'        => true,
            'chain_recs'          => 2,
        ]);

        $this->assertTrue($report['ready']);
    }

    public function test_hooks_path_not_owned_by_husky_is_reported_not_assumed(): void
    {
        $state = $this->healthy();
        $state['hooks_path'] = '';   // default .git/hooks — husky not configured (npm install not run)

        $report = \KnowledgeOsDoctor::diagnose($state);

        $this->assertFalse($report['ready']);
        $failed = array_values(array_filter($report['checks'], fn ($c) => !$c['ok']));
        $this->assertSame('hook path configured (husky prepare)', $failed[0]['name']);
        $this->assertStringContainsString('npm install', $failed[0]['detail']);
    }
}
