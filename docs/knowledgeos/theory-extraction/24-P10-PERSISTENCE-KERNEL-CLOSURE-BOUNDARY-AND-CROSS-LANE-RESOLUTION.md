# `P-10` — Persistence Kernel Closure Boundary & Cross-Lane Resolution

**2026-09-07 · Lane T boundary artifact.** Baseline `18` · `19` · `21` · `22` · `23`; isolation `20`; handover `20a`.

> ⛔ **This phase discovers no capability.** It determines what is closed, what is conditional, what is
> open, and what must be decided outside Lane T.
>
> ⛔ **Schema v2 unmodified · v3 not begun · no A/B/C/D · no architecture · no UUID, surrogate key,
> repository, event sourcing, temporal/graph/relational/document storage, aggregate or event store · no
> Kernel identity or equivalence adjudication · `Q3`/`Q4`/`O-P09-1` unresolved · `F-4`/`F-5`
> unrepaired · 3MC and MD-018 untouched · Lane M Phase 5C/5D remains INVENTORY-ONLY — no formal intake
> record exists · `20a` is an OUTBOUND handover and is NOT admitted evidence · `[OPEN]` is never
> promoted because another lane has a plausible answer.**

`[EMP]` · `[DERIVED]` · `[CONDITIONAL]` · `[OPEN]` · `[ARCH]`.

---

# 1. What the progression actually shows

⛔ **It is NOT "the kernel grew from 5 to 7 to 11."** The **counting axis changed three times.**

| phase | question asked | counting axis | count |
|---|---|---|:--:|
| `P-08` | *what must be preserved?* | **subject-kind, coarse** — assertions, identities, states, grounds | **5** |
| `P-09.2` | *is `K4` one obligation?* | ⭐ **subject-kind, refined** — proposition ≠ ground ≠ reference | **7** |
| `P-09.3` | *is decomposition stopping?* | ⭐⭐ **cell-wise** — subject × role | **11** |
| — | *family-level* | present-state **3** + retention **8** | **3 + 8** |
| — | *subject-wise over the same structure* | `K1`, `K2`, `K5`, retention-antecedent, retention-link | **5** |

$$\boxed{\begin{array}{c}\textbf{The SAME structure yields 5, 7 or 11 depending on the axis counted along.}\\ \boxed{\textbf{The number is not the result. The structure is — and the number's instability WAS the diagnostic.}}\end{array}}$$

⭐ `[DERIVED]` **Nothing was ever added to the kernel.** Each phase re-individuated obligations that
were already present — `K3`'s two clauses were **written in `P-08` §16** as *"prior value + warrant"*.

---

# 2. Audit of the `P-09.3` stopping principle

$$K = (\Delta,\ \mathcal{T}_K)$$

| audit question | verdict |
|---|---|
| is the definition sufficient for the decomposition test? | ✅ **yes** — it supplies the **admissibility** filter: a sub-part no transition varies gives no counterexample |
| ⭐ was it silently strengthened anywhere? | ⚠️ **`P-09.3`'s synthesis reads more strongly than its derivation** — see §3 |
| what does *"no admissible counterexample"* mean? | ⭐ **stable relative to the current evidence boundary and the current transition universe** |
| what does it **NOT** mean? | ⛔ **universally atomic** |

⭐ `[DERIVED]` **And the definition has a second, unstated consequence, recorded here:** because `𝒯_K`
is part of the capability, **two obligations over different transition sets are different
capabilities even with the same `Δ`** — which is what keeps `K1` (item-level continuity) apart from
`K4c` (merge-distinguishability). ⚠️ **`P-09.3` §13 used this without naming it.**

---

# 3. ⭐⭐ Does the evidence establish a PRODUCT structure?

## Claim A — the observed decompositions FACTOR through subject × role

| test | result |
|---|---|
| are all 8 retention cells **populated**? | ✅ **yes, none empty** — `K3a/K3b` · `K4a-i/ii` · `K4b-i/ii` · `K4c-i/ii` |
| was each split found **independently**, by counterexample? | ✅ **yes** — 4 subject-splits in `P-09.2`, 4 role-splits in `P-09.3` §7–§10 |
| ⭐ does the structure account for **all ten `P-07` semantic items** without leftovers? | ⭐⭐ ✅ **yes, and non-trivially** |
| ⚠️ is the "all cells populated" observation a **prediction** or a **construction**? | ⭐ **a construction.** The cells were derived *from* the observations, so a full grid is not independent confirmation |

## ⭐⭐ The non-trivial coherence check

`[DERIVED]` **The subject axis is not "all semantic items" — it is "all ADJUDICATED subject kinds",**
and that **explains** the items with no retention cell rather than excusing them:

| `P-07` item | why it has no retention cell |
|---|---|
| **occurrence** | ⭐ **measured, not adjudicated** — re-derivable (`P-08` §5.4) |
| **glyph** | a value on the corpus; measured |
| **establishment act** | **measured-as-cited** |
| **realization vs model-of** | ⭐ adjudicated **value on a link** ⇒ falls in the **value** row |
| **definition source** | ⭐ `P-09.2` §9: **both** measured and adjudicated — its adjudicated part is a **value** ⇒ value row |

$$\boxed{\textbf{Claim A: } \mathbf{[DERIVED]}\textbf{. The observed decompositions factor through two axes, and the factorization is coherent over all ten items.}}$$

## Claim B — the persistence ontology is GENERATED COMPLETELY by subject × role

⭐⭐ **Two independent reasons this is TOO STRONG.**

### B-fail-1 — the subject axis may be STRATIFIED, not flat

`[EMP]` `P-09` §6 established **warrants ARE assertions**. So *ground* may be
**proposition-about-a-proposition** — a **meta-level** of the same kind, not a fourth kind.

$$\boxed{\begin{array}{c}\Pi_2 \textbf{ proved the OBLIGATIONS differ. It did NOT prove the SUBJECT KINDS differ.}\\ \boxed{\textbf{If } \textit{ground} = \textit{proposition at level } n{+}1 \textbf{, the subject axis is an unbounded HIERARCHY, not a 4-element set.}}\end{array}}$$

⭐ **And `O-P092-4` is the same worry structurally:** *does `K4c`'s `retired-into` relation itself bear
`K4a`?* — a second stratum.

### B-fail-2 — the ROLE axis appears OVERLOADED

`[DERIVED]` *"Link"* currently bundles **relation** and **justification**:

| cell | what it actually holds |
|---|---|
| `K3b` | ⭐ a **warrant** — a *justification* |
| `K4a-ii` | ⭐ `supersedes` — a *relation*, with no justification (that is `K3b`'s job) |
| `K4b-ii` | ⭐⭐ **BOTH** — `invalidated-by` (relation) **and** the defect (justification) |

⭐ **Applying this project's own model-integrity rule** — *is it a new dimension, an overloaded
existing dimension, or merely another value?*

$$\boxed{\textbf{An OVERLOADED EXISTING DIMENSION. "Role" carries two values that are not clearly one: } \textit{relation} \textbf{ and } \textit{justification}.}$$

⚠️ **Partial evidence only:** relation-without-justification is witnessed (`P-08` §19's repaired
witness — `resolved` with no warrant); **justification-without-relation is NOT witnessed** ⇒ the split
is **`[OPEN]`**, not derived.

$$\boxed{\textbf{Claim B: TOO STRONG. Retain only the weaker structural result.}}$$

## Claim C — no future admissible distinction can introduce another axis

$$\boxed{\textbf{Claim C: } \mathbf{[OPEN]}\textbf{. } P\text{-}09.3 \textbf{ itself records that } \mathcal{T}_{KOS} \textbf{ is incomplete, so } C \textbf{ cannot be derived.}}$$

## ⭐ The formulation Lane T is entitled to

$$\boxed{\begin{array}{c}\textbf{ESTABLISHED: } \textit{every retention decomposition observed so far separates into an ANTECEDENT and a LINK,}\\ \textit{and the subjects separate into value, proposition, ground and reference.}\\[6pt] \textbf{NOT ESTABLISHED: } \textit{that these two axes GENERATE the persistence ontology.}\end{array}}$$

⚠️ ⭐ **`P-09.3`'s sentence *"the kernel is not a set of capabilities, it is a product of two
independent individuation axes"* is STRONGER than its derivation.** ⛔ **Recorded as a qualification;
`P-09.3` is not rewritten.**

---

# 4. Third-axis falsification test

⛔ **A falsification condition, not a search for `K12`.** For each candidate: `Δ` · required
transition · is it in `𝒯_KOS` · is the requirement `[EMP]`/`[DERIVED]` · verdict.

| # | candidate | `Δ` | transition needed | in `𝒯_KOS`? | requirement | verdict |
|---|---|---|---|:--:|---|:--:|
| 1 | **content vs encoding** | *is the retained text the authored text or a transform of it?* | re-encoding | 🔴 **no** | — | `[OPEN]` |
| 2 | **completeness** | *is the retained antecedent whole or partial?* | truncation | 🔴 **no** | — | `[OPEN]` |
| 3 | ⭐⭐ **temporal position** | *which of two prior states came first?* | ⭐ **two successive `τ2` re-dispositions** | ⭐⭐ ✅ **YES — `τ2` is in the universe and can recur** | ⚠️ **NOT evidenced** — no double re-disposition has occurred | ⭐⭐ **`[OPEN]` — the HIGHEST-PRIORITY candidate** |
| 4 | ⭐ **provenance role** | *source-of-content vs act-of-entry vs argument-for-pairing* | ✅ **`τ6`** | ✅ **yes** | ⭐ **`[EMP]` — `FR-001` holds two roles at once** | ⭐ **a LINK-axis refinement, NOT a third axis** — the role is a property **of the link**, so it refines an existing cell |
| 5 | **transition classification** | *which `τ11` sub-class occurred* | `τ11` | ✅ yes | ⚠️ preservation unevidenced | `[OPEN]` (`O-P093-4`) |
| 6 | **warrant scope** | *does one warrant cover one transition or many?* | any | ✅ yes | 🔴 unevidenced | `[OPEN]` |

$$\boxed{\begin{array}{c}\textbf{6 candidates · 0 established · 1 with an admissible transition but an unevidenced requirement (3) ·}\\ \textbf{1 with } \mathbf{[EMP]} \textbf{ support but classified as a LINK REFINEMENT rather than a new axis (4).}\\[6pt] \boxed{\begin{array}{l}\textbf{FALSIFICATION CONDITION: the two-axis result fails if EITHER a double re-disposition creates a}\\ \textbf{required ordering distinction (3), OR justification-without-relation is ever witnessed (§3 B-fail-2).}\end{array}}\end{array}}$$

⛔ **"Describable" was not converted into "capability" in any of the six.**

---

# 5. `K2` — explicitly unresolved

$$\boxed{K2 = \textbf{UNDERDETERMINED}}$$

⛔ **No `K2a`/`K2b` established.** Recorded exactly:

| | |
|---|---|
| content and state are **semantically distinguishable** | ✅ |
| **both directions describable** | keep the status, lose the text (**`W7` shape**) · keep the text, lose the status (**`W3`**) |
| ⭐ **no current admissible transition changes a definition's text** | `τ1..τ10` are field-level; `P-08` §4.3 records *"may be regenerated: nothing"* |
| ⇒ **decomposition cannot be derived** | ⭐ `[OPEN]`, not `[DERIVED]` |
| **model replacement** and **semantic reinterpretation** remain open | the latter `[OPEN — LANE M]` |
| ⭐ **`O-P08-1` may additionally make `K2` set-wise redundant** | §6 |

## ⭐⭐ The two questions, forced apart

$$\boxed{\begin{array}{ll}\textbf{A. Can } K2 \textbf{ DECOMPOSE?} & \textbf{depends on } \mathcal{T}_{KOS}\textbf{'s open classes (§5) — a TRANSITION question}\\ \textbf{B. Does } K2 \textbf{ remain independently NECESSARY?} & \textbf{depends on } O\text{-}P08\text{-}1 \textbf{ (§6) — a GOVERNANCE question}\end{array}}$$

⭐ `[DERIVED]` **These are not the same question, they have different owners, and either can be
answered without the other.** ⚠️ **`P-09.3` reported both under one verdict.**

---

# 6. The `P2` kernel-changing dependency, formalized

$$\boxed{\textbf{IF } O\text{-}P08\text{-}1 \textbf{ permits regression } \Rightarrow K3 \textbf{ MAY become universal } \Rightarrow K2 \textbf{ MAY become set-wise redundant.}}$$

⛔ **Stated as a conditional dependency, not a fact. Neither branch is adjudicated.**

| | **Branch `R0` — regression NOT permitted** | **Branch `R1` — regression permitted** |
|---|---|---|
| **`K3` scope** | ⭐ **exceptional** — non-monotone transitions only | ⭐ **universal** — every status transition |
| **`K2` status** | ⭐ **independently necessary** — monotone changes leave no record, so replay cannot recover current state | ⭐⭐ **possibly REDUNDANT** — `{K3a,K3b} ⊨ K2` once every transition leaves a record |
| **kernel implication** | 11 cells, `K2` decomposability still open | ⭐ **10 cells, `K2` absorbed** — the kernel **SHRINKS** |
| **`P-08` §17 redundancy proof** | ✅ available | 🔴 **lapses** |
| **`P-08` §19 negative** | ✅ stands *(the commissioned witness fails)* | ⭐ **reverses — the original witness holds** |
| **still unresolved** | `K2` decomposability · third axis · `W6` | ⭐ **whether replay is genuinely sufficient**, or whether `K2` retains a residue `K3` cannot express |

⭐⭐ `[CONDITIONAL]` **`P2` is therefore no longer only a governance blocker — it is a THEORY-SELECTION
VARIABLE.** ⛔ **And Lane T cannot resolve it: MD-018 is Lane M's own decision record.**

---

# 7. `K1` set-wise independence — re-tested carefully

**Three senses kept apart:**

| sense | role in the argument |
|---|---|
| **identity as semantic sameness** (`F-CONT`) | ⭐ **the one used** |
| **identity as referenceability** | ⛔ **not used** — `P-09` §3 demoted addressability |
| **identity as a persistence designator** | ⛔ **not used — that would be a database-key argument** |

## The argument, in its precise form

`[DERIVED]` Every retention record has the logical form `Changed(x, a, b)`, `Supersedes(p, q)`,
`RetiredInto(x, Y)`. In each, **a term must denote the same thing at record-time and at read-time.**
That is `F-CONT` — **semantic sameness**, not a key.

$$\boxed{\begin{array}{c}\textbf{The other persistence distinctions cannot even FORMULATE their retained records without presupposing}\\ \textbf{the identity of the subject those records are about.}\\[6pt] \boxed{\textbf{A presupposed premise cannot be a derived conclusion } \Rightarrow K1 \textbf{ is set-wise independent UNCONDITIONALLY.}}\end{array}}$$

| is this sufficient for **semantic** independence? | ✅ **yes** — it turns on the *denotation* of a term in a record, which is a semantic property, and holds under **any** representation |

⭐ **And the conditionality first suspected does not materialize** — `K1` is the **only** cell whose
independence survives **both** branches of §6 untouched.

---

# 8. `K5` — preserved as stated

$$\boxed{K5 = \textbf{the current EXTERNAL ground remains checkable}}$$

| corpus-state identification | ⭐ **a PRECONDITION for measurement-grounded `K5`** · **capability status `[OPEN]`** |
|---|---|
| ⛔ **not promoted into the kernel** | its `(Δ,𝒯)` cannot be tested while `W6` is live |
| ⛔ **no snapshot or hash mechanism invented** | `[ARCH]` |
| **`W6` kept separate** | ✅ — an independent blocker, unchanged |

⭐ `[DERIVED]` **`K5` is the second cell independent under both `§6` branches** — it concerns an
**external** ground, and no amount of internal retention entails external availability.

---

# 9. Four meanings of "minimality" — frozen

| # | term | definition | Lane T? |
|---|---|---|:--:|
| 1 | **semantic minimality** | minimum independently necessary **distinctions** | ✅ **in scope — NOT ESTABLISHED** |
| 2 | **capability minimality** | minimum independently necessary **preservation obligations** `(Δ,𝒯)` | ✅ **in scope — a LOWER BOUND only, axis-relative** |
| 3 | **representation minimality** | minimum storage / implementation constructs | ⛔ **`[ARCH]` — not addressed** |
| 4 | ⭐ **Lane-M kernel-object minimality** | whatever *"minimality"* means in Phase 5D | ⛔ **NOT ADMITTED into Lane T** |

⛔ **The 11-cell structure proves NEITHER semantic minimality NOR representation minimality.**

---

# 10. Closure matrix

| cell | status | evidence | transition dependency | Lane-M dependency | kernel-changing? |
|---|---|---|---|---|:--:|
| **`K1`** | ⭐ **CLOSED** — stable, set-wise independent under both branches | `[DERIVED]` §7 | 🔴 none | 🔴 none | 🔴 **no** |
| **`K2`** | ⭐⭐ **UNDERDETERMINED — twice over** | `[OPEN]` | ⭐ **decomposability: model replacement · reinterpretation** | ⭐ **reinterpretation classification** | ⭐⭐ **YES — may be absorbed (`R1`)** |
| **`K3a`** | **CLOSED as a cell** | `[DERIVED]` `P-08` §17 | 🔴 none | 🔴 none | ⚠️ **scope only** |
| **`K3b`** | **CLOSED as a cell** | `[DERIVED]` `P-08` §19 | 🔴 none | 🔴 none | ⚠️ **scope only** |
| **`K4a-i`** | **CLOSED** | `[EMP]` withdrawals + `[DERIVED]` | 🔴 none | 🔴 none | 🔴 no |
| **`K4a-ii`** | **CLOSED** | `[DERIVED]` | 🔴 none | 🔴 none | ⚠️ **role-overload (§3 B-fail-2)** |
| **`K4b-i`** | **CLOSED** | ⭐ `[EMP]` `Closure(𝒦₉)` | 🔴 none | 🔴 none | ⚠️ **subject-stratification (§3 B-fail-1)** |
| **`K4b-ii`** | ⚠️ **CONDITIONAL** — ⭐ holds **both** relation and justification | `[DERIVED]` | 🔴 none | 🔴 none | ⚠️ **role-overload** |
| **`K4c-i`** | ⭐ **`[CONDITIONAL]`** — obligation active only if retirement is permitted | `[EMP]` ×5 retention | `τ11` ✅ evidenced | ⭐⭐ **`O-P09-1` antecedent** | ⭐ **YES — activation** |
| **`K4c-ii`** | ⭐ **`[CONDITIONAL]`** — same antecedent | `[DERIVED]` | `τ11` ✅ | ⭐⭐ **`O-P09-1`** | ⭐ **YES** |
| **`K5`** | ⭐ **CLOSED** — independent under both branches | `[EMP]` `F-4` | 🔴 none | 🔴 none | 🔴 no |
| ⭐ **corpus-state identification** | **`[OPEN]` as a capability** | `W6` | 🔴 n/a | 🔴 none | ⭐ **YES if it is a capability** |
| ⭐⭐ **third-axis possibility** | **`[OPEN]`** — 6 candidates, 0 established | §4 | ⭐ **candidate 3 has an admissible transition** | 🔴 none | ⭐⭐ **YES — would re-open decomposition** |

⭐ **7 cells closed · 2 conditional on Lane M · 1 conditional on role-overload · 1 underdetermined ·
2 open structural questions.**

---

# 11. Unresolved questions by consequence

| class | questions |
|---|---|
| **`C0`** no kernel consequence | `F-4`/`F-5` repair *(defects, not capabilities)* · `X4` term collision *(hygiene)* · whether to **call** the association an Entity |
| **`C1`** scope only | ⭐ **`O-P08-1`'s effect on `K3`'s REACH** *(exceptional vs universal — the cell exists either way)* · occurrence identity mechanism |
| ⭐⭐ **`C2` KERNEL-CHANGING** | ⭐ **`O-P08-1`** *(via `K2`'s set-wise redundancy — §6)* · ⭐ **semantic reinterpretation / transition classification** *(via `K2`'s decomposability — §5)* · ⭐ **`O-P09-1`'s antecedent** *(activates or deactivates `K4c-i`/`K4c-ii`)* · ⭐⭐ **the third-axis question** *(§4 candidates 3 and 4)* · ⭐ **role-overload: is justification a distinct value?** |
| **`C3`** architecture-only | every mechanism · addressing · aggregates · A/B/C/D · representation minimality |
| ⭐ **independently classified** | **`W6`** — ⭐ **it is `C2` IF corpus-state identification is a capability, and `C3` if it is a mechanism; ⛔ that is exactly what cannot currently be decided** |

⭐⭐ `[DERIVED]` **`O-P08-1` appears in BOTH `C1` and `C2`** — it re-scopes `K3` **and** can absorb
`K2`. **It is the only question with two independent kernel consequences.**

---

# 12. Cross-lane dependency boundary

| question | owning lane | why Lane T cannot answer it | exact kernel consequence | formal intake? | admissible? |
|---|---|---|---|:--:|:--:|
| **`O-P09-1`** identity retirement | ⭐ **Lane M** | Lane T declared it **blocking**; resolving its own blocker = adjudicating its own evidence (**`R-34`**) | ⭐ **activates/deactivates `K4c-i` + `K4c-ii`** | 🔴 **no** | 🔴 **no** |
| **Kernel identity adjudication** | **Lane M** | Lane T is **read-only w.r.t. 3MC** by charter | ⭐ could bear on `K1`'s `Δ` | 🔴 no | 🔴 no |
| **equivalence adjudication** | **Lane M** | same | bears on merge warrants (`O-P091-4`) | 🔴 no | 🔴 no |
| ⭐ **semantic reinterpretation classification** | **Lane M** | ⭐⭐ **classifying it IS an identity adjudication** (`P-09.1` §4) | ⭐⭐ **decides `K2`'s decomposability** | 🔴 no | 🔴 no |
| **`Q3`** | **Lane M** | an open adjudication | ⭐ answering *"one"* **is a merge** ⇒ exercises `K4c` | 🔴 no | 🔴 no |
| **`Q4`** | **Lane M** | same | unquantified | 🔴 no | 🔴 no |
| ⭐⭐ **MD-018 direction (`O-P08-1`)** | ⭐ **GOVERNANCE** *(record owned by Lane M)* | ⭐ **the corpus is SILENT on direction — silence is a governance question, not a research one** | ⭐⭐ **`K3` reach + `K2` survival** | 🔴 no | 🔴 no |

$$\boxed{\textbf{7 cross-lane dependencies. ZERO formal intake records exist. NONE is currently admissible evidence.}}$$

⚠️ ⛔ **`20a` is an outbound offer; Lane M has recorded no admission, and Lane T has created no intake.**

---

# 13–14. What "closed" can legitimately mean

| state | condition | applies? |
|---|---|:--:|
| **CLOSED** | no admissible unresolved question can change the capability result | 🔴 **no** — §11 `C2` holds five |
| ⭐ **CONDITIONALLY CLOSED** | the **decomposition structure** is closed, but external decisions can change the final kernel | ⭐⭐ ✅ **YES** |
| **UNDERDETERMINED** | evidence insufficient for even structural closure | 🔴 **no** — the structure survived §3's `A` test and §4's falsification test |

$$\boxed{\begin{array}{c}\textbf{Lane T has achieved CONDITIONAL STRUCTURAL CLOSURE of the currently evidenced persistence}\\ \textbf{decomposition — and NOT universal kernel closure.}\end{array}}$$

⭐ **This is an analytical result, not the pre-offered formulation accepted on authority:** it is
`CONDITIONALLY CLOSED` **because** Claim `A` is `[DERIVED]` (structure holds), Claim `B` is **too
strong** (no generative ontology), and five `C2` questions remain — ⛔ **not** because a middle verdict
is comfortable.

---

# 15. Architecture readiness

⛔ **No architecture designed.**

| gate | | verdict |
|---|---|:--:|
| **`P1`** | semantic distinctions sufficiently stable | ⚠️ **CONDITIONAL** — 7 of 11 cells closed; ⭐ `K2` underdetermined and the third-axis question open |
| **`P2`** | epistemic / governance rules sufficiently stable | 🔴 **BLOCKED** — `O-P08-1` unanswered, with **two** kernel consequences |
| **`P3`** | transition universe sufficiently characterized | 🔴 **BLOCKED** — model replacement and reinterpretation unclassified, and ⭐ `K2` depends on them |
| **`P4`** | corpus-state requirement resolved enough for architecture | 🔴 **BLOCKED** — ⭐ `W6` live; **its very classification (`C2` vs `C3`) is undecidable** |
| **`P5`** | cross-lane dependencies formally admitted | 🔴 **BLOCKED** — ⭐⭐ **zero intake records exist** |

$$\boxed{\textbf{ARCHITECTURE COMPARISON NOT YET PERMITTED — 1 conditional, 4 blocked.}}$$

⭐ **And `P5` is the cheapest to move**: it needs a **procedure**, not a discovery.

---

# 16. Decision boundary

**`[EMP]`** MD-018's chain **ordered**, **"no skip"** ×3, ⭐ **zero direction language** · MD-015's ladder
**cumulative/max** and MD-018 **declares itself distinct** · `Closure(𝒦₉)` 15/15 with warrant replaced
14→66 worlds · four withdrawals, each **of a warrant** · `Θ` REJECTED-and-retained · `Zero`'s `[RF]`
pair · `F-4`/`F-5` standing · `FR-001` **two provenance roles at once** · `machine-record-schema.md:198`
merging **deferred** · **125** `unresolved_equivalence` files · corpus `t₃` **split** / `t₄`
**determination revised** · `τ7` **56 uncommitted renames**.

**`[DERIVED]`** ⭐ **the count is axis-relative — nothing was ever added to the kernel** · ⭐⭐ **Claim A
holds: observed decompositions FACTOR through subject × role, coherently over all ten `P-07` items,
and the subject axis is "adjudicated subject kinds", which EXPLAINS the items with no cell** · ⭐
**`K1` set-wise independent unconditionally — retention records PRESUPPOSE the denotation of their
subject term** · ⭐ **`K5` independent — no internal retention entails external availability** · ⭐
**`K1` and `K5` are the only cells untouched by both `R0`/`R1` branches** · ⭐ **`𝒯_K` membership makes
`K1` and `K4c` different capabilities with related `Δ`** · ⭐ **`K2`'s two questions have different
owners and either can be answered alone** · **`O-P08-1` is the only question with two independent
kernel consequences**.

**`[CONDITIONAL]`** ⭐ **`K2`'s survival — branch `R1` absorbs it into `{K3a,K3b}`** · ⭐ **`K4c-i`/`K4c-ii`
activation — on `O-P09-1`'s antecedent** · **`K4b-ii`'s unity — on the role-overload question** ·
**the two-axis result — on §4's two falsification conditions**.

**`[OPEN]`** ⭐⭐ **Claim B: subject × role does NOT generate the ontology** — the subject axis may be
**stratified** (*ground = meta-proposition*) and the role axis is **overloaded** (*relation +
justification*) · **Claim C** · `K2` decomposability · third axis *(6 candidates, 0 established;
⭐ temporal position is the highest-priority)* · corpus-state identification's capability status ·
`W6`'s own classification · transition classification preservation · `Q3` · `Q4` · `O-P09-1` ·
aggregate existence.

**`[ARCH]`** every mechanism · addressing · locators · corpus-state identifier · aggregates ·
A/B/C/D · **representation minimality**. ⛔ **Nothing here is promoted for convenience.**

---

```
P-10 PERSISTENCE KERNEL CLOSURE BOUNDARY

STRUCTURAL RESULT:
  Every retention decomposition observed so far separates into an ANTECEDENT and a LINK, and the
  subjects separate into value, proposition, ground and reference — 8 retention cells, none empty,
  each split found independently by counterexample, plus 3 present-state capabilities (K1, K2, K5).
  The factorization coherently accounts for all ten P-07 semantic items: the five with no retention
  cell are MEASURED or measured-as-cited, not adjudicated, which the structure EXPLAINS rather than
  excuses. The count is AXIS-RELATIVE (5 / 7 / 11 for one structure) and nothing was ever added to
  the kernel — K3's two clauses were already written in P-08 §16.

TWO-AXIS STATUS:
  ESTABLISHED as a FACTORIZATION of the observed decompositions [DERIVED].
  CONDITIONAL as a structure: two falsification conditions stand (§4).

PRODUCT-ONTOLOGY CLAIM:
  TOO STRONG. P-09.3's "the kernel is a product of two independent individuation axes" exceeds its
  derivation, for two independent reasons: (i) the SUBJECT axis may be STRATIFIED rather than flat —
  warrants ARE assertions, so `ground` may be proposition-at-level-n+1, making the axis an unbounded
  hierarchy; Pi-2 proved the OBLIGATIONS differ, not that the SUBJECT KINDS differ; (ii) the ROLE axis
  is OVERLOADED — "link" bundles RELATION (supersedes) and JUSTIFICATION (warrant), and K4b-ii holds
  both. By this project's own model-integrity rule this is an OVERLOADED EXISTING DIMENSION, not a new
  one; and only one direction is witnessed, so the split stays [OPEN].
  Claim A [DERIVED] · Claim B TOO STRONG · Claim C [OPEN]. P-09.3 not rewritten; qualification recorded.

DECOMPOSITION CLOSURE:
  CONDITIONALLY CLOSED — closed relative to the current evidence boundary and transition universe,
  NOT universally. Falsification conditions: (a) a double re-disposition creating a required ORDERING
  distinction — candidate 3 is the only third-axis candidate whose transition IS admissible, blocked
  solely by an unevidenced requirement; (b) justification-without-relation ever being witnessed.

CURRENT CELLS:
  7 CLOSED        K1 · K3a · K3b · K4a-i · K4a-ii · K4b-i · K5
  2 CONDITIONAL   K4c-i · K4c-ii   (on O-P09-1's antecedent — LANE M)
  1 CONDITIONAL   K4b-ii           (on the role-overload question)
  1 UNDERDETERMINED  K2
  2 OPEN structural  corpus-state identification as a capability · third-axis possibility

K2:
  UNDERDETERMINED, TWICE OVER — and the two questions are DIFFERENT, with different owners:
  (A) can K2 decompose? content vs state is required in both directions but NO admissible transition
      changes a definition's text ⇒ depends on model replacement / semantic reinterpretation [OPEN].
  (B) does K2 remain independently necessary? depends on O-P08-1: under branch R1 (regression
      permitted) K3 becomes universal and {K3a,K3b} |= K2, so the kernel SHRINKS to 10 cells.
  No K2a/K2b established. Describable is not derivable.

K1:
  CLOSED. Set-wise independent UNCONDITIONALLY — every retention record has the form Changed(x,a,b) /
  Supersedes(p,q) / RetiredInto(x,Y), and each requires a term to denote the same thing at record-time
  and read-time. That is semantic sameness (F-CONT), NOT a designator and NOT a database key. A
  presupposed premise cannot be a derived conclusion. K1 is untouched by both R0 and R1.

K5:
  CLOSED as stated — the current EXTERNAL ground remains checkable; no internal retention entails
  external availability. Corpus-state identification remains a PRECONDITION for measurement-grounded
  K5 with capability status [OPEN]; not promoted, no mechanism invented, W6 kept separate.

KERNEL-CHANGING OPEN QUESTIONS (class C2):
  O-P08-1 MD-018 direction        — the ONLY question with TWO independent kernel consequences
                                     (K3's reach, and K2's survival)
  semantic reinterpretation       — decides K2's decomposability
  O-P09-1 antecedent              — activates or deactivates K4c-i and K4c-ii
  third-axis question             — would re-open decomposition (candidates 3, 4)
  role-overload                   — is JUSTIFICATION a value distinct from RELATION?
  W6 independently classified     — C2 if corpus-state identification is a capability, C3 if a
                                     mechanism; which it is CANNOT currently be decided

CROSS-LANE DEPENDENCIES:
  O-P09-1 · Kernel identity adjudication · equivalence adjudication · semantic reinterpretation
  classification · Q3 · Q4 · MD-018 governance direction.
  SEVEN dependencies. ZERO formal intake records exist. NONE currently admissible. 20a is an OUTBOUND
  offer with no recorded admission.

ARCHITECTURE READINESS:
  BLOCKED — P1 CONDITIONAL · P2 BLOCKED · P3 BLOCKED · P4 BLOCKED · P5 BLOCKED.
  Architecture comparison NOT YET PERMITTED. P5 is the cheapest to move: it needs a PROCEDURE, not a
  discovery.

SEMANTIC MINIMALITY:
  NOT ESTABLISHED

REPRESENTATION MINIMALITY:
  [ARCH — NOT ADDRESSED]

NEXT PHASE:
  Obtain the O-P08-1 / MD-018 direction GOVERNANCE RULING (does the status chain permit regression?).
  It is the single next action: it is the only C2 question with two independent kernel consequences,
  it depends on no other open question, it is answerable by a DECISION rather than by research, and
  the corpus is provably silent on direction — so no further Lane T derivation can reach it.

NO KERNEL IDENTITY ADJUDICATION
NO CROSS-LANE FINDINGS CONSUMED
NO PERSISTENCE ARCHITECTURE SELECTED
NO SCHEMA v3
NO A/B/C/D DECISION
```
