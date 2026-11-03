CONSOLIDATION S0378–S0402: REPORT A

Reviewer A. Object: the frozen copy `BOOK-UNDER-REVIEW/` only. Tasks 1–7 of COMMISSION-A. The window-size fields of SCOPE §2.7 do not apply: none of my findings is in §8.100–§8.105.

## Summary (15 lines)
1. One finding is high and comes from the 2026-11-03 changes. §10.2 L115 (consequence 4) says "the relocation test makes it necessary there". Both sources it cites say the opposite: §9.18 L273 and §8.90 C4 L51 ("placement in the kernel is the Principal's decision (U2) …, not a reason the book derives").
2. The second high finding is the closure of T10-20 "as answered by U2". U2 decides kernel placement (§9.19.9 L396, options (a) kernel against (b) application). T10-20 asks for a subdomain class, and §10.1 stipulation 2 (L13) says "No class below is decided by kernel or application membership". §9.18 L210 also leaves the classification to T10-20. The class of g1 is left undecided. This needs the Principal's reading, not a text fix.
3. Consequences 1–2 put "the kernel" in the enforcer column of §10.3.3 (L115) and in the owner list (L176). Neither is a candidate or an operation, so placement and ownership are run together. 10.SUMMARY L27 repeats the wording.
4. Several rows of §10.2–§10.3 are still stale against decided Ch. 9 results:
   - G2 and G4 are decided (§9.18 L77, L79), but §10.2 L102, the G2 and G4 rows (L215, L217) and §10.3 L118 do not say so.
   - "who applies g1 is not placed" (§10.3 L114) is stale since U2.
   - The G7 row (L220) still lists Q4's grade, which U2 has decided.
   - "the correcting act is not a kind of D" (L113): correction is standing's annulment (§9.18).
5. The T10-19 qualification is addressed to "§10.3.4 L292–293" (TODO, change log L25, SCOPE). Those lines are in §10.3.6. §10.2 L5 and §10.4 L18 still say "not contexts" without the qualification.
6. Candidate 6's only exclusive rule, CON-0141, is called "a method rule" (§10.3 L157). The copy's register and §8.8 C1 define it as a reader/fold lemma about derived values, and BOOK-OUTLINE L150 marks it a kernel candidate. Rule (B) also treats 6 differently from 4 on the same inputs.
7. Two rows contradict others: §10.3 L7 ("five, mutually exclusive") against L63 and the 10.00 index ("not mutually exclusive"); T10-21's State says "Resolved" although item (5) is open.
8. The BOOK-OUTLINE rotation table (L186–212) matches the copy's records. Every cited concept ID and status matches the copy's register.
9. R1 flags nothing; the quote check finds no quotation markers (0/0) in my files; R2 shows tier A 0 and 78 tier-D words, none a defect.
Process independence: independence here is process independence. I worked from the frozen copy and the allowed sources only, and saw no other reviewer's report.

## Findings

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|---|
| A-01 | §10.2 L115 (consequence 4) | "the relocation test makes it necessary there" is inverted. The relocation test shows g1 *can* sit in an application under one version g; kernel placement is the Principal's decision. The line is a misattributed Ded and contradicts itself | high | §9.18 L273 ("By the relocation test (§9.6.4) it can sit in an application if every reader uses one version g"); §8.90 C4 L51 | "Placed in the kernel by the Principal's decision U2 (2026-11-01). It is not derived: by the relocation test g1 could sit in an application if every reader used one version g (§9.18 L273; §8.90 C4)" | no tool hits on §10.2.x (the tool does not parse Ch. 10 sub-sections); grep: 10.SUMMARY none → no change | the line restated, and the earlier wording kept |
| A-02 | TODO T10-20; 10.99 L25–26 | The closure equates kernel placement (U2) with the subdomain class (core or supporting). §10.1 stipulation 2 forbids that. The class of the rule g1 is not decided: §10.1 row 2 names only "showing" (core) and the "assessment methods" (supporting), and T10-21 (5) carries only the *display* question | high (normative; for the Principal) | §9.19.9 L396; §10.1 L13, L55, L85–86; §9.18 L210 ("the classification is T10-20's") | Question for the Principal: does the closure also decide g1's class? If yes, say so in T10-20 and in §10.1 row 2. If no, add "the class of g1" to T10-21 (5) | 09.18 L192, L210 (cite T10-20): update the pointer to wherever the class question goes; 10.SUMMARY L47: restate | the Principal's answer is recorded |
| A-03 | §10.3 L115, L176 (consequences 1–2) | The enforcer column holds "g1's class, in the kernel". That is a reference definition, not a candidate plus an operation (§10.3.2 L61). L176's "Q4's answer is the kernel's" mixes placement with the R-b owner, which row B names as 2 | normal | §10.3.2 L57, L61; §9.18.8 row g1 (owner W; enforced by "—") | L115: "2 (O: g1 over D_W, computed by the kernel's step, U2)". L176: "Q4's owner is 2 (row B); its reference is g1, placed in the kernel (U2)" | 10.SUMMARY L27: restate | the rows restated |
| A-04 | §10.3 L114 | "who applies g1 is not placed (§9.18 L273)" is stale. That line now reads "placed in the kernel … U2". Wherever the kernel computes Q4, the one-sidedness guarantee now has a point where it is applied; the display duty (PL-14) stays open | normal | §9.18 L273 | "g1 is applied by the kernel (U2); the display duty has no enforcing context (PL-14)" | none found | restated |
| A-05 | §10.2 L102; §10.2.3 G2 row (L215), G4 row (L217); §10.3 L118 | G2 is decided (T9-17 (b); g1 adopted, Q-B) and G4 is decided (T9-12 (c); withdrawal T9-16 (b)). None of these lines is marked, while G1 is | normal | §9.18 L77, L79, L209 | Annotate each line as decided, in the style of the G1 row | 10.04 L58 (G4, "W asks S whether … authorised"): consistent, no change | annotated |
| A-06 | §10.2.3 G7 row (L220) | The row still lists "Q4's grade" as open. U2 decided it, and §10.2 L114 already says so | normal | §9.18 L82; §9.19.9 L396 | Mark the Q4 part decided (U2); keep the F13 part and the source-kind part | 09.18 L82 cites "§10.2 G7 row": consistent after the change | annotated |
| A-07 | §10.3 L113 | "the correcting act is not a kind of D (G1)" is stale. Correction is standing's annulment, a kind of D, and withdrawal is ewdr | normal | 09.00 index row 9.18 ("correction is standing's annulment"); §10.3.7 L339 | "correction is annul (standing's); withdrawal is ewdr (W's)" | none found | restated |
| A-08 | §10.3 L105 note (consequence 6) | The note says "register and link are among O1's kinds without a D clause". By T9-21, establishment is outside D too, so all three of the row's O1 kinds are outside D. O4 (root) is in D, so the trivial-solution check still fails through O4; the note does not say this | normal | TODO T9-21; §9.13 L52; §9.19 L96 | "register, link and establish are outside D (T9-21); the trivial-solution check holds through O4 (root) alone until T9-21's clauses exist" | none found | restated |
| A-09 | TODO T10-19; 10.99 L25 (and SCOPE §2.1) | "§10.3.4 L292–293" names the wrong section: the table is in §10.3.6. §10.2 L5 and §10.4 L18 still carry the unqualified "not contexts" | normal | 10.03 L246, L284–293 | Readdress the pointer to §10.3.6; add the qualification at §10.2 L5 and carry it to §10.4 L18 | 10.04 L18; 10.SUMMARY L46 | readdressed and qualified |
| A-10 | §10.3 L157; §10.3.4 L218; §10.3.6 L292 | CON-0141 is read as "a method rule" that 6 owns. In the register and at §8.8 C1 it is a reader/fold lemma about derived values (a kernel candidate, BOOK-OUTLINE L150). Under R-b its terms are 1's. Separately, row 6 (an exclusive rule, no owned kind) gets "not a context", while row 4 on the same inputs gets "not admitted today" | normal | register row CON-0141; 08.08 L31; BOOK-OUTLINE L150 | Re-run R-b for CON-0141 (owner 1, or shared). Then give candidate 6 its result either with no exclusive rule (as 7 and 8) or as "not admitted today" (as 4) | 10.04 L73 (R20, "owns non-consultation (CON-0141)"); 10.03 L25, L242: review | owner restated |
| A-11 | §10.3 L29, L172 | §8.17 C5 (CON-0237, the authority-record merge rule R-CONFLICT) is excluded as a "rule of the estate's review and governance process". It is a rule of the record (for §9.16 R2; law 30) | normal | 08.17 L69–77; BOOK-OUTLINE L18, L21 | Move it into the inventory under row A or §10.3.7 (the merge belongs to the record element) | 08.18 L17, 08.21 L21, 09.08 L104: they cite C5 itself, no change; 10.03 L29, L172 | moved |
| A-12 | §10.3 L7; §10.3.5 L237 | "five relations, mutually exclusive" / "five exclusive relations" contradict L63 and the index row 10.3, which say "not mutually exclusive" | normal | 10.03 L63; 10.00 L9 | Annotate L7 and L237 as superseded at S0226–S0252 A-03 | 10.SUMMARY L24 consistent | annotated |
| A-13 | TODO T10-21 | The State column reads "Resolved" while item (5) is open | normal | TODO L26 | "Resolved (1), (2), (4); (3) with T10-15; (5) open" | none | restated |
| A-14 | 10.99 L25; TODO T10-19, T10-20 | No in-line note shows the earlier State or decision-criterion wording of T10-19 and T10-20. The change log's "verbatim removed" column holds no text. I cannot verify that the earlier wording was kept; history is not allowed | normal | 10.99 L5 (column meaning) | Add the removed State and criterion text to the rows or to L25 | — | added |
| A-15 | §10.2.3 row C (§10.3 L124, L127) | The "G1" labels are stale (G1 closed by adoption). The conflicting evidence is now the class "contested" (T9-17 (b)) | normal | §9.18 L76 | Annotate | none | annotated |
| A-16 | §10.3.4 L231 | The consequence line "only standing and the rules about rules are separate areas today" was superseded by T10-10 (1). It is not annotated there (only at L298) | normal | 10.03 L298–300 | Add an in-line "superseded, T10-10 (1)" | none | annotated |
| A-17 | §10.1 L28 | S0365 and S0392 are still described as "read out of order". Both have since been read in order (§8.89, which does not change the row; §8.102). The line has no forward pointer | normal | 08.00 L105, L117; 08.89 L5 | Add "(since read in order: §8.89, §8.102)" | none | added |

Out-of-scope, one line each: THEORY-MAP L454 still gives the position as "S0377; next: S0378" (reviewer D's lines are L455–456). SCOPE §2.1 repeats A-09's wrong section address. Candidate 2's "decision record addendum to be recorded by the Governance Engineer" (§10.2 L5, §10.3 L319): I could not check it, because the decision record is not on my list.

## Coverage (task 3)

| Item | Status | Home |
|---|---|---|
| T10-01, T10-05, T10-10, T10-16 | resolved; consistent with the text | §10.1.3; §10.3.6; §10.3.7 |
| T10-02, T10-03, T10-04, T10-06, T10-08, T10-09, T10-12, T10-13, T10-14, T10-17, T10-18, G11 | still open; no decided result found against them | as listed |
| T10-07 | open (Q6, supersession); its Q2/Q5/O1/O3 part placed at §10.3.3 (c) | consistent |
| T10-11 | open; the pointer §9.13 L327 is valid (covers, F22) | — |
| T10-15 | open; the pointer §9.10 L31 is valid | — |
| T10-19 | qualified; pointer wrong (A-09) | §10.3.6 |
| T10-20 | closed by the Principal; class question uncovered (A-02) | — |
| T10-21 | (5) open; State inconsistent (A-13) | — |
| Pointers to Ch. 9 checked | §9.6 L219, §9.10 L31/L150/L179, §9.11 L11/L48, §9.13 L52/L98/L327, §9.15 L75/L280, §9.16 L77/L79, §9.18 L82/L273, §9.19.9: all valid. §9.18 L273 has changed meaning since §10.3 L114 cited it (A-04) | — |
| Pointers to Ch. 8 checked | §8.6 C3/C6, §8.17 C5/C8, §8.18 C5, §8.21 C15, §8.28 C5, §8.30 C3, §8.8 C1, §8.90 C4: all exist. Misread: §8.90 C4 (A-01), CON-0141 (A-10), §8.17 C5 (A-11) | — |
| Corpus spot checks | S0035 L30/L71–72, S0036 L90–92/L138/L143, S0033 L83–86, S0150 L584–592, S0091 L70/L188: consistent in meaning | allowed list |
| BOOK-OUTLINE L186–212 | matches the copy's records: S0250 has 14 = A-02–A-13 plus N-03, N-04 (10.99 L19); the S0375 row matches THEORY-MAP L454; the order of the second cycle is consistent | — |
| Concepts (task 5) | 27 tagged IDs: status matches the register for all. Meaning mismatch: CON-0141 (A-10). CON-0164 and CON-0179 are cited as "partly" relevant, which is correct | register |

## R2 (`--scope 10.00,10.01,10.02,10.03,10.99`; tier A 0; 78 tier-D words)
Each word judged:
- **Ordinary English, with the meaning clear in context; no defect:** adjusted, single-owned, organised, grouped, proviso, staying, discrepancy, assemble, assess, responsible, analogou(s), guaranteed, publishe(s), affect, unaffected, included, resolving, showing, unaltered, mutually, paragraph, assembled, disjoint, argued, dutie(s), rejection, grounded, provided, receive, classed, moving, assembly, posed, task, instrument, making, speak, defeat, warning, resting, strategic, yield, hand, ones, compared, goes, procedure, discovery, claimed, judged, necessity, violation, well, derive, merged, plus, qualified, take, treat, opened, changed, rest, still, keep.
- **Defined or anchored in the book; no defect:** promise (10.01 L41); enforcer (10.03 L61); subdomain (classes, 10.01 L36–39); mandate (a named document, 10.01 L3); pins (§0.7); gaps (the table at 10.02 L212); model-checked (v14, v20, check 32); annulled, authorised, refutation (Ch. 9, §9.18); critical (review severity); falsifier (used at 10.03 L195–198 as a counterexample to the test).
- **Acceptable:** seam (the source's word, in a paraphrase of CON-0085); currency (10.02 L152, "being current", §0.1; "currentness" would be clearer).
TODO.md is not a section ID, so R2 did not cover it (a limit).

## Sections read (task coverage)
Read in full: 10.00, 10.01, 10.02, 10.03, 10.99, TODO.md, the BOOK-OUTLINE rotation section (and the whole file). Partly read: 09.00 index, 09 TODO, 08.00 index (rows), and the §9.19, §9.18, §9.20, §8.90, §8.17, §8.08 lines cited. Not done: SEQ order (assigned to reviewer B); a check of the CH10-PROPOSAL and the decision record (not on my list).

## Files opened
- COMMISSION-A.md, REVIEWER-NOTE.md, SCOPE.md (the consolidation folder)
- 03-methodology/REVIEWER-INSTRUCTIONS.md
- 03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md
- Copy, read whole: 06-theory/manuscript/BOOK-OUTLINE.md; chapter_10/10.00-index.md, 10.01-subdomains.md, 10.02-bounded-contexts.md, 10.03-ownership-of-invariants.md, 10.99-change-log.md, TODO.md; chapter_09/09.00-index.md, TODO.md
- Copy, by grep or sed: chapter_09/09.19-…, 09.18-…, 09.20-…, 09.10-…, 09.06-…, 09.15-…, 09.16-…, 09.13-…, 09.11-…; chapter_08/08.00-index.md, 08.90-…, 08.17-…, 08.08-…, 08.06-…, 08.21-…, 08.28-…, 08.30-…, 08.18-…, 08.89-…; chapter_10/10.04-context-map.md, 10.05-published-language.md; SUMMARIES/10.SUMMARY.md; THEORY-MAP.md; CONCEPT-REGISTER.tsv (by script)
- Corpus, from the allowed list (sed): S0035, S0036, S0033, S0150, S0091
- Lane tools, read by sed or grep: tmp/tools/undefined_terms.py, tmp/tools/inbound_citations.py

## Commands and searches
- `test -e` existence checks of named paths in the copy (no listings)
- `wc -l` on named files
- `python3 -I <copy>/tmp/tools/book_search.py --help`
- The R2, R1, quote-check and R3 commands below, each with `--root <copy>` on its first call
- `grep -n`/`sed -n` on the named files above; a Python script comparing the cited CON statuses with the register; `awk` on CORPUS-ALLOWED.tsv for S0033/34/35/36/91/150
- R2: `undefined_terms.py --root <copy> --no-corpus --scope 10.00,10.01,10.02,10.03,10.99` → scratch r2.txt, sha256 a1ce2011…7e53
- R1: `register_home_check.py --root <copy>` → r1.txt, sha256 41d4701c…043; 0 flags
- Quote check on the six Ch. 10 files → quote.txt, sha256 0acbad2b…752d; 0/0
- R3: `inbound_citations.py --root <copy>` for §10.2.2, §10.2.3, §10.3.2, §10.3.3, §10.3.4, §10.3.6, CON-0141, "§8.17 C5", T10-19, T10-20, T10-21 → r3.txt, sha256 5f65f82b…83d2
- `sha256sum` of the outputs

Scratch directory for all outputs: /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons5-a/

I used no globs, no recursive search, no git, and no diff. I did not open anything in `00-access/`, any working chapter file, `02-FILES.jsonl`, or any sealed file, and I edited nothing in the repository. One borderline case: the copy's `book_search.py --help` call had no `--root`, but that tool searches the copy by design.
