<?php

namespace Tests\Feature\Characterization\Phase1;

use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\TenantContext;
use Illuminate\Support\Facades\Notification;
use Tests\Support\ElectionScenarioFactory;

/**
 * Phase 1 characterization fixtures — SYNTHETIC DATA ONLY (roadmap P7).
 *
 * Drives one real-election cast through the actual route `slug.vote.store`
 * (VoteController::store) in the testing database. Nothing here touches the
 * development database or any real election. Production behaviour is not changed.
 */
trait CastFixtures
{
    protected Organisation $org;
    protected Election $election;

    protected function makeVotingElection(): void
    {
        Election::resetPlatformOrgCache();
        Notification::fake();

        // Fixture determinism (test-only): `app.max_use_clientIP` = env('MAX_USE_IP_ADDRESS')
        // has no default; when unset, VoteController::vote_post_check treats EVERY vote as
        // "IP limit exceeded" and renders a missing view → the cast fails (Phase 1 finding).
        // 7 = the CodeController fallback. Production configuration is not changed.
        config(['app.max_use_clientIP' => 7]);

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        session(['current_organisation_id' => $this->org->id]);
        $this->election = ElectionScenarioFactory::votingActive($this->org);
    }

    protected function tearDownCast(): void
    {
        TenantContext::clear();
    }

    /** A registered, active voter with a voter slug and a code that has completed the code step. */
    protected function voterReadyToCast(string $code1 = 'ABCD1234'): array
    {
        $voter = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        ElectionMembership::create([
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);

        $slug = VoterSlug::factory()->create([
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);

        $code = Code::factory()->verified()->create([
            'organisation_id'          => $this->org->id,
            'user_id'                  => $voter->id,
            'election_id'              => $this->election->id,
            'code_to_open_voting_form' => $code1,
            'has_agreed_to_vote'       => 1,
            'session_name'             => 'vote_data_' . $voter->id,
            'client_ip'                => '127.0.0.1',
        ]);

        return [$voter, $slug, $code];
    }

    /** The ballot selections `first_submission` would have stored in the session. */
    protected function ballotSessionData(): array
    {
        return [
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [],
            'no_vote_posts'                => [],
        ];
    }

    /** @var list<array{level:string,message:string,context:array}> */
    protected array $logged = [];

    /** Record every log message written during the test (all channels), for log-correlation characterization. */
    protected function captureLogs(): void
    {
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Log\Events\MessageLogged::class, function ($e) {
            $this->logged[] = ['level' => $e->level, 'message' => $e->message, 'context' => $e->context];
        });
    }

    /** Log entries whose message or context contains $needle (string search over the JSON form). */
    protected function logsMentioning(string $needle): array
    {
        return array_values(array_filter(
            $this->logged,
            fn (array $entry) => str_contains($entry['message'] . ' ' . json_encode($entry['context']), $needle)
        ));
    }

    /** A second, active voter slug for the same voter (a second voting session). */
    protected function secondSlugFor(User $voter): VoterSlug
    {
        return VoterSlug::factory()->create([
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);
    }

    /** POST the final vote (slug.vote.store) as this voter. */
    protected function cast(User $voter, VoterSlug $slug, Code $code, ?string $submittedCode = null)
    {
        return $this->actingAs($voter)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                $code->session_name       => $this->ballotSessionData(),
            ])
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), [
                'voting_code' => $submittedCode ?? $code->code_to_open_voting_form,
            ]);
    }
}
