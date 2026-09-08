# Phase 5F — Provenance and Authority Reconciliation

## Ratification vs. semantic identity — kept explicitly separate (per the authorization's §11)

| Claim | Status | Basis |
|---|---|---|
| "K-1 is ratified" | **TRUE** | FA-4 D-FA-6, "50 attack classes, no counterexample," computationally tested (D285-1) |
| "K-1 is semantically identical to Phase-5C's K-1-B" | **FORMAL EQUIVALENCE, not full demonstrated identity** — a *provenance* finding (shared package/citation), not a *ratification* finding | `09` |
| "K-1 is semantically identical to K-2 (verification lane)" | **FALSE at the structural level; TRUE at the semantic level only after unpacking; FALSE at the observational level** | D285-6's own three-level test, `05` |
| "K-1 is the canonical representation of every Kernel concept in the corpus" | **NOT SUPPORTED** — D285-1 §4 itself: *"K_t is not the knowledge state in the verification lane's sense... two different objects wearing one letter"* | D285-1 |

**These four claims are never conflated with one another anywhere in this phase's artifacts.**

## Provenance graph (focused, per the authorization's §13)

| Source | Target | Relationship | Evidence | Evidence type | Confidence |
|---|---|---|---|---|---|
| seq 0630 (step-049) | seq 1006 (D285-1) | `DERIVES_FROM` | D285-1 explicitly cites "step-049; C-022" as K-1's source | Direct evidence | High |
| seq 1006 (D285-1) | seq 1008 (D285-7) | `REPRODUCES` (identical label/definition, same package) | Both use "K-1 `K_t`, 8 primitives (ratified)" verbatim | Machine-observable (identical text) | High |
| seq 1006/D285-1 | seq 1007 (D285-6) | Same package, complementary analysis (D285-1 = comparison table; D285-6 = formal equality test) | Same "D285-x template, HPA review, 2026-08-31" header | Machine-observable | High |
| K-1 (`𝒦=(E,S,T,O,P,R,Π,A)`) | K-2 (`(𝒜,ℛ)`) | `CORRESPONDS_TO` (qualified: semantic yes, structural no, observational no) | D285-6's own three-level equality specification | Direct evidence | High |
| K-1's `Observation` primitive | Sañjaya construction (seq 0979, origin `20260826-151534`) | `CORRESPONDS_TO` (recovery path, not yet computable) | D285-6 §4b, seq 0979's own text | Direct evidence | High |
| K-1's `State` primitive | (no recovery construction found) | `EXCLUDES` (unimported, no evidenced recovery path) | D285-1 §2 ("`State`-as-primitive remains unimported") | Direct evidence (of absence) | High |
| Step 272A (`𝒪_sem`, 19 ops) | K-2 | `DEFINES` (K-2's own operation set) | D285-7 | Direct evidence | High |
| K-1 | K-2's operation set (`𝒪_sem`) | **No relationship established** — `𝒪` never enumerated against K-1's own 8 primitives | D285-7's own explicit "new finding" | Direct evidence (of absence) | High |

**No `DERIVES_FROM` edge is asserted from chronological proximity alone anywhere in this graph** — every
edge above is either a direct textual citation or a machine-observable identical-text match.

## Authority-status reconciliation of K-2's own operation set

D285-7's single most consequential *unresolved* finding, restated here because it directly bears on
every equivalence question in this phase: **the reason `𝒪_core` (the "core operation universe") cannot
be declared closed is not that K-2 fails a minimality test against K-1 — it is that no one has ever
enumerated operations against K-1's own 8-primitive set at all.** This is a **methodological gap in the
corpus's own research**, not a finding this reconstruction is positioned to fill (doing so would be
new mathematical research, not adjudication of existing evidence) — named here, not closed.
