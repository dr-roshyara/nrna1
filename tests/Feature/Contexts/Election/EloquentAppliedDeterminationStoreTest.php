<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Election;

use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Infrastructure\Persistence\EloquentAppliedDeterminationStore;
use App\Models\Organisation;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PB-004 Step 4A.3 (RED) — the greenfield reaction-state store: the append-only
 * idempotency ledger of which determinations have already corrected which election.
 * This is the second, greenfield-owned persistence source of the Aggregate
 * Reconstruction Invariant (existence is the other, sourced from legacy).
 *
 * Behaviour asserted: empty by default · remember→reload round-trip · idempotent
 * (recording the same determination twice keeps a single row) · tenant-scoped
 * (another organisation sees nothing). Externally-visible tenant behaviour only.
 */
final class EloquentAppliedDeterminationStoreTest extends TestCase
{
    private string $orgId;

    protected function setUp(): void
    {
        parent::setUp();
        // Unique org per test (pgsql harness has no per-test rollback; isolate via tenant).
        $this->orgId = $this->tenantOrg('election-store');
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

    private function store(): EloquentAppliedDeterminationStore
    {
        return new EloquentAppliedDeterminationStore();
    }

    private function idsOf(ElectionId $election): array
    {
        return array_map(
            static fn (DeterminationId $d): string => $d->toString(),
            $this->store()->appliedDeterminations($election),
        );
    }

    public function test_empty_when_nothing_recorded(): void
    {
        $this->assertSame([], $this->idsOf(ElectionId::fromString('election-1')));
    }

    public function test_remember_then_reload(): void
    {
        $election = ElectionId::fromString('election-1');
        $this->store()->remember($election, DeterminationId::fromString('det-1'), DeterminationId::fromString('det-2'));

        $ids = $this->idsOf($election);
        sort($ids);
        $this->assertSame(['det-1', 'det-2'], $ids);
    }

    public function test_recording_the_same_determination_twice_keeps_a_single_row(): void
    {
        $election = ElectionId::fromString('election-1');
        $this->store()->remember($election, DeterminationId::fromString('det-1'));
        $this->store()->remember($election, DeterminationId::fromString('det-1'));

        // Tenant + election-scoped row count (global assertDatabaseCount is unsafe: this
        // pgsql harness accumulates rows across test methods).
        $rows = DB::table('election_applied_determinations')
            ->where('organisation_id', $this->orgId)
            ->where('election_id', 'election-1')
            ->count();
        $this->assertSame(1, $rows, 'recording the same determination twice keeps a single row');
        $this->assertSame(['det-1'], $this->idsOf($election));
    }

    public function test_reaction_state_is_scoped_to_the_ambient_organisation(): void
    {
        $election = ElectionId::fromString('election-1');
        $this->store()->remember($election, DeterminationId::fromString('det-1'));

        $otherOrg = Organisation::create([
            'name' => 'Other Store Org',
            'slug' => 'other-store-org',
            'type' => 'tenant',
            'is_default' => false,
        ]);
        TenantContext::set((string) $otherOrg->id);
        $this->assertSame([], $this->idsOf($election), 'another organisation sees no reaction state');

        TenantContext::set($this->orgId);
        $this->assertSame(['det-1'], $this->idsOf($election), 'the owning organisation still sees its own');
    }
}
