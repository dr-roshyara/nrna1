# Revisions to baselined chapters

*Per the continuity rule (`03-methodology/M-04-working-practice-20261018.md`): only the affected claim is revised. Each entry records the five elements.*

**Chapter 1 baseline v1:** 2026-10-18, sha256 `4d65261d18f6fdb1…` (before the entries below).

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision needed? |
|---|---|---|---|---|---|---|---|
| R1-1 | Ch. 1 §1.14.5, confirmation requests | "…is not a block, provided the person asked holds a grant covering the decision." | adds: "**and the confirmation is recorded as an act** (who, when, under which grant). A confirmation that leaves no record … is a report, not an act (INV-A1). It cannot lift a block." | new evidence: PC L159–161 (a UI confirmation is a report; an override must be a recorded act), consistent with INV-A1 and I-2 | substantive, within the chapter | §1.14.5 conditions; glossary "enforcement consequence"; Ch. 2 §2.9 (override) | no; a refinement consistent with existing axioms |
| R1-2 | Ch. 1 §1.17.5, first consequence | "…context assembly must filter by may-read before relevance … whatever its relevance." | adds: PC's "context assembly policy" rule that the policy **filters and never elevates**; linked to §1.7; provenance part pointed to Ch. 0 §0.7; OQ-25 still open | new evidence: PC L118–128 | addition (new evidence), cross-reference | none on conclusions; supports the may-read hypothesis without settling it | no |
| R1-3 | Ch. 1 §1.4, development | "an open question is a gap plus a statement of what **evidence** would close it" (unchanged, kept) | adds a refinement: four kinds of closer (act, prior decision, work, evidence); questions form a dependency graph; **answered ≠ closed**; question identity | new evidence: DR L14–27, L61–133, L159–160, L199–201 | substantive refinement within the chapter | none on other conclusions; consistent with §1.6 and §1.10 | no |
| R1-4 | Ch. 1 §1.16.1 | (no statement on check-then-act) | adds: check and effect belong to one atomic change; the same applies to authority; publish only after commit | new evidence: DE L58, L342–352 | addition | supports the §1.14.1 currency condition; no conclusion changed | no |
| R1-5 | Ch. 1 §1.16.4 | five kinds of record, plus the missing act record | adds the four event kinds; audit event ≠ domain event; a vague "knowledge updated" event violates P-7 | new evidence: DE L15–37, L78–96 | addition | none | no |
| R1-6 | Ch. 1 §1.16.6 | pinning: pin, detect or forbid | adds: retention follows from reconstructability plus pinning (derived); no time-to-live on a pinned referent | new derivation (R + pinning), with the source's EVT-5 as evidence: DE L398–408 | addition (derivation) | a requirement on record keeping (cf. grant records outside version control, §1.13.2) | no |
| R1-7 | Ch. 1 §1.16.8, "presence is not validity" | a present reference is evidence of a claim of authority | adds: the reference must resolve to an act **made and recorded by its human author, independently of the relying mechanism** (usually before; if in the same atomic change, the record shows the human author). *Look-back correction: "recorded before" conflicted with §1.16.1's single atomic change* | new evidence: DE L113–131, L277–301 | substantive refinement | strengthens §1.14.2 (records, never grants) | no |
| R1-8 | Ch. 1 §1.7 | the visibility rule vis(d) ⊆ ∩ vis(sᵢ) | adds: the rule is **necessary, not sufficient**; inferred relationships and combinations can be more sensitive than their sources (aggregation problem); derived items need their own classification, undetermined if missing | new evidence (AG L63–69) and a counterexample to sufficiency (Ded) | substantive refinement | §1.17.5 (may-read), Ch. 0 §0.7 (assembly) | no |
| R1-9 | Ch. 1 §1.6, theorem instances | instances of "X ≠ authority" | adds: "a similarity score is only a relevance signal …" [AG L53]; ranking can only follow filtering by visibility, applicability and standing | new evidence (AG L53) | addition (example) | none | no |
| R1-10 | Ch. 1 §1.17.5, classification | "Chapters 0–2 govern standing and grant scope. **Neither says who may know**." | "UPDATE OR REFINEMENT of §1.7 …"; the confidentiality class identified as §1.7's visibility label | **demonstrated contradiction** with §1.7 within the baseline (found while integrating AG) | contradiction repair, within the chapter | §1.17.5 hypothesis now builds on §1.7; nothing else changes | no; a consistency repair |
| R1-11 | Ch. 1 §1.9 | ladder and the two verdict vocabularies (TR) | adds a census of 20 contracts by realization (1 compiled · 2 blocking · 8 checked · 6 advisory · 3 convention); the compiled one is ARCH-INV-1 in `Verdict::emittable()` (verified); the partial-support scale exists in `AssessmentService` (verified) | new evidence: SI L206–231, code verified | addition (evidence) | supports §1.9 and §1.14.2; informs OQ-28 | no |
| R1-12 | Ch. 1 §1.12 | self-reports are claims (examples from pilot 2) | adds three silent fail-open incidents, header/path drift, registry intention vs inventory; the platform's meta-rule "refuse to run when the policy cannot be read" (verified) | new evidence: SI L117–126, L160–167, L263–273, L334–341, L392 | addition (evidence) | Ch. 2 §2.8.8 (E6c must fail closed) | no |
| R1-13 | Ch. 1 §1.16.1 | one owner per invariant | adds: several enforcement mechanisms are compatible with one owner if one is declared authoritative or their relation is declared; duplication vs defence in depth | new evidence: SI L293–332; derivation | refinement | §1.11 ownership | no |

**Chapter 0 (reviewed in the audit of Chapters 0–2; treated as a baseline).**

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision needed? |
|---|---|---|---|---|---|---|---|
| R0-1 | Ch. 0 §0.2 | framings of "what KnowledgeOS names" | adds AG's "governed state-and-analysis system" with its terms mapped to the book; marked as a summary of the platform layer, not settling the open question | new evidence: AG L15–26 | addition | none | no |

**Corrections after the Principal's checkpoint review (2026-10-18).** These are derivation defects in the revisions above; the wording was not the issue.

| # | Where | Previous | Corrected | Reason | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| R1-3a | Ch. 1 §1.4 | "sorts them by **what closes them**"; "a closing criterion names one of four kinds of **closer**"; but also "only an act closes" (inconsistent) | the four are **prerequisites** before the closing act ("needs only a human act", DR L20); closure is an act; factual vs decision questions noted as open | **demonstrated inconsistency** (Principal) resolved from the source | derivation correction | none beyond §1.4 | the factual-question point is open |
| R1-6a | Ch. 1 §1.16.6 | "cannot be deleted"; "**No time-to-live may apply** to a pinned referent" | retention **for as long as the pinning claim may be checked**; permanence needs a further assumption (indefinite checkability) not derived here | overclaim of the derivation (Principal) | derivation correction | a retention policy, if wanted, is an organisational decision | no |
| R1-11a | Ch. 1 §1.9 | "(**verified**: Verdict.php …)" presented as the compiled contract | "verified **by reading the code**; not tested"; establishes **one boundary** of ARCH-INV-1, not the whole invariant | overclaim of what compilation shows (Principal) | derivation correction | none | no |

**Corrections after the batch-4 independent review (2026-10-18).** The reviewer (a fresh read-only session of the same model family, given claims and evidence but not the lead's conclusions) returned **DEFECTIVE** with findings F1–F25. Its code claims were re-checked in the code before anything was changed. Only material findings are recorded here; wording was not revised for its own sake.

| # | Where | Previous | Corrected | Reason | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| R1-11b | Ch. 1 §1.9 | census presented as "the realization ladder measured"; "most contracts sit low on it"; "compiled" contract "realized by a type restriction … (verified)" | a census of **enforcement modes**; "ladder measured" and "most sit low" withdrawn (11 of 20 sit at "checked" or above); "compiled" = **one runtime guard on one path** (`Assessment::of()` L38–42, read in the code); K-14 and K-15 marked INFERRED by SI; PARTIALLY_SUPPORTED stated as a fact, not as support for OQ-28; PC's third vocabulary noted | F1 contradiction with Ch. 2 §2.9 (realization ≠ enforcement); F2 arithmetic; F3, F12, F18 status | derivation correction | none beyond §1.9; ARCH-INV-1 claim narrowed to one boundary | no |
| R1-12a | Ch. 1 §1.12 | "every one is a self-report diverging" | the divergence is between the **wiring declaration** and behaviour; plus the design-check defaults limit | F17, F15 (verified `design-check.sh` L42–46) | derivation correction | Ch. 2 §2.8.8 fail-closed paragraph | no |
| R1-13a | Ch. 1 §1.16.1 | one invariant, "the P-7 failure" | "**potential** duplicate ownership" (SI's word), two readings: duplication (K-11 vs K-6) or overlap | F6 misreading (SI L215, L220) | traceability correction | none | no |
| R1-7a | Ch. 1 §1.16.8 | DE's rule on approval references (its OQ-33, DE L301) called a "narrower version" of the book's rule | the two are **incomparable**: DE forbids recording during the activating transaction; the book allows it and relies on recorded authorship. Kept as two concepts with the trade-off (DE is more machine-checkable) | F11 | alternative preserved | none | the choice is open |
| R1-5a | Ch. 1 §1.16.4 | "the list omits the anchor" after a four-item table; "the only record not produced by the system" | "**AB's five-item** list omits the anchor"; the **act**, not its record, is the one not produced by the system; audit fields include request identity and integrity protection; projection emits notifications, never facts; ordering not assumed | F14, F23 (DE L85, L174, L380) | placement and derivation correction | links to §1.14.1 repair input (recorded order) | no |
| R1-8a | Ch. 1 §1.7 | AG's example reduced to aggregation | two cases: a **stored** restricted edge (the intersection bound handles it if the edge counts as a source) and an **inferred** edge (aggregation) | F20 | derivation correction | none | no |
| R1-9a | Ch. 1 §1.6 | "ranking can only follow filtering" | filtering must happen **before use** | F19: the stronger order does not follow | derivation correction | none | no |
| R1-3b | Ch. 1 §1.4 | "two root questions block 11 others"; prerequisites as alternatives | the roots block **7** questions (DR's count of 7 for OQ-1 includes four non-questions); prerequisites combine by **conjunction and disjunction**; **two closure rules**: a decision closes by a valid act; a factual question by evidence meeting its kind's standard, recorded by an act | F5 (DR L103, L105, L123), Principal's question 1 | derivation correction | §1.16.8 (standards of evidence) | no |
| R1-6b | Ch. 1 §1.16.6 | pinning of inputs | also pin the **version of the deciding logic** (`policy_version`); an idempotency identity includes the version read | F22(a), F23 (PC L67–69; DE L364–368) | addition (missed evidence) | none | no |

**Chapter 2 (reviewed in the audit of Chapters 0–2; treated as a baseline).**

| # | Where | Previous | Corrected | Reason | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| R2-1 | §2.8.8 "a second domain" | "one epistemic rule … the reason E3–E6 license only 'cannot determine'" | a **shared premise**, not one rule (INV-A3 decides, the delivery rule only constrains conclusions); **E2, E3 and E6** | F4 contradiction with the licensing paragraph; F16 | contradiction repair | none | no |
| R2-2 | §2.8.8 fail-closed | the cure stated without limit ("verified L6") | the guard rejects non-integers but missing keys get hard-coded defaults; the named failure "E2 replaced by a value" passes | F15, verified by reading `design-check.sh` L42–46 | missed evidence | Ch. 1 §1.12 | no |
| R2-3 | §2.8.8 | (none) | a **ruled, recorded absence** (R-42) is a determinate, justified E1, not a gap; ninth kind vs recorded E1 open, AI recommends recorded E1 | F21 (SI L139–148, L202) | addition | gap reports | no |
| R2-4 | §2.9 properties | fourth property "**evaluability**: when it can be checked" | **required consistency guarantee** (atomicity): how tightly the check must be bound to the change | F7: DE's values are consistency requirements; a condition can be checkable at any time and still need atomic enforcement | derivation correction | glossary; Ch. 1 §1.16.6 referent time | no |
| R2-5 | §2.9 override | "a further independent property" | a fifth property **defined only for blocking gates** | F13: independence false; count inconsistent | derivation correction | glossary | no |
| R2-6 | §2.9 results | NOT_APPLICABLE taken over from PC unexamined | two readings: (i) analysis inapplicable (E6-like); (ii) proven disjoint (E1), under which PC's gate rule contradicts the licensing rule. Open; AI recommends reporting (ii) as proven consistent | F9 | alternative preserved | glossary row added | the reading PC intends is open |
| R2-7 | §2.9 EVT-1 | "evaluability fixes where a check may live"; EVT-1 limited to ATOMICALLY_REQUIRED; "the realization ladder read downward" | the **required guarantee** fixes the place; EVT-1 includes SAME_CONTEXT_TRANSACTION; a declared-vs-actual guarantee gap, **not** a change of realization; atomicity holds only within one transaction boundary; a repeated check closes the gap only if atomic with the effect | F7; Principal's question 3 | derivation correction | Ch. 1 §1.16.1 (authority check and effect cannot share one change while grant records sit outside version control) | no |
| R2-8 | §2.9 OQ-24 | DE's recasting presented as the question made concrete | the recasting is **narrower**: a later re-analysis can find a conflict against an active rule even under HARD; the hard/advisory choice decides only whether activation may precede its own analysis | F8 | derivation correction | reserved decision 5 unchanged | no |

## Corpus batch C-1 (S0001–S0007), 2026-10-18

**Basis:**
- the Principal's instruction "Begin Corpus Research and Construct the KnowledgeOS Theory" (2026-10-18), which names `06-theory/manuscript` as the destination;
- the rules agreed ("i agree the rules", ≈21:40Z);
- M-04 v1.0 steps 3–7.

**Pre-edit hashes:** Ch. 0 `c2b74357…`, Ch. 1 `562230ad…`, Ch. 2 `c4eb8452…`.

**Evidence:** the verified per-source records `tmp/corpus-reading/records/E-S0001…E-S0007.md` (every quote passes the anchor check) and the dispositions in `tmp/theory/R01-BOOK-INTEGRATION-S0001-S0005.md` and E-S0006/E-S0007 §14.

**Standing of the sources:** all seven declare themselves *CANDIDATE — NOT ADOPTED; Generated — never authoritative without human review*. They were read on the Principal's in-session instruction.

**Nature of the changes:** every change is an **addition**. No existing sentence is changed or removed; no definition changes meaning.

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision needed? |
|---|---|---|---|---|---|---|---|
| RC-1 | Ch. 0 §0.1, end | — | corpus naming of "model amnesia" and its link to knowledge vaporization | new evidence: S0002 L17; S0007 L21 | addition (evidence) | supports §0.1's core statement | no |
| RC-2 | Ch. 0 §0.3, end | — | rule validity vs document status; the eight knowledge states; model-to-platform gap; Hyp: two dimensions | new evidence: S0004 L36 | addition + hypothesis | bears on Ch. 1 §1.2 and §1.16.2 | no (hypothesis marked; T0-07) |
| RC-3 | Ch. 0 §0.5, end | — | invariant numbers are document-local; "governance precedes automation" unnumbered in canon; separation ≠ ordering | new evidence: S0001 L9; S0003 L85; S0007 L33, L56 | addition | citation discipline for the whole book | no (T0-08) |
| RC-4 | Ch. 0 §0.7, end | — | the reserved knowledge-decision catalogue (2026-07-11); the codification challenge (Open) | new evidence: S0006 L54; S0007 L43 | addition | supports §0.7; one open challenge | no (T0-09) |
| RC-5 | Ch. 1 §1.1, end | — | "a decision is a specification" | new evidence: S0006 L22 | addition (evidence) | none | no |
| RC-6 | Ch. 1 §1.2, end | — | the corpus formula, its parts, external support, pair reading; repetition counted once | new evidence: S0002 L37; S0003 L56; S0007 L26 | addition; **Int** pair reading | confirms §1.2 | no (T1-31) |
| RC-7 | Ch. 1 §1.6, end | — | two keys (evidence earns, governance grants); authority acts outside the engineering catalogue by design | new evidence: S0002 L37; S0006 L36; S0007 L28 | addition; **Int** necessary condition | adds the evidence side to §1.6 | **targeted review recommended**: it adds a necessary condition beside the axiom (foundational candidate) |
| RC-8 | Ch. 1 §1.8, end | — | survival ≠ sufficiency; the loop broken at generation; the rule of three vs n ≥ 2 | new evidence: S0002 L50–52; S0005 L23, L55–57, L72, L76; S0007 L25 | addition | refines "the loop has never closed" | no (T1-32, T1-37) |
| RC-9 | Ch. 1 §1.10, end | — | identifier collisions in the corpus; one home per rule | new evidence: S0001…S0005 records §10; S0006 L21, L76–78 | addition | citation rule; new principle (one home per rule) | **targeted review recommended** for "one home per rule" (new principle) |
| RC-10 | Ch. 1 §1.11, end | — | operating-unowned defect; ownership as boundary evidence; viewpoints as projections; spaces Hyp | new evidence: S0001 L21–L38; S0006 L88 | addition; counterexample qualifier | refines ownership | no (T1-33, T1-34) |
| RC-11 | Ch. 1 §1.16, end | — | external validation cannot close an internal link | new evidence: S0005 L85 | addition; qualifier | §1.16.8 acceptance | no |
| RC-12 | Ch. 2 §2.9, end | — | deciding ≠ enforcing (PAP/PDP/PEP); DP-n hypothesis | new evidence: S0003 L39, L42; S0005 L19; S0007 L35 | addition | supports §2.9 | no (T2-06) |
| RC-13 | Ch. 2 §2.9, after RC-12 | — | DP-n defined (the named policy a capability executes; 1:1); capabilities advisory, enforcement runtime-side (41 controls) | new evidence: S0008 L25, L38, L67 | addition; resolves T2-06 | supports §2.10 and T2-05 | no |
| RC-14 | Ch. 1 §1.9, end | — | four strands instead of one chain; prompt = Execution Asset (resolves T1-36); one meaning per term, with the KnowledgeOS-owner tension recorded | new evidence: S0008 L10, L12, L22, L47–55, L63–76 | addition; **Int** on where the strands meet | refines §1.1/§1.5/§1.9 | **targeted review recommended** for the four-strand structure (foundational candidate) |
| RC-15 | Ch. 1 §1.11, after RC-10 | — | a product space must not hold reusable method (I-7, Relationship Ontology); the rule is breached | new evidence: S0008 L23, L24, L64 | addition (evidence + Open, T1-38) | supports the method/binding/evidence split | no |
| RC-16 | Ch. 0 §0.3, after RC-2 | — | the meta-model: kind × dimensions; nine candidate archetypes; the category test (encoding or scope change ⇒ not a kind); scope must stay a dimension; status as persistence; U-MM-1 as a well-formedness question | new evidence: S0009 L24–25, L46, L55–58, L65–73, L110–111 | addition + open | extends §0.3's model; no existing definition changed | **targeted review recommended** (foundational: the shape of the knowledge model) |
| RC-17 | Ch. 1 §1.8, the "Lead for the corpus" paragraph | the lead (unchanged) | quoted note: the two added dimensions named; "13 concepts" located; rejected dimension still open; domains-own-capabilities refuted; two lifecycles never conflated | new evidence: S0009 L43, L53, L69, L100 | addition (partly answers a recorded lead) | §1.11, §1.14.1, Ch. 2 §2.9 | no |
| RC-18 | Ch. 1 §1.5, end | — | metadata ≠ knowledge (descriptive vs prescriptive); implemented in L-A fields; orthogonal to method/binding/evidence; two knowledge bodies | new evidence: S0010 L13, L37, L87–90, L129 | addition (new distinction) | §1.2 (provenance and standing on the prescriptive side), §1.11 | **targeted review recommended** (a new distinction beside two foundational ones) |
| RC-19 | Ch. 1 §1.8, end | — | the loop's unfired entry event (retrospective); two diagnoses on two edges; the EEP's "does not approve itself" | new evidence: S0010 L52–56, L110, L121–123 | addition | RC-8; §1.6; §1.14.3 | no |
| RC-20 | Ch. 1 §1.17.2, before §1.17.3 | — | corpus evidence for reserved decision 4: "the artifacts record the act, never the reason"; the cost of readings (a)/(b)/(c); the decision stays reserved | new evidence: S0011 L113 | addition (evidence for a reserved decision) | reserved decision 4 (no decision taken) | **Principal decision remains reserved** |
| RC-21 | Ch. 1 §1.14.4, end | — | derived vs authoritative decided by lifecycle (regenerated vs accepted); `authority: derived` never applied | new evidence: S0011 L49–62 | addition + Int refinement of P-8 | §1.14.4 | no |
| RC-22 | Ch. 1 §1.16.8, end | — | falsification against artifacts; falsified ≠ unevidenced, the first worse | new evidence: S0011 L10, L133 | addition | §1.16.8 | no |
| RC-23 | Ch. 1 §1.11, after RC-15 | — | four tests of a real boundary; an uninstantiated type is a description | new evidence: S0011 L30, L40–45, L70, L78–84 | addition; Int (collection of tests) | refines RC-10 | **targeted review recommended** (boundary tests) |
| RC-24 | Ch. 1 §1.2, after RC-6 | the rule cited second-hand ("cited-only") | the primary source read; value sets given; standing of the rule → Src; T1-31 partly resolved | new evidence: S0012 L152 | traceability upgrade (cited-only → Src) | §1.2 | no |
| RC-25 | Ch. 1 §1.14.4, after RC-21 | — | derivation ≠ attestation; status governed, authority not; P-8 reconciled with the hospital case | new evidence: S0012 L16–22, L67–80 | addition; Int reconciliation | §1.6, §1.14.1, §1.14.4 | **targeted review recommended** (reconciliation of an invariant) |
| RC-26 | Ch. 1 §1.11 and Ch. 0 §0.3 | — | spaces nest (deployment = nested space); nine lifecycles; the lifecycle document vs enum conflation | new evidence: S0012 L48–61, L93, L118–136, L158 | addition | supports RC-10's Hyp structurally; T0-07 evidence | no |

## Corrections after the independent review of S0006–S0010 (2026-10-19)

**Basis:**
- the review (AR-11, standing approval);
- report `tmp/review/C1-S0006-S0010/PHASE2-REVIEW-REPORT.md` (verdict SOUND WITH MINOR ISSUES; 3 foundational, 28 ordinary findings);
- blind extraction frozen at sha256 `957b3812…`.

**The findings were checked against the sources before acceptance:** F-01, F-02, F-04, F-08, F-10 and F-21 were spot-verified, the rest by reading the cited lines. **All three foundational findings are accepted.** Book-level ordinary findings are corrected below; record-level findings are corrected in the records' §15.

| # | Where | Previous | Corrected | Finding | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| RC-14a | Ch. 1 §1.9 | "Four strands, not one chain"; strand names as [Src]; "three edges … were rejected"; one-owner tension for KnowledgeOS only | "One corrected chain, read here as four strands"; strand names marked **Int**; edges graded as in the source (1 wrong, 1 hypothesis, 1 imprecise, 2 reattributed or partly, the rest hold); I-7's home document not attributed; the Runtime Adapter added to the one-owner tension | F-01 (foundational), F-06, F-07, F-09 | label correction + misreading correction | the four-strand structure is now explicitly the book's interpretation | targeted review of the Int still recommended |
| RC-19a | Ch. 1 §1.8 | "Two diagnoses, two edges, no contradiction"; S0010's stall placed on the back-edge; "the one finding"; EEP quote "before the August corpus" | a table of the conflicting counts (S0010 "0 traversals" vs n≈3 informal / ≈2 formal; S0013 rejects the generalization); reconciliation by scope marked **Int**; "no contradiction" withdrawn; "the ONLY OKF insight"; the EEP quote undated | F-02 (foundational), F-23, F-04 | contradiction made visible; overstatements removed | §1.8's "the loop has never closed" now reads: the head n = 0; the back-flow disputed (T1-45) | no |
| RC-18a | Ch. 1 §1.5 | "adopts a name for a third distinction"; "rest on repository evidence"; "provenance and standing both fall on the prescriptive side" | "third distinction" marked **Int**; the example and the name second-hand; challenge: provenance is descriptive, so `authority` straddles the line; the prescriptive-side sentence **withdrawn**; T1-44 | F-03 (foundational), F-20 | substantive correction (a withdrawn Int) | RC-18 no longer places §1.2's provenance on the prescriptive side | targeted review of T1-44 when evidence arrives |
| RC-7a | Ch. 1 §1.6 | "an earlier (2026-07-11) statement … predates the August corpus" | quote undated in the source; no precedence claim | F-04 | label correction | none | no |
| RC-4a | Ch. 0 §0.7 | "(ARB, 2026-07-11) already reserved"; "anticipated … before the August discovery work" | undated quoted table; S0006's "a month before" marked as its assertion; the earlier wording withdrawn | F-04 | label correction | none | no |
| RC-8a | Ch. 1 §1.8 | "a deliberate choice" | "the corpus's own bar ('the precise bar is ours')" | F-15 | overstatement removed | none | no |
| RC-10a | Ch. 1 §1.11 | the ARB "siblings" statement as support for "projections" | supports non-hierarchy only; "projections" is S0006's gloss in a partly supported hypothesis; relayed | F-05 | quote scope corrected | the "viewpoints are projections" conclusion rests on less | no |
| RC-13a | Ch. 2 §2.9 | "a DP-n is the rule … a capability enforces"; misquote "unique, needs operational proof" | "executes"; capabilities advisory; verbatim "UNIQUE HYPOTHESIS — needs operational proof" | F-08 | misreading + misquote corrected | none | no |
| RC-16a | Ch. 0 §0.3 | "the category test that produced them"; kinds without grades | the kinds come from convergence; the test rejected candidates; grades (container demoted, purpose candidate, the source's internal inconsistency); the governance-asset demotion MEDIUM-HIGH | F-16, F-17 | overstatement removed; grades restored | none on definitions | no |
| RC-17a | Ch. 1 §1.8 note | "the two added candidate dimensions are evidence-status and operational-state" as Src | the identification with GA's "2 new" marked Int | F-18 | label correction | none | no |
| RC-15a | Ch. 1 §1.11 | "I-7 of the Relationship Ontology" | I-7's home document not stated by S0008 | F-07 | label correction | none | no |

**Not adopted as changes:** F-29…F-32 (both readings defensible; kept as they are, with F-30's "authoritative" qualifier already in the text).
| RC-27 | Ch. 1 §1.6 | — | canon is human-made only; AI output tops out at candidate ("engineering never accepts its own work"); knowledge state ≠ decision outcome | new evidence: S0013 L17, L22, L112 | addition (evidence for the axiom) | §1.6; T0-07 | no |
| RC-26a | Ch. 1 §1.11, after RC-26 | "resolved on evidence" (S0012) | contested by S0013 (H-7 "unevidenced"); both kept; undetermined | new evidence: S0013 L54, L75 | contradiction made visible | RC-26, RC-10's Hyp | no (T1-43) |
| RC-28 | Ch. 0 §0.5, after RC-3 | — | "governance precedes automation" listed as canonical via a DRAFT instrument; undetermined | new evidence: S0013 L40, L61 | addition (contradiction recorded) | T0-08 | no |
| RC-29 | Ch. 1 §1.13, end | — | the register is not the complete record of authorizations; two remedies; consequence for recognition (act ≠ record) | new evidence: S0014 L53, L68 | addition (major for recognition) | §1.13.1, §1.17.3; T1-46 | **targeted review recommended** (bears on the recognition rule) |
| RC-30 | Ch. 1 §1.14.3, after the acceptance rule | — | separation of duties real as process, unevidenced as people; independence = process independence unless a person is evidenced | new evidence: S0014 L57, L72 | addition (a limit on a rule) | §1.14.3; §1.16.8; this book's own reviews | **targeted review recommended** |
| RC-31 | Ch. 1 §1.8, RC-19a table | — | an S0014 row: "0 traversals" retired; agreed fact n≈3 informal, ≈2 formal; DA acknowledgement outstanding | new evidence: S0014 L43 | addition | T1-45 → Under Research | no |
| RC-32 | Ch. 0 §0.5, after RC-28 | — | "governance precedes automation" weighed: candidate is the better-supported reading; the engineering invariant set listed | new evidence: S0014 L35, L44 | addition (weighs a contradiction) | T0-08, T0-13 | no |
| RC-33 | Ch. 1 §1.13, after RC-29 | — | the corpus first proposed "authorizations exist only if minted" (S0015), then reopened it (S0014): the recognition choice inside the corpus | new evidence: S0015 L58, L104 | addition (evidence on a reserved choice) | reserved recognition choice (C1) | no (reserved) |
| RC-34 | Ch. 1 §1.14.5, end | — | the paradox of a proposed constitution: applying proposed rules as binding is an unrecorded adoption or enforcement without ratification | new evidence: S0015 L66; S0014 D-4 | addition (paradox recorded; T1-47) | §1.14.5 | **targeted review recommended** |
| RC-35 | Ch. 1 §1.14.3 (after RC-30); Ch. 2 §2.10 | — | author ≠ reviewer practised (an isolated blind review; prediction matched measurement); protocol-only governance failed once (PMR-10), evidence not a mandate to automate | new evidence: S0015 L9–10, L33, L72, L77; S0014 L36 | addition | RC-30; §2.10 | no |

## Corpus batch C-2 (S0011–S0015): revision index, added afterwards (repair of the record)

*Added 2026-10-18T22:29Z (UTC) after an independent finding that RC-27…RC-35 sit under the review C1 correction heading without a batch heading, basis or pre-edit hashes. **Nothing above is rewritten.** This section indexes the C-2 rows where they already stand and adds the facts that were missing.*

**Timestamps:**
- all times are **UTC**, from the session transcript's tool-call times;
- the local clock (CEST, UTC+2) showed 2026-10-19 for edits after 22:00Z. This is why some headings above (e.g. "Corrections after the independent review … (2026-10-19)") carry the local date. **The UTC date of all these edits is 2026-10-18.**

### Which rows belong to C-2, and where they stand

| Rows | Source | Edit time (UTC) | Where the row stands in this file | Chapters touched |
|---|---|---|---|---|
| RC-20 … RC-23 | S0011 | 21:54:33–21:54:49 | under "Corpus batch C-1 (S0001–S0007)" (mis-filed: S0011 is not in C-1) | Ch. 1 §1.11, §1.14.4, §1.16.8, §1.17.2 |
| RC-24 … RC-26 | S0012 | 21:56:10–21:56:26 | under "Corpus batch C-1" (mis-filed) | Ch. 1 §1.2, §1.11, §1.14.4; Ch. 0 §0.3 |
| RC-26a, RC-27, RC-28 | S0013 | 22:07:04–22:07:13 | under "Corrections after the independent review of S0006–S0010" (mis-filed) | Ch. 1 §1.6, §1.11; Ch. 0 §0.5 |
| RC-29 … RC-32 | S0014 | 22:15:49–22:16:19 | under "Corrections after the independent review" (mis-filed) | Ch. 1 §1.8, §1.13, §1.14.3; Ch. 0 §0.5 |
| RC-33 … RC-35 | S0015 | 22:17:51–22:18:11 | under "Corrections after the independent review" (mis-filed) | Ch. 1 §1.13, §1.14.3, §1.14.5; Ch. 2 §2.10 |

**Also mis-labelled:** the heading "Corpus batch C-1 (S0001–S0007)" covers rows up to RC-19, i.e. sources **S0001–S0010**. RC-13 … RC-19 come from S0008–S0010. The heading's range is wrong; its rows are correct.

**Interleaving, stated plainly.** The review C1 corrections (RC-4a … RC-19a, 22:04:39–22:06:00Z) were made **between** the S0012 edits (21:56Z) and the S0013 edits (22:07Z). C-2 is therefore **not a contiguous block** of edits.

### Basis, evidence and review status

| Item | Value |
|---|---|
| Basis (method) | M-04 v1.0 steps 3–7; the Principal's "Begin Corpus Research and Construct the KnowledgeOS Theory" (21:40:26Z), naming `06-theory/manuscript` |
| **Editing authority at the time** | RC-20 … RC-28 (21:54–22:07Z) were made **before** D-R5 was recorded (22:08:58Z), and RC-20 … RC-26 before D-C1 (21:59:52Z). RC-29 … RC-35 (22:15–22:18Z) were made **after** D-R5. Whether D-R5 covers the earlier edits is **open** (decision record, D-R5 addendum: "Open, for the Principal") |
| Evidence | records E-S0011 … E-S0015 (`tmp/corpus-reading/records/`), every quote anchor-checked |
| Change type | all **additions**, except RC-26a (a contradiction note appended after RC-26). No existing sentence was changed by C-2 rows |
| Checkpoint | `06-theory/manuscript/review/BATCH-C2-checkpoint-S0011-S0015.md` |
| Independent review | review C2 **in progress** (Phase 1 frozen: `review/C2-S0011-S0015/PHASE1-BLIND-EXTRACTION.md`, sha256 `2fe7eb9f…3272`). **Until it returns, RC-20 … RC-35 are not independently reviewed** |

### Hashes (only values actually recorded at the time; none reconstructed)

| Point in time (UTC) | Ch. 0 | Ch. 1 | Ch. 2 | Recorded where | Completeness |
|---|---|---|---|---|---|
| before C-1 (21:40Z) | `c2b74357b7f4567cde6440e81c6cc7311296460875a7fc5d00a6749ef1244fad` | `562230ad6780848c21f92e25c7c88c9287c476461fda3ac625751df08fffc54a` | `c4eb8452e97fd100275d1baacd11f5b750ac5232fcfb4362c3184e11aab2b0be` | session transcript, sha256sum output at 21:41Z | full |
| after C-1 = **before C-2** (21:49:34Z) | `1108b26b54d47cd0…` | `4849d704855aaa4b…` | `a04ed072aea44593…` | transcript (output cut to 16 hex digits) | **prefix only; the full hashes were not recorded and are unavailable** |
| after the C1 corrections + S0013 (22:07:26Z) | `860d8a4939cfbb1e…` | `a810cb2ae9a85631…` | `1348266ebd0e9f3e…` | transcript | **prefix only** |
| **after C-2** (22:18:26Z) | `63d359d0812f0a2acf53bffb9333a1c17ac136183d55dc163e098ab479acd04d` | `477b47aeccb2cba6892ab7ccfe183a5603957674c06d38d05ab27847ad399b31` | `7e6e8c208cec42066825876f4d330a8aa04fc607ae2e6258f9bc270bd61df825` | prefixes recorded at 22:18:26Z; full values computed now (the files are unchanged since: the current hashes' prefixes match) | full |

**Not recoverable:**
- a hash **between** the S0012 edits (21:56Z) and the C1 corrections (22:04Z) was never taken;
- so the C-2 changes from S0011–S0012 cannot be separated by hash from the C1 corrections.

The per-row edit times above, and the rows' own text, are the evidence for that separation.

## Batch C-2: corrections after independent review C2 (S0011–S0015), 2026-10-18 UTC

**Basis:**
- review C2 (AR-11 standing approval): `review/C2-S0011-S0015/PHASE2-REVIEW-REPORT.md`, verdict SOUND WITH MINOR ISSUES (4 foundational, 25 ordinary); Phase 1 frozen at sha256 `2fe7eb9f…3272`;
- **F-01** was verified against the sources before acceptance (S0015 L112 IR-1; S0014 L53 "commissions that authorize work must mint");
- all four foundational findings were **accepted**.

**Pre-edit hashes** (full, computed immediately before these corrections):
- Ch. 0 `63d359d0812f0a2acf53bffb9333a1c17ac136183d55dc163e098ab479acd04d`;
- Ch. 1 `477b47aeccb2cba6892ab7ccfe183a5603957674c06d38d05ab27847ad399b31`;
- Ch. 2 `7e6e8c208cec42066825876f4d330a8aa04fc607ae2e6258f9bc270bd61df825` (unchanged by these corrections).

**Note on the table above:** the rows RC-27 … RC-35 directly above stand without a table header after the "Not adopted" paragraph (F-25). The C-2 index section above explains them; nothing is moved.

| # | Where | Previous | Corrected | Finding | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| RC-33a | Ch. 1 §1.13 | "The corpus first proposed the strict rule [S0015], then reopened it [S0014]"; "only if minted" mapped to "only recorded acts count" | **withdrawn and rewritten**: both sources carry a **forward** minting rule beside two options for the existing gap; "only if minted" is a record-keeping obligation, not the strict recognition rule | F-01, F-02 (foundational) | misreading corrected | the corpus does **not** take the strict recognition position; C1 stays open | no (C1 reserved) |
| RC-29a | Ch. 1 §1.13 (RC-29 consequence) | "misses authorizations that were given but never recorded" | **unregistered, not unrecorded**; "as the register stands" vs "as all records stand" | F-03 (foundational) | misreading corrected | T1-46 corrected | no |
| RC-21a | Ch. 1 §1.14.4 | heading "Derived or authoritative is decided by lifecycle"; quote "nothing there is validated" | heading "A projection is decided by lifecycle"; reconciled with §1.2: projection (lifecycle) ≠ derived (provenance) ≠ authoritative (standing); verbatim quote | F-04 (foundational), F-18 | omission (reconciliation) + quote | RC-21 now consistent with RC-24/RC-25 | no |
| RC-24a | Ch. 1 §1.2 | "×" as a pair unlabelled; S0012 called the primary source | marked Int; qualifier: S0012 L152 points to S0018; S0012 is multi-temporal | F-06, F-07 | label correction | none | no |
| RC-23a | Ch. 1 §1.11 | four tests of a real boundary; status "Int" | test 2 carries S0011's own n = 1 caveat and DEFER; test 4 withdrawn as a boundary test; CV-1 result recorded (not validated as a set) | F-16, F-17; CV-1 | misreading + omission | the boundary tests are now three, with caveats | no |
| RC-26b | Ch. 1 §1.11; Ch. 0 §0.3 | nesting "resolved on evidence"; the Ch. 0 conclusion without the source's hedge | "a structural argument"; hedge restored ("an observation, not a defect claim") | F-08, F-19 | overstatement removed | T1-43 unchanged | no |
| RC-30a | Ch. 1 §1.14.3 | "different AI sessions take the roles under one human" | "under one human" marked Int | F-12 | overstatement removed | none | no |
| RC-35a | Ch. 1 §1.14.3 | "its predicted outcome matched the measured one … the strongest methodological corroboration" | prescription stated; "strongest" attributed to the source's self-assessment | F-13 | overstatement removed | none | no |
| RC-34a | Ch. 1 §1.14.5 | "either (a) … or (b)" | four readings (a)–(d); "applied as binding" not shown | F-28; CV-1 | overstatement removed | T1-47 unchanged | no |
| RC-20a | Ch. 1 §1.17.2 | "in the recorded practice of the platform, acts are recorded without reasons" | one act (n = 1); generalization marked Int | F-15 | overstatement removed | reserved decision 4 unchanged | no |
| RC-32a | Ch. 0 §0.5 | "validated by two blind derivations" | the source's self-description, qualified (one-way blindness; "Most of our 11 invariants") | F-14 | overstatement removed | T0-13 unchanged | no |

**Record-level findings** (F-20 … F-29 except F-25 and F-28): corrected in the records' §15, append-only.

**Both-defensible findings** (F-10, F-11): kept. F-11's caveat (S0014 B-2's evidence is S0015 row 11, not independent; S0013 probably postdates S0014) is noted in E-S0014 §15.

**Post-edit hashes (C-2 corrections):** Ch. 0 `8e1c95de247ec8adf746d2627c3674bf088465a6e274209e850c85e4bfd14b85` · Ch. 1 `6d04d3423e1359be04f1ef78533c8e242c4270702c398ffcfe65857f6382d8da` · Ch. 2 unchanged.

## Corpus batch C-3, part 1 (S0016–S0018), 2026-10-18 UTC

**Basis:**
- D-R5 (writing into chapters; recorded 22:08:58Z);
- CV-1 (concept IDs; recorded 22:20:49Z);
- records E-S0016 … E-S0018, every quote anchor-checked; concept IDs in their §16.

**Pre-edit hashes** = the post-edit hashes of the C-2 corrections above (Ch. 0 `8e1c95de…4b85`, Ch. 1 `6d04d342…d8da`).

**New chapter 3:** `chapter_03/03.00…03.04` (section files per ML-1, each ending with References). Written earlier in this batch; concepts CON-0001…CON-0011.

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| RC-36 | Ch. 1 §1.2, after RC-24a | — | the primary statement: authority = immutable provenance fact × mutable standing judgment; CON-0016…0018; T1-31 settled at its source | new evidence: S0018 L77–96 | addition (primary source) | §1.2 confirmed; T1-31 → Resolved (pair) | **targeted review**: next five-file review (C3) |
| RC-37 | Ch. 1 §1.6, after RC-7 | — | granted vs earned: the two keys are causally distinct (CON-0019, CON-0020) | new evidence: S0018 L61–68, L121 | addition + Ded | §1.6; planned Ch. 4 | review C3 |
| RC-38 | Ch. 1 §1.14.4, RC-25 | — | the chain is "qualification"; the act changes standing; CON-0023 invalid | new evidence: S0018 L17, L94 | precision note | RC-25; T4-02 | no |
| RC-39 | Ch. 1 §1.14.5, after RC-34a | — | freezes declared without authority, procedure or reversal; CON-0015 invalid | new evidence: S0017 L55 | addition | T1-48 | no |
| RC-40 | Ch. 1 §1.17.1, end | — | decision-readiness (CON-0012); engineer vs governance decisions (CON-0013, CON-0014); catalogue vs log | new evidence: S0017 L39, L90–92 | addition | §1.17.1; planned Ch. 5 | review C3 |
| RC-41 | Ch. 0 §0.3, after RC-16 | — | pointer to Chapter 3 | Ch. 3 written | cross-reference | none | no |

**Post-edit hashes (C-3 part 1):** Ch. 0 `e1ab9a0444e20509ab67415ffa4cba7f5b4becc9c73b4b2f43b095b80087dd2d` · Ch. 1 `b6ea49879fbb9afbc21986eafa76f89ad712506af3a5283553b3b594c07c55c9` · Ch. 2 `7e6e8c208cec42066825876f4d330a8aa04fc607ae2e6258f9bc270bd61df825`

## Corpus batch C-3, part 2 (S0019–S0020), 2026-10-18 UTC

**Basis:** D-R5; CV-1; records E-S0019, E-S0020 (quotes checked; concept IDs in §16).

**Pre-edit hashes** (= the post-edit hashes of C-3 part 1): Ch. 0 `e1ab9a0444e20509ab67415ffa4cba7f5b4becc9c73b4b2f43b095b80087dd2d` · Ch. 1 `b6ea49879fbb9afbc21986eafa76f89ad712506af3a5283553b3b594c07c55c9` · Ch. 2 `7e6e8c208cec42066825876f4d330a8aa04fc607ae2e6258f9bc270bd61df825`).

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| RC-42 | Ch. 1 §1.11, after RC-26a | — | three authority systems (policy · method & implementation · domain knowledge); no spanning authority (CON-0026; T1-50) | new evidence: S0019 L100–107 | addition | authority theory: subject-partitioned grant scopes | **review C3** (foundational candidate) |
| RC-43 | Ch. 0 §0.2, end | — | two identities of KnowledgeOS (CON-0027 validated, CON-0028 invalid); why vs what; mission vs constraint (CON-0024); enacted mission (CON-0025 invalid); responsibilities and non-responsibilities (N-1 breached) | new evidence: S0019 L23–90, L157, L173 | addition | Ch. 0 §0.2 open question partly answered | review C3 |
| RC-44 | Ch. 1 §1.14.4, after RC-21a | — | projection defined by DP-2 (CON-0033); `derived` ranked beside `authoritative` = the fusion PM-1 addresses | new evidence: S0020 L28, L89 | addition | RC-21a | no |
| — | **new file** Ch. 3 §3.5 `chapter_03/03.05-ontology-layers.md` | — | three ontology layers (CON-0029…0031), declared scope (CON-0032), projection (CON-0033) | S0020 | new section (ML-1) | Ch. 3 | review C3 |

**Post-edit hashes (C-3 part 2):** Ch. 0 `7b25f6815a5b00606d3315e2d7eb0696bf6dead3413ca73bc8cc2ec8789c5c47` · Ch. 1 `d2fa5310aa9e2f44cda90aea55ceb6dae3762fb6afa3cac40f6da3764484f325` · Ch. 2 unchanged · Ch. 3 §3.5 `db50a5807dcfb4206e1cf514808316aca1d58d2c9dbeca67c17e29810cd5853a`

## Batch C-3: corrections after review C3 (2026-10-18 UTC)

**Basis:** review C3 report `review/C3-S0016-S0020/PHASE2-REVIEW-REPORT.md` (sha256 `ef0aba23a037388bf471fe3f2c4d39a5bbe5024874da6d6e2bc55f469fce848d`), verdict **UNSOUND** (book integration; records SOUND WITH MINOR ISSUES). Every cited source line of F-01…F-06 was re-checked against the sources before acceptance.

| # | Finding | Where | Correction (applied) | Disposition |
|---|---|---|---|---|
| RC-36a | F-01, F-02 (foundational) | Ch. 1 §1.2; Ch. 1 TODO T1-31 | S0018 is *consistent with* the pair reading of "×", does not define it; T1-31 "Resolved (Int)", criterion not met by evidence; CON-0018 → INVALID (several senses of "standing"; T1-52) | accepted |
| RC-37a | F-03 (foundational) | Ch. 1 §1.6 Ded | "no act can decree an evidential progression; evidence alone cannot effect a governance-act progression"; counterexamples recorded for CON-0019/0020 | accepted |
| RC-40a | F-05 (foundational) | Ch. 1 §1.17.1 | CON-0012 → INVALID (no governing record; S0017 self-grade L5; T1-51); U-DM-4 added; 77-rulings sentence marked as unsampled generalization | accepted |
| RC-42a | F-04 (foundational) | Ch. 1 §1.11 | "partitioned" withdrawn: subjects differ, holders overlap (ARB in POLICY and METHOD); only domain-knowledge questions lack a common holder | accepted |
| RC-43a | F-06 (foundational), F-07, F-24 | Ch. 0 §0.2 | "settled" withdrawn; CON-0027 → INVALID (T0-17); CON-0025 reason restated (three temporal layers); CON-0024 → INVALID (T0-16) | accepted |
| RC-44a | F-14, F-16, F-17 | Ch. 1 §1.14.4; §3.5 | DP-2 attributed to CAP-002 (DEFERRED); "dissolved" → "proposes to dissolve (PM-1, undecided)", both readings kept; "later" deleted | accepted |
| — | F-15, F-18, F-19, F-20, F-25 | §3.5 | "FALSIFIED" restored; key-collision claim narrowed; L-A/L-C overlap recorded; L-B membership inconsistency and possible staleness recorded; CON-0029, CON-0032 → INVALID (T3-07, T3-08) | accepted |
| — | F-09, F-10, F-11, F-12, F-13 | §3.1 | "Review C3 notes" added; CON-0006 → INVALID (MM-4; T3-09); CON-0003 reason restated; CON-0009 basis re-based | accepted |
| — | F-21 | §3.4 | CON-0010 → INVALID (conflict with S0020 L49; T0-05; T3-10) | accepted |
| — | F-22, F-26 | Ch. 1 §1.14.5, §1.14.4 | freeze claim attributed to S0017 with in-scope counter-evidence; attestation-act reading labelled Int | accepted |
| — | F-28, F-29 | §3.2; §3.0 index | "sponsor-intended" restored; S0020 added to the index's sources | accepted |
| — | F-08, §8a table | register | invalidity reasons restated as failed checks (CON-0003, 0007, 0011, 0015, 0025, 0028, 0030, 0031); CON-0033 standing stated | accepted |
| — | F-23, F-27, F-30, F-31, F-32 | records E-S0016…E-S0020 §17 | added to the records; CON-0017 counterexample and CON-0023 split recorded in the register history for the next use | accepted (records); the CON-0023 split deferred to its next use |

**Register after C3:** 19 of 33 concepts INVALID; book tags checked against the register (no mismatch).

| File | Pre-edit sha256 | Post-edit sha256 |
|---|---|---|
| `chapter_00/CH-00-foundations.md` | `7b25f6815a5b00606d3315e2d7eb0696bf6dead3413ca73bc8cc2ec8789c5c47` | `6b951d9d5ebc04b17e1a8dd3dc24ed4d05e86e296fcc247c0ac8e8412ae1a8f9` |
| `chapter_01/CH-01-knowledge-authority-execution.md` | `d2fa5310aa9e2f44cda90aea55ceb6dae3762fb6afa3cac40f6da3764484f325` | `5a107bbf1b80490e4d17621ad7d76a998e3b5ce6c68586b1c01ff39c0506108a` |
| `chapter_03/03.01-dimensions.md` | `f7b25f4bfb703a1926234822428c3ec51cc86e9314b5c22bc96b83f4342a0b2c` | `9f2770f214839c8913915a1fa708352536bd383f9b844a31fd86a5fe78a9983e` |
| `chapter_03/03.02-derived-attributes.md` | `38aeaa40e6d395bb90fc9203fb3a98b53006fe0a5b0117e8c4d283856e7b11a3` | `91355374f275f88050e158a91ce38e4a3893c5a946db5ea55ccd578f874d1d89` |
| `chapter_03/03.04-knowledge-and-artifact.md` | `7231c123c3697ca75411d870b4a0f417cb8d61148b86b304f94427e194b14ba6` | `f5f1ee778ce5c064a5de4717b51fc1ec0fc3a4179fd6d1f7754710537981dc98` |
| `chapter_03/03.05-ontology-layers.md` | `db50a5807dcfb4206e1cf514808316aca1d58d2c9dbeca67c17e29810cd5853a` | `30103b47f801a2235e99f7eacc648ee068bc6f65ba264c03fdbf6b50326df037` |
| `chapter_03/03.00-index.md` | `2c61672725b9b4c28f336a906a7f5fdeae1a1bbf6d0ce491f41c8ed9278187d4` | `14bac1fec8b103e09e6d2dd18fb5e8ce8facc999daed3bad230c0790a502f68a` |
| `chapter_01/TODO.md` | `f6bbededebc75a1272873e62ec5407d7f61c874fcc52122298b244f189e79bfd` | `0dc3e7d430af48b9049983e45d570f9569563222aabe1e51883762256533f642` |

## Corpus batch C-4, part 1 (S0021), 2026-10-18 UTC

**Basis:** D-R5; CV-1 (forward practice after review C3: counterexample attempt and governing-record standing recorded for every VALIDATED concept); record E-S0021 (21 quotes, all PASS; concepts in §16).

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| RC-45 | **new file** Ch. 3 §3.6 `chapter_03/03.06-relationships.md` | — | the chain purpose → artifact; kind and owner; four relations; invariants vs implementation choices (CON-0038 validated); knowledge does not execute (CON-0039 validated); success test; PKS as container | new evidence: S0021 | new section (ML-1) | Ch. 3; T3-02, T3-04 evidence updated | review C4 (foundational candidate: CON-0038) |
| RC-46 | Ch. 2 §2.9, after RC-13 | — | where the design-policy layer was found; domain ownership rejected; I-2 equates DP with invariant; binding ≠ operating (CAP-002 deferred); CON-0034 invalid | S0021 L32–33, L44, L106, L166; S0020 L100 | addition | RC-13 (same line of evidence) | review C4 |
| RC-47 | Ch. 2 §2.5, end | — | I-10 "void-overriding": meaning unresolved (CON-0040; T2-08); Int: if it voids by the authority of the issuing act, it fits ground 1 | S0021 L114 | addition | §2.5 | review C4 |
| RC-48 | Ch. 1 §1.14.4, after RC-25 Int | — | S0021's I-4 diagnosis repeats S0012; not independent support | S0021 L108 | addition (evidence weight) | RC-25 | no |
| — | Ch. 3 index | — | §3.6 row; S0021 added to sources | — | navigation | — | no |

| File | Pre-edit sha256 | Post-edit sha256 |
|---|---|---|
| `chapter_01/CH-01-knowledge-authority-execution.md` | `5a107bbf1b80490e4d17621ad7d76a998e3b5ce6c68586b1c01ff39c0506108a` | `62b07640511cc4aede9205fc4b09e1f4c5005b4fc9cd18b7843535adf9b311a7` |
| `chapter_02/CH-02-rules-and-conflict.md` | `7e6e8c208cec42066825876f4d330a8aa04fc607ae2e6258f9bc270bd61df825` | `4dfddedbf7ef0e306c71918a30c408f6b97b89f9fc3416f9b2fb58f999e232af` |
| `chapter_03/03.00-index.md` | `14bac1fec8b103e09e6d2dd18fb5e8ce8facc999daed3bad230c0790a502f68a` | `8010ed8a2c1469f2dd9930ca77478bb65b74829576f2924c44f05d44f2edb5f9` |
| `chapter_03/03.06-relationships.md` | (new file) | `15b415b0363e2ebde3860d2209f3d392c6000829d9180f8e5c1d6dc2dec947f1` |

## Corpus batch C-4, part 2 (S0022), 2026-10-19 UTC

**Basis:** D-R5; CV-1 (forward practice: counterexample attempt and governing-record standing for every VALIDATED concept); record E-S0022 (29 quotes, all PASS; concepts in §16).

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| RC-49 | **new file** Ch. 3 §3.7 `chapter_03/03.07-cross-product-projection.md` | — | falsification by projection (CON-0047 validated); what held (with weak counts flagged); what broke; masking; inert vs attested (CON-0045 invalid); structural vs transformational (CON-0046 invalid) | new evidence: S0022 | new section (ML-1) | Ch. 3; Ch. 8 (method candidate) | review C4 |
| RC-50 | Ch. 1 §1.11, after RC-42a | — | external authority: "who must be obeyed" vs "who decides"; outside the act model (CON-0044 validated; T1-53) | S0022 L108–117, L123 | addition | authority theory: open gap | **review C4 (foundational candidate)** |
| RC-51 | Ch. 1 §1.2, after the Status line | — | the provenance/standing split first appears from a counterexample; same line of evidence as S0018 | S0022 L93 | addition (evidence) | RC-24, RC-36a | review C4 |
| RC-52 | Ch. 2 §2.5, after RC-47 | — | I-10 used, not defined; the overriding source in the example is external | S0022 L66 | addition | RC-47; T2-08 | no |
| — | Ch. 3 index | — | §3.7 row; S0022 added to sources | — | navigation | — | no |

| File | Pre-edit sha256 | Post-edit sha256 |
|---|---|---|
| `chapter_01/CH-01-knowledge-authority-execution.md` | `62b07640511cc4aede9205fc4b09e1f4c5005b4fc9cd18b7843535adf9b311a7` | `30fc4b9985feb1d4ffb2a32bb9e18a3e0b1f94eeec027b018fed9754a36273a4` |
| `chapter_02/CH-02-rules-and-conflict.md` | `4dfddedbf7ef0e306c71918a30c408f6b97b89f9fc3416f9b2fb58f999e232af` | `86efab52b8f2efb6faf79829da25ea84de2fcf1cc918f1543a7ea8524f374e5f` |
| `chapter_03/03.00-index.md` | `8010ed8a2c1469f2dd9930ca77478bb65b74829576f2924c44f05d44f2edb5f9` | `228279be3f4d3b94af8e5372bb9682b48df6bdd08d6c2a583bc855a510f92916` |
| `chapter_03/03.07-cross-product-projection.md` | (new file) | `bb0d2a23bdd37c1d7c0814938605baa1415698903350b2f802e90248c574cef7` |

## Corpus batch C-4, part 3 (S0023), 2026-10-19 UTC

**Basis:** D-R5 (the Professor decides the structure: Ch. 5 opened); CV-1 (forward practice); record E-S0023 (26 quotes, all PASS; concepts in §16).

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| RC-53 | Ch. 1 §1.17.1, after the asymmetry bullet | — | the docket as one instance of decision-readiness; CON-0012 stays invalid | S0023 L5, L16–28 | addition | RC-40a; T1-51 | review C4 |
| RC-54 | Ch. 0 §0.2, after the Int | — | the proposed mission text (sponsor, D-5); AIP-14's metric named, basis undecided (CON-0051 invalid; T0-18) | S0023 L20–50 | addition | CON-0024, CON-0025 | **review C4 (foundational candidate)** |
| RC-55 | Ch. 1 §1.13, after RC-33a | — | D-2 recommendation (mint with disclosure + forward rule); the self-check circular for verification, not authority (Int) | S0023 L101–108 | addition | RC-33a | review C4 |
| RC-56 | **new files** Ch. 5 `05.00-index.md`, `05.01-decision-docket.md`, `TODO.md` | — | Ch. 5 opened: decision package (CON-0048), gating order (CON-0049), presupposing evidence (CON-0050) validated; model amnesia (CON-0052) invalid | S0023 | new chapter / section (ML-1) | Ch. 5 | review C4 |
| RC-57 | Ch. 0, after RC-32 | — | a fourth reading: "adopted stance N-10" vs ratification still asked; candidate reading stands | S0023 L110–114, L168 | addition | RC-28, RC-32; T5-03 | review C4 |
| RC-58 | Ch. 3 §3.5, Open | — | D-8 recommendation: RQ-002 as frame, L-A as its artifact projection | S0023 L75–82 | addition | T3-03, T3-05 | no |

| File | Pre-edit sha256 | Post-edit sha256 |
|---|---|---|
| `chapter_00/CH-00-foundations.md` | `6b951d9d5ebc04b17e1a8dd3dc24ed4d05e86e296fcc247c0ac8e8412ae1a8f9` | `b489c445181b59106421bff80409bdeca215dc229cb21d2dc55f98912295a708` |
| `chapter_01/CH-01-knowledge-authority-execution.md` | `30fc4b9985feb1d4ffb2a32bb9e18a3e0b1f94eeec027b018fed9754a36273a4` | `b35e4744e2c96057567870b93f2fef7c068bbb4b23b914a047ea6a63c236c27b` |
| `chapter_03/03.05-ontology-layers.md` | `30103b47f801a2235e99f7eacc648ee068bc6f65ba264c03fdbf6b50326df037` | `a8571bce17d5a261bd4610e1e3595aa5280b5d4d591bfce54b938cc6b208afde` |
| `chapter_05/05.00-index.md` | (new file) | `241778fbdd49d20fceddf87449bdff7df7cbaab3a2121bb8bdee26ad7f0d1ca6` |
| `chapter_05/05.01-decision-docket.md` | (new file) | `539cf778c9fc3f77acba57bd709be5f04c9452b7fc77d7380510c774a299de24` |

## Corpus batch C-4, part 4 (S0024), 2026-10-19 UTC

**Basis:** D-R5; CV-1 (forward practice); record E-S0024 (29 quotes, all PASS; concepts in §16).

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| RC-59 | Ch. 1, after "Root acts" | — | genesis: the architecture has no root; proposed clause; OQ-S3 (CON-0054 invalid) | S0024 L36, L159–163 | addition | T1-17 primary source | **review C4 (foundational candidate)** |
| RC-60 | Ch. 2 §2.10, end | — | maturity ≠ enforcement (CON-0056); boundary/trigger/advisory (CON-0055); enforcement in the runtime adapter, not in capabilities | S0024 L68–90 | addition | §2.10; RC-13 | review C4 |
| RC-61 | Ch. 3 §3.6, before Open | — | where each thing belongs: kernel test (CON-0057 invalid), PKS contents, never-products; decomposition ≠ materialization | S0024 L124–149, L185–189 | addition | §3.6 | review C4 |
| RC-62 | **new file** Ch. 5 §5.2 `chapter_05/05.02-gap-kinds.md` | — | gap kinds by closure (CON-0053 validated); automating too early; the limit of searching | S0024 | new section (ML-1) | Ch. 5 | review C4 |

| File | Pre-edit sha256 | Post-edit sha256 |
|---|---|---|
| `chapter_01/CH-01-knowledge-authority-execution.md` | `b35e4744e2c96057567870b93f2fef7c068bbb4b23b914a047ea6a63c236c27b` | `678636a05c418ca1d2f1c4a2b0e92e2b3716b148c7044be62f384da955f43785` |
| `chapter_02/CH-02-rules-and-conflict.md` | `86efab52b8f2efb6faf79829da25ea84de2fcf1cc918f1543a7ea8524f374e5f` | `e3eee68058d76ce0d494ba2e1a4943fe93fa6762fbe049d734081f37730efb02` |
| `chapter_03/03.06-relationships.md` | `15b415b0363e2ebde3860d2209f3d392c6000829d9180f8e5c1d6dc2dec947f1` | `0af8e5706bbc7c119223062c1ad6bb0691989d51421c18d59ca8519e8fb498a7` |
| `chapter_05/05.00-index.md` | `241778fbdd49d20fceddf87449bdff7df7cbaab3a2121bb8bdee26ad7f0d1ca6` | `3b43fa7acfba752be9a1ba15a7f483b2a3ff5180c5e428ea7c55034c034d1cb7` |
| `chapter_05/05.02-gap-kinds.md` | (new file) | `36d2b26b137ec22c97a68aa55729c49f3058361671e5243db3fe50c1c81cb717` |

## Corpus batch C-4, part 5 (S0025), 2026-10-19 UTC

**Basis:** D-R5 (Ch. 8 opened); CV-1 (forward practice); record E-S0025 (30 quotes, all PASS; concepts in §16).

| # | Where | Previous | Proposed (applied) | Reason and evidence | Type | Impact | Review / decision? |
|---|---|---|---|---|---|---|---|
| RC-63 | **new files** Ch. 8 `08.00-index.md`, `08.01-bootstrap-experiment.md`, `TODO.md` | — | Ch. 8 opened: the bootstrap experiment; reach register (CON-0058), domain-free ≠ portable (CON-0059), scaffolding contamination (CON-0060) validated | S0025 | new chapter / section (ML-1) | Ch. 8; Ch. 3 §3.7 and Ch. 5 §5.2 referenced | review C4 |
| RC-64 | Ch. 1, under RC-59 | — | genesis: primary source is the experiment; S0024 repeats it (one line, n=1) | S0025 L120–122 | addition (evidence) | RC-59; T1-17 | review C4 |
| RC-65 | Ch. 1 §1.14.3, after RC-35 | — | a solo start cannot satisfy the protocol as written; "party" undefined (T1-54) | S0025 L69, L114, L129, L141 | addition | RC-30 (process independence) | **review C4 (foundational candidate)** |
| RC-66 | Ch. 2 §2.4, end | — | two closed verdict vocabularies: a genuine conflict (CON-0062) | S0025 L72, L143 | addition | §2.3–2.5 | review C4 |
| RC-67 | Ch. 2 §2.8.3, end | — | a deferred rule is silence (CON-0063 invalid; T2-10) | S0025 L74 | addition | SC-2 | review C4 |
| RC-68 | Ch. 2 §2.11, end | — | a deferral that falls due; who decides the trigger fired (CON-0061 invalid; T2-09) | S0025 L144–147 | addition | §2.11 | review C4 |

| File | Pre-edit sha256 | Post-edit sha256 |
|---|---|---|
| `chapter_01/CH-01-knowledge-authority-execution.md` | `678636a05c418ca1d2f1c4a2b0e92e2b3716b148c7044be62f384da955f43785` | `dd090f85442a9f932678c1f1a674b320a492e3b92c07625027b2f754d6a0f90a` |
| `chapter_02/CH-02-rules-and-conflict.md` | `e3eee68058d76ce0d494ba2e1a4943fe93fa6762fbe049d734081f37730efb02` | `92fc8f528dd8649fb918c495d5914759d7fb9677af42ad9f77ab68184aa41bdc` |
| `chapter_08/08.00-index.md` | (new file) | `2d774adeecc3d45969997a6b235533e20c36f5b09f8611d356cd491f597693f2` |
| `chapter_08/08.01-bootstrap-experiment.md` | (new file) | `aa04ea25c9f7cdf5a941f9eb84f212b55c6837929f1c0f55c3754658c8581eb4` |

*Note (2026-10-19T07:50Z):* `chapter_08/08.00-index.md` title aligned with CHAPTER-PLAN ("How the theory is validated") after the hash above; current sha256 `93c2e18fcd049b41254b1c6ee9ec99ac3183340c8dd58eb84ae35fa046704569`.

## Batch C-4: corrections after review C4 (2026-10-19 UTC)

**Basis:** review C4 report `review/C4-S0021-S0025/PHASE2-REVIEW-REPORT.md` (sha256 `341098547efe26f381e37ee5017f969f6de2d219a29f51c9ae573720d05ba1e9`), verdict **UNSOUND** (book integration; records SOUND WITH MINOR ISSUES); 27 findings (2 foundational) plus CV-1 and SQ-1 sections. The evidence of F-01 and F-02 was re-checked against the sources (S0022 L27 "hypothetical"; S0025 L155 genesis under governance; Ch. 1 §1.13.1 AMS definition; existing T1-15). Handled under the rules in force before RK-1.

| # | Finding | Where | Correction (applied) | Disposition |
|---|---|---|---|---|
| RC-50a | F-01 (foundational) | Ch. 1 §1.11 | hospital/ERP marked hypothetical; Int → Hyp/Open with two ways the act model might cover external authority; CON-0044 → INVALID; S0012 LG-6 cited; T1-53 merged into T1-15 | accepted |
| RC-59a | F-02 (foundational) | Ch. 1 root acts | heading and first sentence no longer state Int as Src; genesis restated as an evidence prerequisite, not a root-act analogue; F-1 named as the authority-side analogue; S0024/S0025 classifications both kept | accepted |
| RC-8a | F-03 | Ch. 1 §1.8 | "every element survived unchanged" annotated with S0025's per-document results | accepted |
| RC-54a | F-04 | Ch. 0 §0.2 | metric claim attributed to the docket; "proposes" → "ratify (or amend and ratify)"; comparison labelled Int; "weakens further" withdrawn | accepted |
| RC-65a | F-05 | Ch. 1 §1.14.3 | "party" settles only §2; §5 approval and the light gate are separate blocks; process independence does not lift F-1 | accepted |
| RC-66a | F-06 | Ch. 2 §2.4 | candidate conflict limited to one region; standing, shared values and scope-by-actor addressed; CON-0062 → INVALID | accepted |
| RC-60a | F-07 | Ch. 2 §2.10 | the 41 items are tool permissions (RM-1); counts by mechanism; blocking gate noted; CON-0055 → INVALID | accepted |
| — | F-08 | Ch. 5 §5.2 | "primary closure" withdrawn; classifications recorded; CON-0053 → INVALID | accepted |
| — | F-09 | Ch. 5 §5.1 | general rule qualified (robust evidence); "in the corpus read so far"; CON-0050 → INVALID | accepted |
| — | F-10, F-11 | Ch. 8 index, §8.1 | four additions (A, B, C, F) as candidates; quote restored with propagation failure reported; "deliberately rejected"; "counts against it"; EEP tension; index premise restated as the corpus's claim | accepted |
| — | F-12 … F-16 | records E-S0021 … E-S0025 §18 | chronology corrected; omitted counts and contradictions added | accepted |
| — | F-14, F-17, F-20, F-21, F-25, F-26, F-27 | §5.1, §3.6, §3.7, §1.17.1, §5.2 | Package 7 tension; I-2 quote; links to RC-26/T1-41; F-1 withdrawn; RC-53 qualified; kernel links; LA-4 vs P3 wording | accepted |
| RC-51 (heading) | F-18 | Ch. 1 §1.2 | "first appears" → "also appears" | accepted |
| — | F-19 | Ch. 1 §1.1 | I-11 no longer "cited-only"; SEQ-0012 noted; CON-0039 → INVALID (T3-19) | accepted |
| RC-67a | F-23 | Ch. 2 §2.8.3 | SC-2 step withdrawn; derived from §2.11; CON-0063 → VALIDATED (reviewer's second attempt) | accepted |
| — | F-22, F-24 | register; T0-01 | CON-0052 reason restated; T0-01 location noted; TODO files included in the hash table below | accepted |
| — | §8a | register | reasons restated: CON-0043, 0045, 0052, 0054, 0061; limits noted: CON-0047, 0056 | accepted |
| — | SQ-1 | chapter TODOs | **SEQ-0001 … SEQ-0016** recorded; 13 resolved by the corrections above; open: SEQ-0001 (runtime adapter undefined), SEQ-0002 (maturity levels), SEQ-0012 (circular, T3-19) | accepted |

| File | Pre-edit sha256 | Post-edit sha256 |
|---|---|---|
| `chapter_00/CH-00-foundations.md` | `b489c445181b59106421bff80409bdeca215dc229cb21d2dc55f98912295a708` | `cee8039039637d48a3d8ce78ee1c9628311725b720b8b4778815361edf83d09f` |
| `chapter_00/TODO.md` | `b1f0e7fa6599870ac7a8306ea97069ad2c588afbe85b6cf1399f7ddaa8b44c7c` | `1a23f4093d332409d3e2f0d51bc0fccc22f9ac3a29c2998ef203ba73b21841b3` |
| `chapter_01/CH-01-knowledge-authority-execution.md` | `b7a4bc9e50080e65451d01bb37d33a652cd6f4514d750b212d4e777780c2a4bf` | `3551203c92ee0637ad1ba68efc48fbb1391634bfe4db35a41fd997684e17f2c0` |
| `chapter_01/TODO.md` | `4795b58d8c220e22669628d377af8ebc0ff7c0f88710005d827bd10551f19511` | `2986176c5db63c9284dbd5805715bbaaa3e07f66b3d7688dd37acc666f156676` |
| `chapter_02/CH-02-rules-and-conflict.md` | `f08dbe6f9933072e0a6765cdf7e84fbd4ecdb507664a829b103a36fd15c8fe38` | `c84fd6d5af75db44a2fdf0cfd3240c52fb4a5955c763550cf56ecc301cff2c73` |
| `chapter_02/TODO.md` | `b31a2a7a94e1c5bec74f3d2bd30b768b73ca0e41e016456a9ab20d04bbb07e4c` | `8c9d2a127af6b45d23434d0cbb9350bc732867b83dce41017957443f069c0fd5` |
| `chapter_03/03.06-relationships.md` | `0af8e5706bbc7c119223062c1ad6bb0691989d51421c18d59ca8519e8fb498a7` | `abe92c8c43546a0e768f9bcfe71deb8f88c7e12aea00c6b52d6b7484499c05d5` |
| `chapter_03/03.07-cross-product-projection.md` | `bb0d2a23bdd37c1d7c0814938605baa1415698903350b2f802e90248c574cef7` | `54b80b661b3e8f8234386a240d071854690f87408d739d52d3aeccc301f2fc90` |
| `chapter_03/TODO.md` | `48d282a311c16919ae02a2e0374fabcbc7d3bc2c7211f0f4f1a3c00882e13987` | `290729a17856a319417e9c8af19874c75d10a1eb800167aa02835c618576976c` |
| `chapter_05/05.01-decision-docket.md` | `539cf778c9fc3f77acba57bd709be5f04c9452b7fc77d7380510c774a299de24` | `8108daadc4d3a4b76717224181ffd2f2dc9a10d19171fe8033d695dc090202fb` |
| `chapter_05/05.02-gap-kinds.md` | `36d2b26b137ec22c97a68aa55729c49f3058361671e5243db3fe50c1c81cb717` | `e567d8274b3ef202721c7eb484bd6bce350a1da01cf3720a805bb43766f0e034` |
| `chapter_05/TODO.md` | `8d214b28a06f230e18d551ed2297f3d967773b5324474f39ca1d56a47f603602` | `7611d9e4598a3fd65a39a0f81860865f8a8ff49f63b2b8f4ad01f15fe5f4fb7c` |
| `chapter_08/08.00-index.md` | `93c2e18fcd049b41254b1c6ee9ec99ac3183340c8dd58eb84ae35fa046704569` | `8dd271ec1057d7e57ef15311240facd75889a8e388f72690644ccc1a746e6b87` |
| `chapter_08/08.01-bootstrap-experiment.md` | `aa04ea25c9f7cdf5a941f9eb84f212b55c6837929f1c0f55c3754658c8581eb4` | `ecac95bf678961e9ba10a8531d1e3cd584607b913a903fa4383635658cd4a0cf` |
| `chapter_08/TODO.md` | `f901732fb1358320929acf3a2f555d39c90164760cb9d0f8bee99c182df150b9` | `06452098752f8e114d8d1125bdfd9175e588ac92396ff4b09b505f40cf788a1d` |

*Note:* after the table above, the CON-0044 tag in `chapter_03/03.07-cross-product-projection.md` was set to invalid (missed in the first pass; found by the tag check). Current sha256 `ef9185202343f0bc57bcaeeba7e875e60ed9274556308eb53b0c3474e8c00045`.

## Batch C-5 (S0026–S0030), under RK-1 and RK-2

*Before-hashes of the batch: taken 2026-10-19T08:14Z, kept in the batch file list (`tmp/batch-C5-prehash.txt`); after-hashes at the C5 checkpoint.*

| # | Source | Where | Change | Ideas |
|---|---|---|---|---|
| RC-69 | S0026 | new Ch. 3 §3.8 `03.08-four-orders.md` | four orders, not one hierarchy (CON-0064, with the Ded on what an order requires and what a combined order loses); domain (CON-0065 invalid); executability (CON-0066) and the derivation for "knowledge does not execute" (reduces SEQ-0012); the persistence limit; the decision test (CON-0067 invalid) | E-S0026 ideas 1–6 |
| RC-70 | S0026 | Ch. 0 §0.2 (RC-54 paragraph) | the enacted mission stated two incompatible ways on 2026-08-03 | E-S0026 "mission text" |
| RC-71 | S0027 | **new** Ch. 4 `04.00-index.md`, `04.01-from-observation-to-pattern.md` | Ch. 4 opened: evidence entry (CON-0068), patterns derived not written (CON-0069), counted class fixed in advance (CON-0070, with Ded), evidenced ≠ measured (baseline, selection), independent recurrence, obligation vs monitor coverage (CON-0071) | E-S0027 ideas 1–7 |
| RC-72 | S0027 | Ch. 1 back-flow table | S0027 row: the first formal traversal at instrumentation level; conflicts with "≈2 formal" (different definition of "formal") | E-S0027 idea 5 |
| RC-73 | S0028 | **new** Ch. 6 (`06.00-index.md`, `06.01-method-binding-evidence.md`, `TODO.md`) | Ch. 6 opened in the Rulebook v1 section form: decomposition (CON-0073 ✗), binding as parameter (CON-0072 ✓), one product cannot fix the split (Ded), SD-1 both not either (Ded), portability lattice (CON-0075 ✓; ladder CON-0074 ✗), kernel test necessary only (CON-0057 → ✓), responsibility without component, where the platform's evidence lives (Ded) | E-S0028 ideas 1–8 |
| RC-74 | S0028 | Ch. 1 §1.8; Ch. 3 §3.6, §3.8 | full form of the component rule (CON-0076); CON-0057 tag; orders not exhaustive | E-S0028 ideas 4, 6, 8 |
| RC-75 | S0029 | **new** Ch. 5 §5.3 `05.03-deferral.md` | deferral with activation criteria (CON-0077 ✓), gates by risk (CON-0078 ✓), three questions (CON-0079 ✓); C1 criterion = fixed class for decisions (Ded); C2 where the n ≥ 2 rule applies, resolving S0024/S0025 vs S0029 (Ded); C3 activation's two engines; C4 standing rule bypassed; C5 a view stating rules; C6 sample-size gates | E-S0029 ideas 1–7, 9, 11 |
| RC-76 | S0029 | Ch. 5 §5.2 | correction note on "automating too early" | E-S0029 idea 4 |

## Review C5 settled: theory-only turn (2026-10-19)

Basis: `review/C5-S0026-S0030/STEP2-TRANSITION-REPORT.md` (verdict PARTLY; six steps fail as written). All accepted after re-checking S0026 L59, S0028 L83, S0029 L43, S0030 L8. §3.8, §4.1, §5.3 and §6.1 rewritten as clean theory text; Ch. 1 §1.5 and §1.8 cross-references, Ch. 1 back-flow table (+S0028, +S0026), Ch. 5 §5.2 note; register: CON-0066, CON-0078, CON-0079 → INVALID, 11 bases restated; new fork F11; TODOs T3-20, T4-03, T5-07, T6-03.

| File | Before | After |
|---|---|---|
| `chapter_03/03.08-four-orders.md` | `53a634090eda6d62…` | `f9867aaa00ec8b7e…` |
| `chapter_04/04.01-from-observation-to-pattern.md` | `8b502f6379fad7fd…` | `4d8463bdb44a4dcf…` |
| `chapter_05/05.03-deferral.md` | `feb00e6e3eb60a99…` | `b52f230ee6b0b928…` |
| `chapter_06/06.01-method-binding-evidence.md` | `1576d0ddd7501bce…` | `4d4b767a155cc83a…` |
| `chapter_01/CH-01-knowledge-authority-execution.md` | `9c47927f222bf570…` | `431eb76876e57832…` |
| `06-theory/THEORY-MAP.md` | `88bcd293c9c76623…` | `cf2cac7c0706e603…` |

## BRANCH-01 (Zero/Gap), 2026-10-19

New Ch. 5 §5.4; Ch. 2 §2.8.8 E9, E10; Ch. 0 §0.9 Q3 pointer. Concepts CON-0080…0083. Report: `review/BRANCH-01-zero-gap/BRANCH-REPORT.md`. Sources consulted, not read in order: S0208, S0721, S0896, S1296.

## BRANCH-01 review settled (2026-10-19)

Review `review/BRANCH-01-zero-gap/REVIEW-REPORT.md`; all findings accepted after re-checking S0896 L858/L875/L1270–1276 and S0208 L5/L99/L420. Ch. 5 §5.4 rewritten; Ch. 2 §2.8.8 E9/E10 moved after E8 and marked provisional candidates, with licences; Ch. 0 §0.9 Q3 pointer narrowed and the §0.7 count updated; theory map §3 and §5a; branch report ✓ marks corrected; register follow-ups for CON-0080/0082/0083 (kept provisional) and CON-0081.
