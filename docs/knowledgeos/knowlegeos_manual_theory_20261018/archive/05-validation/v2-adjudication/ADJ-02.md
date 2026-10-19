# ADJ-02 — Adjudication of WORKSHEET-02

Source: `docs/implementation/RQ-002_Knowledge_Meta_Model.md` (88 lines). Readers: X (62 items), Y (84 items). Every X and Y item is mapped to at least one proposition. Judged only against the source text.

## 1. Proposition table

```tsv
prop_id	lines	x_items	y_items	captured_by	class	reader_at_fault	type	foundational	note
P01	3	-	Y:Q1	Y	OMISSION	X	DEF	no	Document kind: synthesis artifact, modeling not design.
P02	3	X:Q1	Y:Q2	BOTH	SCOPE	none	ASM	yes	Hypothesis under test: seven types are projections of orthogonal dimensions. X types it CLM, Y types it ASM. Both faithful.
P03	4	X:Q2	Y:Q3	BOTH	MATCH	none	CLM	no	Status COMPLETE, presented for ARB review.
P04	4	X:Q2	Y:Q4	BOTH	SCOPE	none	INV	no	STOP gate: no Reference Architecture until the ARB rules. X folds it into the status item as a CLM; Y gives it a separate INV.
P05	5	X:Q3	Y:Q5	BOTH	MATCH	none	ASM	no	Closed evidence base, the RQ-002 chain. Y also lists the five artifacts.
P06	5	X:Q3	Y:Q6	BOTH	SCOPE	none	INV	no	"No new research; no unsupported concepts." X folds it into the ASM; Y gives it a separate INV.
P07	11	X:Q4	Y:Q7	BOTH	MATCH	none	CLM	yes	Hypothesis confirmed in refined form.
P08	11	X:Q5	Y:Q8	BOTH	MATCH	none	CLM	yes	Types are not peers; they decompose into 3 orthogonal dimensions + 1 scope dimension.
P09	11,57	X:Q6	Y:Q9,Y:Q54	BOTH	MATCH	none	REV	yes	Lifecycle regime and validation mechanism rejected as axes and treated as derived functions. Restated at L57 ("Derived, not orthogonal"), which Y:Q54 captures.
P10	11	X:Q7	Y:Q10	BOTH	MATCH	none	REV	yes	Seven types survive as archetypes, not primitives.
P11	11	X:Q7	Y:Q11	BOTH	SCOPE	none	DEF	yes	Archetype = densely populated cell of the dimensional space. Y gives it a separate DEF; X has it inline in the REV.
P12	14	X:Q8	Y:Q12	BOTH	BOTH-DEFENSIBLE	none	MATH	yes	Instance = NATURE x REPRESENTATION x GOVERNANCE-STATUS (x AUTHORITY-SCOPE). X reads it as a product of four dimensions; Y reads the parenthesis as an "optional" scope factor. The source's parenthesis is not explained; L53/L81 call scope "provisional", not "optional". Both readings are defensible.
P13	15	X:Q9	Y:Q13	BOTH	SCOPE	none	MATH	yes	lifecycle regime = f(Nature). X bundles both functions in one item.
P14	16	X:Q9	Y:Q14	BOTH	SCOPE	none	MATH	yes	validation method = f(Representation, Nature). X bundles both functions.
P15	21	X:Q10	Y:Q15	BOTH	MATCH	none	CLM	no	Smoking gun 1: the same rule got different D9 types depending on its encoding.
P16	21	-	Y:Q16	Y	OMISSION	X	INV	no	"One thing cannot change type by changing clothes": an item's type does not change with its encoding.
P17	21	X:Q11	Y:Q17	BOTH	MATCH	none	DEF	yes	A rule's identity is its Nature; its encoding is a Representation.
P18	21	X:Q12	Y:Q18	BOTH	SCOPE	none	REV	no	The Business-Rules merge was an unrecognized dimensional split. X types it CLM, Y types it REV.
P19	22	X:Q13	Y:Q19	BOTH	MATCH	none	CLM	no	Rationale gap = Decision-nature knowledge trapped in Tacit representation.
P20	22	X:Q14	Y:Q20	BOTH	MATCH	none	CLM	no	The problem is a cell (Decision x Tacit), not a type.
P21	22	X:Q15	Y:Q21	BOTH	SCOPE	none	REL	no	Remedy (capture at decision time) is a representation transition within an unchanged nature. X types it CLM, Y types it REL. Y's "tacit->recorded" agrees with L77.
P22	23	X:Q16	Y:Q22	BOTH	MATCH	none	CLM	no	D9 already extracted Historical as a status, not a type.
P23	23	-	Y:Q23	Y	OMISSION	X	REL	no	This is "exactly what pulling a dimension out of a flat classification looks like".
P24	23	X:Q17	Y:Q24	BOTH	SCOPE	none	CLM	no	Governance status escaped first; D10 extracts the rest. X types it REL, Y types it CLM.
P25	29	X:Q18	Y:Q25	BOTH	MATCH	none	CLM	no	Executable is a representation class hosting several natures (first conflation).
P26	30	X:Q19	Y:Q26	BOTH	MATCH	none	CLM	no	Decision = Decision nature in its canonical recorded representation.
P27	31	X:Q20	Y:Q27	BOTH	MATCH	none	CLM	no	Definitional = Definition nature with a dual representation (speech + code).
P28	32	X:Q21	Y:Q28	BOTH	MATCH	none	CLM	no	Descriptive looked primitive because nature and representation coincide.
P29	33	X:Q22	Y:Q29	BOTH	MATCH	none	CLM	no	Experiential's "input-only until promoted" is governance status, not nature.
P30	34	X:Q23	Y:Q30	BOTH	MATCH	none	CLM	no	Qualification: the instrument-only producer constraint is a property of the Measurement nature.
P31	35	X:Q24	Y:Q31	BOTH	SCOPE	none	REV	yes	Tacit was never a peer; it is a representation column for every nature ("strongest proof"). X types it CLM, Y types it REV.
P32	39	X:Q25	Y:Q32	BOTH	MATCH	none	DEF	yes	Dimension 1 NATURE (what knowledge asserts): six values + tacit-only Skill.
P33	40	X:Q26	Y:Q33	BOTH	MATCH	none	CLM	no	Evidence: each Nature value has a distinct authority behaviour and capture window.
P34	41	X:Q27	Y:Q34	BOTH	MATCH	none	CLM	no	Contradictory evidence: boundary artifacts (IDDs) bundle natures.
P35	41	X:Q27	Y:Q35	BOTH	SCOPE	none	INV	no	The model must permit composite artifacts of single-nature claims (PK-P3). X types it CLM, Y types it INV.
P36	43	X:Q28	Y:Q36	BOTH	MATCH	none	DEF	yes	Dimension 2 REPRESENTATION (how expressed): four values.
P37	44	X:Q29	Y:Q37	BOTH	MATCH	none	CLM	no	The trust gradient (r=0.67->0.03) is a representation gradient.
P38	44	-	Y:Q38	Y	OMISSION	X	CLM	no	Agent memory, RAG and docs findings (D) vary by representation at constant content.
P39	45	-	Y:Q39	Y	OMISSION	X	CLM	no	No contradictory evidence against the dimension; the only doubt is whether the value list is complete.
P40	45	X:Q30	Y:Q40	BOTH	MATCH	none	CLM	no	"Structured-data" is a candidate value, left unasserted.
P41	47	X:Q31	Y:Q41	BOTH	MATCH	none	DEF	yes	Dimension 3 GOVERNANCE STATUS (lifecycle position): five values.
P42	48	X:Q32	Y:Q42	BOTH	SCOPE	none	MATH	no	Four independent status systems map to one vocabulary. X types it CLM; Y types it MATH (the source uses the correspondence symbol).
P43	48	-	Y:Q43	Y	OMISSION	X	REL	no	D9's Historical falsification is cited as further evidence for Dimension 3.
P44	49	X:Q33	Y:Q44	BOTH	MATCH	none	CLM	no	No contradictory evidence against Dimension 3; the C3 dispute concerns transitions only.
P45	51	X:Q34	Y:Q45	BOTH	MATCH	none	DEF	yes	Dimension 4 AUTHORITY SCOPE (where canonicity holds): four values.
P46	52	X:Q35	Y:Q46	BOTH	MATCH	none	CLM	no	Scoped canonicity; global SSOT falsified; definitions are authoritative within their bounded context.
P47	52	-	Y:Q47	Y	OMISSION	X	CLM	no	The EM-001 product/platform ADR split is first-party evidence that decisions carry scope.
P48	53	X:Q36	Y:Q48	BOTH	MATCH	none	CLM	yes	Dimension 4 is the weakest-evidenced dimension and is marked provisional.
P49	56	X:Q38	Y:Q49	BOTH	SCOPE	none	MATH	no	Regime mapping: decisions->immutable+superseded, descriptions->evolve/regenerate, measurements->immutable records, skill->practice-bound. X types it DEF, Y types it MATH.
P50	56	X:Q37	Y:Q50	BOTH	LABEL-ERROR	Y	CLM	no	Source: "No evidenced counterexample of a nature freely choosing its regime -> recording regime as an axis would denormalize". Y:Q50 restates this as a fact ("Since no nature freely chooses its regime"), dropping the absence-of-evidence qualifier. X keeps the evidential form.
P51	56	-	Y:Q51	Y	OMISSION	X	REL	no	PK-P5 still stands, restated as "the regime is determined by the nature".
P52	57	X:Q39	Y:Q52	BOTH	SCOPE	none	MATH	no	Validation mapping: executable->execution, prose->review/regeneration, conversational->conversation, tacit->use. X types it DEF, Y types it MATH.
P53	57	X:Q40	Y:Q53	BOTH	SCOPE	none	INV	no	Nature override: Decision-nature requires enforcement-binding regardless of representation (Watson Discovery). X types it CLM, Y types it INV.
P54	63	X:Q41	Y:Q55	BOTH	MATCH	none	REL	yes	Nature x Representation: orthogonal but unevenly populated.
P55	63	X:Q42	Y:Q56	BOTH	MATCH	none	CLM	no	The empty or painful cells are the findings (Decision x Tacit; Constraint x Prose).
P56	64	X:Q43	Y:Q57	BOTH	MATCH	none	REL	yes	Nature x Governance status: orthogonal.
P57	65	X:Q44	Y:Q58	BOTH	MATCH	none	REL	yes	Representation x Status: orthogonal with one asymmetry (tacit cannot carry explicit status).
P58	66	X:Q45	Y:Q60	BOTH	MATCH	none	REL	no	Nature -> lifecycle regime: determined (restates P13).
P59	67	X:Q46	Y:Q61	BOTH	MATCH	none	REL	no	(Representation, Nature) -> validation: determined (restates P14).
P60	68	X:Q47	Y:Q62	BOTH	MATCH	none	REL	no	Representation strongly correlates with default authority; this restates PK-P4.
P61	65,70	X:Q48	Y:Q59,Y:Q63,Y:Q64	BOTH	MATCH	none	MATH	no	PK-P9 falls out of the model: no status -> cannot satisfy status-at-discovery -> not governable as artifact. Y has it at finer grain (L65 note, status change, chain).
P62	70	X:Q49	Y:Q65	BOTH	MATCH	none	CLM	no	A principle becoming a theorem is taken as evidence that the model fits reality.
P63	74	X:Q50	Y:Q66	BOTH	SCOPE	none	INV	yes	The status vocabulary (D3) is uniform across all knowledge (constitutional core). X types it CLM, Y types it INV. Neither notes the section 5 "direction only" frame (see P82).
P64	74	X:Q51	Y:Q67	BOTH	SCOPE	none	REL	no	Machinery split by dimension: validation per Representation, capture and lifecycle per Nature, canonicity per Scope. X states only that machinery splits by dimension; Y gives the mapping.
P65	74	X:Q51	Y:Q68	BOTH	MATCH	none	CLM	no	Four small mechanisms instead of seven type-sized ones.
P66	75	X:Q52	Y:Q69	BOTH	MATCH	none	CLM	no	Storage and retrieval decouple: instances are produced by nature and retrieved by task-driven filters.
P67	75	-	Y:Q70	Y	OMISSION	X	REL	no	Task-driven retrieval matches minimal-sufficient-context (PK-P6).
P68	76	X:Q53	Y:Q71,Y:Q74	BOTH	SCOPE	none	CLM	no	The seven archetypes remain the working vocabulary; humans and agents keep speaking in archetypes. X conveys this only through the model/ubiquitous-language item. Y:Q74 types it INV, which is acceptable.
P69	76	X:Q53	Y:Q72	BOTH	MATCH	none	DEF	yes	The dimensions are the model; the archetypes are the ubiquitous language.
P70	76	X:Q54	Y:Q73	BOTH	SCOPE	none	INV	no	Do NOT build a five-dimensional bureaucracy; the dimensions govern the machinery (PK-AD4 applied reflexively). X types it CLM, Y types it INV.
P71	77	X:Q55	Y:Q75	BOTH	MATCH	none	CLM	no	Representation transitions become first-class.
P72	77	-	Y:Q76	Y	OMISSION	X	CLM	no	The evidence's biggest wins are transitions (tacit->recorded, prose->executable, prose->regenerated).
P73	77	X:Q56	Y:Q77	BOTH	MATCH	none	CLM	no	A flat-type architecture cannot express transitions.
P74	81	X:Q57	Y:Q78	BOTH	SCOPE	none	CLM	no	Criterion 1: 3 orthogonal + 1 provisional scope + 2 derived functions. X types it CLM, Y types it MATH.
P75	82	X:Q58	Y:Q79	BOTH	MATCH	none	CLM	no	Criterion 2: each dimension has supporting AND contradictory evidence.
P76	83	X:Q59	Y:Q80	BOTH	MATCH	none	CLM	no	Criterion 3: all seven types decomposed; anomalies explained.
P77	84	X:Q60	Y:Q81	BOTH	SCOPE	none	MATH	no	Semantic foundation: 9 invariants + 10 principles + 4 dimensions + 7 archetypes + 2 derivation functions. X types it CLM, Y types it MATH.
P78	84	X:Q60	-	X	SPURIOUS	X	CLM	no	X:Q60 adds "(later eleven in the Constitutional Model)". The source has nothing about a later count of eleven or a Constitutional Model; this is outside knowledge presented as part of the item.
P79	86	-	Y:Q82	Y	OMISSION	X	CLM	no	Engineering Platform artifacts (qualification records, ADRs, guides) decompose identically into the same dimensional space.
P80	86	X:Q61	Y:Q83	BOTH	MATCH	none	CLM	no	Whether the platform is one bounded context of a General Knowledge Architecture is undecided and deferred to the architecture phase.
P81	88	X:Q62	Y:Q84	BOTH	SCOPE	none	INV	no	Closing STOP gate (restates P04). X types it CLM, Y types it INV.
P82	72	-	-	NEITHER	NEITHER	both	REV	no	The section 5 heading "Impact on the future architecture (direction only)" marks L74-77 as non-binding direction. Neither reader records this status label, and Y types several section 5 items as INV (Y:Q66, Y:Q73, Y:Q74).
```

## 2. Totals

Rows: 82. That is 81 source propositions plus 1 SPURIOUS row (P78), which is not a source proposition.

**Capture**
- Source propositions: 81
- Captured by both (m): 69
- X total (n_X): 69 source propositions, plus 1 spurious item (P78)
- Y total (n_Y): 80 (69 shared + 11 captured only by Y)
- Captured only by X (source propositions): 0
- NEITHER: 1 (P82)

**Counts per class**

| Class | Count | Propositions |
|---|---|---|
| MATCH | 45 | |
| SCOPE | 22 | |
| BOTH-DEFENSIBLE | 1 | P12 |
| OMISSION | 11 | P01, P16, P23, P38, P39, P43, P47, P51, P67, P72, P79 (X omitted all 11) |
| MISREADING | 0 | |
| LABEL-ERROR | 1 | P50 (Y) |
| SPURIOUS | 1 | P78 (X) |
| NEITHER | 1 | P82 |

**Counts per reader_at_fault**

| Reader at fault | Count | Made up of |
|---|---|---|
| X | 12 | 11 OMISSION + 1 SPURIOUS |
| Y | 1 | 1 LABEL-ERROR |
| both | 1 | 1 NEITHER |
| none | 68 | |

**Foundational subset (21 propositions):** P02, P07, P08, P09, P10, P11, P12, P13, P14, P17, P31, P32, P36, P41, P45, P48, P54, P56, P57, P63, P69.
- Capture: all 21 were captured by both readers (m = 21, n_X = 21, n_Y = 21, NEITHER = 0).
- Classes: MATCH 14, SCOPE 6, BOTH-DEFENSIBLE 1 (P12). There is no OMISSION, MISREADING, LABEL-ERROR or SPURIOUS among them.
- Reader at fault: none for all 21.

## 3. Most consequential disagreements

1. **P78 SPURIOUS (X), L84.** X:Q60 annotates the "9 invariants" with "(later eleven in the Constitutional Model)". That fact is not in the source. It is imported knowledge presented inside an extraction, which contaminates the evidence base.
2. **P50 LABEL-ERROR (Y), L56.** The source's argument for rejecting lifecycle regime as an axis is evidential: "No evidenced counterexample of a nature freely choosing its regime". Y:Q50 restates it as a fact ("Since no nature freely chooses its regime"), which makes the conclusion sound stronger than the evidence.
3. **P82 NEITHER, L72.** Section 5 is explicitly "(direction only)". Neither reader carries this status label. Y goes further and types section 5 directions as invariants (Y:Q66 uniform status vocabulary, Y:Q73 no bureaucracy, Y:Q74 keep speaking archetypes). That raises their status from architectural direction to rule. The L74 "(the constitutional core)" wording partly supports Y:Q66, so I did not class it as a separate LABEL-ERROR, but the missing frame matters.
4. **P12 BOTH-DEFENSIBLE, L14.** The parenthesised "( × AUTHORITY-SCOPE )" in the central formula is read as a full fourth factor by X and as optional by Y. The source only calls scope "provisional" (L53, L81), never optional. Both readings are defensible, and the ambiguity is in the source.
5. **X omissions (11, none foundational).** These are mostly evidence and traceability links: P43 Historical falsification as D3 evidence, P47 EM-001, P51 PK-P5, P67 PK-P6, P38 AI-side findings, P39 no evidence against D2. They also include P16 the "changing clothes" identity rule (L21), P72 transitions as the biggest wins (L77) and P79 platform artifacts decomposing identically (L86). X loses the principle-linkage layer (PK-P5, PK-P6) that ties this model back to the earlier principles.
