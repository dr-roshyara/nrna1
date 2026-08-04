# WP-4C-2 — Phase A: Responsibility Analysis (A-0)

**Produced by:** engineering, 2026-08-04. **Strategic discovery. No state, event or aggregate designed.**
**Question:** who owns the next business responsibility once Adjudication concludes that no determination can issue?
**Method:** every conclusion labelled **EVIDENCE** (supported by an existing domain artefact) · **INFERENCE** (reasoned from evidence) · **HYPOTHESIS** (needs validation).

---

## 0. 🛑 THE FINDING — A-0 was mis-framed, and the accepted Context Map says so

**A-0 asked "who owns the next move?" as if one owner had to be chosen from four candidates. The Canonical Context Map records TWO distinct responsibilities on the declare-failure path, with two different owners — and they are not competing answers to one question.**

| Responsibility | Owner | Basis |
|---|---|---|
| **The corrective move** — supply more or better evidence | **Collection** | **EVIDENCE** — COL-5a |
| **The challenge's disposition** — what becomes of the challenge | **Contestation** | **EVIDENCE** — EPIC-004K §10, §15.3 |

**These are different questions about the same event.** *"What must now happen to fix the deficiency?"* is not *"what is the standing of the challenge meanwhile?"* **My discovery report's four-candidate list implied an exclusive choice, and that framing was wrong.**

## 1. Ownership matrix

| Candidate | Business responsibility | Trigger | Required business action | Evidence FOR | Evidence AGAINST | Verdict |
|---|---|---|---|---|---|---|
| **Collection** | supply **more or better evidence** | the insufficiency finding | respond to a correction demand on COL-1's return channel | **EVIDENCE.** Context Map row: *"COL-5a \| Adjudication → Collection \| Insufficiency finding / correction demand \| Conditional (declare-failure path)"*. Pattern ruling: *"Subsumed into COL-1's Customer–Supplier contract"*, because *"a declare-failure outcome flowing back to Collection **is** the customer expressing unmet requirements"* | none found | ✅ **OWNS the corrective move** |
| **Contestation** | the **challenge's disposition** | the declared failure | decide the challenge's standing | **EVIDENCE.** EPIC-004K §10 names Contestation a consumer; **§15.3 carries *"challenge disposition on declared failure: Contestation-side design"* as an open question with a named owner** | none found | ✅ **OWNS the disposition** — and it is **unanswered**, which is why WP-4C-2 exists |
| **Contemporaneous Record-Fixing** | **future** recording practice | a record-deficiency finding | change what records must contain **prospectively** | **EVIDENCE.** COL-5b: *"record-deficiency finding, prospective only"*, Customer–Supplier with a hard limit — *"it may never reach back to an already-fixed record"* | **not this path.** COL-5b is a *record-deficiency* finding; §10's declare-failure is an *evidence-insufficiency* finding | ⚠️ **adjacent, not an owner here** — **HYPOTHESIS** that the two findings can coincide; unvalidated |
| **Election** | none identified | — | — | none found | **EVIDENCE AGAINST.** `ChallengeAdjudicationReaction` short-circuits on `Dismissed` *"because Election is silent"* — Election reacts to determinations that change a result, and a declared failure changes none | ⛔ **no responsibility identified** |
| **Human authority** | none identified | — | — | none found in any accepted artefact | the authority's role is **upstream** — it *declares* the insufficiency (PM-7); nothing records it as the *recipient* of the consequence | ⛔ **no responsibility identified** — **HYPOTHESIS** only, if escalation is later required |

## 2. Responsibility evidence matrix — what each claim rests on

| Claim | Label | Source |
|---|---|---|
| Collection receives an insufficiency finding / correction demand on the declare-failure path | **EVIDENCE** | `EPIC-002_Canonical_Context_Map.md:75` |
| That flow is the **return channel of COL-1**, not a new relationship | **EVIDENCE** | `EPIC-002_Relationship_Pattern_Selection.md:87` |
| Adjudication may demand *more or better evidence* but **may not direct how Collection works** | **EVIDENCE** | ibid., *Consequences* |
| Contestation owns challenge disposition on declared failure, and it is **open** | **EVIDENCE** | EPIC-004K §10 · §15.3 |
| Election has no responsibility on this path | **INFERENCE** from evidence | `ChallengeAdjudicationReaction` (*Election is silent* on outcomes that change no result) + no artefact naming Election |
| The two responsibilities are **independent** — neither blocks the other | **INFERENCE** | they are different edges (COL-5a vs §10) with different contracts; nothing couples them |
| Record-Fixing could be co-triggered | **HYPOTHESIS** | COL-5b exists but describes a *different* finding type |

## 3. Phase B — consequences, and only what ownership establishes

**A-1 (terminal or re-routable?) is now answerable in part, and the answer is not a single verdict:**

| Question | Answer | Label |
|---|---|---|
| Is the *programme-level* disposition terminal? | **NO.** Collection owns a corrective move, so work remains outside Adjudication | **INFERENCE** from COL-5a's evidence |
| Is the *challenge's* state terminal? | **UNRESOLVED — and it is Contestation's to decide.** A pending correction demand is compatible with either a terminal challenge (a new one is raised later) or a suspended one (this one resumes) | **remains open** |
| Is a new workflow state necessary? | **NOT DETERMINABLE HERE.** It follows Contestation's disposition decision | **remains open** |
| Which event announces the transfer of responsibility? | **`AdjudicationFailureDeclared` already announces the fact.** Whether COL-5a's *demand* is the same event or a distinct one is **not settled by any artefact examined** | **HYPOTHESIS** |

**Applying the refined formulation:** *cross-context workflow state follows responsibility ownership.* **Collection's ownership is established, so the programme-level flow is not terminal. Contestation's disposition is not established, so its state must not be chosen.** The two conclusions differ because the two ownerships differ in evidential status — not because one is more important.

## 4. Readiness assessment for WP-4C-2 planning

> **STILL NOT READY — but the blocking question has narrowed from two to one, and it is now precisely stated.**

| | |
|---|---|
| **A-0 (ownership)** | ✅ **RESOLVED FROM ACCEPTED ARTEFACTS** — Collection owns the corrective move (COL-5a); Contestation owns the disposition (§10) |
| **§15.3 (the disposition itself)** | ⛔ **STILL OPEN — Contestation's to decide.** Unchanged by this analysis |
| **A-1 (terminality)** | ◐ **PARTLY ANSWERED** — not terminal at programme level; the challenge's own state remains Contestation's |
| **D4 (publication)** | ⛔ still held — nothing publishes the event |
| **COL-5a's mechanism** | ⚠️ **NEWLY VISIBLE AND UNALLOCATED.** The Context Map establishes the edge; **no work package covers it**, and it is not WP-4C-2 (which is Contestation's) |

**The one question now blocking WP-4C-2:**

> **Given that Collection owns the corrective move, what is the challenge's standing while that correction is outstanding — does the challenge end, or wait?**

**That is narrower than the original §15.3 and it is answerable**, because the surrounding responsibilities are now evidenced rather than assumed.

## 5. What engineering does NOT conclude

- **No state is proposed.** `Lapsed`, `Dismissed`, a new case — none is recommended, and §1 deliberately does not rank them.
- **COL-5a's mechanism is not designed.** Its edge is evidenced; its implementation is unallocated and unauthorized.
- **"Record-Fixing may be co-triggered" stays a HYPOTHESIS.** COL-5b describes a different finding type, and no artefact says the two coincide.
- **No principle is promoted.** *Cross-context workflow state follows responsibility ownership* is a **WP-4C discovery with one confirming work package** — not programme governance. A second independent confirmation would be needed, per ES-006.1.

## 6. Traceability

**EPIC-002** `Canonical_Context_Map.md` (COL-1:70 · **COL-5a:75**) · `Relationship_Pattern_Selection.md` (**COL-5a §85-93** · COL-5b) · **EPIC-004K §10 · §15.3** · PM-7 · **R-88** (WP-4C-2 → Contestation) · R-80 · ADR-T20 · `ChallengeAdjudicationReaction.php` · `ChallengeState.php` · discovery report `2026-08-04-wp4c2-discovery.md` (A-0…A-5).
