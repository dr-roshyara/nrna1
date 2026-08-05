# Is the Repository Multi-Domain? — Vocabulary Verification and Refined Question

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Trigger:** ARB Chair — bounded-context ownership is a **third dimension**, distinct from product ownership; refined question: *"has the repository evolved from a single-product repository into a multi-domain repository?"*; ES-005 should eventually separate **engineering ownership · domain ownership · maturity**.
**Repository Integrity Gate:** ✅ PASSED. **No file moved · no directory created · no standard amended.**

---

> # FINDING — **the added dimension is right and closes a real gap in my Q1 finding. The DDD term is off by one level, and taken literally it would build the wrong structure. The refined question is largely already answered on the record — which makes the genuinely open question narrower than either of ours.**

---

## 1. The gap in my Q1 finding — conceded

The Chair's point stands and it is not a nuance:

> *"DDD allows bounded contexts to exist **before** productization. That is a strategic design decision — not a commercial one."*

**Correct, and it defeats a reading my own finding invited.** My Q1 finding established that **KnowledgeOS's gate has not opened** — that is a **productization** fact, drawn from *"future products **if their gates open**."*

**Productization is a commercial gate. Domain existence is a strategic-design fact. Canon's answer to the first does not answer the second, and I let the first stand in for both.**

> **The Q1 finding is not withdrawn — it is correctly scoped. It answers *"is KnowledgeOS a product?"* It never answered *"is KnowledgeOS a domain?"*, and that is the question that bears on documentation structure.**

**This also ends the four-for-four streak honestly.** The previous four proposals were already answered in canon. **This one identifies a question canon has not ruled** — and the difference is that this one arrived with a distinction, not a structure.

## 2. The vocabulary is off by one level — and the level matters

**The Chair names four bounded contexts:** `Engineering` · `PublicDigit` · `KnowledgeOS` · `PKS`.

**Measured against the repository, these are domains, and each one *contains* bounded contexts:**

| Named "bounded context" | What it actually contains |
|---|---|
| **PublicDigit** | **11 context folders in `app/Contexts/`** — Adjudication · Committee · Contestation · Election · Elections · Finance · Geography · Governance · Membership · Shared · Trust |
| **PKS** | **Candidate bounded contexts CBC-1…CBC-4** — Knowledge Assessment · Knowledge Projection · Normative Governance · Work Management. M6 speaks explicitly of *"the **PKS boundary**"* and what is *"**outside**"* it |
| **Engineering** | Its own discovery artifact is named `Strategic_DDD_Discovery_**Engineering_Governance_Domain**.md` |

> ### Why this is not pedantry
>
> **Taken literally, the refined question answers YES trivially and produces the wrong tree.** The repository contains **at least 15 bounded contexts** (11 in `app/Contexts/` + 4 PKS candidates). *"Each deserves its own documentation root"* would yield `docs/election/`, `docs/adjudication/`, `docs/contestation/`, `docs/knowledge-assessment/`… — **artifact-scattering by a different name, and emphatically not what the Chair intends.**
>
> **The unit the Chair actually means is the DOMAIN.** `docs/publicdigit/` groups eleven bounded contexts under one root. **The proposal is domain-first, not bounded-context-first.**

**Canon's *ruled* vocabulary already operates at the right level.** AIP-14, quoted in `engineering/README.md`: *"the Election System is the **Core Domain**; this platform is a **Supporting Subdomain**."*

**The "bounded context" usage at repository level appears once, in R-37 — and R-37 itself marks it as non-binding:** *"**Context on record (Class A, not ruled)**: repository = three bounded contexts (Product · Engineering · Runtime)."* **So the imprecision is inherited from canon, not introduced by the Chair** — but the ruled vocabulary is the one to build on.

## 3. The refined question is largely already answered — which sharpens what remains

**Canon has already run Strategic DDD Discovery on three separately-named domains:**

- `Strategic_DDD_Discovery_**Engineering_Governance_Domain**.md`
- `Strategic_DDD_Discovery_**Product_Knowledge_System_Domain**.md` *(+ candidate model, converged review — Phase I **ACCEPTED**, Phase II baseline **consolidated through M5**, M6 executed)*
- **AIP-14:** Election System = **Core Domain**; the platform = **Supporting Subdomain**

> **The programme did not drift into multi-domain territory. It has been *doing strategic domain discovery on three named domains*, with ARB rulings, accepted phases and consolidated baselines. "Is the repository multi-domain?" is answered by its own work products: yes, and deliberately.**

**So the open question is not whether multiple domains exist. It is the implication:**

> ### The genuinely undecided question
>
> **Does domain multiplicity entail documentation-root multiplicity?**
>
> Three domains are recognized and under active discovery. **One documentation root serves all three.** Nothing on the record says a domain is *entitled to* a root — that inference is the actual decision, and it is unmade.

**This is the strongest available framing**, and it is stronger than my previous one. *"Which is wrong — the rule or the placement?"* invites a repair. **This asks what the evolved domain model implies for structure** — which is the Chair's point exactly, and it satisfies R-37 because the domain evolution is documented in accepted ARB artifacts rather than asserted.

## 4. The three-dimension model — agreed, and the mixing is localized to one word

**The Chair's table is correct, and the defect can be pinned more precisely than "partially mixed."**

| Dimension | Where ES-005 expresses it | State |
|---|---|---|
| **Engineering vs project ownership** | **ES-005.3 clause 1** — *"could a different project adopt the document unchanged?"* | ✅ clean, portability-based |
| **Maturity** | **ES-005.3 clause 2** — *"research artifacts remain project-side until promoted through qualification"* | ✅ clean, ladder-based |
| **Domain ownership** | — | ❌ **absent** |

> **The three concerns are not tangled across the standard. Two of them share one sentence cleanly, and the third is missing — and its absence surfaces in a single word.**
>
> **ES-005.3 says: *"Needs project context or evidence → **the project**."*** **Definite article, singular.** That word was unambiguous when there was one project. **With three domains it has no referent** — and *that* is the entire defect, expressed in one word.

**This also explains the 49% measurement in the previous finding.** `docs/implementation/` did not fill with PKS documents because anyone decided it should. **It filled because ES-005.3 says "the project" and there was only one place that phrase could point.** The rule routed correctly by its own terms; **its terms stopped describing the world.**

## 5. Recommendation

**Open one architectural decision, at the domain level:**

> **Three domains are recognized in accepted ARB artifacts (Election System · Engineering Governance · Product Knowledge System), and one documentation root serves all of them. Does domain multiplicity entail documentation-root multiplicity?**

**If NO** — ES-005 stands, and the 92 non-PublicDigit documents need a different justification for where they sit.

**If YES** — the consequent work, in order:

1. **Amend ES-005.3 to name the third dimension** — replacing *"the project"* with a domain-resolving rule. **This is the minimal change and it is the whole defect.**
2. **Assign domain membership**, PKS first — still the unmet prerequisite; **89 files cannot be routed to a domain root that has not been named.** *(PKS is a domain in its own right per its discovery artifacts; whether it is a KnowledgeOS constituent is unruled.)*
3. **Express roots as reserved namespaces per ES-005.2** — directories appear as artifacts move; nothing pre-created.
4. **Resolve R-37's scope first** — whether *"no more document reorganizations"* binds `docs/` or only `engineering/`. **Unchanged and still first in line: if it binds, everything above waits for the retrospective.**

**Terminology for the decision, if it is opened: `domain`, not `bounded context`.** The bounded contexts live *inside* the domains — eleven of them inside PublicDigit alone — and using the finer term would licence a structure nobody is proposing.

**`Layer_Verification_Rule.md` is unaffected in every branch.** Cross-product methodology, project-side pending qualification, destined for `engineering/`. **It never had a domain root to go to.**

---

**Traceability:** ES-005.1 (three-concern separation) · **ES-005.3 (clause 1 portability + clause 2 maturity; the missing domain dimension, visible in the words "the project")** · ES-005.2 (reserved namespaces) · ES-005.4 · ES-006.1 · **R-37** (structural freeze; scope unsettled; *"repository = three bounded contexts"* marked **Class A, not ruled**) · **AIP-14** (Core Domain / Supporting Subdomain — the **ruled** vocabulary) · `app/Contexts/` (11 bounded contexts) · `PKS_Phase_II_M6_Bounded_Context_Discovery.md` (CBC-1…CBC-4 inside the PKS boundary) · `Strategic_DDD_Discovery_Engineering_Governance_Domain.md` · `Strategic_DDD_Discovery_Product_Knowledge_System_Domain.md`. **Extends** `2026-08-01-docs-product-orientation-finding.md`; **scopes** `2026-08-01-knowledgeos-product-status-finding.md` to productization. **No file moved · no directory created · no standard amended.**
