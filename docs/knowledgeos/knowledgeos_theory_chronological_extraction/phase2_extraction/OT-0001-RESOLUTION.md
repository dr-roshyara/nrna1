# `OT-0001` — Does *"Evidence EARNS; Governance GRANTS"* have independent formal meaning?

| | |
|---|---|
| **Question** | If `qualification_verdict` is itself assigned by the DA/ARB, both conjuncts of `promote(k)` are governance acts and `SI-0009`'s central asymmetry has **no independent formal content** |
| **Method** | Targeted search of **governance evidence** — ⛔ not more corpus reading |
| **Result** | ⭐ **RESOLVED — the two ARE independent, but not for the reason the corpus states** |

---

## The two models under test

**Model A — two independent mechanisms** *(what `SI-0009` claims)*

```
Evidence → Qualification ─┐
                          ├─→ Promotion
             Governance ──┘
```

**Model B — one mechanism wearing two names** *(the threat)*

```
Evidence → DA/ARB evaluates → Governance decision → Promotion
```

Under **B**, *"evidence EARNS"* is a **descriptive business principle**, not a formal mechanism.

## The evidence

`engineering/governance/ES-003-Qualification.md`:

| Clause | Text |
|---|---|
| Header | ⭐ *"**Authority:** Decision Authority (ARB); **instruments produce, the authority accepts.**"* |
| Compliance | *"AI + Machine (**instruments**) + Human (**acceptance**) — **the AI evaluates, governance decides**"* |
| `ES-003.3` / `R-26` | ⛔ *"The measuring instrument runs → captures → returns PASS/FAIL → stops; **no interpretation inside the instrument**"* |
| `ES-003.1` | `Qualification → Findings → DA approves corrections → Engineer implements → Re-run → Verdict` |

## ⭐ The finding — Model A, on a mechanism the corpus never states

The two conjuncts are independent because **two different actors are each structurally incapable of the other's act**:

| | Instrument | Authority |
|---|---|---|
| **Produces a verdict** | ✅ its only function | ⛔ cannot — *"instruments produce"* |
| **Accepts** | ⛔ **cannot — `R-26`: no interpretation inside the instrument** | ✅ *"the authority accepts"* |

> ## ⭐ **The independence is a SEPARATION OF POWERS, enforced by `R-26`.**
>
> **`SI-0009` survives formalization — and is strengthened.** F0018 asserts the asymmetry (*"no ruling can make an observation Replicated"*) as an observation about causal engines. ⛔ **It never identifies the mechanism.** The mechanism is a governance rule forbidding the instrument to interpret, and a standard declaring that instruments produce while authorities accept.

## The corrected formalization — two vocabularies, two stages

```
verdict : Instrument × Artifact → { PASS · PASS_AFTER_CORRECTION · WARN · FAIL }     (ES-003.1)
accept  : Authority  × Verdict  → { Accepted · Rejected · Deferred ·
                                    Research question · Evidence required · Stable } (ES-003.2)

promote(k)  ⟺  verdict(k) ∈ Passing  ∧  accept(verdict(k)) = Accepted
```

⭐ **Neither conjunct is a threshold.** Both are **discrete outcomes from closed sets**, produced by different actors. `ES-003.2` forbids the numeric comparison my original `ev(k) ≥ bar` assumed.

⚠️ **Not adopted.** This is a **construction from governance evidence**, at L3. The corpus never writes this predicate.

## Open sub-questions

| # | Question | Status |
|---|---|---|
| `OT-0001a` | Which verdicts are `Passing`? `PASS_AFTER_CORRECTION` presumably qualifies; `WARN` is unclear | ⛔ OPEN — corpus-findable |
| `OT-0001b` | Is `accept` total? Five of six governance states are not acceptance — what happens to `Deferred`? | ⛔ OPEN |
| `OT-0001c` | ⭐ Is `R-26` *enforced*, or merely stated? If an instrument does interpret, the separation collapses | ⛔ OPEN — **empirically testable** |

## ⭐ A second result, unlooked for

`ES-003.1`: *"a corrected failure is **never re-labeled a clean pass**; history is part of the verdict."*

> **That is `STR-0004`'s append-only property — stated as a GOVERNANCE RULE, not merely observed as a practice.**

`STR-0004` was demoted in Step 3 to *category 2, a historical property of the corpus, with one violation*. It now has a **normative source** for the verdict domain specifically. ⛔ **This does not restore it to a logical invariant** — `IFR-0010` remains a counterexample in the *document* domain, and the rule governs *verdicts*. **Two domains; the rule covers one of them.**

## Effect on the candidate theory

| Item | Before | After |
|---|---|---|
| `OT-0001` | blocking `STR-0001` + `SI-0009` | ⭐ **RESOLVED — Model A** |
| `SI-0009` | `DERIVATION_INCOMPLETE`, at risk | ⭐ **mechanism supplied; strengthened** |
| `STR-0001` | wrong shape, no replacement | ⭐ **two-stage form derived from governance evidence** |
| `STR-0004` | category 2 | ⚠️ **normative source found for the verdict domain only** |
| — | — | **3 new sub-questions, one empirically testable** |

## ⛔ What this does **not** establish

Not that the mechanism *works* — `promote` has **zero observed instances** in the window, and whether the back-edge ever ran is still contested (`CMP-0001`). **A well-formed predicate with no instances is not a validated theory.**
