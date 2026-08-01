# ARB Classification Model Approval — Decision Record

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Commission:** Principal Architect Instruction — *ARB Classification Model Approval*. Decision 1: the classification model. Decision 2: the derivation principle.
**Status:** **PREPARED FOR ISSUANCE — not ratified here.**
**Repository Integrity Gate:** ✅ PASSED. **No file moved · no directory created · no standard amended · no ruling minted.**

---

## 0. Why this record recommends rather than decides

**The commission says *"decide."* I am not the authority that can.** **R-34:** *authority is created only by explicit issuance.* And the standing rule this programme has applied all session: **readiness is evidence; acceptance is authority; analysis cannot ratify itself.**

> **So this record does everything short of the act: it states the basis, stress-tests the model, names exactly what each decision binds and does not bind, recommends, and drafts the ruling text.** **Issuance is one word.**

**The commission's own text supports this reading** — it distinguishes what *"appears fully evidenced"* from what *"requires authority."* That distinction is the one being honored.

## 1. Decision 1 — the classification model

### 1.1 Two defects in the model as drawn

**The prose is right; the diagram contradicts it.**

| # | Defect | Correction |
|---|---|---|
| **1** | **`Location` is drawn as a *child of* Classification.** The model's purpose is to separate them; nesting Location inside Classification **re-couples exactly what the model exists to divide** | **`Location` is an *output*, not a member.** It belongs outside the box |
| **2** | **`Domain` is absent from the Decision-1 box** — though it was approved two decisions ago as the organizing unit, and is one of the two inputs Decision B intends to add | **`Domain` is a classification property**: *which domain's knowledge is this?* is a fact about the artifact, answerable without knowing where it sits |

**Recommended form:**

```
Classification (what is this artifact?)
    ├── Scope        cross-product | product-specific
    ├── Steward      who curates it
    ├── Maturity     research | qualified | adopted
    └── Domain       the domain it belongs to  (N/A when Scope = cross-product)

Location = f(Classification)          (where does it live?)   — DERIVED, not a member
```

**This is the Chair's model, with Location moved out and Domain moved in.** Both corrections follow from the Chair's own prose (*"Location is derived, not identical"*) and prior approvals (*domain is the organizing unit*).

### 1.2 The evidentiary basis — stated at its true strength

| Property | Already present as | Where |
|---|---|---|
| **Scope** | `**Class:** Engineering Platform methodology module` | the artifact's own header |
| **Steward** | `**Owner:** Decision Authority` · card `owner:` | header · card schema |
| **Maturity** | `**Status:** PROPOSED — NOT ADOPTED` · card `status:` *"lifecycle position"* | header · card schema |
| **Domain** | card `bounded_context:` *"…**or `global`**"* | card schema |
| **Independence of classification from location** | card `authority:` *"trust/source — **independent of status**"* | **stated in the schema itself** |
| **Derivation** | ES-005.3's four classification→location branches | the standard |

> **The honest strength of the claim: the concepts all exist, in three separate places, and no single coherent implementation exists.** ES-005.3's derivation is **implicit with incomplete inputs**; the card schema is **richer but scoped to `docs/knowledge/`** and uses **`bounded_context`, the granularity just corrected**; the header fields are **prose, read by nothing**.
>
> **That is sufficient for Decision 1 — it shows the model is descriptive rather than speculative, which is what R-29/R-37's reversed burden of proof asks — and it is not sufficient to claim the model is already in force.**

### 1.3 One input to step 2, surfaced now and deliberately not decided

**The card schema carries *five* properties, not four:** `status` (maturity) **and** `authority` (*"trust/source"*) are **separate fields, explicitly independent**. The model has no slot for **trust/provenance**.

**Whether trust is a fifth classification property or a facet of Maturity is a real question** — and it matters only when a carrier is chosen. **Recorded as a step-2 input. Not decided.**

### 1.4 Recommendation — Decision 1

> **APPROVE, with the two corrections in §1.1** (Location out, Domain in).
>
> **Risk of approving: low and reversible.** It is a conceptual model; it creates no structure, moves nothing, and binds no path. **Risk of not approving: placement continues to be re-argued per artifact** — which is the failure this thread has demonstrated five times over.

## 2. Decision 2 — the derivation principle

> *"Repository placement shall be derived from artifact classification rather than determined independently for each artifact."*

### 2.1 What approving this actually binds — the part worth being precise about

| Approving Decision 2 **does** | Approving Decision 2 **does not** |
|---|---|
| **Make placement arguments that appeal to anything outside the classification inadmissible** — *"it's useful here"*, *"easier to find"*, *"it already lives there"* cease to be reasons | **amend ES-005** — the standard's inputs are extended in the next commission, under authority |
| Make existing placements **auditable** against the rule, once step 2 defines it | **oblige a re-audit today**, or invalidate any current placement |
| Convert `Layer_Verification_Rule.md`, the staging question and the 92 documents into **outputs of a rule** | **decide any of those outputs** |
| Fix the **order** of future work: classify → derive → place | **create or move anything** |

> **The teeth are in the first row.** That is what approval buys and it is the whole benefit: **an argument about placement that cannot be grounded in a classification property is out of order.**

### 2.2 Recommendation — Decision 2

> **APPROVE.** **It is the weaker of the two claims and the better evidenced:** ES-005.3 already derives placement from classification for **four** cases. **Decision 2 does not introduce derivation — it declares the existing practice as the governing principle and forbids the ad-hoc alternative.**

## 3. Out of scope — confirmed untouched

**No folder created. `docs/knowledgeos/` does not exist. `Layer_Verification_Rule.md` has not moved. ES-005 is unamended. No repository restructuring.** **Verified, not asserted:** every commit this session contains only verification reports and `.claude/` runtime files.

## 4. Draft ruling text — ready to append on the word "issue"

> **R-70 — The Artifact Classification Model.** An artifact is classified by **Scope · Steward · Maturity · Domain** (Domain N/A when Scope = cross-product). **Classification answers *what is this artifact?*; placement answers *where does it live?*; the two are independent.** **Location is a value DERIVED from classification, never a member of it.** Descriptive basis: the artifact header fields (`Class` · `Owner` · `Status`), the knowledge-card schema (`status` · `authority` *"independent of status"* · `owner` · `bounded_context` **or `global`**), and ES-005.3's existing classification→location branches. **Open, routed to the placement-derivation commission:** whether the card's `authority` (trust/source) is a fifth classification property or a facet of Maturity; reconciliation of `bounded_context` to the approved unit, **domain**.
>
> **R-71 — Placement Is Derived, Never Ad Hoc.** Repository placement shall be derived from artifact classification rather than determined independently per artifact. **Consequence: a placement argument that cannot be grounded in a classification property is out of order.** Does **not** amend ES-005, oblige a re-audit of existing placements, or authorize any structural change.
>
> *(Also pending from the prior disposition, unminted: **R-65…R-69** — repository-as-workspace · domain-not-bounded-context · `engineering/` as cross-product scope · ES-005 generic · R-39 as governing precedent.)*

## 5. Deliverables authorized on approval — prepared, not started

| # | Package | Scope |
|---|---|---|
| **1** | **ES-005 Amendment Package** | add **`Steward`** and **`Domain`** as ES-005.3 inputs · replace *"the project"* with a domain-resolving rule · choose the classification **carrier** (header fields vs repo-wide card) · reconcile `bounded_context` → `domain` · dispose the `authority`/trust question |
| **2** | **Repository Restructuring Package** | the derived consequences: `Layer_Verification_Rule.md` · the staging question · the 89 PKS + 3 KnowledgeOS documents · domain roots as **reserved namespaces** per ES-005.2 |
| **3** | **Gate question** *(from the stewardship disposition)* | should a gate assert maturity, so *"`engineering/` = qualified"* is enforced rather than declared? Today nothing asserts it |

**Package 2 remains blocked on the R-37 scope question** — *does "no more document reorganizations" bind `docs/`?* **Package 1 is not blocked by it** (a standards amendment reorganizes nothing).

---

**Traceability:** **R-34** (why this record recommends rather than ratifies) · **R-29 / R-37** (reversed burden of proof; scope of the reorganization freeze still unsettled; Package 2 blocked) · **R-39 / proposed R-69** (precedent before structure) · **ES-005.3** (the existing derivation function; inputs incomplete) · ES-005.2 · ES-001.1 · `engineering/knowledge/methodology/Layer_Verification_Rule.md` (header classification) · `docs/knowledge/_meta/knowledge-card.template.md` (four properties plus `authority`). **Builds on** `2026-08-01-classification-placement-separation-finding.md` · `2026-08-01-stewardship-disposition-record.md`. **No file moved · no directory created · no standard amended · no ruling minted.**
