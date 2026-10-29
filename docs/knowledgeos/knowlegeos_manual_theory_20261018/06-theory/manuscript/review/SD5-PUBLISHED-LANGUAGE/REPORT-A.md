SD5 REVIEW REPORT A

Object: `06-theory/manuscript/chapter_10/10.05-published-language.md` (draft). Mode: theory-turn review. The analysis needed more than about 1,200 words, so the high findings are in the main table and the normal ones are in the appendix.

## Summary (10 lines)
1. The draft has the right shape: writes in (PL-1 to PL-3), reads out (PL-4 to PL-7), and its conditions published as part of the language (PL-8). It fixes no data format. Every line is S0.
2. Three items are INVALID as stated, because each promises more than Ch. 9 gives:
   - **PL-4** redefines Q3 as a question about a recorded act, while the book asks about a hypothetical, possibly unrecorded act.
   - **PL-4** also extends the closed T/F/U vocabulary and the G1 promise to Q2 (whose output is a list of entries) and to Q3 (not modelled).
   - **PL-2** says an entry is appended "iff" it keeps the record in D, which its own list of further guards contradicts. It also applies the owner refusal to every grant; the book applies it to single-use grants only.
   - **PL-6** promises that a second use is "prevented" without L9; the book's G4 says only the entry is prevented, and the use only under L9.
3. PL-5's mark vocabulary is incomplete: 4 of the 7 marks in §9.13.
4. PL-4 rests on CON-0221, which §10.3 excluded as a rule of the review and governance process. CON-0221's own qualification also allows a consumer's fail-closed use of U.
5. PL-1 states standing's ownership of the ten kinds as settled. In the book it is stipulated, (p2). The draft also leaves out the R6 dependency (W's future acceptance rule can turn S's answers into refusals).
6. The naming point (current authority, not standing) is handled correctly, and T10-15 is registered for it.
7. No critical finding. The corrections are local, and none touches a settled Ch. 9 result.

**Process independence:** a separate agent instance, given only the brief. It did not see reviewer B or the Professor's drafting, and was not part of either. This is process independence, not personal independence.

## Findings (high)

| ID | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| A-01 | **Q3 redefined.** PL-4 states Q3 as "is a recorded act covered by a recorded grant at a cut?". The book's Q3 takes (a, v, x, t, s): a principal, a capability, a scope, an instant and a cut. Its reference is a hypothetical act h placed after the cut. Its output is found / not found; "not found" is not "unauthorized"; separation of duties is a separate finding. It is Int and not modelled. | high | 10.05 L67; §9.10 L56–62; §9.13 L255 | Restate Q3 as the book states it, with all of these conditions, and mark its promise conditional on R5's construction. | no inbound citation outside 10.05 (PL-n: 0 hits) | PL-4 matches §9.10 Q3 |
| A-02 | **One vocabulary and one promise for three questions.** "The answer vocabulary is closed: T, F or U" and "Promise (G1)" are applied to Q1–Q3. But Q2's output is the entries about k in record order. G1 is a guarantee about CE*, which is Q1. Q3 and its marks are not modelled. G1 also holds only for Θ_f and C1 = constitute. | high | 10.05 L65–70; §9.10 L45; §9.15 L63; §9.13 L255, L342 | Give each question its own answer form and its own promise. Scope G1 to Q1 on D, for Θ_f. | as A-01 | each question carries its own answer and promise |
| A-03 | **The reasons for T and F are promised but not defined.** PL-4 publishes answers "with the acts the answer rests on". §9.10 states that as a requirement, but §9.13 says the reasons for T and F (the headline) are not yet defined, and the support used for the marks is a conservative closure. | high | §9.10 L34; §9.13 L166, L264 (row 3), L338 | State it as an unmet requirement and move it to PL-9. | as A-01 | PL-9 lists it |
| A-04 | **PL-2's "iff" is false, and its guard list is incomplete.** The book makes the append guard and the static test agree only on citations; the single-use guard has no static counterpart. Guards missing from the list: for a transfer (T at append, recorded at the kernel, of the grant's own scope, from the issuer or an authority above the issuer's scope and never from the holder, last_use); for a designation (the old store is the carrier in force, the new carrier has never been a carrier, it cites every maximal attested entry, its authority is T, it is recorded by the store's owner); a consumption only of a single-use grant; prev_use pointers. | high | 10.05 L45–50; §9.13 L37–48, L63 | Replace "iff" with: "accepted only if it keeps D and passes every guard of its kind", then list all the guards. | as A-01 | the list matches §9.13.2 |
| A-05 | **The owner refusal is overbroad.** PL-2 and PL-6 apply it to every grant. The book applies it to single-use grants only (v12; R4c). PL-2's cross-reference "(PL-5)" should be PL-6. | high | 10.05 L48, L83; §9.13 L46; §9.16 L257 | Scope the rule to single-use grants. | as A-01 | scoped |
| A-06 | **PL-6 promises more than G4 gives.** (i) G4 prevents the entry; it prevents the use only under L9. (ii) The cost is extended in the book: a use needs both the owner and the kernel available. (iii) Who may transfer ownership is left out. (iv) After a merge, a second work-act use is T and only marked; Φ does not correct it. (v) The open protocol questions are left out: T9-07, and T9-11, under which no store owner can change in the v20 model. | high | §9.15 L66; §9.16 L183, L228–231; §9.17 L166; §10.3.3 L91 | Add L9, the extended cost, who may transfer, the merge limit and T9-07/T9-11 to PL-6. | as A-01 | PL-6 matches R4a–R4d and (c′) |
| A-07 | **CON-0221 is used after §10.3 excluded it.** §10.3 excluded it as a rule of the review and governance process. Its register meaning also allows "a fail-closed use of U at the output" (§9.13 defect 1), and the display rule as written may be read to forbid that. | high | 10.05 L73, L136; 10.03 L29, L172; register CON-0221; §9.13 L262 | Ground the display rule on promise (b) and §10.3.3 C only. State that a consumer may decide fail-closed while the U is still shown as U. | 10.03 L29, L172; 10.05 L73, L136 | consistent with §10.3 |
| A-08 | **The mark vocabulary is incomplete.** PL-5 lists 4 of the 7 marks. Missing: "inconsistent recording status"; "removal not, or no longer, authorized"; "second use of a single-use grant". | high | §9.13 L45, L51, L60; §9.16 L183 | Add the three marks. | as A-01 | all 7 listed |
| A-09 | **A stipulation is shown as settled.** PL-1 says "all ten kinds are standing's" as fact. In §10.3.6 this is stipulated outside D under (p2), and the admission holds "by construction". PL-1 also leaves out T10-10 (1)'s clause that removing, annulling and designating stay with standing for every kind, and its consequence R6: under (d3), W's future acceptance rule can take a record out of D, so PL-4 then refuses. | high | 10.03 L263, L274, L288, L300–301; 10.04 L57 | Add (p2) to PL-1. Add R6 (◇ G1) to PL-8 as a condition. | none | shown as conditional |

## Verdicts

| Item | Verdict | Reason (scope: the draft as written) |
|---|---|---|
| PL-1 | PROVISIONAL | the kinds are right; fields are missing (N-03); (p2) is not shown (A-09) |
| PL-2 | INVALID as stated | the "iff", the missing guards and the overbroad owner rule (A-04, A-05) |
| PL-3 | PROVISIONAL | two fidelity slips (N-02) |
| PL-4 | INVALID as stated | Q3 redefined; one vocabulary and promise for three questions; reasons for T/F not defined (A-01 to A-03, A-07) |
| PL-5 | PROVISIONAL | 3 of 7 marks missing (A-08) |
| PL-6 | INVALID as stated | the promise exceeds G4 without L9; owner scope; omitted limits (A-05, A-06) |
| PL-7 | PROVISIONAL | the loss clause is superseded (N-01) |
| PL-8 | PROVISIONAL | R6 and (p2)–(p4) missing |
| PL-9 | PROVISIONAL | non-promises missing (N-08) |
| §10.5.3 | PROVISIONAL | conditions understated for A, M, WM and W (N-05) |
| §10.5.6 | PROVISIONAL | the result ("promised, not attested") holds; the consumer list and the reasoning need repair (N-06) |

Answers to the brief's questions:
- **Q4 (meaning, not interface):** no format is fixed. One borderline case is N-04: the promised "attestation" is a technical choice of the Professor's.
- **Q7 (traceability and levels):** every line at S0 is right. "Inherits PROVISIONAL" mixes the register's status with the stability levels (N-07).
- **Q8 (prior work):** fair overall, with three exceptions: S0036 is not "the same point" as S0039; S0039 states no general test; and S0039's criterion is applied loosely (N-06).

## Limits
- Not opened:
  - `IMPLEMENTATION-STRATEGY-PROPOSED.md` §3, `THEORY-MAP.md`, `RESEARCH-PURPOSE.md`, `EXTERNAL-SOURCES.md`, `BOOK-OUTLINE.md`, `TARGET-TOC.md`;
  - §1.14.1-12, §9.11 and §9.7, apart from L84–88 of §9.7;
  - §10.1 and §10.2, apart from searched lines;
  - §9.16 L1–58 and L259–495, apart from searched lines.
- **R1:** I could not find out which tool is "R1" without opening the rulebook, which the brief forbids. I ran `strength_scan.py` and `register_home_check.py` instead.
- `corpus_quote_check.py` found nothing to check, because the quotes in 10.05 are not in its "…" [Snnnn Lx] format. I checked the corpus quotes by hand (below).
- `ce_model.py` was not run.

---
## Appendix: normal findings

| ID | Finding | Evidence | Correction | R3 |
|---|---|---|---|---|
| N-01 | PL-7: "a recorded loss can change an answer … or is U" is superseded. Since tombstones, the values are unchanged and only the marks change. Law 21 also does not cover answering "now" across applications. | §9.13 L62 (check 23); §9.16 L79, L80, L169 | Restate the loss clause; add the "now" limit. | none |
| N-02 | PL-3: "detectable only against an outside commitment" is wrong: a loss is also detectable from the record if a surviving entry cites it or a numbered successor survives. And a tombstone keeps every field D requires, plus position and integrity result, not only the fields citations rely on. | §9.15 L74; §9.13 L60–61 | Correct both. | none |
| N-03 | PL-1's field list leaves out: the single-use mark, prev_use/last_use, the recording application, the store, the attestation, removed_by. O3's absence entries are placed with standing (§10.3.3 L106) but are neither published nor listed as not admitted. | §9.13 L29, L46, L48, L60 | Add these. | none |
| N-04 | PL-2 promises "attested by the kernel". That is the Professor's technical choice (§9.17 L164, "for review"), and D checks only that an attestation is present, not that it is valid. | §9.17 L178 limit (i) | State it as a meaning, with its premise and the presence-only limit. | none |
| N-05 | §10.5.3: the condition is "—" for A and M, but R7 is ◇ G1 and R20 is ◇. WM's R9 (◇ G11) is left out. W's vocabulary also depends on G7 and on G2, G4–G9, not on G1 alone. The D row adds "marks", which R3 and §10.3.3 C do not state. The C row leaves out that R14 is shared, and leaves out R13. | 10.04 L58–60, L64–65, L71; 10.02 L218, L223 | Mark the conditions. | none |
| N-06 | §10.5.6 and §10.5.1, on S0039's criterion: (i) S0039 L147 gives a reason for not assigning one candidate pattern, not a general test, so adopting it as a test is Int and should be labelled. (ii) "Second" is read as "separate". (iii) The consumer list leaves out AU (R2, unconditional) and M (R20). (iv) "The estate's record is not in D" shows only that no one can rely on PL-4 to PL-7 today, because Q1 refuses that record. (v) S0036 L173 is not "the same point": it is a later PARTIAL status, given because one hand-built PKS exists, with no second-consumer criterion — a change over time. | corpus lines below | Label the test Int, complete the consumer list, restate the reasoning, and correct the S0036 row. | none |
| N-07 | §10.5.4: "inherits PROVISIONAL" mixes the register's status with the stability level (S0–S3; mandate §1, §2). S0 is right, because the claims and the candidate (§10.3.3 L79) are S0. CON-0194 (PL-2, PL-7) and CON-0264 (attestation) are missing from the table. | mandate §1, §2 | Restate the reason; add the two IDs. | none |
| N-08 | PL-9 leaves out these non-promises: reasons for T and F; Q3 and its marks; propagation of a parent grant's ending (defect 2); a revocation's scope is not read; recording time is not read; refusal reasons are not shown minimal (L166); a U at a tie can hold where every resolution agrees (L265). | §9.13 | Add them. | none |
| N-09 | "G1" and "G4" are homonyms: §9.15's guarantees against §10.2.3's gaps. They are unqualified at 10.05 L41, L47, L70, L79 and L85. | 10.04 L57 qualifies the label as "§9.15 G1" | Qualify every G-label. | none |
| N-10 | Citation slips: "§9.10 L35" should be L34 (Output). PL-4 lists Q1's inputs without u, while PL-7 includes it. This is a stale pointer, not lost content. | §9.10 L33–36 | Fix both. | none |
| N-11 | Mandate §3 asks for outside literature as a benchmark; none is checked (Evans's "published language"). T10-03 is already open. | 10.05 L15–29 | Leave T10-03 open; do not let any line rest on Evans or Vernon until checked. | none |

**R2 (`--scope 10.05`): 16 candidates, all tier D, no tier A.**
- "appender" (L35): undefined, and distinct from recorder, recording application (`app`), mover and actor. Define it as the recording application.
- unforgeable, mover: defined at their first use elsewhere (§9.17, §9.13 L48). Acceptable; name (p3) as the premise behind "unforgeable".
- over-mark: glossed in place as "conservative"; acceptable.
- valid-on-intersection, suspension: carried over from §9.13; acceptable.
- frozen, answered, declare, merged, beside, cost, exactly, above, mean, keep: ordinary words.

**Quote check by hand (corpus):** all 12 anchors were found at the cited lines: S0028 L110; S0035 L178; S0039 L147; S0036 L173; S0069 L21, L62; S0062 L125; S0196 L474; S1040 L116, L155–164; S0633 L5, L538.
- Book quotes: §9.16 R4a (L223) and §9.17 (c′) (L201–202) are verbatim.
- Book anchors: §9.13 L52, L98, L327 and §9.07 L86 are correct; §9.10 L35 is off by one (N-10).
- References to a missing claim: none found.

**Strength scan:** 24 strong words. The ones judged wrong are L39 "only then", L41 "all", L59 "only" and L73 "never"; they are covered by A-02, A-09, N-02 and A-07.

## Files opened
In W:
- `06-theory/manuscript/review/SD5-PUBLISHED-LANGUAGE/BRIEF.md`
- `03-methodology/REVIEWER-INSTRUCTIONS.md`
- `03-methodology/MANDATE-STRATEGIC-DESIGN-PROPOSED.md`
- Chapter 10: `10.05` (whole), `10.04` (whole), `10.03` (whole), `TODO.md`, `10.99-change-log.md`, `10.00-index.md`
- Chapter 9: `09.13` (whole), `09.10` (whole), `09.15` (whole), `09.17` (whole), `09.16` (L59–258, plus searched lines), `09.07` (L84–88), `TODO.md` (searched lines only)
- `06-theory/CONCEPT-REGISTER.tsv` (searched)
- `02-FILES.jsonl` (searched)
- tool docstrings in `tmp/tools/`

Corpus access list: `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (searched; all nine sources below are allowed).

Corpus files, opened at the cited lines only:
- S0028 `docs/knowledgeos/2026-08-02-knowledgeos-architecture-baseline.md`
- S0035 `docs/knowledgeos/2026-08-02-knowledgeos-strategic-domain-discovery.md`
- S0039 `docs/knowledgeos/2026-08-02-knowledgeos-product-boundary-discovery.md`
- S0036 `docs/knowledgeos/KnowledgeOS_Platform_Capability_Model.md`
- S0069 `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-002-context-map-v2.md`
- S0062 `docs/knowledgeos/reviews/2026-08-17-KOS-ATTR-ARCH-001-stage2-bounded-context-confirmation.md`
- S0196 `docs/knowledgeos/architecture/20260821-2229-PKS-Current-Architecture-Baseline-Stage-2.md`
- S1040 `docs/knowledgeos/brainstorming/phase_measure_theory/20260828-130007_step-139-knowledgeos-context-map-and-integration-contracts.md`
- S0633 `docs/knowledgeos/brainstorming/phase_measure_theory/20260825-215831_refusal-research-hypothesis-must-not-become-frozen-kernel-contract.md`

## Commands and searches
- `cat` of the brief, the instructions, the mandate and 10.05; `wc` of the sections; `ls` of chapter_09 and chapter_10 and of tmp/tools
- `grep` over `09.16` (headings, R1, R4a, law 21, law 18, T9-10, held act); over `10.01` and `10.02` (promise (b), G1–G10, the class names); over chapter 9 `TODO.md` (T9-07, T9-10, T9-11); over `CORPUS-ALLOWED.tsv` and `02-FILES.jsonl` for the nine source IDs; over the register for the cited CON-IDs. A Python `csv` read of the register gave their statuses.
- `sed -n` of the cited corpus lines and of §9.07 L84–88
- `python3 -I tmp/tools/undefined_terms.py --scope 10.05` (output: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/reviewA/r2.txt`; the scan is book-wide by the tool's design)
- `python3 -I tmp/tools/corpus_quote_check.py 10.05` (output: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/reviewA/quote.txt`)
- `python3 -I tmp/tools/inbound_citations.py "§10.5" PL-1 PL-2 PL-4 PL-5 PL-6 PL-7 PL-8 PL-9 T10-15 CON-0221` (book-wide by the tool's design)
- `python3 -I tmp/tools/strength_scan.py 10.05`
- `python3 -I tmp/tools/register_home_check.py`

One command, which defined a shell function to print the corpus lines, was refused by the worktree guard. I re-ran it as plain `sed` commands.

No repository file was edited. No forbidden file was opened, and no review folder other than SD5.
