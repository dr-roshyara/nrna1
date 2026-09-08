# `P-21` — Withdrawal vs Supersession: Transition Identity Audit

**2026-09-08 · Lane T · transition-semantics audit.** After [`37` `P-20`](./37-P20-GROUND-STRATIFICATION-AND-KERNEL-CARDINALITY-AUDIT.md).

> ⛔ **`P-08`, `P-18`, `P-19`, `P-20`, Schema v2 unmodified · no Schema v3 · no architecture or
> persistence technology · 3MC untouched · no kernel cell added or deleted · DDD and mathematical
> notation are never ontology · implementation is never theory · no silent repair.**
>
> ⭐ **The gap that must be crossed:** *different outcomes* ⇏ *different primitives*. The chain
> **history distinction → information requirement → transition semantics → transition taxonomy →
> persistence consequence** is kept in five separate steps and never collapsed into one inference.

# 1. Starting state — frozen
**11 cells** · **`K4a-ii` independent for `n ≥ 2`** · **`K4b-ii` independent** · ⭐ **`K2` already
retains definition source / current ground as a field value** · **`K2g` is not a cell** · **no
10-vs-11 issue** · `K4b-ii`'s **corroboration arm constructible, not corpus-attested** · `R0`, `Q1a`
`[STIPULATED]` · **`K1` by presupposition argument** · **`K5` restated** · ⛔ **no canonical freeze, no
architecture, no Schema v3.**

# 2. What individuates a transition — derived before classifying

$$\tau : S_{\text{before}} \to S_{\text{after}}, \textbf{ with EIGHT components kept apart}$$

**pre-state · post-state · event semantics · successor relation · identity preservation · status change
· warrant · persistence obligation.**

⭐⭐ **The programme's own criterion, from `P-09.1` §1:** a transition is individuated by its
**successor relation** — `≺ = id` ⇒ item-level, `≺ ≠ id` ⇒ `τ11`. ⚠️ **That criterion alone would settle
this question by construction, which is exactly the too-easy answer.** So it is **used as a starting
point and then tested**, not applied.

$$\boxed{\textbf{⛔ A transition type is NOT identified by its persistence consequences (§8's rule), nor by its post-state, nor by its arity.}}$$

---

# 3. The two canonical cases

## Case `S` — supersession `p₁ ⟶ p₂`
| | |
|---|---|
| **before** | the holder's current assertion is `p₁` |
| **after** | the holder's current assertion is `p₂`; **`p₁` retained** |
| **changed** | the holder's current-assertion field · a **relation** `p₂ ⊐ p₁` is asserted |
| **unchanged** | `p₁`'s **text and identity** · the holder's identity |
| **which identity survives** | ⭐ **both** — `p₁` is retained (`K4a-i`) |
| ⭐ **is successorhood intrinsic or derived?** | ⭐⭐ **§5 shows it is NEITHER a mere pointer NOR derivable — it is an asserted CLAIM** |

## Case `W` — withdrawal without successor `p₁ ⟶ ∅`
| | |
|---|---|
| **before** | current assertion `p₁` |
| **after** | ⭐ the holder has **no** current assertion for that question; **`p₁` retained** |
| **changed** | the holder's current-assertion field · `p₁`'s **operative status** |
| **unchanged** | `p₁`'s **text and identity** |
| ⭐⭐⭐ **is the identity retired?** | ⭐⭐ **NO — see §4. This is the audit's pivotal finding** |
| **is *"withdrawn"* a state or an event?** | ⭐ **`[EMP]` an EVENT that establishes a STATE** — §4 |

---

# 4. ⭐⭐⭐ Auditing *"no successor"* — and refuting the `1→0` premise

**Four readings of `no successor`, which §14 requires be kept apart:**

| | reading | is this what withdrawal is? |
|---|---|---|
| **A** | an **explicit semantic state** | ⭐⭐⭐ ✅ **YES — `[EMP]`.** `INV-9` was **explicitly withdrawn**, with a warrant naming the defect *("wrong scope")*. A recorded **act** |
| **B** | mere **absence of a successor relation** | 🔴 **no** |
| **C** | a transition to an **empty successor set** — `1→0` | ⭐⭐ 🔴 **NO — refuted below** |
| **D** | merely **lack of evidence** that a successor exists | 🔴 **no** |

$$\boxed{\neg\text{Successor}(p) \;\not\equiv\; \text{Withdrawn}(p) \quad \textbf{— reading B/D is an ABSENCE; withdrawal is reading A, an ACT.}}$$

⭐⭐ **Why this matters for `P-20`:** had withdrawal been only **B** or **D**, `P-20` §6's refutation of
the `n=2` reduction would have rested on an **absence**. ⭐ **It rests on an act. `P-20`'s correction is
therefore SAFE — and strengthened.**

## ⭐⭐⭐ Withdrawal is **not** `1→0`

`[EMP]` **`P-09.1` §3.3: the estate practises retirement-WITH-RETENTION and has never practised
deletion.** So `p₁` **does not leave the population** — `K4a-i` retains it.

$$\boxed{\begin{array}{c}\textbf{What changes is not the assertion's EXISTENCE but the HOLDER's CURRENT-ASSERTION FIELD.}\\[4pt] \boxed{\textbf{So withdrawal is a FIELD CHANGE on the holder plus RETENTION — NOT a } 1\to0 \textbf{ population transition.}}\end{array}}$$

⚠️ ⭐ **And this does not contradict `P-07` §8** (*"assertions are superseded, never updated"*): the
assertion's **content** is never edited. What is updated is the **holder's field**, which is `K2`'s
subject, not the assertion's.

---

# 5. ⭐⭐⭐ The parameterized schema — and why it is LOSSY

**Attempt the strongest unification:** `Retire(p, reason, successor?)`, with `successor = p₂` for `S`
and `none` for `W`.

⭐ It looks lossless. **Test whether `successor?`'s value changes the semantic KIND:**

| | |
|---|---|
| ⭐ **is *"successor = `p₂`"* the same claim as *"`p₂` supersedes `p₁`"*?** | ⭐⭐⭐ **NO.** `P-20` §6 constructed precisely the case where **`p₁` is withdrawn AND `p₂` is independently asserted** — there, `p₂` follows `p₁` **without replacing its role** |
| ⇒ what does the schema fail to express? | ⭐⭐ **the ROLE-REPLACEMENT CLAIM: that `p₂` answers the same question `p₁` answered** |

$$\boxed{\begin{array}{c}\textbf{The schema } \mathit{Retire}(p,\ \mathit{reason},\ \mathit{successor}?) \textbf{ is LOSSY: it cannot separate}\\ \textit{"retired, and } p_2 \textbf{ came next"} \textbf{ from } \textit{"retired, and } p_2 \textbf{ REPLACES it."}\end{array}}$$

⭐ **Repairing it requires a second parameter** — `Retire(p, reason, successor?, replaces?)` — ⭐⭐ **and
`replaces?` is a SEMANTIC-KIND MARKER, not an incidental value.** ⇒ **the unification is
REPRESENTATIONAL only** (`P-20` §12's lesson, reapplied).

⛔ **And a lossless common representation would not have proved one transition anyway.**

# 6. State-transformation equivalence

| | `H1` supersession | `H2` withdrawal + independent assertion |
|---|---|---|
| **post-state** | current `= p₂`, `p₁` retained | ⭐ **current `= p₂`, `p₁` retained — IDENTICAL** |

$$\boxed{\textbf{The post-state CANNOT distinguish them. The difference lies in EVENT SEMANTICS, and it surfaces only in HISTORY.}}$$

# 7. Graph-theoretic classification — and the arity question answered

| | naive arity | corrected |
|---|---|---|
| **supersession** | `1→1` | ⭐ a **field change** on the holder **+ a relation** |
| **withdrawal** | `1→0` | ⭐⭐ **NOT `1→0`** — §4. A **field change** on the holder, with `p₁` retained |

$$\boxed{\begin{array}{c}\textbf{The question } \textit{"is withdrawal the } 1\to0 \textbf{ instance and supersession the } 1\to1 \textbf{ instance of one retirement transition?"}\\ \textbf{IS MIS-POSED: neither is a population transition at the assertion level.}\\[6pt] \boxed{\textbf{So ARITY ALONE IS INSUFFICIENT — and it is insufficient because its premise is FALSE, not merely too coarse.}}\end{array}}$$

# 8. Persistence obligations compared

| | supersession | withdrawal |
|---|---|---|
| previous assertion retained | ✅ `K4a-i` | ✅ `K4a-i` |
| holder's current assertion | ✅ `K2` | ✅ `K2` |
| warrant | ✅ `K3b` | ✅ `K3b` |
| ⭐ **role-replacement relation** | ⭐⭐ ✅ **`K4a-ii`** | ⭐ 🔴 **absent** |

$$\boxed{\begin{array}{c}\textbf{The obligations are IDENTICAL except for } K4a\text{-ii.}\\ \boxed{\textbf{⛔ And that difference does NOT prove two transitions — §8's own rule. It is INPUT to §15, not a conclusion.}}\end{array}}$$

# 9–10. Minimal pair and information loss

| # | question | answer |
|---|---|---|
| 1 | pre-states identical? | ✅ current `= p₁` |
| 2 | post-states identical? | ⭐ ✅ **yes** — §6 |
| 3 | identities identical? | ✅ |
| 4 | assertions identical? | ✅ `{p₁, p₂}` |
| 5 | warrants identical? | ✅ *(stipulated same reason)* |
| 6 | ⭐ **is the only difference the historical transition topology?** | ⭐⭐ ✅ **YES** |

$$R(H_1) = R(H_2) = (\text{holder id},\ \text{current} = p_2,\ \{p_1,p_2\},\ W) \quad\Rightarrow\quad \textbf{no } f(R) \textbf{ can reconstruct which occurred}$$

⭐ **And the distinction is REQUIRED** — `P-08` §5.2's process-reliability ground: *"was `p₁` replaced by
a better claim, or simply abandoned?"* ⛔ **But per §10's own warning, this requires a DISCRIMINATING
PARAMETER, not necessarily two primitives.**

# 11. Identifiability
**Are `W` and `S` observationally distinguishable under the current state representation?** ⭐ 🔴 **No** —
§9. **What additional information makes them identifiable?**

| candidate | required? |
|---|:--:|
| **successor endpoint** | ⭐ 🔴 **insufficient — §5** |
| ⭐⭐ **the role-replacement relation** | ⭐⭐⭐ ✅ **THIS, and only this** |
| reason / warrant | 🔴 present in both |
| status | 🔴 same post-state |
| provenance | 🔴 |
| **event type** | ⚠️ **would work — but it is the relation under another name**, so it is not independently required |

$$\boxed{\textbf{⛔ No field is introduced because it eases classification. Exactly ONE datum is required: the role-replacement claim.}}$$

# 12. Markov / state-sufficiency

| | |
|---|---|
| **state insufficiency** | ⭐ ✅ **yes** — the current state does not determine the transition class |
| **history dependence** | ✅ **yes** |
| ⭐ **transition-type multiplicity** | ⭐⭐⭐ **NOT ENTAILED.** ⛔ **History sensitivity is not multiplicity** — §15 must decide it on other grounds |

⭐ **Three notions kept apart, and the anti-overreach test passes: the model is history-sensitive without
that alone forcing two primitives.**

# 13. DDD audit — implementation removed
> *Would a domain expert regard "superseded" and "withdrawn" as two different domain events, or two
> outcomes of one "assertion retirement"?*

| finding | class |
|---|---|
| the corpus records the Zero reading change as a **supersession** *(a stronger formulation replaces a weaker)* | `[EMP]` |
| the corpus records `Θ`-algebra as **REJECTED-and-retained** — an abandonment with **no replacing construct** | `[EMP]` |
| ⭐⭐ **does the source NAME them as two domain events?** | ⭐ **`[OPEN]`** |
| ⭐ what the source **does** establish | ⭐⭐ **that they carry different HISTORICAL MEANINGS — and no more** |

$$\boxed{\textbf{Stated exactly as §13 requires: the source establishes DIFFERENT HISTORICAL MEANINGS, NOT two named domain events. DDD corroborates; it creates nothing.}}$$

---

# 14. The four-possibility matrix

| before | after | interpretation | already classified as |
|---|---|---|---|
| `p` current | `q` current, `p` retained | ⭐ **supersession** *(if `q` replaces `p`'s role)* **or** withdrawal + independent assertion *(if not)* | ⭐⭐ **the two are NOT distinguished by this row — §5** |
| `p` current | none, `p` retained | **withdrawal** | ⭐ a field change + retention, **not `1→0`** |
| `p` current | `p` current | ⭐ **correction / reinterpretation** | ⭐⭐ **`P-14`: `τ10` at the assertion level, same candidate** |
| `p` current | `q` current **+ `p` still standing** | ⭐ **corroboration** | ⚠️ **`K4b-ii`'s arm — `[OPEN]`, unattested (`P-20`)** |

⭐⭐ **Row 1 is the finding: the same before/after pair covers TWO different events.** So the taxonomy
**cannot** be read off pre/post states.

# 15. ⭐⭐⭐ Model selection

| model | verdict |
|---|---|
| **A** — one transition `RETIRE(p, successor?)` | ⭐ 🔴 **REFUTED — §5 proves the schema LOSSY.** A `replaces?` marker must be added, and it marks a **kind** |
| **B** — two primitive transitions `SUPERSEDE(p,q)` and `WITHDRAW(p)` | ⭐⭐ 🔴 **REFUTED — they are NOT peers.** §8: the obligations coincide **except** for `K4a-ii`; §3–§4: the field change and retention are **shared, identical machinery** |
| ⭐⭐⭐ **C** — one family with formally distinct subclasses | ⭐⭐⭐ ✅ **AND THE STRUCTURE IS NESTED, NOT PARALLEL** |
| **D** — insufficient evidence | 🔴 **no** — the discriminator is identified exactly (§11) |

## The nested structure, derived

$$\boxed{\begin{array}{ll}\textbf{WITHDRAWAL} & = \textbf{holder field change} + \textbf{retention of } p_1 + \textbf{warrant}\\[4pt] \textbf{SUPERSESSION} & = \textbf{WITHDRAWAL} + \textbf{a ROLE-REPLACEMENT relation}\end{array}}$$

$$\boxed{\begin{array}{c}\textbf{Withdrawal is the BASE; supersession is withdrawal PLUS one asserted claim.}\\[4pt] \boxed{\begin{array}{l}\textbf{This explains BOTH facts at once: why } P\text{-}08 \textbf{ bundled them as } \tau10 \textbf{ (they share the base),}\\ \textbf{and why the distinction is required (the extension is a separate claim, borne by } K4a\text{-ii).}\end{array}}\end{array}}$$

⭐⭐ **`A` and `C` are not confused:** `A` says the difference is an incidental **parameter value**; `C`
says it is an **additional asserted claim** that one subclass carries and the other does not.

⇒ **`τ10` is OVER-COMPRESSED at the extension level and CORRECT at the base.**

# 16. Consistency with the earlier transition work
⛔ **Nothing is rewritten; corrections are recorded as findings.**

| prior result | `P-21`'s effect |
|---|---|
| **`P-08`'s `τ10` = *"withdrawal/supersession"*** | ⭐ **QUALIFIED** — correct as a **family**, over-compressed as a **label** |
| ⭐⭐ **`P-15` §6's `1→0` retirement arity** | ⭐⭐⭐ **QUALIFIED BY LEVEL, not refuted.** `P-15`'s `1→0` concerns the **identity population** (candidates), where retirement removes an identity from the live population while retaining it. **At the ASSERTION level there is no population change at all** — only a holder field change. ⭐ **A level distinction, newly explicit** |
| **`P-16`'s closure** | ✅ **unaffected** — its subject was ordering |
| **`P-20`'s `n=2` refutation** | ⭐⭐ **STRENGTHENED** — §4 shows withdrawal is reading **A**, an act, so the refutation rests on an event rather than an absence |
| **`P-09.1` §1's successor-relation criterion** | ⚠️ ⭐ **shown INSUFFICIENT here** — both events share `≺` shape at the holder level, and the discriminator is a **claim**, not a relation shape |

# 17. Mathematical error check

| # | inference | classification |
|---|---|---|
| 1 | different post-states ⇒ different transitions | ⭐ **INVALID** — and moot: §6 shows the post-states are **identical** |
| 2 | different persistence obligations ⇒ different transitions | **INVALID** — §8 treated it as input only |
| 3 | different English names ⇒ different transitions | **INVALID** |
| 4 | different graph arity ⇒ different primitive | ⭐⭐ **INVALID — and its PREMISE is false**: withdrawal is not `1→0` (§4) |
| 5 | same representation ⇒ same transition | **INVALID** — §5 |
| 6 | same state transformation ⇒ same transition | ⭐ **INVALID** — the strongest case, since `H1`/`H2` share pre- **and** post-state yet differ |
| 7 | absence of successor ⇒ withdrawal | ⭐⭐ **INVALID** — §4's A/B/C/D |
| 8 | withdrawal without successor ⇒ `1→0` deletion | ⭐⭐⭐ **INVALID — refuted here** by retirement-with-retention |
| 9 | supersession ⇒ every non-current assertion was superseded | ⭐ **INVALID** — `P-18`'s error, refuted by `P-20` |
| 10 | historical distinguishability ⇒ separate primitive | ⭐⭐ **INVALID — the central anti-overreach point**, honoured by verdict `C` |

⭐ **10 of 10 classified; all ten invalid, and three of them refuted by their own premises rather than
merely unused.**

# 18. Statistical error check

| notion | use here |
|---|---|
| **identifiability** | ⭐ `W` and `S` are **not** identifiable from state alone; **one datum** makes them so (§11) |
| **parameterization** | ⭐⭐ the `replaces?` marker is a **kind marker**, not a nuisance parameter |
| **sufficient statistic** | `R(H)` is **not** sufficient for the transition class |
| **state sufficiency** | fails — §12 |
| **observational equivalence** | ⭐ `H1 ≡_{obs} H2` under `R` |

$$\boxed{\textbf{⛔ "Identifiable" is NOT used as a synonym for "ontologically distinct" — a representation may identify two events without two primitives, which is exactly verdict } C.}$$

# 19. Kernel consequence

$$\boxed{\textbf{KERNEL REMAINS 11 CELLS. ⛔ No cell added, deleted or changed. Cardinality unchanged.}}$$

⭐ **Gate `C`:** ⛔ **nothing becomes unclassified** — both subclasses are classified (base and
extension), so `P-16`'s *"zero unclassified transitions"* stands. ⚠️ **A labelling qualification is
recorded: `τ10` names a family, not a primitive.** ⭐ **Gate `C`'s actual blocker remains `M6`,
unchanged.**

---

# 20. ⭐ P-21 VERDICT

| # | question | answer |
|---|---|---|
| **1** | semantically distinguishable? | ⭐ **YES** — but **only historically**; post-states are identical (§6) |
| **2** | distinct **primitive** transitions? | ⭐⭐ **NO** — model `B` refuted; they are **not peers** (§15) |
| **3** | one parameterized family with distinct **subclasses**? | ⭐⭐⭐ **YES — and NESTED: withdrawal is the base, supersession is withdrawal + a role-replacement relation** |
| **4** | is `τ10` over-compressed? | ⭐ **YES at the extension level; CORRECT at the base** |
| **5** | is *withdrawal-without-successor* established? | ⭐⭐ **`[EMP]`** — `INV-9`, an explicit **act** with a warrant; reading **A**, not **B**/**D** |
| **6** | does it change any kernel cell? | ⛔ **NO** |
| **7** | does it change cardinality? | ⛔ **NO** |
| **8** | does it affect Gate `C`? | ⭐ **NO in status** — zero unclassified stands; **a labelling qualification is recorded** |
| **9** | any previous result refuted? | ⭐ **YES — two premises:** *withdrawal is `1→0`* and *`P-09.1`'s successor-relation criterion suffices here* |
| **10** | any previous result strengthened? | ⭐⭐ **YES — `P-20`'s `n=2` refutation**, now resting on an **act** rather than an absence |
| **11** | architecture permitted? | ⛔ **NO** |
| **12** | Schema v3 permitted? | ⛔ **NO** |
| **13** | canonical theory admitted? | ⛔ **NO** |
| **14** | ⭐ **single next question** | ⭐⭐⭐ **Does every assertion have exactly ONE holder — and can an assertion be withdrawn for one holder while remaining current for another?** *(Derived from §4: withdrawal is a change to the HOLDER's current-assertion field. If holders can be plural, `K2`'s subject and `K4a-ii`'s subject come apart.)* |

# 21. Stopping condition
$$\boxed{\textbf{STOP-B — one transition family with semantically distinct subclasses is sufficient and lossless, PROVIDED the role-replacement claim is carried.}}$$

⭐ **Epistemic closure, not exhaustion:** the discriminator is identified **exactly** (§11), the
alternatives `A`, `B` and `D` are each **refuted on their own grounds**, and no search was continued for
elegance.

# 22. Provenance
**`[EMP]`** `INV-9` withdrawn with a warrant and **nothing replacing it** · retirement-with-retention
practised, deletion never · the Zero reading **superseded** by a stronger formulation · `Θ`-algebra
**REJECTED-and-retained**.
**`[DERIVED]`** ⭐⭐⭐ **withdrawal is a holder FIELD CHANGE plus retention, NOT `1→0`** · ⭐⭐ **the
`Retire(p, reason, successor?)` schema is LOSSY; `replaces?` is a kind marker** · ⭐ **supersession =
withdrawal + a role-replacement claim (nested, not parallel)** · post-states are identical · exactly one
datum is required for identifiability · `¬Successor(p) ≢ Withdrawn(p)`.
**`[CORROBORATION]`** the DDD audit. **`[OPEN]`** ⭐ whether the source **names** two domain events ·
`K4b-ii`'s corroboration arm · everything carried forward.
⛔ **No Lane T construction became corpus evidence; no `[ARCH]` promoted; nothing rewritten.**

---

```
STOP-B — ONE TRANSITION FAMILY WITH SEMANTICALLY DISTINCT SUBCLASSES, AND THE STRUCTURE IS NESTED.
KERNEL REMAINS 11 CELLS. TAU-10 IS OVER-COMPRESSED AT THE EXTENSION LEVEL AND CORRECT AT THE BASE.

  WITHDRAWAL   = holder current-assertion field change + retention of p1 + warrant
  SUPERSESSION = WITHDRAWAL + a ROLE-REPLACEMENT relation

  Withdrawal is the BASE; supersession is withdrawal PLUS one asserted claim. This explains both facts
  at once: why P-08 bundled them as tau-10 (they share the base machinery identically) and why the
  distinction is required (the extension is a separate claim, borne by K4a-ii).

TWO PREMISES OF EARLIER WORK REFUTED:
  (i) "withdrawal is 1->0". It is NOT. [EMP]: the estate practises retirement-WITH-RETENTION and has
  never practised deletion, so p1 does not leave the population — K4a-i retains it. What changes is the
  HOLDER's current-assertion field. This does not contradict P-07 §8's "assertions are superseded, never
  updated": the assertion's CONTENT is never edited; the HOLDER's field is. Consequently the question
  "is withdrawal the 1->0 instance and supersession the 1->1 instance of one retirement transition?" is
  MIS-POSED — neither is a population transition at the assertion level, so arity is insufficient
  because its PREMISE IS FALSE, not merely because it is coarse.
  (ii) P-09.1 §1's successor-relation criterion is INSUFFICIENT here: both events share the same
  relation shape at the holder level, and the discriminator is a CLAIM, not a relation shape.

MODEL A REFUTED, and this is the gap the commission demanded be crossed: Retire(p, reason, successor?)
  looks lossless and is NOT. "successor = p2" is NOT the same claim as "p2 supersedes p1" — P-20 §6
  built exactly the case where p1 is withdrawn AND p2 is independently asserted, so p2 follows without
  REPLACING p1's role. Repairing the schema needs a `replaces?` parameter, and that parameter is a
  SEMANTIC-KIND MARKER rather than an incidental value. Representational unification != transition
  identity.
MODEL B REFUTED: they are not PEERS. The persistence obligations coincide EXCEPTLY for K4a-ii, and the
  field change plus retention plus warrant are shared, identical machinery.

THE ANTI-OVERREACH TESTS PASSED: post-states are IDENTICAL (so nothing was inferred from post-state);
  the obligation difference was treated as INPUT, never as proof; state insufficiency and history
  dependence were kept apart from transition-type multiplicity; and "identifiable" was not used as a
  synonym for "ontologically distinct". All ten listed invalid inferences are classified INVALID, three
  of them refuted by their own premises rather than merely left unused.

P-20 STRENGTHENED: withdrawal-without-successor is reading A — an EXPLICIT ACT with a warrant (INV-9,
  "wrong scope") — not reading B or D, an absence. Had it been an absence, P-20's refutation of the n=2
  reduction would have rested on one. It does not.
P-15 QUALIFIED BY LEVEL, not refuted: its 1->0 concerns the IDENTITY POPULATION (candidates); at the
  ASSERTION level there is no population change at all.

DDD, stated exactly as required: the source establishes that the two carry DIFFERENT HISTORICAL
  MEANINGS, and NOT that they are two named domain events. [OPEN]. DDD corroborates; it creates nothing.

GATE C: unchanged in status — both subclasses are classified, so P-16's "zero unclassified transitions"
  stands. A LABELLING qualification is recorded: tau-10 names a family, not a primitive. Gate C's actual
  blocker remains M6.

NEXT QUESTION (one): DOES EVERY ASSERTION HAVE EXACTLY ONE HOLDER — and can an assertion be withdrawn
  for one holder while remaining current for another? Derived from §4: withdrawal is a change to the
  HOLDER's current-assertion field, so if holders can be plural, K2's subject and K4a-ii's subject come
  apart.

NO ARCHITECTURE — NO SCHEMA v3 — NO CANONICAL THEORY — 3MC UNTOUCHED — NO CELL ADDED OR DELETED — NO
SILENT REPAIR.
```
