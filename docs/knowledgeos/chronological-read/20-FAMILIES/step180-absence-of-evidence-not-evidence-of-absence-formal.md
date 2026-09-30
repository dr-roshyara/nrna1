# step180-absence-of-evidence-not-evidence-of-absence-formal

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `NotEstablished != False`; `RuleNotFound => ExistenceUnknown, not => DoesNotExist`; `not Evidence(C) does not imply Evidence(not C)`; `p>0.05 does not imply H0=True; means EvidenceInsufficientToReject(H0)` · **Aliases:** hallucination boundary formalized
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL — "Step 180 states the safety invariant 'an absence of evidence must never silently become evidence of absence', formalized not-Evidence(C) does not imply Evidence(not-C), worked with an infrastructure-documentation search example (RuleNotFound => ExistenceUnknown, never => FirewallRuleDoesNotExist, requiring a NetworkVerification investigation). Draws the classic statistical-hypothesis-testing parallel: p>0.05 does not imply H0=True, only EvidenceInsufficientToReject(H0) -- the NotProven vs ProvenFalse distinction is fundamental, yielding NotEstablished(C) != False(C) as a required invariant. Names AI's tendency to always produce an answer even with insufficient evidence as a specific risk, requiring NoEvidence => Unknown rather than NoEvidence => GeneratedFact, and states that recognizing uncertainty is itself a successful epistemic outcome, not assessment failure -- applied concretely to the series' own BC-discovery process (8 Candidate BCs -> 5 Confirmed BCs via Candidate->Evaluated->Confirmed, not by treating every initial hypothesis as truth)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1373 §"An absence of evidence must never silently become evidence of absence. Formally: ¬Evidence(C) ⇏ Evidence(¬C). ... RuleNotFound ⇒ FirewallRuleDoesNotExist [wrong]. Correct result: RuleNotFound ⇒ ExistenceUnknown. ... p>0.05 does not imply: H0=True. It may simply mean: EvidenceInsufficientToReject(H0). ... NotEstablished ≠ False."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1373. Candidate lifecycle: DORMANT.
Evidence: no retracted_by, no superseded_by, and contested_by_own_contradiction_type is false. DORMANT is a heuristic based on how long ago (by source_id) S1373 was last used — not a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1373 |
| dependencies | PRESENT | S1373 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1373 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

Note: the anchor text contains a worked infrastructure example and a formula, but the row's own `completeness` field marks `formal_definition` and `examples` as NOT-EVIDENCED-IN-CAPTURE — preserved verbatim per the derived data rather than overridden by this agent's reading of the anchor.

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1373]` types=[INVARIANT, WARNING] scope=THEORY-LEVEL — "States the safety invariant 'an absence of evidence must never silently become evidence of absence' (not-Evidence(C) does not imply Evidence(not-C)), worked with an infrastructure-search example (RuleNotFound => ExistenceUnknown, never => the rule does not exist). Draws the statistical-hypothesis-testing parallel p>0.05 does not imply H0=True, only EvidenceInsufficientToReject(H0), reinforcing NotEstablished(C) != False(C). Names AI's tendency to always produce an answer even under insufficient evidence as a specific risk, requiring NoEvidence => Unknown rather than NoEvidence => GeneratedFact, and states that recognizing uncertainty is itself a successful epistemic outcome, not assessment failure." Dependency listed: `step167-invariant-category-taxonomy-and-matrix`. (anchor: "An absence of evidence must never silently become evidence of absence. Formally: ¬Evidence(C) ⇏ Evidence(¬C). ... RuleNotFound ⇒ FirewallRuleDoesNotExist [wrong]. Correct result: RuleNotFound ⇒ ExistenceUnknown. ... p>0.05 does not imply: H0=True. It may simply mean: EvidenceInsufficientToReject(H0). ... NotEstablished ≠ False.")

## Notes for P3
This label declares a dependency on `step167-invariant-category-taxonomy-and-matrix` (row-level `dependencies` field) — P3 should check whether that label was captured elsewhere in the corpus reconstruction so the dependency edge can be wired up. The completeness roll-up marking `formal_definition`/`examples` as NOT-EVIDENCED-IN-CAPTURE despite a rich formal anchor looks like a possible P2a scoring quirk worth a second look, though this file preserves it as-derived rather than correcting it.
