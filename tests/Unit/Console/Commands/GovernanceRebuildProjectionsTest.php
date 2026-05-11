<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\GovernanceRebuildProjections;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class GovernanceRebuildProjectionsTest extends TestCase
{
    public function test_has_correct_signature(): void
    {
        $reflection = new ReflectionClass(GovernanceRebuildProjections::class);
        $signature = $reflection->getProperty('signature')->getDefaultValue();

        $this->assertStringContainsString('governance:rebuild-projections', $signature);
    }

    public function test_accepts_tenant_option(): void
    {
        $reflection = new ReflectionClass(GovernanceRebuildProjections::class);
        $signature = $reflection->getProperty('signature')->getDefaultValue();

        $this->assertStringContainsString('--tenant', $signature);
    }

    public function test_accepts_force_option(): void
    {
        $reflection = new ReflectionClass(GovernanceRebuildProjections::class);
        $signature = $reflection->getProperty('signature')->getDefaultValue();

        $this->assertStringContainsString('--force', $signature);
    }

    public function test_accepts_dry_run_option(): void
    {
        $reflection = new ReflectionClass(GovernanceRebuildProjections::class);
        $signature = $reflection->getProperty('signature')->getDefaultValue();

        $this->assertStringContainsString('--dry-run', $signature);
    }
}
