# Classification ≠ Placement — Disposition, and Two Artifacts That Already Implement It

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Repository Integrity Gate:** ✅ PASSED. **No file moved · no directory created · no standard amended · no ruling minted.**

---

## 1. Six approvals — recorded, with ruling text drafted so issuance is one word

| # | Approved principle |
|---|---|
| 1 | The repository is a **workspace/container, not a domain** |
| 2 | The repository **contains documentation for** multiple domains |
| 3 | Repository organization follows **domains, not bounded contexts** |
| 4 | **`engineering/` represents cross-product engineering scope**, not a business domain |
| 5 | **ES-005 stays generic** — resolve by rule, never by enumerating domains |
| 6 | **R-39 is the governing precedent before introducing new repository structures** |

**Still not minted.** **R-34**: *authority is created only by explicit issuance.* *"I think the ARB can safely approve"* is a recommendation **to** the ARB. I have flagged this twice and will not press it a third time — instead, **draft register entries are prepared below so issuance costs one word.**

> **Proposed, ready to append to `ADR-AIP-LOG-Platform-Rulings.md` on the word "issue":**
>
> **R-65 — Repository is a workspace, not a domain.** A repository is a container; it hosts artifacts belonging to domains and is never itself a modeled thing. Corrects the *"repository = three bounded contexts"* phrasing in R-37's Class-A aside (unruled; no dependency).
> **R-66 — Documentation is organized by DOMAIN, not by bounded context.** Bounded contexts live inside domains (`app/Contexts/` holds 11 inside PublicDigit alone); a root per bounded context would scatter, not organize.
> **R-67 — `engineering/` expresses cross-product SCOPE, not a business domain.** It is the outcome of the Scope decision, not a peer of PublicDigit / PKS / KnowledgeOS.
> **R-68 — ES-005 resolves placement by rule, never by enumeration.** Domains are configuration; naming them in rule text would make every future domain an amendment.
> **R-69 — R-39 is the governing precedent before new repository structure.** Where a governance exception has already solved a placement problem, cite it; do not introduce structure.

## 2. My dichotomy was false, and the third option is the good one

I wrote: *"stewardship resolves placement **only if** stewardship implies location"* — then offered two branches (implies location / orthogonal metadata, placement open).

> **There was a third branch and I did not offer it: Location is *derived* from the classification by an explicit policy — neither identical to Stewardship nor leaving placement open.**
>
> **The dichotomy did the damage: by making "implies" the only route from classification to location, it made coupling look necessary.** Separating the four properties — **Scope · Steward · Maturity · Location** — with Location as a **derived** value, is cleaner and it is the correct correction.

**And *classification answers "what is this?", placement answers "where does it live?"* is the right generalization.** It is also why the earlier reasoning kept stalling: **I was deriving location by re-arguing classification each time, instead of reading a classification and applying a rule.**

## 3. The principle is already practiced — two artifacts implement it today

### 3.1 ES-005.3 is already a derivation function

**Read as a table rather than as prose, the clause *is* classification → location:**

| Classification test | Derived location |
|---|---|
| *"Could a different project adopt it **unchanged**?"* → yes | `engineering/` |
| *"Needs project context or evidence"* | the project |
| *"Active session state"* | the runtime mount |
| *"Research … until promoted through qualification"* | project-side **(maturity override)** |

> **ES-005.3 does not need to *become* a derivation rule. It already is one.** Its defect is the **input set** — it reads **Scope** and **Maturity**, and has no input for **Steward** or **Domain**. **That is a smaller change than adopting a new modelling principle: add inputs to a function that already exists.**

### 3.2 The artifact already declares the whole triple — in its own header

**`Layer_Verification_Rule.md`, lines 3–4:**

| Declared | Field | Value |
|---|---|---|
| **Scope** | `**Class:** Engineering Platform methodology module` | cross-product |
| **Steward** | `**Owner:** Decision Authority` | Engineering/DA |
| **Maturity** | `**Status:** 🟡 PROPOSED — NOT ADOPTED` | research |

> **Classification is already declared, already independent of location, and already on the artifact.** **ES-005.3 simply does not read it** — it re-asks its own portability question instead of consuming the declared `Class`. **The separation the ARB is about to approve is not missing from the repository; it is unwired.**

### 3.3 The knowledge-card schema already implements the four-property model — including the case I called undefined

**`docs/knowledge/_meta/knowledge-card.template.md`:**

| Card field | Its own comment | Maps to |
|---|---|---|
| `status:` | *"lifecycle position"* | **Maturity** |
| `authority:` | *"trust/source — **independent of status**"* | independence **stated explicitly in the schema** |
| `owner:` | *"person/role"* | **Steward** |
| `bounded_context:` | *"one of `schema/bounded-contexts.yaml` **or `global`**"* | **Domain — with `global` as the cross-product value** |

> **`global` is precisely the cross-product / no-domain case I called an "undefined cell." The schema has had a value for it all along, and it is machine-validated (`npm run knowledge-lint`).**
>
> **Two honest limits.** The field is named `bounded_context`, **the granularity the ARB just corrected** — right shape, wrong unit; reconciling it to *domain* is real work. And the card schema is scoped to `docs/knowledge/`, **not repository-wide**, so it is a precedent and a candidate carrier, not an existing repo-wide mechanism.

## 4. Two consequences worth stating before the decision

**4.1 The derivation model is approvable under R-37 whatever its scope turns out to be.** It creates no directory and reorganizes no document. **So question (1) — does R-37 bind `docs/`? — does not gate it.** That unblocks the modelling decision from the freeze question, which is a real ordering gain.

**4.2 The example's output is not part of the model.** The illustrative policy ends *"Default Location = Engineering staging area"* — a value the ARB defers in the same message. **Under R-69/R-39 the same three inputs could equally derive *"`engineering/`, under a recorded exception."*** **The derivation model can be approved while every candidate output stays open** — worth saying so the illustration doesn't get read as the ruling.

## 5. The next question — adopted, with the finding folded in

> **Should repository placement be derived from artifact classification (Scope · Stewardship · Maturity), while keeping classification independent from physical location?**

**Adopted as framed.** **Sharpened by §3: the answer is already YES in practice** — ES-005.3 derives, the artifact declares, the card schema separates. **So the decision is less "adopt a new model" than "make the existing derivation explicit and complete."**

| | **YES** | **NO** |
|---|---|---|
| **ES-005.3** | recognized as a derivation function; **add `Steward` and `Domain` as inputs** | stays as-is; placement continues to be argued per artifact |
| **Classification carrier** | the declared header fields (`Class` · `Owner` · `Status`) become **inputs**, not prose | remains prose |
| **Card schema** | candidate repo-wide carrier; **`bounded_context` → `domain` reconciliation required** | untouched |
| **Structure** | **none** — approvable under R-37 either way | none |
| **Consequences become mechanical** | `Layer_Verification_Rule.md`, the staging question, the 92 documents — **all outputs of the rule** | each stays a separate architectural debate |

**Sequence, adopted as the ARB set it:** **(1) approve the classification model → (2) define derived placement rules → (3) apply them to existing artifacts.** **`Layer_Verification_Rule.md` remains where it is until step 3, by construction.**

## 6. One observation about this whole exchange

**Seven times now, the mechanism was already in the repository** — ES-006.1, ES-005.3, ES-005.2 + R-37, the DA's product clarification, R-39, and now the derivation function and the card schema. **And twice the ARB supplied something canon genuinely lacked: the *domain* dimension, and the *classification ≠ placement* separation.**

> **The division is consistent and worth naming: the repository keeps already containing the mechanism; the ARB keeps supplying the vocabulary that makes the mechanism visible.** Neither substitutes for the other — **but it does mean my first move should be a search, and my proposals should be about naming rather than building.**

---

**Traceability:** **R-34** (why nothing was minted) · **R-39 / proposed R-69** (precedent before structure) · **R-37** (Class-A aside corrected by proposed R-65; freeze does not gate a non-structural model) · **ES-005.3** (already a derivation function; inputs incomplete) · ES-005.2 · ES-001.1 · `engineering/knowledge/methodology/Layer_Verification_Rule.md` lines 3–4 (`Class` · `Owner` · `Status` declared) · **`docs/knowledge/_meta/knowledge-card.template.md`** (`status` / `authority` *"independent of status"* / `owner` / `bounded_context` **or `global`**) · `npm run knowledge-lint`. **Builds on** `2026-08-01-stewardship-disposition-record.md`. **No file moved · no directory created · no standard amended · no ruling minted.**
