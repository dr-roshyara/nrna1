# knowledge-lint-coverage-gap

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** 16 rule identifiers, no evidence/support/transition-legality rule · **Aliases:** EXP-14
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0041, scope OBJECT): knowledge-lint.php is measured to emit 16 distinct rule identifiers covering structure (referential integrity, cycles, single-authoritative, orphans) but none for status-transition legality or evidence/epistemic support.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1682] §"'superseded' and 'archived' are given HIGHER order numbers than 'baseline' and 'frozen'. ... The order field therefore encodes two different things in one integer: progression (1-6) and retirement (7-8)."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1703. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1682, S1703 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1682 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1682, S1703 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S1682] types=['EXPERIMENTAL-RESULT', 'COUNTEREXAMPLE'] scope=OBJECT — "EXP-13 shows the EKP lifecycle's single 'order' integer is not monotone: it allows draft->frozen (skipping four states) and both frozen->superseded and approved->superseded under a naive order-increasing rule, while rejecting a legitimate un-supersession (superseded->approved); concludes order conflates progression (1-6) with retirement (7-8) and that a covering-relation of adjacent-pair transitions is required but absent, so the lint (EXP-14) passes because it checks membership only, never transition legality." (anchor: "'superseded' and 'archived' are given HIGHER order numbers than 'baseline' and 'frozen'. ... The order field therefore encodes two different things in one integer: progression (1-6) and retirement (7-8).")
- [S1682] types=['EXPERIMENTAL-RESULT', 'VALIDATION'] scope=OBJECT — "EXP-14 greps scripts/knowledge-lint.php for emittable rule identifiers, finds 16, and classifies them against five theory-relevant checks (referential integrity, cycle detection, single-authoritative, status-transition legality, evidence/epistemic support), finding the last two absent -- confirming from the implementation side that the running system enforces structure but has no Sigma." (anchor: "The linter enforces STRUCTURE ... It has NO rule for status-transition legality and NO rule mentioning evidence or epistemic support -- confirming EXP-12 from the code side.")
- [S1703] types=['EXPERIMENTAL-RESULT', 'LIMITATION'] scope=CROSS-OBJECT — "Finding SG-7: measuring the real EKP finds only two axes implemented (status as a lifecycle/governance-process axis with 4 distinct values across 38 documents; authority as a source-trust axis with 4 values; 7 observed joint pairs confirming independence), and zero of the five theoretical Sigma axes (evidence, supersession-as-state, validity, asked) as enforced concepts -- no field records evidential support, no lint rule (of 16 listed identifiers) mentions evidence; concludes Sigma has never been tested by reality (no instance) while the EKP itself has no mechanism at all for epistemic status, so a factually-refuted document with status=approved/authority=authoritative has no way to record that fact." (anchor: "The running system has TWO of the five axes ... and ZERO of the evidence, supersession-as-state, validity, or asked axes as ENFORCED concepts. No field records whether a claim is supported by evidence; no lint rule mentions evidence. ... Against the theory: Sigma has never been tested by reality. It has no instance.")
- [S1703] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=OBJECT — "Finding SG-8: independently corroborates (from a different analysis angle) that statuses.yaml's single order integer conflates progression and retirement, with the order-rule permitting draft(1)->frozen(6) (skipping four states) and approved(4)->superseded(7), while forbidding a legitimate un-supersession superseded(7)->approved(4); concludes the schema needs a covering relation rather than a total order, and that the lint passes only because it checks vocabulary membership, never transition legality." (anchor: "The EKP's own two axes are also not clean. statuses.yaml gives a single integer order 1..8 that encodes TWO different things: progression ... and retirement ... The schema declares a total order and the lifecycle needs a COVERING RELATION. The lint passes because it validates MEMBERSHIP in the vocabulary and never validates a TRANSITION.")

## Notes for P3
- No internal tension, evidentiary anomaly, or lifecycle-flag discrepancy observed in this label's own rows beyond what the completeness roll-up above already shows.
