# Model B — Boundary Observations

Accounts for the 239 of 401 math-lane rows that are **not** Model-B evidence
(`00_index.md`'s population table). Every observation here is bounded — named, counted, and where a
record itself points outward, the pointer is recorded as a hypothesis, never as an established
cross-model relationship. **Nothing here changes `model.primary` for any row, and nothing here is
treated as Model-B evidence in `01_evidence-base.md` or `02_concept-register.md`.**

---

## Category breakdown

| `model.primary` | Count | Treatment here |
|---|---|---|
| `KR-SIM` | 199 | Named as this lane's own long-standing boundary category (below) |
| `x` (cross_model) | 16 | Named as this lane's own long-standing boundary category (below) |
| `c1` (engineering_knowledgeos) | 13 | Named as this lane's own long-standing boundary category (below) |
| `g` (gita) | 6 | Already Model-A's evidence (Phase 1) — not re-examined here |
| `c` (ambiguous, non-canonical) | 5 | Individually sampled — see below |
| **Total boundary** | **239** | |

## `KR-SIM` (199 rows) — general exploratory/dialogue-style research, not Model-B mathematics proper

This is a category native to the math lane since its *original* 282-file pass, not something
introduced by this session's own reconciliation work. It comprises the bulk of the corpus's
narrative/exploratory research dialogue that does not itself carry the `mathematical_content`/
`kernel_content` density the `b` tag requires. **Not sampled file-by-file for this phase** — the
authorization's evidence-population instruction is to account for boundary rows by category, not to
individually re-examine each one; a full re-classification of 199 files is outside Phase 2's
approved scope (and would itself be exactly the kind of "reclassify to inflate coverage" move the
authorization's §8 explicitly forbids in the other direction — inflating Model B's own count — so
symmetric caution is exercised here too: no `KR-SIM` row is reclassified `b` on the strength of a
Phase-2-only spot impression).

**One structural observation, bounded, not a reclassification**: several `b`-tagged files in the
independent evidence base (§ concept register, e.g. M0030, M0107, M0114) explicitly *specify* an
experiment as a formal protocol, while several `KR-SIM`-tagged files elsewhere in the corpus appear
(by title pattern alone, not content-verified here) to report *execution* of a same-named protocol.
This suggests the `b`/`KR-SIM` boundary in places tracks "specification vs. narrated execution"
rather than "mathematical vs. non-mathematical content" — recorded as a **hypothesis about this
lane's own tagging practice**, not verified against any specific file pair, and not used anywhere in
this phase's own evidence accounting.

## `x` (cross_model, 16 rows) — outward-pointing by construction

These rows are tagged `cross_model` by the corpus's own original classification, meaning they were
already judged, prior to this reconstruction, to relate more than one model lineage. **Not opened or
content-verified in this phase** (opening them would risk exactly the kind of premature cross-model
comparison the authorization's absolute boundary forbids). Recorded here only as a count and a
named category; any `bridge_candidates` field they may carry is left unexamined, consistent with the
instruction to record outward pointers as bounded hypotheses only, never followed up on, within
Phase 2's scope.

## `c1` (engineering_knowledgeos, 13 rows) — already-owned by a different reconstruction lineage

Per this lane's own classification, primary ownership of these 13 rows sits with the eventual Model
C1 (Engineering KnowledgeOS) reconstruction (Phase 3, unauthorized). Folding them into Model B's own
evidence base — even where their content might look mathematically dense — would be exactly the kind
of directory/content-membership error the authorization's §2 specifically warns against (evidence
membership is determined by the file's own primary tag, not by a Phase-2 reader's impression of
content). Recorded as a count only.

## `g` (gita, 6 rows) — already Model-A's own evidence

These six rows carry `model.primary: g` within the *math lane's own* 401-file inventory (distinct
from, and not to be confused with, the main corpus's separate 84-row Gītā evidence set Model A used
in Phase 1 — that set lives in `classification-register.tsv`, not here). **Not opened or examined in
this phase.** Named here only so the boundary accounting is complete (401 = 162 + 199 + 16 + 13 + 6
+ 5), and so a later reader does not mistake their absence from this phase's evidence base for an
omission rather than a deliberate boundary.

## `c` (ambiguous, non-canonical shorthand, 5 rows) — individually sampled; one data-quality anomaly found

`c` is not one of protocol.md's own canonical `model.primary` values (`gita`/`mathematics`/
`engineering_knowledgeos`/`epistemic_knowledgeos`/`cross_model`, per the corpus's governing
taxonomy) — its presence at all is itself a schema anomaly, carried forward from the original
282-file pass rather than introduced during this reconstruction. All 5 rows were individually opened
and sampled (a small enough set that content verification does not risk scope creep):

- **4 of 5** are genuine logic/mathematics-adjacent external-literature extractions — summarizing or
  drawing on Priest, Shapiro, and Rice (paraconsistent logic, philosophy of mathematics, and
  computability theory respectively). These are content-adjacent to Model B's own subject matter but
  are **not reclassified `b` here** — that would be exactly the kind of silent, phase-scope-exceeding
  reclassification the authorization forbids; the observation is recorded as a bounded finding only,
  for a future, separately-authorized classification-repair pass to consider.
- **1 of 5 (M0093) is explicitly Gītā content, mistagged.** Its actual content is Gītā/philosophical,
  not mathematics-adjacent at all — a clear data-quality anomaly, not a boundary judgment call.
  **Not reclassified here** (same reasoning as above: a repair belongs to a separately-authorized
  classification pass, not to Phase 2's own evidence-accounting work), but flagged explicitly so it
  is not lost. This file was **not** part of Model A's own 84-row Phase-1 evidence set (that set was
  drawn from the main corpus's `classification-register.tsv`, not from this math-lane inventory) —
  no claim is made here about whether Model A's own reconstruction should have included it; that
  question belongs to a later, separately-authorized cross-model or classification-repair phase, not
  to this boundary observation.

---

## What this document does not do

- Does not reclassify any of the 239 rows, including the one data-quality anomaly found (M0093).
- Does not open or content-verify the 199 `KR-SIM` or 16 `x` rows individually.
- Does not establish, imply, or record as fact any cross-model relationship — the one structural
  hypothesis above (about the `b`/`KR-SIM` specification-vs-execution pattern) is recorded explicitly
  as unverified and unused elsewhere in this phase.
- Does not compare any boundary row, or the boundary accounting as a whole, against Model A's own
  evidence or boundary observations.

**Total accounted for: 239 = 199 + 16 + 13 + 6 + 5.** Combined with the 162-row Model-B evidence
population (`00_index.md`), the full 401-file math-lane inventory is accounted for with no row
double-counted and none silently dropped.
