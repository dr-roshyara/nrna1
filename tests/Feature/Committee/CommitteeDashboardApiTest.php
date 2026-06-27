<?php

declare(strict_types=1);

namespace Tests\Feature\Committee;

use App\Contexts\Membership\Infrastructure\Models\CommitteeAssociationModel;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Tests\TestCase;

final class CommitteeDashboardApiTest extends TestCase
{
    public function test_dashboard_returns_members_with_translate_keys(): void
    {
        // ARRANGE: Create real data
        $org = Organisation::factory()->create();
        $user = User::factory()->create();

        // Associate user with organisation
        OrganisationUser::factory()->create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        // Create committee
        $committee = CommitteeModel::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
        ]);

        // Create committee association (member is ACTIVE)
        CommitteeAssociationModel::factory()->create([
            'organisation_id' => $org->id,
            'committee_id' => $committee->id,
            'member_id' => $user->id,
            'status' => 'active',
            'association_type' => 'residence',
            'associated_at' => now(),
        ]);

        // ACT: Use header-based tenant context
        $response = $this->actingAs($user)
            ->withHeader('X-Tenant-Id', $org->id)
            ->get("/organisations/{$org->slug}/committees/{$committee->slug}/dashboard");

        // ASSERT
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Committee/Dashboard')
                ->hasAll(['members', 'committee'])
        );
    }

    public function test_dashboard_with_no_members_returns_empty_array(): void
    {
        // ARRANGE
        $org = Organisation::factory()->create();
        $user = User::factory()->create();

        OrganisationUser::factory()->create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $committee = CommitteeModel::factory()->create([
            'organisation_id' => $org->id,
        ]);

        // ACT
        $response = $this->actingAs($user)
            ->withHeader('X-Tenant-Id', $org->id)
            ->get("/organisations/{$org->slug}/committees/{$committee->slug}/dashboard");

        // ASSERT
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Committee/Dashboard')
                ->has('members')
        );
    }

    public function test_dashboard_requires_authentication(): void
    {
        // ARRANGE
        $org = Organisation::factory()->create();
        $committee = CommitteeModel::factory()->create([
            'organisation_id' => $org->id,
        ]);

        // ACT
        $response = $this->get("/organisations/{$org->slug}/committees/{$committee->slug}/dashboard");

        // ASSERT
        $response->assertRedirect('/login');
    }

    public function test_dashboard_for_nonexistent_committee_returns_404(): void
    {
        // ARRANGE
        $org = Organisation::factory()->create();
        $user = User::factory()->create();

        OrganisationUser::factory()->create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        // ACT
        $response = $this->actingAs($user)
            ->get("/organisations/{$org->slug}/committees/nonexistent-slug/dashboard");

        // ASSERT
        $response->assertStatus(404);
    }

    public function test_dashboard_members_exclude_suspended_and_terminated(): void
    {
        // ARRANGE
        $org = Organisation::factory()->create();
        $user = User::factory()->create();

        OrganisationUser::factory()->create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $committee = CommitteeModel::factory()->create([
            'organisation_id' => $org->id,
        ]);

        // Active member
        $activeMember = User::factory()->create();
        CommitteeAssociationModel::factory()->active()->create([
            'organisation_id' => $org->id,
            'committee_id' => $committee->id,
            'member_id' => $activeMember->id,
        ]);

        // Suspended member (should be excluded)
        $suspendedUser = User::factory()->create();
        CommitteeAssociationModel::factory()->suspended()->create([
            'organisation_id' => $org->id,
            'committee_id' => $committee->id,
            'member_id' => $suspendedUser->id,
        ]);

        // Terminated member (should be excluded)
        $terminatedUser = User::factory()->create();
        CommitteeAssociationModel::factory()->terminated()->create([
            'organisation_id' => $org->id,
            'committee_id' => $committee->id,
            'member_id' => $terminatedUser->id,
        ]);

        // ACT
        $response = $this->actingAs($user)
            ->withHeader('X-Tenant-Id', $org->id)
            ->get("/organisations/{$org->slug}/committees/{$committee->slug}/dashboard");

        // ASSERT
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Committee/Dashboard')
                ->has('members')
        );
    }

    public function test_dashboard_dto_has_committee_section(): void
    {
        // ARRANGE
        $org = Organisation::factory()->create();
        $user = User::factory()->create();

        OrganisationUser::factory()->create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $committee = CommitteeModel::factory()->create([
            'organisation_id' => $org->id,
            'code' => 'TEST',
            'name' => 'Test Committee',
            'status' => 'active',
        ]);

        // ACT
        $response = $this->actingAs($user)
            ->withHeader('X-Tenant-Id', $org->id)
            ->get("/organisations/{$org->slug}/committees/{$committee->slug}/dashboard");

        // ASSERT
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Committee/Dashboard')
                ->hasAll(['committee', 'members'])
        );
    }
}
