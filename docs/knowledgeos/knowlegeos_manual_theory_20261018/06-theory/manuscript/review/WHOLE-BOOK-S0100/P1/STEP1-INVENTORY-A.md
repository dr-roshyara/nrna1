*Whole-book review S0100, part P1, reviewer A (opus). Step 1, blind inventory, saved by the Professor from the hand-back, unedited, 2026-10-19T19:54:51Z.*

# P1, step 1: blind inventory of five sources (reviewer for Ch. 0 and Ch. 1)

I have not opened the book (`R/BOOK-UNDER-REVIEW/`). Of the brief I read only the table "Your part's allowed references" (L54–95), after listing the brief's headings to find it (see the file list at the end). Labels: "Interp." marks my own reading, not the source's words. Line anchors are into each source.

---

## Source 1: S0032, `docs/knowledgeos/KnowledgeOS_Vision_Mission_Clarification.md` (235 lines, read in full)

**Status:** says it is CANDIDATE, generated, "never authoritative without human review" (L6–7). It reviews S0019 (L9). This is REV 2 (L11).

### Main claims
- **C1, method principle:** "ARTIFACT ABSENCE ≠ CONCEPT ABSENCE" (L15). The author says they made this error three times: no strategic-DDD method, no capability pattern, mission vacant (L17–21). Strategic DDD asks "why does this system exist?", not whether a mission document exists (L23).
- **C2, evidence vocabulary:** the grade `INTENDED` was proposed and then withdrawn. "Intent is not evidence" (L25). Evidence status stays at observed / inferred / hypothesized, and intent is derived as f(governance-status, owner) (L27). The author withdrew it by applying their own test: "a new dimension, an overloaded existing dimension, or merely another value" (L25).
- **C3, vision:** the vision is the Product Discovery Charter's working hypothesis, which carries its own falsification clause: "(to be validated or falsified — not assumed)" (L45–46). It is unapproved, sits behind five gated stages, and has "zero market data points" (L56–57). "A statement that carries its own falsifier and awaits market validation is a vision" (L59).
- **C4, AIP-14 is a constraint, not the mission:** AIP-14 (ADR-AIP-02 / R-23) has a per-iteration metric and an enforcement trigger, so it is "AN EXECUTION CONSTRAINT" (L68). The premise: "A mission does not ship with a per-iteration metric and a review trigger. A CONTROL does" (L70–71). Calling it a mission was a labelling error, and the source already contained its own disproof ("a cost control, not a boundary") (L33–35, L221).
- **C5, the mission is implicit and "enacted in machinery":** eight artifacts are cited (L79–86): ES-005.3's litmus, ES-006.4's harvest question, the ES-006.1 ladder, Reference Architecture §1 independence, Platform_Capability_Pattern, the reserved `registry/` namespace, the Platform ≙ Adoption split, and ES-005.4. Claim: each "can only exist if reuse is the purpose" (L75, L77). The mission sentence drawn from them: "Provide a reusable engineering platform that improves the development of software systems through governed engineering knowledge" (L89). It is classed as "IMPLICIT · EVIDENCED · NOT YET FORMALIZED" (L91). The mission is "waiting to be RATIFIED", not discovered (L108, L229).
- **C6, three layers kept apart:** strategic aspiration is the charter, unapproved. The implicit mission is §2.1. "Current formal authorization = none at mission level". ES-001..006, EEP and AIP-14 work at governance and execution level (L106).
- **C7, PublicDigit's role:** it is the product, the beneficiary, and a binding constraint, all graded OBSERVED (L116–118). It is the first adopter (INFERRED, L119). "Laboratory" is HYPOTHESIZED and non-canonical, because it reverses the direction of service (L120–132). Both readings can be true across time horizons "only if the horizon is stated" (L132).
- **C8, five layers and their owners:** vision and mission belong to the sponsor and are unexercised. Governance belongs to DA/ARB. Engineering belongs to sponsor + ARB, with the knowledge machinery owned by one individual. Product belongs to the product team (L136–142). "Three of five layers are owned and functioning" (L150, L226).
- **C9, hierarchy:** Vision → Mission → Governance → Capabilities → PKS → Product. "Every layer is populated" (L163). Conceptual order is not chronological order: "built bottom-up; … discovered top-down" (L167).
- **C10:** the contradiction was mislabelled, but the constraint is real. AIP-14's over-evolution review and the charter's shut gates still apply (L171–181).
- **C11, corrections issued to the review material (L196–198):**
  - "KnowledgeOS is the product" contradicts AIP-14. PublicDigit is the Core Domain and the platform is a Supporting Subdomain.
  - "9 rediscoveries all from unindexed regions" is "REFUTED at n=10".
  - "No capability or check in the repository enforces — every one is advisory".
- **C12, recursion:** "KnowledgeOS engineers the engineering of engineering knowledge", citing four artifacts (Round39-02, Round39-MC, ES-006, Round38C-P2-18) (L208–213). Whether this is an asset or a risk is left to AIP-14 (L213).

### Internal problems found in the source alone
- **REV 2 was not carried through.** Old text still says "vacant": L167 ("Mission is vacant"), L190 ("above the vacant Mission layer"), and the traceability line L233 ("new finding: the MISSION LAYER IS VACANT"). The header L10 still lists "+ INTENDED (new)", although L25 withdraws it.
- **Wrong grade for the mission.** The mission is graded **OBSERVED** at L139 and L157. Interp.: the eight artifacts are observed. The mission sentence at L89 is an inference from them (INFERRED at best), and its wording is the author's composition.
- **"Independent" is asserted, not shown.** The eight artifacts are called "independent" (L91). Interp.: several come from one standards family (ES-005.x, ES-006.x) and plausibly from one programme or author, so their independence is not demonstrated. The same holds for the "four independent artifacts" of recursion (L213), which all come from the Round 38/39 methodology series.
- **A rival explanation for the eight artifacts.** "Can only exist if reuse is the purpose" (L75) overreaches. Interp.: S0019 L54–56 offers a rival reading that fits the same artifacts: "Build portable things — but only when doing so advances this product". On that reading, portability is a *means* under a PublicDigit-serving purpose. S0032 does not rule this rival out.
- **The constraint-vs-mission premise is a stipulation.** "A mission does not ship with a metric" (L70) is a definitional premise, not evidence. AIP-14's own wording, "the platform exists **solely to** improve delivery of PublicDigit" (L68), is grammatically a purpose statement. Interp.: whether that wording is a mission or a constraint is a matter of definition, and the source settles it by definition.
- **"Every layer is populated" is weak at one layer.** L163 versus L160: the PKS layer holds "ONE, authored — not generated (n=0)".
- **A possible conflict with S0008.** "No capability or check … enforces" (L198) versus S0008 L67, "41 enforced controls live runtime-side". This may resolve if capabilities are separated from runtime controls, but "or check" at L198 makes the claim broader.
- **Paraphrase drift.** The charter quote says "across complex software systems" (L45). The restated answer says "across products" (L187). Interp.: this is a small drift.

### What the source leaves open
- Ratification of the mission, which is the sponsor's act (L108).
- The time horizon for the "laboratory" reading (L132).
- Whether recursion is an asset or over-evolution (L213).
- What falsifies the implicit mission (not addressed).

### What a rigorous theory must take, reject, or keep open
- **Take:**
  - Artifact absence does not imply concept absence, with its symmetric caution. Interp.: the reverse error, reading a purpose *into* artifacts, is equally possible, and the theory needs both directions.
  - Evidence status must not be overloaded with intent.
  - Vision, mission, constraint and authorization are separate layers.
  - Conceptual order is not chronological order.
  - Relabelling does not change authorization (L181).
- **Reject or downgrade:**
  - "Mission = OBSERVED". Downgrade to inferred.
  - The "can only exist if" necessity claim.
  - "Eight independent artifacts".
- **Keep open:** whether AIP-14 is a mission or a constraint, since it depends on definition; and the PublicDigit-serving versus reuse-serving purpose (S0019's MQ-1 is not truly dissolved, only re-layered).

---

## Source 2: S0018, `docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md` (157 lines, read in full)

**Status:** pure discovery, CANDIDATE, generated (L5–7). The question asked: "what independent dimensions describe the evolution of engineering knowledge?" (L9).

### Main claims
- **C1, two concessions (L16–17):**
  - LG-1 ("missing transitions → need state machine") was solution design, an inference from a missing implementation to a model.
  - The chain DERIVED → REVIEWED → ATTESTED → RELEASED is *qualification*, not authority. The word "Qualification" already has a recorded UL collision (verification activity versus ladder stage).
- **C2, ten mechanisms (P-1..P-10) recorded as observations** (L25–36): status, authority, the ES-006.1 ladder, methodology maturity, ADR-M status, capability lifecycle, EEP, Observation Protocol, identifier lifecycles ×4, and evidence accumulation n.
- **C3, prior art:** Reference Model §6 GEP-F1 already separates the governance-artifact lifecycle from the governed-work lifecycle, "never conflated" (L38).
- **C4, four kinds plus one non-progression (L59–65):**
  - Governance-act progression: moved by decision.
  - Evidential progression: moved by evidence, "nobody can decree it", reversible only by refutation.
  - Work-execution progression: time, a clock.
  - Composite trajectory: P-3, P-6.
  - Provenance: does not progress; an "immutable fact of origin".
- **C5:** "A GOVERNANCE-ACT progression can be granted. An EVIDENTIAL progression can only be earned" (L67–68). The repository's two declared orthogonalities (status ⟂ authority; ADR-status ⟂ maturity, L47, L102–103) are offered as earlier refusals of fusion.
- **C6, flagship:** `authorities.yaml` asks two questions in one field: "how much should I trust this and where did it come from?" (L72–73). The values `generated` and `derived` are provenance and immutable. The values `authoritative`, `provisional` and `historical` are standing, changed by act (L77–83). Authority = immutable provenance × mutable standing (L94). This "dissolves LG-1": provenance cannot have a state machine, and standing changes by acts recorded as act-logs (L85–91).
- **C7:** "evidence EARNS; governance GRANTS; promotion requires BOTH" is named as the platform's central mechanism (L105, L146).
- **C8:** P-1 and P-5 are the same kind applied to different artifact classes, so sharing a vocabulary is a parsimony question. P-3 and P-4 are different kinds (L107–108).
- **C9:** a unified lifecycle would force one engine onto all kinds (L121). No mechanism needs redesign; P-2 needs separation, and that is the ARB's call (L123).
- **Open questions (L129–134):** PM-1..PM-5, plus LG-5 (SPECIALIZES).

### Internal problems and notes
- **The guard cuts against the flagship claim.** The guard at L104, "`baseline` requires `authority ∈ {authoritative, derived}`", mixes a standing value with a provenance value. Interp.: this is evidence that the field is *used* as one standing-like dimension in practice. It does not undercut the analysis, but it shows that separating the field would break the existing guard. The source calls the coupling "healthy" without noticing this mix.
- **"Earned versus granted" is cleaner in principle than in practice.** Interp.: someone must judge that an observation is "Replicated". The source says "governance merely recognizes" (L62), and that recognition is itself an act. The distinction is between what *causes* a change and *who records it*, and the source's own wording, "nobody can decree it", overstates it.
- **The flagship idea was not new on 08-02.** S0022 L93, retained there as the "original observation", already says "derived describes provenance; authoritative describes standing". Interp.: the flagship idea predates S0018, in S0022.
- **Reversibility claims are asserted, not demonstrated.** These are "only by refutation" for evidential progression and "work concludes" (L62–63). P-10 is a "monotonic counter" (L53), but refutation would lower n or reclassify evidence, so it is not strictly monotonic.

### What the source leaves open
PM-1 (whether to separate the field), PM-2 (act-governance for standing changes), PM-3/PM-4/PM-5, and LG-5.

### What a rigorous theory must take, reject, or keep open
- **Take:** separate the *causes* of change (decision, evidence, work time) from *immutable origin*. Separate provenance from standing. Keep the work lifecycle apart from the artifact lifecycle (GEP-F1). Promotion needs both an evidential condition and an act.
- **Qualify:** "can only be earned" means the *grounds* cannot be decreed, while the *recording* is still an act. P-10 is not strictly monotonic.
- **Keep open:** whether ten mechanisms really reduce to exactly four kinds. This classification is the author's; no alternative taxonomy was tested.

---

## Source 3: S0022, `docs/knowledgeos/KnowledgeOS_Ontology_Cross_Product_Validation.md` (199 lines, read in full)

**Status:** "falsification by projection", with the ontology held fixed (L5). CANDIDATE, generated. Success means surviving "unchanged", not being proved correct (L10).

### Main claims
- **C1, four corrections made before validation (L16–19):**
  - I-11 is restated as "Knowledge does not EXECUTE. Capabilities execute. Knowledge constrains and informs generation."
  - Engineering Method as a layer is recorded as a hypothesis.
  - Runtime Adapter belongs outside the core.
  - Specialization is missing.
- **C2, method:** projection into PublicDigit (actual) and two *hypothetical* domains, a hospital information system and an ERP (L27–33).
- **C3, results (L39–52):** "8 invariant · 2 specialize · 1 disappears · 1 relationship fails". Design Policy is "the strongest result".
- **C4:** 10 of 11 invariants survive (L56–69). They are:
  - I-1 knowledge ≠ artifact
  - I-2 one invariant per capability
  - I-3 declared boundary
  - I-5 one authoritative artifact per topic
  - I-6 runtime never owns knowledge (held "vacuously" in two domains)
  - I-7 PKS never contains reusable method
  - I-8 governance precedes automation
  - I-9 status ⟂ authority
  - I-10 constitutional principle overrides
  - I-11 as corrected
- **C5, F-1 on I-4 ("a projection is derived and is cited as authority by nothing"):**
  - The original verdict is "falsified" by the discharge summary and the financial statement (L83–95).
  - The 08-02 correction says it is "UNDERSPECIFIED, not false … DP-2 CONFLATES DERIVATION WITH ATTESTATION", and the cause is that authority "has no governed state machine" (a lifecycle gap) (L73–79).
- **C6, F-2:** "one PKS per product" fails for configurable products. The model cannot tell a product from a deployment. PublicDigit masks this with `organisation_id` (L97–106).
- **C7, F-3:** there is no place for external authority (regulator, standard). "The ontology can express who decides. It cannot express who must be obeyed" (L108–117).
- **C8:** four specialization candidates, S-1..S-4 (L121–128), and four missing relationships: SPECIALIZES, CONSTRAINS, INSTANTIATES, ATTESTS (L132–137). They have one shape: "structural, not transformational", so the author takes them to be "a single coherent gap … far more likely to be a real omission" (L139–142).
- **C9:** "PublicDigit MASKED two of the three failures", so cross-product projection is "justified by evidence, not by principle" (L166–167, L191).
- **C10:** this is "the first evidence that the remaining ten invariants are about engineering rather than about elections" (L193).

### Internal problems found in the source alone
- **Stale verdicts after the withdrawal.** I-4's falsification is withdrawn (L73), yet the verdict table L159, the closing L188 and the traceability L197 still say "FALSIFIED" or "broke". CV-1 (L173) still asks "scoped … or withdrawn?".
- **The correction box is superseded elsewhere.** The box at L79 diagnoses a missing *state machine* (LG-1). S0018 later withdraws LG-1 as ill-posed. S0022 was not updated, so the two sources disagree on the cause.
- **The counts do not reconcile.** PRODUCT is marked INVARIANT (L48), yet S-2 and the verdict say PRODUCT "requires specialization" (L124, L161). Which two concepts make up "2 specialize" (L52) is ambiguous: the table shows only PRINCIPLE as SPECIALIZES. I-6, which held "vacuously" in two of three domains, is counted as surviving.
- **Thought experiments presented as evidence.** The hospital and ERP domains are hypothetical constructions by the same author (L27). Interp.: "validated by projection, not by argument" (L67) and "justified by evidence, not by principle" (L191) overclaim. A projection into imagined domains is an argument, and an exercise of the author's domain knowledge. L193's "first evidence … about engineering" overreaches for the same reason.
- **The coherence inference is circular-adjacent.** Interp.: "same shape, therefore real omission" (L142) rests on the author's own grouping of the four relations. One grouping does not make four independent confirmations.
- **I-7.** It is held here (L63), but S0008 L23 says it is "(breached)" in practice. The invariant is normative, and the observed system violates it.

### What the source leaves open
CV-1..CV-5. H-5 (Method as a layer) is neither confirmed nor refuted (L148).

### What a rigorous theory must take, reject, or keep open
- **Take:**
  - Knowledge is not the artifact.
  - Knowledge does not execute.
  - Product versus deployment (with `organisation_id` as a real observed instance).
  - External authority, obeyed rather than owned.
  - Attestation as an act distinct from derivation.
  - The methodological point that a single-product test can mask gaps.
- **Downgrade:** "evidence" from hypothetical projection should be classed as conceptual analysis or hypothesis. The survival counts should not be cited as measurements.
- **Keep open:** whether transformational relations form one class; whether I-4 is scoped or withdrawn; H-5.

---

## Source 4: S0008, `docs/knowledgeos/KnowledgeOS_Semantic_Architecture_Reconciliation.md` (110 lines, read in full)

**Status:** semantic reconciliation, CANDIDATE, generated, dated 2026-08-03 (L5–8). Success criterion: "one meaning · one responsibility · one owner · one place" (L10). Premise: the main risk is semantic drift, and "rediscovery happens because meaning is lost, not files" (L12).

### Main claims
- **C1, glossary (L20–39):** 14 concepts plus 3 forced in (Knowledge Space, Design Policy, Execution Asset).
  - KnowledgeOS is "the reusable engineering platform: governs how engineering work is planned, approved, executed, verified, evolved — product-, language-, provider-independent". It has "no single owner" and the name is a placeholder (SC-1) (L22).
  - PKS is a product-scoped Knowledge Space, a context *type*, owned by the product team. Its invariant I-7 is "(breached)" (L23).
  - Engineering Knowledge: transfers between products, is represented by artifacts, and does not execute (L24).
  - Engineering Capability: executes exactly one DP, and the script is its realization; it is "not a bounded context (H-CAT-1)" (L25).
  - Engineering Runtime is REJECTED and merged into Runtime Adapter, which is outside the core with no declared owner (L28–29).
  - Engineering Evidence is SPLIT into Protocol (platform, owner DA) and Records (product, append-only) (L30).
  - Engineering Ontology is REJECTED and merged into Documentation Ontology L-A: 31 types × 8 statuses × 5 authorities × 11 edges, 18 lint rules (L31–32).
  - L-B is a candidate and does not exist. L-C is partial (L33–34).
  - Knowledge Graph is a derived projection of L-A, "not a peer concept" (L35).
  - Engineering Kernel is a candidate; "element-portability proven; set-sufficiency FALSIFIED at n=1 (MVK)" (L36).
  - Knowledge Space has a declared boundary, an owner and a constitution, and spaces nest; "deployment = nested space" (L37).
- **C2, R-1 resolved:** "AI Execution Assets" already exist as the registry's AST model. AST-011 is a prompt template, and `runtime_moment_enum` is a closed vocabulary (L43–53). The residue is a missing link, SC-6 (L55).
- **C3, the reviewer's chain checked edge by edge (L59–69):**
  - "creates PKS" is a hypothesis, n=0.
  - "PKS contains Engineering Knowledge" is WRONG, because it violates I-7.
  - "Executed through Runtime" is imprecise: "41 enforced controls live runtime-side while every capability is advisory".
  - "Evidence improves KnowledgeOS" holds PARTLY: "n≈3 informal, ≈2 formal, PublicDigit→PKS none".
  - The corrected chain is at L72–77, with "MISSION (enacted)".
- **C4:** a drift ledger, SC-1..SC-8 (L90–99). It includes:
  - "Baseline" ×5, "Constitution" ×5, and similar.
  - "Qualification" with 3 senses.
  - authority = provenance × standing.
  - An individual as the owner of the EKP.
- **C5:** the success criterion is "met for 12 concepts" (L105).

### Internal problems and cross-source tensions
- **The success criterion is not met for two KEEP concepts.** It is said to be met for 12 concepts (L105), but KnowledgeOS has "no single owner" (L22) and Runtime Adapter has "no declared owner" (L29). Both are KEEP, so "one owner" fails for at least those two.
- **"Platform" versus "not a software system".** KnowledgeOS is "the reusable engineering platform" (L22). S0019 L173 and S0032 L97, quoting Reference Architecture §1, say "a governance architecture — not a software system". Interp.: this is unresolved category drift over what KnowledgeOS *is*.
- **The feedback loop: S0008 versus S0019.** S0008 says "Evidence improves KnowledgeOS" at n≈3 informal, ≈2 formal (L69). S0019 L139 says "EMPTY" and the retrospective has not run. The dates differ by one day, and the counting rule is not given.
- **The I-4 scoping is presumed.** The Knowledge Graph entry cites "I-4 (as scoped)" (L35), taking an answer to S0022's open CV-1 for granted.
- **"Deployment = nested space" (L37) offers a resolution to S0022's F-2 that S0022 left open.** It is stated without the projection being redone.
- **"Proven" and "falsified" for the kernel are not grounded here.** "Element-portability proven" (L36) cites no evidence in this source, and "set-sufficiency FALSIFIED at n=1" is a single trial.

### What the source leaves open
SC-1..SC-8; L-B (blocked by D-8); kernel sufficiency.

### What a rigorous theory must take, reject, or keep open
- **Take:**
  - The method rule "check before admitting a new concept" (R-1 → AST).
  - Evidence protocol versus evidence records.
  - Capability (advisory) versus runtime controls (enforced). This is an important observed asymmetry, if the 41 count is verified.
  - PKS as a context type that excludes reusable method.
  - Qualifying overloaded terms rather than renaming them.
- **Reject:** "criterion met for 12".
- **Keep open:** what KnowledgeOS *is* (platform, governance architecture, or product-to-be); the feedback-loop count; product versus deployment.

---

## Source 5 (my choice): S0019, `docs/knowledgeos/KnowledgeOS_Mission_Discovery.md` (199 lines)

**Depth:** I meant to skim it, but it is short and I read it in full.

**Why I chose it:** S0032 is a review of this document and declares its central finding withdrawn. Ch. 0/1 presumably treat what KnowledgeOS is and what its mission is. Only by reading S0019 can I check whether S0032's correction is fair, and whether a rival reading of the mission survives.

### Main claims
- **C1, the order rule:** "mission → domains → ontology → taxonomy → indexing → automation". The track ran it backwards (L17). The source of "the rule" is not cited.
- **C2, the banner added after the withdrawal (L23–33):** "two missions" is withdrawn, and the source points to S0032. But the banner's own "Correct model" line at L31 still reads "Mission (VACANT)", contradicting the same banner's "NOT VACANT" (L29).
- **C3, the original finding (L37–58), still the body text:**
  - M-A, AIP-14, is ADOPTED. M-B, the charter, is PROPOSED.
  - The adopted mission sentence combines AIP-14's purpose with Reference Architecture §1's form (L46–48).
  - The reconciliation: "AIP-14 constrains WHY work is undertaken. The Reference Architecture constrains WHAT is built … a cost control, not a boundary" (L54–56).
  - Under M-A, KnowledgeOS is "a supporting capability that is on time" (L58).
- **C4:** responsibilities R-1..R-8, each traced to an artifact (L64–73). R-6, harvest, has "0 traversals". R-7, runtime vocabulary, is "asserted; the artifact is missing". R-8 is "does not approve itself".
- **C5:** non-responsibilities N-1..N-10 (L79–90). N-1, business knowledge, is "BREACHED", because ES-005.1 names PublicDigit and hard-codes `.claude/`. N-10 says no orchestrators or state machines "until operational evidence demonstrates" a need.
- **C6:** three *authority systems*: policy (DA/ARB), method and implementation (sponsor + ARB, plus an individual), and domain knowledge (the product team). "No authority spans all three, and none is designated to reconcile them" (L100–107).
- **C7:** four boundaries: KnowledgeOS, PKS, AI Runtime, Product (L111–116).
- **C8:** PD-3 is split. The machinery is a Generic Subdomain; the Knowledge Constitution is Supporting. It stays a candidate "until it survives a second product" (L122–130).
- **C9, the lifecycle loop (L134–149):**
  - creates PKS: HYPOTHESIZED, n=0.
  - guides Product: partly evidenced, with no unguided baseline.
  - Product generates Evidence: OBSERVED, with 106 verification reports and 1,532 code files.
  - Evidence improves KnowledgeOS: EMPTY.
  - "The cycle is the mission's hypothesis, not its description" (L149).
- **C10:** MQ-1..MQ-7, including MQ-7: "What would falsify M-A? A mission with no falsifier is not a mission" (L163).
- **C11:** almost every architectural difficulty in the track comes from "designing under M-B while governed by M-A" (L183).

### Interp.: why this source matters to the theory
- S0019's reconciliation (L54–56) gives a rival explanation for S0032's eight "mission" artifacts: portability as a means under product primacy. S0032 relabels AIP-14 as a constraint but does not refute this alternative. The disagreement between S0019 and S0032 is therefore a live open question, not a settled correction.
- Note also that S0032's own criterion that a vision "carries its own falsifier" (L59) mirrors S0019's MQ-7. Neither document gives a falsifier for the "implicit mission".
- **Inconsistency:** S0019's banner L31 says "Mission (VACANT)" while L29 says not vacant. The "two missions" body text is left unmarked as superseded, apart from the banner.

### What a rigorous theory must take, reject, or keep open
- **Take:**
  - Responsibilities and non-responsibilities as boundary devices.
  - Authority as several *kinds*, not one owner list.
  - "Cycle as hypothesis, not description".
  - Falsifiability as a requirement for a mission claim.
  - Recorded breaches (N-1, I-7) as evidence that norms and observed state diverge.
- **Keep open:** MQ-1 in its substance (PublicDigit-serving versus reuse-serving); who reconciles the three authority systems; the provenance of "the order rule".

---

## Cross-source themes to check against Ch. 0/1 (all Interp.)

1. **The mission claim is graded OBSERVED but rests on inference** (S0032). A rival reading exists (S0019 L54–56). Check whether the book states the mission as established.
2. **Stale text survives across sources.** Four documents carry withdrawn or partly withdrawn claims next to their corrections: "vacant" in S0032 and S0019, "I-4 falsified" in S0022, and LG-1 as the cause in S0022. Check which version the book quotes.
3. **What KnowledgeOS is, is unresolved.** It is called a platform (S0008), a governance architecture that is not a software system (S0019, S0032 quoting the Reference Architecture), and a product-to-be (the charter, as vision).
4. **Provenance × standing first appears in S0022 L93, before S0018.** Check how the book attributes priority.
5. **The feedback loop has conflicting counts:** EMPTY (S0019) versus n≈3 / ≈2 (S0008).
6. **Enforcement:** "nothing enforces" (S0032 L198) versus "41 enforced controls runtime-side" (S0008 L67).
7. **Hypothetical projections are presented as evidence** (S0022).
8. **"Independent" artifacts are counted without an independence test** (S0032 L91, L213).

---

## Every file opened and every search run

**Files opened (Read):**
- `R/P1/BRIEF.md`, lines 54–95 only (the allowed-references table).
- `docs/knowledgeos/KnowledgeOS_Vision_Mission_Clarification.md` (S0032, full)
- `docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md` (S0018, full)
- `docs/knowledgeos/KnowledgeOS_Ontology_Cross_Product_Validation.md` (S0022, full)
- `docs/knowledgeos/KnowledgeOS_Semantic_Architecture_Reconciliation.md` (S0008, full)
- `docs/knowledgeos/KnowledgeOS_Mission_Discovery.md` (S0019, full)

**Commands and searches (Bash):**
1. `grep -n "^#" BRIEF.md` in `R/P1/`. This lists the brief's heading lines only, to locate the table. It showed heading titles; I read no other section content.
2. `wc -l` on the four step-1 sources.
3. `grep -n "KnowledgeOS_Mission_Discovery\|KnowledgeOS_Vision_Mission\|Engineering_Progression_Model\|Ontology_Cross_Product\|Semantic_Architecture_Reconciliation" docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. All five sources are listed as `match`.
4. `wc -l docs/knowledgeos/KnowledgeOS_Mission_Discovery.md`
5. `git log --format='%h %ad %s' --date=short -- docs/knowledgeos/KnowledgeOS_Mission_Discovery.md`. This is an allowed source file. Result: one commit, d61bf5e84, 2026-08-04.

**Not opened:** the book copy `R/BOOK-UNDER-REVIEW/`, `R/REVIEWER-NOTE.md`, any granted `docs/implementation/` file, and any other file under `R/`. I reached no file from item 3 of the reviewer note and no forbidden file. I wrote, moved or changed nothing.
