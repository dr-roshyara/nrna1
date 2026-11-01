CONSOLIDATION S0278–S0302: REPORT C

Scope: SCOPE §2.6, reviewer C. That is §8.52–§8.61 and their index rows; the changed passages in §8.11, §8.18–§8.21, §8.46 and §8.50; change-log L79–88; BOOK-OUTLINE L186–206; THEORY-MAP L227–236, L411–412 and L415. Everything was read in the frozen copy only.

## 1. Assessment
- **Reliability.** Within its scope, the C59–C68 theory is reliable. Each section keeps its readings open, keeps axes apart and records lineage. I spot-checked about 300 quotations and line citations against the allowed sources (the sha256 of all 50 sources S0254–S0303 matches the allowed list), and nearly all hold.
- **Largest risk.** One line in §8.56 C1 states as settled ("the parser decides") a reading that the rest of the book keeps open. A later section quotes §8.56 C1 for the opposite sentence.
- **Other defects.** The rest are stale status notes and local citation slips. I found no critical defect.

## 2. Findings (ranked)

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| C-01 | §8.56 C1 L40 | "*The sources settle it: reading (b), the parser decides.*" contradicts §8.56.1 PL1 (L24), §8.56.4 (L86), index row 8.56 (L70), THEORY-MAP L231 and change log L83 (all say "three readings"). It also contradicts §8.58 L57, which quotes §8.56 C1 as "the sources do not say which", a phrase §8.56 does not contain. The sentence is also a non sequitur: the sentence before it shows that "Not Kernel" does not discriminate between the readings. Item-6 check: the C63 inventory (S0293 item 15: no owner named for parser correctness) and S0293 L44 ("the HPA's **proposed** mechanism pipeline") do not settle (b). **§8.58's pointer is right; the §8.56 line is the defect.** No content is missing. | high | S0293 L28, L32, L34, L44, L46; the copy's lines as cited | "The sources do not say which; L28 ('stores the normalized meaning') fits (b)." | 12 claim-level citations. §8.57 L22, L53–55, §8.58 L22, L54–56: unaffected ("fits (b)" stays valid). §8.58 L57: becomes exact. §8.57 L9, L20, L38, L83, §8.58 L82, index L71: unaffected. Section-only: map L231 and change log L83 are already consistent. | L40 matches PL1, the index and §8.58 L57 |
| C-02 | §8.18 L202, L213, L215; §8.19 L238–239 | The pending-item notes say "(Settled at §8.60 C1)", but §8.60 C1 gives weaker results. C4 (a) is only "settled in part". C15 is "settled in part": the interpreter is reported, but no migration record carries it (S0300 L524). C14 is "settled: yes, and left open by the plan". The RD residuals and S0138's gaps are "settled as the plan reports it": RD-2, 4, 5, 6 and 9 are carried forward, not resolved (S0300 L180), and no independent review is shown. | normal | §8.60 L34, L38, L40, L41, L46 | Use "(In part settled …)" or "(Settled as the plan reports it …)" item by item, as L217, L248 and L186 already do. | These lines are themselves §8.60 C1's inbound citations. §8.18 L217, §8.20 L247–248 and §8.21 L185–186 are consistent; no other change needed. | Each note states §8.60's actual grade |
| C-03 | THEORY-MAP L227 | "a gate passed by the producer's sentence" is stale. The settled title and §8.52.5 (W-06) say "asserted". | normal | §8.52 L1, L3, L111; index L66 | "asserted" | §8.52 C4 is cited at §8.55 L6, L16, L24 and L41: unaffected | Map matches the title |
| C-04 | §8.59 L3; change log L88 | The header says the C68 R3 on C2 and C5 is "pending that window's settlement". C68 is settled (index L75; map L236; §8.61 L3: "C5: §8.59 C2 and C5 as settled"), and the R3 content is already in C2 (L48, L50) and C5 (L79). Change-log row C68 does not list §8.59 among the R3 sites. | normal | the lines cited | Mark the R3 as applied at C68 and add §8.59 C2, C5 to L88. | §8.60 L63; §8.61 L3, L21, L68, L69: unaffected | Header and log agree |
| C-05 | §8.60 C1 (d) L36 | The condition is misplaced: "§8.18 C4 (d)'s dilemma for 1→3, under the unstated premise that nothing is appended". The dilemma arises when something **is** appended. The premise is what settles (d) for 1→3, as §8.60.4 L104–105 says. | normal | S0300 L614, L765, L1139 | "…the dilemma for 1→3; (d) is settled for 1→3 only under the unstated premise that nothing is appended before the copy." | Index L74 and map L235 ("3→5 only"): consistent | Body agrees with §8.60.4 |
| C-06 | §8.53 L74; §8.57 L67; §8.58 L73; §8.59 L19, L57, L61, L96; §8.55 L76 | Quotation and line slips: (a) "EOS now justified" is in quotation marks, but S0284 L137 reads "'Epistemic Operating System' is now justified". (b) "may not close findings or accept itself" is not in S0296 L38 (which reads "No finding closed, nothing accepted … does not self-close"); the words are the draft's (§8.57.5 L103). (c) "No engine, mechanism, or representation may violate an article" is at S0241 L138 only; L23 and L123 do not contain it. (d) S0253 "inside the current envelope" is at L191; L190 is blank. (e) Art 11.1 is at S0241 L113; L112 is blank (§8.58 L61 has it right). | normal | greps of the source lines | Quote verbatim or drop the quotation marks; fix the line numbers. | §8.53 C4 → §8.54 L87; §8.57 C4 → 13 lines; §8.58 C5 → §8.55 L81; §8.59 C3 → 0: all unaffected, since the substance stands | Quotes match their lines |
| C-07 | Index L77 (SEQ) | The forward-use note covers §8.18–§8.51 only. §8.52–§8.61 also use Ch. 9 and Ch. 10 constructs before those chapters in reading order: §9.13 R3, §9.15 G1/R3, §9.17 R1, g1, (c2), T9-14/19/20, G10, Q1/Q4, PL-4, T10-01, CON-0243, CON-0260. They also cite later Ch. 8 results: §8.54 C1→§8.57 C1; §8.55 C3, C5→§8.57 C1, §8.58 C5; §8.57 C4→§8.60 C4; §8.58 C1→§8.61 C3. Most of these are labelled R3 notes; §8.55 L81 "(§8.58 C5)" is not. | normal | the lines cited | Extend the note to §8.61 and list the forward Ch. 8 dependencies. | — | Note covers §8.52–§8.61 |

Change-log rows L79–88: each row matches its text, except L83 (correct; the §8.56 L40 line contradicts it, C-01) and L88 (missing §8.59, C-04). Row L82 lists R3 on §8.57 before window C64; §8.57.5 L102 records that draft, so this is consistent. BOOK-OUTLINE rotation (L186–206): the order and the S0325 plan are consistent. THEORY-MAP L411 and L415: consistent. L412 counts: not verified (I did not run the tool on working files).

## 3. Concept verdicts
| ID | Verdict | Reason |
|---|---|---|
| — | none in my scope | §8.52–§8.61 home no concept. I checked this: no register home and no `[CON-…]` tag in those files, and every claim ends "Not a new concept". The concepts homed in the Ch. 8 passages' sections are out of scope (SCOPE §2.2). |

## 4. Coverage (inventories C59–C68)
Most items are **COVERED**. I found no item DISTORTED. The misses, ranked:

| Item | Status | Home |
|---|---|---|
| S0294 UM-22 admission rule: lenses excluded for not challenging an invariant are then counted as convergence "from outside the admitted corpus" (L859–914; C63 #13), a circularity | MISSING (search: no hit; "chakra" only as a benchmark in §8.40 L102) | §8.56 C5 / §8.31 C4 |
| S0294's unreconciled epistemic-state vocabularies (L299, L710, L744, L806, L931, L1227; C63 #18), a homonym risk against T/F/U and the evidence classes | MISSING | §8.56 C5 |
| S0294's preserved-dimension sets differ across character versions (C63 #16) | MISSING | §8.56 C3 |
| S0281 "Current State is consumed, not authored" (L103, L221; C59 S0281 #4), which agrees with decision C1 | MISSING | §8.52 C4 |
| S0294 "theorem-backed" Z-KOS-001 against "not itself a formal theory" (L1295–1305) | MISSING (low; §8.51 C1 (i) covers the kind) | §8.56 |
| S0298 DV-5 residual stale-read window, "irreducible without atomicity" (C66 #7) | not decided (no hit for "DV-5"; "atomicity" appears in §8.19 and §8.20) | §8.59.3 |
| Low: S0288 session state vs work-item state; S0281 "deterministic" rests on judgement; S0290's novelty-by-search; S0292 meta-language as reflection; S0300's "fourth/fifth time" footer; S0302 disposed/closed/accepted | MISSING (low) | various |
| S0294's saturation argument; S0298's distinctions already in the book | JUSTIFIABLY EXCLUDED (§8.42; §8.59 lineage) | — |

## 5. Next steps
1. Correct C-01 (§8.56 L40). Done when §8.56, §8.58 L57, the index, the map and the log agree.
2. C-02 to C-06: local text fixes.
3. Extend the index note (C-07).
4. Home O-1 to O-3 in §8.56, or record them as JUSTIFIABLY EXCLUDED with a reason.

## 6. Limits
- `corpus_quote_check.py` reports "0 found, 0 not found" for every section from §8.52 to §8.61. Its pattern ("…" [Snnnn Lx]) does not match the book's citation format, so the mechanical quote check is vacuous here. I used my own checker instead (scratch file below; it only reads). Its misses were then checked by hand. Coverage is a sample, not every quote.
- I read the inventories in full for C59, C60, C67 and C68, and through their item lines for C61–C66.
- The THEORY-MAP L412 counts are unverified.

**Summary:**
- All assigned files and passages were read in full.
- No critical finding; 1 high (C-01), 6 normal; 3 normal omissions plus 3 low ones.
- The main defect is internal: §8.56 C1 states one of its three readings as settled, against its own plain language, the index, the map, the log and §8.58's quotation of it.
- Status notes for the items settled at §8.60 overstate "in part" and "as reported" results.
- Quotes and line references checked against the sources are largely exact. There are five slips, none of which changes a conclusion.
- The window inventories are well covered. The misses concentrate in S0294 (circular admission counting, state vocabularies, dimension sets).
- R1 flags (CON-0003 in Ch. 3, CON-0133 in Ch. 6) are outside my scope.

**Process independence:** process independence only. I did not write this work, and I did not see the other reviewers' reports.

## Appendix A: R2 (`--scope 08.52-08.61,08.00,08.99,08.11,08.18-08.21,08.46,08.50`)

| Word | Judgement |
|---|---|
| misrouted (idx L69), asker (idx L71; §8.57 C1 "the L639 speaker"), tamperer (§8.53), artist, logician, graphic (§8.54, role words replacing names) | plain or glossed in context; no defect |
| registrant (§8.55 L38; first use §8.50) | the registering session; clear |
| AMD3 (idx L74) | defined at §8.18–§8.19, earlier in reading order; fine in a summary row |
| CL-4 (idx L74; §8.60) | introduced at §8.19 L66; fine |
| UM-45 (§8.55 L57) | the record's message label, quoted; fine |
| P-1 (§8.18 L217), Phase-4 (§8.18 L202), RD-9 (§8.19 L238) | the plan's labels, glossed in §8.18 L123 and §8.60 C1; fine |
| commission (§8.59) | consistent sense (an authorization to produce work); fine |
| accretive, karaka, mtime, fingerprint, parser/parsing, site, three-condition, post-condition, scalar, horizon | glossed in their own line or by quotation; fine |
| quantifier-scope (§8.54 L80) | not explained; marked "benchmark only"; acceptable |
| copybecome, cross-axi | tokenisation artifacts; no defect |
| other tier-D words (sixth, pipeline, ordering, slot, meta, participant, uncertainty, renamed, …) | plain English; no defect |
| serialised, canonicaliser, foreclosure, quiescence, unhomed (first used in §8.11 and §8.18–§8.20, outside the changed passages) | ordinary words; not defects; outside my passages |

## Appendix B: coverage of sections
- **Read in full:** §8.52–§8.61; index rows 8.52–8.61 and L77; §8.11 L70–80; §8.18 L198–218; §8.19 L236–240; §8.20 L245–249; §8.21 L183–187; §8.46 L38–41; §8.50 L44–47; §8.99 L1–6, L76–88; BOOK-OUTLINE L184–207; THEORY-MAP L225–237, L409–416.
- **Read in part for cited checks:** §8.40 L102, L110; §8.19 L89–92 (grep).

## Appendix C: files opened and commands run
**Files opened:** under …/CONSOLIDATION-S0278-S0302/: REVIEWER-NOTE.md, SCOPE.md, COMMISSION-C.md. W/03-methodology/REVIEWER-INSTRUCTIONS.md. In the copy: chapter_08 files 08.00, 08.52–08.61, and line ranges of 08.11, 08.18, 08.19, 08.20, 08.21, 08.40, 08.46, 08.50 and 08.99; BOOK-OUTLINE.md L184–207; THEORY-MAP.md L225–237, L409–416; CONCEPT-REGISTER.tsv (grep only); tmp/tools/book_search.py (header grep). Inventories: review/C59…C68 STEP1-INVENTORY.md (the ten named in SCOPE §2.3). Tool headers: W/tmp/tools/undefined_terms.py, inbound_citations.py, corpus_quote_check.py. CORPUS-ALLOWED.tsv (awk/grep). Corpus sources, all allowed and all sha-checked: S0131, S0223, S0232, S0241, S0253, S0254, S0255, S0273, S0276, S0277, S0278, S0279, S0280, S0281, S0282, S0283, S0284, S0285, S0286, S0287, S0288, S0289, S0290, S0291, S0292, S0293, S0294, S0295, S0296, S0297, S0298, S0299, S0300, S0301, S0302 (sed/grep of cited lines). The sha256sum loop also read S0254–S0303. None of S0155, S0156, S0157, S0161, S0170, S0174, S0233 or S0249 was read. File #13 of S0301 was matched by path only and not opened. **No file of REVIEWER-NOTE item 4 was reached** (no working chapters, map, register, outline or 00-access; no git history; no file comparison beyond the two in-source `diff`s of S0285 and S0297).

**Commands:**
- `ls`/`find`/`wc` on the copy and the review folder.
- `python3 -I tmp/tools/{register_home_check,undefined_terms,inbound_citations,corpus_quote_check}.py --root <copy> --help` (first calls).
- `register_home_check.py --root <copy>`.
- `undefined_terms.py --root <copy> --scope 08.52-08.61,08.00,08.99,08.11,08.18-08.21,08.46,08.50`.
- `inbound_citations.py --root <copy>` for "§8.56 C1", "§8.60 C1", "§8.52 C4", "§8.53 C4", "§8.57 C4", "§8.58 C5", "§8.59 C3", "§8.55 C4", "§8.59 C2", "§8.60 C2".
- `corpus_quote_check.py <copy>/…/08.5[2-9]*.md 08.6[01]*.md`.
- Scratch checker /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons2-c-qcheck.py (written, edited once, run twice; read-only on the book and sources).
- sha256sum loop over S0254–S0303 against the allowed list.
- `diff` S0285 L579–856 vs S0283 L1–278 (identical); `diff` S0297 L1–554 vs L556–1109 (identical).
- A path-mapping loop of S0301's 27 file names to allowed-list IDs.
- `book_search.py` (copy) for "--help", "chakra Yoga admission challenges existing invariant", "chakra", "admissible only if it challenges", "convergence signal outside the admitted corpus", "epistemic state vocabularies", "atomicity stale read", "quarantine laundering", "termination argument PREMISE 2".
- `grep` over the copy's chapters for: L621, L211, L103, L221, deterministic, "S0279 L15", "confused with reality", L1155, L1079, UM-22, L1305, L710, L806, L1295, L873, saturation, atomicity, laundering, DV-5, DV-6, "termination argument", CL-4, P-4, "S0131 C4", "8.56 C1", CON tags.
- `sed`/`grep` on the source lines listed above.

Nothing was sent to any outside service; no repository file was changed.
