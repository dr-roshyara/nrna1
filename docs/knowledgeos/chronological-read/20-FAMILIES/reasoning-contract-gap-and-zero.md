# reasoning-contract-gap-and-zero

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Delta_Cog(K,RCog), RCog, Zero_Cog(K,RCog) · **Aliases:** none recorded

**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0067, scope THEORY-LEVEL: Formal reasoning contract, reasoning knowledge gap, and reasoning-zero (contractual completeness, not truth/decision-correctness/action-success); also the full inquiry-to-action pipeline with non-skippable transitions.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2785 §"RCog = <Question,InputTypes,RequiredPremises,AllowedRules,AllowedModels,Assumptions,ConflictPolicy,ResourceBounds,ValidityCriteria,OutputSemantics,ProvenanceRequirements>. ... Delta_Cog(K,RCog) ... Zero_Cog(K,RCog) iff Delta_Cog=empty ... does not mean Truth(q)=1 ... Nor Decision=Correct. Nor Action"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2785 §"RCog = <Question,InputTypes,RequiredPremises,AllowedRules,AllowedModels,Assumptions,ConflictPolicy,ResourceBounds,ValidityCriteria,OutputSemantics,ProvenanceRequirements>. ... Delta_Cog(K,RCog) ... Zero_Cog(K,RCog) iff Delta_Cog=empty ... does not mean Truth(q)=1 ... Nor Decision=Correct. Nor Action"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S2785. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This ACTIVE classification is a heuristic based on how recently (by source_id, last_seen=S2785) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2785 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
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

- `[S2785]` types=[FORMALIZATION] scope=THEORY-LEVEL — "21.44-21.46: formalizes an 11-field Reasoning Contract RCog=<Question,InputTypes,RequiredPremises,AllowedRules,AllowedModels,Assumptions,ConflictPolicy,ResourceBounds,ValidityCriteria,OutputSemantics,ProvenanceRequirements> determining what counts as an acceptable reasoning result (e.g. one contract may allow heuristic inference, another require formally verified deduction); defines the Reasoning Knowledge Gap Delta_Cog(K,RCog)={r∈Req(RCog): ¬Sat(K,r)} giving a structured answer to 'why can this conclusion not yet be determined'; defines Reasoning Zero Zero_Cog(K,RCog) iff Delta_Cog=∅ as contractual completeness only -- explicitly not Truth(q)=1, not Decision=Correct, not Action=Successful." (anchor: "RCog = <Question,InputTypes,RequiredPremises,AllowedRules,AllowedModels,Assumptions,ConflictPolicy,ResourceBounds,ValidityCriteria,OutputSemantics,ProvenanceRequirements>. ... Delta_Cog(K,RCog) ... Zero_Cog(K,RCog) iff Delta_Cog=empty ... does not mean Truth(q)=1 ... Nor Decision=Correct. Nor Action")
- `[S2785]` types=[FORMALIZATION, CONSTRAINT] scope=THEORY-LEVEL — "21.47: presents the complete boxed pipeline Inquiry->Query->Retrieval->Evidence->Premises->Reasoning->Proof->Verification->Determination->Decision->Authorization->Action, each transition carrying a distinct semantic responsibility, and states the reasoning engine cannot skip Proof->Verification when the contract requires it, cannot skip Verification->Determination, and cannot skip Determination->Decision because determination and decision are different domain acts." (anchor: "Inquiry -> Query -> Retrieval -> Evidence -> Premises -> Reasoning -> Proof -> Verification -> Determination -> Decision -> Authorization -> Action. ... it cannot skip Determination -> Decision because determination and decision are different domain acts.")

## Notes for P3

All 2 rows trace to a single source document (S2785); the evidentiary base for this label is broad in row count but narrow in provenance (one authoring pass, one document).
