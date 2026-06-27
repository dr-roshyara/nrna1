# API Architecture Developer Guide

This guide documents the complete architecture of the Public Digit Voting Platform's API layer, built with Laravel 11 + Inertia 2.0, following Hexagonal Architecture principles and Domain-Driven Design.

## 📚 Documentation Index

### Core Architecture
- **[ARCHITECTURE.md](./ARCHITECTURE.md)** — System-wide architecture overview, layers, and design decisions
- **[REQUEST_LIFECYCLE.md](./REQUEST_LIFECYCLE.md)** — How a request flows from client to response
- **[MIDDLEWARE_PIPELINE.md](./MIDDLEWARE_PIPELINE.md)** — Detailed middleware execution order and responsibilities

### Multi-Tenancy & Security
- **[MULTI_TENANCY.md](./MULTI_TENANCY.md)** — Tenant isolation, context management, and security
- **[CSRF_AND_AUTHENTICATION.md](./CSRF_AND_AUTHENTICATION.md)** — CSRF protection and auth strategies

### Development Guide
- **[BEST_PRACTICES.md](./BEST_PRACTICES.md)** — Development guidelines and patterns
- **[DEBUGGING_GUIDE.md](./DEBUGGING_GUIDE.md)** — Common issues, their root causes, and solutions

---

## 🚀 Quick Start: Adding a New API Endpoint

### Step 1: Define Routes

File: `routes/api_v1.php`

```php
// ✅ DO NOT add prefix here - bootstrap/app.php handles it
// ✅ Routes should be direct paths without nesting

Route::get('/governance/committees/{committeeId}/members', [CommitteeMemberController::class, 'index'])
    ->name('governance.committees.members.index');

Route::post('/governance/committees/{committeeId}/members', [CommitteeMemberController::class, 'store'])
    ->name('governance.committees.members.store');
```

### Step 2: Create Controller (HTTP Adapter)

File: `app/Http/Controllers/Api/Governance/CommitteeMemberController.php`

```php
final class CommitteeMemberController extends Controller
{
    public function __construct(
        private CommitteeMemberQueryService $queryService,
        private CommitteeRepositoryInterface $repository,
    ) {}

    public function index(string $committeeId): JsonResponse
    {
        try {
            $tenantIdValue = TenantContext::get();
            if (!$tenantIdValue) {
                return response()->json(['error' => 'Missing tenant context'], 400);
            }
            
            // Call application layer
            $members = $this->queryService->getMembersForCommittee(
                CommitteeId::fromString($committeeId),
                TenantId::fromString($tenantIdValue)
            );

            return response()->json(['members' => $members]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
```

### Step 3: Create Domain Command/Handler (if write operation)

File: `app/Contexts/Governance/Application/Commands/AddCommitteeMemberCommand.php`

```php
final class AddCommitteeMemberCommand
{
    public function __construct(
        public string $tenantId,
        public string $committeeId,
        public string $memberId,
    ) {}
}
```

File: `app/Contexts/Governance/Application/Handlers/AddCommitteeMemberHandler.php`

```php
final class AddCommitteeMemberHandler
{
    public function __construct(
        private CommitteeRepositoryInterface $repository
    ) {}

    public function handle(AddCommitteeMemberCommand $command): void
    {
        $committee = $this->repository->findById(
            CommitteeId::fromString($command->committeeId),
            TenantId::fromString($command->tenantId)
        );

        if (!$committee) {
            throw new \DomainException('Committee not found', 404);
        }

        $committee->addMember(
            MemberId::fromString($command->memberId),
            CommitteeRole::member
        );

        $this->repository->save($committee, TenantId::fromString($command->tenantId));
    }
}
```

### Step 4: Test with Proper Headers

```bash
curl -X POST http://localhost:8000/api/v1/governance/committees/01KRQ774D3PB5JW6A5AWZ87T50/members \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -H "X-Organisation-ID: a1ca231c-59aa-4950-8b23-75b16d5c176a" \
  -H "Content-Type: application/json" \
  -d '{"memberId":"john-doe-id","role":"member"}' \
  -b "PHPSESSID=your-session-id"
```

---

## 🏗️ Architectural Layers

```
┌─────────────────────────────────────────────────┐
│                  HTTP Layer                     │
│  (Controllers, Routes, Requests, Responses)    │
└──────────────────┬──────────────────────────────┘
                   │
┌──────────────────▼──────────────────────────────┐
│             Application Layer                   │
│  (Commands, Handlers, DTOs, Query Services)    │
│  • No Laravel dependencies in domain logic     │
│  • Clean orchestration of business rules       │
└──────────────────┬──────────────────────────────┘
                   │
┌──────────────────▼──────────────────────────────┐
│              Domain Layer                       │
│  (Aggregates, Value Objects, Events)           │
│  • Pure PHP - no framework                     │
│  • Business rules enforcement                  │
│  • Event generation                            │
└──────────────────┬──────────────────────────────┘
                   │
┌──────────────────▼──────────────────────────────┐
│           Infrastructure Layer                  │
│  (Repositories, Persistence, External Services)│
│  • Laravel allowed freely                      │
│  • Database access, caching, queues            │
└─────────────────────────────────────────────────┘
```

---

## 🔑 Key Principles

### 1. **Clean Separation of Concerns**
- Controllers are thin HTTP adapters only
- Domain logic is framework-agnostic
- Application layer orchestrates between them

### 2. **Multi-Tenancy First**
- Every request requires `X-Organisation-ID` header
- Tenant context set via middleware before auth
- All queries automatically scoped to tenant

### 3. **CQRS Light Pattern**
- Reads use QueryService (simple Eloquent)
- Writes go through Commands + Handlers (domain aggregate)
- Events trigger projections for UI-ready data

### 4. **Domain Events**
- All writes generate domain events
- Events are processed by listeners
- Projections keep read models in sync

### 5. **No Magic**
- Explicit over implicit
- No route model binding on write endpoints
- No automatic result transforms

---

## 📊 Typical Request Flow

```
Client Request
    ↓
Route Matching (routes/api_v1.php)
    ↓
Middleware Stack (web → json.api → tenant.api → auth → verified)
    ↓ (TenantContext set, CSRF checked, JSON forced)
    ↓
Controller (HTTP Adapter)
    ↓ (maps request → command/query)
    ↓
Application Layer (Command Handler / Query Service)
    ↓
Domain Layer (Aggregate / Value Objects)
    ↓ (events generated)
    ↓
Infrastructure Layer (Repository)
    ↓ (saves to DB)
    ↓
Event Dispatching → Listeners
    ↓ (projections updated)
    ↓
JSON Response
```

---

## 🐛 Common Debugging Points

See **[DEBUGGING_GUIDE.md](./DEBUGGING_GUIDE.md)** for detailed debugging strategies and solutions to common issues.

### Quick Reference

| Symptom | Likely Cause | Fix |
|---------|-------------|-----|
| 404 on valid route | Route prefix duplication | Check routes/api_v1.php - no nesting |
| 401/403 auth error | Middleware order wrong | Ensure tenant.api before auth |
| Missing tenant header error | Header not sent by client | Check X-Organisation-ID in request |
| SQL error with organisation_id | Column mismatch in query | Use organisation_id not tenant_id |
| HTML response instead of JSON | Inertia middleware intercepted | Verify route in api_v1.php, not web.php |
| CSRF token missing | Wrong exemption config | Check bootstrap/app.php CSRF exemption |

---

## 📖 Configuration Files

### bootstrap/app.php
- Defines API v1 routing and middleware stack
- Configures CSRF exemptions
- Sets up middleware aliases
- Exception rendering rules

### routes/api_v1.php
- Defines all API v1 endpoints
- **Important:** No prefix groups - bootstrap/app.php handles prefix

### routes/web.php
- Inertia web routes only
- Separate from API routes entirely

---

## ✅ Checklist for New Endpoints

- [ ] Routes defined in `routes/api_v1.php` without prefix nesting
- [ ] Controller is thin HTTP adapter
- [ ] Middleware stack in bootstrap/app.php correct
- [ ] Tenant context extracted and validated
- [ ] Command/Handler created for writes (domain logic)
- [ ] QueryService created for reads
- [ ] Domain events generated for important operations
- [ ] Projection listeners created if UI needs data
- [ ] Tests written with proper tenant context
- [ ] API docs updated
- [ ] Route cache cleared before testing

---

## 🔍 Testing Your Endpoint

### From Browser Console (Vue Component)

```javascript
// Test authentication
await fetch('/api/v1/test-auth', {
    credentials: 'include',
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-Organisation-ID': 'your-org-id'
    }
}).then(r => r.json()).then(console.log);
```

### From cURL

```bash
# Include session cookies and proper headers
curl -X POST http://localhost:8000/api/v1/your-endpoint \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -H "X-Organisation-ID: org-id" \
  -H "Content-Type: application/json" \
  -d '{"key":"value"}' \
  -b "PHPSESSID=session-id"
```

---

## 📚 Further Reading

- See [ARCHITECTURE.md](./ARCHITECTURE.md) for design patterns
- See [REQUEST_LIFECYCLE.md](./REQUEST_LIFECYCLE.md) for request flow details
- See [DEBUGGING_GUIDE.md](./DEBUGGING_GUIDE.md) for troubleshooting
- See [BEST_PRACTICES.md](./BEST_PRACTICES.md) for coding guidelines

---

**Last Updated:** 2026-05-17  
**Author:** Claude + Nab Roshyara  
**Status:** Complete & Tested
