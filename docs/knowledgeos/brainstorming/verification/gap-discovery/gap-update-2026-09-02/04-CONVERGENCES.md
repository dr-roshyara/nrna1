# 04 — Independent Convergences

**Two lanes that did not read each other reaching the same object.** Each entry shows the
derivation, so a reviewer can check the identification rather than accept the word *"converged"*.

⚠️ **Convergence is evidence, never proof.** The kernel lane's own `19`/`29` showed two lanes can
look convergent while holding **opposite closure verdicts**. Nothing here is upgraded on convergence
alone.

---

## 1. ⭐⭐⭐ EXACT: `ℛ_req`-adequacy **is** my `Expressive(F,ℐ)` — they are contrapositives

**Their definition** (`SPEC-RREQ-2026-V1`, 2026-09-02). A distinction `d` is an equivalence relation
`~_d` on the state space `S`. A representation language `𝒦` with encoding `E : S → 𝒦` **preserves**
`d` iff

$$\forall s_1,s_2 \in S:\quad (s_1 \nsim_d s_2)\ \Longrightarrow\ (E(s_1) \neq E(s_2))$$

**My definition** (`step-272/12`, adopted 2026-09-01):

$$Expressive(F,\mathcal I) \iff \forall I \in \mathcal I\ \forall s_1,s_2:\quad s_1 \sim_F s_2\ \Longrightarrow\ I(s_1) = I(s_2)$$

**The identification.** Let `~_F` be the kernel of the representation map, `s₁ ~_F s₂ :⟺ F(s₁)=F(s₂)`,
and let each mandated invariant `I ∈ ℐ` induce the distinction `s₁ ~_{d_I} s₂ :⟺ I(s₁)=I(s₂)`. Then:

| | statement |
|---|---|
| mine | `F(s₁) = F(s₂)  ⟹  I(s₁) = I(s₂)` |
| theirs | `I(s₁) ≠ I(s₂)  ⟹  F(s₁) ≠ F(s₂)` |

$$\boxed{\textbf{Contrapositives. Same condition, under } \mathcal I \leftrightarrow \mathcal R_{req}\ \text{and}\ F \leftrightarrow E.}$$

**And their projection criterion is my definition applied to a composite:** *"`π` is
`ℛ_req`-adequate iff `∀d ∀s₁,s₂: s₁ ≁_d s₂ ⟹ π(E(s₁)) ≠ π(E(s₂))`"* — that is exactly
`Expressive(π ∘ E, ℐ)`.

`[INF]` **Two lanes wrote the same predicate, one as an expressibility condition and one as a
non-collapse condition, within 32 hours and without citing each other.** My `12-SUFFICIENCY-DEFINITION`
is dated 2026-09-01; `SPEC-RREQ` is 2026-09-02.

### 🔴 And the identification exposes what their spec lacks

> ⚠️ **CORRECTED** — see [`08` §6](./08-SCOPE-ERROR-AND-CORRECTIONS.md). This is true of the
> `SPEC-RREQ` **document** and **false of the estate**: the approved TODO register carries the missing
> conjunct as **"operational preservation"** (Group I) and as **"Invariant Preservation → Adequacy"**
> (Group H, `H1`). And `H4`'s `Adequate(π,Q) ⇒ D_Q ⊆ Preserved(π)` is **question-indexed, so it
> subsumes my global definition.** `H3`'s `Custody(I)` has no counterpart in my package at all.


My definition has **two** conjuncts:

$$Sufficient(F,\mathcal O,\mathcal I) \iff \underbrace{Congruent(F,\mathcal O)}_{\textbf{no counterpart in } \mathcal R_{req}} \wedge \underbrace{Expressive(F,\mathcal I)}_{= \ \mathcal R_{req}\text{-adequacy}}$$

`Congruent(F,𝒪) ⟺ ∀T∈𝒪 ∀c ∀s₁,s₂: s₁~_F s₂ ⟹ T(s₁,c) ~_F T(s₂,c)` — **the operations must be
well-defined on the quotient.** `ℛ_req` is stated **purely over states and one encoding**; it says
nothing about whether a transformation respects the distinctions it preserves.

$$\boxed{\textbf{A representation can be } \mathcal R_{req}\textbf{-adequate and still have an operation that is not well-defined on it.}}$$

`[PROP]` **Offered, not asserted:** `ℛ_req`-adequacy is **necessary and not sufficient** for
implementability, and the missing conjunct is congruence over `𝒪`. **This is a testable claim** —
exhibit a `ℛ_req`-adequate encoding and an operation that maps two `~_E`-equal states to
`~_E`-distinct results. **It is also why their `𝓘` schema and their `ℛ_req` spec are two documents:
they are the two conjuncts, written separately and never joined.**

## 2. ⭐⭐⭐ EXACT, then REFUTED: FDE `(S⁺,S⁻)` is my `Σ`

`𝒫({Support, Refute})` and FDE's double-powerset valuation `V(φ) ⊆ {T,F}` are **the same
four-element lattice**. `SPEC-RREQ` §1.1 adopts it as the formal semantics of `Knowledge Status`
citing `KR-CONTR-FDE-2026-09`; my `step-272` derived it from OR-merge + `Retract`.

| | my route | their route |
|---|---|---|
| derivation | OR-merge and `Retract` force two independent channels | 13 required distinctions, four representations compared |
| `Unknown ≠ Refuted` | Kolmogorov additivity **cannot** express it | K3 **collapses** all five `DirectContradiction\|X` pairs; FDE preserves every one |
| verdict | *"the strongest exact correspondence in the package"* | **11 preserved, 2 collapsed, `adequate = no`** |

$$\boxed{\textbf{The same structure, derived twice, and executed once. It is confirmed on conflict and refuted on adequacy.}}$$

**Being refuted by an implementation of one's own model is the most informative outcome available**,
and it is the reason this convergence matters more than the agreement would have.

## 3. ⭐⭐ My *"two bits are needed"* meets *"no flat domain of any cardinality is adequate"*

`KR-CONTR-EVAL-2026-09` §9, `[INF]` labelled a **theorem** in its own lane:

> *"No flat domain — of ANY cardinality — indexed by evaluation outcome is adequate."*
> **Minimum structure is a pair**, with an indispensable **reason/boundary** component; `reason`
> alone is **not** adequate (10 values / 21 conditions; fails `Satisfied` vs `Unsatisfied`).
> The minimum flat domain size **= the chromatic number `χ` of the required-distinction graph**;
> `χ = 3` on the protocol set, ranging **3–21**.

| my claim | their claim | relation |
|---|---|---|
| `Σ` needs **two bits**, not three values | a **pair** is the minimum structure | ✅ **same shape, reached from graph colouring** |
| `Σ` is a flat 4-value label set | **no flat domain is adequate at any cardinality** | 🔴 **mine is refuted as a complete answer** |

`[INF]` **The `χ` framing is the sharper instrument and it is not mine.** It converts *"how many
epistemic statuses are there?"* — a question my package treated as a modelling choice — into
**a graph-colouring computation on the required-distinction graph, whose answer varies 3–21 with the
condition set.** That is why no fixed flat vocabulary can work, and it explains my two collapses
without appealing to any of my arguments.

## 4. ⭐⭐ A third independent arrival at the inquiry/awareness channel

| lane | the finding |
|---|---|
| **corpus, Step 281** | `Q_t ⊆ 𝒫` — an inquiry register **outside `K`**, repairing CF#7's `M1`/`M2`/`M3` collapse |
| **mine, `theory-compatibility/01`** | `Q_t ≅ 𝒜ᵢ` — Fagin–Halpern awareness sets, the standard DEL repair for the identical collapse from a **total** valuation |
| **`KR-CONTR-FDE`, 2026-09-02** | `NotAssessed`, `Underdetermined` and `TheoryIncomplete` land in **`(1,0)`, not `(0,0)`** — *"a positive `Standing` is just as boundary-ambiguous as an empty one"* ⇒ **a `reason`/`boundary` channel is required alongside the support pair** |

$$\boxed{\textbf{Three routes — minimality analysis, awareness logic, and a measured distinction matrix — all place the inquiry state OUTSIDE the support structure.}}$$

`[INF]` **The third is the strongest, because it is measured and because it corrects the other two on
a detail neither had.** Step 281 and I both located the ambiguity at *absence*. **It is not at
absence; it is at every `Standing` class.** The repair's *position* was right in all three; its
*extent* was understated in two.

## 5. ⭐ `Kernel_engineering ≠ Kernel_epistemic` is my two-`K` blocker from the membership side

`MD-006`, reached by sequential corpus reading: *"the corpus keeps **enumerating kernel members
without ever stating a membership property**"* — which is `291 Audit A`'s *"what is missing is not an
enumeration but a MANDATORY-MEMBERSHIP RULE"*, reached by graph analysis. And it adds what neither of
us had: *"portability … vs epistemic necessity … **these are different minimality tests and may
select different sets**."*

$$\boxed{K_{engineering}\ (EKS \to PKS \to KnowledgeOS)\qquad\neq\qquad K_{epistemic}\ (Knowledge \to K_t \to Kernel \to Operators)}$$

`[INF]` **This may be the cause of my blocker rather than a parallel to it.** My finding was *two
definitions of `K` with no shared vocabulary*. **If "kernel" carries two distinct minimality criteria
at once, two vocabularies is the expected consequence, not an anomaly** — and `readiness/07`'s
observation that *the unblocked subset **is the EKP*** is then not a coincidence but a measurement of
`K_engineering`. ⚠️ **Recorded as a hypothesis; I did not test it.**

## 6. ⭐ Negative convergence: three independent traditions deny *"probability is knowledge"*

| claim | KnowledgeOS | Dretske information-flow | Kallenberg probability |
|---|:--:|:--:|:--:|
| *"Probability IS knowledge itself"* | **No** | **No** | **No** |
| *"One scalar probability defines knowledge"* | **No** | **No** | **No** |

Both are exactly what the corpus refuted alone on 2026-08-25 (*"the biggest overclaim: knowledge is
conditional expectation"*, *"projection = measure is not the right primitive"*).

`[INF]` **Negative convergence across independent traditions is the strongest evidence class
available in this estate**, and it lands on my `theory-compatibility/03` verdict — *Kolmogorov
incompatible and declined* — from two directions I did not use. **Recorded as corroboration of a
refusal, which is the only thing corroboration of a negative can be.**

## 7. ⭐ The corpus reached my `Determination / Decision / Action` finding as a pipeline

I recorded (`theory-compatibility/05`) that KnowledgeOS makes a **three-way** split where Wald makes
two, and that *"the extra cut is the one governance needs."* The layered model supplies the pipeline:

$$K_t \to Zero \to \textbf{Determination} \to Decision \to Authorization \to Action$$

**And it has five cuts, not three** — `Authorization` is separated from both `Decision` and `Action`.
`[INF]` **My *"three-way where Wald has two"* was itself an undercount.** The corpus's own operating
sequence distinguishes *judging*, *choosing*, *authorizing* and *executing*. **Wald has one cut
where KnowledgeOS has four.**
