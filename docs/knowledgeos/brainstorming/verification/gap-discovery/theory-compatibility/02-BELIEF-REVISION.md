# 02 — Belief Revision (AGM, KM update, base revision)

---

## 1. The external theory `[EXT]`

**AGM.** A belief set `K = Cn(K)` — **deductively closed** — over a language `L`. Three operators:
expansion `K+φ`, revision `K*φ`, contraction `K÷φ`. The eight revision postulates include:

| | Postulate |
|---|---|
| **K*1 Closure** | `K*φ = Cn(K*φ)` |
| **K*2 Success** | **`φ ∈ K*φ`** |
| **K*3/4 Inclusion/Vacuity** | `K*φ ⊆ K+φ`; equality if `¬φ ∉ K` |
| **K*5 Consistency** | `K*φ` consistent unless `⊨ ¬φ` |
| **K*6 Extensionality** | `⊨ φ↔ψ ⟹ K*φ = K*ψ` |

Levi: `K*φ = (K÷¬φ)+φ`. Harper: `K÷φ = K ∩ K*¬φ`. Representation: revision functions correspond to
**epistemic entrenchment** orderings, or to systems of spheres (Grove).

**KM update `[EXT]`.** Katsuno–Mendelzon separate **revision** (new information about a *static*
world) from **update** (the world *changed*). Different postulates (U1–U8); update is performed
**modelwise**, revision globally. `K ⋄ φ` ≠ `K * φ` in general.

**Base revision `[EXT]`.** Hansson: operate on a **belief base** `B` (not closed) rather than `K`.
Base contraction is not equivalent to AGM contraction on `Cn(B)`.

**Non-prioritized revision `[EXT]`.** Drops **Success**: the input may be *rejected*. Variants —
semi-revision, screened revision, **credibility-limited revision** `K ⊛ φ = K*φ if φ ∈ C, else K`,
for a credibility set `C`.

**JTMS `[EXT]`.** Doyle's justification-based truth maintenance: beliefs carry justifications; the
justification structure is monotone, the derived belief set is not.

---

## 2. Correspondences

| KnowledgeOS | Belief-revision counterpart | Kind |
|---|---|---|
| **`𝒜`** | a **belief base `B`**, not a belief set `K` | **exact at the carrier** — §3.1 |
| **Revision::Correction, Refutation** | **AGM revision `*`** | **exact in role** |
| **Revision::Retraction** | **AGM contraction `÷`** | **exact in role** — the source withdraws support |
| **Revision::Evolution** | **KM update `⋄`** | **exact** — §2.1 |
| **Revision::Reinterpretation, ModelRevision** | *(none)* | **ABSENT** — §3.3 |
| **Governed admission** | **credibility-limited revision** | **exact** — §2.2 |
| **`Revision = EventAddition + StateReDerivation`** | **JTMS** | **exact in structure** — §2.3 |
| **`Σ`** | entrenchment degree | **REFUTED** — §3.4 |
| **Ideal State** | *(none)* | ABSENT — AGM has no goal state |
| **Zero** | *(none)* | ABSENT — AGM has no requirement set |
| **Decision / Action / Proposal** | *(none)* | ABSENT — AGM is belief-only |

### 2.1 `Evolution` is KM update — exact `[INF]`

`[CORPUS]` `RevisionType::Evolution` = *"the system really changed from 3.69 to 3.70"*, and §25M.2 is
titled **"Evolution is not an error."**
`[EXT]` That is precisely KM's motivating case: the world changed, so the old belief was *correct
then*.

$$\boxed{\text{Evolution} \leftrightarrow \text{KM update} \qquad \text{Correction/Refutation} \leftrightarrow \text{AGM revision}}$$

`[INF]` **This is a genuine result and the corpus does not know it.** `AGM` appears **5 times** in 564
corpus files and the KM distinction appears nowhere. The corpus **re-derived, from a version-number
example, a distinction the belief-revision literature has held since 1991** — and it went further,
splitting six ways where KM split two.

### 2.2 Governed admission is credibility-limited revision — exact `[INF]`

`[CORPUS]` Closure-03: admission is governed; `LLM proposes; Lord evaluates; Governance authorizes`.
A proposal **may be rejected**.
`[EXT]` **Success (`φ ∈ K*φ`) fails.** The operator is `K ⊛ φ = K*φ` if `φ ∈ C`, else `K`.

`[INF]` **The credibility set `C` is exactly `Policy × Authority`.** The correspondence is
structure-preserving: `C` gates input acceptance; Policy+Authority gate admission; both leave the
prior state unchanged on rejection.

### 2.3 `EventAddition + StateReDerivation` is a JTMS — exact `[INF]`

`[CORPUS]` §25M.9: `H′ = H ∪ {E_revision}`, then `K′ = Derive(H′, Ω, EC, M)`; boxed
`Revision = EventAddition + StateReDerivation`.
`[CORPUS]` Fagin extraction: *"Knowledge history is monotonic in preservation; knowledge state is not
necessarily monotonic in projection."*
`[EXT]` That is the JTMS invariant verbatim: the justification store grows monotonically; the
derived belief set is non-monotone.

$$\boxed{H \text{ monotone} \;\wedge\; K'=\mathrm{Derive}(H') \text{ non-monotone} \;\equiv\; \text{JTMS}}$$

---

## 3. Incompatibilities

### 3.1 `𝒜` is a base, not a belief set `[INF]`, structural

`[EXT]` AGM's carrier is `K = Cn(K)`; every postulate quantifies over a closed set.
`[CORPUS]` Measured: **`Cn(·)` = 0 occurrences in both lanes.** `𝒜` is a finite set of asserted items
with identity, provenance and evidence links.

`[INF]` **AGM does not apply to `𝒜` as stated.** Hansson's base revision does. This is not a small
distinction: base contraction and AGM contraction on `Cn(B)` are provably inequivalent, so importing
AGM results would import theorems that are false of `𝒜`.

### 3.2 Success fails, so six AGM postulates are unavailable `[INF]`

`[INF]` With **Success** dropped (§2.2), **K\*2 fails outright**, and **K\*3, K\*4, K\*5** must be
restated conditionally on `φ ∈ C`. `[EXT]` Non-prioritized revision has its own weaker postulate set.
**The AGM postulate set is not a compatibility target for KnowledgeOS.**

### 3.3 Reinterpretation and ModelRevision have no counterpart at all `[INF]`, decisive

`[EXT]` **AGM holds the language `L` and its interpretation fixed.** Every operator maps
`L`-sentences to `L`-sentences. There is no AGM operator for changing what the sentences *mean*.

`[CORPUS]` But:
- **Reinterpretation** — *"the original observation was valid, but we interpreted it incorrectly"*
- **ModelRevision** — *"reasonable under the old model, but the model itself was subsequently changed"*

$$\boxed{\text{Two of the corpus's six revision types are changes to the interpretation function, which AGM cannot express.}}$$

`[INF]` These are not exotic. `Reinterpretation` is a change in `Evidence(O,P,C,R)`'s rule `R`;
`ModelRevision` is a change in `M` in `Derive(H,Ω,EC,M)`. **Both are first-class in the corpus and
outside the AGM framework entirely.** This is the largest single gap found in this document, and it
runs *against* the external theory rather than against KnowledgeOS.

### 3.4 Entrenchment is a total preorder; `Σ` is a two-bit product `[INF]`

`[EXT]` Entrenchment `≤` is **total**, transitive, and satisfies dominance and minimality — a linear
ordering of sentences by resistance to giving up.
`[CORPUS]` `Σ ≅ {0,1}²` is a **product**, not an order. `Supported (1,0)` and `Refuted (0,1)` are
**incomparable** — neither is "more entrenched".

`[INF]` `Σ` cannot be an entrenchment ordering. `[PROP]` *If* one wanted an ordering, the natural one
is the **lattice** `(0,0) ≤ {(1,0),(0,1)} ≤ (1,1)` under `⊆` on the support set — but that orders
*informativeness*, not *resistance to retraction*, and the corpus asserts no such order. **Offered
only to state DEL-adjacent hypothesis BR-H4; not proposed for adoption.**

### 3.5 Single-agent, single-state `[INF]`

`[EXT]` AGM models one agent's belief set at one time. `[CORPUS]` KnowledgeOS carries provenance,
multiple sources, authority and an inquiry register. `[INF]` No AGM operator reads a source
identifier. **`Retraction` — "the source withdraws its support" — is not expressible in AGM**, because
AGM beliefs have no sources. Base revision with *justifications* (JTMS, §2.3) is required.

---

## 4. Missing primitives

### On the belief-revision side

| Missing | Needed for |
|---|---|
| **interpretation change** | Reinterpretation, ModelRevision (§3.3) |
| **sources / provenance on beliefs** | Retraction (§3.5) |
| **an authority predicate** | governed admission |
| **a requirement set** | `Zero` |
| **a goal state** | `Ideal State` |
| **paraconsistent tolerance** | `Conflict` — `[EXT]` K\*5 Consistency forbids it |

### On the KnowledgeOS side

| Missing | Note |
|---|---|
| **a consequence operator `Cn`** | 0 occurrences; without it "belief set" is undefined |
| **an entrenchment / plausibility order** | nothing orders assertions by retractability |
| **a contraction operator** | `Retraction` is named as a `RevisionType`, **not defined as an operator**; and `𝒪_core` membership is unratified |

---

## 5. Falsifiable hypotheses

| # | Hypothesis | Falsifier |
|---|---|---|
| **BR-H1** | `Evolution ↔ KM update` and `Correction/Refutation ↔ AGM revision` is exact in role | exhibit a corpus `Evolution` case satisfying AGM's revision postulates but violating a KM postulate, or conversely |
| **BR-H2** | Governed admission satisfies the **credibility-limited** postulates with `C = Policy × Authority` | exhibit an admission that violates one — e.g. a rejected proposal that nonetheless changes `𝒜` |
| **BR-H3** | `Revision = EventAddition + StateReDerivation` satisfies the JTMS invariant: `H` monotone, `Derive(H)` non-monotone | exhibit a `RevisionType` that **removes** an event from `H` |
| **BR-H4** | `Σ` admits **no** total entrenchment order consistent with the corpus's operations | exhibit one that ranks `(1,0)` against `(0,1)` and survives `05`'s Roberts admissibility test |
| **BR-H5** | Reinterpretation and ModelRevision are **not** definable as any composition of AGM/KM operators over a fixed `L` | give such a composition |
| **BR-H6** | Because `Cn` is absent, **no AGM theorem transfers to `𝒜` unmodified** | show an AGM theorem that holds of `𝒜` for a non-trivial reason |

---

## 6. Verdict for this theory

$$\boxed{\textbf{THE CLOSEST OF THE FIVE — and the corpus is ahead of the framework on one axis}}$$

- **Four exact correspondences**, more than any other theory here: base revision, credibility-limited
  revision, KM update, JTMS.
- **The carrier matches** once one moves from AGM to **base** revision — which the literature already
  provides.
- **Two of six revision types (Reinterpretation, ModelRevision) exceed the framework.** AGM fixes the
  language; KnowledgeOS revises the interpretation. **`[INF]` On this axis KnowledgeOS is not
  under-formalized — it is formalizing something AGM chose to hold constant.**
- **`Σ` is not entrenchment**, and `Conflict` violates K\*5 Consistency.

`[CORPUS]` Consistent with the Gärdenfors extraction's own conclusion — *"epistemic states and their
dynamics as the primary modelling problem"*, `Kernel = preservation substrate`. **`[INF]` The
substrate the corpus describes is a justification store, and that is a JTMS.**
