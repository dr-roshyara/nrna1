<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class VoterImportStateGateTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        \App\Services\TenantContext::set($this->org->id);

        $this->officer = User::factory()->create(['email_verified_at' => now()]);

        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->officer->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);

        session(['current_organisation_id' => $this->org->id]);
    }

    // ── helper ────────────────────────────────────────────────────────────────

    private function makeElectionInState(string $state): Election
    {
        $election = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['state' => $state, 'voter_source_strategy' => 'election_only']);

        ElectionOfficer::create([
            'id'              => (string) Str::uuid(),
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
            'user_id'         => $this->officer->id,
            'role'            => 'chief',
            'status'          => 'active',
        ]);

        return $election;
    }

    // ── create (page) ────────────────────────────────────────────────────────

    /** @test */
    public function import_page_is_accessible_in_setup_administration_state(): void
    {
        $election = $this->makeElectionInState('setup_administration');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertOk();
    }

    /** @test */
    public function import_page_is_forbidden_in_draft_state(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }

    /** @test */
    public function import_page_is_forbidden_in_voting_active_state(): void
    {
        $election = $this->makeElectionInState('voting_active');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }

    /** @test */
    public function import_page_is_forbidden_in_approved_state(): void
    {
        $election = $this->makeElectionInState('approved');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }

    // ── template download ─────────────────────────────────────────────────────

    /** @test */
    public function template_download_is_forbidden_outside_setup_administration(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.template', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertForbidden();
    }

    // ── preview ───────────────────────────────────────────────────────────────

    /** @test */
    public function preview_is_forbidden_outside_setup_administration(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import.preview', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]), [
                'file' => UploadedFile::fake()->create('voters.csv', 1, 'text/csv'),
            ]);

        $response->assertForbidden();
    }

    // ── import ────────────────────────────────────────────────────────────────

    /** @test */
    public function import_action_is_forbidden_outside_setup_administration(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('elections.voters.import', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]), [
                'file'      => UploadedFile::fake()->create('voters.csv', 1, 'text/csv'),
                'confirmed' => '1',
            ]);

        $response->assertForbidden();
    }

    // ── tutorial (always accessible) ─────────────────────────────────────────

    /** @test */
    public function tutorial_is_accessible_regardless_of_state(): void
    {
        $election = $this->makeElectionInState('draft');

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.tutorial', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertOk();
    }

    // ── edge cases ────────────────────────────────────────────────────────────

    /** @test */
    public function unauthorized_users_cannot_access_import_even_in_correct_state(): void
    {
        $election = $this->makeElectionInState('setup_administration');
        $stranger = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($stranger)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.voters.import.create', [
                'organisation' => $this->org->slug,
                'election'     => $election->slug,
            ]));

        $response->assertStatus(302);
    }
}
