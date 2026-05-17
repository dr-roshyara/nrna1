# API Architecture Overview

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         CLIENT LAYER                                     │
│  (Vue.js Components, Browser, Mobile Apps)                             │
└──────────────────────────────────────────┬──────────────────────────────┘
                                           │
                    ┌──────────────────────┴──────────────────────┐
                    │                                             │
         ┌──────────▼────────┐                        ┌──────────▼────────┐
         │   WEB PIPELINE    │                        │   API PIPELINE    │
         │  (Inertia.js 2.0) │                        │  (/api/v1/...)    │
         └──────────┬────────┘                        └──────────┬────────┘
                    │                                            │
         ┌──────────▼────────────────┐          ┌───────────────▼──────────┐
         │ Middleware Stack (web)    │          │ Middleware Stack (api)   │
         │ - Session                 │          │ - web (session, cookies) │
         │ - Auth                    │          │ - json.api (force JSON)  │
         │ - TenantContext           │          │ - tenant.api (headers)   │
         │ - Inertia Rendering       │          │ - auth                   │
         └──────────┬────────────────┘          │ - verified               │
                    │                           └───────────┬──────────────┘
         ┌──────────▼────────────────────┐                  │
         │ Inertia Controllers          │      ┌────────────▼─────────────┐
         │ (Dashboard, Pages)           │      │ API Controllers (HTTP Adapters)
         │                              │      │ - CommitteeMemberController
         │ Response: Vue Page +         │      │ - CommitteeController
         │           Props              │      │ (Thin, only map request→response)
         └──────────┬────────────────────┘      │
                    │                           └────────────┬────────────┘
         ┌──────────▼────────────────────┐                   │
         │ Application Layer             │     ┌─────────────▼──────────────┐
         │ - CommandHandlers             │     │ Application Layer          │
         │ - QueryServices               │     │ - CommandHandlers          │
         │ - DTOs                        │     │ - QueryServices            │
         │ - Validation                  │     │ - DTOs                     │
         │                               │     │ - Mapping/Validation       │
         └──────────┬────────────────────┘     └─────────────┬──────────────┘
                    │                                        │
                    └────────────────┬─────────────────────┘
                                    │
                    ┌───────────────▼────────────────┐
                    │    DOMAIN LAYER (Pure PHP)     │
                    │ - Aggregates (Committee, etc)  │
                    │ - Value Objects (CommitteeId)  │
                    │ - Domain Events                │
                    │ - Business Rules               │
                    │ (NO LARAVEL DEPENDENCIES)      │
                    └───────────────┬────────────────┘
                                    │
                    ┌───────────────▼────────────────┐
                    │  INFRASTRUCTURE LAYER          │
                    │ - Repository Implementations   │
                    │ - Database (Eloquent)          │
                    │ - Cache                        │
                    │ - Event Listeners              │
                    │ - Projections                  │
                    └────────────────────────────────┘
```

---

## Core Design Patterns

### 1. **Hexagonal Architecture (Ports & Adapters)**

The API layer acts as an **adapter** between the HTTP protocol and the domain layer:

```php
// Controller = HTTP Adapter (Infrastructure)
final class CommitteeMemberController extends Controller
{
    public function store(Request $request, string $committeeId): JsonResponse
    {
        // Input: HTTP Request
        $input = $request->input('memberId');
        
        // Map to domain command
        $command = new AddCommitteeMemberCommand(
            tenantId: TenantContext::get(),
            committeeId: $committeeId,
            memberId: $input['memberId']
        );
        
        // Execute domain logic
        $this->handler->handle($command);
        
        // Output: HTTP Response
        return response()->json(['success' => true]);
    }
}
```

**Isolation Principle:**
- Domain layer knows nothing about HTTP, requests, responses
- Controllers know about HTTP but not business logic
- Application layer orchestrates both

---

### 2. **CQRS Light (Command Query Responsibility Segregation)**

#### Reads (Queries)
```php
// Direct database access - no domain logic needed
public function index(string $committeeId): JsonResponse
{
    $members = $this->queryService->getMembersForCommittee(
        CommitteeId::fromString($committeeId),
        TenantId::fromString($tenantId)
    );
    
    return response()->json(['members' => $members]);
}
```

**QueryService Flow:**
```
Controller → QueryService → Eloquent Query → Cache → Response
```

#### Writes (Commands)
```php
// Full domain aggregate validation
public function store(Request $request, string $committeeId): JsonResponse
{
    $command = new AddCommitteeMemberCommand(...);
    $this->handler->handle($command);  // Full business logic
    
    return response()->json(['success' => true], 201);
}
```

**Handler Flow:**
```
Controller → Command → Handler → Aggregate → Events → Repository → Listeners → Projections
```

---

### 3. **Domain Events & Event Sourcing**

When a domain operation completes, it generates events:

```php
// Domain Aggregate
final class Committee
{
    private array $recordedEvents = [];
    
    public function addMember(MemberId $memberId, CommitteeRole $role): void
    {
        // Validate business rules
        if ($this->hasMember($memberId)) {
            throw new \DomainException('Member already in committee');
        }
        
        // Record the event
        $this->record(new MemberAssignedToCommittee(
            committeeId: $this->id,
            memberId: $memberId,
            role: $role,
            occurredAt: now()
        ));
    }
    
    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }
}
```

**Event Processing:**
```
1. Domain aggregate generates event
2. Repository saves aggregate + publishes events
3. Listeners react to events
4. Projections update for reads
5. UI refreshes with new data
```

---

### 4. **Multi-Tenancy: Header-Based Tenant Context**

Unlike traditional session-based tenancy, the API uses HTTP headers:

```php
// Middleware extracts tenant from header
public function handle(Request $request, Closure $next): Response
{
    $organisationId = $request->header('X-Organisation-ID')
                   ?? $request->header('x-organisation-id')
                   ?? $request->header('X-Tenant-Id');
    
    if (!$organisationId) {
        return response()->json([
            'error' => 'X-Organisation-ID header is required',
            'code' => 'MISSING_TENANT_CONTEXT'
        ], 400);
    }
    
    // Validate user belongs to this organisation
    if (!$request->user()->organisations()->where('organisation_id', $organisationId)->exists()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    // Set global context
    TenantContext::set($organisationId);
    
    return $next($request);
}
```

**Why Header-Based?**
- ✅ Explicit: Every request declares its tenant
- ✅ Testable: Easy to mock different tenants
- ✅ Multi-tenant: Can switch tenants in same session
- ✅ API-friendly: RESTful principle

**TenantContext Service:**
```php
// Static holder for request-scoped tenant ID
final class TenantContext
{
    private static ?string $tenantId = null;
    
    public static function set(?string $tenantId): void
    {
        self::$tenantId = $tenantId;
    }
    
    public static function get(): ?string
    {
        return self::$tenantId;
    }
}
```

---

### 5. **Value Objects: Type Safety**

Instead of strings/integers, use Value Objects:

```php
// ❌ Weak typing - can't validate at compile time
public function addMember(string $memberId, string $role): void { }

// ✅ Strong typing - prevents invalid values
public function addMember(MemberId $memberId, CommitteeRole $role): void { }
```

**Value Object Example:**
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
```

**Benefits:**
- Validation happens once at creation
- Can't pass wrong type to method
- Self-documenting code
- Encapsulates formatting logic

---

## Layer Responsibilities

### HTTP Layer (Controllers)

**Responsibility:** Adapt HTTP to domain language

```php
final class CommitteeMemberController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // 1. Extract tenant context (set by middleware)
        $tenantId = TenantContext::get();
        
        // 2. Validate HTTP request format
        $validated = $request->validate([
            'memberId' => 'required|uuid',
            'role' => 'required|in:member,chair,observer'
        ]);
        
        // 3. Map HTTP input to domain command
        $command = new AddCommitteeMemberCommand(
            tenantId: $tenantId,
            committeeId: $request->route('committeeId'),
            memberId: $validated['memberId']
        );
        
        // 4. Delegate to application layer
        try {
            $this->handler->handle($command);
            return response()->json(['success' => true], 201);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
```

**Rules:**
- ❌ No direct database queries
- ❌ No business logic
- ❌ No framework facades (Cache, Log, etc.)
- ✅ Map request → command
- ✅ Handle exceptions
- ✅ Return HTTP responses

---

### Application Layer (Commands & Handlers)

**Responsibility:** Orchestrate domain logic and infrastructure

```php
final class AddCommitteeMemberHandler
{
    public function __construct(
        private CommitteeRepositoryInterface $repository,
        private MemberValidationService $validator
    ) {}
    
    public function handle(AddCommitteeMemberCommand $command): void
    {
        // 1. Validate preconditions (infrastructure-level checks)
        if (!$this->validator->canAddMember($command->memberId, $command->tenantId)) {
            throw new \DomainException('Member cannot be added');
        }
        
        // 2. Load aggregate
        $committee = $this->repository->findById(
            CommitteeId::fromString($command->committeeId),
            TenantId::fromString($command->tenantId)
        );
        
        if (!$committee) {
            throw new \DomainException('Committee not found', 404);
        }
        
        // 3. Execute domain logic (aggregate handles validation)
        $committee->addMember(
            MemberId::fromString($command->memberId),
            CommitteeRole::from($command->role)
        );
        
        // 4. Persist changes
        $this->repository->save($committee, TenantId::fromString($command->tenantId));
    }
}
```

**Rules:**
- ❌ No HTTP framework (Request, Response)
- ✅ Use dependency injection
- ✅ Orchestrate domain and infrastructure
- ✅ Convert domain exceptions to readable messages
- ✅ Handle infrastructure failures

---

### Domain Layer (Aggregates & Value Objects)

**Responsibility:** Enforce business rules

```php
final class Committee
{
    private \ArrayObject $members;
    
    public function addMember(MemberId $memberId, CommitteeRole $role): void
    {
        // 1. Validate business rule: no duplicates
        if ($this->hasMember($memberId)) {
            throw new \DomainException("Member {$memberId->value()} already assigned");
        }
        
        // 2. Validate business rule: max committee size
        if ($this->members->count() >= self::MAX_SIZE) {
            throw new \DomainException('Committee is full');
        }
        
        // 3. Create member object
        $member = new CommitteeMember($memberId, $role);
        $this->members->append($member);
        
        // 4. Record event for infrastructure layer
        $this->record(new MemberAssignedToCommittee(
            committeeId: $this->id,
            memberId: $memberId,
            role: $role,
            occurredAt: now()
        ));
    }
}
```

**Rules:**
- ❌ No Laravel dependencies (Eloquent, Facades)
- ❌ No I/O operations (database, HTTP)
- ✅ Pure PHP only
- ✅ Validate all business rules
- ✅ Generate domain events
- ✅ Immutable value objects

---

### Infrastructure Layer (Repositories & Listeners)

**Responsibility:** Persistence and external services

```php
final class EloquentCommitteeRepository implements CommitteeRepositoryInterface
{
    public function findById(CommitteeId $id, TenantId $tenantId): ?Committee
    {
        // Direct Eloquent allowed here
        $model = CommitteeModel::where('organisation_id', $tenantId->value())
            ->find($id->value());
        
        return $model ? $this->toDomain($model) : null;
    }
    
    public function save(Committee $committee, TenantId $tenantId): void
    {
        // 1. Convert domain aggregate to Eloquent model
        $model = CommitteeModel::firstOrCreate(
            ['id' => $committee->id()->value()],
            ['organisation_id' => $tenantId->value()]
        );
        
        $model->fill([
            'name' => $committee->name(),
            'description' => $committee->description(),
            'updated_at' => now()
        ])->save();
        
        // 2. Publish domain events to listeners
        event(new DomainEventDispatched($committee->getRecordedEvents()));
    }
}
```

**Rules:**
- ✅ Laravel allowed freely (Eloquent, Cache, Queue)
- ✅ Direct database access
- ✅ I/O operations
- ✅ Convert between domain and persistence models
- ✅ Dispatch events

---

## Request Lifecycle: Detailed Flow

### Example: Add Member to Committee

```
1. CLIENT (Vue Component)
   ↓
   fetch('/api/v1/governance/committees/{id}/members', {
     method: 'POST',
     headers: {
       'X-Organisation-ID': 'a1ca231c...',
       'Content-Type': 'application/json'
     },
     body: JSON.stringify({ memberId: 'john-id', role: 'member' })
   })

2. ROUTING (bootstrap/app.php)
   ↓
   Route::middleware(['web', 'json.api', 'tenant.api', 'auth', 'verified'])
     ->prefix('api/v1')
     ->group('routes/api_v1.php')
   
   Matched: POST /api/v1/governance/committees/{committeeId}/members

3. MIDDLEWARE EXECUTION (in order)
   
   a) web middleware
      - Session setup
      - Cookie encryption
      - CSRF setup (but exempt for /api/v1/*)
   
   b) json.api (ForceJsonResponse)
      - Sets Accept: application/json header
      - Converts redirect responses to JSON
      
   c) tenant.api (IdentifyTenantFromHeader)
      - Extracts X-Organisation-ID header
      - Validates user has access to org
      - Sets TenantContext::set($organisationId)
      
      ❌ FAILS if header missing or user not authorized
      → 400/403 JSON response
   
   d) auth
      - Validates user is authenticated
      - User available in $request->user()
      
      ❌ FAILS if not authenticated
      → 401 JSON response
   
   e) verified
      - Validates email is verified
      
      ❌ FAILS if email not verified
      → 403 JSON response

4. CONTROLLER (CommitteeMemberController::store)
   ↓
   $tenantId = TenantContext::get()  // "a1ca231c..."
   
   ✓ Validate request: memberId, role
   ✓ Map HTTP input to Command
   
   $command = new AddCommitteeMemberCommand(
       tenantId: $tenantId,
       committeeId: $committeeId,
       memberId: $memberId
   )
   
   ✓ Delegate to handler
   $this->removeHandler->handle($command)

5. APPLICATION HANDLER (AddCommitteeMemberHandler::handle)
   ↓
   ✓ Load committee aggregate from repository
     $committee = $this->repository->findById($committeeId, $tenantId)
   
   ✓ Call domain method
     $committee->addMember($memberId, $role)
   
   ✓ Persist aggregate
     $this->repository->save($committee, $tenantId)

6. DOMAIN AGGREGATE (Committee::addMember)
   ↓
   ✓ Validate business rule: no duplicates
   ✓ Validate business rule: max size
   ✓ Create member object
   ✓ Add to collection
   ✓ Record event: MemberAssignedToCommittee

7. REPOSITORY SAVE
   ↓
   ✓ Convert aggregate to Eloquent model
   ✓ Save to database
   ✓ Dispatch domain events

8. EVENT LISTENERS
   ↓
   Event: MemberAssignedToCommittee fired
   
   Listener: CommitteeMemberProjectionListener::onMemberAssigned
   - Query member_directories for member info
   - Update committee_member_projections table
   - Cache cleared for member list
   
   Listener: SendMemberAssignedNotification
   - Queue email notification

9. RESPONSE
   ↓
   return response()->json([
       'success' => true,
       'message' => 'Member removal command accepted',
   ], 202)
   
   ✓ JSON formatted (forced by json.api middleware)
   ✓ Status 202 (Accepted - event will process)

10. CLIENT (Vue Component)
    ↓
    Response received: { success: true, message: '...' }
    ✓ Refresh member list
    ✓ Show success message
```

---

## Key Files & Their Roles

| File | Purpose | Layer |
|------|---------|-------|
| `bootstrap/app.php` | Route groups, middleware stack, CSRF config | Infrastructure |
| `routes/api_v1.php` | API endpoint definitions | Infrastructure |
| `app/Http/Controllers/Api/Governance/CommitteeMemberController.php` | HTTP adapter | Infrastructure |
| `app/Http/Middleware/IdentifyTenantFromHeader.php` | Tenant extraction | Infrastructure |
| `app/Http/Middleware/ForceJsonResponse.php` | JSON enforcement | Infrastructure |
| `app/Services/TenantContext.php` | Request-scoped tenant ID | Infrastructure |
| `app/Contexts/Governance/Application/Commands/AddCommitteeMemberCommand.php` | Command DTO | Application |
| `app/Contexts/Governance/Application/Handlers/AddCommitteeMemberHandler.php` | Command orchestration | Application |
| `app/Contexts/Governance/Domain/Committee/Committee.php` | Aggregate | Domain |
| `app/Contexts/Governance/Domain/Committee/CommitteeRepositoryInterface.php` | Repository interface | Domain |
| `app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeRepository.php` | Repository implementation | Infrastructure |
| `app/Contexts/Governance/Domain/Committee/Events/MemberAssignedToCommittee.php` | Domain event | Domain |
| `app/Contexts/Governance/Infrastructure/Projection/CommitteeMemberProjectionListener.php` | Event listener | Infrastructure |

---

## Design Decisions & Trade-offs

### Decision 1: Header-Based Tenant Context (not Session-Based)

**Trade-off:**
- ✅ Explicit, testable, RESTful
- ❌ Requires client to send header on every request

**Rationale:**
- API clients should declare which tenant they're accessing
- Easier to test (mock different tenants)
- Stateless - matches REST principles
- Supports multiple tenants in same session

---

### Decision 2: Pure Domain Layer (No Framework Dependencies)

**Trade-off:**
- ✅ Portable, testable, reusable
- ❌ More boilerplate, mapping code needed

**Rationale:**
- Domain logic is business logic - shouldn't be tied to Laravel
- Can be used in CLI, queue jobs, other frameworks
- Easier to test without mocking entire framework
- Forces clean architecture

---

### Decision 3: CQRS Light (not Full CQRS)

**Trade-off:**
- ✅ Simpler than full CQRS, faster to develop
- ❌ Can't scale read/write separately

**Rationale:**
- Project doesn't need massive scale yet
- Full CQRS adds complexity without benefits at this size
- Can upgrade to full CQRS later if needed

---

### Decision 4: Synchronous Events (not Async Queue)

**Trade-off:**
- ✅ Simple, guaranteed execution
- ❌ Can cause slow responses if listeners are expensive

**Rationale:**
- Current listeners are fast (update projections)
- Complex async logic can be added later
- Simpler to debug and reason about

---

## Future Scalability Points

### When Read/Write Separation Needed
Replace QueryService direct Eloquent with read model projections:
```php
// Current: Direct query
$members = CommitteeMemberModel::where(...)->get();

// Future: Dedicated read model
$members = CommitteeMemberProjection::where(...)->get();
```

### When Async Processing Needed
Move listeners to queue jobs:
```php
// Current: Synchronous
event(new MemberAssignedToCommittee(...));
// → CommitteeMemberProjectionListener executes immediately

// Future: Asynchronous
event(new MemberAssignedToCommittee(...));
// → Queued job processes in background
```

### When Caching Needed
Wrap queries with cache keys:
```php
// Current: No cache
$members = $this->queryService->get();

// Future: With cache
$members = Cache::remember(
    "committee:$committeeId:members",
    3600,
    fn() => $this->queryService->get()
);
```

---

**Next:** Read [REQUEST_LIFECYCLE.md](./REQUEST_LIFECYCLE.md) for detailed request flow.
