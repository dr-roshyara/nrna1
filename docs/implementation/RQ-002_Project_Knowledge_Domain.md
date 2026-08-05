# RQ-002 — Project Knowledge Domain (Research Charter)

**Kind:** research charter — NOT an ADR, NOT an IDD, NOT a design. This document is the constitution of a research phase: it defines the question, the sub-questions, and the boundaries. It decides nothing about the solution.
**Status:** APPROVED WITH AMENDMENTS (ARB, 2026-07-11) — literature review authorized. *(Naming history: "Management" administrates · "Architecture" was documentation-flavored · "System" already implies something to build. **"Domain"** biases nothing — the research discovers whether a system even exists. RQ-002 is the durable id.)*
**Reference form:** modeled on `EPIC-002_Problem_Statement.md` (the indexed authoritative form for research charters, ER-09 §2).
**Role instruction (binding for this phase):** *think like a researcher, not an architect.* The researcher asks **"what exists?"**; the architect asks "what should we build?". This phase stays in the first role until the ARB authorizes design.

> **The litmus for every output of this research:** *if Markdown were removed completely, would the finding still exist?* If **no**, it is documentation design and out of scope. If **yes**, it is knowledge architecture and in scope. **Design knowledge. Not documentation.**

---

## Research question (broadened at ARB review — knowledge is dynamic, not static)

**How is project knowledge created, validated, evolved, discovered, consumed, challenged, forgotten, and retired?**

The static form ("what kinds exist?") is contained within this, but the domain is a *living system with events*: knowledge is not merely lost to decay — it is **deliberately superseded, invalidated, forked; two truths coexist; forgotten knowledge is rediscovered**. Entropy · obsolescence · conflict · forgetting · rediscovery are first-class domain concepts. A taxonomy that cannot express "this knowledge is decaying" or "these two facts contradict" has missed the domain.

**The granularity question (mandatory):** *what is the smallest unit of project knowledge?* — the aggregate-root question asked of knowledge: one ADR? one decision? one invariant? one business rule? one ubiquitous-language concept? one finding? Until this is answered, every other structure rests on unknown granularity.

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
6. **Ownership** — what would a Project Knowledge capability own vs observe vs never touch, relative to the Engineering Platform, the Product, and the Runtime?
7. **Knowledge Quality (added at ARB review — a research thread of its own):** when is knowledge *authoritative*? What overrides what? Can two contradictory facts coexist — under what rules? Can obsolete knowledge remain searchable? What is canonical vs historical? How is trust measured?

## Research method

This research **inherits the Research Method section of the EPIC-002 charter** (quality gates · observed-practice/claimed-benefit/evidence-strength classification · mandatory negative findings · promotion criteria · time-box + confidence · implementation-as-research-producer), with one amendment for this domain — the **deepened Normalized Knowledge Model**, one per discovered concept:

```text
Concept · Definition · Purpose · Lifecycle · Relationships · Producers · Consumers
· Failure modes · Evidence strength · Contradictory viewpoints
```

*(Recorded observation: this is the method's **second use** — the promotion trigger to a platform research protocol is hereby evidenced; the promotion itself is an ARB act, queued, not executed.)*

## Standing research mission (hand verbatim to any research executor — human, AI, or external tool)

> You are no longer acting as a software architect. You are acting as a senior researcher in Domain-Driven Design, Knowledge Management, AI-assisted Software Engineering, Organizational Learning and Information Architecture.
>
> Your task is NOT to design our platform. Your task is to **discover universal principles governing project knowledge**. The Engineering Platform already governs HOW engineering happens. We are researching WHAT project knowledge actually is.
>
> Research objectives: (1) define "project knowledge" · (2) identify every distinct knowledge category occurring during software engineering · (3) study how knowledge is created · (4) how it evolves · (5) how it becomes obsolete · (6) how it is validated · (7) how it is consumed · (8) how AI engineers discover knowledge · (9) how human engineers discover knowledge · (10) study successful AND failed knowledge management systems.
>
> For every discovered concept produce a Normalized Knowledge Model (ten fields, above).
>
> Do NOT produce architecture. Do NOT produce folders. Do NOT produce templates. Do NOT propose software. Do NOT propose our platform. Your deliverable is a **normalized body of knowledge**. Only after the literature review is complete may architectural synthesis begin.

## Literature review scope (broadened at ARB review — six disciplines)

| Discipline | Topics |
|---|---|
| **Organizational knowledge** | Nonaka & Takeuchi (knowledge creation, tacit vs explicit, the knowledge spiral) · Senge (learning organizations) · organizational memory and forgetting |
| **Knowledge management** | knowledge lifecycle · knowledge decay/entropy · knowledge governance · discovery and retrieval · documentation drift studies |
| **Cognitive science** | working vs long-term memory · expert knowledge and mental models · cognitive load · knowledge transfer |
| **Information architecture** | information scent · navigation · knowledge organization · classification, ontology, taxonomy |
| **Software engineering** | ADRs · living documentation · domain storytelling · event storming · context mapping · decision capture · empirical studies on what teams actually read vs maintain |
| **AI** | context assembly · retrieval and RAG · memory systems and agent memory · knowledge graphs · context compression |
| **Information quality** *(7th discipline, added at ARB review)* | provenance · lineage · trust · authority · freshness · confidence · versioning · conflict resolution |

Purpose: **validate or falsify our candidate taxonomy, the EKP's assumptions, and the "second platform" hypothesis** — never copy systems. Successful **and failed** knowledge-management systems are both mandatory study objects.
*(Framing observation on record: this field may be closer to **Knowledge Engineering** — modeling knowledge itself — than to documentation or knowledge management.)*

## Deliverables (research artifacts ONLY — produced in this order)

1. **Literature review** (state of the art → gap analysis against the EKP incumbent)
2. **Candidate ubiquitous language** for project knowledge
3. **Normalized Knowledge Ontology** *(added at ARB review — the semantic backbone)*: for every discovered concept — parent · children · relationships · lifecycle · authority · producer · consumer
4. **Ownership map** (Project Knowledge candidate: owns / observes / never touches)
5. **Context map** (relationships to Engineering Platform · Product · Runtime · EKP)
6. **Open questions register**
7. **Recommendation** — new platform / EKP capability / absorbed rules / deferred — **with evidence**
8. **STOP → ARB review**: the ARB decides whether the evidence authorizes design.

## Exit criteria (ARB warning: this must not become a six-month research project)

The research **ends** when: literature review complete across the seven disciplines · universal concepts identified · contradictions documented · ubiquitous language produced · ontology drafted · recommendation made — **then STOP.** Time-boxed per the inherited method; provisional conclusions with marked uncertainty beat unbounded search. Only after the STOP may architecture begin.

## Explicitly NOT produced

❌ Document templates · ❌ folder structures · ❌ implementation rules · ❌ aggregates or bounded-context designs · ❌ any change to `engineering/` (R-37 in force pending C3) · ❌ any change to the EKP (the incumbent is studied, not modified) · ❌ ER-09 finalization (paused pending this research).

## Success criterion (elevated at ARB review — a domain model, not a verdict)

**After completing this research, we can derive a ubiquitous language for project knowledge that is independent of any documentation format, tooling, AI provider, or software project.** The first deliverable is a *domain model of knowledge itself* — so that any later architecture (documentation, AI context assembly, knowledge graph, search, retrieval) becomes an implementation of a well-understood domain rather than the starting point.

Secondary criterion (retained): the report can **confirm or falsify** the going-in assumptions (the seven-kind taxonomy, the EKP's single-lifecycle model, the "second platform" hypothesis). A report that merely restates them has failed.

## Relationship to EPIC-002

Independent, parallel research threads sharing one method: **EPIC-002** researches a candidate *product* domain (Evidence — constitutional records); **RQ-002** researches the *meta* domain (knowledge about the project itself). Neither blocks the other; EPIC-002 retains scheduling priority (product primacy, AIP-14).

---
*Traceability: ARB elevation review 2026-07-11 ("ER-09 should become a Project Knowledge Architecture; the research question is 'what is project knowledge?'") · rule-parsimony finding 2026-07-11 (EKP incumbent) · retrospective O-8/O-10/P-6 (evidence of insufficiency) · F2 third-domain question (platform inbox) · R-37 (structural freeze respected).*
