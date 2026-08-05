# KnowledgeOS — Product Discovery Charter

**Kind:** product-discovery charter (one page) — authorizes *structured discovery of whether a product exists here*, *not* the product, its architecture, or any implementation. **Status:** PROPOSED — awaiting ARB/sponsor approval. **Track:** AI Knowledge Platform / product research — **explicitly OUTSIDE the Engineering Platform and its conceptual freeze, and outside the PublicDigit product backlog.** A third track, opened only if this charter is approved.
**Working hypothesis (to be validated or falsified — not assumed):** *there is a market for an AI-native Knowledge Operating System for software engineering whose core promise is preserving architectural knowledge, enforcing engineering governance, and enabling AI agents to reason consistently across complex software systems — and the governance/knowledge machinery built in this repository is evidence the approach works.*
**Naming discipline:** "KnowledgeOS" is a placeholder, not a decision. This charter uses "AI Knowledge Platform" for the concept; product naming is a Business Model–stage output.

## Why a charter and not a conversation

Per the ARB (2026-07-12): this is potentially *a new product, a new bounded domain, a new business, possibly a new company* — a scale of decision that deserves staged discovery with kill-gates, not an open-ended chat. The stages below are sequential; **each ends with an ARB/sponsor gate whose legitimate outcomes include STOP** (the discovery equivalent of the falsifiable observation protocols: finding "no viable product here" is a success of the process, not a failure).

## The five stages (each gated; no stage may begin before its predecessor's gate)

| # | Stage | Question it answers | Deliverable | Explicitly NOT in scope |
|---|---|---|---|---|
| 1 | **Product Discovery** | What problem, for whom? (architectural-knowledge loss: departing seniors, stale ADRs, AI ignoring standards, drifting docs, inconsistent reviews) | Problem statement + customer hypothesis (target segments: software companies, consultancies, enterprise architecture, regulated industries) | features, modules, agents |
| 2 | **Market Validation** | Does anyone pay for this, and who else is near it? | Competitive landscape (code assistants · repo indexers · semantic search · enterprise knowledge tools) + differentiation evidence + willingness-to-pay signals | positioning copy, pricing |
| 3 | **Domain Discovery** | What is the domain, in strategic-DDD terms? | Domain model built on `Strategic_DDD_Discovery_Engineering_Governance_Domain.md` (the inventory/aggregates/UL audit already produced) — extended with *product*-domain concepts, then and only then a context map | assigning Evans/Vernon relationship patterns before the domain model stabilizes (the same rule that governs the engineering-domain map) |
| 4 | **Business Model** | How does this sustain itself? | Model + naming + build/partner/defer decision | company formation, hiring |
| 5 | **Architecture** | Only now: how is it built? | Product architecture, informed by stages 1–4 | anything before gate 4 passes |

## Standing constraints

1. **Three-track separation holds:** Engineering Platform (frozen, awaiting C3/ratification) · PublicDigit product (EPIC-002 running) · this discovery. No stage of this charter modifies either other track; this repository's governance artifacts are *evidence and source material* for stage 3, never deliverables of this charter.
2. **Evidence discipline inherited:** every stage's deliverable cites its evidence; negative findings are mandatory content, not omissions; the established research method (quality gates · contradiction surface · time-box) applies — its fourth use, further evidence for the queued platform-protocol promotion.
3. **The one asset this repo actually proves** (and the honest limit of that proof): a single project's evidence that governance-first AI engineering *can* work — one retrospective data point, zero market data points. Stage 1 must treat this repository as an existence proof, not as validation.
4. **Priority:** this charter does not preempt the standing queue (ratification → C3 → STABLE → Project Knowledge pilot). The pilot is itself stage-3-relevant evidence; there is a natural sequencing argument for gating stage 3 on pilot completion — **ARB decides the interleaving at approval.**

## Approval asks (the ARB/sponsor decides exactly three things)

1. Open the discovery track (yes/no).
2. Authorize stage 1 only (each later stage has its own gate).
3. Rule the interleaving with the pilot (stage 3 after pilot vs. parallel).

**STOP — nothing in this charter executes until approved.**

---
*Traceability: ARB KnowledgeOS reflections + product-discovery-sequence ruling (2026-07-12: "Product Discovery → Market Validation → Domain Discovery → Business Model → Architecture, not simply another conversation"). Companion: `Strategic_DDD_Discovery_Engineering_Governance_Domain.md` (stage-3 input, already produced under the discovery-can-begin ruling). Charter precedent: `Project_Knowledge_Architecture_Charter.md` · `Context_Assembly_Research_Charter.md` · `EPIC-002_Problem_Statement.md`.*
