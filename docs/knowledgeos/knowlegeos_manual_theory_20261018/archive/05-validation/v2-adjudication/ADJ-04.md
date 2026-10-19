# ADJ-04 — Adjudication of WORKSHEET-04

Source: docs/implementation/RQ-002_Project_Knowledge_Domain.md (read in full, first). Worksheet: WORKSHEET-04.md (read in full).
Unit: proposition. A Y item is credited with a proposition when its meaning sentence (read with its verbatim quote) states it faithfully. Every X item (95) and every Y item (47) is mapped to at least one proposition.

## 1. Proposition table

```tsv
prop_id	lines	x_items	y_items	captured_by	class	reader_at_fault	type	foundational	note
R01	3	Q1	Q1	BOTH	MATCH	none	DEF	no	Document is a research charter, not ADR/IDD/design
R02	3	Q2	-	X	OMISSION	Y	DEF	no	Constitution of a research phase defining question, sub-questions, boundaries; Y quote spans it but meaning states only "research charter, not a design"
R03	3	Q3	Q1	BOTH	SCOPE	none	INV	no	Decides nothing about the solution; X separate INV, Y folded into "not a design"
R04	4	Q4	Q2	BOTH	MATCH	none	CLM	no	Approved with amendments (ARB 2026-07-11), literature review authorized
R05	4	Q5	Q3	BOTH	MATCH	none	REV	no	Naming history: Management/Architecture/System set aside with reasons; Y renders as a sequence, defensible
R06	4	Q6	Q3	BOTH	MATCH	none	CLM	no	"Domain" biases nothing; research discovers whether a system exists
R07	4	Q6	-	X	OMISSION	Y	CLM	no	RQ-002 is the durable id
R08	5	Q7	Q4	BOTH	MATCH	none	REL	no	Form modeled on EPIC-002 problem statement (authoritative form, ER-09 §2)
R09	6	Q8	Q5	BOTH	SCOPE	none	INV	yes	Binding role instruction: researcher not architect; Y types it ASM though the source says "binding" (type choice, meaning faithful)
R10	6	Q9	Q5	BOTH	MATCH	none	DEF	no	Researcher asks "what exists?", architect "what should we build?"
R11	6	Q10	-	X	OMISSION	Y	INV	no	Role persists until ARB authorizes design
R12	8	Q11	Q6	BOTH	MATCH	none	INV	yes	Litmus: would the finding exist if Markdown were removed?
R13	8	Q12	Q6	BOTH	MATCH	none	DEF	yes	No -> documentation design (out of scope); yes -> knowledge architecture (in scope)
R14	8	Q13	Q7	BOTH	SCOPE	none	INV	no	"Design knowledge. Not documentation." X INV vs Y CLM/slogan
R15	12	Q14	-	X	OMISSION	Y	REV	no	Research question broadened at ARB review: knowledge dynamic not static
R16	14	Q15	Q8	BOTH	MATCH	none	CLM	yes	Research question with eight lifecycle verbs
R17	16	Q16	Q9	BOTH	MATCH	none	REL	no	Static "what kinds exist" contained; domain is a living system with events
R18	16	Q17	-	X	OMISSION	Y	CLM	yes	Knowledge deliberately superseded, invalidated, forked; two truths coexist; forgotten knowledge rediscovered
R19	16	Q18	Q9	BOTH	SCOPE	none	CLM	yes	Entropy/obsolescence/conflict/forgetting/rediscovery are first-class concepts; X types ASM ("assumed"), source asserts it flatly; type choice only
R20	16	Q19	Q10	BOTH	SCOPE	none	INV	yes	Taxonomy adequacy criterion (must express decay and contradiction); X INV vs Y CLM
R21	18	Q20	Q11	BOTH	MATCH	none	CLM	yes	Mandatory granularity question: smallest unit of project knowledge
R22	18	Q21	Q11	BOTH	MATCH	none	REL	no	Granularity = aggregate-root question asked of knowledge (candidate units listed by X only)
R23	18	Q22	Q12	BOTH	SCOPE	none	REL	yes	Every other structure rests on granularity; X ASM vs Y REL
R24	22	Q23	Q13	BOTH	MATCH	none	CLM	no	Retrospective demonstrated insufficiency (O-10/P-6 duplication, O-8 drift)
R25	22	Q24	-	X	OMISSION	Y	CLM	no	Project knowledge exists but its architecture is implicit
R26	23	Q25	Q14	BOTH	MATCH	none	INV	yes	Incumbent EKP must be studied, not bypassed
R27	23	Q26	-	X	OMISSION	Y	DEF	no	EKP components (frozen Constitution, lifecycle, cards, ai/ quarantine, tooling, Adjudication pilot)
R28	23	Q27	-	X	OMISSION	Y	REL	no	EKP purpose statement near-verbatim this research question
R29	23	Q28	Q14	BOTH	MATCH	none	CLM	no	Sharpest evidence question: was the EKP actually used in PB-004..007 sessions
R30	24	Q29	Q15	BOTH	MATCH	none	CLM	no	Context-assembly gap: engineering context injected, project context assembled ad hoc
R31	25	Q30	Q16	BOTH	MATCH	none	REV	no	ER-09 diagnosed one level too low; durable question is "what is project knowledge?"
R32	25	Q31	Q16	BOTH	MATCH	none	CLM	no	ER-09 remains PROPOSED and paused pending this research
R33	29	Q32	Q17	BOTH	SCOPE	none	ASM	yes	"Project Knowledge Platform" is a hypothesis, not a design; X ASM vs Y CLM
R34	29	Q33	Q18	BOTH	MATCH	none	CLM	yes	Four admissible outcomes (new platform / EKP capability / absorbed rules / deferred)
R35	29	Q34	Q18	BOTH	SCOPE	none	INV	yes	Discovery must not assume its own answer; X INV vs Y ASM ("none presumed")
R36	33	Q35,Q36	Q19	BOTH	SCOPE	none	ASM	yes	Sub-q1 taxonomy: seven-kind candidate list to validate or falsify, completeness/right cuts; X splits in two
R37	34	Q37	Q20	BOTH	MATCH	none	ASM	yes	Sub-q2 lifecycle; EKP assumes one shared lifecycle (attributed to EKP by X, correctly)
R38	35	Q38	Q21	BOTH	MATCH	none	CLM	no	Sub-q3 discovery and context assembly ("just enough")
R39	35	Q39	-	X	OMISSION	Y	MATH	no	Pipeline need -> discover -> assemble -> detect contradictions -> working context; source gives it as a parenthetical inside an open question, X's "is modeled as" slightly firmer than source
R40	36	Q40	Q22	BOTH	MATCH	none	CLM	no	Sub-q4 qualification via evidence strength, source diversity, recency
R41	36	Q41	-	X	OMISSION	Y	CLM	no	Correctness-over-time problem attacked by no current tooling
R42	37	Q42	Q23	BOTH	MATCH	none	CLM	no	Sub-q5 boundary project vs engineering knowledge
R43	38	Q43	Q24	BOTH	MATCH	none	CLM	no	Sub-q6 ownership own/observe/never touch vs Engineering Platform, Product, Runtime
R44	39	Q44	Q25	BOTH	MATCH	none	REV	no	Knowledge Quality added at ARB review as own thread
R45	39	Q45	Q25	BOTH	MATCH	none	CLM	no	Sub-q7 content: authority, override, contradiction coexistence, obsolete searchable, canonical vs historical, trust
R46	43	Q46	Q26	BOTH	MATCH	none	REL	yes	Inherits EPIC-002 research method
R47	43	Q47	-	X	OMISSION	Y	DEF	no	Components of inherited method (quality gates, evidence classification, negative findings, promotion, time-box, impl-as-research)
R48	43	Q48	Q26	BOTH	MATCH	none	REV	no	One amendment: deepened Normalized Knowledge Model per concept
R49	46-47	Q49	Q27	BOTH	SCOPE	none	DEF	yes	NKM ten fields; X MATH vs Y DEF
R50	50	Q50	Q28	BOTH	MATCH	none	CLM	no	Recorded observation: second use evidences promotion trigger
R51	50	Q51	Q28	BOTH	MATCH	none	INV	no	Promotion is an ARB act, queued, not executed
R52	54	Q52	Q29	BOTH	MATCH	none	INV	no	Executor acts as senior researcher in five fields, not architect
R53	56	Q53	Q30	BOTH	MATCH	none	INV	no	Task: discover universal principles, not design the platform
R54	56	Q54	-	X	OMISSION	Y	REL	yes	Engineering Platform governs HOW engineering happens; research is WHAT project knowledge is (boundary underlying sub-q5/sub-q6)
R55	58	Q55	Q31	BOTH	MATCH	none	CLM	no	Ten research objectives
R56	60	Q56	Q32	BOTH	MATCH	none	INV	no	NKM for every discovered concept
R57	62	Q57	Q33	BOTH	MATCH	none	INV	no	Prohibitions: no architecture, folders, templates, software, platform proposals
R58	62	Q58	Q33	BOTH	MATCH	none	DEF	no	Deliverable is a normalized body of knowledge
R59	62	Q59	-	X	OMISSION	Y	INV	no	Architectural synthesis only after literature review complete
R60	64	Q60	-	X	OMISSION	Y	REV	no	Literature scope broadened at ARB review; heading says "six disciplines" (table then lists seven: internal source tension, X faithfully reports heading)
R61	68-73	Q61,Q62,Q63,Q64,Q65,Q66	Q34	BOTH	SCOPE	none	DEF	no	Six base disciplines and topics; X one item per row, Y one list item (names only)
R62	74	Q67	Q34,Q35	BOTH	MATCH	none	REV	no	Information quality added as 7th discipline at ARB review
R63	76	Q68	Q36	BOTH	MATCH	none	CLM	yes	Purpose: validate or falsify candidate taxonomy, EKP assumptions, second-platform hypothesis
R64	76	Q69	Q36	BOTH	MATCH	none	INV	no	Never copy systems; successful and failed KM systems mandatory
R65	77	Q70	Q37	BOTH	MATCH	none	CLM	no	Framing observation (hedged "may"): closer to Knowledge Engineering
R66	79	Q79	Q38	BOTH	MATCH	none	INV	no	Deliverables are research artifacts only, in stated order
R67	81	Q71	Q38	BOTH	SCOPE	none	CLM	no	Deliverable 1 literature review with gap analysis vs EKP; Y aggregates all eight
R68	82	Q72	Q38	BOTH	SCOPE	none	CLM	no	Deliverable 2 candidate ubiquitous language
R69	83	Q73	Q39	BOTH	MATCH	none	DEF	no	Deliverable 3 Normalized Knowledge Ontology (added at ARB review, seven fields)
R70	84	Q74	Q38	BOTH	SCOPE	none	CLM	no	Deliverable 4 ownership map
R71	85	Q75	Q38	BOTH	SCOPE	none	CLM	no	Deliverable 5 context map
R72	86	Q76	Q38	BOTH	SCOPE	none	CLM	no	Deliverable 6 open questions register
R73	87	Q77	Q38	BOTH	SCOPE	none	INV	no	Deliverable 7 recommendation with evidence
R74	88	Q78	Q38	BOTH	MATCH	none	INV	yes	STOP -> ARB decides whether evidence authorizes design
R75	90	Q80	-	X	OMISSION	Y	INV	no	ARB warning: must not become a six-month project (Y's "time-boxed" at L92 is a different statement)
R76	92	Q81	Q40	BOTH	MATCH	none	INV	no	Exit criteria (six) then STOP
R77	92	Q82	Q40,Q41	BOTH	MATCH	none	CLM	no	Time-boxed; provisional conclusions with marked uncertainty beat unbounded search
R78	92	Q83	-	X	OMISSION	Y	INV	no	Only after the STOP may architecture begin
R79	96	Q84	Q42	BOTH	MATCH	none	INV	no	Not produced: templates, folders, implementation rules, aggregates/BC designs
R80	96	Q85	Q42	BOTH	MATCH	none	INV	no	No change to engineering/ (R-37), EKP; no ER-09 finalization
R81	98	Q86	-	X	OMISSION	Y	REV	no	Success criterion elevated at ARB review: domain model, not verdict
R82	100	Q87	Q43	BOTH	MATCH	none	INV	yes	Primary success: ubiquitous language independent of format, tooling, AI provider, project
R83	100	Q88	Q44	BOTH	MATCH	none	CLM	yes	First deliverable is a domain model of knowledge itself
R84	100	Q89	-	X	OMISSION	Y	REL	no	Later architectures become implementations of the domain, not starting points
R85	102	Q90	Q45	BOTH	MATCH	none	INV	no	Secondary criterion: confirm or falsify three going-in assumptions
R86	102	Q91	Q45	BOTH	MATCH	none	INV	no	Merely restating assumptions = failure (Y via verbatim quote)
R87	106	Q92	Q46	BOTH	MATCH	none	REL	no	EPIC-002 and RQ-002 independent parallel threads sharing one method
R88	106	Q93	Q46	BOTH	MATCH	none	DEF	no	EPIC-002 product domain (Evidence); RQ-002 meta domain (Y via verbatim quote)
R89	106	Q94	Q46	BOTH	MATCH	none	INV	no	Neither blocks; EPIC-002 has scheduling priority (AIP-14)
R90	109	Q95	Q47	BOTH	SCOPE	none	REL	no	Traceability; X lists all five links, Y only the ARB elevation review
```

## 2. Totals

| Measure | All | Foundational |
|---|---|---|
| Propositions | 90 | 22 |
| Captured by both (m) | 72 | 20 |
| X total (n_X) | 90 | 22 |
| Y total (n_Y) | 72 | 20 |
| X only | 18 | 2 |
| Y only | 0 | 0 |
| NEITHER | 0 | 0 |

Per class:

| Class | All | Foundational |
|---|---|---|
| MATCH | 54 | 12 |
| SCOPE | 18 | 8 |
| OMISSION | 18 (all Y) | 2 (Y) |
| MISREADING | 0 | 0 |
| LABEL-ERROR | 0 | 0 |
| BOTH-DEFENSIBLE | 0 | 0 |
| SPURIOUS | 0 | 0 |
| NEITHER | 0 | 0 |

Per reader_at_fault: none 72 · Y 18 · X 0 · both 0. Foundational: none 20 · Y 2 · X 0 · both 0.

## 3. Most consequential disagreements

No MISREADING, LABEL-ERROR, SPURIOUS or NEITHER propositions found. The consequential items are the two foundational omissions, plus minor notes:

1. **R18, L16 (foundational OMISSION, Y).** "knowledge is not merely lost to decay — it is deliberately superseded, invalidated, forked; two truths coexist; forgotten knowledge is rediscovered". These are the core domain events the research question is about. Y keeps only the abstract list of first-class concepts (R19).
2. **R54, L56 (foundational OMISSION, Y).** "The Engineering Platform already governs HOW engineering happens. We are researching WHAT project knowledge actually is." This boundary underlies sub-questions 5 and 6. Y omits it.
3. **Non-foundational Y omissions that carry gating or scope rules:** R11 (L6, the role persists until the ARB authorizes design), R59 (L62, synthesis only after the literature review), R78 (L92, architecture only after the STOP), R75 (L90, the six-month warning), R81 (L98, the success criterion elevated to a domain model), R84 (L100, later architectures implement the domain). Y kept the STOP point (R74) and the prohibitions, so these gates are weakened rather than lost.
4. **Type-status notes, not errors.** Y:Q5 types the explicitly "binding" role instruction as ASM (R09). X:Q18 renders a flat assertion as "assumed" (R19). X:Q39 presents the context-assembly pipeline as a model ("is modeled as") when the source gives it as a parenthetical inside an open sub-question (R39). In each case the meaning is still faithful, so none rises to LABEL-ERROR.
5. **Source-internal tensions that neither reader flagged as a tension (no reader fault).** The L64 heading says "six disciplines" while the table at L68-74 lists seven; X:Q60 reports the heading faithfully. L100 says "The first deliverable is a domain model of knowledge itself", while the ordered deliverables at L81 put the literature review first.
