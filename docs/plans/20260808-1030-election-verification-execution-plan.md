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
