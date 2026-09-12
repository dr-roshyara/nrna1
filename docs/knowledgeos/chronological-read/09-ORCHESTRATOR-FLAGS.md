# Orchestrator flags carried forward from Phase 1 batches

Batch-level caveats an extraction agent raised about its own coverage, kept here so
Phase 2/3 can act on them deliberately rather than lose them silently (R0).

## B0004 — migration-plan-amendment-chain under-itemized
Object `migration-plan-amendment-chain` spans 11 files (S0133-S0138, S0141-S0142) with
an estimated 60+ named sub-findings (CL/RC/RD/DI/DV/C series). The dispatched agent
sampled representative findings and overall verdicts rather than itemizing every one,
and explicitly recommended: "a targeted re-pass may be warranted if later phases need
full enumeration." Action: Phase 2 (object reconstruction for this label) should decide
whether full enumeration is required before reconciliation, and if so, dispatch a
single-object re-extraction pass over S0133-S0138/S0141-S0142 rather than assume
B0004's ledger rows are exhaustive for this object.

## B0006 — cross-batch cross-reference and unreconciled lineages
`established-inv-001-004-catalog` is cited constantly in B0006 (S0226-S0232 cluster)
but never defined there — check against B0002's `invariant-catalog` label in Phase 2/3.
Three unreconciled dimension-count lineages found (5-dim/6-dim/7-preserve variants of
what appears to be the same object family) — flagged for Phase 3 reconciliation, not
resolved here. S0206 (EKS Architecture Baseline, 70 contributions alone) is the
richest single source in the batch and a priority cross-reference target.

## B0010 — self-disclosed labeling deviation, and infrastructure interruption note
The dispatched agent flagged its own deviation: 18 contribution rows (S0363-S0397,
label `knowledgeos-kernel-concept`) used `label_confidence:"UNCERTAIN"` with the
labels field set to an existing REGISTERED label plus `unknown_candidate.candidate_of`
pointing at that same label, rather than the contract's strict
`labels:["UNKNOWN-OBJECT-CANDIDATE"]` form. This does not corrupt the index (the label
used is legitimate and already registered) but does encode real uncertainty about
whether these 18 contributions genuinely belong to `knowledgeos-kernel-concept` or a
distinct, not-yet-separated object. Action: Phase 3 reconciliation for
`knowledgeos-kernel-concept` should specifically re-examine these 18 rows (S0363,
S0364, S0365 x3, and others in S0363-S0397) before treating them as settled members
of that family.

Separately: this batch is a clean retry after the first attempt (same batch) was
killed mid-flight by an infrastructure weekly rate limit and produced untrustworthy
partial output (pre-written file records for unread files). That first attempt's
output was fully discarded, never merged. This retry also caught and mechanically
corrected 5 rows using an invented type "OBSERVATION" (paired with a valid type in
every case; the invented word was dropped, the valid type retained).

## B0012 — Zero Lens self-correction chain, and S0446 overlap candidates
The dispatched agent flagged: (1) a 5-step self-correction chain for the "Zero Lens"
definition across S0481->483->484->485->486 (14 REFINEMENT/EXTENSION/CORRECTION
lineage claims total in this batch) — Phase 2 should treat this as one evolving
thread, not five competing definitions. (2) S0446's 14 UNKNOWN-OBJECT-CANDIDATE rows
(Freedman-derived Observation/Inference/Claim/Model vocabulary) may substantially
overlap existing evidence/capability objects — flagged for a dedicated Phase 2/3
reconciliation pass, not merged here. (3) S0488/S0490/S0492 form a tight
self-referential mini-series converging on a KnowledgeState(t)/Update(K_t,E_new)
model — a strong Phase 2 consolidation candidate.
One UNCERTAIN-labeling deviation logged (S0450, `ganesha-wisdom-transformation-loop`
with unknown_candidate hedge) — same benign pattern as B0010, does not corrupt the
index.

## Recurring pattern — invented contribution types
Three separate batches (B0006:"DISCOVERY", B0010:"OBSERVATION", B0012:"EVIDENCE")
have each independently invented a plausible-sounding but non-contractual type word,
always paired with a valid type in the same row. All caught by mechanical
verification and fixed by dropping the invented word. The contract's closed-list
instruction has been reinforced after each occurrence but the pattern keeps
recurring under different wording — worth treating as an expected, low-cost failure
mode to check for on every future batch rather than a one-off.

## B0013 — anchor gap repaired directly by orchestrator, and flagged findings
25 contribution rows (S0534: 20, S0535: 5 — the last two files processed in this
batch, likely rushed after a mid-batch session-limit interruption/resumption) had
`anchor: null` instead of the required verbatim quote. Rather than guess or dispatch
another agent, the orchestrator read both source files directly
(docs/knowledgeos/brainstorming/kernel/20260825-155035-...searle...md and
...20260825-155510-...knowledge-as-capacity...md) and supplied verbatim anchors for
each already-drafted statement, verifying every anchor as an exact substring of its
source file before writing. No statement content was altered.

Findings the dispatched agent itself flagged for Phase 2/3:
- EKI-08 ID collision between S0501 and S0502 (review_flag TYPE-QUESTION on S0502).
- ~8 unreconciled competing "Knowledge State" tuples across the batch, never
  resolved by the corpus itself.
- A dual convention observed for `unknown_candidate` (pure UNKNOWN-OBJECT-CANDIDATE
  sentinel vs. real-label-plus-uncertainty hedge) — consistent with the same benign
  pattern already logged for B0010/B0012; not a defect, just worth Phase 3 awareness.

## verify_batch.py bug fixed (second occurrence of this class)
`c.get("anchor", "").strip()` crashed on an explicit `"anchor": null` the same way
the assumptions check did earlier (a `.get(key, default)` default only applies when
the key is MISSING, not when its value is null). Fixed to `(c.get("anchor") or
"").strip()`. Any future field with this same optional-string shape should use the
`(x.get(...) or "")` pattern from the start.
