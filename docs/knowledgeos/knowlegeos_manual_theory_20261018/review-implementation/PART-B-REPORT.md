# Part B: the strategic design (Ch. 10) set against the EKS code and the reference architecture (Verification Auditor)

Mandate: `03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`, revision 1 (approved 2026-10-29), with the test-run line added after part A, §4. Authorisation: the Principal's decision record, addendum "the Verification Auditor's part B" (commit `4b1f5dfdfcf2d485bceb95251623a08725e08f60`). This report recommends nothing and authorises no change.

## Baseline (mandate §4a)

| Item | Content |
|---|---|
| Audit date | 2026-10-29 |
| Run | **new audit** (part B, first report). It builds on the part-A reports, which are not reruns and are cited by sha256: `INVENTORY.md` `418c6f12e629305f0862ddcde704756c71a835e8c770566cdbf87a42a980b07b`, `DESIGN-VS-CODE.md` `37310772c21f84bcc856ecf419c46e6572bf12111757a7103d5a56ed6b320262`, `AUDIT-PLAN.md` `bc083fd92c0da30093d1c233bef67e07e95b78926f39309049c0d7c121042198`. Part A's figures are reused only because C and D are unchanged (next two rows) |
| C | repository commit `a5416f3bea882667a9758a64ec0be5bd128ae504` (HEAD at the run). `git diff 45c310312 a5416f3be` is empty for `scripts/lib/EngineeringKnowledge/`, the three entry points, `tests/Unit/Cohesion/`, `composer.json` and `phpunit.xml`, and none of these paths has an uncommitted change. Paths: as in `INVENTORY.md` (library last changed at `a72528b29657170fecc416c34dc61b899ba345d1`) |
| D | the same three files as part A, unchanged at `a5416f3be`: Reference Architecture `c861e5d1f6c9aa24ce69dae58311511e5c5660eb` (sha256 `08dfea9949a0ef7e75aab73839eda9f14131d3f054025d95a2919a73c97a7d84`); Decision Model `c8906442478dbee2bf72d2b9ba64765f885a91e3` (`c19339d3d570aae3ad86cae3a86c74c2542dedebcb8a323df0b5a55ed117d9a5`); Knowledge Metamodel `3bdcc3dba5542af9e046d9ef4c2651a8954b6f04` (`a5c88e9bdbc5a40fe38304fc728508340a69b53788f42adac90c20996b9c2b44`). ADRs used: **none** |
| T | strategic-design baseline at `dca72c57f234b8362255e6e3ff706caa308e2b40` (turn 5 settled). Copies read with `git show dca72c57f:<path>` from `06-theory/manuscript/chapter_10/`: `10.01-subdomains.md` (blob `df3c22da`, sha256 `5042f5ea270849be52e01d384c6c64557b157acaa9cfc13e3c344b8fef6ece68`); `10.02-bounded-contexts.md` (`7f6dc9fc`, `5b4e19b110795cf716bb929cec9d777e262e2ef23e6671df697b2ae51b250a25`); `10.03-ownership-of-invariants.md` (`841bd099`, `9900ecbcffb1125b9b8efbcb76c2981ef5375c0787297a603696ec2f574e8767`); `10.04-context-map.md` (`80696991`, `5255b80bc405753dd33532855203184e1f598860b2cb808ea1a2e75c9f092f53`); `10.05-published-language.md` (`1e8fe0b7`, `12c03d42ff3bf7b49b576f21830901c32303919c1f0e8609bb3e346e529c6d5e`). §9.13 and §9.17 were **not read**: every comparison below uses only what Ch. 10 states, and none needed the cited text itself. **Every T element is at level S0** (Ch. 10 throughout) |
| Known coming change | The Principal has decided T10-16 (b): the record and its kind list are owned by no area (commit `382fa6d85e125167879e0dd75e041d93376bafd1`). This is not yet applied to Ch. 10. The audit is against `dca72c57f` as it stands. Findings that (b) would change are marked **"baseline will change (T10-16 (b))"** |
| Tests | `vendor/bin/phpunit --testsuite EngineeringKnowledge --do-not-cache-result --colors=never` → `OK (252 tests, 603 assertions)`, exit 0. `vendor/bin/phpunit tests/Unit/Cohesion --do-not-cache-result --colors=never` → `OK (187 tests, 509 assertions)`, exit 0. PHP 8.5.11, PHPUnit 11.5.6 |
| Scope | T §10.1–§10.5 against C (library, entry points, Cohesion tests by name) and D (three reference files). **Exclusions:** (a) everything else under `docs/knowledgeos/`, including Ch. 10's index, change log and TODO, which are outside the authorised §10.1–§10.5; (b) ADRs and other `engineering/` documents D cites, including Phase-02's six contexts, so D's context list is known only through the Metamodel's §8 summary; (c) test bodies, except where cited |
| Other | **Fixture-reading test, disclosed under mandate §2:** `scripts/lib/EngineeringKnowledge/Tests/BackTest/Phase0BackTest.php` reads `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` at three commits through `git show`. The auditor did not view its content. T line references below are to the copies listed above. "Row n" means §10.1's table and "candidate n" means §10.2 |

**Labels.** Statements are labelled **verified**, **reported**, **inferred** or **open**, as in part A. Every finding has six parts in order: observation · evidence · class · confidence · status · limitations. Because all of Ch. 10 is S0, **no finding is a code deviation or a defect** (mandate §4b; the commission). Each status is ***awaiting the Principal's decision***.

## 0. The frame: what is compared with what

- **T** describes **KnowledgeOS as the reference product**, as a counterfactual: "what the classes would be if KnowledgeOS were the product" (10.01:13). The core of T is **current standing**: a designated record of authority acts and an evaluator CE* that answers T, F or U with reasons (row 1, 10.01:54; candidate 1, 10.02:79). Only **standing** is an admitted bounded context (10.03:317–322). Norms and the catalogue are not admitted; well-foundedness is pending G1; delivery, method, work management and assessment are **not contexts**.
- **C** is a library of document-structure checks (S1–S5) and a code-cohesion metric (LCOM4). It is pinned to this repository's corpus (`INVENTORY.md` §2).
- **D** describes the estate's Engineering Platform, an "engineering governance architecture — not a software system" (RA:14). D was written before C and does not describe it (`DESIGN-VS-CODE.md` D-0).

Every mapping below therefore reads C and D **as if** they were parts of KnowledgeOS. That reading is the auditor's, and finding B-02 records it.

## 1. Subdomains: §10.1's areas mapped to C and D

| §10.1 row (class under (b), 10.01:81–83) | C modules | D statements | Label · confidence |
|---|---|---|---|
| 1 Current standing (**core**) | **none.** C contains no type, operation or field for a record of authority acts, entry kinds, grants, acts or CE*. The terms grant, revoke, annul, tombstone, designate, adopt, consumption and single-use occur in production code only as prose (two docblock mentions of "grant", one of "adoption"), never as code identifiers | RA §2 "Engineering Governance": rulings register, approvals, freezes (RA:27); MM: Human Decision Event recorded by ADR/Ruling, "authority transitions require a referenced HumanDecisionEvent" (MM §5, §7) | C: **verified**, high. D: **reported**; the mapping is **inferred**, medium |
| 2 Well-foundedness (**core: showing**; supporting: assessment) | Shared/Domain `Assessment` (verdict and evidence), `AssuranceHandoffReport` (aggregate), `Verdict` | RA "Engineering Evidence: … evidence is produced, never asserted" (RA:30); MM Evidence type "never becomes authoritative" | **inferred**, medium (B-04, B-05, B-08) |
| 3 Presentation at the point of use (**core**) | Shared/Infrastructure `StructuralCliReporter`; IdentifierIntegrity `CliOutputFormatter`. Both are "presentation only" and render `$verdict->value` (`StructuralCliReporter.php:110`, `CliOutputFormatter.php:48`) | none | **inferred**, low: they show assessment verdicts, not standing or well-foundedness classes |
| 8 The engineering method (supporting) | the handoff report as author-side practice before an architecture or governance handoff (`GenerateHandoffAssuranceReport`) | RA "Engineering Execution" (RA:28); the Decision Model as a whole | **inferred**, low |
| 9 Identity of knowledge items (supporting) | **IdentifierIntegrity** ("Protect the integrity of governed identifiers"; DP-1 "unique within its register(ns), checked before minting") | MM §2 "ids-not-filenames", identity schemes per kind | **inferred**, medium-high (B-03) |
| 10 Relationships and history (supporting) | ReferenceIntegrity S2 (references inside a document) | MM §5 relationship verbs, `supersedes` | **inferred**, low: a § reference is not a relationship between knowledge items |
| 13 Measurement and observation (supporting) | **Cohesion** (LCOM4); the checks S1–S5 as verification procedures (candidate 8, 10.02:169) | RA "Engineering Qualification" (RA:29); "Code metrics" deferred (RA:89) | **inferred**, medium for S1–S5 as assessment methods; low for Cohesion (B-12) |
| 14 Carriers and storage (generic) | the Markdown and YAML readers behind ports (`Markdown*Reader`, `YamlVocabularySource`), "Replaceable: … a YAML or graph reader substitutes here without the domain changing" | — | **inferred**, medium |
| 15 Runtime adapters for agents and tools (generic) | the three CLI entry points (`identifier-check.php`, `knowledge-lint.php`, `link-check.php`) | — | **inferred**, medium |
| no row | **VocabularyIntegrity** (retired terms, confusable identifiers, competing current definitions) | — | B-13 |

## 2. Bounded contexts

| T statement | C | D | Label |
|---|---|---|---|
| **Standing is the one admitted context** (10.03:318; 10.04:15), admitted "by construction (D is standing's)" (10.03:288) | no module (§1 row 1) | MM §8: "the frozen six bounded contexts + two external domains (Phase-02)", among them a governance line and "Human Authority as the authoritative event source" | C: **verified**. D: **reported** (Phase-02 not read). See B-01, B-15. *The ground "D is standing's" will change under T10-16 (b); the mapping itself does not* |
| Candidates 2 (pending), 4 and 5 (not admitted today), 3, 6, 7 and 8 (not contexts) | C's four capabilities fall within non-context areas: 5 (IdentifierIntegrity), 8 (S1–S5, Cohesion), 6 (the handoff report). C states of CAP-001: "**Not a bounded context** — this is a capability (AD-1 §6.2)" (`IdentifierIntegrity/README.md:62`) | MM §8 "Families/meta-kinds ≠ bounded contexts" | the C self-declaration: **verified** as text. Agreement with T's statuses: **inferred**, medium. **No code boundary contradicts a T context boundary**, because the one T context has no code |
| Code boundaries inside non-context areas | C enforces internal module boundaries: Shared depends on no capability, no inward-to-outward edge; ReferenceIntegrity → IdentifierIntegrity is the only edge between capabilities (`INVENTORY.md` §3) | — | **verified**. T neither requires nor forbids internal structure in a non-context area, so this is not a difference |

## 3. Ownership of invariants: where C enforces, set against T's owner

*Relations are §10.3.2's: owns, enforces for another, respects, observes, assumes (10.03:63–71). Only invariants with a counterpart in C are listed. **None of row A (standing's invariants, 10.03:81–106) has an enforcing site in C** (B-01).*

| T invariant (row) | T owner · T enforcer | Where C acts, and how | C's relation in T's terms | Finding |
|---|---|---|---|---|
| identity unique within its register, §1.10 (E, 10.03:145) | 5 · 5 (minting) | `AssessesIdentifierIntegrity::validate` (FAIL if already minted, line 58; WARN if cited but unminted, line 71; INCONCLUSIVE if the series is not a governed register, line 45). **C has no minting operation**; the check is run on its own ("Run before minting", README §4) | **observes**, before the act; cannot prevent | B-03 |
| one-sidedness: an empty record never yields "supported" (B, 10.03:114) | 2 · **none established (G1)** | every application service turns a read failure into INCONCLUSIVE (for example `ValidateIdentifier.php:37–40`). The domain services return INCONCLUSIVE when there is nothing to evaluate (run in part A: "Absence of evidence is not PASS"). `AssuranceHandoffReport::of` refuses an empty check set (line 66), and its aggregate is fail-closed (lines 121–133) | enforces (by construction) an analogue | B-04 |
| I-9, quality evidenced, never asserted (B, 10.03:112) | 2 · none established (G1) | `Assessment::of` refuses an empty evidence string (`Assessment.php:38–48`) | enforces an analogue | B-05 |
| the declared, authorised producer of each entry, CON-0150 (B, 10.03:118) | **shared 1 and 2** · none (G4) | `HandoffContext` carries checker name, version, commit, time and command line, "never AI-process attestation … EKS-07 stays unsolved" (`HandoffContext.php:15–16`). `Assessment` has no producer | neither | B-06 |
| law 4a never-grant; the roles law, "a recorder does not decide" (A, 10.03:86, 101): 7 and 8 **respect** | 1 · 1 | `Verdict::emittable()` excludes PASS AFTER CORRECTION, EMERGENT and CERTIFIED; `Assessment::of` throws on them (`Assessment.php:38`). No code in C records or grants authority | **respects** (agrees with T) | none: an agreement |
| show both values, with reasons, unaltered (C, 10.03:124); PL-14 | shared 1 and 2 · 3 enforces for another, as a role (an assumption on consumers, 10.05:265) | the reporters render the verdict value unaltered; the NOT-CHECKED statement must never be empty (`AssuranceHandoffReport.php:76`) | respects (consumer side) | none: an agreement in form, low confidence (verdicts are not T's values) |
| I-4, contradictions visible (C, 10.03:127); law 6 / I-7, supersession a link (E, 10.03:144) | shared 1 and 2 / 5 · 3 / none | S5 flags unlabelled competing current definitions, WARN only (`AssessesCompetingCurrentDefinitions.php:145`). S3 FAILs a live retired term (`AssessesVocabularyIntegrity.php:72`) | observes an analogue on wording | B-13 |

## 4. Context map: T's relationships and their direction, against C's dependencies

*T rows are written upstream → downstream with a type (10.04:21–24). C's dependency direction is from `INVENTORY.md` §3. ◇ marks a relationship that is conditional in T.*

| T relationship | C counterpart (direction · mechanism) | Under each branch | Finding |
|---|---|---|---|
| **R0 ◇** the record and its kind list → every area (conforms); owner open, T10-16 (10.04:52) | no record, no kind list. The nearest analogue is the list of governed registers: a configuration file whose edit changes verdicts, "configuration, NOT a ruling (G-1 is open)" (`identifier-check --series`; `GovernedRegisterMap.php:14–28`) | T as it stands: owner open, today standing's schema. Under T10-16 (b): no area, changed only by an authorised act outside every area. C: changed by editing a file; C records no act | B-14 — **baseline will change (T10-16 (b))** |
| **R7 ◇** W → A (A writes under W's rule) (10.04:59) | Shared/Domain (Verdict, Assessment) ← each capability: the capabilities depend on Shared, so **the direction matches** if Shared is read as W's language and the capabilities as A. But nothing is written into any record: an Assessment is returned and printed | gap G1 open: W has no kinds, so there is nothing to write into; C matches as far as the language goes. Gap G1 closed with W's kinds in the one record (T10-10 (1)): A would append entries; C has no append, so the mapping fails. **Unresolved under the second branch** | B-08 |
| **R13 ◇** C → W (reads identity and supersession) (10.04:65) | ReferenceIntegrity (assessment) reads IdentifierIntegrity's register: `ReferenceIntegrityContents.php:7`, `MarkdownIntraDocumentReader.php:7`. **Catalogue → assessment**, a row T does not draw | conditional on C (doubtful) and G1 under both branches; the T map has no C → A row in either | B-11 |
| **R18 ◇** P → C, N (informs: product facts); translator **not named**, T10-14 (10.04:70; 10.04:147) | C translates at its receiving side: application-owned ports (`SemanticFactProvider`, "Application-owned port", `Ports/SemanticFactProvider.php:10`; the `*Reader` ports), with Markdown, PHP and Python adapters turning product artifacts into closed-vocabulary facts | T10-14 option (a), an ACL owned by the receiving side, resembles C; option (b), translation as acceptance, has no counterpart (C has no acceptance into a record) | B-10 |
| P → A (product code measured) | Cohesion reads product source (PHP through `token_get_all`; Python through `python3`) | T draws no P → A row | B-12 |
| **R12 ◇** (source open) → S: unrecorded inputs change answers without a new entry (10.04:64) | not applicable: no S in C. C's verdicts likewise change with unrecorded configuration (B-14) | — | B-14 |
| R1 AU → S; R2 S → AU; R3 S → D; R8 S → WM; R19 M → P (the unconditional rows, 10.04:132) | **none in C** (no S; no work-management or method code). The handoff report is meant for an author's handoff to Architecture/Governance (`AssuranceHandoffReport` docblock), an A → AU flow that T does not draw | — | B-01 |
| the direction law, a candidate law (§10.4.3) | not testable on C: C has no kernel | — | none |

## 5. Published language (§10.5) against the actual interfaces

- T's published language is **standing's** (PL-0 to PL-14, 10.05:58–271). It is "a domain meaning and a promise"; "no technical interface … is fixed" (10.05:22).
- C publishes **technical interfaces**: ports, `Verdict` and `Assessment`, CLI arguments and exit codes (identifier-check 0/1/2/3), the NOT-CHECKED text, JSON output, and YAML configuration (`INVENTORY.md` §4).

| PL item | C | Label · finding |
|---|---|---|
| PL-0 record and kind list; PL-1 kinds; PL-2 acceptance; PL-3 form; PL-4 identity and merge; PL-5 owner rules | **none** | **verified** absent (B-01). PL-0: **baseline will change (T10-16 (b))** |
| PL-6 Q1 (T/F/U/refused); PL-7; PL-8 Q3; PL-9 Q2; PL-10 marks; PL-11 | **none.** C's `Verdict` (PASS/FAIL/WARN/INCONCLUSIVE) answers a different question, so it is **not** a counterpart of T/F/U: C's FAIL is a positive defect finding, while T's F includes "nothing recorded" and "must not be read as refuted" (10.05:160) | **verified**; the comparison is pre-empted as a likely mapping error (B-08) |
| PL-12 (c), PL-13: what a T does not establish, published as such | C's mandatory NOT-CHECKED statement: "A PASS here is mechanical, not architectural, assurance" (part-A run; `AssuranceHandoffReport.php:76`) | agreement in **form**, inferred, low |
| PL-14 joint display; §10.5.3 D row | reporters show verdicts unaltered (§3 above) | agreement in form, inferred, low |
| §10.5.3 **W**: "none yet"; promise (b) requires the class vocabulary refuted / not attested / supported by recorded evidence, each with its entries, plus the must-do of accepting evidence with refusal reasons (gaps G1, G7, G8) (10.05:277) | C publishes the nearest analogue: `Verdict` and `Assessment(verdict, evidence: string, subject: ?string)` — no polarity, no item or version as object, no producer | B-08 |
| §10.5.6 "Is it published?" (S0039's test: a consumer other than the producer relies on it) | C's interfaces have consumers other than their producers (the three entry points; `Tests/Adapters`). The test was not applied to T's language, because C has no counterpart of it | **verified** for C; not a comparison |

## 6. Findings

**B-01 · Standing, the one admitted context and the core of T, has no counterpart in C.**
1. *Observation:* C contains no record of authority acts, no entry kinds, no acceptance guards, no evaluator, and no T/F/U answer. None of row A's invariants and none of PL-0 to PL-11 has a code site.
2. *Evidence:* T 10.01:54, 10.03:81–106 and 318, 10.05:58–217. In C, a whole-word search over production code finds the standing vocabulary only in prose (§1 row 1). The class and interface list is in `INVENTORY.md` §2.
3. *Class:* **unresolved**. The class table has no class for "a theory element with no code"; "theory gap" and "outside the theory's scope" describe the reverse cases. *The auditor asks the Principal whether such cases need a class.*
4. *Confidence:* high (the absence is shown directly).
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* the element is at S0. C was built for the estate's document checks, not as KnowledgeOS (B-02), so the absence may be expected. A standing implementation elsewhere in the repository (outside C) was not searched.

**B-02 · The three views have different reference products.**
1. *Observation:* T is a counterfactual with KnowledgeOS as the product. D describes the estate's Engineering Platform, and canon calls it a Supporting Subdomain of PublicDigit (10.01:13). C's readers are "pinned to the corpus" of this estate.
2. *Evidence:* 10.01:13; RA:14; MM §8; for example the `MarkdownTableReader` and `MarkdownVocabularyReader` docblocks.
3. *Class:* **unresolved**. Every mapping in §1–§5 depends on reading C and D as parts of KnowledgeOS. If that reading is wrong, the affected mappings are **mapping errors**.
4. *Confidence:* medium.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* T's own stipulation 1 states the counterfactual. Whether the auditor should compare under it was not stated in the commission.

**B-03 · Identity: C checks uniqueness before minting, but nothing in C mints, so the check observes rather than enforces.**
1. *Observation:* T's invariant "identity unique within its register" (row E) is owned by candidate 5 and enforced by 5 through **minting**. C's IdentifierIntegrity returns FAIL, WARN or INCONCLUSIVE for a proposed identifier, but no C operation mints. The check is advisory and run separately; it "PREVENTS; it never REPAIRS" only if the person minting runs it. C also applies the register notion to a document's numbered sections (S1).
2. *Evidence:* T 10.03:145, 10.02:149–154 (candidate 5 not admitted, 10.03:321). C: `AssessesIdentifierIntegrity.php:45, 58, 71, 84`; `identifier-check.php:22–25`; README §4.
3. *Class:* **unresolved**, with two readings:
   - (i) IdentifierIntegrity is part of 5. Then C has the owner's criterion but not the enforcing step T names.
   - (ii) It is an assessment method (8). Then 8 performs a check that T places in 5's minting, while T says 8 enforces nothing (10.03:159).
4. *Confidence:* medium.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. Whether a document's section number is a "knowledge item" in §1.10's sense is **open**. The governed-register list is configuration with G-1 open (B-14).

**B-04 · C enforces "absence is never a positive result", which T assigns to well-foundedness without naming an enforcer.**
1. *Observation:* T's one-sidedness invariant (an empty record never yields "supported") is owned by 2, with no enforcer established (G1). C enforces an analogue in every application service and in the report aggregate: unreadable input or nothing to evaluate gives INCONCLUSIVE, never PASS, and an empty check set is refused.
2. *Evidence:* T 10.03:114. C: `ValidateIdentifier.php:37–40` and the five other `Validate*` services (`INVENTORY.md`; lines listed in §3); `AssuranceHandoffReport.php:66, 121–133`; the part-A structural run (S1–S3 and S5 INCONCLUSIVE when nothing was found).
3. *Class:* **theory gap**. T does not yet define the enforcer, pending G1 and G6.
4. *Confidence:* medium. The observation is high; the mapping PASS ≈ "supported" is an interpretation, and C's subject is document structure, not a knowledge item's content.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. T's three-class vocabulary has no counterpart of WARN.

**B-05 · Evidence is required by construction in C; T has no enforcer for "evidenced, never asserted".**
1. *Observation:* I-9 ("quality evidenced, never asserted") is owned by 2, with no enforcer (G1). In C, `Assessment::of` refuses an empty evidence string.
2. *Evidence:* T 10.03:112. C: `Assessment.php:38–48`.
3. *Class:* **theory gap**.
4. *Confidence:* medium. A non-empty string is weaker than recorded evidence in T's sense: no polarity, no object, no producer.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. **Homonym:** D's Metamodel also has an "I-9" ("owner: exactly one", MM §7), a different invariant. Labels were not matched across documents.

**B-06 · Producer authorisation is unsolved in both T and C.**
1. *Observation:* T requires a declared, authorised producer for each evidence entry (CON-0150; shared 1 and 2; gap G4; no enforcer). C records code provenance only (checker, version, commit, time, command line) and states that authorship is not attested ("EKS-07 stays unsolved").
2. *Evidence:* T 10.03:118, 10.04 R5. C: `HandoffContext.php:15–27`; `Assessment` has no producer field.
3. *Class:* **theory gap** (G4 open). C does not contradict T; both leave it open.
4. *Confidence:* high for the observation, medium for the mapping.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. Conditional on gap G1 (R5 ◇): under both branches, C has no authorising act to cite.

**B-08 · C's assessment language is the nearest counterpart of W's unstated language, and it differs in vocabulary and structure.**
1. *Observation:* T has no published language for W yet (gap G1). Promise (b) requires the classes refuted / not attested / supported by recorded evidence, each with its entries, and acceptance of evidence with refusal reasons. C publishes `Verdict` (4 emittable of 7) and `Assessment(verdict, evidence string, subject)`. Under R7 the direction (W's language upstream of A) matches C's dependency direction (Shared upstream of the capabilities). C has no counterpart of an acceptance into a record. C's verdict must **not** be compared with PL-6's T/F/U: C's FAIL is a positive finding, while T's F includes "nothing recorded".
2. *Evidence:* T 10.05:277, 160; 10.04:59. C: `Verdict.php:21–38`; `Assessment.php`; `INVENTORY.md` §3.
3. *Class:* **theory gap** (W's language is not stated: G1, G7, G8). R7 under the branch "gap G1 closed with W's kinds in the one record": **unresolved**.
4. *Confidence:* medium.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. The verdict classes and W's classes are matched by the auditor, not by any source.

**B-10 · C has receiving-side translators where T names no translator.**
1. *Observation:* T's R18 translation boundary (product terms into items and rules) has **no translator named**, and no ACL is identified anywhere (T10-14, open; options (a) an ACL owned by the receiving side, (b) translation as acceptance). C translates product artifacts at its receiving side, behind ports it owns: Markdown readers, and the PHP and Python fact adapters behind `SemanticFactProvider`. The receiving modules own the vocabulary, and the adapters produce closed-vocabulary facts.
2. *Evidence:* T 10.04:70, 147, 152–156. C: `Ports/SemanticFactProvider.php:10–20`; the `PhpFactExtractor` docblock; the `*Reader` port docblocks.
3. *Class:* **theory gap** (T10-14 open). C resembles option (a).
4. *Confidence:* medium.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. In T, option (a)'s receivers (catalogue, norms) are not contexts and would have "no owner of record". C's receivers are assessment modules, not the catalogue or norms. Cohesion's input is product **code**, not product **facts** (B-12).

**B-11 · C has a catalogue → assessment dependency that the T map does not draw.**
1. *Observation:* ReferenceIntegrity (assessment) depends on IdentifierIntegrity's section register, in both its domain and its reader. T draws C → W (R13 ◇) but no C → A row.
2. *Evidence:* C: `ReferenceIntegrityContents.php:7`, `MarkdownIntraDocumentReader.php:7`. T: 10.04:52–73, 75–77.
3. *Class:* **theory gap**. T's header states that the map "is not complete" (10.04:5).
4. *Confidence:* medium. It depends on B-03's reading (i) or (ii).
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. R13 is conditional (C doubtful; gap G1). The edge concerns document section numbers (B-03).

**B-12 · Cohesion measures product code; whether that is in T's scope is not decided.**
1. *Observation:* Cohesion computes LCOM4 over product source files (PHP; Python as a bounded experiment). It has no production entry point. T's row 13 (measurement and observation) is supporting and at S0 "with no book concept", and T draws no P → A relationship.
2. *Evidence:* T 10.01:66; 10.02:169; 10.04:70. C: `Lcom4.php`; `AnalyseCohesion.php`; `INVENTORY.md` §4.
3. *Class:* **unresolved**: either row 13 (with a missing P → A row, a theory gap) or **outside the theory's scope** (code quality is not knowledge).
4. *Confidence:* low.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. The D side is in part A's V-1 (RA defers "code metrics").

**B-13 · VocabularyIntegrity has no T subdomain; its checks resemble two T invariants on a different object.**
1. *Observation:* C checks term integrity (live retired terms, confusable identifiers, competing current definitions). No §10.1 row names vocabulary or term integrity. S5 (unlabelled competing current definitions) and S3 (live retired terms) resemble I-4 (contradictions visible) and law 6 / I-7 (supersession a link). But they apply to **wording inside a document**, not to items or acts.
2. *Evidence:* T 10.01:54–68; 10.03:127, 144. C: `AssessesCompetingCurrentDefinitions.php:145` (WARN only); `AssessesVocabularyIntegrity.php:72`.
3. *Class:* **unresolved**: a theory gap (no row) or outside the theory's scope.
4. *Confidence:* low.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. "Term" is core in PKS's own classification (10.01, S0196 row), which T records as another product's.

**B-14 · In C, the list of governed registers and the vocabulary are configuration that changes verdicts without any recorded act.** — **baseline will change (T10-16 (b))**
1. *Observation:* editing `governed-registers.yaml` (or the vocabulary configuration) changes C's verdicts (for example INCONCLUSIVE ↔ PASS). C states the list is "configuration, NOT a ruling (G-1 is open)", and "adding a register is a configuration edit". In T:
   - R0/PL-0 leave the owner of the record's kind list open (T10-16), today standing's schema;
   - R12/K3 records that unrecorded inputs change S's answers without a new entry.
2. *Evidence:* T 10.04:52, 64; 10.05:58–68, 232–234. C: `GovernedRegisterMap.php:14–28`; `identifier-check --series` output (part A).
3. *Class:* **unresolved**. C has no record, so the analogy is structural only.
4. *Confidence:* low.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. Under T10-16 (b), the comparison becomes "kind list owned by no area, changed only by an authorised act outside every area", set against "register list changed by file edit, no act recorded in C". It must be rerun as a new report when (b) is applied to Ch. 10.

**B-15 · D names six contexts where T admits one, and D's capabilities map onto T's areas.**
1. *Observation:* D's Metamodel refers to "the frozen six bounded contexts + two external domains", with Human Authority as the authoritative event source, and RA lists five capabilities. T admits one context (standing). The mappings, inferred:
   - RA Governance → candidate 1 (recording) and 6;
   - RA Evidence → 2 (RA's "produced, never asserted" ≈ I-9);
   - RA Qualification → 8;
   - RA Execution → 7;
   - MM's Human Authority → AU, which matches R1, AU → S, in direction;
   - MM's "Evidence … never becomes authoritative" ≈ CON-0231 (standing asserts nothing about correctness).
2. *Evidence:* MM §8; RA:26–30; T 10.03:317–322; 10.04:53; 10.02:79–130.
3. *Class:* **unresolved**. The reference products differ (B-02), and D's six contexts were not read (Phase-02 excluded).
4. *Confidence:* low for the six-against-one difference; medium for the direction match of R1.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. D is DRAFT or CANDIDATE. Part A found that D predates and does not describe C.

*(B-07 and B-09 are not findings. They are agreements, recorded in §3 and §5: assessment output confers no authority, and display is unaltered with a mandatory NOT-CHECKED statement. The numbering is kept so that cross-references stay stable.)*

## 7. Summary for the Principal

- **No difference is a code deviation or a defect**, because every T element is S0.
- **The core of T, standing, has no counterpart in C** (B-01, high). Every other mapping is to supporting, generic or non-context areas, or to nothing.
- **Theory gaps where C already acts:**
  - C enforces "absence is never positive" and "evidence required" (B-04, B-05), where T names no enforcer (G1);
  - C has receiving-side translators where T names none (B-10, T10-14);
  - C carries an assessment vocabulary where W's language is unstated (B-08);
  - C has a catalogue → assessment edge that T's map lacks (B-11).
- **Shared open item:** producer authorisation is unsolved in both T and C (B-06).
- **Unresolved:**
  - identity checking without minting (B-03);
  - Cohesion's scope (B-12);
  - vocabulary integrity (B-13);
  - configuration as unrecorded input (B-14, **baseline will change (T10-16 (b))**);
  - D's six contexts (B-15);
  - the reference-product frame (B-02).
- **Rerun needed when T10-16 (b) is applied:** B-14, the PL-0 row of §5, and the R0 row of §4.
- **Question for the Principal (from B-01):** should the class table gain a class for "a theory element with no code"?
