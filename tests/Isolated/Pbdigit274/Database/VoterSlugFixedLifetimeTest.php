<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S5: a real-election voting link keeps its fixed lifetime and its history.
 * - POST /election/select (election.store) issues through VoterSlugIssuer (the last live real-election path
 *   that still used VoterSlugService::getOrCreateSlug, with its in-place rewrite and hard deletes);
 * - a new verification code (CodeController::create) no longer extends the link;
 * - voting:clean-expired-slugs never hard-deletes real links (soft-deleted ones are history).
 *
 * Isolated database only; committed data, removed afterwards.
 */
final class VoterSlugFixedLifetimeTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private User $voter;

    /** @var list<string> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-s5-'.uniqid()]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'slug' => 'pbd274-s5-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
        ]);
        $postId = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-274 post', 'required_number' => 1, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('candidacies')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'post_id' => $postId,
            'status' => 'approved', 'created_at' => now(), 'updated_at' => now()]);

        $this->voter = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now()]);
        $this->users[] = $this->voter->id;
        DB::table('user_organisation_roles')->insert(['id' => (string) Str::uuid(), 'user_id' => $this->voter->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $this->voter->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);
    }

    protected function tearDown(): void
    {
        try {
            DB::table('voter_slugs')->where('election_id', $this->election->id)->delete();
            DB::table('codes')->where('election_id', $this->election->id)->delete();
            DB::table('election_memberships')->where('election_id', $this->election->id)->delete();
            DB::table('user_organisation_roles')->whereIn('user_id', $this->users)->delete();
            DB::table('organisations')->where('id', $this->org->id)->delete();
            DB::table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database
        }
        parent::tearDown();
    }

    private function slugRow(array $attributes = []): string
    {
        $id = (string) Str::uuid();
        DB::table('voter_slugs')->insert($attributes + [
            'id' => $id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'slug' => Str::random(32), 'is_active' => true, 'has_voted' => false,
            'status' => 'active', 'current_step' => 1, 'created_at' => now(), 'updated_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);

        return $id;
    }

    /** @return list<array<string, mixed>> */
    private function rows(): array
    {
        return array_map(fn ($r) => (array) $r, DB::table('voter_slugs')->where('user_id', $this->voter->id)
            ->where('election_id', $this->election->id)->orderBy('created_at')->get()->all());
    }

    private function currentSlug(): ?string
    {
        return DB::table('voter_slugs')->where('user_id', $this->voter->id)->where('election_id', $this->election->id)
            ->whereNull('deleted_at')->value('slug');
    }

    private function selectElection()
    {
        // The voter's organisation in the session, as in production: storeElection loads the election through the
        // tenant scope (Election::findOrFail), which otherwise answers 404.
        return $this->actingAs($this->voter)->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('election.store'), ['election_id' => $this->election->id]);
    }

    // ── election.store ────────────────────────────────────────────────────

    public function test_selecting_a_real_election_reuses_a_valid_link_unchanged(): void
    {
        $id = $this->slugRow();
        $slug = DB::table('voter_slugs')->where('id', $id)->value('slug');
        $before = $this->rows();

        $this->selectElection()->assertRedirect(route('slug.code.create', ['vslug' => $slug]));
        $this->assertSame($before, $this->rows(), 'no rewrite, no extension');
    }

    public function test_selecting_a_real_election_replaces_an_expired_link_and_keeps_history(): void
    {
        $old = $this->slugRow(['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);

        $this->selectElection();

        $this->assertCount(2, $this->rows(), 'the expired link is kept (no hard delete)');
        $this->assertNotNull(DB::table('voter_slugs')->where('id', $old)->value('deleted_at'));
        $this->assertNotNull($this->currentSlug());
    }

    // ── CodeController::create: a new code does not extend the link ───────

    public function test_a_new_verification_code_does_not_extend_the_link(): void
    {
        $createdAt = now()->subMinutes(10)->startOfSecond();
        $expiresAt = now()->addMinutes(20)->startOfSecond();
        $id = $this->slugRow(['created_at' => $createdAt, 'expires_at' => $expiresAt]);
        $slug = DB::table('voter_slugs')->where('id', $id)->value('slug');
        // A code whose flags were zeroed (vote_pre_check timeout: has_code1_sent = false) but which was sent
        // recently: getOrCreateCode() does not resend it (not older than T), so CodeController::create reaches
        // its own reset block (`!has_code1_sent && sent_at`), the one that used to extend the voter slug.
        DB::table('codes')->insert([
            'id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'code1' => '111111', 'has_voted' => false, 'has_code1_sent' => false,
            'code_to_open_voting_form' => 'OLDCODE1', 'code_to_open_voting_form_sent_at' => now()->subMinutes(5),
            'created_at' => now()->subMinutes(5), 'updated_at' => now()->subMinutes(5),
        ]);

        $this->actingAs($this->voter)->get(route('slug.code.create', ['vslug' => $slug]));

        $this->assertNotSame('OLDCODE1', DB::table('codes')->where('user_id', $this->voter->id)
            ->where('election_id', $this->election->id)->value('code_to_open_voting_form'), 'the code was regenerated (precondition)');
        $this->assertSame($expiresAt->timestamp, strtotime(DB::table('voter_slugs')->where('id', $id)->value('expires_at')),
            'the link keeps its expiry');
    }

    // ── voting:clean-expired-slugs ────────────────────────────────────────

    public function test_the_cleanup_command_never_hard_deletes_real_link_history(): void
    {
        $history = $this->slugRow(['created_at' => now()->subDays(3), 'expires_at' => now()->subDays(3)->addMinutes(30),
            'deleted_at' => now()->subDays(3), 'is_active' => false, 'status' => 'expired']);

        $this->artisan('voting:clean-expired-slugs', ['--hours' => 1])->assertExitCode(0);

        $this->assertNotNull(DB::table('voter_slugs')->where('id', $history)->first(), 'the soft-deleted real link is kept');
    }
}
