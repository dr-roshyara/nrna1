<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\DemoVoterSlugStep;
use App\Models\VoterSlugStep;
use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\CreatesApplication;

/**
 * Tenant isolation of voter-slug step records (VoterSlugStep, DemoVoterSlugStep via BelongsToTenant),
 * as far as it can be proved WITHOUT a database: the read scope is compiled (never executed) and the
 * `creating` hook is fired in memory. The `pgsql` connection is pointed at a non-existent socket directory,
 * so any accidental query fails instead of reaching a real database, and every test asserts that no query ran.
 *
 * The database-backed proof is tests/Isolated/Pbdigit274/Database/VoterSlugStepTenantIsolationTest.php
 * (runs only on the isolated test database, after G5 is authorised).
 */
final class VoterSlugStepTenantScopeTest extends BaseTestCase
{
    use CreatesApplication;

    private const ORG_A = 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa';
    private const ORG_B = 'bbbbbbbb-bbbb-4bbb-8bbb-bbbbbbbbbbbb';

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => '/nonexistent-tenant-scope-test-socket-dir',
            'database.connections.pgsql.url' => null,
        ]);
        DB::purge('pgsql');
        DB::listen(function () {
            $this->queries++;
        });
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    public static function stepModels(): array
    {
        return ['real' => [VoterSlugStep::class], 'demo' => [DemoVoterSlugStep::class]];
    }

    /** @param class-string<Model> $class */
    private function fireCreating(Model $model): void
    {
        $model->getEventDispatcher()->until('eloquent.creating: '.$model::class, $model);
    }

    #[DataProvider('stepModels')]
    public function test_reads_are_filtered_to_the_current_organisation(string $class): void
    {
        TenantContext::set(self::ORG_A);
        $query = $class::query()->where('step', 1);
        $table = (new $class())->getTable();

        $this->assertStringContainsString("\"{$table}\".\"organisation_id\" = ?", $query->toSql());
        $this->assertContains(self::ORG_A, $query->getBindings());
        $this->assertNotContains(self::ORG_B, $query->getBindings());
        $this->assertSame(0, $this->queries);
    }

    #[DataProvider('stepModels')]
    public function test_the_filter_follows_the_tenant_in_both_directions(string $class): void
    {
        TenantContext::set(self::ORG_A);
        $forA = $class::query()->getBindings();
        TenantContext::set(self::ORG_B);
        $forB = $class::query()->getBindings();

        $this->assertSame([self::ORG_A], $forA, 'organisation A only sees A');
        $this->assertSame([self::ORG_B], $forB, 'organisation B only sees B');
        $this->assertSame(0, $this->queries);
    }

    #[DataProvider('stepModels')]
    public function test_only_an_explicit_bypass_removes_the_filter(string $class): void
    {
        TenantContext::set(self::ORG_A);

        $this->assertStringNotContainsString('organisation_id', $class::query()->withoutGlobalScope('tenant')->toSql());
        $this->assertSame(0, $this->queries);
    }

    #[DataProvider('stepModels')]
    public function test_a_new_step_without_organisation_gets_the_current_tenant(string $class): void
    {
        TenantContext::set(self::ORG_A);
        $step = new $class(['step' => 2]);
        $this->fireCreating($step);

        $this->assertSame(self::ORG_A, $step->organisation_id);
        $this->assertSame(0, $this->queries);
    }

    /**
     * CHARACTERISATION (a finding, not a guarantee): the model does not reject an explicit organisation_id
     * that differs from the current tenant. VoterStepTrackingService copies organisation_id from the voter
     * slug, so cross-tenant write protection depends on the guards before it (VerifyVoterSlug ownership and
     * election/organisation checks), not on this model.
     */
    #[DataProvider('stepModels')]
    public function test_characterisation_an_explicit_foreign_organisation_is_kept(string $class): void
    {
        TenantContext::set(self::ORG_A);
        $step = new $class(['step' => 2, 'organisation_id' => self::ORG_B]);
        $this->fireCreating($step);

        $this->assertSame(self::ORG_B, $step->organisation_id);
        $this->assertSame(0, $this->queries);
    }
}
