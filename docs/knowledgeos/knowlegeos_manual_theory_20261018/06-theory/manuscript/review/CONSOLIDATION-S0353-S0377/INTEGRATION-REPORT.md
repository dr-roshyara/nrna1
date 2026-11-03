CONSOLIDATION S0353–S0377: INTEGRATION REPORT

Integration reviewer. The object is the frozen copy `…/CONSOLIDATION-S0353-S0377/BOOK-UNDER-REVIEW/` only. I worked under INTEGRATION-COMMISSION.md, REVIEWER-NOTE.md and REVIEWER-INSTRUCTIONS.md. "Checked" means I read the cited copy or source lines myself. "Reported" means I took the reviewer's evidence without re-reading it.

## Summary
1. Six reports, 82 findings. After merging duplicates there are 76 distinct items. I confirmed 1 as critical (C-F2), 13 as high and the rest as normal. I rejected 1 (D-13), turned 1 into a question (D-15), and found 4 new defects (N-1 to N-4).
2. **Critical (C-F2).** §9.16 R2 (a) says "the merged record is in D unless the merge is refused". §9.17 R3 (c) states the opposite: a fork, or a foreign move, takes the merged record out of D. At least three places rely on R2 (a): the T1 row (L73), the Lemma D row across records (L69), and the internal guarantee at L409. The effect is a narrower scope, not a collapse, because Q1 refuses such records (fail-closed).
3. **Main pattern.** Decided forks have not reached older passages. F26 is (b) at ties. F22 is (C + O). U2 places g1 in the kernel. F36 is adopted. R3a decided the laws' classes. The related findings are A-01, A-02, A-05, A-06, A-09, C-F3, C-F4, D-02, and N-1 to N-3.
4. **Real theory issues.**
   - D-01: g1's U case over-approximates when one atom feeds both *ref* and *sup*.
   - A-04: Lemma D's conclusion lacks the "losses on record" exception that proof step 1 uses.
   - A-11: CON-0173's step 4 is a sketch, not a proof.
   - D-07: T3's "exactly when" drops the hypotheses H-down and H-mono.
   - F-05: "D's clauses have a model" rests on the empty record only.
5. **Concept verdicts.** All 40 concepts in scope have a verdict. One verdict is modified: CON-0177 is VALIDATED only for cuts that cross no recorded loss which drops identities. After tombstones it stays PROVISIONAL, because it rests on §9.16 R5's reading, which is returned to the Principal.
6. **Decisions in scope.** The copy matches the record in substance. It adds Professor's consequences that need a Ded label, not a decision (Task 8). Every U line still says "decision record entry to be made", although the addendum of 2026-11-01T17:32:50Z exists (N-4). No finding about these decisions needs a question to the Principal.
7. **Coverage gaps.** No reviewer checked the chapter summaries (SUMMARIES/08, 09 and the block summaries). Reviewer E read the C93, C95 and C96 inventories only in part. No reviewer checked Ch. 9's SEQ order as a whole chapter.
8. **Access deviations.** None of the findings I sampled rests on a read outside the copy or the allowed list.
9. **Limits.** I re-ran no model. Where the R3 tool could not resolve a sub-section target (for example "§9.16 R2", "§9.15 R3", "§9.18.5", "§8.90.3"), I searched named copy files by grep.

**Process independence:** independent in process only. I am the same kind of model as the author and the reviewers, and I worked from their reports and the frozen copy.

## 1. Findings (ID(s) · verdict · severity · chapter · evidence · correction)
| ID(s) | Verdict | Sev. | Ch. | Evidence | Correction |
|---|---|---|---|---|---|
| C-F2 | confirmed | **critical** (F4: the book states the premise false, and dependents use it) | 9 | Checked: 09.16 L104 (a), L67, L69, L73, L409; 09.17 L164–173 (a fork, or a single foreign move, leaves the merged record outside D) | (a): "…unless the merge is refused **or the merged record fails D's designation clauses (§9.17 R3 (c))**". L73: drop "which R2 gives". L69, L409: add the same condition. Treat with A-04 (Lemma D) in one restatement. |
| A-01 | confirmed | high | 9 | Checked: 09.00 L13, 14, 18, 19 against §9.3 C1/C4 statuses, §9.8.10, §9.9.11 L137–141 | Restate rows 9.3, 9.4, 9.8 and 9.9 from their restated cores. Merged with N-3, which covers row 9.16. |
| A-02 | confirmed | high | 9 | Checked: 09.07 L131 lists "F26 (b)" as excluded; 09.13 L270 says "F26 (b) at ties is now in Θ_f, v8" | Write "F26 (b) beyond ties". |
| A-03 | modified | high | 9 | Checked: 09.09 L81, L125–126, L145; register CON-0182 and CON-0161; map L301; 09.16 L370–384 (R7 "adopted provisionally … for C19's review") | Mark "candidate axiom" as *classified as a world assumption under the provisional §9.16 R7*, not as "superseded". Correct the index attribution: CON-0161's invalid form is "does not reduce/dischargeable". Do this together with C-F8 (R7's status after C19). |
| A-04 | confirmed | high | 9 | Checked: 09.08 L36, L50–60 (step 1: "apart from losses on record"); 09.13 L274–277 | Add the exception to the lemma. CON-0177 verdict as modified in §3. |
| C-F1 | confirmed | high | 9 | Checked: 09.15 L94 and L138 against L141 and 09.13 L51 (the v9 mark reads the recorder) | Use C's restatement: no clause reads attribution; one mark, the owner check and a D clause read recorded claims. |
| C-F3 (+ B's out-of-scope note on F36) | confirmed | high | 9 | Checked: 09.16 L68, L74, L81, L82 against L25, L359 and §9.11 L96 | Restate the four rows. |
| C-F4 | confirmed | high | 9 | Checked: 09.16 L191 ("not F") against L264 (W-3: every recorded use counts) | Add a "superseded" note. 09.13 L37 has the same wording; check that its note is present. |
| D-01 | confirmed; evidence argued, not modelled | high | 9 | Checked: 09.18 L70, L195–201. A Kleene OR of U and U is U, while every completion of ¬o ∨ o gives T. Whether one atom can enter both *ref* and *sup* in D is D's reading. | Restate the U row ("ref or sup is U under Kleene"). Mark L200's candidate classes as an over-approximation, or compute them over atoms. Keep L201's "only when". Resolved by the restatement or by a ce_model check. |
| D-02 (+ D App. B §9.10 L174) | confirmed | high | 9 | Checked: 09.19 L72, 95, 109, 132, 149, 168, 193, 215, 338 against L396 (U2 decided) | R3 notes. Label "Q4's reference no longer waits on F13" as the Professor's Ded (see §5). |
| F-01 | confirmed (recount: interpretation) | high | 8 | Checked: 08.90 L77 against 08.91 L92, 09.20 L78, 09.18 L179 | Write "returned, not recorded". Whether S0366 (B) counts as an occurrence is an interpretation; record it, and leave opening the fork to the Principal (W-10). |
| A-05 | confirmed | normal | 9 | lines named by A | Notes "decided: (b) at ties" |
| A-06 | confirmed | normal | 9 | Checked: 09.06 L1, L7, L217, L238 | status note |
| A-07 | confirmed (content missing for §9.9.8) | normal | 9 | reported; pointers checked via A's 08.24 and 08.83 references | as A |
| A-08 + D-12 (T9-01, T9-02 cells) | confirmed | normal | 9 | Checked: TODO L11, L12 | One edit of T9-01: drop S0167, add S0357, remove "Q4 conditional on G7". T9-02 next action: "the Open rows of §9.20.7 (F13 open)". Add rows for §9.20.9 (i) and (iii). T9-14: the routing as in A. |
| A-09 | confirmed | normal | 9 | Checked: 09.08 L29 against L50 | note |
| A-10 | confirmed | normal | 9 | Checked: 09.00 L37; 09.06 L116–119 | glossary |
| A-11 | confirmed (countermodel valid) | normal | 9 | an XOR model | State the premise, or restate the step. |
| A-12, A-13, A-14, A-16 | confirmed; A-12 and A-14 reported; A-13 and A-16 checked | normal | 9 | 09.01 L40, 97, 112–113; 09.06 L122 ("64 small cases": reported) | as A. For A-13, "consume" in §9.1's toy model differs from §9.13.2's consumption kind; gloss it. |
| A-15 | confirmed | normal | 9 | Checked: 09.19 L394 | pointer, labelled Ded |
| A-17 + C-F15 | confirmed, merged | normal | 9 | Checked: cell count. TODO L18 has 8 of 9 cells; the last cell is a history note. 09.99 L91–92 have 3 of 4 cells. | Fix the cells. §9.5 References: L182 → L181 (reported). |
| B-F1 | confirmed | normal | 9 | Checked: 09.10 L60 against L62 | as B |
| B-F2 | confirmed | normal | 9 | Checked: 09.13 L69 | as B |
| B-F3 | confirmed | normal | 9 | Checked: 09.13 L41, L44–45 | as B |
| B-F4 | confirmed | normal | 9 | Checked: 09.12 L65, L81 | I1–I15 |
| B-F5–B-F10 | confirmed on B's evidence; B-F8 and B-F9 checked logically | normal | 9 | reported | as B |
| B-F11 | confirmed | normal | 9 | Checked: 09.13 L356 ("current version v23") against TODO T9-17 (v24) | one current version |
| C-F5–C-F7, C-F9, C-F10, C-F12, C-F13 | confirmed on C's evidence | normal | 9 | reported | as C |
| C-F8 | confirmed | normal | 9 | Checked: 09.15 L176 ("provisional until C19's review"); L10 shows C19 happened | State R7's status (joint with A-03). |
| C-F11, C-F14 | confirmed | normal | 9 | Checked: S1375 L971–991 (five bounds); S0090 L11 (one verifier, INV-ATTR-2) | as C |
| D-03 (+ B's out-of-scope note on 09.19 L167) | confirmed | normal | 9 | Checked: 09.19 L167 against 09.10 L161–162 | as D |
| D-04 | confirmed | normal | 9 | Checked: S0237 L209, L298 | as D |
| D-05, D-06, D-09, D-10, D-11, D-14 | confirmed on D's evidence; D-10 checked (09.20 L47 against the L139 row) | normal | 9 | reported | as D |
| D-07 | confirmed | normal | 9 | Checked: 09.19 L29, L242–244 | "if; only if under H-down and H-mono" (also TODO T9-04) |
| D-08 | confirmed (argument valid for finite 𝒦) | normal | 9 | Checked: 09.19 L270 | as D |
| D-13 | **rejected** as a copy defect | — | outline | The rotation row for this consolidation is due at settlement, so its absence from the frozen copy is expected. | Settlement task, not a correction. |
| D-15 | converted to a question (§5) | — | 9 | reported | — |
| E-01, E-05, E-06, E-08, E-11, E-12 | confirmed on E's evidence | normal | 8 | reported | as E |
| E-02 | confirmed | normal | 8 | Checked: 08.87 L59 against 08.89 L50 | Mirror the premises. |
| E-03 | modified | normal | 8 | Checked: 08.88 L62, L69; S0364 L128. E paraphrased L62 backwards: L62 makes (A)'s premise hold when "constitutional evaluation" means D's clauses and the guards, which is exactly the reading under which the two-key agreement fails. | E's conditional restatement stands. |
| E-04 | confirmed | normal | 8 | Checked: S0353 names GO-01, 02, 03, 05, 07, 08, 12–18, 20, 21, 22, 25 (17 numbers; GO-16 only in "GO-15/16/17/25"); 23 and 24 appear only in the suite range | as E |
| E-07 | confirmed | normal | 8 | Checked: S0359 L158–170 | as E |
| E-09 + F-07 | confirmed, merged | normal | 8 | Checked: 08.00 L116 | Extend the forward-use note for §8.82–§8.98. |
| E-10 | confirmed (wording collides with the verdict vocabulary) | normal | 8 | reported | as E |
| F-02, F-04, F-08, F-09, F-10, F-11, F-12 | confirmed on F's evidence; F-09 (c) checked (09.18 L179) | normal | 8 | reported | as F |
| F-03 | confirmed | normal | 8 | Checked: S0372 L99 (the list and "speculative until ratified"); 08.95 L60 | as F |
| F-05 | confirmed | normal | 8 | Checked: 08.92 L82; 09.20 §9.20.8 ("can be read as", "not done here") | "trivially satisfiable; non-trivial satisfiability not shown" |
| F-06 | confirmed | normal | map | Checked: map L448–449 | Advance the position and counts. |
| **N-1** (new) | — | normal | 9 | Checked: 09.08 L50 and 09.13 L276 tie Lemma D across records to "a condition on F26 (c)"; F26 has been (b) at ties since v8. A-05 does not list these lines. | Use the same note as A-05. |
| **N-2** (new) | — | normal | 9 | Checked: 09.16 R2 (c) "as §9.13.2 does now" (positions), under v3's note | Mark "rejected tie rule". |
| **N-3** (new) | — | normal | 9 | Checked: 09.00 L26 (row 9.16): "with ties by content … by position not; ties by position need one now"; "merged … in D, cuts automatic" | Restate the row (ties unordered; C-F2's condition; cuts "evaluable"). |
| **N-4** (new) | — | normal | 9 | Checked: 09.19 L394–399: each U line says "decision record entry to be made by the Governance Engineer", but the confirmation addendum 2026-11-01T17:32:50Z exists. The U6 quotation is the copy-ready line, not the addendum text. | Cite the addendum, and quote its text. |

## 2. R3 (correction · inbound line · disposition)
| Correction | Inbound line | Disposition |
|---|---|---|
| C-F2 | 09.16 L73 (T1 row) | needs the same change |
| C-F2 | 09.16 L69 (Lemma D across records) | needs the same change, plus A-04's exception (one restatement) |
| C-F2 | 09.16 L67 ("mergeable") | needs a different change: add "and the union in D". "Mergeable" is a definition; it does not assert membership. |
| C-F2 | 09.16 L409 (internal guarantee: "dangling … and conflicting copies refused") | needs the same change: add the designation-clause failures |
| C-F2 | register CON-0194 ("are in D, and every cut across records … is in D") | needs the same change. "Cut is in D" also needs "evaluable" (B-F2 / I-09). |
| C-F2 | 09.00 L26 row 9.16 | needs the same change (N-3) |
| C-F2 | 09.13 L278; 09.13 L276 | stay true ("only if the merge is in D"). L276 needs N-1 separately. |
| C-F2 | 10.05 L90–96, L310 (PL-4) | not affected: they state the merge rule, not membership of D |
| C-F2 | TODO T9-18; 10.05 L144, 162, 231, 308, 311, 317 | not affected (they cite R4a–R4d, R1 or identity) |
| C-F2 | §8.15, §8.17–§8.19, §8.39, §8.53, §8.90 (C's list) | not affected. The tool resolves no "§9.16 R2" target. My grep of those seven named files for the "merged record … in D" or "R2 (a)" wording found nothing. |
| A-01 | CON-0111 lines (05.07 L15, 06.05 L91, 08.28 L19, outline L53, map L277); CON-0114 (map L283, outline L170) | stay true (A, accepted) |
| A-02 | 09.08 L29 | needs a different change (A-09's note); 09.10 L61 stays true; 09.13 L270 stays true (the authority) |
| A-03 | map L301; register CON-0182 | need the same change |
| A-03 | 09.00 L19 | needs a different change (the attribution) |
| A-03 | 09.09 L71 (T2 tag) | not affected |
| A-03 | 09.12 L68, 09.15 L55/L68, CON-0193 | not affected |
| A-04 | register CON-0177; map L315; 09.13 L274–277 | need the same change |
| A-04 | 09.08 L98 | needs the same qualifier |
| A-04 | 09.10 L194, L249 | needs the same qualifier at past cuts (reported by A) |
| A-04 | 09.19 L197; 08.00 L116; 08.86 L51 | not affected |
| A-04 | map L450 | not affected (counts only) |
| A-11 | the 13 lines A lists | stay true in wording; only the derivation changes |
| B-F1 | 08.31 L23/L66, 09.11 L51, 09.13 L279, 09.16 L76, 09.20 L74, 10.03, 10.05 lines | stay true. 09.99 L97 needs a different change (the note cleared). |
| B-F4 | outline L174, 09.00 row 9.12 (L22) | need the same change; 09.16 L83 stays true |
| C-F1 | 08.32 L75–76; 10.05 L14, L373 | stay true (they cite R3's conclusion). The tool does not resolve "§9.15 R3", so I did not grep these myself; this rests on C. |
| C-F3, C-F4 | 09.11 L96, 10.03 L113; 08.32, 08.14, 10.03 L70 | stay true (C) |
| D-01 | 08.39 L17 | needs a different change (candidate wording); 08.65 L125 not affected; TODO T9-17 and T9-19 need the same change ("U-case reviewed: over-approximation") |
| D-02 | TODO T9-01 L11; outline L179; 09.10 L174 | need the same change; 09.20 L29 not affected |
| D-07 | TODO T9-04; 08.63 L19 | T9-04 needs the same change; 08.63 not affected |
| E-02 | 08.89 L13, L49; 08.94 L73 | stay true (precedents) |
| E-03 | 08.00 L109; 08.93 L22, L71 | need the same change. The other 12 lines are not verified individually (E); 08.99 L131 is history and not affected. |
| F-01 | 08.96 L58 ("second occurrence") | needs a different change: an interpretive recount, flagged for the Principal's fork decision |
| F-01 | map L267, L271 | need the same change |
| F-02 | the six lines F names | need the change; the confidence uses stay true |
| F-05 | 08.94 L80 | needs the same qualifier; 08.94 L21 and 08.96 L3, L20, L81 stay true |
| All other confirmed corrections | none, or the lines the reviewer listed | as the reviewer reported; I did not re-run them |

## 3. Conflicts between reviewers
| Pair | Resolution | Reason |
|---|---|---|
| A (CON-0177 VALIDATED, scope corrected) and A's own proof-step judgement ("inadequate, premise missing") | VALIDATED for cuts that cross no recorded loss dropping identities. PROVISIONAL for post-tombstone values. | Post-tombstone values rest on §9.16 R5's reading, which is "returned" (09.16 L71). A verdict cannot exceed an undecided premise. |
| A-04 and C-F2 on the Lemma D row (09.16 L69) and 09.13 L274–277 | one joint restatement | They add different premises (loss exception; merged record in D). Both are needed, and they are compatible. |
| A-13 ("consume" undefined, so result 5's (iii) is open) and B (CON-0189 VALIDATED via result 5) | B's verdict stands | §9.11 R1 reads consumption as §9.13.2's entry kind, which is defined. A-13 is a local gloss defect in §9.1's toy model. |
| A-03 ("superseded by R7") and C-F8 (R7's status after C19 unstated) | joint correction | R7 is provisional, so "superseded" overstates. State R7's post-C19 status first, then re-label. |
| A-17 and C-F15 (TODO T9-08) | merged | It is one defect: one cell is missing, and the last cell holds a note. |
| E-09 and F-07 (08.00 L116) | merged | It is the same note; one extension covers §8.82–§8.98. |
| A-08 and D-12 (T9-01) | joint edit of one cell | They raise different stale items in the same cell. |
| D-02 and D-03 (Q4 and F13) and the T9-02 ruling ("decides neither F13") | no conflict | "Q4 no longer waits on F13" follows from U2 as the Professor's Ded. It does not decide F13. Label it Ded. |
| E-03's paraphrase and the copy's L62 | E's correction kept, paraphrase corrected | See §1. |

## 4. Defects in the rotated chapter (Ch. 9), confirmed, by reviewer
- **A:** A-01 to A-17 (A-17 merged with C-F15).
- **B:** F1 to F11.
- **C:** F1 to F15 (critical F2; F15 merged).
- **D:** D-01 to D-12 and D-14. D-13 is rejected; D-15 is a question.
- **New:** N-1, N-2, N-3, N-4.

## 5. Decisions in scope (Task 8)
**What the record decides, against what the copy has:**
- **U5 "(a) EF".** The copy matches. It also says "§9.5 C5's residue is closed at EF" and puts law 20a in applications. The second is part of option (a) as asked. The first is the Professor's consequence: label it Ded (A-15).
- **U6.** The copy quotes the copy-ready line, not the addendum's "one requirement per act kind; D clauses for the five kinds outside D to follow in a later authorised turn (TODO T9-21)". The substance is the same (N-4).
- **U2 "(a) g1's class, in the kernel".** The copy's "Q4's reference no longer waits on F13; what remains of G7 does not block Q4" goes beyond the record. It is a Ded consequence; label it so. The U-case wording (D-01) is the Professor's text and is not decided by U2, so it is corrected by theory, not by a question.
- **U1 "(b) later, after reading resumes".** The T9-02 turn ran 2026-11-02 with reading at S0366, which is consistent. The stale map position (F-06) makes it look otherwise.
- **U3 "(b) kept unfixed; results stay scoped to (A, Req)".** The copy matches ("results scoped").
- **U4 "(d)".** The copy matches.
- **T9-02 ruling.** It is quoted correctly in TODO, §9.20.7 and §9.20.9. Stale: §9.20.8 still says "the deliverable's form put to the Principal", and TODO T9-02's next action "the attribute table (F13)" contradicts the retyped deliverable (D-12). Both are edits. No row is treated as closed beyond the ruling.
- **All U lines.** They say the decision record entry is still to be made, but the addendum exists (N-4). This is an edit.

**Questions for the Principal:** none from these decisions. One from D-15: should the book itself name outside authors and eponymous theorems, as at §9.18 L103, §9.20 L102 and §9.10 L33? Options: (a) keep the names; (b) describe them without names; (c) keep only standard theorem names.

## 6. Coverage gaps (not filled)
- `SUMMARIES/08.SUMMARY.md`, `SUMMARIES/09.SUMMARY.md` and the block summaries for Ch. 8 and Ch. 9 (SCOPE §2.1): no reviewer read them, and they were not in the coverage plan. Summaries of the other chapters are unchecked by design.
- Window inventories: E read C93 to L139, C95 to L70 and C96 to L75; items beyond those lines have no status. C89, C90 and C94 were read to the cross-source table.
- SEQ order: checked per section (A-14, C-F13, D-14, E-09/F-07). No reviewer read Ch. 9 as one reading order, and Ch. 8 was checked only for §8.82–§8.98.
- Concepts: all 40 have verdicts. No concept in scope is left without one.
- Not checked by any reviewer: R1 by B, E and F (I ran it: 241 rows, 0 flags); model outputs (ce_model, helly_check, req_a_model, t902_state_model); §10.3.6 and §10.4 as homes of T9-19.

## 7. Access deviations (weighed, not re-run)
- **A, D, F, and C's first item.** The tools ran with `--help` and no `--root`, so on the working book. The output was disclosed as unused. All findings I sampled (A-01 to A-04, D-01 to D-04, D-07, F-01, F-03, F-05) reproduce on the copy, so none rests on that output.
- **A.** The harness-saved file is a storage location, not a read.
- **B and E.** The `ls` calls were on copy folders. E's glob calls read nothing.
- **C's second item (02-FILES.jsonl).** The sources behind C-F11 and C-F14 (S1375, S0090) and S0237 are on CORPUS-ALLOWED.tsv (checked), and their cited lines confirm the findings. No finding I sampled rests on that index's contents.
- **Forbidden IDs.** None of S0155, S0156, S0157, S0161, S0170, S0174, S0233 or S0249 appears on the allowed list (checked: 0 hits each).

## Files opened
- Consolidation folder: INTEGRATION-COMMISSION.md, REVIEWER-NOTE.md, SCOPE.md, REPORT-A.md to REPORT-F.md.
- `03-methodology/REVIEWER-INSTRUCTIONS.md`.
- Copy, `06-theory/manuscript/`, named lines only, by sed or grep:
  - chapter_09: 09.00-index.md (L11–40 and filename grep); 09.03 (L7, 13, 31); 09.06 (L1–8, 113–124, 215–224, 238); 09.07 (L131–132); 09.08 (L29, 36, 50–60, 67, 129–135); 09.09 (L81, 125–126, 137–141, 145); 09.10 (L58–62, 161–162, 174); 09.12 (grep I1–I14/15); 09.13 (L26, 37, 41, 44–45, 51, 69, 270, 274–278, grep v23/v24); 09.15 (L94, 138, 141, 176, grep C19); 09.16 (L40–44, 64–110, 186–192, 264, 370–384, 405–412, grep R7); 09.17 (L159–180, 302); 09.18 (L62–72, 98, 179, 195–203); 09.19 (grep G7, T3, H-down; L29, 114, 167, 364, 390–410); 09.20 (L1–12, 45–52, 78, 135–170); TODO.md (L1–40).
  - chapter_08: 08.00-index.md (filename grep; L111, 114, 116); 08.82 (grep GO-, twenty); 08.87 (grep T7); 08.88 (L62, grep two-key); 08.89 (grep T7/Premise); 08.90 (L76–77); 08.91 (grep C6); 08.92 (L82); 08.95 (L5, L60); 08.96 (L58); 08.15, 08.17, 08.18, 08.19, 08.39, 08.53 (grep only).
  - chapter_10: 10.05-published-language.md (L90–96 and grep).
  - THEORY-MAP.md (L301, L446–450); CONCEPT-REGISTER.tsv (rows CON-0161, 0182, 0193, 0194).
- Lane tools, read-only: tmp/tools/inbound_citations.py (head).
- Corpus (allowed list): CORPUS-ALLOWED.tsv (greps); S0353 (GO grep); S0359 L158–170; S0364 L128; S0372 L8, L99; S0090 L10–12; S1375 L971–991; S0237 L209, L298 and grep.
- I opened nothing in `00-access/`, no working chapter, map, register or outline, and no forbidden source.

## Commands and searches run
1. `mkdir` of the scratch directory; grep of filenames from the copy's 09.00 and 08.00 indexes.
2. `register_home_check.py --root <copy>` → r1.txt (241 rows, 0 flags).
3. `inbound_citations.py --root <copy>` with "§9.16 R2" and CON-0194 → r3-c2.txt; with CON-0177, CON-0182, "§9.18.5", "§8.88 C5", "§9.15 R3", "§8.90.3" → r3-main.txt. Sub-section targets were unresolved (home: —).
4. `sed -n` and `grep -n` on the copy and source lines listed above. One grep over named files only (08.15, 08.17, 08.18, 08.19, 08.39, 08.53, 08.90, 09.13, 09.15–09.20, 10.05) for the R2 (a) wording.
5. A Python cell count of the TODO.md and 09.99 tables (one awk call errored and was not used).
6. awk on the copy's register for CON-0194's text.
7. grep of CORPUS-ALLOWED.tsv for S0090, S0237, S0353, S0359, S0364, S0366, S0372, S0374, S1375, and for the eight forbidden IDs.
8. No git command, no glob, no recursive search, no directory listing. Nothing in the repository was edited.

Scratch outputs are in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons-s0353-int/ (r1.txt, r3-c2.txt, r3-main.txt).
