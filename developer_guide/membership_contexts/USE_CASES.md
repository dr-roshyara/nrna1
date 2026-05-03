# Use Cases and Orchestration

## Use Case Pattern

A use case orchestrates domain logic and coordinates between multiple aggregates and repositories.

### Structure

```php
final class ApproveApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private MemberRepositoryInterface $memberRepository,
        private FeeRepositoryInterface $feeRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(ApproveMembershipApplicationCommand $command): void
    {
        // 1. Load aggregates from repositories
        // 2. Call domain methods to modify state
        // 3. Create new aggregates
        // 4. Save all aggregates
        // 5. Dispatch domain events
    }
}
```

### The Five Steps

#### Step 1: Load Aggregates

```php
$application = $this->applicationRepository->find(
    $command->applicationId,
    $command->tenantId
);

if (!$application) {
    throw new \RuntimeException("Application not found");
}
```

**Key:**
- Always check if aggregate exists
- Pass TenantId explicitly
- Throw appropriate exception if not found

#### Step 2: Call Domain Methods

```php
$application->approve();
// This modifies aggregate state and records domain events
```

**Domain methods:**
- Call business logic
- Change aggregate state
- Record domain events via `$this->recordThat(...)`
- Never call repositories
- Never access infrastructure

#### Step 3: Create New Aggregates

```php
$member = Member::register(
    $command->tenantId,
    PersonalInfo::create(
        $command->name,
        $command->email,
        $command->phone ?? ''
    ),
    $command->membershipTypeId
);

$fee = Fee::create(
    $member->getId(),
    $command->membershipTypeId,
    $command->tenantId,
    (string) $command->membershipFeeAmount,
    $command->feeDueDate
);
```

**Key:**
- Use static create() methods
- Pass all required value objects
- Store historical snapshots if needed (e.g., membershipTypeId in Fee)

#### Step 4: Save All Aggregates

```php
$this->applicationRepository->save($application, $command->tenantId);
$this->memberRepository->save($member, $command->tenantId, $command->organisationUserId);
$this->feeRepository->save($fee, $command->tenantId);
```

**Key:**
- Save ALL modified aggregates
- Pass TenantId to each save
- If one fails, all fail (transaction in controller)
- Order matters if there are foreign keys

#### Step 5: Dispatch Domain Events

```php
$events = array_merge(
    $application->pullEvents(),
    $member->pullEvents(),
    $fee->pullEvents()
);

$this->eventBus->dispatchAll($events);
```

**Key:**
- Collect events from all aggregates
- Dispatch AFTER all saves
- Use `pullEvents()` to reset aggregate events
- Events are synchronous, in-process

## Example: ApproveApplication

```php
final class ApproveApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private MemberRepositoryInterface $memberRepository,
        private FeeRepositoryInterface $feeRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(ApproveMembershipApplicationCommand $command): void
    {
        // Step 1: Load the application that needs approval
        $application = $this->applicationRepository->find(
            $command->applicationId,
            $command->tenantId
        );

        if (!$application) {
            throw new \RuntimeException(
                "Application not found: {$command->applicationId->toString()}"
            );
        }

        // Step 2: Change application status via domain method
        $application->approve();
        // This records MembershipApplicationApproved event

        // Step 3: Create Member aggregate from application data
        $personalInfo = PersonalInfo::create(
            $command->name,
            $command->email,
            $command->phone ?? ''
        );

        $member = Member::register(
            $command->tenantId,
            $personalInfo,
            $command->membershipTypeId
        );
        // This records MemberRegistered event

        // Step 4: Create Fee aggregate with historical snapshot
        $fee = Fee::create(
            $member->getId(),
            $command->membershipTypeId,  // Historical
            $command->tenantId,
            (string) $command->membershipFeeAmount,
            $command->feeDueDate
        );
        // This records FeePaid (or similar) event

        // Step 5: Save all three aggregates in order
        // (Transaction boundary is in controller, not here)
        $this->applicationRepository->save($application, $command->tenantId);
        $this->memberRepository->save($member, $command->tenantId, $command->organisationUserId);
        $this->feeRepository->save($fee, $command->tenantId);

        // Step 6: Dispatch all collected domain events
        $events = array_merge(
            $application->pullEvents(),
            $member->pullEvents(),
            $fee->pullEvents()
        );

        $this->eventBus->dispatchAll($events);
    }
}
```

## Commands (DTOs)

Commands are Data Transfer Objects that carry data from controller to use case:

```php
final class ApproveMembershipApplicationCommand
{
    public function __construct(
        public readonly ApplicationId $applicationId,
        public readonly TenantId $tenantId,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly MembershipTypeId $membershipTypeId,
        public readonly float $membershipFeeAmount,
        public readonly \DateTimeImmutable $feeDueDate,
        public readonly string $organisationUserId,
    ) {}
}
```

**Key:**
- Use readonly properties
- Pass value objects, not strings
- Include all data needed by use case
- Immutable (can't modify after creation)
- No business logic

## Integration with Controllers

### Wrong Way: Logic in Controller

```php
// ❌ DON'T DO THIS
public function approve(Organisation $org, MembershipApplication $app): Response
{
    // Business logic in controller!
    $application = Application::find($app->id);
    $application->approve();
    $application->save();

    $member = Member::create([
        'name' => request('name'),
        'email' => request('email'),
    ]);

    return redirect()->back();
}
```

**Problems:**
- Controller has business logic
- No domain aggregate
- No events
- Hard to test
- Hard to reuse

### Right Way: Controller Delegates to Use Case

```php
// ✅ DO THIS
final class MembershipApplicationController
{
    public function __construct(
        private ApproveApplication $approveApplication
    ) {}

    public function approve(
        ApproveApplicationRequest $request,
        Organisation $org,
        MembershipApplication $application
    ): Response {
        $command = new ApproveMembershipApplicationCommand(
            applicationId: ApplicationId::fromString($application->id),
            tenantId: TenantId::fromOrganisationId($org->id),
            name: $request->string('name')->value(),
            email: $request->string('email')->value(),
            phone: $request->string('phone')->nullable()->value(),
            membershipTypeId: MembershipTypeId::fromString(
                $request->string('membership_type_id')->value()
            ),
            membershipFeeAmount: $request->numeric('membership_fee_amount'),
            feeDueDate: new \DateTimeImmutable(
                $request->date('fee_due_date')->toDateString()
            ),
            organisationUserId: auth()->user()
                ->organisation_users
                ->first()
                ->id,
        );

        DB::transaction(function () use ($command) {
            $this->approveApplication->execute($command);
        });

        return redirect()->route('organisations.membership.applications')
            ->with('success', 'Application approved');
    }
}
```

**Key:**
- Controller creates DTO from request
- Controller calls use case
- Use case handles business logic
- Controller handles HTTP concerns
- Use case within DB transaction

## Testing Use Cases

### Unit Test (No Database)

```php
public function test_approve_changes_application_status(): void
{
    $application = Application::create(
        userId: 'user-123',
        membershipTypeId: MembershipTypeId::fromString('type-123'),
        tenantId: TenantId::fromOrganisationId('org-123')
    );

    // No repository call, test domain logic directly
    $application->approve();

    $this->assertTrue($application->getStatus()->isApproved());

    $events = $application->pullEvents();
    $this->assertCount(1, $events);
    $this->assertInstanceOf(MembershipApplicationApproved::class, $events[0]);
}
```

### Integration Test (With Database)

```php
public function test_approve_creates_member_and_fee(): void
{
    $org = Organisation::factory()->create();
    $type = MembershipType::factory()->for($org)->create();
    $app = MembershipApplication::factory()
        ->for($org)
        ->create();

    $command = new ApproveMembershipApplicationCommand(
        applicationId: ApplicationId::fromString($app->id),
        tenantId: TenantId::fromOrganisationId($org->id),
        name: 'John Doe',
        email: 'john@example.com',
        phone: null,
        membershipTypeId: MembershipTypeId::fromString($type->id),
        membershipFeeAmount: 150,
        feeDueDate: now()->addMonth(),
        organisationUserId: User::factory()->create()->id,
    );

    $useCase = app(ApproveApplication::class);

    DB::transaction(function () use ($command, $useCase) {
        $useCase->execute($command);
    });

    // Verify application was approved
    $this->assertDatabaseHas('membership_applications', [
        'id' => $app->id,
        'organisation_id' => $org->id,
        'status' => 'approved',
    ]);

    // Verify member was created
    $this->assertDatabaseHas('members', [
        'organisation_id' => $org->id,
        'status' => 'active',
    ]);

    // Verify fee was created
    $this->assertDatabaseHas('membership_fees', [
        'organisation_id' => $org->id,
        'status' => 'pending',
    ]);
}
```

## Common Mistakes

### 1. Saving Partial Data

```php
// ❌ WRONG - Only saved some fields
$application->approve();
$this->repository->save($application, $tenantId);

$member->register(...);
$this->repository->save($member, $tenantId);
// Fee never saved!
```

**Fix:** Save all aggregates

### 2. Events Before Save

```php
// ❌ WRONG - Event fires before DB commit
$events = $application->pullEvents();
$this->eventBus->dispatchAll($events);  // Fires NOW

$this->repository->save($application, $tenantId);  // Might fail!
// Event already dispatched, but data not saved
```

**Fix:** Save first, dispatch after (in transaction)

### 3. Calling Repository from Domain

```php
// ❌ WRONG - Domain depends on infrastructure
final class Member
{
    public function approve(): void
    {
        $this->status = 'active';
        $this->repository->save($this);  // NO!
    }
}
```

**Fix:** Domain only changes state, use case coordinates saves

### 4. Not Using Value Objects

```php
// ❌ WRONG
$command = new ApproveMembershipApplicationCommand(
    'app-123',           // String, not ApplicationId
    'org-123',           // String, not TenantId
    'John',
    'john@example.com',
    'bronze',            // String, not MembershipTypeId
);

// ✅ RIGHT
$command = new ApproveMembershipApplicationCommand(
    applicationId: ApplicationId::fromString('app-123'),
    tenantId: TenantId::fromOrganisationId('org-123'),
    name: 'John',
    email: 'john@example.com',
    membershipTypeId: MembershipTypeId::fromString('bronze'),
);
```

### 5. No Transaction

```php
// ❌ WRONG - Not in transaction
$useCase->execute($command);
return redirect()->back();
// If event dispatch fails, data is already saved

// ✅ RIGHT
DB::transaction(function () use ($command) {
    $useCase->execute($command);
});
return redirect()->back();
// Rollback if anything fails
```

## Checklist for New Use Cases

- [ ] Create Command DTO with readonly properties
- [ ] Use case has clear single responsibility
- [ ] Load aggregates, check for null
- [ ] Call domain methods
- [ ] Create new aggregates
- [ ] Save all aggregates in correct order
- [ ] Dispatch events after saves
- [ ] Write unit tests for domain logic
- [ ] Write integration tests for orchestration
- [ ] Controller passes Command, not raw request
- [ ] Use case runs in DB transaction

---

**Last Updated:** May 3, 2026
