<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\DemoCode;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoterSlug;
use App\Models\VoterSlugStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionMethod;
use Tests\TestCase;

/**
 * VoteController TDD Test Suite
 *
 * Tests written FIRST (RED), then implementation (GREEN).
 * Tests are focused on real election security requirements.
 */
class VoteControllerTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        Election::resetPlatformOrgCache();
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function createEligibleVoter(): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        $this->org->users()->attach($user->id, [
            'id'   => (string) Str::uuid(),
            'role' => 'voter',
        ]);
        return $user;
    }

    private function createRealElection(): Election
    {
        return Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);
    }

    private function createVoterSlug(User $user, Election $election): VoterSlug
    {
        return VoterSlug::factory()->create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $election->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
            'current_step'    => 2,
            'step_meta'       => ['code_completed' => true, 'agreement_accepted' => true],
            'can_vote_now'    => true,
        ]);
    }

    private function createVerifiedCode(User $user, Election $election, array $overrides = []): Code
    {
        return Code::factory()->create(array_merge([
            'user_id'                        => $user->id,
            'election_id'                    => $election->id,
            'organisation_id'                => $this->org->id,
            'can_vote_now'                   => 1,
            'has_voted'                      => 0,
            'has_code1_sent'                 => 1,
            'is_code_to_open_voting_form_usable' => 0,
            'code_to_open_voting_form_used_at'   => now()->subMinutes(5),
            'voting_time_in_minutes'         => 30,
        ], $overrides));
    }

    private function invokePrivateMethod($object, string $method, array $args = [])
    {
        $reflection = new ReflectionMethod($object, $method);
        $reflection->setAccessible(true);
        return $reflection->invokeArgs($object, $args);
    }

    /** Create VoterSlugStep records so EnsureVoterStepOrder allows access to step 3 */
    private function completeSteps(VoterSlug $voterSlug, Election $election, int $upToStep = 2): void
    {
        for ($step = 1; $step <= $upToStep; $step++) {
            VoterSlugStep::create([
                'voter_slug_id' => $voterSlug->id,
                'election_id'   => $election->id,
                'step'          => $step,
            ]);
        }
    }

    // ── Test 1: create() blocks already-voted user ───────────────────────────

    /** @test */
    public function real_election_blocks_create_page_for_already_voted_user(): void
    {
        $user     = $this->createEligibleVoter();
        $election = $this->createRealElection();
        $voterSlug = $this->createVoterSlug($user, $election);
        $this->completeSteps($voterSlug, $election, 2);

        ElectionMembership::assignVoter($user->id, $election->id);

        Code::factory()->voted()->create([
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
            'has_voted'       => true,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.vote.create', ['vslug' => $voterSlug->slug]));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    // ── Test 2: first_submission() blocks already-voted user ─────────────────

    /** @test */
    public function real_election_blocks_first_submission_for_already_voted_user(): void
    {
        $user      = $this->createEligibleVoter();
        $election  = $this->createRealElection();
        $voterSlug = $this->createVoterSlug($user, $election);

        ElectionMembership::assignVoter($user->id, $election->id);

        Code::factory()->voted()->create([
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
            'has_voted'       => true,
            'can_vote_now'    => 1,
            'vote_submitted'  => 1,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('slug.vote.submit', ['vslug' => $voterSlug->slug]), [
                'user_id'                     => $user->id,
                'agree_button'                => 1,
                'national_selected_candidates' => [],
                'regional_selected_candidates' => [],
            ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHasErrors(['vote']);
    }

    // ── Test 3: first_submission() fetches code by election_id ──────────────

    /** @test */
    public function real_election_fetches_code_by_election_id_not_relationship(): void
    {
        $user      = $this->createEligibleVoter();
        $election1 = $this->createRealElection();
        $election2 = $this->createRealElection();
        $voterSlug = $this->createVoterSlug($user, $election1);

        ElectionMembership::assignVoter($user->id, $election1->id);
        ElectionMembership::assignVoter($user->id, $election2->id);

        // Code exists ONLY for election2 — election1 has none
        Code::factory()->create([
            'user_id'         => $user->id,
            'election_id'     => $election2->id,
            'organisation_id' => $this->org->id,
            'can_vote_now'    => 1,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('slug.vote.submit', ['vslug' => $voterSlug->slug]), [
                'user_id'                     => $user->id,
                'agree_button'                => 1,
                'national_selected_candidates' => [],
                'regional_selected_candidates' => [],
            ]);

        // Must fail because no code for election1
        $response->assertSessionHasErrors();
    }

    // ── Test 4: vote_pre_check() SIMPLE mode ────────────────────────────────

    /** @test */
    public function vote_pre_check_handles_simple_mode_correctly(): void
    {
        config(['voting.two_codes_system' => 0]); // SIMPLE MODE

        $user     = $this->createEligibleVoter();
        $election = $this->createRealElection();

        $code = $this->createVerifiedCode($user, $election, [
            'code_to_open_voting_form_used_at' => null, // Code1 not yet used
            'is_code_to_open_voting_form_usable' => 1,
        ]);

        $controller = new \App\Http\Controllers\VoteController();
        $result = $this->invokePrivateMethod($controller, 'vote_pre_check', [&$code]);

        $this->assertEquals('code.create', $result);
    }

    // ── Test 5: vote_pre_check() STRICT mode ────────────────────────────────

    /** @test */
    public function vote_pre_check_handles_strict_mode_correctly(): void
    {
        config(['voting.two_codes_system' => 1]); // STRICT MODE

        $user     = $this->createEligibleVoter();
        $election = $this->createRealElection();

        $code = $this->createVerifiedCode($user, $election, [
            'code_to_open_voting_form_used_at' => now()->subMinutes(5),
            'code_to_save_vote_used_at'        => null,
            'is_code_to_save_vote_usable'      => 1,
        ]);

        $controller = new \App\Http\Controllers\VoteController();
        $result = $this->invokePrivateMethod($controller, 'vote_pre_check', [&$code]);

        $this->assertEquals('', $result); // All checks pass — no redirect
    }

    // ── Test 6: second_code_check() mode-specific ────────────────────────────

    /** @test */
    public function second_code_check_strict_mode_requires_code2_used(): void
    {
        config(['voting.two_codes_system' => 1]); // STRICT MODE

        $user     = $this->createEligibleVoter();
        $election = $this->createRealElection();

        $code = $this->createVerifiedCode($user, $election, [
            'code_to_open_voting_form_used_at' => now()->subMinutes(5),
            'code_to_save_vote_used_at'        => null, // Code2 NOT used
            'vote_submitted'                   => 1,
            'voting_time_in_minutes'           => 30,
        ]);

        $controller = new \App\Http\Controllers\VoteController();
        $result = $this->invokePrivateMethod($controller, 'second_code_check', [&$code]);

        $this->assertArrayHasKey('return_to', $result);
        $this->assertEquals('vote.create', $result['return_to']);
    }

    // ── Test 7: verify_first_submission() routes to correct page ─────────────

    /** @test */
    public function verify_first_submission_routes_to_real_verify_page(): void
    {
        $user      = $this->createEligibleVoter();
        $election  = $this->createRealElection();
        $voterSlug = $this->createVoterSlug($user, $election);

        ElectionMembership::assignVoter($user->id, $election->id);

        $code = $this->createVerifiedCode($user, $election, [
            'session_name' => 'vote_data_' . $user->id,
        ]);

        session([$code->session_name => [
            'user_id' => $user->id,
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [],
        ]]);

        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('slug.vote.submit', ['vslug' => $voterSlug->slug]), [
                'user_id'                     => $user->id,
                'agree_button'                => 1,
                'national_selected_candidates' => [['post_id' => Str::uuid()->toString(), 'post_name' => 'President', 'no_vote' => true, 'candidates' => [], 'required_number' => 1]],
                'regional_selected_candidates' => [],
            ]);

        // Should redirect to verify page (not blocked)
        $response->assertRedirect();
        $targetUrl = $response->headers->get('Location');
        $this->assertStringContainsString('verify', $targetUrl);
    }

    // ── Test 8: save_vote() generates receipt_hash correctly ─────────────────

    /** @test */
    public function save_vote_generates_verifiable_receipt_hash(): void
    {
        $user     = $this->createEligibleVoter();
        $election = $this->createRealElection();

        $code = $this->createVerifiedCode($user, $election);

        $vote_data = [
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [],
            'no_vote_posts'                => [],
        ];

        $private_key = bin2hex(random_bytes(16));
        $hashed_key  = password_hash($private_key, PASSWORD_BCRYPT);

        session(['current_organisation_id' => $election->organisation_id]);

        $controller = new \App\Http\Controllers\VoteController();
        $this->invokePrivateMethod($controller, 'save_vote', [
            $vote_data, $hashed_key, $election, $user, $private_key,
        ]);

        $vote = Vote::withoutGlobalScopes()->latest()->first();

        $this->assertNotNull($vote, 'Vote record must be created');
        $this->assertNotNull($vote->receipt_hash, 'receipt_hash must be generated');
        $this->assertEquals(64, strlen($vote->receipt_hash), 'SHA256 = 64 hex chars');
    }

    // ── Test 9: vote_post_check() IP rate limiting scoped to election ─────────

    /** @test */
    public function vote_post_check_ip_rate_limiting_is_scoped_to_election(): void
    {
        config(['app.max_use_clientIP' => 3]);

        $user1     = $this->createEligibleVoter();
        $election1 = $this->createRealElection();
        $election2 = $this->createRealElection();
        $sameIp    = '192.168.1.100';

        ElectionMembership::assignVoter($user1->id, $election1->id);

        // Fill up election2's IP quota (3 voted codes)
        for ($i = 0; $i < 3; $i++) {
            $voter = $this->createEligibleVoter();
            DB::table('codes')->insert([
                'id'              => (string) Str::uuid(),
                'organisation_id' => $this->org->id,
                'user_id'         => $voter->id,
                'election_id'     => $election2->id,
                'client_ip'       => $sameIp,
                'has_voted'       => 1,
                'can_vote_now'    => 0,
                'is_code_to_save_vote_usable' => 0,
                'code1'           => strtoupper(Str::random(8)),
                'code2'           => strtoupper(Str::random(8)),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // User1 on election1 with same IP — should NOT be blocked
        $code = Code::factory()->create([
            'user_id'         => $user1->id,
            'election_id'     => $election1->id,
            'organisation_id' => $this->org->id,
            'client_ip'       => $sameIp,
            'has_voted'       => false,
            'can_vote_now'    => 1,
        ]);

        $vote_data = ['national_selected_candidates' => [], 'regional_selected_candidates' => []];

        $controller = new \App\Http\Controllers\VoteController();
        $result = $this->invokePrivateMethod($controller, 'vote_post_check', [
            $user1, &$code, $vote_data,
        ]);

        $this->assertEmpty($result['error_message'], 'IP limit on election2 must not affect election1');
    }

    // ── Test 10: getElection() validates organisation mismatch ───────────────

    /** @test */
    public function get_election_throws_on_organisation_mismatch(): void
    {
        $user  = $this->createEligibleVoter();
        $org2  = Organisation::factory()->create(['type' => 'tenant']);

        $election = Election::factory()->create([
            'type'            => 'real',
            'organisation_id' => $this->org->id,
        ]);

        // VoterSlug belongs to org2 but election belongs to org1 — mismatch
        $mismatchSlug = VoterSlug::factory()->create([
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $org2->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);

        $this->actingAs($user)
            ->withSession(['current_organisation_id' => $this->org->id]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Organisation mismatch detected');

        $controller = new \App\Http\Controllers\VoteController();
        $request    = \Illuminate\Http\Request::create('/test', 'GET');
        $request->attributes->set('election', $election);
        $request->attributes->set('voter_slug', $mismatchSlug);

        $this->invokePrivateMethod($controller, 'getElection', [$request]);
    }

    // ── Test: create() exposes election settings in props ──────────────────────

    /** @test */
    public function create_exposes_election_settings_in_props(): void
    {
        $user      = $this->createEligibleVoter();
        $election  = $this->createRealElection();

        // Configure election settings
        $election->update([
            'no_vote_option_enabled'   => true,
            'no_vote_option_label'     => 'Abstain',
            'selection_constraint_type' => 'exact',
            'selection_constraint_min'  => 1,
            'selection_constraint_max'  => 2,
        ]);

        $voterSlug = $this->createVoterSlug($user, $election);
        $this->completeSteps($voterSlug, $election, 2);
        ElectionMembership::assignVoter($user->id, $election->id);

        // Create a valid code so create() doesn't redirect
        $this->createVerifiedCode($user, $election, [
            'can_vote_now'       => 1,
            'has_voted'          => 0,
            'has_agreed_to_vote' => 1,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.vote.create', ['vslug' => $voterSlug->slug]));

        // Assert response is successful (Inertia renders as 'app' view)
        $response->assertStatus(200);

        // Inertia returns HTML with embedded JSON props
        // The props are serialized into the HTML response
        $content = $response->getContent();

        // Assert the most basic settings are present
        $this->assertStringContainsString('no_vote_option_enabled', $content, 'no_vote_option_enabled should be in response');
        $this->assertStringContainsString('selection_constraint_type', $content, 'selection_constraint_type should be in response');
    }

    // ── Test: first_submission() validates per-election settings ────────────────

    /** @test */
    public function first_submission_rejects_no_vote_when_disabled(): void
    {
        $user      = $this->createEligibleVoter();
        $election  = $this->createRealElection();

        // Configure election to DISABLE no-vote option
        $election->update([
            'no_vote_option_enabled'   => false,
            'selection_constraint_type' => 'exact',
            'selection_constraint_max'  => 2,
        ]);

        $voterSlug = $this->createVoterSlug($user, $election);
        $this->completeSteps($voterSlug, $election, 2);
        ElectionMembership::assignVoter($user->id, $election->id);

        // Create valid code
        $code = $this->createVerifiedCode($user, $election, [
            'can_vote_now'       => 1,
            'has_agreed_to_vote' => 1,
        ]);

        // Try to submit with no_vote=true when disabled
        // This should be rejected and redirect back with errors
        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('slug.vote.submit', ['vslug' => $voterSlug->slug]), [
                'agree_button'                 => 1,
                'national_selected_candidates' => [
                    ['no_vote' => true, 'post_name' => 'President']  // no_vote inside selection
                ],
                'regional_selected_candidates' => [],
            ]);

        // Inertia pattern: 302 redirect with session errors (not 422)
        $response->assertStatus(302);
        $response->assertSessionHasErrors('no_vote');
    }

    /** @test */
    public function first_submission_enforces_exact_constraint(): void
    {
        $user      = $this->createEligibleVoter();
        $election  = $this->createRealElection();

        // Configure EXACT constraint: must select exactly 2 candidates per post
        $election->update([
            'selection_constraint_type' => 'exact',
            'selection_constraint_max'  => 2,
            'no_vote_option_enabled'   => false,
        ]);

        $voterSlug = $this->createVoterSlug($user, $election);
        $this->completeSteps($voterSlug, $election, 2);
        ElectionMembership::assignVoter($user->id, $election->id);

        $code = $this->createVerifiedCode($user, $election, [
            'can_vote_now'       => 1,
            'has_agreed_to_vote' => 1,
        ]);

        // Try to submit with 1 candidate (should require exactly 2)
        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('slug.vote.submit', ['vslug' => $voterSlug->slug]), [
                'agree_button'                 => 1,
                'national_selected_candidates' => [
                    0 => [
                        'candidates' => ['cand-1'],  // Only 1, but exact=2 required
                        'no_vote' => false,
                        'post_name' => 'Test Post',
                        'required_number' => 2,
                    ]
                ],
                'regional_selected_candidates' => [],
            ]);

        // Should fail with redirect and validation errors (Inertia pattern)
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
