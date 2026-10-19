# ADJ-06 — Adjudication of WORKSHEET-06 (source: docs/implementation/RQ-002_Architectural_Synthesis.md)

Judged against the source text only. Unit = proposition. SCOPE is used where both readers are faithful and differ only in granularity, boundary (one reader quotes/paraphrases a subset that its meaning label still covers) or type choice (e.g. X types principle consequences/decisions as CLM, Y as INV).

## 1. Proposition table

```tsv
prop_id	lines	x_items	y_items	captured_by	class	reader_at_fault	type	foundational	note
P001	3	Q1	Q1	BOTH	MATCH	none	DEF	no	Document kind: architectural synthesis bridging research and architecture
P002	3	Q1	Q2	BOTH	SCOPE	none	INV	no	NOT design (no folders/templates/software/APIs/aggregates); X folds into Q1 meaning "not design"
P003	3	Q1	Q3	BOTH	SCOPE	none	INV	no	Status COMPLETE; architecture only after ARB rules; X folds into Q1
P004	4	Q2	Q4	BOTH	SCOPE	none	ASM	no	Closed evidence base; Y enumerates its members, X names it only
P005	4	Q2	Q5	BOTH	MATCH	none	CLM	no	No new research; every statement traceable to the base
P006	5	Q3	Q6	BOTH	SCOPE	none	INV	no	Four output tests; type CLM (X) vs INV (Y)
P007	12	Q4	Q7	BOTH	MATCH	none	INV	yes	PK-P1 Same-Population statement
P008	13	Q5	Q8	BOTH	MATCH	none	CLM	no	PK-P1 rationale: second-population artifacts decay
P009	14	Q6	Q9	BOTH	SCOPE	none	REL	no	PK-P1 supporting evidence; X quotes docs-as-code fragment only
P010	15	Q7	Q10	BOTH	SCOPE	none	CLM	no	Watson Discovery counter-evidence (80+ ADRs only with enforcement binding); X names it in meaning only
P011	15	Q7	Q11	BOTH	MATCH	none	CLM	no	Exception proves the rule: binding created a same-population gesture
P012	16	Q8	Q12	BOTH	SCOPE	none	INV	no	No new stores/portals/repositories; capabilities extend existing gestures; X quotes first half, CLM vs INV
P013	19	Q9	Q13	BOTH	MATCH	none	INV	yes	PK-P2 Status-at-Discovery statement
P014	20	Q10	Q14	BOTH	MATCH	none	CLM	no	PK-P2 rationale: consumer cannot tell valid from stale at retrieval
P015	21	Q11	Q15	BOTH	SCOPE	none	REL	no	PK-P2 supporting evidence A/C/D/B
P016	22	Q12	Q16	BOTH	SCOPE	none	CLM	no	No contradictory evidence; stamping cost addressed by PK-P1 (X omits the PK-P1 resolution)
P017	23	Q13	Q17	BOTH	SCOPE	none	INV	no	No retrieval surface without status; bare document is a defect
P018	26	Q14	Q18	BOTH	MATCH	none	INV	yes	PK-P3 Claim-Granularity statement (claim = context-bound, provenance-carrying, status-bearing assertion)
P019	27	Q15	Q19	BOTH	SCOPE	none	CLM	no	PK-P3 rationale; X omits four-discipline convergence
P020	28	Q16	Q20	BOTH	MATCH	none	REL	no	PK-P3 supporting evidence
P021	29	Q17	Q21	BOTH	SCOPE	none	CLM	no	Atomization counter-evidence; X omits nanopublication-overhead niche clause
P022	30	-	Q22	Y	OMISSION	X	REL	no	Claim-level metadata is the reference model; composite forms remain units of comprehension
P023	30	Q18	Q23	BOTH	SCOPE	none	INV	no	Architecture supports both granularities without forced atomization
P024	33	Q19	Q24	BOTH	SCOPE	none	INV	yes	PK-P4 Execution-Proximity statement; INV (X) vs CLM (Y)
P025	34	Q20	Q25	BOTH	SCOPE	none	MATH	no	r=0.67..0.03 accuracy-consultation correlation; non-executable decays (X omits second clause); CLM vs MATH
P026	35	Q21	Q26	BOTH	SCOPE	none	REL	no	PK-P4 supporting evidence (trust hierarchy, 6% vs 35%, in-flow)
P027	36	Q22	Q27	BOTH	MATCH	none	CLM	no	Automation relocates staleness (graveyard with a build step)
P028	37	Q23	Q28	BOTH	SCOPE	none	INV	no	Prefer executable forms; distance raises governance metadata, lowers authority
P029	40	Q24	Q29	BOTH	MATCH	none	INV	yes	PK-P5: exactly two regimes (decisions immutable/superseded by link; descriptions evolve)
P030	40	Q24	Q30	BOTH	SCOPE	none	INV	yes	Every artifact declares its regime; X merged into one item
P031	41	Q25	Q31	BOTH	MATCH	none	CLM	no	Nygard/Martraire resolves as regime-per-class
P032	42	Q26	Q32	BOTH	SCOPE	none	REL	no	PK-P5 supporting evidence
P033	43	Q27	Q33	BOTH	MATCH	none	CLM	no	Boundary artifacts (part decision, part description) exist
P034	43	-	Q33	Y	OMISSION	X	CLM	no	Regime assignment for boundary artifacts is an open question (§8)
P035	44	Q28	Q35	BOTH	SCOPE	none	INV	no	"Which regime?" is a mandatory property of every knowledge class
P036	47	Q29	Q36	BOTH	MATCH	none	INV	yes	PK-P6 Minimal-Sufficient-Context statement
P037	48	Q30	Q37	BOTH	MATCH	none	CLM	no	Unfiltered volume harms both consumer types
P038	49	Q31	Q38	BOTH	SCOPE	none	REL	no	PK-P6 supporting evidence (18/18, masking, 89.1 vs 70.7, CLT, fragments)
P039	50	Q32	Q39	BOTH	MATCH	none	CLM	no	Over-pruning drops caveats; selection becomes error source
P040	51	Q33	Q40	BOTH	SCOPE	none	INV	no	Assembly first-class, storage substrate; Y adds "context is perishable, assembled per task"
P041	54	Q34	Q41	BOTH	MATCH	none	INV	yes	PK-P7 Coexistence-with-Status statement
P042	55	Q35	Q42	BOTH	SCOPE	none	CLM	no	Storage-time resolution destroys information; X omits plural-truths clause
P043	56	Q36	Q43	BOTH	SCOPE	none	REL	no	PK-P7 supporting evidence (C)
P044	57	Q37	Q44	BOTH	SCOPE	none	CLM	no	Machine consumers need one value; no AI conflict adjudication (open gap)
P045	58	Q38	Q45	BOTH	SCOPE	none	INV	no	No delete-the-losing-fact operation; rank, reason, resolve-at-read; CLM vs INV
P046	61	Q39	Q46	BOTH	MATCH	none	INV	yes	PK-P8 Staged-Forgetting statement
P047	62	Q40	Q47	BOTH	MATCH	none	CLM	no	Unretired stale knowledge harms; destroyed knowledge breaks rationale archaeology
P048	63	Q41	Q48	BOTH	SCOPE	none	REL	no	PK-P8 supporting evidence
P049	64	Q42	Q49	BOTH	MATCH	none	CLM	no	Hygiene-vs-failure open dispute; both sides favour explicit mechanics
P050	65	Q43	Q50	BOTH	SCOPE	none	INV	no	Retirement path named before creation path; CLM vs INV
P051	68	Q44	Q51	BOTH	SCOPE	none	INV	yes	PK-P9: tacit knowledge transferred by practice/succession, not capture mandates
P052	68	Q44	Q52	BOTH	SCOPE	none	INV	no	Platform measures concentration risk, supports succession, never demands externalization; X covers via statement label only
P053	69	Q45	Q53	BOTH	SCOPE	none	CLM	no	PK-P9 rationale; X captures SECI clause only
P054	70	Q46	Q54	BOTH	SCOPE	none	REL	no	PK-P9 supporting evidence
P055	71	Q47	Q55	BOTH	MATCH	none	CLM	no	Decision-time rationale capture worthwhile; capture window applies to decisions not skill
P056	72	Q48	Q56	BOTH	SCOPE	none	INV	no	Rationale vs skill distinguished; no tacit-knowledge repository deliverable
P057	75	Q49	Q57	BOTH	SCOPE	none	INV	yes	PK-P10 Scent statement; INV (X) vs DEF (Y)
P058	76	Q50	Q58	BOTH	SCOPE	none	CLM	no	Foraging validated; NASA failure = scent failure; X omits first clause
P059	77	Q51	Q59	BOTH	SCOPE	none	REL	no	PK-P10 supporting evidence; naming and structure ARE knowledge architecture
P060	78	Q52	Q60	BOTH	MATCH	none	CLM	no	Search-makes-scent-obsolete answered: snippets are scent surfaces
P061	79	Q53	Q61	BOTH	SCOPE	none	INV	no	Naming/titling/summarization/addressability obligations of every producer
P062	85	-	Q62	Y	OMISSION	X	CLM	no	AD1 alternatives & trade-offs (central store = one surface but second population/drift; co-located survives but governance fragments)
P063	85	Q54	Q63	BOTH	SCOPE	none	INV	no	AD1 recommendation: co-located content, centralized governance metadata only
P064	86	-	Q64	Y	OMISSION	X	CLM	no	AD2 trade-offs (docs expressive but decay; executable true but limited)
P065	86	Q55	Q65	BOTH	SCOPE	none	INV	no	AD2 recommendation: executable where possible, bind rest; unbound prose lowest authority
P066	87	-	Q66	Y	OMISSION	X	CLM	no	AD3 trade-offs (immutability keeps history/risks staleness; evolution keeps currency/destroys history)
P067	87	Q56	Q67	BOTH	SCOPE	none	INV	no	AD3 recommendation: both, by declared regime
P068	88	-	Q68	Y	OMISSION	X	CLM	no	AD4 trade-offs (formal ontology precise but catastrophic cost; emergent cheap but noisy)
P069	88	Q57	Q69	BOTH	SCOPE	none	INV	no	AD4 recommendation: minimal explicit core + emergent enrichment, faceted; no comprehensive ontology
P070	89	-	Q70	Y	OMISSION	X	CLM	no	AD5 trade-offs (capture-all becomes ROT, add-all harms; selective risks loss)
P071	89	Q58	Q71	BOTH	SCOPE	none	INV	no	AD5 recommendation: selective with intake gates except decisions (capture at decision time or never)
P072	90	-	Q72	Y	OMISSION	X	CLM	no	AD6 trade-offs (global SSOT becomes stale mirror; scoped needs discipline)
P073	90	Q59	Q73	BOTH	SCOPE	none	REV	no	AD6 recommendation: scoped canonicity; global SSOT rejected (Y REV is apt)
P074	91	-	Q74	Y	OMISSION	X	CLM	no	AD7 trade-offs (prevent-all = hoarding; managed forgetting risks loss)
P075	91	Q60	Q75	BOTH	SCOPE	none	INV	no	AD7 recommendation: managed forgetting with safety rail
P076	92	-	Q76	Y	OMISSION	X	CLM	no	AD8 trade-offs (index cheap but silently stale; live discovery current but token-expensive)
P077	92	Q61	Q77	BOTH	SCOPE	none	INV	no	AD8 recommendation: live sources default for code-proximate; indexes only where staleness governed; economics open (X omits)
P078	98	Q62	Q78	BOTH	MATCH	none	DEF	no	Candidate BC Knowledge Qualification
P079	99	Q63	Q79	BOTH	MATCH	none	DEF	no	Candidate BC Discovery & Assembly
P080	100	Q64	Q80	BOTH	MATCH	none	DEF	no	Candidate BC Knowledge Governance
P081	100	-	Q81	Y	OMISSION	X	CLM	no	Evidence anchor: authority = process property; ownerless = non-authoritative
P082	101	Q65	Q82	BOTH	MATCH	none	DEF	no	Candidate BC Evolution & Retirement
P083	102	Q66	Q83	BOTH	MATCH	none	DEF	no	Candidate BC Knowledge Provenance
P084	104	Q67	Q84	BOTH	LABEL-ERROR	Y	REL	no	Source marks this INTERPRETATION; X flags it, Y records the correspondence as a plain REL with no [I]
P085	104	-	Q85	Y	OMISSION	X	CLM	no	Platform may already be a partial implementation of the knowledge domain (hedged; Y keeps the hedge)
P086	104	Q68	Q86	BOTH	MATCH	none	CLM	no	Deep vs superficial similarity is an architecture-phase question, flagged not decided
P087	108	Q69	Q87	BOTH	MATCH	none	INV	yes	Invariant 1: knowledge never silently disappears; deleting last copy forbidden
P088	109	Q70	Q88	BOTH	MATCH	none	INV	yes	Invariant 2: status transitions explicit, reasoned, visible at discovery
P089	110	Q71	Q89	BOTH	SCOPE	none	INV	yes	Invariant 3: provenance (producer, time, context) mandatory on every claim; X quotes only the corollary
P090	110	Q71	Q90	BOTH	SCOPE	none	DEF	yes	A claim without provenance is data, not knowledge; X merged, Y split as DEF
P091	111	Q72	Q91	BOTH	MATCH	none	INV	yes	Invariant 4: contradictions remain visible
P092	112	Q73	Q92	BOTH	MATCH	none	INV	yes	Invariant 5: authority is scoped
P093	113	Q74	Q93	BOTH	MATCH	none	INV	yes	Invariant 6: freshness observable at discovery, priced per domain half-life
P094	114	Q75	Q94	BOTH	MATCH	none	INV	yes	Invariant 7: supersession is a link, never an edit
P095	114	-	Q95	Y	OMISSION	X	REL	no	Invariant 7 matches the platform's existing append-only law
P096	115	Q76	Q96	BOTH	MATCH	none	INV	yes	Invariant 8: no second-population artifact class (PK-P1 as invariant)
P097	116	Q77	Q97	BOTH	MATCH	none	INV	yes	Invariant 9: quality evidenced, never asserted
P098	122	Q78	Q98	BOTH	MATCH	none	CLM	no	Force Trust: non-uniform; failures catastrophic
P099	123	Q79	Q99	BOTH	SCOPE	none	CLM	no	Force Freshness: domain-relative decay, born stale, observable and gated; CLM vs INV
P100	124	Q80	Q100	BOTH	MATCH	none	CLM	no	Force Discoverability
P101	125	Q81	Q101	BOTH	MATCH	none	CLM	no	Force Context assembly
P102	126	Q82	Q102	BOTH	SCOPE	none	CLM	no	Force Human cognition; X quotes worked-example clause only
P103	127	Q83	Q103	BOTH	SCOPE	none	CLM	no	Force AI context limitations; X captures conflict-adjudication clause only
P104	128	Q84	Q104	BOTH	SCOPE	none	CLM	no	Force Organizational learning/turnover; X omits mental-models/fat-tail clauses
P105	133-138,44	Q85	Q105,Q34	BOTH	SCOPE	none	REL	yes	Lifecycle: linear to Evolution, forks (descriptions loop; decisions -> supersession -> historical retention -> staged retirement); REL vs MATH; Y also states fork at L44
P106	143	Q86	Q106	BOTH	SCOPE	none	DEF	no	Creation stage (inv 3, 8); DEF vs INV
P107	144	Q87	Q107	BOTH	MATCH	none	DEF	no	Qualification stage (inv 3, 9)
P108	144	-	Q108	Y	OMISSION	X	REL	no	Qualification stage evidence: McElroy; 58.4% born stale
P109	145	Q88	Q109	BOTH	MATCH	none	DEF	no	Publication stage (inv 2, 5, 6)
P110	146	Q89	Q110	BOTH	SCOPE	none	DEF	no	Discovery & Consumption stage; validation is use; DEF vs REL
P111	147	Q90	Q111	BOTH	SCOPE	none	DEF	no	Evolution stage regime fork (inv 7); DEF vs INV
P112	148	Q91	Q112	BOTH	MATCH	none	DEF	no	Supersession stage (inv 4, 7)
P113	149	Q92	Q113	BOTH	MATCH	none	DEF	no	Historical retention stage (inv 1, 2)
P114	150	Q93	Q114	BOTH	SCOPE	none	DEF	no	Staged retirement stage (inv 1, 8); DEF vs INV
P115	156	Q94	Q115	BOTH	MATCH	none	DEF	no	Capability Knowledge Discovery
P116	157	Q95	Q116	BOTH	MATCH	none	DEF	no	Capability Context Assembly, the one NEW capability recommended
P117	158	Q96	Q117	BOTH	MATCH	none	DEF	no	Capability Knowledge Qualification
P118	159	Q97	Q118	BOTH	MATCH	none	DEF	no	Capability Authority & Status
P119	160	Q98	Q119	BOTH	MATCH	none	DEF	no	Capability Provenance
P120	161	Q99	Q120	BOTH	MATCH	none	DEF	no	Capability Evolution & Supersession
P121	162	Q100	Q121	BOTH	MATCH	none	DEF	no	Capability Retirement
P122	163	Q101	Q122	BOTH	MATCH	none	DEF	no	Capability Governance (invariants as checkable rules)
P123	164	Q102	Q123	BOTH	MATCH	none	DEF	no	Capability Succession Support, deliberately not capture
P124	170	Q103	Q124	BOTH	SCOPE	none	CLM	no	Conditionally yes; D1/D2/D4/D6 triangulated and stable; X omits triangulation clause
P125	170	Q103	Q125	BOTH	MATCH	none	CLM	no	Three gaps block committing to, not writing, a reference architecture
P126	171	Q104	Q126	BOTH	MATCH	none	CLM	no	Next step 1: EKP in-flow usage test first
P127	171	Q104	Q127	BOTH	SCOPE	none	CLM	no	Next step 2: PK Reference Architecture (DRAFT->ADOPTED->STABLE); X in meaning only
P128	171	Q104	Q128	BOTH	SCOPE	none	CLM	no	Next step 3: unpause ER-09 reframed; all behind execution queue (X omits sequencing)
P129	172	Q105	Q129	BOTH	MATCH	none	CLM	no	Missing evidence E-1..E-3
P130	173	Q106	Q130	BOTH	MATCH	none	CLM	no	Biggest uncertainty: context assembly without violating PK-P1
P131	173	-	Q131	Y	OMISSION	X	INV	no	Design answer must come from a small evidence-producing pilot, not further synthesis
P132	177	Q107	Q132	BOTH	SCOPE	none	CLM	no	Constraint check (nothing designed, all anchored, traceable); X via meaning label only
P133	177	Q107	Q133	BOTH	MATCH	none	INV	no	STOP: no architecture before ARB review
P134	81	-	-	NEITHER	NEITHER	both	CLM	no	Status label of Deliverable 2: "direction only, no implementation" (decisions are recommendations)
P135	106	-	-	NEITHER	NEITHER	both	INV	yes	Normative scope of D4: "every future implementation must satisfy; all evidence-anchored"
```

## 2. Totals

All propositions (135):
- Propositions: 135
- Captured by both (m): 118
- X total (n_X): 118 (X-only: 0)
- Y total (n_Y): 133 (Y-only: 15)
- NEITHER: 2
- Per class: MATCH 57 · SCOPE 60 · LABEL-ERROR 1 · OMISSION 15 · MISREADING 0 · BOTH-DEFENSIBLE 0 · SPURIOUS 0 · NEITHER 2
- reader_at_fault: X 15 · Y 1 · both 2 · none 117

Foundational subset (23):
- Propositions: 23
- m: 22 · n_X: 22 · n_Y: 22 · NEITHER: 1
- Per class: MATCH 15 · SCOPE 7 · NEITHER 1 · all others 0
- reader_at_fault: both 1 · none 22

## 3. Most consequential disagreements

1. **P084 — LABEL-ERROR (Y), L104.** The source explicitly opens the paragraph with "INTERPRETATION worth recording" and closes it "flagged, not decided". X tags Q67 "[INTERPRETATION in source]"; Y Q84 records the BC correspondence as a plain REL with no [I] and no interpretation marker, presenting an interpretation as an asserted relation. (Y's Q85 does preserve the "may" hedge for the follow-on sentence.)
2. **P062–P076 (8 props) — OMISSION (X), L85–L92.** For every PK-AD1..AD8, X records only the recommendation; the "Alternatives & trade-offs" column (the contradiction's two sides and their costs) is not captured. Y captures each. These carry the justification for each decision, though they are not foundational.
3. **P034 — OMISSION (X), L43.** "regime assignment for them is an open question (§8)". X records that boundary artifacts exist but drops that the source leaves their regime open, which qualifies PK-P5's "exactly two regimes".
4. **P085 — OMISSION (X), L104.** "The platform may already be a partial implementation of the knowledge domain" — the substantive (hedged) consequence of the interpretation.
5. **P131 — OMISSION (X), L173.** "The design answer must come from a small evidence-producing pilot on real tickets, not from further synthesis" — the source's method prescription for its biggest uncertainty.
6. **P022 — OMISSION (X), L30.** "claim-level quality metadata is the *reference model*; composite forms ... remain the units of comprehension" — the dual-granularity resolution of PK-P3.
7. **P135 — NEITHER, L106 (foundational).** "every future implementation must satisfy; all evidence-anchored" — the normative force of the nine invariants; both readers extract the invariants but neither records that the source binds all future implementations to them.
8. **P134 — NEITHER, L81.** "direction only, no implementation" — the status label of Deliverable 2. Neither reader records it; relevantly, Y types every AD recommendation as INV (binding rule) and X as CLM. Given this label, the recommendations are directions pending ARB, so Y's INV typing is borderline overstatement (judged SCOPE here, since the source itself calls them "Architectural Decisions").

No MISREADING or SPURIOUS items found.
