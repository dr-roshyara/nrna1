# determination-tuple-with-lineage

**Scope(s):** OBJECT · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** D=(Claim,Evidence,Assessment,Actor,Role,Authority,Rule,Scope,Time,Lineage) · **Aliases:** canonical determination structure
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope OBJECT: "Step 196's consolidated ten-field Determination tuple and the eight canonical provenance questions every authoritative determination should answer; also carries the extended Transition tuple with Authority and the six-dimension architecture consolidation (Identity/Time/Evidence/Causality/EpistemicStatus/Authority)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1414 §"\\tau=(S_1,S_2,Rule,Witness,Actor,Authority,Time) ... Authorized(a,\\tau,C,t)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1414 §"\\tau=(S_1,S_2,Rule,Witness,Actor,Authority,Time) ... Authorized(a,\\tau,C,t)."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1414. Candidate lifecycle: DORMANT. Evidence: retracted_by empty, superseded_by empty, contested_by_own_contradiction_type false — nothing in this label's own rows claims retraction, supersession, or internal contradiction. This is a single-source-document label (all 5 rows and the sole `files_touching` entry are S1414); DORMANT is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement — with only one document behind it, there is also no corroborating evidence one way or the other about later use.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1414 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1414 (x4) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1414 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The single rationale-evidence row is a restatement/analysis row rather than an argument or explanation: it consolidates the architecture's six accumulated major dimensions (Identity, Time, Evidence, Causality, EpistemicStatus, Authority) with Governance and Action as the transition mechanisms operating over them [S1414]. This positions the Determination tuple as the point where those six dimensions plus authority/procedure questions converge into one object — the row set as a whole (not just the rationale-typed row) shows the motivating question was "what must an authoritative determination be able to answer" (who made it, in which role, under which authority, under which rule, based on which evidence, at what time, for which scope, was delegation involved) [S1414], with the tuple offered as the consolidated answer to those eight questions. rationale_truncated_count is 0 — no further rationale rows exist beyond what's shown.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S1414] types=[FORMALIZATION, EXTENSION] scope=THEORY-LEVEL — "Extends the Transition tuple to explicitly include Authority (S1,S2,Rule,Witness,Actor,Authority,Time), and defines an Authorized(a,tau,C,t) predicate that a valid governed transition must satisfy." (anchor: "\\tau=(S_1,S_2,Rule,Witness,Actor,Authority,Time) ... Authorized(a,\\tau,C,t).")
- [S1414] types=[FORMALIZATION, EXTENSION] scope=OBJECT — "Refines the earlier Evidence->Assessment->Determination chain by adding Authority as an explicit condition: Determination = Assessment + AuthorizedActor + ValidProcedure." (anchor: "Determination = Assessment + AuthorizedActor + ValidProcedure.")
- [S1414] types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Lists the eight questions every authoritative determination should answer and consolidates them into the canonical Determination tuple D=(Claim,Evidence,Assessment,Actor,Role,Authority,Rule,Scope,Time,Lineage), called 'a powerful canonical structure.'" (anchor: "Who made it? In which role? Under which authority? Under which rule? Based on which evidence? At what time? For which scope? Was delegation involved? ... D=(Claim,Evidence,Assessment,Actor,Role,Authority,Rule,Scope,Time,Lineage).")
- [S1414] types=[RESTATEMENT, ANALYSIS] scope=THEORY-LEVEL — "Consolidates the architecture's six accumulated major dimensions (Identity, Time, Evidence, Causality, EpistemicStatus, Authority) with Governance and Action as the transition mechanisms operating over them." (anchor: "Identity, Time, Evidence, Causality, EpistemicStatus, Authority ... with: Governance and: Action forming the transition mechanisms.")
- [S1414] types=[FORMALIZATION] scope=THEORY-LEVEL — "States the near-final core mathematical object: ValidTransition(tau) = Preconditions AND Evidence AND Authority AND Rules AND Invariants, producing a new state plus immutable lineage." (anchor: "ValidTransition(\\tau) = P(\\tau) \\land E(\\tau) \\land A(\\tau) \\land R(\\tau) \\land I(\\tau) ... The transition itself produces a new state and immutable lineage.")

## Notes for P3
- Own observation: this is a thin, single-document label (5 rows, one source_id, one file). The internal progression reads as coherent (Transition tuple gains Authority -> Determination gains Authority -> eight canonical questions consolidated into the ten-field tuple -> restated against the six-dimension architecture -> folded into ValidTransition), but with only one document behind it there is no independent corroboration within this label's own evidence.
- Own observation: `group_ids` is empty for this label despite its close notational overlap with `determination-tuple-with-lineage`'s own working_label token space and with other tuple-shaped objects (e.g. Transition tuple tau, ValidTransition) referenced inline in its own rows — P3 may want to check whether a mechanical pass simply missed pairing this with sibling tuple/transition-family labels, since no group linkage exists here to flag even a "relationship not yet decided" note.
- Own observation: the "Lineage" field of the tuple is asserted by name in S1414 but this label's own rows contain no row that defines Lineage's semantics beyond appearing as the tenth tuple component and (separately) as "immutable lineage" produced by ValidTransition — informal_meaning is NOT-EVIDENCED-IN-CAPTURE for the tuple as a whole, and Lineage specifically has no dedicated definition row here.
