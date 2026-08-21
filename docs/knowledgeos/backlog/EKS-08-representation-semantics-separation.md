# EKS-08 — Representation–Semantics Separation & Measurement Independence: KnowledgeOS invariants must be evaluated against semantic structure, not representation heuristics

**Status:** **FUTURE ARCHITECTURE EXPLORATION** — P4/P5 **research observation** registered from the R-1 EKS baseline corpus-completion gate review (commit `aa08151d` · HPA ruling 2026-08-22, recorded `81563a24`). ⛔ **Not commissioned; activation requires a human authorization act.** ⛔ **Not an invariant; not INV-005; the invariant map is unchanged.**
**Class:** candidate constitutional invariant (research) — KnowledgeOS.
**Registered by:** parent session recording the HPA ruling of 2026-08-22. ⛔ **This registration approves no architecture and no invariant.**

> ### ⛔ **Registered as research observation — NOT `DECIDED` · NOT `APPROVED` · NOT `ADOPTED` · NOT an invariant row.** The map stays `INV-001..004 + INV-KOS-001`; the EKS baseline is unchanged (the review found no baseline error).

---

## 1 · The observations that produced it (from the R-1 gate review)

**1a. Measurement trap — `22/218` → `2/218`.** A substring heuristic over `.claude/runtime/workflow/*.json` reported 22/218 "time-like" transitions. The matches were **false positives**: `recordedBy` (an actor-role field), the token `date` inside prose, and tokens ending in `at`. Field-level semantic extraction reduced the count to **exactly 2/218** — the two genuine `date` fields (seq 25 START + seq 26 HANDOFF of `KOS-AIP04-DISCOVERY-001`, day granularity) — matching the baseline's claim precisely.

**1b. Derived ≠ persisted — the fold.** `mutationOwner`, `workItemState`, and `sessions` are **computed** by `workflow-state.php` from the append-only transition log; they are **not stored**. An independent reimplementation of the fold initially returned 4 live owners; executing the actual mechanism returned the correct **2** (17 OPEN / 1 STOPPED; two OPEN items carry a live owner). The reimplementation missed that a `HANDOFF` clears `mutationOwner` (ownership passes only at `START`).

> ### ⭐ **The lesson is not the 2/218 count. It is the discipline:** *a property of KnowledgeOS must be defined against the semantic structure of the evidence, not discovered by substring or representation heuristics.*

## 2 · The candidate principles — ⛔ hypothesis only, nothing adopted

**Measurement Independence (HPA candidate):** *"A KnowledgeOS invariant SHALL be evaluated against the semantic structure of a knowledge record, not merely against incidental representation patterns."*

**Representation–Semantics Separation (HPA candidate):** *"A representation SHALL NOT acquire semantic authority merely because a pattern, field, structure, or computation resembles another semantic concept."*

**Examples from EKS:** `recordedBy` ≠ temporal validity · derived `workItemState` ≠ persisted source state · a projection ≠ source · a measurement ≠ architectural fact · a recommendation ≠ decision · evidence ≠ authority.

## 3 · The layers — candidate vocabulary

```
SOURCE           persisted authoritative record
DERIVATION       deterministic computation from source
PROJECTION       representation of derived state
MEASUREMENT      observation produced by an analysis
INTERPRETATION   conclusion drawn from measurement
AUTHORITY        separately governed authorization
```

**Kernel question:** *"Can one layer silently become another?"* — substring match → measurement → architecture claim (❌ forbidden) · projection → source-of-truth (❌ forbidden) · evidence quality → authority (❌ forbidden).

## 4 · Why it is NOT a new invariant row yet (HPA discipline, binding)

- The current four atomic invariants (INV-001..004) are **established**; INV-KOS-001 is the **composite candidate**. This generalization arises from observed failure modes — creating an INV-005-style row is **NOT done**.
- ⛔ **Do not create INV-005.** ⛔ **Do not modify the invariant map.** ⛔ **Do not change the EKS baseline** (the review found no baseline error).
- Carry the candidates forward into **P4/P5**, where the P5 question set and the domain-independence test may evaluate — and only then possibly promote or reject — them.

## 5 · Relationship to the emerging map

- **Strengthens the Dimension Purity Principle** (every dimension has its own meaning, lifecycle, ownership, and transition rules; collapse is the failure).
- **Very close to INV-004 Projection ≠ Source**; may eventually be a *more general formulation* behind several existing invariants — but that is a P4/P5 question, not a claim.
- **Architectural reading (HPA):** EKS/PKS/AIP are giving counterexamples that make the kernel definition more precise — EKS: *"here are the ways a knowledge system can accidentally confuse things"* · PKS: *"explicit separations and fail-closed rules"* · AIP: *"orchestration can consume knowledge without owning it"*. KnowledgeOS: *"what constitutional rules prevent those collapses regardless of which implementation sits underneath?"*

## 6 · P4/P5 disposition

- **P4:** carry the observation; the evidence-collection for the Constitutional Invariant Map must itself apply Measurement Independence — evaluate claims against semantic structure, not representation.
- **P5:** the question set and domain-independence test may promote or reject the two candidates.
- ⛔ No activation; starting any exploration requires a human/PO/ARB authorization act, routed through Governance.

---

## Traceability

R-1 corpus-completion gate review verdict (2026-08-22) · commit `aa08151d` (EKS baseline corpus completion) · HPA ruling 2026-08-22 recorded in `.claude/sessions/2026-08-22.md` + `.claude/CONTEXT.md` (commit `81563a24`) · measurement evidence: `.claude/runtime/workflow/*.json` (18 records · 218 transitions) · mechanism: `.claude/scripts/workflow-state.php` (`fold`). All study outputs remain PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED.
