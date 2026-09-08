# Framework Cold Reconstruction

`19` §7, quoted exactly (re-read fresh, `01`), each element classified.

## Behavioural equivalence `B`

> *"For a kernel `𝒦`, define an externally observable behaviour `B(𝒦, W, Q, E, C) = (K′, Assessment,
> Alternatives, Gap, Revision, Decision-eligibility)` and call two kernels behaviourally equivalent
> iff `B(𝒦₁,·) = B(𝒦₂,·)` on every admissible test case (distributional equality for stochastic
> systems)."*

| Element | Status |
|---|---|
| The tuple's own six named components (`K′`, `Assessment`, `Alternatives`, `Gap`, `Revision`,
`Decision-eligibility`) | **SOURCE-STATED** — named exactly as listed |
| What each component's own value *is*, computed from `𝒦`/`W`/`Q`/`E`/`C` | **REQUIRED BUT NOT
SPECIFIED** — no formula, algorithm, or further definition given anywhere in `19` for any of the six |
| The equivalence condition itself (`B(𝒦₁,·) = B(𝒦₂,·)` on every admissible test case) | **SOURCE-
STATED** as a condition-schema; "every admissible test case" is itself undefined (what makes a case
admissible is not stated) |
| "Compare behaviours, never internal operator traces" | **SOURCE-STATED**, a methodological
instruction, not a formal definition |

## Epistemic preservation `P`

> *"Behavioural equality is not sufficient... Require additionally `P = (Meaning, Evidence, Warrant,
> Uncertainty, Alternatives, History, Identity, Context, Inquiry, Authorization)` `P(𝒦₁) = P(𝒦₂)` on
> every dimension declared essential."*

| Element | Status |
|---|---|
| The ten named dimensions | **SOURCE-STATED** — named exactly as listed |
| What each dimension's own value *is*, or how it is computed/compared | **REQUIRED BUT NOT
SPECIFIED** — none of the ten (including `Warrant`, the one closest to "surviving a defeater") is
given any further definition |
| "on every dimension declared essential" | **REQUIRED BUT NOT SPECIFIED** — who declares a
dimension essential, or by what criterion, is not stated |

## Search discipline (CEGAR)

> *"propose a reduction → search adversarially for a counterexample → keep the primitive if one is
> found, otherwise propose a further decomposition."*

**SOURCE-STATED**, as a search *procedure* for exploring the space of candidate reductions — this is
a methodology for *finding* reductions, not a definition of `B` or `P`'s own components. It presumes
`B`/`P` are already computable (so a counterexample can be checked), which they are not, per the
table above.

## The two contributed additions

**Capability-closure gate**: `∀ c ∈ C_required : ∃ o ∈ C reaching c` — **SOURCE-STATED, and this one
*is* fully formal and decidable** (it reuses the already-established `Reach(S)` mechanism from `06`).
Not itself a definition of survival, but the one genuinely computable piece of machinery in this
section.

**Invariant custody**: `custody(I, 𝒦) = {o ∈ 𝒦 : I fails in 𝒦\{o}}` — **SOURCE-STATED and formal**
(already known from MD-037's own reading of `18` §5.3, re-confirmed here) — but, as already
established (MD-037 `04`), this machinery is built for the *different*, weaker condition `I9`
("Defeater consideration"), not "surviving a defeater."

## Explicit precondition on running the framework at all

> *"Not to be run before level 2 and level 3 (§5) have answers."*

`19`'s own restructure table (§5, already fully read MD-037) marks **Level 2 (State — "what is the
mathematical type of `K_t`?") `OPEN — now the blocking question`** and **Level 3 (Semantics —
"`R₁ ≡_sem R₂`?") `OPEN — no definition exists`**. **SOURCE-STATED**: the framework's own proposing
document says it should not be run until these are answered, and states directly that neither is.
