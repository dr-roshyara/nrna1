<?php

declare(strict_types=1);

namespace Tests\Feature\Committee;

use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CommitteeDashboardHttpTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->user = User::factory()
            ->forOrganisation($this->organisation)
            ->create();
    }

    public function test_auth_user_can_view_own_committee_dashboard(): void
    {
        $committee = CommitteeModel::factory()->create([
            'organisation_id' => $this->organisation->id,
            'geo_unit_id' => null,
            'type' => 'central',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)
            ->withSession(['current_organisation_id' => $this->organisation->id])
            ->get(route('committee.dashboard', [
                'organisation' => $this->organisation,
                'committee' => $committee->slug,
            ]));

        $response->assertStatus(200);

        $response->assertInertia(fn (AssertableInertia $page) =>
            $page->component('Committee/Dashboard')
                 ->has('committee')
                 ->where('committee.id', (string) $committee->id)
                 ->where('committee.name', $committee->name)
                 ->where('committee.code', $committee->code)
                 ->where('committee.type', 'central')
        );
    }

    public function test_cannot_view_other_tenants_committee_returns_404(): void
    {
        $otherOrganisation = Organisation::factory()->create();
        $otherUser = User::factory()
            ->forOrganisation($otherOrganisation)
            ->create();

        $committee = CommitteeModel::factory()->create([
            'organisation_id' => $otherOrganisation->id,
            'geo_unit_id' => null,
            'type' => 'central',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)
            ->withSession(['current_organisation_id' => $this->organisation->id])
            ->get(route('committee.dashboard', [
                'organisation' => $this->organisation,
                'committee' => $committee->slug,
            ]));

        $response->assertStatus(404);
    }

    public function test_unauthenticated_user_cannot_view_dashboard(): void
    {
        $response = $this->get(route('committee.dashboard', [
            'organisation' => $this->organisation,
            'committee' => 'test-committee',
        ]));

        $response->assertRedirect(route('login'));
    }
}
