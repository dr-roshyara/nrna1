# Retrieval benchmark (RB-1) — DESIGN ONLY (results-free; not run; no model trained)

| | |
|---|---|
| **Kind** | experiment design. ⚠ authority: generated. **Execution requires a separate pre-registration freeze and an L0 corpus release** |
| **Purpose** | measure how well computational retrieval surfaces the historical passages that careful human/reader reading found, for the **open semantic questions**. ML is an evidence-retrieval **accelerator**, never a truth classifier and never an input to theory decisions |
| **Motivating fact** | in T-A, the author-reader missed the R-39 pointer that both blind readers found (F-LOG-0049). Retrieval false negatives are real and measurable |

## 1. Information needs (queries; written from the semantic questions, never from corpus text)

| Id | Need | Linked open question |
|---|---|---|
| Q1 | promotion / adoption of knowledge | H-6, Gate 2 |
| Q2 | exception to a promotion rule | R-39, Gate 2 |
| Q3 | eligibility / qualification criteria | A6, Gate 2 |
| Q4 | evidence thresholds (counts, contexts, "repeated evidence") | OBS-SF-1 |
| Q5 | revalidation / re-assessment after promotion | OQ-T2, OQ-T4 |
| Q6 | revocation / demotion | OQ-T2, A2e (P-3) |
| Q7 | grandfathering / pending items under a rule change | A6, OQ-T3 |
| Q8 | retroactivity | A6, OQ-T3 |
| Q9 | later evidence (confirmation, amendment evidence) | OQ-T4 |
| Q10 | a promotion **event** (an act) vs a promotion **state** | H-6 |
| Q11 | authorization / approving authority | OQ-T1 |
| Q12 | rule changes and their scope | A6, OQ-T3 |

For each need, a **frozen query set** (lexical terms plus natural-language descriptions) is written before any retrieval run and hashed.

## 2. Gold standard (never produced by the retrieval system)

- **Source of labels:** only **sealed** human/reader evidence records (the T-A SELF, BLIND-SECONDARY and INDEPENDENT ledgers; the R-39 readers; future sealed readings), mapped to Q1–Q12 by an explicit, frozen mapping table.
- **Unit:** a passage anchor (file + section/line span). A retrieved chunk counts as a hit if it overlaps a gold anchor (anchor recall).
- **Hard subset:** passages on which readers disagreed, or which only some readers found (e.g. R-39). This is the adversarial test set.
- **Leakage guards:**
  - queries must not quote gold passages;
  - no training on gold labels without a train/test split by **source file**;
  - no system output is ever promoted into the gold set without a new sealed human reading.

## 3. Retrieval ladder (each rung frozen: version, parameters, chunking, depth)

1. exact lexical match;
2. BM25 (fixed tokenizer, k1, b);
3. structural / metadata retrieval (headings, register tables, ruling ids, commit metadata);
4. dense embeddings (a named model and version; fixed chunk size and overlap);
5. hybrid (fixed fusion, e.g. reciprocal-rank fusion with a fixed constant);
6. optional cross-encoder or LLM reranking (named model and version; top-k only; **never classifies relevance for the gold set**).

## 4. Metrics (per need, per rung; reported separately, never combined into one score)

- **Recall@5, Recall@10, Recall@20**; **MRR**; **false-negative rate** at k = 20; **evidence-anchor recall**; precision@k (secondary).
- **Hard-subset recall**, reported separately.
- **Uncertainty:** passage-level bootstrap intervals (fixed seed, fixed number of resamples). No significance claims beyond the pre-registered comparisons.
- **Cost:** passages a human must read per gold hit.

## 5. Pre-registered comparisons (to be frozen before execution)

- Each rung vs lexical baseline on Recall@10 and the hard subset.
- Whether any rung recovers the R-39-type misses (recorded misses of the author-reader).
- **No rung's output is evidence.** Every candidate goes to a complete human/reader reading before it can enter a ledger.

## 6. Preconditions for execution

1. freeze this design as a pre-registration (queries, mapping, parameters, seeds);
2. an L0 corpus release covering the indexed corpus;
3. the gold set sealed from existing ledgers **before** any retrieval run.

## 7. Clarification (2026-09-26, human correction; before any freeze)

- The gold set is a **known-relevant evidence set**, not complete corpus relevance. Passages that no sealed reading found are **unlabelled, not irrelevant**.
- So **Recall@k measures recovery of sealed known-relevant anchors** only. Precision@k and "false positives" are therefore lower bounds on true precision, and are reported only as secondary.
- **Hard positives and hard negatives** (retrieved passages outside the gold set) are labelled later, only by **blind human adjudication** under a separate pre-registration. They never come from system output or an LLM judge.

## 8. Refinements (2026-09-26; design only; before any freeze)

- **Positive-unlabelled (PU) framing.**
  - Known: P = sealed known-relevant anchors. Unknown: N.
  - **Primary:** Recall@k over P.
  - **Secondary:** MRR, anchor recall, rank distribution, cost per gold hit, source diversity (number of distinct source files in the top-k), hard-case recall.
  - Precision and F1 appear only after blind human adjudication produces P* (confirmed relevant) and N* (confirmed irrelevant).
- **Narrow first scope.**
  - The first run indexes only the corpus slice released by L0 for the R-39 semantic family (Q1–Q12).
  - Expansion happens only if the per-need recall shows a missing semantic family, and that expansion needs its own pre-registration.
- **Downstream measures (later; only once human-adjudicated labels exist):**
  - calibration (ECE, Brier) of any uncertainty ranking;
  - review-hours per correctly adjudicated case.
  - The objective is reliable adjudications per unit of human review cost, not model accuracy.

## 9. Retrieval architecture for the evidence phase (design only; not run)

| Level | Retriever | Frozen parameters (at pre-registration) |
|---|---|---|
| 0 | exact identifiers and anchors (ids, section numbers, ruling ids) | the identifier list |
| 1 | lexical / BM25 | tokenizer, k1, b |
| 2 | metadata and structural (headings, tables, dates, commit metadata) | the field weights |
| 3 | dense embeddings | the model name and version; chunk size and overlap |
| 4 | hybrid (reciprocal-rank fusion) | the fusion constant |
| 5 | optional reranker | the model and version; top-k only |

- **Unit of retrieval:** a passage that maps onto an evidence-cell anchor (EVIDENCE-SCHEMA §3). Queries are derived from the EP claims (`propositions.json`), never from corpus text.
- **Metrics** (per level and per EQ; never combined):
  - Recall@k and coverage over the **sealed known-relevant anchors** (primary);
  - the false-negative rate at k;
  - precision@k, a **lower bound** under PU labelling (secondary);
  - latency (median and p95);
  - human reading cost per recovered anchor.
- **Boundary:** a retrieved passage is a **candidate**. It becomes evidence only as a HISTORICAL-EVIDENCE cell written by a human or controlled reader and validated by `derive_constraints.py`. ML never sets `assessment`, never decides truth, axiom support, model status, proof or canonical status, and never adds to the gold set.

## 10. The benchmark is an incremental ablation (design frozen here; not run)

- **Rungs R0 → R1 → R2 → R3 → R4 → R5** (§9 Levels 0–5): exact identifiers → BM25 → structure / metadata → embeddings → hybrid (RRF) → reranker.
- Each rung is measured **against the previous rung**, on the same sealed known-relevant anchors and the same frozen queries.
- **Embeddings (R3) are evaluated only after R1/R2 baselines exist. R5 is run only if R4 shows a measurable Recall@k gain over R2.**
- The optimization target is **Recall@k first**: in this phase a false negative (a missed passage) is costlier than a false positive (an extra passage for a human to read).
- Report per rung: Recall@k, coverage, false-negative rate (primary); precision@k as a PU lower bound, latency, human review cost (secondary).
- The gold set is **only** sealed known-relevant anchors from human / controlled readings. It is never LLM-generated. Retrieved passages never acquire evidence status except as HISTORICAL-EVIDENCE cells validated by the engine.

## 11. Lexical-baseline observations (from F-SEARCH-06, F-LOG-0086; design input only)

- **Candidate precision problem:** of 134 line-level candidates, **77 (57%) came from brainstorming transcripts** (non-decisional). A source-kind filter is part of the R2 (structure / metadata) rung, not a later add-on.
- **Dimension saturation:** long table rows satisfy every co-occurrence dimension. Passages must be **segmented below the line** (cell or sentence) before any rung comparison; otherwise R1 precision is overstated and R3 gains are unmeasurable.
- **Absent mechanism:** "automatic" never co-occurs on a candidate line. A retrieval target that lexical search cannot express is exactly where an R3 test (does semantic retrieval find automatic-invalidation language that lexical search misses?) would be informative. **This is not run.**
