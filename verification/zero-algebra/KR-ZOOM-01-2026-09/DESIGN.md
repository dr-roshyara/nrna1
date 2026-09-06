# `KR-ZOOM-01` — DESIGN
## Recursive Epistemic Zoom

**Experiment ID:** `KR-ZOOM-01-RECURSIVE-EPISTEMIC-ZOOM-2026-09`
**Status:** research experiment · **experimental / non-adjudicated**
**Theory v1.2 FROZEN · kernel NOT SELECTED · no algebra declared · no carrier declared**
Statuses per `docs/knowledgeos/governance/EPISTEMIC-STATUS-VOCABULARY.md`.

---

## 1. Question

> Can an observed value $x_r$ at one epistemic resolution become a new epistemic substrate
> whose structure can be observed at resolution $r+1$?

$$K_r \xrightarrow{\;P_S\;} K_r^S \xrightarrow{\;\mathrm{Obs}_Q\;} x_r \xrightarrow{\;Z_{Q,\tau}\;} K_{r+1} \xrightarrow{\;\text{re-base}\;} K_{r+1}^{sub}$$

**Not assumed to be universally valid.** The experiment is designed so it can fail.

## 2. Conventions inspected before writing code

`KR-ZERO-*`, `KR-REP-REDUCTION`, `KR-BRIDGE-01/02/03`, `KR-STATE-01`. Reused: typed elimination
$E^-$; the intervention definition of `Zero`; train/test with independent recorded seeds; controls
declared before execution; pre-registered labels verified after (`A1`); no generator tuning to
balance cells (`A2`); adversarial audit before interpretation. **Nothing was imported silently**:
`Zero` here is re-derived by intervention, never inherited as a verdict.

## 3. Definitions (experimental)

| | |
|---|---|
| $K_r$ | a state: typed dimensions $(\mathrm{id},\mathrm{dtype},\mathrm{name},\mathrm{value},\mathrm{weight})$ |
| $P_S(K)$ | focus. Returns **active** and **EXCLUDED**. `[DEF]` **Excluded $\ne$ Zero** |
| $\mathrm{Obs}_Q$ | bucketed weighted score over the dtypes $Q$ reads $\to$ `OK/WATCH/STRESS/CRITICAL/UNDETERMINED`, plus a deterministic **anchor** |
| $Z_{Q,\tau}$ | **lookup** of the anchor's host node's generated $\tau$-children. Never invents structure |
| $E^-_d$ | typed elimination |
| $\mathrm{Zero}_r(d)$ | $\mathrm{Obs}_Q(K)=\mathrm{Obs}_Q(E^-_d(K))$ — **intervention only** |
| Atomic$(x\mid r,\tau)$ | no admissible **non-trivial** refinement. **Never** defined by data type |

`[DEF]` **Non-trivial** $= \ge 2$ dimensions **and** $\ge 2$ distinct dtypes.

**The anchor is what makes Zoom auditable.** $\mathrm{Obs}_Q$ returns the dimension that
dominated the observation; Zoom expands *that* dimension's host node. Every exposed relation is
traceable to generated data.

## 4. Four traversal regimes

`INWARD` (STRUCTURAL) · `OUTWARD` (CONTEXTUAL) · `RETROSPECTIVE` (HISTORICAL) ·
`PROSPECTIVE` (CONSEQUENTIAL).

`[REC]` **Not** orthogonal axes, **not** assumed inverse, **not** assumed to commute. Typed
regimes only. Commutation is measured (§8).

## 5. Domain and generator

Synthetic **organizational liquidity event**. dtypes: `INTERNAL CONTEXT HISTORY CONSEQUENCE
EVIDENCE TEMPORAL PROVENANCE`. $Q$ reads **only** `INTERNAL, EVIDENCE`; focus
$S = \{$`INTERNAL, EVIDENCE, CONTEXT`$\}$.

Generator: a refinement forest, `MAX_DEPTH = 4`, 1–2 children per direction, each direction
**independently terminating** with probability rising in depth. 300 roots per split.

> **Declared generator constraint (not post-hoc tuning):** a **root** must be observable under
> $Q$ — otherwise there is no value to zoom from. Deeper nodes carry no such constraint, so
> `UNDETERMINED` there is informative.

**The domain is a test substrate. KnowledgeOS is not claimed to have these dimensions.**

## 6. Hypotheses

| | |
|---|---|
| **H1** | Zoom exposes a non-trivial state |
| **H2** | Atomic$(x\mid r)$ and $\lnot$Atomic$(x\mid r+1)$ |
| **H3** | $\mathrm{Zero}_r(d)$ but $\lnot\mathrm{Zero}_{r+1}(d)$ — **exposure alone does not count** |
| **H4** | Zoom changes the contract observable |
| **H5** | $K_{r+1}$ supports a further reasoning cycle |
| **H6** | $C_Q(K_{r+1}) \equiv_Q x_r$ |

## 7. Three decisions taken **before** execution

**D1 — $Q$ and $S$ are held FIXED across resolution in the primary arm.** H3 is otherwise
trivially satisfiable by changing the question. This is the activation-attribution trap already
recorded for `KR-STATE-01`. A **varying-$Q$ contrast arm** is run separately and **never pooled**.

**D2 — the counterfactual ANCHOR trap.** §13 retains vs eliminates $d$ and zooms both. **If the
eliminated dimension IS the anchor**, the branches diverge for a bookkeeping reason. Cases are
classified `ANCHOR` / `NON_ANCHOR` and the counterfactual is adjudicated **only on `NON_ANCHOR`**.
*(Directly carried from the REF/NO-REF decision in `KR-STATE-01-DESIGN`.)*

**D3 — four separate path equalities**: final state · observable · intermediate state ·
relation set. **Never collapsed into one commutativity metric.**

**D4 — visible $\ne$ relevant.** `newly_exposed_dimensions` is descriptive; relevance requires
an intervention.

## 8. Path / order test

For each unordered direction pair, run $\tau_i\!\to\!\tau_j$ and $\tau_j\!\to\!\tau_i$ and record
the four equalities separately, plus the "one determines, the other does not" cell.

## 9. Controls

| | |
|---|---|
| **A** terminal value | no non-trivial Zoom |
| **B** structured value | inward Zoom succeeds |
| **C** context-only | outward succeeds while inward terminates |
| **D** history/consequence | retrospective/prospective expose structure |
| **E** visible but non-operative | visibility does not imply Zero failure |
| **F** excluded becomes relevant after Zoom | the H3 mechanism |

## 10. Reproducibility

`seed_train = 20260904`, `seed_test = 88020260904`. Deterministic replay asserted byte-for-byte
(`code/replay.py`). No generator tuning after seeing hypothesis outcomes.

## 11. Forbidden conclusions (spec §22)

Not to be written under any result: *infinite dimensions · knowledge is fractal · Zoom is a
topological operator · the four directions are orthogonal · Zoom and Compression are inverses ·
Zero is the identity of an epistemic algebra · recursive Zoom is a kernel primitive or a
universal law.*

**Permitted form:** *"H1 was supported in the tested synthetic regime."*
