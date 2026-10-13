<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Admin recovery, per election, through VoterSlugIssuer (PBDIGIT-274 S4; PO decision 2026-10-13). Rewritten
 * from the old contract (no election, legacy user flags, step_meta, deactivating all links), which PBDIGIT-215
 * describes as defective and which could no longer pass (the legacy columns are gone).
 *
 * The same behaviour is tested on the isolated database in
 * tests/Isolated/Pbdigit274/Database/VoterSlugRecoveryPathsTest.php. This file runs in the default suite.
 */
class AdminRecoverySlugTest extends TestCase
{
    // No class-level `use RefreshDatabase;`: repeating it re-enables a wrapping test transaction, inside which
    // VoterSlugIssuer (deliberately) refuses to run. Tests\TestCase already resets the database (PBDIGIT-274).

    private Organisation $org;
    private Election $election;
    private User $admin;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();
        // Only committee.member is skipped (not the subject here). withoutMiddleware() with no argument also removed
        // SubstituteBindings: `User $user` then came from the container and the voter id landed in `$election`.
        $this->withoutMiddleware(\App\Http\Middleware\EnsureCommitteeMember::class);

        Election::resetPlatformOrgCache();
        $this->org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        $this->election = ElectionScenarioFactory::votingActive($this->org);
        $this->admin = User::factory()->create(['name' => 'Election Committee Member', 'email_verified_at' => now()]);
        $this->voter = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
        ElectionMembership::create([
            'user_id' => $this->voter->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active',
        ]);
    }

    private function recover(?string $electionId = null)
    {
        return $this->actingAs($this->admin)->post(
            route('admin.voting.security.recovery', ['user' => $this->voter->id, 'election' => $electionId ?? $this->election->id]),
            ['reason' => 'Voter link expired, requested assistance'],
        );
    }

    private function slugRow(array $attributes = []): string
    {
        $id = (string) Str::uuid();
        DB::table('voter_slugs')->insert($attributes + [
            'id' => $id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'slug' => Str::random(32), 'is_active' => true, 'has_voted' => false,
            'status' => 'active', 'current_step' => 2, 'created_at' => now(), 'updated_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);

        return $id;
    }

    private function currentSlug(): ?string
    {
        return DB::table('voter_slugs')->where('user_id', $this->voter->id)->where('election_id', $this->election->id)
            ->whereNull('deleted_at')->value('slug');
    }

    /** @test */
    public function an_expired_link_is_replaced_and_kept_as_history(): void
    {
        $old = $this->slugRow(['created_at' => now()->subMinutes(40), 'expires_at' => now()->subMinutes(10)]);

        $response = $this->recover();

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $new = $this->currentSlug();
        $this->assertNotNull($new);
        $response->assertSessionHas('recovery_url', route('slug.code.create', ['vslug' => $new]));
        $this->assertNotNull(DB::table('voter_slugs')->where('id', $old)->value('deleted_at'), 'old link kept as history');
        $this->assertSame(2, VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->count());
    }

    /** @test */
    public function a_valid_link_is_returned_unchanged(): void
    {
        $id = $this->slugRow();
        $slug = DB::table('voter_slugs')->where('id', $id)->value('slug');

        $response = $this->recover();

        $response->assertSessionHas('recovery_url', route('slug.code.create', ['vslug' => $slug]));
        $this->assertSame(1, VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->count());
    }

    /** @test */
    public function no_link_after_voting(): void
    {
        DB::table('codes')->insert([
            'id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'code1' => '123456', 'has_voted' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $response = $this->recover();

        $response->assertSessionHas('error');
        $this->assertStringContainsString('already voted', session('error'));
        $this->assertSame(0, VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->count());
    }

    /** @test */
    public function no_link_without_entitlement(): void
    {
        ElectionMembership::withoutGlobalScopes()->where('user_id', $this->voter->id)->delete();

        $response = $this->recover();

        $response->assertSessionHas('error');
        $this->assertStringContainsString('not entitled', session('error'));
        $this->assertSame(0, VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->count());
    }

    /** @test */
    public function an_unknown_election_is_refused(): void
    {
        $response = $this->recover((string) Str::uuid());

        $response->assertSessionHas('error');
        $this->assertSame(0, VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->count());
    }
}
