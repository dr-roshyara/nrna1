---
source_track: TRACK-A-PHASE-MEASURE
derived_from: [all KSME-13/13A fork reports]
cross_track_dependency: none
---

# KSME-13A — Research Dependency Graph

Consolidated from all four KSME-13A forks plus the two KSME-13 grounding forks. Edge types per the
Mandatory Chronological Dependency-Closure Protocol; every edge carries source+tier.

```
DECISION-02 --blocks--> δ-construction, composition-rule                          [theory-08 §5, SOURCE-ESTABLISHED]
DECISION-02 --depends-on--> φ's semantic status (6-reading space)                 [170000 §B.2, SOURCE-ESTABLISHED]
C7 (frame-refinement invariance) --applicability-depends-on--> DECISION-02        [170000 §B.1, SOURCE-ESTABLISHED as label]
majority (composition rule) --legitimacy-depends-on--> DECISION-02's resolution   [170000 §B.2]

Contr_step292 --merely-analogizes--> Contr_theory08                              [step-292/04, "relocated into the transition"]
Contr_step292 --is-a-named-prerequisite-for--> SSA-shaped δ                       [step-292/04, NO algorithm given]

Conflict_Step32algebra --is-distinct-from--> ConflictStatus_Step32state           [no bridging text found]
Conflict_Step32algebra --is-distinct-from--> Conflict_Step60predicate             [no bridging text found]
ConflictStatus_Step32state --is-distinct-from--> Conflict_Step60predicate         [no bridging text found]
Conflict_Step60predicate --requires--> Support+/Support- (§60.10)                 [SOURCE-ESTABLISHED]
Conflict_Step60predicate --is-distinct-from--> Conflict(p,t) (§60.45-46)          [different arity, unresolved]
Merge (Step 60) --produces--> Conflict_Step60predicate [source-asserted worked example, NOT machine-executed]
"Merge != Resolve" --governs--> Merge/Conflict(p)/Resolve relationship            [Step 60 §60.22-24, SOURCE-ESTABLISHED]
Resolve --depends-on--> Policy (via "DomainPolicy" input)                        [Step 60 §60.23]
Resolve --depends-on--> HumanAdjudication, StatisticalModel (both undefined)      [Step 60 §60.23]
Policy --is-explicitly-not-solved-by--> its removal from K ("gate without embedding") [Step 277 §277.35 point 9]
Policy --appears-only-as-opaque-parameter-in--> Authorize_277.25                  [Step 277 §277.25]

Authorize_277.20 --refines-into--> Authorize_277.25                              [SOURCE-ESTABLISHED, same document]
Authorize_Step32 --is-distinct-from--> Authorize_Step259                         [no cross-reference found]
Authorize_Step32 --is-distinct-from--> Authorize_277.20/.25                      [no cross-reference found]
Authorize_Step259 --is-distinct-from--> Authorize_277.20/.25                     [no cross-reference found]
Authorize_Step32 --possibly-chains-with--> Authorize_Step259                     [DERIVED, unverified, codomain resonance only]
Authorize_277.20/.25 --merely-analogizes--> AP-1                                 [thematic parallel only]

Revision --lacks-formal-signature--> only informal K1--E-->K2 arrow              [Step 32 §32.23]
Revision --is-distinct-from--> Promote:Hypothesis×Evidence⇀SupportedClaim         [Step 32 §32.5, a different, typed operation]

⊕_Step32 --is-distinct-from--> ⊕_Step279                                        [NOTATION-COLLISION]
⊕_Step32 --is-distinct-from--> ⊕_20260826arch                                   [NOTATION-COLLISION]

t285_reconcile.py --is-not-reusable-as--> E_B/T_B foundation                     [confirmed by direct file read]

research/knowledgeos-sim --was-previously-investigated-as--> BC-02.14-K-OBJECT-REGISTRY [CONTEXT.md, pre-KSME]
research/knowledgeos-sim --was-left-untouched-by--> established KSME-era session practice [CONTEXT.md pattern, post-track-separation]
```

## Graph completeness

This graph is explicitly partial — it captures load-bearing edges discovered across KSME-11 through
KSME-13A, not a from-scratch traversal of the entire corpus. It extends `KSME-12-TERM-RELATION-GRAPH.md`,
does not replace it.
