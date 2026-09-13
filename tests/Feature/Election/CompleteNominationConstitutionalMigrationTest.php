<?php

namespace Tests\Feature\Election;

use App\Domain\Election\Events\NominationCompleted;
use App\Domain\Election\StateMachine\Transition;
use App\Domain\Election\StateMachine\TransitionTrigger;
use App\Exceptions\InvalidTransitionException;
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
 * Slice B, Step 3: the migrated constitutional path for nomination completion —
 * both complete_nomination (human) and auto_complete_nomination (system),
 * sharing one predicate authority (NominationCompletionPredicates) and one
 * side-effect implementation (applySideEffectsForCompleteNomination()).
 *
 * See .claude/plans/encapsulated-hopping-hamster.md for the full design.
 */
class CompleteNominationConstitutionalMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function buildElectionWithPost(?Organisation $org = null): array
    {
        $org = $org ?? Organisation::factory()->create(['type' => 'tenant']);
        $election = Election::factory()->forOrganisation($org)->create([
            'type' => 'real',
            'state' => 'setup_nomination',
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
        $post = Post::factory()->create(['election_id' => $election->id, 'organisation_id' => $org->id]);

        return [$org, $election, $post];
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

    private function chiefOfficer(Organisation $org, Election $election): User
    {
        $chief = User::factory()->forOrganisation($org)->create();
        \App\Models\ElectionOfficer::create([
            'user_id' => $chief->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);
        \App\Services\TenantContext::set($org->id);

        return $chief;
    }

    // ── Precondition enforcement (both preconditions, human path) ───────────

    public function test_complete_nomination_is_refused_when_pending_candidacies_exist(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->approvedCandidacy($org, $post);
        $this->pendingCandidacy($org, $post);
        $this->actingAs($chief);

        $this->expectException(InvalidTransitionException::class);
        $this->expectExceptionMessage('All pending candidacy applications must be approved or rejected');

        $election->completeNomination('test', $chief->id);
    }

    public function test_complete_nomination_is_refused_when_zero_approved_candidates(): void
    {
        [$org, $election, ] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->actingAs($chief);

        $this->expectException(InvalidTransitionException::class);
        $this->expectExceptionMessage('At least one candidate must be approved');

        $election->completeNomination('test', $chief->id);
    }

    public function test_complete_nomination_does_not_mutate_facts_when_preconditions_fail(): void
    {
        [$org, $election, ] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->actingAs($chief);

        try {
            $election->completeNomination('test', $chief->id);
        } catch (InvalidTransitionException $e) {
            // expected
        }

        $election->refresh();
        $this->assertFalse($election->nomination_completed);
        $this->assertSame(
            0,
            ElectionStateTransition::withoutGlobalScopes()->where('election_id', $election->id)->count()
        );
    }

    // ── Success path: constitutional transition record + facts ──────────────

    public function test_complete_nomination_creates_constitutional_transition_record(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->approvedCandidacy($org, $post);
        $this->actingAs($chief);

        $election->completeNomination('nomination looks good', $chief->id);

        $record = ElectionStateTransition::withoutGlobalScopes()->where('election_id', $election->id)->first();
        $this->assertNotNull($record);
        $this->assertSame('setup_nomination', $record->from_state);
        $this->assertSame('setup_nomination', $record->to_state);
        $this->assertSame('manual', $record->trigger);
        $this->assertSame($chief->id, $record->actor_id);
        $this->assertSame('nomination looks good', $record->reason);
    }

    public function test_complete_nomination_sets_nomination_completed_facts(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->approvedCandidacy($org, $post);
        $this->actingAs($chief);

        $before = now();
        $election->completeNomination('ok', $chief->id);
        $election->refresh();

        $this->assertTrue($election->nomination_completed);
        $this->assertNotNull($election->nomination_completed_at);
        $this->assertTrue($election->nomination_completed_at->gte($before->copy()->startOfSecond()));
    }

    public function test_complete_nomination_auto_sets_voting_window_when_not_previously_configured(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->approvedCandidacy($org, $post);
        $this->actingAs($chief);

        $election->completeNomination('ok', $chief->id);
        $election->refresh();

        $this->assertNotNull($election->voting_starts_at);
        $this->assertNotNull($election->voting_ends_at);
        $this->assertEqualsWithDelta(
            4 * 24 * 60,
            $election->voting_starts_at->diffInMinutes($election->voting_ends_at),
            1
        );
    }

    public function test_complete_nomination_preserves_an_already_configured_voting_window(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->approvedCandidacy($org, $post);
        $this->actingAs($chief);

        $preStart = now()->addDays(10);
        $preEnd = now()->addDays(12);
        $election->update(['voting_starts_at' => $preStart, 'voting_ends_at' => $preEnd]);

        $election->completeNomination('ok', $chief->id);
        $election->refresh();

        $this->assertEquals(0, $preStart->copy()->startOfSecond()->diffInSeconds($election->voting_starts_at));
        $this->assertEquals(0, $preEnd->copy()->startOfSecond()->diffInSeconds($election->voting_ends_at));
    }

    /**
     * The single highest-value test in this slice: completing nomination must
     * never itself set voting_locked, regardless of how stale/elapsed any
     * pre-configured voting window is.
     */
    public function test_complete_nomination_never_sets_voting_locked(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->approvedCandidacy($org, $post);
        $this->actingAs($chief);

        $election->update([
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subMinute(),
        ]);

        $election->completeNomination('ok', $chief->id);
        $election->refresh();

        $this->assertFalse((bool) $election->voting_locked);
    }

    public function test_complete_nomination_does_not_write_legacy_audit(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->approvedCandidacy($org, $post);
        $this->actingAs($chief);

        $election->completeNomination('ok', $chief->id);
        $election->refresh();

        $this->assertEmpty(collect($election->state_audit_log ?? [])->where('action', 'nomination_completed'));
        $this->assertDatabaseMissing('election_audit_logs', [
            'election_id' => $election->id,
            'action' => 'nomination_completed',
        ]);
    }

    public function test_complete_nomination_dispatches_nomination_completed_event_with_transition_actor_and_reason(): void
    {
        Event::fake([NominationCompleted::class]);

        [$org, $election, $post] = $this->buildElectionWithPost();
        $chief = $this->chiefOfficer($org, $election);
        $this->approvedCandidacy($org, $post);
        $this->actingAs($chief);

        $election->completeNomination('great slate', $chief->id);

        Event::assertDispatched(NominationCompleted::class, function (NominationCompleted $event) use ($election, $chief) {
            return $event->election->id === $election->id
                && $event->completedBy === $chief->id
                && $event->reason === 'great slate';
        });
    }

    // ── System path: auto_complete_nomination ────────────────────────────────

    public function test_auto_complete_nomination_is_refused_when_pending_candidacies_exist(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        $this->approvedCandidacy($org, $post);
        $this->pendingCandidacy($org, $post);
        // No auth, no tenant context — exactly console execution.

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('All pending candidacy applications must be approved or rejected');

        $election->transitionTo(Transition::automatic(
            action: 'auto_complete_nomination',
            trigger: TransitionTrigger::GRACE_PERIOD,
            reason: 'Automatic transition after grace period',
        ));
    }

    public function test_auto_complete_nomination_succeeds_with_no_auth_and_no_tenant_context(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        $this->approvedCandidacy($org, $post);
        // Deliberately: no actingAs(), no TenantContext::set() — console context.

        $election->transitionTo(Transition::automatic(
            action: 'auto_complete_nomination',
            trigger: TransitionTrigger::GRACE_PERIOD,
            reason: 'Automatic transition after grace period',
        ));
        $election->refresh();

        $this->assertTrue($election->nomination_completed);
        $this->assertFalse((bool) $election->voting_locked);

        $record = ElectionStateTransition::withoutGlobalScopes()->where('election_id', $election->id)->first();
        $this->assertSame('grace_period', $record->trigger);
        // transitionTo() stores actor_id as null for system-triggered
        // transitions (see the assignment inside transitionTo()) — matching
        // the same convention already used by auto_submit.
        $this->assertNull($record->actor_id);
    }
}
