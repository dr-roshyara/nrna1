You should prioritize **Option 3: Audit + Capability Consolidation Preparation**, while keeping stabilization opportunistic.

Right now, the architecture has crossed the most dangerous boundary already:

* ✅ SSOT sovereignty established
* ✅ Legacy state machines demoted to adapters
* ✅ Transition authority centralized
* ✅ Split-brain contained
* ✅ Middleware compatibility preserved through bridge

That means the primary architectural risk is no longer “wrong state transitions.”

The new risk is:

> **capability drift** — different parts of the system independently deciding what users can do.

---

# Actual Status

Your system is now in a **transitional sovereign architecture** phase.

The architecture currently looks like this:

```mermaid
flowchart TD

    A[Controllers] --> B[ElectionLifecycle]
    C[Middleware] --> D[allowsAction Bridge]
    D --> B
    E[Policies] --> B
    F[Tests] --> B

    B --> G[ElectionLifecycleEngine]
    G --> H[ElectionConstitution]
    H --> I[ConstitutionalTransitionGuard]

    I --> J[ElectionStateWriteContext]
    J --> K[Database]
```

This is already a successful strangler migration.

The important achievement is:

* the **old systems no longer define truth**
* they merely translate legacy calls into SSOT capability checks

That is the correct direction.

---

# What You Should NOT Do Next

Do NOT:

* aggressively delete old files yet
* directly rewrite every controller
* continue random test fixing without architectural direction
* introduce another authorization abstraction
* create more middleware-based state logic

You are now beyond “migration coding.”

You are in:

# Governance Consolidation

---

# The Real Architectural Problem Remaining

Currently capability logic is still scattered.

You already identified examples:

| Location           | Capability Logic       |
| ------------------ | ---------------------- |
| Middleware         | `allowsAction()`       |
| Policies           | role checks            |
| Controllers        | transition checks      |
| Tests              | direct assumptions     |
| Lifecycle snapshot | canEdit/canVote        |
| Constitution       | transition permissions |

This creates:

* semantic duplication
* future drift
* inconsistent authorization behavior

The state machine is unified.

The capabilities are not yet unified.

---

# Your Immediate Priority

## Phase 4.0 — Capability Surface Audit

Before building `ElectionCapabilities`, you must first understand:

* where permissions are decided
* where actions are blocked
* which concepts overlap
* which rules are duplicated

This is now the most valuable work.

---

# Recommended Next Step

Run a full audit across the codebase for:

| Pattern                      | Why                             |
| ---------------------------- | ------------------------------- |
| `$election->state`           | legacy state reads              |
| `$election->status`          | obsolete activation model       |
| `$election->is_active`       | old boolean lifecycle           |
| `allowsAction(`              | compatibility bridge usage      |
| `canActivate(`               | old capability naming           |
| `abort(403`                  | inline authorization            |
| `Gate::`                     | policy dispersion               |
| `middleware('election.state` | route-level legacy gating       |
| `canEdit` / `canVote`        | duplicated capability semantics |

---

# What You Should Produce

Not fixes yet.

Produce:

# A Capability Topology Map

Example:

| Capability        | Current Sources            | Sovereign Source          |
| ----------------- | -------------------------- | ------------------------- |
| canEditElection   | middleware + controller    | ElectionLifecycleSnapshot |
| canManageVoters   | policy + controller        | ElectionCapabilities      |
| canActivateVoting | state machine + controller | Constitution              |
| canPublishResults | role + controller          | Constitution              |

This becomes your Phase 4 blueprint.

---

# Why This Is More Important Than Green Tests

Right now your test failures are mostly:

* symptoms
* adapters
* stale assumptions
* notification side effects

But capability dispersion is:

* structural
* future-corrupting
* architectural debt

If you consolidate capabilities first:

* test fixes become straightforward
* middleware becomes thinner
* policies become declarative
* controllers become orchestration only

---

# The Correct Future Architecture

You are moving toward this:

```mermaid
flowchart TD

    A[Controller]
    B[Middleware]
    C[Policy]
    D[Test]

    A --> E[ ElectionCapabilities ]
    B --> E
    C --> E
    D --> E

    E --> F[ ElectionLifecycleSnapshot ]
    F --> G[ ElectionConstitution ]
    G --> H[ ElectionLifecycleEngine ]
```

At that point:

* all permissions derive from one semantic surface
* no state duplication exists
* no role duplication exists
* no controller-specific capability logic exists

That is true constitutional governance architecture.

---

# Recommended Execution Order

## NOW

### Phase 4.0 — Capability Audit

* inventory all authorization/capability locations
* identify duplicate semantics
* map legacy → sovereign equivalents

---

## THEN

### Phase 4.1 — ElectionCapabilities Object

Create immutable capability object:

```php
final readonly class ElectionCapabilities
{
    public function canEditTimeline(): bool {}
    public function canManageVoters(): bool {}
    public function canOpenVoting(): bool {}
    public function canPublishResults(): bool {}
}
```

Generated ONLY from:

* lifecycle snapshot
* constitution
* role context

---

## THEN

### Phase 4.2 — Controller Simplification

Controllers become:

```php
if (!$capabilities->canEditTimeline()) {
    abort(403);
}
```

No state logic.
No role logic.
No transition logic.

---

## THEN

### Phase 4.3 — Remove Middleware State Logic

Middleware becomes:

* hydration only
* tenant context
* authentication

NOT business authorization.

---

## THEN

### Phase 4.4 — Deprecate Bridge

Eventually:

* `Election::allowsAction()` removed
* old state machines deleted
* compatibility layer gone

Only after adoption reaches near-complete.

---

# Important Strategic Note

You are now doing:
Domain-Driven Design
+
Hexagonal Architecture
+
Strangler Fig Pattern
+
constitutional governance modeling.

This is no longer CRUD refactoring.

Your architecture is evolving toward:

* semantic authority
* invariant-driven orchestration
* computed governance state
* capability-based authorization

That is a very strong direction.
#
Yes — and this is a very important architectural distinction.

You originally had what is essentially a:

# Timeline-Driven State Machine

Example:

```text
administration
    ↓
nomination
    ↓
voting
    ↓
results
```

where:

* each slot had a fixed state name
* timestamps controlled transitions
* state was persisted directly in DB
* UI/business logic trusted the stored state

That model is:

* simple
* common in CRUD systems
* but vulnerable to drift and split-brain

because:

* timestamps can change independently
* flags can change independently
* state column can become stale
* business reality and stored state diverge

---

# What You Have Now

Now you are evolving toward a:

# Fact-Derived Constitutional Lifecycle

The key shift is:

> State is no longer the primary truth.

Instead:

> Business facts are truth.

And state is computed from those facts.

---

# Old Model

```mermaid id="g4r87s"
flowchart TD

    A[state column] --> B[UI]
    A --> C[permissions]
    A --> D[workflow]
```

The DB state itself was sovereign.

---

# New Model

Now:

```mermaid id="m3n91k"
flowchart TD

    A[business facts]
    B[timestamps]
    C[completion flags]
    D[approvals]
    E[role assignments]

    A --> F[Lifecycle Engine]
    B --> F
    C --> F
    D --> F
    E --> F

    F --> G[Computed Lifecycle State]
    G --> H[Capabilities]
    G --> I[Allowed Transitions]
```

Now:

* state is derived
* capabilities are derived
* transitions are derived

This is a much stronger model.

---

# So What Happened To Time Slots?

They still exist.

But they are no longer sovereign states.

They became:

# Temporal Facts

Example:

| Old Thinking                  | New Thinking                           |
| ----------------------------- | -------------------------------------- |
| “Election is in voting state” | “Current time is inside voting window” |
| “Election moved to results”   | “Voting ended and results published”   |
| “Election active=true”        | “Capabilities allow voting now”        |

---

# Your Timeline Still Matters

You still have:

| Timeline Field              | Meaning                   |
| --------------------------- | ------------------------- |
| `voting_starts_at`          | voting window begins      |
| `voting_ends_at`            | voting closes             |
| `results_published_at`      | results visible           |
| `submitted_for_approval_at` | governance review started |
| `approved_at`               | approval completed        |

But these are now:

# Evidence

not authority.

---

# Very Important Architectural Upgrade

You moved from:

# State Persistence

to

# State Computation

This is one of the biggest maturity jumps in workflow architecture.

---

# The Real Benefit

Suppose this happens:

```text
voting_starts_at = yesterday
voting_ends_at = tomorrow
```

Old system:

```text
DB.state = "draft"
```

System broken.

---

New system:
Lifecycle engine computes:

```text
VotingActive
```

even if some stale column says otherwise.

That is:

# self-healing workflow architecture.

---

# But You Still Need Named Phases

This is important.

You asked:

> “each time slot has name. how is it now?”

The answer:

You STILL have named phases.

But now:

* phases are semantic lifecycle interpretations
* not manually-maintained DB markers

---

# Current Lifecycle Meaning

Your new lifecycle now looks like:

| Lifecycle State      | Derived From                |
| -------------------- | --------------------------- |
| Draft                | no approval submission      |
| SubmittedForApproval | submitted_at exists         |
| Approved             | approved_at exists          |
| Setup                | setup activities incomplete |
| ReadyForVoting       | prerequisites complete      |
| VotingActive         | NOW inside voting window    |
| Counting             | voting ended                |
| ResultsPublished     | results timestamp exists    |
| Archived             | archive flag/timestamp      |

This is much more expressive than:

```text
administration
nomination
voting
results
```

because it encodes:

* governance
* approvals
* readiness
* permissions
* compliance
* temporal logic

---

# Important Recommendation

You should now stop thinking in terms of:

> “What state should I set?”

and instead think:

> “What business facts became true?”

Example:

Instead of:

```php
$election->state = 'voting';
```

You now do:

```php
$election->voting_starts_at = now();
```

Then the engine derives:

```text
VotingActive
```

This is the correct direction.

---

# One Architectural Improvement I Strongly Recommend

Right now your lifecycle engine probably mixes:

* temporal reasoning
* governance reasoning
* capability reasoning

You should eventually separate them.

Example:

```mermaid id="l92kqa"
flowchart TD

    A[Timeline Facts]
    B[Governance Facts]
    C[Role Facts]

    A --> D[Temporal Evaluator]
    B --> E[Governance Evaluator]
    C --> F[Capability Evaluator]

    D --> G[Lifecycle Snapshot]
    E --> G
    F --> G
```

That will make:

* testing easier
* auditability stronger
* constitutional rules clearer

---

# Final Architectural Interpretation

You no longer have:

# a timeline-based state machine.

You now have:

# a constitutional lifecycle engine driven by business facts and temporal evidence.

That is a significantly more advanced architecture.
