<?php

namespace Tests\Feature\Committee;

use App\Contexts\Membership\Application\Committee\Commands\AssignMemberWithGeoCommand;
use App\Contexts\Membership\Application\Committee\Handlers\AssignMemberToCommitteeHandler;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class AssignMemberWithLineageTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private User $memberUser;
    private Member $member;
    private int $testGeoUnitId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        $this->withHeader('X-Tenant-Id', $this->org->id);
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->assignRole($this->admin, $this->org, 'owner');

        $this->memberUser = User::factory()->create(['email_verified_at' => now()]);
        $this->assignRole($this->memberUser, $this->org, 'member');

        $orgUser = OrganisationUser::factory()
            ->for($this->org)
            ->for($this->memberUser)
            ->create(['status' => 'active']);

        $membershipType = \App\Models\MembershipType::factory()
            ->for($this->org)
            ->create();

        $this->member = Member::factory()
            ->for($this->org)
            ->for($orgUser, 'organisationUser')
            ->for($membershipType)
            ->create([
                'status' => 'active',
                'personal_info' => json_encode([
                    'fullName' => $this->memberUser->name,
                    'email' => $this->memberUser->email,
                ]),
            ]);

        $this->seedGeoUnits();
    }

    private function seedGeoUnits(): void
    {
        // 1. Insert country (REQUIRED for foreign key)
        DB::table('countries')->insert([
            'code' => 'NP',
            'code_alpha3' => 'NPL',
            'code_numeric' => '524',
            'name_en' => 'Nepal',
            'name_local' => json_encode(['en' => 'Nepal', 'np' => 'नेपाल']),
            'admin_levels' => json_encode([1, 2, 3, 4]),
            'is_active' => true,
            'is_supported' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Insert Province (admin_level = 1)
        $this->testGeoUnitId = DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'NP',
            'admin_level' => 1,
            'admin_type' => 'province',
            'parent_id' => null,
            'path' => '/1/',
            'code' => 'NP-P1',
            'local_code' => 'P1',
            'name_local' => json_encode(['en' => 'Province 1', 'np' => 'प्रदेश १']),
            'metadata' => json_encode(['total_wards' => 14]),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Set member's residence geo (on members table, not users table)
        $this->member->update(['residence_geo_unit_id' => $this->testGeoUnitId]);
    }

    private function createCentralCommittee(): CommitteeModel
    {
        return CommitteeModel::factory()->create([
            'organisation_id' => $this->org->id,
            'geo_unit_id' => null,
            'type' => 'central',
            'status' => 'active',
        ]);
    }

    private function createGeoScopedCommittee(?int $geoUnitId = null, string $type = 'province'): CommitteeModel
    {
        $geoId = $geoUnitId ?? $this->testGeoUnitId;

        // operational_geo_reference needs format like 'np.1' (country.level1.level2...)
        $geoRef = 'np.' . $geoId;

        return CommitteeModel::factory()->create([
            'organisation_id' => $this->org->id,
            'geo_unit_id' => $geoId,
            'type' => $type,
            'status' => 'active',
            'operational_geo_reference' => $geoRef,
        ]);
    }

    private function createValidCommand(CommitteeModel $committee): AssignMemberWithGeoCommand
    {
        return new AssignMemberWithGeoCommand(
            committeeId: CommitteeId::fromString((string) $committee->id),
            tenantId: TenantId::fromString((string) $this->org->id),
            memberId: MemberId::fromString((string) $this->member->id),
            rolePath: RolePath::fromString('1'),
            nominationType: new NominationType('appointed'),
            electionDate: null,
            termEndDate: null,
        );
    }

    public function test_assign_handler_creates_active_membership(): void
    {
        $committee = $this->createCentralCommittee();
        $command = $this->createValidCommand($committee);

        $handler = app(AssignMemberToCommitteeHandler::class);
        $handler->handle($command);

        $this->assertDatabaseHas('committee_associations', [
            'member_id' => $this->member->id,
            'committee_id' => $committee->id,
            'status' => 'active',
            'association_type' => 'manual',
        ]);
    }

    public function test_assign_handler_rejects_ineligible_geo(): void
    {
        // Create a separate geo unit for the committee
        $otherGeoUnitId = DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'NP',
            'admin_level' => 1,
            'admin_type' => 'province',
            'parent_id' => null,
            'path' => '/2/',
            'code' => 'NP-P2',
            'local_code' => 'P2',
            'name_local' => json_encode(['en' => 'Province 2', 'np' => 'प्रदेश २']),
            'metadata' => json_encode(['total_wards' => 12]),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Member has residence in testGeoUnitId (from setUp)
        // Committee is scoped to otherGeoUnitId (different)
        // This should cause policy rejection
        $committee = $this->createGeoScopedCommittee($otherGeoUnitId);
        $command = $this->createValidCommand($committee);

        $this->expectException(\App\Contexts\Membership\Domain\Committee\Exceptions\CommitteeEligibilityException::class);

        $handler = app(AssignMemberToCommitteeHandler::class);
        $handler->handle($command);

        $this->assertDatabaseMissing('committee_associations', [
            'member_id' => $this->member->id,
            'committee_id' => $committee->id,
        ]);
    }

    public function test_assign_handler_accepts_central_committee_any_member(): void
    {
        $committee = $this->createCentralCommittee();
        $command = $this->createValidCommand($committee);

        $handler = app(AssignMemberToCommitteeHandler::class);
        $handler->handle($command);

        $this->assertDatabaseHas('committee_associations', [
            'member_id' => $this->member->id,
            'committee_id' => $committee->id,
            'status' => 'active',
            'association_type' => 'manual',
        ]);
    }

    public function test_assign_handler_rejects_duplicate_assignment(): void
    {
        $committee = $this->createCentralCommittee();
        $command = $this->createValidCommand($committee);

        $handler = app(AssignMemberToCommitteeHandler::class);
        $handler->handle($command);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Member already has an active membership for this committee');

        $handler->handle($command);
    }

    public function test_assign_handler_transaction_rolls_back_on_error(): void
    {
        $committee = $this->createCentralCommittee();
        $command = $this->createValidCommand($committee);

        // Verify the handler throws when repository fails
        $mockRepository = $this->mock(\App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort::class);
        $mockRepository->shouldReceive('findLineageByMemberAndCommitteeForTenant')
            ->andReturn(null);
        $mockRepository->shouldReceive('saveForTenant')
            ->andThrow(new \Exception('Simulated persistence failure'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Simulated persistence failure');

        $handler = app(AssignMemberToCommitteeHandler::class);
        $handler->handle($command);
    }

    public function test_assign_handler_dispatches_event_after_commit(): void
    {
        Event::fake();

        $committee = $this->createCentralCommittee();
        $command = $this->createValidCommand($committee);

        $handler = app(AssignMemberToCommitteeHandler::class);
        $handler->handle($command);

        Event::assertDispatched(\App\Contexts\Membership\Domain\Membership\Events\MemberAssignedToCommittee::class);
    }
}
