---
artifact: UNCERTAINTY-NONIDENTIFIABILITY-MISSINGNESS
mandate: 20260830 re-verification §7
date: 2026-08-30
status: **ALL THREE "SCOPE EXCLUSIONS" ARE FALSIFIED — the corpus contains formal constructions for all three; the AUTHORIZED model contains none**
---

# The Three "Inexpressible" Capabilities

`THEORY-CLOSURE-AUDIT.md` §24 declared three capabilities **INEXPRESSIBLE**, called them scope
exclusions rather than criteria failures, and closed on that basis. The mandate requires searching
the corpus for an existing resolution **before** proposing anything (§15). That search was performed.

> ## HEADLINE
> **The corpus already contains a formal, typed construction for each of the three.** They are absent
> from the verifier's `K = (𝒜, ℛ)` and absent from the **authorized** model v0.2 — but they are not
> absent from the corpus. The audit's three exclusions rest on a **discovery failure**, not on a
> mathematical limit.
>
> **Independence:** all sources below are committed on or before **2026-08-28**; this programme began
> **2026-08-29**. **None of this evidence can be an echo of a verifier finding.**

---

## 1. UNCERTAINTY — previous verdict **FALSIFIED**

**Previous claim:** *"no probability space in 1664 files; `str` is ordinal, so no measure. Step 246's
`KnowledgeOS = Probability Distribution` remains `SOURCE CLAIM`, unsupported."*

**What the corpus actually has** — `step-031 §31.22–31.24`, three consecutive results:

```
31.22  probability object typed:  q = (H, P, Model, Context, Time, Evidence)
31.23  Unknown(H)  is a TYPE DISTINCTION, not  P(H) = 0.5
31.24  U(H) = (type, value, model, scope, source)
       type ∈ { Probability, Interval, SetValued, Unknown, Qualitative }
```

**31.24 is exactly the object the audit said does not exist**, and it is *better* than the thing the
audit looked for. The audit searched for a **probability space** and, not finding one, concluded
uncertainty was inexpressible. **31.24 explicitly refuses to require one** — *"This is more flexible
than forcing every assertion into a probability."*

### 1.1 Where does uncertainty live? — the candidates, tested

| Candidate | Type | Verdict | Counterexample |
|---|---|---|---|
| part of `Assertion` | new field `u` | **INADEQUATE** | `id = H(P,e,c,t,Π)` excludes `u`, so two assertions differing only in uncertainty are **the same assertion**. Updating uncertainty is then not a state change. |
| part of `Evidence` | field on each item | **INADEQUATE** | Uncertainty *about the claim* ≠ reliability *of an item*. Three impeccable observations of an intrinsically noisy quantity: every item certain, the claim uncertain. Unrepresentable. |
| a relation in `ℛ` | `𝒜 × 𝒜 × Type` | **REFUTED** | `ℛ` is binary between assertions; uncertainty is unary. Encoding it needs a self-loop with a payload; `ℛ` carries no payload and `ℛ_sup/ℛ_ref/ℛ_der` are required acyclic. |
| an annotation outside `K` | external map | **INADEQUATE** | Fails exactly as Provenance-model-B failed (canonical §9): it puts the value outside the content that determines `id`, so merge and dedup lose it. **The theory has already run this experiment and rejected the shape.** |
| a probability distribution | `P(H\|E,C,M)` | **REFUTED by the corpus** — 31.23: `Unknown(H)` must not be encoded as `P(H)=0.5`. Requiring a distribution forces the exact type error the corpus names. |
| **property of the evidence set, typed** | **`U(H)=(type,value,model,scope,source)`** | ✅ **the corpus's answer** | survives all five above: unary, carries a payload, names its own model and scope, and admits `Unknown` as a *type* rather than a value |

### 1.2 What is still missing

`U`'s **update and merge semantics are not defined** — no `U × U → U`, no rule for combining
`Interval` with `Probability`, no equality on `U`. And `Σ = (dir, str)` and `U` **overlap without a
stated relation**: both grade epistemic force, one ordinal-derived, one typed-stored.

> **Verdict: `SOURCE ESTABLISHES` the object · `PROPOSED` its algebra · `NOT ADOPTED` in v0.2.**
> **Not `INEXPRESSIBLE`.**

---

## 2. NON-IDENTIFIABILITY — previous verdict **FALSIFIED**

**Previous claim:** *"a property of the evidence lattice, not of `𝒜` or `ℛ`"* → therefore excluded.

**What the corpus actually has** — `step-031 §31.17–31.21`, a complete and correct treatment:

```
31.17  W_t                       the relevant real-world state
31.18  Ω : W → O                 the observation mechanism;  W --Ω--> O --f--> K
31.19  Ω(W₁)=Ω(W₂) ∧ W₁≠W₂  ⇒   "No algorithm can recover information that the
                                  observation function destroys."   (information-theoretic)
31.20  Identifiable(g,Ω)  ⟺  ∀W₁,W₂ :  Ω(W₁)=Ω(W₂) ⇒ g(W₁)=g(W₂)
31.21  ∃W₁,W₂ : Ω(W₁)=Ω(W₂) ∧ g(W₁)≠g(W₂)
       ⇒ KnowledgeOS must be allowed to return  **Underdetermined**
```

This is the standard, correct definition of identifiability. **It is formal, typed, quantified, and
carries a required return value.** The corpus calls it *"one of the strongest mathematical
foundations of the whole architecture."*

### 2.1 Why the audit could not see it

`Identifiable(g,Ω)` quantifies over a **world space `W`** through an **observation function `Ω`**.
The canonical theory's eight-layer ontology begins at `(ℰ, 𝒟, V_D, Time, Origin)` — **entities, not
worlds** — and has **no `Ω`**. The property is therefore not *false* in that theory; it is **not
statable**. The audit correctly observed that it is not a property of `𝒜` or `ℛ` and drew the wrong
conclusion: not *"therefore excluded"* but ***"therefore a layer is missing beneath `Observation`."***

### 2.2 The six states, and whether they are genuinely distinct

| State | Formal condition | Distinct? |
|---|---|---|
| **Unknown** | no assertion, dimension recognised | yes |
| **Missing** | no observation available | yes — differs from Unknown by *why* |
| **Ambiguous** | `Ω(W)` maps to multiple readings under an interpretation function | yes |
| **Underdetermined** | `∃W₁,W₂ : Ω(W₁)=Ω(W₂) ∧ g(W₁)≠g(W₂)` — **current** observations insufficient | yes (31.21) |
| **Non-identifiable** | the above holds **for every admissible `Ω` of the current type** — more observations of the same kind cannot help | **yes, and this is what 31.19 names** |
| **Conflicted** | `∃` supporting **and** contradicting evidence, both active | yes — a property of `e`, not of `Ω` |

**`Underdetermined ≠ Non-identifiable` is the sharpest of these, and the corpus supports it:** 31.21
is relative to *"current observations"*; 31.19 is an *information-theoretic limit*. **More data
resolves the first and can never resolve the second.** The audit's single label collapsed both.

**Where it lives:** in `Ω`, not in `K`, not in `Evidence`, not in `Context`. It is a property of the
**observation function** evaluated against a **property `g`**. Forcing it into `K` would be the
category error the corpus's own `Ω`-based treatment avoids.

> **Verdict: `CORPUS ESTABLISHES` (formal, typed, with a required output value) · `NOT ADOPTED`.**
> **Not `INEXPRESSIBLE`.**

---

## 3. MISSINGNESS — previous verdict **FALSIFIED, decisively**

**Previous claim, verbatim:** *"'not asked' and 'absent' are indistinguishable. Attack succeeded;
unresolved."* and *"**A theory that cannot say 'nobody ever asked' is closed, not finished.**"*

**The corpus says "nobody ever asked".** `20260826-105229_research-synthesis-zero-lens-…md`:

```
DIMENSION
├── VALUE     = known
├── VALUE     = unknown            (Zero-A)
├── DIMENSION = not assessed       ← "NOBODY EVER ASKED"
├── DIMENSION = absent             ← investigated, determined No
├── DIMENSION = not applicable
└── DIMENSION = unresolved         (Zero-B)
```

with **five explicit non-collapse laws**, stated as a table in the same file:

| Law | Gloss (verbatim) |
|---|---|
| `UNKNOWN ≠ ABSENT` | Not knowing ≠ knowing it doesn't exist |
| `UNRESOLVED ≠ INVALID` | Not resolved ≠ false |
| **`NOT_ASSESSED ≠ LOW_CONFIDENCE`** | **Not checked ≠ low confidence** |
| `NOT_APPLICABLE ≠ UNKNOWN` | Doesn't apply ≠ unknown |
| `NO_EVIDENCE ≠ INVALID_EVIDENCE` | No evidence ≠ evidence against |

and a sixth, boxed: **`UnknownValue(D) ≠ UnknownDimension(D)`** — *"These must never collapse into one
state."*

### 3.1 A second, independent construction — the Abhāva typology

`20260822-0258-knowledgeos-abhava-absence-structured-knowledge-lens.md` (committed 2026-08-22)
supplies an **ontological** absence taxonomy and a **record shape**:

| Type | Meaning |
|---|---|
| **Prāgabhāva** | prior absence — never existed *yet* |
| **Pradhvaṃsābhāva** | destroyed — existed, removed |
| **Atyantābhāva** | absolute — impossible by constraint |
| **Anyonyābhāva** | mutual difference — this is not that |

```yaml
AbsenceClaim:            # Navya-Nyāya: pratiyogin · anuyogin · avacchedaka · sambandha
  absent_entity:         # what is absent
  locus:                 # where
  relationship:          # under which relation
  scope:                 # bounded by
  reason:                # why
  temporal_boundary:     # since when / until when
```

plus **`H-KOS-Abhava-001`: *"Absence SHALL be represented as a first-class epistemic object, not as
missing data or Boolean negation"*** and the classifier pipeline
`Observation → no evidence found → classify absence type → reason`, with the explicit warning that
`Unknown` / `True absence` / `Historical absence` / `Future absence` **"must not collapse."**

### 3.2 The eight cases the mandate lists, adjudicated against the corpus

| # | Case | Corpus construct | Covered? |
|---|---|---|---|
| 1 | **not asked** | `DIMENSION = not assessed` | ✅ Zero taxonomy |
| 2 | **asked but unanswered** | `DIMENSION = unresolved` (Zero-B) | ✅ |
| 3 | **searched, nothing found** | classifier's `Unknown` — *"no documentation found"* | ✅ Abhāva pipeline |
| 4 | **observation absent** | `Ω` undefined at that point / `VALUE = unknown` (Zero-A) | ⚠️ **partial** — needs `Ω`'s domain to be declared |
| 5 | **evidence unavailable** | `NO_EVIDENCE ≠ INVALID_EVIDENCE` | ✅ |
| 6 | **proposition unknown** | `Unknown(H)` (31.23) | ✅ |
| 7 | **explicitly refuted** | `Σ.dir = Refuting` / `Pradhvaṃsābhāva` | ✅ |
| 8 | **intentionally not represented** | `NOT_APPLICABLE` / `Atyantābhāva` (impossible by constraint) | ✅ |

**Seven of eight are directly covered; the eighth is partial.** The audit reported *zero* of five.

### 3.3 The mandate's own question, answered

> *"Is missingness an epistemic state, a property of an observation process, a property of a query
> process, or a property of the knowledge representation?"*

**It is all four, and the corpus separates them cleanly:**

| Kind | Lives in | Corpus construct |
|---|---|---|
| **ontological** — the world lacks it | `W` | the four **abhāva** types |
| **observational** — `Ω` cannot see it | `Ω` | 31.18–31.19, non-identifiability |
| **inquisitorial** — nobody asked | **`D_t`, the recognised-dimension set** | `DIMENSION = not assessed` |
| **representational** — asked, answered, not stored | `K` | `VALUE = unknown` (Zero-A) |

**Collapsing these four is exactly what happens when "missingness" is written as one capability.**

### 3.4 Why `K = (𝒜, ℛ)` can hold none of it — the load-bearing structural result

**There is no component of `K` holding the set of recognised dimensions.**

Executed (`THEORY-CONSTRUCTION-TEST.md`, probe `MISSINGNESS-NOT-ASSESSED`): a dimension never assessed
and a dimension assessed with no result **both render as: no assertion mentioning that dimension.**
They are **provably indistinguishable in `K`**, because absence-of-an-assertion is the only available
representation and it is one value, not two.

The Zero model does not have this problem, because it carries the layer separately:

```
Ω     = potential knowledge space
D_t   = dimensions currently recognised          ← the missing component
K_t   = knowledge state over recognised dimensions
Z_t   = Ω \ Represented(K_t)                     what remains unrepresented
```

> **`D_t` is the smallest missing component.** With it, *"not assessed"* is
> `D ∈ D_t ∧ ¬∃a ∈ 𝒜 : a.P.D = D` — decidable in `O(n)`. Without it, the statement has no
> truth-maker.

---

## 4. Adoption status — the honest picture

| Capability | Raw corpus (Stratum 1) | **AUTHORIZED model v0.2** (Stratum 2) | Verifier's canonical theory |
|---|---|---|---|
| Uncertainty | **`U(H)` typed, 31.24** | **absent** — 0 occurrences of "uncertain" | declared inexpressible |
| Non-identifiability | **`Identifiable(g,Ω)`, 31.20** | **absent** — 0 occurrences of "identifiab" | declared inexpressible |
| Missingness | **6-way taxonomy + 5 laws + `AbsenceClaim`** | **absent** — 0 of "unknown"/"absent"/"not assessed" | declared inexpressible |

*(Measured by grep over `reviews/synthesis/model/canonical-architecture-v0.2.md`.)*

**Both prior positions were partly right and stated the wrong conclusion.** The audit was right that
these capabilities are missing from the model it audited, and wrong that the corpus cannot express
them. The corpus's own governance ruled the Abhāva material *"Enrichment, NOT a row"* and P4
**CLOSED** — so the constructions exist and were **deliberately not promoted**.

> # VERDICT
> **The three "scope exclusions" are FALSIFIED as statements about the corpus and SUSTAINED as
> statements about the authorized model.** The correct classification is
> **`SOURCE ESTABLISHES` / `CORPUS ESTABLISHES` · `NOT ADOPTED`** — a **governance** gap, not a
> **mathematical** one. **Nothing here is a verifier proposal; every construction cited was written
> between 2026-08-22 and 2026-08-28, before this programme existed.**
