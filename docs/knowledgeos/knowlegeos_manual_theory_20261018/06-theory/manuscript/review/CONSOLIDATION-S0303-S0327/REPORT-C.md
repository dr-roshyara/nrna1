CONSOLIDATION S0303–S0327: REPORT C

Reviewer C. Object: the frozen copy `…/review/CONSOLIDATION-S0303-S0327/BOOK-UNDER-REVIEW` (written <copy> below). Mode: consolidation review (REVIEWER-INSTRUCTIONS), commission C.

## Summary (15 lines or fewer)
1. Within their stated scope, §8.68–§8.72 are reliable. Every Ded I re-derived holds: §8.68 C1's overlapping and gapped decision rule; the harmonic-mean figure Q ≈ 0.31; FAR_τ = 1 − NC_snf and FCR(=) ≤ FAR_0 in §8.70 C3; the abstention-precision bound x < a/9; V-1's unchanged gate value in §8.72 C2.
2. Spot checks against the sources confirm the quotations. My own quote script matched 216 quotes. I read the other 35 by hand: they are LaTeX transcriptions or book-internal quotes, plus three slips (appendix).
3. I found no critical and no high findings. There are 13 normal findings and an appendix.
4. Largest risk: three of the section's conditional conclusions rest on premises that a source in the estate already bears on, and the book does not use that evidence:
   - F-02: S0315 L110 tells against (p1), the operand premise behind "MATCH holds by construction";
   - F-01: §8.68 C3's "the trivial abstainer passes" rests on reading the gate through CR, while the quoted gate is NC_snf;
   - F-03: S0322 L29 names the process that created the workflow record, an act the store does not record.
5. Stale pointers: the index row and the map line for §8.71 were not updated after C79's R3 notes (F-07). The Ch. 8 reading-order note stops at §8.61 (F-08).
6. Concepts: none is homed in my sections (SCOPE §2.2; I checked the register), so I give no verdicts.
7. Coverage of C75–C79: no material MISSING item. Four minor omissions (F-11–F-13; S0317's positive-class shift).
8. Process independence: this is process independence, not personal independence. I wrote none of this work and saw no other reviewer's report.

## Findings
| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| F-01 | §8.68 C3 (L77; also L1, L30, L138); index 8.68 (L84); map L243 | The section says an always-UNKNOWN compiler "has CR = 0 and passes" the gate. But the gate the section quotes is on NC_snf, a mean distance over pairs of SNF outputs (S0317 L144–148, L1017–1025). An abstainer produces no such pairs, so its NC_snf is undefined. CR and NC_event = 1 − CR are separate quantities (L1092–1130), and abstention is counted only in the later confusion matrix (L1474–1478). So "passes" holds only if the gate is read as NC_event. | normal | S0317 lines as cited | Say: "the gate does not exclude it. Read as NC_event with the matrix's rule, it passes (CR = 0); read as NC_snf, its value is undefined. No rule scores UNKNOWN." The gap stays open either way. | 08.70:103 change to "the gate does not exclude…"; map L243 and index L84 the same; 08.70:18, :19, :82, :98, :101, :105 no change; 08.99:95 history, no change | the restated text names both readings |
| F-02 | §8.71 C4 (p1); §8.72 C3 L77 | The book says S0324 "does not say which is the operand". But S0315 L110 (Src, reported as run live) shows that, with the running process d1612e03, "`--session=S5…` with a matching label yields MATCH; with INTRUDER, MISMATCH". S5's label is a8ce5a39 (S0324 L30). So the operand is the label argument, not the environment value (Int). S0322 L47 passes the label explicitly. On this reading the reported MATCH (S0325 L105) confirms only that the supplied label equals the registered one. The conclusion "nothing beyond the label" stands, and is strengthened. | normal | S0315 L110; S0324 L30, L67, L81; S0322 L47 | Cite S0315 L110 as evidence against (p1). Make "MATCH can pass for any caller that supplies the label" the better-supported branch. | 08.72:18 and :77 add the qualifier; index L87 add it; map L246 no change; 08.99:98 no change | (p1) is weighed against S0315 L110 |
| F-03 | §8.71 C2 (plain language 2: "the registrations sit elsewhere"); §8.69 C2 case (iii) | Of the four disclosed acts (S0322 L29), "workflow record creation (`1ef0325c`)" is not a registration: it is an act on the store. The store census still finds no reference to b51dba91 (L40), so the store holds no trace of the act that created it. S0322 L29 also discloses who did it: the creating actor of §8.69 C2's case (iii), a self-declared process without a lane (L33). | normal (omission) | S0322 L29, L33, L40; S0320 L20 | §8.71 C2: three of the acts are prose registrations; the fourth is an act on the store that the store does not record (compare §8.67 C5). Add an R3 note on §8.69 C2 (W-11 (iii)). | 08.71:47, :95, :121, :146 (§8.69 C2 inbound) no change; 08.72:19, :112 no change | the note is added |
| F-04 | §8.69 C2 (L19, L69) | The section says "the book names the same structure a deadlock argument (§8.62 C4…)". But §8.62 C4 describes "a dependency cycle between gates", and §8.69 says its own case is "not a cycle". They share a kind (law 20a: a reachable state from which no input leads on, §9.11 L56), not a structure. | normal | §8.62 L131; §9.11 L56 | "the same kind (law 20a); §8.62 C4's is a cycle, this one an obligation whose only means is reserved" | 08.69:19, :69 change; T5-08 L20, 08.67:20, :102 and 08.69:149 no change | restated |
| F-05 | §8.72 C1 Ded (L42); §8.71 R3 notes (L8, L129) | The step "the earlier state differed (S0320 L44)" overreads: L44 is about the harness, not S0324. That S0324 contains correction text rests on S0325's own file list (L26, L73), the author's report, which no verifier checked (S0327). | normal (label/premise) | S0320 L44; S0325 L26, L73 | State the premise "S0325's list is accurate (Src)". S0320 L44 is consistent with the earlier state, not evidence of it. | 08.71:8, :129 add the premise; index L88 add "on S0325's report"; map L247 and 08.99:99 no change | premise stated |
| F-06 | §8.72 C5 L112 | "'complete governed record' = the 19 store records: the store presupposed again." That is true of condition 8's evidence (L68). But S0327 L35 also searched docs/knowledgeos/reviews and git for a verification-lane registration. It is the first reader in this line to look beyond the store, in contrast with §8.71 C2. | normal | S0327 L35, L68 | Scope the claim "for condition 8" and note the contrast | inbound lines are section-level only; no change | scoped |
| F-07 | index 8.71 (L87); map L246 | Not updated after C79's R3 notes. The row ends "W-01's git-history evidence held for a ruling" and does not say that the V-number question was decided from text, or that C5's premise is false as written. Row L99 of the change log lists the R3 notes but not "index row 8.71". | normal (stale pointer) | §8.71 L8, L113, L129 | Append the R3 status to both | — | updated |
| F-08 | Ch. 8 SEQ: 08.00 L90 | The forward-use note covers only §8.18–§8.61. §8.62–§8.72 also use §9.19 (T1, T7; in 9 sections), §9.16 R6 law 20a, §9.8 H1′, §9.5, §9.4 L21, CE*, N4-15 and T1-17, and they carry R3 notes to later sections (§8.55, §8.57, §8.61 → §8.62; §8.71 → §8.72). | normal (SEQ) | grep counts (commands list) | Extend the note | — | extended |
| F-09 | map L244 | "MATCH can only follow the REGISTER it unblocks" drops §8.69 C4's "under reading (a)". Index L85 keeps the condition. | normal | §8.69 L99 | Add "(under reading (a))" | — | fixed |
| F-10 | §8.69–§8.72 (R2) | V-1, V-2 and V-8 are S0315's findings (§8.67 L57). The book also uses V-1 for a note (§5.06 L68) and V-1/V-2 for proposed invariants (§6.04 L31). This homonym is unqualified; only V-3's is recorded. | normal | R2 "also used" list | Qualify V-n as "S0315's finding V-n", or record the homonym in §8.67.3 | — | qualified |
| F-11 | §8.72 C2 | S0325 L48 justifies "false on COMPLETED" by saying "the deterministic table routes terminal lanes to po/arb (R8) regardless". The book does not assess this. It supports "a reporting fix" (S0324 L109), but it does not answer the commission's yardstick (S0320 L42). | normal (omission) | S0325 L48 | Add | — | added |
| F-12 | §8.72 C3 | The estate refused before to append one's own REGISTER as `recordedBy: governance` (S0190 L111–117: "false attribution"; "registering its own assignment"; S0184 L104). §8.71 C3 cites S0190 only for the appointment. This bears directly on "the appointee is the process named to append its own REGISTER". | normal (omission) | sources as cited | Connect the two | — | added |
| F-13 | §8.72 C5 | S0327 L9 and L78 cite Governance's earlier record of a verifier's refusal as "the right behaviour" (the V-8 precedent). This bears on §8.66 C4 and is not used. | normal (omission, low) | S0327 L78 | One line | — | added |

## Concept verdicts
| ID | Verdict | Reason |
|---|---|---|
| — | none | SCOPE §2.2: §8.62–§8.72 home no concept. In the copy's register no book_location points into my passages, and §8.68 and §8.70 carry no CON-ID. CON-0080 is cited by T5-08 but homed in §5.4; its verdict belongs to reviewer A. |

## Coverage (C75–C79, against the STEP1 inventories)
| Item | Status | Home |
|---|---|---|
| C75 items 1–15, 17, 18 (vector, structural ≠ semantic, entropy, three uncertainties, hard gate with Q ≈ 0.31, asymmetry, CR and the NC homonym, Zero, the Gödel lens, boundaries, pipeline, T1's defects (a)–(h), thresholds, simulations, v0.4, corpora) | COVERED | §8.68 C1–C5, §8.68.3 |
| C75 item 16: the positive class shifts between §3.7 (abstain) and the matrix (equivalent) | MISSING (minor) | — |
| C75 open question on composite weights (L985) | JUSTIFIABLY EXCLUDED (composite rejected) | §8.68.3 |
| C76, all S0318–S0320 items and the four contradictions | COVERED | §8.69 C1–C5, §8.69.3 |
| C76, S0319 L37: the no-exemption argument (no special mode for the component that makes state legible) | MISSING (minor; L37 used only for the store) | §8.69 C5 |
| C77 items 1–15, the trivial maps, source defects | COVERED (W's "constrained only through recall" corrected to precision: right) | §8.70 |
| C78, S0322 items 1–12, S0323 items 1–9, S0324 items 1–16 | COVERED (S0324 items 6, 9, 13 as restatements of §8.66) | §8.71 |
| C79, S0325 items 1–14 | COVERED, except the L48 R8 justification | §8.72; F-11 |
| C79, S0326 items 1–12 | COVERED (the commit-discipline line justifiably excluded) | §8.72 C4, §8.72.3 |
| C79, S0327 items 1–15 | COVERED, except the L78 precedent | §8.72 C5; F-13 |
| Not in the inventory: S0327 L35 search beyond the store | omission (F-06) | — |

Ranking of the misses: F-03 > F-02 > S0319 L37 > F-11 > F-12 > the C75 positive-class shift > F-13.

## Other checks in scope
- R3 notes §8.55 L69, §8.57 L6, §8.61 L70: checked against §8.62 C1, its lineage and C3, and against the sources. They hold. A diff of S0303 L656–1292 against S0295 L1–637 gives 0 lines. S0295 L1121 matches S0303 L1385. S0305 L18–26 and L36–38 match.
- T5-08 (L20) matches §8.62 C4.
- Change-log rows L95–99 match their sections (W ranges, R3 notes, map annotations at L446); F-07 is the exception. I could not check the hashes (no git).
- BOOK-OUTLINE L186–206: the rotation order is consistent (Ch. 4 and 5 were last reviewed at S0175). The heading cites v1.3 §12, while SCOPE cites v1.4; this is historical and minor.
- Map L422–426: 318 − 293 = 25 sources, consistent. The register counts 56/113/96 = 265 match the copy's register (my count).
- R1 on the copy: one flag, CON-0133 H4, information only and outside scope.

## R2 (`--scope 08.68-08.72`; the other passages filtered by line)
| Word | Judgement |
|---|---|
| compensatory (A, 08.68 L22) | defined in place ("one in which a high value can offset a low one") |
| fingerprint (C, 08.70) | used in the source's sense; glossed at §8.70 PL2 and C2; home §8.53 C1. OK |
| uncertainty (C, 08.70) | the three uncertainties are defined in §8.68 C2. OK |
| start (C, 08.72) | START (transition) and ordinary "start" are kept apart by capitals; the STOP/STOPPED homonym is handled in C4. OK |
| D tier: canonicalization, calibrated, semantic-entropy, gold-ambiguous, reversion, placeholder, vector, normalization, distance, equivalence/equivalent, probability, incompleteness | defined in place or homed (§8.68 L65, L120; §8.70 C4 L99, L79; §8.69 L36; §8.51). OK |
| D tier: ratify (08.69 L124) | implies an authority approved the book's results; use "agree with" (appendix) |
| D tier: vacuously (08.72 L66) | means "trivially"; vacuous truth needs a false antecedent (appendix) |
| D tier: determination, circularity, appointment, appoint, admissibility, unregistered, regression, policie(s), grep, experimental, synthesi(s), pipeline, mathematic(s), algorithm | source terms or ordinary words. OK |
| D tier, ordinary words (just, continue, directly, ignore, worse, planned, addressed, addition, separated, replaced, reporting, rename(d), site, span, minor, understand, abbreviate, hard, ordering, commissioned, template) | no definition needed |
| D tier, outside my lines (08.55 L38, L41, L62; 08.57 L11, L46, L52, L60; 08.61 L1, L8, L49, L65–68: registrant, template, cross-axi, scalar, horizon, post-commit, mtime) | not judged (outside L69, L6, L70) |
| A tier, 08.00 first uses at L5–L82 (adviser, answerer, asker, swing, eponymou, defaulted, misrouted, serially) and B tier (AMD3, C-14, C-04, I-09, CL-4, I-02, C-11, UM-45) | outside rows L84–88; not judged |
| Used in scope, first used elsewhere: V-1, V-2, V-8 | homonym (F-10); waiver, unhomed, AST-015/16/17: OK |

## Coverage of sections
I read in full §8.68, §8.69, §8.70, §8.71 and §8.72, and these passages: 08.00 L76–90; 08.99 L1–4 and L89–99; 08.55 L69; 08.57 L6; 08.61 L70; Ch. 5 TODO L1–4 and L18–21; BOOK-OUTLINE L184–207; THEORY-MAP L237–247, L420–427 and L443–447.

I did not check:
- cross-references inside the book beyond the anchors named above (§9.18 (c2) and T9-20, §9.10 Q1, §8.06, §8.27, §8.28);
- the 339 quoted strings without a line anchor;
- the hashes.

## Appendix (lower priority)
- §8.69 L72 puts a paraphrase in quotation marks; S0318 L64 reads "…resolve V-8 by omission, which is the same defect as resolving it by fiat".
- §8.72 L55 truncates S0320 L64 and ends it with "." where the source has "—".
- §8.68 L77 cites "descriptive" at L1030–1035; the word is at S0317 L1019.
- §8.69 L99 quotes "resolved", while S0320 L36 says "resolution".
- Index L84–88 shows filenames that state withdrawn claims (8.69 "circularity…"; 8.72 "a stop kept only in prose"). This is disclosed as "filename kept".
- Out of scope, one line: §8.62 L9 gives voice evidence for "three answers", but its anchors L25 and L642 both lie in the first answer (reviewer B).

## Process disclosures
- Breach, reported: my first tool call ran `register_home_check.py`, `undefined_terms.py` and `inbound_citations.py` with `--help` and **without `--root`**, so they read the **working** book: its register, chapters and map, which REVIEWER-NOTE item 4 forbids. I saw their output (one R1 line on CON-0133; the first 25 R2 tier-A lines; R3 "--help": 0 hits) and used none of it. Every later run used `--root <copy>`.
- `book_search.py` does not take `--root`; I passed it once as a search term (harmless; it searches the copy).
- The harness saved one R3 output to `/home/nab-raj.roshyara@dg-nexolution.de/.claude/projects/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/tool-results/bxjj16z28.txt`. I did not open it; I re-read my scratch copy instead.
- I did not use the git status and commit list the harness showed at start-up. I ran no git commands. I edited nothing in the repository and sent nothing to an outside service.

## Files opened
- Review folder: `…/06-theory/manuscript/review/CONSOLIDATION-S0303-S0327/REVIEWER-NOTE.md`, `SCOPE.md`, `COMMISSION-C.md`.
- `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md`
- Inventories: `…/06-theory/manuscript/review/C75-S0317/STEP1-INVENTORY.md`, `C76-S0318-S0320/…`, `C77-S0321/…`, `C78-S0322-S0324/…`, `C79-S0325-S0327/…` (all `STEP1-INVENTORY.md`).
- Copy, under `<copy>/06-theory/`:
  - read in full: `manuscript/chapter_08/08.68-…`, `08.69-…`, `08.70-…`, `08.71-…`, `08.72-…`;
  - read by line range: `08.00-index.md` L76–90; `08.99-change-log.md` L1–4, L89–99; `08.55-…` L69; `08.57-…` L6; `08.61-…` L70; `08.62-…` L8–22, L49–72, L96–135 (plus grep); `08.67-…` L117–118 (plus grep); `chapter_05/TODO.md` L1–4, L18–21; `05.04-…` L62, L115; `05.06-…` L68; `06.04-…` L31; `01.14-…` L161–164; `BOOK-OUTLINE.md` L184–207; `THEORY-MAP.md` L237–247, L420–427, L443–447;
  - searched only: `09.11-…` (grep "deadlock"); `09.16-…` (grep "20a");
  - read by script: `CONCEPT-REGISTER.tsv` (python status count; awk on homes).
- Tools (heads and argument lines read): `tmp/tools/undefined_terms.py`, `corpus_quote_check.py`, `register_home_check.py`, `inbound_citations.py`.
- Corpus, all on the allowed list:
  - `CORPUS-ALLOWED.tsv` (grep);
  - read whole: S0318, S0319, S0320, S0322, S0323, S0325, S0326, S0327;
  - read in parts: S0317 (L6, 30–36, 68–78, 112–133, 144–166, 174–180, 205–222, 244–260, 275–280, 326–337, 356–361, 463–466, 572–600, 794–810, 896–911, 998–1062, 1086–1164, 1181–1186, 1290–1330, 1376–1392, 1470–1480, 1526–1532, 1565–1572, 1600–1622); S0321 (L34–40, 122, 176–194, 280–284, 402–410, 450–460, 476–480, 508–521, 628–634, 696–834, 878–888, 1055–1061, 1110–1114, 1124–1143, 1214–1222, 1249–1252, 1300–1303, 1362–1367, 1385–1388, 1424–1427, 1446–1546, 1548–1563); S0324 (L1–30, L55–140); S0311 (L150–156, 176–183, 354–366, 380–385); S0309 (L290–302); S0315 (L14–17, 108–112); S0316 (L14–24, 66–69); S0290 (L17, 47, 84, 85); S0305 (L16–26, 36–38); S0190 (L107–117);
  - searched or compared only: S0303 (L35–41; grep over L1–655; grep "projection of meaning"; L656–1292 by diff); S0295 (L1–637 by diff; L1119–1123); S0184 (grep); S0185 (grep).

## Commands and searches
1. `cat` of REVIEWER-NOTE, SCOPE, COMMISSION-C and REVIEWER-INSTRUCTIONS; `ls -R` of the copy; `wc -l` on 08.68–08.72.
2. **Without `--root` (breach):** `--help` runs of register_home_check, undefined_terms, inbound_citations and corpus_quote_check.
3. Lane tools on the copy, all in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons3-c/`:
   - `register_home_check.py --root <copy>` → `cons3-c-r1.txt`;
   - `undefined_terms.py --root <copy> --scope 08.68-08.72,08.00,08.99,08.55,08.57,08.61` → `cons3-c-r2.txt`;
   - the same with `--scope 08.68-08.72` → `cons3-c-r2b.txt`;
   - `corpus_quote_check.py` on the copy's 08.68–08.72 → `cons3-c-quotes.txt` (0 anchors found: the tool expects the [Snnnn Lx] form);
   - my own checker `bin/cons3-c-qcheck.py`, run twice → `cons3-c-qcheck-out.txt` and `cons3-c-qcheck-out2.txt`;
   - `inbound_citations.py --root <copy>` for "§8.68 C3", "§8.71 C4", "§8.71 C2", "§8.69 C2", "§8.62 C4", "§8.72 C5", "§8.72 C1", "§8.71.3", "§8.69 C4", "§8.72 C3" → `cons3-c-r3.txt`.
4. `grep -P` on CORPUS-ALLOWED for S0290, S0295, S0300, S0302, S0303, S0305, S0309, S0310, S0311, S0315, S0316, S0184, S0185, S0190, S0241, S0282 and S0317–S0327.
5. Range reads with `sed -n` and `cat -n` as listed under "Files opened".
6. `diff` of S0303 L656–1292 against S0295 L1–637 → `cons3-c-s0303-s0295.diff` (0 lines).
7. Greps on the copy:
   - "precedent|L78|git|reviews/" in 08.72;
   - "1ef0325c|workflow record creation" in 08.69, 08.71 and 08.72;
   - "S0315 L10x–11x" in 08.71;
   - "V-1|V-8" in 08.67 and 08.66;
   - "deadlock" in 09.11 and "20a" in 09.16;
   - forward-reference counts (§9.19, §9.8, §9.5, §9.4, §9.11, §9.16 R6, §10.1, §9.10, §9.13, CE*, N4-15, T1-17, K3) over 08.62–08.72.
8. `book_search.py` (the copy's) with "--help", and with "--root" plus "deadlock".
9. `python3 -I` register status count; `awk` on register homes.
10. Two shell `cd` calls (into the copy's chapter_08, and /tmp) followed by absolute-path commands; no `||` fallbacks.
