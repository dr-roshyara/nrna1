*Whole-book review S0100, part P1, reviewer B (sonnet). Step 1, blind inventory, saved by the Professor from the hand-back, unedited, 2026-10-19T19:53:34Z.*

# P1 (Ch. 0 and Ch. 1) — Step 1: blind inventory

The book (`R/BOOK-UNDER-REVIEW/`) has not been opened. Of the brief I read only the "allowed references" table (lines 54-83). Anchors are `Lnn` in each source file. Items marked (INT) are my interpretation; the rest is what the source says.

Note on method: all five sources are self-declared "CANDIDATE — NOT ADOPTED / Generated — never authoritative without human review". Their OBSERVED/INFERRED grades are the author's own and I have not verified them against the underlying repository. In this step I take them as claims made by the source.

---

## S0032 — Vision / Mission Clarification (`KnowledgeOS_Vision_Mission_Clarification.md`, 236 lines)

**Main claims**
- **Mission is enacted, not vacant (L13-29, L63-91, L222).** The earlier claim "the Mission layer is vacant" is corrected to "implicit but evidenced, not yet formalized". Later wording: "mission is ENACTED". The author names the error pattern "ARTIFACT ABSENCE ≠ CONCEPT ABSENCE" and says it made this error three times (L13-23).
- **Eight artifacts as mission evidence (L73-86).** Examples: ES-005.3's "could a different project adopt it unchanged?" test, the ES-006.1 promotion ladder, the Platform ≙ Adoption split pre-positioned for a second adopter, and ES-005.4 never-a-copy. The implicit mission is stated as "reusable engineering platform … through governed engineering knowledge" (L88-91). Note that it does not mention PublicDigit.
- **AIP-14 is a constraint, not a mission (L66-71, L31-35).** It carries a per-iteration metric and an over-evolution trigger. The author says "a mission does not ship with a per-iteration metric… A CONTROL does."
- **Intent is not evidence (L25-27).** `INTENDED` as a fourth evidence grade is withdrawn. The reason given is the repository's rule on new dimensions versus overloaded dimensions versus new values. Intent is instead derived as f(governance-status, owner).
- **Vision is a hypothesis with a falsification clause (L41-59).** The charter says "to be validated or falsified — not assumed". "Zero market data points."
- **PublicDigit's role is "Product Primacy", not "laboratory" (L110-132).** "Laboratory" was NOT FOUND in canon and is graded HYPOTHESIZED. PublicDigit is the Core Domain and the first adopter, and the product binds the platform.
- **Five layers, each with an owner (L134-150).** Vision and Mission are the sponsor's and are unratified. Governance, Engineering and Product have observed owners.
- **Layer hierarchy (L152-167).** Vision → Mission → Governance → Capabilities → PKS → Product is "better supported". Every layer is populated. The conceptual order is not the chronological order, because the platform was built bottom-up.
- **The apparent conflict (L169-181).** The "two competing missions" conflict dissolves. AIP-14's enforcement and the charter's shut gates survive.
- **Recursive property (L202-213).** Four artifacts apply governance to the method itself.
- **Three corrections back to the review (L192-198).** (1) "KnowledgeOS is the product" contradicts AIP-14. (2) "9 rediscoveries, all from unindexed regions" is refuted at n=10. (3) The "0/11 enforcing" finding is restated rather than dropped.

**Distinctions**
- Absence of an artifact versus absence of a concept.
- Evidence status versus intent.
- Vision, mission and constraint as different kinds of statement.
- Discovery (the author's act) versus ratification (the sponsor's act).
- Conceptual order versus chronological order.

**Left open**
- Mission ratification (SA-1 / MQ-1, L144-150).
- Whether the recursion is an asset or an over-evolution risk (L213), deferred to AIP-14.
- The vision is unvalidated: zero market data points.

**What a rigorous theory must take, reject or hold open**
- Take: the distinction between a missing artifact and a missing concept. Take: the separation of evidence, intent and authority.
- Hold open (INT): the "enacted mission" inference. Eight artifacts "meaningless unless reuse is a purpose" (L75) is an abductive argument. Rival readings include good engineering hygiene, or artifacts written in anticipation without commitment. "Enacted" is stronger than the evidence shows, and the document itself says "not adopted".
- Hold open (INT): the "mission versus constraint" line is drawn by the author's judgment ("a mission doesn't ship with a metric", L70). That is an assertion, not a test.
- Take with care: some grading is by presence of text in canon, so the OBSERVED grade attaches to a document existing, not to the platform behaving that way.
- Internal inconsistency to flag (INT): the Traceability footer (L233) still says "new finding: the MISSION LAYER IS VACANT in canon", which contradicts the REV 2 body. The footer was not updated. L190 also still says "vacant Mission layer".

---

## S0018 — Engineering Knowledge Progression Model (`KnowledgeOS_Engineering_Progression_Model.md`, 158 lines)

**Main claims**
- **Ten progression mechanisms inventoried (L21-36).** P-1 document status, P-2 authority, P-3 promotion ladder, P-4 methodology maturity, P-5 ADR-M status, P-6 capability lifecycle, P-7 EEP, P-8 observation protocol, P-9 identifier lifecycles, P-10 evidence count n.
- **Four kinds of change plus one non-progression (L55-68).** Governance-act, evidential, work-execution, composite, and provenance (which does not progress).
- **Central principle (L67, L105, L146):** "A GOVERNANCE-ACT progression can be granted. An EVIDENTIAL progression can only be earned." Also: "evidence EARNS; governance GRANTS; promotion requires BOTH."
- **Flagship finding (L70-96): `authority` is two dimensions in one field.** `generated` and `derived` are provenance, which is immutable. `authoritative`, `provisional` and `historical` are standing, which changes by governance acts. The source argues this dissolves "LG-1" (should authority be a state machine?) as ill-posed. Provenance cannot have a state machine, and standing changes by acts.
- **The reviewer's chain is qualification (L17).** The DERIVED → REVIEWED → ATTESTED → RELEASED chain is "qualification, not authority", and "Qualification" already has a recorded UL collision.
- **No unified lifecycle (L110-123).** A single lifecycle would force either decreed evidence or decisions waiting on evidence. The repository's two declared orthogonalities and GEP-F1 are "three prior refusals".
- **Interactions (L98-108).** Status is coupled to authority by a guard, not by fusion. P-1 and P-5 are the same kind. P-3 and P-4 are different kinds.

**Distinctions**
- Provenance versus standing.
- Granted versus earned.
- Artifact lifecycle versus work lifecycle (GEP-F1).
- Lifecycle versus measurement scale versus accumulation.
- Qualification as trust-increase versus authority.

**Left open (L125-134)**
- PM-1: separate authority's two questions?
- PM-2: role/guard treatment for standing changes.
- PM-3: P-1 and P-5 shared vocabulary.
- PM-4: ruling on the word "Qualification".
- PM-5: model P-6 explicitly as a composite.
- LG-5: admit `SPECIALIZES`.

**What a rigorous theory must take, reject or hold open**
- Take (INT): the earned-versus-granted asymmetry. It is the most theory-like claim in this set and is testable, e.g. whether any case violates it.
- Take: provenance as immutable fact versus standing as mutable judgment. Check against S0022 (L93), which says the same thing for I-4, so the sources partly reinforce each other. I would not count them as independent confirmation.
- Hold open (INT): "ten mechanisms, four kinds" is a classification chosen by the author. Whether the four kinds are exhaustive and exclusive is not tested. P-3 and P-6 are put in "composite", which looks like a residual bin.
- Hold open (INT): "Provenance is immutable" is true of origin as a historical fact. But `derived` in a YAML field can be misassigned or revised, so the field is a record of provenance and can be wrong or edited. The source conflates the fact with the recorded value. The book must keep these apart.
- Hold open (INT): is "evidence cannot be decreed" a descriptive claim or a normative rule? It is stated as both.
- "LG-1 dissolved" depends on the claim that authority-changes are acts logged as rulings, not workflows. That is an observation about current practice and does not by itself show that no state machine is needed.

---

## S0022 — Ontology Cross-Product Validation (`KnowledgeOS_Ontology_Cross_Product_Validation.md`, 200 lines)

**Main claims**
- **Method (L5-10, L23-33).** The ontology is held fixed and projected into three conceptual PKSs: PublicDigit (actual), Hospital Information System (hypothetical) and ERP (hypothetical). The success criterion is whether it survives unchanged.
- **Results (L37-52, L154-164).** 8 concepts invariant, 2 specialize, 1 disappears (Runtime Adapter), 1 relationship fails. 10 of 11 invariants survive. Design Policy projected best (L42, L187).
- **Pre-validation corrections (L12-19).** I-11 is restated as "Knowledge does not EXECUTE; capabilities execute; knowledge constrains and informs generation". The restated form "survives" (L67).
- **F-1 (L73-95): I-4 first falsified, then withdrawn as a lifecycle gap.** The original observation is "a projection can be authoritative" (discharge summary, financial statement). The body says falsification WITHDRAWN: I-4 is "underspecified, not false". Derivation and attestation are independent. Provenance and standing are independent (L93).
- **F-2 (L97-106).** "One PKS per product" fails for configurable products: product versus deployment is inexpressible. PublicDigit masks this through `organisation_id` multi-tenancy.
- **F-3 (L108-117).** There is no place for external authority such as regulators or accounting standards. "The ontology can express who decides. It cannot express who must be obeyed."
- **Specializations S-1..S-4 (L119-128)** and **missing relationships M-1..M-4 (L130-142).** The four relationships are specializes, constrains, instantiates and attests. They are described as "transformational" where existing relationships are "structural". All are recorded, none admitted.
- **Methodological claim (L166-167, L191-193).** PublicDigit masked two of three failures, so cross-product projection is "justified by evidence".

**Distinctions**
- Structural versus transformational relationships.
- Product versus deployment.
- Internal versus external principle.
- Inert versus attested projection.
- Derivation versus attestation.
- Knowledge executes versus constrains.

**Left open (L169-177)**
- CV-1: scope or withdraw I-4.
- CV-2: product versus deployment.
- CV-3: external authority.
- CV-4: admit transformational relationships as a group.
- CV-5: status of the Relationship Ontology.

**What a rigorous theory must take, reject or hold open**
- Take: the method of testing a model by projection into other domains.
- Reject or discount (INT): the projections into Hospital and ERP are the author's constructed examples ("conceptual only", L26). "Held emphatically" (L64) and "validated by projection" (L67) are the author's judgments on invented scenarios. This is weak validation, a sympathetic reading by one reader, and no independent domain check. "Falsification" is too strong a word for it, and the body itself withdrew the I-4 falsification.
- Internal inconsistency (INT): the status table (L69), verdict (L158-159) and Traceability footer (L197) say I-4 is falsified, while L73-79 says the falsification is withdrawn. The counts "10 of 11" and "8 of 12" are therefore not reconcilable from the text alone.
- Take as open: M-1..M-4 and the external-authority gap are genuine gaps IF the three projections are representative. That has not been shown.
- Hold open (INT): "the four missing relationships share one shape, so they are likely a real omission" (L142). This is an argument from pattern across four items, all produced by one author, from three self-chosen examples. It is a hypothesis.

---

## S0008 — Semantic Architecture Reconciliation (`KnowledgeOS_Semantic_Architecture_Reconciliation.md`, 111 lines)

**Main claims**
- **Premise (L12).** The main risk is semantic drift, not architectural drift. "Rediscovery happens because meaning is lost, not files."
- **Glossary (L16-39).** 14 examined concepts plus 3 forced in. Each has one meaning, owner, invariant and place. Dispositions:
  - Kept: KnowledgeOS, PKS, Engineering Knowledge, Engineering Capability, Engineering Method, Engineering Governance, Runtime Adapter, Documentation Ontology, Knowledge Graph, Knowledge Space, Design Policy and Execution Asset (AST).
  - Rejected or merged: Engineering Runtime into Runtime Adapter, and Engineering Ontology into Documentation Ontology.
  - Split: Engineering Evidence into Evidence Protocol (platform) and Evidence Records (product).
  - Candidates: Platform Ontology (L-B), Product Ontology (L-C) and Engineering Kernel.
- **Two-layer ontology (L32-34).** L-A is the executable ontology of artifacts (31 types, 8 statuses, 5 authorities, 11 edges). L-B and L-C are candidates or partial.
- **R-1 resolved (L41-55).** The "missing AI-execution layer" already exists as the registry's `AST-nnn` asset model, with AST-011 a registered prompt template. The residue is one missing link (SC-6). "Occurrence #12 avoided by checking."
- **Reviewer's chain tested (L57-77).** "KnowledgeOS creates PKS" is a hypothesis at n=0. "PKS contains Engineering Knowledge" is WRONG because it violates I-7. "Capabilities executed through Runtime" is imprecise: 41 enforced controls are runtime-side while every capability is advisory. A corrected canonical chain is given.
- **Engineering Kernel (L36).** "Element-portability proven; set-sufficiency FALSIFIED at n=1 (MVK)."
- **Drift ledger SC-1..SC-8 (L88-99).** Examples: the name "KnowledgeOS" is a placeholder; the EKP owner is one individual; terms carry several senses ("Baseline" ×5, "Constitution" ×5); "Qualification" has 3 senses; authority is provenance × standing; naming drift.
- **PKS (L23).** "I-7: never contains reusable method (breached)."

**Distinctions**
- Platform versus Product versus Runtime placement.
- Artifact versus knowledge (organizes carriers, not the knowledge).
- Protocol versus records.
- Capability versus its script.
- Knowledge Graph as a projection, not a peer concept.

**Left open**
- SC-1..SC-8, plus Platform Ontology, Product Ontology and Kernel as candidates.
- The author states the success criterion is met for 12 concepts.

**What a rigorous theory must take, reject or hold open**
- Take: the discipline of one term, one meaning. Take: the semantic-drift ledger, including the multiple senses of "baseline", "constitution" and "qualification". A book would have to define its own terms and flag collisions.
- Take: the I-7 breach. By the source's own table, the PKS rule is already breached in the actual PKS.
- Hold open (INT): the "success criterion met" claim is self-assessed ("one meaning · one owner" is declared by the same author who writes the glossary). The glossary is not independently tested.
- Hold open: PKS is "a context type; instances product-side". The PKS concept has n=1 authored instance (S0032 L160). Whether a general PKS concept is justified is not tested.
- Reject or hold open: the "Engineering Kernel" is a named set that is never governed and has failed sufficiency at n=1, so it should not be a settled concept.
- Hold open: "organizes carriers, not the knowledge" (L66) restates I-1. The knowledge versus artifact split is asserted as an invariant, not demonstrated beyond examples.

---

## Fifth source chosen: S0016 — `KnowledgeOS_Meta_Model.md` (178 lines)

**Reason.** It is the document that supplies the evidence/intent/governance/operational separation, and it states the knowledge-versus-artifact edge and the dimension rule (orthogonal, necessary, sufficient) that the other four lean on. I wanted the source behind the dimension vocabulary Chapters 0 and 1 are likely to use.

**Depth: skim.** The file is short, and I read it through once rather than line by line.

**Main claims**
- **Six dimensions (L58-71).** NATURE, REPRESENTATION, GOVERNANCE-STATUS, AUTHORITY-SCOPE, EVIDENCE-STATUS (new) and OPERATIONAL-STATE (new). INTENT-STATUS is rejected as decomposing into f(governance-status, owner). Each admitted dimension is tested for "orthogonal · necessary · sufficient" (L35-47).
- **Load-bearing edge (L86-98): ENGINEERING KNOWLEDGE ≠ ENGINEERING ARTIFACT.** "A meta-model earns its place by making a known error class impossible to phrase." Documents become the lowest layer.
- **Six artifacts classified (L121-135).** The test claims orthogonality because Round39-MC is frozen yet enforced, while CAP-001 is realized yet only advisory.
- **Annotation (L23-33).** Three corrections:
  - Level mixing: the six "concepts" are four ontological layers.
  - PKS is a container, with Knowledge Space admitted as the container concept.
  - The document conflates a meta-model (§1) with an ontology (§2). The chain is incomplete without DP-n.
- **Mission (L79).** "Enacted, unratified."
- **Derived-from lineage (L9).** It depends on RQ-002, which is "COMPLETE, awaiting ARB" and not adopted (L142, MM-1).

**Left open (L147-155).** MM-1 to MM-4 and MQ-1. This includes whether OPERATIONAL-STATE belongs to the artifact or to the rule it carries (MM-4).

**Interpretation (INT).**
- Take: the separation of evidence status from governance status. Evidence status is the author's "working vocabulary, NOT a field anywhere" (L45), so it is a proposal and not an observed repository fact.
- Hold open: orthogonality is "proved" from a sample of six artifacts (L125-135). Two dimensions varying in two rows shows they are not identical, not that all pairs are independent.
- Hold open: "makes the error unstatable" is a rhetorical test of the model, and does not show the model is true. The author also says the model rests on an unadopted RQ-002.
- The source's own annotation (L29, L31) concedes that levels are mixed, so the six-concepts list should not be read as a settled ontology.

---

## Cross-source observations (INT)

1. **Recurring author self-corrections.** All five sources contain withdrawn or corrected conclusions with the original wording left in places (S0032 L233 vs L13; S0022 L69/L158/L197 vs L73). A theory should take the corrected claim and treat the stale footers and tables as errors in the corpus, not as additional claims.
2. **Shared load-bearing assumptions.** Provenance versus standing (S0018, S0022), knowledge versus artifact (S0008, S0016) and the layer chain recur across sources. They are the work of the same author within about two days (2026-08-02/03), so agreement among them is not independent corroboration.
3. **Status.** Every source is CANDIDATE and generated. Anything the book presents as established from these has to cite them as proposals, not findings.
4. **What the corpus keeps outside the theory.** Nothing here establishes that governance of knowledge works. The documents are about the platform's own artifacts and a single product (PublicDigit). Claims of generality rest on three self-constructed projections (S0022) and n=1 authored PKS (S0032 L160).

---

## Files opened

1. `docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/WHOLE-BOOK-S0100/P1/BRIEF.md` — only lines 54-83, the "allowed references" table. A grep for the string "allowed references" located the line; only the table was read.
2. `docs/knowledgeos/KnowledgeOS_Vision_Mission_Clarification.md` (S0032), full
3. `docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md` (S0018), full
4. `docs/knowledgeos/KnowledgeOS_Ontology_Cross_Product_Validation.md` (S0022), full
5. `docs/knowledgeos/KnowledgeOS_Semantic_Architecture_Reconciliation.md` (S0008), full
6. `docs/knowledgeos/KnowledgeOS_Meta_Model.md` (S0016), full read, reported as a skim

## Searches run

- `grep -n "allowed references" …/P1/BRIEF.md` (single file, to locate the table line)

No other searches. No git commands. The book copy `R/BOOK-UNDER-REVIEW/` was not opened. I did not reach any file listed in item 3 of the reviewer note.
