# D against C: the reference architecture and the code (Verification Auditor, part A)

Mandate: `03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`, revision 1, approved 2026-10-29, §3 item 2. This report compares **D with C only**. It makes no statement about a strategic design (T), and it uses none of the §4b classes. Those classes are defined against the theory, so they belong to part B. Each comparison sits under one of the three headings that §3 asks for:
- the code follows D;
- the code deviates from D;
- D says nothing.

## Baseline (mandate §4a)

| Item | Content |
|---|---|
| Audit date | 2026-10-29 |
| Run | **new audit** |
| C | commit `45c310312f1f37abd3863c3c814fda26143dea32`. The paths are the same as in `INVENTORY.md` (library `a72528b29657170fecc416c34dc61b899ba345d1`; entry points `cb97d70d1…`, `7f04e2205…`; `tests/Unit/Cohesion/`) |
| D | Reference Architecture `c861e5d1f6c9aa24ce69dae58311511e5c5660eb` (sha256 `08dfea99…7d84`). Decision Model `c8906442478dbee2bf72d2b9ba64765f885a91e3` (sha256 `c19339d3…d9a5`). Knowledge Metamodel `3bdcc3dba5542af9e046d9ef4c2651a8954b6f04` (sha256 `a5c88e9b…2b44`). ADRs used: **none**. The one ADR file D cites (`ADR-AIP-LOG-Platform-Rulings.md`, `8388ce91…`) was not read. Full hashes are in `INVENTORY.md` |
| T | **none; part A** |
| Tests | the same four commands and results as `INVENTORY.md`: 252/603 pass, exit 0; 187/509 pass, exit 0; `identifier-check --series` exit 0; `knowledge-lint --profile=structural --root=engineering/architecture/reference` exit 0 |
| Scope | the three files of D, set against C as inventoried. **Exclusions:** the same as `INVENTORY.md` (a)–(d). In addition, nothing D cites outside `reference/` was used, so any statement that depends on rulings, ES standards, the Phase-02 domain model or the capability registry is marked **open** |
| Other | Abbreviations: RA = Reference Architecture, DM = Decision Model, MM = Knowledge Metamodel; line numbers refer to the D commits above |

Labels: **verified**, **reported**, **inferred**, **open**, as in `INVENTORY.md`. Each comparison also carries a **confidence** (high, medium or low) in the sense of §4b item 4, and the status *awaiting the Principal's decision*.

## 0. A structural fact that frames every comparison

**D-0.** Every D document was last changed (2026-07-10 to 2026-07-28) before the first commit of C (2026-08-04). D never names the library, its entry points, PHP, or any `CAP-0nn` identifier (whole-word search). — **verified**, high.

Consequence (**inferred**, high): D cannot have been written to describe C. Most comparisons below therefore fall under "D says nothing". The few points of contact are general statements in D about automation, qualification and deferred concepts.

## 1. Where the code follows D

| # | D says | C shows | Label · confidence |
|---|---|---|---|
| F-1 | Automation stance: "machine verification lives in qualification instruments (periodic), not resident daemons" (DM:131). The RA lists "Rejected: Autonomous continuation" (§5) | Every entry point is a CLI that a person or a calling process runs. No scheduler, cron, daemon or loop construct appears in the library or the entry points. The application services state "this capability CANNOT SELF-ISSUE. It is invoked; it never schedules itself" (for example `ValidateIdentifier.php` docblock) | absence of scheduling: **verified**, high. That the docblock rule is the *same* rule as DM:131: **inferred**, medium |
| F-2 | "the AI evaluates and recommends — authority remains with governance" (DM:131). "Final authority is human" (RA §3) | `Verdict` declares 7 values, but only PASS, FAIL, WARN and INCONCLUSIVE are emittable. PASS AFTER CORRECTION, EMERGENT and CERTIFIED are reserved for "review and certification acts, which a validator does not perform" (`Verdict.php:14-38`). The handoff report "never says APPROVED, REJECTED or ACCEPTED" (`AssuranceHandoffReport.php` docblock) | emittable set: **verified** in code. The handoff wording: **reported** (docblock; covered by `Shared/Tests`, whose assertions were not read one by one). Correspondence to DM:131: **inferred**, medium |
| F-3 | "Governance precedes automation. Automation may implement governance. Automation never defines governance" (RA:18) | Each policy class says it "CREATES no authority and OWNS no rule … this is its engineering expression" (`IdentifierPolicy`, `ReferencePolicy`, `VocabularyPolicy` docblocks). `GovernedRegisterMap` "OWNS NO POLICY. It LOADS configuration" | the code's claim: **reported**. Whether the rule text actually lives elsewhere and the code only expresses it: **open** (needs the authority documents, inventory O-2) |
| F-4 | Qualification: "never fix in-run" (RA §2, line 29) | The library's production code writes no file; readers are read-only by construction (verified, `INVENTORY.md` §3). `identifier-check` "PREVENTS; it never REPAIRS" | no file writes: **verified**, high. Whether these tools are "qualification" in RA's sense: **open** (see S-2) |

## 2. Where the code deviates from D, or appears to

None of these is presented as an established defect. Each is an observation whose meaning depends on an interpretation, stated in the entry.

| # | Observation | Evidence | Interpretation it depends on | Label · confidence |
|---|---|---|---|---|
| V-1 | RA lists **"Code metrics"** as *deferred* (trigger: operational need). C contains a code-metric computation, **LCOM4** (Cohesion) | RA:89; `Cohesion/Domain/Lcom4.php`; 187 passing tests in `tests/Unit/Cohesion/` | That "code metrics" in RA covers a metric computed by tooling at all, rather than only metrics persisted as platform records. Also, whether a later decision (after 2026-07-10) lifted the deferral; D does not record one | observation **verified**. Deviation **unresolved**, low: Cohesion has no production entry point (inventory §4), so it may be an experiment the deferral does not reach (**open**) |
| V-2 | MM says of its schema: **"No validator exists and none is proposed"**. C contains validators (`Validate*` application services) | MM:126; for example `ValidateIdentifier.php`, `ValidateVocabularyIntegrity.php` | MM:126 is about validating the *metamodel schema* (KnowledgeObject id, kind, type, authority…). C's validators check *document structure* (identifiers, references, vocabulary, tables) and production library code reads none of those schema attributes. This was **verified** by grep for `canonical_home`, `knowledge_id`, `serialized_in`, `authority`, `owner`, `kind`, `Normative`, `Descriptive` and `frontmatter`: the only hits are prose, disposition phrases, and Cohesion's own fact-kind keys. On this reading there is no conflict | **inferred**, medium: *not* a deviation. Recorded so that part B does not mistake the two kinds of validator for one |
| V-3 | RA lists **"Machine-readable artifacts"** as *deferred* (trigger: a machine consumer exists). C reads YAML configuration (`governed-registers.yaml`, the vocabulary configuration) and the entry points offer `--json` output | RA:87; `GovernedRegisterMap.php:28`; `YamlVocabularySource.php`; `knowledge-lint.php:14,575` | Whether a configuration file read by a tool, or a JSON report, is a "machine-readable artifact" in RA's sense. Arguably the tool itself is the "machine consumer" that RA's trigger names | **unresolved**, low |
| V-4 | RA names a capability **"Engineering Knowledge"** whose job is "harvest external knowledge into pattern cards", implemented in `knowledge/patterns/`. C's namespace is also called **`EngineeringKnowledge`**, but its job is validating document structure and analysing code cohesion | RA:26; `composer.json` autoload; inventory §2 | That the shared name was meant to denote the same thing. Nothing in D or C links them | the names and the two stated responsibilities: **verified**. A naming collision between D and C: **inferred**, high. Its significance: **open** |
| V-5 | MM's object catalog requires, for `Capability · Component · Runtime Asset`, the ids `CAP-nn · CMP-nnn · AST-nnn` and says "five-question trace mandatory". MM §5 states that each CAP is realised through exactly one component. C names its modules `CAP-001…004` but carries no `CMP-` or `AST-` id and no five-question trace | MM:67 and the §5 `realizes` / `traces-to` rows; grep over C (0 hits for `CMP-`, `AST-`) | That C's `CAP-0nn` belong to the MM's `CAP-nn` series (inventory O-1). MM is CANDIDATE — NOT ADOPTED (MM:4), so it may not bind | the absence of ids: **verified**. Deviation **unresolved**, low |

## 3. Where D says nothing

| # | Subject in C | What D says | Label |
|---|---|---|---|
| S-1 | **Software layering.** Domain → Application → Infrastructure, application-owned ports, and the rule that Shared never depends on a capability. C obeys the rules it states itself: no inward-to-outward edge, no Shared → capability edge (inventory §3) | Nothing. RA describes "an engineering governance architecture — not a software system" (RA:14) | silence **verified**; code conformance to its own rules **verified** |
| S-2 | **Where the tools sit among RA's five capabilities.** C checks documents (S1–S5) and code (LCOM4). RA's candidates would be Qualification ("verify implementation against specification") or Evidence; RA maps both to directories under `verification/`, not to tools | No capability lists tooling as its current implementation (RA §2) | silence **verified**; the placement is **open** |
| S-3 | **Bounded-context ownership of C.** CAP-001 calls itself "Not a bounded context — this is a capability (AD-1 §6.2)" (README:62) | MM §8 refers to "the frozen six bounded contexts + two external domains (Phase-02)" and says every knowledge object lives in one of them, but does not cover tools. Phase-02 was not read | **open** |
| S-4 | **Dependencies between capabilities.** ReferenceIntegrity reuses IdentifierIntegrity's `DocumentSectionSequence` and `MarkdownDocumentContentsReader` | Nothing | silence **verified** |
| S-5 | **The verdict vocabulary** (7 values, 4 emittable) and the fail-closed rule "absence of evidence is not PASS" | RA mentions only "PASS AFTER CORRECTION" (RA §2, §5), as a qualification outcome. D defines no verdict vocabulary | silence **verified**. The overlap on one term: **verified** |
| S-6 | **Cross-language analysis** (the PHP binding; the Python binding through `python3`) | Nothing | silence **verified** |
| S-7 | **Placement of reusable code.** RA §6 states a placement litmus: "adoptable-unchanged by another project → `engineering/`; needs project context → the project". C lives under `scripts/lib/` (the project), and its readers are "pinned to the corpus" of this repository (for example `MarkdownTableReader.php` docblock) | The litmus is stated for artifacts. D does not say whether it applies to code | applicability **open**; facts **verified** |

## 4. Summary for the Principal

- **Verified, high:** D predates C and does not describe it. The code follows the layering rules it states itself. No production library code writes files or schedules itself. Tests pass: 252 and 187.
- **Follows D (inferred, medium):** the automation stance (F-1) and the non-authority of verdicts (F-2).
- **Possible deviations, unresolved:** V-1 (code metrics deferred / LCOM4 present), V-3 (machine-readable artifacts) and V-5 (CAP/CMP/AST ids). Each depends on documents outside part-A scope or on how D's wording is read.
- **Not a deviation (inferred, medium):** V-2.
- **Naming collision:** V-4.
- **Status of every item:** *awaiting the Principal's decision*. This report recommends no change.
