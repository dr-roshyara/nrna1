<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\Organisation;
use App\Models\ElectionMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteControllerPostgresCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    // T1: is_code_to_open_voting_form_usable is cast to boolean (WILL FAIL before fix)
    public function test_is_code_to_open_voting_form_usable_is_cast_to_boolean(): void
    {
        $code = Code::factory()->create(['is_code_to_open_voting_form_usable' => 1]);
        $fresh = Code::withoutGlobalScopes()->find($code->id);
        $this->assertNotNull($fresh, 'Code should be created');
        $this->assertIsBool($fresh->is_code_to_open_voting_form_usable);
        $this->assertTrue($fresh->is_code_to_open_voting_form_usable);
    }

    // T2: platform_organisation_id can be determined (from config or fallback)
    public function test_platform_organisation_id_can_be_determined(): void
    {
        // Ensure public-digit org exists for fallback
        Organisation::factory()->create(['slug' => 'public-digit']);

        // Either config is set OR we can fall back to slug lookup
        $platformOrgId = config('app.platform_organisation_id')
            ?? Organisation::where('slug', 'public-digit')->value('id');
        $this->assertNotNull($platformOrgId, 'Platform organisation ID must be determinable');
    }

    // T3: can_vote_now DB query with boolean true finds the code
    public function test_can_vote_now_db_query_uses_boolean(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $org->id]);
        $user = User::factory()->forOrganisation($org)->create();
        $election = Election::factory()->create(['organisation_id' => $org->id]);
        $code = Code::factory()->create([
            'user_id'     => $user->id,
            'election_id' => $election->id,
            'can_vote_now' => true,
        ]);
        // Query with withoutGlobalScopes to bypass tenant filtering in test
        $found = Code::withoutGlobalScopes()->where('can_vote_now', true)->where('id', $code->id)->first();
        $this->assertNotNull($found);
    }

    // T4: rate limiting — 11th submission gets 429
    public function test_vote_submission_rate_limited_to_10_per_minute(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $org->id]);
        $user = User::factory()->forOrganisation($org)->create();
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);
        ElectionMembership::create([
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $org->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);
        $voterSlug = VoterSlug::create([
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $org->id,
            'slug'            => 'rate-limit-test-slug',
            'is_active'       => true,
            'status'          => 'active',
            'current_step'    => 3,
            'expires_at'      => now()->addHour(),
        ]);
        // Create a verified code so voter can submit votes
        Code::factory()->create([
            'user_id'     => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'can_vote_now' => true,
        ]);

        $this->actingAs($user);
        $lastResponse = null;
        for ($i = 0; $i < 11; $i++) {
            $lastResponse = $this->post(
                route('slug.vote.submit', ['vslug' => $voterSlug->slug]),
                []
            );
        }
        $lastResponse->assertStatus(429);
    }
}
