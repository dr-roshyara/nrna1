# `P-15` — Model Replacement Transition & `K2` Derivation

**2026-09-08 · Lane T.** After [`31` `P-14`](./31-P14-SEMANTIC-REINTERPRETATION-IDENTITY-ADJUDICATION.md).

> **The question:** *does model replacement introduce a persistence distinction the current kernel
> cannot represent, and what becomes of `K2`-A?*
>
> ⛔ **`P-13`, `P-14`, `Q1`/`INTAKE-002`, `W6` not reopened · Schema v2 unmodified · v3 not begun · no
> architecture, model-version storage, event sourcing, temporal storage, model registry or database
> representation · no model-equivalence or semantic-equivalence claim · no unified Kernel · no
> four-model convergence · 3MC / MD-018 untouched · no global reclassification · implementation and
> DDD are never ontology.**

# 1. Starting state
`R0` **`[STIPULATED]`** · `Q1a` **YES `[STIPULATED]`**, `Q1b` **`[DERIVED]`** · **`W6` = `C3`**,
corpus-state denotation is `K3b` warrant content · **`Q2` = SAME CANDIDATE `[DERIVED]`**, no new
capability, borne by `K4a` · **`K2`-A's sole remaining dependency = model replacement** · **kernel 11
cells** · third axis, role overload, `Q4`, kernel identity, equivalence, Claim B, aggregate existence,
`O-INT002-1` all `[OPEN]` · `F-4`/`F-5` unrepaired · no architecture · Schema v3 not begun.

⭐ **A qualification carried forward, as instructed:** `P-14`'s *"reinterpretation is an assertion-level
event"* is established **for the witnessed case**. ⛔ **It is not a universal claim about every
conceivable reinterpretation**, and the *"can, not must"* modality stands.

## Evidence discipline
✅ **admissible:** `brainstorming/` **minus** `verification/gap-discovery/` · corpus code · previously
admitted evidence.
⛔ **inadmissible:** Lane T adjudications · `theory-extraction/` records · `verification/gap-discovery/`
· Lane M Phase 5C/5D without intake · implementation resemblance · DDD · mathematical analogy.

---

# 2–3. Operational definition — six candidates, four dissolve

⛔ **"Model replacement" is not taken as a primitive.**

| | reading | verdict |
|---|---|---|
| **M1** | **refinement** — `M₂` contains/improves `M₁` without invalidating it | ⭐ **`τ3`** — new definition added; `[EMP]`: corpus code carries **four readings of `zero`** under one name (`phaseC.py:25`) |
| **M2** | **correction** — `M₂` replaces an **invalid** `M₁` | ⭐ **`τ10` / `K4a` supersession** — the old model is a superseded assertion set |
| **M3** | **reinterpretation** — same underlying model, different interpretation | ⭐⭐ **already CLOSED by `P-14`** — same candidate, assertion-level, `τ10` |
| **M4** | **substitution** — a genuinely different model for the same domain | ⭐ **live — §4** |
| **M5** | **population replacement** — the objects/classes represented change | ⭐⭐ **`τ11`**, whose arities `P-09.1` already classified |
| **M6** | ⭐⭐⭐ **framework replacement** — the **representational framework** itself changes | ⭐⭐⭐ **the only reading that could reach `K2`-A**, because only it could re-express a definition's **text** |

$$\boxed{\begin{array}{c}\textbf{FOUR of six dissolve into already-classified transitions } (\tau3,\ \tau10,\ P\text{-}14,\ \tau11).\\ \boxed{\textbf{Only } \mathbf{M4} \textbf{ and } \mathbf{M6} \textbf{ remain, and only } \mathbf{M6} \textbf{ bears on } K2\text{-A.}}\end{array}}$$

⛔ **They are NOT six transition classes.**

---

# 4. ⭐⭐⭐ Reconstructing `P-09.1`'s `(all) → (all)`

## The ten questions

| # | question | answer |
|---|---|---|
| 1 | the **predecessor population**? | the identities established before the replacement |
| 2 | the **successor population**? | those established after |
| 3 | ⭐⭐ **is "all" an actual population or shorthand?** | ⭐⭐⭐ **SHORTHAND — §6 proves it** |
| 4 | does **every** predecessor have a successor? | 🔴 **no** — `H-C` |
| 5 | can successor **cardinality** differ? | ✅ yes |
| 6 | can predecessors **disappear**? | ✅ yes ⇒ `1→0`, and `Q1a` now permits it |
| 7 | can **new** candidates appear? | ✅ yes ⇒ `τ9` establishment |
| 8 | can **definitions** survive a model change? | ⚠️ **`[OPEN]`** — depends on `M6`, unwitnessed |
| 9 | can **candidates** survive a model change? | ⭐ ✅ **yes** — `H-A` |
| 10 | ⭐ can the model change with **no** candidate identity changing? | ⭐⭐ ✅ **YES — and this is decisive** |

⛔ **`model replacement ⇒ τ11` is NOT assumed** — and question 10 refutes it in general.

# 5. Minimal countermodels

| | history | decomposition |
|---|---|---|
| **`H-A`** | `M₁ → M₂`, **same candidates, same identities**, different framework | ⭐⭐ **`n` parallel `1→1` IDENTITY-PRESERVING relations** ⇒ `cont = id` ⇒ **ITEM-LEVEL, not `τ11` at all** (`P-09.3`'s criterion) |
| **`H-B`** | `M₁/A,B → M₂/C,D`, identity does not survive | ⭐ **2 × `1→0` retirements + 2 × `0→1` establishments** ⇒ **`K4c-i`/`K4c-ii` + `K3b` + `τ9`**, all in force since `INTAKE-002` |
| **`H-C`** | `A,B,C → A,D,E`, partial continuity | ⭐ **`A`: item-level · `B`,`C`: `1→0` · `D`,`E`: `τ9`** ⇒ **fully decomposed** |
| **`H-D`** | two histories, **identical current model, candidates, assertions, warrants and kernel state**, different model histories | ⭐ **§8** |

# 6. ⭐⭐⭐ Transition arithmetic — `(all)→(all)` is a macro-description

Represent every witness as a population mapping `P → Q` and classify **per element**:

$$\boxed{\begin{array}{ll}1 \to 1 & \textbf{identity preserved} \Rightarrow \textbf{ITEM-LEVEL, not a } \tau11 \textbf{ member at all}\\ 1 \to 0 & \textbf{retirement} \Rightarrow K4c \textbf{ (}\mathbf{[DER\text{-}S{:}Q1a]}\textbf{)} + K3b\\ 0 \to 1 & \textbf{establishment} \Rightarrow \tau9\\ 1 \to n & \textbf{split} \Rightarrow K4c \textbf{, per-claim warranted reassignment}\\ n \to 1 & \textbf{merge} \Rightarrow K4c\end{array}}$$

$$\boxed{\begin{array}{c}\textbf{Every constructible } n \to n \textbf{ mapping is a UNION of these five per-element relations, each already classified.}\\[6pt] \boxed{\begin{array}{l}(\textbf{all}) \to (\textbf{all}) \textbf{ IS NOT AN ARITY AND NOT A PRIMITIVE TRANSITION.}\\ \textbf{It is an AGGREGATE DESCRIPTION of a set of per-element transitions.}\end{array}}\end{array}}$$

⭐⭐ **This corrects `P-09.1` §2, which listed `(all)→(all)` as a fourth arity alongside `1→0`, `1→n`
and `n→1`.** The genuine arities are **per-element**, and the fourth entry was a **level confusion** —
an aggregate description sitting in a list of element relations. ⛔ **`P-09.1` is not rewritten.**

⭐ **And it is the same error class the programme keeps catching:** `P-09.3` found *arity individuates
transitions but parameterizes capabilities*; here an **aggregate** was mistaken for an **element**
relation.

---

# 7. Model identity vs object identity

| identity | survives a model change? | note |
|---|:--:|---|
| ⭐ **model identity** | — | ⭐⭐ **there is NO model cell in the kernel.** A model is not a persistence subject; §8 shows what carries its trace |
| **candidate** | ✅ **can** — `H-A` | ⛔ `different model ⇒ different candidate` is **`[REFUTED]`** by question 10 |
| **definition** | ⚠️ `[OPEN]` under `M6` | §9 |
| **association** | ✅ can | changes only via `τ2` |
| **assertion** | 🔴 **superseded** | `M2` is exactly this |
| **warrant** | **added** | the replacement's justification |
| **implementation** | ✅ | versioned outside our boundary |

$$\boxed{\textbf{different model} \not\Rightarrow \textbf{different candidate} \qquad \textbf{same model NAME} \not\Rightarrow \textbf{same model}}$$

⭐ **The second is `[DERIVED]` from `P-07`'s label-vs-entity rule** — a name is not truth-apt, so a
stable model name establishes nothing.

# 8. Historical recoverability

| | `H1` | `H2` |
|---|---|---|
| history | `M₁ → M₂` | `M₀ → M₂` |
| current state | ⭐ **identical** | ⭐ **identical** |

**Must the estate distinguish them?** ⭐ **Yes — if the transition contained an adjudication** (the
`P-08` §17 shape: a re-expression could be wrong, so it is a legitimate revisit target).

**What is the lost datum, and who bears it?**

| candidate bearer | verdict |
|---|---|
| **model identity** | ⭐ 🔴 **no such cell** — and none is created here |
| ⭐⭐ **predecessor population + transition warrant** | ⭐⭐⭐ ✅ **`K3a` (prior value) + `K3b` (warrant)** — the per-element mappings of §6 are exactly non-monotone changes on the surviving items |
| **candidate continuity** | ✅ `K1`, for the `1→1` elements |
| **assertion supersession** | ✅ `K4a`, for `M2` |
| **provenance** | ⭐ **definition source is a VALUE on a definition** ⇒ `K3a`-bearable, `K5`-checkable |

$$\boxed{\begin{array}{c}\textbf{A predecessor MODEL appears as PROVENANCE CONTENT on the items it produced — never as a model entity.}\\ \boxed{\textbf{Model provenance IS definition provenance. ⛔ No model-history capability.}}\end{array}}$$

⛔ **Full model-history retention is NOT assumed**, and `P-08` §7's rule governs: retention only where
loss changes a required distinction.

# 9. The corpus obligation — no new search needed

⭐ **A search would be redundant, and that is justified rather than assumed:** the `step-183` analogue is
**already admitted evidence**, cited in `P-09` §8 and `P-09.1` §3.4 —
`brainstorming/phase_measure_theory/20260826-004302_important-correction-to-the-model.md` §9:

> *"KnowledgeOS cannot simply preserve **facts**. It may need to preserve the **history of how the state
> representation itself evolved**… At `t₁`… four dimensions… `t₂`… three additional… `t₃`, **one
> dimension was split into two**… `t₄`, **a previous determination was revised**."*
> *"But I would still **not declare this a Kernel requirement yet**."*

| the corpus's own four events | bearer |
|---|---|
| `t₁`/`t₂` dimensions considered / added | ⭐ **`τ9` establishment** |
| `t₃` **split** | ⭐ **`K4c`** — `1→n` |
| `t₄` **determination revised** | ⭐ **`K4a`** supersession + **`K3b`** warrant |

$$\boxed{\begin{array}{c}\textbf{All four corpus-named events are ALREADY BORNE. And the corpus DECLINES to make the requirement a kernel obligation.}\\ \textbf{⛔ *"The model evolved"* is not, by itself, a persistence obligation.}\end{array}}$$

---

# 10. The full capability criterion — applied to `M6`, the only live route to `K2`-A

**The minimal pair:**

| | `H1` | `H2` |
|---|---|---|
| definition `D` | text `T₁` under framework `F₁`, **re-expressed** as `T₂` under `F₂` | text `T₂` **from the start** under `F₂` |
| current text · status · associations | ⭐ **identical** | ⭐ **identical** |

| condition | |
|---|:--:|
| 1 required distinction *(a re-expression could be a mistranslation — a revisit target)* | ✅ **yes** |
| 2 can be lost | ✅ |
| 3 ⭐⭐ **all existing closed capabilities identical?** | ⭐⭐⭐ 🔴 **NO — in `H1`, `K3a` holds the prior value `T₁` and `K3b` its warrant; in `H2` both are EMPTY** |
| 4 kernel cannot represent it | 🔴 **it can** |
| 5 not merely representation/mechanism/… | — |

$$\boxed{\textbf{Condition 3 FAILS. ⛔ NO NEW CAPABILITY — a text change is a NON-MONOTONE change on a definition, and } K3a \textbf{ records prior values.}}$$

⛔ **Not tested against `K2` alone, nor `K5` alone, nor identity alone** — the `P-12`/`P-13` mistake is
not repeated.

# 11. ⭐⭐⭐ `K2`-A — closed by a dilemma

`P-09.3` §6 left `K2`-A underdetermined **because no transition in `𝒯_KOS` varied a definition's text**,
and named model replacement as the only candidate that might.

$$\boxed{\begin{array}{ll}\textbf{If } M6 \textbf{ NEVER occurs} & \textbf{there is no admissible counterexample} \Rightarrow K2 \textbf{ does not decompose}\\[4pt] \textbf{If } M6 \textbf{ DOES occur} & \textbf{§10 shows } K3a + K3b \textbf{ bear it} \Rightarrow K2 \textbf{ still does not decompose}\end{array}}$$

$$\boxed{\begin{array}{c}\textbf{BOTH BRANCHES GIVE THE SAME ANSWER.} \quad \boxed{K2\text{-A is } \mathbf{CLOSED}.}\\[4pt] \textbf{The question was never decidable in favour of decomposition — } \mathbf{M6}\textbf{'s existence is irrelevant to it.}\end{array}}$$

⭐⭐ **And the reason is now clear: a definition's TEXT is a VALUE like any other field.** Its change is a
`K3a`-bearing transition, not evidence of a content/state split inside `K2`. ⭐ The **converse**
direction `K2`-A required — state varying while content is fixed — is witnessed (`W3`), but it lands in
`K2` while the content direction lands in `K3a`. **The two required directions do not both sit inside
`K2`, so no split is available.**

⇒ **`K2` remains ONE capability**, and — from `INTAKE-001` — **independently necessary `[DER-S:R0]`.**

# 12. Mathematical sanity check
⚠️ **Structural tests only; no mathematical equivalence imported.**

| test | result |
|---|---|
| $M_1 \neq M_2 \Rightarrow O_1 \neq O_2$? | ⭐ 🔴 **NO** — `P-14`'s *estimate ⇏ parameter* rule, one level up: a changed **model** is not a changed **object** |
| $M_1 \cong M_2 \Rightarrow M_1 = M_2$? | 🔴 **no** — **isomorphism is not identity** |
| **reparameterization vs new object** | ⭐ `M6` is a **reparameterization** of the same content |
| **structure vs interpretation** | kept apart — `M3` is interpretation, `M6` is structure |
| ⛔ **`≡_sem`** | ⭐⭐ **NOT used, not implied, not claimed. It belongs to the unresolved equivalence question (`Q7`)** |

# 13. Statistical sanity check

| scenario | what changed |
|---|---|
| ⭐ **same data + new model** | the **model** and the **estimate**. ⛔ **NOT** the observation, ⛔ **NOT** the target phenomenon — ⭐ **this is `M6`** |
| **new data + same model** | the **observation** ⇒ `P-08` §5.4's re-measurement regime |
| **new model + new target population** | **both** ⇒ decomposes into `M6` **plus** `τ11` |

$$\boxed{\textbf{A changed model does NOT imply a changed empirical object — } P\text{-}14\textbf{'s result, one level up.}}$$

⛔ **The three are not conflated**, and `data · observation · measurement · model · estimate ·
parameter · interpretation · warrant · validity` are held apart throughout.

# 14. DDD cross-check
⚠️ **Corroboration only.** Behaviourally, model replacement is **policy-shaped**: a whole descriptive
apparatus swapped out while the entities it describes **survive** — which corroborates §7's
`different model ⇏ different candidate`. ⭐ **DDD supplies no category here and names no model
lifecycle.** ⛔ **No bounded context · no aggregate · no Context Mapping · no Entity/Value-Object
assignment for "model".**

---

# 15. Kernel impact

$$\boxed{\textbf{KERNEL REMAINS 11 CELLS. No cell added, removed, or changed in status.}}$$

⭐ **Transition novelty ≠ persistence-capability novelty.** `M4` and `M6` are the two readings that
survive as *transitions*, and **neither produces a capability**: `M4` decomposes per element (§6),
`M6` is `K3a`-borne (§10).

⭐ **One `[OPEN]` non-cell status improves in precision, not in count:** the third axis remains the sole
open structural question, and ⭐ **`(all)→(all)` no longer counts as an unclassified transition class** —
it was never a class.

# 16. Decision

| | |
|---|---|
| **`M1`–`M3`, `M5`** | ⭐ **absorbed** into `τ3`, `τ10`, `P-14`, `τ11` |
| **`M4`** substitution | ⭐ **absorbed** — per-element decomposition |
| **`M6`** framework replacement | ⭐ **a transition, `[OPEN]` as to occurrence; NO capability** — `K3a`+`K3b` |
| ⭐⭐ **`K2`-A** | ⭐⭐⭐ **CLOSED — by dilemma; both branches agree** |
| ⭐ **`(all)→(all)`** | ⭐⭐ **a MACRO-DESCRIPTION, not a primitive.** Corrects `P-09.1` §2 |
| **kernel** | **11 cells** |

# 17. Evidence ledger

| # | witness | admissible? | bears |
|---|---|:--:|---|
| 1 | ⭐ `phase_measure_theory/20260826-004302…md` §9 — the four-event representation history **and** *"I would still not declare this a Kernel requirement yet"* | ✅ **corpus** | all four events already borne; the corpus's own restraint |
| 2 | `phaseC.py:25` *"the four readings"* of `zero` | ✅ **corpus code** | `M1` refinement = `τ3`; one name, several readings |
| 3 | `P-14`'s Zero-meaning-change record | ✅ **corpus** | `M3` closed; `different description ⇏ different object` |
| 4 | `minimum_implementable.py` — `InvariantReg` `"NOT ENUMERATED"` | ✅ **corpus code** | candidate continuity ⇏ definition continuity |
| — | ⛔ `𝒦`-vs-`K` · `D-03`/`D-05` · `ℐ`/`𝓘` · this lane's withdrawals | 🔴 **Lane T** | **excluded; premises nowhere** |
| — | ⛔ Lane M Phase 5C/5D | 🔴 **no intake** | **not consumed** |

⭐ **No new external-lane conclusion was needed, so no intake record is requested.**

# 18. Remaining dependencies
⭐⭐ **`K2`-A is no longer among them.** Remaining: **third axis** `[OPEN]`, witness absent · **role
overload** `[OPEN]`, one direction · **`M6`'s occurrence** `[OPEN]` *(irrelevant to `K2`-A)* ·
**`Q3`/`Q8`** performable exercise of `K4c` · **`Q4`** · **kernel identity** · **equivalence** *(`Q7`,
⛔ untouched)* · **Claim B** · **aggregate existence** · **`O-INT002-1`** · **`O-INT001-1`'s governance
rule** · **`W6`'s mechanism `[ARCH]` and its live empirical failure** · `F-4`/`F-5`.

# 19. Decision boundary

**`[EMP]`** the corpus's four-event representation history with its explicit refusal to make it a kernel
requirement · **four readings of `zero`** under one name · the Zero meaning-change with **zero new
rows** · `InvariantReg` **`"NOT ENUMERATED"`**.

**`[DERIVED]`** ⭐⭐⭐ **`(all)→(all)` is an aggregate description, not an arity — every constructible
`n→n` is a union of `1→1`, `1→0`, `0→1`, `1→n`, `n→1`, each already classified** *(corrects `P-09.1`
§2)* · ⭐⭐ **`K2`-A is CLOSED by dilemma — `M6`'s existence is irrelevant** · ⭐ **a definition's text is
a value; its change is `K3a`-bearing, not a `K2` sub-part** · ⭐ **the two directions `K2`-A required do
not both sit inside `K2`** · ⭐ **model provenance is definition provenance; there is no model cell and
none is created** · ⭐ **`different model ⇏ different candidate`; `same model name ⇏ same model`** ·
**four of six readings dissolve; `M6` fails condition 3** · **transition novelty ≠ capability novelty**.

**`[OPEN]`** `M6`'s occurrence · third axis · role overload · everything in §18.

**`[ARCH]`** all mechanism — model versioning, registries, storage. ⛔ **Nothing promoted for
convenience; `[DER-S:*]` untouched by this artifact.**

---

```
MODEL REPLACEMENT — K2-A CLOSED — NO NEW PERSISTENCE CAPABILITY — KERNEL REMAINS 11 CELLS.

HOW IT WAS SETTLED:
  Six candidate readings; FOUR dissolve into already-classified transitions — refinement is tau-3,
  correction is tau-10, reinterpretation was closed by P-14, population replacement is tau-11. Only
  substitution (M4) and FRAMEWORK replacement (M6) survive, and only M6 could reach K2-A, because only
  it could re-express a definition's TEXT.

  THE DECISIVE TEST — transition arithmetic: every constructible model-replacement mapping is a UNION
  of per-element relations 1->1 (identity preserved, hence ITEM-LEVEL and not a tau-11 member at all),
  1->0 (retirement, K4c+K3b), 0->1 (establishment, tau-9), 1->n (split) and n->1 (merge) — all already
  classified. So (all)->(all) IS NOT AN ARITY AND NOT A PRIMITIVE TRANSITION; it is an AGGREGATE
  DESCRIPTION. This corrects P-09.1 §2, which listed it as a fourth arity alongside 1->0, 1->n and
  n->1 — a level confusion, an aggregate description sitting in a list of element relations. P-09.1 is
  not rewritten. Same error class the programme keeps catching: P-09.3 found arity individuates
  transitions but parameterizes capabilities; here an aggregate was mistaken for an element relation.

  K2-A IS CLOSED BY A DILEMMA, and both branches agree: if M6 never occurs there is no admissible
  counterexample, so K2 does not decompose; if M6 does occur, the minimal pair FAILS CONDITION 3 —
  in the re-expression history K3a holds the prior text and K3b its warrant, while in the born-that-way
  history both are EMPTY — so K3a+K3b bear it and K2 still does not decompose. M6's EXISTENCE IS
  IRRELEVANT TO K2-A. The reason: a definition's TEXT is a VALUE like any other field, so its change is
  a K3a-bearing non-monotone transition, not evidence of a content/state split. And the two directions
  K2-A required do not both sit inside K2 — the state direction is witnessed (W3) inside K2, the content
  direction lands in K3a. K2 remains ONE capability, independently necessary [DER-S:R0].

  THE CORPUS OBLIGATION: no new search was needed and the redundancy is justified — the step-183
  analogue is already-admitted evidence, and its four named events (dimensions considered/added, one
  dimension SPLIT into two, a previous determination REVISED) map to tau-9, K4c and K4a+K3b
  respectively. The corpus then DECLINES to make the requirement a kernel obligation: "I would still
  not declare this a Kernel requirement yet." "The model evolved" is not by itself a persistence
  obligation. A predecessor MODEL appears as PROVENANCE CONTENT on the items it produced, never as a
  model entity — model provenance IS definition provenance, and there is no model cell.

  SANITY CHECKS: M1 != M2 does not imply O1 != O2 (P-14's estimate-vs-parameter rule one level up);
  isomorphism is not identity; same data + new model changes the model and the estimate but neither the
  observation nor the target phenomenon. DDD is policy-shaped corroboration only — no bounded context,
  no aggregate, no Context Mapping. ≡_sem is NOT used, NOT implied and NOT claimed; it remains Q7.

TRANSITION NOVELTY != PERSISTENCE-CAPABILITY NOVELTY: M4 and M6 survive as transitions and neither
  produces a capability. K2-A leaves the residual queue; the third axis remains the sole open
  structural question, and (all)->(all) no longer counts as an unclassified transition class because it
  was never a class.

NO ARCHITECTURE SELECTED — SCHEMA v3 NOT BEGUN — 3MC UNTOUCHED.
```
