# Phase 1 blind extraction: S0016–S0020

## Access and completeness
- I read exactly five files, each in full with one Read call per file. I opened, listed or searched nothing else and wrote no files.
- Line totals shown by the Read tool:
  - S0016 `docs/knowledgeos/KnowledgeOS_Meta_Model.md`: **178 lines**
  - S0017 `docs/knowledgeos/KnowledgeOS_Engineering_Decision_Model_Discovery.md`: **130 lines**
  - S0018 `docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md`: **158 lines**
  - S0019 `docs/knowledgeos/KnowledgeOS_Mission_Discovery.md`: **200 lines**
  - S0020 `docs/knowledgeos/KnowledgeOS_Ontology_Architecture_Classification.md`: **171 lines**
- Item format: ID · TYPE · the source's own strength word (or "—") · line anchor · short verbatim quote (emphasis and emoji removed) · what it means in this document only. My own interpretations are marked [I].
- General note [I]: all five files cite other documents, rulings, YAML files, counts and dates that I was not allowed to open (RQ-002, `statuses.yaml`, `authorities.yaml`, AIP-14, `Engineering_Decision_Model.md`, R-1..R-77, and so on). Every such citation below is recorded only as "the file says". None was verified.

---

# S0016 — KnowledgeOS — Canonical Meta-Model (178 lines)

## (a) Status, authority and dates
- S16-01 · STATUS · CANDIDATE · L6 · "CANDIDATE — NOT ADOPTED" · The document is not adopted.
- S16-02 · STATUS · — · L7 · "Generated — never authoritative without human review" · The authority level is "generated".
- S16-03 · STATUS · — · L5 · "No new principle · no new document class · no repository redesign · no implementation · no tooling · no ADR." · The document limits its own scope.
- S16-04 · DATE · — · L8 · "Strategic DDD Architect, 2026-08-02" · The commission is dated 2026-08-02. Its brief is to "derive the canonical meta-model all future artifacts must conform to".
- S16-05 · STATUS · — · L9 · "COMPLETE, awaiting ARB" · Its main source, RQ-002, is described as complete but not adopted. A second source, `Engineering_Platform_Knowledge_Metamodel`, is "CANDIDATE, gate OQ-ENG-004".
- S16-06 · ANNOTATION · — · L23 · "ANNOTATED 2026-08-02 — THREE CORRECTIONS" · A dated annotation sits above the body. Its record is `KnowledgeOS_Relationship_Ontology.md` (L25).
- S16-07 · ANNOTATION overrides body · — · L29 · "§2's six concepts are not siblings" · Correction 1 calls §2 "level mixing": four ontological layers (Purpose, Platform, Representation, Application) were listed as one set.
- S16-08 · ANNOTATION overrides body · — · L30 · "PKS is a CONTAINER, not a layer below ARTIFACT." · Correction 2 admits "KNOWLEDGE SPACE" as a container concept, defined by a declared boundary (`scope.include`).
- S16-09 · ANNOTATION · — · L31 · "This document conflates two levels — level mixing again." · Correction 3 says §1 is a meta-model and §2 is an ontology.
- S16-10 · ANNOTATION overrides body · — · L33 · "MISSION → STRATEGY → PRINCIPLE → DESIGN POLICY (DP-n) → CAPABILITY → KNOWLEDGE → ARTIFACT" · The annotation says the §2 chain is incomplete and adds STRATEGY, PRINCIPLE and DP-n.
- S16-11 · STATUS · — · L177 · "Submitted to the Decision Authority. Nothing in this document executes." · The document is a submission, not a decision.
- [I] Override status: the annotation (L23–33) contradicts the body, but the body was never revised. §2's table (L77–84), the hierarchy at L102–106 (which still places PKS below ARTIFACT) and the closing (L165–171) all stand unchanged. A reader of the body alone gets the superseded model.

## (b) Definitions and concepts introduced
- S16-12 · DEF · — · L41 · "D-I · NATURE — what kind of thing it is" · Dimension I. Source: RQ-002.
- S16-13 · DEF · — · L42 · "D-II · REPRESENTATION — how it is encoded" · Dimension II. Source: RQ-002.
- S16-14 · DEF · — · L43 · "D-III · GOVERNANCE-STATUS — lifecycle position" · Dimension III. Source: RQ-002 plus `statuses.yaml` (8 values, executable).
- S16-15 · DEF · — · L44 · "D-IV · AUTHORITY-SCOPE — how far it reaches" · Dimension IV. Sources: RQ-002, ES-005.1 and the placement scopes.
- S16-16 · DEF · NEW · L45 · "D-V · EVIDENCE-STATUS — observed · inferred · hypothesized" · A new dimension. The document says of it: "my working vocabulary; NOT a field anywhere".
- S16-17 · DEF · NEW · L46 · "D-VI · OPERATIONAL-STATE — enforced · advisory · unexercised" · A new dimension: "implied by deny/ask/hooks; never formalized".
- S16-18 · DEF · — · L61–63 · "NATURE  ×  REPRESENTATION  ×  GOVERNANCE-STATUS  ×  AUTHORITY-SCOPE" · The "canonical model" is the product of six dimensions.
- S16-19 · DEF · — · L66–68 · "intent             = f(GOVERNANCE-STATUS, OWNER)" · Three derived functions, which are not axes: lifecycle regime = f(NATURE) and validation method = f(REPRESENTATION, NATURE), both from RQ-002, and intent, which is new in this document.
- S16-20 · DEF · — · L79 · "state why the platform exists" · MISSION. Lifecycle "enacted → candidate → ratified", currently "enacted, unratified". Owner: the sponsor. It is the root.
- S16-21 · DEF · — · L80 · "hold rules, methods and decisions that transfer between products" · ENGINEERING KNOWLEDGE. Lifecycle: ES-006.1. Owners: DA and sponsor + ARB.
- S16-22 · DEF · — · L81 · "protect exactly ONE invariant" · ENGINEERING CAPABILITY. Lifecycle: observed → candidate → designed → realized → evidenced → promoted. Its shape is "FROZEN under PGP-01..05".
- S16-23 · DEF · — · L82 · "CARRY knowledge in a representation — it is never the knowledge" · ENGINEERING ARTIFACT. Lifecycle: `statuses.yaml`, 8 states, lint-enforced.
- S16-24 · DEF · — · L83 · "hold one product's concepts, language, contexts and bindings" · PRODUCT KNOWLEDGE SPACE. Lifecycle: "authored (today) · generated (hypothesis, n=0)".
- S16-25 · DEF · — · L84 · "deliver business value" · PRODUCT.
- S16-26 · DEF · — · L88 · "One is the thing; the other is a carrier of it." · The Knowledge ≠ Artifact split.
- S16-27 · DEF · — · L119 · "TRUST/PROVENANCE = authorities.yaml" · Named as a "fifth concern" with the values authoritative · derived · generated · historical · provisional.
- S16-28 · DEF · — · L30 · "KNOWLEDGE SPACE is now admitted as the container concept" · Introduced in the annotation only.

## (c) Claims and findings
- S16-29 · CLAIM · "proved" · L42 · "proved by RQ-002's first smoking gun" · REPRESENTATION is orthogonal because "the same rule landed in different types depending on its encoding". The proof is borrowed from RQ-002.
- S16-30 · CLAIM · "YES" · L45 · "ten rediscoveries and four falsifications happened because claims were not graded" · Offered as the reason EVIDENCE-STATUS is necessary. No evidence for the counts is given in this file [I].
- S16-31 · CLAIM · "YES" · L46 · "it is what "0 traversals" and my "0 of 11 enforcing" error both measured badly" · Offered as the reason OPERATIONAL-STATE is necessary.
- S16-32 · CLAIM / DECISION · REJECT · L47, L54 · "Ratified" and "approved" are the same axis. · INTENT-STATUS fails orthogonality because it maps onto the existing `statuses.yaml` states.
- S16-33 · CLAIM · — · L55 · "INTENT = (GOVERNANCE-STATUS × OWNER)" · Intent decomposes into existing fields.
- S16-34 · CLAIM · — · L71 · "Intent is separated by DERIVATION, not by adding an axis." · Answers the commission's requirement to separate intent.
- S16-35 · CLAIM · — · L90 · "This single distinction makes my recurring error UNSTATABLE." · Three earlier errors ("there is no strategic-DDD method", "no capability pattern", "the mission layer is vacant") cannot be phrased in this model (L94–96).
- S16-36 · CLAIM · — · L98 · "A meta-model earns its place by making a known error class impossible to phrase. This one does." · The document's own success measure.
- S16-37 · CLAIM · — · L108 · "Documents are no longer the centre of the model. They are the bottom of it." · Presented as the reviewer's §8, adopted.
- S16-38 · CLAIM · — · L119 · "The repository was doing orthogonal dimensions before this commission asked for them." · Rests on a quote from `statuses.yaml`: "status … is INDEPENDENT of authority."
- S16-39 · CLAIM · "tested" · L10 / L121–133 · "every existing document classifiable without changing the model" · The stated success criterion. It was tested on six artifacts (L127–132).
- S16-40 · CLAIM · "proof" · L134–135 · "GOVERNANCE-STATUS and OPERATIONAL-STATE vary independently — which is the orthogonality proof" · Two data points: Round39-MC is frozen but enforced, and CAP-001 is realized but advisory.
- S16-41 · CLAIM · — · L132 · "This document | model | prose | draft/candidate | platform | inferred | unexercised" · The document grades its own evidence as "inferred".
- S16-42 · CLAIM · — · L165 · "four dimensions come from RQ-002 and the executable schema" · Repeated in the closing and in the traceability line (L175).
- S16-43 · CLAIM · — · L17 · "The platform is not describing its mission — it is already behaving according to it" · Concession 2: "Mission is ENACTED" replaces "mission is implicit".
- S16-44 · CLAIM · — · L33 · "observable in the Capability Catalog's 1:1 CAP→DP binding" · The annotation's grounds for adding DP-n.
- [I] S16-45 · GAP · — · L37 vs L39 · "Each candidate is tested against CLAUDE.md's three-part rule: orthogonal · necessary · sufficient" · The test table has only "Orthogonal?" and "Necessary?" columns. No sufficiency test is shown anywhere, yet L145, L165 and L175 say each dimension passes all three tests.
- [I] S16-46 · OVERREACH · — · L10 vs L134 · "All six classify." · The criterion covers "every existing document" but the test used six hand-picked artifacts, one of which is the document itself. Under the document's own rule, "inferred" is the right grade for this claim.
- [I] S16-47 · GAP · — · L119 vs L61–63 · TRUST/PROVENANCE is named as a separate, already-orthogonal concern, but it is not one of the six dimensions in the canonical formula. It is unclear whether it is part of the model.
- [I] S16-48 · GAP · — · L40–46 · "Sufficient" for the four existing dimensions is backed only by citations to RQ-002 and `statuses.yaml`, which this file does not contain.

## (d) Rules, constraints, decisions
- S16-49 · DECISION by author, proposed · WITHDRAWN · L16 · "INTENDED is WITHDRAWN as a fourth evidence grade" · The author's concession under the CLAUDE.md dimension rule.
- S16-50 · RULE cited · — · L16 · "a new dimension, an overloaded existing dimension, or merely another value" · The CLAUDE.md model-integrity rule, used as the admission test.
- S16-51 · DECISION by author, proposed · REJECT · L47 · INTENT-STATUS is rejected. The proposal came from "the reviewer".
- S16-52 · RULE · — · L75 · "No concept is defined by a folder." · Concepts are defined by responsibility, lifecycle, ownership and dependencies.
- S16-53 · RULE · — · L123 · "If any row required changing the model, the model would be wrong." · The document's falsification condition.
- S16-54 · CONSTRAINT · — · L141–145 · "It does not adopt RQ-002 — that is the ARB's act" · Also: no principle, document class or folder, nothing added to `knowledge-types.yaml`, and MQ-1 not resolved.
- S16-55 · DECISION adopted by author (not by governance) · "Adopted" · L164 · "Mission is ENACTED" · "Adopted" here means the author accepted the reviewer's wording. It is not a governance adoption [I].

## (e) Open questions, gaps, contradictions inside the file
- S16-56 · OQ · — · L151 · "MM-1 | Adopt RQ-002?" · Owner: ARB. The model "rests on it".
- S16-57 · OQ · — · L152 · "MM-2 | Should EVIDENCE-STATUS and OPERATIONAL-STATE become frontmatter fields?" · Owners: ARB and PD-3's owner.
- S16-58 · OQ · — · L153 · "MM-3 | Is intent = f(governance-status, owner) sufficient" · Owner: ARB.
- S16-59 · OQ · — · L154 · "MM-4 | Does OPERATIONAL-STATE belong to the artifact or to the rule the artifact carries?" · Owner: ARB.
- S16-60 · OQ · — · L155 · "MQ-1 (carried) | Ratify the enacted mission" · Owner: the sponsor.
- [I] S16-61 · CONTRA · — · L103 vs L81 / L33 · The hierarchy "MISSION ▸ ENGINEERING CAPABILITY ▸ ENGINEERING KNOWLEDGE" puts Capability above Knowledge. The §2 table says CAPABILITY "Depends on" ENGINEERING KNOWLEDGE. The annotation chain orders CAPABILITY → KNOWLEDGE → ARTIFACT. The direction of the relation is ambiguous.
- [I] S16-62 · CONTRA · — · L104–105 vs L30 · The hierarchy still shows PKS below ARTIFACT, which correction 2 rejects.
- [I] S16-63 · CONTRA · — · L154 vs L127–135 · MM-4 asks whether OPERATIONAL-STATE belongs to the artifact at all. The §4 classification and the "orthogonality proof" both assign it per artifact. If MM-4 resolves to "the rule", the proof's two data points change meaning.
- [I] S16-64 · CONTRA · — · L79 · The MISSION lifecycle "enacted → candidate → ratified" is a governance-type progression placed on a concept, while the same document says D-III lifecycle comes from `statuses.yaml`. Two lifecycle vocabularies apply to the same concept.
- [I] S16-65 · GAP · — · L128 vs L131 · "frozen" (Round39-MC) and "approved" (`settings.json`) are used as GOVERNANCE-STATUS values for non-document items. A `settings.json` `deny` is not a knowledge-card document, so it is unclear whether `statuses.yaml` actually applies to it.

## (f) Relations to other documents named
- RQ-002_Knowledge_Meta_Model.md (L9, L42–44, L66–67, L142, L151): main source; complete, unadopted.
- Engineering_Platform_Knowledge_Metamodel (L9): candidate, gate OQ-ENG-004.
- The "L-A schema" (L9, L144, L152), which corresponds to S0020's L-A [I].
- KnowledgeOS_Relationship_Ontology.md (L25): record of the annotation.
- CLAUDE.md (L16, L37).
- statuses.yaml (L43, L54, L119), authorities.yaml (L119), knowledge-types.yaml (L144).
- ES-005.1 (L44), ES-006.1 (L80), PGP-01..05 (L81).
- CAP-001 README, Round39-MC, PKS_..._M4, settings.json (L127–131).
- Capability Catalog (L33).
- "D-1..D-7's key-collision proof" (L144), which points to S0020 §3 [I].
- "the reviewer's §8" (L19, L100).

---

# S0017 — KnowledgeOS — Engineering Decision Model Discovery (130 lines)

## (a) Status, authority and dates
- S17-01 · STATUS · CANDIDATE · L6 · "CANDIDATE — NOT ADOPTED".
- S17-02 · STATUS · — · L7 · "Generated — never authoritative without human review".
- S17-03 · STATUS · "hypothesis" · L5 · "every conclusion a hypothesis until evidenced" · The document places all of its own conclusions at hypothesis strength.
- S17-04 · DATE · — · L8 · "Decision Model Discovery, 2026-08-03" · The commission date. It is the latest of the five files.
- S17-05 · STATUS · — · L9 · "this pass CONCLUDES the ontology arc by binding it to decisions; it grows nothing" · The document claims to be lawful under the freeze ("Freeze lawfulness").
- S17-06 · DATE (external) · — · L14 · "DRAFT, ARB-ACCEPTED 2026-07-11" · Status and date given for `Engineering_Decision_Model.md`.
- S17-07 · DATES (external) · — · L55 · "methodology freeze 2026-08-01 · architecture freeze 2026-08-03 · corpus freeze Phase A" · The three freezes cited as evidence.
- S17-08 · STATUS · — · L129 · "Nothing in this document executes. The docket remains the agenda."
- No annotations inside the file.
- [I] S17-09 · TENSION · — · L5 vs L39, L60 · The document calls every conclusion a hypothesis, yet labels two items "FINDING". The strength words conflict.

## (b) Definitions and concepts introduced
- S17-10 · DEF · — · L36 · "ENGINEER decisions (resolution: apply a governed procedure)" · Resolved by the working engineer, human or AI.
- S17-11 · DEF · — · L37 · "GOVERNANCE decisions (resolution: an authority act)" · Resolved by DA, ARB or sponsor. The enacted record is R-1..R-77, DR-1..DR-8, the ADR series and the Decision Docket (D-1..D-9b, 11 packages).
- S17-12 · DEF (cited) · — · L19 · "Question → Decision → Authority → Procedure → Evidence" · The universal pattern, quoted from the Decision Model.
- S17-13 · DEF (cited) · — · L20 · "DetermineConcern · DetermineArtifactLifecycle · DetermineReusePotential · DetermineArtifactType · DeterminePlacement · DetermineApplicableStandards · DetermineQualificationMethod · DeterminePromotionPath" · The eight cataloged decisions.
- S17-14 · DEF · — · L92 · "for authority decisions, the platform's product is DECISION-READINESS, not decisions." · Decision-readiness is the platform's output.
- S17-15 · DEF (used, not defined here) · — · L68–76 · I-1, I-4, "PROJECTION + attestation", "CONTAINER boundary", "the four partial orders", "NATURE: normative force (the demoted "Governance Asset")", archetypes (L52, L58) · Ontology elements taken from documents outside this set. They are not defined in this file [I].
- [I] S17-16 · TENSION · — · L5 · "No new concept" · The ENGINEER/GOVERNANCE decision split and "DECISION-READINESS" read as new framing. The document says the vocabulary already exists (L32).

## (c) Claims and findings
- S17-17 · CLAIM · — · L12 · "THE DECISION MODEL ALREADY EXISTS, AND IT IS CANON" · The decision model is `engineering/architecture/reference/Engineering_Decision_Model.md`, "pointed to by the FROZEN runtime binding in .claude/CLAUDE.md".
- S17-18 · CLAIM (cited) · — · L21 · "No new engineering decisions will be added unless operational evidence demonstrates insufficiency" · The stopping rule, quoted.
- S17-19 · CLAIM · — · L24 · "decisions-changed-by-tool: 1" · CAP-001's success metric. Used to argue that "decision-centricity is not a proposal".
- S17-20 · CLAIM · — · L26 · "it would have been occurrence #12 of proposing-before-searching. Caught." · The commission is therefore re-read as a reconciliation.
- S17-21 · CLAIM · — · L26 · "Problem → Decision → Knowledge → Architecture → Implementation" · The reviewer's sketch, "also already canon".
- S17-22 · FINDING · FINDING 1 · L39 · "the platform has a decision CATALOG for engineers and only a decision LOG for governance." · The governance side is discoverable only by reading 77 rulings. Whether this is a defect is left open (U-DM-1).
- S17-23 · CLAIM · — · L49 · "the one MECHANIZED decision" · DeterminePlacement, through `doc-placement.php` (exit 2 = escalate).
- S17-24 · CLAIM · — · L54 · "two colliding vocabularies, unscoped" · Verdict vocabularies: ES-003.1 vs CAP-001, "MVK reach #7".
- S17-25 · CLAIM · — · L55 · "each time by declaration, under no named authority, procedure, or reversal condition" · FREEZE/UNFREEZE extends nothing. It was used n≥3 times and is "the strongest insufficiency candidate".
- S17-26 · CLAIM · — · L56 · "PM-6 asked exactly this and stands OPEN" · RETIRE/PRESERVE extends nothing; it is the second insufficiency candidate.
- S17-27 · CLAIM · — · L57 · "capability governed, trigger ungoverned" · When to commission a CHALLENGE (C-12, kernel) is not stated anywhere.
- S17-28 · CLAIM · — · L58 · "implicit — handled, never patterned" · Terminology (UL) adoption.
- S17-29 · FINDING · FINDING 2 · L60 · "exactly TWO recurring decisions extend nothing in the existing set — FREEZE and RETIRE" · Submitted as insufficiency evidence, "not as new catalog entries, which only the ARB may mint".
- S17-30 · CLAIM · — · L68 · "is UNASKABLE unless knowledge and its file are different things." · I-1 underpins the DetermineArtifactLifecycle deletion test.
- S17-31 · CLAIM · — · L69–76 · Nine ontology elements, each mapped to one decision · For example, AUTHORITY-SCOPE serves DetermineConcern/Placement, and GOVERNANCE-STATUS serves DeterminePromotionPath.
- S17-32 · CLAIM · — · L73 · "the MVK FAIL = this decision made without the model" · Cited as proof that the CONTAINER boundary matters.
- S17-33 · CLAIM · — · L76 · "the thin back-edge (n≈3) is visible only through them" · About the four partial orders.
- S17-34 · CLAIM · — · L82 · "was NEVER CONSULTED (occurrence #11)" · The frozen Phase-02 bounded-context map was never consulted, and "No recorded decision changed because something was classified into PD-1..PD-6". The caveat is that domains served as scaffolding.
- S17-35 · CLAIM · — · L83 · "an unratified purpose CANNOT yet support decisions" · PURPOSE is "enacted, UNRATIFIED — D-5 pending". Judgment suspended.
- S17-36 · CLAIM (answer) · — · L90 · "The eight indexed Engineer decisions — resolved daily through governed procedures — and the Governance decisions it PREPARES BUT NEVER MAKES." · The answer to the commission's question.
- S17-37 · CLAIM · — · L92 · "77 rulings were made BY humans" · Each ruling "arrived shaped: evidence graded, options separated, unknowns carried". No sample of rulings is shown [I].
- S17-38 · CLAIM · — · L122 · "ontology and decision model are already load-bearing for each other" · Closing, DDD result.
- [I] S17-39 · OVERREACH · — · L90 · "resolved daily" · Contradicted by U-DM-4 (L110), which says there is no evidence the catalog is consulted as a whole. Only DeterminePlacement is shown to be used constantly.

## (d) Rules, constraints, decisions
- S17-40 · RULE (cited, ARB-accepted per the file) · — · L21 · the stopping rule, as above.
- S17-41 · RULE (cited) · — · L22 · "the AI evaluates and recommends — authority remains with governance".
- S17-42 · RULE (method used here) · — · L44 · "Only what extends nothing may count as insufficiency evidence."
- S17-43 · PROPOSAL, routed · — · L98 · "docket — rider on D-8" · The criterion "every ontology element must answer: which decision does it improve?" is called "the genuinely NEW part of the review".
- S17-44 · PROPOSAL, recorded · — · L99 · "the freeze declaration itself is a governance act" · The recommendation to freeze ontology discovery.
- S17-45 · PROPOSAL, routed · — · L100 · "docket — D-8" · The promotion ladder applied to the ontology.
- S17-46 · DECISION by author · — · L123 · "no new decision, no new concept" · Nothing minted. Three riders routed to D-8.
- S17-47 · CONSTRAINT (cited) · — · L50 · "CANDIDATE status, pilot-gated" · The DetermineArtifactLifecycle deletion test.

## (e) Open questions, gaps, contradictions inside the file
- S17-48 · OQ · — · L107 · "U-DM-1 | Is the catalog/log asymmetry (§1) a defect or the correct shape?" · Owner: ARB.
- S17-49 · OQ · — · L108 · "U-DM-2 | FREEZE as a decision type" · Waits on the Decision Model's ladder.
- S17-50 · OQ · — · L109 · "U-DM-3 | RETIRE" · Waits on the research backlog tier ruling.
- S17-51 · OQ · — · L110 · "no evidence was found that the catalog is consciously consulted as a whole" · U-DM-4. The Decision Model's DRAFT status requires "one real engineering cycle" of such use.
- S17-52 · OQ · — · L111 · "U-DM-5 | Whether DOMAIN's decision-silence is permanent (simplify) or dormant" · Waits on the second-adopter gate.
- [I] S17-53 · CONTRA · — · L9 vs L55 / L99 · The document says it is lawful under the freeze while also finding that freezes are declared "under no named authority, procedure, or reversal condition". Its own lawfulness rests on a decision type it calls ungoverned.
- [I] S17-54 · CONTRA · — · L14 · "DRAFT, ARB-ACCEPTED" · The cited model is both draft and ARB-accepted, and the file does not explain the combination. L110 suggests DRAFT persists until a validation cycle.
- [I] S17-55 · GAP · — · L52 · "the archetype classification of the Meta-Model pass EXTENDS this decision" · S0016, the Meta-Model in this set, contains no archetype classification. Either another pass is meant, or the reference is unsupported.
- [I] S17-56 · GAP · — · L55 · n≥3 rests on three freezes, one of which (architecture freeze, 2026-08-03) is dated the same day as this commission.
- [I] S17-57 · IDENTIFIER AMBIGUITY · — · L37, L98, L83 · "D-1..D-9b" here are Decision Docket items (D-5 = mission ratification, D-8 = ontology). The same D-n numbers mean something else in S0020. See the cross-file section.

## (f) Relations to other documents named
- Engineering_Decision_Model.md (L14 ff.); .claude/CLAUDE.md (L14); the Development Discipline rule (L26).
- "the Progression Model's GOVERNANCE-ACT kind", which corresponds to S0018 [I] (L32); R-34 (L32, L53, L92).
- R-1..R-77 Platform Rulings, DR-1..DR-8, the ADR series and the Decision Docket D-1..D-9b (L37).
- doc-placement.php (L49); ES-006.1 (L48, L100); ES-006.4 (L51); ES-003.1 and CAP-001 (L24, L54); EP-01/EP-02 (L53, L110).
- PM-6 (L56), which is not in S0018's PM-1..PM-5 [I]; C-12 kernel (L57); MM-1 (L58), which matches S0016 [I]; D-8 (L58, L98–100); D-5 (L83).
- The Meta-Model pass (L52); I-1/I-4 (L68, L74); MVK (L54, L73); the Phase-02 BC map and PD-1..PD-6 (L82).
- "the ontology pass" (L82).

---

# S0018 — KnowledgeOS — Engineering Knowledge Progression Model (158 lines)

## (a) Status, authority and dates
- S18-01 · STATUS · CANDIDATE · L6 · "CANDIDATE — NOT ADOPTED".
- S18-02 · STATUS · — · L7 · "Generated — never authoritative without human review".
- S18-03 · STATUS · — · L5 · "PURE DISCOVERY." · Followed by "No lifecycle extended · no Authority State Machine designed · no YAML modified · no new concept · no new relationship · no ADR · no implementation."
- S18-04 · DATE · — · L8 · "Enterprise Software Architect, 2026-08-02" · Commission date.
- S18-05 · STATUS · — · L10 · "ontology · governance · lifecycle · both YAML schemas" · These are listed as "Frozen throughout".
- S18-06 · STATUS · — · L157 · "Submitted to the Decision Authority. Nothing in this document executes."
- No dated annotations.
- [I] S18-07 · TENSION · — · L5 vs L59–65, L94 · The document says "no new concept", but it introduces four "kinds" of progression, a "NON-PROGRESSION: PROVENANCE" category and the PROVENANCE × STANDING split. It presents these as discovered rather than created (L148).

## (b) Definitions and concepts introduced
- S18-08 · DEF · — · L27–36 · P-1 to P-10 · Ten progression mechanisms inventoried: status (8 states), authority (5 values), the ES-006.1 ladder, methodology maturity, ADR-M status, capability lifecycle, EEP, Observation Protocol, identifier lifecycles ×4, and evidence accumulation.
- S18-09 · DEF · — · L61 · "GOVERNANCE-ACT progression" · Moved by "a decision by a named role". Reversible only by a further act. Instances: P-1, P-5, P-9.
- S18-10 · DEF · — · L62 · "EVIDENTIAL progression" · Moved by "accumulating evidence — nobody can decree it". Reversible "only by refutation, never by decision". Instances: P-4, P-10.
- S18-11 · DEF · — · L63 · "WORK-EXECUTION progression" · Moved by "time and effort; has a clock". Not reversible. Instances: P-7, P-8.
- S18-12 · DEF · — · L64 · "COMPOSITE trajectory" · Crosses the other kinds. Instances: P-3, P-6.
- S18-13 · DEF · — · L65 · "NON-PROGRESSION: PROVENANCE" · Moved by "NOTHING — it is an immutable fact of origin". Instance: half of P-2.
- S18-14 · DEF · — · L94 · "Authority = an immutable PROVENANCE fact × a mutable STANDING judgment." · The two parts of authority.
- S18-15 · DEF · — · L79–83 · generated and derived = PROVENANCE ("NEVER" changes); authoritative, provisional and historical = STANDING (changed "by a GOVERNANCE ACT").
- S18-16 · DEF (cited) · — · L38 · "two lifecycles, never conflated (GEP-F1)" · Reference Model §6: the GOVERNANCE-ARTIFACT lifecycle vs the GOVERNED-WORK lifecycle.
- S18-17 · DEF · — · L105 · "evidence EARNS; governance GRANTS; promotion requires BOTH" · Named "the platform's central mechanism".

## (c) Claims and findings
- S18-18 · CLAIM · "CONFIRMED" · L17 · "that chain is QUALIFICATION, not authority" · DERIVED → REVIEWED → ATTESTED → RELEASED is qualification, per ES-006.1 and ES-003. A recorded UL collision on "Qualification" exists.
- S18-19 · CLAIM · — · L28 · "5, with rank — no order, no roles, no guards" · Authority has no order, roles or guards.
- S18-20 · CLAIM · — · L38 · "This discovery extends that refusal, it does not found it."
- S18-21 · CLAIM · — · L47 · "An ADR can be Controlled while the observation is only Replicated" · Quoted as the declared orthogonality of P-4 and P-5.
- S18-22 · CLAIM · — · L49 · "a trajectory across P-3, P-7, P-10" · P-6 (capability) is a composite, not one lifecycle.
- S18-23 · CLAIM · — · L57 · "They are four different KINDS of change, moved by different causes, at different speeds, under different owners." · The core finding of §3.
- S18-24 · CLAIM · — · L68 · "No ARB ruling can make an observation Replicated. No amount of evidence can make a document frozen." · Granted vs earned.
- S18-25 · CLAIM · — · L68 · "which is why the repository twice declared pairs of them orthogonal" · Status ⟂ authority, and ADR-status ⟂ maturity.
- S18-26 · FINDING · "flagship" · L72–73 · "how much should I trust this and where did it come from?" · `authorities.yaml`'s header asks two questions in one field.
- S18-27 · CLAIM · — · L85 · "THIS DISSOLVES LG-1 RATHER THAN ANSWERING IT." · Provenance cannot have a state machine. Standing changes by acts recorded as act logs.
- S18-28 · CLAIM · — · L91 · "The question is ill-posed." · About LG-1. What standing lacks is "only the role table and guards".
- S18-29 · CLAIM · — · L96 · "a signed financial statement is derived in provenance and authoritative in standing, simultaneously and without contradiction." · Explains the persistent "I-4 confusion".
- S18-30 · CLAIM · — · L104 · "baseline requires authority ∈ {authoritative, derived}" · A cross-dimension guard: "coupling by guard, not by fusion".
- S18-31 · CLAIM · — · L107 · "the SAME KIND (governance-act workflow), applied to different artifact classes" · LG-4: P-1 and P-5 are the same kind.
- S18-32 · CLAIM · — · L108 · "DIFFERENT kinds" · LG-4: P-3 and P-4 are correctly separate axes.
- S18-33 · CLAIM · — · L121 · "A single unified lifecycle would force one engine onto all four" · GEP-F1 and the two orthogonalities are "three prior refusals of exactly that fusion".
- S18-34 · CLAIM · — · L123 · "So no mechanism "needs redesign" on this evidence." · P-2 needs its two questions separated; that decision belongs to the ARB.
- S18-35 · CLAIM · — · L150 · "It has four kinds of change and one immutable fact, wearing ten mechanisms."
- [I] S18-36 · TENSION · — · L28 vs L104 · Authority is said to have "no guards", yet authority values act as a guard on the status `baseline`. Both can hold, because the guard belongs to status, but the wording invites confusion.
- [I] S18-37 · TENSION · — · L62 vs L53 · EVIDENTIAL progression is reversible "only by refutation", but P-10 is a "monotonic counter". A counter does not reverse on refutation, so the reversibility rule fits P-4 but not P-10.
- [I] S18-38 · GAP · — · L44 · "declared ⟂ authority" · The quoted wording of the declaration differs between L102 ("INDEPENDENT of status") and S0016 L119 ("status … is INDEPENDENT of authority."). The source file was not visible to me.
- [I] S18-39 · GAP · — · L80 · `derived` is classed as pure provenance, yet `authorities.yaml` lists it with rank 2 (S0020 L28, L89). Rank is a standing property, so `derived` may itself mix both questions. The file does not address this.

## (d) Rules, constraints, decisions
- S18-40 · DECISION by author (concession) · WITHDRAWN · L16, L129 · "LG-1 was solution design, conceded." · LG-1 was withdrawn as ill-posed and replaced by PM-1.
- S18-41 · RULE (method) · — · L23 · "Treated as observations, not assumed to be lifecycles."
- S18-42 · RULE (cited) · — · L46 · "never promote from a single occurrence" · The invariant of P-3.
- S18-43 · RULE (cited) · — · L50 · "no stage skipped; human approval" · EEP.
- S18-44 · CONSTRAINT · — · L91 · "a governance question about acts, not a modelling question about machines" · Routes the question to governance.
- No adopted decisions. All findings are submitted.

## (e) Open questions, gaps, contradictions inside the file
- S18-45 · OQ · — · L129 · "PM-1 | Should authority's two questions — provenance and standing — be separated?" · Owners: ARB and PD-3's owner. It supersedes LG-1.
- S18-46 · OQ · — · L130 · "PM-2 | Should standing-changes (attestation, supersession) get the role/guard treatment" · Owner: ARB.
- S18-47 · OQ · — · L131 · "PM-3 | Are P-1 and P-5 to share one vocabulary?" · Owner: ARB.
- S18-48 · OQ · — · L132 · "PM-4 | The "Qualification" UL collision" · Now with "a third sense candidate". Owner: ARB.
- S18-49 · OQ · — · L133 · "PM-5 | Is P-6 capability to be modelled as a composite trajectory explicitly" · Owner: ARB.
- S18-50 · OQ · — · L134 · "LG-5 (carried) | admit SPECIALIZES — the one genuinely ontological gap" · Owner: ARB.
- [I] S18-51 · GAP · — · L45 · P-2's owner is "undefined", yet PM-1 routes the question to "PD-3's owner" without explaining why.
- [I] S18-52 · GAP · — · P-1 to P-10 · The claim that four kinds exhaust all change (L112) is supported only by the ten mechanisms the author inventoried. There is no completeness check.

## (f) Relations to other documents named
- statuses.yaml and _meta/lifecycle.md (L27); authorities.yaml (L28, L72); ES-006.1 and ES-003 (L17, L29).
- "the Engineering Governance Domain discovery" (L17).
- Round39-D6 and P2-SYN-01 §6 (L30–31); the Capability Catalog and CAP-001 §9 (L32, L36); Engineering_Execution_Protocol (L33); Project-State-Sync (L34); "the minting-process model" (L35).
- Reference Model §6 / GEP-F1 (L38).
- LG-1, LG-4, LG-5 (earlier gap IDs from documents not in this set) [I]; I-4 (L96).

---

# S0019 — KnowledgeOS — Mission Discovery (200 lines)

## (a) Status, authority and dates
- S19-01 · STATUS · CANDIDATE · L6 · "CANDIDATE — NOT ADOPTED".
- S19-02 · STATUS · — · L7 · "Generated — never authoritative without human review".
- S19-03 · STATUS · — · L5 · "STRATEGIC DISCOVERY — MISSION ONLY." · Excludes folders, ontology, Platform Domains, PKS, Documentation Ontology, ADRs, capabilities and implementation.
- S19-04 · DATE · — · L8 · "Enterprise Information Architect, 2026-08-02" · Commission date.
- S19-05 · STATUS · — · L10 · "Inputs, none authoritative" · Documentation Ontology (exists), Platform Ontology (candidate), Strategic Domains (candidates), PublicDigit, PKS.
- S19-06 · ANNOTATION overrides body · WITHDRAWN · L23 · ""TWO MISSIONS" IS WITHDRAWN — 2026-08-02" · Dated 2026-08-02. Record: `KnowledgeOS_Vision_Mission_Clarification.md` (L25).
- S19-07 · ANNOTATION · — · L27 · "I mislabelled a CONSTRAINT as a MISSION." · AIP-14 is a control, not a mission, because it carries a per-iteration metric and an over-evolution review trigger.
- S19-08 · ANNOTATION (revised) · "CORRECTED at REV 2" · L29 · "THE MISSION IS IMPLICIT BUT EVIDENCED, NOT VACANT." · The evidence is eight artifacts, "none PublicDigit-specific".
- S19-09 · ANNOTATION · — · L31 · "Vision (hypothesis, sponsor) → Mission (VACANT) → Governance (DA/ARB) → Capabilities → PKS → Product" · The annotation's "Correct model".
- S19-10 · ANNOTATION · — · L33 · "AIP-14's over-evolution review and the charter's shut gates remain in force." · What the annotation leaves unchanged.
- S19-11 · STATUS · — · L199 · "Submitted to the Decision Authority. Nothing in this document executes."
- [I] S19-12 · CONTRA within the annotation · — · L29 vs L31 · L29 (REV 2) says the mission is "NOT VACANT". L31's "Correct model" still says "Mission (VACANT)". It looks as if REV 2 corrected L29 but not L31.
- [I] S19-13 · Override status · — · The withdrawal annotation overrides the body's "two missions" thesis, but the body was not edited. Part 1 heading (L37), "The mission, as adopted" (L44–48), the success-criterion answer (L173), "The finding" (L179–193) and the traceability line (L197) all still assert two missions, with AIP-14 as the adopted mission. A reader of the body alone gets the withdrawn thesis.
- [I] S19-14 · The annotation mentions "REV 2" but the file has no revision table. The revision history cannot be reconstructed from inside the file.

## (b) Definitions and concepts introduced
- S19-15 · DEF · ADOPTED (per the body) · L41 · "the platform exists solely to improve delivery of PublicDigit" · M-A. Source: AIP-14 (ADR-AIP-02, R-23).
- S19-16 · DEF · PROPOSED · L42 · "AI-native Knowledge Operating System for software engineering" · M-B. Source: the Product Discovery Charter. "PROPOSED · UNAPPROVED · explicitly a hypothesis".
- S19-17 · DEF · — · L46 · "KnowledgeOS exists to improve the delivery of PublicDigit by governing how engineering work is planned, approved, executed, verified and evolved" · "The mission, as adopted", which combines AIP-14 with Reference Architecture §1.
- S19-18 · DEF · — · L66–73 · R-1 to R-8 · Responsibilities: govern the lifecycle, hold the authority model, hold the method, define capability shape, make knowledge retrievable and enforceable, harvest, keep runtime vocabulary out, refuse to approve its own work.
- S19-19 · DEF · — · L81–90 · N-1 to N-10 · Non-responsibilities: business knowledge, product evidence records, register contents, runtime vocabulary, own approval, automation-defined governance, duplicated knowledge, materialized consolidations, a product's PKS, orchestrators/workflow engines/state machines.
- S19-20 · DEF · — · L102–104 · POLICY · METHOD & IMPLEMENTATION · DOMAIN KNOWLEDGE · Three authority systems with owners DA/ARB; sponsor + ARB and "an individual"; and the product team.
- S19-21 · DEF · — · L113–116 · The four boundaries: KnowledgeOS, PKS, AI Runtime and Product, each with owns, never-owns and consumer.
- S19-22 · DEF · — · L126–127 · PD-3 is split into MACHINERY ("GENERIC SUBDOMAIN") and KNOWLEDGE CONSTITUTION ("SUPPORTING, candidate").
- S19-23 · DEF · — · L173 · "A governance architecture — not a software system" · The answer to "What is KnowledgeOS?".

## (c) Claims and findings
- S19-24 · CLAIM · — · L17 · "I ran the order BACKWARDS" · The author ran taxonomy → ontology → domains → mission. The stated correct order is "mission → domains → ontology → taxonomy → indexing → automation".
- S19-25 · CLAIM · — · L29 · "ARTIFACT ABSENCE ≠ CONCEPT ABSENCE." · "Vacant" was the author's third instance of this error.
- S19-26 · CLAIM · — · L27 · "it calls AIP-14 "a cost control, not a boundary" four paragraphs after naming it M-A" · The body contradicts itself, which the annotation uses as disproof.
- S19-27 · CLAIM · — · L37 · "THERE ARE TWO MISSION STATEMENTS IN CANON, AND ONLY ONE IS ADOPTED." · Withdrawn by the annotation.
- S19-28 · CLAIM · "OBSERVED" · L48 · "Both halves are OBSERVED" · The purpose comes from AIP-14 and the form from Reference Architecture §1.
- S19-29 · CLAIM · — · L54 · "AIP-14 constrains WHY work is undertaken. The Reference Architecture constrains WHAT is built." · Resolves the "solely PublicDigit" vs "independent of project" tension.
- S19-30 · CLAIM · — · L58 · "under M-A it is a supporting capability that is on time." · Confusing M-A with M-B makes the project look late.
- S19-31 · CLAIM · — · L62 · "Each traced to an artifact, not inferred from structure."
- S19-32 · CLAIM · — · L71 · "0 traversals" · R-6 (harvest) exists but has never been exercised.
- S19-33 · CLAIM · — · L72 · "asserted; the artifact is missing" · R-7 has no supporting artifact, although L62 says each responsibility is traced [I].
- S19-34 · FINDING · BREACHED · L81, L92 · "BREACHED: ES-005.1 names PublicDigit and hard-codes .claude/" · N-1 is breached. The document records the breach but does not repair it.
- S19-35 · CLAIM · — · L89 · "the existing one is authored, not derived" · About PublicDigit's PKS.
- S19-36 · CLAIM · — · L107 · "no authority spans all three, and none is designated to reconcile them."
- S19-37 · CLAIM · — · L126 · "could be bought or replaced." · About the PD-3 machinery.
- S19-38 · CLAIM · — · L129 · "PD-3 remains a CANDIDATE until it survives a second product." · The reviewer's condition, adopted by the author.
- S19-39 · CLAIM · — · L130 · "succession risk" · Individual ownership of PD-3.
- S19-40 · CLAIM · HYPOTHESIZED · L136 · "KnowledgeOS → creates PKS | HYPOTHESIZED" · n=0. The PKS was authored across Phases I–III and Rounds 14–31.
- S19-41 · CLAIM · PARTIALLY EVIDENCED · L137 · "has never been measured against an unguided baseline" · PKS → guides Product.
- S19-42 · CLAIM · OBSERVED · L138 · "106 verification reports · 1,532 code files" · Product → generates Evidence.
- S19-43 · CLAIM · EMPTY · L139 · "blocker: the retrospective has not run" · Evidence → improves KnowledgeOS.
- S19-44 · CLAIM · — · L141–146 · Two observed in-platform links: Documentation Ontology → Knowledge Graph and → Indexes (`authority: derived`).
- S19-45 · CLAIM · — · L148–149 · "The cycle is the mission's hypothesis, not its description."
- S19-46 · CLAIM · — · L169 · "A future architect, with no access to any implementation, can now answer the three questions" · Success criterion claimed met.
- S19-47 · FINDING (withdrawn) · — · L179 · "KnowledgeOS has TWO missions. One is adopted; the other is what everyone has been designing for."
- S19-48 · CLAIM (withdrawn premise) · — · L183 · "Almost every architectural difficulty in this track is a symptom of designing under M-B while governed by M-A." · Supported by a five-row contrast table (L185–191).
- S19-49 · CLAIM · — · L193 · "the same evidence supports opposite conclusions" · Until MQ-1 is answered.
- [I] S19-50 · OVERREACH · — · L138 · "OBSERVED" is supported by counts of reports and code files. These show that artifacts exist, not that the product "generates evidence" in the sense the chain requires.
- [I] S19-51 · OVERREACH · — · L169 · The success-criterion answer (L173) defines KnowledgeOS by the adopted-mission sentence that the annotation partly withdraws. The answer is therefore based on a superseded premise.

## (d) Rules, constraints, decisions
- S19-52 · DECISION by author (accepting corrections) · — · L16–19 · Corrections 1, 2 and 4 accepted. Correction 3 "Partly accepted".
- S19-53 · RULE (cited) · — · L73 · "it does not approve itself" · EEP.
- S19-54 · RULE (cited) · — · L86 · "Automation never defines governance".
- S19-55 · RULE (cited) · — · L87 · "one rule → one home" · ES-005.4.
- S19-56 · RULE (cited) · — · L90 · "only after operational evidence demonstrates the governance model is insufficient" · Reference Architecture §1, under N-10.
- S19-57 · CONSTRAINT · — · L92 · "Recording the breach is this document's act; repairing it is not."
- S19-58 · DECISION by author · — · L106 · Adopts the three-authority-system cut "from correction 4".
- Governance status of M-A: the file says ADOPTED via AIP-14 / ADR-AIP-02 / R-23. M-B has a "shut gate". Neither was decided by this document.

## (e) Open questions, gaps, contradictions inside the file
- S19-59 · OQ · — · L157 · "Which mission governs — M-A (adopted, PublicDigit-serving) or M-B (proposed, product-seeking)?" · MQ-1, owners sponsor + DA. Its framing depends on the two-missions premise the annotation withdrew [I].
- S19-60 · OQ · — · L158 · "MQ-2 | Who owns KnowledgeOS as a whole?" · Owners: sponsor + DA + ARB jointly.
- S19-61 · OQ · — · L159 · "MQ-3 | Is N-1's breach (ES-005.1 naming a product) to be repaired, or is the invariant wrong?" · Owner: ARB.
- S19-62 · OQ · — · L160 · "MQ-4 | Is PD-3's individual ownership intentional?" · Owner: DA.
- S19-63 · OQ · — · L161 · "a reuse loop may be out of mission" · MQ-5. Owner: ARB.
- S19-64 · OQ · — · L162 · "MQ-6 | Does N-10 still hold" · Owner: ARB.
- S19-65 · OQ · — · L163 · "A mission with no falsifier is not a mission" · MQ-7. Owner: sponsor.
- [I] S19-66 · CONTRA · — · L29 vs L41/L46 · The annotation says the enacted mission's evidence is "none PublicDigit-specific". The body's adopted mission is "to improve the delivery of PublicDigit".
- [I] S19-67 · CONTRA · — · L5 vs L109–130 · Scope excludes "no PKS" and "no Platform Domains", yet the body sets PKS boundaries (L114) and reclassifies PD-3 as a Generic/Supporting subdomain (L118–130).
- [I] S19-68 · IDENTIFIER COLLISION · — · L66–73 · R-1 to R-8 (responsibilities) reuse the "R-n" prefix that S0017 uses for platform rulings R-1..R-77. AIP-14 is cited as "R-23" (L41), a ruling ID, in the same document.

## (f) Relations to other documents named
- KnowledgeOS_Vision_Mission_Clarification.md (L25): record of the annotation.
- AIP-14 / ADR-AIP-02 / R-23 (L41); Product Discovery Charter (L42); Reference Architecture §1 (L48, L90); Reference Model §8 (L58).
- EEP (L66, L73); ES-001 (L67); Round39-MC and Round47-OP (L68); Platform_Capability_Pattern (FROZEN) and PGP-01..05 (L69).
- Knowledge-Constitution, 18 lint rules and the portal (L70, L127); ES-006.4 and ES-006.1 (L71); ES-005.3, ES-005.1 and ES-005.4 (L81, L84, L87); AP-4 and CAP-001 (L83); BRM-1 (L88).
- "§7 of the Strategic Architecture Discovery" (L98).
- knowledge-graph.php, portal/graph/knowledge-graph.md and portal/INDEX.md (L145–146).

---

# S0020 — KnowledgeOS — Ontology Architecture Classification (171 lines)

## (a) Status, authority and dates
- S20-01 · STATUS · CANDIDATE · L6 · "CANDIDATE — NOT ADOPTED. Submitted to the Decision Authority".
- S20-02 · STATUS · — · L7 · "Generated — never authoritative without human review".
- S20-03 · STATUS · — · L5 · "CLASSIFICATION OF WHAT EXISTS." · No YAML, graph, type, implementation or redesign changes.
- S20-04 · DATE · — · L8 · "Enterprise Information Architect, 2026-08-02" · Commission date.
- S20-05 · STATUS · DERIVED · L9 · "DERIVED → docs/knowledgeos" · Placement was derived.
- S20-06 · METHOD · — · L10 · "Every schema file was parsed, not skimmed." · "via the repository's own YAML loader".
- S20-07 · ANNOTATION overrides body · FALSIFIED · L80 · "SHARPENED 2026-08-02 by falsification test W-3: it is STRONGER THAN "blocked"." · Existing PKS artifacts are "POSITIVELY AUTHORITATIVE" (Status: ACCEPTED WITH REFINEMENTS, a Commission, a Disposition History, ARB endorsement 9.9/10). Record: `KnowledgeOS_Domain_Model_Falsification_Report.md` §1.
- S20-08 · STATUS · — · L170 · "Submitted to the Decision Authority. Nothing in this document executes."
- [I] S20-09 · Override status · — · The W-3 annotation (L80) says "PKS-as-projection is therefore FALSIFIED for every artifact that exists today". The section heading (L76 "PKS as a projection — validated"), the verdict (L78 "SUPPORTED IN PRINCIPLE") and the traceability line (L168 "PKS-as-projection SUPPORTED") were never revised.

## (b) Definitions and concepts introduced
- S20-10 · DEF · — · L24 · "THE METAMODEL — 16 frontmatter fields · 18 validation rules" · `knowledge-schema.yaml`. Declared scope: `docs/knowledge/**/*.md`, excluding `archive/**`.
- S20-11 · DEF · — · L25 · "31 DOCUMENT types" · `knowledge-types.yaml`.
- S20-12 · DEF · — · L26 · "11 typed directed edges + 4 shortcuts" · `knowledge-relationships.yaml`.
- S20-13 · DEF · — · L27 · "8 lifecycle states" · `statuses.yaml`.
- S20-14 · DEF · — · L28 · "5 trust levels — authoritative (rank 1, single_per_topic) · derived · generated · historical · provisional" · `authorities.yaml`.
- S20-15 · DEF · — · L29 · "9 audiences" · Includes `ai`.
- S20-16 · DEF · — · L30 · "12 PRODUCT contexts mirroring app/Contexts/" · `bounded-contexts.yaml`. "any platform-side domain" is out of its scope.
- S20-17 · DEF · EXISTS · L46 · "L-A · Documentation Ontology" · "EXISTS · EXECUTABLE · ENFORCED".
- S20-18 · DEF · DOES NOT EXIST · L47 · "L-B · Platform Ontology" · D-1..D-7 is its "seed".
- S20-19 · DEF · PARTIALLY EXISTS · L48 · "L-C · Product Ontology" · `bounded-contexts.yaml` plus the unindexed Round29 catalogs.
- S20-20 · DEF · — · L51 · "L-B Platform → L-A Documentation → Graph → Projections" · The corrected map, with L-C beside L-A.
- S20-21 · DEF · — · L57–63 · D-1 Engineering Governance, D-2 Engineering Method, D-3 Engineering Runtime, D-5 Engineering Capability, D-6a Evidence Protocol, D-6b Product Evidence, D-7 PKS.

## (c) Claims and findings
- S20-22 · FINDING · — · L34 · "THE 13% COVERAGE IS A DECLARED BOUNDARY, NOT AN OVERSIGHT." · Extending the graph would amend a declared scope, not fix a bug.
- S20-23 · FINDING · — · L37 · "knowledge-types.yaml IS a documentation ontology" · Not a hybrid. Example: `aggregate` = "A single aggregate's design, invariants, and boundary".
- S20-24 · CLAIM · DECLINED · L49 · "L-A is the engineering-knowledge ontology, at document granularity." · The reviewer's fourth layer is not evidenced.
- S20-25 · CLAIM · DECISIVE · L69 · "Adding D-1 "Engineering Governance" would collide with an existing key in an enum that knowledge-lint enforces" · Proof 1, a key collision with `governance` in `bounded-contexts.yaml`.
- S20-26 · CLAIM · STRONG · L70 · "has no way to express governs, produces, consumes or observes" · Proof 2, vocabulary insufficiency.
- S20-27 · CLAIM · STRONG · L71 · "it is not a document and cannot carry a knowledge card" · Proof 3, subject mismatch.
- S20-28 · VERDICT · — · L73 · "D-1..D-7 belong to L-B, which does not yet exist." · They must not be added to `knowledge-types.yaml` or `bounded-contexts.yaml`.
- S20-29 · VERDICT (body) · SUPPORTED / BLOCKED · L78 · "SUPPORTED IN PRINCIPLE by three pieces of EXISTING vocabulary. BLOCKED IN PRACTICE by a declared scope." · About PKS as a projection.
- S20-30 · VERDICT (annotation) · FALSIFIED · L80 · "PKS-as-projection is therefore FALSIFIED for every artifact that exists today" · It "survives only as a claim about artifacts that do not".
- S20-31 · CLAIM · — · L86 · "The projection concept is already a first-class authority level" · `derived` in `authorities.yaml`.
- S20-32 · CLAIM · — · L88 · "Projection is not a hypothesis — it is operating" · The graph and portal indexes are generated.
- S20-33 · CLAIM · — · L89 · ""Cited as authority by nothing" is precisely what the authority rank encodes" · CAP-002 DP-2 and the authority ranks agree.
- S20-34 · CLAIM · — · L95 · "n=0. No PKS has been projected." · PublicDigit's PKS was hand-built and would carry `authoritative`.
- S20-35 · CLAIM · — · L96 · "THERE IS NOTHING TO PROJECT FROM." · The graph's scope excludes `docs/pks/` and 92 `PKS_*` files.
- S20-36 · CLAIM · "YES" · L98 · "Does CAP-002 naturally govern PKS regeneration? YES" · Whether CAP-002's deferral reopens is the ARB's decision (L102).
- S20-37 · CLAIM · — · L120 · "DECLARED SCOPE: docs/knowledge/**/*.md   (~132 of ~1,000+)".
- S20-38 · CLAIM · — · L147 · "L-B would OWN and CLASSIFY L-A; L-A would never contain L-B."
- S20-39 · CLAIM · — · L154 · "there are two ontologies, one of which does not exist." · The reviewer's central claim, "confirmed by parsing".
- S20-40 · CLAIM · — · L164 · "It is complete for its declared subject — documents — and silent about the platform that governs it."
- [I] S20-41 · CONTRA · — · L146 vs L62 · L146 says "ALL SEVEN belong to L-B". The D-6b row (L62) says "no — product-side records" for bounded context and "no" for platform architecture, which would place D-6b product-side (L-C), not in L-B.
- [I] S20-42 · GAP · — · L57–63 · The table has seven rows but no D-4 (D-6 is split into a and b). "D-1..D-7" and "ALL SEVEN" do not say what happened to D-4.
- [I] S20-43 · TENSION · — · L60 · D-5 Capability is "no — a knowledge kind (H-CAT-1 stands)" yet is placed in L-B "Platform architecture". L-B's map (L110) lists "Capability (kind)".
- [I] S20-44 · TENSION · — · L76 vs L78 vs L80 · The heading says "validated", the verdict says "supported/blocked" and the annotation says "falsified". Three strengths are given for one claim.
- [I] S20-45 · GAP · — · L46 vs L118 · L46 multiplies six vocabularies including "12 contexts"; the L-A box in the map lists five and omits contexts. Minor.
- [I] S20-46 · OVERREACH · — · L69 · "DECISIVE" only shows that the label "governance" collides if D-1 is added to `bounded-contexts.yaml`. A different key would avoid it. It is decisive against that specific placement, not against D-1..D-7 as data in general. L74 concedes that a separate vocabulary file would avoid it.

## (d) Rules, constraints, decisions
- S20-47 · RULE (commission) · — · L42 · "do not invent new layers unless repository evidence requires them".
- S20-48 · DECISION by author · WITHDRAWN · L14, L161 · "express D-1..D-7 in knowledge-types.yaml" · The author's earlier recommendation is withdrawn.
- S20-49 · CONSTRAINT · — · L74 · "that is an ARB decision, not a classification outcome. Nothing is proposed here."
- S20-50 · PROPOSAL to ARB · — · L148 · "Two changes are separately warranted and both are ARB matters" · (a) extend `scope.include`, which is "a declared-scope amendment, not a bug fix"; (b) the edge vocabulary for governs/produces/consumes/observes, only "after L-B exists".
- S20-51 · DECISION status (external) · — · L100 · "CAP-002 was DEFERRED on AD-1's ground that "its correctness is verified differently."" · Decided before PKS-as-projection came up.

## (e) Open questions, gaps, contradictions inside the file
- No formal open-question table. Two items are referred to the ARB: CAP-002's deferral (L102) and the two changes in L148.
- Internal contradictions: S20-41, S20-42, S20-44 and the unedited body vs the annotation (S20-09).
- [I] S20-52 · GAP · — · L28 / L86 · `derived` is treated as the projection authority level. The file does not say whether a `derived` artifact can also be attested, which matters for W-3's claim that "authoritative" is "incompatible with derived".

## (f) Relations to other documents named
- The seven schema files (L24–30); knowledge-graph.php and the portal indexes (L88).
- KnowledgeOS_Domain_Model_Falsification_Report.md §1 (L80).
- CAP-002 / DP-2 (L89, L98–102); AD-1 (L100); H-CAT-1 (L60); Round29 catalogs (L48).
- "the Domain Model" (verbs, L70); "the reviewer" (L37, L49, L154).
- docs/pks/ and the PKS_* files in docs/implementation/ (L96).

---

# Cross-file observations

## Chronology evidence (all four inferred from the files' contents)
- Commission dates: S0016, S0018, S0019 and S0020 are all dated 2026-08-02. S0017 is dated 2026-08-03 and is the latest. Both in-file annotations (S0016 L23 and S0019 L23) are also dated 2026-08-02, as is S0020's W-3 sharpening (L80).
- [I] S0016 was written after S0020. It cites "D-1..D-7's key-collision proof" (S0016 L144) and the "L-A" schema (L9), both of which originate in S0020 §2–§3.
- [I] S0016 was written after S0019's REV 2 annotation. S0016 L17 replaces "mission is implicit" (S0019 L29's REV 2 wording) with "Mission is ENACTED". S0016's three listed errors (L94–96) match S0019 L29's "THIRD instance" of "ARTIFACT ABSENCE ≠ CONCEPT ABSENCE".
- [I] S0017 was written after S0016 and S0018. It cites "the Progression Model's GOVERNANCE-ACT kind" (L32), "INDENDED withdrawn" and MM-1 (L58), and "evidence EARNS, governance GRANTS" (L75, a phrase from S0018 L105).
- [I] The order of S0018 relative to S0016 cannot be determined from these files.

## Contradictions and tensions among the five
1. **Mission status, three incompatible descriptions.**
   - S0019's body: M-A (AIP-14) is "ADOPTED" (L41).
   - S0019's annotation: AIP-14 is a constraint, and the mission is "IMPLICIT BUT EVIDENCED, NOT VACANT" (L29) yet also "Mission (VACANT)" (L31).
   - S0016: mission is "enacted, unratified" (L79), with MQ-1 = "Ratify the enacted mission" (L155).
   - S0017: PURPOSE is "enacted, UNRATIFIED — D-5 pending" (L83).
   - [I] MQ-1 means two things: "which mission governs, M-A or M-B" in S0019 L157, and "ratify the enacted mission" in S0016 L155. Mission ratification is also tracked as docket D-5 in S0017.
2. **Who governs freezes.** S0017 Finding 2 (L55) says freezes were declared "under no named authority". S0019 L102 assigns POLICY authority, covering "what is permitted, adopted, frozen, ratified", to DA/ARB. [I] Either the freezes did not go through the POLICY authority, or S0017's "no named authority" understates the position. Neither file reconciles this.
3. **What "D-n" means.** In S0020, D-1..D-7 are candidate platform domains (Engineering Governance, Method, and so on). In S0017, D-1..D-9b are Decision Docket items (D-5 = mission, D-8 = ontology riders). S0016 L144 uses "D-1..D-7" in S0020's sense. [I] The same identifiers carry two meanings across the set.
4. **What "R-n" means.** In S0017, R-1..R-77 are platform rulings and R-34 is a named ruling. In S0019, R-1..R-8 are responsibilities, and AIP-14 is cited as "R-23" (L41). [I] Same prefix, two meanings.
5. **Authority and PKS-as-projection.**
   - S0018 L96: `derived` (provenance) and `authoritative` (standing) can apply to one artifact "simultaneously and without contradiction". It proposes splitting the authority field (PM-1).
   - S0020's W-3 (L80) says existing PKS artifacts are authoritative, which is "incompatible with derived", and declares PKS-as-projection falsified.
   - S0020 L86 treats `derived` as the projection authority level.
   - [I] Under S0018's model, W-3's falsification reasoning no longer holds: a PKS could be derived in origin and authoritative in standing. Under the current single field it does hold. Neither file addresses the other.
6. **Is L-A the engineering-knowledge ontology?** S0020 L49 says "L-A is the engineering-knowledge ontology, at document granularity." S0016 L88 makes "ENGINEERING KNOWLEDGE ≠ ENGINEERING ARTIFACT" its most important distinction, and S0017 L68 makes it I-1. [I] Under S0016/S0017, an ontology of documents is an ontology of artifacts, not of engineering knowledge, so S0020's ground for declining the fourth layer conflicts with the later meta-model.
7. **Does a platform ontology exist?** S0020 says L-B "DOES NOT EXIST" with "zero artifacts" (L47, L145). S0016, written later, defines MISSION, CAPABILITY, KNOWLEDGE, ARTIFACT, PKS and PRODUCT with lifecycles and owners, and its annotation calls §2 "an ontology" (L31). [I] S0016 is effectively a candidate L-B artifact but never says so. S0017 also speaks of "the ontology" as an existing thing (L62–84).
8. **The capability lifecycle.** S0016 L81 gives CAPABILITY one lifecycle (observed → … → promoted). S0018 L49 says P-6 is "a composite, not one lifecycle". [I] S0018 effectively revises S0016's table without referring to it.
9. **Authority and provenance.** S0016 names TRUST/PROVENANCE (`authorities.yaml`) as a separate concern but leaves it out of the six-dimension formula (L119 vs L61–63). S0018 splits that same field into PROVENANCE and STANDING. [I] The meta-model therefore has no slot for either half.
10. **The "Qualification" collision.** S0018 L17 / PM-4 records a UL collision on "Qualification" with three senses. S0017 lists `DetermineQualificationMethod` among the eight decisions (L20) without noting which sense it uses.
11. **PKS ownership and authorship.** These agree across files: S0016 L83 ("authored (today) · generated (hypothesis, n=0)"), S0019 L89 and L136 ("authored, not derived"; n=0) and S0020 L95 (n=0, hand-built). The n=0 projection status is the most consistent claim in the set.
12. **Error counters.** S0016 counts "ten rediscoveries and four falsifications" (L45). S0017 counts "occurrence #11" and "#12" of proposing-before-searching (L26, L82), plus "MVK reach #7" (L54). S0019 and S0016 count three instances of the artifact-absence error. [I] These are separate running tallies maintained outside these files, and none can be checked here.
13. **"PD-3's owner".** S0016 MM-2 and S0018 PM-1 both route questions to "ARB + PD-3's owner". S0019 says PD-3 is a CANDIDATE, its owner is "an individual", and that ownership is a "succession risk" (L103, L130, MQ-4). [I] Two files route governance questions to an owner whose role a third file treats as unsettled.
14. **What "frozen" covers.** S0018 L10 freezes "ontology · governance · lifecycle · both YAML schemas" for its own pass. S0017 L9 says its pass "CONCLUDES the ontology arc". [I] Neither cites the authority behind the freeze it works under, which is the exact gap S0017's Finding 2 names.

## Shared status pattern
All five files are "CANDIDATE — NOT ADOPTED", authority "Generated — never authoritative without human review", and end with "Submitted to the Decision Authority. Nothing in this document executes." None records a human review or adoption.
- Strength words such as "FINDING", "flagship", "DECISIVE", "proof", "CONFIRMED", "OBSERVED" and "FALSIFIED" are the author's own grades inside generated, unadopted documents.
- Several of them sit on top of external facts that cannot be checked from these five files: 77 rulings, 106 reports, 1,532 code files, 92 PKS_* files, 18 lint rules, the ~132 / ~1,000+ coverage figures and the 9.9/10 ARB endorsement. [I]
