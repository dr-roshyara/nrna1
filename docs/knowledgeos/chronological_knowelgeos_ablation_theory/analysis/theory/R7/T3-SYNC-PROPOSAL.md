# R7 · T3 sync proposal: r1.7 → r1.8 (R21 in §1b; F8 deferred items; VP11 status)

**Status: PROPOSED, not applied; draft r1.3 (2026-10-11).** r1.2 and r1.3 are coordination syncs only (U1, U2, §7); they change no patch text. r1.3 follows a final cross-check (PASS_WITH_LIMITATIONS) and fixes the order of application: C (this act) first, then A (E2b amendment r2.2), then B (register/ARB sync r2.2) last. r0 was reviewed by a fresh independent review: PASS_WITH_LIMITATIONS, findings Q1–Q10, all applied in r1 (change table in §6). r1 was reviewed by a second fresh independent review: PASS_WITH_LIMITATIONS, with no blockers and no material findings. Its seven minor findings T1–T7 are applied in r1.1 (change table in §7) and **were not re-reviewed**. Worker draft. Nothing here is applied to `R7/T3-CANDIDATE-MODEL.md`, nothing is adopted, no VP is decided, no spanning rule is selected, and E2b is not amended. Applying the patch below needs the Human ARB act in §3. Until that act exists, T3 stays at r1.7.

**Standing qualifier:** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Base text.** `R7/T3-CANDIDATE-MODEL.md` **r1.7** as committed in `6e0407db7` (F-LOG-0270). It was verified unmodified in the working tree when this draft was made, and it has 609 lines. All line numbers below refer to that text. Every "current text" quotation was checked by exact-string search against that file. Each anchor is unique in the file unless noted.

**Sources read (authoritative first):**
- `F-GOVERNANCE-LOG.md` F-LOG-0266 to F-LOG-0271, including the appended blocks to F-LOG-0268 and F-LOG-0271;
- T3 r1.7;
- `R7/T2-RULE-CANDIDATES.md` r1.4 (R11, R14–R17, R21);
- `R7/T1-CONCEPT-CANDIDATES.md` r1.6 (C6, C12, C15);
- for the §3 coordination note only: `R7/E2b-AMENDMENT-1-PROPOSAL.md` and `R7/REGISTER-ARB-SYNC-PROPOSAL.md`.

---

## 1. Verification before drafting

### 1.1 R21 is a guard-form row that crosses groupings (T2 r1.4, T1 r1.6, T3 r1.7)

- **T2 r1.4, R21** (line 73) is a guard-form T-A row:
  - its statement is "**Texts are texts.** … No axiom ties the text c to σ or f (A5) …";
  - its tier is "**T-A (guard form; modelling choice)**";
  - its concept trace is "C15".
- **The items whose tie it denies** are c (text) on one side and σ and f on the other. By T1 r1.6:
  - c = C15 ("Record text as text");
  - σ = C6 ("Norm state");
  - f = C12 ("Entry into force").
- **Where T3 r1.7 places those concepts** (§1b, lines 351–356; §1c, line 375):
  - C15 is in CG4 (C13–C18). It is also the CV-text element (§1c, "record text as content", C15).
  - C6 is in CG2 (C3, C5, C6, C7).
  - C12 is in CG3 (C8–C12).
- **The criterion** (§1b, line 358) is "a T-A row 'crosses' if it reads attributes of concepts placed in two or more groupings". For guard rows, T3's convention (§1a rule 1, line 66) is that a guard "is judged by the items whose tie or order it denies".
- **Result:** R21 reads c (CG4 / CV-text) against σ (CG2) and f (CG3), so **it crosses CG4↔CG2 and CG4↔CG3.** Three sources say the same:
  - T3's VP11 side note (line 522, r1.6 L5 correction): "R21 (c in CG4 / CV-text against σ in CG2 and f in CG3 …) is missing from the §1b guard list, a gap since r1.5";
  - F-LOG-0267;
  - F-LOG-0269 ("Guard rows R14–R17 and R21 also cross groupings, and R21 is missing from the §1b list").

### 1.2 R11 is a second guard-list gap (r1 fix Q1; not covered by the logged authority)

- **T2 r1.4, R11** (line 63) is a guard-form T-A row:
  - its tier is "**T-A (guard form)**";
  - its statement is "σ : T → V^𝒯 is total; **no frame axiom derives σ from E**";
  - its guard note is "**N1 guard** (computing σ from acts would build D-chg into the model: X11, R47)".
- **T3 r1.7 records it as reading "σ, E"**: crossing table, line 92, "| R11 | T-A guard | — | σ, E | internal CB23 |". §1a rule 1 (line 66) lists R11 among the guard rows ("R11, R14–R17, R21").
- **Placements:** σ = C6, in CG2. Acts E = C8 and C9, in CG3 (§1b, lines 352–353).
- **Result:** judged by the tie it denies (σ from E), R11 crosses CG2↔CG3. This parallels R17, which is already on the §1b list: norm value (CG2) against acts (CG3) and work events (CG5). Even without the guard convention, R11's [r1] text reads σ together with f, τ and v (via F1 at entry into force, R1). R1 itself is listed as crossing CG2↔CG3.
- **The logged authority names R21 only.** F-LOG-0267, F-LOG-0269 (open item (d)) and F-LOG-0271 (item (d)) name only R21. The pre-act disclosure for F-LOG-0269 ("Guard rows R14–R17 and R21 also cross groupings") **omitted R11**. So R11 is **not** folded into P1. It is offered as the severable **optional** item P1a′ (§2). The human selects or declines it in the act (§3).

### 1.3 Counts, and the F-LOG-0269 quoted list

- **§1b (line 358)** names two lists:
  - the crossing **constraint** rows R1, R2, R3, R5, R6, R8, R9, R18, R19, R22 (ten);
  - separately, "Guard / non-constraint rows that cross: R14, R15, R16, R17" (four).

  §1b itself states no numeral.
- **§6 insufficiency item 1 (line 571)** states "**ten** T-A constraint rows (R1, R2, R3, R5, R6, R8, R9, R18, R19, R22) and **four** guard rows".
  - With P1 alone, "four" becomes "five" and the list is marked non-exhaustive (R11 open).
  - With P1a′ as well, "four" becomes "six".
  - The **ten** is unchanged in both cases.
- **The F-LOG-0269 act text** quotes "R1, R2, R3, R5, R6, R8, R9, R18, R19 and R22 (T3 §1b)" as the T-A rows that cross groupings. This proposal changes only the guard list. It does not touch the constraint list, and it does not change the act's quoted list.
- **No conflict** was found between T3 r1.7's constraint list and the act's list: both are the same ten rows. The only defect in the logged material is the omission of R11 from the F-LOG-0269 pre-act disclosure (§1.2).
- **No other count changes.** The §1a crossing table and its counts are about the P-c cut, not the groupings:
  - S: 46/29/1/3/4;
  - S′ / S″: 36/38/2/3/4.

  R21 already "crosses" there (line 102). R11 is "internal CB23" there (line 92), which is correct for the P-c cut, because CG2 and CG3 both lie in CB23. §6 element, VP, exclusion and extension counts are unaffected.
- **[r1 fix Q8] Rows that are neither guard nor constraint.** These rows are hosted at MW1 or CV4, not in any CG row:
  - the implications R12 and R13 and the meta row R23, all at MW1;
  - the meta row R24, at CV4. R24 reads Ref and con (CG4) against acts (CG3), so by the §1b criterion it reads two groupings.

  §1b's two lists cover constraint rows and guard rows only. Neither §1b nor the F-LOG acts say whether implication or meta rows belong to the list. This proposal treats them as **out of scope for the §1b list** and adds nothing for them. The question is disclosed, not decided.

### 1.4 Status of the items being synced (log)

- **VP12 = (i)** (F-LOG-0268, act and appended statement): C1 is one shared participant spanning CB23 and CB1.
- **VP11 = (ii)** (F-LOG-0269):
  - "CG1–CG6 are kept separate, with the T-A rows that cross groupings as cross-grouping constraints, as a human design decision …; no grouping is enforceable from records (R23)."
  - "Its effect on the adopted model … takes hold only when the consequential E2b amendment is made by its own act."
- **F-LOG-0271** (act and appended statement; the appended statement governs):
  - no spanning rule is selected;
  - the asymmetry stays documented and not accepted;
  - any O3/E3 proposal must present results under S, S′ and S″;
  - VP11 is unchanged (analyst note: already decided (ii)).
- **Deferred items that need a separately authorized T3 edit:**
  - R21 in §1b: F-LOG-0267; F-LOG-0269 open item (d); F-LOG-0271 item (d).
  - F8 (F-LOG-0270; T3 §6, lines 580–583): the VP11 side-note wording, the R8 relations cell, and the VP11 decided-status sync in §4 and §6.

---

## 2. The patch (exact text)

**Notation:**
- **Current** is the exact r1.7 text at the line given. **Replacement** is the exact text that takes its place.
- Superseded text is kept struck through (`~~…~~`), never deleted, as T3 has done since r1.5.
- `F-LOG-[act]` stands for the id the Human ARB act in §3 receives when it is logged. The applier substitutes the real id.
- **Variants:** where P1a′ (optional) changes a replacement, both texts are given. "Base" is the text used when P1a′ is **not** selected; "with P1a′" is the text used when it is.

**[r1.1 T7] Applier instruction.**
- Apply each item exactly once.
- Before applying an item, check that "[r1.8]" is absent at its anchor. If it is present, the item has already been applied: skip it and report it.
- The P1c anchor (line 522) and the P5a anchor (line 523) **survive application**: the old text stays inside the struck-through or kept text. A re-run would match them again, so the "[r1.8]" check is what prevents a second application.
- Apply the several edits to line 522 (P1c, P2, P4a, P4b) one at a time. Their anchors do not overlap.

### P1. R21 in the §1b guard list (item 1)

**P1a. §1b grouping finding (line 358).** *Base; not used if P1a′ is selected.*

Current (substring of line 358; unique):
```
Guard / non-constraint rows that cross: R14, R15, R16, R17.
```
Replacement:
```
Guard / non-constraint rows that cross: R14, R15, R16, R17, **[r1.8]** R21 (c in CG4 / CV-text against σ in CG2 and f in CG3; added under F-LOG-[act]; the gap dated from r1.5, F-LOG-0267) (list not exhaustive; R11 open, §6 item 1).
```

**Source:**
- T2 r1.4 R21 (guard form; c against σ and f);
- T1 r1.6 C15, C6, C12;
- T3 §1b placements (lines 352–354) and the VP11 side note (line 522, r1.6 L5);
- F-LOG-0267 ("R21 is missing from the T3 §1b grouping guard list. The gap dates from r1.5");
- the F-LOG-0269 disclosure.

Only the guard list changes. No CG row, no constraint-row list and no criterion text is edited.

**P1a′ (OPTIONAL, severable; r1 fix Q1). R11 added as well (line 358).** *Replaces P1a when selected.*

Current (substring of line 358; unique):
```
Guard / non-constraint rows that cross: R14, R15, R16, R17.
```
Replacement:
```
Guard / non-constraint rows that cross: R14, R15, R16, R17, **[r1.8]** R21 (c in CG4 / CV-text against σ in CG2 and f in CG3; added under F-LOG-[act]; the gap dated from r1.5, F-LOG-0267), R11 (σ in CG2 against acts E in CG3; added under F-LOG-[act]; not in the F-LOG-0269 pre-act disclosure, which listed R14–R17 and R21 only).
```

**Source:** §1.2 above, which cites T2 r1.4 R11, T3 lines 66, 92, 352 and 353, and the parallel with R17.

**Not covered by the logged authority** (F-LOG-0267, 0269 and 0271 name R21 only). It applies only if the act selects it.

**P1b. §6 insufficiency item 1 (line 571).** This is the count consequence of P1a or P1a′. The full line replacement is given once, in P4c (both variants).

**P1c. VP11 side note (line 522).** The r1.6 gap statement is now stale.

Current (substring of line 522; unique):
```
is missing from the §1b guard list, a gap since r1.5. §1b is out of scope for this amendment and is not edited; closing the gap needs a separate act
```
Replacement (base):
```
~~is missing from the §1b guard list, a gap since r1.5. §1b is out of scope for this amendment and is not edited; closing the gap needs a separate act~~ **[r1.8]** was missing from the §1b guard list (a gap since r1.5, F-LOG-0267); it is added there under F-LOG-[act]. Only the §1b guard list is edited. R11 (σ in CG2 against acts E in CG3) may also cross groupings; it is an open question, not added (§6 item 1)
```
Replacement (with P1a′):
```
~~is missing from the §1b guard list, a gap since r1.5. §1b is out of scope for this amendment and is not edited; closing the gap needs a separate act~~ **[r1.8]** was missing from the §1b guard list (a gap since r1.5, F-LOG-0267); it is added there under F-LOG-[act], together with R11 (σ in CG2 against acts E in CG3), which also crosses groupings and was not in the F-LOG-0269 pre-act disclosure. Only the §1b guard list is edited
```

**Source:** F-LOG-0267; F-LOG-0271 item (d); for R11, §1.2.

The phrase "The guard rows R14, R15, R16, R17 and R21 … also cross groupings", earlier in the same note, is left as written. It is the r1.6 K7/L5 text that the sibling proposals and F-LOG-0269 quote.

### P2. VP11 side note: "provisional; re-check after VP12" (item 2; F8 first bullet)

**§4 VP11 row (line 522).**

Current (substring of line 522; unique):
```
so **CG1 is the only grouping that may straddle the P-c cut** (provisional; re-check after VP12).
```
Replacement:
```
so ~~**CG1 is the only grouping that may straddle the P-c cut** (provisional; re-check after VP12).~~ **[r1.8]** **CG1 straddles the P-c cut through C1, which spans CB23 and CB1 under VP12 (i)** (F-LOG-0268); it is the only grouping that does. The re-check is done (§1a "VP12 re-check", F-LOG-0270; disposition F-LOG-0271). How the straddle **counts** for the crossing criterion is not settled: it follows the spanning readings S, S′ and S″ (§1a rule 2a), none of which is selected (F-LOG-0271), so results are reported under all three.
```

**Source:**
- F-LOG-0268 (VP12 = (i), "spanning the sides");
- F-LOG-0270 (re-check done; three readings, none selected);
- F-LOG-0271 appended statement ("selects no spanning-participant counting rule … must present the relevant results under all three interpretations: S, S′ and S″").

"Only grouping" is unchanged in substance. The same note places CG2, CG3 and CG5 in CB23, and CG4 and CG6 in CB1. CV3 straddles, but it is not a grouping (same note).

### P3. The R8 relations cell (item 3; F8 second bullet)

**§1a relations table, row CB23 ↔ CB1 (line 310).** The phrase "keeper typing (R8, participant-dependent)" occurs twice, at lines 310 and 582. The anchor below is unique to line 310. Line 582 is in the F8 list, which is handled in P4d.

Current (substring of line 310; unique):
```
| CB23 ↔ CB1 | reference (R9) and, under constitution only, F2 (R2, R3); keeper typing (R8, participant-dependent);
```
Replacement:
```
| CB23 ↔ CB1 | reference (R9) and, under constitution only, F2 (R2, R3); ~~keeper typing (R8, participant-dependent);~~ **[r1.8]** keeper typing (R8): a CB23 ↔ CB1 relation under the spanning reading S [E]; under S′ or S″ [E], R8 is internal to CB1 and is not a relation between the sides (§1a crossing table and rule 2a; no reading selected, F-LOG-0271) (other S-only T-A crossings: R5, R6, R10, R19; crossing table);
```

**Source:**
- T3 §1a crossing table: line 89 (R8: "S: crosses (unchanged); S′ / S″: internal CB1"); lines 86, 87, 91 and 100 (R5, R6, R10, R19: "S: crosses");
- §1a note, line 53 (r1.7 N5);
- F-LOG-0270 totals;
- F-LOG-0271 (no reading selected).

The wording states each reading's result and selects none.

### P4. VP11 decided-status sync in §4 and §6 (item 4; F8 third bullet)

**P4a. §4 VP11 row, "decision" cell (line 522).**

Current (substring of line 522; unique):
```
| VP11 | grouping granularity (aggregate-like boundaries) |
```
Replacement:
```
| VP11 | grouping granularity (aggregate-like boundaries) **[r1.8] DECIDED (ii), F-LOG-0269** |
```

**P4b. §4 VP11 row: "options", "held meanwhile" and "what could decide it" cells (line 522).**

Current (substring of line 522; unique):
```
| e.g. merge CG2+CG3, or keep separate with cross-grouping constraints | groupings as listed, explicitly provisional, conceptual only (no enforcement unit: R23).
```
Replacement:
```
| e.g. merge CG2+CG3, or keep separate with cross-grouping constraints. **[r1.8]** (ii) chosen: CG1–CG6 kept separate, with the T-A rows that cross groupings as cross-grouping constraints (F-LOG-0269) | groupings as listed, explicitly provisional, conceptual only (no enforcement unit: R23). **[r1.8]** VP11 = (ii) is decided (F-LOG-0269), as a human design decision introducing a consistency notion not supplied by R4 (§3b); no grouping is enforceable from records (R23). The groupings stay [E] candidates in T3. The effect on the adopted model (admission of the consistency notion that E2b §2d carries as an excluded §3b extension) takes hold only when the consequential E2b amendment is made by its own act, which is **not made as of r1.8**.
```

Current (substring of line 522; unique; the row's last cell):
```
| human design decision; R4 has no consistency notion |
```
Replacement:
```
| human design decision; R4 has no consistency notion. **[r1.8] made: (ii), F-LOG-0269**, decided without reference to any VP1a–d outcome (including VP1b); the act records the CG3 note (§1b) and the register's counterpart ("a grouping justified only by H-B-side clauses would lean the other way") |
```

**Source (P4a, P4b):** the F-LOG-0269 act text; quoted phrases are verbatim. The pattern follows the r1.7 VP12 row (line 523): "**[r1.7] DECIDED (i), F-LOG-0268**", "(i) chosen", "made: (i), F-LOG-0268". The ten-row crossing list in the "slots / rows affected" cell is left as is, because it is the list the act quotes.

**P4c. §6 insufficiency item 1 (line 571).** It carries P1b and item 4.

Current (line 571, whole line):
```
1. **Grouping boundaries** (VP11): ten T-A constraint rows (R1, R2, R3, R5, R6, R8, R9, R18, R19, R22) and four guard rows cross the candidate groupings; R4 has no consistency, transaction or lifecycle notion; and by R23 no grouping could be an enforcement unit.
```
Replacement (base; P1a′ not selected). The fallback is explicit: the list is non-exhaustive, and R11 is stated as an open question.
```
1. **Grouping boundaries** (VP11): ten T-A constraint rows (R1, R2, R3, R5, R6, R8, R9, R18, R19, R22) and ~~four~~ **[r1.8]** at least five guard rows (R14, R15, R16, R17, R21; R21 added to §1b under F-LOG-[act]) cross the candidate groupings; R4 has no consistency, transaction or lifecycle notion; and by R23 no grouping could be an enforcement unit. **[r1.8]** The guard list is **not exhaustive**. **Open question (not decided by r1.8):** R11 (T-A guard: "no frame axiom derives σ from E", T2 r1.4; it reads σ, E, §1a line for R11) also reads σ in CG2 against acts E in CG3, as R17 does, and so appears to cross groupings. It was not in the F-LOG-0269 pre-act disclosure and is not added; adding it needs its own act. Implication and meta rows (R12, R13, R23 at MW1; R24 at CV4) are outside the §1b lists, which cover constraint and guard rows only (whether they belong there is not decided). Whether guard rows are cross-grouping constraints in the act's sense is not decided here (E2b-AMENDMENT-1-PROPOSAL choice C-γ). **[r1.8]** VP11 is decided (ii) by human act (F-LOG-0269), not derived: CG1–CG6 kept separate with the crossing T-A rows as cross-grouping constraints. T1/T2 still give no criterion; the consistency notion is a human design decision not supplied by R4 (§3b), and its effect on the adopted model awaits the E2b amendment by its own act (not made as of r1.8).
```
Replacement (with P1a′):
```
1. **Grouping boundaries** (VP11): ten T-A constraint rows (R1, R2, R3, R5, R6, R8, R9, R18, R19, R22) and ~~four~~ **[r1.8]** six guard rows (R11, R14, R15, R16, R17, R21; R11 and R21 added to §1b under F-LOG-[act]; R11 was not in the F-LOG-0269 pre-act disclosure) cross the candidate groupings; R4 has no consistency, transaction or lifecycle notion; and by R23 no grouping could be an enforcement unit. **[r1.8]** Implication and meta rows (R12, R13, R23 at MW1; R24 at CV4) are outside the §1b lists, which cover constraint and guard rows only (whether they belong there is not decided). Whether guard rows are cross-grouping constraints in the act's sense is not decided here (E2b-AMENDMENT-1-PROPOSAL choice C-γ). VP11 is decided (ii) by human act (F-LOG-0269), not derived: CG1–CG6 kept separate with the crossing T-A rows as cross-grouping constraints. T1/T2 still give no criterion; the consistency notion is a human design decision not supplied by R4 (§3b), and its effect on the adopted model awaits the E2b amendment by its own act (not made as of r1.8).
```

**Source:**
- P1a / P1a′;
- §1.2 and §1.3 (Q8);
- F-LOG-0269.

The **ten** and its list are unchanged, matching the act.

**P4d. §6 deferred follow-up F8 (lines 580–583).** The F8 list is marked as closed by r1.8, and its header count is corrected. Nothing is deleted.

Current (line 580, whole line):
```
**[r1.7 review] Deferred follow-up F8 (recorded, not fixed).** Two items are stale under VP12 (i) and outside the r1.7 scope; they need a separate authorized edit:
```
Replacement:
```
**[r1.7 review] Deferred follow-up F8 (recorded, not fixed).** ~~Two~~ **[r1.8]** Three items (the third added by r1.7 N3) are stale under VP12 (i) and outside the r1.7 scope; they need a separate authorized edit: **[r1.8] All three items below are applied in r1.8 under F-LOG-[act]: the VP11 side note (§4), the relations cell (§1a) and the VP11 status (§4 row; §6 item 1). R21 in §1b (F-LOG-0267) is applied in the same edit. The list is kept as history.**
```

Lines 581–583 are unchanged.

**Source:** F-LOG-0270 (F8 list); F-LOG-0271 item (d); T3 line 583 (the N3 item).

### P5. Other stale references to VP11 being undecided (item 5)

**Scope of the search (r1 fix Q7).** All 609 lines were searched for the terms "VP11", "provisional", "re-check after VP12" / "Re-check after VP12", "undecided" and "not decided". The completeness claim covers **only hits relevant to VP11's decided status, or to the items authorized here**. The other hits are listed below, with the reason each is excluded.

**Relevant hits:**

| line | text (excerpt) | verdict | proposal |
|---|---|---|---|
| 3 | r1.6 status history: "VP12 and VP11 not decided" | inside "**[r1.6 status, kept as history]**"; historical | none |
| 28 | "~~VP11 is not decided.~~ **[r1.7 review fix M1]** VP11 was decided (ii) by F-LOG-0269 …" | already synced in r1.7 | none |
| 358 | §1b "The grouping boundaries are therefore not derivable from T1/T2; they are VP11." | still true: (ii) was decided by human act, not derived. §1b content is outside this patch except the guard list | none |
| 492 | §3b "a consistency / transaction notion to fix grouping boundaries \| not in R4 (VP11) \| human design decision" | still true: the notion is outside R4, F-LOG-0269 is that human design decision, and its admission awaits the E2b amendment | none |
| 522 | VP11 row: decision, options, held-meanwhile and decide-by cells; side note "provisional; re-check after VP12"; R21 gap statement | stale | P2, P1c, P4a, P4b |
| 523 | VP12 row: "§1b (CG1 still lists C1) and the VP11 side note are not edited." | true of r1.7; stale after r1.8 for the side note | **P5a** |
| 523 | VP12 row: "~~VP4 and VP11 remain undecided~~ … (VP11 since decided (ii), F-LOG-0269)" | already synced (M1) | none |
| 571 | §6 item 1: no VP11 status | stale | P4c |
| 576 | §6 item 6: "whether adoptions belong with their object is undecided (part of VP11)" | stale: VP11 = (ii) keeps CG3 (issue act with its adoptions) as listed; "merge CG2+CG3" was not chosen | **P5b** |
| 580–583 | F8 list (581, 583 name VP11) | to be marked closed | P4d |
| 590, 604–609 | §7 change rows naming VP11 | §7 history | none |

**Excluded hits, and why** (they concern VP12 / C1 placement or other VPs, not VP11):
- **25, 26, 38, 51, 53, 61, 67, 78, 176, 199, 200, 432:** r1.6/r1.7 text on the provisional C1 placement. Each is either superseded in place by r1.7 or is r1.7's own "no longer provisional" text.
- **404:** VP12-stale, and outside the authorized items; see §4 item 2.
- **523** (the r1.6 history part of the VP12 row, "Re-check after VP12"): marked "[r1.6, superseded]".
- **542:** the C1 placement is struck and replaced in r1.7.
- **578:** §6 item 8. Its r1.6 "re-checked after VP12" text has an [r1.7] addition.
- **298:** "not decided here" refers to congeniality, not a VP.
- **574:** "undecided" refers to CB4's existence (VP10).
- **3** (other hits on the same line): status history.

**P5a. §4 VP12 row (line 523).**

Current (substring of line 523; unique):
```
§1b (CG1 still lists C1) and the VP11 side note are not edited.
```
Replacement (base):
```
§1b (CG1 still lists C1) and the VP11 side note are not edited. **[r1.8]** In r1.8 (F-LOG-[act]) the VP11 side note is synced and R21 is added to the §1b guard list; CG1 still lists C1.
```
Replacement (with P1a′):
```
§1b (CG1 still lists C1) and the VP11 side note are not edited. **[r1.8]** In r1.8 (F-LOG-[act]) the VP11 side note is synced and R21 and R11 are added to the §1b guard list; CG1 still lists C1.
```

**P5b. §6 insufficiency item 6 (line 576).**

Current (line 576, whole line):
```
6. **Issue-act / adoption grouping** (CG3): R4 gives only obj typing (R4); whether adoptions belong with their object is undecided (part of VP11).
```
Replacement:
```
6. **Issue-act / adoption grouping** (CG3): R4 gives only obj typing (R4); whether adoptions belong with their object is ~~undecided~~ **[r1.8]** not decidable from T1/T2 (part of VP11). VP11 = (ii) (F-LOG-0269) keeps CG3 as listed by human design decision, not by derivation; the act records the CG3 note (§1b: meaning mainly under D-st / D-st⁺, VP1b) and was decided without reference to any VP1a–d outcome. Effect on the adopted model awaits the E2b amendment (not made as of r1.8).
```

**Source:** the F-LOG-0269 act text.

P5a and P5b are proposed as part of the smallest coherent patch. Leaving them out would keep two statements that contradict the synced VP11 row. The human may strike them from the act in §3 without affecting P1–P4 and P6. If they are struck, P6b and P6c drop their mention of the VP12 row and of §6 item 6.

### P6. Title, status line and §7 row (item 6)

**P6a. Title (line 1).** By precedent, the r1.6 and r1.7 rows record "Title and status line → r1.x"; the title has no history slot.

Current:
```
# R7 · T3: candidate domain model with traceability (draft r1.7)
```
Replacement:
```
# R7 · T3: candidate domain model with traceability (draft r1.8)
```

**P6b. Status line (line 3).** The r1.7 status is kept as history; only its leading words are wrapped. **[r1 fix Q4]** The added parenthetical about the r1.7 reviews is tagged **[r1.8]**.

Current (prefix of line 3; unique):
```
**Status:** r1.7, VP12 re-check under F-LOG-0268 (VP12 = (i)); nothing adopted; E2b not amended; asymmetry documented, not accepted; awaiting independent review. **[r1.7]**
```
Replacement (base):
```
**Status:** r1.8, post-closure sync amendment under F-LOG-[act]; nothing adopted; E2b not amended; no VP decided; no spanning rule selected; asymmetry documented, not accepted (F-LOG-0271). **[r1.8]** Applies exactly the T3 edits authorized by F-LOG-[act] (`R7/T3-SYNC-PROPOSAL.md` r1.2): R21 added to the §1b guard list (gap since r1.5, F-LOG-0267), with R11 recorded in §6 item 1 as an open question and the guard list marked non-exhaustive; F8 (F-LOG-0270): the VP11 side note no longer "provisional; re-check after VP12" (VP12 = (i), F-LOG-0268; counting under S, S′, S″, none selected, F-LOG-0271), the R8 relations cell restated per reading, and VP11 = DECIDED (ii) (F-LOG-0269) synced in the §4 VP11 row and §6 items 1 and 6; the VP12 row and F8 list annotated; this status line and a §7 row. The CG, CV, CE, CS and MW content is not edited except the §1b guard list. VP11's effect on the adopted model awaits the E2b amendment by its own act (not made as of r1.8). It authorizes no O3 proposal preparation, E3, E3b, E4 or E5. **[r1.8]** (The r1.7 reviews have since completed, F-LOG-0270; disposition F-LOG-0271.) **[r1.7 status, kept as history]** r1.7, VP12 re-check under F-LOG-0268 (VP12 = (i)); nothing adopted; E2b not amended; asymmetry documented, not accepted; awaiting independent review. **[r1.7]**
```
**With P1a′**, replace the phrase
`R21 added to the §1b guard list (gap since r1.5, F-LOG-0267), with R11 recorded in §6 item 1 as an open question and the guard list marked non-exhaustive;`
with
`R21 (gap since r1.5, F-LOG-0267) and R11 (not in the F-LOG-0269 pre-act disclosure) added to the §1b guard list;`.

**P6c. §7: a new row appended after line 609, the last line of the file.**

Insert after line 609 (base):
```
| r1.8 | **post-closure sync amendment under F-LOG-[act]** (`R7/T3-SYNC-PROPOSAL.md` r1.2); closes F-LOG-0271 open item (d) | Title and status line → r1.8. **§1b:** R21 added to the guard list (c in CG4 / CV-text against σ in CG2 and f in CG3; F-LOG-0267); the ten-row constraint list is unchanged (as quoted by F-LOG-0269). **§1a relations:** "keeper typing (R8, participant-dependent)" struck; restated as a relation under S, internal to CB1 under S′ / S″, none selected (F-LOG-0271), with a pointer to the other S-only T-A crossings (R5, R6, R10, R19). **§4 VP11:** DECIDED (ii), F-LOG-0269 (decision, options, held-meanwhile and decide-by cells); side note: "may straddle … (provisional; re-check after VP12)" struck, CG1 stated to straddle the cut through C1 under VP12 (i), with its counting under S, S′, S″ unselected; the r1.6 R21-gap statement struck and marked closed, R11 noted as open. **§4 VP12:** note that the VP11 side note and §1b guard list are synced in r1.8. **§6:** item 1 "four" → "at least five" guard rows (R21), the list marked non-exhaustive, R11 stated as an open question, implication / meta rows noted as outside the §1b lists, plus VP11 status; item 6 VP11 status; F8 list marked applied and its header "Two" → "Three" (kept as history). Superseded text struck, not deleted. No CG, CV, CE, CS or MW content changed except the §1b guard list. Counts unchanged (elements 28; VPs 19; §1a crossing totals S 46 / 29 / 1 / 3 / 4, S′ / S″ 36 / 38 / 2 / 3 / 4). Nothing adopted; no VP decided; no spanning rule selected; E2b not amended; no O3 preparation, E3, E3b, E4 or E5 authorized |
```
**With P1a′**, make these substitutions in that row:
- the §1b sentence becomes `**§1b:** R21 (c in CG4 / CV-text against σ in CG2 and f in CG3; F-LOG-0267) and R11 (σ in CG2 against acts E in CG3; not in the F-LOG-0269 pre-act disclosure) added to the guard list; the ten-row constraint list is unchanged (as quoted by F-LOG-0269).`;
- `the r1.6 R21-gap statement struck and marked closed, R11 noted as open` becomes `the r1.6 R21-gap statement struck and marked closed, R11 added`;
- `item 1 "four" → "at least five" guard rows (R21), the list marked non-exhaustive, R11 stated as an open question,` becomes `item 1 "four" → "six" guard rows (R11, R21),`.

---

## 3. Draft Human ARB act (for the human to select, amend or reject)

> "I, Human ARB, authorize a post-closure amendment of `R7/T3-CANDIDATE-MODEL.md` r1.7 to r1.8 that applies exactly the edits P1a, P1c, P2, P3, P4a–P4d, P5a, P5b and P6a–P6c of `R7/T3-SYNC-PROPOSAL.md` r1.2, in their base variants, and nothing else: R21 added to the §1b grouping guard list (F-LOG-0267); the F8 items of F-LOG-0270 (the §4 VP11 side note, the §1a R8 relations cell, and the VP11 decided-status sync in §4 and §6, VP11 = (ii) per F-LOG-0269); and the consequential status line, title, VP12-row note, F8 note and §7 row. The order of application is fixed: this act (C) first, then the E2b amendment (A, `R7/E2b-AMENDMENT-1-PROPOSAL.md` r2.2), then the register/ARB sync (B, `R7/REGISTER-ARB-SYNC-PROPOSAL.md` r2.2) last; each is its own act. I record that the pre-act disclosure for F-LOG-0269 listed R14–R17 and R21 and omitted R11. **[Select one:] (A) P1a′ is not selected: R11 is recorded in §6 item 1 as an open question, and the guard list is marked non-exhaustive. / (B) P1a′ is selected: R11 is also added to the §1b guard list. P1a′ applies in place of P1a, and the 'with P1a′' variants of P1c, P4c, P5a, P6b, P6c apply.** Superseded text is struck, not deleted. This act decides no variation point and selects no spanning rule: S, S′ and S″ stay unselected, and the asymmetry stays documented and not accepted (F-LOG-0271). It adopts nothing and does not amend E2b. It does not change the CG, CV, CE, CS or MW content except the §1b guard-list addition. It does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5. [Optional: The applied text is to be checked against this proposal by a fresh independent review before it is relied on.]"

**Notes for the human (not part of the act):**
- **Option (A)** follows the logged authority: F-LOG-0267, 0269 and 0271 name R21 only. **Option (B)** extends the guard list on the evidence in §1.2. Option (B) changes no VP and does not change the F-LOG-0269 quoted constraint list.
- P5a and P5b may be struck from the act; P1–P4 and P6 stand without them.
- Closing this item leaves three items open from the F-LOG-0271 list:
  - (b) the E2b amendment for P-c and VP11 (ii);
  - (c) the register §3/§5a amendment (plus ARB §E3, if read so) for F-LOG-0264;
  - (e) the O3-preparation act.

**Coordination note (r1 fix Q9; corrected in r1.1, T5 and T6):**
- ~~**The E2b sibling carries the same guard list and inherits Q1.** `R7/E2b-AMENDMENT-1-PROPOSAL.md` gives the guard text under choice **C-γ** (around lines 119–124): "R14–R17 and R21 (T3 r1.7 §4 VP11 side note: K7, L5); possibly R11". Its act clause (3b) (around line 287) reads "R14–R17, R21, and R11 if found". **[r1.1 T5]** Its back-references to R11 describe this proposal's §4 item 1 as "unverified": around line 15, line 121, line 330 (§8 item 6) and line 385 (E6). Those back-references are stale, because r1 and r1.1 give direct evidence (§1.2). They need updating in that sibling by its own revision; this proposal does not edit it.~~ **[r1.2 U1]** Superseded: that description fits E2b-AMENDMENT-1-PROPOSAL r1 only.
- **[r1.2 U1] The E2b sibling, now at r2 / r2.1, is aligned.** Its "R11 if found" and "unverified" wording is gone. Its clause (3b) names the guard rows R14–R17 and R21 (from the F-LOG-0269 pre-act disclosure), plus R11 under T3-SYNC option (B), and otherwise treats R11 as an open question per T3 r1.8 §6 item 1. Its count is fixed at 27, and its C-β is confirm-only. It cites this proposal's §1.2 evidence and P1a′ (around lines 134–142 of r2), ~~and it orders the T3-SYNC act before its own clause (3b)~~. **[r1.3 U2 (a)]** A r2.2 calls this act's precedence "recommended, not required": its clause (3b) holds without it, because it names R14–R17 and R21 and treats R11 either way (option (B), or otherwise open). Under the fixed order C → A → B the precedence is met in practice; A does not depend on it. No update to the sibling is needed on this proposal's account.
- **The register sibling.** `R7/REGISTER-ARB-SYNC-PROPOSAL.md` H5 appends "guard rows R14–R17 and R21 also cross groupings". ~~It inherits Q1 in the same way. If option (B) is selected, it should note R11; if option (A) is selected, it should say the list is not exhaustive, with R11 open.~~ **[r1.3 U2 (c)]** Met: B r2.2 J1 makes H5 order-conditional, recording R11 under this act's option (B) and an open, non-exhaustive list under option (A). Nothing further is requested of B.
- **[r1.1 T6] C-γ interaction.** Whether guard rows (R14–R17, R21 and, under option (B), R11) are cross-grouping constraints in the sense of the VP11 act is **E2b-AMENDMENT-1-PROPOSAL choice C-γ**, a human choice. Adding a row to the §1b guard list here records that it crosses groupings; it does not decide C-γ.
- ~~**Pinned citation.** The E2b proposal cites the r1.7 phrase that P1c strikes ("is missing from the §1b guard list, a gap since r1.5"). That citation stays valid, because it is pinned to T3 r1.7, and P1c keeps the phrase visible as struck text.~~ **[r1.1 T5]** Withdrawn: the E2b sibling does not cite "a gap since r1.5".
- ~~**Order of application.** Apply this T3 sync first. The E2b amendment and the register/ARB sync can then cite T3 r1.8, or keep their r1.7 pins with an explicit "since r1.8" note. Each sibling is its own act. The applier should use the struck-through form, so that r1.7 citations still resolve.~~ **[r1.3 U2 (b)] Order of application (FIXED):** C (this act) first, then A (E2b amendment r2.2), then B (register/ARB sync r2.2) last; each is its own act. Neither sibling cites T3 r1.8. A keeps its T3 r1.7 pins, and they stay valid after r1.8, because P1c and P3 keep the replaced text visible as struck text and the §2 traces are untouched by this patch.
- **Placeholder id.** `F-LOG-[act]` is a placeholder. It is expected to be F-LOG-0272 if no other entry precedes it, but the real id is assigned only at logging, and the applier substitutes it everywhere.

---

## 4. Disclosed observations outside this patch (recorded, not proposed)

These were found while verifying. None is proposed for the act; each would need its own authorization.

1. **[r1 fix Q1] R11 is a second guard-list gap.** The evidence is direct (§1.2):
   - T2 r1.4 gives R11 as "T-A (guard form)": "no frame axiom derives σ from E";
   - T3 line 92 records it as reading "σ, E";
   - T3 line 66 (rule 1) lists it as a guard row;
   - σ is C6, in CG2; acts are C8 and C9, in CG3;
   - it parallels R17.

   It is offered as the optional item P1a′ rather than proposed outright, because the logged authority names R21 only. If P1a′ is not selected, the base text states R11 as an open question in T3 §6 item 1.
2. **[r1 fix Q3] An internal T3 inconsistency at the §2 CB23 sensitivity cell (line 404).**
   - The cell still reads "(C1 provisional in CG1; re-check after VP12)".
   - T3's own §7 r1.7 row (line 607) says the CB23 cell's "C1 provisional in CG1; re-check after VP12" is "superseded by the M5 note".
   - The M5 note itself (line 432) does not mention it. M5 covers only the "crossing symmetric under 𝐑₀" claim and the internal-row entries.

   The supersession claimed at line 607 is therefore not carried by the text it points to. This is VP12-stale, not VP11-stale, and it was not on the F8 list.
3. **§1a relations row CB23 ↔ CB1 (line 310).** P3 now points to the other S-only crossings (R5, R6, R10, R19) but does not list them as relations. Whether the relations table should hold them under S is not addressed.
4. **[r1 fix Q8] Implication and meta rows.** R12, R13 and R23 (MW1) and R24 (CV4; Ref and con in CG4 against acts in CG3) are neither guard rows nor constraint rows. They are treated as out of scope for the §1b lists (§1.3). Whether they belong there is undecided.

---

## 5. Self-check

| check | result |
|---|---|
| Base revision checked before drafting | yes: T3 r1.7, commit `6e0407db7`, working tree clean for that file, 609 lines |
| Every "current text" quoted exactly with line number | yes: each anchor was verified by exact-string search, and uniqueness was checked. The R8 phrase occurs at 310 and 582; the P3 anchor includes the row prefix and is unique |
| Every edit has a source | yes: F-LOG-0267, 0268, 0269, 0270, 0271; T2 r1.4 R11, R21; T1 r1.6 C6, C12, C15; T3 r1.7's own lines |
| R21 verified as guard form, crossing groupings | yes: T2 tier "T-A (guard form; modelling choice)"; c (C15, CG4 / CV-text) against σ (C6, CG2) and f (C12, CG3) |
| R11 handled within the logged authority | yes: it is not folded into P1. It is the severable optional item P1a′, selected in the act. The fallback text states it as an open question and marks the list non-exhaustive. The omission from the F-LOG-0269 pre-act disclosure is disclosed |
| F-LOG-0269 quoted list unchanged; conflicts disclosed | yes: the ten constraint rows are untouched, and no conflict with T3 r1.7 was found. The guard count becomes "at least five" (base) or "six" (P1a′) |
| Superseded text struck, never deleted | yes: every replaced phrase is kept as `~~…~~`, including the F8 "Two"; the F8 list and §7 history are kept |
| CG/CV/CE/CS/MW content unchanged except the §1b guard list | yes: no CG/CV/CE/CS/MW row cell is edited. P1a / P1a′ edit only the guard-list sentence of the §1b finding |
| No spanning rule selected; R8 cell correct under S, S′, S″ | yes: P2 and P3 state results per reading and cite F-LOG-0271. P2 no longer says "definitive" |
| VP11 effect on the adopted model stated as pending the E2b amendment | yes: P4b, P4c, P5b and P6b say "not made as of r1.8" |
| P5 completeness claim scoped | yes: it is limited to VP11-relevant hits, and every excluded hit is listed with a reason |
| Standing qualifier verbatim | yes: "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable" (header) |
| Act scope | it decides no VP, selects no spanning rule, keeps the asymmetry documented and not accepted, adopts nothing, does not amend E2b, and does not authorize O3 preparation, E3, E3b, E4 or E5 |
| T3, a sibling or any other existing file edited; anything committed | no: only this proposal file is edited; nothing is applied; nothing is committed |
| Strongest statement within the evidence | yes: R11 is stated on cited evidence, but it is offered as an option because the logged authority does not cover it |

## 6. r1 changes (fresh review of r0: PASS_WITH_LIMITATIONS)

| finding | fix in r1 |
|---|---|
| Q1 (material) R11 is a second guard-list gap | §1.2 added with the evidence (T2 r1.4 R11 guard form; T3 lines 66, 92; CG2 against CG3; parallel to R17). R11 is not folded into P1. The severable optional item **P1a′** adds R11 "(σ in CG2 against acts E in CG3)" with the count "six". The act gives a choice, (A) or (B), and records that the F-LOG-0269 pre-act disclosure omitted R11. The base fallback in P4c and P1c states that the list is non-exhaustive and that R11 is an open question in the T3 text. §4 item 1 cites the evidence instead of "unverified" |
| Q2 R8 cell pointer; F8 header count | P3 adds "(other S-only crossings: R5, R6, R10, R19; crossing table)". P4d strikes and corrects "~~Two~~ **[r1.8]** Three" |
| Q3 §4 item 2 | §4 item 2 is restated as an internal T3 inconsistency. Line 607 claims the line-404 parenthesis is superseded by M5, but the M5 note (line 432) does not mention it |
| Q4 P6b parenthetical | tagged **[r1.8]** and moved into the r1.8 portion of the status line |
| Q5 P2 "definitive" | dropped. The text now reads "CG1 straddles the P-c cut through C1, which spans CB23 and CB1 under VP12 (i)" |
| Q6 "not made" | changed to "not made as of r1.8" in P4b, P4c (both variants), P5b and P6b |
| Q7 P5 completeness | the claim is limited to VP11-relevant hits. Excluded hits (25, 26, 38, 51, 53, 61, 67, 78, 176, 199, 200, 298, 404, 432, 523 history, 542, 574, 578) are listed with reasons. The self-check row is fixed |
| Q8 non-guard, non-constraint rows | a disclosure is added in §1.3 and §4 item 4, with a line in P4c: R12, R13 and R23 (MW1) and R24 (CV4) are out of scope for the §1b lists, and this is undecided |
| Q9 coordination | a §3 coordination note covers the siblings (E2b-AMENDMENT-1-PROPOSAL lines 81–82; REGISTER-ARB-SYNC H5), which carry the five-row list and inherit Q1; the E2b citation of the struck r1.7 phrase, which stays valid because it is pinned to r1.7; the order of application; and the placeholder F-LOG id |
| Q10 act wording | the act now says "the asymmetry stays documented and not accepted (F-LOG-0271)" |

## 7. r1.1 changes (fresh review of r1: PASS_WITH_LIMITATIONS; no blockers; no material findings; minor fixes not re-reviewed)

| finding | fix in r1.1 |
|---|---|
| T1 | the P1a base replacement appends "(list not exhaustive; R11 open, §6 item 1)" to the §1b guard list |
| T2 | the act's sentence "I record that the pre-act disclosure for F-LOG-0269 … omitted R11" is moved outside the (A)/(B) select block |
| T3 | option (B) now states "P1a′ applies in place of P1a, and the 'with P1a′' variants of P1c, P4c, P5a, P6b, P6c apply" |
| T4 | the P3 pointer (and its mention in the P6c row) now reads "other S-only T-A crossings: R5, R6, R10, R19" |
| T5 | the coordination note is corrected. The E2b sibling's guard text is located around lines 119–124 ("R14–R17 and R21"), not lines 81–82. The "pinned citation" bullet is struck and withdrawn, because E2b does not cite "a gap since r1.5". The E2b sibling's "unverified" back-references to R11 (around lines 15, 121, 330 and 385) are disclosed as stale and needing an update there |
| T6 | P4c (both variants) adds "whether guard rows are cross-grouping constraints in the act's sense is not decided here (E2b-AMENDMENT-1-PROPOSAL choice C-γ)". The coordination note mentions C-γ |
| T7 | P4c's meta-row sentence adds "(whether they belong there is not decided)". An applier instruction is added before P1: apply each item once, check first that "[r1.8]" is absent at the anchor, and note that the P1c and P5a anchors survive application |
| U1 (r1.2) | coordination sync with E2b-AMENDMENT-1-PROPOSAL r2 / r2.1. The §3 coordination note's r1-era description of the sibling (lines 119–124 and the stale "unverified" back-references) is struck, not deleted, and replaced with the sibling's current clause (3b): R14–R17 and R21, plus R11 under option (B), otherwise an open question per T3 r1.8 §6 item 1; count 27; C-β confirm-only. The self-reference in the act is changed from r1 to r1.2, and for consistency the same reference in the P6b status line and the P6c §7 row is changed too. No patch text, variant or count changed; not re-reviewed |
| U2 (r1.3) | final cross-check (PASS_WITH_LIMITATIONS). Order fixed: C (this act) → A (E2b r2.2) → B (register/ARB r2.2), stated once in the act preamble and in the coordination note. (a) A's "orders the T3-SYNC act before its clause (3b)" is struck; A r2.2 calls the precedence "recommended, not required", and its (3b) holds without it. (b) The invitation for the siblings to cite T3 r1.8 is struck; neither sibling cites r1.8, and A's r1.7 pins stay valid (P1c and P3 keep struck text; the §2 traces are untouched). (c) The R11 request to the register sibling is struck as met by B r2.2 J1 (order-conditional H5). No patch text, variant or count changed; not re-reviewed |
