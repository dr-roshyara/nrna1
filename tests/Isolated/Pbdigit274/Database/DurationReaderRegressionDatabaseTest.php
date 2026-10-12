<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Exceptions\Voting\VoterSlugLifetimeMisconfigured;
use App\Http\Middleware\CheckVotingWindow;
use App\Models\DemoCode;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\Isolated\Pbdigit274\IsolatedDatabaseTestCase;

/**
 * PBDIGIT-274 S6 (plan section 6, O-10 = B): the database-bound readers of voting.time_in_minutes.
 * - R6 CheckVotingWindow (demo voting window, `column ?? config`);
 * - R2 VoterSlugService::generateSlugForUser (legacy slug creation, new `expires_at`).
 *
 * test_valid_value_unchanged_* are characterisation tests, executed against the code before S6 and again after;
 * test_invalid_value_fails_closed_* are S6's RED → GREEN. Configured value 20, elapsed 25 minutes (see
 * tests/Unit/Services/Voting/DurationReaderRegressionTest.php).
 */
final class DurationReaderRegressionDatabaseTest extends IsolatedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();
        config(['voting.time_in_minutes' => 20]);
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        $this->election = Election::factory()->forOrganisation($this->org)->demo()->create();
        $this->voter = User::factory()->create();
    }

    /**
     * demo_codes.voting_time_in_minutes is NOT NULL (default 30), so R6's `column ?? config` fallback is unreachable
     * for a stored code: the window is the code's own column. Its fail-closed path is covered by the same reader
     * in the database-free test (R7 / R8, identical `column ?? reader` shape).
     */
    private function demoCode(int $minutes): DemoCode
    {
        return DemoCode::factory()->create([
            'organisation_id' => $this->org->id, 'election_id' => $this->election->id, 'user_id' => $this->voter->id,
            'voting_time_in_minutes' => $minutes, 'can_vote_now' => 1, 'has_voted' => false,
            'code_to_open_voting_form_used_at' => now()->subMinutes(25),
        ]);
    }

    /** Runs CheckVotingWindow on a non-code route; returns the response or 'next' when it let the request through. */
    private function window(): mixed
    {
        $slug = (new VoterSlug())->forceFill(['election_id' => $this->election->id, 'slug' => 'pbd274-s6-slug']);
        $request = Request::create('/v/pbd274-s6-slug/demo-vote/create');
        $request->attributes->set('voter_slug', $slug);
        $this->actingAs($this->voter);

        return (new CheckVotingWindow())->handle($request, fn () => 'next');
    }

    public function test_valid_value_unchanged_R6_demo_voting_window(): void
    {
        $code = $this->demoCode(20);

        $response = $this->window();

        $this->assertNotSame('next', $response, '25 minutes > 20: the window has expired');
        $this->assertSame(0, (int) $code->fresh()->can_vote_now, 'the code is reset so that it can be reissued');

        DB::table('demo_codes')->where('id', $code->id)->update(['can_vote_now' => 1, 'voting_time_in_minutes' => 30]);
        $this->assertSame('next', $this->window(), '25 minutes <= 30');
    }

    public function test_valid_value_unchanged_R2_legacy_slug_creation(): void
    {
        $this->freezeSecond();
        session(['selected_election_id' => $this->election->id]);

        $slug = app(VoterSlugService::class)->generateSlugForUser($this->voter);

        $this->assertTrue(now()->addMinutes(20)->equalTo($slug->expires_at));
    }

    /** Written with the S6 change (no RED run): the demo branch used the undefined key voting.slug_expiration_minutes, i.e. always 30. */
    public function test_R3_demo_slug_expiry_follows_the_configured_value(): void
    {
        $this->freezeSecond();

        $slug = app(VoterSlugService::class)->getOrCreateSlug($this->voter, $this->election, true);

        $this->assertTrue(now()->addMinutes(20)->equalTo($slug->expires_at));
    }

    public function test_invalid_value_fails_closed_R2_legacy_slug_creation(): void
    {
        session(['selected_election_id' => $this->election->id]);
        config(['voting.time_in_minutes' => '20min']);

        try {
            app(VoterSlugService::class)->generateSlugForUser($this->voter);
            $this->fail('expected VoterSlugLifetimeMisconfigured');
        } catch (VoterSlugLifetimeMisconfigured) {
        }
        $this->assertSame(0, DB::table('voter_slugs')->where('user_id', $this->voter->id)->count(), 'nothing is written');
    }
}
