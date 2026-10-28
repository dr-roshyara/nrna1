# Consolidation S0151–S0181: integration commission (Opus)

*2026-10-28. A short cross-chapter integration review, after two independent reviews. Read and follow `REVIEWER-NOTE.md` (binding), `SCOPE.md` and `03-methodology/REVIEWER-INSTRUCTIONS.md`. The book is the copy `BOOK-UNDER-REVIEW/` and nothing else.*

## Inputs

| Input | File | sha256 at receipt |
|---|---|---|
| Report A (Opus) | `REPORT-A.md` | `fafe6c550d594e202a3a46d3939de6e5a80e57673952beba72747b867462cf34` |
| Report B (Sonnet) | `REPORT-B.md` | `273b843e07b4567f30c0f2a7a6aa173111519a2ceb477a6f33a9307b7896def0` |
| R3 lists | `R3-OUTPUT.txt` (and `SCOPE.md` §R3) | — |

## Tasks

1. **Classify every finding** of A and B: found by **both** (name the pair), **A only**, **B only**, or the two **disagree** (state the disagreement).
2. **Check each one-only finding against the copy** (and against the cited corpus lines where needed). Mark it **holds**, **holds in part** or **does not hold**, with the evidence. Give priority to:
   - A-02 (carrier reuse: guarded appends give a record outside D);
   - A-03 and B-02 (P2) (a bypass entry promoted when an accepted entry cites it);
   - B-01 (foreign classification changes no CE* value; a foreign negative act still flips a value);
   - A-04 (§9.17 R4 "D has no carriers" stale);
   - A-06 and B-11 (§4.1's independence and the recurrence rule);
   - B-17 (§5.7 C7 and F28 read through a registrar's line);
   - B-07 (an INVALID home in §8.22 C7);
   - B-08 (§8.24 C7 circular).
3. **The designation rule.** A rates the oracle-flag gap critical (A-01); B rates it high (B-02).
   - Which rating holds under the rules (F1–F4)?
   - What is the smallest premise that makes "accepted entries only" and "never promoted" true: kernel attestation (A), or a kernel-held accepted set or K5 binding (B)?
   - Does carrier reuse (A-02) need a rule ("a store is carrier at most once") or an epoch?
   - **T9-10:** A says both parts are technical. B says that whether a fork is prevented or only detected is the Principal's decision. Give your recommendation, and **name exactly which part, if any, needs the Principal**, in plain business language.
4. **Concept verdicts.** Where A and B differ, give your verdict with the reason:
   - CON-0265 (A: VALIDATED; B: PROVISIONAL);
   - CON-0079 (A: VALIDATED; B: PROVISIONAL);
   - CON-0113's restated rule (B: PROVISIONAL; B-13);
   - CON-0264 (A: (c) INVALID as stated in two clauses; B: PROVISIONAL);
   - any other.
5. **For each proposed correction, its cross-chapter consequences.** Run R3 on the copy (`inbound_citations.py --root BOOK-UNDER-REVIEW …`), and name the sections and register rows a correction would touch. Name any correction that conflicts with another.
6. **The rotated chapters:**
   - the union of A's and B's Ch. 4 and Ch. 5 defects, per chapter: A only, B only, both, union;
   - an overlap estimate, labelled indicative only, since the reviewers are not independent;
   - **any defect in Ch. 4 or Ch. 5 that both missed and you find while checking**, counted separately.
7. **The coverage tables:** where A and B mark the same inventory item differently, which is right. List the most important MISSING items to bring into the book.
8. **Process notes.**
   - Does any entry in either reviewer's file list reach a file `REVIEWER-NOTE.md` item 4 forbids, or one of the six excluded files?
   - Record B's disclosed deviations (`frozen-table.txt`, directory listings).
   - Both reviewers wrote scratch files into another session's scratchpad; record it.

## How

- **Open only:**
  - the copy;
  - the two reports and the R3 output;
  - the five window inventories and the C33 settlement named in SCOPE;
  - corpus sources on the allowed list (check each path first);
  - the tools in `W/tmp/tools/`.
- **Never:**
  - S0155, S0156, S0157, S0161, S0170, S0174, and any file not on the allowed list;
  - REVIEWER-NOTE item 4's files;
  - basis files;
  - sealed, hold-out or quarantine files; `.env*`; `_batch_input*`; `chronological-read/*`.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text outside. Edit nothing. Do not reproduce any private person's name.
- Scratch scripts only in your own scratchpad with the prefix `cons_i_`, run with `python3 -I`.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Report

Deliver once, through the hand-back, beginning "CONSOLIDATION S0151-S0181 INTEGRATION REPORT". In tables:
- the classification;
- the one-only checks;
- the designation-rule answers;
- the verdicts;
- consequences and conflicts;
- the rotated-chapter union and estimate;
- the coverage disagreements and the ranked MISSING items;
- process notes;
- a summary of at most 12 lines;
- at the end, **every file you opened and every search you ran**.
