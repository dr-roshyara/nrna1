# Consolidation S0353–S0377: settlement

*2026-11-03. The Professor, on the Principal's ruling of 2026-11-03 ("Continue the S0353–S0377 consolidation on the saved reports with C's 02-FILES.jsonl read recorded; …"). Reports, each with its sha256 in this folder: A `db47ec08f`, B `19e30535e`, C `15a37dbed`, D `24b4d55f8`, E `ecca13fb8`, F `a9380ae6f`, integration `0d58a7986`. Every finding is dispositioned below against the **working book**, as the integration review confirmed, rejected or modified it; the frozen copy is unchanged. No planted-gap test ran at this consolidation.*

## Theory first
- **Critical, C-F2 (confirmed): §9.16 R2 (a) was not re-checked against §9.17's designation clauses.** "The merged record is in D unless the merge is refused" now reads "… or the merged record fails D's designation clauses (§9.17 R3 (c))": a fork, or a single foreign move, leaves it outside D, and Q1 then refuses it. The dependents are restated with the same condition: R1's "mergeable", Lemma D across records, T1 on a merge, the internal guarantee (§9.16.6), §9.13 L276, the index row 9.16, and register CON-0194. **Effect: a narrower scope, no collapse** (fail-closed). No result outside §9.16 cited (a) (integration R3, grep of the seven named Ch. 8 files).
- **Lemma D's premise (A-04, high).** The conclusion now carries the exception its proof step 1 uses: cuts that cross no recorded loss which drops identities; after tombstones, values only, under §9.16 R5's reading, which stays returned. CON-0177 stays VALIDATED for such cuts; the post-tombstone part is PROVISIONAL (integration conflict 1).
- **Decided forks carried into older passages** (A-01, A-02, A-05, A-06, A-09, C-F3, C-F4, D-02, N-1–N-3): F26 is (b) at ties; F22 is (C + O); K4 decided per law; F36 adopted; U2 places g1's class in the kernel. Index rows 9.3, 9.4, 9.8, 9.9 and 9.16 restated from their restated cores. "Q4's reference no longer waits on F13" is labelled the Professor's Ded from U2; F13 stays open.
- **"Candidate axiom" (A-03, modified with C-F8).** Not "superseded": occurrence is **classified a world assumption under §9.16 R7**, which C19's review found applied as worded and which stays provisional as Int, its line returned to the Principal.
- **Logic gaps stated, not closed:** CON-0173's step 4 needs a non-cancellation premise or reads as extensional equality only (A-11); the g1 U row over-approximates where one atom enters both ref and sup (D-01; candidates over atoms open); T3's "exactly when" becomes "if; only if under H-down and H-mono" (D-07); T9 (ii) gets its proof steps and holds on finite 𝒦 without T3's hypotheses (D-08); "D's clauses have a model" is trivial satisfiability by the empty record (F-05).
- **The refusal observation recounted (F-01, interpretation):** §8.90.3 no longer says G8's reasons are recorded (they are returned, §8.91 C6). S0366 (B) is an earlier occurrence, so §8.96 C3 is the third, not the second. Opening a fork stays the Principal's (W-10 of C103).
- **Register:** no status change (57 VALIDATED, 112 PROVISIONAL, 96 INVALID). Meanings restated: CON-0099 (Pres), 0177 (scope), 0182 (R7), 0183 (parse; "consume"), 0193 (current core), 0194 (C-F2; cuts evaluable), 0264 (acceptance vs down-closure).

## Dispositions (as the integration review gives them)

| Finding(s) | Sev | Disposition | Where |
|---|---|---|---|
| C-F2 | **critical** | accepted (the integration's correction, with A-04 in one restatement for the Lemma D row) | §9.16 R1 L67, L69, L73; R2 (a); §9.16.6; §9.13 L276; 09.00 row 9.16; CON-0194 |
| A-01 + N-3 | high | accepted | 09.00 rows 9.3, 9.4, 9.8, 9.9, 9.16 |
| A-02 | high | accepted ("F26 (b) beyond ties") | §9.7.7 result 2 |
| A-03 + C-F8 | high | accepted as modified (classified under provisional R7, not superseded; attribution corrected) | §9.9.5, result 4; 09.00 row 9.9; CON-0182; map L301; §9.15 R8 (c); §9.16 R7 heading |
| A-04 | high | accepted (premise added; verdict split) | §9.8 Lemma D, L98; §9.13 L277; §9.16 L69; CON-0177; map L315 |
| C-F1 | high | accepted (C's restatement) | §9.15 R3, R6 |
| C-F3 (+ B out-of-scope F36 note) | high | accepted | §9.16 R1 F22, result 5, laws 9b–31, F36 rows |
| C-F4 | high | accepted (superseded note; §9.13 L37 already annotated) | §9.16 R3a |
| D-01 | high | accepted (restatement; candidates marked an over-approximation; "only when" kept) | §9.18 L70, L200; TODO T9-17, T9-19 |
| D-02 (+ D App. B §9.10 L174) | high | accepted (R3 notes at the eleven lines; Ded label) | §9.19; §9.10 L174; TODO T9-01; BOOK-OUTLINE L179 |
| F-01 | high | accepted (recount as interpretation) | §8.90.3; §8.96 C3; map C102, C103 lines |
| A-05, N-1 | normal | accepted ("decided: (b) at ties" notes) | §9.6.6a items 2–4; §9.7 step 2, L132, L143; §9.8 L50, L126; §9.13 L276 |
| A-06 | normal | accepted (status note; L238 points to R4, R3a) | §9.6 L7, L238 |
| A-07 | normal | accepted (pointers; the §9.9.8 comparison kept open, content missing) | 09.00 L3; §9.9.8; §9.10 L206; TODO T9-01 |
| A-08 + D-12 | normal | accepted (one joint edit of T9-01; T9-02 next action; T9-14 widened; rows T9-22, T9-23 added) | TODO |
| A-09 | normal | accepted | §9.8 L29 |
| A-10 | normal | accepted | 09.00 glossary |
| A-11 | normal | accepted (the gap stated in the step; proof not given) | §9.7 step 4 |
| A-12, A-13, A-14, A-16 | normal | accepted | CON-0099; §9.1 L97, CON-0183; §9.1 L21, L23; §9.6 L122 |
| A-15 | normal | accepted (pointer labelled Ded) | §9.5 C5; §9.19 L394 |
| A-17 + C-F15 | normal | accepted, merged | §9.5 References; 09.99 L91–92; TODO T9-08 |
| B-F1 … B-F11 | normal | accepted (B-F8 as a clause in the constant-T row, to keep line numbers) | §9.10 L60, L144, L175; §9.13 L45, L69, L356; §9.12 L47, L65, L81; §9.11 L52, L98; §9.14 L137; 09.00 row 9.12; BOOK-OUTLINE L174 |
| C-F5, C-F6, C-F7, C-F9, C-F10, C-F11, C-F12, C-F14 | normal | accepted. C-F6 as a reading note at §9.15.6 (qualifying every "D" in §9.15 and §9.17 one by one is left to their next revision) | §9.15 L5 (L5 → root entries), L10, L105, L154, L157, L197, L204; §9.17 L175, C4, L302; CON-0193, CON-0194, CON-0264; BOOK-OUTLINE L175 |
| C-F13 | normal | accepted as modified: forward-use notes at R4d and the K1 cell; R4c not moved, to keep line references | §9.16 R4d; §9.15 K1 |
| D-03, D-04, D-05, D-06, D-07, D-08, D-09, D-10, D-11, D-14 | normal | accepted | §9.19 L29, L105, L167, L270; §9.18 L98, L107, L174, L205, L209, L263, L321; §9.20 L5, L47, L51, L92, L116; TODO T9-04 |
| D-13 | — | **rejected** as a copy defect (the rotation row was due at settlement); the row is added | BOOK-OUTLINE rotation table |
| D-15 | — | **no question needed**: answered by the Principal's standing outside-authors rule (persons named as people are given by role; eponymous terms and citations stay). §9.18 L103 ("Doyle 1979"), §9.20 L102 (Myhill–Nerode) and §9.10 L33 ("Snodgrass and Ahn 1986") are citations or eponymous terms: they stay | — |
| N-2 | normal | accepted | §9.16 R2 (c) |
| N-4 | normal | accepted (addendum 2026-11-01T17:32:50Z cited in U1–U6; U6's addendum text quoted) | §9.19.9 |
| integration §5 (§9.20.8 stale "put to the Principal") | normal | accepted | §9.20 L158 |
| E-01 … E-12 | normal | accepted; E-03 as modified by the integration (L62's paraphrase corrected; the conditional kept); E-09 merged with F-07 | §8.82, §8.84, §8.86–§8.89; 08.00 L109 (row 8.93), L116; §8.93 L71 |
| F-02 … F-12 | normal | accepted; F-06 by the position line; F-07 merged with E-09 | §8.90–§8.92, §8.94, §8.95, §8.97; 08.00 rows 8.92, 8.95, 8.98; map L448–452 |

## R3 (every accepted correction)
- The integration report's R3 table (§2) is adopted line by line. The lines it marks **"needs the same change"** were changed: §9.16 L69, L73, L409; CON-0194; 09.00 row 9.16 (C-F2); map L301, CON-0182 (A-03); CON-0177, map L315, §9.13 L274–277, §9.8 L98 (A-04); BOOK-OUTLINE L174, 09.00 row 9.12 (B-F4); TODO T9-17, T9-19 (D-01); TODO T9-01, BOOK-OUTLINE L179, §9.10 L174 (D-02); TODO T9-04 (D-07); 08.00 L109, §8.93 L22 and L71 (E-03; L22 is the uses table and needed no change, the claim is at L71); §8.94 L80 (F-05); map C102 and C103 lines (F-01). **"Needs a different change"**: §9.16 L67 ("and the union in D"), 09.00 L19 (attribution), 09.08 L29 (A-09's note), §8.96 L58 (recount), §8.39 L17 (D-01: the candidate wording — **not applied at this settlement**: §8.39 is outside this span's sections; recorded for its chapter's next rotation), 09.99 L97 (B-F1: covered by this settlement's 09.99 row).
- §9.10 L194, L249 (A-04 at past cuts) were reported by A and not re-checked; **not applied**, recorded for the next Ch. 9 revision.
- Every other inbound line "stays true" or is "not affected", as the integration gives it.

## Findings and misses per window (the Principal, 2026-11-03, decision 2 and 5)
- **Findings per window:** `RN-COUNTS.md` (229 window findings, 77% restatement items).
- **What this consolidation found that the windows missed** (E and F, confirmed): C89 §8.82: E-04, E-05, E-06 · C91 §8.84: E-07, E-08 · C93 §8.86: E-01 · C94 §8.87: E-02, E-12 · C95 §8.88: E-03, E-10 · C96 §8.89: E-11 · C97 §8.90: F-01, F-04, F-11 · C98 §8.91: F-09 · C97–C99 §8.90–§8.92: F-02 (routed by C101, unfixed until now) · C99 §8.92 and C101 §8.94: F-05 · C102 §8.95: F-03 · C103 §8.96: F-01's recount · C104 §8.97: F-10, F-12 · C105 row 8.98: F-08 · all: E-09/F-07 (the SEQ note). **22 misses over 17 windows; by the RN reading 19 are restatement items** (a mapping against a settled home: E-01, E-02, E-03, E-06, E-07, E-09/F-07, E-10, E-11, F-01, F-02, F-03, F-04, F-05, F-08, F-11 and the citation slips E-04, E-12, F-09, F-10), **3 new** (E-05's purpose clause, E-08, F-12) *(the Professor's classification, one classifier)*.
- **The Professor's defects found here:** the D-scope pattern again (F-05: "D's clauses have a model"; C-F2 is a missed re-check of the Professor's own turn-7 text after turn 8) and missed homes (F-02, F-11).

## Access deviations
- Recorded in `ACCESS-DEVIATION.md` (this folder) and entered in `00-access/ACCESS-RECORD.md` with C103's. **Open:** the Governance Engineer's check whether `02-FILES.jsonl` names any sealed, hold-out or quarantine file.

## Checks at settlement
- `corpus_quote_check.py` on the 31 changed sections of Ch. 8 and Ch. 9: every bracketed quotation found (0 not found).
- `register_home_check.py`: 241 homed rows, 0 flags.
- Not re-run: the models (`ce_model`, `kernel_model`, `t902_state_model`); no correction changed a model.
