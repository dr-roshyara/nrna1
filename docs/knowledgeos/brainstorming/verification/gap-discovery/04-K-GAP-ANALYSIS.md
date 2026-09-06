# 04 — K Gap Analysis

**Mandate §9.** Executed evidence: `exec/exp_congruence.py`, `exec/exp_identity.py`,
`exec/exp_ekp_bridge.py`.

---

## 1. The census, and what kind of disagreement it is

`01-THEORY-EVOLUTION-MAP.md` §ARC B lists **28 distinct right-hand sides** for `K` across the corpus,
and Step 240 independently counted "approximately 25". The census is not the finding. The finding is
what *kind* of disagreement it is.

The mandate asks whether the competing candidates are (a) genuinely different ontologies,
(b) equivalent representations, (c) conservative extensions, (d) incomparable models, or
(e) contradictory. This session classified all 28.

| Class | Members | Count |
|---|---|---:|
| **(b) equivalent representations** — differ only in notation | `K=(V,E)` ≅ `K=(𝒜,ℛ)` when `V=𝒜`, `E=ℛ` | 6 |
| **(c) conservative extensions** — one adds components to another without contradicting | Q6 `(𝒜,ℛ,ℰ,ℋ,𝒵,ℒ)` ⊃ `(𝒜,ℛ)`; Q13 ⊃ Q6 | 4 |
| **(d) incomparable** — no morphism given or derivable | `K=(G,σ,θ,λ,π)` vs `K=(Content,Qualification,Governance)`; `K=(x,c,t,p,e)` vs anything | 11 |
| **(e) contradictory** — cannot both be the same object | `K=(A,J,T_A)` (one justified belief) vs every state-valued `K` | 1 |
| **(a) genuinely different ontologies** | `K_t ⊆ Ω` (subset of a universe) vs `K=(𝒜,ℛ)` (constructed graph) vs `𝒦=(Ω,𝓕)` (measurable space) | 3 |
| unclassifiable — insufficient definition given | the remainder | 3 |

**KG-1 (`DERIVED`, CRITICAL).** The corpus's own Step 240 refused the escape hatch *"these were
obviously projections"* and this classification shows it was right to: **only 10 of 28 are
reconcilable as representations or extensions.** Eleven are incomparable because no map between
their carrier types is ever given, three are different ontologies about *what kind of thing* `K` is,
and one is a category error.

### 1.1 The category error, stated precisely

`K = (A, J, T_A)` (Step `20260826-172732`, "the KnowledgeOS knowledge atom") is a *justified true
belief*: one proposition, its justification, and a truth assessment. Every other `K` is a **state**
containing many such items. Writing both as `K` is not a notational variant — the two have different
cardinality, different operations (a belief has no merge; a state has no truth value) and different
identity criteria.

This matters because Q7 ("What is Knowledge Itself?") builds on the atom, and later steps build on
Q6/Q13's state. **Two incompatible objects share one symbol across the two most-cited early
documents.**

### 1.2 Instability inside single documents

- `20260826-161551` gives **three** different `K` tuples (`(D,V)`, `(D,S,E,R,T)`, `(D,V,R,E,Σ,τ)`).
- Q14 ("the complete formal definition") gives **three**: Q6's 6-tuple, Q13's 10-tuple, and a *third*
  6-tuple `(𝒜,ℛ,ℰ,𝒞,𝒯,Π)`.

**KG-2 (`EXECUTED`).** A document titled *"the complete formal definition"* contains three
non-identical definitions of its subject. Step 240's registry, being organized by step, cannot see
this.

### 1.3 The `𝒦`/`K` overload

Steps 230, 247, 252, 254, 258 use `𝒦` (script) for a meta-structure and `K` for the state:
`𝒦 = (K, C, T, E, A)`, `𝒦_t = (K_t, H_t)`. Step 258 §258.25 states the distinction explicitly.

**But `𝒦` is itself overloaded:** `20260825-233107` uses `𝒦 = (Ω, 𝓕)` — a measurable space
(Doignon–Falmagne knowledge space). Same glyph, unrelated object, no cross-reference.

**Correction to a tempting reading:** these tuples are *not* self-referential. This session checked
the LaTeX at source. Reporting them as `K = (K, …)` would be a misreading, and this document does
not make it.

---

## 2. Is `K` an ontology or a representation?

The mandate's deeper question. This session's answer, `DERIVED`:

> **`K` is a representation, and treating it as an ontology is what produced the 28 tuples.**

The argument is Step 260's own. `K* = ℋ/≡` says the state *is the equivalence class of histories
indistinguishable under the mandatory operations*. Under that definition `K` has **no intrinsic
content** — its content is entirely determined by `𝒪`. Change `𝒪`, and the same reality yields a
different `K`. That is the signature of a representation, not an ontology.

Every one of the 28 tuples can then be read as a **candidate representation chosen for a different
implicit `𝒪`**:

| Tuple | The `𝒪` it is adequate for |
|---|---|
| `K=(V,E)` | reachability, lineage traversal |
| `K=(𝒜,ℛ)` | query, explain, relate |
| Q6 `(𝒜,ℛ,ℰ,ℋ,𝒵,ℒ)` | + evidence retrieval, replay, gap analysis, lineage |
| `K=(Content,Qualification,Governance)` | + admission and authorization decisions |
| `K=(G,σ,θ,λ,π)` | + typed status/annotation queries |

**This reframing is constructive, not merely critical:** it says the corpus was never really
disagreeing about what knowledge *is*. It was proposing different sufficient statistics for
different unstated operation sets. **The disagreement is downstream of the missing `𝒪`.**

---

## 3. EXECUTED: is `K = (𝒜, ℛ)` sufficient?

`exec/exp_congruence.py`. Three experiments, each against the corpus's own criterion
(Step 259: `F(H₁)=F(H₂) ⟹ ∀O: O(H₁)=O(H₂)`).

### EXP-1 — content-only fails

```
K1 = a1:Nexus.version=3.69 <vendor/api@10>
K2 = a1:Nexus.version=3.69 <forum/scrape@10>
content-only says equal? True
explain(K1) = ['<vendor/api@10>']   explain(K2) = ['<forum/scrape@10>']
content-only sufficient? False   (counterexample: explain)
```
Step 255 Class I `CONFIRMED BY EXECUTION`.

### EXP-2 — provenance-in-assertion is not what repairs it

Two states holding the **same propositions** with the **same per-proposition provenance**, differing
only in whether a `derives` relation exists:

```
same propositions?               True
same per-proposition provenance? True
withdraw('vendor'):  KA -> []      KB -> ['Nexus.version=3.69']
(content + in-assertion provenance) sufficient? False   (counterexample: withdraw)
```

**KG-3 (`EXECUTED`, HIGH).** Step 265's verdict — provenance goes *inside* the assertion — is widely
cited as what makes the state sufficient. It is not. The load-bearing component is `ℛ_der`. Step 265's
placement decision is **orthogonal** to the sufficiency question and should stop being cited for it.

### EXP-3 — sufficiency of `K=(𝒜,ℛ)` is not determined by the corpus

Two histories reaching a **byte-identical** `K`:

```
H_calm   = assert(a1)
H_stormy = assert(a1); assert(c1); relate(c1→a1, contradicts); withdraw(forum)

K(H_calm)   A=['a1']  R=[]
K(H_stormy) A=['a1']  R=[]
structurally equal? True

K=(A,R) sufficient under OPS_MINIMAL? True
K=(A,R) sufficient under OPS_FULL?    True

ever_contested(H_calm)   = False
ever_contested(H_stormy) = True
```

**KG-4 (`EXECUTED`, CRITICAL).** `K=(𝒜,ℛ)` is sufficient **iff** the mandatory operation set excludes
every history-sensitive predicate. `ever_contested` is not an exotic operation — escalation policies
that count prior contestation are ordinary governance, and the corpus's own Steps 155/179/181 discuss
exactly this kind of rule. Therefore:

> **"`K=(𝒜,ℛ)` is the minimal sufficient knowledge state" is not a theorem of the corpus. It is a
> consequence of an unstated choice of `𝒪`, and a different, equally corpus-consistent `𝒪` refutes it.**

This is the sharpest form of `EV-E1`. The corpus's terminal claim is not wrong; it is **conditional
on a premise the corpus never states**.

---

## 4. EXECUTED: identity, equality, membership

`exec/exp_identity.py`. Four defensible equalities, each sourced from a different corpus step.

### `A ∈ K?`

```
probe                                     structural  content-only  content+status  identity-only
a_forum (same prop, other source/status)  False       True          False           False
a_vend2 (same prop+prov+status, NEW id)   False       True          True            False
a_vendor (identical object)               True        True          True            True

disagreement on 2 of 3 probes
```

### `K₁ = K₂?`

Five states, partitioned:

```
structural      -> 5 classes
content-only    -> 2 classes
content+status  -> 3 classes
identity-only   -> 5 classes
```

**KG-5 (`EXECUTED`, CRITICAL).** The mandate requires `K₁ = K₂` and `A ∈ K` to be *computable*. They
are not yet **well-defined**: the same five states fall into 2, 3, 5 or 5 equivalence classes
depending on which corpus-sourced equality is used, and the corpus rules on none of them. Step 261
names the structural/semantic distinction; Step 266 marks semantic equality `🟡 domain semantics`.
Every theorem quantifying over `A ∈ K` is therefore underdetermined.

---

## 5. `K` has one real instance, and it is not where Step 267 looked

`exec/exp_ekp_bridge.py` EXP-11, against `docs/knowledge/`:

```
governed documents carrying a knowledge_id (|A|): 40
typed relationships (|R|):                        59
relation types in live use: derived_from, documents, related_to,
                            requires, state_machine, tested_by
id-typed edges whose target does not resolve:     0
```

**KG-6 (`EXECUTED`, CRITICAL — and this is the most useful finding in this document).**
`K = (𝒜, ℛ)` is **instantiated and running** in this repository, in the Engineering Knowledge
Platform. Step 267 declared `K = (𝒜,ℛ)` **"IMPLEMENTATION MISSING"** because it searched PublicDigit's
*election* domain — `GovernanceLineageGraph` (Membership/Committee/Constitutional),
`EvidenceSet` (Adjudication/Determination), `decisionId`, `integrityHash`.

Those are voting-platform classes. The EKP — `docs/knowledge/` plus `scripts/knowledge-lint.php`,
which this session ran (`Scanned 37 governed documents. ✅ All documents pass.`) — is the governed-
knowledge system, and it exhibits the theory's central object directly: a set of identified,
statused, provenanced claims with a typed relation set over them.

**Consequence for the empirical programme:** the corpus's headline empirical result — *"`K` is not
implemented"* — is **`REFUTED`**. What is true is narrower and more useful: `K`'s *structural* part
is implemented and passing; `K`'s *epistemic* part (`e`, `σ`) is absent (see `06-SIGMA-GAP-ANALYSIS.md`).

---

## 6. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **KG-1** | Of 28 `K` definitions, only 10 are reconcilable as representations or conservative extensions; 11 are incomparable, 3 are different ontologies, 1 is a category error. | `DERIVED` | **CRITICAL** |
| **KG-2** | Q14, titled "the complete formal definition", contains three non-identical definitions of `K`; `20260826-161551` contains three more. | `EXECUTED` | HIGH |
| **KG-3** | Provenance-in-assertion (Step 265) is **not** what makes the state sufficient — `ℛ_der` is. Demonstrated by a two-state counterexample agreeing on all content and all provenance. | `EXECUTED` | HIGH |
| **KG-4** | `K=(𝒜,ℛ)` is sufficient **iff** `𝒪` excludes history-sensitive predicates. A corpus-plausible governance predicate (`ever_contested`) refutes it. The terminal claim is conditional on an unstated premise. | `EXECUTED` | **CRITICAL** |
| **KG-5** | `A ∈ K` and `K₁ = K₂` are not well-defined: four corpus-sourced equalities give 2/3/5/5 partitions of the same five states and disagree on 2 of 3 membership probes. | `EXECUTED` | **CRITICAL** |
| **KG-6** | `K=(𝒜,ℛ)` **is implemented and running** — the EKP knowledge graph, `\|𝒜\|=40`, `\|ℛ\|=59`, lint passing. Step 267's "IMPLEMENTATION MISSING" is refuted; it searched the wrong bounded context. | `EXECUTED` | **CRITICAL (corrective)** |
| **KG-7** | `𝒦` is overloaded: a measurable space in `20260825-233107`, a meta-structure in Step 258. | `EXECUTED` | MEDIUM |
| **KG-8** | Reframing: the 28 tuples are candidate **sufficient statistics for different unstated `𝒪`**, not competing ontologies. The disagreement is downstream of the missing operation registry. | `DERIVED` | (constructive) |

---

**Next:** `05-ASSERTION-SEMANTICS.md`.
