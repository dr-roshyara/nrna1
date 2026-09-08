# Phase 5G — Semantic Equivalence Audit (the central downgrade of this phase)

## The user's own methodological instruction, applied directly

*"Do not assume that 'semantic equality' is a valid mathematical relation just because D285-6 uses
that phrase. First define what equality means."*

## Does the corpus define "semantic equality"? — checked directly

**No formal definition was found.** D285-6 §5 states only: *"semantic [equality] — TRUE — only after
unpacking `Assertion` → `{Proposition, Entity, Observation}` + `{id,c,t,Π}`, modulo the declared drop."*
This is an **assertion of a verdict**, not a definition of the relation being tested. **"Semantic
equality" is confirmed to be a corpus-local term, used without an operational definition anywhere in
the three source documents read (D285-1, D285-6, D285-7).**

## Testing the claim against five independently defined equivalence types (per the authorization's §8)

| Equivalence type | Operational definition (constructed here, as an explicit hypothesis, since the corpus supplies none) | Holds for K-1↔K-2? | Evidence |
|---|---|---|---|
| **Representation equality** | The two carriers are literally the same set/tuple | **NO** | D285-6 §5 itself: "structural — FALSE" |
| **State equality** | Every state distinguishable in $K_1$ remains distinguishable in $K_2$ | **NO** | Three primitives (`Event,Policy,Action`) are dropped — states differing only in these are conflated in $K_2$ |
| **Observational equivalence** | The two models answer the same query set identically | **NO** | D285-6 §5: "observational — FALSE"; `replay`, `policy-eval`, `authorize` unanswerable in $K_2$ |
| **Semantic preservation** | Every *meaning-bearing distinction* $K_1$ can express is either present in $K_2$ or explicitly, verifiably irrelevant to $K_2$'s own purpose | **PARTIALLY, AT BEST** — the corpus *declares* `Event/Policy/Action` irrelevant to $K_2$'s scope, but does not independently verify that declaration against $K_2$'s own stated purpose; this is an **authorial assertion, not a demonstrated result** | D285-1 §2 ("declared EXTERNAL... a deliberate exclusion") |
| **Information equivalence** | $K_1$ is reconstructable from $K_2$ (an inverse exists) | **NO** | No inverse map $g: K_2 \to K_1$ is constructed or claimed anywhere in the corpus; the projection is one-directional and explicitly lossy "by design" |

## Verdict: the corpus's own "semantic equality TRUE" claim, precisely characterized

**What actually holds**: a **partial structural correspondence over a declared subset** of $K_1$'s
carrier — specifically, the sub-tuple $\{Entity, Observation, Proposition, Relation\}$ (modulo
`State`'s own unresolved mapping) matches, field-for-field after unpacking `Assertion`, a
corresponding sub-structure inside $K_2$. **This is NOT full semantic equivalence in the strict sense
constructed above** — three of five tested equivalence types fail outright (representation, state,
observational), one is unverifiable-as-stated (semantic preservation — the "declared irrelevant"
claim is asserted, not independently confirmed), and one fails by the absence of any inverse
(information equivalence).

**This phase downgrades Phase 5F's own acceptance of "semantic equality TRUE" at face value.** Phase
5F correctly reported the corpus's own words but did not sufficiently interrogate what "semantic
equality" actually meant as a formal relation — exactly the gap the authorization asked this phase to
close. **The corrected label**: **PARTIAL CORRESPONDENCE over a declared, non-verified subset** — not
"semantic equality," which this audit finds to be an under-specified, uncritically-adopted corpus-local
term.

## Does this change the projection's own status?

**No** — `05`'s own characterization of $\pi_K$ (partial, non-total, one component non-computable)
stands independently of this terminological correction. What changes is only the *label* applied to
the relationship the projection establishes: not "semantic equality" (an unearned, precise-sounding
term), but "partial correspondence over a declared subset, with the declaration itself unverified."
