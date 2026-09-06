# E — Property Catalog (§21)

`definitional = true` (§24) marks a property whose oracle necessarily restates a theory definition:
its pass is a **logical consequence of the implementation**, not independent evidence.

| ID | Statement | Definitional? |
|---|---|---|
| P1 | Evidence ≠ Knowledge | no |
| P2 | Determination ≠ Knowledge | no |
| P3 | Rejection ≠ Acceptance | **yes** — `Reject` removes one member by construction |
| P4 | Gap ≠ Zero (Zero is the closure predicate over the gap) | **yes** — `Zero := (Δ=∅)` is DEF-22 |
| P5 | Inquiry changes adequacy requirements | no |
| P6 | Historical state remains distinguishable from current state | no |
| P7 | Representation equality is not required for semantic equality | no — **but see CIRC-5** |
| P8 | Evidence dependence prevents unjustified double counting | no |
| P9 | Model fit does not imply causal validity | no |
| P10 | No unsupported semantic information is created | no |
| **P11** | **Knowledge attribution respects factivity** `Knows(a,p,c,t) → True(p,c,t)` | **no — the only fully oracle-independent property** |
| P12 | Different epistemic standards can produce different assessments | no |
| P13 | Unobservable ≠ Underdetermined | no |
| P14 | Completeness ≠ Sufficiency | no |
| P15 | Decision ≠ Determination | no |
| P16 | Authorization ≠ Decision | no |
| P17 | Action ≠ Authorization | no |
| P18 | Current state ≠ history | no |
| P19 | Changing `K_t` does not imply changing identity | no |
| P20 | Zero is inquiry-relative | no |

## Which properties carry real weight

Only **P11** is fully oracle-independent: its evaluator reads `World.truth`, which no agent
transition can access (verified by static audit). Every other property compares the agent's state
against itself under a definition supplied by the theory.

**P3 and P4 are definitional** and should never be cited as evidence that the theory works.
`P4` in particular is `Zero := (Δ=∅)` restated; it can only fail if the implementation is broken.

`P7` is marked non-definitional but is **partly circular** — see `I-circularity-register.md` CIRC-5.
