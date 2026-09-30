# step171-authority-is-temporal-and-domain-not-technical

**Scope(s):** THEORY-LEVEL · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authority(a,x,t1)=true but Authority(a,x,t2)=false`; `CurrentAuthority != HistoricalAuthority`; `Do not derive domain boundaries directly from technical component boundaries`; `Execution(a,x,t) => ValidAuthority(a,x,t), not ValidAuthority(a,x,t_now)`
**Aliases:** "authority as domain concept, not RBAC"; "historical authority reconstruction"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 171 argues Authority must be represented as a domain-level concept (Mandate, Delegation, Role, Jurisdiction, Scope) rather than hidden entirely inside infrastructure RBAC, distinguishing Authentication ('who are you') from Authorization ('what may you do') from Governance ('why are you legitimately entitled to decide'). Formalizes Authority as temporal: Authority(a,x,t1)=true but Authority(a,x,t2)=false is possible, so CurrentAuthority != HistoricalAuthority, giving the temporal authorization invariant Execution(a,x,t) => ValidAuthority(a,x,t) (evaluated AT t, never ValidAuthority(a,x,t_now)) -- connected explicitly to the Chapter-4 continuity theme (current Authority state cannot necessarily reveal past Authority; a past action X_t0 needs Auth(a,x,t0) reconstructed, not today's authority). Also states the central rule: do not derive domain boundaries directly from technical component boundaries (DomainMeaning->Invariants->Boundaries->Architecture->Technology), illustrated by counterexamples showing implementation co-location (a tiny app's if user.isAdmin(): execute(), or a single 'Approve and Execute' transaction merging Decision and Authorization) does not eliminate the underlying conceptual distinctions -- ConceptualSeparation != PhysicalSeparation -- and that AuthorityModelComplexity should scale with GovernanceComplexity, not be imposed universally."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1364 §"Authority(a,x,t1)=true but: Authority(a,x,t2)=false. Therefore: CurrentAuthority ≠ HistoricalAuthority. ... Execution(a,x,t) ⇒ ValidAuthority(a,x,t). Not: ValidAuthority(a,x,t_now). ... Authority_current cannot necessarily tell us: Authority_past. Therefore historical authorization must be reconstructible when historical accountability matters."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1364 §"Authority(a,x,t1)=true but: Authority(a,x,t2)=false. Therefore: CurrentAuthority ≠ HistoricalAuthority. ... Execution(a,x,t) ⇒ ValidAuthority(a,x,t). Not: ValidAuthority(a,x,t_now). ... Authority_current cannot necessarily tell us: Authority_past. Therefore historical authorization must be reconstructible when historical accountability matters."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1364. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1364), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1364 |
| type_signature | PRESENT | S1364 |
| invariants | PRESENT | S1364 (×3) |
| dependencies | PRESENT | S1364 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1364 (×2) |
| examples | PRESENT | S1364 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1364] types=[FORMALIZATION, EXTENSION] scope=THEORY-LEVEL — "Formalizes Authority as temporal: Authority(a,x,t1) can be true while Authority(a,x,t2) is false, so CurrentAuthority != HistoricalAuthority; strengthens the execution invariant to Execution(a,x,t)=>ValidAuthority(a,x,t) evaluated at the actual execution time t, explicitly never ValidAuthority(a,x,t_now) -- connecting to the Chapter-4 continuity theme, since current authority state cannot necessarily reveal past authority, so historical authorization must be reconstructible whenever historical accountability matters." (anchor: "Authority(a,x,t1)=true but: Authority(a,x,t2)=false. Therefore: CurrentAuthority ≠ HistoricalAuthority. ... Execution(a,x,t) ⇒ ValidAuthority(a,x,t). Not: ValidAuthority(a,x,t_now). ... Authority_current cannot necessarily tell us: Authority_past. Therefore historical authorization must be reconstructible when historical accountability matters.")
- [S1364] types=[PRINCIPLE, DISTINCTION] scope=THEORY-LEVEL — "Argues Authority must be a domain-level concept (Mandate, Delegation, Role, Jurisdiction, Scope), not hidden entirely inside infrastructure RBAC; distinguishes Authentication (who are you), Authorization (what may you do), and Governance (why are you legitimately entitled to decide) as related but distinct. States the central rule that domain boundaries must never be derived directly from technical component boundaries, via the chain DomainMeaning -> Invariants -> Boundaries -> Architecture -> Technology." (anchor: "Authority should therefore not be hidden entirely inside infrastructure RBAC. ... Mandate Delegation Role Jurisdiction Scope. ... Authentication answers: Who are you? Authorization answers: What may you do? Governance answers: Why are you legitimately entitled to make this decision? ... Do not derive domain boundaries directly from technical component boundaries. Instead: DomainMeaning → Invariants → Boundaries → Architecture → Technology.")
- [S1364] types=[COUNTEREXAMPLE, PRINCIPLE] scope=THEORY-LEVEL — "Falsifies an overly strong reading of the authority model with two counterexamples: a tiny low-risk application's if user.isAdmin(): execute() may be entirely sufficient (so AuthorityModelComplexity should scale proportionally with GovernanceComplexity, not be mandated universally); and Decision+Authorization can legitimately collapse into one transaction (e.g. a single 'Approve and Execute' click) without eliminating their underlying conceptual distinction -- ConceptualSeparation != PhysicalSeparation, a recurring theme explaining why premature microservice decomposition (Evidence Service, Knowledge Service, Decision Service, ...) should follow, not precede, establishing the BoundedContext." (anchor: "if user.isAdmin(): execute() ... For a tiny low-risk application, that may be sufficient. ... AuthorityModelComplexity ∝ GovernanceComplexity. ... Decision ∧ Authorization → Execution may be a single transaction. ... Implementation co-location does not eliminate conceptual distinction. ... ConceptualSeparation ≠ PhysicalSeparation.")

## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
