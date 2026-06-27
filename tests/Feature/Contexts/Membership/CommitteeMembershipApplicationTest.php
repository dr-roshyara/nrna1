<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership;

use App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership\ApplyForCommitteeMembershipHandler;
use App\Contexts\Membership\Application\Membership\Ports\CommitteeAssociationRepositoryPort;
use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Application\Membership\ReviewMembershipApplication\ReviewMembershipApplicationHandler;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Doubles\FakeCommitteeEligibilityPolicy;
use Tests\Doubles\FakeEventBus;
use Tests\Doubles\InMemoryCommitteeAssociationRepository;
use Tests\Doubles\InMemoryMembershipApplicationRepository;
use Tests\TestCase;

final class CommitteeMembershipApplicationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->user = User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'email_verified_at' => now(),
            'region' => 'Bayern',
        ]);

        $this->bindTestDoubles();
    }

    private function bindTestDoubles(): void
    {
        $appRepository = new InMemoryMembershipApplicationRepository();
        $assocRepository = new InMemoryCommitteeAssociationRepository();
        $eventBus = new FakeEventBus();
        $eligibilityPolicy = new FakeCommitteeEligibilityPolicy();
        $eligibilityPolicy->setEligible(true);

        $this->app->instance(MembershipApplicationRepositoryPort::class, $appRepository);
        $this->app->instance(CommitteeAssociationRepositoryPort::class, $assocRepository);
        $this->app->instance(FakeEventBus::class, $eventBus);
        $this->app->instance(CommitteeEligibilityPolicy::class, $eligibilityPolicy);

        $this->app->singleton(ApplyForCommitteeMembershipHandler::class, function ($app) {
            return new ApplyForCommitteeMembershipHandler(
                $app->make(MembershipApplicationRepositoryPort::class),
                $app->make(FakeEventBus::class),
                $app->make(CommitteeEligibilityPolicy::class),
            );
        });

        $this->app->singleton(ReviewMembershipApplicationHandler::class, function ($app) {
            return new ReviewMembershipApplicationHandler(
                $app->make(MembershipApplicationRepositoryPort::class),
                $app->make(CommitteeAssociationRepositoryPort::class),
                $app->make(FakeEventBus::class),
            );
        });
    }

    public function test_apply_for_committee_membership_residence_success(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/organisations/' . $this->organisation->slug . '/committee/membership/apply', [
                'committee_id' => 'committee-1',
                'reason' => 'RESIDENCE',
                'committee_geo_unit_id' => 1,
                'committee_geopath' => '/1',
                'committee_geopath_segments' => json_encode([1]),
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'application_id',
                'message',
            ])
            ->assertJsonPath('message', 'Application submitted successfully');
    }

    public function test_apply_for_committee_membership_exception_with_justification(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/organisations/' . $this->organisation->slug . '/committee/membership/apply', [
                'committee_id' => 'committee-1',
                'reason' => 'EXCEPTION',
                'exception_justification' => 'Special permission granted by board',
                'committee_geo_unit_id' => 2,
                'committee_geopath' => '/2',
                'committee_geopath_segments' => json_encode([2]),
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'application_id',
                'message',
            ]);
    }

    public function test_apply_for_committee_membership_exception_without_justification(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/organisations/' . $this->organisation->slug . '/committee/membership/apply', [
                'committee_id' => 'committee-1',
                'reason' => 'EXCEPTION',
                'exception_justification' => null,
                'committee_geo_unit_id' => 2,
                'committee_geopath' => '/2',
                'committee_geopath_segments' => json_encode([2]),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['exception_justification']);
    }

    public function test_apply_for_committee_membership_manual(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/organisations/' . $this->organisation->slug . '/committee/membership/apply', [
                'committee_id' => 'committee-1',
                'reason' => 'MANUAL',
                'committee_geo_unit_id' => 1,
                'committee_geopath' => '/1',
                'committee_geopath_segments' => json_encode([1]),
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'application_id',
                'message',
            ]);
    }

    public function test_review_application_approve(): void
    {
        $applicationId = $this->createMembershipApplication('MANUAL');

        $response = $this->actingAs($this->user)
            ->postJson('/organisations/' . $this->organisation->slug . '/committee/membership/review', [
                'application_id' => $applicationId,
                'action' => 'APPROVE',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'association' => [
                    'member_id',
                    'committee_id',
                    'association_type',
                    'associated_at',
                    'status',
                ],
                'message',
            ])
            ->assertJsonPath('message', 'Application approved');
    }

    public function test_review_application_reject(): void
    {
        $applicationId = $this->createMembershipApplication('MANUAL');

        $response = $this->actingAs($this->user)
            ->postJson('/organisations/' . $this->organisation->slug . '/committee/membership/review', [
                'application_id' => $applicationId,
                'action' => 'REJECT',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('association', null)
            ->assertJsonPath('message', 'Application rejected');
    }

    public function test_apply_without_authentication(): void
    {
        $response = $this->postJson('/organisations/' . $this->organisation->slug . '/committee/membership/apply', [
            'committee_id' => 'committee-1',
            'reason' => 'RESIDENCE',
            'committee_geo_unit_id' => 1,
            'committee_geopath' => '/1',
            'committee_geopath_segments' => json_encode([1]),
        ]);

        $response->assertStatus(401);
    }

    public function test_apply_without_verified_email(): void
    {
        $unverifiedUser = User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($unverifiedUser)
            ->postJson('/organisations/' . $this->organisation->slug . '/committee/membership/apply', [
                'committee_id' => 'committee-1',
                'reason' => 'RESIDENCE',
                'committee_geo_unit_id' => 1,
                'committee_geopath' => '/1',
                'committee_geopath_segments' => json_encode([1]),
            ]);

        $response->assertStatus(403);
    }

    private function createMembershipApplication(string $reason): string
    {
        $response = $this->actingAs($this->user)
            ->postJson('/organisations/' . $this->organisation->slug . '/committee/membership/apply', [
                'committee_id' => 'committee-1',
                'reason' => $reason,
                'committee_geo_unit_id' => 1,
                'committee_geopath' => '/1',
                'committee_geopath_segments' => json_encode([1]),
            ]);

        return $response->json('application_id');
    }
}
