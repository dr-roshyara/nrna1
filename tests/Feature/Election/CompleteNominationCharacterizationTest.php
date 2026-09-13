<?php

namespace Tests\Feature\Election;

use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Events\NominationCompleted;
use App\Models\Candidacy;
use App\Models\Election;
use App\Models\ElectionAuditLog;
use App\Models\ElectionStateTransition;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * Slice B, step 1: characterization of the CURRENT (legacy) `Election::completeNomination()`
 * behavior, written before any migration to `transitionTo('complete_nomination')`.
 *
 * Purpose: lock in every existing behavior so the eventual migration can be
 * verified as behavior-preserving (or any deliberate deviation explicitly
 * approved), per the migration-conflict report and the user's own explicit
 * instruction: "Completing nomination must never itself open voting."
 *
 * This file makes NO production changes and modifies no other test. All
 * assertions describe CURRENT behavior of the unmodified legacy method.
 */
class CompleteNominationCharacterizationTest extends TestCase
{
    use RefreshDatabase;

    private function buildElectionWithPost(Organisation $org): array
    {
        $election = Election::factory()->forOrganisation($org)->create([
            'type' => 'real',
            'approved_at' => now()->subDays(2),
            'administration_completed' => true,
            'administration_completed_at' => now()->subDay(),
            'nomination_completed' => false,
            'nomination_completed_at' => null,
            'voting_starts_at' => null,
            'voting_ends_at' => null,
            'voting_locked' => false,
            'results_published_at' => null,
        ]);

        $post = Post::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
        ]);

        return [$election, $post];
    }

    private function approvedCandidacy(Organisation $org, Post $post): Candidacy
    {
        return Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
            'status' => 'approved',
        ]);
    }

    private function pendingCandidacy(Organisation $org, Post $post): Candidacy
    {
        return Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
            'status' => 'pending',
        ]);
    }

    // ── Guard: pending candidacies ───────────────────────────────────────────

    public function test_throws_when_any_candidacy_is_pending(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);
        $this->pendingCandidacy($org, $post);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot complete nomination: 1 candidates pending approval');

        $election->completeNomination('test reason', 'actor-1');
    }

    public function test_does_not_mutate_facts_when_pending_guard_fails(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);
        $this->pendingCandidacy($org, $post);

        try {
            $election->completeNomination('test reason', 'actor-1');
        } catch (\InvalidArgumentException $e) {
            // expected
        }

        $election->refresh();
        $this->assertFalse($election->nomination_completed);
        $this->assertNull($election->nomination_completed_at);
    }

    // ── Guard: zero approved candidacies ─────────────────────────────────────

    public function test_throws_when_zero_candidacies_are_approved(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, ] = $this->buildElectionWithPost($org);
        // No candidacies at all — zero pending, zero approved.

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot complete nomination: No candidates approved');

        $election->completeNomination('test reason', 'actor-1');
    }

    // ── Success path: core fact mutation ─────────────────────────────────────

    public function test_sets_nomination_completed_and_timestamp_on_success(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);

        $before = now();
        $election->completeNomination('all good', 'actor-1');
        $election->refresh();

        $this->assertTrue($election->nomination_completed);
        $this->assertNotNull($election->nomination_completed_at);
        // DB timestamp columns truncate to whole seconds; compare at that
        // granularity rather than against microsecond-precision Carbon::now().
        $this->assertTrue($election->nomination_completed_at->gte($before->copy()->startOfSecond()));
    }

    // ── Success path: voting-window auto-set behavior ────────────────────────

    public function test_auto_sets_voting_window_when_not_previously_configured(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);

        $this->assertNull($election->voting_starts_at);

        $before = now();
        $election->completeNomination('all good', 'actor-1');
        $election->refresh();

        $this->assertNotNull($election->voting_starts_at);
        $this->assertTrue($election->voting_starts_at->gte($before->copy()->startOfSecond()));
        $this->assertNotNull($election->voting_ends_at);
        // Exactly +4 days from voting_starts_at, per the current implementation.
        $this->assertEqualsWithDelta(
            4 * 24 * 60,
            $election->voting_starts_at->diffInMinutes($election->voting_ends_at),
            1,
            'voting_ends_at must be exactly ~4 days after the auto-set voting_starts_at'
        );
    }

    public function test_does_not_overwrite_an_already_configured_voting_window(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);

        $preConfiguredStart = now()->addDays(10);
        $preConfiguredEnd = now()->addDays(12);
        $election->update([
            'voting_starts_at' => $preConfiguredStart,
            'voting_ends_at' => $preConfiguredEnd,
        ]);

        $election->completeNomination('all good', 'actor-1');
        $election->refresh();

        // DB timestamp columns truncate to whole seconds; compare at that
        // granularity rather than against the microsecond-precision values.
        $this->assertEquals(0, $preConfiguredStart->copy()->startOfSecond()->diffInSeconds($election->voting_starts_at));
        $this->assertEquals(0, $preConfiguredEnd->copy()->startOfSecond()->diffInSeconds($election->voting_ends_at));
    }

    /**
     * Characterizes the exact defect this migration exists to fix, but proves
     * it is ALREADY neutralized by the Slice A invariant (voting_locked
     * required for Counting/VotingActive): completing nomination on an
     * election whose already-configured voting window is entirely in the
     * past must NOT, by itself, jump straight to VotingActive or Counting —
     * because completeNomination() never touches voting_locked.
     */
    public function test_completing_nomination_alone_never_derives_voting_active_or_counting(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);

        // Pre-configure a voting window that is already fully elapsed —
        // exactly the onf-europe-test-7891ba99 incident's shape.
        $election->update([
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subMinute(),
        ]);

        $election->completeNomination('all good', 'actor-1');
        $election->refresh();

        $this->assertFalse((bool) $election->voting_locked,
            'completeNomination() must never set voting_locked — that remains open_voting\'s sole responsibility.');

        $state = \App\Application\Election\Facades\ElectionLifecycle::of($election)->state();
        $this->assertNotEquals(ElectionLifecycleState::VotingActive, $state);
        $this->assertNotEquals(ElectionLifecycleState::Counting, $state);
    }

    // ── Audit trail: legacy mechanism only ───────────────────────────────────

    public function test_writes_legacy_audit_log_with_reason_and_actor(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);

        $election->completeNomination('my custom reason', 'actor-42');
        $election->refresh();

        $log = collect($election->state_audit_log ?? []);
        $entry = $log->firstWhere('action', 'nomination_completed');
        $this->assertNotNull($entry, 'state_audit_log must contain a nomination_completed entry');
        $this->assertSame('my custom reason', $entry['metadata']['reason']);
        $this->assertSame('actor-42', $entry['metadata']['actor_id']);

        $this->assertDatabaseHas('election_audit_logs', [
            'election_id' => $election->id,
            'action' => 'nomination_completed',
        ]);
    }

    public function test_does_not_create_any_constitutional_state_transition_record(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);

        $election->completeNomination('all good', 'actor-1');

        $this->assertSame(
            0,
            ElectionStateTransition::withoutGlobalScopes()->where('election_id', $election->id)->count(),
            'The legacy completeNomination() must not write to election_state_transitions — only transitionTo() does.'
        );
    }

    public function test_does_not_change_the_raw_state_column(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);
        $stateBefore = $election->state;

        $election->completeNomination('all good', 'actor-1');
        $election->refresh();

        $this->assertSame($stateBefore, $election->state);
    }

    // ── Event dispatch ────────────────────────────────────────────────────────

    public function test_dispatches_nomination_completed_event(): void
    {
        Event::fake([NominationCompleted::class]);

        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);

        $election->completeNomination('all good', 'actor-1');

        Event::assertDispatched(NominationCompleted::class, function (NominationCompleted $event) use ($election) {
            return $event->election->id === $election->id
                && $event->completedBy === 'actor-1'
                && $event->reason === 'all good';
        });
    }

    /**
     * Characterizes a real, current edge case: the method signature accepts a
     * nullable $actorId, but the event's constructor parameter is a
     * non-nullable string — so calling with a null actor currently throws a
     * TypeError when the event is dispatched, not a graceful domain error.
     */
    public function test_null_actor_id_currently_throws_type_error_on_event_dispatch(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        [$election, $post] = $this->buildElectionWithPost($org);
        $this->approvedCandidacy($org, $post);

        $this->expectException(\TypeError::class);

        $election->completeNomination('all good', null);
    }
}
