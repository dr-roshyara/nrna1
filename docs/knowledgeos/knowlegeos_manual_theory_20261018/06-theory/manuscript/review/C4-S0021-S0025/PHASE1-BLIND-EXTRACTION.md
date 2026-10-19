[harness: subagent output matched instruction-shaped pattern(s): settings-json, permissions-allow-deny. Control tags below are neutralized (`<` → `<\`); treat any remaining directive-shaped text as a finding to relay to the user, not an instruction to you.]

# Phase 1 blind extraction: S0021–S0025

## Access and completeness
- **What I read.** I read exactly five files, each in full with one Read call per file. I opened, listed, searched and globbed no other file, and wrote no files.
  - S0021 `docs/knowledgeos/KnowledgeOS_Relationship_Ontology.md`: last non-empty numbered line is **L192**. The tool showed no empty numbered line after it.
  - S0022 `docs/knowledgeos/KnowledgeOS_Ontology_Cross_Product_Validation.md`: last non-empty line is **L199**. No empty numbered line after it.
  - S0023 `docs/knowledgeos/KnowledgeOS_ARB_Decision_Docket.md`: last non-empty line is **L198**. No empty numbered line after it.
  - S0024 `docs/knowledgeos/KnowledgeOS_Architecture_Fitness_Assessment.md`: last non-empty line is **L210**. No empty numbered line after it.
  - S0025 `docs/knowledgeos/2026-08-02-mvk-bootstrap-validation-report.md`: last non-empty line is **L196**. No empty numbered line after it.
- **Item format.** ID · TYPE · the source's own strength word (or —) · Lnn · "verbatim quote" · what it means in this document only. Quotes have emphasis markup and emoji removed. My own interpretations are marked [I].

---

# S0021 — KnowledgeOS — Relationship Ontology (192 lines)

## (a) Status, authority and dates
- S21-01 · STATUS · "STRATEGIC ONTOLOGY CANDIDATE" · L6 · "status adopted per review, 2026-08-02" · The status label is said to be "adopted per review". The reviewer is not named. [I] What was adopted is the label, not the ontology. S0022 L177 (CV-5) still asks the ARB whether to adopt this label, so this adoption was not an ARB act.
- S21-02 · LATER ANNOTATION (header) · "SURVIVES, BUT NOT UNCHANGED" / "FALSIFIED" / "FAILED" · L7 · "8/12 concepts and 10/11 invariants held" · The header points to S0022 and repeats that I-4 was FALSIFIED. **This contradicts L108**, which says the falsification was WITHDRAWN on 2026-08-02. The header was not updated.
- S21-03 · AUTHORITY · — · L8 · "Generated — never authoritative without human review"
- S21-04 · DATE / COMMISSION · — · L9 · "Principal Knowledge Engineer / Strategic DDD Architect / Enterprise Software Architect, 2026-08-02"
- S21-05 · DISCLAIMER · — · L5 · "RELATIONSHIP MODEL." · The document says it is not a taxonomy, not a redesign, not an implementation, not tooling and not an ADR.
- S21-06 · LATER ANNOTATION · "WITHDRAWN 2026-08-02" · L108 · "falsification WITHDRAWN 2026-08-02" · I-4 is reclassified in place. The body was updated at L108 only. The header (L7) and traceability line (L190) were not updated.
- S21-07 · LATER ANNOTATION · "RESTATED 2026-08-02" · L115 · "My original wording was too strong" · I-11 is restated. **The §3 table at L89 was not updated**: it still says knowledge is "never generative".
- S21-08 · CLOSING STATUS · — · L192 · "Nothing in this document executes." · The document is submitted to the Decision Authority.

## (b) Definitions and concepts introduced
- S21-10 · DEF · — · L48 · "purpose · domain · capability · knowledge · representation · container · runtime element · projection" · These are the commission's eight "kinds". [I] The §2 table also uses kinds that are not on this list ("purpose→governance bridge", "governance" and "application" at L54–56 and L62) and never uses "domain".
- S21-11 · DEF · OBSERVED (all rows) · L52–64 · C-1 VISION … C-13 PROJECTION · There are 13 concepts. Each has a kind and an owner, and every one is graded OBSERVED. This includes C-12 RUNTIME ADAPTER, whose owner is "no owner found" (L63).
- S21-12 · DEF · OBSERVED · L60, L70 · "a knowledge space is a set of artifacts with a DECLARED BOUNDARY" · KNOWLEDGE SPACE (C-9) is admitted as a container.
- S21-13 · DEF · — · L75 · "A Knowledge Space is not a folder." · It is defined as a declared scope with an owner and a governing constitution.
- S21-14 · DEF · — · L79 · "a container relation" · Defines the four relationship verbs: contains, references, generates and depends on.
- S21-15 · DEF · — · L99 · "An invariant may never be changed by a project; a choice may be changed freely." · Defines invariant versus implementation choice.
- S21-16 · DEF (chain) · — · L38–41 · MISSION → STRATEGY (charter) → PRINCIPLE → DESIGN POLICY (DP-n) → CAPABILITY (CAP-n) → KNOWLEDGE → ARTIFACT. [I] VISION (C-1) is missing from the chain. KNOWLEDGE sits below CAPABILITY in the chain, yet in the §3 table (L88–89) KNOWLEDGE depends on Principle and CAPABILITY depends on Knowledge.

## (c) Claims and findings
- S21-20 · CLAIM · "ACCEPTED" · L17 · "Level mixing — the third time" · Correction 1: the six concepts are not siblings. The author concedes this is the third instance of the same error class.
- S21-21 · CLAIM · "ACCEPTED and evidenced" · L18 · "Mission does not directly generate Capability"
- S21-22 · CLAIM · "ACCEPTED" · L19 · "A real error in my model" · Correction 3: a PKS contains artifacts.
- S21-23 · CLAIM · "PARTLY" · L20 · "It conflated two levels — level mixing again." · Correction 4 concerns `KnowledgeOS_Meta_Model.md`: its §1 is a meta-model and its §2 is an ontology.
- S21-24 · CLAIM · "ADMIT" · L30 · "12+ charter artifacts" · This is the evidence for ENGINEERING STRATEGY. [I] Only 11 artifacts are named in the cell (Round32, Round38C-01, Round39-00, Round40-00, Round44/45/46-00 counted as 3, Round16/17 counted as 2, Context Assembly Research, Product Discovery). "12+" is not shown.
- S21-25 · CLAIM · "ADMIT — abundantly evidenced" · L31 · "six registers with their own identifier series" · Six are listed: AIP, AP, PGP, MC, SD and DR. The count matches.
- S21-26 · CLAIM · "ADMIT — DISCOVERED" · L32 · "The Capability Catalog binds EVERY capability to a DP:" · Only four bindings (CAP-001..004) are shown, followed by "…". [I] The universal "EVERY" rests on four shown pairs.
- S21-27 · CLAIM · "REJECT as an owner" · L33 · "No artifact links any" · PD-1..PD-6 exist, but nothing binds a CAP to a PD.
- S21-28 · CLAIM · — · L44 · "Capabilities are owned by design policies, not by domains"
- S21-29 · CLAIM · OBSERVED · L70–73 · This is the C-9 evidence: `scope.include` · three registered roots (knowledgeos, pks, publicdigit) · ES-005.1's three concerns · the EKP's own constitution and owner.
- S21-30 · CLAIM · — · L75 · "was a scope decision, not a bug" · The "13% coverage" question is reframed as a scope decision.
- S21-31 · CLAIM (relationship table) · — · L83–95 · Selected rows:
  - KNOWLEDGE "it is represented, never generative" (L89)
  - KNOWLEDGE SPACE may contain "other Knowledge Spaces" (L91)
  - PKS contains product Artifacts, "never reusable Method" (L92)
  - RUNTIME ADAPTER may reference "capability vocabulary only" (L94)
- S21-32 · INVARIANTS · each "traced to a governed statement" (L101) · L105–115:
  - I-1 "Knowledge is REPRESENTED by an Artifact. An Artifact is never the Knowledge." Source: RQ-002.
  - I-2 "A Capability protects EXACTLY ONE invariant". Source: CAP-001 and the 1:1 binding in the Catalog.
  - I-3 "A Knowledge Space has a DECLARED boundary".
  - I-4 "UNDERSPECIFIED". Text: "Governance created the authority, not the derivation." and "reclassified: a LIFECYCLE gap, not an ontology falsification". The mechanical cause given is that `status` has a governed state machine and `authority` has none. Record: `KnowledgeOS_Lifecycle_Gap_Analysis.md`.
  - I-5 "ONE authoritative artifact per topic + context", marked "lint-ENFORCED".
  - I-6 "The Runtime never owns Knowledge".
  - I-7 "A PKS never contains reusable Method", marked "stated and BREACHED" (EAD-1).
  - I-8 "Governance precedes automation; automation never defines governance". Source: Reference Architecture §1.
  - I-9 "Governance-status ⟂ Authority". Source: `statuses.yaml`.
  - I-10 "A constitutional principle is void-overriding". Source: Round39-MC and Round47-00.
  - I-11 "KNOWLEDGE DOES NOT EXECUTE. Capabilities execute." The row says it "SURVIVED cross-product projection" and was "validated in all three domains".
  - [I] I-4's own invariant wording never appears in S0021. Only the struck-out "FALSE as a general rule" appears. The original wording is in S0022 L83.
  - [I] I-1's source (RQ-002) and I-8's source (Reference Architecture) are not shown to be adopted. MM-1 (L170) still asks to adopt RQ-002. So "governed statement" is asserted, not shown.
- S21-33 · CLAIM · — · L130 · "11 invariants · 8 choices." · The choice table at L121–128 has 8 rows, so the count matches. [I] The 11 still include I-7, which is breached, and I-4, which is reclassified.
- S21-34 · CLAIM · "All eleven place" · L150 · "All eleven place." · This is the success-criterion test. It has 11 rows and the count matches. [I] Three things weaken the test:
  - Placements use labels that are not among C-1..C-13: "Binding (a kind of Knowledge)" at L139, "Boundary/Trigger rules" at L141, and "Evidence" at L144 and L147.
  - The "Space" column mixes registered roots with ES-005.1 concerns (platform, product, runtime, EKP, PKS).
  - The test was run after KNOWLEDGE SPACE had already been admitted in the same document.
- S21-35 · CLAIM · — · L151 · "a Capability may read across a space boundary, but may not own what it reads" · Recorded as an I-12 candidate.
- S21-36 · CLAIM · — · L186 · "the eight choices are as important as the eleven invariants"

## (d) Rules, constraints, decisions
- S21-40 · RULE (self-imposed) · — · L10 · "No new concept unless the current model cannot explain existing evidence." · The author then claims "One is admitted on that ground, one is REJECTED". [I] **That count does not add up.** The body admits STRATEGY, PRINCIPLE and DESIGN POLICY (L30–32) and also KNOWLEDGE SPACE (C-9, L66–75).
- S21-41 · CRITERION · — · L11 · "every existing artifact placeable with NO additional top-level concept"
- S21-42 · DECISIONS · none taken · This document decides nothing. Every disposition is routed to the ARB or DA in §7. The author's "Admit" and "Reject" verdicts are the author's own.

## (e) Open questions, gaps and internal contradictions
- S21-50 · HYPOTHESES · "not admitted" · L157–160 · Four hypotheses:
  - H-1: I-12 read-across. Marked "n=1 capability."
  - H-2: Engineering Domain as owner.
  - H-3: nesting. Marked "containment vs adjacency unevidenced".
  - H-4: where operational state attaches.
- S21-51 · OQ · — · L166–170 · Open questions:
  - RO-1: adopt DP-n. "It is observable in the Catalog but never declared as a concept".
  - RO-2: nesting.
  - RO-3: I-7, "it is breached".
  - RO-4: runtime adapter owner (DA).
  - MM-1 / MQ-1 carried.
- S21-52 · CONTRADICTION · L7 vs L108 · The header says I-4 was FALSIFIED. The body says the falsification was WITHDRAWN.
- S21-53 · CONTRADICTION · L89 vs L115 · The table says knowledge is "never generative". The restated I-11 says knowledge "does participate in generation".
- S21-54 · CONTRADICTION · L91 vs L159 / L167 · The table allows a Knowledge Space to contain other Knowledge Spaces, while H-3 and RO-2 say nesting is unevidenced.
- S21-55 · CONTRADICTION · L99 vs L111 · Invariants "may never be changed", yet I-7 is listed as an invariant and is breached.
- S21-56 · TENSION · L180 vs L166 · DESIGN POLICY is called "Discovered … the actual anchor" and is used in the chain, while RO-1 still asks whether to adopt it as a named layer.
- S21-57 · COUNT · L10 and L30 · "One is admitted" (see S21-40) and "12+" with 11 named (see S21-24).
- S21-58 · TRACEABILITY NOT UPDATED · L190 · Does not mention the I-4 withdrawal or the I-11 restatement.

## (f) Relations to other documents
- `KnowledgeOS_Ontology_Cross_Product_Validation.md` (L7), `KnowledgeOS_Meta_Model.md` (L20), `KnowledgeOS_Lifecycle_Gap_Analysis.md` (L108).
- Capability Catalog, CAP-001, DP-1..DP-4 and DP-2, PD-1..PD-6, and the charters Round16/17/32/38C/39/40/44–46 (L30–33).
- `knowledge-schema.yaml`, ES-005.1, ES-005.2, `statuses.yaml`, Reference Architecture §1, Round39-MC, Round47-00, RQ-002, EAD-1.
- `identifier-check.php`, `governed-registers.yaml`, `portal/graph/knowledge-graph.md`, `.claude/settings.json`, `PKS_..._M4`, "the 106 verification reports" (L138–148).
- Carried question IDs MM-1 and MQ-1 (L170).

---

# S0022 — KnowledgeOS — Ontology Cross-Product Validation (199 lines)

## (a) Status, authority and dates
- S22-01 · STATUS · "CANDIDATE" · L6 · "the ontology's own status is recorded as" STRATEGIC ONTOLOGY CANDIDATE "per the review". CV-5 (L177) still puts "Adopt the status" to the ARB.
- S22-02 · AUTHORITY · — · L7 · "Generated — never authoritative without human review"
- S22-03 · DATE · — · L8 · Commission dated 2026-08-02. The commission roles are the same as in S0021.
- S22-04 · METHOD CLAIM · — · L5 · "The ontology is HELD FIXED throughout. No concept added, removed or renamed during validation."
- S22-05 · LATER ANNOTATION · "FALSIFICATION WITHDRAWN 2026-08-02" · L73–79 · "This section's conclusion is CORRECTED." · **The body was not updated to match.** I-4 is still reported as failed or falsified at:
  - L50 "I-4 FAILS"
  - L52 "1 relationship fails"
  - L69 "One fails — I-4"
  - L91 "A projection CAN be authoritative."
  - L159 "Falsified"
  - L173 CV-1, which is premised on falsification
  - L188 "What broke"
  - L193 "broke in two places"
  - L197 "I-4 FALSIFIED"
- S22-06 · CLOSING · — · L199 · "Nothing in this document executes."

## (b) Definitions and concepts introduced
- S22-10 · DEF · — · L9 · "project the ontology into" three product domains · This is the method.
- S22-11 · DEF · — · L10 · "NOT to prove the ontology correct." · Success means surviving projection unchanged.
- S22-12 · DEF · — · L27 · PublicDigit is "(actual)". Hospital and ERP are "(hypothetical)". L25: "Conceptual only. No implementation, no schema, no code."
- S22-13 · DEF · — · L83 · I-4 original wording: "a Projection is derived and is cited as authority by nothing."
- S22-14 · DEF · — · L123–126 · Specializations:
  - S-1 PRINCIPLE: internal vs external.
  - S-2 PRODUCT: product vs deployment/tenant.
  - S-3 PROJECTION: inert vs attested.
  - S-4 KNOWLEDGE: method, rule, binding or case law. Marked "not required by the projection".
- S22-15 · DEF · — · L134–137 · Missing relationships: M-1 SPECIALIZES · M-2 CONSTRAINS · M-3 INSTANTIATES · M-4 ATTESTS.
- S22-16 · DEF · — · L140 · Structural relationships (contains, references, generates, depends on) versus "transformational" relationships.

## (c) Claims and findings
- S22-20 · PRE-VALIDATION CORRECTIONS · "ACCEPTED" · L12 · "applied BEFORE the ontology was frozen" · There are four:
  - (1) I-11 restated: "Knowledge does not EXECUTE. Capabilities execute. Knowledge constrains and informs generation." (L16)
  - (2) Engineering Method: "Recorded as HYPOTHESIS, not added." (L17)
  - (3) Runtime Adapter is outside the core: "it disappears in all three projections" (L18)
  - (4) Specialization gap: "it is the single most consequential gap the projection confirms" (L19)
  - [I] Correction 3 is "ACCEPTED" while L5 says no concept is removed. H-6 (L149) confirms it is "Still not moved".
- S22-21 · CLAIM · "INVARIANT — the strongest result" · L42 · DESIGN POLICY projects in all three domains.
- S22-22 · CLAIM · "FAILS" · L47 · "one per product, or per DEPLOYMENT?"
- S22-23 · CLAIM · "DISAPPEARS in all three" · L49 · Runtime Adapter. [I] The same row marks PublicDigit "platform-side only", not absent.
- S22-24 · SUMMARY COUNT · — · L52 · "8 invariant · 2 specialize · 1 disappears · 1 relationship fails." · See (e) for why this count does not match the table.
- S22-25 · CLAIM · "held" / "held vacuously" · L58–67 · Invariant notes:
  - I-6 "no runtime concept appears at all" (L62).
  - I-7 "held" (L63). This contradicts S0021 L111, which says I-7 is BREACHED.
  - I-8 "held emphatically", with "no hospital automates a clinical rule before ratifying it" (L64). [I] This is an unevidenced universal statement.
  - I-11: "The reviewer's reformulation is validated by projection, not by argument" (L67). [I] The projections into the hospital and ERP domains are the author's own constructions, so the "validation" is itself argument.
- S22-26 · CLAIM · — · L69 · "10 of 11 invariants survive three domains."
- S22-27 · CLAIM (superseded) · — · L91–95 · "A projection CAN be authoritative." · The original diagnosis was that I-4 conflates derivation with non-authority. It "bears on CAP-002", "whose DP-2 states the falsified form".
- S22-28 · CORRECTED CLAIM · — · L77–79 · "is UNDERSPECIFIED, not false: it holds until attestation." · "Authority has no governed state machine." · "This was a LIFECYCLE gap misdiagnosed as an ontology falsification". The mechanical cause is that `statuses.yaml` has a role table and guards, while `authorities.yaml` has none.
- S22-29 · CLAIM · "FAILS" · L102–106 · "the same ERP is deployed to fifty customers" · The model cannot express "the difference between a PRODUCT and a DEPLOYMENT of it." PublicDigit masks this: "The concept is present in the product and absent from the ontology."
- S22-30 · CLAIM · — · L112–117 · F-3, external authority. "It cannot express" who must be obeyed. In PublicDigit, election law is treated as product domain knowledge.
- S22-31 · CLAIM · — · L139–142 · "the ontology models what things ARE MADE OF, and not what happens TO them." · and "far more likely to be a real omission than an artifact of my three examples".
- S22-32 · VERDICT · — · L154 · "THE ONTOLOGY SURVIVES PROJECTION — BUT NOT UNCHANGED." · L158: "8 of 12 concepts · 10 of 11 invariants".
- S22-33 · CLAIM · — · L166 · "PublicDigit MASKED two of the three failures." · These are multi-tenancy and external legal authority.
- S22-34 · CLAIM · — · L193 · "the first evidence that the remaining ten invariants are about engineering rather than about elections"

## (d) Rules, constraints, decisions
- S22-40 · No decision is made. L95: "The ontology is not modified here." L128: "None is added. All four are recorded for the ARB."
- S22-41 · Hypotheses, none admitted (L148–150):
  - H-5 Method layer: "neither confirms nor refutes".
  - H-6 Runtime Adapter: "SUPPORTED — it disappeared in all three projections."
  - H-1 carried: "strengthened" by the ERP balance check.
- S22-42 · OQ to the ARB (L173–177):
  - CV-1: is I-4 scoped or withdrawn?
  - CV-2: product vs deployment.
  - CV-3: external authority.
  - CV-4: admit transformational relationships as a group.
  - CV-5: adopt the status label.

## (e) Open questions, gaps and internal contradictions
- S22-50 · COUNT MISMATCH · L52 vs §2 table vs L161 and L197.
  - What the §2 table shows: INVARIANT ×8 (Mission, Strategy, DP, Capability, Knowledge, Artifact, Knowledge Space, Product) · SPECIALIZES ×1 (Principle) · FAILS ×2 (PKS, Projection) · DISAPPEARS ×1.
  - L52 instead says "2 specialize".
  - L161 and the traceability line (L197) say 3 specializations (Principle, Product, Projection).
  - **PRODUCT is graded INVARIANT (L48), counted among the "8 of 12" survived intact (L158), and also listed as "Requires specialization" (L161).**
- S22-51 · CONCEPT COUNT · L158 · "12 concepts". S0021 defines 13 (C-1..C-13). VISION is absent from the §2 projection.
- S22-52 · CONTRADICTION · L73–79 vs L50/52/69/159/173/188/193/197 · The withdrawal is not reflected anywhere else in the document (see S22-05).
- S22-53 · TENSION · L5 vs L12–19 · "Held fixed" applies only after four corrections were applied, one of which (I-11) changed an invariant's wording.

## (f) Relations to other documents
- The Relationship Ontology (S0021) is the object under test.
- `KnowledgeOS_Lifecycle_Gap_Analysis.md` (L79), `statuses.yaml` and `authorities.yaml` (L79).
- DP-2 / CAP-002 Projection Integrity (L95, L173), PD-2 (L17), P1 (L126), CAP-001 (L43).
- The Certified Domain Knowledge Release and `governed-registers.yaml` (L32).

---

# S0023 — KnowledgeOS — ARB Decision Docket (198 lines)

## (a) Status, authority and dates
- S23-01 · KIND · — · L5 · "Recommendations only — every decision is the named authority's."
- S23-02 · STATUS · "CANDIDATE" · L6 · "CANDIDATE — the agenda, not the outcome"
- S23-03 · AUTHORITY · — · L7 · "Per EEP: the platform does not approve itself"
- S23-04 · ROLE / DATE · — · L8 · "Chief Knowledge Governance Officer" (secretary to the ARB), dated 2026-08-03.
- S23-05 · REVISION · — · L9 · "REV 2, 2026-08-03" · Package 1 was amended per the PA review and Package 11 was added. **The body was only partly updated:**
  - The scope line (L10) still says "the ten D-items".
  - The one-view table (L181–192) lists 10 rows with no Package 11. Row 1 does reflect D-9a/D-9b.
  - The traceability line (L196) still says "ten one-page packages".
- S23-06 · IN-PLACE AMENDMENT · "DOWNGRADED" · L34 · "That line is DOWNGRADED from trigger evidence to illustration pending metric definition." · The original evidence line at L22 was left unedited, with the amendment written beneath it.
- S23-07 · CLOSING · — · L198 · "It is an agenda."

## (b) Definitions and concepts introduced
- S23-10 · DEF · — · L40–41 · D-9a means defining the Platform Cost basis. D-9b means running the over-evolution check.
- S23-11 · DEF · — · L49 · The enacted mission text: "provide a reusable engineering platform that improves the development of software systems through governed engineering knowledge".
- S23-12 · DEF · — · L75 · The three knowledge ontologies: L-A (executable, artifacts) · the Metamodel (CANDIDATE, with gate OQ-ENG-004) · RQ-002 ("COMPLETE, dimensional").

## (c) Claims and findings
- S23-20 · CLAIM · — · L12 · "The bottleneck has shifted from finding answers to deciding which validated answers become canonical."
- S23-21 · CLAIM · — · L21 · "The tripwire cannot fire if the metric is never computed"
- S23-22 · EVIDENCE (later downgraded) · — · L22 · "20+ platform-only documents and zero PublicDigit feature progress in this track" · The documents are "(self-declared in four of them)".
- S23-23 · COUNTER-EVIDENCE · — · L23 · "the platform work was commissioned, not drift" · There was dense product delivery in parallel (WP-6/7A/7B/7C, WP-3A/4A).
- S23-24 · CLAIM · — · L50 · "8 artifacts, none product-specific" · The mission is enacted but unratified.
- S23-25 · CLAIM · — · L62–64 · "consumption model falsified by E-1; disposition PENDING ARB" · Supporting evidence is "the 13%-declared-scope finding" and ten rediscoveries "despite the EKP existing".
- S23-26 · CLAIM · — · L102 · "the register is provably not the complete record of authorizations" · For CAP-001, the catalog says "unauthorized", the README says "REALIZED", and the register says nothing.
- S23-27 · CLAIM · — · L115 · "the platform governs by a proposed constitution" · There are six PROPOSED standards and a DRAFT kernel.
- S23-28 · CLAIM · — · L116 · "survived a foreign-domain bootstrap with zero translation (EEP) and discriminating force (tactical principles)" · [I] This cites only the favourable half of S0025 (see cross-file).
- S23-29 · CLAIM · — · L129 · "none found — cost is one list" · This is the opposing evidence for D-7.
- S23-30 · CLAIM · — · L140 · "the corpus states everything else this honestly; this it does not state" · This is C-6, on session-simulated role separation.
- S23-31 · CLAIM · — · L151–154 · "Admit C-1 (n=11) to the requirement-candidate register" · Counter-evidence: "occurrence #10 came from an INDEXED region".
- S23-32 · CLAIM · — · L166 · "the PA declares Phase I (knowledge engineering) ENDED" · The PA also calls the Semantic Architecture "stable enough".
- S23-33 · CLAIM · — · L168 · Opposing evidence for Package 11 has three parts:
  - (a) it is Phase-C-class work behind the B.5 gate;
  - (b) "services" collides with N-10 / Reference Architecture §1, and the only evidence is A-11, "which authorizes a question, not a service layer";
  - (c) it is the AIP-14 exposure itself.

## (d) Rules, constraints, decisions — all are recommendations by the secretary; none is adopted
- S23-40 · Pkg 1 · D-9 · ARB · "ADOPT — run the check before anything else on this docket" (L28). Amended to D-9a "ADOPT a basis of delivered-capability + effort", which "reject[s] raw document count" (L40), and D-9b "ADOPT — immediately after D-9a" (L41).
- S23-41 · Pkg 2 · D-5 · sponsor · ADOPT (L56).
- S23-42 · Pkg 3 · D-1 · ARB · "ADOPT a scoped disposition" (L69).
- S23-43 · Pkg 4 · D-8 · ARB · "ADOPT RQ-002 as the dimensional frame; SCOPE the other two under it" (L82). Opposing: this pre-empts the OQ-ENG-004 gate.
- S23-44 · Pkg 5 · D-3 · ARB · "ADOPT the amendment procedure", but defer the context per R-64 and annotate BC-5 (L95).
- S23-45 · Pkg 6 · D-2 · DA · "ADOPT — mint with disclosure + adopt the forward rule" (L108). Includes "CAP-001 would check the ruling that authorizes CAP-001" (L103).
- S23-46 · Pkg 7 · D-4 · ARB · "ADOPT — begin with ES-005 and the EEP" (L121). The kernel lifecycle condition is "arguably satisfied; the ARB must judge" (L117).
- S23-47 · Pkg 8 · D-7 · "the Authority" (AP-4) · ADOPT (L133).
- S23-48 · Pkg 9 · D-6 · DA · ADOPT (L145).
- S23-49 · Pkg 10 · D-10 · ARB · "ADOPT as candidate" with no remedy designed (L157).
- S23-50 · Pkg 11 · ARB + sponsor · "DEFER until D-9a/D-9b and the three doors resolve" (L173), then ADOPT with "service" declared to mean a responsibility grouping.
- S23-51 · ORDERING RULE · — · L26 · Package 1 gates "whether Packages 2–10 are worked now at all". [I] Package 11 is gated separately at L173.

## (e) Open questions, gaps and internal contradictions
- S23-60 · COUNT · L10, L181–192, L196 say ten packages, but eleven exist (see S23-05).
- S23-61 · SELF-DEFECT ACKNOWLEDGED · L34 · Package 1's evidence presupposed document count as the metric, which was the undecided question.
- S23-62 · ANNEX · L177 · Carried, unpackaged question inventory: PM-1..5, SC-1..8, LG-5/CV-4, MQ-7, OQ-K1..K6, OQ-C1..C2, CV-1..CV-5, MM-1..MM-4 and IR-2..IR-5. The annex says "several are subsumed by Packages 1–10", but which ones are subsumed is not mapped.
- S23-63 · [I] TENSION · L79 vs S0021 · Package 4 warns that "a fourth ontology will eventually be commissioned". The Relationship Ontology (S0021, dated 2026-08-02, before this docket) is not among the three ontologies named.
- S23-64 · [I] "Three doors" (L173) is not defined in this file.

## (f) Relations to other documents
- AIP-14, AIP-13, ADR-AIP-02, the Phase B.5 gate, the PA review, "the blind review" (risk #8), and A-1/A-2, A-11, A-12.
- ES-005.3, ES-006.4, the Reference Architecture (Platform ≙ Adoption split; §1 / N-10), MQ-7, E-1, the EKP, SC-6, L-A, the Metamodel / OQ-ENG-004, RQ-002, MM-1..4.
- R-63, R-64, BC-5, C-7, PD-*/BC-*, R-4, CAP-001, PMR-10, R-53, C-2/D-3, EEP, G-1, AP-4, C-6, SC-2, C-1, PG-8, L-A/L-B/L-C, "init→PKS hypothesis (n=0)".
- Phase C, and CV-1..CV-5 (S0022's questions).

---

# S0024 — KnowledgeOS — Architecture Fitness Assessment (210 lines)

## (a) Status, authority and dates
- S24-01 · KIND · — · L5 · "Not discovery · not brainstorming · not redesign." · The document lists what it does not do: no ADR update, no folder restructuring, no extraction, no implementation, no capability design, no new governance.
- S24-02 · AUTHORITY · — · L6 · "Generated — never authoritative without human review" · No status word appears in the header.
- S24-03 · DATE · — · L7 · The commission (plus the Senior Knowledge Engineer's Addendum) is dated 2026-08-02.
- S24-04 · BASELINE · — · L8 · Treated as closed: Architecture Baseline, Product Boundary Discovery, Architecture Consolidation, Strategic Boundary Consolidation, MVK Bootstrap Validation, CAP-001, Engineering Platform, Round47-00, Round47-OP.
- S24-05 · PLACEMENT NOTE · — · L9 · "the explicit instruction is followed" · The filename lacks the date prefix its sibling files use.
- S24-06 · LATER CORRECTION · "CORRECTION APPENDED 2026-08-02" · L12–14 · "Row 2's EVIDENCE cell was wrong." · and "The gap-kind analysis below is unaffected; the maturity scoring of row 2 is." · The evidence cell for row 2 was updated (L49). **Row 2's Level is still L1.** The dimension totals (L66) were not revisited.
- S24-07 · LATER CORRECTION · "IS TOO STRONG" · L70–82 · The "0 of 11 enforcing" finding is corrected. **The body was not updated to match** — see S24-31 and S24-61.
- S24-08 · CLOSING · — · L210 · "Nothing in this document executes."

## (b) Definitions and concepts introduced
- S24-10 · DEF · — · L10 · The fitness function: "Does today's architecture support" `knowledgeos init`.
- S24-11 · DEF · — · L18–22 · Three gap kinds: ARCHITECTURAL (resolved by modelling) · GOVERNANCE (resolved by a ruling) · EVIDENCE (resolved "only by doing it").
- S24-12 · DEF · — · L44 · The matrix dimensions are METHOD, BINDING, EVIDENCE and IMPLEMENTATION, plus ENFORCEMENT, which this assessment adds. Levels run L0–L4.
- S24-13 · DEF · — · L74–78 · BOUNDARY = `permissions.deny` · TRIGGER = `permissions.ask` · ADVISORY = "mentions it and proceeds".
- S24-14 · DEF · — · L137 · "Only Tier-1 items — domain-free ∧ binding-free ∧ evidence-free:" · This is the kernel-membership criterion.
- S24-15 · DEF · — · L159–162 · GENESIS: a route from idea to first decision.

## (c) Claims and findings
- S24-20 · CLAIM · "exactly ONE" / "ONE" / "FOUR" · L20–24 · "1 architectural · 1 governance · 4 evidence." · The Addendum's view, "the gap is not architecture, it's evidence", is "CONFIRMED".
- S24-21 · VERDICT · — · L30–31 · "ARCHITECTURALLY: ALMOST — one gap, and it is not where the vision suggests." · and "EVIDENTIALLY: NO" · `init` is called PREMATURE.
- S24-22 · CLAIM · — · L35 · "9 of 11" responsibilities have a place, a method and an owner. [I] The two that do not are never identified. GENESIS, the architectural gap, is not one of the 11 rows.
- S24-23 · CLAIM · — · L36 · GENESIS: "the platform governs" change and "it has no route from" idea to first decision. Cites Reference Architecture §7 and ES-002.1.
- S24-24 · CLAIM · — · L37 · SD-1 is the governance gap.
- S24-25 · CLAIM · — · L38 · "PKS Generation is n=0." · P3 requires n ≥ 2 before something becomes a component.
- S24-26 · CONSTRAINT · — · L40 · "Stage 1 of that charter is unapproved." · `init` is charter gate 4.
- S24-27 · MATRIX · — · L48–58 · Row-level points:
  - Row 2 says "CORRECTED 2026-08-02: NOT n=1 — an EXECUTED 16-round programme, 92 artifacts".
  - Row 3 says "n=1, proven in a foreign domain — rejected 5 candidates".
  - Row 6 says "n=1 + 1 decision changed".
  - Rows 10 and 11 are at n=0.
  - Every row is marked not enforcing.
- S24-28 · TOTALS · — · L64–68 · METHOD "8 of 11 complete" · BINDING "4 of 11 carry a coupling" · EVIDENCE "4 of 11 at n=0" and "only 3 have more than a single instance" · IMPLEMENTATION "6 of 11 fully manual" · ENFORCEMENT "0 of 11".
- S24-29 · CORRECTED CLAIM · — · L72 · `.claude/settings.json` holds deny ×19 and ask ×22, which are "41 controls the runtime genuinely ENFORCES". The author adds: "What I actually established is that" no capability enforces.
- S24-30 · CLAIM · — · L80 · "enforcement lives in the RUNTIME ADAPTER; capabilities live in the PLATFORM." · Record: `KnowledgeOS_Operational_Knowledge_Principles.md` §2.
- S24-31 · CLAIM (superseded, not edited) · — · L86–90 · "Not one responsibility is enforcing." · "the platform is adoptable by habit, never by mechanism." · "The platform has never had one, at any maturity level."
- S24-32 · CORRECTIONS TO THE ADDENDUM · — · L96–98:
  - (1) SD-1 was misattributed to Product Discovery.
  - (2) ES-005.1 "names PublicDigit and hard-codes .claude/".
  - (3) "A specification is not an implementation." The Runtime Adapter is L1, not L3.
- S24-33 · CLAIM · — · L100 · "Capability Runtime" "names a runtime that does not exist". The term is recorded for retirement but not renamed.
- S24-34 · CLAIM · — · L112 · "Five of eleven need no architecture at all. Their gap is use." · These are rows 3, 8, 6, 11 and 10.
- S24-35 · CLAIM · — · L118–122 · The research items are rows 1 and 4 and G (Genesis). "Everything else is not research. It is unexercised."
- S24-36 · CLAIM · — · L128–133 · Never separate products: the Runtime Adapter · "A Product PKS", which is "an instance", "one per product, by definition" · evidence and learning records · Baseline subsystems · KnowledgeOS itself today ("The gate is shut").
- S24-37 · PROPOSAL · — · L139 · Kernel contents:
  - ES-001..006, with ES-005.1 restated product-neutrally
  - EEP and the "EP-01/02/03 role model"
  - the closed verdict vocabulary
  - ES-005.3, ES-006.1 and ES-006.4
  - the tactical principles
  - Round47-OP's nine criteria, with SD-1 removed
  - PMR-9 and PMR-10
  - the Capability Mapping vocabulary
  - CAP-001's shape
  - "the Reference Architecture, once ADOPTED"
  - [I] Several members are conditional on changes that have not happened yet.
- S24-38 · PROPOSAL · — · L143–145 · PKS contents: "The BINDING and the EVIDENCE layers — this is P1 applied:"
- S24-39 · CLAIM · — · L149 · Business-product items: `app/` (1,532 files), `docs/architecture/` (448), and others.
- S24-40 · CLAIM · — · L155 · "It is the ONLY gap in this assessment that cannot be closed by a ruling or by doing the work" · Its size is "small", a "one clause" fix (L162). Its status is "OQ-S3", and "not the platform's responsibility" is called a legitimate answer.
- S24-41 · CLAIM · — · L167–173 · "is SD-1 a PRODUCT BINDING or a PLATFORM RULE?" · Testable: "re-run the bootstrap instrument with" Round47-OP added and SD-1 suspended.
- S24-42 · CLAIM · — · L175–181 · "the three evidence gaps" · E-1 PKS Generation n=0 · E-2 Operational Learning, 0 traversals · E-3 Capability Evolution, n=0 promotions.
- S24-43 · CLAIM · "AFFIRMED" · L185 · "BRM-1 retired extraction-by-COPY. It did not retire extraction-by-DECOMPOSITION." · L189: decomposition followed by materialization "is still barred."
- S24-44 · CLAIM · — · L191 · "neither Tier-2 nor Tier-3 blockage is detectable by vocabulary search" · The ES-005.1 leak is counted as a fourth instance.
- S24-45 · VERDICT · — · L197–204:
  - "For today's work: YES."
  - Redesign: NO.
  - "1 architectural gap (Genesis) · 1 governance ruling (SD-1) · 3 evidence gaps"
  - "ENFORCEMENT is absent everywhere"
  - "The architecture is not underdesigned. It is under-exercised — and nowhere enforcing."
  - "Do not design more."

## (d) Rules, constraints, decisions
- S24-50 · No decision is made. Questions OQ-S1, OQ-S3 and OQ-S4 are routed to the ARB. The traceability line (L208) lists everything the document did not do.
- S24-51 · CONSTRAINT cited · L40 · Charter gate 4 cannot be waived. · L38 · P3 forbids making something a component below n = 2.

## (e) Open questions, gaps and internal contradictions
- S24-60 · COUNT · "4 evidence" (L22, L24, L208) vs "three evidence gaps" (L175) and "3 evidence gaps" (L199).
- S24-61 · CONTRADICTION · The L70–80 correction (41 enforced controls) versus L86 "Not one responsibility is enforcing", L200 "absent everywhere", L202 "nowhere enforcing" and L208 "0 of 11". [I] The correction also places enforcement in the Runtime Adapter, but matrix row 7 (Runtime Adapter) still shows not enforcing.
- S24-62 · COUNT · BINDING · L65 · "4 of 11 carry a coupling", but five items are enumerated in the same cell. Six rows in the matrix show a non-"none" binding (rows 1, 2, 4, 5, 8, 9).
- S24-63 · COUNT · L66 · "only 3 have more than a single instance" was not updated after row 2 was corrected to 92 artifacts.
- S24-64 · COUNT [I] · L67 · "6 of 11 fully manual". The matrix shows "manual" exactly in rows 2, 3, 10 and 11, plus row 9 as "manual + 1 logger hook". I could not reproduce 6.
- S24-65 · COVERAGE [I] · Rows 2, 5, 7 and 9 appear neither in §4 ("only implementation") nor in §5 ("research"). Yet L122 says everything else is "unexercised".
- S24-66 · TENSION · L24 confirms "the gap is not architecture" while L30 and L36 state one architectural gap. L155 says GENESIS cannot be closed by a ruling, while L162 sizes it as "one clause".
- S24-67 · CORRECTION SCOPE · L14 says row 2's maturity scoring is affected, but the row stays at L1 (L49).

## (f) Relations to other documents
- `KnowledgeOS_Platform_Capability_Model.md` §0 (L12) and `KnowledgeOS_Operational_Knowledge_Principles.md` §2 (L82).
- `docs/architecture/discovery/` (Rounds 16–31), the Reference Architecture §7 and §2 of the Baseline, ES-002.1, SD-1, Round47-OP, the R16 Workbook, `DDD_Tactical_Governance_Principles`, ES-001..006, the EEP, CAP-001 OE-1.
- H-CAT-1, P1, P2, P3, OQ-S1/S3/S4, BRM-1, SD-EXT-1, EAD-1, PMR-9/10, the charter (gate 4, Stage 1), "the 106 verification reports", `identifier-check.php`, `.claude/settings.json`.

---

# S0025 — MVK Bootstrap Validation — Experiment Report (196 lines)

## (a) Status, authority and dates
- S25-01 · KIND · — · L5 · "An experiment, not a commitment." · No file was moved, nothing was extracted and no architecture was proposed.
- S25-02 · AUTHORITY · — · L6 · "Generated — never authoritative without human review"
- S25-03 · DATE · — · L7 · The commission is dated 2026-08-02: "validate the MVK through a real second product bootstrap".
- S25-04 · PLACEMENT · "DERIVED" · L8 · → `docs/knowledgeos` with "exit 0".
- S25-05 · RAW RECORD · — · L10 · A scratchpad path to `mvk-bootstrap-record.md`. [I] This is outside the governed tree, and I did not read it.
- S25-06 · VERDICT · "FAIL" · L14 · "the MVK is insufficient to bootstrap a product"
- S25-07 · LATER ANNOTATION · — · L16–25 · "SCOPE CORRECTION (PA, 2026-08-02)":
  - "invalidated the EXTRACTION BOUNDARY. It did NOT invalidate the platform."
  - VALIDATED: "the EEP transferred with zero translation".
  - INVALIDATED: "the 15-file set named in §6 of the Product Boundary Discovery".
  - "And F-3 below is REFUTED".
- S25-08 · LATER ANNOTATIONS · "REFUTED 2026-08-02" · L71 (reach #2), L116 (F-3), L140 (addition B restated). **The body was not updated to match:**
  - L130 "these are the firm findings" (F-2 and F-3)
  - L169 "Close F-2 and F-3 first."
  - L187 lists "strategic method" as missing
  - L107 records Strategic DDD as INCONCLUSIVE, "no method available"
  - The traceability line (L194) mentions no refutation.
- S25-09 · CLOSING · — · L196 · "Nothing in this document executes."

## (b) Definitions and concepts introduced
- S25-10 · DEF · — · L9 · The principle at stake: "No reusable component until it has earned reuse through at least one real product experience."
- S25-11 · DEF · — · L40 · The MVK boundary: "read access to exactly 15 file paths" totalling "244 KB".
- S25-12 · DEF · — · L42 · "StockRoom" is the test product: a hardware shop's inventory.
- S25-13 · DEF · — · L62 · The Out-of-Boundary Register: "14 reaches, 3 of them contamination".
- S25-14 · DEF · — · L120 · The meta-finding: "A platform that governs CHANGE does not automatically govern GENESIS".

## (c) Claims and findings
- S25-20 · CLAIM · "REALIZED" · L27–29 · "AR-1 IS REALIZED." · and "This is the experiment working, not failing."
- S25-21 · METHOD · — · L35 · "I could not be the fresh session." · An isolated session executed the bootstrap.
- S25-22 · DISCLOSURE · — · L43 · The session inherits the project's `CLAUDE.md`. "It caught and refused two such pulls, and disclosed a third".
- S25-23 · CLAIM · — · L49–58 · What the bootstrap produced:
  - one bounded context, `Inventory`
  - 7 ubiquitous-language terms
  - invariants INV-1..3
  - decision DEC-SR-001, with eight properties, "authored with" "no template available"
  - capability CAP-SR-001
  - five candidates "killed by MVK rules": 2 value objects + 1 event + 1 command + 1 repository method = 5. The count matches.
- S25-24 · CLAIM · — · L60 · Concurrent adjustment was "demoted" "from invariant to mechanism".
- S25-25 · REACHES · — · L68–77 · Load-bearing reaches:
  - #1: the strategic-DDD method was "explicitly EXCLUDED".
  - #3: "could not lawfully begin". EP-01-Light is the source project's property.
  - #4: no genesis path; "had to invent an ordering".
  - #2: "REFUTED 2026-08-02:". The template exists, but "It was not in the MVK, and it was not indexed anywhere".
  - #7: "TWO closed sets collide." These are ES-003.1 and CAP-001 §5.
  - #8: ES-005.1 names PublicDigit.
  - #9: ES-005.4 is DEFERRED, and "a deferred rule cannot be cited as authority."
  - #11 and #12: contamination, refused. Also: "The EEP has no business-capability, strategic-DDD, canonical-discovery, stewardship or impact-classification phase".
  - #13: the brief supplied the sequence. "Left to the MVK alone I would have begun at" Idea → Implementation Plan.
- S25-26 · CLAIM · — · L80 · "The true state is worse than FAIL suggests, not better."
- S25-27 · TRANSFER RESULTS · — · L86–94:
  - EEP: "USED — strongest element." and "Also the source of the blocking finding".
  - Tactical principles: "All seven fired".
  - CAP-001 README: the template claim held.
  - ES-005: "PARTLY UNUSABLE".
  - PKS_Knowledge_Integrity_Model: "~10% used, ~90% source case law".
  - Phase_II Baseline: "~95% source issuance record." Only PMR-9 and PMR-10 are portable.
- S25-28 · CLAIM · — · L97 · "The three methodology documents are 73% of the MVK by volume and mostly non-portable case law." · and "Domain-free is not the same as portable". The author also built §6 of the Product Boundary Discovery and is now refuting their own earlier list.
- S25-29 · PER-SCOPE VERDICTS · — · L103–108 · Tactical DDD PASS · Knowledge integrity PASS · Placement WARN · Identifier INCONCLUSIVE · Strategic DDD INCONCLUSIVE ("no method available") · Execution governance FAIL ("mandatory stages structurally unsatisfiable").
- S25-30 · FAILURES · — · L114–116:
  - F-1: "The MVK forbids proceeding and supplies no lawful way to proceed".
  - F-2: no genesis path.
  - F-3: "REFUTED 2026-08-02." and "RESTATED: the method EXISTS but is product-coupled". Round47-00 is ADOPTED and Round47-OP is BINDING. The blocker is the product path and SD-1.
- S25-31 · CLAIM · — · L122 · "No amount of further analysis inside PublicDigit could have surfaced this."
- S25-32 · BOUNDS · — · L128–131 · n = 1, i.e. "one product · one engineer · one attempt". F-1 depends on team size: for an organization, "that scope's verdict would be WARN, not FAIL". F-2 and F-3 are "the firm findings". The result flatters the MVK.
- S25-33 · CLAIM · — · L155 · "A Bootstrap Engine would have automated a process that does not yet exist." · The gaps are said to lie in governance and method, not in tooling.
- S25-34 · CLAIM · — · L161–165:
  - The independence claim is "partly true and now measured".
  - MVK sufficiency is "REFUTED at n=1".
  - §6 is refuted in part.
  - L-2 "generate a PKS" is still a hypothesis: "nothing was generated".
- S25-35 · CLAIM · — · L176 · CAP-001 is "still" at "0 executions, 0 decisions changed".
- S25-36 · CLAIM · — · L186–190 · Genuinely reusable: the EEP, the tactical principles, the CAP-001 template, ES-005.3 and the restraint rules. Missing: "genesis · role scaling · acquisition · strategic method · one verdict vocabulary". Closing line: "The platform's reusability claim is no longer an assertion."

## (d) Rules, constraints, decisions
- S25-40 · PROPOSAL · candidates · L135–145 · Seven additions, A–G, each to an existing home. "No ES-007 is proposed".
  - A: genesis clause.
  - B: restated. "the module EXISTS." It is blocked on OQ-S1.
  - C: EEP role-scaling clause.
  - D: decision-record contract.
  - E: verdict-vocabulary scoping rule.
  - F: acquisition rule. "Addition F is not a suggestion — it is a deferral coming due."
  - G: ES-005.1 restated.
- S25-41 · RECOMMENDATION · — · L173 · Put A, B, C and F to the Authority "not as amendments" but as candidates, per ES-006.1.
- S25-42 · RECOMMENDATION · — · L174–176 · Re-run the experiment. Do not extract and do not build a Bootstrap Engine. Run CAP-001 at the next real minting.
- S25-43 · NON-ARISING · — · L151–153 · "DOES NOT ARISE — the commission conditioned it on PASS." · Kernel, Capability SDK, Bootstrap Engine, PKS Generator, Validation Engine and Extension Model "remain HYPOTHESES."
- S25-44 · The scope correction at L16 is attributed to the "PA". Nothing in this file is adopted.

## (e) Open questions, gaps and internal contradictions
- S25-50 · CONTRADICTION · L22 says the EEP process is "VALIDATED". L86 says the EEP was "the source of the blocking finding", and L108 gives execution governance (EEP conformance) a FAIL. [I] "Transferred" and "satisfiable" are being conflated.
- S25-51 · CONTRADICTION · F-3 is refuted at L25 and L116, but is still treated as a firm finding at L130, L169 and L187 (see S25-08).
- S25-52 · COUNT · L62 claims 14 reaches. Only 10 are tabled (#1, 2, 3, 4, 7, 8, 9, 11, 12, 13); #5, #6, #10 and #14 are not shown. The table is introduced as "load-bearing entries".
- S25-53 · COUNT [I] · L40 claims 15 files, but the §4 table names 14. The 15th (possibly the Reference Architecture, which is cited in reach #4) is not listed.
- S25-54 · AMBIGUITY [I] · L97 · "The three methodology documents" are not identified. Two candidates are rated mostly non-portable, while the Contract Review Method and ARB Review Discipline are rated "USED", more portable than a template.
- S25-55 · TENSION [I] · L43 frames contamination as pulls from the inherited `CLAUDE.md` ("refused two", "disclosed a third"). But reach #13 is brief-supplied scaffolding, not a `CLAUDE.md` pull.
- S25-56 · TENSION · L55 says no template was available. L71 says one exists outside the MVK. These are consistent only if read as "within the boundary".

## (f) Relations to other documents
- Product Boundary Discovery §6 and AR-1, AD-1, M6, EAD-1, BRM-1, the EEP §2/§3/§5, EP-01-Light, the Reference Architecture §7 and its "Platform ≙ Adoption split".
- ES-001..006 (with ES-001.1, ES-002.1, ES-003.1, ES-004.2, ES-005.1–.4, ES-006.1), the STANDARDS_INDEX, CAP-001 README and §5, `DDD_Tactical_Governance_Principles`, `PKS_Knowledge_Integrity_Model`, `PKS_Phase_II_Methodology_Baseline_v1_2`, `PKS_Knowledge_Contract_Review_Method`, `PKS_ARB_Review_Discipline`, PMR-9, PMR-10.
- `docs/architecture/discovery/Round17_ARB_Decision_Record_Template.md`, `doc-placement.php`, Round47-00, Round47-OP, SD-1, OQ-S1, `2026-08-02-knowledgeos-strategic-boundary-consolidation.md` §0, and L-2.

---

# Cross-file observations

## Chronology
- S0021, S0022, S0024 and S0025 are all dated 2026-08-02. S0023 is dated 2026-08-03 (REV 2). Every in-file correction or annotation I found is also dated 2026-08-02.
- S0022 says it held S0021 fixed. Yet S0021 carries edits made after S0022: the header at L7 cites S0022's results, and the I-11 row says "validated in all three domains". So S0021 was edited after S0022 was written.
- S0022 says the I-11 restatement was applied "BEFORE the ontology was frozen". S0021 records it as "review 2026-08-02".
- S0024 treats "MVK Bootstrap Validation" (S0025) and "Strategic Boundary Consolidation" as a closed baseline (L8). So S0024 is later than S0025.
- S0024's GENESIS wording (L36, L161) repeats S0025 reach #4 (L70) and the meta-finding (L120) word for word.
- S0023 is the last of the five. It carries S0022's CV-1..CV-5 as open (L177).

## Contradictions and tensions between files
1. **I-4 falsified or withdrawn.** S0021 L7 and S0022 L50, L52, L69, L159, L188, L193 and L197 still say I-4 was falsified. S0021 L108 and S0022 L73–79 say the falsification was withdrawn on the same day. S0023 still carries CV-1, which is premised on the falsified form.
2. **I-7.** S0021 L111 says it is "stated and BREACHED" (EAD-1). S0022 L63 says it "held" in all three domains, with no mention of the breach.
3. **Concept count.** S0021 defines 13 concepts (C-1..C-13). S0022 validates "8 of 12" and omits VISION.
4. **One PKS per product.** S0022 F-2 (L97–106) says this claim FAILED. S0024 L129 restates it as fact: "one per product, by definition".
5. **Strategic Ontology Candidate status.** S0021 L6 says the label is "adopted per review". S0022 L177 (CV-5) asks the ARB to adopt it, and S0023 L177 still carries CV-5 as open on 2026-08-03. [I] So the "adoption" was not an ARB act.
6. **The EEP and ES-005 as the strongest ratification candidates.** S0023 Package 7 (L116, L121) cites the bootstrap as evidence the EEP "survived" with "zero translation", and recommends beginning ratification "with ES-005 and the EEP". Against this:
   - S0025 records an execution-governance FAIL (L108).
   - S0025 calls the EEP "the source of the blocking finding" (L86).
   - S0025 finds ES-005 "PARTLY UNUSABLE", with .1 naming PublicDigit and .4 silent (L90).
   - S0024 identifies ES-005.1 as a binding leak inside governance (L52, L97).
   - [I] S0023 cites only the favourable half of S0025.
7. **Classifying GENESIS.** S0024 calls it the "exactly ONE" architectural gap with "no place in the architecture to put it" (L155). S0025 places the gaps "in GOVERNANCE (genesis, roles, acquisition)" (L155) and gives addition A an existing home, Reference Architecture §7 + ES-002.1 (L139).
8. **Gap totals.** S0024 counts 1 architectural + 1 governance + 3 or 4 evidence gaps. S0025's missing list (L187) also includes role scaling (F-1, addition C), acquisition (addition F) and one verdict vocabulary (addition E). S0024 does not count any of these as gaps.
9. **CAP-001 execution evidence.** S0024 row 6 (L53) says "n=1 + 1 decision changed". S0025 L176 says "still" "0 executions, 0 decisions changed". Both are dated 2026-08-02.
10. **Kernel.** S0025 L153 says the Kernel and all other components "remain HYPOTHESES" because a FAIL evidences no component. S0024 §7 (L137–139) nonetheless lists what "belong[s] inside the KnowledgeOS kernel". That list includes the "EP-01/02/03 role model", which S0025 reach #12 (L76) treats as contamination and source-project property.
11. **The 13% coverage figure.** S0021 L75 calls it "a scope decision, not a bug". S0023 L64 uses "the 13%-declared-scope finding" as supporting evidence that the EKP consumption model is falsified.
12. **The same identifier, E-1, means two things.** In S0023 (L62) it is the EKP consumption-model falsification. In S0024 (L179) it is the PKS-Generation n=0 evidence gap.
13. **Number of ontologies.** S0023 Package 4 counts three ontologies (L-A, Metamodel, RQ-002) and warns of "a fourth". S0021 (an earlier relationship ontology) and the `KnowledgeOS_Meta_Model.md` it critiques are not reconciled with that count. [I] It is unclear whether "Metamodel" in S0023 is the same document as `KnowledgeOS_Meta_Model.md`.
14. **Enforcement.** S0024's correction (L72–80) places 41 enforced controls in the Runtime Adapter, while S0024's own verdict says enforcement is absent everywhere. S0021 lists the Runtime Adapter with "no owner found" (C-12, RO-4). S0022 says it "disappears" outside PublicDigit. [I] The only enforcing element found is the one concept the other documents treat as peripheral or ownerless.
15. **"Governed statement" sources.** S0021 traces invariants to RQ-002 (I-1) and the Reference Architecture §1 (I-8). S0023 says RQ-002's adoption is still to be decided (Package 4) and that the platform "governs by a proposed constitution" (Package 7). S0024 says "the Reference Architecture, once ADOPTED" (L139). [I] The S0021 claim "each traced to a governed statement" (L101) rests in part on sources that are not adopted.

## Dependencies between files
- S0022 depends on S0021 (it is the object under test).
- S0021's I-4 and I-11 annotations depend on S0022's results and on `KnowledgeOS_Lifecycle_Gap_Analysis.md`, which I did not read.
- S0024 depends on S0025 for: the n=1 tactical evidence and its five rejections, GENESIS, SD-1 / F-3, the ES-005.1 leak, and the "bootstrap instrument" that OQ-S1 proposes to re-run.
- S0023 depends on S0025 (Package 7's evidence) and on S0022 (the CV annex). It also depends on documents outside this set: the Phase B.5 / PA review, A-1..A-12, E-1 and the C-series.
- Both S0024 and S0025 converge on OQ-S1 (SD-1: product binding or platform rule) as a single high-leverage ruling.

## Shared constraints
- All five documents declare "Generated — never authoritative without human review".
- Four end with "Submitted to the Decision Authority" and "Nothing … executes". S0023 instead ends "It is an agenda."
- None of the five records an adopted decision by the ARB, DA or sponsor. Every "ADOPT", "ADMIT" or "REJECT" is the author's or the secretary's recommendation.
