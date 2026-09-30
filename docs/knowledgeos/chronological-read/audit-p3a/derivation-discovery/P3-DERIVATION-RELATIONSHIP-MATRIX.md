# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# Derivation Relationship Matrix

**Purpose:** map observed multi-definition/derivation cases to the EXISTING
Master Protocol v3.5 P3 relationship vocabulary (§6). No new relationship
value is adopted here. **Date:** 2026-09-21. **Status:** EXPERIMENTAL.
**Authoritative:** NO.

## Worked matrix: `knowledgeos-kernel-concept` (9 formulations, full pairwise
table from the discovery audit — the vocabulary was SUFFICIENT for every
pair, 0 ONTOLOGY-GAP needed)

| Pair | Same object? | Same definition? | Same derivation? | Same conclusion? | Same dependencies? | Relationship | Basis |
|---|---|---|---|---|---|---|---|
| F1→F2 | YES | NO | NO | NO | NO | INDEPENDENT | NONE |
| F2→F3 | YES | NO | NO | NO | PARTIAL ({Evidence,Provenance} only) | REPLACEMENT | SOURCE-CLAIMED |
| F3→F4 | YES | NO | NO | NO | NO | INDEPENDENT | INFERRED |
| F4→F5 | YES | NO | NO | NO | shared prompt lineage (flagged, not independent) | CONTRADICTORY (weak) | SOURCE-CLAIMED, caveated |
| F5→F6 | YES | claimed NO, dissolves to YES on inspection | UNCERTAIN | NO | UNCERTAIN | CONTRADICTORY→SAME (vocabulary collision) | SOURCE-CLAIMED-CONTRADICTION, CORROBORATED-otherwise |
| F6→F7 | YES | NO | NO | NO (F7 is a negative/UNRESOLVED result) | NO | CONTRADICTORY (by implication) | INFERRED |
| F7→F8 | YES | NO | YES (targets the same shared six-part model) | NO (falsifies it) | YES (shared target) | DERIVED-FROM | SOURCE-CLAIMED |
| F8→F9 | YES | NO (F9 keeps 6 members F8 rejected) | PARTIAL (methodological only) | NO | NO | DERIVED-FROM (methodology only — internal tension not resolved) | SOURCE-CLAIMED |
| F9→F10 | YES | NO | NO | NO | NO | INDEPENDENT | NONE |
| F10→F11 | YES | PARTIAL | NO | NO | PARTIAL (shared "Knowledge Space") | EXTENSION | INFERRED |
| F11→F12 | YES | NO | NO | NO (near-opposite emphasis) | PARTIAL | REDEFINITION | INFERRED |
| F12→F13 | YES | NO | PARTIAL | NO | shared external hypothesis | EXTENSION + DERIVED-FROM (dual) | INFERRED / SOURCE-CLAIMED |
| F12→F14 | YES | near-YES | NO (sharper notation) | near-YES | YES | REFINEMENT | INFERRED |
| F14→F15 | YES | NO (adds operator decomposition) | NO | NO | YES | EXTENSION | INFERRED |
| F15→F16 | YES | PARTIAL (narrows) | NO | PARTIAL | YES | REFINEMENT | SOURCE-CLAIMED |

**Reading**: the 5-dimension matrix (§5) is not redundant with the single
relationship label — F5→F6 shows exactly why: same object, contradictory
claim, but on independent inspection the underlying definitions turn out
compatible. Collapsing straight to "CONTRADICTORY" (as the row's own
SOURCE-CLAIMED-CONTRADICTION tag would suggest) would have been wrong; the
5-axis matrix caught it.

## Where the vocabulary was NOT sufficient: `step-verify-programme`

At least 4 pairs required `ONTOLOGY-GAP` (full detail in the Discovery
Audit). Common shape: same session, same author, functionally identical
purpose, minutes-to-hours apart, partial vocabulary overlap, zero citation,
both left standing. **Proposed name for this recurring, unrepresented
pattern** (not adopted — flagged per §6 as a `PROTOCOL-EXTENSION-CANDIDATE`,
never silently added to the enum): `PARALLEL-UNRECONCILED`.

## Case taxonomy from §7, populated with real examples

| Case | Found in this sample? | Example |
|---|---|---|
| A — Same derivation, different wording | Not clearly observed (the closest, F5↔F6, was a claimed *contradiction* dissolving to compatibility, not two identical derivations restated) | — |
| B — Refinement | Yes, repeatedly | `dimension-discovery-uncertainty` S0696 corrects/refines S0691-S0692; kernel F15→F16 |
| C — Extension | Yes, repeatedly | kernel F10→F11, F14→F15; `multi-dimensional-epistemic-state-model` S0806→S0824 |
| D — Independent derivation, same conclusion | Yes | `kernel-review-session-findings` S0612: "three independent state-set derivations... converge on Admitted, Superseded, Reconciled..." |
| E — Common-mode (apparently independent, shared hidden dependency) | Yes, the single most important finding of this audit | kernel Wave 1's shared single-boundary assumption; Wave 3's shared "K_t is well-typed" assumption; step-verify-programme's shared finite-taxonomy assumption across 5 schemes |
| F — Contradictory derivation | Yes | `formal-zero-algebra`'s 5 CONTRADICTION rows; kernel F6→F7, F4→F5 |
| G — Different concepts (misleading similarity) | Not clearly observed in this sample (would need a larger/different sample — this pattern is closer to what P3a's HOMONYM relationship already targets at the inter-label level) | — |

## Explicit vs. inferred (§10) — compliance check across the audit

Every relationship recorded in the two deep-dive families carries an explicit
basis tag (SOURCE-CLAIMED / CORROBORATED / INFERRED / NONE), and in every case
an INFERRED relationship is accompanied by the stated inference (e.g. "38h gap,
new session, F4 doesn't cite the Aug-21 pair" for F3→F4's INDEPENDENT call) —
**no agent inference was silently converted into a source claim anywhere in
this audit**, verified by direct inspection of both agent reports.
