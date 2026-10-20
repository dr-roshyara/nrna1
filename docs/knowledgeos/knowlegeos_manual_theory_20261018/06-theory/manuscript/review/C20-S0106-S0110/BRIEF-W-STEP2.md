# C20, the window commission: step 2–3 brief (transition review)

*2026-10-21. This brief is sent only after your blind inventory has been saved. Your instruction stays `03-methodology/REVIEWER-INSTRUCTIONS.md`. Re-read your saved inventory first (`review/C20-S0106-S0110/STEP1-INVENTORY.md`).*

## Before (the relevant earlier sections)

- Ch. 0 §0.5 (I-7, "Supersession is a link, never an edit").
- Ch. 1:
  - §1.6 (supersession as a further act);
  - §1.13 (the grant as a pointer);
  - §1.16 (a self-contained interval).
- Ch. 8:
  - §8.10 C4 (CON-0154, "authoritative for which claim");
  - §8.12 v2 (all of it; C7 = CON-0199, C8 the record operations).
- Ch. 9:
  - §9.13 (D's kinds, I15, the marks);
  - §9.15 (P-perf, K4);
  - §9.16 R4, R4a.
- THEORY-MAP F24.

## New (what this window added or changed)

- **§8.13** `06-theory/manuscript/chapter_08/08.13-a-reference-is-not-an-assertion.md` (C1–C7), new.
- **Register** CON-0202–CON-0206 (`06-theory/CONCEPT-REGISTER.tsv`).
- **Pointers:** the Ch. 8 index row 8.13 and THEORY-MAP F24.
- **A correction found while writing:** §9.13's line on the recorder, "read by no clause … or the marks", was stale (one mark reads it since v9).
- **The Professor's step-8 file:** `review/C20-S0106-S0110/STEP8-AND-SCAN.md`. It holds:
  - **per-source time and findings**, and the skim judgement;
  - the book search;
  - the dependencies;
  - the falsification attempts (a)–(d);
  - the mechanical checks.

You also receive the **whole theory map**, `06-theory/THEORY-MAP.md`.

## Changed theory under review in this window (Rulebook §2 step 9, and the Principal's instruction)

**A. The application of F22 = (C + O)** (the Principal, 2026-10-21: "Apply this as changed theory in C20's review"). The decision, verbatim:

> "F22: (C + O). The kernel holds the contract. The contract names one owner application per grant, fixed at issuance. A change of owner is a recorded, authorized act. The kernel refuses a use of a grant not recorded by its owner. State the cost: a grant cannot be used while its owner is unavailable, unless ownership is moved. Check the single-writer / leader-per-partition pattern under practice 2, corpus first."

Read **in full**:
- §9.16 **R4a** (new) and the v5 header line;
- §9.13 v10: the grant's owner, the `transfer` kind, the guard paragraph, the owner mark;
- §9.15 v7: K4 (K4-k, K4-o) and G4;
- the §9.06 pointer; THEORY-MAP F22; CON-0191, CON-0193, CON-0194;
- EXT-090 and EXT-091 (`06-theory/EXTERNAL-SOURCES.md`);
- the model `tmp/tools/ce_model.py` **v11** (`owner_at`, `append_guard`, `append`, `marks`, check 24) and its run `ce_model-run-20261021-v11.txt` (about 40 s);
- the corpus-first sources behind R4a: S0071, S0137, S0183 (allowed; read them where R4a relies on them).

**B. The C19 settlement's changes** (`review/C19-S0101-S0105/SETTLEMENT.md` §2–§4). Check that each row marked "Holds? yes" is actually met where it says ("Resolved when"). You need not re-review the whole of §9.13–§9.16: that was commission T's work.

## Questions

**The six of Rulebook §6, on the new part (§8.13) and on A:**
1. Does it match what came before? Is it in order?
2. Is it valid mathematically?
3. Is it valid statistically?
4. Is it valid computationally?
5. Does a concept need to be explained more clearly?
6. **Coverage:** "What relevant theory, concept, distinction, assumption, or finding present in the reviewed sources is missing from the Professor's work?" Answer from your own blind inventory.

For questions 2–4, "not applicable, with a reason" is a valid answer.

**Refutation adequacy** (entry 55):
- judge each falsification attempt in the step-8 file as adequate or inadequate, with the reason;
- give the strongest counterexample or rival explanation you find yourself;
- **R4:** "adequate" for a derivation only with a proof-step table;
- **R5:** severity of a missing premise.

**On A in particular:**
- Is the Principal's decision applied **as worded, no more and no less**?
- The Professor's three readings: "use" = consumption; "recorded" for a transfer = at the kernel, rejecting S0071's "only the current owner may hand off"; the owner taken in kernel record order, not by act time. Is each technical, as claimed, or normative (then it belongs to the Principal)? Is each sound?
- Is the derived second cost (a kernel check on every use) a valid deduction from "the kernel refuses"?
- Does check 24 test what R4a claims? Find a record or append sequence where the guard admits a use not recorded by the owner, or refuses one that is.
- Are S0071, S0137 and S0183 used in their meaning? Is EXT-090's gloss within what was verified?

**On the new part in particular:**
- Is C1's "Int" with CE*'s citations sound? Or does a citation assert something (that its target exists, CON-0203's point)?
- Is C3's link to the marks (decision 2, T1-16) an interpretation the evidence allows?
- Is C4 a case for P-perf, or a governance convention?
- Is C5's "CE* agrees" true of Φ and current-authority as defined in §9.13?

**On depth (the Principal, 2026-10-21: "Apply the skim depth of v1.3 §3 honestly"):** compare your blind depth judgements with the Professor's, per source. Did any source that was closed full deserve skim, or the reverse?

## Report

About two pages, in tables, per the instructions:
- a verdict table;
- a findings table (ID W-nn · finding · severity · evidence · correction · resolved when);
- proof-step tables where R4 requires them;
- coverage;
- a summary of at most 10 lines;
- one line on process independence.

Deliver it once, through the hand-back, beginning with the line "C20 STEP 2-3: REVIEW REPORT". The Professor saves it verbatim and records its sha256 at hand-back.
