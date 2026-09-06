---
artifact: 6 · POLICY-EKP-CONFORMANCE
mandate: 20260830_1931 §8, §9
date: 2026-08-30
status: **"schema = Policy" is REFUTED. The EKP contains THREE of four policy concerns, not one.**
---

# Policy in the Running EKP

## 1. The mandate forbids concluding `schema = Policy`. It is refuted.

**`knowledge-schema.yaml` is NOT a policy. It is a policy REPRESENTATION — one of four separable concerns,
and the EKP separates them cleanly.**

| Concern | Where it lives in the EKP | Present? |
|---|---|---|
| **Policy representation** | `knowledge-schema.yaml` + the four vocabulary files — declarative, data-driven, versioned (`version: 1`) | **YES — fully separated** |
| **Policy interpretation** | `knowledge-lint.php` — reads the schema and resolves field→vocabulary bindings | **YES — separated** |
| **Policy enforcement** | severity levels: 11 `error` (blocking) vs 7 `warning` (advisory) | **YES — and it is a `ResolutionBehavior`** |
| **Policy evaluation** | per-document rule evaluation producing errors/warnings | **YES** |
| **Policy composition** | — | **NO — the EKP has exactly one policy** |
| **Policy versioning** | `version: 1` in each vocabulary; docs may pin `schema_version` | **YES — partial** |

> **The schema is the `Gates` component of `Policy = (Gates, ValidityInterval, ResolutionBehavior)`.**
> Not the whole object. **`schema = Policy` is a category error, and the EKP's own architecture refutes it**
> — the linter's header says it is *"data-driven: field values are validated against the sibling vocabulary
> files rather than hard-coded"*, which is precisely representation/interpretation separation.

## 2. Classifying the 18 rules

| Rule | Descriptive schema | Structural constraint | Normative policy | Validation mechanism | Impl. invariant | Governance rule |
|---|---|---|---|---|---|---|
| `frontmatter_present` / `_parses` | | ✓ | | ✓ | | |
| `required_fields_present` | ✓ | ✓ | | | | |
| `knowledge_id_unique` | | **✓ INVARIANT** | | | | |
| `knowledge_id_pattern` | | | **✓ POLICY** | | | |
| `enum_values_valid` | | | **✓ POLICY** | | | |
| `relationship_keys_valid` | ✓ | ✓ | | | | |
| `relationship_targets_exist` | | **✓ INVARIANT** | | | | |
| `links_resolve` | | | | ✓ | ✓ | |
| `recommended_fields_present` | | | **✓ POLICY** (warning) | | | |
| `owner_present` | | | **✓ POLICY** | | | ✓ |
| `single_authoritative` | | | | | | **✓ GOVERNANCE** |
| `traceability_complete` | | | **✓ POLICY** | | | |
| `orphan_document` | | | **✓ POLICY** (hygiene) | | | |
| `circular_dependency` | | **✓ INVARIANT** (mis-typed as warning) | | | | |
| `boundary_consistency` | | | | | | **✓ GOVERNANCE** |
| `frozen_changed_without_adr` | | | | | | **✓ GOVERNANCE** |
| `review_overdue` | | | **✓ POLICY** (hygiene) | | | |

**3 invariants · 7 policies · 3 governance rules · 3 validation/structural · 2 descriptive.**

> **The policy/invariant discrimination derived in artifact 3 SEPARATES THESE CLEANLY, and it exposes a
> mis-typing:** `knowledge_id_unique` and `relationship_targets_exist` are **invariants** — no coherent
> regime allows duplicate ids or dangling references — and both are correctly `error`.
> **`circular_dependency` is ALSO an invariant and is typed as a `warning`.** That is the same defect found
> in the prior phase, now confirmed by an independent route: **it is not merely under-scoped, it is
> mis-classified.**

## 3. §9 — Who does what to Policy

**Answered from the implementation, not escalated.**

| Question | Answer, from the EKP | Evidence |
|---|---|---|
| **Who defines Policy?** | the schema author — `knowledge-schema.yaml` is the *"single contract that knowledge-lint enforces"* | file header |
| **Who owns Policy?** | the EKP itself; `owner` is a per-document field, but the schema has no owner field | **GAP** |
| **Who applies Policy?** | `knowledge-lint.php`, mechanically, on every governed document | executed |
| **Who evaluates Policy?** | the linter, producing errors/warnings | executed |
| **Who may change Policy?** | *"Bump `version` when the contract changes"* — no authorisation rule is stated | **GAP** |
| **What authorises a Policy change?** | **nothing declared.** `frozen_changed_without_adr` governs *documents*, not the *schema* | **GAP** |

> **Three of six answers are gaps, and they are the same gap: the policy object has no provenance and no
> change-authorisation.** `Policy = (Gates, ValidityInterval, ResolutionBehavior)` — **57.47 has no author,
> no owner, no authorising act.** The EKP has the same hole.
>
> **This is not normative. It is a missing component**, and the theory already has the machinery for it:
> a policy change is a **transformation** requiring **authority**, exactly like an assertion. **Recorded as
> gap G-P1 in artifact 9 — derivable, not a question for the author.**

## 4. Policy vs Governance vs Authority vs Validation vs Assessment — not collapsed

```
Policy      an OBJECT: (Gates, ValidityInterval, ResolutionBehavior)     "what must hold"
Authority   a COMPETENCE held by an actor                                "who may act"
Governance  Policy × Transformation → Admissible                         "is this act allowed"
Validation  K × Standard → Assessment                                    "does the state conform"
Assessment  P × Evidence × Context × Policy → Σ                          "how well supported"
```
**Corpus support for each separation, independent:** `Policy ≠ Authority` (202, 240) · `Policy ≠ Governance`
(252) · `Policy ≠ Verification` (139) · `Validation ≠ Assessment` (252, 232.4).

> **All four separations are corpus-established, and the EKP instantiates three of them in running code.**
> Policy (schema) · Governance (`single_authoritative`, `boundary_consistency`) · Validation (the linter).
> **Only Assessment is absent — which is exactly the theory's own remaining gap.**
