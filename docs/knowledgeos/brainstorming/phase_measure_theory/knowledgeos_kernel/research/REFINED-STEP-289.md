---
artifact: REFINED STEP 289 · DEPENDENCY GRAPH, BOOTSTRAP BOUNDARY, DERIVATION/GOVERNANCE SEPARATION
mandate: `prompts/20260831-224400_step_289_reviewer-b-dependency-graph-bootstrap-boundary-and-derivation-governance-separation-mandate.md`
package: `step-289/01`…`step-289/10` · `step-289/exec/t289_bootstrap.py` + `_v2`
consumes: `285` · `286` · `287` · **`288`** · `261 §261.23` · `POLICY-EQUALITY-AND-COMPOSITION` · `060`
date: 2026-08-31
verdict: **the minimal bootstrap cut is `{≡}` — and Step 288's `𝒪`/`𝒯` bootstrap claim is REFUTED**
---

# Refined Step 289 — Dependency and Bootstrap Boundary

> **The mandate's §1 said: *"Do NOT assume this conclusion is correct merely because Step 288 reported
> it. Reconstruct and audit it independently."* It was reconstructed independently, and it did not
> survive.**

## 0. LOCKED FINDINGS (reviewer acceptance, 2026-08-31 23:01) · and two corrections from Step 290

**Accepted as the current baseline. Seven items locked:**

| # | Locked |
|---|---|
| 1 | **288's bootstrap claim is refuted** — `{𝒪,𝒯}` does not break the cycles; `{≡}` is the unique minimal cut of size 1; **prerequisite ≠ cycle member** |
| 2 | **Two boundaries exist and must not be conflated** — `{≡}` breaks the cycles; `{𝒪,𝒯}` unblocks the eight derivable prerequisites |
| 3 | **The declaration/derivation fork is fundamental** — declaration breaks the cycle; derivation re-enters equality via `𝒯 → congruence → ≡` |
| 4 | **30, not 32** — `≈_∅` universal, `≈_{full}` discrete. **This replaces all "32 usable choices" language** |
| 5 | **The `K`-order correction** — see the amendment below |
| 6 | **`261.23` is two blocks** — equality (1,2,3,6) and representation (4,5); resolving equality does **not** release the representation block |
| 7 | **The five withheld items stay withheld** — they must not become governance questions merely because they are currently important |

> ### ⚠️ AMENDMENT to lock item 5 — my verdict was too strong (Step 290 `09` C-7)
> §4 below records `(𝕂, merge, ∅)` as a join-semilattice **REFUTED** for `𝕂`, on two grounds. **The
> second does not support that word.**
> - `060 §60.71` says *"**Not proven** … we cannot yet assert"* → **NOT ESTABLISHED**, not refuted.
> - **Retraction refutes the *gloss*** — *"adding knowledge only grows the state"* is a claim about
>   **state evolution**, and withdrawal shrinks. ✅ **That stays REFUTED.**
> - But **`merge` may be monotone even where `retract` is not** — retraction is a *different operation*,
>   so it does not touch the algebraic claim about `merge`.
>
> $$\boxed{\begin{array}{ll}(\mathbb K, merge, \emptyset) \text{ join-semilattice} & \textbf{NOT ESTABLISHED} \text{ — proven only for } PureClaimSetUnion\\ \text{"adding knowledge only grows the state"} & \textbf{REFUTED} \text{ — retraction shrinks}\end{array}}$$
>
> **Two claims were conflated and the stronger word applied to both.** The reviewer's *"neither should it
> be upgraded to 'K is a lattice'"* cuts both ways: **neither should it be downgraded to REFUTED when the
> corpus says NOT PROVEN.** ✅ **The five verification-lane artifacts remain SUPERSEDED** — scope restored
> to `PureClaimSetUnion`.

> ### ⚠️ AMENDMENT to §22 answer 9 — the earliest act has changed
> §22 said *"a governance act on `N-1`"*. **Step 290 PERFORMED `N-1` and returned `N-1A — DISTINCT`.**
> The `≡`/`≈` conflict this step reported is **withdrawn**; `G-67` is withdrawn; the replacement `N-1′`
> is **non-critical and downstream of `N-4`**.
> $$\boxed{\textbf{The earliest legitimate next act is } N\text{-}4\ (\mathcal O/\mathcal T).}$$
> 🔴 **DOWNGRADED by Step 291 §10.** **MEASURED: seven independent source nodes** (`𝒪`, `Qualify`,
> `assertion`, `evidence`, `id_context`, `policy`, `temporal`), all resolvable, none blocking another.
> **`𝒪` reaches 20 nodes, `id_context` 17, `policy` 16.** And two of the five meanings of *"earlier"*
> disagree — `N-4` is a prerequisite for **implementation** but **not** for **canonicalization**, which
> needs the separate `evidence` root (`38.85`). **Corrected claim: `N-4` is the highest-LEVERAGE
> independent root, not *the* earliest act. The corpus selects no unique next act.**
> **And Z-1 weakens:** its edge `≈ → ≡` came from `261.21`'s *candidate*, which I mis-modelled as a
> definitional dependency. **`{≡}` remains the minimal cut, now carried by Z-2 and Z-4 — both classed
> NECESSARY and both surviving all three deletion tests. The justification narrows from four cycles to
> three, and strengthens.**

## 1. The headline

$$\boxed{\begin{array}{c}\textbf{4 cycles. All four contain } \equiv. \textbf{ NOT ONE contains } \mathcal O \textbf{ or } \mathcal T.\\ \textbf{Minimal feedback vertex set} = \{\equiv\},\ \textbf{size 1, unique.}\end{array}}$$

**EXECUTED** (`step-289/exec/OUT-t289_bootstrap_v2.txt`), exhaustive over all 12 decision-resolvable
nodes, sizes 1–4:

```
cut ['O']            -> acyclic? False
cut ['T']            -> acyclic? False
cut ['O', 'T']       -> acyclic? False     <-- Step 288's claimed bootstrap does NOT cut
cut ['equiv']        -> acyclic? True      <-- unique minimal cut, size 1
cut ['delta']        -> acyclic? False
```

### Step 288's claim, classified against the mandate's §5 scale

> *"Three dependency cycles, all through one node; the bootstrap boundary is `𝒪`/`𝒯` ratification."*

$$\boxed{\textbf{F — NOT ESTABLISHED; and as stated, REFUTED.}}$$

**Both halves fail.** There are **four** cycles, not three; and `𝒪`/`𝒯` **are not members of any of
them**. They are **upstream sources** that gate derivation, not participants in the feedback structure.

> ⚠️ **How the error happened, because the mechanism generalizes:** Step 288 traced each cycle to a node
> it *depended on*, and concluded that node was *in* the cycle. **Being a prerequisite of a cycle is not
> being a member of it.** That is the mandate's own §16 distinction — *"a dependency is not necessarily
> a blocker"* — applied one level up, and I had not applied it to my own finding.

### ⭐ And the corpus was right all along
`261.23`: *"final kernel selection must stop while **equality** remains ambiguous."*
**The corpus located the block at equality. Step 288's gloss moved it to `𝒪`/`𝒯`; the computed graph
moves it back.** ✅ **Independent confirmation of `261.23`'s wording by a method `261` did not use.**

## 2. Two boundaries, not one (§15)

| | Node(s) | Function |
|---|---|---|
| **ACYCLICITY boundary** | **`{≡}`** | breaks all feedback loops ⚠️ **Step 291: 3 cycles, not 4** |
| **DERIVATION boundary** | **`{𝒪, 𝒯}`** | unblocks the 8 derivable prerequisites (`≈`, congruence, minimality, bindings, canonicalization, merge, invariants, sufficiency) |

> ⚠️ **Step 291 §4: `𝒪`, `𝒯` and `𝒪_K` are THREE DISTINCT OBJECTS, not one.** `259.7` gives a
> **five-kind** operation classification and states *"not every operation has the form `T : K → K`"*;
> **`𝒯 ⊆ 𝒪` is kind 1**, and **`𝒪_K` relates to kind 5**. This step collapsed them into a single
> `{𝒪,𝒯}` cut candidate. **MEASURED with them separated: `cut {𝒪,𝒯,𝒪_K}` is still cyclic, and each is
> in 0 of 3 cycles — the two-boundary finding STANDS and is sharpened.**

$$\boxed{\text{Cutting the cycles} \neq \text{unblocking the derivation.}}$$

**Minimum for acyclicity: `{≡}`. Minimum for progress: `{≡, 𝒪, 𝒯}`.**

⚠️ **A dependency worth stating precisely:** `{≡}` is a cut **only under the *declaration* route.**
`≡` can be resolved two ways — **by declaration** (a governance act; breaks the cycles immediately and
does **not** need `𝒪`) or **by derivation** (select `≡` by requiring congruence, `258.9`; needs `𝒯` and
`δ` first). **Under the derivation route the cycle reasserts itself through `𝒯 → congruence → ≡`.**

> **That is the exact sense in which the system is underdetermined: the formal material cannot select
> `≡`, and an authority can. No recommendation between the two routes is made.**

## 3. Is `𝒪`/`𝒯` derivable? (§5)

**Six candidate routes tested, all fail** (`step-289/03`): from `K` (circular) · from invariants
(downstream of `≡`) · from a minimality proof (`259`: forbidden before congruence) · from the ratified
8 primitives (they are **primitives, not operations**, and have never been shown minimal against an
operation space) · from the forced lower bound (`256.2`/`259.7` give a **candidate set**, 14 forced
≤18 — **not a membership rule**) · **declaration** (the only route with no unmet precondition).

⚠️ **This is an ABSENCE OF EVIDENCE for an incoming edge, not a proof that none exists.** `224.11`
discipline: **absence of evidence stays `Unknown`.** A later step may find a derivation; this finding
would then be **SUPERSEDED**, not contradicted.

## 4. ⭐⭐ A live overclaim found by the §12 order audit

**Corpus source — `step-060`, *Epistemic Algebra and Knowledge State Ordering*:**

| §60.39–41 | semilattice laws **PASS** — ⚠️ **for `PureClaimSetUnion`** |
|---|---|
| **§60.42** | *"KnowledgeOS does more than set union… **therefore the real merge operator may not satisfy the semilattice laws**"* |
| **§60.71** | *"Does knowledge form a lattice? **Not proven.** … **we cannot yet assert: KnowledgeOS is a semilattice**"* |

**Five verification-lane artifacts assert it anyway** — `KNOWLEDGE-STATE-ALGEBRA:89`,
`THEORY-CLOSURE-AUDIT:149`, `KNOWLEDGE-STATE-FINAL-AUDIT:29`, `step_282:219`, and
**`POLICY-EQUALITY-AND-COMPOSITION §3`: *"`(𝕂, merge, ∅)` is a join-semilattice — adding knowledge only
grows the state."***

**A join-semilattice induces a partial order** (`K_A ⊑ K_B ⟺ K_A ⊔ K_B = K_B`), so:
$$\boxed{\text{Asserting } (\mathbb K, merge, \emptyset) \text{ is a join-semilattice IS asserting a } K\textbf{-ORDER — the F5 error class.}}$$

**Three defects:** scope dropped (`PureClaimSetUnion` → `𝕂`) · **verdict inverted** (*"cannot yet
assert"* → asserted) · and in `POLICY-EQUALITY` it appears as an aesthetic aside (*"pleasing duality"*)
whose **own classification table does not list it** — the `(Policy,∧)` meet-semilattice is listed as
**PROVEN (executed)**; the `𝕂` claim is **unclassified and unexecuted**.

**And retraction refutes it directly:** monotone growth requires that adding knowledge never shrinks the
state. The corpus has withdrawal and retraction (Article 8, `I-12`), and **EXECUTED** — withdrawal
re-keys the assertion id and dangles every `ℛ`-edge. $\boxed{\text{Retraction shrinks. Monotone growth is REFUTED for } \mathbb K.}$

⚠️ **This corrects Step 288 in the other direction too:** 288 said *"`K` has no candidate ordering
structure at all."* **There is one — but only over `PureClaimSetUnion`, and the corpus itself declines
to lift it to `𝕂`.**

## 5. `Σ → K` (§11)

An element of `Σ` is a 5-tuple of **labels** (`7×5×4×4×4` = 2240, independently verified). An element of
`K` is **not settled** — three tuple candidates exist and **`258.35` says `K` should be defined
extensionally, not as a tuple at all.**

$$\boxed{\Sigma \text{ is a COMPONENT of } K \text{ under the tuple readings. There is no } \pi: K \rightarrow \Sigma.}$$

**So `Σ_1 ⪯ Σ_2 ⇒ K_1 ⪯ K_2` is not merely unproven — it is not type-correct.** Of the mandate's seven
candidate relations, the only defensible reading of `≈_X` is **interpretation**; a `Σ`-order → `K`-order
inference rests on **analogy**. **`Σ → K` is the FAIL, confirmed.**

## 6. Degeneracy audit (§13) — 3 of 14 results downgraded

**Rule, promoted:** $\boxed{\text{No algebraic property may be accepted from a zero-counterexample result without a degeneracy check.}}$
**Companion measurement, now mandatory:** every relation reported as satisfying the equivalence axioms
must also report **its block count**.

| Downgraded | To |
|---|---|
| `≈_∅` | **the universal relation** — 1 block; axiom-satisfying, information-free; **not a candidate** |
| `≈_{full}` | **the discrete relation** — 2240 blocks; identical to `=` on `Σ`; **not an independent candidate** |
| `≈_S ∘ ≈_V` | **universal** — a vacuous pass (already downgraded at discovery) |

> ⭐ **Consequence for the headline number:** *"exactly 32 distinct `≈_X`"* stands as a count of
> partitions. **As a count of usable candidate observational relations it is 30.** **`N-5`'s choice
> space is 30, not 32.**
>
> ⚠️ **Third narrowing of a count after measurement in this programme** (`47 tests → 4`;
> `at most 32 → exactly 32`; `32 → 30 usable`). **Not arithmetic error — reporting a computed
> cardinality without asking what the extremes mean.**

**6 of 14 results are immune by construction:** a *found counterexample* cannot be vacuous.
⚠️ **`I_48` cannot be degeneracy-checked at all** — no context is defined, so no partition can be
computed. **That confirms BOUNDED, not established.**

## 7. Policy equality does not transfer (§10)

$$\boxed{(Policy, \wedge) \text{ being a meet-semilattice establishes NOTHING about } \mathbb K.}$$
All four policy notions fail to transfer: identifier (`K_t` has no identity rule) · structural
(canonicalization unbound) · extensional (**undecidable in general even for Policy**) · semantic
(*"NOT COMPUTABLE"* even for Policy).

✅ **Two Policy findings generalize as *methodology*, not algebra:** *"policy equality ≠ equal output"*
and *"version must be part of identity"* — both independently reproduced for equality at large.

## 8. §21 Philosophical independence

**The core dependency analysis was run with the Step-286 philosophical material excluded** — every
edge in `step-289/01` cites `012`, `025x`, `038`, `060`, `195`, `246`, `254`, `258`, `259`, `260`,
`261`, `278`, `285`, `38.x`: **research- and verification-lane only.**

**3 of 4 cycles survive deletion of the philosophical material, the verification lane, AND the
historical definitions** (`02`). **The result stands with the philosophical appendix deleted.**
**No philosophical source is cited in support, and none is needed.**

## STATUS

### ESTABLISHED
- **4 cycles; all contain `≡`; none contains `𝒪`/`𝒯`** · **two distinct boundaries** (acyclicity vs derivation)
- no derivation route into `𝒪`/`𝒯` found — 6 routes tested
- `Σ` is a **component** of `K`, not an image; **no `π: K → Σ`**; the order inference is not type-correct
- policy algebra does not transfer to `𝕂`
- the degeneracy rule, and the block-count companion measurement
- `261.23` holds on **three independent grounds**, one of them new
- **8 of 27 nodes are derivable prerequisites** — not every dependency is a blocker

### EMPIRICALLY VERIFIED
minimal cut `{≡}` (exhaustive, sizes 1–4 over 12 nodes) · 4-cycle enumeration · block counts for all 32 projections

### DERIVED
`Z-1` is contingent on the `N-1` conflict; `Z-2`–`Z-4` are necessary · `261.23` is a **conjunction of an
equality block (conds. 1,2,3,6) and a separate representation block (conds. 4,5)** · 5 items must be
**withheld** from the governance surface

### CORROBORATED
`060 §60.71` by an independent route · `261.23`'s wording by a method `261` did not use

### REFUTED
- **Step 288's *"bootstrap boundary is `𝒪`/`𝒯`"*** — `cut {𝒪,𝒯}` is **not** acyclic
- **Step 288's *"three cycles"*** — there are four
- **`(𝕂, merge, ∅)` as a join-semilattice for `𝕂`** — `060.71` + retraction
- **Step 288's *"`K` has no candidate ordering structure at all"*** — one exists over `PureClaimSetUnion`

### SUPERSEDED
the five verification-lane join-semilattice assertions — **scope must be restored to `PureClaimSetUnion`**

### BOUNDED
`N-5` at **30** usable projections (not 32) · 21 normative records, alternatives enumerated · the 13-row closure contract

### NORMATIVE
**21 records** (`step-289/09`), of which **8 are independent**; `N-1` and `N-4` are the roots

### TECHNICALLY OPEN
whether the `≡`/`≈` duplication is documentary or normative — **not determinable from the text** ·
what an element of `K` is (`258.35`) · edges this list may omit

### G1
`≡`'s total Boolean procedure — `012 §35`. **An authority cannot ratify decidability into existence.**

### DEFERRED
every decision · `025v`·`step-017`·`step-039` (the semantic-translation thread) · the `287` identifier

## §22 THE TEN REQUIRED ANSWERS

1. **Derivable now?** Provenance identity · policy composition. **Two.**
2. **Merely bounded?** `≈` at 30 · dedupe at 4 · Decision 3 at 2 · codomain at 4+1 · `I_48`'s scope · 21 decisions.
3. **Technically open?** Every procedure but `=` (conditional) and `≡_D` (wrong level); the quotient; `δ`'s commit case; four bodiless partial functions.
4. **Requires governance?** 21 records — **minus 5 that must be withheld** as derivable or undecidable.
5. **Cycles remaining?** **Four**, all through `≡`.
6. **Minimal bootstrap cut set?** **`{≡}`** for acyclicity; **`{≡, 𝒪, 𝒯}`** for progress. **Computed, not preselected.**
7. **Does `261.23` still stop kernel selection?** **YES**, on three independent grounds.
8. **Has Step 288 reduced the equality blocker?** ✅ **Yes — substantially**, by bounding the spaces and forcing the registers into one table. ⚠️ **And it mislocated the bootstrap**, which this step corrects.
9. **Earliest legitimate next step?** **A governance act on `N-1`** — adjudicate whether `≡` and `≈` are one relation. It is **independent of everything else**, it is a **corpus-integrity repair rather than an architecture choice**, and until it is done, two of six state-level relations cannot both stand as written.
10. **What must NOT be attempted yet?** Selecting `𝒪`/`𝒯`/`≡`/`X`/a kernel · deriving congruence before `≡` and `𝒯` · ratifying anything derivable (congruence, merge laws, the forced `id` repair) · treating `{≡}` as a *recommendation* to declare `≡`.

### The three senses, never conflated
| **research progress** | ✅ substantial — cycles located, cut computed, an overclaim found, three counts narrowed |
|---|---|
| **architectural closure** | 🔴 **none.** 0 of 13 closure-contract rows satisfied |
| **governance ratification** | 🔴 **none.** 21 records open, 0 ratified |

---

$$\boxed{\begin{array}{c}\textbf{Step 289 establishes the dependency and bootstrap boundary of the equality problem;}\\ \textbf{it does not ratify the equality contract, the operation model,}\\ \textbf{the transformation model, or the operational kernel.}\end{array}}$$

**STOP. Step 290 is not begun. No implementation design. No canonical equality. No `𝒪`. No `𝒯`.**

---

### Self-check log

| | Defect | Repair |
|---|---|---|
| **288 §13** | *"three cycles, all through `𝒪`/`𝒯`; the bootstrap boundary is `𝒪`/`𝒯`"* | 🔴 **REFUTED by my own graph** — four cycles, none containing `𝒪`/`𝒯`; the cut is `{≡}`. **Prerequisite of a cycle ≠ member of it** |
| **288 §8** | *"`K` has no candidate ordering structure at all"* | one exists over `PureClaimSetUnion` (`060`) — and the corpus declines to lift it |
| **my own graph v1** | `K → ≡_𝒯` rendered a **definition** as a two-way dependency, producing a phantom 5th cycle | edge removed; **both graphs retained in the transcripts** so the audit is auditable |
| **288 §7 / prior** | *"exactly 32 distinct relations"* as the choice space | **30 usable** — two endpoints degenerate |
| **verification lane ×5** | `(𝕂, merge, ∅)` asserted a join-semilattice | scope restored to `PureClaimSetUnion`; **SUPERSEDED** |

**Five corrections. The first is the largest single reversal in this programme: a headline finding of the
immediately preceding step, refuted by executing the analysis it asserted.** *The lesson is narrow and
worth keeping: **Step 288 reasoned about the cycles in prose and reported a structure; Step 289 computed
it. The prose was wrong.*** Two of the five defects were found only because the mandate required
independent reconstruction rather than inheritance.
