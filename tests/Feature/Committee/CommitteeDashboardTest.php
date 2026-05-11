<?php

declare(strict_types=1);

namespace Tests\Feature\Committee;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Models\Organisation;
use App\Models\User;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CommitteeDashboardTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeRepositoryInterface $repository;
    private TenantId $tenantId;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(CommitteeRepositoryInterface::class);

        // Create user with its own unique organisation (not platform default)
        $organisation = Organisation::factory()->create();
        $this->user = User::factory()
            ->forOrganisation($organisation)
            ->create();
        $this->tenantId = TenantId::fromString($this->user->organisation_id);
    }

    public function test_auth_user_can_view_own_committee_dashboard(): void
    {
        $committeeId = CommitteeId::generate();
        $committee = Committee::createCentral(
            $committeeId,
            $this->tenantId,
            'Central Committee',
            'CENTRAL-001'
        );

        // Fixture setup (acceptable for feature tests)
        $this->repository->saveForTenant($committee);

        // Fetch the persisted committee to get its slug
        $committeeModel = \App\Contexts\Membership\Infrastructure\Models\CommitteeModel::withoutGlobalScopes()
            ->find($committeeId->value());

        $this->assertNotNull($committeeModel, 'Committee not found in database');

        $response = $this->actingAs($this->user)
            ->withSession(['current_organisation_id' => $this->tenantId->value()])
            ->get(route('committee.dashboard', [
                'organisation' => $this->user->organisation,
                'committee' => $committeeModel->slug
            ]));

        $response->assertStatus(200);

        $response->assertInertia(fn (AssertableInertia $page) =>
            $page->component('Committee/Dashboard')
                 ->has('committee')
                 ->where('committee.id', $committeeId->value())
                 ->where('committee.name', 'Central Committee')
                 ->where('committee.code', 'CENTRAL-001')
                 ->where('committee.type', 'central')
                 ->where('committee.geo_reference', null)
        );

        // Verify persistence
        $this->assertDatabaseHas('committees', [
            'id' => $committeeId->value(),
            'organisation_id' => $this->tenantId->value(),
            'name' => 'Central Committee',
        ]);
    }

    public function test_cannot_view_other_tenants_committee_returns_404(): void
    {
        // Create another user with a DIFFERENT organisation to test isolation
        $otherOrganisation = Organisation::factory()->create();
        $otherUser = User::factory()
            ->forOrganisation($otherOrganisation)
            ->create();
        $otherTenantId = TenantId::fromString($otherUser->organisation_id);
        $committeeId = CommitteeId::generate();

        $committee = Committee::createCentral(
            $committeeId,
            $otherTenantId,
            'Other Tenant Committee',
            'OTHER-CENTRAL'
        );

        // Save in other tenant context
        session(['current_organisation_id' => $otherTenantId->value()]);
        $this->repository->saveForTenant($committee);

        // Verify committee exists in database with other tenant's organisation_id
        $this->assertDatabaseHas('committees', [
            'id' => $committeeId->value(),
            'organisation_id' => $otherTenantId->value(),
        ]);

        // ✅ Clear global session before HTTP request to avoid interference
        // The HTTP request's withSession() should be the only session context
        session()->forget('current_organisation_id');

        // Fetch the persisted committee to get its slug
        $committeeModel = \App\Contexts\Membership\Infrastructure\Models\CommitteeModel::withoutGlobalScopes()
            ->find($committeeId->value());

        $this->assertNotNull($committeeModel, 'Committee not found in database');

        // Try to access with different tenant context
        // GlobalScope should filter out the committee because it belongs to other tenant
        $response = $this->actingAs($this->user)
            ->withSession(['current_organisation_id' => $this->tenantId->value()])
            ->get(route('committee.dashboard', [
                'organisation' => $this->user->organisation,
                'committee' => $committeeModel->slug
            ]));

        // Should not find committee - different tenant, so 404
        $response->assertStatus(404);

        // Verify committee still exists in DB (it wasn't deleted, just filtered)
        $this->assertDatabaseHas('committees', [
            'id' => $committeeId->value(),
            'organisation_id' => $otherTenantId->value(),
        ]);

        // Verify our user's tenant can't see it
        $this->assertDatabaseMissing('committees', [
            'id' => $committeeId->value(),
            'organisation_id' => $this->tenantId->value(),
        ]);
    }

    public function test_unauthenticated_user_cannot_view_dashboard(): void
    {
        $response = $this->get(route('committee.dashboard', [
            'organisation' => $this->user->organisation,
            'committee' => 'test-committee'
        ]));

        $response->assertRedirect(route('login'));
    }
}
