# G — `Sat_c` / Epistemic Evaluation Semantics · Semantic Repair, Evaluator Completion & Closure Test

**Experiment ID** `KR-SIM-2026-09-02-G` · **Status** `[EXP]` — research only
**Predecessors** `KR-SIM-2026-09-02-D` (Sat_c closure) · `-E` (evaluators) · `-F` (repair phase)

> **ID collision, reported not resolved.** The commissioning document assigns
> `KR-SIM-2026-09-02-E`, which is already taken by the evaluator run. This experiment is recorded
> as **`-G`**. Provenance preserved; nothing renamed.

> Nothing here is canonical KnowledgeOS architecture. The primary object is
> `Eval_c(K_t, r, Γ_t) → EVal_c`; **`Sat_c := value ∘ Eval_c` is a candidate projection, tested and
> not assumed.**

---

# PHASE 0 — Predecessor audit

| | |
|---|---|
| theory version | v1.2 (weakened v1.1) + class-indexed three-valued `Sat` |
| predecessor | `-D` closure · `-E` evaluators · `-F` repair phase |
| binding findings consumed | A-1 evaluator incompleteness · A-2 no factivity hook · PB-2 · PB-3 · PB-4 · PB-5 |
| conflicting definition found | **`Eval_Time` from `-E` conflicts with A4** — see below |

## Provenance action: two evaluators from `-E` are **RETRACTED** `[NEG]`

Rule 1 forbids inferring a definition because the simulator needs one. Re-reading `-E` against
A3/A4:

| retracted | why |
|---|---|
| **`Eval_Gov`** (authority table) | the corpus defines **no** governance artifact, **no** evidence of authority and **no** conflict rule. An authority table is invented governance, which A3 forbids |
| **`Eval_Time`** (interval coverage) | A4 requires distinguishing **valid/world**, **observation** and **transaction/record** time, and explicitly forbids equating timestamps with temporal validity. `-E`'s evaluator equated interval coverage with validity |

Both now return `U / NO_EVALUATOR` and `U / NO_TEMPORAL_SEMANTICS` with `evaluator = OPEN`.
**This is a self-correction: `-E` supplied semantics the corpus does not license.**

---

# PHASE A — Specification repair (no implementation)

Full 15-attribute specification per class: `results/evaluation/eval_spec.json` (flagged
`canonical: false`).

| class | evaluator | status | unresolved |
|---|---|---|---|
| content | candidate | `[PROP]` | totality depends on the A1 model chosen |
| evidence | candidate | `[PROP]` | — |
| provenance | candidate | `[PROP]` | — |
| status | **OPEN** | `[OPEN]` | `⪰` undefined |
| consistency | **OPEN** | `[OPEN]` | `Contr` undefined; 4th value OPEN |
| governance | **OPEN** | `[OPEN]` | no artifact / no authority evidence / no conflict rule |
| temporal | **OPEN** | `[OPEN]` | valid vs observation vs record time not distinguished by the corpus |
| operational | **OPEN** | `[OPEN]` | `δ` undefined (Step 290) |

## A1 — contradiction: three models, **none chosen**

`K ∈ { {p}, {¬p}, {p,¬p}, {} }`

| model | `{p,¬p}` ↦ | total? | preserves | loses | affects Zero |
|---|---|---|---|---|---|
| **three-valued** | **UNDEFINED** | **✘** | the three-valued codomain | totality | `Zero` undefined on contradictory states |
| **four-valued** | `C` | ✔ | contradiction visible | the three-valued codomain **for the whole family** | every reading needs a `C` rule |
| **delegated** | `U / NO_EVALUATOR` | ✔ | three-valuedness *and* honesty | resolution — it defers to a blocked class | contradiction becomes **theory-`U`**, so `Zero_reasoned` closes over it |

> The delegated model is the honest one *and* it has a consequence the previous runs did not surface:
> a contradiction becomes **theory-blocked `U`**, which `Zero_reasoned` treats as closing. **A system
> holding `p` and `¬p` would be declared "nothing more for the agent to do".** `[NEG]`

## A6 — is `EVal = {⊤,⊥,U}` sufficient? **No.** `[EXP]` — refutes **N2**

Distinct evaluation situations collapsing into each projected value:

| value | distinct situations |
|---|---|
| `T` | 1 |
| `F` | 1 |
| `C` | 1 |
| `UNDEFINED` | 1 |
| **`U`** | **9** |

The nine span `[OPEN]`, `[PROP]` **and** `[NEG]` theory statuses and both `OPEN` and `candidate`
evaluators — `DELTA_UNDEFINED`, `NO_EVALUATOR`, `NO_ORDERING`, `NO_TEMPORAL_SEMANTICS`,
`INSUFFICIENT_PROVENANCE`, `UNOBSERVED`, `UNDERDETERMINED`, `NON_TERMINATING`, `CONTRADICTORY_INPUT`.

> **`Sat_c := value ∘ Eval_c` is a lossy projection, and the loss is concentrated exactly at `U`** —
> the one value the theory most needs to resolve. **Rule 3 requires distinguishing epistemic
> insufficiency, theory incompleteness, context absence and world unobservability; the projection
> cannot.** `EVal` must carry at least `(value, reason)`.

---

# PHASE B — Adversarial deterministic test

Seven cases: `B1` factivity · `B2` revision · `B3` provenance removal · `B4` blocked, plus three
separating cases permitted for PB-2/PB-3/PB-5. **No random search.** Per-case, per-class results with
value, reason, evaluator, dependencies, contradiction/provenance/temporal/operational state:
`results/evaluation/eval_results.json` (168 rows).

## B1 — totality (**N1**)

| content model | total? | undefined on |
|---|---|---|
| **three-valued** | **✘** | `B2-revision`, `S1-contradiction` |
| four-valued | ✔ | — |
| delegated | ✔ | — |

> **N1 is falsified for the model the theory currently proposes.** Reported as a specification
> defect, not converted to `U`.

## B2 — factivity / truth separation

`Eval(K,r,Γ)=⊤` establishes only that the evaluated requirement is satisfied under the candidate
semantics. **No component in the family establishes truth** (A-2 carried forward, unchanged).
**`Truth ⟂ Closure` recorded.** No `Truth` evaluator introduced.

## B3 — revision / retraction

The candidate semantics do **not** distinguish contradiction · revision · supersession · retraction ·
expiration · historical persistence. The theory defines no retirement relation. **`[OPEN]`** — no
lifecycle invented.

## B4 — provenance removal

Removing the source component moves `provenance` to `U / INSUFFICIENT_PROVENANCE` (agent bucket) and
`evidence` to `⊥`. This is the **only** case in the suite producing a `⊥`, and it is what makes the
composition question decidable (Phase C).

## B5 — blocked evaluators after the retraction

With `Eval_Gov` and `Eval_Time` retracted, **five** classes are `OPEN`, not three. The `-E` contagion
figures are superseded: they were computed with two invented evaluators in place. **The purpose was
never to make `U` disappear** — and the honest answer is that `U` here is overwhelmingly
**theory-`U`**, not epistemic `U`.

---

# PHASE C — Composition (**N6**)

Three rules tested; **Kleene not assumed**.

| case | Kleene | U-dominant | Bochvar |
|---|---|---|---|
| B1, B2, B4, S1, S2, S3 | `U` | `U` | `U` |
| **B3-no-source** | **`F`** | **`U`** | **`U`** |

> **The suite discriminates.** Kleene lets a definite `⊥` survive an undetermined sibling; the
> U-dominant rules mask it. That is a substantive semantic choice — *does an established violation
> survive alongside an unevaluable requirement?* — and it is **not forced**. `[OPEN]`

**Composition level** — requirement / evaluator / value — **not decided.** The experiment shows only
that the choice is consequential at the value level; whether composition belongs there is untested.

---

# PHASE D — Zero analysis

Run under **all three** contradiction models (see D-0 — they agree, for a bad reason). Values below
are the delegated model; the other two differ only in `content`'s value on contradictory input, which
no reading can see:

| case | `Zero_strict` | `Zero_reasoned` | `Zero_weak` | `Zero_kleene` | `U` buckets |
|---|---|---|---|---|---|
| B1 factivity | false | **true** | true | `U` | theory ×5 |
| B2 revision | false | true | true | `U` | theory ×6 |
| **B3 no-source** | false | **false** | **false** | **`F`** | agent ×2, theory ×5 |
| B4 blocked | false | true | true | `U` | theory ×5 |
| S1 contradiction | false | **true** | true | `U` | theory ×6 |
| S2 δ defined | false | true | true | `U` | theory ×4 |
| S3 κ operational | false | true | true | `U` | theory ×5 |

## D-0 — **the four Zero readings are not total over the value domain** `[NEG]` NEW

Prompted by the commissioning note's warning that treating the delegated model as default would be
premature theory selection, Phase D was re-run under **all three** contradiction models. They give
**identical Zero readings on all seven cases** — and the reason is a defect, not agreement.

| model | `content` on `S1-contradiction` | does `weak` block? | does `reasoned` block? | does `kleene` see it? |
|---|---|---|---|---|
| three-valued | `UNDEFINED / CONTRADICTORY_INPUT` | **no** | **no** | **no** |
| four-valued | **`C` / CONTRADICTION** | **no** | **no** | **no** |
| delegated | `U / NO_EVALUATOR` | no | no | yes |

All four readings test **only** for `F` and `U`:

```
Zero_weak     := ¬∃r. value(r) = F
Zero_reasoned := Zero_weak ∧ ¬∃r. (value(r) = U ∧ bucket(r) = agent)
Zero_kleene   := F if ∃F else U if ∃U else T
```

> **Any value outside `{T,F,U}` is invisible to every reading, and every reading CLOSES on it.**
> `S1` holds both `p` and `¬p`, and under all three models `Zero_weak = Zero_reasoned = true`:
> **a contradictory state is declared epistemically closed.**

Two consequences:

1. **My Phase D result was not contaminated by the model choice** — but only because the readings
   cannot see the difference, which is worse than if they could.
2. **N2 demands extending the codomain; extending it breaks all four Zero readings.** Any fourth
   value must come with a corresponding extension of every `Zero` definition, or closure silently
   swallows it.

> ### CORRECTED — the two questions ARE separable
> This section originally concluded *"the codomain question and the `Zero` question are not
> separable."* **That is wrong.** Defining closure over the **boundary** `𝓑` rather than over
> `value` repairs D-0 in **6 of 6** contradiction-bearing cases **under all three contradiction
> models** — the boundary records the conflict regardless of which value the model assigns.
> See [`I-zero-lens-and-gita-candidates.md`](I-zero-lens-and-gita-candidates.md) §2. The
> contradiction-model choice and the closure question are **independent** under that formulation.

## D-1 — `Zero_strict` unreachable in all seven `[EXP]`

Unchanged and now better explained: five classes are `OPEN`, so `⊤` is unavailable for them by
construction.

## D-2 — **the `reasoned` / `weak` separation was an artifact** `[NEG]` — SELF-CORRECTION

`Zero_reasoned` and `Zero_weak` **agree on all seven suite cases**. The 8/80-vs-36/80 separation
reported in `-E` came from the **invented** governance and temporal evaluators, which manufactured
*agent-remediable* `U`s. Once those evaluators are retracted, every remaining `U` is theory-blocked,
and `reasoned` closes wherever `weak` does.

**N9 is still falsified — but only by a constructed case**, not by the suite:

```
content = U(UNOBSERVED)   [agent]     evidence = ⊤   provenance = ⊤   no ⊥ anywhere
  →  Zero_weak = true      Zero_reasoned = false
```

> `Zero_reasoned` remains **formally distinct** from `Zero_weak`, and **empirically indistinguishable
> on every honest case in the suite.** The distinction needs agent-remediable `U`s to bite, and the
> honest evaluators produce almost none — because almost every `U` is the theory's fault.

## D1 — closure vs truth (**N8**)

Four distinct claims, tested separately:

| claim | result |
|---|---|
| `Zero ⇒ Truth` | **REFUTED** — B1 closes under `weak` **and** `reasoned` while the attribution is false |
| `Zero ⇒ Knowledge` | **REFUTED** — same case |
| `Zero ⇒ Decision-readiness` | **NOT TESTED** — no decision requirement was evaluated |
| `Zero ⇒ No remaining epistemic work` | **REFUTED for `weak`** (B1 leaves five OPEN classes); **holds vacuously for `reasoned`**, since it closes only over non-agent `U` |

**B1 counterexample retained.**

---

# NEGATIVE TESTS — the required falsification attempts

| # | claim | result |
|---|---|---|
| **N1** | `Eval_c` is total | **FALSIFIED** for the three-valued model (undefined on B2, S1) |
| **N2** | three values are sufficient | **FALSIFIED** — 9 distinct situations collapse into `U` |
| **N3** | every `U` has a valid reason | **HELD on this suite** — 0 unreasoned `U`s; **not** proof of exhaustiveness |
| **N4** | the eight classes are mutually independent | **FALSIFIED** — `content → consistency`, `operational → δ, eval_κ` |
| **N5** | the eight classes are exhaustive | **NOT TESTED / OPEN** — no enumeration procedure exists |
| **N6** | Kleene conjunction is appropriate | **FALSIFIED AS FORCED** — B3 discriminates the three rules |
| **N7** | operational evaluation is well-founded | **FALSIFIED** — `κ = operational` recursed to an arbitrary bound; the bound is not a semantic result |
| **N8** | `Zero` implies truth | **FALSIFIED** — B1 |
| **N9** | `Zero_reasoned` is another spelling of `Zero_weak` | **FALSIFIED, but only by a constructed case** — they agree on all seven suite cases |
| **N10** | supplying evaluators makes `Sat` canonical | **FALSIFIED** — A3/A4 returned `OPEN`; two prior evaluators retracted |

**Eight falsified, one held-not-proved, one untestable.** A failed negative test is a result.

---

# FINDINGS REGISTER

| finding | status |
|---|---|
| `Eval_c` is the primary object; `Sat_c` is a projection of it | `[MODEL]` |
| `value ∘ Eval_c` is lossy, concentrated at `U` (9→1) | `[EXP]` |
| Three-valued content evaluator is not total | `[NEG]` |
| Contradiction has three candidate models, none chosen | `[OPEN]` |
| Under the delegated model a contradiction becomes theory-`U` and `Zero_reasoned` closes over it | `[NEG]` |
| `Eval_Gov` and `Eval_Time` from `-E` are corpus-unsupported | `[NEG]` retraction |
| Governance semantics | `[OPEN]` |
| Temporal semantics (valid/observation/record) | `[OPEN]` |
| `δ` and operational well-foundedness | `[OPEN]` |
| Composition rule discriminated by B3; Kleene not forced | `[OPEN]` |
| Composition level (requirement/evaluator/value) | `[OPEN]` |
| `Truth ⟂ Closure` | `[EXP]` established |
| `Zero_reasoned ≠ Zero_weak` formally | `[PROP]` |
| `Zero_reasoned ≡ Zero_weak` on every honest suite case | `[NEG]` |
| Chain `strict ⇒ reasoned ⇒ weak` | `[DEF]` derived in `-F` (1 620 000 assignments, 0 counterexamples) |
| Factivity | `[OPEN]` |
| Class exhaustiveness · reason exhaustiveness | `[OPEN]` |

---

# FINAL VERDICT

## WHAT IS ESTABLISHED

* `value ∘ Eval_c` is **lossy**: nine distinct evaluation situations — spanning three theory statuses
  and two evaluator states — collapse into the single value `U`.
* The three-valued content evaluator is **not total**.
* The eight classes are **not mutually independent**.
* Operational evaluation is **not well-founded** when `κ = operational` is not excluded.
* The composition rule is **not forced**: B3 discriminates Kleene from the U-dominant rules.
* **`Truth ⟂ Closure`** — B1 closes under `weak` and `reasoned` while the attribution is false.
* The chain `strict ⇒ reasoned ⇒ weak` is **derived** (from `-F`).

## WHAT IS REFUTED

N1 (for the proposed model), N2, N4, N6, N7, N8, N9 (constructively), N10 — and, by self-correction,
**the `-E` claim that `Zero_reasoned` separates from `Zero_weak` in practice.**

## WHAT REMAINS OPEN

`⪰` · `Contr` and the fourth value · governance semantics · temporal semantics (three time notions)
· `δ` · retirement/lifecycle semantics · composition rule · composition level · class exhaustiveness
· reason exhaustiveness · factivity · the normative meaning of `Zero`.

## WHAT IS ONLY PROPOSED

`Eval_c` as the primary object · the `EVal = (value, reason, …)` structure · the three contradiction
models · `Zero_reasoned` · the agent/theory/world reason partition.

## SAT STATUS

> **SEMANTICALLY INCOHERENT** — under the model the theory currently proposes.

Not merely "partially executable": the three-valued content evaluator is **not a function** on
contradictory states (N1), and the projection defining `Sat` **destroys** the distinction Rule 3
requires (N2). Both defects are in the specification, not the implementation. The `delegated` and
`four-valued` models restore totality — but neither is chosen, and each has a cost.

## ZERO STATUS

| reading | status |
|---|---|
| `Zero_strict` | **unreachable** in all seven cases — five classes are `OPEN` |
| `Zero_reasoned` | formally distinct `[PROP]`; **empirically indistinguishable from `weak`** on every honest case `[NEG]` |
| `Zero_weak` | fires on 6 of 7 — including a case with a false attribution |
| `Zero_kleene` | `U` on 6 of 7, `F` on B3 — the only reading that reports the blockage as blockage |

## FACTIVITY STATUS

> **OPEN.**

The family has no truth-bearing relation (A-2). This establishes `Eval=⊤ ⇏ Truth` under the candidate
semantics; it does **not** establish that KnowledgeOS should be non-factive. No `Truth` evaluator was
introduced.

## KERNEL STATUS

> **NOT TESTED.**

No kernel-entry criterion was approached, and none could be while `Sat` is semantically incoherent.

## NEXT EXPERIMENT

> **The `Contr` / fourth-value experiment — with the `Zero` readings extended in the same step.**

It is the smallest step that unblocks the most: `Contr` is required by `consistency`; the `delegated`
contradiction repair routes `content` through it; the fourth-value question is its codomain; and
**D-0 shows that extending the codomain without extending the four `Zero` readings makes closure
silently swallow the new value.** Five OPEN items are one decision.

**Note on `R_b`.** The commissioning note is right that adopting `Sat_content := Sat_consistency` on
contradictory input would be **premature theory selection**, since `Sat_consistency` is itself
undefined. This experiment therefore used the delegated model only as a *reporting* default and has
now re-run Phase D under all three — D-0 records the outcome. **No contradiction model is adopted.**

**Stop condition reached and reported:** multiple semantic models (three-valued / four-valued /
delegated; Kleene / U-dominant / Bochvar) remain supported, and the deterministic suite discriminates
the composition rules but **not** the contradiction models. That is the experiment to design next —
a case that separates the three contradiction models. **Do not guess between them.**
