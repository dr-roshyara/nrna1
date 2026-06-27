# Testing Guide: Unit, Integration, Feature

This guide covers TDD patterns for the Membership Context.

---

## 🎯 Testing Strategy Overview

```
Unit Tests (Fast, In-Memory)
├─ Domain model logic
├─ Value object validation
└─ Use case orchestration

Integration Tests (With Database)
├─ Repository persistence
├─ Eloquent scoping
└─ Transaction behavior

Feature Tests (Full Stack)
├─ HTTP endpoints
├─ Authentication
└─ Tenant isolation
```

---

## 🧪 Unit Tests: Domain & Application Layer

### Directory Structure

```
tests/Unit/Contexts/Membership/
├── Domain/
│   ├── Committee/
│   │   └── CommitteeTest.php
│   ├── ValueObjects/
│   │   ├── CommitteeTypeTest.php
│   │   ├── RolePathTest.php
│   │   └── GeoReferenceTest.php
│   └── Strategies/
│       └── CommitteeStructureTest.php
│
└── Application/
    └── Committee/
        ├── GetCommitteeDashboardTest.php
        ├── CreateCommitteeTest.php
        └── AssignMemberToCommitteeTest.php
```

### Test Domain Model

**File:** `tests/Unit/Contexts/Membership/Domain/Committee/CommitteeTest.php`

```php
<?php

namespace Tests\Unit\Contexts\Membership\Domain\Committee;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeCode;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Membership\Domain\Events\CommitteeFormed;

class CommitteeTest extends TestCase
{
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantId = TenantId::fromString('test-tenant-uuid');
    }

    public function test_can_create_central_committee()
    {
        $committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Central Executive'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,
            geoReference: null
        );

        $this->assertTrue($committee->getStatus()->isActive());
        $this->assertTrue($committee->type()->isCentral());
        $this->assertNull($committee->getOperationalGeoReference());
    }

    public function test_central_committee_cannot_have_geography()
    {
        $this->expectException(InvalidGeographyException::class);

        Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Central Executive'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,
            geoReference: GeoReference::fromString('np.3.15')  // ← Should fail
        );
    }

    public function test_geographic_committee_requires_geography()
    {
        $this->expectException(InvalidGeographyException::class);

        Committee::form(
            type: CommitteeType::district(),
            name: CommitteeName::of('Kathmandu District'),
            code: CommitteeCode::of('KATH-DIST'),
            tenantId: $this->tenantId,
            geoReference: null  // ← Should fail for geographic
        );
    }

    public function test_committee_formation_records_event()
    {
        $committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Central Executive'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,
            geoReference: null
        );

        $events = $committee->pullEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(CommitteeFormed::class, $events[0]);
    }

    public function test_can_assign_member_to_committee()
    {
        $committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Central Executive'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,
            geoReference: null
        );

        $assignment = $committee->assignMember(
            memberId: MemberId::generate(),
            rolePath: RolePath::chairperson(),
            nominationType: NominationType::elected()
        );

        $this->assertNotNull($assignment);
        $this->assertEquals(1, count($committee->getAssignments()));
    }

    public function test_central_committee_enforces_one_chairperson()
    {
        $committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Central Executive'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,
            geoReference: null
        );

        // Assign first chairperson
        $committee->assignMember(
            memberId: MemberId::generate(),
            rolePath: RolePath::chairperson(),
            nominationType: NominationType::elected()
        );

        // Try to assign second chairperson — should fail
        $this->expectException(InvalidMemberAssignmentException::class);

        $committee->assignMember(
            memberId: MemberId::generate(),
            rolePath: RolePath::chairperson(),
            nominationType: NominationType::elected()
        );
    }

    public function test_geographic_committee_member_must_be_within_boundary()
    {
        $committee = Committee::form(
            type: CommitteeType::district(),
            name: CommitteeName::of('Kathmandu District'),
            code: CommitteeCode::of('KATH-DIST'),
            tenantId: $this->tenantId,
            geoReference: GeoReference::fromString('np.3.15')  // Province 3, District 15
        );

        // Member from different district
        $memberGeo = GeoReference::fromString('np.3.16');  // Different district

        $this->expectException(InvalidMemberGeographyException::class);

        $committee->assignMember(
            memberId: MemberId::generate(),
            rolePath: RolePath::chairperson(),
            nominationType: NominationType::elected(),
            memberGeography: $memberGeo
        );
    }

    public function test_can_deactivate_active_committee()
    {
        $committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Central Executive'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,
            geoReference: null
        );

        $committee->deactivate('Test reason');

        $this->assertTrue($committee->getStatus()->isInactive());
    }

    public function test_cannot_deactivate_already_inactive_committee()
    {
        $committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Central Executive'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,
            geoReference: null
        );

        $committee->deactivate();

        $this->expectException(InvalidCommitteeStatusException::class);
        $committee->deactivate();  // ← Fails
    }
}
```

### Test Use Cases (Application)

**File:** `tests/Unit/Contexts/Membership/Application/Committee/CreateCommitteeTest.php`

```php
<?php

namespace Tests\Unit\Contexts\Membership\Application\Committee;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Application\Committee\CreateCommittee;
use App\Contexts\Membership\Application\Committee\DTOs\CreateCommitteeCommand;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Shared\Domain\Events\EventBus;
use Illuminate\Support\Facades\DB;

class CreateCommitteeTest extends TestCase
{
    private CreateCommittee $useCase;
    private CommitteeRepositoryInterface $repositoryMock;
    private EventBus $eventBusMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(CommitteeRepositoryInterface::class);
        $this->eventBusMock = $this->createMock(EventBus::class);

        $this->useCase = new CreateCommittee(
            $this->repositoryMock,
            $this->eventBusMock
        );
    }

    public function test_creates_central_committee()
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('saveForTenant');

        $this->eventBusMock
            ->expects($this->once())
            ->method('dispatchAll');

        $id = $this->useCase->execute(new CreateCommitteeCommand(
            tenantId: TenantId::fromString('tenant-uuid'),
            type: CommitteeType::central(),
            name: 'Central Executive Committee',
            code: 'CENTRAL-001',
            geoReference: null
        ));

        $this->assertInstanceOf(CommitteeId::class, $id);
    }

    public function test_throws_exception_for_invalid_geography()
    {
        $this->expectException(InvalidGeographyException::class);

        $this->useCase->execute(new CreateCommitteeCommand(
            tenantId: TenantId::fromString('tenant-uuid'),
            type: CommitteeType::central(),
            name: 'Central Executive',
            code: 'CENTRAL-001',
            geoReference: 'np.3.15'  // ← Invalid for central
        ));
    }

    public function test_uses_transaction()
    {
        DB::shouldReceive('transaction')
            ->once()
            ->withArgs(function ($callback) {
                return is_callable($callback);
            })
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->repositoryMock->method('saveForTenant');
        $this->eventBusMock->method('dispatchAll');

        $this->useCase->execute(new CreateCommitteeCommand(...));
    }

    public function test_dispatches_events_after_transaction()
    {
        $captured = [];

        $this->eventBusMock
            ->expects($this->once())
            ->method('dispatchAll')
            ->willReturnCallback(function ($events) use (&$captured) {
                $captured = $events;
            });

        $this->useCase->execute(new CreateCommitteeCommand(...));

        // Verify CommitteeFormed event was dispatched
        $this->assertContains(
            CommitteeFormed::class,
            array_map(get_class(...), $captured)
        );
    }
}
```

---

## 🔗 Integration Tests: With Database

### Directory Structure

```
tests/Integration/Contexts/Membership/
└── Repositories/
    ├── EloquentCommitteeRepositoryTest.php
    └── CommitteeTenantIsolationTest.php
```

### Test Repository Persistence

**File:** `tests/Integration/Contexts/Membership/Repositories/EloquentCommitteeRepositoryTest.php`

```php
<?php

namespace Tests\Integration\Contexts\Membership\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;

class EloquentCommitteeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeRepositoryInterface $repo;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repo = app(CommitteeRepositoryInterface::class);
        $this->tenantId = TenantId::fromString('test-tenant-uuid');
    }

    public function test_save_and_find_committee()
    {
        $committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Central Executive'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,
            geoReference: null
        );

        $this->repo->saveForTenant($committee);

        $found = $this->repo->findForTenant($committee->getId(), $this->tenantId);

        $this->assertNotNull($found);
        $this->assertEquals($committee->getId()->value(), $found->getId()->value());
    }

    public function test_find_returns_null_for_unknown_committee()
    {
        $found = $this->repo->findForTenant(
            CommitteeId::generate(),
            $this->tenantId
        );

        $this->assertNull($found);
    }

    public function test_find_returns_null_for_different_tenant()
    {
        $otherTenant = TenantId::fromString('other-tenant-uuid');

        $committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Central Executive'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,  // Created for $this->tenantId
            geoReference: null
        );

        $this->repo->saveForTenant($committee);

        // Try to find from different tenant
        $found = $this->repo->findForTenant($committee->getId(), $otherTenant);

        $this->assertNull($found);  // ← Tenant isolation
    }

    public function test_find_all_returns_only_tenant_committees()
    {
        $tenantA = TenantId::fromString('tenant-a-uuid');
        $tenantB = TenantId::fromString('tenant-b-uuid');

        // Create committees for both tenants
        $committeeA = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Committee A'),
            code: CommitteeCode::of('A-001'),
            tenantId: $tenantA,
            geoReference: null
        );

        $committeeB = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Committee B'),
            code: CommitteeCode::of('B-001'),
            tenantId: $tenantB,
            geoReference: null
        );

        // Save both
        $this->repo->saveForTenant($committeeA);
        $this->repo->saveForTenant($committeeB);

        // Query as tenant A
        session(['current_organisation_id' => $tenantA->value()]);
        $committees = $this->repo->findAllForTenant($tenantA);

        // Should only see committee A
        $this->assertCount(1, $committees);
        $this->assertEquals('Committee A', $committees[0]->getName()->value());
    }

    public function test_save_updates_existing_committee()
    {
        $committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Original Name'),
            code: CommitteeCode::of('CENTRAL-001'),
            tenantId: $this->tenantId,
            geoReference: null
        );

        $this->repo->saveForTenant($committee);
        $original = $this->repo->findForTenant($committee->getId(), $this->tenantId);
        $this->assertEquals('Original Name', $original->getName()->value());

        // Update
        $committee->updateName(CommitteeName::of('Updated Name'));
        $this->repo->saveForTenant($committee);

        $updated = $this->repo->findForTenant($committee->getId(), $this->tenantId);
        $this->assertEquals('Updated Name', $updated->getName()->value());
    }
}
```

---

## 🌐 Feature Tests: Full Stack HTTP

### Directory Structure

```
tests/Feature/Committee/
├── CommitteeDashboardTest.php
├── CreateCommitteeTest.php
└── TenantIsolationTest.php
```

### Test HTTP Endpoints

**File:** `tests/Feature/Committee/CommitteeDashboardTest.php`

```php
<?php

namespace Tests\Feature\Committee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;

class CommitteeDashboardTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->user = User::factory()
            ->for($this->organisation)
            ->create();
    }

    public function test_authenticated_user_can_view_committee_dashboard()
    {
        $committee = Committee::factory()
            ->for($this->organisation)
            ->create();

        $this->actingAs($this->user);

        $response = $this->get("/committee/{$committee->id}/dashboard");

        $response->assertStatus(200);
        $response->assertSee($committee->name);
    }

    public function test_unauthenticated_user_redirected_to_login()
    {
        $committee = Committee::factory()
            ->for($this->organisation)
            ->create();

        $response = $this->get("/committee/{$committee->id}/dashboard");

        $response->assertRedirect('/login');
    }

    public function test_user_cannot_view_other_organization_committee()
    {
        $otherOrg = Organisation::factory()->create();
        $committee = Committee::factory()
            ->for($otherOrg)
            ->create();

        $this->actingAs($this->user);

        $response = $this->get("/committee/{$committee->id}/dashboard");

        $response->assertStatus(404);
    }

    public function test_dashboard_displays_committee_info()
    {
        $committee = Committee::factory()
            ->for($this->organisation)
            ->state([
                'name' => 'Kathmandu District Committee',
                'type' => 'district',
                'code' => 'KATH-DIST',
            ])
            ->create();

        $this->actingAs($this->user);

        $response = $this->get("/committee/{$committee->id}/dashboard");

        $response->assertSee('Kathmandu District Committee');
        $response->assertSee('District Committee');
        $response->assertSee('KATH-DIST');
    }

    public function test_dashboard_displays_sub_committees()
    {
        $parent = Committee::factory()
            ->for($this->organisation)
            ->state([
                'type' => 'province',
                'operational_geo_reference' => 'np.3',
            ])
            ->create();

        $child = Committee::factory()
            ->for($this->organisation)
            ->state([
                'type' => 'district',
                'operational_geo_reference' => 'np.3.15',
            ])
            ->create();

        $this->actingAs($this->user);

        $response = $this->get("/committee/{$parent->id}/dashboard");

        $response->assertSee($child->name);
    }

    public function test_dashboard_displays_member_assignments()
    {
        $committee = Committee::factory()
            ->for($this->organisation)
            ->create();

        $assignment = CommitteeAssignment::factory()
            ->for($committee)
            ->create([
                'role_path' => '1.0.0',  // Chairperson
            ]);

        $this->actingAs($this->user);

        $response = $this->get("/committee/{$committee->id}/dashboard");

        $response->assertSee($assignment->member->name);
        $response->assertSee('Chairperson');
    }
}
```

### Test Tenant Isolation

**File:** `tests/Feature/Committee/TenantIsolationTest.php`

```php
<?php

namespace Tests\Feature\Committee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_other_organizations_data()
    {
        $orgA = Organisation::factory()->create();
        $orgB = Organisation::factory()->create();

        $userA = User::factory()->for($orgA)->create();
        $userB = User::factory()->for($orgB)->create();

        $committeeA = Committee::factory()->for($orgA)->create();
        $committeeB = Committee::factory()->for($orgB)->create();

        // User A tries to view committee B
        $this->actingAs($userA);
        $response = $this->get("/committee/{$committeeB->id}/dashboard");
        $response->assertStatus(404);

        // User A can view their own committee
        $response = $this->get("/committee/{$committeeA->id}/dashboard");
        $response->assertStatus(200);
    }

    public function test_switching_organization_changes_context()
    {
        $orgA = Organisation::factory()->create();
        $orgB = Organisation::factory()->create();

        $user = User::factory()->for($orgA)->create();
        $committeeA = Committee::factory()->for($orgA)->create();
        $committeeB = Committee::factory()->for($orgB)->create();

        $this->actingAs($user);

        // User in org A sees committee A
        $response = $this->get("/committee/{$committeeA->id}/dashboard");
        $response->assertStatus(200);

        // User in org A doesn't see committee B
        $response = $this->get("/committee/{$committeeB->id}/dashboard");
        $response->assertStatus(404);
    }
}
```

---

## 🧪 Test Utilities & Factories

### Create Test Fixtures

```php
// tests/TestHelpers/CommitteeTestHelper.php
final class CommitteeTestHelper
{
    public static function createCentralCommittee(
        TenantId $tenantId,
        string $name = 'Test Central',
        string $code = 'TEST-CENTRAL'
    ): Committee {
        return Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of($name),
            code: CommitteeCode::of($code),
            tenantId: $tenantId,
            geoReference: null
        );
    }

    public static function createDistrictCommittee(
        TenantId $tenantId,
        string $geo = 'np.3.15'
    ): Committee {
        return Committee::form(
            type: CommitteeType::district(),
            name: CommitteeName::of('Test District'),
            code: CommitteeCode::of('TEST-DIST'),
            tenantId: $tenantId,
            geoReference: GeoReference::fromString($geo)
        );
    }
}
```

---

## 📋 Test Checklist

Before submitting a feature:

- [ ] **Unit tests pass** — `php artisan test tests/Unit`
- [ ] **Integration tests pass** — `php artisan test tests/Integration`
- [ ] **Feature tests pass** — `php artisan test tests/Feature`
- [ ] **All tests pass** — `php artisan test`
- [ ] **Tenant isolation tested** — Tests with multiple `TenantId` values
- [ ] **Exception cases covered** — Test both happy path and errors
- [ ] **Business rules verified** — Domain invariants tested
- [ ] **Database transactions verified** — Tests confirm transaction boundaries
- [ ] **Events tested** — Verify events are recorded and dispatched

---

**Next:** Read [07_WORKFLOWS.md](./07_WORKFLOWS.md) for complete end-to-end examples
