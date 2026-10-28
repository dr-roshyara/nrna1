# Window C34: settlement

*2026-10-28. Reviewer W (Opus). Report: `REVIEW-REPORT.md`, saved verbatim (sha256 in `SHA256-REPORT.txt`: `9898098c…1f92`). Draft `3d4d258de`. Changed theory reviewed: `4e50725e2` (the consolidation S0151–S0181, model v19) and `d149ebd07` (§9.17 (c′), the Principal's decision of 2026-10-28). Rulebook v1.4.*

"V (Professor)" marks evidence the Professor re-checked. "R" marks evidence accepted on the reviewer's reading.

## Findings and dispositions

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | **critical** | **V (Professor):** v19's guard has no owner check for designations, and `in_D` tests only that `katt` is present (code read). The reviewer's probe case is reproduced as check 32's merged case: under v19 it is in D; under v20 it is outside D | Accepted. **Model v20** (the Principal's clause permits a model version on a critical finding). (1) Every designation carries its moving application. (2) A store's owner is read from the record: `ROOT_OWNER` for the root carrier; otherwise the application that moved the record there. (3) The guard refuses a move not recorded by the owner of the old store. (4) D refuses a record that holds one, so across merged applications **a single foreign move is detected**, not only a fork. The premises are stated (ownership read from the record; the moving application trusted as declared). *Not modelled:* owner transfer; an attestation bound to the owner (T9-11). These are the Professor's technical choices, **marked for review** | §9.17 R3 (c), (c′); §9.13.2; CON-0264; T9-10, T9-11; `ce_model.py` v20 |
| W-02 | high | **V (Professor):** S0183 L159–164 (Phase 7's gate compares the source's final-state enumeration) | Accepted. C4 is restated as partial conformance: the survivor is checked at the transfer of authority, not at the irreversible removal. The divergence is recorded under CON-0265, whose verdict is unaffected | §8.27 C4, §8.27.1 item 3; register CON-0265 |
| W-03 | high | **V (Professor):** S0185 L38 ("barred only where conflict remains"); S0183 L294 | Accepted. C1 now reads "record over relay" (the C23 rule). A relay can err in both directions. T8-03's instance is grant 22 (a record-derived "fresh" requirement and act-set bars in one gate): two occurrences, one line | §8.27 C1; §8.28 C3; Ch. 8 TODO T8-03 |
| W-04 | high | R | Accepted. S0185 overstates independence. CON-0127 is linked, and the falsifiability control is marked PROVISIONAL | §8.27 C3 |
| W-05 | normal | **V (Professor):** §8.19 C13 ("a refusal downstream of this mkdir is not a refusal"); §5.7 L32 | Accepted. "Converse" is replaced by "two halves of one claim". "Worse" is attributed to the source. The link to §8.19 C7 is replaced by C13. The D claim is split (false attribution, false premise). §5.7 is cited as an earlier home. The risk is narrowed to REGISTER and HANDOFF appends | §8.27 C2, §8.27.1 item 2 |
| W-06 | normal | R | Accepted. "Provisionally certified"; the R1 link is relabelled as an analogy; the CON-0239 link is Int, with its rivals; the L6/L433 tension is recorded | §8.27 C6 |
| W-07 | normal | **V (Professor):** `git show a51be45b8` (the decision record addendum; only "record all four" is marked verbatim) | Accepted. (c′) now quotes the decision record. The chat message is labelled as such, and the polarity of "no" is explained. T9-10's completion condition (a model check of an owner refusal) is **met** by check 32; it is not waived | §9.17 (c′); T9-10 |
| W-08 | normal | R (each site re-read before editing) | Accepted. Changes: CON-0079 and §5.3 lose the stale "not yet reviewed"; CON-0264 loses "withheld" and the T9-10 items; R3 (a)'s scope now states the second route; §5.7 drops "never ran" from the body | register; §5.3; §5.7; §9.17 R3 (a) |
| W-09 | normal | **V (Professor):** check 32 prints the forged-string case (in D) | Accepted. Four limits are stated: an attestation is checked for presence, not validity; one kernel identity is modelled; the moving application is trusted as declared; check 31's "nothing promoted" is trivially true under fail-closed | §9.17 model paragraph; §9.13.2 |

**Coverage items** (the reviewer's table):
- Applied: the narrowing pool is linked to CON-0151; appointing is said to be covered by the bar only "by extension"; the `870305e0` case (W-03); falsifiability (W-04); the S0186 L6/L433 tension (W-06).
- **Left as recorded, not applied:**
  - S0183's quarantine invariant has no discharging act (evidence for CON-0250);
  - S0183 L167, L58, L229, L284 (two write classes, observational labels, trace ≠ soundness, a freeze cannot cover its own authorizing act).

  These go to the S0200 consolidation as MISSING coverage items for Ch. 6 and Ch. 8. They are not resolved here.

**Claim verdicts (reviewer):**

| Claim | Verdict |
|---|---|
| C1, C2, C6 | PROVISIONAL, restated |
| C3, C5, C7 | VALIDATED |
| C4 | INVALID as stated, restated |
| v19 completion | PROVISIONAL |
| The Professor's (c′) reading | INVALID as stated, replaced by v20 and the restated (c′) |
| The Principal's decision | not judged |

## What this settlement establishes, and what it does not

- **Established (V):**
  - v19 did not enforce the owner rule. v20 refuses a foreign move at append, and puts a merged record holding one outside D (check 32).
  - v20's checks 1–31 give output identical to v19 (`diff` of the runs, with check 32's lines removed).
  - S0183's Phase 7 gate does not take the survivor as an operand.
- **Not established:**
  - that an attestation is valid, or cannot be forged: D checks only that one is present. Check 32 shows a forged string from the owner application left in D;
  - owner transfer;
  - several kernel identities;
  - the estate's record.

## R3

- **§8.27 C1, C2, C4:** the inbound lines are in §8.28 (C3, C8) and §8.29 (C1, C3), both drafts under review. §8.28 C3 is aligned with the restated C1. The others stay true: §8.28 C8 refers to the durability concern, which C4 still addresses in part.
- **§9.17 (c′), CON-0264:** inbound lines are in §9.13.2 (aligned to v20) and the map (updated). The meaning is narrowed and completed, not reversed.
- **CON-0265:** a follow-up note was added; the verdict is unchanged.

## Checks after settlement

| Check | Result |
|---|---|
| Quote check | §8.27 0/0; §9.17 7/0 (rerun after the edits: see the session log) |
| Model v20 | all assertions pass; run `tmp/tools/ce_model-run-20261028-v20.txt` |

## Measures

| Measure | Value |
|---|---|
| Findings | 9: 1 critical, 3 high, 5 normal; all accepted |
| On the previous change (`4e50725e2`, `d149ebd07`) | 4 (W-01, W-07, W-08, W-09); on §8.27, 5 |
| Professor, reading | 61 s by start/stop records (02:23:03Z–02:24:04Z). The records mark bookkeeping, not the full reading time |
| Reviewer W | step 1: 110.3 s; steps 2–3: 557.4 s (harness) |
| Elapsed window time | from the first commit `3d4d258de` (2026-10-28T02:27:10Z) to this settlement's commit (see the session log) |
