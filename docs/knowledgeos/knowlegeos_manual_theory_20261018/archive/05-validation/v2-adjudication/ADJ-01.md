# ADJ-01 — Adjudication of WORKSHEET-01

Source: `docs/implementation/RQ-002_General_Knowledge_Architecture_Constitutional_Model.md` (111 lines). Worksheet: `WORKSHEET-01.md` (X = 79 items, Y = 111 items).

Conventions applied consistently:
- Unit = proposition. When one reader's item carries several propositions and each is faithfully conveyed in its meaning sentence, every such proposition counts as captured (class MATCH if the other reader also captured it).
- SCOPE is used when a 1:1 pair is faithful but differs in type label (e.g. CLM vs INV), when one reader splits one proposition into several items, or when one reader's meaning sentence is materially coarser (the content sits only in the quote).
- A proposition is counted as captured by a reader only if the reader's *meaning sentence* conveys it; a quote alone does not count.

## 1. Proposition table

```tsv
prop_id	lines	x_items	y_items	captured_by	class	reader_at_fault	type	foundational	note
P001	3	-	Q1	Y	OMISSION	X	DEF	no	Document kind = constitutional modeling of the invariant structure of knowledge; X has no item for it.
P002	3,5	Q6	Q2,Q11	BOTH	MATCH	none	DEF	yes	Scope: "constitutional" = within software project knowledge; binding on every claim. Y records it twice (L3 as ASM, L5 as DEF).
P003	3	Q1	Q3	BOTH	MATCH	none	CLM	no	Terminal synthesis artifact of RQ-002.
P004	3	Q2	Q4	BOTH	SCOPE	none	CLM	no	NOT design. X CLM vs Y INV; both faithful.
P005	3	Q3	Q5	BOTH	MATCH	none	CLM	no	Model is deliberately timeless (X conveys it parenthetically in Q3).
P006	3	Q3	Q6	BOTH	MATCH	none	REL	no	Relational algebra : database engine analogy.
P007	4	Q5	Q7	BOTH	MATCH	none	CLM	no	Accepted as research by ARB (X omits "refined for epistemic honesty"; not material).
P008	4	-	Q8	Y	OMISSION	X	CLM	no	"complete enough to be falsified": in X:Q4 quote only, not in X's meaning sentence.
P009	4	Q4	Q9	BOTH	SCOPE	none	INV	no	Research freeze: changes only from pilot evidence. X CLM vs Y INV.
P010	4	Q5	Q10	BOTH	MATCH	none	CLM	no	Architectural adoption pending pilot evidence + ARB ruling.
P011	5	-	Q12	Y	OMISSION	X	CLM	no	No evidence from medicine/law/aviation/finance/education collected.
P012	5	Q7	Q13	BOTH	MATCH	none	ASM	no	Cross-industry validity = untested hypothesis.
P013	5	-	Q14	Y	OMISSION	X	CLM	no	The boundary strengthens the model (states what evidence covers).
P014	6	Q8	Q15	BOTH	SCOPE	none	ASM	no	Closed evidence base. X CLM (omits the six-document chain) vs Y ASM (full composition).
P015	13	Q9	Q16	BOTH	MATCH	none	MATH	yes	KNOWLEDGE SPACE = NATURE x REPRESENTATION x GOVERNANCE-STATUS (x AUTHORITY-SCOPE).
P016	13	-	Q17	Y	SCOPE	none	ASM	no	Y's flagged [I]: parenthesis = provisional; product form assumes orthogonality. Orthogonality is not stated in the source but correctly marked as inference; X not obliged to record it.
P017	18	Q10	Q18	BOTH	MATCH	none	DEF	yes	Knowledge Coordinate = position in the space, independent of any artifact.
P018	18	Q11	Q19	BOTH	SCOPE	none	INV	no	Retrieval matches coordinates, never artifact types. X CLM vs Y INV.
P019	19	Q12	Q20	BOTH	MATCH	none	DEF	yes	Knowledge Claim = atomic unit of knowledge.
P020	19	Q12	Q22	BOTH	MATCH	none	DEF	yes	Claim = content + Coordinate + Provenance + Status + Scent (X DEF item carries both P019/P020).
P021	19	Q13	Q21	BOTH	MATCH	none	REV	no	Claim promoted from Project Knowledge specialization to constitutional on ARB identity finding.
P022	19	Q14	Q23	BOTH	MATCH	none	CLM	no	Four-way smallest-unit convergence defines this object.
P023	19	Q15	Q24	BOTH	MATCH	none	INV	yes	Every specialization inherits the Claim unchanged.
P024	20	Q16	Q25	BOTH	MATCH	none	DEF	yes	Knowledge Instance = artifact at a coordinate; single claim or composite.
P025	20	Q17	Q26	BOTH	MATCH	none	INV	no	Composites governed claim-by-claim (freshness claim-by-claim).
P026	21	Q18	Q27	BOTH	MATCH	none	DEF	yes	Knowledge Flow = path of nature derivations and representation transitions.
P027	22	Q19	Q28	BOTH	MATCH	none	DEF	yes	Knowledge Query = retrieval of a region by coordinate predicates.
P028	22	Q19	Q29	BOTH	MATCH	none	REL	no	Purpose lives in the Query (consumer side). Carried inside X:Q19.
P029	22	Q20	Q30	BOTH	MATCH	none	REL	no	Query elevated to first-class as Context Assembly in Project Knowledge.
P030	23	Q21	Q31	BOTH	MATCH	none	DEF	yes	Knowledge Transition = deliberate move of one coordinate.
P031	24	Q22	Q32	BOTH	MATCH	none	DEF	yes	Knowledge Specialization = dense region; weights, never new dimensions.
P032	26	Q23	Q33	BOTH	MATCH	none	CLM	no	Everything else in the constitution is a statement about the space (X:Q23 conveys it as mapping).
P033	26	Q23	Q34	BOTH	MATCH	none	REL	no	Invariants constrain every point; rules govern moves and queries.
P034	26	Q23	Q35	BOTH	MATCH	none	REL	no	Lifecycle = trajectory of represented points; specializations = density maps.
P035	31	-	Q36	Y	OMISSION	X	MATH	yes	Knowledge Instance = NATURE x REPRESENTATION x GOVERNANCE-STATUS (x AUTHORITY-SCOPE); §2 restates the product for the Instance.
P036	32	Q24	Q37	BOTH	MATCH	none	MATH	yes	lifecycle regime = f(Nature).
P037	33	Q25	Q38	BOTH	MATCH	none	MATH	yes	validation method = f(Representation, Nature).
P038	36	Q26	Q39	BOTH	MATCH	none	DEF	yes	NATURE: six values (+ Skill, tacit-only).
P039	37	Q27	Q40	BOTH	MATCH	none	DEF	yes	REPRESENTATION: four values.
P040	38	Q28	Q41	BOTH	MATCH	none	DEF	yes	GOVERNANCE STATUS: five values.
P041	39	Q29	Q42	BOTH	MATCH	none	DEF	yes	AUTHORITY SCOPE: where canonicity holds; four values.
P042	39	Q29	Q43	BOTH	MATCH	none	CLM	no	Authority Scope provisional (thinnest evidence). X conveys "provisional" inside Q29.
P043	41	Q30	Q44	BOTH	MATCH	none	REV	no	Purpose hypothesis tested and disposed: real, but not an instance dimension.
P044	41	Q31	Q45	BOTH	MATCH	none	CLM	no	Creation-side purpose collapses into Nature (purpose shift spawns a new instance of a new nature).
P045	41	-	Q46	Y	OMISSION	X	CLM	no	First-party evidence: measurement baseline vs ratchet constraint.
P046	41	Q32	Q47	BOTH	MATCH	none	CLM	no	Consumption-side purpose is a property of the retrieval relation.
P047	41	Q33	Q48	BOTH	SCOPE	none	DEF	no	Purpose = consumer-side selector of Knowledge Queries. X DEF vs Y REL.
P048	43	-	Q49	Y	OMISSION	X	CLM	no	Confidence table exists per the ARB's epistemic-honesty requirement. Y's gloss ("explicit confidence levels", typed INV) is slightly stronger than the source but defensible.
P049	47	Q34	Q50	BOTH	MATCH	none	CLM	no	Dimensions constitutional: High.
P050	48	Q35	Q51	BOTH	MATCH	none	CLM	no	Invariants constitutional: High (each traced to >=2 fields).
P051	49	Q36	Q52	BOTH	MATCH	none	CLM	no	Governance rules: Medium-High (P4 domain-varying; P1/P2 strongest).
P052	50	Q37	Q53	BOTH	MATCH	none	CLM	no	Lifecycle: High.
P053	51	Q38	Q54	BOTH	MATCH	none	CLM	no	Authority-Scope as 4th dimension: Medium (provisional).
P054	52	Q39	Q55	BOTH	MATCH	none	CLM	no	Any claim beyond software project knowledge: Untested.
P055	54	Q40	-	X	OMISSION	Y	CLM	no	Heading announces "(ten)" invariants; eleven are listed (L56-66). X records the source statement and flags the internal inconsistency; Y has no item.
P056	56	Q41	Q56	BOTH	MATCH	none	INV	yes	I-1 Knowledge never silently disappears.
P057	57	Q42	Q57	BOTH	MATCH	none	INV	yes	I-2 Status transitions explicit, reasoned, visible at discovery.
P058	58	Q43	Q58	BOTH	MATCH	none	INV	yes	I-3 Provenance mandatory on every claim.
P059	59	Q44	Q59,Q60	BOTH	SCOPE	none	INV	yes	I-4 Contradictions visible; coexist at rest, resolve at consumption. Y splits into 4a/4b.
P060	60	Q45	Q61	BOTH	MATCH	none	INV	yes	I-5 Authority scoped; nothing claims global truth.
P061	61	Q46	Q62,Q63	BOTH	SCOPE	none	INV	yes	I-6 Freshness observable at discovery, priced per domain half-life. Y splits into 6a/6b.
P062	62	Q47	Q64	BOTH	MATCH	none	INV	yes	I-7 Supersession is a link, never an edit.
P063	63	Q48	Q65	BOTH	LABEL-ERROR	Y	INV	yes	I-8 No second-population artifact class. The source never defines "second-population"; Y asserts an unflagged gloss ("maintained by a population different from their consumers") as if stated. Needed [I].
P064	64	Q49	Q66	BOTH	MATCH	none	INV	yes	I-9 Quality evidenced, never asserted.
P065	65	Q50	Q67	BOTH	MATCH	none	INV	yes	I-10 Representation transitions deliberate, named, preserve nature and provenance.
P066	65	Q51	Q68	BOTH	MATCH	none	DEF	no	A silent transition (summary dropping a caveat, encoding changing the rule) is a defect.
P067	66	Q52	Q69	BOTH	MATCH	none	INV	yes	I-11 telos: knowledge exists only to satisfy Knowledge Needs.
P068	66	Q52	Q70	BOTH	MATCH	none	REL	no	Documentation is merely one possible representation.
P069	66	Q53	Q71	BOTH	MATCH	none	REV	no	Telos wording strengthened at review (ARB 2026-07-11) to separate knowledge and representation.
P070	66	Q54	Q72	BOTH	MATCH	none	REL	yes	Every other rule derives from the telos (P6, never-a-copy, ephemeral assembly, gap evidence, selective capture).
P071	66	Q55	Q73	BOTH	MATCH	none	CLM	no	Success metric = needs satisfied, not artifacts produced (X:Q55 carries P071+P072).
P072	66	Q55	Q74	BOTH	MATCH	none	CLM	no	Inversion of the supply-driven KM tradition whose failures the evidence documents.
P073	70	Q56	Q75	BOTH	MATCH	none	CLM	no	All ten PK-P1..P10 constitutional within the researched domain; none demoted.
P074	70	Q57	Q76	BOTH	LABEL-ERROR	Y	MATH	no	P5, P9 are theorems. Source: "P5 = f(Nature)". Y asserts "P5 from lifecycle = f(Nature)", an unflagged inference linking P5 to L32. X faithful.
P075	70	Q58	Q77	BOTH	MATCH	none	CLM	no	P4 constitutional as a gradient; cell population varies by specialization.
P076	70	-	Q78	Y	OMISSION	X	CLM	no	Every domain trusts encoded over prose; how much can be encoded differs.
P077	74	Q59	Q79	BOTH	SCOPE	none	DEF	yes	Eight-stage lifecycle with regime fork = f(Nature) at Evolution. X DEF vs Y MATH.
P078	74	Q60	Q80	BOTH	MATCH	none	CLM	no	Lifecycle is a cycle, not a pipeline (Y typed REV; defensible as rejection of pipeline framing).
P079	74	Q60	Q81	BOTH	MATCH	none	REL	no	Validation-is-use closes loop Consumption -> Qualification.
P080	74	Q60	Q82	BOTH	MATCH	none	CLM	no	An unconsumed instance cannot maintain quality.
P081	74	Q61	Q83	BOTH	MATCH	none	CLM	no	Tacit column follows parallel practice-bound path; never reaches Publication.
P082	74	Q61	Q84	BOTH	MATCH	none	MATH	no	Hence tacit is ungovernable as artifact (theorem, per invariant 2).
P083	78	Q62	Q85	BOTH	MATCH	none	MATH	yes	A flow is a sequence of two move types.
P084	78	Q62	Q86	BOTH	SCOPE	none	DEF	yes	Nature derivation = new instance of a new nature referencing its source. Definition is in X:Q62 quote only; X meaning coarser.
P085	78	Q62	Q87	BOTH	SCOPE	none	DEF	yes	Representation transition = same nature moved to a better representation. X coarser as for P084.
P086	78	Q62	Q88	BOTH	MATCH	none	REL	no	Consumption feedback (evidence -> finding) closes the flow into a loop.
P087	80	Q63	Q89	BOTH	MATCH	none	CLM	no	Platform's adopted loop parses exactly as such a path.
P088	80	Q63	Q90	BOTH	MATCH	none	CLM	no	The loop was adopted before the model existed (independent corroboration).
P089	80	Q64	Q91	BOTH	MATCH	none	CLM	no	Domain flows are paths through one space; paths differ, algebra does not.
P090	84	Q65	Q92	BOTH	MATCH	none	INV	yes	A specialization is defined by weights, never by new rules.
P091	84	Q65	Q93	BOTH	MATCH	none	DEF	yes	The four weights (a) dense cells (b) dominant validation (c) canon scope (d) risk-bearing stages.
P092	84	Q66	Q94	BOTH	MATCH	none	INV	yes	Specializations inherit space, invariants, rules, lifecycle unchanged.
P093	86	Q67	Q95	BOTH	MATCH	none	CLM	no	The constitution ends here; what follows is derived material.
P094	92	Q68	Q96	BOTH	SCOPE	none	CLM	no	Appendix A = observed dense regions / examples. X CLM vs Y REL.
P095	92	-	Q97	Y	OMISSION	X	CLM	no	Examples will move to their own architecture documents when derived.
P096	96	Q69	Q98	BOTH	MATCH	none	CLM	no	Engineering Knowledge profile.
P097	97	Q70	Q99	BOTH	MATCH	none	CLM	no	Project Knowledge profile.
P098	98	Q71	Q100	BOTH	MATCH	none	CLM	no	Runtime Knowledge carries INSUFFICIENT EVIDENCE flag inherited from D9.
P099	98	-	Q100	Y	OMISSION	X	CLM	no	Runtime Knowledge profile (Observation x Recorded instrument-produced; instrument runs; Project; Publication/Retirement). X records only the flag.
P100	99	-	Q101	Y	OMISSION	X	CLM	no	Business Knowledge profile (cells, validation, Constitutional canon, risk stages). X:Q72 says only "example".
P101	99	Q72	Q102	BOTH	MATCH	none	CLM	no	Anonymity invariants as fitness tests = first-party proof of Business-Constraint x Executable.
P102	103	Q73	Q103	BOTH	MATCH	none	CLM	no	Ten platform artifact classes decompose with no residue and no new dimensions.
P103	103	-	Q104	Y	OMISSION	X	MATH	no	Explicit coordinate assignments (ADR-AIP-01, merge-gate, OQ-ENG-001, ...). Y faithful; note the source itself uses values outside the §2 value sets (see §3).
P104	103	Q74	Q105	BOTH	MATCH	none	CLM	no	Platform independently converged on invariants 1/2/7/9.
P105	105	Q75	Q106	BOTH	MATCH	none	CLM	no	Engineering Platform = first implemented bounded context (verified within the domain).
P106	105	Q76	Q107	BOTH	SCOPE	none	INV	no	Model conclusion, not a migration order; R-37, rule parsimony, P1 forbid restructuring. X CLM (no reasons) vs Y INV.
P107	109	Q77	Q108	BOTH	MATCH	none	CLM	no	Complete enough to derive domain architectures within the researched domain.
P108	109	Q77	Q109	BOTH	SCOPE	none	REL	no	Order Project -> Engineering -> Runtime with gates (E-1, zero refactoring, EPIC-002). X says only "in a stated order".
P109	109	Q78	Q110	BOTH	MATCH	none	CLM	no	Open items: E-2, E-3, Authority-Scope consolidation, cross-industry claim.
P110	111	Q79	Q111	BOTH	SCOPE	none	INV	no	STOP: ARB review precedes any domain-specific architecture. X CLM vs Y INV.
```

## 2. Totals

All propositions (110):

| Measure | Count |
|---|---|
| Propositions | 110 |
| Captured by both (m) | 96 |
| Captured by X only | 1 (P055) |
| Captured by Y only | 13 |
| n_X (X total) | 97 |
| n_Y (Y total) | 109 |
| NEITHER | 0 |

| Class | Count |
|---|---|
| MATCH | 80 |
| SCOPE | 15 |
| OMISSION | 13 (12 omitted by X, 1 omitted by Y) |
| LABEL-ERROR | 2 (both Y) |
| MISREADING | 0 |
| BOTH-DEFENSIBLE | 0 |
| SPURIOUS | 0 |
| NEITHER | 0 |

| reader_at_fault | Count |
|---|---|
| X | 12 |
| Y | 3 |
| both | 0 |
| none | 95 |

Foundational subset (37):

| Measure | Count |
|---|---|
| Foundational propositions | 37 |
| Captured by both (m) | 36 |
| n_X | 36 |
| n_Y | 37 |
| NEITHER | 0 |
| MATCH | 30 |
| SCOPE | 5 (P059, P061, P077, P084, P085) |
| OMISSION | 1 (P035, omitted by X) |
| LABEL-ERROR | 1 (P063, Y) |
| MISREADING / SPURIOUS / BOTH-DEFENSIBLE | 0 |
| reader_at_fault | X 1 · Y 1 · none 35 |

## 3. Most consequential disagreements

1. **P063, L63 (I-8), LABEL-ERROR, Y, foundational.** The source states "No knowledge capability may create a second-population artifact class" and never defines "second-population". Y:Q65 states the definition as fact ("maintained by a population different from their consumers") without [I]. This is an interpretation of an undefined term inside a constitutional invariant. X records the wording only.
2. **P035, L31, OMISSION by X, foundational.** §2 restates the product as "Knowledge Instance = NATURE × REPRESENTATION × GOVERNANCE-STATUS ( × AUTHORITY-SCOPE )", which differs from L13 where the left side is the Space. X has no item for it. The Space-vs-Instance equation is part of the central formal relations.
3. **P055, L54, OMISSION by Y.** The heading says "(ten)" invariants, but eleven are listed (I-11 telos was added on 2026-07-11). X recorded this internal inconsistency; Y did not. The confidence row at L48 ("The invariants are constitutional") therefore covers an ambiguous set.
4. **P074, L70, LABEL-ERROR, Y.** The source says "P5 = f(Nature)". Y:Q76 states without [I] that P5 is derived "from lifecycle = f(Nature)". This link to L32 is plausible but not stated.
5. **X's non-foundational omissions (P001, P008, P011, P013, P045, P048, P076, P095, P099, P100, P103).** Several are epistemically relevant:
   - P008 (L4): the model is "complete enough to be falsified". The text is in X's quote but not in X's meaning sentence.
   - P013 (L5): the scope boundary strengthens the model.
   - P076 (L70): "every domain trusts encoded over prose".
   - P103 (L103): the explicit coordinate decompositions, which are the evidence behind the "no residue" claim.
6. **Adjudicator observation (not counted as a proposition).** At L103, the source's own decompositions use values outside the §2 value sets: "Runtime" as an Authority-Scope value (L39 lists only Context-local · Project · Platform · Constitutional), "composite" as a Nature, and "Canonical-with-verdict-history" and "Canonical-Stable" as Governance Status values. This weakens "no residue and no new dimensions" (L103). Neither reader flagged it; Y:Q104 reports the assignments faithfully.
