Yes — the review and the refactoring instructions are grounded in **DDD + Clean Architecture + CQRS-style separation of concerns**, but with a pragmatic, “incremental enforcement” style rather than a pure academic model.

Here’s the precise mapping so you can calibrate expectations.

---

# 1. Architectural Model Being Used

## Core Foundation

You are effectively operating in a hybrid of:

* **Domain-Driven Design (DDD)**
* **Clean Architecture (Uncle Bob style layering)**
* **CQRS (lightweight, not full event-sourced CQRS yet)**
* **Hexagonal Architecture (Ports & Adapters)**

---

# 2. How Each Layer Is Interpreted

## 🧠 Domain Layer (DDD Core)

This layer is treated as:

* Pure business truth
* No frameworks
* No UI concerns
* No persistence concerns
* No projections

### Examples in your system:

* `MembershipLineage`
* `CommitteeAssociation`
* `MembershipTransitionPolicy`
* Domain events (MembershipSuspended, etc.)

### Rule:

> Domain expresses *truth*, not *views*

---

## 🧩 Application Layer (Use Cases)

This is interpreted as:

* Orchestration of domain + ports
* No Eloquent
* No UI logic
* No formatting logic
* No persistence details

### Examples:

* `GetCommitteeDashboard`
* command/query handlers
* application services

### Rule:

> Application coordinates, but does not decide representation

---

## 🔌 Ports (Interfaces)

These define boundaries:

* `CommitteeMembershipReadModelAdapter` (→ should be renamed to ProjectionPort conceptually)
* repositories
* identity providers

### Rule:

> Ports define *what is needed*, not how it is done

---

## 🏗 Infrastructure Layer

This is:

* Eloquent
* database queries
* external services
* implementation of ports

### Examples:

* `EloquentCommitteeMembershipReadModelAdapter`

### Rule:

> Infrastructure is replaceable, never referenced directly outside adapters

---

## 🎨 Presentation Layer (Vue)

This is:

* Pure rendering layer
* No business logic
* No formatting decisions
* Only translation keys + display mapping

### Rule:

> UI is a renderer of DTOs, not a decision-maker

---

# 3. CQRS Interpretation in Your System

You are using a **light CQRS split**, not full event sourcing CQRS yet:

## Write side:

* `MembershipLineage`
* `CommitteeAssociation`
* domain events

## Read side:

* `CommitteeDashboardDTO`
* `ReadModelAdapter`
* projections

### Key rule:

> Read model MUST NOT reuse domain aggregates directly

---

# 4. What I Did That Matches DDD Strictly

These are aligned with standard DDD/Clean Architecture:

### ✔ Proper read model isolation

You introduced:

* projection adapter
* DTO separation
* Vue translation keys

### ✔ Anti-Corruption Layer (ACL)

Your adapter is correctly acting as:

> boundary between domain truth and UI consumption

### ✔ Ubiquitous language correction

Replacing:

* `assignments` → `members`
* `role` → `role_key`

This is very DDD-correct.

---

# 5. Where I Was More Strict Than Necessary (Important)

Some suggestions were **more “ideal Clean Architecture” than necessary for your current stage**:

## 1. Moving GetCommitteeDashboard to Committee Context

✔ Architecturally correct in large systems
⚠ But not required yet

You can keep it in Membership context safely for now.

---

## 2. Splitting DTO into multiple read models

✔ Scales well in large CQRS systems
⚠ Over-optimization for current size

Your current single DTO is fine.

---

## 3. Identity ViewPort abstraction

✔ Enterprise-grade decoupling
⚠ Optional until user system changes independently

---

## 4. ProjectionRepository renaming

✔ Improves semantic clarity
⚠ Not functionally required

---

# 6. What You Actually Have (Reality Check)

Your system is currently:

### ✔ Clean Architecture baseline

* layered
* dependency inversion in place

### ✔ DDD-aligned core domain

* membership rules are isolated
* transitions are explicit

### ✔ CQRS-lite read model

* dashboard projection exists
* no event sourcing yet (F3.3 is planned)

### ✔ Anti-Corruption Layer already working

* read model adapter is correct

---

# 7. The Honest Summary

Yes — the guidance is based on:

> DDD + Clean Architecture + CQRS principles

But in **three levels of strictness**:

| Level                | Style used                          |
| -------------------- | ----------------------------------- |
| Domain rules         | Strict DDD                          |
| Application layering | Clean Architecture                  |
| Read models          | CQRS-inspired                       |
| Naming/refactors     | Enterprise-grade optional hardening |

---

# 8. Practical Recommendation for You

Given your current maturity stage:

## You should prioritize:

### Phase 1 (NOW)

* Fix DI + DTO + Vue alignment
* Keep architecture stable
* No structural movement of contexts

### Phase 2 (NEXT)

* Strengthen projection boundary naming
* Introduce identity port abstraction (optional)

### Phase 3 (LATER)

* Move toward full CQRS module separation
* Prepare for F3.3 event sourcing replay model

---

# If you want next step clarity

I can map your system into a **clean bounded context diagram** showing:

* Membership Context
* Committee Context
* Identity Context
* Read Model Layer
* Event Model Layer (F3.3 future)

That will make it very clear where everything *should* live long-term.
Below is a **clean bounded context map of your current system + near-future evolution (F3.3-ready)** using DDD + hexagonal + CQRS separation.

I’ll structure it in three layers:

1. **Current State (as-is reality)**
2. **Clean Bounded Context Model (target architecture)**
3. **Context interactions (how data flows)**

---

# 1. CURRENT STATE (WHAT YOU ACTUALLY HAVE)

Your system is already partially modular but still “context-blended”.

```text
┌──────────────────────────────────────────────────────────────┐
│                     CURRENT SYSTEM                           │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  Membership Context                                          │
│  ├── MembershipLineage (Domain Aggregate)                   │
│  ├── CommitteeAssociation (Entity)                          │
│  ├── MembershipTransitionPolicy                             │
│  ├── GetCommitteeDashboard (Application Service ❗)         │
│  └── ReadModel Adapter (Membership Projection)              │
│                                                              │
│  Committee UI Context (partially inside Membership ❗)       │
│  ├── Dashboard.vue                                          │
│  ├── CommitteeDashboardDTO                                  │
│                                                              │
│  Infrastructure                                             │
│  ├── Eloquent Models (CommitteeModel, User)                │
│  └── ReadModel Adapter Implementation                       │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

### Key issue in current state:

* Membership context is doing **UI composition**
* Committee UI depends on **Membership application service**
* User identity is resolved via **Eloquent inside projection**

---

# 2. CLEAN BOUNDED CONTEXT MODEL (TARGET ARCHITECTURE)

This is the **DDD-correct separation** of your system.

---

## 🧠 1. Membership Context (Core Domain)

> Owns truth of membership lifecycle

```text
┌────────────────────────────────────────────┐
│            MEMBERSHIP CONTEXT              │
├────────────────────────────────────────────┤
│ DOMAIN                                     │
│ ├── MembershipLineage                     │
│ ├── CommitteeAssociation                  │
│ ├── MembershipTransitionPolicy           │
│ ├── Domain Events (Established, etc.)     │
│                                            │
│ APPLICATION (Write Side)                  │
│ ├── Commands (Reapply, Suspend, etc.)     │
│ ├── Handlers                              │
│                                            │
│ EVENT MODEL (FUTURE F3.3)                │
│ ├── EventStream                           │
│ ├── Replay / Reconstitution              │
└────────────────────────────────────────────┘
```

### Responsibility:

* Enforces lifecycle truth
* Owns invariants
* Emits events
* No UI knowledge

---

## 🏛 2. Committee Context (Application + Read Layer)

> Owns dashboards, composition, and UI-facing queries

```text
┌────────────────────────────────────────────┐
│             COMMITTEE CONTEXT             │
├────────────────────────────────────────────┤
│ APPLICATION (READ SIDE)                   │
│ ├── GetCommitteeDashboard                 │
│ ├── CommitteeDashboardResponse           │
│ ├── CommitteeSummaryView                │
│ ├── CommitteeMembersView                │
│                                            │
│ PORTS                                    │
│ ├── CommitteeMemberProjectionPort        │
│ ├── MemberIdentityViewPort              │
│                                            │
│ UI CONTRACTS                             │
│ ├── Translation keys only               │
│ ├── DTOs for Vue                        │
└────────────────────────────────────────────┘
```

### Responsibility:

* Builds dashboard views
* Aggregates read models
* Defines API contracts
* Does NOT own membership rules

---

## 🧾 3. Identity Context (Missing but implied)

> Extracted boundary (currently implicit via User::find)

```text
┌────────────────────────────────────────────┐
│            IDENTITY CONTEXT               │
├────────────────────────────────────────────┤
│ DOMAIN / READ MODEL                      │
│ ├── User                                 │
│ ├── Profile                              │
│                                            │
│ PORT                                      │
│ ├── MemberIdentityViewPort              │
│                                            │
│ RESPONSIBILITY                          │
│ ├── Display name resolution             │
│ ├── External identity integration       │
└────────────────────────────────────────────┘
```

### Why this matters:

Prevents Membership/Committee from depending on:

* User Eloquent
* authentication model changes
* external identity providers

---

## 📊 4. Read Model / Projection Context (Cross-Cutting)

> This is NOT a domain context — it is a query layer

```text
┌────────────────────────────────────────────┐
│            PROJECTION LAYER               │
├────────────────────────────────────────────┤
│ IMPLEMENTATIONS                          │
│ ├── EloquentCommitteeMemberProjection    │
│ ├── (future) Cached projections          │
│ ├── (future) Event-sourced projections  │
│                                            │
│ RESPONSIBILITY                          │
│ ├── Query optimization                  │
│ ├── Batch loading                      │
│ ├── Denormalized read models          │
└────────────────────────────────────────────┘
```

### Important:

This is NOT a bounded context in strict DDD sense — it's a **supporting query infrastructure layer**.

---

# 3. CONTEXT MAP (RELATIONSHIPS)

This is the most important part.

```text
                           ┌────────────────────┐
                           │   IDENTITY CONTEXT │
                           │  (User / Profile)  │
                           └─────────▲──────────┘
                                     │
                                     │ MemberIdentityViewPort
                                     │
┌────────────────────┐     ┌─────────┴──────────┐     ┌──────────────────────┐
│  MEMBERSHIP CONTEXT │────▶│ PROJECTION LAYER   │────▶│ COMMITTEE CONTEXT    │
│                    │     │ (Read Models)      │     │ (Dashboard/UI)      │
│ - Domain rules     │     └─────────▲──────────┘     │ - DTO assembly      │
│ - Events           │               │                │ - Vue contracts     │
│ - Aggregates       │               │                └──────────────────────┘
└────────────────────┘               │
                                     │
                            CommitteeMemberProjectionPort
```

---

# 4. KEY DEPENDENCY RULES (CRITICAL)

## ✔ Allowed Dependencies

### Membership → Projection

✔ YES (via domain events or repository abstraction)

### Committee → Projection

✔ YES (read side only)

### Committee → Identity

✔ YES (via port only)

---

## ❌ Forbidden Dependencies

### Committee → Membership Aggregate

❌ NEVER

### Application → Eloquent

❌ NEVER

### Vue → Domain Logic

❌ NEVER

### Membership → UI DTOs

❌ NEVER

---

# 5. EVENT FLOW (FUTURE F3.3 READY)

Once event sourcing is added:

```text
MembershipLineage
      ↓
Domain Events
      ↓
Event Stream
      ↓
Projection Rebuilder
      ↓
Committee Read Model
      ↓
Dashboard API
      ↓
Vue ($t rendering)
```

---

# 6. SYSTEM HEALTH SUMMARY

## What is already strong:

* ✔ Domain is clean (MembershipLineage solid)
* ✔ Event model exists (F3.3 foundation ready)
* ✔ Read model adapter introduced correctly
* ✔ Vue is translation-driven (excellent)
* ✔ DTO boundary is improving

---

## What is still structurally risky:

* ⚠ Membership context currently owns dashboard use case
* ⚠ User identity still leaks into projection
* ⚠ Committee vs Membership responsibility blur
* ⚠ Read model naming not fully CQRS-pure yet

---

# 7. FINAL ARCHITECTURAL STATE (ONE LINE)

Your system is currently:

> “DDD-aligned modular monolith with emerging CQRS read layer and partially separated bounded contexts, but with Committee ↔ Membership responsibility overlap in the application layer.”

---

# If you want next step

I can draw the **next evolution target (F3.3 event-sourced architecture)** showing:

* event store
* projection rebuilders
* replay engine
* read/write divergence
* eventual consistency boundaries

That will make your system evolution path very explicit.
Below is the **next evolution target architecture (F3.3 Event-Sourced + CQRS Projection System)** based on your current system and your planned replay model.

This is the **future state after event sourcing is fully introduced**, not your current system.

---

# 1. HIGH-LEVEL EVOLUTION TARGET

```text id="ev0top"
                    ┌──────────────────────────────┐
                    │        WRITE SIDE            │
                    │   (Domain + Commands)        │
                    └──────────────┬───────────────┘
                                   │
                                   ▼
                    ┌──────────────────────────────┐
                    │     DOMAIN AGGREGATE         │
                    │   MembershipLineage          │
                    │   CommitteeAssociation       │
                    └──────────────┬───────────────┘
                                   │ emits
                                   ▼
                    ┌──────────────────────────────┐
                    │      DOMAIN EVENTS           │
                    │ MembershipEstablished        │
                    │ Suspended / Restored         │
                    │ Terminated / Reapplied       │
                    └──────────────┬───────────────┘
                                   │
                                   ▼
                    ┌──────────────────────────────┐
                    │       EVENT STORE            │
                    │ (append-only log)            │
                    └──────────────┬───────────────┘
                                   │
        ┌──────────────────────────┼──────────────────────────┐
        ▼                          ▼                          ▼
┌────────────────┐     ┌────────────────────┐     ┌────────────────────┐
│ REPLAY ENGINE  │     │ PROJECTION BUILDER │     │ AUDIT / HISTORY    │
│ (F3.3 core)    │     │ (async or sync)    │     │ VIEW               │
└──────┬─────────┘     └─────────┬──────────┘     └────────────────────┘
       │                         │
       ▼                         ▼
┌──────────────────────────────────────────────────────────────┐
│                READ MODEL / PROJECTIONS                      │
│  CommitteeMemberProjection                                  │
│  CommitteeDashboardProjection                                │
│  MembershipStatsProjection                                   │
└──────────────────────────────┬───────────────────────────────┘
                               │
                               ▼
                    ┌──────────────────────────────┐
                    │      QUERY SIDE (CQRS)       │
                    │ GetCommitteeDashboard        │
                    │ ReadModelPorts               │
                    └──────────────┬───────────────┘
                                   │
                                   ▼
                    ┌──────────────────────────────┐
                    │           VUE UI             │
                    │   $t() translation keys      │
                    │   pure DTO rendering         │
                    └──────────────────────────────┘
```

---

# 2. WRITE SIDE (F3.3 CORE DOMAIN)

## Responsibilities

* Enforce business invariants
* Emit immutable events
* Never read projections
* Never query read models

```text id="wrt01"
MembershipLineage
 ├── establish()
 ├── suspend()
 ├── restore()
 ├── terminate()
 └── reapply()
```

### Output:

```text id="wrt02"
Domain Events (immutable facts)
```

---

# 3. EVENT STORE (NEW SYSTEM OF TRUTH)

## Characteristics

* Append-only
* No updates
* No deletes
* Ordered per aggregate stream

```text id="evt01"
Stream Key:
MembershipLineage:{lineageId}
```

### Stored events:

```text id="evt02"
1. MembershipEstablished
2. MembershipSuspended
3. MembershipRestored
4. MembershipTerminated
5. MembershipReapplied
```

---

# 4. REPLAY ENGINE (F3.3 CORE INNOVATION)

This is the **most important new component in your system**.

## Responsibilities

* Rebuild domain state from events
* Pure function (NO DB, NO POLICY)
* Deterministic

```text id="rep01"
Event Stream
   ↓
MembershipLineage::replay()
   ↓
Fold reducer
   ↓
Rehydrated Aggregate State
```

### Key rule:

> Replay is NOT business logic. It is historical reconstruction.

---

# 5. PROJECTION BUILDER (CQRS READ MODEL LAYER)

This is where your current `ReadModelAdapter` evolves into a proper projection system.

## Responsibilities

* Convert events → query models
* Denormalize data
* Optimize read performance

```text id="prj01"
Event Stream
   ↓
Projection Handlers
   ↓
Materialized Views
```

### Examples:

* CommitteeMemberProjection
* CommitteeDashboardProjection
* MembershipStatsProjection

---

# 6. READ MODEL (QUERY SIDE)

## This replaces current "Adapter logic"

```text id="read01"
GetCommitteeDashboard
   ↓
CommitteeMemberProjectionPort
   ↓
Precomputed View Table / Cache
   ↓
DTO
```

### Key difference vs current system:

| Current         | Future                  |
| --------------- | ----------------------- |
| runtime joins   | precomputed projections |
| User::find()    | identity projection     |
| live DB queries | materialized views      |

---

# 7. IDENTITY CONTEXT (NOW FULLY SEPARATED)

```text id="id01"
Event Stream / Projection
        ↓
MemberIdentityProjection
        ↓
Display Name Resolver
```

No direct DB access from membership or committee context.

---

# 8. CQRS FLOW (FINAL TARGET)

## WRITE PATH

```text id="cqrs1"
Command
  ↓
Aggregate (MembershipLineage)
  ↓
Domain Events
  ↓
Event Store
```

---

## READ PATH

```text id="cqrs2"
Query (GetCommitteeDashboard)
  ↓
Projection Store
  ↓
DTO
  ↓
Vue ($t rendering)
```

---

# 9. SYSTEM OF RECORD SHIFT

This is the biggest conceptual change in F3.3:

## BEFORE (current system)

```text id="sor1"
Database Tables = Source of Truth
```

---

## AFTER (F3.3 target)

```text id="sor2"
Event Store = Source of Truth
```

Everything else becomes:

* derived
* rebuildable
* disposable

---

# 10. FAILURE & RECOVERY MODEL

## If projection breaks:

```text id="fail1"
Event Store
   ↓
Replay Engine
   ↓
Rebuild Projection
```

No data loss possible.

---

# 11. ARCHITECTURAL BOUNDARY MAP (FINAL VIEW)

```text id="bound1"
┌────────────────────────────────────────────────────────────┐
│                    DOMAIN CORE (Membership)               │
│  - Aggregates                                              │
│  - Policies                                                │
│  - Events                                                  │
└───────────────┬────────────────────────────────────────────┘
                │ emits
                ▼
┌────────────────────────────────────────────────────────────┐
│                    EVENT STORE (Truth Log)                │
└───────────────┬────────────────────────────────────────────┘
                │ consumed by
        ┌───────┼────────────────────────────┐
        ▼                               ▼
┌──────────────────────┐      ┌────────────────────────────┐
│ REPLAY ENGINE        │      │ PROJECTION BUILDER        │
└──────────┬───────────┘      └──────────┬─────────────────┘
           ▼                             ▼
   Domain Reconstruction        Materialized Read Models
           │                             │
           └──────────────┬──────────────┘
                          ▼
                ┌──────────────────────┐
                │ COMMITTEE CONTEXT    │
                │ (Dashboard / API)    │
                └──────────┬───────────┘
                           ▼
                     ┌──────────┐
                     │   VUE    │
                     └──────────┘
```

---

# 12. KEY DESIGN SHIFT SUMMARY

## You are moving from:

### ❌ State-based system

* DB rows = truth
* runtime joins
* mutable history interpretation

---

## To:

### ✅ Event-sourced system

* events = truth
* projections = views
* replay = reconstruction
* read models = disposable

---

# If you want next step

I can now generate:

### 🔥 “F3.3 Implementation Execution Map”

* exact classes to create
* test-first order (RED/GREEN)
* folder structure
* migration strategy from current DB model → event store
* safe rollout strategy (no downtime)

That would turn this architecture into a step-by-step build plan.
Below is a **clean F3.3 Event-Sourced + CQRS folder structure** aligned with your current modular monolith, but extended for:

* Event sourcing (replay, stream, event store)
* CQRS read model projections
* strict DDD bounded contexts
* no leakage between layers

---

# 🧱 ROOT STRUCTURE (HIGH LEVEL)

```text
app/
└── Contexts/
    ├── Membership/
    ├── Committee/
    ├── Identity/
    └── Shared/
```

---

# 1. MEMBERSHIP CONTEXT (WRITE SIDE + DOMAIN CORE)

> This is your **system of truth (event-sourced aggregate root lives here)**

```text
app/Contexts/Membership/
├── Domain/
│   ├── Membership/
│   │   ├── Aggregates/
│   │   │   └── MembershipLineage.php
│   │   │
│   │   ├── Entities/
│   │   │   └── CommitteeAssociation.php
│   │   │
│   │   ├── ValueObjects/
│   │   │   ├── MembershipStatus.php
│   │   │   ├── AssociationId.php
│   │   │   └── ApplicationReason.php
│   │   │
│   │   ├── Policies/
│   │   │   └── MembershipTransitionPolicy.php
│   │   │
│   │   └── Events/
│   │       ├── MembershipLifecycleEvent.php
│   │       ├── MembershipEstablished.php
│   │       ├── MembershipSuspended.php
│   │       ├── MembershipRestored.php
│   │       ├── MembershipTerminated.php
│   │       └── MembershipReapplied.php
│   │
│   └── Exceptions/
│       ├── InvalidMembershipTransitionException.php
│       └── InvalidMembershipEventStreamException.php
│
├── Application/
│   ├── Commands/
│   │   ├── EstablishMembership.php
│   │   ├── SuspendMembership.php
│   │   ├── RestoreMembership.php
│   │   ├── TerminateMembership.php
│   │   └── ReapplyMembership.php
│   │
│   ├── Handlers/
│   │   ├── EstablishMembershipHandler.php
│   │   └── ...
│   │
│   ├── Replay/
│   │   └── MembershipReplayService.php
│   │
│   └── Stream/
│       └── MembershipEventStream.php
│
├── Infrastructure/
│   ├── Persistence/
│   │   ├── EventStore/
│   │   │   ├── EloquentEventStore.php
│   │   │   └── EventRecordModel.php
│   │   │
│   │   └── Repositories/
│   │       └── MembershipLineageRepository.php
│   │
│   └── Providers/
│       └── MembershipServiceProvider.php
```

---

# 2. COMMITTEE CONTEXT (READ SIDE / CQRS QUERY LAYER)

> This is your **UI + dashboard + projection orchestration layer**

```text
app/Contexts/Committee/
├── Application/
│   ├── Dashboard/
│   │   ├── GetCommitteeDashboard.php
│   │   ├── DTO/
│   │   │   └── CommitteeDashboardDTO.php
│   │   │
│   │   └── Views/
│   │       ├── CommitteeMemberView.php
│   │       └── CommitteeSummaryView.php
│   │
│   ├── ReadModel/
│   │   ├── Ports/
│   │   │   ├── CommitteeMemberProjectionPort.php
│   │   │   └── MemberIdentityViewPort.php
│   │   │
│   │   └── Queries/
│   │       └── GetCommitteeMembersQuery.php
│
├── Infrastructure/
│   ├── Projections/
│   │   ├── Eloquent/
│   │   │   ├── CommitteeMemberProjection.php
│   │   │   ├── MembershipStatsProjection.php
│   │   │   └── CommitteeDashboardProjection.php
│   │   │
│   │   └── Projectors/
│   │       ├── MembershipProjector.php
│   │       └── CommitteeProjector.php
│   │
│   ├── Persistence/
│   │   └── ReadRepositories/
│   │       └── EloquentCommitteeReadRepository.php
│   │
│   └── Providers/
│       └── CommitteeServiceProvider.php
│
├── Http/
│   └── Controllers/
│       └── CommitteeDashboardController.php
```

---

# 3. IDENTITY CONTEXT (EXTRACTED DOMAIN BOUNDARY)

> Removes all `User::find()` leakage from Membership/Committee

```text
app/Contexts/Identity/
├── Domain/
│   ├── User.php
│   ├── ValueObjects/
│   │   ├── UserId.php
│   │   └── DisplayName.php
│
├── Application/
│   ├── Ports/
│   │   └── MemberIdentityViewPort.php
│
├── Infrastructure/
│   └── Persistence/
│       └── EloquentUserRepository.php
```

---

# 4. EVENT STORE (INFRASTRUCTURE CORE)

> This is the **F3.3 system backbone**

```text
app/Contexts/Shared/EventSourcing/
├── EventStore/
│   ├── EventStoreInterface.php
│   ├── EloquentEventStore.php
│   └── StoredEvent.php
│
├── Streams/
│   ├── EventStream.php
│   └── StreamId.php
│
├── Replay/
│   ├── EventReplayer.php
│   └── AggregateRehydrator.php
│
└── Exceptions/
    └── EventStreamException.php
```

---

# 5. SHARED KERNEL (STRICTLY LIMITED)

```text
app/Contexts/Shared/
├── Domain/
│   ├── AggregateRoot.php
│   ├── DomainEvent.php
│   └── RecordsEvents.php
│
├── ValueObjects/
│   ├── TenantId.php
│   ├── UUID.php
│   └── DateTimeImmutable.php
│
└── Exceptions/
```

---

# 6. EVENT FLOW STRUCTURE (F3.3 CORE LOOP)

```text
Command
  ↓
MembershipLineage (Aggregate)
  ↓
Domain Events
  ↓
Event Store (append-only)
  ↓
Projector
  ↓
Read Model (Committee projections)
  ↓
GetCommitteeDashboard
  ↓
Vue ($t translation rendering)
```

---

# 7. CRITICAL ARCHITECTURAL RULES (ENFORCED BY STRUCTURE)

## 🚫 Forbidden

* No `User::find()` outside Identity context
* No DB access inside Domain
* No event knowledge inside CommitteeContext
* No projection inside MembershipContext
* No UI DTOs inside Domain

---

## ✅ Allowed

* Membership emits events only
* Committee reads projections only
* Identity resolves display names only
* Event store is append-only
* Replay is pure functional logic

---

# 8. WHY THIS STRUCTURE IS IMPORTANT

This structure guarantees:

### ✔ True event sourcing (F3.3 ready)

### ✔ CQRS separation (write vs read)

### ✔ bounded context isolation

### ✔ no accidental coupling between UI and domain

### ✔ deterministic replay capability

### ✔ safe projection rebuilding

---

# 9. IF YOU WANT NEXT STEP

I can generate:

### 🔥 “Migration Plan from Current Code → This Structure”

* file-by-file move strategy
* zero-downtime transition
* test preservation strategy (133 GREEN invariant)
* incremental rollout phases (safe refactor plan)

That would turn this into an executable transformation roadmap.
