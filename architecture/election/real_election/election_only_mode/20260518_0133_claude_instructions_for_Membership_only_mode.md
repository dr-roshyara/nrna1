Below is a **production-grade Claude Code instruction prompt** you can use to harden the **Election-Only mode** inside your Laravel monolith (DDD-style, policy-driven system).

It is designed to enforce:

* single source of truth for eligibility
* strict CQRS discipline (write/read separation even inside monolith)
* removal of scattered logic
* tenancy safety
* deterministic election behavior

---

# 🧠 CLAUDE CODE INSTRUCTION PROMPT

## Election-Only Mode Hardening (Laravel Monolith DDD)

---

## ROLE

You are working as:

> Senior Domain Architect + Security Engineer + DDD/CQRS enforcer

You are improving an existing **Laravel monolith with DDD-style structure**.

The system contains:

* Membership context
* Election context
* OrganisationUser identity layer
* partial CQRS projections
* inconsistent eligibility logic

---

# 🎯 OBJECTIVE

Harden and simplify **Election-Only mode** so that:

### Goals:

1. There is exactly ONE eligibility decision engine
2. Election-Only mode does NOT depend on Membership tables
3. No duplicated eligibility logic across services/controllers/models
4. All voter decisions are deterministic and auditable
5. Tenancy cannot be bypassed via `withoutGlobalScopes()`
6. Election logic becomes predictable and testable

---

# 🚨 CURRENT PROBLEMS (YOU MUST FIX)

You will assume the system currently has:

### ❌ Scattered eligibility logic

* VoterEligibilityService
* ElectionMembership::assignVoter()
* bulkAssignVoters()
* controller-level checks

### ❌ Multiple identity sources

* members table
* organisation_users table
* user_organisation_roles table

### ❌ Unsafe patterns

* withoutGlobalScopes() used in write paths
* missing tenant validation
* inconsistent soft delete handling

---

# 🧱 ARCHITECTURAL RULES (NON-NEGOTIABLE)

## RULE 1: Single Eligibility Engine

You MUST introduce:

> VoterQualificationPolicy (or Gateway)

This is the ONLY place that decides:

* who can vote
* who can be assigned
* who is eligible for bulk operations

❌ No other file may implement eligibility rules.

---

## RULE 2: Election-Only Mode Independence

Election-only mode:

* MUST NOT require Member table
* MUST NOT query membership logic
* MUST rely ONLY on OrganisationUser + Election rules

---

## RULE 3: No scattered DB logic

❌ Forbidden:

* DB::table(...) inside controllers/services for eligibility
* direct membership table checks outside policy
* conditional logic duplicated across layers

---

## RULE 4: Tenancy is explicit

Every write operation MUST validate:

* election.organisation_id == expected organisation_id
* organisation context must be passed explicitly

No implicit trust in TenantContext alone.

---

## RULE 5: CQRS discipline inside monolith

Even in monolith:

* WRITE side = commands + aggregates
* READ side = projections only
* NO mixing of both

---

# 🧬 TARGET ARCHITECTURE

## WRITE MODEL

* Election Aggregate
* ElectionMembership
* VoterQualificationPolicy (decision engine)

## READ MODEL

* election_voter_projection
* election_result_projection

## IDENTITY LAYER (shared)

* OrganisationUser ONLY

---

# 🔧 REQUIRED REFACTORING TASKS

---

## TASK 1 — Create VoterQualificationPolicy (CRITICAL)

Implement a single class:

### Responsibilities:

* isEligible(user, election)
* getEligibleUserIds(election)
* validateAssignment(user, election)

### Rules:

```text
IF election.mode == ELECTION_ONLY:
    eligibility = OrganisationUser only

IF election.mode == MEMBERSHIP:
    eligibility = Member + membership rules
```

---

## TASK 2 — Replace ALL scattered eligibility logic

You MUST remove logic from:

* VoterEligibilityService
* ElectionMembership::assignVoter
* bulkAssignVoters
* controllers

Replace with:

```php
$policy->isEligible(...)
```

---

## TASK 3 — Remove boolean mode switching

Replace:

```php
uses_full_membership
```

WITH:

```php
ElectionMode enum
```

Values:

* MEMBERSHIP
* ELECTION_ONLY

---

## TASK 4 — Fix ElectionMembership assignment flow

All assignment MUST follow:

```text
Controller → Policy → Aggregate → Persist → Event
```

NOT direct DB checks.

---

## TASK 5 — Enforce tenancy validation explicitly

In ALL write methods:

* pass expected organisation_id
* validate against election.organisation_id
* throw exception on mismatch

---

## TASK 6 — Eliminate unsafe withoutGlobalScopes()

You MUST:

* remove blind usage in write paths
* replace with explicit repository queries
* add comments explaining necessity if still required

---

## TASK 7 — Introduce Election Mode Separation Behavior

### ELECTION_ONLY mode:

* NO member lookup
* ONLY organisation_users used
* lightweight eligibility rules

### MEMBERSHIP mode:

* MUST validate Member existence
* membership rules apply

---

## TASK 8 — Normalize audit trail

Ensure:

* voter assignment logged
* bulk assignment logged
* removals logged

All logs MUST include:

* user_id
* election_id
* organisation_id
* actor_id

---

# 🧪 TESTING REQUIREMENTS

You MUST ensure:

### Unit tests:

* policy correctness
* mode separation behavior

### Feature tests:

* cannot assign in wrong tenant
* election-only works without members
* membership mode requires member existence

---

# 🧭 FINAL ARCHITECTURAL INTENT

After refactor:

## Election system becomes:

> deterministic, policy-driven, mode-aware participation engine

NOT:

> scattered conditional logic across services

---

# 🚀 DEFINITION OF DONE

System is complete when:

* only ONE eligibility policy exists
* election-only mode works without membership tables
* no duplicate eligibility logic exists
* tenancy is enforced explicitly
* all assignments go through policy layer
* read/write separation is respected
* tests confirm mode isolation

---
 work in plan mode , tdd first approach and use brainstorming skill during plan 

 This is an excellent, structurally comprehensive Claude Code instruction prompt. It hits the exact architectural nerves exposed in your DDD verification report, particularly the implicit tenancy risks, source-of-truth ambiguity, and the leaky `uses_full_membership` boolean logic.

However, since we are executing this with a **Plan-Mode, TDD-First Approach**, we need to brainstorm like senior architects *before* writing code. When you run a heavy refactor on an election system, things that look clean on paper can break during database transactions, state machine transitions, or performance scaling.

Below is a detailed architectural brainstorming analysis of **what is missing or under-specified** in your prompt, followed by a concrete **TDD Step-by-Step Execution Plan** tailored for Claude Code.

---

# 🧠 PART 1: THE BRAINSTORMING AUDIT (What is Missing?)

Before giving this to Claude Code, we must close 5 subtle domain and engineering loopholes that will cause TDD tests to fail or database states to corrupt if left unaddressed.

### 1. The Soft-Delete Ingestion Collision (Crucial for TDD)

* **The Blind Spot:** Task 4 and Task 6 talk about fixing the assignment flow and removing `withoutGlobalScopes()`. What happens when a user is imported, later deleted (soft-deleted from `election_memberships`), and then re-imported via a CSV?
* **The Fix needed in the plan:** The `VoterQualificationPolicy` or Repository must explicitly handle `withTrashed()` data checking on the *write path* to determine if it should perform an `insert` or a `restore()`. If it blindly tries to write a new row, a unique database constraint (`election_id`, `user_id`) will crash the transaction.

### 2. Idempotency & Bulk Data Limits (Performance Scoping)

* **The Blind Spot:** In Election-Only mode, it is common to import 5,000 to 10,000 voters at once via CSV. If Task 4 forces a strict aggregate route (`Controller → Policy → Aggregate → Persist → Event`) for *every single item*, running 10,000 individual model events inside a single HTTP request loop will cause a memory exhaustion or maximum execution time timeout.
* **The Fix needed in the plan:** The prompt needs a clear distinction for **Bulk Write CQRS Discipline**. The Policy must support a bulk evaluation method (`validateBulkAssignment(array $userIds, Election $election)`), allowing the repository to execute an atomic, chunked bulk insert (`insertOrIgnore`) or dispatch a queued background Job to preserve system stability.

### 3. The Shared Identity Bootstrap Ordering

* **The Blind Spot:** In an Election-Only CSV import, the emails provided might not exist in the platform yet.
* **The Fix needed in the plan:** The plan must explicitly state the bootstrap ordering: The system must check if the base `User` and tenant anchor `OrganisationUser` exist *first*. If they don't, it must safely register them *before* passing the ID to the `VoterQualificationPolicy`. The Policy evaluates eligibility; it shouldn't be responsible for creating users.

### 4. Cache Invalidation Footprint

* **The Blind Spot:** The verification report proved that voter counts are heavily cached (`election.{$id}.voter_count`).
* **The Fix needed in the plan:** The Definition of Done must explicitly require that any aggregate write path (`assignVoter`, `removeVoter`, `bulkImport`) triggers a scoped cache clear event using the explicit tenant key structure to ensure the read projections update immediately.

---

# 🗺️ PART 2: THE TDD PLAN-MODE REFACTORING PATH

Here is the modified, bulletproof version of your plan formatted specifically for a **TDD-First execution model** inside Claude Code.

---

### 🧪 Stage 0: The Baseline Test Harness (Write these first)

Before touching any production domain models or controllers, Claude Code must write the failing test assertions that define our architectural boundaries:

1. `test_election_only_mode_allows_voter_assignment_without_membership_records()`
2. `test_membership_mode_rejects_voter_assignment_if_member_record_is_missing_or_unpaid()`
3. `test_voter_assignment_throws_hard_exception_on_cross_tenant_organisation_id_mismatch()`
4. `test_soft_deleted_voters_are_restored_instead_of_creating_duplicate_database_rows()`

---

### 🛠️ Stage 1: The Core Refactoring Sequence

```text
STEP 1: Define ElectionMode Enum
 └── Create App\Domain\Election\Enums\ElectionMode.php [MEMBERSHIP, ELECTION_ONLY].
 └── Replace `uses_full_membership` column references in tests & codebase.

STEP 2: Build VoterQualificationPolicy (The Sole Decision Engine)
 └── Implement structural methods: isEligible(), validateAssignment(), validateBulkAssignment().
 └── Isolate completely: No database writes or direct state mutations inside this policy.

STEP 3: Unify the Aggregate Assignment Pipeline
 └── Refactor ElectionMembership::assignVoter() and bulk handling.
 └── Enforce explicit tenancy signatures: pass `$expectedOrgId` directly into the arguments.
 └── Integrate the soft-delete check logic using `withoutTrashed()` and `restore()`.

STEP 4: Clean the Infrastructure & Controller Layer
 └── Erase direct references to `user_organisation_roles` from the election management routes.
 └── Wire the controllers directly to the `VoterQualificationPolicy`.
 └── Implement the structured logging footprint for all voter change vectors.

STEP 5: Green-Light the Read Model / Projections
 └── Hook up the Tenant-prefixed cache clear sequence upon successful write completion.

```

---

# 🚀 FINAL CLAUDE CODE INSTRUCTION ADDENDUM

Copy and paste this section directly onto the end of your original Claude prompt to force it to run under strict TDD and Plan-Mode constraints:

```text
---

# 🧭 TDD & PLAN MODE EXECUTION STRATEGY (CRITICAL)

You must run this refactor using a strict TDD-first approach:

1. BRAINSTORM AND ARCHITECT FIRST: Before writing or altering files, output a written plan detailing the exact file modifications, class dependencies, and method signatures you intend to change. Stop and wait for confirmation if major architectural discrepancies appear.
2. WRITE FAILING TESTS FIRST: For every task (from Enum migration to Policy building), draft or modify the Feature/Unit tests to assert the desired failure state (e.g., asserting that cross-tenant validation throws an exception, or that election-only mode ignores member tables).
3. IMPLEMENT INCREMENTALLY: Write the minimum production code necessary to pass the specific test. Avoid giant monolithic commits; verify each step runs green before moving down the refactoring sequence.
4. HANDLE TRANSITIONAL BOUNDARIES SAFELY: Ensure that soft-delete lookups, chunked bulk imports, and tenant-scoped cache keys are completely accounted for in your code implementations to prevent production regressions.

```