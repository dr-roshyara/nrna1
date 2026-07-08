<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Election;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Infrastructure\Acl\LegacyElectionExistenceAdapter;
use App\Models\Organisation;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PB-004 Step 4A.3 (RED) — the Anti-Corruption Layer that answers "does this election
 * exist?" from the CURRENT operational source of truth (the legacy `elections` table),
 * behind the Election-owned `ElectionExistencePort`.
 *
 * Invariants exercised: read-only · tenant-scoped (cross-org ⇒ not found) · soft-deleted
 * ⇒ not found · identity-only (the query touches `elections` — which carries no
 * vote/voter data — never `votes`/`results`, so anonymity is structural, ADR-T11).
 *
 * We assert externally-visible behaviour only (same org ⇒ found, different org ⇒ not
 * found); we do NOT test how TenantContext resolves the ambient organisation.
 */
final class LegacyElectionExistenceAdapterTest extends TestCase
{
    private string $orgId;

    protected function setUp(): void
    {
        parent::setUp();
        // Unique slug per test: this pgsql harness disables per-test transaction rollback
        // (Tests\TestCase::beginDatabaseTransaction is a no-op), so rows persist across
        // methods within a run. A fresh organisation per test isolates via tenant scope.
        $this->orgId = (string) $this->tenantOrg('election-acl');
        TenantContext::set($this->orgId);
    }

    private function tenantOrg(string $prefix): string
    {
        return (string) Organisation::create([
            'name' => $prefix.' org',
            'slug' => $prefix.'-'.Str::uuid(),
            'type' => 'tenant',
            'is_default' => false,
        ])->id;
    }

    private function seedLegacyElection(string $organisationId, ?string $deletedAt = null): string
    {
        $id = (string) Str::uuid();
        DB::table('elections')->insert([
            'id' => $id,
            'organisation_id' => $organisationId,
            'name' => 'Legacy Election '.$id,
            'slug' => 'legacy-election-'.$id,
            'type' => 'real',
            'status' => 'completed',
            'is_active' => true,
            'created_at' => '2026-07-08 09:00:00',
            'updated_at' => '2026-07-08 09:00:00',
            'deleted_at' => $deletedAt,
        ]);

        return $id;
    }

    public function test_existing_election_in_ambient_org_exists(): void
    {
        $id = $this->seedLegacyElection($this->orgId);

        $this->assertTrue((new LegacyElectionExistenceAdapter())->exists(ElectionId::fromString($id)));
    }

    public function test_absent_election_does_not_exist(): void
    {
        $this->assertFalse(
            (new LegacyElectionExistenceAdapter())->exists(ElectionId::fromString((string) Str::uuid())),
        );
    }

    public function test_election_in_another_organisation_is_not_found(): void
    {
        $id = $this->seedLegacyElection($this->tenantOrg('other-acl-org'));

        // Ambient tenant is still $this->orgId — the other org's election must be invisible.
        $this->assertFalse((new LegacyElectionExistenceAdapter())->exists(ElectionId::fromString($id)));
    }

    public function test_soft_deleted_election_is_not_found(): void
    {
        $id = $this->seedLegacyElection($this->orgId, deletedAt: '2026-07-08 10:00:00');

        $this->assertFalse((new LegacyElectionExistenceAdapter())->exists(ElectionId::fromString($id)));
    }
}
