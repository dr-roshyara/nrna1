# congruence-necessary-not-sufficient-invariant-expressibility-gap

**Scope(s):** OBJECT · **Row count:** 12 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `SO-EXP-02` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0042, scope OBJECT): SO-EXP-02 shows that K=(A,R)'s congruence for the Merge operation (found by SO-EXP-01) holds only vacuously: the F4=K=(A,R) abstraction projects away the merge-provenance record that a 'sensitive' operation variant is meant to preserve (Prov(x3)>=Prov(x1)∪Prov(x2), s256.9/s257.20), so the invariant is inexpressible as a predicate on F4 even though F4 is congruent for the operation that must maintain it. Conclusion: the corpus's own congruence criterion (s259.15) is necessary but not sufficient for state adequacy; a second, uncomposed criterion is required — every mandatory invariant must be expressible as a predicate on the candidate state — and the corpus states the two criteria separately (259.15 and 256.9) without ever composing them.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1755] §"READING: F4 is congruent for Merge/sensitive precisely BECAUSE F4 projects
  away the `merged` record."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S1758] §"QUESTION   SO-EXP-02 showed congruence is necessary but not sufficient: an abstraction can be congruent for T while unable to state the invariant T must maintain."
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1769. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1755, S1760, S1767 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1755, S1766, S1767 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | PRESENT | S1755, S1758, S1760, S1769 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
SO-EXP-02's derived result: K=(A,R) is simultaneously congruent for every tested mandatory operation (SO-EXP-01) AND unable to express the corpus's own named merge-provenance invariant (Prov(x3)>=Prov(x1)∪Prov(x2), s256.9/s257.20) — these two facts are consistent, and together prove the corpus's stated congruence criterion (s259.15) is necessary but not sufficient for state adequacy; a second criterion (every mandatory invariant must be expressible as a predicate on the candidate state) is required, and the corpus states the two criteria separately without ever composing them — the same 'correct local results never composed' pattern the first-order pass also recorded [S1755]. SO-EXP-02 shows F4's congruence for Merge/sensitive is vacuous (it projects away the merge-provenance record the sensitive variant exists to preserve), establishing that a second, corpus-unstated criterion is required: every mandatory invariant I must be expressible as a predicate on the candidate state [S1760]. Four second-order findings summarised: SO-1 the previously-UNRESOLVED 259.9 congruence matrix is computed (K=(A,R) congruent for all class-1 operations, both variants, 208-state domain); SO-2 congruence is necessary but not sufficient (Merge/sensitive passes vacuously); SO-3 the invariant audit finds 5/6 mandated invariants expressible with a corpus-supplied repair for the sixth; SO-4 after reclassification 17/57 gaps are genuine theoretical holes, and all five remaining CRITICAL ones converge on Sigma [S1767].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1755] types=[EXPERIMENTAL-RESULT, LIMITATION] scope=THEORY-LEVEL — "SO-EXP-02 demonstrates that F4=K=(A,R)'s congruence for the Merge/sensitive operation is satisfied vacuously: F4(blind result) and F4(sensitive result) are identical only because F4 projects away the `merged` provenance record that the sensitive variant exists to populate; F5 (K=(A,R)+merge-source), by contrast, does distinguish them." (anchor: "READING: F4 is congruent for Merge/sensitive precisely BECAUSE F4 projects
  away the `merged` record.")
- [S1755] types=[ARGUMENT, PRINCIPLE] scope=THEORY-LEVEL — "SO-EXP-02's derived result: K=(A,R) is simultaneously congruent for every tested mandatory operation (SO-EXP-01) AND unable to express the corpus's own named merge-provenance invariant (Prov(x3)>=Prov(x1)∪Prov(x2), s256.9/s257.20) — these two facts are consistent, and together prove the corpus's stated congruence criterion (s259.15) is necessary but not sufficient for state adequacy; a second criterion (every mandatory invariant must be expressible as a predicate on the candidate state) is required, and the corpus states the two criteria separately without ever composing them — the same 'correct local results never composed' pattern the first-order pass also recorded." (anchor: "=> DERIVED RESULT.  The terminal model K=(A,R) is:
       * CONGRUENT for every mandatory state-transforming operation tested
       ... AND
       * UNABLE TO EXPRESS the merge-provenance invariant")
- [S1758] types=[EXPERIMENT] scope=METHODOLOGICAL — "SO-EXP-04 method: for each of six mandated invariants I, construct two states that F4 identifies but on which I differs; if such a pair exists, I is inexpressible in F4. Tests I1 (merge preserves provenance, s265.11), I2 (Reject leaves object in K, s256.11), I3 (Withdraw leaves object in K, s256.12), I4 (provenance never empty), I5 (supersession recorded, s256.8/257.13), I6 (Split preserves lineage to origin, s265.12)." (anchor: "QUESTION   SO-EXP-02 showed congruence is necessary but not sufficient: an abstraction can be congruent for T while unable to state the invariant T must maintain.")
- [S1758] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Executed result: K=(A,R) expresses 5 of 6 mandated invariants located in the corpus (I2-I6 expressible, I1 merge-provenance inexpressible). The single failure has one structural cause: a merged or split object carries a single provenance slot and so cannot record two sources." (anchor: "EXPRESSIBLE in the terminal K=(A,R):   5/6 ... INEXPRESSIBLE in the terminal K=(A,R): 1/6 ... I1  Merge preserves provenance association")
- [S1758] types=[CORRECTION, EXTENSION] scope=OBJECT — "The corpus itself (s265.19) already supplies the repair for I1: making the provenance field pi a set-valued or relation-backed reference (rather than a single slot) makes I1 and I6 expressible with no other change to the model -- this is characterised as an engineering consequence of a corpus-stated invariant, not a normative choice." (anchor: "s265.19's own proposed repair already fixes it: A = (id,P,e,c,t,pi) where pi is a provenance REFERENCE, plus ResolveProvenance: PI -> ProvenanceObject.")
- [S1760] types=[EXPERIMENTAL-RESULT, ARGUMENT] scope=THEORY-LEVEL — "SO-EXP-02 shows F4's congruence for Merge/sensitive is vacuous (it projects away the merge-provenance record the sensitive variant exists to preserve), establishing that a second, corpus-unstated criterion is required: every mandatory invariant I must be expressible as a predicate on the candidate state." (anchor: "F4 passes Merge/sensitive only because F4 cannot see what the sensitive variant preserves. ... Congruence is NECESSARY but NOT SUFFICIENT for state adequacy.")
- [S1760] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "SO-EXP-04's invariant-expressibility audit result restated within the operation-universe analysis: 5 of 6 mandatory invariants expressible in K=(A,R), single failure (I1 merge-provenance) with a corpus-supplied repair (s265.19)." (anchor: "5 of 6 expressible. The single failure has one structural cause: a merged or split object carries one provenance slot and therefore cannot record that it derives from two sources.")
- [S1766] types=[DISTINCTION] scope=THEORY-LEVEL — "Seven items are intentional deployment parameters (product/domain acts, not theory acts): T_class-1 for the deployment, J_mandatory (the invariant set -- flagged as 'the one that matters', per SO-EXP-02/04), merge conflict-resolution rule, per-dimension value membership V_D, probability regime (none by design), the logic layer, and H's retention policy." (anchor: "J_mandatory (the invariant set) | product | the live one -- SO-EXP-02/04: this determines state adequacy, and only 6 invariants were locatable")
- [S1767] types=[RESTATEMENT, ANALYSIS] scope=THEORY-LEVEL — "Four second-order findings summarised: SO-1 the previously-UNRESOLVED 259.9 congruence matrix is computed (K=(A,R) congruent for all class-1 operations, both variants, 208-state domain); SO-2 congruence is necessary but not sufficient (Merge/sensitive passes vacuously); SO-3 the invariant audit finds 5/6 mandated invariants expressible with a corpus-supplied repair for the sixth; SO-4 after reclassification 17/57 gaps are genuine theoretical holes, and all five remaining CRITICAL ones converge on Sigma." (anchor: "SO-1 (EXECUTION EVIDENCE) -- the congruence matrix is computed. ... SO-2 (DERIVATION, new) -- congruence is necessary but not sufficient. ... SO-3 (EXECUTION EVIDENCE) -- the invariant audit. ... SO-4 -- the frontier.")
- [S1768] types=[VALIDATION] scope=OBJECT — "Raw execution output confirming the per-abstraction (F1-F6) pass/fail table for all six mandated invariants, validating the 5/6-expressible, I1-fails result reported in so_exp04.py and doc 01." (anchor: "I1  Merge preserves provenance association         265.11 (boxed)              no     no     no     no    YES    YES")
- [S1769] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Concrete witness: merging the same two objects under the 'blind' and 'sensitive' operation variants produces the SAME F4=K=(A,R) projection (F4 does not distinguish them, so is congruent) but DIFFERENT F5 (K=(A,R)+mergesrc) projections, demonstrating F4's congruence for Merge/sensitive is achieved only by projecting away exactly the provenance record the sensitive variant exists to preserve." (anchor: "Merge blind      -> objs=['z:(p+q)@internal/Proposed']  merged=[] ... F4(blind result)     = ((('z', '(p+q)', 'internal', 'Proposed'),), ()) ... F4 distinguishes them? False ... F5 distinguishes them? True")
- [S1769] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Full per-abstraction table for the corpus merge-provenance invariant Prov(x3) >= Prov(x1) union Prov(x2) (s256.9/s257.20): F1-F4 all identify the holds/violates states (so cannot express the invariant); F5/F6 distinguish them (so can). Concludes congruence (s259.15) is necessary but not sufficient, and the corpus states the congruence criterion and the invariant separately without ever composing them -- the same 'correct local results never composed' pattern recorded by the first-order pass." (anchor: "abstraction                identifies the two states?   invariant expressible? ... F4 K=(A,R) [TERMINAL]      True                         NO ... F5 K=(A,R)+mergesrc        False                        YES")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
