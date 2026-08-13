# Election Verification — Execution Plan

**Date:** 2026-08-08 · **Status:** **SLICE 1 AUTHORISED** by the Product Owner (investigation only; PO review gate before Slice 2). Slices 2–7 remain PROPOSED. · **Checkpoint:** `9a38c510`

> **Governing principle, adopted:** *"We are not trying to make Election tests green. We are trying to establish that the Election system is correct, that the tests actually verify that correctness, and that the authoritative Election Manifesto/Lifecycle is the source of every business decision that is supposed to depend on lifecycle."*
>
> **Slice 1 scope, as authorised:** inventory every Election test and map each to — lifecycle state · capability · business invariant · entry point/route · authoritative decision source · fixture · expected behaviour · actual behaviour · failure status · existing evidence · coverage gap. **Incorporate the already-proven classifications** from `PBDIGIT-48` and the four closed clusters rather than re-deriving them. **No code, no fixtures, no production changes, no legacy migration. Stop for PO review.**
**Implements:** `docs/publicdigit/backlog/full_discovery.md` (Product Owner's Full Discovery Plan, §1–19)
**Does not reopen:** `PBDIGIT-48` Option B · `PBDIGIT-59` · the four closed clusters

---

## Objective

Turn the Full Discovery Plan into executable slices **without re-deriving what is already evidenced**. The plan's own Definition of Done governs; this document is only *how* and *in what order*.

## What is already done — do not redo

| Plan section | Already satisfied by | Evidence |
|---|---|---|
| §2 Freeze the authoritative model | `ADR_20260807_1500_Election_Lifecycle_Single_Source_Of_Truth` | committed |
| §9 Legacy-field migration inventory | `PBDIGIT-48` §Completion Report — 46 sites, four buckets | committed |
| §3 Classification (partial) | 4 clusters closed on proven mechanism | `9b95469a`…`edd6b551` |
| §11 Already-voted exclusion | `PBDIGIT-62` — fail-open guard fixed + regression test | committed |

**Anchor Track B to `PBDIGIT-48`'s existing four-bucket classification.** Re-running that inventory from scratch would discard evidence and re-open a closed decision.

## Sequenced slices

### Slice 1 — Master Matrix *(AUTHORISED; investigation only)*

**Ordering is mandatory and must not be inverted:**

```
business invariant → domain meaning → authoritative authority → application capability
→ application authorization → interface/HTTP entry → persistence → test
```

**Determine test *intent* before interpreting its *failure*.** Never start from a PHPUnit message and reason backwards into a business explanation.

**Per-test mapping** — the 30 columns in the directive, plus one added by the Product Owner:

> **`Business Decision Ownership`** — `Domain` · `Application` · `Policy/Authorization` · `Infrastructure` · `Interface/Projection` · `Unknown`.
>
> **Why it matters:** several mechanisms can answer *"can this happen?"* while owning entirely different responsibilities — lifecycle answers *what state*, capability answers *what is legitimate in that state*, policy answers *is this actor permitted*, the use case *executes*, persistence *stores*, the UI *projects*. **This column is the foundation for Slice 5**: it turns the legacy question from *"does this reference `status`?"* into *"does this code make a decision that belongs to the Election domain authority, or merely transport, persist or display it?"*

**Non-equivalent legacy categories — never collapse:** field exists · fixture writes it · production reads it · **production decides from it** · persisted/displayed only · explicitly retained by decision.

**Evidence discipline:** separate OBSERVED FACT · INTERPRETATION · HYPOTHESIS · CONCLUSION. Where a mechanism was not measured, write **"Mechanism not yet established."** **"Undetermined" is a preferred outcome over a forced classification.**

**Carry forward, do not re-derive:** `PBDIGIT-48`, `ElectionCreationTest`, clusters 8c, 8d, 8a import-preview. Reference the evidence; reproducing the investigation destroys traceability.

**Coverage is a business question**, not a test count: for each capability — allowed path · forbidden path · correct state · correct actor · **server-side enforcement** · negative case · persistence where it matters.

**Cluster 6 is intentional migration evidence, not defects.**

#### ⛔ Slice 1 is investigation and classification only — NOT remediation

**Slice 1 is investigation and classification only, not remediation.** Findings such as defective fixtures, production defects, legacy consumers, missing coverage, or architectural smells **must be recorded** in the matrix and the appropriate finding registers, but **must not be repaired during Slice 1**. No code, test, fixture, production, or legacy-migration changes are authorised by Slice 1. **Remediation begins only in a later authorised slice, after the Product Owner review gate.**

**The prohibition applies even — especially — when the repair looks obvious.** "This fixture clearly uses the old `status` field" is a *finding*, not a licence.

**Operating rule when something actionable is discovered:**

```
DISCOVER → UNDERSTAND BUSINESS INTENT → IDENTIFY DECISION OWNER
        → MEASURE ACTUAL MECHANISM → CLASSIFY → RECORD FINDING → STOP

                       ⛔ NO REPAIR IN SLICE 1
```

**Worked example of the required output shape:**

| Field | Value |
|---|---|
| Observation | fixture writes legacy `status` |
| Business intent | test intends `SetupAdministration` |
| Authoritative authority | `ElectionLifecycle` |
| Actual derived state | `draft` |
| Classification | fixture does not establish the intended state |
| Impact | test does not exercise the intended business scenario |
| Candidate remediation | recorded for a later authorised slice |
| **Action** | **NO CHANGE** |

**The Slice 1 contract, stated once:**

> **Slice 1 answers:** what does the Election test estate claim to verify, what business decision does each test protect, who owns that decision, what mechanism actually executes it, and where are the verification gaps?
>
> **Slice 1 does not answer by changing code:** how should we fix those gaps? — that belongs to subsequent authorised slices.

*(This is a programme-level separation of concerns, mirroring the separation of responsibilities the matrix is mapping inside the system.)*

#### ⚠️ The columns are not the point — the lens is

**30 columns must not become a bureaucratic exercise.** A row is valuable only if it answers:

> **What business rule is this test supposed to protect, who owns that decision, and does the implementation actually exercise that authority?**

| The naive question | The architectural question |
|---|---|
| Is the election in Counting? | What does **`ElectionLifecycle` derive**? |
| Can the operation occur? | What **capability/invariant** governs it? |
| May this officer perform it? | What **authorization/policy** governs the actor? |
| Can the HTTP request reach it? | What **application/interface gates** exist? |
| Does the operation happen? | Which **use case/service** executes it? |
| Is the result stored? | Which **persistence responsibility** owns storage? |
| Does the UI show it? | Is the UI **projecting** authoritative information? |
| Is `status` involved? | Is it **making a business decision**, or merely transported/stored/displayed? |

**A column filled in without answering its architectural question is noise.** Prefer fewer rows reasoned through to thirty columns completed mechanically.

**Gate:** produce matrix, coverage summary, failure classification, legacy-observation register, architectural findings, gaps, open questions · run the 18-item self-audit · then state **"SLICE 1 COMPLETE — AWAITING PRODUCT OWNER REVIEW"** and stop.

### Slice 2 — Finish failure classification (~95 unclassified + GracePeriod)
Evidence-first protocol, unchanged: intent → actual lifecycle state → trace → rejecting boundary → classify. **Repair only after classification.**
**First item:** `ElectionGracePeriodUITest` — measure the request-time Gate subject before hypothesising.

### Slice 3 — Fixture rehabilitation (Track A)
Repair fixtures that assert a lifecycle state they never created. **Each repaired fixture asserts its resulting `ElectionLifecycle` state before asserting behaviour** — the guard that closed 8d and 8a.

### Slice 4 — Production defects, individually authorised
Each one: proven mechanism → smallest repair → RED→GREEN evidence → separate commit. **No batching.**

### Slice 5 — Track B: migrate remaining legacy business-decision consumers
From `PBDIGIT-48` Bucket 1 only. **Bucket 2 (demo) stays until `PBDIGIT-59`; Bucket 3 (display) stays until field retirement.**

### Slice 6 — New coverage (§6, §7, §8, §10, §12–15)
**Largest and least-defined scope.** Lifecycle state matrix · capability matrix · server-side enforcement · journeys A–G · entry resolution · voting integrity · audit determinism.
**Must be authorised as its own work package** — it is building, not repairing.

### Slice 7 — Sweep, mutation/negative testing (§17–18), closure review (§19)

## Risks I would flag before starting

1. **Slice 6 is a scope expansion, not a cleanup.** The plan's 8–10 day estimate looks optimistic against measured throughput: four clusters (~41 failures) consumed one long session, and ~95 remain unclassified. **Recommend estimating Slices 2–4 from Slice 1's matrix rather than in advance.**
2. **Green is not the target.** The plan says this; the failure modes proved it — `withoutMiddleware()` hid a production 500, and cluster 6's red tests *are* `PBDIGIT-48`'s completion signal.
3. **Recognition is not classification.** Four plausible hypotheses dissolved under measurement this cycle. Every classification needs a measured mechanism.
4. **`full_discovery.md` is untracked.** 28 KB of governing methodology outside version control. **Committing it is a PO governance decision**; placement derives from `php scripts/doc-placement.php`.

## Definition of Done

The plan's own (§"The final Definition of Done"), which this plan does not restate or weaken. **Green tests are one criterion among several.**

## Outstanding obligation carried in

Developer guides for `developer_guide/http/` and `developer_guide/models/` — production code changed in both areas and the guides were deferred, not written.

## Traceability

`docs/publicdigit/backlog/full_discovery.md` · `PBDIGIT-48` · `PBDIGIT-58` · `PBDIGIT-62` · `ADR_20260807_1500` · handover in `.claude/sessions/2026-08-07.md` §HANDOVER

---

## SLICE 1 — STEP 1 (scope determination) — STARTED 2026-08-08

**The plan requires scope be determined before any matrix is built** (*"Do not produce a partial matrix and call it complete"*). That step ran first, and its result materially changes the programme's shape.

### 🔴 The estate is ~4.5× larger than the working set measured all session

| Measure | Value |
|---|---|
| Test files referencing Election | **491** |
| Test methods in those files | **~3,688** |
| Files in the 5 suites measured this session | **180** |
| Tests in those suites | **826** (668 passed / 146 → 105 failed / 12 incomplete) |

**The `146 → 105` trajectory describes a subset, not the Election estate.** Every failure figure quoted in this programme so far — including the handover — is scoped to those five suites.

**Distribution beyond the measured set:** `tests/Feature` (74) · `tests/Unit/Domain/Election/Security` (35) · `tests/Feature/Demo` (18) · `tests/Unit/Models` (17) · `tests/Unit/Application/Election/Security` (17) · `tests/Unit/Services` (8) · `tests/Feature/Membership` (8) — none of which were in the measured suites.

### What this means for Slice 1

1. **The 30-column matrix over ~3,688 tests is not a single-session artifact.** Scoping it as such would guarantee the partial-but-authoritative outcome the plan forbids.
2. **A scope decision is required from the Product Owner before matrix construction**, e.g.:
   - **(a)** the 826-test constitutional core already measured — coherent, bounded, already partly classified; or
   - **(b)** all 491 files — complete, but a multi-session programme needing its own slicing; or
   - **(c)** a capability-first cut: matrix the tests covering the constitutional invariants and journeys, and inventory the rest by file without per-test mapping.
3. **"491 files reference Election" is an upper bound, not a consumer count** — the same distinction that reduced 46 legacy sites to a much smaller true set. Many will reference Election incidentally.

### Status

**SLICE 1 STEP 1 COMPLETE — SCOPE DECISION REQUIRED BEFORE MATRIX CONSTRUCTION.**
**Slice 1 is NOT complete.** No matrix rows have been produced. No code, test or fixture changed.

### SCOPE DEFINITION — mechanical pre-filter only (2026-08-08)

**This is NOT a scope classification.** It is a signal-strength pre-filter to make the ~491-file inventory tractable. **No file has been placed in categories A–E**, and directory names were not used as criteria.

| Signal | Files |
|---|---|
| References `election` (population) | **491** |
| Constructs an Election (`Election::factory` / `::create`) | **220** |
| Touches `ElectionOfficer` / `ElectionMembership` | **68** |
| **Touches `ElectionLifecycle` — the authority** | **61** |
| Touches `VoterSourceStrategy` / `VoterEligibility` | **21** |
| **Never touches the Election model or lifecycle at all** | **197** |

#### ⚠️ How this must NOT be used

**"Never touches the Election model" ≠ excluded.** A test can exercise an Election business decision entirely through HTTP, a controller, a policy or a service without naming the model — the `ElectionGracePeriodUITest` 403 and the `VoterImportController` phase rule are both of that shape. **Category D requires evidence of incidental reference, not absence of a class name.**

**"Touches `ElectionLifecycle`" ≠ constitutional core.** A test may reference the authority only to build a fixture.

**Legitimate use:** allocating investigation effort — the 61 lifecycle-touching and 21 eligibility-touching files are the densest place to *start* evidence-gathering, not the answer to where the boundary lies.

**Cross-context relationships still to be established** (the Product Owner's named examples): Membership → voter eligibility · Security → voter/ballot integrity · Application services → lifecycle operations · Models → persistence of authoritative decisions · HTTP → enforcement vs projection.

**Status: SCOPE DEFINITION STARTED — pre-filter only. Categories A–E not assigned. No scope decision made or implied.**

#### Cross-context relationships — first two ESTABLISHED (evidence, not inference)

**1. Membership → Election voter eligibility — CONFIRMED participant**

`FullMembershipPolicy::decideForContext()` `:60-61`:
```php
&& in_array($context->membershipStatus, ['active'], true)
&& in_array($context->feesStatus, ['paid', 'exempt'], true);
```
**Member state directly determines whether a voter may be assigned to an election.** Membership is therefore **inside** the Election verification boundary as a supporting dependency — **Category B at minimum**, and **Category A** for any test targeting that eligibility rule. `tests/Feature/Membership` (8 files) **must not be excluded on directory name.**

**2. Election Security → the voting decision — CONFIRMED participant**

`VoteController:39,59` injects `TrustPolicyEvaluator` as a constructor dependency. **The Election security layer participates in the vote path**, so `tests/Unit/Domain/Election/Security` (35) and `tests/Unit/Application/Election/Security` (17) are **not** outside the boundary by virtue of being "Security". Where they protect ballot-integrity invariants they are **Category A candidates**.

> **52 security files + 8 membership files — 60 in total — would have been wrongly excluded by directory-name reasoning.** That is 12% of the population, and it validates the Product Owner's instruction that location must never determine scope.

**Still to establish:** Application services → lifecycle operations · Models → persistence of authoritative decisions · HTTP → enforcement versus projection · Demo → whether demo semantics constitute Election business decisions (`PBDIGIT-59`-adjacent).

**Status unchanged: SCOPE DEFINITION IN PROGRESS.** Categories A–E not assigned per file; no scope decision made.

#### ⚠️ Correction to the previous entry (self-audit)

The phrase *"60 files that directory-name reasoning would have wrongly excluded"* **overstated the finding**. What is established is a **relationship**, not a scope verdict. `FullMembershipPolicy` reading `feesStatus` proves Membership *participates* in an Election decision; it says nothing about whether any given Membership test *verifies* that participation.

**The correct chain — and the guard against swinging from under- to over-inclusion:**

```
relationship established → identify which tests exercise that relationship → classify those tests
```

**NOT:** *context X relates to Election → all of context X's tests are in scope.*

#### 3. HTTP → enforcement vs projection — ESTABLISHED, and the two are different owners

`ElectionVotingController`:

```php
:51   $canVote = $isEligible && !$hasVoted && $lifecycle->canVote();   // PROJECTION
:53   if (! $lifecycle->canVote()) { … }                              // ENFORCEMENT
:123  if (! $lifecycle->canVote()) { … }                              // ENFORCEMENT
```

**The HTTP layer does both, and they are not the same decision.**

| | Value | Owner |
|---|---|---|
| `$canVote` at `:51` | `isEligible && !hasVoted && lifecycle->canVote()` — a **three-input composition** | **Application** (composition), not Domain |
| `lifecycle->canVote()` at `:53`/`:123` | the constitutional capability alone | **Domain** |

> **The UI's `canVote` is not the lifecycle's `canVote()`.** A test asserting the projected flag verifies an *application composition*; a test asserting the guard verifies a *domain capability*. **Mapping both to `Domain` would erase the distinction the ownership column exists to capture** — and would hide that voter eligibility and already-voted status are folded in at the application layer.

**Consequence for scope:** an HTTP test is in scope when it exercises enforcement or the composition; it is not in scope merely because it renders a page containing a capability flag.

**Relationships: 3 established (Membership · Security · HTTP) · 3 outstanding** — Services → lifecycle operations · Models → persistence of authoritative decisions · Demo (`PBDIGIT-59`-adjacent).

#### 4. Application Services → lifecycle operations — ESTABLISHED, and the answer is *no such layer*

**Traced the actual mutation path rather than inferring from imports.** Every caller of `transitionTo()` in `app/`:

| Caller | Layer |
|---|---|
| `ElectionManagementController` `:235, :849, :906, :937` | **HTTP controller** |
| `Election` model `:1274, :1286, :1300, :1326` | **Domain model** (internal) |

**No application service, use case, or handler mutates Election lifecycle state.** The path is **HTTP → Domain model directly**, with no application layer in between.

**This is not an absence of application layer generally** — `AssignVoterHandler` exists in `Contexts/Elections/Application/Handlers` and *does* mediate voter assignment. So the Election application layer exists for **some** operations and **not** for lifecycle transitions.

| Operation | Application layer? | Decision owner for the transition |
|---|---|---|
| Voter assignment | **yes** — `AssignVoterHandler` | Application (orchestration) + Domain (policy) |
| **Lifecycle transition** | **no** | **Domain model, invoked directly by HTTP** |

**Consequences for scope:**
1. **There are no lifecycle-transition service tests to include, because no service performs them.** Tests exercising transitions are necessarily **controller/HTTP tests or model/domain tests** — searching `tests/Unit/Services` for lifecycle verification would find nothing, and its absence would prove nothing.
2. **`Business Decision Ownership` for transition tests is `Domain`, with the caveat that the *invocation* is `Interface`.** The constitution (`ElectionConstitution` allowed-states/roles) is consulted inside `transitionTo()`, not by the controller.
3. **Recorded, not repaired:** a controller calling a domain-model mutator directly is an architectural observation. Whether that is a boundary weakness is **not** a Slice 1 question. **NO CHANGE.**

**Relationships: 4 established (Membership · Security · HTTP · Services) · 2 outstanding** — Models → persistence of authoritative decisions · Demo (`PBDIGIT-59`-adjacent).

#### 🔴 RELATIONSHIP 4 — CORRECTED. The finding above is WRONG and must not be reused as evidence.

**The conclusion "no application-service lifecycle layer exists" was reached by tracing `transitionTo()` *callers* and stopping at the first apparent domain boundary. `transitionTo()` was itself a caller.** Measuring what it does:

```php
$toState = ElectionConstitution::getTargetStateForAction($transition->action);
// ── 1-2. Validate & Authorize: Delegate to ConstitutionalTransitionGuard ──
$guard = app(ConstitutionalTransitionGuard::class);
$freshElection->validateTransitionRules($transition);
```

**`ConstitutionalTransitionGuard` lives in `app/Application/Election/Services/` and participates in every lifecycle transition.** An Application-layer decision mechanism therefore *does* exist — it is invoked *from inside the model* rather than sitting between controller and model.

**The real path is not `HTTP → Domain`. It is `HTTP → Domain → Application guard → Domain`.**

**Ownership is distributed, not singular:**

| Business concern | Owner | Mechanism |
|---|---|---|
| What state does this action target? | **Domain** | `ElectionConstitution::getTargetStateForAction()` |
| Is the actor authorised, is execution valid here? | **Application** | `ConstitutionalTransitionGuard` |
| Do the business transition rules permit it? | **Domain** | `validateTransitionRules()` |
| Who invokes the operation? | **Interface** | controller |

> **A single lifecycle test can legitimately cross multiple owners.** This is the clearest evidence yet for why `Business Decision Ownership` cannot be a single value assigned by inspection.

**Scope consequence — and why this correction was urgent:** the superseded finding stated that no service performs lifecycle work, which would have **excluded `ConstitutionalTransitionGuard` tests from the Election verification scope**. They are lifecycle-authorization tests.

**Still not established (do not assume):** what the guard *actually decides* versus what `validateTransitionRules()` decides; whether they overlap; and which concrete tests exercise each. **Mechanism not yet established.**

**Status: Relationship 4 is INCOMPLETE/CORRECTED — treat as a corrected hypothesis, not as evidence. Relationships established: 3 (Membership · Security · HTTP). Outstanding: 3 — Services/lifecycle (re-open), Models → persistence, Demo.**

**Methodological rule, now fifth occurrence:** *never infer decision ownership from the first class or method encountered in the call path — trace the decision itself.* Applied "caller ≠ owner" to the controller and then failed to apply it to the model.

#### 4 (RE-OPENED) · Application guard vs Domain validation — **ESTABLISHED, and they overlap**

**The corrected hypothesis is now measured.** The three questions left open — *what does the guard decide, what does `validateTransitionRules()` decide, do they overlap* — are answered from the code, not inferred.

**Execution order (`Election::transitionTo`, `app/Models/Election.php:1614`):**

```
:1641  getTargetStateForAction()            Domain — what state does this action target?
:1649  if (!$transition->isSystemTriggered())
:1650      $guard->assertAllowed(...)        Application — CONDITIONAL
:1655  $freshElection->validateTransitionRules()   Domain — ALWAYS
```

**Each mechanism's decision:**

| | `ConstitutionalTransitionGuard` (Application) | `validateTransitionRules()` (Domain model) |
|---|---|---|
| Runs | **only when NOT system-triggered** (`:1649`) | **always** |
| Decides | action exists · action legal **in this state** · **actor holds a required role** · declared preconditions met | per-action **readiness of this election's own data**, dispatched by naming convention (`open_voting` → `validateOpenVoting`) |
| Rule content owned by | `ElectionConstitution` — the guard owns **no rule**; its own contribution is **actor identity** (`Auth::user()`) | the **model**, in hand-written methods (`whyCannotOpenVoting()` …) |
| Failure | `InvalidTransitionException` | `\DomainException` / `\InvalidArgumentException` |

> **The guard answers "is this action constitutionally legal for this actor from this state?" The model answers "is this election's data ready?" Those are different questions — but their *answers* are not disjoint.**

##### 🔴 Finding 4a — two mechanisms enforce the same rules, with different exceptions

`ElectionConstitution::RULES['open_voting']['preconditions'] = ['voting_window_defined', 'timezone_set']`, and `whyCannotOpenVoting()` checks **both again**:

| Business rule | Guard | Model |
|---|---|---|
| Timezone set | `'timezone_set' => !empty($election->timezone)` | `if (!$this->timezone) return 'Timezone must be set…'` |
| Voting window defined | `voting_starts_at !== null && voting_ends_at !== null` | `if (!$this->voting_starts_at \|\| !$this->voting_ends_at)` |

**Consequence for the matrix:** for a non-system `open_voting`, the guard runs first, so **the model's duplicate checks are unreachable** — and a test asserting `DomainException` for a missing timezone would be asserting a branch that cannot execute on that path. **Exception type silently binds a test to a mechanism.**

##### 🔴 Finding 4b — the constitution does not hold all preconditions

`whyCannotOpenVoting()` enforces **three rules the constitution never declares**: `nomination_completed`, `candidates_count === 0`, `pending_candidacies_count > 0`.

> **`ElectionConstitution::RULES[...]['preconditions']` is not the complete set of preconditions for an action.** A reader treating it as authoritative would be wrong.

Note also a **data-source divergence** on the same concept: the guard's `has_approved_candidates` **queries** `candidacies` where status = approved; the model uses the **counter columns** `candidates_count` / `pending_candidacies_count`. **If a counter is stale the two mechanisms disagree** — recorded, not investigated.

##### 🔴 Finding 4c — system-triggered transitions bypass constitutional validation entirely

`if (!$transition->isSystemTriggered())` means a system transition receives **no state-legality check, no role check and no constitutional-precondition check**. Only the model's data-readiness rules apply.

**Whether that is correct is a business question, not a Slice 1 verdict** — system actions may legitimately be trusted. **What is established is that two different validation regimes exist depending on the trigger**, and `ConstitutionalTransitionGuardSystemRoleBypassTest` exists, so the behaviour is deliberate rather than accidental.

##### Tests that exercise each — the classification input

| Mechanism | Tests |
|---|---|
| **Guard** (Application) | `Unit/Application/Election/ConstitutionalTransitionGuardTest` · `…GuardPreconditionsTest` · `…GuardSystemRoleBypassTest` · `Feature/Election/ElectionTransitionToMethodTest` · `Feature/Election/VotingButtonsStateMachineTest` |
| **Model validation** (Domain) | `Feature/ElectionStateMachineTest` · `Feature/Election/StateMachineTransitionAuditTest` · `Feature/Election/VoterImportStateGateTest` · `Feature/Election/ConstitutionalVotingProtectionTest` · `Feature/Console/ProcessElectionAutoTransitionsTest` |
| **Constitution as data** | `Unit/Domain/Election/ElectionConstitutionTest` · `Unit/Application/Election/LifecycleCapabilityBaselinePolicyTest` · `Feature/Election/StateMachine/CurrentBehaviorTest` |

**Ownership consequence:** a lifecycle test's `Business Decision Ownership` **cannot be read from its directory**. `tests/Unit/Application/…GuardPreconditionsTest` verifies preconditions whose **content is Domain-owned** (`ElectionConstitution`) through an **Application mechanism**. **Owner and mechanism are separate columns, and this is the clearest case so far.**

##### What remains NOT established

* Whether any test asserts the **unreachable** model-side duplicate (4a) — that requires reading the tests, which is Step 2.
* Whether the counter columns ever go stale in practice (4b).
* Whether the system bypass (4c) is exercised by any path other than `ProcessElectionAutoTransitions`.

**Recorded, not repaired. No code, test or fixture changed.**

**Status: Relationship 4 ESTABLISHED (re-opened and completed). Relationships established: 4 (Membership · Security · HTTP · Services/lifecycle). Outstanding: 2 — Models → persistence of authoritative decisions · Demo (`PBDIGIT-59`-adjacent).**

---

### ⛔ STANDING SLICE 1 DISCIPLINE — added 2026-08-08 after Relationship 4

> **Do not refactor duplicate business rules merely because duplication has been discovered during the Master Matrix. Discovery establishes ownership and inconsistency; it does not authorize consolidation.**

**Specifically forbidden as a Slice 1 response to Finding 4a/4b:** *"the same rule is in two places, therefore move it into `ElectionConstitution`."* The two mechanisms answer **different conceptual questions** — *is this action constitutionally legal for this actor from this state?* versus *is this election's data ready?* — and collapsing them would erase that distinction before anyone has decided which rules belong to the **constitutional contract** and which are **aggregate invariants**. **That is a DDD ownership question, not a refactoring question.**

**The investigation answers these ten, in order. Only the tenth authorises change, and it is not engineering's to take:**

| # | Question |
|---|---|
| 1 | What business invariant exists? |
| 2 | Who owns it? |
| 3 | Where is it currently represented? |
| 4 | Which mechanism enforces it? |
| 5 | Are multiple mechanisms enforcing it? |
| 6 | Do they have identical semantics? |
| 7 | Can one mechanism bypass another? |
| 8 | What happens when they disagree? |
| 9 | Which tests actually verify the invariant? |
| **10** | **Only after PO / architecture review: is consolidation required?** |

**Q8 carries its own evidentiary rule, established by Finding 4b:** *potential* inconsistency is an architectural finding; *actual* inconsistency requires evidence. **Recording that two sources could disagree is discovery. Claiming they do disagree requires a measurement.**

#### 5 · Models → persistence of authoritative decisions — **ESTABLISHED**

**Incorporates `PBDIGIT-48`'s proven classification rather than re-deriving it** (three generations of representation; the lifecycle declared SSOT in `ADR_20260807_1500`). What was **not** established there, and is established here, is the **mechanical relationship between a decision and what it persists**.

##### What a successful transition writes (`Election::transitionTo`, `app/Models/Election.php`)

| Line | Write | Nature |
|---|---|---|
| `:1658` | `ElectionStateTransition::create(['from_state','to_state', …])` | **the durable record of the decision itself** |
| `:1670` | `$this->updateQuietly(['state' => $toState])` | **a cache** — and `updateQuietly` **bypasses Eloquent events**, so nothing can observe it |

##### What the authority reads — and it is not that column

`ElectionLifecycleEngineImpl` states its own contract:

> *"**CRITICAL: Derives state ONLY from business facts. State column is compatibility cache, not truth.**"*

`getState()` walks a **12-step constitutional priority order** (suspended → archived → results published → counting → voting active → … → draft), deriving from dates, flags and counts. **It does not read `elections.state`.**

```
decision ──▶ ElectionStateTransition   (history — the only durable record OF the decision)
        └──▶ elections.state           (cache — written quietly, read by legacy consumers)

authority ──▶ derive from business facts   (never reads the cache)
```

##### 🔴 Finding 5a — state can change with no decision and no audit row

**Because state is a pure function of business facts, editing a fact changes the state.** A `voting_ends_at` edit can move an election from `VotingActive` to a later state **without any transition being invoked**, so:

* no `ElectionStateTransition` row is written — **the audit log records transitions, not state changes**;
* no guard runs — `ConstitutionalTransitionGuard` is only reached through `transitionTo()` (Relationship 4);
* `elections.state` still holds the old value.

> **The constitutional guard protects the *transition operation*, not the *state*.** Anything that edits the underlying facts changes the state without passing any of it.

**This is recorded as a property of the design, not as a defect** — a derived model behaves this way by definition. **Whether every fact-edit path *should* be constitutionally guarded is a business question**, and it is the same shape as Finding 4c (two authorization regimes).

##### 🔴 Finding 5b — the cache is known to diverge, and reconciliation is manual

`PBDIGIT-48` established that `app:backfill-election-state` exists **to detect and repair `state` divergence** and that **no scheduler invokes it**. Combined with 5a, divergence is not hypothetical but *expected*: the cache is written only by transitions, while the truth moves with the facts.

**Consistent with `PBDIGIT-47`** (routing read the stale `status` column) and `PBDIGIT-59` (lifecycle reads `voting_*`, legacy queries read `start_date`/`end_date`). **No new claim is made about how often divergence occurs — that would need a measurement** (Q8 evidentiary rule).

##### Test consequence — three groups verifying three different things

| Group | Verifies | Examples |
|---|---|---|
| Asserts the **column** | the **cache**, not the decision | `ElectionActivationTest` · `ElectionStateMachineCapabilitiesTest` · `ResultsPublicationTest` · `ElectionStateMachineProjectionTest` · `ElectionDashboardCapabilitiesTest` |
| Asserts the **snapshot** (`ElectionLifecycle::of(…)->snapshot()`) | the **derivation** — the authority | `ElectionStateMachineTest` · `ElectionManagementConstitutionalTest` · `ConstitutionalVotingProtectionTest` · `ElectionPolicyStateAwareTest` · `VoterEligibilityTest` |
| Asserts `ElectionStateTransition` | the **decision history** | `StateMachineTransitionAuditTest` · `ElectionStateTransitionMigrationTest` · `CapacityApprovalTest` |

> **A test that sets `['state' => 'voting_active']` in a fixture and then asserts behaviour is priming the cache, not the truth** — and the behaviour it observes may come from either, depending on which the code under test consults. **This is the single most likely cause of "passes for the wrong reason" in the Election estate**, and it must be checked per test in Step 2 rather than assumed.

##### What remains NOT established

* **Which** of the column-asserting tests depend on the cache being read back, versus merely setting up a fixture — per-test reading, Step 2.
* Whether any production path other than `transitionTo()` writes `elections.state`.
* How often cache and derivation actually diverge — **needs a measurement, not an inference.**

**Recorded, not repaired. No code, test or fixture changed.**

**Status: Relationship 5 ESTABLISHED. Relationships established: 5 (Membership · Security · HTTP · Services/lifecycle · Models/persistence). Outstanding: 1 — Demo (`PBDIGIT-59`-adjacent).**

#### 5 (CORRECTED) · Relationship 5 report issued — four claims withdrawn

**Full report: [`docs/publicdigit/reviews/2026-08-08-relationship-5-models-to-persistence.md`](../publicdigit/reviews/2026-08-08-relationship-5-models-to-persistence.md).**

**The interim entry above overstated four things. They are corrected in the report, and the corrections are the substance rather than footnotes:**

| # | Withdrawn | Replaced by |
|---|---|---|
| **C-1** | `ElectionStateTransition` is *"the only durable record **of the decision**"* | an **immutable, attributed record of executed transition operations that nothing consumes** — *"decision"* is a business concept I named from the record's shape without establishing its meaning or a single reader |
| **C-2** | *"state can change with **no decision**"* | **the evaluated lifecycle state changes because an authoritative business fact changed** — no decision is implied or missing |
| **C-3** | current state and transition history treated as one authority | **four separate questions** (A current · B history · C executed transition · D evidence-of-decision), whose equivalence is not established |
| **C-4** | *"setting `['state' => …]` primes the cache, not the truth"* as a general rule | **intent determines correctness** — persistence, compatibility and legacy-consumer tests legitimately arrange that column |

**C-2 mattered most:** left standing it invites *"every lifecycle-state change must have a transition record"*, which would convert an approved **derived-state model** into a **state-machine persistence requirement**.

**New evidence the report adds beyond the interim entry:**

* **Two production paths write `elections.state` outside `transitionTo()`** — `ActivateElectionCommand:30` (no guard, no history) and `BackfillElectionState:67`. *(This answers an open item the interim entry left.)*
* **`election_state_transitions` has no production reader** — every occurrence in `app/` is a creation, a return type, or its own immutability hook.
* **Observed transition records do not cover all state-writing paths:** `ActivateElectionCommand` writes `elections.state` and produces no transition record. **Whether that is a gap depends on BD-1** — *"incomplete"* would judge against a contract nobody has established.
* **Event emission is inconsistent between writers** of the same column (`updateQuietly` vs `update`) — recorded, **not** called a defect.

**Three business decisions are raised, none taken** — chiefly **BD-1: is `election_state_transitions` intended as constitutional audit evidence?** The same fact means opposite things depending on the answer: if it is evidence, the missing records are a governance gap; if it is implementation history, they are harmless. **BD-1 should be answered before Step 2**, because it decides whether the audit tests verify a business invariant or an implementation detail.

**Status: Relationship 5 COMPLETE. Relationships established: 5. Outstanding: 1 — Demo (`PBDIGIT-59`-adjacent). Awaiting Product Owner review; not proceeding to Relationship 6 or Step 2 automatically.**

---

### ⛔ STANDING RULE — added 2026-08-08 after the Relationship 5 review

> **Business meaning precedes persistence meaning. Persistence meaning precedes implementation judgement.**

```
what business value? → what business invariant? → who owns the decision?
→ what is authoritative? → how is it persisted? → how is it consumed?
→ how is it verified? →  ONLY THEN:  is the implementation correct?
```

**Corollary, and the one this programme keeps needing:**

> **Technical structure can provide evidence about a business concept. It cannot silently become the business concept.**

**Two language rules follow, and they are binding on every relationship report:**

1. **Never describe coverage as *"incomplete"*, *"missing"* or *"a gap"* before the business contract requiring that coverage is established.** Say **"observed records do not cover all X paths"**, then say **whether that matters is undetermined pending <decision>**. *(Violated once already: `R5-6` called transition coverage "incomplete by construction" and classified it OBSERVED FACT — conflating the coverage, which is a fact, with its significance, which is not.)*
2. **Never call a persisted record a *"decision record"*, *"audit log"* or *"domain event"* from its shape.** `election_state_transitions` is immutable and carries `actor_id`/`reason`/`trigger` — **accountability-shaped, and that is evidence about intent, not proof of it.** Until **BD-1** is answered its business meaning is **UNRESOLVED**.

**Where a business decision is open, record both branches with their consequences** rather than the decision in the abstract — see the BD-1 A/B table in the Relationship 5 report. **The same observations mean opposite things under each branch; that is what makes the decision necessary and what stops engineering from making it by default.**

---

### ⛔ META-GOVERNANCE RULE — binding on ALL discovery in this programme (2026-08-08)

> **Coverage is descriptive. Completeness is normative.**

```
OBSERVED            only transitionTo() produces transition records
NOT YET JUSTIFIED   "the record is incomplete"
WHY                 "complete" presupposes a contract stating which
                    operations MUST produce records — and no such
                    contract has been established (BD-1)
```

**This generalises the language rule above rather than repeating it.** The language rule forbade certain *words*; this states *why*: **counting what a mechanism covers is measurement; judging that coverage sufficient or deficient is a claim about an obligation.** One is available from the repository; the other never is.

**The test to apply, and it is short:** *does this sentence describe what exists, or assert what ought to exist?* If the latter, name the contract that requires it. **If the contract cannot be named, the sentence is a business decision wearing a finding's clothes.**

**Worked instance — `R5-6`, and it is the reason this rule exists.** *"Transition history is incomplete by construction"* was classified **OBSERVED FACT**. The coverage was fact; *"incomplete"* was a verdict against an obligation nobody had established. **The same observation — `ActivateElectionCommand` produces no transition record — is a governance gap under BD-1 Branch A and entirely harmless under Branch B.** A finding whose meaning inverts depending on an unmade decision is not yet a finding.

---

### 🏛️ THE PERMANENT HIERARCHY — the order every investigation reasons in

```
                 BUSINESS VALUE
                       │
               BUSINESS INVARIANT
                       │
              DECISION OWNERSHIP
                       │
                DOMAIN AUTHORITY
                       │
            APPLICATION CAPABILITY
                       │
                 AUTHORIZATION
                       │
                  PERSISTENCE
                       │
                  PROJECTION
                       │
                 VERIFICATION
                       │
           IMPLEMENTATION JUDGEMENT
```

**`IMPLEMENTATION JUDGEMENT` is deliberately last.** It prevents the failure mode this programme has hit repeatedly: **starting from *"what does the code do?"* and then inventing the business model from the code.** Every correction recorded in this plan — Relationship 4's *"caller ≠ owner"*, Relationship 5's four withdrawn claims, `R5-6`'s *"incomplete"* — is the same mistake at a different altitude: **reading a business meaning off a technical structure.**

**Reversing the order is the defect, not the conclusion it produces.** A conclusion reached bottom-up may happen to be correct; it is still unsupported, because the evidence for a business claim cannot come from an implementation detail.

**Not filed as a KnowledgeOS candidate, deliberately.** Both rules are repository-independent and would pass the four promotion tests — **but three candidates have already been filed from this programme, and the distillation principle warns that KnowledgeOS should be *the result of* successful engineering, not something engineering goes looking for** (`docs/pks/2026-08-05-knowledgeos-distillation-principle-candidate.md`). **They are recorded here as binding programme rules. If a second, independent programme needs them, that is the occurrence that justifies filing.**

#### 6 · Demo → do demo semantics constitute Election business decisions? — **ESTABLISHED: yes**

**Full report: [`docs/publicdigit/reviews/2026-08-08-relationship-6-demo-semantics.md`](../publicdigit/reviews/2026-08-08-relationship-6-demo-semantics.md).**

**Scope taken from plan line 227, not from the relationship's name:** *whether demo semantics constitute Election business decisions*. **Not** a review of how demo mode works or whether it is correct.

**Answer: yes — and the split is clean.**

| | |
|---|---|
| **Constitutional lifecycle** | 🟢 **demo-agnostic** — no demo branching in `ElectionConstitution`, `ElectionLifecycleEngineImpl` or `ConstitutionalTransitionGuard`. **A demo fixture is a faithful substitute for lifecycle verification** |
| **Capabilities** | 🔴 **demo changes business behaviour** — four `abort_if(type === 'demo', 404)` exclusions (candidacy review · management · application · voter import), two `$forceNew` repeatability branches, one votes-per-IP exemption |
| **Where those decisions live** | **entirely the Interface layer.** `elections.type` is domain *data*; **no domain or application artifact expresses what a demo may do differently** |
| **Persistence** | three regimes — real → `votes` · private demo → `demo_votes` · **public demo → the session, not the database** (`PublicDemoController:325`). **The Product Owner's public/private rule is implemented as stated** *(precisely: the vote is not persisted; a `PublicDemoSession` row is)* |

**Sharpest observation — `BD-4`:** `PBDIGIT-45` established that **the Constitution owns votes-per-IP**. The demo exemption is expressed by **which controller calls a helper** (`838817bd`), so **a rule the Constitution owns is switched off by code with no constitutional standing.** **Whether that is a defect is not established** — the exemption may be exactly the intended rule.

**⚠️ Supersedes an earlier entry in this programme.** The `PBDIGIT-00` walk recorded the demo path calling `check_ip_address` and being blocked by it. **True then, false now** — `838817bd` removed it under `PBDIGIT-39`. **Superseded, not wrong**; recorded so the entries are not read as contradictory.

**Method note worth keeping:** a name-based scan reported demo branching in the lifecycle engine that does not exist — the matches were the word **"democratic"**. Excluded rather than counted.

**Three new business decisions recorded, none taken:** **BD-4** (is the votes-per-IP exemption constitutional?) · **BD-5** (is *demo* a domain concept or an interface concern?) · **BD-6** (are the four exclusions one business rule or four?). **BD-1/2/3 untouched.**

**Step 2 consequence:** demo fixtures are safe for lifecycle invariants and **not** safe for the four capability behaviours. Which of `tests/Feature/Demo`'s 18 tests fall on each side is **UNDETERMINED** and requires per-test reading.

**Status: Relationship 6 COMPLETE. 🏁 ALL SIX RELATIONSHIPS ESTABLISHED — the Slice 1 Step 1 relationship map is closed. Step 2 (Master Matrix classification) NOT started; awaiting Product Owner review.**

---

## SLICE 1 — STEP 2 (Master Matrix) — **BLOCKED ON THE SCOPE GATE THIS PLAN SET**

**Report: [`docs/publicdigit/reviews/2026-08-08-master-matrix-schema-and-calibration.md`](../publicdigit/reviews/2026-08-08-master-matrix-schema-and-calibration.md).**

**Step 2 was commissioned and was not started.** Step 1 closed with *"SCOPE DECISION REQUIRED BEFORE MATRIX CONSTRUCTION"* (lines 174, 182), offering three options. **No such decision exists** — verified against this plan, `CONTEXT.md` and the session logs. **Choosing one silently would take a programme decision and produce the "partial matrix called complete" outcome this plan forbids.**

**Delivered instead — both scope-independent:**

1. **The 30-dimension matrix schema**, with an **evidence rule per column**. Three columns carry the programme's hard-won distinctions and must never be merged: **#15** ownership ≠ location · **#17** persistence role · **#27/28** coverage ≠ completeness.
2. **A two-row calibration** on `ConstitutionalTransitionGuardPreconditionsTest` — the file Relationship 4 named as the sharpest ownership case.

**What the calibration showed, and it justifies the intent-first rule empirically:**

| | |
|---|---|
| Row 1 `…use_cached_columns` | **By location and name, a constitutional authorization test. By intent, an N+1 performance probe** — it counts SQL and asserts 0, and **explicitly swallows the authorization outcome** (`catch (\Exception $e) { // May fail for role check }`). **Business invariant: none identified.** A test that discards the authorization result cannot be evidence about authorization |
| Row 2 `…checks_correct_table` | A **candidate business-invariant** test, blocked on *which store defines committee membership* — **the same shape as `PBDIGIT-49`** (two homes, no recorded authority) |

> **Two tests, one file, one class under test — one has no business invariant, the other is blocked on an unresolved data-authority question. No file-level or directory-level rule could have separated them.**

**Cost, measured rather than estimated:** two rows from one 136-line file; ~14 of 30 columns confidently populated, **~9 legitimately `Unknown`**, and **~5 unfillable without executing the test**. **Option (b) at ~3,688 rows is a multi-session programme** — as this plan already predicted.

### 🔴 Two decisions now gate Step 2

| # | Decision |
|---|---|
| **SD-1** | **Scope: (a) 826 constitutional core · (b) all ~3,688 · (c) capability-first cut** — the unmet Step 1 prerequisite |
| **SD-2** | **Static reading, executed, or both?** Columns 19–21 (actual behaviour · result · failure classification) and part of 23 **cannot be filled without running the tests.** **This was not among the three original options and should be**, because it changes the cost of every row |

**Also surfaced, not investigated: `BD-7` (candidate)** — which store defines committee membership, `election_officers` or `election_memberships`?

**Status: SLICE 1 STEP 2 NOT STARTED — BLOCKED ON SD-1 AND SD-2. Schema and calibration delivered. No matrix rows beyond the two calibration rows. No test executed, no code, test or fixture changed. BD-1…BD-6 untouched.**

---

### ⛔ TWO PROGRAMME INVARIANTS — added 2026-08-08 after the Step 2 review

#### 1 · Blockers are `PROGRAMME` or `SUBSET`, and a subset blocker names its subset

**`BD-1` was recorded as *"should precede Step 2"*, which reads as a programme blocker. It is not one on present evidence.** It changes how **history/audit tests** are classified; **nothing establishes that rows outside that subset depend on it.**

> **Do not convert *"important decision"* into *"global blocker"* without proof.** It is the same error class as promoting coverage to completeness, and it has the same effect: **it stops work that the evidence does not require stopping.**

**If `BD-1` is to gate matrix construction, the dependency must be demonstrated first:** *which tests actually depend on the meaning of `election_state_transitions`?* **That dependency analysis is itself evidence** — and it is cheap, because Relationship 5 already found the candidate set (`StateMachineTransitionAuditTest`, `ElectionStateTransitionMigrationTest`, `CapacityApprovalTest`).

**Current classification:**

| Blocker | Class |
|---|---|
| **`SD-1`** scope · **`SD-2`** evidence depth | 🔴 **PROGRAMME** — both gate Step 2 in full |
| **`BD-1`** | **SUBSET** — history/audit tests, pending a dependency analysis |
| `BD-2` · `BD-3` · `BD-4` · `BD-5` · `BD-6` · `BD-7` | **SUBSET** or **not yet classified** — none demonstrated to gate the programme |

#### 2 · Never report the scope of the programme as the scope of the current evidence

**Every report and handover states what was *actually examined*, never what the programme *covers*.**

**The precedent this prevents:** the `146 → 105` failure trajectory described **826 tests in five suites** and was carried through the programme in language that could be read as the Election estate — which is **~3,688 tests across ~491 files**, roughly 4.5× larger. **A subset's numbers quoted without their denominator become the whole estate's numbers within two hand-offs.**

**Applied form:** *"Scope actually examined: three relationship investigations plus one 136-line test file — **not** 826 and **not** ~3,688."*

**These two rules are recorded here and nowhere else.** Per the Product Owner's direction, **no further methodology layer is to be added unless a concrete problem arises that the existing rules cannot handle.**

#### `BD-1` dependency analysis — **SUBSET BLOCKER CONFIRMED, and it is narrower than the correction assumed**

**Report: [`docs/publicdigit/reviews/2026-08-08-bd1-dependency-analysis.md`](../publicdigit/reviews/2026-08-08-bd1-dependency-analysis.md).** Authorised as a subset investigation independent of `SD-1`/`SD-2`.

**The candidate set was incomplete.** Relationship 5 named **three** files; the reference set is **eight** (69 test-shaped methods) — out of ~491 files / ~3,688 methods referencing Election. **All eight assert on the record; none merely imports it.**

**The dependency is narrower than "blocks classification of history tests":**

> **`BD-1` changes no test's pass/fail.** Every one passes or fails identically under either branch — the record is written the same way. **What changes is what a passing test *means*:** matrix **#5 Business invariant** (*"every governed transition is attributable"* vs **None identified**) and **#26 Verification strength** (business invariant vs technical). **Two columns. No others.**

| | |
|---|---|
| **6 files** fully dependent | assert *"a transition produces a record"* |
| **1 file** dependent in one test | `ElectionStateTransitionMigrationTest::test_has_no_updated_at_column` — immutability is a business invariant under A, a persistence choice under B. Its other three tests are schema mechanics |
| **1 file** independent | `ElectionStateTransitionModelTest` — UUID PK, timestamps flag, metadata cast: persistence mechanics under either branch |

**Consequence: `BD-1` does not block matrix construction at all.** Rows for these files can be built now with **#5 and #26 marked `Pending BD-1`**. **The earlier framing — *"BD-1 should precede Step 2"* — overstated a two-column dependency in one subset as a gate on the whole step.**

**Method correction worth carrying into matrix construction:** a first count returned **0** for two files, because they use PHPUnit's `#[Test]` attribute rather than a `test_` prefix. **The corrected pattern raised the total from 49 to 69.** **Any matrix built with a `test_`-prefix scan will silently undercount.**

**Still not established:** per-method dependency (69 is a file-total) · whether any of the eight currently pass (**no test executed**) · indirect dependencies via helpers (searched by class and table name only).

**Status: `BD-1` = SUBSET BLOCKER over 7 of 8 referencing files, affecting matrix columns #5 and #26. NOT a programme blocker. `SD-1` and `SD-2` remain the only programme blockers.**

---

## 🔴 GOVERNANCE FINDING — this programme is being carried under a ticket that disclaims it

**Raised 2026-08-08 by reading `PBDIGIT-48`'s story before continuing its work, as the session commission required.**

### The evidence

| Source | Says |
|---|---|
| **`PBDIGIT-48` story, Status** | *"✅ **IMPLEMENTATION COMPLETE & PO-APPROVED 2026-08-07** … **Remains OPEN for field retirement only**, sequenced behind `PBDIGIT-59`"* |
| **`PBDIGIT-48` story, Completion** | *"✅ **Met.** … **No further discovery belongs here**"* |
| **This plan, header** | *"**Implements:** `docs/publicdigit/backlog/full_discovery.md` (Product Owner's Full Discovery Plan)"* · *"**Does not reopen:** `PBDIGIT-48` Option B"* |
| **The commits** | **every commit of this programme carries `(PBDIGIT-48)`** — including all ten made this session |

**OBSERVED FACT.** The programme is **entirely discovery**; the ticket it is filed under says **no further discovery belongs there**; and this plan's own header says it implements a *different* authority and **does not reopen** that ticket.

**OBSERVED FACT.** `docs/publicdigit/backlog/full_discovery.md` — the authority this plan implements — **has no ID, no story header, and is not indexed in `backlog/README.md`.** It is a pasted Product Owner directive saved to a file.

### Why this matters, stated as consequence rather than blame

1. 🔴 **False traceability.** The repository's own convention: *"**Never attach a product story ID to work that story did not cause** — forcing an ID where none is true produces false traceability, which is worse than an unlabelled chore commit."* **~20 commits do exactly that**, and I added ten of them this session without checking.
2. **`PBDIGIT-48` reads as COMPLETE while accumulating open decisions that are not its** — `SD-1`, `SD-2`, `BD-1`…`BD-7`. Anyone reading the ticket sees *"Completion ✅ Met"*; anyone reading the log sees an active programme. **Both are true, which is the defect.**
3. **The programme's actual authority is unfindable.** A fresh session told *"continue `PBDIGIT-48`"* reads a closed ticket; the governing document is an unindexed file with no title.

### What is NOT claimed

* **Not** that any investigation was wrong — the six relationships, the schema, the calibration and the `BD-1` analysis stand on their own evidence.
* **Not** that `PBDIGIT-48`'s own closure is wrong — its field-retirement remainder is correctly sequenced behind `PBDIGIT-59`.
* **Not** that history should be rewritten. **Commit history is a record, not a draft.**

### `SD-3` — BUSINESS / GOVERNANCE DECISION REQUIRED

> **Under which identity does the Election Verification & Closure Programme run?**

| Option | Consequence |
|---|---|
| **(i)** Mint a new `PBDIGIT-nn` for the programme; index `full_discovery.md` under it | Cleanest. Future commits carry a true ID; `PBDIGIT-48` returns to meaning what its story says |
| **(ii)** Formally re-open and re-scope `PBDIGIT-48` to include the programme | Makes the existing labels true retrospectively — **but contradicts *"no further discovery belongs here"*, which the Product Owner approved** |
| **(iii)** Leave it | Every future commit deepens the false traceability |

**Engineering must not mint the identity.** ID assignment is a backlog governance act (*"never reuse a number — an ID names one thing permanently"*), and choosing between (i), (ii) and (iii) decides what `PBDIGIT-48` *means*.

**Recorded, not corrected. No commit re-labelled, no history rewritten, no ID minted.**

**Status: `SD-3` added. `SD-1` and `SD-2` remain the programme blockers for Step 2; `SD-3` blocks nothing technically — it governs how the work is filed.**

---

## 📌 PRESERVATION MANIFEST — what survives `SD-3`, and one correction to the accepted status

**`SD-3` is accepted as a governance finding. All programme work is PAUSED pending the Product Owner's decision on programme identity.** This manifest exists for one reason: **`SD-3` changes where the work is filed, not whether it happened.** Without it, resolving the identity risks a fresh programme starting from zero.

> **We are not restarting Election discovery. We are changing its governance home while preserving its evidence and continuing from the established checkpoint.**

### ⚠️ One factual correction to the accepted status

The accepted summary records *"Relationships 1–5 established · 1 outstanding — Demo."* **Relationship 6 (Demo) was completed earlier the same session** — commit `736a2f90`, report `docs/publicdigit/reviews/2026-08-08-relationship-6-demo-semantics.md`, and this plan line 609 reads *"ALL SIX RELATIONSHIPS ESTABLISHED."*

**Recorded because it changes the inheritance, not to score a point:** the next identity inherits a **closed relationship map**, not a map with one relationship outstanding.

### Status — stated precisely, because *"the Election discovery is complete"* would be false

| Area | Status |
|---|---|
| Relationships 1–6 · relationship map | ✅ **CLOSED** |
| Business Decision register `BD-1`…`BD-7` | ✅ established · ⬜ **decisions open** |
| Decision-ownership model · evidence protocol · programme rules | ✅ established |
| Master Matrix **definition** (30 dimensions + evidence rule per column) | ✅ established |
| Master Matrix **execution** | 🔴 **not started** — gated by `SD-1`/`SD-2` |
| Election test-estate classification · coverage · completeness | 🔴 not started |
| Legacy-consumer audit as a programme phase | 🔴 not started |
| Runtime Election-2026 journey | 🔴 **separate programme**, not executed |
| **Programme identity** | 🔴 **`SD-3` — unresolved** |

### What the resolved identity inherits — pointers, not restatements

| # | Inherited | Where |
|---|---|---|
| 1 | Relationships 1–6 | plan §Relationship entries · `reviews/2026-08-08-relationship-{5,6}-*.md` |
| 2 | `BD-1`…`BD-6`, `BD-7` candidate, **and `BD-1`'s dependency analysis** (subset blocker, 7 of 8 files, two matrix columns) | `reviews/2026-08-08-bd1-dependency-analysis.md` |
| 3 | `SD-1` scope · `SD-2` evidence depth — **the two programme blockers** | plan §Step 2 |
| 4 | Master Matrix schema + calibration + measured cost | `reviews/2026-08-08-master-matrix-schema-and-calibration.md` |
| 5 | Programme rules — *coverage is descriptive, completeness is normative* · *business meaning precedes persistence meaning* · the permanent hierarchy · `PROGRAMME` vs `SUBSET` blockers · **never report programme scope as evidence scope** | plan §standing rules |
| 6 | `PBDIGIT-48`'s own findings — **as historical evidence, not as work to redo** | `PBDIGIT-48` · `ADR_20260807_1500` |
| 7 | The four closed clusters and `PBDIGIT-58`/`59`/`62` results | as already recorded |

### The three programmes this finding separates — **recorded as a proposal, not adopted**

| | Answers |
|---|---|
| **A · Full Discovery** | *What is the Election system supposed to mean, and where does each business decision belong?* — substantially progressed |
| **B · Verification & Closure** | *Does the implementation and test estate establish that the intended system is correct?* — Master Matrix and failure classification live here |
| **C · Runtime Journey** | *Can a real actor perform the business journey end to end?* — organisation → Election 2026 → chief → voters → candidate → voting → results |

**Their separation is `SD-3`'s substance:** whether these are one identity or three is part of the decision, **and engineering is not making it.**

**No further programme work is authorised until `SD-3` is decided. Historical commits and evidence remain intact — nothing re-labelled, nothing rewritten, no ID minted.**

---

## SLICE 1 STEP 2 — first act: establish the matrix's row count. **It is not 826.**

**Operating scope, recorded as an assumption because `SD-1` is still formally unanswered:** the Product Owner's framing (*"105 failed / 668 passed"*, *"Slice 1 authorised"*) points at **the measured constitutional core**, i.e. `SD-1` option **(a)**. **Cheap to correct if wrong — no row has been classified yet.**

### 🔴 The denominator this programme has been quoting describes a run, not the estate

| Source | Figure | What it counts |
|---|---:|---|
| Programme record (failure-estate assessment, plan) | **826** *(814 completed — 668 passed · 146 failed · +12 incomplete)* | **tests that reported a result in one run** |
| **`phpunit --list-tests` over the same five paths** | **1,376** | **tests that exist** |

```
tests/Unit/Domain/Election                      428
tests/Unit/Application/Election                 485
tests/Feature/Election                          446
tests/Architecture/ElectionStateMachineConsistencyTest  12
tests/Architecture/Election                       5
                                               ————
                                               1,376
```

**~550 tests in the measured universe did not report a result in the run whose numbers this programme has been carrying.** **The cause is NOT established** — candidates include classes erroring before any test reports, data-provider expansion differences, or the run covering less than the stated universe. **It must be established before any coverage or completeness claim**, because it is the matrix's denominator.

> **This is the programme's own invariant applied to itself — *never report the scope of the programme as the scope of the current evidence*. "826" has travelled through this work as though it described the Election estate. It describes one run's completions.**

### ⚠️ Method correction — my first two counts were both wrong, and validating is what caught it

| Attempt | `tests/Architecture/Election` | Verdict |
|---|---:|---|
| grep `public function test_…` | 5 | matched PHPUnit **by luck** — misses `#[Test]` files entirely (returned **0** for two of them earlier) |
| "improved" scanner (`test_` + `#[Test]`/`@test`) | **17** | 🔴 **3.4× overcount** — the `@test` regex matches the string inside prose docblocks and mis-attributes the next function |
| **`phpunit --list-tests`** | **5** | ✅ ground truth |

**Both regex approaches were wrong in opposite directions, and the second was worse than the first.** The scanner reported **1,239** across the universe — plausible-looking, and **not** the answer.

> **Binding for matrix construction: the row set comes from `phpunit --list-tests`, never from a source scan.** A regex cannot know what PHPUnit considers a test — attributes, inheritance, abstract bases, traits and data providers all defeat it. **This was caught only because a small path could be checked against a real run.**

### Status

**No matrix rows classified.** The first act of Step 2 was to establish its row set, and doing so produced a finding that changes the denominator of every later claim.

**Open, and now sharper:**

| | |
|---|---|
| **`SD-1`** | still formally unanswered; **(a) assumed** for this work |
| **`SD-2`** | unanswered — and the 826/1,376 gap is **evidence that static reading alone cannot settle it**: only a run says which tests report |
| **`SD-3`** | programme identity — unresolved |
| 🔴 **new** | **why do ~550 tests in the measured universe not report a result?** Not established, not guessed |

---

## DENOMINATOR RECONCILIATION — COMPLETE. The historical figure measured a subset of the universe it named.

**Report: [`docs/publicdigit/reviews/2026-08-08-denominator-reconciliation.md`](../publicdigit/reviews/2026-08-08-denominator-reconciliation.md) · Manifest: [`…-election-test-universe-manifest.tsv`](../publicdigit/reviews/2026-08-08-election-test-universe-manifest.tsv) (1,376 rows)**

### The answer, and it is arithmetic rather than interpretive

```
tests/Unit/Domain/Election      428
tests/Feature/Election          446
                                ———
two of the five named paths      874   >   historical total 814
```

**Neither path has grown since 2026-08-07** (`git log --diff-filter=A` → **0** test files added). **So the historical run cannot have covered even two of the five paths it named.** The stated universe and the measured universe were never the same.

### Hypotheses eliminated by measurement, not by argument

| Hypothesis | Verdict |
|---|---|
| (b) discovered but not executed · (e) data-provider expansion · (f) classes erroring before execution | 🔴 **eliminated.** `--list-tests` and a real run agree exactly in **two** families: `Unit/Domain/Election` **428 = 428** (including 13 errors, which PHPUnit still counts) and `Feature/Election/StateMachine` **24 = 24** |
| (c) tests added since the historical run | 🔴 **eliminated** — 0 files added |
| **(a) the historical run covered less than the stated universe** | ✅ **established** by the arithmetic above |
| **which** subset it covered | **MECHANISM NOT ESTABLISHED** — no command, filter, suite selection or test list was recorded anywhere. **Unrecoverable, and not guessed** |

### Consequences for the programme's own figures

* **826 was an execution number over an unrecorded scope** — never a count of the Election estate.
* **668 / 146 may be retained only as** *"one run's result over an unrecorded subset, 2026-08-07."* **Every claim derived from them requires re-qualification, including the `146 → 105` trajectory.**
* **The earlier "~550 gap" was itself imprecise:** 1,376 − 826 = 550 uses the *reported total*; 1,376 − 814 = 562 uses *completions*. **Both subtract incomparable quantities** — which is the finding, not a correction to it.

### The scope contract — so "1,376" cannot become the next unreproducible number

| Field | Value |
|---|---|
| Command | `vendor/bin/phpunit --list-tests <path>` |
| Paths | the five above |
| Configuration | repository `phpunit.xml`, **unmodified** |
| Checkpoint | **`9533bcfe`**, re-captured at **`cdd93d65`** — **1,376 both times** |
| Identifier form | PHPUnit's own `Class::method` |
| **Denominator** | **1,376** |

**The re-capture at a later checkpoint is deliberate evidence:** Session 2's intervening commits did not change the Election test universe.

**Manifest column honesty:** `reported_in_historical_run` = **`UNRECOVERABLE`** for all 1,376 rows. `current_result` = `RAN_2026-08-08` for **457** rows (the two paths actually executed) and **`NOT_MEASURED`** for **919**. **No row carries an inferred status.**

**Status: DENOMINATOR ESTABLISHED (1,376, under a recorded contract). Master Matrix classification NOT started. `SD-1` formally unanswered — the five-path universe was adopted because the assessment named it. `SD-2` unanswered, and now consequential: 919 of 1,376 rows have no measured result.**

---

## ✅ SD-1 and SD-2 DECIDED · STAGE 2 EXECUTION BASELINE ESTABLISHED

**Product Owner decisions, recorded verbatim in effect:**

| | Decision |
|---|---|
| **`SD-1`** | ✅ **ADOPTED** — the five named paths / **1,376 PHPUnit tests** are the formal Slice 1 programme universe. **With the qualification:** *"this is the current Election verification estate for this programme, based on the five paths the assessment named. It is NOT a claim that these are every Election-related test in the repository."* |
| **`SD-2`** | ✅ **ADOPTED** — **execution evidence precedes behavioural classification.** The 919 unmeasured rows were **not** to be classified as *"not executed"*; they were `NOT_MEASURED` until measured. **They are now measured.** |

### The baseline — 1,376 listed, 1,376 reported

**Command:** `vendor/bin/phpunit --log-junit <file> <path>`, each path **sequentially** — *required*, because `tests/TestCase.php` uses `migrate:fresh` per test on PostgreSQL, so concurrent runs against `nrna_test` would destroy each other.
**Run:** 2026-08-12 14:08:01→14:09:12 (**71s**) · checkpoint **`03846d03`** · PHP 8.5.8 · PHPUnit 11.5.6 · `phpunit.xml` unmodified.

| Status | Count |
|---|---:|
| **PASSED** | **1,256** |
| ERROR | 65 |
| FAILURE | 37 |
| SKIPPED | 18 |
| **FAILING (error + failure)** | **102** |
| **Total** | **1,376** |

**🔑 Every test in the universe produced a result.** This **completes** the elimination that §C of the reconciliation could only demonstrate on two of five paths: **listed = reported across all five.** Hypotheses (b), (e) and (f) are now closed on full-universe evidence rather than generalisation.

**Label honesty:** JUnit reports *incomplete* as `skipped`. The console summaries were Skipped 6 + Incomplete 12 = **18** — the same population under a different label, not a discrepancy.

### What this does to the historical figures

| | Failing | Denominator | Rate |
|---|---:|---:|---|
| Historical (unrecorded subset, 2026-08-07) | 146 → 105 | **814** | 17.9% → 12.9% |
| **Baseline (full universe, measured)** | **102** | **1,376** | **7.4%** |

**⚠️ These rates are NOT comparable, and the resemblance of 102 to 105 is not evidence of continuity.** Different denominators over different — and for the historical figure, *unknown* — test sets. **The baseline supersedes the trajectory rather than continuing it.**

### Failing population — signatures only, NOT classified

**Recorded as raw evidence. No test has been assigned a business intent, an owner, or a failure classification** — that is Master Matrix work and it has not begun.

| Signature | Count |
|---|---:|
| `TypeError: …Simplified\EvaluationEnvelope` | 8 |
| `PermissionDoesNotExist: There is no permission …` | 8 |
| `QueryException SQLSTATE[23503]` foreign-key violation | 7 |
| `Class "App\Domain\Election\Security\OverlayInfluenceContext" not found` | 6 |
| `ModelNotFoundException` | 5 |
| `Class "App\Domain\Election\Security\OverlaySignal" not found` | 5 |
| `Failed asserting that exception of type "DeprecatedQuery…"` | 4 |
| `Undefined constant …Deprecation\Deprecation…` | 4 |

**⚠️ Two cautions carried forward, both from earlier programme findings:**

1. **The `DeprecatedQuery…` group is `Cluster 6`-shaped** — the deprecation ladder runs at `STRICT_LEVEL = 1`, so level-2+ guards are **inactive by design**. **Those tests may be asserting a future authorised state. They must not be "fixed" to reduce the failure count.**
2. **`Class … not found` (11 tests across two classes)** looks like a missing-class/harness issue. **Looks like is not is** — the mechanism is **not established**, and `PBDIGIT-62` is the precedent for a familiar-looking signature concealing a live defect.

**Manifest:** [`2026-08-08-election-test-universe-manifest.tsv`](../publicdigit/reviews/2026-08-08-election-test-universe-manifest.tsv) — 1,376 rows, per-test `status · assertions · time · first detail line`, all from PHPUnit's own output.

**Status: EXECUTION BASELINE ESTABLISHED. Master Matrix classification NOT started — it is the next authorised step, and no row carries a business intent, invariant, owner or failure classification yet.**

---

## MASTER MATRIX — BUILT. **Not complete, and the gap is the point.**

**Artifact: [`docs/publicdigit/reviews/2026-08-08-election-master-matrix.tsv`](../publicdigit/reviews/2026-08-08-election-master-matrix.tsv)** — **1,376 rows, 26 columns**, one row per authoritative test. Rows come from the manifest (`--list-tests` + JUnit), **never from a source scan.**

### The matrix is built in three visibly separated layers, so the weaker never passes for the stronger

| Layer | Columns | Coverage | Source |
|---|---|---:|---|
| **L1 · MEASURED** | result · assertions · time · first detail line | **1,376 / 1,376 (100%)** | PHPUnit's own JUnit output |
| **L2 · STRUCTURAL** | structural layer · structural area | **1,376 / 1,376 (100%)** | the class's **own namespace position** — evidence about *structure*, **not** about business intent |
| **L3 · JUDGEMENT** | business intent · invariant · lifecycle state · capability · actor · authorization · entry point · decision source · **ownership** · fixture · expected · failure classification · coverage · gap · legacy · open question | 🔴 **37 / 1,376 (2.7%)** | **requires reading the test.** Everything else reads `NOT_ESTABLISHED` |

> **`SLICE 1 COMPLETE` is NOT claimed.** The matrix *exists* with a measured result for every row; **business classification stands at 2.7%.** Filling those columns from names or namespaces is exactly what the commission forbids, so they were left empty rather than plausibly populated.

### Failure classification — 102 failing, 24 classified on evidence

| Classification | Tests | Basis |
|---|---:|---|
| **Mechanism not yet established** | **78** | no evidence gathered yet — Slice 2 work |
| Test/fixture defect — missing permission seed | 8 | `Spatie PermissionDoesNotExist`: **the guard was never reached**, so this is *not* evidence of an authorization defect |
| Mechanism **partly** established | 7 | references `…Security\OverlayInfluenceContext`, which **exists nowhere in `app/`**. Removed vs never built — **not established**, so obsolete-reference vs unbuilt-capability cannot be chosen |
| Test/obsolete-reference defect | 5 | test names `…Security\OverlaySignal`; the class lives at `…Security\**Simplified**\OverlaySignal`. Namespace reorganisation the test did not follow |
| **EXPECTED MIGRATION FAILURE — PROTECTED** | 4 | `DeprecationAccessGuardTest`, whose own docblock says *"RED tests"*; `STRICT_LEVEL = 1` makes level-2+ guards inactive **by design**. **Must not be "fixed"** |

**Failing by structural area:** Election — general **71** · Election Security **20** · Timeline editing **8** · Security overlays **3**.

### Coverage — expressed as business consequence, and deliberately thin

**Only where evidence permits:**

* 🔴 **Server-side timeline authorization is UNVERIFIED** — `canEditTimeline` enforcement at `PATCH /elections/{slug}/timeline` has **8 tests that all abort before reaching the guard.** *"8 tests exist"* and *"the invariant is protected"* are different statements, and only the first is true.
* 🔴 **Three security areas contribute nothing at present** — `OverlaySignal` (5), `OverlayInfluenceContext` (7), `EvaluationEnvelope`/D5 resolver (8): 20 tests that cannot load or bind.
* 🟢 **The deprecation ladder's readiness signal is intact** — 4 tests failing **by design**.
* **Everything else: `NOT_ESTABLISHED`.** 1,256 tests pass; **whether they pass for the right reason is unexamined**, and a green test is not evidence of business correctness.

### Architectural findings

1. **The largest structural area is `Election — general` (692 of 1,376, 50%)** — tests whose namespace gives no capability signal. **L2 cannot classify half the estate**, which is itself the argument for L3 rather than a shortcut.
2. **`Election Security` holds 392 rows (28.5%) and 23 of the 102 failures.** Security is the estate's heaviest concentration by a wide margin — **recorded as a distribution fact, not a risk claim.**
3. **A namespace reorganisation left tests behind** (`Security\Simplified`). Established for `OverlaySignal`; **whether it is systemic is not established.**

### Open questions

* Which permission is missing for timeline authorization — absent from the seeder, or renamed?
* Was `OverlayInfluenceContext` withdrawn or never built? **Possibly a business question, the `PBDIGIT-48` shape.**
* Which side of the `EvaluationEnvelope` contract moved — test expectation or production signature? **A TypeError is a contract mismatch; direction not established.**
* Of the 1,256 passing tests, how many verify a business invariant versus an implementation detail? **The calibration showed one passing-looking test was an N+1 probe that swallowed its authorization result.**

### ⚠️ Two errors of my own, recorded because both nearly entered the matrix as facts

1. **I read recursive `find` output as a path assertion.** `find app/Domain/Election/Security -name OverlaySignal.php` matched `Security/**Simplified**/OverlaySignal.php`; I reported *"EXISTS"* at the non-nested path. **The class had moved — the opposite conclusion from the same output.**
2. **Earlier, two regex test-counters were both wrong** (5 by luck; 17 as a 3.4× overcount). **`phpunit --list-tests` is the row source for this matrix precisely because of that.**

**Status: MASTER MATRIX BUILT — 1,376 rows, L1/L2 complete, L3 at 2.7%. SLICE 1 NOT COMPLETE. No production code, test, fixture or configuration changed. `SD-3` still unresolved; Session 2 / IERVP untouched.**

---

## B1 ACCEPTED · `SD-4` RAISED — the verification boundary does not follow the business boundary

**B1 accepted by the Product Owner.** Report: [`2026-08-08-b1-apply-candidacy-classification.md`](../publicdigit/reviews/2026-08-08-b1-apply-candidacy-classification.md).

**B2 is NOT started, and must not be** until `SD-4` is decided. **Continuing B2–B7 with an unresolved scope error would make the Master Matrix exactly the artifact this programme exists to avoid: apparently rigorous, carrying a hidden scope defect.**

### 🔴 `SD-4` — BUSINESS / GOVERNANCE DECISION REQUIRED

> **Should the Master Matrix include the 29 candidacy-application tests currently outside the adopted `SD-1` five-path universe?**

**The contradiction, stated plainly:**

```
programme scope (SD-1)     tests/Feature/Election/          → 1,376 tests
business capability scope  CandidacyApplicationTest, ElectionCandidacyRelationshipTest,
                           ElectionCandidacyApplyPageTest, CandidacyApplicationMigrationTest,
                           DebugCandidacyTest              → 29 tests, ALL in tests/Feature/
```

**`apply_candidacy` is the only constitutional action performed by a *participant*, and the adopted universe excludes every test that exercises it.**

| | Option | Consequence |
|---|---|---|
| **A** | **Expand the universe** to include the five candidacy files | The only participant-performed capability becomes measurable · the matrix reflects the business capability landscape · **but the denominator changes**, `SD-1` needs formal amendment, the baseline must be regenerated for the added population, and **the original 1,376 must be preserved as historical evidence rather than silently replaced** |
| **B** | **Keep `SD-1` unchanged** | Defensible **only if the exclusion is intentional** — and then the programme **may never claim "the Election estate has been verified"** without stating that candidacy application was excluded |

**Architect's recommendation: Option A** — *"the verification boundary should follow the business capability boundary, not an accidental filesystem boundary."* **Recorded as a recommendation. Engineering has not adopted it, and the decision is not taken.**

### ⛔ If Option A is chosen, the sequence is NOT "add 29 tests and continue"

1. formally amend `SD-1`; 2. define the new universe; 3. **`phpunit --list-tests` as the authority**; 4. establish the new denominator; 5. execute the newly added population; 6. **preserve the 1,376 baseline**; 7. update the matrix scope; 8. **only then** resume B2.

**Adding the tests without steps 1–7 would repeat the 826/1,376 defect** — a denominator changed without a recorded contract.

### B1's other outcomes, dispositioned

| | |
|---|---|
| `complete_nomination`'s undocumented self-transition | **An architectural QUESTION, not a constitutional defect.** *Is it intentionally a capability check with derived state, like `resume` and `apply_candidacy`, or is the declaration an oversight?* **Enough for the matrix; do not open a defect** |
| `voter` vs `member` in `allowed_roles` | **BUSINESS RULE NOT SPECIFIED** — the constitution lists both and never distinguishes them. **Session 2 is separately investigating `ElectionMembership` vs organisation `Member`; that evidence was correctly NOT imported** |
| duplicate candidacy | **BUSINESS RULE NOT SPECIFIED** — no declared precondition. **Not a bug** |

### Why B1 was worth doing before scaling

**A failure-driven pass would never have found this.** `PHPUnit → failure → code → presumed cause → fix` had nothing to work with: **the one in-scope test passes.** The finding came from the other direction — *business capability → authoritative rule → decision ownership → execution path → test intent → evidence* — and the answer was not a failing test but **"the verification universe does not contain the tests that matter for this capability."**

**Status: B1 ACCEPTED. `SD-4` OPEN — blocks B2–B7. `SD-1` unamended; the universe remains 1,376. `SD-2` satisfied (baseline exists). `SD-3` still unresolved. No production code, test, fixture or configuration changed.**

---

## ⛔ AUTHORITY BLOCKS ARE ROW-SCOPED, NOT PROGRAMME-SCOPED — rule adopted 2026-08-12

**Product Owner correction, accepted:** *"Resolve an authority question when it blocks classification of that particular capability."* **The matrix must not be hostage to every unresolved business question.**

> **An unresolved authority question blocks the ROWS whose verification shape depends on it. It does not block the matrix.**

### `SD-11a`'s actual blast radius — measured

| | |
|---|---|
| Candidate rows by name (`Result·Count·Tally·Publicat`) | 13 |
| 🔴 **False positive removed** | **`VotingTrustResultTest` (5 rows)** — tests `App\Domain\Election\Security\VotingTrustResult`, i.e. **security trust evaluation, not election results.** A collision on the word *"Result"* |
| **Genuinely blocked on `SD-11a`** | **`ResultsPublicationTest` — 8 rows** |
| **Share of the universe** | **8 / 1,376 = 0.6%** |

**So `SD-11a` blocks 0.6% of the matrix.** Describing the Master Matrix as *"blocked"* on it — as the C10 report's §8 did — **overstated the dependency by more than two orders of magnitude.** Corrected here.

**Fifth name-collision catch today** (after recursive `find`, two test counters, and the `resume`/`complete_nomination` parser). **Consistent lesson: a name match is a candidate, never a classification.**

### The isolation rule, stated for reuse

| Blocker | Blocks |
|---|---|
| **`SD-1` / `SD-2`** (scope · evidence depth) | ✅ **PROGRAMME** — every row's identity and result |
| **`SD-4`** (candidacy boundary) | **the boundary itself** — resolved or not, existing rows stay valid |
| **`SD-11a`** (counting authority) | **8 rows** (`ResultsPublicationTest`) |
| `BD-1` (audit evidence) | **7 of 8** referencing files — established by the BD-1 dependency analysis |
| `PBDIGIT-49` (eligibility home) | C6-related rows — **not yet enumerated** |

**Every future authority question must state its blast radius in rows before it is allowed to pause work.** *"Important"* is not *"blocking"* — the same distinction `BD-1` required, now generalised.

**Status: matrix classification may proceed for capabilities whose authority is established. `SD-11a` isolates 8 rows. L3 remains 41/1,376 pending further batches.**

---

## B2 — `open_voting` · `canVote` · PARTIALLY CLASSIFIED. **They are three decisions, not one.**

**Population:** 68 rows across 5 classes — `VoterEligibilityTest` (27) · `ConstitutionalVotingProtectionTest` (12) · `VotingButtonsStateMachineTest` (10) · `ElectionPolicyStateAwareTest` (10) · `VotingButtonsStateMachineIntegrationTest` (9). **60 PASSED · 4 ERROR · 4 FAILURE.**

### The B2 answer — `open_voting` ≠ `canVote`, and `canVote` is itself two things

**Verified in `ElectionVotingController` (carried forward from Relationship 3 and re-confirmed):**

```php
:41  $hasVoted   = $membership?->has_voted ?? false;
:42  $isEligible = $membership !== null …
:51  $canVote    = $isEligible && !$hasVoted && $lifecycle->canVote();   // PROJECTION
:53  if (! $lifecycle->canVote()) { … }                                  // ENFORCEMENT
:71  'canVote' => $canVote,                                              // shipped to the UI
```

| # | Decision | Business meaning | Authority | Ownership |
|---|---|---|---|---|
| **1** | **`open_voting`** | *may this election's voting phase begin?* | `ElectionConstitution` — **chief only**, preconditions `voting_window_defined` + `timezone_set` | **Domain** (rules) + **Application** (guard) |
| **2** | **`$lifecycle->canVote()`** | *is voting constitutionally permitted right now?* — state machine + window + administration completion | `ElectionLifecycleEngineImpl` (derived) | **Application** (derivation) |
| **3** | 🔑 **`$canVote` (the UI prop)** | *may **this voter** vote right now?* — a **three-input composition** | **none** — composed inline in the controller | 🔴 **Interface/Projection** |

> **The UI's `canVote` is not the lifecycle's `canVote()`.** Decision 3 folds **entitlement** (`$membership !== null`) and **already-voted** (`has_voted`) into a constitutional capability — and **that composition has no owner outside the controller.**

**So a test asserting the UI flag verifies an application composition; a test asserting the guard verifies a domain capability. Mapping both to "Domain" would erase exactly the distinction the ownership column exists to capture.**

### Where the concepts sit — and three are NOT collapsed

| Concept | Established? |
|---|---|
| lifecycle/voting phase | ✅ constitutional |
| capability `canVote()` | ✅ derived by the engine |
| **voter entitlement** | 🔴 `$membership !== null` — and **which store is authoritative is CONTESTED** (`PBDIGIT-49`) |
| **voter eligibility** | ⚠️ **conflated with entitlement here** — `$isEligible` is computed *from membership presence*. **Whether entitlement and eligibility are the same business concept is `BUSINESS RULE NOT SPECIFIED`** |
| already-voted | `membership->has_voted` — **note: C8's gate keys on `codes.has_voted`, a DIFFERENT column.** Two representations of "has voted" |
| actor authorization | `open_voting` = chief only; **voting itself has no role check here** |
| ballot/code/device/IP | out of B2 |

### 🔴 B2's two findings

1. **`$isEligible` is derived from membership presence alone.** Eligibility and entitlement are **not distinguished** in the composition. **Not a defect claim** — it may be the intended model — but **no source specifies it**, and `PBDIGIT-49` shows the membership store itself is contested.
2. **"Has voted" exists in two places:** `election_memberships.has_voted` (B2, decision 3) and `codes.has_voted` (C8's one-vote gate). **Whether they can disagree is NOT ESTABLISHED** — and a disagreement would mean the UI and the submission gate answer differently.

### Coverage — the 8 failures are NOT the story

**60 of 68 pass. The gap is what none of them covers.**

| Dimension | Covered? |
|---|---|
| correct lifecycle state | ✅ plausibly — `VotingButtonsStateMachine*`, `ElectionPolicyStateAware` |
| server-side enforcement | ✅ `ConstitutionalVotingProtectionTest` (12, all passing) — **name and population suggest it; intent not read** |
| **the decision-3 composition** | 🔴 **NOT ESTABLISHED** — whether any test asserts that the UI flag agrees with the guard |
| **entitlement vs eligibility distinction** | 🔴 **cannot be covered — the rule does not exist** |
| **the two `has_voted` representations agreeing** | 🔴 **NOT ESTABLISHED** |

**8 failures:** 7 in `VoterEligibilityTest` (4 ERROR + 3 FAILURE) and 1 in `VotingButtonsStateMachineIntegrationTest`. **Mechanism not yet established for all 8** — not investigated, per the no-repair rule.

> **The most valuable B2 observation is not a failure.** `VoterEligibilityTest` is the largest B2 class (27 rows) **and eligibility is the concept B2 found to be unspecified.** 27 tests exercise a distinction no source defines.

### Blast radius

| Blocker | Rows | Capabilities |
|---|---:|---|
| `PBDIGIT-49` (entitlement store) | **≥27** — all of `VoterEligibilityTest`; **more not enumerated** | C6 · B2 decision 3 |
| `SD-11a` | 8 | C10 |

**`SD-12` raised:** are **entitlement** and **eligibility** the same business concept? **`BUSINESS RULE NOT SPECIFIED`; the code treats them as identical.**
**CROSS-STREAM EVIDENCE — REQUIRES EXPLICIT RECONCILIATION:** Session 2's `D-ENT-1`/Model B concerns precisely this. **Not imported; nothing above depends on it.**

**Status: B2 — PARTIALLY CLASSIFIED (68 rows populated at L2/structural + decision-level L3; per-test intent read for 0 of 68). MASTER MATRIX — CONTINUING. `SD-11a` — BLOCKS ONLY THE 8 MEASURED C10 ROWS. IMPLEMENTATION — NOT AUTHORISED.**

### ⚠️ B2 correction — `$canVote`'s ownership was stated wrongly, and the correct version is a stronger finding

**I wrote:** *"`$canVote` (the UI prop) … Ownership: **Interface/Projection**."* **That is wrong, and it would have entered the matrix as a fact.**

**The Interface does not own the business decision. It projects one.** The correct model:

```
Domain          Election lifecycle rules (constitution)
Application     derive voter exercisability — combine entitlement +
                lifecycle permission + vote consumption + credentials
Authorization   officer/actor permission
Interface       PROJECT the already-derived capability
```

**So the corrected classification of decision 3 is:**

| | |
|---|---|
| **Business Decision Ownership** | **Application** — deriving voter exercisability is an application concern |
| **Where it is executed** | 🔴 **`ElectionVotingController:51` — Interface-layer code** |
| **The finding** | **An Application-layer decision is composed inside an Interface-layer class.** There is no application service, no use case, no named concept — the composition exists only as a controller expression |

> **"The Interface owns this decision" and "an Application decision is being made in the Interface" are different claims. The first excuses the placement; the second identifies it.** My original wording made the weaker, wrong one.

**Consequence for the matrix:** decision 3's rows carry **`Business Decision Ownership = Application`** with a **placement observation**, not `Interface/Projection`. **Recording ownership as Interface would have made the misplacement invisible** — the column would have said "correct layer" about the thing it exists to catch.

**Same shape as Relationship 4's fifth-occurrence rule:** *never infer decision ownership from the first class encountered in the call path.* **I inferred it from the class the code sits in.** Sixth occurrence.

### Vocabulary held distinct — none of these is a synonym

**entitlement · eligibility · exercisability · authorization · credential possession · lifecycle permission · vote consumption.**

**B2 established only that `$isEligible` derives from membership presence.** Whether that represents **entitlement** or **exercisability** is `SD-12`, and **the code's use of the word "eligibility" is not evidence of the business rule.**

### Next-action dependency

```
B2  ── established ──────────► continue where authority is settled
    ├─ SD-12 / PBDIGIT-49 ───► blast radius ≥27, NOT fully enumerated
    └─ SD-4 ─────────────────► programme-boundary decision, GATES B3+
```

**`SD-4` is requested and not taken.** **B3 is not started.**

---

## `PBDIGIT-49` BLAST RADIUS — measured. **213 candidate rows, not 27.**

**Detection method — deliberately not the word "eligibility".** Candidates were found by reference to the **mechanisms `PBDIGIT-49` contests**: `election_memberships` · `ElectionMembership` · `VoterEligibilityService` · `isEligible` · `VoterSourceStrategy`. **Rows mapped to PHPUnit identities from the 1,376 manifest — no grep/file/class count used as a denominator.**

| Mechanism | In-universe classes referencing it |
|---|---:|
| `ElectionMembership` | **19** |
| `VoterSourceStrategy` | 6 |
| `election_memberships` | 4 |
| `isEligible` | 4 |
| `VoterEligibilityService` | 1 |
| `EligibilityEvaluator` · `EligibilitySnapshot` · `'voters'` | **0** |

### A · Blast radius table

| | Count | Share of 1,376 |
|---|---:|---|
| **Candidate classes** | **25** | — |
| **POTENTIALLY AFFECTED rows** | **213** | **15.5%** |
| — PASSED | 180 | |
| — FAILURE | 17 | |
| — ERROR | 11 | |
| — SKIPPED | 5 | |
| **AFFECTED (confirmed direct dependency)** | 🔴 **NOT ESTABLISHED** | — |
| **BLOCKED** | 🔴 **NOT ESTABLISHED** | — |

> **The four terms are kept distinct, and only one is measured.** **`POTENTIALLY AFFECTED` = 213** is a *mechanism-reference* population. **`AFFECTED` and `BLOCKED` require reading each test's intent, which was not done** — so they are **not** reported as numbers. **Calling 213 "blocked" would repeat the exact error the programme has been correcting.**

### B · Reconciliation with the earlier ≥27

| | |
|---|---|
| Earlier lower bound | **≥27** — `VoterEligibilityTest` alone |
| Measured candidates | **213** across 25 classes |
| **Verdict** | The lower bound was **~8× low**. **`VoterEligibilityTest` was the most obviously-named class, not the population** — the same shape as `apply_candidacy`, where the obvious name held 1 of 30 tests |

**`EligibilityEvaluator`, `EligibilitySnapshot` and `'voters'` return ZERO in-universe references** — notable, because `PBDIGIT-49` names `voters` as one of the two contested homes. **The in-universe estate references `election_memberships` and not `voters`,** which is *consistent* with `PBDIGIT-49`'s finding that `voters` is empty in production. **Recorded as consistency, not confirmation.**

### C · Affected capability summary

`ElectionMembership` at **19 classes** makes this **the widest single concept found in the estate so far** — wider than counting (8), candidacy (0 in-universe), and timeline authorization (8). It touches **C4 candidacy · C6 admission · C8 casting** — i.e. **every capability that decides who participates.**

### D/E · Matrix impact

**Rows that can continue:** the **~1,163** with no reference to a contested mechanism — **84.5% of the universe.** **Classification is not blocked.**
**Rows requiring `SD-12`/`PBDIGIT-49` before their *verification shape* can be settled:** **a subset of the 213, size NOT ESTABLISHED.**

### F · Cross-stream relevance

**CROSS-STREAM RELEVANCE — SESSION 2.** `ElectionMembership`, `VoterSourceStrategy` and Full-Membership vs Election-Only mode are exactly Session 2's `D-ENT-1`/Model B territory. **The shared question:** *does `ElectionMembership` constitute voting entitlement, and is entitlement the same as exercisability?* **Not resolved here. Not imported.** **19 candidate classes sit on that question.**

### G · Open questions

* How many of the 213 have a **direct** dependency? **Requires per-test reading.**
* Does `ElectionMembership` == voting entitlement? **`BUSINESS RULE NOT SPECIFIED`** — `SD-12`.
* Why does the in-universe estate never reference `'voters'`? *(Consistent with it being empty; **not established** as deliberate.)*

### H · Recommendation for the next governance decision — **`SD-4` is NOT decided here**

**`SD-12`/`PBDIGIT-49` now outranks `SD-4` on measured impact:** **213 candidate rows (15.5%)** versus `SD-4`'s **29 tests** in one capability. **Recommendation: put `SD-12` before `SD-4`.** **Both remain the Product Owner's; neither is taken.**

**PBDIGIT-49 BLAST RADIUS ESTABLISHED — as a POTENTIALLY-AFFECTED population of 213 rows (15.5%). `AFFECTED` and `BLOCKED` remain NOT ESTABLISHED.**

### ⚠️ Correction — `ElectionMembership`'s 19 classes is an estate observation, not an ownership finding

**I wrote:** *"`ElectionMembership` … touches C4 candidacy · C6 admission · C8 casting — i.e. **every capability that decides who participates**."* **That reads as an architectural conclusion. It is not one.**

**What is established:** `ElectionMembership` is **referenced by tests spanning multiple capabilities**. **What is NOT established:** that `ElectionMembership` **owns** any of those decisions.

**The decisions it might or might not own must stay separate:**

```
ElectionMembership
      │ stores / represents
      ▼
an election-specific relationship
      ├── entitlement?           ← domain decision        NOT ESTABLISHED
      ├── suspension?            ← domain/application     NOT ESTABLISHED
      ├── exercisability?        ← application composition (B2 decision 3)
      ├── authorization?         ← policy                 NOT ESTABLISHED
      └── credential possession? ← voting mechanism       NOT ESTABLISHED
```

**Seventh occurrence of the same error class today** — after recursive `find`, two test counters, the constitution parser, the `Result` name collision, and `$canVote`'s layer. **The pattern is now unmistakable: a reference count tells me where a name appears; it never tells me what owns a decision.**

### ⛔ `SD-12` is NOT an independent decision — it is `D-ENT-1`'s question

**Product Owner directive, adopted.** `SD-12` is **not** *"should the eligibility implementation change?"* It is *"what is the business meaning of `Member`, `ElectionMembership`, entitlement and exercisability?"* — **which is precisely what Session 2 is establishing as `D-ENT-1`.**

> **Creating `SD-12` as a separate decision would produce two competing authorities for one question.** It is therefore **folded into `D-ENT-1`** and tracked as a dependency, not raised for independent ruling.

**Three levels, kept distinct:**

| Level | Owner | Status |
|---|---|---|
| **1 · Measurement** — 213 rows potentially reference the contested mechanisms | Session 1 | ✅ **established** |
| **2 · Business/domain meaning** — what should `ElectionMembership` mean across both modes | **Session 2 (`D-ENT-1`)** | ⬜ **in progress** |
| **3 · Architectural decision** — adopt Model B, amend `ADR-002` | **Product Owner** | 🔴 **not authorised** |

**Session 1's standing constraints until level 3 is reached:**

1. 213 rows stay **`POTENTIALLY AFFECTED`** · 2. `AFFECTED` stays **NOT ESTABLISHED** · 3. `BLOCKED` stays **NOT ESTABLISHED** · 4. **no `ADR-002` amendment** · 5. no production or test change · 6. **continue classifying rows whose authority is already settled** · 7. **`PBDIGIT-49` does not block the ~1,163 uncontested rows** · 8. `D-ENT-1` recorded as **CROSS-STREAM EVIDENCE — REQUIRES EXPLICIT RECONCILIATION**.

> **When Session 2 delivers its proposed `D-ENT-1`, Session 1 STOPS and waits for Product Owner approval before treating it as authoritative or classifying the 213 rows against it.**

**And the constraint that matters most:** **the 213-row population is evidence about blast radius. It is NOT evidence that the current implementation is wrong.** Nothing in this programme has established that.

**Status: `SD-12` withdrawn as an independent decision → folded into `D-ENT-1`. Master Matrix continues on the ~1,163 uncontested rows. L3 41/1,376. `SD-4` still open and still gates B3+.**

---

## B2 per-test classification — batch 1 of 5: `ConstitutionalVotingProtectionTest` (12 of 68)

**Chosen first because B2's coverage claim rested on it.** B2 recorded *"server-side enforcement ✅ plausibly — name and population suggest it; **intent not read**."* **It is now read.**

**The class declares its own invariants** (`ConstitutionalVotingProtectionTest:16-26`) — *"VERIFIES: Election administration and voting are constitutionally connected. A voter cannot bypass administration to start voting"* — with four named groups and lettered invariants **A · B · C · E · H**. **Business intent came from the class, not from me.**

| # | Test | Business intent | Invariant | Lifecycle state | Ownership | Entry point | Result |
|---|---|---|---|---|---|---|---|
| 1 | `cannot_vote_during_setup_administration` | ballot refused before administration completes | **A + B** | `setup_administration` | Application (capability) | `GET slug.vote.create` | PASSED |
| 2 | `cannot_vote_during_nomination_phase` | ballot refused during nomination | A + B | `setup_nomination` | Application | same | PASSED |
| 3 | `cannot_vote_during_suspended_overlay` | 🔑 **suspension overrides lifecycle** | **H** | `suspended` | Application | same | PASSED |
| 4 | `cannot_vote_after_election_closed` | ballot refused after the window closes | A + B | post-window | Application | same | PASSED |
| 5 | `can_vote_when_voting_active` | 🔑 **positive control** | A | `voting_active` | Application | same | PASSED |
| 6 | `code_creation_allowed_during_setup` | **code creation is preparatory, deliberately NOT gated** | **E** | `setup_administration` | Application | code route | PASSED |
| 7 | `agreement_allowed_during_ready_for_voting` | agreement is preparatory, deliberately not gated | **E** | `ready_for_voting` | Application | agreement route | PASSED |
| 8 | `expired_slug_can_reach_code_creation_during_non_voting` | expired slug may renew | — *(regression, "Fix 1")* | non-`voting_active` | Application | code route | PASSED |
| 9 | `direct_post_to_vote_store_blocked_when_not_voting_active` | 🔑 **direct POST cannot bypass the gate** | A + B | non-`voting_active` | Application | **`POST` vote store** | PASSED |
| 10 | `skipping_steps_blocked_when_not_voting_active` | step order enforced | — | non-`voting_active` | **Interface (middleware)** — `EnsureVoterStepOrder` | slug routes | PASSED |
| 11 | `direct_route_to_elections_show_blocked_when_not_voting_active` | **the show page reports `canVote()` correctly** | **A** | non-`voting_active` | **Interface/Projection** | `elections.show` | PASSED |
| 12 | `slug_possession_does_not_imply_voting_authority` | 🔑 **voter slug ≠ voting authority** | **C** | — | Application | slug routes | PASSED |

### What this batch establishes — and it upgrades B2's coverage claim

* ✅ **Server-side enforcement is genuinely verified**, not merely plausible. **Row 9 asserts a direct `POST` is blocked** — that is enforcement, not projection.
* ✅ **Negative and positive cases both present** (rows 1–4 negative, row 5 positive control).
* ✅ **Row 11 is the exact distinction B2 identified** — it verifies the *projection* reports `canVote()` correctly, and is classified **Interface/Projection**, not Domain. **B2's three-decision model is confirmed by a test that was written to it.**
* 🔑 **Row 3 verifies `suspended` overrides lifecycle** — and does so **without depending on `ElectionMembership`**. **This row is NOT in the 213 population**, so suspension-over-lifecycle is verified *independently* of `D-ENT-1`.

### Dependencies

| | |
|---|---|
| `SD-12`/`PBDIGIT-49` | 🟢 **none of the 12.** The class references no contested mechanism — it gates on **lifecycle**, not entitlement |
| `SD-11a` | 🟢 none |
| **Invariant letters A · B · C · E · H** | ⚠️ **the class names them; no authoritative source defining them was located.** **`MECHANISM NOT ESTABLISHED` for the letter scheme** — whether they map to a governing document is unknown |

### 🔴 One new governance question

**`SD-13`: what are Election invariants A · B · C · E · H, and where are they defined?** **A test class asserting lettered invariants implies a register; none was found.** *(Recorded as a question — the tests may be self-documenting rather than citing an external scheme.)*

**Status: B2 per-test 12 / 68. L3 = 53 / 1,376. `SD-12` blast radius unchanged (213). Next batch: `VotingButtonsStateMachineTest` (10) — also lifecycle-gated, so likewise independent of `SD-12`. `VoterEligibilityTest` (27) is deliberately LAST: all 27 sit in the 213 population and depend on the pending handover.**

## B2 per-test — batch 2: `VotingButtonsStateMachineTest` (10) → **22 of 68**

**Independence established from content, not the name:** grep for contested mechanisms (`ElectionMembership` · `election_memberships` · `isEligible` · `VoterSourceStrategy` · `VoterEligibilityService`) returns **0**. **So `SD-12` does not reach this class** — measured, not assumed.

**Intent, from the class's own docblocks:** it tests `Election::transitionTo()` and the `open_voting`/`close_voting` actions — *"MODEL TEST … bridge to state machine"*. **This is a C7/C9 transition class, not a voter-capability class** — despite sitting in B2 because it references `canVote`.

| # | Test | Business intent | Invariant | Ownership | Result |
|---|---|---|---|---|---|
| 1 | `…transition_to_voting_creates_transition_record` | a transition **writes a record** | 🔗 **`BD-1`-dependent** | Infrastructure | PASSED |
| 2 | `…locks_voting_and_completes_nomination` | transition **sets flags** as side effects | side-effect contract | Domain (model) | PASSED |
| 3 | `open_voting_transitions_from_nomination_to_voting` | `open_voting` reaches `voting_active`, **setting window facts as a side effect** | Constitutional target state | Domain + Application guard | PASSED |
| 4 | `open_voting_rejects_if_not_in_nomination_state` | 🔑 **`allowed_states` enforced** | Constitutional | Domain (rules) + Application (guard) | PASSED |
| 5 | `open_voting_creates_state_transition_record` | record written | 🔗 **`BD-1`** | Infrastructure | PASSED |
| 6 | `open_voting_locks_voting_immediately` | voting locked on entry | side-effect contract | Domain | PASSED |
| 7 | `close_voting_transitions_from_voting_to_results_pending` | `close_voting` → `counting` | Constitutional | Domain + Application | PASSED |
| 8 | `close_voting_rejects_if_not_in_voting_state` | 🔑 `allowed_states` enforced | Constitutional | Domain + Application | PASSED |
| 9 | `close_voting_creates_state_transition_record` | record written | 🔗 **`BD-1`** | Infrastructure | PASSED |
| 10 | `close_voting_prevents_double_close_when_already_locked_and_ended` | ⚠️ **guard blocks closing from the wrong state** — and its docblock records that **when `voting_ends_at` is past, the engine derives `counting`, not `voting_active`** | Constitutional + **derivation** | Application (derivation) | PASSED |

### Findings

* 🔗 **Rows 1, 5 and 9 are `BD-1`-dependent** — three tests assert *"a transition creates a record"*, which is precisely the population the `BD-1` dependency analysis identified. **`BD-1`'s blast radius therefore extends beyond the 8 files it named: +3 rows here.** **`BD-1` = SUBSET blocker over `≥11` rows, not 8.** *(Correcting my own earlier figure.)*
* ✅ **Rows 4 and 8 verify `allowed_states` enforcement** — the constitution's core invariant, and **the first direct per-test evidence of it in this programme.**
* 🔑 **Row 10 is the most interesting.** Its docblock states that a past `voting_ends_at` makes the engine derive **`counting`**, not `voting_active` — **so the test author encountered Relationship 5's derived-state behaviour and documented it.** **Independent corroboration** of the derivation finding, from a test written before this programme began.
* ⚠️ **This class is misplaced in B2.** It verifies **C7/C9 transitions**, not C8 voter capability. **B2's population was assembled from `canVote` references** — which this class contains incidentally. **Recorded as a batching observation; the rows stay valid.**

### Dependencies

| Blocker | This batch |
|---|---:|
| `SD-12`/`PBDIGIT-49` | **0 rows** — measured |
| `SD-11a` | 0 |
| **`BD-1`** | 🔴 **3 rows** |
| `SD-13` | **0** — this class uses no lettered invariants, so `SD-13` was **not** investigated (per the directive: only if materially depended upon) |

**Status: B2 per-test 22 / 68. L3 = 63 / 1,376. `BD-1` corrected to ≥11 rows. Next: `ElectionPolicyStateAwareTest` (10) then `VotingButtonsStateMachineIntegrationTest` (9). `VoterEligibilityTest` (27) still deferred.**

---

## `D-ENT-1` CONSUMED — the 213 narrows to **39 rows requiring intent reading**

**Authority:** `docs/publicdigit/reviews/2026-08-12-governance-handover-session2-to-session1.md` — **verified present** and carrying `F1` · Model B · the discriminator. **Consumed as an adopted external business decision. `ADR-002` remains UNAMENDED, and Session 1 did not amend it.**

**Discriminator applied verbatim:** *a test is relevant to `D-ENT-1` only when its business assertion depends on organisation membership being **continuously required after `ElectionMembership` has been established**.*

### Two-stage filter — each stage measured

| Stage | Result |
|---|---:|
| Candidate population (`PBDIGIT-49` mechanism reference) | **213 rows / 25 classes** |
| **Stage 1** — classes referencing **any organisation-membership mechanism** (`user_organisation_roles` · `organisation_users` · `UserOrganisationRole` · `->members(` · `Member::` · `isMemberOf`) | **15 classes / 157 rows** |
| → **NOT AFFECTED** | 🟢 **10 classes / 56 rows** |
| **Stage 2** — of the 15, classes also referencing **exercise-time** voting (`canVote` · `vote.create` · `vote.store` · `ballot` · `castVote` · `slug.vote`) | 🔴 **3 classes / 39 rows** |
| → **NOT AFFECTED (admission-time only)** | 🟢 **12 classes / 118 rows** |

**Stage 1's logic is the strong one:** **a test that never references organisation membership cannot be asserting that organisation membership is continuously required.** That is entailment, not inference — **56 rows are `NOT AFFECTED` on logic alone.**

**Stage 2 rests on F1 itself:** admission-time assertions are **governed by F1 and unaffected** — organisation membership *is* required at admission. **118 rows are `NOT AFFECTED` because F1 endorses what they assert.**

### The remaining 39 — `REQUIRES INTENT READING`, not "affected"

| Class | Rows | Status |
|---|---:|---|
| `ElectionShowControllerTest` | — | 3 exercise refs |
| `LegacyVoteRouteTest` | — | 4 exercise refs |
| `StateMachine\CurrentBehaviorTest` | — | 3 exercise refs |
| **Total** | **39** | **34 PASSED · 5 FAILURE** |

> **These 39 are NOT classified as affected.** They are the only rows where **both** conditions co-occur, so they are the only rows whose intent must be read to decide. **`AFFECTED` remains `NOT ESTABLISHED` until that reading happens.**

### The prediction, tested

**I predicted the discriminator would "shrink the 213 substantially" and that most `ElectionMembership` classes would prove admission-time.** **Measured: 213 → 39, an 82% reduction, and 12 of 15 organisation-referencing classes are admission-time only.** **The prediction held — and it was recorded as a prediction before the measurement, not after.**

**`PBDIGIT-49`'s blast radius on the matrix is therefore ≤39 rows (2.8%), not 213 (15.5%).** The 213 was a *mechanism-reference* population; **the discriminator is what turned it into a business-relevant one.**

### What this does NOT establish

* **Not** that the 39 are wrong, or affected — **only that they are the population to read.**
* **Not** that the 174 excluded rows are *verified*, and the earlier wording was too broad. Precisely: **excluded from `D-ENT-1` potential impact under the measured discriminator; business correctness remains subject to their independent Master Matrix classification.** *(The evidence establishes only that they are not candidates under **this** discriminator — not that `D-ENT-1` cannot touch them under a different reading, and not that they are correct.)*
* **Stage 2's mechanism list is a heuristic.** A test could reach exercise-time behaviour without naming any of those six tokens — **the same detection weakness that made `BD-1` look like 8 rows and `apply_candidacy` look like one file.** **The 39 is a lower bound.**

**Status: `D-ENT-1` consumed. 213 → **39 REQUIRES INTENT READING** · 174 `NOT AFFECTED` (56 by entailment, 118 by F1). `AFFECTED` still NOT ESTABLISHED. `ADR-002` UNAMENDED. L3 unchanged at 63/1,376 — this narrowed a dependency, it did not classify rows.**

## 39-row intent verification — class 1 of 3: `LegacyVoteRouteTest` (3 of 39)

**Read in full (120 lines). All 3 rows PASSED.**

**Intent, from the class's own docblock:** *"The legacy `POST /votes` route uses `VoteEligibility` middleware which only checks `is_voter`/`can_vote` flags on the users table — not whether the user is registered as a voter for THIS specific election. Fix: `VoteEligibility` must verify `isVoterInElection($electionId)`."*

| # | Test | What it asserts | `D-ENT-1` classification |
|---|---|---|---|
| 1 | `…rejects_user_not_registered_for_any_election` | `403` + *"not registered as a voter for this election"* | 🟢 **NOT AFFECTED** |
| 2 | `…accepts_user_registered_for_the_election` | not `403` when an `ElectionMembership` exists | 🟢 **NOT AFFECTED** |
| 3 | `…rejects_user_registered_for_different_election` | 🔑 `403` — membership in **another** election does not admit | 🟢 **NOT AFFECTED** |

**Evidence for NOT AFFECTED — the discriminator turns on organisation membership, and this class never consults it.** All three assert **election-scoped `ElectionMembership` presence at exercise time**. The question *"must organisation membership continue after `ElectionMembership` exists?"* **is never asked**: the fixture creates an `ElectionMembership` and the assertions turn on **which election** it belongs to. **`D-ENT-1`/Model B is silent on that, and F1 does not touch it.**

**Business Decision Ownership:** **Policy/Authorization** (`VoteEligibility` middleware) · **authoritative rule:** election-scoped registration · **entry point:** `POST route('vote.store')`.

### 🔴 But the class carries a separate, significant finding

**Its docblock states the legacy middleware *"only checks `is_voter`/`can_vote` flags on the users table"*** — **the columns `PBDIGIT-35` established exist in NO database.** So the middleware's documented pre-fix behaviour reads **retired columns**.

**Three rows pass, which means the `isVoterInElection` check now fires first.** **Whether the retired-flag branch is still reachable is NOT ESTABLISHED** — not investigated, and not repaired. **Recorded as `PBDIGIT-35` corroboration from an independent source**, and as a **coverage observation: the tests prove the new check works; they do not prove the retired branch is unreachable.**

### Discovery-limitation check

**This class was found by the six-token heuristic** (`vote.store`). ✅ **Correctly detected.** **It is also a case where the heuristic's *inclusion* was right and its *implication* was wrong** — exercise-time tokens were present, but the assertion is election-scoped registration, not organisation continuity. **The heuristic finds candidates; only reading decides.**

**Status: 39-row verification 3 / 39. AFFECTED 0 · NOT AFFECTED 3 · UNDETERMINED 0. Remaining: `ElectionShowControllerTest` (334 lines) · `StateMachine\CurrentBehaviorTest` (841 lines). No new candidates outside the 39 discovered. L3 = 66 / 1,376.**

## 39-row verification — class 2 of 3: `ElectionShowControllerTest` (12 rows → **15 of 39**)

**12 rows: 7 PASSED · 5 FAILURE.** Read with the discriminator, not the class name.

### The decisive evidence is in the fixture helper, and it settles all 12

```php
private function makeVoterMember(User $user, Election $election): ElectionMembership
{
    // FK constraint: (user_id, organisation_id) must exist in user_organisation_roles
    $alreadyAttached = DB::table('user_organisation_roles')…
    return ElectionMembership::create([…]);
}
```

> **Organisation membership appears here ONLY as a foreign-key prerequisite for creating the `ElectionMembership` row** — the comment says so explicitly. **That is admission-time necessity imposed by the schema, which is exactly what F1 governs.**
>
> **No test asserts that organisation membership must CONTINUE after `ElectionMembership` exists.**

**D-ENT-1 classification: all 12 rows `NOT AFFECTED`.**

| Rows | Evidence |
|---|---|
| 8 using `makeVoterMember` | organisation reference is **FK-only, at creation** |
| `non_member_sees_can_vote_false` (`:197` *"No ElectionMembership created"*) | asserts absence of **`ElectionMembership`**, not of organisation membership |
| `guest_is_redirected_to_login` · `unknown_slug_returns_404` · `demo_election_returns_404` · `renders_election_show_inertia_component` | no membership concern at all |

**Honest limit:** the **fixture** was read and the membership sites traced; **per-method business intent was NOT read for all 12.** The `D-ENT-1` classification rests on a class-level structural fact (FK-only organisation reference) that holds for every method — **but the 5 FAILURE rows' own failure mechanisms remain `MECHANISM NOT ESTABLISHED`**, and that is separate work.

### 🔴 Independent finding — recorded separately, and it does NOT change the classification

**The schema requires `(user_id, organisation_id)` in `user_organisation_roles` as a foreign key for `election_memberships`.**

> **So at the database level, an `ElectionMembership` cannot exist without an organisation-membership row.**

**That is potentially in tension with F1/Model B**, which hold that organisation membership is an **admission prerequisite, not continuously required for retaining the entitlement**. **If the FK prevents (or cascades) deletion of the organisation-membership row, the schema may enforce a continuity the adopted business rule says is not required.**

⚠️ **NOT a defect claim, and deliberately not investigated:** the FK's `ON DELETE` behaviour was **not checked**, so whether it blocks, cascades or nulls is **`MECHANISM NOT ESTABLISHED`**. **This is a question for whoever applies `ADR-002`, not a Session 1 finding to act on** — and it is exactly the kind of thing `D-APPLY`'s `V-1` (unratified termination rule) should consider.

**Second independent finding:** `:87-88` — *"Primary: set `has_voted` on `ElectionMembership` (**new single source of truth**)"*. **Corroborates B2's two-`has_voted` observation and names `ElectionMembership` as the intended SSOT** — while C8's one-vote gate keys on `codes.has_voted`. **Whether they can disagree remains NOT ESTABLISHED.**

**Status: 39-row verification 15 / 39. AFFECTED 0 · NOT AFFECTED 15 · UNDETERMINED 0. No new candidates outside the 39. Remaining: `StateMachine\CurrentBehaviorTest` (841 lines, 24 rows). L3 = 78 / 1,376.**

> **Two of three classes read; `D-ENT-1` has produced ZERO affected rows so far.** If the third also comes back clear, **`PBDIGIT-49`'s measured impact on the matrix would be nil** — which would be a substantive result, not an anticlimax: it would mean the estate never encoded the assumption `D-ENT-1` was convened to settle.

---

## 🏛️ `D-ENT-1` AMENDED BY THE PRODUCT OWNER — the adopted rule is hierarchical, and it changes the discriminator

**Adopted as external business authority (PO/ARB, 2026-08-12), recorded verbatim in effect:**

> **FULL MEMBERSHIP MODE:** Organisation Membership is **superior** to ElectionMembership. A person may be an ElectionMember **only while** an active Organisation Member. **If Organisation Membership is removed, the ElectionMembership is automatically suspended.** That automatic suspension is caused by the Organisation-Membership lifecycle and is **NOT** the same business operation as Election Chief suspension. The Election Chief may independently suspend for election-specific reasons. **Removing/suspending ElectionMembership must NOT remove Organisation Membership.** Organisation Membership is governed by the Organisation Chief; ElectionMembership by the Election context, subject to the superior prerequisite.
>
> **ELECTION-ONLY MODE:** no Organisation Member required; the cascade does not apply; Chief suspension remains independent.

**Two causes, one state — never collapsed:** `OrganisationMembershipRemoved → automatic suspension` (workflow **A**) ≠ `ElectionChiefSuspendedVoter` (workflow **B**).

### 1 · Obsolete `D-ENT-1` statements

| Superseded | By |
|---|---|
| *"Organisation membership is an admission prerequisite — **not a continuously evaluated prerequisite** for retaining the entitlement"* (F1 as handed over) | **too broad.** In Full Membership Mode the prerequisite **is continuous**, with a defined consequence: **automatic suspension**, not entitlement re-derivation |
| The discriminator *"…continuously required after ElectionMembership has been established"* | **amended:** a test is relevant if its assertion depends on **the consequence of organisation-membership loss after ElectionMembership exists** — in **either** direction |
| `D-ENT-1` as one universal rule | **two mode-specific rules** |

### 2 · What survives, what must be re-derived — measured, not assumed

| Population | Verdict |
|---|---|
| **Stage 1 exclusions (56 rows)** | ✅ **survive by entailment** — a test that never references organisation membership cannot assert the consequence of losing it |
| **The 15 read rows** (`LegacyVoteRouteTest` 3 · `ElectionShowControllerTest` 12) | ✅ **survive** — verified: none asserts loss consequences in either direction |
| **Stage 2 exclusions (118 rows)** | ⚠️ **filter invalidated in principle** — the exercise-token filter was derived from the OLD question. The NEW invariant (removal → auto-suspension) is not exercise-token-shaped. **Re-derived:** the new invariant requires org-membership *removal*, so candidates must sit in the 15 org-referencing classes. The suspension-relevant one is `ElectionVoterSuspensionTest` — **read in full** |
| **`ElectionVoterSuspensionTest` (7 rows)** | ✅ **NOT AFFECTED — it tests workflow B only** (Chief propose → second-officer confirm → cancel; proposer cannot self-confirm; voted voters cannot be proposed). `UserOrganisationRole` appears **only in fixture creation**. **Workflow A never occurs in it** |

### 3 · 🔴 The new invariant has ZERO in-universe coverage

**Measured:** no test in the 1,376-row universe **removes organisation membership** at all (`grep` for role deletion/detach across all five paths: **empty**).

> **Workflow A — the rule the Product Owner just adopted — is exercised by no test.** Coverage for *"organisation-membership removal automatically suspends the ElectionMembership"*: **ABSENT.** *(And whether production implements the cascade at all is `MECHANISM NOT ESTABLISHED` — the schema FK may even make removal impossible; see below.)*

**Adjacent observation from the read:** confirm-suspension sets `status='inactive'` **and** `suspension_status='confirmed'` — so **the record distinguishes suspension from other inactivity**; Session 2's indistinguishability finding concerned **the enforcing predicate**, and both can be true. **No cause distinction (A vs B) exists anywhere**, because A exists nowhere.

### 4 · The FK finding, reframed under the amended rule

Previously: the `election_memberships → user_organisation_roles` FK seemed **in tension** with F1. **Under the amended rule the direction now aligns** (org membership required while ElectionMembership exists) — **but the mechanism differs**: the business rule says **suspend on removal**; a restrictive FK would **prevent removal**, a cascading one would **delete** rather than suspend. **`ON DELETE` behaviour deliberately not checked, and per the PO's instruction no CASCADE/RESTRICT choice is made here.** Conformance investigation belongs after the rule is recorded — which it now is.

### 5 · Remaining ambiguities — put to the Product Owner before conformance work

| | Question |
|---|---|
| **Q-A1** | Does *"removed"* include **suspension** of Organisation Membership, or only removal? |
| **Q-A2** | Is automatic suspension **reversible** — does restoring Organisation Membership auto-lift it, or is re-admission required? |
| **Q-A3** | Timing: does automatic suspension take effect **during an open voting window**, and does it matter if the person **has already voted**? |
| **Q-A4** | In Election-Only Mode, does the Organisation Chief hold **any** authority over ElectionMembers? |

**Status: amended `D-ENT-1` recorded as external authority. 39-row verification stands at 15/39 with all classifications surviving. `ElectionVoterSuspensionTest` (7 rows) additionally read → NOT AFFECTED. New invariant coverage: ABSENT — recorded as a finding, not repaired. `ADR-002` unamended. No schema, code or test change. `CurrentBehaviorTest` (24 rows) remains, now to be read against the AMENDED rule.**

---

## 🏛️ Election-Only Mode recorded as external authority · phased delivery adopted

**PO decision (2026-08-12), recorded as authority, nothing inferred beyond it:** **ELECTION-ONLY MODE** — Organisation Membership **not required** · ElectionMembership is election-specific and governed by the **Election context** · **Election Chief** holds suspension/removal authority · **the Organisation Chief has NO authority over ElectionMembership merely because the person belongs to that organisation** · ElectionMembership operations must not remove or modify Organisation Membership.

**Delivery order adopted: PHASE 1 Election-Only → PHASE 2 Full Membership.** `Q-A1` (does org *suspension* cascade) · `Q-A2` (reversibility) · `Q-A3` (post-vote) are **Full-Membership concerns, deferred by the PO and not investigated.** **`Q-A4` answered by the decision above.**

**Workflow A's zero coverage is re-filed as a PHASE 2 FUTURE VERIFICATION REQUIREMENT. No test written.**

## 39-row verification — class 3 of 3: `StateMachine\CurrentBehaviorTest` (24 rows) → **39 / 39 COMPLETE**

**24 rows, all PASSED.** Three behavioural groups, read individually:

| Group | Rows | What is verified | Authority |
|---|---:|---|---|
| **Lifecycle derivation** | 11 | each state **derived from business facts** (`approved_at`, `setup_started_at`, nomination/voting windows, `published`, `rejected_at`, `archived_at`) | `ADR_20260807_1500` — *state column is a cache, not truth* |
| **Constitutional transitions** | 7 | `submit_for_approval · approve · begin_setup · complete_administration · open_voting · close_voting · publish_results` reach their `target_state` | **`ElectionConstitution::RULES`** — genuine business authority |
| **Capability snapshot** | 6 | `canVote` ×2 · `canEditTimeline` · `allowedActions` ×3 | `RULES` + engine contract |

**`D-ENT-1` (either variant): all 24 `NOT AFFECTED`.** Evidence: membership appears **once**, at `:526` under the comment **`// Add precondition: has_voters`** — an `ElectionMembership` row created to satisfy the **constitutional precondition** of `complete_administration`. **Nothing asserts a consequence of organisation-membership loss, in either direction.**

**Mode relevance:** **23 rows mode-INDEPENDENT** — lifecycle derivation and constitutional transitions do not depend on how a voter came to exist. **`test_complete_administration_transition` touches BOTH modes** via `has_voters`, since the two modes differ in *how* that precondition is satisfied.

**Corroboration of C8, independently arrived at:** both `canVote` rows construct an election with **no voter at all** and assert `$lifecycle->canVote()` on windows/locks alone. > **`ElectionLifecycle::canVote()` is authority over the VOTING WINDOW, not over voter entitlement** — exactly the C8 split, now measured in the estate rather than inferred from `ElectionVotingController`.

**Minor structural observation:** `test_submitted_for_approval_allowed_actions` asserts `allowedActions()` is **empty** and documents why — *approve/reject are platform-admin only, not tracked in lifecycle allowedActions*. **`allowedActions()` is chief-scoped, not a complete projection of `RULES`.** Not a defect; a scope fact worth knowing before it is used as one.

### 🔴 Conformance findings — mechanism OBSERVED, no mechanism CHOSEN

**Verified in `database/migrations/2026_03_17_213212_create_election_memberships_table.php`:**

```php
$table->uuid('organisation_id');                       // NOT NULL
// Ensures user_id + organisation_id exists in user_organisation_roles
$table->foreign(['user_id','organisation_id'])
      ->references(['user_id','organisation_id'])->on('user_organisation_roles')
      ->onDelete('cascade');                           // ← CASCADE
```

| # | Finding | Phase |
|---|---|---|
| **CF-1** | **The adopted rule says removal SUSPENDS. The schema CASCADE-DELETES.** A deleted row cannot be a suspended row — **and it carries away `has_voted`**, which the estate calls the *"new single source of truth"*. *(The anonymous ballot in `votes` survives, so a tally is unaffected; the record that the person voted does not.)* **This feeds the PO's deferred `Q-A3`; it does not answer it.** | **2** |
| **CF-2** | 🔴 **`organisation_id` is NOT NULL and the FK is enforced, so NO `ElectionMembership` can exist without a `user_organisation_roles` row.** **Election-Only Mode says organisation membership is not required — the schema currently requires it for every admission.** | **1 — NOW** |

**Per the PO's instruction, no `CASCADE`/`RESTRICT`/other mechanism is proposed, and nothing was changed.** Reporting that the existing mechanism is CASCADE, and that CASCADE does not produce the adopted outcome, is **conformance evidence, not a design choice.**

### ❓ Genuine Election-Only business question — STOPPING as instructed

| | Question |
|---|---|
| **Q-B1** | **In Election-Only Mode, does an ElectionMember stand in ANY data relationship to the organisation running the election?** The election belongs to an organisation, so `election_memberships.organisation_id` is populated — but **if satisfying the FK means writing a `user_organisation_roles` row, Election-Only participants become organisation members in the data**, which may grant organisation-level visibility the rule intends to withhold. **Either answer is a coherent product; engineering must not pick.** |

**Status: 🎯 39 / 39 COMPLETE — AFFECTED 0 · NOT AFFECTED 39 · UNDETERMINED 0.** Plus `ElectionVoterSuspensionTest` (7) from the re-derivation. **`PBDIGIT-49` / `D-ENT-1` — original or amended — has ZERO measured impact on the Master Matrix.** L3 = **109 / 1,376**. `ADR-002` unamended · no production, schema, migration, test or fixture change.

> **The estate never encoded the assumption `D-ENT-1` was convened to settle — so the decision changes no existing verification. It creates NEW verification obligations (workflow A, Phase 2) and exposes TWO conformance gaps, one of which is in PHASE 1's path.**

---

## ⛔ CORRECTION — `CF-1` and `CF-2` are WITHDRAWN. Both were wrong.

**I read the CREATE migration and reported its foreign key as the live schema without checking for later ALTERs.** There is one: **`2026_05_19_000001_harden_election_memberships_for_election_only_mode.php` (Phase C.1) DROPPED the composite FK** — for exactly the reason I "discovered":

> *"Drop composite FK to user_organisation_roles — **Root bug: FK blocks election-only mode** (users only have `OrganisationUser`, not `UserOrganisationRole`) — Application-level `VoterQualificationPolicy` takes over this validation."*

**Verified against the live database, not a file:**

```sql
SELECT conname FROM pg_constraint WHERE conrelid='election_memberships'::regclass AND contype='f';
  election_memberships_assigned_by_foreign
  election_memberships_election_id_organisation_id_foreign     -- and nothing else
```

| | Was reported | Actually |
|---|---|---|
| **`CF-2`** | *"no ElectionMembership can exist without a `user_organisation_roles` row — Election-Only admission blocked"* | 🔴 **WITHDRAWN. The FK does not exist. The schema does NOT block Election-Only admission** — and it was removed *deliberately, for this purpose,* in May |
| **`CF-1`** | *"the schema CASCADE-DELETES where the rule says suspend"* | 🔴 **WITHDRAWN. There is no cascade from organisation-membership deletion.** `onDelete('cascade')` existed only on the dropped FK; `down()` would restore it as `restrict` |

**Why I believed it:** the CREATE migration **and** `ElectionShowControllerTest`'s comment *(`// FK constraint: (user_id, organisation_id) must exist in user_organisation_roles`)* agreed. **They agreed because both are stale — the test comment describes a constraint dropped three months ago.** > **Two stale sources agreeing is not corroboration.** **Lesson recorded: a schema claim must be queried from the live database, never read from a migration file.** *(Same class as the earlier recursive-`find` misread: one source, structural claim, opposite conclusion.)*

**What survives the withdrawal:** **workflow A's coverage gap.** Its basis was never the FK — it was the measurement that **no test removes organisation membership at all.** That measurement stands. Indeed the withdrawal *strengthens* it: with no FK and no cascade, **workflow A has no database mechanism whatsoever** — it must be application-level, and nothing exercises it.

### 🔴 `CF-3` (new, and the real finding) — the FK's named replacement does not exist

The migration transferred integrity to the application and warned: *"**The database FK is gone. Integrity is now application responsibility.**"* — naming **`VoterQualificationPolicy`**.

```
find app -iname "*VoterQualification*"   →   (nothing)
```

> **`VoterQualificationPolicy` does not exist under that name.** **A structural guarantee was removed in favour of a named application-level policy, and the named artifact is absent.**

⚠️ **NOT claimed:** that the validation is absent. **`app/Domain/Election/Enum/VoterSourceStrategy.php` consumes `uses_full_membership` and is a strong candidate for discharging it.** **Whether it does is `MECHANISM NOT ESTABLISHED` — and it sits OUTSIDE the 1,376 universe** (SD-4A class B, *"voter source strategy"*, never read).

### `Q-B1` — the modes are already implemented; the PO still decides, but conformance may already hold

**OBSERVED IMPLEMENTATION, explicitly NOT promoted to business authority:**

| Concern | Table | Mode |
|---|---|---|
| Account/tenant association | **`organisation_users`** | what an **Election-Only** participant has *(per the migration's own words)* |
| **Organisation Membership** | **`members`** — with `membership_types`, `membership_fees`, `renewals`, `applications`, **`grants_voting_rights`** | **Full Membership** — *"members table with paid/exempt fees"* |
| Roles | `user_organisation_roles` | orthogonal to both |
| Mode switch | **`organisations.uses_full_membership`** (2026-04-15) — consumed by `VoterSourceStrategy`, `VoterImportController`, `OrganisationSettingsController` | both |

> **The implemented answer to `Q-B1` corresponds to the reviewer's OPTION B: an Election-Only participant holds an `organisation_users` association but is NOT a `Member` of the organisation.** **This does NOT settle `Q-B1`** — *"the code does it"* has never been allowed to mean *"the business requires it"* in this programme. **It means the PO's likely answer and the implemented reality appear to agree**, and Session 3 is not designing greenfield: **Election-Only Mode already exists in the schema with a designated mode switch.**

**Still not chosen by engineering, and now moot as posed:** nullable `organisation_id` · dropping the FK *(already dropped)* · a different association · auto-creating organisation membership. **Recorded so no session harvests a design from this report.**

**Status: `CF-1` WITHDRAWN · `CF-2` WITHDRAWN · `CF-3` OPEN · workflow A coverage gap STANDS (strengthened) · `Q-B1` still the PO's, with implemented evidence attached. 39/39 unaffected — no row classification depended on the FK. L3 = 109/1,376. No production, schema, migration, test or fixture change.**

---

## `CF-3` conformance investigation — **OUTCOME A · CONFIRMED**

**Withdrawals accepted and re-affirmed:** `CF-1` — no live cascade · `CF-2` — the FK was dropped in Phase C.1 *specifically* to support Election-Only Mode. **Neither the CREATE migration nor `ElectionShowControllerTest`'s FK comment may be cited as current schema behaviour again; both are stale.**

> **The responsibility IS discharged. `VoterQualificationPolicy` was a NAME that never shipped; the OBLIGATION shipped under a different name, in the right layer, wired to the only write path.**

### The traced chain — responsibility, not filename

```
Domain port          VoterEligibilityPolicy            isEligible() · qualifyingSubset()
                            ▲ implements
Infrastructure       EloquentVoterEligibilityQueryService
                            ▲ injected (AppServiceProvider)
Application  ┌── AssignVoterHandler:57   if (!policy->isEligible(…)) throw VoterNotEligibleException
             └── BulkAssignVotersHandler:56  $eligibleIds = policy->qualifyingSubset(…)
                            │ then, and only then
Infrastructure       EloquentVoterRepository::create() / ::bulkInsert()   ← the ONLY writes
```

**The migration's obligation, checked clause by clause against the implementation:**

| Migration requires | `EloquentVoterEligibilityQueryService` does | ✓ |
|---|---|---|
| *"users exist in `organisation_users` (election-only mode)"* | `DB::table('organisation_users')… where('status','active')` | ✅ **exact** |
| *"`members` table with paid/exempt fees (full membership)"* | `DB::table('members')` + `whereIn('fees_status',['paid','exempt'])` + `grants_voting_rights` + `membership_expires_at` + `whereNull('deleted_at')` | ✅ **exact, and stricter** |

**Write-path closure — the check that makes this a verdict rather than an impression:**

* **`grep -rnE "ElectionMembership::(create|updateOrCreate|insert)|= new ElectionMembership"` over `app/`, excluding the repository → ZERO other production writes.**
* **`VoterRepository` is referenced by exactly two handlers, and both gate on the policy first.** There is no ungated route to the table.
* **Layering is correct** — Domain port · Infrastructure implementation · **Application enforces** · Infrastructure persists. **Notably this is the OPPOSITE of the C8/C10 pattern**, where the decision sat in Interface code. **Admission is the programme's first observed example of a business decision in its proper layer.**

### ⚠️ Disclosure — my own slip, caught inside the investigation

My first pass reported `CodeController` as a second, **ungated** `ElectionMembership` write path. **It is not.** Line 690 is a *comment* reading *"…the **new ElectionMembership** voter-slug middleware"* — **my pattern `new ElectionMembership` matched prose.** Re-run word-boundary-safe: **CodeController performs no membership write.** > **Same class as the `@test`-matched-prose miscount: my greps locate reliably and attribute unreliably.** Caught before it entered a finding, and recorded rather than quietly dropped — had it stood, it would have manufactured a false integrity gap in **Phase 1's** path.

### Observations recorded, NOT defect claims

| | Observation |
|---|---|
| **O-1** | **The two gates have different failure semantics:** `AssignVoterHandler` **throws** `VoterNotEligibleException`; `BulkAssignVotersHandler` **silently filters** to the eligible subset. **Whether a bulk import must REPORT which people it skipped is a business question** — the enforcement is sound either way |
| **O-2** | `app/Services/VoterEligibilityService.php` also references the policy — **a possible second representation of the same decision** *(cf. the two `has_voted` columns)*. **Not traced; outside this commission** |
| **O-3** | `voter_source_strategy` is **snapshotted on the Election and immutable** — *"Elections own their voter participation rules; org mutations don't retroactively change them."* **That is a genuine, explicitly-stated business authority**, and it means a later mode switch cannot retroactively disenfranchise an existing election's voters |
| **O-4** | The enum's own docblocks mark the mode names **transitional, pending a Phase 4 governance-language review** — so **`ImportedVoterRegistry`/`MembershipRegistry` must not be treated as settled vocabulary** |

### 🔴 Boundary finding — the guarantee is verified OUTSIDE the programme's universe

| Test | Universe |
|---|---|
| `AssignVoterHandlerTest` · `BulkAssignVotersHandlerTest` · `EloquentVoterEligibilityQueryServiceTest` · `VoterEligibilityPolicyContractTest` | 🔴 **OUT ×4** |
| `ElectionMembershipInfrastructureTest` · `ElectionMembershipPersistenceTest` | IN ×2 |

> **All four tests of the ENFORCING components sit outside the 1,376-test universe; the two inside test persistence.** **The mechanism guaranteeing admission integrity is verified — but not by the population this programme adopted to verify Election correctness.** **Direct, measured evidence for `SD-4`** *(SD-4A class B, *"voter source strategy"*, predicted-relevant and now confirmed relevant).* **Not imported. `SD-1` unchanged.**

**Status: `CF-3` CONFIRMED (A) · `CF-1`/`CF-2` withdrawn · no `VoterQualificationPolicy` created · no schema, production, test or fixture change · nothing classified for Session 3. `Q-B1` remains the PO's; the implemented separation (`organisation_users` vs `members`) is now traced to the enforcing code, not just the table list. L3 = 109/1,376.**

---

## `SD-4` admission-boundary evidence produced · `CF-3` closure preserved · one overstatement corrected

**Correction accepted, and it was a real overstatement.** I wrote *"there is nothing to build here."* **What `CF-3` established is narrower: no missing admission-INTEGRITY mechanism exists to build.** **It did NOT establish that the Election-Only admission workflow is complete** — **`BR-1.12` is unresolved**, and the organisation-linkage behaviour still needs interpretation. **`CF-3` remains CLOSED/DISCHARGED and will not be reopened; `VoterQualificationPolicy` will not be created.**

**Full evidence: `docs/publicdigit/reviews/2026-08-12-sd4-admission-verification-boundary-evidence.md`.**

### 🔴 The measured headline

> **The adopted universe verifies the DEFERRED mode and excludes the mode being implemented FIRST.**

| | Rows | Universe | Mode |
|---|---:|---|---|
| `VoterEligibilityTest` | **27** | **IN** | 🔴 **Full Membership ONLY** — mode-token count: `election_only` **0**, full-membership **6**; all 27 names are fees/type/expiry/member-record concepts |
| `EloquentVoterEligibilityQueryServiceTest` | 8 | OUT | **both** — incl. the **only 4 Election-Only** eligibility rows anywhere |
| `BulkAssignVotersHandlerTest` · `AssignVoterHandlerTest` | 7 · 4 | OUT | enforcement wiring — **incl. the row that verifies `CF-3`'s filter-before-write guarantee** |
| `VoterEligibilityPolicyContractTest` | 3 | OUT | **structural — NOT capability boundary** (SD-4A class C; the boundary report's own §6 excludes it) |

**Boundary verdict: 19 of 22 are capability-boundary rows; 3 are excludable.** **Not duplicates of the 27** — they differ in **mode** *and* **level** (HTTP surface vs service). **`test_tenant_isolation_respected` is excluded from a universe adopted to verify Election correctness, though CLAUDE.md RULE 9 makes tenant isolation mandatory test territory.**

**Answer to the commission's question:** ✅ **Yes — *"Election test estate verification"* is misleading, specifically as to Election-Only admission and eligibility tenancy isolation.** **It is NOT misleading as to Full-Membership admission**, which the 27 rows do cover.

### Blast radius — and why nothing stops

| | Rows |
|---|---:|
| **Existing matrix rows blocked by `SD-4`** | 🔴 **0** |
| Rows re-classified if SD-4 excludes | **0** |
| Rows added if SD-4 includes | **+19 new** (and a new denominator, with 1,376 preserved) |

> **`SD-4` blocks the programme's CLAIM, not its CLASSIFICATION.** **"Important" is not "blocking."**

**`BR-1.12` (Session 2's open decision) — bounded, NOT inferred from production:** in-universe radius lies **entirely inside `VoterEligibilityTest`**, at most **~7 rows** that assert a created membership's resulting state; rejection rows are independent. **Exact count NOT ESTABLISHED** (that class is the deferred batch). **Recorded as `BUSINESS RULE NOT YET AUTHORIZED` — not classified against Option A or B.** The 27 rows carry **`DEFERRED — FULL MEMBERSHIP`**; their existence is **not** an Election-Only defect.

### Root cause of my `CF-1`/`CF-2` error, now fully traced

`VoterEligibilityTest:110` — *"seeds the `UserOrganisationRole` row **required by the election_memberships FK**"* — is the **third** stale reference to the FK dropped on 2026-05-19, with the CREATE migration and `ElectionShowControllerTest:54`. **Three stale artifacts agreeing is what made the wrong finding feel corroborated.** **Repository classification, not a defect. Not changed — Session 1 does not edit tests.**

**Status: `SD-4` evidence delivered, decision withheld from engineering · `SD-1` unchanged · 1,376 frozen · baseline NOT re-run · denominator NOT altered · L3 = 109/1,376 · no production, schema, migration, test or fixture change.**

**Next authority-settled batch:** B2 continues per-test — `ElectionPolicyStateAwareTest` (10) then `VotingButtonsStateMachineIntegrationTest` (9). **`VoterEligibilityTest` (27) stays deferred — now for a second, stronger reason: it is Full-Membership-only *and* `BR-1.12`-dependent.**

---

## B2 continued — `ElectionPolicyStateAwareTest` (10) · `VotingButtonsStateMachineIntegrationTest` (9) → **L3 = 128 / 1,376**

**Authority:** `ElectionConstitution::RULES` (C7 open · C9 close) + `ADR_20260807_1500` (state derived from facts). **`SD-1` unchanged · 1,376 frozen · nothing added · `VoterEligibilityTest` NOT touched.**

### 🔴 The batch-level finding: **6 of 19 row names misdescribe their own assertions**

| Row name claims | The assertion actually does |
|---|---|
| `..._for_officer` (×4) | **no actor participates** — `$this->officer` is created in `setUp` and never used; the assertion reads `snapshot->canEdit` |
| `test_view_results_allowed_in_results_state` | asserts `state->value === 'results_published'` — **the state it just set up.** Verifies derivation, **not result viewing** |
| `test_view_results_denied_before_publication` | asserts `canPublishResults === false` **and** `results_published_at === null` *(its own fixture)* — **publishing ≠ viewing** |
| `open_voting_transitions_from_nomination_to_voting` | asserted pre-state is **`ready_for_voting`**, not nomination |
| `close_voting_..._to_results_pending` | asserted post-state is **`counting`**; `results_pending` **is not one of the 12 `ElectionLifecycleState` members** |
| `open_voting_is_idempotent_with_concurrent_requests` | two **sequential** HTTP calls — **repeat-request safety is verified; concurrency is not** |

> **~32% of this batch's names overstate or misstate what is verified.** **Direct measured justification for the standing rule that a name is a candidate, never a classification.** **Consequence for C11: neither `view_results` row verifies whether results may be VIEWED before publication — a results-visibility gap concealed behind two PASSED rows.**

**`ElectionPolicyStateAwareTest`'s class name is also stale** — the docblock records migration *"from deprecated `allowsAction()`"*; it tests **`ElectionCapabilityResolver` snapshots**, not `ElectionPolicy`. Fixtures write `'state' => …` even though the resolver recomputes from facts — **inert, and one docblock says so**, but it invites a reader to think the column drives behaviour.

### Classification

| Rows | Verifies | Ownership | Shape |
|---:|---|---|---|
| **8** | `canEdit` ×5 · `canVote` ×3 derived from constitutional facts | Domain/Application (resolver) | **capability projection from facts — no actor, no authorisation** |
| **2** | state derivation + `canPublishResults` | Domain | 🔴 **name/assertion divergence — C11 viewing NOT verified** |
| **6** | open/close transitions · wrong-state rejection · double-close prevention | Domain + Application guard | genuine C7/C9 state-guard verification |
| **2** | `ElectionStateTransition.actor_id` = actor | Infrastructure | genuine audit verification — **relevant to `BD-1`** |
| **1** | repeat-request safety | Application | verified; **concurrency NOT verified** |

### 🔴 The FAILURE row — executed for evidence, and it is not what its name says

```
open_voting_rejects_if_missing_candidates
Failed asserting that 'Action 'open_voting' cannot proceed.
   Unmet preconditions: voting_window_defined' contains "candidates".
```

**`assertSessionHas('error')` PASSED.** The guard fired — but for **`voting_window_defined`**, never reaching any candidate check.

**OBSERVED FACTS:**
* `RULES['open_voting'].preconditions = ['voting_window_defined', 'timezone_set']` — **no candidate precondition.**
* `RULES['open_voting'].allowed_states = ['setup_nomination', 'ready_for_voting']` — **voting may be opened directly from `setup_nomination`.**
* `complete_nomination` is the **only** candidate-gated action (`has_approved_candidates`) — and its `target_state` is **`setup_nomination`**, i.e. **a self-transition**, so performing it is **not a state prerequisite for `open_voting`.**
* The four precondition names that exist anywhere: `has_approved_candidates` · `has_chief` · `has_posts` · `has_voters`.
* **`grep has_candidates` across all in-universe paths → nothing.**

**INTERPRETATION (not a conclusion):** the constitutional path does not appear to require any candidate before voting opens.
⚠️ **`MECHANISM NOT ESTABLISHED`:** whether `ConstitutionalTransitionGuard` or `Election::validateTransitionRules()` adds a candidate check beyond the Constitution — **not read for this action.** *(Previously observed: the guard is skipped when `isSystemTriggered()`.)*
**`IMPLEMENTATION DEFECT`: none demonstrated.** The precondition engine works and names its unmet precondition.
**`VERIFICATION GAP`:** the candidate-before-voting expectation is verified **nowhere**; the one row claiming it fails for an unrelated reason.

> ⚠️ **A trap worth naming: "fixing" this row by changing the expected string to `voting_window_defined` would make it green and permanently erase the only trace in the estate that anyone expected candidates to be required.** **The failing test is the evidence. It must not be greened.**

### ❓ Genuine business decision — STOPPING as instructed

| | Decision |
|---|---|
| **SD-14** | **May an election open voting with ZERO candidates?** The Constitution does not forbid it, and `open_voting` is reachable from `setup_nomination` without ever completing nomination. **Either answer is a coherent product** — an election with no candidates may be legitimate (a pure referendum) or an obvious defect. **Engineering must not decide, and must not add a precondition to the Constitution.** **Blast radius: 1 row** (the FAILURE); **0 PASSED rows change meaning** |

### `SD-4` amendment PREPARED, NOT EXECUTED — with one arithmetic caveat the decision needs

**If and only if `SD-4` Option A is approved, the sequence is: record amendment → define the universe → preserve 1,376 as historical → new manifest → execute the whole amended universe → reconcile listed = reported → record the new baseline → only then classify. The 1,376 baseline is never silently replaced.**

⚠️ **CAVEAT ON `1,395`.** **`1,376 + 19 = 1,395` is arithmetically right and conceptually incomplete.** **SD-4A found 155 out-of-universe capability-named FILES; I have examined 4.** **So 1,395 would be *"the old boundary plus one capability's worth of enforcement tests"* — NOT the business-capability boundary.** **If the programme then claimed to verify "the Election test estate", it would again claim more than it verifies — the precise failure this programme exists to prevent.** **Recommend the amendment record 1,395 as `admission-enforcement expansion`, explicitly NOT as the capability boundary, leaving ~151 files unexamined and stated as such.** *(Engineering states the consequence; the Product Owner chooses.)*

**Status: L3 = 128/1,376 · `SD-1` unchanged · denominator unchanged · baseline NOT re-run · `1,395` NOT adopted · `CF-3` closed · `D-ENT-1` not reopened · `ADR-002` untouched · `BR-1.12` not inferred · `VoterEligibilityTest` still `DEFERRED — FULL MEMBERSHIP · BR-1.12 DEPENDENT · NOT CLASSIFIED` · no production, schema, migration, test or fixture change.**

---

## B2 CONDITIONALLY CLOSED · `ConstitutionalTransitionGuardTest` (13) → **L3 = 141 / 1,376**

**B2 = 41 / 41 classifiable rows complete.** The remaining 27 (`VoterEligibilityTest`) stay **`DEFERRED — FULL MEMBERSHIP · BR-1.12 DEPENDENT · NOT CLASSIFIED`**. **B2 is conditionally closed on that deferral, not finished.**

### ✅ `SD-14`'s open mechanism question is now CLOSED — and the answer strengthens the decision request

**`ConstitutionalTransitionGuard::assertAllowed()` reads preconditions from ONE source:**

```php
:77   $preconditions = ElectionConstitution::getPreconditionsForAction($action);
:201  default => throw new \LogicException("Unknown precondition: {$condition}"),
```

> **`MECHANISM NOT ESTABLISHED` → `MECHANISM ESTABLISHED`: the guard adds NOTHING beyond the Constitution, and it throws on an unknown precondition name — so an unratified precondition cannot silently pass.**
>
> **Therefore CONFIRMED: no candidate requirement is enforced for `open_voting` anywhere on the constitutional path.** **`SD-14` is a genuinely unspecified business rule, not a mechanism I failed to find.**

### 🔴 A SECOND unratified precondition — the same pattern, a different action

`guard_validates_preconditions` **(FAILURE)** asserts `complete_administration` requires `has_posts` · `has_voters` · **`has_committee_members`**.

| | |
|---|---|
| **Constitution** | `preconditions => ['has_posts', 'has_voters', 'has_chief']` |
| **Asserted but absent** | 🔴 **`has_committee_members`** — not among the only four names that exist anywhere (`has_approved_candidates` · `has_chief` · `has_posts` · `has_voters`) |
| **And yet** | the action's own `description` reads *"Complete voter import **and committee setup**"* |

> **The Constitution's own description says committee setup is part of the action; its preconditions do not require it.** **Here the intent is documented in the Constitution itself — stronger evidence than `SD-14`, where only a test asserted the rule.**

**PATTERN OBSERVATION — 2 measured instances, deliberately NOT promoted:** two independent tests, two different actions, both asserting a precondition the Constitution lacks (`open_voting`→candidates · `complete_administration`→committee members); plus the earlier `complete_nomination` undocumented self-transition. **INTERPRETATION: the rule set appears narrower than the intent recorded in tests and in its own descriptions.** **Two instances is an observation, never a promoted finding** *(ES-006.1)*. **No rule invented, no precondition added.**

### ❓ `SD-15` — second business decision, stopping as instructed

| | Decision | Competing readings |
|---|---|---|
| **SD-15** | **Must an election have committee members before administration can be completed?** | **A · YES** → the precondition is missing from the Constitution and the test is right (governance change required, **ARB**) · **B · NO** → the test and the description are wrong (test + description correction) |
| **SD-14** *(restated with both readings, per commission)* | **May voting open with zero candidates?** | **A · NO** → candidates required; the Constitution lacks the rule · **B · YES** → legitimate (referendum/abstention); **the test expectation is wrong** |

**Blast radius: 1 row each. 2 rows of 1,376 total (0.15%). No PASSED row changes meaning. B2 and the matrix continue.**

### Classification of the 13 rows

| Rows | Verifies | Category |
|---:|---|---|
| **3** | undefined action throws · disallowed state throws · allowed state passes | genuine constitutional **state** authority |
| **3** | `guard_verifies_user_role` · **`only_chief_can_open_voting`** · `chief_and_deputy_can_administer` | genuine constitutional **role** authority |
| **4** | `suspend` from `voting_active` / not from `archived` · `resume` from `suspended` / not from `draft` | genuine **C14** state rules |
| **1** | `system_actions_allowed_without_authenticated_user` — `auto_submit` succeeds with `Auth::logout()` because `system` is a valid role | **intentional bypass, verified.** *(Closes my earlier `isSystemTriggered` concern: designed and tested, not a hole. Weak form — `assertTrue(true)`; the real assertion is that `assertAllowed` does not throw)* |
| **1** | `guard_is_injectable` | **structural/DI — not business behaviour** *(like `VoterEligibilityPolicyContractTest`)* |
| **1** | `guard_validates_preconditions` | 🔴 **BUSINESS RULE NOT SPECIFIED — `SD-15`** |

### ⚠️ Correction to my own earlier B2 finding — it was a NAMING problem, not a coverage hole

I recorded that `ElectionPolicyStateAwareTest`'s four `..._for_officer` rows verify **no actor**. **That stands as a fact about those rows** — **but I must not let it read as "role authority is unverified".** **`only_chief_can_open_voting`, `guard_verifies_user_role` and `chief_and_deputy_can_administer` verify role authority directly.** > **Constitutional role authority IS verified — just not in the rows whose names promised it.** **The finding is a naming/verification-shape defect, NOT a coverage gap.** *(Stating this prevents my own finding from being cited as a hole that does not exist.)*

**Status: L3 = 141/1,376 · B2 conditionally closed (41/41, 27 deferred) · `SD-14` mechanism CLOSED, decision OPEN · `SD-15` OPEN · `SD-1` unchanged · denominator unchanged · `1,395` NOT adopted · `1,376` frozen · baseline NOT re-run · no Constitution, production, schema, migration, test or fixture change · no failing test repaired.**

---

## `SD-14` CLOSED by adopted authority · `ElectionSuspensionTest` (26) → **L3 = 167 / 1,376**

### `SD-14` — consumed as adopted authority, with a polarity warning

**Found in the ARB ruling artifact `docs/publicdigit/reviews/2026-08-13-sd14-ruling-and-implementation-authorization-package.md`, whose disposition the record explicitly assigns to Session 1:**

> *"an election must have **at least one approved candidate** before voting may be opened; this closes `SD-14`; `ElectionConstitution` is the implementation home; **do not implement yet**."* → **Manifesto `EM-VOT-002` ADOPTED.**

⚠️ **POLARITY WARNING — recorded so no one is misled later.** The ruling is logged as **"`SD-14` = YES"**, but **I posed `SD-14` as *"MAY voting open with zero candidates?"*, for which the adopted answer is **NO***. **The substance is settled and unambiguous — candidates ARE required** — **but "SD-14 = YES" read against my phrasing means the opposite of the ruling.** **Always cite `EM-VOT-002`'s text, never the bare polarity.**

**Row disposition** (`open_voting_rejects_if_missing_candidates`, 1 row):

| Was | Now |
|---|---|
| `BUSINESS RULE NOT SPECIFIED` | ✅ **`BUSINESS RULE ADOPTED — EM-VOT-002`** |

**It becomes a legitimate RED test for an adopted-but-unimplemented rule.** **The Constitution still does not encode the precondition, and implementation is UNAUTHORISED** (*"do not implement yet"*). **It must NOT be greened** — and note that even after implementation its assertion would still fail, because its fixture never defines a voting window (`voting_window_defined` fires first). **Fixture work belongs to whoever implements `EM-VOT-002`, under strict TDD. Not Session 1's, and not now.**

### 🔴 My already-classified rows independently corroborate `PBDIGIT-64`

The authorization package records that **the computed/clock path BYPASSES the guarded transition** — an election reached voting candidate-less **without `open_voting` ever running**. **My own B-batch evidence proves this from inside the universe, and I had not connected it:**

> **`CurrentBehaviorTest::test_election_with_voting_window_open_derives_to_voting_active` (PASSED, classified earlier as lifecycle derivation) derives `voting_active` from THE WINDOW ALONE** — no action, no guard, no candidates. **It is the in-universe proof that `voting_active` is reachable without any constitutional action.**

**CONSEQUENCE for verification, stated as scope not as design:** **`EM-VOT-002` cannot be verified by testing `open_voting` alone.** Its verification obligation spans **the derivation engine** as well as the guarded action. **HOW to cover the computed path is Session 3's design question; Session 1 records only that a single-precondition test would be insufficient evidence of the rule.**

### `ElectionSuspensionTest` — 26 rows, all PASSED, and the estate's strongest-verified capability

**Authority: `RULES['suspend'|'resume']` (C14) + `ADR_20260807_1500`.** Six declared RED intents, all genuinely asserted:

| Rows | Verifies | Note |
|---:|---|---|
| **2** | context captured **for audit, not restoration**; **resume RE-DERIVES** | 🔑 `test_resume_does_not_restore_from_context_column` captures `VotingActive`, then after resume asserts **`Counting`** — the window elapsed during suspension, so re-derivation and restoration give **different** answers, and the test proves which one happens. **Genuine architectural invariant, correctly discriminated** |
| **1** | suspension **does not mutate business facts** — 7 before/after equalities | the invariant that makes derivation safe |
| **2** | flags set/cleared; engine derives `Suspended` | `resume` nulls all 5 suspension fields and stamps `resumed_at`/`resumed_by` |
| **4** | **role authority** — chief ✅ · platform_admin ✅ · **deputy ❌** · ordinary user ❌ | **matches `RULES['suspend'].allowed_roles` exactly** |
| **3** | governance metadata · audit record | ⚠️ `suspend_stores_governance_metadata` **and** `test_suspend_stores_governance_metadata` are **two rows asserting the same claim** — probable duplication. Observation, not defect |
| **4** | category valid/invalid · reason required/min-length | Interface validation |
| **3** | capability overlay short-circuits all but `resume`; blocked during suspension (**via `assertInertia`** — projection verified); full map restored after resume | |
| **3** | lifecycle position preserved across `setup_administration` · `setup_nomination` · `voting_active` | |
| **2** | suspension neither alters nor appears in lifecycle progression | |
| **1** | route redirect | |
| **1** | `suspend_fails_when_already_suspended` | idempotency guard |

### 🔴 Programme-level observation — verification depth runs OPPOSITE to outcome criticality

| Capability | Authority | Rows verifying it |
|---|---|---|
| **C14 suspension** | constitutional | **26 rows**, incl. derivation-vs-restoration and facts-immutability invariants |
| **C8 vote casting** | one-vote rule **unspecified** *(`SD-9`)* | anonymity tests sit **outside** the universe |
| **C10 counting** | 🔴 **no authority found at all** *(`SD-11`)* | inline `DB::table()` in a controller |

> **The estate verifies an operational control more rigorously than it verifies casting and counting a vote.** **OBSERVED FACT about the estate — not a criticism of the suspension work, which is exemplary, and not a defect claim.** **It is evidence for how `SD-4`, `SD-9` and `SD-11` should be prioritised.**

**Status: L3 = 167/1,376 · `SD-14` CLOSED (business rule adopted; implementation unauthorised; row kept RED) · `SD-15` OPEN · `SD-1` unchanged · `1,395` NOT adopted · `1,376` frozen · baseline NOT re-run · no Constitution, production, schema, migration, test or fixture change · no failing test repaired · nothing implemented.**

---

## `ElectionLifecycleFacadeTest` (19) → **L3 = 186 / 1,376**

**Wording corrected:** I wrote that covering the computed path *"is Session 3's design question."* **Session 3 already holds the `EM-VOT-002` implementation grant and has investigated that path.** **Session 1 records the verification obligation only:**

> **`EM-VOT-002` — *"an election must have at least one approved candidate before voting may be opened"* — requires verification on BOTH (1) the explicit `open_voting` command path and (2) the computed lifecycle path that derives `VotingActive` without invoking `open_voting`.** **Stated as obligation, not design. Session 3's implementation will NOT be consumed as business authority.**

### Neither non-passing row is a business question — so no decision is raised

**Both executed individually for evidence:**

| Row | Evidence | Category |
|---|---|---|
| `facade_guards_queries_against_deprecated_fields` **FAILURE** | *"Failed asserting that exception of type `App\Exceptions\DeprecatedQueryException` is thrown"* — `assertQueryAllowed(['status' => 'active'], …)` **does not throw**. The method exists; it does not reject `status` | **TACTICAL ARCHITECTURE.** The intent is **already adopted** — `ADR_20260807_1500`: the state/status column is a compatibility cache, not truth. **Adopted intent, absent enforcement.** **No business decision needed** |
| `facade_preserves_canTransitionTo_for_backward_compatibility` **ERROR** | *"Call to undefined method `ElectionLifecycle::canTransitionTo()`"* | **STALE TEST.** It asserts a backward-compatibility affordance **that does not exist**. **This row verifies nothing at all.** Not a business question, not an architecture question |

**Per the commission: the rule behind the FAILURE is already adopted, so it is classified against that authority rather than escalated. Nothing invented, nothing repaired.**

### 🔴 17 of 19 rows verify DELEGATION, not business behaviour

`canEdit`/`canVote`/`canManageVoters`/`canPublishResults` **delegate to snapshot** · `exposes` `allowedActions`/`blockedReason`/`isLocked`/`isTerminal`/`isVotingPhase`/`isInSetup`/`isActionAllowed`/`election` · returns current state · wraps election · accepts a pre-computed snapshot · usable in type hints · permits non-deprecated field queries.

> **These verify that the facade FORWARDS correctly — Application-layer plumbing.** **They would all still pass if every underlying business rule were wrong.** **Classified `STRUCTURAL / DELEGATION`, joining `guard_is_injectable` and `VoterEligibilityPolicyContractTest`.**
>
> **Consequence for `SD-4`: the adopted universe carries substantial STRUCTURAL mass** — a 19-row class contributing **2** rows of business-relevant verification (both non-passing) **and 17 of delegation.** **The denominator is not a measure of business coverage.** **Quantifying the structural share across all 1,376 is not yet done and is NOT claimed.**

### ⚠️ Measurement caveat discovered — per-row evidence depends on execution GROUPING

**Run as a pair, both rows ERRORed inside `RefreshDatabase` → `migrate:fresh` (`tests/TestCase.php:47`), never reaching their bodies — 0 assertions.** **Run individually, each produced its true result** (FAILURE with the real assertion · ERROR with the real cause).

> **The same two tests yield different outcomes depending on how they are grouped**, because `TestCase` skips transaction isolation for pgsql and re-migrates. **Methodological consequence: per-row evidence must come from an individual run or from the full-run baseline — NEVER from an arbitrary `--filter` subset.** **Recorded before it corrupted a classification; had I accepted the paired run, I would have recorded two ERRORs and missed both real causes.**

### Universe-integrity note — the 1,376 is a SNAPSHOT, and `tests/` has moved

**Session 3 has added untracked test files** (`EmVot002ApprovedCandidateBeforeVotingTest` · `EmVot002OpenVotingPreconditionTest` · `ElectionOnlyEntitlementPinTest`). **They are OUTSIDE the 1,376 universe and the denominator is unaffected — it is frozen by contract.** **But the universe no longer equals the contents of `tests/`.** **Stated precisely so no future run mistakes a changed tree for a changed denominator** — the 826/1,376 defect began exactly that way. **No production file is modified in the tree; execution evidence gathered above remains valid.**

**Status: L3 = 186/1,376 · `SD-15` OPEN (the only outstanding decision from this stream's batches) · `SD-14` closed · `SD-1` unchanged · `1,395` NOT adopted · `1,376` frozen · baseline NOT re-run · Session 3's implementation NOT consumed as authority · no Constitution, production, schema, migration, test or fixture change · no failing test repaired.**

---

## `ElectionCreationTest` (25, all PASSED) → **L3 = 211 / 1,376**

**C1 · creation & configuration.** ⚠️ **These 25 rows are IN the adopted universe but PROPOSED FOR EXCLUSION from the capability boundary** (boundary report §6: *"pre-constitutional; a misconfigured election cannot yet affect an outcome"*). **`SD-8` unresolved. Blast radius of `SD-8`: 25 rows' BOUNDARY MEMBERSHIP — 0 rows' CLASSIFICATION.**

### 🔑 C1 has an authority home, and it is NOT the Constitution

```php
// test_policy_only_allows_owner_and_admin_to_create
$this->assertTrue ($this->owner       ->can('create', [Election::class, $this->org]));
$this->assertTrue ($this->admin       ->can('create', [Election::class, $this->org]));
$this->assertFalse($this->chief       ->can('create', [Election::class, $this->org]));
$this->assertFalse($this->deputy      ->can('create', [Election::class, $this->org]));
$this->assertFalse($this->commissioner->can('create', [Election::class, $this->org]));
$this->assertFalse($this->regularMember->can('create', [Election::class, $this->org]));
```

> **A real and non-obvious business rule, genuinely verified: ELECTION OFFICERS CANNOT CREATE ELECTIONS.** Chief, deputy and commissioner are all denied; only **organisation owner/admin** may create. **Authority home: a Laravel Policy, not `ElectionConstitution`.**
>
> **This refines the boundary report: *"pre-constitutional" ≠ "ungoverned".*** **C1's ownership was recorded as *"Application"*; the evidence says **Policy/Authorization**, and it is the estate's clearest authorization verification so far** (8 of 25 rows, including one tenancy row).

### 🔴 Two more name/intent divergences — and the decision touches the NAME, not the assertion

| Row | Asserts |
|---|---|
| `test_small_election_saves_expected_voter_count_for_auto_approval` | **only** `assertDatabaseHas('elections', ['expected_voter_count' => 35])` |
| `test_large_election_saves_expected_voter_count_for_manual_review` | **only** `… ['expected_voter_count' => 100]` |

**Neither asserts any approval routing.** **So the capacity-based auto-approval / manual-review capability their names describe is NOT verified** — divergences **7 and 8** in this programme.

⚠️ **A precise consequence for Session 2's open `EM-OPEN-019` (threshold 30 vs 40):** the row uses **35**, and calls it *"small / auto-approval"*. **35 is between the two candidate thresholds.**

> **`EM-OPEN-019` blocks NEITHER row — the assertions are pure persistence, so they hold under either threshold. But if the threshold is 30, this row's NAME becomes false** (35 would be large / manual review). **Blast radius: 0 rows blocked; 1 row's NAME made misleading.** **A test-hygiene consequence of a business decision, not a classification dependency — recorded, not repaired.**

### Classification of the 25

| Rows | Verifies | Category |
|---:|---|---|
| **8** | owner/admin may create · chief/deputy/commissioner/regular member may not · policy directly · **officer from a different org may not** | ✅ **genuine authorization + tenancy** |
| **7** | name required · voter count required · count ≥ 1 · description optional · **name unique within org** · **same name allowed in a different org** · **demo type cannot be submitted** | input validation + **2 tenancy** |
| **6** | draft state on creation ×2 · null dates · slug generated · `type` always `real` · count persisted | creation invariants |
| **2** | capacity persistence | 🔴 **named for approval routing they do not verify** |
| **2** | timezone field renders · success flash | projection/UI |

⚠️ **`test_draft_election_state_is_draft` and `test_election_defaults_to_draft_state` appear to assert the same claim** — probable duplication, the second such pair *(cf. the suspension metadata pair)*. **Observation, not defect.**

### Cross-references recorded, not resolved

* **`test_election_type_is_always_real` + `test_cannot_submit_demo_type`** → **demo elections cannot be created through the HTTP path.** **Bears on `SD-10`** (*may a demo election accept repeat votes?* — the one-vote gate is `type === 'real'`): **the demo population originates elsewhere (`demo:setup`), so `SD-10`'s subject is not reachable through the verified creation path.** **Recorded as a cross-reference; `SD-10` remains the Product Owner's.**
* **Tenancy verification balance:** C1 has **3 in-universe tenancy rows**, while C6's eligibility tenancy row sits **outside** the universe *(SD-4 evidence §2)*. **Tenancy is not uniformly excluded — it is excluded for eligibility specifically.** **A precision that strengthens the SD-4 evidence rather than restating it.**

**Status: L3 = 211/1,376 (15.3%) · `SD-15` OPEN · `SD-8` affects 25 rows' membership only · `EM-OPEN-019` blocks 0 rows · `SD-1` unchanged · `1,395` NOT adopted · `1,376` frozen · Session 3's implementation and new tests NOT consumed · no Constitution, production, schema, migration, test or fixture change.**

---

## `Unit\Domain\Election\TransitionTest` (14, all PASSED) → **L3 = 225 / 1,376**

**A pure-PHP Domain value-object test** (plain `PHPUnit\Framework\TestCase`, no Laravel) — **compliant with the project's Domain-layer rule, and the batch's counterpoint to the facade's 17 delegation rows: every row here asserts a real invariant.**

| Rows | Verifies |
|---:|---|
| **4** | **immutability** — `readonly` properties reject modification · `withMetadata()` returns a NEW instance · metadata chainable · `getMetadata()` returns value-or-default |
| **4** | **factory semantics** — `manual()` sets `MANUAL` · `automatic()` sets the system actor · custom trigger · grace-period trigger |
| **3** | **actor normalisation** — int cast to string · **null defaults to `'system'`** · trigger defaults to `MANUAL` |
| **2** | `isSystemTriggered()` true for a system actor · trigger value accessible |
| **1** | **empty action throws `InvalidArgumentException`** |

### 🔑 These rows are security-load-bearing, and I traced what they gate

```php
Transition.php:24   $this->actorId = (string) ($actorId ?? 'system');
Transition.php:72   public function isSystemTriggered(): bool { return $this->actorId === 'system'; }
Election.php:1651   if (! $transition->isSystemTriggered()) { $guard->assertAllowed(…); }
```

> **`isSystemTriggered()` is what decides whether the CONSTITUTIONAL GUARD runs at all.** The 14 rows verify the mechanism that controls constitutional enforcement — **this is the most consequential value object the programme has classified.**

**ARCHITECTURAL OBSERVATION — latent hazard, NOT a defect, and NOT live:**

**The bypass keys on `actorId === 'system'` — a string sentinel with a silent null default — rather than on the `trigger` enum that exists precisely to express intent.** **Consequence: `Transition::manual('open_voting', 'system')` would be MANUAL by trigger yet system by actor, and would bypass the guard.**

**Measured, before claiming anything:**

| Check | Result |
|---|---|
| Direct `new Transition(` in `app/` | **ZERO** — every production path goes through a factory |
| `Transition::manual(…)` with `'system'` | **ZERO** |
| `manual()`'s signature | `string\|int $actorId` — **non-nullable, so null cannot silently reach the bypass** |
| `automatic()` | passes `'system'` **explicitly** — an intentional, visible bypass |

> **CONCLUSION: the hazard is LATENT, not live.** **No production caller can reach the bypass implicitly.** **Recorded as an architectural observation for whoever owns the state machine — not a defect claim, not a business question, and nothing to change.** *(I nearly reported a live bypass; the four checks above closed it. Stating the checks, not just the conclusion.)*

### ⚠️ A bounded verification-gap question — the MECHANISM is verified, the CONSEQUENCE may not be

**These 14 rows verify that `isSystemTriggered()` returns the right boolean. No row here verifies what that boolean CAUSES** — that a system-triggered transition **skips `assertAllowed`**.

**And the nearest candidate does not cover it:** `ConstitutionalTransitionGuardTest::system_actions_allowed_without_authenticated_user` calls `$this->guard->assertAllowed(…)` **directly**, proving the `system` **ROLE** is accepted — **it never exercises `Election::transitionTo()`'s bypass branch at all.**

> **`NOT ESTABLISHED`: whether any in-universe row verifies that the bypass branch behaves as intended.** **Bounded honestly — I checked the two obvious candidate classes, not all 1,376 rows.** **This is a candidate gap for a later batch, NOT a finding.**

**Status: L3 = 225/1,376 (16.4%) · `SD-15` OPEN (only open decision from this stream) · `SD-1` unchanged · `1,395` NOT adopted · `1,376` frozen · Session 3's implementation and new tests NOT consumed · no Constitution, production, schema, migration, test or fixture change · nothing repaired.**

> **Progress-number discipline restated, since the figure is now sizeable: `225 / 1,376` means *"225 rows have been read and classified"* — it does NOT mean 16.4% of the Election is verified.** **This batch's 14 rows carry more business weight than the facade's 19; the count cannot express that, which is precisely why the matrix records SHAPE alongside status.**

---

## `ElectionTransitionToMethodTest` (15) → **L3 = 240 / 1,376** · the bypass-branch question answered · **S5 discovered**

### ⛔ TWO corrections to my own tooling, both caught inside this batch

1. **A narrow grep produced a false claim.** I swept for `Transition::automatic(|isSystemTriggered` and reported *"`TransitionTest` is the ONLY in-universe file touching the system path."* **Adding `auto_submit` to the pattern found SEVEN more in-universe files** — including one named **`ConstitutionalTransitionGuardSystemRoleBypassTest`**. **My conclusion was an artifact of my pattern, not a fact about the estate.**
2. **My universe classifier mislabelled a file.** It printed `tests/Architecture/ElectionStateMachineConsistencyTest.php` as **`[OUT]`** — but that single file **IS the fifth adopted path**, i.e. **IN**. My `case` statement omitted it.

> **The standing pattern holds and is now at five instances: my greps LOCATE reliably and ATTRIBUTE unreliably.** **Both slips were caught before entering a finding — the first would have recorded a verification gap that does not exist.**

### Bypass-branch verdict: **C · NOT ESTABLISHED** — with the argument, not an impression

**The branch requires BOTH (a) a system-triggered `Transition` AND (b) that it be passed through `Election::transitionTo()`.**

| Evidence | |
|---|---|
| `Transition::automatic(` in-universe | **only `TransitionTest`** — which never calls `transitionTo()` |
| The seven system-role in-universe files | **all call `$guard->assertAllowed(…)` DIRECTLY**, or concern capabilities/capacity — **none routes through `transitionTo()`** |
| `ElectionTransitionToMethodTest` itself | **zero** `automatic` / `isSystemTriggered` / `'system'` tokens |

> **Verdict C: no in-universe row exercises `Election.php:1651`'s `if (! $transition->isSystemTriggered())` branch.** **NOT D — no defect is demonstrated; the branch is intentional and, as measured earlier, not implicitly reachable in production.**
>
> ⚠️ **Residual, stated rather than glossed: I swept `app/` for `manual(…, 'system')` but did NOT sweep `tests/`.** **The verdict is C on the evidence gathered, not a proof of absence.**

### 🔴 The batch's real finding — **S5: a privilege escalation, documented, fixed, and regression-guarded**

**`ConstitutionalTransitionGuardSystemRoleBypassTest` records verbatim:**

> *"`userHasAnyRole()` returns true unconditionally when `'system'` is the sole required role — `Auth::user()` is never consulted. **Any authenticated human making an HTTP request can call `auto_submit` (draft → approved) without officer knowledge.**"*

**That is a privilege escalation on `C2` (platform approval): a human could move an election `draft → approved` through a route reserved for queue jobs.**

**Fix state — verified in the source, not assumed:**

```php
if (in_array('system', $requiredRoles, true)) {
    if (count($requiredRoles) === 1) {
        return Auth::user() === null;          // ← S5 fix APPLIED
    }
    $userRoles = array_diff($requiredRoles, ['system']);   // multi-role case also handled
    if (empty($userRoles)) { return Auth::user() === null; }
    $requiredRoles = $userRoles;
}
```

| Category | |
|---|---|
| **IMPLEMENTATION DEFECT** | ✅ **real, and CLOSED** — the prescribed fix is present, including correct multi-role handling |
| **TEST VERIFICATION** | ✅ **in-universe regression guard exists** and passes |
| **Business rule** | not affected — `auto_submit`'s `allowed_roles: ['system']` was always the rule; the guard failed to enforce it |

> **This is the programme's first encounter with a genuine security defect that was found, fixed AND regression-guarded — the estate's healthiest pattern so far, and worth recording as such rather than only recording gaps.**

### Classification of the 15 rows (all PASSED)

| Rows | Verifies |
|---:|---|
| **2** | 🔑 `missing_timezone_rejects_open_voting` · `missing_voting_window_rejects_open_voting` — **`open_voting`'s TWO ACTUAL constitutional preconditions.** **Directly relevant to `EM-VOT-002`: the existing preconditions ARE verified in-universe; the newly adopted candidate precondition is not — which is expected, since implementation is Session 3's in-flight work** |
| **2** | `deputy_cannot_perform_chief_only_actions` · `unauthenticated_user_cannot_perform_user_role_actions` — role authority |
| **3** | invalid transition throws · valid target reached · **`cannot_open_voting_twice`** |
| **2** | immutable audit record · `ElectionStateChanged` event dispatched |
| **2** | `transition_uses_database_transaction` · **`rollback_on_validation_failure_restores_original_flags`** — transactional integrity |
| **2** | voting locked immediately · `nomination_completed` set — side effects |
| **2** | **cache-lock contention blocked · lock released after success** |

**Contrast worth recording on the two "concurrent" rows in this programme:** this class **pre-acquires `Cache::lock("election_transition:{id}")` and then calls `transitionTo()`** — a legitimate single-process way to exercise the lock guard. **The earlier `open_voting_is_idempotent_with_concurrent_requests` made two sequential HTTP calls with no lock manipulation.** > **Same word in two names; one row genuinely verifies the lock, the other verifies only repeat-safety.**

**Status: L3 = 240/1,376 (17.4%) · bypass branch **C · NOT ESTABLISHED** (candidate gap, not a defect) · **S5 closed** with a regression guard · `SD-15` OPEN · `SD-1` unchanged · `1,395` NOT adopted · `1,376` frozen · Session 3's implementation and new tests NOT consumed · no Constitution, production, schema, migration, test or fixture change · nothing repaired.**
