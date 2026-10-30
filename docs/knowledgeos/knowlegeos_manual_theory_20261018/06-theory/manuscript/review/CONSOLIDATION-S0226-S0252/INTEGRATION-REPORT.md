CONSOLIDATION S0226–S0252: INTEGRATION REPORT

Integration reviewer. The object is the frozen copy `06-theory/manuscript/review/CONSOLIDATION-S0226-S0252/BOOK-UNDER-REVIEW/` and nothing else. I read REVIEWER-NOTE (binding) first, then the commission, SCOPE, REPORT-A, REPORT-B and REVIEWER-INSTRUCTIONS. "L" means a line of the copy file, or of the cited allowed source. I verified findings; I did not do a new full review.

## Summary (10 lines)
1. I confirmed 23 of 25 reviewer findings: 21 as stated and 2 modified (A-12, B-03). A-01 and A-12 are one correction, and A's and B's notes on the quote tool are one finding. A-10 is not confirmed, because it cannot be checked from the allowed files. A-07 is confirmed with a narrower scope.
2. Six are high: A-01, A-02, A-03, A-04, B-01, B-02. None is critical. Every affected line is at S0, and no concept verdict or derivation rests on them.
3. B-01 is a verified fact. S0248 L411 reads "Mechanism, not kernel" and L1194 reads "Not kernel". §8.44 L60 quotes "Kernel, not mechanism".
4. B-02: the missing §8.43 C6 is missing content, not a stale pointer. S0243, S0241, S0244 and C50 inventory items 9, 10 and 13 support it. There is a fourth inbound pointer that B did not list: §8.44 L24, the dependency row of C5.
5. A-01, A-02 and A-04 have one cause: later decisions (g1, the G1-turn gap states, T10-16 (b)) were applied to only part of Ch. 10.
6. New defects I found: N-01 (§8.44 L24), N-02 (THEORY-MAP L392 "21 typed relationships"; 23 in §10.4), N-03 (§10.2.2's Ded also leaves out candidate 6 under α), N-04 (§10.2 L9 repeats A-02's stale gap list), N-05 (§8.45 L47 uses B-08's overstated point; B's R3 missed it).
7. R3: tool and grep together found more inbound lines than either reviewer listed. For the Ch. 10 subsections (§10.2.3, §10.3.2, §10.3.7, PL-1, R0) the tool resolved no home ("home: —"), so I grepped the copy (see R3 table).
8. Conflicts: none of substance. Five pairs touch the same text and are merged. The order of B-04 and A-01 matters.
9. Gaps: R2 was never run, by either reviewer, scoped to §8.35 C7, the §9.18 passages or the Ch. 9 TODO rows. The tails of the C50, C51 and C52 inventories were read by neither reviewer; they are mainly file lists and lineage notes. No concept verdicts were due; both reviewers agree, and I confirmed it.
10. The quote tool checked nothing in scope: 0 quotes found in all 17 files. It did not catch B-01. Every quote check in this consolidation was done by hand.

Process independence: I did not write the reviewed work. I worked only from the copy, the reports and the allowed sources. This is process independence, not personal independence.

## 1. Findings (verified facts first, then verdict)

| ID(s) | Verdict | Sev. | Ch. | Evidence (verified unless marked) | Correction |
|---|---|---|---|---|---|
| B-01 | confirmed | high | 8 | §8.44 L60 reads "Kernel, not mechanism" (L411, L1194). S0248 L411: "⚠️ Mechanism, not kernel"; L1194: "Not kernel." The misquote occurs nowhere else in the copy (grep). It also contradicts the section's own Ded bullet L68 ("placing the engine outside the kernel does not cure reading (a)"). | Quote L411 "Mechanism, not kernel" and L1194 "Not kernel". The argument stands. |
| B-02 + N-01 | confirmed, content MISSING | high | 8 | §8.43 has C1–C5 only (grep of headings). Four lines point to a C6: 08.00 L57 "(C6)", THEORY-MAP L218 "forbidden, not impossible", §8.43.1 item 6 (L33), and **§8.44 L24 "§8.43 C4, C6"** (C5's dependency row; new). Source support: S0243 L22 says "four layers below the kernel" but draws three, leaving out S0241's Mechanisms layer (S0241 L125–131). S0243 places the verdict vocabulary in Representation (L36, L122); S0241 places it in the Engine Layer (L128–129). S0243 adds eleven kernel services (L72–74) and certifies itself (L170–178). S0244 L40 states the sourcing rule. S0243 L14 claims "structurally impossible". C50 inventory items 9, 10 and 13 and its table L127–128 record the same points. Only self-certification appears in the section, in the lineage line L12. | Write C6 (Src, Int) from these lines. If C6 is dropped instead, remove all four pointers and §8.44 C5's dependency on it. |
| A-01 + A-12 (merged, same line) | confirmed; A-12 modified | high | 10 | §10.2.3 L195 still reads "whatever support of k is recorded". With L196 (contested), an item can then be both refuted and contested, which contradicts g1 (§9.18.5 L195–196: refuted iff ref = T and sup = F) and §9.18.2 L66. The change log claims the fix was made (**10.99 L18**, not "L31": A's 10.99 line numbers are offset by 13, from a joint `cat -n` with 10.00). A-12 modified: "not corrected" corresponds to annulment. What is missing is the authorised-producer condition (T9-12 (c)). Premise (iv) is still listed as unmet, although G4 is decided (§9.18.2 L79). | refuted: "…not withdrawn…, and no support of k is live", with "live" as in §9.18.5 L193 (authorised producer, not annulled, no counted withdrawal). Point to g1's U case list, not to its gloss (see B-04). Mark premise (iv) as decided (T9-12 (c)). |
| A-02 + N-04 | confirmed | high | 10 | §10.2.3 L225 and §10.2 L9 (N-04) say "until G1, G2 and G4–G9 are closed" and refer to "all three classes". §9.18.2 L72–86 gives G2 and G4 decided, G5, G8 and G9 closed in the model, G6 closed under (v), G1 conditional, and G3, G7 and G10 open. | Restate L9 and L225 from §9.18.2: readability is conditional on G1 adoption and on G3, G7 and G10; four classes plus U. |
| A-03 | confirmed | high | 10 | §10.3.2 calls the five relations "mutually exclusive per (candidate, invariant)". §10.3.3 gives two pairs two relations each: "acting under ended authority" (row A, owner 1; "1 observes") and law 26 (owner 2; "2 observes"). A self-enforcing owner (e.g. laws 1, 2: "1 (step)") has no enforcement relation of its own. | Minimal fix now: drop "mutually exclusive"; state that "owns" combines with one enforcement mode. A's two-field model (owner status × enforcement mode) is the option for T10-08, to be decided there. |
| A-04 | confirmed | high | 10 | §10.3.7 L342 and PL-1 L119 say the kind assignment is "now the element's act". §10.3.6 L274, L318, §10.2 L5, §10.4 L15 and PL-0 L76 say it is stipulated and that no recorded act fixes it. T10-16 (b) (PL-0 L77) fixes only who may change the list. L263 ("true by construction") and L288 ("the ten kinds…", "by construction") carry no supersession note. | A's single statement: the assignment is the element's to make, by an authorised act outside every area; none is recorded, so it stays stipulated ((p2)). Annotate L263 and L288 (eight kinds). |
| A-05 + N-03 | confirmed, extended | normal | 10 | Recomputed from the grid's own cells: row 6 gives 5–6 under α and β (printed 4–5), row 7 gives 2–5 (printed 2–4), row 8 gives 2–4 (printed 2–3). The Ded line L73 leaves out 6 under β ("and 4 may") and, in the same "may" sense, under α. | Correct the three cells; add "6 may, under either reading". The §10.2.4 row placing 6 "between the readings" stays true. |
| A-06 | confirmed | normal | 10 | §10.1.2 L37 vs L41: "not made for KnowledgeOS" and "made without KnowledgeOS's model" are different predicates. A's counterexample holds: a solution built on KnowledgeOS's model for another product is both. | Define generic as ¬specific, keeping the boundary proviso. |
| A-07 | confirmed, narrowed | normal | 10 | §10.1.2 rows 1, 5 and 7 show S1. Row 1 and row 7 already carry the caveat ("finding check not shown", "finding check needed"). Row 5 states S1 plainly, while §10.2 L147 says "S1 not shown" for CON-0080 and CON-0004. | Align row 5; state rows 1 and 7 as "S0 or S1, undetermined". |
| A-08 + N-02 | confirmed | normal | 10 (+map) | 10.00 L8 ("none admitted"), L9 ("one question (T10-05)"), L10 ("21 relationships"; §10.4 L6, L132 give 23); §10.3.6 L291 vs L321; §10.4 L155 ("owner depends on T10-16"), L166 ("now 16 of 21"); §10.2.3 L208; TODO T10-07 (Q2, Q5, O1 and O3 are placed in §10.3.3, L138, L186); TODO T10-20 and §10.2 L100 (three classes). **N-02:** THEORY-MAP L392 "21 typed relationships". | Update each line. |
| A-09 | confirmed | normal | 10 | §10.4 L24 defines "conforms" as "shows Y's values unaltered"; R0 (L52) is a schema dependency. R0 (record → every area, S included) and R0′ (S → record, L53) form an unconditional cycle. §10.4.5 L158 weighs only R6 and R12. | Retype R0; record the cycle under the layering risk. |
| A-10 | not confirmed (open) | — | 10 | One commit carrying two addenda at different times (10:00:44Z and 10:46:12Z) is not an inconsistency in itself. The decision record is not in my file list. | The Professor states the commit for the 10:46:12Z addendum. |
| A-11 | confirmed | normal | 10 | PL-1 L103 lists "the ten admitted kinds" (removal and designation included, L112–113) under B, against the split table L61. | Eight kinds under B, two under A. |
| A-13 | confirmed | normal | 10 | "sluice" occurs once in the book (book_search: 10.02 L100). "part 2" has no section hit in the book. | Replace or define both. |
| B-03 | confirmed, modified | normal | 8 | §8.42 L33: "Of 29 candidate rows … the 29 are rendered". S0239 L131: 29 = 25 candidates + 4 Established; L202: "The 22 surviving candidate rows render into the 11 laws without loss", and the 4 Established "appear unchanged as the spine". So 26 rows are rendered, and "without loss" is said of 22 of them. L90 has the same scope slip. | "29 rows (25 candidates + 4 Established)"; "without loss" attributed to the 22, as at L202. |
| B-04 | confirmed | normal | 9 | §9.18.5 L200's gloss covers (T,U), (U,T) and (U,U), not (U,F) or (F,U). The case list on the same line has all five. L147 has nested italics. | As B proposes. |
| B-05 | confirmed | normal | 9 (+map) | 09.18 L3 and L321 name v22 (L192 names v24, check 38); 09.00 L28 lists T9-17 as open; THEORY-MAP L394 lists no "contested"; L190 is a probe from the g0 era. | As B proposes. |
| B-06 | confirmed | normal | 8 | My forward-reference scan finds only §8.41 L17 and L97 (to §8.42). 08.00 L61 covers §8.18–§8.38 and g0 only. | As B proposes. |
| B-07 | confirmed | normal | outline | BOOK-OUTLINE L183 reads "§8.1–8.30"; the rotation row L202 still says Ch. 10 is "in preparation". | As B proposes. |
| B-08 + N-05 | confirmed | normal | 8 | S0251 L430–435: "This is very close to: AGM … TMS … Buddhist middle way … Shaivism witness consciousness", a comparison. §8.45 L43 calls it a source brought in, and **L47 uses it: "S0251's new source (L434) favours (a)"** (N-05). B's "AGM/TMS not among the twelve" was not checked. | "mentions"; delete the support for (a) at L47. |
| B-09 | confirmed | normal | 8 | (a) S0248 L235 "Constitutional candidate" and L770 "does not add new constitutional invariants" both lie in pass 1 (L1–780, C51 inventory L45). (b) Item 5 (L32) opens with S0250's closure and then says "saved after the closure note" (S0246 L12). Which closure is meant is ambiguous. | As B proposes. |
| B-10 | confirmed | normal | 8 | §8.42–§8.45 L3 read "named in the step 2–3 brief" (§8.39–§8.41 give commits). §8.41 L85 uses "A-11" with no home. | As B proposes. |
| B-11 | confirmed, sharpened | normal | 8 | S0241 L11 (head 43) has seven items, with no authority and no "governs"; L19 (Art I.1) has eight. §8.45 L83 groups "head 43 and Art I.1" under one quote containing "governs". | Name both forms; "drops authority" holds against Art I.1 only. |
| B-12 (+ A's note) | confirmed | normal | process | `corpus_quote_check.py` finds 0 quotes in all 17 files in scope (my run). | Extend the tool to `(L…)` citations, or state its coverage. |

## 2. R3, every line (T = stays true · S = needs the same change · D = needs a different change · N = not affected)

| Correction | Inbound lines and disposition |
|---|---|
| A-01/A-12 | §10.2.3 L196, L197, L199: T. 10.99 L18: T once the text is fixed. §10.4 L57 (R4): T. §10.5 L291: N. §9.18 L72, 92, 131, 179, 204, 321: N. TODO T10-06: N. 09.99 L80: history. §9.18 L24 (three classes in the promise): D, add "contested" (outside the scoped passages; one line). |
| A-02/N-04 | §10.2 L9: S. 10.00 L8 ("readability an open gap (G1–G10)"): S. THEORY-MAP L390 ("plus G2–G10"): S. §9.18.2 L72–86: T (the reference). TODO T10-06: T. |
| A-03 | THEORY-MAP L391, 10.00 L9, TODO T10-08: S. §10.5 L277 (enforcer definition), §10.4 L130, §9.18 L321: N. |
| A-04 | §10.3.6 L263, L288: D (annotate; eight kinds). L274, L318, §10.2 L5, §10.4 L15, PL-0 L76, §10.5 L372: T under A's wording. §10.3.7 L342, PL-1 L119: S. PL-0 L77: T. THEORY-MAP L392: D ("remove … designate" are the element's, per §10.3.6 L261's note); its "kind assignment stipulated": T. |
| A-05/N-03 | §10.2.2 L73: S. §10.2.4 rows (L233–235): T. Nothing outside Ch. 10 (grep). |
| A-06 | 10.01 L41: S. No other line (grep "mutually exclusive"). |
| A-07, A-11, A-13 | Only the lines named. A-11: §10.5 L61: T. |
| A-08 | Each line named. THEORY-MAP L392 ("21"): S. L393 ("owner open, T10-16"): S. |
| A-09 | §10.4 L52: S. L158: D (add the R0/R0′ cycle). §9.18 L263: N. |
| B-01 | 08.99 L66, L67: history. 08.45 L17–21, 37, 38, 69, 83, 90: N. THEORY-MAP L219: N. §8.44 L68: T. |
| B-02/N-01 | 08.00 L57, THEORY-MAP L218, §8.43 L33, §8.44 L24: T if C6 is written; if C6 is dropped, all four D (remove) and §8.44 C5's dependency D. 08.99 L65–67: history. |
| B-03 | §8.42 L33, L90: S. 8.43 L96, 8.44 L21, L54, L97: N. |
| B-04 | §8.39 L17–18, L49–50; §8.41 L19; §8.43 L22; §8.45 L21 (cite g1 by name): N. §10.2.3 L199: N. |
| B-05 | 09.18 L3, L190, L321; 09.00 L28; THEORY-MAP L394: S. 09.99 L78: history. |
| B-06 | 08.00 L61: S. §8.41 L17, L97: S (mark as later). |
| B-07 | BOOK-OUTLINE L183: S. Rotation row L202: at settlement. |
| B-08/N-05 | §8.45 L43: S. **L47: S** (B's R3 missed it). 08.00 L59, THEORY-MAP L220: N. |
| B-09 | §8.44 L96: S. L32, L84: D (name the closure). |
| B-10 | §8.42–§8.45 L3, §8.41 L85: S. |
| B-11 | §8.45 L74, L83: S. §8.44 L88–90: T (L90 already separates the two forms). |

## 3. References to missing claims (REVIEWER-NOTE item 6)
- **§8.43 C6:** content is missing, not a stale pointer. The evidence is in the B-02 row above. The section's plain-language item 6 asserts the content with no claim behind it, and §8.44 C5 cites it as a dependency.
- **10.00 L9 "one question (T10-05)" and TODO T10-07's "unplaced" items:** stale pointers. The content exists: T10-05 is resolved (TODO L9), and the items are placed in §10.3.3 L138 and L186.

## 4. Conflicts
| Pair | Resolution | Reason |
|---|---|---|
| A-01 × A-12 | merged into one correction | same line (L195) |
| A-01 × B-04 | apply B-04 first; §10.2.3 points to g1's case list | otherwise §10.2.3 inherits the incomplete gloss |
| A-02 × A-08 (L225) × A-12 (premise iv) | one restatement of §10.2.3's premises, gaps and result | same passage; same cause |
| A (map L390–393) × B (read L385–417, flagged L394 only) | A's points confirmed against the copy; B's silence is an omission, not a disagreement; N-02 added | verified in the copy |
| B-12 × A's quote-tool note | duplicate, merged | same tool output |
| A-03's two-field model × a minimal fix | minimal fix now, the model as the option for T10-08 | the model is a redesign that T10-08 decides |
| B-02's options (write C6 / remove pointers) | write C6 recommended | content is source-supported; removal would touch four pointers and §8.44 C5 |

## 5. Defects in the rotated chapter (Ch. 10), confirmed
- Reviewer A: A-01/A-12, A-02, A-03, A-04 (high); A-05, A-06, A-07 (narrowed), A-08, A-09, A-11, A-13 (normal). A-10 is open.
- Integration (new): N-03 (§10.2.2 candidate 6), N-04 (§10.2 L9). Outside Ch. 10: N-01 (§8.44 L24), N-02 (THEORY-MAP L392), N-05 (§8.45 L47).
- Count: 12 by reviewer A, plus 2 new in Ch. 10.

## 6. Coverage gaps (not filled)
- **Sections:** every section in scope was read in full by one reviewer, per the SCOPE §2.6 plan: A for Ch. 10, B for §8.39–§8.45, the index, §8.35 C7, §9.18 in full, the Ch. 9 TODO, the outline section and the map lines.
- **R2** was not run, by either reviewer, scoped to §8.35 C7, the §9.18 passages or the Ch. 9 TODO rows. B scoped it to 08.39–08.45 and A to 10.
- **Inventories** read by neither reviewer in full: C50 L136–157, C51 L101–117, C52 L96–110. By their headings these are mainly lineage notes and file lists. A read only C48 in full.
- **Sources** not checked as cited: A's list (S0149, S0168, S0175, S0178, S0198, S0199, S0237, S0365, S0392, S0612, S0888, S0991); B's "Rulebook v1.4 L33" (not on the allowed list).
- **Concepts left without a verdict:** none due (no register row homed in scope; I confirmed with awk on `book_location` and found 265 rows: 57 V, 112 P, 96 I, as in the map). Distinctions with no register ID that wait for their chapter's rotation: §8.39 C2, §8.40 C1, and §10.3.2's owner readings and relations (T10-08).
- **Model runs** (ce_model v24, check 38) are reported, not re-run, by anyone.

## Files opened
Base path B = `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018`. Copy path C = `B/06-theory/manuscript/review/CONSOLIDATION-S0226-S0252/BOOK-UNDER-REVIEW`.
- Review folder: REVIEWER-NOTE.md, INTEGRATION-COMMISSION.md, SCOPE.md, REPORT-A.md, REPORT-B.md (cat). No other file in the folder was opened.
- B/03-methodology/REVIEWER-INSTRUCTIONS.md (Read, in full).
- The copy (sed, grep, cat):
  - chapter_10: 10.00, 10.99 and TODO in full; 10.01 L34–72; 10.02 L5, L9, L50–80, L100, L140–150, L186–236; 10.03 L50–120, L261, L263, L274, L288, L291, L300, L321, L328, L342 and greps; 10.04 L18–30, L50–57, L155 and greps; 10.05 L58–64, L76–77, L98–120, L291.
  - chapter_08: 08.00-index L50–61; 08.41 L17, L85, L97; 08.42 greps; 08.43 L25–36, L92–125 and greps; 08.44 L24, L32, L55–70, L80–92 and greps; 08.45 L39–50, L74, L83 and greps; 08.35 L135–137 and the C7 heading; 08.31 L48 and 08.29 L88–89 (grep hits).
  - chapter_09: 09.18 L1–5, L60–90, L140–148, L186–212, L321; 09.00 L28; TODO rows T9-16 to T9-18; 09.99 grep hits.
  - THEORY-MAP L216–220, L385–400; manuscript/BOOK-OUTLINE.md L180–206; CONCEPT-REGISTER.tsv (parsed).
  - Grep snippets from chapter_01 and chapter_05.
  - Directory listings of the copy and its tmp/tools.
- Inventories (SCOPE §2.3): C50 (grep); C46–C52 (wc); the tails of C50, C51 and C52 (headings only).
- Corpus, all checked first on `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (head; awk lookups):
  - S0248: L232–237, L405–413, L764–772, L1038–1043, L1190–1196; greps.
  - S0243: L14, L20–40, L70–76, L118–124, L170–178.
  - S0241: L11, L19, L125–131.
  - S0244: L38–42.
  - S0239: L118–134, L198–203.
  - S0251: L428–437.
  - S0246: L12.
- Never opened: S0155, S0156, S0157, S0161, S0170, S0174, S0233, S0249; 00-access/; COMMISSION-A or COMMISSION-B; any file in REVIEWER-NOTE item 4; any git history. I reached no file of item 4.
- Scratchpad (outside the repository): `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/r3_*.txt`.

## Commands and searches run (all read-only)
1. `cat` of REVIEWER-NOTE, the commission, SCOPE, REPORT-A, REPORT-B; `wc -l` of these.
2. `ls` of the copy, chapter_08, chapter_10, the copy's tmp/tools and B/tmp/tools.
3. `register_home_check.py --root C` (R1: one flag, CON-0133, chapter_06, outside scope).
4. `undefined_terms.py --root C --help`: it ignored `--help` and ran R2 unscoped (first lines read).
5. awk lookups on the allowed list for S0226, S0239, S0241, S0243, S0244, S0245, S0246, S0248, S0250, S0251.
6. `grep` and `sed` on §8.44 and S0248 ("not kernel", "kernel, not", "Recognition Engine"); `inbound_citations.py --root C "§8.44 C3"`.
7. `sed` of THEORY-MAP L216–220; `grep -rn` of the copy for "kernel, not mechanism" and "not mechanism".
8. `grep` of the §8.43 headings, "C6", "impossible", "certif", "layer"; `sed` of §8.43 L25–36, L92–125; `sed` of 08.00 L50–61.
9. `sed` of S0243, S0241 and S0244 at the ranges above.
10. `sed` of S0239 L118–134, L198–203; `grep` of §8.42 for the counts.
11. `sed` of §9.18 L1–5, L60–90, L140–148, L186–212.
12. `sed` of §10.2.3 L186–236; `grep -n` and `cat -n` of 10.02, 10.99 and 10.00.
13. `sed` of §10.3 L50–120; the lines of 10.03, 10.02, 10.04 and 10.05 named in A-04, A-08 and A-10.
14. `sed` of the §10.2.2 grid; Python recomputation of the grid score ranges.
15. `sed` of §10.1.2 L34–72; `grep` of the §10.2 ceilings; §10.2 L140–150; the PL-1 lines; `cat -n` of the Ch. 10 TODO.
16. `grep` of §10.4 for 21 and 23, L155, Q5 and "three classes"; `sed` of §10.4 L18–30, L50–54.
17. Copy `book_search.py`: --help, "sluice", "part 2". `sed` of 10.02 L100 and THEORY-MAP L385–400.
18. Python and awk on the copy CONCEPT-REGISTER.tsv (status counts; homes in scope).
19. `sed` and `grep` of the §9.18 header and references, 09.00 L28 and the Ch. 9 TODO rows.
20. Forward-reference scan of §8.39–§8.45 (grep -no with awk); `find` for the outline, map and register in the copy; `sed` of BOOK-OUTLINE L180–206.
21. `sed` of S0251 L428–437; `grep` of §8.45 for "Buddhist" and "twelve"; `sed` of §8.45 L39–50.
22. `sed` and `grep` on S0248 (pass headings, Trika lines); `sed` of S0246 L12; `grep` of the C51 inventory for "pass" and "Trika"; `grep` of §8.44 for "closure".
23. `sed` of L3 of §8.39–§8.45 (header phrase); §8.44 L32; §8.41 L85.
24. `sed` of §8.45 L74, L83 and §8.44 L86–90.
25. `corpus_quote_check.py` on the copy files §8.35, §8.39–§8.45, §9.18 and all of chapter_10 (0 found in each).
26. `inbound_citations.py --root C` for "§8.43 C6", "§8.42 C6", "§8.45 C2", "§8.44 C5", "§9.18.5", "§10.2.3", "§10.3.2", "§10.3.7", "§10.2.2", "§10.1.2", "PL-1", "R0", saved to scratchpad; `cat` and `cut` of the outputs.
27. `sed` of §8.44 L24, L80–92; `grep -rn` of the copy for "8.43 C6".
28. `grep -rn` of the copy for "§10.2.3", "refuted(k", "§10.3.2", "five relations" and "mutually exclusive"; for "stipulated", "by construction", "element's act" and "§10.3.7"; `sed` of §10.3 L261 and THEORY-MAP L392–393.
29. `grep -rn` of the copy for "only possibly", "29 candidate", "Buddhist", "step 2–3 brief", "ce_model v22", "8.1–8.30" and "drops authority"; `sed` of §8.31 L48, §8.35 L135–137 and the C7 heading.
30. `grep` of the C50 inventory (layer, certification, services, verdict vocabulary); `wc -l` of the seven inventories; heading greps of the C50, C51 and C52 tails.
31. `sed` of §10.2 L9, §10.5 L291 and §10.4 L57.

I edited nothing in the repository and sent no corpus or book text to any outside service.
