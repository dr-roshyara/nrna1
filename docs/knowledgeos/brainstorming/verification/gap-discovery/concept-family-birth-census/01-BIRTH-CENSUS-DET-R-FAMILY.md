---
artifact: BIRTH CENSUS — the Det_r / EvalReq / Sat / Γ concept family
date: 2026-09-10
status: **§1 DELIVERED (census, 9 lanes, firewall excluded). §2–§13 BLOCKED — see the firewall finding.**
scope: a mechanical census only. No chronological read performed; no definition selected; nothing canonicalized.
---

# Birth census — 15 objects, 9 lanes, firewall excluded

**Method.** Earliest file mtime carrying a definitional-shaped occurrence, across
`brainstorming/{kernel, verification, synthesis, mathematical_ideas_…, phase_measure_theory}`,
`reviews/`, `theory-extraction/`, and the repo-root `verification/` and `research/`.
⛔ `three_model_convergence/` excluded from every command.

| object | files outside firewall | earliest occurrence | source |
|---|---:|---|---|
| ⛔ **`Det_r`** | **1** | 2026-09-06 00:39:47 | `mathematical_ideas/…theory-part-06-evidence-evaluation-determination-calculus.md` |
| **`EvalReq`** | 5 | 2026-08-27 18:33:20 | `phase_measure_theory/step-025e-formal-epistemic-contract-algebra.md` |
| **`Sat`** | 94 | ⭐ **2026-08-27 16:25:45** | `phase_measure_theory/step-023-…knowledge-boundary.md` |
| **`Sat_c`** | 36 | 2026-09-02 09:39:25 | `mathematical_ideas/…three-valued-sat-predicate.md` |
| **`Γ`** | 344 | 2026-08-24 12:28:55 | `kernel/…typed-mathematical-epistemic-model.md` |
| **`EC_t`** | 80 | 2026-08-27 16:25:45 | `phase_measure_theory/step-023-…` |
| **`Req`** | 251 | 2026-08-16 21:09:50 | `reviews/…knowledge-placement-requirement-registration.md` |
| **`standard`** | 295 | 2026-08-17 22:23:53 | `reviews/KOS-ARCH-BASELINE-003-verification-2-report.md` |
| **`Acceptance`** | 173 | 2026-08-16 23:14:50 | `reviews/…KOS-ATTR-ARCH-001-review-decision-summary.md` |
| **`Eval`** | 72 | 2026-08-26 17:29:33 | `phase_measure_theory/question-4-evidence-and-epistemic-state-transition.md` |
| **`Eval_c`** | 41 | 2026-09-02 10:13:30 | `mathematical_ideas/…sat-stays-candidate-until-factivity-is-repaired.md` |
| **`Δ_t`** | 156 | 2026-08-25 23:31:07 | `phase_measure_theory/knowledge-qualification-problem-research-framework.md` |
| **`Zero`** | 298 | 2026-08-24 16:30:46 | `kernel/…godel-escher-bach-extraction….md` |
| **`Determination`** | 553 | 2026-08-19 00:57:24 | `reviews/…KOS-AIP04-DISCOVERY-001-…correction2.md` |
| **`Decision`** | 62 | 2026-08-26 12:21:56 | `phase_measure_theory/most-important-discovery-of-the-round.md` |

**All 15 are `TERM BIRTH` at this stage.** Not one has been promoted to `CONCEPT BIRTH` or
`FORMAL OBJECT BIRTH` — that requires reading the source, which §2 has not yet been authorised to do
at this scale.

---

## ⭐⭐ Finding 1 — `Det_r` has no history outside the firewall

| | outside 3MC | inside 3MC | outside share |
|---|---:|---:|---:|
| **`Det_r`** | **1 file** | 38 files | **2.6 %** |
| **`EvalReq`** | 5 | 44 | 10 % |
| `Sat_c` | 48 | 41 | 54 % |
| `Eval_c` | 45 | 35 | 56 % |
| `EC_t` | 68 | 93 | 42 % |

$$\boxed{\textbf{97 \% of } Det_r \textbf{'s occurrences, and 90 \% of } EvalReq \textbf{'s, are FIREWALLED.}}$$

**A concept with one occurrence has no evolution to reconstruct.** `Det_r` is a **single point** in
the readable corpus, not a trajectory. Its history — if it has one — is in the lane this
reconstruction may not open.

### The one readable `Det_r`, quoted in full

`theory-part-06` **§6.18** (2026-09-06 00:39:47), verified verbatim:

```
Let  Sat(K,r,Γ)  be the generalized satisfaction state.

A contract-specific determination function may be defined:

    Det_r :  𝒱 × EC  →  𝕊_sat

Thus:

    Sat(K,r,Γ) = Det_r( EvalReq(K,r,EC,Γ), EC )
```

| object | what this source gives | what it withholds |
|---|---|---|
| **`Det_r`** | ⭐ **a full signature** `𝒱 × EC → 𝕊_sat` | its **body** — *"may be defined"*, contract-specific |
| **`EvalReq`** | four arguments `(K,r,EC,Γ)` | ⛔ **no codomain.** It is *implied* to be `𝒱` by `Det_r`'s domain — **implied, never stated** |
| **`Sat(K,r,Γ)`** | a **composition**, not a primitive | the identity of `𝕊_sat` |

⭐ **`𝒱` is the join.** `Det_r`'s domain is the codomain `EvalReq` would have to have. The corpus
supplies the socket and not the plug. *(This is consistent with what the user reports MD-076
concluded; it is reached here independently, from the one readable source.)*

---

## ⭐ Finding 2 — a false birth caught, and `P-97` corroborated at census scale

My first census pass reported `Sat`'s earliest occurrence as **`step-008`, 2026-08-27 15:19:20** —
**66 minutes before** `step_023`, which `P-97` established as `Sat`'s birth. That would have
refuted `P-97`.

**It is a regex defect.** The pattern `Satisfied\(` matched the tail of **`IdealSatisfied(P)`** — a
different object, in a passage about `Accepted(P) ⇏ IdealSatisfied(P)`. With a word boundary,
`step_023` **2026-08-27 16:25:45** is the earliest across all nine lanes.

$$\boxed{P\text{-}97 \textbf{ CORROBORATED — and now at census scale, not queue scale.}}$$

⚠️ **Third instance of the same trap** in this programme (after `Adequacy`/`Standing`, and the
Sanskrit `Sat`). **Token birth ≠ object birth**, and a missing `\b` manufactures one.
**Rule adopted: every birth-census pattern is word-bounded, and every earliest hit is read before
it is recorded.**

---

## Finding 3 — the family does not have one birth era

| era | objects born |
|---|---|
| **2026-08-16 → 08-19** (`reviews/`, governance) | `Req` · `Acceptance` · `standard` · `Determination` |
| **2026-08-24 → 08-26** (`kernel/`, Q-series) | `Γ` · `Zero` · `Δ_t` · `Eval` · `Decision` |
| **2026-08-27** (`phase_measure_theory`, 025-series) | `Sat` · `EC_t` · `EvalReq` |
| **2026-09-02 → 09-06** (`mathematical_ideas`) | `Sat_c` · `Eval_c` · `Det_r` |

⭐ **The four oldest objects are born in `reviews/` — a governance lane — up to eleven days before
the mathematics starts.** Whether the governance `Determination` (08-19) and the mathematical
`Determination` are the same object is **exactly** the homonym question, and is **NOT YET SEARCHED**.

---

## Status of the requested deliverables

| # | deliverable | status |
|---|---|---|
| 1 | Theory Object Registry | **NOT STARTED** — requires §2 |
| 2 | Definition Evolution Registry | **PARTIAL** — `Det_r`, `EvalReq`, `Sat(K,r,Γ)` rows below |
| 3 | `TheoryState(t)` timeline | **NOT STARTED** |
| 4 | Transformation Ledger | **NOT STARTED** |
| 5 | Cross-Lane Transfer Register | **STARTED** — the era table above |
| 6 | Negative-History Register | **STARTED** — the firewall shares above |
| 7 | co-evolution map | **NOT STARTED** |
| 8 | terminal classification | ⛔ **CANNOT BE REACHED** for `Det_r` — see below |
| 9 | evidence | this document |
| 10 | smallest remaining gap | see below |

## Terminal classification — why it cannot yet be given

For **`Det_r`**, none of A–E is honestly available from this lane:

- **not A/B** — one occurrence is not an evolution;
- **not C** — one definition cannot compete with itself;
- **not D** — ⛔ **`GENUINE CORPUS GAP` requires that the full relevant corpus be exhausted, and
  97 % of it is behind the firewall.** Declaring D here would be exactly the error §10 forbids;
- **not E** — identity is not in question; there is only one object.

$$\boxed{\textbf{Correct status: } \mathbf{NOT\ YET\ SEARCHED} \textbf{ — and not searchable by this lane.}}$$

## Smallest genuinely remaining gap

**A ruling on whether this reconstruction may read `three_model_convergence/14_decision-log/` for
this concept family.** Everything else in the mission is downstream of it.

⛔ **Firewall honoured throughout.** `three_model_convergence/` was counted, never opened.
