# R7 · T3 (d′) sync proposal: r1.8 → r1.9 (the CB1 / CB23 cells, per spanning reading)

**Status: PROPOSED, not applied; draft r1.1 (2026-10-12); r1 verified PWL; r1.1 fixes not re-reviewed.** Worker draft. r0 was reviewed by a fresh independent review: PASS_WITH_LIMITATIONS, with 2 material findings (F1, F2) and 7 minor (F3–F9), all applied in r1. r1 was verified: PASS_WITH_LIMITATIONS (V1 material, V2–V5 minor), applied in r1.1 (§8). Nothing here is applied to `R7/T3-CANDIDATE-MODEL.md`. Nothing is adopted, no VP is decided, no spanning rule is selected, the asymmetry is not accepted, and E2b is not amended. Applying the patch in §3 needs the Human ARB act in §5. Until that act exists, T3 stays at r1.8.

**Standing qualifier (applies to every statement here, verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Authority and place in the sequence.** F-LOG-0284 (Human ARB, 2026-10-12) accepted E3b with A2 = (b). The ⚠E3b outputs (E3-BC1, E3-BC23, E3-REL, the E3-PP placement, and every context and cut-status column) stay **not accepted** until two separate acts are made: "(1) the (d′) T3 sync; (2) an E2b amendment adopting the whole synced CB1/CB23 §2 rows. The reading-dependent cells are adopted per reading, with no reading selected." The human then set the order of work as "Both, contexts first" (F-LOG-0285: "Complete (d′) and E2b Amendment 2 first, then a single E4/R8 package for the whole accepted architecture"; "It is **not an authorization**. Each step still needs its own act"). **This file is act (1) only.** Act (2) is a later, separate proposal and is not drafted here (§4 gives a pointer only).

**What (d′) is (sources, verified).**
- E2b r1.2 §A1.11: "**(d′) (new)** | T3 sync of the CB1 / CB23 cells (M5) and the hosting-keyed R4 clauses (G6), including the missing R4 ids under S | open; not on the R21 / F8 list".
- E2b r1.2 §A1.2 (G6): "the CB23 'shared rows … R8' clause is reading-dependent, since R8 is internal to CB1 under S′ / S″; the CB1 cell carries no R4 ids for R5, R6, R10, R19, R25 and R38, although these rows cross under S. … The hosting-keyed clauses and the missing R4 ids under S are part of open item **(d′)**."
- E2b r1.2 §A1.2 ("Not adopted: the stale T3 cells"): "The §1a CB1 and CB23 T-A / T-B cells, the §2 CB1 and CB23 'T2 ids' cells, the hosting-keyed R4 clauses (G6) and the §2 'reading sensitivity' cells. … under S they are wrong for R5, R6, R10, R19, R25 and R38; under S′ and S″ they are wrong for R8 (T3 r1.7 §1a note, §2 note M5, §1a note N5). Fixing them is open item **(d′)**."
- E2b r1.2 Act I (1) (F-LOG-0275): "The stale T3 r1.7 CB1 / CB23 cells are not adopted." §A1.9b adds the reopen row "T3 sync of the CB1 / CB23 cells (M5) and the hosting-keyed R4 clauses (G6), open item (d′)"; §A1.9 D6 records it as open.
- `R7/E2b-AMENDMENT-1-PROPOSAL.md` r2.2: E2 ("CB cells wrong under every reading … new open item **(d′)** 'T3 sync of the CB1 / CB23 cells (M5)', distinct from (d)") and G6 ("Excluded: the CB1 'shared guards R14, R15' clause and the CB23 'shared rows R8, R9, R16, R21, R22' clause. CB1 lacks R4 ids for R5, R6, R10, R19, R25 and R38 under S; this is disclosed. All of it is folded into (d′)").
- T3 r1.8 line 53 (r1.7 note, with N5) and line 432 (M5 note): the CB1 and CB23 cells "reflect the r1.6 provisional placement (C1 on CB23)"; "the cells are superseded for these rows only".
- `R7/T3-SYNC-PROPOSAL.md` r1.3 §4 item 2: the §2 CB23 sensitivity cell "still reads '(C1 provisional in CG1; re-check after VP12)'", a supersession the M5 note does not carry.
- `R7/E3-ARCHITECTURE-PROPOSAL.md` r1.1 §7.2, reading (b), names what is missing for E3-BC1 / E3-BC23: "the §2 'T2 ids' cell; the evidence-class, inherited-assumptions, reading-sensitivity and open-questions cells; the hosting-keyed clause 'for the shared guards R14, R15 …'; the R4 ids for R5, R6, R10, R19, R25 and R38 under S", and for CB23 "the hosting-keyed clause 'for the shared rows R8, R9, R16, R21, R22, as CB1' (reading-dependent …)".

**"Current record"** used for "correct": VP14 = P-c (F-LOG-0265); VP12 = (i), one shared participant spanning CB23 and CB1 (F-LOG-0268); the VP12 re-check (T3 r1.7 §1a rule 2a, crossing table, "VP12 re-check" (a)–(g); reviewed F-LOG-0270); no spanning rule selected (F-LOG-0271). Per F-LOG-0271 and binding condition B2 (`R7/O3-PREPARATION-AUTHORIZATION-PACKAGE.md` r1.1 §3: "S, S′ and S″, none privileged"), **every reading-dependent cell below states its value under S and under S′ / S″, and selects none.** S′ and S″ give identical results on all 83 T2 rows (T3 r1.8 §1a rule 2a), so they share one value in every cell.

**Base text.** `R7/T3-CANDIDATE-MODEL.md` **r1.8** as committed in `4e2122175` (F-LOG-0274), verified unmodified in the working tree when this draft was made (`git diff HEAD` empty), 610 lines. All line numbers refer to that text. Each anchor quoted in §3 was checked by script: it occurs **exactly once** in the file, on the stated line; the patch was applied to a scratch copy, where (i) only lines 1, 3, 48, 49, 403 and 404 change and one row is appended after line 610; (ii) the four table rows keep their cell counts (7, 7, 8, 8); (iii) every R-, Def-, OQ- and C-token of every old cell survives, in order, in the new line (struck or verbatim; nothing deleted); (iv) the standing qualifier's occurrences are unchanged.

**Sources read:** `F-GOVERNANCE-LOG.md` F-LOG-0265, 0268, 0270, 0271, 0273–0275, 0282–0285; T3 r1.8 (whole file); `R7/T2-RULE-CANDIDATES.md` r1.4 ("rests on" column of R2, R3, R5–R10, R12–R16, R19, R21–R25, R27, R29, R30, R35, R36, R38, R39, R79, R80, R83); `R7/T1-CONCEPT-CANDIDATES.md` r1.6 (C1, C2, C16; OQ1–OQ11); `R7/E2b-ADOPTION-TEXT.md` r1.2 (§A1); `R7/E2b-AMENDMENT-1-PROPOSAL.md` r2.2; `R7/T3-SYNC-PROPOSAL.md` r1.3; `R7/E3-ARCHITECTURE-PROPOSAL.md` r1.1; `R7/O3-PREPARATION-AUTHORIZATION-PACKAGE.md` r1.1 §3 (B2).

---

## 1. Verification of the facts used (from the record, re-checked, not copied)

### 1.1 Reading-dependent rows

From T3 r1.8 §1a crossing table (lines 86–119) and "VP12 re-check" (b) (lines 228–255), each re-read against the T2 r1.4 statement text:

| row | tier | actor item (T2 r1.4) | S | S′ / S″ | r1.6 (what the stale cells encode) |
|---|---|---|---|---|---|
| R5 | T-A | "every act has α(a) ∈ 𝒜" | crosses | internal CB23 | internal CB23 |
| R6 | T-A | "α(w) ∈ 𝒜" | crosses | internal CB23 | internal CB23 |
| R8 | T-A | "a keeper k(ρ) ∈ 𝒜" | crosses | **internal CB1** | crosses |
| R10 | T-A | "Cap ⊆ 𝒜 × {iss, adp}" | crosses | internal CB23 | internal CB23 |
| R19 | T-A | "(α(a), adp) ∈ Cap", "(α(b), adp) ∈ Cap" | crosses | internal CB23 | internal CB23 |
| R25 | T-B (VP1a) | "(α(a), iss) ∈ Cap" (D-chg) | crosses | internal CB23 | internal CB23 |
| R38 | T-B (VP1a) | D-chg named (rule 1) | crosses | internal CB23 | internal CB23 |

R13, R22, R23 read actors but cross under every reading. R63 (T-C), R71 and R76 (T-D) are reading-dependent but are not hosted in any CB1 / CB23 cell (the cells list T-A and T-B rows only), so they do not enter this sync. Every other T-A and T-B row is unchanged across S, S′ and S″ (re-check (b), last paragraph).

**Hence the cells are wrong exactly where E2b §A1.2 says:** under S for R5, R6, R10, R19, R25, R38; under S′ / S″ for R8. Under S′ / S″ every other entry of the r1.6 cells is correct.

### 1.2 Count check against T3 §1a (c) and E2b §A1.3 / §A1.4

| reading | T-A: CB1 internal / crossing / CB23 internal | T-B: crossing / CB23 internal / none |
|---|---|---|
| S | 1 (R7) / 17 (13 hosted on both sides + R12, R13, R23 at MW1 + R24 at CV4) / 6 (R1, R4, R11, R17, R18, R20) | 7 (R25, R29, R35, R36, R38, R39, R83) / 10 / 1 (R80) |
| S′ / S″ | 2 (R7, R8) / 12 (8 hosted on both sides + MW1 3 + R24) / 10 (R1, R4, R5, R6, R10, R11, R17, R18, R19, R20) | 5 (R29, R35, R36, R39, R83) / 12 / 1 (R80) |

Totals: T-A 24 and T-B 18 under each reading. They match T3 r1.8 §1a (c) and the per-reading host lists of E2b §A1.3 / §A1.4 row for row. **The sync introduces no host list that differs from E2b §A1.3 / §A1.4** (relevant to the E2b §A1.9b reopen row "if the sync result differs from them").

### 1.3 The hosting-keyed R4 clauses (G6): what the existing cells show

The R4 cells are the r1.5 cells plus, in r1.6, explicit clauses for the **T-A** rows that became shared under P-c (not on that side in r1.5). **[r1.1, V1]** T3 states no rule for what a cell must contain, and the r1.5 convention is mixed (below). The r1.6 clauses:
- CB1 gained "for the shared guards R14, R15: Def 1.9; G05, H04, H12 (as CB23)". R14 and R15 were not on r1.5 CB1 (T3 line 323). The listed ids are exactly the T2 r1.4 "rests on" ids of R14 / R15 not already in the CB1 cell (R14: Def 1.9, Def 1.8, G05, H04, H12; R15: Def 1.8, Def 1.9, B06, G05, H12, H04; Def 1.8 and B06 are already in CB1).
- CB23 gained "for the shared rows R8, R9, R16, R21, R22, as CB1". These were r1.5 CB1-only rows (line 323).
- **[r1.1, V1] Specific absences (checked by script; no general rule claimed).** CB23 has hosted R25 and R38 under every reading since r1.5 (as CB2) without Def 3.1, Def 3.2, Prop 4.1, H07, Table 9.4 or Prop 9.6. CB1 hosts R29 without Def 3.1 and Lem 3.6, R35 without Def 3.1, R36 without Def 3.1, Def 3.2 and Table 9.4, and R83 without Def 9.1, Table 9.4 and A7; **H11, also in R83's "rests on" list, is present** in CB1.
- **[r1.1, V1] Counter-instances (the r1.5 convention is mixed; no rule is stated).** Rem 3.1a is in the CB23 cell and, in T2 "rests on", is cited only by the T-B rows R32–R34 (R67 cites it only in its sensitivity column); no CB23 concept cites it; it entered from the r1.5 CB3 cell, which hosted R30–R34 and R37. Rem 9.8 is the same case: its only T-B citer is R31. (Lem 3.7 and Rem 9.7 are in CB23 through C2's T1 sources.) *(r0's "no CB cell carries a T-B-only R4 id" and r1's "T-B hosting has not added the hosted rows' own ids" and its concept-route claim were both wrong and are withdrawn.)*

Applied to the rows newly shared with CB1 under S (T2 r1.4 "rests on", R4 / FROZEN R3 part):
- R5: Def 1.4; Def 1.1. R6: Def 1.5. R10: Def 1.2; Rem 0.1; Obs O5; G07; H19. R19: Def 1.10; Def 1.4; A05; A06; F07.
- Not already in CB1 (A05, A06 are, via "A05–A07"): **Def 1.1, 1.2, 1.4, 1.5, 1.10; Rem 0.1; Obs O5; F07, G07, H19.** All are in the CB23 cell (Def 1.1–1.5, 1.8–1.10; Rem 0.1; Obs O2–O5; F06–F08; G07; H19), so "(as CB23)" is accurate.
- R25 (Def 3.1 D-chg; Def 3.2; Prop 4.1; H07) and R38 (Table 9.4 R-j; Prop 9.6): **none**, following the specific precedent above (CB23 hosts both without these ids). This is a choice among mixed precedents, not the application of a rule (§5 (ii), §6 item 1). *The "missing R4 ids for R25 and R38" of G6 therefore resolve to none under T3's own convention [E]; see §6 item 1.*
- Under S′ / S″, no row becomes newly shared with CB1, and R8 (Def 1.6; Obs O7; F14; A03; F02) is internal to CB1, whose cell already lists all five. In the CB23 pointer clause, R8 is dropped. Its R8-only ids, Obs O7, F14 and F02, are then not brought to CB23 by the pointer (Def 1.6 still comes through R9; A03 is in CB23's own list) [E: reading of "as CB1"; §6 item 2].

### 1.4 Reading sensitivity (from T3 r1.8 §1a VP12 re-check (d), lines 261–274)

| reading | under S | under S′ / S″ |
|---|---|---|
| 𝐑₀ | H-D-asymmetric in the change clauses (D-chg R25 crosses; B-chg R30, B-conf R32, R33 do not); record clauses symmetric (R29; R35, R36) | symmetric (the r1.6 result; M2 disclosure) |
| R-i, R-i′ | asymmetric in the change and the record clauses | asymmetric in the record clauses only (as F-LOG-0265) |
| R-h with R83 | as 𝐑₀ under the crossing criterion; separately the H-B record clause is universal (strength); no weighting, no net direction | as 𝐑₀ (symmetric); the same universal-strength note |
| R-j (R38) | crosses | internal |
| R-e (R26, D-chg without Cap) | symmetric in the change clauses: the S-asymmetry rests on D-chg's Cap condition on the issuing actor (Adopted not read through; §1a (d)) | symmetric |
| R-d (R28, D-st⁺) | as 𝐑₀ (D-chg unchanged) | as 𝐑₀ |

The r1.8 CB1 cell's "crossing symmetric under 𝐑₀, asymmetric under R-i, R-i′; R-h / R83 universal (§1a)" is therefore the S′ / S″ value only.

---

## 2. Inventory of the cells

### 2.1 Stale cells (10; all reading-dependent)

| # | patch | line | cell | why stale (with source) | replacement (summary; exact text in §3) |
|---|---|---|---|---|---|
| 1 | D1 | 48 | §1a CB1 "T-A carried" | lists R8 as shared under every reading (wrong under S′ / S″: internal to CB1); omits R5, R6, R10, R19, shared under S. T3 line 53 (r1.7 note, N5); crossing table lines 86–100; E2b §A1.2 | R8 struck from the reading-independent list; per-reading clause: S → R5, R6, R8, R10, R19 shared; S′ / S″ → R8 internal to CB1, the other four internal to CB23 |
| 2 | D2 | 48 | §1a CB1 "T-B optional policy slots" | omits R25 and R38, which cross under S. Lines 106, 119; T3 line 53; E2b §A1.2, §A1.4 | per-reading clause added; the reading-independent list is correct and kept |
| 3 | D3 | 49 | §1a CB23 "T-A carried" | lists R5, R6, R10, R19 as internal under every reading (wrong under S); lists R8 as shared (wrong under S′ / S″); calls them "participant-dependent", a term of the provisional placement. Lines 53, 86–100 | stale items struck; per-reading clause; the actor sentence restated with VP12 (i) |
| 4 | D4a + D4b | 49 | §1a CB23 "T-B optional policy slots" | lists R25 and R38 as internal under every reading (wrong under S). Lines 106, 119; line 53 | R25, R38 struck from the internal list; per-reading clauses |
| 5 | D5 | 403 | §2 CB1 "T2 ids (tier)" | lists R8 as crossing under every reading; omits R5, R6, R10, R19 (T-A) and R25, R38 (T-B) under S. M5 note (line 432); E2b §A1.2; E3 §7.2 (b) | R8 struck from the reading-independent list; per-reading clause |
| 6 | D6 | 403 | §2 CB1 "R4 / FROZEN R3 ids" | G6: carries no R4 ids for R5, R6, R10, R19 (and R25, R38), shared under S; the R14 / R15 clause is hosting-keyed. E2b §A1.2 G6; §A1.11 (d′); E3 §7.2 (b) | per-reading clause: the R14, R15 clause holds under every reading; S → ids for R5, R6, R10, R19 (§1.3), none for R25, R38; S′ / S″ → none further |
| 7 | D7 | 403 | §2 CB1 "reading sensitivity" | "crossing symmetric under 𝐑₀ …" is the S′ / S″ value only; under S the 𝐑₀ result is H-D-asymmetric (T3 §1a (d); M5 note). E2b §A1.2 | the stale clause struck; per-reading statement (§1.4) |
| 8 | D8 | 404 | §2 CB23 "T2 ids (tier)" | R5, R6, R10, R19 and R25, R38 internal under every reading (wrong under S); R8 crossing under every reading (wrong under S′ / S″). M5 note; E2b §A1.2 | stale items struck; per-reading clause |
| 9 | D9 | 404 | §2 CB23 "R4 / FROZEN R3 ids" | the "shared rows R8, … as CB1" clause is reading-dependent (R8 internal to CB1 under S′ / S″). E2b §A1.2 G6 | the clause struck and restated per reading |
| 10 | D10 | 404 | §2 CB23 "reading sensitivity" | "participant-dependent rows R5, R6, R8, R10, R19, R25 (C1 provisional in CG1; re-check after VP12)": provisional placement superseded by VP12 (i); R38 missing (added by the r1.7 re-check). T3-SYNC r1.3 §4 item 2; M5 note; line 255 | the clause struck; per-reading statement with R38 |

### 2.2 Cells verified and not stale (no edit proposed)

| line | cells | finding |
|---|---|---|
| 48, 49 | §1a working label, T1 concepts | concept membership; not keyed to C1's counting. The T1 ids are already adopted (F-LOG-0275 Act I (1)) |
| 48, 49 | §1a inherited open questions | not designated stale by the record (E2b §A1.2); open (§6 item 3). Concept-keyed (OQ column of T1 r1.6). C1 is a stated exception with its own row (line 51), so its OQ10 is not inherited by CB1 through the spanning; CB23 has OQ10 through C2 (T1 OQ10: "C1, C2"). See §6 item 3 |
| 48, 49 | §1a observability | not designated stale by the record (E2b §A1.2); open (§6 item 3). Attribute-keyed. T3 does not extend it for shared rows (CB23 hosts R8 under S but does not list k) |
| 403, 404 | §2 T1 ids | concept membership; adopted |
| 403, 404 | §2 evidence class | T3 does not extend it for shared rows: CB1 hosts R14, R15 yet its evidence cell has no G05 / H04 [C] entry; CB23 hosts R8 yet has no F14 entry. Not keyed to the participant placement, so not stale. See §6 item 4 |
| 403, 404 | §2 inherited assumptions | same convention: CB23 hosts R21 (IA-T) and R9 (A3, A9) without listing them. Not stale |
| 403, 404 | §2 open questions | not designated stale by the record (E2b §A1.2); open (§6 item 3). Concept-keyed; as the §1a open-question cells |

E3 §7.2 (b) lists the evidence-class, inherited-assumptions and open-questions cells as **missing from the adopted trace**. That is an adoption gap (Act I (1) adopted only the T1 ids and the R4 ids not keyed to hosting), not staleness. They need no T3 edit. They would enter E2b only through act (2) (§4).

---

## 3. The patch (exact text)

Each item gives the anchor (current text, unique in T3 r1.8, on the stated line) and the exact replacement. In the replacements, superseded text is struck through (`~~…~~`), never deleted; new text carries **[r1.9]**. The id `F-LOG-[d′ act id]` and `r[n]` are placeholders, filled when the act is logged.

### D1 · line 48 · §1a CB1 row, column 'T-A carried'

*stale cell #1.*

**Current text (anchor):**

```text
internal: R7. Shared with CB23 (crossing): R2, R3, R8, R9, R22 (constraint rows); R14, R15, R16, R21 (guard form); R24 (meta, hosted at CV4). R12, R13 and R23 also cross, but are carried at MW1
```

**Replacement:**

```text
internal: R7. Shared with CB23 (crossing): R2, R3, ~~R8,~~ R9, R22 (constraint rows); R14, R15, R16, R21 (guard form); R24 (meta, hosted at CV4). R12, R13 and R23 also cross, but are carried at MW1. **[r1.9] Per spanning reading (rule 2a [E]; none selected, F-LOG-0271):** under **S**, R5, R6, R8, R10 and R19 are also shared with CB23 (crossing; they read C1); under **S′ / S″**, R8 is internal to CB1 (with R7), and R5, R6, R10 and R19 are internal to CB23 (not hosted here)
```

### D2 · line 48 · §1a CB1 row, column 'T-B optional policy slots'

*stale cell #2.*

**Current text (anchor):**

```text
all crossing, shared with CB23: R29, R35, R36 (existential record clauses), R83 (R-h universal record clause as optional policy; inactive slot, F-LOG-0254), via VP1d; R39 (mode-fixing convention), via VP2. (R79 and R80 stay VP1d options but are not record-side under the criterion: see CB23)
```

**Replacement:**

```text
all crossing, shared with CB23: R29, R35, R36 (existential record clauses), R83 (R-h universal record clause as optional policy; inactive slot, F-LOG-0254), via VP1d; R39 (mode-fixing convention), via VP2. (R79 and R80 stay VP1d options but are not record-side under the criterion: see CB23). **[r1.9] Per spanning reading (rule 2a [E]; none selected, F-LOG-0271):** under **S**, R25 (D-chg, 𝐑₀) and R38 (R-j), via VP1a, are also crossing, shared with CB23; under **S′ / S″** they are internal to CB23 (not hosted here). All stay inactive slots
```

### D3 · line 49 · §1a CB23 row, column 'T-A carried'

*stale cell #3.*

**Current text (anchor):**

```text
internal: R1, R4, R5, R6, R10, R18, R19, R20; R11, R17 (guard form). Shared with CB1 (crossing): R2, R3, R8, R9, R22 (constraint rows); R14, R15, R16, R21 (guard form). R12, R13 and R23 at MW1. R5, R6, R8, R10 and **[r1.6 L1]** R19 read the actor set via C1 (participant-dependent; R25 likewise on the T-B side)
```

**Replacement:**

```text
internal: R1, R4, ~~R5, R6, R10,~~ R18, ~~R19,~~ R20; R11, R17 (guard form). Shared with CB1 (crossing): R2, R3, ~~R8,~~ R9, R22 (constraint rows); R14, R15, R16, R21 (guard form). R12, R13 and R23 at MW1. ~~R5, R6, R8, R10 and **[r1.6 L1]** R19 read the actor set via C1 (participant-dependent; R25 likewise on the T-B side)~~ **[r1.9] Per spanning reading (rule 2a [E]; none selected, F-LOG-0271):** R5, R6, R8, R10 and R19 read the actor set via C1, which spans CB1 and CB23 (VP12 (i), F-LOG-0268). Under **S**, R5, R6, R8, R10 and R19 are shared with CB1 (crossing). Under **S′ / S″**, R5, R6, R10 and R19 are internal to CB23, and R8 is internal to CB1 (not hosted here). R25 and R38 likewise on the T-B side
```

### D4a · line 49 · §1a CB23 row, column 'T-B optional policy slots' (first clause)

*stale cell #4 (part 1).*

**Current text (anchor):**

```text
internal: R25, R26, R27, R28, R38 (H-D-side change and status clauses, via VP1a, VP1b);
```

**Replacement:**

```text
internal: ~~R25,~~ R26, R27, R28, ~~R38~~ (H-D-side change and status clauses, via VP1a, VP1b); **[r1.9]** R25 (D-chg) and R38 (R-j), via VP1a: internal here under **S′ / S″** only (rule 2a [E]; none selected, F-LOG-0271; crossing under S, below);
```

### D4b · line 49 · §1a CB23 row, column 'T-B optional policy slots' (last clause)

*stale cell #4 (part 2; anchor includes the cell-closing ' |' for uniqueness).*

**Current text (anchor):**

```text
Shared with CB1 (crossing): R29, R35, R36, R83 (VP1d); R39 (VP2) |
```

**Replacement:**

```text
Shared with CB1 (crossing): R29, R35, R36, R83 (VP1d); R39 (VP2). **[r1.9]** Under **S** also R25 and R38 (VP1a), crossing, shared with CB1 (rule 2a [E]; none selected, F-LOG-0271). All stay inactive slots |
```

### D5 · line 403 · §2 CB1 row, column 'T2 ids (tier)'

*stale cell #5.*

**Current text (anchor):**

```text
R2, R3, R7, R8, R9, R14, R15, R16, R21, R22, R24 (T-A; all except R7 cross to CB23, shared); R29, R35, R36, R39, R83 (T-B slot; all crossing, shared with CB23). R79 and R80 (VP1d) are not record-side under the §1a criterion (CB23 / no clause); R12, R13, R23 at MW1
```

**Replacement:**

```text
R2, R3, R7, ~~R8,~~ R9, R14, R15, R16, R21, R22, R24 (T-A; all except R7 cross to CB23, shared); R29, R35, R36, R39, R83 (T-B slot; all crossing, shared with CB23). R79 and R80 (VP1d) are not record-side under the §1a criterion (CB23 / no clause); R12, R13, R23 at MW1. **[r1.9] Per spanning reading (rule 2a [E]; none selected, F-LOG-0271):** under **S**, also R5, R6, R8, R10, R19 (T-A, crossing, shared with CB23) and R25, R38 (T-B slot, VP1a, crossing, shared with CB23); under **S′ / S″**, also R8 (T-A, internal to CB1), and R5, R6, R10, R19, R25, R38 are internal to CB23 (not hosted here)
```

### D6 · line 403 · §2 CB1 row, column 'R4 / FROZEN R3 ids'

*stale cell #6 (anchor: the cell's tail, with the closing ' |').*

**Current text (anchor):**

```text
H11 (as r1.5); for the shared guards R14, R15: Def 1.9; G05, H04, H12 (as CB23) |
```

**Replacement:**

```text
H11 (as r1.5); for the shared guards R14, R15: Def 1.9; G05, H04, H12 (as CB23). **[r1.9] Hosting-keyed clauses per spanning reading (rule 2a [E]; none selected, F-LOG-0271; ids are the T2 r1.4 "rests on" ids of the shared rows not already listed, as CB23):** the R14, R15 clause holds under S, S′ and S″ (both cross under every reading). Under **S** only, for the shared rows R5, R6, R10, R19: Def 1.1, 1.2, 1.4, 1.5, 1.10; Rem 0.1; Obs O5; F07, G07, H19 (as CB23); for the shared T-B slots R25, R38: none, as CB23, which has hosted R25 and R38 under every reading since r1.5 (as CB2) without Def 3.1, Def 3.2, Prop 4.1, H07, Table 9.4 or Prop 9.6 (likewise, CB1 hosts R29, R35, R36 and R83 without Def 3.1, Def 3.2, Lem 3.6, Table 9.4 or Def 9.1). Under **S′ / S″**: no further clause (R8 is internal to CB1, and its ids are listed above) |
```

### D7 · line 403 · §2 CB1 row, column 'reading sensitivity'

*stale cell #7 (anchor: the cell's tail).*

**Current text (anchor):**

```text
record clauses reading-relative (R-c, R-f, R-i, R-i′); crossing symmetric under 𝐑₀, asymmetric under R-i, R-i′; R-h / R83 universal (§1a) |
```

**Replacement:**

```text
record clauses reading-relative (R-c, R-f, R-i, R-i′); ~~crossing symmetric under 𝐑₀, asymmetric under R-i, R-i′; R-h / R83 universal (§1a)~~ **[r1.9] crossing per spanning reading (rule 2a [E]; none selected, F-LOG-0271; §1a VP12 re-check (d)):** under **S**, H-D-asymmetric at 𝐑₀ in the change clauses (D-chg R25 crosses; B-chg R30 and B-conf R32, R33 do not), record clauses symmetric (R29; R35, R36); under R-i, R-i′ asymmetric in both the change and the record clauses; R-j (R38) crosses. Under R-e (R26, D-chg without Cap) symmetric under every reading: the S-asymmetry rests on D-chg's Cap condition (under rule 2's convention, Adopted not read through; §1a (d)); R-d (R28) as 𝐑₀. Under **S′ / S″**, symmetric under 𝐑₀ (the r1.6 result; M2 disclosure, §1a re-check (a)); asymmetric under R-i, R-i′ in the record clauses only; R-j (R38) internal. Under R-h with R83 active: as 𝐑₀ under the crossing criterion, for each reading; separately, the H-B record clause is universal (strength, R-h / R83 note); no weighting, no net direction asserted. The T2 hosts of R5, R6, R8, R10, R19, R25 and R38 depend on the spanning reading (T2 ids cell). The asymmetry under S is documented, not accepted |
```

### D8 · line 404 · §2 CB23 row, column 'T2 ids (tier)'

*stale cell #8.*

**Current text (anchor):**

```text
R1, R4, R5, R6, R10, R11, R17, R18, R19, R20 (T-A, internal); R2, R3, R8, R9, R14, R15, R16, R21, R22 (T-A, crossing, shared with CB1); R25–R28, R30–R34, R37, R38, R79 (T-B slot, internal); R29, R35, R36, R39, R83 (T-B slot, crossing, shared with CB1); R80 (T-B, VP1d, no clause)
```

**Replacement:**

```text
R1, R4, ~~R5, R6, R10,~~ R11, R17, R18, ~~R19,~~ R20 (T-A, internal); R2, R3, ~~R8,~~ R9, R14, R15, R16, R21, R22 (T-A, crossing, shared with CB1); ~~R25–R28,~~ R26–R28, R30–R34, R37, ~~R38,~~ R79 (T-B slot, internal); R29, R35, R36, R39, R83 (T-B slot, crossing, shared with CB1); R80 (T-B, VP1d, no clause). **[r1.9] Per spanning reading (rule 2a [E]; none selected, F-LOG-0271):** under **S**, also R5, R6, R8, R10, R19 (T-A, crossing, shared with CB1) and R25, R38 (T-B slot, VP1a, crossing, shared with CB1); under **S′ / S″**, also R5, R6, R10, R19 (T-A, internal) and R25, R38 (T-B slot, VP1a, internal), while R8 is internal to CB1 (not hosted here)
```

### D9 · line 404 · §2 CB23 row, column 'R4 / FROZEN R3 ids'

*stale cell #9 (anchor: the cell's tail).*

**Current text (anchor):**

```text
H19 (union of r1.5 CB2 and CB3); for the shared rows R8, R9, R16, R21, R22, as CB1 |
```

**Replacement:**

```text
H19 (union of r1.5 CB2 and CB3); ~~for the shared rows R8, R9, R16, R21, R22, as CB1~~ **[r1.9] hosting-keyed clause per spanning reading (rule 2a [E]; none selected, F-LOG-0271):** for the shared rows, as CB1: under **S**, R8, R9, R16, R21, R22; under **S′ / S″**, R9, R16, R21, R22 (R8 is internal to CB1 and is not hosted here). Under S, the further shared rows R5, R6, R10 and R19 take their ids from this cell's own list, and the shared T-B slots R25 and R38 carry none (as CB1) |
```

### D10 · line 404 · §2 CB23 row, column 'reading sensitivity'

*stale cell #10 (anchor: the cell's tail).*

**Current text (anchor):**

```text
participant-dependent rows R5, R6, R8, R10, **[r1.6 L1]** R19, R25 (C1 provisional in CG1; re-check after VP12) |
```

**Replacement:**

```text
~~participant-dependent rows R5, R6, R8, R10, **[r1.6 L1]** R19, R25 (C1 provisional in CG1; re-check after VP12)~~ **[r1.9] reading-dependent hosts (VP12 (i), F-LOG-0268: C1 spans CB1 and CB23; rule 2a [E]; none selected, F-LOG-0271):** R5, R6, R10, R19, R25 and R38 cross under S and are internal under S′ / S″; R8 crosses under S and is internal to CB1 (not hosted here) under S′ / S″. The crossing result at 𝐑₀, R-i, R-i′, R-j, R-h, R-e and R-d per reading: as the CB1 row. The asymmetry under S is documented, not accepted |
```

### S1 · line 1 · title

*title version marker (outside the literal scope; strikable, §5 (i)).*

**Current text (anchor):**

```text
# R7 · T3: candidate domain model with traceability (draft r1.8)
```

**Replacement:**

```text
# R7 · T3: candidate domain model with traceability (draft r1.9)
```

### S2 · line 3 · status line (opening)

*status line: new r1.9 head; the r1.8 status follows, kept as history.*

**Current text (anchor):**

```text
**Status:** r1.8, post-closure sync amendment under F-LOG-0273;
```

**Replacement:**

```text
**Status:** r1.9, post-closure sync amendment under F-LOG-[d′ act id] (open item (d′): F-LOG-0275, E2b §A1.11; F-LOG-0284); nothing adopted; E2b not amended; no VP decided; no spanning rule selected; asymmetry documented, not accepted (F-LOG-0271). **[r1.9]** Applies exactly the T3 edits authorized by F-LOG-[d′ act id] (`R7/T3-DPRIME-SYNC-PROPOSAL.md` r[n]): the §1a CB1 and CB23 T-A and T-B cells and the §2 CB1 and CB23 "T2 ids", "R4 / FROZEN R3 ids" and "reading sensitivity" cells are restated per spanning reading (S, S′, S″; none selected) under VP14 = P-c (F-LOG-0265) and VP12 = (i) (F-LOG-0268), from the r1.7 crossing table and VP12 re-check (b)–(d); the CB1 R4 cell gains the hosting-keyed clause for the rows shared only under S. Superseded text is struck through, not deleted. The r1.7 §1a note on the CB1 and CB23 cells and the §2 M5 note are kept as history; the cells they describe are now restated. No other T3 content is changed; counts are unchanged. Adoption of the synced §2 rows needs a separate E2b amendment by its own act (not made as of r1.9). It authorizes no E4 or E5. **[r1.8 status, kept as history]** r1.8, post-closure sync amendment under F-LOG-0273;
```

### S3 · line 610 · §7 change table: new r1.9 row appended after the r1.8 row

*§7: a new r1.9 row appended after the r1.8 row (the anchor is the whole r1.8 row, which stays unchanged).*

**Current text (anchor):**

```text
| r1.8 | **post-closure sync amendment under F-LOG-0273** (`R7/T3-SYNC-PROPOSAL.md` r1.2); closes F-LOG-0271 open item (d) | Title and status line → r1.8. **§1b:** R21 added to the guard list (c in CG4 / CV-text against σ in CG2 and f in CG3; F-LOG-0267); the ten-row constraint list is unchanged (as quoted by F-LOG-0269). **§1a relations:** "keeper typing (R8, participant-dependent)" struck; restated as a relation under S, internal to CB1 under S′ / S″, none selected (F-LOG-0271), with a pointer to the other S-only T-A crossings (R5, R6, R10, R19). **§4 VP11:** DECIDED (ii), F-LOG-0269 (decision, options, held-meanwhile and decide-by cells); side note: "may straddle … (provisional; re-check after VP12)" struck, CG1 stated to straddle the cut through C1 under VP12 (i), with its counting under S, S′, S″ unselected; the r1.6 R21-gap statement struck and marked closed, R11 noted as open. **§4 VP12:** note that the VP11 side note and §1b guard list are synced in r1.8. **§6:** item 1 "four" → "at least five" guard rows (R21), the list marked non-exhaustive, R11 stated as an open question, implication / meta rows noted as outside the §1b lists, plus VP11 status; item 6 VP11 status; F8 list marked applied and its header "Two" → "Three" (kept as history). Superseded text struck, not deleted. No CG, CV, CE, CS or MW content changed except the §1b guard list. Counts unchanged (elements 28; VPs 19; §1a crossing totals S 46 / 29 / 1 / 3 / 4, S′ / S″ 36 / 38 / 2 / 3 / 4). Nothing adopted; no VP decided; no spanning rule selected; E2b not amended; no O3 preparation, E3, E3b, E4 or E5 authorized |
```

**Replacement:**

```text
| r1.8 | **post-closure sync amendment under F-LOG-0273** (`R7/T3-SYNC-PROPOSAL.md` r1.2); closes F-LOG-0271 open item (d) | Title and status line → r1.8. **§1b:** R21 added to the guard list (c in CG4 / CV-text against σ in CG2 and f in CG3; F-LOG-0267); the ten-row constraint list is unchanged (as quoted by F-LOG-0269). **§1a relations:** "keeper typing (R8, participant-dependent)" struck; restated as a relation under S, internal to CB1 under S′ / S″, none selected (F-LOG-0271), with a pointer to the other S-only T-A crossings (R5, R6, R10, R19). **§4 VP11:** DECIDED (ii), F-LOG-0269 (decision, options, held-meanwhile and decide-by cells); side note: "may straddle … (provisional; re-check after VP12)" struck, CG1 stated to straddle the cut through C1 under VP12 (i), with its counting under S, S′, S″ unselected; the r1.6 R21-gap statement struck and marked closed, R11 noted as open. **§4 VP12:** note that the VP11 side note and §1b guard list are synced in r1.8. **§6:** item 1 "four" → "at least five" guard rows (R21), the list marked non-exhaustive, R11 stated as an open question, implication / meta rows noted as outside the §1b lists, plus VP11 status; item 6 VP11 status; F8 list marked applied and its header "Two" → "Three" (kept as history). Superseded text struck, not deleted. No CG, CV, CE, CS or MW content changed except the §1b guard list. Counts unchanged (elements 28; VPs 19; §1a crossing totals S 46 / 29 / 1 / 3 / 4, S′ / S″ 36 / 38 / 2 / 3 / 4). Nothing adopted; no VP decided; no spanning rule selected; E2b not amended; no O3 preparation, E3, E3b, E4 or E5 authorized |
| r1.9 | **post-closure sync amendment under F-LOG-[d′ act id]** (`R7/T3-DPRIME-SYNC-PROPOSAL.md` r[n]); the T3 sync of open item (d′) (F-LOG-0275, E2b §A1.11; F-LOG-0284) | Title and status line → r1.9. **§1a CB table:** CB1 "T-A carried" (R8 struck from the reading-independent list; per-reading clause: under S, R5, R6, R8, R10, R19 shared; under S′ / S″, R8 internal to CB1); CB1 "T-B" (per-reading clause: R25, R38 shared under S); CB23 "T-A carried" (R5, R6, R10, R19 and R8 struck from the reading-independent lists; the "participant-dependent" sentence struck and restated per reading); CB23 "T-B" (R25, R38 struck from the reading-independent internal list; per-reading clauses). **§2:** CB1 and CB23 "T2 ids" (restated per reading); CB1 "R4 / FROZEN R3 ids" (the R14, R15 clause labelled reading-independent; new clause for R5, R6, R10, R19 under S: Def 1.1, 1.2, 1.4, 1.5, 1.10; Rem 0.1; Obs O5; F07, G07, H19; none for the T-B slots R25, R38); CB23 "R4 / FROZEN R3 ids" (the "shared rows … as CB1" clause restated per reading: R8 under S only); CB1 "reading sensitivity" ("crossing symmetric under 𝐑₀ …" struck and restated per reading); CB23 "reading sensitivity" ("participant-dependent … C1 provisional in CG1; re-check after VP12" struck and restated per reading, with R38). Not edited (not designated stale; open-question / observability cells open, proposal §6 item 3): the CB1 and CB23 working-label, T1, open-question and observability cells (§1a) and the T1, evidence-class, inherited-assumption and open-question cells (§2). Source: the r1.7 §1a crossing table and VP12 re-check (b)–(d), T2 r1.4 "rests on" column; matches E2b §A1.3 / §A1.4. Superseded text struck, not deleted. Counts unchanged (elements 28; VPs 19; crossing totals S 46 / 29 / 1 / 3 / 4, S′ / S″ 36 / 38 / 2 / 3 / 4). Nothing adopted; no VP decided; no spanning rule selected; asymmetry not accepted; E2b not amended; no E4 or E5 authorized |
```


**Not edited (scope):** everything else in T3, including the participant row (line 51), the r1.7 §1a note (line 53) and the M5 note (line 432), which are kept as history and pointed to by the new status line. **[r1, F6] Stale text outside the declared cells (disclosed, not proposed; each would need its own authorization):** (a) the participant row (line 51) gives "the full participant-dependent list is in the §1a sensitivity (R5, R6, R8, R10, R19, R25, R63, R76)", which lacks R38 and R71 (added by the r1.7 re-check, line 255) and uses the superseded term "participant-dependent"; (b) after r1.9, the line-53 note ("not re-written, to keep the r1.6 derivation visible"; "the cells are superseded for these rows only") and the line-432 M5 note ("not rewritten") become inaccurate, since the cells are then restated; the r1.9 status line records this, but the notes themselves are not annotated. Also unchanged: the crossing table, counts, relations table, §1b, §3, §4, §6 and every CG, CV, CE, CS and MW row. The **title** (S1) is outside the literal "cells, status line and §7 row" scope. It is listed separately in the act (§5) so that the human can strike it; prior syncs changed it with the status line (F-LOG-0273: "Title and status line → r1.8").

---

## 4. What the later E2b amendment would need to adopt (pointer only; not drafted)

F-LOG-0284 act (2): "an E2b amendment adopting the whole synced CB1/CB23 §2 rows." After r1.9, those rows would be:
- **CB1 §2 row (T3 r1.9 line 403):** T1 ids (already adopted); "T2 ids (tier)" (D5); "R4 / FROZEN R3 ids", including both hosting-keyed clauses (D6); "evidence class", "inherited assumptions" and "open questions" (unchanged, §2.2); "reading sensitivity" (D7).
- **CB23 §2 row (line 404):** the same columns, with D8, D9 and D10.

Points that the later proposal would have to handle (each one is listed, not resolved here):
- **Per-reading adoption.** D5–D10 hold values under S and under S′ / S″. Per F-LOG-0284 ("The reading-dependent cells are adopted per reading, with no reading selected"), they would be adopted per reading, with none selected.
- **The per-reading host lists are currently unratified.** E2b §A1.10 lists "the per-reading host lists (§A1.3, §A1.4), recorded as a report of T3 [E], not ratified". The later proposal must say whether adoption ratifies them or keeps them as a report.
- **Further unratified items the synced rows would carry:** the K-c criterion and rule 2a (§A1.10); the G6 restriction (§A1.10) would be superseded or kept; the §1.3 hosting convention, and its "none" result for R25 and R38, is [E] (§6 items 1, 2).
- **Text to supersede in E2b:** Act I (1)'s pin to "`R7/T3-CANDIDATE-MODEL.md` r1.7 §2 traces", "restricted to the T1 ids and the R4 / FROZEN R3 ids not keyed to hosting", and "The stale T3 r1.7 CB1 / CB23 cells are not adopted"; §A1.2 (G6, "Not adopted: the stale T3 cells"); §A1.9 D6; the §A1.9b (d′) reopen row; §A1.11 (d′). The T3 version note would need to say that the pinned CB1 / CB23 trace source moves from r1.7 to r1.9.
- **[r1, F4] Pre-existing gaps in the CB1 R4 cell** (§6 item 5; deferred, not stale under the current record). Adopting "whole" rows would adopt the cell as it is; act (2) must say whether that is acceptable or whether a rule for what a cell must contain is needed first (a modelling decision).
- **§1a cells.** F-LOG-0284 names the "§2 rows". Whether the synced §1a cells (D1–D4) are also adopted is for that proposal to state. The P-c relations table and the 83-row crossing table stay "T3 [E] analysis" (§A1.2) unless that act says otherwise.
- After both acts, the ⚠E3b outputs would be re-assessed (F-LOG-0284 option (i)), by their own act.

---

## 5. Draft Human ARB act (Approve / Return / Reject; no default)

**Approve:**
> "I, Human ARB, authorize applying to `R7/T3-CANDIDATE-MODEL.md` r1.8 exactly the edits of `R7/T3-DPRIME-SYNC-PROPOSAL.md` r[n] §3, items D1, D2, D3, D4a, D4b, D5, D6, D7, D8, D9, D10, S2 and S3 [and S1, the title's version marker — strike if not authorized], as a **post-closure amendment** (T3 r1.8 → r1.9), being the T3 sync of open item (d′) (E2b §A1.11; F-LOG-0284 act (1)). Superseded text is struck through, not deleted. The application is to be followed by a **fresh independent review** of the applied text against this proposal.
>
> The reading-dependent cells are stated under S and under S′ / S″; **no spanning rule is selected**. It decides no variation point, accepts no asymmetry, and does not withdraw F-LOG-0265's premise. It **adopts nothing**: the synced cells stay '[E] candidate, not adopted' in T3. It **does not amend E2b**; the adoption of the synced CB1 / CB23 §2 rows needs a separate E2b amendment by its own act. It does not accept the ⚠E3b outputs. It authorizes no evidence collection and no implementation, and adopts no canonical terms. It is decided without reference to any VP1a–d outcome (N1). **It does not authorize E4 (R8) or E5.**"

**Return** (for revision, stating what to change):
> "I return `R7/T3-DPRIME-SYNC-PROPOSAL.md` r[n] for revision: [items / points]. Nothing is applied; T3 stays at r1.8; (d′) stays open."

**Reject:**
> "I reject `R7/T3-DPRIME-SYNC-PROPOSAL.md` r[n]. Nothing is applied; T3 stays at r1.8; (d′) stays open, and the ⚠E3b outputs stay not accepted (F-LOG-0284)."

Sub-choices the human may make on Approve (each one is optional):
- **(i)** strike S1 (title);
- **(ii)** for D6 / D9, require the R25 / R38 ids to be listed (Def 3.1, Def 3.2, Prop 4.1, H07; Table 9.4, Prop 9.6) instead of "none". **[r1, F9] In favour:** it matches the literal wording of E2b r1.2 §A1.2 G6 ("the CB1 cell carries no R4 ids for R5, R6, R10, R19, R25 and R38") and §A1.11 ("including the missing R4 ids under S"), the amendment proposal r2.2 G6, and E3 r1.1 §7.2 (b) ("the R4 ids for R5, R6, R10, R19, R25 and R38 under S"), all of which name R25 and R38. **[r1.1, V1] Also in favour:** the r1.5 convention is mixed: CB23 carries Rem 3.1a (cited in "rests on" only by R32–R34) and Rem 9.8 (only T-B citer R31), and CB1 carries H11 (in R83's list), so T-B ids have entered CB cells before. **Against:** the specific absences (§1.3): CB23 has hosted R25 and R38 since r1.5 without these ids, and CB1 hosts R29 (without Def 3.1, Lem 3.6), R35 (Def 3.1), R36 (Def 3.1, Def 3.2, Table 9.4) and R83 (Def 9.1, Table 9.4, A7); listing them for CB1 alone would make the two sides inconsistent, and consistency would need the same for every hosted T-B row, outside this scope. No default.

---

## 6. Where the sources are insufficient (disclosed; [E])

1. **R4 ids for R25 and R38 under S (G6).** T2 r1.4 gives their "rests on" ids, but T3 has no stated rule for which ids a CB cell carries. The answer "none" follows the specific precedent in §1.3 (CB23 hosts R25 and R38 without their ids; CB1 hosts R29, R35, R36 and R83 without Def 3.1, Def 3.2, Lem 3.6, Table 9.4, Def 9.1 and A7, though with H11), not a rule. **[r1.1, V1]** The r1.5 convention is mixed (Rem 3.1a via R32–R34 and Rem 9.8 via R31 are in CB23); no rule is stated. G6's wording ("carries no R4 ids for … R25 and R38") does not say whether it expected ids. The inference is [E]; sub-choice (ii) in §5 offers the alternative.
2. **Meaning of "as CB1" in the CB23 cell.** T3 does not define the pointer. This proposal reads it as "the T2 r1.4 'rests on' ids of the named rows" [E]. On that reading, dropping R8 under S′ / S″ removes Obs O7, F14 and F02 from what the pointer brings.
3. **OQ10 on CB1 under VP12 (i).** C1 spans CB1 (VP12 (i), reading-independent), and T1 OQ10 cites C1. CB1's open-question cells do not carry OQ10, because C1 is a stated exception with its own row (the participant row carries OQ10, OQ5). Whether the spanning makes C1's open questions inherited by CB1 is not decided by any source. Not proposed.
4. **Evidence-class and inherited-assumption cells.** No source states whether these are keyed to concepts or to hosted rows. The r1.6 cells behave as concept-keyed (§2.2). If they were meant to be row-keyed, they would be incomplete under every reading for R14, R15, R8, R9, R21, which is a pre-existing r1.6 matter and not VP12-caused. Not proposed.
5. **Pre-existing gaps, not VP12-caused (recorded, deferred; inventory not exhaustive).** R24 crosses under every reading but is named only in the CB1 cells (§1a "hosted at CV4"; §2 T2 ids), not in the CB23 cells. **[r1, F4]** The CB1 R4 cell lacks ids cited by its own rows or concepts: Rem 1.9a and Prop 5.4(b) (R3; Rem 1.9a also C18); Def 1.7a (R22); F08 (R16; C23); Rem 5.2a, Def 5.1, Lem 4.0a (R24; Def 5.1 also C18, C27; Rem 5.2a also C27); Lem 4.2 (C17); Obs O8 (C24). Def 1.9, G05 and H04 reach CB1 only through the R14 / R15 clause, which Act I (1) excluded. These are deferred: they are not stale under the current record, and fixing them would need a rule for what a cell must contain, which is a modelling decision. Act (2) must handle them (§4).
6. **S-only borderlines** (T3 §1a (e): R27, R28 if Adopted were read through; R56, R60, R61, R62) are not applied, as in T3. If the human ever applied one, D2, D4, D5, D7, D8 and D10 would change for R27 and R28 (and D7 for the asymmetry statement).

---

## 7. Self-check

| check | result |
|---|---|
| every stale CB1 / CB23 cell inventoried, with line, current text, reason with source, and replacement | yes: 10 cells (§2.1; texts in §3). The 16 other non-id CB1 / CB23 cells were verified and are not stale (§2.2) |
| per reading, no selection | yes: D1–D10 each state S and S′ / S″; "none selected, F-LOG-0271" in every [r1.9] clause; S′ and S″ share values because they agree on all 83 rows (rule 2a) |
| facts verified, not copied | yes (F1 corrected a wrong generalization in r0): host lists against the crossing table, the re-check (b) and the T2 r1.4 statements (§1.1); counts against §1a (c) and E2b §A1.3 / §A1.4 (§1.2); R4 ids against T2 r1.4 "rests on" and the CB cells, by script (§1.3); sensitivity against re-check (d) (§1.4) |
| anchors unique | yes: each of the 14 anchors occurs exactly once in T3 r1.8, on its stated line (script) |
| no other T3 content changed | yes, **with one exception:** S1 replaces "r1.8" in the title (outside the literal scope; strikable, §5 (i)). Otherwise only lines 3, 48, 49, 403, 404 and one appended §7 row (script). Stale text outside the cells is disclosed, not edited (§3 "Not edited", F6) |
| superseded text struck, never deleted | yes: token-preservation check by script |
| standing qualifier kept verbatim | yes: occurrences in T3 unchanged (3); quoted verbatim here |
| no VP decided; no spanning rule selected; asymmetry not accepted; F-LOG-0265 premise not withdrawn | yes |
| nothing adopted; E2b not amended; E2b act only pointed to | yes: §4 is a pointer; no act text for it |
| no E4 / E5 authorization; no ⚠E3b output accepted | yes (§5) |
| counts unchanged | yes: elements 28; VPs 19; crossing totals S 46 / 29 / 1 / 3 / 4, S′ / S″ 36 / 38 / 2 / 3 / 4 |
| reviewed | r0: fresh independent review, PASS_WITH_LIMITATIONS (F1–F9, all applied). **r1 not re-reviewed** |
| only this file created | yes; no commit |

---

## 8. r1 changes (fresh review of r0: PASS_WITH_LIMITATIONS; 2 material, 7 minor; r1 not re-reviewed)

| finding | class | fix in r1 |
|---|---|---|
| F1 | MATERIAL | **[superseded in part by V1: the r1 general sentence and concept-route claim were themselves wrong]** The false general claim "no CB cell carries a T-B-only R4 id" is withdrawn from D6 and §1.3. The R4 cells are stated as not built from T2 ids (Lem 3.7, Rem 9.7 and Rem 3.1a reach CB23 through C2, C10 and C28). The "none" for R25 / R38 now rests on the specific evidence: CB23 has hosted R25 and R38 since r1.5 (as CB2) without Def 3.1, Def 3.2, Prop 4.1, H07, Table 9.4 or Prop 9.6; CB1 hosts R29, R35, R36 and R83 without Def 3.1, Def 3.2, Lem 3.6, Table 9.4 or Def 9.1. §6 item 1 updated. Patch re-verified by script |
| F2 | MATERIAL | The Authority paragraph cites F-LOG-0285 ("Both, contexts first"; not an authorization) instead of "F-LOG-0284 next option (i)"; sources list extended |
| F3 | minor | D7 and §1.4 add R-e (R26: symmetric under every reading; the S-asymmetry rests on D-chg's Cap condition, §1a (d)) and R-d (R28: as 𝐑₀) |
| F4 | minor | §6 item 5 lists the pre-existing CB1 R4 gaps (Rem 1.9a, Prop 5.4(b); Def 1.7a; F08; Rem 5.2a, Def 5.1, Lem 4.0a; Lem 4.2; Obs O8; Def 1.9, G05, H04 only via the excluded clause), marked non-exhaustive and deferred; §4 gains the point that act (2) must handle them |
| F5 | minor | §6 item 6 also lists D5, D7 and D10 |
| F6 | minor | §3 "Not edited" discloses the stale participant-row list (line 51: lacks R38, R71; "participant-dependent") and that the line-53 and line-432 notes become inaccurate after r1.9 |
| F7 | minor | §2.2: the CB1 / CB23 open-question and observability cells are "not designated stale by the record (E2b §A1.2); open (§6 item 3)" |
| F8 | minor | §7 states the S1 title exception; the §4 quote is now "`R7/T3-CANDIDATE-MODEL.md` r1.7 §2 traces" |
| F9 | minor | §5 sub-choice (ii) is two-sided: for (E2b G6, §A1.11, amendment G6, E3 §7.2 (b) all name R25, R38); against (the F1 evidence); no default |

**r1.1 changes (verification of r1: PASS_WITH_LIMITATIONS; r1.1 fixes not re-reviewed)**

| finding | class | fix in r1.1 |
|---|---|---|
| V1 | MATERIAL | §1.3: the r1 general sentence ("T-B hosting has not added the hosted rows' own ids") and the C2 / C10 / C28 route claim are dropped (C28 is a CB4 concept; C10 does not cite Lem 3.7; no CB23 concept cites Rem 3.1a). Only specific absences are stated, with the exact absent ids per CB1 row (R29: Def 3.1, Lem 3.6; R35: Def 3.1; R36: Def 3.1, Def 3.2, Table 9.4; R83: Def 9.1, Table 9.4, A7), and H11 noted as present. Rem 3.1a (via R32–R34, from r1.5 CB3) and Rem 9.8 (via R31) are recorded as counter-instances: "the r1.5 convention is mixed; no rule is stated". The counter-evidence is added to the "in favour" side of §5 (ii); §6 item 1 updated. The D6 patch text is unchanged |
| V2 | minor | S3 §7 row: "Verified and not edited" → "Not edited (not designated stale; open-question / observability cells open, proposal §6 item 3)" |
| V3 | minor | D7: the R-e clause adds "(under rule 2's convention, Adopted not read through)" |
| V4 | minor | D4a: the strike-through now renders ("R26, R27, R28, ~~R38~~"); token-preservation check still passes |
| V5 | minor | D10: the pointer "as the CB1 row" now covers R-e and R-d |

