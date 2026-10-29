CONSOLIDATION S0201–S0225: INTEGRATION REPORT

Object: the frozen copy `W/06-theory/manuscript/review/CONSOLIDATION-S0201-S0225/BOOK-UNDER-REVIEW/`. I edited nothing in the repository (`git status` under `06-theory` is clean). Two tool calls ran against the working book by mistake; see §7. No finding below rests on their output.

## 0. Summary (10 lines)
1. **Two of the three reports are not reports (fact).** REPORT-A.md and REPORT-C.md (24,352 bytes each) hold one header line with a stray `".`, then `=====`, then a truncated `find` listing of the copy. They have no findings, no verdicts and no access disclosure. Their stored sha256 values match the files, so they were saved as delivered. I could not establish the cause.
2. Only REPORT-B gives findings to integrate. It covers §9.13–§9.18, which is about 30% of the scope. Everything assigned to A and C has no review (§6).
3. B's 12 findings: 7 confirmed, 5 modified, none wholly rejected. Rejected parts: the L271 item of F2, and the register-row item of F7.
4. **High:** F1 (stale rows in the §9.16 R1 table), F2a (§9.13 L37 states a superseded guard and owner rule inside the definition of D), F3 (D_S and D_W used in a Ded result but never defined), N1 (new: §9.16 R2's identity definition contradicts L319).
5. F6 is modified. The K1 clause is elliptical, not truncated: "within D only if" means iff within D (09.99 L77; 08.17 L75). B's scoping point stands: when numbers are reused, a surviving numbered successor does not reveal a loss (S0300 L438–443; S0206 E-1).
6. New defects in the rotated chapter: N2 (more stale "content tie" lines in §9.16), N4 (a blank line splits the 09.00 index table), N5 (stale 09.00 rows), N6 ("L" means both a legitimacy assumption and a line number).
7. Every line-number citation into §9.13/§9.16 (10.05:228/229/231/318; 09.18:250) is still correct. Every correction must keep line positions, i.e. be edited in place.
8. Concepts without a verdict: 3 VALIDATED (no second verdict), 17 PROVISIONAL and 11 INVALID cores, all homed in §9.01–§9.12.
9. B's R2 judged 77 of the 124 distinct in-scope candidates. 47 were left unjudged.
10. Process independence: this is process independence, not personal independence. I saw no draft of any report. The scratchpad directory is shared with other agents of the session; I saw its file names and opened none of the other agents' files.

## 1. Findings (verified against the copy and sources)
| ID | Verdict | Sev | Ch. | Evidence (fact unless marked Int) | Correction |
|---|---|---|---|---|---|
| A-0 / C-0 (process) | confirmed | critical for coverage | — | REPORT-A/C content as in summary 1. `diff` shows only the letter differs. | Re-commission A and C on the same copy. Find out why a listing was saved as a report. |
| B-F1 + N2 | modified (extended) | high | 9 | 09.16 L66 "(b) outside [D]" vs 09.13 L26 and L270 ("F26 (b) at ties is now in Θ_f").<br>L69 and L80 "content ties" vs L67 "ties unordered".<br>L86 "counts consumptions that are not F" vs 09.15 G4 ("superseded").<br>Also stale, not listed by B: L76 (Q3 "under content ties") and L203 (R4 (iii) "ties are by content"). | Restate L66, L69, L76, L80, L86 and L203 in place, keeping line positions. |
| B-F2 → F2a | modified | high | 9 | 09.13 L37 sits inside the current definition of D. It states that the guard refuses while another consumption "is not F" (superseded by v13, L45). It also states "the 'already consumed?' guard runs on the owner's record", which contradicts §9.16 R4c and the K4 v10 note in 09.15 L77. | Tag both as superseded or restate them, in place. |
| B-F2 → F2b | modified | normal (was high) | 9 | 09.16 L288, L307, L317, L325 and L327 are superseded and untagged. They sit inside dated version blocks (v7 at L279; v5–v6 at L301).<br>**L271 rejected:** it already carries "(Decided after C21 …; R4c.)". | Add "superseded by …" tags. |
| B-F3 | modified | high | 9 | D_S and D_W are used at 09.18 L106–309 and in 08.31/08.33/08.35/08.36; there is no definition anywhere in the copy (scan of all chapter files).<br>**W is glossed** at 09.18 L131 ("the evidence area's (W's)"), so B overstated that part.<br>"Element outside every area" occurs only in 09.18 and Ch. 10 (10.02/10.04/10.05): a forward dependence. | Define D_S and D_W in §9.18.6 at first use. Mark the Ch. 10 terms as forward references. |
| B-F4 | confirmed | normal | 9 | 09.13 L3 status "version 14 … for C22's review" vs L48 v19/v20.<br>L356 "Tool: ce_model.py v15" vs "Model v20, checks 28–32".<br>L331 is an open question already answered at L26 ("model check 1, with v9's order"). | Update in place, keeping L3 as one line. Do not shift L98 or L327 (cited by 10.05:318). |
| B-F5 | confirmed | normal | 9 | 09.18 L3 "v22 (checks 33–36)" vs L193 and L290 (v23, check 37).<br>L44 lists T9-16 as undecided; L78 and L285 say "decided (b)".<br>Stray bold at L154. | As B proposes. |
| B-F6 | modified | normal | 9 | **Not truncated:** 09.99 L77 records "K1 'iff' → K1 scoped to D"; 08.17 L75 has the fuller wording "within D, where every dependency is a citation, also only if".<br>**Scoping gap confirmed (Int):** S0300 L438/L443 (23/30 trials; "the clobbering writer reuses the sequence number"), restated in S0206 L517 (E-1). S0206 restates S0300, so this is one line of support, not two.<br>Within one serialised kernel (K6) numbers are not reused, so K1 holds as scoped. | Make "only if" explicit. Add the premise "numbers are never reused (K6)". |
| B-F7 | modified | normal | 9 | 09.17 L149 "withheld, not promoted" vs L23 and L175 (confirmed).<br>**Register part rejected:** the CON-0264 row already carries the B-01 correction. | Restate L149 only. |
| B-F8 | confirmed | normal | 9 | C4 (L243): G0 = OLD ⊆ NEW blocks on a foreign entry only if NEW does not hold it.<br>The premise appears only implicitly, in L46 ("A foreign entry left behind is not a loss"). | Add "the carry-across copies members only" to the Setting (L227). |
| B-F9 + N1 | modified (one item escalated) | normal; **N1 high** | 9 | Homonyms confirmed: "coupling", "safe direction", "attestation", "owner".<br>**N1:** 09.16 L93 defines identity as "the fields CE* reads … other than the recorder". L319 says "the owner and the application are part of an act's identity" while "Φ itself reads neither". So the identity rule used by R2 (and by PL-4, 10.05:310) has two incompatible definitions. | Qualify the homonyms. Restate L93 to match L319, or the reverse, and say whether R2's order-independence holds with the larger identity. |
| B-F10 | confirmed | normal | 9 | "mover": first use 09.13 L48, gloss 09.17 L166.<br>R4 order is R4 (L195), R4a (L223), R4d (L225), R4c (L249), then v8/v7/v5–v6. R4d cites R4c before it appears. | Gloss "mover" at first use. Add a reading note. |
| B-F11 | confirmed | normal | 9 | 09.14 L157 "on a discrete order" is undefined. | Add a gloss. |
| B-F12 | confirmed (source defect) | normal | 9 | S0300 L1204 "2 of 216"; S0206 L395 "2 (0.9%)"; C39 inventory item 6 "2 of 218". The theory does not rely on the count. | Name the snapshot. |

## 2. R3 (tool run on the copy for CON-0191/0193/0194/0264/0265; grep fallback for line and claim targets)
| Correction | Inbound line | Disposition |
|---|---|---|
| F1 | 10.05:227 ("given cut, equal entry sets, §9.16 R1") | stays true |
| F1 | 10.05:228 (§9.16 L80), 10.05:231 (L79–80) | stay true **if** L80 is edited in place; otherwise they need the same renumbering |
| F1 | 10.05:96, 10.05:310 (PL-4, §9.16 R2) | not affected by F1. **Affected by N1:** they need a different change (name which identity rule is used) |
| F1 | 09.18:250 (§9.16 L79) | stays true; L79 is unchanged |
| F2a/F2b | 10.05:144, 10.05:162, 10.05:308, 10.05:311 (§9.16 R4a–R4d) | stay true; they cite the decided R4a/R4c, not the stale lines |
| F2a/F2b | 08.19:15, 08.19:40, 08.19:43 (K6, L9, R4a) | not affected |
| F3 | 08.31:117, 08.33:87, 08.35:59, 08.36:57 (use D_S/D_W) | stay true; they gain a definition |
| F4 | 10.05:318 (§9.13 L98, L327) | stays true if no line above L327 is added or removed |
| F4 | 09.00:23 (row 9.13 "v8 … for C19's review") | needs the same change (N5) |
| F4 | CONCEPT-REGISTER CON-0191 basis; BOOK-OUTLINE:178 | needs the same change (version and tool); I did not open the basis cell in full |
| F5 | 09.00:29 (row 9.18) | needs the same change (v23) |
| F6 | 10.05:85; 09.17:242 (C3); 08.17:75 | need the same change (explicit "only if"; no-reuse premise) |
| F6 | 08.18:186, 08.18:237 (estate migration; CON-0243) | need a different change. They apply "numbered successor" to the estate, where S0300 shows numbers being reused, so the premise is false there. State the condition, or limit them to kernel-numbered records |
| F6 | CON-0212, CON-0237 rows; §8.14 C5 | not verified line by line; the tool resolves no "K1" target |
| F7 | 09.17:236 (G2 row "classed foreign, preserved, not promoted") | stays true |
| F7 | 08.19:236 (CON-0264) | not affected |
| F8 | 10.02:86, 10.03:99, 10.03:336, 08.26:21, 08.26:54, 08.27:22, 08.27:61, 08.27:64 (CON-0265) | not affected; they cite the gate, not C4's wording |
| F10/F11/F12 | none found | — |

## 3. References to missing claims (REVIEWER-NOTE item 6)
- **F6:** no content is missing. The wording is elliptical, and the full wording exists at 08.17 L75 and in the change log (09.99 L77).
- **F3:** content is missing (no definition of D_S or D_W anywhere). The pointer is not stale.
- **09.16 L327 "(§9.15 K4-o)":** the pointer is stale. K4-o exists but "has no remaining domain of its own" (09.15 L77).

## 4. Conflicts and overlapping corrections
| Pair | Resolution | Reason |
|---|---|---|
| A/C vs B | no conflict can be tested | A and C contain no findings |
| F1 × the line-number citations (10.05:228–231; 09.18:250) | edit in place | the citations are by line number |
| F4 × 10.05:318 | keep L3 as one line; delete or answer L331 only | L327 must keep its number |
| F6 × F8 (09.17 C3, L242) | apply the same K1 premise wording in both | both state K1's detection |
| F7 × F8 (09.17 R3(b) and G2 row) | compatible | G2's "preserved, not promoted" matches the restated (b) |
| F9 (recorder) × N1 | merged into N1 | the same definitional inconsistency |

## 5. Defects in the rotated chapter (Ch. 9)
- **From reviewer B, confirmed:** F1, F2a, F2b, F3, F4, F5, F6 (modified), F7 (modified), F8, F9, F10, F11, F12.
- **From reviewers A and C:** none (no content).
- **New, found while checking:**
  - **N1** (high): the identity rule in §9.16 L93 vs L319.
  - **N2** (high, merged into F1): 09.16 L76 and L203.
  - **N4** (normal): blank line at 09.00 L28 splits the index table. Row 9.18 at L29 falls outside the table.
  - **N5** (normal): 09.00 rows 9.13–9.16 (L23–L26) state superseded versions. B noted this out of scope; I verified it.
  - **N6** (normal): "§9.15 L10" means legitimacy assumption L10, while "§9.16 L79" and "§9.13 L98" mean line numbers. Same notation, two meanings, and line citations break when lines move.

## 6. Coverage gaps (reported, not filled)
**Sections in scope that no reviewer read in full:**
- Ch. 9: 09.00–09.12, 09.99, TODO.md (A's share).
- Ch. 8: 08.31–08.38 and their 08.00 rows.
- 10.02 G7 row.
- BOOK-OUTLINE rotation section.
- THEORY-MAP position, counts and C38–C45 lines (C's share).
- SEQ order of Ch. 8 and Ch. 10, and of Ch. 9 outside §9.13–§9.18.
- Window-inventory coverage of C38–C45 (COVERED/MISSING etc.). B did a keyword scan only; no one did the full check.

**Concepts without a verdict:**
- VALIDATED with no second verdict: CON-0099, 0105, 0111.
- PROVISIONAL: CON-0115, 0117, 0138, 0143, 0149, 0160, 0167, 0169, 0170, 0171, 0173, 0177, 0182, 0183, 0188, 0189, 0190.
- INVALID cores not checked: CON-0100, 0110, 0114, 0116, 0118, 0135, 0136, 0137, 0142, 0148, 0161.

**B's verdicts (reported; I did not re-derive them):**
- CON-0265 VALIDATED (second), with F8 to be applied.
- CON-0192 VALIDATED. B's brute-force script was not shown to me.
- CON-0191, 0193, 0194, 0261, 0264 PROVISIONAL.
- CON-0262, 0263: INVALID cores consistent as restated.

**Mechanical checks missing:**
- R2: B left 47 candidates unjudged: agree, answered, asked, asserted, because, beyond, built, claimed, constitute, consume, cost, define, disagree, discipline, drop, dropped, edge, evaluate, exactly, example, family, fourth, institutional, keep, local, logical, merged, occurred, original, presuppose, refute, remain, return, specification, start, stop, stronger, together, truth, unauthorized, unique, uniqueness, violation, weaker. No R2 was judged for A's or C's scope.
- The quote check was not run on A's or C's sections.

**Access disclosures:**
- **B:** adequate and consistent with the copy. B used `--root <copy>` for R1/R2/R3. My R1 run on the copy reproduces B's result (1 flag, CON-0133). My quote-check total on the copy equals B's (53 found, 0 not found), but B did not name the file path it passed. B listed the names of files in the review folders and the C38 folder, which is borderline under SCOPE §2.3; it disclosed this and opened none of them. All corpus files B read are on the allowed list, and none is excluded by §2.4.
- **A and C:** no disclosure exists. Their listings show copy paths only. Whether any of their work touched the working book cannot be judged.
- **Does any finding rest on working-book output?** No finding of B is shown to. None of mine does.

## 7. Files opened, commands and searches
**My own access deviations (disclosed):**
1. I ran `undefined_terms.py --help` and `register_home_check.py --help` without `--root`. The tools ignore `--help`, so they ran on the default root, the **working book**. I saw the first about 20 rows of the whole-book R2 list and the R1 summary. Both tools were re-run on the copy and nothing above uses the first outputs; the R1 results are identical.
2. I printed SHA256-REPORT-A/B/C.txt (one hash line each), which the commission did not list. I also listed the consolidation folder; I saw the names COMMISSION-A/B/C.md and did not open them.
3. I listed the shared scratchpad and saw other agents' file names. I opened none of those files.

**Files opened:**
- Consolidation folder: REVIEWER-NOTE.md, SCOPE.md, INTEGRATION-COMMISSION.md, REPORT-A.md, REPORT-B.md, REPORT-C.md; SHA256-REPORT-*.txt (see above).
- `W/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- Copy, chapter 09 (line ranges):
  - 09.13: L1–8, 26, 30–50, 98, 265, 270, 325–335, 356–373
  - 09.15: L66, 70–80
  - 09.16: L1–12, 55–92, 196–205, 218–332
  - 09.17: L23, 46, 149, 166, 175, 218–250
  - 09.18: L1–80, 154, 193, 205–245, 250, 285–290
  - 09.14: L157
  - 09.07: L86
  - 09.99: L77
  - 09.00: L23–29
- Copy, other files:
  - 10.05: L85, 96, 144, 162, 225–231, 318
  - CONCEPT-REGISTER.tsv: header; rows by awk
- Python scans over all copy chapter files for "D_S"/"D_W", "mover" and "numbered successor".
- Inventories: `wc` of all eight; grep of C39 STEP1-INVENTORY.md.
- Corpus:
  - CORPUS-ALLOWED.tsv (head, grep, awk for 17 ids).
  - S0206 (sha verified): L393–398, L511–520.
  - S0300 (sha verified): grep hits L438, 443, 1438; L1204.
- Tools: listing of `W/tmp/tools`; inbound_citations.py L1–40; corpus_quote_check.py L1–14. That tool reads `W/02-FILES.jsonl` internally; I did not open it.

**Commands run (all on the copy unless noted above):**
- `register_home_check.py --root <copy>`
- `undefined_terms.py --root <copy> --scope 09.13-09.18` and `--scope 09.00-09.12` (the second for counting only, not judged)
- `inbound_citations.py --root <copy> CON-0194 CON-0264 CON-0265 CON-0191 CON-0193`
- `corpus_quote_check.py` on the copy's 09.13–09.18
- `diff`, `cmp`, `od`, `sha256sum` on the reports
- grep of the copy for: "F26 (b)", "every recorded use", "consumptions that are not F", "content tie", "ties are by content", "Who may transfer is returned", "\"A use\" (returned", "W-6; returned", "already consumed", "What the owner must provide", "R4", "Status", "model v", "element outside every area", "evidence area", "§9.1x … L<n>" line citations, "\bW\b", "recorder", "identity", and the 09.00 table rows
- awk set difference between B's R2 section and the tool's in-scope list
- `git status --porcelain` under 06-theory

Scratch outputs (outside the repository):
- /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/r2_B.txt
- /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/r2_A.txt
- /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/r3.txt
- /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/b5.txt
- /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/inscopeB.txt

Not opened: anything in 00-access/; the working chapters, THEORY-MAP, CONCEPT-REGISTER, BOOK-OUTLINE and BASELINE-REVISIONS; other review-folder files; git history of the manuscript; the sources excluded by SCOPE §2.4. Nothing was sent to any outside service.
