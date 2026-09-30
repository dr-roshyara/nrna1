# well-formed-proposition-requires-named-equality-principle

**Scope(s):** METHODOLOGICAL · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "A property stated without naming its equality relation is not a well-formed proposition." · **Aliases:** equality-typing principle
**Candidate group membership (NOT an identity claim):**
- G0460: explicit agent-stated uncertainty linking `d285-5-narrowed-to-quotient-relative-claim` to this label (batch B0051) — a correction narrowing D285-5's original claim ("delta is non-injective") to "distinct operations produce results that are semantically equivalent only under a projection/quotient that discards provenance; under provenance-sensitive equality the same witness is injective," treated by the capturing agent as superseding, not merely annotating, the old wording. Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0050, scope METHODOLOGICAL: "The central methodological finding extracted from reviewing D285-5 (the t285_reconcile.py Action!=Result test, S2063): any equality-involving formal property (e.g. delta(K,o1)=delta(K,o2) does-not-imply o1=o2) is indeterminate/not well-formed until the specific equality relation (structural/semantic/observational/version/identity) is named, since the property can be vacuously true, false, or load-bearing depending on which equality is meant."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2070 §"Under structural equality: Π∈id. So δ(K,o1)=_structural δ(K,o2) is false — the property never fires. Under semantic equality: δ(K,o1)=_semantic δ(K,o2) not⇒ o1=o2. This is the meaningful property. ... A property stated without naming its equality relation is not a well-formed proposition."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2104. Candidate lifecycle: DORMANT. Evidence: `retracted_by` and `superseded_by` are both empty, `contested_by_own_contradiction_type` is false. This is a heuristic based on how recently (by source_id) this label was last used (S2104, batch B0051), not a confirmed retirement — the principle's own row history shows it being actively extended right up to its last-seen row (S2104 extends it to require a named transition scope), so DORMANT here likely reflects "no rows after this point in the capture window" rather than abandonment.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S2098 (×2) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2070, S2073, S2088, S2089 (×2), S2098, S2099, S2104 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The problem this principle addresses, and the gap it closes, is stated directly in S2098: a formal property like δ(K,o1)=δ(K,o2) does not imply o1=o2 looks well-formed but is actually indeterminate — vacuously false under structural equality (since identity incorporates provenance Π), and only becomes the intended, meaningful claim under semantic equality [S2070]. S2098 frames this as philosophy-versus-methodology: the Gita's framing ("do not define the action by its fruit") posed the underlying question but did not by itself supply the needed qualification — "philosophy asked the question; the equality typing answered it" [S2098]. A second rationale thread in S2098 argues the principle is self-correcting rather than self-serving: the HPA review had originally accepted the D285-5 artifact as written, and the amendments arose only from applying the review's own equality-typing mandate more deeply to the very artifact the methodology was built from [S2098]. The principle is repeatedly validated by independent application: S2072 reports the author finding the same defect, unnoticed, in their own earlier K-projection claim; S2089 independently confirms the correction from a second reviewer voice and calls it the "most important methodological lesson of the whole D285 package," claiming it resolves several apparently contradictory D285 findings into a coherent result; S2099 applies the discipline to a different artifact (a state-ontology matrix) and finds its own comparison relation is only weak "set equality on primitive names," establishing vocabulary disjointness rather than conceptual disjointness. The principle is also extended beyond simple equality: S2088 generalizes it to state/operation/transition/projection equality, and S2104 extends the same discipline to invariant scope, requiring every invariant to declare an explicit transition scope T_P rather than leaving "which transitions" unstated.

rationale_truncated_count = 0 (all rationale-bearing rows for this label are shown above).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (family.assumption_register is empty for this label).

## All rows (source_id order)

- [S2070] types=[PRINCIPLE] scope=OBJECT — "States and generalizes the equality-typing principle: the operation-non-injectivity property is vacuously true under structural equality (identity incorporates provenance Π) and only becomes load-bearing under semantic equality; generalized to 'any equality-involving proposition is ill-formed until its equality relation is named.'" (anchor: "Under structural equality: Π∈id. ...")
- [S2072] types=[VALIDATION] scope=METHODOLOGICAL — "Acknowledges the same equality-typing defect just identified in a different artifact (D285-5) was present, unnoticed, in the author's own earlier K-projection claim — a direct self-audit validating the principle's general applicability." (anchor: "This is the SAME defect class D285-5 caught in the action/result property ...")
- [S2073] types=[PRINCIPLE] scope=METHODOLOGICAL — "Elevates the typing/equality-naming requirement to 'the single most important methodological correction to emerge from the entire Gītā-KnowledgeOS integration effort,' generalizing beyond the single D285-5 artifact." (anchor: "Every D285 hypothesis must be typed before it can be tested. ...")
- [S2088] types=[PRINCIPLE, EXTENSION] scope=METHODOLOGICAL — "Generalizes the D285-5 lesson into a standing rule: every future proposition involving state/operation/transition/projection equality must declare its equality relation, or research risks technically true but scientifically vacuous propositions." (anchor: "Every future proposition involving state equality, operation equality, transition equality or projection equality must declare its equality relation.")
- [S2089] types=[VALIDATION, RESTATEMENT] scope=OBJECT — "Independently confirms the D285-5 correction: action and result must remain distinct, but the non-injectivity claim is only meaningful under a specifically chosen equality/quotient — called an important methodological correction." (anchor: "Action and result must remain distinct ...")
- [S2089] types=[PRINCIPLE, RESTATEMENT] scope=METHODOLOGICAL — "States 'do not ask whether two formulations are the same until the equality relation has been specified' as the most important lesson of the whole D285 package, claimed to resolve several apparently contradictory D285 findings." (anchor: "Do not ask whether two formulations are \"the same\" until the equality relation has been specified.")
- [S2098] types=[RESTATEMENT, ARGUMENT] scope=OBJECT — "States the final typed result: δ(K,o1)=semantic δ(K,o2) does not imply o1=o2, and stating it unnamed makes it vacuous; the Gita's framing did not by itself supply the qualification — 'philosophy asked the question; the equality typing answered it.'" (anchor: "Typed result: δ(K,o₁) =_semantic δ(K,o₂) ⇏ o₁ = o₂. ...")
- [S2098] types=[VALIDATION, ARGUMENT] scope=METHODOLOGICAL — "Notes the HPA review originally accepted the artifact as written, and the amendments arose only from applying the review's own equality mandate more deeply to the artifact the methodology was built from — evidence the methodology is self-correcting, not self-serving." (anchor: "The HPA review accepted this artifact as written. ...")
- [S2099] types=[DISTINCTION, LIMITATION] scope=METHODOLOGICAL — "Applies equality-typing discipline to itself: a state-ontology matrix's comparison relation is only weak set equality on primitive names; the 3 'shared' primitives are shared by name only, conceptual identity is a separate untested claim — the matrix establishes vocabulary disjointness, not conceptual disjointness." (anchor: "Set equality on primitive NAMES. ...")
- [S2104] types=[PRINCIPLE, EXTENSION] scope=METHODOLOGICAL — "Extends the discipline to scope: no invariant may be stated without an explicit transition scope T_P, refining the invariant schema to quantify only over (K,o) in T_P." (anchor: "No invariant may be stated without a transition scope. ...")

## Notes for P3
- This label shows a strong, repeated, independently-confirmed pattern (batch B0050→B0051): the principle is applied not just to the artifact that originally surfaced it (D285-5) but successfully turned on its own author [S2072], on a second artifact type (a state-ontology matrix) [S2099], and extended to a different formal category (invariant scope, not just equality) [S2104]. This is one of the more strongly evidenced methodological principles among the batch, worth priority attention if P3 is looking for candidates for promotion.
- G0460 flags a possible superseding relationship with `d285-5-narrowed-to-quotient-relative-claim` — per this label's own rows, that narrowed claim (operations are equivalent only under a provenance-discarding quotient, injective under provenance-sensitive equality) reads as a direct application of this very principle to D285-5's original claim, so the two labels may be tightly coupled (one being the method, the other its result on a specific case) — flagged for reconciliation, not asserted here.
- `family.files_touching` lists several source_ids (S2071, S2076, S2078, S2079, S2081, S2090, S2100) that do not appear among the 10 rows actually captured in `family.rows` — this file cites only the 8 distinct source_ids that appear in the row list (S2070, S2072, S2073, S2088, S2089, S2098, S2099, S2104). Flagging the discrepancy for P3/reconciliation, consistent with a pattern also seen in other labels in this batch.
