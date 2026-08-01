# Should `docs/` Become Product-Oriented? — Evidence Finding

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Trigger:** ARB Chair position — *"the architecture has outgrown the rule"*; recommends opening one architectural decision rather than relocating `Layer_Verification_Rule.md`.
**Repository Integrity Gate:** ✅ PASSED. **No file moved · no directory created · no standard amended.**

---

> # VERDICT — **the Chair's core claim is CONFIRMED, and the evidence is stronger than the argument offered for it. But the artifact chosen to carry the claim is the wrong one.**
>
> **Confirmed:** `docs/` is no longer single-product. **This is measurable, not arguable.**
>
> **Disputed:** `Layer_Verification_Rule.md` is **not** evidence for the reorganization, and under the Chair's own preferred tree it would **not** move to `docs/knowledgeos/`.

---

## 1. The measured insufficiency — R-37 demands evidence, and here it is

**ES-005.1 states the premise in its own words:**

> *"`docs/` + `architecture/` + `app/` + `tests/` = **Product (what PublicDigit is)**."*

**The Chair's recollection is correct — `docs/` was only for PublicDigit.** That is exactly what the rule says. **What the repository now contains:**

| `docs/implementation/` | Files | Share |
|---|---:|---:|
| `PKS_*` — Product Knowledge System (a **separate track**) | **89** | **49%** |
| `KnowledgeOS_*` | **3** | 2% |
| Everything else (PublicDigit) | 91 | 49% |
| **Total** | **183** | |

> **Half of the folder that ES-005.1 declares to be "what PublicDigit is" is not PublicDigit.**

**The premise did not change by decision. It was falsified by accretion** — 92 documents from two other tracks arrived in the Product folder one at a time, and no rule was ever amended to admit them.

**This is precisely the form of evidence R-37 requires** (*"any proposal for architectural expansion must include evidence that the existing architecture was insufficient"*). **The insufficiency is measured, not asserted.** That distinguishes this from every proposal R-37 rejects by default.

## 2. Where I disagree with the Chair — the wrong artifact is carrying the argument

The Chair's premise is *"research should belong to the thing being researched"* — which assumes **placement encodes ownership**. **For ES-005.3, it does not.**

**Read the litmus in its own words:**

> *"**Could a different project adopt the document unchanged?** Yes → `engineering/`. Needs project context or evidence → the project."*

**That is a cross-product / product-specific test. It never asks which product owns the artifact.** And canon already recorded that reading explicitly — `docs/implementation/PKS_Brainstorming_Documents_Assessment.md` §1.3:

> *"Methodology (**cross-product**, → `engineering/` **after qualification**) ≠ the PKS (**product-specific**, → `docs/`)"* — described there as *"existing constitution, applied."*

> ### The consequence, and it is decisive
>
> **Project-side placement of unqualified cross-product methodology is a MATURITY statement, not an OWNERSHIP statement.** ES-005.3 is not saying *"this research belongs to PublicDigit."* It is saying *"this is not qualified yet, and unqualified things wait project-side."*
>
> **`Layer_Verification_Rule.md` is cross-product methodology.** Its destination on qualification is **`engineering/`** — not `docs/knowledgeos/`, not `docs/publicdigit/`. **Under the Chair's own preferred tree it would not move to any product folder.**

**Therefore: Q4 is answered independently of the reorganization, and the reorganization must not be justified by this file.** If `docs/` became product-oriented tomorrow, this artifact's placement would be unchanged.

## 3. My own prior finding — what was right, and what I never looked at

I concluded *"no amendment, no restructuring, no exception is warranted."*

**That conclusion was correct for the question I was asked** — the Layer Verification Rule's placement — **and I reached it without ever asking what else was in `docs/implementation/`.** Had I looked, the 89 PKS documents were one `ls` away.

> **The correction is specific: no amendment was warranted *by the artifact in front of me*. An amendment may well be warranted *by the 92 documents beside it*, which I did not examine.** "Search canon first" found the rule; it did not make me check whether the rule still described the repository.

## 4. The prerequisite the proposal cannot skip

**You cannot sort by product until every artifact has a product.** Canon does not currently assign one:

| Track | Canon's status | Product membership |
|---|---|---|
| **PublicDigit** | Core Domain (AIP-14) | clear |
| **Engineering Platform** (`engineering/`) | **Supporting Subdomain** of PublicDigit — *not a product* | clear, and **not** a `docs/` tenant |
| **KnowledgeOS** | prospective product, *"if their gates open"* — **gate closed**; *"Platform v0.1 — Not started"* | prospective |
| **PKS** — Product Knowledge System | **"fourth track"**, Phase II M5 baseline consolidated | ⚠️ **unassigned** |

**`EKA → KnowledgeOS` is recorded as a confirmed invariant; `PKS → KnowledgeOS` is not.** So the largest single block of misplaced documents — **89 files** — has **no ruled destination** in the Chair's tree. **A product-first sort requires a product-membership decision first, and it does not exist.**

## 5. Collisions to place before the ARB — recorded, not resolved

| # | Constraint | The collision |
|---|---|---|
| **1** | **R-37** — *"no new top-level folders · **no more document reorganizations**"* | A product-first `docs/` is **the largest document reorganization available**. **Scope ambiguity is material:** the ruling text is unqualified, but its *operational terms* name only `engineering/` (*"permits only bug fixes · broken-link fixes · typo corrections"*). **Whether R-37 binds `docs/` is genuinely unsettled and only the ARB can say** |
| **2** | **R-37, same ruling** | *"maturity ladder … **L6 multi-project**; **neither L5 nor L6 is reached by moving files**."* **The proposal is the L6 move, executed by moving files** — the exact thing R-37 warns is not how L6 is reached. This is the sharpest objection on the record |
| **3** | **R-37, same ruling** | *"the **Platform ≙ Adoption split** remains a **research hypothesis** — not roadmap, not design, not future architecture"* |
| **4** | **ES-005.2 — folder rule** | `publicdigit/`, `knowledgeos/`, `pks/` **cannot be pre-created**. They appear one at a time as first artifacts arrive; the full tree in the proposal is **reserved-namespace material, not directories** |
| **5** | **ES-005.4 — never a copy** | Artifacts **move**; nothing is duplicated into a new tree. `docs/knowledgeos/architecture/` risks becoming a second home for what `engineering/` already holds |
| **6** | **ES-004.2** | Plans are canonically `docs/plans/` — **artifact-first**. A pure product-first tree orphans that rule (51 inbound references) |
| **7** | **Blast radius** | **501** inbound references to `docs/implementation/` across `.md`/`.yaml`/`.sh`/`.json`, **119** of them to `PKS_*` files. Every one breaks on a move. ES-005.4 forbids the easy fix (leave a copy) |

## 6. Where the Chair is right on the merits, independent of timing

**Option B over Option A — agreed, and for the stated reason.** Classifying by bounded context first mirrors the ubiquitous language; classifying by document type first means every product's artifacts are scattered across `implementation/`, `architecture/`, `design/`, `research/`. **`docs/knowledgeos/implementation/` is the better shape than `docs/implementation/knowledgeos/`.**

**And `docs/implementation/knowledgeos/` should not be created** — it would encode artifact-first at exactly the moment the programme is questioning artifact-first.

## 7. Recommendation

**Agreed: do not relocate `Layer_Verification_Rule.md`** — but note the reason differs from the Chair's. It should not move **because it is cross-product methodology already correctly staged project-side pending qualification**, not because its destination is in dispute.

**Open one decision — and a narrower, better-evidenced one than "should `docs/` become product-oriented?":**

> ### The decision to put to the ARB
>
> **ES-005.1 declares `docs/` to be "what PublicDigit is." 49% of `docs/implementation/` is not PublicDigit's. Which is wrong — the rule, or the placement?**
>
> **Framed this way it satisfies R-37's reversed burden of proof**, because the insufficiency is *measured* rather than *argued*. Framed as "should `docs/` be product-oriented," it is an improvement proposal — and R-37 rejects those by default.

**Three sub-questions, in dependency order:**

1. **Does R-37's *"no more document reorganizations"* bind `docs/`, or only `engineering/`?** *(Scope question — must be answered first; if it binds, everything below waits for the retrospective.)*
2. **Which product owns the PKS corpus?** *(Classification — a product-first sort is impossible without it.)*
3. **Only then:** product-first `docs/`, expressed per ES-005.2 as **reserved namespaces**, with directories appearing as artifacts move.

**Nothing here requires acting today**, and the ~900-file blast radius is an argument for deciding carefully, not quickly.

---

**Traceability:** ES-005.1 (the falsified premise) · ES-005.2 (folder rule) · ES-005.3 (the litmus — cross-product, not ownership) · ES-005.4 (never a copy) · ES-004.2 (`docs/plans/`) · ES-006.1 (the ladder) · **R-37** (structural freeze · reversed burden of proof · L6-not-by-moving-files · Platform≙Adoption as hypothesis) · **AIP-14** Product Primacy · `engineering/README.md` DA clarification 2026-07-27 · `docs/implementation/PKS_Brainstorming_Documents_Assessment.md` §1.3 · supersedes nothing; **extends** `2026-08-01-knowledgeos-product-status-finding.md` with evidence that finding did not examine. **No file moved · no directory created · no standard amended.**
