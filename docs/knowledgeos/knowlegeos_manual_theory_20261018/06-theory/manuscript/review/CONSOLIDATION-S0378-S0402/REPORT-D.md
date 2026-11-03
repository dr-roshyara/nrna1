CONSOLIDATION S0378–S0402: REPORT D

Reviewer D. Object: the frozen copy `…/review/CONSOLIDATION-S0378-S0402/BOOK-UNDER-REVIEW/` only. Sections: §8.103–§8.105 and index rows 8.103–8.105 (L118–120); change log `08.99` L141–143; `THEORY-MAP.md` L274–279 and L455–456; `SUMMARIES/08.SUMMARY.md` block 4; three-digit numbering (SCOPE §2.1).

## Summary (at most 15 lines)
1. §8.103–§8.105 mostly hold against S0393–S0402 as read. 13 of 14 bracketed quotations match exactly (the quote check found the 14th). Checks I ran myself: the line counts, including S0394 = 921 lines with no final newline, and the absence claims made by search in §8.104 C1 for R1, R2 and (P).
2. **High, D-01:** §8.103 C2 (L40) quotes S0395 L417 as "A brainstorming lens does create domain authority." The source says "does **not** create". The section contradicts itself at L46, which has it right.
3. **High, D-02:** the plain-language text §8.103.1 item 3 (L22) still says the matrix "disagrees with the adviser's own text". The R3 note at C110 (L59) withdrew that point. R3 was applied to C3 and §8.103.4 but not to the section's own summary of them.
4. Normal defects of fact and count:
   - §8.104 C1 says "seven of fifteen" matrix rows are (P)'s topics. Its own list names eight rows.
   - §8.104 C2 cites the Q-CN lines 2 too high (L2243/2251/2259 for L2241/2249/2257).
   - §8.105 C3 attributes "after compaction and a pasted opening turn" to S0400 L23. Neither S0400 nor S0401 says this.
   - §8.105.3 calls two passages source defects that are not defects (S0398 does have nine families, L21–315; S0399 L636 calls W9 "one additional principle").
5. Normal defects of derivation:
   - §8.105 C1 uses "ativyāpti" in two senses: element placement, and a definition covering the wrong systems (T7).
   - It also states T7 without its premise "s₀ does not meet Req_O".
   - §8.103 C1 says every lens sits in observation, against S0393 L215 and S0394 L1 ("primarily").
6. The change-log rows L141–143 match the text they describe. The map lines L274–279 match their sections, except L278's "mostly", which is stronger than §8.104's "several". The counts on L455 are arithmetically consistent (368 + 25 = 393). L454, the line just outside the named range, is stale: it still gives the position as S0377.
7. Summary block 4 restates settled sections, with three small strengthenings or omissions (D-12).
8. Three-digit numbers: every citation of §8.100–§8.105 in the files I checked names an existing section and claim, and I found none confused with §8.10–§8.15. The index rows and tool sorting are numerical, and `test_section_numbers.py` passes. The index's usage note has no block yet for §8.100–§8.105.
9. Coverage: mostly COVERED. There are four small MISSING items (the coverage table) and two places where W's own inventory claims are DISTORTED.

**Process independence:** this is process independence, not personal independence. I did not write the sections, and I saw no other reviewer's report.

## Findings
"Settl." = settlement text: marked "(Restated at review)", an R3 note, or added at settlement.

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when | Window | Source | Settl./draft |
|---|---|---|---|---|---|---|---|---|---|---|
| D-01 | §8.103 C2 L40 | A quotation with its meaning reversed: "does create domain authority". It contradicts L46 of the same section. | high | S0395 L417: "A brainstorming lens does not create domain authority." Quote check: L40 NOT FOUND. | Restore "does not". | inbound §8.103 C2: claim-level 0; section-only lines (08.99 L141–142; 08.104 L3, L14, L18, L25, L37, L54, L55, L63, L80; 08.105 L3; map L277): none repeats the quotation, so none changes | L40 = source; quote check clean | C109 | S0395 | settl. (C2 restated W-03…) |
| D-02 | §8.103.1 item 3 L22 | Stale text. It still says the matrix "disagrees with the adviser's own text", which the R3 note at L59 (C110 W-13) withdrew. | high | 08.103 L59: "The two 'disagreements' are not borne out" | Add an R3 note at L22, or restate it: one adviser's coding, one commission (§8.104 C1). | map L277, index L118, summary L45: none states the disagreement, so none changes; L73 already noted | L22 agrees with L59 | C109 (R3 at C110) | S0396/S0397 | draft L22 (unannotated) |
| D-03 | §8.103 C1 L31 | "S0393 and S0394 (A) themselves place every lens in observation" is too strong. | normal | S0393 L215 (Ziran: "Adjudication layer"); S0394 L1 "primarily in the observation layer"; §8.103.3 L65 itself reports L215 | "all but Ziran (S0393 L215); 'primarily' (S0394 L1)" | §8.103 C1 claim-level 0; map L277, index L118, summary L42 need no change | wording matches the sources | C109 | S0393, S0394 | settl. |
| D-04 | §8.103 C3 L56 | "the hidden reasoner S0396 fears has no place in D's clauses" reads as an exclusion. §8.104 C3 (W-05) later scoped "no place" to mean "not built". | normal | 08.104 L65 | R3 note on L56 pointing to §8.104 C3's "not built, not excluded". | 08.104 L63 (consistent); index L118 needs no change | note added | C109 | S0396 | settl. (W-14) |
| D-05 | §8.104 C1 L38; §8.104.4 L85 | A miscount: "Seven of its fifteen rows", but the parenthesis maps eight rows. Authority (S0396 L492) and justification (L493) are separate rows. | normal | S0396 L489–503 (= S0397 L3684–3698) | "Eight of its fifteen"; same at L85 | §8.104 C1 claim lines 08.103 L59, L73; 08.105 L3, L15, L68; map L277: none uses the count, so none changes; §8.104.5 L89 is the draft record, kept | count = list | C110 | S0397 | settl. (W-02) |
| D-06 | §8.104 C2 L46, L50, L51 | Line references off by 2: Q-CN-2/3/4 are cited at L2243/L2251/L2259, the "Relevant to" lines. | normal | S0397 L2241, L2249, L2257 hold the questions | correct the three references | none (no inbound line cites these line numbers) | refs = source | C110 | S0397 | settl. (W-08–W-10) |
| D-07 | §8.104 C3 L64 | "D names no accountable supplier for it (CON-0173)" goes beyond CON-0173's meaning (a common, versioned value). | normal | register CON-0173; §10.5 L246 cites it for "a common, versioned value" | Cite CON-0173 for the versioned value only. Home "no named supplier" separately, or mark it Int. | none | wording = register | C110 | none | settl. (W-04) |
| D-08 | §8.105 C3 L53 | "after compaction and a pasted opening turn (S0400 L23 …)": not in S0400 or S0401. | normal | S0400 L23 says only "A Claude restart"; a search of S0400 and S0401 for compact/paste/opening finds only S0401 L139 (the fresh session) | Delete it, or source it to a read file. | map L279, index L120: no change | sourced or removed | C111 | S0400 | settl. (W-05/06) |
| D-09 | §8.105 C1 L30 | (a) "ativyāpti" is used in two senses: the placement half of necessity/placement (an element in the kernel that does not belong, S0398 L46), and T7, a definition covering systems that are not kernels. (b) T7 is stated without its premise "If s₀ does not meet Req_O". Asambhava ⇔ existence is stated without T1's scope (fixed B, G, Req; finite pool). | normal | S0398 L45–47; §9.19.6 L264; §9.19.8 L352 | Qualify the two senses; add the premise and scope. | §8.105 C1: map L279 "T7 as ativyāpti", index L120, summary L53: change with it; 08.99 L143 and BOOK-OUTLINE L209 need no change | senses separated, premise stated | C111 | S0398 | settl. (W-01) |
| D-10 | §8.105.3 L77, L78 | Two "source defects" that are not defects. S0398 has nine numbered families (L21–315) and then a separate lens of ten attacks. S0399 L636 introduces W9 as "one additional principle for the AI platform". | normal | headings of S0398; S0399 L623, L636 | Withdraw both. Keep S0401 L67's (plausible). | §8.105.3 inbound: 0 | withdrawn | C111 | S0398, S0399 | settl. ("W, step 1") |
| D-11 | §8.105 L8, C4 L73 | Line references: S0399-L4 "Governance certification" is at L461, not L462. "S0399-W9, 'assert p only if one knows p' (L531)" joins W9 (L640) with the rule at L531. | normal | S0399 L461, L531, L640 | correct the references | none | refs = source | C111 | S0399 | settl. (W-15) |
| D-12 | SUMMARY block 4 | L49 "mostly land" is stronger than §8.104.1's "Several" (the same word is in map L278). L53 "the definition defects map to the necessity and placement tests" drops asambhava (= existence). L20 "therefore" gives one voice as the reason; for §8.104 the reason is one commission (§8.103 R3 note). | normal | §8.104.1 L25; §8.105 C1 L30; §8.103 L59 | "several"; add asambhava; "one estate, one commission and largely one adviser" | map L278 changes with L49 | wording = sections | C110–C111 | none | metadata |
| D-13 | THEORY-MAP L454 (beside L455) | Stale position: "Last source read in order: S0377; next: S0378", against L455's "Reading position S0402". | normal | L454 vs L455 | Update or supersede L454. | none | one position line | C111 | none | settl. (map) |
| D-14 | 08.00 L122 (usage note) | The note on Ch. 9 constructs used by address and on forward R3 notes stops at §8.82–§8.98. Nothing for §8.100–§8.105: §9.19.8 T1–T2, §9.13.4 R1, §9.18.2, §9.20 Th3–Th4, g1, Θ_f, §9.16 L93, T9-14, T9-17, F6, T0-07; and R3 notes §8.103 C3 ← §8.104, §8.100 C4 ← §8.101, §8.82 C2/§8.98 C4, C6 ← §8.100, §8.88 ← §8.102. | normal | index L122 | Extend the note (the SEQ order of the block). | none | note extended | C106–C111 | none | settl. (index) |

Not findings:
- I could not verify by comparison that S0397 L3196–3835 is identical to S0396 L1–640, because the note bars diff/cmp-like tools. My spot-checks agree at offset 3195 (L3682 = L487, L3403 = L208, L3413 = L218).
- I did not check S0386.

## Task 2: change log and map
- **08.99 L141:** matches the §8.103 header (W-01–W-17, title restated, draft `88fb63622`).
- **08.99 L142:** matches. The R3 notes are at 08.103 L59 and L73, and on map L277. The row omits nothing I found, except that L22 was not annotated (D-02).
- **08.99 L143:** matches. C111 added no R3 notes, and I found no line needing one.
- **Map L274–276:** consistent with the index rows and the claim headings of §8.100–§8.102. L274 homes the third attribution at §8.12 C3 only, while §8.100 C2 also cites §8.10 C5 and §8.12 C2; incomplete, not wrong.
- **Map L277:** matches, and the R3 note is correct.
- **Map L278:** "mostly" (D-12).
- **Map L279:** matches, apart from D-09.
- **Map L455–456:** the arithmetic holds. The count gap of 5 (13 names, the tool reports 8) equals the earlier 3, plus S0365 and S0392. The tool's figure is reported, not reproduced (I did not run `close_source.py`).

## Task 3: summary block 4, line by line
- L20: §8.103 lineage, §8.104 C1, §8.105 lineage (D-12).
- L23–27: §8.100 C1–C5. L29–34: §8.101 C1, C2, C3, C5, C6 (the title), C7. L36–40: §8.102 C1–C5. These match the claim headings.
- L42–45: §8.103 C1, C2, C2, C3.
- L47–50: §8.104 C1, C1, §8.104.1 item 2 (D-12), C3.
- L52–59: §8.105 lineage, C1 (D-12/D-09), C1, C2, C2, C2, C4, C4.
- L60: §8.105 C3.
- L63–65: §8.103.3/C2; map L274–275; §8.105 C3.
- No added result found.

## Task 4: three-digit numbers
- The index rows run L114 (8.98), then L115–120 (8.100–8.105) with no 8.99 row, so they read numerically.
- `book_search` and `inbound_citations` sort numerically, and `test_section_numbers.py` printed "all passed".
- I extracted every §8.100–§8.105 citation in the files I checked (08.00, 08.82, 08.88, 08.98–08.105, map, BOOK-OUTLINE, 08.SUMMARY). Every one names an existing claim: §8.100 C1–C5; §8.101 C1–C3; §8.102 C1–C5; §8.103 C2, C3, §8.103.4; §8.104 C1. §8.100 C4's subagent sentence (S0381 L106) supports 08.105 L55.
- Running R3 on §8.10 returned 08.100 L3, L48 and index L115. Each of these really cites §8.10 C5; none is a §8.100 match taken for §8.10.
- Out of scope, one line: index row 8.100 writes "§9.05", and §10.5 L246 writes "§9.07 L86", a zero-padded form for Ch. 9.

## Task 5: coverage

| Item (inventory) | Status | Home |
|---|---|---|
| C109 S0393 layers, kernel tree, "boring", adjudication, mechanisms, ten lenses, the 26/21 count, SNF in two layers, Ziran in adjudication | COVERED | §8.103 lineage, C1, .3 |
| C109 S0393 Zero and Gödel each in two layers | MISSING (minor) | — |
| C109 S0393 Shi: "situational context as an invariant" (L296) against "would not change the Kernel" | MISSING | — |
| C109 S0394 the Ming rule, REJECTED, Wu, Yin–Yang, the combined test, Part B's overclaim, Part A against Part B | COVERED | C1, .3 |
| C109 S0394 the Ming and NOT-CREATED rules are new domain rules although "the lenses add nothing" | MISSING (minor) | — |
| C109 S0395 four senses, derivation basis, boundary principle, ceiling, taxonomy, status, trivial case, fences, sequence | COVERED (L417 distorted: D-01) | C2, .3 |
| C109 S0395 four non-identities (traceability ≠ atomicity, …, L275–303) | MISSING (minor) | — |
| C109 S0396 items 1–7, 9–13, 15–17 | COVERED | C3, .3; R3 note |
| C109 S0396 representation "compatible with agnosticism" (L303–339); "stop commissioning lenses" | JUSTIFIABLY EXCLUDED (read in full at §8.104) | §8.104 C2, C3 |
| C110 items 1–15; contradictions a–d, f; the state vocabularies | COVERED | §8.104 C1–C3, .3 |
| C110 item 3 [I] "the DOES list supplies the must-do" | DISTORTED in W's inventory; the book (§8.103 C3, W-01) is right: preservations are must-nevers | §8.103 C3 |
| C110 item 17 method rules (L1090–1099, L2203–2219: lens only where it maps to an invariant) | MISSING (minor) | — |
| C110 e: "no tradition supports identity stability" against li "unchanging" (L2780) | MISSING (minor) | — |
| C111 S0398 items 1–13 | COVERED | §8.105 C1, .3 |
| C111 S0398 item 14 and S0399 item 15 (source defects) | DISTORTED (D-10) | §8.105.3 |
| C111 S0399 items 1–10, 12–14, 16 (L4) | COVERED | C2, C4 |
| C111 S0399 item 11 (temporal continuity, L407–427); item 16 (factive against defeasible: status, not truth) | MISSING (minor) | — |
| C111 S0400, S0401, S0402 (all items) | COVERED | C3, C4, .3 |
| C111 cross-table: state proliferation (S0398 against S0399/S0402) | MISSING as a contradiction within the lineage | — |

## Task 7: concepts cited (the copy's register)
- These are used with the ID, status and meaning the register gives: CON-0039 (INVALID, stated as such), 0099 (VALIDATED), 0100 (INVALID), 0105 (VALIDATED), 0125 (PROVISIONAL), 0128, 0132 (PROVISIONAL; the operational reading of §6.5.2 L30 as used), 0183, 0188, 0191 (PROVISIONAL, stated), 0196, 0209 (VALIDATED), 0211, 0212, 0214, 0232 (VALIDATED).
- One exception: CON-0173, meaning stretched (D-07).

## Task 8: R2 (`--scope 08.103-08.105`, 190 candidates, tier A 0)

| Word(s) | Judgement |
|---|---|
| **factive** | Needs a one-line gloss: "knowing p entails p". It carries C2's title, the index row and the summary. |
| **channel** (08.105 L53 "a third channel") | Needs a pointer to the first two channels (F32 / §8.11–§8.12). A possible homonym with CON-0125's "operator as a channel". |
| **manager** (08.105 L21) | An undefined role word in plain language. Use "PO/ARB". |
| hidden-reasoner, reasoner | Defined at §8.104 L61; used at §8.103 L51 and L56 first, glossed in context. Acceptable (SEQ noted). |
| anvaya, vyatireka, concomitance, asambhava, counter-correlate, five-member, justificational, grounding, transmission, transmit, degradation, degrade, defeasibility, defeat, coherence, artificial, testimony, self-certification, certify, believe, recommend, finality, conventional, intrinsic, discrete, linear, representation-agnostic, stripping, stripped, capacity, adjudication, agenda, epistemic-state, unknowability, extrinsic, ignorance, kernel-scope, critic, three-provider, adversarial, convergence, converging, slice, declaration-only, restart, restarted | Source or estate terms, glossed or quoted at first use. No action. |
| luminosity, margin, robustness, foundationalism, intellectual, virtue, explanation, memory, understanding, relativism, separable, unsolved | Reported source topics, not used in a claim. No action. |
| all remaining 130 (capitalized, deception, fenced, four-way, influence, meta-finding, seventeen, undermining, anti-pattern, complementarity, paired, vague, greatest, informed, invention, textbook, threat, attributing, commentary, deepest, filing, imported, softened, typing, apparatu, quantity, acquire, debate, division, ninth, extract, individual, investigate, restating, encyclopedia, reveal, separator, unmapped, defective, describing, listing, misread, progress, expose, moderate, classical, epistemology, fixing, manual, unchecked, headed, went, best, comparing, fill, philosophical, presented, agreeing, caution, confirming, hide, protected, retraction, classed, doubt, combined, relevance, gave, combine, precede, short, toward, along, opening, followed, branche, existed, topic, translation, disclosure, separated, relied, repeated, conversation, directly, heading, missed, normal, sits, quotation, attack, replie, worked, correctly, determination, supplied, judgement, matche, standard, completion, closing, action, called, puts, asked, restate, nine, comparison, quote, filename, share, title, beyond, challenge, reply, ending, opened, quoted, until, once, place, plain, output, leave, adviser, because, keep) | Ordinary English, or terms defined elsewhere in the book (adviser: THEORY-MAP). No action. |

## Coverage of my sections

| Section | Read |
|---|---|
| §8.103, §8.104, §8.105; index rows L115–120 and note L122 | in full |
| 08.99 L136–143; map L272–279, L452–457; 08.SUMMARY in full | in full |
| §8.100–§8.102 | headings, and C2 of §8.100 L42–52 (for map and summary) only |
| Sources | not read whole: only the lines the sections cite, and the searches listed below (S0400 and S0401 in full) |

**Out of scope, one line each:**
- Index row 8.100 writes "§9.05", and §10.5 L246 writes "§9.07".
- R3 tool limit: a section's own plain-language restatement is not found as an inbound line (see D-02).

## Files opened
Each was opened by full path; the corpus files were located through the allowed list.
- Commission and method files: COMMISSION-D.md, REVIEWER-NOTE.md, SCOPE.md (the consolidation folder); 03-methodology/REVIEWER-INSTRUCTIONS.md; 03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md.
- Copy files opened in full or in part: chapter_08/08.103, 08.104, 08.105 (whole); 08.00-index.md (L1–12, L100–125); 08.99-change-log.md (L1–8, L136–145); 08.100 (headings, L42–52, L64–72); 08.101 and 08.102 (headings); THEORY-MAP.md (L270–282, L450–457); SUMMARIES/08.SUMMARY.md (whole); CONCEPT-REGISTER.tsv (header, and grep of 20 rows); 06.05 L29–30; 10.03 L42; 10.05 L246; 09.19 L236–356 (selected lines); copy tmp/tools/book_search.py (head).
- One failed read: copy/chapter_08/08.00-index.md at a wrong path ("does not exist"; no content).
- Lane tools, source read: undefined_terms.py (grep), test_section_numbers.py (head).
- Allowed list and corpus sources: CORPUS-ALLOWED.tsv (head, one grep); S0383 (L64–77, L122–127, L829–832, L1052–1087); S0393, S0394, S0395, S0396, S0397, S0398, S0399, S0402 (the cited lines, via awk/sed); S0400 and S0401 (whole).
- Window inventories: review/C109-S0393-S0396/STEP1-INVENTORY.md, review/C110-S0397/STEP1-INVENTORY.md, review/C111-S0398-S0402/STEP1-INVENTORY.md.
- Nothing from REVIEWER-NOTE item 5 was opened: no working chapter, map, register, outline, summaries, BASELINE-REVISIONS or 02-FILES.jsonl, nothing in 00-access, and no git. No disallowed source was opened.

## Commands and searches run
All tool outputs went to `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons5-d/`.
- **Book search on the copy:** `python3 -I <copy>/tmp/tools/book_search.py` with `--help`; `"8.103"`; `"§8\.105" "8\.10[0-5]"`; `CON-0132`; `"amend the invariant|may amend"`; `"does not execute"`; `10.05`; `CON-0173`; `"9\.19\.8"`; `"minimal kernel exists" "refuse-all"`; `"same identifier" "subagent"`.
- **Quote check:** `python3 -I tmp/tools/corpus_quote_check.py` on 08.103, 08.104, 08.105 of the copy → quote.txt.
- **R1:** `register_home_check.py --root <copy>` → r1.txt.
- **R2:** `undefined_terms.py --root <copy> --help`, which listed the whole book (no `--no-corpus`; read only); then `undefined_terms.py --root <copy> --no-corpus --scope 08.103-08.105` → r2.txt.
- **R3:** `inbound_citations.py --root <copy>` for "§8.103 C1", "§8.103 C2", "§8.104 C1", "§8.104 C2", "§8.104 C3", "§8.105 C1", "§8.105 C3", "§8.105.3" → r3.txt; and for §8.100–§8.105 and §8.10–§8.15 → r3num_*.txt.
- **Section-number test:** `python3 -I tmp/tools/test_section_numbers.py` (temporary book only).
- **Shell commands on the files above:** `grep`, `sed`, `awk`, `wc`, `sha256sum`, `od`/`tail`.
  - Targeted greps: S0397 ranges (admission|admit|gate; admission|represent|linear; KnowledgeClaim|…), "fiat"; S0400/S0401 for compact|paste|opening; S0398 headings.
  - `grep -o` of §8.10[0-5] citations in the 14 copy files named above.
  - Register rows by ID.
  - `mkdir -p` of the scratch directory.
- Nothing else: no globs, no recursive search, no directory listing, no git, no diff or cmp, no edits, nothing copied, nothing sent outside.
