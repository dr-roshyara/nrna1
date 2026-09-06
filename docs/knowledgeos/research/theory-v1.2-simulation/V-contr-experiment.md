# V — Separating the Three Contradiction Models · `KR-CONTR-MODELS-2026-09`

> **✅ ID resolved.** Canonical ID **`KR-CONTR-MODELS-2026-09`** · **alias `KR-CONTR-2026-09`
> (legacy)** — see [`EXPERIMENT-ID-REGISTRY.md`](EXPERIMENT-ID-REGISTRY.md). The bare ID is **retired
> as a live name**; the companion experiment is **`KR-CONTR-EVAL-2026-09`**
> ([`W-KR-CONTR-EVAL-2026-09.md`](W-KR-CONTR-EVAL-2026-09.md)). **This artifact's original provenance
> is unchanged** — it answers Experiment G's narrower commission (*which of three models*), not the
> protocol's (*what evaluation structure is required*). **The two agree where they overlap**: both find
> a contradictory state closes, and both locate the defect in the readings.
>
> **Provenance graph** — recorded here as well as in the successor, so it cannot become ambiguous:
>
> ```
> KR-CONTR-MODELS-2026-09     predecessor / model-separation   ← THIS ARTIFACT
>          ↓                  commissioned by Experiment G
> KR-CONTR-EVAL-2026-09       structured-evaluation experiment
>          ↓
> KR-COMP-2026-09             composition   (not run)
> ```
>
> **Scope note carried from the `EVAL` review:** `M3 ≅ M4` below holds **at the tested assignment level
> only** — §5 shows they separate under a token-sensitive composition rule.

**Baseline** KnowledgeOS Theory **v1.2**, unchanged · **No v1.3** · **Status** `[EXP]`/`[NEG]`
**Factivity** DECIDED (`R1`): `K_t → A_t`. This experiment uses `A_t` vocabulary where it matters.
**No contradiction model is adopted here.**

**Commissioned by Experiment G:**

> *"the deterministic suite discriminates the composition rules but **not** the contradiction models.
> That is the experiment to design next — a case that separates the three contradiction models.
> **Do not guess between them.**"*

**Code** `research/knowledgeos-sim/kos12/contr.py` · **Runner** `run_contr.py` ·
**Results** `results/contr/*.json` (10 files) · **Deterministic — no seed, no sampling.**

---

# 1. Headline

> ## The commissioned question was malformed. **There are not three contradiction models. There are two.**

**`M3` (three-valued, `UNDEFINED`) and `M4` (four-valued, `C`) are isomorphic as value assignments** —
a relabeling, with **0 commutation failures over the full parameterization**. Only **`MD` (delegated)**
is genuinely distinct, and it is **separated from both**.

| finding | test | result |
|---|---|---|
| **`M3` ≅ `M4`** under relabeling `UNDEFINED ↔ C`, `CONTRADICTORY_INPUT ↔ CONTRADICTION` | `C9` | **0 / 8 failures** |
| **`MD` separated from both** `M3` and `M4` | `C4` | **SEPARATED** at value *and* boundary level |
| the separating object is a **pair**, not a case | `C4` | `(X1, X2)` — both corpus-attested |
| **`D-0`'s "all three agree" is a MASKING artifact** | `C8` | separable in isolation; **masked by ONE unrelated `U`** |
| **the fourth-value question is a corollary of the composition question** | `C10` | separates **iff** the rule is token-sensitive; corpus supplies none |
| separation is **not** an artifact of bucket assignment | `C7` | holds in **4/4** assignments |
| `D-0` control reproduced | `C6` | contradictory state still **closes** |

---

# 2. The three models, as the corpus defines them

Taken verbatim from `kos12/evalc.py` — the authoritative prior implementation — **not restated from
memory.** Buckets come from `kos12/expG.BUCKET`, imported rather than re-declared.

| model | `content` on contradictory input | value in `{T,F,U}`? |
|---|---|---|
| **`M3`** three-valued | `UNDEFINED` / `CONTRADICTORY_INPUT` *(agent)* | **no** |
| **`M4`** four-valued | `C` / `CONTRADICTION` *(agent)* | **no** |
| **`MD`** delegated | `U` / `NO_EVALUATOR` *(theory)* | **yes** |

**`MD` routes contradictory input through `Sat_consistency`, which is undefined because `Contr` is
undefined.** The routing step is where the information is lost: after it, nothing records that the
input was contradictory rather than merely unevaluated.

## 2.1 A correction I had to make before any result was valid

My first encoding had `M3` return `U`. **`evalc.py` returns `UNDEFINED`** — a value *outside*
`{T,F,U}`, which is the entire point. The error surfaced because `C1` then contradicted `D-0`'s
recorded agreement, and a new result that contradicts an established one is a signal to check the new
one first. Corrected against the authoritative module; every figure below is post-correction.

---

# 3. The separating object is a **pair**, not a case

The commission asked for *"a case that separates the three models."* **No single case can do it**, and
the reason is structural: models are **functions**, and functions are separated by their **kernels** —
the equivalence relation they induce on the case space. Two models are separated by a pair `(a,b)`
**iff they disagree about whether `a` and `b` are alike.**

**The pair, both members corpus-attested:**

| | provenance | `M3` | `M4` | `MD` |
|---|---|---|---|---|
| **`X1`** agent holds `{p, ¬p}` | corpus `S1-contradiction`; Experiment G PB-2 | `UNDEFINED`/`CONTRADICTORY_INPUT` | `C`/`CONTRADICTION` | `U`/`NO_EVALUATOR` |
| **`X2`** no evaluator | corpus: `Contr` undefined → `Sat_consistency` has none | `U`/`NO_EVALUATOR` | `U`/`NO_EVALUATOR` | `U`/`NO_EVALUATOR` |
| **distinguishes `X1` from `X2`?** | | **YES** | **YES** | **NO** |

> **`MD` cannot tell a contradictory state from a missing evaluator.** It reports the same value and
> the same reason for both. `M3` and `M4` distinguish them at the **value** level.

**Exhaustively:** over the full 8-case parameterization `MD` conflates pairs that the others separate —
`(chS,CHS)`, `(chS,CHs)`, `(chS,ChS)`, `(chS,Chs)` among them — and there is **no pair in the other
direction.** `MD`'s kernel is strictly coarser. This is not a tie broken by preference.

---

# 4. `M3 ≅ M4` — a theorem, not eight coincidences

`C4` found `M3` and `M4` unseparated on 8 cases. **That is weak evidence and was not left there.**
`C9` tests whether the relabeling

```
φ :  UNDEFINED ↦ C ,  CONTRADICTORY_INPUT ↦ CONTRADICTION ,  identity elsewhere
```

**commutes with both models on every case**: `φ(M3(c)) = M4(c)` for all `c`.

> **Result: 0 commutation failures over 8 / 8 cases; `φ` injective.**
> **`M3` and `M4` are the same model under a renaming of one token.**

**Consequence.** The "three-valued vs four-valued" debate, *as a debate about value assignment*, has no
content. `C` is not an additional value — it is a **different name for the value `M3` already
assigns.** The programme has been holding open a choice between a model and itself.

---

# 5. Then what would a fourth value have to do to be real?

**Composition — not assignment.** A fourth value earns its keep only if **aggregating** it behaves
differently from aggregating "no value at all."

## 5.1 The vacuity guard, which changed the answer

My first version of `C10` swept four composition rules — `ignore`, `strict`, `absorbing`,
`belnap_blind` — and found that **none** separates `M3` from `M4`. **That result was vacuous.** All
four are **token-blind**: they treat an unreadable value by its *unreadability*, never by its
*identity*. Since §4 shows the models differ **only** in the identity of an unreadable token, no
token-blind rule *could* separate them. The test was guaranteed to pass and therefore proved nothing —
exactly what the programme's witness requirement exists to catch.

**Rewritten with a token-sensitive witness rule** — `belnap_designated`, in which `C` is a designated
lattice element that **absorbs** (`C ∧ T = C`, `C ∧ F = C`) while `UNDEFINED` propagates as a gap:

| rule | token-sensitive | separates `M3`/`M4`? |
|---|---|---|
| `ignore` · `strict` · `absorbing` · `belnap_blind` | no | **no** (4/4) |
| **`belnap_designated`** | **yes** | **YES** — witness `(chs, Chs)`: `M3 → U`, `M4 → C` |

> **The test is NON-VACUOUS**: it demonstrably *can* separate. It does not, for every rule that treats
> the token blindly.

## 5.2 The consequence — two open items collapse into one

> `[EXP]` **`M3` and `M4` separate if and only if the composition rule is token-sensitive.**
>
> The corpus supplies **no** token-sensitive rule, and **composition — both the rule and its level
> (requirement / evaluator / value) — is itself `OPEN`** (Experiment G).

**Therefore the fourth-value question is not an independent open item. It is a corollary of the
composition question.** It cannot be answered before composition is, and it needs no separate
experiment once composition is settled. **Five open items were listed for this step; one of them was
not a separate item.**

---

# 6. Why `D-0` reported agreement — a masking artifact, and it is worse than stated

`D-0` recorded that all three models give **identical `Zero` readings on all seven cases**, and
attributed this to the readings testing only for `F` and `U`. **That reason is correct but incomplete,
and the fuller reason is more damaging.**

`C8` isolates the mechanism. `D-0` evaluated 7 cases across **8 requirement classes**, and the other
classes independently contribute `U` (`D-0`'s own *"U buckets: theory ×5/×6"* column). Holding the
contradictory `content` fixed and varying only the number of *other* requirements at `U`:

| other requirements at `U` | bucket | distinct reading profiles | models separable? |
|---|---|---|---|
| **0** | — | **2** | **YES** |
| 1 | theory | 1 | no |
| 1 | **agent** | 1 | no |
| 5 | theory | 1 | no |
| 6 | theory | 1 | no |

> `[NEG]` **The models are separable in isolation. A single unrelated `U` anywhere in the state masks
> the difference completely — regardless of its bucket.**

**The mathematical cause.** `Zero_weak` and `Zero_kleene` are **existential quantifiers over the
state**: `¬∃r. value(r)=F`, and `F if ∃F else U if ∃U else T`. Once any `U` is present the reading is
**absorbing** — no further information can change it. `Zero_reasoned` inherits this through `Zero_weak`.

> **The readings are monotone and saturate.** In a system whose evaluators are mostly undefined —
> and `Sat` has **5 of 8 classes non-executable** — **saturation is the normal case, not the edge
> case.** The contradiction-model choice is unobservable in exactly the regime KnowledgeOS is
> actually in.

This is the **sixth instance of the recurring diagnosis**, and the first found in the *readings* rather
than in a value or a metric: an existential projection over the state was asked to carry information
about *which* requirement failed and *why*.

---

# 7. Anti-rigging

**The bucket assignment is the one place this could have been rigged** — `Zero_reasoned` reads
`bucket(r) == "agent"`, so assigning `CONTRADICTORY_INPUT` to `agent` and `NO_EVALUATOR` to `theory`
would manufacture a separation. Two guards:

1. **The buckets are imported from `kos12/expG.BUCKET`, not declared here.** They are the same table
   every prior `Zero` experiment used.
2. **`C7` sweeps all four assignments anyway:**

| `CONTRADICTORY_INPUT` | `NO_EVALUATOR` | kernels differ? | `Zero_reasoned` separates? |
|---|---|---|---|
| agent | agent | **yes** | yes |
| agent | theory *(corpus)* | **yes** | no |
| theory | agent | **yes** | yes |
| theory | theory | **yes** | no |

> **The kernel-level separation of `MD` holds in 4/4 assignments — it is bucket-independent.**
> **The reading-level separation is bucket-dependent** (it needs `NO_EVALUATOR` in the `agent`
> bucket, which is *not* the corpus assignment).

**These must not be conflated.** The result that survives is the **kernel** one. Any claim that the
existing `Zero_reasoned` reading distinguishes the models would be an artifact of a bucket assignment
the corpus does not make.

---

# 8. An honest negative

**On this case space the boundary object `𝓑` adds no discriminating power.** `C4` computes kernels at
both the value level and the boundary level `(value, reason)`, and **they coincide for all three
models.** The reason is straightforward: `M3` already places the distinction *in the value*
(`UNDEFINED`), so the reason carries nothing extra here.

> **`𝓑`'s value was established in Experiment J** — nine conditions indistinguishable in the evaluation
> codomain that remain distinguishable in `𝓑`. **It is not re-established here, and this experiment
> supplies no new support for it.** `𝓑` remains `[PROP]`.

---

# 9. What this settles, and what it does not

## 9.1 Settled

| | status |
|---|---|
| **There are two contradiction models, not three** (`M3 ≅ M4`) | `[EXP]` — theorem by relabeling, 0/8 failures |
| **`MD` is separated from both, by the pair `(X1, X2)`** | `[EXP]` — exhaustive, bucket-independent |
| **`MD` cannot distinguish contradiction from a missing evaluator** | `[NEG]` |
| **The fourth-value question is a corollary of the composition question** | `[EXP]` — non-vacuous, witnessed |
| **`D-0`'s agreement is a masking artifact; the readings saturate at `U`** | `[NEG]` |

## 9.2 `MD` — a refutation, scoped

> `[NEG]` **`MD` fails to preserve a distinction the corpus itself declares.**

The corpus requires `Unknown ≠ Absent` (`ZI-01`) and `Conflict ≠ Invalidity` (`ZI-09`), and the typed
vocabulary carries `G_value: contradictory` with provenance *"{p,¬p} has no value in `{T,F,U}`"*.
`MD` **renames contradiction as absence** — the same collapse as the 9→`U` defect, relocated to the
routing step.

**Scope of the refutation, stated precisely:** this refutes `MD` **relative to the corpus's declared
distinctions.** It is not a proof that delegation is incoherent in general. If the corpus were to
withdraw `ZI-01`/`ZI-09`, the refutation would lapse. **That is the appropriate strength, and no more.**

## 9.3 NOT settled — and not to be inferred

- **No contradiction model is adopted.** Eliminating `MD` leaves **one** model (`M3 ≅ M4`) — but
  "the only survivor" is not the same as "adopted", and adoption is a decision, not an experiment.
- **`Contr` is still undefined.** This experiment separated *models of what happens when contradiction
  is detected*. It supplies **no definition of the contradiction relation itself.**
- **`Sat_consistency` is still without an evaluator**, and `Sat` remains semantically incoherent.
- **The `Zero` readings are still deficient** — `C6` reproduces `D-0`: a contradictory state under the
  four-valued model still has `Zero_weak = Zero_reasoned = true`. **Nothing here repairs closure.**
- **Composition remains `OPEN`**, and now carries the fourth-value question with it.

---

# 10. Consequences for the queue

**What was commissioned as *"`Contr` + the fourth value — five open items, one decision"* is now
four items, and their shape has changed:**

| item | before | after this experiment |
|---|---|---|
| which of three contradiction models | open, 3-way | **2-way, and `MD` is refuted → effectively 1 survivor** |
| the fourth value | independent open item | **a corollary of composition — not separately answerable** |
| `Contr` itself | undefined | **still undefined — untouched** |
| `Zero` readings | deficient | **still deficient — and now known to *saturate*, which is worse** |
| composition rule + level | open | **open, and now load-bearing for the fourth value** |

## 10.1 Recommended next — and a change of order

> **`[PROP]` The next experiment should be COMPOSITION, not `Contr`.**

**Reasoning.** `Contr` was placed ahead of composition on the assumption that the fourth-value question
was part of `Contr`. §5 shows it is not — it belongs to composition. Meanwhile §6 shows the `Zero`
readings **saturate**, so any `Contr` definition tested today would be **unobservable** in the normal
regime. **Defining `Contr` before repairing the readings would produce a definition no experiment could
evaluate.**

**Two candidate orderings, and the choice is a decision:**

| order | argument |
|---|---|
| **composition → readings → `Contr`** | fixes observability first; `Contr` then testable. **Recommended.** |
| `Contr` → composition | preserves the agreed queue; risks defining `Contr` blind |

**This lane recommends; it does not reorder the queue.**

---

# 11. Self-corrections in this experiment

1. **`M3` encoded as returning `U`.** The authoritative `evalc.py` returns `UNDEFINED`. Caught because
   `C1` then contradicted `D-0`. All figures are post-correction.
2. **`C10` was vacuous on first write** — four token-blind rules cannot separate a token-relabeling, so
   the pass was guaranteed. Rewritten with a token-sensitive witness rule that demonstrably *does*
   separate.

Both were caught by the programme's own guards — the first by preferring an established result over a
new one, the second by the witness requirement. **Neither was caught by the tests passing.**

---

# 12. Standing constraints, honoured

**Not done, deliberately:** no contradiction model adopted · `Contr` not invented as a fixture ·
`Sat_c` not implemented · no randomized layer (this experiment is **fully deterministic and
exhaustive** over its parameterization) · `𝓑` not promoted · Theory v1.2 not amended · kernel untouched
and **NOT SELECTABLE** · `KR-EXTREME-2026-09` not started.
