<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\DemoCode;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\VoterSlug;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpiredCodeAutoRegenerationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Election $election;
    private VoterSlug $voterSlug;

    protected function setUp(): void
    {
        parent::setUp();

        // Create organisation and set session context
        $org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $org->id]);

        // Create user with organisation role
        $this->user = User::factory()->forOrganisation($org)->create();
        UserOrganisationRole::firstOrCreate(
            ['user_id' => $this->user->id, 'organisation_id' => $org->id],
            ['role' => 'voter']
        );

        // Create real election
        $this->election = Election::factory()->create([
            'organisation_id' => $org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);

        // Create election membership so user can vote
        ElectionMembership::create([
            'user_id'         => $this->user->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $org->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);

        // Create voter slug
        $this->voterSlug = VoterSlug::create([
            'user_id'         => $this->user->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $org->id,
            'slug'            => 'test-slug-' . now()->timestamp,
            'is_active'       => true,
            'status'          => 'active',
            'current_step'    => 1,
            'expires_at'      => Carbon::now()->addHours(2),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // TEST 1: Expired code (sent >30 min ago) IS regenerated
    // ──────────────────────────────────────────────────────────────────────────

    public function test_expired_code_is_automatically_regenerated(): void
    {
        $originalCode = 'OLDCODE1';
        $code = Code::factory()->create([
            'user_id'                            => $this->user->id,
            'election_id'                        => $this->election->id,
            'organisation_id'                    => $this->election->organisation_id,
            'code_to_open_voting_form'           => $originalCode,
            'code_to_open_voting_form_sent_at'   => now()->subMinutes(35), // expired
            'is_code_to_open_voting_form_usable' => 1,
        ]);

        $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $code->refresh();

        // CODE MUST BE REGENERATED
        $this->assertNotEquals($originalCode, $code->code_to_open_voting_form,
            'Expired code must be regenerated');
        // USABLE FLAG MUST RESET
        $this->assertEquals(1, $code->is_code_to_open_voting_form_usable);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // TEST 2: Fresh code (sent <30 min ago) is NOT regenerated
    // ──────────────────────────────────────────────────────────────────────────

    public function test_fresh_code_is_not_regenerated(): void
    {
        $originalCode = 'FRESHCODE1';
        $code = Code::factory()->create([
            'user_id'                            => $this->user->id,
            'election_id'                        => $this->election->id,
            'organisation_id'                    => $this->election->organisation_id,
            'code_to_open_voting_form'           => $originalCode,
            'code_to_open_voting_form_sent_at'   => now()->subMinutes(5), // fresh
            'is_code_to_open_voting_form_usable' => 1,
        ]);

        $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $code->refresh();

        // CODE MUST NOT CHANGE
        $this->assertEquals($originalCode, $code->code_to_open_voting_form,
            'Fresh code must not be regenerated');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // TEST 3: Expired verified code (can_vote_now=1) IS regenerated
    // ──────────────────────────────────────────────────────────────────────────

    public function test_expired_verified_code_is_regenerated(): void
    {
        $originalCode = 'VERIFIED-OLD';
        $code = Code::factory()->create([
            'user_id'                            => $this->user->id,
            'election_id'                        => $this->election->id,
            'organisation_id'                    => $this->election->organisation_id,
            'code_to_open_voting_form'           => $originalCode,
            'code_to_open_voting_form_sent_at'   => now()->subMinutes(35), // expired
            'can_vote_now'                       => 1, // verified
            'is_code_to_open_voting_form_usable' => 1,
        ]);

        $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $code->refresh();

        // EXPIRED VERIFIED CODE MUST BE REGENERATED
        $this->assertNotEquals($originalCode, $code->code_to_open_voting_form,
            'Expired verified code must be regenerated');
        // VERIFIED STATUS MUST REMAIN
        $this->assertEquals(1, $code->can_vote_now,
            'can_vote_now must remain true after regeneration');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // TEST 4: Fresh verified code (can_vote_now=1) is NOT regenerated
    // ──────────────────────────────────────────────────────────────────────────

    public function test_fresh_verified_code_is_not_regenerated(): void
    {
        $originalCode = 'VERIFIED-FRESH';
        $code = Code::factory()->create([
            'user_id'                            => $this->user->id,
            'election_id'                        => $this->election->id,
            'organisation_id'                    => $this->election->organisation_id,
            'code_to_open_voting_form'           => $originalCode,
            'code_to_open_voting_form_sent_at'   => now()->subMinutes(5), // fresh
            'can_vote_now'                       => 1, // verified
            'is_code_to_open_voting_form_usable' => 1,
        ]);

        $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $code->refresh();

        // FRESH VERIFIED CODE MUST NOT CHANGE
        $this->assertEquals($originalCode, $code->code_to_open_voting_form,
            'Fresh verified code must not be regenerated');
        $this->assertEquals(1, $code->can_vote_now);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // TEST 5: Regenerated code has new timestamp
    // ──────────────────────────────────────────────────────────────────────────

    public function test_regenerated_code_has_fresh_timestamp(): void
    {
        $oldTimestamp = now()->subMinutes(35);
        $code = Code::factory()->create([
            'user_id'                            => $this->user->id,
            'election_id'                        => $this->election->id,
            'organisation_id'                    => $this->election->organisation_id,
            'code_to_open_voting_form'           => 'OLDCODE',
            'code_to_open_voting_form_sent_at'   => $oldTimestamp,
            'is_code_to_open_voting_form_usable' => 1,
        ]);

        $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $code->refresh();

        // TIMESTAMP MUST UPDATE
        $this->assertGreaterThan($oldTimestamp, $code->code_to_open_voting_form_sent_at,
            'code_to_open_voting_form_sent_at must be updated');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // TEST 6: No error response when code expires & regenerates
    // ──────────────────────────────────────────────────────────────────────────

    public function test_expired_code_regeneration_returns_success(): void
    {
        $code = Code::factory()->create([
            'user_id'                            => $this->user->id,
            'election_id'                        => $this->election->id,
            'organisation_id'                    => $this->election->organisation_id,
            'code_to_open_voting_form_sent_at'   => now()->subMinutes(35),
            'is_code_to_open_voting_form_usable' => 1,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        // MUST NOT REDIRECT (no 302)
        $response->assertStatus(200);
        // MUST NOT HAVE ERRORS
        $response->assertSessionDoesntHaveErrors();
    }
}
