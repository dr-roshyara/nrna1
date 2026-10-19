# Concept Validation Register (proposed; scratchpad while the repository is frozen)

**Governing rules (Principal, 2026-10-18):**
- Concept Validation and Theory Admission Rules;
- Progressive Concept Tracking;
- one sequence for development, one register for status;
- **explicit dependencies, with controlled revision** when a foundation fails;
- "a concept may be recorded without being accepted, investigated without being believed, and rejected without being erased".

**Status values:** PROPOSED · UNDER_VALIDATION · UNRESOLVED · NEEDS_REVISION · VALIDATED · REJECTED · SUPERSEDED.
- Missing evidence ≠ invalidity.
- Repeated assertion ≠ independent confirmation.

**Source IDs:** S-IDs from the index; [BOOK] for the manuscript; [M2] for the §1.14.1 repair.

**Validation is not admission.** VALIDATED (research) never means "adopted": governance status is tracked separately (the CE-practice dimensions).

## Entries

| KC | Concept (provisional definition) | Source | Scope | Supporting evidence | Counterevidence / limits | Validation criteria (support · falsify) | Status | Depends on | Theory section | Open questions |
|---|---|---|---|---|---|---|---|---|---|---|
| **KC-001** | **Authority as permission to act**: act × scope × time | [BOOK] §1.6; AMS L49 [Ind] | actors and grants | AMS definition; §1.13 data | live grants lack holder and period; not evaluable today | support: consistent use for actors · falsify: the corpus defines authority only as an artifact property | UNDER_VALIDATION | — | Ch. 1 §1.6, §1.13, §1.14.1 | which concept keeps the word (reserved) |
| **KC-002** | **Authority as artifact trust and origin**: provenance × standing; the `authority` field | S0002 L37; S0003 L56; S0007 L26; S0008 SC-5; S0010 L88; `authorities.yaml` header [Ind] | knowledge artifacts | schema header verified; W3C PROV analogy [CANONICAL] | S0008 itself flags it as two questions in ONE field (SC-5) | support: used only for artifacts · falsify: used for actors | UNDER_VALIDATION | — | Ch. 1 §1.2 (dimensions of a claim) | is it one concept or two (SC-5)? |
| **KC-003** | **KC-001 vs KC-002: term drift, not contradiction** | [Ded] from KC-001 and KC-002 | terminology | different bearers and arity | the Engineering Progression Model is not read | see S-BATCH-S0006-S0010 §5 | UNRESOLVED (hypothesis) | KC-001, KC-002 | glossary | reserved: naming |
| **KC-004** | **Invariant numbering: two schemes** (book I-1…I-11 vs platform I-1…I-9) | [BOOK] Ch. 0 L167–177 [Ind]; S0008 L23–32; S0001 L9 | citations | both tables read | — | support: no single list contains both meanings · falsify: a mapping document unifies them | UNRESOLVED (explained) | — | every "I-n" citation | reserved: which is authoritative |
| **KC-005** | **Status ⟂ owner ⟂ asset identity** (three independent dimensions of an architectural asset) | S0001 L9 | architecture assets | ownership table read from headers [Src] | headers not independently verified | support: cases of owned drafts and ownerless candidates · falsify: a rule tying them | PROPOSED | KC-004 (its "I-9") | (future) architecture chapter | — |
| **KC-006** | **Unowned-unadopted is correct; unowned-operating is a defect** | S0001 L33–40 | governance of assets | one instance (Runtime Adapter, RO-4) | "exactly one" is within one investigation (n = 1) | support: more operating-unowned cases treated as defects · falsify: a governance rule allowing unowned operation | PROPOSED | KC-005 | Ch. 1 §1.13 (AM-4) refinement | — |
| **KC-007** | **Viewpoints are projections, not bounded contexts** | S0001 L29 (REV 2 weakened); S0006 L88 | architecture | shared ownership (insufficient, by the source's own admission); ARB "siblings" quote (second-hand) | language test **asserted, not shown**; S0001's closing overstates [CONTRA] | support: a shown language comparison · falsify: a viewpoint with its own UL and invariants | UNDER_VALIDATION | KC-005 | (future) | — |
| **KC-008** | **Ownership follows spaces** (platform / EKP / product) | S0001 L31 (REV 2: downgraded to hypothesis) | architecture | n = 1 | second adopter missing | support: a second instance · falsify: ownership crossing spaces | PROPOSED (Hyp) | KC-007 | (future) | — |
| **KC-009** | **Survival ≠ sufficiency** (elements portable, the set fails to bootstrap) | S0002 L52; S0008 L36 | kernel / portability | the MVK experiment, as reported | experiment not read directly | support: the experiment record · falsify: a bootstrap succeeding with the same set | PROPOSED | — | (future) platform chapter | — |
| **KC-010** | **Enforce before ranking; fail closed on missing provenance** | S0003 L28 | retrieval governance | [FETCHED] industry, as reported | vendor sources | support: platform enforcement at the boundary · falsify: — | PROPOSED | KC-002 | Ch. 1 §1.17.5; Ch. 2 §2.8.8 | — |
| **KC-011** | **Knowledge retirement ≠ document retirement** (PM-6, MM-4) | S0004 L36 | lifecycle | retirement never observed in several registers | — | support: a rule for when a **rule** stops being true · falsify: — | UNRESOLVED (open question in the source) | — | Ch. 1 §1.14.1 (claims vs records); review 1 F-17(g) | does state attach to the artifact or the rule? |
| **KC-012** | **R-6: KnowledgeOS creates PKS** is the one unproven edge (n = 0) | S0005 L23, L43; S0007 L37; S0008 L63 | platform claim | absence inside and outside, as reported | — | support: one generated governed PKS · falsify: a generation attempt failing | UNRESOLVED (untested) | — | (future) | — |
| **KC-013** | **Policy administration ≠ decision ≠ enforcement** (PAP/PDP/PEP) | S0005 L19 | governance mechanisms | XACML standard [FETCHED] | the DP-n layer is internal | — | PROPOSED | — | Ch. 2 §2.9; Ch. 1 §1.14.5 | — |
| **KC-014** | **Decision index, never a second rulebook; governance acts excluded by design** | S0006 L20–21, L36 (quoting the Decision Model) | engineering decisions | verbatim quotes | the model is DRAFT; adoption test pending (U-EDV-2) | support: one real cycle of use · falsify: — | PROPOSED | — | Ch. 1 §1.14.2 (mechanism never grants) | — |
| **KC-015** | **Dimensional meta-model with archetype kinds** | S0009 L64–86 | element classification | "five passes" (same programme: repetition) | REV 2 / closing [CONTRA]; KIND vs NATURE open (U-MM-1) | support: MM-1 adoption · falsify: a kind changing with representation | UNDER_VALIDATION | KC-016 | Ch. 1 §1.2 (dimensions) | U-MM-1 |
| **KC-016** | **Knowledge ≠ Artifact** (corpus I-1) | S0008 L24; S0009 L55; S0010 | foundational | FRBR, Polanyi [CANONICAL]; the book's own claim / record split (§1.12) | — | support: consistent use · falsify: — | UNDER_VALIDATION | KC-004 (numbering) | Ch. 1 §1.2, §1.12 | — |
| **KC-017** | **Two knowledge bodies; no layer spans both** (L-A vs governed registers) | S0010 L129 | knowledge layer | file locations, as reported | not re-verified | support: `governed-registers.yaml` outside the graph · falsify: a spanning index | PROPOSED | KC-016 | (future) | — |
| **KC-018** | **The feedback loop is unproven; its blocker is an unfired entry event** | S0010 L47–58, L116–123 | platform premise | 0 traversals, as reported | — | support: one harvest traversal · falsify: the retrospective runs and harvests nothing | UNRESOLVED (untestable now) | — | Ch. 0 premise R | — |
| **KC-019** | **"Five names for one system"** | V3 (CE-01) | naming | — | withdrawn by V3 REV 1 | — | **REJECTED** (by the source; kept for history) | — | Ch. 0 §0.2 | — |
| **KC-020** | **"Who owns the meta-model? nobody"** (U-MM-2) | S0009 L120 | ownership | — | corrected by S0001 L44 (header read) | — | **SUPERSEDED** by the narrowed U-VO-2 | KC-005 | — | owner after D-8 |
| **KC-021** | **"PKS contains Engineering Knowledge"** | proposed chain, S0008 L59 | containment | — | violates the corpus's I-7 (S0008 L64) | — | **REJECTED** (by the source) | KC-004 | — | — |
| **KC-022** | **"Domains own Capabilities"** | proposed meta-edge, S0009 L100 | relations | — | no artifact binds CAP to PD; DP-n anchors | — | **REJECTED** (by the source) | — | — | — |
| **KC-023** | **§1.14.1 current authority of a claim (M2 candidate)** | [BOOK] §1.14.1; [M2] | the authority model | proofs T1–T4, P2a, P4 (internal); 49 tests | review 2 pending; live data insufficient | support: review 2 finds no material defect; the Principal's decisions · falsify: a counterexample to T1–T4 | UNDER_VALIDATION | KC-001, KC-016 | Ch. 1 §1.14.1 | RD 1, 2, 6; C1; C14 |

## Dependency notes (controlled revision)

- **If KC-001 is renamed or narrowed (KC-003 resolution):** KC-023 (§1.14.1 wording) and the glossary rows "authority gate" and "current authority" must be reassessed.
- **If KC-004 is resolved for the platform scheme:** every book "I-n" citation must be checked (Ch. 0 §0.5–0.6; Ch. 1 §1.13.2, §1.16).
- **If KC-016 is rejected:** KC-015, KC-017 and the book's record / act distinction (§1.12) must be reassessed. **High dependency; no counterevidence so far.**
- **The rejected and superseded entries** (KC-019–KC-022) stay in the register for history. None enters the canonical theory.

## Development sequence (candidate only; reserved)

The register is independent of the sequence. The corpus offers three candidate sequences, none adopted here:
- S0004 Stages A–E;
- S0002 Phases A → B.5 → C with the D-queue;
- the three-stream freeze order (S0004 L82–86).

The Principal's illustrative sequence (foundations → concepts → rules → validation → canonical) matches the book's current chapters 0–2 in spirit. **Choosing the authoritative sequence is a Principal decision.**

## Candidate model register (Open Model Discovery, Principal 2026-10-18)

**Rules:**
- **No assumed count.** These are the models **named in S0001–S0010 so far**, plus the book's own. The list grows as the sequence continues.
- **No forced convergence.** Convergence is recorded only where a source **shows** it.
- **Validity is assessed per model and per claim.** A failed concept does not reject its model.

| KM | Candidate model | Where named | What it claims to model | Relation to other models (as stated) | Model status | Material concepts (KC) | Notes |
|---|---|---|---|---|---|---|---|
| KM-01 | **RQ-002 Knowledge Meta-Model**: NATURE × REPRESENTATION × GOVERNANCE-STATUS (× AUTHORITY-SCOPE) | S0009 L40 ("COMPLETE") | dimensions of a knowledge item | S0009 claims convergence with KM-02 … KM-05 | PROPOSED (not read directly) | KC-015 | MM-1 "adopt RQ-002" is pending |
| KM-02 | **Engineering Platform Knowledge Metamodel**: five knowledge classes | S0009 L41; S0001 L18 | knowledge classes | contested structural slot with KM-07 (S0001 §5) | PROPOSED (CANDIDATE, gate OQ-ENG-004) | — | owner per header: Decision Authority (S0001) |
| KM-03 | **L-A Documentation Ontology** (`knowledge-types.yaml` …) | S0008 L32; S0009 L42; S0010 L27 | document types, statuses, authorities, edges | **executable**; S0009 counts it as a converging pass | UNDER_VALIDATION (operating; schema verified to exist [Ind]) | KC-002, KC-004 (platform I-9) | the only model verified to exist and execute |
| KM-04 | **Relationship Ontology** | S0009 L43 | kinds of 13 concepts | — | PROPOSED (not read) | — | — |
| KM-05 | **Engineering Progression Model**: four kinds of change + provenance | S0002 L37; S0009 L44 | how knowledge changes standing | defines "authority = provenance × standing" | PROPOSED (not read) | KC-002, KC-003 | **key to KC-003**; read when the sequence reaches it |
| KM-06 | **Dimensional meta-model with archetype kinds** (S0009's own synthesis) | S0009 L64–86 | element classification | claims to be the convergence of KM-01 … KM-05 | UNDER_VALIDATION; **convergence claimed by repetition within one programme, not by independent passes** [Ded] | KC-015 | REV 2 / closing contradiction |
| KM-07 | **Reference Architecture** | S0001 L18; S0006 L88 | "what the platform IS" | ARB: complementary sibling of KM-08, not a hierarchy (S0006, quoted) | PROPOSED (DRAFT) | — | contested with KM-02 |
| KM-08 | **Engineering Decision Model** | S0006 | the decisions an engineer resolves | sibling of KM-07; anticipates a knowledge-decision catalogue | PROPOSED (DRAFT; adoption test pending) | KC-014 | — |
| KM-09 | **Viewpoint model** (semantic / structural / behavioral / governance? / operational) | S0001; S0006 §8 | architectural viewpoints | presentation over KM-01 … KM-08, routed to D-8 | UNDER_VALIDATION (partially supported, S0006) | KC-007 | fifth viewpoint (governance) open |
| KM-10 | **Space / containment model** (platform / EKP / product; spaces nest) | S0001 L31; S0005 R-9; S0008 L37 | ownership boundaries | the alternative to KM-09 as the context map | PROPOSED (Hyp, n = 1) | KC-008 | — |
| KM-11 | **The book's authority model** (§1.6, §1.13, §1.14.1; candidate M2) | [BOOK]; [M2] | current authority of claims from acts, grants, time | **not equivalent** to KM-05's authority (KC-003) | UNDER_VALIDATION (review 2 pending) | KC-001, KC-023 | — |

**Model-level observations [Ded]:**
1. **Convergence is claimed, not shown.** S0009 calls five passes "independent" and "converging". All are products of one programme, several from the same days, and possibly from the same model family. That is **repetition, not replication** (Ch. 2 §2.1). KM-06's convergence claim stays UNDER_VALIDATION.
2. **Not every model is a model of the same thing.** KM-01, KM-02 and KM-06 classify elements. KM-07 and KM-08 describe the platform and its decisions. KM-09 and KM-10 frame architecture. KM-11 computes authority. **There is no reason to expect them to converge into one theory.** Where they share a term ("authority", "I-9"), the register records divergence (KC-003, KC-004) rather than reconciling it.
3. **Only one model is verified to execute** (KM-03). The others are documents whose existence is reported by these sources but not yet read.

**Note (Principal, 2026-10-18):**
- **3MC** is the Principal's own attempt to derive **three models** (three-model convergence). Those three models are **candidate models** like any other: not assumed valid, and not assumed to converge.
- They are **not yet read**. They will receive KM entries when read.
- The method reference `docs/knowledgeos/theory-extraction` is **authorized to read when needed**; it is not needed now.

## Batch S0011–S0015 additions (2026-10-18)

| KC | Concept | Source | Scope | Support | Counterevidence / limits | Criteria (support · falsify) | Status | Depends on | Theory section | Open |
|---|---|---|---|---|---|---|---|---|---|---|
| **KC-024** | **Falsified ≠ unevidenced** (contradicting evidence vs no evidence) | S0011 L133 | validation method | corpus rule; the Principal's rule (missing evidence ≠ invalidity) | — | consistent application | PROPOSED (methodological) | — | method; Ch. 2 §2.8.8 (kinds of nothing) | — |
| **KC-025** | **Status has a governed state machine; authority does not** | S0012 §2 | the EKP schema | **[Ind]** `statuses.yaml` order/settled ×8; `authorities.yaml` 5 values, none | — | support: no authority transition rules anywhere · falsify: a governed authority transition found | **VALIDATED (as a fact about the schema files, 2026-10-18)**; its interpretation remains PROPOSED | KC-002 | Ch. 1 §1.2, §1.14.1 | — |
| **KC-026** | **Derivation ≠ attestation**: DERIVED → REVIEWED → ATTESTED → RELEASED | S0012 L16–22 | projections and authority | discharge-summary analogy | attestation is ungoverned in the schema (KC-025) | support: an attested projection in practice · falsify: — | PROPOSED | KC-025 | Ch. 1 §1.14.4 (projections never gain authority) | relation to the book's "projection is a sink" |
| **KC-027** | **Provenance is immutable; standing changes only by governance acts** (LG-1 ill-posed) | S0012 L152 | the authority field | coincides with the book's axiom (§1.6) and EP01 provenance ⟂ standing | Engineering Progression Model not read | support: the Progression Model text · falsify: a provenance value changed by an act | UNDER_VALIDATION | KC-002, KC-003 | Ch. 1 §1.2, §1.6 | — |
| **KC-028** | **"Canonical" = adopted / frozen / ruled / enforced by humans, never validated by the programme** | S0013 L20–22 | status semantics | the same rule as the Principal's (validated ≠ authorized) | — | — | PROPOSED (methodological; matches this register's separation of research and governance status) | — | method | — |
| **KC-029** | **The register is not the complete record of authorizations** (unminted commissions) | S0014 C-2; S0015 D-3 | authority records | CAP-001 case, as reported | not verified at source (catalog / README / register) | support: the register lacking an entry for an executed authorization · falsify: a minted ruling found | PROPOSED (an evidence candidate for C1-b's case) | KC-001 | Ch. 1 §1.13.1 (C1) | the DA's IR-1 |
| **KC-030** | **Role separation is session-simulated** (process yes, people unevidenced) | S0014 C-6; S0015 | separation of duties | owner fields | — | support: governance text acknowledging it · falsify: distinct people evidenced | PROPOSED | — | Ch. 1 §1.17.3; **also this research's own reviews** | — |
| **KC-031** | **Knowledge Spaces nest** (a deployment is a nested space) | S0012 G-4 (closed H-3) vs S0009 L25, S0013 H-7 (unevidenced) | containment | one worked example | later documents call it unevidenced, without citing S0012 [CONTRA] | support: a deployment modelled as a space · falsify: a deployment that is not | **UNRESOLVED (contradictory sources)** | KC-008 | (future) | ARB RO-2 |

**Controlled revision: KC-018** *(the original row above is kept unchanged)*

| Field | Before (as recorded at batch S0006–S0010) | After |
|---|---|---|
| Statement | "the feedback loop is unproven; its blocker is an unfired entry event" (S0010) | the back-edge evidence → platform has run **n ≈ 3 informally, ≈ 2 formally**; **PublicDigit → PKS: none**. "0 traversals" as a generalization is **falsified** |
| Status | UNRESOLVED (untestable now) | **NEEDS_REVISION**: the narrow claim (PublicDigit → PKS, n = 0) stands as UNRESOLVED; the general "0 traversals" is SUPERSEDED |
| Reason and evidence | — | S0014 B-1; S0015 D-1 (blind review read the rulings register: R-36, R-41/ES-004.3, R-63) |
| Effect | — | KC-012 (R-6, n = 0) is unaffected: it concerns generation, not the back-edge |
| Note | — | S0010 (08-02) predates the correction (08-02/03). This is a **stale claim, not a contradiction** (CE practice §S5) |

**Evidence added to KC-001 / KC-002 / KC-003:** KC-027 and KC-025 (the corpus decomposes the field; the schema has no authority transitions). **KC-003 (term drift) strengthened; still UNRESOLVED**, pending the Engineering Progression Model.

| KM | Candidate model | Where named | What it models | Model status | Per-claim outcome | Notes |
|---|---|---|---|---|---|---|
| **KM-12** | **Engineering Knowledge Domain Model** (D-1 … D-7) | S0011 (subject: `KnowledgeOS_Engineering_Knowledge_Domain_Model.md`, not yet read) | bounded contexts of engineering knowledge | **NEEDS_REVISION** (the source's own verdict: REVISE, not canonical) | D-1 accept · D-2 revise · D-3 defer (WEAK) · D-4 rejection accepted · D-5 demotion accepted · D-6a defer / D-6b accept · D-7 revise | **a worked case of per-claim assessment**: the model is not rejected wholesale |
| **KM-13** | **Frozen Phase-02 six-context map** (BC-1 … BC-6) | S0013 C-12; S0015 D-2 | strategic bounded contexts | **canonical by the corpus's bucket rule (frozen by humans)**; our research status: PROPOSED (not read) | lags reality (the R-63/R-64 boundary is absent; BC-5 dormant) | never consulted by the KM-12 track (occurrence #11) |
| KM-06 (update) | dimensional meta-model | S0013 K-1, K-2 | — | unchanged (UNDER_VALIDATION); the synthesis keeps it **Candidate** | archetypes graded strong / weaker / weakest | consistent with REV 2, **not** with S0009's closing |

## Batch S0016–S0020 additions and status changes (2026-10-18)

**Status change: KC-003** *(the original row is kept)*.
- Before: UNRESOLVED (hypothesis).
- After: **VALIDATED (research finding about usage, within scope)**: the corpus uses "authority" for two non-equivalent concepts. Evidence: S0018 L72–96 (document field); AMS L49 (permission); the `authorities.yaml` header [Ind].
- **Governance: no decision identified (naming reserved; TODO T1-01).**
- Effect: KC-001 and KC-002 are both retained as distinct concepts; neither is rejected.

| KC / TD | Concept | Source | Status | Depends on | TODO |
|---|---|---|---|---|---|
| **TD-001** | **Authority-Determined Decision Validity** (temporary; Principal-drafted from the M2 explanation, **not corpus-derived**) | Principal 2026-10-18; report `chapter_01/TD-001-RESEARCH-REPORT.md` | **PROPOSED, NEEDS_REVISION.** Agrees with V3 L313–323, AMS L49–51, IA L89–101, AB L100–107, IM L211–219; contradicts none; candidate r1 written (original kept) | KC-001, KC-023 | T1-03 |
| KC-032 | four kinds of change + provenance as a non-progression; "evidence earns, governance grants" | S0018 L57–68, L105 | PROPOSED (repeated in S0012, S0016) | KC-027 | — |
| KC-033 | six-dimension meta-model; INTENT-STATUS rejected as decomposable | S0016 L35–71 | UNDER_VALIDATION (depends on MM-1, the RQ-002 adoption) | KC-015 | T2-04 |
| KC-034 | engineer-decision catalogue vs governance-decision log; FREEZE and RETIRE ungoverned | S0017 L32–60 | PROPOSED | KC-014 | T1-06 |
| KC-035 | three authority systems (policy; method and implementation; domain) | S0019 L98–107 | PROPOSED | KC-001 (sense: permission over a subject) | T1-01 |
| KC-036 | two missions; the adopted one is M-A | S0019 L37–58 (body: "two missions" withdrawn at L23; closing unchanged [CONTRA]) | UNRESOLVED | — | T0-02 |
| KC-037 | three ontology layers; L-B platform ontology absent | S0020 L40–51 | PROPOSED | KC-015 | T0-03 |

| KM | Candidate model | Source | Model status | Notes |
|---|---|---|---|---|
| KM-05 (update) | Engineering Progression Model | **now read: S0018** | UNDER_VALIDATION | supplies KC-027, KC-032 |
| KM-14 | Canonical meta-model (six dimensions) | S0016 | UNDER_VALIDATION | derived from KM-01 (RQ-002) and KM-03 (L-A); **not** independent of them |
| KM-15 | three-layer ontology map (L-A / L-B / L-C) | S0020 | PROPOSED | L-B does not exist |

## Batch S0021–S0025 additions (2026-10-18)

| KC | Concept | Source | Status | Depends on | TODO |
|---|---|---|---|---|---|
| KC-038 | chain MISSION → STRATEGY → PRINCIPLE → DESIGN POLICY → CAPABILITY → KNOWLEDGE → ARTIFACT; DP-n as the capability anchor | S0021 L37–44 | PROPOSED (one table, the Catalog, not yet read) | KC-016 | — |
| KC-039 | invariants vs implementation choices (11 vs 8) | S0021 §4 | UNDER_VALIDATION (cross-product projection, S0022: 10 of 11 survive, conceptually) | KC-004 (platform numbering) | T0-01 |
| KC-040 | **external authority / CONSTRAINS is inexpressible** | S0022 F-3, M-2 | PROPOSED (conceptual evidence in two hypothetical domains) | KC-001 | **T1-15** |
| KC-041 | **all ontology relations are structural; transformational ones are missing** (specializes · constrains · instantiates · attests) | S0022 §6 | PROPOSED | KC-038 | T1-15, T1-16 |
| KC-042 | **genesis gap**: no lawful route to the first decision | S0024 §10; S0025 F-2 | PROPOSED (n = 1 experiment) | — | T1-17 |

| KM | Candidate model | Source | Model status | Per-claim outcome |
|---|---|---|---|---|
| KM-16 | **Relationship Ontology** (13 concepts, kinds, 11 invariants, 8 choices) | S0021; validated by projection in S0022 | **UNDER_VALIDATION** (the source's own status: "Strategic Ontology Candidate") | 8 of 12 concepts invariant · PRINCIPLE and PRODUCT specialize · the Runtime Adapter disappears · I-4 failed then withdrawn · "one PKS per product" fails. **Not rejected wholesale** |
| KM-12 (update) | Engineering Knowledge Domain Model | — | unchanged | — |

## Batch S0026–S0030 additions (2026-10-18)

**KC-018: appended revision note** *(earlier rows kept)*. The back-edge now carries three grades of evidence:
- informal n ≈ 3 (S0014 B-1);
- formal governance n ≈ 2 (S0014);
- **formal, instrumentation level, n = 1** (S0027 OE-KOS-3, 2026-08-04).

The narrow claim (PublicDigit → PKS, n = 0) stays UNRESOLVED. S0028 L182 "EMPTY" is a stale reference.

| KC | Concept | Source | Status | TODO |
|---|---|---|---|---|
| KC-043 | **four partial orders** (authority = who justifies whom · production · realization · containment), not one hierarchy | S0026 L77–88 | PROPOSED | T1-01 (third sense of "authority") |
| KC-044 | **DOMAIN scopes meaning; owns nothing** | S0026 L48–51 | PROPOSED | — |
| KC-045 | **Product Binding**: the product-specific input contract a reusable method requires | S0028 L29; P1 L104 | PROPOSED (n = 1; cognate to Round38C-04 Principle/Form per S0028 L95–101) | — |
| KC-046 | **operational evidence entries separate observation / provenance / claims / interpretation / promotion** | S0027 L111–121 | PROPOSED (methodological; matches this research's labels) | — |
| KC-032 (support) | governance-act vs evidential change | + S0029 L59–62 (in practice) | PROPOSED, now with practice evidence (same programme) | — |

| KM | Candidate model | Source | Model status |
|---|---|---|---|
| KM-17 | four-partial-order ontology | S0026 | PROPOSED (refutes the single-stack hierarchy; extends KM-16) |
| KM-18 | Architecture Baseline (five tiers; P1–P5) | S0028 | PROPOSED (the source: CANDIDATE, n = 1 on every principle) |
