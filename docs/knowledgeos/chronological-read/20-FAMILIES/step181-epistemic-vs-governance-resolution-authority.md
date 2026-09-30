# step181-epistemic-vs-governance-resolution-authority

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authority(Actor) != Authority(Source,Proposition)`, `Epistemic resolution: which proposition is better supported`, `Governance resolution: what the organization will adopt/do`, `ResolutionAuthority = f(ConflictType)` · **Aliases:** `who resolves conflict depends on conflict type`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 181 distinguishes epistemic resolution (determining which proposition is better supported) from governance resolution (choosing what the organization will adopt/do) -- even a well-supported epistemic result P can be overridden by Governance choosing ActAsIf(not-P) for risk-tolerance or policy reasons, restating Evidence does not imply Decision. States ResolutionAuthority = f(ConflictType): a factual question about production system behavior is resolved by the relevant technical/domain authority via investigation, while an architecture-adoption question is resolved by Governance -- giving the pipeline Conflict->Classification->ResponsibleContext->ResolutionMethod, explicitly rejecting 'Conflict->AI picks winner' as a strong KnowledgeOS invariant. Distinguishes Authority(Actor) from Authority(Source,Proposition) -- a Board document is authoritative about ArchitectureDecision but not about CurrentCPUUsage -- so source authority is proposition-dependent (Quality(E,Context), not a universal property; e.g. a production observation is superior for RuntimeBehavior but irrelevant for FutureArchitectureIntent), and gives a domain-defined (not universal) rule-based precedence example (ProductionObservation > StagingObservation > Documentation), valid only if the domain explicitly declares it, never assumed.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1374] §"Epistemic resolution ... Determine which proposition is better supported. Governance resolution ... Choose what the organization will adopt or do. ... Evidence ⇏ Decision. ... ResolutionAuthority depends on: ConflictType. ... Conflict → Classification → ResponsibleContext → ResolutionMethod. Not: Conflict → AI picks winner. ... Authority(Actor) from: Authority(Source,Proposition)."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1374] §"Epistemic resolution ... Determine which proposition is better supported. Governance resolution ... Choose what the organization will adopt or do. ... Evidence ⇏ Decision. ... ResolutionAuthority depends on: ConflictType. ... Conflict → Classification → ResponsibleContext → ResolutionMethod. Not: Conflict → AI picks winner. ... Authority(Actor) from: Authority(Source,Proposition)."

## Lifecycle
last_seen: S1374. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1374 |
| Dependencies | PRESENT | S1374 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1374 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | PRESENT | S1374 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1374] types=[DISTINCTION, GOVERNANCE] scope=THEORY-LEVEL — "Distinguishes epistemic resolution (which proposition is better supported) from governance resolution (what the organization adopts/does) -- a well-supported epistemic result can still be overridden by governance for risk/policy reasons, restating Evidence does not imply Decision. Formalizes ResolutionAuthority=f(ConflictType) via Conflict->Classification->ResponsibleContext->ResolutionMethod, explicitly rejecting 'Conflict->AI picks winner.' Distinguishes Authority(Actor) from Authority(Source,Proposition) (a Board document is authoritative about architecture decisions but not about current CPU usage), making source authority proposition-dependent, with a domain-defined (never universally assumed) precedence example ProductionObservation > StagingObservation > Documentation valid only when the domain explicitly declares it." (anchor: "Epistemic resolution ... Determine which proposition is better supported. Governance resolution ... Choose what the organization will adopt or do. ... Evidence ⇏ Decision. ... ResolutionAuthority depends on: ConflictType. ... Conflict → Classification → ResponsibleContext → ResolutionMethod. Not: Conflict → AI picks winner. ... Authority(Actor) from: Authority(Source,Proposition).")
- [S1374] types=[FUTURE-RESEARCH] scope=THEORY-LEVEL — "Proposes Step 182 = The 'Who Owns the Truth?' Experiment, distinguishing KnowledgeOwnership, DecisionAuthority, DomainExpertise, and Accountability as separate concerns, with a stated hypothesis to be tested: 'there is no universal owner of organizational truth' -- instead there are 'bounded authorities over specific kinds of determinations', potentially becoming a foundation of the eventual KnowledgeOS domain model." (anchor: "KnowledgeOwnership from: DecisionAuthority from: DomainExpertise from: Accountability. ... There is no universal owner of organizational truth. There are instead bounded authorities over specific kinds of determinations.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
