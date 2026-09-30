# 1ae: T-min v1.1 held-out test (extended vocabulary and operation-specific evidence bars)

| | |
|---|---|
| Status | research record. Not canonical. Authority: none |
| Pre-registration | `prompts/KNOWLEDGEOS-T-MIN-V1.1-HELDOUT-PREREGISTRATION.md`, frozen with `SAMPLE.json` at `2dfe9eea2` **before** any held-out row was read |
| Held-out sample | 13 rows from positions 16–28 of the same seeded order: R-31, R-36, R-38, R-46, R-49, R-50, R-55, R-58, R-61, R-67, R-73, R-92, R-93 (dates 07-08 … 08-04). None was used to build v1.1 |
| Reserve | 13 rows left untouched |
| Result | `RESULT.json` (`6b90e7eb…`) |
| Log | F-LOG-0136 |

## 1. The main test, P-COV (pre-set threshold: coverage ≥ 0.70)

| | Covered / acts | Estimate [Wilson 95%] |
|---|---|---|
| **v1.1** | **17/24** | **0.71 [0.51, 0.85]** |
| v1 (baseline) | 11/24 | 0.46 [0.28, 0.65] |

- **P-COV: SUPPORTED, but only just.** The point estimate (0.708) clears the threshold. The interval is wide, and its lower bound is below 0.70.
- The three new operations frozen in advance, **ADOPT-DECISION** (R-36, R-73, R-92), **APPROVE** (R-46, R-49) and **DEFER** (R-49), all recurred in unseen rows. The extension generalized **for those operations**.
- **Still not modelled:** RENAME, the refusal verb "NOT promoted" (absent from the frozen REJECT synonyms), DEPRECATE, ASSESS, and **creating or recording queue items** (3 occurrences, now the most frequent gap).
- v1's own coverage differs between the two samples (0.29 in 1ab, 0.46 here). The act mix varies from sample to sample, so a single sample is not a stable estimate.

## 2. The other predictions (v1.1 guards)

| Prediction | Result | Estimate [95%] |
|---|---|---|
| V1′ legality | 12/12 | 1.00 [0.76, 1.00] |
| V2′ four-act separation | 4/4 | 1.00 [0.51, 1.00] |
| V3′ frames | 12/12 | 1.00 [0.76, 1.00] |
| V4′ evidence persistence | 6/6 | 1.00 [0.61, 1.00] |
| V5′ reopening | 0 applicable | untestable |
| **V6′ authority named, R-43 onward** | **10/10** | 1.00 [0.72, 1.00] |
| V7′ supersession | 0 applicable | untestable |

- There are **no violations**.
- Before R-43 (descriptive only), 2 of 3 rows name their authority; R-31 names none. That is consistent with the regime effect found in 1ab.
- The **corrected ACCEPT frame** (acceptance closes the work) is confirmed on unseen rows: R-55 "ACCEPTED AND CLOSED", R-67 "WP-3A IS CLOSED", R-93 "WP-4C-1 closed".

## 3. A new evidence-only minimal pair (secondary; not pre-registered)
**R-36** promotes six behaviours "evidence-based (PB-004 · PB-005 · PB-006)". In the same ruling, a candidate is "Expressly NOT promoted: Registration ≠ Delivery stays Messaging-scoped … **(1 slice)**".
- Same operation (RAISE), same route (a ruling / review adoption), same authority (ARB), same kind (an engineering behaviour), same state.
- The evidence count differs (≥ 2 vs 1), and so does the outcome.
- Re-running the T-min legality analysis with this pair makes **evidence NECESSARY**. It was undetermined until now.
- **Caveat:** the non-promotion also gives a *scope* reason ("stays Messaging-scoped"), so the ground is partly scope and partly count.
- The pair makes the evidence bar on RAISE the best-supported evidence claim. It still does **not** decide route vs operation (G-R vs G-O): there is no route contrast.

## 4. Other held-out source facts (one occurrence each unless noted)
- **Evidence bar on concept creation:** "every additional concept should now have to justify itself through operational evidence" (R-38). This is the **fourth** occurrence, with R-64, R-72 and R-80, and it is consistent with v1.1's CREATE-NORM bar.
- **The operation determines the authority:** "EP-01 plan approval is the Decision Authority's act" (R-46).
- **"an approval admits a proposal while an acceptance admits delivered work"** (R-55): the APPROVE ≠ ACCEPT split added in v1.1, stated by the source itself.
- **Pre-registration inside the corpus:** "All three outcomes recorded IN ADVANCE as legitimate findings … reproduction impossible … itself a finding" (R-50).
- **Explicit guard verification:** "Guards verified: planning approved (R-56) · plan corrected (R-57) · …" (R-58). **No inference:** "P7B-3 withdrawn (it was an inference …)".
- **Role separation:** "keeps evidence collection separate from repair" (R-49).
- **Act-type-specific status, third witness:** a Chief *adoption of a review* carries no PREPARED marker (R-92).
- **A same-day self-correction** re-types R-38 from a freeze to "a consolidation/assessment ruling, not a sixth freeze". This is consistent with I-B4′.
- **Partial acceptance, borderline:** R-93 accepts D1 and D2 and closes WP-4C-1, with D3 and D4 "NOT ACCEPTED BECAUSE NOT OFFERED … separately governed" (HELD in R-97).
  - This is consistent with R-68's rule ("the parent closed only when every sub-slice was disposed of") only if *held / separately governed* counts as disposed.
  - Recorded as an observation, not a violation.

## 5. Verdict and next
- **v1.1 survives its held-out test.** Coverage is 0.71, just above the threshold, and no guard or frame was violated.
- The best-supported findings now are:
  - factored state;
  - the four-act separation;
  - authority, kind, prior state **and evidence** as legality variables;
  - operation-specific frames, including ACCEPT closing work;
  - operation-specific evidence bars;
  - the two-layer record;
  - finite history summaries.
- **Still open:**
  - route vs operation (undecidable here by corpus design);
  - the conformance variable (fitted);
  - the stability of coverage (wide intervals).
- **Next options:**
  1. **Final reserve test** of v1.1 on the 13 reserve rows. Pre-register first, adding QUEUE-ITEM and the refusal synonyms only as a separately scored v1.2 variant.
  2. **Gate 1 inter-coder agreement** on the 28 coded sample rows: a non-Claude coder, Cohen's κ.
  3. **Stop corpus exposure here** and write the candidate theory up for review.
