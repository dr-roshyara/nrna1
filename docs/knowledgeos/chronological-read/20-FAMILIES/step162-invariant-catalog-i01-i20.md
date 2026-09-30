# step162-invariant-catalog-i01-i20

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `I-01..I-20`
**Aliases:** `Invariant and Boundary Derivation catalog`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 162's twenty numbered candidate domain invariants (I-01 Observation integrity: Observation does not imply Conclusion; I-02 Evidence traceability: Evidence->Source/Observation; I-03 Knowledge requires epistemic Status(K); I-04 Knowledge history must be preservable (K1 superseded by K2); I-05 Knowledge cannot silently overwrite contradiction, requires Contradiction(K1,K2) as a relationship; I-06 Determination must expose reasoning basis D->{E,K,C,M}; I-07 Determination!=Decision; I-08 Decision=>Authority; I-09 Recommendation_AI != Decision_Governance; I-10 Authorization!=Governance (should this be permitted vs is this actor permitted); I-11 Authorization must be contextual Auth=f(Actor,Action,Resource,Policy,Context,Time); I-12 Action!=Execution; I-13 Execution must generate observable consequences (Execution->Observation); I-14 Unknown!=False and Unknown!=Null; I-15 Inconclusive!=Failed in verification result space {PASS,FAIL,INCONCLUSIVE}; I-16 absence of evidence is not evidence of absence (not Evidence(C) does not imply not C) unless closed-world is declared; I-17 agent memory M_A(t) is not authoritative history H(t), generally M_A(t) subseteq H(t); I-18 Provenance!=Lineage; I-19 identity must survive evolution (Identity+Version+Lineage together); I-20 Authority must be explicit for Decision/Source/Agent), organized under a four-category constraint taxonomy (semantic/consistency/governance/technical invariants) and a central derivation rule Semantic Concept -> Invariant -> Consistency Requirement -> Boundary (never Noun -> BoundedContext directly).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1355 §"Semantic Concept → Invariant → Consistency Requirement → Boundary. Not: Noun → BoundedContext. That distinction will protect the architecture from over-modelling."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S1355 §"1. Semantic invariant Defines what something means. Evidence ≠ Knowledge. 2. Consistency invariant Defines what must remain consistent together. Decision.authority = ValidAuthority. 3. Governance invariant Defines what is permitted. Execute(A) ⇒ Authorized(A). 4. Technical invariant Defines implementation safety. ... The fourth category should not be mistaken for a domain invariant unless the domain actually requires it."]
- CANDIDATE-FORMAL-BIRTH: [S1355 §"I-01 — Observation integrity ... I-02 — Evidence traceability ... I-03 — Knowledge requires epistemic status ... I-04 — Knowledge must be versionable ... I-05 — Knowledge cannot silently overwrite contradiction ... I-06 — Determination must expose reasoning basis ... I-07 — Determination is not Decision ... I-08 — Decision requires authority ... I-09 — Recommendation is not Decision ... I-10 — Authorization is not governance ... I-11 — Authorization must be contextual ... I-12 — Action is not Execution ... I-13 — Execution must generate observable consequences ... I-14 — Unknown is legitimate ... I-15 — Inconclusive is not Failed ... I-16 — Absence of evidence is not evidence of absence ... I-17 — Agent memory is not authoritative history ... I-18 — Provenance and lineage are different ... I-19 — Identity must survive evolution ... I-20 — Authority must be explicit"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1355. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S1355), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1355 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1355 |
| dependencies | PRESENT | S1355 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1355 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1355] types=['PRINCIPLE'] scope=METHODOLOGICAL — "Central derivation rule for Step 162: bounded contexts must be derived via Semantic Concept -> Invariant -> Consistency Requirement -> Boundary, never directly via Noun -> BoundedContext; this is presented as the safeguard against over-modelling." (anchor: "Semantic Concept → Invariant → Consistency Requirement → Boundary. Not: Noun → BoundedContext. That distinction will protect the architecture from over-modelling.")
- [S1355] types=['DISTINCTION', 'CONCEPT'] scope=THEORY-LEVEL — "Distinguishes four kinds of constraint: (1) semantic invariant (defines what something means, e.g. Evidence!=Knowledge), (2) consistency invariant (what must remain consistent together, e.g. Decision.authority=ValidAuthority), (3) governance invariant (what is permitted, e.g. Execute(A)=>Authorized(A)), (4) technical invariant (implementation safety, e.g. 'only immutable evidence records may enter the evidence store'), warning the fourth category must not be mistaken for a domain invariant unless the domain itself requires it." (anchor: "1. Semantic invariant Defines what something means. Evidence ≠ Knowledge. 2. Consistency invariant Defines what must remain consistent together. Decision.authority = ValidAuthority. 3. Governance invariant Defines what is permitted. Execute(A) ⇒ Authorized(A). 4. Technical invariant Defines implementation safety. ... The fourth category should not be mistaken for a domain invariant unless the domain actually requires it.")
- [S1355] types=['INVARIANT', 'FORMALIZATION'] scope=THEORY-LEVEL — "Full twenty-item candidate invariant catalog derived in Step 162: I-01 Observation integrity (Observation does not imply Conclusion); I-02 Evidence traceability (Evidence->Source/Observation, provenance reconstructable); I-03 Knowledge requires epistemic Status(K); I-04 knowledge history must be preservable (K1 superseded-by K2, event-sourcing not decided); I-05 knowledge cannot silently overwrite contradiction (needs Contradiction(K1,K2) as a relationship, not necessarily a separate aggregate); I-06 Determination must expose reasoning basis (D->{Evidence,Knowledge,Context,Method}, cannot be an opaque conclusion string); I-07 Determination!=Decision; I-08 Decision=>Authority (not necessarily human -- may be Architecture Board, policy engine, delegated role, automated mechanism); I-09 Recommendation_AI != Decision_Governance (AI cannot silently cross the authority boundary); I-10 Authorization!=Governance (governance asks should this be permitted, authorization asks is this actor permitted); I-11 Authorization must be contextual (Auth=f(Actor,Action,Resource,Policy,Context,Time), same actor may be authorized for A1 but not A2); I-12 Action!=Execution (Action=Intent+Target+Parameters vs Execution=ActualEffect, enabling an 'authorized but not executed' state); I-13 Execution must generate observable consequences (Execution->Observation closes the feedback loop; an execution with no way to establish its result is an assurance gap); I-14 Unknown!=False and Unknown!=Null (NULL is technical, UNKNOWN is epistemic); I-15 Inconclusive!=Failed in verification's own result space {PASS,FAIL,INCONCLUSIVE}; I-16 absence of evidence is not evidence of absence (not-Evidence(C) does not imply not-C) unless a closed-world rule is explicitly declared; I-17 agent memory M_A(t) is not authoritative history H(t), generally M_A(t) subseteq H(t) for relevant information, so agent memory must not be the sole continuity mechanism; I-18 Provenance ('where did this come from') != Lineage ('how did this state evolve'), both needed for historical explainability; I-19 identity must survive evolution (K1->K2 evolution needs a traceable relationship via Identity+Version+Lineage together, else history becomes unrelated records); I-20 Authority must be explicit for a consequential Decision, for a Source's Authority/Standing, and for an Agent's GrantedCapability -- Authority is a cross-cutting governance concern." (anchor: "I-01 — Observation integrity ... I-02 — Evidence traceability ... I-03 — Knowledge requires epistemic status ... I-04 — Knowledge must be versionable ... I-05 — Knowledge cannot silently overwrite contradiction ... I-06 — Determination must expose reasoning basis ... I-07 — Determination is not Decision ... I-08 — Decision requires authority ... I-09 — Recommendation is not Decision ... I-10 — Authorization is not governance ... I-11 — Authorization must be contextual ... I-12 — Action is not Execution ... I-13 — Execution must generate observable consequences ... I-14 — Unknown is legitimate ... I-15 — Inconclusive is not Failed ... I-16 — Absence of evidence is not evidence of absence ... I-17 — Agent memory is not authoritative history ... I-18 — Provenance and lineage are different ... I-19 — Identity must survive evolution ... I-20 — Authority must be explicit")

## Notes for P3
(none beyond what is noted above)
