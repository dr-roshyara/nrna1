# Settlement of window C22 (S0116–S0120)

*2026-10-21. By the Professor. One window reviewer, W (Opus), normal window form. Object: §8.15 (main work), the correction of §8.14 C1, the Principal's decision after C21 (§9.13 v14, model v15), §9.16 R4c. F22 was closed as a design sketch by the Principal during the review (§9.16 R4d); kernel findings are handled under that instruction: text notes and returned questions, no model version.*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt |
|---|---|---|
| W, step 1 | `STEP1-INVENTORY.md` | `f582dbb4a93a0e4d29e40473647792e1a64646e1586fbca16cb0f78ae76a716b` |
| W, steps 2–3 | `REVIEW-REPORT.md` | `e2e24c579b7e7569fbca9039d2e4ece11abe31b881fbee58ca09fed0de3da1cd` |

## 0. Deliverable status

**Met, with one critical finding, repaired.**
- S0116–S0120 were read in order, all full.
- **Timing:** each source was started at opening and closed after its theory was written (the Principal).
- §8.15 is settled as v2:
  - CON-0218 is INVALID as stated, restated as CON-0224;
  - CON-0213, CON-0219 and CON-0220 are VALIDATED;
  - CON-0223 is split, with the permitted/enabled distinction now CON-0225.

**The review:** 15 findings (1 critical, 8 high, 6 normal) and 6 material omissions.

## 1. Measures (per source; time includes writing the theory into the book)

| Source | Lines | Time | Depth (Professor / reviewer, blind) | Professor's findings | Review findings bearing on it |
|---|---|---|---|---|---|
| S0116 | 170 | 45 s | full / full | 6 | W-3 (AMD1's force), coverage |
| S0117 | 300 | 62 s | full / full | 6 | W-1 (critical), W-2, W-8, W-9 |
| S0118 | 77 | 22 s | full / full | 3 | — |
| S0119 | 77 | 21 s | full / full | 4 | coverage (residue K) |
| S0120 | 714 | 68 s | full / full (repetition-weighted) | 7 | W-3, W-7, W-12, W-13, W-14, W-15 |

- **The clock.** The times are the environment's clock. They look short for the amount written, so the clock's pace or resolution is uncertain; they are recorded as measured.
  - *Calibrated at C23 (2026-10-23, additive):* the clock is accurate. Across a model run, the wall-clock gap (25.5 s) equals the process's own elapsed time (25.53 s). So the times are real; they are short because this reader reads and writes that fast.
- **Reviewer time** (harness): step 1, 133 s; steps 2–3, 2,864 s.
- **Skim:** none, and both judgements agree.

## 2. Findings and their disposition

| ID | Sev. | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|
| W-1 | **critical** | Accepted. "Permanently" needs a snapshot premise. Restated: a history conjunct cannot be met at first realization; read on the record up to t, it can be met later (the source's own L3). CON-0218 INVALID as stated, restated as CON-0224. The source defect (S0117 L225) is listed | §8.15 C5, §8.15.4, register | **yes** |
| W-2 | high | Accepted. Repetition is necessary at the operational level, not the discriminator; CAP-01 is the counterexample; the counterfactual test discriminates | C5, CON-0224 | **yes** |
| W-3 | high | Accepted. The authority's grounds (non-realization) are kept apart from Governance's reading (the two-limb test); the order of acts is corrected; AMD1 removed a presumption, not a test | C8, CON-0213 | **yes** |
| W-4 | high | Accepted as a **stated consequence** (no model change). A consumable grant now allows exactly one act. Whether the work act is the use, and whether the consumption kind is still needed, are returned to the Principal; check 21's scope goes to T9-07 | §9.13 note; §4 | **yes**; decision with the Principal |
| W-5 | high | Recorded **beside** the Principal's statement in R4d, not rewriting it: the result is conditional on three of the Professor's choices; availability is a third difference; both records hold the uses. Returned (§4) | §9.16 R4d note | recorded; decision with the Principal |
| W-6 | high | Accepted. §9.15 K4-o and G4 reconciled with R4c (text only) | §9.15 v10 | **yes** |
| W-7 | high | Accepted. C9 restated as a rule against rewriting a status; action under an exception is allowed; the CE* parallel uses the fail-closed output (§9.13 defect 1) | C9, CON-0221 | **yes** |
| W-8 | high | Accepted. Orthogonality is the source's claim, not shown | C4, CON-0217 | **yes** |
| W-9 | normal | Accepted. Re-derivation re-runs the computation, not the record's truth | C4 | **yes** |
| W-10 | normal | Accepted. §8.14.4 and §8.14 C1's stale sentence are amended additively | §8.14 | **yes** |
| W-11 | normal | Accepted as a model defect. **No model version** (the Principal, F22 closed) | T9-07 (d) | deferred, recorded |
| W-12 | normal | Accepted. An act's force is apart from its content; open question | C6, §8.15.3 | **yes** |
| W-13 | normal | Accepted. The drift on applicability is noted | C8 | **yes** |
| W-14 | normal | Accepted. Two qualifications | C10, CON-0222 | **yes** |
| W-15 | normal | Accepted. Split into CON-0223 and CON-0225 | register, C11 | **yes** |

**Coverage omissions:**
- *Recorded in the review report and not added (low for the kernel; a later window may need them):*
  - residue K;
  - realization ≠ bounded context ≠ ownership;
  - "independent" as a homonym;
  - L2 against Option B;
  - M4 as a classification rule;
  - the E3/E4 name drift;
  - transfer versus completion in S5's block.
- *Added:* the act's force (W-12), as an open question.

**Source defects:** S0118 S-2 ("two" against three); S0120 L72 (self-contradictory); S0117 L225 ("permanently").

## 3. Checks

- **Quote check:**
  - §8.15: 32 found, 0 not found;
  - §8.14, §9.13, §9.15, §9.16: 0 not found;
  - whole book: 14, the baseline.
- **R1:** 201 rows with a home; 1 inherited flag (CON-0133).
- **Model:** unchanged (v15). Its run was re-run by the reviewer, and all assertions pass.

## 4. Returned to the Principal

1. **Is the work act itself the single use?** Your decisions make every act under a single-use grant a use, and every consumable grant single-use. So a consumable grant allows **exactly one act**: an adoption under it is already its use, and "adopt, then consume" is refused. The options:
   - **(i)** the work act is the use, and the consumption kind is then needed only for a use without work;
   - **(ii)** a work act and its consumption together count as one use.
2. **R4d's statement (W-5).** The reviewer finds that:
   - the coordination result is conditional on three of the Professor's choices;
   - there is a third difference between (C + O) and (C + K), availability (owner and kernel, against the kernel only);
   - the uses sit in both records.

   Should R4d be amended? The note stands beside your statement until you decide.

## 5. Open items

| Item | Role | Next action |
|---|---|---|
| the two questions of §4 | **Principal** | decide |
| T9-07 (F22 protocol; now also W-11, check 21's scope) | Professor | only on a critical finding or a new corpus source |
| the open forks of THEORY-MAP §9; §8.15.3 | Professor / Principal | as later sources bear on them |
| reading | Professor | C23 (S0121–S0125) |
