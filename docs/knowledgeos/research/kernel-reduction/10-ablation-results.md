# 10 — Ablation Results (Part VIII)

## A. Baseline — and it fails `[EXP]` `[NEG]`

| Arm | Capabilities achieved | Lost | Scenarios passed |
|---|---|---|---|
| **`C0` (13 operators, as given)** | **21 / 25** | `C10, C16, C17, C25` | **9 / 16** |
| **`C0+ = C0 ∪ {Qualify}` (14)** | **25 / 25** | — | **16 / 16** |

`C0` fails scenarios S1, S2, S3, S4, S8, S9, S15.

**The cascade.** `C0` holds no `evidential-qualification` atom → `Evidence` is unreachable →
`Verdict` is unreachable (a verdict requires evidence) → C10 *validate*, C16 *uncertainty* and C17
*assumptions* all collapse with it.

> `[EXP]` **The 13-operator candidate set cannot validate anything, because it cannot produce
> evidence.** The gap sits exactly where the prior corpus lane had already recorded
> `Qualify : Observation × Policy ⇀ Evidence` as `G1` — irreducible. The experiment rediscovered a
> known gap from an independent direction, which is the strongest form of corroboration available here.

Per Part VIII the baseline must succeed before ablations are interpreted, so all ablation below is
run on `C0+`. The raw `C0` arm is retained as robustness variant **V7** (§12) so nothing is hidden.

## B. Leave-one-out over `C0+`

| Operator | Exclusive atom(s) | Capabilities lost | Scenarios lost | Failure classes | Result |
|---|---|---|---|---|---|
| `Observe` | `world-contact` | C1 C2 C3 C4 C6 C7 C9 C10 C14 C16 C17 C19 C20 C21 C22 C24 C25 | 13 of 16 | F1 F2 F5 F6 F9 F13 F16 F17 | **A** irreducible |
| `Interpret` | `meaning-assignment` | C2 C3 C4 C6 C7 C9 C10 C14 C16 C17 C19 C20 C21 C22 | 12 of 16 | F2 F13 F16 F17 | **A** irreducible |
| `Represent` | `symbolic-encoding` | C3 | 5 (S1–S4, S16) | F1 | **A** irreducible |
| `Relate` | `relational-linking` | C4 C22 | 2 (S10, S16) | F3 | **A** irreducible |
| `Discriminate` | **none** | — | 0 | — | **B** derivable |
| `Hypothesize` | `content-generation` | C6 C19 C20 | 5 (S5,S7,S8,S14,S15) | F5 F16 F17 | **A** irreducible |
| `Infer` | `entailment` | C7 | 1 (S9) | F6 | **A** irreducible |
| `DetectGap` | **none** | — | 0 | — | **B** derivable |
| `Challenge` | `adversarial-negation` | C9 | 1 (S9) | F8 | **A** irreducible |
| `Validate` | `warrant-assessment` | C10 C16 C17 | 3 (S8,S9,S15) | F9 | **A** irreducible |
| `Revise` | `state-mutation` | C11 C15 C24 | 2 (S4, S6) | F10 F14 | **A** irreducible |
| `Determine` | `closure-judgment` | C12 C23 | 3 (S7,S11,S12) | F11 | **A** irreducible |
| `Select` | `preference-over-actions` | C13 | 1 (S13) | F12 | **A** irreducible |
| `Qualify` | `evidential-qualification` | C10 C16 C17 C25 | 7 | F9 | **A** irreducible |

**12 of 14 apparently irreducible; 2 derivable.** No operator classified C, D, E or F at this stage —
though §13 revisits `Discriminate`, `Revise` and `Select` on DDD grounds and the final table records
`Hypothesize`, `Represent` and `Select` as evidentially weak (§02).

## C. The two derivable operators — explicit reconstructions

### `DetectGap` — DERIVABLE-CANDIDATE, `SMUGGLING = FALSE`

```
NormDelta      := norm-comparison ( EpistemicState , IdealState )     [ from Determine ]
Gap            := difference-decision ( NormDelta )                    [ from Discriminate ]
```

| Check | Result |
|---|---|
| input types | `EpistemicState × IdealState` — both ambient, unchanged |
| output type | `Gap` — identical carrier, by the same derivation rule |
| preconditions | unchanged (`IdealState` defined) |
| postconditions | unchanged |
| semantic effect | identical: a decided difference against a normative target |
| epistemic effect | identical |
| information loss | none — same atoms in the same order |
| new assumptions | **none** |
| semantics secretly embedded elsewhere? | **NO.** `Determine` holds `norm-comparison` *independently* of `DetectGap`, from its own corpus definition (compare `S_t` with `I_t`); `Discriminate` holds `difference-decision` *independently*, from the SD-2 *Buddhi* family. Neither operator was redefined. |

**Triple convergence.** The ablation says derivable; the composition says derivable; and the corpus,
*independently and earlier*, classifies `DetectGap` into `𝒪?` — derived evaluations, signature
`f(K_t) → Result` — rather than the state-changing class `𝒪⁺` (§02 MR-3). This is the single
most strongly supported result in the experiment.

**It also confirms prior constraint 4 by experiment:** `Zero(K_t, I_Q, EC)` behaves as a *state
predicate over a normative comparison*, not as a primitive transformation.

### `Discriminate` — DERIVABLE, but **DEGENERATELY SO** `[NEG]`

```
Discrimination := difference-decision ( any discriminable carrier )    [ from DetectGap ]
```

Type-checks, no smuggling — but the *only* donor is `DetectGap`, and `DetectGap` is itself derivable.
The two are not independently removable (§11). The correct reading is not "`Discriminate` is
derivable" but "**exactly one of `{Discriminate, DetectGap}` is redundant**". Under variant V4, where
`DetectGap`'s difference-decision is scoped to the norm delta (a defensible reading of its corpus
definition), `Discriminate` becomes irreducible and `DetectGap` stays derivable.

## D. Atom-level leave-one-out — the decisive level

| Atom | Holders | Capabilities lost | Result |
|---|---|---|---|
| `world-contact` | Observe | C1 C2 C3 C4 C6 C7 C9 C10 C14 C16 C17 C19 C20 C21 C22 C24 C25 | irreducible |
| `meaning-assignment` | Interpret | C2 C3 C4 C6 C7 C9 C10 C14 C16 C17 C19 C20 C21 C22 | irreducible |
| `symbolic-encoding` | Represent | C3 | irreducible |
| `relational-linking` | Relate | C4 C22 | irreducible |
| `difference-decision` | **DetectGap, Discriminate** | C5 C8 C19 C20 | irreducible |
| `content-generation` | Hypothesize | C6 C19 C20 | irreducible |
| `entailment` | Infer | C7 | irreducible |
| `norm-comparison` | **DetectGap, Determine** | C8 C12 C23 | irreducible |
| `adversarial-negation` | Challenge | C9 | irreducible |
| `warrant-assessment` | Validate | C10 C16 C17 | irreducible |
| `state-mutation` | Revise | C11 C15 C24 | irreducible |
| `closure-judgment` | Determine | C12 C23 | irreducible |
| `preference-over-actions` | Select | C13 | irreducible |
| `evidential-qualification` | Qualify | C10 C16 C17 C25 | irreducible |

> `[EXP]` **Relative to the tested capability model and semantic algebra, all 14 atoms are
> irreducible powers, while only 12 of 14 operators are.**
>
> The candidate kernel is **minimal at the level of semantic powers and non-minimal at the level of
> operator packaging.** The redundancy is entirely in the packaging, not in the epistemics.

## E. Semantic-smuggling control results `[EXP]`

Every probe below is `SMUGGLING = TRUE` and `REJECTED`. They are reported because the outcome is
uniform and instructive:

| Removed | Absorbed into | Full coverage restored? | Verdict |
|---|---|---|---|
| DetectGap | Validate | yes | REJECTED |
| Challenge | Validate | yes | REJECTED |
| Determine | Infer | yes | REJECTED |
| Select | Determine | yes | REJECTED |
| Interpret | Represent | yes | REJECTED |
| Qualify | Observe | yes | REJECTED |
| Hypothesize | Infer | yes | REJECTED |

> `[EXP]` **Absorption restores full capability coverage in 7 of 7 cases.** Reduction-by-absorption
> is *always* available and *always* worthless. Any minimality claim that does not carry an explicit
> smuggling test is therefore unfalsifiable — including, had it lacked one, this one. The two
> reconstructions in §C are the only ones in this experiment that pass without absorption.
