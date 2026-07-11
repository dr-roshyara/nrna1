# Context Assembly — Research Charter

**Kind:** research charter — NOT design. Structures are frozen; this phase studies the domain's central act before anything is built. **Status:** PROPOSED — awaiting ARB approval.
**Foundation:** General Knowledge Constitution + Project Knowledge Strategic Model (KnowledgeNeed → ContextAssemblyService → Working Context). **Method:** inherits the established research method (quality gates · NKMs · mandatory negative findings · contradiction surface · time-box + confidence) — its **third use**; the platform-protocol promotion trigger is doubly evidenced and remains queued for ARB.
**Role instruction (binding):** researcher, not architect. The five questions below are answered from literature AND first-party evidence (this repository's own sessions are a running laboratory of context assembly) — never by inventing a design.

## The five research questions

1. **What is a Knowledge Need?** How is a task decomposed into knowledge requirements? What does the expressed→attempted→satisfied|unsatisfiable→superseded lifecycle look like in real work? (First-party evidence available: every session of PB-004..007 implicitly derived needs — reconstructable from session logs.)
2. **How do business concepts map to knowledge coordinates?** What is the mapping from task language ("voting eligibility") to the dimensional space (canonical Definitions in which Contexts, which governing Decisions, which Rules)? Where does the mapping fail (synonymy, boundary-crossing terms)?
3. **How is "minimal sufficient context" measured?** PK-P6 needs an operational test: what signals over-inclusion (context rot, cost) vs under-inclusion (task failure, missing caveat)? Is sufficiency observable before task completion or only by outcome? (E-3, token economics, folds in here.)
4. **How should contradictions be RANKED rather than resolved?** Invariant 4 demands coexistence-at-rest; a machine consumer acting on the context needs ranking semantics at consumption (recency? authority scope? execution proximity? provenance depth?). This is E-2 — the named open gap in the literature; first-party pilot evidence is the likely path.
5. **What is the lifecycle of a Working Context?** Creation → use → update-during-task (decisions made mid-task enter the context AND become new claims) → disposal. What must be *extracted* before disposal (the feedback capture), and what must genuinely vanish?

## Evidence sources (in priority order)

1. **First-party:** session logs of PB-004..007 as recorded context-assembly traces (what was read, in what order, what was missing, what was re-derived) — includes **E-1, the EKP usage test**, which this research subsumes.
2. **Literature already held:** executor D's corpus (minimal-sufficient-context studies, context rot, agentic search, memory curation) — re-read against these five questions; new external research only where the held corpus is silent.
3. **Pilot (if ARB authorizes):** one real ticket run with explicit need-derivation and assembly, instrumented — the smallest evidence-producing experiment; also the test of the reflexive risk (assembly must not become a second population).

## Deliverables

1. Answers to the five questions, each labeled FACT / INTERPRETATION / RECOMMENDATION / OPEN, with confidence.
2. The KnowledgeNeed lifecycle validated or corrected against first-party traces.
3. Ranking-semantics candidates for contradictions (E-2), with trade-offs — no selection.
4. A sufficiency test proposal for PK-P6 — measurable, not aspirational.
5. STOP → ARB decides whether the evidence authorizes designing the ContextAssemblyService.

## Explicitly NOT produced

❌ Folder structures · ❌ templates · ❌ software design · ❌ retrieval implementations · ❌ any persistent context store (Working Contexts remain ephemeral by constitutional necessity) · ❌ changes to `engineering/` or the EKP.

## Success criterion

After this research, the ContextAssemblyService can be designed as the implementation of a **measured** act — sufficiency testable, ranking semantics evidenced, need lifecycle observed — rather than an imagined one. A report that cannot say how sufficiency is measured has failed.

**STOP — ARB approval precedes execution.**
