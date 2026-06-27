## 📚 Membership Context — Professional Developer Guide

---

## 🏗️ Architecture Overview

### Bounded Context: Membership
Responsible for member registration, membership applications, fee management, and committee assignments.

### Core Design Principles

| Principle | Implementation |
|-----------|----------------|
| **Single Write Authority** | All writes go through DDD use cases, never direct Eloquent |
| **Strangler Fig Pattern** | Legacy reads preserved, writes migrated to DDD |
| **Tenant Isolation** | `organisation_id` with GlobalScope, explicit filtering in repositories |
| **Deterministic Repositories** | No `firstOrCreate()` / `updateOrCreate()` — explicit find → update |
| **Event-Driven** | Domain events dispatched via `dispatchAll()` after transaction commit |

---

## 📁 Folder Structure

```
app/Contexts/Membership/
├── Domain/
│   ├── Member/
│   │   ├── Member.php
│   │   ├── MemberId.php
│   │   ├── MemberStatus.php
│   │   ├── ValueObjects/PersonalInfo.php
│   │   └── Events/MemberRegistered.php
│   ├── Application/
│   │   ├── Application.php
│   │   ├── ApplicationId.php
│   │   ├── ApplicationStatus.php
│   │   └── Events/ApplicationApproved.php
│   ├── Fee/
│   │   ├── Fee.php
│   │   ├── FeeId.php
│   │   ├── FeeStatus.php
│   │   └── Events/FeePaid.php
│   ├── Committee/
│   │   └── Committee.php
│   └── Repositories/
│       ├── MemberRepositoryInterface.php
│       ├── ApplicationRepositoryInterface.php
│       └── FeeRepositoryInterface.php
├── Application/
│   ├── Member/UseCases/
│   │   ├── RegisterMember.php
│   │   ├── ActivateMember.php
│   │   └── SuspendMember.php
│   ├── Application/UseCases/
│   │   ├── SubmitMembershipApplication.php
│   │   ├── ApproveMembershipApplication.php
│   │   └── RejectMembershipApplication.php
│   ├── Fee/UseCases/
│   │   ├── RecordFeePayment.php
│   │   └── WaiveFee.php
│   └── DTOs/
│       ├── SubmitMembershipApplicationCommand.php
│       ├── ApproveMembershipApplicationCommand.php
│       └── RejectMembershipApplicationCommand.php
└── Infrastructure/
    ├── Models/
    │   ├── MemberContextModel.php
    │   ├── ApplicationContextModel.php
    │   └── FeeContextModel.php
    ├── Repositories/
    │   ├── EloquentMemberRepository.php
    │   ├── EloquentApplicationRepository.php
    │   └── EloquentFeeRepository.php
    └── Providers/
        └── MembershipServiceProvider.php
```

---

## 🔑 Key Aggregates

### 1. Member Aggregate

```php
$member = Member::register(
    MemberId::generate(),           // UUID
    TenantId::fromString($orgId),   // organisation_id
    $userId,                        // string (User model ID)
    PersonalInfo::create($name, $email, $phone),
    CommitteeId::fromString($committeeId),
    MembershipTypeId::fromString($typeId)
);

$member->activate();
$member->suspend('Reason');
$member->archive();
```

### 2. Application Aggregate

```php
$application = Application::submit(
    ApplicationId::generate(),      // UUID
    TenantId::fromString($orgId),
    $userId,                        // string (User model ID)
    MembershipTypeId::fromString($typeId),
    $applicationData                // array (optional)
);

$application->approve();            // Status → 'approved'
$application->reject('Reason');     // Status → 'rejected'
```

### 3. Fee Aggregate

```php
$fee = Fee::create(
    MemberId::fromString($memberId),        // UUID
    MembershipTypeId::fromString($typeId),  // Snapshot at time of creation
    TenantId::fromString($orgId),
    $amount,                                // decimal
    $dueDate                                // CarbonImmutable
);

$fee->markAsPaid();                // Status → 'paid'
$fee->markAsOverdue();             // Status → 'overdue'
```

---

## 🔄 Use Case Pattern

### Standard Use Case Structure

```php
final class ApproveMembershipApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private MemberRepositoryInterface $memberRepository,
        private FeeRepositoryInterface $feeRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(ApproveMembershipApplicationCommand $command): void
    {
        // 1. Load aggregate
        $application = $this->applicationRepository->find(
            $command->applicationId,
            $command->tenantId
        );

        // 2. Invoke domain logic
        $application->approve();

        // 3. Create dependent aggregates
        $member = Member::register(...);
        $fee = Fee::create(...);

        // 4. Persist (controller handles transaction)
        $this->applicationRepository->save($application, $command->tenantId);
        $this->memberRepository->save($member, $command->tenantId);
        $this->feeRepository->save($fee, $command->tenantId);

        // 5. Dispatch events
        $events = array_merge(
            $application->pullEvents(),
            $member->pullEvents(),
            $fee->pullEvents()
        );
        $this->eventBus->dispatchAll($events);
    }
}
```

### Command DTO Pattern

```php
final readonly class ApproveMembershipApplicationCommand
{
    public function __construct(
        public ApplicationId $applicationId,
        public TenantId $tenantId,
        public string $userId,
        public string $organisationUserId,
        public string $name,
        public string $email,
        public ?string $phone,
        public CommitteeId $committeeId,
        public MembershipTypeId $membershipTypeId,
        public float $membershipFeeAmount,
        public CarbonImmutable $feeDueDate
    ) {}
}
```

---

## 🗄️ Repository Pattern (Critical!)

### ❌ Never Do This:
```php
// Non-deterministic — can create duplicate rows or update wrong instance
$model = $this->model->firstOrCreate(['id' => $id, 'organisation_id' => $orgId]);
$model->fill($data);
$model->save();
```

### ✅ Always Do This:
```php
// Deterministic — explicit find, throw if not found
$model = $this->model
    ->withoutGlobalScopes()
    ->where('id', $appId)
    ->where('organisation_id', $orgId)
    ->first();

if (!$model) {
    throw new \RuntimeException("Record not found: {$appId}");
}

$model->status = $application->getStatus()->value();
$model->save();
```

### For Optional Creation:
```php
$model = $this->model
    ->withoutGlobalScopes()
    ->where('id', $id)
    ->where('organisation_id', $orgId)
    ->first();

if (!$model) {
    $model = new $this->model();
    $model->id = $id;
    $model->organisation_id = $orgId;
}

$model->field = $value;
$model->save();
```

---

## 🔐 Tenant Isolation

### Repository Find Method:
```php
public function find(ApplicationId $id, TenantId $tenantId): ?Application
{
    $record = $this->model
        ->withoutGlobalScopes()
        ->where('id', $id->value())
        ->where('organisation_id', $tenantId->value())
        ->first();

    return $record ? $this->reconstitute($record) : null;
}
```

### Model Trait:
```php
use App\Traits\BelongsToTenant;  // NOT App\Shared\Domain\Scopes\BelongsToTenant

class ApplicationContextModel extends Model
{
    use SoftDeletes, BelongsToTenant;
    
    protected $table = 'membership_applications';
    protected $fillable = ['id', 'organisation_id', 'user_id', 'membership_type_id', 'status'];
}
```

---

## 🆔 ID Generation (PostgreSQL Compatible)

### All aggregates must use UUID (not ULID):

```php
public static function generate(): self
{
    return new self((string) Str::uuid());
}
```

### Support both UUID and ULID for legacy data:

```php
private function __construct(private string $value)
{
    if (!$this->isValidUuid($value) && !$this->isValidUlid($value)) {
        throw new \InvalidArgumentException("Invalid ID format: {$value}");
    }
}
```

---

## 🧪 Testing Guidelines

### Test Database Configuration (`.env.testing`):
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nrna_test
DB_USERNAME=nrna
DB_PASSWORD=your_password
```

### Test Trait:
```php
use Illuminate\Foundation\Testing\DatabaseMigrations;  // NOT RefreshDatabase or DatabaseTransactions
```

### Example Test:
```php
public function test_approve_creates_member_and_fee(): void
{
    $app = $this->createPendingApplication();
    
    $this->actingAs($this->admin)->patch(
        route('organisations.membership.applications.approve', [$this->org->slug, $app->id])
    );
    
    $this->assertDatabaseHas('membership_applications', [
        'id' => $app->id,
        'status' => 'approved',
    ]);
    
    $this->assertDatabaseHas('membership_fees', [
        'organisation_id' => $this->org->id,
        'status' => 'pending',
    ]);
}
```

---

## 🚫 Common Pitfalls & Solutions

| Pitfall | Solution |
|---------|----------|
| Using `firstOrCreate` in repositories | Use explicit `first()` + conditional create |
| Mixing ULID and UUID | Always use UUID for PostgreSQL compatibility |
| Nested transactions | Only controller wraps transaction, use cases don't |
| Implicit route binding | Use explicit `string $id` + manual `findOrFail()` |
| Missing `organisation_user_id` | Always pass to Member repository save method |
| Missing `membership_type_id` in Fee | Store snapshot at creation time |
| Event dispatch before commit | Use `DB::afterCommit()` or ensure transaction completes |

---

## ✅ Development Checklist

### Adding a New Use Case:
- [ ] Create Command DTO in `Application/DTOs/`
- [ ] Create Use Case in `Application/*/UseCases/`
- [ ] Inject required repositories and event bus
- [ ] Wrap writes in `DB::transaction()` (if controller doesn't)
- [ ] Dispatch events with `dispatchAll()`
- [ ] Bind in `MembershipServiceProvider.php`
- [ ] Add controller method with explicit ID resolution
- [ ] Add route with explicit parameter
- [ ] Write tests

### Creating a New Repository:
- [ ] Define interface in `Domain/Repositories/`
- [ ] Implement in `Infrastructure/Repositories/`
- [ ] Use `withoutGlobalScopes()` + explicit `organisation_id` filter
- [ ] NEVER use `firstOrCreate` / `updateOrCreate`
- [ ] Use `first()` + conditional create pattern
- [ ] Include `__toString()` or `value()` for ID conversion

---

## 📞 Support & Resources

| Issue | Contact |
|-------|---------|
| Architectural questions | Refer to this guide |
| ID type confusion | Check `value()` vs `toString()` methods |
| Tenant isolation | Verify `withoutGlobalScopes()` + `organisation_id` filter |
| Repository bugs | Replace `firstOrCreate` with explicit logic |

---

**Version:** 1.0 | **Last Updated:** 2026-05-03 | **Phase:** 3D Complete

🚀 **Happy coding, Developer!**