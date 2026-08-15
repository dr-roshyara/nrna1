# Voting Opportunity, Supersession & Opportunity-Bound Progression — Architecture Adaptation

**Type:** Architecture investigation + design (Session 4) · **Date:** 2026-08-15
**Authority:** `EM-VOC-004` · `EM-VOT-005` *(scope extended)* · `EM-VOC-005` · `EM-GOV-005` — adopted `cc030496`, `e28e6c7b`. Prior: `EM-VOT-004`, `EM-GOV-004` (`3d31df2b`).
**Predecessors:** `2026-08-15-session4-election-progression-semantic-reconciliation.md` · `2026-08-15-session4-election-lifecycle-architecture-adaptation.md`
**Status:** **ARCHITECTURE ADAPTATION: PARTIALLY BLOCKED · IMPLEMENTATION AUTHORIZATION: NOT GRANTED.** No production code, test, fixture, migration, schema, Constitution or Manifesto touched. No business rule invented. No open Governance question decided.

---

## ⚠️ Read first — a naming collision in the live record

**Two different findings are now called `G-1`, both about the start-of-voting boundary, both dated 2026-08-15.**

| Label | Means | Home |
|---|---|---|
| **Finding `G-1`** | *"neither the Constitution nor the Manifesto ever decided whether progression is commanded or derived"* | semantic reconciliation §2; **refined** by the voting-opportunity clarification |
| **`§19 G-1`** | *"which timestamps are constitutional?"* (`PBDIGIT-59`) | architecture adaptation §19 Tier 1; **still open** |

The commission's §7 *"do not invent the answer to G-1 or G-2"* refers to the **second**. **They must not be conflated — one is refined and progressing, the other is an unanswered blocker.** To stop this propagating, the Tier-1 blockers are referred to below as **`T1-SCHEDULE`** (`PBDIGIT-59`) and **`T1-MEANING`** (`PBDIGIT-67`). Renaming in the governance record is Governance's call, not mine; this report simply refuses to add ambiguity.

---

## A · Executive architecture decision

> **The existing architecture cannot represent the adopted rules without structural change — but the missing piece is one concept, not a subsystem, and the system already contains a correct substrate for half of what is now required.**

Three findings drive everything below.

**1. Value-based window binding is now insufficient. This overturns part of my own prior design.**

The previous report bound authorization to the **window's values**. Under `EM-VOC-005` that is no longer sound, and the reason is decisive:

> A governed correction that produces **the same times** still creates a **new** opportunity. Value comparison would say *"same window, decision still valid"* — and would carry the decision forward. **`EM-VOC-005` says it must not.**

So value comparison silently reintroduces a **zero-width tolerance rule** — *"if the corrected schedule equals the previous one, the earlier decision survives"* — which is exactly the class of rule the ruling rejected. Commission §4 forbids reintroducing tolerance *"under another name"*; **an equality test is that name.** Binding must be to **opportunity identity**, not to schedule values.

**2. The mechanism question I deferred has been answered by Governance — in one direction, and it collapsed the option space.**

The prior report listed four candidate mechanisms and declined to choose. `EM-VOC-005` + `EM-GOV-005` now settle it at business level: the previous opportunity is **superseded and permanently recorded**. That **excludes** active invalidation-by-erasure, **excludes** any mechanism that discards the record, and **selects** the identity/versioning family. **Two of my four candidates are eliminated by adopted rule; I am not choosing between the rest on preference.**

**3. `EM-VOC-005`'s trigger condition has no counterpart in the model at all.**

The rule attaches to *"a correction to a **published** voting schedule."* **There is no concept of a published schedule anywhere in the system** — searched and confirmed absent. Election *results* have publication; schedules do not. **Architecture therefore cannot determine when the rule fires.** This is where `EM-OPEN-023` bites, and it is why this adaptation is **partially blocked** (§K).

---

## B · Domain model

**Investigated before proposing** — per the commission's instruction not to assume `VotingOpportunity` must become an aggregate, and the Principal Architect's instruction to first discover what already exists implicitly.

### What already exists

| Adopted concept | Existing counterpart | Verdict |
|---|---|---|
| **Election** | `Election` aggregate | ✅ exists |
| **Schedule** | `voting_starts_at` / `voting_ends_at` (+ the competing `start_date` / `end_date` — `T1-SCHEDULE`) | ⚠️ exists, **mutable and overwritten by the acts themselves** |
| **Published schedule** | — | ❌ **does not exist in any form** |
| **Voting opportunity** | — | ❌ **no identity for the thing being decided about** |
| **Progression request** | `open_voting` via `transitionTo()` | ✅ exists |
| **Progression decision** | — the act is performed, but **no decision fact is recorded** and the derivation ignores it | ⚠️ partial |
| **Election Rules** | `ElectionConstitution::RULES` + `ConstitutionalTransitionGuard` | ✅ **exists and is correct** |
| **Protocol** | `ElectionStateTransition` (**immutable, undeletable**) · `state_audit_log` (**capped**) · `ElectionAuditLog` (**no immutability guard**) | ⚠️ **one correct substrate, two unsound ones** — §H |

### The conceptual relationship

```
Election
   │
   ├── Published Schedule S1 ──────► Voting Opportunity O1
   │                                    │   identity: its own, not the schedule's values
   │                                    ├── Progression Request(s)
   │                                    ├── Eligibility evaluation(s)
   │                                    ├── Progression Decision: authorized | refused(reason)
   │                                    ├── Outcome: started | expired | cancelled | superseded
   │                                    └── Protocol events (append-only)
   │
   │        governed correction  ──►  O1 SUPERSEDED (permanently recorded)
   │
   └── Published Schedule S2 ──────► Voting Opportunity O2
                                        └── its OWN decision. Inherits nothing.
```

**`VotingOpportunity` is a domain concept with identity and a lifecycle — that much the adopted rules establish.** Whether it becomes an entity inside the `Election` aggregate, a separate aggregate, or a projection over the protocol is a representation question addressed in §G, **and it is not settled here** because the answer depends on `EM-OPEN-023` (§K).

---

## C · Architectural invariants

Stated as invariants — properties that must hold — not as mechanisms.

| # | Invariant | Source |
|---|---|---|
| **I-1** | **`Decision(O1)` can never establish progression for `O2`.** No authorization, refusal or eligibility assessment carries across opportunities | `EM-VOT-005` extended |
| **I-2** | **A superseded opportunity can never become `VotingActive`** | `EM-VOC-005` |
| **I-3** | **A schedule correction never itself starts voting** and never extends an existing authorization | `EM-VOC-005`, `EM-GOV-004` |
| **I-4** | **No clock-driven mechanism exercises the Chief's authority** | `EM-VOT-004` |
| **I-5** | **No Chief action overrides a mandatory Election Rule** — conditions are evaluated inside the authority boundary, with no bypass | `EM-VOT-004` |
| **I-6** | **Refusal is not termination.** A refused request leaves the opportunity in whatever condition the Election Rules leave it; it does not by itself expire, cancel or supersede it | `EM-VOC-004` + PA direction |
| **I-7** | **Protocol events are append-only.** A later event never rewrites or erases an earlier one | `EM-GOV-005` |
| **I-8** | 🆕 **Opportunity identity is not derived from schedule values.** Two opportunities with identical times are still two opportunities | derived from `EM-VOC-005` + the no-threshold ruling |
| **I-9** | 🆕 **Every progression decision names the opportunity it belongs to.** A decision that cannot name its opportunity cannot be evaluated against `I-1` and must therefore be treated as no decision | required to make `I-1` checkable |
| **I-10** | 🆕 **Determining the current opportunity must not require a background sweep.** A mechanism that needs a job to notice supersession puts a clock-driven actor back in the authority path | carried forward from the prior report; survives unchanged |

**I-8 is the load-bearing addition** and the one most likely to be lost in implementation, because value comparison *looks* equivalent and is easier to write.

---

## D · Authority-path matrix

The eleven paths from the prior report, re-examined against the new rules. **Q1** reach `VotingActive`? · **Q2** bypass Chief authorization? · **Q3** bypass opportunity identity? · **Q4** reuse an old decision? · **Q5** operate on a superseded opportunity? · **Q6** evaluate the Election Rules? · **Q7** legitimate under `EM-VOT-004`?

| Path | Q1 | Q2 | Q3 | Q4 | Q5 | Q6 | Q7 | Classification |
|---|---|---|---|---|---|---|---|---|
| **P1** `open_voting` command | ✅ | ❌ | ✅ *(no identity exists)* | n/a | ✅ | partial — 3 of the mandatory conditions | ⚠️ | **non-compliant** — right actor, no opportunity binding, incomplete conditions |
| **P2** clock arrival | ✅ | ✅ | ✅ | n/a | ✅ | ❌ | ❌ | **non-compliant** |
| **P3** `completeNomination()` auto-opens window | ✅ | ✅ | ✅ | n/a | ✅ | ❌ | ❌ | **non-compliant** |
| **P4** 🔴 auto-transition cron → P3 | ✅ | ✅ **no actor at all** | ✅ | n/a | ✅ | ❌ | ❌ | **non-compliant — authority violation** |
| **P5** creation with open window | ✅ | ✅ | ✅ | n/a | ✅ | ❌ | ❌ | **non-compliant** |
| **P6** candidacy approval completes the predicate | ✅ | ✅ | ✅ | n/a | ✅ | ❌ | ❌ | **non-compliant** |
| **P7** schedule edit | ✅ *(deferred)* | ✅ | ✅ **— and now also creates an unrecorded new opportunity** | n/a | ✅ | ❌ | ❌ | **non-compliant — worsened by `EM-VOC-005`** |
| **P8** `resume` from suspension | ✅ | ✅ | ✅ | n/a | ✅ | ❌ | ❌ | **non-compliant** |
| **P9** `forceCloseNomination()` | ❌ | — | — | — | — | ❌ | — | **compliant on this boundary**; remains the `EM-OPEN-021` route |
| **P10** factories / seeders / demo | ✅ | ✅ | ✅ | n/a | ✅ | ❌ | n/a | non-production; **demo behaviour blocked on `T1-SCHEDULE`** |
| **P11** `election:start-voting` console | ❌ — operates on config, not the aggregate | — | — | — | — | — | — | **legacy remnant** |

**Q4 reads `n/a` throughout for a reason that is itself the finding: no decision is recorded today, so there is nothing to reuse.** Decision reuse becomes *possible* only once decisions are recorded — which is precisely why `I-1` and `I-9` must be built in from the start rather than added later. **The absence of the hazard today is an artefact of the absence of the concept, not evidence of safety.**

**Q5 reads `✅` throughout for the same reason:** with no opportunity identity, every path operates on an opportunity that can neither be identified nor known to be superseded.

**Documented, not fixed**, per commission §6.

---

## E · Opportunity lifecycle model

**Two axes, deliberately kept apart** — this is the `I-6` distinction the Principal Architect required be protected.

```
AXIS 1 — the opportunity's condition            AXIS 2 — progression decisions
                                                 (events, not states)
   created
      │                                             request ──► evaluate
   published?  ⛔ EM-OPEN-023                                     │
      │                                                ┌─────────┴─────────┐
   eligible for consideration                       permitted           refused
      │  (clock reached the schedule)                  │              (reason recorded)
      │                                                │                   │
      ├──────────────────────────────────────────────► started            │
      │                                                                    │
      ├── superseded   (governed schedule correction — EM-VOC-005) ◄───────┘
      ├── expired unused        ⛔ timing NOT decided                 the opportunity
      └── explicitly cancelled  ⛔ authority NOT decided              REMAINS as it was
```

**A refusal appears on Axis 2 only. It moves nothing on Axis 1.** That is `I-6` expressed structurally: **refusal is a protocol event, not an opportunity outcome.** The architectural hazard the commission warned about — *"Chief presses Proceed → condition fails → system marks the opportunity cancelled"* — is prevented by keeping refusal off the axis that carries terminal outcomes, rather than by a rule saying "don't do that."

**No transition rules are invented for `expired` or `cancelled`.** Their timing and authority are undecided (commission §10), so this model names them and stops.

### ⚠️ A textual tension in `EM-VOC-004`, surfaced not resolved

`EM-VOC-004` says an opportunity *"may end in one of several ways"* and lists five, one of which is *"temporarily unable to proceed **while the opportunity is still valid**."* **By its own words that item does not end anything** — it is a condition, not a terminal outcome.

**Architecture's reading: four terminal outcomes plus one non-terminal condition** — which is exactly the two-axis split above, and is consistent with the PA's explicit `refusal ≠ termination` direction. **Recorded for confirmation rather than assumed**; the risk of the alternative reading is real, because reading all five as terminal would make every refusal an ending, which `I-6` forbids.

---

## F · Schedule-correction model

```
Published Schedule S1  ──►  Opportunity O1  ──►  decisions, evaluations, protocol events
                                  │
                     governed correction (EM-GOV-004 authority; limits still open)
                                  │
                                  ▼
                          O1 SUPERSEDED
                    permanently recorded, never erased
                                  │
                                  ▼
Published Schedule S2  ──►  Opportunity O2  ──►  its own decision, from zero
```

| Carries forward to `O2` | Does **not** carry forward |
|---|---|
| The election and everything about it that is not a progression decision — posts, voters, candidacies, committee, entitlements | **Authorization** · **Refusal** · **Eligibility assessment** · **any other progression decision** (`EM-VOT-005` extended) |
| The protocol — `O1`'s full history remains readable | `O1`'s **outcome**; `O2` starts with none |

**No tolerance, threshold, grace period, no-op shortcut or insignificant-change logic is proposed, and `I-8` exists specifically to prevent one being reintroduced as an equality test.**

**Not decided here** (commission §10): whether correction is permitted while voting is live or after votes exist; forward, backward or extension moves; credential consequences. Those are the six `EM-GOV-004` boundaries, **explicitly unchanged by the `EM-OPEN-022` ruling**, which settled identity and not extent.

---

## G · Authorization model — reconciling window binding with opportunity identity

**The prior design's invariant survives; its proposed realization does not.**

| | Prior report | Under `EM-VOC-005` |
|---|---|---|
| **Invariant** | *"a previous authorization must never silently authorize a different voting window"* | ✅ **survives, strengthened**: *…never authorize a different **opportunity*** |
| **Realization** | authorization records the window; compare authorized window to operative window | ❌ **insufficient** — equality of values is not identity of opportunity (§A.1) |

**Required form:**

> **An authorization is valid only for the opportunity it names.** Validity is established by **identity**, never by comparing schedule values.

**Why this is stronger, not merely different:** value comparison answers *"is the schedule still the same?"*; identity answers *"is this the same opportunity?"* Those diverge in exactly the case `EM-VOC-005` legislates — a correction that lands on the same times. Identity also makes `I-1` **checkable for refusals and eligibility assessments**, which have no window values of their own to compare, and which the `EM-OPEN-022` ruling brought into scope.

**Is `VotingOpportunity` therefore a new aggregate?** **Not established, and deliberately not asserted.** Three representations remain viable — an entity within the `Election` aggregate; a value-object identity plus protocol events; a separate aggregate. The choice depends on whether opportunities exist before publication (`EM-OPEN-023`) and on whether they have a lifecycle independent of the election. **Both inputs are open, so the choice is deferred rather than guessed.** What *is* established: **the identity must exist and must be nameable by every progression decision** (`I-9`).

---

## H · Protocol model

**What must remain reconstructable** — the commission's framing, and the right one:

```
O1 created · schedule published · progression requested · conditions evaluated
· progression refused + reason · schedule corrected · O1 superseded
· O2 created · new progression requested · conditions evaluated independently
· voting started OR refused again
```

### Gap analysis against `EM-GOV-005` — three substrates, one sound

| Substrate | Preserves history? | Records what `EM-GOV-005` requires? |
|---|---|---|
| **`ElectionStateTransition`** | ✅ **immutable and undeletable, enforced at the model** — `updating` and `deleting` both throw | ❌ **records only successful constitutional transitions.** A refusal throws in the guard **before** any record is created |
| **`state_audit_log`** (JSON on the election) | ❌ **capped at 200 entries, silently discarding the oldest** | records `completeNomination` / `forceCloseNomination`, which bypass `transitionTo()` entirely |
| **`ElectionAuditLog`** | ❌ **no immutability guard** — unlike `ElectionStateTransition`, nothing prevents update or delete | dual-written alongside the capped log |

> **The finding, stated precisely: the one substrate that satisfies *"the original history is preserved"* is the one that does not record the events `EM-GOV-005` newly requires — and the substrates that record them do not preserve them.**
>
> **Refusals are the sharpest case: they are reported to metrics and raised as an exception, and are recorded nowhere durable.** `EM-GOV-005` requires *"each refusal and its reason."* Today a refused progression leaves no trace an auditor could read.

**Architecture states what must be reconstructable and what properties the record must have — append-only, attributable, complete for the listed events. It does not prescribe storage**, since `ElectionStateTransition` already establishes an immutable-record pattern in this codebase that the eventual mechanism should extend rather than duplicate (`ES-005.4`).

---

## I · Existing architecture impact — analysis only

| Component | Impact |
|---|---|
| `Election` aggregate | Gains opportunity identity and the progression-decision fact |
| `ElectionLifecycleEngineImpl::getState()` | Derivation keyed to the opportunity's decision, not to window values |
| `ElectionConstitution::RULES['open_voting']` | Condition set completed (voter · nomination · scheduled-start); **amendment proposed to Governance, not self-authorized** |
| `ConstitutionalTransitionGuard` | ✅ **correct as designed** — gains conditions, keeps its shape |
| `Election::applySideEffectsForOpenVoting()` | Must stop overwriting the schedule; must record the decision |
| `Election::completeNomination()` | Window side effect severed |
| `ProcessElectionAutoTransitions` | Loses the reach into voting; **remaining scope is `G-6`, undecided — do not delete** |
| `updateVotingDates` / `updateTimeline` | Become opportunity-superseding acts; `after:now` conflicts with `EM-GOV-004` |
| `ElectionStateTransition` | **Extend as the protocol substrate** — the immutability pattern is already right |
| `state_audit_log` · `ElectionAuditLog` | Retention cap and missing immutability guard are `EM-GOV-005` gaps |
| `ElectionLifecycleSnapshot` | Additive: eligibility assessment + current opportunity |
| `EnsureVotingActive`, controllers, `Management.vue` | **No change** — pure consumers |
| Tests | 15 failing legacy tests still awaiting disposition; **not touched** |

---

## J · ADR impact

| ADR | Disposition |
|---|---|
| **ADR-A** (progression authority) | **Amend before drafting** — its window-binding realization is superseded by opportunity identity (§G). The invariant stands |
| **ADR-B** (state vs eligibility) | **Unchanged and reinforced** — the two-axis model in §E is the same separation applied to the opportunity |
| **ADR-C** (temporal port) | **Still blocked** on `T1-SCHEDULE` / `T1-MEANING` |
| **ADR-D** 🆕 (voting opportunity identity & supersession) | **Cannot be written yet** — its scope depends on `EM-OPEN-023` |

**No implementation ADR is written while the required business decisions are open**, per commission §13.J.

---

## K · Open questions and blockers

### ⛔ STOP — `EM-OPEN-023` blocks completion

Per commission §14, stated in the required form:

1. **The question.** Does `EM-VOC-005` attach only to a *published* schedule — and what makes a schedule published?
2. **Why Architecture cannot decide it.** **There is no concept of a published schedule in the system** (searched; absent). Deciding what publication means would create a business rule, and choosing a technical default would settle `EM-OPEN-023` by implementation — both forbidden.
3. **Competing interpretations.** **(X)** publication is a real business act, and only corrections after it create opportunities · **(Y)** every governed schedule change is a correction, published or not.
4. **Architectural consequence of each.** Under **X**, a publication act, its authority and its protocol event must all exist — none do today, and setup-time editing stays free. Under **Y**, **every edit during ordinary setup mints and supersedes an opportunity**, filling the protocol with supersession events generated by routine work, and the opportunity concept starts at election creation rather than at scheduling.
5. **The Governance decision required.** Whether publication is a business act; if so, what constitutes it; if not, confirmation that ordinary setup edits create and supersede opportunities.

> **Governance's low-risk assessment is correct on the axis it examined — carry-forward — and does not extend to the axis that actually bites.** *"No progression decision can exist for an unpublished schedule, so nothing could carry forward"* is sound. But `EM-VOC-005` also mandates **supersession recording**, and under reading **Y** that consequence is **not** low-impact: it is protocol volume and identity proliferation driven by routine setup editing. **Recorded so the ruling is made on both consequences, not one.**

### Other blockers and open items

| Item | Status |
|---|---|
| **`T1-SCHEDULE`** (`PBDIGIT-59`) | 🔴 open — *"scheduled start reached"* unevaluable; **decision request issued** |
| **`T1-MEANING`** (`PBDIGIT-67`) | 🔴 open — stored instant 60–120 min from the officer's intent |
| **`EM-VOC-004` textual tension** | 🟠 five items listed as "outcomes", one non-terminal by its own words (§E) |
| **`G-3`** pending candidacy | 🟠 open — completes the mandatory-condition set |
| **`G-4` / `G-5` / `G-5a`** | 🟡 correction extent, live-authorization correction, withdrawal — **explicitly unchanged by the `EM-OPEN-022` ruling** |
| **`G-6`** cron authority | 🟡 open — **do not delete the job to "fix" P4** |
| **`G-7`** counting conditions · **`G-8`** `EM-OPEN-021` | 🟢 open |
| **Expiry / cancellation semantics** | 🟡 **named in `EM-VOC-004`, timing and authority undecided** — not invented here |

### `EM-OPEN-021` — not declared resolved

Per commission §11. The candidate requirement means an opportunity with zero approved candidates **cannot become `VotingActive`**. The business question — *what condition represents an open voting opportunity with no approved candidates* — **remains unresolved.** The adopted vocabulary now offers a place for the answer to live, which is a change in **available vocabulary, not in resolution**. Where today's lifecycle falls through, that is **technical behaviour, not business authority**, and this report labels it as such.

---

## L · Implementation prerequisites for Session 3

**Before an implementation grant is possible:**

| # | Prerequisite |
|---|---|
| 1 | 🔴 **`EM-OPEN-023` ruled** — otherwise the trigger for creating and superseding opportunities is undefined |
| 2 | 🔴 **`T1-SCHEDULE` + `T1-MEANING` ruled** — otherwise *"scheduled start reached"* cannot be evaluated |
| 3 | 🟠 **`G-3` ruled** — otherwise the mandatory-condition set is not closed |
| 4 | **ADR-A amended, ADR-B and ADR-D drafted and approved** |
| 5 | **`EM-VOC-004`'s five-outcome reading confirmed** (§E) |
| 6 | **The `open_voting` constitutional amendment granted** — Architecture proposes; Governance adopts |
| 7 | **Legacy test disposition ruled** — 15 failing tests, unchanged |

**Then, and only then:** RED-first across every path in §D, with the prior report's boundary list extended by — an authorization must not validate a different opportunity; a superseded opportunity must never become `VotingActive`; **two opportunities with identical schedules must still require separate decisions** (the `I-8` case, which a value-comparison implementation would silently pass while being wrong); a refusal must not terminate an opportunity; a refusal and its reason must be durably recorded.

---

## Final handoff

```
ARCHITECTURE ADAPTATION:      PARTIALLY BLOCKED
                              Model, invariants and path audit complete.
                              Representation and trigger blocked on EM-OPEN-023.

IMPLEMENTATION AUTHORIZATION: NOT GRANTED

NEXT ACTOR:                   GOVERNANCE
                              EM-OPEN-023 (blocking) · T1-SCHEDULE · T1-MEANING · G-3
                              · EM-VOC-004 five-outcome confirmation
```

**Boundary restated on exit.** No production code, test, fixture, migration, schema, Constitution or Manifesto was modified. No lifecycle state was created as a workaround. `voting_blocked` was not revived. No tolerance was introduced — and `I-8` exists to prevent one entering as an equality test. No business semantics were inferred from existing code; where current behaviour was described it is labelled technical behaviour, not authority. The cron was not deleted. `EM-OPEN-021` was not resolved. No failing test was made to pass. Every reachability claim in §D is *by inspection* and awaits Session 1's independent measurement.
