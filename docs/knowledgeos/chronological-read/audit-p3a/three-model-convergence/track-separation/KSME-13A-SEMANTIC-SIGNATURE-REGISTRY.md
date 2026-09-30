---
source_track: TRACK-A-PHASE-MEASURE
derived_from: [Step 32, Step 60, Step 277, D285-6, KR-CONTR-FDE writeup, theory-08]
cross_track_dependency: none
---

# KSME-13A — Semantic Signature Registry

Every operation/predicate signature confirmed this pass and in the immediately preceding grounding forks,
with implementation readiness noted.

## Fully specified, implementation-ready

| Symbol | Signature | Source | Notes |
|---|---|---|---|
| `Validate` | `𝒜×ℰ→𝒱` (total) | Step 32 §32.1 | |
| `Infer` | `ℰ⇀𝒜` (partial) | Step 32 §32.1 | |
| `∘` (composition) | `g∘f:E→M`, defined iff `Range(f)⊆Domain(g)`, gated by SemanticTypeCompatibility | Step 32 §32.9-10 | Fully specified |
| `Conflict(p)` | `Support⁺(p)>0 ∧ Support⁻(p)>0` | Step 60 §60.5 | Fully specified, no missing parts |
| `Promote` | `Hypothesis×Evidence⇀SupportedClaim` | Step 32 §32.5 | Distinct from `Revision` — do not conflate |
| `Standing` | `(positive_support: bool, negative_support: bool)` | `20260902-175306` §12.1 | Executable dataclass spec |
| `Boundary` | `(reason, provenance, context, condition)`, typed enums given | `20260902-175306`, `20260902-163000` | Executable dataclass spec; `reason`'s exact cardinality disputed (8 vs 10, see collision registry) |
| `Assert/Retract/Supersede/Merge/Split/LinkEvidence` | see `KSME-11-BOUNDED-KERNEL-READINESS.md` for full table | Step 277 §277.24 | Explicitly labeled "subject to later type audit," not finalized |
| `Qualification` | `O×C×Π ⇀ E∪{⊥}` | Step 277 §277.15 | Consistent with `D285-6`'s `Qualify` framing |

## Named but NOT implementation-ready (signature and/or algorithm missing)

| Symbol | What's missing | Source |
|---|---|---|
| `Revision` | No typed signature anywhere — only informal arrow `K1--E-->K2` | Step 32 §32.5/23 |
| `Conflict` (Step 32 algebra tuple element) | No signature ever given, distinct from `Conflict(p)` | Step 32 §32.76 |
| `⊕` (epistemic "clean merge") | No formula distinguishing it from partial `Merge`; glyph collides with 2 unrelated operators | Step 32 §32.19 |
| `Merge` (Step 60) | Only exercised as set union in the tested case; no general partiality condition stated | Step 60 §60.22-27 |
| `Resolve` | Named, 4 input-kind categories listed, no algorithm | Step 60 §60.23 |
| `Contr` (either sense) | `step-292/04` sense: no signature/algorithm at all, only "must be defined first." `theory-08` sense: a codomain value, not a checkable function | `step-292/04`; `theory-08` |
| `Policy` | Source itself states it is unformalized ("has not fully formalized Policy or Authority," §277.36) | Step 277 §277.36-37 |
| `Authority` | Same disclosure as Policy | Step 277 §277.36 |
| `DomainPolicy`/`HumanAdjudication`/`StatisticalModel` | Named as `Resolve` input kinds, never independently defined | Step 60 §60.23 |
| `Authorize` (Step 32 sense) | `𝒟×𝒞⇀𝒳` — `𝒟`/`𝒞` never independently typed beyond pipeline role | Step 32 §32.1 |
| Composition rules `majority`/`intraframe-only`/`last-wins` | Qualitative description only in-scope; exact formulas live in `docs/knowledgeos/research/` (admissibility pending) | `theory-08`, `170000` |
| `C6`/`C7` (invariance properties) | Labels and consequences known; exact formulas out of current scope | `170000` §B.1 |

## Governing discipline

No signature in the second table may be implemented by inventing the missing part without disclosing that
invention as a new architectural decision, per KSME-13A's own stop discipline.
