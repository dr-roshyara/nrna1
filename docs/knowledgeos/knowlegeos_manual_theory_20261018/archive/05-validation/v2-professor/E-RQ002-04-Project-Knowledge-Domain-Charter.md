# V-2 Professor extraction — RQ-002 Project Knowledge Domain (Research Charter)

```yaml
source_path: docs/implementation/RQ-002_Project_Knowledge_Domain.md
sha256: 0669b0d2e57a3a52e280db0a78beb6e30f779f6a2902bd21cabd1d56b323a867
lines_total: 109
lines_read: 109
extractor: Professor (Session B), blind to the reviewer; B-08 not consulted
```

## Items

> Q1 L3-3: "This document is the constitution of a research phase: it defines the question, the sub-questions, and the boundaries. It decides nothing about the solution."
- Q1 · DEF · The document is a research charter, not a design.

> Q2 L4-4: "APPROVED WITH AMENDMENTS (ARB, 2026-07-11) — literature review authorized."
- Q2 · CLM · Status: approved with amendments.

> Q3 L4-4: ""Domain"** biases nothing — the research discovers whether a system even exists."
- Q3 · REV · Naming history: Management → Architecture → System → Domain; "Domain" chosen as unbiased.

> Q4 L5-5: "modeled on `EPIC-002_Problem_Statement.md`"
- Q4 · REL · The charter's form follows the EPIC-002 problem statement.

> Q5 L6-6: "*think like a researcher, not an architect.*"
- Q5 · ASM · Binding role instruction: researcher ("what exists?"), not architect.

> Q6 L8-8: "*if Markdown were removed completely, would the finding still exist?*"
- Q6 · DEF · The litmus test separating knowledge architecture from documentation design.

> Q7 L8-8: "**Design knowledge. Not documentation.**"
- Q7 · CLM · Slogan of the litmus.

> Q8 L14-14: "**How is project knowledge created, validated, evolved, discovered, consumed, challenged, forgotten, and retired?**"
- Q8 · CLM · The research question (eight verbs).

> Q9 L16-16: "Entropy · obsolescence · conflict · forgetting · rediscovery are first-class domain concepts."
- Q9 · CLM · Knowledge is dynamic; these are first-class concepts.

> Q10 L16-16: "A taxonomy that cannot express "this knowledge is decaying" or "these two facts contradict" has missed the domain."
- Q10 · CLM · Adequacy criterion for any taxonomy.

> Q11 L18-18: "*what is the smallest unit of project knowledge?*"
- Q11 · CLM · Mandatory granularity question (aggregate-root question asked of knowledge).

> Q12 L18-18: "Until this is answered, every other structure rests on unknown granularity."
- Q12 · REL · Granularity is a prerequisite for every other structure.

> Q13 L22-22: "**The retrospective demonstrated the insufficiency**"
- Q13 · CLM · Why now 1: retrospective evidence (ruling duplication, drifting boards).

> Q14 L23-23: "**An incumbent exists and must be studied, not bypassed:**"
- Q14 · CLM · Why now 2: the EKP incumbent; sharpest question is whether it was used in sessions.

> Q15 L24-24: "**A context-assembly gap is observed:**"
- Q15 · CLM · Why now 3: project context is assembled ad hoc per session.

> Q16 L25-25: "**ER-09 was correctly diagnosed as one level too low**"
- Q16 · REV · ER-09 paused; the durable question is "what is project knowledge?".

> Q17 L29-29: "**"Project Knowledge Platform"** — explicitly a **hypothesis**, not a design."
- Q17 · CLM · The candidate is a hypothesis.

> Q18 L29-29: "**Discovery must not assume its own answer.**"
- Q18 · ASM · Four possible outcomes; none presumed.

> Q19 L33-33: "**Taxonomy** — what kinds of project knowledge exist?"
- Q19 · CLM · Sub-question 1 (candidate list to validate or falsify).

> Q20 L34-34: "Do all kinds share one lifecycle (as the EKP assumes) or do kinds differ?"
- Q20 · CLM · Sub-question 2 Lifecycle.

> Q21 L35-35: "how should an AI (or a newcomer) assemble *just enough* relevant knowledge for a task without reading everything?"
- Q21 · CLM · Sub-question 3 Discovery and context assembly.

> Q22 L36-36: "how is knowledge known to be *still correct*?"
- Q22 · CLM · Sub-question 4 Qualification.

> Q23 L37-37: "What is the precise line between "how we engineer" (Engineering Platform) and "what this project is"?"
- Q23 · CLM · Sub-question 5 Boundary.

> Q24 L38-38: "what would a Project Knowledge capability own vs observe vs never touch"
- Q24 · CLM · Sub-question 6 Ownership.

> Q25 L39-39: "when is knowledge *authoritative*? What overrides what? Can two contradictory facts coexist — under what rules?"
- Q25 · CLM · Sub-question 7 Knowledge Quality (added at ARB review).

> Q26 L43-43: "This research **inherits the Research Method section of the EPIC-002 charter**"
- Q26 · REL · Method inherited from EPIC-002, amended with the deepened NKM.

> Q27 L46-47: "Concept · Definition · Purpose · Lifecycle · Relationships · Producers · Consumers · Failure modes · Evidence strength · Contradictory viewpoints"
- Q27 · DEF · Normalized Knowledge Model: ten fields per concept.

> Q28 L50-50: "this is the method's **second use** — the promotion trigger to a platform research protocol is hereby evidenced; the promotion itself is an ARB act, queued, not executed."
- Q28 · CLM · Second use evidences a promotion trigger; promotion not executed.

> Q29 L54-54: "You are no longer acting as a software architect."
- Q29 · CLM · Standing mission: executor acts as a researcher across five fields.

> Q30 L56-56: "Your task is to **discover universal principles governing project knowledge**."
- Q30 · CLM · Mission goal.

> Q31 L58-58: "(1) define "project knowledge" · (2) identify every distinct knowledge category occurring during software engineering"
- Q31 · CLM · Ten research objectives.

> Q32 L60-60: "For every discovered concept produce a Normalized Knowledge Model (ten fields, above)."
- Q32 · CLM · NKM output requirement.

> Q33 L62-62: "Do NOT produce architecture. Do NOT produce folders. Do NOT produce templates. Do NOT propose software. Do NOT propose our platform."
- Q33 · CLM · Prohibitions; deliverable is a normalized body of knowledge.

> Q34 L68-74: "Organizational knowledge"
- Q34 · CLM · Literature scope: seven disciplines (organizational knowledge, KM, cognitive science, IA, SE, AI, information quality added at review).

> Q35 L74-74: "**Information quality** *(7th discipline, added at ARB review)*"
- Q35 · REV · Seventh discipline added at review.

> Q36 L76-76: "**validate or falsify our candidate taxonomy, the EKP's assumptions, and the "second platform" hypothesis** — never copy systems."
- Q36 · CLM · Purpose of the literature review; failed systems are mandatory study objects.

> Q37 L77-77: "this field may be closer to **Knowledge Engineering** — modeling knowledge itself — than to documentation or knowledge management."
- Q37 · CLM · Framing observation.

> Q38 L81-88: "**STOP → ARB review**"
- Q38 · CLM · Eight deliverables in order, ending in STOP and ARB review.

> Q39 L83-83: "**Normalized Knowledge Ontology** *(added at ARB review — the semantic backbone)*"
- Q39 · DEF · Ontology deliverable: parent, children, relationships, lifecycle, authority, producer, consumer per concept.

> Q40 L92-92: "The research **ends** when: literature review complete across the seven disciplines · universal concepts identified · contradictions documented · ubiquitous language produced · ontology drafted · recommendation made — **then STOP.**"
- Q40 · CLM · Exit criteria; time-boxed.

> Q41 L92-92: "provisional conclusions with marked uncertainty beat unbounded search."
- Q41 · CLM · Preference for marked provisional conclusions.

> Q42 L96-96: "❌ any change to the EKP (the incumbent is studied, not modified)"
- Q42 · CLM · Explicitly not produced (templates, folders, rules, aggregates, changes to engineering/ or EKP, ER-09).

> Q43 L100-100: "**After completing this research, we can derive a ubiquitous language for project knowledge that is independent of any documentation format, tooling, AI provider, or software project.**"
- Q43 · CLM · Primary success criterion.

> Q44 L100-100: "The first deliverable is a *domain model of knowledge itself*"
- Q44 · DEF · The target: a domain model of knowledge.

> Q45 L102-102: "A report that merely restates them has failed."
- Q45 · CLM · Secondary criterion: confirm or falsify going-in assumptions.

> Q46 L106-106: "**EPIC-002** researches a candidate *product* domain (Evidence — constitutional records); **RQ-002** researches the *meta* domain (knowledge about the project itself)."
- Q46 · REL · RQ-002 vs EPIC-002: parallel, neither blocks; EPIC-002 has priority.

> Q47 L109-109: "ER-09 should become a Project Knowledge Architecture; the research question is 'what is project knowledge?'"
- Q47 · CLM · Traceability: the ARB elevation review.

## Line accounting (NOT-EXTRACTED)

| Lines | Reason |
|---|---|
| 1 | FRONTMATTER (title) |
| 2, 7, 9–11, 13, 15, 17, 19, 21, 26, 28, 30, 32, 40, 42, 44, 49, 51, 53, 55, 57, 59, 61, 63, 65, 75, 78, 80, 89, 91, 93, 95, 97, 99, 101, 103, 105, 107–108 | BOILERPLATE (blank lines, separators, empty quote lines) |
| 12, 20, 27, 31, 41, 52, 64, 79, 90, 94, 98, 104 | NAVIGATION (section headings) |
| 45, 48 | BOILERPLATE (code fences) |
| 66–67 | BOILERPLATE (table header) |
