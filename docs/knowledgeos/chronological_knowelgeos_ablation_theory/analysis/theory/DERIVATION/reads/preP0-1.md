# Batch read — preP0-1 (17 files)

Batch list: `docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/DERIVATION/batches/preP0-1.tsv`. All 17 files were read in full, in list order, with the Read tool. None was unreadable.

**General date warning.** 11 of the 17 listed dates conflict with dates stated inside the texts. The texts often date themselves **later** than the list. Details are in each section and in the synthesis. Line numbers are those the Read tool returned.

---

### S1823 · docs/knowledgeos/brainstorming/verification/step-280/exec/corpus.py · 2026-01-01 (internal-2026)

- **Date check:** No authorship date is stated. The code stamps real EKP observations `"2026-08-30"` (L26, L28). Synthetic cases use dates in 2026-01 to 2026-06 (L42, L47, L52, L57). ⚠ **Conflict:** the listed 2026-01-01 looks like a synthetic test date picked up as the file date. The 2026-08-30 stamp and the `step-280` path suggest it was written on or after 2026-08-30 [INT].
- **Read depth:** complete (L1–83).
- **What it is:** a Python test corpus (code). It builds about 36 classified cases for an executable KnowledgeOS model (`from kosmodel import *`, L4).
- **Key claims:**
  - "Empirical corpus: >=36 cases, stratified, >=20% negative/boundary." L1
  - "REAL cases are drawn from the running EKP; SYNTHETIC cases cover classes the EKP cannot express." L2
  - "CLASS 3 evidence-refuted (SYNTHETIC x3 — EKP has no refutation)" L40
  - Twelve case classes: ordinary, evidence-supported, evidence-refuted, unknown, contradictory, supersession, replay, policy-controlled, authority-controlled, policy-change, conditional, measurement (para) L33–82.
  - The measurement cases treat `mean` on an ordinal scale and `order` on a nominal scale as negative cases (L81–82). The model therefore rejects operations that are not admissible for the scale (para).
- **Concepts (as constructors, not defined in this file):**
  - `Dimension(name, values, scale)`, with scale "ordinal" (L21–23)
  - `Observation(source, date, content)` (L26)
  - `Evidence(obs, "supports"|"contradicts", "active", source, "direct", ...)` (L27, L43)
  - `Assertion(Prop(entity, dim, value), evidence-set, scope, Iv(from, to), source)` (L28)
  - `Prop` (L28) and the interval `Iv` (L28)
  - Strategies `WAIT/REQUEST/BLOCK` (L77)
  - None of these is given an adoption status.
- **Relations and structure:**
  - An assertion is a proposition plus an evidence set, a scope, a validity interval and a source (L28).
  - Supersession is modelled as old and new assertions with later valid-from dates (L56–57).
  - Replay is modelled as a history list `H` of `("assert", …)` events (L63).
- **Formal content:**
  - Typed records: Dimension with an ordered value tuple and a scale type; an Assertion with an interval of validity.
  - Gates appear as boolean maps such as `{"WellFormed":True,"Evidence":True,"TwoSource":True}` (L65–67).
  - The model module itself is not in this batch.
- **Corrections and contradictions:** none.
- **Open questions:**
  - The model's definitions live in `kosmodel` and are not visible here.
  - Class C04 "unknown" mixes an assertion with an empty evidence set, a never-asserted value, and an orphan (L47–49). The file does not say whether these are one state or three.

---

### S0011 · docs/knowledgeos/KnowledgeOS_Domain_Model_Falsification_Report.md · 2026-06-25 (internal-2026)

- **Date check:**
  - "Commission | … ARB Chair's Addendum, 2026-08-02" (L7)
  - "Validation/falsification commission 2026-08-02" (L167)
  - The date 2026-06-25 appears only inside a quotation of `Round39-D6`: *"These ADRs were authored together on 2026-06-25."* (L30)
  - ⚠ **Conflict:** the document was written on 2026-08-02. The listed date belongs to a cited source.
- **Read depth:** complete (L1–170).
- **What it is:** a falsification test of a candidate "Engineering Knowledge Domain Model" (domains D-1..D-7).
- **Key claims:**
  - "RESULT: THE MODEL DOES NOT SURVIVE INTACT." L12
  - D-2's separate ADR series is shared authority. The series was "authored together on 2026-06-25", so it shows an "independent **subject**, never independent **evolution over time**" (L29–30).
  - "an Anti-Corruption Layer that performs an identity mapping is not an ACL. It is a restatement." L44
  - "PKS is a projection — FALSIFIED for every existing PKS artifact" (L47). "You do not endorse a projection at 9.9/10. You regenerate it." (L58)
  - D-7 "Context Type" was inferred from n=1: "A type that has never been instantiated is a description, not a discovered construct" (L70).
  - "a shared authority that **forbids itself** from mixing two subjects is evidence FOR two models" (L84)
  - "falsified ≠ unevidenced … The first is worse." L133
  - "The model is not canonical." L162
- **Concepts:**
  - **Falsified vs unevidenced** (L133). The text treats this distinction as method.
  - Per-domain confidence on the R16 Strong/Medium/Weak scale (L120–131).
  - Domain statuses:
    - D-1 ACCEPT
    - D-2 REVISE
    - D-3 DEFER
    - D-6a DEFER, D-6b ACCEPT
    - D-5 accept demotion
    - D-4 accept rejection
    - D-7 REVISE (L137–145)
  - "PKS as projection": falsified for existing artifacts (L145).
- **Relations and structure:**
  - Domains D-1 Engineering Governance, D-2 Engineering Method, D-3 Engineering Runtime, D-4 Workflow (rejected), D-5 Capability (a knowledge kind), D-6a Evidence Protocol / D-6b Product Evidence, D-7 PKS context type (L122–131).
  - The abstract vocabulary *deny · request approval · allow* is compared with `.claude/settings.json` `deny/ask/allow` (L42).
- **Formal content:** none. There is an ordinal confidence scale but no mathematics.
- **Corrections and contradictions:**
  - The report corrects the Domain Model (`KnowledgeOS_Engineering_Knowledge_Domain_Model.md`, L8): "Delete 'own decision series' and 'independent evolution' as grounds" (L140).
  - It also sharpens the author's own Ontology Classification §4: "It is stronger than blocked: existing PKS artifacts are POSITIVELY AUTHORITATIVE" (L62).
- **Open questions:**
  - U-1 to U-6 (L113–118). These include:
    - whether Round39-MC was adopted because of D-2's independence or despite it
    - whether `deny/ask/allow` is universal across runtimes (n=1)
    - whether the D-6a/D-6b split holds over a real traversal (0 traversals)

---

### S0013 · docs/knowledgeos/KnowledgeOS_Architecture_Synthesis_Matrix.md · 2026-07-11 (internal-2026)

- **Date check:**
  - "Commission | Architecture Synthesis, 2026-08-03" (L8)
  - "REV 2 — review 2026-08-03" (L11)
  - The date 2026-07-11 appears only as the date of an ARB refinement that the text cites (L42).
  - ⚠ **Conflict:** the document was written on 2026-08-03.
- **Read depth:** complete (L1–125).
- **What it is:** a synthesis or register. It classifies 53 concerns into Canonical, Candidate, Hypothesis, Open and Rejected.
- **Key claims:**
  - "reconcile and classify EVERYTHING discovered; discover NOTHING." L5
  - "'Canonical' below means ADOPTED, FROZEN, RULED, or ENFORCED BY HUMANS — never 'validated by this programme.'" L22
  - Operating stance: "EXERCISE the architecture; only operational evidence reopens discovery" L18
  - The text downgrades "one owner ⇒ one context" to "shared ownership is evidence against separate contexts, insufficient alone". It also downgrades "the space map" from a finding to a HYPOTHESIS (L24).
  - H-5 "`generates → PKS` — the loop's head edge (the platform's distinctive bet)" is "n=0 inside AND outside" (L73).
  - "the falsification record is as long as the candidate record" L116
  - "every row has exactly one bucket, one citation, and … one gate. Nothing is in two states; nothing is stateless." L118
- **Concepts:**
  - Five buckets with definitions (L28, L45, L65, L79, L89).
  - K-1 "Dimensional-with-archetypes meta-model (ELEMENT = KIND × dimensions …)" — candidate (L49).
  - K-2 archetype set DOMAIN · PROCESS · CAPABILITY · KNOWLEDGE · ARTIFACT · RUNTIME ASSET · PROJECTION — candidate (L50).
  - K-3 "four partial orders — authority · production · realization · containment" — candidate (L51).
  - K-6 "Knowledge Space = declared boundary + owner + constitution (not a folder)" — candidate (L54).
  - K-7 "Authority = immutable PROVENANCE × act-changed STANDING" — candidate (L55).
  - K-10 decision-readiness as the platform's product — candidate (L58).
  - Hypotheses H-1..H-9 (L69–77). Rejected R-1..R-12 (L93–104).
- **Relations and structure:**
  - The five-bucket lifecycle register.
  - Deferred refinement A splits the matrix into five columns: `Concern · Asset · Knowledge State · Decision Authority · Evidence` (L16).
  - Deferred refinement B separates the knowledge lifecycle from the decision queue. A reconciliation note warns that this could create a second ladder competing with ES-006.1 (L17).
- **Formal content:**
  - "ELEMENT = KIND × dimensions" (L49) and "Authority = immutable PROVENANCE × act-changed STANDING" (L55) are product-form definitions.
  - The text does not formalise either of them.
- **Corrections and contradictions:**
  - R-1..R-12 list rejected claims with the passes that falsified them. Examples:
    - R-4 "single-stack hierarchy", via Ontology Discovery §4 (L96)
    - R-5 "KnowledgeOS is the Core Domain" (L97)
    - R-6 "MVK 15-file extraction boundary" (L98)
    - R-9 "'0 traversals' of the evidence back-edge (as a generalization)", falsified by "n≈3: R-36 · R-41 · R-63" (L101)
  - R-9 contradicts the "0 traversals" claims in S0028, S0031, S0039 and S0033 [INT].
- **Open questions:**
  - The D-8 ontology reconciliation and D-9a "define the metric" (L83).
  - RO-1..RO-4 (L84).
  - The two verdict sets that collide: ES-003.1 vs CAP-001 §5 (L87).

---

### S0017 · docs/knowledgeos/KnowledgeOS_Engineering_Decision_Model_Discovery.md · 2026-07-11 (internal-2026)

- **Date check:**
  - "Commission | Decision Model Discovery, 2026-08-03" (L8)
  - The date 2026-07-11 is when the cited `Engineering_Decision_Model.md` was ARB-accepted (L14).
  - ⚠ **Conflict:** the document was written on 2026-08-03.
- **Read depth:** complete (L1–130).
- **What it is:** a strategic discovery that turns into a reconciliation. It checks the decision catalog against the enacted decision record.
- **Key claims:**
  - "CHECK-BEFORE-DISCOVERING — THE DECISION MODEL ALREADY EXISTS, AND IT IS CANON" L12
  - The existing pattern is `Question → Decision → Authority → Procedure → Evidence`, with eight named decisions (L19–20).
  - "FINDING 1: the platform has a decision CATALOG for engineers and only a decision LOG for governance." L39
  - "FINDING 2: exactly TWO recurring decisions extend nothing in the existing set — FREEZE and RETIRE" L60
  - "the deletion litmus … is UNASKABLE unless knowledge and its file are different things." L68
  - "for authority decisions, the platform's product is DECISION-READINESS, not decisions." L92
  - DOMAIN "was NEVER CONSULTED … No recorded decision changed because something was classified into PD-1..PD-6." L82
- **Concepts:**
  - Engineer decisions vs governance decisions (L36–37).
  - FREEZE/UNFREEZE as an implicit decision type, "exercised n≥3 … under no named authority, procedure, or reversal condition" (L55). Submitted as insufficiency evidence, not as a catalog entry (L60).
  - Decision-support as an admission criterion for the ontology. The text calls this "the genuinely NEW part of the review" and routes it to D-8 (L98).
  - "evidence EARNS, governance GRANTS" (L75).
- **Relations and structure:**
  - The table at L66–76 maps ontology elements to the decisions they serve. KNOWLEDGE ≠ ARTIFACT (I-1) serves `DetermineArtifactLifecycle`. The four partial orders serve "in which direction may change propagate".
- **Formal content:** none.
- **Corrections and contradictions:**
  - The text corrects the commission's own premise: "the decision model did not need discovering — it needed FINDING" (L119).
  - It names its own near-miss as "occurrence #12 of proposing-before-searching" (L26).
- **Open questions:** U-DM-1..U-DM-5 (L107–111). U-DM-1 asks whether the catalog/log asymmetry is a defect. U-DM-5 asks whether DOMAIN's silence is permanent or dormant.

---

### S0006 · docs/knowledgeos/KnowledgeOS_Engineering_Decision_Model_Validation.md · 2026-07-11 (internal-2026)

- **Date check:**
  - "Commission | Engineering Decision Model Validation, 2026-08-03" (L8)
  - The date 2026-07-11 is cited as the date of the services→decisions correction (L22) and of the ARB sibling statement (L88).
  - ⚠ **Conflict:** the document was written on 2026-08-03. Its input is S0017 (L9), so S0006 comes after S0017 even though it is listed after it on the same date.
- **Read depth:** complete (L1–123).
- **What it is:** validation and reconciliation of the existing Engineering Decision Model.
- **Key claims:**
  - The model was created to stop duplicated authority: "a decision INDEX, never a second rulebook" (L21).
  - "The absence of governance decisions from the catalog is DESIGN, not gap" L36
  - "It models ZERO architecture decisions" L46. It governs "the ENTRY POINTS, not the rungs" of the ladder `Observation → Hypothesis → Evidence → Principle → Architecture` (L48).
  - "Engineering Governance NOW; Knowledge Governance ANTICIPATED, with the arrival condition already stated." L56
  - "MAP." Nothing in the ontology requires a new decision. "the ontology supplies the VOCABULARY …; the catalog supplies the PURPOSE" (L60, L70).
  - A second decision model would violate the ubiquitous language "three times over" (L74).
  - Four viewpoints are "PARTIALLY SUPPORTED with two corrections" (L82).
- **Concepts:**
  - The PKS-class Knowledge Decisions `DetermineKnowledgeNeed · Source · Representation · Qualification · Promotion` are "pilot-gated candidates" (L54).
  - Viewpoints semantic / structural / behavioral / operational: a candidate presentation only (L84–93).
  - "a DDD Domain Service is executable; a decision is a specification" (L22).
- **Relations and structure:**
  - Four families of the eight decisions (L29–34).
  - The viewpoint relation is "complementary siblings, not a hierarchy" (ARB 2026-07-11, L88).
  - The text drops the arrows: "viewpoints are PROJECTIONS of one platform, and projections do not flow into each other" (L89).
- **Formal content:** none.
- **Corrections and contradictions:**
  - It reframes S0017's Finding 1: "the catalog/log asymmetry is at least partly intentional" (L36).
  - It corrects the reviewer's four-viewpoint hypothesis. Correction 1 removes the arrows (L89). Correction 2 notes the mapping is not 1:1 and the structural slot is contested (L90).
- **Open questions:** U-EDV-1..3 (L99–101). These cover the home of knowledge-governance decisions, the unmet adoption test, and the viewpoint names.

---

### S0042 · docs/knowledgeos/README.md · 2026-08-01 (git)

- **Date check:** no date in the text. It links `ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` (L15), which fits the 2026-08-01 date.
- **Read depth:** complete (L1–20).
- **What it is:** a register or pointer. It is the root README for `docs/knowledgeos`.
- **Key claims:**
  - "Canonical documentation root for the KnowledgeOS domain." L3
  - "Holds | documentation classified `scope: product-specific` · `domain: knowledgeos`" L7
  - "Placement is derived, never chosen. Do not hard-code this path — resolve it." L19
- **Concepts:** derived placement through `php scripts/doc-placement.php` (L17). The text treats it as the operative rule.
- **Relations and structure:** Policy (ADR), then Configuration (yaml), then Resolver (L15–17).
- **Formal content:** none.
- **Corrections and contradictions:** none.
- **Open questions:** none stated.

---

### S0177 · docs/knowledgeos/architecture/03-KnowledgeOS-Evidence-Assurance-and-Governance.md · 2026-08-01 (internal-2026)

- **Date check:**
  - The sources are dated 20260816–20260821 (L15–20).
  - The text cites a "DECISION ACT (2026-08-19)" (L57), the "correction is ACCEPTED (2026-08-04)" (L81), and plans `20260821-1138`/`1641` (L24).
  - ⚠ **Conflict:** the document was written on or after 2026-08-21. The listed 08-01 date probably comes from the cited methodology freeze "(2026-08-01)" at L84 [INT].
- **Read depth:** complete (L1–175).
- **What it is:** a review or consolidation in the Architecture Review Set. It is a status-tagged claim register on evidence, assurance and governance.
- **Key claims:**
  - "I-10 (record outranks prose) makes the most authoritative artifact the least durable → authority and durability are **inverted**" L36–37
  - "same storage ≠ same bounded context" L38
  - "Decision status is graded, not monolithic". The ADR is PROPOSED, while a separate PO/ARB act is DECIDED (L55–58).
  - "automation of deterministic work is not speculative architecture" L82
  - "Automation may reduce the cost of assurance and must never manufacture authority" L108–109
  - "the mechanism records authority; it does not create authority" L127
  - Keep-apart list: "Recording ≠ Asserting · Evidence ≠ Proof · Reference ≠ Ownership · Author ≠ Independent Reviewer · Self-check ≠ Independent Assurance · Execution ≠ Governance" L128–130
- **Concepts:**
  - Three-way separation: source of execution / evidence of execution / authoritative decision record. PROPOSED; D1 DECIDED (L42–45, L57–58).
  - R-CONFLICT: a conflict "never resolved by choosing a side". PROPOSED, adopted by the act as D2 (L50–58).
  - EvidenceRecord / EvidenceReference / EvidenceBundle. PROPOSED (L67–70).
  - Evidence lifecycle: "Observation → Evidence Candidate → Qualified Evidence → Governance Accepted Evidence → Knowledge Product Binding". PROPOSED (L71–74).
  - "Independence is graded, not binary: declared → recorded → access-constrained → technically isolated → organizationally separate → externally assessed". PROPOSED (L120–123).
  - "Responsibility ≠ ownership ≠ authority". PROPOSED (L113).
- **Relations and structure:**
  - Claim levels `[DOMAIN]/[GOVERNANCE]/[TECH]/[PATTERN]` combined with statuses ESTABLISHED/PROPOSED/REJECTED/OPEN (L7–9).
  - Authority model: "operating role → decision right → authority grant (scope/time) → governance body → accountability" (L124–125).
- **Formal content:** none.
- **Corrections and contradictions:**
  - Tensions T1–T4 (L138–143). T2 is assurance as a capability vs a context. T3 is ownership of Evidence vs Governance.
  - The text reports a "round 2 correction" in the corpus: "Evidence does not 'serve' governance" (L75–76).
- **Open questions:**
  - EKS authority gaps: no temporal ending, delegation not represented (L147–149).
  - Workshop questions, e.g. "Can Evidence exist without a Knowledge Product?" (L150–153).
  - What metadata cannot prove about independence (L154–156).

---

### S0179 · docs/knowledgeos/architecture/07-KnowledgeOS-Target-Architecture-Review.md · 2026-08-01 (internal-2026)

- **Date check:**
  - The text cites "DECIDED (PO/ARB 2026-08-19, D1–D2)" (L41) and assurance plans `20260821-…` (L112, L286).
  - ⚠ **Conflict:** the document was written on or after 2026-08-21.
- **Read depth:** complete (L1–290).
- **What it is:** a review or reconciliation of the Architecture Review Set documents 01–06. It is a claim register with Decision Candidates.
- **Key claims:**
  - "This is an architecture reconciliation, not a synthesis." L6
  - D1: "KnowledgeOS is a governed knowledge(-product) platform; core domain = creating, governing, validating, evolving, delivering trusted Knowledge Products". ESTABLISHED (L33).
  - D8: "Rule ≠ Decision ≠ Recommendation ≠ Permission; unknown applicability ≠ non-applicability". ESTABLISHED (L40).
  - D11: the KnowledgeOS (T1) and PKS (T3) context sets "must **not** be merged" (L43).
  - Current state: "Request–response, session-based execution — not event-driven" (L99–100).
  - "Not an operating system, not a kernel, not event-driven, not a Digitalization Robot, not a company or product" (L119–121).
  - DC-1 recommends option (b): "run the validation workshop the corpus itself mandates before any freeze" (L200).
- **Concepts:**
  - Seven bounded contexts (Governance, Product, Evidence, Semantic, Delivery, Intelligence, Platform Administration). PROPOSED (L34).
  - `KnowledgeProduct` as a single aggregate (D3) vs multiple aggregates (D4). Both PROPOSED (L35–36).
  - Kernel/OS model. PROPOSED (L58).
  - Hexagonal: "lived" but no ADR (L49, L105–107).
- **Relations and structure:**
  - Four claim levels: `[DOMAIN]·[PATTERN]·[TECH]·[PRODUCT]` (L21–22).
  - Evolution chain: Current State, then Proposed Evolution, then Decisions Required, then Future Target (L162–189).
  - Decision Candidates DC-1..DC-10 (L196–264).
- **Formal content:** none.
- **Corrections and contradictions:**
  - Tensions C1–C15, left unresolved (L142–158). Examples:
    - C1 kernel vs platform
    - C3 Evidence vs Governance ownership
    - C6 event sourcing vs an append-only log with folded state
    - C13 risk of conflating T1 and T3
  - P2 "Layered/MVC rejected" (L50). P3 ADR-KOS-001 was "declined to freeze as-written — conflates levels" (L51).
- **Open questions:** all of DC-1..DC-10. In particular P7 modular monolith vs microservices (L56) and B7 open vs proprietary (L82).

---

### S0189 · docs/knowledgeos/brainstorming/20260801-1231-eks-current-architecture-baseline.md · 2026-08-01 (filename)

- **Date check:** the text gives no date or session. The filename stamp is the only date. Later documents in this batch treat it as the oldest corpus file: S0580 L90, S1103 L52, S1712 L55. No conflict.
- **Read depth:** complete (L1–2099).
- **What it is:** a current-architecture reconstruction ("architecture archaeology") of EKS.
- **Key claims:**
  - "EKS … is better understood as an engineering knowledge and assurance ecosystem" L17
  - "A documented architecture that is not implemented is not current architecture." L97
  - An eight-level evidence hierarchy runs from implementation (highest) down to historical contextual knowledge (lowest) (L84–93).
  - "Measurement is not knowledge." L768, L1185
  - "No new architecture without an activation event." L1012
  - "Deterministic does not mean demanded." L1048
  - "AI agents are participants in the engineering workflow, not the architectural authority." L347
  - "Historical EKS architecture and the later KnowledgeOS target architecture are not interchangeable." L1590
- **Concepts** (all project definitions, descriptive; none marked adopted by an authority):
  - Knowledge: "Knowledge is durable engineering understanding. It differs from raw observation." L379–381
  - Observation: "a recorded or computed fact about engineering activity or an artifact" L406
  - Recommendation: "a deterministic or rule-derived proposal to the engineer. It does not constitute an architectural decision." L423–425
  - Decision: "the human response to a recommendation" L433
  - Outcome: "what actually happened after a decision" L451
  - Assessment: "evaluates whether an intervention was effective" L459
  - Evidence: "the basis on which a claim, decision, assessment or architectural transition is justified" L467
  - Change: "an engineering event capable of producing observations" L475
  - IMPLEMENTED-BUT-IMPLICIT: a classification (L101)
  - Status vocabulary: CURRENT · IMPLEMENTED-BUT-IMPLICIT · PARTIAL · DOCUMENTED-ONLY · HISTORICAL · PROPOSED · UNKNOWN (L2083–2089)
  - Evidence Analytics ("What happened?") vs Adaptive Recommendation ("What should happen next?"). Both GATED (L1219–1265).
- **Relations and structure:**
  - Knowledge loop: Engineering Activity → Evidence → Observation → Assessment / Recommendation → Human Decision → Engineering Outcome → Effectiveness Evidence → Knowledge → Future Engineering Activity (L117–135)
  - Five layers: Governance, Knowledge, Workflow, Observation Runtime, Developer Activity. These are "architectural responsibilities", not components (L145–183)
  - Runtime pipeline: Trigger → ChangeSet → ObservationRuntime → Collectors → Recommendation → Presentation (L1806–1818)
  - Metric → Observation → Rule → Recommendation (L756–764)
  - Governance mechanism: Proposal → Activation Criterion → Evidence → Decision → Architecture (L1842–1852)
  - Three-question staging: can we build it, should we build it now, should it join the platform. These are "orthogonal" (L1031–1044)
  - Eight invariants (L1673–1703)
  - Bounded-context candidates; Observation and Engineering Improvement are "STRONG BOUNDARY CANDIDATE" (L1377–1385)
  - Ecosystem landscape: PublicDIGIT / KnowledgeOS-EKS / PKS (L1622–1639)
- **Formal content:** none. The structures are informal pipelines.
- **Corrections and contradictions:**
  - A historical correction: the post-commit hook used to bypass ObservationRuntime and was "subsequently corrected" (L842–844).
  - No earlier document is refuted.
- **Open questions:** "Confidence and Unknowns" (L1978–1988). These include the exact PKS/EKS runtime boundary, formal bounded contexts, and "whether every documented governance invariant is mechanically enforced".

---

### S0577 · docs/knowledgeos/brainstorming/synthesis/KNOWLEDGE-CONCEPT-EXTRACTION-001.md · 2026-08-01 (internal-2026)

- **Date check:**
  - The text gives the corpus span: Phase 1 "2026-08-01 → 08-24" and Phase 2 "2026-08-22 → 08-25" (L49).
  - It cites `kernel/20260825-103542` (L145) and compares against "v1.1 / the Constitution" (L15).
  - ⚠ **Conflict:** the document was written on or after 2026-08-25.
- **Read depth:** complete (L1–367).
- **What it is:** an extraction from the corpus. It is a concept extraction of "What is Knowledge?" across Phase 1 (`brainstorming/`, 99 docs) and Phase 2 (`brainstorming/kernel/`, 151 docs).
- **Key claims:**
  - "The corpus contains no single definition of Knowledge. It contains roughly 90 definitional sentences and at least ten materially different conceptions." L21–22
  - "Phase 1 converged on Knowledge as a RELATIONSHIP" L25
  - "the relationship conception is Phase 1 original project thinking, not later book-derived research" L37–38
  - "Knowledge Space (in its Phase 2 sense), capacity, and measure are not present in Phase 1" L39–40
  - "K-11 is the deepest tension in the corpus. It does not compete within the field of definitions — it denies the field." L115–116
  - "K-4, K-7 and K-9 cannot support an identity-bearing aggregate at all." L264–265
  - The zero-lens core: "a knower/participant · something known · a ground · and a record that the two were connected" L273
  - "The corpus's most stable result is negative — Knowledge is not stored content." L354
- **Concepts:**
  - Competing models K-1..K-11. All are preserved and none is chosen (L101–113). They are:
    - K-1 stored content (rejected)
    - K-2 relationship
    - K-3 justified state evolution
    - K-4 pipeline
    - K-5 Cognitive Worlds
    - K-6 factive relation to a Knowledge Space
    - K-7 capacity
    - K-8 flow
    - K-9 measure
    - K-10 institutional
    - K-11 primitive
  - Knowledge Space is "a Phase 2 hypothesis with a Phase 1 vocabulary precedent that meant something else" (L233).
  - Projection has three incompatible senses: CQRS, epistemic, and measure (L240–242).
  - From the formal law (⟦L⟧), as comparison only: Core Domain = Knowledge Identity, "the domain of identity-bearing justified epistemic states and their justified life" (L290–291).
  - From ⟦L⟧: "UNKNOWN · ABSENT · FALSE · REJECTED are states of an identified epistemic object" (L280–281).
- **Relations and structure:**
  - Three registers that are never merged: ⟦C⟧ / ⟦I⟧ / ⟦L⟧ (L11–15).
  - Phase-evolution diagram (L122–135).
  - DDD-implication table: what the Knowledge object would be under each model (L255–262).
  - Consistency classification against ⟦L⟧ (L293–306).
- **Formal content:**
  - It mentions "a measure over (Ω, F)" (L242) and K-9 "projection as measure" (L111).
  - It reports that K-9 is under attack in the corpus (L243–244).
  - The text defines no mathematics of its own.
- **Corrections and contradictions:**
  - Self-correction: "I previously reported that 'Knowledge Space' first appears at `kernel/20260825-103542`. That was kernel-scoped and wrong corpus-wide." The phrase appears at `20260822-0200` "as a diagram label" (L144–152).
  - It lists K-4, K-7 and K-9 as a "DIRECT CONTRADICTION" of the law's §4 (L303).
- **Open questions:**
  - "Does change create a new identity?" is "asked by no document and answered by none" (L190–191).
  - Falsification questions 1–7 (L333–339).
  - The unresolved list (L348–350).

---

### S0580 · docs/knowledgeos/brainstorming/synthesis/KNOWLEDGE-RECONSTRUCTION-LEDGER.md · 2026-08-01 (internal-2026)

- **Date check:**
  - "Programme: Session 1 · KnowledgeOS Research Reconstruction" (L4).
  - It treats documents dated 2026-08-22 as earlier sources (L115–117) and refers to "the entire 257-document corpus" (L148) and to "v1.1 §15" (L99).
  - ⚠ **Conflict:** the document was written after 2026-08-22, and it postdates the formal v1.1 [INT]. The 08-01 date is the date of its only processed document.
- **Read depth:** complete (L1–158).
- **What it is:** a register, namely a research ledger with a model-change log. It processes one document, S0189.
- **Key claims:**
  - K-M1 extends K-M0. "NEW CONCEPT — knowledge has a generative pathway." L35
  - "DOC-1 defines knowledge by derivation, not by composition" L54
  - "Observation ──► Recommendation ──► Decision ──► Outcome ──► Assessment ──► KNOWLEDGE" L46
  - The ledger notes what the change does not mean: "it does not mean adding Decision, Outcome or Assessment to the Kernel" L76–77
  - "Applied reflexively, the entire 257-document corpus this programme reconstructs is priority-7 evidence by the project's oldest stated rule." L148–149
- **Concepts:**
  - KCON-001 Knowledge as relationship: "STRONG RESEARCH MODEL" (L84–86)
  - KCON-002 Knowledge as durable understanding: "DEFINITION (project)", first appearance S0189 §9.1 (L88–91)
  - KCON-003 derived from decisions and outcomes: "CLAIM" (L93–95)
  - KCON-004 Observation ≠ Knowledge: "DEFINITION / non-collapse" (L97–100)
  - KCON-005 Evidence as first-class (L102–104)
  - Model IDs K-M0 → K-M1. Change types M2 EXTEND / M3 (L33)
- **Relations and structure:**
  - Six registers that are "never merged": RESEARCH FACT · RESEARCH HYPOTHESIS · EXTERNAL THEORY · PROJECT CONCEPT · ARCHITECTURAL PRESSURE · FORMAL ARCHITECTURE (L6–7).
- **Formal content:**
  - Schematic only: `Knowledge ← relationship( knower, object, evidence, justification, reasoning, context, conclusion )` (L41).
- **Corrections and contradictions:**
  - C-K1: "Is knowledge understanding or a relationship?" (L110–127). This sets S0189 §9.1 against `20260822-0135` and later documents.
  - "Why reconciliation is NOT accepted: no document proposes it" (L123–125).
  - ⚠ Citation issue: it cites "DOC-1 §61" for the exclusion of future kernel concepts (L154). S0189 has only §1–§60. The exclusion is at S0189 §2.1, line 61. The ledger appears to have turned a line number into a section number [INT].
- **Open questions:**
  - "whether the pathway is constitutive of knowledge or merely one production route" L72–74
  - Whether Assessment is the only transition point (L74).

---

### S1103 · docs/knowledgeos/reviews/synthesis/analysis/phase-archaeology-inventory.md · 2026-08-01 (internal-2026)

- **Date check:** "Date of all counts: 2026-08-28." (L3). ⚠ **Conflict:** the document was written on 2026-08-28. The 08-01 date is the corpus start date it reports (L52).
- **Read depth:** complete (L1–60).
- **What it is:** an archaeology inventory, a census of the corpus with a coverage plan.
- **Key claims:**
  - "Historical total | 688 | 2026-08-01 → 2026-08-28" L13
  - "The three main subcorpora are chronologically sequential phases" L15
  - The "1428 'prompt2'" is a "synthesis echo … GN-01: zero evidential weight" L23
  - "copies never count as corroboration" L26
  - Kernel read depth: "~3–5% full-read; 39 K3/K4 docs worklisted for full reads, 1 completed" L43
  - "2026-08-01 EKS baseline (single early outlier — the corpus's oldest KnowledgeOS/EKS mention)" L52
  - "typed mathematical model — Ω's first appearance 2026-08-24" L56
- **Concepts:**
  - Artifact classes PROCESS/PROMPT-ARTIFACT, DUPLICATE/DERIVATIVE and UNCLASSIFIED (binary) (L23–26).
  - Eras R1–R5 (L57–58).
- **Relations and structure:**
  - Three phases: root (99), kernel (170), phase_measure_theory (413) (L9–11).
  - Cluster map (L51–59).
- **Formal content:** none. It records the first appearance of Ω on 2026-08-24 (L56).
- **Corrections and contradictions:**
  - Its kernel count of 170 (L10) differs from S0577's 151 (S0577 L48). [INT] The census dates differ (2026-08-28 here, earlier for S0577). S1712 later states the rule "Dated statistics" (L79–80).
- **Open questions:** the depth caveats at L42–47.

---

### S1712 · docs/knowledgeos/reviews/synthesis/book-edition-2/part-2-reconstruction/02-02-grades-and-the-final-rule/chapter.md · 2026-08-01 (internal-2026)

- **Date check:**
  - "The first file in the record (2026-08-01) already refuses to imply a target architecture" (L55).
  - It cites a corpus line "(dated 2026-08-27)" (L92), the formal era's "Step 109" (L56), and "Edition 2" against a frozen Edition 1 (L3–5).
  - ⚠ **Conflict:** this is a book chapter written well after 2026-08-28 [INT]. The 08-01 date is the corpus file it refers to.
- **Read depth:** complete (L1–139).
- **What it is:** a synthesis chapter of a book (Edition 2) on the grading method.
- **Key claims:**
  - "Every load-bearing statement in this programme carries a grade, and the grading system is the method's spine." L4–5
  - "The grades are a provenance scale, not a truth scale" L36
  - "The final rule — the strongest statement made never exceeds the strength of the available evidence" L70–71
  - "an absence claim is worth exactly as much as the description of the search that produced it." L113–114
  - "a chain of READ claims is not itself READ — composition is the synthesis's act" L123–124
  - I-6 traced end to end: the property is "a *pipeline* property, impossible for any scalar operator alone" (L99–100).
- **Concepts:**
  - Element grades TESTED / READ / COMPOSITION / SPECIFIED / HYPOTHESIS, each with an operational meaning (L23–34). Status: "currently ratified [FA/M]" (L18).
  - Claim types [FA] [H] [M] [P] [E] [IN] [RC] [R] [U] (L42–46).
  - Honesty rules (L69–87). These are:
    - the final rule
    - no silent upgrade
    - the echo rule
    - dated statistics
    - the verbatim rule
    - claim-strength inheritance
  - The weak class for absence claims (L107–114).
- **Relations and structure:**
  - Two orthogonal families, grades and claim types, which "compose" (L47–50).
  - Relation-status ledger BA-ED2-11 (L126–130).
- **Formal content:** none. The grade scale is ordinal provenance.
- **Corrections and contradictions:**
  - It records "a claimed 0.6% sampling miss-rate was superseded" and "an 'only appears at' first-occurrence claim fell to a wider search" (L109–110). [INT] The second matches the self-correction in S0577 L144–152.
  - It records three grade-discipline failures inside the book itself (L119).
- **Open questions:**
  - "Grades are assigned judgments: auditable … not provable" (L118).
  - Drift under editing is "controlled, not eliminated" (L129).

---

### S0028 · docs/knowledgeos/2026-08-02-knowledgeos-architecture-baseline.md · 2026-08-02 (filename)

- **Date check:**
  - "Commission … 2026-08-02" (L8) and "ANNOTATED 2026-08-02" (L95). These are consistent with the listed date.
  - ⚠ Order: the traceability line calls it a "SYNTHESIS of the Architecture Consolidation · Product Boundary Discovery · MVK Bootstrap Validation · Strategic Boundary Consolidation" (L265). It was therefore written **after** S0031, S0039 and S0033, although the list places it before them.
- **Read depth:** complete (L1–268).
- **What it is:** a synthesis, described as a strategic reference model. It is a CANDIDATE.
- **Key claims:**
  - "The generative relation is `creates`, not `contains`. `KnowledgeOS → creates → PKS → guides → Product`." L31–32
  - "T2 is the tier the recent work uncovered, and it is NOT PKS." L47
  - "subsystems are INTERNAL. They are not repository roots." L60–61
  - "THIS IS THE REAL EXTRACTION WORK: decomposing artifacts into method / binding / evidence — not moving files." L86
  - "Per ES-006.1, n=1 admits a CANDIDATE, never a rule — so all five are candidates." L91
  - "the platform governs **change**; it does not govern **genesis**" L184
  - "for the first time, the platform changed an engineering decision rather than merely describing one" L261
- **Concepts:**
  - **Product Binding**: "the product-specific input contract a reusable method requires. SD-1 is the archetype". Labelled "the concept this baseline contributes" (L29).
  - PKS = Product Knowledge Space: "One product's knowledge, one per product … Never reusable — an instance" (L25).
  - AI Runtime: "an ADAPTER, not a product" (L28).
  - P1 Method/Binding/Evidence. Annotated: "P1 WAS RE-DERIVED, NOT DISCOVERED" from Round38C-04 Principle/Form; "Cognate, not duplicate" (L95–101).
  - P2 Portability Ladder (L112–135), P3 Responsibility vs Component (L140–152), P4 Evidence Harvest (L154–165), P5 Product Architecture Reference (L167–171).
  - All are CANDIDATE. P1–P5 are "DOCUMENT-LOCAL LABELS, not minted identifiers" (L14).
- **Relations and structure:**
  - Five tiers T1–T5 (L38–44).
  - Six subsystems, each with its evidence status (L51–58).
  - Three-layer extraction test: METHOD / BINDING / EVIDENCE (L71–75).
  - Lifecycle L-1..L-6 (L175–182).
  - Roadmap stages 0–5 (L231–238).
- **Formal content:**
  - "Extraction readiness = *f*(domain-free, binding-free, evidence-free)." (L114)
  - Tier 1 is the conjunction "domain-free ∧ binding-free ∧ evidence-free" (L118).
  - P3's three-condition rule for when a responsibility becomes a component (L142).
- **Corrections and contradictions:**
  - It calls itself "the baseline's central correction to §6 of the Product Boundary Discovery, which named files and was refuted at n=1" (L69), i.e. a correction to S0039.
  - P1 is self-corrected as re-derived (L95–101).
  - It notes that the Addendum's "Separate Ways with a Shared Kernel" "is internally tense in Evans' taxonomy" (L171).
  - Accepted refinement: "'PKS Generator doesn't exist' was imprecise" (L144).
- **Open questions:** OQ-S1..S5, OQ-K1..K6, OQ-C1..C2 (L188–202). Evidence gaps G-1..G-10 (L206–217).

---

### S0031 · docs/knowledgeos/2026-08-02-knowledgeos-architecture-consolidation.md · 2026-08-02 (filename)

- **Date check:** "Commission | Principal Software Architect / Principal Knowledge Engineer, 2026-08-02" (L7). Consistent. [INT] It is the earliest of the four 08-02 KnowledgeOS documents in this batch: S0039 accepts it as input (S0039 L8).
- **Read depth:** complete (L1–248).
- **What it is:** an architecture consolidation, i.e. a migration map. It is described as "Identifies architecture that already exists. Invents none".
- **Key claims:**
  - "the current strategic model is sufficient FOR THE PRESENT MATURITY LEVEL" L13
  - "Domain-free methodology is already sitting in a product-classified path." L46
  - "KnowledgeOS may be extracted by REFERENCE, never by COPY" L89
  - "Paths change; ids never do." This is quoted from the registry and called "references survive relocation" (L124).
  - "Nine of sixteen rows need NO MOVE … Seven are blocked, and every blocker is a recorded governance gate, not a design gap." L151
  - "Three of the loop's four arrows are unevidenced, and the return arrow is empirically empty." L206
  - "KnowledgeOS does not need to be designed. It needs four triggers to fire." L240
- **Concepts:**
  - The vision "`KnowledgeOS creates PKS` · PKS is an **instance**, not a product". ACCEPTED as destination, not evaluated (L8).
  - Four-layer runtime model: GOVERNANCE POLICY → CAPABILITY MAPPING → RUNTIME ADAPTER → CONCRETE CONFIGURATION. Quoted as existing (L107–111).
  - The kernel principle "Governance precedes automation. Automation may implement governance. Automation never defines governance." Quoted from the Reference Architecture (L67).
- **Relations and structure:**
  - Three governed concerns: Product, Engineering Platform, Runtime mount (L22–26).
  - Migration map M-1..M-16 (L132–149).
  - Stage roadmap 0–5 (L157–164).
  - Dependency graph (L199–204): generates (SPECULATIVE), guides (SPECULATIVE), produces (EVIDENCED), improves (EMPTY).
- **Formal content:** none.
- **Corrections and contradictions:**
  - It restates "the prior discovery's verdict … not *sufficient*" (L12–14). The prior discovery is not named in the file.
  - It cites BRM-1, which retired SDM-EXT-1: "its premise was that the baseline should be materialized, and that premise is decided against" (L85).
- **Open questions:** OQ-K1..K6, OQ-C1, OQ-C2 (L174–183). Gaps G-C1..G-C7 (L187–195).

---

### S0039 · docs/knowledgeos/2026-08-02-knowledgeos-product-boundary-discovery.md · 2026-08-02 (filename)

- **Date check:** "Commission … 2026-08-02" (L7). It was annotated after the MVK test: "TESTED 2026-08-02, VERDICT `FAIL`" (L192), "EXECUTED 2026-08-02" (L286). Consistent.
- **Read depth:** complete (L1–333).
- **What it is:** a strategic DDD discovery of product boundaries, later annotated with the result of the MVK test.
- **Key claims:**
  - Litmus: "would it exist if PublicDigit disappeared?" L33
  - "What is missing is not a domain. It is a single traversal." L95
  - "The knowledge flow and the authority flow run in opposite directions." L133
  - "The honest state: 1 evidenced relationship, 5 candidates, 0 assigned patterns." L153
  - "without L-6 this is a pipeline, not a flywheel." L168
  - "DOMAIN-FREE IS NOT THE SAME AS PORTABLE" L202
  - "portability is a property of parts and sufficiency is a property of the whole." L234
  - "A reusable engineering platform whose reusability has never been exercised." L318
- **Concepts:**
  - SP-1..SP-5 (L35–98). SP-3 AI Runtime is "reclassified as an ADAPTER BOUNDARY, not a product" (L62). SP-5 Operational Learning is "NOT MISSING. DISPERSED, NAMED, AND NEVER ONCE EXERCISED" (L80).
  - Minimum Viable KnowledgeOS (MVK): first a "HYPOTHESIS", then "REFUTED IN PART … `FAIL`" (L192, L236).
  - Extraction categories: PERMANENT KnowledgeOS / PRODUCT TEMPLATE / PRODUCT INSTANCE / RUNTIME ADAPTER (L174–186).
  - "The boundary runs between the PROTOCOL and the RECORDS" (L251).
- **Relations and structure:**
  - Product Context Map (L104–127).
  - One evidenced relationship, SP-4 → SP-1: "the product binds the platform; never forks it" (L141). Candidates PR-1..PR-5 (L145–151).
  - Lifecycle L-1..L-6 (L158–165).
  - Risks AR-1..AR-8 (L273–282).
- **Formal content:** none.
- **Corrections and contradictions:**
  - Self-correction: "My own error, named … They are 73% of the MVK by volume and mostly one project's case law" (L202).
  - It refines the commission's claim that "This one is missing" (L82–84).
  - AR-1 is "REALIZED 2026-08-02 — CONFIRMED BY TEST" (L275). G-2 is "CLOSED … now a finding" (L261).
- **Open questions:**
  - G-1..G-10 (L258–269).
  - "'guides' has never been measured against an unguided baseline" (L162).

---

### S0033 · docs/knowledgeos/2026-08-02-knowledgeos-strategic-boundary-consolidation.md · 2026-08-02 (filename)

- **Date check:** "Commission | Strategic Boundary Consolidation, 2026-08-02" (L7). Consistent. It reviews the MVK report and S0039 as inputs (L9).
- **Read depth:** complete (L1–293).
- **What it is:** a strategic discovery report. It asks whether the MVK experiment changes the domain model.
- **Key claims:**
  - "ANSWER: NO. The experiment does not change the Strategic Domain Model. It changes the EXTRACTION BOUNDARY" L11–12
  - "C-1 · 'There is no strategic-DDD method' — REFUTED. The method exists." L18
  - "'MVK failed' ≠ 'KnowledgeOS failed'" L47
  - "The 'PKS Generator' slot … is currently occupied by a person reading documents." L71
  - "NO NEW STABLE STRATEGIC DOMAIN IS EVIDENCED." L88
  - The two classifications, product ownership and architectural responsibility, "are ORTHOGONAL and must not be collapsed" L96
  - Two extraction blockers: "CASE-LAW DILUTION … and COUPLING AT THE BOUNDARY" L166. "Neither is detectable by searching for domain vocabulary." L167
  - "No promotion may occur on architectural reasoning alone." L214
- **Concepts:**
  - Three bootstrap responsibilities: R-A bootstrap PKS (n=0), R-B engineering (n=1), R-C software (n=0) (L65–69).
  - Three architectural levels: repository → products; product → its architecture; subsystem → inside KnowledgeOS (L113–114).
  - Candidate classes: bounded context / capability / supporting subsystem / workflow / observation (L124–131). Results:
    - PKS Generation: "an observation only"
    - Bootstrap: "a workflow with a missing governance clause"
- **Relations and structure:**
  - Six candidates tested against Round47-OP's nine admissible criteria (L79–86).
  - Context map in which 1 of 5 arrows is evidenced (L174–209).
  - A promotion-evidence table that names the operational event needed for each candidate (L216–224).
- **Formal content:** none.
- **Corrections and contradictions:**
  - It refutes the MVK Bootstrap Validation report's F-3: "F-3 was wrong" (L33). The corrected finding is at L45.
  - It corrects that report's title verdict: "the report conflated them in its title verdict" (L54).
  - It validates "domain-free ≠ reusable" in both directions (L158–163).
- **Open questions:** OQ-S1..OQ-S5 (L241–245), with OQ-S1, "Can Round47-OP's nine criteria be consumed without SD-1's certified release", as the highest priority.

---

## Batch synthesis

**What problem these documents work on.**

The batch contains three strands, and the listed dates hide this.

1. **2026-08-01 to 2026-08-03: EKS/KnowledgeOS strategic boundary and governance.** Documents S0189, S0042, S0031, S0039, S0033, S0028, S0011, S0013, S0017 and S0006 ask the same thing in different ways: what KnowledgeOS is (S0039 L316) and what may be called architecture on the current evidence.
2. **About 2026-08-21: the Architecture Review Set.** S0177 and S0179 register claims about evidence, assurance and target architecture.
3. **2026-08-25 onward: reconstruction of the concept of knowledge.** S0577, S0580, S1103, S1712 and S1823 cover what Knowledge is, how the record is graded, and test cases for an executable model.

Eleven listed dates conflict with dates in the texts. S0011, S0013, S0017, S0006, S0177, S0179, S0577, S0580, S1103, S1712 and S1823 all date themselves later than listed. S0028 was written after S0031, S0039 and S0033 (S0028 L265).

**First appearances in this batch.**

- S0189 gives the oldest project definition of knowledge: "durable engineering understanding" that "differs from raw observation" (L379–381). It also states "Measurement is not knowledge" (L768) and the evidence hierarchy (L84–93).
- S0028 introduces Product Binding (L29) and the Method/Binding/Evidence decomposition (L71–75). S0028 itself later qualifies the decomposition as re-derived (L95).
- S0039 gives the generative relation `KnowledgeOS → creates → PKS` together with the empty return arrow L-6 (L165). S0031 also states it (L199–204).
- S0033 introduces the three bootstrap responsibilities (L65–69).
- S0013 introduces "Authority = PROVENANCE × STANDING" (L55) and "Knowledge Space = boundary + owner + constitution" (L54).
- S0577 introduces the factive, participant- and time-relative relation directed at a Knowledge Space (L89), and the zero-lens core (L273).

**What changed across the batch.**

- **Boundary vs model.** The MVK boundary was refuted while the strategic model held (S0039 L192, S0033 L11–12).
- **"0 traversals" overturned.** The claim was stated in S0031 L189 and S0039 L95, then marked rejected in S0013 R-9: n≈3 (L101).
- **Discovery became reconciliation.** In S0017 L12 and S0006 L60 the decision model was found rather than invented.
- **The knowledge concept moved.** It went from understanding (S0189) to relationship (Phase 1), and then to relativisation to a space (S0577 L137–139). S0580 records C-K1 as unresolved (L110–127).

**What remains open.**

- Whether SD-1 is a product binding (OQ-S1).
- Whether any PKS can be generated, since R-A is n=0.
- Ownership of Evidence vs Governance (S0177 T3, S0179 C3).
- FREEZE and RETIRE, which are ungoverned decisions (S0017 L60).
- Understanding vs relationship (S0580 C-K1).
- Whether a change of state creates a new identity (S0577 L190).
- K-11's challenge to the definitional programme itself (S0577 L115).
