<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S8: the voting-link lifecycle as one voter lives it, end to end over HTTP (plan section 13).
 *
 * Business rule: an entitled voter has exactly one current, valid voting link. A valid link is reused; an expired
 * one is replaced by a new link while the old one is kept as history; nobody else can use it; there is no link
 * after voting or without entitlement.
 *
 * Time passes through the clock (travel), not by rewriting timestamps, so the issuer, the guard and the stored
 * rows all see the same "now". The cast itself is S9's subject (G7): "has voted" is set on the codes row the way
 * a committed cast leaves it.
 *
 * Isolated database only; committed data (the issuer refuses an outer transaction), removed afterwards.
 */
final class VoterSlugLifecycleFlowTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private User $voter;

    /** @var list<string> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        Mail::fake();
        config(['voting.time_in_minutes' => 30]);

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-s8-'.uniqid()]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'PBDIGIT-274 lifecycle '.uniqid(), 'slug' => 'pbd274-s8-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(5),
        ]);
        // Inserted directly (PostFactory / CandidacyFactory create untracked rows of their own).
        $postId = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-274 post', 'required_number' => 1, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('candidacies')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'post_id' => $postId,
            'status' => 'approved', 'created_at' => now(), 'updated_at' => now()]);

        $this->voter = $this->entitledVoter();
    }

    protected function tearDown(): void
    {
        $this->travelBack();
        try {
            DB::table('voter_slug_steps')->where('election_id', $this->election->id)->delete();
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

    private function entitledVoter(): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now()]);
        $this->users[] = $user->id;
        DB::table('user_organisation_roles')->insert(['id' => (string) Str::uuid(), 'user_id' => $user->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $user->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);

        return $user;
    }

    /** @return list<array<string, mixed>> the voter's links in this election, history included, oldest first */
    private function links(User $user): array
    {
        return array_map(fn ($r) => (array) $r, DB::table('voter_slugs')->where('user_id', $user->id)
            ->where('election_id', $this->election->id)->orderBy('created_at')->get()->all());
    }

    private function current(User $user): ?string
    {
        return DB::table('voter_slugs')->where('user_id', $user->id)->where('election_id', $this->election->id)
            ->whereNull('deleted_at')->value('slug');
    }

    private function start(User $user)
    {
        return $this->actingAs($user)->post(route('elections.start', $this->election->slug));
    }

    private function open(User $user, string $slug)
    {
        return $this->actingAs($user)->get(route('slug.code.create', ['vslug' => $slug]));
    }

    private function row(string $slug): array
    {
        return (array) DB::table('voter_slugs')->where('slug', $slug)->first();
    }

    public function test_one_voter_from_first_link_through_expiry_and_replacement_to_having_voted(): void
    {
        // 1. Starting to vote issues one link and leads into the voting flow; the link opens step 1.
        $this->start($this->voter)->assertRedirect(route('slug.code.create', ['vslug' => $first = $this->current($this->voter)]));
        $this->assertNotNull($first);
        $this->assertCount(1, $this->links($this->voter));
        $this->open($this->voter, $first)->assertOk();

        // 2. Ten minutes later, starting again reuses the same link: nothing extended, nothing rewritten.
        $this->travel(10)->minutes();
        $before = $this->links($this->voter);
        $this->start($this->voter)->assertRedirect(route('slug.code.create', ['vslug' => $first]));
        $this->assertSame($before, $this->links($this->voter));

        // 3. Another entitled voter cannot use it: the same "not found" as an unknown link (O-5).
        $other = $this->entitledVoter();
        $this->open($other, $first)->assertNotFound();
        $this->assertSame([], $this->links($other), 'opening a foreign link creates nothing for the opener');

        // 4. The fixed lifetime runs out (30 minutes from creation; step 2's start did not extend it). The owner is
        //    sent back to the election with the expiry message; the expired link is left exactly as it was.
        $this->travel(21)->minutes();
        $expired = $this->row($first);
        $this->open($this->voter, $first)->assertRedirect(route('elections.show', $this->election->slug));
        $this->assertSame('Your voting session has expired, please start again.', session('error'));
        $this->assertSame($expired, $this->row($first));

        // 5. Starting again gives a NEW link instead of a lockout; the old one is kept as history.
        $this->start($this->voter)->assertRedirect(route('slug.code.create', ['vslug' => $second = $this->current($this->voter)]));
        $this->assertNotSame($first, $second);
        $links = $this->links($this->voter);
        $this->assertCount(2, $links, 'history kept, never hard-deleted');
        $this->assertSame($first, $links[0]['slug']);
        $this->assertNotNull($links[0]['deleted_at'], 'the old link is retired');
        $this->assertNull($links[1]['deleted_at']);
        $this->assertSame(1, DB::table('voter_slugs')->where('user_id', $this->voter->id)
            ->where('election_id', $this->election->id)->whereNull('deleted_at')->count(), 'exactly one current link');

        // 6. The old link is gone for good; the new one works.
        $this->open($this->voter, $first)->assertNotFound();
        $this->open($this->voter, $second)->assertOk();

        // 7. After voting there is no link: starting again issues nothing and changes nothing.
        $marked = DB::table('codes')->where('user_id', $this->voter->id)->where('election_id', $this->election->id)
            ->update(['has_voted' => true]);
        $this->assertSame(1, $marked, 'step 1 created the voter\'s code row');
        $afterVote = $this->links($this->voter);
        $this->start($this->voter)->assertRedirect(route('elections.show', $this->election->slug));
        $this->assertSame($afterVote, $this->links($this->voter));
    }

    public function test_a_voter_who_loses_entitlement_gets_no_link_and_cannot_continue_with_the_old_one(): void
    {
        $this->start($this->voter);
        $slug = $this->current($this->voter);
        $this->open($this->voter, $slug)->assertOk();

        DB::table('election_memberships')->where('user_id', $this->voter->id)
            ->where('election_id', $this->election->id)->update(['status' => 'inactive']);

        // O-3: the refusal retires the current link (history kept) and issues none.
        $this->start($this->voter)->assertRedirect(route('elections.show', $this->election->slug));
        $links = $this->links($this->voter);
        $this->assertCount(1, $links, 'no new link');
        $this->assertNotNull($links[0]['deleted_at'], 'the current link is retired, not deleted');
        $this->assertNull($this->current($this->voter));

        // The retired link answers like any unknown link.
        $this->open($this->voter, $slug)->assertNotFound();
    }
}
