# Review C5, step 1: blind reading of S0026 to S0030

I read only the five assigned files, each in full with the Read tool. I used no grep or glob, opened no other file and wrote no files. Line anchors are to the files as read (Lnn). My own judgments are marked [I].

---

## S0026 — KnowledgeOS_Ontology_Discovery.md

**Concepts**
- **Archetypes.** There are nine: PURPOSE, DOMAIN, CAPABILITY, PROCESS, KNOWLEDGE, ARTIFACT, RUNTIME ASSET, CONTAINER (candidate) and PROJECTION (L36–46). "Archetype" is a renaming of the "nine kinds", justified because the word "already exists in the corpus" (L28).
- **Relation verbs.** The verbs are mapped onto existing edges (L34):
  - `realizes` = "represented by" (I-1)
  - `constrains` = I-11 ("knowledge constrains and informs generation")
  - `owns` = the `owner:` field / DP-binding
  - `generates` = "produces a new instance"
- **Nature predicates.** Executable, conceptual, temporal and persistent (L57–62).
- **Admission rule.** A candidate is admitted at n=1; it becomes a kind only on repetition (L29). The parsimony rule is "no new concept unless the current model cannot explain existing evidence" (L116).

**Claims and the source's strength words**
- Container is demoted to CANDIDATE because "Containers generate Projections" has n=1 (EKP `knowledge-graph.php`) (L29).
- PURPOSE being "more fundamental than Container" is a hypothesis with n=0 (L30, L126). Yet the repeated rediscovery of Mission/Vision is called "evidence of a real archetype" (L30).
- Every row of the matrix except CONTAINER is graded **OBSERVED**. DOMAIN is graded "OBSERVED *(by absence)*" (L39).
- **"THE DOMAIN ROW IS THE DISCOVERY"**: Domain "scopes meaning", owns nothing, and "DP-n owns". This is called "orthodox Evans, found empirically" (L48–51, L142).
- **"Exactly one archetype has execution as its ESSENCE: CAPABILITY"**, cited to I-11 verbatim: "Knowledge does not execute. Capabilities execute." (L59). The closing says "exactly one archetype executes" (L143). Executability of an artifact is "a REPRESENTATION-dimension property, not an archetype property" (L59).
- RUNTIME ASSET is "the ONLY archetype that constrains AT RUNTIME — deny×19 · ask×22" (L44). There are also advisory×16 (L101).
- The hierarchy stack `Purpose → Knowledge → Domain → Capability → Process → Runtime → Artifact` is **"TESTED AND REFUTED"**: 1 supported edge, 1 refuted, 4 "unevidenced" (L64–77).
- It is replaced by **"FOUR DISTINCT PARTIAL ORDERS"**: authority, production, realization and containment, "every line of it is already evidenced" (L79–86).
- The stack is called "level mixing — fourth instance … this time not mine" (L88).
- §5: every admitted relation names a decision and an "operational scar". The conclusion is that "none of the admitted ones is" ornament (L94–106).
- "The existing Relationship Ontology's 11 invariants survive the lift … unchanged" (L141).

**Formal structure**
- A matrix of 9 archetypes × 5 relations.
- Four "partial orders":
  - AUTHORITY: PURPOSE→STRATEGY→PRINCIPLE→DP-n→CAPABILITY
  - PRODUCTION: PROCESS→Knowledge/Artifacts/Evidence; CAPABILITY→Evidence; CONTAINER→Projection
  - REALIZATION: KNOWLEDGE→ARTIFACT; CAPABILITY→RUNTIME ASSET
  - CONTAINMENT: CONTAINER ⊃ Artifacts
- An edge-by-edge verdict count of 1/1/4.

**Internal contradictions and weaknesses [I]**
1. **The single-executor claim contradicts itself on the same line.** L59 says only CAPABILITY executes "as essence". The next sentence says "RUNTIME ASSETS execute at the boundary (AST-nnn, the only candidate already executable)". On the evidence actually cited, the one thing observed executing is a Runtime Asset, while Capability's executability rests on a definition (I-11). The "sharpest single answer" (L143) is a stipulation, not an observation.
2. **Labelling the I-11 evidence OBSERVED is circular.** I-11 is an invariant authored inside the same corpus. Citing a definition as evidence that the defined property holds goes from definition to "OBSERVED".
3. **The "owns" column is empty for every confirmed archetype** (L38–46), and ownership is assigned to DP-n, which L51 calls "governance-natured Knowledge". The KNOWLEDGE row says it may own "nothing" (L42). Either DP-n is Knowledge, and then Knowledge owns, or it is outside the archetype set, and then the model cannot express its central ownership relation.
4. **The AUTHORITY "order" commits the level mixing the document condemns.** It runs through STRATEGY, PRINCIPLE and DP-n, none of which is an archetype. Likewise PRODUCTION targets "Evidence", which is not an archetype (L83–84).
5. **"Refuted" overreaches, by the document's own rule.** Four of the six edges are only "no evidence". U-ONT-4 (L128) itself says empty cells are "absence-of-evidence, not evidence-of-absence". The honest verdict on the stack is "unsupported (1 edge refuted)", not "refuted".
6. **The DOMAIN discovery rests on the same weakness.** "OBSERVED by absence" (L39) is an absence finding promoted to the section's main discovery.
7. **"Partial order" is asserted, not shown.** Antisymmetry and transitivity are never checked. CONTAINMENT carries "H-3 OPEN" and PRODUCTION includes the n=1 CONTAINER edge, so "every line … already evidenced" (L79) is false on the document's own annotations. Saying there are four orders "one per relationship type" is also close to a tautology: four relation types trivially give four relations. The substantive question, whether any single relation forms one chain, is not addressed.
8. **The admission evidence is uneven.** Container is demoted at n=1. PURPOSE is said to be supported by repeated "rediscovery", but repetition of authors rediscovering a word is evidence about the authors, not about the domain. The other archetypes' n are never stated, so the n=1 rule cannot be checked as applied consistently.
9. **The representation-versus-archetype move makes the executability claim unfalsifiable.** Any executing non-Capability thing (an artifact, `identifier-check.php`) is reassigned to a "dimension".
10. **§5 is post-hoc.** The same author proposes the relations, chooses the "scars" and declares that none is ornament. No relation was tested for exclusion.
11. **"11 invariants survive unchanged" (L141) is asserted, not demonstrated in this document.**

---

## S0027 — KnowledgeOS_Operational_Evidence_Register.md

**Concepts**
- **Operational-evidence entry.** It records what was observed, the claim it bears on, provenance, and what it does not show (L7).
- **Five-section template** (L113–121): Observation, Provenance, Claims affected, Interpretation, Promotion decision.
- **Streams.** Stream A is operational evidence. Stream B is learning candidates, "generated PERIODICALLY … NEVER written directly" (L127–130).
- **Edges.** The edge R-6 is "guides product engineering". The "back-edge" is evidence → platform change.
- **Pattern candidates** (L154–158, L181–183):
  - MO-1: delivery gate
  - IF-1: "Implementation Finding" kind
  - DG-1: developer-guidance pattern
  - MO-2: ownership-before-state
- **Promotion rule.** ES-006.1 forbids promotion from a single occurrence (L40).

**Claims and strength words**
- **OE-KOS-1:** "first RECORDED behavioural observation of the guides-edge working … evidenced, not measured … Still no unguided baseline" (L34). The behaviours are graded "✅ demonstrated" from an "ARB-persona review" (L17, L23–26).
- **R-6 count rises n=1 → n=2 → n=3.** n=2 comes from OE-KOS-2 (L61) and n=3 from OE-KOS-4 (L99). The count "stands without stretching the edge" because R-6 is defined at the "ACTIVITY level" (L175).
- **OE-KOS-3:** "The BACK-EDGE, formally traversed for the first time — at the INSTRUMENTATION level … formal n=1". It is set beside "the dashboard's informal n≈3 for canon-level changes" (L80).
- DG-1's first counted instance "is a FAILURE-MODE instance" (L80, L158).
- An "Explanatory-precision heuristic" moves from count 1 to 2 (L193).
- A candidate-volume watch: at **~10–15 candidates** the lifecycle "becomes operationally necessary". The count today is 4. It is "not policy" (L197–201).
- "Pattern extraction begins only when recurrence exists" (L140). "No thresholds are policy today" (L152).

**Formal structure**
- An n-counting ladder: candidate → evidence count → counterexamples → promotion → retirement (L199).
- A volume threshold of 10–15.
- A fixed five-way partition of epistemic kinds within an entry.

**Weaknesses and contradictions [I]**
1. **The R-6 count does not establish guidance.** The n=3 records share the same developer/AI system, the same reviewers and the same product slice (WP-4B/4C, Adjudication/Contestation), so they are not independent instances. With no unguided baseline (L34), "constraint-obeying behaviour" is equally explained by an agent following its instructions. That is evidence of compliance with prompts, not of the architecture guiding. The "check" in L175 was self-administered: the author chose the edge's scope and then confirmed the count under it.
2. **The "external review" is a persona** ("ARB-persona review", L17). Its "✅ demonstrated" grades are carried over as evidence (L19–26) without independent verification of the behaviours.
3. **Counting DG-1's failure as an instance confuses instances with counterexamples.** A skipped guide plus a blind safety net is counted as the first "instance" of "implementation accompanied by developer guidance" (L158). On the register's own lifecycle (L199) it is a counterexample.
4. **The volume trigger contradicts the recurrence rule.** The 10–15 threshold counts distinct n=1 candidates, which measures recording activity, not recurrence. That conflicts with "Pattern extraction begins only when recurrence exists" (L140). The threshold is also a quoted reviewer number with no derivation.
5. **Calling OE-KOS-3 a back-edge traversal is generous.** A user audit found a hole in a reminder-hook regex, and the hook was patched (L74). Calling that "observed deficiency → recorded evidence → platform-machinery change" is a relabelling of an ordinary bug fix. The discovery was "by USER AUDIT, not by the platform" (L74), which is evidence against the platform's self-improvement loop, yet it is counted for it.
6. **The register makes conclusions while saying it records "never conclusions"** (L5). Section 3 of each entry asserts count changes on architectural edges, which are conclusions. The Observation sections also carry evaluative words such as "honest stop" and "falsely green" (L55), against the template's "behaviour only" (L117).
7. **"Explanatory precision" (L193) has no measure**, so its n=2 cannot be audited.

---

## S0028 — 2026-08-02-knowledgeos-architecture-baseline.md

**Concepts** (L24–29)
- **KnowledgeOS:** "the reusable engineering platform … output is rules and methods, never product knowledge".
- **PKS:** "one product's knowledge … never reusable — an instance".
- **Business Product.** **Running Software.** **AI Runtime:** "an ADAPTER, not a product".
- **Product Binding:** "the product-specific input contract a reusable method requires".

**Structures**
- **Relation:** "`KnowledgeOS → creates → PKS → guides → Product`", where `creates` is "not `contains`" (compiler analogy, L31–32).
- **Five tiers** T1–T5 (L38–45).
- **Six subsystems** with evidence statuses (L51–58).
- **Three-layer decomposition** METHOD / BINDING / EVIDENCE, with the METHOD test "domain-free and binding-free and evidence-free" (L71–75).
- **Portability Ladder:** "Extraction readiness = f(domain-free, binding-free, evidence-free)", in four tiers (L114–121).
- **P3 component threshold** (L142): exercised ≥1, by someone other than the originator, and a pattern from ≥2 instances.
- **P4** `Evidence → HARVEST → Candidate → Promotion → Change` (L157).
- **Lifecycle** L-1..L-6 (L175–182).
- **Gaps** G-1..G-10; **Stages** 0–5.

**Claims and strength words**
- "All five are candidates" (n=1, ES-006.1) (L91). P1 "WAS RE-DERIVED, NOT DISCOVERED" and is cognate with an existing Principle/Form/**Ambiguous** framework (L95–101).
- Subsystem statuses: three "✅ EVIDENCED — operating"; Operational Learning "EVIDENCED BUT NON-FUNCTIONING — 0 traversals"; PKS Generation "RESPONSIBILITY ONLY" (L53–58).
- L-4 "✅ EVIDENCED: 1,532 files". L-6 "⛔ EMPTY … never traversed" (L180–182).
- "Neither Tier-2 nor Tier-3 blockage is detectable by searching for domain vocabulary … A three-question test is required, not a grep" (L138).
- G-6: CAP-001 changed a decision, "the first row in which the capability changed an engineering decision" (L219–227).
- Closing: "method has been demonstrated portable and whose boundary has not" (L257). "The programme does not need more evidence to define its architecture" (L259).

**Contradictions and weaknesses [I]**
1. **"Method demonstrated portable" (L257) is contradicted inside the document:**
   - G-3: "No second adopting product".
   - G-10: "n=1 on every principle".
   - L-1 and L-2 are HYPOTHESIS, and L-2 has n=0.
   - Stage 2 Validation, the run that would test portability on another product, is open and not executed (L235).

   The Tier-1 "READY" rows (L125–129) are the author's classification of documents against three judgments. That is a prediction of portability, not a demonstration.
2. **"Does not need more evidence to define its architecture" (L259) contradicts "All five principles … rest on n=1 and are therefore candidates"** (L257) and the long gap list.
3. **The `creates` relation is stated as fact (L31) while L-2 (generate a PKS) is n=0** and "every PKS to date was hand-built" (L25). Likewise T3 is defined as "one generated PKS per product" (L42). A target architecture is presented in the same register as the current tiers.
4. **The portability "ladder" is not well-formed as a ladder:**
   - Three boolean predicates give 8 combinations, but the ladder has 4 tiers.
   - Tier 4 ignores the other two predicates.
   - Tiers 2 and 3 are not mutually exclusive: `Round47-00` is "2/3" (L131).
   - Nothing orders 2 before 3. They are parallel blockers, not rungs.
   - "f(…)" suggests a function, but it is just a conjunction.
5. **The METHOD/BINDING/EVIDENCE split is incomplete by the document's own admission.** P1 "lacks … an explicit Ambiguous class" (L101). The three-layer partition is therefore not exhaustive, yet it is used as one in L71–75 and L257.
6. **The case-law percentages and the grep finding are weak evidence:**
   - "~90% / ~95% case law" (L132–133) has no stated measurement method.
   - "Grep found neither" comes from one test, EAD-1. That justifies "grep was insufficient once", not "a three-question test is required". The three-question test itself has no reliability evidence (G-8).
7. **G-6 credits the decision change to the tool when the hand review caused it.** The tool returned INCONCLUSIVE because the "KP" series is not governed. The decision change came from a hand review and a G-1 consideration (L225), not from information the tool supplied. The event is also self-referential: writing this baseline is the operational event that the baseline counts.
8. **"Governance … operating" conflicts with G-7**, which says the "6 ES standards PROPOSED · kernel DRAFT". Existence of proposed documents is being read as an operating subsystem.
9. **The same counts are reused across several claims.** The "1,532 code files" support both the Business Product and L-4. Those files exist independently of KnowledgeOS, so they do not evidence the transition "engineering → produce software" as a KnowledgeOS-governed step. The "106 reports" are reused for Running Software, the Validation subsystem and L-5, and in S0026 L41 for PROCESS being "the most generative".
10. **The text is inconsistent with itself in two further places:**
    - "A knowledge-engineering question, not a DDD one" (L87) sits beside DDD pattern readings (Shared Kernel + Published Language, L110). The "Separate Ways with a Shared Kernel" tension is correctly caught (L171).
    - "P3/§0" (L184) points to a section the document does not have.
11. **There is an identity tension, which the document acknowledges** (L24). KnowledgeOS is "independent of project", but canon says it is a "Supporting Subdomain of PublicDigit". It is acknowledged, not resolved.

---

## S0029 — KnowledgeOS_Deferred_Architecture_Register.md

**Concepts**
- Each row has a deferred concept, a current invariant, an activation criterion, the evidence required and a status (L20).
- The pattern: "A deferred concept has an explicit engineering activation criterion" (L9).
- **Gate families by risk** (L14):
  - Value Object → code complexity
  - Port → second implementation
  - Service → repeated orchestration
  - Bounded Context → independent business behaviour
  - Analytics → sufficient dataset
  - Adaptive learning → large assessment history
  - The cap is "~5–6 canonical families".
- **Staging principle**, "three ORTHOGONAL questions": can we build it? should we build it now? should it join the platform? A row activates when the first two are yes (L16).
- **Capability versus adapter:** "`enable` operates on CAPABILITIES, never adapters" (L16). ObservationTrigger is the capability; commit, file-save, PR and CI are adapters (L60).
- **Status spectrum** REJECTED / PARKED / STAGED / WATCHED / GATED, plus ACTIVE and PARTIALLY ACTIVE. It is "deliberately NOT formalized" (L53).

**Claims and strength words**
- "CONSTITUTIONAL … delay architecture until engineering evidence justifies it" (L3).
- The kind is "PROJECTION … derived, non-authoritative" (L7). The standing rule is "no new services … unless a documented activation criterion … is actually met" (L12).
- "Every row below already operated this way; nothing is minted here" (L9).
- Update rule: rows change "only when a criterion FIRES … or an authority re-rules a criterion — never by re-reasoning" (L10).
- "Consistency replaces judgment-per-proposal" (L70).
- Thresholds:
  - MIN_COMMITS=3, ≥20%, ≥2% (L25)
  - ≥100 cycles, ≥50 assessed, ≥20 accepted (CANDIDATE values; today 1 cycle) (L29)
  - ~10–15 candidates (L27)
  - ~6 months (L30)
  - "dozens of cycles" (L28)
  - A p95-latency target "set once baseline data exists — no threshold invented before data" (L37)
  - "A registry with two entries IS over-abstraction"; a provisioner registry fires on a third provisioner (L65)

**Contradictions and weaknesses [I]**
1. **The register's own transition log shows criteria are rarely what moves rows.** Of the seven transitions (L59–65):
   - 1 is a "governance correction" that the second-adopter gate was "OVER-APPLIED" (L59). That is re-reasoning, which the update rule forbids (L10).
   - 2 are "AUTHORITY re-ruling/commission" (L60–61).
   - 2 are incident- or commission-fired rows that were never in the register ("unstaged → ACTIVE", L63–64).
   - 1 is the "FIRST CRITERION-FIRED" transition (L62), and its criterion ("an IDE-extension adapter arriving anyway") fired only because the authority commissioned that adapter in L61.
   - 1 is partial (L65).

   The register's evidence therefore refutes "Consistency replaces judgment-per-proposal". In practice, judgment and commissions drive activation, and the register records them afterward.
2. **Register rows disagree with the log.** "FileSaved / IDE trigger" stays **STAGED** at L41 but is **ACTIVE** at L60. "Real-Time ObservationTrigger adapter" is **STAGED** at L37 but **ACTIVE** at L62. The "regenerate when a criterion fires" rule (L74) was not applied.
3. **The threshold policy is contradictory.** "No threshold invented before data" (L37) coexists with ≥100/≥50/≥20 set at "today: 1 cycle" (L29), "~6 months" (L30) and 10–15 candidates (L27). None of these numbers is derived.
4. **A non-authoritative projection carries constitutional rules.** The header says "projection decides nothing" (L74) and "derived, non-authoritative" (L7). The same document carries a "CONSTITUTIONAL" purpose, a binding "Standing rule" (L12), gate families and a family cap. Those are new normative content, which also contradicts "nothing is minted here" (L9). New gate re-assignments (for example the Telemetry VO moved from the duplication gate to the complexity gate, L40) and UL renamings (L37, L65) were made in the document on the same day.
5. **The "orthogonal" questions are not shown to be independent.** "Should it join the platform? (reusable across repositories?)" depends on what was built and whether a second repository exists. Orthogonality is asserted, not checked.
6. **The capability/adapter split contradicts S0026.** Here the capability (ObservationTrigger) is a port contract and the adapters are what execute. S0026 says "Capabilities execute" (L59). The two sources disagree on what executes. See X-2.

---

## S0030 — AI_Workflow_Observation_Log.md

**Concepts**
- An entry schema: date, task type (5 values), models, outcome (ACCEPTED / CORRECTED / REJECTED), disagreement, escalation and friction (L12).
- Seven "cost signals", each mapped to an interpretation (L16–24). For example, copy/paste → "automation opportunity", human override → "policy gap", wrong routing → "orchestration need".

**Claims and strength words**
- "This log EARNS the future orchestrator, or it doesn't" (L5).
- "The log's growth is the ONLY path to an AI-Orchestration ADR" (L7).
- "This log is ENGINEERING EVIDENCE, not KnowledgeOS itself" (L8).
- The log holds **zero entries** (L30).

**Weaknesses [I]**
1. **No decision rule makes the "earns or doesn't" test unfalsifiable.** No threshold, comparison or criterion turns signal counts into "supports orchestrator" or "refutes".
2. **The signal-to-meaning mapping is presumptive.** A human override may indicate model error rather than a "policy gap". Copy/paste counts measure friction, not whether orchestration would reduce it.
3. **The "ONLY path" claim is an exclusivity rule, and S0029 shows authority commissions bypassing such paths** (L60–61, L64). It also stands next to S0029's ClaudeCodeTrigger, an automation that feeds advisories into the AI session, built with zero entries here. That is not strictly orchestration, but it is adjacent.
4. **With n=0 the log supports no claim.** Its only content is a measurement design.

---

## Cross-source contradictions [I]

**X-1. Has evidence → platform change (L-6, the back-edge) occurred?**
- S0028 (2026-08-02): "0 traversals" / "EMPTY … never traversed" (L57, L182, L208).
- S0026 (2026-08-03): "the back-edge … *(n≈3)*" (L103).
- S0027: "formally traversed for the first time" (n=1) plus "dashboard's informal n≈3" (L80).

These are three incompatible counts (0, 1 formal, ≈3 informal) within roughly two days, with no reconciliation of what counts as a traversal.

**X-2. What executes?**
- S0026: only CAPABILITY executes essentially, yet Runtime Assets execute (L59).
- S0028: the AI Runtime is "an ADAPTER" (L28).
- S0029: the capability is the port and the adapters execute (L16, L60).

The concept of "capability" is used in incompatible senses.

**X-3. Is projection generation n=1?** S0026 says only the EKP's `knowledge-graph.php` generates projections (L29, L45). S0029 calls itself a PROJECTION (L7) and cites "deterministic PHP projections (dashboard · funnel · lead times)" (L28) and a derivable "PROJECTION" (L40). Either those count, so n>1 and Container is confirmed by S0026's own test, or they are generated without any Container. The second reading supports S0026's refuting branch, the reduction of Container to scope+boundary. U-ONT-1 is answerable from the corpus, and S0026 does not see it.

**X-4. Components built against P3's own threshold.** S0028 P3 says a responsibility becomes a component only after use by a non-originator and a pattern from ≥2 instances (L142). Stage 4/5 is gated on a second adopter, and the document says "not a commitment to build any component". S0029 records components built at n=1 in one repository: `init`, `doctor`, ObservationRuntime, `knowledgeos dev` and DevelopmentEnvironmentProvisioner (L43, L59–65). It re-justifies this as "deterministic ≠ speculative". P3 is overridden without being revised.

**X-5. What KnowledgeOS is.** S0028 says KnowledgeOS outputs "rules and methods, never product knowledge" (L24). S0029 describes a code-analysis/recommendation runtime running collectors on `app/**` and a scope expansion "into general dev tooling" (L66). The product identity has drifted between the two documents.

**X-6. Inconsistent recurrence thresholds:**
- ES-006.1: ≥2 occurrences (S0027 L40, S0028 L91).
- MO-2: recurrence across multiple bounded contexts (S0027 L189, S0029 L35).
- P3: ≥2 instances and a non-originator (S0028 L142).

None of the n counts in any source checks independence: same actor, same project, same reviewer.

**X-7. Overloaded terms.**
- "Observation" means three things: operational events (S0027), code-analysis runs (S0029), and AI-workflow cycles (S0030).
- "Evidence" names a PKS layer (S0028 L75), a production target (S0026 L84) and register entries (S0027).
- "Operational Learning … 0 traversals" (S0028 L57) and S0029's recommendation→decision→assessment cycle ("today: 1 cycle", L29) may or may not be the same subsystem. Nothing says which.

**X-8. Same evidence reused across claims.** "106 reports" and "1,532 files" are each cited for several independent claims (S0026 L41; S0028 L26–27, L55, L180–181) without showing the count discriminates between them.

## Overall judgment [I]

**What survives:**
- S0028's three-layer decomposition is a useful heuristic, but it is not exhaustive and not tested on a second product.
- S0026's observation that ownership, production and realization are different relations, so "which is above" must be asked per relation, is sound as a conceptual point.
- S0029's "current invariant + activation criterion" row form is a sensible deferral device.
- The documents' self-applied n-labels are mostly honest at the item level.

**What does not survive:**
- The headline syntheses overreach their own labels: "demonstrated portable", "does not need more evidence", "exactly one archetype executes", stack "refuted", and "four partial orders … every line evidenced".
- Counting does not check independence. R-6 n=3 and the back-edge "traversal" are counted from same-actor, same-project events without a baseline.
- S0029's own transition log contradicts the claim that criteria, not judgment, drive activation.
- Citing in-corpus invariants (I-1, I-11) as "OBSERVED" evidence is the most recurrent circular move.
