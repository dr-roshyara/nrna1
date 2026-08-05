# WP-7 Implementation Guard Commission

**Date:** 2026-08-01 · **Role:** Senior Principal DDD Architect · **Commission:** is every WP-7 architectural constraint backed by an **executable** engineering mechanism? Enforceability only — no redesign, no implementation, no reopening.
**Repository Integrity Gate:** ✅ PASSED — working tree clean; `feature/pb003`, 15 ahead.

> ## RESULT — **NOT a clean bill. The commission found what it was created to find.**
>
> **The ARB's premise was correct: the constraints rest largely on discipline.** Of 11, **4 are executably enforced**, **7 are manual** (3 cheaply automatable by *existing* precedent patterns).
>
> **And enforceability verification surfaced a collision between two frozen decisions that no design review could have caught:**
>
> > ### ⛔ **G-1 — "Consume Adjudication's existing `AdjudicationDurations` port" and "place the service inside a bounded context" are NOT simultaneously satisfiable under the current Deptrac ruleset.**
>
> **This is not an architectural reopening.** Both decisions stand; what is unstated is *where the application service's code lives* — and that single unstated fact determines whether **any** gate covers the majority of WP-7's code.
>
> **Engineering readiness: CONDITIONAL. G-1 must be decided before slice 7A.**

---

## 1. Architectural Constraint Inventory (Phase 1)

The 11 constraints from the transition record, taken verbatim as the authoritative contract. **None added.**

| ID | Constraint | Source |
|---|---|---|
| C-1 | Never define, default, clamp or substitute a duration | AP-1 |
| C-2 | MAD keeps exactly one home | AP-2 |
| C-3 | EPW constructor takes **business values only** (no port/config/model/clock) | construction commission |
| C-4 | The VO validates itself; the service validates nothing | construction commission |
| C-5 | No aggregate, entity, domain service, repository, domain factory, domain event | pattern verification |
| C-6 | No context crossing | transition record |
| C-7 | Factory names state a **fact**, not a procedure | construction commission |
| C-8 | Fail closed on every unknown | AP-1 |
| C-9 | `--days` never overrides the invariant | transition record |
| C-10 | Do not change how audit evidence is **written** | transition record |
| C-11 | 7C needs an announcement owner | transition record |

## 2. Enforcement Matrix (Phase 2)

**First, the fact that governs the whole matrix — verified, not assumed:**

> **All three structural gates scan the same four paths and nothing else:** `app/Contexts/{Contestation,Adjudication,Election,Shared}`. Confirmed in `deptrac.yaml` (`paths:`), `phpstan-greenfield.neon` (`paths:`), and `GreenfieldCoreArchitectureTest::CONTEXTS`.
>
> **`app/Console/Commands/AuditCleanup.php` — where the plan places the guard — and `app/Helpers/` are covered by ZERO structural gates.** *(One narrow precedent proves a targeted test **can** reach into `app/Console`: `VoterSourceStrategyCallerEnforcementTest` names a console command file explicitly.)*

**Election is in-scope for all three gates** — verified: it joined `CONTEXTS` at PB-004 Step 4C. **So the EPW Value Object inherits real enforcement the moment it lands in `app/Contexts/Election/Domain/`.**

| ID | Enforcement mechanism | Type | Verified |
|---|---|---|---|
| **C-1** | *(none)* — no gate inspects for defaulting/clamping | **MANUAL** | **AP-1 was found by a human preservation review and was invisible to all four gates.** That is not a hypothesis; it is this project's recorded history |
| **C-2** | *(none)* — no gate asserts key uniqueness across configs | **MANUAL** | AP-2 likewise found by review |
| **C-3** | **Deptrac** `ElectionDomain: ~` (depends on **nothing** — blocks importing any port) · **`test_greenfield_domain_is_framework_free`** (`Illuminate\`, `Laravel\`, `Eloquent`, `Carbon\`) · **`test_greenfield_domain_has_no_infrastructure_imports`** · **PHPStan max** | ✅ **AUTOMATED** — *with one hole* | **Hole:** a **PSR `ClockInterface`** is neither in the forbidden-framework list nor covered by Deptrac (documented as an approved-external/uncovered target). A clock could be injected into the VO **undetected** |
| **C-4** | *"VO validates itself"* — no gate · *"service validates nothing"* — no gate | **MANUAL** | **Cheaply automatable:** `ConstitutionalAssertionsTest::test_capability_decision_construction_exclusive` is an exact precedent — it forbids `new CapabilityDecision` outside one designated class |
| **C-5** | **Domain events only:** `EventRegistryCompletenessTest` + `test_at_evt_001_event_ownership` would fire on a new unregistered/unowned event. **Aggregate · entity · domain service · repository · factory: no gate** | ⚠️ **PARTIAL** | events ✅ · the other five ❌ |
| **C-6** | **Deptrac** rulesets — `ElectionApplication: [ElectionDomain, Shared]` admits **no** other context | ✅ **AUTOMATED *if the code is inside a context*** · ❌ **NO COVERAGE outside** | **This is G-1** — see §5 |
| **C-7** | *(none)* | **MANUAL** | **Cheaply automatable:** `VocabularyProhibitionTest::test_layer3_no_authority_vocabulary` is an exact precedent — a forbidden-word list scanned over one directory |
| **C-8** | **The slice's own acceptance tests** (7A: missing/invalid ⇒ throws · 7B: absent anchor ⇒ open · 7C: unmappable folder ⇒ retained) | ✅ **AUTOMATED** (behavioural, arrives with RED) | already written as acceptance criteria in the plan |
| **C-9** | **7C behavioural test** — *"`--days` no longer overrides the invariant"* | ✅ **AUTOMATED** (arrives with RED) | in the plan's 7C test list |
| **C-10** | *(none)* — but the change would be a **visible diff** in one named file | **MANUAL** | `ElectionAuditService` is a single, reviewable file |
| **C-11** | **Not an engineering constraint** — release governance | **MANUAL, correctly** | — |

## 3. Manual Enforcement Assessment (Phase 3)

**Proportionality applied — I am not recommending automation for completeness.**

| ID | Why automation is absent | Feasible? | Manual acceptable? | Risk |
|---|---|---|---|---|
| **C-1** | Detecting "a business value was invented" needs intent, not syntax. But **the two known shapes are greppable**: a numeric literal in a duration path, and `max(`/`min(` clamping in an adapter | ⚠️ **partially** (heuristic) | ❌ **NO** | 🔴 **HIGH** — **this exact defect already occurred** (`max(1,$days)` in Infrastructure), passed every gate, and was caught only by a review that might not recur |
| **C-2** | No gate reads config keys | ✅ **YES — cheap and exact**: assert `maximum_adjudication_duration` appears in exactly one file under `config/` | ⚠️ marginal | 🟠 **MEDIUM** — **also already occurred once** (`60` in two homes) |
| **C-4** | Construction exclusivity isn't checked for this VO | ✅ **YES — an exact precedent exists** (§2) | ⚠️ marginal | 🟠 **MEDIUM** — bypassing the factory silently relocates validation |
| **C-5** | Structural absence is hard to assert without over-fitting | ⚠️ partially | ✅ **YES** | 🟢 **LOW** — a new aggregate/repository in a 3-slice change is **unmissable in review**, and each rejection is *recorded with its reason*, so it cannot read as an oversight |
| **C-7** | Naming semantics | ✅ YES (vocabulary precedent) | ✅ **YES** | 🟢 **LOW** — a name is the most visible thing in a diff |
| **C-10** | Would require pinning a file's behaviour | ✅ trivially (a diff check) — **but disproportionate** | ✅ **YES** | 🟢 **LOW** — one named file |
| **C-11** | Organisational, not technical | ❌ **and must not be** | ✅ **YES** | 🟢 LOW |

### Recommended automation — **exactly three**, each reusing an existing mechanism

| Priority | Constraint | Mechanism to reuse | Why proportional |
|---|---|---|---|
| 🔴 **1** | **C-1** | a targeted fitness test over the retention/adjudication config adapters forbidding numeric duration literals and `max(`/`min(` clamping | **The defect has already happened once.** Highest risk, and it is the constraint the constitution cares about most |
| 🟠 **2** | **C-2** | config-key uniqueness assertion | Also already happened once; exact, no false positives |
| 🟠 **3** | **C-4** | the `test_capability_decision_construction_exclusive` pattern, applied to `new EvidencePreservationWindow` | An existing, proven, single-purpose pattern |

**Explicitly NOT recommended:** automating **C-5** (over-fitting: a test asserting "no repository exists" ossifies the model against legitimate future change), **C-7** (a name is the most visible thing in a code review), **C-10** (disproportionate), **C-11** (not engineering's to enforce).

*(This commission does not build these. It identifies them.)*

## 4. Architectural Coverage Report (Phase 4)

| Category | Count | Automated | Manual |
|---|---|---|---|
| **Constraints** | **11** | **4** (C-3*, C-6†, C-8, C-9) + C-5 partially | **7** |
| — of which cheaply automatable by an existing pattern | 3 | — | C-1, C-2, C-4 |
| — of which manual is **acceptable** | 4 | — | C-5, C-7, C-10, C-11 |
| — of which manual is **insufficient** | **1** | — | 🔴 **C-1** |

`*` C-3 automated **except** a PSR clock. `†` C-6 automated **only if** the code sits inside a context — **see G-1**.

**Constraint → Enforcement → Verification traceability**

| Constraint | Enforcement | How it is verified |
|---|---|---|
| C-3 | Deptrac `ElectionDomain: ~` · framework-free · no-infra-imports · PHPStan max | `composer merge-gate` |
| C-6 | Deptrac per-context rulesets | `composer merge-gate` — **contingent on placement** |
| C-5 (events) | `EventRegistryCompletenessTest` · AT-EVT-001 | Architecture testsuite |
| C-8, C-9 | slice acceptance tests | PHPUnit, arriving with RED |
| C-1, C-2, C-4, C-5 (rest), C-7, C-10, C-11 | **review discipline** | **no executable verification today** |

## 5. Drift Detection Assessment (Phase 5)

| Drift scenario | Detected? | Gate |
|---|---|---|
| **Bypassing the VO** (`new EvidencePreservationWindow` inline, or arithmetic re-inlined into the service) | ❌ **NO** | none — *(C-4; precedent pattern available)* |
| **A second home for MAD** | ❌ **NO** | none — *(C-2; already happened once)* |
| **Creating a domain service / aggregate / repository in Election** | ❌ **NO** (structurally) — ✅ visible in review | *(C-5, accepted)* |
| **A new domain event** | ✅ **YES** | `EventRegistryCompletenessTest` · AT-EVT-001 |
| **Crossing bounded contexts** | ⚠️ **CONTINGENT** | Deptrac — **only if the code is inside `app/Contexts/`** |
| **A procedural factory name** (`calculate…`) | ❌ **NO** | none — *(C-7, accepted: visible in review)* |
| **A port/config/clock entering the VO constructor** | ✅ **YES**, except a **PSR clock** | Deptrac + framework-free |
| **A duration defaulted or clamped in an adapter** | ❌ **NO** | 🔴 **none — and this precise drift already occurred (AP-1)** |
| **Retention logic added to `AuditCleanup`/`app/Helpers`** | ❌ **NO** | **no gate scans those paths at all** |

### ⛔ G-1 — the collision, stated precisely

Two frozen decisions, each correct in isolation:

1. **"WP-7 consumes the *existing* `AdjudicationDurations` port"** — so MAD keeps one home (AP-2). *Verified present at `app/Contexts/Adjudication/Application/Port/AdjudicationDurations.php`, already `(?electionType, ?organisationId)`-scoped — exactly 7A's needed shape.*
2. **Deptrac's approved model:** `ElectionApplication: [ElectionDomain, Shared]`, and *"bounded contexts collaborate ONLY via events (TP-1) — no direct cross-context code dependency."*

**Therefore:** an application service placed in `app/Contexts/Election/Application/` that imports `App\Contexts\Adjudication\Application\Port\AdjudicationDurations` **would fail Deptrac.**

**And the plan's actual placement — the guard inside `AuditCleanup` (`app/Console/Commands/`) — sits *outside* every analysed path**, so it raises **no violation and receives no protection**. The constraint isn't broken; **it is unobserved**.

| Option (factual, **not a recommendation** — this is the ARB's / engineering's call) | Consequence |
|---|---|
| **(a)** Service outside `app/Contexts/` | Consistent with TP-1 and AP-2 · **but C-6 and C-3-style purity go unenforced for that code** |
| **(b)** Service inside `Election/Application` | Fully gated · **but Deptrac fails on the MAD import unless the approved model is extended** |
| **(c)** Extend the Deptrac model to admit the durations port as an approved dependency | Preserves both · **modifies the approved architecture model — requires ARB authority** |

**I am not choosing.** Each option touches a different authority, and choosing one here would be exactly the "ticket silently becoming architecture" the standing discipline forbids.

**This does not invalidate the freeze.** No architectural *decision* is missing; an **engineering placement fact** is. But it must be settled **before 7A**, because 7A creates the new retention port and therefore faces the same question.

## 6. DDD Compliance Review (Phase 6)

**Do the gates reinforce the domain model, or substitute for it?**

| Principle | Enforced? | Mechanism | Assessment |
|---|---|---|---|
| **Bounded-context ownership** | ✅ **inside contexts** · ❌ outside | Deptrac per-context rulesets | **Reinforces** — the ruleset is derived from the approved model, and `deptrac.yaml` says so explicitly: *"the tool verifies the architecture; the architecture never evolves because the tool guessed something."* **That is the correct direction of authority** |
| **Tactical responsibilities** | ⚠️ **partial** | domain purity ✅ · construction ❌ · orchestration ❌ | The **structural** boundary is enforced; the **responsibility allocation within it** is not |
| **Dependency direction** | ✅ | Deptrac hexagonal layers + `Domain: ~` | **Reinforces** — inward-only, verified |
| **Ubiquitous language** | ❌ for WP-7 | *(precedent exists but unapplied here)* | Accepted — naming is review-visible |
| **Policy ownership** (Q-2 owns the values) | ❌ **the weakest point** | the **port** makes ownership *visible*, but **no gate prevents a value being invented behind it** | 🔴 **This is C-1, and it is the constraint the constitution cares about most** |

**Verdict on alignment:** the gates **reinforce** the model where they reach — they encode approved boundaries, not tool-convenient ones, and the Domain-depends-on-nothing rule is exactly the DDD statement. **They do not replace the model.** The deficiency is **reach and granularity**, not direction.

## 7. Engineering Readiness Recommendation (Phase 7)

| Check | Status |
|---|---|
| All constraints have an enforcement mechanism | ❌ **NO** — 7 manual, of which **C-1 is insufficient** |
| Manual enforcement explicitly justified | ✅ **YES** — 4 accepted with reasons; 3 flagged for cheap automation |
| Drift can be detected | ⚠️ **PARTIALLY** — 2 of 9 scenarios detected; **the drift that already happened once is undetected** |
| Engineering gates align with DDD responsibilities | ✅ **YES** — derived from the approved model; correct direction of authority |
| Implementation can proceed with continuous protection | ❌ **NOT YET** — **G-1 undecided**, and most WP-7 code would land where no gate reaches |

> ### **RECOMMENDATION TO THE ARB — engineering readiness is CONDITIONAL**
>
> **I do not record the completion statement.** It would assert that all constraints are backed by enforcement, and **that is not true today.** Recording it would be the Governance Verification Drift this programme has already named once.
>
> **Before RED (in addition to the WP-6 programme gate):**
> 1. ⛔ **Decide G-1** — where the application service and retention port live, and which of (a)/(b)/(c) applies. *Owner: ARB (option c touches the approved model) or engineering (options a/b).* **Blocks 7A.**
> 2. 🔴 **Automate C-1** — the only constraint where manual enforcement is **insufficient**, and the only one whose defect has **already occurred**. *Recommend as part of 7A, where the adapter is written anyway.*
>
> **Recommended but not blocking:** C-2 and C-4 automation (both cheap, both reusing existing patterns, both guarding defects with precedent).
>
> **Explicitly accepted as manual:** C-5 · C-7 · C-10 · C-11.
>
> **Unchanged:** the architecture remains **frozen**. This commission found **no architectural defect** — it found that **enforcement reach is narrower than the constraint set**, which is an engineering finding with engineering owners.

---

**Traceability:** transition record §Implementation Constraints (the 11, taken verbatim) · verified in-repo: `composer.json` §`merge-gate` · `deptrac.yaml` (paths + rulesets + the TP-1 statement) · `phpstan-greenfield.neon` (paths) · `tests/Architecture/GreenfieldCoreArchitectureTest.php` (`CONTEXTS` incl. Election; `FORBIDDEN_FRAMEWORK_IMPORTS`; AT-EVT-001) · `ConstitutionalAssertionsTest::test_capability_decision_construction_exclusive` (construction-exclusivity precedent) · `VocabularyProhibitionTest::test_layer3_no_authority_vocabulary` (vocabulary precedent) · `EventRegistryCompletenessTest` · `VoterSourceStrategyCallerEnforcementTest` (precedent for gating an `app/Console` file) · `app/Contexts/Adjudication/Application/Port/AdjudicationDurations.php` · WP-6 findings **AP-1**/**AP-2** (both found by human review, invisible to all gates). **No code written; no gate created; no architecture redesigned; no governance modified; no decision made that belongs to another authority.**
