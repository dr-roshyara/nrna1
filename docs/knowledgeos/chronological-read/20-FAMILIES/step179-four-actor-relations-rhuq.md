# step179-four-actor-relations-rhuq

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** R!=H!=U!=Q, R⊆A×X×T capability, H⊆A×X×T responsibility, U⊆A×X×T authority, Q⊆A×X×T accountability · **Aliases:** Capability/Responsibility/Authority/Accountability as four relations over Actor×Action×Time
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0033`, scope `THEORY-LEVEL`: Step 179 formalizes Capability, Responsibility, Authority, Accountability as four distinct relations R,H,U,Q subseteq Actors x Actions x Time, generally R!=H!=U!=Q -- described as a simple formalization capturing 'a surprisingly large portion of enterprise governance confusion.' Worked example: an infrastructure engineer Can(Engineer,MigrationTest)=true and Responsible(Engineer,MigrationTest)=true, producing Evidence_1 and Determination_1=TestSuccessful, but this does not imply Authorized(Engineer,ProductionMigration)=true. Names the dangerous collapse pattern (engineer discovers -> decides -> executes, or the AI equivalent: reads repo -> finds problem -> determines solution -> changes code -> deploys) via CanDiscover!=>MayDecide and MayDecide!=>MayExecute, and requires Can(AI,Action) AND Authorized(AI,Action) jointly, never Can alone, before execution.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1372] §"R⊆A×X×T as the capability relation. ... H⊆A×X×T as the responsibility relation. ... U⊆A×X×T as the authority relation. ... Q⊆A×X×T as accountability. Then generally: R≠H≠U≠Q. That simple formalization captures a surprisingly large portion of enterprise governance confusion. ... CanDiscover ⇏ MayDecide and: MayDecide ⇏ MayExecute."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1372] §"R⊆A×X×T as the capability relation. ... H⊆A×X×T as the responsibility relation. ... U⊆A×X×T as the authority relation. ... Q⊆A×X×T as accountability. Then generally: R≠H≠U≠Q. That simple formalization captures a surprisingly large portion of enterprise governance confusion. ... CanDiscover ⇏ MayDecide and: MayDecide ⇏ MayExecute."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1372. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1372), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1372 |
| type_signature | PRESENT | S1372 |
| invariants | PRESENT | S1372 |
| dependencies | PRESENT | S1372 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1372] types=[FORMALIZATION] scope=THEORY-LEVEL — "Formalizes Capability, Responsibility, Authority, Accountability as four distinct relations R,H,U,Q subseteq Actors x Actions x Time, generally R!=H!=U!=Q, said to capture 'a surprisingly large portion of enterprise governance confusion'; worked infrastructure-engineer example (Can+Responsible for a migration test, producing evidence and a determination, without thereby being Authorized for production migration); names the dangerous discover->decide->execute collapse (for a human engineer or an AI agent reading a repo, finding a problem, and deploying a fix) via CanDiscover!=>MayDecide and MayDecide!=>MayExecute, requiring Can(AI,Action) AND Authorized(AI,Action) jointly before execution." (anchor: "R⊆A×X×T as the capability relation. ... H⊆A×X×T as the responsibility relation. ... U⊆A×X×T as the authority relation. ... Q⊆A×X×T as accountability. Then generally: R≠H≠U≠Q. That simple formalization captures a surprisingly large portion of enterprise governance confusion. ... CanDiscover ⇏ MayDecide and: MayDecide ⇏ MayExecute.")

## Notes for P3
- Agent observation: this label has only 1 recorded row(s); evidence base is thin and the classification above should be read as provisional pending further capture.
