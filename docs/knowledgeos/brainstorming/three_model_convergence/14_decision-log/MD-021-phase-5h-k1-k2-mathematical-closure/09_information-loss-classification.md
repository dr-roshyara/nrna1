# Phase 5H — Information-Loss Classification (D1–D7)

| K-1 primitive | Category | Justification |
|---|---|---|
| Event | **D1 — Deliberate abstraction** | Explicitly declared external by the verification lane itself (`VERIF_EXTERNAL` in `t285_reconcile.py`; "declared EXTERNAL — a deliberate exclusion" in D285-1) |
| Policy | **D1 — Deliberate abstraction** | Same evidence as Event |
| Action | **D1 — Deliberate abstraction** | Same evidence as Event |
| Observation | **D4 — Computational limitation** | The mapping exists and is triangulated across 4 sources (`08`); it is currently unusable only because `Qualify` has no computable body — not because the information is theoretically irrelevant or absent |
| State | **D7 — UNRESOLVED (evidence insufficient)** | The projection target itself is undefined (`06`); this phase cannot classify the loss because it cannot first identify what, if anything, is preserved or lost |
| Entity | **Not lost** — preserved, nested (not a loss category; listed for completeness) | Present in all 4 sources, at reduced structural prominence |
| Proposition | **Not lost** — preserved directly | Present in all 4 sources |
| Relation | **Not lost** — preserved directly, role-asymmetric | Present in all 4 sources |

## Adversarial check: is D1 (Event/Policy/Action) itself fully justified, or merely asserted?

Per Phase 5G's own `06` (semantic-equivalence audit), the "declared irrelevant" claim for these three
primitives is an **authorial assertion, not an independently verified result** — the corpus states
that Event/Policy/Action are "external to K-2's scope" but does not supply a test showing K-2's own
stated purpose is genuinely unaffected by their absence. **This phase does not upgrade D1 to a
stronger category on this basis** — D1 (deliberate abstraction) remains the correct classification
for *why the corpus excludes them*, while the *unverified* status of that exclusion's own
justification is a separate, already-disclosed finding (Phase 5G `06`), not re-litigated here.

## D2, D3, D5, D6 — not used

**D2 (context-specific irrelevance, independently verified)**: not used, since no independent
verification was found for any primitive (see the adversarial check above — the closest candidate,
Event/Policy/Action, reaches only D1, an *asserted* exclusion, not a *verified* irrelevance).
**D3 (representation elsewhere)**: not used for any primitive in this table — `Observation`'s Sañjaya
recovery construction is a *representation elsewhere* in a general sense, but the load-bearing fact for
its classification here is specifically the computational blocker, which is why D4 (not D3) governs.
**D5 (undefined mapping)**: closely related to `State`'s own D7 classification, but D7 is preferred
here since the evidence is not merely "no mapping was found" but "the evidence is insufficient to
determine whether a mapping could even be meaningfully stated" (per `06`'s own finding that "the
carrier" itself is undefined, not merely un-mapped). **D6 (genuine information loss, distinctions
collapsed)**: not confidently assignable to any single primitive without first resolving `07`'s own
Assertion-unpacking conflict, since which distinctions are "collapsed" depends on which unpacking
scheme is used.
