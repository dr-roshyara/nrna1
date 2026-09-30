---
task: KSME-20 (P3a Reliability Diagnosis, per user commission)
scope: corpus-wide chronological-read pipeline (P3a), NOT Track-A-only
derived_from: [P3A-QUALITY-GATE-MEMO.md, highstakes_agreement_report.json, _highstakes_answer_key.json,
  ledger/AH0001-4/verdicts.jsonl, ledger/PV_batch1-4/validation.jsonl, P3A-PRIMARY-EVIDENCE-VALIDATION-REPORT.md,
  P3A-IMPLEMENTATION-CONFORMANCE-AUDIT.md, STAGE1-EMPIRICAL-PROBE.md, 3 KSME-20 forks]
---

# KSME-20 — P3a Quality-Gate Reproduction

## 1. Selection criterion and agreement definitions (reproduced exactly, not paraphrased loosely)

**"High-stakes" slice** = all pairs whose original P3a `relationship` verdict was one of exactly three
values: **SAME (45) + REPLACEMENT (19) + DERIVED-FROM (94) = 158 pairs (8.8% of 1,793)**. Rationale (only
operational, no separate written justification exists): these three assert the strongest positive claims
(full identity / full supersession / direct derivation) and are exactly the verdicts P3b's own roll-up
rules use to trigger consequential governance flags.

**Exact agreement**: original `relationship` string == independently re-derived `relationship` string.
**33/158 = 20.9%** (reproduced exactly).

**Coarse agreement**: both verdicts bucketed into `{SAME, REFINEMENT, EXTENSION, SPECIALIZATION,
DERIVED-FROM, CONTINUATION, REDEFINITION, REPLACEMENT}` ("some positive relationship") vs. `{UNWITNESSED,
INDEPENDENT}` ("none established"), HOMONYM held out separately. **119/158 = 75.3%** (reproduced exactly).

**Memo's own recommendation**: "PASS WITH EXPLICIT LIMITATIONS" — resume P3b for the 2,237 labels with no
dependency on the 158 disputed pairs; hold the 260 touched labels PROVISIONAL pending re-adjudication or an
explicit accept-with-limitation decision. Not a blanket halt; not unconditional continuation.

## 2. Confusion matrix (real counts, cross-verified against all 158 rows + all 4 AH ledgers, exact 1:1 match)

| Original ↓ / Independent → | SAME | CONT. | EXT. | SPEC. | REFIN. | REDEF. | UNWIT. | INDEP. | HOMO. | DERIVED | agree | n |
|---|--:|--:|--:|--:|--:|--:|--:|--:|--:|--:|--:|--:|
| **SAME** | 11 | 21 | 4 | 3 | – | – | 5 | – | – | 1 | 11 | 45 |
| **REPLACEMENT** | – | 5 | 1 | – | 3 | 3 | 3 | – | – | – | 4 | 19 |
| **DERIVED-FROM** | – | 28 | 10 | 5 | 2 | – | 27 | 2 | 2 | 18 | 18 | 94 |

Confidence of the 125 disagreements: HIGH 68, MEDIUM 55, LOW 2 — mostly confidently held on both sides, not
hedged guesses.

## 3. Cause classification of 20 sampled disagreements (7-cause taxonomy, per commission)

| Cause | Count | Example |
|---|--:|---|
| (2) Ambiguous ontology — enum doesn't cleanly apply | 11/20 (55%) | RP0001, RP0149, RP0210, RP0625, RP0423, RP0526, RP0132, RP0694, RP1401, RP1047, RP0440 |
| (3) Insufficient evidence | 3/20 | RP0107, RP0629, RP0379 |
| (7) Genuine judgment call, both defensible | 2/20 | RP0059, RP0496 |
| (5) UNWITNESSED used incorrectly | 1/20 | RP0098 |
| (6) Inconsistent adjudication procedure | 1/20 | RP0647 (independent applied an undocumented rule) |
| (1) Bad reconciliation decision | 1/20 | RP0140 (ignored the source's own governance act keeping objects separate) |
| (4) Type/scope mismatch | 1/20 | RP0235 |

Dominant soft boundaries: SAME↔CONTINUATION, DERIVED-FROM↔CONTINUATION, DERIVED-FROM↔EXTENSION,
DERIVED-FROM↔SPECIALIZATION, REPLACEMENT↔REDEFINITION.

**Important correction found during classification, not to be silently absorbed**: RP0440 was initially
classified by hand-reading as cause (7), but `STAGE1-EMPIRICAL-PROBE.md`'s own deeper 4-case dive shows both
reviewers cited *identical* source rows (S0167/S0171/S0176) and still split DERIVED-FROM vs. INDEPENDENT —
Stage 1 judges neither reviewer wrong; the real relationship ("operationally connected but architecturally
independent") simply has no slot in the 11-value enum. Reclassified to cause (2).

## 4. What the two separate validation exercises found, and how they complicate the memo

- **`P3A-PRIMARY-EVIDENCE-VALIDATION-REPORT.md`** validates a *different* population: 1,104 PRIMARY-sourced
  candidate pairs P3a's own pair-generation script never even constituted. On n=100: 91% verbatim/faithful
  citations, 86% explicit-or-strong-implicit real relationship — **the corpus has the evidence; the
  pipeline never surfaced it.** Of 13 sampled pairs that already existed as P3a pairs, only 4/13 = 30.8%
  agreed — an independent corroboration of the ~21-31% agreement band via a different sample and method.
  Of 6 sampled UNWITNESSED pairs, 6/6 were found to have a real relationship on direct primary-source
  reading. **This report also found and fixed a bug in its own measurement tool that had mis-resolved
  RP0526's cited target file** — meaning the two "independent" UNWITNESSED reads the quality-gate memo
  reports for RP0526 were unknowingly working from the same wrong file this later report corrected. RP0526
  is therefore weaker corroborating evidence than the memo presents it as, not a clean second confirmation.

- **`P3A-IMPLEMENTATION-CONFORMANCE-AUDIT.md`** is a mechanistic root-cause audit of the pipeline code
  itself, reproduced via 8 synthetic fixtures recreating RP0526/RP0288's failure shape from first
  principles. **Central finding: `derive_reconciliation.py`'s `row_brief()` strips `dependencies[]`,
  `lineage_claims[]`, `invariants[]`, `assumptions[]`, and file-level PRIMARY/SECONDARY-SYNTHESIS
  provenance from every row before any P3a reviewer — original or independent — ever sees it**, and
  candidate-pair generation only does exact-string label matching (no source-ID resolution, no
  substring/paraphrase matching). Root-cause split: predominantly Category B (implementation defect); a
  real but secondary Category A (ontology-coverage gap, ~7%, confirmed on two independent samples);
  near-zero Category C (0/100 NOT_FOUND — the corpus itself is not the problem); a minority Category D
  (agent misjudgment, e.g. RP1376: "Corrects EA4's formula" wrongly called SAME). **Explicit conclusion:
  "No change to the Master Protocol document itself is indicated... changes would be implementation-only."**

**These two reports corroborate the memo's headline number across three independent methods
(~20-31% agreement) but shift its interpretation**: the memo frames the problem as ontology softness and
evaluator judgment; the later reports show a large share of the apparent "disagreement" is a fixable
upstream data-visibility bug — decisive structured evidence exists and was captured at P1, but is
mechanically discarded before any reviewer sees it. **This is a direct, corpus-internal confirmation of the
user's own hypothesis that disputed relationships were evaluated without the full evidence/derivation
context available to them** (see the Track-Provenance Audit for the additional cross-track dimension).

## 5. Concentration vs. distribution

- **By batch**: broadly distributed. Every one of the 30 P3a production batches contributed multiple
  disagreements; per-batch agreement ranges 0-50% with no batch near-total agreement (except one n=1 batch).
- **By relationship tier**: real skew — DERIVED-FROM is measurably weakest (19.1% exact agreement, and the
  only tier where ~33% of disagreements go to "no relationship at all"), vs. SAME (24.4%) and REPLACEMENT
  (21.1%), which mostly downgrade to a *milder* positive relationship. Even the best tier (SAME) still
  disagrees 76% of the time.
- **By object family/topic**: no thematic clustering found — governance, formal/mathematical, philosophical
  and meta-epistemic objects all appear among the sample with no concentration.

**Conclusion: broadly distributed across batches and families; mild concentration in DERIVED-FROM as a
categorically weaker tier than SAME/REPLACEMENT.**

## 6. Bottom line

**Not a case requiring ontology redesign.** Dominated by one precisely located, already-diagnosed
implementation defect (`row_brief()` stripping structured evidence before reconciliation), with a real but
bounded ontology-coverage gap (~7-10%, recurring across independent samples) and a small residual of
genuine evaluator disagreement (per the 20-pair sample: 3 insufficient-evidence + 2 judgment-call + 1
procedural + 1 outright-bad-decision = 7/20, vs. 11/20 ontology/data-visibility related once RP0440 is
reclassified). See `KSME-20-FORK-DECISION.md` for the GREEN/YELLOW/RED gate classification this supports.
