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
}
