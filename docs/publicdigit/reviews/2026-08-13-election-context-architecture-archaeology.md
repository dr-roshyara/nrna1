# Election Context — Architecture Archaeology

**Session:** 4 (Principal Architect / DDD boundary verification)
**Date:** 2026-08-13
**Branch:** `election-review` @ `d5efff2a`
**Mode:** READ-ONLY. No production code, tests, namespaces, fixtures or configuration were modified.
**Commission:** Determine the ACTUAL current Election architecture and which code is authoritative for Election-Only mode. Do not accept the prior claim that `app/Contexts/Elections/Domain/` is authoritative.

> **Evidence discipline used throughout.** Every authority claim below is grounded in one of four kinds of evidence: (a) a DI binding, (b) a call-site grep, (c) a declared architecture artifact (`deptrac.yaml`, `config/app.php`), or (d) a read of the executing code. Where evidence is absent, the row says UNKNOWN rather than guessing. Words like *legacy*, *active* and *deprecated* are used only where cited.

---

## 1 · Executive conclusion

**The prior claim is REFUTED.**

> ~~"`app/Contexts/Elections/Domain/` is authoritative for Election rules."~~

Three findings, each independently sufficient to refute it:

1. **There are two different directories, not one.** `app/Contexts/Election/` (singular) and `app/Contexts/Elections/` (plural) both exist. They are different architectural generations with different provenance, different naming conventions, different wiring, and different capabilities. The quoted analysis appears to have conflated them.

2. **Neither owns the Election lifecycle.** The runtime authority for lifecycle, state derivation, transitions and voting-gating is `app/Domain/Election/` (rules) + `app/Application/Election/` (engine/guard) + `app/Models/Election.php` (aggregate + transition orchestration). This is proven by DI binding, by call-sites, and by reading the executing code.

3. **The repository itself says so, in writing.** `app/Contexts/Election/Infrastructure/Acl/LegacyElectionExistenceAdapter.php` documents the legacy `elections` table as *"the CURRENT operational source of truth, until a greenfield Election-lifecycle capability replaces it (Strangler)."* The greenfield Election context explicitly disclaims lifecycle ownership.

**Dependency direction is one-way and decisive:** `Contexts/Elections → Domain/Election`. `app/Domain/` and `app/Application/` contain **zero** references to `App\Contexts` (verified by grep). A downstream consumer cannot be the authority for its upstream's rules.

**What `Contexts/Elections` (plural) actually owns:** one capability — **voter eligibility decision + voter assignment**. 15 files. No ServiceProvider of its own. Absent from `deptrac.yaml`. Its two mode policies are Phase-A/B strangler artifacts, one of which is a live decision function and one of which is a dead stub.

**Final verdict (§20): CURRENT ARCHITECTURE ESTABLISHED** for everything EM-VOT-002 touches. Six unknowns were raised and itemised in §16; two were closed in-session (§16.1, §7). Of the four remaining, U-1 is a decision and U-6 is a test; only U-2 (entitlement) and U-4 (voter-verification depth) are genuine remaining archaeology. **None blocks EM-VOT-002.**

---

## 2 · Current runtime architecture

### 2.1 Frozen tree

```
branch:  election-review
HEAD:    d5efff2a (PBDIGIT-68) Current-state report: alignment named per layer; SD-15 history found; S1 fix flagged
```

Uncommitted at session start (left untouched):

| Path | State |
|---|---|
| `app.log` | modified |
| `architecture_legacy/ai_architecture/documentation/20260809_0804_0how_to_develop_knowledgeos_further.md` | untracked |
| `docs/plans/20260812-1748-election-only-implementation-boundary-plan.md` | untracked |
| `docs/publicdigit/backlog/claude_findings.md` | untracked |
| `docs/publicdigit/backlog/election_findings.md` | untracked |
| `docs/publicdigit/backlog/election_only_mode_no_constituion.md` | untracked |
| `docs/publicdigit/backlog/implemntation_siutation.md` | untracked |
| `docs/publicdigit/reviews/2026-08-13-em-vot-002-implementation-boundary-audit.md` | untracked |
| `tests/Feature/Election/ElectionOnlyEntitlementPinTest.php` | untracked |

`f2c2cc4e` (PBDIGIT-64, EM-VOT-002) is committed and present in the working tree. Not judged here; §14 maps its authority only.

### 2.2 Reference-weight census

File counts containing each namespace (grep, whole repository):

| Namespace | `app/` | `routes/` | `tests/` | `database/` | `config/` |
|---|---|---|---|---|---|
| `App\Models\Election` | **108** | 1 | **315** | 18 | 0 |
| `App\Domain\Election` | **173** | 0 | **154** | 1 | 0 |
| `App\Application\Election` | **93** | 1 | **111** | 0 | 0 |
| `App\Contexts\Election\` (sing.) | 29 | 0 | 13 | 0 | **1** |
| `App\Contexts\Elections` (plur.) | 19 | 0 | 14 | 0 | 0 |
| `App\Domain\Voting` | 19 | 0 | 3 | 0 | 0 |

Weight is corroborating, not decisive — a small module can still be authoritative. The decisive evidence is dependency direction (§10) and DI binding (§2.3).

### 2.3 The authoritative wiring

`app/Providers/AppServiceProvider.php:146-158` — the single place the lifecycle authority is chosen:

```php
// Election Lifecycle: SSOT (Single Source of Truth) engine for state derivation
$this->app->bind(
    \App\Domain\Election\Services\ElectionLifecycleEngine::class,        // port  (Domain)
    \App\Application\Election\Services\ElectionLifecycleEngineImpl::class // impl (Application)
);

// Election Constitutional Guard: Hard gate enforcement for state transitions with metrics
$this->app->singleton(
    \App\Application\Election\Services\ConstitutionalTransitionGuard::class, ...
);
```

Neither Election *context* appears in this wiring. The lifecycle port lives in `app/Domain/Election`, its implementation in `app/Application/Election`.

---

## 3 · Election lifecycle architecture

### 3.1 The layer split (evidence-backed)

| Concern | Owner (file) | Layer | Evidence |
|---|---|---|---|
| **Rule table** — allowed states, roles, preconditions, target state per action | `app/Domain/Election/Constitution/ElectionConstitution.php` (`const RULES`) | Domain | read; sole definition of `open_voting` |
| **Command authorization** — evaluates rules + preconditions | `app/Application/Election/Services/ConstitutionalTransitionGuard.php` | Application | only file evaluating `getPreconditionsForAction()` |
| **Computed state derivation** — 12-rule priority ladder | `app/Application/Election/Services/ElectionLifecycleEngineImpl.php::getState()` | Application | bound to the Domain port |
| **Capability projection** — `canVote`/`canEdit`/… per state | same file, `derivePermissions()` | Application | read |
| **Consumption API** — the one façade all callers use | `app/Application/Election/Facades/ElectionLifecycle.php` | Application | used by controllers + middleware |
| **Transition orchestration** — lock, audit row, state write, side effects | `app/Models/Election.php::transitionTo()` (L1614-1710) | Model/Infra | sole `updateQuietly(['state' => …])` site |
| **Write barrier** — blocks unauthorized `state` writes | `app/Models/Election.php::setStateAttribute()` (L910) | Model/Infra | read |
| **Temporal window** | `app/Services/ElectionClockService::isVotingOpen()` | Service | called by the engine |

### 3.2 Is state persisted, computed, projected, or hybrid?

**HYBRID, and the two halves disagree by design.** This is the single most important structural fact about the Election architecture, and it is what EM-VOT-002 collided with.

```
COMMAND SIDE (writes)                     COMPUTED SIDE (reads)
─────────────────────                     ─────────────────────
Election::transitionTo(Transition)        ElectionLifecycleEngineImpl::getState()
  → ConstitutionalTransitionGuard           → 12-rule ladder over business facts
      → ElectionConstitution::RULES         → ElectionClockService (time)
      → validatePreconditions()             → candidacies() (EM-VOT-002)
  → validateTransitionRules()  (3rd layer)
  → updateQuietly(['state' => $toState])  ← writes the `state` COLUMN
```

The `state` column is explicitly demoted in the engine's own docblock:

> *"CRITICAL: Derives state ONLY from business facts. State column is compatibility cache, not truth."*

Consequence: **the column can be advanced by a command while the engine refuses to derive the same state.** No command guard can reach the computed path, and no computed rule can reach the command path. That is the architectural reason EM-VOT-002 required a change in *both* places (`ElectionConstitution` precondition + engine rule 5), and it is a property of the current architecture, not a defect introduced by PBDIGIT-64.

### 3.3 Two read paths for "current state" — a live duplicate authority

| API | Reads | Result |
|---|---|---|
| `Election::lifecycleState()` (L1181) | `$this->state` column → `ElectionLifecycleState::from()` | **cache** |
| `Election::getCurrentStateAttribute()` (L893) | `$this->state ?? 'draft'` | **cache** |
| `Election::currentState()` (L1569) | `ElectionLifecycleEngineImpl::getState()` | **computed** |
| `Election::getEngineSnapshot()` (L1578) | engine `compute()` | **computed** |
| `Election::state_info` accessor | `ElectionLifecycle::of()->state()` | **computed** |
| `ElectionLifecycle::of($e)->state()` | engine `compute()` | **computed** |

Both families are live. `transitionTo()` itself reads the **cache** (`$freshElection->current_state`, L1637) to record `from_state` in the audit row, while the guard it calls is handed a **computed** snapshot (L1646). Recorded as an observation; not in EM-VOT-002's scope.

### 3.4 Engine resolution inconsistency

The DI binding registers the interface `ElectionLifecycleEngine → ElectionLifecycleEngineImpl`. But every production call site resolves the **concrete class** directly:

- `Election::currentState()` L1571 — `app(ElectionLifecycleEngineImpl::class)`
- `Election::getEngineSnapshot()` L1580 — same
- `Election.php` L1928 — same
- `ElectionLifecycle::__construct()` — `app(ElectionLifecycleEngineImpl::class)`

Same object today, so behaviour is identical; but the interface seam is not actually load-bearing, and substituting the engine via the container would not take effect. Observation only.

### 3.5 The named components, classified

| Component | Classification | Evidence |
|---|---|---|
| `ElectionConstitution` | **A** — active runtime authority (rule table) | sole `open_voting` definition |
| `ElectionLifecycleEngine` (interface) | **A** — active port | bound in AppServiceProvider |
| `ElectionLifecycleEngineImpl` | **A** — active authority (derivation) | resolved at every read site |
| `ConstitutionalTransitionGuard` | **A** — active authority (command) | sole precondition evaluator |
| `ElectionLifecycle` (façade) | **A** — active consumption API | controllers + middleware |
| `ElectionStateWriteContext` | **A** — active write-authorization boundary | checked in `setStateAttribute()` |
| `Transition` (VO) | **A** — active input type | `transitionTo()` parameter |
| `EnsureVotingActive` | **B** — active adapter (defence-in-depth) | delegates to `canVote()`, no own logic |
| `EnsureElectionState` | **B** — active adapter | delegates to façade + `OperationCapabilityMapper` |
| `OperationCapabilityMapper` | **B** — active adapter | used by `EnsureElectionState` |
| `Election` model | **A** — active aggregate + orchestrator | sole state-write site |
| `ElectionStateMachine` | **D** — transitional, no production caller | `getStateMachine()` called only from tests; an architecture test forbids new call sites |
| `TransitionMatrix` | **E** — dead | its `validate()` is commented out at `AppServiceProvider.php:366`; comment reads *"deprecated: TransitionMatrix validation has been migrated to ElectionConstitution"* |
| `ElectionState` (enum) | **E** — dead, and semantically stale | referenced only by the dead `TransitionMatrix`; carries the OLD vocabulary (`administration`, `nomination`, `voting`, `results`) — disjoint from `ElectionLifecycleState` |

> **Incidental finding (dev-only, not in scope).** `app/Console/Commands/ActivateElectionCommand.php` writes `state = 'administration'` — a value absent from `ElectionLifecycleState`, so a subsequent `lifecycleState()` call would raise `ValueError`. The command self-documents as development/testing only and announces the bypass. Recorded for backlog triage; **no change recommended by this session**.

---

## 4 · Election-Only mode architecture

### 4.1 What "Election-Only mode" *is*, in code

It is **one immutable per-election column** (`elections.voter_source_strategy`) with two values, modelled by an enum that lives in **`app/Domain/Election/Enum/VoterSourceStrategy.php` — not in either Election context.**

```php
case ImportedVoterRegistry = 'election_only';    // label(): "Election-Only"
case MembershipRegistry    = 'full_membership';  // label(): "Full Membership"
```

The enum documents itself as *"PHASE 3 TRANSITIONAL SEMANTIC BRIDGE VOCABULARY … NOT final constitutional authority vocabulary"*, with both cases carrying `@deprecated` on the case *name* pending a Phase-4 governance-language review.

*Note:* this "Domain" enum imports `App\Models\Organisation`, `App\Models\Election` and `Illuminate\Support\Facades\Log` — a Domain-layer purity deviation under the project's own Layer-3 rule. Factual observation; it does not change where the concept lives.

### 4.2 Where mode is selected

**Exactly one production site** — election creation:

```
app/Http/Controllers/Election/ElectionManagementController.php:163
  'voter_source_strategy' => VoterSourceStrategy::fromOrganisation($organisation)->toPersistenceValue()
      → $organisation->uses_full_membership ? MembershipRegistry : ImportedVoterRegistry
```

Plus one backfill command (`BackfillVoterSourceStrategy`) for pre-Phase-2 rows. After creation the snapshot is sovereign and immutable: `fromElection()` **throws** `RuntimeException` on a null snapshot rather than falling back — verified by read.

### 4.3 Where mode is enforced

| # | Site | Layer | What it does |
|---|---|---|---|
| 1 | `App\Services\VoterEligibilityService::isEligibleVoter()` | Service | delegates to the policy port |
| 2 | `App\Services\VoterEligibilityService::unassignedEligibleQuery()` | Service | **branches on mode itself** — own SQL per mode |
| 3 | `Contexts\Elections\Infrastructure\Policies\EloquentVoterEligibilityQueryService` | Context Infra | branches on mode; builds `EligibilityContext`; delegates decision |
| 4 | `Contexts\Elections\Domain\Policies\ElectionOnlyPolicy::decideForContext()` | Context Domain | the pure decision: `mode.isImportedVoterRegistry() && isActive && !isDeleted` |
| 5 | `App\Services\VoterImportService` | Service | mode-specific parse/preview/import paths |
| 6 | `VoterImportController`, `ElectionVoterController` | HTTP | serialise `->toApiValue()` into Inertia props |

### 4.4 The commission's seven questions, answered

1. **What does `ElectionOnlyPolicy` own?** The pure eligibility *decision* for election-only mode — active org-user, not soft-deleted — via `decideForContext()`. Nothing else.
2. **What does `FullMembershipPolicy` own?** The mirror decision for membership mode. Same shape; the fee/status/type data is gathered by infrastructure, not by the policy.
3. **Where is mode selected?** `ElectionManagementController::store()` at creation, from `organisation.uses_full_membership`. Immutable thereafter.
4. **Where is mode enforced?** The six sites in §4.3 — all within voter eligibility, assignment and import.
5. **Does mode affect…**

   | Capability | Affected? | Evidence |
   |---|---|---|
   | voter assignment | **YES** | `AssignVoterCommand`/`BulkAssignVotersCommand` carry the mode |
   | voter eligibility | **YES** | the policy port and both implementations |
   | voter import | **YES** | `VoterImportService` mode-specific paths |
   | election lifecycle | **NO** | zero mode references in `Domain/Election` lifecycle or `Application/Election` |
   | nomination / candidacy | **NO** | `CandidacyManagementController` = 0, `CandidacyController` = 0 mode refs |
   | voting / vote casting | **NO** | `VoteController` = 0 mode refs |
   | credentials / codes | **NO** | `CodeController` = 0 mode refs |
   | membership | inverse | mode is *derived from* `uses_full_membership`; it does not drive Membership |

6. **What is actually implemented today?** Selection at creation; snapshot immutability with hard failure on absence; mode-branched eligibility queries; mode-branched bulk filtering; mode-branched import; API/telemetry/UX vocabulary separation (`toApiValue()` / `telemetryKey()` / `label()`).
7. **What is architectural intention only?**
   - `ElectionOnlyPolicy::isEligible()` and `FullMembershipPolicy::isEligible()` — the interface methods are **never invoked in production**. The container binds the port to `EloquentVoterEligibilityQueryService`, so only `decideForContext()` is reached. `ElectionOnlyPolicy::isEligible()` is a stub whose body returns `true` for any imported-registry mode and carries `// Phase A: Placeholder satisfies interface contract`.
   - `qualifyingSubset()` on both domain policies returns `[]` by design ("domain policies don't implement bulk filtering").
   - Phase-4 vocabulary finalisation — not started.
   - The `AppServiceProvider` lines 130-138 bind `ElectionOnlyPolicy → ElectionOnlyPolicy` and `FullMembershipPolicy → FullMembershipPolicy`: **no-op self-registrations**. They are reachable only as constructor dependencies of the Eloquent service, which the container would autowire regardless.

> **A correction I owe this record.** Mid-investigation I read the caller grep as showing *zero* production callers for `ElectionOnlyPolicy`. That was wrong: the grep excluded `app/Contexts/Elections/`, which is exactly where the live caller sits (`EloquentVoterEligibilityQueryService` injects it). The accurate statement is the narrower one above — `decideForContext()` is live; `isEligible()` is dead.

---

## 5 · `app/Models/Election.php` assessment

**Classification: A — active runtime authority. It is the de-facto aggregate root.** 2,279 lines.

| It owns | Evidence |
|---|---|
| the only `state` write in the codebase | `updateQuietly(['state' => $toState])`, L1670, comment: *"only place in codebase"* |
| transition orchestration: distributed lock → DB transaction → guard → business rules → audit row → state write → side effects | L1614-1710 |
| the write barrier / sovereignty boundary | `setStateAttribute()` L910-933 |
| a third business-rule layer, `whyCannotOpenVoting()` | L2094-2115 |
| both cached and computed state read APIs | §3.3 |
| persistence, casts, relations, counters | throughout |

This is not a thin Eloquent read model. It is where lifecycle *behaviour* is orchestrated, which is why any Election work that ignores `app/Models/Election.php` will be wrong regardless of which `Contexts/` folder it targets.

---

## 6 · `app/Domain/Election/` assessment

**Classification: A — current runtime authority for Election rules and vocabulary.** 108 files.

It contains, and is called by production for:

- **the constitutional rule table** — `Constitution/ElectionConstitution.php` (`RULES`), the only definition of what `open_voting` requires
- **the lifecycle port** — `Services/ElectionLifecycleEngine.php`, the interface that AppServiceProvider binds
- **the state vocabulary** — `Enum/ElectionLifecycleState` (12 cases), `Enum/ElectionAction`, `Enum/ElectionRole`, `Enum/CapabilityOutcome`
- **the Election-Only mode concept** — `Enum/VoterSourceStrategy` (§4.1)
- **domain events** — `ElectionApproved`, `VotingOpened`, `VotingClosed`, `ResultsPublished`, `VoterAssignedToElection`, `BulkVotersAssignedToElection`, …
- **window policies** — `Policies/NominationWindowPolicy` (constructed by the engine), `Policies/VotingWindowPolicy`
- **transition value objects** — `StateMachine/Transition`, `TransitionTrigger`
- **the Security/sovereignty subsystem** — ~60 files under `Security/` (constitutional trust, divergence ledger, overlays, ballot authorization, replay certification)

**It must not be called "old DDD."** It holds the live constitutional rule set. The only *dead* residents are `StateMachine/TransitionMatrix` (E) and `Enum/ElectionState` (E), plus `StateMachine/ElectionStateMachine` (D).

---

## 7 · `app/Application/Election/` assessment

**Classification: A — active application-layer authority for the current Election domain.** 69 files.

It is **not** a facade over an older domain, and **not** orchestration around `Contexts/Elections`. Proof: it contains **zero** references to `App\Contexts` (grep). It is the application layer *of* `app/Domain/Election`, implementing that Domain's port.

Contents by role:

| Group | Role | Classification |
|---|---|---|
| `Services/ElectionLifecycleEngineImpl` | implements the Domain port; 12-rule derivation | A |
| `Services/ConstitutionalTransitionGuard` | command authorization + precondition evaluation | A |
| `Services/ElectionCapabilityResolver` | capability resolution | A |
| `Facades/ElectionLifecycle` | the single consumption API | A |
| `Governance/ElectionStateWriteContext` | write-authorization boundary | A |
| `Capabilities/*` (16 files) | capability snapshot / policy layers / trace | A |
| `Security/*` (30+ files) | overlays, trust policy evaluation, snapshot assembly | A |
| `Monitoring/*` | constitutional metrics, drift monitor, SSOT violation events | A |
| `Deprecation/*` (`DeprecationPolicy`, `QueryPolicyGuard`, `ElectionReadModel`, `LegacyElectionStateObserver`) | **active compatibility layer** — graduated enforcement levels 1-4 gating the write barrier | **C** |

The `Deprecation/` group deserves emphasis: it is *active*, not abandoned. `DeprecationPolicy::isEnforcementActive($level)` decides whether an unauthorized `state` write is silently recorded (level ≥1) or thrown (level ≥4).

**Resolved (U-5): the level is a hardcoded constant, not configuration.**

```php
app/Application/Election/Deprecation/DeprecationPolicy.php:52
public const STRICT_LEVEL = 1;                      // getLevelName(1) === 'metrics strict'

public static function isEnforcementActive(int $requiredLevel): bool
{
    return self::STRICT_LEVEL >= $requiredLevel;     // no config(), no env()
}
```

Levels are `0 warning · 1 metrics strict · 2 query guard strict · 3 lifecycle strict · 4 full strict`. At the compiled-in level 1:

- `isEnforcementActive(1)` → **true** → an unauthorized `state` write **is recorded** to `ConstitutionalMetrics`;
- `isEnforcementActive(4)` → **false** → the write barrier **never throws**, and the write **proceeds**.

**Therefore the sovereignty boundary is observability-only in every environment today.** `setStateAttribute()` reports unauthorized writes; it does not prevent them. This is a correction to how §11 and §19 would otherwise read, and it is reflected in both. It is not environment-dependent and cannot be tightened without a code change.

---

## 8 · `app/Contexts/Election*` assessment — the two directories

### 8.1 `app/Contexts/Election/` (SINGULAR) — governed greenfield context

29 files. **Classification: A within its own capability; explicitly NOT the lifecycle owner.**

| Evidence | Finding |
|---|---|
| `config/app.php:212` | its `ElectionServiceProvider` **is** registered |
| `deptrac.yaml` | its Domain/Application/Infrastructure **are** declared layers (`ElectionDomain`, `ElectionApplication`, `ElectionInfrastructure`) |
| `Domain/Election.php` docblock | *"the part of an election that reacts to a binding determination and DECIDES how the election is corrected"* |
| `Infrastructure/Acl/LegacyElectionExistenceAdapter.php` docblock | legacy `elections` table is *"the CURRENT operational source of truth, until a greenfield Election-lifecycle capability replaces it (Strangler; then this adapter is swapped with no domain change)"* |
| provider docblock | *"The `ElectionExistencePort` binding is the Strangler seam: today → legacy ACL adapter; a future greenfield lifecycle capability replaces it here"* |

**Capability owned:** apply an adjudication *determination* to an election as a *correction* — idempotent, forward-only (ADR-T8/T11: anonymity forbids un-casting votes), tenant-free at the aggregate (ADR-T16), integrating via inbox/outbox events (`DeterminationIssued` in → `ElectionCorrectionApplied` out). Governed by WP-7 slices, ARB rulings, R-44/R-60/R-70, TP-1.

This context is **mature, governed, and correctly bounded** — and it says in its own source that it does not own the lifecycle.

### 8.2 `app/Contexts/Elections/` (PLURAL) — ungoverned strangler slice

15 files. **Classification: A for one narrow capability; NOT a peer bounded context.**

| Evidence | Finding |
|---|---|
| `config/app.php` | **no ServiceProvider registered** — bindings live in the monolithic `app/Providers/AppServiceProvider.php` |
| `deptrac.yaml` | **absent entirely** — not an analysed path, not a declared layer |
| outbound imports | depends on `App\Domain\Election\*`, `App\Models\*`, `Illuminate\*` |
| inbound from other contexts | **zero** — no other `app/Contexts/*` imports it |
| docblocks | *"Phase A"*, *"Phase B"*, *"Phase B strangler"*, *"Phase C: Model methods will delegate…"* |

**Capability owned (live):**

| File | Runtime role |
|---|---|
| `Domain/Policies/VoterEligibilityPolicy` | the eligibility **port**; container-bound |
| `Infrastructure/Policies/EloquentVoterEligibilityQueryService` | the bound implementation — builds context, branches on mode |
| `Domain/Policies/ElectionOnlyPolicy::decideForContext()` | live pure decision |
| `Domain/Policies/FullMembershipPolicy::decideForContext()` | live pure decision |
| `Domain/ValueObjects/EligibilityContext` | live DTO |
| `Application/Handlers/AssignVoterHandler`, `BulkAssignVotersHandler` | live — injected into `ElectionVoterController` |
| `Application/Commands/AssignVoterCommand`, `BulkAssignVotersCommand` | live command DTOs |
| `Infrastructure/Repositories/EloquentVoterRepository` | live |
| `Domain/Events/ResultsPublishedEvent`, `ResultsUnpublishedEvent` | present; consumer not traced (see §16) |
| `Domain/Policies/*::isEligible()`, `*::qualifyingSubset()` | **dead stubs** (§4.4 #7) |

**Structural note.** Two directories differing only by a plural `s`, both under `app/Contexts/`, holding unrelated capabilities from different architectural generations, is itself a hazard: `App\Contexts\Election\…` and `App\Contexts\Elections\…` are trivially confusable in imports, in greps, and — as the quoted analysis demonstrates — in architectural reasoning. Recorded as a finding for governance (§18); **no rename recommended or performed here.**

---

## 9 · HTTP / application entry paths

Traced from route file to database. Every step is a real file.

### A · Election creation

```
POST (organisation-scoped election create)
  → app/Http/Controllers/Election/ElectionManagementController::store()          L117
      → VoterSourceStrategy::fromOrganisation($organisation)->toPersistenceValue()  L163
          → app/Domain/Election/Enum/VoterSourceStrategy
      → Election::create([... 'voter_source_strategy' => …])
          → app/Models/Election.php  (fillable L116, cast L216)
              → DB: elections
```
Mode is snapshotted here and never again.

### B · Approval / submission

```
ElectionManagementController::submitForApproval()                               L1049
  → Election::transitionTo(Transition::manual('submit_for_approval', …))
      → [ common transition path, path D ]
```
Constitution: `submit_for_approval` — states `[draft]`, roles `[chief, deputy]`, preconditions `[timezone_set]`, target `submitted_for_approval`. `approve`/`reject` are `platform_admin` only; `auto_submit` is the `system` free-plan path (`≤ config('election.self_service_voter_limit', 40)` via `Election::requiresApproval()`).

### C · Begin setup

```
ElectionManagementController::activate()                                        L222
  → Election::transitionTo(Transition::manual('begin_setup', auth()->id(), …))  L235
```

### D · Lifecycle transition — the common command path

```
Election::transitionTo(Transition)                                    app/Models/Election.php:1614
  ├─ Cache::lock("election_transition:{id}", 10)->block(5, …)                   L1616
  └─ DB::transaction:
       ├─ $freshElection = $this->fresh()                                       L1634
       ├─ $fromState = $freshElection->current_state         ← CACHED column    L1637
       ├─ $toState   = ElectionConstitution::getTargetStateForAction($action)   L1641
       ├─ $snapshot  = ElectionLifecycle::of($freshElection)->snapshot()  ← COMPUTED  L1646
       ├─ if (!$transition->isSystemTriggered()):                               L1649
       │     ConstitutionalTransitionGuard::assertAllowed($e, $action, $snapshot)
       │       ├─ ElectionConstitution::getRulesForAction()
       │       ├─ state check · role check (Election::resolveActorRole())
       │       └─ validatePreconditions()  ← 'has_approved_candidates' evaluated here (L188)
       ├─ $freshElection->validateTransitionRules($transition)                  L1655
       │     └─ 'open_voting' → validateOpenVoting() → whyCannotOpenVoting()    L2094
       ├─ ElectionStateTransition::create([...])          ← audit row           L1658
       ├─ $this->updateQuietly(['state' => $toState])     ← ONLY state write    L1670
       └─ match($action) → applySideEffectsFor…()                               L1673
```

### E · Open voting

```
ElectionManagementController::openVoting()                                      L901
  → transitionTo(Transition::manual('open_voting', …))                          L906
      → guard: states [setup_nomination, ready_for_voting] · role [chief]
               preconditions [voting_window_defined, timezone_set, has_approved_candidates]
      → validateOpenVoting() → whyCannotOpenVoting()
      → target state: voting_active
```

### F · Lifecycle state derivation (the read path)

```
any caller
  → ElectionLifecycle::of($election)                app/Application/Election/Facades/ElectionLifecycle.php
      → app(ElectionLifecycleEngineImpl::class)->compute($election)
          → getState()  — 12-rule ladder
              ├─ suspended_at · archived_at · results_published_at            (rules 1-3)
              ├─ rule 4  Counting: now ≥ voting_ends_at ∧ approved ∧ admin ∧ nomination
              ├─ rule 5  VotingActive: ElectionClockService::isVotingOpen()
              │                        ∧ hasCandidatesApproved()   ← EM-VOT-002
              ├─ rules 6-11 …
              └─ rule 12 Draft — else throw InvalidElectionStateException
          → derivePermissions($state)  → canVote / canEdit / canManageVoters / …
      → recordLifecycleEvaluation()   (ConstitutionalMetricsContract)
```

### G · Voter eligibility / assignment

```
ElectionVoterController (index / store / bulkStore)
  ├─ App\Services\VoterEligibilityService::isEligibleVoter($org,$user,$mode)
  │     → VoterEligibilityPolicy  [port, Contexts\Elections\Domain]
  │         → EloquentVoterEligibilityQueryService  [Contexts\Elections\Infrastructure]  ← DI bound
  │             ├─ buildElectionOnlyContext()   → DB: organisation_users
  │             │     → ElectionOnlyPolicy::decideForContext()
  │             └─ buildFullMembershipContext() → DB: members ⨝ organisation_users ⨝ membership_types
  │                   → FullMembershipPolicy::decideForContext()
  ├─ App\Services\VoterEligibilityService::unassignedEligibleQuery()
  │     → own mode branch, own SQL          ← DUPLICATE of the above (§13)
  └─ AssignVoterHandler / BulkAssignVotersHandler  [Contexts\Elections\Application]
        → EloquentVoterRepository → DB: election_memberships
        → events: VoterAssignedToElection / BulkVotersAssignedToElection  [Domain\Election\Events]
        → failures → DeadLetterEntry
```

### H · Voter verification

`app/Domain/Election/Security/VoterVerification/VerificationSession.php` +
`app/Application/Election/VoterVerification/Policies/VoterVerificationPolicy.php`.
Sits inside the `Domain/Election` Security subsystem, not in either context. Full trace not required by EM-VOT-002 and not completed — see §16.

### I · Vote casting

```
route (middleware: voting.active → EnsureVotingActive)
  → EnsureVotingActive::handle()               app/Http/Middleware/EnsureVotingActive.php
      → ElectionLifecycle::of($election)->canVote()      ← delegates, no own logic
  → VoteController::create() L212 / store() L1513
      → $lifecycle = ElectionLifecycle::of($election); if (!$lifecycle->canVote()) …   L220-221, L1609
      → first_submission() L518 / second_submission() L765   (two-use code system)
      → DB: votes (NO user_id — ADR-T11 anonymity), results
```

Middleware self-documents its subordinate role: *"INVARIANT D: Middleware enforcement is defence-in-depth, not primary authority"* — the controller `canVote()` check is the primary gate. Both resolve to the same engine snapshot.

### J · Results

`ElectionManagementController::publish()` L821 → `transitionTo('publish_results')` (constitution: state `[counting]`, role `[chief]`). `ResultController` / `DemoResultController` serve reads.

---

## 10 · Bounded-context evidence

### 10.1 Declared architecture (the strongest artifact in the repository)

`deptrac.yaml` — self-described as encoding *"the APPROVED ARCHITECTURE (bounded contexts, hexagonal layers, approved dependencies) — never … the incidental filesystem/package layout"*, per ARB / PB-007.

Analysed paths — **exactly four**:

```
./app/Contexts/Contestation
./app/Contexts/Adjudication
./app/Contexts/Election        ← SINGULAR
./app/Contexts/Shared
```

Declared layers: `{Contestation,Adjudication,Election}×{Domain,Application,Infrastructure}` + `Shared`. Approved collaboration: **events only** (TP-1) — no direct cross-context code dependency.

Therefore, in the *approved* model:
- the governed greenfield Core is **Contestation · Adjudication · Election (singular) · Shared**;
- `app/Contexts/Elections` (plural) **is not part of it**;
- `app/Domain/`, `app/Application/`, `app/Models/` are **not covered by deptrac at all** — the file names them among *"approved external platform dependencies … intentionally outside the analysed paths TODAY"*, with an `ExternalPlatform` layer recorded as a future refinement.

**Consequence to state precisely:** the lifecycle authority (`Domain/Election` + `Application/Election` + `Models/Election`) is **real, live, and outside the governed boundary model**. It is not "legacy to be deleted"; it is the operational core that the greenfield model has not yet strangled, and the greenfield Election context's own ACL says exactly that.

### 10.2 Per-context observed state

| Context | Files | Provider in `config/app.php` | In deptrac | Inbound from other contexts |
|---|---|---|---|---|
| Membership | 495 | ✅ | ✗ | — |
| Governance | 89 | ✅ | ✗ | — |
| Geography | 71 | ✅ | ✗ | — |
| Adjudication | 60 | ✅ | ✅ | — |
| Contestation | 51 | ✅ | ✅ | — |
| Shared | 30 | ✗ | ✅ | messaging/inbox/outbox |
| **Election** (sing.) | 29 | ✅ | ✅ | **0** |
| **Elections** (plur.) | 15 | ✗ | ✗ | **0** |
| Committee | 4 | ✗ | ✗ | — |
| Trust | 2 | ✗ | ✗ | — |
| Finance | 1 | ✗ | ✗ | — |

Directory existence clearly does not track context maturity: Membership is the largest folder and is outside deptrac; Election (singular) is small and inside it.

### 10.3 Dependency direction — the decisive test

```
app/Contexts/Elections/   ──imports──▶   app/Domain/Election/   (VoterSourceStrategy, 2 events)
app/Contexts/Elections/   ──imports──▶   app/Models/            (OrganisationUser, ElectionMembership, DeadLetterEntry)
app/Contexts/Elections/   ──imports──▶   app/Services/          (ElectionCacheService)

app/Domain/       ──imports──▶ app/Contexts/*    :  ZERO  (verified by grep)
app/Application/  ──imports──▶ app/Contexts/*    :  ZERO  (verified by grep)
app/Contexts/*    ──imports──▶ app/Contexts/Election(s)  :  ZERO
```

`Contexts/Elections` is strictly **downstream** of `Domain/Election`, and nothing is downstream of it except HTTP controllers. Under any reading of DDD, a downstream consumer that imports its neighbour's enum and domain events is **not** the authority for that neighbour's rules.

### 10.4 Language evidence

Two distinct vocabularies coexist:

| Concept | `Domain/Election` (live) | `Contexts/Election` singular (greenfield) |
|---|---|---|
| identity | `App\Models\Election` (Eloquent, tenant-scoped) | `ElectionId` VO + `Election` aggregate (tenant-free) |
| state | `ElectionLifecycleState` (12 cases) | none — no lifecycle concept at all |
| change | `Transition` + `ElectionConstitution` action | `applyDetermination()` + `CorrectionType` |
| events | `VotingOpened`, `ElectionApproved`, … | `ElectionCorrectionApplied` |

They do not overlap. That is consistent with §8.1: the greenfield context models *correction-in-response-to-adjudication*, not *lifecycle*.

---

## 11 · Authority map

| Invariant / capability | Current runtime authority | Layer | Classification | Evidence |
|---|---|---|---|---|
| What transitions exist; who may; what preconditions | `Domain/Election/Constitution/ElectionConstitution::RULES` | Domain | A | sole definition |
| Command transition permitted? | `Application/Election/Services/ConstitutionalTransitionGuard` | Application | A | sole precondition evaluator |
| Precondition `has_approved_candidates` | same, L188 | Application | A | read |
| Computed lifecycle state | `Application/Election/Services/ElectionLifecycleEngineImpl::getState()` | Application | A | DI-bound port impl |
| `canVote` / `canEdit` / … | same, `derivePermissions()` | Application | A | read |
| Consumption API | `Application/Election/Facades/ElectionLifecycle` | Application | A | all controllers/middleware |
| State column write | `Models/Election::transitionTo()` L1670 | Model | A | *"only place in codebase"* |
| Unauthorized write **detection** (not blocking — `STRICT_LEVEL = 1`, §7) | `Models/Election::setStateAttribute()` + `ElectionStateWriteContext` + `DeprecationPolicy` | Model + Application | A + C | read |
| Voting temporal window | `Services/ElectionClockService::isVotingOpen()` | Service | A | called by engine rule 5 |
| Nomination window | `Domain/Election/Policies/NominationWindowPolicy` | Domain | A | constructed by engine |
| Third-layer open-voting rules | `Models/Election::whyCannotOpenVoting()` | Model | A | called via `validateOpenVoting()` |
| Election-Only mode vocabulary + snapshot | `Domain/Election/Enum/VoterSourceStrategy` | Domain | A | sole definition |
| Mode selection at creation | `ElectionManagementController::store()` L163 | HTTP | A | sole production site |
| Voter eligibility port | `Contexts/Elections/Domain/Policies/VoterEligibilityPolicy` | Context Domain | A | DI-bound |
| Voter eligibility implementation | `Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService` | Context Infra | A | DI-bound |
| Election-only eligibility decision | `ElectionOnlyPolicy::decideForContext()` | Context Domain | A | injected + called |
| Voter assignment | `Contexts/Elections/Application/Handlers/*` | Context App | A | injected into controller |
| Eligible-voter *listing* | `App\Services\VoterEligibilityService::unassignedEligibleQuery()` | Service | A (duplicate) | own SQL, §13 |
| Voter import | `App\Services\VoterImportService` | Service | A | mode-branched |
| Determination → correction | `Contexts/Election/Domain/Election` (singular) | Context Domain | A | provider-registered, deptrac-governed |
| Election existence for the greenfield context | `LegacyElectionExistenceAdapter` → `elections` table | Context Infra | B (ACL) | read |
| Vote anonymity (no `user_id`) | schema + `ADR-T11`; `Contexts/Election` preserves, does not own | DB | A | ACL docblock |

---

## 12 · Duplicate authority map

| # | Rule | Competing implementations | Which executes? | Consistent? |
|---|---|---|---|---|
| 1 | **Approved candidate required for voting** | (a) `ElectionConstitution` precondition `has_approved_candidates` → guard L188 `candidacies()->where('status','approved')->exists()`; (b) `ElectionLifecycleEngineImpl::hasCandidatesApproved()` — same query; (c) `Election::whyCannotOpenVoting()` — `candidates_count === 0` **and** `pending_candidacies_count > 0` | **All three** on the command path (guard → then `validateTransitionRules`); **(b) only** on the computed path | (a)≡(b). **(c) DIFFERS**: it tests cached *total* candidates and *pending* count, not approved-existence. An election with 1 candidate whose status is `draft` passes (c)'s first test but fails (a)/(b). |
| 2 | **Current state** | cached: `lifecycleState()`, `getCurrentStateAttribute()` · computed: `currentState()`, `getEngineSnapshot()`, `state_info`, façade | both families live; `transitionTo()` uses **cached** for `from_state` and **computed** for the guard snapshot | can diverge by design (§3.2) |
| 3 | **Eligible-voter query** | (a) `EloquentVoterEligibilityQueryService::qualifyingSubsetElectionOnly()` / `…FullMembership()`; (b) `VoterEligibilityService::unassignedEligibleQuery()` | both — (a) for filtering known IDs, (b) for listing candidates for assignment | predicates look equivalent (active org-user; or active member + fees paid/exempt + voting rights + not expired) but are maintained in two files in two layers. Not proven equivalent by test. |
| 4 | **Transition validation** | `ConstitutionalTransitionGuard` (A) vs `TransitionMatrix` (E) vs `ElectionStateMachine` (D) | guard only | matrix disabled at `AppServiceProvider.php:366`; state machine has no production caller |
| 5 | **State vocabulary** | `ElectionLifecycleState` (12 cases, live) vs `ElectionState` (7 cases, dead) | `ElectionLifecycleState` | **disjoint** vocabularies; `ActivateElectionCommand` writes the dead one's `'administration'` |
| 6 | **Engine resolution** | interface binding vs direct concrete resolution | concrete, everywhere | same object; seam not load-bearing (§3.4) |
| 7 | **Election-Only eligibility decision** | `ElectionOnlyPolicy::decideForContext()` (live) vs `ElectionOnlyPolicy::isEligible()` (stub, returns `true`) | `decideForContext()` | the stub is permissive; dead today, hazardous if ever bound |

Duplicate code is not automatically defective. Rows 1(c), 5 and 7 are the ones where the duplicates are **semantically different**, and row 1(c) is the only one that touches EM-VOT-002.

---

## 13 · Election-Only capability map

| Capability | Current owner | Runtime path | EO-relevant? | Evidence | Status |
|---|---|---|---|---|---|
| Election creation | `ElectionManagementController::store()` + `Models/Election` | §9-A | **REQUIRED** — mode is snapshotted here | L163 | implemented |
| Election configuration | `ElectionSettingsController`, `ElectionManagementController` | HTTP → model | SHARED | routes | implemented |
| Admission / approval | `ElectionConstitution` + guard + `transitionTo` | §9-B | SHARED | constitution rows | implemented |
| Nomination | `Domain/Election/Policies/NominationWindowPolicy` + engine rule 7 | §9-F | SHARED — mode-independent | 0 mode refs | implemented |
| Candidate approval | `candidacies.status`; `complete_nomination` precondition | guard | SHARED | constitution L79 | implemented |
| **Lifecycle** | `ElectionConstitution` + `ElectionLifecycleEngineImpl` + `transitionTo` | §9-D/F | **REQUIRED** | §11 | implemented (hybrid, §3.2) |
| **Voting opening** | constitution `open_voting` + guard + `whyCannotOpenVoting` | §9-E | **REQUIRED** | §12 row 1 | implemented |
| **Voting state** | engine rule 5 (`isVotingOpen ∧ hasCandidatesApproved`) | §9-F | **REQUIRED** | read | implemented; fall-through open (§14) |
| **Voter assignment** | `Contexts/Elections/Application/Handlers/*` | §9-G | **REQUIRED** — mode-carrying | injected | implemented |
| **Voter eligibility** | `VoterEligibilityPolicy` → `EloquentVoterEligibilityQueryService` → `ElectionOnlyPolicy::decideForContext()` | §9-G | **REQUIRED** | DI binding | implemented; `isEligible()`/`qualifyingSubset()` on domain policies are dead stubs |
| **Voter import** | `App\Services\VoterImportService` | HTTP → service | **REQUIRED** — EO-specific parse/preview/import | L35-37, L194-372 | implemented |
| Voter verification | `Domain/Election/Security/VoterVerification/*` + `Application/Election/VoterVerification/*` | §9-H | SHARED | file presence | **UNKNOWN depth** (§16) |
| Vote casting | `VoteController` + `EnsureVotingActive` | §9-I | SHARED — mode-independent | 0 mode refs | implemented |
| Suspension | constitution `suspend`/`resume`; engine rule 1 | §9-D/F | SHARED | constitution | implemented (overlay, not a phase) |
| Counting | engine rule 4 | §9-F | SHARED | read | implemented |
| Results | `publish_results` + `ResultController` | §9-J | SHARED | constitution | implemented |
| Audit | `ElectionStateTransition`, `ElectionAuditLog`, per-voter log files | `transitionTo` L1658 | SHARED | read | implemented |
| Security / sovereignty | `Domain/Election/Security/*` (~60 files) + `Application/Election/Security/*` (~30) | overlays, trust policies | SHARED | file census | implemented (largest subsystem) |
| Membership | `Contexts/Membership` (495 files) | — | **NOT REQUIRED** for EO | mode derived *from* `uses_full_membership`; EO path queries `organisation_users`, never `members` | implemented |
| Credentials / codes | `CodeController`, `Code`/`ReceiptCode` models | HTTP | SHARED — mode-independent | 0 mode refs | implemented |
| Entitlement | — | — | **UNKNOWN** | no production `entitlement` authority found; an untracked pin test exists | **NOT ESTABLISHED** (§16) |

**Summary.** Election-Only mode's *entire* implemented footprint is: creation-time snapshot → voter eligibility → voter assignment → voter import → UI/telemetry vocabulary. Every other Election capability is mode-independent and shared.

---

## 14 · EM-VOT-002 — current authority map

The rule: *an election must not be `VotingActive` unless at least one **approved** candidate exists* (Election Manifesto §4a; SD-14 = YES).

Where it belongs **today**, by layer:

| Question | Answer (today, evidence-based) |
|---|---|
| **Business-rule home** | `app/Domain/Election/Constitution/ElectionConstitution::RULES['open_voting']['preconditions']` — the only place transitions and their preconditions are declared. |
| **Constitutional expression** | `'has_approved_candidates'` in that precondition list. Present at L99 (also L79 for `complete_nomination`). |
| **Command-path enforcement** | `ConstitutionalTransitionGuard::validatePreconditions()` L188 — `$election->candidacies()->where('status','approved')->exists()`. |
| **Computed-path enforcement** | `ElectionLifecycleEngineImpl::getState()` rule 5 — `isVotingWindowOpenNow() && hasCandidatesApproved()`. **Architecturally unavoidable**: no command guard can reach this path, because the engine derives state from facts without any transition occurring (§3.2). |
| **Application enforcement** | Not a separate layer. The façade (`canVote()`) and `EnsureVotingActive` both project the engine snapshot; they add no rule and must not. |
| **Test verification** | `tests/Unit/Application/Election/EmVot002OpenVotingPreconditionTest.php` (command) · `tests/Feature/Election/EmVot002ApprovedCandidateBeforeVotingTest.php` (computed) · plus `ElectionLifecycleEngineTest`, `ConstitutionalTransitionGuardTest`, `ElectionActivationTest`, `ElectionScenarioFactory`. |

**Comparison with Session 3's implementation (`f2c2cc4e`): the placement is CORRECT.** The rule text sits in the Domain constitution; both enforcement points sit in the layer that owns the corresponding path; nothing was added to a context that does not own the invariant. Had the rule been placed in `Contexts/Elections` — as the quoted analysis would have directed — it would have been in a downstream consumer with no reachability from either the guard or the engine.

**The open item is not placement — it is fall-through semantics.** Verified by reading `getState()`: when the voting window is open and no approved candidate exists, rule 5 declines and derivation falls through rules 6-12. For a fully set-up election (`administration_completed ∧ nomination_completed`, both voting dates set, `now ≥ voting_starts_at`, `approved_at` set, `setup_started_at` set) **every** remaining rule declines, and control reaches the terminal `throw new InvalidElectionStateException`. Because `canVote()` is computed from `getState()`, an exception — not a denial — propagates to `VoteController` and `EnsureVotingActive`.

This is consistent with `0822f333` ("the fall-through THROWS (4 rows regressed)") and with the in-code comment that names the gap honestly:

> *"When unmet, no substitute state is chosen here: derivation falls through to the existing rules below (fallback semantics are an open PO decision)."*

**This session neither resolves EM-OPEN-021 nor recommends a fallback state.** It records that the fall-through is reachable, that its consequence is an exception rather than a denial, and that the decision is a domain/PO decision — as Session 2 already qualified it (`217f2fe5`).

Detailed diff-level analysis of `f2c2cc4e` is already recorded in `docs/publicdigit/reviews/2026-08-13-em-vot-002-implementation-boundary-audit.md`; this section deliberately does not duplicate it.

---

## 15 · Architecture generations — proven vs assumed

| # | Generation | Proven status | Basis |
|---|---|---|---|
| 1 | `app/Models/*` — Eloquent + fat aggregate | **PROVEN active authority** | sole state-write site; 108 app / 315 test files; 18 database refs |
| 2 | `app/Domain/Election` + `app/Application/Election` — layered constitutional core | **PROVEN active authority** | DI binding; sole rule table; all controller/middleware reads |
| 3 | `app/Services/*` — service layer | **PROVEN active**, partly duplicating gen 4 | `VoterEligibilityService`, `VoterImportService`, `ElectionClockService` all live |
| 4 | `app/Contexts/Elections` (plural) — strangler slice | **PROVEN active for ONE capability**; **NOT a governed context** | DI-bound port + handlers; absent from deptrac; no own provider; Phase A/B docblocks |
| 5 | `app/Contexts/Election` (singular) + Contestation/Adjudication/Shared — governed greenfield Core | **PROVEN active for its own capability**; **PROVEN not to own lifecycle** | deptrac layers; provider in `config/app.php`; its own ACL docblock |
| — | *"the whole Election domain has migrated to `Contexts/`"* | **DISPROVEN** | §10.3 dependency direction; §8.1 ACL text; §2.3 DI binding |
| — | *"`Contexts/` is the target architecture for Election lifecycle"* | **PLAUSIBLE INTENTION, NOT A CURRENT FACT** | the Strangler-seam docblocks state the intention explicitly, but no ADR authorising a lifecycle migration was located, and no lifecycle code exists in either context |

**Statements this session refuses to make**, because evidence does not support them:
- that `app/Domain/Election` or `app/Application/Election` is "legacy"
- that `app/Models/Election.php` should be decomposed
- that `Contexts/Elections` (plural) should become the Election bounded context
- that the folder count should be reduced

And the prescription in the quoted report — *"ensure the authoritative ones (`Contexts/`) become the source of truth"* — should **not** enter architecture documentation. The defensible statement is:

> The repository contains multiple architectural generations and capability structures. Their current authority, runtime ownership and bounded-context boundaries are established in this document from dependencies and execution paths. Any migration or authority reassignment requires a separate, explicitly authorised architecture decision.

---

## 16 · Unknowns (with the exact evidence needed)

| # | Unknown | Evidence required to resolve |
|---|---|---|
| U-1 | **Fall-through semantics** when the voting window is open with zero approved candidates | A PO/domain decision (EM-OPEN-021), then a RED test. **Not an archaeology gap** — the code is fully understood; the *intent* is undecided. |
| U-2 | **Entitlement** authority for Election-Only | No production entitlement gate was found. An untracked `tests/Feature/Election/ElectionOnlyEntitlementPinTest.php` exists; read its assertions and locate the intended enforcement point, or confirm none exists. |
| ~~U-3~~ | ~~Results-event consumers~~ | **RESOLVED — see §16.1** |
| U-4 | **Voter-verification depth** (path H) | Full trace of `VerificationSession` + `VoterVerificationPolicy` + `VoterVerification` model to HTTP entry. Not required by EM-VOT-002. |
| ~~U-5~~ | ~~Runtime enforcement level~~ | **RESOLVED — `STRICT_LEVEL = 1`, hardcoded. See §7.** |
| U-6 | Whether `VoterEligibilityService::unassignedEligibleQuery()` and `qualifyingSubset*()` are **semantically equivalent** | A differential test over shared fixtures. Today equivalence is asserted by neither code nor test. |

Two of the six were cheap enough to close during this session (§16.1, §7). Of the rest: U-1 is a decision, U-6 is a test, U-2 and U-4 are genuine remaining archaeology. **None blocks EM-VOT-002**; U-1 blocks its *completion*.

### 16.1 · U-3 RESOLVED — duplicate results-publication vocabulary confirmed

Two classes model the same business fact, in two different generations, and **neither is wired end-to-end**:

| Class | Generation | Dispatched? | Listened to? |
|---|---|---|---|
| `Contexts\Elections\Domain\Events\ResultsPublishedEvent` | plural context | **YES** — `ElectionManagementController::publish()` L862 | **NO listener** anywhere |
| `Contexts\Elections\Domain\Events\ResultsUnpublishedEvent` | plural context | **YES** — `…::unpublish()` L889 | **NO listener** anywhere |
| `Domain\Election\Events\ResultsPublished` | `Domain/Election` | **NO** — imported at `Models/Election.php:29`, never constructed | n/a |

`app/Providers/EventServiceProvider.php` declares no `$listen` map for either. The dispatched pair is fire-and-forget: observable only through `Event::fake()` in `tests/Feature/Election/ResultsPublicationTest.php` and the two unit tests under `tests/Unit/Elections/Events/`.

**Classification:** the plural-context pair is **B** (live but consumer-less integration surface); `Domain\Election\Events\ResultsPublished` is **E** (declared, imported, never dispatched). Two names for one fact, in the two generations, is the same pattern as §12 row 5 — recorded, not repaired. Feeds recommendation 3.

---

## 17 · Risks

| # | Risk | Severity | Basis |
|---|---|---|---|
| R-1 | **Computed fall-through throws instead of denying.** A fully set-up election in its voting window with no approved candidate raises `InvalidElectionStateException` from `getState()`, propagating through `canVote()` into `VoteController` and `EnsureVotingActive`. | **HIGH** | read of `getState()`; corroborated by `0822f333` |
| R-2 | **`whyCannotOpenVoting()` encodes a different rule** (`candidates_count`, `pending_candidacies_count`) than the constitution (`approved` existence). Cached counters are projections. Divergence between the two is silently possible. | MEDIUM | §12 row 1 |
| R-3 | **Naming collision `Election` / `Elections`.** Two sibling directories, unrelated capabilities, different generations. It has already produced one incorrect architectural conclusion — the claim this session was commissioned to test. | MEDIUM | §8.2 |
| R-4 | **Cache/computed state divergence is structural.** The `state` column can hold a value the engine will not derive; `transitionTo()` mixes both in one operation. | MEDIUM | §3.2, §3.3 |
| R-5 | **`Contexts/Elections` is ungoverned.** No provider of its own, absent from deptrac, so no fitness function protects its boundary; it already imports `App\Models` and `App\Services` directly. | MEDIUM | §8.2 |
| R-6 | **Dead permissive stub.** `ElectionOnlyPolicy::isEligible()` returns `true` for any imported-registry mode. Harmless while unbound; a permissive default if ever bound to the port. | LOW-MEDIUM | §4.4 #7 |
| R-7 | **Interface seam not load-bearing.** Every site resolves `ElectionLifecycleEngineImpl` concretely, so the container binding cannot substitute the engine. | LOW | §3.4 |
| R-9 | ~~**The sovereignty boundary does not block.**~~ **DOWNGRADED — see §21.** `STRICT_LEVEL = 1` is compiled in, so unauthorized `state` writes are recorded and then allowed. **This is deliberate, governed staging**, not an unfinished rollout: `ADR_20260807_1500` (ACCEPTED) step 4 makes level-by-level escalation *"with zero violations"* the mechanism that **proves** the Option-B migration, gated on `PBDIGIT-59`. Residual risk is only that the machinery reads as enforcement to anyone who checks neither the constant nor the ADR. | ~~MEDIUM-HIGH~~ → **LOW** | §7 · §21 |
| R-10 | **Results-publication events have no consumers.** Both dispatched events are listener-less, and a third, parallel event class exists that is never dispatched. Anything assumed to happen on publication does not happen via events. | MEDIUM | §16.1 (U-3 resolved) |
| R-8 | **Dead vocabulary still writable.** `ActivateElectionCommand` writes `'administration'`, absent from `ElectionLifecycleState`; a later `lifecycleState()` would `ValueError`. Dev-only command. | LOW | §3.5 note |

---

## 18 · Recommendations

Sequenced. Each names its authority. **This session implements none of them.**

**Immediate — unblocks EM-VOT-002 (PO / domain authority)**

1. **Decide EM-OPEN-021: fall-through semantics** (R-1). The architecture question is settled; only the business intent is open. Options exist (a dedicated non-voting state; reuse `ReadyForVoting`; an explicit blocked variant) — **choosing among them is not engineering's call**, and this session deliberately does not recommend one. Once decided: Decision → RED → GREEN → certification, per the standing rule.

**Near-term — backlog items, outside every open story's scope**

2. **Reconcile `whyCannotOpenVoting()` with the constitution** (R-2). One rule, one evaluator. Needs its own story; it is not EM-VOT-002 scope.
3. **Resolve U-2 (entitlement)** before any Election-Only completion claim, and **dispose of the duplicate results-publication vocabulary** now that U-3 is resolved (§16.1): decide which event class is the real one, then wire or remove — a naming/ownership decision, not a cleanup.
3b. ~~Decide whether the sovereignty boundary is meant to enforce.~~ **WITHDRAWN — already decided.** `ADR_20260807_1500` step 4 governs the escalation and gates it on `PBDIGIT-59`. The only residual action is documentation: the constant carries no pointer to the ADR that governs it, which is why this session initially read it as an open question (§21).
4. **Record the `Election`/`Elections` collision as a governance finding** (R-3) — a naming/ownership decision for ARB, not a rename to be performed opportunistically.
5. **Bring `Contexts/Elections` under a fitness function or fold it in** (R-5) — an architecture decision, since deptrac encodes the *approved* model and adding a path changes that model.

**Documentation (this session's own follow-through)**

6. Replace the refuted claim wherever it was recorded, with the §15 formulation.
7. Add a developer guide for the hybrid command/computed lifecycle (§3.2) — the single most misunderstood property of this architecture, and the direct cause of this commission. Per the Definition-of-Done standing rule, this belongs under `developer_guide/<area>/`.

**Explicitly NOT recommended**

- No migration of `Domain/Election` or `Application/Election` into `Contexts/`.
- No decomposition of `app/Models/Election.php`.
- No folder-count reduction.
- No deletion of `TransitionMatrix` / `ElectionState` / `ElectionStateMachine` inside any Election-Only story — dead-code removal is separate, evidence-carrying work.

---

## 19 · What MUST NOT be changed yet

| Component | Why |
|---|---|
| `ElectionConstitution::RULES` | sole rule table; every guard decision and target-state mapping derives from it |
| `ElectionLifecycleEngineImpl::getState()` rule order | a 12-rule priority ladder with constitutional ordering; reordering silently changes outcomes for existing rows |
| `Election::transitionTo()` | the only state-write site; holds lock + transaction + audit ordering |
| `Election::setStateAttribute()` / `ElectionStateWriteContext` / `DeprecationPolicy::STRICT_LEVEL` | the sovereignty boundary. **Do not raise `STRICT_LEVEL` opportunistically**: at level 4 the barrier starts throwing, and §12 row 2 plus the `ActivateElectionCommand` path show writes exist today that would begin failing. Raising it is an authorised change with its own RED evidence, never a tidy-up. |
| The `state` column's demoted status | it is a compatibility cache by design; promoting it to truth would be an unapproved architecture change |
| `VoterSourceStrategy` case names/values | `elections.voter_source_strategy` is persisted and immutable per election; renames are a Phase-4 governance decision |
| `VoterEligibilityPolicy` signature | documented as a frozen Phase-A contract |
| `ElectionOnlyPolicy::decideForContext()` | live in the eligibility path |
| `Contexts/Election` (singular) domain + ACL seam | governed by deptrac, ARB rulings, ADR-T8/T11/T16, TP-1; forward-only and idempotent invariants |
| `TransitionMatrix`, `ElectionState`, `ElectionStateMachine` | dead/transitional, but removal is separate authorised work — not a side effect of Election-Only |
| The two untracked docs and the untracked pin test | other sessions' in-flight artifacts |

---

## 20 · Final verdict

> ## CURRENT ARCHITECTURE ESTABLISHED

Established with evidence for everything the Election-Only objective and EM-VOT-002 touch:

- **Lifecycle authority:** `Domain/Election` (rules, vocabulary, port) + `Application/Election` (engine, guard, façade) + `Models/Election` (aggregate, orchestration, sole state write) — proven by DI binding, call-site grep, and code read.
- **Election-Only authority:** mode defined in `Domain/Election/Enum/VoterSourceStrategy`; selected once at creation; enforced only across voter eligibility, assignment and import; eligibility decision owned by `Contexts/Elections`.
- **Bounded-context boundaries:** the approved model (`deptrac.yaml`, ARB/PB-007) is Contestation · Adjudication · Election *(singular)* · Shared. `Contexts/Elections` *(plural)* is an ungoverned single-capability strangler slice. Neither context owns the lifecycle, and the greenfield one says so in its own source.
- **EM-VOT-002 placement:** correct as implemented in `f2c2cc4e`.

Of the six unknowns raised, two were closed in-session (U-3 §16.1, U-5 §7); U-2 and U-4 are peripheral remaining archaeology; U-1 and U-6 are a decision and a test respectively, not missing archaeology. I am therefore not returning "PARTIALLY ESTABLISHED": the authority question this commission asked is answered.

Two findings that emerged from closing those unknowns are worth surfacing beyond their sections, because both are cases of machinery that reads stronger than it behaves: the **write barrier records but does not block** (`STRICT_LEVEL = 1`, R-9), and the **results-publication events have no listeners** (R-10).

**Answer to the commission's §7 question — "Is `app/Contexts/Elections` THE Election Context?"**

> **B — PARTIAL.** It owns exactly one Election capability: voter eligibility decision and voter assignment. It is not the Election bounded context, it is not the authority for Election rules, and it is strictly downstream of the code that is.

**Answer to §8 — "What does `app/Domain/Election` represent?"**

> **Current runtime authority.** It owns the constitutional rule table, the lifecycle port, the state vocabulary, the Election-Only mode concept, the domain events, the window policies, and the Security/sovereignty subsystem. Calling it "old DDD" would be factually wrong.

**Answer to §9 — "What does `app/Application/Election` represent?"**

> **The application layer of `app/Domain/Election`.** Not a facade over an older domain and not orchestration around `Contexts/*` — it holds zero references to `App\Contexts`. It implements the Domain's port and hosts the guard, façade, capability/security policies and monitoring, plus an active compatibility layer in `Deprecation/`.

---

## 21 · Addendum (2026-08-13, same session) — Level-1 evidence this report originally missed

While drafting the follow-up governance artifacts, Canonical Discovery surfaced two ADRs in `docs/publicdigit/adr/` that **this report did not cite**, and that outrank every evidence class it used:

| ADR | Status | Bearing on this report |
|---|---|---|
| `ADR_20260807_1500_Election_Lifecycle_Single_Source_Of_Truth` | **ACCEPTED** | **Corroborates §2–§3 at Level 1.** It names `ElectionLifecycle` (the facade over `ElectionLifecycleEngineImpl`) the single source of truth for election state, classes `status`/`is_active`/`state` as compatibility artifacts, and records the Product Owner's approval of **Option B** (complete migration, DoD = zero production readers). It also documents the *"three generations in one model"* and the platform's *"recurring migration failure (authority moves, consumers stay)"* — independently confirming §15. |
| `ADR_20260806_1620_Constitutional_Rule_Ownership_Migration` | PROPOSED | §6 records **"one authority per concern"** as an observation at **n=1** (votes-per-IP), explicitly **not promoted**, with a second independent occurrence as the promotion precondition (`ES-006.1`). §12 of this report is a candidate second occurrence. |

**Three corrections follow.**

1. **R-9 downgraded MEDIUM-HIGH → LOW.** `STRICT_LEVEL = 1` is not an ambiguous rollout state. `ADR_20260807_1500` step 4 makes level-by-level escalation *"with zero violations"* the mechanism that proves the migration, sequenced behind `PBDIGIT-59`. Recommendation 3b is withdrawn.
2. **§3.3's two read-path families are not merely an observation — they are named migration debt** under an accepted ADR with an approved completion strategy. The column readers are unmigrated consumers, not a competing design.
3. **§15's row** *"no ADR authorising a lifecycle migration was located"* **stands as written** — neither ADR authorises moving `Domain/Election` into `Contexts/`. But the adjacent implication that the lifecycle's authority was undeclared is **wrong**: it was declared at Level 1, and the declaration agrees with this report's Levels 2–7 conclusion.

**What this says about the method, not just the content.** This report's conclusions survive the omission because they happened to agree with the accepted ADR. That is luck, not rigour: a competent, evidence-disciplined investigation searched code, wiring, dependencies and declared architecture — and skipped the highest-ranked artifact class, because ADRs are spread across three directories under four naming conventions with no index of which capability each governs. **That is the deficiency, and it is now carried as `AMB-1`** in `ADR_20260813_1722`, where it is argued to meet the methodology-freeze exception ("unless PublicDigit implementation exposes a genuine deficiency"). Invoking that exception is ARB's call, not engineering's.

---

## 22 · Traceability

- **Commission:** Session 4 — Election Architecture Archaeology (read-only), 2026-08-13.
- **Refutes:** the claim *"`app/Contexts/Elections/Domain/` is authoritative for Election rules."*
- **Companion (not duplicated):** `docs/publicdigit/reviews/2026-08-13-em-vot-002-implementation-boundary-audit.md` — diff-level EM-VOT-002 scope audit.
- **Related commits:** `f2c2cc4e` (PBDIGIT-64, EM-VOT-002) · `0822f333` (fall-through throws) · `56ef03c7` (guard-bypass latent) · `9ee15cc6` (`transitionTo` review) · `217f2fe5` (EM-OPEN-021 qualified a domain decision) · `c9e0c225` (ticket authority matrix) · `d5efff2a` (current-state report).
- **Declared-architecture artifacts consulted:** `deptrac.yaml` (ARB/PB-007) · `config/app.php` (provider registry) · `app/Providers/AppServiceProvider.php` (DI bindings).
- **Constraints honoured:** no production code, tests, fixtures, namespaces, dependencies or configuration modified; no files moved, renamed or deleted; EM-OPEN-021 and EM-VOT-002 not resolved; no tactical DDD design introduced; no target architecture prescribed.
