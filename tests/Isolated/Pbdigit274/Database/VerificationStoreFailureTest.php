<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Application\Election\Security\VoterAttestationEvidence;
use App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery;
use App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery;
use App\Contexts\Elections\Infrastructure\Security\TrustLegitimacySecurityVerdict;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Services\ElectionAuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use RuntimeException;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-286 P5 (D3-E; second review gap 4): when the verification store can't be read, no caller authorises a vote.
 * The port is replaced by one that throws; each test also proves the port WAS asked, so the refusal comes from the
 * store failure and not from an earlier check.
 *
 * - code entry (CodeController::refuseEntry, \Throwable → generic refusal);
 * - the real vote (VoteController::store, \Throwable → failCastClosed): no vote, participation not consumed;
 * - the demo vote (DemoVoteController::store, \Exception → rollback): no demo vote.
 * The failure's message never reaches the voter.
 *
 * Isolated database only (G-ISO for P6); committed data, removed afterwards. The audit service is stubbed (no files).
 * . ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VerificationStoreFailureTest.php
 */
final class VerificationStoreFailureTest extends IsolatedCommittedDatabaseTestCase
{
    public const INTERNAL ='verification store unavailable: SQLSTATE[08006] host=internal-db';

    private Organisation $org;
    private Election $election;
    private ?Election $demo = null;
    private string $postId;
    private string $candidacyId;

    /** @var list<string> */
    private array $users = [];

    private int $asked = 0;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        config(['voting.time_in_minutes' => 30, 'app.max_use_clientIP' => null]);
        $this->app->instance(ElectionAuditService::class, new class extends ElectionAuditService {
            public function log($election, string $event, $user = null, string $category = 'voters', ?string $ip = null, array $metadata = []): void
            {
            }

            public function logVoterAction($election, $voter, int $step, string $action, array $metadata = []): void
            {
            }
        });

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd286-fail-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'PBDIGIT-286 store failure '.uniqid(), 'slug' => 'pbd286-fail-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
            'ip_restriction_enabled' => false, 'ip_whitelist' => null,
        ]);
        $this->postId = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $this->postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-286 post', 'required_number' => 1, 'is_national_wide' => true, 'created_at' => now(), 'updated_at' => now()]);
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
            if ($this->demo !== null) {
                foreach (['demo_votes', 'demo_codes', 'voter_slugs'] as $table) {
                    DB::table($table)->where('election_id', $this->demo->id)->delete();
                }
                DB::table('elections')->where('id', $this->demo->id)->delete();
            }
            DB::table('election_security_events')->where('election_id', $this->election->id)->delete();
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

    // ── fixtures ───────────────────────────────────────────────────────────────────────────────────────

    /** From now on the verification store fails; every resolved evaluator uses the failing port. */
    private function storeFails(): void
    {
        $asked = &$this->asked;
        $this->app->instance(VoterAttestationEvidence::class, new class($asked) implements VoterAttestationEvidence {
            public function __construct(private int &$asked) {}

            public function hasActiveAttestation(string $electionId, string $userId): bool
            {
                $this->asked++;
                throw new RuntimeException(VerificationStoreFailureTest::INTERNAL);
            }
        });
        foreach ([TrustPolicyEvaluator::class, TrustLegitimacySecurityVerdict::class, VotingSecurityVerdictQuery::class, VotingSecurityAssessmentQuery::class] as $abstract) {
            $this->app->forgetInstance($abstract);
        }
    }

    private function user(): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => null,
            'organisation_id' => $this->org->id]);
        $this->users[] = $user->id;
        DB::table('user_organisation_roles')->insertOrIgnore(['id' => (string) Str::uuid(), 'user_id' => $user->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $user->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);

        return $user;
    }

    private function as(User $user): static
    {
        return $this->actingAs($user)->withSession(['current_organisation_id' => $this->org->id]);
    }

    /** An entitled voter at the verification step (code entered, agreed), as in IpVoteLimitAtCastTest. */
    private function voterAtVerification(): array
    {
        $user = $this->user();
        $code = strtoupper(Str::random(8));
        DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'code1' => (string) random_int(100000, 999999), 'code_to_open_voting_form' => $code,
            'code_to_save_vote' => 'SAVE1234', 'is_code_to_open_voting_form_usable' => 0, 'code_to_open_voting_form_used_at' => now(),
            'is_code_to_save_vote_usable' => 1, 'can_vote_now' => 1, 'has_voted' => false, 'vote_submitted' => 0,
            'has_agreed_to_vote' => 1, 'client_ip' => '127.0.0.1', 'session_name' => 'vote_data_'.$user->id, 'voting_time_in_minutes' => 30,
            'created_at' => now(), 'updated_at' => now()]);
        $slug = Str::random(40);
        DB::table('voter_slugs')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'slug' => $slug, 'is_active' => true, 'has_voted' => false, 'status' => 'active',
            'current_step' => 4, 'created_at' => now(), 'updated_at' => now(), 'expires_at' => now()->addMinutes(30)]);

        return ['user' => $user, 'slug' => $slug, 'code' => $code];
    }

    private function cast(array $v): TestResponse
    {
        $ballot = ['national_selected_candidates' => [['post_id' => $this->postId, 'no_vote' => false,
            'candidates' => [['candidacy_id' => $this->candidacyId]]]], 'regional_selected_candidates' => [], 'no_vote_posts' => []];

        return $this->actingAs($v['user'])
            ->withSession(['current_organisation_id' => $this->org->id, 'vote_data_'.$v['user']->id => $ballot])
            ->post(route('slug.vote.store', ['vslug' => $v['slug']]), ['voting_code' => $v['code']]);
    }

    private function counted(TestResponse $response): bool
    {
        return $response->isRedirect(route('vote.verify_to_show')) && session()->has('success');
    }

    private function errorsText(): string
    {
        $errors = session('errors');

        return trim(($errors ? implode(' ', $errors->all()) : '').' '.(string) session('error'));
    }

    private function votes(): int
    {
        return DB::table('votes')->where('election_id', $this->election->id)->count();
    }

    private function assertNothingInternalShown(TestResponse $response): void
    {
        $this->assertStringNotContainsString('SQLSTATE', $this->errorsText().' '.$response->getContent());
        $this->assertStringNotContainsString('internal-db', $this->errorsText().' '.$response->getContent());
    }

    // ── control: the fixture can vote when the store works ─────────────────────────────────────────────

    public function test_control_with_a_working_store_the_vote_counts(): void
    {
        $voter = $this->voterAtVerification();

        $this->assertTrue($this->counted($this->cast($voter)), 'control: '.$this->errorsText());
        $this->assertSame(1, $this->votes());
    }

    // ── the three callers ──────────────────────────────────────────────────────────────────────────────

    public function test_code_entry_refuses_generically_when_the_store_fails(): void
    {
        $voter = $this->user();
        $this->as($voter)->post(route('elections.start', $this->election->slug));
        $slug = DB::table('voter_slugs')->where('user_id', $voter->id)->whereNull('deleted_at')->value('slug');
        $this->assertNotNull($slug, 'a link was issued at start');

        $this->storeFails();
        $response = $this->as($voter)->get(route('slug.code.create', ['vslug' => $slug]));

        $this->assertGreaterThan(0, $this->asked, 'the verification store was asked');
        $this->assertTrue($response->isRedirect(), 'code entry refused');
        $this->assertStringContainsString('We could not confirm your access to this election right now', $this->errorsText());
        $this->assertNothingInternalShown($response);
        $this->assertSame(0, $this->votes());
    }

    public function test_the_real_vote_is_refused_and_nothing_is_stored_when_the_store_fails(): void
    {
        $voter = $this->voterAtVerification();

        $this->storeFails();
        $response = $this->cast($voter);

        $this->assertGreaterThan(0, $this->asked, 'the verification store was asked');
        $this->assertFalse($this->counted($response), 'refused');
        $this->assertSame(0, $this->votes(), 'no vote persisted');
        $code = DB::table('codes')->where('user_id', $voter['user']->id)->where('election_id', $this->election->id)->first();
        $this->assertFalse((bool) $code->has_voted, 'participation not consumed');
        $this->assertNothingInternalShown($response);
    }

    public function test_the_demo_vote_rolls_back_and_nothing_is_stored_when_the_store_fails(): void
    {
        // The demo election belongs to the test organisation and the session carries it: ElectionMiddleware resolves
        // selected_election_id through the tenant scope (Election::find).
        $this->demo = Election::factory()->forOrganisation($this->org)->demo()->create(['name' => 'PBDIGIT-286 demo '.uniqid(),
            'slug' => 'pbd286-demo-'.uniqid(), 'is_active' => true]);
        $voter = $this->user();

        $this->storeFails();
        $response = $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $this->org->id, 'selected_election_id' => $this->demo->id])
            ->post(route('demo-vote.store'), ['voting_code' => 'ABCDEFGH']);

        $this->assertGreaterThan(0, $this->asked, 'the verification store was asked (the evaluation runs first in the transaction)');
        $this->assertTrue($response->isRedirect(), 'refused');
        $this->assertSame(0, DB::table('demo_votes')->where('election_id', $this->demo->id)->count(), 'no demo vote persisted');
        $this->assertSame(0, DB::transactionLevel(), 'the demo transaction was rolled back');
        $this->assertNothingInternalShown($response);
    }
}
