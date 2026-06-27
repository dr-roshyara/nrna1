# Implementation Guide: Building Features

This guide walks you through the process of adding a new feature to the Membership Context, step-by-step.

---

## 🎯 Feature Checklist

When adding a feature, you'll typically:

- [ ] Write tests (TDD-first)
- [ ] Design domain logic (aggregate methods)
- [ ] Create value objects (if new domain concepts)
- [ ] Write use case (application layer)
- [ ] Create DTOs (input/output)
- [ ] Implement repository methods (if needed)
- [ ] Add HTTP controller
- [ ] Add routes
- [ ] Create Vue component (if UI needed)
- [ ] Write migrations (if database change)
- [ ] Document in API reference

---

## 📝 Example: Add Committee Deactivation Feature

### Step 1: Write Tests First (TDD)

Create `tests/Unit/Contexts/Membership/Application/Committee/DeactivateCommitteeTest.php`:

```php
<?php

namespace Tests\Unit\Contexts\Membership\Application\Committee;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Application\Committee\DeactivateCommittee;
use App\Contexts\Membership\Application\Committee\DTOs\DeactivateCommitteeCommand;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeCode;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Shared\Domain\Events\EventBus;

class DeactivateCommitteeTest extends TestCase
{
    private DeactivateCommittee $useCase;
    private CommitteeRepositoryInterface $repositoryMock;
    private EventBus $eventBusMock;
    private Committee $committee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(CommitteeRepositoryInterface::class);
        $this->eventBusMock = $this->createMock(EventBus::class);

        $this->useCase = new DeactivateCommittee(
            $this->repositoryMock,
            $this->eventBusMock
        );

        // Create a test committee
        $this->committee = Committee::form(
            type: CommitteeType::central(),
            name: CommitteeName::of('Test Committee'),
            code: CommitteeCode::of('TEST-001'),
            tenantId: TenantId::fromString('tenant-123'),
            geoReference: null
        );
    }

    public function test_deactivate_changes_status_to_inactive()
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('findForTenant')
            ->with($this->committee->getId(), $this->committee->getTenantId())
            ->willReturn($this->committee);

        $this->repositoryMock
            ->expects($this->once())
            ->method('saveForTenant')
            ->with($this->committee);

        $this->eventBusMock
            ->expects($this->once())
            ->method('dispatchAll');

        $this->useCase->execute(new DeactivateCommitteeCommand(
            committeeId: $this->committee->getId(),
            tenantId: $this->committee->getTenantId(),
            reason: 'Administrative decision'
        ));

        $this->assertTrue($this->committee->getStatus()->isInactive());
    }

    public function test_deactivate_raises_committee_deactivated_event()
    {
        $this->repositoryMock->method('findForTenant')->willReturn($this->committee);
        $this->repositoryMock->method('saveForTenant');

        $this->eventBusMock
            ->expects($this->once())
            ->method('dispatchAll')
            ->with($this->callback(function ($events) {
                // Check that CommitteeDeactivated event is present
                return collect($events)
                    ->some(fn($e) => $e instanceof CommitteeDeactivated);
            }));

        $this->useCase->execute(new DeactivateCommitteeCommand(
            committeeId: $this->committee->getId(),
            tenantId: $this->committee->getTenantId(),
            reason: 'Administrative decision'
        ));
    }

    public function test_cannot_deactivate_already_inactive_committee()
    {
        $this->committee->deactivate(); // Already inactive

        $this->repositoryMock->method('findForTenant')->willReturn($this->committee);

        $this->expectException(InvalidCommitteeStatusException::class);

        $this->useCase->execute(new DeactivateCommitteeCommand(
            committeeId: $this->committee->getId(),
            tenantId: $this->committee->getTenantId(),
            reason: 'Already inactive'
        ));
    }
}
```

### Step 2: Create Domain Event

Create `app/Contexts/Membership/Domain/Events/CommitteeDeactivated.php`:

```php
<?php

namespace App\Contexts\Membership\Domain\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;

final class CommitteeDeactivated extends AbstractDomainEvent
{
    public function __construct(
        private readonly CommitteeId $committeeId,
        private readonly string $reason,
    ) {
        parent::__construct();
    }

    public function getCommitteeId(): CommitteeId
    {
        return $this->committeeId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
```

### Step 3: Add Domain Method to Committee

Edit `app/Contexts/Membership/Domain/Committee/Committee.php`:

```php
public function deactivate(string $reason = ''): void
{
    if ($this->status->isInactive()) {
        throw new InvalidCommitteeStatusException(
            'Committee is already inactive'
        );
    }

    $this->status = CommitteeStatus::inactive();
    $this->recordEvent(new CommitteeDeactivated($this->id, $reason));
}
```

### Step 4: Create DTO

Create `app/Contexts/Membership/Application/Committee/DTOs/DeactivateCommitteeCommand.php`:

```php
<?php

namespace App\Contexts\Membership\Application\Committee\DTOs;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;

final readonly class DeactivateCommitteeCommand
{
    public function __construct(
        public CommitteeId $committeeId,
        public TenantId $tenantId,
        public string $reason = '',
    ) {}
}
```

### Step 5: Create Use Case

Create `app/Contexts/Membership/Application/Committee/DeactivateCommittee.php`:

```php
<?php

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\DeactivateCommitteeCommand;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Shared\Domain\Events\EventBus;
use Illuminate\Support\Facades\DB;

final class DeactivateCommittee
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees,
        private readonly EventBus $eventBus
    ) {}

    public function execute(DeactivateCommitteeCommand $command): void
    {
        DB::transaction(function () use ($command) {
            // Find committee
            $committee = $this->committees->findForTenant(
                $command->committeeId,
                $command->tenantId
            );

            if ($committee === null) {
                throw new CommitteeNotFoundException($command->committeeId);
            }

            // Call domain method
            $committee->deactivate($command->reason);

            // Persist changes
            $this->committees->saveForTenant($committee);

            // Dispatch events (inside transaction)
            $this->eventBus->dispatchAll($committee->pullEvents());
        });
    }
}
```

### Step 6: Create Controller

Create `app/Http/Controllers/Committee/DeactivateCommitteeController.php`:

```php
<?php

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\DeactivateCommittee;
use App\Contexts\Membership\Application\Committee\DTOs\DeactivateCommitteeCommand;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contracts\TenantContextInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DeactivateCommitteeController
{
    public function __construct(
        private readonly DeactivateCommittee $deactivateCommittee,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function store(string $committeeId, Request $request): RedirectResponse
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->deactivateCommittee->execute(new DeactivateCommitteeCommand(
                committeeId: CommitteeId::fromString($committeeId),
                tenantId: $this->tenantContext->currentTenantId(),
                reason: $request->input('reason', ''),
            ));

            return redirect()
                ->route('committee.show', $committeeId)
                ->with('success', 'Committee deactivated successfully');

        } catch (CommitteeNotFoundException) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Committee not found']);
        }
    }
}
```

### Step 7: Add Routes

Edit `routes/committee/committeeRoutes.php`:

```php
Route::middleware(['auth', 'verified', 'ensure.organisation'])->group(function () {
    // ... existing routes ...
    
    Route::post('/committee/{committeeId}/deactivate', 
        [DeactivateCommitteeController::class, 'store']
    )->name('committee.deactivate');
});
```

### Step 8: Register in Service Provider

Edit `app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php`:

```php
public function register(): void
{
    // ... existing bindings ...
    
    $this->app->bind(DeactivateCommittee::class, function ($app) {
        return new DeactivateCommittee(
            $app->make(CommitteeRepositoryInterface::class),
            $app->make(EventBus::class)
        );
    });
}
```

### Step 9: Create Event Listener (If Needed)

Create `app/Listeners/Committee/OnCommitteeDeactivated.php`:

```php
<?php

namespace App\Listeners\Committee;

use App\Contexts\Membership\Domain\Events\CommitteeDeactivated;

final class OnCommitteeDeactivated
{
    public function handle(CommitteeDeactivated $event): void
    {
        // Send notification
        // Update search index
        // Log audit trail
        // etc.
    }
}
```

Register in `app/Providers/EventServiceProvider.php`:

```php
protected $listen = [
    CommitteeDeactivated::class => [
        OnCommitteeDeactivated::class,
    ],
];
```

---

## 🔍 Layer Responsibilities Summary

| Layer | What You Do | What You DON'T Do |
|-------|-----------|------------------|
| **Domain** | Write business logic, validation, events | Framework code, database, HTTP |
| **Application** | Orchestrate domain + infrastructure, own transactions | Business logic (that's domain), rendering (that's HTTP) |
| **Infrastructure** | Implement repositories, adapters, models | Business logic (that's domain), orchestration (that's application) |
| **HTTP** | Receive requests, call use cases, render responses | Business logic, data persistence, transaction management |

---

## ✅ Checklist Before Submitting

- [ ] **Tests pass**: `php artisan test`
- [ ] **Domain layer has no framework**: No `use Illuminate\*`
- [ ] **Application layer owns transaction**: Wraps in `DB::transaction()`
- [ ] **Application dispatches events**: After transaction succeeds
- [ ] **Repository is interface-based**: Uses `CommitteeRepositoryInterface`
- [ ] **DTOs used, not arrays**: No `execute($data)` with array, use DTO
- [ ] **Events are domain events**: Extends `AbstractDomainEvent`
- [ ] **Value objects used**: No plain strings for domain concepts
- [ ] **Exception handling**: Specific exceptions, not generic `Exception`
- [ ] **Tenant safety verified**: Tests with multiple `TenantId` values
- [ ] **Type hints complete**: No `mixed` types
- [ ] **Final classes**: Domain and application classes marked `final`

---

## 🚀 Common Patterns

### Pattern 1: Query Use Case (Read-Only)

```php
final class GetCommitteeDetails
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees
    ) {}

    public function execute(CommitteeId $id, TenantId $tenantId): CommitteeView
    {
        $committee = $this->committees->findForTenant($id, $tenantId);
        
        if ($committee === null) {
            throw new CommitteeNotFoundException($id);
        }

        return CommitteeView::from($committee);
    }
}
```

**Note:** No event dispatch, no transaction, just read and transform.

### Pattern 2: Command Use Case (Write)

```php
final class UpdateCommitteeName
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees,
        private readonly EventBus $eventBus
    ) {}

    public function execute(UpdateCommitteeNameCommand $command): void
    {
        DB::transaction(function () use ($command) {
            $committee = $this->committees->findForTenant(
                $command->committeeId,
                $command->tenantId
            ) ?? throw new CommitteeNotFoundException($command->committeeId);

            $committee->updateName($command->newName);

            $this->committees->saveForTenant($committee);
            $this->eventBus->dispatchAll($committee->pullEvents());
        });
    }
}
```

**Note:** Transaction wraps everything, events dispatched inside.

### Pattern 3: Service with Multiple Repositories

```php
final class TransferMembersToNewCommittee
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees,
        private readonly MemberRepositoryInterface $members,
        private readonly EventBus $eventBus
    ) {}

    public function execute(TransferCommand $command): void
    {
        DB::transaction(function () use ($command) {
            $fromCommittee = $this->committees->findForTenant(...);
            $toCommittee = $this->committees->findForTenant(...);
            
            foreach ($command->memberIds as $memberId) {
                $member = $this->members->find($memberId);
                
                $fromCommittee->removeAssignment($memberId);
                $toCommittee->assignMember($memberId, ...);
            }
            
            $this->committees->saveForTenant($fromCommittee);
            $this->committees->saveForTenant($toCommittee);
            
            $events = array_merge(
                $fromCommittee->pullEvents(),
                $toCommittee->pullEvents()
            );
            $this->eventBus->dispatchAll($events);
        });
    }
}
```

---

## 🧪 Testing Strategy

### Unit Test (Domain + Application)

Test business rules and orchestration without database:

```php
// In tests/Unit/Contexts/Membership/...

public function test_committee_cannot_have_duplicate_roles()
{
    $committee = Committee::form(...);
    
    $committee->assignMember($memberId1, RolePath::chairperson());
    
    $this->expectException(InvalidMemberAssignmentException::class);
    $committee->assignMember($memberId2, RolePath::chairperson());
}
```

### Integration Test (With Database)

Test repository and domain together:

```php
// In tests/Integration/Contexts/Membership/...

public function test_save_and_find_committee()
{
    $committee = Committee::form(...);
    
    $this->repository->saveForTenant($committee);
    $found = $this->repository->findForTenant(
        $committee->getId(),
        $committee->getTenantId()
    );
    
    $this->assertEquals($committee->getId(), $found->getId());
}
```

### Feature Test (Full Stack)

Test HTTP endpoint to response:

```php
// In tests/Feature/Committee/...

public function test_deactivate_committee_endpoint()
{
    $committee = $this->createCommittee();
    
    $response = $this->post("/committee/{$committee->getId()}/deactivate", [
        'reason' => 'Testing',
    ]);
    
    $response->assertRedirect(route('committee.show', $committee));
    $this->assertTrue($committee->refresh()->getStatus()->isInactive());
}
```

---

## 📋 File Checklist for New Feature

```
✅ Domain Layer
   ├─ Domain event(s)
   ├─ Domain method in aggregate
   ├─ Domain exception(s) if needed
   ├─ Domain test(s)
   └─ Domain service interface (if external service needed)

✅ Application Layer
   ├─ Use case (Command/Query)
   ├─ DTO(s)
   ├─ Application test(s)
   └─ View model (if needed)

✅ Infrastructure Layer
   ├─ HTTP controller
   ├─ Form request validation
   ├─ Repository method (if needed)
   ├─ Service adapter (if needed)
   ├─ Event listener (if needed)
   └─ Database migration (if needed)

✅ HTTP Layer
   ├─ Route
   ├─ Vue component (if needed)
   └─ Feature test

✅ Configuration
   ├─ Service provider bindings
   └─ Route registration
```

---

## 🎯 Key Principles When Building

1. **Push logic to domain** — Aggregate methods, not controller
2. **Use value objects** — Never plain strings for domain concepts
3. **Own transactions in use case** — Not in repository
4. **Dispatch events after success** — Inside transaction
5. **Test at domain level first** — Then integration, then features
6. **Use interfaces** — Depend on `CommitteeRepositoryInterface`, not implementation
7. **Keep controllers thin** — Just HTTP plumbing
8. **One DTO per use case** — No generic array of data
9. **Mark classes final** — Unless you're sure about inheritance
10. **Type everything** — No `mixed` types

---

**Next:** Read [05_API_REFERENCE.md](./05_API_REFERENCE.md) to look up available classes and methods
