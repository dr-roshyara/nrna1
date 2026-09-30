# pks

**Scope(s):** OBJECT · **Row count:** 45 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `PKS`; `Product Knowledge Space`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1092: co-occurs with `knowledge-flow` — labels co-occur in the same contribution's labels[] 4 separate times across the corpus
- G1093: co-occurs with `KnowledgeOS` — labels co-occur in the same contribution's labels[] 4 separate times across the corpus
- G1098: co-occurs with `capability` — labels co-occur in the same contribution's labels[] 4 separate times across the corpus
- G1100: co-occurs with `decision-model` — labels co-occur in the same contribution's labels[] 5 separate times across the corpus
- G1101: co-occurs with `knowledge-space` — labels co-occur in the same contribution's labels[] 5 separate times across the corpus
- G1102: co-occurs with `runtime-adapter` — labels co-occur in the same contribution's labels[] 3 separate times across the corpus
- G1109: co-occurs with `meta-model` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1112: co-occurs with `mission` — labels co-occur in the same contribution's labels[] 3 separate times across the corpus
- G1167: co-occurs with `aip-platform` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0001, scope OBJECT: "A product-scoped knowledge space: one product's concepts, language, contexts, and bindings; repeatedly discussed as a context TYPE vs instance, and as a disputed 'projection'."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0002 §"Platform Lifecycle (Idea→Knowledge→PKS→Implementation→Evidence→Evolution) ... B-1 correction: software→evidence OBSERVED · back-edge n≈3 informal / ≈2 formal · KnowledgeOS→PKS n=0 · Genesis has NO route"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0008 §"Canonical concept glossary — the 14 examined, plus 3 the corpus forces in"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S0237. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0002, S0005 (×2), S0008, S0011, S0028, S0031, S0033 (×2), S0036, S0237 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0008, S0016, S0021 (×2), S0028 (×4) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0002, S0008, S0021, S0028 |
| dependencies | PRESENT | S0002 (×2), S0005, S0007, S0008, S0010, S0011, S0013, S0020, S0021, S0028 (×3), S0031, S0036 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0005, S0011, S0013 (×2), S0028, S0033 (×2), S0034 (×2) |
| examples | PRESENT | S0011, S0022 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0002 (×2), S0019, S0022 |
| open_questions | PRESENT | S0021, S0022, S0234 |

## Rationale

The platform lifecycle is graded link-by-link: software-to-evidence is observed; the back-edge is n≈3 informal / ≈2 formal; KnowledgeOS-to-PKS generation is n=0; and the Genesis stage has no route at all. Individual grades are confirmed; the loop as a whole remains a hypothesis [S0002]. Additionally, Relationship R-6: KnowledgeOS creates PKS. External support: scaffolding/generators (Spring Initializr-class) are implementation guidance only; context-engineering platforms that auto-assemble context are adjacent. Challenge: no external instance of generating a governed knowledge space is found; internally n=0. Confidence: UNIQUE — the loop's first edge is unproven both inside and outside the corpus [S0005]. Further, Aggregate result: externally supported (>=HIGH) relationships are R-3, R-4, R-5, R-8, R-10, and R-2's separation; medium relationships are R-1, R-7, R-9; the unique, operationally-unproven items are R-6 (creates->PKS) and DP-n as a named layer. The distinctive bet of KnowledgeOS is now precisely locatable as R-6; everything else is either validated or a naming choice, and R-6 is exactly what the charter's gated stages and the docket's doors already refuse to assume [S0005]. Relatedly, The proposed chain is validated edge by edge: 'KnowledgeOS creates PKS' is a hypothesis (n=0, every PKS is authored by a person); 'PKS contains Engineering Knowledge' is WRONG, violating I-7 (a PKS contains product knowledge and bindings; reusable method must not live in it — the current breach is the exception that proves the rule); 'knowledge organized by Ontology' holds with the precision that L-A organizes artifacts/carriers, not knowledge itself (I-1); 'used by Capabilities' holds (capabilities may read across space boundaries, never own — H-1); 'Capabilities executed through Runtime' is imprecise (capabilities are advisory scripts invoked on demand; the runtime executes Execution Assets/ASTs; 41 enforced controls live runtime-side while every capability is advisory — the drawn edge hides this finding); 'Runtime produces Evidence' should attribute production to WORK (P-7/P-8), the runtime only hosts the work; 'Evidence improves KnowledgeOS' is only partly true (n≈3 informal, B-1's fact) [S0008]. In the same vein, Per-domain confidence after falsification (R16 Strong/Medium/Weak scale): D-1 Strong->STRONG; D-2 Strong->MEDIUM; D-3 Strong->WEAK; D-6a (Evidence Protocol) new->MEDIUM; D-6b (Product Evidence) new->STRONG; D-5 (Engineering Capability, not a context) Medium->MEDIUM (unchanged, H-CAT-1 stands); D-4 (Workflow, rejected) ->STRONG as a rejection; D-7 (PKS, a context type) new->WEAK. Distinction drawn: falsified is worse than unevidenced — D-3 and D-7 carry contradicting evidence (falsified); D-6a carries no evidence (unevidenced) [S0011]. Six lifecycle transitions graded: L-1 discover a product (HYPOTHESIS, charter Stage 1 unapproved); L-2 discovery->generate a PKS (HYPOTHESIS, n=0, adapter is human); L-3 PKS->guide engineering (PARTIAL, protocol used, effect unmeasured); L-4 engineering->produce software (EVIDENCED, 1,532 files); L-5 software->collect evidence (PARTIAL, 106 reports); L-6 evidence->improve KnowledgeOS (EMPTY, never traversed). The genesis correction forces an additional insertion: Idea -> GENESIS -> KnowledgeOS -> Engineering -> Software, where the GENESIS box has no governing rule [S0028]. Additionally, A 16-row migration map (M-1..M-16) traces every artifact's current home, future home, and extraction trigger, all quoted from canon. Nine rows need no move (already in permanent home), including M-10 CAP-001 Domain/Application/Shared as extractable now with zero repository knowledge verified. Seven rows are blocked, each by a recorded governance gate (R-37, BRM-1, Phase II freeze, OQ-K1), not a design gap: M-5 Layer_Verification_Rule (OQ-K1), M-6 Integrity Model/Review Method/ARB Discipline (Phase II freeze + R-37 + OQ-K1), M-7 Methodology Baseline (BRM-1), M-9 PKS_Phase_III (R-37), M-11 CAP-001 Infrastructure (must never be extracted), M-12 .claude/settings.json (never moves), M-13 Capability Mapping (waits on a second runtime adapter), M-16 Cross-product research (OQ-K2) [S0031]. Further, The MVK experiment tested Knowledge -> ARCHITECT (reading rules) -> Software (n=1, FAIL on boundary), not KnowledgeOS -> PKS GENERATOR -> Software (n=0, not tested). This decomposes bootstrapping into three distinct responsibilities, only one exercised: R-A bootstrap a PKS (n=0, every PKS to date hand-built); R-B bootstrap engineering (n=1, the experiment, boundary insufficient); R-C bootstrap software (n=0, no code written in the experiment). The 'PKS Generator' slot in the target architecture is currently occupied by a person reading documents; whether a generator can occupy it is untested [S0033]. Relatedly, A conceptual context map (no folders, no files moved) traces five arrows: KnowledgeOS->PKS Generator (component does not exist); PKS Generator->Product PKS (hypothesis, n=0); Product PKS->Business Product (partially evidenced); Business Product->Operational Evidence (evidenced, 106 reports); Operational Evidence->KnowledgeOS (empty, zero-independent, never traversed). One of five arrows is evidenced; the loop-closing arrow is empty and the loop contains a component (PKS Generator) that has never existed. No additional context is justified by the experiment [S0033]. In the same vein, An 8-step bootstrap orchestration of existing capabilities (no design of knowledgeos init) shows 4 of 8 steps ready (tactical modelling, capability instantiation, runtime binding, verification) while steps 1 (establish execution lifecycle, blocked by Genesis/role scaling), 2 (discover the domain, blocked by SD-1), 3 (produce the PKS, blocked at n=0), and 8 (harvest and improve, blocked by 0 traversals) are blocked — the beginning and the end. init remains premature since P3 requires n>=2 [S0036]. Nine landscape findings (C-1..C-10 evidence) mapped onto the register, with F2/F3/F4 carry-forwards honored (implemented mechanism != domain ownership; evidence is current-state-scoped not universal; consumption != ownership != KnowledgeOS): state-as-fold/append-only log (C-2, SAME as Revisability-001's enforcement, mechanism layer); forward-only supersession (C-5, SAME as Revisability-001, asymmetric ownership PKS-governance/EKS-mechanism/AIP-declared-only); closed-verdict vocabulary (C-3, SAME as the Validation Layer, a published-language boundary); assessment-conferring-no-authority (C-4, SAME as INV-003 Established); epistemic-class discipline (C-8, SAME as Pramana-001's source-role separation, PKS-owned, asymmetric F3); regenerable non-authoritative projection (C-7, SAME as INV-004 Established); honest-UNKNOWN (C-10, SAME as H-ZERO-001, 'discipline, not mechanism'); authority-as-recorded-reference (C-1, SAME as the Authority family's core, X-02 SAME-vs-RELATED across systems UNKNOWN/U-02); the F4 relationship question (AIP is DISTINCT -- a consumer/runtime, not the owner, not the KnowledgeOS layer itself) [S0237].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

### Rationale: arguments, analysis & alternatives (7 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0002, S0005, S0028, S0031, S0033, S0036, S0237

- [S0002] The platform lifecycle is graded link-by-link: software-to-evidence is observed; the back-edge is n≈3 informal / ≈2 formal; KnowledgeOS-to-PKS generation is n=0; and the Genesis stage has no route at all. Individual grades are confirmed; the loop as a whole... (anchor: "Platform Lifecycle (Idea→Knowledge→PKS→Implementation→Evidence→Evolution) ... B-1 correction: software→evidence OBSERVED · back-edge n≈3 informal / ≈2 formal · KnowledgeOS→PKS n=0 · Genesis has NO route")
- [S0005] Relationship R-6: KnowledgeOS creates PKS. External support: scaffolding/generators (Spring Initializr-class) are implementation guidance only; context-engineering platforms that auto-assemble context are adjacent. Challenge: no external instance of generat... (anchor: "R-6 | KnowledgeOS —creates→ PKS | UNIQUE — the loop's first edge is unproven inside and outside")
- [S0028] Six lifecycle transitions graded: L-1 discover a product (HYPOTHESIS, charter Stage 1 unapproved); L-2 discovery->generate a PKS (HYPOTHESIS, n=0, adapter is human); L-3 PKS->guide engineering (PARTIAL, protocol used, effect unmeasured); L-4 engineering->pr... (anchor: "The Lifecycle (L-1..L-6) ... the genesis correction P3/§0 forces into the lifecycle: the platform governs change; it does not govern genesis")
- [S0031] A 16-row migration map (M-1..M-16) traces every artifact's current home, future home, and extraction trigger, all quoted from canon. Nine rows need no move (already in permanent home), including M-10 CAP-001 Domain/Application/Shared as extractable now with... (anchor: "Migration Map — Nine of sixteen rows need NO MOVE ... Seven are blocked, and every blocker is a recorded governance gate, not a design gap")
- [S0033] A conceptual context map (no folders, no files moved) traces five arrows: KnowledgeOS->PKS Generator (component does not exist); PKS Generator->Product PKS (hypothesis, n=0); Product PKS->Business Product (partially evidenced); Business Product->Operational... (anchor: "Strategic Context Map (conceptual) ... One of five arrows is evidenced. The loop-closing arrow is empty, and the loop contains a component that has never existed")
- [S0036] An 8-step bootstrap orchestration of existing capabilities (no design of knowledgeos init) shows 4 of 8 steps ready (tactical modelling, capability instantiation, runtime binding, verification) while steps 1 (establish execution lifecycle, blocked by Genesi... (anchor: "Bootstrap Readiness ... 4 of 8 steps are ready. The blocked ones are steps 1, 2, 3 and 8 — the beginning and the end ... The orchestration is a Process Manager whose middle exists and whose ends do not")
- [S0237] Nine landscape findings (C-1..C-10 evidence) mapped onto the register, with F2/F3/F4 carry-forwards honored (implemented mechanism != domain ownership; evidence is current-state-scoped not universal; consumption != ownership != KnowledgeOS): state-as-fold/a... (anchor: "Landscape integration -- which EKS/PKS/AIP responsibilities already exist, and where their boundaries lie")

### Corrections, contradictions & retractions (7 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0002, S0005, S0008, S0010, S0011, S0022, S0032

- [S0002] P1 defines PKS content as method/binding/evidence (cognate of Round38C-04); I-7 states a PKS never contains reusable method, yet this rule is both stated and breached in the current corpus. PKS contains artifacts; a projection claim is falsified for existin... (anchor: "PKS Boundary — P1 method/binding/evidence ... I-7 (PKS never contains reusable method — stated AND breached)")
- [S0005] Three corrections applied to the (external) concept matrix: (1) 'PKS = Context Engineering' softened to 'PKS addresses the same problem space as context engineering and extends it with governance, lifecycle, authority and engineering semantics' — not an ide... (anchor: "Corrections applied to the concept matrix — PKS = Context Engineering softened")
- [S0008] The proposed chain is validated edge by edge: 'KnowledgeOS creates PKS' is a hypothesis (n=0, every PKS is authored by a person); 'PKS contains Engineering Knowledge' is WRONG, violating I-7 (a PKS contains product knowledge and bindings; reusable method mu... (anchor: "Concept relationships — the reviewer's example chain, validated ... KnowledgeOS creates PKS contains Engineering Knowledge organized by Ontology used by Capabilities executed through Runtime produces Evidence improves KnowledgeOS")
- [S0010] Two corrections to an Addendum's positioning: (1) 'KnowledgeOS is a Core Domain' contradicts AIP-14/DA ruling 2026-07-27 that the Election System is the Core Domain and the platform is a Supporting Subdomain; the Reference Model records only the conditional... (anchor: "Two corrections to the Addendum's own positioning ... "KnowledgeOS is a Core Domain" ... Contradicts a DA ruling")
- [S0011] The claim 'PKS is a projection' is falsified for every existing PKS artifact: examined fields (Authority: Generated, human-asserted; Status: ACCEPTED WITH REFINEMENTS; a Disposition History with ARB reviewer endorsement 9.9/10; a Commission) are incompatibl... (anchor: "W-3 · "PKS is a projection" — FALSIFIED for every existing PKS artifact")
- [S0022] 'One PKS per product' fails: the same ERP deployed to fifty customers with different charts of accounts, fiscal calendars, and jurisdictions raises the unanswered question of whether that is one PKS with fifty bindings or fifty PKSs. PublicDigit masks this:... (anchor: "F-2 · "One PKS per product" fails for configurable products ... the same ERP is deployed to fifty customers ... What the ontology cannot express | the difference between a PRODUCT and a DEPLOYMENT of it")
- [S0032] The reviewer's proposed Vision->Mission->Governance->Capabilities->PKS->Product hierarchy is validated as better supported than the author's prior 'two missions' framing, because it explains why AIP-14 looked like a mission: when a layer is occupied only im... (anchor: "Lifecycle — is the proposed hierarchy better supported? VERDICT: the Vision → Mission → Governance → Capabilities → PKS → Product hierarchy IS better supported than my "two missions" framing")

### Experiments (3 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0002, S0019, S0022

- [S0002] Elements that do NOT survive removal of the single product: registers' contents, all records/reports, every PKS instance, Round-corpus case law, governed-registers.yaml, .claude/settings.json, and election domain knowledge. (anchor: "What does not survive — registers' contents · all records/reports · every PKS instance · Round-corpus case law · governed-registers.yaml · .claude/settings.json · election domain knowledge")
- [S0019] Four commissioned lifecycle links graded: KnowledgeOS->creates PKS is hypothesized (n=0, PublicDigit's PKS was authored, not generated); PKS->guides Product is partially evidenced (protocol used, never measured against an unguided baseline); Product->genera... (anchor: "Part 5 — Lifecycle ... KnowledgeOS → creates PKS | HYPOTHESIZED, n=0 ... Evidence → improves KnowledgeOS | EMPTY, blocker: the retrospective has not run")
- [S0022] Twelve concepts projected into PublicDigit, a hypothetical Hospital Information System, and a hypothetical ERP System: MISSION, STRATEGY, DESIGN POLICY, CAPABILITY, KNOWLEDGE, ARTIFACT, KNOWLEDGE SPACE, PRODUCT are invariant across all three; PRINCIPLE spec... (anchor: "Concept-by-concept projection ... 8 invariant · 2 specialize · 1 disappears · 1 relationship fails")

### Extensions, distinctions & restatements (10 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0005, S0005, S0011, S0013, S0013, S0028, S0033, S0033, S0034, S0034

- [S0005] Aggregate result: externally supported (>=HIGH) relationships are R-3, R-4, R-5, R-8, R-10, and R-2's separation; medium relationships are R-1, R-7, R-9; the unique, operationally-unproven items are R-6 (creates->PKS) and DP-n as a named layer. The distinct... (anchor: "The relationship pass sharpens the concept pass's conclusion: the platform's EDGES are almost all standard engineering — what is uniquely unproven is ONE edge (creates → PKS) and ONE layer name (DP-n)")
- [S0005] Challenge accepted: 'KnowledgeOS is not merely a generator. It is the platform that governs the ENTIRE PKS lifecycle.' R-6 is restated as a composite chain: KnowledgeOS discovers/models/governs/generates/evolves PKS, guides PRODUCT ENGINEERING (the actor — ... (anchor: "REV 2 — R-6 BROADENED per review: the bet is the INTEGRATION, not one edge")
- [S0011] Per-domain confidence after falsification (R16 Strong/Medium/Weak scale): D-1 Strong->STRONG; D-2 Strong->MEDIUM; D-3 Strong->WEAK; D-6a (Evidence Protocol) new->MEDIUM; D-6b (Product Evidence) new->STRONG; D-5 (Engineering Capability, not a context) Medium... (anchor: "Confidence — R16 Workbook scale, scored independently ... falsified ≠ unevidenced")
- [S0013] Fifteen candidate concerns, each citing its supporting commission and what it waits on: K-1 dimensional-with-archetypes meta-model; K-2 the archetype set (strong: DOMAIN/PROCESS/CAPABILITY/KNOWLEDGE/ARTIFACT/RUNTIME ASSET/PROJECTION; weaker: CONTAINER; weak... (anchor: "2. CANDIDATE — supported by evidence, awaiting ruling or more evidence (K-1..K-15)")
- [S0013] Twelve rejected/falsified claims listed with falsifying pass: R-1 the flat A-G category list as a meta-model; R-2 Governance/Product as kinds, Knowledge Asset as one thing; R-3 Domains own Capabilities; R-4 the single-stack hierarchy (Purpose->...->Artifact... (anchor: "5. REJECTED — falsified, with the falsifying pass cited (R-1..R-12)")
- [S0028] P5 synthesizes: KnowledgeOS is an engineering platform with internal subsystems, and the extraction boundary runs between the platform and the Product PKS, not between folders. A precision note corrects an Addendum's DDD pattern reading: 'Separate Ways with... (anchor: "P5 · The Product Architecture Reference — synthesis ... Precision note on the Addendum's pattern reading: "Separate Ways with a Shared Kernel" is internally tense in Evans' taxonomy")
- [S0033] The MVK experiment tested Knowledge -> ARCHITECT (reading rules) -> Software (n=1, FAIL on boundary), not KnowledgeOS -> PKS GENERATOR -> Software (n=0, not tested). This decomposes bootstrapping into three distinct responsibilities, only one exercised: R-A... (anchor: "C-3 · What the experiment actually tested — ACCEPTED, and it is the most consequential correction ... three distinct responsibilities, only one of which has ever been exercised")
- [S0033] Two orthogonal classifications must not be collapsed: product ownership (who owns this knowledge, a stable ownership boundary) vs architectural responsibility (what responsibility does this artifact describe, internal and changes as design evolves). Knowled... (anchor: "Objective 2 — Products vs subsystems ... Promoting a subsystem responsibility to a repository root would encode an INTERNAL DESIGN DECISION as a REPOSITORY-WIDE OWNERSHIP BOUNDARY")
- [S0034] A note records that this domain model was later falsification-tested and did not survive intact: D-1 Governance remained STRONG (survived all four falsifiers); D-2 Method dropped STRONG->MEDIUM (own-decision-series claim falsified as evolution, survives on ... (anchor: "FALSIFICATION TESTED 2026-08-02 — THIS MODEL DID NOT SURVIVE INTACT ... VERDICT: REVISE, then re-test. NOT canonical")
- [S0034] D-7, PKS, is reclassified from a context to a context type: it has one instance per product; 'Product Knowledge' is not a boundary in KnowledgeOS, it is the shape of a boundary each product instantiates. PublicDigit PKS, Hospital PKS, ERP PKS would each be ... (anchor: "D-7 · Product Knowledge Space — RECLASSIFIED: a CONTEXT TYPE, not a context ... Conflating the type with an instance is what let PublicDigit's methodology end up inside its own PKS paths")

### Validation (3 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0007, S0007, S0020

- [S0007] PKS as governed, machine-readable AI context converges with industry 'context engineering' (Gartner July 2025: 'context engineering is in, prompt engineering is out', predicted in 80% of AI tools by 2028); enterprise practice defines governed, machine-reada... (anchor: "PKS — governed, machine-readable context for AI ... "context engineering" 2025–26")
- [S0007] The industry-wide finding that 86% of enterprises assemble rich context but hand it over with no governance over agent actions, and 89% say governance is critical while ~50% have it, is convergent with this platform's own governed-context-plus-advisory-capa... (anchor: "The enforcement finding (governed context + advisory capabilities) ... 86% of enterprises are assembling rich, semantically grounded context — and handing it over with no governance")
- [S0020] PKS-as-projection is supported in principle by three existing vocabulary elements: authorities.yaml already carries 'derived' (generated index/summary from an authoritative source), knowledge-relationships.yaml already carries 'derived_from', and the reposi... (anchor: "PKS as a projection — validated ... SUPPORTED IN PRINCIPLE by three pieces of EXISTING vocabulary. BLOCKED IN PRACTICE by a declared scope ... SHARPENED 2026-08-02 by falsification test W-3")

### Other concept notes (3 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0007, S0013, S0022

- [S0007] KnowledgeOS generating a PKS (n=0) has only adjacent external support (context-engineering platforms generate context from metadata, not the same as generating a governed PKS); it remains a unique hypothesis needing operational proof only. (anchor: "KnowledgeOS→PKS generation (n=0) ... UNIQUE HYPOTHESIS — operational proof only")
- [S0013] Nine hypotheses with evidence state: H-1 the space map is the platform's real ownership/context map (n=1, downgraded); H-2 GOVERNANCE as a fifth viewpoint (asset found, framing undecided); H-3 PURPOSE more fundamental than CONTAINER (n=0); H-4 KnowledgeOS a... (anchor: "3. HYPOTHESIS — plausible, insufficient evidence (H-1..H-9)")
- [S0022] Four candidate specializations recorded, none added: S-1 PRINCIPLE into INTERNAL (owned, amendable) vs EXTERNAL (imposed, non-amendable, compliance-only); S-2 PRODUCT into PRODUCT vs DEPLOYMENT/TENANT; S-3 PROJECTION into INERT (index, graph — authority by ... (anchor: "Candidate specializations (S-1..S-4)")

### Definitions (4 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0008, S0016, S0021, S0028

- [S0008] A 17-row glossary assigns each term (KnowledgeOS, PKS, Engineering Knowledge, Engineering Capability, Engineering Method, Engineering Governance, Engineering Runtime, Runtime Adapter, Engineering Evidence, Engineering Ontology, Documentation Ontology L-A, P... (anchor: "Canonical concept glossary — the 14 examined, plus 3 the corpus forces in")
- [S0016] Six concepts defined by responsibility/lifecycle/owner/dependencies, none defined by a folder: MISSION (why the platform exists; enacted->candidate->ratified, currently enacted-unratified; owner: sponsor; depends on nothing, the root); ENGINEERING KNOWLEDGE... (anchor: "The six concepts ... MISSION · ENGINEERING KNOWLEDGE · ENGINEERING CAPABILITY · ENGINEERING ARTIFACT · PRODUCT KNOWLEDGE SPACE · PRODUCT")
- [S0021] Thirteen concepts classified by kind and owner: C-1 VISION (purpose, sponsor); C-2 MISSION (purpose, sponsor, enacted-unratified); C-3 ENGINEERING STRATEGY (purpose->governance bridge, ARB/sponsor); C-4 ENGINEERING PRINCIPLE (governance, DA/sponsor+ARB/plat... (anchor: "Concepts × kind × owner (C-1..C-13)")
- [S0028] Product definitions: KnowledgeOS (the reusable engineering platform, output is rules and methods, never product knowledge; canon status: potential product, Supporting Subdomain, gate unopened); PKS (one product's knowledge, one per product, never reusable, ... (anchor: "Product Definition ... The generative relation is creates, not contains. KnowledgeOS → creates → PKS → guides → Product")

### Limitations & warnings (2 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0011, S0234

- [S0011] D-7's 'Context Type' claim (one PKS instance per product) was inferred from a single instance (PublicDigit's), with zero instances generated from the type; 'one instance per product' is an observation of one case restated as a rule — a type never instantiat... (anchor: "W-4 · D-7 "Context Type" was inferred from a single instance")
- [S0234] AIP's evidence records the ARB 'Platform =^ Adoption separation' observation (platform concepts and PublicDigit adoption evidence interwoven in the same documents; separation expected to be driven by the first successful adoption in another project) and dis... (anchor: "the PKS<->AIP boundary is UNKNOWN in the current AIP corpus and is left to Stage 4")

### Formalizations & axioms (4 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0021, S0028, S0028, S0028

- [S0021] A full relationship table across all thirteen concepts, using four typed relations (contains = container relation, references = a pointer, generates = produces a new instance, depends on = cannot be understood without). Notable entries: KNOWLEDGE SPACE may ... (anchor: "The relationship model — the commission's seven questions ... contains = a container relation · references = a pointer · generates = produces a new instance · depends on = cannot be understood without")
- [S0028] A five-tier product architecture: T1 KnowledgeOS (the reusable engineering product); T2 KnowledgeOS Services (governance, capabilities, validation, runtime integration, operational learning, PKS generation — the platform's internal service layer, not PKS); ... (anchor: "Product Architecture — Five tiers ... T2 is the tier the recent work uncovered, and it is NOT PKS ... subsystems are INTERNAL")
- [S0028] The extraction boundary is corrected from a file list (Product Boundary Discovery's section 6, refuted at n=1) to a decomposition: METHOD belongs to KnowledgeOS (domain-free and binding-free and evidence-free); BINDING belongs to the Product PKS (the produc... (anchor: "Extraction Boundary ... The boundary is not a file list. It is a decomposition ... METHOD | BINDING | EVIDENCE / CASE LAW")
- [S0028] A responsibility becomes a component only when exercised at least once, by someone other than the originator, with a repeatable pattern extracted from >=2 instances. A prior claim 'PKS Generator doesn't exist' is refined as imprecise: the RESPONSIBILITY exi... (anchor: "P3 · Responsibility vs Component — the genesis gap ... Accepted refinement: "PKS Generator doesn't exist" was imprecise ... a Human-in-the-Loop Adapter")

### Open questions & future research (2 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0021, S0022

- [S0021] RO-3: is I-7 (PKS never contains reusable method) to be repaired or withdrawn, since it is breached — authority: ARB. (anchor: "RO-3 | Is I-7 (PKS never contains reusable method) to be repaired or withdrawn?")
- [S0022] CV-2: does the ontology need PRODUCT vs DEPLOYMENT, since PublicDigit already has organisation_id — authority: ARB. (anchor: "CV-2 | Does the ontology need PRODUCT vs DEPLOYMENT?")


## Notes for P3

- This is my own observation: this label participates in 9 candidate groups (G1092, G1093, G1098, G1100, G1101, G1102, G1109, G1112, G1167) — a comparatively dense cross-linkage that may be worth prioritizing in P3 reconciliation.
