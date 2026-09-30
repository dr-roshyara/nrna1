# L0-REL-32 and T-min: resolving the WP-4B gate, then consolidating a minimal transition theory

| | |
|---|---|
| Status | research record. Not canonical. Authority: none |
| L0-REL-32 | spec `IA1-WP4B/SPEC-REL32.json`, frozen at `7fc394b27`; read register rows R-78 (L64) and R-80 (L66) only |
| T-min | pre-registration `prompts/KNOWLEDGEOS-T-MIN-PREREGISTRATION.md`, frozen at `fa1ccea9e` |
| Checker | `tmin_check.py` (`ac1cb86d…`), selftest 3/3 |
| Output | `TMIN/RESULT.json` (`e4e81d49…`) |
| Log | F-LOG-0129 |

## 1. L0-REL-32: source facts, then interpretation
- **R-80** (Directive, first recorded 08-03 15:53; its decision cell was never changed): *"EXECUTION-GOVERNANCE VOCABULARY CLOSED"*.
  - PERMISSION · AUTHORIZATION · COMMISSIONING · EXECUTION become the canonical terms.
  - "A new term requires DEMONSTRATED ambiguity, not ANTICIPATED ambiguity".
  - It reopens no ruling.
  - Its scope annotation: the vocabulary is "FOR THIS PROGRAMME AND THIS GOVERNANCE MODEL … it claims no universality".
- **R-78** (Directive, 08-03 15:34): "the programme transitions from architecture to delivery".
  - "remaining questions shall be framed as IMPLEMENTATION-DELIVERY questions"; "R-75 SELECTED THE PATH; what remains is DELIVERING IT".
  - "It decides no transport, no ownership, no allocation and no implementation".
  - "reopening requires MATERIALLY NEW EVIDENCE, not a better argument".
- **Frozen result: SUPPORT(H-deviation).** Neither row licenses WP-4B RED before PROMOTION/ALLOCATION, and neither re-orders R-79.
- **I-A1 consequence (frozen rule): UNDETERMINED, not VIOLATED.** The source establishes two facts:
  - R-79's step (1), the two Board acts, was **unmet** at 16:10: R-86 and R-87 say they "remain OPEN" on 08-04;
  - R-79's own Effect text calls them "the gate", but it is **ambiguous what they gate**. R-86 later says they "bear on the production producer path and §WP-4 closure, not on batch 7's execution".

  The source does **not** establish that WP-4B's *authorization* was absent. R-72's own proviso is "no unresolved governance blockers remain", and R-78 declares the remaining questions delivery questions 36 minutes before RED. That reading is an INTERPRETATION.
- **What is FALSIFIED is M3's gate choice.** M3 sub-model A made the Board acts guard SATISFY-PROVISO(4B), and so predicted START(4B) was unreachable while they were open. Practice started WP-4B, and the authority later accepted it (R-87) without recording a deviation.
- **New distinction (SOURCE-motivated):** an **affirmed sequence ≠ an authorization guard**.
  - Directive orderings (R-79) are relocated by later acts (R-86).
  - The guard is the condition attached to the authorization itself (R-72's proviso).

## 2. T-min: minimal event-centred structure (MODEL-ASSUMPTION)
- `e = (o, r, a, k, σ, ε, x, Δ⁺, Δ⁻, outcome)`, with `Legal(e) = f(o, r, a, k, σ, ε, x)`.
- The state is a (target, coordinate) map plus a **finite** history summary.
- The frame is Δ⁺ ⊆ Frame⁺(o), with Δ⁺ ∩ Δ⁻ = ∅, and covers direct effects only.

## 3. Variable necessity over 21 coded events (MODEL-DERIVED; selected events; UNDETERMINED ≠ redundant)

| Variable | Result | Minimal pair |
|---|---|---|
| authority a | **NECESSARY** | ADOPT by the Chief (refused: "declines … in the Chief's own favour") vs ADOPT by the Decision Authority (R-86, performed) |
| object kind k | **NECESSARY** | register entry for an operational acceptance (R-90, withdrawn) vs a constitutional decision (admitted) |
| prior state σ | **NECESSARY** | START(WP-4B): refused at R-72 ("implementation does not begin") vs performed at 16:10; START(WP-8) refused (permission only) |
| operation o | UNDETERMINED | no single-variable pair |
| **route r** | UNDETERMINED | no single-variable pair |
| **evidence ε** | UNDETERMINED | no single-variable pair |
| exception x | UNDETERMINED | no single-variable pair |

- **This is the G-R/G-O impasse stated exactly.** The data contains no pair that varies route alone, operation alone or evidence alone. Those hypotheses cannot be separated until such a pair is observed.
- **Sufficiency counterexample:** ADOPT by the Decision Authority, R-86 (performed) vs R-91 (refused, HELD). The two agree on every coded variable.
  - A variable is missing. The source names it: R-91 "collapsed" evidence submission and constitutional review ("A PREPARED ruling was still engineering deciding the outcome").
  - **Candidate variable: procedural conformance of the object**, i.e. whether the act's content respects role separation (who may produce which part). This is a **provenance-of-content** property, not a state coordinate.

## 4. Markov sufficiency

| History pair | Same apparent state | Same futures? | Finite fix |
|---|---|---|---|
| R-90 (withdrawn) vs R-91 (held) | "not adopted" | no | status {PREPARED, ADOPTED, HELD, WITHDRAWN} + registry |
| WP-4B at R-72 vs WP-4B at 16:10 | "authorized in principle" | no | authorization carries its **proviso state** (unmet / discharged) |
| a Chief ruling before vs after R-86 | "issued by Chief" | **yes** | none: adoption creates no delegation |

**No case requires unbounded history.** Every observed path dependence is removed by a finite summary.

## 5. Theory status

| Element | Status |
|---|---|
| single linear lifecycle | refuted |
| product/multi-coordinate state; operation frames (the idea) | survives two regimes; the frame tables have been corrected twice (ADOPT, ACCEPT) |
| record: strict immutability | **falsified**; I-B4′ (drafting window) survives |
| four-act separation (permission / authorization / commissioning / execution) | SOURCE-established (R-79, R-80) |
| authority, kind, prior state as legality variables | NECESSARY (one minimal pair each) |
| route, operation, evidence, exception | UNDETERMINED |
| variable set | **insufficient**: procedural conformance is missing |
| state with finite history summary | sufficient for every observed case |
| M3 work-gate encoding | falsified (sequence ≠ guard) |
| I-A1 | undetermined; supported under the R-72-proviso reading |
| **reopening needs materially new evidence** | **third occurrence** (L216, R-86, R-78) |

## 6. Is more corpus exposure justified? Yes, but only for minimal pairs.
The single highest-information observation is **a performed act paired with a refused act of the same operation, by the same authority, on the same kind, differing only in evidence**.
- The best candidate is **supersession**: two supersessions were refused for insufficient evidence (R-77 ARB, R-83), so a *performed* supersession by the same authority would complete the pair.
- **Proposed:** a locate-only search of the register for performed supersessions (term classes plus authority triple only). If one exists with the same authority and kind, a bounded read of that row decides whether **ε is NECESSARY**.
- A route-only pair (the same RAISE, authority and kind with a different route) would settle G-R vs G-O. None is known; it is worth a locate after the ε test.
