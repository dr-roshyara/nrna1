<?php

namespace Tests\Feature\Election;

use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-253 — RED: Voter Management filters by Voted (PO 2026-10-06), next to "All statuses".
 *
 * ?voted=voted | not_voted, from codes.has_voted (the participation authority, PBDIGIT-252) — the same parameter
 * and semantics as the read-only list (OrganisationController::voters). The dead election_memberships.has_voted is
 * never consulted.
 *
 *   F1 voted     → only voters with a voted code
 *   F2 not_voted → everyone else, including a voter flagged only in the dead column
 *   F3 the active filter is echoed in `filters.voted`
 *   F4 combines with the status filter
 * Synthetic data only. Participation state only — no ballot data (ADR-T11).
 */
class VoterManagementVotedFilterTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private Election $election;
    private User $chief;
    private User $voted;
    private User $notVoted;
    private User $deadFlagOnly;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
        $this->election = ElectionScenarioFactory::setupAdministration($this->org);
        $this->chief = $this->officer($this->election, 'chief');

        $this->voted = $this->registeredVoter($this->election);
        Code::factory()->voted()->create([
            'organisation_id' => $this->org->id, 'election_id' => $this->election->id, 'user_id' => $this->voted->id,
        ]);
        $this->notVoted = $this->registeredVoter($this->election);
        $this->deadFlagOnly = $this->registeredVoter($this->election);
        ElectionMembership::where('election_id', $this->election->id)->where('user_id', $this->deadFlagOnly->id)
            ->update(['has_voted' => true]);
    }

    private function page(array $query)
    {
        return $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])
            ->get($this->voterRoute('elections.voters.index', $this->election, $query));
    }

    /** @return list<string> */
    private function userIds($response): array
    {
        return collect($response->assertOk()->viewData('page')['props']['voters']['data'])->pluck('user_id')->sort()->values()->all();
    }

    public function test_f1_voted_shows_only_voters_with_a_voted_code(): void
    {
        $this->assertSame([$this->voted->id], $this->userIds($this->page(['voted' => 'voted'])));
    }

    public function test_f2_not_voted_shows_everyone_else_including_the_dead_column_flag(): void
    {
        $expected = collect([$this->notVoted->id, $this->deadFlagOnly->id])->sort()->values()->all();

        $this->assertSame($expected, $this->userIds($this->page(['voted' => 'not_voted'])));
    }

    public function test_f3_the_active_voted_filter_is_echoed(): void
    {
        $this->page(['voted' => 'not_voted'])->assertInertia(fn ($p) => $p->where('filters.voted', 'not_voted'));
    }

    public function test_f4_the_voted_filter_combines_with_the_status_filter(): void
    {
        ElectionMembership::where('election_id', $this->election->id)->where('user_id', $this->notVoted->id)
            ->update(['status' => 'inactive']);

        $this->assertSame([$this->deadFlagOnly->id], $this->userIds($this->page(['voted' => 'not_voted', 'status' => 'active'])));
    }
}
