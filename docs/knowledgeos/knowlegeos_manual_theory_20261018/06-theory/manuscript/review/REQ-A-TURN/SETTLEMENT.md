# The Req/A theory turn: settlement

*2026-11-01. The Professor. Object: §9.19 version 1 (commits `42d353c4e`, `6ae633b7d`, `c5fd16563`, `c3c1b6b09`). Reviews: three, blind-first and independent. Each step-1 answer was saved with its sha256 before any step-2 brief (`SHA256-STEP1.txt`, `716c5d2ce`, `770f4b1a8`). The three reports were saved verbatim with sha256 after all three had returned, and no reviewer saw another's report (`SHA256-REPORTS.txt`, `d7223d92e`):*
- *A: kos-lane-reviewer, Opus;*
- *B: kos-lane-reviewer, Sonnet;*
- *C: kos-theory-reviewer.*

*Result: §9.19 version 2.*

## 1. What the reviews established (theory first)

- **Agreed by all three:**
  - the proofs of T1, T3, T5 (under its conditions), T7 and T8 are adequate;
  - no "the minimal kernel" is claimed and no selection is made;
  - the commission's limits are kept;
  - **task 1's headline "five of six answered" is wrong by the section's own class definitions.** A and C rated it INVALID as stated; B rated it PROVISIONAL with two rows unsupported.
- **Version 2:** all six claims are answered in part, and none needs a new capability under EF.
- **Two questions about Req's form surfaced:** EF or enabledness (U5), and O1's cut with the D clauses for five act kinds (U6).
- **Uniqueness depends on more than the granularity:**
  - alternative routes break it (B, by a scratch model; C);
  - so does Req's cut (C);
  - a one-entry-per-input rule restores it where composites broke it (C).

  Model v2 reproduces each.
- **T5 was glossed too broadly** (A, B, C). **EF admits a one-witness system** and a halting guard (A, C). **The F-min criterion as fixed is defective** (C). **Q4's reference needs F13** (A, B, C).
- **Added:** T9 (the necessary mechanisms are ⋂𝒦; with substitutes, possibly none) and T10 (a sufficient condition for uniqueness). Both are from C.

## 2. Every finding, dispositioned

*Accepted means applied in §9.19 v2, or in the files named. R3 means the citing lines were updated: BOOK-OUTLINE L179 and L44, TODO T9-01, T9-03 and T9-04, 09.00 row 9.19, 09.99 last row, and §9.10's R3 notes.*

| ID | Disposition | Where applied |
|---|---|---|
| A-01 | accepted: §9.5 C5 in part; EF vs enabledness routed (U5); HALT probe | §9.19.2, §9.19.3, §9.19.7, U5; R3 |
| A-02 | accepted (with C-01): §9.4 C7 in part; O1 one operation; five kinds outside D (U6) | §9.19.2, §9.19.3, U6; R3 |
| A-03 | accepted (with C-03): "how to establish a capability" is a method item, a homonym; not addressed by Req | §9.19.2; R3 |
| A-04 | accepted: B scoped to K1 and one designated record; Ρ as an alternative boundary | §9.19.4, T1, T4, T7, T9 |
| A-05 | accepted (with B F8, C-04): T5 scoped to augmentation, batching, and a state meeting every requirement; T5′ not proved | §9.19.1, T5, §9.19.8; TODO T9-04 |
| A-06 | accepted: T5's premise on s₀ added (step 0) | T5 |
| A-07 | accepted (with B F2, C-10): §9.3's reason corrected to §9.10.7's Int; remainders stated | §9.19.2 |
| A-08 | accepted: N1 (ii)'s "accepted, not produced" withdrawn as a reason; non-admission rests on 1 and 2 (i) | §9.19.3 N1 |
| A-09 | accepted (with B F9, C-08): Req's core is stated without T9-02; Q4's reference needs F13; escalated (U1 with U2) | §9.19.3, §9.19.4, U1, U2 |
| A-10 | accepted (with B F6): one direction only; three senses of "necessary" | §9.19.4, §9.19.5; TODO T9-03 |
| A-11 | accepted (with C-15, C-16): "need not hold"; 𝒦 convex, not up-closed | T6; prior work |
| A-12 | accepted: the "realizes every kind" line is not support | §9.19.7; model docstring |
| A-13 | accepted (with C-12): E1 analysed with RESEARCH-PURPOSE's questions 1–3; R7 (ii) kept apart | §9.19.3 E1 |
| A-14 | accepted (with B F4): lifecycle split into grants (removal model) and items (undecided, outside D) | §9.19.5 |
| A-15 | accepted (with B F1, C-09): §6.5 C1 in part; §9.6 L191 cited; "latest" U at ties | §9.19.2 |
| A-16 | accepted (with C-17): the model's frame is stated as a choice; v2 probes added (ALT, COARSE-T, GACT, HALT) | §9.19.6 setting; `req_a_model.py` v2 |
| A-17 | accepted: bootstrap's falsifier noted as near-met; placement kept, "contested" | §9.19.2 |
| B F1 | accepted (see A-15) | — |
| B F2 | accepted (see A-07) | — |
| B F3 | accepted: the stipulations listed beside the classes; R3 note at §9.10 L144 | §9.19.2; §9.10 |
| B F4 | accepted (see A-14) | — |
| B F5 | accepted: alternative-route non-uniqueness (ALT); T10 as the positive condition | T10, §9.19.7, model |
| B F6 | accepted (see A-10); "derivable" and "composite" defined; the concept-sense homonym of "primitive" stated | §9.19.4 |
| B F7 | accepted: "as Req individuates kinds"; Req's cut named as a third choice | T4, §9.19.4 |
| B F8 | accepted (see A-05) | — |
| B F9 | accepted (see A-09) | — |
| B F10 | accepted: N1 judged against the central question; RESEARCH-PURPOSE L70 noted | §9.19.3 N1 |
| B F11 | accepted: necessity is not independence (history) | §9.19.5 |
| B F12 | accepted: "kernel-read" defined in U4; "guarded" defined in G | §9.19.4, U4 |
| B F13 | accepted: (a) F22's decision cited at §9.16 R4a; (b) the finite-pool premise in §9.19.1; (c) T6 reworded | §9.19.1, §9.19.2, T6 |
| C-01 | accepted (see A-02) | — |
| C-02 | accepted: §5.5 C3 in part (law 31 open, §9.16 R1 L81; R3a; reply to the caller). A and B had judged it answered on the kernel side. C's evidence, law 31's open status, decides it under the section's own class definition | §9.19.2 |
| C-03 | accepted (see A-03) | — |
| C-04 | accepted (see A-05); batching added to G; COARSE-T | §9.19.4, T5, model |
| C-05 | accepted: Req's cut (GACT) | §9.19.4, T1, §9.19.7 |
| C-06 | accepted: EF named as part of Req; T2, T3 (b) and T4 scoped to EF; the one-witness case beside T7 | §9.19.3, §9.19.6 |
| C-07 | accepted: the F-min criterion corrected to "removal makes some non-default answer unreachable"; R3 note at §9.10 L13. **RESEARCH-QUESTION.md is left unchanged as a record;** this settlement records the correction. *Effect on the turn:* the disproof condition fixed before the work was itself defective, and the correction was made after review | §9.19.8; §9.10 |
| C-08 | accepted (see A-09); U2 is asked before U1 | U1, U2 |
| C-09 | accepted (see A-15): the `authorized` homonym; (C + O) puts the obligations in the delegated record's contract | §9.19.2 |
| C-10 | accepted (see A-07) | — |
| C-11 | accepted: the halting case and law 20a (R6 (b)) | §9.19.2 |
| C-12 | accepted (see A-13) | — |
| C-13 | accepted: removal models labelled definitional; countermodels are arguments from non-use | §9.19.5 |
| C-14 | accepted: T9 and T10 added. T9 (i)'s bearing on §9.6.3's removal test is **recorded, not re-derived** in §9.6 (scope of this turn) | §9.19.6 |
| C-15 | accepted: Moore families and least models in prior work; "convex" | prior work |
| C-16 | accepted (see A-11) | — |
| C-17 | accepted (see A-16) | — |

**Not accepted:** none. **Partly accepted:** C-14 (ii)'s random check is reported as C's, not re-run. A's anti-exchange Hyp (step 1, primitive minimality as a closure question) was not taken up at this turn; it is recorded here as an open lead for T9-03.

## 3. Mechanical checks at version 2

- Quote check (`corpus_quote_check.py`, §9.19): 1 found, 0 not found.
- R1 (`register_home_check.py`): 241 rows with a home; 1 flag, CON-0133, inherited.
- R2 (`undefined_terms.py --scope 09.19`): tier A `appender`, defined where first used (§9.19.4 G-kind, "an appender of kind i is a mechanism that adds an entry of kind i"); the tool lists it as before. "kernel-read", "guarded", "derivable" and "composite" are now defined (B F12, A R2, B F6).
- `proof_step_check.py`: 0 scanned (headings not recognised); stated in §9.19.10.
- Model v2: v1 output unchanged; v2 probes as reported in §9.19.

## 4. Process

- No corpus source was read by the Professor. Reviewer A opened S0896 (L800–815) and S0164 (L2221–2300) after checking the allowed list. Reviewer B disclosed one grep on a disallowed path at step 1 that matched nothing and was not relied on.
- Disclosed earlier: the model ran before the question was committed (RESEARCH-QUESTION.md). Reviewer C (C-17) and reviewer A (A-16) judged the effect: the model's frame became the setting unexamined. Version 2 states that frame as a choice.
- Each reviewer was told only its own brief; no reviewer folder mentions anything beyond the review.
