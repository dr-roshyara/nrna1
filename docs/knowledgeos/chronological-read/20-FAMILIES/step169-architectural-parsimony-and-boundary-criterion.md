# step169-architectural-parsimony-and-boundary-criterion

**Scope(s):** THEORY-LEVEL · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `BoundaryCandidate(B) strengthened by DistinctLanguage+DistinctInvariants+DistinctLifecycle+DistinctOwnership+DistinctChangePressure`; `DomainNeed→ArchitecturalRequirement→Mechanism, not InterestingConcept→MandatoryComponent`; `KnowledgeOS must justify every additional abstraction by a problem it solves`
**Aliases:** "architecture inflation warning"; "minimal-system counterexample"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 169's deeper falsification attempt against the whole architecture (not just the laws): could a conventional deterministic system (Request->Approval->Execution->Audit Log, no AI, no probability, no knowledge graph) already suffice, making the KnowledgeOS conceptual machinery unnecessary? Concludes additional epistemic structure is justified only when the domain actually exhibits uncertain knowledge, heterogeneous evidence, AI-generated assessments, changing knowledge, conflicting sources, historical reconstruction needs, semantic provenance, decision justification, or verification dependencies -- otherwise a simpler architecture is healthier. States the resulting principle 'KnowledgeOS must justify every additional abstraction by a problem it solves,' and formalizes the DDD bounded-context strengthening criterion BoundaryCandidate(B) is strengthened by DistinctLanguage+DistinctInvariants+DistinctLifecycle+DistinctOwnership+DistinctChangePressure, explicitly rejecting 'this looks like a separate module' as a boundary justification. Warns against 'architecture inflation' (a useful concept discovered somewhere does not make it a mandatory component everywhere): the correct rule is DomainNeed->ArchitecturalRequirement->Mechanism, never InterestingConcept->MandatoryComponent. Applies this test to Evidence (strong domain object: provenance/lifecycle/integrity/relationships/validation/expiration), Confidence score (should NOT become a domain object -- no independent lifecycle/authority/invariant/ownership, just a property of an assessment), Verification (strong candidate), and Governance Decision (must not reduce to approved=true)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1362 §"Could Evidence, Knowledge, Determination, Decision and Execution all be one aggregate? Technically, perhaps. But then ask: Who owns the invariants? ... If these answers differ significantly, the single aggregate becomes problematic. ... BoundaryCandidate(B) is strengthened when there is: DistinctLanguage + DistinctInvariants + DistinctLifecycle + DistinctOwnership + DistinctChangePressure."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1362 §"Could Evidence, Knowledge, Determination, Decision and Execution all be one aggregate? Technically, perhaps. But then ask: Who owns the invariants? ... If these answers differ significantly, the single aggregate becomes problematic. ... BoundaryCandidate(B) is strengthened when there is: DistinctLanguage + DistinctInvariants + DistinctLifecycle + DistinctOwnership + DistinctChangePressure."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1362. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1362), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1362 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1362 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1362 |
| dependencies | PRESENT | S1362 (×2) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1362 (×2) |
| examples | PRESENT | S1362 (×2) |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Applies the parsimony/boundary test to concrete concepts: Evidence has substantial domain behavior (provenance/lifecycle/integrity/relationships/validation/expiration) and merits domain-object status; a bare Confidence score lacks independent lifecycle/authority/invariant/ownership and should NOT become a domain object, only a property of an assessment (prevents over-modeling); Verification (subject/predicate/method/evidence/scope/verdict/freshness) is a strong candidate; Governance Decision (authority/scope/lifecycle/responsibility/consequences) must not be reduced to a bare approved=true flag. [S1362]

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1362] types=[FORMALIZATION, PRINCIPLE] scope=THEORY-LEVEL — "Tests whether Evidence/Knowledge/Determination/Decision/Execution could collapse into one aggregate by asking who owns each invariant, which changes require which authority, which lifecycles differ, and which concepts change independently -- concluding bounded-context separation must emerge from invariant and lifecycle differences, not aesthetic preference, formalized as BoundaryCandidate(B) strengthened by DistinctLanguage+DistinctInvariants+DistinctLifecycle+DistinctOwnership+DistinctChangePressure, explicitly better than 'this looks like a separate module.'" (anchor: "Could Evidence, Knowledge, Determination, Decision and Execution all be one aggregate? Technically, perhaps. But then ask: Who owns the invariants? ... If these answers differ significantly, the single aggregate becomes problematic. ... BoundaryCandidate(B) is strengthened when there is: DistinctLanguage + DistinctInvariants + DistinctLifecycle + DistinctOwnership + DistinctChangePressure.")
- [S1362] types=[COUNTEREXAMPLE, PRINCIPLE] scope=THEORY-LEVEL — "Proposes a minimal deterministic counterexample system (Request->Approval->Execution->Audit Log, no AI/probability/knowledge graph) that might already be sufficient for some domains, arguing KnowledgeOS's additional epistemic machinery is justified only where uncertain knowledge, heterogeneous evidence, AI-generated assessments, changing knowledge, conflicting sources, historical reconstruction, semantic provenance, decision justification, or verification dependencies genuinely exist. States the resulting principle 'KnowledgeOS must justify every additional abstraction by a problem it solves,' and warns against 'architecture inflation' via the rule DomainNeed -> ArchitecturalRequirement -> Mechanism, never InterestingConcept -> MandatoryComponent." (anchor: "Request ↓ Approval ↓ Execution ↓ Audit Log. Everything is deterministic. No AI. No probabilistic reasoning. No complex knowledge graph. Perhaps this is sufficient. If so, KnowledgeOS should not impose unnecessary complexity. ... KnowledgeOS must justify every additional abstraction by a problem it solves. ... DomainNeed → ArchitecturalRequirement → Mechanism. Not: InterestingConcept → MandatoryComponent.")
- [S1362] types=[ANALYSIS, EXAMPLE] scope=THEORY-LEVEL — "Applies the parsimony/boundary test to concrete concepts: Evidence has substantial domain behavior (provenance/lifecycle/integrity/relationships/validation/expiration) and merits domain-object status; a bare Confidence score lacks independent lifecycle/authority/invariant/ownership and should NOT become a domain object, only a property of an assessment (prevents over-modeling); Verification (subject/predicate/method/evidence/scope/verdict/freshness) is a strong candidate; Governance Decision (authority/scope/lifecycle/responsibility/consequences) must not be reduced to a bare approved=true flag." (anchor: "Confidence should not automatically become a domain object. It may simply be a property of an assessment. This prevents over-modeling. ... Governance Decision clearly has: authority; scope; lifecycle; responsibility; consequences. Therefore it should not be reduced to: approved = true.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
