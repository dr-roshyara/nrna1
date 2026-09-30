# attribution-declared-not-attested

**Scope(s):** THEORY-LEVEL · **Row count:** 10 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G1827**: [`attribution-declared-not-attested` · `reiter-knowledgeos-derivation-mandate`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0002`, scope `THEORY-LEVEL`: INV-ATTR-2: process/session independence and attribution are Declared, never attestable from the record.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0049 §"Reviewer involvement: NONE ... this independence claim is DECLARED — asserted here, recorded in this artifact, not attestable"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2566. Candidate lifecycle: **CONTESTED**. Evidence: contested by an own-row CONTRADICTION-typed row

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S2561 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2561 |
| dependencies | PRESENT | S2561, S2566 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2561, S2563, S2566 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0058 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Sensing does not transfer cleanly from Reiter to KnowledgeOS: Reiter's sensing relies on knowledge being modelled by a factive accessibility relation, but R1 decided KnowledgeOS's object is A_t (an attribution), and no KnowledgeOS component may assert Knows. [S2561]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0049] types=['ASSUMPTION'] scope=METHODOLOGICAL — "The review's own independence from the artifacts it reviews is stated as a self-declared, non-attestable claim per INV-ATTR-2, consistent with the assurance model's own C4 mandatory-disclosure ladder ('I claim this was independent')." (anchor: "Reviewer involvement: NONE ... this independence claim is DECLARED — asserted here, recorded in this artifact, not attestable")
- [S0055] types=['ASSUMPTION', 'LIMITATION'] scope=METHODOLOGICAL — "The verifier's own process separation from the Phase-A producer cannot be established from the record (one shared committer identity across all lanes) and is recorded as Declared, not attestable, per INV-ATTR-2 -- the same limitation applying to the verification report itself." (anchor: "The Option A separation condition — cannot be established from the record; saying so ... Independence = Declared.")
- [S0056] types=['LIMITATION'] scope=METHODOLOGICAL — "The verifier discloses a bounding limitation on its own independence: although a different process from the corrector, this verification shares the same underlying model (Claude Opus 5) as the v1.1 correction's author, so a shared model blind spot is possible even though process separation is real." (anchor: "DISCLOSED, because it is material: v1.1 was co-authored by the SAME MODEL as this report ... Process separation is real; MODEL separation is not.")
- [S0058] types=['WARNING'] scope=OBJECT — "Verification #2 (the phase-a-v1.1-correction-verification report) is found to have run under no recorded lane, grant, or START at all; its content is not disputed, but its independence cannot be attested from the record, which INV-ATTR-2 already predicts as a limitation." (anchor: "There is no lane, no START and no grant under which Verification #2 was performed. ... its independence is not recorded anywhere.")
- [S0075] types=['LIMITATION'] scope=METHODOLOGICAL — "The verifier discloses partial independence: it is not the same process as any prior BC-7 author, but shares its model with Verification #1's author, judged not to warrant redoing the work but stated for the PO/ARB's own pricing of the residual risk." (anchor: "Independence is PARTIAL ... BC-7 Verification #1 was authored by this verifier's own model.")
- [S0078] types=['ASSUMPTION'] scope=METHODOLOGICAL — "The refinement discloses an exposure limitation on its own independence: it was given the ARB's candidate framing for C-3 before classifying, and states plainly that a refiner never exposed to that framing would have been strictly cleaner, though it reports having tested rather than inherited the candidates." (anchor: "The starting message carried, besides the assignment, the ARB routing analysis, which contains the three ARB-supplied C-3 candidate outcomes ... A refiner who had never seen the framing would be strictly cleaner")
- [S2561] types=['EXPLANATION', 'DISTINCTION'] scope=CROSS-OBJECT — "Sensing does not transfer cleanly from Reiter to KnowledgeOS: Reiter's sensing relies on knowledge being modelled by a factive accessibility relation, but R1 decided KnowledgeOS's object is A_t (an attribution), and no KnowledgeOS component may assert Knows." (anchor: "Reiter's sensing works because knowledge is modelled by an **accessibility relation** and is **factive**. `R1` decided that KnowledgeOS's object is `A_t`, an attribution, and that no component may assert `Knows`.")
- [S2561] types=['CONTRADICTION', 'CONSTRAINT'] scope=CROSS-OBJECT — "[NEG]-tagged finding: Reiter's knowledge operator and KnowledgeOS's object are different predicates BY GOVERNANCE DECISION, not by oversight; therefore sensing's formal guarantees, which are guarantees about a factive operator, do not carry over to attribution." (anchor: "[NEG] Reiter's knowledge operator and KnowledgeOS's object are different predicates by governance decision, not by oversight. Sensing's formal guarantees are guarantees about a factive operator and do not carry over to attribution.")
- [S2563] types=['RESTATEMENT', 'RETRACTION'] scope=CROSS-OBJECT — "Explicit non-candidates list, each pointing to its refuting/excluding deliverable: Golog/RGolog is unreachable until delta resolves (deliverable 10); Reiter's Knows operator is excluded by governance decision R1 (deliverable 07); Situation=A_t is refuted (deliverable 03); Poss-as-Qualify is refuted (deliverable 07)." (anchor: "Not candidates: Golog / RGolog -- unreachable until delta resolves (10) - Reiter's Knows -- excluded by R1 (07) - Situation = A_t -- refuted (03) - Poss as Qualify -- refuted (07).")
- [S2566] types=['RESTATEMENT'] scope=CROSS-OBJECT — "Restates two governing pre-existing findings used throughout the Reiter derivation programme: R1 established KnowledgeOS's object is A_t (an attribution), with no component permitted to assert Knows; Step 291 established operation-registry membership, signatures, bodies, identity, and ratification are all OPEN." (anchor: "R1: KnowledgeOS's object is A_t, an attribution; no component may assert Knows. Step 291: operation-registry membership, signatures, bodies, identity, ratification -- all OPEN.")

## Notes for P3
(none beyond what is noted above)
