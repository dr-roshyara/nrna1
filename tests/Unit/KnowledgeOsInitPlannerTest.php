<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first for the init planner: Track A infrastructure automation
 * (review 2026-08-04: "automation of deterministic work is not speculative
 * architecture"). Pure planning over gathered state; the runner executes.
 * Idempotency is the core contract: an initialized repo plans ZERO actions.
 */
final class KnowledgeOsInitPlannerTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/KnowledgeOsInitPlanner.php';
    }

    private function initialized(): array
    {
        return [
            'obs_dir_exists'     => true,
            'metrics_dir_exists' => true,
            'husky_post_commit'  => true,
            'hooks_path_set'     => true,
        ];
    }

    public function test_fresh_repository_plans_every_step(): void
    {
        $plan = \KnowledgeOsInitPlanner::plan([
            'obs_dir_exists'     => false,
            'metrics_dir_exists' => false,
            'husky_post_commit'  => false,
            'hooks_path_set'     => false,
        ]);

        $needed = array_column(array_filter($plan, fn ($s) => $s['needed']), 'action');
        $this->assertContains('create observations directory', $needed);
        $this->assertContains('create metrics directory', $needed);
        $this->assertContains('install commit-trigger delegate (.husky/post-commit)', $needed);
        $this->assertContains('configure hook path (run npm install — husky prepare)', $needed);
    }

    public function test_initialized_repository_plans_nothing(): void
    {
        $plan = \KnowledgeOsInitPlanner::plan($this->initialized());

        $this->assertNotEmpty($plan);
        $this->assertSame([], array_filter($plan, fn ($s) => $s['needed']), 'init must be idempotent');
    }

    public function test_partial_state_plans_only_the_gaps(): void
    {
        $state = $this->initialized();
        $state['husky_post_commit'] = false;

        $plan = \KnowledgeOsInitPlanner::plan($state);
        $needed = array_values(array_filter($plan, fn ($s) => $s['needed']));

        $this->assertCount(1, $needed);
        $this->assertSame('install commit-trigger delegate (.husky/post-commit)', $needed[0]['action']);
    }

    public function test_claude_hook_provisioned_only_when_claude_platform_detected(): void
    {
        // PlatformProvisioner slice (review 2026-08-04): detect the AI
        // platform, MERGE the trigger hook if absent — never overwrite,
        // never provision a platform that isn't there
        $claudeNoHook = $this->initialized();
        $claudeNoHook['claude_present'] = true;
        $claudeNoHook['claude_trigger_wired'] = false;

        $needed = array_column(array_filter(\KnowledgeOsInitPlanner::plan($claudeNoHook), fn ($s) => $s['needed']), 'action');
        $this->assertSame(['merge ClaudeCodeTrigger hook into .claude/settings.json (preserving user hooks)'], $needed);

        $claudeWired = $claudeNoHook;
        $claudeWired['claude_trigger_wired'] = true;
        $this->assertSame([], array_filter(\KnowledgeOsInitPlanner::plan($claudeWired), fn ($s) => $s['needed']), 'idempotent when already wired');

        $this->assertSame([], array_filter(\KnowledgeOsInitPlanner::plan($this->initialized()), fn ($s) => $s['needed']), 'no Claude platform → nothing provisioned');
    }

    public function test_vscode_watch_task_planned_only_when_vscode_detected(): void
    {
        // environment-adaptive: the adapter is installed only where its environment exists
        $withVscode = $this->initialized();
        $withVscode['vscode_present'] = true;
        $withVscode['vscode_watch_task'] = false;

        $needed = array_column(array_filter(\KnowledgeOsInitPlanner::plan($withVscode), fn ($s) => $s['needed']), 'action');
        $this->assertSame(['install VS Code watch task (.vscode/tasks.json — FileSaveTrigger adapter)'], $needed);

        // no VS Code → the step is not needed (never forced on a foreign environment)
        $this->assertSame([], array_filter(\KnowledgeOsInitPlanner::plan($this->initialized()), fn ($s) => $s['needed']));
    }
}
