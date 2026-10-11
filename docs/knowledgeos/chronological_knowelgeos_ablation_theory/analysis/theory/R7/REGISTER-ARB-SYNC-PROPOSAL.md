# R7 · Register / ARB synchronization proposal for F-LOG-0264 (draft r2.2)

**Status: PROPOSED, not adopted; draft r2.2.** 2026-10-11. Worker draft. Three fresh independent reviews so far, all PASS_WITH_LIMITATIONS (§6):
- r0's review gave findings P1–P11, applied in r1.
- r1's review gave findings R1–R11, applied in r2.
- r2's review found no blocker and no material finding; its minor findings S1–S9 are applied in r2.1.
- **S1–S9 are minor fixes applied after a PASS_WITH_LIMITATIONS review. r2.1 has not been re-reviewed.**
- A final cross-check of r2.1 came back PASS_WITH_LIMITATIONS; its findings J1–J3 are applied in r2.2 (§6d). r2.2 has not been re-reviewed.

**Fixed act order (coordinator, r2.2): C → A → B.**
1. **C:** the T3-SYNC act (`R7/T3-SYNC-PROPOSAL.md`, T3 r1.7 → r1.8).
2. **A:** the E2b amendment act (`R7/E2b-AMENDMENT-1-PROPOSAL.md`).
3. **B:** this act, signed **last**.

The housekeeping items in §3 are written to be correct when B is signed last, and they are conditional on whether C and A were actually made.

**Applier instruction:** before applying, fill every placeholder from the actual governance log:
- `F-LOG-NNNN` is this act;
- `F-LOG-[T3-SYNC id]` and `F-LOG-[E2b-amendment id]` are the earlier acts, if made;
- `F-LOG-[latest id]` is the latest log entry.

Choose each conditional branch by what the log records. Delete the branch not taken. Never fill a placeholder by assumption.

It decides nothing, edits no file and authorizes nothing. Every text below is a proposal for a Human ARB act.

**Authority:** F-LOG-0264, the ruling and its clause: "The consequential amendment of the register, and of ARB §E3 ('E2 for VP10, VP12, VP11') if it is read as requiring a decision, needs its own act."
- **Context:** F-LOG-0258, F-LOG-0261, F-LOG-0263, F-LOG-0265 to 0271.
- **Package:** `R7/E3-PREREQ-VP-DECISION-PACKAGE.md` r4.1 §1.7, §1.8, §6 item 2.

**Targets (as inspected):** `R7/VP-DECISION-REGISTER.md` r1.18 (262 lines) and `R7/ARB-DECISION-PACKAGE.md` r4.3 (258 lines). Line numbers refer to these revisions.

**Method:**
- Nothing is deleted. Original text stays in place.
- Every proposed change **appends** a bracketed annotation.
- All new register text is tagged `[r1.19 sync]`, and all new ARB text `[r4.4 sync]`. The F-LOG citations go inside the text.
- **Historical change rows are never edited** (ES-004.3). New content goes into the new r1.19 / r4.4 change row.
- **One disclosed exception to "append only":** the version token in each title line is replaced in place: register `(draft r1.18)` → `(draft r1.19)`, and ARB `(draft r4.3)` → `(draft r4.4)`, the latter only if any ARB edit is taken. This follows established practice: register r1.2 recorded "title and status aligned", and each later revision bumped the title. The superseded version stays recorded in the change rows.
- `F-LOG-NNNN` stands for the act that would authorize this sync.

**Standing qualifier:** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

---

## 1. Register: affected text (F-LOG-0264 scope)

F-LOG-0264 names register §3 and register §5a ("O3: yes"). The three VP10 status annotations say "consequential register/ARB amendment pending its own act". The act would discharge that.

### 1.1 §3, O3 row (line 131), cell "minimal VPs to decide"

**Current (exact):**
> `| **O3** prepare an architecture authorization proposal | **VP15, VP5, VP14, VP10, VP12, VP11** | …`

**Proposed (cell becomes):**
> `**VP15, VP5, VP14, VP10, VP12, VP11** **[r1.19 sync] For VP10, the re-affirmed VP10 = (iii) (F-LOG-0263) counts as VP10's decision for this set, by human ruling as a decision item outside the register's VP10 options (not on evidence; F-LOG-0264). The list above stands as recorded; only what satisfies VP10 in it is ruled.**`

The rest of the row is unchanged. The O2 row (line 130) is not affected.

### 1.2 §5a, VP10 row (line 175), cell "decision required for the next deliverable?"

**Current (exact):**
> `O1: no. **O2: an explicit "left open" record is required** (edge VP10 → VP14; r1.2). **O3: yes**`

**Proposed:**
> `O1: no. **O2: an explicit "left open" record is required** (edge VP10 → VP14; r1.2). **O3: yes** **[r1.19 sync] "O3: yes" is met by the re-affirmed (iii) (F-LOG-0263), by human ruling outside the VP10 options (not on evidence; F-LOG-0264). The text "O3: yes" stays as recorded.**`

The "recommended action" cell is a historical timing recommendation. No edit is proposed.

### 1.3 VP10 status annotation (identical at lines 63, 151 and 175)

**Current (exact, each line):**
> `VP10 **[RECORDED (iii) "left open as a slot", F-LOG-0258; [r1.11] REVISITED and RE-AFFIRMED (iii), F-LOG-0263; [r1.12] ruled to count for the O3 minimal set (new item outside the options), F-LOG-0264; consequential register/ARB amendment pending its own act]**`

**Proposed (append inside the brackets, at all three lines):**
> `…; consequential register/ARB amendment pending its own act; [r1.19 sync] register §3 / §5a amended (not on evidence; F-LOG-0264; F-LOG-NNNN); ARB: {§2 choice}]`

`{§2 choice}` takes one of three values:
- **A:** "§C row 4 and §E3 annotated (r4.4)";
- **B:** "§E3 annotated (r4.4); §C row 4 left as recorded";
- **C:** "no F-LOG-0264 annotation to §C row 4 or §E3 prerequisites; F-LOG-0264 governs".

### 1.4 Inspected, no edit proposed

- **§1b VP10 "needed for" (line 63):** reads `O3 (whether the proposal includes CB4)`. It does not say a decision is required.
- **§5b priority 4:** concerns O2 timing.
- **§2 graph:** shows ordering only.
- **Prior package §2.3** (`R7/VP5-VP10-VP14-DECISION-PACKAGE.md`): F-LOG-0264 names it in its "notwithstanding", but **not** in its amendment clause. It stays as recorded.

### 1.5 New §7 change row (appended after line 262), plus a title bump

> `| r1.19 | sync under human act | F-LOG-NNNN: consequential amendment for F-LOG-0264. §3 O3 row and §5a VP10 row annotated: the re-affirmed VP10 = (iii) counts as VP10's decision for the O3 minimal set (human ruling outside the options, not evidence). VP10 annotations (§1b, §4, §5a) record the amendment. Original text kept. {Housekeeping taken, if any, with the H6 / H7 notes from §3 below.} No VP decided; no spanning rule selected; asymmetry not accepted; O3 preparation, E3, E3b, E4, E5 not authorized |`

Line 1: `(draft r1.18)` → `(draft r1.19)`. This is the disclosed exception to "append only" (Method).

---

## 2. ARB package: does §E3 / §C row 4 need synchronization? (the human's reading)

### 2.1 Exact current text

- **§C row 4 (line 62):** `| 4 | VP10 (CB4) | DEFER the value; record "left open" before VP14 | **O2: the explicit "left open" record** (edge VP10 → VP14); O3: decision |`
- **§E2 preamble (line 131):** `Each line is a **separate** act. The human fills in the option from the register (§1) or "leave open"; this package proposes none.`
- **§E2b prerequisites (line 151, excerpt):** `| prerequisites | E1; E2-VP15 decided; E2-VP5 decided **as option (i)** (other options block this gate); E2-VP10 recorded as left open (or decided); E2-VP14 decided …`
- **§E3 prerequisites (line 164):** `| prerequisites | E1; E2b (O2 adoption); E2 for VP10, VP12, VP11 (beyond E2b's set); a prepared authorization proposal (O3) |`
- **§D row 4 (line 107):** `… it needs VP15, VP5, VP14 (O2) and VP10, VP12, VP11 (O3), and its own authorization | register §3; F-LOG-0248 |`

### 2.2 The inconsistency, stated exactly

**§C row 4.** The row contrasts "O2: the explicit 'left open' record" with "O3: decision". Read alone, it says a left-open value does not suffice for O3.
- F-LOG-0264 overrides the row by name ("notwithstanding … ARB §C row 4").
- After the ruling, the conflict is one of **legibility**, not substance.
- The amendment clause does **not** name §C row 4, so syncing it is a choice and not a requirement.

**§E3 prerequisites, "E2 for VP10".** There are two readings. Each is given at its strongest.

**Reading (a), E2 sense: no edit needed.**
1. §E2 lets an E2 act fill in "the option from the register (§1) or 'leave open'".
2. On that reading, **F-LOG-0258** (VP10 = (iii) recorded) would already have satisfied "E2 for VP10". F-LOG-0261 lists it as done: "done: E1, E2 (VP1d-H, VP15, VP5, VP10, VP14)".
3. The duty to revisit VP10 before O3 comes from F-LOG-0258 ("This value must be revisited before any O3 proposal"), not from §E3.
4. F-LOG-0263 discharged that duty. §E3 is satisfied as written.
5. "(beyond E2b's set)" can be read as attaching to VP12 and VP11, the items actually beyond E2b's set, with VP10 listed only because the revisit falls due at this gate.

**Reading (b), "requires a decision": edit needed.**
1. §E3 lists VP10 "(beyond E2b's set)".
2. E2b's own prerequisites already include "E2-VP10 recorded as left open (or decided)" (line 151).
3. Listing VP10 again as an E3 prerequisite beyond E2b's set therefore implies something **more** than the left-open record that E2b already required.
4. That reading matches §C row 4 ("O2: … 'left open' record … O3: decision"), register §3 and register §5a ("O3: yes").
5. §E3's scope ties it to the register: "Both use the same minimal VP set", i.e. register §3's "minimal VPs to decide".
6. E2-VP14 carries an explicit clause, "(an explicit "left open" record counts)". §E3 carries no such clause for VP10.
7. On this reading, an unannotated §E3 contradicts F-LOG-0264. This is the case F-LOG-0264 names: "if it is read as requiring a decision".

**Analyst note [E]:** on either reading, F-LOG-0263 together with F-LOG-0264 meets "E2 for VP10" in substance. Under (b), the annotation makes that visible in the ARB package.

**§D row 4** says "needs", with no contrast between deciding and leaving open. No edit is proposed under any option.

### 2.3 The choice inside the act (the human selects one; none is pre-selected)

| option | edits | basis |
|---|---|---|
| **A: annotate §C row 4 and §E3** | both annotations below | reading (b) for §E3. §C row 4 is synced for legibility because F-LOG-0264 overrode it by name |
| **B: annotate §E3 only** | the §E3 annotation below | reading (b). Stays strictly within F-LOG-0264's amendment clause, which names §E3, not §C row 4. §C row 4 stands as overridden by the "notwithstanding" |
| **C: add no F-LOG-0264 annotation to §C row 4 or the §E3 prerequisites, F-LOG-0264 governing** | no F-LOG-0264 annotation. The AH items in §2.4 remain separately available, except AH3's row-4 part (see §2.4) | reading (a). F-LOG-0258 already satisfied "E2 for VP10", and the revisit came from F-LOG-0258. §C row 4 is a summary of register r1.4 (§C cites "register r1.4"), kept as a historical snapshot. F-LOG-0264 governs |


**Proposed annotations (used by A and B as applicable):**
- **§C row 4, last cell:** `… ; O3: decision **[r4.4 sync] For O3, the re-affirmed VP10 = (iii) (F-LOG-0263) counts as VP10's decision, by human ruling outside the VP10 options (not on evidence; F-LOG-0264); "O3: decision" stays as recorded.**`
- **§E3 prerequisites:** `… a prepared authorization proposal (O3) **[r4.4 sync] For VP10: met by the re-affirmed (iii) (F-LOG-0263), ruled to count as VP10's decision for O3 (not on evidence; F-LOG-0264). Other prerequisites unchanged; this annotation authorizes nothing.**`
- **New change row r4.4.** It is needed whenever any ARB edit is taken: A, B, or any AH item, including C plus an AH item. It is assembled from these parts:
  > `| r4.4 | sync under human act | F-LOG-NNNN: {A: §C row 4 and §E3 annotated for F-LOG-0264 / B: §E3 annotated for F-LOG-0264 / C: no F-LOG-0264 annotation, F-LOG-0264 governing}{ (VP10 = (iii) counts for O3, by ruling, not on evidence)}{; housekeeping: AH1 (§C1 P-c condition) / AH2 (§E3 records F-LOG-0271's binding condition) / AH3 (§C decided-status marks) / AH4 (status line review state)}. Original text kept.{ If AH1 or AH2: No spanning rule selected; asymmetry not accepted.} No decision content changed; nothing authorized |`
- **Title:** line 1 `(draft r4.3)` → `(draft r4.4)`. This is the disclosed exception (Method).

### 2.4 ARB housekeeping (optional, separate from A / B / C; noted only)

These items can be taken or left with A, B or C. Any item taken goes in the r4.4 change row (§2.3); under C plus an AH item, the r4.4 row is still needed. **AH3's row-4 part (VP10) is not available under C,** because it would annotate §C row 4. Under A it merges into A's row-4 annotation. **Under B or C with AH3, row 4 (VP10) stays the only unmarked VP row in §C** among the rows AH3 marks; this is disclosed, not corrected.

| # | where (line) | current (exact) | discrepancy | proposed fix (append) |
|---|---|---|---|---|
| AH1 | §C1 row (95) | `\| H-D / H-B congeniality (disclosed, not optimized) \| H-D-congenial \| partially H-B-congenial \| neutral under 𝐑₀ \| neutral under 𝐑₀ \| neutral \|` | The P-c "neutral under 𝐑₀" lacks the condition under VP12 (i): it holds under S′ / S″ only and is H-D-asymmetric under S (F-LOG-0270, F-LOG-0271) | `[r4.4 sync] P-c under VP12 (i): neutral under 𝐑₀ only under S′ / S″; H-D-asymmetric under S; no reading selected, asymmetry NOT accepted (F-LOG-0270, F-LOG-0271)` |
| AH2 | §E3 (164–168) | prerequisites / exclusions / acceptance criteria as recorded | Does not record F-LOG-0271's binding condition | **records, adds no new criterion.** Append to the acceptance criteria: `[r4.4 sync] Recorded from F-LOG-0271 (human, verbatim): "Any O3/E3 proposal must present the relevant results under all three interpretations: S, S′ and S″. It must not silently privilege one interpretation or present the asymmetry as an accepted property of the cut."` |
| AH3 | §C rows 5 and 7 (63, 65) | `\| 5 \| VP14 (partition, or no boundary layer) \| DECIDE NOW \| O2, O3 \|`; `\| 7 \| VP4, VP12, VP11 \| DEFER to the O3 gate \| O3 \|` | Unmarked, against the r4.2 precedent. Row 1 (line 59) marks **two cells**: the item cell (`**[r4.2] DECIDED (i), F-LOG-0254**`) and the timing cell (`**[r4.2] decided (F-LOG-0254)**`). Rows 2–4 are likewise unmarked | following that precedent, append to the **item cell** and the **timing cell** of each row:<br>• row 5: item `**[r4.4 sync] DECIDED P-c, F-LOG-0265**`; timing `**[r4.4 sync] decided (F-LOG-0265)**`<br>• row 7: item `**[r4.4 sync] VP12 DECIDED (i), F-LOG-0268; VP11 DECIDED (ii), F-LOG-0269; VP4 open**`; timing `**[r4.4 sync] VP12, VP11 decided (F-LOG-0268, F-LOG-0269)**`<br>• optionally row 2: item `**[r4.4 sync] DECIDED (i), F-LOG-0255**`; timing `**[r4.4 sync] decided (F-LOG-0255)**`<br>• optionally row 3: item `**[r4.4 sync] DECIDED (i), F-LOG-0257**`; timing `**[r4.4 sync] decided (F-LOG-0257)**`<br>• optionally row 4, **only under A** (unavailable under C; outside B's scope). Merged A + AH3 row 4 becomes:<br>`\| 4 \| VP10 (CB4) **[r4.4 sync] RECORDED (iii), F-LOG-0258; re-affirmed F-LOG-0263; counts for O3 by ruling, not on evidence (F-LOG-0264)** \| DEFER the value; record "left open" before VP14 **[r4.4 sync] recorded (F-LOG-0258); re-affirmed (F-LOG-0263)** \| **O2: the explicit "left open" record** (edge VP10 → VP14); O3: decision **[r4.4 sync] For O3, the re-affirmed VP10 = (iii) (F-LOG-0263) counts as VP10's decision, by human ruling outside the VP10 options (not on evidence; F-LOG-0264); "O3: decision" stays as recorded.** \|`<br>Never "DECIDED (iii)" for VP10 |
| AH4 | status line (3) | excerpt: `Draft r4 (aligned with the verified final state and the package review; change rows at the end), **not independently reviewed**.` | Stale: `R7/REVIEW-ARB-PACKAGE.md` records four review rounds (package r1–r4, all PASS_WITH_LIMITATIONS) | append: `[r4.4 sync] "not independently reviewed" superseded: reviewed in R7/REVIEW-ARB-PACKAGE.md (package r1–r4, four rounds, all PASS_WITH_LIMITATIONS); later revisions not reviewed as a package` |

---

## 3. Housekeeping: register discrepancies against F-LOG-0263 to 0271 (separate from the F-LOG-0264 sync; each optional)

Every fix appends text tagged `[r1.19 sync]`. **Historical change rows are not edited.** H1, H3, H5 and H8 carry **conditional branches** keyed to whether the T3-SYNC act (C) and the E2b amendment act (A) were made before this act (B). Apply the branch the log supports (applier instruction, header). Where a fix concerns a change row (H6, H7), its content goes into the new r1.19 row (§1.5).

| # | where (line) | current (exact) | discrepancy | proposed fix |
|---|---|---|---|---|
| H1 | status line (3) | `Draft r1.4 (r0 + the §5a decision-timing analysis, …)` and `[r1.5] … No other VP is decided; nothing is adopted.` | The title is r1.18. "No other VP is decided; nothing is adopted" is not the current state: VP15, VP5, VP10, VP14, VP12 and VP11 are decided or recorded, and E2b is adopted (F-LOG-0261) | append: `**[r1.19 sync]** Current revision r1.19. "Draft r1.4" names the last pre-closure analyst revision; r1.5–r1.19 are amendments under human rulings or syncs (§7). Current state: VP1d-H, VP15, VP5, VP10, VP14, VP12, VP11 decided or recorded (§1); bounded model adopted by E2b (F-LOG-0261){, as amended by F-LOG-[E2b-amendment id]}; the [r1.5] sentence describes r1.5 only.`<br>Keep the braced clause **only if** the E2b amendment act was made; otherwise delete it |
| H2 | VP12 annotation (72, 153, 176) | `[r1.15] DECIDED (i), F-LOG-0268; re-check under P-c pending (R25 / H-D asymmetry disclosed)` | Stale: the re-check is done and disposed of | append: `; [r1.19 sync] re-check done, T3 r1.7 (F-LOG-0270); no spanning rule selected; asymmetry documented, NOT accepted; any O3 / E3 proposal reports S, S′, S″ (F-LOG-0271)` |
| H3 | VP14 annotation (73, 174) | `…; effect on the adopted model pending the E2b amendment; T3 re-derivation pending its own authorization]` | Stale: the re-derivation was authorized and done. The E2b status depends on act A | append: `; [r1.19 sync] re-derivation authorized (F-LOG-0266) and done, T3 r1.6 (F-LOG-0267), re-checked r1.7 (F-LOG-0270); {E2b amended by F-LOG-[E2b-amendment id] / E2b amendment pending}`<br>Use the first branch if the E2b amendment act was made, the second if not |
| H4 | VP14 consequences cell (73), `P-c: … neutral under 𝐑₀.`; §5a VP14 (174), `P-c, P-d neutral under 𝐑₀` | The condition is not stated in the VP14 cells, which state neutrality unconditionally. It appears in change rows r1.14, r1.17 and r1.18. The VP12 annotations carry only "(R25 / H-D asymmetry disclosed)" | append after each: `[r1.19 sync] P-c under VP12 (i): neutral under 𝐑₀ only under spanning readings S′ / S″; H-D-asymmetric under S (D-chg R25 crosses); no reading selected, asymmetry NOT accepted. S″ coincides with VP12 (ii) results; S′ in tension with "spanning"; F-LOG-0265 premise not withdrawn (T3 r1.7; F-LOG-0270, F-LOG-0271)` |
| H5 | VP11 consequences (71); §5a VP11 (177) | line 71: `Ten T-A rows cross groupings (R1, R2, R3, R5, R6, R8, R9, R18, R19, R22)`; line 177: `T3 §1b: ten T-A rows cross groupings (R1, R2, R3, R5, R6, R8, R9, R18, R19, R22)` | F-LOG-0269 disclosed that R14–R17 and R21 also cross groupings. The R21 gap in T3 §1b was disclosed at F-LOG-0267 / F-LOG-0269. Change row r1.16 names only "the disclosed R21 gap" | append after each, by branch:<br>• **If the T3-SYNC act was made, option (B):** `[r1.19 sync] guard rows R14–R17 and R21 also cross groupings (F-LOG-0269); R21 added to T3 §1b by F-LOG-[T3-SYNC id]; R11 added (T3 r1.8)`<br>• **If the T3-SYNC act was made, option (A):** `[r1.19 sync] guard rows R14–R17 and R21 also cross groupings (F-LOG-0269); R21 added to T3 §1b by F-LOG-[T3-SYNC id]; guard list not exhaustive; R11 open question (T3 r1.8 §6 item 1)`<br>• **If the T3-SYNC act was not made:** `[r1.19 sync] guard rows R14–R17 and R21 also cross groupings (F-LOG-0269); R21 missing from T3 §1b, disclosed at F-LOG-0267 / F-LOG-0269 (open T3 sync, F-LOG-0271 item (d))` |
| H6 | §7 r1.15 (259) | `VP12 decided (i) … with the T3 r1.6 K1 disclosure … presented before the act. No other change` | Omits the human's supplementary statement appended to F-LOG-0268 | **row r1.15 not edited.** Add to the new r1.19 row: `F-LOG-0268 appendix (human, verbatim): "**Required follow-up:** Re-check and document the H-D asymmetry before E3 proceeds. Do not treat this act as evidence that the asymmetry is acceptable or that E3 is authorized." Satisfied per F-LOG-0271.` |
| H7 | §7 r1.17 / r1.18 (261, 262) | as recorded | Omits F-LOG-0270's correction ("the F-LOG-0268 disclosure that the human acted on presented only S"). Omits F-LOG-0271's "required follow-up … satisfied" and that the human's verbatim statement governs | **rows r1.17 and r1.18 not edited.** Add to the new r1.19 row: `F-LOG-0270: the F-LOG-0268 disclosure presented only S. F-LOG-0271: F-LOG-0268's follow-up satisfied; the human's verbatim statement governs.` |
| H8 | Inputs (5–10) | excerpts: `` `R7/T3-CANDIDATE-MODEL.md` r1.5 `` …; `F-LOG-0245 to 0251` | Stale: the inputs cite T3 r1.5 and the log to F-LOG-0251 | append: `[r1.19 sync] current: T3 {r1.8 / r1.7}; log through F-LOG-[latest id]`<br>Use r1.8 if the T3-SYNC act was made, else r1.7. `F-LOG-[latest id]` is the latest entry at application, normally F-LOG-NNNN |
| H9 | status line (3) | `**Not yet independently reviewed. It decides nothing and recommends no value.**` | The first sentence is contradicted by recorded reviews:<br>• `R7/REVIEW-ARB-PACKAGE.md`: rounds on register r1.1–r1.4, all PASS_WITH_LIMITATIONS<br>• `R7/REVIEW-VP1dH-AMENDMENT.md`: the r1.5 amendment, Round 1 PASS_WITH_LIMITATIONS, Round 2 PASS<br>• the r1.7 changes were reviewed inside `R7/REVIEW-VP5-VP10-VP14-PACKAGE.md`<br>The second sentence still holds (see H10) | append: `[r1.19 sync] "Not yet independently reviewed" superseded: reviewed in R7/REVIEW-ARB-PACKAGE.md (register r1.1–r1.4, PASS_WITH_LIMITATIONS) and R7/REVIEW-VP1dH-AMENDMENT.md (r1.5), PASS_WITH_LIMITATIONS then PASS; the r1.7 changes reviewed inside R7/REVIEW-VP5-VP10-VP14-PACKAGE.md; later revisions not reviewed as a register` |
| H10 | §6 self-check (212) | `\| No VP is decided or recommended \| yes. …` | Contradicted: the register now records human decisions | append: `[r1.19 sync] the register itself decides and recommends no VP; it records the human acts recorded in §1 (F-LOG-0254, 0255, 0257, 0258, 0259, 0263, 0264, 0265, 0268, 0269)` |
| H11 | §5b (196–206) and §2 tier table (117–123) | only VP1d-H is marked: §5b `**[r1.5] DECIDED (i), F-LOG-0254**`; tier table (line 122) `[r1.5] decided (i)` (lower case) | The r1.5 precedent marks decided items. VP15, VP5, VP10, VP14, VP12 and VP11 are unmarked | **disclose or mark** (the human's choice).<br>**Disclose:** add this sentence to the new r1.19 change row (§1.5): `§5b and §2 tier table mark only VP1d-H; the decided status of the other VPs is in §1.`<br>**Mark:** append, at each VP's occurrence in §5b and in the §2 tier table:<br>• VP15: `**[r1.19 sync] DECIDED (i), F-LOG-0255**`<br>• VP5: `**[r1.19 sync] DECIDED (i), F-LOG-0257**`<br>• VP14: `**[r1.19 sync] DECIDED P-c, F-LOG-0265 (supersedes "no boundary layer", F-LOG-0259)**`<br>• VP12: `**[r1.19 sync] DECIDED (i), F-LOG-0268**`<br>• VP11: `**[r1.19 sync] DECIDED (ii), F-LOG-0269**`<br>• VP10: `**[r1.19 sync] RECORDED (iii), F-LOG-0258; re-affirmed F-LOG-0263; counts for O3 by ruling, not on evidence (F-LOG-0264)**`<br>VP10 is never marked "DECIDED" |
| H12 | VP14 consequences cell (73) | `**P-c:** the change and conformance clauses of both families internal` | **False under S:** under VP12 (i) with S, R25 (H-D D-chg) crosses (T3 r1.7; F-LOG-0270) | append: `[r1.19 sync] under VP12 (i) with spanning reading S, false for D-chg: R25 crosses; holds under S′ / S″ (F-LOG-0270, F-LOG-0271)` |

**Checked and consistent with the log:** change rows r1.11, r1.12, r1.13, r1.14 and r1.16, and the VP11 annotation.

**Cosmetic, no fix:** in §7 the column usage changes from row r1.6 onward.

(H12 is the review's item P9(d). It is numbered separately because H9–H11 were already allocated to P9(a)–(c).)

---

## 4. Draft Human ARB act

> "I, Human ARB, acting after the T3-SYNC act and the E2b amendment act in the fixed order C → A → B (each where made, as recorded in the log), authorize the consequential amendment required by F-LOG-0264, as set out in `R7/REGISTER-ARB-SYNC-PROPOSAL.md` r2.2: the register §3 O3 row, §5a VP10 row and VP10 status annotations are annotated to record that, by F-LOG-0264 (a human ruling, not evidence), the re-affirmed VP10 = (iii) (F-LOG-0263) counts as VP10's decision for the O3 minimal set, with the original text kept visible; register → r1.19. For the ARB package I choose option **[A: annotate §C row 4 and §E3 / B: annotate §E3 only / C: add no F-LOG-0264 annotation to §C row 4 or the §E3 prerequisites, F-LOG-0264 governing]**. Housekeeping items **[H1–H12, AH1–AH4 / the listed subset / none]** are authorized as text synchronizations only (AH3's §C row-4 part only under A), with H1, H3, H5 and H8 applied in the branch the log supports and every placeholder filled from the log; for H11 I choose **[disclose / mark]** (delete if H11 not taken). This act decides no variation point, changes no decision content, accepts no asymmetry and selects no spanning rule. It does not amend E2b, does not amend T3 (the R21 / F8 syncs need their own act), and does not authorize the preparation of an O3 proposal, nor E3, E3b, E4 or E5."

---

## 5. Self-check

| check | result |
|---|---|
| **§1–§2.3 reflect only F-LOG-0264** | yes. Each annotation says only that (iii) counts as VP10's decision for O3, by human ruling outside the options, "not on evidence; F-LOG-0264". The §2.4 and §3 housekeeping carries content from F-LOG-0258 to 0271; it is separate and optional. If H2, H4 or H12 is taken, the r1.19 row says "no spanning rule selected; asymmetry not accepted" (§1.5). If AH1 or AH2 is taken, the r4.4 row says the same (§2.3) |
| VP10 never labelled "DECIDED" | yes. H11 and AH3 use "RECORDED (iii), F-LOG-0258; re-affirmed F-LOG-0263; counts for O3 by ruling, not on evidence (F-LOG-0264)" |
| History kept | yes. Every change is an appended annotation. Historical change rows are never edited (H6 and H7 go into the new r1.19 row). **One disclosed exception:** the title-line version tokens (Method) |
| Revision tags | yes. All new text is tagged `[r1.19 sync]` or `[r4.4 sync]`. No earlier revision tag is used |
| ARB reading left to the human | yes. A, B and C each carry the strongest basis for their reading, and none is pre-selected. The [E] note is labelled analyst. AH1–AH4 are separate from A, B and C; C is worded so that it does not conflict with them, and AH3's row-4 part is excluded under C |
| AH2 adds no criterion | yes. It records F-LOG-0271 verbatim |
| Scope of the amendment clause respected | yes. §C row 4 is flagged as **not** named in the clause, and the prior package §2.3 is not edited |
| No VP decided; no gate authorized | yes. The act excludes O3 preparation, E3, E3b, E4 and E5, the E2b amendment and any T3 amendment |
| Quotes exact | yes, from register r1.18 and ARB r4.3 at the stated lines. Quotes marked "excerpt" or "…" are partial (H1, H3, H4, H6, H8, H10, AH4). H4 strips the bold from `**P-c:**`. H11 quotes the tier table at line 122 in its lower-case form "[r1.5] decided (i)". The human's F-LOG-0268 and F-LOG-0271 statements (H6, AH2) are quoted verbatim |
| Limits | worker draft, Claude family. r2.1 (S1–S9) and r2.2 (J1–J3) have not been re-reviewed. Placeholders (`F-LOG-NNNN`, `F-LOG-[T3-SYNC id]`, `F-LOG-[E2b-amendment id]`, `F-LOG-[latest id]`) are filled from the log at application. `F-LOG-NNNN` is a placeholder |

---

## 6. Change tables

### 6a. r0 → r1 (review of r0: PASS_WITH_LIMITATIONS)

| finding | class | fix in r1 |
|---|---|---|
| P1 | material | H2 and H3 (and all new text) now tagged `[r1.19 sync]`, with F-LOG citations inside the text. The backdated r1.14, r1.17 and r1.18 tags are removed |
| P2 | material | H6 and H7 no longer append to historical rows r1.15 and r1.18. Their content goes into the new r1.19 change row (§1.5; Method) |
| P3 | material | §2.2 restated at strength. Reading (b): "(beyond E2b's set)" versus E2b's "E2-VP10 recorded as left open (or decided)" (line 151) implies more than the record. Reading (a): F-LOG-0258 already satisfied "E2 for VP10" (F-LOG-0261 "done: … E2 (… VP10 …)"), and the revisit duty came from F-LOG-0258, not §E3. A, B and C are rebalanced, with none pre-selected |
| P4 | minor | "(not on evidence; F-LOG-0264)" added to every annotation (§1.1–§1.3, §2.3) |
| P5 | minor | act reworded: "annotated to record that, by F-LOG-0264 (a human ruling, not evidence), the re-affirmed VP10 = (iii) … counts …" |
| P6 | minor | H5 quotes line 177 separately ("T3 §1b: ten T-A rows …"). H8 now cites lines 5–10 |
| P7 | minor | H4 now cites change rows r1.14, r1.17 and r1.18. H6 quotes the human verbatim, in quotation marks, including "Do not treat this act as evidence …" |
| P8 | minor | H4 fix text extended: S″ coincides with VP12 (ii) results; S′ is in tension with "spanning"; the F-LOG-0265 premise is not withdrawn (F-LOG-0271) |
| P9 | minor | added H9 (the "Not yet independently reviewed" status), H10 (§6 line 212), H11 (§5b and §2 tier table, disclose or mark) and H12 (the VP14 "P-c … internal" clause, false under S) |
| P10 | minor | new §2.4: AH1 (§C1 line 95), AH2 (§E3, the F-LOG-0271 binding condition), AH3 (§C rows 5 and 7, plus 2–4). Optional and separate from A, B and C |
| P11 | minor | self-check scoped "§1–§2 reflect only F-LOG-0264". The r1.19 row text includes "no spanning rule selected; asymmetry not accepted" |

### 6b. r1 → r2 (review of r1: PASS_WITH_LIMITATIONS)

| finding | class | fix in r2 |
|---|---|---|
| R1 | material | Option C reworded in the act, §1.3 and §2.3 to "add no F-LOG-0264 annotation to §C row 4 or the §E3 prerequisites, F-LOG-0264 governing". AH3's row-4 part is unavailable under C, merges into A, and is outside B's scope (§2.4) |
| R2 | material | In H11 and AH3, VP10 becomes "RECORDED (iii), F-LOG-0258; re-affirmed F-LOG-0263; counts for O3 by ruling, not on evidence (F-LOG-0264)", never "DECIDED". Self-check row added |
| R3 | minor | §2.2 reading (a) item 5: "(beyond E2b's set)" may attach to VP12 and VP11. Reading (b) items 5–6: §E3's "Both use the same minimal VP set" ties it to register §3; E2-VP14 has an explicit "left open" clause and §E3 has none |
| R4 | minor | AH2 reframed as recording F-LOG-0271 verbatim, adding no criterion. The r4.4 change-row text covers A, B and C, the AH items, and C plus an AH item (§2.3) |
| R5 | minor | the title-line bumps are stated as the one disclosed exception to "append only" (Method, §1.5, §2.3, self-check), with the r1.2 precedent |
| R6 | minor | H9 now quotes the full source sentence pair. H8 is marked as an excerpt, with the backtick placement corrected. Self-check "Quotes exact" corrected |
| R7 | minor | H4 discrepancy reworded: "not stated in the VP14 cells". It notes that the VP12 annotations carry "(R25 / H-D asymmetry disclosed)" |
| R8 | minor | H9 cites `R7/REVIEW-ARB-PACKAGE.md` (register r1.1–r1.4 rounds) and `R7/REVIEW-VP1dH-AMENDMENT.md` (r1.5) |
| R9 | minor | H5: the R21 gap is "disclosed at F-LOG-0267 / F-LOG-0269" |
| R10 | minor | act adds "does not amend T3 (the R21 / F8 syncs need their own act)" and a [disclose / mark] slot for H11 |
| R11 | minor | self-check row relabelled "§1–§2.3". The r4.4 row carries "No spanning rule selected; asymmetry not accepted" whenever AH1 or AH2 is taken (§2.3). The r1.19 trigger list extended to H12 |

### 6c. r2 → r2.1 (review of r2: PASS_WITH_LIMITATIONS; no blocker, no material finding; minor fixes not re-reviewed)

| finding | class | fix in r2.1 |
|---|---|---|
| S1 | minor | H9: REVIEW-VP1dH-AMENDMENT.md recorded as "(r1.5), PASS_WITH_LIMITATIONS then PASS"; r1.7 changes noted as reviewed inside REVIEW-VP5-VP10-VP14-PACKAGE.md; "later syncs" → "later revisions not reviewed as a register" |
| S2 | minor | H6 keeps the `**Required follow-up:**` markup of the human's statement |
| S3 | minor | self-check partial-quote list adds H4, H6 (and AH4); notes H4 strips the bold from `**P-c:**` and H11's tier-table quote is the lower-case "[r1.5] decided (i)" (line 122) |
| S4 | minor | H11 "disclose" given exact text and location (a sentence in the r1.19 change row); act slot adds "(delete if H11 not taken)" |
| S5 | minor | "§C row 4 only" variant sentence dropped from §2.3 |
| S6 | minor | AH3 names target cells (item and timing cells, per row 1 at ARB line 59; the review cited line 58, the table separator) and gives the full merged A + AH3 row-4 string |
| S7 | minor | §2.4: under B or C with AH3, row 4 stays the only unmarked VP row (disclosed) |
| S8 | minor | optional AH4 added: ARB status line 3 "not independently reviewed" vs four rounds in REVIEW-ARB-PACKAGE.md; act and r4.4 row text updated to AH1–AH4 |
| S9 | minor | H10 cites "human acts recorded in §1 (F-LOG-0254, 0255, 0257, 0258, 0259, 0263, 0264, 0265, 0268, 0269)" |

### 6d. r2.1 → r2.2 (final cross-check of r2.1: PASS_WITH_LIMITATIONS; not re-reviewed)

| finding | class | fix in r2.2 |
|---|---|---|
| J1 | material | Fixed order C (T3-SYNC) → A (E2b amendment) → B (this act, last) stated in the header and the act. The housekeeping items that assumed B came first are now conditional on whether C and A were made:<br>• H1: "as amended by F-LOG-[E2b-amendment id]" if A was made<br>• H3: "E2b amended by F-LOG-[E2b-amendment id]" or "E2b amendment pending"<br>• H5: three branches. T3-SYNC (B) gives "R11 added"; T3-SYNC (A) gives "guard list not exhaustive; R11 open question (T3 r1.8 §6 item 1)"; no T3-SYNC keeps the current text<br>• H8: T3 r1.8 if C was made, else r1.7; log through F-LOG-[latest id]<br>Applier instruction added: fill every placeholder from the actual log |
| J2 | minor | H11 "mark" gives the full appended string for VP15, VP5, VP14, VP12, VP11 and VP10 (VP10 "RECORDED …", never "DECIDED"). No "e.g." fragments remain |
| J3 | minor | The T3-SYNC coordination request about R11 is met by J1 (H5's T3-SYNC option (A) / (B) branches). No further change |
