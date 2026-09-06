# E — The Factivity Repair Experiment · `KR-SIM-2026-09-02-C`

**Commissioned by** `20260902-092821_review-of-v1-2-sat-stays-candidate-until-factivity-is-repaired.md`
**Status** `[EXP]` · Same scenarios, **same worlds**, paired across arms. **No new randomized
experiment** — the existing 10 000 worlds are re-used, and every arm sees the identical stream.

## The obstruction being repaired

```
factivity   vs.   Γ-determinacy
```

If two possible worlds share an epistemic input `E_t` but differ in truth, a total deterministic
`Γ(E,Q,C,EC)` cannot guarantee factivity. Established by witness in the v1.1 run and reproduced
under v1.2.

## Results — 10 000 worlds, paired

| arm | attributions | false | knowledge claims | false knowledge | **factivity violation** | coverage | decision lane |
|---|---|---|---|---|---|---|---|
| **baseline** (v1.1/v1.2 as written) | 3 320 | 390 | 3 320 | 390 | **0.1175** | 0.3320 | 0.3320 |
| **R1** rename `K_t` | 3 320 | 390 | **0** | **0** | **n/a** | 0.3320 | 0.3320 |
| **R2** externalize factivity | 3 320 | 390 | **0** | **0** | **n/a** | 0.3320 | 0.3320 |
| **R3** partial `Γ` *(as specified)* | 1 143 | 135 | 1 143 | 135 | **0.1181** | 0.1143 | 0.1143 |
| **R3′** partial `Γ`, channel **consulted** | 3 497 | 0 | 3 497 | **0** | **0.0000** | 0.3497 | 0.3497 |

## Finding 1 — **R3 as specified does not repair factivity** `[EXP]` `[NEG]`

`0.1181` against a baseline of `0.1175` — **indistinguishable, and marginally worse.** It buys
nothing and costs **66 % of coverage**.

**Root cause.** R3 attributes where a verification channel *exists*. **Existence of a channel is not
consultation of it.** Whether a property happens to be verifiable is independent of whether the
source reporting it is misleading, so restricting attribution to verifiable properties filters on the
wrong variable entirely.

> `[INF]` The specification "make `Γ` partial" is under-determined. Partial **on what**? Partiality
> by *availability* is not partiality by *warrant*, and only the second could touch factivity.

## Finding 2 — **R3′ repairs factivity by ceasing to be an epistemic system** `[EXP]` `[NEG]`

R3′ consults the channel and uses its result. Violations: **0**. Coverage: **1.053× the baseline** —
*higher*, which is the tell. Provenance of R3′'s attributions:

| | count | share |
|---|---|---|
| epistemically earned — the pipeline agreed | 1 008 | **0.2882** |
| **read off the world** — the pipeline had no determination at all | 2 354 | **0.6731** |
| **overrode the pipeline** — the pipeline said something else | 135 | **0.0386** |

> **71.2 % of R3′'s attributions are not products of the epistemic pipeline.**

R3′ does not repair the epistemic system; it **bypasses** it. Note also that `Γ` in R3′ reads
`world.truth`, so it is **no longer a function of `E` alone** — which is exactly the premise the
impossibility rests on. R3′ escapes the theorem by leaving its hypotheses, not by refuting it.

## Finding 3 — **R1 and R2 are behaviourally identical** `[EXP]`

Every number matches to the unit: 3 320 attributions, 390 false, 0 knowledge claims. They differ
**architecturally**, not epistemically:

| | R1 rename | R2 externalize |
|---|---|---|
| what the kernel emits | `AttributedState` `A_t` | `ClaimToKnowledge` |
| who may assert `Knows` | nobody — the term is retired from the kernel | an **external verifier**, a component the kernel does not contain |
| new component required | none | the verifier |
| what `DEF-1` then governs | a predicate the kernel never computes | the verifier's output |

**R2's useful measurement:** of the claims the kernel emits, **2 930 / 3 320 = 88.25 % survive
external verification.** That number is only available *because* verification was externalized —
under the baseline it is invisible, since every claim is already labelled knowledge.

## Verdict on the three repairs

| repair | fixes factivity? | cost | assessment |
|---|---|---|---|
| **R1 rename** | **yes, by construction** | vocabulary only | **genuine repair.** 390 false attributions remain, but none is called knowledge — and the theory never claimed attributions were factive |
| **R2 externalize** | **yes** | one new component; **no kernel component may ever assert `Knows`** | **genuine repair, and the most informative** — it makes the 88.25 % survival rate measurable |
| **R3 as specified** | **no** — `0.1181` vs `0.1175` | −66 % coverage | **REFUTED** |
| R3′ corrected | yes | 71.2 % of attributions become non-epistemic | **not a repair** — it dissolves the problem by removing the epistemics |

> `[EXP]` **Two of the three proposed repairs work, and they are the same intervention: stop calling
> it knowledge.** The third fails as specified and, when corrected, succeeds only by abandoning the
> epistemic pipeline.

## Consequence for `Sat`

The review withheld `[DEF]` from `Sat : 𝒦 × ℛ → {⊤,⊥,U}` pending this experiment. On the evidence:

* the experiment **does not close `Sat`** — it settles what `Γ` may claim, not what satisfaction means;
* but it **removes the blocker the review named**: under R1 or R2 there is no longer a contradiction
  between factivity and `Γ`-determinacy, because `Γ` no longer claims factivity;
* `Sat` should therefore stay **`[PROP]`** — for the *other* reasons this lane found (E1: `Zero` is
  three-way ambiguous; `⪰` undefined; class exhaustiveness unknown), **not** for the factivity reason.

`[NORMATIVE]` Choosing between R1 and R2 is an architecture decision, not an experimental one. The
measurable difference is that **R2 buys an observable verification rate and R1 does not.**

## What this experiment cannot establish

* Whether real epistemic systems can obtain external verification at acceptable cost. `verifiable`
  was a Bernoulli draw at `p = 0.35`, chosen by the experimenter.
* Whether 390 false attributions out of 3 320 is tolerable. That is a policy question about the
  admission policy, which is where every violation originated.
* That renaming is *sufficient* — R1 removes the contradiction without removing the false
  attributions. A system that is wrong 11.75 % of the time is not improved by relabelling its output;
  it is only **described more honestly.**
