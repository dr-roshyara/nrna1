# MD-076 — `Det_r`/`EvalReq` Birth-and-Evolution and Computability Synthesis

## Mission and method

User's mission: chronologically reconstruct the birth and evolution of `Det_r`, `EvalReq`, `Eval`,
`Eval_c`, `Req`, `r`, `standard`, `Acceptance`, `Sat`, `Sat_c`, `EC_t`, and determine — without
inventing the missing computation — whether the corpus supplies enough to actually compute
`Det_r`/`EvalReq`.

**Disclosed before any work began**: this mission's nine requested deliverables substantially
duplicate already-frozen work. `MD-067` performed the full 876-file chronological, queue-driven
traversal this mission's §1 asks for, producing birth points and typed-edge evolution histories for
exactly these objects. `MD-068` is the Definition Evolution Registry. `MD-069` is literally the
`EC_t→Req→r→Eval→EvalReq→Sat→Δ_t` `TheoryState` timeline (`T0`–`T23`) with `YES`/`RECONSTRUCTED`/
`UNWITNESSED` edge typing — the mission's own requested co-evolution diagram. `MD-070` is the
adversarial review of whether the corpus supplies `Det_r`/`EvalReq`'s semantics. A concurrent
session's `MD-073`/`MD-074` already ran the literal single-case computation attempt this mission's §7
asks about and returned **BLOCKED**, with findings sharper than anything previously synthesized (no
declared codomain for `EvalReq`; `Eval` never invoked anywhere; the decisive 3-argument `Sat(K,r,Γ)`
has zero consumers — every stipulation in the corpus uses the older 2-argument `Sat(K,r)`).

**Method chosen, consistent with this project's own standing "reuse, not redo" discipline (proven at
MD-071 and MD-075)**: synthesize the nine required deliverables directly from this already-established
evidence. **No new corpus file was read to produce this phase** — every birth point, transformation,
and computability finding below is drawn from MD-067–070/073/074's own already-committed text, cited
by artifact and line/section where those artifacts themselves cite primary source. Where the existing
material does not fully answer a specific question this mission's own framing asks (e.g., precise
per-object "earliest genuine birth" statements in the exact form requested), that gap is named
explicitly rather than silently filled.

## Frozen baseline

`MD-067`–`MD-075`, `EKS-44`/`45`/`47`/`48`/`50`. None reopened, none modified.

## Status: EXECUTED. HARD STOP.

| # | Required deliverable | File |
|---|---|---|
| 1 | Chronological birth-and-evolution report | `01_chronological-birth-and-evolution-report.md` |
| 2 | `Det_r`/`EvalReq` Definition Evolution Registry | `02_detr-evalreq-definition-evolution-registry.md` |
| 3 | Evaluation-chain dependency graph | `03_evaluation-chain-dependency-graph.md` |
| 4 | Transformation Ledger | `04_transformation-ledger.md` |
| 5 | Evidence/Falsification register | `05_evidence-falsification-register.md` |
| 6 | Current `TheoryState` impact | `06_theorystate-impact.md` |
| 7 | Terminal classification A–E | `07_terminal-classification.md` |
| 8 | Unchanged frozen conclusions + next smallest research input | `08_unchanged-frozen-conclusions-and-next-input.md` |

**Terminal classification: C — FORMALLY SPECIFIED BUT SEMANTICALLY OPEN** (see `07_...md` for the
full reasoning, including why this is more precise than D despite a genuine formula-level
inconsistency found).

**No frozen artifact modified. No new `Sat` body invented. No K-1/K2 work touched. No backlog ticket**
— the gap this mission investigates is already fully tracked (`EKS-44`/`47`/`48`); no genuinely new
load-bearing problem was discovered beyond what those tickets and `MD-073`/`074` already record.
