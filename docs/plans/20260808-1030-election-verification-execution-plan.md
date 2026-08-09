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
