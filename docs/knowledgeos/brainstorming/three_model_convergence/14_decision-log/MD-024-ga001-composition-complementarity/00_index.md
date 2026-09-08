# MD-024 — GA-001 Composition and Complementarity Study

**Status: COMPLETE.** Executed under separate, explicit authorization (2026-09-08), with two
methodological corrections to MD-023's own wording applied throughout (not editing MD-023's frozen
text — recorded here only): "no canonicalization criterion within the tested criterion set was found"
(not "cannot be resolved by further scientific analysis alone"); MD-023's non-convergence finding holds
"within the tested diagnostic sample" (not universally across every untested pair). **Not Stage 07.
Not Phase 5O.**

## Scientific purpose

MD-023 reframed GA-001 into an untested hypothesis: that Model B's operator-oriented structures and
Models A/C1/C2's aggregate-oriented structures might be complementary DDD layers (Aggregate/Entity vs.
Domain-Service) rather than rival Kernel candidates. This study's sole purpose: **determine whether
Model B's operator structures can be coherently composed with A/C1/C2's aggregate structures, without
changing either side's semantics, invariants, or identity.**

## Pre-execution finding, disclosed before any composition test (per the study's own required
first-action discipline)

A raw-source check was performed on M0030 (the document that specifies C0's 13 operators and
explicitly requests a formal Input/Output/Precondition/Postcondition contract for each) and M0035 (the
document Model B's own register cites as the confirmed execution of M0030's protocol). **M0030's own
template asks for exactly this contract; M0035, the actual execution record, does not contain it** —
no operator in Model B's own legitimate 151-file evidence base has a stated formal input/output type
anywhere in the corpus. A more rigorous operator-contract apparatus (`ASSERT`/`LINK`/`REVISE`/
`RETRACT`/`ISOLATE`, with real preconditions/postconditions over a proposition graph) does exist in the
math lane (`20260902-182025_review-exceptionally-sharp-rigorous.md`, M0185, `canonical_source` M0184)
— but it is tagged `KR-SIM`, not `b`, and is therefore **outside Model B's own reconstructed evidence
base by Phase 2's own authorization and MD-020's standing rule**; it is not consulted as B's own
content anywhere in this study. **This confirms, rather than merely predicts, that most composition
attempts below will be constrained by the absence of a stated operator input type on B's own side** —
disclosed here as a finding from evidence, not assumed before testing.

## Frozen, read-only inputs

`00_control/protocol.md`; the complete MD-023 artifact set (10 files, read in full); `02_model-a_gita/`,
`03_model-b_mathematical/`, `04_model-c_kernel-ddd/`, `05_cross-model/`, Phase 6, `06_gap-analysis/`.
Phase 5A–5N, the handover, and MD-022 are an external frozen boundary, not touched. The P-series is not
consulted.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_composition-candidate-register.md` | Full enumeration of composable structures on both sides. |
| `02_pre_registered-selection.md` | Selection criteria, documented before testing; the 4 selected pairs. |
| `03_formal-composition-tests.md` | The mathematical composition test (domain/codomain/state space/closure/invariant-preservation) for each pair. |
| `04_ddd-composition-analysis.md` | The DDD-level analysis (aggregate vs. operator fields) for each pair. |
| `05_invariant-and-state-transition-analysis.md` | Whether operators preserve aggregate invariants, where testable. |
| `06_conflictrecord-analysis.md` | The dedicated `ConflictRecord` diagnostic case. |
| `07_composition-failure-analysis.md` | Exact obstructions for every non-composed pair. |
| `08_complementarity-adjudication.md` | H1–H4 verdict for the core hypothesis. |
| `09_adversarial-falsification.md` | Falsifiers for every positive and negative finding. |
| `10_resolution-status.md` | The required resolution matrix. |
| `11_verification-and-completion-report.md` | Verification suite; final report. |

## What this study does NOT do

Does not enter Stage 07. Does not select a model, canonicalize any Kernel or `K_t`, or declare identity/
formal equivalence from a successful composition. Does not reconstruct missing operator specifications
(the KR-SIM-tagged `ASSERT`/`LINK`/etc. apparatus is named as existing but is never imported as B's own
content). Does not touch Phase 5A–5N, the handover, or MD-022. Does not reopen GA-038's canonicalization
question. Does not treat 4 tested pairs as a statistical sample.
