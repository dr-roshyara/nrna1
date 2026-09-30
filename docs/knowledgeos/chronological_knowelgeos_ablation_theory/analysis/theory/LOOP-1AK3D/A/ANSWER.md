# Evidence at the adoption of ES-004.3

Sources: `SOURCES.md` (G = git commit, S1 = R-41 row, S2 = session log) and `TASK.md` only.

## 1. Decision act

- **SOURCE FACT.** S2 Decisions: "ES-004.3 adopted by explicit PA instruction (recorded R-41)". S1: "adopted as a permanent documentation standard (Principal Architect instruction)". S2 Completed: "the register records the decision event".
- **SOURCE FACT.** Who: the Principal Architect ("PA instruction"). The recording artifacts are in commit 7632b5685, which is "Co-Authored-By: Claude Fable 5".
- **SOURCE FACT.** Date: "| R-41 | 2026-07-30 |". Commit time: "2026-07-30 19:33:58 +0200".
- **UNKNOWN.** The time of day the PA instruction was given. The commit time is when the *record* was committed, not when the instruction was given.
- **INFERENCE.** The instruction comes first, then its "institutionaliz[ation]": ES-004.3 is hosted, then R-41 is appended.

## 2. Instance 1 (provenance)

- **SOURCE FACT.** It is "the WP-1 closure inconsistency (a CLOSED plan whose header still read "AUTHORIZED — execution begins…"), corrected 2026-07-30". The fix is "Work Plan ✔ (status line fixed `307b90e8e`)".
- **INFERENCE.** It was observed **before** the decision:
  - S1 labels it "Provenance".
  - S2 says "the rule generalizes the WP-1 status-line correction into permanent governance".
  - Commit 307b90e8e is cited by hash in content committed in 7632b5685, so it already existed.
- **UNKNOWN.** The exact time it was observed.

## 3. Instance 2

- **SOURCE FACT.** It is ADR-T22's row, which "still carried the issuance-time "Implementation NOT yet authorized"". It was annotated "*(status at issuance — since REALIZED by WP-1, ARB-accepted 2026-07-27; ES-004.3 annotation, decision text unchanged)*". ADR-T21's identical clause was "verified still ACCURATE (WP-3 not yet authorized) — untouched".
- **SOURCE FACT.** It was caught by the checklist run. G: "First execution of the slice-closure synchronization checklist caught ADR-T22's stale … clause". S2: "Checklist executed against WP-1 (first application)". S1: "first checklist execution the same day caught a second instance".
- **INFERENCE.** The activity needed the rule's text to exist:
  - The checklist is part of the rule (S1: "Minimum synchronization checklist at slice closure: Work Plan · CONTEXT.md · …").
  - The run is the rule's "first application".
  - The annotation is labelled an "ES-004.3 annotation".
- **UNKNOWN.** Whether the checklist could only run *after adoption*, rather than from a draft. No source says this outright.
- **SOURCE FACT.** It was found the same day and is in the same commit as the adoption record.
- **INFERENCE.** It was found *after* the decision.

## 4. Ordering: `SECOND-AFTER-DECISION` (INFERENCE)

**Decisive quotes:**
- S2 Decisions: "ES-004.3 adopted by explicit PA instruction (recorded R-41); the rule generalizes the WP-1 status-line correction into permanent governance." The stated basis is instance 1 only.
- S2: "**Checklist executed against WP-1 (first application):**". This is an application of a rule that already exists.
- S2: "ES-004.3 annotation, decision text unchanged". The fix cites ES-004.3 as its authority.
- S2 Summary: "adopted as **ES-004.3** … First checklist execution against WP-1's closure caught a second real instance."
- S1: "Minimum synchronization checklist at slice closure: …". The checklist is the rule's own content.
- S2 Completed order: "ES-004.3 hosted" → "R-41 appended" → "Checklist executed against WP-1 (first application)".

**Every quote pointing the other way:**
- S1 R-41, the decision record itself, already contains instance 2: "Provenance: … first checklist execution the same day caught a second instance (ADR-T22 row's … clause, annotated)."
- G: a single commit, 7632b5685, holds both "ES-004.3 … adopted (PA instruction, R-41)" and "ADR-T22 status annotated per first checklist run". At git level they cannot be separated, which fits `SAME-ACT`.
- S1: "| R-41 | 2026-07-30 |" and "the same day". These give only day-level granularity.
- S2: "the register records the decision event". If the decision event is the R-41 append, the R-41 text that cites instance 2 postdates the discovery of instance 2.
- S2 Summary: "PA instruction institutionalized". It gives no time for the instruction relative to any drafting of the checklist.

**Why not the alternatives:**
- **Not `SAME-ACT`.** The sources describe the adoption and the checklist run as separate steps ("R-41 appended" and "Checklist executed … (first application)"). Only the commit bundles them.
- **Not `UNDETERMINED`.** Every source that describes the checklist run treats it as applying a rule that already exists, and no source puts the run before the instruction.
- **Caveat.** No clock time is given, so this is an inference, not a stated fact.

## 5. Decision-time evidence: **1**

- **FORMAL CONSEQUENCE.** If the second instance came after the decision (§4), and "caught" means it was first discovered by that run, then only instance 1 was known when the decision was made.
- **INFERENCE.** If the "decision act" is instead read as the writing of R-41, that record cites 2 instances.
- **FORMAL CONSEQUENCE.** If the ordering is treated as UNDETERMINED, the count becomes UNK.

## Git: what it can and cannot order

**What it can order:**
- Both the adoption record and the ADR-T22 annotation were committed by "2026-07-30 19:33:58 +0200", in commit 7632b5685.
- **INFERENCE.** 307b90e8e came before 7632b5685, because 7632b5685 cites it by hash.
- **INFERENCE.** The refinement and validation sections of S2 came in later commits. The log gained only "+16" lines in 7632b5685, but S2 spans lines 1-41.

**What it cannot order:**
- The PA instruction against the checklist run. The instruction is not a git event, and both of its results sit in one commit.
- The order of the hosting, the R-41 append and the ADR-T22 annotation within the commit.
- When either instance was actually observed.

## Ambiguities

1. Which act is "the decision act": the PA instruction, ES-004.3 hosting, the R-41 append, or the commit?
2. Adopted vs. drafted: was the checklist run from adopted text or from a draft awaiting adoption?
3. "the same day" (S1) is relative to the instance-1 correction, not to the decision.
4. The sources do not say whether the bullet order in S2 "Completed" is chronological.
5. R-41's "Provenance" names both instances. It can be read as the decision's evidence base (2) or as provenance written up after the decision (1 at decision time).
6. Who ran the checklist and wrote the records (the PA, or the co-author "Claude Fable 5") is not stated. The human committer is UNK.
