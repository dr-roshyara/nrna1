# step186-refined-KS-typed-proposition

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `KS=(Sbj,Prop,Val,Ctx,T,P,E,A,G) then refined to KS=(p,C,T,P,E,A,G) with p a typed proposition`, `Type(p) in {Observation,Claim,Inference,Determination,Decision,Policy,Recommendation}` · **Aliases:** `revised Knowledge State with typed proposition space`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0033`, scope `THEORY-LEVEL`: Step 186 stress-tests the Step-185(pre) KS=(I,S,C,T,P,A,G) tuple and finds it insufficient: it lacks explicit subject/property structure (e.g. 'Nexus 3.69 is installed' needs Identity_subject distinct from Identity_assertion). First revision: KS=(Sbj,Prop,Val,Ctx,T,P,E,A,G) with a worked instantiation for Nexus version. Second revision, needed because Val cannot be a scalar for normative ('the migration should happen'), inferential ('probably happened'), or causal/historical ('happened because of X') propositions: collapses to KS=(p,C,T,P,E,A,G) where p is drawn from a typed proposition space P, with Type(p) in {Observation,Claim,Inference,Determination,Decision,Policy,Recommendation} -- these types must not share one unrestricted semantic category.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1381 §"Neither is explicitly represented in the tuple. Therefore we need: I to mean more than merely identity of the assertion. ... KS=(Sbj,Prop,Val,Ctx,T,P,E,A,G) ... Val cannot be treated as merely a scalar. We need a proposition space: P. ... KS=(p,C,T,P,E,A,G) where p is a typed proposition. ... Type(p) ∈ {Observation,Claim,Inference,Determination,Decision,Policy,Recommendation}."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1381 §"Neither is explicitly represented in the tuple. Therefore we need: I to mean more than merely identity of the assertion. ... KS=(Sbj,Prop,Val,Ctx,T,P,E,A,G) ... Val cannot be treated as merely a scalar. We need a proposition space: P. ... KS=(p,C,T,P,E,A,G) where p is a typed proposition. ... Type(p) ∈ {Observation,Claim,Inference,Determination,Decision,Policy,Recommendation}."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1381. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1381 |
| type_signature | PRESENT | S1381 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1381 |
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
- [S1381] types=['CORRECTION', 'FORMALIZATION'] scope=THEORY-LEVEL — "Finds the original KS=(I,S,C,T,P,A,G) tuple insufficient (lacks subject/property structure), first refining to KS=(Sbj,Prop,Val,Ctx,T,P,E,A,G) with a worked Nexus-version instantiation, then discovering Val cannot be a scalar for normative/inferential/causal propositions, collapsing to KS=(p,C,T,P,E,A,G) where p is drawn from a typed proposition space with Type(p) in {Observation,Claim,Inference,Determination,Decision,Policy,Recommendation} -- these types must not share one unrestricted semantic category." (anchor: "Neither is explicitly represented in the tuple. Therefore we need: I to mean more than merely identity of the assertion. ... KS=(Sbj,Prop,Val,Ctx,T,P,E,A,G) ... Val cannot be treated as merely a scalar. We need a proposition space: P. ... KS=(p,C,T,P,E,A,G) where p is a typed proposition. ... Type(p) ∈ {Observation,Claim,Inference,Determination,Decision,Policy,Recommendation}.")

## Notes for P3
- Thin evidence base (n=1 row(s)) — treat conclusions here as provisional.
