CONSOLIDATION S0328–S0352: INTEGRATION REPORT

Integration reviewer. Object: the frozen copy `…/CONSOLIDATION-S0328-S0352/BOOK-UNDER-REVIEW/` and the files the commission lists. Every tool was run with `--root <copy>` from its first call. **Process breach, disclosed first:** one recursive grep (for `CAP-09` and `DI-5`) ran by mistake from the lane root rather than from the copy, because the shell's working directory carried over from an earlier `cd`. It therefore searched files outside my list: `00-access/` scratch, the working `chapter_08`, and earlier review folders and their old copies. It printed path:line hits only, no content. I used none of those hits, and repeated the search on the copy alone. No file outside the list was opened.

## Summary (≤10 lines)
1. I confirmed 6 high findings and no critical one. **D-01**: §9.19.9 L395 records U1 as "(a) next", while the ruling it quotes says "(b) later". **A-01**: §6.5 holds F22 and F4 open, but both are decided. **F1**: §8.15 C5 states the snapshot premise backwards. **F2**: CON-0239 says "not presupposed by K2", but the restated K2 presupposes it (K2-d). **F-01**: §8.23 C1 inverts the S0159 L297 quote (quote check: 657 found, 1 not found, exactly this one). **F-02**: §8.20's title still asserts the INVALID CON-0252.
2. Of the 56 normal findings, I confirmed most as reported. I modified A-03, A-04, F6 (scope), F-06 (already annotated), F9 and D-02, and merged three duplicate groups: the SEQ note (A-12, F-10, D-05), index staleness (A-13 and B's out-of-scope note) and R1 CON-0133 (A-08 and the other three reviewers' notes).
3. New defects found while checking: two more old-K1 quotes (§8.18 L212, §8.21 L168); "2 of 216 (§8.17 C6)" also at §8.19 L167; CAP-09's first use is at §8.9 L54; A-03's first trivial case carries the same idealisation; §8.15 C5 L66 needs F4's citation fix.
4. All 119 concepts in scope received a verdict (A 44, B 66, C 9). C proposes CON-0254 PROVISIONAL → VALIDATED (within premises). Its proof table is adequate for (ii); (i) rests on an assumption. The decision is for the settlement.
5. Decisions U1–U6: only U1 contradicts the record (fix by edit). D-02's "routed" label is stale. The other U entries match the record. Where the copy adds consequences (U5, U2), they are labelled or easily labelled as Ded. No question to the Principal is needed.
6. Coverage gaps: the SEQ of §8.31–§8.72 was not checked; the 08.99 rows for §8.1–§8.19 were not checked against their text.

Process independence: I am a separate subagent run. I did not author or see the reviewers' working files, and I share the model family. This is process independence, not personal independence.

## 1. Findings
Verdicts: C = confirmed, M = modified, R = rejected. "Verified" means I checked it myself in the copy or the source; "on report" means accepted on the reviewer's stated evidence, not re-derived.

| ID(s) | Verdict | Sev. | Chapter | Evidence (mine) | Correction |
|---|---|---|---|---|---|
| D-01 | C | high | 09.19 L395 | Verified: "Decided: (a) next, before reading resumes" sits beside the quoted ruling "(b) later, after reading resumes". The record addendum says "(b) later". TODO L12 says (b). | "Decided: (b) later, after reading resumes" |
| A-01 | C | high | 6.5 C0, C6 hold, .4 L114–115, .5 L124 | Verified: L115 "F22: … (C0)?" and L124 "On hold until F22 is settled". MAP L126 has F4 decided on 2026-10-20; MAP L141 and L436 and OUTLINE L58 have F22 decided (C+O) on 2026-10-21 | As A: dated decisions with their homes; restate what C6's Hyp rests on |
| F1 | C | high | 8.15 C5 L65; register CON-0224 | Verified: "fails only if … single moment" is backwards ("holds only if"), and "not permanently" is unconditional against the (ii) branch. The source's "permanently" concerns Option A as a threshold (S0117 L209–225). | As B (two parts) |
| F2 | C | high | 8.18 C1; register CON-0239 | Verified: the register says "not presupposed by" K2. 09.15 L75 has K2 as "a single designated record (K2-d) a presupposition", and CON-0261 says "Old K2 follows within one designation". | Date the clause to K2 as worded at C25, as B says |
| F-01 | C | high | 8.23 C1 L44 | Verified: S0159 L297 reads "domain decisions, not technology decisions"; the book reverses it. The quote tool flags only this line. | Restore the source wording |
| F-02 | C (title) / M (L96) | high (title); normal (L96) | 8.20 title, C6 L96 | Verified: the title says "a store cannot record its own close" (CON-0252, INVALID). §9.17 L310 shows the phrase was reintroduced once already. L96 is followed at L98 by its W-02 correction, which is the F6 pattern. | Restate the title. Mark L96 as the superseded draft. |
| A-02 | C | normal | 6.3 L51 | Verified: §6.1 has C1–C8; the lattice is C3. The pointer is stale; no content is missing. | "§6.1 C3" |
| A-03 | M | normal | 8.6 C2 | Verified L43–46. The first trivial case ("B(a)=B(b) → reports nothing") rests on the same idealisation as "exactly" (new) | Qualify both bullets with "when detections give the same output" |
| A-04 | M | normal | 8.1 L21 | Verified S0025 L62–80: the heading says "3 of them contamination". #11 and #12 carry the word CONTAMINATION. #13 is red-marked and "the brief supplied it", but its row does not use the word. | "#11–#13 (the third by the source's count and #13's brief-supplied sequence)" |
| A-05, A-06 | C | normal | 8.8 | Verified L15, L46, L52, L98 (A-05). A-06 on report. | As A |
| A-07 | C | normal | 8.7 L40 | Verified: the book's §0.5 has no §0.5.2 heading | "S0164's §0.5.2" |
| A-08 (= R1 notes in B, C, D) | C | normal | 6.5 C1 tag | R1 rerun: 1 flag (H4), CON-0133 | Add "invalid" to the tag |
| A-09, A-10 | C | normal | 8.5 L3; 8.3 C6 L47 | Verified the texts | As A |
| A-11 | C | normal | 6.6 L66 | Verified: S0913 L1351–1375 holds the five permissions. The pointer is stale; the content exists in the source. | Cite S0913 L1351–1375 and drop "§8.6" |
| SEQ (A-12 + C F-10 + D-05) | C (merged) | normal | 08.00 L99 note; Ch. 6 index | Verified: the note ends at "§8.62–§8.72". Ch. 6 has no note. Spot-checked: K3/K4/F22 in 06.05 and 06.06; K3 in 08.09; G3/L11 in 08.20 and 08.22 | One extension of the note covering §8.5–§8.9, §8.20–§8.30 and §8.73–§8.81 with the reviewers' lists, plus a Ch. 6 note |
| A-13 (+ B's out-of-scope note) | C (merged) | normal | 08.00 L28; 06.00 L3, L17 | Verified: row 8.12 says "before review"; §6.7 is marked "(provisional)" | As A. Also drop "four assurance states" (B) |
| A-14 | C | normal | Ch. 6/8 indexes | On report | One gloss line per index |
| A-15 | C | normal | 8.2 C3; 6.1 C1; 8.6 C4; 6.4 C7 | (c) verified by recomputation: LR 1.5, 1.5, product 2.25, joint 50; "0.857" is unshown. (a), (b), (d) on report. | As A |
| A-17, F-09, F10 | C | normal | reference tables | Spot-checked: §8.7 cites S0056 L60 and S0138 L106, neither in its table; §8.20 cites S0142 L35, not in its table. F10 on report. | Complete the tables |
| F3 | C, extended | normal | 8.17 L76, L104; 8.18 L186 | Verified. Also at 8.18 L212 and 8.21 L168 (new) | Quote the current K1 cell, or date the quote "(K1 before 2026-10-24)" |
| F4 | C | normal | 8.17 C2 L48 | Verified: S0117 L175–178 (E7) against L242 (E6); S0127 L30 follows L242 | A defect inside S0117 |
| F5 | C | normal | 8.18 L9, L107, L196, L217; 8.19 L38, L61, L115 | Verified: 7 "pending S0300" markers; §8.60 L33–40 settles them | Annotate with §8.60 C1 |
| F6 | M | normal | 8.14 L29; 8.15 L41; 8.19 C25 L216 | Verified. Note: C22 and C25 cite CON-0246/0247 as the drafts they bear on; the defect is L216's refuted "must bind an authenticated actor" standing before its L218 correction, not the tags as such | Move the refuted sentences to .4. The tags may stay with "(INVALID; see CON-0248/0249)". |
| F7, F8 | C | normal | 8.13 L72; 8.19 L17 | Verified | As B |
| F9 | M | normal | 8.10; 8.09 | Verified: CAP-09's first use is §8.9 L54, not §8.10 (new). C-5 is used in 08.00, 08.04, 08.09 and 08.10 before the 08.12 glossary. | Gloss at first use (§8.9) |
| F-03 | C | normal | register CON-0257 follow_up | Verified against 08.22 L23, L109 | As C |
| F-04 (+ new) | C | normal | 8.20 L55; **8.19 L167** (new) | Verified: §8.17 C6 says 2 of 210 (L79); 216 is S0142 L242 | Cite S0142 L242 at both lines |
| F-05, F-11–F-15 | C | normal | 8.20, 8.21; CON-0250, 0251, 0257, 0259 | F-12: S0142 L327–329 verified. F-11: the countermodel is logically valid. F-13, F-14, F-15 on report (definitional and interpretive). | As C |
| F-06 | M | normal (low) | 8.23 L127 | Verified: L122 already annotates the line ("describes the state when C12 was written") | Optional inline tag only |
| F-07 | C | normal | Ch. 8 TODO L14 | Verified: the TODO says v18; 08.22 L111 and 08.99 L43 say v17 | One version, with a reason |
| F-08 | C | normal | 08.99 | Verified: no C27/C28 rows; no row cites S0151–S0181 (grep count 0) | Add rows, or point to where that history lives |
| D-02 | M | normal | 09.00 L29; §9.19.9 heading | Verified. The "Copy-ready … (2026-11-0_)" lines are the as-asked record; keep them as history. | Row and heading say "decided" |
| D-03 | C | normal | 8.74 L21, L78, L85; 08.99 L112 | Verified: no R3 note and no scoping in C4 | As D |
| D-04 | C | normal | 8.68 L140; 8.71 L129; 08.99 L114 | Verified: the fail-safe is at L119 and the V-3 homonym at L124. 8.74 has no line pointers (L20, L70). | L119; L124 |
| D-06 | C | normal | 8.78 L86, L90, L68, L76 | Verified: S0344 L603–608 ("self-register;" at L608); "current role…next role" at L926–930. L74 and L137's "L928" are correct. | As D |
| D-07 | C | normal | 8.75 L46 | Verified S0334 L443–459: no-transition or no-selection tests are 1, 3–7 and 11 (seven) | As D |
| D-a1, D-a2, D-a4 | C, on report | normal (low) | 8.80, 8.73 | Not re-checked | As D |
| D-a3 | C | normal (low) | OUTLINE L186 | Verified "Rulebook v1.3 §12" | Add the v1.4 note |
| New N4 | new | normal | 8.15 C5 L66 | "safer ground … E7 discriminating (S0117)" omits S0117's own L242 | Cite S0117 L161–178 against L242 |

## 2. R3 (each correction · inbound line · disposition)
Tool output is in `cons4-i-r3.txt`; other lines come from grep where the tool does not parse the target. Section-only lines were counted, not read one by one, and their disposition is given as a class.

| Correction | Inbound line | Disposition |
|---|---|---|
| D-01 | TODO L12; MAP L431; 08.62 L3 | stays true |
| D-01 | 09.19 L72, L170, L411 ("escalated (U1)") | stays true (history) |
| D-01 | 09.00 L29 | needs a different change (D-02) |
| D-01 | 09.19 L114 ("g1 not placed"; Q4 needs F13) | needs a different change: stale since U2. It lies outside the scope lines; one line, not reviewed |
| A-01 | MAP L141; OUTLINE L58 | stays true (already state the decision) |
| A-01 | 85 and 87 section-only lines (tool) | not affected (they cite the section, not the hold); not read individually |
| F1 | 08.17 L16, L34, L36, L38, L48; 08.23 L117; MAP L345, L444; 08.00 L31, L33; register L219 | stays true |
| F1 | register L225 (CON-0224) | needs the same change |
| F1 | 08.17 L108 | stays true (it asks for exactly this) |
| F1 | 08.15 L66 | needs a different change (N4) |
| F2 | 08.19 L60; 08.20 L96, L246; 08.30 L18, L47; MAP L189; 09.17 L41, L88; 08.22 L56; 08.27 L24; register L262 | stays true. 08.20 L96 changes under F-02, not F2. |
| F2 | register CON-0239 meaning | needs the same change |
| F2 | the other ~30 concept lines and 65 section-only lines | not affected (they cite the distinction; a grep for "presuppos" and "K2" among them found no other quote) |
| F-01 | 08.26 L22, L56, L60; 08.00 L39; MAP L193 | stays true (they use the heading's, i.e. the source's, sense) |
| F-02 | 08.00 L36; 08.20 L58, L98, L246, L263; 09.17 L209, L216, L310; register L253, L255; MAP L191; OUTLINE L229 | stays true |
| F-02 | 08.46 L55; 09.17 L64, L137, L158, L160, L176, L218, L220, L326; register L252, L263 | not affected (they cite CON-0251/0254) |
| F3 | 08.18 L212; 08.21 L168 | need the same change |
| F3 | other lines B lists (08.18 L17, L52…; 08.19 L66; 08.21 L21, L145, L169; 09.08 L104; 10.03 L29, L172; MAP L263) | not affected |
| F3 | 09.99 L62 | stays true (history) |
| F4 | register L225 | needs the same change |
| F4 | 08.15 L66 | needs the same change (N4) |
| F4 | 08.15 L65, L67, L136; 08.23 L117; MAP L345 | not affected |
| F5 | lines B lists | stay true |
| F5 | 08.60 L16, L33–40 | stay true (targets) |
| A-02 | 06.03 L51 only (grep "§6.1 C9": 1 hit) | — |
| A-03 | OUTLINE L41, L148; register L127; 08.23 L26, L112; MAP L278 | stays true ("at least" already) |
| A-07 | none (grep "§0.5.2": 1 hit) | — |
| F-04 | 08.19 L167 | needs the same change |
| F-04 | 08.20 L113; 08.21 L167; 08.31 L75; 09.13 L112; 09.17 L137 | stays true |
| D-03 | 08.75 L20, L68, L106; 08.76 L68 | stays true (already scoped) |
| D-03 | 08.75 L3, L18; 08.76 L3, L19; 08.77 L20, L93 | not affected (dependency or home lists) |
| D-03 | 08.75 L97, L127 | stays true (they are the reason and the record of the fix) |
| D-04 | 08.68 L22, L78; 08.71 L22, L65, L114; 08.72 L17; 08.76 L67; 08.77 L62, L63 | stays true (checked by grep) |
| D-04 | 08.77 L160 | stays true (history of the sweep) |
| D-06 | 08.80 L46 (L608) | stays true |
| D-06 | 08.78 L74, L137 (L928) | stays true |
| All other corrections | as given in the reports | I accept the reviewers' dispositions; I did not re-derive them |

## 3. References to missing claims (item 6)
| Finding | Missing content or stale pointer | Evidence |
|---|---|---|
| A-02 (§6.1 C9) | stale pointer | §6.1 has C1–C8; the lattice is C3 |
| A-07 (§0.5.2) | stale pointer (a source-local number read as a book address) | 00.05 has only "## 0.5" |
| A-11 (§8.6 for S0913's permissions) | stale pointer; the content exists in the source but not at §8.6 | S0913 L1351–1375 |
| F-03 (CON-0256 (i) as home) | stale pointer | 08.22 L23, L109 withdrew the home |
| D-04 (§8.67.3 L118, L123) | stale line pointers | 08.67 L119, L124 |
| D-02 ("routed") | stale label; no content missing | §9.19.9 records all six as decided |

## 4. Conflicts
| Pair | Resolution | Reason |
|---|---|---|
| F-02 (C, high) vs F6 (B, normal), the same pattern | F-02 is high for the title only; L96 and F6 are normal | Each refuted body sentence is followed by its correction in the same claim. The title is uncorrected, and the phrase has already been reintroduced once (§9.17 L310). |
| F2's R3 "08.20 L96 unaffected" vs F-02's restatement of L96 | Both stand | L96 is unaffected by F2 and changed by F-02. Keep its §8.18 C1 citation. |
| F1 and F4, both editing register CON-0224 (L225) and 08.15 L65–67 | Make one combined edit | They touch different clauses: the "not permanently" clause (F1) and the "safer ground (S0117)" clause (F4/N4) |
| A-12, F-10 and D-05, all on 08.00 L99 | Make one combined extension | The same note |
| T8-05: A "partly stale" vs B and C "open, no home" | Open; add pointers | A's hits (§1.16 L87, a definition; §8.26 L48–50, §9.8) are partial and close no item. B and C's misses are not absence. |
| "adviser": A (needs a gloss) vs C (no defect) | Not undefined; a pointer is needed | Defined at MAP L7. The Ch. 8 index uses it first (L57) without a pointer. |
| Verdicts | No concept judged by two reviewers | — |

## 5. Confirmed defects in the rotated chapters
| Reviewer | Ch. 6 | Ch. 8 §8.00–§8.30, 08.99, TODO |
|---|---|---|
| A | A-01, A-02, A-08, A-11, A-13 (Ch. 6 part), A-14, A-15 (b, d), SEQ (Ch. 6) — 8 | A-03, A-04, A-05, A-06, A-07, A-09, A-10, A-13 (Ch. 8), A-15 (a, c), A-17, SEQ (§8.5–§8.9) — 11 |
| B | — | F1, F2, F3, F4, F5, F6, F7, F8, F9, F10 — 10 |
| C | — | F-01 to F-15 (F-10 merged into SEQ) — 15 |
| Integration (new) | — | old-K1 quotes at 8.18 L212 and 8.21 L168; 8.19 L167 "2 of 216 (§8.17 C6)"; CAP-09's first use at §8.9 L54 (unglossed); A-03's first trivial case; N4 (8.15 L66) — 6 |

## 6. Coverage gaps
- **SEQ of Ch. 8 §8.31–§8.72:** no reviewer checked the reading order of these sections, although SCOPE 2.1 asks for Ch. 8's SEQ.
- **08.99:** read in full (C), but the rows for §8.1–§8.19 and L52–L109 were checked against their text by nobody.
- **Meaning-level source anchors:** sampled only, by A, B and C. In §8.73–§8.81, 100 quotes were skipped by D's heuristic script; the lane tool checks 0 there.
- **Concepts:** all 119 have a verdict (28 VALIDATED with a second verdict; 61 PROVISIONAL; 30 INVALID checked as restated). Verdicts that change status or scope, for the settlement: CON-0254 PROVISIONAL → VALIDATED within premises (C); CON-0239 VALIDATED, scoped by dating (B); CON-0108 and CON-0127 VALIDATED only as conditionals (A).
- **D's MISSING inventory items (6, low):** not re-checked.

## 7. Decisions U1–U6 against the record
| Item | What the record decides | What the copy treats as decided beyond it | Question to the Principal? |
|---|---|---|---|
| U1 | T9-02 "(b) later, after reading resumes"; T9-02 waits for a later authorised turn | 09.19 L395 says the opposite, "(a) next" (D-01). TODO T9-02 is correct. | No: an edit. The record is unambiguous. |
| U2 | Q4's grade is g1's class, in the kernel | L394 adds consequences labelled Ded (a kernel reference definition g1 over D_W; the vacuity test decided; R7 (ii)). 09.18 L273 reads "placed in the kernel as Q4's reference", which matches option (a) as asked ("g1 in the kernel"). 09.19 L114 and L170 still say Q4 needs F13: stale. | No. The consequences are deductions to check, not decisions. |
| U3 | (b) kept unfixed; results scoped to (A, Req) | Matches; "(A, Req)" is omitted | No |
| U4 | (d) deferred until §9.5 C1 (a) has a reference definition | "E1 stays an open extension of O1" matches the deferral | No |
| U5 | (a) EF | "§9.5 C5's residue is closed at EF" and "T2, T3 (b), T4 stand" are inferences given without a label. The halting case going to applications was part of option (a) as asked. | No. Label the inferences Ded. |
| U6 | (a) one requirement per act kind; D clauses later (T9-21). Uniqueness holds in the toy model only. | Matches: "one minimal kernel, in the toy model only; no minimal kernel of KnowledgeOS is stated, §9.19.8". §9.19.8 states "not stated". T9-21 says "a later theory turn, when authorised". 09.10 L105 matches. | No |
| D-02 | All six decided 2026-11-01 | 09.00 L29 and the heading say "routed"; TODO T9-01 L11 says "routed U1–U6" (out of scope, one line) | No: an edit |

## Files opened
- Review folder: INTEGRATION-COMMISSION.md, REVIEWER-NOTE.md, SCOPE.md, REPORT-A.md, REPORT-B.md, REPORT-C.md, REPORT-D.md.
- `03-methodology/REVIEWER-INSTRUCTIONS.md`.
- Copy, by sed, grep or Python (line reads): 09.19 L114, L348–400; 09.00 L29; ch. 9 TODO L1–4, L10–13, L31; 09.10 L20, L75, L105; 09.18 L82, L273; 09.15 L74–75; 09.17 L88, L310; 09.99 L62; THEORY-MAP L7, L126, L141, L189, L246–257, L429–436; BOOK-OUTLINE L58, L186–209; CONCEPT-REGISTER rows CON-0218, 0224, 0239, 0246–0249, 0254, 0257, 0261; 06.00 L1–6, L17; 06.01 claim headings; 06.03 L51; 06.05 (grep); 06.06 L47, L66; 00.05 headings; 08.00 L28, L36, L39, L99–115; 08.01 L21; 08.03 L47; 08.05 L3; 08.06 L40–48, L68; 08.07 L40 and its references; 08.08 L15, L46, L52, L98; 08.13 L72; 08.14 L29; 08.15 L41, L62–68, L134–137; 08.17 L46–49, L76, L79, L104; 08.18 L35–40, L186, L212 and the pending markers; 08.19 L17, L121, L181–183, L213–218, L237, L256–257 and the markers; 08.20 L1, L55, L94–101, L114, L120, L212 and its references; 08.21 L168; 08.22 L23, L109, L111; 08.23 L40–45, L122, L127; 08.60 L33–40; 08.67 L117–124; 08.68, 08.71, 08.72, 08.74, 08.76, 08.77 (lines with §8.67.3); 08.74 L1, L21, L78, L85 and greps; 08.75 L46; 08.78 and 08.80 (lines with L60x/L92x); 08.99 L30–52, L110–118; ch. 8 TODO L14.
- Corpus (allowed list): S0025 L60–82; S0117 L170–180, L209–244; S0127 L30; S0142 L229, L234, L242, L246, L327–329; S0159 L297; S0183 L155–164; S0334 L440–460; S0344 L598–610, L922–931 (and grep); S0913 L1349–1376. CORPUS-ALLOWED.tsv (header and awk lookups).

## Commands and searches
- `ls` of the copy, its chapters and tools, and the lane's `tmp/tools`; `mkdir` of the scratch folder.
- `register_home_check.py --root <copy>` → `cons4-i-r1.txt` (1 flag).
- `corpus_quote_check.py` on the copy's 06.0*, 08.0*–08.2*, 08.30, 08.73–08.81 → `cons4-i-quote.txt` (657 found, 1 not found).
- `inbound_citations.py --root <copy> --help` (treated as a claim; 0 lines), then for §6.5 C0, §6.5 C6, §8.15 C5, §8.18 C1, §8.23 C1, §8.20 C6, §8.74 C4, §8.17 C2, §8.6 C2, CON-0224, CON-0252 → `cons4-i-r3.txt`.
- Python script `cons4-i-cov.py` (concept verdict coverage against SCOPE §2.2).
- Python context extractions on copy lines, as listed above.
- Greps on the copy for: "external anchor", "outside commitment that covers", "pending S0300", CAP-09, C-5, DI-5, "U1", "§6.1 C9", "§0.5.2", "2 of 216", "cannot record its own", "adviser", "F22|F4|on hold", "K3|K4|F22|CE", "G3|L11", "S0913", "0.857", "not yet in the book", "S0056|S0138|S0145", "S0142 L35", "S0151–S0181", "presuppos|K2" (in the R3 output).
- **The breached search:** `grep -rn 'CAP-09' .` and the DI-5 grep, run from the lane root (path:line output only; not used; repeated on the copy).
- No git command, and no book_search call. Nothing in the repository was edited.
- Scratch files (outside the repository), in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons4-i/`: `cons4-i-r1.txt`, `cons4-i-quote.txt`, `cons4-i-r3.txt`, `cons4-i-cov.py`.
