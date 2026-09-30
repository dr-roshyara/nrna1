# exp01-evidence-aggregation-operators

**Scope(s):** OBJECT · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** BAYES_LIKE, MAX, PIPE, RAW, SATURATING, WEIGHTED_MEAN · **Aliases:** EXP-01 aggregation operators
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0032, scope OBJECT): Four candidate scalar operators (MAX, weighted mean, noisy-OR/SATURATING, naive-Bayes-style BAYES_LIKE) for aggregating evidence-item strengths into a single epistemic scalar, evaluated under RAW (no preprocessing) vs PIPE (dependency-collapse plus relevance-filter before aggregation) semantics against eight designed properties (A-H) from an earlier design (20260827-134514) and verdict (20260827-135038); GN-27 adjudicated a CSV-vs-verdict discrepancy, and GN-46 is an independent recheck reproducing that reasoning without modifying either artifact.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1316] §"SATURATING     A       FAIL   PASS   PASS      PASS  
SATURATING     B       PASS   PASS   PASS      PASS  
...
SATURATING     G       FAIL   PASS   PASS      PASS"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1480. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1476, S1480 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1316, S1326, S1476, S1480 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1316, S1480 |
| experiments | PRESENT | S1316, S1326, S1476 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
[S1476] (EXPLANATION): Lists items described-as-executable but never run: EXP-01 criteria D/E/I/J plus calibration (a design exists but was never executed); EG-05 self-verification suites (only SPECIFIED); SNF v0.2/v0.3, praised as exemplary because the corpus explicitly declined to invent numbers rather than fabricate results; the SNF v0.4 calibration proposal; and the 025a-1 prototype, whose execution did not complete, honestly recorded as such rather than claimed successful.

[S1480] (EXPLANATION/WARNING): Enumerates what is only claimed, never verified: the 136+ conceptual-PASS claims cross-referenced to plan 07 sections B/C; every K_t/S_t tuple-adequacy claim; step 025k's closure claims; unratified algebras from steps 025f/g/h beyond their already-proven fragments; SNF performance; 'complete specification' claims which the corpus itself has asserted, then denied, then reinstated; and the 8-primitive sufficiency claim, targeted by derivation chain D-1.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S1316] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "Under RAW semantics, SATURATING and BAYES_LIKE both fail duplicate invariance (A), dependency awareness (F), and irrelevance (G); MAX and WEIGHTED_MEAN also fail F and G under RAW, and WEIGHTED_MEAN additionally fails A under RAW. Under PIPE semantics, all four operators pass A, F, G, and H (temporal validity, a store-level property, N/A as a raw operator property). All four pass order invariance (C) and boundedness under either semantics. Only WEIGHTED_MEAN(naive) fails associativity (D); a state-carrying (sum,count) form of the mean is noted as associative instead. No operator passes contradiction preservation (E): every scalar operator's netted output conflates a strong contradiction (0.9 vs 0.9, netted 0.000) with a weak one (0.1 vs 0.1, netted 0.000)." (anchor: "SATURATING     A       FAIL   PASS   PASS      PASS  
SATURATING     B       PASS   PASS   PASS      PASS  
...
SATURATING     G       FAIL   PASS   PASS      PASS")
- [S1316] types=['EXPERIMENTAL-RESULT', 'WARNING'] scope=OBJECT — "Concrete calibration demonstration: SATURATING (noisy-OR) applied to 10 independent weak items each with strength 0.3 produces 0.9718 -- a number in [0,1] that resembles a high-confidence probability but has no underlying probability space, likelihood, or calibration behind it, illustrated as 'a number pretending to be probability'. Separately, BAYES_LIKE applied to a 3-item evidence chain (api, llm1, llm2) where llm1 and llm2 both depend on api but are treated as independent evidence yields 0.9846, versus the correct single-source value of 0.8000 obtained by treating only the root api observation -- a concrete demonstration of severe overconfidence from double-counting dependent evidence." (anchor: "SATURATING over 10 independent weak items (s=0.3): 0.9718
  -> a '0.97' with no probability space, no likelihood, no calibration:
     exactly the design's 'number pretending to be probability'.")
- [S1326] types=['VALIDATION', 'EXPERIMENTAL-RESULT'] scope=OBJECT — "The EXP-01 independent recheck (detailed in S1315/S1316) confirms the historical negative verdict (no scalar in [0,1] can retain a support/counter-support pair) and shows it is in fact provable; duplicate/dependency safety is a property of the normalization pipeline, not of any raw operator. The CSV-vs-verdict discrepancies are re-derived (not merely re-read): the historical CSV mixes raw-operator and normalized-pipeline semantics across cells, exactly matching a prior adjudication's (GN-27) description of it as 'inconsistent under any single reading' -- that adjudication stands and is independently corroborated, with the missing associativity outcomes added. Separately, the executed seven-column matrix alone does NOT entail the published negative conclusion, since SATURATING passes all seven executed columns; the conclusion follows only from the matrix plus a narratively-argued contradiction criterion (E), a pre-operator dependency/duplicate requirement, and a calibration caution -- all of which the historical record itself states, so the experiment is judged honestly represented even though 'the matrix established it' would be a false characterization that nothing audited actually asserts." (anchor: "EXP-01 independently rechecked (§14): the historical negative verdict is CONFIRMED — and is in fact provable ... PF-4 determination (§14): the executed 7-column matrix alone does NOT entail the published conclusion")
- [S1476] types=['EXPERIMENTAL-RESULT'] scope=THEORY-LEVEL — "Declares the complete corpus-wide list of actually-executed empirical artifacts to be exactly four: (1) exp01_recheck.py, which ran the four aggregation operators under RAW/PIPE semantics against adversarial cases (100x duplicates, dependence chains, contradiction, boundedness, order, associativity) plus calibration demonstrations, establishing operator formula behavior and raw-level failures but NOT system-level replication, true calibration, or operator selection; (2) zero_reference.py, which ran source-level Zero over a worked example (step 025d) with tests T1-T8, a scalar-collapse counterexample, and an Insufficient-via-Gamma case, establishing Zero computability relative to evaluators on tested inputs but not evaluator semantics themselves, eta, or status-set closure; (3) ladder_dc_reference.py, which executed I-12 no-skip, the A6 boundary (a 10^6-evidence inertness test), Decision-Contract conjunction/no-averaging/Unknown-to-Block behavior, an Omega_A expressibility probe, and governed exits, establishing that the ratified boundary semantics execute as specified and demonstrating both the Accepted-and-Contest inexpressibility and an MV-F-5 mismatch, but not ladder dynamics, adjudication, or any full system realization; (4) knowledgeos_evidence_calculus_property_tests.csv, the historical EXP-01 property matrix (7 executed columns), establishing only the historical run's recorded outcomes with the adjudicated caveat (GN-27, independently corroborated) that the CSV mixes semantics, and explicitly NOT establishing matrix-only sufficiency of the negative verdict (MV-F-16) or criteria D/E/I/J/calibration." (anchor: "A. Actually executed (the complete corpus-wide list -- 4 artifacts) | exp01_recheck.py ... | zero_reference.py ... | ladder_dc_reference.py ... | knowledgeos_evidence_calculus_property_tests.csv ...")
- [S1476] types=['EXPLANATION'] scope=THEORY-LEVEL — "Lists items described-as-executable but never run: EXP-01 criteria D/E/I/J plus calibration (a design exists but was never executed); EG-05 self-verification suites (only SPECIFIED); SNF v0.2/v0.3, praised as exemplary because the corpus explicitly declined to invent numbers rather than fabricate results; the SNF v0.4 calibration proposal; and the 025a-1 prototype, whose execution did not complete, honestly recorded as such rather than claimed successful." (anchor: "C. Described-as-executable, never run EXP-01 criteria D/E/I/J + calibration (design exists) - EG-05 self-verification suites (SPECIFIED) - SNF v0.2/v0.3 (corpus explicitly declined to invent the numbers -- exemplary) - SNF v0.4 calibration proposal - 025a-1 prototype (execution did not complete, honestly recorded).")
- [S1480] types=['EXPLANATION', 'WARNING'] scope=THEORY-LEVEL — "Enumerates what is only claimed, never verified: the 136+ conceptual-PASS claims cross-referenced to plan 07 sections B/C; every K_t/S_t tuple-adequacy claim; step 025k's closure claims; unratified algebras from steps 025f/g/h beyond their already-proven fragments; SNF performance; 'complete specification' claims which the corpus itself has asserted, then denied, then reinstated; and the 8-primitive sufficiency claim, targeted by derivation chain D-1." (anchor: "B. What is only claimed Everything in section B/section C of plan 07: 136+ conceptual-PASS claims - all K_t/S_t tuple adequacy claims - closure claims (025k) - unratified algebras (025f/g/h beyond their proven fragments) - SNF performance - "complete specification" claims (asserted-denied-reinstated) - the 8-primitive sufficiency claim (D-1 target).")

## Notes for P3
- No internal tension, evidentiary anomaly, or lifecycle-flag discrepancy observed in this label's own rows beyond what the completeness roll-up above already shows.
