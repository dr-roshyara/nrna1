# Category C Test Decomposition Pattern

**Status:** Canonical Template Established  
**Date:** 2026-05-15  
**Reference Tests:** 
- Domain: `tests/Feature/Committee/AssignMemberWithLineageTest.php` (6/6 PASS)
- HTTP: `tests/Feature/Committee/CommitteeDashboardHttpTest.php` (3/3 PASS)

---

## The Problem (What Classifier Revealed)

76 Category C tests compress the entire architecture into single test assertion units:

```
domain setup
+ application orchestration
+ HTTP layer
+ middleware
+ ACL/authorization
+ projection assertions
+ tenant resolution
= ONE COGNITIVE UNIT (unstable)
```

This is why failures cascade and debugging is impossible.

---

## The Solution (Controlled Decomposition)

**Each C-INTEGRATION test becomes TWO focused tests:**

### 1. Domain Truth Test
**Asserts:** business invariants, aggregates, policies, state transitions  
**Calls:** handlers directly (no HTTP)  
**Fixtures:** real aggregates from repositories  
**Example:** `AssignMemberWithLineageTest.php`

```php
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
    ]);
}
```

**Key Rules:**
- NO `$this->get()`, `$this->post()`, etc.
- NO Inertia assertions
- NO middleware testing
- Real aggregates only
- Database assertions focus on write model tables
- Exception type assertions (not message strings)

---

### 2. HTTP Integration Test
**Asserts:** routing, authentication, authorization, response format  
**Calls:** endpoints via HTTP (Router → Middleware → Controller)  
**Fixtures:** minimal domain setup via factories  
**Example:** `CommitteeDashboardHttpTest.php`

```php
public function test_auth_user_can_view_own_committee_dashboard(): void
{
    $committee = CommitteeModel::factory()->create([
        'organisation_id' => $this->organisation->id,
        'geo_unit_id' => null,
        'type' => 'central',
        'status' => 'active',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('committee.dashboard', [
            'organisation' => $this->organisation,
            'committee' => $committee->slug,
        ]));

    $response->assertStatus(200);
    $response->assertInertia(fn (AssertableInertia $page) =>
        $page->component('Committee/Dashboard')
             ->where('committee.id', (string) $committee->id)
    );
}
```

**Key Rules:**
- Use factories for fixture setup (no domain aggregate construction)
- Make HTTP request via `$this->get()`, `$this->post()`, etc.
- Assert on HTTP response: status, redirects, Inertia props
- Test auth/authorization scenarios
- NO handler invocation
- NO database state assertions (those belong in domain test)

---

## Naming Convention

```
[Feature]
├── [Entity]Test.php              ← DOMAIN truth test
└── [Entity]HttpTest.php          ← HTTP integration test
```

**Examples:**
```
CommitteeDashboardTest.php       → domain persistence + retrieval
CommitteeDashboardHttpTest.php   → HTTP endpoint rendering

AssignMemberWithLineageTest.php  → domain assignment logic
AssignMemberHttpTest.php         → (future) HTTP endpoint for assignment
```

---

## Test Execution Strategy

### Phase 1: Decompose Membership/Committee Tests (10-15 tests)
These are the foundation and highest priority.

```bash
tests/Feature/Committee/*Test.php
tests/Feature/Membership/*Test.php
tests/Unit/Contexts/Membership/*Test.php
```

### Phase 2: Decompose Auth/Election Tests (20 tests)
These establish system boundaries.

```bash
tests/Feature/Auth/*Test.php
tests/Feature/Election/*Test.php
```

### Phase 3: Decompose Remaining Integration (remaining ~35 tests)
Apply pattern to all other C-INTEGRATION tests.

---

## Verification Checklist (Per Test)

- [ ] Domain test passes (handler call, DB assertions)
- [ ] Domain test does NOT make HTTP calls
- [ ] HTTP test passes (endpoint call, response assertions)
- [ ] HTTP test does NOT invoke handlers directly
- [ ] No test duplicates business logic
- [ ] Both tests use appropriate fixtures
- [ ] Exception types are asserted (not message strings)
- [ ] Test names follow convention
- [ ] No projection assertions in domain test
- [ ] No business rule assertions in HTTP test

---

## Why This Works

```yaml
BEFORE:
  One test = domain + HTTP + middleware + ACL + projection
  Failure = unclear which layer broke
  
AFTER:
  Domain test = domain truth (fast, simple, stable)
  HTTP test = integration flow (end-to-end verification)
  Failure = clear which layer has the problem
```

**Result:**
- Domain tests verify business correctness
- HTTP tests verify endpoint contracts
- Each test has single responsibility
- Debugging is deterministic

---

## Expected Outcome

```
66 C-INTEGRATION tests
→ Split into 132 focused tests
→ Domain tests: 66 (pure business logic)
→ HTTP tests: 66 (endpoint verification)
→ Category C eliminated permanently
→ System becomes measurable and governable
```

---

## Key Principle

> **One test = one architectural truth**

Not:

```
One test = entire architecture
```

This principle governs all future test design in this system.

---

**Next Step:** Apply this pattern to first 5 C-INTEGRATION tests, verify all pass, then batch-apply to remaining 61 tests.
