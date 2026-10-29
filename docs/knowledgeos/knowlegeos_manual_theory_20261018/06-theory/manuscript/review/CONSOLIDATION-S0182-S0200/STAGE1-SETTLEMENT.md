# Consolidation S0182–S0200: stage 1 settlement (a document only)

*2026-10-28. The Professor. Rulebook v1.4 §12; the Principal's approval of the plan with two corrections (2026-10-28). **The copy's files are not edited.** Each remedy is stated against the copy's text (`BOOK-UNDER-REVIEW/`, frozen at `bd421337b`, hashes in `frozen-table.txt`) and chosen from the copy and the sources only; the working book was not opened or compared. Stage 2 waits for the Rule Reviewer's score; it applies each remedy to the working book where the working book has the same defect.*

**Reports, saved verbatim with sha256:**
- A (Opus): `REPORT-A.md`, `9d95ad44…65b9ee`;
- B (Sonnet): `REPORT-B.md`, `62c239c7…47c2ca`;
- integration (Opus): `INTEGRATION-REPORT.md`, `c94d3204…796261`.

**Access:** `ACCESS-NOTE.md`. Reviewer A read `README-PROFESSOR.md`, which names the test, and grepped `00-access/ACCESS-RECORD.md`. The integration reviewer ran `grep -c . 00-access/*`, which printed one count and no content. Reviewer B opened neither file. No reviewer opened a file of REVIEWER-NOTE item 4. These facts come from the transcripts, audited by script.

"V (Professor)" marks evidence the Professor re-checked in the copy or a source. "R" marks evidence accepted on the integration review's reading.

## 0. Entry 58 record

| Measure | Value |
|---|---|
| Reviewer A (Opus), harness | 1,320.6 s |
| Reviewer B (Sonnet), harness | 990.6 s |
| Integration (Opus), harness | 773.4 s |
| **Review total** | **3,084.6 s (51 min 25 s)**; the last consolidation took about 6 h |
| Settlement (the Professor) | from the integration report (2026-10-28 17:26Z) to this commit (see the session log); the Professor's harness time is not available |
| Elapsed, freeze (`d3988310f`, 16:49:22Z) to this commit | see the session log |
| **Defects in the rotated chapters (confirmed)** | **Ch. 6: 4** (A only 1: I-15; both 3: I-01, I-14, I-18), plus 2 SEQ items not re-checked by the integration review (A: §6.4 C3's forward pointer; B: Ch. 6's forward references). **Ch. 8: 23** (A only 11, B only 6, both 5, new 1: N-2) |
| Elsewhere in scope | 6 (I-03 map, I-05 Ch. 9, the Ch. 0 TODO order, I-06, I-27, N-1 register) |
| Overlap estimate | indicative only, since the reviewers are not independent: Chapman ≈ 4 (Ch. 6), ≈ 33 (Ch. 8) |
| Findings | A: 20; B: 17; integration: 28 integrated defects (19 confirmed, 9 modified), 2 new, B-17 rejected as a defect; no critical |
| **Coverage gap** | Neither reviewer read Ch. 8 §8.07–§8.26 in full. **76 concepts** homed there have no text-based second verdict (integration §5). Not filled in stage 1 |

## 1. Findings and remedies (stated against the copy)

| I-nn (reviewers) | Sev. | Check | Remedy, against the copy's text |
|---|---|---|---|
| I-01 (A-01, B-01) | high | **V (Professor):** copy `06.07` L28 reads "So at the time the set was a governed artifact; S0089 classes such a diagram as evidence of registration (S0089 L81)". S0089 L81 reads "never committed, therefore not a governed artifact ⇒ … *evidence of NON-REGISTRATION*, not *evidence of NON-EXISTENCE*" | §6.7 C1, "Later evidence": replace that sentence with "So at the time the set was not a governed artifact: S0089 classes an untracked diagram as evidence of non-registration, not of non-existence (S0089 L81)." R3: map L242 and §8.9 L77 stay true |
| I-02 (A-02) | high | **V (Professor):** copy `08.00` L28–29 read "CON-0208 invalid as stated" and "CON-0213 validated" | Index row 8.14: "CON-0208 validated (restated); CON-0213 provisional". Row 8.15: "D2's grounds (CON-0213 provisional)". Register CON-0213 `same_subject_as`: drop "(invalid as stated)" from its CON-0208 reference. R3: the 8 other lines stay true |
| I-03 (A-03) | normal | R | Map L373: `[CON-0265 ✓]`. Map L96: `[CON-0079 ✓]` |
| I-04 (A-04) | high | **V (Professor):** copy `08.30` L7 reads "Everything said about it is relayed by S0198 and S0199". S0234 L13–21 is the reconstruction's erratum (E-1 to E-3, with "not needed" withdrawn). The copy cites S0234 in §6.5 (2 lines) and §9.6 (12 lines) | §8.30 lineage: "The reconstruction itself (S0234) is not in this window; the book reads it directly at §6.5 and §9.6, with its erratum applied (S0234 L13–21)". Homes table: add §6.5 C2 and §9.6. C2: name the routes; the measurement is one (S0136 via §8.19; S0234 via §9.6; S0300/S0199 via §8.30), and none is a reproduction. C4: cite S0234 L15 as the applied erratum; that it is a disposition record stays Int |
| I-05 (A-05, B-03, B-04), N-1 | normal | R (ce_model.py L129, L261–268 as cited); **V (Professor):** copy `09.17` L166 ("The owner is read from the record: the root carrier's owner, …") and L173 ("With this last clause a single foreign move in a merged record is detected, not only a fork.") | (a) §9.17 L166 and the (c′) sentence at L204: "The root carrier's owner is set with the root designation, outside D (§9.15 L11); every later owner is read from the record." (b) L173 and register CON-0264: add "if the mover declares its own application". (c) §9.13.2 (L48), guard clause: add the owner refusal with the same condition, and point to §9.17.2 for carrier, old store and carrier in force |
| I-06 (B-05) | normal | R | T9-10 row: name the declared-application premise. The status stays as the Principal closed it (`a51be45b8`) |
| I-07 (A-06) | normal | R | §8.27 C1, fourth bullet: "The two bars concern different subjects (the AMD6 review, S0183 L291–294; the DV-correction review, S0185 L38); that a relay errs broad is not shown." Keep "too narrow" |
| I-08 (A-07, B-09) | normal | R | §8.27.1 item 2: replace "and must be recorded if it did" with "and an act that happened but is not recorded counts as missing" |
| I-09 (A-08, B-10) | normal | R | §8.28 C1, first limit: confine the presupposition to criterion 1 and S0191 L465–467. Cite S0189 L1983 as "exact runtime boundary unknown". Rest the one-system reading on S0186 L14. Apply the same change to T0-03b. Index row 8.28: "one of them" (criterion 1), not "two of them" |
| I-10 (A-09) | normal | R | §8.28.3 "Rule": cite S0188 L271 for the definition, and record that S0188 itself flags the homonym (L288) |
| I-11 (A-10) | normal | R | §8.29 C4: "model v20", not "v19" |
| I-12 (A-11, B-06) | normal | R | §8.30 C5, last bullet: "Not refuted for the magnitude pairings S0200 itself proposes (C+iT, L166–172; E+iP, L345; the five-component vector, L315–334) where interval scales are given." Record S0200's concession (L313, L336) and the three senses of its first axis (L77–82, L204–209, L330) |
| I-13 (A-12), N-2 | normal | **V (Professor):** copy `08.08` L52 counts "S0073's '6 of 37' against its own '37 of 37'" among "Ten cases" | §8.8 C2, "Count only": restate the S0073 pair as two classes (all STARTs recorded by a human; STARTs whose text says Governance recorded them), not a conflicting count, and make the total "nine cases" |
| I-14 (A-13, B-15) | normal | **V (Professor):** copy `06.06` L4 reads "undated" | §6.6: delete "undated" at L4. Ch. 6 index: order the rows 6.1 → 6.7 |
| I-15 (A-14) | normal | R | Ch. 6 TODO T6-02: point to §6.1 C6, and relate it to T6-03 |
| I-16 (A-15) | normal | R | §8.5 C9: "in four of the window's sources" |
| I-17 (A-16) | normal | R | `08.99`: add a title and a header row; add rows for the C34–C36 settlements |
| I-18 (A-17, B-14) | normal | R | Register `basis` column: remove the stale "not yet reviewed" where `reviewed` records a review (18 rows in scope, 41 register-wide). The CON-0176 sub-point is rejected. The unregistered valid cores of CON-0119 and CON-0122 are recorded for the Principal, not treated as a defect |
| I-19 (A-18) | normal | R | §8.27 and §8.28 reference tables: add the lines cited in the bodies (S0183 L294; S0185 L23, L38; S0186 L6, L14; S0187 L81–103, L172–221, L685; S0188 L73, L271, L630, L809; S0189 L879, L1983, L1988; S0190 L46, L113, L153, L171; S0191 L162–298, L465–467) |
| I-20 (A-19) | normal | R | §8.27 C7: add that S0182 L57–60 concerns the 27 renamed corpus entries |
| I-21 (A-20) | normal | R | Define "adviser" and "Rule Reviewer" once (Ch. 0 or the map), and link to the definition at first use in Ch. 8 |
| I-22 (B-02) | normal | R | §8.28.1 items 1–2 and §8.28.3: add "in the sources read" or "in this window". Attribute L31's first sentence to S0190 L25. §9.17 L166 takes the same scope ("the estate reports none") |
| I-23 (B-07) | normal | R | §8.30 C4, V line: quote both texts in full ("P4 gate OPEN conditional on P3-F1 bounded evidence completion"; "P4 gate remains CLOSED until this lands"); they agree, so delete the contrast |
| I-24 (B-08) | normal | R | §8.27 lineage: "…whose fourth column names corpus files not on the allowed list (S0182 L39–44)" |
| I-25 (B-11) | normal | R | §8.28 C2: replace "a direct estate instance" with "an instance of the same kind of premise (a declared identity); that the moving application is declared by the moving process is a step still to state" |
| I-26 (B-12) | normal | R | Index row 8.29: delete "a view is never authority (C7)" |
| I-27 (B-13) | normal | R | Map L374 and BOOK-OUTLINE L183: "§8.1–8.30" |
| I-28 (B-16) | normal | R | §8.29.1 item 3: "a file whose writing needs approval", not "a protected file" |
| SEQ | normal | R | Ch. 8 index note: say that §8.22–§8.30 also use Ch. 9 (D, K3, K6, §9.17). Ch. 0 TODO: order T0-03, T0-03a, T0-03b |
| B-17 | — | R | Rejected as a defect: the book already records it (08.00 L48, DB-4; T0-03). Kept as a recommendation to the Principal |

**Out of scope (one line each, not settled):** "Rulebook v1.3" at §8.1 and BOOK-OUTLINE L186; the dual home of CON-0076; the R1 H4 flag on CON-0133.

## 2. Concept verdicts (to enter the register at stage 2)

- **Ch. 6 (22), both reviewers on the text:**
  - VALIDATED, confirmed (second): 0072, 0075, 0087, 0089, 0102, 0104 (scope: AIP-14 as quoted), 0108;
  - **raised to VALIDATED within scope:** 0147, and 0176 (as mathematics; the kernel-law reading stays Hyp);
  - PROVISIONAL: 0103, 0132, 0140, 0172;
  - INVALID cores confirmed as restated: 0073, 0074, 0085, 0086, 0088, 0133, 0134, 0139, 0146.
- **Ch. 8, with a text-based verdict from A (and B):**
  - VALIDATED, confirmed: 0120, 0121 (scoped: a method rule and a possibility, its benefit Hyp; integration conflict resolution), 0126, 0208, 0209;
  - **raised to VALIDATED within scope:** 0127 (the deduction under R ⊆ F; "never PASS" is policy);
  - PROVISIONAL: 0058, 0059, 0060, 0125, 0128, 0164, 0166, 0213, 0214;
  - INVALID cores confirmed: 0106, 0112, 0119, 0122, 0210.
- **Ch. 8, the 76 concepts homed in §8.07–§8.26:** no second verdict. B's verdicts on the register rows are recorded in REPORT-B but are not entered, since a register row is not the text. **For the Principal** (below).

## 3. Conflicts, as the integration review resolved them

- CON-0121: VALIDATED, scoped. CON-0128 and CON-0059: PROVISIONAL. CON-0127, CON-0147 and CON-0176: VALIDATED.
- B-06 and A-11 are merged (I-12).
- B-11 stands as a strength correction to §8.28 C2: a settled status does not protect a wording from correction.
- On CON-0245, A-04 and B's "relayed twice" both hold: within the window the measurement is relayed twice; across the book it reaches by three routes, and none is a reproduction.
- The §9.17 / §9.13.2 / TODO / CON-0264 edits are applied as one (I-05).

## 4. What stage 1 establishes, and what it does not

- **Established (V):**
  - the inversion at §6.7 C1;
  - the stale status words in the Ch. 8 index;
  - §8.30's "relayed only" lineage against the book's direct reading of S0234;
  - §8.8's count includes a pair that is not a conflict;
  - the stale "undated" at §6.6.
- **Not established:**
  - the content of Ch. 8 §8.07–§8.26 beyond spot-reads (the coverage gap);
  - the v20 run identity (reported by A and B, not re-run by the integration review);
  - whether the working book has the same defects. That is stage 2, after the score.

## 5. Addendum (2026-10-29): reviewer C, Ch. 8 §8.07–§8.26

*The Principal's instruction (2026-10-29): an extra reviewer for the coverage gap. Commission `COMMISSION-C.md` (`7a2a367ba`). Report: `REPORT-C.md`, saved verbatim, sha256 `d48b2512…99e8a7a`. Reviewer C (Opus): harness 830.9 s; transcript 01:24:16Z–01:38:07Z. **Access:** the transcript audit finds no file outside its commission. It opened only `COMMISSION-C.md` and `REVIEWER-NOTE.md` in this folder, nothing in `00-access/`, no item-4 file, and no excluded source; every chapter path it used is inside the copy. C had no other reviewer's report. No integration review was run on C's report: the Professor re-checked its four high findings (V), and its normal findings are accepted on C's reading (R).*

| C-nn | Sev. | Check | Remedy, against the copy's text |
|---|---|---|---|
| H1 | high | **V (Professor):** S0138 L106 cites "§4.1 — Phase 7's precondition"; S0164 L2603 renumbers "4.1→4.4, 4.2→4.3 …, with a map in §0.5.2" | §8.7 C2: reclassify the "second case (S0164)" as the map remedy, not an instance of the rule. CON-0129 then rests on one case plus the proof. Register CON-0129: delete "cited identifiers survive a reordering". Align with §8.19 C27 |
| H2 | high | **V (Professor):** S0117 L213 ("STRUCTURALLY UNSATISFIABLE at establishment") and L225 ("`E10` requires a history that cannot exist at establishment time") | §8.15 C5, §8.15.4 and §8.23 C11: withdraw "source defect" and state C5 as the fork of §8.17 C2 (reading (ii) holds). Register CON-0224: put the fork in the lead sentence. CON-0218: its recorded ground ("permanently" refuted) depends on the fork; record it as conditional |
| H3 | high | **V (Professor):** copy `08.14` L22 reads "The authority declined to count a decided boundary as an adopted commitment …" | §8.14 heading, plain language and C1 body: state the limb-① reading as the registrar's reading (S0112 L14; S0120 L37), not as the authority's act. §8.15 C1: mark the agreement as coming from the same producer. Index row 8.14 (copy L28): the same |
| H4 | high | **V (Professor):** "CON-0256 (i)" occurs 4 times in the copy's `08.22` | §8.22 C7 heading, homes row, plain-language point 1 and §8.22.4: remove CON-0256 (i) as a home and state the deduction as following from the definitions. Ch. 8 TODO T8-04: the same |
| N1 | normal | R | Nine lines citing "§8.14 C1" for "a historical claim is not falsified by later acts" are repointed to §8.14 C3 (CON-0214): §8.18 L19, L158, L194, L196; §8.19 L138; §8.21 L79, L81; §8.22 L22, L90 |
| N2 | normal | R | §8.15 C2: rewrite the heading and the Int as evidence for neither rule (as §8.16 C2), and drop the layered notes |
| N3 | normal | R | §8.12 C6 and CON-0201: label the identity of "not established" and "NOT ATTESTED" as Int, and qualify the homonym "established" (S0102 L523) |
| N4 | normal | R | §8.17 C5: scope the "iff" to D, where every dependency is a citation, or weaken it to "if". §9.15 L74 (out of scope) is noted for stage 2 |
| N5 | normal | R | §8.18 C4 (a): delete W-07's second horn ("preserve both"); the first horn stands |
| N6 | normal | R | §8.13 C4 and CON-0207: read "a selection the decider did not make"; the act's text is evidence of that |
| N7 | normal | R | §8.11 C6 and CON-0181: drop "only"; give a falsifier for the detection claim |
| N8 | normal | R | §8.7 C2, the Ded: "…no outside reference to i or to j" |
| N9 | normal | R | §8.7 C3: cite §8.20 C19 and mark "17 of 39" as contested |
| N10 | normal | R; **merged with I-13 / N-2** | §8.8 C2: the tally is the book's, not the source's (S0075 L212 counts a "Third recurrence"). Together with I-13: "**Nine** cases in one estate (the book's tally)" |
| N11 | normal | R | §8.9 C2 and CON-0151: separate "empty within the period" from "permanently empty" |
| N12 | normal | R | §8.21 C1: keep only CON-0257's statement; move the history to §8.21.4 |
| N13 | normal | R | §8.23.1 and C12: the §8.22 fork was decided on 2026-10-27 (`7ecf5a024`); update both |
| N14 | normal | R | §8.19.3: keep only the current statement of the designation rule |
| N15 | normal | R | §8.25 C7: state the object of S0175's ESTABLISHED tag as the source gives it (L102–104, a current-state negation) |

**Out of scope (one line each):** T8-04 lists CON-0256 (i) (covered by H4); §9.15 K1 L74 carries the "iff" (N4); the CON-0151 row's "(VALIDATED as a distinction)" sits inside a PROVISIONAL row; R1's CON-0133 flag.

**Concept verdicts (C, from the text; to enter the register at stage 2):**
- **VALIDATED, second verdict (11):** 0129 (evidence one case; H1), 0153, 0219, 0220, 0226, 0232, 0239, 0240, 0242, 0245, 0253;
- **raised to VALIDATED (2):** 0225, 0231;
- **PROVISIONAL (47):** the rest of the 49, with these restatements: 0151 (N11), 0181 (N7), 0201 (N3), 0207 (N6), 0224 (H2);
- **INVALID cores confirmed (16):** with 0218's ground conditional on the fork (H2).

The coverage gap of §1 is closed: every concept in scope now has a text-based verdict.

**Measures, updated:**

| Measure | Value |
|---|---|
| Review harness, total | A 1,320.6 + B 990.6 + integration 773.4 + C 830.9 = **3,915.5 s (65 min 16 s)** |
| **Defects in the rotated chapters (confirmed)** | **Ch. 6: 4** (unchanged). **Ch. 8: 41**: 23 from A, B and integration, and 18 from C (C's N10 merged with I-13/N-2) |
| Overlap | C has no overlap measure with A and B, since its sections were the ones they did not read |
