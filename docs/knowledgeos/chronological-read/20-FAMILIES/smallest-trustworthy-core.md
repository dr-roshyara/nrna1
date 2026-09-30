# smallest-trustworthy-core

**Scope(s):** OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Smallest Trustworthy Core` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0006`, scope `OBJECT`: S0202's external-research-derived candidate list of kernel primitives for any trustworthy organizational knowledge system: Identity/Persistent Identity, Provenance, Temporal Validity/History, Authority/Decision Representation, Evidence(-Linking), Decision & Rationale, Lineage, Integrity Constraints/Conflict Detection/Mechanical Assurance -- explicitly external-research-only, not yet compared against EKS/PKS/AIP, and explicitly not KnowledgeOS architecture.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0202 §"Research across knowledge management, organizational memory, software architecture knowledge management, provenance systems, temporal databases, epistemic systems, and governance systems repeatedly converges on a small set of concerns"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0202 §"25. Smallest Trustworthy Core ... Candidate 1: Persistent Identity ... Candidate 8: Integrity Constraints"]
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0205. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0202 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0202, S0202, S0202, S0205 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0202 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Core finding of the external-research pass: nine concerns (Identity, Provenance, Temporality, Authority, Decision and Rationale, Lineage, Evidence, Governed Lifecycle, Integrity/Assurance) recur across the literature more consistently than Knowledge graphs, AI, RAG, Ontologies, Workflow engines, Analytics, Visualization, or Event sourcing; the trustworthy core of organizational knowledge is framed as being primarily about preserving epistemic accountability over time, not retrieval performance. [S0202]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0202] types=['ANALYSIS', 'DISTINCTION'] scope=THEORY-LEVEL — "Core finding of the external-research pass: nine concerns (Identity, Provenance, Temporality, Authority, Decision and Rationale, Lineage, Evidence, Governed Lifecycle, Integrity/Assurance) recur across the literature more consistently than Knowledge graphs, AI, RAG, Ontologies, Workflow engines, Analytics, Visualization, or Event sourcing; the trustworthy core of organizational knowledge is framed as being primarily about preserving epistemic accountability over time, not retrieval performance." (anchor: "Research across knowledge management, organizational memory, software architecture knowledge management, provenance systems, temporal databases, epistemic systems, and governance systems repeatedly converges on a small set of concerns")
- [S0202] types=['HYPOTHESIS', 'CONCEPT'] scope=THEORY-LEVEL — "Pass-1's 'Smallest Trustworthy Core' proposes eight external-research-only kernel candidates with evidence/failure-impact/confidence ratings: Persistent Identity (High), Provenance (Very High), Temporal History (High), Authority Representation (High), Evidence Linking (High), Decision & Rationale (Medium-High), Lineage (High), Integrity Constraints (High) -- explicitly candidates only, not yet compared against EKS/PKS/AIP." (anchor: "25. Smallest Trustworthy Core ... Candidate 1: Persistent Identity ... Candidate 8: Integrity Constraints")
- [S0202] types=['LIMITATION', 'DISTINCTION'] scope=THEORY-LEVEL — "Research provides only weak support for placing AI models, LLMs, RAG, vector search, graph algorithms, analytics, reporting, visualization, UIs, workflow orchestration, recommendation systems, industry-specific rules, or Bayesian reasoning models in a trusted kernel; these are framed as generally replaceable layers above the core, not foundational trust mechanisms." (anchor: "Research provides relatively weak support for placing the following in a trusted kernel: AI models, LLMs, RAG, Vector search, Graph algorithms, Analytics, Reporting, Visualization, User interfaces, Workflow orchestration, Recommendation systems, Industry-specific rules, Bayesian reasoning models ... These are generally replaceable layers rather than foundational trust mechanisms.")
- [S0202] types=['HYPOTHESIS', 'EXTENSION'] scope=THEORY-LEVEL — "Commentary pass proposes, as a hypothesis only ('we should not design that yet'), that a future kernel may need to protect not a bare current_state but a composite of knowledge_state + validity + authority + lineage, motivated by the temporal-knowledge research finding that 'what was authoritative at time T' requires temporal, authority, and lineage state simultaneously." (anchor: "It means that a future KnowledgeOS kernel may need to protect something more sophisticated than: current_state. Potentially: knowledge_state + validity + authority + lineage")
- [S0202] types=['EXTENSION', 'HYPOTHESIS'] scope=THEORY-LEVEL — "Second-pass draft's version of the Smallest Trustworthy Core adds two columns not present in pass 1 -- 'Domain-Owned?' and 'Counter-Evidence' -- explicitly separating whether a candidate is domain-owned (e.g. Identity=Yes, Provenance=Yes) from whether it is a kernel candidate, and records specific counter-evidence per candidate (e.g. Temporal Validity: 'complexity cost'; Authority/Decision and Evidence: 'may be domain-specific'; Lifecycle/Versioning: 'may be projection'); also adds a ninth row, Conflict Detection, and a tenth, Mechanical Assurance (classified domain-owned=No, kernel-candidate=Maybe), neither of which appeared in pass 1's eight-row list." (anchor: "## 25. Smallest Trustworthy Core ... | Candidate | Research Evidence | Why It Matters | What Breaks If Wrong | Domain-Owned? | Kernel Candidate? | Confidence | Counter-Evidence |")
- [S0202] types=['CORRECTION', 'WARNING', 'DISTINCTION'] scope=THEORY-LEVEL — "Final commentary pass explicitly challenges the second draft's own phrase 'Bi-temporal modeling required', arguing temporal validity being important does not prove the kernel must implement a full bitemporal storage model, and draws three explicit distinctions: Temporal semantics ≠ Bitemporal database implementation; Lineage ≠ Event sourcing; Provenance ≠ PROV graph as persistence model." (anchor: "I would not yet accept this statement literally: 'Bi-temporal modeling required' ... The evidence establishes that temporal validity is highly important. But that does not automatically prove that the kernel must implement a full bi-temporal storage model.")
- [S0205] types=['HYPOTHESIS', 'DISTINCTION'] scope=THEORY-LEVEL — "Strategic conclusion: the future KnowledgeOS kernel is unlikely to be a mathematical engine; it is closer to a 'formal semantic operating system' whose candidate primitives (Identity, Evidence, Authority, State, Transition, Provenance, Time, Invariant) are proven and reasoned about using mathematics (state machines + lattice + temporal model + graph relationships), but 'the kernel protects the rules; the mathematics describes the behavior' -- mathematics is a reasoning tool, not the kernel itself." (anchor: "I think the future KnowledgeOS kernel probably will not be a mathematical engine. It will be closer to a formal semantic operating system. The kernel primitives may look like: Identity, Evidence, Authority, State, Transition, Provenance, Time, Invariant. Mathematics helps us prove and reason about them ... The kernel protects the rules. The mathematics describes the behavior.")

## Notes for P3
- Nothing unusual observed while assembling this file; evidence is internally consistent for the rows captured under this label.
