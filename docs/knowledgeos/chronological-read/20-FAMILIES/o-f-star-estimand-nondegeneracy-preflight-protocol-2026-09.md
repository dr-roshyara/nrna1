# o-f-star-estimand-nondegeneracy-preflight-protocol-2026-09

**Scope(s):** METHODOLOGICAL · **Row count:** 4 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Hypothesis→Estimand→O-F*→Population→Calibration→Control→Freeze→Execution→Adjudication`, `O-F*` · **Aliases:** `Estimand Non-Degeneracy Preflight`
**Candidate group membership (NOT an identity claim):**
- **G0834**: [`k9-qualification-degeneracy-source` · `o-f-star-estimand-nondegeneracy-preflight-protocol-2026-09` · `of-star-corrected-to-require-admissible-witness`] — labels share the notation 'O-F*'

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0066`, scope `METHODOLOGICAL`: A frozen (2026-09-05) experimental-protocol addition requiring every declared-variable quantity to have a pre-execution variation witness under the frozen computation ('class reachability does not imply estimand variability'), run before the freeze step so a failing estimand can still legitimately be rewritten; accompanied by two named research-governance safeguards (observed diagnostic does not license a new primary estimand; observed convergence does not license theory promotion) evidenced by near-miss artifacts in KR-ZOOM-03 and KR-ZOOM-OUT-01, and later shown (in the registry, S2754) to itself fail retrospectively on KR-ZOOM-OUT-03.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2754 §"KR-ZOOM-OUT-03-CALIBRATED-CONTEXT-RETURN-2026-09 ... PRINCIPAL RESULT: O-F insufficient — class reachability ⇏ estimand variability."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2755 §"Hypothesis → Estimand → O-F* → Population → Calibration → Control → Freeze → Execution → Adjudication"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2755 §"Hypothesis → Estimand → O-F* → Population → Calibration → Control → Freeze → Execution → Adjudication"]

## Lifecycle
last_seen: S2760. Candidate lifecycle: **CONTESTED**. Evidence: no structured retraction/supersession/contradiction evidence recorded for this lifecycle value.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2755 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2755 |
| experiments | PRESENT | S2754 |
| open_questions | PRESENT | S2760 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2754] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "FROZEN, GATE MET, EXECUTED 2026-09-05: primary Delta_loss is NON-IDENTIFIABLE/DEGENERATE (A5=0 exactly, not merely negligible); the M1 artifact from KR-ZOOM-OUT-01 is eliminated by stratification (0.000); M0 (0.497/0.520) replicates only as a descriptive phenotype with no single winner; FR-004's prediction is not met; and the retrospectively-validated O-F* repair (O-F*) is shown to FAIL on this very experiment — it would not have been authorized had it been checked in advance." (anchor: "KR-ZOOM-OUT-03-CALIBRATED-CONTEXT-RETURN-2026-09 ... PRINCIPAL RESULT: O-F insufficient — class reachability ⇏ estimand variability.")
- [S2755] types=['GOVERNANCE', 'FORMALIZATION'] scope=METHODOLOGICAL — "The experimental protocol frozen 2026-09-05, introducing O-F* (Estimand Non-Degeneracy Preflight): every declared-variable quantity must have a pre-execution variation witness under the frozen computation because 'class reachability does not imply estimand variability'; it runs before the freeze because its failure is grounds to rewrite the estimand while that is still legitimate." (anchor: "Hypothesis → Estimand → O-F* → Population → Calibration → Control → Freeze → Execution → Adjudication")
- [S2755] types=['GOVERNANCE', 'WARNING'] scope=METHODOLOGICAL — "Two research-governance safeguards (explicitly not theory claims) stated because KR-ZOOM-03's directed diagnostic and KR-ZOOM-OUT-01's conditional-population result both appeared only after the pre-registered primary estimand had already returned a weaker verdict and could not be adopted; calibration must be a separate pass with its own gate, inspected before analysis code runs, because in KR-ZOOM-OUT-01 the null stratum silently became the 'winning' M1 artifact and the calibration-gate failure was invisible until audit." (anchor: "Observed diagnostic ⇏ new primary estimand ... Observed convergence ⇏ theory promotion")
- [S2760] types=['GOVERNANCE', 'FUTURE-RESEARCH'] scope=METHODOLOGICAL — "Recommends KR-BIOCOMM-ZERO-01 as the next artifact — a pre-registration plus synthetic witness generator running the R1-R6 tests on controlled synthetic traces first, biological examples serving only as structural inspiration, explicitly citing the O-F* protocol as part of the required execution order." (anchor: "Do not immediately create another theory document. ... Lens Freeze → Pre-registration → O-F* → Synthetic Witness Generator → Controls → Execution → Adjudication")

## Notes for P3
- The mechanically-computed `lifecycle_candidate` is CONTESTED, but none of this label's own rows is CONTRADICTION-typed and `lifecycle_evidence` shows no retraction/supersession claim either — this is the reviewer's own observation, not something the source data states explicitly. The likely substantive basis (visible in S2754's own text) is that S2754 reports the O-F* protocol itself ("the retrospectively-validated O-F* repair") FAILING on the KR-ZOOM-OUT-03 experiment it was meant to gate — i.e. the object's own row records a case where applying it would not have prevented the very degeneracy it exists to catch. Whether that counts as "contested" in the sense P3 cares about (an unresolved tension in what the object *is*) versus merely "one adverse test result against a working protocol" is a judgment call left open here.
