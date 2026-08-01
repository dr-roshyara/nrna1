# ES-005 Amendment Package — Prepared for ARB Review

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Commission:** Principal Architect Instruction — *Authorize ES-005 Amendment Package*. Prepare only; do not implement.
**Status:** **PREPARED — not applied.** `engineering/governance/ES-005-Repository.md` is **unmodified**.
**Repository Integrity Gate:** ✅ PASSED. **No file moved · no directory created · no standard amended · no ruling minted.**

**Freeze note (following the precedent this platform already uses):** `engineering/` is under structural freeze (**R-37**: bugfix/link/typo only) and conceptual freeze (**R-38**). This artifact proceeds under the **explicitly-commissioned exception**, the same basis cited by `AI_Engineering_Platform_Architecture_Baseline_Current_State.md` and the 2026-07-12 verification reports. **It amends nothing — it proposes.**

> ### ⚠️ One boundary the commission should know about before it reviews
>
> **The commissioned-exception precedent, as used so far, covers *descriptive* artifacts.** Both prior freeze notes justify themselves with *"introduces **no new concept, rule, capability, or structure** — it records what already exists."*
>
> **An amendment to ES-005 changes a rule.** So **preparing** this package is covered; **applying** it — editing `engineering/governance/ES-005-Repository.md` — is an act **R-37's operational terms constrain**, and I can find no precedent for a standards amendment under the freeze.
>
> **Consequence: approving this package is not the same as being permitted to apply it.** Applying it needs its own explicit R-37 authorization. **This corrects my earlier "Package 1 is not blocked" — accurate about the *reorganization* clause, incomplete about the *operational terms*.**

---

## Deliverable 1 — ES-005 Amendment Proposal

### 1.1 ES-005.3 — replacement text *(the substantive amendment)*

> **ES-005.3 — Placement Derivation** *(amends the Placement Litmus, ARB 2026-07-10)*
>
> **Classification.** Every governed artifact carries a classification, **declared by the artifact**:
>
> | Property | Values |
> |---|---|
> | **Scope** | `cross-product` · `product-specific` |
> | **Steward** | the role accountable for curating the artifact |
> | **Maturity** | `research` · `qualified` · `adopted` — positions on the ES-006.1 ladder |
> | **Domain** | the domain whose knowledge the artifact is · **N/A when Scope = `cross-product`** |
>
> **Derivation.** **Location is derived from classification, never determined per artifact:**
>
> | Scope | Maturity | Derived location |
> |---|---|---|
> | `cross-product` | `qualified` · `adopted` | **`engineering/`** |
> | `cross-product` | `research` | **PENDING — the stewardship decision.** Until issued, **pre-amendment behaviour stands: project-side** |
> | `product-specific` | any | **the documentation root of the domain to which the artifact belongs** |
> | — *(active session state)* | — | **the runtime mount** |
>
> **Admissibility (from R-71).** **A placement argument that cannot be grounded in a classification property is out of order.** Usefulness, findability, and existing location are not reasons.
>
> **Domains are configuration, not rule text (R-68).** The enumeration of domains lives in a registry, never in this standard.

**What changed, and why each change is minimal:**

| Change | From | To |
|---|---|---|
| Inputs | Scope + Maturity (2) | **Scope + Steward + Maturity + Domain (4)** — per R-70 |
| *"the project"* | definite article, **singular; no referent with three domains** | **"the documentation root of the domain to which the artifact belongs"** — generic |
| Form | prose litmus, derivation implicit | **explicit derivation table** — the function ES-005.3 already was, made readable |
| Hard-coded path | `docs/implementation/` named in rule text | **removed** — configuration, per R-68 |

### 1.2 ES-005.1 — consequential amendment *(required for internal consistency; not enumerated in the commission)*

**ES-005.1 currently declares `docs/` to be *"Product (what PublicDigit is)."* A generic domain-resolution rule cannot coexist with that sentence** — the same standard would say documentation is PublicDigit's *and* resolves per domain. **Surgical fix, preserving what is still true:**

> **ES-005.1 — The Three-Concern Separation** *(amended)*. `app/` + `tests/` + `architecture/` = **the Product implementation** (PublicDigit) · **`docs/` = the documentation space, hosting artifacts of every domain the repository documents — the repository is a workspace, not a domain (R-65)** · `engineering/` = **Engineering Platform — cross-product scope, not a domain (R-67)** · `.claude/` = **Runtime mount point**; the mount never moves and is never "the architecture."

**Flagged for ARB attention: this amendment was not in the commission's five scope items.** It is included because **omitting it leaves a contradiction inside the amended standard.** **If the ARB prefers, it can be split into its own decision — but the two cannot be issued in either order without a window of inconsistency.**

### 1.3 Carrier recommendation *(scope item 3)*

> **RECOMMENDED: the artifact's own header is the carrier. The knowledge-card schema supplies the field vocabulary. Cards are NOT extended repository-wide.**

| Option | For | Against |
|---|---|---|
| **Artifact header** ⭐ | already present on the artifact in question (`Class` · `Owner` · `Status`) · zero new infrastructure · **one home per fact (ES-005.4)** · appears per artifact as governance reaches it, mirroring ES-005.2's folder philosophy | prose today; unvalidated until a linter reads it |
| **Repo-wide card** | machine-validated (`npm run knowledge-lint`) · four of the properties already modelled | scoped to `docs/knowledge/` (**40 cards**); extending it repo-wide obliges a card for ~900 documents · **duplicating classification in card *and* header strains ES-005.4 (never a copy)** |

**The recommendation is parsimony, not preference:** the header carrier **borrows a proven vocabulary without creating an obligation**, and it keeps each fact in one place.

### 1.4 `bounded_context` ↔ `Domain` reconciliation *(scope item 4)*

> **RECOMMENDED: this is not a rename. They are two different properties at two different granularities, and both are valid.**

**The earlier diagnosis — *"right shape, wrong unit"* — was about the card being *read as* the domain carrier. `bounded_context` is correct for what it names:** bounded contexts genuinely exist **inside** domains (11 inside PublicDigit), and per-context tagging is legitimate for `docs/knowledge/`.

| Action | Detail |
|---|---|
| **Add** `domain:` | new field; new `docs/knowledge/schema/domains.yaml` (**none exists today**; `bounded-contexts.yaml` does) |
| **Keep** `bounded_context:` | unchanged meaning, unchanged values; **`global` retains its role** — spans contexts *within* a domain |
| **Cross-product artifacts** | `scope: cross-product` makes `domain:` **N/A** — no sentinel value needed |
| **Migration** | `domain:` **optional at introduction**, promoted to required only when populated; **40 cards affected, none broken** |

**Rename was the wrong instinct: renaming a correct field to fix a misreading destroys a distinction the schema had right.**

### 1.5 The `authority` question — recorded, not resolved *(scope item 5)*

> **OPEN: is `authority` (trust/provenance) a fifth classification property, or a separate concern?**
>
> **Evidence that it is separate:** the card schema declares `authority:` *"trust/source — **independent of status**"* — **two fields, independence stated in the schema.** **Evidence that it interacts:** `Layer_Verification_Rule.md` encodes trust *inside* its Status line (*"non-binding … a recommended heuristic, never authority"*), so in practice they are entangled on at least one artifact.
>
> **Recorded per the commission. Not resolved. It becomes decision-relevant only when a carrier is chosen and a linter must validate fields.**

## Deliverable 2 — Change Rationale

| # | Rationale |
|---|---|
| **1** | **Measured insufficiency, not preference.** ES-005.1 declares `docs/` to be PublicDigit's; **89 of 183 files in `docs/implementation/` are `PKS_*` (49%)**, 3 are `KnowledgeOS_*`. **R-29/R-37 demand evidence of insufficiency; this is measurement.** |
| **2** | **The defect is one word, and the amendment is its minimal repair.** *"the project"* — definite article, singular — **had no referent once three domains existed.** `docs/implementation/` filled with PKS files because **the rule routed correctly by its own terms; its terms stopped describing the world.** |
| **3** | **The amendment adds inputs to a function that already exists.** ES-005.3 already derives location from classification in four branches. **This is completion, not replacement** — the smallest change that closes the gap. |
| **4** | **It removes a hard-coded path from rule text** (`docs/implementation/`), per R-68 — so future domains are configuration, not amendments. |
| **5** | **It closes nothing it lacks authority to close.** The `cross-product` + `research` cell is left **PENDING with pre-amendment behaviour preserved**, because filling it requires the stewardship decision, which is outside this commission. |

## Deliverable 3 — Backward Compatibility Assessment

| Affected | Effect | Breaking? |
|---|---|---|
| **Cross-product qualified/adopted artifacts** (`DDD_Tactical_Governance_Principles.md`) | derived location `engineering/` — **unchanged** | **No** |
| **`Layer_Verification_Rule.md`** | falls in the **PENDING** cell; pre-amendment behaviour preserved | **No** — by construction |
| **The 89 `PKS_*` + 3 `KnowledgeOS_*` documents** | derive to **a domain root that does not exist** | ⚠️ **Yes — see below** |
| **40 knowledge cards** | `domain:` added as **optional**; `bounded_context:` untouched | **No** |
| **501 inbound references** to `docs/implementation/` | the amendment **moves nothing** | **No** |
| **ES-005.2 / ES-005.4 / ES-006.1** | untouched; the amendment cites them | **No** |

> ### ⚠️ The one real incompatibility, stated plainly
>
> **On issuance, 92 artifacts are immediately non-conformant** — their derived location is a domain root that does not exist, and creating it is Package 2, **blocked on the R-37 scope question.**
>
> **Two ways to handle it:**
>
> | Option | Effect |
> |---|---|
> | **A ⭐ — issue with a recorded transitional non-conformance** | the 92 artifacts are listed as known non-conformant, resolved by Package 2. **Honest, visible, and the pattern this programme already uses (R-39 records an exception rather than hiding it)** |
> | **B — make domain resolution conditional on the root existing** | the rule silently falls back to today's location. **Rejected as a recommendation: it makes the rule self-nullifying and hides exactly what the measurement exposed** |
>
> **Recommended: A.** A standard that records its own non-conformance is enforceable; one that dissolves on contact is not.

## Deliverable 4 — Traceability from the Approved Classification Model

| Amendment element | Approved basis | Evidence |
|---|---|---|
| Four classification inputs | **R-70** — Scope · Steward · Maturity · Domain | artifact header (`Class`/`Owner`/`Status`) · card schema |
| `Location` as derived output, not a member | **R-70** — *"Location is derived, never a member"* | ES-005.3's existing four branches |
| Admissibility clause | **R-71** — placement derived, never ad hoc | — |
| `docs/` as multi-domain documentation space | **R-65** workspace-not-domain · **R-66** domain-not-bounded-context | 49% measurement · `app/Contexts/` = 11 |
| `engineering/` as cross-product **scope** | **R-67** | ES-005.3 clause 1 |
| Domains as configuration | **R-68** | ES-005.2's reserved-namespace table precedent |
| Precedent before structure | **R-69 / R-39** | why no staging root is proposed |

**Note on status:** the commission treats R-65…R-71 as **established for its purposes**; they remain **unminted in the register.** Draft text is in `2026-08-01-classification-model-approval-record.md` §4 and `2026-08-01-stewardship-disposition-record.md` §1.

## Deliverable 5 — Open Questions Requiring Future Authority

| # | Question | Blocks |
|---|---|---|
| **1** | **Does applying an ES amendment fall inside R-37's operational terms?** The exception precedent covers descriptive artifacts, not rule changes | **applying this package** |
| **2** | **The stewardship decision** — cross-product research: engineering-stewarded, or project-side? | the **PENDING** cell |
| **3** | **Does R-37's reorganization clause bind `docs/`?** | **Package 2 entirely** |
| **4** | **Which domain owns the PKS corpus?** `EKA → KnowledgeOS` is invariant; `PKS → KnowledgeOS` is not | any domain root; the 89 files |
| **5** | **Is `authority` (trust) a fifth property?** | carrier + linter design |
| **6** | **Should a gate assert maturity**, so *"`engineering/` = qualified"* is enforced rather than declared? | nothing today; **it is declared and unenforced** |
| **7** | **Where do the classification fields become required** — all governed artifacts, or on-arrival per ES-005.2's philosophy? | linter scope |

**Also noted for Package 2's inputs, since it landed today outside the model:** `docs/adr/20260801_1712_legacy_folder_and_files.md` records `architecture_legacy/`, the developer-guide relocation, and *"new developer guides must come inside ./docs"* — **a placement decision taken independently of the derivation rule, and squarely inside question 3.**

---

## Success criterion check

**The ARB can approve, revise, or reject this package without reopening the classification model:** the model appears only in **Deliverable 4**, as traceability. **Every proposal here is a consequence of it, and none re-argues it.**

**Out of scope, confirmed:** `docs/knowledgeos/` and `docs/publicdigit/` **do not exist** · `Layer_Verification_Rule.md` **unmoved** · **no folder created** · ES-005 **unmodified on disk** · ES-006 **untouched** · **no gate implemented.**

---

**Traceability:** **R-70 / R-71** (the model and the principle — treated as established for this commission, unminted) · **R-65…R-69** (unminted) · **R-34** · **R-37 / R-38** (freeze; the applying-vs-preparing boundary) · **R-39** (recorded exception as the pattern for transitional non-conformance) · **R-29** (burden of proof) · **ES-005.1/.2/.3/.4** · **ES-006.1** · `docs/knowledge/_meta/knowledge-card.template.md` · `docs/knowledge/schema/` (no `domains.yaml`) · 40 knowledge cards · the 49% measurement. **Builds on** `2026-08-01-classification-model-approval-record.md`. **No file moved · no directory created · no standard amended · no ruling minted.**
