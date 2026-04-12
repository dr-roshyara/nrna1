<?php

namespace Tests\Feature;

use App\Http\Controllers\CodeController;
use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\VoterSlug;
use App\Models\VoterSlugStep;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use ReflectionMethod;
use Tests\TestCase;

class CodeControllerTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $user;
    private Election $election;
    private VoterSlug $voterSlug;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);

        // Set organisation session context so BelongsToTenant scope works correctly
        session(['current_organisation_id' => $this->org->id]);

        $this->user = User::factory()->forOrganisation($this->org)->create();

        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);

        $this->voterSlug = VoterSlug::create([
            'user_id'         => $this->user->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'slug'            => 'tst' . Str::random(30),
            'expires_at'      => Carbon::now()->addHours(2),
            'is_active'       => true,
            'status'          => 'active',
            'current_step'    => 1,
        ]);

        UserOrganisationRole::create([
            'user_id'         => $this->user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);

        ElectionMembership::create([
            'user_id'     => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'role'        => 'voter',
            'status'      => 'active',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T1: Code format — 8 chars, safe charset
    // ──────────────────────────────────────────────────────────────────────────

    public function test_generated_code_is_8_chars_with_safe_charset(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        // Debug: verify request reached the controller (not blocked by middleware)
        $statusCode = $response->getStatusCode();
        $this->assertNotEquals(302, $statusCode,
            'Request was redirected - middleware may have blocked it. Location: ' . $response->headers->get('location'));
        $this->assertNotEquals(403, $statusCode, 'Request was forbidden');
        $this->assertNotEquals(404, $statusCode, 'Not found');
        $this->assertNotEquals(500, $statusCode, 'Internal server error: ' . $response->getContent());

        $code = Code::withoutGlobalScopes()
            ->where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->first();

        $this->assertNotNull($code, 'Code record should have been created');
        $this->assertEquals(8, strlen($code->code_to_open_voting_form));
        $this->assertMatchesRegularExpression(
            '/^[ABCDEFGHJKLMNPQRSTUVWXYZ23456789]{8}$/',
            $code->code_to_open_voting_form
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T2: Expired code — ALL flags reset
    // ──────────────────────────────────────────────────────────────────────────

    public function test_expired_code_regenerates_with_all_flags_reset(): void
    {
        $code = Code::factory()->create([
            'user_id'                            => $this->user->id,
            'election_id'                        => $this->election->id,
            'organisation_id'                    => $this->org->id,
            'code_to_open_voting_form'           => 'OLDCODE1',
            'code_to_open_voting_form_sent_at'   => now()->subMinutes(35),
            'can_vote_now'                       => 0,
            'is_code_to_open_voting_form_usable' => 0,
            'code_to_open_voting_form_used_at'   => now()->subMinutes(34),
            'has_voted'                          => false,
            'vote_submitted'                     => false,
        ]);

        $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $code->refresh();

        $this->assertNotEquals('OLDCODE1', $code->code_to_open_voting_form, 'Code should have been regenerated');
        $this->assertEquals(0, $code->can_vote_now, 'can_vote_now must be reset to 0');
        $this->assertEquals(1, $code->is_code_to_open_voting_form_usable, 'usable flag must be reset to 1');
        $this->assertNull($code->code_to_open_voting_form_used_at, 'used_at must be reset to null');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T3: Verified code (can_vote_now=1) not regenerated
    // ──────────────────────────────────────────────────────────────────────────

    public function test_verified_code_is_not_regenerated(): void
    {
        $code = Code::factory()->verified()->create([
            'user_id'         => $this->user->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
        ]);
        $originalCode = $code->code_to_open_voting_form;

        $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $code->refresh();
        $this->assertEquals($originalCode, $code->code_to_open_voting_form, 'Verified code must not be regenerated');
        $this->assertEquals(1, $code->can_vote_now);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T4: has_voted blocks create()
    // ──────────────────────────────────────────────────────────────────────────

    public function test_has_voted_user_redirected_from_create(): void
    {
        Code::factory()->voted()->create([
            'user_id'         => $this->user->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T5: has_voted blocks store()
    // ──────────────────────────────────────────────────────────────────────────

    public function test_has_voted_user_blocked_in_store(): void
    {
        $code = Code::factory()->voted()->create([
            'user_id'         => $this->user->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('slug.code.store', ['vslug' => $this->voterSlug->slug]), [
                'voting_code' => $code->code_to_open_voting_form,
            ]);

        $response->assertSessionHasErrors('voting_code');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T6: has_voted blocks showAgreement() [NEW fix required]
    // ──────────────────────────────────────────────────────────────────────────

    public function test_has_voted_user_redirected_from_show_agreement(): void
    {
        Code::factory()->voted()->create([
            'user_id'         => $this->user->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'can_vote_now'    => 0,
        ]);

        // Step 1 must be recorded so EnsureVoterStepOrder allows access to step 2 (agreement)
        VoterSlugStep::create([
            'voter_slug_id'   => $this->voterSlug->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'step'            => 1,
            'completed_at'    => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('slug.code.agreement', ['vslug' => $this->voterSlug->slug]));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T7: 8-char validation — 6 chars rejected
    // ──────────────────────────────────────────────────────────────────────────

    public function test_store_rejects_code_with_wrong_length(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('slug.code.store', ['vslug' => $this->voterSlug->slug]), [
                'voting_code' => 'ABC123', // 6 chars
            ]);

        $response->assertSessionHasErrors('voting_code');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T8: IP rate limiting scoped to election (not global)
    // ──────────────────────────────────────────────────────────────────────────

    public function test_ip_rate_limiting_is_scoped_to_election(): void
    {
        $election2 = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);
        $sameIp = '10.0.0.99';

        // Fill IP limit on election2 (unrelated election)
        for ($i = 0; $i < 7; $i++) {
            $u = User::factory()->forOrganisation($this->org)->create();
            Code::factory()->voted()->create([
                'election_id'     => $election2->id,
                'organisation_id' => $this->org->id,
                'user_id'         => $u->id,
                'client_ip'       => $sameIp,
            ]);
        }

        // Create code for the real election under test with same IP
        $code = Code::factory()->create([
            'user_id'                  => $this->user->id,
            'election_id'              => $this->election->id,
            'organisation_id'          => $this->org->id,
            'client_ip'                => $sameIp,
            'code_to_open_voting_form' => 'TESTABCD',
        ]);

        // Should NOT be blocked by IP limit (limit scoped to election2, not election1)
        $response = $this->actingAs($this->user)
            ->post(route('slug.code.store', ['vslug' => $this->voterSlug->slug]), [
                'voting_code' => 'TESTABCD',
            ]);

        // Should fail for wrong code reason (IP limit should NOT be the error)
        $errors = session('errors') ? session('errors')->getBag('default')->all() : [];
        $errorMessages = array_values($errors);
        $allMessages   = implode(' ', array_map(fn ($msgs) => implode(' ', (array) $msgs), $errorMessages));

        $this->assertStringNotContainsStringIgnoringCase(
            'IP address',
            $allMessages,
            'IP rate limiting should be scoped to current election only'
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T9: generateUniqueCodeForOrganisation via reflection
    // ──────────────────────────────────────────────────────────────────────────

    public function test_generate_unique_code_for_organisation_produces_valid_codes(): void
    {
        $controller = new CodeController();
        $method     = new ReflectionMethod($controller, 'generateUniqueCodeForOrganisation');
        $method->setAccessible(true);

        $result = $method->invoke($controller, $this->org->id);

        $this->assertEquals(8, strlen($result));
        $this->assertMatchesRegularExpression(
            '/^[ABCDEFGHJKLMNPQRSTUVWXYZ23456789]{8}$/',
            $result
        );

        // Uniqueness under load: 50 codes must all differ
        $codes = [];
        for ($i = 0; $i < 50; $i++) {
            $codes[] = $method->invoke($controller, $this->org->id);
        }
        $this->assertCount(50, array_unique($codes), 'Duplicate codes generated');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T10: has_voted blocks submitAgreement() [NEW fix required]
    // ──────────────────────────────────────────────────────────────────────────

    public function test_has_voted_blocks_submit_agreement(): void
    {
        Code::factory()->voted()->create([
            'user_id'         => $this->user->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'can_vote_now'    => 1,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('slug.code.agreement.submit', ['vslug' => $this->voterSlug->slug]), [
                'agreement' => 1,
            ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // T11: Expired AND verified code (can_vote_now=1) not regenerated [edge case]
    // ──────────────────────────────────────────────────────────────────────────

    public function test_expired_verified_code_is_not_regenerated(): void
    {
        $code = Code::factory()->verified()->create([
            'user_id'                          => $this->user->id,
            'election_id'                      => $this->election->id,
            'organisation_id'                  => $this->org->id,
            'code_to_open_voting_form_sent_at' => now()->subMinutes(35), // expired but verified
            'can_vote_now'                     => 1,
        ]);
        $originalCode = $code->code_to_open_voting_form;

        $this->actingAs($this->user)
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $code->refresh();
        $this->assertEquals($originalCode, $code->code_to_open_voting_form, 'Verified code must not be regenerated even if expired');
        $this->assertEquals(1, $code->can_vote_now);
    }
}
