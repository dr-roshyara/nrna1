CONSOLIDATION S0303–S0327: REPORT A

Reviewer A. Object: the frozen copy BOOK-UNDER-REVIEW only. Sections: Ch. 4 (04.00–04.03, 04.99, TODO) and Ch. 5 (05.00–05.07, 05.99, TODO), each read in full.

## Summary (facts first, then interpretation)
1. No critical finding. 2 high, 17 normal, 5 low (table below).
2. Facts. Every quoted source line I opened matches (Ch. 4: S0027, S0026, S0028, S0040, S0044. Ch. 5: S0023, S0024, S0025, S0029, S0030, S0052, S0053, S0055, S0058, S0061, S0065, S0076–S0080, S0129, plus S0896/S2377 spot lines). The quote tool reports 0 not-found across all sections.
3. The tool covers only anchored quotes. Line-number cites I did check had these errors: §5.7 C1 cites L47 where L46 is meant; §4.3 C1 cites L28 where L29 is meant; §5.3 C1 gives a scope-overreaching contrast. Details are in the table.
4. Largest risk (interpretation): the consolidation notes were appended beside the text they correct, and the stale text was left in. Three cases give a reader two contradictory statements: §4.1.2 (A1), §5.7 C1/5.7.4 (A3), §5.4 C7 (A5). A fourth is a stale sentence in §4.2 C4 (A24, low).
5. Second risk: INDEX/HOME. CON-0013, 0014, 0019 and 0163 have no home in Ch. 4 or Ch. 5. That contradicts SCOPE §2.2 and the register's "planned chapter_04/05". CON-0163's second half also has a dangling pointer (A2).
6. Arithmetic or numbering slips in the book: "six words" for a 4-token act (A6). The malformed homonym-table row in 05.00 (A7).
7. Concept verdicts: 13 VALIDATED confirmed (CON-0013/0014/0019 with a caveat on home). 11 PROVISIONAL stay PROVISIONAL. 14 INVALID cores hold as restated. CON-0163 is the weakest of the PROVISIONAL.
8. Not covered: the S0208, S0721, S1296, S0913 and S2834 lines cited in §5.4/§5.5. S0896 and S2377 were spot-checked only. Details are in "Coverage of sections".
9. The R3 tool takes only claim IDs, so free-text claims were not run. Where a correction touches a quoted claim, I give the inbound lines I could read.

## 1. Findings

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|---|
| A1 | 04.01 §4.1.2 L17–18 and TODO T4-03 L11 | Contradiction. L17's correction says no graded notion can establish independence on the record (§8.06 C3), so the promotion condition is "unsatisfiable on the record". L18 says a graded notion "would make the condition satisfiable". T4-03's resolution criterion ("an independent instance") and M7's "T4-03 has a criterion that can be met" cannot both hold with L17. §8.06 C3 (Ded) shows only that no shared factor can be concluded from an incomplete record. It does not show that no instance is independent. | high | §8.06 L55–58 (premise R ⊆ F, open world). §4.1 L17–18. TODO L11. TODO-TRIAGE M7 L66–73. | Reword L18: a graded notion makes independence satisfiable in the world but not establishable on the record. State which of the two the promotion condition requires. Change T4-03's criterion to "an instance whose independence is declared with its search stated", or route the criterion to DB-2. | §4.1 C5 cited by §8.06 L10, L70, §6.4 L20 (unaffected). T4-03 cited by TRIAGE M7 L73 (needs the wording change). | L18, T4-03 and M7 agree |
| A2 | 04.00 L3; Ch. 1 §1.6 L42; register CON-0013/0014/0019/0163 | Home/loop. 04.00 says the kinds of change live in Ch. 1 §1.6 and that "this chapter does not defer to it in turn". Ch. 1 §1.6 L42 defers to "§4.1" for "re-analysis and staleness also lower it". Ch. 4 §4.1 says nothing about that. S0018 L62 says evidential progression falls "only by refutation", so CON-0163's second half has no cited support. CON-0013/0014 are homed in Ch. 1 §1.17.1 (L16–18). Register `book_location` says "planned chapter_05" or "planned chapter_04", and no such Ch. 4/5 text exists. SCOPE §2.2 ("all are homed in Ch. 4 or Ch. 5") is therefore inaccurate for CON-0013/0014/0019/0163. | normal (known: M13, "break the §1.6 ↔ Ch. 4 loop") | 04.00 L3. 01.06 L36–42. 01.17 L16–18. S0018 L61–68. TRIAGE M13 L115–121. R3 `CON-0163`: only 01.06 L42, 04.00 L3, register L21/L164, BOOK-OUTLINE L115. | Remove or source "re-analysis and staleness" in §1.6 L42, or give it a Ch. 4 text. Update the register `book_location` for CON-0013/0014/0019/0163 to their actual Ch. 1 homes until M13 is decided. | the lines above | M13 closed |
| A3 | 05.07 L29 and 5.7.4 L100 vs L32 | Stale text. L29 and L100 say S0079 L57 refuses to close "a lane that never ran". L32 (N-1) and L26 correct this: the L57 lane was HANDED_OFF and started, with no eligible deliverable. The restated rule (deliverable ∧ (lifecycle ∨ defect)) survives, because L57 still refuses a lane with no output. The cited reason is wrong in two places. | normal | S0079 L55–57: "HANDED_OFF, started … produced no output … completing it would assert it finished work it never did". | In L29 and L100 replace "never ran" with "no eligible commissioned deliverable". | `§5.7 C1`: register CON-0144/0174, THEORY-MAP L294/L297 (not read), 05.00 L46, 08.11 L20. | L29 and L100 agree with L32 |
| A4 | 05.07 L29 | Mis-cite. "a lane with no output may still be ended by CANCEL (S0079 L57; S0080 L47)". L46 is the row for the no-output lane (CANCEL, "produced no output"). L47 is a different lane. | low | S0080 L46–47. | Cite L46. | — | — |
| A5 | 05.04 C7 L112–113 | Stale text. "Most pairs of states are incomparable" is in the text, and the next bullet says it "is not computed here and is withdrawn". Computed here (reported, my script): on P(R) under inclusion, comparable ordered pairs = 2·3ⁿ − 2ⁿ of 4ⁿ. That is 0.72 (n=3), 0.57 (n=4), 0.44 (n=5), 0.20 (n=8). So the claim holds on the power set only for |R| ≥ 5. It is not shown for the book's states. | normal | scratch computation. S2377 L992–1022 context not re-read. | Delete the sentence, or keep it as "for |R| ≥ 5 on P(R)". | `§5.4 C7`: 05.00 L32; THEORY-MAP L373, L376 (unaffected). | |
| A6 | 05.06 C6 L50, L60 | Slip. "The act's six words" and "the act's words are six [S0065 L7]". The quoted act "Accept KOS-ARCH-BASELINE-002 strategic model." has 4 whitespace tokens (7 if the ID is split at hyphens). It is not 6. | normal | `len(split())` = 4 (my computation). S0065 L7 read. | "four words". Also correct PRINCIPAL-TESTIMONY.md L34 and THEORY-MAP L273 ("six-word act"). | `§5.6 C6`: 05.07 L39, L56; 08.* L20; THEORY-MAP L273; PRINCIPAL-TESTIMONY L34 | all four agree |
| A7 | 05.00 L33 (homonym "independent") | The row has 4 cells in a 3-column table. The sense-(3) gloss "not refuted on the record…" sits in a stray 4th cell. | normal | 05.00 L25–33. | Move the gloss into the "Senses" cell. | `05.00 L32` is cited as a table by §5.x only through the index. | |
| A8 | 04.01 C1 heading L22 and §4.1.2 "Consequence" L17 | Label contradicts its own note. C1 is headed "(Ded)" and the note says "Relabelled Int (B-15)". The "Consequence in this estate (Ded)" note says "it is Int". 05.03 C3 is headed "(Src, counted here)" while its note says the conclusion is Int. | normal | 04.01 L17, L22–23. 05.03 L49–50. | Change the headings. | `§4.1 C1`: 04.02 L24 cites "(C1)" as the fact/inference/interpretation separation (no change needed). Register CON-0068 basis says "four kinds" (not re-read in full). | |
| A9 | 04.01 C4 L58 | Attribution stated as fact. "The 'demonstrated' grades come from an 'ARB-persona review' [L17], an AI role." L17 is the row that holds the review verdict. The grades are at L23–26. S0027 does not say the persona is an AI. | normal | S0027 L17, L23–26. | Label "an AI role" Int. State that L17 carries the verdict, not the grades. | `§4.1 C4`: 05.03 L82, 08.02 L23 (both cite the identification point, which is unaffected). | |
| A10 | 04.01 C6 L71 | Omission. The register counts the OE-KOS-3 failure as "a FAILURE-MODE instance: obligation skipped + net blind" (S0027 L158). The book says only that it counts it "as the first instance". The book's reading (a counterexample by the register's own lifecycle) stands. | normal | S0027 L158. | Add the register's own qualifier. | `§4.1 C6` / CON-0071: 08.20 L228; 10.01 L58; 10.02 L143 (unaffected). | |
| A11 | 05.03 C1 L36 | Overreach. "The threshold policies point opposite ways: 'no threshold invented before data' [L37], beside candidate values of ≥100 cycles … [L29]." L37's phrase concerns the p95 save-latency target of one gate. L29's values are labelled "CANDIDATE values". The two do not conflict as stated. | normal | S0029 L29, L37; S0027 L152 ("no thresholds are policy today"). | Reword: candidate thresholds are set at "1 cycle", where L37 states a data-first policy for another gate. | `§5.3 C1`: 05.00 L33; T5-06 (unaffected). | |
| A12 | 04.03 C1 L13 | CON-0113's restated form is described as "valid in the restated form" but is Int, PROVISIONAL (L14). It has no concept ID, unlike CON-0109/0124/0144/0145 (→ 0165/0168/0174/0175). A claim without an ID is outside CV-1. Also, the "used-versus-performed" ground is cited at S0044 L28. The "UNEXERCISED as behavior" row is L29. | normal | S0044 L26–31. R3 `CON-0113`: 04.03 L13, L14, L52; register; THEORY-MAP L253. | Register the restated rule under its own ID and say "PROVISIONAL" in the heading. Cite L29. | listed | |
| A13 | 04.02 L36; 05.00 L33; 05.05 C1 | RD-1…RD-7 are used without a gloss. S0044 L37–43 defines RD-1 (immutable issued record), RD-2 (identity), RD-3 (honest silence), RD-4 (three layers), RD-5 (age), RD-6 (actor provenance gap), RD-7 (decided twice). The C1 heading of §5.5 ("RD-6 is the first observed instance…") never says what RD-6 is. | normal | S0044 L37–43. | Add the list once in §4.3, where S0044 is read. | `RD-6`: 05.05 L20, 08.19 L238; THEORY-MAP L250. | |
| A14 | 05.07 L20, C8, C9 | SEQ: BC-7 is used from §5.7 with no gloss. Its first definition is Ch. 6 §6.5.2. No Ch. 0–5 text defines it (only TODO T1-01/T1-28 mention it). | normal | grep of chapters 0–5. | Gloss in one line at first use, or mark it a forward reference. | — | |
| A15 | 05.02 L51 | Stale. "(Ch. 8, planned)". Ch. 8 exists. The method point is §8.4 C3 (an absence claim needs an untruncated search; CON-0120). | normal | 08.04 L32–34. | Point to §8.4 C3. | none cite the sentence | |
| A16 | 05.06 C7 L67 heading | "provisional, not yet reviewed". The chapter was rotated in full at S0151–S0181 and 5.6 is marked reviewed. Either the label is stale or the points were not covered. C7 has no concept ID. | normal | 05.06 L3, L67; TODO header. | Settle which; update the label. | — | |
| A17 | Ch. 5 TODO (all) | CV-1 §4a (TODO header L5): every INVALID concept is entered. There is no row for CON-0078, 0081, 0109, 0124, 0144, 0145 (Ch. 4: CON-0113). Their restated forms exist for four of them. CON-0081 and CON-0078 are not restated as rows. | normal | greps of every chapter TODO and TRIAGE. | Add rows, or state that restated-form INVALIDs are exempt. | — | |
| A18 | Ch. 4 TODO T4-04 L12 | Partly stale. "cited only through counts; content not assessed". §4.1 C3 (L42, B-12) assesses the L173–175 scope check, and C5 assesses the volume watch (L197–201). OE-KOS-4's own entry is still not assessed. Row also lacks a triage tag, unlike every other row. | normal | 04.01 L42, L65. | Narrow the row. | `T4-04`: none cite it. | |
| A19 | Ch. 4 TODO T4-02 L10 | "Ch. 1 RC-25" points at derivation ≠ attestation. The qualification revision is RC-38 (§1.14.4-12). | low | 01.99 L33, L38; 01.14 L522. | Cite §1.14.4-12 / RC-38. | | |
| A20 | 05.05 C2 L39–40 | Source defect noted: S0077 L26 says "Four independent verification passes"; L18–23 list three (V#1, V#2, V#3). Book already states this (05.07 C2). Confirmed. | info | S0077 L18–26. | none | | |
| A21 | 05.06 C1a / C6 vs 05.00 | Forks F21 and F28 are used in the sections. The chapter index has no fork table. Not an error. | info | | | | |
| A22 | 04.03 / 05.00 | SEQ forward references (Ch. 4 → §8.06, §6.3, Ch. 5; Ch. 5 → §6.5, §8.x, §9.x) are marked as such in the text. No use-before-introduction defect beyond A14, plus one local case: §5.4 C7 uses THM-G2/G4 before C8 defines them. | low | | | | |
| A23 | 05.04 C2 L60 | Source gives a sufficient direction ("every blocking requirement satisfied … ExecutionEligible = True, subject to authorization", S0896 L781–815, L800–815). The book states "only if" (L60). S0896 L780 says "execution is permitted only if", so "only if" is supported. Confirmed. | info | S0896 L780–784, L800–815. | none | | |
| A24 | 04.02 C4 L36 | "not yet read" remains next to its own correction (L37). | low | | Delete L36's last sentence. | | |
| A25 | 05.01–05.03 | Verified against sources: 5.1 packages and quotes; 5.2 counts (4 vs 3 evidence gaps, S0024 L24/L199); 5.3 C3 table rows (S0029 L59–66). No defect. | info | | | | |

**Change logs (04.99, 05.99):** all rows checked against the text they describe. Every "Now reads" form is present in the current section. No mismatch.

## 2. Concept verdicts

**VALIDATED (second, independent verdict)**
| ID | Verdict | Reason |
|---|---|---|
| CON-0013 | VALIDATED | S0017 L36. Defined by resolution mode. Home is Ch. 1 §1.17.1, not Ch. 5 (A2). Maps onto homonym sense (3) of "decision" in 05.00. |
| CON-0014 | VALIDATED | S0017 L37–40. Same home note. Maps onto sense (1) of "decision" in 05.00. |
| CON-0019 | VALIDATED | S0018 L61 ("a further act (supersession)") matches. Home is Ch. 1 §1.6. |
| CON-0048 | VALIDATED (core) | Fields vary by package, as stated. |
| CON-0049 | VALIDATED | S0023 L12, L26. Application partial, as stated. |
| CON-0068 | VALIDATED | Five fixed sections (S0027 L113–121). The "four kinds" restatement is Int (B-15). |
| CON-0069 | VALIDATED | S0027 L130, L132 verified. |
| CON-0070 | VALIDATED as a norm | Scope: confirmatory counts. The R-6 application is a separate, unverified claim. |
| CON-0071 | VALIDATED (definitional part) | Trivially true; the empirical half stays Hyp. |
| CON-0077 | VALIDATED as a design | The record falls short; the design is intact. |
| CON-0079 | VALIDATED, scoped | Independence of the answers, Q2 read as demand. Ded is by example. "Orthogonal" is not shown. |
| CON-0107 | VALIDATED | Source fidelity (S0040 L14–41). Reports no run. |
| CON-0131 | VALIDATED | Proof valid: f(s,x) = f(s′,x) cannot enforce a rule that distinguishes subjects. Hidden premise stated in the text. The engine's subject-blindness is a single probe (S0058 L82–88), not re-run. |

**PROVISIONAL (verdict for each)**
| ID | Verdict | Reason |
|---|---|---|
| CON-0080 | PROVISIONAL | The four open conditions in 5.4.4. Missing/Unknown merge E-kinds. |
| CON-0082 | PROVISIONAL | Depends on which test makes a kind of nothing. |
| CON-0083 | PROVISIONAL | Needs the content-versus-scope dimension decision. |
| CON-0101 | PROVISIONAL | S2377 and S0896 are one line of support. Sources S0896 and S2377 not read in full by me. |
| CON-0123 | PROVISIONAL | One day, one registrar (S0052, S0053). |
| CON-0163 | PROVISIONAL, weak | The "cannot be decreed" half is source-backed (S0018 L62, L67). The "falls by re-analysis or staleness" half has no cited source, and its pointer (§4.1) leads nowhere (A2). |
| CON-0165 | PROVISIONAL | n = 1 (S0044 RD-6). |
| CON-0168 | PROVISIONAL | Two cases, one estate. |
| CON-0174 | PROVISIONAL | The must-do half is the book's norm. A3 stale wording to repair. |
| CON-0175 | PROVISIONAL | Generalises an instruction on one lane (S0080 L47) into a recognition rule (Int). |
| CON-0187 | PROVISIONAL | The trivial case (a gate that refuses everything) needs a must-do half. |

**INVALID (checked as restated, not re-litigated)**
CON-0012, 0023, 0050, 0051, 0052, 0053, 0054, 0078, 0081, 0109, 0113, 0124, 0144, 0145: the core defect holds in each restatement. Rechecked with source lines: CON-0052 (count not reconciled), CON-0053 (S0024 L24/L199 counts; S0025 L155), CON-0078 (S0029 L23, L30, L66 gates fit no family). CON-0023 and CON-0054 have their text in Ch. 1 / the TODO, not in Ch. 4/5. TODO rows are missing for the six listed in A17.

## 3. Coverage (pointers and TODO rows, checked against the copy)
| Item | Status | Home |
|---|---|---|
| 04.00 kinds of change (CON-0019/0020/0163, 0021, 0022) | open, loop | Ch. 1 §1.6; M13 |
| 04.00 TARGET-TOC M13 | open | TRIAGE M13 L115–121 |
| 04.01 "§8.06 C3", "DB-2" | still valid | §8.06 L55–58; TRIAGE M7 |
| T4-01 (CON-0022) | still open | M13 |
| T4-02 (CON-0023) | still open; pointer imprecise | A19; Ch. 1 §1.14.4-12 |
| T4-03 (R-6) | open; criterion unreachable as worded | A1 |
| T4-04 | partly stale | A18 |
| 05.00 invariant rows (§5.5 C2/C5/C6, §5.6 C3/C6, §5.4 C9, §5.7 C1/C9) | each points to an existing claim | — |
| 05.01 "Ch. 7 retired (DB-6)" | consistent with THEORY-MAP L426 | — |
| 05.02 "Ch. 8, planned" | stale | A15 |
| 05.04 "§9.10 Q7" | exists | 09.10 L96 |
| 05.05 "§9.13.2 recorder field (v6)" | exists | 09.13 L50 |
| 05.07 "§6.5 C2", "§6.6 C4", "§8.10 C10" | exist and match | — |
| T5-01…T5-07 | open and consistent with the sections | — |
| T5-04 (status "Open", triage "CLOSE → register") | status and triage wording disagree | low |
| T5-08, T5-09 | row text consistent with §5.4 C9 and C8; T5-08's §8.62 C4 cite is reviewer C's | — |
| SEQ order, Ch. 4 | acceptable (forward refs are marked) | — |
| SEQ order, Ch. 5 | acceptable except BC-7 in §5.7 (A14) and THM-G4 in §5.4 C7 (A22) | — |

## 4. R1 and R2
- R1 (`register_home_check.py --root <copy>`): 1 flag, CON-0133 in chapter_06 (H4, out of my scope, one line: the tag carries no status).
- R2 (`undefined_terms.py --root <copy> --scope 04,05`): tier A had 4 candidates; tier B had 11; the D list is background.
| Word | Judgement |
|---|---|
| unattested | acceptable: un- + "attested" (defined §1.14), 9 uses, no gloss needed |
| lexicographically | ordinary mathematical term; one word unglossed at 05.04 L115 (low) |
| obedient, staffing | ordinary English |
| V-1 | defined in the same line (05.06 L68) |
| G-2 | a source label ("G-2/R5b", S0129 L147); glossed by context in 05.05 |
| RD-1 … RD-7, RD-4 | undefined at first use (A13) |
| R-6 | defined in §4.1 C3 |
| SD-1 | named at 05.02 L20, defined at L40 (acceptable) |
| R-1 … R-8, P-2 | source labels; R-1…R-8 glossed as "the repair" (05.06 L68); P-2 unglossed (low) |
| T-1b | defined in-line (05.07 L70) |
| F-08 | a review finding ID; acceptable |
| must-never (tier D) | used as the opposite of "must-do"; defined only in REVIEWER-INSTRUCTIONS, never in the book (05.04 C9 introduces it by use). Judgement: one-line gloss recommended (low) |
Tier-D background words judged ordinary English or ordinary math (premature, falsifiable, progress, incomparable, immutable, etc.).

## 5. Coverage of sections
| Section | Status |
|---|---|
| 04.00, 04.01, 04.02, 04.03, 04.99, TODO | read in full |
| 05.00, 05.01, 05.02, 05.03, 05.04, 05.05, 05.06, 05.07, 05.99, TODO | read in full |
| Source lines cited in 5.4 from S0208, S0721, S1296, S0913, S2834 | not opened (all on the allowed list; time) |
| S0896, S2377 | cited lines spot-checked only (about a dozen each) |
| S0164, S0067, S0071, S0073, S0091 | not opened; claims depending on them (inside 5.5/5.6/5.7 cross-refs) taken from the book's own text |
| Window inventories | not used (the commission says none exists for Ch. 4/5) |

One-line out-of-scope observations: (1) CON-0133 H4 flag (above); (2) §8.10 L81 says "the third off-record verification" while §5.7 C2 L38 says "the second verification … after verification #2"; consistent only if the §8.10 one is later (not checked); (3) THEORY-MAP L273 repeats "six-word act".

## Process independence
Process independence only. I did not see any other reviewer's report. I work from the frozen copy and the allowed corpus. I am the same model family as the Professor.

## Files opened and commands run

**Files (read):**
- Commission files (folder CONSOLIDATION-S0303-S0327): REVIEWER-NOTE.md, SCOPE.md, COMMISSION-A.md; 03-methodology/REVIEWER-INSTRUCTIONS.md.
- Copy, Ch. 4: 04.00-index.md, 04.01-from-observation-to-pattern.md, 04.02-improvement-cycle.md, 04.03-recommendation-lifecycle-observed.md, 04.99-change-log.md, TODO.md.
- Copy, Ch. 5: 05.00-index.md, 05.01-decision-docket.md, 05.02-gap-kinds.md, 05.03-deferral.md, 05.04-gap-operator.md, 05.05-who-executed-who-authorized.md, 05.06-what-an-act-covers.md, 05.07-closure-and-the-record.md, 05.99-change-log.md, TODO.md.
- Copy, other parts (cited by my sections), lines only:
  - chapter_01/01.17 L14–20; 01.06 L36–44; 01.99 greps; 01.14 grep (RC-25, RC-38, CON-0023).
  - chapter_08/08.06 L24–28, L55–64; 08.10 L88–99 (grep); 08.04 (greps and C-heads); 08.05, 08.07, 08.08 (grep for C-heads).
  - chapter_06/06.05 L54–67, L84, L102–112; 06.03 grep.
  - chapter_09/09.10 (grep Q7); 09.13 (grep recorder, v6); 09.03 (grep CON-0111).
  - 02.10 grep (RC-60).
- Copy, other: TARGET-TOC.md (greps for M13), TODO-TRIAGE-S0100.md (L60–75, L115–122, greps), CONCEPT-REGISTER.tsv (rows for the 38 scope concepts, plus greps), THEORY-MAP.md (L250 only), PRINCIPAL-TESTIMONY.md (a grep line only, L34); copy directory listings.
- Corpus (allowed list): CORPUS-ALLOWED.tsv; S0017 (L19–21, L36–40, L110), S0018 (L61–68), S0019 (L27), S0023, S0024, S0025 (L108, L139, L155), S0026 (L103), S0027, S0028 (L57, L182), S0029, S0030 (L5–24), S0040, S0044, S0051–S0053 (cited lines), S0055 (L75, L112), S0058 (L82–88), S0061 (L27), S0065 (L3–20), S0076, S0077, S0078 (L67, L161–169, L222), S0079, S0080, S0129, S0896 (L105, L255–262, L671–709, L768–815, L847–850, L981–1050, L1270–1276), S2377 (a dozen single lines: L1, L13, L336, L461, L525, L1346, L1788, L2020, L2319, L2395).

**Commands / searches:**
- `find`/`ls`/`wc -l` of the copy; `sed -n` and `cat -n` of the files above.
- `register_home_check.py --root <copy>` → scratch cons3-a-r1.txt.
- `undefined_terms.py --root <copy> --scope 04,05` → cons3-a-r2.txt.
- `corpus_quote_check.py` on the Ch. 4/Ch. 5 section files (it has no --root; it reads the corpus) → cons3-a-quote.txt.
- `inbound_citations.py --root <copy>` for: "§4.1 C1", "§4.1 C4", "§4.1 C6", "§5.7 C1", "§5.4 C7", "§5.3 C1", "CON-0163" (cons3-a-r3.txt); "§5.6 C6", "§4.3 C1", "CON-0113", "§4.1 C5", "§5.4 C3" (cons3-a-r3b.txt); "§4.1 C3", "T4-04", "§5.5 C1" (output read in terminal).
- `book_search.py` (the copy's) for: CON-0013, CON-0014, CON-0019, CON-0163, "PA review role Principal Architect", DB-2, BC-7, RD-6, must-never.
- `grep` on the copy for CON-IDs in Ch. 4/5, TODO rows, "six words", stale-wording patterns, M13, DB-2, BC-7 in Ch. 0–5; `grep` of S0023, S0027, S0029, S0044 for single lines and terms (PA, persona, MO-1, IF-1, thresholds).
- `python3 -I` scratch: token count of the act at S0065 L7 (4 and 7); comparable-pairs count on the power set for n = 1…8.
- Scratch directory used: /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons3-a (files cons3-a-r1.txt, cons3-a-r2.txt, cons3-a-quote.txt, cons3-a-r3.txt, cons3-a-r3b.txt).
- Tool binaries were run from the working repository's tmp/tools (read-only), always with --root <copy>; the copy itself contains only book_search.py and book_synonyms.tsv. I listed tmp/tools in the working root (names only).

**Files in the forbidden classes:** I opened none of the working chapter files, the working THEORY-MAP/CONCEPT-REGISTER/BOOK-OUTLINE, BASELINE-REVISIONS, or anything in 00-access/. I ran no git history, diff, blame or commit listing. I did not open REVIEWER-NOTE's other-reviewer reports.
