---
artifact: DDD-UBIQUITOUS-LANGUAGE-AUDIT
mandate: 20260830 re-verification §12
date: 2026-08-30
status: **SEMANTIC CLOSURE NOT ACHIEVED — 11 of 25 audited terms carry more than one semantic role**
supersedes_claim: "Every canonical term now has exactly one meaning" (THEORY-CLOSURE-AUDIT §4) — **FALSE by measurement**
---

# Ubiquitous Language Audit — Fresh Pass

The prior audit closed the UL box (`19`) on the strength of three amendments (Authority ×3,
Provenance ×4, `E`/`V` fixed) and concluded *"Every canonical term now has exactly one meaning."*
This pass re-audits from the corpus rather than from that conclusion.

**Method:** for each term, grep the corpus for definitional forms and count *distinct* right-hand
sides; then check whether a bounded-context boundary and a translation are **explicitly declared**
(the mandate's condition for a term legitimately meaning different things in different contexts).

---

## 1. Result table

Legend — **Roles**: how many distinct semantic roles are in live use. **Boundary?**: is a bounded
context declared for each role? **Translation?**: is a mapping between roles stated?

| Term | Roles | Boundary? | Translation? | Verdict |
|---|---|---|---|---|
| **Knowledge** | 1 (bounded only: `Information ⊇ Knowledge`) | n/a | n/a | ⚠️ **undefined by design** — bounded, never defined |
| **Knowledge State** | 2 — `K=(𝒜,ℛ)` · `K_t` 10-tuple (T-006) | ❌ | ❌ | 🔴 **OVERLOADED** |
| **Assertion** | 1 — `(id,P,e,c,t,Π)` | — | — | ✅ |
| **Proposition** | 1 — `P=(E,D,V)`; `Claim = Proposition` reduction | — | — | ✅ |
| **Evidence** | 3 — `(ref,polarity,state)` · `E_q=(source,observation,context,time,method,provenance)` (230.15) · `QualifiedObservation` (253) | ❌ | ⚠️ asserted as "projection", not derived | 🔴 **OVERLOADED** |
| **Observation** | 2 — a *field of* Evidence (230.15) · the *input to* qualification (253) | ❌ | ❌ | 🔴 **OVERLOADED** — a thing cannot be both the input and a component of the output |
| **Source** | 2 — evidence-`source` field · document authority-rank source | ❌ | ❌ | 🟡 mild |
| **Provenance** | 4 — `Π` · EvidenceProvenance · `History(T)` · MessageProvenance | ✅ **named separately** | ✅ | ✅ **RESOLVED** — the one clean fix |
| **Lineage** | 1 — `Π ∘ ℛ_der*` | — | — | ✅ |
| **History** | 1 — `H ‖ e_t`, external to `K` | — | — | ✅ |
| **Authority** | 4 — competence `Auth(a,r,c,p)` · 7-ary `f(Actor,Role,…)` · actor standing rank · **EKP artifact trust-grade** (`authorities.yaml`) | ⚠️ 3 of 4 | ❌ for the 4th | 🔴 **OVERLOADED** (see §2) |
| **Authorization** | 2 — the *act* · the *result* `c_t` | ❌ | ❌ | 🟡 the corpus uses one word for act and product |
| **Policy** | 2 — `Policy-as-content ∈ K_t` · `Policy-in-force (versioned)` | ✅ **v0.2 R-1** | ✅ | ✅ **RESOLVED — by the ratified model, not by the audit** |
| **Rule** | ≥2 — engineering standard (`ES-00n`) · policy gate `gᵢ` | ⚠️ different estates | ❌ | 🟡 mild |
| **Invariant** | 1 — predicate over `𝕂` that must hold in every admissible state | — | — | ✅ (the invariant-vs-policy discriminator is sound) |
| **Validation** | 2 — four predicates `StructuralValid…GovernanceValid` · `Validation : K × X → Assessment` | ❌ | ❌ | 🔴 **OVERLOADED** — one is a family of predicates, one is a function into Assessment |
| **Assessment** | 2 — `P × Evidence × Context × Policy → Σ` (232.4) · `f(Evidence,ClaimType,Model,Assumptions)` | ❌ | ❌ | 🔴 **OVERLOADED** — different arities, different codomains |
| **Verdict** | 2 — `Apply(p,d) ∈ {True,False,Unknown}` · a *governance* ruling (PASS/FAIL/DEFER) | ❌ | ❌ | 🔴 **OVERLOADED** — and dangerous: one is computed, one is decided |
| **Assurance** | ≥5 — `(A_τ,A_P,A_E,A_G)` · `BackwardTraceability` · `BackwardTraceability + ForwardLearning` · 7-tuple with Uncertainty+Validation · "composed local contracts" | ❌ | ❌ | 🔴 **THE WORST TERM IN THE CORPUS** — already `REFUTED` as definable (CB-1), still in use |
| **Sufficiency** | 2 — constitutional threshold (prose) · `Q` conjunct in 42.41 | ❌ | ❌ | 🔴 **OVERLOADED**, and **untyped in both** |
| **Unknown** | 3 — `Σ.str = None` · `Apply` third value · `Unknown(H)` type (31.23) | ❌ | ❌ | 🔴 **OVERLOADED** — the three are *not* the same thing |
| **Missing** | ≥4 — see the four-kind separation in `UNCERTAINTY-…MISSINGNESS.md` §3.3 | ❌ | ❌ | 🔴 **OVERLOADED** |
| **Conflict** | 2 — evidence-internal (`supporting ∧ contradicting` in one `e`) · assertion-level `contradicts ∈ ℛ` | ⚠️ implicitly | ❌ | 🟡 mild |
| **Contest** | 1 — `Γ = Contested` | — | — | ✅ |
| **Non-identifiability** | 2 — 31.19 information-theoretic limit · 31.21 `Underdetermined` w.r.t. *current* observations | ❌ | ❌ | 🔴 **OVERLOADED — and the distinction is load-bearing** (more data resolves one, never the other) |

**Count: 11 red · 4 yellow · 8 clean · 2 n/a.**

---

## 2. The two findings that matter most

### 2.1 `Ω` — an overload the prior audit never registered, worse than `E`/`V`

The audit's §3 fixed `E` (Entity vs Events) and `V` (Value vs Vertices) and declared UL closed. It did
not audit the Greek symbols. Measured, `Ω` carries **at least four global senses**:

| Sense | Where | Direction |
|---|---|---|
| sample space of `(Ω, ℱ, P)` | measure-theory extractions | a set of outcomes |
| **Knowledge Space** — `Zero(K_t) = Ω \ Represented(K_t)` | Zero lens | a **superset of knowledge** |
| **observation function** `Ω : W → O` | 31.18 | a **map from world to observation** |
| remaining possibilities/alternatives | conditional-evidence v1/v2 | a residual set |

plus `Ω_D` (reality space), `Ω_E` (epistemic state space), `Ω_A` (a source status tuple in
`ladder_dc_reference.py`).

> **Senses 2 and 3 are both foundational and point in opposite directions.** Any reader who combines
> them silently identifies the space of all possible knowledge with the codomain of observation —
> which would make the non-identifiability result trivially false. **This is the single most
> dangerous naming collision in the corpus and it is unregistered.**

Additionally, `# step 263.md:9` — the corpus's **latest** reconstruction — records `E` and `V` as
**"live ambiguities"**. The audit's claim that they are *"local rebindings in isolated branches"* is
contradicted by the corpus's own most recent status statement.

### 2.2 `Authority` — three senses separated, a fourth left standing

`docs/knowledge/schema/authorities.yaml` types `authority` as a **five-rank trust grade of a
document** (`authoritative · derived · generated · historical · provisional`, `single_per_topic`
enforced by `knowledge-lint`). The audit mapped this to *"Standing — an ordinal attribute"*, but its
Standing is an attribute of an **actor**; the EKP's is an attribute of an **artifact**.

**Different subject of predication ⇒ different concept.** A running linter enforces one of them, so
the overload is not academic: `authority: authoritative` is a machine-checked statement about a file
and shares its word with the competence relation that decides whether a person may commit knowledge.

**Forbidden substitution, recorded:** *never read `authority:` in EKP frontmatter as the `Auth(a,r,c,p)`
of 230.14, and never read `Auth(...)` as a document trust grade.*

---

## 3. Terms whose overload is a *type* error, not a naming inconvenience

Three of the eleven are structural, not cosmetic:

1. **Observation** — 230.15 makes `observation` a **field of** `Evidence`; 253 makes `Evidence =
   QualifiedObservation`, i.e. the **image of** an observation. **A value cannot be both a component
   of `X` and the pre-image of `X`.** The audit resolved this by declaring its own 3-field model *"a
   PROJECTION of"* the 6-field one — which reconciles the two *Evidence* shapes and leaves the
   *Observation* type error untouched.
2. **Assessment / Validation** — `Validation : K × X → Assessment` and
   `Assessment : P × Evidence × Context × Policy → Σ`. Composing them types `Validation` as returning
   a *function*, not a value. One of the two signatures must be wrong; the corpus does not say which.
3. **Verdict** — computed (`Apply → {True,False,Unknown}`) vs decided (a governance ruling). The
   estate's own operating discipline turns on never letting the first become the second
   (*"Assessment → verdict → NEVER grants authority"*). **Sharing the word is precisely the risk that
   rule exists to prevent.**

---

## 4. Forbidden substitutions — the working list

| Never write | When you mean |
|---|---|
| `Ω` for the knowledge space | ... anywhere near 31.18–31.21. Use `𝒦_pot`. |
| `Ω` for the observation function | ... anywhere near the Zero lens. Use `obs`. |
| `Authority` | a document's trust grade → **`SourceGrade`** |
| `Verdict` | a governance ruling → **`Ruling`**; a policy result → **`Admissibility`** |
| `Assessment` | the act → **`Assessing`**; the result → **`Σ`** |
| `Unknown` | the `Σ` strength floor → **`None`**; the three-valued gate result → **`Indeterminate`**; the epistemic type → **`Unknown(H)`** |
| `Missing` | ...ever, unqualified. Use the four-kind vocabulary (ontological / observational / inquisitorial / representational). |
| `Assurance` | ...ever, until CB-1 is disposed. It has five live definitions and is `REFUTED` as definable. |

---

## 5. Verdict

> **Box 19 (UL canonicalised) is NOT closed.** The prior audit closed it having fixed 2 of the 13
> live overloads and having declared the remainder resolved. **Eleven terms carry more than one
> semantic role with no declared boundary and no declared translation** — which is the mandate's own
> failure condition.
>
> **The one genuine success is `Provenance`**: four objects, four names, no shared word, each
> independently anchored. **It is the model for how the other eleven should be resolved**, and it
> shows the work is tractable — it simply has not been done.
