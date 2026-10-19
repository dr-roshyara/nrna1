C6 step 1: blind reading of S0031–S0035

Scope: I read only the five assigned files, each in full with Read. I used no grep or glob, opened no other file and wrote no files. Every anchor below is a line number in the file named. Items marked [I] are my own judgments. Everything else reports what the source says.

I have noted the coordinator's revised step-2 coverage question. It did not change this blind reading.

---

## S0031 — `2026-08-02-knowledgeos-architecture-consolidation.md`

**Concepts and definitions**
- **Self-declared kind** (L5): "Identifies architecture that already exists. Invents none · improves none · redesigns none · renames none."
- **Vision** (L8): "ACCEPTED AS DESTINATION, not evaluated". The vision is "KnowledgeOS creates PKS", "PKS is an instance, not a product", and "four strategic products". The four products are never listed.
- **Carried-in correction** (L12–14): "sufficient FOR THE PRESENT MATURITY LEVEL". The text says the model is "currently validated", not "mature".
- **Three governed concerns from ES-005.1** (L20–26):
  - Product: `docs/`, `architecture/`, `app/`, `tests/`.
  - Engineering Platform: `engineering/`.
  - Runtime mount: `.claude/`, which is "never 'the architecture'".
- **Three registered documentation domains** (L28): `publicdigit`, `knowledgeos`, `pks`.
- **Measured landscape** (L32–40): `app/` has 1,532 files (133 under `app/Domain`). `engineering/verification` has 106 md. The rest of `engineering/` has 30 md + 2. `docs/architecture` has 448. `docs/implementation` has 195, of which 92 are `PKS_*`. `docs/knowledge` has 132. `docs/pks`, `docs/knowledgeos` and `docs/publicdigit` have 9, 2 and 12.
- **Central finding** (L42–46, quoting EAD-1): domain-free methodology (Integrity Model, Review Method, ARB Discipline; zero election terms) sits in a product-classified path. The text calls this "misplacement — not any missing design".
- **KnowledgeOS inventory** (L52–65). The Reference Architecture is called "the kernel" (L56). Reference Model §8 is "the strategic model" (L61).
- **Kernel principles** (L67–69): "Governance precedes automation. Automation may implement governance. Automation never defines governance." There is no orchestrator, workflow engine or state machine "until operational evidence demonstrates insufficiency" (R-37, burden of proof).
- **BRM-1 / RETAIN MODEL B** (L83–89). SDM-EXT-1 was retired, so materialization was decided against. The text concludes "Therefore: extracted by REFERENCE, never by COPY", which it equates with ES-005.4 ("one rule → one home").
- **Four-layer runtime model** (L104–118): Governance Policy → Capability Mapping (tool-neutral) → Runtime Adapter → Concrete Configuration. The text says `settings.json` "is an ADAPTER", that the model is portable "rather than tool-shaped", and that Capability Mapping exists so vocabulary "cannot LEAK UPWARD".
- **Stable ids** (L124): CMP-nnn and AST-nnn, "Paths change; ids never do". The text calls this "the single most important extraction-safety property".
- **R-42** (L125): the platform/product line drawn inside the runtime model.
- **Reserved `registry/` namespace** (L126): its trigger is "a second runtime adapter".

**Logical structure**
- **Migration map** M-1..M-16 (L132–149). Each row gives current home, future home, reason and a trigger "quoted from canon".
- **Count claim** (L151, repeated at L235–236): "Nine of sixteen need NO MOVE … Seven are blocked, and every blocker is a recorded governance gate, not a design gap".
- **Stage roadmap 0–5** (L157–164): Current, Consolidation, Extraction, Reusable KnowledgeOS, `knowledgeos init`, Multiple Products. The text says "Only Stage 1 is open" (L170).
- **Open decisions** OQ-K1..K6, OQ-C1 and OQ-C2 (L176–183).
- **Evidence gaps** G-C1..G-C7 (L189–195).
- **Four-arrow loop** (L199–206): KnowledgeOS→PKS is SPECULATIVE, PKS→PublicDigit is SPECULATIVE, PublicDigit→Evidence is EVIDENCED (106 reports), Evidence→KnowledgeOS is EMPTY. "The loop is the accepted destination; it is not yet an observed cycle."
- **Verdict** (L240–241): "KnowledgeOS does not need to be designed. It needs four triggers to fire" (second product, second adapter, market evidence, return-arrow traversal). The text adds that this "is the last [document] that should be written before the first of them fires."

**Strength words:** "already", "verbatim", "ready now", "verified", "permanently", "never", "none, ever", "the single most important".

**Internal contradictions and weaknesses [I]**
1. **Self-description versus vision.** "Invents none" (L5) sits beside an unevaluated vision accepted as destination (L8). That vision ("creates PKS") is marked SPECULATIVE by the document's own graph (L200).
2. **"Everything below passes ES-005.3" (L50).** The table under it includes `Layer_Verification_Rule`, marked "blocked — OQ-K1" (L63). It also includes `engineering/verification`, marked SPLIT with records that are product-side (L65).
3. **The 9/7 count does not reconcile.**
   - Rows needing no move: M-1, M-2, M-3, M-4, M-8, M-11, M-12. That is 7.
   - M-10 is "already in permanent home" (L235) and also "READY NOW" for extraction to KnowledgeOS (L143). Those are incompatible.
   - Rows blocked or unresolved: M-5, M-6, M-7, M-9, M-13, M-14, M-15, M-16. That is 8.
   - So the split is 7 or 8 against 8, not 9/7.
4. **"Every blocker is a governance gate, not a design gap" (L151).** M-16 is PENDING, meaning unruled, which is a gap. The triggers for M-13 and M-15 are evidence events (a second adapter, a retrospective), not gates. The verdict at L238 itself lists missing evidence.
5. **Stage 1 is called unblocked but has prerequisites.** It is "available now" (L160) and "Why it is unblocked" (L216), yet carries the prerequisite "OQ-K1 + OQ-K2 first" (L217). Those are ARB decisions, so Stage 1 is gated.
6. **"Therefore: by reference" (L89) overreaches.** OQ-C1 (L182) admits that reference-based extraction "was never put to" BRM-1.
7. **M-10 "verified: zero repository knowledge" (L143).** S0035 O-21 shows this rests on a grep that returned one hit. That is evidence of absence by search, not portability.
8. **Unverified attribution.** "Four authorities, over three weeks" (L168, L237) is never itemized.
9. **"The last document" (L241)** is falsified by the same-day S0033 and S0034. S0035 rec 9 even points to this file as a companion.
10. **Portability is inferred from vocabulary absence.** The central finding (domain-free means it belongs in KnowledgeOS, M-6) reads "zero election terms" as reusable. S0033 refutes exactly this inference.

---

## S0032 — `KnowledgeOS_Vision_Mission_Clarification.md`

**Concepts and definitions**
- **Evidence grades** (L10): OBSERVED, INFERRED, HYPOTHESIZED, plus "INTENDED (new)". INTENDED is withdrawn at L25–27 because it "overloaded the evidence dimension". Intent is re-derived as `f(governance-status, owner)`, citing `KnowledgeOS_Meta_Model.md`.
- **Principle** (L15): "ARTIFACT ABSENCE ≠ CONCEPT ABSENCE." It lists three self-errors: no strategic-DDD method, no capability pattern, mission vacant (L17–21).
- **Analogy** (L23): "Linux and PostgreSQL have no mission statements and unmistakable missions."
- **Constraint versus mission** (L33, L70–71): "A mission does not ship with a per-iteration metric and a review trigger. A CONTROL does." AIP-14 is reclassified as an execution constraint.
- **Vision** (L41–59): the charter hypothesis, "AI-native Knowledge Operating System for software engineering…", with the falsification clause "(to be validated or falsified — not assumed)". Rule (L59): a statement with its own falsifier that awaits market validation is a vision.
- **Implicit or enacted mission** (L63–91). Eight artifacts are cited:
  1. ES-005.3 litmus.
  2. ES-006.4 harvest question.
  3. ES-006.1 promotion ladder.
  4. Reference Architecture §1 independence.
  5. Platform_Capability_Pattern.
  6. `registry/`.
  7. The Platform≙Adoption split "pre-positioned".
  8. ES-005.4.
- **Mission sentence derived from these** (L89): "Provide a reusable engineering platform that improves the development of software systems through governed engineering knowledge." It is classified "IMPLICIT · EVIDENCED · NOT YET FORMALIZED".
- **Three-way distinction** (L106): strategic aspiration, implicit mission, current formal authorization (none at mission level).
- **Discovery versus ratification** (L108): "waiting to be RATIFIED".
- **Roles of PublicDigit** (L114–120): product (OBSERVED), beneficiary (OBSERVED), binding constraint (OBSERVED, "the product BINDS the platform; never forks it"), first adopter (INFERRED), laboratory (HYPOTHESIZED, "NOT FOUND IN CANON").
- **Laboratory versus Product Primacy** (L124–132): presented as "opposite directions of service", then "Both can be true across time horizons — but only if the horizon is stated."
- **Five layers and owners** (L136–150): Vision and Mission belong to the sponsor and are unratified. Governance belongs to the DA/ARB. Engineering belongs to sponsor + ARB plus "an individual (owner: nab.raj.sharma)". Product belongs to the producing track.
- **Hierarchy** (L154–165): Vision → Mission → Governance → Capabilities → PKS → Product. PKS is "ONE, authored, not generated (n=0)".
- **Conceptual versus chronological order** (L167): "built bottom-up; … discovered top-down".
- **What dissolves and what survives** (L171–181): the "two missions" conflict was a labelling error, but AIP-14's enforcement and the charter's gates are real. "Reframing … does not change what is authorized."
- **Three corrections to the review** (L194–198):
  1. "KnowledgeOS is the product" contradicts AIP-14. PublicDigit is the Core Domain and the platform a Supporting Subdomain.
  2. "9 rediscoveries, all unindexed" is "REFUTED at n=10".
  3. "No capability or check in the repository enforces — every one is advisory." `knowledgeos init` would be "the first enforcing mechanism".
- **Recursive property** (L204–213): four artifacts (Round39-02, Round39-MC, ES-006, Round38C-P2-18) show "engineering of engineering knowledge".

**Internal contradictions (later annotations overriding the body)**
1. **Rev 2 says "not vacant" (L11, L13–29), but the body and traceability still say vacant.** L167 reads "Mission is vacant". L190 reads "above the vacant Mission layer". The traceability line (L233) reads "new finding: the MISSION LAYER IS VACANT in canon, which is why a constraint rose to fill it". The traceability records the very claim Rev 2 retracts.
2. **"Implicit" is superseded by "ENACTED" (L29)**, yet L63, L91, L139, L157 and L222 still use "implicit". The terms are mixed throughout.
3. **INTENDED is withdrawn (L25)**, but the header (L10) still advertises it as new.
4. **The mission-owner grade is wrong.** The mission owner is graded OBSERVED (L139) while also "no owner has ratified it". The owner is therefore inferred. [I]
5. **The mission sentence is mis-graded.** It is the author's composition (L89) yet is graded OBSERVED in the lifecycle table (L157). By the document's own scale it is INFERRED at best. [I]
6. **The eight artifacts are not independent** (L91, "eight independent artifacts"). Five come from the same standards family, ES-005 and ES-006, which S0031 L54 shows are PROPOSED. [I]
7. **"Meaningless unless reuse … is a purpose" (L75) overreaches.** Portability tests and adapter isolation also serve hygiene, replaceability and maintainability inside one product. [I]
8. **The machinery reading conflicts with an adopted ruling.** S0032 reads a product-independent mission out of the machinery while the adopted AIP-14 says the platform exists "solely to improve delivery of PublicDigit" (L68). It also says "intent is not evidence" (L25). Inferring mission from enacted machinery against an explicit written ruling is the very move it forbids. [I]
9. **Correction 3's universal claim is unsupported and contradicted.** "No capability or check … enforces" has no measurement here. S0034 L14 cites "18 enforced lint rules".
10. **The recursion artifacts are not independent.** The four "independent" artifacts are all Round38/39-era methodology from one programme. [I]
11. **The analogy is decorative.** The Linux/PostgreSQL line is an unverified analogy used rhetorically. [I]

---

## S0033 — `2026-08-02-knowledgeos-strategic-boundary-consolidation.md`

**Concepts and definitions**
- **Question and answer** (L7, L11–12): does the MVK experiment change the Strategic Domain Model? "NO." It changes the extraction boundary instead.
- **C-1** (L18–45): "no strategic-DDD method" (F-3) is refuted. Round47-00 is ADOPTED with SD-1..SD-7, and Round47-OP is BINDING.
- **Nine admissible software-boundary criteria** (L29): semantic ownership, transactional consistency, lifecycle independence, invariants, UL divergence, team autonomy, deployment autonomy, integration characteristics, performance constraints.
  - The rule is promotion "only if supported by **one or more**". No other justification is admissible ("It seems reasonable", "the ontology has this concept" and "the folder exists" are named as inadmissible).
  - The protocol also states "Semantic truth does NOT automatically imply software structure" (L31). It includes a candidate rejection protocol and a retroactive demotion.
- **Three couplings that block reuse** (L37–41): a product path; SD-1 consumes only the "Certified Domain Knowledge Release v1.0", which makes the method "unconsumable elsewhere as written"; election-specific worked examples.
- **C-2** (L47–54): "MVK failed" is not "KnowledgeOS failed". The EEP was validated (transferred with zero translation; tactical principles rejected 5 candidates). The 15-file extraction boundary was invalidated.
- **C-3** (L56–71): what was tested was Knowledge→Architect→Software (n=1, FAIL on boundary). What was not tested was KnowledgeOS→PKS Generator→Software (n=0). It names three responsibilities: R-A bootstrap PKS (n=0), R-B bootstrap engineering (n=1), R-C bootstrap software (n=0). The "PKS Generator" slot is occupied "by a person reading documents".
- **Six candidates** (L79–92): Bootstrap/Genesis, Engineering Governance, Knowledge Validation, Runtime Integration, PKS Generation, Operational Learning. Verdict: "NO NEW STABLE STRATEGIC DOMAIN IS EVIDENCED" (two exist, two are subsystems or capability families, one is a workflow, one is an observation).
- **Two orthogonal dimensions** (L96–101): product ownership (stable) and architectural responsibility (internal, evolving).
- **Classification** (L105–111): KnowledgeOS is a PRODUCT ("potential product", gate unopened). PKS is a "GENERATED PRODUCT ARTIFACT — an instance, one per product", with "generated" at n=0. PublicDigit is a business product. Adapters are not products. Bootstrap, Capabilities, Governance, Validation and Learning are internal subsystems, "analogous to Spring Core/Beans/MVC".
- **Three architectural levels** (L113–116): repository, product, subsystem. Rule: promoting a subsystem to a repository root "encodes an INTERNAL DESIGN DECISION as a REPOSITORY-WIDE OWNERSHIP BOUNDARY".
- **Five-way classification matrix** (L124–131): bounded context, capability, supporting subsystem, workflow, observation.
- **Extraction-readiness table** (L137–154):
  - REUSABLE: EEP, DDD tactical principles, ES-005.2/005.3/004.2/004.3/003.3, CAP-001 core.
  - CANDIDATE: Round47-OP criteria; SD-2..SD-7.
  - PublicDigit-specific: SD-1; ES-005.1 "as written".
  - UNKNOWN: ES-005.4, deferred.
  - Case law: Integrity Model "~90% CASE LAW"; Review Method and ARB Discipline MIXED with named reusable rules; Methodology Baseline v1.2 "~95% CASE LAW", with only PMR-9 and PMR-10 portable.
  - Reference Architecture: "EXPERIMENTAL — DRAFT … contains the genesis blocker".
- **"Domain-free ≠ reusable", validated "in BOTH directions"** (L158–167). It names two blocker classes: **case-law dilution** and **coupling at the boundary**. "Neither is detectable by searching for domain vocabulary. EAD-1's zero-election-terms test found neither."
- **Five-arrow context map** (L173–209). Only "1 of 5 arrows evidenced". PKS→Product is "PARTIALLY EVIDENCED (protocol used, effect unmeasured)".
- **Promotion-evidence table** (L216–224): one operational event per candidate. Promotion follows ES-006.1's "2 independent occurrences" bar, and "n=1 admits a candidate, never a rule" (L133, L255).
- **Before/after table** (L228–235). It asserts "Method → a falsifiable one-session instrument".
- **Open questions** OQ-S1..S5 (L241–245). OQ-S2 notes that tactical DDD is platform-side and ADOPTED while strategic DDD is product-side. OQ-S4 asks whether the PKS Generator is a component or a role.
- **Recommendation** (L263): "The strategic model is stable. Stop consolidating it."

**Weaknesses and contradictions [I]**
1. **The quoted test is not the test applied.** The quoted rule promotes on "one or more" criteria. Yet Bootstrap is rejected with "one criterion, n=1" (L81). Knowledge Validation is rejected with "invariants only" (L83). Runtime is rejected with two criteria (L84). Operational Learning is rejected with one (L86). The author applies an unstated higher bar, so the claim "using the programme's own test is the only non-arbitrary way" (L77) is undermined.
2. **One thing gets three classifications.** Engineering Governance is a "Supporting Subdomain" (L82), an "INTERNAL SUBSYSTEM of KnowledgeOS" (L111), and "Supporting subsystem ✅" (L127). Subdomain and subsystem are conflated.
3. **Team autonomy is asserted without evidence.** It is claimed for Governance (L82), yet the sources elsewhere show a single sponsor, a single ARB and an individual owner.
4. **KnowledgeOS is classified "PRODUCT" (L107)** while canon, per S0035 Q1 and S0032, says a potential product only.
5. **PKS as "generated product artifact" contradicts n=0.** The source concedes that "generated" is n=0 (L108) yet keeps the label.
6. **The percentages have no method.** "~90% / ~95% case law" is unexplained. "VALIDATED in both directions" rests on a handful of documents.
7. **The MVK results are thin.** "EEP transferred with zero translation" is n=1, one session, and apparently judged by the same author. "Falsifiable instrument" is asserted.
8. **The recommendation undercuts itself.** "Stop consolidating" comes in a consolidation document, and a new domain model (S0034) was produced the same day.

---

## S0034 — `KnowledgeOS_Engineering_Knowledge_Domain_Model.md`

**Concepts and definitions**
- **Method** (L10): "Meaning first". No domain is derived from a folder, filename or markdown type. Candidates are validated against the nine Round47-OP criteria and graded Strong/Medium/Weak on the R16 Workbook scale. The scale's thresholds are never given.
- **Top annotation PD-3** (L12–18): the Engineering Knowledge Platform is a missing domain. `Knowledge-Constitution` is frozen and authoritative, with owner "nab.raj.sharma", a named individual. It has seven schemas, 18 enforced lint rules, a graph and a portal. Treating it as an ontology layer (L-A) was "a category error". It "scores 6 of 9 criteria, tying D-1 as the most strongly evidenced domain".
- **Top falsification annotation** (L20–33), with verdict "REVISE, then re-test. NOT canonical":
  - D-1: STRONG.
  - D-2: Strong→MEDIUM. Its ADR series has shared sponsor + ARB authority and was "authored in one day, retrospectively". It survives only on Round39-D6's scope exclusion.
  - D-3: Strong→WEAK. "No Capability Mapping artifact exists"; the mapping is "nearly the IDENTITY function", and "an ACL that performs an identity mapping is not an ACL".
  - D-4 rejection: STRONG.
  - D-6a: MEDIUM, 0 traversals.
  - D-7: WEAK. "PKS-as-projection FALSIFIED": the artifacts carry ACCEPTED status with an ARB score of 9.9/10, and "You do not endorse a projection; you regenerate it."
  - Required revisions: restate D-2 on scope exclusion only, and D-7 as "an observation of one instance".
- **Self-critique** (L35–41): the Landscape's artifact-kind classification is "filename thinking wearing a taxonomy". "Classification follows the domain model." An index keyed to today's arrangement freezes accidental structure.
- **D-1 Governance** (L49–59): five criteria, with append-only rulings and R-46 (plan approval ≠ execution authorization) as invariants. Verdict: bounded context.
- **D-2 Method** (L61–75): Round39-MC (MC-01..08), ADR-M, Integrity Validator, Baseline MB-39.1, the "void to the extent of the conflict" consistency rule, and its own UL. Called "PRINCIPAL DISCOVERY … most strongly evidenced boundary". "Two constitutions · two ADR series · two baselines · two vocabularies."
- **D-3 Runtime** (L77–86): UL divergence "deliberately engineered … a bounded context by construction", plus deployment autonomy and an ACL. Verdict: Generic Subdomain, n=1 runtime.
- **D-4 Workflow** (L88–97): rejected. It has no owner (the EEP sits in ES-002/D-1) and no UL of its own.
- **D-5 Capability** (L99–107): "a KNOWLEDGE KIND, not a bounded context". H-CAT-1 stands and the pattern is "provisionally stable at n=2 conceptual".
- **D-6 Evidence** (L109–121) splits into D-6a Evidence Protocol (platform bounded context, 0 traversals, "never executed") and D-6b Product Evidence (product records). The basis is canon's owner field, "the producing track; consumed by DA": "split ownership is a boundary, not an ambiguity."
- **D-7 PKS** (L123–131): a **context TYPE**. Instances are product-side bounded contexts. Conflating type and instance is said to be "what let PublicDigit's methodology end up inside its own PKS paths".
- **P1 layers** (L135–144): METHOD, BINDING, EVIDENCE per domain.
- **Relationship diagram and edge table** (L150–180). "D-1 authorizes D-2" is HYPOTHESIZED, because Round39-MC was adopted by sponsor authority (ADR-M-012). This is called "the model's most consequential open question".
- **Result** (L196): "FOUR platform-side bounded contexts. Not eight" (D-1, D-2, D-3, D-6a).
- **UL collisions** (L203–218), 12 terms. "Baseline" has five senses and is called CRITICAL. Constitution has 5+, Capability 3, Platform 4, Governance 3 (one product-side), Verdict has two colliding closed sets, among others. The source says these were "measured, not asserted", but no method is shown.
- **Folder-fit table** (L224–240):
  - D-2 lives in `docs/architecture/design`, a product path: "largest misfit".
  - D-6b is "INVERTED": product records inside `engineering/`.
  - Claim: the ownership question finds what a vocabulary search cannot, which is "the demonstration that the model had to precede the index".
- **Recommendations** R-1..R-8 (L246–253). R-5 says to index "by DOMAIN and by P1 layer, never by artifact kind".

**Contradictions, internal and annotation versus body**
1. **The body was never revised after the falsification annotation.** It still reads D-2 "STRONG … most strongly evidenced" (L61, L74), D-3 "STRONG" (L77), "FOUR platform-side bounded contexts" (L196, L269), and closes on D-2 as the headline (L263). The annotation demands revision and declares the document "NOT canonical".
2. **PD-3's ranking does not match the body.** PD-3 "6 of 9, tying D-1" conflicts with D-1's "Five criteria" (L59, L186). It also conflicts with D-2 being "the most strongly evidenced" (L74).
3. **The "four of eight" survivors are not the four bounded contexts.** The traceability line (L274) implies the survivors as offered are D-1, D-2, D-3 and Product Domain Knowledge. The "four platform-side bounded contexts" are D-1, D-2, D-3 and D-6a. Two different "fours" are easy to conflate. [I]
4. **D-6a is a bounded context with zero executions** (L121, L189). S0033 would call the same thing an "existing, non-functioning mechanism" (Operational Learning, L86 and L131), not a context.
5. **The method claim conflicts with how D-6 was split.** "No domain from folder or filename" sits beside a D-6 split derived from one metadata owner field. The scope exclusion is a document's declared scope, which is weak boundary evidence. [I]
6. **D-2 is heterogeneous.** Its METHOD column (L138) mixes Round39 methodology (MC-01..08), Round47 strategic-DDD criteria and the R16 workbook. S0033 OQ-S2 treats the strategic-DDD method as a separate product-side asset. [I]
7. **Causal claims rest on one case.** The type/instance causal claim (L131) and the claim that the model "had to precede the index" (L238) are unsupported. [I]
8. **The "measured" UL counts have no procedure.** [I]

---

## S0035 — `2026-08-02-knowledgeos-strategic-domain-discovery.md`

**Concepts and definitions**
- **Method** (L9): trace from committed artifacts only, and record absence rather than fill it.
- **Litmus** (L10): "Would this responsibility still exist if PublicDigit disappeared?" It is equated with ES-005.3 (I-2).
- **Outcome 1** (L16–20): sufficient for the present maturity level. "No new strategic bounded context". Six questions are answered in canon, one is under STOP, and one is answered by a standard.
- **Brainstorming premise versus DA** (L24–32): "real product is KnowledgeOS" and "KnowledgeOS is now your Core Domain" contradict AIP-14 ("solely to improve delivery of PublicDigit") and the DA clarification of 2026-07-27 ("Election System is the Core Domain; platform a Supporting Subdomain"). The gate trigger is a second adopting product; there is one product.
- **Recurring pattern** (L34–44): five occurrences of a hypothesis that something is missing which canon already answers. Failure mode: "proposing before searching".
- **Evidence list** (L48–64).
- **24 observations** O-1..O-24 (L68–93). Key ones:
  - O-1..O-4: Reference Model §8 "Platform ≠ product". KnowledgeOS is a "potential product"; the conditional inversion means "AIP-14 does not transfer unchanged"; "the product binds the platform".
  - O-7: ES-005.3. O-8: ES-005.4.
  - O-9: the §7.2 promotion model, "itself a candidate for promotion".
  - O-10: §7.3 canonical-ownership-before-creation, CANDIDATE.
  - O-11..O-15: six clusters, "observations, not bounded-context claims"; seven candidate aggregates; the standing STOP on context maps; "the strategic relationship is the conclusion, not the starting point"; naming drift.
  - O-16..O-18: the charter's five gated stages, PROPOSED; "placeholder" name; "one retrospective data point, zero market data points".
  - O-19, O-20: three roots; cross-product research PENDING.
  - O-21: CAP-001 grep found one hit.
  - O-22: H-CAT-1. O-23: CAP-004 and CAP-006 already realized under R-36. O-24: zero executions.
- **12 interpretations** I-1..I-12, each with a confidence (L97–110).
- **Six clusters tested by the litmus** (L118–125). Only Evidence & Learning splits, into mechanism and records.
- **Q1–Q8 answers** (L140–149):
  - Q1: Core Domain "does not yet arise".
  - Q3: PKS is "A domain … containing bounded contexts — not itself a bounded context" (AD-1).
  - Q4: the lifecycle exists as §7.2 + ES-006.1, and a new one would violate ES-005.4.
  - Q7: ES-005.3 is the reuse boundary.
  - Q8: runtimes are adapters.
- **"MISSING: nothing at the strategic level"** (L151).
- **No context map** (L156–178): STOP. Only the three-concern boundary is shown, and no relationship pattern is assigned.
- **Closing definition** (L225): "a potential product, not yet gated, whose reusable core is … separated by ES-005.1 and bounded by ES-005.3, and whose promotion mechanism is ES-006.1."

**Weaknesses [I]**
1. **I-2's "≡" is invalid.** "Could a different project adopt the document unchanged?" tests a document. "Would the responsibility exist without PublicDigit?" tests a responsibility. A responsibility can survive while its document cannot. S0033 shows exactly this with ES-005.1 ("PublicDigit-specific as written") and SD-1.
2. **"Already exists" is overstated.** Q4 says the lifecycle "ALREADY EXISTS" when O-9 records it as a candidate. ES-006.1 is PROPOSED (S0031 L54, G-C6), yet it is treated as normative ("[ES-006.1 normative]", O-9) and used as the authority for promotion bars throughout S0033 and S0035.
3. **O-21 is labelled an observation but carries an interpretation.** Its result (no matches, one comment hit) is interpreted elsewhere as "zero repository knowledge".
4. **The recommendations are out of order.** Rec 9 appears before rec 8 (L216–217), which indicates a later insertion pointing to S0031.
5. **"Nothing missing at the strategic level" (L151) is contradicted the same day.** S0034 finds a distinct D-2 context and a "missing domain" PD-3. S0033 finds three bootstrap responsibilities.
6. **Rec 1 and rec 6 are ignored by the companions.** Rec 1 ("do not restate §8 elsewhere") and rec 6 ("every architectural document before the trigger is Stage-5 work at Stage 0") apply directly to S0031, S0033 and S0034, which ignore them.

---

## Cross-source contradictions

1. **What PKS is gets five different answers.**
   - S0031 L8: an instance, not a product.
   - S0033 L108: a generated product artifact.
   - S0035 Q3 L144: a domain containing bounded contexts, not itself a bounded context.
   - S0034 L127–131: a context type whose instances *are* bounded contexts.
   - S0034 L31: an "observation of one instance"; PKS-as-projection falsified.
   - S0032 L160: one PKS, authored, not generated.
2. **Runtime has four incompatible statuses.**
   - S0031 L104 and M-13: "ALREADY FULLY MODELLED", with Capability Mapping "inside the Runtime Adapter Record".
   - S0034 body: a bounded context and Generic Subdomain.
   - S0033: not a domain, a supporting subsystem + ACL.
   - S0034 annotation: no Capability Mapping artifact exists, and an identity-mapping ACL "is not an ACL".
3. **Context maps are drawn despite the STOP.** S0035 refuses to draw one under the standing STOP (O-13). The same day, S0033 §5 draws a "Strategic Context Map" and S0034 §3 draws a domain-relationship graph. This holds unless the STOP covers only the Governance-domain discovery ("drawn from *this*"), a scope ambiguity none of the sources resolves.
4. **Loop arrow status is inconsistent.** S0031 has four arrows with PKS→PublicDigit SPECULATIVE. S0033 has five arrows with the same edge "PARTIALLY EVIDENCED".
5. **KnowledgeOS's status is inconsistent.** S0033 L107 calls it a PRODUCT. S0035 Q1 and S0032 Correction 1 say it is a potential product only, and that calling it the product contradicts AIP-14. S0031 accepts the vision as destination "not evaluated", which S0035 L24 says contradicts a DA ruling.
6. **Mission versus AIP-14.** S0032's reuse-oriented mission conflicts with AIP-14's "solely … PublicDigit". S0032 treats the conflict as a horizon issue but grades the mission OBSERVED.
7. **Domain-free versus reusable.** S0031 assigns M-6 and M-7 to KnowledgeOS because they are domain-free. S0033 says the same corpus is ~90–95% case law, and that domain-freeness does not imply reusability.
8. **Standards readiness.** S0031 M-1 says ES-001..006 are "already correct" in KnowledgeOS. S0033 says ES-005.1 is "PublicDigit-specific as written" and ES-005.4 is "UNKNOWN, cannot be cited". S0031's "kernel declares reusability" (L67) faces S0033's "contains the genesis blocker" (L152).
9. **Enforcement.** S0032's "no capability or check enforces" faces S0034's "18 enforced lint rules".
10. **Counts of the recurring self-error disagree.** S0032 has three absence errors and "9 rediscoveries, refuted at n=10". S0035 has five occurrences of proposing before searching. These are related phenomena but the numbers are inconsistent and the categories unreconciled.
11. **Strategic method placement.** S0034 puts the Round47 criteria inside D-2, a platform context. S0033 OQ-S2 says strategic DDD is product-side while tactical DDD is platform-side.
12. **Operational learning / evidence protocol has three classifications.** S0035 calls it a cluster "split". S0033 calls it "NOT a domain — existing mechanism". S0034 calls it "D-6a, a bounded context (never executed)".
13. **Engineering ownership.** S0032 gives it to sponsor + ARB plus "an individual". S0034 PD-3 uses that individual ownership as the decisive evidence for a separate domain.

## Cross-cutting judgments [I]

- **The reasoning is circular.** All five sources treat repository "canon" (often PROPOSED, DRAFT or CANDIDATE, and authored inside the same programme) as authority for its own sufficiency. "Already exists in canon" repeatedly substitutes for "has been shown adequate".
- **Repository-internal counts are evidence; the thesis-level conclusions are not.** The counts (1,532, 106, 448, 92) and the zero-traversal / zero-execution findings are the most solid material. The thesis-level conclusions all rest on n=0 or n=1: the strategic model is stable, PKS is a type, D-2 is distinct, the mission is enacted.
- **The strongest durable theoretical content:**
  - artifact absence ≠ concept absence;
  - constraint ≠ mission ("a control ships with a metric and a trigger");
  - domain-free ≠ reusable, with its two blocker classes;
  - ownership versus architectural responsibility as orthogonal axes, plus the three-level rule;
  - type versus instance;
  - protocol versus records;
  - bootstrap split into R-A, R-B and R-C, with the generator slot filled by a human (component or role);
  - reference versus copy extraction;
  - the identity-mapping-is-not-an-ACL test;
  - the overloaded-dimension test for evidence grades;
  - conceptual versus chronological order.
- **The weakest material:**
  - the mission sentence graded OBSERVED;
  - the criteria test applied inconsistently with its own one-or-more threshold;
  - unmeasured percentages and UL counts;
  - un-retracted bodies beneath falsifying annotations (S0032, S0034);
  - the self-refuting "last document" and "stop consolidating" closings.
