# `P-09` — Kernel Stability & Preconditions Derivation

**2026-09-07 · stability analysis only.** Baseline: [`18`](./18-P08-MINIMUM-PERSISTENCE-OBLIGATIONS-DERIVATION.md) · [`17`](./17-P07-IDENTITY-ADDRESSING-CONTINUITY-DERIVATION.md) · [`16`](./16-P06-PERSISTENCE-IDENTITY-DERIVATION.md).

> **The governing rule of this phase:** *do not make `P-09` prove that `P-08` was correct; make it
> capable of proving `P-08` insufficient.* ⭐ **It did.** §9 and §4 below.

# 1. Scope and prohibitions

⛔ **Schema v2 unmodified · v3 not begun · proposal register, 3MC, `dimension-registry.md`, MD-017,
MD-018 untouched · `F-4`/`F-5` unrepaired · `Q3`/`Q4` unresolved · no A/B/C/D · no fifth architecture ·
no UUID / surrogate key / natural key / event sourcing / append-only / temporal tables / graph / relational
selection · no ORM, API, table, column, PK, FK, repository · no aggregate boundaries · DDD is not
ontology · theory is never inferred from implementation existence · no excluded-lane evidence ·
`P-07` and `P-08` are NOT rewritten.**

⭐ **Qualifications discovered here are recorded AS qualifications.** `P-07` and `P-08` stand verbatim
as historical derivations. `[EMP]` · `[DERIVED]` · `[ARCH]` · `[OPEN]`.

---

# 2. `P-08` dependency reconstruction

## Chain A — the monotonicity chain

```
MD-018 monotonicity                      ⭐ [OPEN]  ← §4: NOT EVIDENCED. P-08 assumed it.
        ↓
prior monotone states are redundant      [DERIVED], sound BUT conditional on the premise above
        ↓
K3 applies only to non-monotone transitions   [DERIVED] — SCOPE conditional
        ↓
P-08 kernel = 5                          [DERIVED] — count survives; K3's REACH does not
```

## Chain B — the corpus-state chain

```
corpus-state identification              ⭐ [OPEN]  ← §5: no identifier existed at measurement time
        ↓
measurement reproducibility              [DERIVED] — fails without it
        ↓
RE-MEASURED regime                       [DERIVED] — regime survives, its guarantee does not
        ↓
K5                                       [DERIVED] — forced, and currently UNSATISFIED (W6 live)
```

## Chain C — the extraction-record chain

```
extraction-record persistence            [OPEN] → §6 resolves it: NON-CIRCULAR
        ↓
definition identity derivation           [DERIVED], with a SCOPE qualification (§6.3)
        ↓
P-07 identity kernel                     [DERIVED]
        ↓
P-08 K1                                  [DERIVED] — survives on independent grounds
```

## Chain D — the warrant chain

```
warrant withdrawal                       ⭐ [EMP]  ← §7: witnessed in this lane's own conduct
        ↓
warrant becomes a historical assertion   [DERIVED]
        ↓
possible K4 recursion                    [DERIVED] — terminates (§7.4)
        ↓
possible kernel enlargement              🔴 [DERIVED] — NO. K4's SCOPE enlarges; no capability is added
```

## Chain E — the result-as-cited chain

```
result-as-cited lifecycle                [DERIVED] → §8: DUAL MODALITY
        ↓
RE-MEASURED vs UPDATED distinction       [DERIVED] — holds, but the partition attaches to CLAIMS not ITEMS
        ↓
historical observation obligations       [DERIVED] — K2 + K4, no new capability
```

## ⭐ Chain F — the chain `P-08` did not draw

```
𝒯_KOS descriptive, not closed            [EMP], P-08 §1 said so
        ↓
⭐ representation-level evolution         ⭐⭐ [EMP] — CORPUS-ATTESTED and OMITTED (§9)
        ↓
kernel is a lower bound over an INCOMPLETE transition set
        ↓
⭐⭐ "lower bound" is not established     [DERIVED] — this is what makes the kernel NOT YET STABLE
```

$$\boxed{\textbf{Six chains. Two resolve } (\mathbf{C}, \mathbf{D})\textbf{. One refines } (\mathbf{E})\textbf{. Three remain open } (\mathbf{A}, \mathbf{B}, \mathbf{F})\textbf{ — and } \mathbf{F} \textbf{ is decisive.}}$$

---

# 3. `O-P08-1` — status regression

⛔ **The answer is NOT inferred from the absence of an observed regression.** The corpus was searched
for **permission**, **prohibition**, and **direction language**.

## 3.1 What the corpus actually says — measured, with a positive control

| finding | location | `[?]` |
|---|---|:--:|
| the chain is **ordered**: `candidate → supported → corroborated → formally_defined → operationally_defined → canonical` | `model-boundary-decisions.md:895ff` · `machine-record-schema.md:164ff` · `dimension-registry.md:11` | `[EMP]` |
| ⭐ **"No entry may skip a stage."** — stated **three times**, in all three homes | `machine-record-schema.md:173` · `dimension-registry.md:15` · `model-boundary-decisions.md:929` | `[EMP]` |
| *"nothing yet in the registry has left `candidate`"* · *"every entry is `candidate`"* | `model-boundary-decisions.md:929` · `dimension-registry.md:1514` | `[EMP]` |
| ⭐⭐ **direction language: ZERO.** No *monotone*, *ordered-only*, *forward*, *backward*, *irreversible*, *demote*, *downgrade*, *regress*, *revert* in any sentence about `status_chain` | search over the whole `brainstorming/` tree, `*.md` + `*.yaml` | ⭐ `[EMP]` |
| **positive control** — `grep 'skip a stage'` returns **3 hits**, so the pattern class can match | — | `[EMP]` |
| ⭐ **MD-018 explicitly declares itself DISTINCT from MD-015's maturity ladder** | `model-boundary-decisions.md` §4: *"Explicit status chain, **distinct from** MD-015's five-stage maturity ladder"* | ⭐ `[EMP]` |
| ⭐⭐ **MD-015's ladder IS monotone by construction** — *"names the **HIGHEST** stage reached across all files so far — this is **cumulative** corpus state"* | `machine-record-schema.md` MD-015 addendum | ⭐ `[EMP]` |
| a **parallel** five-value scale in the corpus terminates in **`FALSIFIED`**, called *"a genuine parallel to this reconstruction's own status_chain discipline (MD-018)"* | `per-file/0155.yaml:54-56` | `[EMP]` |

## 3.2 ⭐⭐ The finding

$$\boxed{\begin{array}{c}\textbf{"No entry may skip a stage" constrains STEP SIZE, not DIRECTION.}\\ \textbf{A no-skip rule is satisfied by } \textit{corroborated} \rightarrow \textit{supported} \textbf{ just as well as by } \textit{supported} \rightarrow \textit{corroborated}.\\[6pt] \boxed{\textbf{Ordered } \not\Rightarrow \textbf{ monotone. } \mathbf{MD\text{-}018 \Rightarrow monotone \textbf{ is } OPEN, \textbf{ not evidenced.}}}\end{array}}$$

⭐⭐⭐ **And the diagnosis is precise: monotonicity IS evidenced in the corpus — for MD-015's maturity
ladder, which is a cumulative maximum. `P-08` imported the property of the artifact MD-018 explicitly
distinguishes itself from.** ⚠️ **Recorded as a qualification of `P-08` §17; `P-08` is not rewritten.**

## 3.3 The six notions, kept apart

| # | notion | is it a status regression? | witnessed | `[?]` |
|---|---|---|---|:--:|
| 1 | **status regression** | — | ⭐ **not witnessed, and not prohibited** | `[OPEN]` |
| 2 | **refutation** | 🔴 **no — an EXIT, not a rung.** `FALSIFIED` is a terminal value in the parallel scale, not a lower rung | ✅ `Zero` → `[RF]`; `0155.yaml` | `[EMP]` |
| 3 | **supersession** | 🔴 **no — two objects and a relation** | ✅ this lane's withdrawals | `[EMP]` |
| 4 | **withdrawal** | 🔴 **no — a claim is retracted, its subject's status is untouched** | ✅ `INV-9` | `[EMP]` |
| 5 | **correction** | ⚠️ **it can LOOK like one** — a status wrongly recorded and then fixed is not a transition of the thing at all | ✅ `F-4` pending | `[DERIVED]` |
| 6 | **reclassification** | 🔴 **no — a change of category, not of rung** | ✅ `K` `D-03`/`D-05` | `[EMP]` |

⭐ **`[DERIVED]` Five of six are NOT regression — which is exactly why the absence of an observed
regression proves nothing.** Every backward-*looking* event in the estate is one of 2–6.

## 3.4 ⭐ New corpus evidence that pressures the premise

`[EMP]` `brainstorming/phase_measure_theory/20260826-004302_important-correction-to-the-model.md` §9
enumerates the estate's own representation history:

> *"At `t₁`, we considered four dimensions relevant. At `t₂`, we discovered three additional dimensions.
> At `t₃`, one dimension was split into two. **At `t₄`, a previous determination was revised.**"*

⭐ **`t₄` is corpus-attested REVISION OF A DETERMINATION.** ⚠️ **Disciplined reading:** a *determination*
is not a *status*, so this does **not** establish status regression — but it removes any basis for
assuming the estate's transitions are one-directional in general.

## 3.5 Re-testing the `P-08` redundancy proof

| | verdict |
|---|---|
| **is the proof valid?** | ✅ **yes — the implication is sound** |
| **is its premise evidenced?** | 🔴 **NO** (§3.2) |
| **is the conclusion therefore available?** | 🔴 **no — it is CONDITIONAL** |
| **if regression IS permitted, which claims fail?** | ⭐ `P-08` §17's redundancy proof · `P-08` §7's *"monotone ⇒ no obligation"* row for `unresolved → resolved` · `P-08` §19's negative result about the commissioned witness — ⭐⭐ **that witness would become VALID as originally stated** |
| **which transition classes become `K3`-bearing?** | ⭐ **the entire status chain**, for candidates, definitions and associations — `K3` would go from *exceptional* to *universal* |
| **recalculated lower bound?** | ⭐ **still 5.** `K3`'s **reach** changes; **no capability is added or removed** |

⛔ **`O-P08-1` remains unresolved, so the `P-08` kernel is preserved as CONDITIONAL and no larger
kernel is invented.**

---

# 4. `O-P08-2` — corpus-state identification

⛔ **No mechanism is chosen.** Not git, not a hash, not a timestamp, not a snapshot.

## 4.1 Six notions kept apart

| notion | what it is |
|---|---|
| **corpus state** | the configuration of the corpus at an instant — **a fact about the world** |
| **state identifier** | a designator that **distinguishes** one such configuration from another |
| **measurement timestamp** | ⭐ **when the measuring happened — NOT what was measured.** The corpus can change without the clock changing anything about the claim, and vice versa |
| **provenance** | who measured, under what pattern and scope |
| **reproducibility mechanism** | how the state is made available again |
| **physical snapshot mechanism** | ⛔ **implementation — out of scope** |

⭐ **`[DERIVED]` A timestamp is NOT a state identifier.** `τ7`'s 56 renames happened between commits: two
measurements minutes apart, two different corpora, and **no clock reading distinguishes what was
measured** — only when.

## 4.2 The required properties, derived

| property | required? | why | `[?]` |
|---|:--:|---|:--:|
| **distinguishability** | ✅ **yes** | two states that yield different results must get different identifiers, else the results contradict each other for no recorded reason | `[DERIVED]` |
| **stability** | ✅ **yes** | the identifier of a past state must not change afterwards | `[DERIVED]` |
| **temporal ordering** | ⚠️ **NOT required** | ⭐ **surprising and derived:** to *distinguish* two observations, ordering is unnecessary; ordering is needed only to say which is *later*, which is provenance's job | ⭐ `[DERIVED]` |
| **reproducibility** | ⚠️ **conditional** | ⭐ **an identifier can distinguish without restoring.** A claim can be *attributable* to a state that is no longer reachable — and then it is **historical**, not re-derivable | ⭐ `[DERIVED]` |
| **scope binding** | ✅ **yes** | a zero measures *a scope and a pattern*, never a theory | `[EMP]` |
| **sensitivity to rename** | ⭐ **CLAIM-RELATIVE** | ⭐⭐ a **site** claim is invalidated by `τ7`; a **count** claim is not | ⭐ `[DERIVED]` |
| **sensitivity to content change** | ✅ **yes, always** | every claim class is content-sensitive | `[DERIVED]` |
| **sensitivity to addition/removal** | ✅ **yes** | a count claim is directly invalidated | `[EMP]` — `MD-020`: *"corpus grew during the pass"* |
| **independence from mutable external references** | ✅ **yes** | ⭐ otherwise the identifier inherits the very mutability it exists to pin down | `[DERIVED]` |

## 4.3 ⭐⭐ The minimum semantic requirement

$$\boxed{\begin{array}{c}\textbf{A corpus-state identifier must be a STABLE, CONTENT-DERIVED DESIGNATOR, bound to a declared scope,}\\ \textbf{whose sensitivity is AT LEAST that of the claim class it grounds, and which depends on nothing mutable.}\\[6pt] \boxed{\textbf{Ordering and restorability are NOT part of the minimum. Distinguishability is.}}\end{array}}$$

⭐ **The claim-relative clause is the non-obvious result:** one identifier scheme need not serve all
claim classes, and a site-claim demands strictly more sensitivity than a count-claim.

## 4.4 Is `W6` live?

| | |
|---|---|
| **does any mechanism satisfy the requirement?** | ⚠️ **in principle yes; in fact no** |
| ⭐ **why not** | ⭐⭐ **`τ7`'s 56 renames were UNCOMMITTED. At measurement time no stable content-derived designator existed for the state measured** |
| **verdict** | ⭐⭐ **`W6` IS LIVE.** Not hypothetical, not a future risk — a present, unremediated gap in this lane's own measurements |

⛔ **`K5` is therefore FORCED and currently UNSATISFIED.** That is a fact about the estate, not a
licence to choose a mechanism.

---

# 5. `O-P08-4` — must the extraction record persist? (anti-circularity)

## 5.1 The seven notions kept apart

| notion | example | modality |
|---|---|---|
| **corpus fact** | `Σ` occurs at 143 symbolic sites | **measured** |
| **extraction observation** | *"I found 7 readings of `ℐ`"* | **measured** |
| **extraction classification** | ⭐ *"reading R5 is `[DR]`, R6 is `[CG]`"* | ⭐ **adjudicated** |
| **candidate identity** | *"`𝒦 ≠ K`"* | ⭐ **adjudicated** |
| **adjudication** | the act of deciding either | **adjudicated** |
| **theory claim** | *"`Σ ≅ 𝒫({Support,Refute})`"* | **adjudicated** |
| **provenance of the extraction** | pattern, scope, corpus state | **measured** |

## 5.2 ⭐ Breaking the circle

**The circular form to avoid:** *we must persist the extraction record → because the definition-identity
result needs it → because we decided the record must persist.*

⭐⭐ **The circle breaks on an independent ground:** the record's **adjudicative** content consists of
**decisions**, and a decision is **not re-derivable by any procedure** — that is `P-08` §6's criterion,
established **before** and **independently of** any question about extraction records.

| if the extraction record is lost, what becomes unrecoverable? | `[?]` |
|---|:--:|
| corpus facts | 🔴 **nothing — re-measurable** (given §4's identifier) | `[DERIVED]` |
| extraction observations | 🔴 **nothing — re-measurable** | `[DERIVED]` |
| ⭐ **extraction classifications** | ⭐ **LOST — 8 records × their dispositions are decisions** | `[DERIVED]` |
| ⭐ **candidate identity** (`𝒦 ≠ K`, `Q3`'s open state) | ⭐⭐ **LOST — and re-deriving means re-deciding** | `[DERIVED]` |
| theory claims | **LOST as claims**, though re-assertable | `[DERIVED]` |
| provenance of the extraction | ⭐ **LOST — and NOT re-derivable**, because the corpus has since changed | ⭐ `[DERIVED]` |

$$\boxed{\begin{array}{c}\textbf{The extraction record is NOT one thing. Its MEASURED content need not persist; its ADJUDICATIVE content must —}\\ \textbf{and must for the reason ALL adjudications must, not because } P\text{-}07 \textbf{ needs it.} \quad \boxed{\textbf{NON-CIRCULAR.}}\end{array}}$$

## 5.3 ⭐ The exact qualification of `P-07` — recorded, not repaired

`P-07` §6 concluded: *"definition identity is forced UNCONDITIONALLY."* Its sole witness is a
re-disposition **performed by this lane**.

$$\boxed{\begin{array}{c}\textbf{What is established: } \textit{any estate that ADJUDICATES definition→candidate associations requires definition identity.}\\ \textbf{What is NOT established: } \textit{KnowledgeOS theory requires it independently of being adjudicated about.}\end{array}}$$

⭐ `[DERIVED]` **The conclusion survives — the antecedent is satisfied, because KnowledgeOS *is* being
adjudicated about, and `Q3`/`Q4` are open precisely as adjudications.** ⚠️ **But its modality is
narrower than `P-07` stated: conditional on adjudication, not unconditional.** ⛔ **`P-07` stands as
written.**

**Verdict on `O-P08-4`:** extraction-record persistence is **logically required for its adjudicative
content**, and **required only for auditability** for its measured content — ⛔ **and it introduces no
new obligation**, since `K1`/`K3` already carry it.

---

# 6. `O-P08-6` — can a warrant be withdrawn?

## 6.1 The seven-level distinction

`claim` → `ground` → `warrant` → `warrant about a claim` → `warrant about a transition` →
`withdrawal of a warrant` → `withdrawal of the underlying claim`. ⭐ **The last two are different
events with different consequences**, and the corpus witnesses the difference.

## 6.2 ⭐⭐ The witness — this lane's own conduct

| withdrawn claim | ⭐ **what was actually defective** | `[?]` |
|---|---|:--:|
| *"`(Ω,𝓕,P)` occurs 0 times"* | ⭐⭐ **the WARRANT** — the probe covered **564 of 3136+ files** and used **glyph-literal patterns over a LaTeX corpus**. The *claim* was refuted **by refuting its ground** | ⭐ `[EMP]` |
| *"`Θ` has never been raised"* — wrong **3 times** | ⭐⭐ **the WARRANT each time** — each search was inadequate, and the third failure surfaced `Θ = world states, U = utility, C = constraints, R = risk model` verbatim | ⭐ `[EMP]` |
| `INV-9` — *"3 `.py` files"* | ⭐ **the WARRANT** — the scope was wrong; measurement gave 77 + 68 files, 7384 LOC | ⭐ `[EMP]` |
| `TG-02` — *"missing independence relation"* | ⭐ **the WARRANT, and the claim INVERTED** — the relation exists as a six-component vector | ⭐ `[EMP]` |

$$\boxed{\begin{array}{c}\textbf{Every withdrawal in this lane was a WITHDRAWAL OF A WARRANT, not of a subject-matter claim.}\\ \boxed{\textbf{Warrants are withdrawable. } \mathbf{[EMP]}\textbf{, four independent witnesses.}}\end{array}}$$

## 6.3 What a warrant therefore is

| candidate reading | verdict |
|---|---|
| **immutable ground** | 🔴 **refuted** — all four witnesses |
| **mutable state** | 🔴 **refuted** — it is not *updated*; it is *shown wrong* |
| ⭐ **assertion** | ⭐ ✅ **YES.** A warrant is a **claim about a ground**, and it behaves exactly as `P-08` §5.2 says assertions behave — **superseded, never deleted** |
| **evidence** | ⚠️ **it CITES evidence; it is not evidence** |
| **relationship** | ⚠️ **it sits on a link** without being one |
| **unresolved** | 🔴 no — the evidence decides |

## 6.4 ⭐ Consequence — a scope correction, not a new capability

`[DERIVED]` If warrants are assertions, `K4` **already** governs them. ⛔ **No `K6`.**

⚠️ **But `P-08` under-stated `K4`'s scope**: it wrote *"assertions"* meaning the estate's
subject-matter claims. ⭐ **`K4` must be read as covering warrants too** — which means `K3`'s warrants
are themselves `K4`-bearing.

## 6.5 ⭐⭐ Does the recursion terminate?

**The worry:** withdrawing a warrant needs a warrant, which is an assertion, which can be withdrawn…

$$\boxed{\begin{array}{c}\textbf{It TERMINATES. All four witnesses were withdrawn on the ground of a RE-MEASUREMENT —}\\ \textbf{a wider scope, a concept-level pattern, a corrected file count.}\\[6pt] \boxed{\textbf{The regress ends in the MEASURED modality, which is reconstructible rather than adjudicated.}}\end{array}}$$

⭐ `[DERIVED]` **The adjudicated/measured partition is what makes the recursion well-founded** — an
independent argument for it, arrived at from a direction `P-08` never considered.

---

# 7. `O-P08-5` — result-as-cited lifecycle

| case | verdict | `[?]` |
|---|---|:--:|
| **same measurement, corrected** | ⭐ **SUPERSESSION of an assertion** — the earlier number was *wrong*, and `K4` applies. **Not an update** | ⭐ `[DERIVED]` |
| **new measurement, same corpus state** | ⚠️ **if the results differ, one is wrong** ⇒ reduces to the case above. If they agree, it is **corroboration**, adding no object | ⭐ `[DERIVED]` |
| **new measurement, changed corpus state** | ⭐ **A DISTINCT OBSERVATION.** No continuity, no supersession — **two facts about two corpora** | ⭐ `[DERIVED]` |
| **same result, improved citation** | **provenance correction** — `τ6`, non-monotone ⇒ `K3` | `[DERIVED]` |
| **superseded result** | `K4` | `[DERIVED]` |
| **withdrawn result** | ⭐ `K4` — **and witnessed four times in §6.2** | `[EMP]` |

## ⭐⭐ The answer to the key question

$$\boxed{\begin{array}{c}\textbf{A result-as-cited has NO continuity identity } \textit{qua observation} \textbf{ — each observation of a different corpus state is a distinct fact.}\\ \textbf{It DOES bear supersession } \textit{qua assertion} \textbf{ — because citing it is a claim, and claims can be wrong.}\\[6pt] \boxed{\textbf{DUAL MODALITY. And which one applies is decided by ONE FACT: whether the corpus state differs.}}\end{array}}$$

⭐ **This makes `O-P08-2` strictly prior to `O-P08-5`** — without a corpus-state identifier the estate
**cannot tell a correction from a new observation.** `[DERIVED]` **`W6` being live therefore blocks
`O-P08-5` from being operationally answerable**, even though it is semantically answered here.

⚠️ **Statistical sanity check only:** *repeated observation* vs *changed state* is exactly this
distinction; ⛔ **no statistical ontology is imported.**

---

# 8. `O-P08-3` — transition-set sufficiency

**The question, as commissioned:** *is there evidence of an ALREADY-EXISTING transition class whose
omission could invalidate the current kernel?*

## ⭐⭐⭐ Yes. And the corpus states it in its own voice.

`[EMP]` `brainstorming/phase_measure_theory/20260826-004302_important-correction-to-the-model.md` §9:

> *"If this model survives further research, KnowledgeOS cannot simply preserve **facts**. It may need to
> preserve the **history of how the state representation itself evolved**."*
>
> *"At `t₁`, we considered four dimensions relevant. At `t₂`, we discovered three additional dimensions.
> **At `t₃`, one dimension was split into two.** At `t₄`, a previous determination was revised."*
>
> *"But I would still **not declare this a Kernel requirement yet.**"*

| | finding | `[?]` |
|---|---|:--:|
| **`τ11` — representation-level evolution** | ⭐⭐ **a transition class `P-08` omitted entirely.** All ten members of `𝒯_KOS` are **item-level**: a thing's field changes. `τ11` changes **which things there are** | ⭐ `[EMP]` |
| **its four observed forms** | **addition** (`t₂`) · ⭐ **SPLIT** (`t₃`) · **revision of a determination** (`t₄`) · and by symmetry **merge** — which is exactly what resolving `Q3` would do to `ℐ`/`𝓘` | `[EMP]` for the first three, `[DERIVED]` for merge |
| **is it observed in this lane too?** | ✅ **yes — `𝒦`'s distinction from `K` IS a split**, which `P-08` mis-modelled as `τ2` (association change) | ⭐ `[EMP]` |
| **does its omission threaten the kernel?** | ⭐⭐ **YES.** `W1` — *two candidates collapse into one identity* — **is precisely an unrecorded merge.** `K1` says *preserve identity*; a merge **retires** one. **`K1` does not cover the case that motivated it** | ⭐ `[DERIVED]` |
| **the missing obligation** | ⭐ **a retired identity must remain RESOLVABLE to its successor**, or every citation to it dangles | `[DERIVED]` |
| **does the kernel change?** | ⚠️ **it would require either a SPLIT of `K1` or a sixth capability** | ⭐ `[OPEN]` |
| ⛔ **is it promoted here?** | 🔴 **NO** | — |

## ⭐ Why it is *not* promoted — the corpus's own restraint

$$\boxed{\begin{array}{c}\textbf{The corpus reaches } P\text{-}08\textbf{'s question independently and explicitly DECLINES to make it a kernel requirement:}\\ \textit{"I would still not declare this a Kernel requirement yet."}\\[6pt] \boxed{\textbf{So the transition class is } \mathbf{[EMP]} \textbf{ and its OBLIGATION is } \mathbf{[OPEN]} \textbf{ — by the corpus's own judgement, not by my caution.}}\end{array}}$$

⛔ **Therefore `𝒯_KOS` is NOT sufficient**, and the statement *"sufficient for the present lower-bound
derivation"* **cannot be made.** ⭐⭐ **This is the finding that settles §15's verdict.**

---

# 9. Re-testing `ADJUDICATED ⇒ persist` / `MEASURED ⇒ reconstructible`

| item | measured | adjudicated | verdict |
|---|:--:|:--:|---|
| **glyph** | ✅ | 🔴 | measured |
| **occurrence** | ✅ | 🔴 | measured |
| **definition** | ⚠️ its *text* is **authored** | ✅ | ⭐ **neither cleanly** — authored text is **not** a measurement and **not** a decision |
| **candidate** | 🔴 | ✅ | adjudicated |
| **establishment act** | ✅ **cited** | 🔴 | measured-as-cited |
| **association** | 🔴 | ✅ | adjudicated |
| **implementation artifact** | ✅ | 🔴 | measured |
| **realization / model-of** | 🔴 | ✅ | adjudicated |
| ⭐ **definition source** | ✅ **observed in code** | ✅ **classified as a source** | ⭐⭐ **BOTH — and `F-4` is the harm** |
| **assertion / status** | 🔴 | ✅ | adjudicated |
| ⭐ **measurement result** | ✅ | ✅ **citing it is a claim** | ⭐⭐ **BOTH — §7** |
| ⭐ **warrant** | ⚠️ **it CITES a measurement** | ✅ **it is an assertion** | ⭐ **BOTH — §6** |

## ⭐⭐ The exact refinement required

$$\boxed{\begin{array}{c}\textbf{The partition is TOO COARSE as applied to ITEMS. Three items are both; one is neither.}\\[6pt] \boxed{\textbf{MODALITY ATTACHES TO THE CLAIM, NOT TO THE ITEM.}}\\[6pt] \textbf{Every item then decomposes cleanly: its measured claims are reconstructible; its adjudicated claims must persist.}\end{array}}$$

⭐ **The `definition` case forces a third modality:** an **authored** text is neither measured nor
decided — **it is irreducible**, and `P-08` §4.3 already said *"may be regenerated: nothing."*

⛔ **The partition is refined, not abandoned.** ⭐ **And the refinement EXPLAINS `F-4`:** `Σ` `D-05`'s
source is a **measured** fact carrying an **adjudicated** classification, and collapsing the two is the
defect itself.

---

# 10. Re-testing `K1`–`K5`

| | protects | smallest witness | independently forced? | derivable from another `K`? | affected by `O-P08-*` | needs splitting? |
|---|---|---|:--:|:--:|---|:--:|
| **`K1`** identity | *this is the same thing* | `W1` | ✅ | 🔴 | ⭐ **`O-P08-3`: `W1` is a MERGE, which `K1` does not cover** | ⭐⭐ **PROBABLY — `[OPEN]`** |
| **`K2`** current state | *this is how it stands* | `W3`, `W7` | ✅ | 🔴 | `O-P08-5` refines | 🔴 |
| **`K3`** warranted transitions | *this state was reached by a decision* | `W2` | ✅ | 🔴 | ⭐ **`O-P08-1`: SCOPE conditional — exceptional vs universal** | 🔴 |
| **`K4`** supersession | *the estate once claimed otherwise* | `W4` | ✅ | 🔴 | ⭐ **`O-P08-6`: SCOPE ENLARGED to warrants** | 🔴 |
| **`K5`** warrant checkability | *the ground can still be inspected* | `W5`, `W6` | ✅ | 🔴 | ⭐⭐ **`O-P08-2`: FORCED and CURRENTLY UNSATISFIED** | 🔴 |

$$\boxed{\begin{array}{c}\textbf{5 of 5 remain independently forced. NONE is derivable from another. NONE is removed.}\\ \textbf{But TWO have unresolved scope } (K3, K4)\textbf{, ONE is unsatisfied } (K5)\textbf{, and ONE may need splitting } (K1).\\[6pt] \boxed{\textbf{A kernel with four caveats on five members is a lower bound that has not settled.}}\end{array}}$$

⛔ **Capability count is not a count of fields, objects, records or storage structures.**

---

# 11. Re-testing the witnesses

| | still forces its capability? | note |
|---|:--:|---|
| **current-state insufficiency** *(A vs B re-disposition)* | ⭐ ✅ **STRENGTHENED** | §5 shows the distinguishing fact is an **adjudication**, non-re-derivable on independent grounds |
| **monotone-history redundancy** | ⭐ 🔴 **SUSPENDED** | its premise is not evidenced (§3). ⚠️ **The proof is valid; its conclusion is unavailable** |
| **`W1`** candidate collapse | ⚠️ ✅ **forces MORE than `K1` provides** | §8 — it is a merge |
| **`W2`** re-disposition as delete+create | ✅ | unaffected |
| **`W3`** `[RF]` reads as never-live | ✅ | unaffected |
| **`W4`** supersession destroys the claim | ⭐ ✅ **BROADENED** | §6 — it now covers warrants |
| **`W5`** source unresolvable | ✅ | `F-4` still live |
| ⭐ **`W6`** measurement unreconstructible | ⭐⭐ ✅ **CONFIRMED LIVE** | §4.4 — 56 renames, **uncommitted**; no identifier existed at measurement time |
| **`W7`** absent endpoint as null | ✅ | unaffected |
| ⭐ **the `P-08` §19 NEGATIVE** *(unresolved→resolved)* | ⚠️ **its refutation is SUSPENDED** | ⭐ it was refuted **on monotonicity**; with `O-P08-1` open, **the original witness may hold after all** |

⛔ **No entropy, bits, compression or storage estimates.** The test remains: *what must stay
distinguishable?*

---

# 12. Circularity audit — mandatory

| suspected circle | verdict |
|---|---|
| **definition identity** | ⭐ **BROKEN** — §5.2, on the independent non-re-derivability of decisions. ⚠️ **A scope qualification survives** (§5.3) |
| **extraction-record persistence** | ⭐ **BROKEN** — the record splits into measured and adjudicative content; only the latter persists, for the general reason |
| **candidate identity** | ✅ **not circular** — forced by `W1` and by `Q3` being an **open adjudication**, both independent of any persistence decision |
| **historical observation** | ⭐⭐ **CIRCULAR AS `P-08` ARGUED IT.** *"the result must persist because it is the only witness of what the corpus said then"* presupposes that what the corpus said then must be recoverable. ⭐ **Broken here:** it is independently forced because **a claim's ground must be inspectable** — `K5`, which `W5` forces without any measurement |
| **warrant** | ✅ **not circular** — §6.2 is four `[EMP]` witnesses, and §6.5's regress terminates in the measured modality |
| **provenance** | ✅ **not circular** — `FR-001`'s dual role is `[EMP]` |
| **corpus-state identification** | ⚠️ ⭐ **`[OPEN]`, and honestly so.** Whether a corpus state must be identifiable **cannot** be settled by noting that measurements need it; §4 derives the identifier's *properties* **conditionally on measurements being cited at all**, which is `[EMP]` |

$$\boxed{\textbf{One genuine circularity found and broken } (\textit{historical observation})\textbf{. One left explicitly } \mathbf{[OPEN]}\ (\textit{corpus state})\textbf{. No circular conclusion enters the kernel.}}$$

---

# 13. Statistical sanity check

⚠️ **Category-error detection only. No statistical ontology imported.**

| test | does the `P-08` model commit it? |
|---|---|
| **observation → object** | ⭐ **NEARLY — and §9 catches it.** Requiring a *result-as-cited* to persist risks reifying an observation. **Averted** by making it a **historical observation** bearing supersession, never an entity with continuity |
| **measurement vs measured value** | 🔴 **no** — `P-08` §13 already separated them; §4 hardens it: **a bare number is uninterpretable** |
| **repeated measurement vs changed state** | ⭐ **this is `O-P08-5`, and it is now answered** — the corpus state decides which it is |
| **estimate → parameter** | ⭐ **`F-4` commits it, and the estate records it as a defect.** ⛔ Not repaired |
| **sample vs population** | 🔴 no — scope declaration is `[EMP]` practice |
| **provenance vs observation** | 🔴 no — §6 keeps warrant and measurement apart |
| **hypothesis vs adjudication** | ⭐ **guarded by `P-08` §12** — discovery gives retrospective **identity**, never retrospective **establishment** |
| **label vs identity** | 🔴 no — `𝒦`/`𝕂`/`K`; `ℐ`/`𝓘` `[OPEN]` |

⭐ **One near-miss, averted by §9's refinement. One committed error, already recorded as `F-4`.**

---

# 14. DDD sanity check

⚠️ **DDD terminology is not evidence for semantic ontology.** ⛔ **No aggregate root, repository,
entity, event or bounded context is introduced.**

| where DDD **confirms** a derived distinction | |
|---|---|
| an object whose identity survives total state replacement | ⭐ confirms `K1` — independently of what it is called |
| immutable, superseded-not-updated claims | ⭐ confirms `K4`, **and §6 extends it to warrants**, which DDD has no name for |
| four lifecycles with four retention rules | ⭐ confirms they cannot share one consistency boundary |

| where DDD **exposes a contradiction** | |
|---|---|
| ⭐⭐ **`τ11` (merge/split)** | **no DDD notion covers the RETIREMENT of an identity with continued resolvability of the retired one.** ⭐ The gap `P-09` found is a gap in the cross-check too — **which is corroboration that it is a real gap, not a modelling artifact** |
| **the association** | still not a Relationship — independent lifecycle, absent endpoint |

⛔ **`P-07`'s `[OPEN]` on aggregate existence is preserved unchanged.**

---

# 15. Kernel-stability matrix

| open question | current answer | evidence class | changes `K1`–`K5`? | changes only a condition? | must remain open? |
|---|---|:--:|---|---|:--:|
| **`O-P08-1`** status regression | ⭐ **`MD-018 ⇒ monotone` is NOT EVIDENCED.** Corpus states *ordered* + *no-skip*; **direction language: zero**. Monotonicity is evidenced only for **MD-015's ladder**, which MD-018 declares itself distinct from | ⭐ `[EMP]` on the wording · `[OPEN]` on the property | 🔴 **no capability changes** | ⭐ ✅ **`K3`'s SCOPE: exceptional → possibly universal** | ⭐ **YES — needs a governance ruling, not a derivation** |
| **`O-P08-2`** corpus state | ⭐ **minimum requirement DERIVED** (§4.3, claim-relative sensitivity). **No identifier existed at measurement time** | `[DERIVED]` + ⭐ `[EMP]` for the failure | 🔴 no | ⭐ ✅ **`K5` is FORCED and UNSATISFIED — `W6` LIVE** | ⚠️ **the mechanism is `[ARCH]`; the requirement is settled** |
| **`O-P08-3`** transition set | ⭐⭐ **INSUFFICIENT.** `τ11` representation-level evolution is **corpus-attested** and omitted; the corpus itself **declines** to make its obligation a kernel requirement | ⭐⭐ `[EMP]` for the class · `[OPEN]` for the obligation | ⭐⭐ **POSSIBLY — `K1` may need splitting** | 🔴 **no — this is a capability question** | ⭐⭐ **YES** |
| **`O-P08-4`** extraction record | ⭐ **NON-CIRCULAR.** Adjudicative content persists for the general reason; measured content need not | `[DERIVED]` | 🔴 no | ✅ **`P-07`'s definition-identity claim narrows to *conditional on adjudication*** | 🔴 **resolved** |
| **`O-P08-5`** result-as-cited | ⭐ **DUAL MODALITY** — distinct observation across corpus states; supersession within one | `[DERIVED]` | 🔴 no | ✅ refines `K2`/`K4` | ⚠️ **operationally blocked by `O-P08-2`** |
| **`O-P08-6`** warrant withdrawal | ⭐⭐ **WARRANTS ARE WITHDRAWABLE — 4 `[EMP]` witnesses.** They are **assertions** | ⭐ `[EMP]` | 🔴 **no `K6`** | ⭐ ✅ **`K4`'s SCOPE ENLARGES to warrants; recursion terminates in MEASURED** | 🔴 **resolved** |

## Kernel status

$$\boxed{\textbf{NOT YET STABLE}}$$

**Exactly why — three reasons, in descending force:**

1. ⭐⭐ **The transition set is provably incomplete** (`O-P08-3`). A *lower bound over `𝒯_KOS`* is not a
   lower bound at all when `𝒯_KOS` is missing an attested class **whose omission lets `W1` — the
   witness that forces `K1` — actually occur.** A constraint set must not be missing the case that
   motivated its first member.
2. ⭐ **`K3`'s reach rests on an unevidenced premise** (`O-P08-1`). It may be exceptional or universal,
   and **an architecture comparison would evaluate candidates differently under each.**
3. ⭐ **`K5` is forced and currently unsatisfied** (`O-P08-2`, `W6` live). A constraint the estate is
   **presently violating in its own measurements** is not yet a stable input.

⭐ **What is stable:** all five capabilities remain **independently forced**, **none derivable from
another**, and **none removed** — `O-P08-4`, `O-P08-5` and `O-P08-6` resolved *without* enlarging the
kernel. ⚠️ **The membership is holding; the boundary is not.**

⛔ **This is not a negative verdict about `P-08`.** `P-08` labelled its own result a lower bound and
named the six questions; `P-09` answered three, conditioned two, and found one it had not asked.

---

# 16. Preconditions for a `P-10` architecture comparison

⛔ **No architecture is designed, compared or ranked here.**

| # | precondition | kind | status |
|---|---|---|---|
| **P1** | ⭐⭐ **the transition set must be closed enough that no attested class is missing** — specifically, `τ11` must be classified and the *"may a retired identity be cited?"* question answered | ⭐ **SEMANTIC** | 🔴 **BLOCKING** — `[OPEN]`, and the corpus declines to rule |
| **P2** | ⭐ **status monotonicity must be CHARACTERIZED** — not necessarily *decided*, but the estate must know whether `K3` is exceptional or universal | ⭐ **EPISTEMIC** *(a governance ruling, not a derivation)* | 🔴 **BLOCKING** |
| **P3** | **corpus-state semantics must be sufficient** — §4.3's requirement satisfiable, and `W6` closed for existing claims | **SEMANTIC**, with an `[ARCH]` mechanism | 🔴 **BLOCKING** |
| **P4** | **warrant lifecycle must be characterized** | **SEMANTIC** | ✅ **MET — §6** |
| **P5** | **extraction-record dependency must be non-circular** | **EPISTEMIC** | ✅ **MET — §5** |
| **P6** | **result-as-cited lifecycle must be characterized** | **SEMANTIC** | ⚠️ **MET semantically; operationally waits on `P3`** |
| **P7** | **the modality of every item must be resolvable** | **SEMANTIC** | ✅ **MET — §9's refinement: modality attaches to the CLAIM** |

$$\boxed{\textbf{4 of 7 met. 3 BLOCKING — } P1 \textbf{ (a semantic gap)}, P2 \textbf{ (an epistemic ruling)}, P3 \textbf{ (a live failure)}.}$$

⭐ **`P2` is the only one that cannot be closed by derivation** — the corpus is silent, and silence
about direction is a **governance question**, not a research one.

**Kept strictly apart:** *semantic precondition* (`P1`, `P3`, `P4`, `P6`, `P7`) · *epistemic
precondition* (`P2`, `P5`) · *architectural choice* (addressing, locators, identifier scheme,
transition-record and supersession representation, `A/B/C/D`) · *implementation mechanism* (⛔ **absent
from this document**).

---

# 17. Decision boundary

## Settled `[EMP]`
*"No entry may skip a stage"* — **three times, three homes** · the chain's **six ordered stages** ·
*"nothing has left `candidate`"* · ⭐ **zero direction language anywhere near `status_chain`** (positive
control: `skip a stage` → 3 hits) · ⭐ **MD-018 declares itself distinct from MD-015's ladder**, and
MD-015's ladder is *"the HIGHEST stage reached … cumulative"* · a parallel scale terminating in
**`FALSIFIED`** (`0155.yaml`) · ⭐⭐ **the corpus's own four-transition representation history including
*"one dimension was split into two"* and *"a previous determination was revised"*, with
*"I would still not declare this a Kernel requirement yet"*** · ⭐⭐ **four withdrawals in this lane, every
one a withdrawal of a WARRANT** · `τ7`: **56 renames, uncommitted** · `MD-020`: *"corpus grew during the
pass"* · `F-4`/`F-5` stand unrepaired.

## Derived `[DERIVED]`
⭐⭐ **ordered ⇏ monotone; a no-skip rule constrains STEP SIZE, not DIRECTION** · ⭐⭐ **`P-08` imported
monotonicity from the artifact MD-018 distinguishes itself from** · ⭐ **five of six backward-looking
notions are not regression, so absence of observed regression proves nothing** · ⭐⭐ **a corpus-state
identifier must be stable, content-derived, scope-bound, claim-relatively sensitive, and free of mutable
dependencies — ordering and restorability are NOT minimal** · ⭐ **a timestamp is not a state
identifier** · ⭐ **the extraction record splits into measured and adjudicative content; only the latter
persists, and for the general reason — NON-CIRCULAR** · ⭐ **warrants are assertions; `K4`'s scope
enlarges; the regress terminates in the MEASURED modality** · ⭐⭐ **modality attaches to the CLAIM, not
the ITEM — three items are both, one (authored text) is neither** · ⭐ **a result-as-cited has no
continuity qua observation and bears supersession qua assertion** · ⭐⭐ **`W1` is a MERGE, and `K1` does
not cover it** · ⭐ **one genuine circularity found in `P-08`'s historical-observation argument and
broken via `K5`** · **the redundancy proof is valid and its conclusion unavailable** · **`K1`–`K5` all
independently forced, none derivable from another**.

## Architectural choices `[ARCH]`
the corpus-state identifier **mechanism** · how transition records and supersession are represented ·
addressing strategy · locator adoption · artifact keying · whether to name the association an Entity ·
aggregate modelling · the physical pattern · **`A/B/C/D` — ⛔ not selected.**

## Open `[OPEN]`
⛔ **Not converted to `[DERIVED]` by convenience.** **`Q3`** · **`Q4`** · **`O-P08-1`** *(needs a
governance ruling)* · ⭐⭐ **`τ11`'s obligation — the corpus explicitly declines to rule** · ⭐⭐ **may a
retired identity be cited, and must it resolve to its successor? — the `K1`-split question** ·
`definition → candidate` upper bound · occurrence identity mechanism · implementation-artifact
lifecycle · establishment-act promotion · aggregate existence · locator sufficiency · physical
persistence pattern · **whether `𝒯_KOS` has further omitted classes** *(one was found; the search was
not exhaustive)*.

### New open questions from `P-09`
| | |
|---|---|
| **`O-P09-1`** | ⭐⭐ **May an identity be RETIRED, and must citations to it still resolve?** Forced by `τ11`; decides whether `K1` splits |
| **`O-P09-2`** | ⭐ **Is a *determination* (corpus `t₄`) the same kind of thing as a *status*?** If yes, `O-P08-1` is answered **against** monotonicity by existing corpus evidence |
| **`O-P09-3`** | **Does an authored text constitute a third modality** beside measured and adjudicated? §9 says the partition does not fit it |
| **`O-P09-4`** | ⭐ **Must the corpus-state identifier be uniform across claim classes**, or may sensitivity vary per class as §4.2 permits? |

---

```
P-09 KERNEL STABILITY / PRECONDITIONS DERIVED

KERNEL STATUS: NOT YET STABLE

PERSISTENCE KERNEL:
  K1-K5 RETAINED AS A CONDITIONAL LOWER BOUND — all five independently forced, none derivable
  from another, none removed. BUT: K1 may require splitting (O-P08-3 / O-P09-1), K3's scope is
  conditional on an UNEVIDENCED monotonicity premise (O-P08-1), K4's scope ENLARGES to warrants
  (O-P08-6, resolved), and K5 is FORCED BUT CURRENTLY UNSATISFIED (O-P08-2, W6 live).
  The count of 5 is NOT established as a lower bound, because the transition set it quantifies
  over is provably incomplete.

KERNEL-CHANGING OPEN QUESTIONS:
  O-P08-3  transition set INSUFFICIENT — representation-level evolution (tau-11: add / SPLIT /
           merge / revise-a-determination) is CORPUS-ATTESTED and omitted; W1, the witness that
           forces K1, is precisely an unrecorded MERGE, which K1 does not cover. The corpus
           itself declines to make its obligation a kernel requirement.
  O-P09-1  may an identity be retired, and must citations to it still resolve? — decides K1's split
  O-P08-1  MD-018 => monotone is NOT EVIDENCED (ordered + no-skip only; zero direction language;
           monotonicity is evidenced for MD-015's ladder, from which MD-018 declares itself
           distinct) — K3 exceptional or universal
  O-P08-2  no corpus-state identifier existed at measurement time (56 uncommitted renames) — K5
           forced and unsatisfied

  RESOLVED WITHOUT ENLARGING THE KERNEL: O-P08-4 (non-circular) · O-P08-5 (dual modality) ·
  O-P08-6 (warrants are assertions; recursion terminates in the MEASURED modality)

ARCHITECTURE-COMPARISON PRECONDITIONS:
  P1 BLOCKING  no attested transition class may be missing; tau-11 must be classified   [SEMANTIC]
  P2 BLOCKING  status monotonicity must be CHARACTERIZED — a governance ruling, not a
               derivation; the corpus is silent on direction                            [EPISTEMIC]
  P3 BLOCKING  corpus-state semantics sufficient and W6 closed for existing claims       [SEMANTIC]
  P4 MET       warrant lifecycle characterized                                           [SEMANTIC]
  P5 MET       extraction-record dependency non-circular                                 [EPISTEMIC]
  P6 MET semantically, operationally waits on P3                                         [SEMANTIC]
  P7 MET       modality resolvable — it attaches to the CLAIM, not the ITEM              [SEMANTIC]

NO PERSISTENCE ARCHITECTURE SELECTED
NO SCHEMA v3
NO A/B/C/D DECISION
```
