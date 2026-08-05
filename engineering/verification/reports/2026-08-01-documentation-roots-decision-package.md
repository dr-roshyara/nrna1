# Domain-Oriented Documentation Roots — ARB Decision Package

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Status:** **PREPARED — not issued.** Outcomes are **not pre-filled**; the ARB decides.
**Repository Integrity Gate:** ✅ PASSED. **No file moved · no directory created · no standard amended.**

---

## 1. The three refinements — all three hold, and two of them do more than tighten wording

### R1 · *"The repository **contains documentation for** multiple domains"* — adopted

**Accepted, and it is not stylistic.** A repository is a **container**, not a modeled thing. *"The repository is multi-domain"* reifies the workspace into a domain, and nothing in DDD licences that.

> **It also independently diagnoses a defect already on the record.** R-37's aside reads *"repository = three bounded contexts (Product · Engineering · Runtime)"* — **the same category error, twice over**: a container treated as a context, at the wrong granularity. R-37 marks that line *"Class A, not ruled"*, so nothing depends on it.
>
> **Two findings reached the same defect from different directions — the vocabulary check and the container/domain distinction.** That convergence is the reason to fix the wording rather than merely prefer it.

**Working phrase going forward: a multi-domain workspace hosting artifacts that belong to domains.**

### R2 · Scope · Domain · Maturity — adopted, with one precision

**The renaming is right, and *"Engineering is the outcome of the Scope decision, not a domain"* is the load-bearing correction.** My earlier table listed *"engineering ownership"* as a peer dimension; it is not. It is **one branch of Scope**.

**This maps onto ES-005.3's existing clauses exactly — confirmation, not invention:**

| Dimension | ES-005.3's own words | Outcome |
|---|---|---|
| **Scope** | *"Could a different project adopt the document **unchanged**?"* | Yes → `engineering/` · No → project-side |
| **Maturity** | *"research artifacts remain project-side **until promoted through qualification**"* | staged → qualified |
| **Domain** | — | **absent** |

> **The one precision: these are not orthogonal axes, they are a decision tree — which is how the Chair drew it, though "orthogonal" is how it was described.**
>
> **Domain applies only on the product-specific branch.** A cross-product artifact has no domain — that is what *cross-product* means. **Maturity applies to both branches**, and this is where the interaction bites: **for an unqualified artifact, Maturity overrides Scope for placement.** Cross-product + unqualified is still staged project-side.

### R3 · Generic rule, domains as configuration — adopted, and it has precedent inside the standard being amended

> *"If an artifact is project-specific, place it under the documentation root of the domain to which it belongs."*

**Correct, and consistent with rule parsimony (ES-001.1) and ES-005.4 (one rule → one home).** Naming PublicDigit, PKS and KnowledgeOS in rule text would make every future domain an amendment.

**The precedent is inside ES-005 itself:** ES-005.2 already keeps its enumeration out of the rule — *"reserved namespaces are **documented (README table)**"* — and the Standards Index registers the home: **`engineering/README.md`**. **"Domains are configuration" is how this standard already works for namespaces.**

**Consequence to state plainly: the PKS-membership question does not disappear, it relocates.** It becomes a **registry entry** rather than a governance amendment — which is better, but it still needs a home and a naming authority. **R-42 forecloses one candidate:** the Platform Registry *"governs AI Engineering Platform assets only"*, and project-side artifacts *"execute at no AI runtime moment and carry no AIP lineage."* **The domain registry cannot live there.**

## 2. The refined model has one undefined cell — and the artifact that started this sits in it

**Apply Scope → Domain → Maturity to `Layer_Verification_Rule.md`:**

| Dimension | Value | Source |
|---|---|---|
| **Scope** | **cross-product** — a general engineering heuristic, adoptable unchanged | ES-005.3 clause 1 |
| **Maturity** | **research** — *"🟡 PROPOSED — NOT ADOPTED … non-binding … a recommended heuristic, never authority"* (R-34) | the file's own status line |
| **Domain** | **none** — cross-product artifacts have no domain, by definition | R2 |

**Maturity says: stage it project-side. Domain says: project-side is partitioned by domain. The artifact has no domain.**

> ### **Cross-product + unqualified has nowhere to go once project-side becomes domain-oriented.**
>
> **This is not hypothetical.** It is the exact artifact whose placement opened this thread, and it currently sits in `engineering/knowledge/methodology/` — **its qualified destination, reached before qualification.** The reason it never relocated cleanly is now visible: **the model had no cell for it, so neither did the repository.**

**Two shapes would close the cell — recorded as shapes, not recommended:**

| Shape | Idea | Cost |
|---|---|---|
| **Non-domain staging root, project-side** | one root for cross-product artifacts awaiting qualification | a root that is not a domain, inside a domain-partitioned tree |
| **Pre-qualification area, engineering-side** | unqualified cross-product methodology stages within `engineering/`, marked unqualified | weakens *"`engineering/` = qualified"*, the property ES-005.3 clause 2 exists to protect |

**Either way, the amendment must define this cell explicitly** — otherwise the same artifact drifts to the same wrong place for the same reason.

## 3. The decision, in ruleable form

> # **Does the repository's evolution from documenting one domain to documenting multiple domains require documentation roots to become domain-oriented?**

**Adopted as framed.** It is expressed in DDD language, independent of current products, prejudges no folder structure, and rests on **measured** evidence — which is what R-37's reversed burden of proof requires.

**Evidence of record, unchanged:** ES-005.1 declares `docs/` to be *"what PublicDigit is"*; **89 of 183 files in `docs/implementation/` are `PKS_*` (49%)**, 3 are `KnowledgeOS_*`. Three domains carry accepted ARB discovery artifacts. **501 inbound references** to `docs/implementation/`, **119** of them to `PKS_*`.

### Options and consequences — not pre-filled

| | **Option A — No** | **Option B — Yes** |
|---|---|---|
| **ES-005** | unchanged | **ES-005.3 amended**: *"the project"* → a generic domain-resolving rule |
| **The 92 non-PublicDigit documents** | **need a different justification for where they sit** — the 49% measurement stands either way | routed by domain as artifacts move |
| **The undefined cell (§2)** | **does not arise** — project-side stays unpartitioned | **must be defined before any move** |
| **Domain membership** | not required | **required, PKS first** — 89 files cannot route to an unnamed root |
| **Where domains are enumerated** | n/a | a **registry/config home** with a naming authority; **not** the Platform Registry (R-42) |
| **Structure** | n/a | **reserved namespaces** per ES-005.2 — directories appear as artifacts move; nothing pre-created |
| **`Layer_Verification_Rule.md`** | cross-product, unqualified, project-side pending qualification | **the same** — plus §2 must say *where* |

### Ordering — unchanged, and still first

1. **Does R-37's *"no more document reorganizations"* bind `docs/`, or only `engineering/`?** *(Its operational terms name only `engineering/`; the ruling text is unqualified.* **If it binds, everything below waits for the retrospective.**)
2. The decision above.
3. If **Yes**: amend ES-005.3 · define the §2 cell · name the domain registry and its authority · assign PKS · reserved namespaces.

**Nothing in this package requires action today, and the ~900-file blast radius is a reason to decide carefully rather than quickly.**

---

**Traceability:** ES-001.1 (rule parsimony) · ES-005.1 · **ES-005.2** (reserved namespaces; enumeration-as-configuration precedent, home `engineering/README.md`) · **ES-005.3** (clause 1 Scope · clause 2 Maturity · Domain absent) · ES-005.4 · ES-006.1 · **R-34** (authority only by explicit issuance — the file's own status) · **R-37** (scope unsettled; reversed burden of proof; *"repository = three bounded contexts"* **Class A, not ruled**) · **R-42** (Platform Registry excludes project-side artifacts) · **AIP-14**. **Builds on** `2026-08-01-multi-domain-repository-finding.md` and `2026-08-01-docs-product-orientation-finding.md`. **No file moved · no directory created · no standard amended · no outcome pre-filled.**
