# stochastic-golog-mdp-candidate

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** choice(beta,a), eValue(gamma), prob(a,beta,s), stDo(alpha;beta,p,s,s'), value(do(a,sigma))=value(sigma)+reward-cost · **Aliases:** stGolog / MDP formalism
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0061, scope OBJECT: "Stochastic action decomposition (agent-controlled action, nature choosing among outcome actions via a choice predicate and probability function), stDo recursive probability-of-execution definition, and an MDP value/expected-value formalism (value/reward/cost per step, eValue integrating over initial-state probabilities and execution probabilities); policies as stGolog programs with sensing-conditioned branches."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2536 §"choice(giveCoffee(p),a)\stackrel{def}{=}a=giveCoffeeS(p)\lor a=giveCoffeeF(p) ... prob(a,\beta,s)=p ... stDo(\alpha;\beta,p,s,s') ... value(do(a,\sigma))\stackrel{def}{=}value(\sigma)+reward(a,\sigma)-cost(a,\sigma) ... eValue(\gamma) ... Policies: stGolog programs with conditional branching on sense outcomes."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2536 §"choice(giveCoffee(p),a)\stackrel{def}{=}a=giveCoffeeS(p)\lor a=giveCoffeeF(p) ... prob(a,\beta,s)=p ... stDo(\alpha;\beta,p,s,s') ... value(do(a,\sigma))\stackrel{def}{=}value(\sigma)+reward(a,\sigma)-cost(a,\sigma) ... eValue(\gamma) ... Policies: stGolog programs with conditional branching on sense outcomes."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2536. Candidate lifecycle: ACTIVE. Evidence: `retracted_by` and `superseded_by` are both empty, `contested_by_own_contradiction_type` is false. This is a heuristic based on how recently (by source_id) this label was last used (S2536, its only occurrence, batch B0061), not a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2536 |
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
NOT-EVIDENCED-IN-CAPTURE (family.rationale_evidence is empty for this label; rationale_truncated_count = 0).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (family.assumption_register is empty for this label).

## All rows (source_id order)

- [S2536] types=[FORMALIZATION] scope=OBJECT — "Full stochastic-action/stGolog/MDP apparatus: agent-controlled stochastic actions decomposed via a nature-choice predicate and probability function, a recursive stDo definition computing execution probability, a per-step value function (reward minus cost), an expected-value integral eValue over initial-state and execution probabilities, and policies defined as stGolog programs with sensing-conditioned branches." (anchor: "choice(giveCoffee(p),a)≝a=giveCoffeeS(p)∨a=giveCoffeeF(p) ...")

## Notes for P3
- This is a genuine single-row, single-source (S2536) label — a formalization extracted from what appears to be a source-material reading pass (the file path is titled "extraction-reiter-knowledge-in-action-SOURCE.md"), i.e. this looks like it may be an extraction of Reiter's own published stochastic Golog/MDP formalism (a citation of external literature) rather than a novel KnowledgeOS-original construct. No row in this label's own family states whether or how this formalism was applied to or adopted into KnowledgeOS's own theory — flagging for P3 to check the source document's fuller context if a connection to the corpus's own K_t/MDP-related labels is suspected.
