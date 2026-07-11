# RQ-002 — Project Knowledge Architecture (Research Charter)

**Kind:** research charter — NOT an ADR, NOT an IDD, NOT a design. This document is the constitution of a research phase: it defines the question, the sub-questions, and the boundaries. It decides nothing about the solution.
**Status:** PROPOSED — awaiting ARB review before the literature review begins.
**Reference form:** modeled on `EPIC-002_Problem_Statement.md` (the indexed authoritative form for research charters, ER-09 §2).
**Role instruction (binding for this phase):** *think like a researcher, not an architect.* The researcher asks **"what exists?"**; the architect asks "what should we build?". This phase stays in the first role until the ARB authorizes design.

> **The litmus for every output of this research:** *if Markdown were removed completely, would the finding still exist?* If **no**, it is documentation design and out of scope. If **yes**, it is knowledge architecture and in scope. **Design knowledge. Not documentation.**

---

## Research question

**What is project knowledge — what kinds exist, and how should they be organized, governed, discovered, validated, evolved, and consumed by humans and AI throughout a project's lifetime?**

## Why now (evidence, not appetite)

1. **The retrospective demonstrated the insufficiency** (O-10/P-6: ruling duplication across record types · O-8: boards drifting behind implementation): project knowledge exists but its architecture is implicit.
2. **An incumbent exists and must be studied, not bypassed:** the EKP (`docs/knowledge/` — frozen Constitution, lifecycle, knowledge cards, `ai/` quarantine, lint/graph tooling, Adjudication pilot). Its purpose statement is near-verbatim this research question. The sharpest evidence question on record: *did the EKP actually get used during PB-004..007 engineering sessions, or did sessions read raw ADRs and guides instead?*
3. **A context-assembly gap is observed:** session startup injects *engineering* context (MEMORY/CONTEXT/plan); *project* context (bounded context, glossary, ADRs, prior reports for the ticket at hand) is assembled ad hoc by each session.
4. **ER-09 was correctly diagnosed as one level too low** (ARB): it answers "how should documents be stored?" — the durable question is "what is project knowledge?". **ER-09 remains PROPOSED and pauses pending this research.**

## Candidate

**"Project Knowledge Platform"** — explicitly a **hypothesis**, not a design. Discovery may conclude: a new platform beside the Engineering Platform · a capability within the existing EKP · a set of rules absorbed by existing structures · or a deferred concept awaiting more evidence. **Discovery must not assume its own answer.**

## Sub-questions (understand — not design)

1. **Taxonomy** — what kinds of project knowledge exist? Candidate list to validate *or falsify*: business · architecture · decision · implementation · operational · research · historical. Is the list complete? Are these even the right cuts?
2. **Lifecycle** — for each kind: creation → validation → evolution → retirement. Do all kinds share one lifecycle (as the EKP assumes) or do kinds differ?
3. **Discovery & context assembly** — how should an AI (or a newcomer) assemble *just enough* relevant knowledge for a task without reading everything? (need → discover → assemble → detect contradictions → working context)
4. **Qualification** — how is knowledge known to be *still correct*? (evidence strength · source diversity · recency — the correctness-over-time problem no current tooling attacks)
5. **Boundary** — how is project knowledge different from engineering knowledge? What is the precise line between "how we engineer" (Engineering Platform) and "what this project is"?
6. **Ownership** — what would a Project Knowledge Platform own vs observe vs never touch, relative to the Engineering Platform, the Product, and the Runtime?

## Research method

This research **inherits the Research Method section of the EPIC-002 charter** unchanged (quality gates · observed-practice/claimed-benefit/evidence-strength classification · Normalized Knowledge Model per concept · mandatory negative findings · promotion criteria · time-box + confidence · implementation-as-research-producer). Standing instruction to any external research tool: *"Do NOT design our architecture. Perform a systematic literature review. Produce Normalized Knowledge Models."*
*(Recorded observation: this is the method's **second use** — the promotion trigger to a platform research protocol is hereby evidenced; the promotion itself is an ARB act, queued, not executed.)*

## Literature review scope (multi-disciplinary)

| Discipline | Topics |
|---|---|
| **Knowledge management** | organizational knowledge lifecycles · knowledge governance · documentation decay/drift studies |
| **DDD practice** | how DDD teams keep domain knowledge synchronized with code · ubiquitous-language maintenance · context-map upkeep |
| **Knowledge representation** | knowledge graphs · ontologies · ADR/decision-knowledge tooling ecosystems |
| **AI-assisted development** | retrieval/context-assembly for coding agents · minimal-sufficient-context research · documentation-as-context practices |
| **Empirical software engineering** | studies on documentation usage, staleness, and trust · what teams actually read vs maintain |

Purpose: **validate or falsify our candidate taxonomy and the EKP's assumptions** — never copy systems.

## Deliverables (research artifacts ONLY)

1. **Literature review** (state of the art → gap analysis against the EKP incumbent)
2. **Candidate ubiquitous language** for project knowledge
3. **Ownership map** (Project Knowledge candidate: owns / observes / never touches)
4. **Context map** (relationships to Engineering Platform · Product · Runtime · EKP)
5. **Open questions register**
6. **Recommendation** — new platform / EKP capability / absorbed rules / deferred — **with evidence**
7. **STOP → ARB review**: the ARB decides whether the evidence authorizes design.

## Explicitly NOT produced

❌ Document templates · ❌ folder structures · ❌ implementation rules · ❌ aggregates or bounded-context designs · ❌ any change to `engineering/` (R-37 in force pending C3) · ❌ any change to the EKP (the incumbent is studied, not modified) · ❌ ER-09 finalization (paused pending this research).

## Success criterion

The research report can **confirm or falsify** the going-in assumptions (the seven-kind taxonomy, the EKP's single-lifecycle model, the "second platform" hypothesis). A report that merely restates them has failed; a report that changes them with evidence has succeeded.

## Relationship to EPIC-002

Independent, parallel research threads sharing one method: **EPIC-002** researches a candidate *product* domain (Evidence — constitutional records); **RQ-002** researches the *meta* domain (knowledge about the project itself). Neither blocks the other; EPIC-002 retains scheduling priority (product primacy, AIP-14).

---
*Traceability: ARB elevation review 2026-07-11 ("ER-09 should become a Project Knowledge Architecture; the research question is 'what is project knowledge?'") · rule-parsimony finding 2026-07-11 (EKP incumbent) · retrospective O-8/O-10/P-6 (evidence of insufficiency) · F2 third-domain question (platform inbox) · R-37 (structural freeze respected).*
