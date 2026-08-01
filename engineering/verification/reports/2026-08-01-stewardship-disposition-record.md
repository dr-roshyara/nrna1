# Documentation Roots — Chair Disposition, Stewardship Counter-Argument, and Narrowed Question

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Repository Integrity Gate:** ✅ PASSED. **No file moved · no directory created · no standard amended · no ruling minted.**

---

## 1. Approved — recorded as the Chair's disposition

**Five conclusions approved as supported by evidence:**

| # | Approved conclusion |
|---|---|
| **1** | **The repository is a workspace/container, not a domain** |
| **2** | **The repository *contains documentation for* multiple domains** |
| **3** | **The repository organization unit is the DOMAIN, not the bounded context** |
| **4** | **`engineering/` is the result of the cross-product Scope decision — not another domain** |
| **5** | **ES-005 stays generic: a domain-resolution rule, never an enumeration of domains** |

> **⚠️ Recorded as disposition, not minted as rulings.** **R-34 governs this:** *authority is created only by explicit issuance; nothing becomes a ruling by inference.* The wording *"what I would approve now"* is a disposition; **`R-nn` identifiers in the rulings register are an issuance act belonging to the Decision Authority.** I have not appended any. **Say the word and I will append them with identifiers; until then these five are citable as the Chair's approved position, not as platform rulings.**

## 2. Held — not approved

- a new staging root · **not created, not proposed further**
- relocating `Layer_Verification_Rule.md` · **untouched**
- **the "undefined cell" conclusion** · **withdrawn as framed — see §3**

## 3. The disagreement — dispositioned, and the Chair's route is better supported on every point I could check

### 3.1 The word was wrong, and it overstated the finding

**Conceded.** *"A cell that two named shapes can fill is not **undefined** — it is **unruled**."* **"Undefined" says the model is broken; the accurate word says it is incomplete.** I wrote the stronger word and it did rhetorical work the evidence did not support. **Withdrawn as framed.**

### 3.2 The precision that does survive

**Stewardship resolves *placement* only if stewardship implies location.**

| If | Then |
|---|---|
| **Stewardship implies location** | Engineering stewardship → the artifact sits `engineering`-side while unqualified. **That is a clarification of ES-005.3 clause 2** (*"research artifacts remain project-side until promoted"*) — reached **without a directory**, which is strictly better, **but it is still the clause being interpreted, not bypassed** |
| **Stewardship does not imply location** | it is orthogonal metadata: it names **who curates**, and the **placement question stays open** |

> **Either way clause 2 must be faced. What stewardship changes is the cost: a governance clarification instead of a structural change** — and on that comparison it wins outright.

### 3.3 The evidence check — **n = 1 is exact**

**Verified, and it favors the Chair.** `engineering/knowledge/methodology/` holds two artifacts: `DDD_Tactical_Governance_Principles.md` is **ADOPTED** (DA ruling, 2026-07-26); `Layer_Verification_Rule.md` is **PROPOSED — NOT ADOPTED**. **One unqualified cross-product artifact. Exactly one.** **R-29/R-37's burden of proof therefore does bite against generalizing from it**, and *"don't generalize until operational evidence requires it"* is correctly applied.

### 3.4 The precedent I missed — and it is squarely on point

**`DDD_Tactical_Governance_Principles.md` sits in `engineering/` as an *early promotion under a recorded governance exception* — R-39 — whose evidence base is stated as "one context."**

> **The programme has already solved "engineering-side placement of material that is not fully qualified" — with a ruling, not a directory.**
>
> **The Chair's stewardship route has direct precedent. My two staging shapes have none.** I proposed structure where the repository had already demonstrated the governance answer.

### 3.5 The objection I owed, and it is weaker than I expected

**The one argument for location over label:** *"`engineering/` = qualified" is machine-testable; a status header is not.*

**Checked, and it does not hold today.** **No Architecture test or gate asserts that property.** The only thing in the repository that reads maturity/status is `.claude/scripts/engineering-placement-guard.sh` — **the non-blocking checkpoint I wrote this session.** Meanwhile clause 2's *purpose* — preventing unqualified material from reading as authority — **is already served by R-34 and the file's own status line** (*"non-binding … a recommended heuristic, never authority"*).

> **So the objection is potential, not current. It cannot carry weight against a reversed burden of proof.** It converts instead into a concrete, answerable engineering question: **should a gate assert maturity, so that status-based protection is enforced rather than declared?**

## 4. The narrowed question — adopted, and it is a *prerequisite*, not a sibling

> # **Should cross-product research artifacts be governed by Engineering stewardship before qualification, without implying they belong to the engineering baseline?**

**Adopted as framed.** It addresses the actual ambiguity without committing the repository to structure, and it is answerable from governance rather than from one artifact's inconvenience.

**Options and consequences — not pre-filled:**

| | **YES — stewardship before qualification** | **NO — clause 2 applies to cross-product research too** |
|---|---|---|
| **ES-005.3 clause 2** | scope clarified: it governs **product-specific** research; cross-product research is stewarded, not relocated | unchanged, applies to both |
| **New root** | **none needed** | the cross-product/unqualified case lands project-side **with no domain** — and the two held shapes become live again |
| **`Layer_Verification_Rule.md`** | stays, under stewardship, status-bounded by R-34 | must relocate, destination unresolved |
| **Protection mechanism** | **status-based** (R-34 + status line) — declared today; open follow-on: **should a gate assert it?** | location-based |
| **Precedent** | **R-39** — engineering-side, not-fully-qualified, governed by a recorded exception | none for a non-domain staging root |

> ### Why this orders the queue
>
> **If the answer is YES, the cross-product/unqualified case never reaches project-side — so it never touches the domain-roots question at all.** The roots decision then rests purely on the **89 PKS + 3 KnowledgeOS product-specific documents**, which is where the measured evidence actually is.
>
> **The narrowed question therefore comes *before* the roots question, and answering it may remove the weakest input from it.** My package treated them as one; they are two, in that order.

## 5. Ordering

1. **Does R-37's *"no more document reorganizations"* bind `docs/`, or only `engineering/`?** *(Unchanged, still first. Note: **two staged, uncommitted reorganizations currently sit in the working tree** — `tenancy/ → developer_guide/tenancy/` and `readme/`+`rough/ → brainstorming/`. They fall under this question.)*
2. **The stewardship question (§4).**
3. **The domain-roots question** — `2026-08-01-documentation-roots-decision-package.md`, with §2's held items removed from it and its evidence base narrowed to the product-specific corpus.
4. **If stewardship = YES, an open follow-on:** should a gate assert maturity, so *"`engineering/` = qualified"* is enforced rather than declared?

---

**Traceability:** **R-34** (authority only by explicit issuance — why no ruling was minted) · **R-39** (early promotion under recorded exception, evidence base one context — **the precedent**) · **R-29 / R-37** (reversed burden of proof; scope of the reorganization freeze unsettled) · **ES-005.3** (clause 1 Scope · clause 2 Maturity) · ES-005.2 · ES-001.1 (rule parsimony) · `engineering/knowledge/methodology/` (n = 1 verified) · `.claude/scripts/engineering-placement-guard.sh` (the only status-reading mechanism; non-blocking). **Dispositions** `2026-08-01-documentation-roots-decision-package.md` §2; **withdraws** its "undefined cell" framing; **retains** its §1 refinements and §3 evidence. **No file moved · no directory created · no standard amended · no ruling minted.**
