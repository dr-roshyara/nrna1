# situational-provenance

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Situational Provenance` · **Aliases:** `li as procedural pattern`
**Candidate group membership (NOT an identity claim):**
- **G0073**: [`selection-procedure-object` · `situational-provenance`] — explicit agent-stated uncertainty: 'situational-provenance' POSSIBLY relates to 'selection-procedure-object' (batch B0012). Note: Chinese-philosophical-lens extension of provenance beyond source (who/when/where) and acquisition (how) to situation: conditions, environment, relation to surrounding context, phase, actors, constraints, what changed, what was absent/excluded; a number itself (e.g. a 95% success rate) is said to be insufficient without its relations.
- **G0076**: [`analytical-lineage-object` · `situational-provenance`] — explicit agent-stated uncertainty: 'analytical-lineage-object' POSSIBLY relates to 'situational-provenance' (batch B0012). Note: Extends the source+acquisition provenance model with Selection/Transformation/Analysis/Exclusion/Publication provenance, motivated by the replication-crisis anti-pattern (p-hacking, optional stopping, HARKing, subgroup slicing); proposes AnalyticalLineage (Raw Dataset->Cleaning->Exclusions->Transformation->Subgroup selection->Statistical test->Model->Result->Claim) and EvidenceEpisode (original acquisition/replication/contradiction/extension/failed replication/synthesis) so a claim accumulates a reconstructible evidence history rather than a single overwritten result.
- **G1392**: [`evidence-acquisition-domain` · `situational-provenance`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0012, scope OBJECT) (relation_to_existing: POSSIBLY:selection-procedure-object): Chinese-philosophical-lens extension of provenance beyond source (who/when/where) and acquisition (how) to situation: conditions, environment, relation to surrounding context, phase, actors, constraints, what changed, what was absent/excluded; a number itself (e.g. a 95% success rate) is said to be insufficient without its relations.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0452] §"Daoist thinking is uncomfortable with the idea that reality consists of isolated objects possessing fixed meanings independently of their relationships and circumstances. ... E = 95% of deployments succeeded ... Relative to what?"
- CANDIDATE-CONCEPTUAL-BIRTH: [S0452] §"Daoist thinking is uncomfortable with the idea that reality consists of isolated objects possessing fixed meanings independently of their relationships and circumstances. ... E = 95% of deployments succeeded ... Relative to what?"
- CANDIDATE-FORMAL-BIRTH: [S0452] §"REALITY -> occurs -> PHENOMENON -> encounter/detection -> OBSERVATION -> acquisition context -> OBSERVATION RECORD -> assessment -> CANDIDATE EVIDENCE -> relation to hypothesis -> EVIDENCE -> synthesis -> KNOWLEDGE -> judgement -> WISDOM -> ACTION ... plus SITUATION{PERSON/TIME/RELATION -> ROLE/CHANGE/CONTEXT} feeding OBSERVATION."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0452. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S0452, S0452 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | PRESENT | S0452, S0452, S0452 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0452 |
| Examples | PRESENT | S0452 |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0452] types=[CONCEPT, EXAMPLE] scope=OBJECT — "Chinese/Daoist relational lens: a bare statistic ('95% of deployments succeeded') is meaningless without its relations (which deployments/teams/systems/period/procedure/risk class/exclusions/environment/what changed); its relations are part of its meaning, motivating Situational Provenance as a third provenance axis alongside Source and Method." (anchor: "Daoist thinking is uncomfortable with the idea that reality consists of isolated objects possessing fixed meanings independently of their relationships and circumstances. ... E = 95% of deployments succeeded ... Relative to what?")
- [S0452] types=[FORMALIZATION] scope=THEORY-LEVEL — "Combines the Zero-Lens occurrence-to-action chain with the Chinese relational structure, concluding 'the observation is never epistemically naked' -- every observation is fed by Situation{Person/Role, Time/Change, Relation/Context}." (anchor: "REALITY -> occurs -> PHENOMENON -> encounter/detection -> OBSERVATION -> acquisition context -> OBSERVATION RECORD -> assessment -> CANDIDATE EVIDENCE -> relation to hypothesis -> EVIDENCE -> synthesis -> KNOWLEDGE -> judgement -> WISDOM -> ACTION ... plus SITUATION{PERSON/TIME/RELATION -> ROLE/CHANGE/CONTEXT} feeding OBSERVATION.")
- [S0452] types=[PRINCIPLE, DEFINITION] scope=THEORY-LEVEL — "Two culminating principles: evidence is a status an observation may acquire through a defensible relationship among phenomenon/observation/acquisition procedure/situation/hypothesis/explanation (substantially stronger than 'evidence has provenance'); and an observation cannot be fully understood apart from the situation/relationships/conditions/transformations through which it arose -- so the epistemic object is Evidence + arising conditions + acquisition path + relational context + transformation history." (anchor: "Evidence is not a thing that KnowledgeOS merely stores. Evidence is a status that an observation may acquire through a defensible relationship between phenomenon, observation, acquisition procedure, situation, hypothesis and explanation. ... An observation cannot be fully understood apart from the situation, relationships, conditions and transformations through which it arose.")

## Notes for P3
Carries 3 candidate group memberships (G0073, G0076, G1392); P3 should prioritize resolving whether these reflect the same underlying object — multiple memberships here only means more surface signal touched this label, not that it is more likely to be a duplicate. Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows.
