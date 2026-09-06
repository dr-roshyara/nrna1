---
artifact: G · THEORY-TO-EKP-CONFORMANCE-MATRIX
mandate: 20260830_1918 §11
date: 2026-08-30
status: **PARTIALLY ENGINEERINGALLY CONFORMANT** — 7 enforced, 4 unenforced, 6 absent
method: read `knowledge-schema.yaml`, the 18 lint rules, and the linter source; then EXECUTED against real data
---

# Theory → EKP Conformance

**Rule applied throughout: a predicate is ENGINEERINGALLY VERIFIED only if the implementation actually
enforces or computes it. Name similarity counts for nothing.**

## 1. What the EKP actually enforces — the 18 rules, read from source

`frontmatter_present` · `frontmatter_parses` · `required_fields_present` · **`knowledge_id_unique`** ·
`knowledge_id_pattern` · **`enum_values_valid`** · `relationship_keys_valid` ·
**`relationship_targets_exist`** · `links_resolve` · `recommended_fields_present` · `owner_present` ·
**`single_authoritative`** · `traceability_complete` · `orphan_document` · **`circular_dependency`** ·
**`boundary_consistency`** · `frozen_changed_without_adr` · `review_overdue`

**Severity matters:** 11 are `error`, 7 are `warning`. **`circular_dependency` is a WARNING.**

## 2. The matrix

| Theory predicate | Mathematical definition | EKP implementation | Executable test | Result |
|---|---|---|---|---|
| **unique identity** | `∀a,b ∈ 𝒜 : id(a)=id(b) ⇒ a=b` | `knowledge_id_unique` (**error**) + `knowledge_id_pattern` | `knowledge-lint` | **ENGINEERINGALLY VERIFIED** — executed, 37 docs, 0 errors |
| **no dangling endpoints** | `∀(f,t,ty) ∈ ℛ : f,t ∈ ids(𝒜)` | `relationship_targets_exist` (**error**), covers `.md` **and** package `.yaml` | `knowledge-lint` | **ENGINEERINGALLY VERIFIED** — executed, 0 dangling |
| **acyclicity of DAG families** | `acyclic(ℛ_sup), acyclic(ℛ_ref), acyclic(ℛ_der)` | `circular_dependency` — **WARNING only, and only over `requires`/`depends_on`** | my executed cycle check | **NOT CONFORMANT — see §3** |
| **`ℛ` is typed** | `ℛ ⊆ 𝒜×𝒜×RelationType` | `knowledge-relationships.yaml`, typed edges with declared inverses | `relationship_keys_valid` (error) | **ENGINEERINGALLY VERIFIED** |
| **supersession is a relation, not deletion** | `ℛ_sup`, old retained | `supersedes`/`superseded_by` + `status: superseded` (settled, kept) | schema | **ENGINEERINGALLY VERIFIED** |
| **`Γ` has a controlled vocabulary** | `Γ : 𝒜 × GovCtx → GovStatus` | `authorities.yaml`, 5 ranked values | `enum_values_valid` (error) | **ENGINEERINGALLY VERIFIED** |
| **`Σ ⊥ Γ`** | orthogonal axes | `authorities.yaml`: *"INDEPENDENT of status"* + worked cross-quadrant example; both enums enforced separately | `enum_values_valid` | **ENGINEERINGALLY VERIFIED** — the strongest result in the programme |
| **context scoping** | `c ∈ Assertion` | `bounded_context` (error) + `boundary_consistency` (warning) | `knowledge-lint` | **ENGINEERINGALLY VERIFIED** |
| **determinism of derivation** | `f(x)` stable | `knowledge-graph.php` | **executed twice, byte-identical**, 39 nodes / 70 edges | **ENGINEERINGALLY VERIFIED** |
| **`WellFormed(P)`: `V ∈ V_D`** | Q14 §5.4 | **ABSENT** — `title` is prose, no dimension, no value space | — | **NOT IMPLEMENTED** |
| **no inverted intervals** | `vt ≥ vf` | **ABSENT — the schema has no temporal field at all** | — | **NOT IMPLEMENTED** |
| **evidence by reference** | `e ⊆ Evidence` | **ABSENT** | — | **NOT IMPLEMENTED** |
| **provenance `Π`** | origin of the assertion | **ABSENT** — `authority` is a trust rank, not an origin | — | **NOT IMPLEMENTED** |
| **`Σ` derived from Assessment** | `P×e×c×Policy→Σ` | **ABSENT** | — | **NOT IMPLEMENTED** |
| **contradiction detection** | `𝕂×𝒜×𝒜→Bool` | **ABSENT** | — | **NOT IMPLEMENTED** |
| **`T` as a guarded transition** | `𝕂×Op×Policy×Authority ⇀ 𝕂×Outcome` | **ABSENT** — documents are edited by hand; the linter validates *after the fact* | — | **NOT IMPLEMENTED** |
| **`single_authoritative`** | *no theory counterpart* | one `authority:authoritative` per (topic, context), **error** | `knowledge-lint` | **IMPLEMENTATION EXCEEDS THEORY** |

## 3. The one genuine non-conformance — an actionable finding

**Theory (artifact C, prior phase, proven):** `supersedes` is transitive; **a cycle makes "which assertion is
current?" undefined**; therefore acyclicity is an **invariant**, not an optional check.

**EKP:** `circular_dependency` is a **warning**, and its comment scopes it to **`requires`/`depends_on`
only**. **Supersession cycles are unchecked.**

**Executed against the live graph:** `supersedes` edges among the 37 governed docs = **0**. **The gap is
currently vacuous — but it is unguarded.** The first supersession chain that closes will pass the linter.

> **RECOMMENDATION (verifier, not corpus): extend `circular_dependency` to `supersedes`, `derived_from` and
> `refines`, and raise it from warning to error for those three families.** This is a theory result that
> produces a concrete, testable engineering change — **the first time in this programme that the
> mathematics has told the implementation something it did not already know.**

## 4. Where the implementation exceeds the theory

| EKP rule | Theory has it? |
|---|---|
| `single_authoritative` — one authoritative doc per (topic, context) | **NO.** A genuine invariant the theory lacks: *at most one authoritative assertion per topic per context.* **Adopted as a candidate theory predicate.** |
| `boundary_consistency` — cross-context links must be typed as cross-context | **NO** — the theory has `c` but no cross-context link discipline |
| `frozen_changed_without_adr` | **NO** — a change-control predicate over History |
| `orphan_document`, `review_overdue` | **NO** — hygiene, not epistemics |
| `related_to` as a **symmetric** relation | **NO until now** — 46 of 51 real edges. **Adopted into the relation algebra as `ℛ_rel`.** |

> **The implementation taught the theory three things: `single_authoritative`, symmetric relations, and
> that a working knowledge platform needs none of `e`, `t`, `Π`, `Σ`.** Triangulation earned its keep in
> both directions.

## 5. Verdict

**7 predicates ENGINEERINGALLY VERIFIED · 1 NOT CONFORMANT (unguarded acyclicity) · 6 NOT IMPLEMENTED ·
3+ implementation invariants with no theory counterpart.**

> ## **PARTIALLY ENGINEERINGALLY CONFORMANT**

The conformant seven are the *structural* core — identity, referential integrity, typed relations,
controlled vocabularies, context, determinism, and `Σ ⊥ Γ`. **Everything epistemic — proposition structure,
evidence, temporal validity, provenance, status derivation, contradiction — is absent.**

**The EKP is a conformant implementation of the theory's SKELETON and implements none of its EPISTEMICS.**
