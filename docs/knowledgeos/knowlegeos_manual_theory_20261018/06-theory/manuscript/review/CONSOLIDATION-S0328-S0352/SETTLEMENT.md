# Consolidation S0328–S0352: settlement (stage 2)

*2026-11-02. The Professor, on the Principal's authorisation of stage 2 under the guard in `PLANTED-GAP-RESULT.md`. Reports: A `a464f6714`, B `c5c8ac98b`, C `6b912b6aa`, D `1057c3e1d`, integration `0985739fc` (each with its sha256). Every finding is dispositioned below against the **working book**, on its own evidence; the copy is unchanged.*

## The guard (applied first)
- **Not applied, the working book already correct:** A-02 (`06.03` L51 reads "§6.1 C3"), D-01 (`09.19` L395 reads "(b) later, after reading resumes"), C F-01 (`08.23` L44 quotes S0159 L297 as the source has it). Their R3 lines stay as they are (all "stays true", or handled by D-02).
- **Q2** (`08.11` L31): no finding; the working book reads "§8.12 C3". No change.

## Theory first
- **Decisions shown as decided** (A-01, D-02): §6.5 now dates F22 ((C + O), 2026-10-21, §9.16 R4a) and F4 (constitute, within the kernel, 2026-10-20); C6's kernel reading is to be re-read under (C + O). §9.19.9 and the index row and TODO T9-01 say "decided" for U1–U6; §9.19 L114 is marked stale since U2.
- **Stale formulations of K1 and K2** (B F2, F3): CON-0239's "not presupposed by K2" is dated to K2 as worded at C25; five quotations of K1's old cell are dated and given the current cell.
- **§8.15 C5's fork** (B F1, F4, N4): the snapshot premise now reads "holds only if"; "not permanently" is scoped to reading (i); the source defect about E6/E7 is internal to S0117.
- **CON-0252 no longer asserted** (C F-02): §8.20's title restated; the draft deduction marked superseded by CON-0254 (ii).
- **Register meanings scoped** (C F-03, F-11–F-14): CON-0250 (a comparison the held item fails), CON-0251 ("cites" includes information first produced at e), CON-0257 (intended state fixed by another input; the follow-up's withdrawn home removed), CON-0259 (containment, R ⊆ survivor).
- **CON-0254 → VALIDATED within its premises** (reviewer C, proof steps; the integration: adequate for (ii); (i) rests on the standard assumption that hash fixed points are infeasible to find). The register now counts 57 VALIDATED, 112 PROVISIONAL, 96 INVALID.

## Dispositions

| Finding | Sev | Disposition | Where |
|---|---|---|---|
| A-01 | high | accepted | §6.5 C0, .4, .5 |
| A-02 | normal | **not applied (guard)** | — |
| A-03 | normal | accepted as modified by the integration (both trivial cases qualified) | §8.6 C2 |
| A-04 | normal | accepted as modified (checked: S0025 L62–80) | §8.1 |
| A-05, A-06 | normal | accepted | §8.8 table, C2, .1 item 3, .4 |
| A-07 | normal | accepted | §8.7 C2 |
| A-08 | normal | accepted (R1 now 0 flags) | §6.5 C1 tag |
| A-09, A-10 | normal | accepted | §8.5 header, C9; §8.3 C6 |
| A-11 | normal | accepted (checked: S0913 L1351–1375) | §6.6 C6 |
| A-12 + C F-10 + D-05 | normal | accepted, merged: one extension of the Ch. 8 forward-use note; a Ch. 6 note | 08.00; 06.00 |
| A-13 | normal | accepted in part: row 8.12, the Ch. 6 header and row 6.7 corrected; the review-trail notes left in the prose are **not removed** (they carry the provenance of corrections, the house practice in every chapter) | 08.00; 06.00 |
| A-14 | normal | accepted: finding-ID note and adviser pointer in the Ch. 8 index; note in Ch. 6; DP-3, G-1, BC-5 glossed (checked: S0078 L203, L208) | 08.00; 06.00; §6.5 |
| A-15 (a)–(d) | normal | accepted: premise stated; C1 relabelled a sketch; a second distribution given (joint LR 0.667, computed); the premise marked as not evidence | §8.2 C3; §6.1 C1; §8.6 C4; §6.4 C7 |
| A-17 | normal | accepted (each line checked present in the body) | tables §8.2, §8.3, §8.6–§8.9 |
| A out of scope: MAP L162 (F10's home §6.1 C3 or C2) | — | **not applied**: §6.1 does not mention OQ-S1; not verified; for the next map check | — |
| B F1 | high | accepted (both parts, text and register) | §8.15 C5; CON-0224 |
| B F2 | high | accepted (dated, text and register) | §8.18 C1; CON-0239 |
| B F3 (+ integration: §8.18 L212, §8.21 L168) | normal | accepted | §8.17 L76, L104; §8.18 L186, L212; §8.21 L168 |
| B F4 (+ N4) | normal | accepted (checked: S0117 L175–178, L242; S0127 L30) | §8.17 C2; §8.15 C5; CON-0224 |
| B F5 | normal | accepted: every "pending S0300" body marker annotated; C11's final check (S0300 L929–937) | §8.18; §8.19 |
| B F6 | normal | accepted as modified: the refuted sentences marked as drafts inline (not moved); the tags annotated with the restated concepts | §8.14 C1; §8.15 C2; §8.19 C17 (L216), C22, C25 |
| B F7, F8 | normal | accepted | §8.13 C5; §8.19 L17 |
| B F9 (+ integration: CAP-09 at §8.9) | normal | accepted: C-5/C-10 glossary in the Ch. 8 index; CAP-09 glossed at §8.9; "W-1 (an estate label)" | 08.00; §8.9; §8.10 |
| B F10 | normal | accepted | tables §8.14, §8.17; CON-0208, CON-0245 sources |
| C F-01 | high | **not applied (guard)** | — |
| C F-02 | high | accepted (title restated, filename kept; L96 marked superseded) | §8.20 |
| C F-03 | normal | accepted | CON-0257 follow-up |
| C F-04 (+ integration: §8.19 L167) | normal | accepted | §8.20 C2; §8.19 |
| C F-05 | normal | accepted | §8.20 C8 |
| C F-06 | normal | accepted as modified (inline tag) | §8.23 C12 |
| C F-07 | normal | accepted: v17, as recorded at the resolution (checked: `ce_model.py` "Version 17", 09.99 L71) | Ch. 8 TODO T8-04 |
| C F-08 | normal | accepted: L37's "now reads" updated; the missing C27, C28 and S0151–S0181 rows are pointed to their settlements in this consolidation's 08.99 row | 08.99 |
| C F-09 | normal | accepted: 117 cited lines added, computed by the Professor's script with range containment (a line inside a listed range counts as listed); bare "(Lnn)" lines not covered | tables §8.20, §8.21, §8.24, §8.28–§8.30 |
| C F-11 | normal | accepted (countermodel valid) | §8.20 C2; CON-0251 |
| C F-12, F-13, F-14 | normal | accepted (register; §8.20 C9 already scoped to "a byte comparison that the held item happens to fail") | CON-0250, CON-0257, CON-0259 |
| C F-15 | normal | accepted | §8.20 C19; §8.21 C10 |
| D-01 | high | **not applied (guard)** | — |
| D-02 (+ TODO T9-01 L11) | normal | accepted; the "as asked" lines kept as history (integration) | §9.19.9; 09.00 row 9.19; Ch. 9 TODO |
| §9.19 L114 (integration R3 on D-01) | normal | accepted: marked stale since U2 | §9.19 |
| D-03 | normal | accepted (checked: S0336 L39 via §8.75 C5) | §8.74 C4, home table |
| D-04 | normal | accepted: L119, L124 | §8.68 L140; §8.71 L129 |
| D-06 | normal | accepted (checked: S0344 L603, L608, L926, L930) — *the Professor's defect at C85* | §8.78 C3–C4 |
| D-07 | normal | accepted (checked: S0334 L443–459) | §8.75 C1 |
| D-a1, D-a2, D-a3 | normal (low) | accepted | §8.80 C1; §8.73 header; BOOK-OUTLINE rotation heading |
| D-a4 | normal (low) | noted: a sketch within its stated scope; no change | — |
| D R2: AST-018, beneficiary | normal (low) | accepted: glossed at first use | §8.77 head; §8.73 head |
| D coverage: six low MISSING items | — | recorded, not added: no loss of theory (D's own ranking, all low) | — |
| Integration new: A-03's first trivial case; N4 | normal | accepted (above) | §8.6; §8.15 |
| Integration conflicts (F-02/F6; F2/F-02; F1/F4; the SEQ note; T8-05; "adviser") | — | resolved as the integration proposes; T8-05 given its partial hits | as above; Ch. 8 TODO T8-05 |
| A coverage: T6-02, T6-03, T6-05, T6-11 | — | accepted: partly answered (T6-02, T6-03); closed rows marked (T6-05, T6-11) | Ch. 6 TODO |
| Concept verdicts (119, all given) | — | accepted; status change: CON-0254 → VALIDATED within premises; CON-0239 VALIDATED, scoped by dating; CON-0108, CON-0127 VALIDATED as conditionals (their texts already state the conditions) | register; §8.20 C6 tag; map |

## R3 (every accepted correction)
- The integration report's R3 table (§2) is adopted line by line. The lines it marks **"needs the same change"** were changed: register CON-0224 (F1, F4); §8.18 L212 and §8.21 L168 (F3); §8.15 L66 (N4); §8.19 L167 (F-04); register CON-0239 (F2). The lines it marks **"needs a different change"** were changed by their own finding: 09.00 L29 (D-02); §9.19 L114. Every other inbound line "stays true" or is "not affected", as the integration gives it.
- For corrections the integration accepted with the reviewers' own dispositions (A-05–A-17, B F5–F10, C F-05–F-15, D-03–D-07, D-a1–D-a3), the reviewers' R3 lines are adopted; none of them needs a change beyond the correction itself.
- New inbound effects of the settlement's own wording: the §8.20 title is cited by filename only (the filename is kept); the CON-0254 status change touches §8.20 C6's tag and the map line (both changed).

## Checks at settlement
- `corpus_quote_check.py` on the 35 changed sections: every bracketed quotation found (0 not found).
- `register_home_check.py`: 0 flags (CON-0133's H4 flag removed by A-08).
- Register counts recomputed from the file: 265 concepts, 57 VALIDATED, 112 PROVISIONAL, 96 INVALID.

## Procedure, disclosed
- **F-09, a first pass undone.** The Professor's first script counted a line as missing whenever its exact token was absent, even inside a listed range (263 additions). Its undo used a pattern that also removed original table content in six files. The four files with no other stage-2 edits were restored from the commit (`git checkout`), and the table rows of §8.20 and §8.21 restored row by row from the commit; then the corrected script ran. The final diff of those tables adds lines only.
- **D-06** was the Professor's own pointer error at C85 (the C85 settlement had cited S0344 from a numbering that skipped blank lines).
- **Not done (authorised scope):** no Rulebook change, no pointer-check tool (P-Q4), no T9-21, no T9-02.

## Coverage gaps carried forward (the integration, item 6)
- The SEQ of Ch. 8 §8.31–§8.72 was checked by no reviewer; the 08.99 rows for §8.1–§8.19 and L52–L109 were checked against their text by nobody. For the next consolidation that rotates or touches them.

## Measures
- Findings: 6 high (2 of them plant repairs, not applied: D-01, F-01) and 56 normal (1 a plant repair, not applied: A-02), plus 6 found by the integration review; none critical. Ch. 6: 8 defects; Ch. 8 §8.00–§8.30: 36 (A 11, B 10, C 15) plus the integration's 6; §8.73–§8.81 and the passages (D): 7 and 4 low.
- Planted-gap test: blind 3 of 4, combined 3 of 4 (`PLANTED-GAP-RESULT.md`).
