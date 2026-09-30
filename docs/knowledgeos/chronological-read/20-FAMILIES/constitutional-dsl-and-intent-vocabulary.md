# constitutional-dsl-and-intent-vocabulary

**Scope(s):** OBJECT · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Constitutional DSL`, `Evidence Weighting Model`, `Intent Vocabulary` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0009`, scope `OBJECT`: A proposed constitutional rule DSL grammar (CONSTITUTION/ENTITY/EVIDENCE/RULE/CONTRADICTION_RULE/TRANSITION_RULE), a closed intent-action/entity/relationship vocabulary with regex pattern-grammar parsing, and a numeric evidence-weighting model (0.0-1.0 base weights, modifiers, 1.5x supersession threshold) for a 'constitutional knowledge engine' Kernel design; the numeric-weighting part is subsequently challenged and largely rejected in the same batch as inconsistent with OQ-5 (confidence is domain-owned, not a mechanism score).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0358 §"1. PROPOSAL RECEIPT ... 2. CONSTITUTIONAL PRE-CHECK ... 3. STATE RETRIEVAL & BINDING ... 4. CONSTITUTIONAL RESOLUTION ... 5. TRANSITION EXECUTION (if RESOLVED) ... 6. POST-TRANSITION VERIFICATION"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0358 §"1. PROPOSAL RECEIPT ... 2. CONSTITUTIONAL PRE-CHECK ... 3. STATE RETRIEVAL & BINDING ... 4. CONSTITUTIONAL RESOLUTION ... 5. TRANSITION EXECUTION (if RESOLVED) ... 6. POST-TRANSITION VERIFICATION"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0360. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0358, S0359, S0359, S0359 |
| type_signature | PRESENT | S0358 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0360 |
| assumptions | PRESENT | S0359 |
| semantics | PRESENT | S0358 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0360 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
| Statement | Stated | Source | Anchor |
|---|---|---|---|
| a numeric weight difference of 1.5x is the correct threshold for automatic supersession | EXPLICIT | S0359 | contradiction_resolution_threshold: 1.5 |

## All rows (source_id order)
- [S0358] types=['FORMALIZATION'] scope=OBJECT — "A six-stage Kernel state machine is proposed (proposal receipt → constitutional pre-check → state retrieval/binding → constitutional resolution → transition execution → post-transition verification), culminating in an output of RESOLVED/AMBIGUOUS/MISMATCH/UNRESOLVED at the resolution stage." (anchor: "1. PROPOSAL RECEIPT ... 2. CONSTITUTIONAL PRE-CHECK ... 3. STATE RETRIEVAL & BINDING ... 4. CONSTITUTIONAL RESOLUTION ... 5. TRANSITION EXECUTION (if RESOLVED) ... 6. POST-TRANSITION VERIFICATION")
- [S0358] types=['CONSTRAINT', 'PRINCIPLE'] scope=OBJECT — "A six-row fail-closed failure-mode table maps each named failure (ambiguous intent, missing evidence, contradiction detected, unauthorized actor, invalid context, history violation) to a specific non-executing refusal outcome." (anchor: "Your kernel must be fail-closed ... Ambiguous intent → Return AMBIGUOUS, request clarification, do NOT execute ... The kernel never guesses, never approximates, and never executes an invalid transition.")
- [S0359] types=['FORMALIZATION'] scope=OBJECT — "A first-cut Constitutional DSL grammar is specified (CONSTITUTION/IMPORT/CONTEXT/ENTITY/EVIDENCE/RULE/CONTRADICTION_RULE/TRANSITION_RULE blocks), illustrated with a worked GovernanceConstitution v2.3.0 example including a WorkItem entity, Document/Observation evidence types, and rules for evidence sufficiency and lifecycle transitions." (anchor: "CONSTITUTION <name> version <semver> ... RULE <rule_name> [priority <number>]: ... CONTRADICTION_RULE <rule_name>: ... TRANSITION_RULE <rule_name>:")
- [S0359] types=['FORMALIZATION'] scope=OBJECT — "A closed intent vocabulary (14 named actions, 8 entity types, 6 relationship types) with a regex-based pattern-grammar for slot-filling intent parsing is proposed, explicitly framed as closed rather than general NLP." (anchor: "Action Vocabulary ... START_REVIEW, START_ADOPTION_REVIEW, CONTINUE, STOP, ADOPT, REJECT, ... The parser uses regex + slot filling")
- [S0359] types=['FORMALIZATION', 'ASSUMPTION'] scope=OBJECT — "A numeric evidence-weighting scheme assigns fixed base weights per evidence type (0.2-1.0), multiplicative modifiers for authority/provenance-depth/validity/recency, and a 1.5x threshold for one claim to supersede a contradicting one (20% band triggers reconciliation or escalation instead)." (anchor: "base_weights: CONSTITUTIONAL: 1.0, CONSENSUS: 0.9, APPROVAL: 0.8, ... contradiction_resolution_threshold: 1.5 # If evidence A weight > evidence B weight * 1.5, A supersedes B")
- [S0360] types=['WARNING', 'CORRECTION'] scope=CROSS-OBJECT — "Numeric evidence weighting is flagged as the single most important technical problem in the prior proposal: it risks laundering mechanism-derived confidence scores into domain truth or authority, directly contradicting a prior ruling (OQ-5) that confidence is a domain-owned structured epistemic attribute rather than a mechanism score, and should not be adopted without strong domain justification." (anchor: "mechanism score ≠ evidence weight ≠ domain confidence ≠ authority ... The Kernel should not silently turn: 0.8 > 0.3 into: therefore truth / therefore authority. That is precisely the kind of semantic laundering the architecture has been trying to prevent.")

## Notes for P3
- No unusual tensions or evidentiary anomalies were observed for this label within the captured rows.
