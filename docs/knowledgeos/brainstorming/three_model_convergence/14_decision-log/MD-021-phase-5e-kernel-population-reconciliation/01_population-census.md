# Phase 5E — Population Census

## P2 — `phase_measure_theory/knowledgeos_kernel/`

**Mechanical determination**: `find phase_measure_theory/knowledgeos_kernel -type f` = 265 files on
disk, of which **237 are `.md`** (13 `.py`, 8 `.txt`, 5 `.json`, and 2 anomalously-named files with
shell-unsafe names, one apparently a stray shell-echoed fragment — none of these non-`.md` files are
authorial documents; they are experiment scripts, logs, or data artifacts). **P2's declared population
of 237 is exactly the `.md` count** — confirmed to match the user's own stated figure precisely.

**Record reconciliation**: joined the 237 on-disk `.md` paths against `01_source-analysis/per-file/
*.yaml` records whose `path` field contains `phase_measure_theory/knowledgeos_kernel/`. Result:
**237 on-disk files, 237 matching records, 0 on-disk files without a record, 0 records without an
on-disk file.** P2 is fully integrated into the main sequential pass — no reconciliation gap exists
(unlike the mathematical lane's own pre-Phase-0 state).

## P3 — `kernel/`

**Mechanical determination**: `find kernel -type f` = 181 files, of which **172 are `.md`** (1 `.docx`,
8 `.gitkeep` empty-directory markers). Breakdown by subdirectory: 163 at top level, 2 in
`refinement_phase/`, 1 in `corpus/`, 4 in `classification/`, 1 in `falsification/`, 1 in `synthesis/`
— **163+2+1+4+1+1 = 172**, confirmed. **These nested subdirectories are NOT the same as the top-level
`docs/knowledgeos/brainstorming/corpus/`, `.../classification/`, `.../falsification/`, `.../synthesis/`
directories MD-010/MD-011 excluded from the primary corpus** — the exclusion rule targets direct
children of `docs/knowledgeos/brainstorming/`, not files nested inside `kernel/` that merely share a
subdirectory name. This distinction is stated explicitly, per the authorization's own instruction not
to infer population membership from directory names alone without checking the actual exclusion
rule's scope.

**Record reconciliation**: joined the 172 on-disk `.md` paths against per-file records whose `path`
contains `/brainstorming/kernel/`. Result: **172 on-disk files, 172 matching records, 0 gaps, 0
orphans.**

## Overlap with P1 (Phase 5D's own 116-document census)

| | Count |
|---|---:|
| P1 ∩ P2 | 13 |
| P1 ∩ P3 | 58 |
| P2 ∩ P3 | 0 |
| P3-only (new territory this phase) | 114 |
| P2-only (new territory this phase) | 224 |

**The 71 overlapping documents (13+58) are not re-dispositioned from scratch** — Phase 5D's own §01
disposition is authoritative for them; this phase cross-references rather than duplicates that work
(re-verified as still consistent during this phase's own reading of the full P2/P3 digest — no
disagreement found).

## Method used to disposition the 409 documents

A compact per-document digest (title, classification tag, truncated introduces/defines/contradicts)
was built from the same governed per-file records used throughout this reconstruction, and **read in
full, sequentially, covering all 409 documents** (2,045 digest lines). Disposition was assigned using
a documented, disclosed rule set applied to each document's own title/summary content (pattern
categories: explicit self-declared duplicates; reviewer/audit/critique framing; governance/mandate/
ratification framing; register/ledger/index/gap-update framing; named-external-source book/lens
extraction; explicit tuple/equation-bearing titles), with **every rule-assigned disposition checked
against the actual digest content already read** (not applied blind) and a residual set of 33 documents
individually re-read and manually dispositioned where the pattern rules alone were insufficient. This
is a **complete-population census with disclosed classification method**, not a sample — every one of
the 409 documents received an explicit disposition; none was skipped or inferred without inspection.
