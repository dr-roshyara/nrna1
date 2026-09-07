# `P-08` — Minimum Persistence Obligations Derivation

**2026-09-07 · decision-support / semantic derivation only.**
**Baseline:** [`15`](./15-P06-SEMANTIC-ONTOLOGY.md) · [`16`](./16-P06-PERSISTENCE-IDENTITY-DERIVATION.md) · [`17`](./17-P07-IDENTITY-ADDRESSING-CONTINUITY-DERIVATION.md).

> ⛔ **Schema v2 unmodified · v3 not begun · 8 stress records unmodified · proposal register unmodified ·
> `F-4`/`F-5` unrepaired · `Q3`/`Q4` open · 3MC / `dimension-registry.md` / MD-017 / MD-018 untouched ·
> no A/B/C/D · no fifth architecture · no tables, columns, keys, FKs, ORM, APIs, migrations or storage
> technology · no aggregate boundaries introduced · Entity ≠ database identity · identity ≠ surrogate ID ·
> persistence ≠ storage · DDD not ontology · no excluded-lane evidence · implementation existence is not
> theory · candidate existence is never inferred from code existence · no recommendation becomes a decision.**

`[EMP]` evidenced · `[DERIVED]` logically required · `[ARCH]` design choice · `[OPEN]` unresolved.

---

# 1. Scope and the shape of the answer

`P-08` does **not** ask *what to store*. It asks **what would be LOST**, and by what.

⭐ `[DERIVED]` **That reframing produces the document's structure**, because an obligation to persist is
**meaningless without a named transition**: nothing "must persist" absolutely — it must survive **a
specified change**. `P-07`'s `F-CONT` already presupposed this without saying it.

$$\boxed{\textbf{Persist}(x, \tau) \iff x \textbf{ survives transition } \tau \textbf{, and KnowledgeOS is required to distinguish the worlds where it did and did not.}}$$

⭐⭐ **Consequence — the persistence kernel is a RELATION, not a set.** It is a set of
**(item, transition, lost datum)** triples. §16 derives it in that form.

## The transition set `𝒯_KOS` — what the estate is actually observed to do

| # | transition | witnessed |
|---|---|:--:|
| `τ1` | **status change** on a candidate, definition or association | `[EMP]` `Zero` → `[RF]` |
| `τ2` | **association change** (re-disposition) | `[EMP]` `K` `D-03`/`D-05` → `𝒦` |
| `τ3` | **new definition added** to an existing candidate | `[EMP]` `Zero` `D-04` |
| `τ4` | **glyph-membership change** | ⚠️ `[OPEN]` — pending `Q3` |
| `τ5` | **implementation change** in an external artifact | `[EMP]` git over `research/**` |
| `τ6` | **source re-marking** | `[EMP]` `Σ` `D-05` (`F-4`) |
| `τ7` | ⭐ **file rename / move** | ⭐ `[EMP]` **56 renames in one day, uncommitted** |
| `τ8` | **corpus rescan** producing different counts | `[EMP]` this lane's repeated re-measurements |
| `τ9` | **establishment** of a candidate | `[EMP]` `G-67`, `C-022`, `FR-001` |
| `τ10` | **withdrawal / supersession** of an assertion | ⭐ `[EMP]` **this session: `INV-9`, the `(Ω,𝓕,P)` zero, `Θ`-never-raised ×3 — every one WITHDRAWN, none deleted** |

⛔ `𝒯_KOS` is **descriptive**, not closed. A transition the estate has not yet performed is `[OPEN]`.

---

# 2. Dependency audit of `P-07` — four qualifications, none repaired

⛔ **`P-07` is not silently repaired.** The qualifications are recorded here and `P-07` stands as written.

| `P-07` conclusion | rests on | verdict |
|---|---|---|
| identity forced by continuity, not reference (`F-REF`/`F-CONT`) | logical derivation | ✅ **sufficiently established** — the six probes are independent of `Q3`/`Q4` |
| `identity ≠ surrogate identifier` | logical derivation | ✅ **sufficiently established** |
| candidate identity forced | ⚠️ **a derivation resting on a derivation** — `15` §1B *distinction is discovered, not created*, itself `[DERIVED]` | ⚠️ **conditional.** §12 restates it in a form that does not overclaim |
| **definition identity forced UNCONDITIONALLY** | ⭐⭐ **one event — `K` `D-03`/`D-05` — performed BY THIS LANE during stress testing, not by the corpus** | ⚠️⚠️ **potentially stronger than its evidence.** It shows the **extraction procedure** requires the sameness claim; that KnowledgeOS *theory* requires it is `[DERIVED]` **only if the extraction record is itself something that must persist**. §4 carries the qualification |
| occurrence carries no state | `[EMP]` over **8 of 8 stress records** — the whole extraction population, a negligible fraction of the corpus | ⚠️ **conditional and correctly falsifiable.** `P-07`'s falsification condition is preserved verbatim in §11 |
| **continuity kernel = 6** | ⭐⭐ **a count over TWO KINDS of thing** — 3 identities **plus** 3 citation classes | ⚠️⚠️ **underdetermined as a number.** §6 tests the abstraction and the number does not survive |
| no aggregate established; *candidate-as-root* refuted | logical derivation, recorded `[OPEN]` | ✅ **correctly bounded** |

⭐ **Nothing in `P-08` depends on `Q3`, on `Q4`, or on any excluded-lane result.** `τ4` is the only place
`Q3` appears, and it appears **as an open transition**, never as a premise.

---

# 3. Persistence is not storage

| # | notion | what it is | kind |
|---|---|---|---|
| 1 | **semantic existence** | the thing is the thing it is | ⭐ **semantic — and NOT a persistence obligation.** `Π` R2/R3 exist and are unnamed; `ℐ` exists with **0 of 7** definitions established |
| 2 | **identity** | a stable designator carrying sameness | **semantic** |
| 3 | **referenceability** | *some* expression picks it out | **semantic** |
| 4 | **addressability** | ⭐ **one way of achieving (3)** | ⚠️ **implementation consequence, not an obligation** |
| 5 | **continuity** | *"same thing, changed state"* is assertable | **semantic** |
| 6 | **historical recoverability** | a prior state is answerable | **semantic** — §7 |
| 7 | **persistence** | ⭐ **the bridge: certain semantic facts survive a named transition** | ⭐ **a MODAL obligation over `𝒯_KOS`, not a location** |
| 8 | **physical storage** | bytes somewhere | 🔴 **implementation. Out of scope of every `P-0x`** |

$$\boxed{\begin{array}{c}\textbf{Semantic: } 1,2,3,5,6.\qquad \textbf{Obligation: } 7.\qquad \textbf{Implementation: } 4, 8.\\ \boxed{\textbf{Persistence is quantified over TRANSITIONS. Storage is quantified over LOCATIONS. They are not the same predicate.}}\end{array}}$$

⭐ `[DERIVED]` **Addressability is demoted.** `P-07` listed a 10-item *addressing obligation*; `P-08`
finds addressing is **a solution to referenceability**, so it belongs in `[ARCH]`, not in the kernel.

---

# 4. The per-item persistence requirement

⛔ **This is not a schema.** *"minimum information that must survive"* is a **semantic** answer.

## 4.1 Glyph

| question | answer | `[?]` |
|---|---|:--:|
| thing itself persists? | ✅ **yes** — it is the corpus's own token | `[EMP]` |
| identity persists? | 🔴 **no** — it **is** its own value | `[DERIVED]` |
| reference persists? | ✅ yes | `[DERIVED]` |
| current state persists? | ⚠️ **n/a** *(structurally stateless — `B-6` sense)* | `[DERIVED]` |
| prior states recoverable? | ⚠️ **n/a** | `[DERIVED]` |
| may be recomputed? | ✅ **yes — from the corpus** | `[EMP]` |
| provenance persists? | 🔴 no | `[DERIVED]` |
| relation persists? | ✅ **yes — glyph↔candidate, pending `Q3`** | `[OPEN]` |
| **minimum that must survive** | **the string** | `[DERIVED]` |
| **may be regenerated** | **every occurrence count** | `[EMP]` |

## 4.2 Occurrence

| question | answer | `[?]` |
|---|---|:--:|
| thing itself persists? | 🔴 **no** | `[DERIVED]` |
| identity persists? | 🔴 **no** — `P-07` §3 | `[DERIVED]` (⚠️ falsifiable, §11) |
| reference persists? | 🔴 no | `[DERIVED]` |
| current state persists? | ⚠️ **n/a — carries none in 8 of 8 records** | `[EMP]`, scoped |
| prior states recoverable? | ⚠️ n/a | `[DERIVED]` |
| may be recomputed? | ⭐ **CONDITIONAL — only against a pinned corpus state.** See §5.4 | ⭐ `[DERIVED]` |
| provenance persists? | ⭐ ✅ **YES — pattern + scope + corpus state** | ⭐ `[DERIVED]` |
| relation persists? | 🔴 no | `[DERIVED]` |
| **minimum that must survive** | ⭐ **(pattern, scope, corpus-state identifier, result-as-cited)** — 4 data, **no occurrence object** | ⭐ `[DERIVED]` |
| **may be regenerated** | **the site list** | `[DERIVED]` |

## 4.3 Definition

| question | answer | `[?]` |
|---|---|:--:|
| thing itself persists? | ✅ **yes — its text is irreducible.** No re-derivation reconstructs an authored reading | `[DERIVED]` |
| identity persists? | ✅ **yes** ⚠️ *qualified by §2 — the evidence is one lane-performed re-disposition* | `[DERIVED]`, qualified |
| reference persists? | ✅ yes | `[EMP]` — `Zero.D-03` is cited across records |
| current state persists? | ✅ yes | `[EMP]` |
| prior states recoverable? | ⭐ **only for NON-MONOTONE transitions** — §7 | ⭐ `[DERIVED]` |
| may be recomputed? | 🔴 **NO** — the strongest negative in the matrix | `[DERIVED]` |
| provenance persists? | ✅ **yes** — and `F-4` is what its failure looks like | `[EMP]` |
| relation persists? | ✅ **yes** — to candidate, via the association | `[DERIVED]` |
| **minimum that must survive** | **(identity, text, source, current association, warrant of any non-monotone change)** | `[DERIVED]` |
| **may be regenerated** | **nothing** | `[DERIVED]` |

## 4.4 Candidate

| question | answer | `[?]` |
|---|---|:--:|
| thing itself persists? | ⚠️ **CONDITIONAL — and this is the delicate one.** A candidate is a **hypothesis**; what persists is the **assertion that it is one**. §12 | ⭐ `[DERIVED]` |
| identity persists? | ✅ **yes — it is ADJUDICATED and adjudications cannot be regenerated** (§6) | ⭐ `[DERIVED]` |
| reference persists? | ✅ yes | `[EMP]` |
| current state persists? | ✅ yes | `[EMP]` |
| prior states recoverable? | ⭐ **only for non-monotone transitions** — `candidate → refuted` is non-monotone; MD-018's chain is not | ⭐ `[DERIVED]` |
| may be recomputed? | 🔴 **no** — regenerating it would mean **re-adjudicating** | `[DERIVED]` |
| provenance persists? | ✅ **yes — the establishment act** | `[EMP]` — `G-67`, `C-022`, `FR-001` |
| relation persists? | ✅ **yes — to definitions, via associations** | `[DERIVED]` |
| **minimum that must survive** | **(identity, establishment act + mode + warrant-or-`n/a`, current status)** | `[DERIVED]` |
| **may be regenerated** | ⭐ **its glyph occurrence counts — never its identity** | `[DERIVED]` |

## 4.5 Establishment act

| question | answer | `[?]` |
|---|---|:--:|
| thing itself persists? | 🔴 **no — it is external and already exists** | `[DERIVED]` |
| identity persists? | 🔴 **no — a citation suffices** | `[DERIVED]` |
| reference persists? | ✅ **YES — this is the whole obligation** | `[EMP]` |
| current state persists? | ⚠️ n/a | `[DERIVED]` |
| prior states recoverable? | ⚠️ n/a — an act does not change | `[DERIVED]` |
| may be recomputed? | 🔴 **no** — an act is not derivable | `[DERIVED]` |
| provenance persists? | ⭐ **it IS provenance** | `[DERIVED]` |
| relation persists? | ✅ yes — to the candidate | `[EMP]` |
| **minimum that must survive** | ⭐ **(citation, mode, warrant text or its structural absence)** — §10 | ⭐ `[DERIVED]` |
| **may be regenerated** | **nothing** | `[DERIVED]` |

## 4.6 Definition → candidate association

| question | answer | `[?]` |
|---|---|:--:|
| thing itself persists? | ✅ **yes** | `[DERIVED]` |
| identity persists? | ✅ **yes** — `P-07` §5, both `F-REF` and `F-CONT` | `[DERIVED]` |
| reference persists? | ✅ **yes — a revisit trigger must point at it** | `[DERIVED]` |
| current state persists? | ✅ yes | `[EMP]` — MD-017's `unresolved_equivalence` default |
| prior states recoverable? | ⭐ **NO — `unresolved → resolved` is MONOTONE, so the current state ENTAILS it.** What must survive is the **warrant**, not the history. §7, §19 | ⭐⭐ `[DERIVED]` |
| may be recomputed? | 🔴 **no** — adjudicated | `[DERIVED]` |
| provenance persists? | ✅ **yes — the supporting evidence** | `[EMP]` — the containment argument for `𝒦` |
| relation persists? | ✅ **yes, and with ONE endpoint possibly absent** | `[EMP]` |
| **minimum that must survive** | **(identity, definition endpoint, candidate endpoint or its absence, relation type, status, warrant)** | `[DERIVED]` |
| **may be regenerated** | **nothing** | `[DERIVED]` |

## 4.7 Implementation artifact

| question | answer | `[?]` |
|---|---|:--:|
| thing itself persists? | 🔴 **NO — `I-6`: code is not theory. `F-4` is the error of treating it as such** | `[EMP]` |
| identity persists? | 🔴 no — a citation suffices | `[DERIVED]` |
| reference persists? | ✅ **yes, and it must remain RESOLVABLE** | `[DERIVED]` |
| current state persists? | 🔴 **no — it is versioned outside our boundary** | `[EMP]` |
| prior states recoverable? | 🔴 **no — that is git's obligation, not ours** | `[DERIVED]` |
| may be recomputed? | ⚠️ n/a | `[DERIVED]` |
| provenance persists? | ✅ yes | `[DERIVED]` |
| relation persists? | ✅ yes | `[DERIVED]` |
| **minimum that must survive** | ⭐ **(citation, version identifier, AND — where it is a definition's ONLY source — enough cited content to keep the claim checkable)** §5.3 | ⭐ `[DERIVED]` |
| **may be regenerated** | **nothing we own** | `[DERIVED]` |

## 4.8 Realization vs model-of · 4.9 Definition source · 4.10 Assertion / status

| | realization-vs-model-of | definition source | assertion / status |
|---|---|---|---|
| thing itself | 🔴 no — a **value on a link** | 🔴 no — a **value on a definition** | ✅ **yes** |
| identity | 🔴 no | 🔴 no | 🔴 **no** |
| reference | 🔴 no | ✅ **yes — must stay resolvable** | ⚠️ **only if superseded** |
| current state | ⚠️ it **is** a state | ⚠️ it **is** a value | ✅ yes |
| prior states | 🔴 no | ⭐ ✅ **YES — `τ6` source re-marking is NON-MONOTONE** | ⭐⭐ ✅ **YES — supersession, §5.2** |
| recomputed? | 🔴 no — adjudicated | 🔴 no | 🔴 no |
| provenance | ⚠️ inherits the link's | ⭐ **it IS provenance** | ✅ yes |
| relation | ✅ its link | ✅ its definition | ✅ **the supersession relation** |
| **minimum** | **the value on the link** | ⭐ **(source kind, locator, and the extract where it is the sole ground)** | ⭐⭐ **(text, provenance, supersession relation) — and NEVER deletion** |
| **regenerable** | nothing | nothing | ⭐ **nothing** |

---

# 5. The four continuity regimes — minimum obligations

## 5.1 `UPDATED` — candidate · definition · association

| | answer | `[?]` |
|---|---|:--:|
| must remain invariant | ⭐ **the identity, and nothing else** | `[DERIVED]` |
| may change | **every other field** — status, association, source, grounding, implementation | `[EMP]` |
| is the previous state required? | ⭐⭐ **NO — and this is the sharpest result of `P-08`.** What the semantics require is **the WARRANT for the current state**, not the state it replaced | ⭐ `[DERIVED]` |
| history or current state? | ⭐ **current state + warrant.** Complete history is **not** required | `[DERIVED]` |
| what proves continuity? | ⭐ **the persisted identity plus a transition record on non-monotone transitions** (§7) | `[DERIVED]` |

⭐ **Witness that warrant beats history:** `Zero`-as-a-state is `[RF]` and the record carries **why** —
*9 of 11 assumptions refuted*. `[EMP]` **The estate already keeps warrants and does not keep prior
status values.** ⇒ the weakest requirement preserving the semantics is **warrant**, not history.

## 5.2 `SUPERSEDED` — assertions

| | answer | `[?]` |
|---|---|:--:|
| must the old assertion remain? | ⭐⭐ **YES** | ⭐ `[EMP]` |
| must the supersession relation remain? | ✅ **yes** — otherwise the old text stands unmarked as current | `[DERIVED]` |
| must the old assertion's provenance remain? | ✅ **yes** — otherwise the withdrawal cannot be evaluated | `[DERIVED]` |
| **is deletion semantically permissible?** | ⭐ 🔴 **NO** | ⭐ `[DERIVED]` |

⭐⭐ **`[EMP]` from this lane's own conduct, not from a rule:** `INV-9`, the `(Ω,𝓕,P) = 0` claim, and
*"`Θ` has never been raised"* (wrong **three times**) were **every one WITHDRAWN and none deleted** —
`F-4` and `F-5` likewise **stand as recorded defects** and are explicitly not repaired.

$$\boxed{\begin{array}{c}\textbf{A superseded assertion is evidence about the RELIABILITY OF THE PROCESS that produced it.}\\ \textbf{Deleting it destroys evidence that is not about its subject at all.}\end{array}}$$

⛔ **This does not imply an immutable event log.** It is an obligation on **assertions only** — one of
four classes.

## 5.3 `VERSIONED` — implementation artifacts

| | answer | `[?]` |
|---|---|:--:|
| **is the artifact part of KnowledgeOS theory?** | 🔴 **NO** — `I-6`; and **`F-4` is exactly this error committed** | `[EMP]` |
| or only its citation/version reference? | ✅ **only the citation and version** | `[DERIVED]` |
| what must remain resolvable? | **the citation, at the cited version** | `[DERIVED]` |
| ⭐ **what if the external repository disappears?** | ⭐⭐ **`Σ` `D-05` becomes UNCHECKABLE** — `code@kosmodel.py:Sigma` is its **only** source, so the definition degrades from *checkable* to *merely asserted* | ⭐ `[DERIVED]` |

⭐ **The obligation this exposes:** where an external artifact is a definition's **sole** ground, the
estate must **either** persist enough cited content to keep the claim checkable **or** mark the
definition's grounding as pointer-only. ⛔ **Neither is selected — both are `[ARCH]`.** ⭐ And this
explains `F-4` structurally: **it exists because only a pointer was kept.**

## 5.4 `RE-MEASURED` — occurrences

⛔ *"Re-measured"* does **not** mean *"nothing persists."*

| | answer | `[?]` |
|---|---|:--:|
| what must persist? | ⭐ **(pattern, scope, corpus-state identifier, result-as-cited)** | ⭐ `[DERIVED]` |
| what must **not** persist? | **the site list as durable objects** | `[DERIVED]` |
| what reproduces the measurement? | pattern + scope **applied to the same corpus state** | `[DERIVED]` |
| what is adequate reconstruction? | ⭐ **the same result, or a difference ATTRIBUTABLE to a known corpus transition** | `[DERIVED]` |
| what scope must remain declared? | **the tree, the file set, and the fraction of the estate covered** | `[EMP]` — this lane's own rule |

## ⭐⭐ The finding that weakens `P-07` §3

$$\boxed{\begin{array}{c}\textbf{The corpus is MUTABLE — } \tau 7 \textbf{: 56 renames in one day, uncommitted.}\\ \textbf{So re-running a pattern today reproduces the PROCEDURE, not the CLAIM.}\\ \boxed{\textbf{"Re-measurable" is FALSE unless the corpus state is pinned.}}\end{array}}$$

⭐ `[DERIVED]` **Therefore the measurement RESULT must persist after all** — not as an entity, but as a
**historical observation** (§13). `P-07` §3 said occurrences are re-measured rather than stored; `P-08`
qualifies it: **the sites are not stored; the observation is, together with what would let it be
re-derived.** ⚠️ **Recorded as a qualification of `P-07`, not a repair of it.**

---

# 6. Identity kernel vs continuity kernel — testing the abstraction

| question | answer | `[?]` |
|---|---|:--:|
| **1** which identity facts must persist? | ⭐ **all three** — candidate, definition, association | `[DERIVED]` |
| **2** which can be regenerated? | ⭐ **none** | `[DERIVED]` |
| **3** which continuity facts must persist? | **the identities, plus a warrant per non-monotone transition** | `[DERIVED]` |
| **4** which can be reconstructed? | **prior states of monotone chains** — MD-018 | ⭐ `[DERIVED]` |
| **5** ⭐ **is the continuity kernel a PERSISTENCE kernel or a RECOVERABILITY kernel?** | ⭐⭐ **A RECOVERABILITY kernel.** Its three citation classes demand that a claim's **ground stay checkable**, which is a **weaker** modality than *"the fact must survive"* | ⭐ `[DERIVED]` |
| **6** ⭐ **is "citation class" the right abstraction?** | ⭐⭐ 🔴 **NO** | ⭐ `[DERIVED]` |

## The criterion that produces the answer to 1–2

`[DERIVED]` Regenerating a candidate identity would mean **re-deciding** that two definitions belong to
one candidate. That is an **adjudication**, not a computation — and no procedure regenerates a decision.

$$\boxed{\textbf{ADJUDICATED} \Rightarrow \textbf{must persist.}\qquad \textbf{MEASURED} \Rightarrow \textbf{may be reconstructed, given the corpus state.}}$$

⭐⭐ **This single criterion partitions all ten items**, and it is the operative rule of `P-08`.

## Why "citation class" does not survive

`[DERIVED]` The three classes — establishment acts, definition sources, artifact citations — are three
instances of **one** obligation: *a claim whose warrant lies outside the estate must remain checkable.*

$$\boxed{\begin{array}{c}\textbf{"Citation class" was the REPRESENTATION. The obligation is WARRANT CHECKABILITY.}\\ P\text{-}07\textbf{'s } \mathbf{3 + 3 = 6} \textbf{ added identities to citations — two different modalities.}\\ \boxed{\textbf{The correct statement is } \mathbf{3} \textbf{ persistence identities } + \mathbf{1} \textbf{ recoverability obligation over } \mathbf{3} \textbf{ classes.}}\end{array}}$$

⚠️ ⛔ **`P-07` is not repaired.** Its `6` is a correct count of *items in a mixed list*; it is **not** a
kernel cardinality, and `P-08` does not use it as one.

---

# 7. Historical recoverability — the weakest requirement

⛔ **No event sourcing, no append-only storage, no audit log is assumed or concluded.**

## ⭐⭐ The monotonicity result

`[EMP]` **MD-018's status chain is ORDERED:** `candidate → supported → corroborated → formally_defined
→ operationally_defined → canonical`.

$$\boxed{\begin{array}{c}\textbf{For a MONOTONE chain, the current status ENTAILS every prior status.}\\ \Rightarrow \textbf{persisting prior states is REDUNDANT (§17).}\\[6pt] \boxed{\textbf{The historical-recoverability obligation attaches EXACTLY to NON-MONOTONE transitions.}}\end{array}}$$

| transition | monotone? | requirement |
|---|:--:|---|
| **candidate discovery** (`τ9`) | ⭐ **n/a — not a state change but an ENTRY** | **current state + the establishment act.** §12 |
| **definition re-disposition** (`τ2`) | 🔴 **NO** — the current association does not reveal the former one | ⭐ **current state + a transition record naming the PRIOR association and its warrant.** Not a history |
| **association `unresolved → resolved`** | ⭐ ✅ **YES, monotone** | ⭐ **current state + warrant.** **Prior state NOT required** — §19 refines the commissioned witness |
| **assertion supersession** (`τ10`) | 🔴 **NO** | ⭐ **complete retention of the superseded text + the relation** — §5.2. The one place full retention is forced |
| **implementation version change** (`τ5`) | 🔴 no | **the cited version only.** Prior versions are git's obligation |
| **occurrence re-measurement** (`τ8`) | ⚠️ **not a state change — a re-observation** | ⭐ **reconstructibility from immutable evidence** = (pattern, scope, corpus state) |
| **status → refuted** (`τ1`) | 🔴 **NO — refutation is an EXIT, not a rung** | **current state + warrant** |
| **source re-marking** (`τ6`) | 🔴 no | **current state + warrant** (`F-4` is the live case) |

$$\boxed{\begin{array}{c}\textbf{Five distinct requirement levels are needed, and NONE of them is "complete history":}\\ \textbf{current-only · current+act · current+warrant · current+warrant+prior-value · full retention · reconstructibility.}\end{array}}$$

---

# 8. What *"edit"* means, per object class

⛔ **The tension `P-07` recorded is NOT resolved by design here** — it is dissolved by showing *"edit"*
is **six different operations**.

| operation | semantics | what the persistence layer must preserve |
|---|---|---|
| **mutation of the same thing** | ⭐ identity survives, state replaced | **identity + new state + warrant.** 🔴 **Not the old state** |
| **supersession by a new thing** | two identities + a relation | ⭐ **both texts + the relation + both provenances.** **Deletion impermissible** |
| **versioning of an external artifact** | outside our boundary | **the cited version.** Nothing of the artifact itself |
| **replacement of an observation** | ⭐ **not an edit — a NEW observation of a CHANGED corpus** | ⭐ **both observations + the corpus states.** Overwriting destroys `τ7`/`τ8` attribution |
| **correction of provenance** | non-monotone (`τ6`) | ⭐ **new value + prior value + warrant.** `F-4` is precisely a pending case |
| **correction of a classification** | non-monotone (`τ2`, disposition changes) | ⭐ **new value + prior value + warrant** |

$$\boxed{\begin{array}{c}\textbf{append-never-edit is CORRECT for assertions and observations, and WRONG for the three updated items —}\\ \textbf{because for them the semantics demand a WARRANT, and a warrant is not a log.}\\ \boxed{\textbf{Only 2 of 6 operations require retention. 4 require a warrant. NONE requires an immutable event log.}}\end{array}}$$

---

# 9. Provenance — three roles, or one abstraction with typed roles?

| | definition source | establishment act | evidence for an association |
|---|---|---|---|
| **what is linked** | definition → external text/code | candidate → an act | association → an argument |
| **what the link means** | *"this reading came from there"* | *"this candidate entered here"* | *"this pairing is supported by that"* |
| **must the target persist?** | ⭐ **must remain CHECKABLE** (§5.3) | **must remain CITABLE** | ⭐ **must persist — the argument is ours** |
| **must it stay resolvable?** | ✅ yes | ✅ yes | ✅ yes |
| **does the link have identity?** | 🔴 **no** — a value on the definition | 🔴 **no** — a citation | ⚠️ **inherits the association's** |
| **can it be reconstructed?** | 🔴 no | 🔴 no | 🔴 no — it is an argument |
| **must changes stay recoverable?** | ⭐ ✅ **yes — `τ6` is non-monotone** | 🔴 no — an act does not change | ✅ yes |

## The commissioned test — is there a deeper abstraction?

⭐ ✅ **YES, and it does not collapse the roles.** All three instantiate:

$$\text{Warrant}(\text{claim}, \text{ground}, \text{role}) \quad\text{with } \textbf{role} \in \{\text{source-of-content},\ \text{act-of-entry},\ \text{argument-for-pairing}\}$$

⭐⭐ **And `FR-001` proves the role is a property of the LINK, not of the ground:** it is the
**act-of-entry** for the candidate `δ = 0.3099` **and** the **source-of-content** for its value —
**one ground, two roles, simultaneously.**

$$\boxed{\textbf{One abstraction, THREE IRREDUCIBLE TYPED ROLES. Collapsing them would make } FR\text{-}001 \textbf{ contradictory.}}$$

⛔ **Not collapsed, and `role` is not proposed as a schema field.**

---

# 10. Establishment act — re-tested, not promoted

| notion | distinct because | `[?]` |
|---|---|:--:|
| **artifact** | `FR-001` the document | `[EMP]` |
| **act** | *"this established a candidate"* — a **use** of the artifact | `[DERIVED]` |
| **evidence** | what the artifact contains | `[EMP]` |
| ⭐ **warrant** | ⭐⭐ **`C-022` carries one — *"50 attack classes"*. `G-67` carries NONE: naming establishes existence with no warrant at all** | ⭐ `[EMP]` |
| **citation** | the resolvable reference | `[EMP]` |
| **provenance relation** | the typed link (§9) | `[DERIVED]` |

⭐ **`B-6` applies to the warrant:** `G-67`'s absence is **`n/a`** — naming-establishment is
*structurally* warrantless — **not** `never` (*"searched, none found"*). ⚠️ **The two must not be
conflated, or `P-EX` collapses.**

**What must persist so a candidate's warrant remains auditable?**

$$\boxed{(\textbf{citation},\ \textbf{establishment mode},\ \textbf{warrant text or its structural absence-kind}) \quad \textbf{— three data, NO entity.}}$$

⭐ `[DERIVED]` **Promotion is not required**, confirming `P-07` on independent grounds: the auditability
obligation is discharged by **three values on a link**, and nothing about it needs identity, state or a
lifecycle. ⛔ **Not refused either — `[OPEN]`.**

---

# 11. Occurrence — the obligation, not the mechanism

**The safer question, as commissioned:** *what minimum information must persist so that an occurrence
claim can be reconstructed under its declared scope and pattern?*

| candidate content | is it obligated? | `[?]` |
|---|---|:--:|
| **storing the occurrence** | 🔴 **not obligated** | `[DERIVED]` |
| **storing a locator** | ⚠️ **`[OPEN]` — no locator scheme exists to test** | `[OPEN]` |
| **storing a query / pattern** | ✅ **obligated** — without it nothing is reproducible | `[DERIVED]` |
| **storing scope** | ✅ **obligated** — a zero measures *a scope and a pattern*, never a theory | `[EMP]` |
| **storing provenance** | ✅ **obligated** — who measured, when | `[DERIVED]` |
| **storing the measurement result** | ⭐ ✅ **OBLIGATED — §5.4.** It is the only witness of what the corpus said then | ⭐ `[DERIVED]` |
| **storing the algorithm / version used** | ⭐ ⚠️ **obligated where the result is glyph-sensitive** — this lane's own **glyph-literal patterns over a LaTeX corpus produced a false zero** | ⭐ `[EMP]` |

⛔ **No selection among these.** The obligation is derived; the mechanism is `[ARCH]`.

## ⭐ `P-07`'s falsification condition — preserved verbatim

> **If future evidence assigns state to an individual occurrence, occurrence continuity/identity must
> be reconsidered.**

⚠️ **And `P-08` adds a second:** if a **result-as-cited** is ever treated as a thing that can *change*
rather than as a thing that can be *superseded by a new observation*, the `RE-MEASURED` regime collapses
into `UPDATED` and this section must be redone.

---

# 12. Candidate discovery vs candidate creation — the epistemological safeguard

| # | stage | what it is | what it does **not** give | `[?]` |
|---|---|---|---|:--:|
| 1 | **existence as an epistemic hypothesis** | there is *something* the corpus is talking about | ⛔ **no identity, no name, no standing** | `[EMP]` — `Π` R2/R3 |
| 2 | **recognition / discovery** | the estate notices it | ⛔ **not establishment** | `[EMP]` — `𝒦` via containment |
| 3 | **naming** | ⭐ establishes **EXISTENCE** — `P-EX` | ⛔ **no definition, no warrant** — `G-67` | `[EMP]` |
| 4 | **establishment / ratification** | an act with a mode and possibly a warrant | ⛔ **not truth** | `[EMP]` — `C-022` |
| 5 | **persistence** | the estate's assertion survives `𝒯_KOS` | ⛔ **nothing about 1–4** | `[DERIVED]` |

## ⭐⭐ The safeguard, stated so it cannot be walked past

$$\boxed{\begin{array}{c}\textbf{Discovery confers RETROSPECTIVE IDENTITY — never retrospective ESTABLISHMENT.}\\ \textbf{"We discovered it" licenses only: } \textit{"the hypothesis is the same hypothesis as the one recorded earlier."}\\ \textbf{It does NOT license: } \textit{"it was already an established KnowledgeOS entity."}\end{array}}$$

**What continuity requires *before* establishment** — the minimum, `[DERIVED]`:

1. the identity may be asserted retrospectively **only as an identity of a hypothesis**;
2. the **establishment act's citation must persist**, so *"when did this become established"* is answerable and **cannot be back-dated**;
3. ⛔ **the pre-establishment record must never be rewritten as though it had been established.**

⭐ **This retro-qualifies `P-07` §4.** Its *"`𝒦` existed before it was distinguished"* is sound **for
`𝒦` as a hypothesis** and **overclaims if read as `𝒦`-the-established-candidate**. ⚠️ **`P-07` stands as
written; the qualification is recorded here.** ⛔ **And candidate existence is never inferred from the
existence of code.**

---

# 13. Statistical / mathematical sanity check

⚠️ **Category-error detection only. No statistical ontology is imported and no model is asserted.**

| distinction | mapping | error it blocks | `[?]` |
|---|---|---|:--:|
| **latent object vs observation** | candidate vs occurrence | ⭐ *"persist the observations and you have persisted the object"* — 🔴 no: **occurrences are regenerable, candidates are adjudicated** | `[DERIVED]` |
| **parameter vs estimate** | candidate vs definition | *"the best definition IS the candidate"* — refuted by `1:N` | `[DERIVED]` |
| **hypothesis vs established object** | stage 1 vs stage 4 (§12) | ⭐ **the safeguard error** | `[DERIVED]` |
| **sample vs population** | 143 measured sites vs all sites in scope | ⭐ *"the persisted count is the truth"* — 🔴 no: **it is a measurement of a declared scope at a corpus state** | `[EMP]` |
| **label vs identity** | glyph vs candidate | *"same label ⇒ same object"* — refuted by `𝒦`/`𝕂`/`K`; `ℐ`/`𝓘` is `[OPEN]` | `[EMP]` |
| ⭐ **measurement vs measured value** | the act vs the number | ⭐⭐ *"persist the number"* is insufficient — **without (pattern, scope, corpus state) the number is uninterpretable.** §5.4 | ⭐ `[DERIVED]` |
| **equivalence vs identity** | `≡_sem` vs `SameId` | *"equivalent ⇒ merge"* — the corpus's 7 relations already forbid it | `[EMP]` |
| ⭐ **state transition vs new object** | `UPDATED` vs `SUPERSEDED` | ⭐ **the whole of §7–§8** | `[DERIVED]` |
| ⭐ **missing endpoint vs null value** | ⭐⭐ **an association with no candidate is NOT an association with a null candidate** — the first is *a hypothesis awaiting an endpoint*, the second is *a claim that there is none* | ⭐ **the single most dangerous persistence error available**: representing "not yet adjudicated" as "adjudicated to nothing" | ⭐ `[DERIVED]` |
| **historical observation vs current estimate** | result-as-cited vs a fresh rescan | ⭐ *"the rescan replaces the citation"* — 🔴 no: **it is a second observation of a changed corpus** | `[DERIVED]` |

⭐ **`[DERIVED]` The four errors persistence would introduce if unguarded:** observations→entities ·
estimates→parameters · labels→identities · statuses→objects. **All four are blocked by the
adjudicated/measured partition of §6.**

---

# 14. DDD sanity check

⚠️ **DDD is a cross-check. The persistence obligations below follow from the SEMANTIC BEHAVIOUR and
would be identical under any modelling vocabulary.**

| DDD notion | persistence obligation that follows from behaviour, not category | `[?]` |
|---|---|:--:|
| **Entity** | identity persists; state replaceable; ⛔ **"Entity" is NOT database identity** | `[DERIVED]` |
| **Value Object** | no identity; persists **as part of its owner** | `[DERIVED]` |
| **Domain Assertion** | ⭐ **full retention + supersession relation; deletion impermissible** | `[EMP]` |
| **Domain Event** | ⭐ **fits nothing we own** — establishment acts are **references to pre-existing artifacts**, so **no event store is implied** | `[DERIVED]` |
| **Relationship** | ⭐ **insufficient for the association** — it has an independent lifecycle and can outlive an absent endpoint | `[DERIVED]` |
| **Aggregate** | ⛔ **NONE INTRODUCED. `P-07`'s `[OPEN]` is preserved unchanged** | `[OPEN]` |
| **Aggregate boundary** | ⛔ **none.** ⭐ `P-08` adds an independent reason: **four lifecycle regimes with four different retention obligations cannot share one consistency boundary** | ⭐ `[DERIVED]` |
| **Lifecycle** | ⭐ **four regimes ⇒ four retention obligations** — §5 | `[DERIVED]` |
| **Invariant** | ⭐ **every evidenced invariant is GLOBAL**, so no boundary-local enforcement is implied | `[DERIVED]` |

$$\boxed{\textbf{The obligations of §4–§9 were derived WITHOUT any DDD category, and no category changes one of them.}}$$

---

# 15. Minimum Persistence Obligation Matrix

| semantic item | identity | current state | historical state | provenance | relationship | reconstructibility | **persistence obligation (capability, not mechanism)** |
|---|:--:|:--:|:--:|:--:|:--:|:--:|---|
| **Glyph** | 🔴 `[D]` | ⚠️ n/a `[D]` | 🔴 `[D]` | 🔴 `[D]` | ⚠️ `[OPEN]` `Q3` | ✅ **full** `[EMP]` | *hold the token; regenerate every count* |
| **Occurrence** | 🔴 `[D]`⚠️ | ⚠️ n/a `[EMP]` | ⭐ **as observation** `[D]` | ✅ `[D]` | 🔴 `[D]` | ⭐ **conditional on a pinned corpus state** `[D]` | ⭐ *keep a claim re-derivable: pattern, scope, corpus state, result-as-cited* |
| **Definition** | ✅ `[D]`⚠️ | ✅ `[EMP]` | ⭐ **non-monotone only** `[D]` | ✅ `[EMP]` | ✅ `[D]` | 🔴 **none** `[D]` | *carry an irreducible authored text under a stable designator, with its ground* |
| **Candidate** | ✅ `[D]` | ✅ `[EMP]` | ⭐ **non-monotone only** `[D]` | ✅ `[EMP]` | ✅ `[D]` | 🔴 **none** `[D]` | *carry an adjudicated identity independent of every representation* |
| **Establishment act** | 🔴 `[D]` | ⚠️ n/a `[D]` | 🔴 `[D]` | ⭐ **it IS** `[D]` | ✅ `[EMP]` | 🔴 `[D]` | ⭐ *keep the warrant auditable and un-back-datable* |
| **Def→cand association** | ✅ `[D]` | ✅ `[EMP]` | ⭐ 🔴 **NO — monotone** `[D]` | ✅ `[EMP]` | ✅ **endpoint may be ABSENT** `[EMP]` | 🔴 **none** `[D]` | ⭐ *hold a standing, adjudicable pairing that exists before its endpoint does* |
| **Implementation artifact** | 🔴 `[EMP]` | 🔴 `[EMP]` | 🔴 `[D]` | ✅ `[D]` | ✅ `[D]` | 🔴 `[D]` | ⭐ *keep a version-pinned citation checkable without the external repository* |
| **Realization / model-of** | 🔴 `[D]` | ⚠️ **is one** `[D]` | 🔴 `[D]` | ⚠️ inherits `[D]` | ✅ `[D]` | 🔴 `[D]` | *carry an adjudicated value on a link* |
| **Definition source** | 🔴 `[D]` | ⚠️ **is one** `[D]` | ⭐ ✅ **`τ6` non-monotone** `[D]` | ⭐ **it IS** `[D]` | ✅ `[D]` | 🔴 `[D]` | ⭐ *keep the ground checkable, and corrections to it warranted* |
| **Assertion / status** | 🔴 `[D]` | ✅ `[EMP]` | ⭐⭐ ✅ **FULL** `[EMP]` | ✅ `[D]` | ⭐ **supersession** `[D]` | 🔴 `[D]` | ⭐⭐ *never lose a claim the estate once made, nor the fact that it was withdrawn* |

`[D]` = `[DERIVED]` · ⚠️ = carries a §2 qualification. ⛔ **No cell names a mechanism.**

---

# 16. The Minimum Persistence Kernel — derived independently

⛔ **`3` identities, `6` continuity classes and `10` addressing obligations are `P-07` results and are
NOT assumed.** The kernel is derived from §15 and §19.

## Testing the nine offered categories

| offered category | verdict |
|---|---|
| **identity preservation** | ✅ **kernel** — adjudicated, non-regenerable (§6) |
| **state preservation** | ✅ **kernel** — the trivial member, but its omission is a real loss |
| **continuity preservation** | 🔴 **NOT a separate member** — it is *identity preservation + warranted transition records*. **Redundant** |
| **relationship preservation** | ⭐ 🔴 **NOT separate** — a relationship that needs preserving **is** the association, already an identity |
| **provenance resolvability** | ⚠️ **merges** — see below |
| **historical recoverability** | ⭐ **NOT general** — reduces to **two** obligations: *warranted transition records* (non-monotone) and *supersession retention* (assertions) |
| **reproducibility** | ⚠️ **merges** — see below |
| **supersession preservation** | ✅ **kernel** — irreducible, `[EMP]` |
| **version resolvability** | ⚠️ **merges** — see below |

## ⭐ The merge that reduces the kernel

`[DERIVED]` **provenance resolvability**, **reproducibility** and **version resolvability** are three
faces of one obligation: *the ground of a claim must remain checkable without depending on a mutable
external.* — a source extract, a pinned corpus state, and a pinned artifact version are the **same
capability** applied to three kinds of ground.

$$\boxed{\begin{array}{rl}\textbf{K1} & \textbf{IDENTITY PRESERVATION — for the three adjudicated identities; non-regenerable}\\ \textbf{K2} & \textbf{CURRENT-STATE PRESERVATION — for the three updated items}\\ \textbf{K3} & \textbf{WARRANTED TRANSITION RECORDS — on NON-MONOTONE transitions only (prior value + warrant)}\\ \textbf{K4} & \textbf{SUPERSESSION RETENTION — assertions: both texts, the relation, both provenances; deletion impermissible}\\ \textbf{K5} & \textbf{WARRANT CHECKABILITY — a claim's ground stays checkable without a mutable external}\\ \hline & \boxed{\textbf{PERSISTENCE KERNEL} = \mathbf{5}\ \textbf{capabilities}}\end{array}}$$

## Minimality argument

| pair | independent? |
|---|---|
| `K1`/`K2` | ✅ identity is not state — `Zero` keeps identity through `[RF]` |
| `K1`/`K3` | ✅ ⭐ **`K3` is not derivable from `K1`** — identity assertions are adjudications, not transitions |
| `K3`/`K4` | ✅ `K3` = *prior value + warrant*; `K4` = *full text retention*. **Different strengths, different classes** |
| `K4`/`K5` | ✅ `K4` retains **our** claims; `K5` keeps **external** grounds checkable |
| `K2`/`K5` | ✅ state is a fact; a warrant is its ground |

⭐ **And each is forced by a distinct §19 witness — no witness is discharged by two members.**

⚠️ **`5` is a lower bound under the present evidence, not a proof of minimality.** ⭐ **It is notably
NOT 3, NOT 6 and NOT 10** — `P-08` reaches a different number by a different question, as commissioned.

---

# 17. Information-theoretic minimality

⚠️ **Concept only — no numerical information theory.**

> *What information distinguishes all semantically different histories KnowledgeOS must distinguish?*

## ⭐⭐ Insufficiency proof — current state alone is provably too weak

| | history |
|---|---|
| **A** | definition `D` authored under candidate `𝒦`; never re-dispositioned |
| **B** | `D` authored under `K`, later **re-dispositioned** to `𝒦` |

`[EMP]` **Current state is IDENTICAL in A and B.** `[DERIVED]` **KnowledgeOS must distinguish them**,
because **B contains an adjudication that could be wrong** and is therefore a legitimate revisit
target, while **A contains none**.

$$\boxed{\begin{array}{c}\textbf{Two histories, one persisted state, a required distinction} \Rightarrow \textbf{current-state-only is INSUFFICIENT.}\\ \textbf{The minimal sufficient addition is } \mathbf{K3} \textbf{: prior value} + \textbf{warrant. Full history is NOT required.}\end{array}}$$

## ⭐ Redundancy proof — prior monotone statuses are eliminable

`[DERIVED]` If a status chain is **ordered** (MD-018) and transitions are **monotone**, then
`status = corroborated` **entails** passage through `supported`. Persisting the intermediate values adds
**no distinguishing information**.

$$\boxed{\textbf{Monotone chain} \Rightarrow \textbf{prior states are RECONSTRUCTIBLE FROM CURRENT STATE} \Rightarrow \textbf{persisting them is REDUNDANT.}}$$

⚠️ **Conditional on monotonicity.** If a status is ever moved **backwards**, the transition becomes
non-monotone, `K3` engages, and this proof lapses. ⛔ **Whether MD-018 permits regression is `[OPEN]`.**

⭐ **These two proofs are exactly the pair §17 asks for — one insufficiency, one redundancy — and
between them they fix `K3`'s strength: *transition records, not history.*** ⛔ **And *minimum
persistence* is a count of **distinctions**, never of fields.**

---

# 18. Losslessness and the loss taxonomy

**`Lossless` here means:** every semantic distinction KnowledgeOS is required to make remains makeable
after any transition in `𝒯_KOS` — **not** that bytes are preserved.

| loss | class | why |
|---|---|---|
| **two candidates collapse into one identity** | ⭐ **SEMANTICALLY FATAL** | unrecoverable — no adjudication can be re-derived, and `Q3` shows the estate cannot always tell |
| **a superseded assertion is deleted** | ⭐ **FATAL** | destroys evidence about the **process**, not the subject (§5.2) |
| **corpus state lost for a cited measurement** | ⭐ **FATAL** | the number becomes uninterpretable (§13) |
| **an absent endpoint recorded as a null value** | ⭐⭐ **FATAL** | *"not yet adjudicated"* silently becomes *"adjudicated to nothing"* |
| **prior association lost on re-disposition** | **FATAL** | §17's insufficiency proof |
| **a sole external ground becomes unresolvable** | ⚠️ **RECOVERABLE with epistemic downgrade** | the definition survives as *asserted* rather than *checkable* — `F-4` is the live case |
| **occurrence site list lost** | ✅ **ACCEPTABLE** | regenerable from (pattern, scope, corpus state) |
| **an address changes** | ✅ **ACCEPTABLE** | 56 renames broke nothing that mattered |
| **intermediate monotone statuses lost** | ✅ **ACCEPTABLE** | §17 redundancy proof |
| **an artifact's prior versions lost** | ⚠️ **UNKNOWN** | artifact lifecycle is `[OPEN]` |
| **glyph-membership history lost** | ⚠️ **UNKNOWN** | depends on `Q3`; ⛔ **not closed** |

⛔ **No compression, normalization or database technique is prescribed or implied.**

---

# 19. Failure witnesses — the smallest missing datum

| # | witness | smallest missing information | forces |
|---|---|---|:--:|
| `W1` | ⭐ **two different candidates collapse into one identity** | **one bit per pair: `SameId` or not — an adjudication, not a computation** | **`K1`** |
| `W2` | ⭐ **re-disposition appears as deletion + creation** | **the prior association value, plus the warrant for changing it** | **`K3`** |
| `W3` | **a `[RF]` candidate reads as if it had never been live** | **the current status, with its warrant** | **`K2`** |
| `W4` | ⭐⭐ **supersession destroys the historical claim** | **the superseded text and the supersession relation** — `INV-9` withdrawn-not-deleted | **`K4`** |
| `W5` | **a source becomes unresolvable** | **enough cited content to keep the claim checkable, or an honest grounding downgrade** | **`K5`** |
| `W6` | ⭐ **a measurement cannot be reconstructed** | **the corpus-state identifier** — with pattern and scope kept, this is the one remaining gap | **`K5`** |
| `W7` | ⭐⭐ **an absent endpoint is recorded as null** | **the distinction between *awaiting adjudication* and *adjudicated to none*** | **`K2`** *(state fidelity)* |

## ⭐ The commissioned witness that does **not** hold — reported as a negative

> *"Unresolved → resolved loses the fact that the hypothesis was previously unresolved."*

⭐ **`[DERIVED]` This witness FAILS as stated.** `unresolved → resolved` is **monotone**: the current
state `resolved` **entails** that the pairing was adjudicated, so the prior value carries no
distinguishing information (§17).

⭐⭐ **What the witness correctly detects, once repaired:** if `resolved` is recorded **without a
warrant**, an *adjudicated* pairing becomes indistinguishable from one that was **never in doubt**.

$$\boxed{\textbf{The missing datum is the WARRANT, not the history. } W2\textbf{'s obligation is } \mathbf{K3}\textbf{; this one's is } \mathbf{K2} + \mathbf{K3}\textbf{'s warrant clause.}}$$

⚠️ **Recorded honestly against the commission's own example**, and it **strengthens** rather than
weakens the kernel: it shows `K3`'s warrant clause is load-bearing independently of its prior-value
clause.

---

# 20. Persistence is not implementation

⛔ **Explicitly not concluded, and not concludable from anything above:** *"therefore UUID"* ·
*"therefore surrogate IDs"* · *"therefore event sourcing"* · *"therefore temporal tables"* ·
*"therefore graph storage"* · *"therefore relational tables"* · *"therefore aggregates"* ·
*"therefore immutable records"* · *"therefore soft deletion"*.

⭐ **`K4` in particular does NOT imply an event log** — it is an obligation on **one of four** object
classes, and §8 shows **four of six** *"edit"* operations need only a warrant. ⭐ **`K1` does NOT imply
a surrogate** — `P-07` §1.1: a stable natural key **is** an identity, and the definition may have one.

$$\boxed{P\text{-}08 \textbf{ produces the obligations that make such choices EVALUABLE. It evaluates none of them.}}$$

---

# 21. Decision boundary

## Settled `[EMP]` — direct corpus evidence only
`τ7`: **56 renames in one day, uncommitted** · `Zero` retains two `[RF]` definitions **with the
refutation reason recorded** · `Σ` `D-05`'s **only** source is `code@kosmodel.py:Sigma` (`F-4`) ·
`C-022` carries a warrant, **`G-67` carries none** · `FR-001` plays **two provenance roles at once** ·
MD-018's status chain is **ordered** · MD-017's `unresolved_equivalence` is the **default** ·
an association exists with its **candidate endpoint absent** · ⭐ **this session withdrew `INV-9`, the
`(Ω,𝓕,P)` zero and *"`Θ` never raised"* — every one WITHDRAWN, none deleted** · **`F-4`/`F-5` stand as
recorded, unrepaired defects** · a **glyph-literal pattern over a LaTeX corpus produced a false zero** ·
occurrences carry no individual state in **8 of 8** stress records.

## Derived `[DERIVED]`
⭐⭐ **`Persist(x,τ)` is modal over transitions — so the kernel is a RELATION, and `𝒯_KOS` must be named
before any obligation is determinate** · ⭐⭐ **`ADJUDICATED ⇒ must persist; MEASURED ⇒ may be
reconstructed` partitions all ten items** · ⭐⭐ **historical recoverability attaches EXACTLY to
non-monotone transitions — five requirement levels, none of them complete history** · ⭐⭐ **monotone
chains make prior states redundant (proof)** · ⭐⭐ **current-state-only is provably insufficient
(proof)** · ⭐ **"re-measurable" is FALSE unless the corpus state is pinned — so the RESULT must persist
as a historical observation** · ⭐ **"citation class" is not the right abstraction; the obligation is
WARRANT CHECKABILITY — `P-07`'s `3+3=6` mixed two modalities** · ⭐ **provenance resolvability +
reproducibility + version resolvability are ONE capability** · ⭐ **`edit` is six operations; only 2 of
6 require retention, 4 require a warrant, NONE requires an event log** · ⭐ **discovery confers
retrospective identity, never retrospective establishment** · ⭐ **an absent endpoint is not a null
value** · ⭐ **addressability is demoted from obligation to solution** · ⭐ **four lifecycle regimes with
four retention obligations cannot share one consistency boundary** · **establishment-act auditability
needs three values on a link, no entity** · **`PERSISTENCE KERNEL = 5`, a lower bound**.

## Architectural choices `[ARCH]` — cannot yet be selected
glyph- vs candidate-addressing · whether to adopt a locator scheme · **how** warrant checkability is
achieved (extract vs pointer-plus-downgrade) · **how** a corpus state is pinned · **how** transition
records are represented · **how** supersession is represented · artifact keying · **whether to call the
association an Entity** · the physical persistence pattern · **A/B/C/D — ⛔ not selected, and `P-07`
found the association appears in none of the four.**

## Open `[OPEN]`
**`Q3`** · **`Q4`** · **`definition → candidate` upper bound (`1` or `N`)** · **occurrence identity
mechanism** · **implementation-artifact lifecycle** · **establishment-act promotion** *(shown
unnecessary, not refused)* · **aggregate existence** · **locator sufficiency** *(untestable — no scheme
exists)* · **physical persistence pattern**.

### ⭐ New open questions discovered by `P-08`
| | |
|---|---|
| **`O-P08-1`** | ⭐⭐ **Does MD-018 permit status REGRESSION?** If yes, §17's redundancy proof lapses and `K3` engages on the whole chain. **The single highest-leverage open question `P-08` produces** |
| **`O-P08-2`** | ⭐ **How is a corpus state identified?** `τ7` was **uncommitted**, so no commit id existed at measurement time — `W6` is currently **live**, not hypothetical |
| **`O-P08-3`** | **Is `𝒯_KOS` complete?** It is descriptive. An unperformed transition could add an obligation |
| **`O-P08-4`** | ⭐ **Must the EXTRACTION RECORD itself persist?** §2 shows the definition-identity result depends on it — **and this question is prior to that result** |
| **`O-P08-5`** | **Is a `result-as-cited` superseded or updated?** §11's second falsification condition |
| **`O-P08-6`** | ⭐ **Can a warrant itself be withdrawn?** If so, warrants are assertions, and `K4` applies to them — which would **enlarge the kernel** |

---

# 22. Required final synthesis

## 1 · Minimum Persistence Obligations
**Preserve three adjudicated identities · the current state of the three updated items · a warranted
transition record on every non-monotone transition · every assertion the estate has ever made, with its
supersession relation · the checkability of every claim's external ground.** ⛔ **Nothing else is
obligated.**

## 2 · Minimum Persistence Kernel
$$\mathbf{5} \textbf{ capabilities: } \mathbf{K1}\ \text{identity} \cdot \mathbf{K2}\ \text{current state} \cdot \mathbf{K3}\ \text{warranted transition records} \cdot \mathbf{K4}\ \text{supersession retention} \cdot \mathbf{K5}\ \text{warrant checkability}$$
⭐ **Independently derived — not `P-07`'s 3, 6 or 10.** ⚠️ **A lower bound, not a minimality proof.**

## 3 · What must survive
candidate · definition · association **identities** · definition **text** · current **statuses** ·
**establishment citations with mode and warrant-or-`n/a`** · **every assertion ever made** · **prior
value + warrant on non-monotone changes** · **(pattern, scope, corpus state, result) for every cited
measurement** · ⭐ **the distinction between an absent endpoint and a null one**.

## 4 · What may be reconstructed
**every occurrence site list** *(given a pinned corpus state)* · **all glyph counts** · **intermediate
statuses of monotone chains** · **addresses** · ⛔ **and NO adjudication, ever**.

## 5 · What must remain historically recoverable
⭐ **Exactly two things, and not "history":** ① **non-monotone transitions** — via prior value +
warrant; ② **superseded assertions** — via full retention. ⛔ **No event sourcing, no append-only
storage, no audit log is implied.**

## 6 · What is still architectural choice
addressing strategy · locator adoption · warrant-checkability mechanism · corpus-state pinning
mechanism · transition-record and supersession representation · artifact keying · aggregate modelling ·
the physical pattern · **A/B/C/D**.

## 7 · What remains open
`Q3` · `Q4` · definition cardinality upper bound · occurrence identity mechanism · artifact lifecycle ·
establishment-act promotion · aggregate existence · locator sufficiency · physical persistence pattern ·
⭐ **`O-P08-1` … `O-P08-6`**, of which **`O-P08-1` (status regression)** and **`O-P08-2` (corpus-state
identification — currently live)** are the two that would change the kernel.

---

**P-08 MINIMUM PERSISTENCE OBLIGATIONS DERIVED — PERSISTENCE KERNEL: 5 CAPABILITIES (K1 identity · K2 current state · K3 warranted transition records · K4 supersession retention · K5 warrant checkability; a lower bound, independently derived, not P-07's 3/6/10) — HISTORICAL RECOVERABILITY: NON-MONOTONE TRANSITIONS ONLY (prior value + warrant) PLUS FULL RETENTION OF SUPERSEDED ASSERTIONS — NO COMPLETE HISTORY, NO EVENT SOURCING, NO APPEND-ONLY STORAGE IMPLIED — RECONSTRUCTIBILITY: MEASURED FACTS ONLY, AND ONLY AGAINST A PINNED CORPUS STATE (ADJUDICATIONS NEVER RECONSTRUCTIBLE) — NO PERSISTENCE ARCHITECTURE SELECTED — SCHEMA v3 NOT BEGUN.**
