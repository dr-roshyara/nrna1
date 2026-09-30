# EXECUTION — what determines a standing-raising outcome?

Only SOURCES.md and TASK.md were used. The full coding is in `EXECUTION.json`. There are 30 records, E01–E30.

## 1. Events found

- **SOURCE FACT:** S1/S2: the PA adopted ES-004.3 as a documentation standard (E01). S2: the O-2 watch item was held "below the evidence bar" (E02, borderline).
- **SOURCE FACT:** S3 has two items:
  - a binding vocabulary ruling from PO/ARB (E03, borderline);
  - the three-state-planes observation, "NOT promoted to methodology — single occurrence, and ES-006.1 forbids promoting from one" (E04).
- **SOURCE FACT:** S4/S5 is the R-36 AI Architecture Promotion Review:
  - 6 items promoted into AST-013 OPERATING_INSTRUCTIONS (E05–E10);
  - the remaining matrix items not promoted, whether as Category-B follow-ups, platform docs (C), candidates (D) or already-permanent items (E11–E25).
- **SOURCE FACT:** S6 has five records:
  - the PA adopted the protocol as "the operating standard" (E26);
  - the author did not write it into a standards document, citing Phase 16 (E27);
  - the DA's decision between (a) and (b) is pending (E28);
  - the PA parked the candidate on 2026-07-26 (E29);
  - R-39, a DA early promotion recorded as an exception (E30).
- **OBSERVATION:** These were not coded as events:
  - ES-004.3's role-based refinement (same rule, "No new register row");
  - the "architecturally stable" declaration;
  - O-1, F-2 and E-1;
  - pointers in `.claude/CLAUDE.md` and MEMORY;
  - AST-008 deprecation.

  None of these raises an item into a higher-standing home.

## 2. Joining S4 and S5 (R-36)

- **OBSERVATION:** S5's Promotion Report counts match S4's categories exactly:
  - C = 4 (#5, #12, #15, #16);
  - D = 5 (#14, #17, #18, #19, and the #5 generalization);
  - B = 2 (#6, #11);
  - already = 4 (#1, #4, #8, #13);
  - promoted 6 = the 4 "A" items + 2 "added by ARB".
- **INFERENCE:** S4 is the pre-review matrix behind R-36. The two ARB additions are "stop-at-architectural-uncertainty" (S4 #7, which S4 had marked "already / No delta") and "implementation-evidence-outweighs-unverified-theory" (not in S4). So evidence comes from S4, and outcome and actor come from S5.

## 3. Eligible events

- **FORMAL CONSEQUENCE:**
  - **P** (PROMOTED, ground not CHOICE) = E01, E03, E05–E10, E26, E30.
  - **Q** (NOT-PROMOTED, RULE) = E04, E16, E18, E27.
  - Known evidence among P: E05, E06, E07 and E09 have 3 slices; E26 has 2 slices.
  - Q values:
    - E04: actor UNK, methodology, evidence 1;
    - E16: ARB, OI, evidence 1;
    - E18: ARB, standards doc, evidence UNK;
    - E27: session author, standards doc, evidence 2.

## 4. Model verdicts

| Model | Verdict | Deciding pairs | Undetermined pairs |
|---|---|---|---|
| M_E | **FALSIFIED** | (E26, E27) | 25 |
| M_A | UNDETERMINED | — | 13 |
| M_T | UNDETERMINED | — | 8 |
| M_AT | UNDETERMINED | — | 3 |
| M_C | not needed; cannot be excluded | | |

- **FORMAL CONSEQUENCE:** M_E is falsified. The same protocol at the same evidence (2 slices) was PROMOTED as the operating standard by the PA (E26). It was also NOT-PROMOTED into a standards document by the session author on a RULE (Phase 16) (E27).
- **FORMAL CONSEQUENCE:** That pair does not count under M_A, because the actors differ (PA vs the session author). It does not count under M_T either, because the targets differ (OTHER vs STANDARD-DOC). So no model other than M_E has a deciding pair.
- **FORMAL CONSEQUENCE:** M_C is not needed on the determinate pairs.
- **INFERENCE:** M_T is fragile. E01 (ES-004.3, STANDARD-DOC) has evidence UNK, but the text names at most two instances. Coded as 1 or 2, the pair (E01, E27) would falsify M_T. M_A and M_AT would still stand.
- **OBSERVATION:** Thresholds the text states:
  - ES-006.1: not "from one";
  - "2 slices is the floor" (principles doc, S4 #6);
  - ER-02: "repeated evidence";
  - the PA's criterion: "WP-1 plus a few more slices";
  - O-2: a "single instance, below the evidence bar".

  Every item promoted into OPERATING_INSTRUCTIONS with known evidence had 3 slices. Every RULE refusal with known evidence had 1 or 2.
- **HYPOTHESIS:** Outcomes are governed by who is authorized to promote, with an evidence bar (≥ 2–3) that a DA can override by recorded exception (R-39). The Phase 16 refusal (E27) is about authorization, not evidence. This is closest to M_A or M_AT. The sources cannot test it further.
- **UNKNOWN:** How the DA decides E28. Also what R-39 promoted, into which home, and at what evidence.

## 5. Sensitivity (FORMAL CONSEQUENCE)

- If E26 is not a standing-raising event, M_E becomes UNDETERMINED.
- If E01's evidence is 1 or 2, M_T becomes FALSIFIED.
- If E01's target is coded OTHER, M_T loses its E01 pairs.
- If the R-36 promotions are coded CHOICE, they are excluded as P. Undetermined pairs drop to 3 (M_A), 6 (M_T) and 1 (M_AT). No verdict changes.
- If E16's target is coded CANDIDATE-PATTERN, its M_T/M_AT pairs vanish, except (E30, E16) under M_T.
- If "PO/ARB" is treated as equal to "ARB", or E04's actor is recoded, no verdict changes.

## 6. Ambiguities and how they were coded

1. **E01 evidence.** One provenance instance existed before adoption, and a second was found the same day by the first checklist run. No count is stated, so it is coded UNK.
2. **E01 target.** The text says "permanent documentation standard" but also "not a new standard document". It is coded STANDARD-DOC because it is hosted in the ES-004 documentation standard. Coding it OTHER is tested above.
3. **E02 (O-2).** It is unclear this is a promotion event: no home is named, and the text does not literally say "not promoted". It is coded NOT-PROMOTED, borderline, with target, actor and ground UNK.
4. **E03 (vocabulary ruling).** It is a binding ruling, not a promotion into a named document. It is kept as a borderline PROMOTED/OTHER record with evidence UNK, so it only creates undetermined pairs.
5. **E04 actor.** The disposition is in the passive voice. It could be PO/ARB or the first-person author, so it is coded UNK.
6. **R-36 actor.** S5 does not say who adopted the review, but "2 added by ARB at review" and "ARB wording refinement at adoption" support ARB. The S4 author only recommended. Coded ARB.
7. **R-36 target for items whose own text names no home.** These are E11, E12, E15, E16, E19–E25. They are coded OPERATING-INSTRUCTIONS, because the review is an "AI Architecture Promotion Review" into AST-013. The alternative is CANDIDATE-PATTERN or platform doc, the home each item stayed in.
8. **Items whose own text names a home:**
   - E13 → PRINCIPLES-DOC;
   - E14 → METHODOLOGY/PRINCIPLES (the process doc);
   - E17 → OTHER ("global rule");
   - E18 → STANDARD-DOC.
9. **Category-B items (E13, E14).** "Expressly NOT promoted" in R-36, but deferred as "follow-ups" to separate slices. Coded NOT-PROMOTED for R-36; the later principles-doc or v1.1 outcome is unknown.
10. **Evidence not stated as an integer, coded UNK:**
    - "2–3" (E08);
    - "2-ish" (E17);
    - lists without counts (E21, E22);
    - E10 and E25 (no evidence given).

    "all three tickets" (E24) is coded 3 tickets.
11. **Grounds:**
    - **RULE:** only where a rule is cited as forbidding or requiring: ES-006.1 (E04); ER-02 plus the PB-005 Q1 ruling (E16); Documentation Economy (E18); Phase 16 (E27).
    - **UNK:** scope or evidence reasons with no named rule, e.g. "Messaging-scoped", "does not generalize", "duplication".
    - **CHOICE:** only the explicit (a)/(b) options (E28).
12. **PROMOTED items with ground UNK.** Treated as eligible P; only CHOICE excludes. The CHOICE alternative is tested above.
13. **E09.** S4 said "No delta" but R-36 promoted it. Coded PROMOTED; S5 is the decision.
14. **E26 vs E27.** Same protocol, two different acts: the PA adopting it as an operating standard, and the author not self-promoting it into a standards document. Coded as two records in one cluster. This is the only deciding pair in the analysis.
15. **Exception.** No text says "no exception" for any item, so every record is UNK except R-39 (YES).
16. **Undetermined pairs.** Counted only where an UNK value could make the pair falsifying. Pairs already ruled out by known values are omitted.
