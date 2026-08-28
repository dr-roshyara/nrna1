# EKS-05 — Knowledge Evidence Boundary: separating Execution State from Governance Evidence

**Status:** **FUTURE ARCHITECTURE EXPLORATION** — registered on the PO/ARB act 2026-08-19 (*"Register a future architecture exploration topic"*). ⛔ **Not commissioned; activation requires a human authorization act.**
**Class:** candidate strategic-DDD boundary problem (KnowledgeOS architecture).
**Registered by:** Governance (`b64828fe`). ⛔ **This registration approves no architecture.**

> ### ⛔ **Registered as `FUTURE ARCHITECTURE EXPLORATION` — NOT `DECIDED` · NOT `APPROVED` · NOT `ADOPTED` · NOT `ARCHITECTURE BASELINE`.**

---

## 1 · The observation that produced it

**The `KOS-AIP-GOV-STATE-DURABILITY` initiative found that execution state and governance evidence had been sharing one persistence boundary** (`.claude/runtime/`) while differing in **lifecycle · ownership · durability requirements · authority model · change frequency**.

| Execution context | Governance evidence |
|---|---|
| temporary operational state · agent sessions · workflow execution · automation activity | decisions · provenance · evidence lineage · authority history · audit records |

> ### ⭐ **The architectural reading offered with the act: this was not primarily a storage migration but the discovery of a missing domain boundary.**
> **`Same storage ≠ Same bounded context.`**

**Live corroboration, dated:** commit `de998173` committed sixteen governance documents while **28 grants and 33 transitions stayed outside git** — the documents survived, the authority lineage did not.

## 2 · The hypothesis — ⛔ hypothesis only, nothing adopted

KnowledgeOS **may** require a first-class boundary between **Knowledge Execution Context** and **Knowledge Evidence Context**, integrated with **Knowledge Governance Context**.

**Candidate concepts, none adopted:** Evidence Qualification Boundary · Governance Authority Resolution · Evidence Conflict Policy · Durable Knowledge Lineage.

## 3 · Architect refinements delivered with the registration — recorded so they are not lost

⚠️ **These are inputs to a future exploration, not decisions.**

| # | Refinement |
|---|---|
| **1** | **Governance and Evidence are not siblings only.** Governance answers *"who has authority to decide?"*; Evidence answers *"what evidence supports this claim?"* The relation runs **Governance defines authority rules → Evidence provides proof/provenance → Knowledge Products** |
| **2** | ⭐ **Knowledge Execution Context is the most important missing piece.** *"An AI agent does not create knowledge directly"* — it creates **observations, proposals, changes, evidence candidates**; only governance makes those organizational knowledge |
| **3** | ⚠️ **Rename caution: "Evidence Promotion" → Evidence ADMISSION / QUALIFICATION Boundary.** *"Promotion"* implies any execution evidence can become trusted. The real chain is **created → candidate evidence → qualified evidence → governance-usable evidence** |
| **4** | **`R-CONFLICT` as a domain invariant, not a technical merge problem** — *"not a Git conflict, a Knowledge Authority Conflict."* Candidate object `EvidenceConflict` (id · conflictingEvidence[] · detectedAt · resolutionStatus · resolutionAuthority · resolutionDecision), belonging to the Evidence Context because **evidence history is the protected asset** |
| **5** | **Authority Resolver is a DOMAIN capability, not a technical service** — *"which artifact is authoritative"* is a governance decision, not a lookup |
| **6** | ⭐ **AI is a CONSUMER, not an owner.** *"AI consumes governed knowledge. It does not govern knowledge."* Intelligence must not sit above the governance stack |

## 4 · Open questions, if activated

1. Should **Knowledge Execution** become an explicit bounded context?
2. Should **evidence lineage** become a first-class KnowledgeOS capability?
3. Should there be an explicit boundary where **execution evidence becomes governance-usable**?
4. Should **authoritative knowledge resolution** become a domain capability?
5. Should **`R-CONFLICT`** evolve into a general KnowledgeOS evidence policy?

## 5 · Tests any future investigation MUST apply

| Test | Requirement |
|---|---|
| **Boundary** | for each proposed context: what **language**, what **decisions**, what **invariants**, what **lifecycle** does it own? |
| **Ownership** | ⛔ **do not create a context unless orthogonal ∧ necessary ∧ sufficient** |
| **Authority** | separate **recording information · owning meaning · owning authority · making decisions** |
| **Evidence** | **Recording ≠ Asserting ≠ Proving ≠ Authorizing** |

## 6 · Evidence status

| | |
|---|---|
| ✅ **OBSERVED** | the durability boundary problem · the runtime/governance lifecycle mismatch · the authority-lineage preservation requirement |
| 🔴 **NOT established** | new bounded contexts · new aggregates · new domain services · new ownership model |

## 7 · ⚠️ One tension recorded, not resolved

**The registration act forbids creating an ADR** (*"This act DOES NOT … create an ADR"*). **The architect review delivered alongside it recommends one** — *"Create ADR-KOS-002 and run a bounded-context validation round"* — while also cautioning *"do not freeze the new KnowledgeOS bounded contexts yet."*

⛔ **Governance registers the topic and does NOT create `ADR-KOS-002`.** **Creating it requires its own authorization act**, and the review's own caution points the same way: the contexts are not to be frozen ahead of a validation round.

## 8 · Activation triggers

- a **KnowledgeOS architecture baseline review** occurs; **or**
- **evidence governance becomes a recurring operational problem**; **or**
- **AI agent autonomy requires stronger provenance guarantees.**

**If activated, Architecture produces** *"Knowledge Evidence Boundary Architecture Exploration"*: current-state analysis · domain language proposal · bounded-context evaluation · context map · aggregate candidates · ownership analysis · **ADR recommendation only if justified**.

## 9 · Non-decisions

⛔ KnowledgeOS bounded-context model · **`ADR-KOS-002`** · Knowledge Execution Context existence · Knowledge Evidence Context existence · Governance Authority Service existence · Evidence Qualification Boundary design · implementation architecture · ownership.

**Next actor only if triggered: Architecture Review Board / Principal Architecture Review.**

**Traceability:** PO/ARB registration act 2026-08-19 · the architect review delivered with it · `KOS-AIP-GOV-STATE-DURABILITY-DECISION.md` (B′, `R-CONFLICT` adopted, placement governance) · `KOS-AIP-GOV-STATE-DURABILITY-ADR.md` `67a8e75e` · implementation design `ae451db9` · commit `de998173` + `.gitignore:25`/`:32` · `ES-005.4` · `ES-006.1` · placement derived: `doc-placement.php --scope=product-specific --maturity=research --domain=knowledgeos` → `docs/knowledgeos`, exit 0

---

# APPENDED 2026-08-19 — **the delivered candidate model, recorded in full**

⚠️ **Governance correction: the first registration captured refinements 1–6 but omitted the candidate context model, the layering correction, the ADR sketch and the business framing.** They were delivered with the act and are recorded here **so the exploration does not have to re-derive them.**

> ## ⛔ **RECORDED AS DELIVERED INPUT. NOTHING BELOW IS ADOPTED.**
> **No bounded context is created · no context map is baselined · `ADR-KOS-002` is NOT created · no ownership is assigned.** The architect's own caution governs: ***"Do not freeze the new KnowledgeOS bounded contexts yet."***

## A · Candidate context model — `HYPOTHESIS`

**Core domain — candidates**

| Context | Candidate responsibilities |
|---|---|
| **Knowledge Governance** | authority · decisions · policies · lifecycle |
| **Knowledge Evidence** | provenance · receipts · verification · conflict handling |
| **Knowledge Product** | ADRs · methods · architecture knowledge |

**Supporting domains — candidates**

| Context | Candidate responsibilities |
|---|---|
| **Knowledge Execution** | agent sessions · workflows · automation runs |
| **Knowledge Semantic** | vocabulary · ontology · meaning |
| **Knowledge Delivery** | APIs · retrieval · user access |
| **Knowledge Intelligence** | AI reasoning · analytics |
| **Platform Administration** | — |

⚠️ **Eight candidate contexts. `ES-005.4` and the ownership test both apply to every one of them** — *orthogonal ∧ necessary ∧ sufficient*, or it is not a context. **None has passed that test yet.**

## B · The layering correction — ⭐ the sharpest single point delivered

**The rejected shape:** `AI Agents → Knowledge Product` — *"too direct; it hides the production lifecycle."*

**The candidate production chain:**

```
Knowledge Execution → Knowledge Evidence → Knowledge Governance → Knowledge Product
```

**And the consumption direction, kept separate from it:**

```
AI Agents → Knowledge Delivery → Knowledge Product
                                      ↑
                    Governance + Evidence + Semantic
```

> ### ⛔ **"AI consumes governed knowledge. It does not govern knowledge."**
> **Intelligence must not sit above the governance stack.** *(This is the same prohibition the estate already enforces as `INV-ATTR-2` and `G-2` — a participant may not be the authority over its own participation.)*

## C · `ADR-KOS-002` — the delivered SKETCH · ⛔ **the ADR is NOT created**

**Candidate title:** *Separate Execution State from Governance Evidence.*
**Candidate decision:** KnowledgeOS separates **execution state · evidence records · governance authority records** into separate ownership boundaries.
**Candidate rules, as delivered:**

1. Execution state is **ephemeral**
2. Evidence is **durable**
3. Governance authority is **explicit**
4. **Evidence qualification requires boundary crossing**
5. **Conflicts preserve history**
6. **Authority resolution is governed, not inferred**

⚠️ **Rules 5 and 6 are already decided at platform level for the durability work item** — `R-CONFLICT` (adopted) and the Single Authority Resolver Invariant. ⭐ **An ADR restating them would need to say whether it *cites* those decisions or *supersedes* them.** ⛔ **Not resolved here; recorded as a question for the exploration.**

## D · Business framing, as delivered

> **The capability is ORGANIZATIONAL MEMORY** — *"not: AI remembers conversations, but: the organization remembers why decisions are trusted."*
> **The stated difference: an AI assistant versus an AI engineering organization.**

⚠️ **Recorded as the delivered rationale. `OBSERVED` as motivation; not a validated business case.**

## E · The architect's own assessment, recorded verbatim in effect

✅ core idea · DDD boundary discovery · Execution Context addition · Evidence Context · Governance Context · `R-CONFLICT` as domain invariant · Authority Resolver as domain-owned · ADR recommended.
⚠️ **Evidence "Promotion" naming — rename recommended** · **immediate adoption — needs bounded-context validation before freezing.**

> ### ⭐ **The discovery the exploration exists to test, stated in the delivering act's own words:**
> **"KnowledgeOS cannot be trustworthy unless it treats evidence lineage as a first-class domain capability."**

## F · What is still NOT written, stated plainly

⛔ **There is no "KnowledgeOS Architecture v3.0" document in this estate, and this registration does not create one.** A full architecture — current-state analysis, domain language, bounded-context evaluation, context map, aggregates, ownership — is the **expected future output at §8**, and it requires **its own commission**. **What exists today is a registered hypothesis with its inputs preserved.**

**Traceability (append):** the architect review delivered 2026-08-19 §§7–10 and its final assessment · §1–§9 above · `R-CONFLICT` (adopted) · Single Authority Resolver Invariant (`-AMD2`) · `INV-ATTR-2` · `G-2` · `ES-005.4` · `ES-006.1`
