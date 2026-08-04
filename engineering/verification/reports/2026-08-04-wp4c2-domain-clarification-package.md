# WP-4C-2 — Domain Clarification Package

**For:** the **Contestation domain owner**. **From:** engineering, 2026-08-04.
**Status:** engineering discovery is **closed**; engineering is **waiting**. Nothing proceeds until these decisions are returned.

> **This package contains four things only: the unresolved business questions · the engineering evidence bearing on each · the engineering constraints · the decisions required.**
> **It contains no proposed solutions, no candidate workflow states, and no implementation recommendations — by instruction and by design.**

**Why this is a separate document from the discovery reports:** those contain engineering analysis — alternatives weighed, implementation implications, cost estimates — which this package is required to exclude. **Different audience, different purpose. The reports remain the evidence; this is the ask.**

---

## 1. The situation, in business terms

An election result is contested. The challenge is routed to adjudication. The constitutional authority examines the evidence and **declares that it is insufficient — no ruling can be issued.**

**Two facts are already settled and are not in question here:**

- **Collection owes a correction.** The accepted Context Map records the return flow to Collection as an *"insufficiency finding / correction demand"*, bounded so that Adjudication may demand **more or better evidence** but may not direct **how** Collection works. *(COL-5a, subsumed into COL-1's Customer–Supplier contract.)*
- **Contestation owns the challenge itself**, and what becomes of it on a declared failure is recorded as an **open question with Contestation named as its owner** (EPIC-004K §15.3).

**What is missing is the business meaning of the interval between those two facts.**

---

## 2. The questions

### Q1 — What business fact ends the waiting period?

Collection owes more or better evidence. **What event, decision, or deadline concludes that owing?**

**Evidence:** COL-5a establishes *that* the correction is owed. **No examined artefact states what discharges it.**

### Q2 — Does the original challenge remain the same business object during corrective work?

**Is this the same challenge, waiting — or does corrective work produce a new challenge?**

**Evidence — and it points both ways.** Two accepted artefacts disagree, which is itself why this is asked:

| Artefact | What it implies |
|---|---|
| The adjudication-process table's **partial** unique index, written so *"a challenge may accumulate processes over time … but never two at once"* | the **same** challenge persists and can be adjudicated more than once |
| The `Challenge` aggregate's routing guard, which permits routing only from `Admitted` or `Investigating` | a routed challenge **cannot** be routed again |

**Neither is wrong; they were written for different purposes.** **Engineering has not resolved this and will not** — a conflict between two accepted artefacts is not evidence of the business's intent.

### Q3 — Which bounded context owns responsibility after that business fact?

**Before** it: Contestation owns the challenge; Collection owns the correction. **After** it: **unknown, because Q1's fact is unknown.**

**Evidence:** COL-5a · EPIC-004K §10.

### Q4 — Does the confirmed business meaning already exist in the Contestation model?

**Answerable only once Q1 and Q2 are decided.**

**Evidence engineering can supply:** the current **implementation** model does not express a waiting or returning challenge — from `Routed`, the only available transition requires a determination identifier, which a declared failure never produces. **This demonstrates a limitation of the implementation, not that the business model is wrong.** If the business decides no waiting state exists, the model may be correct as it stands.

---

## 3. Engineering constraints — what the answers must live with

| Constraint | Source |
|---|---|
| Adjudication may demand **more or better evidence**; it may **not** direct how Collection works | COL-5a's pattern ruling |
| A declared failure **has no determination**, and never will — nothing was ruled | EPIC-004K §10 · PM-7 |
| A record already fixed **may never be reached back into** | COL-2 / COL-5b's constitutional limit |
| Adjudication announces the fact; it does **not** decide the challenge's disposition | ADR-T8 (loop head only) · §15.3 |
| Whatever is decided must hold for **one** challenge at a time in adjudication | PM-1 · INV-B1 |

---

## 4. Decisions required from the business

1. **The fact that ends the waiting period** (Q1).
2. **Whether the challenge survives corrective work** (Q2) — and if two accepted artefacts must be reconciled, which expresses the business's intent.
3. **The responsibility owner after that fact** (Q3).
4. **Whether that meaning is already present in the domain** (Q4) — or genuinely absent.

**Each answer will be recorded as one of: `Confirmed Domain Decision` · `Clarification of Existing Policy` · `New Business Policy` · `Out of Scope`.** **No answer will be recorded as engineering evidence**, and engineering will not upgrade an answer's classification by acting on it.

---

## 5. What engineering will and will not do

**Will:** attach traceability · identify the bounded contexts and invariants each answer touches · state the implementation implications **after** the decisions are made.

**Will not:** infer business intent · resolve the Q2 conflict · propose states, transitions, events or aggregates · begin tactical design.

**Until these answers return, engineering is stopped on WP-4C-2 — not slowed.**

---

## 6. Evidence sources

`engineering/verification/reports/2026-08-04-wp4c2-discovery.md` · `…-responsibility-analysis.md` (Phase A) · `…-phase-b-contestation-discovery.md` (Phase B) · **EPIC-002** Canonical Context Map (COL-1 · COL-5a · COL-5b) · **EPIC-004K §10 · §15.3** · PM-1 · PM-7 · INV-B1 · ADR-T8 · `Challenge.php` · `ChallengeState.php` · `2026_07_30_000001_create_adjudication_processes_table.php`.
