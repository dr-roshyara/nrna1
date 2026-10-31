# Part B, rerun 2: the strategic design at `244a2af9a` set against the EKS code (Verification Auditor)

Mandate: `03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`, **revision 3** (sha256 `0ace7ce8a11344d78a1db6ff432dd475712627cab525790937ad3fefaa5d4e61`, checked at the start of this run), §4, §4a, §4b. Authorisation: implementation-track decision record, entry **I-011** (2026-10-31T12:43:15Z, commit `81323760d`), which amends I-004's timing. Commission: `review-implementation/COMMISSION-PART-B-RERUN-2.md`, approved in I-011 at sha256 `b76e769f0f84e622610d300eabbb4a66e15551e3c418e36434abc2b1f455d01e` (checked at the start of this run). This report recommends nothing and authorises no change. The earlier reports are **not edited**.

## Baseline (mandate §4a)

| Item | Content |
|---|---|
| Audit date | 2026-10-31 |
| Run | **full rerun** of `PART-B-REPORT.md` (sha256 `7ced165de4ab388ce009b1c271290cf36eacfc81de8df2ed049672f24e49bfed`, matches `SHA256-PART-B.txt`) and `PART-B-RERUN-1.md` (sha256 `a96359e2d728e3e9c505ddad31266c2bc66f65f6157158298bf4d97e5f4548f8`, matches `SHA256-PART-B-RERUN-1.txt`). **Reason:** T changed. Ch. 10 was reviewed and corrected at the consolidation S0226–S0252 (stage 2, `c5669e8c0`), then changed by T9-19 (`a8ff1f4de`), T10-21 (`c6366b303`) and the window settlement `244a2af9a` (the commission). The last T baselines were `dca72c57f` (`PART-B-REPORT.md`) and `8e4b1a420` (`PART-B-RERUN-1.md`). **Every comparison below is made at `244a2af9a` only**; no earlier finding is updated in place |
| C | repository commit `81323760d8d3d8a3625f0cd33598ebf7935ce7cb` (HEAD of the shared checkout at the run). `git diff --stat f7504c6c9 HEAD` is **empty** for `scripts/lib/EngineeringKnowledge/`, `scripts/identifier-check.php`, `scripts/knowledge-lint.php`, `scripts/link-check.php`, `tests/Unit/Cohesion/`, `composer.json`, `phpunit.xml`, and none has an uncommitted change. **C is unchanged since `f7504c6c9`** (library last changed at `a72528b29657170fecc416c34dc61b899ba345d1`, 2026-09-30). 84 production and 36 test PHP files in the library. The configuration C loads, `docs/knowledge/schema/governed-registers.yaml` (sha256 `58c5d0252ecf90cfd4d6745419afb108af9f340cfad2e8a045847841d6a72daa`), is also unchanged since `f7504c6c9` (last changed at `d61bf5e84`) |
| D | **unchanged** since `f7504c6c9` (`git diff` empty for `engineering/architecture/reference/`). Reference Architecture `c861e5d1f` (sha256 `08dfea9949a0ef7e75aab73839eda9f14131d3f054025d95a2919a73c97a7d84`); Decision Model `c89064424` (`c19339d3d570aae3ad86cae3a86c74c2542dedebcb8a323df0b5a55ed117d9a5`); Knowledge Metamodel `3bdcc3dba` (`a5c88e9bdbc5a40fe38304fc728508340a69b53788f42adac90c20996b9c2b44`). D was not re-read: unchanged, its earlier mappings are reused only where cited. ADRs used: none |
| T | `244a2af9aa26322b2cea2d5224bb883fce77a6ef` (2026-10-31 08:48 +0100), the last commit to touch Ch. 10; no later commit touches it. Copies read with `git show 244a2af9a:docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/chapter_10/<file>`, blob and sha256: `10.00-index.md` `8046d645` `52de8d2468c21b3d915e9fc8ed8bfbd1d4185b8606736ceab4c78232b460a044`; `10.01-subdomains.md` `7477aff4` `607b5acba77c8a29113eb74707c2cae677eb27e71e14426a619e38eed3845602`; `10.02-bounded-contexts.md` `c923fd85` `09ccbf7d27a8eba7bf7f154dc5c991fd4abd6a52b00ba519ad5a3deb76a9c373`; `10.03-ownership-of-invariants.md` `1b2353a6` `c0ae226bc45ed340dcab0a847bbd7f1808687114c420bb907ab5efaca4bb83cb`; `10.04-context-map.md` `44f11fca` `bd32caabdc021cf343996305cdb053376493066aa8de5c7ff59eead2dafd1b84`; `10.05-published-language.md` `e11ac01d` `7cd50e674517fdd6a8b0eb3bc9105fef63d42da01671143c59577ed04e24c5a7`; `10.99-change-log.md` `85191afb` `b06f0aeae867cf3a4fe2ce87977d7a3665179f3ac7ec94a96a6679270c44e56c`; `TODO.md` `ad635387` `b9356255fcc97541b4321cf4b5fb9960656f33d1776d1430a32805060f75e77c`. Nothing else of the book was read; §9.13–§9.18, which Ch. 10 cites, were **not** read. **Every T element is S0** |
| Open in T (commission) | **T10-17** (owner assignments to be re-tested against R-a and the "information to reject" test; TODO L22), **T10-18** (the "own language" column; L23), **T10-19** (the applications' own rules not inventoried; L24), **T10-20** (the class of the rule that derives the evidence classes; L25). Each is named where it bears on a comparison. I-011: a partial rerun follows their Ch. 10 revision |
| Tests | `vendor/bin/phpunit --testsuite EngineeringKnowledge --do-not-cache-result --colors=never` → `OK (252 tests, 603 assertions)`, exit 0. `vendor/bin/phpunit tests/Unit/Cohesion --do-not-cache-result --colors=never` → `OK (187 tests, 509 assertions)`, exit 0. `php scripts/identifier-check.php --series` → exit 0; series R and PMR governed, "configuration, NOT a ruling (G-1 is open)", every other series INCONCLUSIVE. PHP 8.5.11, PHPUnit 11.5.6. All three run in the shared checkout at `81323760d`. `git status --short` after the runs is identical to the session start: **the tests changed no tracked file** |
| Scope | T (eight copies) against C (library, entry points, the configuration it loads) and, through the earlier mappings, D. Every finding of `PART-B-REPORT.md` and `PART-B-RERUN-1.md` re-examined (§6); the four comparisons the commission names (§2–§5). **Exclusions:** (a) everything under `docs/knowledgeos/` except the mandate, the two decision records, the commission, the earlier reports in this folder and the eight T copies; (b) §9.x text that Ch. 10 cites, including §9.18 (the evidence kinds' fields and rule D_W, and g1, are known only as Ch. 10 states them); (c) ADRs and other `engineering/` documents; (d) test bodies; (e) any implementation outside C |
| Other | **Fixture-reading test, disclosed (mandate §2):** the EngineeringKnowledge suite runs `Tests/BackTest/Phase0BackTest.php`, which reads `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` through `git show`; the auditor did not view its content. **Where this file was written:** the session's guard refused writes to the shared checkout, so the file was written in a git worktree (`.claude/worktrees/auditor-part-b-rerun-2/`, branch `worktree-auditor-part-b-rerun-2`, based on `e698cfdbb`), uncommitted. It is meant for `review-implementation/` of the shared checkout; its content was produced from the shared checkout at `81323760d` and does not depend on the worktree's tree. Line references to T are to the copies above ("10.03:145" = file 10.03, line 145). Rows of §10.1 are "row n"; candidates of §10.2 "candidate n" |

**Labels.** Statements are **verified** (checked in code, by search, or by running), **reported** (from a document, not checked), **inferred**, or **open**. Each finding has the six parts of §4b, in order. All of Ch. 10 is S0, so **no difference is a code deviation or a defect**. Each status is ***awaiting the Principal's decision***.

## 1. What changed in T since the earlier baselines (reported from the copies)

- **Candidate 2, well-foundedness, is admitted** (S0), "derived by the Professor, S0, for review", under (p2)/(p5), stipulated: its kinds **evid, ewdr, erst** have an acceptance rule of its own, D_W (10.02:5; 10.03:289, 319). Gap **G1 is "closed by adoption"** (10.02:214). The Principal's decision T9-19 adopted all three kinds (research record, addendum 2026-10-31T12:20:16Z).
- **W's language is "not yet written"** (10.05:28, 291).
- **The evidence classes** are refuted, **contested** (T9-17 (b)), supported by recorded evidence, not attested, and **U with the classes it could resolve to** (10.01:45; 10.02:195–199; 10.04:57). Refuted now needs no live support (10.02:195).
- **An evidence entry** has its producer, cited bases, object, polarity, scope and act time, and no source-kind field (10.02:220, G7).
- **T9-12 (c):** every declared producer's evidence is accepted and shown with its producer and authorisation status, and only authorised producers count. **T9-16 (b):** withdrawals and restorations count only from holders of a grant over the evidence's scope (10.05:291; 10.02:195, 205).
- **T10-21's names** (10.05:33, 139, 174; 10.03:73): Q1's F is shown as **"not recognized as current authority", never "refuted"**. The kernel's seal is **"accepted by the kernel"**. §10.3's ownership status is **"disputed ownership"**. The evidence classes keep refuted / contested / not attested / supported.
- **Unchanged in substance since `8e4b1a420`:** the record element and §10.3.7 (10.03:326–344); R0, R0′ (10.04:52–53); standing's eight kinds (10.03:339, 342).
- **Statements in T that are not yet aligned with the adoption** (reported; they bear on §2 and §3 and are named in limitations; the auditor judges no theory):
  - PL-1 still lists "the ten admitted kinds" (10.05:103). The §10.2.3 G1 row still gives D's kinds as the ten (10.02:214).
  - PL-0's book history of added kinds names consumption, transfer and designation only (10.05:75).
  - §10.3.3 row B still reads "no record support (G1)", with enforcers "none established (G1)" (10.03:108–118).
  - §10.4 still counts 16 of 23 rows as conditional. The rows conditional on G1 are "to be re-marked at the next review" (10.04:6, 126, 132).

## 2. Candidate 2 (now admitted) against the code

| T element (at `244a2af9a`) | C | Label |
|---|---|---|
| kinds evid, ewdr, erst in the one designated record; acceptance rule D_W (10.02:5; 10.03:289) | **none.** C has no record, no entry kinds and no append. In production code the words polarity, refut*, producer, restor*, "not attested", "accepted by", supported, tombstone, annul and append occur **0** times. `evid`/`ewdr`/`erst` occur as words **0** times (as substrings only inside "evidence" and "PHP-specific understanding"). Every "kind" hit is a code-unit or token kind in Cohesion (`UnitKind`, `QualifierKind`, `PhpFactExtractor`) or in `Identifier.php`/`link-check.php` | **verified** (search, §9) |
| the entry's fields: producer, cited bases, object, polarity, scope, act time (10.02:220) | `Assessment(verdict, evidence: string, subject: ?string)` (`Shared/Domain/Assessment.php:29–33`). Per report, `HandoffContext` carries target, checker name and version, source commit, generated-at time and command line (`HandoffContext.php:21–28`). Nearest analogues: object ≈ `subject`/`target`; producer ≈ the checker's identity (code provenance, "never AI-process attestation", `HandoffContext.php:15–17`); act time ≈ `generatedAt`. **No polarity, no cited bases, no scope, no authorisation** | C: **verified**. The analogues: **inferred**, low |
| the derivation of the class, rule g1 (10.02:199; class pending T10-20) | none. C's report has a fail-closed **aggregate** over the slices: FAIL > INCONCLUSIVE > WARN > PASS (`AssuranceHandoffReport.php:111–140`) | **verified**; the comparison is R2-02 |
| acceptance of evidence with refusal reasons (O2; gap G8) | none: C accepts no entry. `Assessment::of` refuses a non-emittable verdict and an empty evidence string (`Assessment.php:38–47`); that is a constructor guard on an output, not acceptance into a record | **verified**; **inferred** that it is no counterpart |
| withdrawal and restoration (ewdr, erst; T9-16 (b)) | none. "withdrawn" occurs only as a **disposition-label marker** in document wording (`MarkdownDispositionReader.php:23, 61`, `SUPERSEDED_MARKERS`) | **verified**; a **homonym**, recorded to prevent a mapping error |

Findings: R2-01, R2-02, R2-03, R2-04.

## 3. R1-01 (the kind list changed by editing a configuration file) against the new T

- **T (reported):** the record and its kind list are owned by no area, changed only by an authorised act outside every area (10.04:52; 10.05:77). At this baseline T records a **further instance** of such an act: the evidence kinds were adopted by the Principal's decision (10.02:5, 214; T9-19). PL-0's history line (10.05:75) does not yet list it (§1).
- **C (verified):** unchanged. The governed-register list is configuration loaded by a class that "OWNS NO POLICY", and "Adding a register is a CONFIGURATION EDIT, not a code change" (`GovernedRegisterMap.php:13–21`; `IdentifierIntegrity/README.md:74`). The configuration is unchanged since `f7504c6c9`. `--series` output: "configuration, NOT a ruling (G-1 is open)". No module requires or records an act for an edit.

Finding: R2-06.

## 4. The conditional relationships of §10.4, each under each branch

*At `244a2af9a`, 16 rows carry ◇ (10.04:132). For the rows conditional on G1, the branches are **(i) as marked**: G1 open, W with no kinds; and **(ii) as adopted**: G1 closed by adoption, W admitted with evid, ewdr, erst (§1). Branch (ii) is what §10.2 and §10.3.6 now state; the re-marking is pending (10.04:6). The other branches: G11 (N without / with kinds of its own), G10 (C doubtful / with kinds), G4, the source of R12's inputs, and R-a for R21. "None" means that C has no participant at either end.*

| Row | Branch | C counterpart | Result |
|---|---|---|---|
| **R4** W → D (display rule) | (i) | nothing to show | — |
| | (ii) | C's reporters show **`Verdict`** unaltered (`StructuralCliReporter.php:110`, `CliOutputFormatter.php:48`), not W's five-way class | agreement **in form only** (unaltered display), inferred, low; the vocabulary difference is R2-02 |
| **R5** S → W (producer authorisation) | (i), (ii); G4 open | none (no S; C has no producer check) | R2-04 |
| **R6** W → S (D holds W's kinds to D_W) | (i), (ii) | none (no S, no record, no D) | R2-01 (W side); R1-04 (S side) |
| **R7** W → A (A writes under W's rule) | (i) | the language half matches in **direction**: Shared/Domain (`Verdict`, `Assessment`) upstream of every capability (`INVENTORY.md` §3) | agreement in direction, inferred, medium (as `PART-B-REPORT.md` §4) |
| | (ii) | A would **append** evidence entries into the one record under D_W. C's assessments are **returned and printed**; nothing is appended anywhere | R2-05 |
| **R9** N → WM | G11 either way | none (no N, no WM) | no counterpart |
| **R10** S → N (shared CON-0063) | G11 either way | none | no counterpart |
| **R11** N → W, A (criteria) | G11 open (N as content) | C's checks **cite criteria owned elsewhere** and use them: "AP-7/DR-4: criteria are used here, owned elsewhere" (`GovernedRegisterMap.php:24–25`); the disposition markers are "pinned to the corpus" §0.4.4 and DP-3 (`MarkdownDispositionReader.php:18–23, 59–62`; `AssessesVocabularyIntegrity.php:74`) | agreement in **direction** (criteria → assessment), inferred, low. T's row rests on S0196 L537 "on reviewer A's report, not re-read" (10.04:64). Not a finding |
| | G11 closed (N with kinds) | none: C reads no entries; its criteria are encoded in code and configuration | no counterpart; the change path of the criteria is the R2-06 pattern |
| **R12** (source open) → S | either | no S. C's own analogue: verdicts change with unrecorded configuration | `PART-B-REPORT.md` B-14, R12 half: **still holds** (§6) |
| **R13** C → W | (i), (ii); C doubtful | none. C's only edge out of its section register runs **to an assessment module** (ReferenceIntegrity → IdentifierIntegrity, `ReferenceIntegrityContents.php:7`, `MarkdownIntraDocumentReader.php:7`), not to W | B-11 **still holds** |
| **R14** S ↔ C | either | none (no S) | no counterpart |
| **R15** C → D | either | none relevant | no counterpart |
| **R16** N → D | either | none | no counterpart |
| **R17** consumer (delivery role) → U | (i) and the class half under (ii) | C's report hands verdicts and the mandatory NOT-CHECKED statement to the user. "it never says APPROVED, REJECTED or ACCEPTED … Automation produces assurance evidence — never a decision" (`AssuranceHandoffReport.php:20–22, 27–33, 76`) | agreement **in form** (the consumer decides nothing for U), inferred, low. Under (ii) the class half is not W's class (R2-02) |
| **R18** P → C, N | G10/G11 either way; T10-14 open | receiving-side ports and adapters (`Cohesion/Application/Ports/SemanticFactProvider.php:10–19`; the `*Reader` ports) | B-10 **still holds** |
| **R20** S, N, W → M | either | none | no counterpart |
| **R21** AU → N, W (amending acts) | G11, G1 either way; R-a not applicable | none. Changes to C's criteria and configuration are code or file edits; no act is recorded | no counterpart; the change path is the R2-06 pattern |

*Unconditional rows R0, R0′, R1, R2, R3, R8, R19:* **no change** against `PART-B-RERUN-1.md` (R0, R0′) and `PART-B-REPORT.md` §4 (the rest). C has no participant (R1-02, R1-03, R1-04).

## 5. The published language (§10.5, with T10-21's names) against C's interfaces

| T item | C interface | Result |
|---|---|---|
| PL-0, PL-3, PL-4 (the record's language); the `remove` and `designate` halves of PL-1, PL-2 and PL-5 | none | R1-02, R1-03 **still hold** |
| PL-1 (standing's eight kinds), PL-2 (its guards), PL-5 (grant owner) | none | R1-04 **still holds** |
| PL-6, Q1's **F shown as "not recognized as current authority", never "refuted"** (10.05:174) | `Verdict::FAIL`, a positive defect finding (`Verdict.php:22`; e.g. `AssessesVocabularyIntegrity.php:72`) | **no counterpart**: C answers no Q1. T10-21 now publishes the separation that `PART-B-REPORT.md` B-08 drew as a caution: C's FAIL must not be read as Q1's F. If C's FAIL is set against anything in T, it is W's "refuted", not Q1's F (R2-02). Inferred, medium |
| PL-2, the kernel's seal **"accepted by the kernel"** (10.05:139) | none: C accepts nothing. C's report vocabulary **excludes** "ACCEPTED" as governance vocabulary (`AssuranceHandoffReport.php:20–22`). C's "attestation" means **authorship attestation**, stated as absent (`HandoffContext.php:15–17`; `knowledge-lint.php:207–208`) | no counterpart. Two **homonyms**, recorded to prevent a mapping error: C's "attestation" ≠ T's former "attested by the kernel" ≠ W's "not attested" |
| §10.3's **"disputed ownership"** (10.03:73) | none: C has no ownership status | no counterpart |
| PL-7 to PL-11 | none | as `PART-B-REPORT.md` §5: **still holds** |
| PL-12 (c), PL-13 (what a T does not establish) | the NOT-CHECKED statement, "A PASS here is mechanical, not architectural, assurance" (`AssuranceHandoffReport.php:27–33`) | agreement **in form**, inferred, low (as before) |
| PL-14, the joint display (an assumption on consumers) | reporters show verdicts unaltered | agreement in form, low (as before) |
| §10.5.3 **W**: language "none yet / not yet written"; promise (b) requires refuted / contested / supported / not attested, each with its entries, and U with its candidates; the must-do of accepting evidence with refusal reasons (10.05:291) | `Verdict` (four emittable values) and `Assessment` | R2-02 (vocabulary), R2-01 (acceptance) |
| §10.5.6, "is it published?" | C's interfaces have consumers other than their producers (the three entry points; `Tests/Adapters`) | as before: verified for C; not a comparison |

## 6. Re-examination of every earlier finding at `244a2af9a`

*"Still holds" means the same observation, re-checked at the new T line numbers against unchanged C. The earlier text stays on its own baseline. "Changed" points to the new finding that states the comparison at `244a2af9a`. Status column: the Principal's earlier disposition (I-003, I-004), and **awaiting the Principal's decision** for this rerun's result.*

| Earlier finding | Result at `244a2af9a` | Evidence (new T lines; C unchanged) | Class · confidence | Earlier disposition | Bearing of T10-17–T10-20 |
|---|---|---|---|---|---|
| **B-01** standing has no counterpart (reclassified as R1-04) | **still holds**, through R1-04 | 10.03:339, 342; 10.05:61, 103–119 | theory element with no code · high | R1-04: confirms I-003 (I-004) | T10-17: row A's owner assignments provisional |
| **B-02** different reference products | **still holds** | 10.01:12 (stipulation 1 unchanged) | unresolved · medium | stays unresolved (I-003) | — |
| **B-03** identity checked, never minted | **still holds** | 10.03:145 (row E unchanged); candidate 5 not admitted (10.03:321). C: `AssessesIdentifierIntegrity.php:43–85`; README:66 "Run before minting" | unresolved · medium | stays unresolved (I-003) | T10-17 (owner 5 provisional) |
| **B-04** "absence never positive" enforced in C; T names no enforcer | **changed** → **R2-03** | W admitted (10.03:289); row B unchanged (10.03:114) | theory gap · medium | routed to the Professor for T9-17 (I-003); "neither used nor rejected" (research record, B-04/B-05 note) | T10-17; T10-20 |
| **B-05** evidence required in C; no enforcer for I-9 | **still holds** | 10.03:112 (row B: "none established (G1)", unchanged). T now names an entry's fields (10.02:220), which sharpens the limitation: C's non-empty string carries none of polarity, object, scope | theory gap · medium | as B-04 | T10-17 |
| **B-06** producer authorisation unsolved in T and C | **changed** → **R2-04** | T9-12 (c) decided (10.02:205; 10.05:291); row B CON-0150 still "none established", shared 1 and 2 (10.03:118) | see R2-04 | kept as a shared open question (I-003) | T10-17 |
| **B-08** C's assessment language vs W's unstated language | **changed** → **R2-02** (vocabulary) and **R2-05** (R7 under the adopted branch) | 10.05:28, 291; 10.04:60 | see R2-02, R2-05 | to the Ch. 10 open items (I-003) | T10-20 |
| **B-10** receiving-side translators; T names none | **still holds** | 10.04:71, 147, 152–156 (T10-14 open; option (b) "the book's own mechanism supports", neither adopted) | theory gap · medium | to the Ch. 10 open items (I-003) | — |
| **B-11** C → A dependency the map does not draw | **still holds** | 10.04:52–74 (no C → A row) | theory gap · medium | to the Ch. 10 open items (I-003) | — |
| **B-12** Cohesion's scope | **still holds** | 10.01:66 (row 13, "no book concept"); no P → A row; 10.03:159 (8 owns none) | unresolved · low | stays unresolved (I-003) | **T10-19**: the applications' own rules (8 included) are not inventoried, so whether Cohesion's rules would be candidate 8's is not decidable in T |
| **B-13** VocabularyIntegrity has no subdomain | **changed** → **R2-08** | W's class "contested" (10.02:196); I-4's W half (10.03:127) | see R2-08 | stays unresolved (I-003) | T10-19 |
| **B-14** configuration changes verdicts unrecorded | **still holds** for the R12/K3 half; the T10-16 half was superseded by R1-01 at rerun 1 and is re-examined as R2-06 | 10.04:65 (R12 ◇, source open, unchanged); 10.05:246–248 | unresolved · low | stays unresolved (I-003) | — |
| **B-15** D's six contexts vs T's admitted contexts | **changed** → **R2-07** | 10.03:318–322 (now two admitted) | see R2-07 | stays unresolved (I-003) | T10-18 |
| **R1-01** kind list vs register list | **changed** → **R2-06** | 10.02:5, 214; 10.05:75–77 | see R2-06 | unresolved; input for the Lead DDD Architect at stage 1 (I-004) | — |
| **R1-02** the record element has no counterpart | **still holds** | 10.01:70–73; 10.03:330, 343; 10.04:52; 10.05:56–96 | theory element with no code · high | confirms I-003 (I-004) | — |
| **R1-03** §10.3.7's invariants and R0′ have no counterpart | **still holds** | 10.03:334–338, 343; 10.04:53 | theory element with no code · high | confirms I-003 (I-004) | T10-17 ("re-cut, not re-derived", 10.03:344) |
| **R1-04** standing has no counterpart | **still holds** | as B-01 | theory element with no code · high | confirms I-003 (I-004) | T10-17 |

**Agreements re-examined (not findings; numbering B-07, B-09 kept):** "assessment output confers no authority" (law 4a, the roles law, 10.03:86, 101; `Verdict.php:14–17`, `Assessment.php:38–43`) **still holds**. "Display unaltered, with a mandatory NOT-CHECKED statement" **still holds**, at low confidence.

**No earlier finding "no longer applies".**

## 7. Findings of this rerun

**R2-01 · Candidate 2, now admitted, has no counterpart in C.**
1. *Observation:* At `244a2af9a`, well-foundedness is an admitted context (S0, for review). It is defined by three entry kinds of the one record (evid, ewdr, erst), whose acceptance rule D_W is its own, and by the derivation of four classes plus U from live entries. C contains no record and no entry kinds. It has no operation that appends, withdraws or restores, no polarity, no cited bases and no class derivation. The words that carry these concepts in T occur 0 times in C's production code (§2).
2. *Evidence:* T 10.02:5, 99–117, 195–199, 214, 220; 10.03:289, 319; 10.05:291. C: the searches of §9 (verified); `Assessment.php:27–50`; `PART-B-RERUN-1.md` §2 (no append, unchanged C).
3. *Class:* **theory element with no code**.
4. *Confidence:* high (the absence is shown directly).
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0, and the admission is "for review", stipulated under (p2)/(p5). D_W's content and rule g1 are in §9.18, which was not read: the comparison uses only what Ch. 10 states. Implementations outside C were not searched. The absence may be expected: I-003 records EKS as a supporting tool and the kernel as built new. T10-20 (the class of the derivation rule) is open.

**R2-02 · C's verdict vocabulary set against W's classes and T10-21's names (rerun of B-08, vocabulary half).**
1. *Observation:* C emits PASS, FAIL, WARN and INCONCLUSIVE. Promise (b) requires W to publish refuted, contested, supported by recorded evidence, not attested, and U with its candidates. Set side by side:
   - **(a) contested:** C has no value that shows a conflict unresolved. C's report aggregate **resolves** by precedence: one FAIL makes the aggregate FAIL whatever other slices say, and the precedence is position-independent. Under T9-17 (b), a live refutation with a live support is **contested**, not refuted (10.02:195–196). But C's slices are **different checks of different parts** (S1–S5), not support and refutation of one claim. The research record routes this exact shape to this re-check ("one code aggregate (FAIL if any part fails) has refutation-first's shape but combines checks of different parts").
   - **(b) not attested against U:** C has one value, INCONCLUSIVE, both for "could not be read" (`ValidateIdentifier.php:35–42`) and for "nothing to evaluate" or "not a governed register" (`AssessesIdentifierIntegrity.php:43–46`). T keeps U (undetermined inputs) apart from not attested (neither) (10.02:198–199; gap G9, 10.02:222).
   - **(c) WARN** has no counterpart in W's classes.
   - **(d) T10-21:** T now reserves "refuted" for the evidence class and shows Q1's F as "not recognized as current authority" (10.05:174). That is the separation `PART-B-REPORT.md` B-08 drew: C's FAIL is not Q1's F. If it corresponds to anything, it is W's "refuted".
2. *Evidence:* T 10.01:45; 10.02:195–199, 222; 10.05:174, 291. C: `Verdict.php:19–39`; `AssuranceHandoffReport.php:111–140`; `ValidateIdentifier.php:35–42`; `AssessesIdentifierIntegrity.php:43–46`. Research record, addendum on the B-04/B-05 note (decision record L652, read-only).
3. *Class:* **unresolved**. C's verdicts are about document structure, not a knowledge item's content, so matching them to W's classes is the auditor's analogy. If the slices are not readings of one claim, (a) is a **mapping error** and not a difference.
4. *Confidence:* high for the observations; low for the mapping.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. W's language is "not yet written" (10.05:28), so the comparison is against what promise (b) requires, not against a published language. T10-20 is open.

**R2-03 · rerun of B-04 · C enforces "absence is never a positive result"; T, with W now admitted, still names no enforcer in Ch. 10.**
1. *Observation:* C is unchanged: unreadable input or nothing to evaluate gives INCONCLUSIVE, never PASS, and an empty check set is refused. In T the one-sidedness invariant is still owned by 2 with enforcer **"none established (G1)"** (row B, unchanged). T now records W as admitted with an acceptance rule of its own (D_W) and G1 as closed by adoption. **Changed against B-04:** T's ground for "no enforcer" was the missing evidence kinds (G1); those kinds now exist in T, but row B has not been re-derived. Whether D_W enforces one-sidedness is stated, if anywhere, in §9.18 (not read).
2. *Evidence:* T 10.03:108–118 (row B), 289, 319; 10.02:214, 219 (G6, completeness, still open). C: `ValidateIdentifier.php:35–42`; `AssuranceHandoffReport.php:65–67, 111–140`.
3. *Class:* **theory gap** (Ch. 10 does not yet name the enforcer).
4. *Confidence:* medium: the observation is high; mapping PASS to "supported" is the auditor's interpretation, as in B-04.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. §10.3.3 row B has not been re-marked since the adoption (§1). T10-17 (the owner assignments) bears on it.

**R2-04 · rerun of B-06 · T has decided which producers count; C has no producer.**
1. *Observation:* B-06 found producer authorisation unsolved in both T and C. At `244a2af9a`, T has a decided rule (T9-12 (c)). Every declared producer's evidence is accepted and shown with its producer and authorisation status, but only authorised producers count. Liveness requires an authorised producer, and withdrawals count only from holders of a grant over the evidence's scope (T9-16 (b)). C has no producer on `Assessment`. Its report context records the **checker's** code provenance and states that nothing asserts who produced the artifact ("EKS-07 stays unsolved").
2. *Evidence:* T 10.02:195, 205; 10.05:291; 10.03:118 (CON-0150, enforcer "none established", shared 1 and 2, G4); 10.04:58 (R5 ◇ G1, G4). C: `Assessment.php:29–33`; `HandoffContext.php:9–28`; `knowledge-lint.php:207–208`.
3. *Class:* **theory element with no code**, for the decided counting and display rule. The enforcer and the authorising grant (G4) remain undefined in Ch. 10, the theory-gap part B-06 recorded.
4. *Confidence:* high for the observation; medium for the class (one could read the checker's identity as a "declared producer"; C itself says it is not authorship).
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. The rule's text is in §9.18 (not read). T10-17 bears on the owner (shared 1 and 2).

**R2-05 · rerun of B-08, R7 half · Under the adopted branch, assessment methods write evidence entries into the record; C writes nothing.**
1. *Observation:* R7 (W → A) is conditional on G1. `PART-B-REPORT.md` B-08 left it **unresolved under the second branch** (G1 closed with W's kinds in the one record). At `244a2af9a` that branch is the one T states as adopted. Under it, an assessment method supplies evidence entries in W's language and decides no class. C's assessments are returned to the caller and printed, and no entry is appended to any record. The direction under branch (i) (Shared upstream of every capability) still matches.
2. *Evidence:* T 10.04:60, 126; 10.02:169–171 (candidate 8 "write evidence entries into candidate 2"); 10.02:214. C: `Assessment.php`; `AssuranceHandoffReport.php`; `StructuralCliReporter.php:110`; part A (production library code writes no file); `INVENTORY.md` §3.
3. *Class:* **theory element with no code** (the write path).
4. *Confidence:* high for the absence. The branch reading depends on the pending re-marking of R7 (10.04:6).
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. Candidate 8 is not a context (10.03:293); the row binds an application, so its absence in C may be expected.

**R2-06 · rerun of R1-01 · The kind list changes only by an authorised act, and T now shows a further instance; C's register list still changes by a file edit, unrecorded.**
1. *Observation:* As in R1-01, C's governed-register list (and its declared vocabulary) is configuration that no C module owns. Editing it changes verdicts, and C neither requires nor records an act. The configuration and C are unchanged. **Changed on T's side:** T's kind list changed at this baseline by a recorded decision of the Principal (the evidence kinds, T9-19), an instance of "changed only by an authorised act outside every area". On the *ownership* half the resemblance found in R1-01 stands (no owning module / no owning area). On the *change* half the difference stands: T's list changes by recorded act, C's by unrecorded edit.
2. *Evidence:* T 10.04:52; 10.05:72–79; 10.02:5, 214. C: `GovernedRegisterMap.php:13–25`; `IdentifierIntegrity/README.md:74`; `identifier-check --series` (this run); `git diff f7504c6c9 HEAD` empty for `docs/knowledge/schema/`.
3. *Class:* **unresolved**, as before: C has no record and no entry kinds, so the comparison is structural only.
4. *Confidence:* low.
5. *Status:* awaiting the Principal's decision. (R1-01's disposition, input for the Lead DDD Architect at stage 1, I-004, is the Principal's; this rerun does not alter it.)
6. *Limitations:* S0. Whether a register (a series of identifiers) is a "kind" at all is open. T's PL-0 history (10.05:75) and PL-1 (10.05:103) do not yet list the adopted kinds (§1).

**R2-07 · rerun of B-15 · D names six contexts; T now admits two.**
1. *Observation:* D (unchanged) refers to "the frozen six bounded contexts + two external domains". T at `244a2af9a` admits standing and well-foundedness (two, S0; the second "for review"). It previously admitted one. The mapping RA "Engineering Evidence" → candidate 2 (inferred in B-15) now maps to an **admitted** context. RA's "evidence is produced, never asserted" (RA:30) still resembles I-9.
2. *Evidence:* MM §8; RA:26–30 (as cited in `PART-B-REPORT.md` B-15, D unchanged); T 10.03:318–322; 10.02:5.
3. *Class:* **unresolved** (reference products differ, B-02; D's six contexts not read).
4. *Confidence:* low.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. T10-18 (evidence for the "own language" column) is open, so how many candidates have their own language is itself not settled in T.

**R2-08 · rerun of B-13 · VocabularyIntegrity still has no subdomain; its S5 now resembles W's "contested", and "withdrawn" is a homonym.**
1. *Observation:* No §10.1 row names vocabulary or term integrity (unchanged). C's S5 flags unlabelled competing current definitions as WARN and does not resolve them. That now resembles W's new class **contested** (conflict shown, not resolved, 10.02:196) and I-4's W half (10.03:127). C's "withdrawn" is a **disposition-label marker** in document wording, not a withdrawal of reliance (ewdr, CON-0214). Both apply to **wording inside a document**, not to evidence entries about items.
2. *Evidence:* T 10.01:54–68; 10.02:196, 203; 10.03:127. C: `AssessesCompetingCurrentDefinitions.php:143–148`; `MarkdownDispositionReader.php:22–23, 59–62`.
3. *Class:* **unresolved**: a theory gap (no row) or outside the theory's scope.
4. *Confidence:* low.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. T10-19 (the applications' own rules not inventoried) bears on whether such checks would be candidate 8's.

## 8. Summary for the Principal

- **C and D are unchanged** since `f7504c6c9`; the tests pass with the same counts as before. Every difference below comes from T's changes.
- **Re-examined, 17 earlier findings:** 11 **still hold** (B-01 through R1-04, B-02, B-03, B-05, B-10, B-11, B-12, B-14's R12 half, R1-02, R1-03, R1-04). 6 are **changed** (B-04, B-06, B-08, B-13, B-15, R1-01). **None no longer applies.**
- **New and rerun findings: 8.**
  - **theory element with no code (3):** R2-01, candidate 2 (high); R2-04, the decided producer rule; R2-05, R7's write path under the adopted branch.
  - **theory gap (1):** R2-03, no enforcer named for one-sidedness.
  - **unresolved (4):** R2-02, the verdicts against W's classes; R2-06, the kind list; R2-07, D's contexts; R2-08, vocabulary integrity.
  - **Code deviations, design deviations, estate defects, outside the theory's scope: 0.**
- **T10-21:** C answers no Q1. The new names make explicit the separation the first report drew (C's FAIL is not Q1's F), and C's "attestation" and "withdrawn" are homonyms.
- **Conditional rows:** of 16, three show agreements in form or direction (R4, R11, R17), three are carried by findings (R5, R6, R7), and the rest have no participant in C under either branch.
- **T at this baseline is not aligned throughout with the adoption** (PL-1's ten kinds, row B, the 16-of-23 count; §1). The comparisons used Ch. 10's later statements and name the earlier ones in limitations.

## 9. Files opened, commands run, and access beyond the mandate

**Files opened (read):**
- the mandate (`03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`, in full);
- `docs/knowledgeos/IMPLEMENTATION-TRACK-DECISION-RECORD.md` (in full);
- `03-methodology/PRINCIPAL-DECISION-RECORD-RULES-20261018.md` (lines 655–683 in full, and lines matching an audit search, read-only);
- in this folder: `COMMISSION-PART-B-RERUN-2.md`, `PART-B-REPORT.md`, `PART-B-RERUN-1.md` (in full), and the three `SHA256-*.txt` files;
- the eight T copies (above), extracted with `git show` to the session's job tmp folder;
- C, read in part: `Shared/Domain/Verdict.php`, `Assessment.php`, `AssuranceHandoffReport.php` (L20–140), `HandoffContext.php` (L1–40); `IdentifierIntegrity/Application/ValidateIdentifier.php` (L35–42); `IdentifierIntegrity/Domain/AssessesIdentifierIntegrity.php` (L43–85, excerpts); `IdentifierIntegrity/Infrastructure/GovernedRegisterMap.php` (L10–30); `IdentifierIntegrity/README.md` (L60–64 and matching lines); `VocabularyIntegrity/Infrastructure/MarkdownDispositionReader.php` (L15–30, L55–66); `VocabularyIntegrity/Domain/AssessesCompetingCurrentDefinitions.php` (L140–148); `VocabularyIntegrity/Domain/AssessesVocabularyIntegrity.php` (L68–74); `Cohesion/Application/Ports/SemanticFactProvider.php` (L8–20); `use` lines of `ReferenceIntegrityContents.php` and `MarkdownIntraDocumentReader.php`; matching lines from the searches below.

**Not opened:** D's three files (unchanged; hashes only); `INVENTORY.md` (cited from `PART-B-REPORT.md`); `docs/knowledge/schema/governed-registers.yaml` (hash and history only).

**Commands (read-only):**
- `sha256sum` (mandate, commission, earlier reports, T copies, D files, configuration);
- `git log`, `git show --stat 81323760d`, `git rev-parse`, `git worktree list`, `git ls-tree 244a2af9a <chapter_10>/`, `git show 244a2af9a:<chapter_10 file>` (eight files);
- `git diff --stat f7504c6c9 HEAD -- <C paths>`, `-- engineering/architecture/reference/`, `-- docs/knowledge/schema/`;
- `git status --short` (C paths, report folder, records; once repository-wide, see below);
- `find` and `ls` in the library and in `docs/knowledge/schema/`;
- `command grep` over explicit production-file lists of the library and the three entry points (word counts for polarity, refut, contest, withdraw, restor, producer, attest, "not recogni", "accepted by", supported, "not attested", evid, ewdr, erst, tombstone, annul, append, kind, warrant, "source kind", provenance; then the matching lines);
- the three test commands of the baseline;
- creating this worktree (`EnterWorktree`) and its report folder (`mkdir -p`), to write this file only.

**Access beyond the mandate, disclosed:**
1. `find . -name governed-registers.yaml` from the repository root walked every directory **by file name**, including `docs/knowledgeos/`. No file content was read, and no match lay there.
2. A repository-wide `git status --short` (after the tests) printed **file names** of uncommitted paths, two of them under `docs/knowledgeos/` (`…/tmp/corpus-reading/START-TIMES.tsv`, `…/chronological_knowelgeos_ablation_theory/analysis/theory/DERIVATION/`). Neither was opened.
3. The session's start hook injected repository memory files (`.claude/MEMORY.md` and others) into the context automatically. They are outside `docs/knowledgeos/` and were not used in any comparison.
4. **A write location outside the shared checkout's report folder**, forced by the session's guard (baseline, "Other"). Nothing else was written, and nothing was committed.

No other access.
