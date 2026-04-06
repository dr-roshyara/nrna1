<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * TDD Tests for verify_to_show() and submit_code_to_view_vote()
 *
 * Architecture: Votes are anonymous — NO user_id on Vote.
 * Verification uses receipt_hash = sha256(private_key . vote_id . app_key).
 * The verification code sent by email: "{private_key}_{vote_uuid}".
 * Code model is NOT consulted during verification — preserves anonymity.
 */
class VoteVerifyTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $user;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        Election::resetPlatformOrgCache();

        $this->org      = Organisation::factory()->create(['type' => 'tenant']);
        $this->user     = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        $this->org->users()->attach($this->user->id, [
            'id'   => (string) Str::uuid(),
            'role' => 'voter',
        ]);
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);

        session(['current_organisation_id' => $this->org->id]);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Create a Vote with a known private_key so we can reconstruct the
     * verification code in tests.
     */
    private function createVoteWithVerificationCode(): array
    {
        $privateKey = bin2hex(random_bytes(16)); // 32-char hex

        $vote = Vote::withoutGlobalScopes()->create([
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
        ]);

        // Compute and store receipt_hash (same formula as VoteController::save_vote)
        $vote->receipt_hash = hash('sha256', $privateKey . $vote->id . config('app.key'));
        $vote->save();

        $verificationCode = $privateKey . '_' . $vote->id;

        return ['vote' => $vote, 'code' => $verificationCode, 'private_key' => $privateKey];
    }

    private function createVoterSlug(): VoterSlug
    {
        return VoterSlug::factory()->create([
            'user_id'         => $this->user->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
            'current_step'    => 5,
            'has_voted'       => true,
        ]);
    }

    // ─── GET verify_to_show — page renders ───────────────────────────────────

    /** @test */
    public function verify_to_show_renders_inertia_page_for_authenticated_user(): void
    {
        $this->actingAs($this->user)
             ->get(route('vote.verify_to_show'))
             ->assertStatus(200)
             ->assertInertia(fn ($page) =>
                 $page->component('Vote/VoteShowVerify')
                      ->has('user_name')
             );
    }

    /** @test */
    public function verify_to_show_redirects_guest_to_login(): void
    {
        $this->get(route('vote.verify_to_show'))
             ->assertRedirect(route('login'));
    }

    // ─── POST submit_code_to_view_vote — valid code ───────────────────────────

    /** @test */
    public function submit_code_with_valid_code_shows_vote_data(): void
    {
        ['code' => $verificationCode, 'vote' => $vote] = $this->createVoteWithVerificationCode();

        $response = $this->actingAs($this->user)
             ->post(route('vote.submit_code_to_view_vote'), [
                 'voting_code'   => $verificationCode,
                 'election_type' => 'real',
             ]);

        // Should not redirect back with errors
        $response->assertSessionHasNoErrors();
        $response->assertSessionMissing('error');
    }

    /** @test */
    public function submit_code_requires_voting_code_field(): void
    {
        $this->actingAs($this->user)
             ->post(route('vote.submit_code_to_view_vote'), [
                 'election_type' => 'real',
             ])
             ->assertSessionHasErrors('voting_code');
    }

    /** @test */
    public function submit_code_rejects_code_without_underscore_separator(): void
    {
        $this->actingAs($this->user)
             ->post(route('vote.submit_code_to_view_vote'), [
                 'voting_code'   => 'SHORTCODE',
                 'election_type' => 'real',
             ])
             ->assertSessionHasErrors('voting_code');
    }

    /** @test */
    public function submit_code_rejects_code_with_invalid_uuid_portion(): void
    {
        $this->actingAs($this->user)
             ->post(route('vote.submit_code_to_view_vote'), [
                 'voting_code'   => 'ba2f5445d5de773786f4a56a9f640d1a_not-a-uuid',
                 'election_type' => 'real',
             ])
             ->assertSessionHasErrors('voting_code');
    }

    /** @test */
    public function submit_code_rejects_tampered_private_key(): void
    {
        ['vote' => $vote] = $this->createVoteWithVerificationCode();

        // Use wrong private_key — receipt_hash won't match
        $tamperedCode = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa_' . $vote->id;

        $this->actingAs($this->user)
             ->post(route('vote.submit_code_to_view_vote'), [
                 'voting_code'   => $tamperedCode,
                 'election_type' => 'real',
             ])
             ->assertSessionHasErrors('voting_code');
    }

    /** @test */
    public function submit_code_rejects_nonexistent_vote_id(): void
    {
        $fakeUuid = (string) Str::uuid();
        $code     = 'ba2f5445d5de773786f4a56a9f640d1a_' . $fakeUuid;

        $this->actingAs($this->user)
             ->post(route('vote.submit_code_to_view_vote'), [
                 'voting_code'   => $code,
                 'election_type' => 'real',
             ])
             ->assertSessionHasErrors('voting_code');
    }

    // ─── extract_vote_data_from_code unit tests (via reflection) ─────────────

    /** @test */
    public function extract_vote_data_parses_valid_verification_code(): void
    {
        $voteId     = (string) Str::uuid();
        $privateKey = bin2hex(random_bytes(16));
        $code       = $privateKey . '_' . $voteId;

        $controller = app(\App\Http\Controllers\VoteController::class);
        $result     = $this->invokePrivate($controller, 'extract_vote_data_from_code', [$code]);

        $this->assertTrue($result['success']);
        $this->assertEquals($voteId, $result['vote_id']);
        $this->assertEquals($privateKey, $result['private_key']);
    }

    /** @test */
    public function extract_vote_data_fails_without_underscore(): void
    {
        $controller = app(\App\Http\Controllers\VoteController::class);
        $result     = $this->invokePrivate($controller, 'extract_vote_data_from_code', ['nodivider']);

        $this->assertFalse($result['success']);
    }

    /** @test */
    public function extract_vote_data_fails_when_uuid_part_is_not_a_uuid(): void
    {
        $controller = app(\App\Http\Controllers\VoteController::class);
        $result     = $this->invokePrivate($controller, 'extract_vote_data_from_code', [
            'ba2f5445d5de773786f4a56a9f640d1a_not-a-valid-uuid'
        ]);

        $this->assertFalse($result['success']);
    }

    // ─── retrieve_vote_record unit tests (via reflection) ────────────────────

    /** @test */
    public function retrieve_vote_record_returns_vote_for_valid_code(): void
    {
        ['vote' => $vote, 'private_key' => $privateKey] = $this->createVoteWithVerificationCode();

        $controller = app(\App\Http\Controllers\VoteController::class);
        $result     = $this->invokePrivate($controller, 'retrieve_vote_record', [
            $vote->id,
            $privateKey,
        ]);

        $this->assertTrue($result['success']);
        $this->assertEquals($vote->id, $result['vote']->id);
    }

    /** @test */
    public function retrieve_vote_record_fails_for_wrong_private_key(): void
    {
        ['vote' => $vote] = $this->createVoteWithVerificationCode();

        $controller = app(\App\Http\Controllers\VoteController::class);
        $result     = $this->invokePrivate($controller, 'retrieve_vote_record', [
            $vote->id,
            'wrongkey000000000000000000000000',
        ]);

        $this->assertFalse($result['success']);
    }

    /** @test */
    public function retrieve_vote_record_fails_for_nonexistent_vote(): void
    {
        $controller = app(\App\Http\Controllers\VoteController::class);
        $result     = $this->invokePrivate($controller, 'retrieve_vote_record', [
            (string) Str::uuid(),
            'anykey',
        ]);

        $this->assertFalse($result['success']);
    }

    // ─── Anonymity guarantee ──────────────────────────────────────────────────

    /** @test */
    public function vote_record_has_no_user_id(): void
    {
        ['vote' => $vote] = $this->createVoteWithVerificationCode();

        $fresh = Vote::withoutGlobalScopes()->find($vote->id);
        $this->assertNull($fresh->user_id ?? null, 'Vote must not contain user_id (anonymity breach)');
    }

    // ─── Private helper ───────────────────────────────────────────────────────

    private function invokePrivate(object $object, string $method, array $args = []): mixed
    {
        $ref = new \ReflectionMethod($object, $method);
        $ref->setAccessible(true);
        return $ref->invokeArgs($object, $args);
    }
}
