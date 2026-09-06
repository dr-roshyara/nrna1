# 16 — Negative Results (Part XXI — mandatory)

Negative results are first-class here. Nothing below was manufactured into a positive.

## N-1 — The baseline failed `[NEG]` `[EXP]`

`C0` as given achieves 21/25 capabilities and 9/16 scenarios. It cannot produce `Evidence`, therefore
cannot produce a `Verdict`, therefore cannot validate, cannot represent uncertainty, cannot represent
assumptions. **The candidate operator set is not adequate to its own stated purpose.** Everything
downstream had to be run on a repaired baseline.

## N-2 — Removing `DetectGap` broke nothing `[NEG]`

0 capabilities lost, 0 of 16 scenarios lost, 0 property failures, in all 8 model variants. This is the
cleanest negative result in the experiment and it is a *positive* finding about the kernel.

## N-3 — Removing `Discriminate` broke nothing — but this is an implementation artifact `[NEG]` F19

`Discriminate` appeared removable **only because `DetectGap` redundantly carried its atom.** Under V4
(where `DetectGap`'s difference-decision is scoped to the norm delta) `Discriminate` is irreducible.
This is exactly the failure mode Part XXI names: *"X appeared necessary/unnecessary only because the
simulator encoded its responsibility into Y."* Recorded as F19, **not** as a kernel result.

## N-4 — Five operators are individually removable but not jointly `[NEG]`

`{DetectGap, Determine}`, `{DetectGap, Discriminate}`, `{Hypothesize, Infer}`,
`{Hypothesize, Represent}`. Structural dependency, not redundancy. A leave-one-out-only study would
have reported C5 and C8 as needed by nobody.

## N-5 — No third-order interaction exists `[NEG]`

Triple ablations over both implicated clusters returned exactly the union of the pairwise losses. The
dependency structure of this model is second-order. Recorded because it *bounds* the complexity.

## N-6 — The randomized property suite is non-discriminating for capability loss `[NEG]`

Guard-activation audit: removing `Validate`, `Qualify`, `Observe` or `Interpret` drives P10's guard to
**0.00**; removing `Determine` drives P2's and P6's guards to **0.00**. Those arms' "no property
failures" are **vacuous passes**. Property tests detect *misbehaviour*; ablation produces *incapacity*;
the two are different and only the second is what leave-one-out creates.

`[INF]` **Standing methodological consequence:** never report an algebraic or behavioural property
from a zero-counterexample result without a degeneracy check, and always report the count of
*active* trials alongside the failure rate.

## N-7 — P3 (provenance) is untestable in this instrument `[NEG]` F20

Guard activation 0.00 in every arm. The simulator carries provenance structurally, so no operator set
can lose it. Its universal pass is worthless and C18 was reclassified non-discriminating (§03 M-2).
This is a **simulator artifact**, not a kernel property.

## N-8 — `Validate`'s "derivability" under V7 is degenerate `[NEG]` F20

In V7 (no `Qualify`) `Validate` classifies as derivable — but only because *no* operator set can
produce a `Verdict` at all, so its atom is inert. A vacuous derivability. Rejected as evidence.

## N-9 — Every smuggling probe succeeded `[NEG]` `[EXP]`

7 of 7 absorptions restored full coverage. **Reduction by absorption is always available and always
worthless.** Any minimality claim lacking an explicit smuggling test is unfalsifiable.

## N-10 — Atom irreducibility is model-relative `[NEG]`

V1 fuses `meaning-assignment` and `symbolic-encoding` into one atom and **loses no capability.** So
"all 14 atoms are irreducible" holds *relative to the declared capability model* and is not an
absolute result. This directly limits the shape hypothesis of §14.

## N-11 — Authorization was never tested `[NEG]`

No scenario exercises it; failure class F18 (governance/authorization confusion) was observed zero
times *because it could not be observed*. The Part XVI hypothesis "Authorization = governance
mechanism" is **assumed in the model, not tested by it** — as is "Governance = external constraint".
Neither may be cited as an experimental result.

## N-12 — The simulator cannot see degree `[NEG]` F20

It answers "can this operator set do X at all?", never "how well". The corpus's seven distinct
revision verbs are indistinguishable to it; so are a rich and a poor interpretation. Every result is
bounded by this.

## N-13 — Cardinality could not discriminate between the two minimal kernels `[NEG]`

Both are 13. The result the protocol warned against — optimizing for numerical smallness — was
structurally unavailable here. This is fortunate rather than clever.

## N-14 — `C0` has almost no corpus provenance as a set `[NEG]` `[CORPUS]`

Only 6 of 1 782 corpus files contain ≥6 of the 13 operator tokens, and half of those are this lane's
own prompts or 2026-09-01 external extracts. `Hypothesize` (2 role files), `Select` (3) and
`Represent` (5) are near-unsupported. The experiment tested a *proposal*, not an established
vocabulary, and its conclusions inherit that limitation.
