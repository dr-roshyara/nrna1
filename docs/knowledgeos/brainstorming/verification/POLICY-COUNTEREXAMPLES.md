---
artifact: 3 · POLICY-COUNTEREXAMPLES
mandate: 20260830_1931 §3
date: 2026-08-30
status: DELIVERED — seven distinctions, each with corpus evidence AND a formal counterexample
---

# What Policy Is NOT

**Every distinction below carries both corpus evidence and a constructed counterexample. None is asserted
conceptually.**

| Claim | Corpus evidence | Counterexample | Verdict |
|---|---|---|---|
| **Policy ≠ Knowledge State** | `Policy ≠ Aggregate` (non-step) | The same `K` (37 real EKP assertions) was evaluated under two policies. **`K` was byte-identical in both runs; only the policy varied.** If policy were part of `K`, the two runs would have had different states — they did not | **PROVEN** |
| **Policy ≠ Evidence** | `Evidence = QualifiedObservation` (253) vs `Policy = normative rule` (252) | Evidence is *observed*; a policy is *enacted*. Executed: `D2` has `nsrc=1, origin=blog` — **the evidence is unchanged while `P_A` admits and `P_B` refuses.** Evidence cannot both be and judge itself | **PROVEN** |
| **Policy ≠ Assertion Status (Σ)** | `EpistemicStrength ≠ GovernanceStatus` (194.19) | Executed: `Σ` is the *output* of `Assessment(·, Policy)`. Step 199's `Policy = Allowed` **conflates a policy with its verdict** and is refuted: `Allowed` is `Apply(p,d)`'s codomain, not `p` | **PROVEN — refutes step 199** |
| **Policy ≠ Validation Result** | `Policy ≠ Verification` (139) | Executed: `Apply(P_B, D2) = False` with detail `{Pre:T, Invariant:T, Authorization:T, JustifStrength:F, TwoSource:F, TrustedOrigin:F}`. **The policy is the object that produced this; the result is the dict.** One is reusable across inputs, the other is not | **PROVEN** |
| **Policy ≠ Governance Status (Γ)** | `Policy ≠ Governance` (252) | `Γ` is a property *of an assertion*; a policy is a property *of the regime*. Executed on real EKP data: 37 assertions carry 5 distinct `Γ` values **under one policy** | **PROVEN** |
| **Policy ≠ Assessment** | `Validation ≠ Assessment` (252) | Type-level: `Assessment : P × Evidence × Context × Policy → Σ` **takes Policy as an argument.** A function cannot be its own parameter. **This is why candidate `Policy : Claim × Evidence × Context → Assessment` was rejected in artifact 2** | **PROVEN** |
| **Policy ≠ Transformation History** | `Policy is not a command` (207) | Executed congruence result: `T`'s domain is `𝕂 × Op × Policy × Authority` — **Policy is an input to `T`, History is an output of it.** Two histories with the same resulting `K` can have been produced under different policies | **PROVEN** |

## Two further tests the mandate lists

**Is Policy part of `P` (the proposition)?** **NO.** Q14 §5.3 states a proposition *"has no epistemic
status, has no evidence, has no temporal validity, has no provenance."* A policy is none of a proposition's
three components `(Entity, Dimension, Value)`. **Counterexample: `(Nexus, Version, 3.69)` is the same
proposition under every policy.**

**Is Policy part of `T`?** **It is a PARAMETER of `T`, not a component.** Executed distinguishability test
(prior phase): same `(K, op)`, different policy, different outcome — so it must be an *argument*. But
executed here: the same policy object is applied to many different `T` invocations — so it is not *owned*
by any one. **Policy is an independent object passed to `T`.**

**Is Policy merely configuration / implementation metadata?** **NO — and the EKP settles this
empirically.** `knowledge-schema.yaml` is a configuration file **and** it is normative: it declares which
values are admissible and the linter refuses documents that violate it. **Configuration that changes what
the system will accept is policy, whatever file it lives in.** Conversely `review_overdue` is
*hygiene*, not admissibility. **The discriminator is enforcement, not location** — see artifact 6.

## The remaining possibility

> **Policy is an INDEPENDENT NORMATIVE OBJECT.** All ten alternative placements are refuted above.
> It is not part of `K`, `P`, `T`, evidence, authority, governance, history, or `Σ`, and it is not mere
> configuration. **This is a conclusion by elimination, and the elimination is complete over the mandate's
> own list.**

## The policy/invariant boundary — the hardest case

`Policy ≠ Invariant` (non-step corpus). **They appear in the SAME formula** — `Admissible = Pre ∧
**Invariant** ∧ Assurance ∧ Authorization` — which looks like a conflation and is not.

**Derived criterion, applied:**
- **Invariant** — must hold in *every* admissible state. Negating it makes the state **invalid**.
  Example: `acyclic(ℛ_sup)`. There is no coherent regime in which supersession cycles are fine.
- **Policy** — a choice that could defensibly be otherwise. Negating it yields a **different but still
  valid** regime. Example: `Unknown → Block` vs `Unknown → Allow`. **Both are coherent; the corpus chose
  Block for safety-critical work and labelled the choice "(policy: Block)" in the source.**

> **Executed discriminator: `P_A` and `P_A4` differ ONLY in `ResolutionBehavior` and both produce valid
> systems.** That is what makes it a policy. **No such variation exists for acyclicity.**
