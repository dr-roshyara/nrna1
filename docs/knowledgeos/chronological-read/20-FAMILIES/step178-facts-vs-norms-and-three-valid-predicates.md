# step178-facts-vs-norms-and-three-valid-predicates

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Facts and norms have different epistemic origins, Valid(K) epistemic / Valid(D) governance / Valid(X) operational are different predicates, avoid universal isValid=true · **Aliases:** empirical vs normative propositions, three meanings of valid
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0033 · scope THEORY-LEVEL — "Step 178 distinguishes an empirical proposition ('the server has 31GB RAM', evaluated against evidence) from a normative proposition ('production changes require Architecture Board approval', derived from organizational authority, not proved by observation) -- 'facts and norms have different epistemic origins', giving Epistemic Context ownership of IsSupported/WhatEvidence/WhatDetermination, Governance ownership of WhatPolicyApplies/WhatDecision/WhoHasAuthority/WhatActionIsPermitted, Operational ownership of WhatActuallyHappened. Distinguishes three different meanings of 'valid' -- epistemic Valid(K) (supported under epistemic rules), governance Valid(D) (made per applicable governance rules), operational Valid(X) (execution conforms to operational constraints) -- as different predicates, warning against a generic isValid=true field hiding several distinct domain meanings, replaced by semantic predicates EpistemicallySupported/GovernanceCompliant/Authorized/ExecutionConformant."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1371 §"Facts and norms have different epistemic origins. ... Epistemic Context owns questions such as: IsSupported? ... Governance owns: WhatPolicyApplies? ... Operational owns: WhatActuallyHappened? ... Valid(K) ... Valid(D) ... Valid(X) ... These are not the same predicate. ... avoid universal isValid."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1371. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded. Since no retraction/supersession/contradiction evidence is present, this lifecycle label is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1371 |
| dependencies | PRESENT | S1371 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1371 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1371] types=[DISTINCTION] scope=THEORY-LEVEL — "Distinguishes empirical propositions ('the server has 31GB RAM', evaluated against evidence) from normative propositions ('production changes require Board approval', derived from organizational authority) -- 'facts and norms have different epistemic origins' -- assigning IsSupported/WhatEvidence/WhatDetermination to Epistemic, WhatPolicyApplies/WhatDecision/WhoHasAuthority/WhatActionIsPermitted to Governance, WhatActuallyHappened to Operational. Distinguishes three different meanings of 'valid' (epistemic Valid(K), governance Valid(D), operational Valid(X)) as different predicates, warning against a universal isValid=true field, replaced by EpistemicallySupported/GovernanceCompliant/Authorized/ExecutionConformant." (anchor: "Facts and norms have different epistemic origins. ... Epistemic Context owns questions such as: IsSupported? ... Governance owns: WhatPolicyApplies? ... Operational owns: WhatActuallyHappened? ... Valid(K) ... Valid(D) ... Valid(X) ... These are not the same predicate. ... avoid universal isValid.")

## Notes for P3
(Own observation.) This label's source S1371 and `step173-three-zone-strategic-architecture`'s source S1366 are both batch B0033, THEORY-LEVEL, and use the identical Epistemic/Governance/Operational triad (this label assigns IsSupported/WhatEvidence/WhatDetermination to Epistemic, WhatPolicyApplies/WhatDecision/WhoHasAuthority to Governance, WhatActuallyHappened to Operational — the same three-way split step173 uses for its zone architecture) — despite this, no group_id links the two labels. This looks like a strong candidate same-document-or-same-passage relationship that the mechanical grouping pass (P2a) missed, offered here as my own observation for P3 to check directly (e.g. via source proximity S1366/S1371 in 03-CONTRIBUTIONS.jsonl), not as an identity claim between the two working_labels.
