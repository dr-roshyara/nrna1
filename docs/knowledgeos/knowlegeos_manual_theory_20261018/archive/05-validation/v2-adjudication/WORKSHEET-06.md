# V-2 pairing worksheet 06

Mechanical grouping by overlapping line ranges only; no judgement. Two anonymous extractions, X (107 items) and Y (133 items).

source: docs/implementation/RQ-002_Architectural_Synthesis.md

## G1 · L3-3
- X:Q1 L3-3 · DEF · Kind: architectural synthesis, not design; status complete, awaiting ARB.  
  "the bridge between research and architecture"
- Y:Q1 L3-3 · DEF · The document is an architectural synthesis bridging research and architecture.  
  "architectural synthesis — the bridge between research and architecture."
- Y:Q2 L3-3 · INV · It excludes all design artifacts.  
  "NOT design: no folders, templates, software, APIs, aggregates, or implementation."
- Y:Q3 L3-3 · INV · Architecture may begin only after an ARB ruling.  
  "**Status:** COMPLETE — presented for ARB review; architecture begins only after the ARB rules."

## G2 · L4-4
- X:Q2 L4-4 · ASM · Closed evidence base.  
  "No new research was performed; every statement below is traceable to this base"
- Y:Q4 L4-4 · ASM · The closed evidence base is the charter, raw findings, discovery report, EP Reference Architecture and EEP.  
  "RQ-002 charter · `RQ-002_Research_Findings_Raw.md` (4 executors, 7 disciplines, ~120 sources) · `RQ-002_Strategic_Discovery_Report.md` · Engineering Platform Reference Architecture · EEP."
- Y:Q5 L4-4 · CLM · No new research was done, and every statement is traceable (via A/B/C/D executor labels and contradiction ids).  
  "No new research was performed; every statement below is traceable to this base"

## G3 · L5-5
- X:Q3 L5-5 · CLM · Output tests.  
  "traceable · falsifiable by future research · implementation-agnostic · governance-ready."
- Y:Q6 L5-5 · INV · Four output tests apply: traceable, falsifiable, implementation-agnostic, governance-ready.  
  "traceable · falsifiable by future research · implementation-agnostic · governance-ready."

## G4 · L12-12
- X:Q4 L12-12 · INV · PK-P1 Same-Population statement.  
  "Knowledge capabilities must live inside existing workflow gestures, maintained and consumed by the same population."
- Y:Q7 L12-12 · INV · PK-P1: capabilities must sit inside existing gestures, with one maintaining/consuming population.  
  "Knowledge capabilities must live inside existing workflow gestures, maintained and consumed by the same population."

## G5 · L13-13
- X:Q5 L13-13 · CLM · PK-P1 rationale.  
  "Every artifact class requiring a second population or a separate maintenance gesture has a documented decay-to-graveyard lifecycle."
- Y:Q8 L13-13 · CLM · PK-P1 rationale: second-population/separate-gesture artifacts decay.  
  "Every artifact class requiring a second population or a separate maintenance gesture has a documented decay-to-graveyard lifecycle."

## G6 · L14-14
- X:Q6 L14-14 · CLM · PK-P1 supporting evidence.  
  "docs-as-code succeeds precisely because maintainers=consumers"
- Y:Q9 L14-14 · REL · PK-P1 is supported by B's wiki, LLIS, ADR, AKM and docs-as-code evidence and A's KM failure rate.  
  "B (Google GooWiki ~90% dead; NASA LLIS two audited decades; modal ADR abandonment ~50% at 1–5 records; AKM tools zero uptake; docs-as-code succeeds precisely because maintainers=consumers) · A (KM ~50% initiative failure, cultural causes)."

## G7 · L15-15
- X:Q7 L15-15 · CLM · PK-P1 contradictory evidence (Watson Discovery) reinterpreted.  
  "The exception proves the rule: binding created a same-population gesture."
- Y:Q10 L15-15 · CLM · Counter-evidence: Watson Discovery sustained ADRs, but only via enforcement binding.  
  "IBM Watson Discovery sustained 80+ ADRs over 2 years — but only with enforcement tooling binding code to records (B)."
- Y:Q11 L15-15 · CLM · The exception is reinterpreted as confirming PK-P1 because binding created a same-population gesture.  
  "The exception proves the rule: binding created a same-population gesture."

## G8 · L16-16
- X:Q8 L16-16 · CLM · PK-P1 consequence.  
  "the platform introduces **no new stores, portals, or repositories**"
- Y:Q12 L16-16 · INV · No new stores/portals/repositories; capabilities extend existing gestures.  
  "the platform introduces **no new stores, portals, or repositories**; every knowledge capability extends a gesture that already exists (commit, review, session start, qualification run)."

## G9 · L19-19
- X:Q9 L19-19 · INV · PK-P2 Status-at-Discovery statement.  
  "The validity status of knowledge (canonical / historical / deprecated / draft) must be visible at the point of discovery, not the point of curation."
- Y:Q13 L19-19 · INV · PK-P2: validity status must be visible at discovery.  
  "The validity status of knowledge (canonical / historical / deprecated / draft) must be visible at the point of discovery, not the point of curation."

## G10 · L20-20
- X:Q10 L20-20 · CLM · PK-P2 rationale.  
  "the consumer cannot distinguish valid from stale at retrieval time."
- Y:Q14 L20-20 · CLM · PK-P2 rationale: the master failure across four domains is not knowing valid from stale at retrieval.  
  "The master failure signature across four independent domains reduces to: the consumer cannot distinguish valid from stale at retrieval time."

## G11 · L21-21
- X:Q11 L21-21 · CLM · PK-P2 supporting evidence.  
  "similarity has zero correlation with recency"
- Y:Q15 L21-21 · REL · PK-P2 is supported by A (NASA), C (Wikidata, ROT), D (RAG freshness rot: similarity uncorrelated with recency), B (wiki trust collapse, newcomers harmed).  
  "A (NASA retrieval failure;"

## G12 · L22-22
- X:Q12 L22-22 · CLM · PK-P2 contradictory evidence.  
  "none found; the only tension is cost of stamping status"
- Y:Q16 L22-22 · CLM · No contradiction to PK-P2; status-stamping cost is handled by PK-P1.  
  "none found; the only tension is cost of stamping status — addressed by PK-P1 (status must ride existing gestures)."

## G13 · L23-23
- X:Q13 L23-23 · CLM · PK-P2 consequence.  
  "a bare document is an architectural defect."
- Y:Q17 L23-23 · INV · No retrieval surface may show content without status; a bare document is a defect.  
  "no retrieval surface (human navigation, search, AI context assembly) may present content without its status; a bare document is an architectural defect."

## G14 · L26-26
- X:Q14 L26-26 · INV · PK-P3 Claim-Granularity statement.  
  "Knowledge quality attaches at the granularity of the individual claim — a context-bound, provenance-carrying, status-bearing assertion — not at the page or document."
- Y:Q18 L26-26 · INV · PK-P3: quality attaches to individual claims, not documents.  
  "Knowledge quality attaches at the granularity of the individual claim — a context-bound, provenance-carrying, status-bearing assertion — not at the page or document."

## G15 · L27-27
- X:Q15 L27-27 · CLM · PK-P3 rationale.  
  "bundle-level governance provably cannot detect partial staleness."
- Y:Q19 L27-27 · CLM · PK-P3 rationale: four-discipline convergence, and bundle governance provably misses partial staleness.  
  "Four disciplines converged independently on this unit; bundle-level governance provably cannot detect partial staleness."

## G16 · L28-28
- X:Q16 L28-28 · CLM · PK-P3 supporting evidence (nanopublication, knowledge claim, smallest true span, term-bound-to-context).  
  "the smallest span that remains true when read alone"
- Y:Q20 L28-28 · REL · PK-P3 is supported by C (nanopublication), A (McElroy), D (smallest true span), B (term-bound-to-context).  
  "C (nanopublication; assessable claim) · A (knowledge claim, McElroy;"

## G17 · L29-29
- X:Q17 L29-29 · CLM · PK-P3 contradictory evidence.  
  "atomization destroys argumentative context — decontextualization strips caveats and changes decisions"
- Y:Q21 L29-29 · CLM · Counter-evidence: atomization loses context and caveats; nanopublications stayed niche due to overhead.  
  "C/D (atomization destroys argumentative context — decontextualization strips caveats and changes decisions; nanopublication overhead confined it to niches)."

## G18 · L30-30
- X:Q18 L30-30 · CLM · PK-P3 consequence.  
  "the architecture must support both granularities without forcing atomization."
- Y:Q22 L30-30 · REL · Claims are the quality reference model, while composites remain units of comprehension.  
  "claim-level quality metadata is the *reference model*; composite forms (decision records, guides) remain the units of comprehension"
- Y:Q23 L30-30 · INV · The architecture must support both granularities without forced atomization.  
  "the architecture must support both granularities without forcing atomization."

## G19 · L33-33
- X:Q19 L33-33 · INV · PK-P4 Execution-Proximity statement.  
  "Trust in knowledge is stratified by proximity to executable reality; validation-by-execution is the only mechanism proven to keep an artifact true."
- Y:Q24 L33-33 · CLM · PK-P4: trust follows execution proximity; execution is the only proven validation.  
  "Trust in knowledge is stratified by proximity to executable reality; validation-by-execution is the only mechanism proven to keep an artifact true."

## G20 · L34-34
- X:Q20 L34-34 · CLM · PK-P4 rationale.  
  "Accuracy–consultation correlation runs r=0.67 (testing docs) to 0.03 (specifications)"
- Y:Q25 L34-34 · MATH · PK-P4 rationale: correlation from 0.67 to 0.03; non-executable knowledge decays.  
  "Accuracy–consultation correlation runs r=0.67 (testing docs) to 0.03 (specifications); everything non-executable relies on social process and decays."

## G21 · L35-35
- X:Q21 L35-35 · CLM · PK-P4 supporting evidence.  
  "running code > tests > code-proximate docs > architecture docs > wikis"
- Y:Q26 L35-35 · REL · PK-P4 is supported by B's trust hierarchy, D's grounding results and A's in-flow preference.  
  "B (trust hierarchy: running code > tests > code-proximate docs > architecture docs > wikis) · D (curated-KB grounding 6% vs 35% hallucination) · A (in-flow preference)."

## G22 · L36-36
- X:Q22 L36-36 · CLM · PK-P4 contradictory evidence.  
  "automation *relocates* staleness"
- Y:Q27 L36-36 · CLM · Counter-evidence: automation relocates staleness to unmaintained source models.  
  "B/C5-auto (automation *relocates* staleness — generated views of an unmaintained model are"

## G23 · L37-37
- X:Q23 L37-37 · CLM · PK-P4 consequence.  
  "the further a form sits from execution, the heavier its governance metadata must be — and the lower its default authority."
- Y:Q28 L37-37 · INV · Prefer executable forms; distance from execution raises governance metadata and lowers default authority.  
  "prefer knowledge forms that execute or are bound to execution; the further a form sits from execution, the heavier its governance metadata must be — and the lower its default authority."

## G24 · L40-40
- X:Q24 L40-40 · INV · PK-P5 Two-Regime statement.  
  "Knowledge evolves under exactly two regimes: **decisions** are immutable and superseded by link; **descriptions** evolve or regenerate with their referent. Every artifact declares its regime."
- Y:Q29 L40-40 · CLM · PK-P5: exactly two evolution regimes exist.  
  "Knowledge evolves under exactly two regimes: **decisions** are immutable and superseded by link; **descriptions** evolve or regenerate with their referent."
- Y:Q30 L40-40 · INV · Every artifact must declare its regime.  
  "Every artifact declares its regime."

## G25 · L41-41
- X:Q25 L41-41 · CLM · PK-P5 rationale.  
  "The Nygard/Martraire contradiction resolves in practice as regime-per-class, never one rule for all."
- Y:Q31 L41-41 · CLM · PK-P5 rationale: the Nygard/Martraire contradiction resolves as regime-per-class.  
  "The Nygard/Martraire contradiction resolves in practice as regime-per-class, never one rule for all."

## G26 · L42-42
- X:Q26 L42-42 · CLM · PK-P5 supporting evidence.  
  "dated decisions stay true as historical facts"
- Y:Q32 L42-42 · REL · PK-P5 is supported by B, C and A.  
  "B (C3 reconciliation; ADR = only mainstream form with retirement semantics; dated decisions stay true as historical facts) · C (statement ranks; supersession-is-a-link) · A (falsified claims retained as informative)."

## G27 · L43-43
- X:Q27 L43-43 · CLM · PK-P5 contradictory evidence.  
  "boundary artifacts exist (part decision, part description — e.g., design documents)"
- Y:Q33 L43-43 · CLM · Counter-evidence: mixed artifacts exist and their regime assignment is open.  
  "boundary artifacts exist (part decision, part description — e.g., design documents); regime assignment for them is an open question (§8)."

## G28 · L44-44
- X:Q28 L44-44 · CLM · PK-P5 consequence.  
  ""which regime?" is a mandatory property of every knowledge class the future architecture defines."
- Y:Q34 L44-44 · REL · The D6 lifecycle forks at evolution according to regime.  
  "the lifecycle (D6) forks at evolution;"
- Y:Q35 L44-44 · INV · Regime is a mandatory property of every future knowledge class.  
  "is a mandatory property of every knowledge class the future architecture defines."

## G29 · L47-47
- X:Q29 L47-47 · INV · PK-P6 Minimal-Sufficient-Context statement.  
  "Retrieval is task-driven, not document-driven: the goal is the smallest assembly of claims that lets the task proceed, for human and AI consumers alike."
- Y:Q36 L47-47 · INV · PK-P6: retrieval aims at the smallest task-sufficient claim assembly.  
  "Retrieval is task-driven, not document-driven: the goal is the smallest assembly of claims that lets the task proceed, for human and AI consumers alike."

## G30 · L48-48
- X:Q30 L48-48 · CLM · PK-P6 rationale.  
  "Unfiltered volume measurably harms both consumer types."
- Y:Q37 L48-48 · CLM · PK-P6 rationale: unfiltered volume harms humans and AI.  
  "Unfiltered volume measurably harms both consumer types."

## G31 · L49-49
- X:Q31 L49-49 · CLM · PK-P6 supporting evidence.  
  "context rot in 18/18 frontier models"
- Y:Q38 L49-49 · REL · PK-P6 is supported by D's context-rot/masking/assembly results and B's fragment reading.  
  "D (context rot in 18/18 frontier models; masking +2.6% at −52% cost; assembly optimization 89.1% vs 70.7%; CLT redundancy and split-attention effects; documents read in fragments at moment of need — B)."

## G32 · L50-50
- X:Q32 L50-50 · CLM · PK-P6 contradictory evidence.  
  "over-pruning drops the one required caveat; selection becomes the new error source"
- Y:Q39 L50-50 · CLM · Counter-evidence: over-pruning drops caveats, making selection an error source.  
  "D (over-pruning drops the one required caveat; selection becomes the new error source)."

## G33 · L51-51
- X:Q33 L51-51 · CLM · PK-P6 consequence.  
  "the architecture treats *assembly* as the first-class act, storage as substrate."
- Y:Q40 L51-51 · INV · Context is perishable and per-task; assembly is first-class and storage is substrate.  
  "context is perishable and assembled per task; the architecture treats *assembly* as the first-class act, storage as substrate."

## G34 · L54-54
- X:Q34 L54-54 · INV · PK-P7 Coexistence-with-Status statement.  
  "Contradictory knowledge coexists at rest under explicit ranked status; resolution happens at the point of consumption; demotion is visible and reasoned, never a silent edit."
- Y:Q41 L54-54 · INV · PK-P7: contradictions coexist ranked at rest, resolve at consumption, and are demoted visibly.  
  "Contradictory knowledge coexists at rest under explicit ranked status; resolution happens at the point of consumption; demotion is visible and reasoned, never a silent edit."

## G35 · L55-55
- X:Q35 L55-55 · CLM · PK-P7 rationale.  
  "Forcing resolution at storage destroys information"
- Y:Q42 L55-55 · CLM · PK-P7 rationale: storage-time resolution destroys information; large systems curate plural truths.  
  "Forcing resolution at storage destroys information; the largest running systems curate plural truths."

## G36 · L56-56
- X:Q36 L56-56 · CLM · PK-P7 supporting evidence.  
  "Wikidata preferred/normal/deprecated + reason"
- Y:Q43 L56-56 · REL · PK-P7 is supported by C.  
  "C (Wikidata preferred/normal/deprecated + reason; WP conflicting-sources; C3 as the deepest IQ dispute with an observed synthesis)."

## G37 · L57-57
- X:Q37 L57-57 · CLM · PK-P7 contradictory evidence (open gap).  
  "machine consumers acting on knowledge need one value"
- Y:Q44 L57-57 · CLM · Counter-evidence: machines need one value, and AI retrieval lacks conflict adjudication.  
  "D (machine consumers acting on knowledge need one value; mainstream AI retrieval has no conflict adjudication — a named open gap, §8)."

## G38 · L58-58
- X:Q38 L58-58 · CLM · PK-P7 consequence.  
  "the architecture never has a "delete the losing fact" operation; it has rank, reason, and resolve-at-read."
- Y:Q45 L58-58 · INV · The architecture has no delete-the-losing-fact operation; it uses rank, reason and resolve-at-read.  
  "the architecture never has a"

## G39 · L61-61
- X:Q39 L61-61 · INV · PK-P8 Staged-Forgetting statement.  
  "Retirement is a designed lifecycle stage: an explicit status transition through demotion and archival — never silent deletion, never neglect."
- Y:Q46 L61-61 · INV · PK-P8: retirement is a designed, explicit status transition.  
  "Retirement is a designed lifecycle stage: an explicit status transition through demotion and archival — never silent deletion, never neglect."

## G40 · L62-62
- X:Q40 L62-62 · CLM · PK-P8 rationale.  
  "Unretired stale knowledge is actively harmful (newcomer dropout, AI grounding poisoning), while destroyed knowledge breaks rationale archaeology."
- Y:Q47 L62-62 · CLM · PK-P8 rationale: unretired stale knowledge harms, and destroyed knowledge breaks rationale archaeology.  
  "Unretired stale knowledge is actively harmful (newcomer dropout, AI grounding poisoning), while destroyed knowledge breaks rationale archaeology."

## G41 · L63-63
- X:Q41 L63-63 · CLM · PK-P8 supporting evidence.  
  "default retirement mode = silent abandonment discovered by a confused reader"
- Y:Q48 L63-63 · REL · PK-P8 is supported by C, A and B.  
  "C (ROT staged disposal; archive = out of navigation, in history) · A (retirement the least-designed stage everywhere; maintain-or-divest gates only in late KM models) · B (default retirement mode = silent abandonment discovered by a confused reader; stale worse than none — Steinmacher)."

## G42 · L64-64
- X:Q42 L64-64 · CLM · PK-P8 contradictory evidence (open dispute).  
  "both sides support *explicit* mechanics over the status quo of neglect"
- Y:Q49 L64-64 · CLM · Counter-evidence: forgetting-as-hygiene vs failure is open, but both sides favour explicit mechanics.  
  "A/C4 (managed-forgetting-as-hygiene vs forgetting-as-failure remains a genuinely open dispute — but both sides support *explicit* mechanics over the status quo of neglect)."

## G43 · L65-65
- X:Q43 L65-65 · CLM · PK-P8 consequence.  
  "every knowledge class the architecture defines must name its retirement path before it names its creation path."
- Y:Q50 L65-65 · INV · Each knowledge class must define its retirement path before its creation path.  
  "every knowledge class the architecture defines must name its retirement path before it names its creation path."

## G44 · L68-68
- X:Q44 L68-68 · INV · PK-P9 Succession-over-Capture statement.  
  "Tacit knowledge is transferred by practice and succession, not by capture mandates"
- Y:Q51 L68-68 · CLM · PK-P9: tacit knowledge transfers by practice and succession.  
  "Tacit knowledge is transferred by practice and succession, not by capture mandates"
- Y:Q52 L68-68 · INV · The platform measures concentration risk, supports succession, and never mandates externalization.  
  "the platform measures concentration risk and supports succession mechanics — it never demands externalization of what cannot be externalized."

## G45 · L69-69
- X:Q45 L69-69 · CLM · PK-P9 rationale.  
  "The dominant conversion theory (SECI) lacks empirical grounding"
- Y:Q53 L69-69 · CLM · PK-P9 rationale: SECI is ungrounded, offboarding write-ups fail, only succession is measured (~15–25%).  
  "The dominant conversion theory (SECI) lacks empirical grounding;"

## G46 · L70-70
- X:Q46 L70-70 · CLM · PK-P9 supporting evidence.  
  "truck factor ≤2 in 65% of projects"
- Y:Q54 L70-70 · REL · PK-P9 is supported by A and B.  
  "A (SECI critique; Polanyi line) · B (Robillard; Rigby fat-tailed losses; truck factor ≤2 in 65% of projects; Google onboarding = apprenticeship)."

## G47 · L71-71
- X:Q47 L71-71 · CLM · PK-P9 contradictory evidence → rationale vs skill distinction.  
  "decision-time capture of *rationale* is worthwhile and different"
- Y:Q55 L71-71 · CLM · Counter-evidence/qualification: rationale capture at decision time is worthwhile, since the window applies to decisions, not skill.  
  "decision-time capture of *rationale* is worthwhile and different — the capture window argument (C: knowledge vaporization) applies to decisions, not to skill."

## G48 · L72-72
- X:Q48 L72-72 · CLM · PK-P9 consequence.  
  "no deliverable of the future platform is a tacit-knowledge repository."
- Y:Q56 L72-72 · INV · Rationale and skill are distinguished; no tacit-knowledge repository will be built.  
  "the architecture distinguishes rationale (capturable at decision time, PK-P5 regime 1) from skill (succession only); no deliverable of the future platform is a tacit-knowledge repository."

## G49 · L75-75
- X:Q49 L75-75 · INV · PK-P10 Scent statement.  
  "Discoverability is a property of labels, addresses, and summaries — not of content. Knowledge that emits no scent is functionally nonexistent."
- Y:Q57 L75-75 · DEF · PK-P10: discoverability is a property of labels/addresses/summaries; scentless knowledge is effectively nonexistent.  
  "Discoverability is a property of labels, addresses, and summaries — not of content. Knowledge that emits no scent is functionally nonexistent."

## G50 · L76-76
- X:Q50 L76-76 · CLM · PK-P10 rationale.  
  "the NASA failure was a scent failure, not a storage failure."
- Y:Q58 L76-76 · CLM · PK-P10 rationale: foraging theory is validated, and NASA's failure was one of scent.  
  "Information foraging is one of the few empirically validated theories in the field; the NASA failure was a scent failure, not a storage failure."

## G51 · L77-77
- X:Q51 L77-77 · CLM · PK-P10 supporting evidence.  
  "naming and structure ARE knowledge architecture"
- Y:Q59 L77-77 · REL · PK-P10 is supported by C and D; naming and structure are knowledge architecture.  
  "C (Pirolli & Card; Nielsen abandonment data; ROT dilutes collection-level scent) · D (agentic search depends on repository legibility — naming and structure ARE knowledge architecture)."

## G52 · L78-78
- X:Q52 L78-78 · CLM · PK-P10 contradictory evidence answered.  
  "snippets are scent surfaces"
- Y:Q60 L78-78 · CLM · No substantive counter-evidence; the objection that search makes scent obsolete is answered because snippets are scent surfaces.  
  "is answered within the theory: snippets are scent surfaces)."

## G53 · L79-79
- X:Q53 L79-79 · CLM · PK-P10 consequence.  
  "naming, titling, summarization, and addressability are architectural obligations of every knowledge producer"
- Y:Q61 L79-79 · INV · Naming, titling, summarizing and addressing are obligations of every producer.  
  "naming, titling, summarization, and addressability are architectural obligations of every knowledge producer — enforceable, checkable, and cheap."

## G54 · L85-85
- X:Q54 L85-85 · CLM · PK-AD1 centralized vs co-located.  
  "**Co-located content, centralized *governance metadata only***"
- Y:Q62 L85-85 · CLM · PK-AD1 contradiction: a central store has one governance surface but drifts; co-location survives but fragments governance.  
  "**Centralized vs co-located knowledge** (A/C5)"
- Y:Q63 L85-85 · INV · PK-AD1 decision: content is co-located, and only governance metadata is centralized.  
  "**Co-located content, centralized *governance metadata only*** (status vocabulary, ownership registry). Scoped canonicity per PK-P2/P4."

## G55 · L86-86
- X:Q55 L86-86 · CLM · PK-AD2 documentation vs executable.  
  "**Executable where possible; bind the rest to execution**"
- Y:Q64 L86-86 · CLM · PK-AD2 contradiction: docs are expressive but decay; executables stay true but are limited.  
  "**Documentation vs executable knowledge**"
- Y:Q65 L86-86 · INV · PK-AD2 decision: executable where possible, bind the rest; unbound prose has lowest authority.  
  "**Executable where possible; bind the rest to execution** (checks, links, enforcement); unbound prose is lowest-authority by default."

## G56 · L87-87
- X:Q56 L87-87 · CLM · PK-AD3 immutable vs evolving.  
  "**Both, by declared regime** (PK-P5). Neither is "the" rule."
- Y:Q66 L87-87 · CLM · PK-AD3 contradiction: immutability preserves history; evolution preserves currency.  
  "**Immutable vs evolving artifacts** (B/C3)"
- Y:Q67 L87-87 · INV · PK-AD3 decision: both, by declared regime.  
  "**Both, by declared regime** (PK-P5). Neither is"

## G57 · L88-88
- X:Q57 L88-88 · CLM · PK-AD4 explicit vs emergent ontology.  
  "**Minimal explicit core + emergent enrichment; faceted, never enumerative.** No comprehensive ontology, ever."
- Y:Q68 L88-88 · CLM · PK-AD4 contradiction: formal ontology is precise but catastrophically costly; emergent is cheap but noisy.  
  "**Explicit vs emergent ontology** (C/C1–C2)"
- Y:Q69 L88-88 · INV · PK-AD4 decision: a minimal explicit core plus emergent enrichment, faceted, and never a comprehensive ontology.  
  "**Minimal explicit core + emergent enrichment; faceted, never enumerative.** No comprehensive ontology, ever."

## G58 · L89-89
- X:Q58 L89-89 · CLM · PK-AD5 capture.  
  "**Selective with intake gates — except decisions, which are captured at decision time or never.**"
- Y:Q70 L89-89 · CLM · PK-AD5 contradiction: capture-all becomes ROT and harms; selective risks loss.  
  "**Capture everything vs selectively** (C/C5, D)"
- Y:Q71 L89-89 · INV · PK-AD5 decision: selective capture with intake gates, except decisions captured at decision time.  
  "**Selective with intake gates — except decisions, which are captured at decision time or never.** The capture window is the exception that justifies urgency, not volume."

## G59 · L90-90
- X:Q59 L90-90 · CLM · PK-AD6 SSOT.  
  "**Scoped canonicity, reference-based reuse.** Global SSOT is rejected."
- Y:Q72 L90-90 · CLM · PK-AD6 contradiction: a global SSOT becomes a stale mirror; scoping needs discipline.  
  "**Global SSOT vs scoped canonicity** (C/C4)"
- Y:Q73 L90-90 · REV · PK-AD6 decision: global SSOT is rejected in favour of scoped canonicity.  
  "**Scoped canonicity, reference-based reuse.** Global SSOT is rejected."

## G60 · L91-91
- X:Q60 L91-91 · CLM · PK-AD7 forgetting.  
  "**Managed forgetting as a designed capability** with the safety rail: demote visibly, archive retrievably, never destroy silently."
- Y:Q74 L91-91 · CLM · PK-AD7 contradiction: preventing all forgetting means hoarding; managed forgetting risks loss.  
  "**Forgetting as failure vs hygiene** (A/C4, C/C7)"
- Y:Q75 L91-91 · INV · PK-AD7 decision: managed forgetting with safety rails.  
  "**Managed forgetting as a designed capability** with the safety rail: demote visibly, archive retrievably, never destroy silently."

## G61 · L92-92
- X:Q61 L92-92 · CLM · PK-AD8 retrieval.  
  "**Live sources are the default for code-proximate knowledge; indexes only where staleness is governed**"
- Y:Q76 L92-92 · CLM · PK-AD8 contradiction: indexes are cheap but silently stale; live discovery is current but token-expensive.  
  "**Pre-indexed retrieval vs live discovery** (D)"
- Y:Q77 L92-92 · INV · PK-AD8 decision: live sources by default, indexes only with governed staleness; economics remain open.  
  "**Live sources are the default for code-proximate knowledge; indexes only where staleness is governed** (status+freshness metadata). Economics = open question (§8)."

## G62 · L98-98
- X:Q62 L98-98 · DEF · Candidate BC Knowledge Qualification.  
  "**Knowledge Qualification** | Intake validation (born-stale gate), claim evaluation, freshness verification over time"
- Y:Q78 L98-98 · DEF · Candidate BC Knowledge Qualification: intake validation, claim evaluation, freshness verification.  
  "| **Knowledge Qualification** | Intake validation (born-stale gate), claim evaluation, freshness verification over time |"

## G63 · L99-99
- X:Q63 L99-99 · DEF · Candidate BC Discovery & Assembly.  
  "**Knowledge Discovery & Assembly** | In-flow retrieval, task-driven context assembly, scent enforcement"
- Y:Q79 L99-99 · DEF · Candidate BC Discovery & Assembly.  
  "| **Knowledge Discovery & Assembly** | In-flow retrieval, task-driven context assembly, scent enforcement |"

## G64 · L100-100
- X:Q64 L100-100 · DEF · Candidate BC Knowledge Governance.  
  "**Knowledge Governance** | Status vocabulary, scoped authority, ownership + freshness, compliance"
- Y:Q80 L100-100 · DEF · Candidate BC Knowledge Governance.  
  "| **Knowledge Governance** | Status vocabulary, scoped authority, ownership + freshness, compliance |"
- Y:Q81 L100-100 · CLM · Authority is a process property, and ownerless content is non-authoritative.  
  "(authority = process property; ownerless = non-authoritative)"

## G65 · L101-101
- X:Q65 L101-101 · DEF · Candidate BC Evolution & Retirement.  
  "**Knowledge Evolution & Retirement** | Regime management (immutable vs evolving), supersession, staged forgetting"
- Y:Q82 L101-101 · DEF · Candidate BC Evolution & Retirement.  
  "| **Knowledge Evolution & Retirement** | Regime management (immutable vs evolving), supersession, staged forgetting |"

## G66 · L102-102
- X:Q66 L102-102 · DEF · Candidate BC Provenance.  
  "**Knowledge Provenance** | Lineage, custody, trust metadata riding every claim"
- Y:Q83 L102-102 · DEF · Candidate BC Knowledge Provenance.  
  "| **Knowledge Provenance** | Lineage, custody, trust metadata riding every claim |"

## G67 · L104-104
- X:Q67 L104-104 · CLM · [INTERPRETATION in source] Self-similarity to platform contexts.  
  "these candidates are **self-similar to the Engineering Platform's own bounded contexts**"
- X:Q68 L104-104 · CLM · The question is flagged, not decided.  
  "Whether that similarity is deep (one domain, two adoptions) or superficial is an architecture-phase question — flagged, not decided."
- Y:Q84 L104-104 · REL · The candidate BCs correspond to Engineering Platform BCs.  
  "these candidates are **self-similar to the Engineering Platform's own bounded contexts** (Verification & Evidence ≙ Qualification; Session Continuity ≙ Discovery & Assembly; Knowledge Governance appears in both)."
- Y:Q85 L104-104 · CLM · The platform may partially implement the knowledge domain (hedged).  
  "The platform may already be a partial implementation of the knowledge domain applied to engineering knowledge."
- Y:Q86 L104-104 · CLM · The depth of the similarity is undecided.  
  "Whether that similarity is deep (one domain, two adoptions) or superficial is an architecture-phase question — flagged, not decided."

## G68 · L108-108
- X:Q69 L108-108 · INV · D4 invariant 1.  
  "**Knowledge never silently disappears** — retirement is a status transition through archive; deletion of the last copy of a claim is forbidden."
- Y:Q87 L108-108 · INV · Invariant 1: nothing silently disappears, and deleting the last copy of a claim is forbidden.  
  "**Knowledge never silently disappears** — retirement is a status transition through archive; deletion of the last copy of a claim is forbidden."

## G69 · L109-109
- X:Q70 L109-109 · INV · D4 invariant 2.  
  "**Status transitions are explicit, reasoned, and visible at discovery.**"
- Y:Q88 L109-109 · INV · Invariant 2.  
  "**Status transitions are explicit, reasoned, and visible at discovery.** (PK-P2 evidence set)"

## G70 · L110-110
- X:Q71 L110-110 · INV · D4 invariant 3 (provenance mandatory).  
  "a claim without provenance is data, not knowledge."
- Y:Q89 L110-110 · INV · Invariant 3: provenance (producer, time, context) on every claim.  
  "**Provenance is mandatory on every claim** — producer, time, context"
- Y:Q90 L110-110 · DEF · Without provenance, a claim is data, not knowledge.  
  "a claim without provenance is data, not knowledge."

## G71 · L111-111
- X:Q72 L111-111 · INV · D4 invariant 4.  
  "**Contradictions remain visible** — coexist at rest under rank; no silent-pick, no silent-edit resolution."
- Y:Q91 L111-111 · INV · Invariant 4.  
  "**Contradictions remain visible** — coexist at rest under rank; no silent-pick, no silent-edit resolution."

## G72 · L112-112
- X:Q73 L112-112 · INV · D4 invariant 5.  
  "**Authority is scoped** — canonical-within-a-scope; no artifact claims global truth."
- Y:Q92 L112-112 · INV · Invariant 5.  
  "**Authority is scoped** — canonical-within-a-scope; no artifact claims global truth. (C-F3.4)"

## G73 · L113-113
- X:Q74 L113-113 · INV · D4 invariant 6.  
  "**Freshness is observable at the point of discovery** — and priced per domain half-life, not uniformly."
- Y:Q93 L113-113 · INV · Invariant 6.  
  "**Freshness is observable at the point of discovery** — and priced per domain half-life, not uniformly."

## G74 · L114-114
- X:Q75 L114-114 · INV · D4 invariant 7.  
  "**Supersession is a link, never an edit** — the superseded record keeps its truth as history."
- Y:Q94 L114-114 · INV · Invariant 7.  
  "**Supersession is a link, never an edit** — the superseded record keeps its truth as history."
- Y:Q95 L114-114 · REL · Invariant 7 matches the platform's append-only law.  
  "matches the platform's existing append-only law"

## G75 · L115-115
- X:Q76 L115-115 · INV · D4 invariant 8.  
  "**No knowledge capability may create a second-population artifact class.**"
- Y:Q96 L115-115 · INV · Invariant 8 is PK-P1 restated as an invariant.  
  "**No knowledge capability may create a second-population artifact class.** (PK-P1 — the law as an invariant)"

## G76 · L116-116
- X:Q77 L116-116 · INV · D4 invariant 9.  
  "**Knowledge quality is evidenced, never asserted** — freshness, authority, and trust derive from recorded process properties (owner, date, gate results), never from self-description."
- Y:Q97 L116-116 · INV · Invariant 9: quality derives from recorded process properties.  
  "**Knowledge quality is evidenced, never asserted** — freshness, authority, and trust derive from recorded process properties (owner, date, gate results), never from self-description."

## G77 · L122-122
- X:Q78 L122-122 · CLM · Force: Trust.  
  "one betrayal reroutes consumers permanently — so quality failures are catastrophic, not incremental"
- Y:Q98 L122-122 · CLM · Force Trust: trust is non-uniform, and quality failures are catastrophic.  
  "The architecture must never assume uniform trust; one betrayal reroutes consumers permanently — so quality failures are catastrophic, not incremental"

## G78 · L123-123
- X:Q79 L123-123 · CLM · Force: Freshness/entropy.  
  "Decay is domain-relative and much knowledge is born stale — freshness must be observable AND gated at intake"
- Y:Q99 L123-123 · INV · Force Freshness: freshness must be observable and gated at intake.  
  "Decay is domain-relative and much knowledge is born stale — freshness must be observable AND gated at intake"

## G79 · L124-124
- X:Q80 L124-124 · CLM · Force: Discoverability.  
  "Scent is architectural: labels, addresses, summaries carry the finding function; collections rot scent-wise"
- Y:Q100 L124-124 · CLM · Force Discoverability: scent is architectural, and collections lose scent.  
  "Scent is architectural: labels, addresses, summaries carry the finding function; collections rot scent-wise"

## G80 · L125-125
- X:Q81 L125-125 · CLM · Force: Context assembly.  
  "Task-driven minimal assembly is the consumption model for both humans and AI; storage is substrate"
- Y:Q101 L125-125 · CLM · Force Context assembly.  
  "Task-driven minimal assembly is the consumption model for both humans and AI; storage is substrate"

## G81 · L126-126
- X:Q82 L126-126 · CLM · Force: Human cognition.  
  "the transferable unit is the worked example"
- Y:Q102 L126-126 · CLM · Force Human cognition: packaging is governed by cognitive effects, and the worked example is the transferable unit.  
  "Chunking, split-attention, redundancy govern packaging; the transferable unit is the worked example; consistency enables expert chunking"

## G82 · L127-127
- X:Q83 L127-127 · CLM · Force: AI context limitations.  
  "conflict adjudication for machine consumers is unsolved"
- Y:Q103 L127-127 · CLM · Force AI context limitations.  
  "Context is perishable; injected context is currently assumed trustworthy (unverified); conflict adjudication for machine consumers is unsolved"

## G83 · L128-128
- X:Q84 L128-128 · CLM · Force: Organizational learning/turnover.  
  "succession beats capture for skill; decision rationale has a narrow capture window"
- Y:Q104 L128-128 · CLM · Force Organizational learning/turnover.  
  "Knowledge lives in mental models; loss is fat-tailed; succession beats capture for skill; decision rationale has a narrow capture window"

## G84 · L133-138
- X:Q85 L133-138 · REL · Lifecycle diagram: evolution forks (descriptions loop; decisions → supersession → historical retention → staged retirement).  
  "Creation ──► Qualification ──► Publication ──► Discovery & Consumption ──► Evolution"
- Y:Q105 L133-138 · MATH · The lifecycle diagram: a linear sequence to Evolution, which forks into a descriptions loop and a decisions path (Supersession → Historical Retention → Staged Retirement).  
  "Creation ──► Qualification ──► Publication ──► Discovery & Consumption ──► Evolution ─┬─► [descriptions] evolve/regenerate ──► (loop)"

## G85 · L143-143
- X:Q86 L143-143 · DEF · Stage Creation (invariants 3, 8).  
  "A claim is articulated inside an existing gesture; decision rationale is captured at decision time or never"
- Y:Q106 L143-143 · INV · Creation stage (invariants 3, 8 in force).  
  "A claim is articulated inside an existing gesture; decision rationale is captured at decision time or never"

## G86 · L144-144
- X:Q87 L144-144 · DEF · Stage Qualification (3, 9).  
  "Intake validation: born-stale gate, claim evaluation, provenance check"
- Y:Q107 L144-144 · DEF · Qualification stage (invariants 3, 9).  
  "Intake validation: born-stale gate, claim evaluation, provenance check"
- Y:Q108 L144-144 · REL · Qualification stage evidence (the 58.4% figure is restated as born stale).  
  "A (McElroy; 58.4% born stale)"

## G87 · L145-145
- X:Q88 L145-145 · DEF · Stage Publication (2, 5, 6).  
  "The claim becomes discoverable with status, owner, freshness, and scent"
- Y:Q109 L145-145 · DEF · Publication stage (invariants 2, 5, 6).  
  "The claim becomes discoverable with status, owner, freshness, and scent"

## G88 · L146-146
- X:Q89 L146-146 · DEF · Stage Discovery & Consumption (2, 4, 6).  
  "consumption feeds back as validation ("validation is use")"
- Y:Q110 L146-146 · REL · Discovery & Consumption stage: consumption feeds back as validation (invariants 2, 4, 6).  
  "Task-driven assembly of minimal sufficient claims; consumption feeds back as validation"

## G89 · L147-147
- X:Q90 L147-147 · DEF · Stage Evolution (7).  
  "Regime fork: descriptions evolve/regenerate with the referent; decisions never change"
- Y:Q111 L147-147 · INV · Evolution stage (invariant 7).  
  "Regime fork: descriptions evolve/regenerate with the referent; decisions never change"

## G90 · L148-148
- X:Q91 L148-148 · DEF · Stage Supersession (4, 7).  
  "A new claim demotes the old by link, with a reason"
- Y:Q112 L148-148 · DEF · Supersession stage (invariants 4, 7).  
  "A new claim demotes the old by link, with a reason"

## G91 · L149-149
- X:Q92 L149-149 · DEF · Stage Historical retention (1, 2).  
  "Demoted knowledge remains retrievable, marked, out of default navigation"
- Y:Q113 L149-149 · DEF · Historical retention stage (invariants 1, 2).  
  "Demoted knowledge remains retrievable, marked, out of default navigation"

## G92 · L150-150
- X:Q93 L150-150 · DEF · Stage Staged retirement (1, 8).  
  "Deliberate, owned, periodic — never silent abandonment"
- Y:Q114 L150-150 · INV · Staged retirement stage (invariants 1, 8).  
  "Deliberate, owned, periodic — never silent abandonment"

## G93 · L156-156
- X:Q94 L156-156 · DEF · Capability Knowledge Discovery.  
  "**Knowledge Discovery** | Find relevant claims in-flow, by scent and by live-source search"
- Y:Q115 L156-156 · DEF · Capability Knowledge Discovery.  
  "| **Knowledge Discovery** | Find relevant claims in-flow, by scent and by live-source search | C, D, B |"

## G94 · L157-157
- X:Q95 L157-157 · DEF · Capability Context Assembly (the one new capability).  
  "**Context Assembly** | Compose minimal sufficient task context for a human or AI consumer"
- Y:Q116 L157-157 · DEF · Capability Context Assembly, the one new capability recommended.  
  "| **Context Assembly** | Compose minimal sufficient task context for a human or AI consumer | D (the observed gap; the one NEW capability the Discovery Report recommended) |"

## G95 · L158-158
- X:Q96 L158-158 · DEF · Capability Knowledge Qualification.  
  "**Knowledge Qualification** | Gate intake (born-stale), evaluate claims, verify freshness over time"
- Y:Q117 L158-158 · DEF · Capability Knowledge Qualification.  
  "| **Knowledge Qualification** | Gate intake (born-stale), evaluate claims, verify freshness over time | A, C |"

## G96 · L159-159
- X:Q97 L159-159 · DEF · Capability Authority & Status.  
  "**Knowledge Authority & Status** | Scoped canonicity, status vocabulary, ownership, visible-at-discovery"
- Y:Q118 L159-159 · DEF · Capability Authority & Status.  
  "| **Knowledge Authority & Status** | Scoped canonicity, status vocabulary, ownership, visible-at-discovery | A, B, C |"

## G97 · L160-160
- X:Q98 L160-160 · DEF · Capability Provenance.  
  "**Knowledge Provenance** | Lineage and trust metadata on every claim"
- Y:Q119 L160-160 · DEF · Capability Provenance.  
  "| **Knowledge Provenance** | Lineage and trust metadata on every claim | C |"

## G98 · L161-161
- X:Q99 L161-161 · DEF · Capability Evolution & Supersession.  
  "**Knowledge Evolution & Supersession** | Regime-aware change: evolve descriptions, supersede decisions"
- Y:Q120 L161-161 · DEF · Capability Evolution & Supersession.  
  "| **Knowledge Evolution & Supersession** | Regime-aware change: evolve descriptions, supersede decisions | B, C |"

## G99 · L162-162
- X:Q100 L162-162 · DEF · Capability Retirement.  
  "**Knowledge Retirement** | Staged forgetting: demote, archive, audit cadence"
- Y:Q121 L162-162 · DEF · Capability Retirement.  
  "| **Knowledge Retirement** | Staged forgetting: demote, archive, audit cadence | C, A |"

## G100 · L163-163
- X:Q101 L163-163 · DEF · Capability Governance.  
  "**Knowledge Governance** | The invariants (D4) as enforceable, checkable rules"
- Y:Q122 L163-163 · DEF · Capability Governance: the invariants as checkable rules.  
  "| **Knowledge Governance** | The invariants (D4) as enforceable, checkable rules | all |"

## G101 · L164-164
- X:Q102 L164-164 · DEF · Capability Succession Support (measurement, not capture).  
  "deliberately NOT a capture capability"
- Y:Q123 L164-164 · DEF · Capability Succession Support, explicitly not capture.  
  "| **Succession Support** | Concentration-risk measurement and succession mechanics for tacit knowledge | B (the only measured mitigation) — deliberately NOT a capture capability |"

## G102 · L170-170
- X:Q103 L170-170 · CLM · Readiness: evidence sufficient to begin, not to commit.  
  "**Conditionally yes.**"
- Y:Q124 L170-170 · CLM · The evidence is conditionally sufficient; D1/D2/D4/D6 are triangulated and stable.  
  "**Conditionally yes.** The principles (D1), invariants (D4), lifecycle (D6), and decisions (D2) are triangulated across independent executors and stable enough to constitute a constitutional foundation."
- Y:Q125 L170-170 · CLM · Three gaps block committing to an architecture, but not writing one.  
  "Three evidence gaps remain, none of which blocks *writing* a reference architecture, but all of which block *committing* to one."

## G103 · L171-171
- X:Q104 L171-171 · CLM · Recommended next steps (usage test → reference architecture → ER-09 unpause).  
  "(1) Run the **EKP in-flow usage test** first"
- Y:Q126 L171-171 · CLM · Next step 1: the EKP usage test first.  
  "(1) Run the **EKP in-flow usage test** first — behavioral, cheap, decisive for the incumbent's role"
- Y:Q127 L171-171 · CLM · Next step 2: a PK Reference Architecture with the DRAFT→ADOPTED→STABLE lifecycle.  
  "(2) Then a **Project Knowledge Reference Architecture** derived from this synthesis — same DRAFT→ADOPTED→STABLE lifecycle as the platform's own."
- Y:Q128 L171-171 · CLM · Next step 3: unpause ER-09, reframed, behind the execution queue.  
  "(3) Unpause **ER-09** reframed under PK-P1..P10 (document rules become bindings of the knowledge principles). All sequenced behind the existing execution queue (C3, product primacy)."

## G104 · L172-172
- X:Q105 L172-172 · CLM · Missing evidence E-1..E-3.  
  "E-1: the EKP usage test (unrun). E-2: conflict adjudication for machine consumers"
- Y:Q129 L172-172 · CLM · Missing evidence E-1, E-2, E-3.  
  "E-1: the EKP usage test (unrun). E-2: conflict adjudication for machine consumers (open in the literature — a pilot would produce first-party evidence). E-3: token economics of live discovery vs curated assembly at this repository's scale (measurable during normal sessions)."

## G105 · L173-173
- X:Q106 L173-173 · CLM · Biggest remaining uncertainty.  
  "Whether **in-flow context assembly** can be built without violating PK-P1 itself"
- Y:Q130 L173-173 · CLM · The biggest uncertainty is whether context assembly can avoid violating PK-P1.  
  "Whether **in-flow context assembly** can be built without violating PK-P1 itself — i.e., without becoming a curated second-population artifact."
- Y:Q131 L173-173 · INV · The answer must come from a pilot, not more synthesis.  
  "The design answer must come from a small evidence-producing pilot on real tickets, not from further synthesis."

## G106 · L177-177
- X:Q107 L177-177 · CLM · Constraint check and gate.  
  "**STOP — awaiting ARB review of this synthesis before any architecture is written.**"
- Y:Q132 L177-177 · CLM · Constraint check: nothing designed, everything anchored and traceable.  
  "no folders, templates, software, APIs, aggregates, or interactions designed; every principle, decision, invariant, force, stage, and capability carries an evidence anchor; no new research performed; all concepts traceable to the closed evidence base."
- Y:Q133 L177-177 · INV · No architecture before ARB review.  
  "**STOP — awaiting ARB review of this synthesis before any architecture is written.**"

