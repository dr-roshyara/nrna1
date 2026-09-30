# C09 — Statistical / ML analysis · v1.1

**Treatment:** F-SPECIFIC; checkpoint research only (human choice). Inherits unchanged: v3.5 R9 (repetition is not
confirmation), P3B §13.10a (pre-registration of any test), P3B §9E.2/§9E.3 principles (controls; independence and
recurrence at every scale); evidence is counted by function and provenance, not by mentions (v3.5 R9, B4).

## 1. Per file
STATISTICAL-ML is `DEFERRED-TO-CHECKPOINT`, unless the file itself makes a statistical or probabilistic claim. That
claim is then analysed per C06 (`review_flag: STAT-QUESTION` at Level 1, a CORRECTNESS-FINDING at Level 2 if
warranted), with the lens APPLIED.

## 2. At a checkpoint
Population: the inventory and ledgers of all AUDITED F-IDs, with exact duplicates collapsed and CONTENT-IDENTICAL-TO-S
files marked. Two modes:
- **EXPLORATORY** (descriptive; labelled so; never evidence for a hypothesis): term frequencies by file, definition
  counts per term, co-occurrence of terms within files, term-key graph metrics, clustering of files by inventory
  profile.
- **CONFIRMATORY** (only for a pre-registered hypothesis, C11): a test fixed in advance (population, statistic,
  comparison or control, decision rule, stopping rule). The test is out-of-sample when it uses files AUDITED after
  `registered_after_f`.

## 3. Admitted methods (FD-13)
Counts and proportions with exact denominators · rank/permutation tests · bootstrap intervals · Jaccard / cosine
similarity over inventory term vectors · hierarchical clustering with a stated linkage · embedding similarity only if
the model, version and preprocessing are recorded and the result is reproducible. Anything else needs a protocol
version.

## 4. Output
`checkpoints/CP-##/STATS/` — each analysis states the population, the method, the parameters, the seed if any, the
result, and the mode. No per-file statistics are written as findings.
