# 🧠 KNOWLEDGE TRANSFER DOCUMENT (KTD) — ARCHITECTURE GOVERNANCE

## PublicDigit / Governance Platform — DDD + Laravel 11 System

**Date:** 2026-05-15  
**Purpose:** System-wide architectural discipline and testing governance  
**Audience:** Claude Code CLI / Next Session Mentor Loop  

---

# 1. 🎯 SYSTEM PURPOSE

We are building a:

> **Multi-tenant Governance & Committee Management Platform (PublicDigit)**

Core capabilities:

* Committee lifecycle management
* Membership lineage tracking
* Governance decisions & transitions
* Geo-based committee structures
* Auditability & immutability guarantees

---

# 2. 🧱 ARCHITECTURE MODEL

## 2.1 Layered DDD Architecture

```
┌──────────────────────────────┐
│ Presentation (HTTP / Vue)    │
├──────────────────────────────┤
│ Application Layer            │
│ (Use Cases / Services)       │
├──────────────────────────────┤
│ Domain Layer                 │
│ (Aggregates / VOs / Rules)   │
├──────────────────────────────┤
│ Infrastructure Layer         │
│ (Eloquent / DB / API)        │
└──────────────────────────────┘
```

---

## 2.2 Key Domain Concepts

### Core Aggregates

* `Committee` (FINAL aggregate root)
* `Membership`
* `Organisation`
* `GovernanceDecision`

### Value Objects

* `CommitteeId`
* `TenantId`
* `GeoUnitId`
* `ParticipantRole`

---

## 2.3 Domain Rules

* Aggregates are **final**
* Domain objects are **never mocked**
* Domain invariants are tested via **real instances**
* Repositories return **real aggregates only**

---

# 3. 🧪 TESTING ARCHITECTURE (CRITICAL)

## 3.1 3-Layer Testing Strategy

---

## 🟢 LAYER 1 — Domain Tests (PURE UNIT)

Rules:

* NO Laravel
* NO database
* NO mocks for domain objects

Allowed:

* real aggregates
* factory methods
* value objects

Example:

```php
Committee::createCentral(...)
```

---

## 🟡 LAYER 2 — Application Service Tests

Purpose:

* test orchestration logic

Allowed mocking:

* repositories
* read models
* ports/adapters

NOT allowed:

* mocking domain aggregates (Committee)

Correct pattern:

```php
$committee = Committee::createCentral(...);

$repo->shouldReceive('findById')->andReturn($committee);
```

---

## 🟣 LAYER 3 — Feature / HTTP Integration Tests

Rules:

* real HTTP requests
* real DB (RefreshDatabase)
* real migrations
* real repositories

Assertions:

* DB state
* not HTTP redirect success

---

# 4. ⚠️ CRITICAL ANTI-PATTERNS

## ❌ DO NOT DO THIS

### 1. Mock domain aggregates

```php
Mockery::mock(Committee::class); ❌
```

### 2. Trust HTTP redirect as success

```php
assertRedirect(); ❌ meaningless alone
```

### 3. Hide exceptions in feature tests

```php
try/catch swallowing ❌
```

### 4. Session-based tenant coupling in domain

```php
session('current_organisation_id') ❌ in domain
```

---

# 5. 🧭 TENANT CONTEXT ARCHITECTURE

## 5.1 Current Design (Correct Direction)

```
Middleware → TenantContext → Domain / Application
```

### TenantContext rules:

* static in-memory holder
* NO domain logic
* NO persistence
* NO Eloquent

```php
TenantContext::set($id);
TenantContext::get();
```

---

## 5.2 Tenant Resolution Priority

```
1. Route model binding ({organisation})
2. HTTP header (X-Tenant-Id)
3. Session fallback (temporary compatibility)
```

---

# 6. 🧨 DATABASE & CONSTRAINT RULES

## 6.1 PostgreSQL is source of truth

* CHECK constraints are enforced strictly
* invalid values fail transaction immediately
* transaction abort cascades all further queries

---

## 6.2 Common failure types

### A. Silent HTTP success but no DB insert

Cause:

* validation failure
* service early return
* swallowed exception

---

### B. CHECK constraint violation

Cause:

* domain enum mismatch
* migration drift
* inconsistent test data

---

### C. Transaction abort cascade (25P02)

Cause:

* first DB error invalidates transaction

---

## 6.3 Rule

> If DB rejects data → test must FAIL FAST, not cascade

---

# 7. 🧪 FEATURE TEST DEBUG STRATEGY

## Mandatory debugging steps:

### Step 1

```php
$this->withoutExceptionHandling();
```

### Step 2

Inspect response:

```php
$response->dump();
$response->getContent();
```

### Step 3

Assert DB directly:

```php
assertDatabaseHas(...)
```

### Step 4

Check validation errors:

```php
$response->assertSessionHasErrors();
```

---

# 8. 🧠 DOMAIN vs INFRASTRUCTURE RULE

| Concept             | Rule                 |
| ------------------- | -------------------- |
| Committee           | REAL object only     |
| Repository          | abstraction boundary |
| DB constraints      | enforcement layer    |
| Application service | orchestration only   |

---

# 9. 🚨 CORE ARCHITECTURAL PRINCIPLES

## 9.1 Final classes rule

* Domain aggregates are FINAL
* NEVER mocked
* ALWAYS real instances in tests

---

## 9.2 Mocking rule

| Layer          | Mock allowed          |
| -------------- | --------------------- |
| Domain         | ❌ never               |
| Application    | ✔ ports only          |
| Infrastructure | ✔ everything external |

---

## 9.3 Test truth hierarchy

```
DB state > Domain state > HTTP response > Logs
```

---

# 10. 🧪 TEST EXECUTION GOVERNANCE MODEL

## Pseudo Algorithm for ALL tests:

```
FOR each test:
    IF Layer 1:
        use real domain objects only
        no Laravel

    IF Layer 2:
        mock repositories only
        use real domain objects

    IF Layer 3:
        use real HTTP + DB
        assert DB state, not HTTP success

    ALWAYS:
        disable exception hiding
        assert DB state explicitly
```

---

# 11. 🔄 CLAUDE CODE CLI MENTOR LOOP

## Execution Protocol

### Step 1 — Claude proposes change

```
Claude outputs implementation or test
```

---

### Step 2 — Validation rules

Check:

```
IF domain object is mocked:
    REJECT

IF DB state not asserted:
    REJECT

IF HTTP redirect used as success:
    REJECT

IF tenant context touches session in domain:
    REJECT
```

---

### Step 3 — Approval logic

```
IF code passes rules:
    APPROVE

ELSE:
    RETURN correction instructions
```

---

### Step 4 — Escalation rule

```
IF architectural uncertainty:
    CONSULT ChatGPT for second opinion
```

---

# 12. 🧭 ARCHITECTURE ESCALATION POLICY

Always escalate when:

* tenant model ambiguity exists
* DB constraints conflict with domain
* repository returns inconsistent aggregates
* test design becomes unclear between layers

---

# 13. 🧩 FINAL SYSTEM STATE SUMMARY

You are building:

> A DDD-based multi-tenant governance platform with strict separation between:

* Domain truth (pure PHP)
* Application orchestration
* Infrastructure persistence
* HTTP boundary layer

Key constraints:

* final aggregates
* strict_types=1 everywhere
* PostgreSQL enforced integrity
* layered testing model

---

# 🚀 NEXT CHAT START PROMPT

Use this to bootstrap the next session:

```
You are mentoring a DDD Laravel 11 system (PublicDigit).

Follow this architecture strictly:
- Domain aggregates are final and never mocked
- Layered testing: Domain / Application / Feature
- PostgreSQL constraints are source of truth
- TenantContext replaces session usage in domain
- Feature tests must assert DB state, not HTTP success
- Always validate against KTD rules

If uncertain, escalate architecture decisions.

Now analyze the next implementation or test and decide:
APPROVE / REJECT / ESCALATE
```

---

# 📋 REFERENCE IMPLEMENTATION PATTERNS

## Pattern 1: Domain Test (Layer 1)

```php
<?php
declare(strict_types=1);

namespace Tests\Unit\Domain\Committee;

use App\Contexts\Committee\Domain\Committee;
use App\Contexts\Committee\Domain\CommitteeId;
use PHPUnit\Framework\TestCase;

final class CommitteeTest extends TestCase
{
    public function test_committee_is_created_as_final(): void
    {
        // No mocking - use real aggregate
        $committee = Committee::createCentral(
            CommitteeId::generate(),
            'Board of Directors'
        );

        $this->assertNotNull($committee->getId());
        $this->assertTrue($committee->isCentral());
    }
}
```

---

## Pattern 2: Application Service Test (Layer 2)

```php
<?php
declare(strict_types=1);

namespace Tests\Unit\Application\Committee;

use App\Contexts\Committee\Application\AssignMemberService;
use App\Contexts\Committee\Domain\Committee;
use Mockery;
use PHPUnit\Framework\TestCase;

final class AssignMemberServiceTest extends TestCase
{
    public function test_assigns_member_to_committee(): void
    {
        // Create REAL domain aggregate
        $committee = Committee::createCentral(...);

        // Mock repository PORT only
        $repository = Mockery::mock(CommitteeRepositoryPort::class);
        $repository->shouldReceive('save')->once();

        $service = new AssignMemberService($repository);
        $service->assign($committee, $memberId);

        Mockery::close();
    }
}
```

---

## Pattern 3: Feature Test (Layer 3)

```php
<?php
declare(strict_types=1);

namespace Tests\Feature\Committee;

use App\Models\Committee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AssignMemberFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_assigned_to_committee(): void
    {
        $this->withoutExceptionHandling();

        $committee = Committee::factory()->create();
        $memberId = '...';

        $response = $this->post('/committees/' . $committee->id . '/members', [
            'member_id' => $memberId,
        ]);

        // Assert DB state, NOT redirect
        $this->assertDatabaseHas('committee_memberships', [
            'committee_id' => $committee->id,
            'member_id' => $memberId,
            'status' => 'active',
        ]);

        $response->assertRedirect();
    }
}
```

---

**End of Architecture Governance KTD**
