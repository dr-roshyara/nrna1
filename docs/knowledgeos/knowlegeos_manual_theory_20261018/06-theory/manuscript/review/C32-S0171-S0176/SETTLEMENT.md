# Window C32: settlement

*2026-10-27. Reviewer W (Opus). Report: `REVIEW-REPORT.md`, saved verbatim (sha256 in `SHA256-REPORT.txt`). Draft `019ed5b47`; brief `92d343957`. Changed theory reviewed: `9260e6d3a` (the ruling of `7ecf5a024` applied) and `a7dc2b83d` (C31 settlement). Rulebook v1.4.*

"V (Professor)" marks evidence the Professor re-checked; "R" marks evidence that is a reading of the text, accepted on that reading.

## Findings and dispositions

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | critical | **V (Professor):** reproduced on v17. d1 (C1→C2) and d2 (C1→C3) both `(True, None)`, the record is in D, and both are T | Accepted. **Completed (the Professor's technical completion of the ruling, marked for review):** the guard requires the old store to be the carrier in force, so a fork and a stale designation are refused. D admits at most one designation per store, one chain from the root carrier with each designation reaching its predecessor by citation, and no unknown store. Model v18 check 30: fork refused, forked record outside D, stale refused, second designation accepted with the chain in D | §9.17 R3 (c), §9.13.2, CON-0264, model v18 |
| W-02 | high | R; probes as reported | Accepted. The store is a **recorded claim set by the kernel** (the carrier in force), covered by the integrity check. It is scoped against R1: it records, it does not designate. The rival (membership by down-closure stopping at the previous designation) is recorded. The three contradicting lines are fixed (R3 (c) opening, §8.19 L236, §8.23 C4). Check 30: the kernel sets the store; an unknown store puts the record outside D | §9.17 R3 (c), §8.19, §8.23 C4, model |
| W-03 | high | **V (Professor):** check 30, bypass case | Accepted. Maximality is taken over the entries the kernel accepted, so a bypass write is not forced into the members | §9.17 R3 (c), model |
| W-04 | high | R | Accepted. Check 29's scope is stated in R3 (c). Check 30 adds the reviewer's cases and two designations over 40 merge orders. Part (6) is labelled true by construction | §9.17 R3 (c) |
| W-05 | normal | **V (Professor):** `git show 7ecf5a024` | Accepted. Quotes now from the record's text, labelled "the Rule Reviewer's recommendation, adopted by the Principal ('yes record them')" | §9.17, §9.13.2, §8.22 C7, §8.23, TODOs, map, CON-0257, CON-0264 |
| W-06 | normal | R | Accepted. §8.22 C7's stale sentence and the map's turn-8 line are restated; the CON-0264 basis cell now cites v18; "do not cite one another" now reads "none reaches another"; R3 is recorded below | as listed |
| W-07 | normal | R | Accepted. The kind `observe` is withdrawn (v18); an old system's behaviour is evidence outside D. Check 29 (6) is labelled true by construction | §9.13.2, model |
| W-08 | high | **V (Professor):** CON-0260 ("defined by being regenerable") | Accepted. C6 restated: a translation is a derived claim, and the C23 rule applies to any translation. The mapping reading is marked [I]; S0173's "Business Meaning Truth" is recorded | §8.25 C6 |
| W-09 | normal | R | Accepted: "effective only if T"; "F or U"; title restated | §8.25 C2, title |
| W-10 | normal | **V (Professor):** S0175's source table (S0150 = `225329`, the IP-protection file) | Accepted | §8.25 lineage |
| W-11 | normal | R | Accepted. A synthesis's own additions are a separate, unsupported source. S0171's third reading and S0175's unsourced tag are recorded | §8.25 lineage, C1, C7 |
| W-12 | normal | R | Accepted. The three contradictions now have a home: a homonym; a proposal, not a contradiction; unstable templates | §8.25 C5, C6, §8.25.3 |

**Coverage items marked MISSING, now recorded:**
- S0172: the Rule definition uses later concepts; the other authority gaps; "rule" as a homonym; semantics first;
- S0171: brain or memory; the learning loop without retraction;
- S0173: the three truths and three templates.

The reviewer's items 8 and 9 (S0175's undeclared tag; S0173 L77 against L213) are judged minor source defects, not recorded.

**Claim verdicts (reviewer):**
- C1, C3, C5, C8 VALIDATED;
- C2, C4 PROVISIONAL (C2 restated);
- C6 INVALID as stated, restated;
- C7 VALIDATED (tag) and PROVISIONAL (scope), restated.
- The T9-09 application was PROVISIONAL with a critical gap; it is completed here and remains PROVISIONAL until the consolidation.
- The T8-04 application is VALIDATED.

## What this settlement establishes, and what it does not

- **Established (V):**
  - v17 accepted a fork and a stale designation;
  - v18 refuses both, at append and in D;
  - checks 1–28 give output identical to v17;
  - checks 29 and 30 pass (`tmp/tools/ce_model-run-20261027-v18.txt`).
- **Not established:**
  - that the carrier-in-force condition, the kernel-set store and the chain rule are what the decision intends. They are the Professor's technical completion, **marked for review**. They add conditions the record does not state, chosen because without them K2's single designated record fails. Each is listed for the consolidation;
  - that the model's records stand for the estate's.
- Passing the checks shows only that the rule as stated has the properties checked.

## R3 (changed claims: CON-0264, §8.22 C7, §8.25 C2, §8.25 C6)

74 inbound lines (outside the review folders, 56 in the book files listed by `inbound_citations.py`):
- **Updated in this settlement:** §8.19 L236; §8.22 C7; §8.23 (T8-04 line, C4); §9.13.2; §9.17; the map (turn-8 and ruling lines); TODO T8-04 and T9-09; register CON-0264 and CON-0257.
- **Kept, with reasons:**
  - **history rows** (08.99, 09.99, register history): history is not rewritten;
  - **§8.23 L19 and C2:** "projection" for derived state, which is regenerable and therefore a correct use;
  - **§8.24 C7:** "the Principal's ruling … applied after this window" stays true; the decision was adopted by the Principal;
  - **§8.26** (15 lines): cites §8.25 C7 in the sense the review validated; §8.26 is under review in C33;
  - **index rows, 09.00 and the T0-03 row:** unaffected.
- **The §8.25 filename** still contains "a-translation-is-a-projection". It is kept so no reference breaks; the title is restated.

## Checks after settlement

| Check | Result |
|---|---|
| Quote check | §8.25 3/0; §9.17 7/0; §9.13 10/0; §8.22 11/0 |
| R1 | 241 rows with a section home, 1 flag (CON-0133, information) |
| Model v18 | all assertions pass |

## Measures

| Measure | Value |
|---|---|
| Findings | 12: 1 critical, 4 high, 7 normal; all accepted. 7 concern the previous change (`9260e6d3a`), 5 concern §8.25 |
| Defects found after a review passed | W-01 (the ruling's application, reviewed for the first time here) |
| Professor, reading | 1 min 8 s by start/stop records |
| Reviewer W | step 1: 130.5 s; steps 2–3: 380.4 s (harness; cumulative 510.9 s) |
| Elapsed window time | first commit `019ed5b47` (17:42:14Z) to this settlement's commit (see the session log) |
