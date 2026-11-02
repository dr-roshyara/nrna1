CONSOLIDATION S0303–S0327: INTEGRATION REPORT

Integration reviewer. Object: the frozen copy `…/review/CONSOLIDATION-S0303-S0327/BOOK-UNDER-REVIEW` (written <copy> below). Every tool ran with `--root <copy>` from its first call. I made no `--help` calls.

## Summary (10 lines)
1. There are no critical findings. One high finding is confirmed: A1, a contradiction between "unsatisfiable" and "satisfiable" in §4.1.2, with a T4-03 criterion that cannot be met as worded. The other confirmed findings are normal.
2. Confirmed as stated: A1, A3–A6, A8–A11, A13–A19, A24; B-01–B-09; C F-02–F-04, F-07, F-08, F-10, F-11, F-13. Modified: A2, A7, A12, B-10 (merged into F-08), F-01, F-05, F-06, F-09, F-12. Information items with no defect: A20–A23, A25.
3. F-01 is modified materially. Under NC_snf, an always-UNKNOWN compiler that gives every pair one shared output has d = 0 and FAILS "NC_snf < minimum". So C's proposed wording "the gate does not exclude it" is itself too strong.
4. New issues: §8.71 L21 also drops "under reading (a)" (extends F-09). C's note on §8.62 L9 is partly wrong (L656 anchors the second answer). A7's row is L32, not L33.
5. References to missing claims: one case is content missing (§1.6 L42 points to "§4.1" for re-analysis and staleness; Ch. 4 has no such text and S0018 L62 says "only by refutation"). Seven cases are stale pointers whose content exists (A4, A12, A15, A19, B-07, F-07, F-09).
6. Concept verdicts: A's verdicts on all 38 scope concepts stand. CON-0013, 0014, 0019 and 0163 have no Ch. 4 or Ch. 5 home (SCOPE §2.2 is inaccurate for them), so their verdicts concern their Ch. 1 homes.
7. Coverage: every section in the coverage plan was read in full by its assigned reviewer. Gaps: the SEQ order of Ch. 8 before §8.62; several source lines cited in §5.4 and §5.5; unanchored quotes in §8.68–§8.72; commit hashes.
8. Access: no finding by B or C rests on their working-book `--help` reads. I re-checked 19 of their findings on the copy and the sources. My own deviation is disclosed under Access deviations.
9. Largest risk: corrections appended beside stale text (A1, A3, A5, A8, A24) and summary lines (index, map) not updated after R3 notes (F-07, F-09, the new line). A reader can meet two contradictory statements.
10. Process independence: this is process independence only. I wrote none of the work under review and none of the three reports. I am the same model family as the Professor and the reviewers.

## 1. Findings (verified against the copy and the cited sources)
| ID(s) | Verdict | Sev. | Ch. | Evidence (my check) | Correction |
|---|---|---|---|---|---|
| A1 | confirmed | high | 4 | 04.01 L17: "the promotion condition is unsatisfiable on the record". L18: a graded notion "would make the condition satisfiable". TODO L11 criterion: "an independent instance". §8.06 L55–58 proves only that the record cannot establish F = ∅ under R ⊆ F. | As A proposes: L18 should say "satisfiable in the world, not establishable on the record", and T4-03's criterion should be reworded (or routed to DB-2). |
| A2 | confirmed, modified | normal | 4 (1) | 01.06 L42 defers to "§4.1" for "re-analysis and staleness also lower it". A grep of chapter_04 finds no such content. S0018 L62: "only by refutation, never by decision". Register: CON-0013/0014 are at "Ch. 1 §1.17.1 (RC-40); planned chapter_05", and CON-0019/0163 at "Ch. 1 §1.6 (RC-37); planned chapter_04". **Content missing, not a stale pointer.** | Remove or source the clause in §1.6 L42. Record that these four concepts are homed in Ch. 1 until M13 is decided. Note SCOPE §2.2's inaccuracy. |
| A3 | confirmed | normal | 5 | 05.07 L29 and L100 say "never ran". S0079 L55–57: "HANDED_OFF, started … no output". L26 and L32 already correct this. | In L29 and L100, write "no eligible commissioned deliverable". |
| A4 | confirmed | normal (low) | 5 | S0080 L46 is the no-output CANCEL row; L47 is a different lane. | Cite L46. |
| A5 | confirmed | normal | 5 | 05.04 L112 keeps "Most pairs of states are incom…", and L113 withdraws it. Recomputed: 2·3ⁿ−2ⁿ comparable ordered pairs out of 4ⁿ gives 0.72, 0.57 and 0.44 for n = 3, 4, 5. | Delete the sentence, or scope it to "n ≥ 5 on P(R)". |
| A6 | confirmed | normal | 5 | S0065 L7, "Accept KOS-ARCH-BASELINE-002 strategic model.", has 4 tokens. 05.06 L50 and L60 say "six". So do THEORY-MAP L273 ("a six-word act") and PRINCIPAL-TESTIMONY L34. | "four words", in all four places. |
| A7 | confirmed, modified | normal (low) | 5 | The 4-cell row is 05.00 **L32** (grep), not L33. | Move the stray cell into "Senses". |
| A8 | confirmed | normal | 4, 5 | 04.01 L22 "(Ded)" sits above L23 "Relabelled Int". L17's "Consequence (Ded)" says "it is Int". 05.03 L49 "(Src…)" sits above L50's Int note. | Fix the labels. |
| A9 | confirmed | normal | 4 | S0027 L17 holds the "ARB-persona review" verdict; the grades are at L23–26. The source does not say the persona is an AI. | Label "an AI role" as Int, and cite L23–26 for the grades. |
| A10 | confirmed | normal (low) | 4 | S0027 L158: "a FAILURE-MODE instance: obligation skipped + net blind". 04.01 L71 omits this qualifier (grep count 0). | Add it. Note that the register already half-recognises the counterexample. |
| A11 | confirmed | normal | 5 | S0029 L37's "no threshold invented before data" concerns the p95 save-latency gate. L29's values are "CANDIDATE values". | Reword 05.03 L36 as A proposes. |
| A12 | confirmed, modified | normal | 4 | 04.03 L14 cites L28 for the used-versus-performed ground. S0044 L29 is the "OBSERVED as vocabulary, UNEXERCISED as behavior" row. The restated rule has no concept ID. | Cite L29. Give the restated rule an ID (Professor's call). |
| A13 | confirmed | normal | 4, 5 | RD-1…RD-7 are unglossed (04.02 L36; 05.05 L20, whose heading names RD-6). S0044 L37–43 defines them. | Gloss once, in §4.3. |
| A14 + A22 | confirmed | normal | 5 | BC-7 appears in Ch. 0–5 only in 05.07 (L20, L68–73) and in Ch. 1's TODO. | A one-line gloss or a marked forward reference. Mark the THM-G2/G4 use before C8 likewise. |
| A15 | confirmed | normal (low) | 5 | 05.02 L51 "Ch. 8, planned". §8.4 C3 (L32) exists. **Stale pointer.** | Point to §8.4 C3. |
| A16 | confirmed | normal | 5 | 05.06 L67: "provisional, not yet reviewed". The chapter was rotated in full. | The Professor decides whether the label is stale or the points were left unreviewed. |
| A17 | confirmed | normal | 4, 5 | Neither TODO has a row for CON-0078, 0081, 0109, 0113, 0124, 0144 or 0145 (grep). CV-1 §4a is quoted in the Ch. 5 TODO header. | Add rows, or state the exemption. |
| A18 | confirmed | normal | 4 | 04.01 L42, L49 and L65 assess the L173–175 scope check and L197–201. T4-04 says "content not assessed". | Narrow the row. |
| A19 | confirmed | normal (low) | 4 | T4-02 cites "Ch. 1 RC-25". The qualification revision is RC-38 / 1.14.4-12 (01.99 L38; 01.14 L397, L522). **Stale pointer.** | Cite §1.14.4-12. |
| A24 | confirmed | normal (low) | 4 | 04.02 L36 "is not yet read" sits next to L37's correction. | Delete the clause. |
| B-01 | confirmed | normal | 8 | S0310 L1468–1476 writes "K₁ ≠ K₂" together with "identity continuity preserved". L1300 concerns "semantic identity". Two senses are possible. 08.65 L43, L147 and L203 and index L81 say "contradiction". | As B proposes, in the section and in index L81. |
| B-02 | confirmed | normal (low) | 8 | S0303 L1504–1521 ("KnowledgeOS should store: EVENT … Not: [the sentence]") lies outside L1–655. 08.62 L68's "no counterpart in S0303" is unscoped. 08.55 L69 is already scoped to L1–655. | Scope the claim to L1–655, and note the partial counterpart. |
| B-03 | confirmed | normal | 8 | RESEARCH-PURPOSE L28–35 versus S0306 L283–291: relationships and history are also absent. | Complete the set difference in 08.63 L56. |
| B-04 | confirmed | normal | 8 | Recomputed: 81.34% under one category mapping and 80.34% under the other. The unweighted mean is 80.84%. "Neither gives 80.0%" still holds. | State the mapping, or give the range. |
| B-05 | confirmed | normal (low) | 8 | 08.66 L32 says "one file earlier, in S0310". L200 says "S0310, three files earlier". No reference point is named. | Name the reference point, e.g. "one file before S0311". |
| B-06 | confirmed | normal | 8 | 08.66 L35 and L122 use "interpreter" for the AST-015 workflow fold. L40 and L173 use it for the semantic compiler fork. Map L446 already qualifies the second sense. | Qualify both senses. |
| B-07 | confirmed | normal (low) | 8 | 09.10 L35 holds "Not the truth of k's content". 08.65 L110 cites L34. 08.62 L154 and 08.64 L77 cite L33–35. **Stale pointer.** | Cite L35. |
| B-08 | confirmed | normal (low) | 8 | S0302 L8 has plural "ACCEPTANCE acts" and a "DV-1…DV-7 bar". L82 has "the DV-1 Phase-5 bar". | Quote in full. |
| B-09 | confirmed | normal | 8 | S0307 L896–900: Model A's rows sum to 6–23 s against the stated "5-30 sec". | Add it to the Ded's list of source defects. |
| B-10 → F-08 | merged, confirmed | normal | 8 | 08.00 L90's forward-use note lists sections only up to §8.61 (it ends "§8.58 C1 → §8.61 C3"). §8.62–§8.72 use §9.19 and other Ch. 9/10 constructs. | Extend the note. This answers B-10's open question about the declared convention. |
| F-01 | confirmed, **modified** | normal | 8 | S0317 L1020–1025 gives the gate as "NC_snf < minimum → FAIL", with NC_snf a mean of d over pairs (L140–148). CR and NC_event are at L1092–1130; abstention appears in the matrix (L1474–1478). 08.68 L77 states the gate as NC_snf but computes CR = 0. | "Whether the gate excludes the always-abstainer depends on the reading. Under NC_event with the matrix rule it passes (CR = 0). Under NC_snf it is undefined, or, if UNKNOWN counts as one shared output, d = 0 and it fails. No rule scores UNKNOWN." C's "does not exclude it" is too strong. The same change applies to the title L1, L69, L138, index L84 and map L243. |
| F-02 | confirmed | normal | 8 | S0315 L16 gives the verifying process as d1612e03. L110: "`--session=S5…` with a matching label yields `MATCH`; with `INTRUDER`, `MISMATCH`". S0324 L30 gives S5's registered label as a8ce5a39. So the operand is the label argument (Int). Neither 08.71 nor 08.72 cites S0315 L110 (grep count 0). | As C proposes. |
| F-03 | confirmed | normal | 8 | S0322 L29 lists "workflow record creation (`1ef0325c`)". L40: no store record references b51dba91. 08.71 L27 says "four registrations … sit elsewhere". | As C proposes. |
| F-04 | confirmed | normal | 8 | 08.62 L131: "a dependency cycle between gates". 08.69 L31 and L69: "not a cycle", followed by "the same structure". | Change 08.69 L69 to "the same kind (law 20a)". §8.62 stays as it is. |
| F-05 | confirmed, modified | normal | 8 | S0320 L44 is the V-5 row: the harness references `bootstrapping_status`, and the canonical boundary document is listed among the items to align. This is weak evidence of the earlier state, not none. | State the premise "S0325's list is accurate (Src)" and call S0320 L44 consistent with it. |
| F-06 | confirmed, modified | normal (low) | 8 | S0327 L35 searched the reviews folder and git for a **verification-lane registration**, not for prior participation. 08.72 L112 is true for condition 8 (L68). | Add the contrast. The existing sentence stays. |
| F-07 | confirmed | normal | 8 | Index L87 ends "W-01's git-history evidence held for a ruling". §8.71 L8 and L129 carry the C79 R3 decisions. Change-log L99 does not list "index row 8.71". **Stale pointer.** | Update index L87, map L246 and change-log L99. |
| F-09 + new | confirmed, extended | normal (low) | 8 | Map L244 and also **08.71 L21** (new) read "MATCH can only follow (the) REGISTER" without "under (a)". Index L85 conditions the claim through "by the existing MATCH check". | Add "(under reading (a))" in both places. |
| F-10 + B (V-5) | merged, confirmed | normal | 5, 6, 8 | V-1 is a note at 05.06 L68 and a proposed invariant at 06.04 L31. V-1, V-2, V-5 and V-8 are S0315 findings. | Write "S0315's V-n". |
| F-11 | confirmed | normal (low) | 8 | S0325 L48 ("routes terminal lanes to `po/arb` (`R8`) regardless") is quoted at 08.72 L53 but the R8 clause is not assessed. | Assess it. |
| F-12 | confirmed, modified | normal (low) | 8 | 08.71 L74 does use S0190 L107–117 for the self-registration refusals, so C's "only for the appointment" is inaccurate. What is missing is S0190's "false attribution" ground for `recordedBy: governance`, which bears on §8.72 C3. | Connect the two in §8.72 C3. |
| F-13 | confirmed | normal (low) | 8 | S0327 L9 and L78 (the V-8 precedent) do not appear in 08.72 (grep). | One line. |
| C appendix, "descriptive" | confirmed | normal (low) | 8 | "descriptive metrics" is at S0317 ≈L1017–1019, not L1030–1035. | Correct the anchor (same line as F-01; compatible). |
| C out-of-scope note, §8.62 L9 | modified | normal (low) | 8 | L9's anchors are L25 and L642 (both in the first answer) and **L656** (the second answer). Only the third answer is unanchored, and L12 locates it at L1294–1811. | Add a voice anchor for the third answer. |
| A20, A21, A23, A25 | confirmed: no defect | info | 5 | — | — |

## 2. R3: inbound lines for each confirmed correction
`inbound_citations.py --root <copy>` was run for 30 targets (scratch cons3-i-r3.txt). Section-only hits were checked by grepping the changed phrase across the copy's chapters, map, register and outline.

| Correction | Inbound line | Disposition |
|---|---|---|
| A1 (§4.1.2; §4.1 C5) | 06.04:20; 08.06:10, :70; register L129 | stays true |
| A1 | TODO4 L11 (T4-03); TODO-TRIAGE M7 (L67ff, L253) | needs the same change (the criterion wording) |
| A2 (CON-0163) | 01.06:42 | needs a different change (remove or source the clause) |
| A2 (CON-0163) | 04.00:3; register L21, L164; BOOK-OUTLINE L115 | 04.00 stays true. The register `book_location` needs a different change (drop "planned chapter_04/05"). The outline is not affected. |
| A3 (§5.7 C1) | 04.01:43; 05.00:31, :46; 06.07:85; 08.10:82; 08.11:20, :73; register L145, L175; map L294, L297 | not affected ("never ran" occurs in none of them) |
| A5 (§5.4 C7) | 05.00:32; map L373, L376 | not affected |
| A6 (§5.6 C6) | 05.07:39, :56; 08.05:20 | not affected |
| A6 (§5.6 C6) | map L273; PRINCIPAL-TESTIMONY L34 | needs the same change ("four") |
| A8 (§4.1 C1, §5.3 C3) | §4.1 C1: no claim hits (section-only only); 04.02:24 cites "(C1)" | not affected |
| A9 (§4.1 C4) | 05.03:82; 08.02:23 | not affected |
| A10 (§4.1 C6 / CON-0071) | 08.20:20, :226, :228; 10.01:58; 10.02:143; register L72; map L71 | not affected |
| A11 (§5.3 C1) | 05.00:33; TODO5 L18 | not affected |
| A12 (§4.3 C1 / CON-0113) | register L114; map L253 | not affected (the line cite is internal to the section) |
| A13 (RD gloss) | 05.05:20; 04.02:36–37 | 04.02:36 needs a different change (A24) |
| B-01 (§8.65 C4) | 08.66:167; 08.69:149; 08.70:59 | not affected |
| B-01 (§8.65 C4) | index 08.00:81 | needs the same change |
| B-02 (§8.62 C1) | 08.55:69 | stays true (already scoped to L1–655) |
| B-03 (§8.63 C1) | 08.64:25, :66; 08.66:59 | not affected |
| B-04 (§8.66 C3) | 08.67:114; 08.68:23, :92; 08.71:150 | not affected ("81.3" occurs only at 08.66:95) |
| B-05 | none outside 08.66 (grep for "file(s) earlier") | — |
| B-06 (§8.66 C4) | 08.67:6, :18, :70; 08.71:116; 08.72:20, :111 | not affected; map L446 is already qualified |
| B-07 (§8.65 C2) | 08.66:81; 08.68:21, :66, :107; 08.70:3, :17, :58, :60, :120 | not affected (only 08.65:110 cites "L34") |
| B-08 (§8.62 C3) | 08.61:70 | not affected |
| B-09 (§8.63 C3) | 08.64:26; 08.65:18, :99; 08.68:19, :79; 08.70:44, :105 | not affected |
| F-01 (§8.68 C3) | 08.70:103 ("left open that the trivial abstainer passes"); map L243; index L84; 08.68 L1, L22, L69, L138 | needs the same change |
| F-01 (§8.68 C3) | 08.70:18, :19, :82, :98, :101, :105 | stays true (08.70:98 uses the matrix rule explicitly) |
| F-01 (§8.68 C3) | 08.99:95 | history, not affected |
| F-02 (§8.71 C4) | 08.72:3, :18, :77 | :18 and :77 need a qualifier; :3 is not affected |
| F-02 (§8.71 C4) | index L87; map L246 | index L87 needs a qualifier; map L246 is not affected |
| F-03 (§8.71 C2; §8.69 C2) | 08.72:19, :112; 08.71:47, :95, :121, :146 | not affected |
| F-04 (§8.62 C4) | 08.69:69 | needs the change |
| F-04 (§8.62 C4) | 08.69:19, :149; TODO5 L20; 08.67:20, :102 | stays true |
| F-05 (§8.72 C1) | 08.71:8, :129 | need the premise added |
| F-05 (§8.72 C1) | index L88 | needs a different change ("on S0325's report") |
| F-05 (§8.72 C1) | map L247; 08.99:99 | not affected |
| F-06 (§8.72 C5) | section-only hits (08.71:8, :113, :129; map L247) | not affected |
| F-09 (§8.69 C4) | map L244; 08.71:21 | needs the same change |
| F-09 (§8.69 C4) | 08.71:18, :47, :48, :95, :146; 08.72:18 | not affected (they cite the open point) |
| F-09 (§8.69 C4) | index L87; 08.99:98 | not affected |

## 3. References to missing claims (REVIEWER-NOTE item 6)
| Finding | Content missing or stale pointer | Evidence |
|---|---|---|
| A2 (§1.6 L42 → "§4.1") | **content missing**, and unsupported by the cited source | no "re-analys/stale/lower" in chapter_04; S0018 L62 says "only by refutation" |
| A15 ("Ch. 8, planned") | stale pointer | §8.4 C3 at 08.04 L32 |
| A19 (T4-02 "RC-25") | stale pointer | RC-38 / 1.14.4-12 |
| B-07 (§9.10 L34) | stale pointer | the content is at L35 |
| A4 (S0080 L47), A12 (S0044 L28) | stale line pointers | the content is at L46 and L29 |
| F-07 (index L87), F-09 (map L244; 08.71 L21) | stale summary lines | the content exists in §8.71 L8/L129 and §8.69 L99 |

## 4. Conflicts between reviewers
| Pair | Resolution | Reason |
|---|---|---|
| B-10 (SEQ undecided) vs F-08 | merged into F-08 | 08.00 L90 is the declared convention, and it stops at §8.61 |
| B's V-5 qualifier vs F-10 | merged | the same homonym family; one fix |
| F-01's proposed wording vs the source | F-01 modified | under NC_snf with a shared UNKNOWN output, d = 0, so the gate fails |
| F-12's premise vs 08.71 L74 | F-12 modified | S0190 is already used for the refusals; only its "false attribution" ground is missing |
| C's §8.62 L9 note vs B (no finding) | C's note modified | L656 anchors the second answer |
| A2 vs SCOPE §2.2 | A upheld | the register homes CON-0013/0014/0019/0163 in Ch. 1 |
| Same text: A3 + A4 (05.07 L29); A1 + A8 (04.01 L17–18); A13 + A24 (04.02 L36); F-01 + "descriptive" anchor (08.68 L77); F-02 + F-07 (index L87); F-07 + F-09 (map L244/L246) | compatible | each touches a different clause; apply both |

## 5. Rotated-chapter defects (confirmed)
| Reviewer | Ch. 4 | Ch. 5 |
|---|---|---|
| A | A1 (high); A2; A8; A9; A10; A12; A13; A17; A18; A19; A24 | A3; A4; A5; A6; A7 (at L32); A8 (05.03); A11; A13; A14/A22; A15; A16; A17 |
| Integration (new) | none | A7's line correction only; no new Ch. 5 defect found in the passages I checked |

## 6. Coverage gaps (not filled)
| Gap | Detail |
|---|---|
| SEQ, Ch. 8 §8.1–§8.61 | SCOPE asks for the reading order of Ch. 8. The reviewers covered §8.62–§8.72 and the L90 note only. |
| Source lines in §5.4/§5.5 | S0208, S0721, S1296, S0913 and S2834 were not opened. S0896 and S2377 were spot-checked only. S0164, S0067, S0071, S0073 and S0091 were not opened (A). CON-0101's verdict rests on partial reading; it stays PROVISIONAL. |
| §8.68–§8.72 quotes | 339 unanchored quoted strings were not checked (C). B's matcher flags were re-read only where load-bearing. |
| Commit hashes | not verifiable in any section (no git, by rule) |
| Concepts | none without a verdict. CON-0013, 0014, 0019 and 0163 have no Ch. 4/5 home, so their verdicts concern their Ch. 1 homes. |
| Ch. 4/5 inventory coverage | no window inventories exist for these chapters; it was not assessed |

## 7. Access deviations
- **B:** `--help` runs of undefined_terms.py and register_home_check.py without `--root`, on the working book. B reports the output discarded.
- **C:** `--help` runs of register_home_check.py, undefined_terms.py and inbound_citations.py on the working book. C saw one R1 line, 25 R2 tier-A lines and an empty R3 result. My R1 on the copy gives the same single flag (CON-0133 H4), so nothing C reported depends on the working-book output.
- **Sample check:** I verified B-01–B-05, B-07–B-09 and F-01–F-07, F-09, F-11–F-13 on the copy and on allowed sources. Every one is grounded in the copy or a source. **None rests on a working-book read.**
- **Weight:** the breach is low. It was disclosed, its output was unused, and its effect on the findings is nil.
- **My own deviation:** a `grep -rni` over the copy's `manuscript/` directory, for "re-analys" and "staleness", printed two lines (L62, L274) of the copy's `BASELINE-REVISIONS.md`, which REVIEWER-NOTE item 4 forbids. I did not open the file and did not use those lines. Later greps named their files explicitly.

## Files opened
- Consolidation folder: REVIEWER-NOTE.md, SCOPE.md, INTEGRATION-COMMISSION.md, REPORT-A.md, REPORT-B.md, REPORT-C.md.
- `…/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- `…/tmp/tools/inbound_citations.py` (L1–40).
- `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (head, awk lookups).
- Copy, under `<copy>/06-theory/`:
  - **Ch. 4:** 04.00 L1–5; 04.01 L15–24, L58, L71, greps; 04.02 L36 (and greps); 04.03 L13–14; TODO L1–14.
  - **Ch. 5:** 05.00 L25–34 and greps; 05.02 grep L51; 05.03 L36, L49–51, grep; 05.04 L110–115; 05.06 L3, L67–68, grep "six"; 05.07 L20, L26, L29, L32, L100; TODO L3–5, L20, greps.
  - **Ch. 1, 6, 9:** 01.06 L42; 01.99 and 01.14 greps; 06.04 L31 (grep); 09.10 L33–35; 09.11 L56.
  - **Ch. 8:** 08.00 L81 (grep), L84, L85, L87, L90; 08.04 grep; 08.06 L55–58 and grep; 08.55 L69; 08.62 L9, L64–72, L131, greps; 08.63 L56; 08.64 grep; 08.65 L43, L110, L137–147, greps; 08.66 L32, L35, L40, L95, L122, L173, L200, greps; 08.67 L20, L102; 08.68 L1, L22, L30, L69, L77, L138 (grep); 08.69 L1, L19, L31, L55, L69, L99, L139, L148–149; 08.70 L18, L19, L82, L98, L101, L103, L105, greps; 08.71 L8, L21, L27, L37, L74, L93, L113, L129, L150, greps; 08.72 L18, L42, L53, L60–65, L69–77, L112, greps; 08.99 L95, L99, greps.
  - **Other copy files:** THEORY-MAP L240, L243, L244, L246, L247, L273, L422–426, L445–446 (and greps); CONCEPT-REGISTER (rows CON-0013, 0014, 0019, 0163, 0144, 0174; header; greps); RESEARCH-PURPOSE L28–35; PRINCIPAL-TESTIMONY (grep); TODO-TRIAGE-S0100 (grep M7); BOOK-OUTLINE (grep).
  - Directory listings of the copy.
  - Inadvertent: two lines of BASELINE-REVISIONS.md, through grep output (see section 7).
- Corpus (allowed list):
  - S0018 L61–62, L67; S0027 L17, L23–26, L158; S0029 L29, L37; S0044 L26–31, L37–43; S0065 L7; S0079 L55–57; S0080 L46–47;
  - S0190 L107–117; S0302 L8, L82; S0303 L1500–1522 (and wc); S0306 L280–292; S0307 L890–920; S0310 L1296–1300, L1446–1481, L1574–1586; S0311 L434–446;
  - S0315 L16 (grep), L108–112; S0317 L140–150, L1015–1027, L1092–1132, L1470–1480; S0320 L44; S0322 L29, L33, L40; S0324 L30, L67, L81; S0325 L26, L48, L73, L105; S0327 L9, L35, L68, L78.

## Commands and searches
1. `cat` of the six consolidation-folder files and REVIEWER-INSTRUCTIONS.
2. `ls` of the copy, its chapters 04, 05 and 08, and `tmp/tools` (names only).
3. `awk` and `grep` on CORPUS-ALLOWED.tsv for the S-IDs above.
4. `sed -n` and `grep -n` reads as listed under "Files opened".
5. `grep -rn "BC-7" chapter_0[0-5]/` on the copy.
6. `grep -rlni "stale" chapter_04/`.
7. `grep -rni "re-analys" .` and `grep -rni "staleness" .` in the copy's manuscript directory. These reached BASELINE-REVISIONS (disclosed in section 7).
8. Phrase greps over the copy's chapters, THEORY-MAP, CONCEPT-REGISTER, BOOK-OUTLINE and TODO-TRIAGE for: ARB-persona; opposite ways; most pairs of states; incomparable; unsatisfiable on the record; would make the condition satisfiable; independent instance; S0080 L47; S0044 L28; Ch. 1 RC-25; Ch. 8, planned; trivial abstainer passes; MATCH can only follow; registrations sit elsewhere; S0320 L44; git-history evidence held; 81.3; files earlier; contradiction within/holds both; lifecycle and policy; no counterpart; six; never ran; interpret*; three answers; R8/L48/regardless; S0190/S0184/false attribution; precedent/L78; operand/(p1); S0315 L110; 1ef0325c; deadlock/not a cycle/same structure; RD-; NC/passes/CR = 0.
9. `python3 -I` computations:
   - the B-04 means (80.84, 81.34, 80.34);
   - the A5 fractions (by hand from the formula);
   - a summary of the R3 output → `cons3-i-r3-summary.txt`.
10. `python3 -I tmp/tools/register_home_check.py --root <copy>` → `cons3-i-r1.txt` (1 flag, CON-0133 H4).
11. `python3 -I tmp/tools/inbound_citations.py --root <copy>` for: §4.1 C5, §5.7 C1, §5.4 C7, §5.6 C6, §5.3 C1, §4.1 C1, §4.1 C4, §4.1 C6, §4.3 C1, CON-0163, §5.5 C1, §8.65 C4, §8.62 C1, §8.63 C1, §8.66 C3, §8.66 C1, §8.66 C4, §8.65 C2, §8.62 C3, §8.63 C3, §8.68 C3, §8.71 C4, §8.71 C2, §8.69 C2, §8.62 C4, §8.72 C1, §8.72 C3, §8.72 C5, §8.72 C2, §8.69 C4 → `cons3-i-r3.txt`. The harness also persisted one display of it to `~/.claude/projects/…/tool-results/b5qwftdlk.txt`; I did not open that file.
12. Not run: R2 (undefined_terms.py) and the quote check. The reviewers ran both, and this commission asks me to verify findings, not to re-review.

Scratch files: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons3-i/` (`cons3-i-r1.txt`, `cons3-i-r3.txt`, `cons3-i-r3-summary.txt`).

What I did not do: I ran no git commands, opened nothing in `00-access/`, and opened no working chapter, map, register or outline. I edited nothing in the repository and sent nothing to an outside service.
