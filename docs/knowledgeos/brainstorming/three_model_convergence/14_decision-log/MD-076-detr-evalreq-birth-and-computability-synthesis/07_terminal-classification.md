# MD-076 §07 — Terminal Classification

## Candidates considered

**A (COMPUTATION ESTABLISHED)** — rejected outright. No case, anywhere, has ever been carried from
concrete `K`/`r`/`EC`/`Γ` to a computed `Sat` value via `Det_r(EvalReq(...),EC)`. Rejected precisely
for the reason the mission itself warns against: the presence of a complete-looking typed equation
(`[Def 6.18]`) is not evidence of A.

**B (COMPUTATION PARTIALLY ESTABLISHED)** — considered and rejected. B would require *some* cases or
components to be genuinely computable. None are: `EC` has never been instantiated for any real
requirement; `Γ` has no definition at any level; `EvalReq` has no codomain and its only worked
illustration doesn't generalize even to the corpus's own best-evidenced case; `Det_r` has zero bodies
for zero requirements. There is no partial success to point to — the block is total and uniform
across every component tested.

**D (FORMULA ITSELF UNSTABLE)** — seriously considered, given the 2-arg/3-arg `Sat` mismatch (§03) is
a genuine, source-observable inconsistency, not merely an absence. **Rejected as the primary
classification**, for a precise reason: D's own definition concerns *historical formulations remaining
materially unreconciled* — i.e., **rival, competing definitions of the same object that conflict**.
That is not what is found here. There is exactly **one** `Det_r`/`EvalReq` definition (§02, one version
each, never revised or rivaled). The 2-arg/3-arg mismatch is not two competing *definitions* of `Sat`
disagreeing with each other — every 2-arg stipulation is silent about the 3-arg form, not contradicting
it. It is more precisely characterized as an **unused/orphaned extension** of `Sat`'s own signature,
not an unstable/contested formula. This finding is preserved and reported (§03/§04) as a compounding
defect under C, not elevated to its own classification.

**E (CONTRADICTED)** — rejected. No stronger chronological evidence directly contradicts `Det_r`/
`EvalReq`'s own typed definition — nothing asserts `Det_r:𝒱×EC→𝕊_sat` is false or was withdrawn. The
definition stands, unrivaled, unexercised.

## Verdict

## **C — FORMALLY SPECIFIED BUT SEMANTICALLY OPEN**

`Det_r`/`EvalReq` are given a name, an argument list, and (for `Det_r`) a type signature — genuine
formal specification, the theory's own real advance over every earlier `Sat` attempt (`MD-070`'s own
verdict: "a genuine structural/type-level advance"). But the **meaning and computation of every
load-bearing component remains open**: `EvalReq`'s own codomain is unstated; its only illustration
does not generalize; `Det_r`'s own body is, by the source's own explicit design, an externally-supplied
parameter never actually supplied anywhere; the inputs required to even attempt an invocation (`EC`
instance, `Γ`) do not exist in the corpus at any level. **This is the most precisely-evidenced
statement of C this reconstruction has produced** — it does not merely repeat MD-070's own finding, it
adds the specific missing-codomain finding, the disconnected-graph-at-both-ends finding, and the
2-arg/3-arg orphaning finding as the concrete content of "semantically open."
