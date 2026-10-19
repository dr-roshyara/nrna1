# ADJ-03 — Adjudication of WORKSHEET-03

Source: `docs/implementation/RQ-002_Knowledge_Taxonomy.md` (141 lines). Readers X (126 items) and Y (99 items).
Every X and Y item is mapped to at least one proposition. General note: many Y meaning sentences are only labels (e.g. "Executable lifecycle."), and the verbatim quote carries the content. Where the quote shows the proposition faithfully, I count the item as a faithful capture.

## 1. Proposition table

```tsv
prop_id	lines	x_items	y_items	captured_by	class	reader_at_fault	type	foundational	note
P01	3	Q1	Q1	BOTH	MATCH	none	DEF	no	Kind: synthesis artifact = semantic foundation for future Reference Architecture
P02	3	Q2	Q1	BOTH	MATCH	none	INV	no	Exclusions: not folders/templates/document ontology/design (X INV, Y CLM)
P03	4	Q3	Q2	BOTH	MATCH	none	INV	no	Status COMPLETE for ARB review; STOP until ARB rules
P04	5	Q4	Q3	BOTH	MATCH	none	ASM	no	Closed evidence base (charter, raw findings, discovery, synthesis)
P05	5	Q5	Q3	BOTH	SCOPE	none	INV	no	Method rule: no new research, traceable, candidates validated or falsified; Y keeps only the candidate-testing part
P06	11	Q6	Q4	BOTH	MATCH	none	CLM	yes	Family thesis: family of types with one constitutional core, not a homogeneous domain
P07	11	Q7	Q5	BOTH	MATCH	none	CLM	yes	7 types survive; 5 candidates falsified or merged
P08	11	Q8	-	X	OMISSION	Y	CLM	yes	Types differ on every governance-relevant axis (producer, validation, lifecycle, retirement, freshness, discovery)
P09	11	Q9	Q6	BOTH	MATCH	none	REL	yes	All types obey the nine invariants of the Synthesis
P10	11	Q10	Q7	BOTH	MATCH	none	INV	yes	Uniform treatment is an architectural error; per-type governance is mandatory
P11	18	Q11	Q8	BOTH	MATCH	none	DEF	yes	Executable Knowledge definition
P12	19	Q12	Q9	BOTH	MATCH	none	CLM	no	Executable producer = consumer
P13	20	Q13	Q10	BOTH	MATCH	none	CLM	no	Executable consumers; highest-trust stratum
P14	21	Q14	Q11	BOTH	MATCH	none	DEF	no	Executable authority = passing run, earned per execution
P15	22	Q15	Q12	BOTH	MATCH	none	CLM	yes	Validation = execution, the only mechanism shown to keep an artifact true
P16	22	Q16	-	X	OMISSION	Y	REL	no	Evidence: B r=0.67, D 6% vs 35%
P17	23	Q17	Q13	BOTH	MATCH	none	CLM	no	Executable changes with its referent (survival mode)
P18	24	Q18	Q14	BOTH	MATCH	none	CLM	no	Executable retirement
P19	25	Q19	Q15	BOTH	MATCH	none	CLM	no	Executable freshness self-enforcing
P20	26	Q20	Q16	BOTH	MATCH	none	CLM	no	Executable discoverability via live-source search
P21	27	Q21	Q17	BOTH	MATCH	none	CLM	no	Platform already implements Executable
P22	28	Q22	Q18	BOTH	MATCH	none	REL	no	Executable evidence pointers
P23	31	Q23	Q19	BOTH	MATCH	none	DEF	yes	Decision Knowledge definition
P24	32	Q24	Q20	BOTH	MATCH	none	INV	no	Capture window is decision time or never
P25	33	Q25	Q21	BOTH	MATCH	none	CLM	no	Decision consumers; agents cannot derive rationale from code
P26	34	Q26	Q22	BOTH	MATCH	none	MATH	yes	Canonical = accepted ∧ unsuperseded ∧ owned ∧ bound to code
P27	34	Q27	-	X	OMISSION	Y	REL	no	Binding grounded in the one longitudinal ADR success (B)
P28	35	Q28	Q23	BOTH	MATCH	none	CLM	no	Decision validation = enforcement-binding + review; hardest to validate
P29	36	Q29	Q24	BOTH	MATCH	none	CLM	no	Decision lifecycle immutable (PK-P5 regime 1)
P30	37	Q30	Q25	BOTH	MATCH	none	CLM	no	Retirement = supersession by link with reason
P31	38	Q31	Q26	BOTH	MATCH	none	CLM	no	Content does not stale; canonicity stales; status upkeep never measured
P32	39	Q32	Q27	BOTH	MATCH	none	INV	no	Decision knowledge must surface when governed code is touched
P33	39	Q33	-	X	OMISSION	Y	CLM	no	ADR consumption gap is a discoverability failure; readership never measured
P34	40	Q34	Q28	BOTH	MATCH	none	CLM	no	Platform is an existence proof of the Decision type
P35	41	Q35	Q29	BOTH	MATCH	none	REL	no	Decision evidence pointers
P36	44	Q36	Q30	BOTH	MATCH	none	DEF	yes	Definitional Knowledge definition
P37	45	Q37	Q31	BOTH	MATCH	none	CLM	no	Definitional producer = whole team; intermediaries break it
P38	46	Q38	Q32	BOTH	MATCH	none	CLM	no	Definitional consumers; rarely via glossary
P39	47	Q39	Q33	BOTH	MATCH	none	INV	no	Authority = conversation + code identifiers; glossary never authoritative
P40	47	Q40	-	X	OMISSION	Y	CLM	no	Living Glossary = tacit admission that hand-maintained glossaries fail
P41	48	Q41	Q34	BOTH	MATCH	none	CLM	no	Conversational validation; drift is a boundary signal
P42	49	Q42	Q35	BOTH	MISREADING	X	CLM	no	Source: rots when owning conversation stops (~2 yrs after OWNER DEPARTURE); X: rots ~2 yrs after the conversation stops (minor). Y quotes verbatim
P43	50	Q43	Q36	BOTH	MATCH	none	REL	no	Terms retire with their context
P44	51	Q44	Q37	BOTH	MATCH	none	CLM	no	Freshness = conversation cadence, unmeasurable from artifact
P45	52	Q45	Q38	BOTH	MATCH	none	CLM	no	Definitional discoverability: code + people
P46	53	Q46	Q39	BOTH	MATCH	none	CLM	no	Platform partially implements Definitional
P47	53	Q47	Q39	BOTH	MATCH	none	ASM	no	Conversational-validation assumption challenged when engineers are AI
P48	54	Q48	Q40	BOTH	MATCH	none	REL	no	Definitional evidence pointers
P49	57	Q49	Q41	BOTH	MATCH	none	DEF	yes	Descriptive Knowledge definition (runbooks = operational subtype)
P50	58	Q50	Q42	BOTH	MATCH	none	CLM	no	Survives only where producer and consumer are the same population
P51	58	Q51	-	X	OMISSION	Y	CLM	no	Second-population variants (wikis) decay; best-evidenced failure in corpus
P52	59	Q52	Q43	BOTH	MATCH	none	DEF	no	Descriptive authority = ownership + freshness date + review-with-code
P53	59	Q53	Q43	BOTH	MATCH	none	INV	no	Ownerless descriptive content is non-authoritative
P54	60	Q54	-	X	OMISSION	Y	CLM	no	Validation = review coupled to code change + regeneration (Y keeps only the caveat)
P55	60	Q55	Q44	BOTH	MATCH	none	CLM	no	Automation relocates staleness if source model unmaintained
P56	61	Q56	Q45	BOTH	MATCH	none	CLM	no	Regime 2; stale within 30-90 days without coupling
P57	62	Q57	Q46	BOTH	MATCH	none	INV	no	Staged ROT disposal, never silent abandonment
P58	63	Q58	Q47	BOTH	MATCH	none	CLM	no	Owner + last-verified stamp priced to half-life
P59	63	Q59	Q47	BOTH	MATCH	none	CLM	no	"Last edited" is a proxy, not validity
P60	64	Q60	Q48	BOTH	MATCH	none	CLM	no	Scent-dependent discoverability
P61	65	Q61	Q49	BOTH	MATCH	none	CLM	no	Runbooks validate by use
P62	65	Q62	-	X	OMISSION	Y	CLM	no	Runbooks = strongest consumption-feedback loop of any prose form
P63	66	Q63	Q50	BOTH	MATCH	none	CLM	no	Platform implements Descriptive as guides; wiki anti-pattern avoided
P64	67	Q64	Q51	BOTH	MATCH	none	REL	no	Descriptive evidence pointers
P65	70	Q65	Q52	BOTH	MATCH	none	DEF	yes	Experiential/Research definition; input-only until promoted
P66	71	Q66	Q53	BOTH	MATCH	none	CLM	no	Experiential producer; historical capture at project end
P67	72	Q67	Q54	BOTH	MATCH	none	INV	no	Consumed via promotion chain, never directly as authority
P68	73	Q68	Q55	BOTH	MATCH	none	INV	yes	No intrinsic authority until evaluated and promoted
P69	74	Q69	Q56	BOTH	MATCH	none	CLM	no	Claim evaluation at intake; absent gate = lessons-learned failure
P70	75	Q70	Q57	BOTH	MATCH	none	INV	no	Append-only; falsified claims retained (negative knowledge)
P71	76	Q71	Q58	BOTH	MATCH	none	INV	no	Never edited; superseded; archives retrievable
P72	77	Q72	Q59	BOTH	SCOPE	none	MATH	no	Born-stale (58.4%) dominates aging; intake validation ≥ review cadence. Y omits the ≥ relation; X types it MATH
P73	78	Q73	Q60	BOTH	MATCH	none	INV	no	Index by future task, not past project
P74	79	Q74	Q61	BOTH	MATCH	none	CLM	no	Platform implements Experiential with claim-evaluation gate
P75	80	Q75	Q62	BOTH	MATCH	none	REL	no	Experiential evidence pointers
P76	83	Q76	Q63	BOTH	MATCH	none	DEF	yes	Qualification/Evidence definition
P77	84	Q77	Q64	BOTH	MATCH	none	INV	no	Produced by instruments, never authors (I-9)
P78	85	Q78	Q65	BOTH	MATCH	none	CLM	no	Qualification consumers; dashboards derived-only
P79	86	Q79	Q66	BOTH	MATCH	none	DEF	no	Authority = instrument + recorded configuration; never editorial
P80	87	Q80	Q67	BOTH	MATCH	none	CLM	no	Run is the validation; re-validation = re-run
P81	88	Q81	Q68	BOTH	MATCH	none	CLM	no	Immutable record with verdict-history semantics
P82	89	Q82	Q69	BOTH	MATCH	none	CLM	no	Superseded as baseline; old measurement true of its moment
P83	90	Q83	Q70	BOTH	MATCH	none	CLM	no	Timestamped; staleness = referent drift detected by re-run
P84	91	Q84	Q71	BOTH	MATCH	none	CLM	no	Attached to referent; provenance chains traversable
P85	92	Q85	Q72	BOTH	MATCH	none	CLM	no	Fully implemented; platform's most mature type
P86	93	Q86	Q73	BOTH	MATCH	none	REL	no	Qualification evidence pointers
P87	96	Q87	Q74	BOTH	MATCH	none	DEF	yes	Tacit Knowledge definition
P88	97	Q88	Q75	BOTH	MATCH	none	CLM	no	Tacit producer: every practitioner, involuntarily
P89	98	Q89	Q76	BOTH	MATCH	none	CLM	no	Tacit consumers via interruption / apprenticeship
P90	99	Q90	Q77	BOTH	MATCH	none	DEF	no	Tacit authority = expertise recognition
P91	100	Q91	Q78	BOTH	MATCH	none	CLM	no	Validated by use + peers; not artifact-checkable
P92	101	Q92	Q79	BOTH	SCOPE	none	CLM	no	Tacit lifecycle; X types the sequence MATH, Y keeps only the departure-loss clause; both faithful
P93	102	Q93	Q80	BOTH	MATCH	none	CLM	no	No retirement possible; leaves with person
P94	103	Q94	Q81	BOTH	MATCH	none	CLM	no	Self-refreshing while practiced; proxy = truck factor
P95	104	Q95	Q82	BOTH	MATCH	none	CLM	no	Social discovery does not scale; succession only measured mitigation (~15-25%)
P96	105	Q96	Q83	BOTH	MATCH	none	INV	no	Tacit deliberately ungoverned as artifact (PK-P9); no capture mandates
P97	105	Q97	Q84	BOTH	MATCH	none	CLM	no	Session logs + reconstruction = partial rationale capture channel
P98	106	Q98	Q85	BOTH	MATCH	none	REL	no	Tacit evidence pointers
P99	112	Q99,Q100	Q86	BOTH	MATCH	none	REV	no	Architecture falsified: content domain; decomposes into Decision+Descriptive+Executable
P100	112	Q101	-	X	OMISSION	Y	CLM	no	r=0.41 for architecture docs sits between constituent strata
P101	113	Q102,Q103	Q87	BOTH	MATCH	none	REV	no	Business rule merged: encoded→Executable; un-encoded→Definitional/Descriptive; why→Decision
P102	113	Q103	-	X	OMISSION	Y	CLM	no	Un-encoded rules carry lower default authority (PK-P4); no separate behaviour found
P103	114	Q104,Q105	Q88	BOTH	MATCH	none	REV	no	Organizational falsified as artifact-governable; environment, not artifact class
P104	115	Q106	Q89	BOTH	MATCH	none	REV	yes	Historical is a STATUS, not a type
P105	115	Q107	-	X	OMISSION	Y	CLM	no	Any instance becomes historical via supersession/archival; historical = lifecycle position
P106	115	Q108	Q90	BOTH	MATCH	none	CLM	no	Treating historical as a type would re-break status-at-discovery (PK-P2)
P107	116	Q109,Q111	Q91	BOTH	MATCH	none	REV	no	Runtime: insufficient evidence; not classified (no-unsupported-concepts); open question
P108	116	Q110	-	X	OMISSION	Y	REL	no	Runtime's instrument-produced character suggests kinship with Qualification
P109	117	Q112	Q92	BOTH	MATCH	none	REV	no	Requirements merged (per evidence available) into Decision+Definitional+Executable
P110	117	Q113	-	X	OMISSION	Y	CLM	no	Executable-specification failure history (12% versioned); no distinct behaviour
P111	121-129	Q114	-	X	OMISSION	Y	MATH	yes	Classification matrix (per-type profile). X miscounts: 6 governance axes + platform status, not 7. Matrix marks Descriptive ◐ while L66 says implemented ✅ (source inconsistency, unnoted)
P112	131	Q115	Q93	BOTH	LABEL-ERROR	X	CLM	no	"Family thesis holds" is labelled INTERPRETATION in source; Y keeps the label, X states it as fact ("confirmed")
P113	131	Q116	-	X	OMISSION	Y	INV	no	Core applies to all rows; governance machinery must differ per row. This restates L11 (P10), but X types it INV inside an INTERPRETATION paragraph
P114	131	Q117	Q94	BOTH	MATCH	none	CLM	no	Platform implements/partially implements 6 of 7 rows for engineering knowledge (neither item carries the paragraph's INTERPRETATION label)
P115	131	Q118	-	X	OMISSION	Y	CLM	no	Strengthens, without deciding, the specialization-of-general-domain question
P116	135	Q119	Q95	BOTH	MATCH	none	CLM	no	Open Q1: runtime kinship needs first-party evidence
P117	135	Q120	-	X	OMISSION	Y	REL	no	Convergence of EPIC-002 and RQ-002 research threads
P118	136	Q121	Q96	BOTH	MATCH	none	CLM	no	Open Q2: agent-compatible analogue of conversational validation
P119	137	Q122	Q97	BOTH	MATCH	none	CLM	no	Open Q3: which regime governs which section of boundary artifacts (IDDs)
P120	141	Q123	-	X	OMISSION	Y	CLM	no	Success check: 7 types identified; per-type properties defined
P121	141	Q124	Q98	BOTH	MATCH	none	CLM	no	One-domain-or-family answered: family with constitutional core
P122	141	Q125	Q99	BOTH	MATCH	none	CLM	no	"4 falsified/merged, 1 insufficient-evidence". The source contradicts itself: L11 and the table give 5 falsified/merged. Y flags this explicitly; X hedges with "counted here as"
P123	141	Q126	-	X	OMISSION	Y	INV	no	Constraints honored; STOP awaiting ARB (STOP itself also at L4/P03)
```

## 2. Totals

| Measure | All | Foundational |
|---|---|---|
| Propositions | 123 | 17 |
| Captured by both (m) | 104 | 15 |
| X total (n_X) | 123 | 17 |
| Y total (n_Y) | 104 | 15 |
| X only | 19 | 2 |
| Y only | 0 | 0 |
| NEITHER | 0 | 0 |

Counts by class:

| Class | All | Foundational |
|---|---|---|
| MATCH | 99 | 15 |
| OMISSION (all by Y) | 19 | 2 (P08, P111) |
| MISREADING | 1 (X) | 0 |
| LABEL-ERROR | 1 (X) | 0 |
| SCOPE | 3 | 0 |
| BOTH-DEFENSIBLE | 0 | 0 |
| SPURIOUS | 0 | 0 |
| NEITHER | 0 | 0 |

Counts by reader_at_fault:

| reader_at_fault | All | Foundational |
|---|---|---|
| none | 102 | 15 |
| Y | 19 | 2 |
| X | 2 | 0 |
| both | 0 | 0 |

## 3. Most consequential disagreements

1. **P111 (L121-129), foundational OMISSION by Y.** Y does not capture the classification matrix, which is the document's one-page summary and the basis for the §3 reading. X's item also contains a small miscount: the matrix has 6 governance axes plus platform status, not 7.
2. **P08 (L11), foundational OMISSION by Y.** Y does not capture the claim that the types differ on every governance-relevant axis. This is the premise the per-type-governance conclusion (P10) rests on.
3. **P112 (L131), LABEL-ERROR by X.** The source heads this paragraph "Reading of the matrix (INTERPRETATION)". X turns "the family thesis holds" into a confirmed fact. Y keeps the label. In the same paragraph, X also types Q116 as an INV, and neither reader labels P114 (six of seven rows) as interpretation.
4. **P42 (L49), MISREADING by X (minor).** The source puts the "~2 years" relative to owner departure ("rots when the owning conversation stops (~2 years after owner departure)"). X places it after the conversation stops.
5. **P122 (L141) vs P07 (L11), source inconsistency, no reader fault.** L11 and the §2 table give five falsified/merged candidates plus one insufficient-evidence candidate. L141 says "4 falsified/merged, 1 insufficient-evidence". Y flags the contradiction; X only hedges. Neither reader noted two smaller source inconsistencies: the matrix marks Descriptive ◐ while L66 says implemented ✅, and L53 points to "open question §3" while the open questions are in §4.
6. **Non-foundational omissions by Y (17).** These are mostly evidence anchors and secondary clauses: P16, P27, P33, P40, P51, P54, P62, P100, P102, P105, P108, P110, P113, P115, P117, P120 and P123. Y's granularity is coarser, and in G1-G87 its meaning sentences are often bare labels.
