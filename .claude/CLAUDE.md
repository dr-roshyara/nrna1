# ⚙️ Engineering Process — EP-01 Plan First (POINTER, not a restatement)

**Follow the PublicDigit Engineering Process.** For every **non-trivial engineering task**: perform the **Engineering Readiness Review (EP-03)** — derive the business/DDD/architecture/TDD/design/impact/verification answers from the repository, ask the human only what cannot be derived — then the **Planning Stage (EP-01)**: produce the plan → **wait for explicit human approval (APPROVAL APPLIES TO THE PLAN, not merely to the task request)** → implement only the approved plan → **Completion Review (EP-02): did we implement the approved plan?** **If implementation invalidates the approved plan: STOP, explain why, present the revised plan, wait for approval — never silently change direction.** *(Provider binding: in Claude Code the Planning Stage maps to Plan Mode.)*

Rule text, exception tiers (plan required/optional/not-required), and ratification status live **once**, in `docs/implementation/Implementation_Process_v1.1_Draft.md` §"Execution Rules — Engineering Process (EP)". This section is provider-agnostic: any assistant (Claude, Copilot, Cursor, Gemini, Codex) obeys the same process.

**Engineering Decisions (POINTER — this runtime binding is FROZEN; it grows no further):** Before acting, resolve the required Engineering Decisions. See `engineering/architecture/reference/Engineering_Decision_Model.md`. Canonical rules: `engineering/governance/STANDARDS_INDEX.md` → ES-001..ES-006. MEMORY carries hints; the ES documents are the truth.

---

# 🎨 BEFORE ANYTHING: READ UI_GUIDELINES.md

All UI/Vue changes must follow the design system in `.claude/UI_GUIDELINES.md`.

**Quick checklist before editing any .vue file:**
1. Read the Rule Levels (MUST/SHOULD/MAY)
2. Check `design-system.exceptions.json` for approved deviations
3. Use semantic tokens (primary, success, danger, neutral)
4. Use canonical components (<Button>, <Card>, etc.)
5. Run `npm run design-check` after changes
6. Report violations before/after in your response

**The design system is executable.** It's not aspirational. Don't work around it.

---

# 📚 Engineering Knowledge Platform (EKP)

Project knowledge is a governed engineering asset under `docs/knowledge/`, with the same discipline as code.

- **Entry point:** [`docs/knowledge/portal/INDEX.md`](../docs/knowledge/portal/INDEX.md) — role-based navigation, topic hubs, recipes, packages.
- **Rules:** [`Knowledge-Constitution.md`](../docs/knowledge/Knowledge-Constitution.md) + [`_meta/lifecycle.md`](../docs/knowledge/_meta/lifecycle.md). Every governed doc needs a knowledge card (see [`_meta/knowledge-card.template.md`](../docs/knowledge/_meta/knowledge-card.template.md)).
- **AI rule:** AI-generated knowledge enters under `docs/knowledge/ai/` as `authority: generated` and is **never authoritative without human review**.
- **Validate:** `npm run knowledge-lint` (PHPStan-for-knowledge) · regenerate graph with `npm run knowledge-graph`.
- **Reference model:** the **Adjudication** pilot at [`docs/knowledge/domains/adjudication/`](../docs/knowledge/domains/adjudication/README.md).
- Legacy folders during transition: `architecture/` = Think, `docs/` = Official Truth, `developer_guide/` = Build.

**Documentation placement (POINTER — the rule is EXECUTABLE, so it is not restated here).** **Where a document belongs is derived, never chosen — resolve it, never hard-code a root:**

```bash
php scripts/doc-placement.php --scope=<product-specific|cross-product|session-state> \
                              [--maturity=<research|qualified|adopted>] [--domain=<id>]
php scripts/doc-placement.php --list      # domains, roots, rules
```

Policy: `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` · configuration: `docs/knowledge/schema/documentation-placement.yaml`. **Exit code 2 = unruled: record `PENDING` and escalate.**

---

# 🏗️ Backend Architecture: Laravel with Discipline

**Laravel is the framework. Use it, don't fight it.**

But use it with **discipline**:

```
Infrastructure layer  → Laravel features allowed
Application layer    → Laravel features limited
Domain layer         → No Laravel dependencies
```

---

# Layer Rules with Laravel Pragmatism

## Layer 1: Infrastructure (Laravel allowed)

| Feature | Status | Use Case |
|---------|--------|----------|
| Facades | ✅ Allowed | Cache, Log, Queue, config |
| Eloquent | ✅ Allowed | Read models, simple writes |
| Route model binding | ✅ Allowed | API controllers only |
| Eloquent events | ✅ Allowed | Cache invalidation, logging |
| SoftDeletes | ✅ Allowed | Read models |
| Traits | ✅ Allowed | Testing, infrastructure reuse |

## Layer 2: Application (Limited)

| Feature | Status | Alternative |
|---------|--------|-------------|
| Facades | ❌ Banned | Constructor injection |
| Eloquent | ❌ Banned | Repository interface |
| Route binding | ❌ Banned | Explicit `findOrFail()` |
| Events | ✅ Allowed | Domain events only |
| DTOs | ✅ Required | No arrays |

## Layer 3: Domain (No Laravel)

| Feature | Status |
|---------|--------|
| Anything Laravel | ❌ Completely banned |
| Facades, Eloquent, Events | ❌ Forbidden |
| Pure PHP only | ✅ Required |

---

# Revised Folder Structure

```
app/

# Domain - Pure PHP, no framework dependencies
Domain/
    User/
        Entity/
            User.php              # Business logic, no Eloquent
            UserId.php            # Value object
        Event/
            UserRegistered.php    # Domain event
        Repository/
            UserRepository.php    # Interface only
        Exception/
            UserAlreadyExists.php # Business exception

# Application - Orchestration, limited framework use
Application/
    User/
        Command/
            RegisterUserCommand.php
            ChangePasswordCommand.php
        DTO/
            RegisterUserDto.php   # readonly class
        Handler/
            # Query handlers for reads

# Infrastructure - Laravel allowed freely
Infrastructure/
    Persistence/
        Eloquent/
            UserModel.php          # Eloquent model
        Repository/
            EloquentUserRepository.php  # Implements interface
    Cache/
        RedisCache.php
    Queue/
        LaravelQueueAdapter.php

# Interface - HTTP layer
Http/
    Controllers/
        UserController.php
    Requests/
        RegisterUserRequest.php    # FormRequest, creates DTO
    Resources/
        UserResource.php           # API transformation

# Read Models - Simple, Eloquent allowed
ReadModels/
    UserReadModel.php              # Direct Eloquent queries
```

---

# Revised Rules (15 → 10 Pragmatic Rules)

## Rule 1: Framework Boundaries

**Domain layer: Zero Laravel dependencies.**
**Application layer: Limited Laravel.**
**Infrastructure: Laravel allowed freely.**

```php
// Domain - Pure PHP only
final class User {
    public function __construct(
        private readonly UserId $id,
        private readonly Email $email
    ) {}
}

// Application - Repository interface, no Eloquent
final class RegisterUserCommand {
    public function __construct(
        private readonly UserRepository $repository  // Interface
    ) {}
}

// Infrastructure - Eloquent allowed
final class EloquentUserRepository implements UserRepository {
    public function __construct(
        private readonly UserModel $model  // Eloquent allowed here
    ) {}
}
```

---

## Rule 2: Facades in Infrastructure Only

**Facades allowed in Infrastructure. Forbidden in Application.**

```php
// ✅ Infrastructure - Allowed
final class CacheUserRepository implements UserRepository {
    public function find(UserId $id): ?User {
        return Cache::remember($key, 3600, function() use ($id) {
            return $this->inner->find($id);
        });
    }
}

// ❌ Application - Forbidden
final class RegisterUserCommand {
    public function execute(RegisterUserDto $dto): void {
        Cache::put('user_' . $dto->getId(), $dto); // NO
    }
}
```

---

## Rule 3: Route Model Binding Only in API Controllers

**Allowed for simple read endpoints. Forbidden for write operations.**

```php
// ✅ GET endpoint - allowed
Route::get('/api/users/{user}', function (User $user) {
    return UserResource::make($user);
});

// ❌ POST/PUT/DELETE - use explicit findOrFail
Route::put('/api/users/{id}', function (string $id, UpdateUserRequest $request) {
    $user = User::findOrFail($id);  // Explicit
    // ...
});
```

---

## Rule 4: DTOs for Application Layer (No Arrays)

**Application layer never accepts arrays.**

```php
// ✅ Correct
final class RegisterUserCommand {
    public function execute(RegisterUserDto $dto): UserId { ... }
}

// ✅ FormRequest creates DTO
final class RegisterUserRequest extends FormRequest {
    public function getDto(): RegisterUserDto {
        return new RegisterUserDto(
            Email::fromString($this->get('email')),
            $this->get('name')
        );
    }
}
```

---

## Rule 5: Read Model vs Write Model (CQRS Light)

**Use Eloquent directly for reads. Use Repository + DTO for writes.**

```php
// ✅ Reads - Eloquent directly
final class UserController {
    public function index(): AnonymousResourceCollection {
        return UserResource::collection(User::paginate());
    }
    
    public function show(User $user): UserResource {
        return UserResource::make($user);
    }
}

// ✅ Writes - Repository pattern
final class UpdateUserCommand {
    public function __construct(private readonly UserRepository $repository) {}
    public function execute(UpdateUserDto $dto): void {
        $user = $this->repository->find($dto->getUserId());
        $user->changeEmail($dto->getEmail());
        $this->repository->save($user);
    }
}
```

---

## Rule 6: Value Objects for Domain Concepts

**Email, UserId, Money are Value Objects. Not strings, not ints.**

```php
final readonly class Email {
    private function __construct(private string $value) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email: {$value}");
        }
    }
    
    public static function fromString(string $value): self {
        return new self($value);
    }
    
    public function toString(): string {
        return $this->value;
    }
}
```

---

## Rule 7: Domain Events + Eloquent Events Coexist

| Event Type | Use For |
|------------|---------|
| Domain events | Business logic (OrderPlaced → UpdateInventory) |
| Eloquent events | Infrastructure (saved → ClearCache) |

```php
// ✅ Domain event - business logic
final class Order {
    public function complete(): void {
        $this->record(new OrderCompleted($this->id));
    }
}

// ✅ Eloquent event - infrastructure
class Product extends Model {
    protected static function booted(): void {
        static::saved(function (Product $product) {
            Cache::forget('product_' . $product->id);
        });
    }
}
```

---

## Rule 8: Exceptions by Layer

| Layer | Exception Type | User sees message? |
|-------|---------------|-------------------|
| Domain | `DomainException` | ✅ Yes |
| Application | `ApplicationException` | ✅ Yes |
| Infrastructure | `RuntimeException` | ❌ No (500 error) |

```php
// Domain
final class UserAlreadyExistsException extends DomainException {
    public function __construct(Email $email) {
        parent::__construct("User {$email->toString()} already exists");
    }
}

// Application
final class InvalidCredentialsException extends ApplicationException {
    public function __construct() {
        parent::__construct('Invalid email or password');
    }
}

// Infrastructure - logs only
throw new RuntimeException('Database connection failed');
```

---

## Rule 9: Repository Pattern for Aggregates Only

**Do not create repositories for every table. Only for aggregates.**

```php
// ✅ Aggregate - has repository
final class Order { ... }  // OrderRepository exists
final class User { ... }   // UserRepository exists

// ✅ Simple entity - no repository needed
final class LogEntry { ... }  // Use Eloquent directly

// ✅ Many-to-many - no repository
// Use relationship methods: $user->roles()->attach()
```

---

## Rule 10: Final Classes in Domain Only

| Layer | Final? |
|-------|--------|
| Domain | ✅ All classes final |
| Application | ✅ Command/Handler classes final |
| Infrastructure | ⚠️ Optional (flexibility needed) |
| HTTP | ⚠️ Controllers can be non-final |

---

# Decision Tree: When to Use Each Style

```
Starting new feature:

Is this simple CRUD?
    ↓ YES → Use Eloquent + Controller (Laravel way)
    ↓ NO

Does it have complex business rules?
    ↓ YES → Use Domain + Command + Repository (Your rules)
    ↓ NO → Use Laravel way

Is this only for reading data?
    ↓ YES → Use Eloquent + Resource (Laravel way)
    ↓ NO

Is this a write operation with validation?
    ↓ YES → Use FormRequest + Command + Repository
```

---

# When to Apply Full Clean Architecture

| Project Type | Apply Full Rules? |
|--------------|-------------------|
| Internal admin panel | ❌ Overkill |
| Simple blog | ❌ Not needed |
| E-commerce with complex pricing | ✅ Yes |
| Banking/fintech | ✅ Yes |
| Multi-year enterprise system | ✅ Yes |
| API with 50+ endpoints | ⚠️ Only for complex endpoints |
| Solo developer side project | ❌ No |

---

**The revised rules:**
- Keep the architectural rigor
- Stop fighting the framework
- Use Laravel where it shines (reads, simple CRUD)
- Apply Clean Architecture only where needed (complex writes, business logic)

---

## The Golden Rule

> **Use Laravel for what Laravel is good at.**
> **Use Clean Architecture for what you need to protect.**
> **Know the difference.**  
---


## **⚠️ LARAVEL 11 + INERTIA 2.0 MIGRATION RULES (CRITICAL)**

### **Framework Versions**
- **Laravel:** 8 → 11 (Major version jump)
- **Inertia.js:** 1.0 → 2.0 (Breaking changes)
- **Date:** Post-migration (Inertia 1.0 behavior NO LONGER works)

### **CRITICAL: Form Submissions with Inertia 2.0**

**❌ WRONG (Inertia 1.0 way - BROKEN NOW):**
```javascript
// DO NOT USE - This returns 302 redirects instead of JSON
import { useCsrfRequest } from './useCsrfRequest';
const csrfRequest = useCsrfRequest();
await csrfRequest.post('/endpoint', data);
```

**✅ RIGHT (Inertia 2.0 way - USE THIS):**
```javascript
// ALWAYS use for form submissions on Inertia pages
import { router } from '@inertiajs/vue3';
router.post('/endpoint', data, {
  preserveState: true,
  preserveScroll: true,
  onSuccess: (page) => { /* handle */ },
  onError: (errors) => { /* handle */ },
  onFinish: () => { /* cleanup */ }
});
```

### **Why This Matters:**

| Aspect | Raw Fetch ❌ | Inertia Router ✅ |
|--------|------------|---------------|
| **CSRF Tokens** | Manual setup needed | Automatic (meta tags) |
| **Headers** | `Accept`, `X-Requested-With` not set | Automatic |
| **Redirects** | Returns HTML (breaks app) | Handled correctly |
| **Flash Messages** | Not available | Via `page.props.flash` |
| **Status Codes** | Returns 302 instead of 200 | Returns 200 on success |

### **Backend Controller Pattern:**

```php
// For Inertia 2.0: Simple redirects with flash messages
public function store(Request $request)
{
    try {
        $resource = Model::create($request->validated());

        // ✅ Inertia 2.0: Redirect with flash
        return redirect()->route('resource.show', $resource)
            ->with('success', 'Created successfully!');

    } catch (\Exception $e) {
        // ✅ Return to form with errors
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
```

### **Key Rules:**

1. **On Inertia Pages:** ALWAYS use `router.post()` for form submissions
2. **Raw Fetch Only:** For standalone API endpoints (not on Inertia pages)
3. **Never Check `wantsJson()`:** Inertia 2.0 handles this automatically
4. **Always Use Callbacks:** `onSuccess`, `onError`, `onFinish` for proper state management
5. **No Manual CSRF:** Inertia 2.0 meta tags handle it automatically

### **Common Pitfall - Status 302 Redirects:**

If you see `HTTP 302 Found` with HTML response:
1. You're using raw `fetch` instead of `router.post()`
2. Controller is returning `redirect()` (correct) but frontend expects JSON (wrong approach)
3. **Fix:** Switch to `router.post()` - Inertia handles redirects properly

---

### **RULE 9: TESTING STRATEGY**
```
- Unit tests MUST test tenant isolation logic
- Always test with multiple TenantId values
- Mock repositories MUST enforce tenant boundaries
- Integration tests MUST use actual tenant database connections
- Test tenant switching scenarios explicitly
```

---

# 🧭 Development Discipline — Business → DDD → Architecture → Tests → Implementation (STANDING RULE)

**Every change follows this order. Do not skip upstream stages. Do not jump to a test or to code before the stage that justifies it exists.**

```
Business need  →  DDD model (ubiquitous language, boundaries, ownership)  →
Architecture (decision recorded)  →  Tests (executable architecture / TDD RED)  →  Implementation (GREEN)
```

Concretely:
1. **Business first** — know the business/constitutional need a change serves. No change is "just technical" if it touches behavior.
2. **DDD before architecture** — model it: what is it, which bounded context/subdomain owns it, what does it *own* vs merely *preserve*? Resolve ownership of every invariant **before** protecting it. (E.g. anonymity is a *constitutional* invariant that Messaging *preserves* — not one it owns.)
3. **Architecture decision before tests** — record the decision (ADR / Decision Log `D-nn`) that the model produces. A test encodes a decision; the decision must exist first.
4. **Tests before implementation (TDD)** — RED first, then minimal GREEN. Architecture/fitness tests verify **properties**, not class names.
5. **For a discovered gap, the sequence is:** `Finding → Architecture Decision → RED → GREEN → Certification`. Never `Finding → Implementation → Certification`.

**Anti-pattern to avoid (DDD):** never let a *ticket* or an *observation* silently *become* architecture. Architecture **produces** tickets; tickets do not accrete into architecture. When several tickets have produced a reusable capability, **model it as a platform capability first**, then let tests/consumers follow.

**When in doubt, stop and model.** Producing a strategic model / ADR is real work, not a detour — it is the first stage, not a delay before "the real work."

**Tactical DDD work is additionally governed by the seven DDD Tactical Governance Principles** — canonical home `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` (ADOPTED, ARB 2026-07-26; PublicDigit binding: `docs/architecture/governance/DDD_PRINCIPLES.md`); this line is a pointer, never the rule text.

**Automation:** a non-blocking `PreToolUse` tripwire (`.claude/scripts/discipline-gate-reminder.sh`) reminds when a **new** test or production file is about to be created, to confirm the upstream artifacts exist. It is a checkpoint, not a wall — the rule above is the obligation.

---

# 📘 Developer Guide — Definition of Done (STANDING RULE)

**Every implementation step ships with a developer guide. This is part of the Definition of Done — do it without being asked.**

A "step" is a code slice (a `PB-xxx-Cn`-style commit, or any self-contained implementation change). For each step:

1. **Write or update** a developer guide under `developer_guide/<area>/` (e.g. `developer_guide/audit_system/`). One area folder per subsystem/ticket; **one file per step**, numbered for reading order (`00_index.md`, `01_step_...md`, …). Keep an `00_index.md` that maps the steps.
2. **Ground every snippet in the committed code** — no invented APIs. Match the house voice of the area's existing guides.
3. **Each guide covers:** purpose · where it fits (layer/namespace) · key files · design decisions (with ADR/D-refs) · how it works (with code) · how to use/extend · testing · pitfalls · a **Traceability** line.
4. **Honor the invariants** in the docs too: anonymity (ADR-T11 — no voter↔vote linkage in examples), and messaging/business-boundary rules.
5. **Commit** the guide with the step (or as a paired `docs(<area>): …` commit alongside the code/`-DOC` commit). Update the area `00_index.md`.

**Cadence:** per step, updated as slices land, finalized at ticket certification. Small/mechanical fixes may fold into the nearest step guide rather than a new file — use judgment, but never skip silently.

**Automation:** a `Stop` hook (`.claude/scripts/dev-guide-reminder.sh`) prints a non-blocking, **area-aware** reminder — it nudges per code-*area* changed today (e.g. `app/Contexts/Contestation` → `developer_guide/contestation/`) that has no matching guide update, so a guide written for an unrelated track can't silence a real gap. The reminder is a safety net — **the rule above is the obligation, and it is a judgment I should not skip**: when an ADR already captures the design, still add a short developer-facing guide (the ADR records the *decision*; the guide is the developer *how-to*).

---

# Planning, Memory and Session Management

## Principle

The project repository is the single source of truth.

Do not use Claude's global project memory (`~/.claude/...`) for project-specific plans, progress, or memory whenever it can be stored inside this repository.

All project planning and memory must live inside:

.claude/

This allows the entire development history to be version controlled, reviewed, and shared with every developer and AI assistant.

---

# Directory Structure

.claude/
    MEMORY.md
    CONTEXT.md
    sessions/
    plans/

---

# MEMORY.md

MEMORY.md stores stable project memory.

It should contain information that remains useful across many sessions.

Examples:

- important project conventions
- coding standards adopted by the project
- important implementation constraints
- recurring user preferences
- project-wide assumptions
- frequently referenced facts

Do NOT store:

- temporary tasks
- daily progress
- implementation plans
- debugging notes

Always update MEMORY.md when long-term project knowledge changes.

---

# CONTEXT.md

CONTEXT.md represents the current working state.

It should answer:

- What are we currently working on?
- Which ticket is active?
- What remains to be done?
- What blockers exist?
- What should be done next?

This file should always reflect the latest project state.

Whenever work starts or finishes, update CONTEXT.md.

---

# Plans

**Runtime work plans are ephemeral. Engineering plans are governed artifacts created only after EP-01 approval.**

Every significant task must have its own plan document.

**Storage and naming are governed by ES-004.2** (`engineering/governance/ES-004-Documentation.md` — the canonical rule; this section is a pointer):

Store plans in:

./docs/plans/

Naming:

YYYYMMDD-HHMM-<what_is_it_about>-plan.md

Example:

20260711-1830-evidence-context-strategic-discovery-plan.md

The timestamp preserves chronological history; the -plan.md suffix marks the type. A superseding plan cites the superseded plan's filename in its traceability section. Existing plans in .claude/plans/ are historical records — they stand; the convention applies from the next plan onward.

Avoid randomly generated filenames.

Each plan should include:

- Objective
- Background
- Scope
- Design decisions
- Task checklist
- Progress
- Risks
- Open questions
- Next actions

Plans are living documents.

Update them continuously rather than creating new ones.

---

# Session Logs

Every work session must create or update a session log.

Location:

.claude/sessions/

Filename:

YYYY-MM-DD.md

Example:

2026-07-06.md

Each session log should contain:

## Summary

Short description of today's work.

## Completed

Completed tasks.

## Decisions

Important decisions made.

## Problems

Issues encountered.

## Next Steps

Recommended starting point for the next session.

Append to the current day's session log instead of creating multiple logs for the same day.

---

# Workflow

At the beginning of every work session:

1. Read MEMORY.md.
2. Read CONTEXT.md.
3. Read the relevant plan document(s).
4. Read today's session log if it already exists.

Before starting implementation:

- Update the plan if requirements changed.
- Update CONTEXT.md if priorities changed.

During implementation:

- Keep the plan progress current.
- Record important decisions.

At the end of every work session:

- Update the plan.
- Update CONTEXT.md.
- Update MEMORY.md if long-term knowledge changed.
- Update today's session log.

Never finish a work session without updating these files.

---

# General Rules

- The repository is the authoritative memory.
- Avoid duplicate information.
- Update existing documents instead of creating unnecessary new ones.
- Use clear, descriptive filenames.
- Keep plans and session logs concise but complete.
- Never create randomly named plan files.
- Treat planning and documentation as part of the implementation, not as optional work.
- **Artifact lifecycle consistency (POINTER — canonical rule: ES-004.3, `engineering/governance/ES-004-Documentation.md`):** at every slice closure, run the ROLE-BASED synchronization checklist (Runtime: plan status + CONTEXT · Historical: session log, append-only · Reference: dev guide · Decision: ADR status annotations + acceptance record). Synchronization touches only the MUTABLE portion of an artifact — decision text and history are never rewritten.
