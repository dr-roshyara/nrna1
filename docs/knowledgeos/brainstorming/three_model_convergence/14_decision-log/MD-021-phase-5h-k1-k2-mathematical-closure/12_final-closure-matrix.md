# Phase 5H — Final Closure Matrix (required, per the authorization's §17–18)

## Per-target closure classification

| Target | Closure status |
|---|---|
| **K-1 operators (𝒪_1)** | **PARTIALLY CLOSED** — 9 named, typed transformations reconstructed (`02`); no complete governed register exists (**SOURCE-RESEARCH GAP**, `11` Gap 1) |
| **`Qualify`** | **PARTIALLY CLOSED** — type signature reconstructed and triangulated (`04`); algorithm is a confirmed **SOURCE-RESEARCH GAP** (`11` Gap 2); a further arity inconsistency found (`11` Gap 5) |
| **State mapping** | **SOURCE-RESEARCH GAP** — the projection target itself is undefined (`06`, `11` Gap 3); no partial closure was possible |

## Required final matrix

| Question | Existing evidence | New evidence (this phase) | Mathematical status | Knowledge-engineering status | Final |
|---|---|---|---|---|---|
| K-1 operators | Named in seq 0630 §49.31/76 | Full re-read of seq 0630 (2,232 lines, vs. ~900 read previously); confirmed no enumeration exists | 9 typed transformations, no preconditions/postconditions/invariants | `RECONSTRUCTED PROVENANCE` (level 3) | **PARTIALLY CLOSED** |
| Observation → e | D285-6's brief mention only | seq 0630 §49.76, seq 0795 §170.4, `e_equality.py`'s `A(P,e,c,t,Pi)` | Type mapping evidenced; algorithm absent | `DIRECT SOURCE EVIDENCE` for the type; `RESEARCH INTERPRETATION` for "e=Evidence" | **PARTIALLY CLOSED** |
| Qualify | D285-6's brief mention only | seq 0630, seq 0795, both triangulated | Type signature RECONSTRUCTABLE; algorithm NOT EVIDENCED; arity conflict found | `DIRECT SOURCE EVIDENCE` (type); `MACHINE-OBSERVABLE FACT` (absence of body) | **PARTIALLY CLOSED** |
| State → ? | "the carrier," unexpanded | Corpus-wide "carrier" search, 10 occurrences, none matching | `NOT EVIDENCED` | `MACHINE-OBSERVABLE FACT` (absence confirmed by search) | **SOURCE-RESEARCH GAP** |
| Assertion expansion | Two conflicting prose lists (Phase 5G) | Two conflicting **code** variables, disagreeing in the same pattern; a third, structurally different code variant (`e_equality.py`) | Internally contradictory across 4 sources | `MACHINE-OBSERVABLE FACT` (the conflict itself) | **SOURCE-RESEARCH GAP** (central finding) |
| K-1 → K-2 projection | Phase 5G: partial, non-total | Precisely re-specified per-component (`08`); the non-computability isolated to Observation specifically | Partial, non-total, one component confirmed non-computable, one component's target undefined | Mixed levels, stated per-cell in `08` | **PARTIALLY CLOSED** |
| Information loss | Phase 5G: "lossy by design" (unspecific) | D1–D7 classification applied per primitive (`09`) | 3 primitives D1 (asserted, unverified); 1 primitive D4; 1 primitive D7 | `DIRECT SOURCE EVIDENCE` (D1 claims); `RECONSTRUCTED PROVENANCE` (D4/D7 classification) | **PARTIALLY CLOSED** |
| Semantic equality | Phase 5G: downgraded to "partial correspondence, unverified" | The corpus's own `t285_equality.py` supplies a precise, self-correcting `=_semantic` definition — but `07`'s own conflict undermines its application | Well-defined relation; internally contested application | `DIRECT SOURCE EVIDENCE` (the definition); `MACHINE-OBSERVABLE FACT` (the contested application) | **PARTIALLY CLOSED, reframed** (`10`) |

## Overall result (per the authorization's §17, choosing exactly one, not forcing A)

**B — Projection partially closable; explicit source gaps remain.**

Not A: full closure is not supported (State's target is genuinely undefined; the operator register
does not exist; Qualify's algorithm does not exist). Not C: this understates what was actually closed
this phase (Observation's mapping, Qualify's type signature, and the operator census all advanced
meaningfully beyond Phase 5G's own treatment). Not D in the narrow sense the authorization intends
(internally contradictory evidence, full stop) — though a genuine contradiction was found (`07`), it
is disclosed and bounded, not a blanket indictment of all evidence; most of this phase's other
findings are internally consistent. Not E: enough was established to avoid a blanket "insufficient
evidence" verdict.
