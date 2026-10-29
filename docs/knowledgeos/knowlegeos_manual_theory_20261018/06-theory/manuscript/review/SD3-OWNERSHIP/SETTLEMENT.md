# Strategic-design turn 3 (ownership of invariants): settlement

*2026-10-29. Reviewers A (Opus) and B (Sonnet), commission `BRIEF.md`. Reports saved verbatim with sha256: `REPORT-A.md` (`adbd7492…f0c0a2c`), `REPORT-B.md` (`19ece914…367439bb`). Draft `0d439d268`.*

"V (Professor)" marks evidence the Professor re-checked. "R" marks evidence accepted on a reviewer's reading. The restatements are the Professor's choices, marked for review.

## Findings and dispositions

| Finding(s) | Sev. | Check | Disposition |
|---|---|---|---|
| A F-01, B B-01 | **critical** | R (the argument is internal to the draft; verified by reading L35, L160) | Accepted. The falsifier is withdrawn. §10.3.4 is restated: term-level refinement still defeats the test; at model level the test becomes S0150's pair (a choice). Sufficiency is not established. A new question has options (A), (B), (C), with consequences, recommending (B) |
| A F-02, B B-05 | high | **V (Professor):** CON-0132's register wording; §6.5.2 L29–30 | Accepted. Five exclusive relations; "preserves" split into "enforces for another" (CON-0132) and "respects" (the mandate); four statuses |
| A F-04, B B-04 | high | **V (Professor):** §6.5.2 L29–31; §9.10 L150 | Accepted. Two owner readings (R-a amendment act; R-b terms), compared; R-b applied until acts name contexts |
| A F-03, F-05, B B-02, B B-03 | high | **V (Professor):** §10.2 L120–123, L248 | Accepted. Candidate 3 enforces for another and owns none; shared and contested rows marked; placements moved from §10.2's "unplaced" are given reasons (Q2, Q5) or kept unplaced (Q6) |
| A F-06, B B-06 | high | R | Accepted. A selection rule; rows added (law 17, G6, the roles law, the grant-owner rule, CON-0231 via ARCH-INV-1, CON-0141); process rules excluded with the reason |
| A F-07, B B-11 | high | **V (Professor):** strategy §3; §10.2 ceilings | Accepted. Every row S0 (cap at the candidate) |
| A F-08 | high | **V (Professor):** §9.07 L76; §9.15 L78 | Accepted. "K5 of §9.7"; §10.2 L85 corrected |
| A F-09, B B-15 | high | **V (Professor):** §9.16 R6 | Accepted. Law 20a: (b) applications; law 22 HP: (a) |
| B B-09 | high | **V (Professor):** §9.10 L179 | Accepted. Q3 owned by 1 |
| A F-10, B B-20 | high / normal | R | Accepted. Re-grounded on S0036 L138/L143 and S0033 (weak); "laws differ" withdrawn as a ground |
| A F-12, B B-10 | normal / high | R (S0091 L70, L188) | Accepted. Law 27 contested |
| B B-13 | high | R | Accepted. Turn 2's handoffs answered or kept open (§10.3.3 end) |
| B B-07, B-08, B-12, B-14, B-16, B-17, B-19; A F-13–F-17 | normal | R | Accepted as listed in §10.3.5 |
| B B-21, A R2 | normal | R | "owner (invariant)" kept apart from "owner application" (§9.17) and "mutation owner"; "define" made operational by R-b's procedure |
| A F-19 | normal | R | Deferred to T10-08 (register the concepts after the chain) |
| B B-22 | normal | R | Question rewritten with consequences for every option, in business terms |
| B B-23, A F-11 | normal | R | S0036 restated; S0091 C-6 and the owner/enforcement table added |

**Disclosure (reviewer B):** a recursive grep printed matching lines from other review folders (outside its file list). B opened none of them and used none. One printed line suggested a reading B had already found in §6.5.2. *Recorded.*

## What this settlement establishes, and what it does not

- **Established (V):**
  - the book's operational owner reading (§6.5.2);
  - Q3's reference was decided;
  - F36's decisions for laws 20a and 22;
  - §9.7's K5 differs from §9.15's;
  - candidate 3 owns no invariant under R-b.
- **Not established:**
  - that R-b agrees with R-a;
  - any admission rule;
  - any ceiling above S0.

## For the Principal

One question (T10-05, §10.3.4): when should a candidate count as its own bounded context, (A), (B) or (C)? The recommendation is (B).

## Measures

| Measure | Value |
|---|---|
| Reviewer A (Opus), harness | 612.0 s |
| Reviewer B (Sonnet), harness | 607.1 s |
| Review total | 1,219.1 s (20 min 19 s) |
| Turn elapsed | from 09:14:44Z (start of the inventory) to this settlement's commit (see the session log) |
| Findings | A: 19 (1 critical); B: 23 (1 critical); all accepted, F-19 deferred |
