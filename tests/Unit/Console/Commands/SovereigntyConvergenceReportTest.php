<?php

namespace Tests\Unit\Console\Commands;

use App\Infrastructure\Observation\DivergenceTelemetryStore;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * SovereigntyConvergenceReportTest
 *
 * D.0.2 — Verifies the convergence certification command produces correct
 * constitutional evidence without making sovereignty decisions.
 *
 * CONSTITUTIONAL INVARIANTS:
 * - Observational only: never determines retirement safety
 * - Lineage preserving: route, actor, evidence, timing, category
 * - No automated sovereignty decisions
 */
class SovereigntyConvergenceReportTest extends TestCase
{
    private DivergenceTelemetryStore $store;

    protected function setUp(): void
    {
        parent::setUp();
        $this->store = app(DivergenceTelemetryStore::class);
        $this->store->clear();
    }

    protected function tearDown(): void
    {
        $this->store->clear();
        parent::tearDown();
    }

    /** @test */
    public function it_reports_no_divergences_when_store_is_empty(): void
    {
        $this->artisan('sovereignty:convergence-report')
            ->expectsOutputToContain('No divergence observations found')
            ->expectsOutputToContain('human authorization required')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_reports_divergences_when_they_exist(): void
    {
        $this->store->record([
            'type' => 'sovereignty_leak',
            'source' => 'ValidateVotingIp',
            'phase' => 'D.0.1',
            'user_id' => 42,
            'registered_ip' => '192.168.1.100',
            'current_ip' => '10.0.0.50',
            'route' => 'slug.vote.create',
        ]);

        $exitCode = Artisan::call('sovereignty:convergence-report', [
            '--observations-path' => $this->store->path(),
        ]);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Total evaluations', $output);
        $this->assertStringContainsString('1', $output);
        $this->assertStringContainsString('slug.vote.create', $output);
        $this->assertStringContainsString('192.168.1.x', $output);
        $this->assertStringContainsString('human authorization required', $output);
    }

    /** @test */
    public function it_reports_convergence_when_no_leaks(): void
    {
        $this->store->record([
            'type' => 'matched',
            'source' => 'ValidateVotingIp',
            'phase' => 'D.0.1',
            'user_id' => 42,
        ]);

        $exitCode = Artisan::call('sovereignty:convergence-report', [
            '--observations-path' => $this->store->path(),
        ]);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('All observations converged', $output);
        $this->assertStringContainsString('human authorization required', $output);
    }

    /** @test */
    public function it_outputs_json_when_requested(): void
    {
        $this->store->record([
            'type' => 'sovereignty_leak',
            'source' => 'ValidateVotingIp',
            'phase' => 'D.0.1',
            'user_id' => 42,
            'registered_ip' => '192.168.1.100',
            'current_ip' => '10.0.0.50',
            'route' => 'slug.vote.create',
        ]);

        $exitCode = Artisan::call('sovereignty:convergence-report', [
            '--json' => true,
            '--observations-path' => $this->store->path(),
        ]);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('report_type', $output);
        $this->assertStringContainsString('D.0.2_sovereignty_convergence', $output);
        $this->assertStringContainsString('total_evaluations', $output);
        $this->assertStringContainsString('HUMAN_REVIEW_REQUIRED', $output);
    }

    /** @test */
    public function it_never_determines_retirement_safety_automatically(): void
    {
        $this->store->record([
            'type' => 'matched',
            'source' => 'ValidateVotingIp',
            'phase' => 'D.0.1',
            'user_id' => 1,
        ]);

        $exitCode = Artisan::call('sovereignty:convergence-report', [
            '--observations-path' => $this->store->path(),
        ]);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('All observations converged', $output);
        $this->assertStringContainsString('human authorization required', $output);
        $this->assertStringNotContainsString('retirement_safe', $output);
    }

    /** @test */
    public function it_masks_ip_addresses_for_display(): void
    {
        $this->store->record([
            'type' => 'sovereignty_leak',
            'source' => 'ValidateVotingIp',
            'phase' => 'D.0.1',
            'user_id' => 1,
            'registered_ip' => '192.168.1.100',
            'current_ip' => '10.0.0.50',
        ]);

        $exitCode = Artisan::call('sovereignty:convergence-report', [
            '--observations-path' => $this->store->path(),
        ]);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('192.168.1.x', $output);
        $this->assertStringContainsString('10.0.0.x', $output);
    }

    /** @test */
    public function it_respects_since_filter(): void
    {
        $this->store->record([
            'type' => 'sovereignty_leak',
            'source' => 'ValidateVotingIp',
            'phase' => 'D.0.1',
            'user_id' => 1,
        ]);

        $exitCode = Artisan::call('sovereignty:convergence-report', [
            '--since' => '2099-01-01T00:00:00+00:00',
            '--observations-path' => $this->store->path(),
        ]);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('No divergence observations match', $output);
    }
}
