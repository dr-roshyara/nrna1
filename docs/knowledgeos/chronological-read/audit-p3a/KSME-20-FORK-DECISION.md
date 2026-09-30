---
task: KSME-20 (P3a Reliability Diagnosis — decision gate, per user commission)
derived_from: [KSME-20-P3A-QUALITY-REPRODUCTION, KSME-20-P3A-ERROR-TAXONOMY, KSME-20-P3A-TRACK-PROVENANCE-AUDIT,
  KSME-20-P3A-ADJUDICATION-CONTRACT, 3 forks, direct mechanical joins this pass]
---

# KSME-20 — Fork Decision: Is P3a reliable enough to continue?

## Gate classification: YELLOW

Not GREEN (the reconciliation ontology has real, evidence-confirmed gaps and one live implementation
defect — trusting P3a's output as-is would be wrong). Not RED (nothing found calls the 11-value ontology's
basic design into question, or suggests the reconciliation *approach* is unsound — the problem is bounded,
precisely located, and predominantly mechanical). **Limited, well-understood categories require correction
before P3b/D1-D27/Kernel work treats P3a's output as settled; the corpus-reading approach itself is sound.**

## Required answers

**Is P3a reliable enough to continue?** Not as-is, but the path to reliable is short and mostly mechanical
— see the Adjudication Contract's 3-step fix order. Continuing P3b at scale on unrepaired P3a data would
propagate a ~20-31% verdict-agreement problem into every downstream dependency edge; continuing after the
two mechanical fixes (evidence-visibility repair, NEGATIVE-BOUNDED/CENSUS enforcement) would not.

**What exactly caused the 20.9% exact agreement?** Three converging, independently-corroborated causes,
NOT one: (1) **dominant** — a single, precisely located implementation defect (`row_brief()` stripping
`dependencies`/`lineage_claims`/`invariants`/`assumptions`/provenance before any reviewer sees them),
confirmed via 8 reproducing synthetic fixtures and independently corroborated by a second validation
exercise (86% of a separately-sampled 100-pair PRIMARY-evidence set shows a real relationship the pipeline
never surfaced). (2) A real, bounded ontology-coverage gap (~7-10%, two confirmed patterns:
"operationally-connected-but-architecturally-independent," "symmetric/co-integrative sibling") — genuinely
requires a governance decision, not a mechanical fix. (3) A small residual of ordinary reviewer
disagreement on never-disambiguated soft boundaries (SAME↔CONTINUATION, DERIVED-FROM↔CONTINUATION/
EXTENSION/SPECIALIZATION, REPLACEMENT↔REDEFINITION).

**Is UNWITNESSED being misused?** Yes, but precisely characterizable, not a blanket failure: ~71% of
sampled UNWITNESSED verdicts are correct; ~17% reflect perfunctory/truncated search; ~10% are the ontology
gap above misfiled as absence; ~2% misfile corpus-level contestation as absence. Separately, ≥9.5% of all
684 UNWITNESSED verdicts (65 pairs) are demonstrably wrong specifically because of the evidence-visibility
bug in cause (1) above — a different root cause than mislabeling.

**Is the reconciliation ontology itself adequate?** Mostly yes, with two confirmed, bounded gaps (§3 of the
Error Taxonomy) — not a wholesale redesign case. The REFINEMENT/EXTENSION/REDEFINITION/SPECIALIZATION/
CONTINUATION/DERIVED-FROM cluster is under-specified at the protocol level (no decision procedure was ever
written), which is a **specification gap**, not an ontology-design flaw — closed by the Adjudication
Contract's fixed evaluation order.

**How many of the 260 disputed labels are actually wrong?** Cannot be stated precisely without re-running
step 3 of the Adjudication Contract (repair evidence visibility, then re-adjudicate only the affected
subset). What can be stated: of the 158 high-stakes pairs, the confusion matrix shows 125 disagree with the
original verdict, but per the cause breakdown, at most ~7/20 sampled (35%, extrapolated ~55 of 158) are
genuine adjudication errors or judgment calls needing a human decision; the remainder (~65%) resolve
mechanically once causes (1) and (2) above are addressed — either the evidence-visibility fix changes the
verdict automatically, or the pair is correctly routed to `PENDING-ONTOLOGY-DECISION` rather than forced
into a wrong enum value.

**How many are genuinely unresolved (need human adjudication)?** Estimated ~55-65 of the 158 (the
judgment-call and ontology-gap-affected subset), not all 158 and not all 260 touched labels.

**How many are merely scope/type differences?** The Track-Provenance Audit found cross-track/mixed-object
involvement is moderately elevated in the disputed slice (24.1%/41.1% vs. 18.1%/31.5% baseline, ~1.3x) but
is a secondary, not dominant, factor — most of the disagreement is not explained by track/scope crossing.

**Can P3b safely resume?** **Partially, immediately; fully, after the two mechanical fixes.** The
quality-gate memo's own original recommendation stands and is corroborated here: resume P3b for the 2,237
labels with zero dependency on the 158 disputed pairs now; hold the 260 touched labels PROVISIONAL until the
Adjudication Contract's steps 1-2 are applied and step 3's bounded re-adjudication completes.

**What existing artifacts should KSME-20 reuse rather than rebuild?** Everything in P1-P3a (extraction,
object index, families, reconciliation pairs) — confirmed substantially complete and, once repaired,
trustworthy. `v2/derive_reconciliation_v2.py` and `evidence_bundle_v2.py` as the candidate-generation/
evidence-completeness input layer (real, validated, but requires the explicit adoption decision it has been
awaiting since 2026-09-21). The Step-series file numbering (759 files) and existing `lineage_claims`
(2,121 contributions, 7.6%) as the raw material for derivation-chain assembly (see below) — this needs no
new corpus reading, only graph assembly over already-extracted data.

## The derivation-chain reframing (user's newest hypothesis) — tested directly, partially confirmed

**Context-loss-by-evidence-stripping: strongly confirmed** — this is precisely what causes (1)/(3) above
are. The `row_brief()` bug means reviewers (original AND independent re-derivation alike) were often
evaluating pairs without the `lineage_claims`/`dependencies` evidence the corpus already captured — not
because the chain doesn't exist, but because it was mechanically hidden from view before adjudication.

**Context-loss-by-track-crossing: modestly confirmed** — a real but secondary factor (§3 above).

**The chain-structure hypothesis itself is independently supported by data that already exists**: 53.7% of
all 1,793 already-judged P3a relationships are chain-type (EXTENSION 25.1%, CONTINUATION 11.1%, REFINEMENT
7.8%, DERIVED-FROM 5.2%, SPECIALIZATION 4.5%) — most of what the pipeline has judged is chain structure, not
competing/independent theories. Assembling actual multi-hop derivation chains (walking these typed edges
into connected sequences, cross-referenced against the Step-series file order for 759 files) is graph
traversal over already-extracted data, not a new extraction pass — **this revises the effort estimate for
any future "Derivation Reconstruction" work sharply downward**, contingent on the P3a repair above (a
derivation chain built from unrepaired, ~21-31%-reliable edges would inherit the same reliability problem).

## Stop conditions honored

Per the commission: **no proceeding to large-scale P3b beyond the unaffected 2,237 labels. No proceeding to
D1-D27. No Kernel selection.** The immediate objective — "make the existing corpus-reconciliation foundation
trustworthy before extending it" — is answered: it is trustworthy for 92% of the corpus (2,237/2,497 labels
unaffected by the disputed 158 pairs) now, and the remaining 8% has a named, bounded, mostly-mechanical
repair path, not an open-ended reliability problem. **Next step is a human decision on: (a) whether to
implement the Adjudication Contract's 3-step fix order, (b) whether to adopt v2 as the input-layer fix,
(c) the governance decision on the 2 confirmed ontology gaps — not something this pass executes
unilaterally.**
