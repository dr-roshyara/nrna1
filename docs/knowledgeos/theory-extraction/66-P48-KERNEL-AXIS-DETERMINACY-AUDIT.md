# `P-48` — Kernel Axis Determinacy Audit

**2026-09-08 · Lane T.** After [`65` `P-47`](./65-P47-PERSISTENCE-KERNEL-MINIMALITY-AND-IRREDUCIBILITY-PROOF-AUDIT.md).

# 1. Executive verdict

$$\boxed{\begin{array}{c}\mathbf{C\ —\ [OPEN].} \textbf{ Several representations are admissible and } \mathbf{none\ is\ privileged\ by\ the\ corpus} \textbf{ — and the corpus } \mathbf{SAYS\ SO\ EXPLICITLY.}\\[6pt] \boxed{\textbf{⭐⭐⭐ } \mathbf{M\text{-}2\ [EXP]}\textbf{: } \textit{"} \mathbf{A\ minimality\ claim\ without\ a\ fixed\ admissible\ algebra\ is\ not\ a\ claim.} \textit{"}}\end{array}}$$

⚠️⚠️ **This audit was drafted with verdict `B` and is corrected to `C` on the commissioner's two
mid-audit pointers.** ⭐ **`F1` is `[EMP]`, not `[UNWITNESSED]`: the corpus defines kernel minimality
formally, and its answer is the very axis-relativity `P-47` had rediscovered independently.**

$$\boxed{\mathbf{[DEF\text{-}33]}\quad \textbf{"Kernel minimality is } \mathbf{NOT}\ \min|\mathit{operators}|\textbf{." It is } \quad K_{min} = \arg\min_{K} \mathit{Complexity}(K)}$$
$$\textbf{subject to } \mathit{CapabilityClosure} \cdot \mathit{SemanticAdequacy} \cdot \mathit{Preservation} \cdot \mathit{InvariantPreservation} \cdot \mathit{TransitionCompleteness}$$

$$|K| = 11 \textbf{ — unchanged, still } \mathbf{[REC]}. \textbf{ ⛔ No cell added, removed, merged or renamed.}$$

# 2. ⚠️ Corrections carried into this audit

**(a)** ⭐ **`F2` as I drafted it — *"each `τ` forces exactly one cell"* — was refuted by `P-47` itself**
*(retirement/merge forces multiple `K4` cells)*. Replaced by the many-to-many formulation.
**(b)** ⭐⭐ **`F3` was undefined** until an equivalence relation was fixed; without one a 5-cell
partition can be manufactured by grouping.
**(c)** ⭐ **The six candidate equivalence relations are NESTED**, tested with entailments reported.
**(d)** ⭐⭐⭐ **My drafted verdict `B` — *"the witness set fixes the granularity"* — is `[REFUTED]` by
`[DEF-33]`.** The witness partition is **one admissible representation**, ⛔ **not the privileged one.**

⚠️ **Pre-registered `D`; drafted `B`; the answer is `C`. Both wrong. Record now 6 of 12.**

⛔ **No `three_model_convergence/` · Schema v3 · architecture · canonicalization · governance change ·
`EKS-12` reopening · `[REC]` upgrade.**

# 3. ⭐⭐⭐ `F1` — the corpus DOES define kernel minimality

⭐ **Found only after two commissioner pointers** *(`kernel/`, then
`mathematical_ideas_that_can_be_implemented/`)* — ⚠️ **my own `F1` search had looked for *granularity*
vocabulary and never for a *minimality definition*.**

| witness | status |
|---|---|
| ⭐⭐⭐ **`[DEF-33]`** — `K_min = \arg\min_K Complexity(K)` s.t. five constraints; *"Kernel minimality is **not** `min |operators|`"* | ⭐ **`[EMP]` — a formal DEFINITION** |
| ⭐⭐⭐ **Theorem 10** — *"Operator-count minimality is **representation-relative**. Under algebra `𝒜₀`: `|K_min| = 13`. Under another admissible algebra: `8`."* ⚠️ *"essentially an **empirical theorem** about the current experimental setup"* | ⭐ **`[EXP]`-grade theorem** |
| ⭐⭐⭐ **`M-2` `[EXP]`** — *"These are **not conflicting measurements** — they are **solutions to different optimization problems**. **A minimality claim without a fixed admissible algebra is not a claim.**"* | ⭐⭐ **`[EXP]`** |
| *"You must **define minimality before running the experiment**"* | `[EMP]` |
| *"The type of `E_t` should be fixed **before kernel minimality is claimed**"* | `[EMP]` |
| a file titled ***"minimality is representation-dependent"*** | `[EMP]` |

$$\boxed{\textbf{⭐⭐ The } \mathbf{11/7/5} \textbf{ problem is the } \mathbf{13/8} \textbf{ phenomenon, already characterised: } \mathbf{solutions\ to\ different\ optimization\ problems.}}$$

⚠️ **Scope, stated honestly:** `[DEF-33]` and Theorem 10 were developed for the **operator** kernel.
⭐ **`[DEF-33]` is phrased generally** *("Kernel minimality is…")*, so its application to the
persistence kernel is **`[DERIVED]`**, ⛔ **not `[EMP]`** — and Theorem 10's `13`/`8` numbers are
**`[ANALOGY ONLY]`** here.

## 3a · `kernel/` — a granularity vocabulary, applied to a different kernel
⭐ The first pointer also paid off: `kernel/` (163 files) carries *"the **smallest authoritative
KnowledgeCore boundary**"*, *"the **minimum set of domain CONCEPTS** the Kernel must own"*, and
⭐⭐ ***extent*** *(which aggregates and which gate)* vs ***contents*** *(which members, states,
events)*. ⚠️ **All about the `K-1` DDD kernel — and *"KnowledgeAggregate is the smallest boundary"* is
marked **`Unproven`** even there.** ⛔ **`K-1`/`K-2` is governance-frozen; `[ANALOGY ONLY]`.**

# 4. Primary-source reconstruction

| artifact | count | what it is |
|---|---|---|
| `P-08` | **5** | `K1`–`K5`, forced by `W1`–`W7` |
| `P-09.2` | **7** | ⭐ ***"revised lower bound (was 5). Five was NOT preserved for continuity"*** |
| `P-09.3` | **11** | the completed decomposition |
| `P-09.3` §20 | **5** *(subject-wise)* | ⚠️ **a GROUPING of the 11** — ⭐ **and a *different object* from `P-08`'s 5, same number** |

⭐⭐ **So `5 → 7 → 11` is a derivation history** *(each supersedes its predecessor)*, ⭐ **while the
subject-wise `5` is a re-description of the 11.** ⛔ **Neither pattern is "three rival axes" — but under
`[DEF-33]` both are *admissible representations*, which is the point.**

# 5. `F2` — the obligation structure, and `P-17`'s prior result

$$\boxed{\begin{array}{l}\textbf{"The two-axis structure was } \mathbf{NEVER} \textbf{ a decomposition of the KERNEL — only of the RETENTION FAMILY."}\\ \boxed{\mathbf{RETROSPECTIVE\ CLASSIFICATION \neq GENERATIVE\ STRUCTURE}}\\ \textbf{"} \mathbf{ZERO} \textbf{ cells were predicted by the product. ALL EIGHT were witnessed first and labelled afterwards."}\\ \textbf{⭐⭐ "indexing succeeds and } \mathbf{generation\ fails} \textbf{ — every cell's content is traceable to its } \mathbf{OWN\ WITNESS}\textbf{."}\end{array}}$$

⭐ **The axes INDEX, they do not GENERATE** — so **no axis fixes the granularity**. ⭐⭐ **But that
does not mean the witnesses do either**: they fix **one** representation, and `[DEF-33]` asks for
`\arg\min Complexity` over **admissible** representations. ⇒ **`F2` yields an admissible
decomposition, not a unique one.**

⭐ **The `τ ↔ obligation ↔ capability` relation is many-to-many** *(retirement forces `K4c-i` **and**
`K4c-ii`; `τ2` forces `K3a` **and** `K3b`)* — ⛔ **and no unique granularity follows from it.**

# 6. The subject-wise `5` loses an obligation

`P-17`'s converse test: *"`(link, value)` yields a **JUSTIFICATION** while `(link, reference)` yields a
**RESOLVABLE RELATION WITH CARDINALITY**. Same coordinates, different obligations ⇒ the coordinates do
not DETERMINE the capability."*

⭐⭐ **So the subject-wise 5 fails `SemanticAdequacy`** *(one of `[DEF-33]`'s five constraints)* ⇒
⭐⭐⭐ **it is an INDEX, not an admissible representation.** ⛔ **The 11/7/5 field therefore has fewer
live members than it appeared — but "how many remain" still depends on the algebra.**

# 7. `F3` — the equivalence relation, and why it cannot settle it alone

| candidate | position | status |
|---|---|---|
| identical **forcing witness** | ⭐ finest | ⭐ **`[DERIVED]` — `P-08` §19's structure** |
| identical countermodels ⊆ information requirement ⊆ retained-state distinction | ⭐⭐ **NESTED, not independent** | `[DERIVED]` |
| **mutual derivability** | coarsest | ⚠️ **`[REC]` — a modelling choice** |
| identical persistence obligation | co-extensive with *forcing witness* here | `[DERIVED]` |

$$\boxed{\textbf{⭐⭐ Each relation yields a partition, and } \mathbf{[DEF\text{-}33]\ does\ not\ privilege\ one} \textbf{ — it asks for } \arg\min \mathit{Complexity} \textbf{, and } \mathbf{Complexity\ is\ never\ defined\ for\ the\ persistence\ kernel.}}$$

⭐⭐⭐ **That undefined `Complexity` is the precise blocker** — sharper than *"the axis is unfixed"*.

# 8. `F4` — the obligable unit
⭐ **R has no actor model** — `P-24`: `current for <agent|process|lane>` returns **zero**; `P-22`'s
*holder* was refuted. ⛔ **No actor-based granularity without importing one, which is forbidden.** ⭐
`P-08` §19's *"smallest missing datum"* is the available unit — **one admissible choice.**

# 9. `11` / `7` / subject-wise `5` under `[DEF-33]`

| representation | `CapabilityClosure` | `SemanticAdequacy` | admissible? |
|---|---|---|---|
| ⭐ **11** *(witness partition)* | ✅ | ✅ | ⭐⭐ **YES** |
| **7** | ⚠️ superseded — `K4` sub-obligations unresolved | ⚠️ | ⭐ **NO — historically superseded** |
| **5** *(`P-08`)* | ⚠️ superseded | ⚠️ | ⭐ **NO — *"not preserved for continuity"*** |
| **5** *(subject-wise)* | ✅ | ⭐⭐⭐ **✗ — §6** | ⛔ **NO** |

⭐⭐ **So exactly one representation is currently admissible — but that is a fact about *what has been
constructed*, ⛔ not a proof that no other admissible algebra exists.** ⭐⭐⭐ **Theorem 10's `13` vs
`8` is precisely the warning: a second admissible algebra appeared when someone looked for one.**

# 10. Uniqueness
⛔ **`U1`** — the corpus fixes the **criterion**, ⛔ not the **representation**. ⛔ **`U2`** — no unique
derivation; `Complexity` is undefined here. ⭐⭐ **`U3` — SELECTED: several representations are
admissible in principle and only one has been constructed.** ⛔ **`U4`** — ⭐ **the choice is *not*
external convention: `[DEF-33]` gives an internal criterion. It is simply not yet evaluable.**

⭐ **`U3` and `U4` kept apart — and the distinction is load-bearing here.**

# 11. ⭐⭐ Attack on `P-47`

| | claim | verdict |
|---|---|---|
| **A** | *"all 11 cells are forced"* | ⭐ **HOLDS — within the witness representation** |
| **B** | *"set-inclusion minimality holds"* | ⭐ **HOLDS — relative to that representation** |
| ⭐⭐ **C** | *"the axis problem blocks cardinality minimality"* | ⭐⭐⭐ **CORRECT, and better named:** the blocker is ⭐ **an undeclared admissible algebra and an undefined `Complexity`** — `M-2`'s *"not a claim"* condition |
| **D** | *"`K2` is now forced"* | ⭐ **VERIFIED** — same R-level obligation and scope; ⛔ not reopened |

⚠️ **And `P-47` §13's *"what this did not prove"* should have included: *that a minimality claim is
even well-formed without a declared algebra*.**

# 12. ⭐⭐⭐ The finding that matters most

$$\boxed{\begin{array}{c}\textbf{Every minimality statement in } P\text{-}08 \textbf{ through } P\text{-}47 \textbf{ was made } \mathbf{WITHOUT\ DECLARING\ AN\ ADMISSIBLE\ ALGEBRA.}\\ \boxed{\textbf{By } M\text{-}2\textbf{'s own standard, those statements are } \mathbf{not\ claims} \textbf{ — ⭐ which is why the number kept moving.}}\end{array}}$$

⭐⭐ **`P-08`'s hedge was therefore better-judged than the forty artifacts that followed it.** ⛔ **And
this is not a refutation of `|K| = 11` — it is a statement about what kind of object `|K| = 11` is.**

# 13. Discipline
⛔ No count of documents, occurrences or surviving audits used as evidence. ⭐ **Stratification held:**
`[DEF-33]`/Theorem 10 are operator-kernel material, applied as `[DERIVED]`/`[ANALOGY ONLY]`; `K-1`/`K-2`
is frozen and not consumed; no S or D witness used. ⛔ **No actor model, bounded context or aggregate
manufactured.**

# 14. What this audit did NOT prove
⛔ That `[DEF-33]` **governs** the persistence kernel *(`[DERIVED]`, its home is the operator kernel)*.
⛔ That the witness representation is `\arg\min Complexity` — **`Complexity` is undefined here**.
⛔ That no second admissible representation exists — ⭐ **only that none has been constructed**.
⛔ Anything about **well-foundedness, terminality, acyclicity** — still **deferred**.

# 15. Status register
**`[EMP]`** ⭐⭐ **`[DEF-33]`** · **Theorem 10** · **`M-2`** *"a minimality claim without a fixed
admissible algebra is not a claim"* · *"you must define minimality before running the experiment"* ·
`P-09.2` *"revised lower bound (was 5)"* · `P-17`'s four findings · `kernel/`'s `extent`/`contents` and
*"Unproven"*.
**`[DERIVED]`** `[DEF-33]`'s applicability to the persistence kernel · the nesting of the equivalence
candidates · the subject-wise 5 fails `SemanticAdequacy`.
**`[EXP]`** `M-2` · Theorem 10 *(operator kernel)*.
**`[REFUTED]`** ⭐⭐ **my drafted verdict `B`** · *"each τ forces exactly one cell"* · the subject axis
as generative.
**`[ANALOGY ONLY]`** the `13`/`8` counts · `kernel/`'s `extent`/`contents`.
**`[REC]`** *mutual derivability* · `|K| = 11`'s governance status.
**`[OPEN]`** ⭐⭐⭐ **the admissible algebra for the persistence kernel** · **`Complexity`'s
definition** · witness-set completeness *(`τ4`/`Q3`)* · everything carried from `P-29`–`P-47` ·
⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**

# 16. ⛔ Backlog — no new item
⭐ A research under-specification, ⛔ **not an operational defect**. `EKS-13` covers the one live
exposure; ⛔ **no ticket merely because an open question remains** *(§15)*.

### 17. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{What is the } \mathbf{admissible\ representation\ algebra} \textbf{ for the PERSISTENCE kernel — and is } \mathit{Complexity} \textbf{ definable over it at all?}}$$

⭐⭐ **`[DEF-33]` makes minimality evaluable only relative to a declared algebra with a defined
`Complexity`.** ⭐⭐⭐ **Lane T has never declared either — so `|K| = 11` is, by the corpus's own
standard, a well-evidenced *lower bound under one construction*, and `M-2` says a minimality claim
about it is not yet a claim at all.**

```
C — [OPEN]. SEVERAL REPRESENTATIONS ARE ADMISSIBLE, NONE PRIVILEGED BY THE CORPUS — AND THE CORPUS SAYS
SO EXPLICITLY. |K| = 11 UNCHANGED, STILL [REC].

THIS AUDIT WAS DRAFTED WITH VERDICT B AND IS CORRECTED TO C ON THE COMMISSIONER'S TWO MID-AUDIT
POINTERS. My F1 search had looked for GRANULARITY vocabulary and never for a MINIMALITY DEFINITION —
and the corpus has one:

  [DEF-33]  "Kernel minimality is NOT min |operators|."  It is  K_min = argmin_K Complexity(K)
            subject to CapabilityClosure, SemanticAdequacy, Preservation, InvariantPreservation,
            TransitionCompleteness.

  Theorem 10  "Operator-count minimality is REPRESENTATION-RELATIVE. Under algebra A_0, |K_min| = 13.
              Under another admissible algebra, 8."

  M-2 [EXP]   "These are NOT conflicting measurements — they are solutions to DIFFERENT OPTIMIZATION
              PROBLEMS. A MINIMALITY CLAIM WITHOUT A FIXED ADMISSIBLE ALGEBRA IS NOT A CLAIM."

So the 11/7/5 problem is the 13/8 phenomenon, already characterised months ago. My drafted verdict B —
"the witness set fixes the granularity" — is REFUTED: the witness partition is ONE ADMISSIBLE
REPRESENTATION, not the privileged one.

THE FIRST POINTER ALSO PAID OFF, at a different level. kernel/ carries "the smallest authoritative
KnowledgeCore boundary", "the minimum set of domain CONCEPTS", and the extent/contents distinction —
all about the K-1 DDD kernel, where "KnowledgeAggregate is the smallest boundary" is itself marked
"Unproven". [ANALOGY ONLY]; K-1/K-2 is governance-frozen and was not consumed.

P-17's prior result still holds and now reads differently: the axes INDEX the cells and do not GENERATE
them, so no axis fixes the granularity — but neither do the witnesses, because [DEF-33] asks for
argmin Complexity over ADMISSIBLE representations, and Complexity is never defined for the persistence
kernel. THAT UNDEFINED COMPLEXITY IS THE PRECISE BLOCKER, sharper than "the axis is unfixed".

THE SUBJECT-WISE 5 IS ELIMINATED ON THE CORPUS'S OWN CRITERION: P-17 showed (link, value) yields a
JUSTIFICATION while (link, reference) yields a RESOLVABLE RELATION WITH CARDINALITY, so collapsing them
fails SemanticAdequacy — one of [DEF-33]'s five constraints. And 5 and 7 are superseded derivation
stages, not rivals. Exactly one representation is currently admissible — but that is a fact about what
has been CONSTRUCTED, not a proof that no other exists. Theorem 10's 13-versus-8 is the standing
warning: a second admissible algebra appeared as soon as someone looked.

THE FINDING THAT MATTERS MOST: every minimality statement in P-08 through P-47 was made WITHOUT
DECLARING AN ADMISSIBLE ALGEBRA. By M-2's own standard those statements are NOT CLAIMS — which is why
the number kept moving. P-08's hedge was better-judged than the forty artifacts that followed it. This
does not refute |K| = 11; it says what kind of object it is.

Pre-registered D; drafted B; the answer is C. Both wrong. Record 6 of 12.

ONE NEXT UNRESOLVED QUESTION: What is the ADMISSIBLE REPRESENTATION ALGEBRA for the persistence kernel —
  and is Complexity definable over it at all? Lane T has never declared either.

NO NEW BACKLOG ITEM — NO CELL CHANGE — NO SCHEMA v3 — NO ARCHITECTURE — NO CANONICALIZATION — NO
GOVERNANCE CHANGE — NO three_model_convergence INSPECTION.
```
