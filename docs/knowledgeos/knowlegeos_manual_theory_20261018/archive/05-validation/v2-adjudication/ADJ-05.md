# ADJ-05 — Adjudication of worksheet 05

source: docs/implementation/RQ-002_Strategic_Discovery_Report.md (117 lines) · X = 73 items · Y = 111 items · unit = proposition.

Conventions: SCOPE is used where both readers are faithful but differ in item boundaries (one item vs several) or in type choice (e.g. DEF vs MATH, REL vs INV, CLM vs REV). Ontology nodes (§3, L51-72) are labelled MATH by Y. They are definitional tree nodes, not formal relations, but the meanings are faithful, so they are SCOPE. The one genuine formula inside them (L57 canonical predicate) is its own proposition.

## 1. Proposition table

```tsv
prop_id	lines	x_items	y_items	captured_by	class	reader_at_fault	type	foundational	note
P001	3	-	Y:Q1	Y	OMISSION	X	DEF	no	Document kind: research synthesis, NOT architecture, NOT design
P002	3	X:Q1	Y:Q2	BOTH	MATCH	none	CLM	no	Evidence base consumed (4 executors, 7 disciplines, ~120 sources, 55 NKMs, 33 neg., 35 contradictions)
P003	4	X:Q2	Y:Q3	BOTH	MATCH	none	INV	no	Status COMPLETE; STOP, no architecture until ARB rules on §7 (CLM vs INV type difference immaterial)
P004	5	X:Q3	Y:Q4	BOTH	MATCH	none	DEF	yes	Four finding labels FACT/INTERPRETATION/RECOMMENDATION/OPEN QUESTION
P005	13	X:Q4	Y:Q5	BOTH	MATCH	none	CLM	yes	FACT 1 same-population law
P006	13	X:Q5	Y:Q6	BOTH	MATCH	none	CLM	yes	Second population or separate gesture leads to decay-to-graveyard lifecycle
P007	13	X:Q5	Y:Q7,Y:Q8	BOTH	SCOPE	none	CLM	no	Decay examples; X lists classes without figures, Y carries figures (GooWiki ~90%, LLIS, ~2 yrs, ADR ~50%)
P008	14	X:Q6	Y:Q9	BOTH	MATCH	none	CLM	yes	FACT 2 validity status invisible at discovery = master failure signature
P009	14	-	Y:Q10	Y	OMISSION	X	CLM	no	NASA/SO/wiki/RAG failures reduce to: consumer cannot distinguish canonical/historical/wrong at retrieval
P010	14	X:Q7	Y:Q11	BOTH	MATCH	none	CLM	yes	Born stale (58.4% of obsolete SO answers obsolete at posting)
P011	14	X:Q7	Y:Q12	BOTH	SCOPE	none	CLM	no	Intake validation matters at least as much as maintenance cadence; X folds into Q7
P012	15	X:Q8	Y:Q13	BOTH	SCOPE	none	CLM	no	Smallest unit converged four ways
P013	15	-	Y:Q14,Y:Q15	Y	OMISSION	X	DEF	no	Per-discipline units (nanopublication, knowledge claim, smallest span true alone, decision-or-definition in context); Y:Q15 quote is only "AI/CogSci:" but meaning is faithful
P014	15	X:Q8	Y:Q16	BOTH	MATCH	none	DEF	yes	Composite atomic unit: context-bound, provenance-carrying, status-bearing assertion co-located with what it governs
P015	15	X:Q9	Y:Q17	BOTH	MATCH	none	CLM	yes	Corollary: page/document governance cannot detect partial staleness
P016	16	X:Q10	Y:Q18	BOTH	MATCH	none	CLM	yes	FACT 4 trust stratified by proximity to execution; validation-by-execution the only proven mechanism
P017	16	-	Y:Q19	Y	OMISSION	X	MATH	no	Accuracy-consultation correlation r=0.67 (testing docs) to r=0.03 (specs)
P018	16	X:Q11	Y:Q20	BOTH	SCOPE	none	MATH	yes	De facto trust hierarchy ordering (X REL, Y MATH)
P019	16	X:Q12	Y:Q21	BOTH	MATCH	none	CLM	no	Trust asymmetric and stateful; burned once, consumers reroute, maintenance collapses
P020	17	X:Q13	Y:Q22	BOTH	MATCH	none	CLM	yes	FACT 5 rationale most demanded, least captured, hardest to validate
P021	17	-	Y:Q23	Y	OMISSION	X	CLM	no	Rationale neither executes nor arises in daily conversation
P022	17	-	Y:Q24	Y	OMISSION	X	CLM	no	Rationale lives in mental models; lost fat-tailed via turnover (65% truck factor <=2)
P023	17	X:Q13	Y:Q25	BOTH	MATCH	none	CLM	no	Only measured mitigations are succession mechanics, not repositories
P024	21	X:Q14	Y:Q26	BOTH	MATCH	none	CLM	no	Created conversationally and by doing; externalization window at decision time or never
P025	21	X:Q14	Y:Q27	BOTH	SCOPE	none	CLM	no	SECI lacks empirical grounding; X omits that the knowledge-leak diagnosis is independently confirmed
P026	22	X:Q15	Y:Q28	BOTH	MATCH	none	CLM	yes	Four validation routes: execution, conversation, claim evaluation, use
P027	22	X:Q15	Y:Q29	BOTH	SCOPE	none	CLM	no	No consumption feedback loop means quality cannot be maintained (X only via quote)
P028	23	X:Q16	Y:Q30	BOTH	MATCH	none	CLM	yes	Two evolution regimes: decisions immutable + superseded by link; descriptions evolve/regenerate
P029	23	-	Y:Q31	Y	OMISSION	X	INV	yes	Regime membership is a first-class property of an artifact (underpins OQ3, L94)
P030	24	X:Q17	Y:Q32	BOTH	MATCH	none	CLM	no	Discovery by foraging; scent is a label property; scentless knowledge functionally nonexistent
P031	24	-	Y:Q33	Y	OMISSION	X	CLM	no	Discovery in-flow, opportunistic, satisficing; people preferred over documents
P032	24	X:Q17	Y:Q34	BOTH	SCOPE	none	CLM	no	For AI minimal-sufficient context beats volume; X omits context rot (18 models) and agentic search displacing indexes
P033	25	X:Q18	Y:Q35	BOTH	MATCH	none	CLM	no	Consumed in fragments at moment of need, never as wholes
P034	25	-	Y:Q36	Y	OMISSION	X	CLM	no	Packaging governed by cognitive load; LLM-generated instruction files net-negative for duplicating the derivable
P035	26	X:Q19	Y:Q37,Y:Q38	BOTH	SCOPE	none	INV	no	Contradictions coexist under explicit status at rest, resolve at consumption, demote by supersession, never silent edit
P036	27	X:Q20	Y:Q39	BOTH	MATCH	none	DEF	no	Forgetting: loss vs obsolescence, different remedies
P037	27	-	Y:Q40	Y	OMISSION	X	CLM	no	Depreciation domain-relative (17%/week to 3%/month)
P038	27	-	Y:Q41	Y	OMISSION	X	CLM	no	Deliberate retirement least-designed stage; default = silent abandonment
P039	28	X:Q21	Y:Q42	BOTH	MATCH	none	INV	yes	Retirement = status transition, never deletion (archive out of navigation, in history)
P040	28	X:Q21	Y:Q43	BOTH	SCOPE	none	CLM	no	Unretired stale knowledge actively harmful (newcomer dropout, poisons AI grounding)
P041	30	-	-	NEITHER	NEITHER	both	DEF	no	Section status label: "Gap analysis: the EKP incumbent against the evidence — INTERPRETATION". Both readers record the L34-40 verdicts as plain CLM/REV without carrying this label
P042	34	X:Q22	Y:Q44	BOTH	MATCH	none	CLM	no	EKP metadata aligned (INTERPRETATION per L30; see P041)
P043	35	X:Q23	Y:Q45	BOTH	MATCH	none	CLM	no	knowledge-lint aligned (INTERPRETATION per L30)
P044	36	X:Q24	Y:Q46	BOTH	MATCH	none	CLM	no	ai/ quarantine aligned (INTERPRETATION per L30)
P045	37	X:Q25	Y:Q47	BOTH	MATCH	none	CLM	no	Central store at risk (INTERPRETATION per L30)
P046	38	X:Q26	Y:Q48	BOTH	MATCH	none	REV	no	Single lifecycle falsified (INTERPRETATION per L30)
P047	39	X:Q27	Y:Q49	BOTH	MATCH	none	CLM	no	Card granularity challenged (INTERPRETATION per L30)
P048	40	X:Q28	Y:Q50,Y:Q51	BOTH	SCOPE	none	CLM	no	Consumption model untested; decisive question answerable from session logs, pending
P049	44	X:Q29	Y:Q52	BOTH	MATCH	none	DEF	yes	UL: Knowledge Claim
P050	44	X:Q30	Y:Q53	BOTH	MATCH	none	DEF	yes	UL: Binding vs Descriptive knowledge
P051	44	X:Q31	Y:Q54	BOTH	MATCH	none	DEF	yes	UL: Validation Mechanism (none predicts decay)
P052	44	X:Q32	Y:Q55	BOTH	MATCH	none	DEF	yes	UL: Same-Population Principle
P053	44	X:Q32	Y:Q55	BOTH	MATCH	none	DEF	no	UL: Workflow Gesture
P054	44	X:Q33	Y:Q56	BOTH	MATCH	none	DEF	no	UL: Information Scent
P055	44	X:Q33	Y:Q56	BOTH	MATCH	none	DEF	no	UL: Discovery-in-Flow
P056	44	X:Q33	Y:Q57	BOTH	MATCH	none	DEF	no	UL: Born-Stale
P057	44	X:Q34	Y:Q57	BOTH	MATCH	none	DEF	no	UL: Knowledge Half-Life
P058	44	X:Q34	Y:Q58	BOTH	MATCH	none	DEF	yes	UL: Supersession
P059	44	X:Q35	Y:Q58	BOTH	MATCH	none	DEF	no	UL: Staged Retirement
P060	44	X:Q36	Y:Q59	BOTH	MATCH	none	DEF	yes	UL: status vocabulary Canonical/Historical/Deprecated, visible at discovery or worthless
P061	44	X:Q37	Y:Q60	BOTH	MATCH	none	DEF	no	UL: Scoped Canonicity
P062	44	X:Q38	Y:Q60	BOTH	MATCH	none	DEF	no	UL: Rationale Gap
P063	44	X:Q38	Y:Q61	BOTH	MATCH	none	DEF	no	UL: Tacit Residue
P064	44	X:Q39	Y:Q61	BOTH	MATCH	none	DEF	no	UL: Concentration Risk / Truck Factor
P065	44	X:Q39	Y:Q62	BOTH	MATCH	none	DEF	no	UL: Minimal Sufficient Context
P066	44	X:Q40	Y:Q62	BOTH	MATCH	none	DEF	no	UL: Consumption Feedback Loop
P067	44	X:Q40	Y:Q62	BOTH	MATCH	none	DEF	no	UL: Contradiction Coexistence
P068	44	X:Q36	-	X	SPURIOUS	X	DEF	no	X's meaning adds "five in the Meta-Model"; no Meta-Model or five-value vocabulary exists in the source
P069	46	X:Q41	Y:Q63	BOTH	MATCH	none	CLM	no	Every term passes the Markdown-removal (format-independence) test
P070	48	-	-	NEITHER	NEITHER	both	DEF	no	Section status: "Normalized Knowledge Ontology — INTERPRETATION (semantic backbone; not folders, not a schema)". Neither reader records that the ontology is INTERPRETATION, or that it is not a folder or schema design
P071	51	-	Y:Q64	Y	OMISSION	X	MATH	no	Root PROJECT KNOWLEDGE with six child nodes (X has the six nodes but not the tree claim)
P072	52-54	X:Q42	Y:Q65	BOTH	SCOPE	none	DEF	yes	Binding node; Y adds producers=consumers=developers, highest trust (X DEF vs Y MATH)
P073	55-58	X:Q43	Y:Q66	BOTH	SCOPE	none	DEF	yes	Decision node: immutable + supersession-by-link, rationale gap; X omits enforcement-binding validation
P074	57	-	Y:Q66	Y	OMISSION	X	MATH	yes	canonical = accepted ∧ unsuperseded ∧ owned ∧ bound (the only formal predicate in the document)
P075	59-61	X:Q44	Y:Q67	BOTH	SCOPE	none	DEF	yes	Definitional node: conversational validation, term-bound-to-context; Y adds rots when conversation stops
P076	62-64	X:Q45	Y:Q68	BOTH	SCOPE	none	DEF	yes	Descriptive node: survives only code-proximate/same-population
P077	65-67	X:Q46	Y:Q69	BOTH	SCOPE	none	DEF	yes	Experiential node: claim evaluation at intake; Y adds append-only, falsified claims retained
P078	68-70	X:Q47	Y:Q70	BOTH	SCOPE	none	DEF	yes	Tacit node: transfer by practice/succession only; Y adds validation by use, concentration risk
P079	70	-	Y:Q71	Y	OMISSION	X	CLM	no	Capture programs against tacit knowledge fail
P080	71-72	X:Q48	Y:Q72	BOTH	SCOPE	none	INV	yes	Six cross-cutting properties attach to every node (X REL vs Y INV)
P081	75	X:Q49	Y:Q73,Y:Q74	BOTH	SCOPE	none	CLM	yes	Primary cut by validation mechanism and population structure, not topic; Y:Q74 "rejected" is defensible as rejection of topic as the primary cut (L103 says REFRAMED)
P082	79	X:Q50	Y:Q75	BOTH	MATCH	none	CLM	no	Owns: status vocabulary, intake gates, retirement, scent, claim granularity, context assembly
P083	80	X:Q51	Y:Q76,Y:Q77	BOTH	SCOPE	none	INV	yes	Observes artifacts in place; the evidence forbids centralizing (knowledge lives with what it describes)
P084	81	X:Q52	Y:Q78	BOTH	MATCH	none	INV	no	Never touches content, tacit knowledge, decision authority
P085	85	-	Y:Q79	Y	OMISSION	X	REL	no	Engineering Platform supplies validation machinery
P086	85	X:Q53	Y:Q80	BOTH	MATCH	none	CLM	no	Platform independently converged on the strongest invariants (source labels this FACT)
P087	86	X:Q54	Y:Q81	BOTH	MATCH	none	REL	no	Product: code is the primary knowledge carrier
P088	86	-	Y:Q82	Y	OMISSION	X	CLM	no	Constitutional invariants are Binding knowledge validated by fitness suites
P089	87	X:Q55	Y:Q83	BOTH	MATCH	none	CLM	no	Session startup is the existing gesture; project-context assembly is the observed gap
P090	87	-	Y:Q84	Y	OMISSION	X	INV	no	CLAUDE.md-class files subject to non-derivable-only rule (X captures it only later in the L108 rule list)
P091	87	-	Y:Q85	Y	OMISSION	X	CLM	no	Agentic search over live repo displaced indexes
P092	88	X:Q56	Y:Q86	BOTH	MATCH	none	CLM	no	EKP: governance metadata aligned, 3 challenged assumptions, 1 pending usage test
P093	92	X:Q57	Y:Q87	BOTH	MATCH	none	CLM	no	OQ1 EKP in-flow usage
P094	93	X:Q58	Y:Q88	BOTH	MATCH	none	CLM	no	OQ2 conflict resolution for machine consumers
P095	94	X:Q59	Y:Q89	BOTH	MATCH	none	CLM	no	OQ3 regime for part-decision part-description artifacts
P096	95	X:Q60	Y:Q90	BOTH	MATCH	none	CLM	no	OQ4 claim-granularity economics
P097	96	X:Q61	Y:Q91	BOTH	SCOPE	none	ASM	no	OQ5 conversational validation with AI engineers (X CLM vs Y ASM; Y also captures the human-cadence presumption)
P098	97	X:Q62	Y:Q92	BOTH	MATCH	none	CLM	no	OQ6 mining rationale from existing gestures
P099	98	X:Q63	Y:Q93	BOTH	MATCH	none	CLM	no	OQ7 token economics
P100	103	X:Q64	Y:Q94	BOTH	MATCH	none	REV	no	Seven-kind topic taxonomy REFRAMED (partially falsified)
P101	104	X:Q65	Y:Q95	BOTH	MATCH	none	REV	no	EKP single-lifecycle model FALSIFIED; >=4 lifecycles
P102	105	X:Q66	Y:Q96	BOTH	MATCH	none	REV	no	Second-platform hypothesis weakened to rejection in store form (Y "near-rejection" slightly softer, still faithful)
P103	105	-	Y:Q97	Y	OMISSION	X	CLM	no	A new central platform = wiki/LLIS pattern with better intentions
P104	105	-	Y:Q98	Y	OMISSION	X	CLM	no	Evidence-shaped alternative: rules + metadata in place, plus one capability
P105	107	X:Q67	Y:Q99	BOTH	MATCH	none	CLM	yes	RECOMMENDATION: absorbed rules + one targeted capability, not a new platform
P106	108	X:Q68	Y:Q100	BOTH	MATCH	none	CLM	no	Seven rules to absorb
P107	108	-	Y:Q101	Y	OMISSION	X	CLM	no	Most rules already platform practice; recommendation names and generalizes them
P108	109	X:Q69	Y:Q102	BOTH	MATCH	none	CLM	no	The one new capability: project-context assembly in-flow
P109	109	-	Y:Q103	Y	OMISSION	X	CLM	no	Mechanism: extend session startup via agentic discovery over live sources, not an index
P110	109	-	Y:Q104	Y	OMISSION	X	CLM	no	Only candidate satisfying the same-population law by construction
P111	110	X:Q70	Y:Q105	BOTH	SCOPE	none	CLM	no	EKP retain/test/refine, do not expand; X omits usage-test-first and the bypass condition
P112	111	X:Q71	Y:Q106	BOTH	SCOPE	none	REV	no	Four explicitly not-recommended options (X CLM vs Y REV)
P113	111	-	Y:Q107	Y	OMISSION	X	CLM	no	Each rejection based on sourced negative findings
P114	113	X:Q72	Y:Q108	BOTH	MATCH	none	INV	no	STOP; ARB decides whether design is authorized and in which direction
P115	116	X:Q73	Y:Q110	BOTH	SCOPE	none	CLM	no	Success check: assumptions judged; Y adds ontology draft exists
P116	116	-	Y:Q109	Y	OMISSION	X	CLM	no	Success check: format/tool/provider/project-independent UL derived
P117	116	-	Y:Q111	Y	OMISSION	X	CLM	no	Exit criteria and time-box met (7 disciplines, 35 contradictions, 33 negative findings, single cycle)
```

## 2. Totals

| Measure | All | Foundational |
|---|---|---|
| Propositions | 117 | 31 |
| Captured by both (m) | 88 | 29 |
| X total (n_X) | 89 (88 + 1 X-only) | 29 |
| Y total (n_Y) | 114 (88 + 26 Y-only) | 31 |
| X only | 1 | 0 |
| Y only | 26 | 2 |
| NEITHER | 2 | 0 |

**Per class:** MATCH 65 · SCOPE 23 · OMISSION 26 (all omitted by X) · SPURIOUS 1 (X) · NEITHER 2 · MISREADING 0 · LABEL-ERROR 0 · BOTH-DEFENSIBLE 0.
Foundational: MATCH 19 · SCOPE 10 · OMISSION 2 (X) · others 0.

**Per reader_at_fault:** none 88 · X 27 (26 OMISSION + 1 SPURIOUS) · Y 0 · both 2 (NEITHER).
Foundational: none 29 · X 2 · Y 0 · both 0.

## 3. Most consequential disagreements

1. **P074 · L57 · foundational OMISSION (X).** The only formal predicate in the document is `canonical = accepted ∧ unsuperseded ∧ owned ∧ bound`. X's Decision-node item (X:Q43) leaves it out. It defines what "Canonical" in the status vocabulary (P060) means for decision knowledge.
2. **P029 · L23 · foundational OMISSION (X).** "Which regime an artifact belongs to is a first-class property." It turns the two-regime finding into a property of each artifact, and OQ3 (L94) depends on it.
3. **P068 · L44 · SPURIOUS (X).** X:Q36's meaning says the status vocabulary has "five in the Meta-Model". The source has no Meta-Model and gives exactly three values. This is content imported from outside the source.
4. **P041 · L30 and P070 · L48 · NEITHER.** The source marks the EKP gap analysis (§1c) and the ontology (§3) as INTERPRETATION, and says the ontology is "not folders, not a schema". Neither reader records these labels. Both present the verdicts (P042-P047) and the ontology nodes (P072-P080) as unqualified CLM/REV/DEF/MATH items. No individual item is classed LABEL-ERROR, because both readers behave the same way. The loss of epistemic status sits at section level, and this is where it is recorded.
5. **Systematic X under-capture (26 omissions).** These include supporting evidence (P017 correlation figures, P022 truck factor, P037 depreciation rates), mechanisms (P109 agentic discovery rather than an index), and the platform-pattern argument (P103, P104). X compresses several source sentences into one-word meanings ("Consumed.", "Challenged.", "Forgotten.") and relies on the quote to carry the content. Where the quote did carry it, the proposition was credited (as SCOPE). Where it did not, it is an OMISSION.
6. **Type disagreements, all judged SCOPE.** Y labels the §3 ontology nodes and the trust hierarchy as MATH, and the cross-cutting properties as INV. X labels them DEF/REL. Both are faithful, but MATH overstates how formal these items are. The only real formal relation is P074.
