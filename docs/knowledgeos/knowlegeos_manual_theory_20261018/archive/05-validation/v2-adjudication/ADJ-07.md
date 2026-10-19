# ADJ-07: Adjudication of WORKSHEET-07

Source: docs/implementation/RQ-002_Research_Findings_Raw.md (84 lines). Worksheet: WORKSHEET-07.md (X = 71 items, Y = 194 items).

Method: a proposition is one distinct finding, definition, rule or relation stated in the source. Supporting statistics are folded into the claim they support (for example, the ~50% figure is folded into F1.2). A reader's item counts as capturing a proposition when its quote or meaning carries it. X's meanings are often bare labels, such as "C F1.2."; each one is read together with its quote. Sub-claims that appear in neither the quote nor the meaning are not counted for X.

## 1. Proposition table

```tsv
prop_id	lines	x_items	y_items	captured_by	class	reader_at_fault	type	foundational	note
P001	3	Q1	Q1	BOTH	MATCH	none	DEF	yes	file = append-only verbatim executor outputs; not synthesis, not architecture (governs status of every later item)
P002	3	-	Q2	Y	OMISSION	X	REL	no	synthesis deliverables 1-7 consume this file
P003	3	-	Q3	Y	OMISSION	X	DEF	no	each section = one executor's complete return
P004	9	Q2	Q4	BOTH	MATCH	none	CLM	no	Executor C inventory 16/14/7/9/28
P005	13	Q3	Q5	BOTH	MATCH	none	CLM	no	F1.1 formal semantics fail at human incentive
P006	13	Q4	Q6	BOTH	MATCH	none	CLM	no	F1.2 decision knowledge vaporizes; Y adds ~50% ADR repos 1-5 records (folded)
P007	13	Q5	Q7	BOTH	MATCH	none	CLM	no	F1.3 taxonomy/folksonomy false binary; hybrids win
P008	13	Q6	Q8	BOTH	MATCH	none	CLM	no	F1.4 ontology cost scales faster than value (Cyc)
P009	13	Q6	Q9	BOTH	MATCH	none	CLM	no	>6-month ontology phases lose sponsors; survivors minimal extensible cores (X via meaning)
P010	13	Q7	Q10	BOTH	MATCH	none	CLM	no	F2.1 information scent is a property of labels
P011	13	Q8	Q11	BOTH	MATCH	none	CLM	no	F2.2 hospitality vs authority; faceted classification favoured
P012	13	Q9	Q12	BOTH	MATCH	none	CLM	no	F2.3 collections rot structurally (ROT)
P013	13	Q9	Q13	BOTH	MATCH	none	DEF	no	archiving = distinct state; forgetting = engineered staged transition (X via meaning); type differs CLM/DEF
P014	13	Q10	Q14	BOTH	MATCH	none	CLM	yes	F3.1 provenance only converged quality standard (W3C PROV), stays minimal
P015	13	Q11	Q15	BOTH	MATCH	none	CLM	yes	F3.2 contradictory facts coexist under status metadata; deprecation != deletion
P016	13	-	Q16	Y	OMISSION	X	REL	no	humans curate coexisting truths, machines resolve at consumption
P017	13	Q12	Q17	BOTH	MATCH	none	CLM	yes	F3.3 knowledge half-life; born stale (31.7%/58.4%/20.5%; ~47%)
P018	13	-	Q18	Y	OMISSION	X	MATH	no	staleness->distrust->disuse->no-maintenance decay spiral
P019	13	Q13	Q19	BOTH	MATCH	none	DEF	yes	F3.4 SSOT is an authority-assignment policy, not a fact about truth
P020	13	Q13	Q20	BOTH	MATCH	none	CLM	no	scoped canonicity is working practice; global SSOT produces stale mirrors (X via meaning)
P021	15	Q14	Q21	BOTH	MATCH	none	CLM	no	Executor C lists 14 NKMs
P022	15	Q14	Q22	BOTH	MATCH	none	DEF	yes	nanopublication = assertion + provenance + publication metadata, immutable, corrected by new assertion
P023	15	Q14	Q23	BOTH	MATCH	none	CLM	no	SECI evidence strength ANECDOTAL (X via meaning)
P024	17	Q15	Q24	BOTH	MATCH	none	CLM	no	negative: Semantic Web failed; Cyc failed after person-century
P025	17	-	Q25	Y	OMISSION	X	CLM	no	enterprise KGs routinely abandoned (67%; ~18 months; entity resolution)
P026	17	-	Q26	Y	OMISSION	X	CLM	no	ADR adoption broad in awareness, shallow in practice
P027	17	-	Q27	Y	OMISSION	X	CLM	no	recorded knowledge often stale on arrival, almost never repaired
P028	19	-	Q28	Y	OMISSION	X	CLM	no	C-C1 formal semantics vs statistics; lightweight cores + statistical enrichment
P029	19	-	Q29	Y	OMISSION	X	CLM	no	C-C2 control vs emergent vocabulary; hybrids win
P030	19	Q16	Q30	BOTH	MATCH	none	CLM	yes	C-C3 deepest IQ disagreement; coexist at rest, resolve at consumption, linked supersession, never silent edit
P031	19	-	Q31	Y	OMISSION	X	CLM	no	C-C4 practice moved from global SSOT to scoped canonicity
P032	19	-	Q32	Y	OMISSION	X	CLM	no	C-C5 capture-everything vs selective; unresolved
P033	19	-	Q33	Y	OMISSION	X	CLM	no	C-C6 tacit convertible (SECI) vs inarticulable (Polanyi)
P034	19	-	Q34	Y	LABEL-ERROR	Y	CLM	no	C-C7: source marks the outcome "(synthesis: ...)", Y reports it as "resolved" (source uses "resolved" only for C9); X omitted the item
P035	19	-	Q35	Y	OMISSION	X	MATH	no	C-C8 born-stale evidence: creation-time validation >= maintenance
P036	19	-	Q36	Y	OMISSION	X	CLM	no	C-C9 resolved: small core + extensions
P037	21	Q17	Q37	BOTH	MATCH	none	DEF	yes	C smallest unit = contextualized assertion bound to provenance, status, address (source heading "(synthesis)"; both report it as C's unit)
P038	21	Q18	Q38	BOTH	MATCH	none	REL	no	below = data; above = composites whose value is the context atomization destroys
P039	21	-	Q39	Y	OMISSION	X	DEF	no	per-discipline units: nanopublication / scented chunk / assessable claim / decision with alternatives
P040	23	Q19	Q40	BOTH	MATCH	none	CLM	no	provenance is the substrate
P041	23	Q19	Q41	BOTH	MATCH	none	MATH	yes	authority = status metadata not deletion; Y adds rank/status correspondences and supersession-as-link (SCOPE-level difference)
P042	23	-	Q42	Y	OMISSION	X	CLM	no	canonicity scoped; conflicts coexist at rest, resolve at consumption
P043	23	-	Q43	Y	OMISSION	X	CLM	no	freshness domain-relative, two-sided; validate at creation
P044	23	Q20	Q44	BOTH	MATCH	none	CLM	no	confidence as qualification; numeric confidence unvalidated
P045	23	-	Q45	Y	OMISSION	X	CLM	no	quality has negative space; ROT removal is a quality operation
P046	25	Q21	Q46	BOTH	MATCH	none	CLM	no	C: 28 sources
P047	31	Q22	Q47	BOTH	MATCH	none	CLM	no	F-AI-1 minimal sufficient context beats maximal
P048	31	Q23	Q48	BOTH	MATCH	none	CLM	no	F-AI-2 context rot measured and universal
P049	31	Q24	Q49	BOTH	MATCH	none	CLM	no	F-AI-3 retrieval fails in 33 modes / 7 categories (X via meaning)
P050	31	Q24	Q50	BOTH	MATCH	none	CLM	no	semantic similarity has zero correlation with recency
P051	31	Q24	Q51	BOTH	MATCH	none	CLM	no	curation dominates retrieval mechanics (35% vs 6%) (X via meaning)
P052	31	Q25	Q52	BOTH	MATCH	none	CLM	no	F-AI-4 agentic search displaced pre-indexed RAG for code
P053	31	-	Q53	Y	OMISSION	X	CLM	no	counter-position: token economics (Milvus)
P054	31	Q26	Q54	BOTH	MATCH	none	CLM	no	F-AI-5 add-all memory worse than no memory
P055	31	-	Q55	Y	OMISSION	X	CLM	no	quality-gated storage + selective deletion = +10%
P056	31	-	Q56	Y	OMISSION	X	CLM	no	write-time summarization destroys episodic signal; verbatim beats extracted
P057	31	Q27	Q57	BOTH	MATCH	none	CLM	no	F-AI-6 compression lossy in decision-relevant ways
P058	31	Q28	Q58	BOTH	MATCH	none	CLM	no	F-AI-7 instruction files work only with non-derivable knowledge
P059	31	Q29	Q59	BOTH	MATCH	none	CLM	no	F-AI-8 GraphRAG multi-hop gain, worse on simple facts, costly
P060	31	Q30	Q60	BOTH	MATCH	none	CLM	no	F-CS-1 knowledge value = compressibility into schema
P061	31	Q31	Q61	BOTH	MATCH	none	CLM	no	F-CS-2 CLT prescribes packaging
P062	31	Q32	Q62	BOTH	MATCH	none	CLM	no	F-CS-3 knowledge lives in mental models; turnover dominant loss
P063	31	Q33	Q63	BOTH	MATCH	none	DEF	no	F-CS-4 onboarding = discovery under hostile conditions
P064	31	Q34	Q64	BOTH	MATCH	none	CLM	no	F-CS-5 docs materially outdated in 30-90 days
P065	31	-	Q64	Y	OMISSION	X	CLM	no	trust decays faster and near-irreversibly (death spiral); second proposition in Y:Q64
P066	31	Q35	Q65	BOTH	MATCH	none	CLM	no	F-CS-6 decision knowledge highest value, lowest captured
P067	33	Q36	Q66	BOTH	MATCH	none	CLM	no	Executor D lists 14 NKMs (X via meaning)
P068	33	-	Q67	Y	OMISSION	X	REL	no	agentic search: codebase as knowledge store; legibility = KA property
P069	33	Q36	Q68	BOTH	MATCH	none	CLM	no	retrieval unit granularity: no universal optimum, query-adaptive
P070	33	-	Q69	Y	OMISSION	X	CLM	no	decision records only mainstream form with explicit retirement semantics
P071	35	Q37	Q70	BOTH	MATCH	none	CLM	no	D negative: add-all memory < no memory
P072	35	-	Q71	Y	OMISSION	X	CLM	no	ADR practice fails at capture not value
P073	37	-	Q72	Y	OMISSION	X	CLM	no	D-C1 big windows vs context rot; rot empirical
P074	37	-	Q73	Y	OMISSION	X	CLM	no	D-C2 minimal-sufficient validated
P075	37	-	Q74	Y	OMISSION	X	CLM	no	D-C3 agentic search won; economics contested
P076	37	-	Q75	Y	OMISSION	X	CLM	no	D-C4 curation validated
P077	37	Q38	Q76	BOTH	MATCH	none	CLM	no	D-C5 verbatim ahead; hybrid converging
P078	37	-	Q77	Y	OMISSION	X	CLM	no	D-C6 small units vs decontextualization; query-adaptive
P079	37	-	Q78	Y	OMISSION	X	CLM	no	D-C7 document-everything vs freshness-beats-coverage
P080	37	-	Q79	Y	OMISSION	X	CLM	no	D-C8 managed forgetting genuinely open
P081	37	-	Q80	Y	OMISSION	X	CLM	no	D-C9 human-authored context files win
P082	39	Q39	Q81	BOTH	MATCH	none	DEF	yes	D smallest unit defined by self-sufficiency of interpretation, not size
P083	39	-	Q82	Y	OMISSION	X	INV	no	everything needed to use the assertion travels with it
P084	39	-	Q83	Y	OMISSION	X	DEF	no	AI unit = self-contained proposition + provenance + timestamp
P085	39	Q39	Q84	BOTH	MATCH	none	DEF	no	CogSci unit = chunk; smallest transferable = worked example (X via meaning)
P086	41	Q40	Q85	BOTH	MATCH	none	ASM	no	injected context assumed trustworthy (unverified assumption)
P087	41	-	Q86	Y	OMISSION	X	CLM	no	freshness absent from embeddings; explicit metadata or live sources
P088	41	-	Q87	Y	OMISSION	X	CLM	no	conflicting retrievals largely unsolved; open gap
P089	41	-	Q88	Y	OMISSION	X	CLM	no	humans check quality via credibility, recency, other humans
P090	41	-	Q89	Y	OMISSION	X	CLM	no	ADR only artifact class with built-in quality semantics
P091	41	Q40	Q90	BOTH	MATCH	none	CLM	no	validation is use; no feedback loop -> no quality (X via meaning)
P092	43	Q41	Q91	BOTH	MATCH	none	CLM	no	D: 25 sources
P093	49	Q42	Q92	BOTH	MATCH	none	CLM	no	A-F1.1 SECI dominant with weak empirical basis
P094	49	-	Q93	Y	LABEL-ERROR	Y	CLM	no	source attributes non-convertibility to "Polanyi scholars" in a critique, and the document itself lists it as contested (L19 C6, L55 C1); Y states it as unattributed fact; X omitted the item
P095	49	Q43	Q94	BOTH	MATCH	none	DEF	no	A-F1.2 five retention bins (Walsh & Ungson)
P096	49	-	Q95	Y	OMISSION	X	CLM	no	operational knowledge in individuals/routines; repository metaphor contested
P097	49	Q44	Q96	BOTH	MATCH	none	CLM	no	A-F1.3 domain-specific depreciation; loss vs obsolescence
P098	49	Q45	Q97	BOTH	MATCH	none	CLM	no	A-F1.4 forgetting both pathology and strategy
P099	49	Q46	Q98	BOTH	MATCH	none	CLM	no	A-F1.5 learning organization not evidenced
P100	49	-	Q99	Y	OMISSION	X	MATH	no	KM lifecycle models converge on create->validate->store->distribute->use
P101	49	-	Q100	Y	OMISSION	X	CLM	no	only later models add deliberate retirement
P102	49	Q47	Q101	BOTH	MATCH	none	CLM	no	real systems omit validate/maintain/retire -> write-only archives
P103	49	Q48	Q102	BOTH	MATCH	none	CLM	no	A-F2.2 KM initiatives fail ~50%+, cultural causes
P104	49	Q49	Q103	BOTH	MATCH	none	CLM	no	A-F2.3 discoverability not volume decides value
P105	49	Q50	Q104	BOTH	MATCH	none	CLM	no	A-F2.4 documentation drifts silently (28.9%; 4.7 years)
P106	49	Q51	Q105	BOTH	MATCH	none	CLM	no	A-F2.5 developers prefer people and in-flow search
P107	49	Q52	Q106	BOTH	MATCH	none	CLM	no	A-F2.6 codify-or-personalize contested
P108	51	Q53	Q107	BOTH	MATCH	none	CLM	no	Executor A lists 14 NKMs (X via meaning)
P109	51	-	Q108	Y	OMISSION	X	CLM	no	concentration risk 3-5x; counter-study absorbs loss
P110	51	-	Q109	Y	OMISSION	X	CLM	no	authority-without-maintenance worse than none
P111	51	Q53	Q110	BOTH	MATCH	none	DEF	yes	knowledge claim becomes knowledge only after surviving evaluation; Y adds Frické DIKW (folded)
P112	53	Q54	Q111	BOTH	MATCH	none	CLM	no	A negative: ~50%+ KM failure (culture)
P113	53	-	Q112	Y	OMISSION	X	CLM	no	corporate wikis mostly fail critical mass
P114	53	-	Q113	Y	OMISSION	X	CLM	no	ADR adopt-then-abandon; author pays, stranger benefits
P115	53	-	Q114	Y	OMISSION	X	CLM	no	codified knowledge often wrong from birth, rarely corrected
P116	53	-	Q115	Y	MISREADING	Y	CLM	no	source "designed CoPs frequently die early"; Y drops the quantifier ("die early"). Minor. X omitted the item
P117	55	-	Q116	Y	OMISSION	X	CLM	no	A-C1 tacit convertible vs inconvertible
P118	55	-	Q117	Y	OMISSION	X	CLM	no	A-C2 codify/connect 80/20 vs evolving mix
P119	55	-	Q118	Y	OMISSION	X	CLM	no	A-C3 stock vs enacted practice; SE leans practice
P120	55	-	Q119	Y	OMISSION	X	CLM	no	A-C4 forgetting bug vs feature
P121	55	Q55	Q120	BOTH	MATCH	none	CLM	no	A-C5 SSOT vs coupled placement; separate repos drift by construction
P122	55	-	Q121	Y	OMISSION	X	CLM	no	A-C6 decision-record failure cultural vs structural
P123	55	-	Q122	Y	OMISSION	X	CLM	no	A-C7 core-developer loss fatal vs absorbed
P124	55	-	Q123	Y	OMISSION	X	CLM	no	A-C8 validation, not refinement, creates knowledge
P125	55	-	Q124	Y	OMISSION	X	CLM	no	A-C9 CoPs designable vs emergent
P126	55	Q55	Q125	BOTH	MATCH	none	CLM	no	A-C10 born-obsolete is larger staleness source (X via meaning)
P127	57	-	Q126	Y	OMISSION	X	DEF	no	org-knowledge: no consensus; smallest transferable = shared practice episode
P128	57	Q56	Q127	BOTH	MATCH	none	DEF	yes	KM unit = knowledge claim (falsifiable, producer, context, evaluation status)
P129	57	Q56	Q128	BOTH	MATCH	none	REL	no	larger forms are bundles of claims
P130	57	-	Q129	Y	OMISSION	X	CLM	no	bundle-granularity governance cannot detect partial staleness
P131	59	-	Q130	Y	OMISSION	X	DEF	no	authority = governance assignment; evaluation-based authority untested
P132	59	-	Q131	Y	OMISSION	X	CLM	no	unmaintained authority teaches routing around governance
P133	59	-	Q132	Y	OMISSION	X	DEF	no	trust = binding constraint, property of source relationship
P134	59	-	Q133	Y	OMISSION	X	CLM	no	two spoilage mechanisms: scheduled revalidation vs intake validation
P135	59	-	Q134	Y	OMISSION	X	INV	yes	retirement is a status transition, not deletion (core lifecycle rule, echoed L23/L33/L79)
P136	59	-	Q135	Y	OMISSION	X	CLM	no	dominant failure = unmarked status; historical/canonical indistinguishable
P137	59	Q57	Q136	BOTH	MATCH	none	CLM	no	NASA and SO failures reduce to invisible validity status at discovery
P138	61	Q58	Q137	BOTH	MATCH	none	CLM	no	A: 20 source clusters
P139	67	Q59	Q138	BOTH	MATCH	none	CLM	no	DDD: durable knowledge lives in code + conversation
P140	67	-	Q139	Y	OMISSION	X	CLM	no	workshop artifact death by design
P141	67	-	Q140	Y	OMISSION	X	CLM	no	glossaries/context maps rot ~2 years after owner departure
P142	67	-	Q141	Y	OMISSION	X	CLM	no	practitioner literature silent on sustainment
P143	67	-	Q142	Y	OMISSION	X	CLM	no	language drift signals crossed context boundary
P144	67	-	Q143	Y	OMISSION	X	CLM	no	context maps survive only as code by-products
P145	67	-	Q144	Y	OMISSION	X	CLM	no	many small disposable maps, not one master map
P146	67	Q60	Q145	BOTH	MATCH	none	CLM	no	DDD SLR: validation vacuum
P147	69	-	Q146,Q147	Y	OMISSION	X	CLM	no	staleness universal (68%) and tolerated (81% still useful)
P148	69	Q61	Q148	BOTH	MATCH	none	MATH	yes	trust stratified by code proximity r=0.67/0.58/0.41/0.03 (underpins B-C1, trust hierarchy, thesis)
P149	69	-	Q149	Y	OMISSION	X	CLM	no	code-proximate must be accurate to be used; abstract docs = archaeology
P150	69	Q62	Q150	BOTH	MATCH	none	CLM	no	self-report overstates use ~10x; surveys = upper bounds
P151	69	-	Q151,Q152	Y	OMISSION	X	CLM	yes	rationale scarcest, most demanded, lives in heads (basis of L79 "unsolved middle" and thesis part 3)
P152	69	Q63	Q153	BOTH	MATCH	none	CLM	no	capture fails for economic reasons, not tooling
P153	69	Q64	Q154	BOTH	MATCH	none	CLM	no	turnover loss fat-tailed, not mitigated by archives (X via meaning)
P154	69	Q64	Q155	BOTH	MATCH	none	CLM	no	only measured mitigations = succession mechanics
P155	69	-	Q156	Y	OMISSION	X	CLM	no	only quantified positive: ~15% efficiency, preliminary (Y keeps "preliminary")
P156	71	Q65	-	X	OMISSION	Y	CLM	no	Executor B lists 13 NKMs (count only in X meaning)
P157	71	-	Q157	Y	OMISSION	X	CLM	no	UL rots when ownership breaks despite canon (supported by L67 documented failure)
P158	71	-	Q158	Y	OMISSION	X	CLM	no	crunching without externalization creates fragility
P159	71	-	Q159	Y	OMISSION	X	CLM	no	EventStorming board does not transfer knowledge to absentees
P160	71	-	Q160	Y	OMISSION	X	CLM	no	Domain Story: absence of maintenance reports is the finding
P161	71	-	Q161	Y	OMISSION	X	CLM	no	ADR modal lifecycle trial-and-abandon; readership unmeasured; success needed enforcement tooling
P162	71	-	Q162	Y	OMISSION	X	CLM	no	living documentation unevaluated; view-model not model-reality consistency
P163	71	-	Q163	Y	OMISSION	X	CLM	no	executable specification failure per author's survey
P164	71	Q65	Q164	BOTH	MATCH	none	CLM	yes	docs-as-code success: maintainers of knowledge = maintainers of artifact (same-population mechanism of thesis)
P165	71	-	Q165	Y	OMISSION	X	CLM	no	team wiki best-evidenced failure; stale docs cause newcomer dropout
P166	71	-	Q166	Y	OMISSION	X	CLM	no	NASA LLIS two decades of audited non-use
P167	71	-	Q167	Y	OMISSION	X	CLM	no	tacit knowledge/mental models = actual store; strongest-evidenced
P168	71	-	Q168	Y	OMISSION	X	CLM	no	code-proximate micro-docs only class updated promptly
P169	73	-	Q169	Y	OMISSION	X	CLM	no	B negative: Google wiki died measurably (90%)
P170	73	Q66	Q170	BOTH	MATCH	none	CLM	no	stale documentation worse than none for newcomers
P171	73	-	Q171	Y	OMISSION	X	CLM	no	93% observe outdated docs, ~60% never contribute
P172	75	-	Q172	Y	OMISSION	X	CLM	no	B-C1 resolved: trust stratified by proximity and reader; newcomers harmed
P173	75	-	Q173	Y	OMISSION	X	CLM	no	B-C2 conversation for creation; artifacts only when maintainers=users
P174	75	Q67	Q174	BOTH	MATCH	none	CLM	no	B-C3 Nygard vs Martraire unresolved; practice reconciliation
P175	75	-	Q175	Y	OMISSION	X	CLM	no	B-C4 docs necessary-not-sufficient; human scaffolding
P176	75	-	Q176	Y	OMISSION	X	CLM	no	B-C5 automation relocates staleness
P177	75	-	Q177	Y	OMISSION	X	CLM	no	B-C6 behavioral evidence is arbiter (Y types INV; CLM fits better)
P178	75	-	Q178	Y	OMISSION	X	CLM	no	B-C7 ADR endorsement = authority cascade
P179	77	-	Q179	Y	OMISSION	X	DEF	no	DDD unit: term bound to context; invariant = smallest binding unit
P180	77	-	Q180	Y	OMISSION	X	DEF	no	SE: surviving unit updated in same gesture; consumed unit = task-situated answer
P181	77	Q68	Q181	BOTH	MATCH	none	DEF	yes	convergent atomic unit: single decision-or-definition, context-bound, co-located, cheap to update
P182	77	-	Q182	Y	OMISSION	X	CLM	no	separate maintenance gesture decays at wiki rate
P183	79	-	Q183	Y	OMISSION	X	DEF	no	authority = ownership + freshness; ownerless = non-authoritative
P184	79	-	Q184	Y	OMISSION	X	CLM	no	canonical-vs-historical solved only on paper in ADR model
P185	79	-	Q185	Y	OMISSION	X	MATH	no	de facto trust hierarchy running code > tests > ... > departed colleagues' notes
P186	79	-	Q185	Y	SPURIOUS	Y	REL	no	Y:Q185 meaning asserts "(repeated in file 05)", which is not in the source and cannot be verified from it
P187	79	-	Q186	Y	OMISSION	X	CLM	no	verification-by-execution only mechanism shown to keep artifact true
P188	79	-	Q187	Y	OMISSION	X	REL	no	DDD validates conversationally, SE behaviorally
P189	79	-	Q188	Y	OMISSION	X	CLM	no	rationale = unsolved middle, most demanded, least validated
P190	79	Q69	Q189	BOTH	MATCH	none	DEF	no	ADR authoritative when accepted+unsuperseded, owned, bound to code
P191	79	-	Q190	Y	OMISSION	X	CLM	no	retirement least-designed stage; default = silent abandonment
P192	81	Q70	Q191	BOTH	MATCH	none	CLM	yes	thesis 1: knowledge survives only where maintainers = consumers and update is in-workflow (source marks it "executor's synthesis"; both report it as B's thesis)
P193	81	-	Q192	Y	OMISSION	X	CLM	no	thesis 2: second-population / separate-gesture artifact classes decay to graveyard
P194	81	-	Q193	Y	OMISSION	X	CLM	no	thesis 3: design rationale stays tacit, lost fat-tailed through turnover
P195	83	Q71	Q194	BOTH	MATCH	none	CLM	no	B: ~50 sources
```

## 2. Totals

All propositions (195):

- Captured by both readers (m): 86
- Captured by X (n_X): 87. These are 86 shared plus 1 captured by X alone.
- Captured by Y (n_Y): 194. These are 86 shared plus 108 captured by Y alone.
- Captured by neither reader (NEITHER): 0

Counts by class:

| Class | Count |
|---|---|
| MATCH | 86 |
| OMISSION | 105 (X omitted 104, Y omitted 1) |
| MISREADING | 1 (Y) |
| LABEL-ERROR | 2 (Y) |
| SCOPE | 0 |
| BOTH-DEFENSIBLE | 0 |
| SPURIOUS | 1 (Y) |
| NEITHER | 0 |

Counts by reader at fault:

| Reader at fault | Count |
|---|---|
| none | 86 |
| X | 104 (all omissions) |
| Y | 5 (1 omission, 2 label errors, 1 misreading, 1 spurious) |
| both | 0 |

SCOPE-level differences, where both readers are faithful but Y splits more finely or chooses a different type, are recorded in the notes of MATCH rows. Examples are P013, P041 and P111.

Foundational propositions (18):

- These are P001, P014, P015, P017, P019, P022, P030, P037, P041, P082, P111, P128, P135, P148, P151, P164, P181 and P192.
- 16 are MATCH, with m = 16.
- 2 are OMISSION by X: P135 and P151.
- n_X = 16 and n_Y = 18. NEITHER = 0.
- None of the foundational propositions has a MISREADING, LABEL-ERROR or SPURIOUS item.

## 3. Most consequential disagreements

1. **P135, line 59. Foundational OMISSION by X.** The source states "retirement is a STATUS TRANSITION, not deletion". This is the lifecycle rule behind the authority-as-status theme at lines 23, 33 and 79. Only Y captured it, as Y:Q134. X's only item for line 59 is the NASA/SO summary (X:Q57).
2. **P151, line 69. Foundational OMISSION by X.** The source states that rationale is "the scarcest, most-demanded knowledge and lives in heads". Line 79 ("the unsolved middle is RATIONALE") and part 3 of the thesis at line 81 both depend on it. X has no item for it.
3. **P094, line 49. LABEL-ERROR by Y.** Y:Q93 states "Genuinely tacit knowledge is not convertible" as plain fact. The source gives this as the position of "Polanyi scholars" inside a critique of SECI. The document itself lists it as an open contradiction at line 19 (C6) and line 55 (C1).
4. **P034, line 19. LABEL-ERROR by Y.** For C-C7, Y:Q34 says "resolved as never destroy silently, demote visibly". The source marks this outcome "(synthesis: ...)". It uses "resolved" only for C9 ("(resolved: small core + extensions)"), so Y raises the status of an executor synthesis to a resolution.
5. **P116, line 53. MISREADING by Y (minor).** Y:Q115 reads "Designed communities of practice die early". The source says "designed CoPs frequently die early". Dropping "frequently" turns a frequency claim into a universal one.
6. **P186, line 79. SPURIOUS item by Y.** The meaning of Y:Q185 adds "(repeated in file 05)". Nothing in the source supports this cross-reference. The trust hierarchy itself (P185) is faithful.
7. **Systematic coarse granularity by X (104 omissions).** X records each finding by its headline alone and adds a count label for the lists of contradictions, negative findings and NKMs. As a result, X misses almost every individual contradiction (lines 19, 37, 55 and 75) and every item in Executor B's NKM list (line 71). It also misses most of the quality-treatment sub-claims at lines 23, 41, 59 and 79, and thesis parts 2 and 3 (line 81). For example, line 19 lists 9 contradictions and X captured only C3. These are omissions rather than misreadings: every X item that is present is faithful.
