---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-11-TERM-DISCOVERY-REGISTRY]
derived_from: [all KSME-12 fork reports]
cross_track_dependency: none
---

# KSME-12 — Term Discovery Registry (extends KSME-11's registry; does not overwrite it)

This registry is additive to `KSME-11-TERM-DISCOVERY-REGISTRY.md`. Terms already registered there
(`K_t`, `Qualify`, `G1`, `𝒪_K`, `258.9`, `=`, `Contr`, SSA, `KR-STATE-02`, etc.) are not repeated here
unless a new relationship or status change was found this pass.

| Term | First discovered | Timestamp | Defined? | Source | Status | Same-day | Cross-midnight | Thread |
|---|---|---|---|---|---|---|---|---|
| `≅_I` (context-bounded reference identity) | `step-288/04` §12/14 | 2026-08-31 | Y | `I_48` | Established, narrow-scope | Completed | Completed | Congruence |
| `≅_P` (provenance-sameness) | `step-288/04` | 2026-08-31 | N (blocked — λ unbound) | primary | Blocked | Completed | Completed | Congruence |
| `∼_H` ("same history") | `step-288/04` | 2026-08-31 | N (blocked — 𝒯 open) | `258.12`/`260` | Blocked | Completed | Completed | Congruence |
| `∼_F` (fold equality) | `step-288/04` | 2026-08-31 | Y (decidable), N (adequate) | `25I.31`, `258.11` | Decidable-but-insufficient | Completed | Completed | Congruence |
| `q:K→K/≡` quotient audit (0 of 8 properties) | `step-288/04` §13 | 2026-08-31 | Y (scored) | primary | Not-established | Completed | Completed | Quotient |
| `G-108` (`Kernel_engineering≠Kernel_epistemic`) | file 37 | 2026-09-01 12:56 | Y (hypothesis) | primary | `[HP]`, 0.7% protocol execution, unadopted | Completed | Completed | Kernel-ambiguity |
| `G-110` (`Π`'s 3rd meaning collision) | file 38 | 2026-09-01 22:19 | Y (as collision) | primary | Documentary | Completed | Completed | Notation |
| `MD-006` (kernel C1/C2 split) | quoted via file 37, ultimate source `three_model_convergence` | 2026-09-01 | Y (hypothesis) | **SOURCE-CLAIMED-VIA-CITATION — never independently verified, `three_model_convergence/` never opened by this investigation** | `[HP]` | Completed | Completed | Kernel-ambiguity (cross-track citation) |
| Sañjaya layer / capability | `20260826-151244` | 2026-08-26 | Y (Reality→StateKnowledge) | primary | Established | Completed | Completed | Observation-layer |
| `W --Ω--> O` | `20260826-160322` | 2026-08-26 | Y (typed, no body) | primary | Established (typing); not-established (executable) | Completed | Completed | Observation-layer |
| `I-O` invariant (Observation≠Interpretation) | `REFINED-STEP-287-INVARIANTS.md` | 2026-08-31 | Y | primary | Conditionally-derived, testable | Completed | Completed | Observation-layer |
| `H-K16` (Sañjaya=observation layer) | `R5-RESEARCH-TO-ARCHITECTURE-REPORT.md` | 2026-08-31 | Y | primary | Established | Completed | Completed | Observation-layer |
| `𝒩∉O` (Knower not in Observation) | R5/I-O | 2026-08-31 | Y | primary | Established, ties to `H-K03` | Completed | Completed | Observation-layer |
| `A+¬A` (conflict state) | Step 32 §32.33 | 2026-08-28 | Y | primary | Established | Completed | Completed | Contradiction-as-state |
| `ConflictStatus` | Step 32 §32.35 | 2026-08-28 | Y | primary | Established | Completed | Completed | Contradiction-as-state |
| `Conflict` (algebra element, role unclear) | Step 32 §32.76 | 2026-08-28 | N | primary | Unresolved | Completed | Completed | Contradiction-as-state |
| `Conflict(p)` | Step 60 §60.5 | 2026-08-28 | Y | primary | Established | Completed | Completed | Contradiction-as-state |
| `Support⁺/Support⁻` | Step 60 §60.10 | 2026-08-28 | Y | primary | Established | Completed | Completed | Contradiction-as-state |
| "Merge ≠ Resolve" | Step 60 §60.22 | 2026-08-28 | Y | primary | Established | Completed | Completed | Contradiction-as-state |
| Operational/Epistemic state split | Step 60 §60.73 | 2026-08-28 | Y | primary | Established | Completed | Completed | Architecture (cross-cutting) |
| `KR-CONTR-EVAL`, Candidates A-D | `theory-08` §1-2 | 2026-09-04 (predecessors 2026-09-02) | Y | mathematical_ideas | `[EXP]` executed | Completed | Completed | Contradiction-as-guard (source lane) |
| `Contr≠Satisfied` | `theory-08` §1 | 2026-09-04 | Y | mathematical_ideas | `[PRP]` proposed | Completed | Completed | Contradiction-as-guard |
| `DECISION-02` (φ frame vs. partition) | `theory-08` §5 | 2026-09-04 | N (open) | mathematical_ideas | Open, blocking δ and composability | Completed | Completed | δ-construction (NEW dependency KSME-11 missed) |
| Layered model `X→Y_t→E_t→ℱ_t→Π_t→K_t` | file 38 | 2026-09-01 22:19 | Y (proposed) | primary | `[H]`, unadopted | Completed | Completed | Qualify/G1-adjacent |
| `§6b`'s rule ("never merge") | `REFINED-STEP-286` | pre-2026-09-01 | Y | primary | Established discipline, reused ≥2× | Completed | Completed | Methodology |
| `Terminus`/qualification-terminus hypothesis | `REFINED-STEP-286 §6` | pre-Sep-1 | Y | primary | `[H]` | Completed | Completed | Qualify/G1 |
| `Acknowledgment` hypothesis | `REFINED-STEP-286 §6b` | pre-Sep-1 | Y | primary | `[H]`, not adopted | Completed | Completed | Qualify/G1 |
| `AP-1` (authority principle) | `03-D285-PROTOCOL`/`18-GITA-CANDIDATES` | earlier | Y | primary | Resolved, implemented (132/132) | Completed | Completed | Authority (distinct, solved) |
| "authority regress" (analogy only) | `09-GAP-UPDATE-FROM-CAVELL`, prompt `20260831-211057` | 2026-08-31 | N (not a formal gap) | primary | Analogical citation of `AP-1`, not an independent instance | Completed | Completed | Qualify/G1 (borrowed analogy) |
| `GK-09`/`GK-16` (Sañjaya numbering collision) | `07-GK-REGISTRY-RECONCILIATION.md` | 2026-08-31 | — | primary | Registry-ID artifact, not conceptual | Completed | Completed | Numbering-collision |

## Pending (explicitly not resolved this pass)

`00-INDEX.md` (133KB, `phase_measure_theory/knowledgeos_kernel/research/`) not read directly (its content
overlaps file 38, read at the same timestamp). `step-292/05,06,09` not re-mined line-by-line for
additional terms beyond what KSME-11 and this pass's Fork 3 already extracted (time-boxed). Files 01-24,
26-29, 32-36 in the numbered research lane were read via `REFINED-STEP-286.md`'s verbatim-quoting
synthesis, not the raw files directly — judged faithful given quote density, but not identical to direct
reading. `theory-08`'s own predecessor files (`20260902-155427`/`161500`/`170000`) not opened line-by-line.
None of these gaps changed any verdict in this pass.
