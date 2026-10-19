# V-2 pairing worksheet 03

Mechanical grouping by overlapping line ranges only; no judgement. Two anonymous extractions, X (126 items) and Y (99 items).

source: docs/implementation/RQ-002_Knowledge_Taxonomy.md

## G1 · L3-3
- X:Q1 L3-3 · DEF · The document is a synthesis artifact that serves as the semantic foundation for a future Reference Architecture.  
  "synthesis artifact (the semantic foundation for a future Reference Architecture)"
- X:Q2 L3-3 · INV · The taxonomy excludes folder structures, templates, document ontologies and design.  
  "NOT a folder structure, NOT templates, NOT an ontology of documents, NOT design."
- Y:Q1 L3-3 · CLM · Kind: semantic foundation, not design.  
  "NOT a folder structure, NOT templates, NOT an ontology of documents, NOT design."

## G2 · L4-4
- X:Q3 L4-4 · INV · The Reference Architecture is blocked until an ARB ruling (status COMPLETE, presented for ARB review).  
  "**STOP: the Reference Architecture may not be written until the ARB rules.**"
- Y:Q2 L4-4 · CLM · Status; Reference Architecture blocked until ARB rules.  
  "COMPLETE — presented for ARB review."

## G3 · L5-5
- X:Q4 L5-5 · ASM · The closed evidence base is the charter, raw findings, discovery report and synthesis (with its 10 principles and 9 invariants).  
  "RQ-002 charter · Raw Findings (executors A/B/C/D) · Strategic Discovery Report · Architectural Synthesis (PK-P1..P10, invariants I-1..9)."
- X:Q5 L5-5 · INV · No new research is allowed; every classification must be traceable, and candidates must be tested, not assumed.  
  "No new research; every classification is traceable; candidates are validated **or falsified**, not assumed."
- Y:Q3 L5-5 · ASM · Method: closed evidence base; candidate types tested.  
  "candidates are validated **or falsified**, not assumed."

## G4 · L11-11
- X:Q6 L11-11 · CLM · Project knowledge is a family of types sharing one constitutional core rather than one homogeneous domain.  
  "is a FAMILY of knowledge types sharing one constitutional core, not a homogeneous domain.**"
- X:Q7 L11-11 · CLM · Seven types are validated and five candidates are falsified or merged.  
  "Seven types survive the evidence; five candidates are falsified or merged."
- X:Q8 L11-11 · CLM · The types differ on all governance-relevant axes.  
  "The types differ on *every* governance-relevant axis — producer, validation, lifecycle, retirement, freshness, discovery"
- X:Q9 L11-11 · REL · All types are subject to the nine invariants of the Synthesis.  
  "while all obey the nine invariants of the Architectural Synthesis (status-at-discovery, provenance, supersession-by-link, …)."
- X:Q10 L11-11 · INV · Governance must be per-type; uniform treatment is forbidden by the evidence.  
  "Uniform treatment of knowledge is therefore an architectural error the evidence forbids; per-type governance is mandatory."
- Y:Q4 L11-11 · CLM · Headline: project knowledge is a family of types with one core.  
  "is a FAMILY of knowledge types sharing one constitutional core, not a homogeneous domain."
- Y:Q5 L11-11 · CLM · Counts: 7 survive, 5 falsified or merged.  
  "Seven types survive the evidence; five candidates are falsified or merged."
- Y:Q6 L11-11 · REL · All types obey the Synthesis's nine invariants.  
  "while all obey the nine invariants of the Architectural Synthesis"
- Y:Q7 L11-11 · CLM · Per-type governance is mandatory.  
  "Uniform treatment of knowledge is therefore an architectural error the evidence forbids; per-type governance is mandatory."

## G5 · L18-18
- X:Q11 L18-18 · DEF · Executable Knowledge is machine-checkable encoded knowledge, including business rules when encoded.  
  "Knowledge encoded so that a machine can check it — code, tests, gates, fitness functions, and business rules/invariants *when encoded* in them."
- Y:Q8 L18-18 · DEF · Type 1 Executable.  
  "Knowledge encoded so that a machine can check it"

## G6 · L19-19
- X:Q12 L19-19 · CLM · Executable knowledge is produced by developers while building, so producers and consumers coincide by construction.  
  "Developers, in the act of building (producer = consumer, by construction)."
- Y:Q9 L19-19 · CLM · Executable producer = consumer.  
  "Developers, in the act of building (producer = consumer, by construction)."

## G7 · L20-20
- X:Q13 L20-20 · CLM · Executable knowledge is consumed by developers, CI and agents and is the highest-trust stratum.  
  "Developers, CI, agents; the highest-trust stratum for every consumer type."
- Y:Q10 L20-20 · CLM · Executable has the highest trust.  
  "the highest-trust stratum for every consumer type"

## G8 · L21-21
- X:Q14 L21-21 · DEF · The authority of executable knowledge is the passing run, earned per execution.  
  "The passing run. Authority is earned per execution, not assigned."
- Y:Q11 L21-21 · CLM · Executable authority = the passing run.  
  "The passing run. Authority is earned per execution, not assigned."

## G9 · L22-22
- X:Q15 L22-22 · CLM · Execution validates executable knowledge and is the only mechanism empirically shown to keep artifacts true.  
  "EXECUTION — the only mechanism empirically shown to keep an artifact true"
- X:Q16 L22-22 · REL · The execution claim is anchored in B's r=0.67 trust correlation and D's 6% vs 35% grounding result.  
  "(B: trust r=0.67 at test proximity; D: curated-KB grounding 6% vs 35%)."
- Y:Q12 L22-22 · CLM · Executable validation = execution.  
  "EXECUTION — the only mechanism empirically shown to keep an artifact true"

## G10 · L23-23
- X:Q17 L23-23 · CLM · Executable knowledge changes together with what it refers to, which is why it survives.  
  "Changes with its referent in the same gesture — the survival mode (B: the only documentation class updated promptly)."
- Y:Q13 L23-23 · CLM · Executable lifecycle.  
  "Changes with its referent in the same gesture"

## G11 · L24-24
- X:Q18 L24-24 · CLM · Executable knowledge retires by deletion together with its code, with history kept in version control.  
  "Deleted with the code it guards; history preserved by version control."
- Y:Q14 L24-24 · CLM · Executable retirement.  
  "Deleted with the code it guards; history preserved by version control."

## G12 · L25-25
- X:Q19 L25-25 · CLM · Freshness of executable knowledge is self-enforcing because stale checks fail.  
  "Self-enforcing — a stale executable fails."
- Y:Q15 L25-25 · CLM · Executable freshness.  
  "Self-enforcing — a stale executable fails."

## G13 · L26-26
- X:Q20 L26-26 · CLM · Executable knowledge is discovered through live-source search, with repository legibility acting as its scent.  
  "Live-source search (agentic search; repository legibility = scent for this type — D)."
- Y:Q16 L26-26 · CLM · Executable discoverability.  
  "Live-source search (agentic search; repository legibility = scent for this type — D)."

## G14 · L27-27
- X:Q21 L27-27 · CLM · The Engineering Platform already implements executable knowledge.  
  "already implemented — the merge gate, fitness suites, greenfield PHPStan are this type in production."
- Y:Q17 L27-27 · CLM · Platform already implements Executable.  
  "already implemented — the merge gate, fitness suites, greenfield PHPStan are this type in production."

## G15 · L28-28
- X:Q22 L28-28 · REL · Executable knowledge rests on B, D and PK-P4.  
  "B (trust stratification, code-proximate survival) · D (execution-grounding) · PK-P4."
- Y:Q18 L28-28 · CLM · Evidence pointers for Executable.  
  "B (trust stratification, code-proximate survival) · D (execution-grounding) · PK-P4."

## G16 · L31-31
- X:Q23 L31-31 · DEF · Decision Knowledge is a recorded choice with context, alternatives, rationale and consequences (ADR-class).  
  "A recorded choice with context, alternatives, rationale, consequences (ADR-class; rulings; the"
- Y:Q19 L31-31 · DEF · Type 2 Decision.  
  "A recorded choice with context, alternatives, rationale, consequences"

## G17 · L32-32
- X:Q24 L32-32 · INV · Decision knowledge must be captured by deciders at decision time, or it is lost.  
  "Deciders at decision time — the capture window is decision time or never (C: vaporization)."
- Y:Q20 L32-32 · CLM · Decision producer and capture window.  
  "the capture window is decision time or never"

## G18 · L33-33
- X:Q25 L33-33 · CLM · Decision knowledge serves future maintainers, newcomers, auditors and agents, since rationale cannot be derived from code.  
  "Future maintainers, newcomers, auditors, agents (rationale is exactly what agents cannot derive from code — D)."
- Y:Q21 L33-33 · CLM · Decision consumers include agents.  
  "rationale is exactly what agents cannot derive from code"

## G19 · L34-34
- X:Q26 L34-34 · MATH · A decision is canonical iff it is accepted, unsuperseded, owned and bound to code.  
  "Canonical = accepted ∧ unsuperseded ∧ owned ∧ **bound to code**"
- X:Q27 L34-34 · REL · The binding requirement is grounded in the single longitudinal ADR success, which needed enforcement binding.  
  "(the one longitudinal ADR success required enforcement binding — B)."
- Y:Q22 L34-34 · MATH · Decision canonicity as a conjunction of four conditions.  
  "Canonical = accepted ∧ unsuperseded ∧ owned ∧ **bound to code**"

## G20 · L35-35
- X:Q28 L35-35 · CLM · Decisions are validated by enforcement-binding plus review and are the hardest type to validate.  
  "Enforcement-binding + human review; neither executes nor arises in conversation — the hardest type to validate (B)."
- Y:Q23 L35-35 · CLM · Decision validation = enforcement-binding + review.  
  "the hardest type to validate"

## G21 · L36-36
- X:Q29 L36-36 · CLM · Decision knowledge is immutable (PK-P5 regime 1); a dated decision remains historically true.  
  "IMMUTABLE — regime 1 of PK-P5; a dated decision stays true as a historical fact."
- Y:Q24 L36-36 · CLM · Decision lifecycle immutable.  
  "IMMUTABLE — regime 1 of PK-P5; a dated decision stays true as a historical fact."

## G22 · L37-37
- X:Q30 L37-37 · CLM · Decisions retire via reasoned supersession links; ADRs are the only mainstream form with built-in retirement semantics.  
  "Supersession by link with reason; the only mainstream form with built-in retirement semantics (B)."
- Y:Q25 L37-37 · CLM · Decision retirement.  
  "Supersession by link with reason; the only mainstream form with built-in retirement semantics"

## G23 · L38-38
- X:Q31 L38-38 · CLM · A decision's content does not go stale but its canonicity does; status upkeep is an unmeasured weak point.  
  "Does not stale as history; its *canonicity* stales — status upkeep is the (never-measured) weak point."
- Y:Q26 L38-38 · CLM · Decision freshness: canonicity stales, content does not.  
  "Does not stale as history; its *canonicity* stales"

## G24 · L39-39
- X:Q32 L39-39 · INV · Decision knowledge must surface when the code it governs is touched.  
  "Must surface when the governed code is touched"
- X:Q33 L39-39 · CLM · The documented ADR consumption gap is a discoverability failure; ADR readership was never measured.  
  "discoverability failure is the documented ADR consumption gap (B: readership never measured,"
- Y:Q27 L39-39 · CLM · Decision discoverability.  
  "Must surface when the governed code is touched"

## G25 · L40-40
- X:Q34 L40-40 · CLM · The platform already implements decision knowledge consistently with the evidence.  
  "already implemented (ADR corpus, rulings register, append-only, supersession) — the platform is an existence proof of this type done per the evidence."
- Y:Q28 L40-40 · CLM · Platform implements Decision type.  
  "the platform is an existence proof of this type done per the evidence."

## G26 · L41-41
- X:Q35 L41-41 · REL · Decision knowledge is anchored in B, C F1.2, A NKM-10 and the highest-demand/lowest-capture finding.  
  "B (NKM-06/ADR corpus of findings) · C (F1.2) · A (NKM-10) · highest-demand/lowest-capture finding (D F-CS-6, B)."
- Y:Q29 L41-41 · CLM · Evidence pointers for Decision.  
  "highest-demand/lowest-capture finding"

## G27 · L44-44
- X:Q36 L44-44 · DEF · Definitional Knowledge is ubiquitous-language terms fixed within one bounded context, plus the context boundaries.  
  "Ubiquitous-language terms with meaning fixed inside one bounded context; context boundaries themselves."
- Y:Q30 L44-44 · DEF · Type 3 Definitional.  
  "Ubiquitous-language terms with meaning fixed inside one bounded context; context boundaries themselves."

## G28 · L45-45
- X:Q37 L45-45 · CLM · Definitional knowledge is produced jointly by the whole team; intermediary analysts break the process.  
  "The whole team, jointly and continuously (knowledge crunching — analysts-as-intermediaries break it)."
- Y:Q31 L45-45 · CLM · Definitional producer.  
  "The whole team, jointly and continuously"

## G29 · L46-46
- X:Q38 L46-46 · CLM · The same team consumes definitional knowledge; newcomers rarely use glossaries.  
  "The same team (speech + code identifiers); newcomers via code and conversation — rarely via glossary (B)."
- Y:Q32 L46-46 · CLM · Definitional consumers learn via code and conversation.  
  "rarely via glossary"

## G30 · L47-47
- X:Q39 L47-47 · INV · Authority for definitions lies in conversation and code identifiers; a glossary is never authoritative.  
  "The living conversation + the code's identifiers; a glossary is derivative, never authoritative"
- X:Q40 L47-47 · CLM · The existence of the Living Glossary pattern implies hand-maintained glossaries fail.  
  "(Living Glossary exists as a tacit admission hand-maintained ones fail — B)."
- Y:Q33 L47-47 · CLM · Definitional authority = conversation + code identifiers.  
  "a glossary is derivative, never authoritative"

## G31 · L48-48
- X:Q41 L48-48 · CLM · Definitions are validated conversationally; term drift signals a context boundary rather than decay.  
  "CONVERSATIONAL — drift is heard as awkwardness; term drift is a *boundary signal*, not mere decay (B)."
- Y:Q34 L48-48 · CLM · Definitional validation conversational; drift signals a boundary.  
  "CONVERSATIONAL — drift is heard as awkwardness; term drift is a *boundary signal*, not mere decay"

## G32 · L49-49
- X:Q42 L49-49 · CLM · Definitional knowledge evolves continuously and rots about two years after the owning conversation stops.  
  "Continuous evolution; splinters when contexts blur; **rots when the owning conversation stops** (~2 years after owner departure)."
- Y:Q35 L49-49 · CLM · Definitional lifecycle.  
  "**rots when the owning conversation stops** (~2 years after owner departure)"

## G33 · L50-50
- X:Q43 L50-50 · REL · Terms retire with their context; historical meanings survive in decision records.  
  "Terms retire when their context retires; historical meanings preserved in decision records that used them."
- Y:Q36 L50-50 · CLM · Definitional retirement.  
  "Terms retire when their context retires"

## G34 · L51-51
- X:Q44 L51-51 · CLM · Definitional freshness equals conversation cadence and cannot be measured from the artifact.  
  "Freshness = conversation cadence; unmeasurable from the artifact alone."
- Y:Q37 L51-51 · CLM · Definitional freshness.  
  "Freshness = conversation cadence; unmeasurable from the artifact alone."

## G35 · L52-52
- X:Q45 L52-52 · CLM · Definitions are found in code and people; glossaries have weak scent unless code-generated.  
  "In the code (identifiers) and in people; glossaries have weak scent unless regenerated from code."
- Y:Q38 L52-52 · CLM · Definitional discoverability.  
  "In the code (identifiers) and in people"

## G36 · L53-53
- X:Q46 L53-53 · CLM · The platform partially implements definitional knowledge.  
  "partially implemented (Phase-02.6 vocabulary, OI-1 renames-by-supersession)"
- X:Q47 L53-53 · ASM · Conversational validation assumes human engineers; this is challenged when engineers are AI.  
  "the conversational-validation assumption is challenged when engineers are AI (open question §3)."
- Y:Q39 L53-53 · CLM · Platform partially implements; AI challenges conversational validation.  
  "the conversational-validation assumption is challenged when engineers are AI"

## G37 · L54-54
- X:Q48 L54-54 · REL · Definitional knowledge is anchored in B (NKM-01/02, Özkan) and the term-bound-to-context unit.  
  "B (NKM-01/02; Özkan SLR validation vacuum) · smallest-unit answer (term-bound-to-context)."
- Y:Q40 L54-54 · CLM · Evidence pointers for Definitional.  
  "smallest-unit answer (term-bound-to-context)"

## G38 · L57-57
- X:Q49 L57-57 · DEF · Descriptive Knowledge is prose describing current state, with runbooks as an operational subtype.  
  "Prose describing current state: guides, READMEs, views, runbooks/procedures (operational subtype)."
- Y:Q41 L57-57 · DEF · Type 4 Descriptive.  
  "Prose describing current state: guides, READMEs, views, runbooks/procedures (operational subtype)."

## G39 · L58-58
- X:Q50 L58-58 · CLM · Descriptive knowledge survives only when producers and consumers are the same population.  
  "Survives ONLY where these are the same population (docs-as-code)"
- X:Q51 L58-58 · CLM · Second-population descriptive variants such as wikis decay, the best-evidenced failure.  
  "second-population variants (wikis) decay to graveyard — the best-evidenced failure in the corpus."
- Y:Q42 L58-58 · CLM · Descriptive survival condition.  
  "Survives ONLY where these are the same population (docs-as-code)"

## G40 · L59-59
- X:Q52 L59-59 · DEF · Descriptive authority comes from ownership, freshness date and review-with-code.  
  "Ownership + freshness date + review-with-code (the Google model)"
- X:Q53 L59-59 · INV · Ownerless descriptive content has no authority.  
  "ownerless descriptive content is definitionally non-authoritative."
- Y:Q43 L59-59 · CLM · Descriptive authority = owner + freshness + review.  
  "ownerless descriptive content is definitionally non-authoritative"

## G41 · L60-60
- X:Q54 L60-60 · CLM · Descriptive knowledge is validated by code-coupled review and regeneration.  
  "Review coupled to code change; regeneration from live sources where possible"
- X:Q55 L60-60 · CLM · Automation moves staleness to the source model if that model is unmaintained.  
  "noting automation *relocates* staleness if the source model is unmaintained (B/C5-auto)."
- Y:Q44 L60-60 · CLM · Descriptive validation caveat.  
  "automation *relocates* staleness if the source model is unmaintained"

## G42 · L61-61
- X:Q56 L61-61 · CLM · Descriptive knowledge follows regime 2 and becomes stale within 30–90 days without coupling.  
  "EVOLVES/REGENERATES — regime 2 of PK-P5; materially stale within 30–90 days without coupling (D)."
- Y:Q45 L61-61 · CLM · Descriptive lifecycle.  
  "EVOLVES/REGENERATES — regime 2 of PK-P5; materially stale within 30–90 days without coupling"

## G43 · L62-62
- X:Q57 L62-62 · INV · Descriptive knowledge retires via staged ROT disposal, never silent abandonment.  
  "Staged ROT disposal: merge+redirect / update-or-retire / archive — never silent abandonment."
- Y:Q46 L62-62 · CLM · Descriptive retirement.  
  "Staged ROT disposal: merge+redirect / update-or-retire / archive — never silent abandonment."

## G44 · L63-63
- X:Q58 L63-63 · CLM · Descriptive freshness is an owner plus a last-verified stamp scaled to domain half-life.  
  "Owner + last-verified stamp, priced to the domain half-life"
- X:Q59 L63-63 · CLM · A last-edited date is only a proxy, not a measure of validity.  
  "is a proxy, not validity (A)."
- Y:Q47 L63-63 · CLM · Descriptive freshness (owner + verified stamp).  
  ""last edited" is a proxy, not validity"

## G45 · L64-64
- X:Q60 L64-64 · CLM · Descriptive discoverability depends most on scent.  
  "Scent-dependent (labels, summaries, addressability) — the type where foraging theory bites hardest."
- Y:Q48 L64-64 · CLM · Descriptive discoverability depends on scent.  
  "the type where foraging theory bites hardest"

## G46 · L65-65
- X:Q61 L65-65 · CLM · Runbooks are validated by use.  
  "runbooks validate by USE (a followed runbook that works is validated; one that fails is challenged immediately)"
- X:Q62 L65-65 · CLM · Runbooks have the strongest consumption feedback of any prose.  
  "the strongest consumption-feedback loop of any prose form."
- Y:Q49 L65-65 · CLM · Operational subtype validated by use.  
  "runbooks validate by USE"

## G47 · L66-66
- X:Q63 L66-66 · CLM · The platform implements descriptive knowledge via DoD-coupled guides and READMEs and avoids wikis.  
  "implemented as developer guides (DoD-coupled = same gesture ✅) and READMEs; the wiki anti-pattern is structurally avoided."
- Y:Q50 L66-66 · CLM · Platform implements Descriptive as guides.  
  "the wiki anti-pattern is structurally avoided"

## G48 · L67-67
- X:Q64 L67-67 · REL · Descriptive knowledge is anchored in B, C and A.  
  "B (NKM-07/09/10/13; Google GooWiki 90%) · C (ROT, scent) · A (drift 28.9%/4.7yr)."
- Y:Q51 L67-67 · CLM · Evidence pointers for Descriptive.  
  "Google GooWiki 90%"

## G49 · L70-70
- X:Q65 L70-70 · DEF · Experiential/Research Knowledge is learning-produced claims, input-only until promoted.  
  "Findings, lessons, retrospective observations, research charters/NKMs — claims produced by learning, **input-only until promoted**."
- Y:Q52 L70-70 · DEF · Type 5 Experiential/Research.  
  "claims produced by learning, **input-only until promoted**"

## G50 · L71-71
- X:Q66 L71-71 · CLM · Experiential knowledge is produced post-experience and historically captured under mandate at project end.  
  "Learners/researchers post-experience or post-review; historically captured under mandate at the worst moment (project end — A/LLIS)."
- Y:Q53 L71-71 · CLM · Experiential producer.  
  "historically captured under mandate at the worst moment"

## G51 · L72-72
- X:Q67 L72-72 · INV · Experiential knowledge reaches deciders only via promotion, never as direct authority.  
  "Future deciders — via the promotion chain, never directly as authority."
- Y:Q54 L72-72 · CLM · Experiential consumers.  
  "via the promotion chain, never directly as authority"

## G52 · L73-73
- X:Q68 L73-73 · INV · Experiential claims have no intrinsic authority until they survive evaluation and are promoted.  
  "NONE intrinsically — an experiential claim gains authority only by surviving evaluation and being promoted"
- Y:Q55 L73-73 · CLM · Experiential authority none intrinsically.  
  "an experiential claim gains authority only by surviving evaluation and being promoted"

## G53 · L74-74
- X:Q69 L74-74 · CLM · Experiential knowledge is validated by claim evaluation at intake, a gate whose absence characterises lessons-learned failures.  
  "CLAIM EVALUATION at intake — the gate almost no system implements, whose absence is the lessons-learned failure signature (A)."
- Y:Q56 L74-74 · CLM · Experiential validation.  
  "CLAIM EVALUATION at intake — the gate almost no system implements"

## G54 · L75-75
- X:Q70 L75-75 · INV · Experiential records are append-only and retain falsified claims as negative knowledge.  
  "Append-only record; falsified claims retained as still-informative (negative knowledge prevents error repetition)."
- Y:Q57 L75-75 · CLM · Experiential lifecycle append-only; negative knowledge kept.  
  "falsified claims retained as still-informative"

## G55 · L76-76
- X:Q71 L76-76 · INV · Experiential knowledge is never edited, only superseded, with archives retrievable.  
  "Never edited; superseded by newer findings; archives remain retrievable."
- Y:Q58 L76-76 · CLM · Experiential retirement.  
  "Never edited; superseded by newer findings; archives remain retrievable."

## G56 · L77-77
- X:Q72 L77-77 · MATH · Born-stale risk dominates aging, so intake validation matters at least as much as review cadence.  
  "Two-sided — born-stale risk at intake (58.4%) dominates aging; intake validation ≥ review cadence."
- Y:Q59 L77-77 · CLM · Experiential freshness two-sided.  
  "born-stale risk at intake (58.4%) dominates aging"

## G57 · L78-78
- X:Q73 L78-78 · INV · Experiential knowledge must be indexed by future task rather than past project.  
  "The historical failure point (NASA: full repository, undiscoverable lessons); requires indexing by *future task*, not by past project."
- Y:Q60 L78-78 · CLM · Experiential discoverability.  
  "requires indexing by *future task*, not by past project"

## G58 · L79-79
- X:Q74 L79-79 · CLM · The platform implements experiential knowledge with the claim-evaluation gate.  
  "implemented and working — pattern cards, evidence register, retrospective inbox, this very RQ-002 chain are this type with the claim-evaluation gate the literature says is missing."
- Y:Q61 L79-79 · CLM · Platform implements Experiential with the gate.  
  "with the claim-evaluation gate the literature says is missing"

## G59 · L80-80
- X:Q75 L80-80 · REL · Experiential knowledge is anchored in A, C F3.3 and the platform's EPC discipline.  
  "A (NKM-14 knowledge claim; LLIS audits) · C (F3.3) · platform's own EPC discipline."
- Y:Q62 L80-80 · CLM · Evidence pointers for Experiential.  
  "A (NKM-14 knowledge claim; LLIS audits)"

## G60 · L83-83
- X:Q76 L83-83 · DEF · Qualification/Evidence Knowledge is records of instrument measurements.  
  "Records of what instruments measured — gate outputs, qualification verdicts, baselines, provenance chains."
- Y:Q63 L83-83 · DEF · Type 6 Qualification/Evidence.  
  "Records of what instruments measured"

## G61 · L84-84
- X:Q77 L84-84 · INV · Qualification knowledge is produced by instruments, never authored, per invariant 9.  
  "Instruments (measuring runs), never authors; recorded as produced, never asserted (I-9)."
- Y:Q64 L84-84 · CLM · Qualification producer.  
  "Instruments (measuring runs), never authors; recorded as produced, never asserted (I-9)."

## G62 · L85-85
- X:Q78 L85-85 · CLM · Its consumers are deciders, auditors, retrospectives and derived-only dashboards.  
  "Deciders, auditors, retrospectives, dashboards (derived-only)."
- Y:Q65 L85-85 · CLM · Qualification consumers.  
  "dashboards (derived-only)"

## G63 · L86-86
- X:Q79 L86-86 · DEF · Qualification authority is the instrument plus its recorded configuration, never editorial.  
  "The instrument + its recorded configuration (thread counts are part of measurement semantics — F-7D-2 as first-party evidence); authority is never editorial."
- Y:Q66 L86-86 · CLM · Qualification authority = instrument + configuration.  
  "authority is never editorial"

## G64 · L87-87
- X:Q80 L87-87 · CLM · For qualification knowledge, the run is the validation and revalidation is re-running.  
  "The run itself IS the validation; re-validation = re-run."
- Y:Q67 L87-87 · CLM · Qualification validation.  
  "The run itself IS the validation; re-validation = re-run."

## G65 · L88-88
- X:Q81 L88-88 · CLM · Qualification records are immutable and their verdict history is part of the verdict.  
  "IMMUTABLE record with verdict-history semantics (PASS AFTER CORRECTION — history is part of the verdict)."
- Y:Q68 L88-88 · CLM · Qualification lifecycle.  
  "IMMUTABLE record with verdict-history semantics"

## G66 · L89-89
- X:Q82 L89-89 · CLM · Measurements are superseded as baseline but remain true for their moment.  
  "Never retired as fact; superseded as *current baseline* by newer runs; the old measurement stays true of its moment."
- Y:Q69 L89-89 · CLM · Qualification retirement.  
  "the old measurement stays true of its moment"

## G67 · L90-90
- X:Q83 L90-90 · CLM · Qualification freshness is built-in via timestamps; staleness is referent drift detected by re-running.  
  "Timestamped by construction; staleness = referent drift, detected by re-running."
- Y:Q70 L90-90 · CLM · Qualification freshness.  
  "Timestamped by construction; staleness = referent drift, detected by re-running."

## G68 · L91-91
- X:Q84 L91-91 · CLM · Qualification knowledge is discovered through attachment to its referent and provenance chains.  
  "Attached to the thing qualified (report ↔ ticket ↔ commit); provenance chains make lineage traversable."
- Y:Q71 L91-91 · CLM · Qualification discoverability.  
  "provenance chains make lineage traversable"

## G69 · L92-92
- X:Q85 L92-92 · CLM · Qualification is fully implemented and the platform's most mature type.  
  "fully implemented (OQ records, merge-gate outputs, Infection baselines, append-only session evidence) — the platform's most mature type."
- Y:Q72 L92-92 · CLM · Platform fully implements Qualification.  
  "the platform's most mature type"

## G70 · L93-93
- X:Q86 L93-93 · REL · Qualification knowledge is anchored in C, B and platform practice.  
  "C (PROV, provenance-as-substrate) · B (verification-by-execution) · platform practice as corroborating first-party evidence."
- Y:Q73 L93-93 · CLM · Evidence pointers for Qualification.  
  "C (PROV, provenance-as-substrate)"

## G71 · L96-96
- X:Q87 L96-96 · DEF · Tacit Knowledge is mental models, skills and judgment, the actual operational store of every team.  
  "Mental models, skills, judgment — the actual operational knowledge store of every team (strongest-evidenced concept in the corpus)."
- Y:Q74 L96-96 · DEF · Type 7 Tacit.  
  "Mental models, skills, judgment — the actual operational knowledge store of every team"

## G72 · L97-97
- X:Q88 L97-97 · CLM · Tacit knowledge is produced involuntarily by practitioners while doing.  
  "Every practitioner, involuntarily, by doing."
- Y:Q75 L97-97 · CLM · Tacit producer.  
  "Every practitioner, involuntarily, by doing."

## G73 · L98-98
- X:Q89 L98-98 · CLM · Tacit knowledge is consumed via interruption and apprenticeship.  
  "Colleagues via interruption (the empirically preferred channel); successors via apprenticeship."
- Y:Q76 L98-98 · CLM · Tacit consumers.  
  "Colleagues via interruption (the empirically preferred channel)"

## G74 · L99-99
- X:Q90 L99-99 · DEF · Tacit authority is social recognition of expertise.  
  "Expertise recognition — social, unrecorded, real."
- Y:Q77 L99-99 · CLM · Tacit authority.  
  "Expertise recognition — social, unrecorded, real."

## G75 · L100-100
- X:Q91 L100-100 · CLM · Tacit knowledge is validated by use and peers and cannot be checked as an artifact.  
  "USE and peer conversation; not artifact-checkable."
- Y:Q78 L100-100 · CLM · Tacit validation.  
  "USE and peer conversation; not artifact-checkable."

## G76 · L101-101
- X:Q92 L101-101 · MATH · Tacit lifecycle sequence, ending in abrupt, fat-tailed loss at departure.  
  "Built by practice → strengthened by retrieval → decays with disuse → **lost abruptly at departure** (fat-tailed: 3–5x expected loss)."
- Y:Q79 L101-101 · CLM · Tacit lifecycle.  
  "**lost abruptly at departure** (fat-tailed: 3–5x expected loss)"

## G77 · L102-102
- X:Q93 L102-102 · CLM · No retirement model is possible for tacit knowledge; offboarding write-ups fail when needed (Robillard).  
  "None possible — it leaves with the person; offboarding"
- Y:Q80 L102-102 · CLM · Tacit retirement.  
  "None possible — it leaves with the person"

## G78 · L103-103
- X:Q94 L103-103 · CLM · Tacit freshness is self-refreshing while practiced, with truck factor as proxy.  
  "Self-refreshing while practiced; unmeasurable otherwise; the proxy is concentration risk (truck factor)."
- Y:Q81 L103-103 · CLM · Tacit freshness.  
  "the proxy is concentration risk (truck factor)"

## G79 · L104-104
- X:Q95 L104-104 · CLM · Social discovery does not scale; only succession mechanics are measured mitigation (~15–25%).  
  "which does not scale and dies with turnover; succession mechanics are the only measured mitigation (~15–25%)."
- Y:Q82 L104-104 · CLM · Tacit discoverability and mitigation.  
  "succession mechanics are the only measured mitigation (~15–25%)"

## G80 · L105-105
- X:Q96 L105-105 · INV · Tacit knowledge is deliberately not governed as artifact; the platform only measures and supports succession.  
  "deliberately UNGOVERNED as artifact (PK-P9); the platform's role is measurement (concentration risk) and succession support — never capture mandates."
- X:Q97 L105-105 · CLM · Session logs plus reconstruction are a partial, near-free rationale capture channel, matching the literature's direction.  
  "the platform's session logs + reconstruction discipline are a partial *rationale* capture channel at near-zero gesture cost"
- Y:Q83 L105-105 · CLM · Platform deliberately leaves tacit ungoverned.  
  "deliberately UNGOVERNED as artifact (PK-P9)"
- Y:Q84 L105-105 · CLM · Session logs as a partial rationale channel.  
  "the platform's session logs + reconstruction discipline are a partial *rationale* capture channel at near-zero gesture cost"

## G81 · L106-106
- X:Q98 L106-106 · REL · Tacit knowledge is anchored in B and A.  
  "B (NKM-12; LaToza; Ko; Rigby; Avelino) · A (NKM-1; retention bins)."
- Y:Q85 L106-106 · CLM · Evidence pointers for Tacit.  
  "B (NKM-12; LaToza; Ko; Rigby; Avelino)"

## G82 · L112-112
- X:Q99 L112-112 · REV · Architecture Knowledge is falsified as a type; it decomposes into Decision, Descriptive and Executable.  
  "Decomposes cleanly into Decision knowledge (ADRs/principles — immutable, superseded) + Descriptive knowledge (views — regenerate) + Executable (fitness rules)."
- X:Q100 L112-112 · DEF · Architecture is a content domain, not a behavioural type.  
  "names a *content domain*, not a behavioral type"
- X:Q101 L112-112 · CLM · The r=0.41 trust correlation of architecture docs lies between its constituent strata.  
  "(B: r=0.41 for architecture docs sits exactly between the strata of its constituents)."
- Y:Q86 L112-112 · REV · Architecture falsified as a type: it names a content domain, decomposes into Decision + Descriptive + Executable.  
  "**Architecture Knowledge** | ❌ FALSIFIED as a distinct type"

## G83 · L113-113
- X:Q102 L113-113 · REV · Business Rule Knowledge is merged: encoded rules are Executable, un-encoded ones Definitional or Descriptive.  
  "An encoded business rule is Executable knowledge (test/gate/policy-check); an un-encoded one is Definitional (a term/constraint in the language) or Descriptive prose"
- X:Q103 L113-113 · CLM · Un-encoded rules have lower authority; a rule's rationale is Decision knowledge; no separate behaviour exists.  
  "with correspondingly lower default authority (PK-P4). The rule's *decision* (why it exists) is Decision knowledge. No separate behavior found."
- Y:Q87 L113-113 · REV · Business rules merged (encoded → Executable; otherwise Definitional/Descriptive; why → Decision).  
  "**Business Rule Knowledge** | ⚠ MERGED"

## G84 · L114-114
- X:Q104 L114-114 · REV · Organizational Knowledge is falsified as an artifact-governable type; its residue lands in the seven types.  
  "Walsh & Ungson's bins (culture, routines, structures) are predominantly enacted/tacit; the artifact-governable residue lands in the seven types."
- X:Q105 L114-114 · CLM · Organizational knowledge is an environment rather than an artifact class.  
  "It is environment, not artifact class (A: repository metaphor contested; memory is enacted)."
- Y:Q88 L114-114 · REV · Organizational knowledge falsified as an artifact-governable type.  
  "It is environment, not artifact class"

## G85 · L115-115
- X:Q106 L115-115 · REV · Historical Knowledge is falsified as a type and reclassified as a status.  
  "❌ FALSIFIED as a type — **it is a STATUS**"
- X:Q107 L115-115 · CLM · Any instance becomes historical through supersession or archival; historical is a lifecycle position.  
  "Any type's instance becomes historical via supersession/archival"
- X:Q108 L115-115 · CLM · Treating historical as a type would violate PK-P2.  
  "Treating it as a type would re-break status-at-discovery (PK-P2)."
- Y:Q89 L115-115 · REV · Historical is a status.  
  "**Historical Knowledge** | ❌ FALSIFIED as a type — **it is a STATUS**"
- Y:Q90 L115-115 · CLM · Reason for Q89.  
  "Treating it as a type would re-break status-at-discovery (PK-P2)."

## G86 · L116-116
- X:Q109 L116-116 · CLM · Runtime Knowledge (observations, telemetry, logs) cannot be classified due to insufficient evidence.  
  "⏸ INSUFFICIENT EVIDENCE"
- X:Q110 L116-116 · REL · Runtime knowledge appears related to Qualification knowledge.  
  "Its provenance-bearing, instrument-produced character suggests kinship with Qualification/Evidence knowledge"
- X:Q111 L116-116 · INV · Runtime knowledge must not be classified without evidence; it is held as an open question.  
  "classifying it now would violate the no-unsupported-concepts constraint. → open question."
- Y:Q91 L116-116 · REV · Runtime knowledge: insufficient evidence, open question.  
  "classifying it now would violate the no-unsupported-concepts constraint"

## G87 · L117-117
- X:Q112 L117-117 · REV · Requirements are merged into Decision, Definitional and Executable knowledge.  
  "Behaves as Decision knowledge (what was agreed, immutable once accepted) + Definitional (terms) + Executable (when expressed as acceptance tests"
- X:Q113 L117-117 · CLM · Executable specifications have a failure history (12% versioned), and requirements show no distinct behaviour.  
  "with the executable-specification failure history noted: 12% versioned, business indifference). No distinct behavior evidenced."
- Y:Q92 L117-117 · REV · Requirements merged into Decision + Definitional + Executable.  
  "**Requirements** | ⚠ MERGED (per evidence available)"

## G88 · L121-129
- X:Q114 L121-129 · MATH · A classification matrix gives every type a profile across seven governance axes plus platform status; its rows restate §1 (validation, regime, retirement, authority, freshness, discovery, platform status per type).  
  "| Type | Validation | Lifecycle regime | Retirement | Authority | Freshness | Discovery | Platform status |"

## G89 · L131-131
- X:Q115 L131-131 · CLM · Because no two types share a profile, the family thesis is confirmed.  
  "no two rows share a full profile — the family thesis holds."
- X:Q116 L131-131 · INV · The invariants apply to all types while governance machinery must differ per type.  
  "The constitutional core (the nine invariants) applies to all rows; the *governance machinery* must differ per row."
- X:Q117 L131-131 · CLM · The platform implements six of seven types for engineering knowledge.  
  "The Engineering Platform already implements or partially implements six of seven rows **for engineering knowledge**"
- X:Q118 L131-131 · CLM · This strengthens, but does not decide, the question of the platform being a specialization of a general knowledge domain.  
  "strengthening, without deciding, the flagged question of whether it is one specialization of a general knowledge domain."
- Y:Q93 L131-131 · CLM · [labelled INTERPRETATION in the source] Family thesis.  
  "no two rows share a full profile — the family thesis holds."
- Y:Q94 L131-131 · CLM · Platform covers six of seven types for engineering knowledge.  
  "The Engineering Platform already implements or partially implements six of seven rows **for engineering knowledge**"

## G90 · L135-135
- X:Q119 L135-135 · CLM · Open question: runtime kinship with qualification needs first-party evidence, which EPIC-002 is expected to supply.  
  "Runtime/telemetry knowledge: kinship with Qualification knowledge needs first-party evidence"
- X:Q120 L135-135 · REL · EPIC-002 and RQ-002 research threads converge on this question.  
  "convergence of the two research threads noted"
- Y:Q95 L135-135 · CLM · Open question 1.  
  "Runtime/telemetry knowledge: kinship with Qualification knowledge needs first-party evidence"

## G91 · L136-136
- X:Q121 L136-136 · CLM · Open question: whether conversational validation has an agent-compatible analogue.  
  "Definitional validation when engineers are AI: does conversational validation have an agent-compatible analogue? — OPEN (inherited from the Synthesis)"
- Y:Q96 L136-136 · CLM · Open question 2.  
  "Definitional validation when engineers are AI: does conversational validation have an agent-compatible analogue?"

## G92 · L137-137
- X:Q122 L137-137 · CLM · Open question: which regime governs which section of mixed decision/description artifacts.  
  "Boundary artifacts (IDDs: part decision, part description) — which regime governs which section? — OPEN (inherited)"
- Y:Q97 L137-137 · CLM · Open question 3.  
  "Boundary artifacts (IDDs: part decision, part description) — which regime governs which section?"

## G93 · L141-141
- X:Q123 L141-141 · CLM · Success criteria: 7 types identified with per-type properties defined.  
  "every distinct evidenced type identified (7) · per-type lifecycle/authority/validation/retirement defined (§1, §3)"
- X:Q124 L141-141 · CLM · The family-vs-domain question is answered: a family with a constitutional core.  
  "the one-domain-or-family question answered: **a family with a constitutional core**"
- X:Q125 L141-141 · CLM · Candidates were genuinely tested (counted here as 4 falsified/merged, 1 insufficient evidence).  
  "Candidates were genuinely falsified (4 falsified/merged, 1 insufficient-evidence — the taxonomy earned its shape rather than inheriting the candidate list)."
- X:Q126 L141-141 · INV · Constraints are honored, and no Reference Architecture may be written before ARB review.  
  "Constraints honored: no folders, no templates, no design, no new research. **STOP — awaiting ARB review before any Reference Architecture is written.**"
- Y:Q98 L141-141 · CLM · Success criterion answered.  
  "the one-domain-or-family question answered: **a family with a constitutional core**"
- Y:Q99 L141-141 · CLM · Count of falsified/merged candidates (inconsistent with Q5 and the table, see Uncertainties).  
  "4 falsified/merged, 1 insufficient-evidence"

