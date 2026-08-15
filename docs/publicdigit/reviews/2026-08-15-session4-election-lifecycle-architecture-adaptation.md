# Election Lifecycle — Architecture Adaptation to `EM-VOT-004` / `EM-GOV-004`

**Type:** Architecture investigation + design (Session 4) · **Date:** 2026-08-15
**Authority basis:** PO/ARB adoption `3d31df2b` — `EM-VOT-004` (start-of-voting progression), `EM-GOV-004` (schedule-correction principle)
**Predecessor evidence:** `2026-08-15-session4-election-progression-semantic-reconciliation.md`
**Status:** **Architecture authorized · implementation NOT authorized.** No production code, test, Constitution or Manifesto modified. No business rule invented. Two STOP conditions reached and reported rather than guessed (§19).

**Review outcome, 2026-08-15 — Principal Architect: ✅ ACCEPTED as an architecture investigation/design result. Implementation NOT granted.** Direction, structural approach, three-concept separation, refusal model and the decision not to restore `voting_blocked` all accepted. **One defect was returned for sharpening — the meaning of the authorization fact under schedule correction — corrected in §5a**, which supersedes the original bare-boolean formulation; **the revision was reviewed and accepted**, with the window binding and the resulting monotone closure recorded as an improvement on the first design.

**Two qualifications attached to the acceptance, binding on later work:**
1. **Architecture states the *invariant*, not the *mechanism*.** The binding invariant is *"a previous authorization must never silently authorize a different voting window."* **Whether that is achieved by supersession, invalidation, withdrawal-then-re-authorization or schedule versioning is an Architecture decision deferred until `G-4`/`G-5`/`G-5a` are ruled.** An earlier draft asserted *"any correction supersedes the authorization"* — withdrawn, because naming the mechanism would have decided business rules Governance has not made. §5a now records the invariant, the candidate mechanisms, and the four constraints any mechanism must satisfy.
2. **The cron must not simply be deleted.** The predicate change already removes its reach into voting; its remaining grace behaviour is a separate untouched authority question (`G-6`).

**Handoff, 2026-08-15: the next responsible actor is Session 2 — Governance**, to produce a **Tier-1 Business Decision Request covering `G-1` and `G-2` only**, then `G-3`. **Not Session 3. Not Session 1. Not Architecture again, except to incorporate Governance's answers and finalize ADR-A/B/C.** **Architecture's work for this stage is complete.**

---

## 1 · Executive architecture decision

> **The defect is not that the wrong paths are unguarded. It is that `VotingActive` is a *predicate* rather than a *decision*.**
>
> Today `VotingActive` is recomputed on every read as `window_open(now) ∧ has_approved_candidate`. A predicate over time **becomes true by itself** and **remembers nothing**. No amount of guarding can make such a predicate express an officer's authority, because the predicate is re-evaluated after every guard has finished running.
>
> **The adaptation is one structural change:** admit a **recorded constitutional fact** representing the officer's decision, and make it a required conjunct of the state.
>
> ```
> BEFORE   VotingActive  ⟺  within(window, now) ∧ hasApprovedCandidate
> AFTER    VotingActive  ⟺  progressionAuthorized ∧ within(window, now) ∧ ¬closed
> ```
>
> `progressionAuthorized` is **written only by the Chief's authorized act**, and that act is accepted only when every mandatory condition holds at that moment.

**Why this is the right shape, and not merely one option among several:**

The brief (§8) requires that *no* alternate path bypass the rule. There are two ways to achieve that:

| Strategy | Cost | Failure mode |
|---|---|---|
| **Guard every path** | O(number of paths) — and I found **seven** live ones (§9) | Fails on the eighth. Every future writer of a window fact silently becomes a new path into voting. **The reconciliation report exists because exactly this happened once already.** |
| **Change the predicate** | O(1) — one conjunct | **Closed under future paths.** A path that cannot write the authorization fact cannot produce voting, no matter what else it writes. |

The second is not just cheaper; it is the only one that makes the guarantee **structural** rather than **vigilant**. Under it, all seven paths I found become harmless without individually being repaired: they can still make an election *eligible*, and none of them can make it *authorized*.

**This directly instantiates the adopted principle.** *"Time makes progression possible"* → the clock is a **delimiting** conjunct (`within(window, now)`), bounding a state it can no longer create. *"Authority and eligibility make it valid"* → authority is the **constitutive** conjunct (`progressionAuthorized`), and eligibility is what the act must satisfy before that fact may be written.

---

## 2 · The business rule being implemented architecturally

`EM-VOT-004`, decomposed into the three architectural obligations it creates:

| Business clause | Architectural obligation |
|---|---|
| *"does not enter voting by the passage of time"* | The clock must be **insufficient** for `VotingActive` — a non-clock conjunct is required |
| *"reaching the scheduled start … confers no authority"* | Time must be **necessary but not sufficient**, and must remain expressible as *eligibility* without becoming *state* |
| *"the officer must explicitly decide to proceed"* | The decision must leave a **durable, attributable fact** that the state derivation reads |
| *"progression is valid only if, at that moment, every mandatory condition holds"* | The conditions are evaluated **at the instant of the act**, not continuously, and the act is **refusable** |
| *"the officer is told which condition is unmet … may decide to proceed again"* | Refusal is an **expected business outcome carrying a reason**, not an error; and it is **non-terminal** |
| Authority boundary: *"no clock-driven mechanism may exercise the Chief's authority"* | No derivation may write the authorization fact |
| Authority boundary: *"no Chief action may override a mandatory condition"* | The act evaluates conditions **inside** the authority boundary; there is no privileged path that skips them |
| *"must not bypass … through administrative authority or schedule manipulation"* | Schedule writes and administrative acts must be **structurally incapable** of producing `VotingActive` |

---

## 3 · Current architecture violations

All measured this session by inspection of the running code. Severity is architectural, not operational.

| # | Violation | Evidence |
|---|---|---|
| **V-1** | **The clock alone produces `VotingActive`.** No officer act is required or consulted. | `ElectionLifecycleEngineImpl::getState()` step 5 |
| **V-2** | **The officer's act writes a fact the authority ignores.** `open_voting` sets `voting_locked`; the derivation never reads it. | `Election::applySideEffectsForOpenVoting()` `:1837` vs engine step 5 |
| **V-3** | **`voting_locked` carries two opposite meanings** — *"the Chief opened this"* and *"this is sealed shut after expiry"*. It is unusable as an authority term in its present form. | `:1837` vs `Election::enforceVotingLock()` `:1593` |
| **V-4** | **The officer's act destroys the schedule it should honour.** `open_voting` overwrites `voting_starts_at = now()`, `voting_ends_at = now()+4d`. | `:1832-1835`, with the comment *"This ensures engine derives VotingActive state"* |
| **V-5** | **Time extinguishes the officer's constitutional authority.** Once derived `voting_active`, `open_voting` is outside `allowed_states` and the guard **denies the Chief**. | `ElectionConstitution::RULES['open_voting']`; `ConstitutionalTransitionGuard::assertAllowed()` |
| **V-6** | **`completeNomination()` opens the voting window as a side effect** — *"Auto-set voting dates if not already set"* → `voting_starts_at = now()`. Combined with step 5, **completing nomination can put an election straight into `VotingActive`.** | `Election::completeNomination()` `:1386-1392` |
| **V-7** | 🔴 **A background job can start an election.** `ProcessElectionAutoTransitions` calls `completeNomination(..., actorId: null)` after a grace period — triggering V-6 with **no human actor at all**, bypassing `transitionTo()` and the guard entirely. | `ProcessElectionAutoTransitions.php:69` |
| **V-8** | **Election creation accepts an already-open window.** The create path validates `date_format` only — **no `after:now`** — so an election may be born with `voting_starts_at` in the past. | `ElectionManagementController.php:135,173` |
| **V-9** | **Approving a candidacy can flip the state.** With a window already open and zero approved candidates, the approval supplies the missing conjunct and the election becomes `VotingActive` — the reviewer is an unwitting path into voting. | `CandidacyReviewController.php:105` + engine step 5 |
| **V-10** | **Schedule editing is a deferred path into voting.** A window set to open in one minute produces `VotingActive` one minute later with no further act — the precise circumvention `EM-VOT-004` forbids. | `updateVotingDates()` `:1230`; `updateTimeline()` `:1422` |
| **V-11** | **`after:now` on schedule edits contradicts `EM-GOV-004`**, which explicitly permits correcting *"a window whose time has already elapsed"*. The adopted rule cannot be implemented against the current validation. | `:1226`, `:1422` |
| **V-12** | **The derivation is the live enforcement authority, not a display.** `EnsureVotingActive` and the controllers gate ballots on `ElectionLifecycle::canVote()`, which reads the snapshot. **A derived `VotingActive` means ballots are actually accepted.** | `EnsureVotingActive::handle()` |

> **V-7 is the most serious.** It is the exact situation the authority boundary was authorized to prevent: **a clock-driven mechanism exercising the Chief Election Officer's authority**, with no actor recorded. It is live in `elections:process-auto-transitions`.

---

## 4 · Target authority model

Three concerns are currently fused into one computation. The adaptation separates them:

```
   TIME                    OFFICER                   ELECTION RULES
     │                        │                            │
     ▼                        ▼                            ▼
 eligibility            authorization                 permission
"may this now be       "I decide to               "is progression
 considered?"           proceed"                    actually allowed?"
     │                        │                            │
     └────────────────────────┴────────────────────────────┘
                              │
                              ▼
                    VotingActive  (result of all three)
```

| Concern | Owner | Nature | May write the authorization fact? |
|---|---|---|---|
| **Eligibility** | the schedule + the clock | **computed**, continuous, stateless | ❌ never |
| **Authorization** | the Chief Election Officer | **decided**, discrete, recorded | ✅ **only this** |
| **Permission** | the Election's mandatory conditions | **evaluated at the instant of the act** | ❌ — it *constrains* the write, never performs it |

**The two authorized boundary statements map onto this exactly:**
- *No clock-driven mechanism may exercise the Chief's authority* → the left column can never write the fact.
- *No Chief action may override a mandatory condition* → the middle column's write is gated by the right column, **inside** the same operation, with no bypass parameter.

---

## 5 · Target lifecycle semantics

> **⚠️ Sharpened twice on 2026-08-15 following Principal Architect review.** ① The first formulation used a bare boolean `progressionAuthorized` — **defective**, see §5a. ② The second stated *supersession-by-comparison* as the model; **that over-specified**, because choosing the mechanism pre-empts business rules Governance has not yet made. **The model is now stated as an invariant plus the constraints any mechanism must satisfy. The mechanism itself is deferred.**

**The architectural invariant — this much is forced by adopted rule and Architecture may state it:**

> **A previous authorization must never silently authorize a different voting window.**
>
> Equivalently: **voting is active only while a valid authorization exists *for the window currently in force*.**

```
VotingActive  ⟺  ∃ authorization A
                 ∧ A authorizes the election's operative window     ← the invariant
                 ∧ within(A.window, now)

Counting      ⟺  ∃ authorization A
                 ∧ past(A.window.end, now)
```

**`A.window` is the window recorded in the authorization — not whatever the schedule currently says.** That is what makes the invariant checkable at all, and it is the property the two derivations below depend on.

Everything else in the 12-step derivation is untouched.

## 5a · Authorization binding — the defect the review caught, and why the fix is forced

**The Principal Architect asked what happens to `progressionAuthorized` when the schedule is later corrected. Working the case through shows the bare boolean fails, and fails in exactly the way the adopted rule forbids:**

```
10:00  Chief authorizes.  Window 10:00-12:00.  Conditions verified.  Voting live.
10:30  Someone corrects the window to 14:00-16:00.
       within(window, now) is now false  ->  election leaves VotingActive.
14:00  within(window, now) becomes true again.
       progressionAuthorized is STILL true.
       ->  VOTING RESTARTS. No decision was made. The clock did it.
```

**That is precisely the circumvention the authorization named:** *"must not bypass, suppress, or override a refusal through administrative authority or schedule manipulation."* **A bare boolean is therefore excluded by adopted rule, not by preference.**

**The root cause is that a boolean is under-specified.** It records *that* authorization happened; it cannot record *what was authorized*. But the Chief never authorized "voting, in general" — the Chief authorized **this window**, on the basis of conditions verified **at that moment**. The fact must carry that binding, or it silently generalizes a specific decision into a standing permission.

**The correction: the authorization fact carries the window it authorized, immutably.** A schedule correction produces a window the existing authorization does not cover, so the authorization is **superseded** rather than inherited. The Chief must decide again — which is the adopted rule working as intended, not an inconvenience.

### Three properties fall out, and each closes a hole the boolean left open

| Property | Mechanism |
|---|---|
| **Schedule correction cannot restart voting** | A corrected window is never the authorized window, so the clock has nothing to re-satisfy |
| **A closed election cannot be resurrected** | `Counting` keys off the **authorized** window, which is immutable. Correcting the schedule after closure leaves `past(authorizedWindow.end)` true — the election stays in counting. **Closure is monotone without needing a job, a flag, or a sweep to record it** |
| **Extending a live vote requires a fresh decision** | An extension supersedes the authorization; voting stops until the Chief authorizes against the new window. Boundary 2 (*extending*) thereby gets a **structural** answer — *"it requires re-authorization"* — without Architecture deciding the **policy** question of whether extending is permitted at all |

### Invariant vs mechanism — the line Architecture must not cross yet

**Refined 2026-08-15 at PA direction.** An earlier draft said *"any correction supersedes the authorization."* **That named a mechanism, and naming a mechanism here would decide business rules Governance has not made.** The distinction now held:

| | Whose call | Status |
|---|---|---|
| **The invariant** — a prior authorization must never silently authorize a different window | **Architecture may state it**; it is *forced* by the adopted anti-circumvention clause | ✅ **Stated** |
| **The mechanism** — *how* the system stops a prior authorization from applying to a changed window | **Architecture decides — but only after `G-4`/`G-5`/`G-5a` are ruled** | ⏸ **Deferred** |

**Candidate mechanisms, recorded so the option set is auditable. None is chosen:**

| Mechanism | Shape |
|---|---|
| **Supersession by comparison** | The derivation compares the authorized window to the operative one; a mismatch simply fails the conjunct |
| **Active invalidation** | The correction marks the authorization void as part of its own act |
| **Withdrawal then re-authorization** | Correction is *refused* while an authorization is live; the Chief must withdraw first, as a distinct audited act |
| **Schedule versioning** | The authorization cites a schedule version; a correction mints a new version the old authorization does not name |

**Constraints any chosen mechanism must satisfy — this is Architecture's genuine contribution at this stage, and it holds regardless of which is picked:**

1. **It must not erase the authorization record.** Monotone closure (§5 property 3) depends on the authorized window remaining readable after the fact. A mechanism that *deletes* authorizations would reopen the resurrection hole it was meant to close.
2. **It must be attributable and auditable**, on the same footing as the authorization itself — otherwise the act that ends an authorization is less accountable than the act that created it.
3. **It must fail closed on ambiguity.** Where it cannot be determined that an authorization covers the operative window, voting must not be active.
4. **It must not require a background sweep.** A mechanism depending on a job to notice a stale authorization reintroduces a clock-driven actor into the authority path — the defect this whole adaptation removes.

**Honest cost, stated rather than buried, because it will drive the ruling:** the strictest reading — *any* correction ends the authorization — means a correction made during live voting **stops voting immediately**. For integrity that is arguably right; a form save should not silently extend a ballot period. But it is a sharp operational edge, it sits inside open boundaries 2 and 6, and **Governance may well prefer an explicit withdrawal/re-authorization flow to an abrupt stop.** → **`G-4` · `G-5` · `G-5a`.**

### Properties of the sharpened model, each discharging an obligation from §2

1. **Time cannot create voting.** No authorization fact exists until an act writes it. *(discharges V-1, V-6…V-10 simultaneously)*
2. **Automatic closing is preserved by construction.** The added conjunct is on the **entry** side only. When `now` passes the authorized window's end, `within` becomes false and `Counting` follows — with no act, exactly as `EM-VOT-004` note 3 requires. **Start and end remain deliberately asymmetric.** *(§11)*
3. **Closure is monotone.** Because `Counting` keys off the **authorized** window rather than the current one, no later edit can walk an election backwards out of counting. This is stronger than the first formulation, which needed `¬closed` as a separate recorded fact — and would have required a job or a sweep to record it. **The binding removes that mechanism entirely.**
4. **The stateless fall-through disappears.** Today `Counting` re-checks `approved_at ∧ administration_completed ∧ nomination_completed` and, when unmet, logs an anomaly and **returns no state**. Under the target those conditions were necessarily true when authorization was granted, so `Counting` follows from the authorization and the authorized window alone. The anomaly branch becomes unreachable rather than unhandled.
5. **The mandatory conditions need no re-evaluation on read.** They were verified at the instant of the act and are sealed into the fact. The derivation stays cheap, and — more importantly — **an election cannot silently leave voting because a condition changed underneath it** (a candidate withdrawing mid-vote must not void the ballot period; that is a governance matter, not a derivation side effect).

---

## 6 · Clock interaction model — and a STOP

The clock's role changes from **constitutive** to **delimiting**. That is the whole of its architectural demotion, and it is what *"time makes progression possible"* means in code.

**Required architectural change regardless of the open decisions:** `ElectionClockService` is a static facade over global `now()`. It cannot be pinned, injected or scoped to an election's timezone. It must become an **injectable temporal port** on the domain boundary, so that (a) the authorization instant is testable and deterministic, and (b) whichever timezone rule the PO adopts is absorbed at one seam without re-touching the domain.

### ⛔ STOP — the meaning of *"the scheduled start time has been reached"* is not currently determinable

`EM-VOT-004` makes the scheduled start a **mandatory condition**. Two adopted-but-unresolved defects make that condition unevaluable:

| Blocker | Why it blocks this architecture |
|---|---|
| **`PBDIGIT-59`** — *which timestamps are constitutional?* | **Two column pairs describe one concept.** The engine reads `voting_starts_at`/`voting_ends_at`; the officer sets `start_date`/`end_date`. They disagree on 4 of 4 elections. Worse for us: **`voting_ends_at` is overwritten by the act of closing** (measured: `15:28:44`) and **`voting_starts_at` is overwritten by the act of opening** — so the column the engine treats as *the schedule* is in fact *the actual*. **A rule that says "the scheduled time has been reached" cannot be implemented against a column that records when the act happened.** |
| **`PBDIGIT-67`** — *schedule input interprets local time as UTC* | The stored instant is **60–120 minutes wrong, DST-variable**, and live. *"Has the scheduled start been reached?"* would be answered against an instant the officer never chose. |

**These are not adjacent concerns — they are the definition of one of the four mandatory conditions.** Per brief §20 I stop at this boundary and do not choose a clock interpretation.

**What is NOT blocked:** everything else in this report. The authority model, the authorization fact, the eligibility/refusal separation, the path closure and the schedule-correction seam are all independent of *which* timestamp is authoritative. **Recommendation: proceed with the architecture; treat the clock predicate as a named seam behind the temporal port, and let `PBDIGIT-59`/`67` fill it.** This keeps the two decisions genuinely separable rather than bundling them.

---

## 7 · Chief authority model

The Chief's act is a **command that may be refused** — and refusal is a **business outcome, not an error**.

```
Chief → RequestVotingProgression(electionId, actorId, reason?)
              │
              ▼
     ┌────────────────────────────────┐
     │ evaluate mandatory conditions  │   ← inside the authority boundary
     │   · scheduled start reached    │      (no bypass parameter exists)
     │   · ≥ 1 approved candidate     │
     │   · ≥ 1 admitted voter         │
     │   · nomination completed       │
     │   · no constitutional          │
     │     prohibition                │
     └───────────────┬────────────────┘
              ┌──────┴──────┐
           all hold      any unmet
              │              │
              ▼              ▼
    record authorization   Refusal(unmetConditions)
    fact + emit event      · nothing written
              │            · state unchanged
              ▼            · officer may correct and retry
        VotingActive
```

**Two design points worth stating explicitly, because both are currently violated:**

- **Authority is exclusive but not unlimited.** `open_voting` is already chief-only in the Constitution; that stays. What changes is that the condition evaluation moves *inside* the same operation as the fact write, so there is no ordering in which authority is exercised without permission being checked.
- **Refusal must not be modelled as an exception.** Today an unmet precondition throws `InvalidTransitionException`. `EM-VOT-004` makes refusal a **foreseen, named, recoverable outcome** — the officer *"is told which condition is unmet, may correct it, and may decide to proceed again."* Modelling a foreseen business outcome as an exception loses the structured reason and encourages callers to treat it as a fault. **Recommend a returned refusal result; the exception may remain for genuinely exceptional cases (unknown action, unauthorized role).** *(Session 3 boundary item, §21.)*

---

## 8 · Mandatory-condition evaluation model, and the representation decision

### The three business situations — and why they are **not** three states

`EM-VOT-004` note 4 requires three distinguishable situations. My recommendation is **one lifecycle state plus a first-class eligibility assessment**, not three states:

| Situation | Question it answers | Correct home |
|---|---|---|
| **A** — not yet eligible | *"Has the scheduled moment arrived?"* | a **pure function** of schedule + clock. No fact, no state. |
| **B** — eligible, awaiting the officer | *"May the officer act now?"* | the same function, now true → the **Proceed capability** becomes available |
| **C** — cannot proceed, condition named | *"What is stopping this?"* | the **outcome of an evaluation**, carrying the unmet conditions |

**Why C must not become a state.** A state answers *"where is this election in its life?"* — C answers *"why did the last attempt fail?"* Encoding C as a state forces the system to compute *"why can't this proceed"* continuously, for every election, on every read — which is precisely the mistake being corrected: **a condition masquerading as a decision.**

**This is also the principled answer to the `voting_blocked` question (brief §7).** Generation 2 needed *three parallel* blocked states — `nomination_blocked`, `voting_blocked`, `counting_blocked` — because it encoded *"why not"* into the state name. That combinatorial growth is the smell: the state axis was being made to carry a second, orthogonal axis. **The business meaning Governance described survives intact under the separation; only the state-shaped encoding is declined.** The historical name is therefore neither restored nor its concept discarded — it is **relocated to the axis it belongs on**, satisfying `EM-VOT-004` note 4 without adopting the vocabulary the PO declined.

### Where it lives

`ElectionLifecycleSnapshot` is already the officer-facing read model and already carries `allowedActions` and `blockedReason`. It is the correct home for the eligibility assessment — a value object naming the unmet conditions in business terms. **No new read model, no new projection, no new UI contract shape.**

### ⚠️ A mandatory-condition question Architecture must NOT settle

The historical evaluator `Election::canEnterVotingPhase()` requires **`pending_candidacies_count === 0`** — *"no candidacy application is still awaiting review."*

**That condition is NOT in `EM-VOT-004`'s adopted set of four.** Architecture must neither silently preserve it (inventing a constitutional condition) nor silently drop it (discarding a possibly-intended safeguard). **→ Governance, §19.**

Two further hazards in the same evaluator, recorded as engineering findings rather than business questions: it reads the **denormalized counter** `candidates_count` rather than approved candidacies — while the Constitution's guard correctly reads `candidacies()->where('status','approved')` — and it applies a `min_candidates_for_voting` config value that has no recorded business authority. **Condition inputs must be the authoritative relations, not caches.**

---

## 9 · All-path authority matrix

Every path capable of producing `VotingActive`. Because state is derived, **any writer of a conjunct is a path** — this is why the list is longer than the command surface.

| # | Path | Produces `VotingActive` today? | Should it? | Authority exercised | Conditions checked today | Bypasses `EM-VOT-004`? | Architectural guard |
|---|---|---|---|---|---|---|---|
| **P1** | `open_voting` via `transitionTo()` | ✅ | ✅ **the only one** | Chief (correct) | window defined · timezone set · approved candidate | ⚠️ partially — no voter, no nomination check | Becomes the **sole writer** of the authorization fact; condition set completed |
| **P2** | **Clock arrival** on a scheduled window | ✅ | ❌ | **none — no actor** | candidate only | 🔴 **yes — the headline violation** | Dissolved: clock is demoted to a delimiting conjunct |
| **P3** | **`completeNomination()`** auto-opening the window | ✅ | ❌ | `manageSettings` policy, **not** chief-only; **guard not invoked** | pending = 0 · ≥1 approved | 🔴 **yes** | Dissolved by the predicate; **additionally the window side effect must be severed** |
| **P4** | 🔴 **`elections:process-auto-transitions` cron** → P3 | ✅ | ❌ | **none — `actorId = null`** | posts · voters · no pending | 🔴 **yes — a machine exercising the Chief's authority** | Dissolved by the predicate; **the job's authority must be explicitly re-scoped** |
| **P5** | **Election creation** with an already-open window | ✅ *(once a candidate is approved)* | ❌ | creator | `date_format` only — **no `after:now`** | 🔴 yes | Dissolved by the predicate |
| **P6** | **Candidacy approval** completing the predicate | ✅ | ❌ | reviewer | none relevant | 🔴 yes | Dissolved by the predicate |
| **P7** | **Schedule edit** scheduling imminent opening | ✅ *(deferred)* | ❌ | `manageSettings` | `after:now` | 🔴 yes — *"schedule manipulation"*, named in the authorization | Dissolved by the predicate; §10 |
| **P8** | **`resume`** from suspension | ✅ | ❌ | chief / platform admin | none — engine re-derives | 🔴 yes | Dissolved: re-derivation cannot manufacture the fact |
| **P9** | `forceCloseNomination()` | ❌ *(does not set the window)* | ❌ | `manageSettings` | none | no — **but it is the surviving `EM-OPEN-021` route** | Unchanged; §17 |
| **P10** | Factories / seeders / demo provisioning | ✅ | n/a — non-production | none | none | n/a | Dissolved by the predicate; **demo must be re-checked**, since demo elections cannot currently reach voting at all (`PBDIGIT-59`) |
| **P11** | `election:start-voting` console command | ❌ — operates on `config('election.*')`, not the aggregate | ❌ | none | none | no | **Legacy remnant**; disposition §16 |

**Eight of eleven paths bypass the adopted rule today. Seven of the eight are closed by the single predicate change** — which is the argument for §1's strategy, stated as measurement rather than preference.

---

## 10 · Schedule-correction architecture (`EM-GOV-004`)

**The adopted invariant — *"a schedule correction never by itself advances the election"* — is satisfied by construction** under §5: correction writes the **window**, and the window is no longer sufficient for `VotingActive`. Nothing further is needed to honour that clause. This is a second dividend of the predicate change.

**What the five *adopted* qualifiers do require architecturally** (all five are adopted, so this much is within authority):

| Qualifier | Architectural requirement |
|---|---|
| never advances the election | ✅ structural — the window is not a sufficient conjunct |
| never bypasses a mandatory condition | Correction must be a **distinct command** with **no ability to write the authorization fact** |
| attributable to the officer | Actor recorded on the correction, not only in a log line |
| carries a stated reason | Reason is a **required input**, not optional metadata |
| auditable | The correction is an **auditable act in its own right**, distinct from the generic model-save path it uses today |

**A required change that is *forced* by the adoption:** `after:now` (`:1226`, `:1422`) **must be removed as a validation rule**, because `EM-GOV-004` explicitly permits correcting an elapsed window. But **what replaces it is one of the six un-adopted boundaries.** Architecture therefore specifies a **policy seam** — a named extension point that is deliberately empty — rather than a rule. Leaving `after:now` in place would silently veto an adopted rule; replacing it with any concrete limit would silently invent policy. **The seam is the only honest option.**

**Explicitly NOT decided here** (the six open boundaries): moving a window forward · extending a window · correcting an elapsed window · moving a window backward · effect on issued credentials · permissibility once votes exist. **→ Governance, §19.**

**Correction after authorization — governed by the §5a invariant.** An earlier draft flagged this as an unprotected gap: a correction made after authorization would move a **live** ballot period. The binding closes it at the level of the invariant — **a prior authorization can never silently authorize the corrected window** — which is exactly `EM-GOV-004`'s first qualifier, *"never by itself advances the election."*

**What remains open is both the policy and the mechanism**, and they must be ruled in that order: whether correcting a live authorization is permitted at all (`G-4`, `G-5a`), and what should happen to the authorization when it is (`G-5`). **Architecture will select the mechanism once those are answered — not before.** Boundaries 2 and 6.

---

## 11 · Automatic-closing preservation

`EM-VOT-004` note 3 is explicit that the scope is the **start** of voting and that automatic closing is unchanged. The architecture preserves this **by construction, not by convention**:

| Boundary | Mover | Mechanism under the target |
|---|---|---|
| **Entry** | the Chief, subject to conditions | requires `progressionAuthorized` — a fact only the act writes |
| **Exit** | the clock | `within(window, now)` becomes false → `Counting`. **No act, no fact, no new conjunct.** |

The asymmetry is deliberate and is now **visible in the model itself**: the conjunct was added only on the entry side. A future reader can see that start and end differ *by design* rather than by accident — which is precisely what the reconciliation found had been lost.

> ⚠️ **Do not let the new conjunct leak into the exit predicate.** If `Counting` were ever made to require an act, the adopted rule would have been silently generalized into *"progression is always manual"* — which note 3 forbids. **This is a standing architectural constraint for Session 3 (§21) and a verification obligation for Session 1 (§22).**

---

## 12 · DDD responsibility mapping

| Concern | Element | Layer | Notes |
|---|---|---|---|
| **Aggregate boundary** | `Election` — **unchanged** | Domain | Progression is an invariant *of* the Election. **No new aggregate; no new bounded context.** |
| **Authoritative domain decision** | *"may progression be authorized now?"* | Domain | A pure policy over the election's facts + the temporal port. No framework. |
| **Domain invariant** | `VotingActive ⟹ progressionAuthorized` | Domain | The one invariant the whole adaptation exists to protect. |
| **Value object** | the refusal — the named unmet conditions | Domain | Immutable; business vocabulary; no state names. |
| **Application command** | `RequestVotingProgression(electionId, actorId, reason)` | Application | Orchestration only: load → evaluate → record-or-refuse → emit. Returns the refusal; does not decide policy. |
| **Domain event** | voting progression authorized | Domain | `VotingOpened` exists and is dispatched today — **reuse, do not invent.** |
| **Read model** | `ElectionLifecycleSnapshot` + eligibility assessment | Domain VO / Application | Existing shape extended, not replaced. |
| **Authorization boundary** | `ConstitutionalTransitionGuard` + `ElectionConstitution::RULES` | Application / Domain | **Existing and correct** — chief-only is already right. It gains the completed condition set. |
| **Temporal concept** | injectable clock port | Domain port / Infra adapter | Replaces the static `ElectionClockService`. Absorbs `PBDIGIT-59`/`67` at one seam. |
| **Audit concept** | `ElectionStateTransition` | Infrastructure | **Already records actor, timestamp, reason for `open_voting`.** It stays the audit trail — but see below. |
| **Persistence of the decision** | a **first-class fact on the aggregate** | Infrastructure | **Recommended over reading the audit table.** An audit log should be *derivable from* facts, never the *source* of them; and a derivation that scans a transition table couples the hot read path to history. |
| **Anti-corruption** | `voting_locked` | — | **Must be split before it can carry authority.** Under the target the "opened" meaning moves to the authorization fact, leaving `voting_locked` with only the sealing meaning — or retired. |

**Deliberately not introduced** (brief §14): no state-machine framework, no lifecycle-engine replacement, no new context, no aggregate redesign, no event-sourcing, no CQRS split beyond what exists. **The adaptation is one fact, one value object, one port, and one conjunct.**

---

## 13 · Existing-component impact

| Component | Impact | Kind |
|---|---|---|
| `ElectionLifecycleEngineImpl::getState()` | steps 4–5 gain the authorization conjunct | **behavioural — the core change** |
| `ElectionConstitution::RULES['open_voting']` | preconditions completed: **+ admitted voter, + nomination completed, + scheduled-time-reached** | **constitutional expression — see §18** |
| `Election::applySideEffectsForOpenVoting()` | must **stop overwriting the window**; must write the authorization fact | behavioural |
| `Election::completeNomination()` | window side effect (`:1386-1392`) must be **severed** — it is not nomination's business to schedule voting | behavioural |
| `ProcessElectionAutoTransitions` | must lose the ability to reach voting; its remaining legitimate scope needs re-statement | **behavioural + authority** |
| `ElectionClockService` | static facade → injectable port | structural |
| `voting_locked` | split or retire | structural |
| `Election::getCurrentStateAttribute()` | stale second accessor still promising Generation-1 blocked states | **documentation/API debt** |
| `ElectionLifecycleSnapshot` | gains the eligibility assessment | additive |
| `EnsureVotingActive`, controllers, `Management.vue` | **no change** — they consume `canVote()` / `allowedActions` and stay pure consumers | none |
| `updateVotingDates` / `updateTimeline` | `after:now` removed; correction becomes an attributable, reasoned, audited act | **blocked on the six open boundaries** |

**The UI needs no restructuring.** It already renders `allowedActions` and denial reasons — the Proceed button reappears simply because the state no longer skips past `ReadyForVoting`. That the frontend is unaffected is evidence the existing capability-snapshot architecture was sound; the defect was in what fed it.

---

## 14 · Legacy reconciliation

| Category | Content |
|---|---|
| **Historical behaviour** | Every generation derived the voting boundary from the clock. Generation 2 qualified it with `canEnterVotingPhase()` and named the failure `voting_blocked`. |
| **Historical defect** | None established. Generation 2 was *narrower* than the new rule, not wrong. |
| **Migration regression** | The blocked vocabulary and the setup-completion gate were deleted (`9f2cba4d`) with no ADR. |
| **Current defect** | V-1…V-12 (§3) — of which **V-6, V-7, V-8, V-9, V-10, V-11 are newly found this session**. |
| **Newly adopted business rule** | `EM-VOT-004`. **Not a restoration.** |

> **Stated for the record, per brief §15:** `EM-VOT-004` **does not restore legacy behaviour.** No generation required the Chief's act. The Generation-2 `canEnterVotingPhase()` evaluator is **evidence that a condition-evaluation seam once existed** — useful as precedent for *where* such logic lives — but its condition set is **not** the adopted set and must not be copied (§8).

---

## 15 · Classification of the reconciliation findings

Per brief §9. **A** conform to `EM-VOT-004` · **B** already-adopted rule · **C** architectural defect · **D** historical compatibility · **E** documentation · **F** separate business decision · **G** no change.

| Finding | Class | Note |
|---|---|---|
| Lifecycle derives state from clock + facts | **A** | The derivation stays; it gains a conjunct |
| The clock can produce `VotingActive` | **A** | The core violation |
| Setup completion missing from the derived voting predicate | **B** | `EM-VOT-004` names nomination completed; `EM-VOT-003` names the voter |
| The Chief's action writes a fact the lifecycle does not use | **A** | That fact **becomes** the authority |
| `voting_locked` overloaded | **C** | **Prerequisite** — no authority can be carried by an ambiguous fact |
| `open_voting` overwrites the scheduled window | **C** + **F** | The repair depends on `PBDIGIT-59` (which column *is* the schedule) |
| Clock-derived state makes the Chief's action unavailable | **A** | Dissolves once authorization is the gate |
| Multiple current-state accessors | **E** | Retire after the semantic tests are dispositioned (§16) |
| Historical blocked-state semantics disappeared | **A** | The *distinguishability* is required (note 4); the **name is not restored** (§8) |
| 🆕 `completeNomination()` auto-opens the window | **A** | V-6 |
| 🆕 The cron can start an election with no actor | **A** | V-7 — most severe |
| 🆕 Creation accepts an already-open window | **A** | V-8 |
| 🆕 Candidacy approval can flip the state | **A** | V-9 |
| 🆕 `after:now` contradicts `EM-GOV-004` | **B** | V-11 |
| 🆕 `no pending candidacies` is an unadopted condition | **F** | §8 · §19 |
| 🆕 Conditions read denormalized counters | **C** | §8 |

---

## 16 · Test-semantic disposition

**No test was modified.** Reviewed to establish which semantics they encode.

| Test | Encoded semantics | Under `EM-VOT-004` | Disposition |
|---|---|---|---|
| `DerivedStateTest` — 8 cases asserting `administration` / `nomination` / `voting` / `counting` / `results` | Generation-1 state vocabulary, via the **deprecated accessor** | **Superseded** — vocabulary replaced by `ElectionLifecycleState` | **Retire with recorded rationale.** They exercise a surface that is itself slated for retirement |
| `DerivedStateTest::returns_voting_blocked_*` (2) | *"window open but unfit ⇒ not voting"* | **Business meaning SURVIVES; the state-shaped encoding does not** (note 4, §8) | **Re-express** as eligibility-assessment tests. **Do not make them green as written** — that would adopt the declined vocabulary |
| `DerivedStateTest::returns_counting_blocked_*` (2) | *"window ended but unsealed / no votes ⇒ not counting"* | **Requires clarification** — `EM-VOT-004` is silent on the exit boundary, and note 3 keeps closing unchanged | 🟡 **Governance** — is *"no votes recorded"* a business condition on counting at all? |
| `DerivedStateTest::returns_voting_state_only_during_voting_window` | *"window is necessary for voting"* | **Still valid**, and now strictly necessary-but-not-sufficient | **Retain the semantic**, re-express against the authority |
| `BusinessConditionsTest` (3 failing) | `canEnterVotingPhase()` — nomination ∧ candidates ≥ min ∧ no pending | **Partially valid**: nomination ✅ adopted · candidate ✅ adopted (but wrong input) · **no-pending ❌ not adopted** · **voter ❌ missing** | 🟡 **Blocked on the §19 condition question** — cannot be dispositioned until Governance rules on `no pending` |

> **The standing instruction holds: do not make a test green because it is red.** Two of these (`counting_blocked`, `BusinessConditionsTest`) are **blocked on governance**, not on engineering. Marking them fixed would resolve business questions by test repair — the precise failure mode this programme exists to prevent.

---

## 17 · `EM-OPEN-021` — status under the target architecture

**Governance recorded (note 6) that `EM-VOT-004` does not resolve it. I do not contradict that, and I do not close it.** I report one architectural observation for Governance to weigh:

Under the target predicate, **no path can produce `VotingActive` for a zero-candidate election** — including `forceCloseNomination()` (P9), because that path writes no authorization fact. The question *"what lifecycle state is such an election in?"* therefore **no longer arises**: it is `ReadyForVoting`, ineligible, with the unmet condition named. The **state-selection** half dissolves.

What survives is a **different and arguably better** question: *what does it mean for an election to have completed nomination with zero approved candidates — is that a legitimate election at all?* That is a validity question, not a lifecycle-state question.

**→ Governance may wish to re-scope `EM-OPEN-021` accordingly. Architecture does not close it, does not re-scope it, and does not choose a fallback.**

---

## 18 · ADRs required

| ADR | Subject | Status |
|---|---|---|
| **ADR-A** | **Progression authority: `VotingActive` requires a recorded authorization fact, bound to the window it authorized.** The predicate change, the authority model, the closure argument over all paths, and the §5a binding — including why a bare boolean is excluded by the adopted anti-circumvention clause. | **Ready to draft** |
| **ADR-B** | **Lifecycle state vs eligibility assessment.** Why the three business situations are one state plus an assessment, and why `voting_blocked` is relocated rather than restored or discarded. | **Ready to draft** |
| **ADR-C** | **The temporal port and the meaning of the scheduled instant.** | 🔴 **BLOCKED on `PBDIGIT-59` + `PBDIGIT-67`** — the port may be drafted; the predicate it evaluates may not |

**Proposed constitutional expression, returned to Governance rather than self-authorized** (brief §12): `ElectionConstitution::RULES['open_voting']['preconditions']` currently holds `voting_window_defined`, `timezone_set`, `has_approved_candidates`. `EM-VOT-004` requires the set to also carry **an admitted voter**, **nomination completed**, and **the scheduled start reached**. Since the Manifesto names the Constitution as the expression home, this is the natural site — **but amending running enforcement code requires implementation authority, which the adoption deliberately withheld.** Recorded here as *"Architecture dependency / proposed constitutional expression"* and handed back.

---

## 19 · Items requiring Governance clarification — **sequenced**

**Prioritization set by the Principal Architect at review, 2026-08-15.** Tier 1 blocks implementation; Tier 2 completes the condition set; Tier 3 protects the correction path; Tier 4 is scoping. **Governance's job here is narrow: answer business questions. It is not asked to revisit the architecture.**

### 🔴 Tier 1 — blocks implementation. *"Scheduled start reached"* cannot be evaluated without these.

| # | Question | Why Architecture must not settle it |
|---|---|---|
| **G-1** | **`PBDIGIT-59` — which timestamps are constitutional?** | *"The scheduled start time has been reached"* is a **mandatory condition** of an adopted rule. Two column pairs compete, and the pair the engine reads is **overwritten by the acts themselves** — a rule about the *scheduled* time cannot be evaluated against a column recording when the act *happened* |
| **G-2** | **`PBDIGIT-67` — what does an officer's entered time mean?** | The stored instant is 60–120 min from what the officer chose, DST-variable, live. Evaluating the condition against it would enforce a schedule **nobody set** — a correctly-built authorization mechanism around the wrong clock |

### 🟠 Tier 2 — completes the mandatory-condition set

| # | Question | Why Architecture must not settle it |
|---|---|---|
| **G-3** | **Is *"no candidacy application still pending"* a mandatory condition for starting voting?** | Present in the historical evaluator, **absent from `EM-VOT-004`'s four**. Keeping it invents a constitutional condition; dropping it discards a possible safeguard. **The full start-condition set is not closed until this is answered** |

### 🟡 Tier 3 — prevents schedule correction becoming a hidden authority mechanism

| # | Question | Why Architecture must not settle it |
|---|---|---|
| **G-4** | **The six `EM-GOV-004` boundaries.** | Adopted at principle level only |
| **G-5** | **Does an authorization survive a schedule correction, and if not, how does it end?** §5a excludes *"survives arbitrary correction"* as forbidden by the adopted anti-circumvention clause, and **recommends fail-closed as a revisable default**. What remains is whether superseding should stop live voting **abruptly** or route through a governed re-authorization flow | The adopted rule evaluates conditions *at the moment of the act* and is silent on what happens to the act afterwards |
| **G-5a** | 🆕 **Should a schedule correction be permitted at all while an authorization is live — or must the Chief first *withdraw* the authorization as a distinct audited act?** | **No withdrawal act exists in the adopted rules.** Proposing one is a governance question. The alternative — correction silently superseding a live authorization — is the abrupt-stop behaviour in `G-5` |

### 🟢 Tier 4 — scoping; not implementation blockers unless they become so

| # | Question | Why Architecture must not settle it |
|---|---|---|
| **G-6** | **What is `elections:process-auto-transitions` still authorized to do?** Its administration→nomination grace behaviour is untouched by `EM-VOT-004`, but its authority was never explicitly granted | **Deleting it silently would change election behaviour without a decision** — the same failure mode as the migration that caused this programme |
| **G-7** | **Is *"no votes recorded"* a business condition on entering counting?** *(from `counting_blocked`)* | Needed to disposition two failing tests; the exit boundary is outside `EM-VOT-004` |
| **G-8** | **`EM-OPEN-021` re-scoping** — §17 | Governance's to re-scope, not Architecture's |

> **Note on `G-6`, recorded because the temptation is real:** the cron is the mechanism behind the most serious finding in this report (V-7/P4). It would be easy to treat "delete it" as the fix. **It is not.** The predicate change already removes its ability to reach voting; what remains of the job is a separate, untouched grace behaviour whose authority was never examined. Removing that without a ruling would repeat the exact error the reconciliation documented — **a capability disappearing in a commit that was about something else.**

---

## 20 · Explicitly out of scope

Per brief §14, and confirmed untouched: the wider Election architecture · any state-machine framework · replacing the lifecycle engine · new bounded contexts · aggregate redesign · unrelated migrations · `A-2` · Full Membership · other phase-transition semantics · the six schedule-correction policies · `EM-OPEN-021`'s resolution · the `PBDIGIT-67` render-divergence defect (recorded there, deliberately unraised) · `PBDIGIT-48` field retirement.

**No larger redesign was found to be necessary.** The existing constitutional architecture — Constitution as rule registry, Guard as enforcer, engine as derivation, snapshot as capability read model — **is sound and is retained in full.** The defect was one missing conjunct and one missing fact, not a wrong structure. Stated plainly because the opposite conclusion would have been easy to reach and expensive to act on.

---

## 21 · Implementation boundary for Session 3 — **NOT GRANTED**

To be granted only after this architecture is reviewed and approved.

| | |
|---|---|
| **May change** | `ElectionLifecycleEngineImpl::getState()` steps 4–5 · the authorization fact and its persistence · `applySideEffectsForOpenVoting()` (stop overwriting the window; write the fact) · `completeNomination()` (sever the window side effect) · `ProcessElectionAutoTransitions` (remove the reach into voting, within G-6) · `ElectionClockService` → injectable port · `ElectionLifecycleSnapshot` (additive) · the `open_voting` condition evaluator |
| **Must NOT change** | The Constitution (until §18 is granted) · the Manifesto · the exit predicate (§11) · `EnsureVotingActive` · the UI capability contract · `voting_locked`'s sealing meaning until split is designed · any test (until §16 is dispositioned) · anything in §20 |
| **Invariant to enforce** | `VotingActive ⟹ progressionAuthorized` — and `progressionAuthorized` is writable **only** by the Chief's authorized act |
| **Paths that must be covered** | **All of P1–P10** (§9). A fix that repairs only `open_voting` does not satisfy the rule — the `EM-VOT-002` both-paths lesson applies with a wider both |
| **RED first** | ① clock arrival alone does not produce voting · ② each of P3–P10 cannot produce voting · ③ the cron cannot start an election · ④ Proceed with zero approved candidates is refused, naming the condition · ⑤ same for zero admitted voters · ⑥ same for nomination incomplete · ⑦ Proceed after all conditions met succeeds · ⑧ **automatic closing still occurs with no act** · ⑨ a schedule correction does not by itself start voting · ⑩ the officer may correct and retry successfully · ⑪ 🆕 **an authorization does not survive a schedule correction** — correct the window after authorizing; voting must not resume when the new window arrives · ⑫ 🆕 **a closed election cannot be resurrected** — correct the schedule to a future window after counting has begun; the election must stay in counting |
| **Evidence required** | RED before GREEN, per test · the exact pre-existing failure baseline, unchanged · no test edited to pass · the authorization fact's actor and instant observable in the audit trail |
| **Contracts that must remain unchanged** | `ElectionLifecycleSnapshot`'s existing fields · `ElectionLifecycle` facade's public surface · `allowedActions` vocabulary · `VotingOpened` event shape |

---

## 22 · Verification boundary for Session 1

Independent of Session 3's tests. **Do not verify anything not yet architecturally defined and implemented** — items marked ⏸ are blocked upstream.

| # | Must independently establish |
|---|---|
| 1 | Time alone cannot activate voting — advance the clock across a scheduled start; state must not become voting |
| 2 | The Chief's explicit action is required, and its actor and instant are recorded |
| 3 | The Chief cannot override a mandatory condition — no parameter, role or route permits it |
| 4 | **No alternate path activates voting** — P2 … P10 each exercised independently |
| 5 | 🔴 The background job cannot start an election (P4) |
| 6 | Zero approved candidates ⇒ refused, condition named |
| 7 | Zero admitted voters ⇒ refused, condition named |
| 8 | Nomination incomplete ⇒ refused, condition named |
| 9 | A schedule correction does not itself activate voting |
| 10 | **Automatic closing remains intact** — the window ends with no act and counting follows (§11) |
| 11 | Authority and audit facts survive suspension/resume |
| 12 | 🆕 **An authorization does not survive a schedule correction** — the corrected window must not reactivate voting when it arrives (§5a) |
| 13 | 🆕 **Closure is monotone** — no schedule edit walks an election backwards out of counting (§5a) |
| ⏸ | *"Scheduled time reached"* semantics — **blocked on G-1/G-2** |
| ⏸ | Schedule-correction limits — **blocked on G-4** |

---

## 23 · Architecture acceptance criteria

This architecture is acceptable only if it answers the question the brief set:

> ***"How can the system guarantee that the Chief has the authority to request voting, while neither the clock nor the Chief can bypass the Election's mandatory rules?"***

| Criterion | How this design meets it |
|---|---|
| The Chief **has** the authority | `open_voting` remains chief-only and becomes the **sole** writer of the authorization fact — the authority is now real rather than pre-emptable (V-5 dissolves) |
| The **clock cannot** exercise it | The clock is demoted to a delimiting conjunct; it can make an election *eligible*, never *authorized* |
| The **Chief cannot** bypass the rules | Conditions are evaluated inside the same operation that writes the fact, with no bypass path |
| **No alternate path** bypasses it | Guaranteed **structurally, not vigilantly** — seven of eight offending paths close without individual repair, and the guarantee holds for paths not yet written |
| Schedule correction is not a backdoor | Structural — the window is no longer sufficient |
| Automatic closing survives | By construction — the conjunct was added on the entry side only |
| Nothing was invented | Two STOP conditions reached (§6) and eight questions returned to Governance (§19) rather than answered |

---

**Boundary restated on exit.** No production code, test, Constitution or Manifesto was modified. No business rule was invented, and no adopted rule was reinterpreted. No governance decision was closed. `EM-OPEN-021` remains open. Session 3 is **not granted**; Session 1 has nothing to verify yet. Every reachability claim in §9 is *by inspection* and awaits Session 1's independent measurement. Two ADRs are ready to draft; a third is blocked on `PBDIGIT-59` and `PBDIGIT-67`.
