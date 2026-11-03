CONSOLIDATION S0378–S0402: REPORT C

Reviewer C, consolidation review of the frozen copy `BOOK-UNDER-REVIEW/`. My scope: §8.100–§8.102 and their index rows (L115–117), the passages changed at settlement (`08.82` L52, `08.88` L5, `08.98` L58, L74), and change-log rows `08.99` L138–140. Line numbers are the copy's.

## Summary
1. No critical or high findings. All three sections hold within their stated scope ("within the estate"/"within D"). The quote check finds every quotation (36/50/13 found, 0 not found). R1 has 0 flags.
2. There are eleven normal findings. Four are factual or pointer defects against the sources (F-C1 to F-C4), one is a mis-stated lineage detail (F-C5), and two challenge §8.100 C2: a concept gloss (F-C6) and a baseline in its derivation (F-C7). The other four are coverage, index or concept-fit issues (F-C8 to F-C11).
3. The largest single one is F-C1. §8.101 C3 and C4 say S0386 "adds" a seventeenth bar, `5928b9f9`, which would be a change over time against S0385 L228. In fact `5928b9f9` was already among the sixteen (S0380 L98; S0381 L89; S0382 L46). The error started in the WA step-1 inventory (S0386 item 10, cross-source C4) and was taken over at settlement.
4. F-C7: the proof of "its only effect" (§8.100 C2) compares against a baseline in which the producer simply passes the bar. In the estate, though, verification could not be commissioned at all until the producer was recorded (S0378 L46, L65; S0382 L26). So the attribution also *opened* the commission, and S0380 L80's "bars, never enables" fails a second way.
5. All four settlement passages match the section they correct and the settlement they name. All three change-log rows match their text.
6. Window coverage is good. The gaps are minor (coverage table). One is an omission inside a restated claim (F-C12: S0380 L81 not addressed).
7. Concepts cited are used correctly as to ID and status. Two have meaning-fit issues (CON-0179 in F-C6, CON-0242 in F-C11).
8. R2 lists 112 tier-D candidates in scope and 0 of tier A (ASD-001 is listed for information). I judged each: none is an undefined technical term needing a definition; estate terms are quoted or glossed where used.
9. SEQ order within §8.100–§8.102 is sound. Only R3 notes point forward (§8.100 C4 to §8.101 C3). The index rows sort numerically (8.98, then 8.100, 8.101, 8.102).

Process independence: I am a separate reviewer session that wrote none of this work. I saw no other reviewer's report and no working (non-copy) book file. This is process independence, not personal independence.

## Findings
All findings: severity normal; type defect unless marked "omission".

| ID | Section | Finding | Evidence | Correction | R3 lines and disposition | Resolved when | Window | Source | Text |
|---|---|---|---|---|---|---|---|---|---|
| F-C1 | §8.101 C3 L65; C4 L71 | "S0386 L105 adds a bar, `5928b9f9`, against S0385 L228's 'stays at 16'" and "a new bar". `5928b9f9` is already one of the 16; S0386 gives it a new ground. The count stays 16, so there is no change over time against S0385. | S0380 L98 lists `5928b9f9`; S0381 L89 "recorder (supersession notice)"; S0382 L46; §8.97 L52 already records its self-bar; S0386 L105 "Identity bar added: `5928b9f9`… read AST-019 source" | "S0386 L105 states a new ground (reading AST-019, the §2 finding) for a label already barred (S0380 L98); the count stays 16, consistent with S0385 L228." Add S0386's "added" to §8.101.3's source defects. | §8.101 C3 claim: §8.100 L68 (the step-0 R3 note), unchanged. §8.101 C4: none. Text "5928b9f9": §8.97 L52 supports the correction, unchanged; §8.105 L86 is unrelated, unchanged. | both lines corrected, source defect listed | C107 | S0386 (S0380, S0381, S0382) | settlement (C3 restated, WA W-09; C4 restated) |
| F-C2 | §8.101 C5 L86 | "AST-018 refuses (L365)": S0387 has 175 lines. `:365` is a code line of `next-actor-orchestration.php`, cited at S0387 L70. A stale pointer, not missing content. | S0387 L70 "`AST-018 appoint` refuses at `next-actor-orchestration.php:365`"; `wc -l` gives 175 | "(S0387 L70, the code site `:365`)" | no inbound (claim §8.101 C5: 0; text "L365" only this line, plus an unrelated §2.10) | pointer fixed | C107 | S0387 | settlement (C5 restated, WB W-06) |
| F-C3 | §8.101 C1 L43 | The convergence is said to be counted as "the cleanest result" (L1099). S0383 L1099 says that of the *separation* of lenses, DDD and kernel (L1087–1097), not of convergence. | S0383 L1087–1099; L37 ("key insight… converge") is the only convergence-count line; L1103 reports "strengthening the already-admitted convergence" | Drop L1099, or replace it with L1103. The §8.33 C3 reading stands on L37. | §8.101 C1 claim: §8.102 L3, L67; §8.103 L3, L14, L31, L32, L35, L77; §8.105 L3, L6, L34. All cite the one-voice point, not L1099: unchanged. | citation corrected | C107 | S0383 | settlement (WA W-02) |
| F-C4 | §8.101 C8 L113 | "A possible misattribution… not checked" can be decided. S0363 does not contain "'Kernel = pure function' → HYPOTHESIS"; S0361 L797 does, verbatim. So S0388 L58's attribution to `kernel/20260823-110950` (S0363) is a misattribution. | grep of S0363 (1,216 lines): "pure function" only at L208 ("The domain is a pure function"); S0361 L797 has the sentence verbatim | State it as S0388's misattribution and list it with S0388's source defects. *Limit:* the byte-exact duplicate `kernel/110305` (S0388 L307) was not opened. | §8.101 C8: no claim inbound. Text "pure function": §8.86 (C2, .1, .5), §8.87.3, unchanged. | stated as decided, with the limit | C107 | S0388 (S0363, S0361) | settlement (WB W-10) |
| F-C5 | §8.102 lineage L5 | "S0364 L11–14 are the three options of S0392 L1304": S0392 L1304 names two options (core domain act; first transition of a larger domain). S0364 adds a third ("a subordinate act"). | S0392 L1304; S0364 L11–14 | "S0364 L11–14 restate S0392 L1304's two options and add a third." The lineage conclusion (L1302 = S0364 L7, word for word) stands. | §8.88 L5 R3 note cites only L1302: unchanged. Index row 8.102 and map L276: unchanged. | wording fixed | C108 | S0392, S0364 | settlement (W-05) |
| F-C6 | §8.100 C2 L45 | "The method is CON-0179's (§8.10 C5: an author traced by a write-class operation…)": CON-0179's register meaning includes "corroborated by the producer's self-disclosure… one case". S0380 has no self-disclosure. So this is CON-0179's provenance route without its corroboration, and CON-0179's "one case" no longer describes the estate. | register CON-0179 (PROVISIONAL); S0380 L33 "not self-declaration… never declared"; §8.10 C5 "only the first is provenance" | "CON-0179's provenance route, without the self-disclosure that corroborated S0092's trace (S0380 L33)." Flag CON-0179's "one case" for its rotation (out of scope). | §8.100 C2 claim: 08.82 L52, 08.98 L58, 08.101 L20, L66, L77 (all the document/workflow-record point): unchanged. CON-0179 cites (§8.10, §8.12, §8.67, §8.69, §10.1, §10.2): unchanged. | gloss corrected | C106 | S0380 | settlement (W-01) |
| F-C7 | §8.100 C2 L49 (Ded) | Step (6)'s baseline ("without any attribution P also passed") is not the estate's. Without the attribution, verification could not be commissioned (F-3 gate). The attribution satisfied the gate and so *enabled* the appointment. "Only effect… for the barred set" holds only against a label-check baseline. Against the gated baseline a wrong attribution also lets a verification run under a false assurance, which also refutes S0380 L80's "bars… opposite of… enables". | S0378 L46 "Before that verification can be commissioned, finding F-3 must be settled"; L65; S0382 L26 "Why this appointment is now possible… That condition is satisfied" | Add a step (6′): "in the estate the commission was gated on F-3 (S0378 L46, L65); the attribution met the gate (S0382 L26)". The conclusion then reads: "true for the barred set against a label-check baseline; against the gated baseline it also enables the commission, under a false assurance (8)". | index L115 and map L274 stay true; they may be extended. §8.100 C2 inbound lines: unchanged. | step added, conclusion restated | C106 | S0380, S0378, S0382 | settlement (W-09) |
| F-C8 | §8.102 C3 L46 | "C3 covers three of (A)'s ten 'facts'; the others restate C1–C2". Facts 4 (justification preserved), 5 (epistemic state "determined at admission") and 7 (history "recorded at admission") are not in C1–C2. Fact 5 conflicts with the book's derived state (§8.93 C3). | S0392 L652–662; C108 inventory item 14 | List facts 4, 5 and 7 with their dispositions: 5 against §8.93 C3; 7, history as the record (§8.91 C1); 4, no justification concept (§8.94 C1 (iii)). | §8.102 C3 claim: §8.104 L90 (a boundary home): unchanged | sentence corrected | C108 | S0392 | settlement (W-07) |
| F-C9 | index row 8.101 (L116); index note | Finding IDs F-n now come in several lists: S0378–S0379 F-1…F-8 (§8.100), S0390/S0391 F-1…F-5 (§8.101) and S0392's undefined "F-1…F-5" (§8.102). The index note separates only S0361's list (§8.86) and S0376's. Row 8.101's unqualified "ASD-001 and F-1" therefore reads, by that note, as S0361's F-1. A homonym. | index note text; §8.101 C6; §8.102 lineage | Extend the note: "F-n in §8.100 (S0378–S0379's findings) and §8.101 (S0390's) are further lists". Qualify row 8.101 as "S0390's F-1". | index only | note and row qualified | C107 (also C106, C108) | S0390, S0378, S0379, S0392 | settlement (index row) |
| F-C10 | headers §8.100 L3, §8.101 L3, §8.102 L3; index note | The "uses" lists are incomplete. §8.100 omits §8.11, §8.93 C2, §9.9 T2.2, H1′, §8.13.3 and §8.101 C3 (R3). §8.101 omits §8.93 C2, §10.3, §8.11, §8.70, §8.84 and §8.13.3. §8.102 omits §8.11, K1, G8, G1, K7, §9.13 L53 and §8.93 C3. The index's forward-R3 list stops at §8.98 and does not record the new R3 notes (§8.82 C2, §8.98 C4, C6 ← §8.100; §8.100 C4 ← §8.101; §8.88 lineage ← §8.102). | the section bodies cite each one (e.g. §8.101 C2 L55 "§8.93 C2"; C1 L44 "§10.3"; §8.102 C5 "§8.11") | Complete the lists and extend the index note. | n/a | lists complete | C106–C108 | none | draft (unmarked headers) |
| F-C11 | §8.101 C7 L103; §8.101.3 L126 | CON-0242 ("checkable is not checked": the result rests on the producer until another party re-runs the check) is a loose fit for S0391 L58's "verified, not assumed". That phrase means asserting a criterion by test instead of inferring it as a corollary of another fix. CON-0242 is also a loose fit for O-2's trivially passing tests, whose nearer homes are §8.29 C4 and §9.19.8. | register CON-0242 (VALIDATED); S0391 L43–45, L58; S0390 L284 | State the CON-0242 link as an Int analogy with the difference, or rehome to §8.29 C4 / §9.19.8. | none | homes stated | C107 | S0391, S0390 | settlement (WB W-07, W-13) |
| F-C12 (omission) | §8.100 C2 | S0380 L81 narrows its own novelty claim ("only its third-party use is new"; the evidence class was already accepted through `cf621832`'s own transcript-lineage proof). The book refutes L74 and L84 but does not answer L81. S0092's trace (§8.10 C5) was already third-party, so L81 is contradicted too. | S0380 L81; §8.10 C5; C106 inventory S0380 item 9 | Add: "L81's narrower claim (only the third-party use is new) is also contradicted by S0092 (§8.10 C5)." | none | sentence added | C106 | S0380 | settlement (C2 restated, W-01) |

Verified, no finding:
- **§8.100.** All checked line references hold. Spot-checks: S0378 L15, L30, L35, L36, L46, L53, L57, L69; S0379 L3, L17, L41, L55, L68, L72; S0380 L31–L110 (each cited line); S0381 L4, L46, L51–52, L83–98, L104–106, L174–177, L212, L237–239; S0382 L11, L26, L32, L34, L44, L50, L58, L80, L86, L88.
  - Bar count: 15 labels plus PO/ARB, counted.
  - The novelty refutation: S0105 L131 and L163 support "the second by Governance for a barring purpose".
  - C3 agrees with §8.09 C2's own identifier clause.
- **§8.101.** About 60 cited lines checked across S0383–S0391. Defects beyond F-C1 to F-C4: none. The S0386 sha256 on disk (`95c6113…`) matches §8.101 L8.
- **§8.102.** About 40 lines checked. The lineage is confirmed: S0392 L606 equals S0363 L511; S0392 L1302 equals S0364 L7; S0363 does not contain the question (grep).

## Settlement passages and change log

| Item | Verdict |
|---|---|
| `08.82` L52 (R3, C106 W-05) | matches §8.100 C2 (premise (2) holds for the workflow record; checkable by label against a document) |
| `08.88` L5 (R3, C108 W-05) | matches §8.102 lineage. S0363 does not hold the question verbatim (checked), so "word for word S0392 L1302" discriminates correctly. Unaffected by F-C5. |
| `08.98` L58 (R3, C106 W-05) | matches §8.100 C2 |
| `08.98` L74 (R3, C106 W-16) | matches §8.100 C1 (S0379 L17, S0378 L15 verified). The earlier "not yet read" is kept after the note: acceptable. "§8.25 C7" beside §8.26 C3 homes "a status tag is a claim", so it is not the defect §8.100.5 withdrew. |
| `08.99` L138 | matches: §8.100 W-01–W-16; R3 notes at 08.82 L52 and 08.98 L58, L74; draft `a8580bf7c` agrees with §8.100 L3 |
| `08.99` L139 | matches: §8.100 L68 R3 note (WA W-08); S0386 access note (§8.101 L8); draft `9879a379b` |
| `08.99` L140 | matches: 08.88 L5 R3 note; W-01–W-10, A-1–A-7; draft `8e48f3b89` |
| Index rows L115–117 | match their sections, apart from F-C9 (homonym) and F-C10 (R3 notes not reflected) |

## Coverage against the window inventories (summary; COVERED unless listed)

| Item | Status | Home |
|---|---|---|
| C106 S0378 #5 (no mechanism change, L34); #14 (non-repair, L71); S0379 #8 (F-1 closed) | JUSTIFIABLY EXCLUDED (procedural) | — |
| C106 S0380 #9 (L81, evidence class; only third-party use new) | MISSING | F-C12 |
| C106 S0380 #8 (wrong attribution leaves P unbarred) | COVERED | §8.100 C2 Ded (see F-C7) |
| C106 S0381 #4, #6, #9, #10 (identity from environment; disclose everything; read only `fold`; no self-appointment) | JUSTIFIABLY EXCLUDED (already homed: §8.11, §8.80 C1) | — |
| C106 S0382 #11 ("appointed but not activated") | COVERED | §8.100 C4 |
| C107A S0383 #5 (unknown ≠ absent ≠ false, L583–596) | MISSING (minor; excluded by the blanket lens rule) | nearest home §8.105 C1 / §8.40 |
| C107A S0383 #6 (ConflictRecord, the DDD test at L82) | MISSING (minor) | — |
| C107A S0384 #7 (absence changes connectedness); #10, #13 ("permissible transformation" undefined, so the hypothesis cannot be tested) | MISSING (minor) | §8.101 C2 keeps it a hypothesis only by its own label |
| C107A S0385 #11 (§22 decided, not executed); S0386 #4 (L39 corrected premise); S0386 #11 | JUSTIFIABLY EXCLUDED (estate process) | — |
| C107A S0386 #10 and cross-source C4 (17 bars) | DISTORTED | F-C1 |
| C107B S0387 #1 (four roles); #10 (author does not rule on own remedy) | MISSING (minor) | — |
| C107B S0388 #4 (L41–46, L60), #5 (reasoning chain L78) | JUSTIFIABLY EXCLUDED (secondary report of sources already homed: §8.88, §8.94) | — |
| C107B S0389 #9 (#14, #15 rows), #12 (flags for the human) | JUSTIFIABLY EXCLUDED (listing) | — |
| C107B S0390 #6 (L157: AST-018 decides who, AST-019 binds) | MISSING (minor) | — |
| C107B S0391 #1 (STOPPED, seq 4) | MISSING (minor) | — |
| C108 #6, Part B on confidence (L878–904: the kernel consumes an assessment it does not own) | MISSING (minor) | — |
| C108 #12 "No semantic mechanism becomes identity authority" (L266, L663); #13 evidence-grounding (L165); #17 sufficiency (L175, L695); #15 "do not freeze, do not code" (L750–752, L1292) | MISSING (minor) | nearest homes §8.96 C3, §8.95 C4 (c) |
| C108 #14 (facts 5–8) | DISTORTED (by "the others restate C1–C2") | F-C8 |

All other inventory items are COVERED. Their homes are the claims C1–C8 of the three sections and the .3 coverage blocks.

## Concepts cited (copy's register)

| ID | Status | Use in scope | Verdict |
|---|---|---|---|
| CON-0127 | VALIDATED | never PASS (§8.100 C4, §8.101 C3, C5) | correct |
| CON-0151 | PROVISIONAL | identifier clause (§8.100 C3, §8.101 C3) | correct |
| CON-0179 | PROVISIONAL | §8.100 C2 method | gloss omits the self-disclosure clause (F-C6) |
| CON-0196 | PROVISIONAL | AUTHORED class; its assumptions | correct |
| CON-0215 | PROVISIONAL | never-exercised invariant (§8.100 C5, §8.101 C6) | correct |
| CON-0120 | VALIDATED | absence keyed to one label | correct |
| CON-0242 | VALIDATED | §8.101 C7, O-2 | loose fit (F-C11) |
| CON-0175 | PROVISIONAL | "open" in §8.102 C2 | acceptable: the fork is what is open; the status is PROVISIONAL |
| CON-0192 | VALIDATED | rule conflict | correct |
| CON-0211 | PROVISIONAL | the dispute flag against g1's "contested" | correct |

## R2 (`--scope 08.100-08.102`)
Tier A in scope: 0. ASD-001 is listed for information only: its first use is in index row L116 and it is defined at §8.101 L8; acceptable. I judged all 112 tier-D candidates.

- **Estate or source terms, glossed or quoted where used (no defect):** status-supersession; lane-shape (unglossed, but listed and not used); leakage; candidate-declaration; start-gate; orienting; domain-state; adjudication; topological; mtime; fixture; enforcer; entailed; self-check; additive; mitigation; bypassed.
- **Ordinary English or numerals (no definition needed):**
  - retell, checklist, conduct, consolidated, dilemma, unestablished, disk, decomposed, searching, discrimination, evening;
  - horn (in "three horns", the dilemma idiom), recast, recurring, stayed, filesystem, keyed, secondary, renaming, clash, companion, sixteen, decline, discussion, glossed, fifteen, overloaded, structurally, thread;
  - converging, denied, group, declaring, grouping, nearly, contradictory, genuinely, authorizing, imply, novelty, rename, instrument, minute, precede, restatement, told, opening, warn, classified, hand, covered, falsified, conversation, holding, primary;
  - adopting, exercised, instead, missed, reached, someone, sits, exit, replie (tool stem), strongest, lies, else, transcript, anything, repeat, closing, passed, drift, declare, assistant, otherwise, together, refutation, restate, ties, filename, brainstorming;
  - admit (sense qualified: (a)/(b)/(c), "a kind admitted to D"), beyond, listed, said, appointment, quoted, above, plain, adviser (defined in the map), because, agree, keep.

No homonym is unqualified except the F-n IDs (F-C9).

## Coverage of sections
- **Read in full:** `08.100` (103 lines), `08.101` (149), `08.102` (87); index L1–20 and rows L105–125, including L115–117; `08.82` L52; `08.88` L5; `08.98` L58, L74; `08.99` L136–141.
- **Read in part, as cited:** §8.09 C2; §8.10 C5; THEORY-MAP L274–276; register rows.
- **Sources:** I spot-checked the cited lines (not a full re-read) of S0378–S0382 (read whole), S0383–S0391 and S0392; I also checked S0105 L125–135 and L158–166, S0364 L1–16, and S0363 and S0361 by targeted grep and sed.
- **Not done:** I did not re-read S0383 or S0390 in full. I did not open the duplicate `kernel/110305`. The three-digit numbering checks, Ch. 10, the summaries and the map are other reviewers' scope.

Out of scope, one line each:
- THEORY-MAP L274 could carry F-C7's extension.
- CON-0179's "one case" is stale in the register.
- S0386 is itself a source of the F-C1 slip.

## Files opened (all read-only)
1. `.../06-theory/manuscript/review/CONSOLIDATION-S0378-S0402/COMMISSION-C.md`
2. `.../CONSOLIDATION-S0378-S0402/REVIEWER-NOTE.md`
3. `.../CONSOLIDATION-S0378-S0402/SCOPE.md`
4. `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md`
5. `.../03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`
6. Copy, `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_08/`: `08.00-index.md` (L1–20, L105–125); `08.100-…`, `08.101-…`, `08.102-…` (whole); `08.09-…` L29–42; `08.10-…` L45–60; `08.82-…` L52; `08.88-…` L5; `08.98-…` L58, L74; `08.99-change-log.md` L136–141; grep lines in `08.105-…` and `08.97-…` (lines with "5928b9f9")
7. Copy, `BOOK-UNDER-REVIEW/06-theory/CONCEPT-REGISTER.tsv` (header and rows of 20 cited IDs, via grep); `BOOK-UNDER-REVIEW/06-theory/THEORY-MAP.md` L274–276; `BOOK-UNDER-REVIEW/tmp/tools/book_search.py` (grep of its source)
8. Inventories (SCOPE §2.3): `review/C106-S0378-S0382/STEP1-INVENTORY.md`, `review/C107-S0383-S0391/STEP1-INVENTORY-A.md`, `review/C107-S0383-S0391/STEP1-INVENTORY-B.md`, `review/C108-S0392/STEP1-INVENTORY.md`
9. `CORPUS-ALLOWED.tsv` (header and grep by S-ID only)
10. Corpus (all on the allowed list), under `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/`:
    - read whole: `governance/2026-08-23-KOS-OPERATING-MODEL-001-ADOPTION-DECISION.md` (S0379), `governance/2026-08-23-KOS-OPERATING-MODEL-001-AUTHORIZATION-DECISION.md` (S0378), `reviews/2026-08-23-…-PRODUCER-IDENTITY-registration.md` (S0380), `…-VERIFIER-CANDIDATE-DECLARATION-prompt.md` (S0381), `…-VERIFIER-APPOINTMENT-registration.md` (S0382);
    - read by line (sed) or grep: `…-VERIFIER-CANDIDATE-DECLARATION-84c0f6f6.md` (S0385), `…-VERIFIER-ACTIVATION-84c0f6f6.md` (S0386, also sha256), `…-APPOINTMENT-SEQUENCING-DEFECT-001.md` (S0387), `…-INDEPENDENT-VERIFICATION.md` (S0390), `…-FAIL-DISPOSITION-AND-REPAIR-PATH.md` (S0391), `reviews/20260823-2212-KOS-BRAINSTORMING-KERNEL-THREAD-READING-REPORT.md` (S0388), `brainstorming/00_INDEX.md` (S0389), `brainstorming/20260823-205735-…` (S0383), `brainstorming/20260823-210001-…` (S0384), `brainstorming/kernel/20260823-225038-…` (S0392), `brainstorming/kernel/20260823-110950-…` (S0363), `brainstorming/kernel/20260823-111647-…` (S0364), `brainstorming/kernel/20260823-110248-…` (S0361), `reviews/2026-08-19-KOS-AIP04-DISCOVERY-001-provenance-reconciliation.md` (S0105)
11. Lane tool sources (grep/sed of headers): `tmp/tools/undefined_terms.py`, `tmp/tools/inbound_citations.py`

No file listed in REVIEWER-NOTE item 5 was reached: no working chapter files, working map, register, outline or summaries, no `02-FILES.jsonl`, nothing in `00-access/`, no git command of any kind.

## Commands and searches run
- `python3 -I tmp/tools/book_search.py` in the copy: `--help` (it searched the copy; it has no help), "8.100", "8.101", "8.102", "S0105", "CON-0179", "CON-0196", "CON-0151", "CON-0127", "CON-0215", "decides only what it names", "§8.25 C7", "8.25", "5928b9f9", "cleanest result", "pure function", "L365", "three options", `ten "facts"`, "only effect", "ASD-001".
- `test -f` on three candidate index paths in the copy, named one by one (to find the copy's layout; not a listing). `wc -l` on the three sections, the sources and the inventories, named one by one.
- `grep -nP "\t(S-IDs)\t"` on CORPUS-ALLOWED.tsv (two calls, plus one for S0361); grep of the register by ID (three calls).
- sed or Read of the cited source line ranges (as in Files opened); `sha256sum` on S0386; grep for "365" in S0387 and S0390; "pure function" in S0363 and S0361; "independently of the Kernel" in S0363.
- `python3 -I tmp/tools/corpus_quote_check.py --help` (it failed: no such file) and then on the three section files of the copy. Output: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons5-c/quote_check.txt`
- `python3 -I tmp/tools/register_home_check.py --root <copy>` (R1). Output: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons5-c/r1.txt`
- `python3 -I tmp/tools/undefined_terms.py --root <copy> --help` (it printed the whole-book list), then `--root <copy> --no-corpus --scope 08.100-08.102` (R2). Output: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons5-c/r2.txt`
- `python3 -I tmp/tools/inbound_citations.py --root <copy> "§8.100 C2" "§8.101 C1" "§8.101 C3" "§8.101 C4" "§8.101 C5" "§8.101 C8" "§8.102 C3" "§8.102" "§8.101" "§8.100"` (R3). Output: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons5-c/r3.txt`, summarised with awk.
- `mkdir -p` of the scratch directory.
- Every tool was called with `--root <copy>` from its first call, except `corpus_quote_check.py`, which takes no root and was given the copy's files. Every R3 citation in the table is from `r3.txt` or a book_search hit.
- Nothing was edited, copied or sent outside.
