# KR-CONTR-EVAL-2026-09
# Contradiction, Evaluation Domain and Zero
### Scope: **structured evaluation / adequacy** — *what evaluation structure is minimally required?*

**Protocol authored by the KnowledgeOS research programme** (2 184 lines,
`…/20260902-155900_prompt-minimum-machinery-to-represent-contradiction.md`).
**Executor:** this lane. **Protocol authorship is not Claude's.**
**Baseline** Theory v1.2 **UNCHANGED** · **No Theory v1.3** · `FR-001` frozen, not reopened ·
**Factivity is an independent track and nothing here depends on it.**
**Code** `research/knowledgeos-sim/kos12/contr2.py` · **Runner** `run_contr2.py` ·
**Results** `results/contr2/*.json` (11).

> ### ✅ EXPERIMENT-ID COLLISION — RESOLVED, not merely documented
> The commissioning protocol issued this experiment as `KR-CONTR-2026-09`. That ID had already been
> used the same day by Experiment G's narrower commission. **The bare ID is now RETIRED as a live
> name** — see [`EXPERIMENT-ID-REGISTRY.md`](EXPERIMENT-ID-REGISTRY.md).
>
> ```
> KR-CONTR-MODELS-2026-09   scope: contradiction-model separation
>                           alias: KR-CONTR-2026-09 (legacy)
>                           artifact: V-contr-experiment.md
>
> KR-CONTR-EVAL-2026-09     scope: structured evaluation / adequacy   ← THIS ARTIFACT
>                           issued in the protocol as KR-CONTR-2026-09
> ```
>
> **Provenance graph — recorded in BOTH artifacts so it cannot become ambiguous:**
>
> ```
> KR-CONTR-MODELS-2026-09          predecessor / model-separation
>          │                       commissioned by Experiment G
>          ↓
> KR-CONTR-EVAL-2026-09            structured-evaluation experiment   ← THIS
>          │                       commissioned by the programme protocol
>          ↓
> KR-COMP-2026-09                  composition                        (not run)
> ```
>
> **Both experiments retain their original provenance; neither history is rewritten.** The two are
> complementary, not competing, and agree where they overlap (§14, NEG-7).
>
> **⚠️ A conflict inside the review is preserved rather than resolved silently:** its §9 proposed
> `KR-CONTR-EVAL-2026-09` *"if your naming convention permits a scope suffix"*; its §19 proposed
> keeping the bare `KR-CONTR-2026-09`. **The convention does permit a suffix, and §9 itself notes that
> a suffix *eliminates* the collision where keeping the bare ID merely documents it.** The suffixed
> form is therefore used. **Flagged here so the choice is visible and reversible.**

---

## 1. Executive Summary

> ## **Experimental Gate `C` — Structured Evaluation Required; protocol kernel designation `K2`.**
> ## **NO KNOWLEDGEOS KERNEL IS SELECTED.**
>
> `K2` is the **commissioning protocol's** designation (its §35 scale `K0`–`K4`). **It is not a
> KnowledgeOS kernel, not a kernel version, and not a selection.** The kernel remains
> **NOT SELECTABLE**; Theory v1.2 is unchanged; nothing is adopted.

### The headline

> **The obstruction is STRUCTURAL, not CARDINAL.**
>
> The nine "no determinate value" conditions cannot be adequately represented by **any** flat value
> domain, **regardless of cardinality**. Adding values — a fourth, a fifth, any number — does not
> address a deficiency that is not about how many values there are.

**And the reason A, B and C fail is not about contradiction at all.** All three fail on the *same*
distinctions, and those failures collapse the **unknown family**. **A value added for contradiction
cannot repair missing distinctions that lie outside the contradiction dimension.**

| result | classification |
|---|---|
| Candidates **A** (fourth value), **B** (delegated relation) and **C** (exclusive construction) each separate **7 of 12** required distinctions | `[EXP]` |
| Candidate **D** (structured) separates **12 of 12** | `[EXP]` |
| **`χ = 3` on the protocol's required set** — a fourth value is **not forced**. ⚠️ **This does NOT establish that the correct evaluation domain has three values** (§3.1) | `[EXP]` |
| **…yet no flat domain of ANY cardinality indexed by evaluation outcome is adequate** | `[INF]` — theorem, §9 |
| **Minimum structure = a PAIR.** `reason` is the single indispensable field; **two** minimum sets, both size 2 | `[EXP]` |
| **`C` destroys every distinction WITHIN contradiction** — 10 subtypes → 2 classes; all second-order combinations → one value | `[NEG]` NEG-1 |
| **Candidate C reports a contradiction as `T` (satisfied)** | `[NEG]` |
| **The protocol's invariants `I1`–`I5` have a gap** — C satisfies all five while doing the above | `[NEG]` + `[PROP]` `I11` |
| **Contradiction does NOT imply Zero — it implies CLOSURE** under A and B | `[NEG]` NEG-7 |
| contradiction is **derivable** from the candidate power set — a semantic state, not a kernel operator | `[EXP]` |

---

## 2. Baseline and Scope

**Frozen/current and untouched:** Theory v1.2 · `FR-001` · the Zero-Lens results · the factivity
decision track. **The simulated agent never has access to world truth**; the evaluator does. The
separation `World ≠ Observation ≠ Evidence ≠ Interpretation ≠ EpistemicState ≠ Evaluation ≠
KnowledgeAttribution` is preserved by construction: `State` carries only agent-side commitments.

**Not done:** no theory edit · no v1.3 · `Contr`, `C`, eight `Sat` classes and `Zero=C` **not added to
canonical theory** · no architecture · no kernel promotion.

---

## 3. Formal Problem

The problem is one of **injectivity**, not of logic design.

```
    R : 𝒮 ⟶ D          𝒮 = semantic conditions,  D = representation domain

    R is ADEQUATE  ⟺  ∀ (x,y) ∈ ℛ_req .  x ≠ y  ⟹  R(x) ≠ R(y)
```

**Consequence, and it makes §9 of the protocol exactly computable.** Assigning values to conditions
*is* a graph colouring: conditions joined by a required distinction need different values. Therefore

> ### The minimum flat domain size = **the chromatic number of the required-distinction graph.**

`ℛ_req` is taken **verbatim** from the protocol's §9 table plus invariant candidates `I1`–`I5` —
12 required pairs over 11 conditions. **It is not extended by this lane**, and §11 measures what
happens when it is.

## 3.1 ⚠️ How `χ = 3` must and must not be read

> **`χ = 3` establishes ONLY:** *given this particular required-distinction set and this adequacy
> criterion, at least three distinguishable flat classes are required.*
>
> **It does NOT establish** that the correct evaluation domain has three values · that three values
> suffice semantically · or that any flat domain is adequate at all. **§9 shows none is.**

`χ` is a **lower bound on a flat encoding under one declared required-set** — and §11 shows that set
is a modelling choice which moves the number from 3 to 21.

---

## 4. Semantic Classes Tested

**21 conditions**, each carrying its protocol provenance. `Contr(p,K) ⟺ Pos(p,K) ∧ Neg(p,K)` is the
*starting* definition; §15 records what survives.

| family | conditions |
|---|---|
| determinate | `Satisfied` · `Unsatisfied` |
| **no-determinate-value** | `Unknown` · `Absent` · `NotAssessed` · `NotApplicable` · `Unobservable` · `Uninterpreted` · `InsufficientEvidence` · `Underdetermined` · `TheoryIncomplete` |
| **contradiction** | `DirectContradiction` · `SourceConflict` · `ObservationConflict` · `EvidenceConflict` · `UnequalConfidence` · `ContextDifference` · `TemporalDifference` · `ModelConflict` · `Retraction` · `Supersession` |

A commitment carries `(prop, polarity, source, context, time, confidence, model, layer, status)`.
`TheoryIncomplete` is modelled by a state flag `evaluator_exists = False`, **not** by keying on the
condition's name — keying on the name would let the representation read the answer.

---

## 5. Candidate Representations

| | domain | rule |
|---|---|---|
| **A** fourth value | `{T,F,U,C}` | `Pos ∧ Neg → C`; **`U` — never `T`/`F` — whenever the evaluation genuinely cannot run** |
| **B** delegated relation | `{T,F,U} × 𝔹` | `Sat` stays three-valued; `Contr` is an independent relation **outside** `Sat` |
| **C** exclusive construction | `{T,F,U}` | contradiction structurally prevented; one side dropped, **higher confidence wins, ties to positive** |
| **D** structured | tuple | `(value, polarity, reason, provenance, evidence, context, time, model, status, confidence)` — the tuple is **discovered** in §9, not assumed |

**Fairness.** A is the *strongest* available mapping, per protocol §5 — an earlier version of it
returned `T` for uninterpreted and theory-incomplete states, which was **this lane's defect, not a
property of A**, and was corrected before any figure below was taken (§22).

---

## 6. Deterministic Witness Suite

All 15 protocol cases plus `EvidenceConflict` and `TheoryIncomplete`. **Fully deterministic — the
separation results use no randomness at all.** Witnesses are shown, per §10, never a bare pass/fail.

| candidate | required pairs separated | adequate |
|---|---|---|
| A fourth value | **7 / 12** | **no** |
| B delegated relation | **7 / 12** | **no** |
| C exclusive | **7 / 12** | **no** |
| **D structured** | **12 / 12** | **yes** |

**The identical collapses across A, B and C** — witnesses:

```
Unknown  ≡  Absent  ≡  NotAssessed  ≡  NotApplicable  ≡  Unobservable
        ≡  InsufficientEvidence  ≡  Underdetermined  ≡  TheoryIncomplete      →  U
```

**All three fail on exactly the same five pairs, and none of the five involves contradiction.** They
fail on the *unknown* family. **Adding a fourth value does not help, because the problem was never in
the contradiction slot.**

---

## 7. Separation Matrix

Over all 21 conditions (210 pairs):

| candidate | distinct values used | collapsed pairs |
|---|---|---|
| A | 4 | most of the no-determinate-value family |
| B | 4 | as A, contradiction subtypes conflated |
| C | 3 | as A **plus contradiction folded onto `T`/`F`** |
| **D** | **21** | **none** |

---

## 8. Zero Interaction

**Tested, not assumed.** For all eight contradiction-family conditions:

| | result |
|---|---|
| `Contradiction ⇒ ¬Zero` | **FALSE** |
| `Zero_weak` on a contradictory state, under **A** and **B** | **closes** (`true`) |
| `Zero_kleene` over contradictory states | uniformly **`T`** |

> `[NEG]` **NEG-7. Contradiction does not merely fail to imply Zero — under both A and B a
> contradictory state CLOSES.** Under A the value `C` is outside `{T,F,U}` and every reading is blind
> to it; under B `Sat` reports `U` and the `Contr` flag is not read by any reading.

**This reproduces `D-0` in a new setting and confirms it is not an artifact of one model.** It is also
the point of contact with `KR-CONTR-MODELS-2026-09`, which found the same readings **saturate**.

> `[EXP]` **`Contr ≠ Zero`** — invariant `I7` holds, but for an uncomfortable reason: they are
> different objects *because* contradiction produces closure rather than a gap. **Zero remains a lens
> and must not become an evaluation value** — nothing here supports promoting it.

---

## 9. Minimality Analysis

**Single-field ablation on D**, then an **exhaustive search over all `2¹⁰` subsets**:

| | result |
|---|---|
| fields necessary on single ablation | **`reason`** — and only `reason` |
| **minimum adequate size** | **2** |
| number of minimum sets | **2 — not unique** |
| the sets | **`{value, reason}`** and **`{polarity, reason}`** |

> `[EXP]` **SOFTENED, per review.** *Not* "`reason` is the sole indispensable field" — that wording
> is unsafe, because `reason` could in principle encode arbitrarily much, and in the limit
> `Reason(x) = x` makes it a **disguised complete state identifier** whose indispensability would be
> vacuous. The safe statement is:
>
> ### **Within the tested representation language and candidate fields, an explicit reason/boundary component is indispensable for separating the required distinctions.**
>
> `value` and `polarity` are interchangeable as its partner. **§9.1 tests the objection rather than
> waving it away.**

## 9.1 Is `reason` a genuine field, or a disguised state identifier?

**Measured, three guards:**

| guard | result |
|---|---|
| **identity encoding?** | **NO** — 21 conditions, **10** distinct reason values |
| **does it collapse?** | **YES** — **66** pairs collapsed; a genuine categorical field |
| **adequate ALONE?** | **NO — 11/12.** It fails exactly one required pair |

> **The pair really is a pair.** `reason` alone fails on **`Satisfied` vs `Unsatisfied`** — both carry
> `reason = None`. So the division of labour is clean and explains *why* the minimum has two
> components:
>
> ```
> reason           carries the ENTIRE boundary family (9 conditions)
> value|polarity   carries ONLY the determinate T/F split
> ```
>
> Neither is doing the other's work, and neither is a disguised encoding of the whole state.

## 9.2 The follow-on question — the minimal structured semantics of `reason`

**Not "add nine categories."** The TODO register already rejected that. The question the review poses
is what structure `reason` minimally has. **Tested hypothesis** — that it factors into two orthogonal
sub-dimensions drawn from the corpus bucket vocabulary:

```
reason  ≟  (locus, modality)        locus    = WHERE the deficiency lies
                                    modality = WHAT KIND it is
```

| | result |
|---|---|
| flat reason values → factored | **10 → 8** |
| **lossless?** | **NO** — 3 distinctions lost: `Unknown`/`NotAssessed`, `Unknown`/`Uninterpreted`, `NotAssessed`/`Uninterpreted` (all → `agent` / `not-yet-done`) |
| **`(value, locus, modality)` adequate?** | **YES — 12/12** |

> `[PROP]` **The two-factor structure is ADEQUATE for the current required set but NOT LOSSLESS with
> respect to the flat enumeration.**
>
> **And that gap is the interesting part.** The flat nine-category list carries three distinctions
> **the required set does not demand.** Whether they matter is — again — a **`ℛ_req` decision**, not an
> experimental question. §11 said the required set sets everything; here it does so again, from the
> other direction.
>
> **The factor names are candidates carrying no authority. Nothing is adopted.**

**And the theorem that makes this more than an observation.** Under the strongest Candidate A, **all
nine** no-determinate-value conditions evaluate to the single outcome `U`:

> `[INF]` **If the evaluation outcome is identical across a family, then no function of the outcome
> alone separates any pair in it. Hence no flat domain — of ANY cardinality — indexed by evaluation
> outcome is adequate.**

**The obstruction is not the number of values.** Of the ten candidate fields, exactly one — **`reason`**
— separates all nine on its own.

**Caveat, per protocol §17:** this is experimental minimality **for the tested distinction set only**.
It is not a claim of universal necessity.

---

## 10. Statistical Stress Testing

**Robustness only. It proves nothing the deterministic witnesses have not already settled.**

`trials = 20 000` · `seed = 20260902` · generator: uniform over the field domains · cases independent ·
contradictory fraction **0.2037**.

| candidate | misclassification rate | 95 % CI |
|---|---|---|
| A | **0.0** | [0, 0] |
| B | **0.0** | [0, 0] |
| **C** | **0.2037** | [0.198, 0.209] |
| D | **0.0** | [0, 0] |

**C misses exactly the contradictory fraction** — it structurally cannot hold both polarities, so it
detects contradiction never. **This is expected and is not the finding**; §6 is.

---

## 11. Required-Set Sensitivity — the boundary this experiment discovered

**`χ` is not a property of contradiction. It is a function of which distinctions the programme
declares required** — and the protocol calls its §9 table a *minimum*.

| required set | edges | **χ** | D adequate |
|---|---|---|---|
| **S1** protocol minimum (§9 + `I1`–`I5`) | 12 | **3** | yes |
| S2 + the unknown family mutually distinct | 48 | **9** | yes |
| S3 + the contradiction family mutually distinct | 76 | **9** | yes |
| **S4** all 21 conditions mutually distinct | 210 | **21** | yes |

> `[EXP]` **The minimum evaluation domain ranges from 3 to 21 across defensible required-sets.
> A structured representation is adequate across the ENTIRE range; no flat domain is adequate at the
> top of it.**
>
> **This is the boundary. The question "how many values does contradiction need?" is underdetermined —
> not because the mathematics is hard, but because the required-distinction set is a MODELLING
> DECISION the programme has not yet made.** Choosing a value count before choosing the required set
> is choosing an answer before the question.

---

## 12. DDD Analysis

| question | answer | classification |
|---|---|---|
| ubiquitous-language meaning | *the representation simultaneously holds incompatible commitments concerning `p`* | `[PROP]` — survives §15 with a qualification |
| property or entity? | **a property of a set of commitments** — never of a proposition alone | `[EXP]` |
| between what? | **between commitments**, which may sit at different **layers** (observation / evidence / interpretation / claim / model) | `[EXP]` |
| what owns it? | **no new aggregate.** It is a *relation over commitments already owned* | `[PROP]` |
| temporal? contextual? | **both matter, and neither is decisive alone** — §15 | `[EXP]` |
| resolvable? | detection ≠ resolution ≠ truth determination — `I8`, `I9` hold | `[EXP]` |
| does resolving remove it historically? | **no** — `Retraction` and `DirectContradiction` are distinguishable under D | `[EXP]` `I10` |

> **`EvidenceConflict ≠ ClaimContradiction ≠ ModelConflict`** — protocol §31/§32. Under D these are
> three distinct representations, separated by the `evidence` (layer) and `model` fields. **Under A, B
> and C they are one value.** `[EXP]` — NEG-5 confirmed.

---

## 13. Kernel Analysis

**Derivability against the candidate power set** `Observe · Interpret · Represent · Relate ·
Discriminate · Hypothesize · DetectGap · Challenge · Validate · Revise · Determine · Select · Qualify`:

| step | powers | argument |
|---|---|---|
| detect `Pos ∧ Neg` | `Represent` `Relate` `Discriminate` | both commitments are already represented; noticing they are related by negation and distinct is Relate + Discriminate |
| classify the subtype | `Discriminate` `Qualify` | subtype is a function of fields already present (§9) |
| decide it must be resolved | `Challenge` `Determine` | a governance act, not a representational one |
| resolve it | `Revise` `Select` | supersession / retraction — existing machinery |

> ### Protocol kernel designation — **`K2`** (the protocol's §35 scale): existing capability sufficient, but a new semantic representation is required.
> ### **This is NOT a KnowledgeOS kernel selection. No kernel is selected. The kernel remains NOT SELECTABLE.**
>
> **No step required a power outside the candidate set, so contradiction is a SEMANTIC STATE, not a
> kernel operator.** But `K1` would be wrong: §9 shows the current representation *cannot carry* the
> distinctions the capability needs. **Importance is not irreducibility, and `K3` is not warranted.**
>
> **Caveat:** derivability is relative to the **tested** operator set, which is not final (protocol §22).

---

## 14. Negative Results

| | result |
|---|---|
| **NEG-1** | **CONFIRMED.** `C` cannot distinguish contradiction subtypes: 10 family members → **2** classes; **all** second-order combinations (`Contr` + insufficient / not-assessed / unobservable / underdetermined / not-applicable) → **one** value. `C` separates contradiction from non-contradiction **while destroying every distinction within it** |
| **NEG-2** | **NOT confirmed** — structured evaluation preserved all 12 required distinctions, and all 210 pairs |
| **NEG-3** | **PARTIAL.** Context matters (`ContextDifference` is separable only via the `context` field) but is **not** decisive alone |
| **NEG-4** | **PARTIAL.** Same for time |
| **NEG-5** | **CONFIRMED.** `SourceConflict`, `EvidenceConflict` and `ModelConflict` are distinct from `ClaimContradiction` under D; identical under A/B/C |
| **NEG-6** | **CONFIRMED for A and C.** Contradiction cannot be reduced to `Sat` — but **B shows it can live outside `Sat` without fixing anything**, because B fails on the *unknown* family |
| **NEG-7** | **CONFIRMED and stronger than stated.** Zero cannot be determined from contradiction alone — worse, **contradiction produces closure** |
| **NEG-8** | **CONFIRMED for A/B/C**, which lose provenance and temporal distinctions entirely. **Not** for D's minimum pair — but `{value, reason}` retains them only because `reason` encodes them categorically, **not** because provenance survives as a field |

### An additional negative result the protocol did not anticipate

> `[NEG]` **Candidate C maps `DirectContradiction` to `T` — indistinguishable from `Satisfied`.**
>
> **And it satisfies invariants `I1`–`I5` while doing so.** All five compare contradiction against the
> *unknown* family; none forbids confusing it with *satisfied*. **The invariant set as specified is
> insufficient**, and a candidate can pass every one of them while committing the most severe error
> available. Caught only by the §19 capability guard.

> `[PROP]` **`I11` — `Contr(p,K) ≢ Satisfied(p,K)`.** A **test-protocol proposal**, legitimate but
> **`[PROP]` until independently ratified.** **A: pass · B: pass · C: FAIL · D: pass.**
>
> **The invariant suite is NOT retroactively declared complete**, and the `I1`–`I5`-pass /
> capability-guard-fail conflict stands unresolved in §16 by design.

---

## 15. Boundary Conditions — what survives of the starting definition

`Contr(p,K) ⟺ Pos(p,K) ∧ Neg(p,K)` tested against each perturbation:

| perturbation | verdict |
|---|---|
| **provenance** differs (S1 vs S2) | survives — still contradiction; provenance changes the **subtype**, not the fact `[EXP]` |
| **confidence** differs (.95 / .10) | **survives.** Contradiction is **not reducible to confidence imbalance** — protocol §30 `[EXP]` |
| **retraction** | **does NOT survive** — `p` retracted, `¬p` active is **not** current contradiction; distinguishable from it under D `[EXP]` |
| **supersession** | **does NOT survive** — temporal supersession is not contradiction `[EXP]` |
| **context** differs | **survives as a distinct subtype**; whether `p@C₁ ∧ ¬p@C₂` is *contradiction* is a **modelling decision**, not a fact the data settles `[OPEN]` |
| **time** differs | as context `[OPEN]` |
| **model** differs | **`ModelConflict` is a distinct concept**, separable under D `[EXP]` |

> **`Pos ∧ Neg` is necessary but not sufficient.** It over-generates: it classifies retraction and
> supersession as contradiction. **The definition requires a currency/frame qualifier**, minimally
> `status = active`. **Whether it further requires a common context and a common time is OPEN** and is
> a decision, not an experiment.

---

## 16. Invariants Discovered

| | A | B | C | D |
|---|---|---|---|---|
| `I1` `Contr ≠ Unknown` | ✓ | ✓ | ✓* | ✓ |
| `I2` `Contr ≠ Absent` | ✓ | ✓ | ✓* | ✓ |
| `I3` `Contr ≠ NotAssessed` | ✓ | ✓ | ✓* | ✓ |
| `I4` `Contr ≠ InsufficientEvidence` | ✓ | ✓ | ✓* | ✓ |
| `I5` `Contr ≠ Unobservable` | ✓ | ✓ | ✓* | ✓ |
| **`I11` `Contr ≠ Satisfied`** `[PROP]` | ✓ | ✓ | **✗** | ✓ |

**\* C satisfies `I1`–`I5` vacuously — by mapping contradiction to `T`.** *(Conflict between the raw
invariant test and the capability guard is **preserved and identified**, per protocol §37, not
silently resolved.)*

`I7` `Contr ≠ Zero` — **holds** (§8) · `I8` detect ≠ resolve — **holds**, disjoint power sets ·
`I9` resolve ≠ determine truth — **holds** · `I10` historical ≠ current — **holds** under D.

---

## 17. Candidate Formal Definitions

```
[PROP]   Contr(p, K, φ)  ⟺  ∃ c₁,c₂ ∈ K .  active(c₁) ∧ active(c₂)
                                          ∧ prop(c₁)=prop(c₂)=p
                                          ∧ polarity(c₁) ≠ polarity(c₂)
                                          ∧ frame(c₁) ≈_φ frame(c₂)

         φ  = the frame qualifier: which of {context, time, model, layer} must AGREE
              for two opposed commitments to count as contradictory.
         [OPEN] — φ is a MODELLING DECISION, not an experimental result.

[PROP]   EVal_min = (value | polarity,  reason)            -- §9, two minimum sets
[PROP]   subtype(Contr) = f(provenance, layer, context, time, model)   -- §12
```

---

## 18. What Remains Open

`φ` — the frame qualifier (**a decision**) · whether `ℛ_req` is the protocol minimum or something
larger (**a decision, and §11 shows it sets everything**) · `Contr`'s composition with other
requirements · the `reason` vocabulary's exhaustiveness · whether `𝓑` is the right home for `reason`
(Experiment J supports it; **this experiment adds no support**) · `⪰` · `δ` · `≡_sem`.

---

## 19. Theory Impact

> **NONE. Theory v1.2 is unchanged. No v1.3.**

`Contr`, `C`, the eight `Sat` classes and `Zero = C` are **not** added to canonical theory.
`Zero` remains a **lens** and is not promoted to an evaluation value.

---

## 20. Kernel Impact

> **Protocol designation `K2`** — a scale point in the commissioning protocol's §35, **not** a
> KnowledgeOS kernel and **not** a version. No new irreducible capability was found. **The kernel
> remains NOT SELECTABLE and nothing is selected.** Consistent with **`C-1`**: no mechanism enters the
> kernel merely because KnowledgeOS can use it.

---

## 20.1 The conclusion this experiment earns — stated at the safe strength

> **The experiment does not support representing contradiction as a distinguished scalar evaluation
> value. The tested adequacy requirement is satisfied by a structured representation in which at least
> one reason-like dimension is indispensable. The precise evaluation semantics, including the role of
> contradiction, remains OPEN.**

**The emerging candidate shape** — `[PROP]`, and stated as a shape rather than a definition:

```
            Evaluation  =  Status  +  Typed Reason / Boundary
```

**not** *"KnowledgeOS = four-valued logic."* The safer position is also the stronger one:
**KnowledgeOS has no evidence that it needs four truth values; it has evidence that a flat evaluation
value alone is insufficient for the tested boundary distinctions.**

**Deliberately NOT claimed: *"structured evaluation is the answer."*** **`D` is the surviving tested
candidate, not the final theory.** It was the only one of four to meet the criterion; that makes every
alternative *tested* inadequate, not `D` correct.

Likewise `EVal = (v, r)` is **`[EXP]`/`[PROP]`** — the minimum found *in the tested search space under
the chosen adequacy criterion*. **It is not a KnowledgeOS primitive and must not become one by
citation.**

## 20.2 The architectural clarification — `Contr` is not at the root

**The review's most consequential structural point, and it reorganises the whole queue:**

```
        Required Distinctions          ← the DECISION that sets everything (§11)
                 ↓
        Evaluation Representation      ← this experiment
                 ↓
        Typed Boundary / Reason        ← §9.1–9.2
                 ↓
            Composition                ← KR-COMP, next
                 ↓
            Aggregation
                 ↓
               Zero
                 ↓
           Determination

        Contradiction  →  ONE particular required distinction
                          NOT the root of the chain
```

> **`Contr` was being treated as a foundational item. It is not one.** It is a single entry in the
> required-distinction set — and §6 already showed this empirically: **A/B/C fail on the *unknown*
> family, not on contradiction.** The contradiction question was never the blocker; the evaluation
> representation was.

**And this explains, retrospectively, why `Zero` resisted definition.** The old
`Zero(K,I,EC) ⟺ Δ = ∅` was underspecified because `Sat` was. When `Sat(r) = U` one must ask
**"`U` because *what*?"** — and `U_unobservable` is epistemically different from `U_not-assessed`.

> `[EXP]` **`Zero` cannot be defined before evaluation semantics are expressive enough to answer
> "`U` because what?"** The dependency is **`Sat → Zero`**, never the reverse. **Confirmed from two
> directions:** §8 here, and `KR-CONTR-MODELS` §6, where the readings were shown to **saturate**.

## 20.3 What is established about `Contr` itself

> ### **`Contr` remains `OPEN`. What this experiment established is the SHAPE OF THE PROBLEM around it.**

```
                        CONTRADICTION
                             │
                 ┌───────────┴───────────┐
                 │                       │
          model assignment        evaluation structure
                 │                       │
           M3 ≅ M4                  reason required
           MD refuted *             scalar insufficient
                 │                       │
                 └───────────┬───────────┘
                             │
                        COMPOSITION
                             │
                           OPEN
```

`*` **`MD` is refuted only relative to the corpus distinctions tested** (`ZI-01`, `ZI-09`).
`M3 ≅ M4` holds **only at the tested assignment level** — `KR-CONTR-MODELS-2026-09` §5 shows they
separate under a token-sensitive composition rule, which is precisely what `KR-COMP` must settle.

**Four things that previously risked being conflated are now distinct:** model assignment · evaluation
structure · composition · and `Contr` itself. **Only the first two have results.**

---

## 21. Decision Recommendation

> ### Gate: **`C` — STRUCTURED EVALUATION REQUIRED**

**Not `A`** (three values insufficient in practice) · **not `B`** (a fourth value is neither forced by
the required set nor sufficient at larger ones) · **not `D`** as gate-D (more values is the wrong
axis — §9's theorem) · **not `E`** (candidate B put contradiction outside `Sat` and still failed) ·
**not `F`** — though §11 shows one *sub*-question is underdetermined, and that is stated rather than
hidden inside the gate.

**Recommended, in order:**

1. **Adopt nothing yet.** `[PROP]` The minimum pair `(value|reason)` is a recommendation.
2. **Decide `ℛ_req` first.** §11 shows every downstream number depends on it. **This is the decision
   that unblocks the rest, and it is not an experiment.**
3. **Then decide `φ`**, the frame qualifier.
4. **Add `I11`** to the invariant set — the gap is demonstrated, not hypothetical.
5. **Repair the readings before defining `Contr`** — §8 and `KR-CONTR-MODELS` agree that a
   contradictory state currently closes.

**The lane recommends. It does not adopt, and it does not adjudicate its own work.**

## 21.1 Next commission — `KR-COMP-2026-09` · **COMMISSIONED, NOT RUN**

**Primary question:**

> **What composition semantics, if any, preserves the distinctions required by the structured
> evaluation result WITHOUT introducing distinctions the corpus does not support?**

**Both halves are binding.** A composition rule that preserves everything by inventing structure fails
the second half as surely as one that collapses distinctions fails the first.

**Minimum test dimensions:**

| # | dimension |
|---|---|
| 1 | composition over `value` |
| 2 | composition over `reason` |
| 3 | composition over **`(value, reason)`** |
| 4 | whether **`M3`/`M4` remain isomorphic** under composition |
| 5 | whether contradiction can be preserved **without becoming a truth value** |
| 6 | whether `U` remains **one** epistemic category or becomes **reason-parameterized** |
| 7 | interaction with **Zero** |
| 8 | contradiction **+ missing evaluator** |
| 9 | contradiction **+ insufficient evidence** |
| 10 | contradiction **+ theory incompleteness** |

### Preparatory commission — Priest extraction, **before** `KR-COMP` runs

**Not** *"what Priest says about four values."* The commission is:

> Extract Priest's formal treatment of **semantic values, designated values, truth-function
> composition, consequence, negation, conjunction and implication**, and the **relationship between a
> semantic value and a logical operation**. Then identify exactly which KnowledgeOS distinctions
> **could or could not** be represented by a **flat value system** versus a structured
> **`(value, reason)`** system.
>
> **`[EXT]` — do not import any logical system as a KnowledgeOS decision.**

Source already in the corpus:
`…/mathematical_ideas_that_can_be_implemented/20260902-160601_priest-non-classical-logic-tools-for-contr-zero-identity.md`.

> ### ⚠️ **Do NOT begin by selecting the composition algebra.**
> **First enumerate candidate composition semantics, then construct separating witnesses.** Selecting
> an algebra first would repeat the error `KR-CONTR-MODELS` §5 caught in this lane's own work — a
> sweep of four token-blind rules that could not possibly have separated what it was asked to.

---

## 22. Reproducibility

```bash
cd research/knowledgeos-sim && python run_contr2.py
```

Deterministic except §10, which records `seed = 20260902`, `trials = 20 000`, generator and CI.
**11 result files** in `results/contr2/`.

### Self-corrections made during execution

1. **`R_A` returned `T` for uninterpreted and theory-incomplete states** — this lane's defect, unfair
   to Candidate A. Strengthened to return `U` whenever the evaluation cannot run; `TheoryIncomplete`
   re-modelled via an `evaluator_exists` flag rather than by keying on the condition's name. **All
   figures are post-correction.**
2. **The §10 detector matched the literal `"C"` inside context labels `C1`/`C2`**, making Candidate D
   appear to misfire on 47 % of states. A defect in the **test**, not in D. Replaced with explicit
   per-candidate detection predicates; D's rate is **0.0**.
3. **ID collision** with the earlier `KR-CONTR-2026-09` — reported in the header and re-labelled,
   **not silently overwritten**.

---

## 23. Classification Register

| statement | class |
|---|---|
| A/B/C separate 7/12; D separates 12/12 | `[EXP]` |
| minimum flat domain on the protocol set is `χ = 3` | `[EXP]` |
| no flat domain indexed by evaluation outcome is adequate at any cardinality | `[INF]` |
| minimum structure is a pair; `reason` indispensable; two minimum sets | `[EXP]` |
| `χ` ranges 3–21 over defensible required-sets | `[EXP]` |
| `C` destroys within-contradiction distinctions | `[NEG]` |
| `C` maps contradiction to `T`; `I1`–`I5` have a gap | `[NEG]` |
| `I11 Contr ≠ Satisfied` | `[PROP]` |
| contradiction produces closure | `[NEG]` |
| contradiction is a semantic state, derivable from candidate powers | `[EXP]` |
| `Contr(p,K,φ)` with a frame qualifier | `[PROP]` |
| `EVal = (value\|polarity, reason)` as the minimum representation | `[EXP]`/`[PROP]` — **the minimum in the TESTED search space under the chosen adequacy criterion; NOT a primitive** |
| `I11` proposed as a test-protocol addition | `[PROP]` — **awaiting independent ratification; not adopted into the invariant suite** |
| `M3 ≅ M4` | `[EXP]` — **at the tested ASSIGNMENT level only**; they separate under a token-sensitive composition rule |
| `Contr` itself | **`[OPEN]`** — this experiment established the *shape of the problem*, not `Contr` |
| `φ` and `ℛ_req` | `[OPEN]` — decisions |
| Belnap / paraconsistent / Kleene / Priest / bilattice | `[EXT]` — referenced as shapes, **no semantics imported** |

---

## Final Supervisory Summary

| Question | Result | Class |
|---|---|---|
| Can contradiction be represented? | **Yes — structurally, not by a value** | `[EXP]` |
| Separated from uncertainty? | **Yes**, by all four candidates | `[EXP]` |
| Separated from absence? | **Only by D** | `[EXP]` |
| Separated from insufficient evidence? | **Only by D** | `[EXP]` |
| Separated from theory incompleteness? | **Only by D** | `[EXP]` |
| **Is a fourth value necessary?** | **No — and it would not be sufficient. Wrong axis** | `[EXP]` |
| **Is structured evaluation necessary?** | **Yes.** Minimum: a **pair** containing `reason` | `[EXP]` |
| Is Zero sufficient to express contradiction? | **No — contradiction CLOSES** | `[NEG]` |
| State, relation or event? | **A relation over commitments**; a property of a set, not of `p` | `[EXP]` |
| Does context matter? | Yes, for subtype; **not decisive alone** | `[EXP]` |
| Does time matter? | Yes; **supersession is not contradiction** | `[EXP]` |
| Does provenance matter? | Yes, for subtype; **not for the fact** | `[EXP]` |
| Does contradiction imply an epistemic gap? | **No** | `[NEG]` |
| Does it imply a kernel capability? | **No.** Protocol designation `K2`; **no KnowledgeOS kernel selected** | `[EXP]` |
| **Theory v1.2 changed?** | **NO** | — |
