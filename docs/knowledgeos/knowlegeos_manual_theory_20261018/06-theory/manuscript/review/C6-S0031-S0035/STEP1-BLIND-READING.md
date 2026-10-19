# Review C6, step 1: blind reading of S0031–S0035

I read only the five files I was given, each in full, and wrote no files. Anchors are `Sxxxx:Lnn`. My own judgments are marked **[I]**. Strength words are quoted as the source wrote them.

---

## S0031 — Architecture Consolidation (`docs/knowledgeos/2026-08-02-knowledgeos-architecture-consolidation.md`)

**Framing and stance**
- Self-declared kind: "Identifies architecture that already exists. Invents none · improves none · redesigns none · renames none" (L5).
- The vision is "ACCEPTED AS DESTINATION, not evaluated": "KnowledgeOS creates PKS · PKS is an instance, not a product · four strategic products" (L8). The four products are never listed.
- A correction carried in from review (L12–14): the model is "sufficient FOR THE PRESENT MATURITY LEVEL" and is "currently validated", not mature.

**Concepts**
- Three governed concerns under ES-005.1, "ADOPTED" (L20–26): Product (`docs/` `architecture/` `app/` `tests/`), Engineering Platform (`engineering/`), and Runtime mount (`.claude/`, "never 'the architecture'").
- Three registered documentation domains (L28): `publicdigit`, `knowledgeos`, `pks`.
- Measured landscape (L32–40): 1,532 code files in `app/` (133 in `app/Domain`), 106 verification md, 448 `docs/architecture` md, 195 `docs/implementation` md of which 92 are `PKS_*`, 132 EKP md, and 9/2/12 in the three roots.
- "Central landscape finding" (L42–46), quoted from EAD-1: the governance framework has "ZERO" election terms, so it is "domain-free methodology … in `docs/`", a path ES-005.1 classifies as product. The migration must resolve this "misplacement — not any missing design".
- ES-005.3 litmus as the inventory filter (L50): "could a different project adopt this unchanged?"
- "The kernel" is the Reference Architecture, status DRAFT (L56, L67). Its stated principles are "Governance precedes automation…" and "independent of project, programming language, or execution provider".
- R-37 burden of proof (L69): no orchestrator or state machine until operational evidence shows the governance model is insufficient.
- BRM-1 / SDM-EXT-1 (L83–89): materialization was "decided against". The rule follows: "KnowledgeOS may be extracted by REFERENCE, never by COPY", which is also ES-005.4 ("One rule → one home").
- A four-layer runtime model (L104–118): Governance Policy → Capability Mapping (tool-neutral) → Runtime Adapter → Concrete Configuration. Capability Mapping exists so tool vocabulary "cannot LEAK UPWARD".
- Stable registry ids CMP-nnn / AST-nnn: "Paths change; ids never do" (L124). Also R-42 (L125) and the `registry/` trigger "when a second runtime adapter exists" (L126).

**Structure**
- Migration map M-1..M-16 (L132–149).
- Count claims: "Nine of sixteen rows need NO MOVE … Seven are blocked, and every blocker is a recorded governance gate, not a design gap" (L151). The verdict repeats 9/16 and 7/16 (L235–236).
- Roadmap Stages 0–5 with quoted entry conditions (L157–164). L170: "Nothing about that ordering is proposed here; all of it is quoted."
- Open decisions OQ-K1..K6 and OQ-C1..C2 (L174–183).
- Evidence gaps G-C1..G-C7 (L187–195).
- Loop graph KnowledgeOS→PKS→PublicDigit→Evidence→KnowledgeOS (L199–206): two arrows SPECULATIVE, one EVIDENCED, one EMPTY. "The loop is the accepted destination; it is not yet an observed cycle."
- Closing claims: "KnowledgeOS does not need to be designed. It needs four triggers to fire", and this is "the last [document] that should be written before the first of them fires" (L240–241).

**Weaknesses [I]**
- The 9/7 split does not reproduce from the table.
  - Unambiguous no-move rows: M-1, M-2, M-3, M-4, M-8, M-11, M-12. That is 7.
  - M-10 ("READY NOW", meaning move to KnowledgeOS) is a pending move, not "already in permanent home". The verdict counts it both ways (L235).
  - Blocked or trigger-gated rows: M-5, M-6, M-7, M-9, M-13, M-14, M-15, M-16. That is 8.
  - M-2 and M-3 also carry gates.
- "Every blocker is a recorded governance gate" is not true for every row. M-16 is an unruled PENDING. M-13, M-15 and Stage 3 wait on empirical triggers (a second adapter, a second product).
- "Invents none … renames none" conflicts with what the document does. It introduces the label "kernel", the ids M-n and Stage 0–5, and assembles the stage table. That assembly is synthesis, not quotation (L170 vs L168). The Stage 1 entry condition "nothing" is not a quote. "Four authorities, over three weeks" is unsupported in-doc.
- Internal contradiction: ES-005.1 is "ADOPTED" (L20), but ES-001..006 are "PROPOSED ×6" (L54, G-C6).
- "Zero repository knowledge" for CAP-001 (M-10) is inferred from an absence of path strings. That is an absence-of-artifact inference.
- "Domain-free ⇒ belongs to KnowledgeOS" (M-6) rests on a vocabulary test that S0033 later says detects neither real blocker.
- The "last document" claim is falsified by S0032–S0035, all dated the same day. S0035 recommendation 9 points to S0031 as its companion.

---

## S0032 — Vision/Mission Clarification (`docs/knowledgeos/KnowledgeOS_Vision_Mission_Clarification.md`)

**Framing and status**
- Status: CANDIDATE, not adopted.
- Evidence grades: OBSERVED · INFERRED · HYPOTHESIZED, "+ INTENDED (new)" (L10).
- REV 2 (L11) corrects "Mission layer vacant" to "implicit but evidenced, not yet formalized".

**Concepts**
- Error pattern: "ARTIFACT ABSENCE ≠ CONCEPT ABSENCE" (L15). Three self-errors are listed (L17–21): strategic-DDD method, capability pattern, mission. The supporting analogy is that Linux and PostgreSQL have no mission statements (L23).
- INTENDED "WITHDRAWN" (L25–27): intent is not evidence, and adding it "overloaded the evidence dimension". It is replaced by intent "DERIVED as `f(governance-status, owner)`", with a pointer to Meta_Model §1. This applies the repo rule "new dimension / overloaded dimension / another value".
- "Mission is ENACTED", described as stronger than "implicit" (L29).
- Constraint vs mission (L33, L66–71): AIP-14 is "AN EXECUTION CONSTRAINT WITH A METRIC AND AN ENFORCEMENT TRIGGER". Rule offered: "A mission does not ship with a per-iteration metric and a review trigger. A CONTROL does."
- Vision is a hypothesis with a falsification clause, owned by the sponsor, with "zero market data points" (L41–59).
- Implicit mission is evidenced by 8 artifacts (L77–86): ES-005.3, ES-006.4, ES-006.1, Reference Architecture §1, Platform_Capability_Pattern, `registry/`, the Platform≙Adoption split, ES-005.4. Each is graded OBSERVED.
  - Inference rule: "Each of these is meaningless unless reuse across products is a purpose" (L75).
  - Derived mission sentence: "Provide a reusable engineering platform that improves the development of software systems through governed engineering knowledge" (L89).
- Definition vs mission (L97): Reference Architecture §1 is "a DEFINITION — what, not why".
- Strategy roles for PublicDigit (L114–132): product (YES), beneficiary (YES), binding constraint (YES), first adopter (INFERRED), laboratory (HYPOTHESIZED, "not found in canon").
  - Laboratory and Product Primacy are said to invert the "direction of service". "Both can be true across time horizons — but only if the horizon is stated."
- Five ownership layers (L136–150): Vision and Mission belong to the sponsor and are unratified. "Three of five layers are owned and functioning."
- Hierarchy Vision→Mission→Governance→Capabilities→PKS→Product, "every layer is populated" (L154–165). Conceptual order is not chronological: the platform was "built bottom-up; the hierarchy is being discovered top-down" (L167).
- §6 (L171–181): "the CONTRADICTION was a labelling error; the CONSTRAINT is real … Reframing … does not change what is authorized."
- Corrections back to the reviewers (L194–198):
  - "KnowledgeOS is the product" contradicts AIP-14.
  - "9 rediscoveries all from unindexed regions" is "REFUTED at n=10".
  - Kept as two claims: "No capability or check … enforces"; "no capability beyond CAP-001 operationally validated".
- Recursive property (L204–213), "four independent artifacts": Round39-02, Round39-MC, ES-006, Round38C-P2-18. Conclusion: "engineers the engineering of engineering knowledge".

**Contradictions inside S0032**
- REV 2 says the mission is not vacant (L11, L63, L222). Unrevised passages still say "Mission is vacant" (L167) and "above the vacant Mission layer" (L190). The traceability line states as a "new finding: the MISSION LAYER IS VACANT" (L233).
- INTENDED is withdrawn at L25 but still listed as an active grade in the header (L10).
- §2 says AIP-14 is a control, "not a statement of why the platform exists" (L71). §3 then uses AIP-14's "exists solely to improve delivery of PublicDigit" as the canonical purpose ("Under canon, KnowledgeOS exists to serve PublicDigit", L130) to reject "laboratory". [I] The same sentence cannot be non-purpose in §2 and purpose in §3.
- The implicit mission ("reusable … software systems", PublicDigit-free, L89–91) conflicts with the adopted "solely … PublicDigit" ruling. The conflict is dissolved only by reclassifying the ruling's wording.
- Layer sets differ. Ownership has 5 layers (Vision/Mission/Governance/Engineering/Product). Lifecycle has 6 (…/Capabilities/PKS/Product).

**Weaknesses [I]**
- The mission and its sponsor ownership are graded OBSERVED (L139, L157), but the mission is derived from 8 artifacts. That makes it INFERRED at most. This is the very grade inflation the document warns against.
- The "meaningless unless reuse across products is a purpose" rule is invalid. Reuse tests, never-a-copy rules and pre-positioned splits can serve hygiene, optionality or cost control within one product.
- "Eight independent" and "four independent artifacts" are not independent. The four Round39 / ES items come from one programme and one author.
- The Linux/Postgres analogy is rhetorical, not evidence.
- "A signature … away from resolution" (L150) understates the conflict with AIP-14.
- The "no check enforces" claim conflicts with S0034's "18 enforced lint rules" (S0034:L14).

---

## S0033 — Strategic Boundary Consolidation (`docs/knowledgeos/2026-08-02-knowledgeos-strategic-boundary-consolidation.md`)

**Concepts and claims**
- Question: does the MVK experiment change the Strategic Domain Model? Answer: "NO … It changes the EXTRACTION BOUNDARY" (L11–12). The "Strategic Domain Model" itself is never defined in the document [I].
- C-1 (L18–45): "There is no strategic-DDD method" is REFUTED.
  - Round47-00 (ADOPTED, SD-1..SD-7) and Round47-OP (BINDING) exist.
  - The nine admissible criteria are quoted (L29): semantic ownership · transactional consistency · lifecycle independence · invariants · UL divergence · team autonomy · deployment autonomy · integration characteristics · performance constraints. A seam is promoted "only if supported by **one or more**". "It seems reasonable" is inadmissible. "Semantic truth does NOT automatically imply software structure."
  - Three couplings: product path; SD-1 intake from the "Certified Domain Knowledge Release v1.0"; election examples.
- C-2 (L47–54): "MVK failed" ≠ "KnowledgeOS failed". VALIDATED: the EEP ("zero translation") and the tactical principles (rejected 5 candidates). INVALIDATED: the 15-file extraction boundary.
- C-3 (L56–71): TESTED is Knowledge→Architect→Software (n=1, FAIL). NOT TESTED is KnowledgeOS→PKS Generator (n=0).
  - Responsibilities: R-A bootstrap PKS (n=0), R-B bootstrap engineering (n=1), R-C bootstrap software (n=0).
  - The PKS Generator slot is "occupied by a person reading documents".
- §1 (L77–92): six candidates tested against the nine criteria.
  - Bootstrap: 1 criterion, "NOT a domain".
  - Governance: 4 criteria, "ALREADY EXISTS", Supporting Subdomain.
  - Knowledge Validation: capability family; H-CAT-1 refused the abstraction.
  - Runtime Integration: subsystem + ACL.
  - PKS Generation: "MERELY AN OBSERVATION".
  - Operational Learning: existing mechanism, "governed in six places", 0 traversals.
  - Result: "NO NEW STABLE STRATEGIC DOMAIN IS EVIDENCED."
- §2 (L96–120): two orthogonal axes, product ownership (stable) and architectural responsibility (internal).
  - KnowledgeOS is a PRODUCT ("potential", gate shut).
  - PKS is a "GENERATED PRODUCT ARTIFACT — an instance, one per product" ("generated" n=0).
  - PublicDigit is the business product. Adapters are not products.
  - Bootstrap/Capabilities/Governance/Validation/Learning are internal subsystems, compared to Spring modules.
  - Three levels: repository→products · product→own architecture · subsystem.
  - Rule: promoting a subsystem to a root "would encode an INTERNAL DESIGN DECISION as a REPOSITORY-WIDE OWNERSHIP BOUNDARY". No folder is proposed (ES-005.2).
- §3 (L124–133): a five-way classification grid (context / capability / subsystem / workflow / observation). "n=1 admits a candidate, never a rule" (ES-006.1).
- §4 extraction readiness (L137–154):
  - REUSABLE: EEP, the tactical principles, ES-005.2/.3, ES-004.2/.3, ES-003.3, CAP-001 core.
  - CANDIDATE: Round47-OP criteria (blocked by SD-1) and SD-2..7.
  - PublicDigit-specific: SD-1 and ES-005.1 ("names PublicDigit, hard-codes `.claude/`").
  - **ES-005.4: "UNKNOWN — deferred, therefore silent; cannot be cited as authority"** (L147).
  - Integrity Model "~90% case law"; Baseline v1.2 "~95% case law, exactly two portable rules PMR-9 · PMR-10".
  - Kernel "EXPERIMENTAL — DRAFT … contains the genesis blocker".
- "Domain-free ≠ reusable", "VALIDATED in both directions" (L158–167). It names two blocker classes, CASE-LAW DILUTION and COUPLING AT THE BOUNDARY. "Neither is detectable by searching for domain vocabulary. EAD-1's … test found neither."
- §5 conceptual context map with 5 arrows (L173–210): only 1 is evidenced. Product PKS→PublicDigit is "PARTIALLY EVIDENCED".
- §6 promotion-evidence table (L216–224), with one triggering event per candidate.
  - Example: "a second bootstrap attempt hitting the same blocker — 2 independent occurrences, ES-006.1's repetition bar".
- Open questions OQ-S1..S5 (L241–245). Recommendations "Stop consolidating", "Do not build a PKS Generator", "next move is one ARB answer, not another document" (L263–286).

**Contradictions and weaknesses [I]**
- **The criteria are applied inconsistently with the quoted rule.** The rule is disjunctive ("one or more"), yet candidates meeting 1–4 criteria are rejected (Bootstrap, Knowledge Validation, Runtime, Operational Learning). Either the threshold is not "one or more", or the rejections are invalid. The same problem recurs in S0034's criteria counts.
- Bootstrap is a "workflow" in §3 (L126) and a subsystem in §2 (L111); §3 also says it is "no" subsystem.
- Engineering Governance is both a "Supporting Subdomain" (of PublicDigit, per S0035) and an internal KnowledgeOS subsystem that is "NOT a context" (L176).
- A context map is drawn (§5) despite S0035's standing STOP (S0035:L13, L158).
- The "~90% / ~95%" figures are unsourced estimates. "Four of these six rest on n=1 or n=0" (L133): I count 5.
- "Reusable" is concluded from one foreign-domain session (n=1). The document itself says n=1 admits only candidates.
- Calling the MVK a "falsifiable one-session instrument" (L235) is a self-assessment, with no test given.

---

## S0034 — Engineering Knowledge Domain Model (`docs/knowledgeos/KnowledgeOS_Engineering_Knowledge_Domain_Model.md`)

**Framing**
- Status: CANDIDATE.
- Method: "Meaning first" (L10). No domain is derived from folders or filenames. Candidates are validated against the nine criteria and graded with the R16 Strong/Medium/Weak signal.

**Later annotations that override the body**
- **PD-3, the EKP, is a "MISSING" domain** (L12–18). `Knowledge-Constitution.md` is frozen and authoritative, owned by a named individual, with 7 schemas, "18 enforced lint rules", a graph and a portal. Treating it as an ontology layer was a "category error". It "scores 6 of 9 criteria, tying D-1 as the most strongly evidenced domain."
- **Falsification** (L20–33): "VERDICT: REVISE, then re-test. NOT canonical."
  - D-1 STRONG.
  - D-2 Strong→MEDIUM. The "own ADR series" is a shared sponsor+ARB authority, written "in one day, retrospectively". It survives only on Round39-D6's scope exclusion.
  - D-3 Strong→WEAK. "No Capability Mapping artifact exists"; "the mapping is nearly the IDENTITY function … an ACL that performs an identity mapping is not an ACL."
  - D-4 rejection STRONG.
  - D-6a MEDIUM (0 traversals).
  - D-7 WEAK, n=1. "PKS-as-projection FALSIFIED": "You do not endorse a projection at 9.9/10; you regenerate it."
- Landscape critique accepted (L35–41): classifying by artifact kind is "filename thinking wearing a taxonomy"; "Classification follows the domain model."

**Body (unrevised)**
- D-1 Governance: 5 criteria, a bounded context (L49–59). Invariants: append-only rulings; R-46 "plan approval ≠ execution authorization".
- D-2 Method: "STRONG … PRINCIPAL DISCOVERY … most strongly evidenced boundary" (L61–75). Grounds: own constitution (Round39-MC, MC-01..08, adopted), own ADR-M series, own validator and MB-39.1 baseline, a void-on-conflict consistency rule, and its own UL (F-AUTH, F-PROC…).
- D-3 Runtime: "STRONG", "a bounded context by construction" because it "hold[s] a language apart"; a Generic Subdomain; n=1 (L77–86).
- D-4 Workflow: REJECTED, with no ownership or UL of its own (L88–97).
- D-5 Capability: MEDIUM. A "knowledge kind", not a context, because H-CAT-1 stands and the pattern is "provisionally stable at n=2 conceptual instances" (L99–107).
- D-6 Evidence SPLITS into D-6a Protocol (platform, a bounded context "that has never executed") and D-6b Product Evidence (records) (L109–121). Argument: "A single owner cannot be 'the producing track' and the platform."
- D-7 PKS is a "CONTEXT TYPE": each instance is a product-side bounded context. Causal claim: conflating type and instance "is what let PublicDigit's methodology end up inside its own PKS paths" (L123–131).
- Ownership table using P1 layers METHOD / BINDING / EVIDENCE (L135–144).
- Relationships graph (L150–180). "D-1 authorizes D-2" is HYPOTHESIZED, because Round39-MC was adopted by sponsor authority (ADR-M-012). This is called "the model's most consequential open question".
- "FOUR platform-side bounded contexts. Not eight" (L196): D-1, D-2, D-3, D-6a.
- UL collisions, 12 terms (L203–216). "Baseline" has 5 senses and is called "CRITICAL", "the most severe unrecorded collision". Rule: qualify a term rather than rename it.
- Folder fit (L224–240): D-2 lives in a product path, "the model's largest misfit". D-6b is "INVERTED" (product records inside `engineering/`). "Model had to precede the index."
- Recommendations R-1..R-8 (L246–253): index by domain and P1 layer.

**Contradictions and weaknesses [I]**
- The body and closing still call D-2 strongest, on "two ADR series", and D-3 STRONG/YES (L74, L86, L187–188, L263). The header annotations falsify exactly those grounds. The body was never revised.
- PD-3 "6 of 9, tying D-1", but D-1 is scored at 5 criteria (L59).
- With PD-3 added and D-3 downgraded to WEAK, "four platform-side bounded contexts" no longer holds.
- "Four of eight candidates survive as offered" (L47): only D-1..D-3 survive unchanged (3 of 7, or 3 of 8 counting Product Domain Knowledge).
- **D-2's grounds are not among the nine admissible justifications.** "Own constitution", "own ADR series" and "own validator" are evidence that artifacts exist, the "folder exists"-type reasoning the protocol bans.
- "Bounded context by construction" infers a boundary from design intent, an inference S0032's own rule ("intent is not evidence") forbids.
- Criteria are scored by count ("5 criteria", "6 of 9") although the rule is disjunctive. Counts are not a defined metric.
- Several sense-counts are asserted, not listed (Register ×3, Qualification ×2, Verdict ×2), against the heading claim "measured, not asserted" (L201).
- The causal claim about methodology entering PKS paths is unsupported.
- Declaring 4 bounded contexts and drawing a relationship map conflicts with S0035 (no bounded context proposed, map under STOP) and S0033 (subsystems are not contexts; no new domain).

---

## S0035 — Strategic Domain Discovery (`docs/knowledgeos/2026-08-02-knowledgeos-strategic-domain-discovery.md`)

**Concepts and claims**
- Litmus: "Would this responsibility still exist if PublicDigit disappeared…?" (L10). This is said to be ES-005.3.
- Outcome 1 (L16–20): "SUFFICIENT FOR THE PRESENT MATURITY LEVEL". "No new strategic bounded context is evidenced". Six of 8 questions are answered in canon, the seventh is under a STOP, the eighth by a standard.
- Premise contradiction (L24–32): the brainstorming claim "KnowledgeOS is now your Core Domain" contradicts AIP-14 ("solely to improve delivery of PublicDigit") and the DA ruling of 2026-07-27 ("Election System is the Core Domain; this platform is a Supporting Subdomain"). The trigger is a second adopting product; "There is one" product today.
- Recurrence pattern, five occurrences of "proposing before searching" (L34–44).
- Observations O-1..O-24 (L68–93), including:
  - Reference Model §8 "Platform ≠ product", with KnowledgeOS a "potential product".
  - The conditional inversion: AIP-14 "do[es] not transfer unchanged".
  - "Product binds the platform; never forks it."
  - §7.2 promotion model, which "is itself a candidate".
  - §7.3 canonical-ownership-before-creation (CANDIDATE).
  - Six clusters that are "observations, not bounded-context claims".
  - Seven candidate aggregates; Operational Evidence is owned by the producing track.
  - O-13, the standing STOP: "no context map may be drawn … until operational evidence (C3 + pilot) exists and the ARB rules".
  - O-14, "the strategic relationship is the conclusion, not the starting point".
  - Known naming drift.
  - Charter: five gated stages, PROPOSED; "placeholder" name; "one retrospective data point, zero market data points".
  - `cross-product-research` resolves to PENDING.
  - CAP-001 grep (one hit).
  - H-CAT-1; CAP-004/006 entered already realized (R-36); CAP-001 has 0 executions.
- Interpretations I-1..I-12 with confidence levels (L97–110). I-2 states "ES-005.3 ≡ the litmus" (High). I-11 is AIP-14 exposure (Medium).
- §5 clusters tested by the litmus (L116–136): Governance, Decisions, Execution, Verification and Knowledge are platform-side; Evidence & Learning SPLIT (protocol vs records).
- §6 answers Q1–Q8 (L140–152):
  - Q3: PKS is "**A domain** … containing bounded contexts — not itself a bounded context" (AD-1).
  - Q4: the lifecycle exists (§7.2 + ES-006.1); a new one "would violate ES-005.4".
  - "MISSING: nothing at the strategic level."
- §7: no context map, "deliberately"; only the three-concern boundary diagram (L156–178).
- OQ-K1..K6 (L184–189). Decisions explicitly not taken (L193–203).
- Recommendations (L207–217), numbered 1–7, 9, 8. Recommendation 9 is a later insertion pointing to S0031. Notable items:
  - "do not restate [§8] elsewhere" (ES-005.4);
  - "Every architectural document written before that trigger is Stage-5 work performed at Stage 0".
- Closing definition (L225): KnowledgeOS is "a potential product, not yet gated, whose reusable core is already separated … by ES-005.1 and bounded by ES-005.3, and whose promotion mechanism is already ES-006.1."

**Weaknesses [I]**
- I-2 "≡" is an overclaim. One test concerns a *document adopted unchanged*, the other a *responsibility surviving*. A responsibility can survive while its document needs changes.
- The §5 litmus verdicts are bare assertions ("✅ yes"), not tests.
- "Zero repository knowledge" rests on string absence.
- "MISSING: nothing at the strategic level" is contradicted by S0033 (three bootstrap responsibilities, two never attempted), S0034 (the PD-3 missing domain; four contexts) and S0032 (mission unratified).
- Leaning on ES-005.4 as binding (I-4, §9, rec. 1) conflicts with S0033's "ES-005.4 UNKNOWN — cannot be cited as authority".
- The five-occurrence pattern is self-counted and self-reported.
- Its own rec. 6 indicts S0031–S0034, written the same day.

---

## Cross-source contradictions

1. **What PKS is.** Five different classifications:
   - "an instance, not a product" (S0031:L8);
   - "GENERATED PRODUCT ARTIFACT" (S0033:L108);
   - "A domain containing bounded contexts, not itself a bounded context" (S0035:L144);
   - "context TYPE; each instance a bounded context" (S0034:L123–131);
   - D-7 WEAK with projection falsified (S0034:L31).
2. **Number of platform bounded contexts.** None new and subsystems "NOT contexts" (S0033:L88, L176; S0035:L20, L151). Against that: four bounded contexts (S0034:L196), then five candidates once PD-3 is added.
3. **Runtime.** "ALREADY FULLY MODELLED" (S0031:L104) and an ACL/subsystem (S0033:L84) vs "no Capability Mapping artifact exists", the identity mapping, and WEAK (S0034:L28).
4. **Status of ES-005.x.**
   - ES-005.1 ADOPTED (S0031:L20) vs ES-001..006 PROPOSED×6 (S0031:L54).
   - ES-005.1 PublicDigit-specific as written (S0033:L146) vs M-1 "passes ES-005.3 → KnowledgeOS" (S0031:L134).
   - ES-005.4 cannot be cited (S0033:L147) vs cited as binding (S0031:L89; S0035:L102, L145, L209).
5. **Domain-free methodology.** Belongs in KnowledgeOS (S0031 M-6) vs ~90–95% case law, so the vocabulary test is insufficient (S0033:L148–167).
6. **The PKS→PublicDigit arrow.** SPECULATIVE (S0031:L201) vs PARTIALLY EVIDENCED (S0033:L205).
7. **Context maps.** The standing STOP (S0035:L158) vs maps drawn in S0033 §5, S0034 §3 and S0031 §9.
8. **KnowledgeOS as product.** "PRODUCT" (S0033:L107) and the vision accepted (S0031:L8) vs "potential product; the premise contradicts the DA" (S0035:L24–32) and "KnowledgeOS is the product" called a contradiction of AIP-14 (S0032:L196).
9. **Enforcement.** "No capability or check … enforces" (S0032:L198) vs "18 enforced lint rules" (S0034:L14).
10. **"Stop writing documents".** S0031:L241, S0033:L286 and S0035:L227 each say no further document is needed; the set itself continues (and S0035 cross-references S0031 after the fact).
11. **Domain model unchanged** (S0033:L230) vs "a domain is missing" (S0034:L12).

## Recurring structures to check the author against

- The three concerns (ES-005.1).
- Nine disjunctive admissibility criteria.
- The R16 Strong/Medium/Weak scale.
- The evidence grades (OBSERVED/INFERRED/HYPOTHESIZED, with INTENDED withdrawn).
- n-counts and the ES-006.1 repetition bar of 2.
- The promotion ladder Observation→…→standard.
- The 4-layer runtime model.
- The loop arrows (4 in S0031, 5 in S0033).
- Stages 0–5 and charter gates 1–5.
- Two blocker classes (case-law dilution, coupling at the boundary).
- Three bootstrap responsibilities R-A/R-B/R-C.
- Two orthogonal classification axes and three architectural levels.
- The P1 layers METHOD / BINDING / EVIDENCE.
- The Vision→Mission→Governance→Capabilities→PKS→Product hierarchy.
- The protocol vs records split of evidence.
- Type vs instance (PKS).
- Definition vs mission vs constraint.
- Absence of an artifact ≠ absence of a concept.

## Cross-cutting weaknesses [I]

- Status grades inflate repeatedly: inferences are labelled OBSERVED.
- Counts are self-asserted and often don't reproduce (9/7; 4-of-8; 6-of-9 vs 5; "four of six").
- Annotations falsify a body that is left standing (S0032, S0034).
- The admissibility rule is quoted as disjunctive but applied as a threshold.
- "Independent" evidence comes from one programme.
- Repo-internal canon (AIP-14, DA rulings) is treated as settling questions about what is true, not just about what is authorized.
