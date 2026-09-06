# K — Kernel Result (§13)

> **No operator count is reported.** The protocol forbids it and the previous lane
> (`KR-2026-09-01`, THM-10) established that operator-count minimality is representation-relative:
> the same capability set admitted a 13-operator and an 8-operator minimum under two different
> algebras.

## Semantic capability set exercised

A capability is an *externally distinguishable epistemic transformation or responsibility*.

| Capability | Externally distinguishable by |
|---|---|
| world-contact | nothing else can introduce empirical content (SMUG-0) |
| evidential admission | `Observation` present, `Evidence` absent (excluded source) |
| meaning assignment | one token, several readings under different contexts (AD-9) |
| hypothesis-space opening | `H_Q` membership changes what `cannot-determine` means |
| target-relative assessment | weights differ **per hypothesis** for the same evidence |
| set-valued selection | `\|A\| ∈ {0,1,>1}` are three observably different outcomes |
| elimination | `Reject` changes `H_Q` without changing `A` |
| attribution | `attributed` flips while the determination is unchanged (policy sweep) |
| state commit | `K_t ≠ K_{t+1}` with identity stable |
| normative comparison | `Δ_t` changes with `Q` at fixed `K_t` |
| closure judgment | `Zero` flips with `Q` at fixed `K_t` |
| action preference | two decisions from one determination (P15) |
| authorization | decision made, permission withheld (P16) |

## Candidate reductions tested

| Reduction | Result |
|---|---|
| `Adequate` ← `Zero` | **SUPPORTED** — extensionally identical (CIRC-3); one is redundant |
| `Determination` ← `Knowledge` | **REFUTED** — `attributed` flips under a policy change at fixed determination (factivity sweep) |
| `Decision` ← `Determination` | **REFUTED** — one determination, two decisions (P15) |
| `Authorization` ← `Decision` | **REFUTED** — decision `migrate`, permission denied (P16) |
| `Action` ← `Authorization` | **REFUTED** — granted but not executed (P17) |
| `Underdetermined` ← `Unobservable` | **REFUTED** — distinct in 37.4 % of trials, 0 collapses (P13) |
| `Evidence` ← `Observation` | **REFUTED** — admission policy can exclude a source |

## Irreducible candidates

`[OPEN]` — **none is claimed.** What the experiment shows is weaker and more useful: each capability
above is **externally distinguishable**, i.e. there exists an observable behaviour that separates a
system having it from one lacking it. Distinguishability is necessary for irreducibility, not
sufficient.

## Representation-dependent results

| Result | Depends on |
|---|---|
| `Adequate ≡ Zero` | the choice to define both over the same `Req` set |
| the value of `\|A_t\|` | the standard's threshold and corroboration rule |
| whether `Revise` yields a unique determination | **whether superseded evidence is retired — the theory does not say** (CE-3) |

## Semantic-equivalence results

`P7` passes: two representations of `K` that differ syntactically agree under the semantic
projection. **But CIRC-5 makes this partly circular** — the projection was chosen by the
experimenter. Until `≡_sem` is grounded independently (candidate: the preservation vector `𝒫`,
fixed by contract), **no minimality claim is admissible.**

## Remaining minimality questions

1. Ground `≡_sem` independently of the projection (CIRC-5). **Blocking.**
2. Decide whether `Adequate` and `Zero` are one concept or two (CIRC-3).
3. Determine whether attribution is a kernel capability or a **contract application** — the policy
   sweep shows `Γ` is parameterized by `EC`, so it may be governance rather than epistemics.
4. Model the missing revision verbs before asking whether `Revise` is primitive (CE-3).
