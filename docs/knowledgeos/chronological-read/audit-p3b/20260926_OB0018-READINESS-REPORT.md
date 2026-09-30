# OB0018 readiness report (read-only preparation; no execution)

**Kind:** readiness analysis for a human decision. **Nothing executed · no agent dispatched · no corpus content read · no frozen artifact modified.**

**Authority:** G-LOG-0077 (human decision O1). This document decides nothing.

**Governing documents (unchanged):**
- Master Protocol v3.5;
- P3b v1.7 (frozen);
- S5 plan v2.3.2 and contract revision 3;
- decomposition-pilot pre-registration (G-LOG-0052);
- post-pilot brief `audit-p3b/20260925_1355_s5-post-pilot-architecture-decision-brief.md` (G-LOG-0054) §D, the source design.

---

## 1. O1 closure status

- **Recorded:** G-LOG-0077 (`04f74a2cf`). The V1.2.4 E0 line is closed.
- **Status of the instruments:** they remain **unvalidated**, because E0 terminated before the statistical decision stage under §18. That is not a failure verdict.
- **Not pursued:** O2 and O3 are not implemented; there is no V1.2.5.
- **Preserved hypothesis (untested):** per-file or chunked M1 matching.
- **Immutable:** PX0106–PX0109.

## 2. What "OB0018" means here (disambiguation; a fact from the record)

| Name | What it is | State |
|---|---|---|
| **S5 production batch OB0018** | 5 labels, tier U, 3 in the checklist, contract revision 3, predicted 2,657 dispositions; run `OB0018-R2` | `PREPARED` in `P3B-STATE.json`; **not dispatched; not in scope here** |
| **The "OB0018 decomposition-fidelity track"** | the **multi-unit equivalence experiment** of brief §D. It uses **one** label of that batch as its control: `s1620-removal-test-provenance-base-case-and-user-decisions` (seeded pick, rule in §D), in an isolated pilot namespace (`PX0018-…`) | proposal only; human decision §I-1 pending |

**Consequence:** executing the track touches **no** production batch. OB0018-R2 stays PREPARED.

## 3. Research question and design facts (answers to the 16 readiness questions)

| # | Question | Answer (source) |
|---|---|---|
| 1 | What should OB0018 establish? | whether **decomposition plus synthesis** preserves the judgments of **single-context analysis** on a label that both can process (brief §B "not established": multi-unit epistemic equivalence) |
| 2 | Precise research question | **on a fixed multi-unit label, do synthesis arms A (records only), B (plus mechanical invariant/consistency pass) and C (plus bounded whole-file re-reads at cross-unit adjacencies and conflicts) reproduce the single-context baseline on the frozen comparison fields, with zero BASELINE-UPHELD on judgment fields and failure-mode metrics within frozen bounds?** Hypotheses H0 (A suffices), H1 (B needed), H2 (C needed) |
| 3 | Unit of observation | per **(arm, comparison field)** disposition on one label. The label-level resolution is a single observation (n = 1 label) |
| 4 | Input population | the control label's required set R(L) = stage-2 files ∪ row sources (no thinning): **34 files, 771,717 bytes**, 8 row sources, 5 OMQ-14 sources, no binary file, non-hub, tier U (recomputed in §4; identical to brief §D) |
| 5 | Output | per arm: a synthesized label object (production schema, verifier-checked); 3 shared unit record sets; the baseline object; one blind audit record; failure-mode metrics |
| 6 | Success | per arm **faithful** = 0 PROTOCOL-VIOLATION ∧ 0 BASELINE-UPHELD on judgment fields ∧ each failure-mode metric within its frozen bound. Decision mapping: A faithful → A; else B → B; else C → C |
| 7 | Failure | no arm faithful → none shown to preserve equivalence (a valid negative result) |
| 8 | Evidence retained | page ledgers (READ-LOG, hashes), unit records, synthesized objects, baseline, verifier outputs, audit, provenance chain SOURCE → PAGE → UNIT → SYNTHESIS |
| 9 | What remains sealed | H-19 hold-out (reader and resolver refuse); arm labels are blind to the auditor until the audit is frozen |
| 10 | Human authorization point | (i) approve the §D design, with or without amendments (brief §I-1); (ii) authorize execution of the frozen pre-registration |
| 11 | S5 decisions it informs | the **S5 load architecture** (brief §H options 1–3): whether decomposed labels need a consistency pass (B) or re-reads (C); the re-read budget; whether the 4 known loss mechanisms change label-level outcomes |
| 12 | Decisions it does NOT inform | binary-file policy; the heaviest labels (up to 8.59 MB / 25 units); model-family independence; the co-label edge rule (it only measures its effect); any research or theory question; Decision A |
| 13 | Constraints that stay unchanged | population, 396-batch composition, 1,975 labels, hubs, K = 64, A'1, whole-file rule, READ-COVERAGE, claim hierarchy, contract revision 3, R19; production unaffected |
| 14 | Computational load | about **8 agent contexts**: 1 baseline (772 KB; within the 1.09 MB completed in R2.3), 3 units (≤ 300 KB each), 3 syntheses, 1 audit. Arm C adds ≤ 300 KB of re-reads (frozen budget) |
| 15 | Context bottlenecks | the **baseline** is the tightest (772 KB against about 1.1–1.3 MB demonstrated). The **audit** needs its own whole-file reads of disputed fields. Units are comfortably small |
| 16 | Smallest reliable experiment | the §D design itself: one label, shared units, three synthesis arms, one blind audit. Smaller designs lose the discriminating arms. See §6 for the minimum amendments |

## 4. Computational-load assessment (deterministic; metadata only; aggregates)

**Method:**
- Recomputed in memory with the production preparer's `Context` and the pilot's frozen required-set rule and `partition` (first-fit decreasing, files never split).
- Sizes come from git object sizes only. No content was read, no file was written, and no hold-out identifier is printed.

| Quantity | Value |
|---|---|
| S5 labels / hubs | 1,975 / 66 |
| required bytes per label: median / p90 / p99 / max | 57,075 / 536,106 / 3,193,099 / 8,586,148 |
| files per label: median / p99 / max | 2 / 102 / 298 |
| total required bytes | 476.9 MB |
| labels above the demonstrated single-context capacity (1,088,267 bytes) | **86 (4.4%)**, carrying **43.8% of all required bytes** |
| units at a 600 KB budget | 1 unit: 1,790 · 2–3: 137 · 4–10: 36 · >10: 4 (max 13); **358 unit boundaries** in total |
| units at a 300 KB budget | 1 unit: 1,610 · 2–3: 251 · 4–10: 91 · >10: 15 (max 25); 963 boundaries |
| OB0018 control | 771,717 bytes, 34 files, non-hub; **3 units at 300 KB**; percentile rank 0.93 |

*(Definitions note: the load brief reported a median of 38,975 and the post-pilot brief 82 labels over 1.09 MB (text only); the definitions differ slightly (row-source basis; binary files included here). The maximum 8,586,148 agrees with the load brief.)*

**Classification** (the question from the human instruction):
- **B: tractable with deterministic batching** for **≈ 90% of labels** (1 unit at 600 KB).
- **C: tractable only with decomposition** for the heavy tail, which carries 44% of the bytes.
- **D (a methodological amendment) is not indicated by load alone.** Capacity is solved mechanically, as the pilot showed; the open question is **synthesis fidelity**, which is exactly what OB0018 measures.

**Representativeness caveat:**
- The control is a **3-unit** case (2 boundaries). The tail reaches 13 units at 600 KB.
- Losses at boundaries may scale with the boundary count, so OB0018 bounds fidelity for **few-unit** labels only. Extrapolation to 10+ units is **not** licensed.
- Arm C's re-read cost scales with boundaries: ≈ 358 × 2 file re-reads population-wide at 600 KB, an **estimate**.

## 5. Decomposition options (brief §H, re-evaluated with §4)

| Option | Fits the measured load? | Fidelity status | What OB0018 contributes |
|---|---|---|---|
| 1. Deterministic decomposition only | yes: text files ≤ 241 KB always fit a unit | four measured loss mechanisms remain | arm A tests it |
| 2. + mechanical consistency pass (§C-2/-3/-4 invariants) | yes | detectable inconsistencies addressed | arm B tests it |
| 3. + bounded selective re-read | yes; cost ∝ boundaries (358 at 600 KB) | cross-unit judgments by one reader | arm C tests it |
| 4. Hierarchical synthesis | yes | untested | not tested (out of scope) |

**Provenance invariant (all options):**
- Decomposition may reduce load; it may not alter the historical object.
- Every child result stays traceable: **SOURCE → PAGE (hash) → UNIT RECORD → SYNTHESIS FIELD**.
- Any content change caused by decomposition is **recorded as a finding** (as the pilot did), never absorbed.

## 6. Recommended minimum experiment (proposal; not executed; needs a pre-registration)

**Keep brief §D unchanged in substance.** Apply only these amendments, each justified by evidence:

| Amendment | Evidence | Kind |
|---|---|---|
| **M-a** Inherit the **decomposition-pilot integrity regime**: reader page ledger, hash re-computation, production verifier READ-COVERAGE on relabelled copies, namespace isolation, seal-aware reader. **Do not** adopt the V1.x exact-command allowlist with an immediate §18 stop | pilot: 0 protocol violations across its agents (G-LOG-0053); V1.x exact-command regime: allowlist breaches in 3 of 13 agent runs, and harness texts invalidated runs (G-LOG-0076) | integrity design (human decision D-2) |
| **M-b** Separate four outcome levels and **never collapse them**: (1) engineering feasibility (every required page read); (2) extraction fidelity (quotes exact, schema clean); (3) semantic fidelity (per-field auditor dispositions vs baseline); (4) reconstruction fidelity (label-level resolution equality plus the four failure-mode metrics) | the pilot's A/B/C levels; this instruction | reporting |
| **M-c** Freeze the **failure-mode bounds numerically** before execution (e.g. 0 unresolved invariant conflicts; 100% edge endpoints valid; 0 BASELINE-UPHELD adjacency classes) | brief §D gives examples only | pre-registration |
| **M-d** The co-label edge rule (brief §I-5) is undecided, so **report metric (iv) under both readings** and keep the arm decision independent of it, unless the human decides it first | brief §C-4 | analysis |
| **M-e** State the scope: **n = 1 label, 3 units**; the result licenses "evidence for architecture X on few-unit labels", not a population claim | §4 caveat | claim discipline |
| **M-f** Hard stopping rule from the start: **one pre-registration → one independent audit → authorization**. Only a MATERIAL audit finding reopens it | lesson of V1.2.1–V1.2.4 | governance |

**Arm C re-read budget:** keep ≤ 300 KB (brief §D), frozen.

**Auditor:** only Claude-family aliases are demonstrated selectable (V1.2.x §3 observation). Use a same-family auditor, disclosed as a limitation, unless the human supplies a different-family option.

## 7. Provenance and state-machine safeguards

**States, per run and per arm:**

```
DISCOVERED → FROZEN (pre-registration committed, hashes) → AUTHORIZED (human act, G-LOG)
→ DISPATCHED → EXTRACTED (unit records) → VALIDATED (verifier + quotes + READ-COVERAGE)
→ RECONSTRUCTED (synthesis per arm) → AUDITED (blind, frozen) → SEALED (result frozen, reported)
```

**Forbidden transitions:**
- any state → DISPATCHED without AUTHORIZED;
- EXTRACTED → RECONSTRUCTED without VALIDATED;
- FAILED → any success state (a failed unit is recorded, never silently re-run; any re-run needs a new namespace and a human act);
- repair that modifies a frozen record (repairs only add, with diffs);
- a model output becoming evidence without the ledger, verifier and audit chain;
- **any canonicalization, P4+ step or production write during OB0018**;
- OB0018 standing in for the S5 floor (**R19**).

## 8. Possible future ML augmentation (not in OB0018 unless frozen deterministically)

- **§C-3 calibration grouping needs a similarity rule to pair near-identical passages across units.**
  - For OB0018 it must be **deterministic and frozen**: normalized exact quote or anchor matching, or MinHash with a fixed seed and threshold.
  - Pairs are **candidates** for one adjudicating agent; similarity never decides equivalence.
- **Later, after adjudicated S5 data exist:**
  - retrieval of candidate duplicates and near-identical passages across units;
  - anomaly flags (calibration-rate divergence between units, as in the pilot: 4/4 vs 1/8 vs 11/21);
  - uncertainty-ordered review of synthesis fields;
  - always with a random audit sample (Horvitz–Thompson) over the unreviewed remainder.
- **Decision-relevance rule:** introduce ML only where a possible outcome changes a pending decision or measurably lowers review cost.

## 9. Explicit forbidden shortcuts

`SOURCE → ML → THEORY` · `SOURCE → similarity → CANONICAL` · `ML confidence → truth` · `embedding similarity → identity` · `graph structure → historical fact` · `OB0018 result → S5 floor skipped for any label` (R19) · `one-label result → population claim` · `descriptive E0 observations → instrument evidence`.

## 10. Human decisions still required

1. **D-1:** approve running the §D experiment with amendments M-a…M-f, or amend or decline it (brief §I-1).
2. **D-2:** the integrity regime. Pilot-style outcome-based integrity (M-a, recommended on evidence) **or** the V1.x exact-command allowlist.
3. **D-3:** the auditor: same-family (disclosed) or a supplied different-family option.
4. **D-4 (optional before the run):** the co-label edge rule (brief §I-5). If undecided, dual reporting (M-d).
5. **After D-1…D-3:** I draft the pre-registration and runbook; one independent audit; then **authorization of execution** (G-LOG).

**Not needed for OB0018:** the binary policy (the control has no binary file), byte-bounded paging (control pages ≤ 25 KB) and the production schema-4 revision (the pilot's schema-4 delta is authorized for the pilot only). These remain prerequisites for **S5 production**, not for OB0018.

## 11. Updated critical path toward S5

```
O1 closed (G-LOG-0077)
→ D-1…D-3 (human) → OB0018 pre-registration → one audit → authorization → OB0018 execution
→ S5 load-architecture ruling (brief §H) + binary policy + paging revision + schema revision (human)
→ S5 authorization → S5 floor: 396 batches, 1,975 labels (≈ 90% single-unit; 86 heavy labels decomposed)
→ S5a/S5b → P3b terminal → P4 → P5 → P6 → P7
→ mathematical reconstruction → DDD reconstruction → theory recovery → adversarial testing → canonicalization
```

**OB0018 READINESS COMPLETE — NO OB0018 EXECUTION — HUMAN AUTHORIZATION REQUIRED**
