CONSOLIDATION S0201–S0225: INTEGRATION REPORT, PART 2

What I checked: the frozen copy `W/06-theory/manuscript/review/CONSOLIDATION-S0201-S0225/BOOK-UNDER-REVIEW/` and the allowed corpus files only. I edited nothing. Every tool run used `--root <copy>` or was given copy file paths, from the first call on. I made no `--help` call.

## 0. Summary (10 lines)
1. **A's 13 findings:** 6 confirmed, 7 modified, 0 rejected. A-01 (high) and A-02 (high) stand. A-01 is narrowed, because §9.16 R4 (i) and §9.4 C2 already state the right disjunction. So A-01 is an internal inconsistency in the text of the kernel type and in the F36 map row. It is not a defect in the decided design.
2. **C's 11 findings plus 5 appendix items:** all confirmed; F-03, the "whole-or-nothing" R2 item and appendix A1 are modified. F-01 stays high. A-07 merges into F-01 (c).
3. **Coverage misses:** A's rank-1 coverage miss (S0206 "class E") is **modified to mostly COVERED**. The duplicate grant id is in §8.19 L74 and §8.30 L87, the untimed authority in §8.31 C3 and §9.6 L96, and authenticity in §8.28. Only the S0206 citation and its "no owner" framing are missing.
4. **Line-number citations (fact):** chapters 8 and 10 cite §9.10 L29–39, L31, L33, L35, L41–64, L67, L93, L150, L179; §9.7 L58, L86; §9.6 L96, L219; §9.11 L11, L48. So A-02, A-04, A-05, A-06 and A-01 (in 09.06) **must be edited in place, keeping line positions**. This is the same rule as part 1's F1 and F4.
5. **New defects (N7, N8, N11):** index rows 9.7 and 9.9 state refuted (INVALID) forms (CON-0142, CON-0161), and row 9.4 states a conditional result without its condition.
6. **New homonym (N9):** "safe" has a third sense, "safe delegation" (09.06 L151). It extends B-F9 and A-08.
7. **Sections:** with A and C included, every section in SCOPE §2.1 has been read in full by some reviewer.
8. **Still uncovered:** the SEQ order of Ch. 10 (no reviewer); about 70 of the 135 R2 candidates in A's scope, which A judged as a class rather than one by one; B's 47 from part 1.
9. **Concepts:** all 40 in-scope concepts now have a verdict (A for §9.1–§9.12, B for §9.13–§9.18). A's raising of 4 concepts to VALIDATED is a single-reviewer verdict. I checked that its reasons hang together; I did not re-derive them.
10. **Access:** no finding of A or C rests on output from the working book. Every line A and C cite that I checked matches the copy. One figure is not reproduced: A's quote-check total "163 found" comes out as 125 found / 0 not found on the copy (N10).

**Process independence:** this is process independence, not personal independence. I saw no drafts of any report. The scratchpad directory is shared by reviewers A and B, the first integrator and me. A and B both report writing a file named `r2.txt` there. I did not open any of those files.

## 1. Findings (A and C), checked against the copy and sources
| ID(s) | Verdict | Sev | Ch. | Evidence (fact unless marked Int) | Correction |
|---|---|---|---|---|---|
| A-01 | **modified** | high | 9 | 09.01 L101 says "by result 5, every application that consumes acts must share one append-only history variable". Result 5 (L113) proves only sufficiency of (i)–(iii). 09.11 L78/L82 and register CON-0183 ("fails … unless …") and CON-0189 ("one record is shared") state necessity. The book's own §9.16 R4 (i) gives "a shared serialization point, **or ownership with transfer**", and §9.4 C2 serialises "per claim". A's counter-model (the act space split into parts, one history per part, plus a guard) is valid. Int: the decided design (R4c, with the kernel as coordination point per single-use grant) is one instance of such a split, so nothing that rests on necessity breaks. | Say "sufficient". Name the alternative: a split per constraint, grant or owner. Point to §9.16 R4 (i). Do **not** cite 09.13 L37's owner-record guard (superseded, part 1 F2a). Edit 09.06 L127 in place (L219 below it is cited 4×). |
| A-02 | confirmed | high | 9 | 09.10 L38 ("that recorded acts occurred, and that every act was recorded") contradicts §9.10.6's Q1 row (L214), §9.12.7 (L99–101) and §9.9.9 item 5 (L126, "dissolved"). | As A proposes. Edit L38 in place: 10.05 cites §9.10 L29–39. |
| A-03 | modified | normal | 9 | 09.06 L129 is right only for A1-as-fact. §9.11 law 2 (A1-as-form) is class T and checkable. The CON-0170 row says "unsatisfiable for A1 as stated". The CON-0136 row has **no** such clause. | Scope L129 and the CON-0170 row to A1-as-fact. Drop CON-0136 from A's list. |
| A-04 | modified | normal | 9 | 09.07 L85–86 ("the same policy values") against step 3 (L83: disagreement for ρ only if ρ is policy; F27). The overreach concerns **policy values only**. Definition, coverage and root are not shown to be dispensable. | Make result 4, step 4 and CON-0173 read: "the policy values shown to change answers (P, κ, υ; σ and ρ as F27 decides)". Keep definition, coverage and root as they are. Edit L86 in place: 10.04 and 10.05 cite §9.07 L86. |
| A-05 + part-1 N5 | confirmed (merged) | normal | 9 | §9.6.8 (L236–237) still asks K3 and "Run Analysis 1–3 for CE". 09.06 L217 says "turns on K4 alone", although (C+O) is decided at the end of that line. Index row 9.6 says "Prepared for the Principal". 09.10 L275 and 09.11 L129 list F22 and F36 as open; 09.11 L96 says F36 is decided. Law 15 (09.11 L51, "partial for Q1; absent for Q3") contradicts 09.10 L272. The 09.07 §9.7.1 text "no function to delegate" is unscoped. T9-05 (TODO L15) does not point to §9.11.3. CON-0138 says "not yet a total function"; CON-0190 lacks I15 (09.12 L33). | As A proposes, edited in place. Merge with N5 (rows 9.13–9.16), B-F5 (row 9.18), N7, N8 and N11 into one index correction. |
| A-06 | confirmed; R3 modified | normal | 9 | 09.10 L73, L119–120 and L217 do not carry T9-12 (c) or T9-16 (b) (TODO L22, L26). | Q4: "no grade without an **authorised** evidence entry", in L73 **and** the L217 table row; scope O2. Edit in place: L93, L150 and L179 are cited. |
| A-07 + C F-01(c) | confirmed (merged) | normal | 8/9 | 09.09 L27 (Reliability(a,d,t), T-a) and 09.10 C ("T-a … derivable") against 08.38 L57/L61 ("home Q4"; fork on source kind or reliability). Neither side cites the other. | One fork row in THEORY-MAP naming both homes. Add pointers at 08.38 L57/L61 and in §9.9.2. |
| A-08 | modified | normal | 9 | Confirmed: K1–K5 vs K1–K7 (only 09.16 L57 tells them apart); "Lemma D" vs the domain D (09.08 L50, L62; "§9.8 D" at 09.10 L194); C1; R1–R5. Related to part-1 N6 and B-F9. | Qualify by section. A rename of "Lemma D" would touch 15 lines (R3), 7 of them in B's scope, including 09.16 L69 (part-1 F1). Prefer the qualifier "§9.8 Lemma D". |
| A-09 | confirmed | normal | 9 | 09.11 L18 "violated by some finite prefix" admits non-safety properties. A's example holds. | As A proposes (the Alpern–Schneider form). |
| A-10 | confirmed | normal | 9 | 09.08 L50 uses D, Θ_f, C1 = constitute and §9.13.6 before §9.13. The same holds for the other lines A cites. | A forward glossary in 09.00, or back-pointers. |
| A-11 | modified | normal (b: low) | 9 | (a) S0091 L181 is the I-4 row; L182 is G-1 (fact). (b) 09.06 L11 already says S0234 "inherits" BC-7's placement, so lineage is noted at section level; only "adds" at L195 is loose. (c) S0113 heading L22 ("Three of the five") vs body L24/L34 ("only one"; "Four of five"): a source defect, and the theory follows the body. | (a) Fix the line number. (b) Change "adds" to "restates". (c) List as a source defect only. |
| A-12 | modified | normal | 9 | The CON-0171 row lacks the fixed fold, the declared writer role, the durable tracked store and comparable scope (09.06 L172–177). Int: F22 = (C+O)'s owner clause belongs to the kernel-held contract (§9.16 R4a), not to the delegated record's contract. | Align the four items. Add a pointer to §9.16 R4a, not an owner clause in CON-0171. |
| A-13 + part-1 N4 | confirmed (merged) | normal | 9 | Blank line at 09.00 L28 (seen with `cat -A`). TODO rows T9-12 to T9-17 have 5 cells under a 9-column header (L22–27). | Fix both tables. |
| A cov. 1 (S0206 class E) | **modified** | low | 8 | S0206 L489–491: grant identity "nothing — E — 1 duplicate measured"; timing; authenticity. The book has the duplicate at §8.19 L74 (S0136) and §8.30 L87 ("as at every measurement"); timing at §8.31 C3 and §9.6 L96; authenticity at §8.28. | Cite S0206 L486–497 as a further measurement, and its "no owner" table, in §8.32. |
| A cov. 2 + C (S0205 list) | confirmed (merged) | low | 8/9 | The kernel lists of S0205, S0216, S0221 and S0222 are compared nowhere. §8.34 L125 covers S0212 and S0213 only. | Add them to one comparison table (§8.34 or §9.9.8). |
| C F-01 | confirmed | high | map | No hits for warrant / adoption cites / produce vs test / non-rule items in THEORY-MAP or in any TODO.md of chapters 8 and 10 (the TODOs searched now). The map's open-fork list stops at C19–C21. | As C proposes; (b) marked "Principal decision"; (c) merged with A-07. |
| C F-02 | confirmed | normal | 8 | 08.37 L49 "span three axes"; the mapping at L56–61 gives none on authority. Index L51 says "five values on three axes". | As C proposes. |
| C F-03 | modified | normal | 8 | 08.00 L54 covers §8.18–§8.30 only. §8.31–§8.38 cite §9.10 (43×), §9.18 (44×), §10.2 (21×), §10.5 (22×), PL- (24×), §10.4 (6×), §10.1 (4×), §9.12 (2×), §9.14 (2×). | Add §10.4, §9.12 and §9.14 to C's list. |
| C F-04 | confirmed (Int) | normal | map | MAP L389 vs 08.33 L3 ("S0208 had been consulted in a branch before"). The allowed list has 218 IDs in S0001–S0225 (recounted). The cause is not shown, and I did not run `close_source.py`. | As C proposes, after the tool confirms it. |
| C F-05 | confirmed | normal | outline | Quote check on the copy: 01.02 7/0, 01.06 3/0, 01.08 9/0, 01.11 6/0, against outline L204 ("Ch. 1 has six"). | As C proposes. |
| C F-06 | confirmed | normal | outline | The rotation table (L196–202) ends at S0225; Ch. 10 is absent. | As C proposes. |
| C F-07 | confirmed | normal | 10/8/9 | 10.02 L218 and 08.38 L52 say "only"; 09.18 L110 lists actor, cited bases, about, polarity, scope and act time. 09.18 L81 and L298 lack the extension. | As C proposes. |
| C F-08 | confirmed (tool limit) | normal | — | `corpus_quote_check.py` gives 0/0 on all of 08.31–08.38 (reproduced). | As C proposes. Not a defect of the book. |
| C F-09 | confirmed | normal | 8 | S0201 L454 already classes C-8 as DOMAIN-CONTEXT-CANDIDATE; S0203 L752 agrees. 08.31 L141 lists it as a flattening. | Drop the C-8 item. |
| C F-10 | confirmed | normal | 8 | S0217 L384: "its authority is dependent on the validity of that chain"; C42 inventory #5. 08.37 L46's relay says "S0220 … with only the first sentence" without naming the origin. | As C proposes. The relay should start at S0217 L378–384. |
| C F-11 | confirmed | normal | 8 | S0202 L1830; S0201 L27 (anti-fallback). C38 inventory #7, #8 and the method line at L96. `book_search` on the copy: "eliminate candidates" and "fallback" give 0 hits in Ch. 8. | As C proposes. |
| C R2 "R-2" | confirmed | low | 8 | 08.32 L10: the label is used in quotes without a gloss. | Gloss it. |
| C R2 "whole-or-nothing" | modified | normal | 8 | It is **named** as a value of ω (01.14 L175 table, C4; 09.13 L84) but never defined. 08.35 L81 uses it for "a revocation's scope is not read", a different referent. | Define it once at §1.14 C4 / §9.13 L84; fix 08.35 L81. |
| C app. A1 | modified | low | 8 | The quote's wording is S0203 L670, while 08.31 L79 cites both "S0201 L322; S0203 L664–670". | Attribute the wording to S0203. |
| C app. A2, A3, A4, A5 | confirmed | low | 8 | 08.34 L110/L112; the 08.38 file name; S0212 "Publication" is at L514; S0215 L40 reads "no belief is kept". | As C proposes. |

## 2. R3, each inbound line (tool output where it resolves a target, otherwise a grep of the copy)
| Correction | Inbound line | Disposition |
|---|---|---|
| A-01 | 09.01 L101; 09.11 L78, L82; CON-0183, CON-0189 rows; BOOK-OUTLINE L173; MAP L155 ("needs one shared record") | need the same change |
| A-01 | 09.06 L127 | needs a lighter change ("a shared point per constraint", as step 2 at L124 says); edit in place |
| A-01 | 09.11 L43 (row 9b, "needs R1's premises") | stays true once R1 says "sufficient" |
| A-01 | the other §9.1 C6 inbound lines (09.00:21; 09.02:18; 09.03:3, 23; 09.06:9, 74; 09.07:11; 09.08:11; 09.09:10; 09.10:21, 108, 111, 141, 207; 09.11:3, 7, 27; TODO:15; MAP:161, 180; OUTLINE:167) | not affected (they cite the type, not condition (iii)) |
| A-02 | 24 lines cite "§9.10 Q1" (A said 22). None uses the assumption clause. 10.05:312 (PL-6) and 10.05:174 rest on L4 / closed-world wording | not affected; 10.05 "§9.10 L29–39" stays true if L38 is edited in place |
| A-03 | 09.06 L129; CON-0170 row | same change |
| A-03 | CON-0136 row; 09.99 L23 (log) | not affected / keep |
| A-04 | 09.07 L86, L94, L133; CON-0173 row; MAP L270; 10.05 L246 ("a common, versioned value of each"); 09.06 L41 ("one versioned evaluation rule and value set") | same change (scope the policy values); L86 in place |
| A-04 | 10.02 L88; 10.04 L65; 10.05 L318, L326; 09.18 L270; 09.07 L165 and 09.06 L255 (settled/withdrawn lists); CON-0142 row | stay true / not affected |
| A-05 | index row 9.6; §9.6.8; 09.06 L217; 09.10 L275; 09.11 L51, L129; TODO T9-05; CON-0138, CON-0190 | same change (annotations, in place) |
| A-05 | 10.03 L115 ("law 15 for Q4 … §9.10 L179") | stays true (Q4 is still absent), if L179 does not move |
| A-05 | MAP L155 ("T9-05") | stays true |
| A-06 | 09.10 L73, L217, L119–120 | same change |
| A-06 | 09.18 L90 (quotes the requirement as it stood) | keep (historical quotation) |
| A-06 | 08.38 L16; 09.18 L60, L318; 10.05 L253; 10.04 L60; 08.31 L117; 08.32 L22, L25, L65, L95 (O2 "changes no authority") | not affected |
| A-07 + F-01(c) | 08.38 L57, L61; MAP L213; 09.09 L27 | add a pointer |
| A-08 (if renamed) | CON-0177 row; 09.08 L50; 09.09 L18, L53; 09.10 L186, L194, L249; 09.11 L80; 09.13 L14, L68, L274, L276, L277; 09.16 L69, L423 | need the same change. 09.16 L69 is also edited by part-1 F1: one in-place edit. Qualifying instead touches only the ambiguous uses. |
| A-09 | BOOK-OUTLINE L173; 09.11 L27; 09.01 L97 | not affected |
| A-11 | none | — |
| A-12 | CON-0171 row | same change |
| A-12 | 09.99 L24 (log); CON-0137 row | keep / not affected |
| C F-01 | MAP L212, L213 | gain pointers; the rest as C lists (unaffected) |
| C F-02 | 08.00 L51 | same change |
| C F-02 | 08.99 L57 | keep |
| C F-02 | 08.38 L22, 115, 126, 142, 148; MAP L212 | not affected |
| C F-07 | 10.02 L218; 08.38 L52 | same change |
| C F-07 | 09.18 L81, L298 | different change (add the source-kind pointer) |
| C F-07 | 08.35 L71, L117; 08.36 L24, L96, L146; 08.37 L23, L40, L47, L92; 08.38 L16, L57, L61, L68, L86, L154; 10.02 L114; 10.03 L115 | stay true / not affected |
| C F-07 | 08.99 L56–57; 10.99 L16 | keep (logs) |
| C F-07 | 05.04 L133, L164 ("G7" = S2377's failure class) | not affected; a label homonym (one line, out of scope) |
| C F-03, F-04, F-05, F-06, F-09, F-10, F-11 | as C lists (none, or unaffected) | checked by grep, agree |

## 3. References to missing claims (REVIEWER-NOTE item 6)
| Finding | Missing content or stale pointer | Evidence |
|---|---|---|
| A-05 T9-05 | stale pointer: the content exists in §9.11.3 | 09.11 L29–69 |
| A-06 | stale: the decisions exist | TODO L22, L26; §9.18 |
| A-12 | stale register: the content is in the text | 09.06 L171–177 |
| A-11 (a); C app. A4 | stale line pointers: content at L181 and L514 | corpus |
| C F-04 | stale list (Int) | 08.33 L3 |
| C F-07 (09.18 L81, L298) | stale pointer: the extension exists at 10.02 L218 | — |
| C F-10 (08.37 L46 relay) | **content missing**: the origin, S0217 L384, is not cited | C42 inventory #5 |
| N7, N8 | stale index rows: the restated forms exist | CON-0173, CON-0182 |

## 4. Conflicts and corrections that touch the same text
| Pair | Resolution | Reason |
|---|---|---|
| A-13 × part-1 N4 | merge | same blank line |
| A-05 × part-1 N5 × B-F5 × N7/N8/N11 | merge into one 09.00 correction (rows 9.4, 9.6, 9.7, 9.9, 9.13–9.16, 9.18) | different rows of one table; no contradiction |
| A-07 × C F-01(c) × C F-07 | compatible. F-07 edits 08.38 L52; A-07 adds pointers at L57 and L61; F-01(c) adds the map row | different lines; one fork |
| A-01 × part-1 F2a | A-01's wording must not reinstate 09.13 L37's "guard on the owner's record" | F2a marks it superseded by R4c; the split is stated abstractly |
| A-08 rename × part-1 F1 (09.16 L69) | one in-place edit; prefer qualifying to renaming | 09.16 L79–80 are cited by line number |
| A-02 / A-04 / A-05 / A-06 × line-number citations | edit in place | the §9.10, §9.7 and §9.6 line citations listed in summary item 4 |
| A-04 × A-05 (both in §9.7) | compatible | different lines (L85–86 against §9.7.1, step 5) |
| A-10 × C F-03 × B-F10 | compatible; one remedy pattern per chapter | SEQ problems in different chapters |
| A-06 × C F-07 | compatible | Q4's wording vs G7's field list |

## 5. Defects in the rotated chapter (Ch. 9)
- **From A, confirmed or modified:** A-01, A-02, A-03, A-04, A-05, A-06, A-07, A-08, A-09, A-10, A-11 (a, b), A-12, A-13. A-11 (c) is a source defect.
- **From B (part 1):** as integrated there.
- **From C, touching Ch. 9:** F-07 (09.18 L81, L298).
- **New:**
  - **N7** (normal): 09.00 row 9.7 says "definition and policy parameters kernel, execution delegable". That is the term of CON-0142, which is INVALID; §9.7 result 4 says the kernel is "not shown".
  - **N8** (normal): row 9.9 says "the residual trust assumption does not reduce, a candidate axiom". That is CON-0161, which is INVALID. The restated form is CON-0182, and §9.16 R7 makes occurrence a world assumption.
  - **N9** (normal): "safe" has a third sense, "Delegation is safe if …" (09.06 L151). It adds to B-F9's "safe direction".
  - **N11** (normal): row 9.4 says "uniqueness needs a serialization point" without §9.4 C2's condition ("if the outcome is that of *the* established assessment"; "not forced", L36–38). This is A-01's pattern.
  - **N10** (process): A reports a quote-check total of "163 found". On the copy, 09.00–09.12, 09.99 and TODO give 125 found, 0 not found. The figure is not reproduced and its cause is not known. No finding depends on it.

## 6. Coverage
- **Sections in scope that no reviewer read in full:** none.
- **Not assessed by anyone:** the SEQ order of Ch. 10 (SCOPE §2.1). Ch. 8's SEQ was assessed only for §8.31–§8.38 (F-03).
- **R2 not judged one by one:** about 70 Tier-D candidates of A's 135 in-scope candidates. A judged them as a class: "ordinary or defined in context". I judged a sample: "safe" is a defect (N9); unattested, legitimate, compliant and relocated are acceptable. B's 47 from part 1 are also still open. C judged all 111 of its candidates.
- **Concepts without a verdict:** none. Single-reviewer verdicts: A raised CON-0115, 0167, 0169 and 0177 to VALIDATED. Each validation must record its scope in the register, because CON-0115 is validated only as a reported design rule.
- **Access disclosures:**
  - **A:** one call without `--root` (R1, R2, R3), with its output discarded. All of A's line citations that I checked (A-01 to A-13) match the copy. One figure is not reproduced (N10).
  - **C:** `--help` on three tools without `--root`. Its R1 result (1 flag, CON-0133) and its R2 count (111) reproduce on the copy. Its Ch. 1 quote figures reproduce.
  - **Result:** no finding of A or C is shown to rest on working-book output.
  - **Shared scratchpad:** A and B both wrote `r2.txt` there, so the scratch records can no longer be told apart.

## 7. Files opened, commands and searches
**Disclosures:**
- I listed the consolidation folder (`ls -la`) and saw the names COMMISSION-A/B/C.md, INTEGRATION-COMMISSION.md and the SHA256-*.txt files. I did not open any of them.
- A second listing (`ls ../../../` from inside the copy) showed the same names.

**Opened:**
- Consolidation folder: REVIEWER-NOTE.md, SCOPE.md, INTEGRATION-COMMISSION-2.md, REPORT-A.md, REPORT-B.md, REPORT-C.md, INTEGRATION-REPORT.md.
- `W/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- Copy, by `sed`/`grep`/python line reads:
  - 09.01 L95–118; 09.04; 09.06 L120–130, L168–180, L217, L219, §9.6.8; 09.07 L80–96, L130–135, L160–168, §9.7.1; 09.08 L50, L62, L94, L30; 09.09 L27, L120–128; 09.10 L30–42, L62–100, L112–121, L194, L205–220, L270–276; 09.11 L13–27, L34, L43–44, L51, L70–84, L129; 09.12 L33, L95–103; 09.16 L57, L191–267 (grep hits); 09.18 L81, L110, L298; 09.00 L10–29; TODO L1–27.
  - 08.31 L141 and grep hits; 08.32 L10 and owner lines; 08.33 L3; 08.34 L17, L36–41, L110–125; 08.35 L64–69, L81; 08.37 L21, L44–61, L72; 08.38 L16–62 (grep hits); 08.00 L51, L54.
  - 10.02 L88, L218; 10.04 L65; 10.05 PL lines, L174, L241, L246, L326; 01.14 L174–175, L206; BOOK-OUTLINE L173, L186–206; THEORY-MAP L155, L212–213, L270, L380–402; CONCEPT-REGISTER.tsv (rows by awk).
- Inventories: the C38, C39 and C42 STEP1-INVENTORY.md files (grep); line counts of all eight.
- Corpus, all on CORPUS-ALLOWED.tsv and none excluded by SCOPE §2.4:
  - S0091 L181–182, L192
  - S0234 L272
  - S0113 L22–24, L34
  - S0201 L27, L322, L446–456
  - S0202 L1830
  - S0203 L670, L726–729, L748–754
  - S0206 L486–497
  - S0212 L514–515
  - S0215 L40
  - S0217 L376–385
- Tools: argument lines of `inbound_citations.py`; `corpus_quote_check.py` L1–30.

**Commands** (scratch output in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/int2/`):
- `register_home_check.py --root <copy>`.
- `undefined_terms.py --root <copy> --scope 09.00-09.12,09.99` and `--scope 08.31-08.38`; then a set difference against the words named in REPORT-A and REPORT-C.
- `inbound_citations.py --root <copy>` for "§9.1 C6", CON-0183, CON-0189, CON-0170, CON-0173, CON-0171, CON-0188 and "§9.10 Q1".
- `corpus_quote_check.py` on the copy's 08.31–08.38, 01.02, 01.06, 01.08, 01.11, 09.00–09.12, 09.99 and TODO.
- The copy's `book_search.py` for "no owner", "eliminate candidates" and "fallback".

**Greps and python scans of the copy:**
- "C+O" / "coordination point" / "F22"; "§9.10 Q1" (with context; filtered for assum/occur/complet/world); "§9.10 Q4|O2|O1"; "G7"
- "share one|one shared record|…"; "Lemma D|§9.8 D"; "whole-or-nothing"; "Inference-001"; "class E|grant-id"
- "three axes|five values"; later-chapter reference counts in 08.31–08.38; line-number citations into §9.1–§9.12; "T9-05"; "DB-1"
- the MAP and the TODOs for the F-01 terms

Two `grep -o` runs failed on regex complexity and were redone in python. I also used awk on CORPUS-ALLOWED.tsv (paths, and the S0001–S0225 count) and `cat -A` on the index.

**Not opened:** `00-access/`; the working chapters, THEORY-MAP, register, BOOK-OUTLINE and BASELINE-REVISIONS; other review-folder files; manuscript git history; the sources SCOPE §2.4 excludes; the scratch files of other agents. Nothing was sent to any outside service, and I made no edits.
