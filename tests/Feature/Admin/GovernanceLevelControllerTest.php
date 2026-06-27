<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\GovernanceLevelDefinition;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for GovernanceLevelController.
 *
 * Tenant isolation guarantee: every read/write path uses
 *   ->where('tenant_id', $tenantId)->...->firstOrFail()
 * before acting on a record. Tests below verify this contract.
 *
 * API routes are registered under:
 *   /organisations/{slug}/governance/levels/api[/{id}]
 * The {slug} segment is resolved by ensure.organisation middleware, which sets
 * $request->attributes->get('organisation') for the controller.
 *
 * TODO(frontend-regression): Add Vitest component test asserting
 *   showToast() receives the translated string (not a raw i18n key)
 *   to prevent the original regression from re-entering undetected.
 */
final class GovernanceLevelControllerTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $user;

    /** Auto-incrementing counter reset per test to keep fixtures unique. */
    private int $n = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->n    = 0;
        $this->org  = Organisation::factory()->create(['type' => 'tenant']);
        $this->user = User::factory()->forOrganisation($this->org)->create();

        session(['current_organisation_id' => $this->org->id]);
    }

    // ──────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────

    /**
     * Builds the API base URL for the current test's primary organisation.
     * Named routes do not exist for the API sub-group, so we encapsulate the
     * URL contract here rather than repeat magic strings across tests.
     */
    private function apiBase(): string
    {
        return "/organisations/{$this->org->slug}/governance/levels/api";
    }

    /**
     * Canonical fixture factory. ALL tests must use this method — no raw
     * GovernanceLevelDefinition::create() calls allowed outside this helper.
     *
     * Auto-increments unique fields (level, committee_code, geo_code) so
     * callers only need to specify the fields relevant to their assertion.
     */
    private function createLevelForOrg(Organisation $org, array $overrides = []): GovernanceLevelDefinition
    {
        ++$this->n;

        return GovernanceLevelDefinition::create(array_merge([
            'tenant_id'      => $org->id,
            'level'          => $this->n,
            'committee_name' => "Committee {$this->n}",
            'committee_code' => "C{$this->n}",
            'geo_name'       => "Geo {$this->n}",
            'geo_code'       => "G{$this->n}",
            'is_active'      => true,
            'sort_order'     => $this->n,
            'created_by'     => (string) $this->user->id,
        ], $overrides));
    }

    // ══════════════════════════════════════════════════════════════
    // Authentication
    // ══════════════════════════════════════════════════════════════

    #[\PHPUnit\Framework\Attributes\Test]
    public function guest_cannot_access_api_endpoints(): void
    {
        $level = $this->createLevelForOrg($this->org);

        // All four methods must return HTTP 401 (Unauthorized) for unauthenticated
        // JSON requests — the auth middleware issues 401, not a redirect, when
        // the request signals JSON via Accept header.
        $this->getJson($this->apiBase())->assertUnauthorized();
        $this->postJson($this->apiBase(), [])->assertUnauthorized();
        $this->putJson("{$this->apiBase()}/{$level->id}", [])->assertUnauthorized();
        $this->deleteJson("{$this->apiBase()}/{$level->id}")->assertUnauthorized();
    }

    // ══════════════════════════════════════════════════════════════
    // Index
    // ══════════════════════════════════════════════════════════════

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_returns_own_tenant_levels_ordered_by_level_ascending(): void
    {
        $second = $this->createLevelForOrg($this->org, ['level' => 2]);
        $first  = $this->createLevelForOrg($this->org, ['level' => 1]);

        $response = $this->actingAs($this->user)->getJson($this->apiBase());

        $response->assertOk();
        $response->assertJsonCount(2);
        // Explicit ordering contract — not just an ID check
        $response->assertJsonPath('0.id', $first->id);
        $response->assertJsonPath('1.id', $second->id);
        $response->assertJsonPath('0.level', 1);
        $response->assertJsonPath('1.level', 2);
    }

    // NOTE: sort_order secondary ordering cannot be tested directly because
    // the DB enforces UNIQUE(tenant_id, level) — two records with the same
    // level for the same tenant are impossible. The controller's orderBy('sort_order')
    // is a future-proofing clause (e.g., if the constraint is relaxed to allow
    // sub-levels). Ordering by level is fully covered in the test above.

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_does_not_return_other_tenant_levels(): void
    {
        $otherOrg  = Organisation::factory()->create(['type' => 'tenant']);
        $this->createLevelForOrg($otherOrg);   // must never appear in response
        $mine = $this->createLevelForOrg($this->org);

        $response = $this->actingAs($this->user)->getJson($this->apiBase());

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.id', $mine->id);
        $response->assertJsonPath('0.tenant_id', $this->org->id);
    }

    // ══════════════════════════════════════════════════════════════
    // Store
    // ══════════════════════════════════════════════════════════════

    #[\PHPUnit\Framework\Attributes\Test]
    public function store_creates_level_scoped_to_current_tenant_and_returns_201(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson($this->apiBase(), [
                'level'          => 0,
                'committee_name' => 'National Committee',
                'committee_code' => 'NAT',
                'geo_name'       => 'Nepal',
                'geo_code'       => 'NP',
                'is_active'      => true,
                'sort_order'     => 0,
            ]);

        $response->assertCreated();
        $response->assertJsonFragment(['committee_name' => 'National Committee', 'level' => 0]);

        $this->assertDatabaseHas('governance_level_definitions', [
            'tenant_id'      => $this->org->id,
            'committee_name' => 'National Committee',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function store_returns_422_when_required_fields_are_missing(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson($this->apiBase(), []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['level', 'committee_name', 'committee_code', 'geo_name', 'geo_code']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function store_returns_422_for_duplicate_level_number_within_same_tenant(): void
    {
        $this->createLevelForOrg($this->org, ['level' => 1]);

        $response = $this->actingAs($this->user)
            ->postJson($this->apiBase(), [
                'level'          => 1,
                'committee_name' => 'Duplicate',
                'committee_code' => 'DUP',
                'geo_name'       => 'Geo',
                'geo_code'       => 'GGEO',
                'is_active'      => true,
                'sort_order'     => 0,
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['level']);

        // Confirm no duplicate record was written despite the 422
        $this->assertSame(
            1,
            GovernanceLevelDefinition::where('tenant_id', $this->org->id)->count()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function store_allows_same_level_number_for_different_tenants(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $this->createLevelForOrg($otherOrg, ['level' => 1]);

        $response = $this->actingAs($this->user)
            ->postJson($this->apiBase(), [
                'level'          => 1,
                'committee_name' => 'My Committee',
                'committee_code' => 'MYC',
                'geo_name'       => 'My Geo',
                'geo_code'       => 'MGEO',
                'is_active'      => true,
                'sort_order'     => 0,
            ]);

        $response->assertCreated();
    }

    // ══════════════════════════════════════════════════════════════
    // Update
    // ══════════════════════════════════════════════════════════════

    #[\PHPUnit\Framework\Attributes\Test]
    public function update_modifies_level_and_returns_fresh_record(): void
    {
        $level = $this->createLevelForOrg($this->org);

        $response = $this->actingAs($this->user)
            ->putJson("{$this->apiBase()}/{$level->id}", [
                'committee_name' => 'Updated Name',
                'committee_code' => $level->committee_code,
                'geo_name'       => $level->geo_name,
                'geo_code'       => $level->geo_code,
                'is_active'      => false,
                'sort_order'     => 99,
            ]);

        $response->assertOk();
        $response->assertJsonFragment(['committee_name' => 'Updated Name', 'is_active' => false, 'sort_order' => 99]);

        $this->assertDatabaseHas('governance_level_definitions', [
            'id'             => $level->id,
            'committee_name' => 'Updated Name',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function update_returns_422_when_required_fields_are_empty(): void
    {
        $level = $this->createLevelForOrg($this->org);

        $response = $this->actingAs($this->user)
            ->putJson("{$this->apiBase()}/{$level->id}", [
                'committee_name' => '',
                'committee_code' => '',
                'geo_name'       => '',
                'geo_code'       => '',
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['committee_name', 'committee_code', 'geo_name', 'geo_code']);

        // Confirm original record is intact
        $this->assertDatabaseHas('governance_level_definitions', [
            'id'             => $level->id,
            'committee_name' => $level->committee_name,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function update_level_number_is_immutable_and_ignored_in_payload(): void
    {
        // Design decision: update() does not accept a 'level' field.
        // Sending one must be silently ignored — the level number must not change.
        $levelA = $this->createLevelForOrg($this->org, ['level' => 1]);
        $levelB = $this->createLevelForOrg($this->org, ['level' => 2]);

        $response = $this->actingAs($this->user)
            ->putJson("{$this->apiBase()}/{$levelA->id}", [
                'level'          => 2,   // attempt to change to levelB's number
                'committee_name' => $levelA->committee_name,
                'committee_code' => $levelA->committee_code,
                'geo_name'       => $levelA->geo_name,
                'geo_code'       => $levelA->geo_code,
                'is_active'      => true,
                'sort_order'     => 0,
            ]);

        $response->assertOk();
        // Level must remain 1, not 2
        $this->assertDatabaseHas('governance_level_definitions', ['id' => $levelA->id, 'level' => 1]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function update_returns_404_for_another_tenants_level_and_leaves_record_unchanged(): void
    {
        $otherOrg   = Organisation::factory()->create(['type' => 'tenant']);
        $otherLevel = $this->createLevelForOrg($otherOrg, [
            'committee_name' => 'Original Name',
            'committee_code' => 'OTH',
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("{$this->apiBase()}/{$otherLevel->id}", [
                'committee_name' => 'Hijacked Name',
                'committee_code' => 'HIJ',
                'geo_name'       => 'Hijack Geo',
                'geo_code'       => 'HG1',
                'is_active'      => true,
                'sort_order'     => 0,
            ]);

        $response->assertNotFound();

        // Positive: original values survive
        $this->assertDatabaseHas('governance_level_definitions', [
            'id'             => $otherLevel->id,
            'committee_name' => 'Original Name',
        ]);

        // Negative: attempted payload values must not exist anywhere
        $this->assertDatabaseMissing('governance_level_definitions', [
            'committee_name' => 'Hijacked Name',
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    // Destroy
    // ══════════════════════════════════════════════════════════════

    #[\PHPUnit\Framework\Attributes\Test]
    public function destroy_deletes_level_and_returns_204(): void
    {
        $level = $this->createLevelForOrg($this->org);

        $response = $this->actingAs($this->user)
            ->deleteJson("{$this->apiBase()}/{$level->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('governance_level_definitions', ['id' => $level->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function destroy_returns_404_for_another_tenants_level_and_leaves_record_intact(): void
    {
        $otherOrg   = Organisation::factory()->create(['type' => 'tenant']);
        $otherLevel = $this->createLevelForOrg($otherOrg, ['committee_name' => 'Must Survive']);

        $response = $this->actingAs($this->user)
            ->deleteJson("{$this->apiBase()}/{$otherLevel->id}");

        $response->assertNotFound();

        // Positive: record must still exist with original data
        $this->assertDatabaseHas('governance_level_definitions', [
            'id'             => $otherLevel->id,
            'committee_name' => 'Must Survive',
        ]);
    }
}
