# Phase 5I — Verification and Completion Report

## Raw-source spot-checks (25 performed; requirement ≥20, covering all three executable scripts)

| # | Category | Claim checked | Raw source | Result |
|---|---|---|---|---|
| 1 | `t285_reconcile.py` | `RATIFIED`/`VERIF`/`ASSERTION_CONTAINS`/`VERIF_EXTERNAL` set literals | full file read this phase | **Faithful**, transcribed exactly |
| 2 | `t285_reconcile.py` | Re-executed `r_only - VERIF_EXTERNAL` = `{Observation, State}` | re-run this phase (`00`) | **Faithful**, confirmed by direct re-execution, not just reading |
| 3 | `t285_reconcile.py` | The `pi` dict's own entry for `Observation`, inconsistent with #2 | full file read | **Faithful** — the intra-script inconsistency confirmed |
| 4 | `t285_reconcile.py` | T-B `verdict()` calls are hardcoded booleans, not computed | full file read | **Faithful** |
| 5 | `t285_equality.py` | `IMAGE`/`TARGET`/`UNPACK` set literals | full file read | **Faithful** |
| 6 | `t285_equality.py` | Test 2 (`UNPACK <= IMAGE`) is a genuine computed subset check | full file read, logic traced | **Faithful** |
| 7 | `t285_equality.py` | Test 3's `QUERIES` dict is hardcoded, not derived | full file read | **Faithful** |
| 8 | `t285_equality.py` | The "WRONG... CORRECT: =_semantic" self-correction | full file read | **Faithful**, verbatim |
| 9 | `e_equality.py` | `A(P,e,c,t,Pi)` constructor, `id=H(...)` derived hash | full file read | **Faithful** |
| 10 | `e_equality.py` | `a_scan`/`a_vendor` worked example, differing only in `Pi` | full file read | **Faithful** |
| 11 | `e_equality.py` | `eq_struct`/`eq_semantic`/`eq_obs`/`eq_prov` are genuinely computed functions | full file read, logic traced | **Faithful** |
| 12 | `e_equality.py` | `QUERIES[1]` is vacuous (self-comparison); only `QUERIES[0]` is ever passed to `eq_obs` | full file read, re-confirmed via targeted grep this phase | **Faithful** — dead code confirmed, not an active defect |
| 13 | `e_equality.py` | E3–E6's own findings (non-injectivity only under a chosen quotient) | full file read | **Faithful** |
| 14 | D285-1 §2 | Assertion unpacking (6 fields) | seq 1006, re-confirmed | **Faithful** |
| 15 | D285-6 §3 | Assertion unpacking (`{Proposition,Entity,Observation}+{id,c,t,Π}`) | seq 1007, re-confirmed | **Faithful** |
| 16 | D285-6 | `Qualify: Observation × Policy → Evidence` (2-argument) | seq 1007, re-confirmed | **Faithful** |
| 17 | seq 0630 | `Evidence = QualifiedObservation` | seq 0630 §49.76, re-confirmed | **Faithful** |
| 18 | seq 0795 | `CaptureAndQualify(O)` (1-argument), named conditions | seq 0795 §170.4, re-confirmed | **Faithful** |
| 19 | Cross-script comparison | `ASSERTION_CONTAINS` (`t285_reconcile.py`) vs. `UNPACK` (`t285_equality.py`) | both files, direct comparison this phase | **Faithful** — confirmed genuinely different sets |
| 20 | Cross-script comparison | Neither script imports or references the other | both files, checked for import statements | **Faithful** — confirmed independent, no shared code |
| 21 | K-1's own primitive set | Re-confirmed stable across seq 0630, D285-1, D285-7 | all three, cross-checked this phase | **Faithful** — no instability found at K-1's own level (confirms the instability is localized to K-2's `Assertion`) |
| 22 | K-2's own top-level structure | `K=(𝒜,ℛ)` stable across all 5 Assertion records | `01` register, cross-checked | **Faithful** — top-level structure undisputed |
| 23 | `Π` denotation | D285-6 glosses `Π` as "Policy"; `e_equality.py`'s worked example populates `Pi` with `"origin:scan"` (provenance-shaped) | seq 1007 + `e_equality.py`, direct comparison | **Faithful** — confirmed genuine discrepancy |
| 24 | `id` field treatment | D285-6 lists `id` as a peer field; `e_equality.py` derives `id` as a hash of the other fields | seq 1007 + `e_equality.py`, direct comparison | **Faithful** — confirmed genuine discrepancy |
| 25 | Step 272A/272B existence | Files exist at expected paths | `find` check this phase | **Faithful**; full content not read (disclosed as a reconstruction gap, `15`) |

**Every correction/finding disclosed in this phase is listed here with its source** — no claim in
`00`–`15` lacks a corresponding spot-check row above or a direct citation to Phase 5H's own already-
verified material.

## Verification suite

- `resume.py` → `CONSISTENT` (unchanged). `resume_mathematical.py` → `CONSISTENT` (unchanged).
- `classification-register.tsv` → 0 non-pending rows, unchanged this phase (pre-existing `M` status
  from before this session's own work, not touched by Phase 5I).
- Model A (5), Model B (5), Phase 3 (5), Phase 4 (5), Phase 5A (4), Phase 5B (7), Phase 5C (7), Phase
  5D (9), Phase 5E (12), Phase 5F (13), Phase 5G (15), Phase 5H (14) — **101 files total** — confirmed
  present and untouched.
- D285-1, D285-6, D285-7, and all three executable scripts (`t285_reconcile.py`, `t285_equality.py`,
  `e_equality.py`) — confirmed **not modified** (read-only access throughout; `git status` confirms no
  changes under `phase_measure_theory/` or `kernel/`).
- Filesystem scope: only `14_decision-log/MD-021-phase-5i-k2-definition-integrity/` (this directory,
  16 files) plus governance-record updates were written this phase.
- No global classification was changed anywhere.

## Completion criteria (self-checked)

1. K-2's ontological status classified with the required 1–7 vocabulary, evidence-backed — ✅ (`08`).
2. Assertion Definition Register built without merging during collection — ✅ (`01`).
3. Executable semantics audited line-by-line for all three scripts, comments not treated as
   authoritative — ✅ (`03`, `04`, `05`).
4. Five equivalence relations tested between the scripts, none forced — ✅ (`06`).
5. `Qualify`'s A–E classification performed — ✅ (`07`).
6. Multiple projections represented and tested for compatibility, not silently generalized — ✅ (`09`).
7. Information loss re-assessed only where new evidence bears on it — ✅ (`10`).
8. Every provenance edge typed; no `DERIVES_FROM` from sequence alone — ✅ (`11`).
9. Equivalence-relation axioms explicitly tested — ✅ (`12`).
10. DDD implications named only, no pattern declared — ✅ (`13`).
11. Required final matrix produced — ✅ (`14`).
12. ≥20 raw-source checks, covering all three scripts — ✅ (25 performed, this file).
13. Both resume scripts pass; register unchanged; all 101 frozen prior-phase files confirmed
    untouched; D285-1/D285-6/D285-7 and all three executable scripts confirmed unmodified; only the
    authorized Phase-5I location modified — ✅.
14. No canonical Kernel, no four-model convergence, no DDD context-map adoption, no implementation, no
    frozen-artifact repair, no Phase 5J begun — ✅.

## Overall completion classification (per the authorization's §19 — choosing the strongest status actually supported)

**D — INTERNALLY INCONSISTENT.**

Not A (CLOSED): the Assertion field-set conflict is real and unresolved. Not B (PARTIALLY CLOSED)
alone: this would understate the finding — the evidence does not merely have gaps, it actively
**conflicts** at multiple levels. Not C (NOT CLOSED): this would understate the genuine progress made
(K-1's own structure remains solid; K-2's top-level shape is undisputed; several precise, positive
findings were reached). Not E (UNRESOLVED): there is sufficient evidence to reach a specific,
positive finding, not merely insufficient evidence.

**The inconsistency spans three layers, precisely**:
1. **Conceptual ontology** — D285-1 and D285-6's own prose disagree about what `Assertion` contains.
2. **Executable semantics** — `t285_reconcile.py` and `t285_equality.py` disagree with each other in
   the same pattern as the prose, and `t285_reconcile.py` **disagrees with itself** (its own T-A and
   T-C sections).
3. **Provenance** — `t285_reconcile.py`'s own computed output was never updated to reflect D285-1's
   own later, explicit prose revision (the Sañjaya-layer correction to `Observation`'s status) — the
   code and the prose it is supposed to implement have drifted apart.

**This is not a failure of this reconstruction's own search effort** — every layer of inconsistency
above is confirmed by direct, re-executed comparison of the corpus's own artifacts against each other,
not by an absence of evidence. K-2, as currently evidenced, is genuinely **internally inconsistent as
a defined object**, at the level of its `Assertion` constituent specifically (not at its own top-level
`(𝒜,ℛ)` structure, which remains stable).

## Final Phase 5I status

**COMPLETE.**

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A through 5H artifact was
modified. D285-1/D285-6/D285-7 and the corpus's own three executable scripts were not modified. No
DDD context mapping was declared. No four-model or cross-model convergence was performed. No unified
or canonical Kernel was constructed. No implementation was performed.**

**PHASE 5I COMPLETE — AWAITING SEPARATE EXPLICIT AUTHORIZATION.**

Nothing beyond Phase 5I is authorized by this completion. Phase 5J, four-model convergence, global
reclassification, unified/canonical Kernel construction, DDD context-map adoption, repair of any
frozen artifact, and implementation all remain unauthorized and untouched.
