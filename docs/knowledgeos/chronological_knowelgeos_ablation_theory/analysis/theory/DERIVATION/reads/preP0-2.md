# Batch preP0-2 — primary-source read

Batch list: `docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/DERIVATION/batches/preP0-2.tsv` (16 files). All 16 were read in full, in list order, with the Read tool. No file was unreadable.

General date note [INT]: every file's only internal date is in its `Commission` row (2026-08-02). Several files carry later annotations ("ANNOTATED 2026-08-02", "CORRECTED 2026-08-02") that cite documents later in this batch, or outside it. The documents were therefore revised after first drafting, on the same day. The list order (S-ids) is not a reliable internal writing order. The cross-references indicate the drafting order inside the day.

---

### S0035 · docs/knowledgeos/2026-08-02-knowledgeos-strategic-domain-discovery.md · 2026-08-02 (filename)

- **Date check:** L7 "PA / Principal Knowledge Engineer / Chief Software Architect, 2026-08-02"; L18 "Wording corrected by the PA, 2026-08-02". This agrees with the filename date. No conflict.
- **Read depth:** complete (L1–234).
- **What it is:** strategic discovery traced from canon. It answers commission Q1–Q8 and concludes that no new strategic context is evidenced.
- **Key claims:**
  - "THE CURRENT STRATEGIC MODEL IS SUFFICIENT *FOR THE PRESENT MATURITY LEVEL*" (L16). This was corrected from plain "sufficient": "KnowledgeOS has reached its *currently validated* model, not its *mature* one" (L18).
  - "THE BRAINSTORMING PREMISE CONTRADICTS A DECISION-AUTHORITY RULING" (L24). It cites AIP-14: "the platform exists **solely to improve delivery of PublicDigit**" (L28).
  - "The gate has not opened. The recorded trigger is *a second real adopting product*. There is one." (L32)
  - Pattern: "Four searches for a missing decision; four decisions already on the record. The consistent failure mode is mine and procedural — proposing before searching." (L44), with "Five, now."
  - ES-005.3's litmus ≡ the commission's litmus (I-2, L100).
  - Evidence & Learning splits: "The *protocol* for gathering evidence is reusable; the *evidence* is the producing track's" (L128).
  - The context map is not delivered because of a standing STOP: "no context map may be drawn from this until operational evidence (C3 + pilot) exists and the ARB rules" (L82, L158).
  - Closing: "a potential product, not yet gated, whose reusable core is already separated … by ES-005.1 and bounded by ES-005.3, and whose promotion mechanism is already ES-006.1" (L225).
- **Concepts:**
  - Observation / Interpretation / Candidate / Supported-Conclusion claim tiers (§3–§6; L231 "claims separated as Observation … / Interpretation … / Candidate … / Supported Conclusion"). Method device; adopted within the doc.
  - "proposing before searching" failure mode (L44). Recorded pattern, count 5.
  - Litmus "Would this responsibility still exist if PublicDigit disappeared…" (L10). Identified with existing ES-005.3.
  - Platform ≠ product (Reference Model §8, O-1..O-4, L70–73). Cited as existing canon.
  - Promotion model `Observation → repeated evidence → candidate → … → standard` (O-9, L78). Cited as an existing candidate.
- **Relations and structure:** a three-layer boundary diagram, PRODUCT / ENGINEERING PLATFORM / RUNTIME MOUNT (L164–176). "the product BINDS the platform · never FORKS it · the platform never ABSORBS product concerns" (L174–175). Six concept clusters tested against the litmus (L118–125). Explicitly "not Customer/Supplier, ACL, Shared Kernel or Published Language assignments" (L178).
- **Formal content:** none. The litmus is the only rule-like test.
- **Corrections and contradictions:**
  - It refutes the three brainstorming documents `architecture_legacy/ai_architecture/pks/20260802_0928–0930_*.md` (L64). Their claim "KnowledgeOS is now your Core Domain" (L26) contradicts AIP-14.
  - The PA corrected its own wording (L18).
  - It points to the companion `2026-08-02-knowledgeos-architecture-consolidation.md` (L216). That file is not in this batch.
- **Open questions:** OQ-K1–K6 (L184–189): the ES-005.3 research clause; where cross-product research lives (`PENDING`, L89); whether the gate has opened; charter approval; AIP-14 over-evolution exposure; the working name.

---

### S0025 · docs/knowledgeos/2026-08-02-mvk-bootstrap-validation-report.md · 2026-08-02 (filename)

- **Date check:** L7 "KnowledgeOS Platform Validation, 2026-08-02"; L16 "SCOPE CORRECTION (PA, 2026-08-02)"; L71 and L116 "REFUTED 2026-08-02". This agrees with the filename. The F-3 annotation (L116) cites `2026-08-02-knowledgeos-strategic-boundary-consolidation.md`, which is a later same-day document.
- **Read depth:** complete (L1–197).
- **What it is:** an experiment report. An isolated session tried to bootstrap a foreign product ("StockRoom") using only the 15-file MVK.
- **Key claims:**
  - "VERDICT: **FAIL** — the MVK is insufficient to bootstrap a product" (L14). Scope-corrected: "This `FAIL` invalidated the EXTRACTION BOUNDARY. It did NOT invalidate the platform." (L18)
  - The EEP "transferred with zero translation"; the tactical principles "rejected five candidates in a foreign domain" (L22).
  - Reach #13: "Left to the MVK alone I would have begun at 'Idea → Implementation Plan' and never been told to model a context first" (L77). "The true state is worse than FAIL suggests" (L80).
  - "**Domain-free is not the same as portable**" (L97).
  - F-1: EEP unsatisfiable at bootstrap; F-2: "There is no genesis path"; F-3: strategic-DDD method later "RESTATED: the method EXISTS but is product-coupled" (L114–116).
  - Meta-finding: "A platform that governs CHANGE does not automatically govern GENESIS — and the gap is invisible from inside a mature project, because genesis happened before the governance existed." (L120)
  - "A Bootstrap Engine would have automated a process that does not yet exist." (L155)
  - "The platform's reusability claim is no longer an assertion. It is a measurement — and the measurement says *not yet*." (L190)
- **Concepts:**
  - MVK (Minimum Viable Kernel [INT: expansion not given in text]), the 15-file set, 244 KB (L40). Status: invalidated as an extraction boundary.
  - GENESIS (gap) (L120, L139). Introduced as a finding.
  - Contamination / out-of-boundary register (L62–77). A method device.
  - Bootstrap instrument: "a falsifiable, repeatable, one-session instrument for testing reusability" (L188).
  - Additions A–G, proposed as candidates "to EXISTING home[s]" (L133–145). For example, A, the genesis clause: "at day zero the stakeholder's stated need is admissible evidence; the first decision is *Provisional/Proposed*" (L139). F is the platform-acquisition rule (L144). Status: candidate.
  - SD-1 coupling: it "admits *only* PublicDigit's Certified Domain Knowledge Release" (L116).
- **Relations and structure:** a per-scope verdict table (L101–108), PASS / WARN / INCONCLUSIVE / FAIL. Transferred-element table (L84–94). The architecture components Kernel, Capability SDK, Bootstrap Engine, PKS Generator, Validation Engine and Extension Model "remain HYPOTHESES" (L153).
- **Formal content:** none. Invariants for the toy domain (L54) are product examples.
- **Corrections and contradictions:**
  - It refutes §6 of the Product Boundary Discovery: "REFUTED IN PART — the 3 methodology documents are 73% by volume and mostly non-portable case law" (L163; also L96).
  - AR-1 is "REALIZED" (L27, L164).
  - It self-corrects reach #2: the template exists at `Round17_ARB_Decision_Record_Template.md` (L71). F-3 is refuted (L116).
- **Open questions:** OQ-S1 (L140, "is SD-1 a product binding or a platform rule?"). Whether F-1 dissolves for an organization, which would give WARN (L129). L-2 "generate a PKS" is still a hypothesis (L165).

---

### S0024 · docs/knowledgeos/KnowledgeOS_Architecture_Fitness_Assessment.md · 2026-08-02 (internal-2026)

- **Date check:** L7 "…Senior Knowledge Engineer's Addendum, 2026-08-02"; L12 "CORRECTION APPENDED 2026-08-02"; L70 "CORRECTED 2026-08-02". No conflict. The corrections cite the later `KnowledgeOS_Platform_Capability_Model.md` (L12) and `KnowledgeOS_Operational_Knowledge_Principles.md` (L82).
- **Read depth:** complete (L1–211).
- **What it is:** a fitness assessment against the fitness function "Does today's architecture support `knowledgeos init`?" (L10).
- **Key claims:**
  - Three gap kinds: ARCHITECTURAL (1), GOVERNANCE (1), EVIDENCE (4) (L18–24). "*the gap is not architecture, it's evidence*" is CONFIRMED (L24).
  - "ARCHITECTURALLY: ALMOST … EVIDENTIALLY: NO — and `knowledgeos init` is PREMATURE" (L30–31).
  - P3: "a responsibility becomes a component only at **n ≥ 2** with a repeatable pattern extracted" (L38).
  - "MATURITY ≠ ENFORCEMENT … *the platform is adoptable by habit, never by mechanism*" (L84–88). Later corrected (L70–80).
  - Corrected: "enforcement lives in the RUNTIME ADAPTER; capabilities live in the PLATFORM. Enforcement exists exactly where governance does not, and is absent exactly where it does." (L80)
  - "The architecture is not underdesigned. It is under-exercised — and nowhere enforcing." (L202)
  - "BRM-1 retired extraction-by-COPY. It did not retire extraction-by-DECOMPOSITION" (L185). "decomposition *followed by* materialization is still barred" (L189).
- **Concepts:**
  - Five-dimension responsibility matrix METHOD / BINDING / EVIDENCE / IMPLEMENTATION, plus ENFORCEMENT, which "this assessment adds" (L44). Maturity levels L0–L4 (L46–58; L208 "L0–L4 applied").
  - Gap kinds architectural / governance / evidence (L18–22). New.
  - BOUNDARY / TRIGGER / ADVISORY (L74–78). Imported from S0043.
  - Tier-1 kernel = "domain-free ∧ binding-free ∧ evidence-free" (L137).
  - P1 (method/binding/evidence) and P2, P3 are referenced as prior principles (L38, L143, L191).
- **Relations and structure:** 11 responsibilities (L46–58). Lists of "never products" (L126–133), kernel (L139), generated PKS (L145) and business product (L149). Evidence gaps E-1..E-3 (L177–181).
- **Formal content:** the Tier-1 conjunction "domain-free ∧ binding-free ∧ evidence-free" (L137); the threshold rule n ≥ 2 (L38). Nothing else.
- **Corrections and contradictions:**
  - It corrects the Addendum in three places: SD-1 was misattributed to Product Discovery; ES-005.1 binding; Runtime Adapter "L1, not L3" (L94–98).
  - Its own Row 2 evidence is corrected: "Strategic Discovery is not n=1" (L14, L49).
  - "0 of 11 ENFORCING" is "TOO STRONG" (L70).
  - It recommends retiring the term "Capability Runtime" (L100).
- **Open questions:** OQ-S1, the SD-1 ruling (L167); OQ-S3, whether genesis is the platform's at all (L120, L163); OQ-S4, PKS generation (L119); charter Stage 1 approval (L40).

---

### S0034 · docs/knowledgeos/KnowledgeOS_Engineering_Knowledge_Domain_Model.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "…ARB Chair's Addendum, 2026-08-02"; L12 "found 2026-08-02"; L20 "FALSIFICATION TESTED 2026-08-02". Its banners cite the later `KnowledgeOS_Strategic_Architecture_Discovery.md` (L18) and `KnowledgeOS_Domain_Model_Falsification_Report.md` (L22), which is not in the batch.
- **Read depth:** complete (L1–277).
- **What it is:** a strategic knowledge-domain model. It validates candidates D-1..D-7 against `Round47-OP`'s nine criteria.
- **Key claims:**
  - "*Classification follows the domain model. The Landscape found the symptom; it could not supply the ontology.*" (L39)
  - D-2, Engineering Method, is distinct from D-1, Governance: "Two constitutions · two ADR series · two baselines · two vocabularies" (L75). This is later downgraded to MEDIUM (L27).
  - D-6 Evidence splits: "The boundary runs THROUGH the concept, not around it" (L111), into D-6a Protocol and D-6b Records (L120).
  - D-7 PKS is "a **context type**" (L128).
  - "FOUR platform-side bounded contexts. Not eight." (L196)
  - "`Baseline` with five senses is the most severe unrecorded collision in the repository" (L218).
  - "the model had to precede the index" (L238).
- **Concepts:**
  - D-1 Engineering Governance: bounded context, STRONG (L59).
  - D-2 Engineering Method: bounded context (L74). After falsification it is MEDIUM (L27).
  - D-3 Engineering Runtime: bounded context (L86). After falsification it is WEAK, with "the mapping is nearly the IDENTITY function, and an ACL that performs an identity mapping is not an ACL" (L28).
  - D-4 Workflow: REJECTED (L96).
  - D-5 Capability: "a KNOWLEDGE KIND with a governing pattern — NOT a bounded context" (L106).
  - D-6a / D-6b (L120).
  - D-7 "context TYPE" (L131). After falsification it is WEAK, and "PKS-as-projection FALSIFIED for existing artifacts" (L31).
  - PD-3 EKP is flagged as missing from the model: "a category error" (L16).
  - Status: CANDIDATE — NOT ADOPTED (L6); later "REVISE, then re-test. NOT canonical" (L22).
- **Relations and structure:** a domain graph with verbs authorizes / produces / instantiates / isolates(ACL) / observed by / harvests (L150–170). The edge D-1→D-2 is "HYPOTHESIZED" (L174). UL collision table, 12 terms (L203–216). Folder-fit table (L224–232).
- **Formal content:** none. Criteria grading Strong/Medium/Weak is from R16 (L10).
- **Corrections and contradictions:**
  - It accepts a critique of the Landscape (S0038): "That is filename thinking wearing a taxonomy" (L37).
  - It is itself falsified in part (L20–33).
  - It is corrected by S0037 for missing PD-3 (L12–18).
- **Open questions:** R-1, D-1↔D-2 authority (L246); R-2, the Baseline collision (L247); R-4, H-CAT-1 (L249); co-location of the four contexts, which needs R-37 lifted (L240).

---

### S0038 · docs/knowledgeos/KnowledgeOS_Engineering_Knowledge_Landscape.md · 2026-08-02 (internal-2026)

- **Date check:** L7 "…ARB Chair's Addendum, 2026-08-02"; L11 "ROLE CLARIFIED 2026-08-02". No conflict. L21 references "occurrence #10", which is recorded in later documents.
- **Read depth:** complete (L1–256).
- **What it is:** a knowledge inventory made by repository sweep (30 asset families). It turns into a measurement of rediscovery.
- **Key claims:**
  - "The count rises from 6 to 9. This document's most important content is what it found against itself." (L25)
  - Rediscoveries: P1 ≈ `Round38C-04` Principle/Form; the Capability Model ≈ `Platform_Capability_Pattern`; the requested taxonomy ≈ `RQ-002` (L33–35).
  - "The repository does not LACK a knowledge taxonomy. It has at least THREE" (L47). "The failure mode is not a gap. It is unreachability that keeps REGENERATING the same knowledge." (L55)
  - RQ-002: `Knowledge instance = NATURE × REPRESENTATION × GOVERNANCE-STATUS ( × AUTHORITY-SCOPE )` (L35).
  - "Four of nine suspected gaps were not gaps" (L175). GENESIS survives the sweep (L165).
  - Lifecycle inferred, with the new stages CHALLENGE and SATURATION (L181–192).
  - "NOT missing knowledge. UNREACHABLE knowledge that keeps regenerating itself." (L221)
  - "A platform that must rediscover its own methods has not yet been engineered — it has only been accumulated." (L248)
- **Concepts:**
  - Asset classes: Engineering Method, Governance Rule, Discovery/Decision/Review/Assessment/Verification Instrument, Capability Specification, Platform/Runtime Pattern, Product Case Law (L96–109). The text later recognises this as a flat taxonomy (L201).
  - Form vs Content decomposition: FORM / METHOD / product CONTENT / product EVIDENCE (L113).
  - Duplication kinds: healthy / historical evolution / accidental (L153–157). New.
  - Index of engineering knowledge, "keyed to the EXISTING taxonomies, inventing none" (L225). Recommended.
  - Discoverability estimate "25–35 %" (L211).
- **Relations and structure:** asset relationship diagram (L130–147). K-19 → everything and K-18 → K-20 are marked "HYPOTHESIZED and load-bearing" (L149).
- **Formal content:** the RQ-002 product formula, quoted (L35, L200). It is cited, not authored here.
- **Corrections and contradictions:**
  - It refutes its own product, the Platform Capability Model (S0036), and P1 (L33–41).
  - Its own §10 correlation is later "REFUTED: … Occurrence #10 drew on an INDEXED region" (L21). "The cause is not only a missing index — it is also not reading the index that exists."
  - Its role was reframed as "OBSERVATIONAL EVIDENCE" (L13). Its flat classification is "not yet semantically grounded" (L17).
- **Open questions:** reconciling with the AKB layers (L202); an ARB ruling on K-19 (L52); `check_roles.php` is unclassified (L109).

---

### S0018 · docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "…Enterprise Software Architect, 2026-08-02". No conflict. It concedes LG-1 from S0012 (L16), so it postdates S0012.
- **Read depth:** complete (L1–158).
- **What it is:** pure discovery of the independent dimensions of knowledge evolution, across 10 progression mechanisms.
- **Key claims:**
  - "LG-1 was solution design, conceded … an inference from a missing implementation to a model — **exactly what DDD forbids.**" (L16)
  - "The mechanisms are NOT ten instances of 'lifecycle.' They are four different KINDS of change" (L57).
  - "a GOVERNANCE-ACT progression can be granted. An EVIDENTIAL progression can only be earned." (L67)
  - `authorities.yaml` asks "*how much should I trust this* ***and*** *where did it come from?*" (L72–73).
  - "Authority = an immutable PROVENANCE fact × a mutable STANDING judgment. Attestation changes standing. Nothing changes provenance." (L94)
  - "evidence EARNS; governance GRANTS; promotion requires BOTH" (L105, L146).
  - "The repository does not have ten lifecycles. It has four kinds of change and one immutable fact, wearing ten mechanisms." (L150)
- **Concepts:**
  - Progression kinds: GOVERNANCE-ACT, EVIDENTIAL, WORK-EXECUTION, COMPOSITE, and NON-PROGRESSION: PROVENANCE (L61–65). New. Status: candidate (L6).
  - PROVENANCE (`generated`, `derived`) vs STANDING (`authoritative`, `provisional`, `historical`) (L77–83). New split.
  - Mechanisms P-1..P-10 (L27–36).
  - Prior canon cited: GEP-F1, "two lifecycles, never conflated" (L38).
- **Relations and structure:** seven-question table per mechanism (L42–53). Interactions: status ⟂ authority; ADR-status ⟂ maturity; coupling "by guard, not by fusion" (L104); P-3/P-6 consume P-10 (L105).
- **Formal content:** the semi-formal decomposition "Authority = PROVENANCE × STANDING" (L94); the orthogonality declarations (L102–103); "P-10 … monotonic counter" (L53).
- **Corrections and contradictions:**
  - It withdraws LG-1 (S0012) as ill-posed (L91, L129).
  - It confirms the reviewer's point "that chain is QUALIFICATION, not authority" (L17).
  - It explains the `I-4` confusion: "`derived` (provenance) was read as if it entailed low standing" (L96).
- **Open questions:** PM-1, separating provenance and standing (L129); PM-2, role/guard treatment for standing changes (L130); PM-3 to PM-5 (L131–133); LG-5 `SPECIALIZES` (L134).

---

### S0015 · docs/knowledgeos/KnowledgeOS_Independent_Review_Phase_B.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "Phase B of the PA roadmap, 2026-08-02/03"; L134 "executed 2026-08-02/03". ⚠ **Conflict flag:** the text states execution spanning 2026-08-02/03, while the listed date is 2026-08-02. Writing may have been completed on 2026-08-03.
- **Read depth:** complete (L1–137).
- **What it is:** a review record and comparison. An isolated, blinded first-principles review was compared with the track's conclusions.
- **Key claims:**
  - Reviewer verdict "CONTINUE DISCOVERY". The internal platform alone would earn "Adopt with Reservations" (L11).
  - "12 conclusions reproduced blind · 4 sharpened · 3 corrected" (L134; also L83–85).
  - "designed-for-reuse ≠ demonstrated-reusable" is a verbatim convergence (L29).
  - D-1: "'0 traversals' is FALSIFIED as we stated it". The back-edge is "DEMONSTRATED (n≈3)": R-36, R-41/ES-004.3, R-63 (L39–46).
  - D-2, occurrence #11: the frozen `Phase-02-Domain-Model.md` six-context map (BC-1..BC-6) was "NEVER CONSULTED" (L50).
  - D-4: the EKP consumption model is "FALSIFIED by E-1" (L62).
  - "separation of duties is real as PROCESS and unevidenced as PEOPLE" (L30).
- **Concepts:**
  - Phase A freeze: "the discovery corpus is FROZEN as historical engineering evidence … factual corrections only" (L93). Recorded as a PA directive.
  - "Doubly-derived conclusions" as Phase C's base (L101).
  - Session-simulated role separation (L30, L106). A new open item.
  - Register-completeness rule: "authorizations exist only if minted" (L104). Proposed.
- **Relations and structure:** Phase A/B/C roadmap (L93–106). Convergence/divergence scoreboard (L79–87).
- **Formal content:** none.
- **Corrections and contradictions:**
  - It corrects the "0 traversals" claim made in six documents: S0019, S0016, S0036, S0034, S0037 and S0043 in this batch [INT: the six are not named in the text]. L43: "asserted in at least six documents". L46: "'The flywheel has never turned' must be retired".
  - It downgrades I-8 in S0021: "'governance precedes automation' is **deliberately unnumbered**… Our I-8 over-granted it" (L34).
  - It flags the reviewer's misattributed prompt quote "PublicDigit is only the first laboratory" (L14).
- **Open questions:** IR-1 to IR-5 (L112–116): minting CAP-001's authorization; disposing of E-1; amending the Phase-02 map via AIP-13; acknowledging session-simulated SoD; retiring "0 traversals".

---

### S0012 · docs/knowledgeos/KnowledgeOS_Lifecycle_Gap_Analysis.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "…Enterprise Software Architect, 2026-08-02"; L152 "WITHDRAWN 2026-08-02". No conflict. The LG-1 withdrawal annotation cites S0018 (L152), which was written later.
- **Read depth:** complete (L1–181).
- **What it is:** a gap analysis that classifies the cross-product failures as ontology, lifecycle or governance.
- **Key claims:**
  - "`I-4` is NOT falsified. I misdiagnosed a LIFECYCLE gap as an ONTOLOGY falsification." (L14)
  - "Governance created the authority, not the derivation … **'DP-2 conflates DERIVATION with ATTESTATION'**" (L22).
  - Chain: `DERIVED → REVIEWED → ATTESTED → RELEASED` (L19).
  - "STATUS HAS A GOVERNED STATE MACHINE. AUTHORITY DOES NOT." (L76)
  - "ONLY ONE OF THE FOUR IS GENUINELY ONTOLOGICAL." (L108)
  - G-4 dissolves: "a deployment IS a Knowledge Space … Knowledge Spaces NEST" (L93, L96).
  - `lifecycle.md` shows `idea → research → …` as statuses, while `statuses.yaml` holds them as `knowledge_type` values. This is a "type/status conflation" (L118–123).
- **Concepts:**
  - Gap categories Structural / Behavioral / Evolutionary (L86); gap domains LIFECYCLE / ONTOLOGY / GOVERNANCE (L101–106). New.
  - Level confusions catalogue: flat vs dimensional, artifact vs concept, layer vs sibling, lifecycle vs structure (L26–29).
  - Nine existing lifecycles (L50–61).
- **Relations and structure:** the asymmetry table between `statuses.yaml` and `authorities.yaml` (L67–74). Healthy and possible-duplicate lifecycle pairs (L129–134).
- **Formal content:** none beyond the state lists.
- **Corrections and contradictions:**
  - It withdraws the I-4 falsification in S0022 (L14, L145).
  - It resolves H-3 from S0021 (L158).
  - It is itself corrected by S0018: LG-1 withdrawn (L152).
- **Open questions:** LG-2 to LG-6 (L153–157). In particular: DP-2 restatement; the `idea`/`research` canonicity; two axes versus two names; `SPECIALIZES`; where obligation attaches.

---

### S0016 · docs/knowledgeos/KnowledgeOS_Meta_Model.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "…Strategic DDD Architect, 2026-08-02"; L23 "ANNOTATED 2026-08-02". The annotation cites S0021 (L25), which was written later.
- **Read depth:** complete (L1–178).
- **What it is:** a canonical meta-model derived from RQ-002. It sets out six dimensions and six concepts.
- **Key claims:**
  - "`INTENDED` is WITHDRAWN as a fourth evidence grade … **Intent is not evidence.**" (L16)
  - "*Mission is ENACTED*" replaces "*mission is implicit*" (L17).
  - INTENT-STATUS is rejected: "`INTENT = (GOVERNANCE-STATUS × OWNER)`" (L55).
  - "`ENGINEERING KNOWLEDGE` ≠ `ENGINEERING ARTIFACT`. One is the thing; the other is a carrier of it." (L88)
  - "A meta-model earns its place by making a known error class impossible to phrase." (L98)
  - Test finding: "`Round39-MC` is `frozen` yet `ENFORCED`, while `CAP-001` is `realized` yet only `advisory`" (L134). This is given as the orthogonality proof (L135).
  - "Documents are no longer the centre of the model. They are the bottom of it." (L108)
- **Concepts:**
  - Dimensions D-I NATURE, D-II REPRESENTATION, D-III GOVERNANCE-STATUS and D-IV AUTHORITY-SCOPE exist, from RQ-002. D-V EVIDENCE-STATUS (*observed · inferred · hypothesized*) is NEW. D-VI OPERATIONAL-STATE (*enforced · advisory · unexercised*) is NEW (L41–46). INTENT-STATUS is REJECTED (L47).
  - Six concepts: MISSION, ENGINEERING KNOWLEDGE, ENGINEERING CAPABILITY ("protect exactly ONE invariant"), ENGINEERING ARTIFACT ("CARRY knowledge in a representation — it is never the knowledge"), PRODUCT KNOWLEDGE SPACE, PRODUCT (L79–84).
  - TRUST/PROVENANCE named as a fifth concern (L119).
  - Status: CANDIDATE (L6).
- **Relations and structure:** the hierarchy `MISSION ▸ ENGINEERING CAPABILITY ▸ ENGINEERING KNOWLEDGE ▸ ENGINEERING ARTIFACT ▸ PKS ▸ PRODUCT` (L102–106). Concept dependency column (L79–84).
- **Formal content:** yes. `KnowledgeOS instance = NATURE × REPRESENTATION × GOVERNANCE-STATUS × AUTHORITY-SCOPE × EVIDENCE-STATUS × OPERATIONAL-STATE` (L61–63). Derived functions: `lifecycle regime = f(NATURE)`, `validation method = f(REPRESENTATION, NATURE)`, `intent = f(GOVERNANCE-STATUS, OWNER)` (L66–68). Admission rule: orthogonal · necessary · sufficient (L37).
- **Corrections and contradictions:**
  - It is self-annotated by S0021 with three corrections: "LEVEL MIXING"; "`PKS` is a CONTAINER, not a layer below `ARTIFACT`"; and "§1 … is a meta-model; §2 … is an ontology" (L29–31).
  - It adds the missing link `DESIGN POLICY (DP-n)` to the chain (L33).
- **Open questions:** MM-1, adopt RQ-002 (L151); MM-2, frontmatter fields (L152); MM-3 (L153); MM-4, whether OPERATIONAL-STATE belongs to the artifact or the rule (L154); MQ-1 (L155).

---

### S0019 · docs/knowledgeos/KnowledgeOS_Mission_Discovery.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "…Enterprise Information Architect, 2026-08-02"; L23 "WITHDRAWN — 2026-08-02". The annotation cites `KnowledgeOS_Vision_Mission_Clarification.md` (L25), which is not in the batch.
- **Read depth:** complete (L1–200).
- **What it is:** strategic discovery, mission only: "What is KnowledgeOS?" (L9).
- **Key claims:**
  - Order correction: "I ran the order BACKWARDS … **The rule is mission → domains → ontology → taxonomy → indexing → automation.**" (L17)
  - Body: "THERE ARE TWO MISSION STATEMENTS IN CANON, AND ONLY ONE IS ADOPTED" (L37). M-A is AIP-14 (ADOPTED); M-B is the Charter's "AI-native Knowledge Operating System" (PROPOSED) (L41–42).
  - Adopted-mission formulation: "KnowledgeOS exists to improve the delivery of PublicDigit by governing how engineering work is planned, approved, executed, verified and evolved — in a form that is independent of project, programming language and execution provider." (L46)
  - "AIP-14 constrains WHY work is undertaken. The Reference Architecture constrains WHAT is built." (L54) It is "a **cost control**, not a boundary" (L56).
  - Later annotation: "'TWO MISSIONS' IS WITHDRAWN … I mislabelled a CONSTRAINT as a MISSION" (L23–27). "THE MISSION IS IMPLICIT BUT EVIDENCED, NOT VACANT" (L29). "ARTIFACT ABSENCE ≠ CONCEPT ABSENCE" (L29).
  - "Almost every architectural difficulty in this track is a symptom of designing under M-B while governed by M-A." (L183)
  - Answer: "A governance architecture — not a software system" (L173).
- **Concepts:**
  - M-A / M-B: introduced, then withdrawn as "two missions" (L23).
  - Responsibilities R-1..R-8 (L66–73), including R-8 "Refuse to approve its own work".
  - Non-responsibilities N-1..N-10 (L81–90). N-1 is "BREACHED" (L81, L92).
  - Three authority systems: POLICY / METHOD & IMPLEMENTATION / DOMAIN KNOWLEDGE (L102–104). Adopted from a reviewer correction.
  - PD-3 split: MACHINERY = GENERIC SUBDOMAIN; KNOWLEDGE CONSTITUTION = SUPPORTING, candidate (L126–129).
  - The model `Vision (hypothesis, sponsor) → Mission (VACANT) → Governance → Capabilities → PKS → Product` (L31). Note: L31 says "VACANT" while L29 says "NOT VACANT" [INT: internal inconsistency in the annotation].
- **Relations and structure:** four boundaries — KnowledgeOS / PKS / AI Runtime / Product (L111–116). Lifecycle-link grades (L134–139). "The cycle is the mission's *hypothesis*, not its *description*" (L149).
- **Formal content:** none.
- **Corrections and contradictions:**
  - It accepts four reviewer corrections (L12–19).
  - Its own "two missions" finding is withdrawn (L23).
  - It reframes S0037 as "superseded in sequence" (see S0037 L15–17).
- **Open questions:** MQ-1 to MQ-7 (L157–163). In particular: which mission governs; who owns KnowledgeOS; N-1's breach; PD-3's individual ownership; whether R-6 harvest is within mission; N-10; and "What would falsify **M-A**?"

---

### S0020 · docs/knowledgeos/KnowledgeOS_Ontology_Architecture_Classification.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "…Enterprise Information Architect, 2026-08-02"; L80 "SHARPENED 2026-08-02 by falsification test W-3". No conflict.
- **Read depth:** complete (L1–171).
- **What it is:** a classification of the existing executable ontology. It parses seven schema YAMLs and places D-1..D-7.
- **Key claims:**
  - "I recommended *'express D-1..D-7 in `knowledge-types.yaml`.'* That mixes abstraction levels" (L14). Withdrawn (L161).
  - "THE 13% COVERAGE IS A DECLARED BOUNDARY, NOT AN OVERSIGHT" (L34). The boundary is `scope.include = docs/knowledge/**/*.md` (L24).
  - `knowledge-types.yaml` "IS a documentation ontology" (L37).
  - Three layers: "L-A Documentation (exists) · L-B Platform (absent) · L-C Product (partial)". The fourth layer is declined (L144).
  - Three proofs that D-1..D-7 do not belong in the schemas: KEY COLLISION (`governance` already exists), VOCABULARY INSUFFICIENCY (no `governs/produces/consumes/observes`), SUBJECT MISMATCH (L69–71).
  - PKS-as-projection: "SUPPORTED IN PRINCIPLE … BLOCKED IN PRACTICE", then "FALSIFIED for every artifact that exists today" (L78–80).
  - "It is complete for its declared subject — documents — and silent about the platform that governs it." (L164)
- **Concepts:**
  - L-A / L-B / L-C ontology layers (L46–48). New classification; candidate.
  - PROJECTION as authority `derived` (L86).
  - CAP-002 as the possible governor of PKS regeneration (L102).
- **Relations and structure:** ontology map (L106–138). "L-B would OWN and CLASSIFY L-A; L-A would never contain L-B" (L147).
- **Formal content:** none. Enumerated vocabularies: 31 types, 8 statuses, 5 authorities, 9 audiences, 12 contexts, 11 edges, 18 rules (L46).
- **Corrections and contradictions:**
  - It withdraws its own earlier recommendation (L14, L161).
  - It reframes "a coverage problem" as a scope decision (L35).
  - W-3 falsification sharpening (L80).
  - It questions CAP-002's deferral (AD-1) (L100).
- **Open questions:** whether `scope.include` should extend; whether the edge vocabulary needs transformational verbs (L148); the CAP-002 deferral (L102).

---

### S0022 · docs/knowledgeos/KnowledgeOS_Ontology_Cross_Product_Validation.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "…Enterprise Software Architect, 2026-08-02"; L73 "FALSIFICATION WITHDRAWN 2026-08-02". The withdrawal cites S0012 (L79).
- **Read depth:** complete (L1–200).
- **What it is:** falsification by projection. The ontology is held fixed and projected into PublicDigit, a Hospital IS and an ERP.
- **Key claims:**
  - Success criterion: "NOT to prove the ontology correct … To determine whether it survives projection **unchanged**" (L10).
  - I-11 restated: "Knowledge does not EXECUTE. Capabilities execute. Knowledge constrains and informs generation." (L16)
  - "8 invariant · 2 specialize · 1 disappears · 1 relationship fails." (L52)
  - DESIGN POLICY is "INVARIANT — the strongest result" (L42).
  - "The ontology can express *who decides*. It cannot express *who must be obeyed*." (L117)
  - "the ontology models what things ARE MADE OF, and not what happens TO them" (L139). "The ontology describes composition. It does not describe transformation." (L190)
  - "PublicDigit MASKED two of the three failures" (L166).
- **Concepts:**
  - Invariants I-1..I-11 as tested (L58–67). I-4 fails, and the failure is later withdrawn.
  - Specializations S-1 INTERNAL/EXTERNAL principle; S-2 PRODUCT/DEPLOYMENT; S-3 INERT/ATTESTED projection; S-4 METHOD/RULE/BINDING/CASE LAW (L123–126). Recorded, not added.
  - Missing relationships M-1 SPECIALIZES, M-2 CONSTRAINS, M-3 INSTANTIATES, M-4 ATTESTS, as "transformational" (L134–140). Candidate.
  - Hypotheses H-5 (Method layer, not admitted), H-6 (Runtime Adapter outside the core, supported) and H-1, strengthened (L148–150).
  - Status: "STRATEGIC ONTOLOGY CANDIDATE" (L6).
- **Relations and structure:** concept-by-concept projection table (L37–50). Structural verbs (contains, references, generates, depends on) vs transformational verbs (L140).
- **Formal content:** none beyond the invariant statements.
- **Corrections and contradictions:**
  - It accepts four pre-validation corrections, including I-11 (L12–19).
  - Its F-1, the I-4 falsification, is later WITHDRAWN by S0012: "UNDERSPECIFIED, not false" (L75–79).
  - Diagnosis (L93): "`derived` describes **provenance**; `authoritative` describes **standing**". This prefigures S0018.
- **Open questions:** CV-1 to CV-5 (L173–177). In particular: I-4 scope; PRODUCT vs DEPLOYMENT; external authority; admitting the transformational relations as a group; the ontology status.

---

### S0043 · docs/knowledgeos/KnowledgeOS_Operational_Knowledge_Principles.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "Principal DDD Architect / Knowledge Engineer, 2026-08-02". No conflict. It cites `KnowledgeOS_Vision_Mission_Clarification.md` REV 2 (L73), which is outside the batch.
- **Read depth:** complete (L1–113).
- **What it is:** a principle reconciliation. Six proposed principles from an unread article are checked against the repository.
- **Key claims:**
  - "I HAVE NOT READ THE SOURCE ARTICLE … Every claim attributed to it is **second-hand**" (L9).
  - "FIVE OF SIX ALREADY EXIST. Three are stated in canon; two are ENACTED WITHOUT BEING NAMED." (L15)
  - "Boundaries ≠ Triggers" maps onto `deny` (19) vs `ask` (22) (L27).
  - "The repository has 41 enforced controls and 16 unenforced ones — and EVERY ENGINEERING CAPABILITY sits in the unenforced column." (L52)
  - The one principle to adopt: "Knowledge is an executable engineering asset, not passive documentation." (L59). Bound: "n=1 decision changed" (L64).
  - The five-layer operating model (Boundaries, Purpose, Preferences, Sensing, Triggers) is rejected because it would "repeat the D-4 error" (L72).
- **Concepts:**
  - BOUNDARY / TRIGGER / ADVISORY (L48–50). ADVISORY is the "third term the source does not supply" (L44). New.
  - Observation ladder: "manual observation → repeatable observation → automated observation → continuous sensing" (L79). "adopted as sequence, not architecture" (L76). The repository is at "rung 1½" (L90).
  - Executable-knowledge principle (L59). Recommended, not minted (L66).
- **Relations and structure:** reconciliation table (L23–30).
- **Formal content:** none.
- **Corrections and contradictions:**
  - It corrects S0024's "0 of 11 responsibilities are enforcing" as "TOO STRONG" (L42).
  - It rejects "The Mission layer is vacant", citing a withdrawal elsewhere (L73).
- **Open questions:** adoption of the one principle. The "article was not read" limitation stands (L103).

---

### S0036 · docs/knowledgeos/KnowledgeOS_Platform_Capability_Model.md · 2026-08-02 (internal-2026)

- **Date check:** L7 "…Senior DDD Architect's Addendum, 2026-08-02"; L11 "ANNOTATED 2026-08-02". The annotation cites S0038 (L13), which was written later.
- **Read depth:** complete (L1–292).
- **What it is:** a platform capability model with 25 capabilities. §0 admits new evidence from `docs/architecture/discovery/` (92 files) and `architecture_legacy/round7/`.
- **Key claims:**
  - "THIS DOCUMENT WAS ITSELF REDISCOVERED WORK" (L11). "§2's 'one class per capability' is that error" (L20).
  - `docs/architecture/discovery/` is "A COMPLETE EXECUTED STRATEGIC-DDD DISCOVERY RUN, Rounds 16 → 31" (L30).
  - Evidence Saturation Checkpoint: "a STOPPING RULE for discovery … No other artifact in the platform answers 'when is discovery done?'" (L55).
  - Platform Definition: "KnowledgeOS is a reusable engineering platform whose product is METHOD … and which owns no product's knowledge." (L86)
  - "SIXTH occurrence of … *proposing before searching*" (L81).
  - Bootstrap orchestration: "4 of 8 steps are ready … the beginning and the end" are blocked (L238). It is "a Process Manager whose middle exists and whose ends do not" (L239).
  - Next slice: "MAKE ONE ADVISORY CHECK ENFORCING" — C-17, not C-13 (L256–263).
  - "The MVK experiment concluded the method was missing. It was not missing. It was unreachable" (L285).
- **Concepts:**
  - Capability classes KERNEL / SERVICE / RUNTIME ADAPTER / PRODUCT BINDING / GENERATED ARTIFACT / PRODUCT EVIDENCE / RESEARCH CANDIDATE (L100–129). Later flagged as a flat taxonomy (L20).
  - "Knowledge Discovery" as one capability with five responsibilities (C-08..C-12) (L137).
  - Core Domain = "the engineering method itself" (L90). Conditional on the gate; canon status "Gate shut" (L94).
  - Tier-2 / Tier-3 blockage (L184–189).
  - C-18 Multi-Representation Conformance is a research candidate, n=1 (L119).
- **Relations and structure:** capability dependency graph (L150–166). Candidate patterns (ACL, Shared Kernel, inverted Customer/Supplier, Published Language) are "not assigned" (L168–175).
- **Formal content:** none.
- **Corrections and contradictions:**
  - It refutes three prior findings: MVK reach #1 (no strategic-DDD method), MVK reach #2 (no template), and the Fitness Assessment's "Strategic Discovery n=1" (L77–79).
  - It self-reports `Round27D_ARB_Consistency_Boundary_Review.md` as previously existing work (L82).
  - It is corrected by S0038 (L11–22).
- **Open questions:** PG-1 to PG-8 (L245–252). In particular: enforcement; traversal; generation; a second runtime; OQ-S1; Tier-3 decomposition; and "a searchable index of platform instruments" (PG-8).

---

### S0021 · docs/knowledgeos/KnowledgeOS_Relationship_Ontology.md · 2026-08-02 (internal-2026)

- **Date check:** L9 "…Enterprise Software Architect, 2026-08-02"; L6 "status adopted per review, 2026-08-02"; L115 "RESTATED 2026-08-02"; L108 "falsification WITHDRAWN 2026-08-02". The header cites S0022 (L7), and I-4 cites S0012 (L108). Both are later revisions.
- **Read depth:** complete (L1–193).
- **What it is:** a relationship model, an ontology of 13 concepts × kinds × owners, with invariants vs implementation choices.
- **Key claims:**
  - Correction 1, "Level mixing — the third time" (L17); correction 3, "A PKS contains artifacts" (L19).
  - "DESIGN POLICY (`DP-n`) … ADMIT — DISCOVERED. Neither of us named it" (L32). "Capabilities are owned by design policies, not by domains" (L44).
  - ENGINEERING DOMAIN as capability owner is REJECTED: "No artifact links any `CAP-n` to a `PD-n`" (L33).
  - "A Knowledge Space is not a folder. It is a DECLARED SCOPE with an owner and a governing constitution." (L75)
  - "11 invariants · 8 choices. A future adopter may change every choice and none of the invariants" (L130).
  - "A taxonomy tells you what exists. A relationship model tells you what may change." (L185)
- **Concepts:**
  - The chain `MISSION → STRATEGY → PRINCIPLE → DESIGN POLICY → CAPABILITY → KNOWLEDGE → ARTIFACT` (L38–41).
  - Concepts C-1..C-13 with kinds purpose / governance / capability / knowledge / representation / container / runtime element / projection (L52–64). KNOWLEDGE SPACE (C-9) is admitted as a container.
  - Invariants I-1..I-11 (L105–115). I-4 is UNDERSPECIFIED; I-11 is restated.
  - Implementation choices (L119–128).
  - Candidate I-12: "a Capability may READ across a space boundary but never OWN what it reads" (L151, L157).
  - Status: "STRATEGIC ONTOLOGY CANDIDATE" (L6).
- **Relations and structure:** the contain / reference / generate / depend-on matrix (L81–95). Verb definitions (L79).
- **Formal content:** semi-formal invariant list (L105–115). Nothing else.
- **Corrections and contradictions:**
  - It corrects S0016: level mixing; PKS as a container; meta-model vs ontology (L17–20).
  - Its I-4 is corrected by S0012 (L108). Its I-11 is restated (L115).
  - S0015 later says I-8 was "over-granted" (S0015 L34).
- **Open questions:** RO-1 to RO-4 (L166–169); H-1 to H-4 (L157–160), including whether Knowledge Spaces nest (H-3, later closed by S0012).

---

### S0037 · docs/knowledgeos/KnowledgeOS_Strategic_Architecture_Discovery.md · 2026-08-02 (internal-2026)

- **Date check:** L8 "…Enterprise Knowledge Engineer, 2026-08-02"; L15 "SUPERSEDED IN SEQUENCE 2026-08-02". The annotation cites S0019 as "written afterwards" (L17).
- **Read depth:** complete (L1–336).
- **What it is:** strategic DDD discovery from ownership. It discovers PD-1..PD-7 at a bar of ≥3 of the nine criteria.
- **Key claims:**
  - Evidence grades: "OBSERVED … INFERRED … ASSUMED … HYPOTHESIZED. Only OBSERVED is authoritative" (L11).
  - PD-3 EKP: "THE DOMAIN D-1..D-7 MISSED" (L73), with `owner: nab.raj.sharma` (L77). "That is a category error, and this is its correction." (L85)
  - PD-2 lifecycle independence is "FALSIFIED — 'Retrospectively documented… authored together on 2026-06-25'" (L69).
  - PD-6 Runtime is "NOT A DOMAIN at the commissioned bar" (L115).
  - "It is not yet ONE thing. It is THREE separately-governed domains that overlap, plus a generic runtime — with NO single owner" (L212).
  - Symptoms, including ten rediscoveries, "are what three unreconciled authorities over one subject produce" (L220).
  - "KnowledgeOS is not under-architected. It is under-OWNED." (L328)
  - "Two of nine links are observed inside the platform. The chain is a destination, not a pipeline." (L259)
- **Concepts:**
  - PD-1 Governance (6 criteria); PD-2 Method (4); PD-3 EKP (6); PD-4 Capability (3, blocked by H-CAT-1); PD-5a Protocol (3) and PD-5b Records (3); PD-6 Runtime (2, below the bar); PD-7 PKS (not a platform domain) (L48–119).
  - Conditional Core / Supporting / Generic: PD-2 is CORE, conditional and inferred (L130).
  - Invariants I-1..I-7, OBSERVED (L226–232). V-1 and V-2 are "STATED AND VIOLATED" (L234–241).
- **Relations and structure:** graded context map (L141–187); ownership table (L199–208); candidate architecture (L275–312).
- **Formal content:** none. Threshold rule "≥ 3 of `Round47-OP`'s nine criteria" (L10).
- **Corrections and contradictions:**
  - It corrects S0034: PD-3 added; D-3 Runtime demoted; D-7 moved product-side (L312).
  - It is superseded in sequence by S0019 (L15–25).
  - It applies a stricter bar than `Round47-OP`'s "one or more" (L10).
- **Open questions:** SA-1 "WHO OWNS KNOWLEDGEOS?" (L265); SA-2 to SA-6 (L266–271); U-1 (L268).

---

## Batch synthesis

**Problem.** All 16 files work on one question across one day (2026-08-02, with S0015 running to 08-03): *what KnowledgeOS is*, and whether its engineering platform is reusable beyond PublicDigit. They do this through commissioned discovery, assessment and falsification documents. Each is marked "CANDIDATE" or "Generated", and each is "Submitted to the Decision Authority".

**First appearances in this batch** [INT: first relative to these 16 files only]:
- GENESIS as the gap — "A platform that governs CHANGE does not automatically govern GENESIS" (S0025 L120; S0024 L153).
- The bootstrap/contamination instrument (S0025).
- MATURITY ≠ ENFORCEMENT, later refined to BOUNDARY / TRIGGER / ADVISORY (S0024, S0043).
- Rediscovery as unreachability: "UNREACHABLE knowledge that keeps regenerating itself" (S0038 L221).
- Knowledge ≠ Artifact, and the six-dimension meta-model adding EVIDENCE-STATUS and OPERATIONAL-STATE (S0016).
- DESIGN POLICY (DP-n) as capability anchor, and KNOWLEDGE SPACE as a declared-scope container (S0021).
- Composition vs transformation, with SPECIALIZES / CONSTRAINS / INSTANTIATES / ATTESTS (S0022).
- Authority = PROVENANCE × STANDING, and "evidence EARNS; governance GRANTS" (S0018).
- Three authority systems, and "under-OWNED" (S0019, S0037).

**What changed across the batch.** Many claims were revised within the day:
- The I-4 projection-authority claim moved from falsified (S0022) to underspecified, as a lifecycle gap (S0012), and then to a provenance/standing conflation (S0018). LG-1 was withdrawn.
- The "0 of 11 enforcing" claim (S0024) was corrected to 41 enforced controls and 0 enforcing capabilities (S0043).
- "No strategic-DDD method" (S0025) was refuted twice (S0025 annotation; S0036 §0).
- "Two missions" (S0019) was withdrawn as a constraint mislabelled as a mission.
- The bounded-context set moved from D-1..D-7 (S0034) to PD-1..PD-7 (S0037), adding the EKP and demoting Runtime.
- "0 traversals" was falsified by the blind review (S0015: n≈3).
- The proposing-before-searching count rose from 5 (S0035) to 6 (S0036), 9 (S0038), 10 and then 11 (S0015).

The documents keep a recurring self-diagnosis of "level mixing" (S0012 L26–29; S0021 L17).

**What remains open:**
- Who owns KnowledgeOS (SA-1, MQ-2).
- Whether SD-1 is a product binding (OQ-S1).
- Whether genesis is the platform's responsibility (OQ-S3).
- Adoption of RQ-002 (MM-1).
- Separating provenance from standing (PM-1).
- Admission of transformational relationships (CV-4).
- PRODUCT vs DEPLOYMENT (CV-2).
- Reconciling with the frozen Phase-02 map (IR-3).
- Disposition of E-1 (IR-2).
- Whether any second adopting product opens the gate (OQ-K3).
