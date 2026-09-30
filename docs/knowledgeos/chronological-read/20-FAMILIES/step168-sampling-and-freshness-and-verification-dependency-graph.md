# step168-sampling-and-freshness-and-verification-dependency-graph

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Change(E)→Affected(K)→Affected(D)→Affected(Decision)`, `Freshness(V)=t_now - t_verification; STALE if exceeds threshold`, `V3=f(V1,V2); V1 invalid => V3 stale/invalid`, `hat p vs p (sampled != universal compliance)`
**Aliases:** `assurance decay`, `impact analysis as dependency analysis over semantic lineage`, `statistical sampling caution`, `verification dependency graph`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 168 adds: (1) a sampling caution -- observed sample compliance hat-p (even 100/100) does not prove universal compliance p=1 ('sampled compliance != universal compliance'), and any confidence interval still depends on sampling design/independence/population definition/measurement validity, requiring a Population-vs-ObservedSample distinction; (2) assurance decay -- Verified(t0) does not imply Verified(t1) for t1>t0 since the system may have changed, formalized via Freshness(V)=t_now-t_verification with a policy threshold Freshness(V)<Delta, else STALE, requiring Change->ImpactAnalysis->Reverification; (3) a verification dependency graph -- a verification V3 can depend on other verifications (V1,V2 -> V3), so if V1 becomes invalid, V3 becomes stale/invalid (propagation analogous to build-system dependency invalidation), generalized into 'ImpactAnalysis = dependency analysis over semantic lineage' (Change(E)->Affected(K)->Affected(D)->Affected(Decision)), argued to be more powerful than traditional file-based impact analysis (FileA->ClassB->TestC). Also: if Evidence E is revoked, a Knowledge claim relying on it needs ReviewRequired(K), not automatically K=false -- InvalidBasis != FalseConclusion, the correct resulting state is Unknown, not False.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1361 §"Sampled compliance ≠ universal compliance. ... 100/100=100%. We may say: All sampled deployments complied. We should not automatically say: The architecture guarantees that all deployments comply. That would be an invalid inference. ... Population from: ObservedSample."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1361 §"Verified(t0) ⇏ Verified(t1). ... Freshness(V)=t_now - t_verification. Some policies may specify: Freshness(V) < Δ. Then a previously valid verification may become: STALE. ... Change → ImpactAnalysis → Reverification. ... V1,V2 → V3 ... V1 → invalid, then: V3 → stale/invalid. ... ImpactAnalysis = dependency analysis over semantic lineage. ... EvidenceChange may invalidate KnowledgeAssessment which may invalidate Determination which may require GovernanceReview."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1361. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S1361), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1361 |
| type_signature | PRESENT | S1361 |
| invariants | PRESENT | S1361 |
| dependencies | PRESENT | S1361 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1361 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1361 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1361] types=['WARNING', 'DISTINCTION'] scope=THEORY-LEVEL — "Statistical sampling caution: an observed sample compliance rate (even 100/100) does not prove universal compliance (sampled compliance != universal compliance); a confidence interval on the estimate still depends on sampling design, independence, population definition, and measurement validity; distinguishes Population from ObservedSample as a matter of semantic precision." (anchor: "Sampled compliance ≠ universal compliance. ... 100/100=100%. We may say: All sampled deployments complied. We should not automatically say: The architecture guarantees that all deployments comply. That would be an invalid inference. ... Population from: ObservedSample.")
- [S1361] types=['FORMALIZATION', 'EXTENSION'] scope=THEORY-LEVEL — "Formalizes assurance decay: Verified(t0) does not imply Verified(t1) for later t1 (the system may have changed), via Freshness(V)=t_now-t_verification and a policy threshold Freshness(V)<Delta, else STALE, requiring Change->ImpactAnalysis->Reverification. Introduces a verification dependency graph (V3 depending on V1, V2; if V1 becomes invalid, V3 becomes stale/invalid, analogous to build-system dependency invalidation) and generalizes this into 'ImpactAnalysis = dependency analysis over semantic lineage' (Change(E)->Affected(K)->Affected(D)->Affected(Decision)), argued more powerful than traditional file-based impact analysis (FileA->ClassB->TestC). Notes revoking Evidence E requires ReviewRequired(K) for dependent Knowledge, not automatically K=false (InvalidBasis != FalseConclusion; correct state is Unknown, not False)." (anchor: "Verified(t0) ⇏ Verified(t1). ... Freshness(V)=t_now - t_verification. Some policies may specify: Freshness(V) < Δ. Then a previously valid verification may become: STALE. ... Change → ImpactAnalysis → Reverification. ... V1,V2 → V3 ... V1 → invalid, then: V3 → stale/invalid. ... ImpactAnalysis = dependency analysis over semantic lineage. ... EvidenceChange may invalidate KnowledgeAssessment which may invalidate Determination which may require GovernanceReview.")

## Notes for P3
(none beyond what is noted above)
