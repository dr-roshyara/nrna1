# S-Series next research architecture (analysis and proposals only)

**Kind:** research-architecture analysis.

**This document does not modify V1.2.x, P3b, v3.5 or Architecture v1.2.** Nothing here is authorized or executed, and ML is **not** part of any pre-registered V1.2.x run.

## 1. The layered pipeline (layers never merged)

```
SOURCE
 → DETERMINISTIC EXTRACTION      (paged reader, segmentation, hashes, identity manifest)
 → MODEL EXTRACTION              (LLM extractors under pre-registered prompts and allowlists)
 → COMPUTATIONAL DERIVATION      (deterministic scripts: counts, joins, equalization, κ, capture)
 → ML DIAGNOSTICS                (read-only over the layers above; writes to its own store)
 → HUMAN ADJUDICATION            (the only layer that turns a candidate into a decision)
 → RECONSTRUCTION                (v3.5 P1–P3: layer A records; P3b roll-up floor)
 → THEORY RECOVERY               (v3.5 P7 / Architecture v1.2 Phase 2A)
 → THEORY ATTACK / VALIDATION    (pre-registered tests, falsification; v1.2 2C / v3.5 P5 evidence vector)
 → CANONICALIZATION              (governance act only; v3.5 P6/GATE, v1.2 Layer 5)
```

**Relationship to the governing documents:**
- the chain above is v3.5 + P3b as they stand;
- ML diagnostics sit where P3b §26.7 puts implementation-layer machinery;
- nothing here decides Decision A.

## 2. What ML may and may not do

| ML may | ML must not |
|---|---|
| classify (proposition type, relation type) | silently become evidence |
| cluster; find near-duplicates | overwrite or correct a source claim |
| flag possible omissions (items with no close neighbour in another source) | decide historical identity (v3.5 R5/R16) |
| estimate uncertainty | canonicalize a concept |
| propose relations (contradiction, refinement, specialization, derivation) | replace human adjudication |
| prioritize the human review queue | turn similarity into semantic truth |
| learn from adjudicated examples | train on its own generated labels |

**Mandatory provenance for every computational or ML object:**
- input hashes;
- model and version;
- algorithm;
- parameters;
- seed (where applicable);
- timestamp;
- output hash;
- **human disposition** (`PENDING` / `ACCEPTED` / `REJECTED` / `MODIFIED`, with adjudicator and date).

**Diagnostic layer rules:**
- ML objects live in a separate store (e.g. `ml-diagnostics/<run>/`) and are never written into layer-A records or the research register;
- a diagnostic enters reconstruction only through a human adjudication record citing both the diagnostic and the source evidence.

## 3. Efficiency research: measurements (no invented numbers)

**Known S-lane scale:** S5 has 396 batches and 1,975 labels (66 hubs); P2 has 2,497 labels. Every other quantity is to be measured.

**Target:** epistemic reliability per unit of human review and computation, reported as separate curves, never as one score.

| Quantity | How it is measured | First available from |
|---|---|---|
| compute cost per page | tokens and wall time per page from harness transcripts | a valid V1.2.3 run |
| extraction cost per label | the same, aggregated per label | the V1.2.3 run; later S5 |
| duplicate / near-duplicate rate | embedding similarity within and across extractor outputs; a human-verified sample to calibrate | post-result diagnostic on V1.2.3 outputs |
| disagreement rate | κ decision units; M1/M2 disagreement share; extractor-pair non-overlap | V1.2.3 |
| uncertainty distribution | the distribution of the composite uncertainty score (§4, experiment 6) over items | the diagnostic layer |
| human review minutes | timed adjudication sessions (logged start/end per item) | the adjudication experiment (5) |
| errors discovered per review minute | adjudicated errors ÷ review minutes, per queue ordering | experiments 5 and 6 |
| candidate-generation cost | tokens per accepted candidate | a strategy experiment (after F-14) |
| verification cost | tokens and minutes per verified finding | the same |

## 4. Next experiments (separate, each pre-registered; none executed)

**1. Extraction completeness (multi-source capture–recapture)**
- **Design:** k ≥ 3 genuinely diverse sources over the same fixed files. Diversity means different model families where available, different prompting (segment inventory vs open-ended vs question-driven), and a human extractor on a subsample.
- **Estimator:** log-linear capture–recapture models with source-dependence terms (e.g. Rasch-type or heterogeneity models), reported as a **lower bound with an interval**.
- **Explicit assumptions:** a closed population (fixed files); the matching rule; source dependence modelled, not assumed away. It cannot see items that no source can perceive.

**2. Extraction reliability / reproducibility**
- **Design:** repeat each extractor r ≥ 3 times with identical inputs and prompts.
- **Measures:** within-extractor agreement across repeats, set-overlap distributions, and variance of counts.
- **Output:** reproducibility intervals per extractor.

**3. Semantic-equivalence correctness**
- **Design:** a human-adjudicated gold set of item pairs (stratified by matcher decision), with at least two adjudicators and their agreement reported.
- **Measures:** matcher precision/recall against gold, **not only κ**.

**4. Relation classification**
- **Design:** adjudicated pairs drawn from P3a/P3b-style relations (SAME, REFINEMENT, REPLACEMENT, CONTRADICTION, …).
- **Measures:** confusion matrices for a candidate classifier (NLI or rules) versus adjudication; the classifier proposes only.

**5. Human adjudication efficiency**
- **Design:** timed adjudication on a fixed item set; inter-adjudicator agreement; an error-injection subsample to measure detection.
- **Measures:** minutes per item and detection rate.

**6. ML-assisted review efficiency**
- **Design:** the same adjudicators and item pool, randomized between uncertainty-ordered and random review queues.
- **Measures:** errors found per minute in each arm.
- **Data discipline:**
  - disjoint **training / test / adjudication** sets;
  - a **held-out audit sample** never used for training or tuning;
  - **no model trained on its own labels**;
  - retraining only on human-adjudicated data, with versioned snapshots.

## 5. Research-goal check

| | |
|---|---|
| **A. What V1.2.x is trying to establish** | whether two extraction instruments (a segment-level proposition inventory, and a blinded two-matcher procedure) are reliable enough, on six fixed files, to be used as Phase-1 extraction instrumentation: mechanical segment coverage, quote fidelity, matcher agreement and relative capture |
| **B. What it cannot establish** | corpus-wide or label-wide completeness; semantic correctness; matching correctness; model independence; generalization beyond six files; any strategy conclusion; any change to v3.5/P3b obligations; anything about theory |
| **C. What evidence reconstruction must establish** | v3.5 P1–P3 complete: every object label with evidence-backed statuses, inspected births, a settled layer, resolved absences (or ESCALATED with scope, e.g. the 66 hubs), typed source-supported edges, and the research register. That is, the P3b terminal predicate (§23) and the P4 hand-over, under R0/R2/R19 |
| **D. What theory recovery must establish** | from the completed reconstruction: the theory the corpus actually developed (recovered), what can be synthesized, and what must be added with explicit labels, followed by pre-registered attack and validation. Canonicalization only by a governance act |
| **E. What the final research goal requires** | an auditable, reproducible chain from source to reconstructed history to recovered or constructed theory, where every claim is traceable, epistemic statuses are never collapsed, and every construction is justified mathematically or architecturally and exposed to falsification |

**The ultimate objective:** reconstruct what the historical KnowledgeOS corpus actually developed into, then determine, through auditable, reproducible and defensible research, what structure can legitimately be recovered or constructed. **V1.2.x does not achieve this.**

**Remaining distance, as testable milestones:**
1. **V1.2.3:** a new independent audit, then a human authorization, then a valid run with a recorded instrument outcome.
2. **F-14 formalized** (augmentation vs substitution), plus Decision A (architecture governance).
3. **The route back to S5:** load handling, then the OB0018 decomposition-fidelity result, then S5 authorization.
4. **The S5 reconstruction floor** completed across 396 batches, with the P3b terminal predicate met (hubs ESCALATED with claim scope; H-02 resolved for Tier X).
5. **S5a/S5b passes;** then `30-RECONCILIATION.md` and the **P4 hand-over.**
6. **P4 membership → P5 validation vector → P6 governance vector.**
7. **P7 synthesis:** "RECONSTRUCTED, PENDING GOVERNANCE REVIEW".
8. **Theory attack and validation** under pre-registration.
9. **A governance act for any canonicalization.**

**In parallel, and optionally:** the six experiments of §4 to raise reliability per unit of review.

## 6. Addendum (2026-09-26, after hardening closed): governing principle and concrete methods

**Governing principle:**
> **Every experiment must either reduce uncertainty about the corpus or reduce the cost of obtaining reliable evidence.**

**Immutable boundary:**
- never SOURCE → ML → THEORY;
- never SOURCE → similarity → CANONICAL.

**The roadmap** E0–E11 is in `.claude/S-SERIES-TODO.md`. Its critical path is **E0 → return to S5 (E9) → P3b terminal/P4 (E10) → P5–P7 (E11)**. Everything else is augmentation (R19).

**Concrete methods.** These are proposals. Each needs its own pre-registration.

| Question | Method | Why this method | Failure mode guarded against |
|---|---|---|---|
| **completeness** (E2) | log-linear capture–recapture (Fienberg): source-pair interaction terms; heterogeneity via Rasch/M_h models; model selection by AIC, with the full set reported | two-source Lincoln–Petersen assumes independence, which same-vendor LLMs violate | positive source dependence → underestimate; so report a **lower bound** plus a sensitivity band |
| **proportions** (coverage, quote fidelity, precision) | Wilson intervals; cluster bootstrap by file (resampling the 6 files) | small n; items within a file are correlated | narrow naive intervals |
| **agreement** | Cohen's κ **plus** prevalence and bias indices (PABAK), and Gwet's AC1 as a sensitivity check | κ paradox under skewed prevalence | a high-agreement / low-κ misreading |
| **false-negative rate of a filter or diagnostic** | a random audit sample with known inclusion probabilities → **Horvitz–Thompson** estimate of misses among *un-flagged* items | ML-ordered queues never examine the low-score tail | survivorship bias in "errors found" |
| **uncertainty for ML candidates** (E5) | calibrated scores (isotonic/Platt on the adjudicated set only); **split-conformal** prediction sets with a stated coverage level | gives distribution-free coverage guarantees on exchangeable data | overconfident similarity scores treated as truth |
| **review prioritization** (E6) | randomized arms (uncertainty-ordered vs random); primary outcome errors per minute; plus the HT audit of the unreviewed remainder | only randomization identifies the causal effect of ordering | confounding by adjudicator fatigue or learning (counterbalanced order) |
| **active learning** | uncertainty sampling on human-adjudicated labels only; frozen held-out test set; versioned snapshots | reduces labelling cost | self-training on model labels (prohibited); test leakage |
| **near-duplicates / omissions** | sentence embeddings + MinHash/LSH as **candidates**; mutual-nearest-neighbour absence across sources as an omission flag | cheap recall-oriented candidate generation | similarity ≠ identity (v3.5 R5/R16): every merge is human-adjudicated |
| **structure diagnostics** (pre-theory) | typed-edge graph over *adjudicated* relations: cycle detection in derivation edges, contradiction-pair components, degree outliers → review queue | makes structural inconsistency visible early | graph metrics promoted to theory (prohibited) |

**Provenance** for every row: §2 (input hashes, model and version, algorithm, parameters, seed, output hash, human disposition).

**What E0 alone licenses:** only the V1.2.x §22 instrument verdict. No completeness, strategy or theory claim.
