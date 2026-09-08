# `P-16` — Third Axis & Transition Closure Audit

**2026-09-08 · Lane T · closure audit.** After [`32` `P-15`](./32-P15-MODEL-REPLACEMENT-TRANSITION-AND-K2-DERIVATION.md).

> **The objective is ELIMINATION**, and the audit must also attempt to falsify its own elimination.
>
> ⛔ **`P-13`, `P-14`, `P-15`, `Q1`, `W6` not reopened · `K1`–`K5` not decomposed without a complete
> witness · Schema v2 unmodified · v3 not begun · no architecture, event sourcing, temporal storage,
> graph storage or model registry · 3MC / MD-018 untouched · no unified Kernel · no four-model
> convergence · DDD, mathematics and implementation are never ontology · Lane T's own adjudications are
> not corpus evidence · no Lane M conclusion consumed.**

# 1. Starting state
`R0` **`[STIPULATED]`** · `Q1a` **YES `[STIPULATED]`**, `Q1b` **`[DERIVED]`** · **`W6` = `C3`** ·
**`Q2` = SAME CANDIDATE** · **`K2`-A = CLOSED** · **`(all)→(all)` = aggregate description, not a
primitive** · model replacement and semantic reinterpretation create **no** capability · **kernel 11
cells** · kernel identity, equivalence, `Q4`, Claim B, aggregate existence, third axis, role overload,
`O-INT002-1` all `[OPEN]` · `F-4`/`F-5` unrepaired.

# 2. Methodological principle — `[OPEN]` is not a prior

$$\boxed{\mathbf{[OPEN]} \textbf{ means } \textit{"the evidence does not yet close the question"} \textbf{ — NOT } \textit{"there probably is a third axis."}}$$

⛔ **This audit does not begin by seeking supporting evidence.** ⭐ **And per the anti-anchoring rule,
*"no witness found"* is NOT refutation — a refutation requires a REDUCTION ARGUMENT.** §12 and §18
supply one.

# 3. What "third axis" means — defined

`P-09.3` individuated retention capabilities along two axes:

| axis | values |
|---|---|
| **ROLE** | **antecedent** *(the prior thing)* · **link** *(its relation to the present)* |
| **SUBJECT** | **value** *(`K3a`/`K3b`)* · **proposition** *(`K4a-i`/`K4a-ii`)* · **ground** *(`K4b-i`/`K4b-ii`)* · **reference** *(`K4c-i`/`K4c-ii`)* |

plus the three present-state capabilities `K1`, `K2`, `K5`.

$$\boxed{\begin{array}{c}\textbf{A THIRD AXIS exists only if there is an admissible persistence distinction whose LOSS}\\ \textbf{cannot be represented by ANY combination of the established axes.}\end{array}}$$

⛔ **Conceptual difference is not axis difference.** `P-10` §4 named **temporal position** as the
highest-priority candidate — the only one with an admissible transition — so §5 attacks it first.

# 4. The stopping rule, restated for use
> *A new persistence capability is permitted only if an admissible transition/history pair demonstrates
> a loss distinction that the current closed capabilities cannot represent.*

⭐ **Twelve reduction questions** are applied per candidate: transition · history pair · required
distinction · what can be lost · are all 11 cells identical · can `K1`–`K5` represent it · merely a
role · merely a subject · merely provenance · merely representation · merely a mechanism · merely a
different arity.

---

# 5. ⭐⭐⭐ The original witness, constructed formally — and it fails

**`P-10`'s pair:** `K → 𝒦 → X → K` versus `K → X → 𝒦 → K`, same current state.

⭐ **What `K3a` actually retains:** `P-08` §16 — a prior value **and** a warrant, **per non-monotone
transition**. So each transition contributes a record. Represent each as an ordered pair
`(prior → successor)`:

| | transition records |
|---|---|
| **`H1`** | `(K→𝒦)` · `(𝒦→X)` · `(X→K)` |
| **`H2`** | `(K→X)` · `(X→𝒦)` · `(𝒦→K)` |

$$\boxed{\begin{array}{c}\textbf{The record SETS are DIFFERENT. } K3a \textbf{ already distinguishes } H1 \textbf{ from } H2 \textbf{ — with no ordering information at all.}\\ \boxed{\textbf{The order is DERIVABLE: the records form a walk, and the walk is recovered from its edges plus the endpoint.}}\end{array}}$$

⭐⭐ **`P-10` §4 stated that `K3a` "as stated entails no ordering". That is true of a bare list of prior
VALUES and false of a set of prior→successor PAIRS** — and `P-08` §16 defines the record per
*transition*, which fixes both ends. ⚠️ **`P-10` is not rewritten; the correction is recorded here.**

## ⭐ Falsifying the elimination — the Eulerian residue

⭐⭐ **Edge sets determine a walk only when the trail is unique.** The minimal ambiguity needs two
distinct Eulerian trails on the same edge multiset with the same endpoints:

$$H1: K \to \mathcal{K} \to K \to X \to K \qquad H2: K \to X \to K \to \mathcal{K} \to K$$

| | |
|---|---|
| **current state** | ⭐ **identical — `K`** |
| **edge multiset** | ⭐ **identical** — `(K→𝒦)`, `(𝒦→K)`, `(K→X)`, `(X→K)` |
| **warrants** | ⭐ **stipulated identical** |
| **all 11 cells** | ⭐⭐⭐ **IDENTICAL** |
| **the histories** | ⭐ **differ in ORDER** |

⭐ **So condition 3 is satisfied. The whole third-axis question now rests on ONE question: is the order
REQUIRED?** — §12.

---

# 6. Sequence information vs a new capability

`H1: A→B→C` versus `H2: A→C→B`, same final state.

| question | answer |
|---|---|
| are the transitions retained? | ✅ **yes — `K3a`, one record each** |
| are their warrants retained? | ✅ **`K3b`** |
| are successor relations retained? | ✅ **`K4c-ii`** where identity changes |
| ⭐ **is the ordering derivable from the records?** | ⭐ ✅ **YES in the general case** *(§5)*; 🔴 **no in the Eulerian-ambiguous case** |
| **if timestamps are absent, is order actually required?** | ⭐⭐ **§12** |
| **if required, is it a property of an existing record?** | ⭐ **it would be — a field on a `K3a` record, not a new capability** |

$$\boxed{\textbf{sequence information} \neq \textbf{a new semantic dimension}}$$

# 7. Is the third axis merely *"history exists"*?

⭐ **Substantially yes** — every candidate below is a claim that some *chain* must be kept. And
`P-08` §7 already settled that:

> **Five requirement levels, and NONE of them is complete history:** current-only · current+act ·
> current+warrant · current+warrant+prior-value · full retention *(assertions)* · reconstructibility
> *(measurements)*.

⛔ **No generic "history" capability is introduced.** ⭐ **`P-08` rejected full-history preservation as a
universal requirement, and that rejection is load-bearing in §12.**

# 8. Order vs causality

$$\boxed{A \textbf{ precedes } B \quad\neq\quad A \textbf{ causes } B}$$

| if a witness depends on causality | verdict |
|---|---|
| is causal attribution required? | ⚠️ **no witness** |
| ⭐ is it a **warrant**? | ⭐⭐ ✅ **yes — a causal claim is a JUSTIFICATION** *("changed because of the containment argument")* ⇒ **`K3b`** |
| provenance? | overlapping — `K3b`/`K5` |
| a relation between assertions? | ⭐ **`K4a-ii`** |
| merely explanatory metadata? | ⚠️ **absent a required-loss witness, yes** |

⛔ **No causal persistence introduced.** ⭐ **Reduced to `K3b`.**

# 9. Branching / path multiplicity

`A→B`, `A→C`, then both `→D`.

⭐ **This is a split (`1→n`) followed by a merge (`n→1`)**, both borne by **`K4c-i`/`K4c-ii` with
cardinality** since `INTAKE-002`. ⭐⭐ **And the graph is RECONSTRUCTIBLE from the retained edges** —
reachability and transitive closure are derived, never stored.

$$\boxed{\textbf{⛔ No "path" capability. A history forming a graph is not evidence of a graph capability.}}$$

# 10. Merge/split ordering

`A → B,C → D` versus `A → D` **and separately** `A → B,C`.

| | edge sets |
|---|---|
| first | `A→{B,C}` · `{B,C}→D` |
| second | `A→D` · `A→{B,C}` |

⭐ **Different edge sets ⇒ `K4c-ii` distinguishes them.** ⇒ **REDUCED.** ⭐ **Third axis collapses on
this branch.**

# 11. Reinterpretation and correction chains

| | `H1` | `H2` | cells |
|---|---|---|---|
| **reinterpretation** | `m₁ → m₂ → m₃` | `m₁ → m₃` | ⭐⭐ `H1` holds **three** assertions and **two** supersession relations; `H2` holds **two** and **one** |
| **correction** | `C₁ ⊐ C₂ ⊐ C₃` | `C₁ ⊐ C₃` | ⭐ `H1` retains `C₂`; `H2` never had it |

⭐⭐⭐ **`P-08` §5.2: deletion of a superseded assertion is impermissible, so `K4a-i` retains EVERY
assertion ever made.** ⇒ **the cells DIFFER ⇒ condition 3 FAILS ⇒ both branches REDUCED to `K4a`.**

⭐ **And chain ORDER is derivable here without any new machinery:** `supersedes` is itself a relation
between the two texts, so `C₁ ⊐ C₂ ⊐ C₃` **is** its own ordering. ⇒ **assertion-chain depth and order
are borne by `K4a-ii`. Not a third axis.**

# 12. ⭐⭐⭐ The set-wise test — and the reduction that refutes the residue

**The only surviving candidate is §5's Eulerian pair, where all 11 cells are identical.** Condition 3
holds. **Condition 1 is the test.**

| | |
|---|---|
| **the claimed required distinction** | *which of two prior excursions came first* |
| ⭐ **is it among `P-08` §7's five requirement levels?** | ⭐⭐⭐ 🔴 **NO.** Order over **several** prior values is a property of **complete history** — and `P-08` §7 **excluded complete history as a requirement**, deriving instead *current state + warrant + prior value on non-monotone transitions* |
| **is there a corpus witness demanding it?** | ⭐ **the closest is step-183's *"how the meaning changed over time"*** — ⚠️ **and `P-14` showed that requirement is borne by `K4a`, whose supersession chain IS ordered (§11)** |
| **what does the estate actually require of a past state?** | ⭐ *"is the CURRENT state warranted?"* — a revisit trigger points at the current association and its warrant, never at the visiting order of superseded ones |

$$\boxed{\begin{array}{c}\textbf{CONDITION 1 FAILS — and it fails BY DERIVATION, not by absence of evidence:}\\ \textbf{the distinction is ORDER OVER MULTIPLE PRIOR VALUES, which } P\text{-}08\ \S7 \textbf{ had ALREADY EXCLUDED}\\ \textbf{from the obligation set when it derived five requirement levels and rejected complete history.}\\[6pt] \boxed{\textbf{THIS IS THE REDUCTION ARGUMENT the anti-anchoring rule demands.}}\end{array}}$$

⚠️ ⭐ **The refutation is BOUNDARY-RELATIVE**, as every closure result in this programme is: it holds
against the **current obligation set**. ⛔ **It is not a proof that no such obligation could ever exist.**

## ⭐ Falsification condition — recorded

$$\boxed{\begin{array}{c}\textbf{The third axis REOPENS if a corpus witness ever REQUIRES knowing the ORDER of two prior values}\\ \textbf{whose transition records form an ambiguous Eulerian trail.}\\ \textbf{One such witness would satisfy condition 1 with condition 3 already satisfied.}\end{array}}$$

---

# 13. Provenance chains

`source₁ → definition` vs `source₂ → definition` ⇒ ⭐ **differ in `K3a`/`K5` content. Distinguished.**

`source₁ → source₂ → definition` — a **chain** of sources:

| | |
|---|---|
| is provenance ordering **required**? | ⚠️ **no witness** |
| is it **checkability**? | ⭐ **`K5`** |
| **warrant content**? | ⭐⭐ **`K3b`** — `P-13` established that corpus-state denotation is warrant content; a source-of-source is the same shape |
| **source identity**? | ⭐ 🔴 **no source cell** — definition source is a **value** (`P-13` §7) |
| merely explanatory metadata? | ⚠️ **absent a loss witness, yes** |

$$\boxed{\textbf{⛔ No provenance-history capability. ⚠️ The CHAIN case is } \mathbf{[OPEN]} \textbf{ — no witness either way.}}$$

# 14. Mathematical sanity check
⚠️ **Structural tests only; no graph persistence imported as architecture.**

| notion | status in the kernel |
|---|---|
| **node identity** | ⭐ **`K1`** |
| **edge identity** | ⭐ **`K4c-ii`** *(retired-into)*, `K4a-ii` *(supersedes)* |
| **path** | ⭐ **DERIVED from edges — not stored** |
| **reachability · transitive closure** | ⭐⭐ **DERIVABLE. Never a capability** |
| ⭐ **trail ORDER** | ⭐⭐⭐ **derivable EXCEPT under Eulerian ambiguity — §5. This is the entire residue** |
| **graph isomorphism** | ⛔ **not identity** — `P-15` §12's rule |
| **graph history** | ⭐ **not a primitive** — the union of edge records |

$$\boxed{\begin{array}{c}G_1 \neq G_2 \not\Rightarrow \textbf{a new capability.}\\ \boxed{\textbf{The mathematics LOCALIZES the third-axis question to ONE precisely characterized residue: Eulerian trail ambiguity.}}\end{array}}$$

⭐ **That localization is the audit's most useful structural result** — it converts an open-ended
"is there a third dimension?" into a single decidable question, answered in §12.

# 15. Statistical sanity check

| scenario | what changed |
|---|---|
| ⭐ **same observations, different ANALYSIS order** | ⭐⭐ **the analysis sequence only — NOT the observation, NOT the estimate's target, NOT the parameter** |
| same observations, different **model** | model + estimate — `P-15` §13 |
| different observations, same model | the observation — `P-08` §5.4 |

$$\boxed{\textbf{⛔ A different analytical sequence does NOT imply a different underlying object.}}$$

⭐ `observation sequence · measurement result · model update · estimate revision · parameter identity ·
provenance · causal interpretation` — held apart, and **only the first is at issue**; it is a property of
the analysis, not of the object.

# 16. DDD cross-check
⚠️ **Corroboration only.** The apparent third axis maps onto **Domain Event ordering** — and DDD treats
event order as a property **of the event stream**, i.e. of records, **not** as an entity dimension. ⭐
**Corroborates §6's conclusion that order would be a field on an existing record.**
⛔ **No Event Sourcing · no Aggregate · no Repository · no Saga · no Process Manager · no Bounded
Context.**

---

# 17. Result matrix

| candidate phenomenon | admissible witness? | capability that bears it | new loss distinction? | result |
|---|:--:|---|:--:|---|
| ⭐ **event ordering** *(general)* | ✅ **constructible** | ⭐ **`K3a`** — records are `(prior→successor)` **pairs**, so the walk is derivable | 🔴 **no** | ⭐ **REDUCED** |
| ⭐⭐ **event ordering** *(Eulerian-ambiguous)* | ✅ **constructible; all 11 cells identical** | ⭐⭐⭐ **none — but the distinction is NOT REQUIRED** *(`P-08` §7 excluded order over multiple priors)* | 🔴 **no** | ⭐⭐ **REFUTED by reduction** |
| **path multiplicity** | ✅ constructible | **`K4c-i`/`K4c-ii`** with cardinality; paths derived from edges | 🔴 no | **REDUCED** |
| **repeated reinterpretation** | ✅ `[EMP]` **one instance** *(`P-14`)* | ⭐ **`K4a-i`** retains every assertion; **`K4a-ii`** orders the chain | 🔴 no | ⭐ **REDUCED** |
| **correction chain** | ✅ constructible | ⭐ **`K4a-i`** — deletion impermissible, so `C₂` is retained | 🔴 no | ⭐ **REDUCED** |
| **provenance chain** | ⚠️ **none** | **`K3b`** warrant content · `K5` checkability | ⚠️ **`[OPEN]`** | ⚠️ **`[OPEN]` — no witness either way** |
| **merge/split ordering** | ✅ constructible | **`K4c-ii`** — the edge sets differ | 🔴 no | **REDUCED** |
| **alternate histories** *(counterfactual branches)* | ⚠️ **none** | ⭐ **nothing — the estate records what happened, not what might have** | ⚠️ `[OPEN]` | ⚠️ **`[OPEN]`; ⛔ and a counterfactual is not a record** |
| **causal structure** | ⚠️ none | ⭐ **`K3b`** — a causal claim is a justification | 🔴 no | **REDUCED** |

⭐ **6 REDUCED · 1 REFUTED by reduction · 2 `[OPEN]` with no witness in either direction.**
⛔ **No cell filled with speculation.**

# 18. Third-axis verdict

$$\boxed{\textbf{THIRD AXIS — REFUTED / CLOSED, boundary-relative.}}$$

**The reduction argument, in three steps:**
1. ⭐ **Six of the nine candidates reduce to existing cells** — the record structure `(prior→successor)`,
   `K4a`'s total retention, and `K4c-ii`'s cardinality do the work.
2. ⭐⭐ **The mathematics localizes the remainder to ONE residue** — Eulerian trail ambiguity, where all
   11 cells are provably identical.
3. ⭐⭐⭐ **That residue fails condition 1 BY DERIVATION:** its distinction is *order over multiple prior
   values*, a property of **complete history**, which **`P-08` §7 had already excluded** when it derived
   five requirement levels and rejected full-history retention.

⚠️ ⛔ **Two candidates remain `[OPEN]` with no witness in either direction** — provenance chains and
alternate histories. ⭐ **Neither is a third-axis candidate on present evidence**: the first reduces to
warrant content if it is ever required, the second is a **counterfactual**, and the estate records what
happened.

# 19. Transition completeness audit

⛔ **No claim of universal completeness.**

| transition | class |
|---|---|
| `0 → 1` **establishment** | ⭐ **empirically witnessed** — `G-67`, `C-022`, `FR-001` |
| `1 → 1` **identity-preserving change** | ⭐ **empirically witnessed** — `τ1`, `τ2`, `τ6` |
| `1 → n` **split** | ⭐ **empirically witnessed** — corpus `t₃` |
| `1 → 0` **retirement** | ⚠️ **PERMITTED `[STIPULATED]` but NOT witnessed** — retention-without-retirement is what the 5 cases show |
| `n → 1` **merge** | ⚠️ **scheduled, NOT witnessed** — 125 `unresolved_equivalence` files await it |
| **assertion supersession** | ⭐ **empirically witnessed** — the Zero meaning change |
| `M6` **framework replacement** | ⚠️ **merely conceivable** — `P-15` |
| ⭐ **unclassified** | ⭐⭐ **NONE** — `(all)→(all)` was retired by `P-15` as an aggregate description |

$$\boxed{\begin{array}{c}\textbf{3 witnessed, 1 permitted-unwitnessed, 1 scheduled, 1 conceivable, ZERO unclassified.}\\ \boxed{\textbf{⛔ No new transition class was exposed by this audit — and transition novelty would not imply capability novelty anyway.}}\end{array}}$$

# 20. Kernel impact

$$\boxed{\textbf{KERNEL REMAINS 11 CELLS. No cell added, removed, decomposed, or changed in status.}}$$

⭐ **And the structural queue empties:** the third axis was the **sole open structural question** after
`P-15`. ⇒ **0 OPEN non-cells.**

⭐⭐ **Consequence for `P-10`'s Claim B** *(subject × role does not generate the ontology)*: `P-16`
removes the *third-axis* route to falsifying the two-axis structure. ⚠️ **Claim B remains `[OPEN]`** —
its other two grounds, **subject-axis stratification** and **role overload**, are untouched here.

# 21. Remaining dependencies
**role overload** `[OPEN]`, one direction witnessed · **subject-axis stratification** `[OPEN]` ·
**Claim B** `[OPEN]` on those two grounds · **`W6`'s mechanism `[ARCH]`** and its **live empirical
failure** · **`O-INT002-1`** · **`O-INT001-1`'s governance rule** · **provenance chains** and
**alternate histories** `[OPEN]`, no witness · **`Q3`/`Q8`** an exercise of `K4c` · **`Q4`** · **kernel
identity** · **equivalence (`Q7`)** · **aggregate existence** · `F-4`/`F-5`.

# 22. Decision boundary

**`[EMP]`** `P-08` §5.2's deletion-impermissibility for assertions · the Zero meaning change · corpus
`t₃` split · establishment acts `G-67`/`C-022`/`FR-001` · merge scheduled across 125 files.

**`[DERIVED]`** ⭐⭐⭐ **transition records are `(prior→successor)` PAIRS, so walk order is derivable —
correcting `P-10` §4's *"`K3a` entails no ordering"*, which is true of a list of values and false of a
set of pairs** · ⭐⭐ **the residue is exactly Eulerian trail ambiguity, and it fails condition 1 by
derivation because `P-08` §7 already excluded order over multiple priors** · ⭐ **assertion-chain order
is borne by `K4a-ii`, since `supersedes` is its own ordering** · ⭐ **paths, reachability and transitive
closure are derived from edges, never stored** · ⭐ **a causal claim is a justification ⇒ `K3b`** ·
**sequence information ≠ a semantic dimension** · **order, if ever required, would be a FIELD on an
existing record** · **zero unclassified transitions**.

**`[REFUTED]`** ⭐ **the third axis, boundary-relative** — with the falsification condition of §12
recorded.

**`[OPEN]`** provenance chains · alternate histories · role overload · stratification · Claim B ·
everything in §21.

**`[ARCH]`** all mechanism. ⛔ **Nothing promoted for convenience; `[DER-S:*]` untouched.**

---

```
THIRD AXIS — REFUTED / CLOSED — NO ADDITIONAL PERSISTENCE CAPABILITY — KERNEL REMAINS 11 CELLS.

THE REDUCTION ARGUMENT (a refutation, not an absence of evidence):
  1. SIX of nine candidates reduce to existing cells. The load-bearing correction: P-08 §16 defines a
     K3a record PER TRANSITION, so each record is a (prior -> successor) PAIR, not a bare prior value.
     P-10's own witness — K->𝒦->X->K versus K->X->𝒦->K — therefore has DIFFERENT RECORD SETS and is
     already distinguished with no ordering information at all. This corrects P-10 §4's "K3a as stated
     entails no ordering", which is true of a list of VALUES and false of a set of PAIRS. P-10 not
     rewritten.
     Correction chains and repeated reinterpretation reduce to K4a: deletion of a superseded assertion
     is impermissible (P-08 §5.2), so intermediates are retained and the cells DIFFER — and chain order
     needs no new machinery, because `supersedes` IS its own ordering. Merge/split ordering and path
     multiplicity reduce to K4c-ii, since the edge sets differ and paths are derived from edges.
     Causal structure reduces to K3b: a causal claim is a JUSTIFICATION.
  2. The MATHEMATICS LOCALIZES the remainder to exactly ONE residue — Eulerian trail ambiguity, e.g.
     K->𝒦->K->X->K versus K->X->K->𝒦->K, where the edge multiset, the endpoint, the warrants and ALL
     ELEVEN CELLS are provably identical while the histories differ in order. Reachability and
     transitive closure are derivable; only trail order is not.
  3. THAT RESIDUE FAILS CONDITION 1 BY DERIVATION. Its distinction is ORDER OVER MULTIPLE PRIOR VALUES
     — a property of COMPLETE HISTORY — and P-08 §7 had ALREADY EXCLUDED complete history from the
     obligation set when it derived five requirement levels (current-only · current+act ·
     current+warrant · current+warrant+prior-value · full retention for assertions · reconstructibility
     for measurements). What the estate requires of a past state is "is the CURRENT state warranted?",
     not the visiting order of superseded ones. The closest corpus requirement — step-183's "how the
     meaning changed over time" — was shown by P-14 to be borne by K4a, whose supersession chain is
     itself ordered.

BOUNDARY-RELATIVE, as every closure result in this programme is. FALSIFICATION CONDITION RECORDED: the
  third axis REOPENS if a corpus witness ever REQUIRES knowing the order of two prior values whose
  transition records form an ambiguous Eulerian trail — condition 3 is already satisfied there, so one
  such witness would suffice.

STILL [OPEN] WITH NO WITNESS EITHER WAY: provenance chains (would reduce to K3b warrant content if ever
  required; there is no source cell) and alternate histories (a COUNTERFACTUAL is not a record).
  Neither is a third-axis candidate on present evidence.

TRANSITION COMPLETENESS AUDIT: 3 empirically witnessed (0->1 establishment, 1->1 identity-preserving,
  1->n split, plus assertion supersession), 1 PERMITTED but UNWITNESSED (1->0 retirement — the five
  retention cases are retention WITHOUT retirement), 1 SCHEDULED but unwitnessed (n->1 merge, 125
  files awaiting), 1 merely conceivable (M6 framework replacement), and ZERO UNCLASSIFIED now that
  P-15 retired (all)->(all). No new transition class was exposed, and transition novelty would not
  imply capability novelty anyway.

KERNEL IMPACT: 11 cells, nothing added, removed, decomposed or changed in status. The STRUCTURAL QUEUE
  EMPTIES — the third axis was the sole open structural question after P-15, so 0 OPEN non-cells remain.
  P-10's Claim B is NOT thereby closed: P-16 removes only the third-axis route to falsifying the
  two-axis structure, and its other two grounds — subject-axis STRATIFICATION and ROLE OVERLOAD —
  are untouched here.

NO ARCHITECTURE SELECTED — SCHEMA v3 NOT BEGUN — 3MC UNTOUCHED.
```
