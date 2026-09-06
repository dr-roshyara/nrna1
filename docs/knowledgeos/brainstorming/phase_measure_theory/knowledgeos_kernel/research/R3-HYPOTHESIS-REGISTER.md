---
artifact: R3 · HYPOTHESIS REGISTER
status: RESEARCH · 9 of **16** mappings became testable hypotheses
revision: 2026-08-31 — renumbered against the revised register; `H-K13` reassigned to **Jñāna**
---

# R3 · Hypothesis Register

| ID | Hypothesis, stated so it can fail | From | Testable? |
|---|---|---|---|
| **H-K03** | KnowledgeOS requires an explicit distinction between a changing field/state and the agent that knows it: `K_t ≠ 𝒩`, and `𝒩` persists while `K_t → K_{t+1}` | GK-03/04 | ✅ |
| **H-K04b** | The source's **second** knower level (universal) has a KnowledgeOS image | GK-04, E-02 | ✅ |
| **H-K06** | `o ≠ δ(K,o)` **and** `δ(K,o₁)=δ(K,o₂) ⇏ o₁=o₂` are *required* properties | GK-06/07 | ✅ |
| **H-K11** | A valid action retains epistemic/normative validity even when its intended outcome is not achieved: `Valid(o,K) ⊥ Outcome(o)` | GK-11 | ✅ |
| **H-K05** | There is an invariant `P` with `P(K_t) = P(K_{t+1})` across transitions, which ordinary provenance does **not** already supply | GK-05 | ✅ |
| **H-K08** | Guidance can exist without authority: `Guidance ≠ Authority ≠ Decision ≠ Execution` | GK-08 | ✅ |
| **H-K09** | `Observation → Knowledge → Discrimination → Decision → Action` are **distinct epistemic operations**, not one process described five ways | GK-09 | ✅ |
| **H-K10** | `Role`, `Obligation`, `Permitted`, `Required` are formally separable, and contribute something beyond ordinary policy/role/norm | GK-10 | ✅ |
| **H-K06f** | Failed inquiry produces epistemic gain, not merely operational history | GK-11/E-06 | ✅ **and this is where the statistician is required** |
| **H-K13** | `Jñāna` maps to `Knowledge` | GK-13 | ✅ — and it **fails**: see §Jñāna |
| **H-K16** | `Sañjaya` is the observation function `Ω`, with `Battlefield` = `W` | GK-16 | ✅ **and it is already a corpus construct** |
| — | GK-01 (`Kṛṣṇa → Ω`), GK-12 (`Yoga`) | — | 🔴 **not advanced** — E-10 blocks the first; the second has no technical consequence |

## H-K06f needs its criterion stated before it is tested

Step 286 §14 forbids translating 6.40 as *"all failed experiments create knowledge."* The
statistician's criterion, stated in advance:

**A failed operation `o` yields epistemic gain iff at least one holds:**

| # | Criterion | Qualifies? |
|---|---|---|
| 1 | a hypothesis was **eliminated** — the admissible set strictly shrank | ✅ **yes** |
| 2 | **uncertainty decreased** — `U(H)` narrowed on some `H` | ✅ yes |
| 3 | **model discrimination improved** — two models previously observationally equivalent now differ | ✅ yes |
| 4 | ~~the **posterior changed**~~ → **REVISED (reviewer A, 2026-08-31)**: a posterior can move on noisy, biased or later-falsified evidence. **`new information ≠ better knowledge`.** Criterion 4 now requires a *declared metric*: `ΔU = U(H_t) − U(H_{t+1}) > 0` **or** `ΔI = I(H;E_{t+1}∣E_t) > 0`, i.e. **a measurable improvement in uncertainty, discrimination, or calibrated predictive performance** | ✅ **only with the metric declared** |
| 5 | only **operational history** was produced — `H` grew, `K` unchanged | 🔴 **NO — this is not epistemic gain** |

> **Criterion 5 is the one that matters.** Most "failed" runs produce only (5). **Treating (5) as
> epistemic gain is how a knowledge system inflates its own evidence base** — and it is exactly what
> a naive reading of 6.40 would license. **The hypothesis is admissible only with criteria 1–4 as
> its test.**

## Note on H-K05 (Ātman)

Stated **against** the null hypothesis that provenance already suffices. Candidates for `P`:
identity · provenance · lineage · historical continuity · knowledge source · epistemic identity.
**If `P` reduces to provenance, H-K05 adds nothing** — and R4 tests exactly that.

## Jñāna (H-K13) — Priority 1, and the mapping is wrong

`[S]` `Jñāna` — knowing (Ch. 4, 7, 13). The revised prompt maps it to **`Knowledge`**.

`CORPUS` — **`Knowledge` is absent from the 8 ratified primitives** *and* from the Steps 100–158
vocabulary. And the corpus's own Sanskrit reading is not "Knowledge":

> `ज्ञान (Jñāna) — Knowing | Reasoning / transformation process`, inside the **Tripuṭī**
> `Jñātā · Jñāna · Jñeya` = knower · knowing · known.

> **Corrected mapping:** `Jñātā` → the Knower (ratified `Kṣetrajña`) · `Jñeya` → the known
> (`Proposition`/assertion content) · **`Jñāna` → the act of knowing, i.e. the transformation `δ` —
> not `K`.**
>
> Mapping a Priority-1 term onto a **deliberately absent** primitive would have reintroduced
> `Knowledge` by the back door. **`R4` — and it reinforces the ratified decision.**

## Sañjaya (H-K16) — see the gap update

`Sañjaya` = state-observation capability, sitting between **Domain reality** and **State knowledge**:
`W --Ω--> O`. Carries `Knower ≠ Observer`, `K_A(Reality) ≠ K_B(Reality)`, `Access ⊨ Understanding`,
and **`Sañjaya_K = (Observed, Inferred, Reported, Unknown, Conflicting, Unresolved)`**.
**`R5` on structure.** Full treatment: `01-GAP-UPDATE-FROM-REVISED-286.md` §2.
