# 01 — What Moved

Every row: **my registered finding** → **the new artifact** → **the movement, classified.**
Movement classes: `CLOSED` · `CHARACTERIZED` (relation now known, not ratified) · `SUPERSEDED`
(a stronger finding replaces mine) · `SHARPENED` · `EXECUTED` (my claim was tested) · `STALE`.

**No status in `readiness/01` is upgraded on the strength of a self-declared ratification stamp — see `06`.**

---

## 1. ⭐⭐⭐ The primary blocker is now CHARACTERIZED

**My finding** (`readiness/README`, `02` §127): *"Most important blocker: which `K` is canonical —
two rival definitions, 0 shared symbols. The only blocker no derivation can resolve."*

**The new artifact** — `REFINED-STEP-285.md`, kernel lane:

$$\boxed{(\mathcal A,\mathcal R)\;=_{semantic}\;\pi_K(K_t)}$$

after unpacking `Assertion`, modulo a **declared** drop of `{Event, Policy, Action}`; and
**explicitly NOT `=_structural`, NOT `=_observational`.** Further: **definable but NOT computable**
(`Qualify` has no body) and **lossy** — `replay`, `policy-eval` and `authorize` are unanswerable in
`(𝒜,ℛ)`.

| | before | after |
|---|---|---|
| relation between the two `K`s | **unknown — "rivals"** | **a lossy semantic projection, with the loss enumerated** |
| what blocks closure | *"requires governance"* | **`Qualify` has no body** — a *derivation* blocker, named |
| shared symbols | 0 | 0 — **unchanged; the projection is over unpacked `Assertion`, not shared glyphs** |

$$\boxed{\textbf{CHARACTERIZED, NOT RATIFIED. My "two rivals" framing was correct as a measurement of the vocabularies and wrong as a description of the objects.}}$$

`[INF]` **This is the largest single movement in my package's history.** It does not close the
blocker — nothing is ratified, and the projection's own author flags the unqualified `=` as a defect
they caught in their own work. But *"the only blocker no derivation can resolve"* is now **false**:
a derivation resolved the relation; ratification of *which side is canonical* remains a governance act.

⚠️ **And the lane's own caution transfers:** an unqualified `=` between the two `K`s is exactly the
error it caught in itself. **My package must never cite this as `(𝒜,ℛ) = K_t`.**

## 2. ⭐⭐⭐ `ℐ` is written — and the reason it cannot close is DEEPER than I said

**My finding** (`readiness/04` §69, `07`): *"`ℐ` is the unrecorded keystone… it has never been
enumerated. `BLOCKED — REQUIRES DERIVATION.` Kernel minimum 15–18, undetermined because of it."*

**The new artifact** — `REFINED-STEP-287-INVARIANTS.md`: **7 candidates · 0 ESTABLISHED** · 2 DERIVED
· 2 CORPUS-SUPPORTED · 2 CONDITIONALLY DERIVED · 1 `G1` OPEN · 3 vacuity risks. With the schema:

$$\mathcal I(P):\ \forall K,o.\ Admissible(o,K)\Rightarrow\big(P(K)\Rightarrow P(\delta(K,o))\big)$$

**And two blockers upstream of every candidate:**

| Blocker | Consequence |
|---|---|
| **`Admissible` is not evaluable** | depends on `Assurance`, which the corpus **REFUTED as definable** ⇒ **the schema's antecedent cannot be decided** |
| **`δ` has no commit case** | `Γ` is derived, not a component of `K`, so `δ` has nowhere to write ⇒ **executed: `K₁ is K₀`** ⇒ **the consequent cannot be evaluated for the central operation** |

$$\boxed{\textbf{SUPERSEDED. } \mathcal I \textbf{ is no longer unrecorded. It cannot close because } Admissible \textbf{ is undecidable and } \delta \textbf{ is a no-op — both upstream of } \mathcal I.}$$

`[INF]` **My diagnosis was right about the keystone and wrong about the depth.** I said the register
was missing; the register now exists and **establishes nothing**, for reasons my package did not
have. This is a better finding than mine and it replaces mine.

⚠️ **Consequence for `07-MINIMUM-IMPLEMENTABLE`:** the 15–18 bound was *"undetermined because `ℐ` was
never enumerated."* That premise is gone. The bound is now undetermined because **0 of 7 candidates
is established**, which is a different and worse position.

## 3. ⭐⭐ `Σ`: my model was IMPLEMENTED — confirmed on contradiction, refuted on adequacy

**My finding** (`step-272/12`, `theory-compatibility/03`):
`Σ ≅ 𝒫({Support,Refute}) ≅ {0,1}²`, derived not stored; `Σ ≅ supp(m)` on `2^{\{φ,¬φ\}}`;
*"two bits are needed."*

**The new artifact** — `KR-CONTR-FDE-2026-09`, executed at `research/knowledgeos-sim/kos12/fde/`,
verdict at `results/fde/verdict.md`. **13 required distinctions, four representations:**

| representation | preserved | collapsed | not-representable | adequate |
|---|---:|---:|---:|:--:|
| Classical `{P,¬P}` | 1 | 1 | **11** | no |
| K3 `{T,F,U}` | 3 | **10** | 0 | no |
| **FDE `(S⁺,S⁻)`** — *my `Σ`* | **11** | **2** | 0 | **no** |
| `Standing × Boundary × Context × Provenance` | **13** | 0 | 0 | **YES** |

**FDE `(S⁺,S⁻)` is my `𝒫({Support,Refute})` — the same lattice, independently implemented.**

✅ **Confirmed, decisively, on what I claimed it for:** *"K3 collapses all five
`DirectContradiction | X` pairs. FDE preserves every one of them… representing positive and negative
support independently solves the contradiction-vs-unknown problem that a third value cannot."*
**That is my `Unknown ≠ Refuted` argument, executed and upheld against the K3 alternative.**

🔴 **Refuted on adequacy — two collapses:**

```
NoEvidence            ≡  Absent
InsufficientEvidence  ≡  Underdetermined
```

*"Both are boundary distinctions. Neither involves contradiction. `Standing` has no channel for **why**."*

⚠️ **And a correction that hits my package's framing directly.** I treated `(0,0)`/`Unknown` as the
locus of the missingness ambiguity. **Measured, it is not:**

| `Standing` | scenarios it covers |
|---|---|
| **`(1,0)`** | `PositiveEvidence` · **`NotAssessed`** · **`Underdetermined`** · `InsufficientEvidence` · **`TheoryIncomplete`** |
| `(0,1)` | `NegativeEvidence` · `SupersededEvidence` |
| `(1,1)` | the seven contradiction-family scenarios |
| `(0,0)` | `NoEvidence` · `Unobservable` · `ScopeExclusion` · `Absent` |

$$\boxed{\textbf{A positive } Standing \textbf{ is just as boundary-ambiguous as an empty one.}}$$

$$\boxed{\textbf{EXECUTED. } \Sigma \textbf{'s two channels are necessary and measured NOT sufficient. The missing channel is } \textit{reason}\textbf{, and it is needed at every } Standing \textbf{ class, not only at } (0,0).}$$

## 4. ⭐⭐ The operation-necessity test: a test was run — but not the one `G-67` names

**My finding** (`step-272`, GN-84): the criterion
`o primitive ⟺ ∃r ∈ R_mandatory : r ∉ Closure(𝒯_{-o})` **has never been run**; minimal registry
exists, **NOT unique (six)**, not selectable, not ratified. And `AF-F-33` (my 14-forced/18-upper
bound) was registered `PROPOSED` and **withdrawn**.

**The new artifact** — `KR-2026-09-01`, `kernel-reduction/`, exhaustive search over **all 16 384
subsets** of a 14-operator set under a declared 25-capability model:

| Result | |
|---|---|
| `C0` (13 operators) **fails its own baseline** | 21/25 capabilities, 9/16 scenarios |
| the missing power | **`Qualify : Observation × Policy ⇀ Evidence`** — *"which the corpus already carried as `G1`"* |
| minimal kernels | **exactly two, both cardinality 13**, differing only in packaging |
| powers vs operators | **14 powers irreducible · only 12 of 14 operators** |
| `DetectGap` | **derivable in 12/12 admissible representations**, no smuggling |
| under every audit reduction granted | `\|K_min\|` falls **13 → 8**, four minimal kernels, six-operator core |
| semantic-smuggling control | 7/7 absorption probes **REJECTED** |

🔴 **But the headline is self-superseded** (`19-directive-adoption-and-research-restructure.md`):

$$\boxed{\textbf{Reachability} \neq \textbf{Epistemic adequacy}}$$

*"The experiment falsified the assumption that kernel minimality can be determined independently of
semantic representation."* The programme sequence becomes
**Representation → Capability → Reachability → Minimality**, and the kernel question moves from
**first to seventh** in a seven-level hierarchy.

$$\boxed{\textbf{PARTIALLY MOVED. An ablation ran, and it measured a different quantity than } G\text{-}67 \textbf{ asks for. } R_{mandatory} \textbf{ was never the input — a 25-capability model was.}}$$

✅ **`AF-F-33` stays withdrawn** and is now superseded by measured numbers from a declared model.
**`13` and `8` are not upgrades of my `14/18`** — they answer *"minimal under this capability model
and algebra"*, not *"forced by `R_mandatory`."* **The two questions are still distinct and my
withdrawal was correct.**

## 5. `Zero`: REFUTED as a state and as a missingness representation

**My finding** (`theory-compatibility/04`): *"`Zero` is a lattice antichain, not a measure"*;
Shannon's partition lattice survives, entropy does not.

**The new artifacts:**
- `theory-v1.2-simulation`: of `Zero`'s **seven** candidate readings, **state** and **missingness
  representation** are **REFUTED** — *"`Zero` is coarser than the four-way unknown taxonomy."*
  Predicate, derived view, boundary (three-valued only) and metaphor survive.
- `kernel-reduction` §2: `Gap := difference-decision(norm-comparison(K_t, I_t))`, **derivable 12/12**
  ⇒ *"`Zero(K_t, I_Q, EC)` behaves as a **state predicate over a normative comparison**, not a kernel
  primitive."*
- Three-valued `Sat` makes `Zero ⟺ Δ_t = ∅` **name three predicates that disagree on the best case**:
  a determined, corroborated state is `strict=false`, `weak=true`, Kleene `=U`.

$$\boxed{\textbf{SHARPENED, and my measure-theoretic negative is corroborated by a second route.}}$$

✅ **Compatible with mine and stronger:** *not a measure* (me) and *not a state, not a primitive, a
predicate whose three readings disagree* (them). **The reading I relied on — a structure over the
gap, not a quantity — survives; the reading I did not test (`Zero` as a state) is refuted.**

## 6. Probability: `G-22` SHARPENED, and my one-verdict framing was too flat

**My finding** (`theory-compatibility/03`, `06`): `(Ω,𝓕,P)` as a triple = **0 occurrences in both
lanes**; *"`P` was declined by T-3 — a choice, not a gap."*

**The new artifacts** (`36`, `37`, `17`): the record carries **three distinct prior verdicts, and
they are not the same verdict**:

| verdict | |
|---|---|
| `(Ω,ℱ)` measurable space | ⚠️ challenged 2026-08-25 — **but *"can be REPAIRED without abandoning your core idea"*** |
| *"projection = measure"* | 🔴 **REFUTED as a primitive** |
| *"Knowledge Space is inherently ONE probability space"* | 🔴 **DECLINED — `§18`: one probability space per REGIME** |

And the lane's own **correction of itself**, which I must adopt: *"a random variable presupposes a
measurable codomain, hence a metric or at least an ordering"* is **FALSE** — `X:(Ω,ℱ)→(S,𝒮)` needs
`(S,𝒮)` merely measurable.

$$\boxed{\textbf{TYPING needs a measurable space — AVAILABLE.}\qquad\textbf{The DYNAMIC matches need a metric — MISSING.}}$$

$$\boxed{\textbf{SHARPENED. } G\text{-}22 \textbf{ moves from "no probability space" to "a candidate space blocked on one typed question."}}$$

⚠️ **My sentence *"`P` was declined by T-3"* is too flat and is corrected in `03`.** What was
declined is a **single universal `ℙ`**; what was refuted is **projection = measure**; what was
*challenged and called repairable* is `(Ω,ℱ)`. **Three verdicts, one of them an invitation.**

## 7. `Determination` now has a position and a signature — and neither is a ratification

| artifact | what it supplies |
|---|---|
| the layered model (`38`) | a **position**: `K_t → Zero → Determination → Decision → Authorization → Action` |
| `SPEC-DET-2026-v1` | a **signature**: `Det(E,τ) → ⟨d_state, d_action, d_confidence, d_prov_ref⟩`, `d_action ∈ {EXECUTE, DEFER, ESCALATE, HALT}`, with `Det ≠ Truth ≠ Knowledge ≠ Belief` boxed |

`[CORPUS]` **`SPEC-DET-2026-v1` self-declares `Status: [RATIFIED]`.** See `06` — **no corresponding
governance act exists.** ⇒ **`Determination`'s status in `readiness/01` is NOT upgraded.**

$$\boxed{\textbf{Position: MOVED. Signature: PROPOSED. Status: unchanged.}}$$

## 8. `History ≠ State`, formally — and it is KnowledgeOS-derived

`KR-HISTORY-2026-09-02` ran `K_t,K_{t+1}` identical ∧ `History_1 ≠ History_2`: **no function of
`K_t` separates them**; the kernel **writes history and never reads it** (0 reads, statically
verified); `ClosureEvent ∈ 𝒦` **REFUTED**. Step 292 then corroborated it from Reiter — and
**correctly refused to invert the provenance**: *"KnowledgeOS-DERIVED · CORROBORATED BY REITER. Not
Reiter-derived."*

`[INF]` **New to my package, and it bears on my `Revision` finding.** I mapped
`EventAddition + StateReDerivation` to a **JTMS**. A JTMS keeps justifications *in* the state and
reads them. **This kernel writes history and never reads it** — so the JTMS correspondence is weaker
than I stated: the *structure* matches, the *consultation* does not. **Recorded, not repaired.**

## 9. Smaller movements

| my item | movement |
|---|---|
| *"four equality relations (Step 246)"* | **STALE — nine registers, five names for one structural relation, `≅`/`≡` overloaded.** I carried the same stale count the other lane caught elsewhere. |
| `Σ` five-dimensional `(A,S,R,V,C)` | **my lane's refutation is cited and upheld** — *"no negative pole, cannot express refutation"* — and a repair is proposed: **compose with `Σ₀`**. My `Σ₀` and their 5-axis `Σ` are different objects. |
| non-monotonicity | **eighth independent arrival**, now with a new form: *monotonicity is a per-**operator** declaration, not a property of `𝕂`* — which converges with `261.19`'s *"no single equality relation is adequate for all operations."* |
| missingness `M1`/`M3` | a **candidate carrier** exists (`𝒟_t` as a varying set) — *structure* present, **population rule BLOCKED** via `258.21`'s quantification over `𝒯`; `M2` needs a third value; `M7` orphan structural. **`A6`/`G-60` NOT closed.** |
| `Reject` repair | **untouched.** |
