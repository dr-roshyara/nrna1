<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Services\ElectionAuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-284: no stored or logged value may let anyone link a vote to its voter (anonymity, ADR-T11).
 *
 * Each test plays an attacker with READ access to the database and to the audit entries (no secrets such as
 * app.key): after real casts, it tries to match votes to voters.
 * 1. votes.vote_hash: recomputed from the voter's codes row (id, election, code value) and the cast second;
 * 2. the vote_confirmed audit entry: it carries user_id next to a hash that can be recomputed from vote id + user id.
 *
 * Isolated database only; committed data, removed afterwards. The audit service is replaced by a recorder that
 * keeps the entries in memory (what the real service writes to its JSONL files).
 */
final class VoteAnonymityLinkageTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private string $postId;
    private string $candidacyId;
    /** @var list<string> */
    private array $users = [];
    /** @var list<array{event: string, user_id: ?string, metadata: array}> */
    public static array $audit = [];

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        self::$audit = [];
        config(['voting.time_in_minutes' => 30, 'app.max_use_clientIP' => null]);
        $this->app->instance(ElectionAuditService::class, new class extends ElectionAuditService {
            public function log($election, string $event, $user = null, string $category = 'voters', ?string $ip = null, array $metadata = []): void
            {
                VoteAnonymityLinkageTest::$audit[] = ['event' => $event, 'user_id' => $user?->id, 'metadata' => $metadata];
            }

            public function logVoterAction($election, $voter, int $step, string $action, array $metadata = []): void
            {
                VoteAnonymityLinkageTest::$audit[] = ['event' => $action, 'user_id' => $voter?->id, 'metadata' => $metadata];
            }
        });

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd284-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'PBDIGIT-284 '.uniqid(), 'slug' => 'pbd284-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
        ]);
        $this->postId = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $this->postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-284 post', 'required_number' => 1, 'is_national_wide' => true, 'created_at' => now(), 'updated_at' => now()]);
        $this->candidacyId = (string) Str::uuid();
        DB::table('candidacies')->insert(['id' => $this->candidacyId, 'organisation_id' => $this->org->id, 'post_id' => $this->postId,
            'status' => 'approved', 'created_at' => now(), 'updated_at' => now()]);
    }

    protected function tearDown(): void
    {
        try {
            $voteIds = DB::table('votes')->where('election_id', $this->election->id)->pluck('id');
            DB::table('results')->whereIn('vote_id', $voteIds)->delete();
            DB::table('votes')->where('election_id', $this->election->id)->delete();
            foreach (['receipt_codes', 'voter_slug_steps', 'voter_slugs', 'codes', 'election_memberships'] as $table) {
                DB::table($table)->where('election_id', $this->election->id)->delete();
            }
            DB::table('candidacies')->where('post_id', $this->postId)->delete();
            DB::table('posts')->where('id', $this->postId)->delete();
            DB::table('user_organisation_roles')->whereIn('user_id', $this->users)->delete();
            DB::table('elections')->where('id', $this->election->id)->delete();
            DB::table('organisations')->where('id', $this->org->id)->delete();
            DB::table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database
        }
        parent::tearDown();
    }

    /** Three voters cast real votes through the voting route; returns their user ids. */
    private function castThreeVotes(): array
    {
        $ids = [];
        foreach ([1, 2, 3] as $n) {
            $user = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => null,
                'organisation_id' => $this->org->id]);
            $this->users[] = $ids[] = $user->id;
            DB::table('user_organisation_roles')->insertOrIgnore(['id' => (string) Str::uuid(), 'user_id' => $user->id,
                'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
            ElectionMembership::create(['user_id' => $user->id, 'organisation_id' => $this->org->id,
                'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);
            $code = strtoupper(Str::random(8));
            DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
                'user_id' => $user->id, 'code1' => (string) random_int(100000, 999999), 'code_to_open_voting_form' => $code,
                'code_to_save_vote' => 'SAVE1234', 'is_code_to_open_voting_form_usable' => 0, 'code_to_open_voting_form_used_at' => now(),
                'is_code_to_save_vote_usable' => 1, 'can_vote_now' => 1, 'has_voted' => false, 'vote_submitted' => 0,
                'has_agreed_to_vote' => 1, 'client_ip' => '203.0.113.'.$n, 'session_name' => 'vote_data_'.$user->id,
                'voting_time_in_minutes' => 30, 'created_at' => now(), 'updated_at' => now()]);
            $slug = Str::random(40);
            DB::table('voter_slugs')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
                'user_id' => $user->id, 'slug' => $slug, 'is_active' => true, 'has_voted' => false, 'status' => 'active',
                'current_step' => 4, 'created_at' => now(), 'updated_at' => now(), 'expires_at' => now()->addMinutes(30)]);
            $ballot = ['national_selected_candidates' => [['post_id' => $this->postId, 'no_vote' => false,
                'candidates' => [['candidacy_id' => $this->candidacyId]]]], 'regional_selected_candidates' => [], 'no_vote_posts' => []];
            $this->actingAs($user)->withServerVariables(['REMOTE_ADDR' => '203.0.113.'.$n])
                ->withSession(['current_organisation_id' => $this->org->id, 'vote_data_'.$user->id => $ballot])
                ->post(route('slug.vote.store', ['vslug' => $slug]), ['voting_code' => $code])
                ->assertRedirect(route('vote.verify_to_show'));
        }
        $this->assertSame(3, DB::table('votes')->where('election_id', $this->election->id)->count(), 'precondition: three votes');

        return $ids;
    }

    public function test_the_vote_hash_cannot_be_recomputed_from_the_voters_code_row(): void
    {
        $this->castThreeVotes();
        $votes = DB::table('votes')->where('election_id', $this->election->id)->get(['id', 'vote_hash', 'cast_at']);
        $codes = DB::table('codes')->where('election_id', $this->election->id)->where('has_voted', true)
            ->get(['id', 'user_id', 'code_to_open_voting_form']);

        $linked = [];
        foreach ($votes as $vote) {
            $castAt = strtotime((string) $vote->cast_at);
            foreach ($codes as $code) {
                foreach (range($castAt - 5, $castAt + 5) as $second) {   // the stored cast time, to the second
                    if (hash('sha256', $code->id.$this->election->id.$code->code_to_open_voting_form.$second) === $vote->vote_hash) {
                        $linked[] = $vote->id;
                    }
                }
            }
        }

        $this->assertSame([], $linked, count($linked).' of 3 votes linked to their voter through vote_hash');
    }

    public function test_no_audit_entry_carries_a_value_that_links_a_user_to_a_vote(): void
    {
        $this->castThreeVotes();
        $voteIds = DB::table('votes')->where('election_id', $this->election->id)->pluck('id');

        $linked = [];
        foreach (self::$audit as $entry) {
            if ($entry['user_id'] === null) {
                continue;
            }
            foreach ($voteIds as $voteId) {
                $candidate = hash('sha256', $voteId.$entry['user_id'].$this->election->id);
                if (in_array($candidate, array_map('strval', $entry['metadata']), true) || str_contains(json_encode($entry['metadata']), (string) $voteId)) {
                    $linked[] = $entry['event'];
                }
            }
        }

        $this->assertSame([], $linked, count($linked).' audit entries link a user to a vote');
    }
}
