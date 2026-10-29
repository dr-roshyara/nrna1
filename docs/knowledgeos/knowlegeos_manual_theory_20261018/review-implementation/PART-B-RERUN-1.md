# Part B, rerun 1: the record element after T10-16 (b) (Verification Auditor)

Mandate: `03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`, **revision 2** (approved 2026-10-29; it adds the finding class "theory element with no code", §4b and §8), §4a (a rerun is a new report). This report recommends nothing and authorises no change. The original report is **not edited**.

## Baseline (mandate §4a)

| Item | Content |
|---|---|
| Audit date | 2026-10-29 |
| Run | **rerun** of `PART-B-REPORT.md`, sha256 `7ced165de4ab388ce009b1c271290cf36eacfc81de8df2ed049672f24e49bfed` (checked against `SHA256-PART-B.txt`: OK; the file was committed by others at `da0b6aea5`). **Reason:** the T baseline changed. T10-16 (b) (the record and its kind list are owned by no area) was applied to Ch. 10 at the G1 settlement, `8e4b1a420`. Mandate §4a: "every comparison it affects is rerun before its findings are treated as current" |
| Scope of the rerun | as commissioned, **only** the following. (1) B-14. (2) The PL-0 row of the original §5. (3) The R0 row of the original §4. (4) The new §10.3.7 (the record element's invariants: identity, merge, integrity, moving the record, removal). (5) The new map line R0′. (6) Reclassifying B-01 under the new class. **Every other comparison and finding of `PART-B-REPORT.md` is not rerun and stays on its baseline `dca72c57f`.** No finding in this report is mixed with that baseline |
| C | repository commit `f7504c6c9fb96e0f104f404143123e6881064d8e` (HEAD at the run). `git diff a5416f3be f7504c6c9` is empty for `scripts/lib/EngineeringKnowledge/`, the three entry points, `tests/Unit/Cohesion/`, `composer.json` and `phpunit.xml`. **C is unchanged** since the original report (library last changed at `a72528b29657170fecc416c34dc61b899ba345d1`) |
| D | unchanged; the same three files and sha256 as `PART-B-REPORT.md` (Reference Architecture `c861e5d1…`, Decision Model `c8906442…`, Metamodel `3bdcc3db…`). ADRs used: none |
| T | `8e4b1a420fe31aba5350d0fe0da631db6d1845d7` (the G1 settlement). Copies read with `git show 8e4b1a420:<path>` from `06-theory/manuscript/chapter_10/`: `10.01-subdomains.md` (blob `413d9e70`, sha256 `aa679fef5e6753219591346bf2eb40512cb94036220c52214e0dd252902f7be1`); `10.02-bounded-contexts.md` (`5e952580`, `2ae0d754dacc294914c621975704f652da599e0bd6ab396bf837b79cf8a0a9c7`); `10.03-ownership-of-invariants.md` (`b487c8d6`, `8fcc85d5bf375f7edaccd54c4edc5758ff893fcba97152740ff170ef4a353f35`); `10.04-context-map.md` (`5ee8fcfe`, `7c8fb9b0febcf0e6e0834ec9a25ff33fc612881e64813d1b939a1a3dfad85595`); `10.05-published-language.md` (`694f6d44`, `574ada5f0d9b281282bf964aa362d2208cd2f2c47508b8faa9ac2f82d1a907b6`). §9.13, §9.17 and §9.18 were not read: Ch. 10 states every rule compared here. **Every T element is S0** |
| Changes in T between the two baselines (by `diff` of the copies; verified) | 10.01: a new paragraph, "The record element's class", which classes it as supporting (lines 70–73). 10.02: the admission note (line 5). 10.03: lines 261 and 300, and the new **§10.3.7** (325–345). 10.04: the header, **R0 restated** and **R0′ added** (52–53), R17 restated, the conditional count (now 16 of 23, line 132), and the §10.4.4 table. 10.05: §10.5.2 split into **"the record's language"** and **"standing's language"** (56–67); PL-0 restated (72–80); PL-3 and PL-4 moved under the record's language (81–96); PL-1's ownership of kinds restated (119); a recommendation on guard-refusal reasons (not adopted) |
| Tests | `vendor/bin/phpunit --testsuite EngineeringKnowledge --do-not-cache-result --colors=never` → `OK (252 tests, 603 assertions)`, exit 0. `vendor/bin/phpunit tests/Unit/Cohesion --do-not-cache-result --colors=never` → `OK (187 tests, 509 assertions)`, exit 0. `php scripts/identifier-check.php --series` → exit 0 (series R, PMR; "configuration, NOT a ruling (G-1 is open)") |
| Other | **A fixture-reading test is disclosed (mandate §2):** `Tests/BackTest/Phase0BackTest.php` reads `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` through `git show`; the auditor did not view its content. **Not opened:** the implementation-track decision record (entry I-003). It lies under `docs/knowledgeos/` outside the auditor's access. Revision 2 of the mandate was read in the mandate itself (`git diff a5416f3be HEAD` on the mandate: the status row, the new class row, §8) |

**Labels.** Statements are labelled **verified**, **reported**, **inferred** or **open**, as in part A and part B. Each finding has six parts: observation · evidence · class · confidence · status · limitations. All of Ch. 10 is S0, so **no difference is a code deviation or a defect**. Each status is ***awaiting the Principal's decision***.

## 1. What T now says about the record element (reported from the T copies)

- **Owner:** "The record and its kind list are owned by no area." It is "changed only by an authorised act outside every area; adding a kind is such an act, so no area can admit a context" (10.05:77; 10.04:52; 10.03:329).
- **Class:** supporting (Int, S0). It is specific to KnowledgeOS but not promised in itself. The rival reading, "core", is not adopted (10.01:70–73).
- **Not a context:** the element is owned by no area, its rules are enforced by the kernel's steps, and it relies on standing's answers for the authority of a removal or a designation (10.03:343).
- **Its invariants** (§10.3.7, 10.03:334–338):
  - law 9a (idempotence), law 29 (content-derived identity), law 30 (merge by union);
  - the designation rule, with one owner application per store;
  - the carrier-change gate (CON-0265), enforced by the architecture act;
  - §9.15 G6, under which an integrity failure is refused;
  - laws 7 and 8 (removal by tombstone, by a recorded act).

  Standing keeps laws 1, 2, 4a, 4b and 5; Q1–Q3; laws 9b and 24; the grant-owner rule; and annulment (10.03:339). Row A was "re-cut, not re-derived" (T10-17, 10.03:344).
- **Its language:** PL-0; PL-1's kinds `remove` and `designate` and the fields every kind carries; PL-2's guards on every entry and on removal and designation; PL-3; PL-4; PL-5's store owner (10.05:56–67).
- **R0** is now unconditional: the element → every area (conforms). **R0′** is new: S → the record element (reads: the authority of its operations), unconditional (10.04:52–53, 132).
- **Standing** now owns eight kinds, and its admission is "no longer 'by construction' of its own schema" (10.03:342; 10.05:119).

## 2. The rerun comparisons

| Item | T at `8e4b1a420` | C (unchanged) | Result |
|---|---|---|---|
| **PL-0** (original §5 row) | the one designated record and its kind list, owned by no area; changed only by an authorised act outside every area | **none.** C has no record and no entry kinds. A search of production code found no hashing of content, no union of records, no tombstone or removal, no designation or store move, and no append operation. The hits are "AMD5" for `md5`, `array_merge` on in-memory lists, and prose in docblocks | **R1-02** (theory element with no code) |
| **R0** (original §4 row) | the element → every area (conforms); **unconditional**; the owner decided, outside every area | **none** (no record, no areas writing into one). The nearest analogue is the governed-register list, a configuration file outside the library that no C module owns (`GovernedRegisterMap` "OWNS NO POLICY. It LOADS configuration", `GovernedRegisterMap.php:14–28`) | **R1-02**; the analogue is in **R1-01** |
| **R0′** (new) | S → the record element: a removal or a designation is accepted only if its authority is T at append, evaluated by standing | **none on either side** (no standing evaluator, no removal or designation) | **R1-03** |
| **§10.3.7**, identity (laws 29, 9a) | entry identity is derived from content, and appending twice changes nothing | none. *Homonym, not a counterpart:* Cohesion's `UnitIdentity` is a **declaration-path** identity of code units, under which "source-order changes may change identity" (`UnitIdentity.php` docblock). That is a different object, with identity taken from position, not from content | **R1-03** |
| **§10.3.7**, merge (law 30) | union of records, joining copies by identity; conflicting copies refused; tombstone wins; weakest status | none | **R1-03** |
| **§10.3.7**, integrity (§9.15 G6) | an integrity failure is refused | none. *Homonym:* C's "IdentifierIntegrity", "ReferenceIntegrity" and "VocabularyIntegrity" are document-structure checks, not an entry's integrity check | **R1-03** |
| **§10.3.7**, moving the record (designation rule; one owner application per store; carrier-change gate) | owned by the element; the gate is enforced by the architecture act | none. C's Markdown and YAML readers **read** carriers through ports and never move or designate a store (part A: production library code writes no file) | **R1-03** |
| **§10.3.7**, removal (laws 7, 8) | removal by tombstone, by a recorded act, changing no value | none | **R1-03** |
| **B-14** (rerun) | see R1-01 | see R1-01 | **R1-01** |
| **B-01** (reclassified) | standing, now **eight kinds**, Q1–Q3 and its laws (10.03:339, 342) | none (`PART-B-REPORT.md` B-01; C unchanged) | **R1-04** |

## 3. Findings

**R1-01 · rerun of B-14 · A list that decides what can be judged, changed outside the record element's discipline.**
1. *Observation:* Under T at `8e4b1a420`, the kind list belongs to an element owned by no area, and it changes only by **an authorised act outside every area** (R0, PL-0). In C, the list of governed registers (and the declared vocabulary) is a configuration file outside the library:
   - no C module owns it (`GovernedRegisterMap` only loads it);
   - editing it changes C's verdicts, for example a series moves between INCONCLUSIVE and evaluable;
   - C neither requires nor records an act for the edit ("Adding a register is a CONFIGURATION EDIT"; "configuration, NOT a ruling (G-1 is open)").

   **Compared with B-14 at `dca72c57f`:** T's side has changed from "owner open, today standing's schema" to "no area". On the *ownership* half, C's arrangement (a list owned by no module) now resembles T. On the *change* half, the difference remains: T requires an authorised act, and C has no notion of one. The R12/K3 half of B-14 (unrecorded inputs change answers without a new entry) is unchanged in T and is not re-compared.
2. *Evidence:* T 10.04:52; 10.05:72–80; 10.03:329. C: `GovernedRegisterMap.php:14–28`; `IdentifierIntegrity/README.md` §4; the `identifier-check --series` output (this run).
3. *Class:* **unresolved**, as before. C has no record and no entry kinds, so the comparison is an analogy of structure, not a mapping of an element.
4. *Confidence:* low.
5. *Status:* awaiting the Principal's decision. *(Supersedes B-14 for the T10-16 part. B-14 itself stays as written on its baseline.)*
6. *Limitations:* S0. Whether C's register list is a "kind list" at all is open: registers are series of identifiers, not kinds of entries. Who may edit the configuration was not examined: there are no repository permissions in C.

**R1-02 · The record element and its kind list (PL-0, R0) have no counterpart in C.**
1. *Observation:* T now names a distinct element: one designated record and its kind list, owned by no area, supporting (Int), not a context. It is upstream of every area (R0, unconditional). C contains no record, no kind list, and no operation that appends, merges, removes or designates.
2. *Evidence:* T 10.01:70–73; 10.03:329, 343; 10.04:52; 10.05:56–80. C: the search results in §2 (verified); `PART-B-REPORT.md` B-01; part A §3 (production library code writes no file).
3. *Class:* **theory element with no code** (revision 2).
4. *Confidence:* high (the absence is shown directly).
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. The element is new in T at this baseline, so there was no earlier comparison to rerun beyond PL-0 and R0. The absence may be expected, because C was not built as KnowledgeOS (`PART-B-REPORT.md` B-02, not rerun). Implementations elsewhere in the repository were not searched.

**R1-03 · The record element's invariants (§10.3.7) and R0′ have no counterpart in C; three C names look like counterparts and are not.**
1. *Observation:* None of the §10.3.7 invariants has an enforcing site in C: identity, idempotence, merge, designation with one owner per store, the carrier-change gate, integrity refusal, and removal by tombstone. R0′ (the record element reads standing's authority answers) has neither end in C. Three names in C could be mistaken for counterparts:
   - `UnitIdentity` is a positional identity of code units;
   - the capabilities named "\*Integrity" check document structure;
   - the `*Reader` adapters read carriers but never move a record.
2. *Evidence:* T 10.03:334–338, 343; 10.04:53. C: §2 above (search results); `UnitIdentity.php` docblock; part A §2 and §3.
3. *Class:* **theory element with no code**, for each invariant and for R0′. The three name matches are recorded to prevent a **mapping error**; they are not findings of their own.
4. *Confidence:* high.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* S0. Row A was "re-cut, not re-derived" (T10-17), so the owner assignments are themselves provisional in T. The carrier-change gate is enforced by an architecture act, so it would not be expected in code at all.

**R1-04 · B-01 reclassified: standing has no counterpart in C.**
1. *Observation:* B-01 stands with its scope narrowed by T's change. Standing now owns eight kinds (root, grant, adopt, revoke, annul, endg, consume, transfer), Q1–Q3, laws 1, 2, 4a, 4b, 5, 9b and 24, the grant-owner rule, and annulment. Record-keeping has moved to the record element (R1-02, R1-03). None of standing's elements has a counterpart in C. Standing's admission no longer holds "by construction"; that changes T's ground, not the comparison.
2. *Evidence:* T 10.03:339, 342; 10.05:119; 10.02:5. C: unchanged since `PART-B-REPORT.md` B-01 (`git diff` empty).
3. *Class:* **theory element with no code**. **Reclassified from "unresolved"**: it fits the new class exactly, and the mandate's revision 2 cites B-01 as its reason (§8, row 1).
4. *Confidence:* high.
5. *Status:* awaiting the Principal's decision.
6. *Limitations:* the same as B-01 (S0; reference-product frame, B-02). This rerun reclassifies B-01. It does not rerun B-01's other comparisons, which stay on `dca72c57f`.

## 4. Summary for the Principal

- **What changed in T:** T10-16 (b) splits a **record element**, owned by no area, from standing. The element gets PL-0, PL-3, PL-4, parts of PL-1, PL-2 and PL-5, and the record-keeping invariants (§10.3.7). R0 becomes unconditional, and R0′ is added.
- **Against C, which is unchanged:** the record element, its invariants and R0′ have **no counterpart** (R1-02, R1-03: theory element with no code, high confidence).
- **B-01 is reclassified** as a theory element with no code (R1-04).
- **B-14 rerun (R1-01):** still unresolved, at low confidence. C's register list is, like T's kind list, owned by no module. But T requires an authorised act to change it, and C has none.
- **Not rerun:** the rest of `PART-B-REPORT.md`, which stays on baseline `dca72c57f`. *Other changes in T at `8e4b1a420`* (R17 restated, the conditional count, the catalogue wording in §10.4.4) bear on the original §4. They were **not** in this commission and were not compared.
