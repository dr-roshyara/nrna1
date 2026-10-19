# V-2 pairing worksheet 04

Mechanical grouping by overlapping line ranges only; no judgement. Two anonymous extractions, X (95 items) and Y (47 items).

source: docs/implementation/RQ-002_Project_Knowledge_Domain.md

## G1 · L3-3
- X:Q1 L3-3 · DEF · The document is a research charter, explicitly not an ADR, IDD or design.  
  "research charter — NOT an ADR, NOT an IDD, NOT a design."
- X:Q2 L3-3 · DEF · The charter is the constitution of a research phase, defining question, sub-questions and boundaries.  
  "This document is the constitution of a research phase: it defines the question, the sub-questions, and the boundaries."
- X:Q3 L3-3 · INV · The charter must not decide anything about the solution.  
  "It decides nothing about the solution."
- Y:Q1 L3-3 · DEF · The document is a research charter, not a design.  
  "This document is the constitution of a research phase: it defines the question, the sub-questions, and the boundaries. It decides nothing about the solution."

## G2 · L4-4
- X:Q4 L4-4 · CLM · The ARB approved the charter with amendments and authorized the literature review.  
  "APPROVED WITH AMENDMENTS (ARB, 2026-07-11) — literature review authorized."
- X:Q5 L4-4 · REV · Earlier names were rejected: "Management" administrates, "Architecture" was documentation-flavored, "System" implies something to build.  
  "was documentation-flavored"
- X:Q6 L4-4 · CLM · "Domain" was chosen because it biases nothing; the research must discover whether a system exists at all; RQ-002 is the durable id.  
  "biases nothing — the research discovers whether a system even exists. RQ-002 is the durable id."
- Y:Q2 L4-4 · CLM · Status: approved with amendments.  
  "APPROVED WITH AMENDMENTS (ARB, 2026-07-11) — literature review authorized."
- Y:Q3 L4-4 · REV · Naming history: Management → Architecture → System → Domain; "Domain" chosen as unbiased.  
  ""Domain"** biases nothing — the research discovers whether a system even exists."

## G3 · L5-5
- X:Q7 L5-5 · REL · The charter's form follows the EPIC-002 problem statement, the authoritative template for research charters.  
  "modeled on `EPIC-002_Problem_Statement.md` (the indexed authoritative form for research charters, ER-09 §2)."
- Y:Q4 L5-5 · REL · The charter's form follows the EPIC-002 problem statement.  
  "modeled on `EPIC-002_Problem_Statement.md`"

## G4 · L6-6
- X:Q8 L6-6 · INV · Executors must take the researcher's role in this phase, not the architect's.  
  "*think like a researcher, not an architect.*"
- X:Q9 L6-6 · DEF · The researcher is defined by asking what exists; the architect by asking what to build.  
  "the architect asks"
- X:Q10 L6-6 · INV · The research role continues until the ARB authorizes design.  
  "This phase stays in the first role until the ARB authorizes design."
- Y:Q5 L6-6 · ASM · Binding role instruction: researcher ("what exists?"), not architect.  
  "*think like a researcher, not an architect.*"

## G5 · L8-8
- X:Q11 L8-8 · INV · Litmus test: an output is in scope only if it would survive removal of Markdown.  
  "if Markdown were removed completely, would the finding still exist?*"
- X:Q12 L8-8 · DEF · Findings failing the test are documentation design (out of scope); passing ones are knowledge architecture (in scope).  
  "If **no**, it is documentation design and out of scope. If **yes**, it is knowledge architecture and in scope."
- X:Q13 L8-8 · INV · The research must model knowledge, not documentation.  
  "**Design knowledge. Not documentation.**"
- Y:Q6 L8-8 · DEF · The litmus test separating knowledge architecture from documentation design.  
  "*if Markdown were removed completely, would the finding still exist?*"
- Y:Q7 L8-8 · CLM · Slogan of the litmus.  
  "**Design knowledge. Not documentation.**"

## G6 · L12-12
- X:Q14 L12-12 · REV · The research question was broadened at ARB review from static to dynamic knowledge.  
  "## Research question (broadened at ARB review — knowledge is dynamic, not static)"

## G7 · L14-14
- X:Q15 L14-14 · CLM · The research question asks about eight dynamics of project knowledge.  
  "**How is project knowledge created, validated, evolved, discovered, consumed, challenged, forgotten, and retired?**"
- Y:Q8 L14-14 · CLM · The research question (eight verbs).  
  "**How is project knowledge created, validated, evolved, discovered, consumed, challenged, forgotten, and retired?**"

## G8 · L16-16
- X:Q16 L16-16 · REL · The static "what kinds exist" question is contained in the dynamic one; the domain is a living system with events.  
  "is contained within this, but the domain is a *living system with events*"
- X:Q17 L16-16 · CLM · Knowledge undergoes deliberate events (supersession, invalidation, forking, coexistence, rediscovery), not just decay.  
  "knowledge is not merely lost to decay — it is **deliberately superseded, invalidated, forked; two truths coexist; forgotten knowledge is rediscovered**."
- X:Q18 L16-16 · ASM · Entropy, obsolescence, conflict, forgetting and rediscovery are assumed to be first-class domain concepts.  
  "Entropy · obsolescence · conflict · forgetting · rediscovery are first-class domain concepts."
- X:Q19 L16-16 · INV · A taxonomy that cannot express decay or contradiction has missed the domain.  
  "has missed the domain."
- Y:Q9 L16-16 · CLM · Knowledge is dynamic; these are first-class concepts.  
  "Entropy · obsolescence · conflict · forgetting · rediscovery are first-class domain concepts."
- Y:Q10 L16-16 · CLM · Adequacy criterion for any taxonomy.  
  "A taxonomy that cannot express "this knowledge is decaying" or "these two facts contradict" has missed the domain."

## G9 · L18-18
- X:Q20 L18-18 · CLM · The granularity question (smallest unit of project knowledge) is mandatory.  
  "*what is the smallest unit of project knowledge?*"
- X:Q21 L18-18 · REL · The granularity question is the DDD aggregate-root question applied to knowledge, with candidate units listed.  
  "the aggregate-root question asked of knowledge: one ADR? one decision? one invariant? one business rule? one ubiquitous-language concept? one finding?"
- X:Q22 L18-18 · ASM · All other structures depend on resolving granularity first.  
  "Until this is answered, every other structure rests on unknown granularity."
- Y:Q11 L18-18 · CLM · Mandatory granularity question (aggregate-root question asked of knowledge).  
  "*what is the smallest unit of project knowledge?*"
- Y:Q12 L18-18 · REL · Granularity is a prerequisite for every other structure.  
  "Until this is answered, every other structure rests on unknown granularity."

## G10 · L22-22
- X:Q23 L22-22 · CLM · The retrospective showed insufficiency: rulings duplicated across record types and boards drifting behind implementation.  
  "**The retrospective demonstrated the insufficiency** (O-10/P-6: ruling duplication across record types · O-8: boards drifting behind implementation)"
- X:Q24 L22-22 · CLM · Project knowledge exists but has only an implicit architecture.  
  "project knowledge exists but its architecture is implicit."
- Y:Q13 L22-22 · CLM · Why now 1: retrospective evidence (ruling duplication, drifting boards).  
  "**The retrospective demonstrated the insufficiency**"

## G11 · L23-23
- X:Q25 L23-23 · INV · The incumbent EKP must be studied rather than bypassed.  
  "**An incumbent exists and must be studied, not bypassed:**"
- X:Q26 L23-23 · DEF · The EKP is identified by its components: frozen Constitution, lifecycle, knowledge cards, ai/ quarantine, tooling, Adjudication pilot.  
  "the EKP (`docs/knowledge/` — frozen Constitution, lifecycle, knowledge cards, `ai/` quarantine, lint/graph tooling, Adjudication pilot)."
- X:Q27 L23-23 · REL · The EKP's stated purpose nearly coincides with RQ-002's research question.  
  "Its purpose statement is near-verbatim this research question."
- X:Q28 L23-23 · CLM · The sharpest evidence question is whether the EKP was actually used during PB-004..007 sessions.  
  "*did the EKP actually get used during PB-004..007 engineering sessions, or did sessions read raw ADRs and guides instead?*"
- Y:Q14 L23-23 · CLM · Why now 2: the EKP incumbent; sharpest question is whether it was used in sessions.  
  "**An incumbent exists and must be studied, not bypassed:**"

## G12 · L24-24
- X:Q29 L24-24 · CLM · Session startup injects engineering context, but project context is assembled ad hoc, which is an observed gap.  
  "**A context-assembly gap is observed:** session startup injects *engineering* context (MEMORY/CONTEXT/plan); *project* context (bounded context, glossary, ADRs, prior reports for the ticket at hand) is assembled ad hoc by each session."
- Y:Q15 L24-24 · CLM · Why now 3: project context is assembled ad hoc per session.  
  "**A context-assembly gap is observed:**"

## G13 · L25-25
- X:Q30 L25-25 · REV · ER-09 is judged to address a question one level too low (how to store documents vs what project knowledge is).  
  "**ER-09 was correctly diagnosed as one level too low** (ARB): it answers"
- X:Q31 L25-25 · CLM · ER-09 remains proposed and is paused pending this research.  
  "**ER-09 remains PROPOSED and pauses pending this research.**"
- Y:Q16 L25-25 · REV · ER-09 paused; the durable question is "what is project knowledge?".  
  "**ER-09 was correctly diagnosed as one level too low**"

## G14 · L29-29
- X:Q32 L29-29 · ASM · "Project Knowledge Platform" is held only as a hypothesis.  
  "explicitly a **hypothesis**, not a design."
- X:Q33 L29-29 · CLM · Four possible outcomes are allowed: new platform, EKP capability, absorbed rules, or deferred concept.  
  "Discovery may conclude: a new platform beside the Engineering Platform · a capability within the existing EKP · a set of rules absorbed by existing structures · or a deferred concept awaiting more evidence."
- X:Q34 L29-29 · INV · The discovery must not presuppose its conclusion.  
  "**Discovery must not assume its own answer.**"
- Y:Q17 L29-29 · CLM · The candidate is a hypothesis.  
  "**"Project Knowledge Platform"** — explicitly a **hypothesis**, not a design."
- Y:Q18 L29-29 · ASM · Four possible outcomes; none presumed.  
  "**Discovery must not assume its own answer.**"

## G15 · L33-33
- X:Q35 L33-33 · ASM · The going-in taxonomy is a seven-kind candidate list to be validated or falsified.  
  "Candidate list to validate *or falsify*: business · architecture · decision · implementation · operational · research · historical."
- X:Q36 L33-33 · CLM · Sub-question 1 asks about the completeness and correctness of the taxonomy cuts.  
  "Is the list complete? Are these even the right cuts?"
- Y:Q19 L33-33 · CLM · Sub-question 1 (candidate list to validate or falsify).  
  "**Taxonomy** — what kinds of project knowledge exist?"

## G16 · L34-34
- X:Q37 L34-34 · ASM · The EKP is stated to assume a single lifecycle for all kinds, which sub-question 2 tests.  
  "Do all kinds share one lifecycle (as the EKP assumes) or do kinds differ?"
- Y:Q20 L34-34 · CLM · Sub-question 2 Lifecycle.  
  "Do all kinds share one lifecycle (as the EKP assumes) or do kinds differ?"

## G17 · L35-35
- X:Q38 L35-35 · CLM · Sub-question 3 asks how AI or newcomers assemble just enough knowledge for a task.  
  "how should an AI (or a newcomer) assemble *just enough* relevant knowledge for a task without reading everything?"
- X:Q39 L35-35 · MATH · Context assembly is modeled as the sequence need → discover → assemble → detect contradictions → working context.  
  "(need → discover → assemble → detect contradictions → working context)"
- Y:Q21 L35-35 · CLM · Sub-question 3 Discovery and context assembly.  
  "how should an AI (or a newcomer) assemble *just enough* relevant knowledge for a task without reading everything?"

## G18 · L36-36
- X:Q40 L36-36 · CLM · Sub-question 4 asks how continued correctness is known, via evidence strength, source diversity and recency.  
  "how is knowledge known to be *still correct*? (evidence strength · source diversity · recency"
- X:Q41 L36-36 · CLM · No current tooling addresses correctness over time.  
  "the correctness-over-time problem no current tooling attacks)"
- Y:Q22 L36-36 · CLM · Sub-question 4 Qualification.  
  "how is knowledge known to be *still correct*?"

## G19 · L37-37
- X:Q42 L37-37 · CLM · Sub-question 5 asks for the boundary between engineering knowledge (how we engineer) and project knowledge (what this project is).  
  "how is project knowledge different from engineering knowledge?"
- Y:Q23 L37-37 · CLM · Sub-question 5 Boundary.  
  "What is the precise line between "how we engineer" (Engineering Platform) and "what this project is"?"

## G20 · L38-38
- X:Q43 L38-38 · CLM · Sub-question 6 asks for an own/observe/never-touch ownership map.  
  "what would a Project Knowledge capability own vs observe vs never touch, relative to the Engineering Platform, the Product, and the Runtime?"
- Y:Q24 L38-38 · CLM · Sub-question 6 Ownership.  
  "what would a Project Knowledge capability own vs observe vs never touch"

## G21 · L39-39
- X:Q44 L39-39 · REV · Knowledge Quality was added as a sub-question at ARB review.  
  "**Knowledge Quality (added at ARB review — a research thread of its own):**"
- X:Q45 L39-39 · CLM · Sub-question 7 asks about authority, override, contradiction coexistence, obsolete searchability, canonical vs historical, and trust measurement.  
  "when is knowledge *authoritative*? What overrides what? Can two contradictory facts coexist — under what rules? Can obsolete knowledge remain searchable? What is canonical vs historical? How is trust measured?"
- Y:Q25 L39-39 · CLM · Sub-question 7 Knowledge Quality (added at ARB review).  
  "when is knowledge *authoritative*? What overrides what? Can two contradictory facts coexist — under what rules?"

## G22 · L43-43
- X:Q46 L43-43 · REL · RQ-002 inherits EPIC-002's research method.  
  "This research **inherits the Research Method section of the EPIC-002 charter**"
- X:Q47 L43-43 · DEF · The inherited method comprises quality gates, evidence classification, mandatory negative findings, promotion criteria, time-box + confidence, and implementation-as-research-producer.  
  "(quality gates · observed-practice/claimed-benefit/evidence-strength classification · mandatory negative findings · promotion criteria · time-box + confidence · implementation-as-research-producer)"
- X:Q48 L43-43 · REV · The method is amended with a deepened Normalized Knowledge Model per concept.  
  "with one amendment for this domain — the **deepened Normalized Knowledge Model**, one per discovered concept:"
- Y:Q26 L43-43 · REL · Method inherited from EPIC-002, amended with the deepened NKM.  
  "This research **inherits the Research Method section of the EPIC-002 charter**"

## G23 · L46-47
- X:Q49 L46-47 · MATH · The NKM is a ten-field structure: Concept, Definition, Purpose, Lifecycle, Relationships, Producers, Consumers, Failure modes, Evidence strength, Contradictory viewpoints.  
  "Concept · Definition · Purpose · Lifecycle · Relationships · Producers · Consumers"
- Y:Q27 L46-47 · DEF · Normalized Knowledge Model: ten fields per concept.  
  "Concept · Definition · Purpose · Lifecycle · Relationships · Producers · Consumers · Failure modes · Evidence strength · Contradictory viewpoints"

## G24 · L50-50
- X:Q50 L50-50 · CLM · This is the method's second use, which evidences the trigger for promotion to a platform research protocol.  
  "this is the method's **second use** — the promotion trigger to a platform research protocol is hereby evidenced"
- X:Q51 L50-50 · INV · Promotion requires an ARB act; it is queued, not executed.  
  "the promotion itself is an ARB act, queued, not executed."
- Y:Q28 L50-50 · CLM · Second use evidences a promotion trigger; promotion not executed.  
  "this is the method's **second use** — the promotion trigger to a platform research protocol is hereby evidenced; the promotion itself is an ARB act, queued, not executed."

## G25 · L54-54
- X:Q52 L54-54 · INV · Executors must act as senior researchers in five named disciplines, not as architects.  
  "You are no longer acting as a software architect. You are acting as a senior researcher in Domain-Driven Design, Knowledge Management, AI-assisted Software Engineering, Organizational Learning and Information Architecture."
- Y:Q29 L54-54 · CLM · Standing mission: executor acts as a researcher across five fields.  
  "You are no longer acting as a software architect."

## G26 · L56-56
- X:Q53 L56-56 · INV · The executor's task is to discover universal principles, not design the platform.  
  "Your task is NOT to design our platform. Your task is to **discover universal principles governing project knowledge**."
- X:Q54 L56-56 · REL · The Engineering Platform governs how engineering happens; RQ-002 researches what project knowledge is.  
  "The Engineering Platform already governs HOW engineering happens. We are researching WHAT project knowledge actually is."
- Y:Q30 L56-56 · CLM · Mission goal.  
  "Your task is to **discover universal principles governing project knowledge**."

## G27 · L58-58
- X:Q55 L58-58 · CLM · Ten research objectives are set, from defining project knowledge to studying successful and failed KM systems.  
  "(2) identify every distinct knowledge category occurring during software engineering · (3) study how knowledge is created · (4) how it evolves · (5) how it becomes obsolete · (6) how it is validated · (7) how it is consumed · (8) how AI engineers discover knowledge · (9) how human engineers discover knowledge · (10) study successful AND failed knowledge management systems."
- Y:Q31 L58-58 · CLM · Ten research objectives.  
  "(1) define "project knowledge" · (2) identify every distinct knowledge category occurring during software engineering"

## G28 · L60-60
- X:Q56 L60-60 · INV · Each discovered concept must get a ten-field NKM.  
  "For every discovered concept produce a Normalized Knowledge Model (ten fields, above)."
- Y:Q32 L60-60 · CLM · NKM output requirement.  
  "For every discovered concept produce a Normalized Knowledge Model (ten fields, above)."

## G29 · L62-62
- X:Q57 L62-62 · INV · The executor may not produce architecture, folders, templates, software or platform proposals.  
  "Do NOT produce architecture. Do NOT produce folders. Do NOT produce templates. Do NOT propose software. Do NOT propose our platform."
- X:Q58 L62-62 · DEF · The executor deliverable is a normalized body of knowledge.  
  "Your deliverable is a **normalized body of knowledge**."
- X:Q59 L62-62 · INV · Architectural synthesis may start only after the literature review is complete.  
  "Only after the literature review is complete may architectural synthesis begin."
- Y:Q33 L62-62 · CLM · Prohibitions; deliverable is a normalized body of knowledge.  
  "Do NOT produce architecture. Do NOT produce folders. Do NOT produce templates. Do NOT propose software. Do NOT propose our platform."

## G30 · L64-64
- X:Q60 L64-64 · REV · The literature scope was broadened at ARB review (heading says six disciplines).  
  "## Literature review scope (broadened at ARB review — six disciplines)"

## G31 · L68-74
- X:Q61 L68-68 · DEF · The organizational-knowledge discipline's scope: Nonaka & Takeuchi, Senge, organizational memory and forgetting.  
  "| **Organizational knowledge** | Nonaka & Takeuchi (knowledge creation, tacit vs explicit, the knowledge spiral) · Senge (learning organizations) · organizational memory and forgetting |"
- Y:Q34 L68-74 · CLM · Literature scope: seven disciplines (organizational knowledge, KM, cognitive science, IA, SE, AI, information quality added at review).  
  "Organizational knowledge"
- X:Q62 L69-69 · DEF · The knowledge-management discipline's scope.  
  "| **Knowledge management** | knowledge lifecycle · knowledge decay/entropy · knowledge governance · discovery and retrieval · documentation drift studies |"
- X:Q63 L70-70 · DEF · The cognitive-science discipline's scope.  
  "| **Cognitive science** | working vs long-term memory · expert knowledge and mental models · cognitive load · knowledge transfer |"
- X:Q64 L71-71 · DEF · The information-architecture discipline's scope.  
  "| **Information architecture** | information scent · navigation · knowledge organization · classification, ontology, taxonomy |"
- X:Q65 L72-72 · DEF · The software-engineering discipline's scope.  
  "| **Software engineering** | ADRs · living documentation · domain storytelling · event storming · context mapping · decision capture · empirical studies on what teams actually read vs maintain |"
- X:Q66 L73-73 · DEF · The AI discipline's scope.  
  "| **AI** | context assembly · retrieval and RAG · memory systems and agent memory · knowledge graphs · context compression |"
- X:Q67 L74-74 · REV · Information quality (provenance, lineage, trust, authority, freshness, confidence, versioning, conflict resolution) was added as a 7th discipline at ARB review.  
  "*(7th discipline, added at ARB review)*"
- Y:Q35 L74-74 · REV · Seventh discipline added at review.  
  "**Information quality** *(7th discipline, added at ARB review)*"

## G32 · L76-76
- X:Q68 L76-76 · CLM · The literature review's purpose is to validate or falsify the candidate taxonomy, EKP assumptions and the second-platform hypothesis.  
  "Purpose: **validate or falsify our candidate taxonomy, the EKP's assumptions, and the"
- X:Q69 L76-76 · INV · Systems must not be copied; both successful and failed KM systems must be studied.  
  "never copy systems. Successful **and failed** knowledge-management systems are both mandatory study objects."
- Y:Q36 L76-76 · CLM · Purpose of the literature review; failed systems are mandatory study objects.  
  "**validate or falsify our candidate taxonomy, the EKP's assumptions, and the "second platform" hypothesis** — never copy systems."

## G33 · L77-77
- X:Q70 L77-77 · CLM · The field may be closer to Knowledge Engineering than to documentation or KM (a framing observation, hedged with "may").  
  "this field may be closer to **Knowledge Engineering** — modeling knowledge itself — than to documentation or knowledge management."
- Y:Q37 L77-77 · CLM · Framing observation.  
  "this field may be closer to **Knowledge Engineering** — modeling knowledge itself — than to documentation or knowledge management."

## G34 · L79-79
- X:Q79 L79-79 · INV · Deliverables are research artifacts only and must be produced in the stated order.  
  "## Deliverables (research artifacts ONLY — produced in this order)"

## G35 · L81-88
- X:Q71 L81-81 · CLM · Deliverable 1: a literature review with gap analysis against the EKP.  
  "**Literature review** (state of the art → gap analysis against the EKP incumbent)"
- Y:Q38 L81-88 · CLM · Eight deliverables in order, ending in STOP and ARB review.  
  "**STOP → ARB review**"
- X:Q72 L82-82 · CLM · Deliverable 2: a candidate ubiquitous language.  
  "**Candidate ubiquitous language** for project knowledge"
- X:Q73 L83-83 · DEF · Deliverable 3 (added at ARB review): a normalized ontology with seven fields per concept, serving as the semantic backbone.  
  "**Normalized Knowledge Ontology** *(added at ARB review — the semantic backbone)*: for every discovered concept — parent · children · relationships · lifecycle · authority · producer · consumer"
- Y:Q39 L83-83 · DEF · Ontology deliverable: parent, children, relationships, lifecycle, authority, producer, consumer per concept.  
  "**Normalized Knowledge Ontology** *(added at ARB review — the semantic backbone)*"
- X:Q74 L84-84 · CLM · Deliverable 4: an ownership map.  
  "**Ownership map** (Project Knowledge candidate: owns / observes / never touches)"
- X:Q75 L85-85 · CLM · Deliverable 5: a context map to four neighbouring areas.  
  "**Context map** (relationships to Engineering Platform · Product · Runtime · EKP)"
- X:Q76 L86-86 · CLM · Deliverable 6: an open questions register.  
  "**Open questions register**"
- X:Q77 L87-87 · INV · Deliverable 7: a recommendation among the four options, required to be evidenced.  
  "**Recommendation** — new platform / EKP capability / absorbed rules / deferred — **with evidence**"
- X:Q78 L88-88 · INV · Deliverable 8: a stop point where the ARB decides whether design is authorized.  
  "**STOP → ARB review**: the ARB decides whether the evidence authorizes design."

## G36 · L90-90
- X:Q80 L90-90 · INV · The ARB warns that the research must not run for six months.  
  "## Exit criteria (ARB warning: this must not become a six-month research project)"

## G37 · L92-92
- X:Q81 L92-92 · INV · Research ends when six exit criteria are met, then stops.  
  "literature review complete across the seven disciplines · universal concepts identified · contradictions documented · ubiquitous language produced · ontology drafted · recommendation made — **then STOP.**"
- X:Q82 L92-92 · CLM · Provisional, uncertainty-marked conclusions are preferred over unbounded search (time-boxed per inherited method).  
  "provisional conclusions with marked uncertainty beat unbounded search."
- X:Q83 L92-92 · INV · Architecture may begin only after the stop.  
  "Only after the STOP may architecture begin."
- Y:Q40 L92-92 · CLM · Exit criteria; time-boxed.  
  "The research **ends** when: literature review complete across the seven disciplines · universal concepts identified · contradictions documented · ubiquitous language produced · ontology drafted · recommendation made — **then STOP.**"
- Y:Q41 L92-92 · CLM · Preference for marked provisional conclusions.  
  "provisional conclusions with marked uncertainty beat unbounded search."

## G38 · L96-96
- X:Q84 L96-96 · INV · The research must not produce templates, folders, implementation rules, aggregates or bounded-context designs.  
  "❌ Document templates · ❌ folder structures · ❌ implementation rules · ❌ aggregates or bounded-context designs"
- X:Q85 L96-96 · INV · No changes to engineering/ (R-37), none to the EKP, and no ER-09 finalization.  
  "❌ any change to `engineering/` (R-37 in force pending C3) · ❌ any change to the EKP (the incumbent is studied, not modified) · ❌ ER-09 finalization (paused pending this research)."
- Y:Q42 L96-96 · CLM · Explicitly not produced (templates, folders, rules, aggregates, changes to engineering/ or EKP, ER-09).  
  "❌ any change to the EKP (the incumbent is studied, not modified)"

## G39 · L98-98
- X:Q86 L98-98 · REV · The success criterion was elevated at ARB review from a verdict to a domain model.  
  "## Success criterion (elevated at ARB review — a domain model, not a verdict)"

## G40 · L100-100
- X:Q87 L100-100 · INV · Success means deriving a ubiquitous language independent of format, tooling, AI provider or project.  
  "we can derive a ubiquitous language for project knowledge that is independent of any documentation format, tooling, AI provider, or software project."
- X:Q88 L100-100 · CLM · The first deliverable is a domain model of knowledge.  
  "The first deliverable is a *domain model of knowledge itself*"
- X:Q89 L100-100 · REL · Later architectures are to be implementations of the domain model, not starting points.  
  "so that any later architecture (documentation, AI context assembly, knowledge graph, search, retrieval) becomes an implementation of a well-understood domain rather than the starting point."
- Y:Q43 L100-100 · CLM · Primary success criterion.  
  "**After completing this research, we can derive a ubiquitous language for project knowledge that is independent of any documentation format, tooling, AI provider, or software project.**"
- Y:Q44 L100-100 · DEF · The target: a domain model of knowledge.  
  "The first deliverable is a *domain model of knowledge itself*"

## G41 · L102-102
- X:Q90 L102-102 · INV · Secondary criterion: the report must confirm or falsify the three going-in assumptions.  
  "Secondary criterion (retained): the report can **confirm or falsify** the going-in assumptions (the seven-kind taxonomy, the EKP's single-lifecycle model, the"
- X:Q91 L102-102 · INV · Merely restating the assumptions counts as failure.  
  "A report that merely restates them has failed."
- Y:Q45 L102-102 · CLM · Secondary criterion: confirm or falsify going-in assumptions.  
  "A report that merely restates them has failed."

## G42 · L106-106
- X:Q92 L106-106 · REL · EPIC-002 and RQ-002 are independent parallel threads sharing one method.  
  "Independent, parallel research threads sharing one method"
- X:Q93 L106-106 · DEF · EPIC-002 is product-domain research (Evidence); RQ-002 is meta-domain research about project knowledge.  
  "**EPIC-002** researches a candidate *product* domain (Evidence — constitutional records); **RQ-002** researches the *meta* domain (knowledge about the project itself)."
- X:Q94 L106-106 · INV · Neither thread blocks the other, but EPIC-002 has scheduling priority per product primacy.  
  "Neither blocks the other; EPIC-002 retains scheduling priority (product primacy, AIP-14)."
- Y:Q46 L106-106 · REL · RQ-002 vs EPIC-002: parallel, neither blocks; EPIC-002 has priority.  
  "**EPIC-002** researches a candidate *product* domain (Evidence — constitutional records); **RQ-002** researches the *meta* domain (knowledge about the project itself)."

## G43 · L109-109
- X:Q95 L109-109 · REL · Traceability links the charter to the ARB elevation review, the rule-parsimony finding (EKP incumbent), retrospective O-8/O-10/P-6, the F2 third-domain question, and R-37.  
  "*Traceability: ARB elevation review 2026-07-11"
- Y:Q47 L109-109 · CLM · Traceability: the ARB elevation review.  
  "ER-09 should become a Project Knowledge Architecture; the research question is 'what is project knowledge?'"

