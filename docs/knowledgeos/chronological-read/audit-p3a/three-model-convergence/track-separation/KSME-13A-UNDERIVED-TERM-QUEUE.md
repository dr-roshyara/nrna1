---
source_track: TRACK-A-PHASE-MEASURE
derived_from: [all KSME-13/13A fork reports]
cross_track_dependency: see items marked scope-blocked
---

# KSME-13A — UNDERIVED Term Queue

Status vocabulary used throughout: `NOT-YET-TRAVERSED / NOT-FOUND-IN-SEARCH / NO-SOURCE-FOUND-AFTER-
EXHAUSTIVE-SEARCH / SOURCE-EXPLICITLY-ABSENT / SOURCE-EXPLICITLY-REJECTED / UNRESOLVED / GOVERNANCE-
DEPENDENT / SOURCE-CLAIMED-VIA-CITATION`.

| Term | Context | Why underived | Status |
|---|---|---|---|
| `DECISION-02` | `theory-08`/`170000` | Explicitly a decision, not derivable from experiment (6-reading space, all compatible with existing evidence) | `GOVERNANCE-DEPENDENT` — resolved as a *classification*, not an open search failure |
| `C6`, `C7`, `majority`, `last-wins` exact formulas | `170000` cites primary sources `X`/`Y` in `docs/knowledgeos/research/` | Primary source outside currently-authorized scope | `NOT-YET-TRAVERSED (scope-blocked, admissibility pending)` |
| `Reason` exact cardinality (8 vs 10) | FDE writeup vs. `theory-08`/`KR-CONTR-EVAL` | Two dated results, primary `KR-CONTR-EVAL` file not located in-scope | `SOURCE-CLAIMED-VIA-CITATION` (10-value figure); `SOURCE-ESTABLISHED` (8-value illustrative list, in-scope) — not reconciled |
| `Contr_step292` algorithm | `step-292/04` | No signature/algorithm anywhere in the file | `NO-SOURCE-FOUND-AFTER-EXHAUSTIVE-SEARCH` |
| `Contr_theory08 ≟ Contr_step292` | cross-thread | Only an authorial analogy exists | `UNRESOLVED` (relationship type: `merely-analogizes`, not `is-equivalent-to`) |
| `⊕` (epistemic, Step 32) | Step 32 §32.19 | No formula anywhere; glyph collides with 2 unrelated operators | `NO-SOURCE-FOUND-AFTER-EXHAUSTIVE-SEARCH` |
| `Conflict_Step32algebra` | Step 32 §32.76 | Named tuple element, never given a signature, never reused | `NO-SOURCE-FOUND-AFTER-EXHAUSTIVE-SEARCH` |
| `Conflict_Step32algebra ≟ ConflictStatus_Step32state ≟ Conflict_Step60predicate` | cross-source | No bridging formula anywhere | `UNRESOLVED`, resolved-as-distinct (relationship D) |
| `Conflict(p,t)` (temporal variant) | Step 60 §60.45-46 | Different arity from `Conflict(p)`, never related | `UNRESOLVED` |
| `⪯` formal properties | Step 32→Step 60 | Candidate ordering given, reflexivity/antisymmetry/transitivity never verified | `UNRESOLVED` (real predecessor→successor chain exists) |
| `Merge_v` (version-aware merge) | Step 60 §60.44 | One-line mention, no algorithm | `NOT-FOUND-IN-SEARCH` (not traversed further) |
| `Authorize_Step32 ≟ Authorize_Step259 ≟ Authorize_277.20/.25` | cross-source | Zero cross-references found in either direction for any pair except `277.20`→`277.25` | `UNRESOLVED`, resolved-as-3-distinct-identities |
| "the earlier corpus" (unattributed citation) | Step 277 line 907 | No Step number or filename given | `NOT-FOUND-IN-SEARCH` |
| `Revision` (typed signature) | Step 32 §32.5/23 | Only informal arrow notation exists | `NO-SOURCE-FOUND-AFTER-EXHAUSTIVE-SEARCH` |
| `Resolve` (algorithm) | Step 60 §60.23 | Named input kinds, no procedure | `NO-SOURCE-FOUND-AFTER-EXHAUSTIVE-SEARCH` |
| `Policy` (internal structure) | Step 277 §277.36 | Corpus itself states this is unformalized | `SOURCE-EXPLICITLY-ABSENT` |
| `Authority` (internal structure) | Step 277 §277.36 | Same disclosure as Policy | `SOURCE-EXPLICITLY-ABSENT` |
| `DomainPolicy`/`HumanAdjudication`/`StatisticalModel` | Step 60 §60.23 | Named only, never independently defined | `NOT-FOUND-IN-SEARCH` (not traversed further) |
| `BC-02.14`–`20` (`research/knowledgeos-sim/`) findings | this session, pre-KSME | Established practice left it untouched; never formally admitted or firewalled | `GOVERNANCE-DEPENDENT` — awaiting explicit authorization |
| `t285_reconcile.py` as `E_B`/`T_B` foundation | `D285-6`'s companion code | Confirmed: standalone demo, not a library | `SOURCE-EXPLICITLY-REJECTED` (as a reuse candidate) |

## Queue status

**Not empty.** 8 items are `GOVERNANCE-DEPENDENT` or scope-blocked pending explicit authorization (all
`docs/knowledgeos/research/`-rooted, plus the `BC-02.14`–`20` question). The remaining items are terminal
— confirmed absent/rejected/unresolved by direct exhaustive search, not by search limitation. Per the
protocol's own instruction, this is the honest, expected outcome, not a failure of this pass.
