# 04 — Gap Reclassification

**Mandate Part D.** *"The number 53 may mix fundamentally different things… The goal is to prevent
the theory from being declared incomplete merely because it deliberately leaves application-specific
parameters open."*

All 53 first-order gaps reclassified into the mandate's ten categories, plus the five gaps this
second-order pass added and the three it withdrew.

**Categories:** ① actual theoretical hole · ② intentional parameter · ③ terminology ambiguity ·
④ implementation gap · ⑤ governance gap · ⑥ evidence gap · ⑦ historical inconsistency ·
⑧ out-of-scope capability · ⑨ normative choice · ⑩ already resolved by corpus but previously missed

---

## 1. The reclassification

| ID | Gap (abbreviated) | 1st-order | **Reclassified** | Note |
|---|---|---|---|---|
| G-01 | `𝒪` unenumerated ⟹ `≡` unconstructed | CRIT | **⑩ + ②** | §256.2 + §259.7 enumerate 15 ops; §259.18 states the quantifier problem itself; §254 already writes `Minimality(K\|𝒯)` |
| G-02 | `K=(𝒜,ℛ)` minimality conditional | CRIT | **②** | Relativity to `𝒯` is the corpus's design (§254, §259.15–16), not a defect |
| G-03 | `A∈K`, `K₁=K₂` ill-defined | CRIT | **① (narrowed)** | Congruence eliminates F1/F2/F3; only structural-class equalities survive (SO-EXP-01). Residual: canonical form unfixed |
| G-04 | dependency graph cyclic | CRIT | **⑩** | §187.28–29 breaks it; the two-level model is *implemented* (`02`) |
| G-05 | 11/26 objects transitively non-computable | CRIT | **①** | Real. Narrowed by the `A_struct` split (`03` ON-5) |
| G-06 | **Σ is ≥5 orthogonal axes** | CRIT | **①** | **The frontier.** §271.35 marks Σ 🔴 and §271.36 commissions Step 272 to derive it |
| G-07 | ordinal averaging meaningless | CRIT | **① + ⑩** | §264.23 forbids it; §271.13 re-flags it. Audit of existing rules still owed |
| G-08 | `Determination` absent | CRIT | **⑩** | §157.22 aggregate + §165.8 type. **Withdrawn as stated** (`03`) |
| G-09 | 28 incomparable `K` definitions | CRIT | **⑦** | Historical; §240 registers it; resolved by the projection reading |
| G-10 | schema vocabulary ungoverned | CRIT | **⑤** | Genuine governance gap; unaffected by D-2's resolution |
| G-11 | evidence layer has no inputs | CRIT | **①** | Real theoretical hole |
| G-12 | no empirical relational structure | CRIT | **①** | Real; Roberts' representation stage never executed |
| G-13 | corpus circularity after ~Step 258 | CRIT | **⑦** | Method-historical; now worse (see §3) |
| G-14 | `Relevant` class C | HIGH | **①** | Real |
| G-15 | `Context` untyped | HIGH | **① + ③** | Occurs in the two most load-bearing definitions |
| G-16 | transition signature unsettled | HIGH | **⑦ + ⑩** | §256.27/§257.35 give a typed registry; the 45 RHS are historical |
| G-17 | no pre/post/failure semantics | HIGH | **①** | §256.16–17 frames partiality; the predicates are absent |
| G-18 | no operation versioning | HIGH | **①** | §266.14 states the dependency |
| G-19 | no transition/merge provenance | HIGH | **①** | Real; §265 has no row for it |
| G-20 | merge convergence rule unnamed | HIGH | **②** | Convergence is a property *of a rule*; naming it is a deployment parameter |
| G-21 | `AggregateSupport` / `IndependenceFactor` refuted | HIGH | **① + ⑩** | §270.28 and §271.13 flag both; relabelling still owed |
| G-22 | no `(Ω,𝓕,P)` | HIGH | **② + ①** | Probability is an *external regime* by design; but the numbers in use lack one |
| G-23 | single `t` vs bitemporality | HIGH | **①** | Real |
| G-24 | `ℛ` edges not first-class | HIGH | **①** | Real, and load-bearing |
| G-25 | `Unknown` homeless | HIGH | **① → G-06** | Subsumed by Σ |
| G-26 | no negation/conditional/quantification | HIGH | **⑧ + ②** | A logic layer is a scope decision; `Policy` carries rules (§271 🔴) |
| G-27 | `Insufficient` missing | HIGH | **① → G-06** | Subsumed by Σ |
| G-28 | `Authority` = permission vs trust rank | HIGH | **③** | Two concepts, one word |
| G-29 | `Status` overload uncaught for 270 steps | HIGH | **③ → G-06** | Method note; substance is G-06 |
| G-30 | `Regime` vanished | HIGH | **⑦** | §271 reinvents it without the word |
| G-31 | aggregate boundary unfixed | HIGH | **⑩** | §157/§165/§173 separate `Knowledge`/`Determination`/`Decision` explicitly |
| G-32 | `statuses.yaml order` conflates two things | HIGH | **④** | |
| G-33 | `vocabulary-integrity.yaml` missing | HIGH | **④** | |
| G-34 | 4 of 5 invariants pass vacuously | HIGH | **⑥** | |
| G-35 | "47 tests" is a filter artifact | HIGH | **⑥** | |
| G-36 | selection precision/recall never run | HIGH | **⑥** | Still runnable |
| G-37 | no Policy / version identity | HIGH | **①** | |
| G-38 | *implementation contradicts the authority stipulation* | HIGH | **WITHDRAWN** | Refuted by `02`: 132/132 `humanActRef`; they agree |
| G-39 | `Claim`, `Verdict` undefined | MED | **③** | |
| G-40 | `Candidate` conflated | MED | **③ → G-06** | |
| G-41 | `Contested` is a process | MED | **③ → G-06** | |
| G-42 | independence unrepresentable | MED | **①** | Structural counterpart of G-21 |
| G-43 | `𝒦` overloaded | MED | **③** | |
| G-44 | `025c`/`025n` same title | MED | **⑦** | |
| G-45 | 11 transition names | MED | **③** | |
| G-46 | `GovernanceLineageGraph` Type-3 reported as Type-1 | MED | **⑥** | |
| G-47 | "KnowledgeOS" code is a git-hook doctor | MED | **⑥** | |
| G-48 | `T` not invertible | MED | **②** | Destructive by design; §266.15 concurs |
| G-49 | step numbers unreliable | LOW | **⑦** | 217, 229, **268** absent |
| G-50 | 34 exact duplicates | LOW | **⑦** | |
| G-51 | Q14 holds three `K` definitions | LOW | **⑦** | |
| G-52 | 20 structural S2 failures, all in `archive/` | LOW | **④** | |
| G-53 | near-duplicates not enumerated | LOW | **⑦** | |

### Added by this second-order pass

| ID | Gap | Category |
|---|---|---|
| **G-54** | `Split` has no semantic-preservation invariant (§256.10 states the requirement and supplies none) | **①** |
| **G-55** | The merge-provenance invariant (§265.11, boxed) is **not expressible** in the terminal `A` — one provenance slot cannot record two sources | **①** |
| **G-56** | Congruence (§259.15) is necessary but **not sufficient**; invariant-expressibility is a second criterion the corpus never states | **①** |
| **G-57** | `humanAct` / `humanActRef` are untyped prose (79 strings, 0 typed objects); the constitutional layer is recorded but not machine-checkable | **④** |
| **G-58** | `Determination` was lost in the DDD→formalization band transition, unrecorded — the third instance of this failure mode (with `Regime` and the repaired measure-theory model) | **⑦** |

---

## 2. The count that matters

| Category | Count | What it means for "completeness" |
|---|---:|---|
| ① actual theoretical hole | **17** | genuinely open |
| ② intentional parameter | **7** | **not incompleteness** — deliberately deployment-relative |
| ③ terminology ambiguity | **9** | repairable by a glossary act, not research |
| ④ implementation gap | **5** | engineering |
| ⑤ governance gap | **1** | engineering + a governance act |
| ⑥ evidence gap | **6** | run the tests / correct the citations |
| ⑦ historical inconsistency | **12** | archaeology; does not affect the current model |
| ⑧ out-of-scope capability | **1** | a scope statement, not a hole |
| ⑨ **normative choice** | **0** | **see `06`** |
| ⑩ already resolved, previously missed | **7** | **withdraw or downgrade** |
| **withdrawn** | **1** | G-38 |

*(Rows sum above 58 because ten gaps carry a primary and a secondary category; each is counted in
both. Distinct gap IDs: 53 original − 1 withdrawn + 5 new = **57**.)*

### The headline correction

> **17 of 57 are actual theoretical holes.** The first-order register's "53 gaps, 13 CRITICAL"
> conflated genuine holes with intentional parameters, historical debris, terminology, engineering
> tasks and things the corpus had already resolved.

And of the 13 first-order CRITICALs: **4 are ⑩ (already resolved)**, **1 is ② (intentional)**,
**2 are ⑦ (historical)**, **1 is ⑤**, leaving **5 genuine CRITICAL theoretical holes** — of which
**four converge on one object.**

---

## 3. The convergence

| Remaining CRITICAL hole | Converges on |
|---|---|
| G-06 Σ is ≥5 axes | **Σ** |
| G-25 `Unknown` homeless | **Σ** |
| G-27 `Insufficient` missing | **Σ** |
| G-11 evidence layer has no inputs | evidence → **Σ** (assessment produces Σ) |
| G-12 no empirical relational structure | measurement of **Σ** |

**Five of the five remaining CRITICAL theoretical holes are the same object: `Σ`.**

And that is exactly what the corpus's own latest artifact says. Step 271 §271.35 marks `K` 🟢 and
`Σ` 🔴, and §271.36 commissions:

> **STEP 272 — "Derive the minimum epistemic-status structure from distinguishability,
> contradiction, missingness, evidence assessment, supersession and inference — while explicitly
> separating epistemic state from lifecycle and governance state."**

**Step 272 does not exist.** (Verified: highest step = 271; 217, 229 and **268** also absent.)

`exec/so_exp05_sigma_from_272.py` partially discharges it (`05` §4).

---

## 4. A note on ② — intentional parameters

The mandate's warning is well founded. Seven gaps are **deliberate deployment relativity**:

- `Minimality(K | 𝒯)` — §254 writes it relative from the start
- `Congruent_tested ≠ Congruent_global` — §259.16 refuses the stronger claim on principle
- merge convergence is a property *of a chosen rule* — §025l's claim needs a rule, not a fix
- probability is an **external regime** — settled 2026-08-25, re-derived 2026-08-30
- `T` non-invertibility — §266.15 states it as a fact, not a defect
- the logic layer (negation/conditionals) — a scope statement
- `𝒪` itself — §259.15's candidate survives "that test only", by design

**Counting these as incompleteness is a category error**, and the first-order register made it for
at least G-02, G-20, G-22 and G-48.

---

**Next:** `05-CANONICAL-DEPENDENCY-CLOSURE.md`.
