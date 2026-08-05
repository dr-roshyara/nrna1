# WP-4C-2 — Phase B: Contestation Discovery

**Produced by:** engineering, 2026-08-04. **Discovery only — no state invented, no transition designed.**
**Status: ✅ COMMISSION ACCEPTED · ⛔ DISCOVERY CLOSED · Phase C HANDED TO THE CONTESTATION DOMAIN OWNER** (ARB, 2026-08-04). **WP-4C-2 is in a waiting state pending authoritative business decisions — this commission only; other authorized work is unaffected.** Handover artefact: `2026-08-04-wp4c2-domain-clarification-package.md`.
**The one question:** given that **Collection** owns the corrective action (COL-5a, established in Phase A), what is the **business standing of the challenge** while correction is outstanding?
**Method:** **EVIDENCE** (accepted artefact) · **INFERENCE** (derived) · **HYPOTHESIS** (needs validation).

---

## 0. The answer comes from the guards, not from judgment

Phase A's discovery report said the adjacent states were *"none obviously right"* — a judgment about fit. **The transition guards say something stronger and checkable: most of them are not reachable at all.**

```php
admit(…)        guard: Raised
dismiss(…)      guard: Raised                              ← NOT reachable from Routed
route(…)        guard: Admitted, Investigating             ← NOT re-enterable from Routed
adjudicate(…)   guard: Routed        + requires DeterminationId
resolve(…)      guard: Adjudicated   + requires DeterminationId
lapse(…)        guard: Raised, Admitted                    ← NOT reachable from Routed
```

> **FROM `Routed`, EXACTLY ONE TRANSITION EXISTS: `adjudicate(DeterminationId, …)`.** **EVIDENCE** — `Challenge.php:73-133`.

**Three consequences follow directly, and none required a modelling opinion:**

1. **`Dismissed` and `Lapsed` are not candidates** — not because they fit poorly, but because **their guards make them unreachable from `Routed`.** *(This supersedes the "adjacent states" framing in the discovery report, which was weaker than the evidence available.)*
2. **Re-routing is not expressible** — `route()` guards `Admitted`/`Investigating`, so a routed challenge cannot be routed again.
3. **The single available exit requires the one thing a declared failure never has** — a `DeterminationId`.

## 1. Alternatives supported by existing artefacts

**Only two are, and neither is complete. No third is invented here.**

| | **ALT-1 — the challenge stays `Routed`** | **ALT-2 — the challenge returns for re-routing** |
|---|---|---|
| **Business meaning** | "still with adjudication" — unchanged by the declared failure | "back with Contestation, awaiting corrected evidence" |
| **Owner** | Contestation owns the challenge; Collection owns the correction | same |
| **Lifecycle implication** | **no transition at all** — the challenge is standing still by *absence* of a path, not by design | requires a transition out of `Routed` that does **not** exist |
| **Supporting evidence** | **EVIDENCE:** it is what happens today. Nothing consumes `AdjudicationFailureDeclared`, so the state is untouched | **EVIDENCE of intent:** the adjudication-process migration records *"an expired one returns to Contestation, which may route it again — WP-5"*, and its **partial unique index deliberately permits a challenge to accumulate processes over time**, which only re-routing needs |
| **Contradicting evidence** | **`Routed` means "handed to adjudication."** After a declared failure adjudication holds nothing — so the state would assert a false fact. **INFERENCE** | **the transition does not exist in the aggregate.** `route()` cannot be re-entered, and no other path leaves `Routed`. **EVIDENCE** |
| **Verdict** | ⛔ **not a business answer** — it is the current absence of one | ◐ **the only evidenced intent, and it is UNIMPLEMENTED** |

## 2. 🛑 FINDING — the accepted re-route intent has no aggregate transition behind it

**Adjudication's schema was built for a challenge that can be adjudicated more than once over time.** Its partial unique index exists *precisely* so that *"a challenge may accumulate processes over time … but never two at once."*

**The `Challenge` aggregate cannot produce that sequence.** Nothing leaves `Routed` except `adjudicate()`, so a challenge can be routed exactly once, ever.

> **REFINED WORDING (ARB, 2026-08-04) — stated at the strength the evidence supports:**
> **The current implementation does not expose a transition supporting either declared-failure or expiry re-routing. Whether both ultimately share the SAME transition remains a design decision for Contestation.**
>
> *(An earlier phrasing said “the same missing transition blocks the expiry path”, which asserted that one transition would serve both. The evidence shows only that NEITHER is expressible today.)* **INFERENCE, from two accepted artefacts that presuppose a capability neither provides.**

**This is why Phase A's A-3 was right to refuse to assume one reaction could serve both events — and it now cuts the other way too: the two paths share a missing capability even if they need different dispositions.**

## 3. Completion criteria — answered

| # | Question | Answer | Label |
|---|---|---|---|
| **1** | Does the challenge terminate? | **It cannot, in the current model.** No terminal state is reachable from `Routed` | **EVIDENCE** (guards) |
| **2** | Does it remain pending? | **It remains `Routed` by default — because nothing moves it, not because pending was modelled.** "Pending" is currently an absence, not a state | **EVIDENCE + INFERENCE** |
| **3** | Who owns it during that period? | **Contestation owns the challenge; Collection owns the corrective action** (COL-5a) | **EVIDENCE** (Phase A) |
| **4** | Does the existing model express that state? | **NO.** `Routed` asserts "handed to adjudication", which is false once adjudication has declared it cannot rule | **INFERENCE** |
| **5** | What evidence demonstrates a new state is genuinely required? | **The guard analysis (§0) — and it shows a new state may not be what is required.** From `Routed`: no terminal state is reachable, `route()` cannot be re-entered, and the only exit needs a `DeterminationId`. **So a new TRANSITION is definitely required; whether it needs a new STATE depends on whether the destination is an existing state (`Admitted`/`Investigating`, for re-routing) or a new one.** That is Contestation's call | **EVIDENCE for the transition · OPEN for the state** |

## 4. Readiness assessment

> **WP-4C-2 IS READY FOR PLANNING ONCE ONE DECISION IS TAKEN — and the decision is now a choice between two evidenced options, not an open modelling problem.**

**The decision for Contestation:**

> **Does a declared failure return the challenge for possible re-routing (the intent already recorded for expiry), or does it end the challenge in a way the model cannot currently express?**

**If re-routing:** the destination is plausibly an existing state, so this may be a **transition-only** change — **HYPOTHESIS**, because whether `Admitted` or `Investigating` is the correct destination is a business question about whether admission survives a failed adjudication.

**If terminating:** a new state is required, because no terminal state is reachable from `Routed`. **EVIDENCE.**

**Two things this analysis does NOT do:** it does not choose between them, and it does not propose the transition's name or shape.

## 5. Consequences for adjacent work, surfaced not absorbed

| Item | Status |
|---|---|
| **The expiry path (WP-6/Q-2) is equally unexpressible** | Its return-and-re-route intent is accepted and **also has no transition behind it**. **Whether one transition serves both paths is a design decision for Contestation, NOT a conclusion of this report** — corrected here to match §2's refined wording, which withdrew the shared-transition claim. **INFERENCE limited to: neither path is expressible today** |
| **COL-5a's mechanism** | still unallocated (Phase A) |
| **D4** | still held — nothing publishes the event |
| **A-4 / COL-5a's second consumer** | still unassessed |

## 6. Traceability

`Challenge.php:73-133` (**the guards — the load-bearing evidence**) · `ChallengeState.php:15-22` · **EPIC-002** COL-5a · **EPIC-004K §10 · §15.3** · **Q-2** horizon ruling · `2026_07_30_000001_create_adjudication_processes_table.php` (the partial unique index and its recorded rationale) · **R-88** · R-80 · ES-006.1 · Phase A `2026-08-04-wp4c2-responsibility-analysis.md` · discovery `2026-08-04-wp4c2-discovery.md`.

---

## 7. Phase C — the criteria, recorded before the work

**Phase C resolves BUSINESS SEMANTICS. It is not authorized to design.**

**Constraint (ARB, 2026-08-04):** **do not design workflow transitions or implementation mechanisms.** Business semantics unavoidably refer to responsibility changes — *what fact transfers responsibility, what outcome ends waiting, whether responsibility returns* — and **discussing those is in scope; specifying a transition is not.**

**Completion criteria — Phase C is complete when it can answer:**

1. **What business fact ends the waiting period?**
2. **Does the original challenge survive that period?**
3. **Which bounded context owns the challenge before and after that fact?**
4. **Does the existing Contestation model already express that business meaning?**
5. **If not, what evidence — not implementation convenience — demonstrates that the model must change?**

### ⚠️ Engineering's readiness statement for Phase C

**Phases A and B were answerable from accepted artefacts. Questions 1 and 2 above are not.**

**ENGINEERING-ACCESSIBLE DOCUMENTARY EVIDENCE HAS BEEN EXHAUSTED. Further progress requires domain-owner business clarification rather than additional engineering discovery.** *(Refined 2026-08-04 from “evidence exhausted”, which claimed too much: evidence could still emerge from domain experts, business workshops, constitutional clarification, or an accepted artefact this sweep did not surface. What is exhausted is what ENGINEERING CAN READ.)*

**What was read and what it settles:** the Context Map settles ownership of the corrective path (COL-5a) · EPIC-004K §10/§15.3 names the disposition question and leaves it open · the aggregate guards settle reachability. **No artefact examined states what business fact ends the waiting period, or whether a challenge survives a failed adjudication.**

> **So Phase C is NOT an evidence-gathering exercise engineering can complete. It requires the domain owner — Contestation — to state a business meaning that no artefact currently records.** Producing candidate answers here would be inventing business semantics under a discovery label, which is the same defect Phase B refused when it declined to choose a state.

**Question 3 is already answered** (Phase A: Contestation owns the challenge, Collection the correction). **Question 4 is answered in the negative** (§0: `Routed` asserts a fact that is false after a declared failure). **Questions 1, 2 and therefore 5 need the domain owner.**

### 7a. The Phase C clarification package — questions with their evidence attached

**No separate Phase C artifact is created.** The questions, their constraint and their evidence belong together, and this section already holds them; a second document would split one package across two homes (ES-005.4). **This section IS the package put to the domain owner.**

| # | Question for the domain owner | Evidence engineering can attach | Status |
|---|---|---|---|
| **1** | **What business fact ends the waiting period?** | **Nothing found.** COL-5a establishes that Collection owes *more or better evidence*; **no artefact says what arrival, decision or deadline concludes that owing** | ⛔ **needs the domain owner** |
| **2** | **Does the original challenge remain the same business object through corrective work?** | **Nothing direct.** Two accepted artefacts point opposite ways: the process table's partial index **permits** a challenge to accumulate processes (suggesting the same object persists), while `route()`'s guard **forbids** re-routing (suggesting it cannot) | ⛔ **needs the domain owner** — and **the tension itself is evidence the question is real** |
| **3** | **Which context owns responsibility before and after that fact?** | **Before:** Contestation owns the challenge · Collection owns the correction (COL-5a, §10). **After:** unknown, because the fact in Q1 is unknown | ◐ **half answered** |
| **4** | **Does the existing model express that meaning?** | **Engineering concludes the currently examined Contestation IMPLEMENTATION MODEL does not express it:** from `Routed` exactly one transition exists and it requires a `DeterminationId`; `Dismissed` and `Lapsed` are unreachable; `route()` is not re-enterable. **⚠️ Whether the overall BUSINESS model must change remains contingent on the answers to Q1 and Q2** — *(refined 2026-08-04; an earlier line read simply “answered — negative”, which conflated an implementation-model limitation with a business-model deficiency. The code cannot express it; that is not yet proof the DOMAIN must evolve.)* | ◐ **answered for the implementation model · OPEN for the business model** |
| **5** | **What business evidence requires the model to change?** | **Derivable only once Q1 and Q2 are answered.** Engineering can say what each answer would COST (re-routing ⇒ possibly transition-only; terminating ⇒ a new state is required, since none is reachable) — **but cost is not business evidence, and must not be mistaken for it** | ⛔ **downstream of Q1/Q2** |

**Classification discipline for the answers when they arrive:** each becomes a **CONFIRMED DOMAIN DECISION** — not an Observed Evidence item, an Inference, or a Business Hypothesis. **Engineering must record which of the four each answer is, and must not upgrade a hypothesis by using it.**

**The one thing engineering must not do while waiting:** answer Q1 or Q2 by extrapolating from the two artefacts in Q2's row. **They conflict, and a conflict is not a hint** — resolving it in either direction would be inventing the business meaning this phase exists to obtain.

