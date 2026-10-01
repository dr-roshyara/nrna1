# EQUIVALENCE: SCHEMA-r3.md (r3c) → SCHEMA-r3-CODER.md (r3c-1)

Rebuilt from scratch for r3c-1. It maps every rule sentence of `SCHEMA-r3.md` to the coder line that states it.
- "S-line" is the line number in `SCHEMA-r3.md` as it stands in this directory.
- "Coder id" is the id at the start of the line in `SCHEMA-r3-CODER.md`. Every rule line there starts with its id, so each row can be checked by putting the two lines side by side.

## Correspondence codes

- **V**: verbatim. The words are the same once these are removed: revision tags (`[r3-Gn]`, `[r1-fix-…]`, `[r2-fix-…]`, `[r3c-fix-…]`), struck-through text (`~~…~~`) and bold/italic markers. One S-sentence may be spread over several coder lines, one clause or list item per line, with its words unchanged.
- **E**: verbatim except for the example named in the Note. That example is replaced by one from the fictional allotment-garden domain (TASK item 2). The rule part of the sentence is unchanged.
- **R**: verbatim except for a cross-reference, adjusted so that it points into the coder file. The Note says what was adjusted.
- **H**: a table header or table cell of SCHEMA-r3, written out as a line. The cell's words are unchanged.
- **NIS**: a line added under TASK item 3. SCHEMA-r3 refers to something it does not contain, and the line says `NOT IN SCHEMA`. It states no rule.

What "example" means here: E is used only for quoted or named illustrations of a rule (after "e.g.", "Example(s):", "such as", or a quoted instance in parentheses) that name an item from the coded domain or copy its wording. The following stay verbatim, because they are the wording that a rule tests for: closed or open word lists (refusal verbs, D7/D9 operators, status labels, the five R-A3 lemmas and the list of unlisted synonyms, D15 markers), REPORTED-sign forms, never-disposition forms, intended-failure markers (including "RED") and non-commencement forms (including "no RED begins"). The TASK's banned wording (ruling, register, ADR, RED, WP-, matrix, Chief, ARB, Decision Authority) therefore still appears in rule lines where SCHEMA-r3 uses it as a term or a tested form (e.g. REGISTER ROW, "register ruling identifier", "no RED begins"). It does not appear in the §6 worked examples or in any replacement example.

## 1. Unit (SCHEMA-r3 §1)

| Coder id | S-line | Code | Note |
|---|---|---|---|
| U-1 | 15 | V | "(1aw)" label removed |
| U-2 | 16 | V | |
| U-2a | 16 | NIS | how lineage is established |
| U-3 | 17 | V | |
| U-4 | 17 | V | |
| U-5 | 17 | V | |
| U-6 | 18 | R | "(§5 D10)" → "(D10)" |
| U-7 | 18 | V | |
| U-8 | 18 | V | |
| U-9 | 19 | V | |
| U-10 | 19 | V | |

## 2. Components (SCHEMA-r3 §2)

| Coder id | S-line | Code | Note |
|---|---|---|---|
| F-ACT | 23 | V | |
| F-act.state | 24 | V | |
| F-act.operation.1 | 25 | V | |
| F-act.operation.2 | 25 | NIS | the v1 operation list |
| F-act.actor | 26 | V | |
| F-act.basis.1–.3 | 27 | V | |
| F-act.state_if_extended.1–.3 | 28 | V | purpose sentence "It exists so that THEORY-CHOICES TC-1 alternative 1c can be computed" not carried (see §7 below) |
| F-act.state_if_extended.4–.5 | 29 | V | |
| F-act.gated_candidates.1–.4 | 30 | V | |
| F-act.ground_candidates.1–.6 | 31 | V | purpose sentence not carried |
| F-act.attributed_to.1 | 32 | E | "RULING AS ISSUED, VERBATIM", "IN THE BOARD'S OWN WORDS", "(Principal Architect instruction)" → "TEXT AS MOVED, UNCHANGED", "IN THE COMMITTEE'S OWN WORDS", "(as dictated by the treasurer)" |
| F-act.attributed_to.2 | 32 | V | purpose sentence not carried |
| F-act.attempt_kind.1–.2 | 33 | V | |
| F-act.mechanism_refusal.1–.3 | 34 | V | purpose sentence not carried |
| F-act.later_disposition.1–.2 | 35 | V | |
| F-NORM | 37 | V | |
| F-norm.deontic.1 | 38 | V | |
| F-norm.deontic.2–.5 | 39 | V | |
| F-norm.deontic.6 | 39 | NIS | r2 meaning of PERMITTED, AUTHORIZED, UNAUTHORIZED, DELEGATED (also covers "r2 sense" in R-N3.1, "the r2 value" in R-N3.9, D7.8) |
| F-norm.about.1 | 40 | V | |
| F-norm.about.2–.3 | 41 | V | |
| F-norm.stated_by.1 | 42 | V | |
| F-norm.stated_by.2 | 43 | E | "ARB CHIEF (PREPARED)" → "TREASURER (PREPARED)" |
| F-norm.quote | 44 | V | |
| F-norm.basis.1–.2 | 45 | V | |
| F-norm.performative_token.1–.6 | 46 | V | |
| F-norm.performative_token.7 | 46 | E | "Slice 7C AUTHORIZED" → "Plot 2 bonfire request AUTHORIZED" |
| F-norm.performative_token.8–.9 | 47 | V | |
| F-norm.cited_as_ground.1–.4 | 48 | V | "(TC-5)" not carried |
| F-norm.other_clauses.1–.4 | 49 | V | |
| F-norm.indicative.1–.3 | 50 | V | "(TC-6)" not carried |
| F-norm.shared_with | 51 | V | |
| F-norm.adopted_via.1 | 52 | E | "## R-36 promotion matrix (adopted by R-36): …" → `R-36` replaced by "## R-12 plot rota table (adopted by R-12): …" → `R-12` |
| F-norm.adopted_via.2–.3 | 52 | V | "It is a join key for THEORY-CHOICES TC-2 2b only" not carried (purpose) |
| F-BOTH.1–.2 | 54 | V | |
| F-BOTH.3–.5 | 55 | V | "See THEORY-CHOICES TC-3" not carried |

## 3. Epistemic status (SCHEMA-r3 §3)

| Coder id | S-line | Code | Note |
|---|---|---|---|
| ST-1–ST-3 | 58 | V | |
| ST-4–ST-7 | 59 | V | |

## 4. Critical rules (SCHEMA-r3 §4)

| Coder id | S-line | Code | Note |
|---|---|---|---|
| R-A1.1–.2 | 62 | V | struck r2 sentence not carried |
| R-A1.3 | 62 | E | "Implementation does not begin" → "Digging does not begin" |
| R-A1.4–.5 | 62 | V | |
| R-A2.1 | 63 | V | |
| R-A2.2–.4 | 64 | V | R-A2.3 restores "a refusal expression that disposes of that proposal" (review B1) |
| R-A2.5 | 64 | E | "X would blur chronology" → "X would shade the next plot"; "with no refusal expression" kept (review row 46) |
| R-A2.6 | 64 | V | |
| R-A2.7–.10 | 65 | V | |
| R-A2.11–.12 | 66 | V | |
| R-A3.1–.2 | 67 | V | |
| R-A3.3–.5 | 68–71 | V | |
| R-A3.6 | 72 | E | "ADR-T14 is not superseded" → "the shed rule is not superseded"; the other two forms kept |
| R-A3.7–.9 | 74 | V | all twelve unlisted synonyms kept |
| R-A3.10 | 75 | V | |
| R-A3.11–.12 | 77 | V | |
| R-A3.13–.14 | 79 | V | |
| R-A3.15 | 79 | E | R-36 row example ("Promoted into AST-013 …", "Expressly NOT promoted: …", promote) → `## R-30` example ("Transferred to Mrs Hale: plot 6.", "Expressly NOT transferred: plot 7.", transfer) |
| R-A3.16 | 79 | V | "(THEORY-CHOICES TC-1)" not carried |
| R-A3.17–.20 | 80 | V | |
| R-A4.1–.2 | 81 | V | |
| R-A5.0.1 | 84 | V | |
| R-A5.0.2 | 84 | NIS | the gaps G3, G4 (GAPS.md) |
| R-A5.0.3–.4 | 84 | V | |
| R-A5.0.5 | 84 | NIS | r2 meaning of reported ACT_OCCURRED and ACT_REVERSED |
| R-A5.0.6 | 84 | V | |
| R-A5.1.1–.4 | 86 | V | struck r2 sentence not carried |
| R-A5.1.5–.9 | 87 | V | |
| R-A5.1.10 | 88 | V | |
| R-A5.1.11 | 88 | E | "documented in PB-004 retro §9", "already ARB-adopted in Handover §9", "(adoption act: R-86)" → "documented in the autumn minutes §3", "already adopted in the 2023 rule book §9", "(adoption act: minute 12)" |
| R-A5.1.12–.14 | 88 | V | |
| R-A5.1.15 | 88 | R | "under this bullet" → "under R-A5.1.10–R-A5.1.14" (the same bullet) |
| R-A5.1.16–.17 | 89 | V | |
| R-A5.1.18–.19 | 90 | V | |
| R-A5.1.20 | 90 | E | "Promotion Review ADOPTED" → "Hedge Report ADOPTED" |
| R-A5.1.21 | 90 | V | |
| R-A5.1.22 | 91 | E | "declines to resolve an ambiguity …" → *resolve* replaced by "declines to extend a tenancy …" → *extend*; other two forms kept |
| R-A5.1.23 | 91 | E | "R-90 … withdrawn" → "the bonfire motion … withdrawn" |
| R-A5.1.24 | 92 | E | `append --json='{"type":"CLOSE",…}' → refused: …` → *append*, CLOSE transition, replaced by "booked the rotavator for 3 May → refused: …" → *book*, the 3 May slot |
| R-A5.2.1–.4 | 93 | V | struck r2 sentence not carried |
| R-A5.2.5 | 94 | V | |
| R-A5.3.1–.2 | 95 | V | |
| R-A5.3.3 | 97 | H | table header |
| R-A5.3.4–.5 | 99 | H | rank 1 condition cell |
| R-A5.3.6 | 99 | H | rank 1 result cell; restores "for X" (review B1) |
| R-A5.3.7–.9 | 99 | H | rank 1 result cell |
| R-A5.3.10 | 99 | H + NIS | "ACT_REVERSED, as in r2"; the r2 conditions are not in SCHEMA-r3 |
| R-A5.3.11 | 100 | H | |
| R-A5.3.12 | 101 | H | |
| R-A5.3.13 | 102 | H | |
| R-A5.3.14–.16 | 104 | V | |
| R-A5.4.1–.8 | 105–108 | V | |
| R-A5.C1–C3 | 110 | V | |
| R-A5.C4–C5 | 111 | V | |
| R-A6.1–.3 | 114 | V | |
| R-A6.4–.6 | 115 | V | |
| R-A6.7–.8 | 116 | V | |
| R-N1.1–.2 | 119 | V | |
| R-N1.3–.4 | 120 | V | |
| R-N1.5 | 122 | E | "PROMOTE" on a named candidate behaviour → *promote*, replaced by "ALLOCATE" on a named applicant → *allocate* |
| R-N1.6 | 123 | E | "may begin RED" → "may begin sowing" |
| R-N1.7 | 124 | E | "forbids promoting from one" → "forbids subletting a plot" |
| R-N1.8 | 125 | E | "Remains Candidate" in a promotion matrix row → `status: Candidate`, not *promote*, replaced by "Remains waiting" in a waiting-list review row → `status: waiting`, not *allocate* |
| R-N2.1–.2 | 128 | V | |
| R-N2.3 | 129 | V | |
| R-N2.4 | 130 | V | |
| R-N2.5–.6 | 131 | V | full example list kept |
| R-N3.1–.3 | 134 | V | |
| R-N3.4 | 135 | V | |
| R-N3.5–.6 | 136 | V | |
| R-N3.7–.8 | 137 | V | |
| R-N3.9 | 138–141 | V | |
| R-N3.10–.11 | 143 | V | |
| R-N3.12 | 144 | V | |
| R-N3.13 | 145 | V | |
| R-N3.14–.15 | 147 | V | |
| R-S1.1 | 149 | V | |
| R-S1.2–.4 | 150 | V | |
| R-S1.5–.7 | 151 | V | |
| R-X1.1–.3 | 153–155 | V | |
| R-X1.4 | 157 | V | |
| R-X1.5 | 157 | NIS | r2 rules on other section uses |

## 5. Definitions (SCHEMA-r3 §5)

| Coder id | S-line | Code | Note |
|---|---|---|---|
| D1.1 | 161 | V | |
| D1.2 | 161 | E | "## R-36 promotion matrix (adopted by R-36): …" → "## R-12 plot rota table (adopted by R-12): …" |
| D2.1 | 163 | V | |
| D2.2 | 163 | E | "SLICE X AUTHORIZED" → "HEDGE WORK AUTHORIZED"; other two forms kept |
| D3.1–.3 | 166 | V | |
| D3.4 | 167 | V | |
| D3.5 | 168 | E | "Then R-90 itself withdrawn" → "Then the bonfire motion itself withdrawn"; "was adopted", "ruled", "has been issued" kept |
| D3.6 | 168 | E | "SLICE X AUTHORIZED" → "HEDGE WORK AUTHORIZED"; "X is adopted" kept |
| D3.7 | 169 | E | "(adoption act: R-86)", "per R-44", "in the commission's own artefacts" → "(adoption act: minute 12)", "per minute 4", "in the treasurer's own ledger" |
| D3.8 | 170 | V | |
| D3.9 | 171 | E | same three replacements as F-act.attributed_to.1 |
| D3.10–.11 | 172–173 | V | |
| D3.12–.13 | 175 | V | |
| D3.14–.16 | 177–179 | V | |
| D3.17 | 179 | E | "declines to resolve" → "declines to extend"; "a matrix cell" → "a review-table cell"; "expressly does NOT promote" → "expressly does NOT transfer" |
| D3.18 | 180 | V | |
| D4.1 | 182 | V | restores "performing X on the target" (review B1) |
| D4.2 | 183–186 | V | restores "a question put to the decider of whether to do X" |
| D4.3 | 188 | V | |
| D4.4 | 189 | V | |
| D4.5 | 190–191 | E | "target WP-4C-1" → "target the plot 12 beehive permit"; "which the adopting authority may adopt, hold or refuse" kept |
| D4.6–.7 | 193 | V | |
| D5.1 | 196 | V | |
| D5.2–.4 | 197 | V | |
| D5.5 | 198 | V | |
| D5.6–.7 | 199 | V | |
| D5.8 | 200 | V | |
| D6.1–.2 | 203 | V | |
| D6.3 | 204 | V | |
| D6.4 | 205 | V | |
| D6.5–.7 | 206 | V | struck r2 definition not carried |
| D6.8 | 207 | V | |
| D6.9–.11 | 208 | V | restores "rank 3 if it occurred" and "The intended failure is not a non-effect marker" (review row 127) |
| D6.12–.14 | 209 | V | |
| D6.15 | 210 | V | |
| D6.16–.17 | 211 | V | |
| D6.18 | 212–215 | V | |
| D7.1–.2 | 217–218 | V | |
| D7.3 | 219 | E | "do not promote" → "do not allocate" |
| D7.4 | 219 | V | |
| D7.5 | 220 | V | |
| D7.6 | 220 | E | "no self-issued act may adopt" → PROHIBITED *adopt*, actor a self-issued act, replaced by "no plot holder may sublet" → PROHIBITED *sublet*, actor a plot holder |
| D7.7–.9 | 221 | V | D7.8 adds "(NOT IN SCHEMA)" after "Those keep their r2 meaning" (NIS) |
| D8.1 | 223 | V | |
| D8.2 | 224 | E | "the register holds X" → "the rule book holds X"; the label list is kept |
| D8.3 | 225 | E | "operational acceptance", "a constitutional decision", "a SEPARATE act", "a ruling", "a PLANNING act" → "routine maintenance", "a members' decision", "a SEPARATE job", "a minute", "a courtesy notice"; "(a matrix legend category)" → "(a table legend category)" |
| D8.4 | 225 | E | "the Authority ruled it was operational acceptance" → "the committee found it was routine maintenance" |
| D8.5–.6 | 226 | V | |
| D8.7 | 227 | E | "operational acceptance" / *accept* → "routine maintenance" / *maintain* |
| D8.8–.9 | 227 | V | |
| D8.10 | 228 | V | restores "no RED begins" |
| D8.11 | 228 | E | "it APPLIES §12", "no observable behaviour changes until 7C", "Implementation continues under …" → "the rota APPLIES from May", "no watering changes until June", "Pruning continues under …" |
| D8.12 | 228 | V | "(TC-6)" not carried |
| D8.13–.14 | 229 | V | |
| D8.15–.16 | 231 | V | restores "guard" |
| D9.1 | 233 | V | |
| D10.1–.3 | 236 | V | |
| D10.4 | 237 | E | "withdrawn … because the Authority ruled …" → "withdrawn … because the committee found …" |
| D11.1 | 240 | E | "such as a promotion-matrix row" → "such as a waiting-list review row" |
| D11.2 | 241 | V | |
| D11.3 | 242 | V | |
| D11.4–.5 | 243 | V | "(TC-5)" not carried |
| D11.6 | 243 | E | "forbids promoting from one" (a ground clause in a session log) and "the register holds X" (a ground clause in R06/R07) → "forbids subletting a plot" (when a ground clause in committee minutes) and "the rule book holds X" (when a ground clause) |
| D12.1 | 246 | E | "PROMOTE", "promote to …", "NOT promoted", "do not promote" → "ALLOCATE", "allocate to …", "NOT allocated", "do not allocate" |
| D12.2–.3 | 247 | E | "PROMOTE" / "do not promote" → "ALLOCATE" / "do not allocate" |
| D12.4 | 248 | E | "Remains Candidate", "Stays in ADR-MP-06" → "Remains waiting", "Stays on list B"; "a category letter" kept |
| D12.5 | 248 | V | |
| D12.6 | 249 | V | |
| D12.7 | 250 | V | |
| D12.8–.9 | 251 | V | |
| D13.1 | 254–256 | V | |
| D13.2–.3 | 256 | V | |
| D13.4 | 257–258 | V | |
| D13.5 | 258 | E | "AUTHORIZED TO PLAN AND IMPLEMENT" → `plan + implement` replaced by "authorized to dig and plant" → `dig + plant` |
| D13.6 | 259 | E | "MAY BE PLANNED AND MUST NOT BE STARTED" → PERMITTED *plan*, PROHIBITED *start*, replaced by "may be measured and must not be fenced" → PERMITTED *measure*, PROHIBITED *fence* |
| D13.7 | 260 | V | |
| D13.8 | 260 | E | "ANNOTATE is adopted" → "FENCING is adopted" |
| D14.1 | 262 | V | |
| D15.1 | 264 | V | |
| D15.2–.3 | 265 | V | |
| D15.4 | 266 | V | struck r2 head-word list not carried |
| D15.5 | 266 | E | "GROUNDS RECORDED BY THE AUTHORITY:" → "GROUNDS RECORDED BY THE SECRETARY:"; "GROUNDS AS ISSUED:", "Basis —" kept |
| D15.6–.7 | 266 | V | |
| D15.8–.10 | 267 | V | |
| D15.11 | 268 | V | |
| D15.12 | 270 | V | |

## 6. Coder-only material

| Coder item | Status |
|---|---|
| Opening "How to read this file" | Reading instructions. They state no rule. |
| §6 worked examples (E-A01–E-A26, E-N01–E-N17) | Illustrations only. Every span is invented and comes from one fictional domain (an allotment-garden association). None uses the TASK's banned wording. Each example shows one decision path and lists the coder lines it applies. None adds a rule. E-A21 and E-A22 assign acts to records only by U-7 ("one record per (operation, target)"), R-A5.1.5–R-A5.1.7 and R-S1.2. The draft's "(by lineage)" is gone. |
| §7 output layout | Layout only. It is not in SCHEMA-r3 as a format. The field set is SCHEMA-r3 §1–§3. `span_note` is where the disclosure required by U-2 goes. |

## 7. SCHEMA-r3 text not carried into the coder file

None of the following decides a coded value:
- the change key (S-lines 1–10) and every revision tag;
- struck-through r2 text (S-lines 62, 86, 93, 206, 266);
- the label "(1aw)" (S-line 15);
- purpose clauses: "It exists so that …" (S-lines 28, 31, 32, 34) and "It is a join key for THEORY-CHOICES TC-2 2b only" (S-line 52);
- pointers to THEORY-CHOICES: "(TC-5)" (48, 243), "(TC-6)" (50, 228), "See THEORY-CHOICES TC-3" (55), "(THEORY-CHOICES TC-1)" (79).

## 8. Confirmation

- **Coverage.** Every S-line in SCHEMA-r3.md §1–§5 that carries a rule has a row above.
- **No change of rule content.** Every coder rule line is V, E, R or H. E changes only the named example. R changes only the named cross-reference. H only turns a table cell into a line.
- **No added rule.** NIS lines state only that something is missing.
- **Review B1 / non-blocking qualifiers restored verbatim:**
  - "on the target" (D4.1);
  - "for X" (R-A5.3.6);
  - "a refusal expression that disposes of that proposal" (R-A2.3);
  - "with no refusal expression" (R-A2.5);
  - "The intended failure is not a non-effect marker" and "rank 3 if it occurred" (D6.10–D6.11);
  - "guard" (D8.15), "no RED begins" (D8.10), "a question put to the decider of whether to do X" (D4.2).
- **Draft wording dropped because SCHEMA-r3 wins.** Rows 27, 50, 60 and 110 of the old EQUIVALENCE covered rewordings that cannot be checked or that differ from SCHEMA-r3. Each is now verbatim:
  - "plain meaning" → "r2 meaning" + NIS (F-norm.deontic.4–.6);
  - "(subject to A3)" removed (R-A3.1);
  - "counted, apart from decided values" → "counted, and excluded from support counts" (R-A4.2);
  - "elsewhere 'span or section' allows it" → R-X1.4 + NIS.
- **Limit of this check.** I did the check by reading only. TASK forbids commands, so nothing was diffed or word-counted by machine. The line numbers refer to SCHEMA-r3.md as read in this session.
