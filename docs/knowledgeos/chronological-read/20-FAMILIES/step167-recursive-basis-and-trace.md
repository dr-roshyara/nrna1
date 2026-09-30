# step167-recursive-basis-and-trace

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** AssuranceScope(x), Assured(x) iff RequiredEvidence(x,AssuranceScope) available and valid, Basis(x), Reconstructible(x), Trace(x) = x ∪ (∪_{b in Basis(x)} Trace(b)) · **Aliases:** recursive lineage reconstruction formalism
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0033`, scope `THEORY-LEVEL`: Step 167's recursive formalization of reconstructible lineage: defines Basis(x) per concept (e.g. Basis(Decision)={Authority,DeterminationVersion,Policy,Time}; Basis(Determination)={KnowledgeVersion,EvidenceSet,Method,Context}; Basis(Knowledge)={EvidenceSet,Evaluation,Provenance}), then Trace(x) = x union the union of Trace(b) for b in Basis(x), terminating at primitive evidence/external observations; Reconstructible(x) holds when Trace(x) can be completely resolved, giving a candidate governance invariant DecisionMade(D) => Reconstructible(D). Explicitly bounds this with a defined Scope(Trace(x)) / AssuranceScope(x) (all information required to explain and verify under the applicable governance policy) to prevent an infinite lineage requirement, yielding Assured(x) iff RequiredEvidence(x,AssuranceScope) is available and valid -- judged far more precise than an unqualified claim that 'the system is auditable.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1360] §"Basis(D) = {Authority, DeterminationVersion, Policy, Time}. ... Trace(x) = x + ∪_{b∈Basis(x)}Trace(b). Eventually we reach primitive evidence or external observations. ... Reconstructible(x) = Trace(x) can be completely resolved. ... DecisionMade(D) ⇒ Reconstructible(D). ... Scope(Trace(D)). ... Assured(x) ⟺ RequiredEvidence(x,AssuranceScope) is available and valid. This is much more precise than: 'The system is auditable.'"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1360] §"Basis(D) = {Authority, DeterminationVersion, Policy, Time}. ... Trace(x) = x + ∪_{b∈Basis(x)}Trace(b). Eventually we reach primitive evidence or external observations. ... Reconstructible(x) = Trace(x) can be completely resolved. ... DecisionMade(D) ⇒ Reconstructible(D). ... Scope(Trace(D)). ... Assured(x) ⟺ RequiredEvidence(x,AssuranceScope) is available and valid. This is much more precise than: 'The system is auditable.'"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1360. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1360), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1360 |
| type_signature | PRESENT | S1360 |
| invariants | PRESENT | S1360 |
| dependencies | PRESENT | S1360 |
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
- [S1360] types=[FORMALIZATION] scope=THEORY-LEVEL — "Defines a per-concept Basis(x) (e.g. Basis(Decision)={Authority,DeterminationVersion,Policy,Time}; Basis(Determination)={KnowledgeVersion,EvidenceSet,Method,Context}; Basis(Knowledge)={EvidenceSet,Evaluation,Provenance}) and a recursive Trace(x)=x union union_{b in Basis(x)} Trace(b), terminating at primitive evidence/external observations; Reconstructible(x) holds when Trace(x) fully resolves, giving the candidate invariant DecisionMade(D)=>Reconstructible(D). Bounds this by an explicit Scope(Trace(x))/AssuranceScope(x) to avoid an infinite lineage requirement, yielding Assured(x) iff RequiredEvidence(x,AssuranceScope) is available and valid -- judged far more precise than an unqualified 'the system is auditable.'" (anchor: "Basis(D) = {Authority, DeterminationVersion, Policy, Time}. ... Trace(x) = x + ∪_{b∈Basis(x)}Trace(b). Eventually we reach primitive evidence or external observations. ... Reconstructible(x) = Trace(x) can be completely resolved. ... DecisionMade(D) ⇒ Reconstructible(D). ... Scope(Trace(D)). ... Assured(x) ⟺ RequiredEvidence(x,AssuranceScope) is available and valid. This is much more precise than: 'The system is auditable.'")

## Notes for P3
- Agent observation: this label has only 1 recorded row(s); evidence base is thin and the classification above should be read as provisional pending further capture.
