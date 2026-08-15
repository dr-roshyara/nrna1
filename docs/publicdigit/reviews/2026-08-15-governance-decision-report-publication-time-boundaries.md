# Governance Decision Report — Publication, Time Semantics & Remaining Business Boundaries

**Type:** Governance Decision Report (Session 2) · **Date:** 2026-08-15 · **Commission:** PO/ARB, *"Publication, Time Semantics & Remaining Business Boundaries"*
**⛔ Governance only.** No production code, test, fixture, migration, schema, Constitution, ADR or implementation artifact was read *as authority* or modified. **Session 3 is not authorized. Architecture was not asked to solve a business question.**

**Governing principle held throughout:** *the Chief Election Officer decides when to request progression; the Election Rules decide whether progression is permitted.* **Authority, not sovereignty.**

---

## 0 · Authority verification (commission §1) — performed before any decision

Read from the **committed Manifesto**, not from reports:

| Rule | Verified adopted | Basis on record |
|---|---|---|
| `EM-VOT-004` | ✅ ADOPTED | PO/ARB authorization, 2026-08-15 — time makes progression *possible*, never automatic |
| `EM-VOC-004` | ✅ ADOPTED | PO clarification, 2026-08-15 — a published schedule defines a voting opportunity |
| `EM-VOT-005` | ✅ ADOPTED *(scope extended 2026-08-15)* | authorization — and now every progression decision — is opportunity-bound |
| `EM-GOV-004` | ✅ ADOPTED **(principle only)** | schedule-correction principle; **six boundaries expressly NOT adopted** |
| `EM-GOV-005` | ✅ ADOPTED | material events recorded; original history preserved |
| `EM-VOC-005` | ✅ ADOPTED | `EM-OPEN-022` = Reading A, 2026-08-15 |

**Separation held, and it matters here:** what follows distinguishes **adopted rule** · **derived consequence of an adopted rule** *(Governance may state these; they add no policy)* · **open decision** · **Architecture observation** · **technical behaviour** · **recommendation**. **Nothing in the current implementation is treated as evidence of intended business meaning.** Where the implementation is cited it is cited as *evidence that a concept does or does not exist*, never as authority.

---

## 1 · `EM-OPEN-023` — What constitutes publication of a voting schedule?

**Question.** When does a voting schedule become a *published* schedule — the thing `EM-VOC-005` attaches to?

**Business context.** `EM-VOC-005` makes a correction to a **published** schedule create a new voting opportunity, with supersession and a fresh progression decision. If "published" has no business meaning, the rule has no determinate trigger: either every setup edit becomes a correction, or none does. Both are wrong, and neither was decided.

**Evidence.**
1. The adopted wording of `EM-VOC-004` and `EM-VOC-005` uses *"published"* consistently — **this is deliberate PO wording repeated across two rules**, not an accident of drafting.
2. **No adopted rule anywhere defines publication of a schedule.** The Manifesto contains no such definition; the word appears only inside those two rules.
3. **The domain already has a governed publication concept — for results, not schedules.** A PO domain ruling on record (`PBDIGIT-60`, 2026-08-06) holds that **publication is an immutable constitutional fact, and visibility is a separate toggleable control** (*hide/show, never "unpublish"*). *(Cited as an existing PO ruling — a precedent available for extension by analogy, not an authority that already answers this question.)*
4. **Architecture observation, registered as such:** the running system has no schedule-publication concept — only a *window defined* notion. **This is evidence about the current system, not about intended business meaning**, and it is explicitly not treated as an answer.

**Options.**

- **Option A — publication is a distinct business act.** A schedule is prepared, then an authorized act publishes it. Requires Governance to define **who** may publish · **what** publication means to voters · **whether** publication creates the voting opportunity · **whether** a published schedule is thereafter immutable except by governed correction · **what** protocol event exists · **whether** an unpublished schedule can have an opportunity at all.
- **Option B — publication occurs at a defined business milestone.** Publication is not a separate act but the consequence of reaching a named milestone, which must then be stated precisely.
- **Option C — there is no separate publication act.** Then Governance must say what event makes a schedule **authoritative** and therefore subject to `EM-VOC-005`.

**Decision.**

> ## ⛔ **STOP — Governance decision required from PO/ARB.**
>
> **This is new business policy. No adopted rule determines it, and no derivation from adopted rules produces it.** Governance will not manufacture the answer, and — per the PO's own closing note — will not steer it toward *"publication creates the opportunity"*, plausible though that model is.

**Business rationale for stopping.** Each option assigns a *different actor* a *different power at a different moment*. That is precisely the class of question the Chief-requests/Rules-evaluate principle exists to keep out of Architecture's hands. Choosing here would be Governance legislating.

**Consequences — constraints that bind whichever option is chosen** *(derived from already-adopted rules; these add no policy and pre-empt no option)*:
- **C-1** — the chosen moment must be **recordable as a material event** (`EM-GOV-005`). An answer that leaves publication unobservable makes supersession unreconstructable.
- **C-2** — the chosen moment must be **determinate and non-retroactive**: it must be possible to say, at any time, whether a schedule is published, without knowing what happens later.
- **C-3** — the answer must **not create a second policy surface** of the kind the `EM-OPEN-022` ruling expressly rejected: no significance threshold, no "this edit doesn't really count".

**Explicit non-decisions.** Publication's effect on voters, communication obligations, immutability of a published schedule, and who may publish — **all remain undecided and are components of this same PO decision.**

**Traceability.** `EM-VOC-004` · `EM-VOC-005` · `EM-OPEN-023` (opened 2026-08-15) · `PBDIGIT-60` publication ruling *(precedent, not authority)*.

---

## 2 · When does a VotingOpportunity come into existence?

**Question.** At what business moment does a voting opportunity begin — and what happens to schedule edits *before* that moment?

**Decision — split, deliberately.**

> ### Part (a) — the moment itself: ⛔ **BLOCKED on §1, and logically inseparable from it.**
>
> Per commission §9, Governance **stops and raises** rather than inventing: **the moment an opportunity begins cannot be decided independently of what publication means.** They are one decision, not two, and the PO should answer them together.

> ### Part (b) — edits before the opportunity exists: ✅ **RESOLVED BY DERIVATION.**
>
> **A change to a schedule that is not yet published is not a correction within the meaning of `EM-VOC-005`, creates no voting opportunity, and produces no supersession.**

**Business rationale for (b).** This is **not a new rule**. `EM-VOC-005` attaches, by its own adopted words, to *a published voting schedule*. Whatever the PO decides "published" means, **everything strictly before that moment is outside the rule's reach.** Governance states the consequence so that it cannot be inferred the other way round.

**Consequences.** The danger Architecture identified — *routine preparation generating repeated opportunities and supersession records* — **is answered as a matter of business rule: ordinary setup editing is preparation, not correction.** Governance notes plainly that this danger was a reason to state the boundary, **not** a reason to prefer any particular answer to §1; an implementation-convenience argument must never select a business meaning.

**Explicit non-decisions.** Whether preparation is itself recorded in the protocol · whether an unpublished schedule can exist indefinitely · whether more than one opportunity can be live at once.

**Traceability.** `EM-VOC-005` (adopted text) · `EM-VOC-004` · Architecture's repeated-supersession observation *(registered as an observation)*.

---

## 3 · `T1-SCHEDULE` — Which time is constitutionally authoritative?

**Question.** When the Election Rules say *"the scheduled voting start time has been reached"*, which business time is authoritative?

**Business context.** `EM-VOT-004` makes reaching the scheduled start the trigger for **eligibility for the officer's consideration**. If "the scheduled start" is ambiguous between a planned time and something the system later wrote, the rule's trigger is ambiguous — and a *later* fact could retroactively change whether an *earlier* refusal was correct.

**Decision — ✅ RESOLVED BY DERIVATION, with the field selection expressly refused.**

> **(i) The authoritative time for progression is the published schedule of the voting opportunity currently under consideration** — its published voting start and published voting end. **Nothing else is the schedule.**
>
> **(ii) The time at which voting actually started or ended is a separate business fact.** It records what happened; it never states what was scheduled.
>
> **(iii) An actual event must never overwrite a published schedule.** A schedule that has been overwritten by the event it predicted cannot afterwards testify to what was promised.
>
> **(iv) A superseded opportunity keeps its own published schedule permanently.** It is not re-pointed at the corrected times, and it is not deleted.
>
> **(v) When a correction has occurred, the authoritative schedule for progression is the one belonging to the new opportunity** — because, under `EM-VOC-005`, the corrected schedule *is* a different opportunity, and under `EM-VOT-005` nothing from the previous one carries into it.

**Business rationale.** Every clause above is a consequence of rules already adopted — `EM-GOV-005` (history preserved, never rewritten), `EM-VOC-004` (supersession is a distinguishable outcome that must remain legible), `EM-VOC-005` (the previous opportunity remains permanently recorded), `EM-VOT-005` (nothing carries over). **Governance is stating what the adopted rules already require, not adding policy.** The published/actual distinction offered in the commission is **confirmed as reflecting the intended business meaning** — and confirmed *because the protocol rules force it*, not because it is a familiar shape.

**Consequences.** A refusal recorded against an opportunity remains checkable forever against the schedule that was in force when it was made. **Retroactive justification becomes impossible**, which is the property the protocol philosophy exists to protect.

**Explicit non-decisions.** ⛔ **Governance names no database field, column, timestamp, or attribute** — the commission forbids it and §9 excludes technical representation. **Whether the domain must carry an "actual start/end" at all, and how it is represented, is Architecture's question, constrained by (ii) and (iii).** Also undecided: how far a correction may move a schedule (`EM-GOV-004` boundaries, open).

**Traceability.** `EM-VOT-004` · `EM-VOC-004` · `EM-VOC-005` · `EM-VOT-005` · `EM-GOV-005`.

---

## 4 · `T1-MEANING` — What does an officer-entered time mean?

**Question.** The Chief enters *"10:00"*. What kind of value is that, which timezone governs it, who establishes that timezone, and what happens at daylight-saving transitions?

**Evidence.**
1. **No adopted rule defines the time semantics of an election schedule.** None.
2. **`EM-OPEN-018` is open and expressly NOT authorized** — it carries a narrow PO steer (*device timezone, not residence*) with the fallback undecided. **Critically, `EM-OPEN-018` is about how times are DISPLAYED.** It is **not** authority over what a schedule *means* for progression.
3. **Architecture/implementation observation, registered as such:** a timezone-set precondition exists on election actions in the running system, which is *evidence that an election-scoped timezone concept exists in the code*. **It is not evidence that the business intends the election's timezone to govern**, and it is not treated as such.

**Decision — split.**

> ### (A)–(D) — kind of value · governing timezone · who sets it and when · daylight-saving transitions:
> ## ⛔ **STOP — Governance decision required from PO/ARB.**
>
> **These are new business policy with legal consequences for when voting may begin.** No adopted rule determines them, and no derivation produces them. Governance refuses to select a default and refuses to let the display steer of `EM-OPEN-018` become schedule semantics by proximity.

> ### (E) — officer location: ✅ **RESOLVED AS A BINDING BOUNDARY.**
>
> **The officer's device or location timezone is NOT adopted as a business rule for election schedules.** It must not be used, inferred, or defaulted to by Architecture or Implementation for the meaning of a scheduled time. `EM-OPEN-018`'s device-timezone steer concerns **display only** and, being unauthorized, extends to nothing else.

**Business rationale.** A scheduled voting start is a public commitment to an electorate. Making its meaning depend on *who happens to be operating the interface* would make the same election start at different instants for different officers — and would let a schedule's meaning change without any recorded event, which `EM-GOV-005` forbids in substance.

**Consequences.** Until (A)–(D) are ruled, **no implementation may fix the meaning of an entered time**, and **daylight-saving edge cases must not be resolved as technical defaults** — the commission is explicit that they affect whether voting may legally begin, which makes them business decisions.

**Governance flag for the PO's convenience, not a recommendation:** (A)–(D) are **one coherent decision**, not four; answering the governing timezone without answering who sets it and whether it may change after publication would leave the rule incomplete. **Note the dependency: whether a timezone may change *after publication* is entangled with §1.**

**Explicit non-decisions.** Display formatting · voter-facing localisation · storage · conversion · the `EM-OPEN-018` disposition itself.

**Traceability.** `EM-VOT-004` · `EM-OPEN-018` *(open, unauthorized, display-scoped)* · `EM-GOV-005`.

---

## 5 · `G-3` — Pending candidacy and the meaning of "nomination completed"

**Question.** What must be true of candidacies when the Chief requests progression, and what does *"nomination completed"* mean for opening a voting opportunity?

**Decision — split.**

> ### (a) The candidate and voter condition at the moment of the request: ✅ **ALREADY ADOPTED — restated, not re-decided.**
>
> `EM-VOT-002`: **at least one approved candidate** before voting may be opened. `EM-VOT-003`: an election may enter voting **only with at least one approved candidate and at least one admitted voter.** `EM-VOT-004` fixes **when** these are evaluated: **at the moment the Chief requests progression**, on **every** path into voting.

> ### (b) What "nomination completed" MEANS, and whether an undecided candidacy bars progression:
> ## ⛔ **STOP — Governance decision required from PO/ARB.**
>
> The three situations the commission names are **not** equally settled:
> - **nomination not completed** — **undecided**: no adopted rule says whether completion of nomination is itself a precondition for opening voting, or whether the candidate/voter conditions alone suffice;
> - **completed, zero approved candidates** — **`EM-OPEN-021`, preserved untouched**;
> - **completed, sufficient approved candidates** — permitted **subject to** every other mandatory condition; nothing here weakens `EM-VOT-004`.
>
> Governance additionally records the question the commission implies but does not state: **does a candidacy that is neither approved nor rejected block progression, or is it simply not counted?** Both readings satisfy `EM-VOT-002` on its face. **Not decided.**

**Business rationale.** `EM-VOT-002`/`003` are floor conditions — *at least one approved*. They are silent about **undecided** candidacies, and silence is not permission. Reading "at least one approved" as "and no pending ones" would add a condition no ruling contains; reading it as "pending are irrelevant" would let an election open while a candidate's own status is unresolved. **That is a business choice with fairness consequences for the candidate, and it belongs to the PO.**

**Consequences.** ⛔ **`EM-OPEN-021` is NOT resolved by implication** and is untouched by this report. Its binding constraints stand: the technical fall-through state is not a business rule, and Session 3 must not resolve it implicitly.

**Explicit non-decisions.** Nomination-phase closure authority · whether nomination can reopen · candidate approval/rejection authority · effect of a correction on candidacies.

**Traceability.** `EM-VOT-002` · `EM-VOT-003` · `EM-VOT-004` · `EM-OPEN-021` *(preserved)*.

---

## 6 · `EM-VOC-004` outcome semantics — confirmation

**Question.** Is *"temporarily unable to proceed while still valid"* a **non-terminal condition**, while started / expired / cancelled / superseded are **lifecycle outcomes**?

**Decision — ✅ CONFIRMED, with a wording defect recorded.**

> **(i) Yes.** *Temporarily unable to proceed while the opportunity is still valid* is a **condition of an opportunity that has not ended**. **Started · expired unused · explicitly cancelled · superseded** are the ways an opportunity **ends**.
>
> **(ii) A refusal is not a termination.** A refused request to proceed records a decision; it does not consume, end, or invalidate the opportunity. The Chief may request again while the opportunity remains valid, and the Rules evaluate again.
>
> **(iii) Two different histories exist and must not be merged:** the **opportunity's lifecycle** (created → valid → one ending) and the **progression-decision history** (request → evaluate → permitted or refused-with-reason), which may contain **many** entries for one opportunity. `EM-GOV-005` requires both to be recorded.

**Business rationale.** The adopted text itself says *"while the opportunity is still valid"* — the qualification is in the rule, and it is decisive. The commission's own §11 states the same principle (*refusal ≠ termination*). **Merging the two histories would destroy exactly the information `EM-VOC-004` was adopted to preserve.**

**Wording defect recorded — one-line PO ratification requested.** `EM-VOC-004` introduces all five items with *"may end in one of several ways"*, while item two is expressly **not** an ending. **The adopted meaning is confirmed above; the sentence understates it.** Governance **has not rewritten the adopted text.** Proposed correction, preserving meaning: *"A voting opportunity may end in one of several ways — voting started · expired unused · explicitly cancelled · superseded by a later governed schedule — and, while it has not ended, it may be temporarily unable to proceed. These outcomes and this condition must remain distinguishable."*

**Explicit non-decisions.** How many refusals are tolerable · whether repeated refusal triggers anything · representation of either history · whether "created" and "valid" are distinct business moments *(entangled with §1)*.

**Traceability.** `EM-VOC-004` · `EM-VOT-004` · `EM-GOV-005` · commission §11.

---

## 7 · The `G-1` naming collision — ✅ RESOLVED (Governance bookkeeping)

**No identifier is renamed.** Each label stands in its original record; the following reference forms are adopted for future use. **The commission named two collisions; there are three.**

| Original label & home | What it is | Status | Reference form from now |
|---|---|---|---|
| `G-1` — Election progression review | **commanded vs derived progression** — the Constitution defines commands, the Manifesto defines preconditions, neither decided whether progression is commanded or derived | **Resolved for the start of voting** by `EM-VOT-004`, refined by `EM-VOC-004`/`EM-VOT-005`. **Survives** for entering nomination, early closure, counting, results | **`G-PROG-1`** |
| `G-1` — `PBDIGIT-59` | **timestamp authority** — which recorded time is authoritative | **Answered in business terms by §3 above**; representation remains Architecture's | **`G-TIME-1`** |
| `G-1` — `KOS-AI-ORCH-001` amendment | **platform** amendment: completion is recorded by Governance; Verification reports, it does not close | Accepted; unrelated to Election | **`G-ORCH-A1`** |

**Rationale.** Three unrelated decisions sharing one label is a traceability hazard, and renaming committed records would breach the history-preservation discipline. **Aliases preserve both.**

---

## 8 · New questions raised by this commission

| # | Question | Why it exists |
|---|---|---|
| **N-1** | May the governing timezone change **after** publication? | §1 × §4 intersect; neither alone answers it |
| **N-2** | Does an **undecided** candidacy bar progression, or is it simply not counted? | `EM-VOT-002`'s floor condition is silent, and silence is not permission |
| **N-3** | Is completion of nomination itself a precondition for opening voting, independent of the candidate/voter conditions? | assumed by the commission's three-way split; never adopted |
| **N-4** | Are "created" and "valid" distinct business moments for an opportunity? | surfaced by §6(iii); entangled with §1 |
| **N-5** | Is preparation (pre-publication) itself recorded in the protocol? | `EM-GOV-005` covers *material events*; whether preparation is one is undecided |

**None of these is decided here.** N-1 and N-4 are **entangled with `EM-OPEN-023`** and should travel with it.

---

## 9 · Questions this commission did NOT decide (§9, honoured in full)

Detailed schedule-correction authority · correction after votes cast · forward/backward movement · extensions · credential consequences · cancellation authority · exact expiry timing · cron scope (`G-6`) · counting semantics (`G-7`) · technical representation of a voting opportunity · aggregate boundaries · database/schema design · implementation · test modification. **`EM-GOV-004`'s six boundaries remain open in full. `EM-OPEN-021` remains open and untouched.**

**One dependency was discovered and raised rather than answered** (§2a): *when an opportunity begins* is logically inseparable from *what publication means*.

---

## 10 · Handoff

```
GOVERNANCE STATUS

EM-OPEN-023        BLOCKED — PO/ARB decision required (publication is new business policy;
                   Governance declines to steer it). Three derived constraints bind any answer.
Opportunity start  (a) BLOCKED — inseparable from EM-OPEN-023, raised not invented
                   (b) RESOLVED by derivation — a pre-publication edit is NOT a correction,
                       creates no opportunity, produces no supersession
T1-SCHEDULE        RESOLVED by derivation — the published schedule of the opportunity under
                   consideration is authoritative; actual start/end are separate facts; an
                   actual event never overwrites a published schedule; a superseded
                   opportunity keeps its own schedule permanently. No field named.
T1-MEANING         (A)-(D) BLOCKED — PO/ARB decision required, and they are ONE decision
                   (E) RESOLVED — officer device/location timezone is NOT a business rule;
                       EM-OPEN-018's steer is display-scoped and unauthorized
G-3                (a) ALREADY ADOPTED — EM-VOT-002/003 evaluated at the moment of request
                   (b) BLOCKED — "nomination completed" undefined; pending candidacy undecided
                       EM-OPEN-021 preserved, NOT resolved by implication
EM-VOC-004         CONFIRMED — refusal is not termination; two histories, never merged
                   wording defect recorded; one-line PO ratification requested
G-1 naming         RESOLVED — three collisions found (two named); aliases G-PROG-1 /
                   G-TIME-1 / G-ORCH-A1; no identifier renamed

NEW QUESTIONS      N-1 timezone change after publication · N-2 undecided candidacy ·
                   N-3 is nomination completion itself a precondition · N-4 created vs valid ·
                   N-5 is preparation recorded

ARCHITECTURE MAY PROCEED WHEN
  · EM-OPEN-023 is ruled (with N-1 and N-4 travelling with it), AND
  · T1-MEANING (A)-(D) is ruled
Architecture MAY proceed NOW, within these limits only, on what §3 and §6 settle:
  published schedule vs actual event as separate facts; the two histories kept distinct.
It may NOT choose a publication moment, a timezone rule, or a pending-candidacy rule.

IMPLEMENTATION
NOT AUTHORIZED
```

**Traceability.** Commission (PO/ARB, 2026-08-15) · Manifesto §§3, 4, 4a, 9 as verified in §0 · `EM-OPEN-022` ruling registration (`e28e6c7b`) · `EM-OPEN-021` *(preserved)* · `EM-OPEN-018` *(open, display-scoped)* · `PBDIGIT-60` publication ruling *(precedent, not authority)* · `PBDIGIT-59` *(`G-TIME-1`)*.

**STOP — end of Governance report. No Architecture work follows.**
