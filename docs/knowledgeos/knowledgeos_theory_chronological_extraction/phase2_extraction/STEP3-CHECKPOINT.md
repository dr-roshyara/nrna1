# Step 3 — Theory Readiness and Targeted Resolution · Checkpoint

| | |
|---|---|
| **Method** | ⭐ **Targeted search + formal validation** — ⛔ *not* "read five more files" |
| **Scope searched** | the whole repository, semantically |
| **Result** | **1 open term resolved · 1 claim validated empirically · 1 hypothesis falsified · 2 of my own constructions demoted** |
| **Corpus files read this step** | ⭐ **0 new narrative files.** The answers were in `engineering/governance/` |

---

## The single most important result

> ## ⛔ **`bar` was never missing. A numeric threshold is FORBIDDEN.**

`ES-003.2 — Score-Persistence Stop` (ARB 2026-07-10): *"Numeric review scores are conversational, never architectural — they are NOT persisted in repository records. Records persist **governance states** (Accepted · Rejected · Deferred · Research question · Evidence required · Stable) plus rationale."* Corroborated by `ES-006.3` (*"no composite scores"*) and `ES-004` (*"No numeric scores in records"*).

**`STR-0001` imposed a quantitative structure the corpus explicitly refuses.** I spent Iteration 1 calling `bar` the highest-value open term and searching for a number the governance had already ruled out of existence.

⭐ **Had the laboratory been built first, it would have implemented a promotion rule the governance forbids — and every downstream result would have been an artefact of that invention.** This is the NO-SILENT-COMPLETION rule earning its place.

## Score card

| Item | Before | After |
|---|---|---|
| `bar` | blocking all 6 structures | ⭐ **RESOLVED — forbidden, not missing** |
| `SI-0007` schema cannot hold its example | L3 construction | ⭐ **VALIDATED** — artifact-verified ×4, then `EXP-0001`: 39 docs, **0 counterexamples** |
| `SI-0008` empty arrow = `bar = ∞` | L2 hypothesis | ⛔ **FALSIFIED** |
| `STR-0003` check-before-admit | "total function" | ⛔ **demoted to observed pattern** — codomain conflates 3 senses; outcomes not exclusive |
| `STR-0004` append-only | "structure" | ⛔ **re-categorized as historical practice** — counterexample `IFR-0010` inside the window |
| `STR-0001` promotion | F1, awaiting `bar` | ⛔ **wrong shape**; corrected form proposed, **not adopted** |
| — | — | ⭐ **`OT-0001` opened** |

## ⭐ `OT-0001` — the new blocking term, and it is sharper

> **If `qualification_verdict` is itself assigned by the DA/ARB, then both conjuncts of `promote(k)` are governance acts — and *"evidence EARNS; governance GRANTS"* has no independent formal content.**

This threatens `SI-0009`, **the strongest object in the seed**. The question did not exist before Step 3.

## Lab-readiness

**3 of 6 pass the nine-condition gate** — `STR-0002` (narrow), `STR-0004` (as a historical property), `STR-0005`.

> ⛔ **None of the three tests the candidate theory.** Two test properties *of the corpus*; one tests *a schema defect*. **Not one tests the promotion mechanism.**

`EXP-0001` executed — the project's first empirical result. ⛔ It did **not** raise maturity to M3: M2 still requires an independent reader (§11.0), and none exists.

## Final classification

| Class | Candidates |
|---|---|
| **READY** | `STR-0002` *(narrow)* · `STR-0004` *(cat. 2)* · `STR-0005` |
| **WAITING_FOR_CORPUS** | `STR-0001` · `STR-0006` · `SI-0009` — ⭐ all three blocked by **`OT-0001`** or a missing definition |
| **UNDERDETERMINED** | `STR-0003` |
| **FALSIFIED** | `SI-0008` |
| ⛔ **BLOCKED_EXTERNAL** | `CMP-0005` *(D-1↔D-2)* · `OT-0004` *(PM-1 algebra)* — governance acts |

## What the next corpus operation should be

⭐ **Targeted search, not sequential reading.** One question decides more than 25 files would:

> **`OT-0001` — is qualification a DA act?** Search `ES-003`, the EEP, the rulings register, `Round39-MC`.

⚠️ **And a scope decision is now unavoidable.** Everything resolved in Step 3 came from `engineering/governance/` — **outside the 3,081-entry registry.** The registry's scope is not merely inconvenient; it excludes the material that answers the theory's questions.

## ⛔ Protocol adequacy

No protocol rule was found inadequate. Three earned their place:

`NO_SILENT_COMPLETION` (prevented inventing `bar`) · `Q14` (kept all structures at F1, so nothing propagated from an unvalidated formalism) · `Q22` (`EXP-0001` did not raise maturity despite succeeding).

⛔ **No protocol change proposed.**
