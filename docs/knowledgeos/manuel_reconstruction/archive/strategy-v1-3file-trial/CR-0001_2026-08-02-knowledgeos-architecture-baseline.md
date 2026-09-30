# CR-0001 — Manual Chronological Reconstruction

> **Reconstruction agent:** Claude (primary reconstruction agent)
> **Review status:** ⏳ AWAITING INDEPENDENT REVIEW — this record is not an accepted state.
> **State produced:** **Proposed S1** (§09). Claude does not accept its own state; S1 becomes *Accepted S1* only through external review.
> **Trial scope:** 3-file trial (F0001 → F0002 → F0003). S0 = NONE.
> **Rule applied throughout:** every "Fact" below is a *report of what the source says*. It is not a verification that what the source says is true. Where the source reports counts, statuses or tool outputs (e.g. "1,532 code files", "106 verification reports", a CAP-001 verdict), this reconstruction records **that the source reports them**, not that they hold.

Location convention: `L<n>` = line number in the source file as read with `cat -n` (267 lines total). `§n` = the source's own numbered section heading.

---

## 00 — Source Identity

| Field | Value |
|---|---|
| Reconstruction ID | `CR-0001` |
| Source filename | `2026-08-02-knowledgeos-architecture-baseline.md` |
| Source path | `docs/knowledgeos/2026-08-02-knowledgeos-architecture-baseline.md` (repo-relative) |
| Source type | Markdown document. Self-classified (L5) as *"SYNTHESIS / STRATEGIC REFERENCE MODEL"*; explicitly *not* an implementation plan, folder structure, extraction schedule, new capability/context, governance amendment, ADR update, or commitment to build |
| Source date | **2026-08-02** — from the filename and from the Commission line (L8) and the P1 annotation (L95). No time of day given in the source |
| Other date metadata (not source content) | List file entry: `Aug 5 15:44` (filesystem mtime). Git: single commit `d61bf5e84`, 2026-08-04 08:26:03 +0200, message *"knowledgeos is at strategic discovery end stage"* |
| Chronological position | `F0001` — first entry in `docs/knowledgeos/list_of_files_to_read.log` |
| Previous accepted state | **NONE** |
| Complete file read? | **Yes.** 267 lines / 20,733 bytes, read in a single pass (not sectioned) |
| Size | 267 lines, 20,733 bytes |

### Limitations encountered

1. **Chronological position is weakly determined.** F0001–F0046 all carry the identical list timestamp `Aug 5 15:44`, which appears to be a filesystem mtime, not an authorship date. The list ordering among those 46 entries therefore does not by itself establish that this file was authored first.
2. **This source is not the programme's first artifact.** It explicitly self-describes as a *synthesis* of prior artifacts (L10, L265) and names them: *Architecture Consolidation · Product Boundary Discovery · MVK Bootstrap Validation · Strategic Boundary Consolidation* (L265). It also cites many prior identifiers (ES-001..006, CAP-001, AIP-14, SD-1, Round47-OP, Round38C-04, ARB rulings, etc.). "Previous accepted state = NONE" therefore means *no state has been accepted in this reconstruction*, **not** that nothing preceded the source historically. Those prior artifacts were **not** read (Rule 1).
3. **The file contains an in-file annotation that references a sibling document.** The block at L95–L101 is headed *"ANNOTATED 2026-08-02 — P1 WAS RE-DERIVED, NOT DISCOVERED"* and cites `KnowledgeOS_Engineering_Knowledge_Landscape.md` §0 (L101). That document appears later in the list (F0019). The annotation is part of the source as it exists and is reconstructed as source content, but it indicates the file was **edited after its main body was written** (same stated day). The referenced document was not read.
4. **Many cited identifiers are undefined in this source** (e.g. G-1's meaning as a *prior* gap, EEP, EAD-1, BRM-1, R-37, PMR-9/10, K-07, 38C03-CON-01, OQ-ENG-004, "charter", "DA", "the Addendum"). Their meaning cannot be reconstructed from this file and is not inferred.
5. **Internal identifier collision noted, not resolved:** "G-1" is used at L14 (*"a fourth ungoverned series would worsen G-1"*) and at L208 as a gap in this document's own §7 table (*"L-6 never traversed"*). Whether L14's G-1 refers to the §7 G-1 or to a prior, differently defined G-1 is **ambiguous** in the source (see Q-list).
6. The Commission text *"Stop writing reports. Begin the KnowledgeOS Architecture Baseline"* and the "Senior DDD Architect's Addendum" (L8) are cited but not reproduced; the Addendum's content is known only through what this source reports about it (L16, L49, L171).

---

## 01 — Explicit Source Facts

### Header / metadata block (L1–L16)

**F001:** The document is titled *"KnowledgeOS — Architecture Baseline"*.
Evidence: H1 heading. Location: L1.

**F002:** The document classifies itself as a *synthesis / strategic reference model* and lists seven things it is not (implementation plan, folder structure, extraction schedule, new capability or context, governance amendment, ADR update, commitment to build).
Location: L5 (Kind row); restated L244–L253.

**F003:** Its status is declared *"CANDIDATE — NOT ADOPTED"*, submitted to "the Decision Authority"; it states that a baseline becomes a baseline by an adoption event, not by being written.
Location: L6; restated L253, L267.

**F004:** Its authority is declared *"Generated — never authoritative without human review"*; on conflict, "frozen and governed artifacts win".
Location: L7.

**F005:** The document was commissioned by the instruction *"Stop writing reports. Begin the KnowledgeOS Architecture Baseline"* plus a "Senior DDD Architect's Addendum", dated 2026-08-02.
Location: L8; traceability L265.

**F006:** Placement is declared *"DERIVED → `docs/knowledgeos` (exit 0)"*.
Location: L9.

**F007:** Method is declared as *"SYNTHESIS, not discovery"*: every claim is said to trace to an already produced artifact; nothing new is said to be investigated.
Location: L10.

**F008:** Declaration 1: `P1`–`P5` are *document-local labels*, not minted identifiers. Before writing, "CAP-001" was reportedly run as `php scripts/identifier-check.php KP-1`, reportedly returning `INCONCLUSIVE` with the message *"series 'KP' is not a governed register(ns)"*. A hand check reportedly found no `KP-n` in the corpus, but minting "a fourth ungoverned series would worsen G-1", so nothing was minted. The source states *"The tool's verdict changed the action"* and that this is logged in "CAP-001 §9".
Location: L14.

**F009:** Declaration 2: the Addendum's DDD relationship patterns are recorded as *candidate readings, not assignments*; a "standing ARB ruling" is quoted: *"the strategic relationship is the conclusion, not the starting point."* The document states it assigns no pattern.
Location: L16.

### §1 Product Definition (L20–L32)

**F010:** *KnowledgeOS* is defined as "the reusable engineering platform" that governs how engineering work is planned, approved, executed, verified and evolved; quoted as *"Independent of project, programming language, or execution provider"*; its output is "rules and methods, never product knowledge".
Location: L24, Definition column.

**F011:** KnowledgeOS's status "in canon" is given as *"potential product"* with a gate *"unopened"*; "Canon" is reported to treat the platform as a *Supporting Subdomain of PublicDigit* (AIP-14).
Location: L24, Status column.

**F012:** *PKS — Product Knowledge Space* is defined as one product's knowledge, one per product: concepts, ubiquitous language, bounded contexts, decisions, product bindings; *"Never reusable — an instance"*.
Location: L25.

**F013:** The source states that "generated" PKS is *n=0*: every PKS to date was hand-built.
Location: L25, Status column.

**F014:** *Business Product* is defined as the software that delivers business value (code, tests, deployment) — "PublicDigit today"; status reported as "1,532 code files".
Location: L26.

**F015:** *Running Software* is defined as the deployed system and the evidence it emits; status reported as "106 verification reports".
Location: L27.

**F016:** *AI Runtime* is defined as *"an ADAPTER, not a product"*, replaceable; the source distinguishes *replaceable* from *reusable* as different properties; it quotes `.claude/` as *"never 'the architecture'"*.
Location: L28.

**F017:** *Product Binding* is defined as "the product-specific input contract a reusable method requires"; "SD-1 is the archetype"; the source says this is *"the concept this baseline contributes"*.
Location: L29.

**F018:** The source asserts the generative relation is *`creates`, not `contains`*: `KnowledgeOS → creates → PKS → guides → Product`, illustrated by the analogy "a compiler is not a program; it produces one". KnowledgeOS "does not contain PublicDigit's knowledge — it produces the frame that holds it".
Location: L31–L32 (blockquote).

### §2 Product Architecture (L34–L65)

**F019:** Five tiers are listed: T1 KnowledgeOS (the reusable engineering product) · T2 KnowledgeOS Services (governance, capabilities, validation, runtime integration, operational learning, PKS generation) · T3 Product PKS (one generated PKS per product) · T4 Business Product (PublicDigit · Hospital · ERP …) · T5 Running Software (code, deployments, runtime evidence).
Location: L36–L45 (code block).

**F020:** The source states T2 is "the tier the recent work uncovered", that it is *not* PKS, and that it is "the reusable platform's internal service layer".
Location: L47.

**F021:** The source reports a discrepancy in the Addendum (its diagram shows six subsystems while its discipline table names three) and states it resolves this by marking each subsystem's evidence status rather than dropping any.
Location: L49.

**F022:** Per-subsystem evidence status as stated by the source:
- Governance — evidence: ES-001..006 · EEP · rulings register — *EVIDENCED — operating*
- Capabilities — evidence: CAP-001 realized · CAP-004/006 pre-existing — *EVIDENCED — operating*
- Validation — evidence: ES-003 · 106 verification reports · verdict vocabulary — *EVIDENCED — operating*
- Runtime Integration — evidence: the four-layer model · reserved `registry/` namespace — *EVIDENCED at n=1 runtime; untested against a second*
- Operational Learning — "governed in six places" — *EVIDENCED BUT NON-FUNCTIONING — 0 traversals*
- PKS Generation — evidence: none — *RESPONSIBILITY ONLY — no implementation; "the slot is filled by an architect reading documents"*

Location: L51–L58 (table).

**F023:** "Critical rule": subsystems are *internal* and are *not repository roots*; promoting one to a root would encode an internal design decision as a repository-wide ownership boundary.
Location: L60–L61.

**F024:** The baseline proposes no directory; it cites ES-005.2 as independently forbidding it: *"a directory exists only when its first artifact arrives."*
Location: L63.

**F025:** Three existing roots are named — `docs/knowledgeos` · `docs/publicdigit` · `docs/pks` — said to classify by *product ownership*, called "the stable axis".
Location: L65.

### §3 Extraction Boundary (L67–L87)

**F026:** The source states "the boundary is not a file list. It is a decomposition", describing this as the baseline's *central correction* to §6 of the "Product Boundary Discovery", which "named files and was refuted at n=1".
Location: L69.

**F027:** Three layers are defined with ownership and test:
- METHOD → KnowledgeOS — test: domain-free **and** binding-free **and** evidence-free
- BINDING → the Product PKS — the product-specific input contract the method requires
- EVIDENCE / CASE LAW → the Product PKS — what happened in one project

Location: L71–L75.

**F028:** The decomposition is applied to `Round47-OP`, described as "the artifact that proved the decomposition necessary":
- The nine boundary criteria (semantic ownership · transactional consistency · lifecycle independence · invariants · UL divergence · team autonomy · deployment autonomy · integration characteristics · performance constraints) → METHOD → KnowledgeOS
- The candidate rejection protocol · *"semantic truth does not imply software structure"* → METHOD → KnowledgeOS
- SD-1 — *"consumes only the Certified Domain Knowledge Release v1.0"* → BINDING → the PKS, "replaced by a generic input contract"
- The worked election seams → EVIDENCE → PublicDigit's PKS

Location: L77–L84.

**F029:** The source states "the real extraction work" is decomposing artifacts into method / binding / evidence, not moving files; it reframes the question from "can Round47-OP be extracted?" to "which parts are engineering law, and which are PublicDigit bindings?" and calls this "a knowledge-engineering question, not a DDD one".
Location: L86–L87.

### §4 The Five Principles (L89–L171)

**F030:** All five principles are declared *candidates*, because "per ES-006.1, n=1 admits a CANDIDATE, never a rule"; each is said to carry its evidence bound.
Location: L91.

**F031 (P1, main statement):** *Method vs Binding*: every reusable artifact must explicitly separate (1) the METHOD — domain-free, reusable · (2) the BINDING — product-specific input contract · (3) the EVIDENCE — product-specific case law. Derived from *"Strategic DDD = Engineering Method + Product Binding — the SD-1 diagnosis"*. Evidence: n=1 (Round47-OP), "corroborated independently by the methodology corpus, where the missing separation appears as *case-law dilution*". Candidate pattern reading: Shared Kernel + Published Language — *not assigned*.
Location: L93, L104, L106–L110.

**F032 (P1, annotation):** An annotation dated 2026-08-02 states *"P1 WAS RE-DERIVED, NOT DISCOVERED"*: `Round38C-04_Principle_Form_Classification_Framework` (34.5 KB, `docs/architecture/design/`) already defines a framework classifying ADR elements as *Principle, Form, or Ambiguous*, ARB-authorized under 38C03-CON-01, governing "Option C — Hybrid Principle/Form Split". The annotation says they are *not identical*: Principle/Form classifies by *constitutional entrenchment*, P1 by *portability* — "Cognate, not duplicate". It states P1 lacks an explicit `Ambiguous` class, and that "the Capability Model had to invent 'UNCLASSIFIED' ad hoc for `check_roles.php`". It states *"P1 should be derived from K-07, not proposed beside it"*, with record in `KnowledgeOS_Engineering_Knowledge_Landscape.md` §0.
Location: L95–L101.

**F033 (P2):** *The Portability Ladder — four tiers, not three*: "Extraction readiness = f(domain-free, binding-free, evidence-free)". Tier 1: all three → READY. Tier 2: domain-free but binding-coupled → BLOCKED (remove the binding). Tier 3: domain-free but case-law-diluted → BLOCKED (separate the evidence). Tier 4: not domain-free → PRODUCT-SPECIFIC, stays.
Location: L112–L121.

**F034 (P2 applied):** The source classifies named artifacts:
- Tier 1 READY: `DDD_Tactical_Governance_Principles`; `Engineering_Execution_Protocol`; PMR-9 · PMR-10; CAP-001 Domain/Application/Shared; ES-005.2 · ES-005.3 · ES-004.2/.3 · ES-003.3
- Tier 2: Round47-OP nine criteria (binding: SD-1)
- Tier 2/3: `Round47-00` SD-2..SD-7 (binding ⚠️; "SD-4 cites election concepts")
- Tier 3: `PKS_Knowledge_Integrity_Model` ("~90% case law"); `PKS_Phase_II_Methodology_Baseline_v1_2` ("~95% case law")
- Tier 4 "as written": ES-005.1 ("names PublicDigit")
- Tier 4: Registers · 106 reports · `app/`

Location: L123–L135.

**F035 (P2, instrument finding):** The source states that neither Tier-2 nor Tier-3 blockage is detectable by searching for domain vocabulary; "EAD-1's zero-election-terms test found neither"; "a three-question test is required, not a grep". It says this answers OQ-S5.
Location: L137–L138.

**F036 (P3):** *Responsibility vs Component — the genesis gap*: a responsibility becomes a component only when (1) exercised at least once, (2) exercised by someone other than the originator, (3) a repeatable pattern is extracted from ≥ 2 instances.
Location: L140–L142.

**F037 (P3, refinement):** An "accepted refinement": the statement *"PKS Generator doesn't exist"* was "imprecise". The responsibility EXISTS (an architect reads KnowledgeOS and creates a PKS); the component DOES NOT EXIST (no automation, no repeatable pattern, n=0); "today's implementation" is *a Human-in-the-Loop Adapter* — "the port exists; the adapter is a person".
Location: L144–L150.

**F038 (P3, bootstrapping):** "Bootstrapping is three responsibilities": bootstrap PKS (n=0) · bootstrap engineering (n=1) · bootstrap software (n=0).
Location: L152.

**F039 (P4):** *The Evidence Harvest — the loop-closing pattern*: NOT `Evidence → Decision → Change`; IS `Evidence → HARVEST → Candidate → Promotion → Change`.
Location: L154–L157.

**F040 (P4 mechanisms):** ES-006.4 harvest question · ES-006.1 ladder · Observation Protocol → *READY — use them*. Pattern Cards + Evidence Register as its own artifact → *BLOCKED*, trigger: PB-004 retrospective. CAP-001 evidence record → *1 row as of today*. The source states: "The process exists. The instances were missing — and one now exists."
Location: L159–L165.

**F041 (P5):** *The Product Architecture Reference — synthesis*: KnowledgeOS is an engineering platform with internal subsystems; "the extraction boundary runs between the platform and the Product PKS — not between folders."
Location: L167–L169.

**F042 (P5, precision note):** The Addendum's reading *"Separate Ways with a Shared Kernel"* is called "internally tense in Evans' taxonomy" because Separate Ways means no integration, which a Shared Kernel contradicts; it is "recorded as needing ARB resolution; not asserted".
Location: L171.

### §5 The Lifecycle (L173–L184)

**F043:** Six lifecycle transitions with status and bound:
- L-1 KnowledgeOS → discover a product — HYPOTHESIS — "charter Stage 1 unapproved"
- L-2 discovery → generate a PKS — HYPOTHESIS — n=0, "the adapter is human"
- L-3 PKS → guide engineering — PARTIAL — "protocol used; effect unmeasured"
- L-4 engineering → produce software — EVIDENCED — "1,532 files · CAP-001"
- L-5 software → collect evidence — PARTIAL — "106 reports"
- L-6 evidence → improve KnowledgeOS — EMPTY — quoted *"ZERO-INDEPENDENT… never traversed"*

Location: L175–L182.

**F044:** "Genesis correction": "the platform governs change; it does not govern genesis"; `Idea → GENESIS → KnowledgeOS → Engineering → Software` — "the second box has no governing rule". The source attributes this correction to "P3/§0".
Location: L184.

### §6 The Open Questions (L186–L202)

**F045:** Thirteen open questions are listed with authority and status:
- OQ-S1 Is SD-1 a PRODUCT BINDING or a PLATFORM RULE? — ARB — "HIGHEST LEVERAGE — P1 answers it conceptually; only the ARB can rule"
- OQ-S2 Should the strategic/tactical method pair sit on the same side of the platform line? — ARB — open; "tactical is platform-side and ADOPTED; strategic is product-side"
- OQ-S3 Is Genesis an EEP gap or a separate lifecycle? — ARB — open
- OQ-S4 Is "PKS Generator" a component or a role? — ARB — "answered in part by P3: a responsibility with a human adapter"; component status "awaits n≥2"
- OQ-S5 Do the blocker classes need a detection instrument? — ARB — "answered in part by P2: yes — a three-question test, because grep found neither"
- OQ-K1 Does ES-005.3's research clause still hold? — ARB — open; "blocks M-5/M-6"
- OQ-K2 Where does cross-product research live? (`PENDING`) — ARB — open
- OQ-K3 Has the KnowledgeOS gate opened? — DA — open; trigger: second adopting product
- OQ-K4 Is the Product Discovery Charter approved? — ARB — open; PROPOSED
- OQ-K5 AIP-14 over-evolution exposure? — ARB — "open and accruing"
- OQ-K6 Does "KnowledgeOS" remain the name? (a placeholder) — ARB — open
- OQ-C1 Does BRM-1 permit reference-based extraction? — ARB — open; "gates all of Stage 3"
- OQ-C2 Does the Phase II freeze independently bar M-6? — ARB — open

Location: L188–L202.

### §7 The Evidence Gaps (L204–L227)

**F046:** Ten evidence gaps with closing triggers:
- G-1 L-6 never traversed — one harvest that changes a platform rule
- G-2 No PKS generated by a mechanism (n=0) — one PKS produced without a human adapter
- G-3 No second adopting product — "the DA's recorded trigger"
- G-4 No second runtime adapter — "the reserved `registry/` trigger"
- G-5 Zero market data points — charter Stage 2
- G-6 CAP-001 evidence: 1 row, 1 decision changed (was 0/0) — "partially closed today"
- G-7 6 ES standards PROPOSED · kernel DRAFT · metamodel CANDIDATE — ratification · OQ-ENG-004
- G-8 No detection instrument for Tier-2/Tier-3 blockage — P2's three-question test, applied twice
- G-9 Genesis unruled — OQ-S3
- G-10 n=1 on every principle here — a second independent occurrence

Location: L206–L217.

**F047:** The G-6 event record: the event was that writing this baseline required labels for five principles; verdict `INCONCLUSIVE` (*"series 'KP' is not a governed register(ns); absence of evidence is not PASS"*); decision changed: YES — the intent was to mint `KP-1..KP-5`; the verdict sent the check to a hand review, which found no collision but established that a fourth ungoverned series would worsen G-1; nothing minted. The source calls this "the first row in which the capability changed an engineering decision — the bar set in CAP-001 README §6, unmet since the capability shipped".
Location: L219–L227.

### §8 The Roadmap (L229–L240)

**F048:** Six roadmap stages:
- 0 Current — HERE — "one product · CAP-001 realized · boundary refuted · principles synthesized"
- 1 Consolidation — entry condition: nothing (moves no file) — OPEN; "This document is Stage 1's substance"; remaining: OQ-S1 · OQ-K1 · OQ-K2
- 2 Validation — entry condition: nothing (one session per run) — OPEN, "the cheapest stage"; "Re-run the bootstrap instrument with Round47-OP added and SD-1 suspended, on a different tiny product"
- 3 Extraction — entry: R-37 lifted (C3 + PB-004 + retrospective) ∧ OQ-K1 ∧ OQ-C1 — BLOCKED
- 4 Reusable KnowledgeOS — entry: *"a second real adopting product"* ("pre-positioned, not executed") — BLOCKED
- 5 `knowledgeos init` · multiple products — entry: charter gate 4 · market evidence — BLOCKED

The column header states entry conditions are "quoted from canon".
Location: L231–L238.

**F049:** "Stages 1 and 2 are both open and both cost nothing structural. Stage 3 onward is gated by decisions no document can make."
Location: L240.

### Closing (L244–L267)

**F050:** The IS / IS NOT table: IS — synthesis of accumulated evidence; stable strategic reference model; guide to what to extract and what to leave; *falsifiable — testable by the next bootstrap run*; the answer to "what are we actually building?"; CANDIDATE pending adoption. IS NOT — the seven exclusions of F002.
Location: L246–L253.

**F051:** Closing synthesis: KnowledgeOS is "a reusable engineering platform whose method has been demonstrated portable and whose *boundary* has not"; extraction = decomposing each artifact into method, binding and evidence, which "no vocabulary search can detect"; six subsystems — three operating, one untested at n=1, one governed but never run, one a responsibility with a human adapter; six lifecycle transitions — one evidenced, one empty, "and the empty one closes the loop"; all five principles rest on n=1 and are candidates.
Location: L257.

**F052:** Closing claim: "The programme does not need more evidence to define its architecture. It needed synthesis — and the synthesis says: one ARB answer (OQ-S1), one repeatable one-session experiment, and nothing extracted until both have spoken." Plus: "today, for the first time, the platform changed an engineering decision rather than merely describing one."
Location: L259–L261.

**F053:** Traceability line: names the synthesized sources (Architecture Consolidation · Product Boundary Discovery · MVK Bootstrap Validation · Strategic Boundary Consolidation) and restates: no pattern assigned · no folder proposed · no file moved · no context created · no capability built · no governance amended · no code.
Location: L265.

**F054:** Final line: "Submitted to the Decision Authority. Nothing in this document executes."
Location: L267.

---

## 02 — Initial Epistemic State (based ONLY on this file)

> Categories are applied to **the source's own claims**. "Established" means *the source itself asserts this with an evidence status it treats as settled* — not that this reconstruction has verified it.

### Established (by the source's own account)

- The document's own status: CANDIDATE, not adopted; generated; non-authoritative without human review; non-executing (F003, F004, F054). *This is established about the document, within the document.*
- That the document assigns no DDD pattern, proposes no directory, mints no identifier, moves no file, creates no context, builds no capability, amends no governance (F008, F009, F024, F053).
- That `P1`–`P5` are document-local labels (F008).
- Subsystems reported as EVIDENCED — operating: Governance, Capabilities, Validation (F022). *Source-reported; supporting evidence named but not shown.*
- Lifecycle transition L-4 reported as EVIDENCED (F043).
- The source reports that a prior file-list boundary (Product Boundary Discovery §6) was *"refuted at n=1"* (F026). *Reported outcome of prior work; not demonstrated in this file.*
- The source reports that EAD-1's zero-election-terms test did not detect Tier-2 or Tier-3 blockage (F035). *Reported outcome of prior work.*

### Proposed

- Five principles P1–P5, all explicitly CANDIDATES at n=1 (F030–F041).
- The definitions of KnowledgeOS, PKS, Business Product, Running Software, AI Runtime, Product Binding (F010–F017) — offered as the baseline's product definition; the document as a whole is a candidate awaiting adoption.
- The `creates, not contains` generative relation (F018).
- The five-tier architecture (F019) and the six-subsystem internal service layer (F020, F022).
- The three-layer METHOD / BINDING / EVIDENCE decomposition as the extraction boundary (F026–F029).
- The four-tier Portability Ladder and the tier assignment of named artifacts (F033, F034).
- The component-promotion rule (P3; F036).
- The harvest pattern `Evidence → HARVEST → Candidate → Promotion → Change` (F039).
- A "three-question test" as the detection instrument for Tier-2/3 blockage (F035) — named as required; not specified beyond the three properties; not yet applied ("applied twice" is the G-8 closing trigger, F046).
- The six-stage roadmap and the Stage-2 experiment (F048).
- That P1 "should be derived from K-07, not proposed beside it" (F032) — a recommendation in the annotation.

### Observed (reported as observation in the source)

- The CAP-001 identifier-check run returning `INCONCLUSIVE` for `KP-1`, followed by a hand check finding no `KP-n` (F008, F047).
- Every PKS to date was hand-built; generated PKS n=0 (F013).
- Counts: 1,532 code files; 106 verification reports (F014, F015, F043).
- Operational Learning: governed in six places, 0 traversals; L-6 "never traversed" (F022, F043).
- The Addendum's six-vs-three subsystem discrepancy (F021).
- The existence of `Round38C-04_Principle_Form_Classification_Framework` with Principle/Form/Ambiguous classes (F032).
- The Capability Model's ad hoc "UNCLASSIFIED" class for `check_roles.php` (F032).
- Case-law proportions (~90%, ~95%) in two PKS artifacts (F034) — *reported estimates; method of estimation not given*.

### Implemented (what the file itself demonstrates or reports as implemented)

- **Demonstrated within the file:** nothing executable. The file is a document; its own non-execution is declared (F054).
- **Reported as implemented / existing elsewhere (not demonstrated here):** CAP-001 "realized", with an identifier-check script (`php scripts/identifier-check.php`) that was run (F008, F022, F048); CAP-004/006 "pre-existing" (F022); ES-001..006, EEP, rulings register (F022); `registry/` namespace reserved (F022); the Business Product (PublicDigit) code (F014).
- **Explicitly not implemented:** PKS Generation as a component (F022, F037); Pattern Cards + Evidence Register as its own artifact (F040, BLOCKED).

⚠️ *IMPLEMENTED ≠ VALIDATED:* the source reports CAP-001 changed one decision (1 row); it does not claim CAP-001 is validated.

### Hypothesized

- Lifecycle L-1 (KnowledgeOS → discover a product) and L-2 (discovery → generate a PKS) — explicitly HYPOTHESIS (F043).
- *Note:* The source also describes P1–P5 as "candidates" and the document as "falsifiable — testable by the next bootstrap run" (F050). "Candidate" is the source's term; the source does not call the principles "hypotheses". This reconstruction keeps them under **Proposed** and does not relabel them.

### Unknown (within this source)

- Whether the document was ever adopted (it states it is submitted, not adopted).
- Answers to all 13 open questions, including the fully open ones (OQ-S1 is answered only "conceptually" by P1 per the source, and explicitly left to the ARB).
- The content of the three-question test beyond the three properties.
- The effect of L-3 ("effect unmeasured").
- The meaning of many cited identifiers (see Limitation 4).
- Whether any Tier-1 "READY" artifact is actually portable to a second product (the source reports the *method* "demonstrated portable" but gives no second-product evidence in this file; G-3 says there is no second adopting product).
- What "Hospital · ERP …" in T4 refer to (examples or real products) — not stated.

### Rejected (explicitly ruled out by the source)

- The file-list form of the extraction boundary (Product Boundary Discovery §6) — "refuted at n=1" and corrected (F026).
- `Evidence → Decision → Change` as the loop-closing pattern (F039).
- `contains` as the KnowledgeOS→PKS relation, in favour of `creates` (F018).
- Promoting subsystems to repository roots (F023).
- Proposing any directory in this baseline (F024).
- Minting `KP-1..KP-5` (F008, F047).
- Asserting "Separate Ways with a Shared Kernel" — not rejected as false, but explicitly **not asserted** and referred to the ARB (F042). *Recorded here as "declined to assert", not as "rejected".*
- The unqualified statement "PKS Generator doesn't exist" — called imprecise and refined (F037).
- A grep / domain-vocabulary search as sufficient detection for Tier-2/3 blockage (F035).
- (Annotation) P1 as a *discovery* — the annotation says it was re-derived (F032).

---

## 03 — Concepts

| Concept ID | Source term | Source meaning | Status | Evidence |
|---|---|---|---|---|
| C001 | **KnowledgeOS** | The reusable engineering platform governing how engineering work is planned, approved, executed, verified and evolved; independent of project, language, execution provider; outputs rules and methods, never product knowledge. Status "potential product", gate unopened; name is "a placeholder" | explicit | L24; L200 (OQ-K6) |
| C002 | **PKS — Product Knowledge Space** | One product's knowledge (concepts, UL, bounded contexts, decisions, product bindings); one per product; never reusable — an instance | explicit | L25 |
| C003 | **Business Product** | Software delivering business value — code, tests, deployment; PublicDigit today | explicit | L26 |
| C004 | **Running Software** | Deployed system and the evidence it emits | explicit | L27 |
| C005 | **AI Runtime** | An adapter, not a product; replaceable | explicit | L28 |
| C006 | **replaceable vs reusable** | Stated as different properties | explicit (distinction), meaning of each only implicit | L28 |
| C007 | **Product Binding** | The product-specific input contract a reusable method requires; SD-1 is the archetype; "the concept this baseline contributes" | explicit | L29 |
| C008 | **`creates` (vs `contains`)** | The generative relation KnowledgeOS→PKS; KnowledgeOS produces the frame that holds product knowledge | explicit | L31–L32 |
| C009 | **`guides`** | Relation PKS→Product | explicit (named), meaning not elaborated | L32 |
| C010 | **Tiers T1–T5** | Five-tier product architecture | explicit | L36–L45 |
| C011 | **KnowledgeOS Services (T2)** | The reusable platform's internal service layer; not PKS; "uncovered" by recent work | explicit | L40–L47 |
| C012 | **Subsystem** (Governance, Capabilities, Validation, Runtime Integration, Operational Learning, PKS Generation) | Internal parts of T2; not repository roots | explicit | L51–L61 |
| C013 | **Repository root / product-ownership axis** | Existing roots classify by product ownership, "the stable axis" | explicit | L65 |
| C014 | **Extraction Boundary** | A decomposition, not a file list; runs between platform and Product PKS, not between folders | explicit | L69; L169 |
| C015 | **METHOD (layer)** | Domain-free, binding-free, evidence-free; belongs to KnowledgeOS; "engineering law" | explicit | L73; L87; L104 |
| C016 | **BINDING (layer)** | Product-specific input contract; belongs to the Product PKS | explicit | L74; L104 |
| C017 | **EVIDENCE / CASE LAW (layer)** | What happened in one project; belongs to the Product PKS | explicit | L75; L104 |
| C018 | **case-law dilution** | The form in which missing method/evidence separation appears in the methodology corpus; Tier 3 = "case-law-diluted" | explicit (term); definition implicit | L109; L120 |
| C019 | **domain-free / binding-free / evidence-free** | The three properties determining extraction readiness | explicit | L73; L114 |
| C020 | **Portability Ladder** | Four-tier readiness classification (READY / BLOCKED-binding / BLOCKED-evidence / PRODUCT-SPECIFIC) | explicit | L112–L121 |
| C021 | **Extraction readiness** | f(domain-free, binding-free, evidence-free) | explicit | L114 |
| C022 | **three-question test** | Detection instrument required for Tier-2/3 blockage, replacing vocabulary search | explicit (named); content only implicit via C019 | L138; L194; L215 |
| C023 | **Responsibility vs Component** | A responsibility becomes a component only on three conditions | explicit | L140–L142 |
| C024 | **genesis gap / Genesis** | The platform governs change, not genesis; the step from Idea to KnowledgeOS has no governing rule | explicit | L140; L184; L192; L216 |
| C025 | **Human-in-the-Loop Adapter** | Today's implementation of PKS generation: "the port exists; the adapter is a person" | explicit | L150 |
| C026 | **port / adapter** (as applied to PKS generation) | Used for PKS generation (port exists, adapter human) and AI Runtime (adapter) | explicit usage; relation between the two usages unclear | L28; L150 |
| C027 | **Bootstrapping (three responsibilities)** | bootstrap PKS (n=0) · bootstrap engineering (n=1) · bootstrap software (n=0) | explicit | L152 |
| C028 | **Evidence Harvest** | Loop-closing pattern `Evidence → HARVEST → Candidate → Promotion → Change` | explicit | L154–L157 |
| C029 | **Candidate** (as epistemic status) | n=1 admits a candidate, never a rule (per ES-006.1); used for P1–P5 and for the document itself | explicit | L6; L91 |
| C030 | **n=0 / n=1 / n≥2** | Evidence counts bounding claims | explicit (usage) | throughout, e.g. L25, L91, L193 |
| C031 | **Product Architecture Reference** | P5: platform with internal subsystems; boundary between platform and Product PKS | explicit | L167–L169 |
| C032 | **Lifecycle L-1..L-6** | Six transitions KnowledgeOS → product → PKS → engineering → software → evidence → KnowledgeOS | explicit | L175–L182 |
| C033 | **Candidate reading (of a DDD pattern)** | A recorded, unassigned reading; "relationship is the conclusion, not the starting point" | explicit | L16; L110; L171 |
| C034 | **document-local label** | Label not minted as a governed identifier | explicit | L14 |
| C035 | **ungoverned series** | An identifier series not in a governed register; minting one "would worsen G-1" | explicit (term); "fourth" implies three existing — unnamed | L14; L225 |
| C036 | **Decision Authority (DA)** | Body to which the baseline is submitted; authority for OQ-K3 | explicit (named), not defined | L6; L197; L267 |
| C037 | **ARB** | Authority for most OQs; source of "standing ruling" | explicit (named), not defined | L16; L190–L202 |
| C038 | **adoption event** | What makes a baseline a baseline | explicit | L6 |
| C039 | **Principle / Form / Ambiguous** (from Round38C-04) | Existing classification by constitutional entrenchment; cognate to P1 | explicit (reported, in annotation) | L97–L101 |
| C040 | **portability** (as classification axis) | P1's axis, contrasted with constitutional entrenchment | explicit | L99 |
| C041 | **"engineering law"** | Contrasted with PublicDigit bindings | explicit (term), meaning ≈ METHOD — **implicit** equivalence | L87 |
| C042 | **gate (KnowledgeOS gate)** | "unopened"; opening trigger: second adopting product | explicit usage | L24; L197 |
| C043 | **Roadmap Stages 0–5** | Current · Consolidation · Validation · Extraction · Reusable KnowledgeOS · `knowledgeos init` | explicit | L231–L238 |
| C044 | **bootstrap instrument** | Re-runnable instrument for Stage 2 validation | explicit (named), not described | L235; L251 |

---

## 04 — Decisions and Changes

### New in this source (the source itself claims to introduce)

- **Product Binding** as a concept — "the concept this baseline contributes" (F017). *Note:* the P1 annotation (F032) indicates the broader P1 decomposition had a cognate predecessor; it does not say the *term* "Product Binding" pre-existed.
- The **METHOD / BINDING / EVIDENCE** decomposition as the extraction boundary, described as a "central correction" (F026–F029). *Source also says P1 is "derived from" the SD-1 diagnosis (F031), so "new" here means "first synthesized as a principle in this source", per the source.*
- The **Portability Ladder** (four tiers) (F033).
- The **P3 component-promotion rule** and the refinement of "PKS Generator doesn't exist" (F036, F037).
- The **P4 harvest pattern** statement (F039).
- The **five-tier architecture** and **six-subsystem** evidence marking (F019, F022).
- The **`creates, not contains`** relation (F018).
- The **L-1..L-6** lifecycle table, **OQ list**, **G-1..G-10** gap list, and **Stages 0–5** roadmap as assembled here. *Whether these items were first formulated here or collected from prior artifacts cannot be determined from this file* (the method is declared "synthesis"; F007).

### Existing but undocumented (source explicitly indicates prior existence)

- Prior artifacts synthesized: Architecture Consolidation · Product Boundary Discovery · MVK Bootstrap Validation · Strategic Boundary Consolidation (F053).
- CAP-001 (realized; with README §6 bar and §9 log), CAP-004/006 (pre-existing) (F008, F022, F047).
- ES-001..006 standards (reported PROPOSED in G-7), ES-006.1 ladder, ES-006.4 harvest question, Observation Protocol, EEP, rulings register (F022, F040, F046).
- A standing ARB ruling on relationships-as-conclusions (F009).
- AIP-14 treating the platform as a Supporting Subdomain of PublicDigit (F011).
- SD-1 and the "SD-1 diagnosis"; Round47-OP; Round47-00 SD-2..SD-7 (F028, F031, F034).
- `Round38C-04_Principle_Form_Classification_Framework`, ARB-authorized under 38C03-CON-01 (F032).
- The "Capability Model" with an ad hoc "UNCLASSIFIED" class (F032).
- EAD-1's zero-election-terms test (F035).
- Existing roots `docs/knowledgeos`, `docs/publicdigit`, `docs/pks` (F025).
- The reserved `registry/` namespace; "the four-layer model" (F022).
- A Product Discovery Charter (PROPOSED) with stages/gates (F045, F048).
- The statement "PKS Generator doesn't exist", now refined (F037).
- A "kernel" (DRAFT) and "metamodel" (CANDIDATE) (F046).

### Proposed future change

- ARB to rule on OQ-S1 (SD-1 binding vs platform rule) and the other OQs (F045).
- Stage 2: re-run the bootstrap instrument with Round47-OP added and SD-1 suspended, on a different tiny product (F048).
- SD-1 to be "replaced by a generic input contract" (F028).
- Apply the three-question test twice (G-8 trigger) (F046).
- Extraction (Stage 3+) only after R-37 lifted ∧ OQ-K1 ∧ OQ-C1 (F048).
- "Nothing extracted until both [OQ-S1 answer and the one-session experiment] have spoken" (F052).
- (Annotation) derive P1 from K-07 rather than propose it beside it (F032).
- Pattern Cards + Evidence Register, on trigger PB-004 retrospective (F040).

### Decision (what the source explicitly decides)

- **Not to mint** `KP-1..KP-5`; use document-local `P1`–`P5` labels (F008, F047). *This is the only operational decision the source reports having taken and acted on.*
- **Not to assign** any DDD relationship pattern (F009, F042).
- **Not to propose** any directory (F024).
- To **mark** all six subsystems with evidence status rather than drop any (F021).
- To classify all five principles as **candidates** (F030).
- ⚠️ The document as a whole is **not** a decision: it is submitted, not adopted (F003, F054).

### Implementation

- None within the document. The source reports one executed action — running `php scripts/identifier-check.php KP-1` (F008) — and one resulting non-action (nothing minted). It explicitly states no code, no file moved, no capability built (F053).

---

## 05 — Evidence and Provenance

| ID | Reconstruction claim | Supported by | Location |
|---|---|---|---|
| R001 | The document is a candidate synthesis, not adopted, not authoritative, non-executing | F002, F003, F004, F054 | L5–L7, L267 |
| R002 | The document declares itself synthesis of four named prior artifacts, not discovery | F007, F053 | L10, L265 |
| R003 | KnowledgeOS is defined as a reusable engineering platform outputting rules/methods, never product knowledge | F010 | L24 |
| R004 | Canon (per source) holds the platform as a Supporting Subdomain of PublicDigit; KnowledgeOS as product is only "potential", gate unopened | F011 | L24 |
| R005 | PKS is one-per-product, never reusable; all PKS to date hand-built | F012, F013 | L25 |
| R006 | Product Binding is explicitly introduced by this baseline; SD-1 is its archetype | F017 | L29 |
| R007 | KnowledgeOS→PKS relation is `creates`, explicitly not `contains` | F018 | L31–L32 |
| R008 | Five tiers are proposed; T2 is an internal service layer, not PKS | F019, F020 | L36–L47 |
| R009 | Of six subsystems, three are reported operating, one n=1, one non-functioning (0 traversals), one responsibility-only | F022, F051 | L51–L58, L257 |
| R010 | Subsystems are explicitly not repository roots; no directory proposed | F023, F024 | L60–L63 |
| R011 | The extraction boundary is re-conceived as a three-layer decomposition; the prior file-list boundary is reported refuted at n=1 | F026, F027 | L69–L75 |
| R012 | Round47-OP is decomposed: nine criteria + rejection protocol → METHOD; SD-1 → BINDING; election seams → EVIDENCE | F028 | L77–L84 |
| R013 | All five principles are explicitly candidates at n=1 | F030, F046 (G-10) | L91, L217 |
| R014 | P1 is annotated (same day) as re-derived; a cognate Principle/Form/Ambiguous framework pre-exists; P1 lacks an Ambiguous class | F032 | L95–L101 |
| R015 | P2 proposes a four-tier Portability Ladder and assigns named artifacts to tiers | F033, F034 | L112–L135 |
| R016 | The source reports vocabulary search (EAD-1) failed to detect Tier-2/3 blockage and states a three-question test is required | F035 | L137–L138 |
| R017 | P3 proposes a three-condition rule for responsibility→component; PKS generation is a responsibility with a human adapter | F036, F037 | L142–L150 |
| R018 | P4 rejects `Evidence→Decision→Change` for `Evidence→HARVEST→Candidate→Promotion→Change` | F039 | L156–L157 |
| R019 | "Separate Ways with a Shared Kernel" is flagged internally tense and referred to the ARB, not asserted | F042 | L171 |
| R020 | L-1, L-2 are HYPOTHESIS; L-4 EVIDENCED; L-3, L-5 PARTIAL; L-6 EMPTY | F043 | L175–L182 |
| R021 | The source states the platform governs change, not genesis | F044 | L184 |
| R022 | 13 open questions are recorded, all under ARB or DA authority; OQ-S4 and OQ-S5 "answered in part" | F045 | L188–L202 |
| R023 | 10 evidence gaps are recorded; G-6 is reported partially closed "today" | F046, F047 | L206–L227 |
| R024 | The CAP-001 check returned INCONCLUSIVE and changed the decision (no mint); source calls this the first time the capability changed an engineering decision | F008, F047, F052 | L14, L219–L227, L261 |
| R025 | Roadmap: Stages 1–2 open with no structural entry condition; 3–5 blocked on named conditions | F048, F049 | L231–L240 |
| R026 | The source's own verdict: method "demonstrated portable", boundary not | F051 | L257 |
| R027 | The source concludes: one ARB answer (OQ-S1) + one repeatable one-session experiment; nothing extracted until both | F052 | L259 |
| R028 | The only action reported as executed is the identifier-check run; no code/files/directories/contexts/capabilities/governance changed | F008, F053 | L14, L265 |

---

## 06 — Relationships

Only relationships the source itself states are recorded. Classification labels are used only where the source's wording supports them.

| # | Between | Relationship (source wording) | Classification | Evidence |
|---|---|---|---|---|
| REL-01 | KnowledgeOS → PKS | "creates" (explicitly not "contains") | DERIVED-FROM (PKS derived from / produced by KnowledgeOS) — **per source wording "creates"** | L31–L32 |
| REL-02 | PKS → Product | "guides" | No catalogue label fits; recorded as the source's own term **"guides"** | L32 |
| REL-03 | P1 ↔ Round38C-04 Principle/Form framework | "Cognate, not duplicate"; "not identical"; P1 "re-derived"; "should be derived from K-07" | **UNRESOLVED.** Source rules out SAME ("not identical") and says "cognate". It recommends P1 *become* DERIVED-FROM K-07 but does not state that it currently is. Relation between "K-07" and "Round38C-04" is not stated in the source (see Q) | L95–L101 |
| REL-04 | P1 ↔ SD-1 diagnosis (`Strategic DDD = Engineering Method + Product Binding`) | "Derived from" | DERIVED-FROM (source's own term) | L108 |
| REL-05 | Product Binding ↔ SD-1 | "SD-1 is the archetype" | SPECIALIZATION candidate (SD-1 as an instance of Product Binding). **Source says "archetype", not "instance"; classification held as tentative** | L29 |
| REL-06 | P2 ↔ P1 | Not stated explicitly. P2 uses the same three properties as P1's layers | **UNRESOLVED** — no explicit link stated in source | L104, L114 |
| REL-07 | P2 ↔ OQ-S5 / P3 ↔ OQ-S4 | "answers in part" | Source-stated partial answer; no catalogue label applied | L193–L194 |
| REL-08 | P3/§0 → Genesis correction to lifecycle | "the genesis correction P3/§0 forces into the lifecycle" | Source-stated causal/logical dependency; "§0" referent unclear (this document has no §0) — **UNRESOLVED referent** | L184 |
| REL-09 | P5 ↔ P1–P4 | P5 is labelled "synthesis" | Not classifiable from source beyond "synthesis" — **UNRESOLVED** | L167 |
| REL-10 | Baseline §3 → Product Boundary Discovery §6 | "central correction" of a boundary "refuted at n=1" | REFINEMENT is **not** asserted; source says "correction" of something "refuted". Recorded as **correction (source term)** | L69 |
| REL-11 | This baseline → Roadmap Stage 1 | "This document is Stage 1's substance" | Source-stated; no label | L234 |
| REL-12 | Subsystems ↔ T2 | subsystems are the content of T2 ("internal service layer") | Composition, per source | L40–L47, L51–L61 |
| REL-13 | METHOD ↔ "engineering law" | juxtaposed at L87 | **UNRESOLVED** whether SAME; source does not assert equivalence | L73, L87 |
| REL-14 | AI Runtime "adapter" ↔ PKS-generation "Human-in-the-Loop Adapter" | same word "adapter" in both | **UNRESOLVED** — possible SAME or HOMONYM; source does not relate them | L28, L150 |
| REL-15 | G-1 at L14 ↔ G-1 at L208 | same identifier | **UNRESOLVED** — possible SAME or HOMONYM; see Limitation 5 | L14, L208 |
| REL-16 | Stage 2 experiment ↔ OQ-S1 | experiment runs with "SD-1 suspended"; OQ-S1 asks whether SD-1 is binding or platform rule | Implied evidential link; **INFERRED, not stated** | L235, L190 |

---

## 07 — Epistemic Status (significant propositions)

| # | Proposition | Status | Note |
|---|---|---|---|
| E01 | The document is a candidate, not adopted | EXPLICIT | Self-declared |
| E02 | KnowledgeOS is "the reusable engineering platform" | PROPOSED | Definition within a candidate document; source's own canon status: "potential product" |
| E03 | The platform is a Supporting Subdomain of PublicDigit (AIP-14) | EXPLICIT (reported as canon) | Source reports prior canon; not verified |
| E04 | PKS: one per product, never reusable | PROPOSED | Definition within candidate document |
| E05 | All PKS to date hand-built; generated n=0 | OBSERVED | Reported |
| E06 | Product Binding is introduced by this baseline | EXPLICIT | Claim of contribution |
| E07 | `creates`, not `contains` | PROPOSED | Asserted relation within candidate |
| E08 | Five tiers / T2 service layer | PROPOSED | |
| E09 | Governance, Capabilities, Validation are operating | OBSERVED (source-reported) | Evidence names given, not shown |
| E10 | Runtime Integration evidenced at n=1 | OBSERVED (source-reported) | |
| E11 | Operational Learning: 0 traversals | OBSERVED (source-reported) | |
| E12 | PKS Generation: responsibility only | OBSERVED + PROPOSED | Absence of component observed; "responsibility with human adapter" is a proposed framing (P3) |
| E13 | Subsystems are not repository roots | PROPOSED ("critical rule") | Uncertain between PROPOSED and DECIDED: labelled "rule" but inside a non-adopted candidate → held as PROPOSED |
| E14 | No directory proposed | DECIDED (for this document) | |
| E15 | Product Boundary Discovery §6 file-list boundary refuted at n=1 | REJECTED (reported) | Refutation performed elsewhere; reported here |
| E16 | Boundary = METHOD/BINDING/EVIDENCE decomposition | PROPOSED | |
| E17 | Round47-OP decomposition assignments | PROPOSED | Applied classification |
| E18 | P1–P5 | PROPOSED (source term: CANDIDATE) | n=1 each |
| E19 | P1 was re-derived; cognate framework exists | EXPLICIT (annotation) / OBSERVED | Existence of Round38C-04 reported; cognate judgement is the annotator's |
| E20 | Tier assignments of named artifacts | PROPOSED | ~90%/~95% case-law figures: OBSERVED estimates, method unknown |
| E21 | Vocabulary search did not detect Tier-2/3 blockage | OBSERVED (reported, EAD-1) | |
| E22 | A three-question test is required | PROPOSED | Source says "required"; not specified or applied |
| E23 | Responsibility→component three conditions | PROPOSED | |
| E24 | "PKS Generator doesn't exist" imprecise | EXPLICIT ("accepted refinement") | Source says "accepted"; by whom not stated → acceptance status UNKNOWN |
| E25 | Harvest pattern over Evidence→Decision→Change | PROPOSED; alternative REJECTED | |
| E26 | ES-006.4 / ES-006.1 / Observation Protocol "READY" | EXPLICIT (source status) | G-7 reports ES standards PROPOSED — "READY" ≠ ratified |
| E27 | "Separate Ways with a Shared Kernel" internally tense | EXPLICIT (source argument); not asserted | Referred to ARB |
| E28 | L-1, L-2 | HYPOTHESIZED | |
| E29 | L-4 | OBSERVED (source: EVIDENCED) | |
| E30 | L-3, L-5 | OBSERVED partial | |
| E31 | L-6 empty | OBSERVED | |
| E32 | Platform governs change, not genesis | EXPLICIT (as a correction) — uncertain between OBSERVED and PROPOSED | Recorded as uncertain |
| E33 | 13 OQs open (two answered in part) | EXPLICIT | |
| E34 | 10 gaps | EXPLICIT | |
| E35 | CAP-001 changed a decision (G-6) | OBSERVED + DECIDED | Run observed; no-mint decided |
| E36 | Method "demonstrated portable" | EXPLICIT (source claim) | ⚠️ No demonstration contained in this file; source elsewhere records no second adopting product (G-3). Held as source claim, **basis UNKNOWN** |
| E37 | "The programme does not need more evidence to define its architecture" | EXPLICIT (source's closing judgement) | An evaluative claim, not an observation |
| E38 | Stages 3–5 blocked | EXPLICIT | |
| E39 | Document is falsifiable by the next bootstrap run | PROPOSED | |

---

## 08 — Open Questions (not to be answered from later material)

**Q001:** Was this baseline ever adopted by the Decision Authority? (Source: submitted, not adopted.)

**Q002:** What is the content of the four synthesized artifacts (Architecture Consolidation, Product Boundary Discovery, MVK Bootstrap Validation, Strategic Boundary Consolidation), and do they actually support the claims attributed to them?

**Q003:** Which chronological order holds among the files F0001–F0046, given they share one list timestamp? Is this file genuinely first, or only first in list order?

**Q004:** When exactly was the P1 annotation (L95–L101) added relative to the main body, and does its reference to `KnowledgeOS_Engineering_Knowledge_Landscape.md` §0 mean that document existed on 2026-08-02?

**Q005:** What is K-07, and how does it relate to `Round38C-04_Principle_Form_Classification_Framework`? (The annotation names both without stating their relation.)

**Q006:** Does "G-1" at L14 refer to this document's §7 G-1 ("L-6 never traversed") or to a differently defined prior G-1? What are the three existing "ungoverned series" implied by "a fourth"?

**Q007:** What are SD-1..SD-7, Round47-OP and Round47-00 in full, and what does "Certified Domain Knowledge Release v1.0" denote?

**Q008:** What is "the SD-1 diagnosis", and where was it recorded?

**Q009:** What precisely is the "three-question test"? Is it just the three properties (domain-free, binding-free, evidence-free) posed as questions, or something more?

**Q010:** On what evidence does the source say the *method* has been "demonstrated portable" (L257), given G-3 (no second adopting product)? Is this referring to the MVK Bootstrap Validation?

**Q011:** What is "the bootstrap instrument", and what was its prior result?

**Q012:** Who "accepted" the refinement of "PKS Generator doesn't exist" (L144)?

**Q013:** What are EEP, EAD-1, BRM-1, R-37, C3, PB-004, PMR-9/10, M-5/M-6, OQ-ENG-004, "the kernel", "the metamodel", "the four-layer model", "the Capability Model", "charter", "Phase II freeze"?

**Q014:** Is "Hospital · ERP …" in T4 a set of real prospective products or illustrative placeholders?

**Q015:** What does "§0" in "P3/§0" (L184) refer to, given this document has no §0?

**Q016:** Is the AI Runtime "adapter" (L28) the same concept as the "Human-in-the-Loop Adapter" (L150)?

**Q017:** Is the METHOD layer the same thing as "engineering law" (L87)?

**Q018:** How were the "~90%" and "~95% case law" proportions estimated?

**Q019:** What does the "KnowledgeOS gate" consist of, and who controls it (DA per OQ-K3)?

**Q020:** Is the relationship between KnowledgeOS and PublicDigit (Supporting Subdomain per canon vs. potential separate product) going to be resolved, and by whom? (OQ-K3/OQ-K5 bear on this.)

**Q021:** Will the Stage-2 experiment (bootstrap re-run with Round47-OP added, SD-1 suspended, on a different tiny product) be run, and with what result?

**Q022:** Was the concept "Product Binding" (as a term) present in earlier work, given the source claims it as its own contribution while also deriving P1 from the prior SD-1 diagnosis?

**Q023:** Does ES-005.3's research clause still hold (OQ-K1), and where does cross-product research live (OQ-K2)?

**Q024:** Is "Candidate" (source term) intended as the same epistemic category as "hypothesis"? The source uses both terms separately (HYPOTHESIS for L-1/L-2; CANDIDATE for P1–P5).

---

## 09 — Reconstructed State — **PROPOSED S1** (not accepted)

> **Based solely on this source, the following state can be reconstructed.**

1. **What the programme knew/recognized at this point (as reported by the source).** A programme named (provisionally) *KnowledgeOS* existed alongside a single business product, *PublicDigit*. Prior artifacts had already been produced (four named synthesis inputs, standards ES-001..006, a capability CAP-001, rulings, ARB/DA authorities). The source reports: one product; hand-built PKS only (n=0 generated); three internal subsystems operating, one at n=1, one never traversed, one responsibility-only; one lifecycle transition evidenced (engineering → software) and the evidence→platform transition empty; the platform is in canon a Supporting Subdomain of PublicDigit, with "product" status only potential.

2. **Problems identified.**
   - A prior file-list extraction boundary was refuted at n=1.
   - Tier-2 (binding-coupled) and Tier-3 (case-law-diluted) blockage is invisible to vocabulary search.
   - No mechanism generates a PKS; the role is filled by a person.
   - The evidence→improve-platform loop (L-6) has never been traversed.
   - Genesis (Idea → KnowledgeOS) is ungoverned.
   - Every principle rests on n=1.
   - No second product, no second runtime adapter, no market data.
   - An internal tension in the Addendum's DDD pattern reading ("Separate Ways with a Shared Kernel").
   - A six-vs-three subsystem discrepancy in the Addendum.
   - Identifier governance: KP series not governed; risk of a further ungoverned series.
   - (Annotation) P1 duplicates, in cognate form, an existing Principle/Form/Ambiguous framework and lacks its Ambiguous class.

3. **Concepts that existed in the source.** KnowledgeOS; PKS; Business Product; Running Software; AI Runtime (adapter); Product Binding; `creates` vs `contains`; five tiers; T2 services and six subsystems; METHOD / BINDING / EVIDENCE (case law); domain-/binding-/evidence-free; Portability Ladder; three-question test; responsibility vs component; Human-in-the-Loop Adapter; bootstrap PKS/engineering/software; Evidence Harvest; genesis; candidate vs rule (n=1); lifecycle L-1..L-6; roadmap Stages 0–5. (Full list: §03.)

4. **Solutions or mechanisms proposed.** P1 three-layer separation; P2 four-tier ladder with artifact assignments; P3 three-condition promotion rule; P4 harvest pattern; P5 platform/PKS boundary; replace SD-1 with a generic input contract; a three-question detection test; a one-session Stage-2 validation experiment; ARB ruling on OQ-S1; (annotation) derive P1 from K-07.

5. **What had actually been decided.** Only document-scoped decisions: do not mint KP-1..KP-5 (use local P1–P5); assign no DDD pattern; propose no directory; mark all six subsystems rather than drop any; classify P1–P5 as candidates. The baseline itself was **not** decided/adopted.

6. **What had actually been implemented.** Within this document: nothing. Reported elsewhere: CAP-001 realized (identifier-check script run once in this work, yielding the first recorded decision change); PublicDigit code; verification reports; governance standards (PROPOSED); reserved `registry/` namespace. Not implemented: PKS generator component; Pattern Cards / Evidence Register artifact.

7. **What remained uncertain.** All 13 open questions (OQ-S1 flagged highest leverage); adoption of the baseline; the three-question test's form; L-3 effect; portability of the boundary; whether the platform is a product; the name "KnowledgeOS"; the meanings of many cited identifiers; the relation of P1 to K-07 / Round38C-04.

8. **What had NOT yet been established.** Any principle as a rule (all n=1 candidates); any mechanism-generated PKS; any L-6 traversal; any second adopting product or runtime; any market evidence; any extraction; any adopted DDD relationship pattern between KnowledgeOS and PublicDigit; the lifecycle transitions L-1 and L-2 (hypotheses); the Genesis governance; ratification of ES standards, kernel, metamodel.

---

## 10 — Candidate Interpretations

> **CANDIDATE INTERPRETATION — NOT ESTABLISHED** (each below)

**CI-1 — The document marks a shift from discovery/reporting to consolidation.**
- *Supporting evidence:* commission "Stop writing reports" (L8); method "SYNTHESIS, not discovery" (L10); "This document is Stage 1's substance" (L234); closing "It needed synthesis" (L259). Git commit message "strategic discovery end stage" is metadata, not source content.
- *Against / limitations:* only this file's self-description; no prior file read to compare.
- *Confidence:* medium.
- *Why interpretation:* "shift" requires comparison with prior state, which is unavailable (previous accepted state NONE).

**CI-2 — The central idea of the document is the METHOD/BINDING/EVIDENCE decomposition.**
- *Supporting:* called "the real extraction work" (L86), "central correction" (L69), repeated in closing (L257), P1 and P2 both build on the three properties.
- *Against:* the source also foregrounds the CAP-001 decision change (L219–L227, L261) and OQ-S1; the P1 annotation downgrades P1's originality ("re-derived").
- *Confidence:* medium-high as a reading of emphasis within this file.
- *Why interpretation:* "central" is an emphasis judgement, not a source-stated ranking (except "central correction" for §3).

**CI-3 — The document operates with a strict evidence-count epistemology (n=0/n=1/n≥2, candidate vs rule).**
- *Supporting:* pervasive n-counts (L25, L91, L142, L152, L193, L217); "n=1 admits a CANDIDATE, never a rule" (L91); "A baseline becomes a baseline by an adoption event" (L6).
- *Against:* the closing claims "method demonstrated portable" (L257) and "does not need more evidence to define its architecture" (L259) are stronger than the n-count discipline elsewhere; tension not addressed by the source.
- *Confidence:* medium.
- *Why interpretation:* characterizes a pattern across the document; the source does not name it as a method of its own.

**CI-4 — There is an unresolved tension between KnowledgeOS as "potential product" and as "Supporting Subdomain of PublicDigit".**
- *Supporting:* L24 records both; OQ-K3, OQ-K5 bear on it.
- *Against:* the source may see no tension (canon vs aspiration); it does not call it a tension.
- *Confidence:* low-medium.
- *Why interpretation:* the source records both statuses without stating a conflict.

**CI-5 — The same-day P1 annotation shows the author's own later reading correcting the main body within the file.**
- *Supporting:* "ANNOTATED 2026-08-02 — P1 WAS RE-DERIVED, NOT DISCOVERED" (L95); the main-body claim "the concept this baseline contributes" (L29) sits uncorrected elsewhere.
- *Against:* author identity for the annotation is not stated; the annotation may be by a reviewer.
- *Confidence:* medium that it is a post-hoc correction; low on who made it.
- *Why interpretation:* sequence and authorship of edits are not stated.

---

## 11 — Reconstruction Boundary

### This file establishes

- That on (or dated) 2026-08-02 a **candidate, non-adopted** "Architecture Baseline" for KnowledgeOS was written, framed as a synthesis of named prior artifacts.
- The *content* of that candidate: definitions (F010–F018), five tiers (F019), six subsystems with evidence marking (F022), the METHOD/BINDING/EVIDENCE extraction decomposition (F026–F029), five candidate principles P1–P5 (F030–F042), a six-transition lifecycle (F043–F044), 13 open questions (F045), 10 evidence gaps (F046–F047), a six-stage roadmap (F048–F049).
- That the source **reports** one executed tool run (CAP-001 identifier check → INCONCLUSIVE) and one resulting decision (no identifiers minted).
- That the document declares it decides nothing beyond its own scoping and executes nothing.
- That the file was annotated (same stated date) to say P1 was re-derived from a cognate existing framework.

### This file does not establish

- That any of its definitions, principles, tiers, lifecycle or roadmap were adopted.
- That the reported counts (1,532 files, 106 reports, 0 traversals, ~90/95% case law) are accurate.
- That the method is portable to another product (claimed, but no second-product evidence in this file).
- That any DDD relationship pattern holds between KnowledgeOS and PublicDigit.
- That this is the earliest KnowledgeOS artifact (it explicitly is not).
- The meaning of the many cited but undefined identifiers.
- Who authored the document or the annotation (it says "Generated"; a commission and an Addendum are cited).
- Any KnowledgeOS "theory". **The source does not present itself as theory**; it presents a candidate architecture reference model.

### Still unknown

- Adoption outcome; ARB rulings on OQ-S1..OQ-C2; the three-question test's form; Stage-2 experiment outcome; true chronological order within F0001–F0046; the content of prior synthesized artifacts; the referents of G-1 (L14), §0 (L184), K-07.

### Evidence required to resolve the unknowns

- A record of the Decision Authority's adoption/non-adoption of this baseline.
- ARB rulings on the listed OQs.
- The four synthesized artifacts (Architecture Consolidation, Product Boundary Discovery, MVK Bootstrap Validation, Strategic Boundary Consolidation) — to check attributions.
- Authoring timestamps / version history finer than the single 2026-08-04 commit, to order F0001–F0046 and date the P1 annotation.
- `KnowledgeOS_Engineering_Knowledge_Landscape.md` §0 and `Round38C-04_Principle_Form_Classification_Framework`, for the P1 annotation.
- CAP-001 README §6 and §9, for the G-6 claim.
- Definitions of the undefined identifiers (Q013).
- A record of any Stage-2 bootstrap run and any application of the three-question test.

---

## 12 — Potential Theoretical Significance

Each item: **Potential significance only. No theoretical conclusion established.**

1. **METHOD / BINDING / EVIDENCE separation.** *Potentially significant for later theory reconstruction* because it introduces a distinction between reusable method, product-specific input contract, and product-specific case law, and uses it to redefine what "extraction" means.
2. **Product Binding as "input contract a reusable method requires".** May later matter because it frames product-specificity as an interface requirement rather than as content.
3. **`creates` vs `contains`.** May later matter because it distinguishes a generating platform from a container of knowledge.
4. **Responsibility vs Component (P3) and the Human-in-the-Loop Adapter.** May later matter because it distinguishes a role that is exercised from a mechanism that exists, with explicit evidence thresholds.
5. **Evidence → HARVEST → Candidate → Promotion → Change (P4).** May later matter because it inserts a candidate/promotion stage between evidence and change.
6. **Candidate vs rule by evidence count (n=1 → candidate).** May later matter as an epistemic-status discipline.
7. **"Detection needs a test, not a grep" (P2 instrument finding).** May later matter because it distinguishes structural properties of artifacts from vocabulary-level properties.
8. **Change vs genesis.** May later matter because it identifies an ungoverned lifecycle phase.
9. **Tool verdict changing a decision (G-6).** May later matter as the source's own criterion that a capability "changes an engineering decision rather than merely describing one".
10. **Portability axis vs constitutional-entrenchment axis (annotation).** May later matter because it records two cognate classification axes and a missing "Ambiguous" class.
11. **"A baseline becomes a baseline by adoption, not authorship."** May later matter because it separates the existence of a document from its authority.

---

## Final Reconstruction Summary

```text
CR-0001

SOURCE:
docs/knowledgeos/2026-08-02-knowledgeos-architecture-baseline.md
(267 lines, 20,733 bytes; read in full, single pass)

DATE:
2026-08-02 (filename, commission line, annotation). Git first commit 2026-08-04 08:26 +0200.
List mtime Aug 5 15:44 (shared by F0001–F0046; not an authorship date).

INITIAL STATE:
Previous accepted state NONE. Source is a CANDIDATE, NOT ADOPTED, generated,
non-authoritative, non-executing "Architecture Baseline", self-declared as a
SYNTHESIS of four named prior artifacts (not read). It is not the programme's
first artifact.

ESTABLISHED CONCEPTS:
None established as adopted. Explicitly defined in the source (candidate status):
KnowledgeOS · PKS (Product Knowledge Space) · Business Product · Running Software ·
AI Runtime (adapter) · Product Binding.

NEW CONCEPTS (claimed or first assembled in this source):
Product Binding (explicitly claimed as this baseline's contribution) ·
METHOD/BINDING/EVIDENCE decomposition as extraction boundary · Portability Ladder
(4 tiers) · three-question test (named, not specified) · Responsibility vs Component
rule · Human-in-the-Loop Adapter · Evidence Harvest pattern · creates-not-contains ·
five tiers T1–T5 · six subsystems with evidence marking · genesis vs change.
(Whether first formulated here vs collected from prior work: UNKNOWN.)

DECISIONS:
Do not mint KP-1..KP-5 (use document-local P1–P5) · assign no DDD pattern ·
propose no directory · mark all six subsystems · classify P1–P5 as candidates.
The baseline itself: NOT decided / NOT adopted.

IMPLEMENTATIONS:
None in this document. Reported: one run of `php scripts/identifier-check.php KP-1`
→ INCONCLUSIVE → decision changed. Reported elsewhere as existing: CAP-001
(realized), CAP-004/006, ES-001..006 (PROPOSED), PublicDigit code, 106 verification
reports. Explicitly NOT implemented: PKS generator component; Pattern Cards/Evidence
Register artifact.

HYPOTHESES:
L-1 (KnowledgeOS → discover a product), L-2 (discovery → generate a PKS).
P1–P5 are labelled CANDIDATES (not "hypotheses") by the source.

OPEN QUESTIONS:
Source: OQ-S1..S5, OQ-K1..K6, OQ-C1..C2 (13; OQ-S4, OQ-S5 answered in part).
Reconstruction: Q001–Q024 (adoption, chronology, annotation timing, undefined
identifiers, G-1 ambiguity, basis of "method demonstrated portable", etc.).

RELATIONSHIPS:
KnowledgeOS –creates→ PKS (explicitly not contains) · PKS –guides→ Product ·
P1 DERIVED-FROM SD-1 diagnosis (source term) · SD-1 archetype of Product Binding
(tentative SPECIALIZATION) · P1 "cognate, not duplicate" of Round38C-04 (UNRESOLVED) ·
§3 "corrects" Product Boundary Discovery §6 (not labelled REFINEMENT) ·
several UNRESOLVED (P2↔P1, METHOD↔"engineering law", adapter↔adapter, G-1↔G-1).

THEORETICAL CLAIMS:
Source advances a candidate architecture reference model, five candidate
principles at n=1, and an evaluative closing ("method demonstrated portable;
boundary not"; "does not need more evidence to define its architecture").
No claim presented as theory.

THEORY ESTABLISHED:
NONE

RECONSTRUCTION CONFIDENCE:
High for fact extraction and locations (full read, line-referenced).
Medium for epistemic-status assignments where the source's own labels
(e.g. "rule", "READY", "accepted refinement") sit inside a non-adopted document.
Low for chronological position among F0001–F0046.

KNOWN LIMITATIONS:
Chronological order within F0001–F0046 undetermined (shared mtime) · source is a
synthesis of unread prior artifacts · in-file same-day annotation cites a later-listed
file (F0019), not read · many identifiers undefined in source · G-1 identifier
ambiguity (L14 vs L208) · "§0" referent at L184 unresolved · all counts/statuses are
source-reported, not verified.
```

---

*CR-0001 complete. Stopped here per Rule 9. CR-0002 not begun. Awaiting independent review.*
