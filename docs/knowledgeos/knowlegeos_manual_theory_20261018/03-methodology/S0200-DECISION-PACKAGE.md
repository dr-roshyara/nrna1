# The S0200 decision package (FOR DECISION)

| Field | Value |
|---|---|
| Status | **DECIDED 2026-10-29:** "accept as recommended" — D1–D11 as recommended (decision record, addendum 2026-10-29T02:08:48Z) |
| Prepared by | the Governance Engineer, 2026-10-29 |
| Evidence base | consolidation S0182–S0200: stage 1 `8faab7396`, addendum `1d10e28da`, stage 2 `0ae02e66b`; test result `e5da8af5a`; C32 reconstruction `66241dd81`; mandates revision 1 `64e6e8603` |

*Labels: **verified** (checked by the Governance Engineer) · **reported** (the Professor's figures, not reconstructed) · **inferred** · **proposed** · **open**.*

## 1. Where the research stands at S0200

| Measure | Value | Label |
|---|---|---|
| Corpus read in order | S0001–S0200 (≈ 7 % of 2,734 allowed sources) | verified (read log) |
| Concepts | 265: **52 VALIDATED** (47 at S0181), 117 PROVISIONAL, 96 INVALID | verified (register) |
| Consolidation S0182–S0200 | 4 reviewers, 65 min 16 s of review (≈ 6 h last time); 45 defects in rotated Ch. 6 and Ch. 8; none critical; stage 2 in 9 min 56 s | reported |
| Planted-gap test | **2 of 4** (blind and combined); R3 line by line found a stale citation for the first time; the ordinary-word class missed for the fifth time; a removed claim misdiagnosed | verified (`PLANTED-GAP-RESULT.md`) |
| Critical finding reconstructed (C32 fork) | real, severity right; the current rule closes it in a bounded exhaustive search; the search also re-found the later defect A-02 | verified (`C32-RECONSTRUCTION/RESULT.md`) |
| Stage 2 | plants not applied (C7 and its index row intact; §6.7 C1 correct; no "keel"); register counts; Ch. 8 change log restored in full (49 lines + 2) | verified |

**Self-reported defect (the Professor):** a C37 settlement script emptied the Ch. 8 change log (`64186ddd0`); found by reviewer A, restored in stage 2. Verified restored. *(Inferred: scripts that rewrite a file should read it before opening it for writing; no rule proposed.)*

## 2. Decisions, one at a time

### D1. The consolidation S0182–S0200 and its test
Accept the consolidation as settled, and the test result as scored (reviewer A's report counted both ways: blind 2 of 4, combined 2 of 4).
**Recommended: accept.**

### D2. P1 — the word checker (R2) shows the sections under review first
Its list had 687 lines and nobody judged them; restricted to the scope, tier A had 4 words, "keel" among them. A tool change (`--scope`), each listed word judged by the reviewer; no rule change.
**Recommended: yes.**

### D3. P2 — a reference to a missing claim is checked as possible lost content
One line in `REVIEWER-INSTRUCTIONS.md`: before treating a reference to a non-existent claim as an index error, check the window's inventory and sources.
**Recommended: yes.**

### D4. P3 — test preparation
No file that mentions a test is placed in a folder reviewers are sent to; every commission lists the files it may open (the Professor's commission C already does).
**Recommended: yes** (working practice of the Governance Engineer and the Professor; no rule change).

### D5. The consolidation design used at S0200
Keep: concept verdicts limited to the concepts homed in scope; stage 1 a document only; rotation of older chapters; a planted-gap test every second consolidation.
**Recommended: keep.** *(Evidence: cost fell from ≈ 6 h to ≈ 65 min with 45 defects found; the coverage gap it opened was closed by one extra reviewer in 14 min.)*

### D6. Who commissions the consolidation and test reviewers (point 5, proposed 2026-10-28)
| Option | For | Against |
|---|---|---|
| a. The Governance Engineer commissions | the author of the work does not choose its reviewers' scope | slower; puts the Governance Engineer into the research loop |
| **b. The Professor commissions, from a fixed brief template the Governance Engineer maintains** (file list mandatory; scope taken from the rules; the Governance Engineer sees each brief) | fast; this test's access problem came from a missing file list, which a template fixes | the author still commissions |
| c. As now | no work | the access problem can recur |

**Recommended: b.**

### D7. Implementation strategy, part A — research and test integrity
The strategy opens no new route by which excluded material could influence the theory or a test, and approving it grants **no access** beyond the allowed list.
**Recommended: approve.**

### D8. Implementation strategy, part B — conditional authorisation
Approve the two-track strategy **in principle** (stability levels S0–S3, gates as conditions, tests never promote a claim, stop conditions).
**Not yet ready:** the stage-0 **implementation-candidates list** has not been prepared (it was to be my deliverable once the strategy was approved). Stage 1 (the executable reference model) therefore **cannot start at S0200**.
**Recommended:** approve in principle; I prepare the candidates list next, the Professor confirms each claim's status, and stage 1 comes back to you as a separate decision with the list.

### D9. The role names
Principal (human ARB) · Governance Engineer · Professor (Research Lead) · Independent Reviewers · Verification Auditor · Implementation Engineer (`IMPLEMENTATION-STRATEGY-PROPOSED.md` §0).
**Recommended: approve.**

### D10. The strategic-design mandate, revision 1
Five elements, a chain of five theory turns, correctness before budget, no technical interfaces, starting now that the S0200 consolidation is settled.
**Recommended: approve.** *(Timing: the Professor may prefer to finish the next reading window first; the mandate allows it to start at once.)*

### D11. The Verification Auditor mandate, revision 1
Part A (inventory, design against code, audit plan by element type) may start on approval; part B needs a reviewed strategic-design baseline **and** your separate authorisation.
**Recommended: approve; start part A in a new session.**

## 3. How to answer

"**Accept as recommended**" approves D1–D11 as recommended above (D6 option b; D8 in principle only). Any item can be changed by number. Only what you approve is recorded, and the record lists what was recorded.
