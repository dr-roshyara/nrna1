# Election Progression — Semantic Reconciliation: Legacy ↔ Constitution ↔ Current Lifecycle

**Type:** Architecture investigation report (Session 4) · **Date:** 2026-08-15
**Boundary:** **INVESTIGATION ONLY.** No production code, test, Constitution, Manifesto or Lifecycle was modified. No fix implemented. No legacy code copied forward. No business rule chosen. No decision resolved.
**Status:** Evidence and classification only. Every A–F classification below carries its evidence. Where evidence is insufficient, the row says **F — unresolved** rather than guessing.

> **Headline, stated before the detail, because it corrects the hypothesis that commissioned this work.**
>
> The commissioning hypothesis was: *"the legacy state machine implemented command-driven progression, and the migration silently converted it into clock-driven progression."*
>
> **That hypothesis is NOT supported by the evidence.** The oldest recoverable election progression logic was **already clock-driven** at the voting boundary. **No generation of this system has ever required the Chief Election Officer to act in order for an election to become voting-active.**
>
> **But a different regression is confirmed, and it is the one that matters:** an intermediate generation could say *"the window is open but the election is not fit to vote"* — it had **blocked** states. **The current architecture deleted that vocabulary and never replaced it.** That deletion is the direct cause of `EM-OPEN-021`.

---

## 0 · What was examined

| Source | Artifacts |
|---|---|
| **Legacy behaviour** | `Election::getCurrentStateAttribute()` pre-`ece12ae5`; `BackfillElectionState::determineState()` pre-`9f2cba4d`; `ElectionStateMachine`; `TransitionMatrix`; `ProcessElectionAutoTransitions`; `tests/Unit/Election/DerivedStateTest.php`; `tests/Unit/Election/BusinessConditionsTest.php`; `developer_guide/election/real_election/statemachine/` |
| **Current business rules** | `ElectionConstitution::RULES`; `ConstitutionalTransitionGuard::isPreconditionMet()`; `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` |
| **Current lifecycle** | `ElectionLifecycleEngineImpl::getState()`; `ElectionLifecycle` facade; `Election::transitionTo()` and its side effects; `ElectionClockService`; `developer_guide/election/real_election/state_machine_new/` |
| **Intent record** | commit messages and the two design documents `01_OVERVIEW.md` and `02_STATE_TRANSITIONS.md`; `docs/adr/` |

**Authority note applied throughout:** `architecture_legacy/` documents and model docblocks are **not authority** (Manifesto §10). They are used here as *behavioural evidence only*, and labelled as such.

---

## 1 · Legacy business behaviour — what the old system actually did

Three generations exist, not two. Distinguishing them is what makes the diagnosis precise.

```
GEN 1   ≤ 2026-04-26     state was COMPUTED from the clock. No state column consulted.
   │
   ▼    ece12ae5 (2026-04-26)
GEN 2   2026-04-26 …     state PERSISTED and set by explicit commands (transitionTo).
   │    2026-05-20        Business-condition validators added. BLOCKED states appear.
   ▼    ba4cfa9c / 9f2cba4d (2026-05-19/20), 11710f39 (2026-05-24)
GEN 3   current           state re-DERIVED from facts + clock. Column demoted to cache.
                          BLOCKED states deleted. Command fact not read by the derivation.
```

### Generation 1 — clock-driven, no preconditions

Recovered from the pre-image of `Election::getCurrentStateAttribute()`:

- results published → `results`
- **now between `voting_starts_at` and `voting_ends_at` → `voting`**
- **now past `voting_ends_at` → `results_pending`**
- otherwise administration / nomination by completion flags

**No candidate check. No voter check. No officer act. The clock alone moved the election into and out of voting.**

### Generation 2 — commands became sovereign, and "blocked" was invented

`ece12ae5` (2026-04-26) replaced the computed accessor with `return $this->state ?? 'draft'` and introduced explicit, audited, role-checked commands (`transitionTo()`, `TransitionMatrix`, `ElectionStateTransition` records) plus **business-condition validators**: `canEnterAdministrationPhase()`, `canEnterNominationPhase()`, `canEnterVotingPhase()`, `canEnterCountingPhase()`.

Critically, `BackfillElectionState::determineState()` — self-described in its own docblock as *"This replicates the original computed state logic"* — expressed the semantics as:

```php
if ($election->voting_ends_at && now() > $election->voting_ends_at) {
    if ($election->voting_locked) { return 'results_pending'; }
    return 'voting_ended_unlocked';          // ← qualified state
}
if ($election->voting_starts_at && now() >= $election->voting_starts_at) {
    if ($election->canEnterVotingPhase()) { return 'voting'; }
    return 'voting_blocked';                 // ← qualified state
}
…
if ($election->administration_completed) {
    if ($election->canEnterNominationPhase()) { return 'nomination'; }
    return 'nomination_blocked';             // ← qualified state
}
```

where `canEnterVotingPhase()` = **nomination completed AND at least `min_candidates_for_voting` candidates AND zero pending candidacies**.

**This is the decisive recovery.** The legacy model had a first-class way to express: *the clock says it is time, but this election is not fit to proceed.* It did **not** silently proceed, and it did **not** invent a fallback — it named the condition.

### Generation 2 — time as a *fallback*, never as the primary mover

`ProcessElectionAutoTransitions` shows the legacy relationship between time and authority precisely:

- progression through **administration → nomination → voting-lock** was a **manual command** (`completeAdministration()`, `completeNomination()`);
- an **opt-in** grace period (`allow_auto_transition`, default true; `auto_transition_grace_days`, default 7) let the *system* act **only after the officer had failed to**, and only with the same preconditions re-checked (`hasRequiredPostsAndVoters()`, `hasPendingCandidates()`);
- **the voting window boundary itself was never commanded** — `enforceVotingLock()` merely *seals* a window the clock has already ended, and its own docblock states it "is NOT a constitutional transition action… It bypasses the constitutional state machine intentionally."

**So the legacy answer to "does time advance the election?" is split, and the split is the important part:**

| Boundary | Legacy mover |
|---|---|
| administration → nomination | **Officer command**, with a time-based grace fallback |
| nomination → voting-ready | **Officer command**, with a time-based grace fallback |
| **→ voting active** | **The clock** (qualified by `canEnterVotingPhase()`) |
| **voting active → counting** | **The clock**, deliberately and irrevocably |
| counting → results published | **Officer command**, never automatic |

The legacy developer guide states the rationale for the voting boundary in business terms: *"Ensures voting integrity · Prevents manipulation of close time · Cannot be overridden by admins or bugs · Critical for democratic process"* — with `complete_phase` explicitly listed as a **blocked action** during voting.

---

## 2 · Current business rule — what is actually authorised today

### What the Constitution says

`ElectionConstitution::RULES` defines `open_voting` as a **command**: allowed states `setup_nomination` / `ready_for_voting`; allowed role **`chief` only**; preconditions `voting_window_defined`, `timezone_set`, `has_approved_candidates`; target state `voting_active`. `close_voting` is likewise a command (chief/deputy) targeting `counting`.

**The Constitution therefore describes a command-driven voting boundary.**

### What the Constitution does NOT say

- It **never states that arrival of `voting_starts_at` may, or may not, produce `voting_active` by itself.** The derived path is outside its vocabulary entirely.
- It contains **no rule about correcting, rescheduling or recovering a time window**, expired or otherwise.
- It contains **no voter precondition at the voting boundary** — `has_voters` exists only on `complete_administration`, an upstream gate.

### What the Manifesto says

- `EM-VOT-001` / `EM-VOT-002` / `EM-VOT-003` bind the **candidate** (and, per `EM-VOT-003`, **voter**) requirement at the voting boundary.
- **No adopted rule anywhere states who or what advances an election through a phase boundary.** Progression *authority* is unruled at the business level.
- `EM-OPEN-021` is open and explicitly **not actionable** until the PO decides.

> **Finding G-1 — the governing gap, stated plainly.** The Constitution defines the *commands*; the Manifesto defines the *preconditions*. **Neither defines whether progression is commanded or derived.** The system's most consequential lifecycle semantic has never been decided by anyone — it has only ever been implemented, three different ways.

---

## 3 · Current lifecycle behaviour — what the code actually does

`ElectionLifecycleEngineImpl::getState()` derives state in a fixed 12-step order. Two steps decide progression:

```php
// Step 4 — Counting
if ($election->voting_ends_at !== null && $now->gte($election->voting_ends_at)) {
    if ($election->approved_at !== null && $election->administration_completed
        && $election->nomination_completed) { return Counting; }
    Log::warning('Constitutional anomaly: voting ended without setup completion', …);
    // ← falls THROUGH; no state chosen for the anomaly
}

// Step 5 — VotingActive
if ($this->isVotingWindowOpenNow($election) && $this->hasCandidatesApproved($election)) {
    return VotingActive;
}
```

**Seven consequences follow, each verified by inspection:**

1. **Arrival of `voting_starts_at` alone produces `VotingActive`** (given ≥1 approved candidate). No officer act is required or recorded.
2. **`administration_completed` and `nomination_completed` are NOT checked on this path.** The derived route into voting bypasses setup completion that Generation 2's `canEnterVotingPhase()` enforced.
3. **The Chief's act leaves a fact the engine ignores.** `open_voting` sets `voting_locked = true` — and **nothing in the derivation reads it.** The only persisted trace of the officer's authority is invisible to the authority that computes state.
4. **`voting_locked` is overloaded to the point of ambiguity.** It is set both by `open_voting` (meaning *"the Chief opened this"*) and by `enforceVotingLock()` after expiry (meaning *"this is sealed shut"*). One flag, two opposite meanings — it could not be used as a command term today even if the derivation wanted to.
5. **The Chief's command destroys the schedule it was meant to honour.** `applySideEffectsForOpenVoting()` overwrites `voting_starts_at = now()` and `voting_ends_at = now() + 4 days`, discarding the window the committee scheduled. The comment says the quiet part directly: *"This ensures engine derives VotingActive state."* **The command was reshaped to satisfy the derivation, rather than the derivation reshaped to respect the command.**
6. **Once the clock has opened the window, the Chief's Proceed is not merely redundant — it is unlawful.** `open_voting` is allowed only from `setup_nomination` / `ready_for_voting`. The derived state is already `voting_active`, so `ConstitutionalTransitionGuard::assertAllowed()` **denies the action**. The officer's constitutional authority is extinguished by the passage of time.
7. **There is no recovery authority for a mis-set or expired window.** `canEditTimeline` is `false` in `VotingActive`, `Counting`, `ResultsPublished`, `Archived` and `Suspended`; and the timeline endpoint validates `voting_starts_at` as `after:now`, so **a past slot cannot be corrected at all, by anyone, in any state.**

**On authority:** the persisted `state` column is explicitly a compatibility cache (`ElectionLifecycleState` docblock: *"State column is compatibility cache only; engine is sovereign"*), and `Election::getCurrentStateAttribute()` — still carrying its Generation-1 docblock promising blocked states — now returns that cache. **Two "current state" accessors with different answers coexist on the same model.**

### The intent record contradicts itself

| Design document | Documented derivation of `voting_active` |
|---|---|
| `01_OVERVIEW.md` | **`voting_locked = true` AND `voting_starts_at ≤ NOW ≤ voting_ends_at`** — *includes the command fact* |
| `02_STATE_TRANSITIONS.md` | `voting_starts_at IS PAST AND voting_ends_at IS FUTURE` — *clock only* |
| **Implementation** | window open **AND** ≥1 approved candidate — *clock + candidate, no command fact* |

**Three documents, three different predicates.** No ADR governs lifecycle derivation authority (`docs/adr/` reviewed; none addresses it). **The current behaviour was therefore not adopted — it was arrived at.**

---

## 4 · Semantic comparison

Legend — **A** intentional business change · **B** migration regression · **C** Constitution gap · **D** legacy defect · **E** implementation defect · **F** unresolved.

| # | Business behaviour | Legacy (Gen 1 / Gen 2) | Constitution / Manifesto | Current Lifecycle | Same? | Class |
|---|---|---|---|---|---|---|
| 1 | **Time reaches window start** | Gen 1: → `voting`. Gen 2: → `voting` **only if** nomination complete, ≥ min candidates, no pending; else **`voting_blocked`** | **Silent** — describes only the `open_voting` command | → `VotingActive` if ≥1 approved candidate; **setup completion not checked** | ❌ | **B** (Gen 2 gate lost) + **C** (never ruled) |
| 2 | **Time reaches window end** | → `results_pending` if locked, else **`voting_ended_unlocked`** | **Silent** — describes only `close_voting` | → `Counting` if approved + admin + nomination complete; **otherwise logs a warning and falls through with no state** | ❌ | **B** (qualified state lost) + **E** (fall-through) |
| 3 | **Chief presses Proceed** (`open_voting`) | Gen 2: the sovereign act; sets state, audits actor, emits event | **Chief-only command; 3 preconditions; → `voting_active`** | Executes, **but overwrites the scheduled window with now → now+4d**; and is **denied outright if the clock already opened the window** | ❌ | **E** (schedule destroyed) + **B** (authority pre-emptable) |
| 4 | **Chief presses Stop** (`close_voting`) | Gen 2: command → `results_pending`. Gen 1/legacy guide: *"cannot manually transition out of voting"* | **Chief/deputy command; no preconditions; → `counting`** | Executes; sets `voting_ends_at = now`, engine then derives `Counting`. **Consistent.** | ✅ | — |
| 5 | **No approved candidates** | Gen 2: **`voting_blocked`** — named, not inferred | `EM-VOT-001/002/003` forbid entering voting. **State when the window is nonetheless open: `EM-OPEN-021`, OPEN** | `VotingActive` correctly refused; **falls through to whatever the derivation order yields** | ❌ | **B** (legacy had an answer) + **C** (open) |
| 6 | **No admitted voters** | Gen 2: not checked at the voting boundary | **`EM-VOT-003` ADOPTED** (≥1 admitted voter to enter Voting Active) | **Not checked on either path.** `has_voters` is a `complete_administration` precondition only | ❌ | **C** — adopted rule, implementation not authorised |
| 7 | **Expired time slot** | Gen 2: `voting_ended_unlocked`; `enforceVotingLock()` seals it as an explicitly non-constitutional operational act | **Silent** | `Counting` if setup complete; else warning + fall-through | ❌ | **B** + **C** |
| 8 | **Changing a FUTURE slot** | Gen 2: permitted while not locked | **Silent** — no constitutional rule on rescheduling | Permitted where `canEditTimeline` is true (Draft, Approved, Rejected, Setup*, ReadyForVoting) | ✅ | **C** (unruled but consistent) |
| 9 | **Changing a CURRENT slot** | Gen 2: `voting` is not editable once locked | **Silent** | Forbidden — `canEditTimeline = false` in `VotingActive` | ✅ | **C** (unruled but consistent) |
| 10 | **Changing a PAST slot** | Gen 2: no path found | **Silent** | **Impossible** — `canEditTimeline = false` in every post-voting state **and** `after:now` validation | ⚠️ | **C** — the stated business need (*Chief may correct a passed slot*) **has no authority anywhere** |
| 11 | **Recovery after unmet prerequisites** | Gen 2: named the condition (`*_blocked`) and stayed there; grace automation re-checked preconditions and **declined to act** when unmet | **Silent** | **No blocked vocabulary exists.** Election lands in a derivation-order artefact | ❌ | **B** — the recovery *concept* was deleted, not replaced |
| 12 | **Skipping an election level** | Gen 1: possible by clock. Gen 2: prevented — linear `TRANSITIONS`, and `canEnterVotingPhase()` required nomination complete | **Prevented** — `allowed_states` per action | **Possible on the derived path**: a scheduled window + one approved candidacy yields `VotingActive` without `complete_nomination` ever being performed | ❌ | **B** + **E** — *reachability asserted by inspection; measurement is Session 1's* |

---

## 5 · Authority reconciliation — which source actually governs today

| Question | Answer from evidence |
|---|---|
| **Which source is authoritative in practice?** | **The clock, via `ElectionLifecycleEngineImpl`.** The facade is the sole consumption point; the UI is a pure renderer of `allowedActions`; the persisted column is a cache; the Constitution's commands are *validated against* the derived state, so the derivation decides whether a constitutional act is even lawful. |
| **Which source is authoritative by governance?** | **The Manifesto for business rules, the Constitution for workflow rules** — per the standing separation and the standing clause *"the implementation must conform… never become the source from which the Manifesto is derived."* |
| **Is there a conflict?** | **Yes, and it is structural, not cosmetic.** The Constitution grants the Chief an exclusive power (`open_voting`, chief-only). The derivation can **exercise that power's effect without the Chief**, and can then **make the Chief's exercise of it unlawful** (§3.6). **A derived read model is currently overriding a constitutional grant of authority.** |
| **Was the override decided?** | **No.** No ADR governs it; the two design documents contradict each other and the code; the commit that removed the blocked states (`9f2cba4d`) is titled as a *write-barrier* change. **This is drift, not a decision.** |

**I do not resolve this conflict.** Resolving it is a Product Owner / ARB act. §7 lists exactly what must be decided.

---

## 6 · `EM-OPEN-021` reassessment

**Question:** what state is an election in when its voting window is open but it has no approved candidates?

| Test | Finding |
|---|---|
| **Did this situation exist in the legacy model?** | **Yes.** |
| **Was it reachable?** | **Yes** — Gen 1 reached it structurally (no candidate check at all); Gen 2 reached it whenever the clock passed `voting_starts_at` with `canEnterVotingPhase()` false. |
| **Was it intentionally prevented?** | **No — it was intentionally *named*.** Gen 2 did not prevent the condition; it gave it a first-class name, **`voting_blocked`**, and refused to call it `voting`. |
| **Does it exist only because of the new time-derived lifecycle?** | **No.** The *condition* predates the new lifecycle. **What the new lifecycle removed is the answer.** |

> **This materially changes the item, and the change should go to the PO.**
>
> `EM-OPEN-021` has been carried as *"an unresolved domain decision — the derivation order yields a fall-through state and no business rule exists."* **That framing is now incomplete.** A business answer **did** exist in this system's own history: **`voting_blocked`**, with the sibling states `nomination_blocked` and `voting_ended_unlocked`, and with a precise predicate (`canEnterVotingPhase()`).
>
> **The ARB's candidate list — `setup_nomination` · a holding state · another defined state · other — did not include it, because nobody had recovered it.** The second option, *"a holding state"*, now has a **concrete historical realisation** rather than being an abstraction. **I recommend nothing; I only report that the option set the PO was given was missing a real, previously-implemented candidate.**

**Is `EM-OPEN-021` obsolete, dependent, or still independently required?**

**Dependent — but not dissolved.** The prior session's insight (*"the new rule may DISSOLVE `EM-OPEN-021` — that state exists because the clock advances"*) is **half right**:

- **Correct:** if arrival of time no longer produced `VotingActive`, the *clock-arrival* route into the zero-candidate window-open condition would close.
- **Incomplete:** the condition is **also** reachable without the clock — via the recorded `forceCloseNomination()` reachability fact. **So the question survives a progression ruling and must still be answered.**

**Sequencing consequence:** ruling progression **first** is still correct, because it changes *how many* routes reach the condition and therefore *what the answer has to cover*. **It does not remove the need for the ruling.**

---

## 7 · Recommendation

### Verdict

> ### **3 — CURRENT ARCHITECTURE INTRODUCED A SEMANTIC REGRESSION**
>
> **with a correction to the commissioning hypothesis that must be recorded alongside it:**
>
> **The regression is NOT "command-driven progression became clock-driven."** That change never happened, because command-driven entry into voting **never existed**. The voting boundary has been clock-driven since Generation 1.
>
> **The regression is the deletion of the qualified ("blocked") lifecycle vocabulary and of the setup-completion gate on the derived path**, between Generation 2 and Generation 3, **without a decision, an ADR, or a replacement.**
>
> **And the business intent stated by the Product Owner — *"reaching the time does not by itself advance the election; the Chief explicitly proceeds"* — is a `C` (Constitution/Manifesto gap), not a `B`. It has never been implemented in ANY generation of this system.** Treating it as a regression to be "restored" would be historically false; it is a **new rule to be adopted**, and adopting it is a Product Owner act.

### Since the verdict is 3 — the smallest corrections required (NOT implemented, NOT authorised)

Stated as the minimum needed to close each confirmed defect. **Each requires its own authorization; none is recommended for bundling.**

| # | Confirmed defect | Smallest correction |
|---|---|---|
| **R-1** | The derived path into `VotingActive` omits the setup-completion gate that Gen 2 enforced (row 1, row 12) | **Restore the predicate, not the code**: the derived route requires the same completion facts the command route requires. **Business-rule question first** — this is a Manifesto/Constitution statement, then a RED test. |
| **R-2** | Voting-ended-with-incomplete-setup falls through with **no state chosen**, logging an anomaly (row 2) | The anomaly needs a **named** outcome. **This is the same question as `EM-OPEN-021`, one boundary later** — it must not be answered by derivation order either. |
| **R-3** | `open_voting` overwrites the committee's scheduled window with `now → now+4 days` (row 3) | Separate *"the Chief authorises this window"* from *"the Chief starts a new 4-day window now."* **Which one the business means is a PO question**, and the 4-day default has no recorded authority. |
| **R-4** | `voting_locked` carries two opposite meanings (§3.4) | Split the concept before any rule tries to depend on it. **Prerequisite to any command-driven progression rule** — a rule cannot be enforced through an ambiguous fact. |
| **R-5** | Two `current state` accessors on one model disagree; the Gen-1 docblock still promises blocked states (§3) | Documentation/API defect. Smallest fix is retirement of the stale accessor path — **but it is load-bearing for the 12 failing legacy tests below, so it must be sequenced after R-6.** |
| **R-6** | The legacy semantic specification is **RED and unreconciled** in the estate | `tests/Unit/Election/DerivedStateTest.php` — **12 of 12 failing** (measured this session; every case returns `draft`). `tests/Unit/Election/BusinessConditionsTest.php` — **3 failing**. These encode Gen-2 semantics against an accessor that no longer computes. **They must be dispositioned by decision — reconciled, or retired with a recorded rationale — never left silently red.** Disposition authority is not mine. |

### Not corrections — decisions

| Needs a decision, not a fix |
|---|
| Whether progression is **commanded** or **derived** at each boundary (the `C` gap, §2 Finding G-1) |
| Whether the Chief may **correct a passed time slot**, and under what authority (row 10 — today: nobody can, in any state) |
| `EM-OPEN-021`, now with `voting_blocked` added to the option set as a historical candidate (§6) |
| `EM-VOT-003`'s voter half — **adopted, unimplemented on both paths** (row 6) |

---

## Final output

**1 · Legacy business behaviour.** Setup phases advanced by **officer command**, with an opt-in grace-period fallback that re-checked preconditions and declined when unmet. **The voting boundary advanced on the clock in every generation.** Generation 2 qualified that clock: when the window opened but the election was unfit, it entered a named **`voting_blocked`** state rather than `voting`; likewise `nomination_blocked` and `voting_ended_unlocked`. Results were **never** published automatically.

**2 · Current business rule.** The Constitution grants `open_voting` to the **Chief alone** with three preconditions. The Manifesto binds candidate (and, per `EM-VOT-003`, voter) requirements at that boundary. **Neither states whether the passage of time may advance an election.** That authority question is unruled.

**3 · Current lifecycle behaviour.** State is derived from facts plus the clock. **Arrival of `voting_starts_at` plus one approved candidate produces `VotingActive` with no officer act, no setup-completion check, and no reading of the fact the officer's act writes.** Once that happens, the Chief's constitutional `open_voting` is **denied**. When the Chief does act in time, the act **overwrites the scheduled window**. A passed slot cannot be corrected by anyone.

**4 · Exact mismatches.** Twelve rows, §4. Confirmed divergences: rows 1, 2, 3, 5, 6, 7, 11, 12. Consistent: rows 4, 8, 9. Unruled-with-a-stated-business-need: row 10.

**5 · Likely source of each mismatch.** **B (regression)** for the deleted blocked vocabulary and the lost setup gate — introduced between `ece12ae5`/`9f2cba4d` and the current engine, with **no ADR and with two design documents that contradict each other and the code**. **C (Constitution gap)** for progression authority, rescheduling authority, and the voter precondition. **E (implementation defect)** for the schedule-overwriting side effect and the stateless fall-through. **D (legacy defect)** is *not* claimed anywhere — no legacy behaviour was found to be wrong; some was merely narrower than today's stated intent.

**6 · Impact on Election-Only.** **No Election-Only-specific impact identified.** Every finding sits on the election lifecycle, which is mode-independent; nothing here touches entitlement, admission or membership concepts. **The findings apply identically in both modes**, and this report proposes no mode-scoped rule.

**7 · Impact on the time-window governance rule.** **Decisive, and it inverts the drafting assumption.** A rule drafted as *"restore command-driven progression"* would be **historically false** — there is nothing to restore. The rule must be written as a **new adoption**: *what the arrival of a scheduled time means, and what it does not.* The evidence also shows it **cannot be enforced as drafted today**, because the only persisted trace of the Chief's act (`voting_locked`) is ambiguous (R-4) and unread (§3.3).

**8 · Reassessment of the no-candidate situation.** The condition **predates** the current lifecycle; the current lifecycle removed the **answer**, not created the question. A previously-implemented candidate answer — **`voting_blocked`** — exists and was **absent from the option set the PO was given**. The item is **dependent on the progression ruling but not dissolved by it**, because a non-clock route (`forceCloseNomination()`) remains.

**9 · Decisions required from Human / PO / ARB.**

| # | Decision |
|---|---|
| **D-1** | **Does the arrival of a scheduled time advance an election, or only make an act available?** Answer required **per boundary** — entry to voting and exit from voting may legitimately differ, and the legacy integrity rationale for automatic *closing* is on record. |
| **D-2** | If progression is commanded: **what happens when the window opens and no one acts?** (Legacy answer: an opt-in grace period acted for them, preconditions re-checked.) |
| **D-3** | **May the Chief correct or reschedule a time slot that has passed?** Today: no one can, in any state. |
| **D-4** | **`EM-OPEN-021`** — now to be decided with `voting_blocked` explicitly added to the candidate set as a historical option. |
| **D-5** | **Disposition of the 15 failing legacy semantic tests** (R-6) — reconcile or retire, with rationale recorded. Not an engineering convenience call. |
| **D-6** | Whether the **`voting_active` derivation predicate** is itself constitutional material (i.e. belongs in the Constitution rather than in an engine `if`). |

**10 · Does Session 4 need a formal architecture work item?** **Yes — one, and it should be raised now.** Scope: *"Election progression authority — is lifecycle state commanded or derived, and where is that predicate canonically expressed?"* It is an **architecture decision (ADR), not an implementation slice**, and **it is blocked on `D-1`**: architecture cannot choose the business meaning. Everything in R-1…R-6 is downstream of it. **Raising it as a backlog item is the correct next act; I have not created one, as creating work items is outside an investigation-only commission.**

**11 · Does Session 2 need a Constitution / Manifesto amendment?** **Yes — but not yet, and not the one that was being drafted.** The amendment must wait on `D-1`, and when it comes it is an **adoption of a new rule**, not a restoration. **Recommended order: `D-1` → ADR (Session 4) → Manifesto/Constitution expression (Session 2) → RED (Session 3) → verification (Session 1).** **Pausing the Constitution extension was the right call** — for a different reason than the one that motivated it: not because the old rule was lost, but because **no such rule ever existed to be recovered.**

**12 · Evidence locations.**

| Claim | Evidence |
|---|---|
| Gen-1 clock-driven derivation | `git show ece12ae5 -- app/Models/Election.php` (pre-image of `getCurrentStateAttribute()`) |
| Gen-2 blocked states + predicate | `git show 9f2cba4d^:app/Console/Commands/BackfillElectionState.php` → `determineState()` |
| Blocked states deleted | `9f2cba4d` (2026-05-20), *"Phase 3.3 — ElectionStateWriteContext controlled write barrier"* |
| Gen-2 command sovereignty | `Election::transitionTo()`; `TransitionMatrix::TRANSITIONS`; `ElectionStateTransition` |
| Legacy grace automation | `app/Console/Commands/ProcessElectionAutoTransitions.php:37-133` |
| Legacy voting-boundary rationale | `developer_guide/election/real_election/statemachine/STATES.md:186-241` *(evidence, not authority)* |
| Current derivation | `app/Application/Election/Services/ElectionLifecycleEngineImpl.php:64-169` (steps 4 and 5) |
| Command fact unread / overloaded | `Election::applySideEffectsForOpenVoting()` `:1818-1848`; `Election::enforceVotingLock()` `:1593-1602` |
| Schedule overwritten by command | `app/Models/Election.php:1832-1835` |
| Chief's act denied after clock opens | `ElectionConstitution::RULES['open_voting']['allowed_states']`; `ConstitutionalTransitionGuard::assertAllowed()` |
| No past-slot correction | `ElectionManagementController.php:1422` (`after:now`); `ElectionLifecycleEngineImpl::derivePermissions()` (`canEditTimeline`) |
| Preconditions registry | `ConstitutionalTransitionGuard::isPreconditionMet()` `:178-200` |
| Contradictory design intent | `state_machine_new/01_OVERVIEW.md` (voting phase table) vs `02_STATE_TRANSITIONS.md:180-215` |
| No governing ADR | `docs/adr/` — reviewed; none addresses lifecycle derivation authority |
| Legacy spec is RED | `tests/Unit/Election/DerivedStateTest.php` 12/12 fail · `tests/Unit/Election/BusinessConditionsTest.php` 3 fail (measured 2026-08-15) |
| Prior review never reached the engine | `architecture_legacy/backend/discoveries/20260613-election-state-machine-analysis.md` §"Not Yet Investigated" — `ElectionLifecycleEngineImpl.php` listed as **not examined** |

---

**Boundary restated on exit.** Nothing was implemented, repaired, authorised or decided. No business rule was chosen. `EM-OPEN-021`, `EM-VOT-003` and all open items remain open. The 15 failing tests were **measured, not modified**. Session 3 is not redesigned; Session 1's verification role is not pre-empted — every reachability claim marked *by inspection* awaits their measurement.
