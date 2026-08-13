# Election-Only independent verification — **P0: `EM-VOT-002` / `PBDIGIT-64`**

**Commission:** Product Owner · Session 1 · **independent verification; no repair, no implementation, no disposition executed**
**Date:** 2026-08-13 · **Verified against:** `f2c2cc4e` *(PBDIGIT-64)* · **Baseline:** frozen 1,376 · **L3:** 240/1,376

> **Session 3's GREEN report was NOT consumed. The code was read, the predicates compared, and an independently chosen regression target was executed.**

---

## 1 · Verdict up front

| | |
|---|---|
| **`EM-VOT-002` enforcement** | ✅ **VERIFIED on BOTH paths** |
| **New lifecycle state invented?** | ✅ **NO** — `EM-OPEN-021` not pre-empted by a chosen state |
| 🔴 **Unreported consequence** | **The fall-through terminates in `InvalidElectionStateException`. 4 in-universe rows regressed from a 24/24 PASSED baseline (2 ERROR · 2 FAILURE), and Session 3's commit reports only its own "8/8"** |

## 2 · What I verified, and how

**Both enforcement points use the SAME predicate — compared side by side, not assumed:**

```php
// command path — ConstitutionalTransitionGuard::isPreconditionMet
'has_approved_candidates' => $election->candidacies()->where('status','approved')->exists(),

// computed path — ElectionLifecycleEngineImpl::hasCandidatesApproved
return $election->candidacies()->where('status','approved')->exists();
```

✅ **Both require `status = 'approved'`, so a PENDING or DRAFT candidacy does NOT satisfy the rule.** **The distinction the commission warned about — *candidate exists* vs *approved candidate exists* — is correctly implemented in both places.**

**Command path:** `RULES['open_voting'].preconditions` = `['voting_window_defined','timezone_set','has_approved_candidates']`.
**Computed path:** `if ($this->isVotingWindowOpenNow($election) && $this->hasCandidatesApproved($election))`.

**`EM-OPEN-021` structurally untouched:** the commit does not modify `ElectionLifecycleState`, which still carries **exactly the 12 members** recorded at programme start. **No substitute state was introduced.**

## 3 · 🔴 The finding — falling through does not land safely

**Independently chosen target:** `CurrentBehaviorTest` — because **I had myself identified `test_election_with_voting_window_open_derives_to_voting_active` as the in-universe proof that `voting_active` is reachable with no action and no candidates.** **If EM-VOT-002 is enforced on the computed path, that row must change behaviour.** It did.

| Baseline | Now |
|---|---|
| **24 / 24 PASSED** | **20 PASSED · 2 ERROR · 2 FAILURE** |

```
1) test_election_with_voting_window_open_derives_to_voting_active
   InvalidElectionStateException: Election … in invalid constitutional state.
   Check: approved_at=…, setup_started_at=…, administration_completed=1
   at ElectionLifecycleEngineImpl.php:164

2) test_close_voting_transition            ← same exception
3) test_voting_active_can_vote             Failed asserting that false is true
4) test_voting_active_allowed_actions      array does not contain 'close_voting'
```

**Session 3's commit states:** *"when unmet, no substitute state is chosen here: derivation falls through to the existing rules below (fallback semantics remain an open PO decision, EM-OPEN-021 untouched)."*

> **Declining to CHOOSE a state is correct governance. But the fall-through does not reach a safe default — it reaches a TERMINAL THROW at `ElectionLifecycleEngineImpl:164`.**
>
> 🔴 **So the de facto answer to `EM-OPEN-021` is already observable: an election with an OPEN VOTING WINDOW and ZERO APPROVED CANDIDATES has NO DERIVABLE STATE and raises `InvalidElectionStateException`.** **That is a behaviour, not an absence of one.**

### Why row 2 is the operationally serious one

**`test_close_voting_transition` now ERRORS.** Since lifecycle state is derived on **every** read, an election in this condition **cannot have its state resolved at all** — which plausibly reaches capability resolution, dashboards, and `close_voting` itself.

> ⚠️ **HYPOTHESIS, NOT ESTABLISHED: such an election may be UNMANAGEABLE — unable even to close voting, because derivation throws before any action can be evaluated.** **I have not traced every consumer, so this is a hypothesis carrying real operational risk, not a demonstrated defect.** **It is the single item I would put in front of the Product Owner first.**

## 4 · Classification, kept separate

| Category | Finding |
|---|---|
| **VERIFIED BUSINESS RULE** | `EM-VOT-002` enforced on both paths, approval-status-correct |
| **OBSERVED IMPLEMENTATION** | fall-through → `InvalidElectionStateException` |
| **TEST VERIFICATION** | 2 of the 4 regressions (`can_vote`, `allowed_actions`) are **arguably CORRECT new behaviour** — a candidate-less election *should not* be votable. **Their fixtures are now obsolete, which is Mission 9's LEGITIMATE category** |
| 🔴 **UNREPORTED REGRESSION** | **4 in-universe rows** moved off a 24/24 baseline; the commit reports only its own 8/8. **Session 3 appears not to have run the in-universe regression** |
| **BUSINESS DECISION — now URGENT** | **`EM-OPEN-021`.** Previously deferrable; the fall-through gives it an observable answer (*throw*), so deferral is no longer neutral |
| **IMPLEMENTATION DEFECT** | **NOT CLAIMED.** Whether "throw" is wrong depends on `EM-OPEN-021`, which is the PO's |

## 5 · What I did NOT do

**No production, Constitution, schema, migration, fixture or test change · no test greened, renamed or deleted · `EM-OPEN-021` not decided · no fallback state proposed · no fixture repaired · 1,376 unchanged · `L3` unchanged at 240 (verification is not classification) · Session 3 not authorised or blocked by me.**

## 6 · Evidence still required for a full readiness verdict

**This document covers P0 only.** Not yet verified: **P1** entitlement (`PBDIGIT-65`/`69`) · **P1** `PBDIGIT-62` · **P1** Election-Only lifecycle map · **P2** ballot/submission/one-vote · **P2** results/publication. **Sections 5–18 of the commissioned structure remain unpopulated and are NOT implied by section 1's verdict.**

**Also unmeasured: the FULL in-universe regression.** **I ran one independently chosen class, not 1,376 rows.** **The true regression count is `NOT ESTABLISHED` and may exceed 4** — every class asserting a window-open election without an approved candidate is a candidate.

## 7 · Next action, by session

| Session | Action |
|---|---|
| **Product Owner / ARB** | **`EM-OPEN-021` — now urgent.** What state applies when the window is open with zero approved candidates? *(Also still open: `SD-15` · `BR-1.12` · `SD-4` · `EM-OPEN-019`, whose implemented threshold is observably **40** — `capacity_eligibility`, *"Free plan (≤40 voters)"*)* |
| **Session 3** | run the **in-universe regression**, then classify each affected fixture as legitimately-obsolete vs merely-failing — **per Mission 9, not by adding data until green** |
| **Session 1** | measure the full regression count; then P1 |

---

**P0 VERIFICATION COMPLETE — `EM-VOT-002` ENFORCED ON BOTH PATHS · ONE UNREPORTED CONSEQUENCE ESCALATED**
**`EM-OPEN-021` UNRESOLVED AND NO LONGER NEUTRAL · NOTHING REPAIRED**

**Traceability:** `f2c2cc4e` · `ConstitutionalTransitionGuard::isPreconditionMet` · `ElectionLifecycleEngineImpl::hasCandidatesApproved` · `:164` throw · `ElectionConstitution::RULES['open_voting']` · `ElectionLifecycleState` (12 members, unmodified) · `CurrentBehaviorTest` baseline 24/24 (matrix) vs measured 20/2/2 · EM-VOT-002 ruling artifact · disposition register `2026-08-13-test-estate-disposition-candidates.md`

---

# 8 · Lifecycle trace of the anomalous state — **the dead-end is TEMPORARY, and the mechanism is a pre-existing gap**

**Wording corrected, per the Product Owner.** I previously wrote *"`EM-OPEN-021` already has an observable answer."* **Replaced by the precise formulation:**

> **Current implementation behaviour is observable, but the intended business lifecycle semantics remain unresolved.**

**Two different questions, kept apart and NOT merged:**

| | Question | Answer |
|---|---|---|
| **`EM-VOT-002`** | Can an election become `VotingActive` without an approved candidate? | ✅ **NO — verified, both paths** |
| **`EM-OPEN-021`** | What is the legitimate lifecycle MEANING of an election whose window is open but which cannot satisfy that invariant? | ❓ **NOT YET DECIDED** |

## 8.1 · The exact fall-through — traced rule by rule

**State: `approved_at` set · `setup_started_at` set · `administration_completed` · `nomination_completed` · window OPEN · ZERO approved candidates.**

| Rule | Outcome |
|---|---|
| 1 `suspended_at` | skip |
| 2 `archived_at` | skip |
| 3 `results_published_at` | skip |
| 4 `now >= voting_ends_at` → **Counting** | **skip — window still open** |
| 5 window open **&& hasCandidatesApproved** | 🔑 **skip — the `EM-VOT-002` guard** |
| 6 `administration_completed && nomination_completed` | ⚠️ **CONDITION MATCHES, BUT RETURNS NOTHING.** Its two inner branches are *"window not yet opened"* → `ReadyForVoting`, and *"window undefined"* → `SetupNomination`. **Neither applies to an OPEN window, so control falls out of a matched branch** |
| 7 | skip — `nomination_completed` is true |
| 8 | skip — `administration_completed` is true |
| 9 | skip — `setup_started_at` is not null |
| 10 · 11 · 12 | skip — already approved |
| → | 🔴 **`InvalidElectionStateException`** |

> **DIAGNOSIS: this is NOT a new defect introduced by `EM-VOT-002`. Rule 6 has always lacked a branch for *"setup complete AND window open"* — because rule 5 unconditionally caught every open window, that gap was UNREACHABLE. `EM-VOT-002` made it reachable.** **A pre-existing latent gap, newly exposed — which is a materially different finding from a regression, and matters for how it is dispositioned.**

## 8.2 · Operation matrix for the anomalous state

**One mechanism governs almost all of it:** `Election::transitionTo()` computes `ElectionLifecycle::of($e)->snapshot()` **unconditionally, before** the system-trigger check — so **any** action that routes through `transitionTo()` derives state first and therefore throws.

| Operation | Available? |
|---|---|
| `getState()` · `snapshot()` · `canVote` · `allowedActions` · `isLocked` | 🔴 **THROW** |
| `open_voting` | 🔴 THROW *(and would be refused anyway — the precondition is correctly unmet)* |
| **`close_voting`** | 🔴 **THROW** — measured: `test_close_voting_transition` ERRORs |
| **`suspend`** | 🔴 **THROW.** ⚠️ **Note the asymmetry: rule 1 checks `suspended_at` FIRST, so an ALREADY-suspended election derives cleanly — but BECOMING suspended requires a transition, which derives state first.** **Suspension is an escape hatch you cannot reach from inside.** |
| `resume` · `archive` · `publish_results` | 🔴 THROW — same mechanism |
| Any consumer of the lifecycle facade *(controllers · middleware · dashboards)* | 🔴 **THROW** — `NOT individually traced`, inferred from the shared `getState()` dependency |

## 8.3 · Recoverability — **TWO exits exist, so this is a temporary trap, not a permanent one**

| Exit | Mechanism | Confidence |
|---|---|---|
| **1 · The clock** | When `now >= voting_ends_at`, **rule 4 returns `Counting`** *(setup was completed legitimately, so its inner check passes)*. **The election becomes manageable again with no intervention.** | ✅ **traced in code** |
| **2 · Approve a candidacy** | One approved candidacy makes rule 5 match, restoring `VotingActive` immediately | ⚠️ **CONDITIONAL — `NOT ESTABLISHED` whether the candidate-approval path can execute without deriving lifecycle state.** If approval routes through a controller that reads the snapshot, this exit is also blocked, and only the clock remains |

> **So the maximum extent of the trap is the interval `[voting_starts_at, voting_ends_at)`.** **REVISION of my earlier hypothesis: I flagged a possible permanent *"lifecycle dead-end"*. The trace shows it is TIME-BOUNDED — bounded by the configured voting window.** **Still operationally serious for a live election — an administrator could be locked out of their own election for the entire voting period, unable even to suspend it — but *"unrecoverable"* was too strong, and I withdraw it.**

## 8.4 · Two precedents already in the code — **evidence for the decision, NOT recommendations**

**Recorded because `EM-OPEN-021`'s decision-maker should know the codebase already answers similar questions in two different ways:**

1. **Rule 4 handles a comparable anomaly SOFTLY:** *"Setup incomplete but voting closed → constitutional limbo (soft enforcement)"* — it emits `Log::warning` and continues **rather than throwing.**
2. **Rule 6 already uses `SetupNomination` as a fall-back** when the voting window is undefined.

🔴 **I do NOT conclude that either is correct here.** **I do NOT propose `SetupNomination`, a holding state, `Cancelled`, soft-warn, or catching the exception.** **These are observed precedents in the implementation, presented so the Product Owner decides with full sight of them.**

## 8.5 · Regression classification — the four measured rows

| Row | Category |
|---|---|
| `test_voting_active_can_vote` · `test_voting_active_allowed_actions` | **A · obsolete fixture** — the scenario claims a legitimately `VotingActive` election but establishes no approved candidate. **Under the adopted invariant the fixture is now incomplete.** *(FAILURE, not ERROR — they reach a clean assertion)* |
| `test_election_with_voting_window_open_derives_to_voting_active` · `test_close_voting_transition` | 🔴 **C · behaviour dependent on unresolved `EM-OPEN-021`** — they enter *window-open + zero-candidates* and throw. **NOT classifiable as regressions, because no expected state has been decided** |
| | **B · genuine regression: NONE identified in this class.** **D · unrelated pre-existing failures: none in this class (baseline was 24/24).** |

**No fixture repaired. No test greened. Category A rows are NOT to be "fixed" by adding a candidate until the Product Owner disposes of them** — that is Phase 2, and Mission 9's distinction applies.

---

# 9 · Full regression surface — **measured across the whole frozen universe**

**Method:** the five adopted paths executed sequentially with `--log-junit`, then a **per-test** diff against the frozen manifest. **XML entities decoded** *(see §9.3 — my first pass did not, and that mattered)*.

## 9.1 · Reconciliation — the baseline is INTACT

| | |
|---|---:|
| Baseline rows (frozen manifest) | **1,376** |
| Baseline rows **no longer present** | ✅ **0** |
| Genuinely new rows | **+14** |
| **Total now** | **1,390** |

> ✅ **NOT ONE ROW OF THE FROZEN BASELINE HAS BEEN LOST.** **1,376 − 0 + 14 = 1,390 reconciles exactly.** **The programme's historical evidence is fully intact — which was the reason for freezing it.**

⚠️ **But the DENOMINATOR CONTRACT and the PATHS have diverged: re-running the five paths now yields 1,390, not 1,376.** **`SD-1` remains frozen at 1,376 as the contract; 1,390 is what the paths currently contain.** **Anyone re-running must not read 1,390 as a corrected denominator** — that conflation is precisely how the 826/1,376 defect began.

## 9.2 · The regression surface — **13 rows, not 4**

| Rows | Class | Note |
|---:|---|---|
| **4** | `StateMachine\CurrentBehaviorTest` | measured earlier |
| **3** | `VoterStrategySnapshotTest` | 🔴 **new area** |
| **2** | `ElectionPolicyStateAwareTest` | the `canEdit`/`canVote` capability rows |
| **1** | 🔴 **`Architecture\Election\ElectionLifecycleStateConsistencyTest`** | **architecture fitness** |
| **1** | 🔴 **`Architecture\ElectionStateMachineConsistencyTest`** | **architecture fitness** |
| **1** | `ElectionDashboardAccessTest` | 🔴 **projection/access — a real consumer** |
| **1** | `VoterImportStateGateTest` | 🔴 **new area** |

> **My single-class estimate of 4 was 3.25× too low — I had flagged it as possibly exceeding 4, and it does.**
>
> 🔴 **TWO ARCHITECTURE/CONSISTENCY tests now fail. These do not assert fixtures — they assert declared invariants ABOUT the state machine.** **That independently corroborates the §8.1 diagnosis: derivation is no longer TOTAL, because rule 6 matches without returning.** **A failing consistency test is materially stronger evidence than a failing fixture.**
>
> **`ElectionDashboardAccessTest` confirms the §8.2 inference was not merely theoretical: a REAL consumer of the lifecycle projection is affected, not just state-machine tests.**

## 9.3 · ⛔ My comparison bug, disclosed — and why it mattered

**My first diff reported *"36 baseline rows gone, 50 added"*, which looked like evidence destruction.** **It was my own bug: JUnit writes `&quot;` where the manifest holds `"`, so every data-provider row (`test_from_divergence with data set "SovereigntyLeak is Critical"`) failed to match.** **All 36 were in two data-provider classes, matched 1:1 by "additions" — the giveaway I checked before publishing.**

**Consequence had I not caught it: I would have reported that 36 rows of the frozen baseline were destroyed — alarming, and false.** **It also made the 13 a lower bound, since unmatched rows were silently excluded.** **After decoding: gone = 0, and 13 is exact.** **Ninth incident, and the second where the reconciliation arithmetic itself exposed the error.**

## 9.4 · One row went GREEN — legitimately, and it corrects a prediction of mine

**`VotingButtonsStateMachineIntegrationTest::open_voting_rejects_if_missing_candidates` — the row I insisted must NOT be greened — now PASSES.**

**I predicted it would still fail after implementation, because its fixture never defines a voting window. That prediction was WRONG.** The guard reports **all** unmet preconditions, so the message now reads *"Unmet preconditions: voting_window_defined, has_approved_candidates"* — and `assertStringContainsString('candidates', …)` matches.

> ✅ **And the pass is legitimate, not coincidental: `voting_window_defined` does not contain the substring `candidates`, so the assertion succeeds ONLY when `has_approved_candidates` is genuinely unmet.** **The row greened because the RULE WAS IMPLEMENTED — exactly the outcome my warning was meant to protect. No assertion was weakened and no fixture was touched.** **It moves from disposition category C to a genuine passing business verification.**

## 9.5 · The 14 new rows — Session 3's evidence is real; a second stream's is RED

| Rows | Class | Status |
|---:|---|---|
| 5 | `EmVot002ApprovedCandidateBeforeVotingTest` | ✅ **all PASSED** — computed path, incl. *only-unapproved-candidacies* and *zero-candidacies* |
| 3 | `EmVot002OpenVotingPreconditionTest` | ✅ **all PASSED** — command path |
| **6** | 🔴 **`ElectionOnlyEntitlementPinTest`** | **all ERROR** |

✅ **Session 3's claimed 8/8 is independently confirmed, and the test names show the approved-vs-unapproved distinction is genuinely exercised.**

⚠️ **`ElectionOnlyEntitlementPinTest` is UNTRACKED and all 6 rows ERROR** — subjects include *credential possession does not override suspension* · *membership in one election grants nothing in another* · *election-only voter without member aggregate passes the ballot gate*. **That is exactly the `PBDIGIT-65`/`69` entitlement territory of my P1 mission.** **Authorship NOT ESTABLISHED — it is not mine, and I have not touched it.** **Whether these are intentional RED pins or broken is for their author; I record only that they exist, are untracked, and all error.**

## 9.6 · Corrected regression classification

| Category | Count | Rows |
|---|---:|---|
| **A · obsolete fixture** *(scenario claims a legitimately votable election but establishes no approved candidate)* | **≤6** | `CurrentBehaviorTest` ×2 · `ElectionPolicyStateAwareTest` ×2 · plausibly `VoterImportStateGateTest`, `ElectionDashboardAccessTest` — **per-row confirmation NOT done** |
| **B · genuine regression** | **`NOT ESTABLISHED`** | ⚠️ **the 2 ARCHITECTURE rows are the strongest candidates** — a declared consistency invariant now fails, which no fixture change can explain |
| **C · dependent on unresolved `EM-OPEN-021`** | **≥2** | `test_election_with_voting_window_open_derives_to_voting_active` · `test_close_voting_transition` |
| **D · unrelated pre-existing** | **0 of the 13** | all 13 were PASSED in the baseline |

**Not decomposed further: `VoterStrategySnapshotTest` ×3 were not read.** **Assigning them to A/B/C by name is exactly the error this programme forbids.**

---

**P0 COMPLETE · REGRESSION SURFACE = 13 ROWS · BASELINE INTACT (0 LOST) · `EM-VOT-002` VERIFIED BOTH PATHS**
**`EM-OPEN-021`: current implementation behaviour is observable; the intended business lifecycle semantics remain UNRESOLVED**
**NOTHING REPAIRED · NOTHING GREENED BY ME · NOTHING DELETED · `L3` STILL 240 · `SD-1` STILL 1,376**

---

# 10 · P1 analysis — **three corrections, and the two "architecture" tests are NOT what I said**

## 10.1 · ⛔ Correction 1 — the regression surface is **19**, not 13

**My §9 script captured each `<testcase>` body with `(.*?)(?:</testcase>|/>)`. The `/>` alternative TRUNCATED bodies at the first self-closing child element, hiding `<failure>`/`<error>` tags that followed.** **Re-parsed with `</testcase>` only: 19 newly non-passing rows.** **Tenth incident; the third caused by my own parsing rather than by the estate.**

## 10.2 · ⛔ Correction 2 — the two "architecture" tests assert the OLD BUSINESS RULE, not a structural invariant

**This is the correction that matters most, because I escalated these as the strongest finding.**

| Row | Its actual assertion |
|---|---|
| `ElectionLifecycleStateConsistencyTest::test_lifecycle_engine_computes_voting_active_state_when_voting_window_is_open` | message: *"**Lifecycle engine computes voting_active when voting window is currently open**"* |
| `ElectionStateMachineConsistencyTest::test_engine_uses_election_clock_service_for_voting_window` | *"Engine should use ElectionClockService logic and **derive VotingActive state**"* — expected `VotingActive`, got **`Draft`** |

> 🔴 **Both assert *"window open ⇒ `VotingActive`"* — which is EXACTLY the rule `EM-VOT-002` REPEALS.**
>
> **I wrote that these are *"materially stronger evidence than a failing fixture"* because they *"assert declared invariants ABOUT the state machine."* THAT WAS WRONG.** **I inferred their nature from `Architecture\…ConsistencyTest` in their names — the precise error this programme has documented nine times, and I committed it on the finding I had escalated hardest.**
>
> **Corrected classification: category C/A — tests encoding the SUPERSEDED business rule. NOT evidence of a broken structural invariant, and NOT evidence that derivation's totality is architecturally asserted anywhere.** **My §9.2 claim that they "independently corroborate the rule-6 diagnosis" is WITHDRAWN — they corroborate nothing of the kind; they simply expected the old outcome.**

## 10.3 · ⛔ Correction 3 — the dashboard row does not throw, but a different consumer does

**`ElectionDashboardAccessTest::test_chief_can_open_and_close_voting` expected `'counting'` and got a different state — an ASSERTION MISMATCH, no exception.** Its workflow (chief opens, then closes voting) now legitimately requires an approved candidate. **Category A.** **So *"a real consumer is affected by the exception"* was wrong as stated.**

**But the escalation survives on different evidence:**

| **Rows that genuinely THROW `InvalidElectionStateException`** | 3 |
|---|---|
| `CurrentBehaviorTest::test_election_with_voting_window_open_derives_to_voting_active` | derivation |
| `CurrentBehaviorTest::test_close_voting_transition` | **`close_voting` unreachable** |
| 🔴 **`VoterImportStateGateTest::import_page_is_forbidden_in_voting_active_state`** | **A REAL CONSUMER — an HTTP page** |

> **The exception blast radius is 3 rows, and one of them IS a real request-path consumer** — a voter-import page. **So the operational concern stands, but the correct evidence is `VoterImportStateGateTest`, not the dashboard.**

## 10.4 · The 19, decomposed as far as the evidence allows

| Category | Rows | Evidence |
|---|---:|---|
| **C · depends on unresolved `EM-OPEN-021`** *(throws; no expected state has been decided)* | **3** | the three above |
| **A · obsolete fixture / superseded rule** *(asserted the old "window ⇒ VotingActive", or a workflow requiring it)* | **7** | `ElectionLifecycleStateConsistencyTest` · `ElectionStateMachineConsistencyTest` · `ElectionDashboardAccessTest` · `ElectionPolicyStateAwareTest` ×2 *(`cast_vote_allowed_during_voting`, `manage_settings_denied_during_voting`)* · `CurrentBehaviorTest` ×2 *(`can_vote`, `allowed_actions`)* |
| **`NOT ESTABLISHED`** | **3** | `VoterStrategySnapshotTest` ×3 — **not read.** Their names concern snapshot immutability, and one expects a THROW when the snapshot is null. **Assigning them by name is the error above; deliberately unclassified** |
| 🔴 **D · almost certainly UNRELATED to `EM-VOT-002`** | **6** | `D2_5ConstitutionalArticlesSnapshotTest` · `D5ResolverIntegrationTest` · `D6SovereigntyConvergenceTest` · `CapabilityPolicyLayerTest` ×2 · `DeviceBindingPolicyTest` — **enum/structural Security-cluster rows.** `test_enum_has_all_required_cases` cannot plausibly be affected by a candidate precondition. **Consistent with the documented execution-order/isolation fragility** *(the same effect that made two facade rows ERROR when paired and pass when run alone)*. **`MECHANISM NOT ESTABLISHED`** |

> **So `EM-VOT-002`'s attributable surface is at most 10 of 19 rows (3 C + 7 A), with 3 unclassified and 6 probably environmental.** **My §9 framing — "13 rows, all newly non-passing after EM-VOT-002" — overstated the causal attribution by conflating "changed status" with "caused by the change."**

## 10.5 · Was the old `VotingActive` semantically legitimate? — the question that decides A vs B

**No, and this is the substantive architectural finding:**

> **Before `EM-VOT-002`, an election with an open window and zero approved candidates DERIVED `VotingActive`, and `canVote` returned TRUE. Voters could have been admitted to a ballot with nothing on it.** **The adopted rule declares that state illegitimate.** **Therefore the 7 category-A rows were asserting a state the business has now declared invalid — they are not regressions; they were encoding a defect as an expectation.**

**Consequence: `EM-VOT-002` did not BREAK the state machine. It revealed that the state machine's derivation was never TOTAL over legitimate configurations — rule 6 has no branch for *"setup complete, window open, invariant unmet"* (§8.1), and nothing anywhere asserts that derivation must be total.** **That gap is now reachable. It is a genuine domain-model completeness question, not an implementation slip.**

## 10.6 · Verdict on the commissioned question

> ✅ **`EM-OPEN-021` IS demonstrably a domain decision, not a documentation question.** **Evidence: three rows reach a state with NO derivable lifecycle value, one of them through an HTTP consumer, and one of them being `close_voting` — the very operation an administrator would need. No adopted rule says what that state should be.**

**And equally: `EM-VOT-002` itself is correctly implemented.** **The invariant is right; the state model is incomplete beneath it.**

**Election-Only relevance: the anomaly is mode-INDEPENDENT** — derivation reads windows and candidacy approval, neither of which is mode-specific. **It is a lifecycle-engine matter, not an Election-Only one**, though it blocks Election-Only readiness because Election-Only elections traverse the same derivation.

## 10.7 · Recommended next authority action

| Authority | Action |
|---|---|
| **PO / ARB** | **Rule `EM-OPEN-021`.** The choice set is *observed in the codebase, and I recommend none*: an existing state · a new holding state · soft-warn-and-continue *(rule 4's existing precedent)* · administrative-intervention-required. **Note the operational stake: until it is ruled, an election in this configuration cannot be closed or suspended.** |
| **Session 3** | **no change** — the grant covered `EM-VOT-002`, which is verified. **Do NOT choose a fallback state.** |
| **Session 1** | read `VoterStrategySnapshotTest` ×3; then establish whether the 6 Security rows are order-dependent by running them in isolation |

**Unchanged: nothing repaired, greened, renamed, deleted or skipped · `SD-1` = 1,376 · `L3` = 240 · `ElectionOnlyEntitlementPinTest` (6 ERROR, untracked, not mine) remains OUTSIDE the frozen universe as external evidence for `PBDIGIT-65`/`69`.**

---

# 11 · P2 evidence — **the anomalous state is REACHABLE IN PRODUCTION through legitimate operations**

> **Session 1 does not recommend or select the domain semantics.** **This section reports what the current system does. Every `EM-OPEN-021` answer remains open.**

## 11.1 · 🔴 The production path — found, and it uses only guarded, legitimate calls

**The throw requires `nomination_completed = true` with ZERO approved candidates. Two production writers exist:**

| Writer | Guard |
|---|---|
| `Election::completeNomination()` | ✅ **explicitly blocks it** — `if (approved count === 0) throw new \InvalidArgumentException('Cannot complete nomination: No candidates approved')` |
| 🔴 **`Election::forceCloseNomination()`** | **NO approved-candidate guard.** Its only guard is *"Cannot modify nomination after voting has started"*. It **auto-rejects every pending candidacy** and then sets `nomination_completed = true` |

**So the reachable sequence, entirely within permitted operations:**

```
1. election has only PENDING candidacies (none approved yet)
2. chief calls forceCloseNomination()  BEFORE voting_starts_at   ← permitted by its own guard
3. all pending → REJECTED · zero approved · nomination_completed = true
4. the clock reaches voting_starts_at  → window OPEN
5. derivation: rule 5 refused (EM-VOT-002) · rule 6 matches but returns nothing
6. → InvalidElectionStateException
```

> ⚠️ **This is NOT a fixture artifact. It is reachable in production via a legitimate administrative action.** **And note the irony: `forceCloseNomination`'s guard exists to prevent disruption AFTER voting starts, yet the call CREATES the configuration that becomes undecidable WHEN voting starts.**

## 11.2 · Where the exception escapes — **there is no handler anywhere**

| Layer | Evidence |
|---|---|
| **3 middlewares derive state** | `EnsureVotingActive:47` · **`VoteEligibility:62,79` (VOTER-FACING)** · `EnsureElectionState:54` |
| `catch` blocks in those three | 🔴 **0 · 0 · 0** |
| Handler for `InvalidElectionStateException` in `app/Exceptions/` or `bootstrap/app.php` | 🔴 **NONE** |

> **PROVEN: the exception propagates out of middleware unhandled → HTTP 500 on any gated route, before the controller runs.** **The measured row confirms the shape: `import_page_is_forbidden_in_voting_active_state` expected **403** and instead ERRORed — in deployment that is a **500 where a clean 403 was intended**.** **`VoteEligibility` being affected means the VOTER-facing path is included.**

## 11.3 · Recovery / reachability matrix

| | Operation | Verdict | Basis |
|---|---|---|---|
| **A** | closed normally (`close_voting`) | 🔴 **DISPROVEN** | **measured** — `test_close_voting_transition` throws |
| **B** | suspended | 🔴 **DISPROVEN BY TRACE** | `Election::transitionTo()` computes `ElectionLifecycle::of()->snapshot()` **unconditionally before** the system-trigger check, so every transition derives state first. **Traced, NOT executed — so "by trace", not "measured"** |
| **C** | administratively repaired | **NOT ESTABLISHED** | no repair path examined |
| **D** | returned to a setup state | **NOT ESTABLISHED** | would require a transition → same mechanism as B |
| **E** | advanced by approving a candidate | **NOT ESTABLISHED** | ⚠️ `CandidacyManagementController:52` exposes `nominationLocked => (bool) nomination_completed` — **which `forceCloseNomination` has just set true.** **If nomination being locked prevents new approvals, this exit is closed too — NOT VERIFIED, and it is the single most decision-relevant unknown left** |
| **F** | **the clock** | ✅ **PROVEN BY TRACE** | at `now >= voting_ends_at`, **rule 4 returns `Counting`** (its inner check — approved · admin · nomination complete — is satisfied). **The election becomes derivable again with no intervention** |

> **The only established exit is the passage of the voting window.** **Maximum stuck interval = `[voting_starts_at, voting_ends_at)`.**

## 11.4 · Five-state matrix — **static trace of rules 1–12, not executed**

| | Configuration | Derived result |
|---|---|---|
| **A** | window open · no approved · **nomination INCOMPLETE** | ✅ **`SetupNomination`** — rule 7 catches it. **No throw** |
| **B** | window open · no approved · **nomination COMPLETE** | 🔴 **THROW** — rule 6 matches, returns nothing; 7–12 all skip |
| **C** | window open · **≥1 approved** | ✅ **`VotingActive`** — rule 5 |
| **D** | **window CLOSED** · no approved | ✅ **`Counting`** — rule 4. ⚠️ **`Counting` carries NO candidate requirement, so a candidate-less election proceeds to counting zero votes.** *Observation only* |
| **E** | window open · **only pending/rejected** | **identical to A or B** — `hasCandidatesApproved()` is false either way, so the outcome turns entirely on `nomination_completed` |

> 🔑 **`nomination_completed` is the sole discriminator between a safe fall-through (`SetupNomination`) and the throw.** **The anomalous state has a precise domain description: *"nomination was declared complete, yet no candidate is approved."***

## 11.5 · Existing domain precedent — **reported only, NOT selected**

| Precedent | Where |
|---|---|
| **Soft handling of a constitutional anomaly** | **Rule 4**: *"Setup incomplete but voting closed → constitutional limbo (soft enforcement)"* — `Log::warning` and **continue**, no throw |
| **`SetupNomination` used as a fall-back** | **Rule 6**, when the voting window is undefined |
| **`SetupAdministration` used as a fall-back** | **Rule 7**, when the nomination window is pending |
| **Suspension as an explicit operational pause** | Rule 1, checked first — **an already-suspended election always derives cleanly** |
| **Hard refusal** | `InvalidElectionStateException` · `InvalidTransitionException` · `LogicException` on an unknown precondition |

🔴 **The codebase therefore already answers comparable questions in BOTH directions — soft-warn-and-continue AND hard-throw.** **Their existence authorises none of them as the meaning of this state. I select none.**

## 11.6 · Proven vs not established

**PROVEN:** `EM-VOT-002` enforced on both paths with the approval-correct predicate · the anomaly is reachable in production via `forceCloseNomination()` · no `catch` and no global handler exist · `close_voting` throws (measured) · a voter-facing middleware derives state · `nomination_completed` is the discriminator · the clock resolves it at `voting_ends_at`.

**NOT ESTABLISHED:** whether a candidate can still be approved once nomination is locked *(exit E — the most decision-relevant gap)* · whether `suspend` fails in execution as the trace predicts · whether any UI surfaces the 500 gracefully · the 3 `VoterStrategySnapshotTest` rows · whether the 6 Security-cluster rows are order-dependent · how many additional production consumers derive state.

## 11.7 · Is the evidence sufficient for the `EM-OPEN-021` ruling?

> ✅ **YES for the ruling itself.** The decision-maker now knows: **the state is production-reachable through a legitimate action; it has no derivable lifecycle value; every gated route 500s including a voter-facing one; the election cannot be closed and (by trace) cannot be suspended; it self-resolves only when the voting window elapses; and the codebase contains precedents for both soft and hard handling.**
>
> **The one gap that could change the URGENCY — not the ruling — is exit E.** **If a candidate can still be approved, an administrator has a manual escape and this is serious-but-recoverable. If nomination-locked blocks approval, the only escape is waiting out the voting window.** **I did not verify it, because doing so would mean constructing a scenario whose intended semantics are precisely what `EM-OPEN-021` has not yet decided.**

**Session 1 does not recommend or select the domain semantics.**

---

**P2 EVIDENCE COMPLETE · STOPPING AS INSTRUCTED**
**Nothing repaired · nothing greened · nothing deleted · no fixture, production, Constitution, schema or migration change · `SD-1` = 1,376 · `L3` = 240 · `65`/`69` not investigated · `ElectionOnlyEntitlementPinTest` remains outside the frozen universe**

**Traceability:** `Election::forceCloseNomination()` · `Election::completeNomination()` (guarded) · `ElectionLifecycleEngineImpl::getState()` rules 1–12 · `:164` throw · `EnsureVotingActive:47` · `VoteEligibility:62,79` · `EnsureElectionState:54` · `CandidacyManagementController:52` · measured JUnit run `post-emvot002.xml` (1,390 rows)

---

# 12 · P3 — recoverability closed, and the HTTP 500 is **PROVEN**

## 12.1 · The HTTP consequence — no longer an inference

**Checked the framework path, not only `grep`:**

```php
final class InvalidElectionStateException extends \DomainException { … }   // no render(), no report(), no status
```

| Layer | Finding |
|---|---|
| Exception class | **plain `\DomainException`** — **no `render()`**, so Laravel cannot derive a response from it |
| `bootstrap/app.php` → `withExceptions` | configures **only `dontFlash`** for password fields — **no mapping, no handler** |
| The 3 deriving middlewares | **no `catch`** *(P2)* |

> ✅ **PROVEN — not traced, established: an election in the anomalous state returns HTTP 500 on any gated route, including the voter-facing `VoteEligibility` path.** **Laravel's default handling applies because nothing anywhere claims the exception.**

## 12.2 · 🔑 Exit E resolved — and the obstruction is NOT a lock

**Approval path: `CandidacyReviewController::review()` → sets `'status' => 'approved'`.**

**Its complete guard set:** `demo → 404` · `election_id` mismatch → 403 · `organisation_id` mismatch → 403 · **`abort_if($application->status !== 'pending', 422, 'Application has already been processed.')`**

| Question | Answer |
|---|---|
| **A · Does `forceCloseNomination()` lock nomination?** | 🔴 **NO.** `nominationLocked` exists **only as an Inertia prop** (`CandidacyManagementController:52`, `(bool) nomination_completed`) — **a UI hint with NO enforcement anywhere.** `store`/`update`/`destroy` guard only demo-type and tenancy |
| **B · What approves a candidacy?** | `CandidacyReviewController::review()` |
| **C · Guards?** | the four above — **none concerns nomination completion** |
| **D · Does `nomination_completed` prevent approval?** | 🔴 **NO — nothing enforces it** |
| **Does approval derive lifecycle state?** | ✅ **NO** — no `ElectionLifecycle`, no `snapshot` in that controller. **So approval would NOT throw, even in the anomalous state** |

> **So the recovery obstruction is NOT a lock — it is DATA DESTRUCTION.** **`forceCloseNomination()` set every `pending` candidacy to `rejected`, and `review()` refuses anything that is not `pending` (422).** **The applications that could have been approved were consumed by the very call that created the anomaly.**
>
> **That distinction is material for the ruling: a lock would be a deliberate policy decision; this is a SIDE EFFECT.** *(Reported as fact — I do not judge whether the side effect is correct.)*

## 12.3 · Recovery-path matrix — final

| Path | Verdict | Basis |
|---|---|---|
| `close_voting` | 🔴 **DISPROVEN** | **measured** — throws |
| `suspend` | 🔴 **DISPROVEN BY TRACE** | `transitionTo()` derives the snapshot unconditionally first |
| **re-approve an EXISTING candidacy** | 🔴 **DISPROVEN** | all were set to `rejected`; `review()` aborts **422** unless `pending` |
| **approve a NEW candidacy/application** | ⚠️ **NOT ESTABLISHED** | **no lock blocks it and the path does not throw** — but whether a new application can be CREATED after force-close, with the nomination window elapsed and voting open, is **unverified** |
| administrative repair · return to setup | **NOT ESTABLISHED** | both require a transition → same mechanism as `suspend` |
| **the clock** | ✅ **PROVEN BY TRACE** | rule 4 → `Counting` at `voting_ends_at` |

**No candidate path was chosen between; each is classified independently, per the commission.**

## 12.4 · ESTABLISHED · NOT ESTABLISHED · UNDECIDED

**ESTABLISHED**
`EM-VOT-002` enforced on both paths with the approval-correct predicate · the anomalous state is **production-reachable** via `forceCloseNomination()` using only permitted operations · **`nomination_completed` is the sole discriminator** · **HTTP 500 is proven**, with no `catch`, no `render()`, no global mapping · `close_voting` disproven as recovery (measured) · **no nomination lock exists anywhere** · **the obstruction to re-approval is the auto-rejection side effect** · the clock resolves the state at `voting_ends_at`.

**NOT ESTABLISHED**
Whether a NEW candidacy/application can be created and approved in this configuration *(the last open recovery question)* · whether `suspend` fails in execution as traced · whether any UI degrades the 500 gracefully · how many further production consumers derive state · the 3 `VoterStrategySnapshotTest` rows · whether the 6 Security-cluster rows are order-dependent.

**UNDECIDED**
**`EM-OPEN-021`** — the intended lifecycle semantics.

## 12.5 · Why I stopped short of the last unknown

**Verifying the new-application path would require constructing an election in the anomalous configuration and attempting a candidacy submission — and the expected outcome of that attempt IS the semantics `EM-OPEN-021` has not decided.** **Measuring it would mean asserting an intended result. I declined for the same reason I declined exit E in P2.** **It is a bounded, cheap follow-up ONCE the ruling exists.**

> **`EM-OPEN-021` remains a domain/business decision. This verification does not select its semantics.**

---

**P3 COMPLETE · STOPPING · EVIDENCE PACKAGE RETURNED**
**Nothing repaired · nothing greened · nothing deleted · no production, test, fixture, configuration, Constitution, schema or migration change · `SD-1` = 1,376 · `L3` = 240 · Session 2's decisions and Session 3's implementation not consumed as authority**

**Traceability:** `InvalidElectionStateException` *(plain `\DomainException`, no `render()`)* · `bootstrap/app.php:134` `withExceptions` *(`dontFlash` only)* · `CandidacyReviewController::review()` *(4 guards, `pending` required, no lifecycle read)* · `CandidacyManagementController:52` *(`nominationLocked` = UI prop only)* · `Election::forceCloseNomination()` *(auto-rejects pending, no approved-candidate guard)* · `EnsureVotingActive:47` · `VoteEligibility:62,79` · `EnsureElectionState:54` · `ElectionLifecycleEngineImpl::getState()` rules 1–12
