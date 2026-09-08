# MD-029 — Controlled Specification-Sufficiency and Composition Retest (MD-024 Pair 1 only)

**Status: COMPLETE.** ONE diagnostic experiment: **B `Validate` ↔ C1 P-3**, retested using exactly the
two files admitted by MD-028-DQ-1 (`04-operator-contracts.md`, `03-capability-model.md`), plus C1's
own already-admissible source (seq 0157, the P-3 falsification record). Not Pair 2/3/4. Not a general
theory. Not Stage 07.

## Pre-execution finding, disclosed before the test (raw-source check performed)

Both admitted files were checked directly. **04's own general contract convention** ("Input =
carriers required by some derivation rule; Output = the derived carrier") **is not self-contained** —
the concrete derivation rules mapping specific input-carrier combinations to specific outputs live in
`06-composition-rules.md`, which is **not** admitted by MD-028-DQ-1. **03's own capability table**
(admitted) does supply Validate's OUTPUT carrier directly (`Verdict`, capability C10, kind "artifact")
— genuinely more than MD-024 had. **Validate's exact INPUT carrier set is therefore `NOT SPECIFIED BY
SOURCE` within the admitted scope** — this is disclosed here, before the test, not discovered as a
surprise partway through.

## A second finding, from re-reading P-3's own raw source directly (seq 0157)

**Correction to this reconstruction's own prior characterization** (recorded here, not edited into
MD-023/024's or Phase 4's own frozen text): those documents described P-3's falsification as tested
*"via a many-to-many evidence-sharing stress test."* **Seq 0157's own raw text contains no such
scenario.** The actual method is a **pairwise transactional-atomicity argument** (each of the six
proposed properties tested pairwise for whether semantic relatedness/traceability implies co-location
necessity) — a structured analytical argument, not an executed stress test in the computational sense.
The falsification conclusion itself (*"the six-part invariant... does NOT require one aggregate"*)
stands, verified directly; only the *method description* inherited from earlier phases is corrected.

## Frozen inputs

MD-028 (including `13_recorded-decision.md`), MD-027, MD-026, MD-025, MD-024, MD-023, Stage 06, Phase
1–6, Phase 5A–5N, the handover, MD-022 — external boundary, not touched. The two admitted files (read
in full this study). Seq 0157 (`kernel/20260823-114530-aggregate-hypothesis-falsification-atomicity-
vs-relatedness.md`, already-admissible C1 evidence, read in full this study). No other
`kernel-reduction/` file, no `theory-v1.1-simulation/`, no P-series.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_authorization-and-scope.md` | What MD-028-DQ-1 permits; what this study does not do. |
| `02_validate-source-contract.md` | Validate's exact source-stated contract; what is/isn't specified. |
| `03_p3-source-contract.md` | P-3's exact contract, re-verified against seq 0157 directly. |
| `04_composition-definition.md` | The formal composition attempt. |
| `05_semantic-preservation-test.md` | Preservation testing, where testable. |
| `06_ddd-analysis.md` | Lexical/structural/functional correspondence vs. actual command binding. |
| `07_mathematical-adjudication.md` | Classification on the six-level ladder. |
| `08_provenance-and-evidence-ledger.md` | Full source-to-claim traceability. |
| `09_adversarial-falsification.md` | The 10 required self-attacks. |
| `10_resolution-status.md` | Final classification and smallest next action. |
| `11_verification-and-completion-report.md` | Verification suite; final report. |

## What this study does NOT do

Does not test Pair 2, 3, or 4. Does not select a model. Does not establish a common Kernel. Does not
reopen GA-038 or K-1/K-2. Does not open Stage 07. Does not admit any further file. Does not upgrade
provenance. Does not canonicalize anything.
