# Batch B0032 — Extraction Summary

**Files processed:** 40 (S1308–S1347). All read in full (no FIREWALL-LIMITED, no partial-read
disclosures needed — all files were small-to-medium markdown/Python/text files).

**Commit:** 39fdef05dc027c6264b6c349a26362a59191a35f

## Contents overview

Two major threads dominate this batch:

1. **Session-2 Kernel review register, closing sequence** (S1308–S1314, S1322–S1325, S1330–S1332):
   the final ~15 per-artifact reviews (S2-R-F033 through S2-R-F040), the cross-track observation
   register (X-001..X-006), the master review-framework index (S2-00), the coverage-report audit,
   and the terminal synthesis (S2-FINAL-KERNEL-REVIEW.md) recommending DEFER with two named cheap
   actions. Headline results: zero A-class (Kernel-extent-changing) findings across all 40
   Session-1 artifacts; thirteen apparent intra-corpus contradictions dissolved by thirteen
   distinct mechanisms, with exactly two genuine constitutional-level tensions surviving; the
   corpus's own coverage report is shown to have read only ~3-5% of its source corpus; and the
   authority-temporal-validity gap (S1-F001/S2-R-F001) is identified as the single strongest,
   irreversible-loss implementation question in the whole register.

2. **Mathematical/computational verification thread** (S1315–S1320, S1326, S1329, S1334,
   S1338–S1344): three Python reference-implementation scripts (EXP-01 evidence-aggregation
   operators, the Zero(K,EC) formal algebra, and a status-ladder/Decision-Contract admissibility
   check) plus their captured outputs, feeding into a full GN-46 mathematical-verification-report,
   two rounds of governance findings-disposition analysis (GN-48/49), and finally the book's own
   Part III closing chapters (III.9 "Three Kernels" and III.10 "The Invariants") teaching the
   surviving findings at finding-strength. Headline results: the historical negative verdict about
   scalar evidence-aggregation operators is confirmed and shown provable; the ratified Decision
   Contract six-tuple has no ratified admissibility law of its own; a Proposal-to-Decision flow
   edge is resolved as a modelling omission (not a type error); no ratified invariant is actually
   violated (only I-3, I-5, I-9, I-11 are threatened/wording-flawed); and the three "kernel"
   traditions (constitutional/formal/historical) are formally layered without being identified.

A third, smaller thread (S1321, S1327, S1328, S1333, S1335–S1337, S1345, S1347) tracks a Gita
Chapter 1-4 research exercise: an initial "mathematical derivation" document with many invented,
under-specified, or outright invalid equations is written, then systematically audited and
corrected into a disciplined philosophical-test-framework methodology (accepted by a formal HPA
ruling), including recovery of much earlier ("Step 155/156") Sarathi-centered conceptual work
that appears to historically pre-date and partially converge with the Gita-derived hypotheses.

## Object index activity

17 new index proposals were registered (none merged into existing labels without a proposal),
including: epistemic-intermediate-representation, kernel-cost-vs-invariant-justification-tension,
context-tuple, assessment-observation-evidence-recommendation-decision-non-collapse,
authority-temporal-validity-gap, minimum-preservation-unit, reconstructible-epistemology,
kernel-removal-membership-test, exp01-evidence-aggregation-operators, status-ladder-committed-
boundary, decision-contract-admissibility, gita-chapter4-mathematical-derivation, measure-theory-
falsification-cascade, session2-cross-track-observation-register, gn46-mathematical-verification-
report, and epistemic-sarathi-see-guide-decide.

Existing labels reused where a clear match existed: zero-lens-bayesian-gaps,
leonardo-context-completeness-lens, sufficient-state-control-theory-lens,
pramana-four-knowledge-sources, gita-tripiti-relationship-lens, lord-lens-horizon-expansion,
sarathi-investigation-guide, formal-zero-algebra, three-kernels-layering,
knowledgeos-kernel-concept, gita-chapter3-validation-exercise (referenced, not reused directly).

## Self-check results

All five mandatory self-checks were run and pass:
- TOTAL INVALID ROWS (types closed-list check): 0
- TOTAL UNREGISTERED LABELS: 0
- valid lines (JSON validity): 169
- TOTAL INCONSISTENT ROWS (unknown_candidate/labels consistency): 0
- TOTAL FIELD-SHAPE ERRORS (files.jsonl field types): 0

One correction made during self-check: 53 contribution rows initially set `unknown_candidate`
while using a specific best-guess label rather than `["UNKNOWN-OBJECT-CANDIDATE"]` — a violation
of the mandatory consistency rule. All 53 were corrected by nulling `unknown_candidate` and
retaining the specific label with its `label_confidence` (mostly UNCERTAIN) unchanged, since in
every case a specific best-guess label was genuinely intended and preserving it (rather than
downgrading to `UNKNOWN-OBJECT-CANDIDATE`) keeps more information.

## Provenance note

Several documents in this batch are themselves synthesis/review artifacts one or more layers
removed from original source material (Session-2 reviews of Session-1 findings; a book chapter
teaching prior findings at finding-strength; disposition analyses of findings registers). These
were graded SECONDARY-SYNTHESIS. Primary-authored artifacts (the Python scripts, the raw Gita
research documents, the master verification report, the book chapter itself, the cross-track
observation register, the final kernel synthesis) were graded PRIMARY.
