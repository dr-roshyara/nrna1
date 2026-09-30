# L0-REL-06 evidence pass — Knowledge Metamodel §6 (reader SELF) — REPORT

| | |
|---|---|
| Release | L0-REL-06 ("yes"): `engineering/architecture/reference/Engineering_Platform_Knowledge_Metamodel.md` §6 `## 6. Lifecycle semantics — four orthogonal axes (conflation is the policed failure mode)`; reader SELF; for EQ-3 / EQ-4 (EP-03a, EP-04a, EP-04b) |
| Hash | `a5c88e9bdbc5a40fe38304fc728508340a69b53788f42adac90c20996b9c2b44` ✔ · span **L102–105** (next heading L106) |
| Files | `evidence.json` `a1718c3a…` · `constraints.json` `c955abec…` · cumulative `../CUMULATIVE/evidence.json` `3bb48fe2…` · `../CUMULATIVE/constraints.json` `4034f22d…` |
| Formal basis | SECONDARY-REPRODUCED |

## What the section states (L104, its own wording)
- Four orthogonal lifecycle axes:
  - `authority` (generated → … → authoritative → historical);
  - `status` (idea → … → frozen/sealed | superseded → archived);
  - `maturity` (Candidate → Observed → Validated → Standard — Learning only);
  - `adoption` (planned → adopted → deprecated → removed — **Runtime only**).
- Illegal combinations are rejected.
- **"Evolution rules (when may X become Y) remain hosted where they live — ES-006.1, the Plan Concept, R-39's multi-context bar — this metamodel indexes, never restates."**
- Recorded absences: no research-expiry rule; no Architecture-Analysis lifecycle rule.

## Evidence (6 cells, 0 events)

| Cell | Proposition | Assessment | Why |
|---|---|---|---|
| M6-c1/c2/c3 | EP-03a / EP-04a / EP-04b | **SILENT** (HIGH) | the source **declares** that it does not state transition rules; it names their hosts |
| M6-c4 | EP-03a | SILENT (HIGH) | "no research-expiry rule": expiry ≠ evidence failure; an absent rule is not persistence |
| M6-c5 | EP-04a | OUT-OF-SCOPE (HIGH) | the `adoption` axis is **Runtime only**, not knowledge adoption |
| M6-c6 | EP-13c | AMBIGUOUS (HIGH) | "R-39's multi-context bar": names R-39 as host of a bar; does not decide EP-13c |

## Engine (frozen)
- **This pass:** 18 SILENT + EP-13c AMBIGUOUS. No constraint; every model NOT-ELIMINATED; no flag.
- **Cumulative** (13 cells, L0-REL-03/04/06): **15 SILENT · 3 OUT-OF-SCOPE · 1 AMBIGUOUS**. No constraint.

## What was gained
- **A declared map of where the rules live.** The metamodel names the hosts of the evolution (transition) rules: **ES-006.1**, **the Plan Concept** (`docs/implementation/Plan_Concept_Decision_Paper.md`, located by file name only), and **R-39's multi-context bar**.
- These are the sources that could state post-promotion transitions (EQ-3/EQ-4). The next release should target them rather than further lexical candidates.
- **R-39 signature, dimension B (new wording, R-39 thread only):** R-39 is referred to as hosting "a multi-context bar", i.e. as bar-defining. What R-39's exception did is still not stated in released lines.

## Closed
- No other section or file read; no pointer followed; EPIC-004 closed; no ML; no model conclusion.
- Absence is never read as P0.
