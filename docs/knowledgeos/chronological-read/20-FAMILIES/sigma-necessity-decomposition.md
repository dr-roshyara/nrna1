# sigma-necessity-decomposition

**Scope(s):** `OBJECT` · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `5 orthogonal axes: asked/evidence/authority/supersession/validity`, `decision-signature method` · **Aliases:** `EXP-21..EXP-23`
**Candidate group membership (NOT an identity claim):**
- **G0417**: [`epistemic-vs-governance-status-orthogonality` · `sigma-necessity-decomposition`] — explicit agent-stated uncertainty: 'sigma-necessity-decomposition' POSSIBLY relates to 'epistemic-vs-governance-status-orthogonality' (batch B0041). Note: A vocabulary-independent necessity test: ten constructed situations are checked against nine theory-required downstream decisions to derive how many distinct status values and axes are mathematically necessary, finding at least five orthogonal irreducible facts (asked, evidence, authority, supersession, validity) plus a sixth unmodeled fact (an open contest process) -- refuting any single-axis or naive single-enum Sigma.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0041, scope OBJECT): A vocabulary-independent necessity test: ten constructed situations are checked against nine theory-required downstream decisions to derive how many distinct status values and axes are mathematically necessary, finding at least five orthogonal irreducible facts (asked, evidence, authority, supersession, validity) plus a sixth unmodeled fact (an open contest process) -- refuting any single-axis or naive single-enum Sigma.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1702] §"A single status enum must encode the cross-product of every fact any decision reads. ... A one-dimensional Sigma is provably inadequate: it would need {n} values. The distinctions are NOT one axis. They are at least FIVE orthogonal facts."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1702] §"A single status enum must encode the cross-product of every fact any decision reads. ... A one-dimensional Sigma is provably inadequate: it would need {n} values. The distinctions are NOT one axis. They are at least FIVE orthogonal facts."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S1703`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1702 |
| type_signature | PRESENT | S1702 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1702, S1702, S1703, S1703 |
| assumptions | PRESENT | S1702 |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1702, S1702, S1703, S1703 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
| Statement | Stated | Source ID | Anchor |
|---|---|---|---|
| a status value is necessary iff two situations distinguishable by a required decision are not distinguishable by any other status value in the set | EXPLICIT | S1702 | "METHOD (necessity test): a status value S is NECESSARY iff..." |

## All rows (source_id order)
- `[S1702]` types=[EXPERIMENTAL-RESULT, FORMALIZATION] scope=OBJECT — "EXP-21/22 define ten constructed situations (never-asked, asked-nothing-found, one-supporting-item, meets-policy-bar, support+accepted, support+rejected, refuting-evidence, both-directions, replaced-by-newer, was-valid-now-expired) and nine theory-required downstream decisions as functions of each situation, computing a 'decision signature' per situation; groups situations by identical signatures to find how many distinct situations must be distinguishable, then maps each decision to the underlying facts it reads (asked, evidence, authority, supersession, validity), showing the worst-case cross-product of these five facts' arities (2x4x3x2x2=96) would be needed by any single-dimensional status enum, proving a one-dimensional Sigma is provably inadequate and the distinctions form at least five orthogonal facts, not one axis." (anchor: "A single status enum must encode the cross-product of every fact any decision reads. ... A one-dimensional Sigma is provably inadequate: it would need {n} values. The distinctions are NOT one axis....")
- `[S1702]` types=[EXPERIMENTAL-RESULT, VALIDATION] scope=OBJECT — "EXP-23 tests each of the five candidate axes (asked, evidence, authority, supersession, validity) for irreducibility by dropping it and checking whether any two situations that a required decision must separate become identical (a 'harmful collision'); finds each axis is irreducible; concludes the corpus's nine status words (Unknown/Supported/Refuted/Conflicted reading the evidence axis; Accepted/Rejected reading the authority axis; Superseded reading the supersession axis; Invalidated/Stale reading the validity axis) conflate four independent axes when placed in a single enum, and identifies Contested as reading a sixth, entirely unmodeled fact (an open process) -- concluding the vocabulary question is downstream of this axis-count finding and does not itself require a human decision." (anchor: "Every axis whose removal produces a HARMFUL collision is IRREDUCIBLE ... This is the necessity demonstration the mandate asks for, and it is produced WITHOUT choosing any vocabulary. ... Putting th...")
- `[S1703]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Finding SG-1: all ten corpus-derived situations produce mutually distinct decision signatures under the nine required decisions, so no situation is redundant and any Sigma with fewer than ten distinguishable states cannot support the corpus's own required decisions, ruling out the corpus's running three-value ladder (Candidate<Supported<Accepted, verified running in ladder_dc_reference.py) as a complete Sigma -- it is adequate only as one axis among several." (anchor: "Ten situations were modelled from corpus material ... 10 situations -> 10 distinct decision signatures (no two situations produce the same set of decisions). ... any Sigma carrying fewer than ten d...")
- `[S1703]` types=[EXPERIMENTAL-RESULT, CORRECTION] scope=THEORY-LEVEL — "Finding SG-2: maps each of nine required decisions to the underlying fact(s) it reads (asked, evidence, authority, supersession, validity), computes the worst-case cross-product (96 states) that a single-dimensional enum would need, and concludes Sigma requires five orthogonal axes, not one; assigns each corpus status word to its actual axis (Unknown/Supported/Refuted/Conflicted->evidence; Accepted/Rejected->authority; Superseded->supersession; Invalidated/Stale->validity; Contested->a process fact; Candidate->ambiguous across two axes), showing nine words in one enum conflate four independent axes; states this subsumes but strictly refines the corpus's own EpistemicStatus!=GovernanceStatus separation (ARC D) as correct-but-insufficient (2 axes separated where 5 are needed)." (anchor: "A one-dimensional Sigma would need 96 values. The distinctions the corpus requires are FIVE orthogonal facts, not one enum. ... Putting these nine words in one enum conflates four independent axes....")

## Notes for P3
- No additional observations beyond what is captured above; nothing about this label's own rows struck this reviewer as unusual relative to its evidentiary base.
