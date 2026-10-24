# Consolidation S0131–S0150: settlement, stage 2 (the working book)

*2026-10-24. By the Professor, after the Rule Reviewer's score (`PLANTED-GAP-RESULT.md`, `3b9b562bb`) and the Principal's instruction to start stage 2. Stage 1: `SETTLEMENT-STAGE1.md` (`00030fa97`). This document records what was applied to the working book, where the working book had the same defect, and what was not applied. The copy `BOOK-UNDER-REVIEW/` is unchanged.*

**The Principal's stage-2 instruction, quoted:** "Apply the corrections to the working book where it has the same defect. §8.21: restore the ledger claim on S0150. Do not merely retitle. §8.19 C19: restore both the heading and the first bullet against S0138 L116, not just the heading. R3 lines: go through every line of R3 output for each correction and state its disposition (unaffected / corrected / reassess). Ch. 3: re-verdict as PROVISIONAL the rows whose INVALID reason only says "unsupported", each with its reason."

## 0. Entry 58 record (complete)

| Measure | Value |
|---|---|
| **Harness time** | A 1,050 s · B 1,118 s · integration 1,065 s = **3,233 s** |
| Wall clock, review phase | 00:18:40 → 00:59:20 UTC (2,440 s; A and B in parallel) |
| Part 0 | 00:17:09 → 00:18:40 UTC (about 90 s), plus the plan (not timed) |
| Settlement stage 1 | 00:59:20 → 01:01:00 UTC (commit `00030fa97`). Most of it was drafted while the integration reviewer ran, so the 100 s understates it; the overlap was not separated |
| Score (the Rule Reviewer) | committed 01:06:16 UTC (`3b9b562bb`) |
| **Settlement stage 2** | 01:07:18 UTC (logged start) → the commit of this document (its time is in the commit). Includes the R3 dispositions (§3), the Ch. 3 re-verdicts, practice-2 citations, the quote baseline and R1 |
| **Defects in the rotated chapters** | **Ch. 2: 20** (A 13, B 11; both 7, A only 6, B only 5; union holding 18; plus M-1, M-2 found by the integration reviewer). **Ch. 3: 26** (A 13, B 16; both 7, A only 6, B only 11; union holding 24; plus M-4, M-5). One more Ch. 3 defect is A-13's, counted in A's 13 |
| Overlap estimate | Ch. 2 ≈ 22, Ch. 3 ≈ 32 (Chapman, the integration reviewer). **Indicative only:** the reviewers are positively dependent, so these are likely underestimates |
| Defects found at stage 2 that no reviewer listed | **3**, all in work stage 1 had accepted but not fully stated: §8.19 C10 L110's "only" (an R3 line of CON-0242, corrected); the A-16 premises present in the register but absent from the three section texts (corrected); CON-0009's kept-INVALID reason, which read as "unsupported" (restated, §4) |
| R5 notes | no finding critical by F4 only; none critical at all |

## 1. The planted-gap items in the working book (the score's items)

**Fact:** the plants existed only in the copy (base `c39473c28`). The working book, checked at stage 2:

| Item | In the working book | Instruction | Status |
|---|---|---|---|
| Q1, AC-7 (§3.8) | absent (`grep` over the manuscript: no match) | — | **no same defect**; nothing to apply. MM-1, the other undefined code A-20 found, is defined in 03.00 ([S0016 L151]) |
| Q2, "seal-line" (§8.18 C20) | absent | — | **no same defect** |
| Q3, §8.21 C14 (the ledger claim) | **present**: C14 "An internal ledger does not prove priority without an external anchor and trusted time", on S0150 L112–118 and L248–273, with its attack | "restore the ledger claim on S0150. Do not merely retitle" | **satisfied in the working book: the claim stands, and the section was not retitled away from it.** The section was renamed for the CON-0256 restatement (A-02); its title keeps "an internal ledger proves nothing outward". The References lines A-01 asked for (S0149, S0150) were added |
| Q4a, §8.19 C19 | heading "Detection windows shrink but do not close without serialisation"; first bullet: a write "inside the interval between the FINAL re-hash and the removal itself" is undetected (S0138 L116, in meaning) | "restore both the heading and the first bullet against S0138 L116" | **satisfied in the working book**: heading and first bullet both agree with S0138 L116 |
| Q4b, §8.20 C3's citation of §8.19 C19 | "§8.19 C19 found detection windows irreducible without serialisation" (L71) | — | **no same defect**: agrees with the working C19. Dispositioned line by line in §3 |

**Stage-1 treatment, acknowledged (the score):** Q3 was treated in part. The right remedy was offered but not chosen, and "decide by checking the working book" departed from the two-stage rule. Q4a was treated in part: stage 1 fixed only the heading and left the bullet's distortion. Both are recorded as the Professor's settlement defects. The working book's correctness on these items is a fact about its text, not a credit to stage 1.

## 2. Application of stage 1 (§2–§5 of `SETTLEMENT-STAGE1.md`)

*"Same defect" means the working book had the defect at the freeze (`8981f6d8e`). It held for every stage-1 row except the planted items (§1). Locations are the working book's.*

### 2.1 Verdicts

| CON | Applied |
|---|---|
| 0239, 0242, 0245, 0253 | VALIDATED; tags in §8.18–§8.20; register; map. 0253 "at least three axes"; 0242 "one sufficient route" |
| 0240 | VALIDATED, term narrowed: §8.18 C2 heading, 8.18.1, index row |
| 0256 | INVALID as stated, **restated as CON-0257** (a reference must carry information that originates independently of the act and that the act cannot rewrite), with A-02's correction bullet in §8.21 C1; EXT-094 (test-oracle problem; not opened) as prior work. CON-0255 stays INVALID as stated |
| 0038 | INVALID as stated, **restated as CON-0258** (invariant versus choice relative to stated guarantees G); §3.6 |
| 0243 | **split**: the operand condition becomes **CON-0259**; "shows that one is missing" becomes "shows that the set differs; only a list shows what differs" (§8.18 C20). Done in one change with CON-0256 (K3) |
| 0033 | restated core given an ID: **CON-0260** (projection defined by regeneration, the norm kept separate); CON-0045 cites it |
| 0010, 0007 | PROVISIONAL (§3.4, §3.2, T3-10, T3-02, SEQ-0012) |
| 0063 | VALIDATED, term "cannot bind at t" |
| 0056 | basis replaced with S0024 L84–88 |
| 0251 | "is shown before"; EXT-084 (Lamport 1978); corpus first: S0915 L66–91, S0991 L667–709 |
| 0254 | (i) "cannot feasibly" |
| 0162 | **A-13 applied at stage 2:** one stated case set; (ii) shown to imply (i), which is kept only as a first check (§3.2, register) |

### 2.2 Findings

| Row(s) | Applied in the working book |
|---|---|
| A-01 = B-02 | see §1 (Q3); References extended |
| B-01 | "on the container" removed at §8.20 item 1, C9 heading, C9 Int |
| A-02, A-05, B-06, B-07, A-15 | as §2.1 |
| A-16 | premises in the register (rows 245, 250, 255) **and, at stage 2, in the section texts** (§8.18 C24, §8.19 C17 restatement, §8.20 C6 restatement) |
| A-08 = B-10 | INVALID homes replaced (§8.20.3 → CON-0254; §8.19 → CON-0249; §8.21 → "two necessary-condition forms (CON-0085 INVALID)") |
| A-09 | §8.20 C14 pointer corrected; S0140's marker lifecycle added to §8.19 C22 |
| A-17 | §8.18 C10 and C21 "only" withdrawn; **§8.19 C10 L110 also** (R3, §3) |
| B-03 | "independently" withdrawn; lineage notes on "agreement" in §8.18–§8.20 |
| B-08, B-09, B-12, B-40 | applied (§8.19 C18 forward note; C19 already correct, §1; Ch. 8 index dependency note; §8.18 C24 order note) |
| A-25 / B-13 coverage | the MISSING items of stage-1 §4 added to the sections' coverage additions or excluded with a reason |
| A-24 / B-11 | THEORY-MAP §9 (§8.1–8.21, the S0082 basis for the count, register 260, Windows C25–C28 block); BOOK-OUTLINE (Ch. 8 §8.1–8.21, marks for §8.18–§8.21, boundary tests) |
| Ch. 2: A-03 = B-15, B-16, A-07 = B-24, A-14, A-18 = B-17, A-19 = B-18, A-20 = B-20, A-21, A-22 = B-21, B-14, B-22, B-23, A-24, M-1, M-2 | all applied (02.00, 02.00a, 02.04, 02.05, 02.08, 02.09, 02.10, 02.11, Ch. 2 TODO; Ch. 1 L269 and T1-02 for K2). B-19 rejected (does not hold) |
| Ch. 3: A-04 = B-04, A-06 = B-31, B-32, A-10 / B-26, A-11, A-12, A-19 / B-27, A-20 = B-25, B-28, A-22, A-23 = B-29, B-30, B-33, B-34, A-24 = B-36, B-38, B-39, M-4, M-5 | applied. **A-13 and A-22's §3.2 part were found unapplied during the stage-2 check and applied then.** A-23: the one real quote failure (03.06 "remain HYPOTHESES" [S0025 L153]) is corrected; the two typography flags did not hold, and 03.01 L22 and 03.04 L11 were nevertheless made exact with marked elision, which removes the book's 3 inherited quote failures |
| **B-35** | **not applied: carried as T3-28.** The §3.10 C4 count (1,940 / 0 / 946) was reproduced by three reviewers, but no script and run are kept yet. The note at §3.10 L105 says so |

### 2.3 Practice 2 (`PRACTICE2-NOTES.md`)

| CON | Applied | Classification |
|---|---|---|
| 0251 | §8.20 C2 bullet: "Lamport's happened-before relation gives us a useful formal abstraction" [S0915 L68]; S0991 L667–709; EXT-084 | established (adapted) |
| 0245 | §8.19 C1 bullet: S0991 L381–387, L411–473; EXT-092 (Berenson et al. 1995; not opened) | established |
| 0253 | §8.20 bullet before C16: S2389; EXT-093 (DeMillo et al. 1978; not opened) | established (adapted) |
| 0257 | §8.21 C1: EXT-094 (Barr et al. 2015; not opened) | adapted |

*Ext entries EXT-092–094 are marked "not opened or verified" in EXTERNAL-SOURCES.*

## 3. R3 dispositions, line by line

*Each correction's R3 output (`inbound_citations.py`, saved in the scratchpad, one file per target) was read line by line. "Unaffected" means the line's use does not depend on the corrected part. Line numbers are the working book's at the time of the run. Lines repeated across targets are dispositioned once, at their first target.*

| Target | Corrected | Unaffected | Reassess |
|---|---|---|---|
| **CON-0256 / §8.21 C1** (→ 0257) | §8.21 title, C1 heading and A-02 bullet, plain language item 1, L15, L46, L182; index row 8.21; register 256–258 | 8.18 C20 lines; 8.19 C2, C11, C17; 8.20 C3, C7; map; outline 215; map 299, 358 | — |
| **§8.21 C14** | References (S0149, S0150) | all other lines (they cite the claim, which stands; §1) | — |
| **CON-0251** | index row 8.20; §8.20 C2 heading; A-05 bullet; register; map | §8.20 C6, C8, C9 lines; history notes; the §8.20.3 open question (L245); the CON-0252 row | — |
| **CON-0254** | index; §8.20 C6 heading; L101; L246; register 255; **(ii)'s premise in the text (A-16)** | L62, L67, L263; the CON-0252 row; map; outline 223 | — |
| **CON-0250** | §8.20 L118, L120 | the rest | — |
| **CON-0063** | 02.08 L56; T2-10; register; map; outline | L55; SEQ-0006 | — |
| **§2.11 "effective"** | 02.00a L46; 02.08 L56; T2-10; Ch. 1 L269; T1-02; register 64 | 00.01:27; 00.02:36; 01.13:97; 01.14:94; 01.16:43; 01.17:39, 48; 02.00a:48; 02.03:39; 02.08:29 (premise note added under A-14, separately); 02.08:187; 02.99:30; T2-09; SEQ-0006; 08.01:34; 09.08 lines; 09.14:40; register 62; outline 12 | — |
| **CON-0010** | 03.04 L7; T3-10; SEQ-0012; register 11; map 19 | 03.01 L67 (uses distinctness only); register 40 | — |
| **CON-0038** (→ 0258) | 03.06 L74; T3-27; register 39, 259 | L82 (history) | — |
| **CON-0007** | all lines (03.02 L31, L35; T3-02; register) | — | — |
| **CON-0243** (→ 0259) | 8.20 L18; 8.21 L15, L46, L182; register 244, 260 | 8.18 L184, L212, L237; 8.19 C2, C11, C17; 8.20 C3, C7 Int, L109; 8.21 L50, L164, L168; register 248, 250, 256–258; map; outline; index rows | — |
| **CON-0240** | index row 8.18; 8.18 L41; register | 8.18 L214, L232; 8.19 L78, L81, L121, L132, L135; register 249; map; outline | — |
| **CON-0092** | — | all (41/22 already handled at 02.10:44 and qualified at 02.09, 02.10) | — |
| **§3.8 C5** | map L25; 03.04 L5, L7 | 01.05:11; 03.09:7, 65, 101, 182; TODO lines; register rows; map 162; section-only lines | — |
| **CON-0011** | register 12 | the rest | — |
| **CON-0033** (→ 0260) | 03.05 L46; T3-16; register 46 (CON-0045), 261 | 01.14:522; register 34 | — |
| **CON-0006** | 03.01 L20 note; T3-09; register (reason restated) | L40, L50, L78; map 16 | **reassessed: kept INVALID** (§4) |
| **CON-0008, 0041, 0043, 0065** | tags; TODO rows; register | 03.07:64 (supports); 06.01:81; T6-02; SEQ-0010; 06.03:19 | — |
| **CON-0056** | register 57 | 02.10:30; SEQ-0002, SEQ-0003; 06.04:34; register 72, 103 | — |
| **§8.19 C19** (Q4b) | 8.18 L191 ("only" withdrawn, an A-17 site found through this list) | 8.20 L69, L71, L114 (agree with the working C19); section-only lines | — |
| **§8.20 C9** | 8.21 L15, L46 | the rest | — |
| **CON-0239** | — | all: index 8.18; 8.18 C1, C7, C12, C13 and their Int lines, L233; 8.19 L17, C3, C4, C13, C18 (forward note already added under B-08), L259; 8.20 C4; 8.21 C3; register 240, 247, 249, 252; map 187; outline 221, 225 | — |
| **CON-0242** | 8.18 C10 Int (one sufficient route); 8.18 L191; **8.19 C10 Int L110** ("CON-0242 makes a result checked only when another party re-runs it" → "as first stated … 'only' withdrawn"); register 243 | 8.19 L38, L97, C23 Int (L199, "closed requires another party" is about remedies); 8.20 L19, C7 Int, C11 Int, C12 Int and L147, L183, C23 Int; register 244, 254; map 187 | — |
| **CON-0245** | §8.19 C1 tag (validated); practice-2 bullet; register 246; map 188 | 8.19 L52, C10 Int; L235; L261; outline 225 | — |
| **CON-0253** | §8.20 tag (validated); practice-2 bullet; register 254; map 189 | 8.20 C12, C15 and its Int, L180, L183, C17 and its Int, L266; 8.21 L85, L133 | — |
| **CON-0244** | **§8.18 C24 Int: the premise (A-16)** | index 8.18; 8.21 L21, L147, L191; register 245 (premise already there); map 187, 196 | — |
| **CON-0249** | **§8.19 C17 restatement: the premises (A-16)** | 8.19 L151, L218, L237, L257; 8.20 C10 and its Int (L127); 8.21 C5 and its Int (L81); register 248, 250 (premises already there); map 188 | — |
| **CON-0162** (A-13, stage 2) | §3.2 L9 note; register 163 | T3-21 (about CON-0009); register 10 | — |

*No line was left at "reassess" except CON-0006, settled in §4.*

## 4. Ch. 3 re-verdicts (the Principal's K5 instruction)

**Rule applied:** re-verdict as PROVISIONAL a Ch. 3 INVALID row whose reason says only that the concept is unsupported (absence of evidence, not a contradiction, a counterexample or a mis-typed definition).

**Re-verdicted PROVISIONAL, each with its reason (register `basis` and section tag):**

| CON | Reason |
|---|---|
| 0008 | consistency open (U-MM-1), not shown to fail |
| 0041 | the source's own hypothesis on one capability; not verifiable is not refuted |
| 0043 | a definition exists, contested and given by example; no contradiction shown |
| 0065 | graded by an inference from absence: unsupported, not false |
| *0010, 0007* | *already PROVISIONAL at stage 1 (A-04 = B-04; B-32)* |

**Considered and kept INVALID:**

| CON | Why the reason is more than "unsupported" |
|---|---|
| 0006 | **reverted after an R3 reassessment:** it conflates binding strength, enforcement consequence and exercise history (§3.1 L40–78, the split at L78) |
| 0003 | consistency fails in scope (values outside the 8; a second vocabulary for the same position) |
| 0009 | "sufficient" is predicated of one dimension, but is a property of the set (§3.2 L9). Restated as CON-0162. *Its register reason read as "untested" and was restated at stage 2* |
| 0011 | the paradigm instance (PKS) lacks the defining feature (S0020 L96) |
| 0029, 0031 | overlapping definitions (bounded-contexts.yaml counted in two layers) |
| 0030 | incoherent membership |
| 0032 | the book's gloss is not the source's ("declared by an act") |
| 0033 | the governing record DP-2 stands DEFERRED; restated core CON-0260 |
| 0034 | binding ≠ operating, shown by CAP-002 |
| 0035, 0036 | the kind assigned is not among the declared kinds |
| 0037 | inconsistent application (L89 vs L115; L91 vs L159) |
| 0038 | circular (restated CON-0258) |
| 0039 | circular (SEQ-0012) |
| 0042 | its own invariant I-7 breached |
| 0044 | a counterexample (a voluntarily adopted external standard) |
| 0045 | its evidential ground withdrawn by the source |
| 0046 | partly definitional; a stronger counter added (B-34) |
| 0057 | refuted as a necessary condition (§6.1 C4) |
| 0066 | holds in one of three senses; ignores a stated exception |
| 0067 | not applied to all relations |
| 0091 | refuted by §3.9's own referents |
| 0096 | defined by a quote containing the answer; replaced by CON-0098 |

**Register after stage 2:** 260 concepts: **45 VALIDATED, 121 PROVISIONAL, 94 INVALID**.

## 5. Checks

| Check | Result |
|---|---|
| **Quote check** (whole book, `corpus_quote_check.py`) | 1,082 found, **0 not found** (baseline was 3, all inherited in Ch. 3). **`tmp/tools/QUOTE-BASELINE.txt` regenerated**, with the reason recorded in its header: the three inherited failures were repaired in the Ch. 2–3 rotation |
| **R1** (`register_home_check.py`) | 236 rows with a section home; **1 flag**, inherited: CON-0133's H4 tag carries no status (register INVALID, text "merged into ARCH-INV-1"). Not in this consolidation's scope; unchanged |
| Stale filename | the renamed §8.21 file: no reference to the old name outside `review/` and the copy |

## 6. Not done, carried

- **B-35** (a script and run for §3.10 C4): T3-28.
- **Open kernel questions** from §8.18.3–§8.21.3, unchanged: they go with the S0150 decision (`S0150-EVALUATION-FACTS.md` §5).
- **K5** is now answered in part by §4. Whether the instruction scale should state "unsupported ⇒ PROVISIONAL" as a rule is for the S0150 decision. The rules stay frozen until then.

## 7. Traceability

Plan `PLAN.md` (approved) · part 0 `8981f6d8e` · practice 2 `aafb7d9f0` · reports `a322a8d71` · stage 1 `00030fa97` · score `3b9b562bb` · this settlement and the working-book edits: the commit that adds this file.
