# 1q: the transition-frame experiment on five rulings-register rows

| | |
|---|---|
| Status | research record; not canonical; authority: none |
| Spec | `FRAME-1Q/SPEC.json`, frozen before any sampled row was read (`e772eb53d`) |
| Source | `ADR-AIP-LOG-Platform-Rulings.md` (`7795c14b…`), rows L33, L39, L52, L67 and L77 only |
| Instrument | `frame_fit.py` (`3534445e…`), selftest 4/4 including a mutation test; M0 (`m0_check.py`) is imported read-only and unchanged |
| Result | `FRAME-1Q/RESULT.json` (`4e3eb44f…`) |
| Log | F-LOG-0122 |

**Selection caveat.** Rows were sampled *because* they contain frame phrases, so the sample is not representative. Everything below says "in the sample", never "in the register".

## 1. Sample and why
- Sampling used a frozen rule over two strata: rows using the explicit template (R-81, R-91) and rows before it (R-47, R-53, R-66).
- Rows already used were excluded: R-37, R-39, R-41, R-88, R-90 and R-100.
- Locate-level fact: the "WHAT IT CHANGES / WHAT IT DELIBERATELY DOES NOT CHANGE" template appears in every row from R-81 to R-91, and in no earlier row.

## 2. Operations and frames observed (SOURCE-FACT)

| Row | Operations as named | Changes | Explicitly preserved / non-effects | Guard stated in the source |
|---|---|---|---|---|
| R-47 | authorize execution (slice 7A) | authorization of 7A; execution responsibility passes to engineering | "7B and 7C are NOT authorized" · "7A is inert" · a methodology module "remains PROPOSED … silence is not adoption (R-34)" | "WP-6 ACCEPTED ∧ WP-7 PLAN APPROVED", satisfied by R-43 ∧ R-46 |
| R-53 | annotate; record reproduced counter-evidence | a forward-pointer annotation on R-43; counter-evidence recorded | "AN ANNOTATION, NOT AN AMENDMENT; R-43's decision text is unchanged" · "the WP-6 acceptance STANDS" · FALSE vs UNSUPPORTED "remains UNDETERMINED" | annotation is permitted where decision text and history are not (ES-004.3) |
| R-66 | accept slice 7C; close WP-7; open WP-7B-R1 | acceptance; WP-7 CLOSED | "how deletion is performed is unchanged" · bounded-context ownership, context map, PL, UL and invariants unchanged · WP-8 "requires formal definition and authorization" · release gated on C-2 | "all eight R-65 requirements satisfied" |
| R-81 | authorize a repair slice; lift a freeze **for this repair only** | authorization of one slice; freeze lifted within that scope | "adopts no crash model" · "does not widen R-76" · "does not choose the mechanism" · the rest of Batch 7 stays frozen · decision text unchanged | evidence: a defect "under crash models A, B and C alike" |
| R-91 | determine on submitted evidence; later HELD | evidence CORRECT; reachability LATENT; status HELD | "no repair is authorized" · decisions "SOUND AND UNCHANGED", not reopened · crash-model set "NOT extended" · "HELD, not withdrawn … its number is not retired" | "Event D separates evidence submission from constitutional review" |

**Frame structure** means at least one stated change and at least one stated preservation or non-effect for the same act. All **5 of 5** sampled rows have it, and so does R-88. Every row also states its **guard** explicitly. That is new: M0 treated guards as a model assumption, but the rulings write them down.

## 3. Frame fit against M0 (MODEL-DERIVED; the mapping to M0 operations is INTERPRETATION)

| Row | M0 operation | Fit |
|---|---|---|
| R-47 | — | NOT-MODELLED (AUTHORIZE is missing) |
| R-53 | CONTRA | **PARTIAL-FIT.** Counter-evidence is recorded and **standing is preserved**, exactly as M0's CONTRA frame predicts. The annotation layer is not in M0 |
| R-66 | GOV-CLOSE | PARTIAL-FIT: governance closure fits; acceptance and "work opened" are not in M0 |
| R-81 | REOPEN | **PARTIAL-FIT with an expressiveness gap.** The source lifts the freeze *for one scope*, while M0's `regime` is global |
| R-91 | — | NOT-MODELLED (DETERMINE and HOLD are missing) |

- **No frame contradiction.** Where M0 models the operation, no stated change falls outside its frame.
- **The first real-data check of a modelled prediction:** counter-evidence does not by itself lower standing. R-53 is a direct instance ("the WP-6 acceptance STANDS"); R-91 is an analogue ("evidence about the IMPLEMENTATION, not about the decision").
- **Verdict on M0:** *not contradicted, under-specified*.
- **M0's vocabulary gaps** are AUTHORIZE, ANNOTATE, ACCEPT, CLOSE/OPEN-WORK, DETERMINE, HOLD and SUBDIVIDE (R-88).
- **Its two expressiveness gaps:**
  - freeze must be **scope-indexed** (R-81);
  - counter-evidence must be **target-indexed**, decision vs implementation (R-91).

## 4. What the non-effects are (candidate typing; not decided from these cases)
- **Invariants (10):** the recurring invariants are:
  - **decision text is immutable; only annotations are added** (R-53, R-81, R-91, and R-88: **4 rows, the strongest recurring invariant**);
  - silence is not adoption;
  - evidence about realization is not evidence about the decision.
- **Relations (6):** authorization is **scope-bounded**: R-47 (7B/7C not authorized), R-66 (WP-8 needs its own authorization), R-81 (this repair only), R-91 (repair not authorized), R-88 (neither package authorized). **5 rows.** Architecture governance vs execution governance is another relation (R-91).
- **State coordinates (2):** acceptance stands (R-53); HELD vs WITHDRAWN (R-91).
- **Guards (1):** release gated on C-2 (R-66).

## 5. Path dependence (SOURCE plus computation)
- **R-91 vs R-90:** both are "not adopted", but their futures differ. R-90 was WITHDRAWN and its number RETIRED; R-91 is HELD, its number kept, and it can still be adopted after review.
- Computation: "adopted?" alone is **not** a sufficient state. A 5-valued status {unused, prepared, adopted, held, withdrawn} **is** sufficient (Markov over all traces up to depth 5).
- This is a second witness that the history that matters enters as a **finite status/registry summary**. R-81, R-88 and R-91 say explicitly that adoption creates no delegation, which is anti-path-dependence of authority. Nothing yet requires unbounded history.

## 6. Retrieval (ML-relevant, measured)
- Lexical precision of the frame-phrase classes on the sample is **19/20**. The one miss was a "neither" used for guards.
- Count-term precision (F-LOG-0120) was **0/1**.
- Frame language is lexically well-marked; evidence counts are not. **No ML is needed for frame retrieval.**
- Recall is unmeasured: there is no gold set.

## 7. Is M1 justified?
- **Yes, in a minimal form, and only as a pre-registered hypothesis to be tested on unread rows.** Candidate additions, each SOURCE-OBSERVED in two or more rows:
  - a ruling-status coordinate {PREPARED, ADOPTED, HELD, WITHDRAWN} with an identifier registry;
  - authorization as a **scope-indexed relation**, not a coordinate;
  - a scope-indexed freeze;
  - target-indexed counter-evidence;
  - the **two-layer record invariant**: decision text immutable, annotations append-only;
  - the operations AUTHORIZE, ANNOTATE, ACCEPT, CLOSE/OPEN, DETERMINE, HOLD, SUBDIVIDE.
- **Not justified:** any change to the guard theory (G-R/G-K/G-O/G-E remain unresolved), and any fixed dimension set.

## 8. Highest-information next observation
- **Pre-register M1 with falsifiable predictions, then test them on the unread template-stratum rows: R-82 to R-87 and R-89 (7 rows).**
- These are an independent test set whose frames are explicit.
- Predictions to freeze:
  - P1: no row amends decision text other than by annotation;
  - P2: every authorization stated is scope-bounded;
  - P3: every status is in {PREPARED, ADOPTED, HELD, WITHDRAWN};
  - P4: no stated change falls outside M1's frame for its operation;
  - P5: no row lets counter-evidence change standing without a separate act.
- A single violation falsifies the corresponding M1 element. That is a real test, unlike the development fits so far.
