# API Development Best Practices

Guidelines for developing API endpoints following the established architecture.

---

## ✅ Controller Best Practices

### 1. Controllers Should Be Thin HTTP Adapters

**Good:**
```php
final class CommitteeMemberController extends Controller
{
    public function __construct(
        private AddCommitteeMemberHandler $handler,
        private CommitteeMemberQueryService $queryService,
    ) {}

    public function store(Request $request, string $committeeId): JsonResponse
    {
        // 1. Validate input format
        $validated = $request->validate([
            'memberId' => 'required|uuid',
            'role' => 'required|in:member,chair,observer'
        ]);

        // 2. Map to domain command
        $command = new AddCommitteeMemberCommand(
            tenantId: TenantContext::get(),
            committeeId: $committeeId,
            memberId: $validated['memberId'],
        );

        // 3. Delegate to handler
        try {
            $this->handler->handle($command);
            return response()->json(['success' => true], 201);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
```

**Bad:**
```php
❌ public function store(Request $request): JsonResponse
{
    // Business logic in controller
    $committee = Committee::find($request->input('committee_id'));
    if (!$committee) {
        throw new \Exception('Not found');
    }

    $member = Member::find($request->input('member_id'));
    if ($member->committees->count() >= 10) {
        return error('Max committees reached');
    }

    // Database manipulation
    $committee->members()->attach($member);
    $committee->updated_at = now();
    $committee->save();

    return response()->json($committee);
}
```

### 2. Always Get Tenant Context First

```php
public function store(Request $request): JsonResponse
{
    // First thing: get tenant context
    $tenantId = TenantContext::get();
    if (!$tenantId) {
        return response()->json(['error' => 'Missing tenant context'], 400);
    }

    // Rest of code...
}
```

### 3. Catch Specific Exceptions

```php
// ✅ Good: Catch specific domain exceptions
try {
    $this->handler->handle($command);
    return response()->json(['success' => true], 201);
} catch (\DomainException $e) {
    return response()->json(['error' => $e->getMessage()], 422);
} catch (\RuntimeException $e) {
    return response()->json(['error' => 'Server error'], 500);
}

// ❌ Bad: Catch everything
try {
    // ...
} catch (\Throwable $e) {
    // Can't distinguish between domain errors and system errors
    return response()->json(['error' => 'Error']);
}
```

### 4. Use Type Hints Everywhere

```php
// ✅ Good: Clear types
public function store(Request $request, string $committeeId): JsonResponse
{
    $tenantId = TenantContext::get();
    return response()->json([...], 201);
}

// ❌ Bad: Ambiguous returns
public function store($request, $id)
{
    return response(['data' => ...]);
}
```

---

## ✅ Command/Handler Best Practices

### 1. Commands Are DTOs (Data Transfer Objects)

**Good:**
```php
// Commands have no logic, just data
final class AddCommitteeMemberCommand
{
    public function __construct(
        public string $tenantId,
        public string $committeeId,
        public string $memberId,
        public string $role = 'member',
    ) {}
}
```

**Bad:**
```php
❌ final class AddCommitteeMemberCommand
{
    public function __construct(...) {}

    // ❌ Logic in command
    public function validate(): bool { ... }
    public function execute(): void { ... }
}
```

### 2. Handlers Orchestrate (Don't Contain Business Logic)

**Good:**
```php
final class AddCommitteeMemberHandler
{
    public function handle(AddCommitteeMemberCommand $command): void
    {
        // 1. Load aggregate
        $committee = $this->repository->findById(
            CommitteeId::fromString($command->committeeId),
            TenantId::fromString($command->tenantId)
        );

        if (!$committee) {
            throw new \DomainException('Committee not found', 404);
        }

        // 2. Execute domain method
        // (business logic is in the aggregate, not here)
        $committee->addMember(
            MemberId::fromString($command->memberId),
            CommitteeRole::from($command->role)
        );

        // 3. Persist
        $this->repository->save($committee, TenantId::fromString($command->tenantId));
    }
}
```

**Bad:**
```php
❌ final class AddCommitteeMemberHandler
{
    public function handle(AddCommitteeMemberCommand $command): void
    {
        // ❌ Business logic in handler
        $committee = Committee::find($command->committeeId);

        if ($committee->members->count() >= MAX_SIZE) {
            throw new \Exception('Full');  // Should be aggregate responsibility
        }

        if ($committee->members->contains($command->memberId)) {
            throw new \Exception('Duplicate');  // Should be aggregate responsibility
        }

        // ❌ Direct database manipulation
        $committee->members()->attach($command->memberId);
        $committee->save();
    }
}
```

### 3. Use Dependency Injection

```php
// ✅ Good: Injected dependencies
final class AddCommitteeMemberHandler
{
    public function __construct(
        private CommitteeRepositoryInterface $repository,
        private MemberValidationService $validator
    ) {}

    public function handle(AddCommitteeMemberCommand $command): void
    {
        // Use injected dependencies
        $committee = $this->repository->findById(...);
        $valid = $this->validator->canAdd(...);
    }
}

// ❌ Bad: Using facades or static methods
final class AddCommitteeMemberHandler
{
    public function handle(AddCommitteeMemberCommand $command): void
    {
        $committee = Committee::find(...);  // Static method
        Cache::put(...);  // Facade
        Log::info(...);   // Facade
    }
}
```

---

## ✅ Domain Model Best Practices

### 1. Aggregates Enforce Business Rules

**Good:**
```php
final class Committee
{
    private \ArrayObject $members;
    private int $maxSize;

    public function addMember(MemberId $memberId, CommitteeRole $role): void
    {
        // ✅ Validate business rule: no duplicates
        if ($this->hasMember($memberId)) {
            throw new \DomainException(
                "Member {$memberId->value()} already assigned to committee"
            );
        }

        // ✅ Validate business rule: max size
        if ($this->members->count() >= $this->maxSize) {
            throw new \DomainException(
                "Committee is at max capacity ({$this->maxSize})"
            );
        }

        // ✅ Create member object with value objects
        $member = new CommitteeMember($memberId, $role);
        $this->members->append($member);

        // ✅ Record event for infrastructure
        $this->record(new MemberAssignedToCommittee(
            committeeId: $this->id,
            memberId: $memberId,
            role: $role,
            occurredAt: now()
        ));
    }
}
```

**Bad:**
```php
❌ final class Committee extends Model
{
    // ❌ Eloquent coupling - not pure domain
    protected $fillable = ['name', 'members'];

    // ❌ No validation
    public function addMember($memberId): void
    {
        $this->members()->attach($memberId);
        $this->save();  // Database coupling
    }

    // ❌ No events
}
```

### 2. Use Value Objects for Identity & Type Safety

**Good:**
```php
final readonly class CommitteeId
{
    private function __construct(private string $value) {}

    public static function fromString(string $value): self
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException("Invalid UUID: $value");
        }
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}

// Usage
$committteeId = CommitteeId::fromString($input);  // ✅ Validation happens here
$committee->addMember($memberId);  // ✅ Type-safe
```

**Bad:**
```php
❌ // Passing strings around
$committee->addMember($memberId);  // What format is $memberId?
$committee->setType('chair');  // Is 'chair' valid? Who checks?

// No validation
CommitteeId::fromString('invalid');  // No error!
```

### 3. Domain Events for Side Effects

**Good:**
```php
final class Committee
{
    private array $recordedEvents = [];

    public function addMember(MemberId $memberId, CommitteeRole $role): void
    {
        // ... validation ...

        // Add member
        $this->members->append(new CommitteeMember($memberId, $role));

        // ✅ Record event for infrastructure to handle
        $this->record(new MemberAssignedToCommittee(
            committeeId: $this->id,
            memberId: $memberId,
            role: $role,
            occurredAt: now()
        ));

        // Domain doesn't know or care how event is handled
        // Could be: email, projections, cache, audit log, etc.
    }

    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }
}

// In Repository
$this->repository->save($committee, $tenantId);
// Repository handles dispatching events to listeners

// In Listeners
// Email listener, projection listener, cache listener, etc.
```

**Bad:**
```php
❌ final class Committee
{
    public function addMember($memberId): void
    {
        // ❌ Domain handles email
        Mail::send(new MemberAddedEmail($memberId));

        // ❌ Domain updates cache
        Cache::forget('committee_members');

        // ❌ Domain logs audit
        AuditLog::create([...]);

        // ❌ Domain knows too much!
    }
}
```

---

## ✅ Repository Best Practices

### 1. Repositories Load Aggregates

```php
final class EloquentCommitteeRepository implements CommitteeRepositoryInterface
{
    public function findById(CommitteeId $id, TenantId $tenantId): ?Committee
    {
        // Load from persistence
        $model = CommitteeModel::where('organisation_id', $tenantId->value())
            ->find($id->value());

        // Reconstruct domain aggregate
        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(CommitteeModel $model): Committee
    {
        // Reconstruct aggregate with all invariants
        return Committee::reconstruct(
            id: CommitteeId::fromString($model->id),
            name: $model->name,
            description: $model->description,
            members: $this->loadMembers($model),
            maxSize: $model->max_size
        );
    }
}
```

### 2. Repositories Persist Aggregates

```php
public function save(Committee $aggregate, TenantId $tenantId): void
{
    // Convert aggregate to model
    $model = CommitteeModel::firstOrCreate(
        ['id' => $aggregate->id()->value()],
        ['organisation_id' => $tenantId->value()]
    );

    // Update from aggregate state
    $model->update([
        'name' => $aggregate->name(),
        'description' => $aggregate->description(),
        'max_size' => $aggregate->maxSize(),
        'updated_at' => now(),
    ]);

    // Publish events
    event(new DomainEventDispatched($aggregate->getRecordedEvents()));
}
```

### 3. Implement Repository Interfaces in Domain

```php
// Domain layer (no Laravel)
namespace App\Contexts\Governance\Domain\Committee;

interface CommitteeRepositoryInterface
{
    public function findById(CommitteeId $id, TenantId $tenantId): ?Committee;
    public function save(Committee $aggregate, TenantId $tenantId): void;
    public function delete(CommitteeId $id, TenantId $tenantId): void;
}

// Infrastructure layer (with Laravel)
namespace App\Contexts\Governance\Infrastructure\Repositories;

final class EloquentCommitteeRepository implements CommitteeRepositoryInterface
{
    // Implementation using Eloquent
}
```

---

## ✅ Testing Best Practices

### 1. Test Domain Logic in Isolation

```php
// tests/Unit/Governance/Domain/CommitteeTest.php
final class CommitteeTest extends TestCase
{
    #[Test]
    public function cannotAddDuplicateMember(): void
    {
        $committee = Committee::create(...);
        $memberId = MemberId::fromString('...');

        $committee->addMember($memberId, CommitteeRole::member);

        $this->expectException(\DomainException::class);
        $committee->addMember($memberId, CommitteeRole::chair);  // Duplicate!
    }
}
```

### 2. Test Handlers with Mock Repository

```php
// tests/Feature/AddCommitteeMemberTest.php
final class AddCommitteeMemberTest extends TestCase
{
    #[Test]
    public function canAddMemberViaAPI(): void
    {
        $tenant = Tenant::create(['id' => 'org-123']);
        $committee = Committee::create(['id' => 'cmte-456']);
        $member = Member::create(['id' => 'member-789']);

        $response = $this->postJson('/api/v1/governance/committees/cmte-456/members', [
            'memberId' => 'member-789',
            'role' => 'member',
        ], [
            'X-Organisation-ID' => 'org-123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('committee_member_projections', [
            'committee_id' => 'cmte-456',
            'member_id' => 'member-789',
        ]);
    }
}
```

### 3. Always Test with Tenant Context

```php
// ❌ Bad: Tests without tenant context
$response = $this->postJson('/api/v1/committees', [...]);

// ✅ Good: Tests include headers
$response = $this->postJson('/api/v1/committees', [...], [
    'X-Organisation-ID' => 'org-123',
    'X-Requested-With' => 'XMLHttpRequest',
]);
```

---

## ✅ Naming Conventions

### Routes
```php
// Pattern: /api/v1/{context}/{resource}/{action}
// Examples:
GET    /api/v1/governance/committees              # List
POST   /api/v1/governance/committees              # Create
GET    /api/v1/governance/committees/{id}         # Show
PUT    /api/v1/governance/committees/{id}         # Update
DELETE /api/v1/governance/committees/{id}         # Delete

// Nested resources
GET    /api/v1/governance/committees/{id}/members # List members
POST   /api/v1/governance/committees/{id}/members # Add member
DELETE /api/v1/governance/committees/{id}/members/{memberId} # Remove member
```

### Naming Classes
```php
// Commands (intent-based)
AddCommitteeMemberCommand
RemoveCommitteeMemberCommand
AssignCandidateToPostCommand

// Handlers (same name + Handler)
AddCommitteeMemberHandler
RemoveCommitteeMemberHandler
AssignCandidateToPostHandler

// Events (past tense)
MemberAssignedToCommittee
MemberRemovedFromCommittee
CandidateAssignedToPost

// Value Objects
CommitteeId
MemberId
CommitteeRole

// Aggregates
Committee
CandidatePost

// Repositories (Aggregate + Repository)
CommitteeRepositoryInterface
EloquentCommitteeRepository
InMemoryCommitteeRepository  # for testing
```

### Error Codes
```php
// Consistent error code patterns
'MISSING_TENANT_CONTEXT'   # 400
'INVALID_ORGANISATION'     # 404
'UNAUTHORIZED_ACCESS'      # 403
'USER_NOT_FOUND'          # 404
'DUPLICATE_MEMBER'        # 422
'INVALID_ROLE'            # 422
```

---

## ✅ API Response Format

### Success Response
```json
{
  "success": true,
  "data": { ... },
  "message": "Operation completed"
}
```

### Error Response
```json
{
  "error": "Detailed error message",
  "code": "ERROR_CODE",
  "status": 422
}
```

### List Response
```json
{
  "data": [ ... ],
  "total": 42,
  "page": 1,
  "per_page": 20
}
```

---

## ✅ Logging Best Practices

### What to Log
```php
// ✅ Log important business events
\Log::info('Member added to committee', [
    'committee_id' => $committee->id(),
    'member_id' => $member->id(),
    'role' => $role->value,
    'tenant_id' => $tenantId->value(),
]);

// ✅ Log errors with context
\Log::error('Failed to add member', [
    'committee_id' => $committee->id(),
    'member_id' => $member->id(),
    'error' => $e->getMessage(),
    'trace' => $e->getTraceAsString(),
]);
```

### What NOT to Log
```php
// ❌ Don't log sensitive data
\Log::info('User login', ['password' => $password]);  // NO!

// ❌ Don't log everything
\Log::debug('Starting loop iteration 1');  // Too verbose
\Log::debug('Variable $x = 5');  // Not useful

// ❌ Don't log in production if expensive
foreach ($items as $item) {
    \Log::debug("Processing item $item->id");  // Millions of logs!
}
```

---

## ✅ Configuration & Environment

### Separate Configuration
```php
// config/api.php
return [
    'version' => 'v1',
    'prefix' => 'api/v1',
    'rate_limit' => env('API_RATE_LIMIT', '60,1'),
    'tenants' => [
        'enabled' => true,
        'header' => 'X-Organisation-ID',
    ],
];

// Usage
config('api.tenants.header')  # 'X-Organisation-ID'
```

### Environment Variables
```bash
# .env
API_RATE_LIMIT=60,1
API_LOG_QUERIES=true
API_CACHE_ENABLED=true
```

---

## ✅ Performance Tips

### 1. Use Eager Loading
```php
// ❌ N+1 problem
$committees = Committee::all();
foreach ($committees as $committee) {
    echo count($committee->members);  # Query per committee!
}

// ✅ Eager load
$committees = Committee::with('members')->get();
foreach ($committees as $committee) {
    echo count($committee->members);  # Loaded with parent
}
```

### 2. Cache Query Results
```php
$members = Cache::remember(
    "committee:$committeeId:members",
    now()->addHour(),
    fn() => Member::where('committee_id', $committeeId)->get()
);
```

### 3. Paginate Large Results
```php
// ✅ Good: Paginated
$members = Member::paginate(20);

// ❌ Bad: All at once
$members = Member::all();
```

---

## ✅ Security Best Practices

### 1. Validate Input
```php
// Always validate at entry point
$validated = $request->validate([
    'memberId' => 'required|uuid',
    'role' => 'required|in:member,chair,observer',
]);
```

### 2. Check Tenant Context
```php
// Every operation must verify tenant context
$tenantId = TenantContext::get();
if (!$tenantId) {
    return response()->json(['error' => 'Missing tenant'], 400);
}

// Verify user has access to tenant
if (!auth()->user()->organisations()->where('organisation_id', $tenantId)->exists()) {
    return response()->json(['error' => 'Unauthorized'], 403);
}
```

### 3. Sanitize Output
```php
// ✅ Don't expose internal details
return response()->json([
    'id' => $member->id,
    'name' => $member->name,
    'email' => $member->email,
]);

// ❌ Don't include sensitive data
return response()->json($member->toArray());  # Might include password hash!
```

---

**Next:** Read [ARCHITECTURE.md](./ARCHITECTURE.md) for complete architecture overview.
