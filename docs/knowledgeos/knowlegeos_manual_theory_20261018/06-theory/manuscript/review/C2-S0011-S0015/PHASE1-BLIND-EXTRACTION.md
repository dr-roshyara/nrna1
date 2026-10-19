# Phase 1 blind extraction: S0011–S0015

## Access and completeness

- I read only the five authorised files, each in full with the Read tool. I opened, listed or searched nothing else, and I wrote no files.
- Line totals as the Read tool showed them:
  - **S0011** (Falsification Report): 170 lines
  - **S0012** (Lifecycle Gap Analysis): 181 lines
  - **S0013** (Architecture Synthesis Matrix): 125 lines
  - **S0014** (Phase B Evidence Reconciliation): 112 lines
  - **S0015** (Independent Review Phase B): 137 lines
- Format of each item: ID · TYPE · the source's own strength word, if any · line anchor · short verbatim quote (emphasis markup and emoji removed) · what it means in this document only.
- My own interpretations are marked [I].

---

# S0011 — Engineering Knowledge Domain Model: Falsification Report (170 lines)

## (a) Status, authority and dates

- **Kind:** "FALSIFICATION TEST" (L5).
- **Authority:** "Generated — never authoritative without human review" (L6).
- **No Status row.** The verdict on the model is "NOT canonical" (L146, L162). The document's own disposition is "Submitted to the Decision Authority. Nothing in this document executes." (L169).
- **Date of the file itself:** commission dated 2026-08-02 (L7, L167).
- **Dates it quotes:**
  - 2026-06-25: the Round39-D6 ADRs were authored that day (L30, L116).
  - 2026-07-10: the EEP's status, "Adopted · STABLE (Decision Authority, 2026-07-10)" (L92).
  - 2026-07-31: the session file `.claude/sessions/2026-07-31.md` (L41).
- **Relative date:** "eight documents I wrote today" (L41) is relative to the commission date.

## Items

- S0011-01 · DEFINITION · — · L5 · "FALSIFICATION TEST." · The document is a falsification test.
- S0011-02 · DECISION-OR-STANDING · — · L5 · "The objective was to BREAK the model, not improve it." · The goal is refutation, not improvement.
- S0011-03 · DECISION-OR-STANDING · — · L5 · "No redesign · no new domain · no folder · no invented abstraction." · States what was out of scope.
- S0011-04 · DEFINITION (authority) · "never authoritative" · L6 · "Generated — never authoritative without human review" · Declares its own authority as generated.
- S0011-05 · DATE · — · L7 · "Strategic DDD Architect / Principal Knowledge Engineer / Enterprise Information Architect + ARB Chair's Addendum, 2026-08-02" · Names the commission roles and date. An ARB Chair addendum was part of the commission.
- S0011-06 · CLAIM · — · L8 · "a candidate, not accepted architecture" · The model under test (`KnowledgeOS_Engineering_Knowledge_Domain_Model.md`) is only a candidate.
- S0011-07 · DEFINITION · — · L9 · "Round47-OP's nine admissible justifications · the R16 Workbook's Strong/Medium/Weak scale" · The instruments were taken from the repository itself.
- S0011-08 · CLAIM · — · L10 · "Every test was executed against artifacts, not reasoned from the model." · Method claim: the tests were empirical.
- S0011-09 · CLAIM · — · L10 · "Four falsifiers were sought; three landed" · Four attempts were made and three succeeded.
- S0011-10 · CLAIM · — · L12 · "THE MODEL DOES NOT SURVIVE INTACT." · Headline verdict.
- S0011-11 · CLAIM · — · L13 · "One claim FALSIFIED · two WEAKENED · two SURVIVED · two unchanged." · Summary tally (see the inconsistencies below).
- S0011-12 · CLAIM · "strongest" · L14 · "The strongest claim in the model — D-2 — was among those weakened, by evidence the model itself cited." · D-2 was weakened by the model's own cited evidence.
- S0011-13 · CLAIM · — · L20 · "D-2's 'own decision series' is a shared authority — two of four grounds fall" · W-1: two of D-2's four grounds fail. Only two grounds are shown.
- S0011-14 · EXAMPLE (quoted source) · — · L24 · "Decision Authority (all ADRs unless noted): Round 39 Methodology Governance (sponsor + ARB)." · The Round39-D6 header names a joint sponsor+ARB authority.
- S0011-15 · EXAMPLE (quoted source) · — · L25 · "amendment requires a Constitutional Amendment Record (sponsor + ARB + version bump)." · Amending the series needs sponsor + ARB + a version bump.
- S0011-16 · CLAIM · WEAKENED · L29 · "The ARB participates in both. ADR-M is a scoping device under a shared authority, not a separate authority" · The "own ADR series" ground (ADR-M as distinct from ADR-AIP) is weakened.
- S0011-17 · CLAIM · FALSIFIED · L30 · "Retrospectively documented. These ADRs were authored together on 2026-06-25." · The "independent evolution" ground is falsified: the series was written in one sitting.
- S0011-18 · DEFINITION (principle) · — · L30 · "A series written in one sitting evidences an independent subject, never independent evolution over time" · Rule: what a series written at once can and cannot evidence.
- S0011-19 · CLAIM · "half true" · L32 · "ADR separation is historical rather than semantic. It is half true — the separation is real in subject, absent in time." · Assesses the ARB Chair's named falsifier.
- S0011-20 · CLAIM (prior claim under test) · — · L36 · "a boundary built to hold a language apart is a bounded context by construction." · The author's earlier argument for D-3.
- S0011-21 · CLAIM · — · L40 · "There is no Capability Mapping file. The layer is described inside one record and instantiated nowhere" · No artifact holds the abstract capability vocabulary.
- S0011-22 · CLAIM · — · L41 · "Exactly ONE source artifact … plus .claude/sessions/2026-07-31.md and eight documents I wrote today. No independent consumer" · The vocabulary is cited only by its origin record, one session log and the author's own documents.
- S0011-23 · CLAIM · — · L42 · "deny · ask · allow (19/22/8 entries) … The mapping is very nearly the IDENTITY function" · The runtime vocabulary and the abstract vocabulary map almost one-to-one.
- S0011-24 · DEFINITION (principle) · — · L44 · "an Anti-Corruption Layer that performs an identity mapping is not an ACL. It is a restatement." · An identity-mapping ACL is not a real ACL.
- S0011-25 · CLAIM · — · L45 · "The vocabulary separation is asserted in prose, not enforced by any artifact or tool." · The D-3 boundary is unenforced.
- S0011-26 · CORRECTION-OR-RETRACTION · — · L45 · "D-3 drops from STRONG to WEAK." · D-3's confidence is downgraded.
- S0011-27 · CLAIM · FALSIFIED · L47 · "PKS is a projection — FALSIFIED for every existing PKS artifact" · W-3 headline.
- S0011-28 · CLAIM · — · L53 · "Authority: Generated — never authoritative without human review (a table row, human-asserted) … authority: derived (machine-checkable)" · The PKS M4 artifact carries a human-asserted authority row. A projection would carry a machine-checkable `derived`.
- S0011-29 · CLAIM · — · L54 · "Status: ACCEPTED WITH REFINEMENTS … a projection is regenerated, not accepted" · PKS artifacts carry acceptance status; projections would not.
- S0011-30 · CLAIM · — · L55 · "commissioned by the PA · work plan approved in Plan Mode · ARB reviewer endorsement 9.9/10" · The PKS artifact has a disposition history. A projection would have a source and a regeneration command instead.
- S0011-31 · CLAIM · — · L56 · "Commission — a named plan and work plan" · The PKS artifact names a commission; a projection would not.
- S0011-32 · DEFINITION (principle) · — · L58 · "You do not endorse a projection at 9.9/10. You regenerate it." · Endorsement is incompatible with being a projection.
- S0011-33 · CLAIM · — · L60 · "returns 10 files — every one a PROSE MENTION of the vocabulary, not one an application of it." · A grep found no artifact that actually applies `authority: derived|generated`.
- S0011-34 · CLAIM · — · L60 · "Those paths sit outside knowledge-schema.yaml's declared scope.include, so no frontmatter there is validated at all." · Frontmatter in `docs/pks`, `docs/knowledgeos` and `docs/implementation` is not validated.
- S0011-35 · CORRECTION-OR-RETRACTION · "SHARPENS" · L62 · "It is stronger than blocked: existing PKS artifacts are POSITIVELY AUTHORITATIVE — authored, commissioned, reviewed, disposed and accepted." · Sharpens the author's earlier "blocked in practice" statement (Ontology Classification §4).
- S0011-36 · CLAIM · — · L62 · "That is the lifecycle of an authoritative artifact, and it is incompatible with derived." · Being authored, commissioned, reviewed and accepted makes an artifact authoritative, not derived.
- S0011-37 · CLAIM · — · L68-69 · "ONE — PublicDigit's … ZERO" · W-4: the D-7 "Context Type" has one observed instance and zero generated instances.
- S0011-38 · DEFINITION (principle) · — · L70 · "A type that has never been instantiated is a description, not a discovered construct" · Also: "one instance per product" is one observation restated as a rule.
- S0011-39 · CLAIM · — · L74, L78 · "Governance discoveries (constitutional/capability) MUST NOT appear here. No DDD concepts here." · S-1: D-2 survives on an explicit scope-exclusion rule in Round39-D6.
- S0011-40 · CLAIM · — · L82 · "a deliberately maintained boundary, not an accident of filing" · Scope exclusion is evidence of intent.
- S0011-41 · CLAIM · — · L83 · "ADR-M excludes governance; ADR-AIP carries no methodology rules" · The exclusion is bidirectional in effect.
- S0011-42 · CLAIM · — · L84 · "a shared authority that forbids itself from mixing two subjects is evidence FOR two models, not against" · The shared-authority finding does not defeat D-2.
- S0011-43 · CLAIM · — · L86 · "D-2 remains a boundary — but on subject exclusion, not on decision-series separation or independent evolution." · D-2's surviving ground.
- S0011-44 · CLAIM · "REJECTION SURVIVES" · L92-94 · "Owner: Decision Authority … the same owner as D-1 … Workflow has no owner of its own." · S-2: the D-4 rejection is confirmed by the EEP header ("Adopted · STABLE (Decision Authority, 2026-07-10)").
- S0011-45 · CLAIM · — · L100 · "multiple owners of the same concept — not found — DA/ARB throughout" · D-1 falsifier 1 failed.
- S0011-46 · CLAIM · — · L101 · "changes that do not co-occur — not found — ES set moves under STANDARDS_INDEX" · D-1 falsifier 2 failed.
- S0011-47 · CLAIM · — · L102 · "forced co-evolution with a product — not found — ratification and freeze are product-independent" · D-1 falsifier 3 failed.
- S0011-48 · CLAIM · — · L103 · "absent invariants — not found — append-only rulings; R-46" · D-1 falsifier 4 failed.
- S0011-49 · CLAIM · "Partial" · L105-107 · "Composition overlaps the ARB but is not identical — it is 'sponsor + ARB' … enough to keep D-2 alive, not enough to call it autonomous." · S-4: the methodology governance body is named separately but only partly independent.
- S0011-50 · OPEN-QUESTION · — · L113 · "Was Round39-MC adopted by sponsor authority BECAUSE D-2 is independent, or DESPITE it being part of D-1? … The artifacts record the act, never the reason." · U-1. The document says the model's validity turns on this.
- S0011-51 · OPEN-QUESTION · "untestable at n=1" · L114 · "Would a second runtime require translation, or is deny/ask/allow universal?" · U-2.
- S0011-52 · OPEN-QUESTION · — · L115 · "0 traversals. The split is motivated and entirely untested" · U-3: does the D-6a/D-6b split hold under a real traversal?
- S0011-53 · OPEN-QUESTION · — · L116 · "stated 2026-06-25; no subsequent enforcement event found" · U-4: is the scope exclusion still enforced?
- S0011-54 · OPEN-QUESTION · — · L117 · "the pattern postdates the refusal; only the ARB may decide" · U-5: could H-CAT-1 be overturned on the FROZEN Platform_Capability_Pattern?
- S0011-55 · OPEN-QUESTION · — · L118 · "shared ARB suggests peers-under-one-authority, not hierarchy" · U-6: does D-1 authorize D-2, or are they peers?
- S0011-56 · CLAIM · STRONG · L124 · "D-1 … Strong … STRONG" · Confidence unchanged.
- S0011-57 · CLAIM · MEDIUM · L125 · "D-2 … Strong … MEDIUM" · Downgraded.
- S0011-58 · CLAIM · WEAK · L126 · "D-3 … Strong … WEAK" · Downgraded.
- S0011-59 · CLAIM · MEDIUM · L127 · "D-6a Evidence Protocol … MEDIUM … well-motivated, 0 traversals" · Newly scored.
- S0011-60 · CLAIM · STRONG · L128 · "D-6b Product Evidence … STRONG … ownership stated in canon; abundant records" · Newly scored.
- S0011-61 · CLAIM · MEDIUM · L129 · "D-5 … not a context — a knowledge kind … unchanged; H-CAT-1 stands" · Unchanged.
- S0011-62 · CLAIM · "STRONG (as a rejection)" · L130 · "D-4 Engineering Workflow … REJECTED" · The rejection is held with strong confidence.
- S0011-63 · CLAIM · WEAK · L131 · "D-7 PKS … a context TYPE … inferred from n=1; projection claim falsified" · Scored weak.
- S0011-64 · DEFINITION · — · L133 · "falsified ≠ unevidenced … D-3 and D-7 carry contradicting evidence (falsified). D-6a carries no evidence (unevidenced). The first is worse." · Distinguishes two kinds of failure.
- S0011-65 · DECISION-OR-STANDING (recommendation) · ACCEPT · L139 · "may become canonical" · Recommendation for D-1.
- S0011-66 · DECISION-OR-STANDING · REVISE · L140 · "restate the boundary on the ONE surviving ground … Delete 'own decision series' and 'independent evolution' as grounds" · Recommendation for D-2.
- S0011-67 · DECISION-OR-STANDING · DEFER · L141 · "untestable at n=1 … Revisit when a second runtime adapter exists" · Recommendation for D-3. The trigger is reserved in `engineering/README.md`.
- S0011-68 · DECISION-OR-STANDING · DEFER / ACCEPT · L142 · "the split needs one traversal; the records half is not in doubt" · D-6a deferred, D-6b accepted.
- S0011-69 · DECISION-OR-STANDING · ACCEPT · L143 · "do not promote until the ARB overturns H-CAT-1 on evidence" · The D-5 demotion is accepted.
- S0011-70 · DECISION-OR-STANDING · ACCEPT · L144 · "confirmed by the EEP's declared owner" · The D-4 rejection is accepted.
- S0011-71 · DECISION-OR-STANDING · REVISE · L145 · "restate as 'an observation of one instance' … record PKS-as-projection as FALSIFIED for existing artifacts" · Recommendation for D-7.
- S0011-72 · CLAIM · "NOT canonical" · L146 · "REVISE, then re-test … 2 of 8 accept · 3 revise · 2 defer · 1 accept-as-rejection" · Verdict on the whole model, with a tally.
- S0011-73 · CLAIM · — · L152 · "It broke in three places, and two of them were its headline claims." · Closing summary.
- S0011-74 · CLAIM · "most damaging" · L156 · "A boundary asserted in prose and instantiated nowhere is not a bounded context" · Ranks the D-3 finding highest.
- S0011-75 · CLAIM · "most consequential" · L157 · "D-2's 'own ADR series' is a shared authority, and the series was written in one day." · Ranks the D-2 finding.
- S0011-76 · CLAIM · "most useful" · L158 · "A shared authority that forbids itself from mixing two subjects is stronger evidence than two ADR series" · Ranks the scope exclusion.
- S0011-77 · CLAIM · — · L160 · "U-1 — whether the sponsor split methodology governance because it is independent, or despite it not being" · Names U-1 as unanswerable from the artifacts.
- S0011-78 · CLAIM · — · L162 · "a candidate that lost two grounds, gained a better one, and now carries per-domain confidence instead of a uniform claim." · Final status of the model.
- S0011-79 · CLAIM (method) · — · L163 · "A model that survived intact would have told us nothing." · States the epistemic value of falsification.
- S0011-80 · DECISION-OR-STANDING · — · L169 · "Submitted to the Decision Authority. Nothing in this document executes." · The document has no executive force.

## (b) Five most important ideas for a KnowledgeOS theory

1. **Accepted artifacts are authoritative regardless of their label (L53-62).** Artifacts that were authored, commissioned, reviewed, disposed and accepted are authoritative in practice, even though their field says "Generated — never authoritative." Standing comes from acts (endorsement, acceptance), not from labels or derivation. This sits at the centre of recognising acts versus records.
2. **"The artifacts record the act, never the reason" (L113).** This marks a structural limit on what records can establish about authority and intent. Validity questions such as U-1 cannot be settled from the corpus.
3. **Shared authority versus independent subject (L29-32, L84, L107).** One authority body can maintain two separate models through self-imposed scope exclusion. Authority independence and subject independence are different axes.
4. **Falsified versus unevidenced; asserted-in-prose versus enforced-by-artifact (L45, L133).** These are graded validity notions: a boundary that exists only in prose is not a structure, and contradicting evidence is worse than missing evidence.
5. **n=1 limits (L70, L114).** A type with one instance is a description, not a construct. Some questions (whether the ACL is universal) cannot be tested until a second instance exists. Independence/replication is a precondition for generalising.

## (c) Ambiguities, undefined terms, inconsistencies

- **Tallies disagree.**
  - L10 says "three landed". L13 says "One claim FALSIFIED · two WEAKENED · two SURVIVED · two unchanged" (seven claims).
  - L133 says D-3 *and* D-7 are "falsified", and W-2/W-3 both use FALSIFIED, so the falsified count is at least two.
  - L146 says "2 of 8 accept · 3 revise · 2 defer · 1 accept-as-rejection". By my count of the §5 table [I]: ACCEPT is D-1, D-6b and D-5 (the demotion), which is 3; REVISE is D-2 and D-7, which is 2 (3 only if "the model as a whole" counts); DEFER is D-3 and D-6a, which is 2; accept-as-rejection is D-4, which is 1.
- **"Two of four grounds fall" (L20):** only two grounds are shown, and the other two are never named.
- **"Headline claims":** L14 calls D-2 the model's "strongest claim". L152 says "two of them were its headline claims" without saying which. By L156-157 they are presumably D-3 and D-2 [I].
- **"Unchanged" versus scored:** D-6a and D-6b have no "Before" score ("—", L127-128), yet the summary speaks of "unchanged".
- **Undefined in this document:** D-1…D-7, PA, DA, ARB, EEP, H-CAT-1, Round39-MC, Round39-D6, Round47-OP, R16 Workbook, R-46, "traversal" (D-6a), and "ACL". The meaning of "0 traversals" is not given.
- **Self-reference:** the document carries the same "Generated — never authoritative" label (L6) that it argues is a human-asserted row unlike real provenance (L53). [I]
- **"Eight documents I wrote today" (L41):** the author's own documents are counted as citations, then discounted. The author is an AI session ("I") and is not identified.
- **Relation to U-3:** D-6a's "0 traversals" may or may not refer to the same "traversal" notion that other documents retire. Not resolvable inside S0011. [I]

---

# S0012 — KnowledgeOS Lifecycle Gap Analysis (181 lines)

## (a) Status, authority and dates

- **Kind:** "LIFECYCLE GAP ANALYSIS" (L5).
- **Status:** "CANDIDATE — NOT ADOPTED" (L6).
- **Authority:** "Generated — never authoritative without human review" (L7).
- **Disposition:** "Submitted to the Decision Authority. Nothing in this document executes." (L180).
- **Date of the file itself:** commission 2026-08-02 (L8, L178).
- **In-place amendment, also dated:** "LG-1 (WITHDRAWN 2026-08-02 — ILL-POSED)" (L152). It points to a separate, later-referenced document, `KnowledgeOS_Engineering_Progression_Model.md` §4. So this file was amended after its first writing, on the same date.
- **No dates are quoted from external artifacts.** `lifecycle.md` "v1.1" (L37) is a version, not a date.

## Items

- S0012-01 · DEFINITION · — · L5 · "LIFECYCLE GAP ANALYSIS. Not an ontology revision · not a lifecycle design · no new concept · no implementation · no ADR." · Document kind and exclusions.
- S0012-02 · DEFINITION (status) · — · L6 · "CANDIDATE — NOT ADOPTED" · Self-status.
- S0012-03 · DEFINITION (authority) · — · L7 · "Generated — never authoritative without human review" · Self-authority.
- S0012-04 · DATE · — · L8 · "Principal Knowledge Engineer / Strategic DDD Architect / Enterprise Software Architect, 2026-08-02" · Commission roles and date.
- S0012-05 · DECISION-OR-STANDING · — · L9 · "THE ONTOLOGY IS FROZEN. Nothing added, removed or renamed" · The constraint the document says it honoured.
- S0012-06 · DEFINITION · — · L10 · "not repairing anything — identifying which failures belong to ontology, which to lifecycle, and which to governance" · Success criterion.
- S0012-07 · CORRECTION-OR-RETRACTION · — · L12 · "THE CORRECTION IS ACCEPTED IN FULL — AND IT IS MY FOURTH INSTANCE OF LEVEL CONFUSION" · The author accepts an external reviewer's correction.
- S0012-08 · CORRECTION-OR-RETRACTION · — · L14 · "I-4 is NOT falsified. I misdiagnosed a LIFECYCLE gap as an ONTOLOGY falsification." · Retracts an earlier falsification of I-4.
- S0012-09 · CLAIM · — · L16 · "A discharge summary is not authoritative because it was projected." · Medical example: projection does not confer authority.
- S0012-10 · DEFINITION · — · L19 · "DERIVED → REVIEWED → ATTESTED → RELEASED" · The sequence by which the example artifact became authoritative.
- S0012-11 · CLAIM · — · L22 · "Governance created the authority, not the derivation." · Authority comes from governance acts.
- S0012-12 · CLAIM · "a different and better claim" · L22 · "DP-2 conflates DERIVATION with ATTESTATION" · Replaces "DP-2 is wrong".
- S0012-13 · CLAIM · — · L24-29 · "flat vs dimensional … artifact vs concept … layer vs sibling … lifecycle vs structure" · The author lists four of their own level confusions (taxonomy, existence, ontology, this one).
- S0012-14 · CLAIM · — · L31 · "I was about to report 'there is no lifecycle.' There is" · A fifth error was avoided by checking.
- S0012-15 · CLAIM · — · L37 · "docs/knowledge/_meta/lifecycle.md — status: baseline · authority: authoritative · v1.1" · An authoritative lifecycle document exists.
- S0012-16 · CLAIM · — · L43 · "Every document carries both at all times. Most projects wrongly conflate them" · lifecycle.md §1 declares two independent dimensions (status and authority, per L65/L131 [I]).
- S0012-17 · CLAIM · — · L44 · "Author · Peer reviewer · ARB/Chief Architect · Knowledge/Release Manager · Governance Board · ADR-only for frozen → superseded" · lifecycle.md §2 has a role table saying who moves a document.
- S0012-18 · CLAIM · — · L45 · "each transition has guards knowledge-lint can check" · lifecycle.md §3 has transition guards.
- S0012-19 · CLAIM · — · L46 · "'Approved' is objective, not…" · lifecycle.md §4 has quality gates. The quote is truncated in the source.
- S0012-20 · CLAIM · — · L52 · "statuses.yaml state machine | 8, with order + settled" · Lifecycle #1.
- S0012-21 · CLAIM · — · L53 · "ES-006.1 promotion ladder | research → pilot → qualification → standard" · Lifecycle #2.
- S0012-22 · CLAIM · — · L54 · "EEP | plan → review → approval → implement → verify → report → decide" · Lifecycle #3.
- S0012-23 · CLAIM · — · L55 · "Capability | observed → candidate → designed → realized → evidenced → promoted" · Lifecycle #4.
- S0012-24 · CLAIM · — · L56 · "ADR-M status | Draft · Accepted · Controlled · Superseded · Retired" · Lifecycle #5.
- S0012-25 · CLAIM · — · L57 · "Methodology maturity | Observed · Replicated · Reinforced · Working Principle · General Principle" · Lifecycle #6.
- S0012-26 · CLAIM · — · L58 · "four, one per series (R · PMR · ADR · ES)" · Lifecycle #7: identifier lifecycles.
- S0012-27 · CLAIM · — · L59 · "ACTIVE → CONCLUDED (confirmed | falsified | inconclusive)" · Lifecycle #8: the Observation Protocol.
- S0012-28 · CLAIM · — · L61 · "Nine lifecycles, with roles and machine-checkable guards. The gap is NOT 'no lifecycle.'" · Summary.
- S0012-29 · CLAIM · — · L65 · "statuses.yaml and authorities.yaml are declared orthogonal, and they are NOT equally equipped" · Asymmetry thesis.
- S0012-30 · CLAIM · — · L69 · "Values | 8 | 5" · statuses.yaml has 8 values; authorities.yaml has 5.
- S0012-31 · CLAIM · — · L70-71 · "order … none … settled … none" · Authority has no progression field and no terminal marker.
- S0012-32 · CLAIM · "NOT DEFINED" · L72-73 · "Who may move it … NOT DEFINED … Transition guards … NOT DEFINED" · Authority has no role table and no guards.
- S0012-33 · CLAIM · — · L74 · "baseline requires authority: authoritative or derived" · Authority is used inside a status guard.
- S0012-34 · CLAIM · — · L76 · "STATUS HAS A GOVERNED STATE MACHINE. AUTHORITY DOES NOT." · "THE GAP".
- S0012-35 · CLAIM · — · L78 · "There is no rule for who may move derived → authoritative, and no guard for it." · Authority change is ungoverned.
- S0012-36 · DEFINITION · — · L80 · "Attestation is an authority transition (derived → authoritative)." · Defines attestation.
- S0012-37 · CLAIM · — · L80 · "the model cannot express 'attested' — so it collapses 'derived' into 'never authoritative.'" · Explains the cause of the I-4 problem.
- S0012-38 · CLAIM · — · L82 · "The reviewer's diagnosis … now has a mechanical cause rather than an intuition." · The author claims a mechanical explanation.
- S0012-39 · DEFINITION · — · L86 · "Structural (what exists) · Behavioral (what acts) · Evolutionary (how things change)" · Gap categories, attributed to "the review".
- S0012-40 · CLAIM · — · L90 · "G-1 … EVOLUTIONARY … MISSING LIFECYCLE — authority has no transitions … LIFECYCLE" · I-4 / ATTESTS / PROJECTION classified as a lifecycle gap.
- S0012-41 · CLAIM · — · L91 · "G-2 SPECIALIZES … EVOLUTIONARY … MISSING RELATIONSHIP … ONTOLOGY" · The one ontology gap.
- S0012-42 · CLAIM · — · L92 · "G-3 External authority / CONSTRAINS … BEHAVIORAL … obligation ≠ ownership … GOVERNANCE" · Governance gap.
- S0012-43 · CLAIM · — · L93 · "a deployment IS a Knowledge Space. The ontology already permits Knowledge Space contains other Knowledge Spaces" · G-4 (product versus deployment) "DISSOLVES".
- S0012-44 · CLAIM · "not a failure — a CONFIRMATION" · L94 · "Runtime Adapter disappeared in both new domains … Hexagonal behaving correctly" · G-5.
- S0012-45 · CLAIM · — · L96 · "G-4 answers H-3 … Knowledge Spaces NEST, and a deployment is the nested case." · H-3 resolved "using evidence rather than preference".
- S0012-46 · CLAIM · — · L97 · "No concept introduced. The ontology explained the evidence" · Meets the commission's test.
- S0012-47 · CLAIM · — · L103-106 · "LIFECYCLE 1 … ONTOLOGY 1 (+1 dissolved) … GOVERNANCE 1 … NOT A FAILURE 1" · Counts.
- S0012-48 · CLAIM · CONFIRMED · L108-110 · "ONLY ONE OF THE FOUR IS GENUINELY ONTOLOGICAL … lifecycle and governance wearing ontology's clothes." · The commission's hypothesis is confirmed.
- S0012-49 · CLAIM · — · L118-119 · "idea → research → draft → discovery → reviewed → approved → baseline → frozen … idea and research are ABSENT" · The lifecycle.md diagram and the statuses.yaml enum disagree.
- S0012-50 · CLAIM · — · L120 · "as knowledge_type values in knowledge-types.yaml" · `idea` and `research` exist as types instead.
- S0012-51 · CLAIM · — · L123 · "a type/status conflation inside the very document whose §1 warns 'most projects wrongly conflate them.'" · The authoritative document contains a self-inconsistency.
- S0012-52 · CORRECTION-OR-RETRACTION (hedge) · — · L125 · "Recorded as an observation, not a defect claim … knowledge-lint enforces the enum." · The direction of the defect is left open.
- S0012-53 · CLAIM · HEALTHY · L131 · "status ⟂ authority … declared orthogonal" · Healthy pair.
- S0012-54 · CLAIM · HEALTHY · L132 · "These are independent axes" · ADR-M status and methodology maturity are a healthy pair.
- S0012-55 · CLAIM · "possible duplication" · L133 · "statuses.yaml (8) vs ADR-M (5) … both are document lifecycles" · Possible duplicate lifecycles.
- S0012-56 · CLAIM · "possible duplication" · L134 · "ES-006.1 ladder (4) vs methodology maturity (5) … both are promotion ladders" · Possible duplicate ladders.
- S0012-57 · OPEN-QUESTION (embedded) · — · L136 · "What is unreconciled is whether the last two pairs are two axes or two names for one." · Unresolved.
- S0012-58 · CLAIM · — · L142 · "'There is no lifecycle' | nine exist" · Not a gap.
- S0012-59 · CLAIM · — · L143 · "§2 names a performer for every status transition" · Status-transition governance exists.
- S0012-60 · CLAIM · — · L144 · "§4 defines 'Approved' objectively" · Quality gates exist.
- S0012-61 · CLAIM · — · L145 · "it is UNDERSPECIFIED, not false — true until attestation, and attestation is ungoverned" · Status of I-4.
- S0012-62 · CLAIM · — · L146 · "Knowledge-Space nesting covers it" · Product versus deployment needs no new concept.
- S0012-63 · CORRECTION-OR-RETRACTION · "WITHDRAWN … ILL-POSED" · L152 · "Should authority have a governed state machine?" · LG-1 is struck through and withdrawn on 2026-08-02.
- S0012-64 · DEFINITION · — · L152 · "authority is TWO dimensions in one field — immutable PROVENANCE (generated, derived) × act-changed STANDING (authoritative, provisional, historical)." · Splits authority into two dimensions.
- S0012-65 · CLAIM · — · L152 · "Provenance CANNOT have a state machine; standing changes by governance ACTS." · Core principle.
- S0012-66 · CLAIM · — · L152 · "Superseded by PM-1/PM-2 — record: KnowledgeOS_Engineering_Progression_Model.md §4 · ARB + PD-3's owner" · Points elsewhere for the replacement.
- S0012-67 · OPEN-QUESTION · — · L153 · "Restate DP-2 to separate DERIVATION from ATTESTATION? This is CV-1, now with a mechanical cause" · LG-2; authority: ARB.
- S0012-68 · OPEN-QUESTION · — · L154 · "lifecycle.md shows idea/research as statuses; statuses.yaml defines them as types. Which is canonical?" · LG-3; authority: PD-3's owner.
- S0012-69 · OPEN-QUESTION · — · L155 · "two axes or two names?" · LG-4; authority: ARB.
- S0012-70 · OPEN-QUESTION · — · L156 · "Admit SPECIALIZES — the one genuinely ontological gap" · LG-5; authority: ARB.
- S0012-71 · OPEN-QUESTION · — · L157 · "Where does obligation attach, given the model has only ownership?" · LG-6; authority: ARB.
- S0012-72 · DECISION-OR-STANDING · "(resolved)" · L158 · "Knowledge Spaces NEST — closed by G-4" · H-3 closed.
- S0012-73 · CLAIM · — · L166-171 · "status has a governed state machine with roles and lint-checkable guards; authority has neither." · Closing restates the asymmetry, the hypothesis, H-3 closed, and the enum disagreement.
- S0012-74 · CLAIM · — · L173 · "It found a missing state machine — for authority — and everything downstream of that absence looked like an ontology problem." · Closing thesis.
- S0012-75 · CLAIM · — · L174 · "One asymmetry between two YAML files explains the single most consequential failure in the cross-product validation." · Strong causal claim.
- S0012-76 · DECISION-OR-STANDING · — · L180 · "Nothing in this document executes." · No executive force.

## (b) Five most important ideas

1. **Authority = immutable provenance × act-changed standing (L152).** This is the clearest statement in the set that origin (a fact about records) and standing (a result of acts) are different dimensions.
2. **"Governance created the authority, not the derivation" (L16-22).** Derived → reviewed → attested → released: attestation is the act that confers authority. This is central to recognising acts versus records.
3. **Status is governed and authority is not (L65-80).** The document locates the absence of governed authority transitions mechanically, and says this makes "attested" inexpressible.
4. **The authoritative lifecycle document disagrees with the enforced enum (L114-125).** An "authoritative" text and executable enforcement can diverge. This bears on which one carries validity.
5. **Failures are classified as ontology, lifecycle or governance (L86-110).** Many apparent ontology failures are lifecycle or governance gaps. Obligation versus ownership (G-3, LG-6) is named as a governance gap.

## (c) Ambiguities and inconsistencies

- **The withdrawal undercuts the unrevised core.** LG-1 is withdrawn as ill-posed because provenance cannot have a state machine (L152). But §2's headline (L76), the closing (L167, L173: "a missing state machine — for authority") and the traceability (L178) still present "authority has no state machine" as the finding. Those passages were not revised.
- **Nesting is called both available and established.** G-4/H-3 is "resolved … using evidence" (L96), but the evidence given is only that the ontology "already permits" nesting (L93). Permission is not evidence that nesting happens. [I]
- **Gap counts.** "Only one of the four" (L108) means four gaps (G-1..G-4), yet five are listed (G-5 is not a failure). G-4 is counted under ONTOLOGY as "+1 dissolved". So whether one or two gaps are ontological depends on how "dissolved" is counted.
- **Possible double count in "nine lifecycles".** lifecycle.md's status machine and #1, the statuses.yaml state machine, may be the same lifecycle (doc + enum). [I]
- **"Five lifecycle vocabularies" (L127)** are not enumerated. The table lists four pairs spanning six names.
- **"Status" undefined:** §1 says lifecycle.md's "Two independent dimensions" without naming them. Status and authority are presumably meant (from L65/L131) [I].
- **"The review" (L86) and "the reviewer" (L82)** are not identified.
- **Medical example:** the "discharge summary" (L16) appears without context. It presumably comes from a cross-product projection [I].
- **Undefined here:** I-4, DP-2, CV-1, PD-3, ATTESTS, PROJECTION, SPECIALIZES, CONSTRAINS, INSTANTIATES, H-3, PM-1/PM-2, and "the cross-product validation".
- **Possible tension:** "There is no transition governance" is said not to be a gap (L143), while authority transitions are said to be ungoverned (L78). This is consistent only if "transition governance" means status transitions alone.
- **Unclear value set:** authorities.yaml has 5 values (L69). LG-1 lists five (generated, derived, authoritative, provisional, historical), but it is not stated that these are the yaml's values.

---

# S0013 — KnowledgeOS Architecture Synthesis Matrix (125 lines)

## (a) Status, authority and dates

- **Kind:** "ARCHITECTURE SYNTHESIS — reconcile and classify EVERYTHING discovered; discover NOTHING." (L5).
- **Status:** "CANDIDATE — NOT ADOPTED" (L6).
- **Authority:** "Generated — never authoritative without human review" (L7).
- **Role:** "This matrix does not BE the synthesis ruling — it PREPARES it" (L9). It is decision-readiness input to D-8, which is a human decision.
- **Disposition:** "Submitted to the Decision Authority. Nothing in this document executes." (L124).
- **Dates of the file itself:**
  - Commission 2026-08-03 (L8, L122).
  - REV 2 review 2026-08-03 (L11): the file was revised after a review the same day.
  - Header readings "read 2026-08-03" (K-13, L61).
- **Dates it quotes:** "DA clarification 2026-07-27" (C-1, L32); "ARB refinement 2026-07-11" (C-11, L42).

## Items

- S0013-01 · DEFINITION · — · L5 · "reconcile and classify EVERYTHING discovered; discover NOTHING. No new concept · every classification cites its commission · anything without evidence stays a hypothesis." · Kind and rules.
- S0013-02 · DEFINITION (status/authority) · — · L6-7 · "CANDIDATE — NOT ADOPTED … Generated — never authoritative without human review" · Self-status.
- S0013-03 · DEFINITION · — · L8 · "Discovery creates candidates. Validation removes weak candidates. Reconciliation aligns. Synthesis produces the canonical model." · The commission's stated sequence (2026-08-03).
- S0013-04 · DECISION-OR-STANDING · — · L9 · "This matrix does not BE the synthesis ruling — it PREPARES it … D-8 is a human decision." · Role is limited to decision input.
- S0013-05 · DECISION-OR-STANDING · "accepted as-is" · L11 · "REV 2 — review 2026-08-03: accepted as-is; two refinements DEFERRED; the programme's mode changes" · Revision note.
- S0013-06 · CLAIM · — · L15 · "approve this matrix as the current architectural INVENTORY — not canonical architecture" · The reviewer's proposal 1.
- S0013-07 · CLAIM · — · L15 · "freeze Strategic Discovery except the docket · redirect effort to EXERCISING KnowledgeOS · measure against reality, not further literature … all four are ARB decisions … enacted by nobody here" · Three companion proposals, all pending ARB.
- S0013-08 · DECISION-OR-STANDING · DEFERRED · L16 · "Concern · Asset · Knowledge State · Decision Authority · Evidence … buckets are GOVERNANCE states while assets are ARCHITECTURE" · Deferred refinement A.
- S0013-09 · DECISION-OR-STANDING · DEFERRED · L17 · "split Knowledge Lifecycle from Decision Queue (Rejected is an outcome, not a state; Open Question is unresolved work, not knowledge)" · Deferred refinement B.
- S0013-10 · CLAIM · — · L17 · "any such lifecycle must RECONCILE WITH ES-006.1 … the reviewer's sketch orders Candidate before Hypothesis, the reverse of this corpus's usage. A second competing ladder would repeat the second-rulebook error" · Check-before note.
- S0013-11 · DECISION-OR-STANDING · — · L18 · "EXERCISE the architecture; only operational evidence reopens discovery" · New operating stance ("the CAP-001 principle").
- S0013-12 · DEFINITION · — · L22 · "'Canonical' below means ADOPTED, FROZEN, RULED, or ENFORCED BY HUMANS — never 'validated by this programme.'" · Definitional guard.
- S0013-13 · CLAIM · — · L22 · "My passes validate candidates; they cannot confer canon (R-34: engineering never accepts its own work; ES-004.1: retrospectives recommend, never declare)" · The programme cannot confer canon on itself.
- S0013-14 · CLAIM · — · L22 · "every Canonical row below is human-made, and every product of this discovery programme sits at Candidate or below." · Consequence of the guard.
- S0013-15 · CORRECTION-OR-RETRACTION · — · L24 · "'one owner ⇒ one context' weakened to 'shared ownership is evidence against separate contexts, insufficient alone'" · Challenge accepted.
- S0013-16 · CORRECTION-OR-RETRACTION · — · L24 · "'the space map is the real ownership map' downgraded finding → HYPOTHESIS (n=1 platform)" · Challenge accepted.
- S0013-17 · CLAIM (canonical) · — · L32 · "Product Primacy — the platform exists solely to improve PublicDigit delivery; two platform-only iterations trigger over-evolution review" · C-1; AIP-14 · ADR-AIP-02 · R-23 · DA clarification 2026-07-27.
- S0013-18 · CLAIM (canonical) · — · L33 · "KnowledgeOS is prospective — Supporting Subdomain; gate = a second real adopting product; gate not open" · C-2.
- S0013-19 · CLAIM (canonical) · — · L34 · "Two lifecycles, never conflated (governance-artifact vs governed-work) | GEP-F1 — CANON" · C-3.
- S0013-20 · CLAIM (canonical) · — · L35 · "Platform Capability Pattern PGP-01..05 (one capability ↔ one invariant · fail closed · prevents-never-repairs) | FROZEN" · C-4.
- S0013-21 · CLAIM (canonical) · — · L36 · "Methodology Constitution MC-01..08 | ADOPTED (sponsor+ARB, ADR-M-012)" · C-5.
- S0013-22 · CLAIM (canonical) · — · L37 · "Strategic DDD Constitution/Protocol SD-1..7 (SD-1 binds discovery to Certified Domain Knowledge Release) | Round47-00/OP" · C-6.
- S0013-23 · CLAIM (canonical) · — · L38 · "One authoritative artifact per topic+context | I-5 — lint-ENFORCED (single_authoritative)" · C-7.
- S0013-24 · CLAIM (canonical) · — · L39 · "AI evaluates and recommends; authority remains with governance · evidence ≠ acceptance" · C-8; EDM · R-34 · ES-004.1.
- S0013-25 · CLAIM (canonical) · — · L40 · "Governance precedes automation; automation never defines governance | Reference Architecture §1 (N-10 bar)" · C-9.
- S0013-26 · CLAIM (canonical) · — · L41 · "The enacted rulings corpus — R-1..R-77 · DR-1..DR-8 · 38C-15 · the freezes as declared acts" · C-10.
- S0013-27 · CLAIM (canonical) · — · L42 · "Viewpoint relation on record: 'complementary siblings, not a hierarchy' | ARB refinement 2026-07-11, in the EDM" · C-11.
- S0013-28 · CLAIM (canonical) · — · L43 · "The frozen Phase-02 six-context map BC-1..BC-6 (canonical though never-consulted — the tension is docketed, not dissolved)" · C-12.
- S0013-29 · CLAIM (candidate) · — · L49 · "Dimensional-with-archetypes meta-model (ELEMENT = KIND × dimensions; five converging passes)" · K-1; waits on MM-1 · D-8.
- S0013-30 · CLAIM (candidate) · strong / weaker / weakest · L50 · "DOMAIN · PROCESS · CAPABILITY · KNOWLEDGE · ARTIFACT · RUNTIME ASSET · PROJECTION (strong); CONTAINER (weaker — generates at n=1); PURPOSE (weakest — unratified mission)" · K-2.
- S0013-31 · CLAIM (candidate) · — · L51 · "The four partial orders — authority · production · realization · containment (replacing any single stack)" · K-3.
- S0013-32 · CLAIM (candidate) · — · L52 · "11 invariant relationships vs 8 implementation choices (10 invariants survived cross-product projection; I-4 corrected to a lifecycle gap)" · K-4.
- S0013-33 · CLAIM (candidate) · — · L53 · "DP-n as the capability anchor (observed 1:1 in the Catalog; never declared as a concept)" · K-5; waits on RO-1.
- S0013-34 · DEFINITION (candidate) · — · L54 · "Knowledge Space = declared boundary + owner + constitution (not a folder)" · K-6; waits on RO-2 (nesting).
- S0013-35 · DEFINITION (candidate) · — · L55 · "Authority = immutable PROVENANCE × act-changed STANDING · four progression kinds" · K-7; source: Engineering Progression Model; waits on the docket.
- S0013-36 · CLAIM (candidate) · — · L56 · "The strategic domain set PD-1/2/3/5 (PD-6 below bar; PD-5 split protocol/records)" · K-8.
- S0013-37 · CLAIM (candidate) · — · L57 · "Viewpoints as PRESENTATION (siblings, no arrows; behavioral = EDM+EEP; structural slot contested)" · K-9.
- S0013-38 · CLAIM (candidate) · — · L58 · "Decision-readiness as the platform's product for authority decisions" · K-10; waits on EP-02 of the next real cycle (U-DM-4).
- S0013-39 · CLAIM (candidate) · — · L59 · "FREEZE and RETIRE as insufficiency evidence for the decision catalog (n≥3 · PM-6)" · K-11.
- S0013-40 · CLAIM (candidate) · "softened form" · L60 · "PKS addresses the context-engineering problem space and EXTENDS it with governance" · K-12; waits on Stream 4 evidence.
- S0013-41 · CLAIM (candidate) · — · L61 · "EDM (DRAFT, ARB-accepted, adoption test pending) · Reference Architecture (DRAFT) · Knowledge Metamodel (CANDIDATE, gate OQ-ENG-004)" · K-13: canon-track artifacts are themselves only candidates.
- S0013-42 · CLAIM (candidate) · — · L62 · "The mission is ENACTED via 8 artifacts, not vacant" · K-14; waits on D-5 ratification.
- S0013-43 · CLAIM (candidate) · — · L63 · "candidate-unowned = ladder working; operating-unowned = defect (sole instance: Runtime Adapter)" · K-15.
- S0013-44 · CLAIM (hypothesis) · — · L69 · "The space map is the platform's real ownership/context map | n=1 platform (downgraded this pass)" · H-1.
- S0013-45 · CLAIM (hypothesis) · — · L70 · "GOVERNANCE as a fifth viewpoint (its asset exists — the DA & Verification Matrix) | asset found; framing undecided" · H-2.
- S0013-46 · CLAIM (hypothesis) · — · L71 · "PURPOSE more fundamental than CONTAINER | n=0" · H-3.
- S0013-47 · CLAIM (hypothesis) · — · L72 · "KnowledgeOS as a 'Knowledge Architecture Platform' | n=1; waits on second adopter" · H-4.
- S0013-48 · CLAIM (hypothesis) · — · L73 · "generates → PKS — the loop's head edge (the platform's distinctive bet) | n=0 inside AND outside" · H-5.
- S0013-49 · CLAIM (hypothesis) · — · L74 · "capability may read across a space boundary, never own | n=1 capability" · H-6 (I-12 / H-1).
- S0013-50 · CLAIM (hypothesis) · — · L75 · "Space NESTING (containment vs adjacency) | unevidenced" · H-7.
- S0013-51 · CLAIM (hypothesis) · — · L76 · "DOMAIN's decision-silence is dormant, not permanent | untestable at n=1" · H-8.
- S0013-52 · CLAIM (hypothesis) · — · L77 · "EVIDENCE-STATUS · OPERATIONAL-STATE as meta-model dimensions | proposed, undocketed rungs" · H-9.
- S0013-53 · OPEN-QUESTION (register) · — · L83 · "The ARB Decision Docket (the agenda — 11 packages) | D-9a first (define the metric) · D-8 ontology reconciliation … · D-5 mission ratification · MM-1 adopt RQ-002" · Main open register.
- S0013-54 · OPEN-QUESTION (register) · — · L84 · "RO-1 DP-n adoption · RO-2 nesting · RO-3 I-7 breach disposition · RO-4 Runtime Adapter owner" · Relationship Ontology opens.
- S0013-55 · OPEN-QUESTION (register) · — · L85 · "PM-6 retirement · the backlog tiers" · Research governance.
- S0013-56 · OPEN-QUESTION (register) · — · L86 · "U-MM-1 (kind vs dimension) · U-MM-3 · U-ONT-1..5 · U-DM-1..5 · U-EDV-1..3 · U-VO-1..4 — … none resolved by reasoning" · Programme unknowns.
- S0013-57 · OPEN-QUESTION (register) · — · L87 · "the two closed verdict sets (ES-003.1 vs CAP-001 §5 — unscoped) · the ES-005.3 research-clause tension" · Standing collisions.
- S0013-58 · CORRECTION-OR-RETRACTION (rejected) · — · L93 · "The flat A–G category list as a meta-model | Meta-Model Discovery §1 (RQ-002's smoking gun)" · R-1.
- S0013-59 · CORRECTION-OR-RETRACTION · — · L94 · "'Governance' and 'Product' as kinds · 'Knowledge Asset' as one thing" · R-2.
- S0013-60 · CORRECTION-OR-RETRACTION · — · L95 · "'Domains own Capabilities' | … (no CAP↔PD artifact; DP-n owns)" · R-3.
- S0013-61 · CORRECTION-OR-RETRACTION · — · L96 · "The single-stack hierarchy (Purpose→…→Artifact) | Ontology Discovery §4 (1 supported · 1 refuted · 4 unevidenced)" · R-4.
- S0013-62 · CORRECTION-OR-RETRACTION · — · L97 · "'KnowledgeOS is the Core Domain / the real product' (the brainstorming premise) | … contradicts AIP-14 ruling" · R-5.
- S0013-63 · CORRECTION-OR-RETRACTION · — · L98 · "The MVK 15-file extraction boundary | MVK Bootstrap Validation (FAIL; AR-1 realized)" · R-6.
- S0013-64 · CORRECTION-OR-RETRACTION · — · L99 · "'Two missions' · 'mission layer vacant' · the INTENDED evidence grade" · R-7.
- S0013-65 · CORRECTION-OR-RETRACTION · — · L100 · "'0 of 11 responsibilities enforcing' | … (deny×19/ask×22 ARE enforced)" · R-8.
- S0013-66 · CORRECTION-OR-RETRACTION · — · L101 · "'0 traversals' of the evidence back-edge (as a generalization) | Phase B blind review (n≈3: R-36 · R-41 · R-63)" · R-9.
- S0013-67 · CORRECTION-OR-RETRACTION · — · L102 · "I-4 as falsified (projection-authority) · LG-1 | … (a lifecycle gap, not an ontology falsehood) · Progression Model (ill-posed)" · R-10.
- S0013-68 · CORRECTION-OR-RETRACTION · — · L103 · "A second Engineering Decision Model | EDM Validation Q7 (three-fold UL violation)" · R-11.
- S0013-69 · CORRECTION-OR-RETRACTION · — · L104 · "'One owner ⇒ one context' (as stated) | this synthesis, on review challenge (ownership is evidence, not proof)" · R-12.
- S0013-70 · CLAIM · — · L112-116 · "Canonical 12 … Candidate 15 … Hypothesis 9 … Open 5 registers … Rejected 12" · Bucket counts. They match the rows; together with the registers they sum to 53 (L122).
- S0013-71 · CLAIM · — · L116 · "the falsification record is as long as the candidate record — the discovery was real, not confirmatory" · Inference from the counts.
- S0013-72 · CLAIM · — · L118 · "every row has exactly one bucket, one citation, and (where applicable) one gate. Nothing is in two states; nothing is stateless." · Completeness and uniqueness claim.
- S0013-73 · CLAIM · — · L118 · "The endless-discovery risk … is closed by construction — there is nothing left to discover that is not already a named open question with a named authority." · Closure claim.
- S0013-74 · CLAIM · — · L122 · "53 concerns reconciled and classified across five buckets, every row citing its commission" · Traceability.
- S0013-75 · DECISION-OR-STANDING · — · L124 · "Nothing in this document executes. The docket remains the agenda, and D-8 is where synthesis becomes architecture." · Disposition.

## (b) Five most important ideas

1. **The definitional guard (L22).** "Canonical" means human-adopted, frozen, ruled or enforced, never "validated by this programme" (R-34, ES-004.1). Validity of standing comes only from human acts, so the evaluator cannot confer canon on itself.
2. **C-8 (L39).** "AI evaluates and recommends; authority remains with governance · evidence ≠ acceptance." This separates evidence and recommendation from the act of acceptance.
3. **K-7 (L55).** Authority = immutable provenance × act-changed standing, now carried as a candidate rather than as a finding.
4. **The bucket structure (L28-104).** Canonical, Candidate, Hypothesis, Open and Rejected, with n-counts and gates. It gives an explicit evidence-status taxonomy; H-5, the platform's "distinctive bet", is at n=0.
5. **"Only operational evidence reopens discovery" (L18).** Together with "measure against reality, not further literature" (L15), this moves validity from documents to operation.

## (c) Ambiguities and inconsistencies

- **"Enforced by humans" versus lint.** The guard defines Canonical as "ENFORCED BY HUMANS" (L22), yet C-7 is canonical because it is "lint-ENFORCED" (L38), which is machine enforcement.
- **Canonical content lives in non-canonical artifacts.**
  - C-11 sits "in the EDM" (L42), while K-13 says the EDM is "DRAFT, … adoption test pending" (L61).
  - C-9 cites Reference Architecture §1, which K-13 calls DRAFT.
  - C-6's instrument, Round47-00/OP, is given no adoption status.
- **"Nothing is in two states" (L118) does not hold [I].**
  - Nesting appears as a candidate gate (K-6, RO-2), a hypothesis (H-7, "unevidenced") and an open question (RO-2).
  - I-4 appears in K-4 ("corrected to a lifecycle gap") and in R-10.
  - The authority topic appears in K-7 (candidate) and R-10 (LG-1 rejected). These are different framings, but the claim of no overlap is strong.
- **Identifier collision inside the document.** H-3 is "PURPOSE more fundamental than CONTAINER" (L71), but H-7's source column cites "H-3 (Relationship Ontology)" (L75), a different H-3. H-6 likewise cites "I-12 / H-1" (L74), colliding with this document's own H-1 (L69). IDs are reused across registers.
- **K-4 numbers are unclear:** "11 invariant relationships … 10 invariants survived … I-4 corrected". It is not clear whether I-4 is among the 11 and whether the count is 10 or 11.
- **"53 concerns"** counts five "registers" as concerns. The Open bucket aggregates many questions ("the agenda — 11 packages", U-*). The total therefore depends on counting registers as units.
- **"Closed by construction" (L118)** is an overclaim relative to the "Programme unknowns" register (L86) and the deferred refinements. [I]
- **Formatting break:** L18 (REV 2 row 4) lacks the leading ">" of the surrounding blockquote, so the table inside the blockquote is broken.
- **Unidentified people:** "the reviewer" (L9, L15-17) is not named. "My passes" (L22) means an unidentified author.
- **Undefined here:** most identifiers (AIP-14, GEP-F1, PGP, MC, SD, I-5, EDM, N-10, DR-, 38C-15, MM-1, RQ-002, RO-*, PD-*, PM-6, OQ-ENG-004, CAP-001, AR-1, and so on).
- **C-12 tension:** C-12 is "canonical though never-consulted" (L43), which raises whether canon that is never consulted actually governs. Acknowledged as "docketed".

---

# S0014 — KnowledgeOS Phase B.5 Evidence Reconciliation (112 lines)

## (a) Status, authority and dates

- **Kind:** "EVIDENCE GOVERNANCE. Not a review · not Phase C" (L5).
- **Status:** "CANDIDATE — NOT ADOPTED. This document is the GATE before canonical architecture; it opens nothing by itself" (L6).
- **Authority:** "Generated — never authoritative without human review" (L7).
- **Self-declaration:** "platform-only work and counts toward the AIP-14 exposure it records (D-9)" (L10).
- **Disposition:** "Submitted to the Decision Authority. Nothing in this document executes. Phase C does not begin until the DA says so." (L111).
- **Date of the file itself:** commission "Phase B.5, 2026-08-03" (L8, L109).
- **No external dates are quoted.** The title says "Phase B.5" while the filename says "Phase_B".

## Items

- S0014-01 · DEFINITION · — · L5 · "EVIDENCE GOVERNANCE. Not a review · not Phase C · no redesign · no ADR · no ontology change · no rewriting of the frozen corpus." · Kind and exclusions.
- S0014-02 · DEFINITION (status) · — · L6 · "This document is the GATE before canonical architecture; it opens nothing by itself" · Self-status.
- S0014-03 · DEFINITION (authority) · — · L7 · "Generated — never authoritative without human review" · Self-authority.
- S0014-04 · DATE · — · L8 · "Phase B.5, 2026-08-03 — Principal Strategic DDD Architect / Principal Knowledge Engineer / Chief Software Architect" · Commission.
- S0014-05 · DECISION-OR-STANDING · — · L9 · "every Phase B finding is classified into EXACTLY ONE category … a defect (C) and its required decision (D) are different things" · Classification rule; spanning findings are split.
- S0014-06 · CLAIM · — · L10 · "this document is itself platform-only work and counts toward the AIP-14 exposure it records (D-9)" · Self-referential cost declaration.
- S0014-07 · CLAIM · — · L14 · "Strategic DDD discovery is substantially complete. This document is not discovery — it is KNOWLEDGE GOVERNANCE" · Declares a phase transition. The four categories are defined as learned (A), corrected (B), process changed (C), and decision still required (D).
- S0014-08 · DECISION-OR-STANDING · — · L16 · "Nothing flows from Phase B into Phase C except through this classification." · Gate rule.
- S0014-09 · CLAIM · HIGH · L22 · "Twelve conclusions, each derived twice by mutually blind paths. Confidence: HIGH — the strongest this programme can produce without a second product." · Basis of Category A.
- S0014-10 · CLAIM · — · L26 · "KnowledgeOS is a governed hypothesis; the gate is shut; the Vision is sponsor-owned and unapproved" · A-1; "it IS the architecture's frame".
- S0014-11 · CLAIM · — · L27 · "Product Primacy governs; 'laboratory' is not canonical | ADR-AIP-02, derived independently twice" · A-2.
- S0014-12 · CLAIM · — · L28 · "The EKP is a real governed context with a named individual owner | Knowledge-Constitution frontmatter, found blind" · A-3; feeds C-6/D-6.
- S0014-13 · CLAIM · — · L29 · "Engineering Governance is the strongest bounded context | 5–6 criteria, twice" · A-4.
- S0014-14 · CLAIM · — · L30 · "The reusable kernel: EEP · ES-001..006 (pointers rebound) · Decision Model · Reference Architecture · Tactical DDD Principles · CAP-001 Domain/App/Shared · schema YAMLs minus bounded-contexts.yaml" · A-5; "two near-identical lists, blind".
- S0014-15 · CLAIM · — · L31 · "designed-for-reuse ≠ demonstrated-reusable; exactly one binding exists | MVK FAIL + blind review, convergent" · A-6; "bounds every reuse claim".
- S0014-16 · CLAIM · — · L32 · "Method (D-2/PD-2) is a distinct governed body — via scope exclusion, not ADR-series separation" · A-7.
- S0014-17 · CLAIM · — · L33 · "Three knowledge ontologies coexist unreconciled | both reviews | blocks — see D-8" · A-8.
- S0014-18 · CLAIM · — · L34 · "Enforcement lives runtime-side; capabilities are advisory (Boundaries/Triggers/Advisory)" · A-9.
- S0014-19 · CLAIM · — · L35 · "The invariant set (human authority · produced-never-asserted · append-only · one-rule-one-home · fail-closed · burden-reversed · one-owner) | reproduced with citations" · A-10.
- S0014-20 · CLAIM · — · L36 · "PMR-10's manual-adoption failure is operational evidence against protocol-only governance … (as evidence, not as a mandate to automate)" · A-11.
- S0014-21 · CLAIM · — · L37 · "the blind reviewer prescribed the MVK experiment unaware it had run; predicted and actual measurements agree … the isolated-session instrument is now doubly validated" · A-12: method cross-validated; changes process.
- S0014-22 · CORRECTION-OR-RETRACTION · HIGH · L43 · "'0 traversals / the arrow has never been traversed' is RETIRED as generalized." · B-1.
- S0014-23 · CLAIM · HIGH · L43 · "the evidence→platform back-edge has run ≈3 times, informally (R-36 · R-41/ES-004.3 · R-63); the formal ES-006.1 ladder ≈2 (R-36; R-39 as recorded exception); EAD-1's narrow claim (PublicDigit→PKS: none) stands" · B-1 agreed fact; owner "me (claim author) → DA acknowledges (IR-5)".
- S0014-24 · CORRECTION-OR-RETRACTION · HIGH · L44 · "I-8 over-granted: 'governance precedes automation' is deliberately unnumbered in canon — an invariant-in-waiting, not an invariant" · B-2; Phase C to list it as a candidate.
- S0014-25 · CORRECTION-OR-RETRACTION · HIGH · L45 · "The Phase B review misattributed a 'laboratory' quote to its commission prompt … the reviewer's handling (test → reject) was correct" · B-3; "immaterial to all findings".
- S0014-26 · CORRECTION-OR-RETRACTION · HIGH · L46 · "'all 9 rediscoveries drew on unindexed regions' remains refuted at n=10 (occurrence #10 came from an indexed region)" · B-4; carried forward so the claim is not resurrected.
- S0014-27 · CLAIM · HIGH · L52 · "PROPOSING-BEFORE-SEARCHING — now ELEVEN occurrences, the eleventh being an entire domain-model track run without consulting the frozen Phase-02 six-context map" · C-1.
- S0014-28 · CLAIM · — · L52 · "'discover → model → FORGET → rediscover' is the failure KnowledgeOS exists to prevent — this defect is therefore a PLATFORM DESIGN REQUIREMENT CANDIDATE" · C-1 elevated; ARB to admit "model amnesia prevention" ("n=11 clears any repetition bar"); PG-8 index named as the remedy candidate.
- S0014-29 · CLAIM · HIGH · L53 · "PA commissions drove real construction (CAP-001) with no ruling minted — 'the register is provably not the complete record of authorizations'" · C-2.
- S0014-30 · DECISION-OR-STANDING (proposed fix) · — · L53 · "commissions that authorize work must mint" · C-2 process fix; "traceability thesis weakened at its root".
- S0014-31 · CLAIM · HIGH · L54 · "The evidence-line-no-gate-covers class recurred 3× in one week (R-43/R-50 false gate evidence · R-59's invented rule · R-62-class recording gaps)" · C-3; "acceptance can rest on unsound evidence"; no automation proposed (N-10).
- S0014-32 · CLAIM · HIGH · L55 · "Placement is executable and repeatedly bypassed — 93 PKS_* files in the folder the roots ADR exists to unmix" · C-4.
- S0014-33 · CLAIM · — · L55 · "Platform_Capability_Pattern passes ES-005.3's litmus and sits product-side (predates EM-001, never migrated)" · C-4: a new instance, found blind. Moves are R-37-gated: record, don't move.
- S0014-34 · CLAIM · MEDIUM · L56 · "Register backward-links were fixed 'for this case' only (R-53) — no systematic mechanism" · C-5.
- S0014-35 · CLAIM · "HIGH (inferred from owner fields)" · L57 · "Separation of duties is real as PROCESS, unevidenced as PEOPLE — and this limit is stated NOWHERE (role separation is session-simulated)" · C-6; leads to D-6.
- S0014-36 · CLAIM · MEDIUM-HIGH · L58 · "The frozen strategic model lags operational reality and the amendment path (AIP-13) is unused — the R-63/R-64 boundary is absent; BC-5 shows no live signature" · C-7; leads to D-3.
- S0014-37 · CLAIM · MEDIUM · L59 · "The AIP-14 Platform Cost metric has no recent derivation while platform-only output density rose sharply (this track, including this document)" · C-8: "the over-evolution tripwire cannot fire if the metric is not computed".
- S0014-38 · DECISION-OR-STANDING · — · L63 · "None of these is decided here. Each names its authority and what it blocks in Phase C." · Category D framing.
- S0014-39 · OPEN-QUESTION (decision required) · — · L67 · "Dispose E-1 — the EKP's consumption model is recorded FALSIFIED, disposition PENDING ARB" · D-1; ARB; blocks any knowledge-platform section ("The single biggest architectural liability for any KnowledgeOS ambition").
- S0014-40 · OPEN-QUESTION · — · L68 · "Mint CAP-001's authorization/acceptance into the register — or rule that the register is not the complete authorization record (IR-1)" · D-2; DA; evidence: catalog "unauthorized" vs README "REALIZED" vs a silent register; blocks capability-status claims.
- S0014-41 · OPEN-QUESTION · — · L69 · "Amend the frozen Phase-02 map via AIP-13 — admit/reject the R-63/R-64 boundary; dispose BC-5's dormancy (IR-3)" · D-3; ARB; blocks the context map.
- S0014-42 · OPEN-QUESTION · — · L70 · "Ratify (or decline) the ES set, the Reference Architecture, the Metamodel — ends the 'governs by a proposed constitution' contradiction" · D-4; ARB; Phase C may proceed labelled PROPOSED.
- S0014-43 · OPEN-QUESTION · — · L71 · "Ratify the enacted mission (MQ-1)" · D-5; sponsor; carried as "enacted, unratified".
- S0014-44 · OPEN-QUESTION · — · L72 · "State in governance that role separation is session-simulated, with its limits (IR-4)" · D-6; DA.
- S0014-45 · OPEN-QUESTION · — · L73 · "Issue the canonical register list (G-1) — the standing cause of INCONCLUSIVE" · D-7; "Authority" (unspecified).
- S0014-46 · OPEN-QUESTION · — · L74 · "Reconcile the three knowledge ontologies (EKP schema · Metamodel · RQ-002) — or rule their scopes" · D-8; ARB; blocks the knowledge model.
- S0014-47 · OPEN-QUESTION · — · L75 · "Derive the Platform Cost metric and run the AIP-14 over-evolution check (OQ-K5) — this track's own output density is the trigger evidence" · D-9; ARB; "gates whether Phase C work may proceed AT ALL".
- S0014-48 · OPEN-QUESTION · — · L76 · "Admit C-1's 'model amnesia prevention' as a KnowledgeOS requirement candidate" · D-10; ARB; n=11.
- S0014-49 · DECISION-OR-STANDING (gate rows) · — · L82-89 · "Frame … A-1 · A-2 | Kernel … A-5 · A-6 | Contexts … A-3 · A-4 · A-7 / D-3 | Knowledge model … D-1 + D-8 | Invariants … A-10, with B-2's downgrade | Lifecycle … B-1's n≈3 fact — never '0 traversals' | Capabilities … A-9 · A-11 / D-2 | Whether Phase C runs at all … D-9" · What each Phase C section may use and what blocks it.
- S0014-50 · CLAIM · — · L91 · "Success criterion met by construction: Phase C receives only validated architecture (A), reconciled facts (B), classified process improvements (C), and explicit pending decisions (D). No unresolved evidence flows through." · Self-assessment.
- S0014-51 · CLAIM · — · L99-102 · "12 validated conclusions — the only permissible Phase C base … 4 factual corrections … 8 process defects … 10 decisions" · Closing counts. They match the tables.
- S0014-52 · CLAIM · — · L101 · "'discover → model → forget → rediscover' is the disease KnowledgeOS exists to cure — and this programme has it" · Self-indictment.
- S0014-53 · CLAIM · — · L104 · "unreconciled evidence written into architecture becomes the next generation's rediscovery." · Rationale for the gate.
- S0014-54 · DECISION-OR-STANDING · — · L105 · "Phase C may begin on A and B — and must stand waiting at exactly three doors: E-1, the frozen map, and the AIP-14 check." · Gate summary.
- S0014-55 · DECISION-OR-STANDING · — · L111 · "Phase C does not begin until the DA says so." · The DA's authorising act is required.

## (b) Five most important ideas

1. **C-2 (L53, D-2 L68).** Authorizing acts (PA commissions) happened without being recorded ("minted") in the register, so the register is not the complete record of authorizations. This is the clearest case in the set of acts versus records, with a proposed rule: "commissions that authorize work must mint".
2. **Category A's validity standard (L22, A-12).** Validity comes from double, mutually blind derivation; HIGH confidence is "the strongest … without a second product". It defines an independence-based standard of validation.
3. **C-6/D-6 (L57, L72).** Separation of duties is real as process but unevidenced as people; role separation is session-simulated. This exposes a limit on independence and authority, because reviewer and author roles may be the same agent.
4. **The A/B/C/D split and the gate (L9, L16, L78-91).** Findings, corrections, defects and pending decisions are kept as distinct kinds. Nothing becomes architecture without passing a human-gated classification, and decisions name their authority.
5. **C-1 model amnesia (L52).** The failure KnowledgeOS is meant to prevent recurs within the programme itself, 11 times. This is direct evidence of what the system must do.

## (c) Ambiguities and inconsistencies

- **"Exactly three doors" (L105)** names E-1 (= D-1), the frozen map (D-3) and AIP-14 (D-9). But the gate table also blocks the knowledge model on D-8 (L85, L102 "D-1+D-8"), and capabilities on D-2 (⚠️). So D-8 is a fourth door.
- **"No unresolved evidence flows through" (L91)** conflicts with A-8, a Category A ("validated") item that is itself "unreconciled" and "blocks" (L33).
- **"Each derived twice by mutually blind paths" (L22)** is questionable. One path (the track) produced conclusions before seeing the reviewer, but the comparison was done by the track's author with full knowledge, so the blindness was one-directional at comparison time [I]. A-12 cites only "Phase B §3", not two blind paths.
- **"Exactly one category" rule versus content.** A-12 is a process finding (it "changes process: yes"), yet it sits in Category A. Several A items "feed" C/D items. The rule allows this only via splitting, which is not shown for A-12. [I]
- **Owner versus confidence.** C-6's confidence is "HIGH (inferred from owner fields)": high confidence on an inference. C-1's owner is "me, operationally". "me" is never identified.
- **D-7's authority** is just "Authority", with no named body.
- **Kind label.** "Not a review" (L5), yet the document classifies a review's findings and attributes them. The "Phase B.5" title versus the "Phase_B" filename is cosmetic.
- **Scope of B-1's numbers.** "≈3 informal" and "≈2 formal" include R-39 "as recorded exception". Whether that is 1 or 2 formal promotions is unclear.
- **Undefined here:** E-1, EKP, EAD-1, IR-*, MQ-1, G-1, PG-8, OQ-K5, EM-001, R-37, N-10, "INCONCLUSIVE", "AIP-14 exposure".

---

# S0015 — KnowledgeOS Independent First-Principles Review, Phase B (137 lines)

## (a) Status, authority and dates

- **Kind:** "INDEPENDENT REVIEW RECORD + COMPARISON" (L5). The document records a blind review done by a different, isolated session and compares it with "this track". It is not the review itself.
- **Status:** "CANDIDATE — NOT ADOPTED" (L6).
- **Authority:** "Generated — never authoritative without human review" (L7).
- **Disposition:** "Submitted to the Decision Authority. Nothing in this document executes." (L136).
- **Dates of the file itself:**
  - Commission "Phase B of the PA roadmap, 2026-08-02/03" (L8).
  - Traceability: "Phase B commission (PA roadmap, 2026-08-02) · executed 2026-08-02/03" (L134). So the commission was 2026-08-02 and execution ran 2026-08-02/03; the header merges them.
- **Quoted date:** a filename, `2026-08-02-mvk-bootstrap-validation-report.md` (L73).
- **The Phase A freeze is recorded here "per the PA directive" (L93),** with no date.

## Items

- S0015-01 · DEFINITION · — · L5 · "INDEPENDENT REVIEW RECORD + COMPARISON. No document of the frozen corpus was modified. No redesign. No ADR." · Kind.
- S0015-02 · DEFINITION (status/authority) · — · L6-7 · "CANDIDATE — NOT ADOPTED … Generated — never authoritative without human review" · Self-status.
- S0015-03 · DATE · — · L8 · "Phase B of the PA roadmap, 2026-08-02/03 — 'Ignore our conclusions. Rediscover the architecture from the evidence.'" · Commission and its instruction.
- S0015-04 · DEFINITION (method) · — · L9 · "an ISOLATED session (no conversation context) performed a first-principles Strategic DDD review with the conclusion corpus BLOCKED: docs/knowledgeos/** · docs/pks/** · .claude/CONTEXT.md / MEMORY.md / sessions/** · architecture_legacy/ai_architecture/**" · Independence method.
- S0015-05 · CLAIM · — · L10 · "I could not be the reviewer — I authored every conclusion under test." · Reason for isolation ("Same contamination logic as the MVK bootstrap experiment").
- S0015-06 · CLAIM (reviewer verdict) · — · L11 · "CONTINUE DISCOVERY — split finding: the internal Engineering Platform alone would earn 'Adopt with Reservations'; KnowledgeOS as a reusable product remains 'a well-governed hypothesis with a demonstrated internal ancestor'" · The blind reviewer's verdict.
- S0015-07 · CORRECTION-OR-RETRACTION (integrity flag) · — · L14 · "The reviewer's independence note quotes the commission prompt as asserting 'PublicDigit is only the first laboratory.' The prompt contained no such sentence." · The reviewer misattributed a quote.
- S0015-08 · CLAIM · — · L14 · "it tested the framing against ADR-AIP-02 and rejected it — so the conclusion is unaffected; the misattribution is recorded." · The flag is judged immaterial.
- S0015-09 · CLAIM · — · L20 · "These now carry double, independent derivation — the strongest confidence this programme can produce without a second product." · Status of the convergences.
- S0015-10 · CLAIM · — · L24 · "KnowledgeOS is a hypothesis; the gate is shut; the Vision is charter-owned and unapproved | 'a well-governed hypothesis… every artifact that names KnowledgeOS marks itself PROPOSED, INERT, 'potential product,' or 'placeholder''" · Convergence 1.
- S0015-11 · CLAIM · — · L25 · "'PublicDigit is the laboratory' is not canonical — canon says Product Primacy | independently flagged and rejected against ADR-AIP-02" · Convergence 2.
- S0015-12 · CLAIM · — · L26 · "PD-3 / the EKP is a real governed context with a NAMED INDIVIDUAL owner | 'Strong existence, contested identity' — context #5, owner: nab.raj.sharma observed independently" · Convergence 3.
- S0015-13 · CLAIM · — · L27 · "Engineering Governance is the strongest bounded context | context #1, Strong, 'boundary exercised constantly'" · Convergence 4.
- S0015-14 · CLAIM · — · L28 · "near-identical list, independently assembled — 'the genuine kernel; surprisingly small and clean'" · Convergence 5 (the kernel list).
- S0015-15 · CLAIM · — · L29 · "'designed-for-reuse ≠ demonstrated-reusable — exactly one binding exists' — verbatim convergence" · Convergence 6.
- S0015-16 · CLAIM · "sharpened" · L30 · "SA-2: individual ownership is a succession risk | 'separation of duties is real as PROCESS and unevidenced as PEOPLE' — … role separation is session-simulated" · Convergence 7, sharpened.
- S0015-17 · CLAIM · — · L31 · "Three knowledge ontologies coexist unreconciled … | contradiction #5 — 'one context, three competing models'" · Convergence 8.
- S0015-18 · CLAIM · — · L32 · "'93 PKS_* files pool in the folder its rules exist to unmix'; plus new instance: Platform_Capability_Pattern passes ES-005.3's litmus and sits product-side" · Convergence 9 (placement breached).
- S0015-19 · CLAIM · "sharpened into a falsification" · L33 · "'direct operational evidence AGAINST the Reference Architecture's stance that protocol + human authority suffice without resident mechanisms — collected, to its credit, by the platform itself'" · Convergence 10 (PMR-10 failure; "the third escape occurred").
- S0015-20 · CLAIM / CORRECTION · "downgrade" · L34 · "'governance precedes automation' is deliberately unnumbered — 'an invariant-in-waiting the corpus is honest enough not to promote.' Our I-8 over-granted it" · Convergence 11: invariants reproduced, with one downgrade.
- S0015-21 · CLAIM · — · L35 · "The split verdict itself — enacted internal mission vs unratified product vision | 'Adopt with Reservations' internally · 'CONTINUE DISCOVERY' for KnowledgeOS" · Convergence 12.
- S0015-22 · CORRECTION-OR-RETRACTION · FALSIFIED · L39 · "'0 traversals' is FALSIFIED as we stated it" · Divergence D-1.
- S0015-23 · CLAIM · — · L43 · "'Evidence → improves KnowledgeOS: EMPTY — 0 traversals; the arrow has never been traversed' — asserted in at least six documents" · The track's prior claim.
- S0015-24 · CLAIM · — · L44 · "'…improves the platform from evidence: DEMONSTRATED (n≈3)' — R-36 (six behaviours promoted from PB-004/5/6 evidence) · R-41/ES-004.3 (from the WP-1 incident) · R-63 (scope declared from operational validation)" · The blind finding.
- S0015-25 · CORRECTION-OR-RETRACTION · — · L45 · "EAD-1's original claim was narrow — no evidence has flowed from PublicDigit to PKS. We generalized it to the whole return arrow, and the generalization is wrong." · Reconciliation: the formal ladder has about 2 promotions; the informal back-edge at least 3.
- S0015-26 · CLAIM · — · L46 · "'The flywheel has never turned' must be retired; 'the flywheel turns rarely and informally' is what the evidence supports" · Consequence.
- S0015-27 · CLAIM · — · L50 · "a FROZEN SIX-CONTEXT MAP (BC-1..BC-6) that our entire D-1..D-7 / PD-1..PD-6 exercise NEVER CONSULTED" · D-2: occurrence #11 of proposing-before-searching.
- S0015-28 · CLAIM · — · L52 · "We ran two full rounds of bounded-context discovery, a falsification, and a strategic architecture — without checking the platform's own frozen strategic model." · Self-indictment; the reviewer found the map "immediately".
- S0015-29 · CLAIM · — · L54 · "substantial convergence (their #1/#2/#3/#4 ≈ BC-6/BC-3/BC-4/BC-1)" · The reviewer's contexts match frozen BCs.
- S0015-30 · CLAIM · — · L54 · "the operationally hottest boundary today (R-63/R-64 work-package vs meta-governance) is absent from the frozen map, and BC-5 Adversarial Review shows no current operational signature. The frozen model lags reality; the amendment path (AIP-13) exists and is unused." · Drift findings.
- S0015-31 · CLAIM · — · L58 · "The catalog says 'unauthorized'; the README says 'REALIZED'; the register (R-43..R-72) records neither an authorization nor an acceptance." · D-3: CAP-001's authorization is missing from the register.
- S0015-32 · CLAIM · — · L58 · "'The register is provably not the complete record of authorizations — a material weakness for a system whose thesis is traceability.'" · Reviewer quote.
- S0015-33 · CLAIM · "self-indicting" · L58 · "CAP-001 was built in this session under PA commissions that were never minted as rulings." · The author admits the gap.
- S0015-34 · CLAIM · — · L62 · "incumbent project-knowledge governance — disposition PENDING ARB (metadata model aligned; consumption model FALSIFIED by E-1)." · D-4: ES-006's register entry for the EKP.
- S0015-35 · CLAIM · — · L62 · "The one knowledge platform that is executable is the one whose consumption model the platform's own evidence has falsified … Our track examined the EKP's schemas in detail and never found E-1." · D-4 significance and miss.
- S0015-36 · CLAIM · "sharpened" · L66 · "'the platform governs by a proposed constitution… exactly the class of over-claim it polices elsewhere.'" · D-5: an evidence gap restated as a self-consistency breach ("6 ES standards PROPOSED").
- S0015-37 · CLAIM · — · L72 · "THAT EXPERIMENT WAS RUN — the MVK bootstrap ('StockRoom'), verdict FAIL." · The reviewer's gap #1 recommendation was already executed.
- S0015-38 · CLAIM · — · L72 · "the kernel as filed does NOT bootstrap — no genesis path, EEP roles unsatisfiable solo, SD-1 product-coupled" · MVK result details.
- S0015-39 · CLAIM · — · L73 · "it is corroborated — the full record is 2026-08-02-mvk-bootstrap-validation-report.md, blocked from their view" · Answer to the reviewer's "uncorroborated, unweighted" note on OE-6.
- S0015-40 · CLAIM · — · L74 · "Gap #2: no canonical register list (G-1) | carried in our track since CAP-001's baseline — convergent" · Gap #2.
- S0015-41 · DECISION-OR-STANDING · — · L75 · "Gap #5: acknowledge session-simulated role separation | new — adopted into the open questions below" · Gap #5.
- S0015-42 · CLAIM · "strongest" · L77 · "the reviewer's proposed test and our executed test are the same test. Their prediction of what it would measure matches what it measured." · Method cross-validation claim.
- S0015-43 · CLAIM · — · L83 · "Reproduced blind | 12 | §1" · Scoreboard.
- S0015-44 · CLAIM · — · L84 · "Sharpened | 4 | role separation · PMR-10-as-falsification · unratified-constitution · placement breach" · Scoreboard.
- S0015-45 · CLAIM · — · L85 · "Corrected | 3 | '0 traversals' (D-1) · the unconsulted frozen map (D-2) · I-8's over-grant" · Scoreboard.
- S0015-46 · CLAIM · — · L86 · "New, missed by us | 2 | E-1 / EKP disposition PENDING · CAP-001's register gap" · Scoreboard.
- S0015-47 · CLAIM · — · L87 · "Unaffected | — | the reviewer's misattributed prompt quote (flagged, immaterial)" · Scoreboard.
- S0015-48 · CLAIM · — · L89 · "the architecture substantially SURVIVES independent rediscovery — 12 conclusions reproduced blind — and the track is corrected in three places, two of them our own repeated claims." · Phase B outcome.
- S0015-49 · DECISION-OR-STANDING · — · L93 · "the discovery corpus is FROZEN as historical engineering evidence — the docs/knowledgeos/* discovery/validation documents and the docs/pks/* Phase III records. No rewriting, no consolidation, no polish; factual corrections only." · The Phase A freeze, per the PA directive.
- S0015-50 · CLAIM · — · L93 · "The reasoning paths — including every withdrawn claim — are the evidence." · Rationale for the freeze.
- S0015-51 · DECISION-OR-STANDING · — · L95 · "the '0 traversals' claim, where it appears generalized, is now superseded by this record. Per the freeze, the originals are not rewritten; this document is the correction of record." · Correction by a separate record, not by edit.
- S0015-52 · DECISION-OR-STANDING (directive to Phase C) · — · L101 · "the 12 doubly-derived conclusions as its base — the only claims with independent replication" · Phase C base.
- S0015-53 · DECISION-OR-STANDING · — · L102 · "'the back-edge runs rarely and informally' — never again 'never'" · Phase C must use this wording.
- S0015-54 · DECISION-OR-STANDING · — · L103 · "reconcile with the frozen Phase-02 six-context map via AIP-13 — including the R-63/R-64 boundary the map lacks" · Phase C obligation.
- S0015-55 · DECISION-OR-STANDING · — · L104 · "a register-completeness rule — authorizations exist only if minted" · Proposed rule.
- S0015-56 · DECISION-OR-STANDING · — · L105 · "dispose E-1 / the EKP consumption model before any knowledge-platform claim" · Precondition.
- S0015-57 · DECISION-OR-STANDING · — · L106 · "state, in governance, that role separation is session-simulated — with its limits" · Obligation.
- S0015-58 · OPEN-QUESTION · — · L112 · "Mint CAP-001's authorization and acceptance into the register — or record why the register is not the complete authorization record" · IR-1; DA.
- S0015-59 · OPEN-QUESTION · — · L113 · "Dispose E-1: the EKP consumption model is recorded as falsified, disposition PENDING" · IR-2; ARB.
- S0015-60 · OPEN-QUESTION · — · L114 · "Amend the frozen Phase-02 map (AIP-13 path) for the R-63/R-64 boundary and BC-5's dormancy — or record why not" · IR-3; ARB.
- S0015-61 · OPEN-QUESTION · — · L115 · "Acknowledge session-simulated separation of duties in governance text" · IR-4; DA.
- S0015-62 · OPEN-QUESTION · — · L116 · "Retire the generalized '0 traversals' claim; adopt the n≈3 record" · IR-5; DA (factual correction).
- S0015-63 · OPEN-QUESTION (carried) · — · L117 · "ratification · G-1 · three-ontology reconciliation · MQ-1 mission ratification" · ARB / sponsor.
- S0015-64 · CLAIM · — · L125 · "Twelve of this track's conclusions were reproduced by a reviewer who could not see them — including the kernel list, the EKP's individual ownership, and the Product-Primacy correction." · Headline.
- S0015-65 · CLAIM · — · L126 · "The blind reviewer consulted the frozen six-context map our whole domain-model track never opened — occurrence #11 — and falsified our most-repeated number ('0 traversals')." · "The humbling part".
- S0015-66 · CLAIM · "strongest single result" · L127 · "The reviewer independently prescribed the MVK bootstrap experiment as the missing evidence — not knowing it had been run. Their predicted measurement and our actual measurement agree." · Closing.
- S0015-67 · CLAIM · — · L128 · "CONTINUE DISCOVERY is the same disposition this track reached: an enacted internal platform, an unratified product hypothesis, and a charter that already encodes the correct next act" · Verdict alignment.
- S0015-68 · CLAIM · — · L130 · "An architecture that survives blind rediscovery in twelve places and is corrected in three is worth more than one that was never tested. Phase C can now build on the twelve — and must carry the three." · Closing thesis.
- S0015-69 · DECISION-OR-STANDING · — · L136 · "Nothing in this document executes." · No executive force.

## (b) Five most important ideas

1. **Independence by isolation (L9-10).** The author cannot review their own conclusions, so an isolated session with the conclusion corpus blocked does the review. This is the document's operational definition of independence, and it underlies all "double derivation" confidence claims.
2. **The register gap (D-3, L58, L104).** Authorizations exist only if minted. Commissions acted as authorizations but left no ruling, so the record of acts is incomplete. The proposed rule makes the record, not the act, the condition of recognition.
3. **Correction of record under a freeze (L93-95).** Originals stay frozen; a later record supersedes their claims, and "the reasoning paths — including every withdrawn claim — are the evidence". Validity is carried by an append-only correction layer.
4. **"Governs by a proposed constitution" (D-5, L66).** Unratified instruments act as operative governance. This is a validity/standing contradiction: operating force without a ratifying act.
5. **Falsification of the generalized "0 traversals" (D-1, L39-46).** Over-generalising a narrow claim is a recurring validity failure. The back-edge runs rarely and informally (n≈3), which bears on whether evidence actually changes the platform.

## (c) Ambiguities and inconsistencies

- **Double counting in the scoreboard [I].** "Reproduced blind: 12" includes rows 7, 9 and 10, which are also counted as "Sharpened" (L84). It also includes row 11, which carries the I-8 downgrade that is counted under "Corrected" (L85). The categories overlap, so "12 reproduced" overstates clean reproduction.
- **"Corrected 3" mixes kinds.** It lists two conclusion corrections and one process finding (the unconsulted map). L89 says "two of them our own repeated claims", but which two is unclear.
- **"Predicted measurement agrees" (L77, L127)** is not supported by the quoted text. The reviewer's gap #1 says only that a cheap trial "would convert designed-for-reuse into evidence" (L72). No predicted outcome (such as FAIL) is quoted. [I]
- **Where independence was limited.** The block list (L9) covers docs/knowledgeos, docs/pks, .claude context and architecture_legacy/ai_architecture, but not engineering/ (where the frozen Phase-02 map lives) or docs/knowledge. The reviewer could therefore see canon and some track outputs placed elsewhere. Whether the reviewer was the same model or agent class is not stated; "isolated session (no conversation context)" is the only independence guarantee.
- **Dates:** the header says commission "2026-08-02/03"; the traceability says commission 2026-08-02, executed 2026-08-02/03.
- **"Charter-owned" (L24)** may or may not refer to the same owner as "sponsor-owned" elsewhere [I]. Not resolvable inside S0015.
- **Owner identity (L26):** "owner: nab.raj.sharma" is quoted. Whether this named individual holds the role is the subject of convergence 3; the field is a record, not evidence of acts.
- **"The third escape occurred" (L33)** is undefined. So are SA-2, OE-6, EAD-1, E-1, BC-1..BC-6, AIP-13, R-numbers, and "laboratory" as a charter term.
- **D-5 placement:** it is listed under "Divergences — where the blind review CORRECTS this track" but scored as "Sharpened", not "Corrected".
- **"Same disposition this track reached" (L128)** asserts the track's prior verdict without citing it.
- **Self-referential role:** the document reports the blind reviewer's findings at second hand. The blind report itself is not quoted in full, and its location is not given in this document.

---

## Cross-file note (marked [I], kept outside the per-file meanings)

- The same identifiers carry different meanings across files: C-1..C-12 in S0013 vs C-1..C-8 in S0014; D-1..D-10 in S0014 vs D-1..D-5 in S0015 vs D-1..D-7 (domains) in S0011; H-3 in S0012 vs S0013. Each must be read file-local.
- The nesting question is "resolved / closed" in S0012 (L96, L158) but "HYPOTHESIS … unevidenced" in S0013 (H-7, L75).
- The authority/LG-1 framing in S0012's body is superseded by the withdrawal row inside the same file and by S0013's K-7/R-10.
